<?php
use Bigcommerce\Api\Client as Bigcommerce;
class Customermodel extends CI_Model
{
	public function __construct()
	{
		$this->setting_table 	      = "setting";
		$this->category_table	      = "category";	
		$this->product_table_bc	      = "bc_product_data_store";
		$this->product_category_table = "product_category";
		$this->products               = "products";
		$this->brands               = "brands";
        $this->load->database();
        include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function getBcConfig()
	{
		$query = $this->db->query("SELECT * FROM ".$this->setting_table."");
		return $query->row_array();
	}

	public function InsertBcProduct($product_datas,$page,$api_limit)
	{
		$product_datas_a = $product_datas;

		$project_names = array_map(function($product_datas_a) { return $product_datas_a->id ;}, $product_datas_a );

		$data_bc =  array(
			"data_details" => base64_encode(serialize($project_names)),
			"page" =>  $page,
			"limit" => $api_limit,
		);

		$this->db->insert($this->product_table_bc,$data_bc);
		//return $page;
    }	

    public function importBarndToDb($brand_insert)
    {
    	$this->db->insert_batch($this->brands,$brand_insert);
    }

    public function GetBcproductData($pagenumber)
	{
		$query_product_bc_data = $this->db->query("SELECT * FROM ".$this->product_table_bc." WHERE page = '".$pagenumber."'");
		$bc_product_data = $query_product_bc_data->row_array();
		return $bc_product_data;
	}

	public function getbunchinserteddata()
	{
		$query_product_bc_data = $this->db->query("SELECT page,data_details FROM ".$this->product_table_bc."");
		$bc_product_data = $query_product_bc_data->result_array();
		if(isset($bc_product_data) &&  !empty($bc_product_data))
		{
			//foreach($bc_product_data as  $value) {
				$res = $this->InsertProductMulti($bc_product_data);	
				
				exit;
				if(isset($res) && !empty($res))
				{
					return '2';
				}
			//}
		}
	}

	public function InsertProductMulti($bc_product_data)
	{

		foreach($bc_product_data as  $value) 
		{

			$product_data_multi = unserialize(base64_decode($value['data_details']));
			
			$config_data = $this->getBcConfig();
			
			$bcstoreurl		= $config_data['storeurl'];
			$client_id		= $config_data['client_id'];
			$store_hash		= $config_data['storehas'];
			$auth_token		= $config_data['apitoken'];
		
			Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); // Bc class connection				
			Bigcommerce::verifyPeer(false); 
			Bigcommerce::failOnError(); 


			if(isset($product_data_multi) && !empty($product_data_multi) && count($product_data_multi) > 0)
			{
				$data_update = array();
				$data_insert = array();
				$data_option = array();
				$data_custom_fields = array();
				$data_category = array();
				$delete_product_ids = array();
				$data_sort_tabel = array();
				$data_sort_tabel_def_cat = array();
				$data_tmp_file = array();
				$optiondata = array();
				
				
				foreach($product_data_multi as $product_data_s)
				{
					
					//$cat = $category;
					$check_product_exist = '';$stock ='';$product_image = '';$product_price = '';$brand_data = '';$brand_id	= 0;$brand_name = '';$product_name = '';
					
					$product_data = Bigcommerce::getProduct($product_data_s);
					
					$createdate = date('Y-m-d H:i:s');

					$bc_product_status = 'inactive';
					if(isset($product_data->is_visible) && !empty($product_data->is_visible) && $product_data->is_visible == 1){
						$bc_product_status = 'active';
					}
					$stock 			  = $product_data->inventory_level;
					
				
					if(isset($product_data->name) && !empty($product_data->name)){ $product_name = $product_data->name;}

					if(isset($product_data->primary_image->thumbnail_url) && !empty($product_data->primary_image->thumbnail_url)){ $product_image = $product_data->primary_image->thumbnail_url;}

					if(isset($product_data->price) && !empty($product_data->price)){ $product_price = $product_data->price;}

					if(isset($product_data->brand_id) && !empty($product_data->brand_id)){ $brand_id = $product_data->brand_id;}

					if(isset($product_data->brand_id) && !empty($product_data->brand_id)){ $brand_id = $product_data->brand_id;}
					
					$check_product_exist = $this->GetProductID($product_data_s);
					
					if(isset($check_product_exist) && !empty($check_product_exist)){
						
						
						$data_update[] =  array(
							"bc_product_id" 		=> $product_data->id,
							"product_sku" 			=> $product_data->sku,
							"product_title" 		=> $this->db->escape_str($product_name),
							
							"image" 				=> $product_image,
							"price"					=> $product_price,
							"brand_id" 				=> $brand_id,
							"brand_name" 			=> $brand_name,
							"stock" 				=> $stock,
							"bc_product_status" 	=> $bc_product_status,
							"product_url"           => $product_data->custom_url,
							"create_date"			=> $createdate
						 );
					}else{
					
						$data_insert[] =  array(
							"bc_product_id" 		=> $product_data->id,
							"product_sku" 			=> $product_data->sku,
							"product_title" 		=> $this->db->escape_str($product_name),
							
							"image" 				=> $product_image,
							"price" 				=> $product_price,
							"brand_id" 				=> $brand_id,
							"brand_name" 			=> $brand_name,
							"stock" 				=> $stock,
							"bc_product_status" 	=> $bc_product_status,
							"product_url"           => $product_data->custom_url,
							"create_date" 			=> $createdate
						);
					}

					if(isset($product_data->categories) && !empty($product_data->categories))
					{
						foreach ($product_data->categories as $value) {
							
							$data_category[] =  array(
								"product_id" =>   $product_data->id,
								"category_id" =>  $value,

							);
						}
					}
					
					//print_r(unserialize(base64_decode($data_update[0]['product_data'])));
					
				}

				if(isset($data_category) && !empty($data_category))	{
					$this->db->insert_batch($this->product_category_table,$data_category);
				}

				if(isset($data_insert) && !empty($data_insert))	{
					$this->db->insert_batch($this->products,$data_insert);
				}

				if(isset($data_update) && !empty($data_update)){
					$this->db->update_batch($this->products,$data_update,'bc_product_id'); 
				}
			}
		//return $page_call;

		}
		exit;
		return '1';
	}

	public function GetProductID($bc_product_id){

		$query_product = $this->db->query("SELECT bc_product_id FROM ".$this->products." WHERE bc_product_id = '".$bc_product_id."'");
		return $query_product->num_rows();
	}

	public function emptytable()
	{
		$query_product = $this->db->query("TRUNCATE TABLE bc_product_data_store");
	}
}
?>