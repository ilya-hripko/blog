<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends RESTRICTED_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('zacl');
        $this->load->helper('language');
        
        $this->load->model('ion_auth_model');
        $this->load->model('user_model');
        $this->data['base_url'] = $this->base_url;
        $this->load->model('front_model');
        $this->data['blog_categories'] = $this->front_model->get_blog_categories(0);
        $this->data['top_menu'] = $this->front_model->get_top_menu();
    }

    function index() {
        $this->login();
    }

    function signup() {

        $post = $this->input->post();
        if(isset($post['email'])) {
            
            $email = isset($post['email']) ? trim($post['email']) : '';
            $username = isset($post['username']) ? trim($post['username']) : '';
            $pwd = isset($post['password']) ? trim($post['password']) : '';
            
            $errors = array();
            $required = array('email' => $email, 'username' => $username, 'password' => $pwd);
            foreach($required as $error_key => $value) {
                if(!$value) {
                    $errors[$error_key] = 'Field required.';
                }
            }
            
            if (!isset($errors['email']) && (!$email || !filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL))) {
                $errors['email'] =  "Please enter a valid Email.";
            }
            
            if(!isset($errors['email']) && $this->user_model->get_user_by_email($email)) {
                $errors['email'] =  "There is exist account with this Email.";
            }
            
            if (empty($errors)) {
                $extended_data = array('name' => $username);
                $user_id = $this->ion_auth->register('', $pwd, $email, $extended_data, $this->roles['User'], true);
                
                if($user_id) {
                    $session_data = array('user_id' => $user_id, 'email' => $email, 'name' => $username, 'activate' => false, 'roleid' => $this->roles['User']);
                    $this->session->set_userdata($session_data);
                    $output = array('status' => 'success', 'redir' => $this->base_url . 'cabinet/');
                } else {
                    $output = array('status' => 'error', 'type' => 'global', 'error' => 'Server Error!');
                }
            } else {
                $output = array('status' => 'error', 'type' => 'field', 'errors' => $errors);
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($output));
            return;
        } else {
            if($this->session->userdata('activate')) {
                $this->_check_if_login();
            }
        } 
        
        $this->data['success'] = $this->session->flashdata('message');
        $this->data['error'] = $this->session->flashdata('message_error');
        
        $this->data['html'] = load_view('front/auth/signup', $this->data, true);
        $this->data['page'] = 'login';
        $this->data['title'] = 'Sign Up';
        
        load_view('layout/front', $this->data);
        
    }

    function login() {
        
        if($this->user_id) {
            $this->_redirect_user_by_role();
            return;
        }
        
        if($this->ion_auth->login_remembered_user()) {
            $this->_redirect_user_by_role();
            return;
        }
        
        $post = $this->input->post();
        if(isset($post['email'])) {
            $email = isset($post['email']) ? trim($post['email']) : '';
            $pwd = isset($post['password']) ? trim($post['password']) : '';
            
            $remember = isset($post['remember_me']) ? true : false;
            
            $errors = array();
            $required = array('email' => $email, 'password' => $pwd);
            foreach($required as $error_key => $value) {
                if(!$value) {
                    $errors[$error_key] ='Field required.';;
                }
            }
            
            if (!isset($errors['email']) && (!$email || !$email = filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL))) {
                $errors['email'] = "Please enter a valid Email.";
            }
            
            if (!isset($errors['email'])) {
                $user_info = $this->user_model->get_user_by_email($email);
                if(!$user_info) {
                    $errors['email'] = "Email not found.";
                }
            }
            
            if(!empty($errors)) {
                $output = array('status' => 'error', 'type' => 'field', 'errors' => $errors);
                $this->output->set_content_type('application/json')->set_output(json_encode($output));
                return;
            }
            
            if($this->ion_auth->login($email, $pwd, $remember)) {
                $redir = $this->_redirect_user_by_role(true);
                $output = array('status' => 'success', 'redir' => base_url() . $redir);
            } else {
                $output = array('status' => 'error', 'type' => 'global', 'error' => "Incorect password.");
            }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($output));
            return;
            
        }
        
        $this->data['success'] = $this->session->flashdata('message');
        $this->data['error'] = $this->session->flashdata('message_error');
        
        $this->data['html'] = load_view('front/auth/login', $this->data, true);
        $this->data['page'] = 'login';
        $this->data['title'] = 'Sign in';
                
        load_view('layout/front', $this->data);
    }
    
    public function forgot() {
        
        if($this->session->userdata('activate') && $this->session->userdata('user_id')) {
            $this->_redirect_user_by_role();
            return;
        }
        
        $post = $this->input->post();
        if(isset($post['email'])) {
            $email = isset($post['email']) ? trim($post['email']) : '';
            
            $errors = array();
            
            if(!$email) {
                $errors['email'] = 'Required field';
            }

            if (!isset($errors['email']) && (!$email || !filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL))) {
                $errors['email'] = 'Not valid format';
            }
            
            if (!isset($errors['email'])) {
                $user_info = $this->user_model->get_user_by_email($email);
                if(isset($user_info->active) && !$user_info->active) {
                    $errors['email'] = 'Email not activated';
                } else if (!isset($user_info->active)) {
                    $errors['email'] = 'Email not found';
                }
            }
            
            if(!empty($errors)) {
                $output = array('status' => 'error', 'type' => 'field', 'errors' => $errors);
                echo json_encode($output);
                return;
            }
            
            $forgotten = $this->ion_auth->forgotten_password($email);
            if($forgotten) {
                $output = array('status' => 'success', 'message' => 'Email с дальнейшими инструкциями по востановлению пароля был отправлен на ' . $email . ' почтовый ящик.');
            } else {
                $output = array('status' => 'error', 'type' => 'global', 'error' => 'Неизвестная ошибка. Попробуйте повторить попытку позже или свяжитесь со службой поддержки.');
            }
            echo json_encode($output);
            return;
        }
        
        $this->data['error'] = $this->session->flashdata('message_error');
        $this->data['base_url'] = $this->base_url;
        $this->data['title'] = 'Reset password';
        $this->base_front_view('forgot', $this->data);
    }
    
    public function reset_password($code = '') {
        $reset = $this->ion_auth->forgotten_password_complete($code);

        if ($reset) {  //if the reset worked then send them to the login page
            $this->session->set_flashdata('message', 'New password was sent to your email');
            redirect('login');
        } else { //if the reset didnt work then send them back to the forgot password page
            $this->session->set_flashdata('message_error', 'Not valid reset');
            redirect('forgot');
        }
    }
    
    public function logout() {
        $this->ion_auth->logout();
        redirect('login');
    }
    
        
    protected function _redirect_user_by_role($get_url = false) {

        $roleid = $this->session->userdata('roleid');
        $user_id = $this->session->userdata('user_id');
        
        switch ($roleid) {
            case 1:
                $redir_path = 'super_admin/users';
                break;
            case 2:
                $redir_path = '/';
                break;
            default:
                $redir_path = 'logout';
                break;
        }
        
        if(!$get_url) {
            redirect($redir_path, 'refresh');
        } else {
            return $redir_path;
        }
        
    }

}