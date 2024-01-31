<?php
class SerialNumberSearchBar_model extends CI_Model
{
	function __construct()
	{
		$this->serial_table = "serial";
		$this->category_table = "category";
		$this->setting_table = "setting";
		$this->serise_table = "serise";
		$this->category_product_table = "product_category";
		$this->serise_product_table = "serise_product";
		$this->product_table = "products";
		$this->load->database();
	}

	public function getBcConfig()
	{
		$query = $this->db->query("SELECT * FROM ".$this->setting_table."");
		return $query->row_array();
	}

	public function get_serial_detaill($search)
	{
		$search_query = '';
		if(isset($search) && !empty($search))
		{
			$search_e = explode(' ',$search);
			$i = 1;
			foreach($search_e as $search_e_s)
			{	
				if($i == 1){
					$search_query .= ' WHERE (pc.serial LIKE "%'. $this->db->escape_like_str($search_e_s).'%")';
				}else{
					$search_query .= ' AND (pc.serial LIKE "%'. $this->db->escape_like_str($search_e_s) .'%")';
				}
				$i++;
			}
		}
	
		$query = $this->db->query("SELECT p.bc_product_id,p.product_title,p.product_url,p.image FROM ".$this->product_table." as p JOIN ".$this->serial_table." as pc ON(p.bc_product_id = pc.bc_product_id)  ".$search_query." GROUP BY pc.bc_product_id");
		$data['product'] = $query->result_array();
		return $data;
	}

	public function get_serial_detail($search,$offset)
	{
		$search_query = '';
		if(isset($search) && !empty($search))
		{
			$search_e = explode(' ',$search);
			$i = 1;
			foreach($search_e as $search_e_s)
			{	
				if($i == 1){
					// $search_query .= ' WHERE (pc.serial LIKE "%'. $search_e_s.'%")';
					$search_query .= ' WHERE (pc.serial LIKE "%'. $this->db->escape_like_str($search_e_s).'%")';
				}else{
					// $search_query .= ' AND (pc.serial LIKE "%'.$search_e_s.'%")';
					$search_query .= ' AND (pc.serial LIKE "%'.$this->db->escape_like_str($search_e_s).'%")';
				}
				$i++;
			}
		}
		
		$query = $this->db->query("SELECT p.bc_product_id,p.product_title,p.product_url,p.image FROM ".$this->product_table." as p JOIN ".$this->serial_table." as pc ON(p.bc_product_id = pc.bc_product_id)  ".$search_query." GROUP BY pc.bc_product_id  LIMIT ".$offset.",20");
		$data['product'] = $query->result_array();
		return $data;
	}

	public function get_serial_detail_total($search)
	{
		$search_query = '';
		if(isset($search) && !empty($search))
		{
			$search_e = explode(' ',$search);
			$i = 1;
			foreach($search_e as $search_e_s)
			{	
				if($i == 1){
					$search_query .= ' WHERE (pc.serial LIKE "%'. $this->db->escape_like_str($search_e_s).'%")';
				}else{
					$search_query .= ' AND (pc.serial LIKE "%'. $this->db->escape_like_str($search_e_s).'%")';
				}
				$i++;
			}
		}
		
		$query = $this->db->query("SELECT p.bc_product_id,p.product_title,p.product_url,p.image FROM ".$this->product_table." as p JOIN ".$this->serial_table." as pc ON(p.bc_product_id = pc.bc_product_id)  ".$search_query." GROUP BY pc.bc_product_id");
		$data['product_total'] = $query->num_rows();
		return $data;
	}

	public function gethomecategory(){
		$query = $this->db->query("SELECT category_id,name,image FROM ".$this->category_table." WHERE status = 'yes' ORDER BY display_order ASC");
		$data = $query->result_array();
		return $data;
		
	}

	public function getserise($category_id){
		//$query = $this->db->query("SELECT title,id FROM ".$this->serise_table." WHERE category_id = '".$category_id."'");
		//echo "SELECT c.name,s.id FROM ".$this->category_table." as c JOIN ".$this->serise_table." as s ON(s.title = c.category_id)  where s.category_id = '".$category_id."' ORDER BY c.sort_order ASC";
		$query = $this->db->query("SELECT c.name,s.id FROM ".$this->category_table." as c JOIN ".$this->serise_table." as s ON(s.title = c.category_id)  where s.category_id = '".$category_id."' order by case when `sort_order` = ' ' then '999999' end, `sort_order` ASC");
		$data = $query->result_array();
		return $data;
	}

	public function getproduct($series_id)
	{
		
		/*$query		   =  $this->db->query("SELECT p.bc_product_id,p.product_title,p.product_url FROM ".$this->product_table." as p JOIN 
			".$this->serise_product_table." as ps ON(p.bc_product_id = ps.product_id) WHERE ps.serise_id = ".$series_id." ORDER BY p.product_title ASC");*/ 
		/*$query		   =  $this->db->query("SELECT p.bc_product_id,p.product_title,p.product_url FROM ".$this->product_table." as p JOIN 
			".$this->serise_product_table." as ps ON(p.bc_product_id = ps.product_id) WHERE ps.serise_id = ".$series_id." AND p.bc_product_status != 'inactive' ORDER BY p.product_title ASC");	*/
		$query		   =  $this->db->query("SELECT p.bc_product_id,p.product_title,p.product_url FROM ".$this->product_table." as p JOIN 
			".$this->serise_product_table." as ps ON(p.bc_product_id = ps.product_id) WHERE ps.serise_id = ".$series_id." AND p.bc_product_status != 'inactive' ORDER BY p.sort_order ASC");
		
		$result = $query->result_array();
		return $result;

		//$query = $this->db->query("SELECT title,id FROM ".$this->serise_product." WHERE serise_id = '".$series_id."'");
		//$data = $query->result_array();
		//return $data;
	}
}
	
?>