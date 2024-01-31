<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
use Bigcommerce\Api\Client as Bigcommerce;
class Order extends CI_Controller {
    protected $perPage;

	function __construct()
	{	
		parent::__construct();
		$this->load->model("admin/ordermodel");
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

	public function importorderdatabase()
	{
		
		$this->ordermodel->emptytable();

		$config_data = $this->ordermodel->getBcConfig();
		
		$bcstoreurl		= $config_data['storeurltrades'];
		$client_id		= $config_data['client_idtrades'];
		//$store_hash		= 'z7godtn57o';
		$store_hash		= $config_data['storehastrades'];
		$auth_token		= $config_data['apitokentrades'];
	
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); 			
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError(); 

		$api_limit		= 40;
		$total_order = Bigcommerce::getOrdersCount($filter = array());
		
		$total_pages 	= ceil($total_order / $api_limit);
		
		if(isset($total_pages) && !empty($total_pages) && $total_pages > 0)
		{
			for($i=1;$i<=$total_pages;$i++)
			{ 	
				$var = array(
					"page" => $i,
					"limit" => $api_limit
				);
				
				$getOrders = Bigcommerce::getOrders($var);
				
				if(isset($getOrders) && !empty($getOrders))
				{
				    $this->ordermodel->InsertBcOrders($getOrders,$i,$api_limit);
				   
				}
			}
			$result = $this->multiproductimport();
		}

	}

	public function multiproductimport()
	{
		$res_p = $this->ordermodel->getbunchinserteddata();
		$this->session->set_userdata('updatedataproductdata','1'); 
		redirect('admin/settings');
	}

	public function demoorder(){
		$res_p = $this->ordermodel->demoorder();
	}
}
?>