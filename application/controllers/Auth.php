

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Log_model');
    }
    
    public function login() {
        // Redirect if already logged in
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }
        
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');
            
            if ($this->form_validation->run() === FALSE) {
                $data['error'] = validation_errors();
                $this->load->view('auth/login', $data);
            } else {
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                
                $user = $this->User_model->authenticate($email, $password);
                
                if ($user) {
                    // Set session data
                    $session_data = [
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'logged_in' => TRUE
                    ];
                    $this->session->set_userdata($session_data);
                    
                    // Log activity
                    $this->Log_model->log_activity(
                        $user->id,
                        'login',
                        $user->name . ' logged in successfully'
                    );
                    
                    // Redirect based on role
                    redirect('dashboard');
                } else {
                    $data['error'] = 'Invalid email or password';
                    $this->load->view('auth/login', $data);
                }
            }
        } else {
            $this->load->view('auth/login');
        }
    }
    
    public function logout() {
        if ($this->session->userdata('user_id')) {
            // Log activity
            $this->Log_model->log_activity(
                $this->session->userdata('user_id'),
                'logout',
                $this->session->userdata('name') . ' logged out'
            );
        }
        
        // Destroy session
        $this->session->sess_destroy();
        redirect('login');
    }
    
    public function check_auth() {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }
}