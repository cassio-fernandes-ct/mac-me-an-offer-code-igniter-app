<?php 
ini_set('display_errors','On');
error_reporting(E_ALL);
use Bigcommerce\Api\Connection;
use Bigcommerce\Api\Client as Bigcommerce;

class Demo extends CI_controller
{
	public $exist = array();
	function __construct()
	{
		parent::__construct();	
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');
		$this->load->model('admin/customerupdatemodel');
		$this->currentdate = date('Y-m-d H:i:s');
		$this->customers_log_table = 'customers_log';
		$this->address_log_table   = 'address_log';
		

		include(APPPATH.'/third_party/bcapi/vendor/autoload.php');
		
	}

	public function index(){

		$data['1'] = 'ss';
		$data11['2'] = 'sfdfdfdff';

		$vars = array_keys(get_defined_vars());

		for ($i = 0; $i < sizeOf($vars); $i++) {
		    unset($vars[$i]);
		}
		unset($vars,$i);
		echo "<pre>";
	
	}

}