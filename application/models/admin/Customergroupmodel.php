<?php
class Customergroupmodel extends CI_Model{
	var $table_name	= "";
	function __construct()
	{
		$this->customer_table 			 	= "store_credit_one";
		$this->customer_addresses_table 	= "customer_addresses";
		$this->setting_table  				= "setting";
	}
	
	function updatecustomerstatus($old_customer_id,$new_customer_id,$store_credit)
	{
		$query = $this->db->query("update ".$this->customer_table." set status = 'yes', mmo_bc_id = '".$new_customer_id."' WHERE mmt_bc_id = '".$old_customer_id."'");
		$query1 = $this->db->query("update customers_log set storecredit = ".$store_credit." WHERE bc_id_mmt = '".$old_customer_id."'");
	}

	public function getBcConfig(){
		$query = $this->db->get_where($this->setting_table,array('id'=>1));
		return $query->row_array();
	}
}
?>