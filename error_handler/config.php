<?php

global $HK_ERR_HANDLER_CONF, $HK_ERR_HANDLER_ENV;

$HK_ERR_HANDLER_ENV = ENVIRONMENT;
$HK_ERR_HANDLER_ON  = true;



$HK_ERR_HANDLER_CONF = array(
   'live' => array(

       'db_type' => 'inifile',
       'use_dba' => false,
       'error_log_file' => $DIR.'/logs/errors-'.date('Y-m-d').'.log',

       'dest' => array(
           'log'     => false,
           'email'   => false,
           'browser' => true
       ),

       'emails' => array(
           'use_smtp' => true,
           'debug' => 0,
           'subject' => 'Movinhosehold: Error!',
           'from' => 'bug-report@testblog.hm',
           'recepients' => 'ilya.hripko@gmail.com',
           'smtp_server' =>'localhost',
           'smtp_port' => '25',
           'smtp_username' => 'bug-report@testblog.hm',
           'smtp_password' => '123456',
		   
       ),

       'turn_off_header_alredy_sent_reporting' => true,

   ),
   
   'ilya' => array(

       'db_type' => 'inifile',

       'error_log_file' => $DIR.'/logs/errors-'.date('Y-m-d').'.log',

       'dest' => array(
           'log'     => false,
           'email'   => false,
           'browser' => true
       ),

       'emails' => array(
           'use_smtp' => true,
           'debug' => 0,
           'subject' => 'Movinhosehold: Error!',
           'from' => 'bug-report@testblog.hm',
           'recepients' => 'ilya.hripko@gmail.com',
           'smtp_server' =>'localhost',
           'smtp_port' => '25',
           'smtp_username' => 'bug-report@testblog.hm',
           'smtp_password' => '123456',
		   
       ),

       'turn_off_header_alredy_sent_reporting' => true,

   )

);
