<?php 
ini_set('display_errors','On');
error_reporting(E_ALL);
use Bigcommerce\Api\Connection;
use Bigcommerce\Api\Client as Bigcommerce;
class Webhookcustomer extends CI_controller
{
	function __construct()
	{
		parent::__construct();	
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');
		$this->load->model('admin/webhookcustomermodel');
		include(APPPATH.'/third_party/bcapi/vendor/autoload.php');
		
	}
	
	public function customerhook()
	{	
		
		$webhookContent = '';
		$other_data = $_SERVER;

		$webhook = fopen('php://input' , 'rb');
		while (!feof($webhook)) {
		$webhookContent.= fread($webhook, 4096);

		}
		fclose($webhook);
		$webhookContentobj = json_decode($webhookContent);

		$other_data = $_SERVER;	
		/*file_put_contents(APPPATH.'third_party/hook/mmao/mmaooo_server_'.rand().'.txt',print_r($webhookContentobj,TRUE));
		file_put_contents(APPPATH.'third_party/hook/mmao/mmao_'.$webhookContentobj->data->id.'.txt',print_r($webhookContentobj,TRUE));*/

		if(isset($webhookContentobj->data->id) && !empty($webhookContentobj->data->id))
		{
			$customerid = "";
			if(isset($webhookContentobj->data->address->customer_id) && !empty($webhookContentobj->data->address->customer_id))
			{
				$customerid = $webhookContentobj->data->address->customer_id;
			}

			$data = $webhookContentobj->data->id.'_'.$webhookContentobj->scope.'_'.$customerid;

			//	
			if(isset($webhookContentobj->scope) && !empty($webhookContentobj->scope))
			{
				$this->webhookcustomermodel->WebhookCallcustomerupdate($webhookContentobj->data->id,$webhookContentobj->scope,$customerid);
			}

		}
	}

	

	public function insertcustomer(){

		$this->webhookcustomermodel->WebhookCallcustomerupdate($customer_id='190149',$scope = 'store/customer/created',$customerid='');

		//$this->webhookcustomermodel->WebhookCallcustomerupdate_mmt($customer_id='682',$scope = 'store/customer/address/created',$customerid='185');
		
		//$this->webhookcustomermodel->WebhookCallcustomerupdate($customer_id='180377',$scope = 'store/customer/address/created',$customerid='190029');

		//$this->webhookcustomermodel->bc_api();
	}

	public function customerhookmmt()
	{
		$webhookContent = '';
		$webhook = fopen('php://input' , 'rb');
		while (!feof($webhook)) {
		$webhookContent.= fread($webhook, 4096);

		}
		fclose($webhook);

		$webhookContentobj = json_decode($webhookContent);

		$other_data = $_SERVER;
		
		//file_put_contents(APPPATH.'third_party/hook/mmt/1cu_'.rand().'.txt',print_r($webhookContentobj,TRUE));
		
		if(isset($webhookContentobj->data->id) && !empty($webhookContentobj->data->id))
		{
			$customerid = "";
			if(isset($webhookContentobj->data->address->customer_id) && !empty($webhookContentobj->data->address->customer_id))
			{
				$customerid = $webhookContentobj->data->address->customer_id;
			}

			$data = $webhookContentobj->data->id.'_'.$webhookContentobj->scope.'_'.$customerid;

			
			if(isset($webhookContentobj->scope) && !empty($webhookContentobj->scope))
			{
				$this->webhookcustomermodel->WebhookCallcustomerupdate_mmt($webhookContentobj->data->id,$webhookContentobj->scope,$customerid);
			}
		}
	}

	

	public function deletecustomer()
	{
		$id = $this->input->get('id');
		$this->webhookcustomermodel->demodeletecustomermmo($id);

	}

	public function orderwebhook()
	{
		$webhookContent = '';
		$webhook = fopen('php://input' , 'rb');
		while (!feof($webhook)) {
		$webhookContent.= fread($webhook, 4096);

		}
		fclose($webhook);

		$webhookContentobj = json_decode($webhookContent);

		//$ramdom = date("d-m-y-h:i:s");
		//file_put_contents(APPPATH.'third_party/hook/order/'.$ramdom.'.txt',print_r($webhookContentobj,TRUE));
			
		if(isset($webhookContentobj->data->id) && !empty($webhookContentobj->data->id))
		{
			$this->webhookcustomermodel->orderwebhook($webhookContentobj->data->id);
			
		}

	}

	public function demoOrderwebwook(){

		$this->webhookcustomermodel->orderwebhook($order_id = '310');
	}
	
}