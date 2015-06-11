<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

if(!function_exists('generate_user_msg_containers'))
{
    function generate_user_msg_containers($success = '', $error = '') {
        return "<div id='errorMessage' class='alert alert-danger" . (!$error ? ' hide' : '') . "'>" . $error . "</div>\n" . 
                '<div id="infoMessage" class="alert alert-success' . (!$success ? ' hide' : '') . '">' . $success . '</div>';
    }
}

if( !function_exists( 'return_json' ) ) {
    function return_json($response) {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        echo json_encode($response);
        die();
    }
} // function