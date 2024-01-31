<?php
use Bigcommerce\Api\Client as Bigcommerce;
class Guestcustomoffermodel extends CI_Model
{
	public function __construct()
	{
		$this->setting_table 	      = "setting";
		$this->customers_log	      = "customers_log";	
		
        $this->load->database();
        include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function getBcConfig()
	{
		$query = $this->db->query("SELECT * FROM ".$this->setting_table."");
		return $query->row_array();
	}

	public function checkexistemail($email)
	{
		$email = $this->db->escape($email);
		$query = $this->db->query("SELECT bc_id_mmo FROM ".$this->customers_log." where email = ".$email."");

		return $query->row_array();
	}
}
?>