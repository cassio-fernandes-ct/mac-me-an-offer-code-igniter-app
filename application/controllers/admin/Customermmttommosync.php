<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
use Bigcommerce\Api\Client as Bigcommerce;
class Customermmttommosync extends CI_Controller {
    protected $perPage;

	function __construct()
	{	
		parent::__construct();
		$this->load->model("admin/customermmttommosyncmodel");
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');
		
		include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function index()
	{
		$this->data['title'] = 'Customer';

		$this->data['totalcustomermmo']  = $this->customermmttommosyncmodel->getmmtcustomer();
		
	}

	public function deletecustomer(){
		$id = $this->input->get('id');
		$this->customermmttommosyncmodel->deletecustomer($id);
	}
}
?>