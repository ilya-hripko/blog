<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Migrate extends RESTRICTED_Controller {

    function __construct() {
        parent::__construct();
        if(!$this->zacl->check_acl('super admin panel'))
            redirect(base_url());
        $this->load->library('migration');
    }

    public function index() {        
        echo "migrate";
    }

    public function version($id){
        if (!($res = $this->migration->version($id))) {
            show_error($this->migration->error_string());
        } else {
            echo "Done";
        }
    }

}
