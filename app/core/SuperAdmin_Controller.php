<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class SuperAdmin_Controller extends RESTRICTED_Controller
{

    function __construct()
    {
        parent::__construct();
        if(!$this->zacl->check_acl('super admin panel'))
            redirect(base_url());
    }

}