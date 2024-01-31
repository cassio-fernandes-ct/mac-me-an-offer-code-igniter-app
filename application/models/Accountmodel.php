<?php

use Bigcommerce\Api\Client as Bigcommerce;
class Accountmodel extends CI_Model
{
	public function __construct()
	{
		$this->setting_table 	      = "setting";
		$this->category_table	      = "category";	
		$this->product_table_bc	      = "bc_product_data_store";
		$this->product_category_table = "product_category";
		$this->products               = "products";
		$this->brands                 = "brands";
		$this->quote_table            = "quote";
		$this->product_table          = "products";
		$this->product_option_table   = "product_option";
		$this->shipping_label_table   = "shipping_label";
		$this->customers_log_table    = "customers_log";
        $this->load->database();
        include(APPPATH.'third_party/bcapi/vendor/autoload.php');
        header('Access-Control-Allow-Origin: *');
        

       
	}

	public function getBcConfig()
	{
		$query = $this->db->query("SELECT * FROM ".$this->setting_table."");
		return $query->row_array();
	}

	public function getcustomeridstore($data)
	{
		
		$query = $this->db->query("SELECT * FROM ".$this->customers_log_table." WHERE bc_id_mmt = '".$data['customer_id']."' ");
		return $query->row_array();
	}

	public function getquote($data)
	{
		
		$customer_id = $data['customer_id'];
		if($data['store'] == 'mmt'){
			$customer_data = $this->getcustomeridstore($data);
			$customer_id = $customer_data['bc_id_mmo'];
		}
		
		
		$filter = '';
		if(isset($customer_id) && !empty($customer_id))
		{
			if($data['selected'] == 'All')
			{
				$filter = "WHERE (q.contact_flag = 1 OR q.contact_flag = 0) AND q.customer_id = '".$customer_id."'";
			}
			if($data['selected'] == 'Abandoned')
			{
				$filter = 	"WHERE q.contact_flag = 0 AND q.customer_id = '".$customer_id."' ";
			}
			if($data['selected'] == 'Completed')
			{
				$filter = "WHERE q.contact_flag = 1 AND q.customer_id = '".$customer_id."'";
			}

		
			$search_query = '';
			if(isset($data['search']) && !empty($data['search']))
			{
				$search_e = explode(' ',$data['search']);
				$i = 1;
				foreach($search_e as $search_e_s)
				{	
					if($i == 1){
						$search_query .= ' AND (p.product_title LIKE "%'.$search_e_s.'%" OR q.form_email_address LIKE "%'.$search_e_s.'%")';
					}else{
						$search_query .= ' AND (p.product_title LIKE "%'.$search_e_s.'%"OR q.form_email_address LIKE "%'.$search_e_s.'%")';
					}
					$i++;
				}
			}
			
			  $pagenumber = 1;
		        $offset = 0;
		        $page = $data['page'];
		        if(isset($page) && !empty($page))
		        {
		            $pagenumber = $page;   
		        }
		       
		        $offset = ($pagenumber-1)*$data['limit'];

		//echo "SELECT q.id,p.product_title,p.image,p.product_url,q.form_email_address,q.price,q.contact_flag	 FROM ".$this->product_table." as p RIGHT JOIN ".$this->quote_table." as q ON(p.bc_product_id = q.product_id) ".$filter." ".$search_query." ORDER BY q.id DESC limit ".$offset." ,".$data['limit']." ";
		//exit;

			$query = $this->db->query("SELECT q.id,p.product_title,p.image,p.product_url,q.form_email_address,q.price,q.contact_flag	 FROM ".$this->product_table." as p RIGHT JOIN ".$this->quote_table." as q ON(p.bc_product_id = q.product_id) ".$filter." ".$search_query." AND knockout = 0 ORDER BY q.id DESC limit ".$offset." ,".$data['limit']." ");
			$res['quotedata'] = $query->result_array();
			$queryone = $this->db->query("SELECT q.id,p.product_title,p.image,p.product_url,q.form_email_address,q.price,q.contact_flag	 FROM ".$this->product_table." as p RIGHT JOIN ".$this->quote_table." as q ON(p.bc_product_id = q.product_id) ".$filter." ".$search_query." AND knockout = 0");
			$res['quotetotal'] = $queryone->num_rows();

			return $res;
		}
	}

	public function viewquote($post)
	{
		$query = $this->db->query("SELECT * FROM ".$this->quote_table." WHERE id = ".$post['quoteid']."");
		$res = $query->row_array();

		$data['customerinfo'] = $res;
		$data['product'] = $this->productget($res['product_id']);
		//$data['selectedoption'] = $this->productoptionget($res['attribute'],$res['product_id']);
		$da = json_decode($res['selectedoption']);
		
		$data['selectedoption'] = @$da->option;
		$data['qustionans']     = @$da->qustionans;

		$data['shpping_label'] = $this->shpping_label($post['quoteid']);

		return $data;
	}

	public function productget($productid)
	{
		if(isset($productid) && !empty($productid))
		{
			$query = $this->db->query("SELECT bc_product_id,product_title FROM ".$this->product_table." WHERE bc_product_id = ".$productid."");
			return $query->row_array();

		}
	}


	public function productoptionget($selectedattribute,$product_id){

		$selected = json_decode($selectedattribute);
		$data = array();
		if(isset($selected) && !empty($selected) && isset($product_id) && !empty($product_id))
		{
			foreach ($selected as $key => $value) {
				
				$query = $this->db->query("SELECT 	option_set_name,option_label FROM ".$this->product_option_table." WHERE product_id = ".$product_id." AND attribut_id = ".$key." AND option_label_value_id = ".$value." ");
				$data[] = $query->row_array();

			}
		}

		return $data;
		
		//$query = $this->db->query("SELECT product_title FROM ".$this->product_option_table." WHERE bc_product_id = ".$productid."");
		//return $query->row_array();
	}

	public function shpping_label($id)
	{
		if(isset($id) && !empty($id))
		{
			$query = $this->db->query("SELECT shipping_image FROM ".$this->shipping_label_table." WHERE quote_id = ".$id."");
			return $query->row_array();
		}

	}
}
?>