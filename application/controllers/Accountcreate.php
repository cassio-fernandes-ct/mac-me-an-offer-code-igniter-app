<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
use Bigcommerce\Api\Client as Bigcommerce;
class Accountcreate extends CI_Controller {
    protected $perPage;

	function __construct()
	{	
		parent::__construct();
		header('Access-Control-Allow-Origin: *'); 
		$this->load->model("accountcreatemodel");
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');
		ini_set('display_errors','On');
		error_reporting(E_ALL);
		include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function index()
	{
		echo "product import";
	}

	public function getcustomerinformation()
	{
		$data = $this->input->post();
		
		$currentdate = date('Y-m-d H:i:s');

		$reginsert['emailaddress'] = $data['FormField'][1][1];
		$reginsert['firstname']    = $data['FormField'][2][4];
		$reginsert['lastname']     = $data['FormField'][2][5];
		$reginsert['phonenumber']  = $data['FormField'][2][7];
		$reginsert['company']      = $data['FormField'][2][6];

		$reginsertPassword['email']  = $data['FormField'][1][1];
		$reginsertPassword['password'] = $this->mylibrary->stringEncryption('encrypt', $data['FormField'][1][2]);


		$storedata['customercreate']['company']      				= $data['FormField'][2][6];
		$storedata['customercreate']['first_name']   				= $data['FormField'][2][4];
		$storedata['customercreate']['last_name']    				= $data['FormField'][2][5];
		$storedata['customercreate']['email']        				= $data['FormField'][1][1];
		$storedata['customercreate']['phone']        				= $data['FormField'][2][7];
		$storedata['customercreate']['_authentication']['password'] = $data['FormField'][1][2];
		
		$storedata['customeraddress']['first_name']  				= $data['FormField'][2][4];
		$storedata['customeraddress']['last_name']   				= $data['FormField'][2][5];
		$storedata['customeraddress']['company']     				= $data['FormField'][2][6];
		$storedata['customeraddress']['street_1']    				= $data['FormField'][2][8];
		$storedata['customeraddress']['street_2']    				= $data['FormField'][2][9];
		$storedata['customeraddress']['city']        				= $data['FormField'][2][10];
		$storedata['customeraddress']['state']       				= $data['FormField'][2][12];
		$storedata['customeraddress']['zip']         				= $data['FormField'][2][13];
		$storedata['customeraddress']['country']    				= $data['FormField'][2][11];
		$storedata['customeraddress']['phone']       				= $data['FormField'][2][7];

		
		if($data['store'] == 'mmo'){
			$storecustomerid = $this->createcustomer_into_macmeoffer($storedata);
		}

		if($data['store'] == 'mmt'){
			$storecustomerid = $this->createcustomer_into_mmt($storedata);
		}
		//mac offer tread then id set when live prossses and on customers_log table
		
		if(isset($storecustomerid['id']) && !empty($storecustomerid['id']))
		{
			if($data['store'] == 'mmo'){
				$datainsert['bc_id_mmt']  = $storecustomerid['id'];  
			}

			if($data['store'] == 'mmt'){
				$datainsert['bc_id_mmo']  = $storecustomerid['id'];  
			}

			$datainsert['email']            = $reginsert['emailaddress'];
			$datainsert['firstname']        = $reginsert['firstname'];
			$datainsert['lastname']         = $reginsert['lastname'];
			$datainsert['phonenumber']      = $reginsert['phonenumber'];
			$datainsert['company']          = $reginsert['company'];
			$datainsert['createdfrom']      = $data['store'];
			$datainsert['sync']             = 1;
			$datainsert['created_date']     = $currentdate;
			$datainsert['last_update_date'] = $currentdate;

			
			$message = $this->accountcreatemodel->addresgicustomerinfo($datainsert,$reginsertPassword);
					
			if(isset($message['succuss']) && !empty($message['succuss']))
			{
				$mess['succuss'] = $message['succuss'];
			}else{
				$mess['error'] = $message['error'];
			}
		}else{

			$mess['error'] = $storecustomerid['error'];
		}
        echo json_encode($mess);  
	}


	public function democustomerinfoarray()
	{

			/*$data['customercreate']['company']      = 'test company01';*/
			$data['customercreate']['first_name']   = 'test firs01';
			$data['customercreate']['last_name']    = 'test last01';
			$data['customercreate']['email']        = 'test2@test.com';
			/*$data['customercreate']['phone']        = '123456789';
			$data['customercreate']['_authentication']['password'] 	= 'test@321';

			$data['customeraddress']['first_name']  = 'test first01';
			$data['customeraddress']['last_name']   = 'test last01';
			$data['customeraddress']['company']     = 'test company01';
			$data['customeraddress']['street_1']    = '414 street 0101';
			$data['customeraddress']['street_2']    = '414 street 0201';
			$data['customeraddress']['city']        = 'city01';
			$data['customeraddress']['state']       = 'state01';
			$data['customeraddress']['zip']         = '12345601';
			$data['customeraddress']['country']     = 'Australia';
			$data['customeraddress']['phone']       = '123456789-23-01';*/

			$createdustomerid  = $this->createcustomer_into_macmeoffer($data);

			echo "<pre>";
			print_r($createdustomerid);
			exit;
		
	}

	public function shipping_address()
	{
		$data = $this->input->post();
		
		$currentdate = date('Y-m-d H:i:s');

		$datainsert['action']                                       = 'add'; 	
		if(isset($data['shipid']) && !empty($data['shipid']))
		{
			$datainsert['shipid'] 		                            = $data['shipid'];
			$datainsert['action']                                   = 'update'; 	
		}

		/*$datainsert['customeraddress']['first_name']  = 'test first01';
		$datainsert['customeraddress']['last_name']   = 'test last01';
		$datainsert['customeraddress']['company']     = 'test company01';
		$datainsert['customeraddress']['street_1']    = '414 street 0101';
		$datainsert['customeraddress']['street_2']    = '414 street 0201';
		$datainsert['customeraddress']['city']        = 'city01';
		$datainsert['customeraddress']['state']       = 'state01';
		$datainsert['customeraddress']['zip']         = '12345601';
		$datainsert['customeraddress']['country']     = 'Australia';
		$datainsert['customeraddress']['phone']       = '123456789-23-01';
		$datainsert['customer_id']              = '190024';
		$datainsert['store'] 					= 'mmo';  */
		$state = ' ';
		if(isset($data['FormField'][2][12]) && !empty($data['FormField'][2][12]))
		{
			$state = $data['FormField'][2][12];
		}

		$datainsert['customeraddress']['first_name']  				= $data['FormField'][2][4];
		$datainsert['customeraddress']['last_name']   				= $data['FormField'][2][5];
		$datainsert['customeraddress']['company']     				= $data['FormField'][2][6];
		$datainsert['customeraddress']['street_1']    				= $data['FormField'][2][8];
		$datainsert['customeraddress']['street_2']    				= $data['FormField'][2][9];
		$datainsert['customeraddress']['city']        				= $data['FormField'][2][10];
		$datainsert['customeraddress']['state']       				= $state;
		$datainsert['customeraddress']['zip']         				= $data['FormField'][2][13];
		$datainsert['customeraddress']['country']    				= $data['FormField'][2][11];
		$datainsert['customeraddress']['phone']       				= $data['FormField'][2][7];
		$datainsert['customer_id'] 									= $data['customer'];
		$datainsert['store'] 									    = $data['store'];
		
		$message = $this->accountcreatemodel->shippingaddress($datainsert);


	}

	public function createcustomer_into_macmeoffer($data)
	{
		$storecustomer  = array();
		$config_data = $this->accountcreatemodel->getBcConfig();
		
		$bcstoreurl		= $config_data['storeurltrades'];
		$client_id		= $config_data['client_idtrades'];
		//$store_hash		= 'z7godtn57o';
		$store_hash		= $config_data['storehastrades'];
		$auth_token		= $config_data['apitokentrades'];
	
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); 			
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError(); 

		try {

			$createdustomerid = Bigcommerce::createCustomer($data['customercreate']);
		  	$id = $createdustomerid->id;
		  	$storecustomer['id'] = $id;
		}

		//catch exception
		catch(Exception $e) {

		  	$error = 'Message: ' .$e->getMessage();
		  	$storecustomer['error'] = $error;

		}

		return $storecustomer;

	}

	public function createcustomer_into_mmt($data)
	{
		$storecustomer  = array();
		$config_data = $this->accountcreatemodel->getBcConfig();
		
		$bcstoreurl		= $config_data['storeurl'];
		$client_id		= $config_data['client_id'];
		//$store_hash		= 'z7godtn57o';
		$store_hash		= $config_data['storehas'];
		$auth_token		= $config_data['apitoken'];
	
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); 			
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError(); 

		try {

			$createdustomerid = Bigcommerce::createCustomer($data['customercreate']);
		  	$id = $createdustomerid->id;
		  	$storecustomer['id'] = $id;
		}

		//catch exception
		catch(Exception $e) {

		  	$error = 'Message: ' .$e->getMessage();
		  	$storecustomer['error'] = $error;

		}

		return $storecustomer;

	}

	
}
?>