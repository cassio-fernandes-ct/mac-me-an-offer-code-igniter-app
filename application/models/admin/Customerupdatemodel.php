<?php 
use Bigcommerce\Api\Client as Bigcommerce;

class Customerupdatemodel extends CI_Model
{
	function __construct()
	{
		
		$this->setting_table            = "setting";
		$this->customers_log_table		= "customers_log";
		$this->address_log_table		= "address_log";
		$this->orders                   = "orders";
		$this->tmp_Cpassword_table      = "tmp_Cpassword";
		include(APPPATH.'/third_party/bcapi/vendor/autoload.php');
		$this->currentdate = date('Y-m-d H:i:s');
	}

	public function getBcConfig(){

		$query = $this->db->query("SELECT * FROM ".$this->setting_table." ");
		return $query->row_array();
	}
	public function noSyncMmtIdGet()
	{
		//echo "SELECT * FROM ".$this->customers_log_table." as c  INNER JOIN ".$this->tmp_Cpassword_table." as tmp ON c.email=tmp.email WHERE c.bc_id_mmt IS NULL OR c.bc_id_mmt = ''";
		//exit;

		//echo "SELECT * FROM '".$this->customers_log_table."' as c  INNER JOIN '".$this->tmp_Cpassword_table."' as tmp ON c.email=tmp.email WHERE `c.bc_id_mmt` IS NULL OR `c.bc_id_mmt` = ''";
		
	//	$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE `bc_id_mmt` IS NULL OR `bc_id_mmt` = ''");
		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." as c  INNER JOIN ".$this->tmp_Cpassword_table." as tmp ON c.email=tmp.email WHERE c.bc_id_mmt IS NULL OR c.bc_id_mmt = ''");
		return $query->result_array();

	}

	public function noSyncMmoIdGet(){

		//$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE `bc_id_mmo` IS NULL OR `bc_id_mmo` = ''");
		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." as c  INNER JOIN ".$this->tmp_Cpassword_table." as tmp ON c.email=tmp.email WHERE c.bc_id_mmo IS NULL OR c.bc_id_mmo = ''");
		return $query->result_array();
	}

	public function getpassword(){

		$query = $this->db->query("SELECT * FROM ".$this->tmp_Cpassword_table."");
		return $query->result_array();
	}

	public function updatepassword($pswinfo){
		 $this->db->update_batch($this->tmp_Cpassword_table, $pswinfo, 'email');

	}

	public function duplicataddressdeleteformmmo(){
		$query = $this->db->query("SELECT mmo_customer_id,mmo_address_id,mmt_customer_id,mmt_address_id,COUNT(*) FROM `address_log` GROUP BY `mmo_address_id`, `mmo_customer_id` HAVING COUNT(*) > 1");
		return $query->result_array();
	}


	public function duplicataddressdeleteformmmt(){
		$query = $this->db->query("SELECT mmo_customer_id,mmo_address_id,mmt_customer_id,mmt_address_id,COUNT(*) FROM `address_log` GROUP BY `mmt_address_id`, `mmt_customer_id` HAVING COUNT(*) > 1");
		return $query->result_array();
	}
}
?>