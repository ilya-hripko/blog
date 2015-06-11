<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class CRON_Controller extends CI_Controller {
    public $shop_id;

    function __construct()
    {
        parent::__construct();
        
        $this->load->config('memcached', true);

        $cache_adapter = (CACHE_ON) ? 'memcached' : 'dummy';
        $this->load->driver('cache', array('adapter' => $cache_adapter, 'backup'  => 'file'));
        
        if(!$this->input->is_cli_request()) {
            //die('PERMISSION DENIDED');
        }
    }
}