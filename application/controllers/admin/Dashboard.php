<?php
class Dashboard extends Admin_Controller {
	public function __construct()
    {
        parent::__construct();
        ini_set('memory_limit', '-1');
        $this->data['title'] = 'Admin Dashboard';
        
        $this->load->model("admin/dashboardmodel");
    }
    public function index()
    {
        
        $this->data['title'] = 'Admin Dashboard';
        $this->data['page'] = 'admin/dashboard';

        $this->data['totalcategory']   = $this->dashboardmodel->totalcategory();
        $this->data['totalserise']   = $this->dashboardmodel->totalserise();
        $this->data['totalquote']   = $this->dashboardmodel->totalquote();
        
        $this->load->view('admin/common/leftmenu',$this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/common/content');
        $this->load->view('admin/common/footer');
     
    }

    public function demo()
    {

        $this->load->view('admin/common/leftmenu');
        $this->load->view('admin/common/header');

        //$this->load->view('admin/common/content');
        $this->load->view('admin/common/footer');

    }
}