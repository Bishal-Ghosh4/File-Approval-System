
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->check_auth();
        $this->load->model('File_model');
        $this->load->model('Log_model');
        $this->load->model('User_model');
    }
    
    private function check_auth() {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
    
    public function upload() {
        if ($this->input->method() !== 'post') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        // Only employees can upload
        if ($this->session->userdata('role') !== 'employee') {
            echo json_encode(['success' => false, 'message' => 'Only employees can upload files']);
            return;
        }
        
        $user_id = $this->session->userdata('user_id');
        
        // Configure upload
        $upload_path = './uploads/' . $user_id . '/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'pdf|jpg|jpeg|png|docx';
        $config['max_size'] = 3072; // 3MB
        $config['encrypt_name'] = TRUE;
        
        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload('file')) {
            echo json_encode([
                'success' => false,
                'message' => $this->upload->display_errors('', '')
            ]);
            return;
        }
        
        $upload_data = $this->upload->data();
        
        // Insert file record
        $file_data = [
            'user_id' => $user_id,
            'file_name' => $upload_data['orig_name'],
            'file_path' => $upload_path . $upload_data['file_name'],
            'file_size' => $upload_data['file_size'],
            'file_type' => $upload_data['file_ext'],
            'status' => 'pending'
        ];
        
        $file_id = $this->File_model->insert_file($file_data);
        
        // Log activity
        $this->Log_model->log_activity(
            $user_id,
            'file_upload',
            'Uploaded file: ' . $upload_data['orig_name']
        );
        
        echo json_encode([
            'success' => true,
            'message' => 'File uploaded successfully',
            'file_id' => $file_id
        ]);
    }
    
    public function get_files() {
        $role = $this->session->userdata('role');
        $user_id = $this->session->userdata('user_id');
        
        if ($role === 'manager') {
            $files = $this->File_model->get_all_files();
        } else {
            $files = $this->File_model->get_user_files($user_id);
        }
        
        echo json_encode(['success' => true, 'files' => $files]);
    }
    
    public function approve($file_id) {
        if ($this->session->userdata('role') !== 'manager') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $manager_id = $this->session->userdata('user_id');
        $file = $this->File_model->get_file_by_id($file_id);
        
        if (!$file) {
            echo json_encode(['success' => false, 'message' => 'File not found']);
            return;
        }
        
        // Update file status
        $this->File_model->update_status($file_id, 'approved', $manager_id);
        
        // Log activity
        $this->Log_model->log_activity(
            $manager_id,
            'file_approval',
            'Approved file: ' . $file->file_name . ' (uploaded by ' . $file->uploader_name . ')'
        );
        
        // Send email notification
        $this->send_notification_email(
            $file->uploader_email,
            $file->uploader_name,
            $file->file_name,
            'approved'
        );
        
        echo json_encode([
            'success' => true,
            'message' => 'File approved successfully'
        ]);
    }
    
    public function reject($file_id) {
        if ($this->session->userdata('role') !== 'manager') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $reason = $this->input->post('reason');
        $manager_id = $this->session->userdata('user_id');
        $file = $this->File_model->get_file_by_id($file_id);
        
        if (!$file) {
            echo json_encode(['success' => false, 'message' => 'File not found']);
            return;
        }
        
        // Update file status
        $this->File_model->update_status($file_id, 'rejected', $manager_id, $reason);
        
        // Log activity
        $this->Log_model->log_activity(
            $manager_id,
            'file_rejection',
            'Rejected file: ' . $file->file_name . ' (uploaded by ' . $file->uploader_name . ')'
        );
        
        // Send email notification
        $this->send_notification_email(
            $file->uploader_email,
            $file->uploader_name,
            $file->file_name,
            'rejected',
            $reason
        );
        
        echo json_encode([
            'success' => true,
            'message' => 'File rejected successfully'
        ]);
    }
    
    private function send_notification_email($to_email, $to_name, $file_name, $status, $reason = null) {
        $this->email->from('noreply@fileapproval.com', 'File Approval System');
        $this->email->to($to_email);
        
        if ($status === 'approved') {
            $this->email->subject('File Approved - ' . $file_name);
            $message = "
                <h2>File Approved</h2>
                <p>Dear $to_name,</p>
                <p>Your file <strong>$file_name</strong> has been approved by the manager.</p>
                <p>Thank you for using our system.</p>
            ";
        } else {
            $this->email->subject('File Rejected - ' . $file_name);
            $message = "
                <h2>File Rejected</h2>
                <p>Dear $to_name,</p>
                <p>Your file <strong>$file_name</strong> has been rejected by the manager.</p>
            ";
            
            if ($reason) {
                $message .= "<p><strong>Reason:</strong> $reason</p>";
            }
            
            $message .= "<p>Please make the necessary changes and resubmit.</p>";
        }
        
        $this->email->message($message);
        
        // Send email (suppress errors in development)
        @$this->email->send();
    }
    
    public function download($file_id) {
        $file = $this->File_model->get_file_by_id($file_id);
        
        if (!$file) {
            show_404();
            return;
        }
        
        // Check permissions
        $user_id = $this->session->userdata('user_id');
        $role = $this->session->userdata('role');
        
        if ($role !== 'manager' && $file->user_id != $user_id) {
            show_error('Unauthorized access', 403);
            return;
        }
        
        // Download file
        $this->load->helper('download');
        force_download($file->file_path, NULL);
    }
}