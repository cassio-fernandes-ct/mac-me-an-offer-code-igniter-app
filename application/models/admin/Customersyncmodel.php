<?php 
use Bigcommerce\Api\Client as Bigcommerce;

class Customersyncmodel extends CI_Model
{
	function __construct()
	{
		
		$this->setting_table            = "setting";
		$this->customers_log_table		= "customers_log";
		$this->address_log_table		= "address_log";
		$this->orders                   = "orders";
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

	public function noSyncMmtIdGet()
	{

		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE `bc_id_mmt` IS NULL OR `bc_id_mmt` = '' ");
		return $query->num_rows();

	}

	public function noSyncMmtIdGetD()
	{

		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE `bc_id_mmt` IS NULL OR `bc_id_mmt` = '' ");
		return $query->result_array();

	}

	public function noSyncMmoIdGet(){

		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE `bc_id_mmo` IS NULL OR `bc_id_mmo` = '' ");
		return $query->num_rows();
	}

	public function noSyncMmoIdGetD(){

		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE `bc_id_mmo` IS NULL OR `bc_id_mmo` = '' ");
		return $query->result_array();
	}
}
?>