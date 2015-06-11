<?php

// Check php extensions
if($_SERVER['CIENV'] != "daniel")
if (!function_exists('dba_handlers'))
    die('dba php extension must be installed.');
else if (!in_array($HK_ERR_HANDLER_CONF[$HK_ERR_HANDLER_ENV]['db_type'], dba_handlers()))
    die('Current configuration does not allow to work with "'.$HK_ERR_HANDLER_CONF[$HK_ERR_HANDLER_ENV]['db_type'].'" database.');



function eh_get_error($line, $file) {
    if($_SERVER['CIENV'] == "daniel"){
        return array(
            'created'=>0,
            'counter'=>0
        );
    }

    $key = md5($line.$file);
    $val = dba_fetch($key, eh_dba_handler());

    if (!$val)
        $result = array(
            'created'=>0,
            'counter'=>0
        );
    else
        $result = unserialize($val);

    return $result;
}

function eh_update_error($line, $file, $error) {
    if($_SERVER['CIENV'] == "daniel")
        return false;

    $key = md5($line.$file);
    $val = serialize($error);
    $x = dba_replace($key, $val, eh_dba_handler());
}

function eh_dba_handler() {
    if($_SERVER['CIENV'] == "daniel")
        return false;

    global $HK_ERR_HANDLER_CONF, $HK_ERR_HANDLER_ENV;
    return dba_open(dirname(__FILE__).'/db/storage_'.$HK_ERR_HANDLER_CONF[$HK_ERR_HANDLER_ENV]['db_type'], 'c', $HK_ERR_HANDLER_CONF[$HK_ERR_HANDLER_ENV]['db_type']);
}
