// ============================================
// File: application/models/Log_model.php
// ============================================
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model {
    
    public function log_activity($user_id, $action, $description) {
        $data = [
            'user_id' => $user_id,
            'action' => $action,
            'description' => $description,
            'ip_address' => $this->input->ip_address()
        ];
        
        return $this->db->insert('activity_logs', $data);
    }
    
    public function get_recent_logs($limit = 50, $user_id = null) {
        $this->db->select('activity_logs.*, users.name as user_name, users.email as user_email');
        $this->db->from('activity_logs');
        $this->db->join('users', 'users.id = activity_logs.user_id');
        
        if ($user_id) {
            $this->db->where('activity_logs.user_id', $user_id);
        }
        
        $this->db->order_by('activity_logs.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    public function get_logs_by_action($action, $limit = 50) {
        $this->db->select('activity_logs.*, users.name as user_name');
        $this->db->from('activity_logs');
        $this->db->join('users', 'users.id = activity_logs.user_id');
        $this->db->where('activity_logs.action', $action);
        $this->db->order_by('activity_logs.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
}