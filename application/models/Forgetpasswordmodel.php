<?php
use Bigcommerce\Api\Client as Bigcommerce;
class Forgetpasswordmodel extends CI_Model
{
	function __construct()
	{
		$this->customer_table = "customers_log";
		$this->setting_table = "setting";
		$this->address_log_table = "address_log";
		$this->tmp_cpassword_table = "tmp_Cpassword";
		$this->load->database();
		include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function getBcConfig()
	{
		$query = $this->db->query("SELECT * FROM ".$this->setting_table."");
		return $query->row_array();
	}

	public function random_strings($length_of_string = 50)
    {
       $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
       return substr(str_shuffle($str_result),  
                          0, $length_of_string);
    }

    public function getCustomerIdFromEmail($data)
    {
    	
    	$res = array();

    	$email = $this->db->escape($data['email']);

    	$where = "where email = ".$email."";
    	
    	$query = $this->db->query("SELECT * FROM ".$this->customer_table." ".$where."");
		$customer_details = $query->row_array();
		$rendom_token =	$this->random_strings();

		$update['forget_password_token'] = $rendom_token;

		$where = array('email' => $data['email']);
		$this->db->where($where);
		$this->db->update($this->customer_table, $update); 

		if($data['store'] == 'mmo')
		{
			$res['customer_id'] = $customer_details['bc_id_mmo'];
		}elseif ($data['store'] == 'mmt') {
			$res['customer_id'] = $customer_details['bc_id_mmt'];
		}

		$res['forget_password_token'] = $rendom_token;
		
		return $res;
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

	public function forgetPasswordChange($password_details)
	{
		$checkTokenIsValid = $this->tokenIsValidOrNot($password_details);
		
		$result = array();
	
		if($checkTokenIsValid['total'] > 0)
		{
			$details = $checkTokenIsValid['details'];
			
			$this->changeBcPassword($password_details,$details);

			$update['forget_password_token'] = '';

			$where = array('email' => $checkTokenIsValid['details']['email']);
			$this->db->where($where);
			$this->db->update($this->customer_table, $update);

			//$updatePassword['password'] = md5($password_details['password']);
			$updatePassword['password'] = $this->mylibrary->stringEncryption('encrypt', $password_details['password']);
			//$updatePassword['password'] = $password_details['password'];

			$wheree = array('email' => $checkTokenIsValid['details']['email']);
			$this->db->where($wheree);
			$this->db->update($this->tmp_cpassword_table, $updatePassword);
			$result['suc'] = '1';

		}else{

			$result['error'] = '<div id = "alertmessagetokenerror" class="alertBox alertBox--error">
								    <div class="alertBox-column alertBox-icon">
								        <icon glyph="ic-error" class="icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg></icon>
								    </div>
								    <p class="alertBox-column alertBox-message">
								        <span>The link you were given in the Request Password email is invalid. Please request another new password to receive a new link.</span>
								    </p>
								</div>';
		}
		
		return $result;
	}

	public function tokenIsValidOrNot($password_details)
	{
		$where = '';
		if(isset($password_details['store']) && !empty($password_details['store']) && $password_details['store'] == 'mmo')
		{
			
			$where = 'WHERE bc_id_mmo = '.$password_details['c'].' AND forget_password_token = "'.$password_details['t'].'"';

		}elseif (isset($password_details['store']) && !empty($password_details['store']) && $password_details['store'] == 'mmt') {

			$where = 'WHERE bc_id_mmt = '.$password_details['c'].' AND forget_password_token = "'.$password_details['t'].'"';

		}

		$query = $this->db->query("SELECT * FROM ".$this->customer_table." ".$where."");
		$result['details'] = $query->row_array();
		$result['total'] = $query->num_rows();

		return $result;
	}

	public function changeBcPassword($password_details,$details)
	{
		if($password_details['store'] == 'mmo')
		{
			$this->updatepasswordmmo($password_details,$details);
			

		}elseif($password_details['store'] == 'mmt') 
		{
			$this->updatepasswordmmt($password_details,$details);
		}
		
	}


	public function updatepasswordmmo($password_details,$details)
	{
		$this->Bigcommerceapiconfig();
		$storedata['customercreate']['_authentication']['password'] = $password_details['password'];
		$createdustomerid = Bigcommerce::updateCustomer($password_details['c'],$storedata['customercreate']);

		if(isset($createdustomerid->id) && !empty($createdustomerid->id))
		{
			$this->getBcConfigMmt();
			$mmtcustomerupdate = Bigcommerce::updateCustomer($details['bc_id_mmt'],$storedata['customercreate']);
			
		}
		
	}

	public function updatepasswordmmt($password_details,$details)
	{
		$this->getBcConfigMmt();
		$storedata['customercreate']['_authentication']['password'] = $password_details['password'];
		$createdustomerid = Bigcommerce::updateCustomer($password_details['c'],$storedata['customercreate']);

		if(isset($createdustomerid->id) && !empty($createdustomerid->id))
		{
			$this->Bigcommerceapiconfig();
			$mmtcustomerupdate = Bigcommerce::updateCustomer($details['bc_id_mmo'],$storedata['customercreate']);
			
		}
	}

	public function checkcustomerexistormot($data){
		$where = '';
		if(isset($data['store']) && !empty($data['store']) && $data['store'] == 'mmo')
		{
			
			$where = 'WHERE bc_id_mmo = '.$data['customer_id'].'';

		}elseif (isset($data['store']) && !empty($data['store']) && $data['store'] == 'mmt') {

			$where = 'WHERE bc_id_mmt = '.$data['customer_id'].'';

		}

		$query = $this->db->query("SELECT * FROM ".$this->customer_table." ".$where."");
		return $query->row_array();

	}

	public function logintommt($data)
	{

		$checkcustomerexitornot = $this->checkcustomerexistormot($data);
		$res = array();
		if(isset($checkcustomerexitornot['bc_id_mmt']) && !empty($checkcustomerexitornot['bc_id_mmt']))
		{
			$config_data        = $this->getBcConfig();
			$bcstoreurl		    = $config_data['storeurltrades'];
			$client_id		    = $config_data['client_idtrades'];
			$store_hash		    = $config_data['storehastrades'];
			$auth_token		    = $config_data['apitokentrades'];
			$client_secret		= $config_data['client_secrettrades'];

		
			Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash, 'client_secret' => $client_secret)); // Bc class connection				
			Bigcommerce::verifyPeer(false); 
			Bigcommerce::failOnError(); 

				$customer = $checkcustomerexitornot['bc_id_mmt'];
				$mmttoken = Bigcommerce::getCustomerLoginToken($customer);
				$url = $config_data['mmt_url'].'/login/token/'.$mmttoken;
				$res['suc'] = $url;
		}else{
			$res['error'] = 'customer id not found.';
		}

		return $res;
	}

	public function logintommo($data)
	{

		$checkcustomerexitornot = $this->checkcustomerexistormot($data);
		$res = array();
		if(isset($checkcustomerexitornot['bc_id_mmo']) && !empty($checkcustomerexitornot['bc_id_mmo']))
		{
			$config_data        = $this->getBcConfig();
			$bcstoreurl		    = $config_data['storeurl'];
			$client_id		    = $config_data['client_id'];
			$store_hash		    = $config_data['storehas'];
			$auth_token		    = $config_data['apitoken'];
			$client_secret		= $config_data['client_secret'];

		
			Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash, 'client_secret' => $client_secret)); // Bc class connection				
			Bigcommerce::verifyPeer(false); 
			Bigcommerce::failOnError(); 

				$customer = $checkcustomerexitornot['bc_id_mmo'];
				$mmttoken = Bigcommerce::getCustomerLoginToken($customer);
				$config_data = $this->getBcConfig();

	
				$url = $config_data['mmo_url'].'/login/token/'.$mmttoken;
				$res['suc'] = $url;
		}else{
			$res['error'] = 'customer id not found.';
		}

		return $res;
	}

}
	
?>
