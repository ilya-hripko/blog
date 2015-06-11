<?php
class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function get_user_by_email($email) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);
        
        return $this->db->get()->row();
    }
    
    public function get_user_by_id($id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $id);
        
        return $this->db->get()->row();
    }
    
    public function update_user($user_id, $data) {
        $this->db->update('users', $data, array('user_id' => $user_id));
    }
    
}