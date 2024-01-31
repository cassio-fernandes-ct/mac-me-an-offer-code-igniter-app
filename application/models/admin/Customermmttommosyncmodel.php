<?php 
use Bigcommerce\Api\Client as Bigcommerce;

class Customermmttommosyncmodel extends CI_Model
{
	function __construct()
	{
		
		$this->setting_table            = "setting";
		$this->customers_log_table		= "customers_log";
		$this->address_log_table		= "address_log";
		$this->mmt_customer             = "mmt_customer";
		include(APPPATH.'/third_party/bcapi/vendor/autoload.php');
		$this->currentdate = date('Y-m-d H:i:s');
	}

	public function getBcConfig(){
		$query = $this->db->get_where($this->setting_table,array('id'=>1));
		return $query->row_array();
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

	//$customer_data = Bigcommerce::getCustomer($customer_id);
	public function getTotalNuamberOfCustomerOrder_mmo(){
		$this->Bigcommerceapiconfig();
		$getCustomerCount = Bigcommerce::getCustomerCount();
		return $getCustomerCount;
	}

	public function getTotalNuamberOfCustomerOrder_mmt(){
		$this->Bigcommerceapiconfigmmt();
		$getCustomerCount = Bigcommerce::getCustomerCount();
		return $getCustomerCount;
	}

	public function getmmtcustomer()
	{

		$query = $this->db->query("SELECT * FROM ".$this->mmt_customer." WHERE done = 'no' LIMIT 0,3");
		$data = $query->result_array();
	
		foreach ($data as $value) {
			//$customer_address = $value['mmt_customer_id']
			$customer_address = '12';
			$this->deletecreatecustomer($customer_address);
		}
	}

	public function deletecreatecustomer($mmt_customer_id)
	{	
		$this->Bigcommerceapiconfigmmt();
		$customer_data = Bigcommerce::getCustomer($mmt_customer_id);
		$address_data = Bigcommerce::getCustomerAddresses($mmt_customer_id);
	
		$this->deletecustomermmo($customer_data->email);
	}

	public function deletecustomermmo($email){
		$this->Bigcommerceapiconfig();
		$array['email'] = $email;
		$customer_data = Bigcommerce::getCustomers($array);
		

	}

	public function deletecustomer($id){
		$this->Bigcommerceapiconfig();
		$createdustomerid = Bigcommerce::deleteCustomer($id);
		
	}
}
?>