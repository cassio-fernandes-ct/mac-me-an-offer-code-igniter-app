<?php 
ini_set('display_errors','On');
error_reporting(E_ALL);
use Bigcommerce\Api\Connection;
use Bigcommerce\Api\Client as Bigcommerce;

class Customerupdate extends CI_controller
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


	Public function duplicataddressdeleteformmmo(){

			$deleteadd['mmo'] = $this->customerupdatemodel->duplicataddressdeleteformmmo();
			$deleteadd['mmt'] = $this->customerupdatemodel->duplicataddressdeleteformmmt();
		
	}

	/*public function librarydemo(){

		$config_data = $this->customerupdatemodel->getpassword();
		$i = 0;
		$pswinfo = array();
		foreach ($config_data as $value) {

	     	$pswinfo[$i]['email'] = $value['email'];
	        $pswinfo[$i]['password'] = $this->mylibrary->stringEncryption('encrypt', $value['password']);
	    	 $i++;
        }
       	$config_data = $this->customerupdatemodel->updatepassword($pswinfo);
	}*/

	public function checkexitaddress($q)
	{

		$query = $this->db->query("SELECT * FROM ".$this->address_log_table." ".$q."");
		return $query->num_rows();
	}

	public function Bigcommerceapiconfig()
	{

		$config_data = $this->customerupdatemodel->getBcConfig();
	
		$bcstoreurl		= $config_data['storeurl'];
		$client_id		= $config_data['client_id'];
		$store_hash		= $config_data['storehas'];
		$auth_token		= $config_data['apitoken'];

		@Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); // Bc class connection				
		@Bigcommerce::verifyPeer(false); 
		@Bigcommerce::failOnError(); 

	}

	public function Bigcommerceapiconfigmmt()
	{
		$config_data = $this->customerupdatemodel->getBcConfig();
		$bcstoreurl		= $config_data['storeurltrades'];
		$client_id		= $config_data['client_idtrades'];
		$store_hash		= $config_data['storehastrades'];
		$auth_token		= $config_data['apitokentrades'];

		@Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); // Bc class connection				
		@Bigcommerce::verifyPeer(false); 
		@Bigcommerce::failOnError();	
	}

	public function customerarray($customer,$password){
		$storedata = array();
		if(isset($customer['getCustomers']) && !empty($customer['getCustomers']))
		{
			$storedata['customercreate']['company']      				= $customer['getCustomers']{0}->company;
			$storedata['customercreate']['first_name']   				= $customer['getCustomers']{0}->first_name;
			$storedata['customercreate']['last_name']    				= $customer['getCustomers']{0}->last_name;
			$storedata['customercreate']['email']        				= $customer['getCustomers']{0}->email;
			$storedata['customercreate']['phone']        				= $customer['getCustomers']{0}->phone;
			$storedata['customercreate']['store_credit']        		= $customer['getCustomers']{0}->store_credit;
			$storedata['customercreate']['_authentication']['password'] = $this->mylibrary->stringEncryption('decrypt',$password);
		}

		if(isset($customer['getAddresses']) && !empty($customer['getAddresses']))
		{
			$i = 0;
			foreach ($customer['getAddresses'] as $response) {
				
				$street_2 = '';if(isset($response->street_2) && !empty($response->street_2)){$street_2 = $response->street_2;}

				$state = ' ';if(isset($response->state) && !empty($response->state)){$state = $response->state;}

				$company = ' ';if(isset($response->company) && !empty($response->company)){$company = $response->company;}
				$city = ' ';if(isset($response->city) && !empty($response->city)){$city = $response->city;}
				$zip = ' ';if(isset($response->zip) && !empty($response->zip)){$zip = $response->zip;}
				$country = ' ';if(isset($response->country) && !empty($response->country)){$country = $response->country;}
				$phone = ' ';if(isset($response->phone) && !empty($response->phone)){$phone = $response->phone;}
				$address_type = ' ';if(isset($response->address_type) && !empty($response->address_type)){$address_type = $response->address_type;}

				$storedata['customeraddress'][$i]['first_name']  				= $response->first_name;
				$storedata['customeraddress'][$i]['last_name']   				= $response->last_name;
				$storedata['customeraddress'][$i]['company']     				= $company;
				$storedata['customeraddress'][$i]['street_1']    				= $response->street_1;
				$storedata['customeraddress'][$i]['street_2']    				= $street_2;
				$storedata['customeraddress'][$i]['city']        				= $city;
				$storedata['customeraddress'][$i]['state']       				= $state;
				$storedata['customeraddress'][$i]['zip']         				= $zip;
				$storedata['customeraddress'][$i]['country']    				= $country;
				$storedata['customeraddress'][$i]['phone']       				= $phone;
				$storedata['customeraddress'][$i]['address_type']       		= $address_type;

				$storedata['customeraddress_id'][$i]['id']       		        = $response->id;
				$i++;
			}
		}

		
		return $storedata;
	}
	//mmo custmerget
	public function getcustomer($email = '',$password = '')
	{

		$this->Bigcommerceapiconfig();
		$getCustomers 	= '';
		$var          	= array();
		$customer    	= array();
		$customerarray  = array();
		$var['email']   = $email;

		$customer['getCustomers'] = @Bigcommerce::getCustomers($var);
		
		//echo $customer['getCustomers']{0}->id;
		//exit;
		if(isset($customer['getCustomers']{0}->id) && !empty($customer['getCustomers']{0}->id))
		{
			$customer['getAddresses'] = @Bigcommerce::getCustomerAddresses($customer['getCustomers']{0}->id);
			$customerarray = $this->customerarray($customer,$password);
			
			$bc_mmo_id = $customer['getCustomers']{0}->id;
			$this->updatecustomermmt($email,$flow = 'mmoTommt',$customerarray,$bc_mmo_id);
		}
	}

	public function updatecustomermmt($email = '',$flow = '',$customerarray = array(),$bc_mmo_id)
	{
		
		$this->Bigcommerceapiconfigmmt();
		$var['email'] = $email;
		$getCustomers = @Bigcommerce::getCustomers($var);
	
		if(isset($getCustomers{0}->id) && !empty($getCustomers{0}->id))
		{
			//$this->updatecustomermmt($email);
			if($flow != 'mmoTommt')
			{


			}else{

				$this->exist['mmt'][$getCustomers{0}->id] = $email;
				$dataupdate['bc_id_mmt']      	= $getCustomers{0}->id;
				$dataupdate['cron']      	    = 'yes';
				$this->db->where('email',$email);
				$this->db->update($this->customers_log_table,$dataupdate); 
			}

		}else{

				try {
					
					$createdustomerid = @Bigcommerce::createCustomer($customerarray['customercreate']);
				  	$id = $createdustomerid->id;
					$dataupdate = array();
				  	$dataupdate['email']          	= $email;
					$dataupdate['bc_id_mmt']      	= $id;
					$dataupdate['cron']      	    = 'yes';
					$dataupdate['last_update_date'] = $this->currentdate;
					$dataupdate['createdfrom']      = 'mmo';
					
					$this->db->where('email',$email);
					$this->db->update($this->customers_log_table,$dataupdate); 
					if(isset($customerarray['customeraddress']) && !empty($customerarray['customeraddress']))
					{
						$i = 0;
						foreach ($customerarray['customeraddress'] as  $value) {

							$q = "where mmo_customer_id = ".$bc_mmo_id." AND mmo_address_id = ".$customerarray['customeraddress_id'][$i]['id'];
							$checkexitaddress  = $this->checkexitaddress($q);
							
							if($checkexitaddress == 0){ 

								$customer_data = @Bigcommerce::createCustomeraddress($id,$value);	
								$insert['mmo_customer_id'] 	= $bc_mmo_id;
								$insert['mmo_address_id']  	= $customerarray['customeraddress_id'][$i]['id'];
								$insert['mmt_address_id']  	= $customer_data->id; 
								$insert['mmt_customer_id'] 	= $customer_data->customer_id;
								$insert['created_date']    	= $this->currentdate;
								$insert['last_update_date'] = $this->currentdate;
								$insert['cron']             = 'yes';
								
								$this->db->insert($this->address_log_table,$insert);

								$i++;
							}
						}
					}
				}
				
				catch(Exception $e) {

				  	$error = 'Message: ' .$e->getMessage();
					$this->exist['mmt']['error'][$email] = $error;
				  	// $this->exist['mmt']['error'][$getCustomers{0}->id] = $error;
				}
		}
	}

	public function noSyncMmtIdGet()
	{
		$getdetails = $this->customerupdatemodel->noSyncMmtIdGet();
		
		if(isset($getdetails) && !empty($getdetails))
		{
			foreach ($getdetails as  $value) {
				
				$this->getcustomer($value['email'],$value['password']);

			}

		}
		$this->session->set_userdata('mmtupdate','1'); 
		redirect('admin/Customersync');
		//$this->print_mem();

		//unset($storedata);

		//$this->print_mem();
	}

	function print_mem()
	{
	   /* Currently used memory */
	   $mem_usage = memory_get_usage();
	   
	   /* Peak memory usage */
	   $mem_peak = memory_get_peak_usage();

	   echo 'The script is now using: <strong>' . round($mem_usage / 1024) . 'KB</strong> of memory.<br>';
	   echo 'Peak usage: <strong>' . round($mem_peak / 1024) . 'KB</strong> of memory.<br><br>';
	}

	public function noSyncMmoIdGet(){

		$getdetails = $this->customerupdatemodel->noSyncMmoIdGet();
		
		if(isset($getdetails) && !empty($getdetails))
		{
			foreach ($getdetails as  $value) {

				$this->getcustomer_mmt($value['email'],$value['password']); 

			}
		}

		$this->session->set_userdata('mmoupdate','1'); 
		redirect('admin/Customersync');
	}

	public function getcustomer_mmt($email = '',$password = '')
	{
 
		$this->Bigcommerceapiconfigmmt();
		$getCustomers 	= '';
		$var          	= array();
		$customer    	= array();
		$customerarray  = array();
		$var['email'] = $email;
		  
		$customer['getCustomers'] = @Bigcommerce::getCustomers($var);
		 
		// echo $customer['getCustomers']{0}->id;
		// exit;
		if(isset($customer['getCustomers']{0}->id) && !empty($customer['getCustomers']{0}->id))
		{
			$customer['getAddresses'] = @Bigcommerce::getCustomerAddresses($customer['getCustomers']{0}->id);
			$customerarray = $this->customerarray($customer,$password);
			
			$bc_mmt_id = $customer['getCustomers']{0}->id;
			$this->updatecustomermmo($email,$flow = 'mmtTommo',$customerarray,$bc_mmt_id);
		}
	}

	public function updatecustomermmo($email = '',$flow = '',$customerarray = array(),$bc_mmt_id)
	{
		$this->Bigcommerceapiconfig();
		$var['email'] = $email;
		$getCustomers = @Bigcommerce::getCustomers($var);
		
		if(isset($getCustomers{0}->id) && !empty($getCustomers{0}->id))
		{
			//$this->updatecustomermmt($email);
			if($flow != 'mmtTommo')
			{


			}else{

				$this->exist['mmt'][$getCustomers{0}->id] = $email;
				
				$dataupdate['bc_id_mmo']      	= $getCustomers{0}->id;
				$dataupdate['cron']      	    = 'yes';
				
				$this->db->where('email',$email);
				$this->db->update($this->customers_log_table,$dataupdate); 
			}

		}else{
				try {
					
					$createdustomerid = Bigcommerce::createCustomer($customerarray['customercreate']);
					// echo "<pre>";print_r($createdustomerid);die;

				  	$id = $createdustomerid->id;
					$dataupdate = array();
				  	$dataupdate['email']          	= $email;
					$dataupdate['bc_id_mmo']      	= $id;
					$dataupdate['cron']      	    = 'yes';
					$dataupdate['last_update_date'] = $this->currentdate;
					$dataupdate['createdfrom']      = 'mmo';
					
					//echo "<pre>";
					//print_r($dataupdate);

					$this->db->where('email',$email);
					$this->db->update($this->customers_log_table,$dataupdate); 
					
					if(isset($customerarray['customeraddress']) && !empty($customerarray['customeraddress']))
					{
						$i = 0;
						foreach ($customerarray['customeraddress'] as  $value) {
							$q = "where mmt_customer_id = ".$bc_mmt_id." AND mmt_address_id = ".$customerarray['customeraddress_id'][$i]['id'];
							$checkexitaddress  = $this->checkexitaddress($q);
							
							
							if($checkexitaddress == 0){ 
								
							
							$customer_data = @Bigcommerce::createCustomeraddress($id,$value);	
							$insert['mmt_customer_id'] 	= $bc_mmt_id;
							$insert['mmt_address_id']  	= $customerarray['customeraddress_id'][$i]['id'];
							$insert['mmo_address_id']  	= $customer_data->id; 
							$insert['mmo_customer_id'] 	= $customer_data->customer_id;
							$insert['created_date']    	= $this->currentdate;
							$insert['last_update_date'] = $this->currentdate;
							$insert['cron']             = 'yes';

							$this->db->insert($this->address_log_table,$insert);
							//echo "<pre>";
							//print_r($insert);
							//echo "-------------------------";
							$i++;
							}
						}
					}
				}
				
				catch(Exception $e) { 
				  	$error = 'Message: ' .$e->getMessage();
				  	$this->exist['mmt']['error'][$getCustomers{0}->id] = $error;
				}
		}
	}
}