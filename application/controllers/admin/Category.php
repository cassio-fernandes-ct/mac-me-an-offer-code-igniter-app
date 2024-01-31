<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
use Bigcommerce\Api\Client as Bigcommerce;
class Category extends CI_Controller {
    protected $perPage;

	function __construct()
	{	
		parent::__construct();
		$this->load->model("admin/categorymodel");
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');
		include(APPPATH.'third_party/bcapi/vendor/autoload.php');
		
		$session_data = $this->session->userdata('admin_session');
		if(!isset($session_data) || empty($session_data))redirect('admin/login');
        $this->load->library('Ajax_pagination');
        $this->load->library('email');
        $this->perPage = 10;        

	}

	public function index()
    {	$this->data['title'] = 'Category';	
    	$this->load->view('admin/common/leftmenu',$this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/category/list');
        $this->load->view('admin/common/footer');
	}

	public function view(){

		$ctegory_id =  $this->uri->segment(4);
		$totalcategory = $this->categorymodel->update_data($ctegory_id);

		$this->load->view('admin/common/leftmenu',$this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/category/create');
        $this->load->view('admin/common/footer');
		
	}

	public function dataajax()
	{
		$search = '';
		if(isset($_REQUEST['query']) &&  !empty($_REQUEST['query']))
		{
			$search = $_REQUEST['query']['generalSearch'];
		}

		$totalcategory = $this->categorymodel->get_categorydata($search);

		$records['meta']['total'] = $totalcategory;
			
		if (isset($_REQUEST['pagination']) && !empty($_REQUEST['pagination'])) 
		{
			$records['meta']['sort'] = $_REQUEST['sort']['sort'];	
		}
		$records['meta']['page'] = 1;
		if (isset($_REQUEST['pagination']['page']) && !empty($_REQUEST['pagination']['page'])) 
		{
			$records['meta']['page'] = $_REQUEST['pagination']['page'];
		}

		$records['meta']['perpage'] = 10;
		if (isset($_REQUEST['pagination']['perpage']) && !empty($_REQUEST['pagination']['perpage'])) 
		{
			$records['meta']['perpage'] = $_REQUEST['pagination']['perpage'];
		}

		$records['meta']['pages'] = ceil($records['meta']['total']/$records['meta']['perpage'] );

		

		if($records['meta']['pages'] < $records['meta']['page'])
		{
			$start_from = 0;

			$totalcategory = $this->categorymodel->get_categorydetails($start_from,$records['meta']['perpage'],$search);
		}else{
			$start_from = ($records['meta']['page']-1) * $records['meta']['perpage']; 
			$totalcategory = $this->categorymodel->get_categorydetails($start_from,$records['meta']['perpage'],$search);
		}
		//$records['data'] = '';
		
		
		if(isset($totalcategory) && !empty($totalcategory))
		{
			$i = 0;
			foreach ($totalcategory as  $value) {
				
				$records['data'][$i]['id'] = $value['category_id'];
				$records['data'][$i]['image'] = '<img style = "width:50%" src="' .$value['image']. '" alt="'.$value['image'].'"/>';
				$records['data'][$i]['title'] = $value['name'];
				$records['data'][$i]['category'] = '<input type="text" onkeypress="return isNumberKey(event)" onkeyup="myFunction(\''.$value['category_id'].'\')" id="'.$value['category_id'].'" name="display_order['.$value['category_id'].']" value="'.@$value['display_order'].'" class="form-control display_order display_order_textbox" style="width:45%;">';
				if($value['status'] == 'no')
				{
					$status = '<span style="cursor: pointer;" style="width: 211px;"><span onclick="update(\''.$value['category_id'].'\',\''.$value['status'].'\');" class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill">Not visible on homepage</span></span>';
				}else{
					$status = '<span style="cursor: pointer;" style="width: 211px;"><span onclick="update(\''.$value['category_id'].'\',\''.$value['status'].'\');" class="kt-badge  kt-badge--primary kt-badge--inline kt-badge--pill">Show the category on homepage</span></span>';
				}
				$records['data'][$i]['status'] = $status;
				$i++;
			}
		}
		
		echo json_encode($records);
	
	}

	public function update()
	{
		$ctegory_id =  $this->uri->segment(4);
		$status =  $this->uri->segment(5);
		$this->categorymodel->update($ctegory_id,$status);
		$this->session->set_userdata('updatedata','1'); 
		redirect('/admin/category');
	}

	

	// dav code end//
	public function empty_table()
	{
		$this->categorymodel->EmptycategoryTable();
		
		redirect('/admin/category');
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
				echo $i.',';
			}
		}
		redirect('/admin/category');
	}

	function emaildemo()
	{
	
		$config_data =  $this->categorymodel->getBcConfig();
		
		$config['protocol']    = $config_data['protocol'];
        $config['smtp_user']   = $config_data['smtp_user'];
        $config['smtp_port']   = $config_data['smtp_port'];
        $config['smtp_host']   = $config_data['smtp_host'];
        $config['smtp_pass']   = $config_data['smtp_pass'];
        $config['smtp_crypto'] = 'ssl';
        $config['charset']     = 'iso-8859-1';
        $config['wordwrap']    = TRUE; 

		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");
		$this->email->from($config['smtp_user']);
		$this->email->to($config_data['admin_email']);
		$this->email->reply_to($config_data['admin_email']);
		$this->email->subject('testing');
		$this->email->message('this mail for s testing.');
		$this->email->attach('/var/www/html/application/uploads/ups/shipping/1576764535.gif');
		
		$this->email->attach('/var/www/html//application/uploads/pdf/MMAO_Packing_Instructions.pdf');
		//$this->email->send();

		if ($this->email->send()) {
            echo 'Your Email has successfully been sent.';
        } else {
            show_error($this->email->print_debugger());
        }
		
	}

	public function checkexistornot()
	{
		
		$data = $this->input->post();
		
		$result = $this->categorymodel->update_display_order_exit($data);
		echo $result;
	}
	public function orderlist()
	{
		$display_order = $this->input->post('display_order');

		$data = $this->categorymodel->update_display_order($display_order);
		redirect('/admin/category');
	}

}
?>