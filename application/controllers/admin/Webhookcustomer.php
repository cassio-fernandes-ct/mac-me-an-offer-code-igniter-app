<?php 
// ini_set('display_errors','On');
// error_reporting(E_ALL);
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

		$webhook = fopen('php://input' , 'rb');
		while (!feof($webhook)) {
			$webhookContent.= fread($webhook, 4096);
		}

		fclose($webhook);
		$webhookContentobj = json_decode($webhookContent);

		// block endless requests for inactive customer
		if( isset( $webhookContentobj->data->id ) && 433165 == $webhookContentobj->data->id ) {
			return false;
		}

		if( isset( $webhookContentobj->data->customer_id ) && 433165 == $webhookContentobj->data->customer_id ) {
			return false;
		}

		// file_put_contents(APPPATH.'third_party/hook/mmao/mmo_server_'.rand().'.txt',print_r($webhookContentobj,TRUE));

		$date = new DateTime();

		$data = [
			'datetime' => $date->format( 'Y-m-d H:i:s' ),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'server' => $_SERVER,
			'get' => $_GET,
			'post' => $_POST,
			'request' => $_REQUEST,
			'data' => $webhookContentobj,
		];

		$data = PHP_EOL . json_encode( $data, JSON_PRETTY_PRINT ) . PHP_EOL;

		file_put_contents( APPPATH . 'third_party/webhook/logging.log', $data, FILE_APPEND );

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
				$this->webhookcustomermodel->WebhookCallcustomerupdate($webhookContentobj->data->id,$webhookContentobj->scope,$customerid);
			}

		}
	}

	

	public function insertcustomer(){

		$this->webhookcustomermodel->WebhookCallcustomerupdate($customer_id='409357',$scope = 'store/customer/created',$customerid='');

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
		
	//	file_put_contents(APPPATH.'third_party/hook/mmt/mmt_'.rand().'.txt',print_r($webhookContentobj,TRUE));
		
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

		$ramdom = date("d-m-y-h:i:s");
		//file_put_contents(APPPATH.'third_party/hook/order/'.$ramdom.'.txt',print_r($webhookContentobj,TRUE));
			
		if(isset($webhookContentobj->data->id) && !empty($webhookContentobj->data->id))
		{
			$this->webhookcustomermodel->orderwebhook($webhookContentobj->data->id);
			
		}

	}

	public function customergroup()
	{
		$webhookContent = '';
		$webhook = fopen('php://input' , 'rb');
		while (!feof($webhook)) {
		$webhookContent.= fread($webhook, 4096);

		}
		fclose($webhook);

		$webhookContentobj = json_decode($webhookContent);

		$ramdom = date("d-m-y-h:i:s");
		//file_put_contents(APPPATH.'third_party/hook/customergroup/'.$ramdom.'.txt',print_r($webhookContentobj,TRUE));

	}

	public function demoOrderwebwook(){

		$this->webhookcustomermodel->orderwebhook($order_id = '354010');
	}
	
}