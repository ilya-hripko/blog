<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('load_view')) {
    function load_view($tpl = '', $data = array(), $is_html = false) {
        $CI = & get_instance();
        
        if (!empty($data)) {
            foreach ($data as $name => $value) {
                    $data[$name] = sanitize($name, $value);
            }
        }
        if ($is_html) {
            return $CI->load->view($tpl, $data, $is_html);
        } else {
            $CI->load->view($tpl, $data);
        }
    }
}

if (!function_exists('backend_load_view')) {
    function backend_load_view($tpl = '', $data = array(), $is_html = false) {
        $CI = & get_instance();
        
        if (!empty($data)) {
            foreach ($data as $name => $value) {
                    $data[$name] = sanitize($name, $value);
            }
        }
        $layout_data = array();
        $layout_data['html'] = $CI->load->view($tpl, $data, true);
        $CI->load->view('layout/backend/main', $layout_data);
    }
}

if (!function_exists('clean_data')) {
    function clean_data($name, $data) {
        return sanitize($name, $data);
    }
}

if (!function_exists('sanitize')) {
    function sanitize($name, $vars) {
        if (is_array($vars)) {
            foreach ($vars as $key => $value) {
                $vars[$key] = sanitize($key, $value);
            }
        } else if (is_object($vars)) {
            $vars = clone $vars;
            foreach ($vars as $key => $value) {
                $vars->$key = sanitize($key, $value);
            }
        } else {
            if(!preg_match('!no_encode!si', $name) 
            && !preg_match('!body!si', $name)
            && ('el'!=$name)
            && !preg_match('!css!si', $name)
            && !preg_match('!html!si', $name)
            && !preg_match('!pagination!si', $name)
            && !preg_match('!text!si', $name)
            && !preg_match('!description!si', $name)
            && !preg_match('!post_crop!si', $name)){
                /*$vars = html_entity_decode($vars);
                $vars = str_replace("\xA0", ' ', $vars);*/
                $vars = htmlentities($vars, ENT_QUOTES, 'UTF-8');
            }
        }
        return $vars;
    }
}