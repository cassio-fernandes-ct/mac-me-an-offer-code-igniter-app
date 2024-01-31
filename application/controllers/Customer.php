<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
use Bigcommerce\Api\Client as Bigcommerce;
class Customer extends CI_Controller {
    protected $perPage;

	function __construct()
	{	
		parent::__construct();
		$this->load->model("customermodel");
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');
		ini_set('display_errors','On');
		error_reporting(E_ALL);
		include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function index()
	{
		echo "product import";
	}

	public function importcustomerdatabase()
	{

		$config_data = $this->customermodel->getBcConfig();
	
		$bcstoreurl		= $config_data['storeurl'];
		$client_id		= $config_data['client_id'];
		//$store_hash		= 'z7godtn57o';
		$store_hash		= $config_data['storehas'];
		$auth_token		= $config_data['apitoken'];
	
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); 			
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError(); 

		$api_limit		= 250;


		$total_customer = Bigcommerce::getCustomersCount($filter = array());
		echo "<pre>";
		print_r($total_customer);
		exit;

		/*$total_product = Bigcommerce::getProductsCount($filter = array());
	
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
			$result = $this->multiproductimport();
		}
		redirect('admin/product');*/
	}

}
?>