<?php
class Customergroupmodel extends CI_Model{
	var $table_name	= "";
	function __construct()
	{
		$this->customer_table 			 	= "store_credit_one";
		$this->customer_addresses_table 	= "customer_addresses";
		$this->setting_table  				= "setting";
	}
	
	public function getBcConfig(){
		$query = $this->db->get_where($this->setting_table,array('id'=>1));
		return $query->row_array();
	}
}
?>