

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->check_auth();
        $this->load->model('File_model');
        $this->load->model('Log_model');
    }
    
    private function check_auth() {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
    
    public function index() {
        $data['user'] = [
            'id' => $this->session->userdata('user_id'),
            'name' => $this->session->userdata('name'),
            'email' => $this->session->userdata('email'),
            'role' => $this->session->userdata('role')
        ];
        
        // Get statistics
        if ($data['user']['role'] === 'manager') {
            $data['stats'] = $this->File_model->get_statistics();
            $data['files'] = $this->File_model->get_all_files();
        } else {
            $data['stats'] = $this->File_model->get_statistics($data['user']['id']);
            $data['files'] = $this->File_model->get_user_files($data['user']['id']);
        }
        
        // Get recent activity logs
        if ($data['user']['role'] === 'manager') {
            $data['logs'] = $this->Log_model->get_recent_logs(20);
        } else {
            $data['logs'] = $this->Log_model->get_recent_logs(20, $data['user']['id']);
        }
        
        $this->load->view('dashboard/index', $data);
    }
}