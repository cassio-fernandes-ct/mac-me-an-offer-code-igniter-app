<?php 
use Bigcommerce\Api\Client as Bigcommerce;
class Customergroup extends CI_controller{
	
	function __construct()
	{
		parent::__construct();	
		ini_set('memory_limit', '-1');
		
		ini_set('display_errors','on');
		error_reporting(E_ALL);
		
		$this->load->library('bigcommerceapi');
		$this->load->model("admin/customergroupmodel");
		
		include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function getBcConfig(){
		$query = $this->db->get_where($this->setting_table,array('id'=>1));
		return $query->row_array();
	}
	
	public function customer_group(){

		/* macofalltrades */
		$client_id 		= '46s39g803b8wd2rexb6oecgo0bio898';
		$auth_token 	= '9dbhxept77e8besu4i1x1vp55w06ifk';
		$store_hash 	= 'xt5en0q8kf';

		$currentdate = date('Y-m-d H:i:s');
		// Bc class connection
		Bigcommerce::configure(array('client_id' => $client_id,'auth_token' => $auth_token,'store_hash' => $store_hash));	
		// SSL verify False
		Bigcommerce::verifyPeer(false);
 		// Display error exception on
		Bigcommerce::failOnError();	
		
		$customergroup  = Bigcommerce::getCustomerGroups();
		//$this->customergroupmodel->updatecustomerMessage($id,$error1);
	  
	    $customergroup_array = array();
		if(isset($customergroup) && !empty($customergroup)){

			foreach ($customergroup as $value) {
				$customergroup_array[] = array('group_id' => $value->id,
												'group_name' => $value->name );
			}
		}


		try{
			$table = 'customer_groups';
			$first = reset($customergroup_array);
			
			$columns = implode( ',',
					array_map( function( $value ) { return "$value"; } , array_keys($first) )
			);

	    
			$values = implode( ',', array_map( function( $customergroup_array ) {
					return '('.implode( ',',
							array_map( function( $value ) { return '"'.str_replace('"', '""', $value).'"'; } , $customergroup_array )
					).')';
				} , $customergroup_array )
			);

			$updates = implode( ',',
				array_map( function( $value ) { return "$value = VALUES($value)"; } , array_keys($first) )
			);
		
		
			$sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";
			$this->db->query($sql);
			
		}catch(\Exception $e){
			return $e->getMessage();   // insert query
		}
	}

	public function updatemmocustomergroup(){
		$sql = "SELECT * FROM `customer_groups`";
		$query = $this->db->query($sql);
		$q = $query->result_array();
		
		if(isset($q) && !empty($q))
		{	
			set_time_limit(-1); 

			foreach ($q as $value) {
				
					$customergroup = $this->getcustomergroupmmt($value);
					
				
			}
			
		}
	}

	public function getcustomergroupmmt($value)
	{
		$client_id 		= '46s39g803b8wd2rexb6oecgo0bio898';
		$auth_token 	= '9dbhxept77e8besu4i1x1vp55w06ifk';
		$store_hash 	= 'xt5en0q8kf';

		$currentdate = date('Y-m-d H:i:s');
		// Bc class connection
		Bigcommerce::configure(array('client_id' => $client_id,'auth_token' => $auth_token,'store_hash' => $store_hash));	
		// SSL verify False
		Bigcommerce::verifyPeer(false);
 		// Display error exception on
		Bigcommerce::failOnError();	
		if(isset($value['group_id']) && !empty($value['group_id']))
		{
			$customergroup  = Bigcommerce::getCustomerGroupone($value['group_id']);
			
			$this->customer_group_mmo($customergroup,$value);
		}
	}

	public function customer_group_mmo($customergroup,$value){

		// 2023-05-30 :: WebFX :: troubleshooting where these requests are coming from
		$data = [
			'datetime' => date( 'Y-m-d H:i:s' ),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'file' => __FILE__,
			'line' => __LINE__,
			'server' => $_SERVER,
			'request' => $_REQUEST,
			'get' => $_GET,
			'post' => $_POST,
		];
		$data = PHP_EOL . json_encode( $data, JSON_PRETTY_PRINT ) . PHP_EOL;

		file_put_contents( APPPATH . '/logs/newbc.log', $data, FILE_APPEND );

		return;

 		$customergroup_array = array();
		
		$client_id 	= '6pnulio9e8icu2uow0jjzynrm5tta38';
		$auth_token 	= '5oaffn9ssm25999xyfqobadt49zegc3';
		$store_hash 	= 'ilhtqzrn07';

		$currentdate = date('Y-m-d H:i:s');
		// Bc class connection
		Bigcommerce::configure(array('client_id' => $client_id,'auth_token' => $auth_token,'store_hash' => $store_hash));	
		// SSL verify False
		Bigcommerce::verifyPeer(false);
 		// Display error exception on
		Bigcommerce::failOnError();	


		if(isset($value['bc_group_id']) && !empty($value['bc_group_id']))
		{
				$name['name'] = $customergroup->name;
				$createcustomergroup  = Bigcommerce::updateCustomerGroup($customergroup->id,$name);

		}else{
			$name['name'] = $customergroup->name;
			$createcustomergroup  = Bigcommerce::createcustomergroup($name);

			$sql = "UPDATE `customer_groups` SET `bc_group_id`= '".$createcustomergroup->id."' WHERE `group_id` = ".$customergroup->id."";
			$this->db->query($sql);
			
		}


	}

	/*public function customer_group_mmo(){
 		$customergroup_array = array();
		
		$client_id 	= '6pnulio9e8icu2uow0jjzynrm5tta38';
		$auth_token 	= '5oaffn9ssm25999xyfqobadt49zegc3';
		$store_hash 	= 'ilhtqzrn07';

		$currentdate = date('Y-m-d H:i:s');
		// Bc class connection
		Bigcommerce::configure(array('client_id' => $client_id,'auth_token' => $auth_token,'store_hash' => $store_hash));	
		// SSL verify False
		Bigcommerce::verifyPeer(false);
 		// Display error exception on
		Bigcommerce::failOnError();	

			
		$customergroup  = Bigcommerce::getCustomerGroups();
			
		
		$customergroup_array = array();
		if(isset($customergroup) && !empty($customergroup)){

			foreach ($customergroup as $value) {
				$customergroup_array[] = array('bc_group_id' => $value->id,
												'group_name' => $value->name);
			}
		}
		$this->db->update_batch('customer_groups',$customergroup_array, 'group_name'); 

	} */

	public function getCustomerGroup(){

		// 2023-05-30 :: WebFX :: troubleshooting where these requests are coming from
		$data = [
			'datetime' => date( 'Y-m-d H:i:s' ),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'file' => __FILE__,
			'line' => __LINE__,
			'server' => $_SERVER,
			'request' => $_REQUEST,
			'get' => $_GET,
			'post' => $_POST,
		];
		$data = PHP_EOL . json_encode( $data, JSON_PRETTY_PRINT ) . PHP_EOL;

		file_put_contents( APPPATH . '/logs/newbc.log', $data, FILE_APPEND );

		return;

		// todo -- figure out what's this for; endpoint returning 404		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.bigcommerce.com/stores/xt5en0q8kf/v2/customer_groups/8",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
		    "accept: application/json",
		    "content-type: application/json",
		    "x-auth-client: 46s39g803b8wd2rexb6oecgo0bio898",
		    "x-auth-token: 9dbhxept77e8besu4i1x1vp55w06ifk"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
			$customergroup = json_decode($response);
			
		 	if(isset($customergroup->id) && !empty($customergroup->id))
		 	{
		 		$client_id 	= '6pnulio9e8icu2uow0jjzynrm5tta38';
				$auth_token 	= '5oaffn9ssm25999xyfqobadt49zegc3';
				$store_hash 	= 'ilhtqzrn07';

				$currentdate = date('Y-m-d H:i:s');
				// Bc class connection
				Bigcommerce::configure(array('client_id' => $client_id,'auth_token' => $auth_token,'store_hash' => $store_hash));	
				// SSL verify False
				Bigcommerce::verifyPeer(false);
		 		// Display error exception on
				Bigcommerce::failOnError();	

				$name['name'] = $customergroup->name;
				$createcustomergroup  = Bigcommerce::createcustomergroup($name);

				echo "<pre>";
				print_r($createcustomergroup);
				exit;
		 	}
		}

	}

} 
