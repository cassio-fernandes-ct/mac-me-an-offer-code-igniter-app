<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

	function __construct()
	{	
		parent::__construct();
		$this->load->model('customer/Customer_model');
	}

	public function index()
	{
		$this->load->helper('form');
		$this->load->view('customer/register');	
	}

	public function success()
	{
		$this->load->library('form_validation'); 
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{	
			if ($this->form_validation->run('customer_register') == TRUE)
	        {
				$customer = [
					'email'	=> 	get('email_id'),
					'password'	=> 	get('password'),
					'country'	=> 	get('country'),
					'first_name'	=> 	get('fname'),
					'last_name'	=> 	get('lname'),
					'address_line_1'	=> 	get('address_line_1'),
					'address_line_2'	=> 	get('address_line_2'),
					'company_name'=> 	get('company'),
					'city'	=> 	get('city'),
					'state'	=> 	get('state'),
					'pincode'	=> 	get('pincode'),
					'phone_number'=> 	get('phone'),
				];
				var_dump($this->Customer_model->register($customer));
				exit;
	        }
			$this->index();
		}
		else
			return redirect(base_url().'register');
	}

}
