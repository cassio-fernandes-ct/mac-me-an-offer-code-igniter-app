<?php 
class Series extends Admin_Controller {
    protected $perPage;

	function __construct()
	{	
		parent::__construct();
		$this->load->model("admin/categorymodel");
		$this->load->model("admin/serise_model");
		$session_data = $this->session->userdata('admin_session');
		if(!isset($session_data) || empty($session_data))redirect('admin/login');
       
	}

	public function index()
	{		
		$this->data['title'] = 'Series';	
    	$this->load->view('admin/common/leftmenu',$this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/serise/list');
        $this->load->view('admin/common/footer');
	}
	public function create($serise_id = null)
	{


		$this->load->library('form_validation'); 
        $this->load->helper('pluck');
		$this->data['title'] = 'Series';	
		$this->data['categories'] = array_pluck_category($this->serise_model->pluck_categories(),'name','category_id','Selct category');

		//$this->serise_model->series_subcategory();

		if(!empty($serise_id))
		{
			$this->data['serise'] = $this->serise_model->get_serise_by_id($serise_id);

		}
		if ($this->input->server('REQUEST_METHOD') === 'POST')
		{
			if(!empty($serise_id))
			{
				//$d = $this->input->post();
					//echo "<pre>";
					//print_r($d);
					//exit;
				if ($this->form_validation->run('serise_update') == TRUE)
		        {

		        	$postdata['category_id'] = $this->input->post('category_id');
		        	$postdata['title'] = $this->input->post('title');
		        	
					$this->serise_model->updateSerise($serise_id,$_POST['product_ids'],$postdata);
					$this->session->set_userdata('updatedata','1'); 
		        	redirect('admin/series');
	        	}
			}
			else
			{	
				if ($this->form_validation->run('serise_insert') == TRUE)
		        {

		        	$this->serise_model->insertSerise([
		        		'category_id' => $this->input->post('category_id'),
		        		'title' => $this->input->post('title'),
		        	],$_POST['product_ids']);

		        	$this->session->set_userdata('insertdata','1'); 
		        	redirect('admin/series');
		        }
			}
	        	 
		}
		

    	$this->load->view('admin/common/leftmenu',$this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/serise/create');
        $this->load->view('admin/common/footer');
	}


	public function delete($serise_id)
	{
		$this->serise_model->deleteSerise($serise_id);
		$this->session->set_userdata('deleted','1'); 
		redirect('/admin/series');
	}


	public function update($serise_id)
	{
		$this->create($serise_id);
		//redirect('/admin/serise');


	}

	

	public function get_category($category_id)
	{
		echo json_encode($this->serise_model->get_subcategorys_id($category_id)) ;

	}

	public function get_product($category_id)
	{
		echo json_encode($this->serise_model->get_product_by_category_id($category_id)) ;

	}
	 
	public function get_selected_product($serise_id)
	{
		echo json_encode($this->serise_model->get_selected_product($serise_id));
	}



	public function dataajax()
	{
		
		$search = '';
		if(isset($_REQUEST['query']) &&  !empty($_REQUEST['query']))
		{
			$search = $_REQUEST['query']['generalSearch'];
		}

		$totalcategory = $this->serise_model->get_serisedata($search);

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

			$totalcategory = $this->serise_model->get_serise_details($start_from,$records['meta']['perpage'],$search);
		}else{
			$start_from = ($records['meta']['page']-1) * $records['meta']['perpage']; 
			$totalcategory = $this->serise_model->get_serise_details($start_from,$records['meta']['perpage'],$search);
		}
		//$records['data'] = '';
		
		
		if(isset($totalcategory) && !empty($totalcategory))
		{
			$i = 0;

			foreach ($totalcategory as  $value) {
				
				$records['data'][$i]['category_id'] = $value['id'];
				$records['data'][$i]['title'] =  $value['name'];
				//$records['data'][$i]['category'] = '';
				$records['data'][$i]['action'] =  '<button type="button" class="btn btn-outline-info btn-elevate btn-pill" onclick="update_serise(\''.$value['id'].'\')" >'.
			        		'<i class="flaticon-edit-1"></i>Update</button>'.
			        ' <button type="button" class="btn 	btn-outline-danger  btn-elevate btn-pill" onclick="ask_confirmation_for_delete_serise(\''.$value['id'].'\',\''.$value['name'].'\');"  ><i class="flaticon-delete"></i>Delete</button>';
				$i++;
			}
		}
		echo json_encode($records);
	}

}

