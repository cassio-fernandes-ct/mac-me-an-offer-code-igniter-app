<?php
use Bigcommerce\Api\Client as Bigcommerce;
class Productmodel extends CI_Model
{
	public function __construct()
	{
		$this->setting_table 	      = "setting";
		$this->category_table	      = "category";	
		$this->product_table_bc	      = "bc_product_data_store";
		$this->product_category_table = "product_category";
		$this->products               = "products";
		$this->brands                 = "brands";
		$this->product_option_table   = "product_option";
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
				$res = $this->InsertProductMulti($bc_product_data);	
				
		}
	}

	public function InsertProductMulti($bc_product_data)
	{
		//ini_set('display_errors', 1);
		//ini_set('display_startup_errors', 1);
		//error_reporting(E_ALL);

		set_time_limit(-1);

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
			$data_option = array();

			if(isset($product_data_multi) && !empty($product_data_multi) && count($product_data_multi) > 0)
			{
				
				foreach($product_data_multi as $product_data_s)
				{
					//$cat = $category;
					$sort_order = ''; 
					$check_product_exist = '';
					$stock ='';
					$product_image = '';
					$product_price = '';
					$brand_data = '';
					$brand_id	= 0;
					$brand_name = '';
					$product_name = '';
					$sku = '';
					
					try {
						
						$product_data = Bigcommerce::getProduct($product_data_s);

						if(isset($product_data) && empty($product_data)) {
							throw new Exception('Bigcommerce\Api\Error');
						} else {
							//TODO:  HBP code add on 30-6-2020 start
							if(isset($product_data->sort_order) && !empty($product_data->sort_order))
							{
								$sort_order = $product_data->sort_order;
							}
							//TODO:  HBP code add on 30-6-2020 end

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
							if(isset($product_data->sku) && !empty($product_data->sku)){ $sku = $product_data->sku;}
							
							$check_product_exist = $this->GetProductID($product_data_s);
							
							if(isset($check_product_exist) && !empty($check_product_exist)){
								
								$data_update[] =  array(
									"bc_product_id" 		=> $product_data->id,
									"product_sku" 			=> $sku,
									"product_title" 		=> $this->db->escape_str($product_name),
									"sort_order"            => $sort_order,
									"image" 				=> $product_image,
									"price"					=> $product_price,
									"brand_id" 				=> $brand_id,
									"brand_name" 			=> $brand_name,
									"stock" 				=> $stock,
									"bc_product_status" 	=> $bc_product_status,
									"product_url"           => $this->db->escape_str($product_data->custom_url),
									"create_date"			=> $createdate
								 );
							}else{
							
								$data_insert[] =  array(
									"bc_product_id" 		=> $product_data->id,
									"product_sku" 			=> $sku,
									"sort_order"            => $sort_order,
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
						}
					} catch(Exception $e) {
						$error = $e->getMessage();
						echo $error.'<br>';
					}

					try {
						$productoption = Bigcommerce::getProductoptions($product_data_s);
						if(isset($productoption) && empty($productoption)) {
							throw new Exception('Bigcommerce\Api\Error');
						} else {
							if(isset($productoption) && !empty($productoption))
							{
								foreach ($productoption as $po) {
								
									$getOptionvalue = Bigcommerce::getOptionCValue($po->option_id);

									if(isset($getOptionvalue) && !empty($getOptionvalue))
									{
										foreach ($getOptionvalue as $pov) {
											$data_option[] =  array(
														"product_id" => $product_data->id,
														"option_id" =>  $po->option_id,
														"attribut_id" =>  $po->id,
														"option_set_name" => $this->db->escape_str($po->display_name),
														"option_label" => $this->db->escape_str($pov->label),
														"option_label_value_id" =>  $this->db->escape_str($pov->id),
														"option_label_value" =>  $this->db->escape_str($pov->value)
											);
										}
									}else{

										$data_option[] =  array(
														"product_id" => $product_data->id,
														"option_id" =>  $po->option_id,
														"attribut_id" =>  $po->id,
														"option_set_name" => $this->db->escape_str($po->display_name),
														"option_label" => '',
														"option_label_value_id" =>  '',
														"option_label_value" =>  ''
											);
									}
								}
							}
						}
					} catch(Exception $e) {
						$error = $e->getMessage();
						echo $error.'<br>';
					}
					
					$this->db->delete($this->product_category_table,array('product_id'=>$product_data->id));
					$this->db->delete($this->product_option_table,array('product_id'=>$product_data->id));
				}
			}
			// echo "<pre>";
			// print_r($data_update);
			// print_r($data_insert);
			// exit;
			if(isset($data_category) && !empty($data_category))	{
				$this->db->insert_batch($this->product_category_table,$data_category);
			}

			if(isset($data_insert) && !empty($data_insert))	{
				$this->db->insert_batch($this->products,$data_insert);
			}

			if(isset($data_update) && !empty($data_update)){
				$this->db->update_batch($this->products,$data_update,'bc_product_id'); 
			}

			if(isset($data_option) && !empty($data_option))	{
				$this->db->insert_batch($this->product_option_table,$data_option);
			}
		
		}

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
		
		$check_product_exist = '';
		$stock ='';
		$product_image = '';
		$product_price = '';
		$brand_data = '';
		$brand_id	= 0;
		$brand_name = '';
		$product_name = '';
		$sku = '';
	}

	public function GetProductID($bc_product_id){

		$query_product = $this->db->query("SELECT bc_product_id FROM ".$this->products." WHERE bc_product_id = '".$bc_product_id."'");
		return $query_product->num_rows();
	}

	public function emptytable()
	{
		$query_product = $this->db->query("TRUNCATE TABLE bc_product_data_store");
	}

	public function getbunchinserteddata_series()
	{
		$query_product_bc_data = $this->db->query("SELECT page,data_details FROM ".$this->product_table_bc."");
		$bc_product_data = $query_product_bc_data->result_array();
		if(isset($bc_product_data) &&  !empty($bc_product_data))
		{
				
				$res = $this->InsertProductMulti_series($bc_product_data);	
				
		}
	}

	public function InsertProductMulti_series($bc_product_data)
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
			foreach($product_data_multi as $product_data_s)
			{
					
				$product_data = Bigcommerce::getProduct($product_data_s);

				if(isset($product_data->categories) && !empty($product_data->categories))
				{
					foreach($product_data->categories as $category)
					{ 
						
						$parent_id = $this->getprentcategory($category);
						$insert_series['title'] = $category;
						$insert_series['category_id'] = $parent_id['parent_id'];
						
						$this->db->insert('serise', $insert_series);
   						$insert_id = $this->db->insert_id();

   						$series_product['serise_id'] = $insert_id;
   						$series_product['product_id'] = $product_data_s;

   						$this->db->insert('serise_product', $series_product);
					}
				}
				
			}
		}
	}

	public function getprentcategory($category)
	{
		$query= $this->db->query("SELECT parent_id FROM category WHERE 	category_id = '".$category."' ");
		return $query->row_array();
	}

}
?>