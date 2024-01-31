<?php 
ini_set('display_errors','On');
error_reporting(E_ALL);
class Managewebhookmodel extends CI_Model
{
	function __construct(){
		$this->app_token_table  	 = "app_token";
		$this->created_hook_table  	 = "created_hook";
		$this->user_table  			 = "users";
		$this->setting  			 = "setting";
	}
	
	public function GetWebHooks()
	{
		$query_product = $this->db->query("SELECT * FROM ".$this->created_hook_table."");
		return  $query_product->result_array();
	}
	
	public function GetAPPdetails()
	{
		$query_product = $this->db->query("SELECT * FROM ".$this->setting."");
		return $query_product->row_array();
	}
		
	public function single_webhook($id)
	{
		$query_product = $this->db->query("SELECT * FROM ".$this->created_hook_table." WHERE id = '".$id."'");
		return $query_product->row_array();
	}
	
	public function GetLiveWebhook()
	{
	
		$select_app_token 		  = $this->db->query("SELECT * FROM ".$this->setting."");
		$token_details    		  =  $select_app_token->row_array();
	
		$store_url = 'https://api.bigcommerce.com/';
		$store_hash = 'stores/'.$token_details['storehas'];
		$product_hook_create_url = $store_url.$store_hash.'/v2/hooks';
		
		$api_url = $product_hook_create_url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url ); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','X-Auth-Client: '.$token_details['client_id'].'','X-Auth-Token: '.$token_details['apitoken']) );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false ); 
		$response = curl_exec ($ch);
		$product_hook_res = json_decode($response); 
		
		return $product_hook_res;
	}
	
	public function insert_record()
	{
	
		$select_app_token 		  = $this->db->query("SELECT * FROM ".$this->setting."");
		$token_details    		  =  $select_app_token->row_array();
		
	
		$product_hook 				 = array();
		$product_hook['scope'] 		 = $this->input->post("scope");
		$product_hook['is_active']   = true;
		$product_hook['destination'] = $this->input->post("destination");
		
		$store_url = 'https://api.bigcommerce.com/';
		$store_hash = 'stores/'.$token_details['storehas'];
		$product_hook_create_url = $store_url.$store_hash.'/v2/hooks';
		
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $product_hook_create_url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json','X-Auth-Client: '.$token_details['client_id'].'','X-Auth-Token: '.$token_details['apitoken']) );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($product_hook));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		$response = curl_exec ($curl);
		
		$product_hook_res = json_decode($response); 
		echo "<pre>";
		print_r($product_hook_res);
		exit;
		
		$type = 'hook';
		
		$res = array();
		
		if(isset($product_hook_res->id) && !empty($product_hook_res->id))
		{
			$data_insert =  array(
				"hook_id" 		=> $product_hook_res->id,
				"scope" 	    => $product_hook_res->scope,
				"destination"   => $product_hook_res->destination,
				"type"   		=> $type
			);
			$this->db->insert($this->created_hook_table,$data_insert);
			$hook_id = $this->db->insert_id();
			
			$res['status'] = 'Success';
			$res['Message'] = $hook_id;
			return $res;
		}
		else if(isset($product_hook_res->error) && !empty($product_hook_res->error))
		{
			$res['status']   = 'Error';
			$res['Message']  = json_encode($product_hook_res->error);
			return $res;
		}
	}
	
	
	public function update_record($webhook_id)
	{
		$select_app_token 		  = $this->db->query("SELECT * FROM ".$this->setting."");
		$token_details    		  =  $select_app_token->row_array();
		
		$product_hook 				 = array();
		$product_hook['is_active']   = true;
		$product_hook['destination'] = $this->input->post("destination");
		
		$store_url = 'https://api.bigcommerce.com/';
		$store_hash = 'stores/'.$token_details['storehas'];
		$product_hook_create_url = $store_url.$store_hash.'/v2/hooks/'.$webhook_id;
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $product_hook_create_url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/json','X-Auth-Client: '.$token_details['client_id'].'','X-Auth-Token: '.$token_details['apitoken']) );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($product_hook));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec ($curl);
		$product_hook_res = json_decode($response); 
		
		$type = 'hook';
		
		$res = array();
		
		if(isset($product_hook_res->id) && !empty($product_hook_res->id))
		{
			$data_insert =  array(
				"hook_id" 		=> $product_hook_res->id,
				"scope" 	    => $product_hook_res->scope,
				"destination"   => $product_hook_res->destination,
				"type"   		=> $type
			);
			$this->db->where('hook_id',$webhook_id);
			$this->db->update($this->created_hook_table,$data_insert);
						
			$res['status'] = 'Success';
			$res['Message'] = $webhook_id;
			
			return $res;
		}
		else if(isset($product_hook_res->error) && !empty($product_hook_res->error))
		{
			$res['status']   = 'Error';
			$res['Message']  = json_encode($product_hook_res->error);
			return $res;
		}
		
	}
	
	public function delete_record($hook_id)
	{
		$select_app_token 		  = $this->db->query("SELECT * FROM ".$this->setting."");
		$token_details    		  =  $select_app_token->row_array();
	
		$store_url  = 'https://api.bigcommerce.com/';
		$store_hash = 'stores/'.$token_details['storehas'];
		$product_hook_create_url = $store_url.$store_hash.'/v2/hooks/'.$hook_id;
		
		$api_url = $product_hook_create_url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url ); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','X-Auth-Client: '.$token_details['client_id'].'','X-Auth-Token: '.$token_details['apitoken']) );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false ); 
		$response = curl_exec ($ch);
		$product_hook_res = json_decode($response); 
 

		if(isset($product_hook_res->id) && !empty($product_hook_res->id))
		{
			$this->db->delete($this->created_hook_table,array('hook_id'=>$hook_id));
						
			$res['status'] = 'Success';
			$res['Message'] = $hook_id;
			return $res;
		}
		else if(isset($product_hook_res->error) && !empty($product_hook_res->error))
		{
			$res['status']   = 'Error';
			$res['Message']  = json_encode($product_hook_res->error);
			return $res;
		}
		
	}
	
}
?>