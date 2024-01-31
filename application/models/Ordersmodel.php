<?php

use Bigcommerce\Api\Client as Bigcommerce;
class Ordersmodel extends CI_Model
{
	public function __construct()
	{
		$this->setting_table 	      = "setting";
		$this->category_table	      = "category";	
		$this->product_table_bc	      = "bc_product_data_store";
		$this->product_category_table = "product_category";
		$this->products               = "products";
		$this->brands                 = "brands";
		$this->quote_table            = "quote";
		$this->product_table          = "products";
		$this->product_option_table   = "product_option";
		$this->shipping_label_table   = "shipping_label";
		$this->customers_log_table    = "customers_log";
		$this->orders_table           = "orders";
		$this->address_log_table      = "address_log";

        $this->load->database();
        include(APPPATH.'third_party/bcapi/vendor/autoload.php');
        header('Access-Control-Allow-Origin: *');
        

       
	}

	public function accountsetting($data){
		$d = array();

		if(isset($data['customer_id']) && !empty($data['customer_id']))
		{
			$start = date('Y-m-d H:i:s');
	   		$beforetwominites =  date('Y-m-d H:i:s',strtotime('-2 minutes',strtotime($start)));
	   		$where = '';
			if($data['storename'] == 'mmo'){

				$where =  'WHERE `bc_id_mmo` = "'.$data['customer_id'].'" AND `last_update_date` >= "'.$beforetwominites.'"';
			}
			if($data['storename'] == 'mmt'){

				$where =  'WHERE `bc_id_mmt` = "'.$data['customer_id'].'" AND `last_update_date` >= "'.$beforetwominites.'"';
			}

			$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." ".$where."");
			$res = $query->row_array();
		
			$ids = strtotime($res['last_update_date']);
			
			if($data['storename'] == 'mmo'){
				$idd = $res['bc_id_mmo'];
			}
			if($data['storename'] == 'mmt'){
				$idd = $res['bc_id_mmt'];
			}
			if(isset($ids) && !empty($ids))
			{
				$to_time = strtotime($start);
				$from_time = $ids;
				$minutecount = round(abs($to_time - $from_time) / 60,2); 

				if($minutecount < 2)
				{
					$d['res'] = 2 - $minutecount;
					$d['idd'] = $idd;
				}
			}
			
		}

		return $d;

	}

	public function addresses($data,$storename){
		$res = '';
		$idd = array();
		$d = array();
		$start = date('Y-m-d H:i:s');
		
   		$beforetwominites =  date('Y-m-d H:i:s',strtotime('-2 minutes',strtotime($start)));

		$where = '';
		if($storename == 'mmo'){

			$where =  'WHERE `mmo_address_id` IN ('.implode(",", $data).') AND `last_update_date` >= "'.$beforetwominites.'"';
		}
		if($storename == 'mmt'){

				$where =  'WHERE `mmt_address_id` IN ('.implode(",", $data).') AND `last_update_date` >= "'.$beforetwominites.'"';
		}

		if(isset($data) && !empty($data)){
		
			$query = $this->db->query("SELECT * FROM ".$this->address_log_table." ".$where."");
			$res = $query->result_array();
			$ids = array_map(function ($res) {return strtotime($res['last_update_date']);}, $res);
			if($storename == 'mmo'){
				$idd = array_map(function ($res) {return $res['mmo_address_id'];}, $res);
			}
			if($storename == 'mmt'){
				$idd = array_map(function ($res) {return $res['mmt_address_id'];}, $res);
			}
			if(isset($ids) && !empty($ids))
			{
				$to_time = strtotime($start);
				$from_time = min($ids);
				$minutecount = round(abs($to_time - $from_time) / 60,2).""; 
				if($minutecount < 2)
				{
					$d['res'] = 2 - $minutecount;
					$d['idd'] = $idd;
				}
			}
			
		}
		return $d;
	}

	public function getBcConfig()
	{
		$query = $this->db->query("SELECT * FROM ".$this->setting_table."");
		return $query->row_array();
	}

	public function getcustomeridstore($data)
	{
		
		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE bc_id_mmo = '".$data['customer_id']."' ");
		return $query->row_array();
	}

	public function getorder($post)
	{
		$res = $this->getcustomeridstore($post);

		$pagenumber = 1;
		$offset = 0;
		$page = $post['page'];
		if(isset($page) && !empty($page))
		{
			$pagenumber = $page;   
		}

		$offset = ($pagenumber-1)*$post['limit'];

		
		if(isset($res['bc_id_mmt']) && !empty($res['bc_id_mmt']))
		{
			
			$query = $this->db->query("SELECT * FROM ".$this->orders_table." WHERE 	customer_id = '".$res['bc_id_mmt']."' AND is_deleted = 0 ORDER BY orderId DESC limit ".$offset." ,".$post['limit']." ");
			$result['orderdetails'] = $query->result_array();

			$query = $this->db->query("SELECT * FROM ".$this->orders_table." WHERE 	customer_id = '".$res['bc_id_mmt']."' AND is_deleted = 0 ");
			$result['ordertotal'] = $query->num_rows();

			return $result;
		}
	}

	public function viewOrderDeatils($data){
		$query = $this->db->query("SELECT * FROM ".$this->orders_table." WHERE 	orderId = '".$data['orderId']."'");
		$data = $query->row_array();

		$okenul = $this->logintommt($data);
		return $okenul;
	}

	public function logintommt($data)
	{

		$res = array();
		if(isset($data['customer_id']) && !empty($data['customer_id']))
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
			$redirecUrl = '/account.php?action=view_order&order_id='.$data['orderId'];
			
				$customer = $data['customer_id'];
				$mmttoken = Bigcommerce::getCustomerLoginToken($customer,$redirecUrl);
				$url = $config_data['mmt_url'].'/login/token/'.$mmttoken;
				$res['suc'] = $url;
		}else{
			$res['error'] = 'customer id not found.';
		}

		return $res;
	}

	
}
?>