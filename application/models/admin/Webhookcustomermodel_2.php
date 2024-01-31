<?php 
use Bigcommerce\Api\Client as Bigcommerce;

class Webhookcustomermodel extends CI_Model
{
	function __construct()
	{
		
		$this->setting_table            = "setting";
		$this->customers_log_table		= "customers_log";
		$this->address_log_table		= "address_log";

		
		include(APPPATH.'/third_party/bcapi/vendor/autoload.php');
	}

	public function emailaddressexits($email){
	
		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE `email` = '".$email."'");
		return $query->num_rows();

	}	

	public function emailaddressexitss($email){
	
		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE `email` = '".$email."'");
		return $query->row_array();

	}

	//mmt webhook 
	public function WebhookCallcustomerupdate_mmt($customer_id,$scope,$customerid)
	{
		$this->Bigcommerceapiconfigmmt();
		
	
		if(isset($scope) && !empty($scope) &&  $scope == 'store/customer/created')
		{	
			$customer_data = Bigcommerce::getCustomer($customer_id);
			
			$emailexit = $this->emailaddressexits($customer_data->email);
			
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
						$datainsert['webhook']        = 1;
						$datainsert['flag']           = 0;
						$this->db->insert($this->customers_log_table,$datainsert);
					}
					
				}else{

					$emailaddressexitss = $this->emailaddressexitss($customer_data->email);

					$createcustomermmt = $this->updatecustomermmt($customer_data,$emailaddressexitss['bc_id_mmt']);
					
					$datainsert['email']          = $customer_data->email;
					$datainsert['bc_id_mmo']      = $customer_data->id;
					$datainsert['firstname']      = $customer_data->first_name;
					$datainsert['lastname']       = $customer_data->last_name;
					$datainsert['phonenumber']    = $customer_data->phone;
					$datainsert['webhook']        = 1;
					$datainsert['flag']           = 1;

					$this->db->where('email',$datainsert['email']);
					$this->db->update($this->customers_log_table,$datainsert);
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
						$address = $this->createaddressmmt($getaddress,$getmmtcustomerid['bc_id_mmt']);
						
						$insert['mmo_customer_id'] = $data['customer_id'];
						$insert['mmo_address_id']  = $data['address_id'];
						$insert['mmt_address_id'] = $address['address_id']; 
						$insert['mmt_customer_id'] = $address['customer_id'];
						
						$this->db->insert($this->address_log_table,$insert);
						
					}
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
								
								$this->db->insert($this->address_log_table,$insert);
								
							}
						}
					}

				}
				
			}
			
		}
	}


	//mmo webhook
	public function WebhookCallcustomerupdate($customer_id,$scope,$customerid)
	{
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
						$datainsert['webhook']        = 1;
						$datainsert['flag']           = 0;
						$this->db->insert($this->customers_log_table,$datainsert);
					}
					
				}else{

					$emailaddressexitss = $this->emailaddressexitss($customer_data->email);
					$createcustomermmt = $this->updatecustomermmt($customer_data,$emailaddressexitss['bc_id_mmt']);
					
					$datainsert['email']          = $customer_data->email;
					$datainsert['bc_id_mmo']      = $customer_data->id;
					$datainsert['firstname']      = $customer_data->first_name;
					$datainsert['lastname']       = $customer_data->last_name;
					$datainsert['phonenumber']    = $customer_data->phone;
					$datainsert['webhook']        = 1;
					$datainsert['flag']           = 1;

					$this->db->where('email',$datainsert['email']);
					$this->db->update($this->customers_log_table,$datainsert);
				}
				$this->createCustomerAndAddress($customer_id);
		}
		elseif(isset($scope) && !empty($scope) && $scope == 'store/customer/updated')
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
						$datainsert['webhook']        = 1;
						$datainsert['flag']           = 0;
						$this->db->insert($this->customers_log_table,$datainsert);
					}
					
				}else{

					$emailaddressexitss = $this->emailaddressexitss($customer_data->email);
					$createcustomermmt = $this->updatecustomermmt($customer_data,$emailaddressexitss['bc_id_mmt']);
					
					$datainsert['email']          = $customer_data->email;
					$datainsert['bc_id_mmo']      = $customer_data->id;
					$datainsert['firstname']      = $customer_data->first_name;
					$datainsert['lastname']       = $customer_data->last_name;
					$datainsert['phonenumber']    = $customer_data->phone;
					$datainsert['webhook']        = 1;
					$datainsert['flag']           = 1;

					$this->db->where('email',$datainsert['email']);
					$this->db->update($this->customers_log_table,$datainsert);
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
						$address = $this->createaddressmmt($getaddress,$getmmtcustomerid['bc_id_mmt']);
						
						$insert['mmo_customer_id'] = $data['customer_id'];
						$insert['mmo_address_id']  = $data['address_id'];
						$insert['mmt_address_id'] = $address['address_id']; 
						$insert['mmt_customer_id'] = $address['customer_id'];
						
						$this->db->insert($this->address_log_table,$insert);
						
					}
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

	//mmo
	public function createCustomerAndAddress($customer_id){

		$this->Bigcommerceapiconfig();
		$getCustomerAddresses = Bigcommerce::getCustomerAddresses($customer_id);

		
		//file_put_contents(APPPATH.'third_party/hook/customer/address/updateeeeeeee.txt',print_r($getCustomerAddresses,TRUE));
		
		if(isset($getCustomerAddresses) && !empty($getCustomerAddresses))
		{
			foreach ($getCustomerAddresses as  $value) {
				
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
						$address = $this->createaddressmmt($getaddress,$getmmtcustomerid['bc_id_mmt']);
						echo "<pre>";
						print_r($address);
						$insert['mmo_customer_id'] = $data['customer_id'];
						$insert['mmo_address_id']  = $data['address_id'];
						$insert['mmt_address_id'] = $address['address_id']; 
						$insert['mmt_customer_id'] = $address['customer_id'];
						
						$this->db->insert($this->address_log_table,$insert);
						
					}
				}
			}
		}

	}

	//mmt
	public function createCustomerAndAddress_mmt($customer_id){

		$this->Bigcommerceapiconfigmmt();
		$getCustomerAddresses = Bigcommerce::getCustomerAddresses($customer_id);

		
	//	file_put_contents(APPPATH.'third_party/hook/customer/address/updateeeeeeee.txt',print_r($getCustomerAddresses,TRUE));
		
		if(isset($getCustomerAddresses) && !empty($getCustomerAddresses))
		{
			foreach ($getCustomerAddresses as  $value) {
				
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
					$getmmtcustomerid = $this->getcustermid($store = 'bc_id_mmt',$value->customer_id);
					echo "<pre>";
					print_r($getmmtcustomerid);
					if(isset($getmmtcustomerid['bc_id_mmo']) && !empty($getmmtcustomerid['bc_id_mmo']))
					{
						$address = $this->createaddressmmo($getaddress,$getmmtcustomerid['bc_id_mmo']);
						echo "<pre>";
						print_r($address);
						$insert['mmo_customer_id'] = $data['customer_id'];
						$insert['mmo_address_id']  = $data['address_id'];
						$insert['mmt_address_id'] = $address['address_id']; 
						$insert['mmt_customer_id'] = $address['customer_id'];
						
						$this->db->insert($this->address_log_table,$insert);
						
					}
				}
			}
		}

	}

	public function updateaddressmmt($customerid,$addressid,$getaddress)
	{
		$this->Bigcommerceapiconfigmmt();
		$customer_data = Bigcommerce::updateCustomerAddress($customerid,$addressid,$getaddress['customeraddress']);
	}

	public function createaddressmmt($getaddress,$bc_id_mmt)
	{
		$this->Bigcommerceapiconfigmmt();
		$customer_data = Bigcommerce::createCustomeraddress($bc_id_mmt,$getaddress['customeraddress']);
		$address['address_id'] = $customer_data->id;
		$address['customer_id'] = $customer_data->customer_id;
		return $address;
	}

	public function createaddressmmo($getaddress,$bc_id_mmt)
	{
		$this->Bigcommerceapiconfig();
		$customer_data = Bigcommerce::createCustomeraddress($bc_id_mmt,$getaddress['customeraddress']);
		$address['address_id'] = $customer_data->id;
		$address['customer_id'] = $customer_data->customer_id;
		return $address;
	}

	public function updatecustomermmo($getcustomer,$id)
	{
		$this->Bigcommerceapiconfig();
		
		$data['customercreate']['company']      = $getcustomer->company;
		$data['customercreate']['first_name']   = $getcustomer->first_name;
		$data['customercreate']['last_name']    = $getcustomer->last_name;
		$data['customercreate']['email']        = $getcustomer->email;
		$data['customercreate']['phone']        = $getcustomer->phone;
		$data['customercreate']['store_credit'] = $getcustomer->store_credit;
		
		try {

			$createdustomerid = Bigcommerce::updateCustomer($id,$data['customercreate']);
		  	$id = $createdustomerid->id;
		  	$storecustomer['id'] = $id;
		}

		//catch exception
		catch(Exception $e) {

		  	$error = 'Message: ' .$e->getMessage();
		  	$storecustomer['error'] = $error;

		}
		echo "<pre>";
		print_r($storecustomer);
		exit;
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
	
		return $storecustomer;
	}

	public function updatecustomermmt($getcustomer,$id)
	{
		$this->Bigcommerceapiconfigmmt();
		
		$data['customercreate']['company']     = $getcustomer->company;
		$data['customercreate']['first_name']   = $getcustomer->first_name;
		$data['customercreate']['last_name']    = $getcustomer->last_name;
		$data['customercreate']['email']        = $getcustomer->email;
		$data['customercreate']['phone']        = $getcustomer->phone;
		$data['customercreate']['store_credit'] = $getcustomer->store_credit;
		
		try {

			$createdustomerid = Bigcommerce::updateCustomer($id,$data['customercreate']);
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

	public function Bigcommerceapiconfigmmt()
	{
		$config_data = $this->getBcConfig();
		$bcstoreurl		= $config_data['storeurltrades'];
		$client_id		= $config_data['client_idtrades'];
		$store_hash		= $config_data['storehastrades'];
		$auth_token		= $config_data['apitokentrades'];

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

		}elseif ($store == 'mmo') {
			$this->Bigcommerceapiconfig();
		}

		$createdustomerid = Bigcommerce::deleteACustomerAddress($getMmtAddressidCustomerid['mmt_customer_id'],$getMmtAddressidCustomerid['mmt_address_id']);

		

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
		}elseif ($store == 'mmo') {
			$this->Bigcommerceapiconfig();
		}

		$createdustomerid = Bigcommerce::deleteCustomer($getMmtAddressidCustomerid['bc_id_mmt'],$store);

		try {

			$this->db->where('id',$getMmtAddressidCustomerid['id']);
    		$this->db->delete($this->customers_log_table);

    		$this->db->where('mmt_customer_id',$getMmtAddressidCustomerid['bc_id_mmt']);
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

	public function demodeletecustomermmo($id)
	{
		$this->Bigcommerceapiconfig();
		$createdustomerid = Bigcommerce::deleteCustomer($id);
		echo "<pre>";
		print_r($createdustomerid);
		exit();
	}
}
?>