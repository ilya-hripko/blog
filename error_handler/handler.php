<?php

$DIR = dirname(__FILE__);
// Get config
require_once ($DIR.'/config.php');

if ($HK_ERR_HANDLER_ON) {
    // DBA functions
    require_once ($DIR.'/dba_functions.php');
    require_once ($DIR.'/custom_debug_bugtrace.php');

    // -----------------------------------------------------------------------------

    set_error_handler('hkeh_error_handler');
    register_shutdown_function('hkeh_fatal_error_shutdown_handler');

} // error handler off


    function hkeh_error_handler ($code, $message, $file, $line) {

       // config
       global $HK_ERR_HANDLER_CONF, $HK_ERR_HANDLER_ENV;
       $CONF = $HK_ERR_HANDLER_CONF[$HK_ERR_HANDLER_ENV];

       // Skip not allowed errors
       if ( ( $code & error_reporting() ) != $code )
         return true;

       // Skip error about "header already sent"
       if ($CONF['turn_off_header_alredy_sent_reporting'] && strpos($message, 'Cannot modify header information') !== false)
         return true;

       $error_type = hkeh_get_code_label($code);

       // get error from database
       $db_error = $flags = eh_get_error($line, $file);
       if (time() - $db_error['created'] > 300) {
          $counter = $db_error['counter'] + 1;
          $db_error['created'] = time();
          $db_error['counter'] = 1;
       } else
         $db_error['counter'] += 1;

       if ($db_error['counter'] != 1)
         $flags = false;

       // Save to database
       eh_update_error($line, $file, $db_error);

       if ($CONF['dest']['log'])
         hkeh_to_log($error_type, $message, $file, $line, $CONF['error_log_file']);
       if ($CONF['dest']['browser'])
         hkeh_to_browser($error_type, $message, $file, $line, $flags);
       if ($CONF['dest']['email'])
         hkeh_to_email($error_type, $message, $file, $line, $CONF['emails'], $flags);
    }

    function hkeh_to_log($error_type, $message, $file, $line, $dst_log_file) {
       $message = date('Y-m-d H:i:s').": $error_type\terror; file: $file; line: $line; message: $message\r\n";
       error_log($message, 3, $dst_log_file);
    }

    function hkeh_to_email($error_type, $message, $file, $line, $emails, $flags) {
      // Skip if not first time for last 5 min
      if (!$flags)
        return false;
      
      $msg = hkeh_email_message($error_type, $message, $file, $line, $flags);

      //echo DOCUMENT_ROOT;
      if($emails['use_smtp']){
          require_once 'PHPMailer/PHPMailerAutoload.php';
          $mail = new PHPMailer();
          $mail->isSMTP();
          $mail->SMTPDebug = $emails['debug'];
          $mail->SMTPAuth = 'login';
          $mail->Host = $emails['smtp_server'];
          $mail->Port = $emails['smtp_port'];
          $mail->Username = $emails['smtp_username'];
          $mail->Password = $emails['smtp_password'];
          $mail->setFrom($emails['from']);
          $mail->Subject =  $emails['subject'].'. UID: '.uniqid();
          $mail->Body = $msg;
          $mail->isHTML(true);
          foreach(explode(',', $emails['recepients']) as $recepient)
            $mail->addAddress($recepient);
          $mail->Send();
      }else{
        $test = mail($emails['recepients'], $emails['subject'].'. UID: '.uniqid(), $msg, "From: ".$emails['from']."\r\nContent-Type: text/html; charset=ISO-8859-1\r\n");
      }
//      var_dump($test); die();
     // error_log($msg, 1, $emails['recepients'], "Subject: ".$emails['subject']."\nFrom: ".$emails['from']."\nContent-Type: text/html; charset=ISO-8859-1");
    //
    //  mail($emails['recipients'], $emails['subject'], $msg)
    //  error_log($msg, 1, )
    }

    function hkeh_to_browser($error_type, $message, $file, $line, $flags=false) {
       $output = hkeh_browser_message($error_type, $message, $file, $line, $flags);
       echo $output;
    }

    function hkeh_fatal_error_shutdown_handler() {
      $last_error = error_get_last();
      if ( in_array($last_error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING)) )
        hkeh_error_handler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
    }

    function hkeh_get_code_label($error_code) {
       $codes = array(
              'E_ERROR'             => 1,
              'E_WARNING'           => 2,
              'E_PARSE'             => 4,
              'E_NOTICE'            => 8,
              'E_CORE_ERROR'        => 16,
              'E_CORE_WARNING'      => 32,
              'E_COMPILE_ERROR'     => 64,
              'E_COMPILE_WARNING'   => 128,
              'E_USER_ERROR'        => 256,
              'E_USER_WARNING'      => 512,
              'E_USER_NOTICE'       => 1024,
              'E_STRICT'            => 2048,
              'E_RECOVERABLE_ERROR' => 4096,
              'E_DEPRECATED'        => 8192,
              'E_USER_DEPRECATED'   => 16384,
              'E_ALL'               => 32767
       );

       $res = false;

       foreach ($codes as $cur_error_key => $cur_error_val)
         if ($cur_error_val == $error_code)
           break;
       return $cur_error_key;
    }

    function hkeh_browser_message($error_type, $message, $file, $line, $flags=false) {
       // For firebug
       $output = "\n\n<div style='display:none;'>\n\nError Message.\n\nType: $error_type\nLine: $line\nFile: $file\nMessage: $message\n\n</div>\n\n";
       // For browser
       $output .= "<div style='border:1px solid gray; background: #FFFFFF; color: #000000;'>
<b>Error Message.</b><br/>
<b>Type:</b> $error_type <br/>
<b>Line:</b> $line <br/>
<b>File:</b> $file <br/>
<b>Message:</b> $message <br/>
</div>";
       echo $output;
       echo "<pre>"; print_r(hkeh_debug_backtrace('hkeh_shortest_backtrace', 2)); echo "</pre>";
    }

    function hkeh_email_message($error_type, $message, $file, $line, $flags=false) {
       $font_size = '12';

       $key_color = 'black';
       $msg_color = 'black';

       $key_bg = '#fff8b0';
       $msg_bg = '#cde8ff';

       $key_bg_2 = '#fff8b0';
       $msg_bg_2 = '#cde8ff';

       $border_color = 'gray';

       $counter_msg = !$flags ? 'N/A' : $flags['counter'].' times (from '.date('Y-m-d H:i:s', $flags['created']).')';

       ob_start();
       print_r(hkeh_debug_backtrace('hkeh_shortest_backtrace', 2));
       $backtrace = ob_get_contents();
       ob_end_clean();

       $output = "
          <table border='0' cellpadding='3' cellspacing='0' style='border-collapse: collapse;'>
            <tr>
              <td bgcolor='$key_bg' align='right' valign='top' style='border: 1px solid gray; padding: 3px 3px 3px 3px !important;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$key_color}'><b>Error:</b></font></td>
              <td bgcolor='$msg_bg' align='left' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$msg_color}'>$error_type</font></td>
            </tr>
            <tr bgcolor='$key_bg_2'>
              <td bgcolor='$key_bg_2' align='right' valign='top' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$key_color}'><b>File:</b></font></td>
              <td bgcolor='$msg_bg_2' align='left' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$msg_color}'>$file</font></td>
            </tr>
            <tr bgcolor='$key_bg'>
              <td bgcolor='$key_bg' align='right' valign='top' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$key_color}'><b>Line:</b></font></td>
              <td bgcolor='$msg_bg' align='left' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$msg_color}'>$line</font></td>
            </tr>
            <tr bgcolor='$key_bg'>
              <td bgcolor='$key_bg' align='right' valign='top' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$key_color}'><b>Request Link:</b></font></td>
              <td bgcolor='$msg_bg' align='left' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$msg_color}'>" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://') . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '')) . "</font></td>
            </tr>
            <tr bgcolor='$key_bg'>
              <td bgcolor='$key_bg' align='right' valign='top' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$key_color}'><b>Server Data:</b></font></td>
              <td bgcolor='$msg_bg' align='left' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$msg_color}'>" . print_r($_SERVER, true) . "</font></td>
            </tr>
            <tr bgcolor='$key_bg'>
              <td bgcolor='$key_bg' align='right' valign='top' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$key_color}'><b>POST Data:</b></font></td>
              <td bgcolor='$msg_bg' align='left' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$msg_color}'>" . print_r($_POST, true) . "</font></td>
            </tr>
            <tr bgcolor='$key_bg'>
              <td bgcolor='$key_bg' align='right' valign='top' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$key_color}'><b>GET Data:</b></font></td>
              <td bgcolor='$msg_bg' align='left' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$msg_color}'>" . print_r($_GET, true) . "</font></td>
            </tr>
            <tr bgcolor='$key_bg_2'>
              <td bgcolor='$key_bg_2' align='right' valign='top' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$key_color}'><b>Counter:</b></font></td>
              <td bgcolor='$msg_bg_2' align='left' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$msg_color}'>{$counter_msg}</font></td>
            </tr>
            <tr bgcolor='$key_bg'>
              <td bgcolor='$key_bg' align='right' valign='top' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$key_color}'><b>Message:</b></font></td>
              <td bgcolor='$msg_bg' align='left' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$msg_color}'>$message</font></td>
            </tr>
            <tr bgcolor='$key_bg_2'>
              <td bgcolor='$key_bg_2' align='right' valign='top' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$key_color}'><b>Backtrace:</b></font></td>
              <td bgcolor='$msg_bg_2' align='left' style='border: 1px solid gray;'><font face='Verdana' size='2' style='font-size: {$font_size}px;' color='{$msg_color}'><pre>{$backtrace}</pre></font></td>
            </tr>
          </table>
       ";
              
       return $output;
    }