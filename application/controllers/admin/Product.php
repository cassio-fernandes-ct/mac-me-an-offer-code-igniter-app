<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
use Bigcommerce\Api\Client as Bigcommerce;
class Product extends CI_Controller {
    protected $perPage;

	function __construct()
	{	
		parent::__construct();
		$this->load->model("admin/productmodel");
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');

		// helper functionality for product imports
		$this->load->library( 'ProductImport' );

		ini_set('display_errors','On');
		error_reporting(E_ALL);
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

	public function index()
	{
		echo "product import";
	}


	/**
	 * @todo 	Combine with controllers/admin/Productcron.php. Ideally put all product import functionality in 
	 * 			ProductImport library
	 *
	 */
	public function importproductdatabase( $run_hash = '' )
	{
		$run_hash = $_GET['run_hash'] ?? '';
		
		// prevent unauthorized execution attempts
		if( !$this->productimport->validate_hash( $run_hash ) ) {
			$this->productimport->log_failed_attempt( 'failed hash validation' );
			exit( 'You are not allowed to run this import.' );
		}

		// prevent concurrent execution attempts
		if( $this->productimport->is_running() ) {
			$this->productimport->log_failed_attempt( 'import already running' );
			exit( 'Import is already running!' );
		}

		ignore_user_abort( true );
		$this->productimport->start();

		$this->productmodel->emptytable();
		$config_data = $this->productmodel->getBcConfig();
	
		$bcstoreurl		= $config_data['storeurl'];
		$client_id		= $config_data['client_id'];
		//$store_hash		= 'z7godtn57o';
		$store_hash		= $config_data['storehas'];
		$auth_token		= $config_data['apitoken'];
	
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); 			
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError(); 

		$api_limit		= 250;
		$total_product = Bigcommerce::getProductsCount($filter = array());
	
		$total_pages 	= ceil($total_product / $api_limit);
		
		if(isset($total_pages) && !empty($total_pages) && $total_pages > 0)
		{
			for($i=1;$i<=$total_pages;$i++)
			{ 	
				$var = array(
					"page" => $i,
					"limit" => $api_limit
				);

				$getProducts = Bigcommerce::getProducts($var);
				
				if(isset($getProducts) && !empty($getProducts))
				{
				    $this->productmodel->InsertBcProduct($getProducts,$i,$api_limit);
				   
				}
			}
			
			$this->multiproductimport();

			// updates log and sends notification
			$this->productimport->finish();	

			$this->finish();
		}
	}

	public function multiproductimport()
	{
		$this->productmodel->getbunchinserteddata();
	}
	
	public function finish() 
	{
		$this->session->set_userdata('updatedataproductdata','1'); 
		redirect('admin/settings');
	}


	public function importBarndToDb()
	{
		$config_data = $this->productmodel->getBcConfig();
	
		$bcstoreurl		= $config_data['storeurl'];
		$client_id		= $config_data['client_id'];
		//$store_hash		= 'z7godtn57o';
		$store_hash		= $config_data['storehas'];
		$auth_token		= $config_data['apitoken'];
	
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); 			
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError(); 

		$brand = Bigcommerce::getBrands();
		$brand_insert = array();
		if(isset($brand) && !empty($brand))
		{
			foreach ($brand as $value) {
			
				$brand_insert[] =  array(
					'brand_id' 	=> $value->id,
					'brand_name' 	=> $value->name );
				
			}
		}

		$this->productmodel->importBarndToDb($brand_insert);		
		echo "barnd import";
		
	}
	public function datainseert()
	{
		$config_data = $this->productmodel->getBcConfig();
	
		$bcstoreurl		= $config_data['storeurl'];
		$client_id		= $config_data['client_id'];
		//$store_hash		= 'z7godtn57o';
		$store_hash		= $config_data['storehas'];
		$auth_token		= $config_data['apitoken'];
	
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); 			
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError(); 

		$api_limit		= 250;
		$product_data = Bigcommerce::getProduct('650');
		$productoption = Bigcommerce::getProductoptions('650');
	
		$createdate = date('Y-m-d H:i:s');

		$check_product_exist = '';$stock ='';$product_image = '';$product_price = '';$brand_data = '';$brand_id	= 0;$brand_name = '';$product_name = '';
		$bc_product_status = 'inactive';
		if(isset($product_data->is_visible) && !empty($product_data->is_visible) && $product_data->is_visible == 1){
			$bc_product_status = 'active';
		}
		$stock 	= $product_data->inventory_level;
		
	
		if(isset($product_data->name) && !empty($product_data->name)){ $product_name = $product_data->name;}

		if(isset($product_data->primary_image->thumbnail_url) && !empty($product_data->primary_image->thumbnail_url)){ $product_image = $product_data->primary_image->thumbnail_url;}

		if(isset($product_data->price) && !empty($product_data->price)){ $product_price = $product_data->price;}


		if(isset($product_data->brand_id) && !empty($product_data->brand_id)){ $brand_id = $product_data->brand_id;}

	
		$data_update[] =  array(
			"bc_product_id" 		=> $product_data->id,
			"product_sku" 			=> $product_data->sku,
			"product_title" 		=> $this->db->escape_str($product_data->name),	
			"image" 				=> $product_image,
			"price"					=> $product_price,
			"brand_id" 				=> $brand_id,
			"brand_name" 			=> $brand_name,
			"stock" 				=> $stock,
			"bc_product_status" 	=> $bc_product_status,
			"product_url"           => $product_data->custom_url,
			"create_date"			=> $createdate
		);

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
				}
			}
		}
	
	}

	public function getbunchinserteddata_series()
	{
		$this->productmodel->getbunchinserteddata_series();
	}

	public function newbc()
	{
		// 2023-05-30 :: WebFX :: troubleshooting where these requests are coming from
		$data = [
			'datetime' => date( 'Y-m-d H:i:s' ),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'file' => __FILE__,
			'line' => __LINE__,			
			'server' => $_SERVER,
			'request' => $_REQUEST,
			'get' => $_GET,
			'post' => $_POST
		];
		$data = PHP_EOL . json_encode( $data, JSON_PRETTY_PRINT ) . PHP_EOL;

		file_put_contents( APPPATH . '/logs/newbc.log', $data, FILE_APPEND );

		return;
		

		
		$api_url = 'https://api.bigcommerce.com/stores/ilhtqzrn07/v3';
		$client_id = '6pnulio9e8icu2uow0jjzynrm5tta38';
		$access_token = '5oaffn9ssm25999xyfqobadt49zegc3';
		$config = new \BigCommerce\Api\v3\Configuration();
		$config->setHost( $api_url );
		$config->setClientId( $client_id );
		$config->setAccessToken( $access_token );
		$client = new \BigCommerce\Api\v3\ApiClient( $config );
		$catalog  = new \BigCommerce\Api\v3\Api\CatalogApi( $client );

		$product_ids = [ 648 ];

		try {
			/*
			 * List of request parameters and response properties available at
			 * https://developer.bigcommerce.com/api-reference/catalog/catalog-api/products/getproducts
			 */
			$products_response = $catalog->getProducts(['limit' => '5','page' => '100',
				'include' => [ 'variants', 'custom_fields', 'images', 'bulk_pricing_rules', 'options', 'modifiers' ],
			

		]);
			// $products_response = $catalog->getProducts( [
			// 	//'id:in'   => $product_ids,
			// 	'include' => [ 'variants', 'custom_fields', 'images', 'bulk_pricing_rules', 'options', 'modifiers' ],
			// ] );
			echo "<pre>";
			print_r($products_response );
			
		} catch ( \BigCommerce\Api\v3\ApiException $e ) {
			$error_message = $e->getMessage();
			$error_body    = $e->getResponseBody();
			$error_headers = $e->getResponseHeaders();

			echo "<pre>";
			print_r($error_message );
			print_r($error_body );
			print_r($error_headers);


			// do something with the error
			return;
		}

		$product_ids = array_map( function( \BigCommerce\Api\v3\Model\Product $product ) {
			return $product->getId();
		}, $products_response->getData() );
	}

	public function oneprodut(){

			$config_data = $this->productmodel->getBcConfig();
			
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

			 $product_data_s = '410';
				
					//$cat = $category;
					$check_product_exist = '';$stock ='';$product_image = '';$product_price = '';$brand_data = '';$brand_id	= 0;$brand_name = '';$product_name = '';$sku = '';
					
					$product_data = Bigcommerce::getProduct($product_data_s);
					$productoption = Bigcommerce::getProductoptions($product_data_s);
					
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
					//$getOptionvalu = Bigcommerce::getOptionCValue('2040');
					
					$this->db->delete($this->product_category_table,array('product_id'=>$product_data->id));
					$this->db->delete($this->product_option_table,array('product_id'=>$product_data->id));
				
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

	public function GetProductID($bc_product_id){

		$query_product = $this->db->query("SELECT bc_product_id FROM products WHERE bc_product_id = '".$bc_product_id."'");
		return $query_product->num_rows();
	}

	

}
?>