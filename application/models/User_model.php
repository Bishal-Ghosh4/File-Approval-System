// ============================================
// File: application/models/User_model.php
// ============================================
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
    
    public function authenticate($email, $password) {
        $this->db->where('email', $email);
        $user = $this->db->get('users')->row();
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }
    
    public function get_user_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row();
    }
    
    public function get_user_by_email($email) {
        return $this->db->get_where('users', ['email' => $email])->row();
    }
}