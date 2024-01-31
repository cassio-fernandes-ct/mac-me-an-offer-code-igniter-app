<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI Storecredit.php
use Bigcommerce\Api\Client as Bigcommerce;
class Storecredit extends CI_Controller {
    protected $perPage;

	function __construct()
	{	
		parent::__construct();
		$this->load->model("admin/storecreditmodel");
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
    {	$this->data['title'] = 'Store Credit';	
    	$this->load->view('admin/common/leftmenu',$this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/storecredit/list');
        $this->load->view('admin/common/footer');
	}

	public function dataajax()
	{
		
		$search = '';
		if(isset($_REQUEST['query']) &&  !empty($_REQUEST['query']))
		{
			$search = $_REQUEST['query']['generalSearch'];
		}

		$totalcategory = $this->storecreditmodel->get_customredata($search);
		
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

			$totalcategory = $this->storecreditmodel->get_serial_details($start_from,$records['meta']['perpage'],$search);
		}else{
			$start_from = ($records['meta']['page']-1) * $records['meta']['perpage']; 
			$totalcategory = $this->storecreditmodel->get_serial_details($start_from,$records['meta']['perpage'],$search);
		}
		//$records['data'] = '';
		
		
		if(isset($totalcategory) && !empty($totalcategory))
		{
			$i = 0;

			foreach ($totalcategory as  $value) {
				
				
				$records['data'][$i]['category_id'] = $value['id'];
				$records['data'][$i]['email'] = $value['email'];
				$records['data'][$i]['name'] =  $value['firstname'].' '.$value['lastname'];
			
				$records['data'][$i]['storecredit'] =  '$'.number_format($value['storecredit'],2);
				//
				//$records['data'][$i]['category'] = '';
				$records['data'][$i]['action'] =  '<button type="button" class="btn btn-outline-info btn-elevate btn-pill" onclick="update_storescredit(\''.$value['bc_id_mmo'].'\',\''.$value['bc_id_mmt'].'\')" >'.
			        		'<i class="flaticon-edit-1"></i>Update</button>';
				$i++;
			}
		}
		echo json_encode($records);
	}

	public function edit(){

		$this->data['title'] = 'Store Credit';

		$mmo = $this->uri->segment(4);
		
		$mmt = $this->uri->segment(5);
	
		if(isset($mmo) && !empty($mmo) && isset($mmt) && !empty($mmt))
		{
			$this->data['getstorecreditdata'] = $this->storecreditmodel->getstorecreditdata($mmo,$mmt);	
			
			if ($this->input->server('REQUEST_METHOD') === 'POST')
			{
				$data = $this->storecreditmodel->updatestorecredit($mmo,$mmt);
				if(isset($data['mmo']['error']) && !empty($data['mmo']['error']) && isset($data['mmt']['error']) && !empty($data['mmt']['error']))
				{
					$this->data['storecredit_error'] = $data;
					//redirect('admin/storecredit/edit/'.$mmo.'/'.$mmt);
				}else{
					$this->session->set_userdata('updatedata','1'); 
					redirect('admin/Storecredit');
				}
			}

	    	$this->load->view('admin/common/leftmenu',$this->data);
	        $this->load->view('admin/common/header');
	        $this->load->view('admin/storecredit/create',$this->data);
	        $this->load->view('admin/common/footer');
	    }else{

	    	$this->session->set_userdata('notabletoredirect','1'); 
			redirect('admin/Storecredit');
	    }
	}

	
}
?>