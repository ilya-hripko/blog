<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

function pick_language()
{
    $config = & load_class('Config');
    $prev_lang = !empty($_COOKIE['MSLng']) ? $_COOKIE['MSLng'] : '';
    define('PREV_LANGUAGE', $prev_lang);

    /* @Author Ilya H
     * begin enable or disable multi language support for called controller
     * It's not use now
     */
    $router =& load_class('Router', 'core');
    // Lang set in URL via ?lang=something
    //prd($_GET);
    //prd();
    if (!empty($_GET['lang'])) {
        // Turn en-gb into en
        $lang = substr($_GET['lang'], 0, 2);
        //$_SESSION['aLang'] = $lang;
    }
    // Lang has is picked by a user.
    // Set it to a session variable so we are only checking one place most of the time
    elseif (!empty($_COOKIE['MSLng'])&&($router->uri->rsegments[1]!='main_front')&&($router->uri->rsegments[1]!='main_front_new')) {
        //$lang = $_SESSION['aLang'] = $_COOKIE['MSLng'];
        $lang = $_COOKIE['MSLng'];
    }

//    // Still no Lang. Lets try some browser detection then
//    else if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
//        // explode languages into array
//        $accept_langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
//
//        log_message('debug', 'Checking browser languages: ' . implode(', ', $accept_langs));
//
//        // Check them all, until we find a match
//        foreach ($accept_langs as $lang) {
//            // Turn en-gb into en
//            $lang = substr($lang, 0, 2);
//
//            // Check its in the array. If so, break the loop, we have one!
//            if (in_array($lang, array_keys($config->item('languages')))) {
//                break;
//            }
//        }
//    }

    // If no language has been worked out - or it is not supported - use the default
    if (empty($lang) or !in_array($lang, array_keys($config->item('languages')))) {
        $lang = $config->item('default_language');
    }

    
    if(empty($prev_lang) || $prev_lang!=$lang)
    {
        $server_name = isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:'';
        setcookie("MSLng", $lang, time() + 86400 * 3650, '/', $server_name == 'localhost' ? '' : preg_replace('/^www\./', '', $server_name));
    }

    /*
     * end enable or disable multi language support for called controller
     */

    // Set the language config. Selects the folder name from its key of 'en'
    $lngs = $config->item('languages');
    $config->set_item('language', $lngs[$lang]['name']);
    $config->set_item('date_format_full', $lngs[$lang]['date_format_full']);
    $config->set_item('date_format_short', $lngs[$lang]['date_format_short']);
    $config->set_item('date_format_datepicker', $lngs[$lang]['date_format_datepicker']);

    // Sets a constant to use throughout ALL of CI.
    define('CURRENT_LANGUAGE', $lang);
    define('CURRENT_LANGUAGE_FOLDER', $lngs[$lang]['name']);
}