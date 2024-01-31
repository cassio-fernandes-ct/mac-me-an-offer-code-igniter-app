<?php
class Serial_model extends CI_Model
{
	function __construct()
	{
		$this->serial_table = "serial";
		$this->category_table = "category";
		$this->category_product_table = "product_category";
		$this->serise_product_table = "serise_product";
		$this->product_table = "products";
		$this->load->database();
	}

	public function pluck_categories()
	{
		$this->db->select('category_id, name');
		$this->db->order_by("name", "asc");
        $this->db->from($this->category_table);
		return $this->db->get()->result_array();
	}

	public function pluck_proucts()
	{
		$query = $this->db->query("SELECT bc_product_id,product_title FROM ".$this->product_table." ORDER BY `product_title` ");
		return $query->result_array();
	}

	public function deleteserial($title)
	{
		$this->db->delete($this->serial_table,['serial' => $title]) ;
	}


	public function get_serial_by_id($serise_id)
	{
		
		$query 	= $this->db->query("SELECT `serial` FROM ".$this->serial_table." WHERE id = '".$serise_id."' ");
		$serial = $query->row_array();
		if (isset($serial) && !empty($serial)) {
			$query 	= $this->db->query("SELECT `bc_product_id` FROM ".$this->serial_table." WHERE serial = '".$serial['serial']."' ");
			$value =  $query->result_array();
			
			$data =  array_map(function($value){
				return $value['bc_product_id'];
			}, $value);

			$query1 	= $this->db->query("SELECT `id` FROM ".$this->serial_table." WHERE serial = '".$serial['serial']."' ");
			$value1 =  $query1->result_array();

			$data1 =  array_map(function($value1){
				return $value1['id'];
			}, $value1);

			$res['id']      = $data1;
			$res['product_ids'] = $data;
			$res['serial']      = $serial['serial'];
			$res['serial_id']   = $serise_id;
			return $res;
		}
	}

	public function get_serialdata($search)
	{

		$search_query = '';
		if(isset($search) && !empty($search))
		{
			$search_e = explode(' ',$search);
			$i = 1;
			foreach($search_e as $search_e_s)
			{	
				if($i == 1){
					$search_query .= ' WHERE (serial LIKE "%'.$search_e_s.'%")';
				}else{
					$search_query .= ' AND (serial LIKE "%'.$search_e_s.'%")';
				}
				$i++;
			}
		}
		
		$query = $this->db->query("SELECT * FROM ".$this->serial_table." ".$search_query." GROUP BY `serial` ");
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
					$search_query .= ' WHERE (serial LIKE "%'.$search_e_s.'%")';
				}else{
					$search_query .= ' AND (serial LIKE "%'.$search_e_s.'%")';
				}
				$i++;
			}
		}


		$query = $this->db->query("SELECT * FROM $this->serial_table ".$search_query." GROUP BY `serial` ORDER BY id DESC limit ".$startform." ,".$limit."");
		return $query->result_array();
	}



	public function insertserial($data)
	{
		
		if(isset($data) && !empty($data))
		{
			$serial = $data['serial'];
			$products = $data['product_ids'];
			$insert = array();
			foreach ($products as $value) {
				
				$total_rows = $this->check_product_serial($serial,$value);
				if($total_rows == 0)
				{
					$insert[] = array(
						'serial' => $serial,
						'bc_product_id' => $value);
				}
			}

			$this->db->insert_batch($this->serial_table, $insert); 

		}
	   
	}

	public function check_product_serial($serial,$value)
	{
		$query = $this->db->query("SELECT * FROM $this->serial_table where `serial` = '".$serial."' AND `bc_product_id` = ".$value."");
		return $query->num_rows();
	}

	public function updateSerise($serise_id,$data,$id)
	{
		$this->db->where_in('id', $id);
		$this->db->delete($this->serial_table) ;
		$this->insertserial($data);
	}

	public function existserial($serial,$id)
	{
		
		//echo "SELECT * FROM $this->serial_table where `serial` = '".$serial."' AND id NOT IN ($id);";
		if(empty($id))
		{
			$query = $this->db->query("SELECT * FROM $this->serial_table where `serial` = '".$serial."' ");
		return $query->num_rows();
		}else{
			$query = $this->db->query("SELECT * FROM $this->serial_table where `serial` = '".$serial."' AND id NOT IN ($id);");
		return $query->num_rows();
		}
		
	}
	/*function insert_in_serise_product_table($serise_id,$product_ids)
	{
		foreach ($product_ids as $id ) {
	        $this->db->insert($this->serise_product_table,['serise_id'=>$serise_id,'product_id'=>$id]);
		}
	}*/

	public function check_should_be_unique_two_field($title,$table)
	{

		$data = explode(".",$table);
     	$table_name = $data[0];
		$this->db->where($data[1], $_POST[$data[1]]);
       	$this->db->where($data[2], $_POST[$data[2]]);
       	$result = $this->db->get($table_name);
       	if($result->num_rows() > 0)
       		return false;
       	else
       		return true;
	}


	public function get_product_by_category_id($category_id)
	{

		$this->db->select('product_id');
        $this->db->from($this->category_product_table);
        $this->db->where('category_id',$category_id);
        $query = $this->db->get();
		$product_ids=[];
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{

				$product_ids[]=$row->product_id;
			}
		}
		else
		{
			return false;
		}
		$this->db->select('bc_product_id, product_title');
        $this->db->from($this->product_table);
        $this->db->where_in('bc_product_id',$product_ids);
        $query = $this->db->get();
        $product_titles=[];

		if($query->num_rows() > 0)
		{
    	   foreach($query->result() as $row)
			{
				 $product_titles[$row->bc_product_id]=$row->product_title;
			}
			return $product_titles;
		}
		else
		{
			return false;
		}

	}

	public function get_selected_product($serise_id)
	{
		return array_map (function($value){
		    return $value['product_id'];
		} , $this->db->from($this->serise_product_table)
				->where('serise_id',$serise_id)
				->select('product_id')
				->get()
				->result_array()
		);
	}


}
	
?>