<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Test extends CI_Controller{

    protected $shipping_label_file_name = '';

	public function __construct()
    {
        parent::__construct();
        $this->load->model("productmodel");
        $this->load->model('admin/settingmodel');
        $this->load->library('email');
        header('Access-Control-Allow-Origin: *'); 

    }
}