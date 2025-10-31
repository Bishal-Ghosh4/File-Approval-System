/ ============================================
// File: application/models/File_model.php
// ============================================
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model {
    
    public function insert_file($data) {
        $this->db->insert('files', $data);
        return $this->db->insert_id();
    }
    
    public function get_all_files() {
        $this->db->select('files.*, users.name as uploader_name, users.email as uploader_email, 
                          managers.name as manager_name');
        $this->db->from('files');
        $this->db->join('users', 'users.id = files.user_id');
        $this->db->join('users as managers', 'managers.id = files.approved_by', 'left');
        $this->db->order_by('files.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    public function get_user_files($user_id) {
        $this->db->select('files.*, managers.name as manager_name');
        $this->db->from('files');
        $this->db->join('users as managers', 'managers.id = files.approved_by', 'left');
        $this->db->where('files.user_id', $user_id);
        $this->db->order_by('files.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    public function get_file_by_id($id) {
        $this->db->select('files.*, users.name as uploader_name, users.email as uploader_email');
        $this->db->from('files');
        $this->db->join('users', 'users.id = files.user_id');
        $this->db->where('files.id', $id);
        return $this->db->get()->row();
    }
    
    public function update_status($file_id, $status, $manager_id, $reason = null) {
        $data = [
            'status' => $status,
            'approved_by' => $manager_id,
            'approved_at' => date('Y-m-d H:i:s')
        ];
        
        if ($reason) {
            $data['rejection_reason'] = $reason;
        }
        
        $this->db->where('id', $file_id);
        return $this->db->update('files', $data);
    }
    
    public function get_statistics($user_id = null) {
        $stats = [];
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        
        $stats['total'] = $this->db->count_all_results('files');
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        $this->db->where('status', 'pending');
        $stats['pending'] = $this->db->count_all_results('files');
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        $this->db->where('status', 'approved');
        $stats['approved'] = $this->db->count_all_results('files');
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        $this->db->where('status', 'rejected');
        $stats['rejected'] = $this->db->count_all_results('files');
        
        return $stats;
    }
}