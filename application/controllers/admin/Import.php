<?php 
class Import extends CI_controller{
	
	function __construct()
	{
		parent::__construct();	
		$session_data = $this->session->userdata('admin_session');
		if(!isset($session_data) || empty($session_data))redirect('admin/login');
		$this->load->library('upload');
		$this->load->library('image_lib');
		$this->load->model("admin/importmodel");
		
		
	}
	
	function index(){
		
		$this->data["title"] = 'Bulk Import/Export Management';
		$this->data["page_head"]  = 'Bulk Import/Export Management';
	
		$this->load->view('admin/common/leftmenu', $this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/import/list');
        $this->load->view('admin/common/footer');
	}

	public function dataajax()
	{
		

		$totalcategory = $this->importmodel->getimportlogtotal();

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

			$totalcategory = $this->importmodel->getimportlog($start_from,$records['meta']['perpage']);
		}else{
			$start_from = ($records['meta']['page']-1) * $records['meta']['perpage']; 
			$totalcategory = $this->importmodel->getimportlog($start_from,$records['meta']['perpage']);
		}
		//$records['data'] = '';
		
		
		if(isset($totalcategory) && !empty($totalcategory))
		{
			$i = 0;
			foreach ($totalcategory as  $value) {
				
				$records['data'][$i]['id'] = $value['id'];
				$records['data'][$i]['file_name'] =  $value['file_name'];
				$records['data'][$i]['date'] = $value['date'];
				
				$records['data'][$i]['action'] = '<a href = "'. $this->config->base_url().'application/uploads/serial/'.$value['file_name'].'"> <button type="button" class="btn btn-outline-info btn-elevate btn-pill" "><i class="flaticon-download"></i>Download</button> </a><a href = "'. $this->config->base_url().'admin/import/deletelogfile?file_name='.$value['file_name'].'&id='.$value['id'].'"> <button type="button" class="btn btn-outline-danger  btn-elevate btn-pill" "><i class="flaticon-delete"></i>Delete</button> </a>';
				$i++;
			}
		}else{
			$records['data'][0]['date'] = "No records found";
		}
		
		echo json_encode($records);
	
	}
	
	function uploadcsv()
	{
		
		$uploaddir = FCPATH.'application/uploads/serial'; 
		
		$upload_conf = array(
            'upload_path'   => $uploaddir,
            'allowed_types' => "csv",
            'max_size'      => '0',
            'overwrite'     => false,
			'remove_spaces' => true,
			'encrypt_name' => false,
			'file_name' =>time()
            );
		$this->upload->initialize($upload_conf);
		foreach($_FILES['uploadfile'] as $key=>$val)
        {
            $i = 1;
            foreach($val as $v)
            {
                $field_name = "file_".$i;
                $_FILES[$field_name][$key] = $v;
                $i++;   
            }
        }
		unset($_FILES['uploadfile']);

		$error = array();
        $success = array();
        		//foreach($_FILES as $field_name => $file)
       // {
		    if (!$this->upload->do_upload($field_name))
            {
                $error['upload'][] = $this->upload->display_errors();
            }
			else
            {
				$config = array(
					'file_name' => $field_name
				);

				$upload_data = $this->upload->data();
				$success['original'][] = $upload_data;
				$upload_name = $upload_data['file_name'];
			}  
       // }

		if(count($error) > 0)
        {
            $data['status'] = 'error'; 
			$data['field_name'] = '';
			$data['error_data'] = $error;
        }
        else
        {
			$this->importmodel->importcsvlog($upload_name);
			$data['status'] 	  = 'success';
			$data['field_name']   = $upload_name;
            $data['success_data'] = $success;
        }
		
		
		echo json_encode($data);
	}

	function getorderdata()
	{	
		$filename = $this->input->post('filename');
	
		$file_path 			 = FCPATH.'application/uploads/serial/'.$filename; 

		$getcsvdata    		 = $this->readCSV($file_path);
		
		$this->data['importdata']   = array();
		$this->data['importdata']   = $getcsvdata;
		$this->data['total_import_data']  = count($getcsvdata);
		
		$import_data = '';

		$import_data = $this->load->view('admin/import/upload',$this->data, TRUE);
		//$import_data = $this->load->view('admin/import/newlist',$this->data, TRUE);

		 

		echo $import_data;
	}

	
	function getorderdataa()
	{	
		//$filename = $this->input->post('filename');
		$filename = '1574317678.csv';
		
		$file_path 			 = FCPATH.'application/uploads/serial/'.$filename; 

		$getcsvdata    		 = $this->readCSV($file_path);
		
		$this->data['importdata']   = array();
		$this->data['importdata']   = $getcsvdata;
		$this->data['total_import_data']  = count($getcsvdata);
		
		$import_data = '';

		//$import_data = $this->load->view('admin/import/upload',$this->data, TRUE);

		 $this->load->view('admin/common/leftmenu', $this->data);
         $this->load->view('admin/common/header');
		 $this->load->view('admin/import/newlist',$this->data);
		 $this->load->view('admin/common/footer');

		echo $import_data;
	}
	
	function readCSV($csvFile)
	{
		$file_handle = fopen($csvFile, 'r');
		$header = null;
		$all_rows2 = array();
		$i = 1;
		while (!feof($file_handle) ) {
			$r = fgetcsv($file_handle, 1024, "," ,'"', "\\");
			if ($header === null) {
				$header = str_replace(' ','_',$r);
				continue;
			}
		
			if(isset($r) && !empty($r)){
				
				$combine_header_data = array_combine($header, $r);
				$combine_header_data_lower = array_change_key_case($combine_header_data, CASE_LOWER);
				$all_rows2[$i] = $combine_header_data_lower;
			}
			$i++;
		}
		
		$_SESSION['import_data'] = $all_rows2;
		return $all_rows2;
	}
	
	function importsingledata(){
		
		$import_ids = $this->input->get('code');
		$data_status = array();
		if(isset($import_ids) && !empty($import_ids))
		{
			$insert_data = array();
			$update_data = array();
			$delete_data = array();
			foreach($import_ids as $import_ids_s)
			{
				$data_status[$import_ids_s]['code']  = $import_ids_s;
				$filter_data 						 = $_SESSION['import_data'][$import_ids_s];
				
				$product_id 	= $this->importmodel->checkproductDB($filter_data['product']);
				if(isset($product_id) && !empty($product_id)){
					if(isset($filter_data['id']) && !empty($filter_data['id']))
					{

						if(isset($filter_data['action']) && !empty($filter_data['action']) &&  strtolower($filter_data['action']) == 'd'){

							$delete_data[] =  $filter_data['id'];
							$data_status[$import_ids_s]['response']  = $filter_data['serial'].' - Filter data delete successfull...';

						}else{

							$update_data[] =  array(
								'id'            => $filter_data['id'],
								'serial'        => $filter_data['serial'],
								'bc_product_id' => $product_id['bc_product_id'] 
							);

							$data_status[$import_ids_s]['response']  = $filter_data['serial'] . ' - Filter data update successfull...';
						}

					}else{
						$checkdata = $this->importmodel->checkdata($filter_data['serial'],$product_id['bc_product_id']);
						if(isset($checkdata) && !empty($checkdata))
						{

							$data_status[$import_ids_s]['response']  = $filter_data['serial'] . ' - Filter data update successfull...';

						}else{
							$insert_data[] = array(
								'serial'        => $filter_data['serial'],
								'bc_product_id' => $product_id['bc_product_id']
							);

							$data_status[$import_ids_s]['response']  = $filter_data['serial'] . ' - Filter data import successfull...';
						}
						
					}
				}else{

					$data_status[$import_ids_s]['response']  = $filter_data['serial'] . 'product not found.';
				}
			}

			if(isset($insert_data) && !empty($insert_data)){
				$this->importmodel->inserdbrecord($insert_data);
			}
			if(isset($update_data) && !empty($update_data)){
				$this->importmodel->updatedbrecord($update_data);
			}
			
			if(isset($delete_data) && !empty($delete_data)){
				$this->importmodel->deletedbrecord($delete_data);
			}
		}
		echo json_encode($data_status);
	}
	
	function deletelogfile(){
		$delete_id = $this->input->get('id');
		$filename  = $this->input->get('file_name');
		$this->importmodel->deletelog($delete_id,$filename);
		
		$this->session->set_userdata('logfileremove','yes');
		redirect('admin/import');
	}
	
	
	function exportfilterdata(){
		
		$export_data = $this->importmodel->exportfilterdataDB();
		
		$i = 0;
		$list = array();
		foreach ($export_data as $filter_data){

			$list[$i]['id'] = $filter_data['id'];
			$list[$i]['serial'] = $filter_data['serial'];
			$list[$i]['product'] = $filter_data['product_title'];
			$list[$i]['action'] = '';
			$i++;

		}
		//$uploaddir = FCPATH.'application/uploads/import'; 

		header("Content-type: application/csv");
  		header("Content-Disposition: attachment; filename=serial.csv");
  		$fp = fopen('php://output', 'w');
  		$csv_fields=array();

		$csv_fields[] = 'id';
		$csv_fields[] = 'serial';
		$csv_fields[] = 'product';
		$csv_fields[] = 'action';
		
  		fputcsv($fp, $csv_fields);
		foreach ($list as $fields) {
		    fputcsv($fp, $fields);
		}

		fclose($fp);
		 
	}

}
?>