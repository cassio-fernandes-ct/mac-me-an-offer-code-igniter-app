<?php
class Serise_model extends CI_Model
{
	function __construct()
	{
		$this->serise_table = "serise";
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
		$this->db->where('parent_id','0');
        $this->db->from($this->category_table);
		return $this->db->get()->result_array();
	}



	public function deleteSerise($serise_id)
	{
		$this->db->delete($this->serise_product_table, ['serise_id' => $serise_id]) ;
		$this->db->delete($this->serise_table, ['id' => $serise_id]) ;
	}


	public function get_serise_by_id($serise_id)
	{
		//$data = $this->db->from($this->serise_table)->where('id',$serise_id)->get()->row_array();

		$query = $this->db->query("SELECT id,category_id,title FROM ".$this->serise_table." where id = ".$serise_id."");
		$data = $query->row_array();
		$product = $this->get_product_by_category_id($data['title']);
		$selected = $this->get_selected_product($data['id']);
		$subcategory = $this->get_subcategorys_id($data['category_id']);
		
		$result['data'] = $data;
		$result['products'] = $product;
		$result['productselected'] = $selected;
		$result['subcategory'] = $subcategory;
		
		return $result;
	}

	public function get_serisedata($search)
	{

		$search_query = '';
		if(isset($search) && !empty($search))
		{
			$search_e = explode(' ',$search);
			$i = 1;
			foreach($search_e as $search_e_s)
			{	
				if($i == 1){
					$search_query .= ' WHERE (title LIKE "%'.$search_e_s.'%")';
				}else{
					$search_query .= ' AND (title LIKE "%'.$search_e_s.'%")';
				}
				$i++;
			}
		}
		
		$query = $this->db->query("SELECT * FROM ".$this->serise_table." ".$search_query." ");
		return $query->num_rows();

	}



	public function get_serise_details($startform,$limit,$search)
	{
		
		$search_query = '';
		if(isset($search) && !empty($search))
		{
			$search_e = explode(' ',$search);
			$i = 1;
			foreach($search_e as $search_e_s)
			{	
				if($i == 1){
					$search_query .= ' WHERE (s.title LIKE "%'.$search_e_s.'%" OR c.name LIKE "%'.$search_e_s.'%" )';
				}else{
					$search_query .= ' AND (s.title LIKE "%'.$search_e_s.'%" OR c.name LIKE "%'.$search_e_s.'%")';
				}
				$i++;
			}
		}
	
		$query = $this->db->query("SELECT s.id,s.category_id,s.title,c.name FROM ".$this->serise_table." as s LEFT JOIN ".$this->category_table." as c ON(s.title = c.category_id) ".$search_query." ORDER BY id DESC limit ".$startform." ,".$limit."");

		//$query = $this->db->query("SELECT id,category_id,title FROM $this->serise_table ".$search_query." ORDER BY id DESC limit ".$startform." ,".$limit."");

		$data = $query->result_array();
		/*$r = array();
		foreach ($data as  $value) {

			$r[$value['id']][] = $value['name'];
			# code...
		}*/
		return $data;
	}



	public function insertSerise($seriseData,$product_ids)
	{

	    $this->db->insert($this->serise_table, $seriseData);
	    $serise_id = $this->db->insert_id();
		$this->insert_in_serise_product_table($serise_id,$product_ids);

	}

	public function updateSerise($serise_id,$product_ids,$postdaata)
	{
		$this->db->where('id',$serise_id);
		$this->db->update($this->serise_table,$postdaata);

		$this->db->delete($this->serise_product_table, ['serise_id' => $serise_id]);
		$this->insert_in_serise_product_table($serise_id,$product_ids);
	}

	function insert_in_serise_product_table($serise_id,$product_ids)
	{
		foreach ($product_ids as $id ) {
	        $this->db->insert($this->serise_product_table,['serise_id'=>$serise_id,'product_id'=>$id]);
		}
	}

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

	public function check_should_be_unique_two_field_update($title,$table)
	{
		$data = explode(".",$table);
		
		$id = $this->uri->segment(4);

		$data = explode(".",$table);
     	$table_name = $data[0];
		$this->db->where($data[1], $_POST[$data[1]]);
       	$this->db->where($data[2], $_POST[$data[2]]);
       	$this->db->where('id !=',$id);
       	$result = $this->db->get($table_name);
       
       	if($result->num_rows() > 0)
       		return false;
       	else
       		return true;
	}

	public function get_subcategorys_id($category_id)
	{

		$query = $this->db->query("SELECT id,name,category_id FROM ".$this->category_table." where  parent_id = ".$category_id."");

		if($query->num_rows() > 0)
		{
			$category_name[] ='Select series';
    	   foreach($query->result_array() as $row)
			{
				 $category_name[$row['category_id']]=$row['name'];
			}
			return $category_name;
		}
		else
		{
			return false;
		}
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