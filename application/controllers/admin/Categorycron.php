<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
use Bigcommerce\Api\Client as Bigcommerce;
class Categorycron extends CI_Controller {
    protected $perPage;

	function __construct()
	{	
		parent::__construct();
		$this->load->model("admin/categorymodel");
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');
		include(APPPATH.'third_party/bcapi/vendor/autoload.php');
		$this->load->library('Ajax_pagination');
        $this->load->library('email');
        $this->perPage = 10;        

	}

	
	public function importcatdataindb()
	{			
		$config_data 	= $this->categorymodel->getBcConfig();
		
		$bcstoreurl		= $config_data['storeurl'];
		$client_id		= $config_data['client_id'];
		$store_hash		= $config_data['storehas'];
		$auth_token		= $config_data['apitoken'];
	
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); // Bc class connection				
		Bigcommerce::verifyPeer(false); // SSL verify False
		Bigcommerce::failOnError(); // Display error exception on
		$api_limit		= 50;
		$total_category = Bigcommerce::getCategoriesCount();
		
		// echo $total_category; exit;
		$total_pages 	= ceil($total_category / $api_limit);

		if(isset($total_pages) && !empty($total_pages) && $total_pages  > 0)
		{
			for($i=1;$i<=$total_pages;$i++)
			{ 	
				$vars = array(
					"page" => $i,
					"limit" => $api_limit
				);
				
				$getCategory = Bigcommerce::getCategories($vars);
				
				$data_category =  array();
				$data_category_update = array();
				$image_base_path='https://cdn11.bigcommerce.com/s-ilhtqzrn07/product_images/';
				$j = 0;
				foreach($getCategory as $category_data)
				{	

					$cat_ser_data = base64_encode(serialize($category_data));
					if(empty($category_data->image_file))
						$image=asset_url().'media/images/default.jpg';
					else
						$image=$image_base_path.$category_data->image_file;
					// Insert Category
					$data_category[$j]['category_id']  	     	 = $category_data->id;
					$data_category[$j]['category_data'] 		 = $cat_ser_data;
					$data_category[$j]['parent_id'] 			 = $category_data->parent_id;
					$data_category[$j]['name']           		 = $category_data->name;
					$data_category[$j]['description'] 		 	 = $category_data->description;
					$data_category[$j]['sort_order'] 	 	 	 = $category_data->sort_order;
					$data_category[$j]['page_title'] 	    	 = $category_data->page_title;
					$data_category[$j]['meta_keywords']     	 = $category_data->meta_keywords;
					$data_category[$j]['meta_description']  	 = $category_data->meta_description;
					$data_category[$j]['layout_file'] 			 = $category_data->layout_file;
					$data_category[$j]['is_visible'] 			 = $category_data->is_visible;
					$data_category[$j]['search_keywords']	 	 = $category_data->search_keywords;
					$data_category[$j]['url'] 					 = $category_data->url;
					$data_category[$j]['image'] 				 = $image;
					$j++;
				}

				$this->categorymodel->importcategorydb($data_category);
				echo 'Batch '.$i.' categories sorting updated successfully..<br>';
			}
		}
		//redirect('/admin/category');
	}

}
?>