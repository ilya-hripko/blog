<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Name:  Ion Auth Model
 *
 * Author:  Ben Edmunds
 * 		   ben.edmunds@gmail.com
 * 	  	   @benedmunds
 *
 * Added Awesomeness: Phil Sturgeon
 *
 * Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
 *
 * Created:  10.01.2009
 *
 * Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
 * Original Author name has been kept but that does not mean that the method has not been modified.
 *
 * Requirements: PHP5 or above
 *
 */
//  CI 2.0 Compatibility
if (!class_exists('CI_Model')) {

    class CI_Model extends Model {
        
    }

}

class Ion_auth_model extends CI_Model {

    /**
     * Holds an array of tables used
     *
     * @var string
     * */
    public $tables = array();

    /**
     * activation code
     *
     * @var string
     * */
    public $activation_code;

    /**
     * forgotten password key
     *
     * @var string
     * */
    public $forgotten_password_code;

    /**
     * new password
     *
     * @var string
     * */
    public $new_password;

    /**
     * Identity
     *
     * @var string
     * */
    public $identity;

    public function __construct() {
        parent::__construct();
        $this->load->config('ion_auth', TRUE);
        $this->load->helper('cookie');
        $this->load->helper('date');
        $this->load->library('session');

        $this->tables = $this->config->item('tables', 'ion_auth');

        $this->identity_column = $this->config->item('identity', 'ion_auth');
        $this->store_salt = $this->config->item('store_salt', 'ion_auth');
        $this->salt_length = $this->config->item('salt_length', 'ion_auth');
        $this->min_password_length = $this->config->item('min_password_length', 'ion_auth');

        /**
         * Checks if salt length is at least the length
         * of the minimum password length.
         * */
        if ($this->salt_length < $this->min_password_length) {
            $this->salt_length = $this->min_password_length;
        }
    }

    /**
     * Misc functions
     *
     * Hash password : Hashes the password to be stored in the database.
     * Hash password db : This function takes a password and validates it
     * against an entry in the users table.
     * Salt : Generates a random salt value.
     *
     * @author Mathew
     */

    /**
     * Hashes the password to be stored in the database.
     *
     * @return void
     * @author Mathew
     * */
    public function hash_password($password, $salt = false) {
        if (empty($password)) {
            return FALSE;
        }

        if ($this->store_salt && $salt) {
            return sha1($password . $salt);
        } else {
            $salt = $this->salt();
            return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }

    function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * This function takes a password and validates it
     * against an entry in the users table.
     *
     * @return void
     * @author Mathew
     * */
    public function hash_password_db($identity, $password) {
        if (empty($identity) || empty($password)) {
            return FALSE;
        }

        $query = $this->db->select('password')
                ->select('salt')
                ->where($this->identity_column, $identity)
                ->where($this->ion_auth->_extra_where)
                ->limit(1)
                ->get($this->tables['users']);

        $result = $query->row();

        if ($query->num_rows() !== 1) {
            return FALSE;
        }

        if ($this->store_salt) {
            return sha1($password . $result->salt);
        } else {
            $salt = substr($result->password, 0, $this->salt_length);

            return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }

    /**
     * Generates a random salt value.
     *
     * @return void
     * @author Mathew
     * */
    public function salt() {
        return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
    }

    /**
     * Activation functions
     *
     * Activate : Validates and removes activation code.
     * Deactivae : Updates a users row with an activation code.
     *
     * @author Mathew
     */

    /**
     * activate
     *
     * @return void
     * @author Mathew
     * */
    public function activate($id, $code = false) {
        if ($code !== false) {
            $query = $this->db->select($this->identity_column)
                    ->where('activation_code', $code)
                    ->limit(1)
                    ->get($this->tables['users']);

            $result = $query->row();

            if ($query->num_rows() !== 1) {
                return FALSE;
            }

            $identity = $result->{$this->identity_column};

            $data = array(
                'activation_code' => '',
                'active' => 1,
            );

            $this->db->where($this->ion_auth->_extra_where);
            $this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));
        } else {
            $data = array(
                'activation_code' => '',
                'active' => 1
            );

            $this->db->where($this->ion_auth->_extra_where);
            $this->db->update($this->tables['users'], $data, array('user_id' => $id));
        }

        return $this->db->affected_rows() == 1;
    }

    /**
     * Deactivate
     *
     * @return void
     * @author Mathew
     * */
    public function deactivate($id = 0) {
        if (empty($id)) {
            return FALSE;
        }

        $activation_code = sha1(md5(microtime()));
        $this->activation_code = $activation_code;

        $data = array(
            'activation_code' => $activation_code,
            'active' => 0
        );

        $this->db->where($this->ion_auth->_extra_where);
        $this->db->update($this->tables['users'], $data, array('user_id' => $id));

        return $this->db->affected_rows() == 1;
    }

    /**
     * change password
     *
     * @return bool
     * @author Mathew
     * */
    public function change_password($identity, $old, $new) {
        $query = $this->db->select('password, salt')
                ->where($this->identity_column, $identity)
                ->where($this->ion_auth->_extra_where)
                ->limit(1)
                ->get($this->tables['users']);

        $result = $query->row();

        $db_password = $result->password;
        $old = $this->hash_password_db($identity, $old);
        $new = $this->hash_password($new, $result->salt);

        if ($db_password === $old) {
            //store the new password and reset the remember code so all remembered instances have to re-login
            $data = array(
                'password' => $new,
                'remember_code' => '',
            );

            $this->db->where($this->ion_auth->_extra_where);
            $this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));

            return $this->db->affected_rows() == 1;
        }

        return FALSE;
    }

    /**
     * Checks username
     *
     * @return bool
     * @author Mathew
     * */
    public function username_check($username = '') {
        if (empty($username)) {
            return FALSE;
        }

        return $this->db->where('username', $username)
                        ->where($this->ion_auth->_extra_where)
                        ->count_all_results($this->tables['users']) > 0;
    }

    /**
     * Checks email
     *
     * @return bool
     * @author Mathew
     * */
    public function email_check($email = '') {
        if (empty($email)) {
            return FALSE;
        }

        return $this->db->where('email', $email)
                        ->where($this->ion_auth->_extra_where)
                        ->count_all_results($this->tables['users']) > 0;
    }

    /**
     * Get email by social
     *
     * @return email or false if not exist
     * @author Mathew
     * */
    public function get_email_by_social($provider, $uid) {
        $query = $this->db->select('email')
                ->where('social_provider', $provider)
                ->where('social_uid', $uid)
                ->limit(1)
                ->get($this->tables['users']);

        $result = $query->row();

        if ($query->num_rows() !== 1) {
            return FALSE;
        }

        return $result->email;
    }

    /**
     * Identity check
     *
     * @return bool
     * @author Mathew
     * */
    protected function identity_check($identity = '') {
        if (empty($identity)) {
            return FALSE;
        }

        /*
          if (isset($this->ion_auth->_extra_where) && !empty($this->ion_auth->_extra_where))
          {
          $this->db->where($this->ion_auth->_extra_where);
          }
         */
        return $this->db->where($this->identity_column, $identity)
                        ->count_all_results($this->tables['users']) > 0;
    }

    /**
     * Insert a forgotten password key.
     *
     * @return bool
     * @author Mathew
     * */
    public function forgotten_password($email = '') {
        if (empty($email)) {
            return FALSE;
        }

        $key = $this->hash_password(microtime() . $email);

        $this->forgotten_password_code = $key;

        $this->db->where($this->ion_auth->_extra_where);

        $this->db->update($this->tables['users'], array('forgotten_password_code' => $key), array('email' => $email));

        return $this->db->affected_rows() == 1;
    }

    /**
     * Forgotten Password Complete
     *
     * @return string
     * @author Mathew
     * */
    public function forgotten_password_complete($code, $salt = FALSE) {
        if (empty($code)) {
            return FALSE;
        }

        $this->db->where('forgotten_password_code', $code);

        if ($this->db->count_all_results($this->tables['users']) > 0) {
            $password = $this->salt();

            $data = array(
                'password' => $this->hash_password($password, $salt),
                'forgotten_password_code' => '0',
                'active' => 1,
            );

            $this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));

            return $password;
        }

        return FALSE;
    }

    /**
     * profile
     *
     * @return void
     * @author Mathew
     * */
    public function profile($identity = '', $is_code = false) {
        if (empty($identity)) {
            return FALSE;
        }

        $this->db->select(array(
            $this->tables['users'] . '.*'
        ));


        if ($is_code) {
            $this->db->where($this->tables['users'] . '.forgotten_password_code', $identity);
        } else {
            $this->db->where($this->tables['users'] . '.' . $this->identity_column, $identity);
        }

        $this->db->where($this->ion_auth->_extra_where);

        $this->db->limit(1);
        $i = $this->db->get($this->tables['users']);

        return ($i->num_rows > 0) ? $i->row() : FALSE;
    }

    public function find_user_email($user_id) {
        if (empty($user_id)) {
            return FALSE;
        }

        $this->db->select('email');


        $this->db->where('user_id', $user_id);

        $this->db->limit(1);
        $i = $this->db->get('users');

        return ($i->num_rows > 0) ? $i->row() : FALSE;
    }

    /**
     * Basic functionality
     *
     * Register
     * Login
     *
     * @author Mathew
     */

    /**
     * register
     *
     * @return bool
     * @author Mathew
     * */
    public function register($username, $password, $email, $additional_data = false, $roleid = false) {

        if ($this->email_check($email)) {
            $this->ion_auth->set_error('account_creation_duplicate_email');
            return FALSE;
        }

        // IP Address
        $ip_address = $this->input->ip_address();
        $salt = $this->store_salt ? $this->salt() : FALSE;
        $password = $this->hash_password($password, $salt);

        // Users table.
        $data = array(
            'password' => $password,
            'email' => $email,
            'roleid' => $roleid,
            'ip_address' => $ip_address,
            'date_created' => now(),
            'last_login' => now(),
            'active' => 1
        );

        if ($additional_data)
            $data = array_merge($data, $additional_data);

        if ($this->store_salt) {
            $data['salt'] = $salt;
        }

        if ($this->ion_auth->_extra_set) {
            $this->db->set($this->ion_auth->_extra_set);
        }

        $this->db->insert($this->tables['users'], $data);

        $id = $this->db->insert_id();

        return $this->db->affected_rows() > 0 ? $id : false;
    }

    /**
     * login
     *
     * @return bool
     * @author Mathew
     * */
    public function login($identity, $password, $remember = FALSE) {
        if (empty($identity) || empty($password) || !$this->identity_check($identity)) {
            return FALSE;
        }

        $query = $this->db->select($this->identity_column . ', user_id, password, roleid, name')
                ->where($this->identity_column, $identity)
                ->where('active', 1)
                ->where($this->ion_auth->_extra_where)
                ->limit(1)
                ->get($this->tables['users']);

        $result = $query->row();

        if ($query->num_rows() == 1) {
            $password = $this->hash_password_db($identity, $password);

            if ($result->password === $password) {
                $this->update_last_login($result->user_id);

                $session_data = array(
                    $this->identity_column => $result->{$this->identity_column},
                    'user_id' => $result->user_id, //everyone likes to overwrite id so we'll use user_id
                    'roleid' => $result->roleid,
                    'name' => $result->name,
                    'email' => $result->email
                );

                $this->session->set_userdata($session_data);

                if ($remember && $this->config->item('remember_users', 'ion_auth')) {
                    $this->remember_user($result->user_id);
                }

                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * get_users
     *
     * @return object Users
     * @author Ben Edmunds
     * */
    public function get_users($roleid = false, $limit = NULL, $offset = NULL) {
        $this->db->select(array(
            $this->tables['users'] . '.*'
        ));

        $this->db->join($this->tables['groups'], $this->tables['users'] . '.roleid = ' . $this->tables['groups'] . '.id', 'left');

        if (is_string($roleid)) {
            $this->db->where($this->tables['groups'] . '.id', $roleid);
        }

        if (isset($limit) && isset($offset))
            $this->db->limit($limit, $offset);

        return $this->db->get($this->tables['users']);
    }

    /**
     * get_users_count
     *
     * @return int Number of Users
     * @author Sven Lueckenbach
     * */
    public function get_users_count($roleid = false) {
        if ($roleid) {
            $this->db->where($this->tables['groups'] . '.id', $roleid);
        }

        $this->db->from($this->tables['users']);

        return $this->db->count_all_results();
    }

    /**
     * get_active_users
     *
     * @return object
     * @author Ben Edmunds
     * */
    public function get_active_users($roleid = false) {
        $this->db->where($this->tables['users'] . '.active', 1);

        return $this->get_users($roleid);
    }

    /**
     * get_inactive_users
     *
     * @return object
     * @author Ben Edmunds
     * */
    public function get_inactive_users($roleid = false) {
        $this->db->where($this->tables['users'] . '.active', 0);

        return $this->get_users($roleid);
    }

    /**
     * get_user
     *
     * @return object
     * @author Phil Sturgeon
     * */
    public function get_user($id = false) {
        //if no id was passed use the current users id
        if (empty($id)) {
            $id = $this->session->userdata('user_id');
        }

        $this->db->where($this->tables['users'] . '.user_id', $id);
        $this->db->limit(1);

        return $this->get_users();
    }

    /**
     * get_user_by_email
     *
     * @return object
     * @author Ben Edmunds
     * */
    public function get_user_by_email($email) {
        $this->db->limit(1);

        return $this->get_users_by_email($email);
    }

    /**
     * get_users_by_email
     *
     * @return object
     * @author Ben Edmunds
     * */
    public function get_users_by_email($email) {
        $this->db->where($this->tables['users'] . '.email', $email);

        return $this->get_users();
    }

    /**
     * get_user_by_username
     *
     * @return object
     * @author Kevin Smith
     * */
    public function get_user_by_username($username) {
        $this->db->limit(1);

        return $this->get_users_by_username($username);
    }

    /**
     * get_users_by_username
     *
     * @return object
     * @author Kevin Smith
     * */
    public function get_users_by_username($username) {
        $this->db->where($this->tables['users'] . '.username', $username);

        return $this->get_users();
    }

    /**
     * get_user_by_identity
     *                                      //copied from above ^
     * @return object
     * @author jondavidjohn
     * */
    public function get_user_by_identity($identity) {
        $this->db->where($this->tables['users'] . '.' . $this->identity_column, $identity);
        $this->db->limit(1);

        return $this->get_users();
    }

    /**
     * get_newest_users
     *
     * @return object
     * @author Ben Edmunds
     * */
    public function get_newest_users($limit = 10) {
        $this->db->order_by($this->tables['users'] . '.date_created', 'desc');
        $this->db->limit($limit);

        return $this->get_users();
    }

    /**
     * get_users_role
     *
     * @return object
     * @author Ben Edmunds
     * */
    public function get_users_role($id = false) {
        //if no id was passed use the current users id
        $id || $id = $this->session->userdata('user_id');

        if (isset($this->session->userdata['roleid']))
            return $this->session->userdata('roleid');


        $user = $this->db->select('roleid')
                ->where('id', $id)
                ->get($this->tables['users'])
                ->row();

        return $this->db->select('name, description')
                        ->where('id', $user->roleid)
                        ->get($this->tables['groups'])
                        ->row();
    }

    /**
     * get_groups
     *
     * @return object
     * @author Phil Sturgeon
     * */
    public function get_groups() {
        return $this->db->get($this->tables['groups'])
                        ->result();
    }

    /**
     * get_role
     *
     * @return object
     * @author Ben Edmunds
     * */
    public function get_role($id) {
        $this->db->where('id', $id);

        return $this->db->get($this->tables['groups'])
                        ->row();
    }

    /**
     * get_group_by_name
     *
     * @return object
     * @author Ben Edmunds
     * */
    public function get_group_by_name($name) {
        $this->db->where('name', $name);

        return $this->db->get($this->tables['groups'])
                        ->row();
    }

    /**
     * update_user
     *
     * @return bool
     * @author Phil Sturgeon
     * */
    public function update_user($id, $data) {

        $user = $this->get_user($id)->row();

        $this->db->trans_begin();

        if (
                array_key_exists($this->identity_column, $data) &&
                $this->identity_check($data[$this->identity_column]) &&
                $user->{$this->identity_column} !== $data[$this->identity_column]
        ) {
            $this->db->trans_rollback();
            $this->ion_auth->set_error('account_creation_duplicate_' . $this->identity_column);
            return FALSE;
        }


        if (array_key_exists('password', $data)) {
            $data['password'] = $this->hash_password($data['password'], $user->salt);
        }

        $this->db->where($this->ion_auth->_extra_where);

        $this->db->update($this->tables['users'], $data, array('user_id' => $id));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->trans_commit();
        return TRUE;
    }

    /**
     * delete_user
     *
     * @return bool
     * @author Phil Sturgeon
     * */
    public function delete_user($id) {
        $this->db->delete($this->tables['users'], array('id' => $id));
        return TRUE;
    }

    /**
     * update_last_login
     *
     * @return bool
     * @author Ben Edmunds
     * */
    public function update_last_login($id) {
        $this->load->helper('date');

        if (isset($this->ion_auth->_extra_where) && !empty($this->ion_auth->_extra_where)) {
            $this->db->where($this->ion_auth->_extra_where);
        }

        $this->db->update($this->tables['users'], array('last_login' => now()), array('user_id' => $id));

        return $this->db->affected_rows() == 1;
    }

    /**
     * set_lang
     *
     * @return bool
     * @author Ben Edmunds
     * */
    public function set_lang($lang = 'en') {
        set_cookie(array(
            'name' => 'lang_code',
            'value' => $lang,
            'expire' => $this->config->item('user_expire', 'ion_auth') + time()
        ));

        return TRUE;
    }

    /**
     * login_remembed_user
     *
     * @return bool
     * @author Ben Edmunds
     * */
    public function login_remembered_user() {
        //check for valid data
        if (!get_cookie('identity') || !get_cookie('remember_code') || !$this->identity_check(get_cookie('identity'))) {
            return FALSE;
        }

        //get the user
        if (isset($this->ion_auth->_extra_where) && !empty($this->ion_auth->_extra_where)) {
            $this->db->where($this->ion_auth->_extra_where);
        }

        $query = $this->db->select($this->identity_column . ', user_id, roleid')
                ->where($this->identity_column, get_cookie('identity'))
                ->where('remember_code', get_cookie('remember_code'))
                ->limit(1)
                ->get($this->tables['users']);


        //if the user was found, sign them in
        if ($query->num_rows() == 1) {
            $user = $query->row();
            $this->update_last_login($user->user_id);

            $session_data = array(
                $this->identity_column => $user->{$this->identity_column},
                'user_id' => $user->user_id, 
                'roleid' => $user->roleid,
                'email' => $user->email
            );

            $this->session->set_userdata($session_data);


            //extend the users cookies if the option is enabled
            if ($this->config->item('user_extend_on_login', 'ion_auth')) {
                $this->remember_user($user->user_id);
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * remember_user
     *
     * @return bool
     * @author Ben Edmunds
     * */
    private function remember_user($id) {
        if (!$id) {
            return FALSE;
        }

        $user = $this->get_user($id)->row();

        $salt = sha1($user->password . uniqid());

        $this->db->update($this->tables['users'], array('remember_code' => $salt), array('user_id' => $id));

        if ($this->db->affected_rows() > -1) {

            set_cookie(array(
                'name' => 'identity',
                'value' => $user->{$this->identity_column},
                'expire' => $this->config->item('user_expire', 'ion_auth'),
            ));

            set_cookie(array(
                'name' => 'remember_code',
                'value' => $salt,
                'expire' => $this->config->item('user_expire', 'ion_auth'),
            ));

            return TRUE;
        }

        return FALSE;
    }

}