<?php 
class Serial extends CI_controller{
	
	function __construct()
	{
		parent::__construct();	
		$session_data = $this->session->userdata('admin_session');
		if(!isset($session_data) || empty($session_data))redirect('admin/login');
		$this->load->library('upload');
		$this->load->library('image_lib');
		$this->load->model("admin/serial_model");
		
		
	}
	
	function index()
	{
		$this->data["title"] = 'Serial';
		$this->data["page_head"]  = 'Serial';
	
		$this->load->view('admin/common/leftmenu', $this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/serial/list');
        $this->load->view('admin/common/footer');
	}

	public function dataajax()
	{
		
		$search = '';
		if(isset($_REQUEST['query']) &&  !empty($_REQUEST['query']))
		{
			$search = $_REQUEST['query']['generalSearch'];
		}

		$totalcategory = $this->serial_model->get_serialdata($search);
		
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
			$totalcategory = $this->serial_model->get_serial_details($start_from,$records['meta']['perpage'],$search);
		}else{
			$start_from = ($records['meta']['page']-1) * $records['meta']['perpage']; 
			$totalcategory = $this->serial_model->get_serial_details($start_from,$records['meta']['perpage'],$search);
		}
		
		//$records['data'] = '';
		
		
		if(isset($totalcategory) && !empty($totalcategory))
		{
			$i = 0;
			foreach ($totalcategory as  $value) {
				
				$records['data'][$i]['id'] = $value['id'];
				$records['data'][$i]['serial'] = $value['serial'];
				$records['data'][$i]['action'] =  '<button type="button" class="btn btn-outline-info btn-elevate btn-pill" onclick="update_serise(\''.$value["id"].'\')" >'.
			        		'<i class="flaticon-edit-1"></i>Update</button>'.
			        ' <button type="button" class="btn 	btn-outline-danger  btn-elevate btn-pill" onclick="ask_confirmation_for_delete_serise(\''.$value["id"].'\',\''.$value["serial"].'\');"  ><i class="flaticon-delete"></i>Delete</button>';
				$i++;
			}
		}else{
			$records['data'][0]['serial'] = 'No recode found.';
		}
		echo json_encode($records);
	}
	
	public function delete($title)
	{

		$this->serial_model->deleteserial(urldecode($title));
		$this->session->set_userdata('deletedata','1');
		redirect('/admin/serial');
	}


	public function update($title)
	{
		$this->create($title);
		//redirect('/admin/serial');
	}

	public function create($serise_id = null)
	{
	

		$this->load->library('form_validation'); 
        $this->load->helper('pluck');
		$this->data['title'] = 'Serial';	
		$this->data['products'] = array_pluck($this->serial_model->pluck_proucts(),'product_title','bc_product_id','Selct Products');
		
		if(!empty($serise_id))
		{
			$this->data['serial'] = $this->serial_model->get_serial_by_id($serise_id);
		}
		
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			if(!empty($serise_id))
			{

				if ($this->form_validation->run('serial_update') == TRUE)
		        {
		        	$data = $this->input->post();
					$this->serial_model->updateSerise($serise_id,$data,$this->data['serial']['id']);
					$this->session->set_userdata('updatedata','1');
		        	redirect('admin/serial');
	        	}
			}
			else
			{	
				if ($this->form_validation->run('serial_insert') == TRUE)
		        {
		        	$data = $this->input->post();
		        	$this->serial_model->insertserial($data);
		        	$this->session->set_userdata('insertdata','1');
		        	redirect('admin/serial');
		        }
			}
	        	 
		}

    	$this->load->view('admin/common/leftmenu',$this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/serial/create');
        $this->load->view('admin/common/footer');
	}

	public function get_product($category_id)
	{
		echo json_encode($this->serise_model->get_product_by_category_id($category_id)) ;

	}
	 
	public function get_selected_product($serise_id)
	{
		echo json_encode($this->serise_model->get_selected_product($serise_id));
	}

	public function existserial()
	{
		$post = $this->input->post('search');
		$id = $this->input->post('id');

		echo $this->serial_model->existserial($post,$id);
	}
	
}
?>