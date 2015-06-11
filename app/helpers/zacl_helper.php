<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

if(!function_exists('check_acl'))
{

    function check_acl($resource_name)
    {
        $ci = & get_instance();
        $ci->load->library('zacl');
        return $ci->zacl->check_acl($resource_name);
    }

}