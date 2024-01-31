<?php
//if (!defined('BASEPATH')) {
   // exit('No direct script access allowed');
//}

class Login extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/commonmodel');
        
        $this->data['title'] = 'Admin Login';
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push('Admin', base_url('news'));
    }
    public function index()
    {   
        
        $session_data = $this->session->userdata('admin_session');
        if (isset($session_data) && !empty($session_data)) {
            redirect('admin/dashboard');
        }
        $this->data['sub_title'] = 'Admin Login';
        $this->data['page'] = 'admin/login';
        $this->data['errmsg'] = '';
            
        if ($this->form_validation->run('admin_login') === FALSE)
        {   
            $this->data['page'] = 'admin/login';
        }
        if ($this->input->post('email') && $this->input->post('password')) {

          
            $this->authenticate();
            
        }
        $this->load->view($this->data['page'], $this->data);
    }


    public function authenticate()
    {
        $this->load->database();
        $email = $this->db->escape($this->input->post('email'));
        $query = $this->db->query("SELECT * from users where username=".$email." AND password='".md5($this->input->post('password'))."' AND status IN('yes')");
        //$query = $this->db->query("SELECT * from users where username='".$this->input->post('email')."' AND password='".md5($this->input->post('password'))."' AND status IN('yes')");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            //SET SESSION
            $user_session = array('user_id' => $row['id'], 'firstname' => $row['firstname'], 'lastname' => $row['lastname'], 'email' => $row['email'], 'username' => $row['username']);
            $this->session->set_userdata('admin_session', $user_session);
            redirect('admin/dashboard');
        } else {
            $this->data['errmsg'] = 'Invalid User Name or Password. Please Try Again.';
        }        
    }


    public function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/login', 'refresh');
    }


}
