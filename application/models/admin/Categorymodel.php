<?php
class Categorymodel extends CI_Model
{
	public function __construct()
	{
		$this->setting_table 	= "setting";
		$this->category_table	= "category";	
        $this->load->database();

	}
	
	public function update_data($ctegory_id){
		$query = $this->db->query("SELECT * FROM ".$this->category_table."  where category_id !=  '".$data['id']."' AND display_order = '".$data['val']."' ");
		return $query->num_rows();
	}

	public function update_display_order_exit($data){
		
		
		$product_datas_a = $data['dishs'];
		$project_names = array_map(function($product_datas_a) { return $product_datas_a['dish_id'] ;}, $product_datas_a );

		//echo "SELECT * FROM ".$this->category_table."  WHERE category_id NOT IN ( '" . implode( "', '" , $project_names ) . "' ) AND display_order = '".$data['val']."' ";
		//$query = $this->db->query("SELECT * FROM ".$this->category_table."  where category_id !=  '".$data['id']."' AND display_order = '".$data['val']."' ");
		
		// $query = $this->db->query("SELECT * FROM ".$this->category_table."  WHERE category_id NOT IN ( '" . implode( "', '" , $project_names ) . "' ) AND display_order = '".$data['val']."' ");

		$query = $this->db->query("SELECT * FROM ".$this->category_table."  WHERE category_id != '".$data['id']."' AND display_order = '".$data['val']."' ");

		return $query->num_rows();
	}

	public function update_display_order($display){
		foreach($display as $key => $display_order_val){
			/*if($display_order_val == '' ){
				$display_order_val = 0;
			}*/

			if(isset($display_order_val) && !empty($display_order_val) )
			{
				$query = $this->db->query("Update ".$this->category_table." SET display_order = '".$display_order_val."' where category_id = '".$key."'");
			}
		}
	}

	public function get_categorydata($search)
	{
		$search_query = '';
		if(isset($search) && !empty($search))
		{
			$search_e = explode(' ',$search);
			$i = 1;
			foreach($search_e as $search_e_s)
			{	
				if($i == 1){
					$search_query .= ' WHERE (name LIKE "%'.$search_e_s.'%")';
				}else{
					$search_query .= ' AND (name LIKE "%'.$search_e_s.'%")';
				}
				$i++;
			}
		}
		
		$query = $this->db->query("SELECT * FROM ".$this->category_table." ".$search_query." ");
		return $query->num_rows();
	}

	public function get_categorydetails($startform,$limit,$search)
	{
		
		$search_query = '';
		if(isset($search) && !empty($search))
		{
			$search_e = explode(' ',$search);
			$i = 1;
			foreach($search_e as $search_e_s)
			{	
				if($i == 1){
					$search_query .= ' WHERE (name LIKE "%'.$search_e_s.'%")';
				}else{
					$search_query .= ' AND (name LIKE "%'.$search_e_s.'%")';
				}
				$i++;
			}
		}
		//echo "SELECT category_id,name,status,image FROM `category` ".$search_query." limit ".$startform." ,".$limit."";
		$query = $this->db->query("SELECT category_id,name,status,image,display_order FROM `category` ".$search_query." ORDER BY display_order ASC limit ".$startform." ,".$limit."");
		return $query->result_array();
	}

	public function update($ctegory_id,$status)
	{
		$query = $this->db->query("update category set status = case when status='no' then 'yes' else 'no' end where category_id = '".$ctegory_id."'");
	}

	public function getBcConfig()
	{
		$query = $this->db->query("SELECT * FROM ".$this->setting_table."");
		return $query->row_array();
	}
	
	public function EmptycategoryTable()
	{
		// $this->db->empty_table('category_data');
		$this->db->empty_table('category');
	}
	
	public function importcategorydb($data_category)
	{
		if(isset($data_category) && !empty($data_category))
		{
			$update = array();
			$insert = array();
			foreach($data_category as $value)
			{
				$checkCategoryExist = $this->checkCategoryExist($value['category_id']);
			
				if(isset($checkCategoryExist) && !empty($checkCategoryExist))
				{
					if($value['sort_order'] == 0){ $sort_order = ' '; }else{$sort_order = $value['sort_order'];}
					
					$update[] =  array(
						"category_id" 		    => $value['category_id'],
						"category_data" 		=> $value['category_data'],
						"parent_id" 		    => $value['parent_id'],
						"name" 		    		=> $value['name'],
						"description" 		    => $value['description'],
						"sort_order" 		    => $sort_order,
						"page_title" 		    => $value['page_title'],
						"meta_keywords" 		=> $value['meta_keywords'],
						"layout_file" 		    => $value['layout_file'],
						"is_visible" 		    => $value['is_visible'],
						"search_keywords" 		=> $value['search_keywords'],
						"url" 		    		=> $value['url'],
						"image" 		    	=> $value['image']
					);

				}else{
					if($value['sort_order'] == 0){ $sort_order = ' '; }else{$sort_order = $value['sort_order'];}
					
					$insert[] =  array(
						"category_id" 		    => $value['category_id'],
						"category_data" 		=> $value['category_data'],
						"parent_id" 		    => $value['parent_id'],
						"name" 		    		=> $value['name'],
						"description" 		    => $value['description'],
						"sort_order" 		    => $sort_order,
						"page_title" 		    => $value['page_title'],
						"meta_keywords" 		=> $value['meta_keywords'],
						"layout_file" 		    => $value['layout_file'],
						"is_visible" 		    => $value['is_visible'],
						"search_keywords" 		=> $value['search_keywords'],
						"url" 		    		=> $value['url'],
						"image" 		    	=> $value['image']
					);
				}
			}
			
			if(isset($insert) && !empty($insert))	{
				$this->db->insert_batch($this->category_table,$insert);
			}

			if(isset($update) && !empty($update)){
				$this->db->update_batch($this->category_table,$update,'category_id'); 
			}
		}
	}

	public function checkCategoryExist($category_id)
	{
		$query_check_cat = $this->db->query("SELECT category_id FROM ".$this->category_table." WHERE category_id ='".$category_id."'");
		return $query_check_cat->num_rows();
	}			
}
?>