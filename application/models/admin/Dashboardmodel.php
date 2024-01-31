<?php
class Dashboardmodel extends CI_Model
{
	function __construct()
	{
		$this->category_table = "category";
		$this->serise_table = "serise";
		$this->quote_table = "quote";
	}

	function totalcategory()
	{
		$query_category = $this->db->query("SELECT * FROM ".$this->category_table."");
		return $query_category->num_rows();
	}

	function totalserise()
	{
		$query_serise = $this->db->query("SELECT * FROM ".$this->serise_table."");
		return $query_serise->num_rows();
	}

	function totalquote()
	{
		$query_quote = $this->db->query("SELECT * FROM ".$this->quote_table."");
		return $query_quote->num_rows();
	}

	function getsettingdata($id)
	{
		$query = $this->db->get_where($this->setting_table,array('id'=>$id));
		return $query->row_array();
	}
}
?>