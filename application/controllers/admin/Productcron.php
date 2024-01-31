<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
use Bigcommerce\Api\Client as Bigcommerce;
class Productcron extends CI_Controller {
    protected $perPage;

	private $log_file = APPPATH . 'logs/cron/productcron.log';
	private $flag_file = APPPATH . 'third_party/flags/product-cron.flag';

	private $start_hrtime;
	private $pid;


	function __construct()
	{	
		parent::__construct();
		$this->load->model("admin/productmodel");
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');

		// helper functionality for product imports
		$this->load->library( 'ProductImport' );

		//ini_set('display_errors','On');
		//error_reporting(E_ALL);
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
	public function importproductdatabase()
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
			exit( 'Import is already is already running!' );
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
		}
	}

	public function multiproductimport()
	{
		$res_p = $this->productmodel->getbunchinserteddata();
	}

	public function sqlmode()
	{
		$cron = 'done';
		
		$query = $this->db->query("SET GLOBAL sql_mode='', SESSION sql_mode=''");
		file_put_contents(APPPATH.'third_party/hook/sqlmode.txt',print_r($cron,TRUE));
	}

}