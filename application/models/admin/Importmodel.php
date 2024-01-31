<?php
header('Content-Type: text/html; charset=utf-8');
class Importmodel extends CI_Model{

	var $table_name	= "";
	
	function __construct(){
		
		$this->serial_table   = "serial";
		$this->product_table  = "products";
		$this->csvlog_table   = "import_file_log";
	}
	
	function Checkproducts($productsku)
	{
		$query_product = $this->db->query("SELECT * FROM ".$this->filter_table." WHERE product_sku = '".$productsku."'");
		return $query_product->num_rows();
	}

	function updatestatus($productsku)
	{
		$update_pro_id = $this->db->query("Update ".$this->product_table." SET status = 'yes' WHERE product_sku = '".$productsku."'");
	}
	
	function exportfilterdataDB(){
		
		$quer =  $this->db->query("SELECT s.id,s.serial,p.product_title FROM ".$this->serial_table." as s JOIN ".$this->product_table." as p ON(p.bc_product_id = s.bc_product_id)");

	return $quer->result_array();
       // query_to_csv($quer,TRUE,'filter_data'.rand().'.csv');
		
	}
	
	public function getimportlogtotal()
	{
	
		$query = $this->db->query("SELECT * FROM ".$this->csvlog_table."");
		return $query->num_rows();
	}


	function getimportlog($startform,$limit){
		$query_import_log = $this->db->query("SELECT * FROM ".$this->csvlog_table." ORDER BY id DESC limit ".$startform." ,".$limit."");
		return $query_import_log->result_array();
	}

	
	
	function deletedbrecord($delete_recored){
		$this->db->where_in('id', $delete_recored);
		$this->db->delete($this->serial_table);
	}
	
	function importcsvlog($file_name)
	{
		$current_date = date('Y-m-d H:i:s');
		$this->db->query("INSERT INTO ".$this->csvlog_table." SET file_name = '".$file_name."', date = '".$current_date."'");
	}
	
	function checkproductDB($productname){
		
		
		$query_product = $this->db->query("SELECT bc_product_id FROM ".$this->product_table." WHERE product_title = '".$productname."'");
		return $query_product->row_array();
	}

	function checkdata($serial,$bc_product_id)
	{
		$query_product = $this->db->query("SELECT id FROM ".$this->serial_table." 
			WHERE serial 		= '".$serial."' 
			AND bc_product_id   = '".$bc_product_id."'");
		return $query_product->row_array();
	}
	
	function inserdbrecord($insert_data)
	{
		if(isset($insert_data) && !empty($insert_data)){
			$this->db->insert_batch($this->serial_table,$insert_data);
		}
	}
	
	function updatedbrecord($update_data)
	{
		if(isset($update_data) && !empty($update_data)){
			$this->db->update_batch($this->serial_table,$update_data,'id');
		}
	}
	
	function deletelog($id,$file_name){
		
		$this->db->where_in('id', $id);
		$this->db->delete($this->csvlog_table);
		
		unlink('application/uploads/import/'.$file_name);
		
		return 'yes';
	}
}
?>