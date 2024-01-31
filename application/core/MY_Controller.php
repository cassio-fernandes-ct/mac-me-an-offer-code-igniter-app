<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Base_Controller extends CI_Controller {
 
    function __construct()
    {
        parent::__construct();
 
        //do whatever you want to do when object instantiate
    }
}
 
class Admin_Controller extends CI_Controller {
 
    function __construct()
    {
        parent::__construct();
        if(empty($this->session->userdata('admin_session')))
        {
            redirect('admin/login','refresh');
        }
        $this->load->model('admin/commonmodel');
        $this->breadcrumbs->push('Home', base_url());
    }
}