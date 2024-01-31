<?php
use Bigcommerce\Api\Client as Bigcommerce;
class Accountcreatemodel extends CI_Model
{
	function __construct()
	{
		$this->customer_table = "customers_log";
		$this->setting_table = "setting";
		$this->address_log_table = "address_log";
		$this->tmp_cpassword_table = "tmp_Cpassword";

		$this->load->database();
	//	include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function addresgicustomerinfo($reginsert,$reginsertPassword)
	{
		if(isset($reginsert) && !empty($reginsert))
		{
			$row = $this->existemailaddress($reginsert['email']);

			if($row == 0)
			{
		    	$this->db->insert($this->customer_table,$reginsert);
		    	if(isset($reginsertPassword) && !empty($reginsertPassword))
				{
		
		    		$this->db->insert($this->tmp_cpassword_table,$reginsertPassword);
		    	}
		    	$mess['succuss'] = "inserted";
		    }else{

		    	$mess['error'] = "Email address all ready exist.";
		    }
		}

		return $mess;

	}

	public function existemailaddress($reginsert)
	{
		$query = $this->db->query('select id from '.$this->customer_table." WHERE email  ='".$reginsert."'");
        return $query->num_rows();
	}

	public function getBcConfig()
	{
		$query = $this->db->query("SELECT * FROM ".$this->setting_table."");
		return $query->row_array();
	}

	public function getcustermid($store,$customerid)
	{
		$where = 'WHERE '.$store.' = '.$customerid;
		$query = $this->db->query( "SELECT * FROM ".$this->customer_table." ".$where."");
		return $query->row_array();
	}

	public function getBcConfigMmt()
	{
		$config_data = $this->getBcConfig();

		$bcstoreurl		= $config_data['storeurltrades'];
		$client_id		= $config_data['client_idtrades'];
		//$store_hash		= 'z7godtn57o';
		$store_hash		= $config_data['storehastrades'];
		$auth_token		= $config_data['apitokentrades'];
	
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); 			
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

	public function createmmocustomer($data,$id)
	{
		$this->Bigcommerceapiconfig();
		$customer_data = Bigcommerce::createCustomeraddress($id,$data['customeraddress']);
		$address['address_id'] = $customer_data->id;
		$address['customer_id'] = $customer_data->customer_id;
		return $address;

	}

	public function createmmtcustomer($data,$id)
	{
		$this->getBcConfigMmt();
		$customer_data = Bigcommerce::createCustomeraddress($id,$data['customeraddress']);
		$address['address_id'] = $customer_data->id;
		$address['customer_id'] = $customer_data->customer_id;
		return $address;
	}

	public function updatemmocustomer($data,$customerid,$addressid)
	{
		$this->Bigcommerceapiconfig();
		Bigcommerce::updateCustomerAddress($customerid,$addressid,$data['customeraddress']);
	}

	public function updatemmtcustomer($data,$customerid,$addressid)
	{
		$this->getBcConfigMmt();
		Bigcommerce::updateCustomerAddress($customerid,$addressid,$data['customeraddress']);
	}

	public function shippingaddress($data)
	{

		if($data['action'] == 'update'){

			if($data['store'] == 'mmo')
			{
				
				$id = $this->getcustermid($store = 'bc_id_mmo',$data['customer_id']);
				$mmo = $this->updatemmocustomer($data,$data['customer_id'],$data['shipid']);

			}

			if($data['store'] == 'mmt')
			{
				
				$getmmtcustomerid = $this->getcustermid($store = 'bc_id_mmt',$data['customer_id']);
				$mmo = $this->updatemmtcustomer($data,$data['customer_id'],$data['shipid']);
				
			}

		}elseif($data['action'] == 'add') {
			
			if($data['store'] == 'mmo')
			{
				
				$id = $this->getcustermid($store = 'bc_id_mmo',$data['customer_id']);
				$mmo = $this->createmmocustomer($data,$data['customer_id']);

			}

			if($data['store'] == 'mmt')
			{
				
				$getmmtcustomerid = $this->getcustermid($store = 'bc_id_mmt',$data['customer_id']);
				$mmo = $this->createmmtcustomer($data,$data['customer_id']);
				
			}


			
		}
	}

}
	
?>