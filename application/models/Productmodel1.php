<?php
use Bigcommerce\Api\Client as Bigcommerce;
class Productmodel extends CI_Model
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
        $this->load->database();
        include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function getBcConfig()
	{
		$query = $this->db->query("SELECT * FROM ".$this->setting_table."");
		return $query->row_array();
	}


	public function getquotenumrow($product_id,$emailaddress)
	{
    	$query = $this->db->query("SELECT * FROM ".$this->quote_table." where product_id = '".$token."' AND form_email_address = '".$emailaddress."' AND contact_flag = 0");
		$res['totalnum'] = $query->num_rows();
		$res['quotedetails'] = $query->row_array();
    }


 	public function quotesteponeinsert($data)
	{
		if(isset($data) && !empty($data))
		{


			$row = $this->getquotenumrow($data['product_id'],$data['estimator_form_email_address']);

			if($row['totalnum'] == 0){


				$quoteinsrt = array();
				$quoteinsrt['product_id']          = $data['product_id'];
				$quoteinsrt['qty']                 = '';
				$quoteinsrt['attribute']           = json_encode($data['attribute']);
				$quoteinsrt['price']               = $data['price'];
				$quoteinsrt['token']               = $data['tokensavedetils_option'];
				$quoteinsrt['form_email_address']  = $data['estimator_form_email_address'];
				$quoteinsrt['form_first_name']     = $data['estimator_form_first_name'];
				$quoteinsrt['form_last_name']      = $data['estimator_form_last_name'];
				$quoteinsrt['form_street1']        = $data['estimator_form_street1'];
				$quoteinsrt['form_street2']        = $data['estimator_form_street2']; 
				$quoteinsrt['form_city']           = $data['estimator_form_city'];
				$quoteinsrt['form_state']          = $data['estimator_form_state']; 
				$quoteinsrt['form_zip']            = $data['estimator_form_zip'];

				$this->db->insert($this->quote_table,$quoteinsrt);

			}else{

				$quoteupdate = array();
				$quoteupdate['product_id']          = $data['product_id'];
				$quoteupdate['qty']                 = '';
				$quoteupdate['attribute']           = json_encode($data['attribute']);
				$quoteupdate['price']               = $data['price'];
				$quoteupdate['token']               = $data['tokensavedetils_option'];
				$quoteupdate['form_email_address']  = $data['estimator_form_email_address'];
				$quoteupdate['form_first_name']     = $data['estimator_form_first_name'];
				$quoteupdate['form_last_name']      = $data['estimator_form_last_name'];
				$quoteupdate['form_street1']        = $data['estimator_form_street1'];
				$quoteupdate['form_street2']        = $data['estimator_form_street2']; 
				$quoteupdate['form_city']           = $data['estimator_form_city'];
				$quoteupdate['form_state']          = $data['estimator_form_state']; 
				$quoteupdate['form_zip']            = $data['estimator_form_zip'];
				$this->db->where('token', $quoteupdate['token']);
				$this->db->update($this->quote_table,$quoteupdate);

			}

			return $data['tokensavedetils_option'];
		}


	}




	public function updatecustomerinfo($data)
	{
		if(isset($data) && !empty($data))
		{
			$quoteupdate['token']              = $data['tokensavedetils'];
			$quoteupdate['form_prev_sold']     = $data['radio'];
			$quoteupdate['receive_payment']    = $data['receive_payment'];
			$quoteupdate['form_serial_number'] = $data['estimator_form_serial_number']; 
			$quoteupdate['form_email_address'] = $data['estimator_form_email_address'];
			$quoteupdate['form_first_name']    = $data['estimator_form_first_name'];
			$quoteupdate['form_last_name']     = $data['estimator_form_last_name'];
			$quoteupdate['form_street1']       = $data['estimator_form_street1'];
			$quoteupdate['form_street2']       = $data['estimator_form_street2']; 
			$quoteupdate['form_city']          = $data['estimator_form_city'];
			$quoteupdate['form_state']         = $data['estimator_form_state']; 
			$quoteupdate['form_zip']           = $data['estimator_form_zip'];
			$quoteupdate['contact_flag']       = 1;
			
			$this->db->where('token', $quoteupdate['token']);
			$this->db->update($this->quote_table,$quoteupdate);

			$data = $this->db->from($this->quote_table)
				->where('token', $quoteupdate['token'])
				->get()
				->row_array();
			return $data['id'];
			
		}
	}

	public function random_strings($length_of_string = 50)
    {
       $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
       return substr(str_shuffle($str_result),  
                          0, $length_of_string);
    } 

    public function getquote($token){


    	$query = $this->db->query("SELECT * FROM ".$this->quote_table." where token = '".$token."'");
		$res =  $query->row_array();

		$data['customerinfo'] = $res;
		$data['product_category_name'] = $this->productget($res['product_id']);
		$data['selectedoption'] = $this->productoptionget($res['attribute'],$res['product_id']);
		$data['customerinfo'] = $res;
		return $data;
    }


    public function productget($productid)
	{
		if(isset($productid) && !empty($productid))
		{
			$query = $this->db->query("SELECT bc_product_id,product_title FROM ".$this->product_table." WHERE bc_product_id = ".$productid."");

			$data['product'] = $query->row_array();

			$product_category_query = $this->db->query("SELECT p.name FROM category as p LEFT JOIN product_category as pc ON(p.category_id = pc.category_id) WHERE pc.product_id = ".$productid." AND p.name != 'Shop' ");
			
			$category = $product_category_query->result_array();

			$data['category_name'] = $category[0]['name'];

			return $data;
			
		}
	}

	public function exceptshop($catgeory = '')
	{
		echo 'array_filter';
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
	}
}
?>