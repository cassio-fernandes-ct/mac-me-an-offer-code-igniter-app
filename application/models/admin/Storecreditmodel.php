<?php
use Bigcommerce\Api\Client as Bigcommerce;
class Storecreditmodel extends CI_Model
{
	function __construct()
	{
		
		$this->customers_table = "customers_log";
		$this->setting_table   = "setting";
		include(APPPATH.'/third_party/bcapi/vendor/autoload.php');
		$this->load->database();
	}

	public function getBcConfig(){
		$query = $this->db->get_where($this->setting_table,array('id'=>1));
		return $query->row_array();
	}

	public function get_customredata($search)
	{

		$search_query = '';
		if(isset($search) && !empty($search))
		{
			$search_e = explode(' ',$search);
			$i = 1;
			foreach($search_e as $search_e_s)
			{	
				if($i == 1){
					$search_query .= ' WHERE (email LIKE "%'.$search_e_s.'%" OR firstname LIKE "%'.$search_e_s.'%" OR lastname LIKE "%'.$search_e_s.'%")';
				}else{
					$search_query .= ' AND (email LIKE "%'.$search_e_s.'%" OR firstname LIKE "%'.$search_e_s.'%" OR lastname LIKE "%'.$search_e_s.'%" )';
				}
				$i++;
			}
		}
		
		$query = $this->db->query("SELECT * FROM ".$this->customers_table." ".$search_query." GROUP BY `email` ");
		return $query->num_rows();

	}



	public function get_serial_details($startform,$limit,$search)
	{
		
		$search_query = '';
		if(isset($search) && !empty($search))
		{
			$search_e = explode(' ',$search);
			$i = 1;
			foreach($search_e as $search_e_s)
			{	
				if($i == 1){
					$search_query .= ' WHERE (email LIKE "%'.$search_e_s.'%" OR firstname LIKE "%'.$search_e_s.'%" OR lastname LIKE "%'.$search_e_s.'%")';
				}else{
					$search_query .= ' AND (email LIKE "%'.$search_e_s.'%" OR firstname LIKE "%'.$search_e_s.'%" OR lastname LIKE "%'.$search_e_s.'%")';
				}
				$i++;
			}
		}


		$query = $this->db->query("SELECT * FROM $this->customers_table ".$search_query." GROUP BY `email` ORDER BY id DESC limit ".$startform." ,".$limit."");
		return $query->result_array();
	}

	public function getstorecreditdata($mmo,$mmt){
		$query = $this->db->query("SELECT * FROM $this->customers_table WHERE bc_id_mmo = ".$mmo." AND bc_id_mmt = ".$mmt."");
		return $query->row_array();
	}

	public function updatestorecredit($mmo,$mmt){

		$mmo_array = array();
		$mmt_array = array();

		if(isset($mmo) && !empty($mmo))
		{
			$this->Bigcommerceapiconfig();
			$data['customercreate']['store_credit'] = $this->input->post('storecredit');
			
			try {
				$createdustomerid = Bigcommerce::updateCustomer($mmo,$data['customercreate']);
				$mmo_array['id'] = $createdustomerid->id;
			}

			//catch exception
			catch(Exception $e) {

			  	$error = 'Message: ' .$e->getMessage();
			  	$mmo_array['error'] = $error;

			}

		}

		if(isset($mmt) && !empty($mmt))
		{
			$this->Bigcommerceapiconfigmmt();
			$data['customercreate']['store_credit'] = $this->input->post('storecredit');
			
			try {
				$createdustomerid = Bigcommerce::updateCustomer($mmt,$data['customercreate']);
				$mmt_array['id'] = $createdustomerid->id;
			}

			//catch exception
			catch(Exception $e) {

			  	$error = 'Message: ' .$e->getMessage();
			  	$mmt_array['error'] = $error;

			}

		}

		if(isset($mmo_array['error']) && !empty($mmo_array['error']) && isset($mmt_array['error']) && !empty($mmt_array['error']))
		{
			$data['storecredit'] = $this->input->post('storecredit');
			$this->db->insert($this->customers_table,$data);

		}else{
			$arrayquery['bc_id_mmo'] = $mmo;
			$arrayquery['bc_id_mmt'] = $mmt;

			$datad['storecredit'] = $this->input->post('storecredit');

			$this->db->where($arrayquery);
			$this->db->update($this->customers_table, $datad);
		}
		
		$res['mmo'] =  $mmo_array;
		$res['mmt'] =  $mmt_array;
		
		return $res;
		
	}

	public function Bigcommerceapiconfigmmt()
	{
		$config_data = $this->getBcConfig();
		$bcstoreurl		= $config_data['storeurltrades'];
		$client_id		= $config_data['client_idtrades'];
		$store_hash		= $config_data['storehastrades'];
		$auth_token		= $config_data['apitokentrades'];

		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); // Bc class connection				
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError();	
	}


	public function Bigcommerceapiconfig()
	{

		$config_data = $this->getBcConfig();
		$bcstoreurl		= $config_data['storeurl'];
		$client_id		= $config_data['client_id'];
		$store_hash		= $config_data['storehas'];
		$auth_token		= $config_data['apitoken'];

		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); // Bc class connection				
		Bigcommerce::verifyPeer(false); 
		Bigcommerce::failOnError(); 

	}
}
	
?>