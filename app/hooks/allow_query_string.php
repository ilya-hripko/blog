<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Allow Query Strings in CI while still using AUTO uri_protocol
 *
 * @author Dan Horrigan
 * @author Phil Sturgeon
 */
class cleanURI
{

    static $query_string;

}

function clean_uri()
{
    $_GET = null;

    /*
     * Some servers does not put QUERY_STRING into $_SERVER['QUERY_STRING']
     * in case there are two '?' in URI (?page/module?parameter=1)
     * So query_string does not want to work in frendly ulr mode.
     * This patch is to fix this issue.
     */

    if(strpos($_SERVER['REQUEST_URI'], '?') !== false &&
            $_SERVER['QUERY_STRING'] == substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')))
        cleanURI::$query_string = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?') + 1);
    else
        cleanURI::$query_string = $_SERVER['QUERY_STRING'];

    foreach(array('REQUEST_URI', 'PATH_INFO', 'ORIG_PATH_INFO') as $uri_protocol)
    {
        if(!isset($_SERVER[$uri_protocol]))
        {
            continue;
        }

        if(strpos($_SERVER[$uri_protocol], '?') !== FALSE)
        {
            $_SERVER[$uri_protocol] = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER[$uri_protocol]);
        }
    }
}

function recreate_get()
{
    parse_str(cleanURI::$query_string, $_GET);
}