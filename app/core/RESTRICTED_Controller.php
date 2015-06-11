<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class RESTRICTED_Controller extends CI_Controller {
    public $user_id;
    public $roles;
    public $data = array();
    public $base_url = '';

    function __construct()
    {
        parent::__construct();
        if(!preg_match("!".$this->config->item('cookie_domain')."!si", base_url()))
            $this->load->library('session', array('cookie_domain'=>''));
        else $this->load->library('session');

        $this->load->library('ion_auth');
        $this->load->library('zacl');
        
        $this->load->library('form_validation');
        $this->roles = $this->zacl->roles;
        
        $this->user_id = $this->session->userdata('user_id');
        $this->user_name = $this->session->userdata('name');
        $role_id = $this->session->userdata('roleid');
        
        if($role_id && $role_id == 1) {
            $key = $this->config->item('encrypt_key');
            $key_data = $this->input->get('key');
            
            if($key_data) {
                $var = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($key_data), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
                try {
                    $var = unserialize($var);
                    
                    if(isset($var['user_id'])) {
                        $new_user_id = $var['user_id'];
                    }
                  
                } catch (Exception $e) {}
            }
        }
        
        if(isset($new_user_id)) {
            $this->user_id = $new_user_id;
        }

        
        $this->lang_id = $this->session->userdata('lang_id');
        
        $this->load->helper('language');
        $this->load->helper('validate');
        $this->load->helper('user_message');
        
        $this->load->config('memcached', true);

        $cache_adapter = (CACHE_ON) ? 'memcached' : 'dummy';
        $this->load->driver('cache', array('adapter' => $cache_adapter, 'backup'  => 'file'));

        if(!$this instanceof Auth && !$this instanceof Index && !$this instanceof Ajax && !$this->ion_auth->logged_in()){
            if($_SERVER['REQUEST_METHOD'] == "POST")
                $this->forceReloginIfSessionExpiredJSON();
            else
                $this->forceReloginIfSessionExpired();
            die;
        }
        
        if($this->input->get('xhr')) {
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        }
        
        $this->base_url = base_url();
    }

    public function display_admin_view($template, $data, $in_var = false) {
        if($in_var) {
            return load_view('admin/' . $template, $data, true);
        }
        
        load_view('admin/' . $template, $data);
    }
    
    public function base_front_view($template, $data) {
        $this->data['html'] = load_view('front/' . $template, $data, true);
        $this->data['base_url'] = $this->base_url;
        $this->data = array_merge($this->data, $data);
        $this->load->view('layout/front', $this->data);
    }
    
    public function forceReloginIfSessionExpired() {
        if(!$this->ion_auth->logged_in()) {
            if($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'session_expired'));
            } else {
                redirect('auth/login');
            }
            die;
        }
    }

    public function forceReloginIfSessionExpiredJSON()
    {
        $this->forceReloginIfSessionExpired();
    }
    
    public function _get_pagination_config($config_change = array()) {
        $config = array();
        $config['base_url'] = $this->base_url;
        $config['total_rows'] = 200;
        $config['per_page'] = 20;
        $config['num_links'] = 5;
        $config['uri_segment'] = 5;
        $config['uri_segment_array'] = 4;
        $config['use_page_numbers'] = true;
        $config['full_tag_open'] = '<div class=pagination><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = '<li class="hidden-xs">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active hidden-xs"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_tag_open'] = '<li class="hidden-xs">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="hidden-xs">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_link'] = '&lsaquo; First';
        $config['last_link'] = 'Last &rsaquo;';
        $config['next_link'] = 'Next &gt;';
        $config['prev_link'] = ' &lt; Prev';
        
        foreach($config_change as $key => $value) {
            $config[$key] = $value;
        }
        
        return $config;
        
    }
    
    public function _fix_url_slash(){
        if($_SERVER['REQUEST_URI'] && substr($_SERVER['REQUEST_URI'], -1)!='/'){
            redirect($this->base_url.substr($_SERVER['REQUEST_URI'],1).'/', 'location', 301);
        }
    }
    
    function add_modified_header($time) {

        $LastModified_unix = $time;
        $LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix);
        $IfModifiedSince = false;
        if (isset($_ENV['HTTP_IF_MODIFIED_SINCE'])) {
            $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
        }

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
        }

        if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
            exit;
        }
        header('Last-Modified: ' . $LastModified);
    }
    
}