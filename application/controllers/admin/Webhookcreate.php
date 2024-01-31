<?php 
ini_set('display_errors','On');
error_reporting(E_ALL);
use Bigcommerce\Api\Connection;
use Bigcommerce\Api\Client as Bigcommerce;
class Webhookcreate extends CI_controller
{
	function __construct()
	{
		parent::__construct();	
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');
		$this->load->model('admin/webhookcreatemodel');
		include(APPPATH.'/third_party/bcapi/vendor/autoload.php');
		
		$this->created_hook = "created_hook";
	}
	
	
	public function index()
	{
		$base_url = base_url();
		$webhookdetails = $this->webhookcreatemodel->GetwebhookDetails();
		 
		// Create APP
		$tokenUrl = "https://login.bigcommerce.com/oauth2/token";
		$data = array(
			"client_id" => $webhookdetails['client_id'],
			"client_secret" => $webhookdetails['client_secret'],
			"redirect_uri" => $base_url."index.php/admin/webhookcreate",
			"grant_type" => "authorization_code",
			"code" => $_GET["code"],
			"scope" => $_GET["scope"],
			"context" => $_GET["context"],
		);
		
		$postfields = http_build_query($data);
		
		$ch = curl_init();                    
		$url = "https://login.bigcommerce.com/oauth2/token";
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec ($ch);
		curl_close ($ch);
		
		$obj = json_decode($output);
		
		$access_token = $obj->{'access_token'};
		$scope 		  = $obj->{'scope'};
		$id			  = $obj->{'user'}->{'id'};
		$email 		  = $obj->{'user'}->{'email'};
		$context 	  = $obj->{'context'};
		
		$insert_array				  = array();
		$insert_array['access_token'] = $access_token;
		$insert_array['scope'] 		  = $scope;
		$insert_array['context']      = $context;
		$insert_array['user_id']      = $id;
		$insert_array['user_email']   = $email;
		
		$this->webhookcreatemodel->InsertToken($insert_array);
	}
	
	public function loadapp()
	{
		echo '<h2>Welcome To Product Synchronize APP</h2><br>';
		echo 'Live Update Products and Categories<br>';
	}	
	
	public function deleteproduct()
	{
		$this->webhookcreatemodel->RemoveDeteletProducts();
	}
	
	public function producthook()
	{	
		$webhookContent = '';
		$webhook = fopen('php://input' , 'rb');
		while (!feof($webhook)) {
		$webhookContent.= fread($webhook, 4096);
		}
		fclose($webhook);
		$webhookContentobj = json_decode($webhookContent);
		
		//file_put_contents(APPPATH.'third_party/hook/Product_product_2.txt',print_r($webhookContentobj,TRUE));

		if(isset($webhookContentobj->scope) && !empty($webhookContentobj->scope))
		{
			$this->webhookcreatemodel->WebhookCallProduct($webhookContentobj->data->id,$webhookContentobj->scope);
		}
	}
	
	public function categoryhook()
	{	
		$webhookContent = '';
		$webhook = fopen('php://input' , 'rb');
		while (!feof($webhook)) {
		$webhookContent.= fread($webhook, 4096);
		}
		fclose($webhook);
		$webhookContentobj = json_decode($webhookContent);
		
		//file_put_contents(APPPATH.'third_party/hook/category_log.txt',print_r($webhookContentobj,TRUE));
		
		if(isset($webhookContentobj->scope) && !empty($webhookContentobj->scope) && $webhookContentobj->scope!='store/category/deleted')
		{
			$this->webhookcreatemodel->WebhookCallCategory($webhookContentobj->data->id,$webhookContentobj->scope);
		}else{
			$this->webhookcreatemodel->WebhookCallRemoveCategory($webhookContentobj->data->id);
		}
	}
	
	public function democategory()
	{
		$this->webhookcreatemodel->WebhookCallCategory('112','store/category/updated');
		
	}
	public function uninstall()
	{
		$this->webhookcreatemodel->EmptyAppTabel();
	}

	public function demoproduct()
	{
		$webhookres_id = '648';
		$webhookres_scope = 'store/product/update';
		$config_data = $this->webhookcreatemodel->getBcConfig();
		$config_datad = $this->webhookcreatemodel->WebhookCallProduct($webhookres_id,$webhookres_scope);

		
		$bcstoreurl		= $config_data['storeurl'];
		$client_id		= $config_data['client_id'];
		$store_hash		= $config_data['storehas'];
		$auth_token		= $config_data['apitoken'];
		
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); // Bc class connection				
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError(); 

		$product_data = Bigcommerce::getProduct('648');

	}

	public function updateAllwebhook()
	{
		$config_data = $this->webhookcreatemodel->getBcConfig();
		$data = $this->db->query("SELECT * FROM `".$this->created_hook."` ");
		$hookdata = $data->result_array();
		
		foreach ($hookdata as $hookvalue) {
		
			$curl = curl_init();
			$apipath = $config_data['apipath'];
			$apitoken = $config_data['apitoken'];

			curl_setopt_array($curl, array(
			CURLOPT_URL => $apipath."hooks/".$hookvalue['hook_id'],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST=> 0,
			CURLOPT_SSL_VERIFYPEER=> 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "PUT",
			CURLOPT_POSTFIELDS => "{\"is_active\":true}",
			CURLOPT_HTTPHEADER => array(
			"accept: application/json",
			"content-type: application/json",
			"x-auth-token: ".$apitoken
			),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				echo $response;
			}
		}
	}
	
}