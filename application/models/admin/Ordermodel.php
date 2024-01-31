<?php
use Bigcommerce\Api\Client as Bigcommerce;
class Ordermodel extends CI_Model
{
	public function __construct()
	{
		$this->setting_table 	      = "setting";
		$this->category_table	      = "category";	
		$this->order_table_bc	      = "bc_order_data_store";
		$this->product_category_table = "product_category";
		$this->products               = "products";
		$this->brands                 = "brands";
		$this->product_option_table   = "product_option";
		$this->orders                 = "orders";
        $this->load->database();
        include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function getBcConfig()
	{
		$query = $this->db->query("SELECT * FROM ".$this->setting_table."");
		return $query->row_array();
	}

	public function InsertBcOrders($Order_datas,$page,$api_limit)
	{
		$Order_datas_a = $Order_datas;

		$project_names = array_map(function($Order_datas_a) { return $Order_datas_a->id ;}, $Order_datas_a );
	
		$data_bc =  array(
			"data_details" => base64_encode(serialize($project_names)),
			"page" =>  $page,
			"limit" => $api_limit,
		);

		$this->db->insert($this->order_table_bc,$data_bc);
		//return $page;
    }	

	public function getbunchinserteddata()
	{
		$query_order_bc_data = $this->db->query("SELECT page,data_details FROM ".$this->order_table_bc."");
		$bc_order_data = $query_order_bc_data->result_array();

		if(isset($bc_order_data) &&  !empty($bc_order_data))
		{
				$res = $this->InsertProductMulti($bc_order_data);	
				
		}
	}

	public function InsertProductMulti($bc_product_data)
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		set_time_limit(-1);

		foreach($bc_product_data as  $value) 
		{
			
			$order_data_multi = unserialize(base64_decode($value['data_details']));
			
			$config_data = $this->getBcConfig();
			
			$bcstoreurl		= $config_data['storeurltrades'];
			$client_id		= $config_data['client_idtrades'];
			$store_hash		= $config_data['storehastrades'];
			$auth_token		= $config_data['apitokentrades'];
		
			Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash ));			
			Bigcommerce::verifyPeer(false); 
			Bigcommerce::failOnError(); 

			$orderdata = array();
			if(isset($order_data_multi) && !empty($order_data_multi) && count($order_data_multi) > 0)
			{
				$i = 0;
				foreach($order_data_multi as $order_data_s)
				{
					$checkOrderExistOrNot =  $this->checkOrderExistOrNot($order_data_s);
					if($checkOrderExistOrNot == 0){ 
						$orderdetils  = Bigcommerce::getOrder($order_data_s);
						$orderproduct  = Bigcommerce::getOrderProducts($order_data_s);

						$orderdata[$i]['orderId']              = $orderdetils->id;
						$orderdata[$i]['customer_id']          = $orderdetils->customer_id;
						$orderdata[$i]['items_total']          = $orderdetils->items_total;
						$orderdata[$i]['total_amount']         = $orderdetils->total_inc_tax;
						$orderdata[$i]['status']               = $orderdetils->status;
						$orderdata[$i]['date_created']         = date('M dS Y',strtotime($orderdetils->date_created));
						$orderdata[$i]['date_modified']        = date('M dS Y',strtotime($orderdetils->date_modified));
						$orderdata[$i]['is_deleted']           = $orderdetils->is_deleted;
						$orderdata[$i]['product_thumbnail_url'] = '';
						$orderdata[$i]['error'] =   '';
						try {

						    if(isset($orderproduct{0}->product_id) && !empty($orderproduct{0}->product_id))
							{
								$getProductImages  = Bigcommerce::getProductImages($orderproduct{0}->product_id);
								if(isset($getProductImages{0}->thumbnail_url) && !empty($getProductImages{0}->thumbnail_url))
								{
									$orderdata[$i]['product_thumbnail_url']        =  $this->db->escape_str($getProductImages{0}->thumbnail_url);
								}
							}
						} catch (Exception $e) {
						    echo 'Caught exception: ',  $e->getMessage(), "\n";
						    $orderdata[$i]['error'] = $e->getMessage();

						}
					}else{

						$orderdetils  = Bigcommerce::getOrder($order_data_s);
						$orderproduct  = Bigcommerce::getOrderProducts($order_data_s);

						$orderdataupdate[$i]['orderId']              = $orderdetils->id;
						$orderdataupdate[$i]['customer_id']          = $orderdetils->customer_id;
						$orderdataupdate[$i]['items_total']          = $orderdetils->items_total;
						$orderdataupdate[$i]['total_amount']         = $orderdetils->total_inc_tax;
						$orderdataupdate[$i]['status']               = $orderdetils->status;
						$orderdataupdate[$i]['date_created']         = date('M dS Y',strtotime($orderdetils->date_created));
						$orderdataupdate[$i]['date_modified']        = date('M dS Y',strtotime($orderdetils->date_modified));
						$orderdataupdate[$i]['is_deleted']           = $orderdetils->is_deleted;
						$orderdataupdate[$i]['product_thumbnail_url'] = '';
						$orderdataupdate[$i]['error'] =   '';
						try {

						    if(isset($orderproduct{0}->product_id) && !empty($orderproduct{0}->product_id))
							{
								$getProductImages  = Bigcommerce::getProductImages($orderproduct{0}->product_id);
								$tt = $this->searchForId(1,$getProductImages);

								if(isset($tt->thumbnail_url) && !empty($tt->thumbnail_url))
								{
									$orderdataupdate[$i]['product_thumbnail_url']        =  $this->db->escape_str($tt->thumbnail_url);
								}
							}
						} catch (Exception $e) {
						    echo 'Caught exception: ',  $e->getMessage(), "\n";
						    $orderdataupdate[$i]['error'] = $e->getMessage();

						}
					}
					$i++;
				}

			}
			
			if(isset($orderdata) && !empty($orderdata))	{
				$this->db->insert_batch($this->orders,$orderdata);
			}

			if(isset($orderdataupdate) && !empty($orderdataupdate)){
				$this->db->update_batch($this->orders,$orderdataupdate,'orderId'); 
			}

		}
	}


	public function emptytable()
	{
		$query_product = $this->db->query("TRUNCATE TABLE bc_order_data_store");
	}


    public function checkOrderExistOrNot($order_data_s)
	{
		$query_product_bc_data = $this->db->query("SELECT * FROM ".$this->orders." WHERE orderId = '".$order_data_s."'");
		$bc_product_data = $query_product_bc_data->num_rows();
		return $bc_product_data;
	}

	public function demoorder(){

		$config_data = $this->getBcConfig();
			
		$bcstoreurl		= $config_data['storeurltrades'];
		$client_id		= $config_data['client_idtrades'];
		$store_hash		= $config_data['storehastrades'];
		$auth_token		= $config_data['apitokentrades'];
	
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash ));			
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError(); 

		$order_id = '318';
		$orderdetils  = Bigcommerce::getOrder($order_id);
		$orderproduct  = Bigcommerce::getOrderProducts($order_id);

		$array =array();
		$array['is_thumbnail'] = 1; 
		$getProductImages  = Bigcommerce::getProductImages(2820);
		$tt = $this->searchForId(1,$getProductImages);


		echo "<pre>";
		print_r($tt->thumbnail_url);
		exit;
		
		//print_r($orderdetils);
		//print_r($orderproduct);
		exit;
	}

	function searchForId($id, $array) {
	   foreach ($array as $key => $val) {
	   
	       if ($val->is_thumbnail == $id) {

	           return $val;
	       }
	   }
	   return null;
	}
}
?>