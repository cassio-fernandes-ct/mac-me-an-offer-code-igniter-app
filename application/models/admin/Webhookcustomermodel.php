<?php 
use Bigcommerce\Api\Client as Bigcommerce;

class Webhookcustomermodel extends CI_Model
{
	function __construct()
	{
		
		$this->setting_table            = "setting";
		$this->customers_log_table		= "customers_log";
		$this->address_log_table		= "address_log";
		$this->orders                   = "orders";
		$this->tmp_Cpassword            = "tmp_Cpassword";
		include(APPPATH.'/third_party/bcapi/vendor/autoload.php');
		$this->currentdate = date('Y-m-d H:i:s');
	}

	public function emailaddressexits($email){
	
		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE `email` = '".$email."'");
		return $query->num_rows();

	}	


	public function emailaddressexitss($email){
	
		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE `email` = '".$email."'");
		return $query->row_array();

	}

	public function checkexitaddress($q)
	{

		$query = $this->db->query("SELECT * FROM ".$this->address_log_table." ".$q."");
		return $query->num_rows();
	}

	public function checkCustomerDataSame($data,$store){
		$a = '';
		if($store == 'mmo'){
			$a = 'AND customer_group_id_mmo =  "'.$data->customer_group_id.'" ';
		}else{
			
			$a = 'AND customer_group_id_mmt =  "'.$data->customer_group_id.'" ';
		}
		$q = 'WHERE email = "'.$data->email.'" AND firstname = "'.$data->first_name.'" AND lastname = "'.$data->last_name.'" AND phonenumber = "'.$data->phone.'" AND 	company = "'.$data->company.'"' ;
		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." ".$q." ".$a."");
		return $query->num_rows();
		//$data =  $query->num_rows();
		//echo "<pre>";
		//print_r($data);
		//exit;
	}

	public function WebhookCallcustomerupdate($customer_id,$scope,$customerid)
	{
		$log_file_message = array();
		$this->Bigcommerceapiconfig();
		if(isset($scope) && !empty($scope) &&  $scope == 'store/customer/created')
		{	
			$customer_data = Bigcommerce::getCustomer($customer_id);
			
			$emailexit = $this->emailaddressexits($customer_data->email);
				
				if($emailexit == 0)
				{
					$createcustomermmt = $this->createcustomermmt($customer_data);
					
					if(isset($createcustomermmt['id']) && !empty($createcustomermmt['id']))
					{
						$datainsert['email']          = $customer_data->email;
						$datainsert['bc_id_mmo']      = $customer_data->id;
						$datainsert['bc_id_mmt']      = $createcustomermmt['id'];
						$datainsert['createdfrom']    = 'mmo';
						$datainsert['firstname']      = $customer_data->first_name;
						$datainsert['lastname']       = $customer_data->last_name;
						$datainsert['phonenumber']    = $customer_data->phone;
						$datainsert['storecredit']    = $customer_data->store_credit;
						$datainsert['webhook']        = 1;
						$datainsert['flag']           = 0;
						$datainsert['last_update_date'] = $this->currentdate;
						$this->db->insert($this->customers_log_table,$datainsert);
					}
					
				}else{

					$emailaddressexitss = $this->emailaddressexitss($customer_data->email);
					$createcustomermmt = $this->updatecustomermmt($customer_data,$emailaddressexitss['bc_id_mmt']);
					
					$datainsert['email']          	= $customer_data->email;
					$datainsert['bc_id_mmo']      	= $customer_data->id;
					$datainsert['firstname']      	= $customer_data->first_name;
					$datainsert['lastname']       	= $customer_data->last_name;
					$datainsert['phonenumber']    	= $customer_data->phone;
					$datainsert['storecredit']    	= $customer_data->store_credit;
					$datainsert['webhook']        	= 1;
					$datainsert['flag']           	= 1;
					$datainsert['last_update_date'] = $this->currentdate;
					$this->db->where('email',$datainsert['email']);
					$this->db->update($this->customers_log_table,$datainsert);
				}
				$this->createCustomerAndAddress($customer_id);
		}
		elseif(isset($scope) && !empty($scope) && $scope == 'store/customer/updated')
		{

			$customer_data = Bigcommerce::getCustomer($customer_id);
			
			$emailexit = $this->emailaddressexits($customer_data->email);
			
			/*$minitesmore = $this->emailaddressexitss($customer_data->email);
			$to_time = strtotime($this->currentdate);
			$from_time = strtotime($minitesmore['last_update_date']);
			$minutecount = round(abs($to_time - $from_time) / 60,2)." minute"; */
			$checksamedata = $this->checkCustomerDataSame($customer_data,$store='mmo');
			//exit;
			if($checksamedata == 0)
			{
				
				if($emailexit == 0)
				{
					$createcustomermmt = $this->createcustomermmt($customer_data);
					
					if(isset($createcustomermmt['id']) && !empty($createcustomermmt['id']))
					{
						$datainsert['email']          	= $customer_data->email;
						$datainsert['bc_id_mmo']      	= $customer_data->id;
						$datainsert['bc_id_mmt']      	= $createcustomermmt['id'];
						$datainsert['createdfrom']    	= 'mmo';
						$datainsert['firstname']      	= $customer_data->first_name;
						$datainsert['lastname']       	= $customer_data->last_name;
						$datainsert['phonenumber']    	= $customer_data->phone;
						$datainsert['company']    	= $customer_data->company;
						$datainsert['storecredit']    	= $customer_data->store_credit;
						$datainsert['webhook']        	= 1;
						$datainsert['flag']           	= 0;
						$datainsert['last_update_date'] = $this->currentdate;
						$this->db->insert($this->customers_log_table,$datainsert);
					}
					
				}else{

					$emailaddressexitss = $this->emailaddressexitss($customer_data->email);
					$createcustomermmt = $this->updatecustomermmt($customer_data,$emailaddressexitss['bc_id_mmt']);
					
					$datainsert['email']          	= $customer_data->email;
					$datainsert['bc_id_mmo']      	= $customer_data->id;
					$datainsert['firstname']      	= $customer_data->first_name;
					$datainsert['lastname']       	= $customer_data->last_name;
					$datainsert['phonenumber']    	= $customer_data->phone;
					$datainsert['company']    	    = $customer_data->company;
					$datainsert['storecredit']    	= $customer_data->store_credit; 
					$datainsert['customer_group_id_mmt']    = $createcustomermmt['customer_group_id_mmt'];
					$datainsert['customer_group_id_mmo']    = $customer_data->customer_group_id;
					$datainsert['webhook']          = 1;
					$datainsert['flag']             = 1;
					$datainsert['last_update_date'] = $this->currentdate;
					
					$this->db->where('email',$datainsert['email']);
					$this->db->update($this->customers_log_table,$datainsert);
				}

			}else{

				$log_file_message['minutes']     =  $checksamedata;
				$log_file_message['customer_id'] =  $customer_id;
				
				//file_put_contents(APPPATH.'third_party/hook/minutes/less_'.$customer_id.'.txt',print_r($log_file_message,TRUE));
			}


		}elseif(isset($scope) && !empty($scope) && $scope == 'store/customer/deleted'){
			
			if(isset($customer_id) && !empty($customer_id))
			{
				$array['queryStringCheckonQuery'] = 'bc_id_mmo';
				$array['customer_id'] = $customer_id;
				
				$getMmtAddressidCustomerid = $this->CustomerIdGet($array); 
				
				if(isset($getMmtAddressidCustomerid['bc_id_mmt']) && !empty($getMmtAddressidCustomerid['bc_id_mmt']))
				{
					$this->deleteCustomer($getMmtAddressidCustomerid,$store = 'mmt');

				}
				
			}
		}elseif(isset($scope) && !empty($scope) && $scope == 'store/customer/address/deleted'){

			if(isset($customer_id) && !empty($customer_id))
			{
				$array['queryStringCheckonQuery'] = 'mmo_address_id';
				$array['address_id'] = $customer_id;
				$getMmtAddressidCustomerid = $this->addressIdCustomerIdGet($array); 
				
				if(isset($getMmtAddressidCustomerid['mmt_address_id']) && !empty($getMmtAddressidCustomerid['mmt_address_id']) && isset($getMmtAddressidCustomerid['mmt_customer_id']) && !empty($getMmtAddressidCustomerid['mmt_customer_id']))
				{
					$this->deleteACustomerAddress($getMmtAddressidCustomerid,$store = 'mmt');
					
				}

			}

		}elseif (isset($scope) && !empty($scope) && ($scope == 'store/customer/address/created' OR $scope == 'store/customer/address/updated')){
			
			if($scope == 'store/customer/address/created')
			{
				

				$data = array();
				$configure = $this->getBcConfig();
				$data['storeurl']      = $configure['storeurl'];
				$data['apipath']       = $configure['apipath'];
				$data['apitoken']      = $configure['apitoken'];
				$data['storehas']      = $configure['storehas'];
				$data['client_id']     = $configure['client_id'];
				$data['client_secret'] = $configure['client_secret'];
				$data['address_id']    = $customer_id;
				$data['customer_id']   = $customerid;
				$getaddress = $this->bc_api($data);
				
				if(isset($getaddress) && !empty($getaddress))
				{
					$getmmtcustomerid = $this->getcustermid($store = 'bc_id_mmo',$customerid);
					
					if(isset($getmmtcustomerid['bc_id_mmt']) && !empty($getmmtcustomerid['bc_id_mmt']))
					{

						$q = "where mmo_customer_id = ".$data['customer_id']." AND mmo_address_id = ".$data['address_id'];
						$checkexitaddress  = $this->checkexitaddress($q);
						//file_put_contents(APPPATH.'third_party/hook/debug/checkexiste.txt',print_r($checkexitaddress,TRUE));

						if($checkexitaddress == 0)
						{
							//file_put_contents(APPPATH.'third_party/hook/debug/mmowebhook.txt',print_r($data,TRUE));
							$address = $this->createaddressmmt($getaddress,$getmmtcustomerid['bc_id_mmt']);
							
							//file_put_contents(APPPATH.'third_party/hook/debug/mmowebhook_1.txt',print_r($data,TRUE));
							
							$insert['mmo_customer_id'] = $data['customer_id'];
							$insert['mmo_address_id']  = $data['address_id'];
							$insert['mmt_address_id']  = $address['address_id']; 
							$insert['mmt_customer_id'] = $address['customer_id'];
							$insert['created_date']    	= $this->currentdate;
							$insert['last_update_date'] = $this->currentdate;
							$insert['cron']             = 'no2';
							$this->db->insert($this->address_log_table,$insert);
						}
					}
				}
				else{
					//file_put_contents(APPPATH.'third_party/hook/debug/checkexiste_more.txt',print_r($checkexitaddress,TRUE));

				}

			}elseif ($scope == 'store/customer/address/updated') {
				$address_id  = $customer_id;
				$customer_id = $customerid;
				if(isset($address_id) && !empty($address_id) && isset($customer_id) && !empty($customer_id))
				{
					$q = "WHERE `mmo_customer_id` = '".$customer_id."' AND `mmo_address_id` = '".$address_id."'";
					$getmmtcustomerid = $this->getCustomerAddress($q);
					
					if(isset($getmmtcustomerid['mmt_address_id']) && !empty($getmmtcustomerid['mmt_address_id']) && isset($getmmtcustomerid['mmt_customer_id']) && !empty($getmmtcustomerid['mmt_customer_id']))
					{
						$to_time = strtotime($this->currentdate);
						$from_time = strtotime($getmmtcustomerid['last_update_date']);
						$minutecount = round(abs($to_time - $from_time) / 60,2)." minute"; 
						
						if($minutecount > 2)
						{

							$log_file_message['minutes']     =  $minutecount;
							$log_file_message['customer_id'] =  $address_id;
							//file_put_contents(APPPATH.'third_party/hook/minutes/more_address_'.$address_id.'.txt',print_r($log_file_message,TRUE));

							$data = array();
							$configure = $this->getBcConfig();
							$data['storeurl']      = $configure['storeurl'];
							$data['apipath']       = $configure['apipath'];
							$data['apitoken']      = $configure['apitoken'];
							$data['storehas']      = $configure['storehas'];
							$data['client_id']     = $configure['client_id'];
							$data['client_secret'] = $configure['client_secret'];
							$data['address_id']    = $address_id;
							$data['customer_id']   = $customer_id;

							$getaddress = $this->bc_api($data);
						
							if(isset($getaddress) && !empty($getaddress))
							{
								$getmmtcustomerid = $this->updateaddressmmt($getmmtcustomerid['mmt_customer_id'],$getmmtcustomerid['mmt_address_id'],$getaddress);

								$update['mmo_customer_id'] = $customer_id;
								$update['mmo_address_id']  = $address_id;
								$update['mmt_address_id']  = $getmmtcustomerid->id; 
								$update['mmt_customer_id'] = $getmmtcustomerid->customer_id;
								$update['last_update_date'] = $this->currentdate;
								$update['cron']             = 'no66';
								$where = array('mmo_customer_id ' => $customer_id , 'mmo_address_id ' => $address_id);
								$this->db->where($where);
								$this->db->update($this->address_log_table, $update); 

							}
						}else{

							$log_file_message['minutes']     =  $minutecount;
							$log_file_message['customer_id'] =  $address_id;
							//file_put_contents(APPPATH.'third_party/hook/minutes/less_address_'.$customer_id.'.txt',print_r($log_file_message,TRUE));
						}

					}else{
						
						
						$data = array();
						$configure = $this->getBcConfig();
						$data['storeurl']      = $configure['storeurl'];
						$data['apipath']       = $configure['apipath'];
						$data['apitoken']      = $configure['apitoken'];
						$data['storehas']      = $configure['storehas'];
						$data['client_id']     = $configure['client_id'];
						$data['client_secret'] = $configure['client_secret'];
						$data['address_id']    = $address_id;
						$data['customer_id']   = $customer_id;

						$getaddress = $this->bc_api($data);

						if(isset($getaddress) && !empty($getaddress))
						{
							$getmmtcustomerid = $this->getcustermid($store = 'bc_id_mmo',$customerid);

							if(isset($getmmtcustomerid['bc_id_mmt']) && !empty($getmmtcustomerid['bc_id_mmt']))
							{
								$address = $this->createaddressmmt($getaddress,$getmmtcustomerid['bc_id_mmt']);
								$insert['mmo_customer_id'] = $data['customer_id'];
								$insert['mmo_address_id']  = $data['address_id'];
								$insert['mmt_address_id'] = $address['address_id']; 
								$insert['mmt_customer_id'] = $address['customer_id'];
								$insert['created_date'] = $this->currentdate;
								$insert['last_update_date'] = $this->currentdate;
								$insert['cron']             = 'no3';
								$this->db->insert($this->address_log_table,$insert);
								
							}
						}
					}

				}
				
			}
			
		}
	}

	public function addressIdCustomerIdGet($array){
		
		$where = 'WHERE '.$array['queryStringCheckonQuery'].' = '.$array['address_id'];
		$query = $this->db->query( "SELECT * FROM ".$this->address_log_table." ".$where."");

		return $query->row_array();


	}

	public function CustomerIdGet($array)
	{
		$where = 'WHERE '.$array['queryStringCheckonQuery'].' = '.$array['customer_id'];
		$query = $this->db->query( "SELECT * FROM ".$this->customers_log_table." ".$where."");
		return $query->row_array();
	}

	public function createCustomerAndAddress($customer_id)
	{

		$this->Bigcommerceapiconfig();
		$getCustomerAddresses = Bigcommerce::getCustomerAddresses($customer_id);

		
		//file_put_contents(APPPATH.'third_party/hook/customer/address/updateeeeeeee.txt',print_r($getCustomerAddresses,TRUE));
		
		if(isset($getCustomerAddresses) && !empty($getCustomerAddresses))
		{
			foreach ($getCustomerAddresses as  $value) 
			{
				
				$configure = $this->getBcConfig();
				$data['storeurl']      = $configure['storeurl'];
				$data['apipath']       = $configure['apipath'];
				$data['apitoken']      = $configure['apitoken'];
				$data['storehas']      = $configure['storehas'];
				$data['client_id']     = $configure['client_id'];
				$data['client_secret'] = $configure['client_secret'];
				$data['address_id']    = $value->id;
				$data['customer_id']   = $value->customer_id;

				$getaddress = $this->bc_api($data);
				
				if(isset($getaddress) && !empty($getaddress))
				{
					$getmmtcustomerid = $this->getcustermid($store = 'bc_id_mmo',$value->customer_id);
					echo "<pre>";
					print_r($getmmtcustomerid);
					if(isset($getmmtcustomerid['bc_id_mmt']) && !empty($getmmtcustomerid['bc_id_mmt']))
					{

						$q = "where mmo_customer_id = ".$data['customer_id']." AND mmo_address_id = ".$data['address_id'];
						$checkexitaddress  = $this->checkexitaddress($q);
						//file_put_contents(APPPATH.'third_party/hook/debug/checkexiste.txt',print_r($checkexitaddress,TRUE));
						//file_put_contents(APPPATH.'third_party/hook/debug/QUERY_MMO.txt',print_r($q,TRUE));
						if($checkexitaddress == 0)
						{
							$address = $this->createaddressmmt($getaddress,$getmmtcustomerid['bc_id_mmt']);
							echo "<pre>";
							print_r($address);
							$insert['mmo_customer_id'] = $data['customer_id'];
							$insert['mmo_address_id']  = $data['address_id'];
							$insert['mmt_address_id']  = $address['address_id']; 
							$insert['mmt_customer_id'] = $address['customer_id'];
							$insert['created_date']    	= $this->currentdate;
							$insert['last_update_date'] = $this->currentdate;
							$insert['cron']             = 'no4';
							$this->db->insert($this->address_log_table,$insert);
						
						}
					}
				}
			}
		}

	}

	public function updateaddressmmt($customerid,$addressid,$getaddress)
	{
		$this->Bigcommerceapiconfigmmt();
		$customer_data = Bigcommerce::updateCustomerAddress($customerid,$addressid,$getaddress['customeraddress']);
		return $customer_data;
	}

	public function createaddressmmt($getaddress,$bc_id_mmt)
	{
		$this->Bigcommerceapiconfigmmt();
		$customer_data = Bigcommerce::createCustomeraddress($bc_id_mmt,$getaddress['customeraddress']);
		$address['address_id'] = $customer_data->id;
		$address['customer_id'] = $customer_data->customer_id;
		return $address;
	}

	public function createcustomermmt($getcustomer)
	{
		$this->Bigcommerceapiconfigmmt();
		
		$data['customercreate']['company']     = $getcustomer->company;
		$data['customercreate']['first_name']   = $getcustomer->first_name;
		$data['customercreate']['last_name']    = $getcustomer->last_name;
		$data['customercreate']['email']        = $getcustomer->email;
		$data['customercreate']['phone']        = $getcustomer->phone;
		$data['customercreate']['store_credit'] = $getcustomer->store_credit;
		
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
		// echo "<pre>";
		// print_r($storecustomer);
		// exit;
		return $storecustomer;
	}

	public function updatecustomermmt($getcustomer,$id)
	{
		$storecustomergroupid = $this->customer_group_id_get($getcustomer->customer_group_id,$store='mmo');
		
		$this->Bigcommerceapiconfigmmt();
		
		$data['customercreate']['company']     = $getcustomer->company;
		$data['customercreate']['first_name']   = $getcustomer->first_name;
		$data['customercreate']['last_name']    = $getcustomer->last_name;
		$data['customercreate']['email']        = $getcustomer->email;
		$data['customercreate']['phone']        = $getcustomer->phone;
		$data['customercreate']['store_credit'] = $getcustomer->store_credit;
		$data['customercreate']['customer_group_id'] = @$storecustomergroupid['id_mmt'];
		
		try {

			$createdustomerid = Bigcommerce::updateCustomer($id,$data['customercreate']);
		  	$id = $createdustomerid->id;
		  	$storecustomer['id'] = $id;
		  	$storecustomer['customer_group_id_mmt'] = $createdustomerid->customer_group_id;
		}

		//catch exception
		catch(Exception $e) {

		  	$error = 'Message: ' .$e->getMessage();
		  	$storecustomer['error'] = $error;

		}
	
		return $storecustomer;
	}

	public function Bigcommerceapiconfigmmt()
	{
		$config_data = $this->getBcConfig();
		$bcstoreurl		= $config_data['storeurltrades'];
		$client_id		= $config_data['client_idtrades'];
		$store_hash		= $config_data['storehastrades'];
		$auth_token		= $config_data['apitokentrades'];

		// $bcstoreurl		= $config_data['storeurltrades'];
		// $client_id		= '46s39g803b8wd2rexb6oecgo0bio898';
		// $store_hash		= 'xt5en0q8kf';
		// $auth_token		= '9dbhxept77e8besu4i1x1vp55w06ifk';

		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); // Bc class connection				
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError();	
	}


	public function Bigcommerceapiconfig()
	{

		$config_data = $this->getBcConfig();
		$bcstoreurl		= $config_data['storeurl'];
		$client_id		= $config_data['client_id'];
		$store_hash		= $config_data['storehas'];
		$auth_token		= $config_data['apitoken'];

		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); // Bc class connection				
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError(); 

	}

	public function getcustermid($store,$customerid)
	{
		$where = 'WHERE '.$store.' = '.$customerid;
		$query = $this->db->query( "SELECT * FROM ".$this->customers_log_table." ".$where."");
		return $query->row_array();
	}

	public function getCustomerAddress($q)
	{

		$query = $this->db->query( "SELECT * FROM ".$this->address_log_table." ".$q."");
		return $query->row_array();
	}


	public function bc_api($data)
	{
		$response = Bigcommerce::getCustomerAddress($data['customer_id'],$data['address_id']);
		
		if(isset($response->id) && !empty($response->id))
		{
			$street_2 = '';if(isset($response->street_2) && !empty($response->street_2)){$street_2 = $response->street_2;}

			$state = ' ';if(isset($response->state) && !empty($response->state)){$state = $response->state;}

			$company = ' ';if(isset($response->company) && !empty($response->company)){$company = $response->company;}
			$city = ' ';if(isset($response->city) && !empty($response->city)){$city = $response->city;}
			$zip = ' ';if(isset($response->zip) && !empty($response->zip)){$zip = $response->zip;}
			$country = ' ';if(isset($response->country) && !empty($response->country)){$country = $response->country;}
			$phone = ' ';if(isset($response->phone) && !empty($response->phone)){$phone = $response->phone;}
			$address_type = ' ';if(isset($response->address_type) && !empty($response->address_type)){$address_type = $response->address_type;}

			$storedata['customeraddress']['first_name']  				= $response->first_name;
			$storedata['customeraddress']['last_name']   				= $response->last_name;
			$storedata['customeraddress']['company']     				= $company;
			$storedata['customeraddress']['street_1']    				= $response->street_1;
			$storedata['customeraddress']['street_2']    				= $street_2;
			$storedata['customeraddress']['city']        				= $city;
			$storedata['customeraddress']['state']       				= $state;
			$storedata['customeraddress']['zip']         				= $zip;
			$storedata['customeraddress']['country']    				= $country;
			$storedata['customeraddress']['phone']       				= $phone;
			$storedata['customeraddress']['address_type']       		= $address_type;

			return $storedata;

		}
	}


	public function getBcConfig(){
		$query = $this->db->get_where($this->setting_table,array('id'=>1));
		return $query->row_array();
	}

	public function deleteACustomerAddress($getMmtAddressidCustomerid,$store)
	{

		if($store == 'mmt'){
			$this->Bigcommerceapiconfigmmt();
				$createdustomerid = Bigcommerce::deleteACustomerAddress($getMmtAddressidCustomerid['mmt_customer_id'],$getMmtAddressidCustomerid['mmt_address_id']);

		}elseif ($store == 'mmo') {
			$this->Bigcommerceapiconfig();
			$createdustomerid = Bigcommerce::deleteACustomerAddress($getMmtAddressidCustomerid['mmo_customer_id'],$getMmtAddressidCustomerid['mmo_address_id']);
		}

	

		

    	try {


			$this->db->where('id',$getMmtAddressidCustomerid['id']);
    		$this->db->delete($this->address_log_table);

    		

		  	$storecustomer['id'] =$getMmtAddressidCustomerid['id'];
		}

		//catch exception
		catch(Exception $e) {

		  	$error = 'Message: ' .$e->getMessage();
		  	$storecustomer['error'] = $error;

		}

		//file_put_contents(APPPATH.'third_party/hook/c/updateeeeeeee.txt',print_r($storecustomer,TRUE));

	}

	public function deleteCustomer($getMmtAddressidCustomerid,$store = 'mmt')
	{
		if($store == 'mmt'){
			$this->Bigcommerceapiconfigmmt();
				$createdustomerid = Bigcommerce::deleteCustomer($getMmtAddressidCustomerid['bc_id_mmt'],$store);

		}elseif ($store == 'mmo') {
			$this->Bigcommerceapiconfig();
				$createdustomerid = Bigcommerce::deleteCustomer($getMmtAddressidCustomerid['bc_id_mmo'],$store);
		}

	

		try {

			$this->db->where('id',$getMmtAddressidCustomerid['id']);
    		$this->db->delete($this->customers_log_table);

    		$this->db->where('mmt_customer_id',$getMmtAddressidCustomerid['bc_id_mmt']);
    		$this->db->delete($this->address_log_table);
    		
    		$this->db->where('email',$getMmtAddressidCustomerid['email']);
    		$this->db->delete($this->tmp_Cpassword);

		  	$storecustomer['id'] = $getMmtAddressidCustomerid['id'];
		}

		//catch exception
		catch(Exception $e) {

		  	$error = 'Message: ' .$e->getMessage();
		  	$storecustomer['error'] = $error;

		}

		//file_put_contents(APPPATH.'third_party/hook/c/updateeeeeeee.txt',print_r($storecustomer,TRUE));
	}

	public function demodeletecustomermmo($id)
	{
		$this->Bigcommerceapiconfig();
		$createdustomerid = Bigcommerce::deleteCustomer($id);
		echo "<pre>";
		print_r($createdustomerid);
		exit();
	}

	//--------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	public function WebhookCallcustomerupdate_mmt($customer_id,$scope,$customerid)
	{
		$this->Bigcommerceapiconfigmmt();
		
	
		if(isset($scope) && !empty($scope) &&  $scope == 'store/customer/created')
		{	

			$customer_data = Bigcommerce::getCustomer($customer_id);

			$emailexit = $this->emailaddressexits($customer_data->email);
			

				if($emailexit == 0)
				{
					$createcustomermmt = $this->createcustomermmo($customer_data);
					
					if(isset($createcustomermmt['id']) && !empty($createcustomermmt['id']))
					{
						$datainsert['email']          = $customer_data->email;
						$datainsert['bc_id_mmt']      = $customer_data->id;
						$datainsert['bc_id_mmo']      = $createcustomermmt['id'];
						$datainsert['createdfrom']    = 'mmt';
						$datainsert['firstname']      = $customer_data->first_name;
						$datainsert['lastname']       = $customer_data->last_name;
						$datainsert['phonenumber']    = $customer_data->phone;
						$datainsert['storecredit']    = $customer_data->store_credit;
						$datainsert['webhook']        = 1;
						$datainsert['flag']           = 0;
						$this->db->insert($this->customers_log_table,$datainsert);
					}
					
				}else{

					$emailaddressexitss = $this->emailaddressexitss($customer_data->email);
					$createcustomermmt = $this->updatecustomermmo($customer_data,$emailaddressexitss['bc_id_mmo']);
					
					$datainsert['email']          = $customer_data->email;
					$datainsert['bc_id_mmt']      = $customer_data->id;
					$datainsert['firstname']      = $customer_data->first_name;
					$datainsert['lastname']       = $customer_data->last_name;
					$datainsert['phonenumber']    = $customer_data->phone;
					$datainsert['storecredit']    = $customer_data->store_credit;
					$datainsert['webhook']        = 1;
					$datainsert['flag']           = 1;

					$this->db->where('email',$datainsert['email']);
					$this->db->update($this->customers_log_table,$datainsert);
				}
				
				$this->createCustomerAndAddress_mmt($customer_id);


		}
		elseif(isset($scope) && !empty($scope) && $scope == 'store/customer/updated')
		{

			$customer_data = Bigcommerce::getCustomer($customer_id);
			$emailexit = $this->emailaddressexits($customer_data->email);

			/*$minitesmore = $this->emailaddressexitss($customer_data->email);
			$to_time = strtotime($this->currentdate);
			$from_time = strtotime($minitesmore['last_update_date']);
			$minutecount = round(abs($to_time - $from_time) / 60,2)." minute"; */

			$checksamedata = $this->checkCustomerDataSame($customer_data,$store='mmt');
			
			if($checksamedata == 0)
			{
				
				if($emailexit == 0)
				{
					$createcustomermmo = $this->createcustomermmo($customer_data);
					
					if(isset($createcustomermmo['id']) && !empty($createcustomermmo['id']))
					{
						$datainsert['email']          = $customer_data->email;
						$datainsert['bc_id_mmt']      = $customer_data->id;
						$datainsert['bc_id_mmo']      = $createcustomermmo['id'];
						$datainsert['createdfrom']    = 'mmt';
						$datainsert['firstname']      = $customer_data->first_name;
						$datainsert['lastname']       = $customer_data->last_name;
						$datainsert['phonenumber']    = $customer_data->phone;
						$datainsert['company']    	  = $customer_data->company;
						$datainsert['storecredit']    = $customer_data->store_credit;
						$datainsert['webhook']        = 1;
						$datainsert['flag']           = 0;
						
						$this->db->insert($this->customers_log_table,$datainsert);
					}
					
				}else{

					$emailaddressexitss = $this->emailaddressexitss($customer_data->email);

					$createcustomermmo = $this->updatecustomermmo($customer_data,$emailaddressexitss['bc_id_mmo']);
					
					$datainsert['email']          = $customer_data->email;
					$datainsert['bc_id_mmt']      = $customer_data->id;
					$datainsert['firstname']      = $customer_data->first_name;
					$datainsert['lastname']       = $customer_data->last_name;
					$datainsert['phonenumber']    = $customer_data->phone;
					$datainsert['company']    	  = $customer_data->company;
					$datainsert['storecredit']    = $customer_data->store_credit;
					$datainsert['customer_group_id_mmt']    = $customer_data->customer_group_id;
					$datainsert['customer_group_id_mmo']    = $createcustomermmo['customer_group_id_mmo'];
					$datainsert['webhook']        = 1;
					$datainsert['flag']           = 1;

					$this->db->where('email',$datainsert['email']);
					$this->db->update($this->customers_log_table,$datainsert);
				
				}
			}else{

			//	$log_file_message['minutes']     =  $minutecount;
				$log_file_message['customer_id'] =  $customer_id;
				$log_file_message['store']       =  'mmt';

				//file_put_contents(APPPATH.'third_party/hook/minutes/less_'.$customer_id.'.txt',print_r($log_file_message,TRUE));
			}

		}elseif(isset($scope) && !empty($scope) && $scope == 'store/customer/deleted'){
			echo "hellllloooo";
			if(isset($customer_id) && !empty($customer_id))
			{
				$array['queryStringCheckonQuery'] = 'bc_id_mmt';
				$array['customer_id'] = $customer_id;
				
				$getMmtAddressidCustomerid = $this->CustomerIdGet($array); 
				
				if(isset($getMmtAddressidCustomerid['bc_id_mmo']) && !empty($getMmtAddressidCustomerid['bc_id_mmo']))
				{
					$this->deleteCustomer($getMmtAddressidCustomerid,$store = 'mmo');

				}
				
			}
		}elseif(isset($scope) && !empty($scope) && $scope == 'store/customer/address/deleted'){

			if(isset($customer_id) && !empty($customer_id))
			{
				echo "<pre>";
				print_r($customer_id);
				
				$array['queryStringCheckonQuery'] = 'mmt_address_id';
				$array['address_id'] = $customer_id;
				$getMmtAddressidCustomerid = $this->addressIdCustomerIdGet($array); 
			
				if(isset($getMmtAddressidCustomerid['mmt_address_id']) && !empty($getMmtAddressidCustomerid['mmt_address_id']) && isset($getMmtAddressidCustomerid['mmt_customer_id']) && !empty($getMmtAddressidCustomerid['mmt_customer_id']))
				{
					$data = $this->deleteACustomerAddress($getMmtAddressidCustomerid,$store = 'mmo');


					
				}

			}

		}elseif (isset($scope) && !empty($scope) && ($scope == 'store/customer/address/created' OR $scope == 'store/customer/address/updated')){
			
			if($scope == 'store/customer/address/created')
			{
				
				$data = array();
				$configure = $this->getBcConfig();
				$data['storeurl']      = $configure['storeurl'];
				$data['apipath']       = $configure['apipath'];
				$data['apitoken']      = $configure['apitoken'];
				$data['storehas']      = $configure['storehas'];
				$data['client_id']     = $configure['client_id'];
				$data['client_secret'] = $configure['client_secret'];
				$data['address_id']    = $customer_id;
				$data['customer_id']   = $customerid;

				
				//file_put_contents(APPPATH.'third_party/hook/debug/mmtwebhook.txt',print_r($data,TRUE));

				$getaddress = $this->bc_api($data);
				
				if(isset($getaddress) && !empty($getaddress))
				{
					$getmmtcustomerid = $this->getcustermid($store = 'bc_id_mmt',$customerid);
					
					if(isset($getmmtcustomerid['bc_id_mmo']) && !empty($getmmtcustomerid['bc_id_mmo']))
					{
						$q = "where mmt_customer_id = ".$data['customer_id']." AND mmt_address_id = ".$data['address_id'];
						$checkexitaddress  = $this->checkexitaddress($q);

						
						//file_put_contents(APPPATH.'third_party/hook/debug/query-'.$this->currentdate.'.txt',print_r($q,TRUE));

						if($checkexitaddress == 0)
						{

							$address = $this->createaddressmmo($getaddress,$getmmtcustomerid['bc_id_mmo']);
							//file_put_contents(APPPATH.'third_party/hook/debug/mmtwebhook_1.txt',print_r($data,TRUE));
							$insert['mmt_customer_id'] 	= $data['customer_id'];
							$insert['mmt_address_id']  	= $data['address_id'];
							$insert['mmo_address_id'] 	= $address['address_id']; 
							$insert['mmo_customer_id'] 	= $address['customer_id'];
							$insert['created_date']    	= $this->currentdate;
							$insert['last_update_date'] = $this->currentdate;
							$insert['cron']             = 'no5';
							
							$this->db->insert($this->address_log_table,$insert);

						}
					}
				}else{

				//file_put_contents(APPPATH.'third_party/hook/debug/checkexiste_more_mmt.txt',print_r($checkexitaddress,TRUE));

				}
			}elseif ($scope == 'store/customer/address/updated') {

				
				$address_id  = $customer_id;
				$customer_id = $customerid;

				if(isset($address_id) && !empty($address_id) && isset($customer_id) && !empty($customer_id))
				{
					
					$q = "WHERE `mmt_customer_id` = '".$customer_id."' AND `mmt_address_id` = '".$address_id."'";
					$getmmocustomerid = $this->getCustomerAddress($q);
					
					if(isset($getmmocustomerid['mmo_customer_id']) && !empty($getmmocustomerid['mmo_customer_id']) && isset($getmmocustomerid['mmo_address_id']) && !empty($getmmocustomerid['mmo_address_id']))
					{

						$to_time = strtotime($this->currentdate);
						$from_time = strtotime($getmmocustomerid['last_update_date']);
						$minutecount = round(abs($to_time - $from_time) / 60,2)." minute"; 
						
						if($minutecount > 2)
						{

							$log_file_message['minutes']     =  $minutecount;
							$log_file_message['customer_id'] =  $address_id;
							//file_put_contents(APPPATH.'third_party/hook/minutes/more_address_'.$address_id.'.txt',print_r($log_file_message,TRUE));

							$data = array();
							$configure = $this->getBcConfig();

							$data['storeurl']      = $configure['storeurltrades'];
							$data['apipath']       = $configure['apipathtrades'];
							$data['apitoken']      = $configure['apitokentrades'];
							$data['storehas']      = $configure['storehastrades'];
							$data['client_id']     = $configure['client_idtrades'];
							$data['client_secret'] = $configure['client_secrettrades'];
							$data['address_id']    = $address_id;
							$data['customer_id']   = $customer_id;

							$getaddress = $this->bc_api($data);
							
							if(isset($getaddress) && !empty($getaddress))
							{
								
								$getmmtcustomerid = $this->updateaddressmmo($getmmocustomerid['mmo_customer_id'],$getmmocustomerid['mmo_address_id'],$getaddress);

								$update['mmt_customer_id'] 	= $customer_id;
								$update['mmt_address_id']  	= $address_id;
								$update['mmo_address_id'] 	= $getmmtcustomerid->id; 
								$update['mmo_customer_id'] 	= $getmmtcustomerid->customer_id;
								$update['last_update_date'] = $this->currentdate;

								
								$where = array('mmt_customer_id ' => $customer_id , 'mmt_address_id ' => $address_id);
								$this->db->where($where);
								$this->db->update($this->address_log_table, $update); 
							}
						}else{

							$log_file_message['minutes']     =  $minutecount;
							$log_file_message['customer_id'] =  $address_id;
							//file_put_contents(APPPATH.'third_party/hook/minutes/less_address_'.$customer_id.'.txt',print_r($log_file_message,TRUE));

						}
					}else{
						
						
						$data = array();
						$configure = $this->getBcConfig();
						$data['storeurl']      = $configure['storeurl'];
						$data['apipath']       = $configure['apipath'];
						$data['apitoken']      = $configure['apitoken'];
						$data['storehas']      = $configure['storehas'];
						$data['client_id']     = $configure['client_id'];
						$data['client_secret'] = $configure['client_secret'];
						$data['address_id']    = $address_id;
						$data['customer_id']   = $customer_id;

						$getaddress = $this->bc_api($data);

						if(isset($getaddress) && !empty($getaddress))
						{
							$getmmtcustomerid = $this->getcustermid($store = 'bc_id_mmt',$customerid);

							
							if(isset($getmmtcustomerid['bc_id_mmo']) && !empty($getmmtcustomerid['bc_id_mmo']))
							{
								$address = $this->createaddressmmo($getaddress,$getmmtcustomerid['bc_id_mmo']);
								$insert['mmt_customer_id'] 	= $data['customer_id'];
								$insert['mmt_address_id']  	= $data['address_id'];
								$insert['mmo_address_id'] 	= $address['address_id']; 
								$insert['mmo_customer_id'] 	= $address['customer_id'];
								$insert['created_date']    	= $this->currentdate;
								$insert['last_update_date'] = $this->currentdate;
								$insert['cron']             = 'no6';
								$this->db->insert($this->address_log_table,$insert);
								
							}
						}
					}



				}
				
			}
			
		}
	}

	public function customer_group_id_get($customer_group_id,$store)
	{

		$a = '';
		if($store == 'mmo'){
			$a = 'WHERE id_mmo =  "'.$customer_group_id.'"';
		}else{
			
			$a = 'WHERE id_mmt =  "'.$customer_group_id.'"';
		}
		
		$query = $this->db->query("SELECT * FROM customer_groups ".$a."");
		return $query->row_array();
		
	}

	public function updatecustomermmo($getcustomer,$id)
	{
		$storecustomergroupid = $this->customer_group_id_get($getcustomer->customer_group_id,$store='mmt');
		
		$this->Bigcommerceapiconfig();
		
		$data['customercreate']['company']     = $getcustomer->company;
		$data['customercreate']['first_name']   = $getcustomer->first_name;
		$data['customercreate']['last_name']    = $getcustomer->last_name;
		$data['customercreate']['email']        = $getcustomer->email;
		$data['customercreate']['phone']        = $getcustomer->phone;
		$data['customercreate']['store_credit'] = $getcustomer->store_credit;
		$data['customercreate']['customer_group_id'] = @$storecustomergroupid['id_mmo'];
		
		try {

			$createdustomerid = Bigcommerce::updateCustomer($id,$data['customercreate']);
		  	$id = $createdustomerid->id;
		  	$storecustomer['id'] = $id;
		  	$storecustomer['customer_group_id_mmo'] = $createdustomerid->customer_group_id;

		}

		//catch exception
		catch(Exception $e) {

		  	$error = 'Message: ' .$e->getMessage();
		  	$storecustomer['error'] = $error;

		}
	
		return $storecustomer;
	}

	public function createcustomermmo($getcustomer)
	{
		$this->Bigcommerceapiconfig();
		
		$data['customercreate']['company']     = $getcustomer->company;
		$data['customercreate']['first_name']   = $getcustomer->first_name;
		$data['customercreate']['last_name']    = $getcustomer->last_name;
		$data['customercreate']['email']        = $getcustomer->email;
		$data['customercreate']['phone']        = $getcustomer->phone;
		$data['customercreate']['store_credit'] = $getcustomer->store_credit;
		
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

	public function createCustomerAndAddress_mmt($customer_id)
	{
		
		$this->Bigcommerceapiconfigmmt();
		$getCustomerAddresses = Bigcommerce::getCustomerAddresses($customer_id);
		
		if(isset($getCustomerAddresses) && !empty($getCustomerAddresses))
		{
			foreach ($getCustomerAddresses as  $value) {
				
				$configure = $this->getBcConfig();
				$data['storeurl']      = $configure['storeurltrades'];
				$data['apipath']       = $configure['apipathtrades'];
				$data['apitoken']      = $configure['apitokentrades'];
				$data['storehas']      = $configure['storehastrades'];
				$data['client_id']     = $configure['client_idtrades'];
				$data['client_secret'] = $configure['client_secrettrades'];
				$data['address_id']    = $value->id;
				$data['customer_id']   = $value->customer_id;

			
				$getaddress = $this->bc_api($data);
				
				if(isset($getaddress) && !empty($getaddress))
				{
					$getmmtcustomerid = $this->getcustermid($store = 'bc_id_mmt',$value->customer_id);

					if(isset($getmmtcustomerid['bc_id_mmo']) && !empty($getmmtcustomerid['bc_id_mmo']))
					{
						$q = "where mmt_customer_id = ".$data['customer_id']." AND mmt_address_id = ".$data['address_id'];
						$checkexitaddress  = $this->checkexitaddress($q);

						
						//file_put_contents(APPPATH.'third_party/hook/debug/query_1.txt',print_r($q,TRUE));
						if($checkexitaddress == 0)
						{
							//file_put_contents(APPPATH.'third_party/hook/debug/query_12.txt',print_r($checkexitaddress,TRUE));
							$address = $this->createaddressmmo($getaddress,$getmmtcustomerid['bc_id_mmo']);
							//echo "<pre>";
							//print_r($address);
							
							$insert['mmt_customer_id'] 	= $data['customer_id'];
							$insert['mmt_address_id']  	= $data['address_id'];
							$insert['mmo_address_id'] 	= $address['address_id']; 
							$insert['mmo_customer_id'] 	= $address['customer_id'];
							$insert['created_date']    	= $this->currentdate;
							$insert['last_update_date'] = $this->currentdate;
							$insert['cron']             = 'no1';
							$this->db->insert($this->address_log_table,$insert);
							//file_put_contents(APPPATH.'third_party/hook/debug/query_14.txt',print_r($insert,TRUE));
							//echo "<pre>";
							//print_r($insert);
						}
					}
				}
				else{
					//file_put_contents(APPPATH.'third_party/hook/debug/query_13.txt',print_r($checkexitaddress,TRUE));
				}
			}
		}

	}
	
	public function createaddressmmo($getaddress,$bc_id_mmt)
	{
		$this->Bigcommerceapiconfig();
		$customer_data = Bigcommerce::createCustomeraddress($bc_id_mmt,$getaddress['customeraddress']);
		$address['address_id'] = $customer_data->id;
		$address['customer_id'] = $customer_data->customer_id;
		return $address;
	} 

	public function updateaddressmmo($customerid,$addressid,$getaddress)
	{
		$this->Bigcommerceapiconfig();
		$customer_data = Bigcommerce::updateCustomerAddress($customerid,$addressid,$getaddress['customeraddress']);

		return $customer_data;
	}

	//order webhook call 


	public function orderwebhook($order_data_s){
		
		$checkOrderExistOrNot =  $this->checkOrderExistOrNot($order_data_s);
	
		$this->Bigcommerceapiconfigmmt();
		if($checkOrderExistOrNot == 0){ 
			$orderdetils  = Bigcommerce::getOrder($order_data_s);
			$orderproduct  = Bigcommerce::getOrderProducts($order_data_s);

			$orderdata['orderId']              = $orderdetils->id;
			$orderdata['customer_id']          = $orderdetils->customer_id;
			$orderdata['items_total']          = $orderdetils->items_total;
			$orderdata['total_amount']         = $orderdetils->total_inc_tax;
			$orderdata['status']               = $orderdetils->status;
			$orderdata['date_created']         = date('M dS Y',strtotime($orderdetils->date_created));
			$orderdata['date_modified']        = date('M dS Y',strtotime($orderdetils->date_modified));
			$orderdata['is_deleted']           = $orderdetils->is_deleted;
			$orderdata['product_thumbnail_url'] = '';
			$orderdata['error'] =   '';
			$this->storecreditupdate($orderdetils->customer_id);
			$this->Bigcommerceapiconfigmmt();
			try {

			    if(isset($orderproduct{0}->product_id) && !empty($orderproduct{0}->product_id))
				{
					$getProductImages  = Bigcommerce::getProductImages($orderproduct{0}->product_id);
					$tt = $this->searchForId(1,$getProductImages);
					if(isset($tt->thumbnail_url) && !empty($tt->thumbnail_url))
					{
						$orderdata['product_thumbnail_url']        =  $this->db->escape_str($tt->thumbnail_url);
					}
				}
			} catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			    $orderdata['error'] = $e->getMessage();

			}

			$this->db->insert($this->orders,$orderdata);

		}else{

			$orderdetils  = Bigcommerce::getOrder($order_data_s);
			//echo "<pre>";
			//print_r($orderdetils->store_credit_amount);
			//exit;
			$orderproducts  = Bigcommerce::getOrderProducts($order_data_s);
			$orderproduct = end($orderproducts);
			
			$orderdataupdate['orderId']              = $orderdetils->id;
			$orderdataupdate['customer_id']          = $orderdetils->customer_id;
			$orderdataupdate['items_total']          = $orderdetils->items_total;
			$orderdataupdate['total_amount']         = $orderdetils->total_inc_tax;
			$orderdataupdate['status']               = $orderdetils->status;
			$orderdataupdate['date_created']         = date('M dS Y',strtotime($orderdetils->date_created));
			$orderdataupdate['date_modified']        = date('M dS Y',strtotime($orderdetils->date_modified));
			$orderdataupdate['is_deleted']           = $orderdetils->is_deleted;
			$orderdataupdate['product_thumbnail_url'] = '';
			$orderdataupdate['error'] =   '';

			$this->storecreditupdate($orderdetils->customer_id);
			$this->Bigcommerceapiconfigmmt();
			try {

			    if(isset($orderproduct->product_id) && !empty($orderproduct->product_id))
				{

					$getProductImages  = Bigcommerce::getProductImages($orderproduct->product_id);
					$tt = $this->searchForId(1,$getProductImages);
					if(isset($tt->thumbnail_url) && !empty($tt->thumbnail_url))
					{
						$orderdataupdate['product_thumbnail_url']        =  $this->db->escape_str($tt->thumbnail_url);
					}
				}
			} catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			    $orderdataupdate['error'] = $e->getMessage();

			}

			$this->db->where('orderId', $orderdataupdate['orderId'] );
			$this->db->update($this->orders,$orderdataupdate);
		}
		
	}

	public function storecreditupdate($customer_id )
	{
		if(isset($customer_id) && !empty($customer_id))
		{
			$this->Bigcommerceapiconfigmmt();
			$customer_data = Bigcommerce::getCustomer($customer_id);
			
			$array['queryStringCheckonQuery'] = 'bc_id_mmt';
			$array['customer_id'] = $customer_id;
					
			$getMmtAddressidCustomerid = $this->CustomerIdGet($array);
			if(isset($getMmtAddressidCustomerid['bc_id_mmo']) && !empty($getMmtAddressidCustomerid['bc_id_mmo']))
			{
				$this->Bigcommerceapiconfig();
				$data['customercreate']['store_credit'] = $customer_data->store_credit;
				$createdustomerid = Bigcommerce::updateCustomer($getMmtAddressidCustomerid['bc_id_mmo'],$data['customercreate']);
				$mmo_array['id'] = $createdustomerid->id;

				$arrayquery['bc_id_mmo'] = $getMmtAddressidCustomerid['bc_id_mmo'];
				$arrayquery['bc_id_mmt'] = $customer_id;

				$datad['storecredit'] =  $customer_data->store_credit;

				$this->db->where($arrayquery);
				$this->db->update($this->customers_log_table, $datad);
			}
			
			
		}
	}

	public function checkOrderExistOrNot($order_data_s)
	{
		$query_product_bc_data = $this->db->query("SELECT * FROM ".$this->orders." WHERE orderId = '".$order_data_s."'");
		$bc_product_data = $query_product_bc_data->num_rows();
		return $bc_product_data;
	}

	function searchForId($id, $array) {
	   foreach ($array as $key => $val) {
	   
	       if ($val->is_thumbnail == $id) {
	       		//file_put_contents(APPPATH.'third_party/hook/order/query_14.txt',print_r($val,TRUE));
							
	           return $val;
	       }
	   }
	   return null;
	}	
}
?>