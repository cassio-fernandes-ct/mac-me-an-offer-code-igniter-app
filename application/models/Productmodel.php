<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: image/gif');

use Bigcommerce\Api\Client as Bigcommerce;

class Productmodel extends CI_Model {
	public function __construct() {
		$this->setting_table = "setting";
		$this->category_table = "category";
		$this->product_table_bc = "bc_product_data_store";
		$this->product_category_table = "product_category";
		$this->products = "products";
		$this->brands = "brands";
		$this->quote_table = "quote";
		$this->product_table = "products";
		$this->product_option_table = "product_option";
		$this->shipping_label_table = "shipping_label";
		$this->customers_log = "customers_log";
		$this->serial_table = "serial";

		$this->currentdate = date('Y-m-d H:i:s');
		$this->load->database();
		include APPPATH . 'third_party/bcapi/vendor/autoload.php';
		//header('Access-Control-Allow-Origin: *');

	}

	public function getBcConfig() {
		$query = $this->db->query("SELECT * FROM " . $this->setting_table . "");
		return $query->row_array();
	}

	public function getquotenumrow($productid, $emailaddress) {
		//echo "SELECT * FROM ".$this->quote_table." where product_id = '".$productid."' AND form_email_address = '".$emailaddress."' AND contact_flag = 0";

		$query = $this->db->query("SELECT * FROM " . $this->quote_table . " where product_id = '" . $productid . "' AND form_email_address = '" . $emailaddress . "' AND contact_flag = 0");
		$res['totalnum'] = $query->num_rows();
		$res['quotedetails'] = $query->row_array();

		return $res;
	}

	public function quotesteponeinsert($data) {

		if (isset($data) && !empty($data)) {

			$row = $this->getquotenumrow($data['product_id'], $data['estimator_form_email_address']);

			if ($row['totalnum'] == 0) {

				$qoteoption = $this->productoptiongett(json_encode($data['attribute']), $data['product_id']);

				$quoteinsrt = array();
				$quoteinsrt['product_id'] = $data['product_id'];
				$quoteinsrt['qty'] = '';
				$quoteinsrt['attribute'] = json_encode($data['attribute']);
				$quoteinsrt['price'] = $data['price'];
				$quoteinsrt['token'] = $data['tokensavedetils_option'];
				$quoteinsrt['form_email_address'] = $data['estimator_form_email_address'];
				$quoteinsrt['form_first_name'] = $data['estimator_form_first_name'];
				$quoteinsrt['form_last_name'] = $data['estimator_form_last_name'];
				$quoteinsrt['form_street1'] = $data['estimator_form_street1'];
				$quoteinsrt['form_street2'] = $data['estimator_form_street2'];
				$quoteinsrt['form_city'] = $data['estimator_form_city'];
				$quoteinsrt['form_state'] = $data['estimator_form_state'];
				$quoteinsrt['form_zip'] = $data['estimator_form_zip'];
				$quoteinsrt['customer_id'] = $data['customer_id'];

				$quoteinsrt['selectedoption'] = json_encode($qoteoption);

				//	$quoteinsrt['selectedoption']      = json_encode($this->productoptiongett(json_encode($data['attribute']),$data['product_id']));
				//knockout email not send code.
				//$d['selectedoptionn'] = $this->productoptionget(json_encode($data['attribute']),$data['product_id']);
				//$knockoutnumber	= $this->knockout($d['selectedoptionn']);

				if( isset( $qoteoption['option'] ) ) {
					$knockoutnumber = $this->knockout($qoteoption['option']);
				} else {
					$knockoutnumber = 0;
				}

				$quoteinsrt['knockout'] = $knockoutnumber;
				$quoteinsrt['knockouttotal'] = $knockoutnumber;
				//knockout email not send code.
				$quoteinsrt['created_date'] = $this->currentdate;
				$quoteinsrt['last_update_date'] = $this->currentdate;

				$this->db->insert($this->quote_table, $quoteinsrt);

			} else {
				$qoteoption = $this->productoptiongett(json_encode($data['attribute']), $data['product_id']);

				$quoteupdate = array();
				$quoteupdate['product_id'] = $data['product_id'];
				$quoteupdate['qty'] = '';
				$quoteupdate['attribute'] = json_encode($data['attribute']);
				$quoteupdate['price'] = $data['price'];
				$quoteupdate['token'] = $data['tokensavedetils_option'];
				$quoteupdate['form_email_address'] = $data['estimator_form_email_address'];
				$quoteupdate['form_first_name'] = $data['estimator_form_first_name'];
				$quoteupdate['form_last_name'] = $data['estimator_form_last_name'];
				$quoteupdate['form_street1'] = $data['estimator_form_street1'];
				$quoteupdate['form_street2'] = $data['estimator_form_street2'];
				$quoteupdate['form_city'] = $data['estimator_form_city'];
				$quoteupdate['form_state'] = $data['estimator_form_state'];
				$quoteupdate['form_zip'] = $data['estimator_form_zip'];
				$quoteupdate['selectedoption'] = json_encode($qoteoption);
				//$quoteupdate['selectedoption']      = json_encode($this->productoptiongett(json_encode($data['attribute']),$data['product_id']));
				//knockout email not send code.
				//$d['selectedoptionn'] = $this->productoptionget(json_encode($data['attribute']),$data['product_id']);
				//$quoteupdate['knockout']            = $this->knockout($d['selectedoptionn']);
				//$knockoutnumber	= $this->knockout($d['selectedoptionn']);

				if( isset( $qoteoption['option'] ) ) {
					$knockoutnumber = $this->knockout($qoteoption['option']);
				} else {
					$knockoutnumber = 0;
				}

				$quoteupdate['knockout'] = $knockoutnumber;
				$quoteupdate['knockouttotal'] = $knockoutnumber;

				//knockout email not send code.
				$quoteupdate['last_update_date'] = $this->currentdate;
				$this->db->where('id', $row['quotedetails']['id']);
				$this->db->update($this->quote_table, $quoteupdate);

			}

			return $data['tokensavedetils_option'];
		}

	}


	function productoptiongett($selectedattribute, $product_id) {

		$selected = json_decode($selectedattribute);

		$data = array();

		if (isset($selected) && !empty($selected) && isset($product_id) && !empty($product_id)) {
			foreach ($selected as $key => $value) {

				if (isset($key) && !empty($key) && isset($value) && !empty($value) && is_numeric($value)) {

					$sql = "SELECT 	option_set_name,option_label FROM " . $this->product_option_table . " WHERE product_id = " . $product_id . " AND attribut_id = " . $key . " AND option_label_value_id = " . $value . " ";

					$query = $this->db->query("SELECT 	option_set_name,option_label FROM " . $this->product_option_table . " WHERE product_id = " . $product_id . " AND attribut_id = " . $key . " AND option_label_value_id = " . $value . " ");
					$d = $query->row_array();

					if (isset($d) && !empty($d)) {
						$data['option'][] = $d;
					} else {
						$queryy = $this->db->query("SELECT option_set_name FROM " . $this->product_option_table . " WHERE product_id = " . $product_id . " AND attribut_id = " . $key . "");
						$dd = $queryy->row_array();

						if (isset($value) && !empty($value) && isset($dd['option_set_name']) && !empty($dd['option_set_name'])) {
							$data['qustionans'][] = array('option_set_name' => $dd['option_set_name'],
								'option_label' => $value,
							);
						}
					}
				} else {

					$queryy = $this->db->query("SELECT option_set_name FROM " . $this->product_option_table . " WHERE product_id = " . $product_id . " AND attribut_id = " . $key . "");
					$dd = $queryy->row_array();

					if (isset($value) && !empty($value)) {
						$data['qustionans'][] = array('option_set_name' => $dd['option_set_name'],
							'option_label' => $value,
						);
					}
				}
			}
		}

		return $data;
	}


	public function updatecustomerinfo($data) {
		//	echo "hllo";

		if (isset($data) && !empty($data)) {
			//echo "hllo.1";
			//****** CO 1006: Guest "Checkout" start ********//

			//****** CO 1006: Guest "Checkout" end ********//

			$chkno = '100000';
			// if($data['receive_payment']=='Check – US First Class Mail (free)'){ 
			$query = $this->db->query("SELECT MAX(chkno) as chkno FROM ".$this->quote_table.""); 
			if($query->num_rows()>0){ 
				$row_data = $query->row_array();
				if($row_data['chkno']!=''&&$row_data['chkno']>0){  
					$chkno = $row_data['chkno']+1; 
				}
			} 
			// }else{ $chkno = '0'; } 

			$quoteupdate['token'] = $data['tokensavedetils'];
			//$quoteupdate['form_prev_sold']     = $data['radio'];
			$quoteupdate['form_prev_sold'] = '';
			$quoteupdate['receive_payment'] = $data['receive_payment'];
			$quoteupdate['form_serial_number'] = $data['estimator_form_serial_number'];
			$quoteupdate['form_email_address'] = $data['estimator_form_email_address'];
			$quoteupdate['form_first_name'] = $data['estimator_form_first_name'];
			$quoteupdate['form_last_name'] = $data['estimator_form_last_name'];
			$quoteupdate['form_street1'] = $data['estimator_form_street1'];
			$quoteupdate['form_street2'] = $data['estimator_form_street2'];
			$quoteupdate['form_city'] = $data['estimator_form_city'];
			$quoteupdate['form_state'] = $data['estimator_form_state'];
			$quoteupdate['form_zip'] = $data['estimator_form_zip'];
			$quoteupdate['form_phone_number'] = $data['estimator_form_phone_number'];
			$quoteupdate['chkno'] = $chkno;
			$quoteupdate['contact_flag'] = 1;
                        $quoteupdate['insurance'] = (isset($data['estimator_form_insurance']) && $data['estimator_form_insurance'] === 'Yes') ? 1 : 0;
			$quoteupdate['last_update_date'] = $this->currentdate; 

			$dataa = $this->db->from($this->quote_table)
				->where('token', $quoteupdate['token'])
				->get()
				->row_array();

			if($quoteupdate['insurance'] === 1) {
				$query = $this->db->query("SELECT price FROM " . $this->quote_table . " WHERE token = '" . addslashes($data['tokensavedetils']) . "'");
				if($query->num_rows() > 0) {
					$row_data = $query->row_array();
					if($row_data['price'] != '') {
						$new_price = '$' . number_format(preg_replace('/[^0-9\.]/', '', $row_data['price']) - 10, 2);
						$quoteupdate['price'] = $new_price;
					}
				}
			}

			$geuestusercreate = $this->geuestusercreate($data, $dataa);
			if (isset($geuestusercreate['id']) && !empty($geuestusercreate['id'])) {
				$quoteupdate['customer_id'] = $geuestusercreate['id'];
			}
			$this->db->where('token', $quoteupdate['token']);
			$this->db->update($this->quote_table, $quoteupdate);

			return $dataa['id'];

		}
	}

	//****** CO 1006: Guest "Checkout" start ********//
	public function geuestusercreate($postdata, $databasedata) {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		if (isset($databasedata['customer_id']) && !empty($databasedata['customer_id']) && $databasedata['customer_id'] != 'undefined') {
		} else {
			//$this->load->module('guestcustomoffermodel');
			//$c_id = $this->guestcustomoffermodel->checkexistemail($postdata['estimator_form_email_address']);
			//echo "<pre>";
			//print_r($c_id);
			//exit;
			$c_id = $this->checkexistemail($postdata['estimator_form_email_address']);

			if (isset($c_id['bc_id_mmo']) && !empty($c_id['bc_id_mmo'])) {
				$createdustomerid = $c_id['bc_id_mmo'];

			} else {
				$data['customercreate']['first_name'] = $postdata['estimator_form_first_name'];
				$data['customercreate']['last_name'] = $postdata['estimator_form_last_name'];
				$data['customercreate']['email'] = $postdata['estimator_form_email_address'];

				$createdustomerid = $this->createcustomer_into_macmeoffer($data);

				if (isset($createdustomerid['id']) && !empty($createdustomerid['id']) && !isset($createdustomerid['error']) && empty($createdustomerid['error'])) {

					$this->load->model('Csc_model');
					$state_data = $this->Csc_model->get_state_data($postdata['estimator_form_state']);

					$data['customeraddress']['first_name'] = $postdata['estimator_form_first_name'];
					$data['customeraddress']['last_name'] = $postdata['estimator_form_last_name'];
					$data['customeraddress']['company'] = '  ';
					$data['customeraddress']['street_1'] = $postdata['estimator_form_street1'];
					$data['customeraddress']['street_2'] = $postdata['estimator_form_street2'];
					$data['customeraddress']['city'] = $postdata['estimator_form_city'];
					$data['customeraddress']['state'] = $postdata['estimator_form_state'];
					$data['customeraddress']['zip'] = $postdata['estimator_form_zip'];
					$data['customeraddress']['country'] = $state_data['country_name'];
					$data['customeraddress']['phone'] = $postdata['estimator_form_phone_number'];

					$customer_data = Bigcommerce::createCustomeraddress($createdustomerid['id'], $data['customeraddress']);

					$address['address_id'] = $customer_data->id;
					$address['customer_id'] = $customer_data->customer_id;
				}
			}
			return $createdustomerid;
		}
	}

	public function checkexistemail($email) {

		$query = $this->db->query("SELECT bc_id_mmo FROM " . $this->customers_log . " where email = '" . $email . "'");
		return $query->row_array();
	}
	public function createcustomer_into_macmeoffer($data) {

		$storecustomer = array();
		$config_data = $this->getBcConfig();

		$bcstoreurl = $config_data['storeurl'];
		$client_id = $config_data['client_id'];
		//$store_hash		= 'z7godtn57o';
		$store_hash = $config_data['storehas'];
		$auth_token = $config_data['apitoken'];

		Bigcommerce::configure(array('client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash));
		Bigcommerce::verifyPeer(false);
		Bigcommerce::failOnError();

		try {

			$createdustomerid = Bigcommerce::createCustomer($data['customercreate']);
			$id = $createdustomerid->id;
			$storecustomer['id'] = $id;
		}

		//catch exception
		 catch (Exception $e) {

			$error = 'Message: ' . $e->getMessage();
			$storecustomer['error'] = $error;

			$createdustomerid = Bigcommerce::getCustomers(array('email' => $data['customercreate']['email']));
			if (isset($createdustomerid) && !empty($createdustomerid)) {
				$storecustomer['id'] = $createdustomerid{0}->id;
			}

		}

		return $storecustomer;

	}
	//****** CO 1006: Guest "Checkout" end ********//
	public function random_strings($length_of_string = 50) {
		$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		return substr(str_shuffle($str_result),
			0, $length_of_string);
	}

	public function getquote($token) {

		$query = $this->db->query("SELECT * FROM " . $this->quote_table . " where token = '" . $token . "'");
		$res = $query->row_array();

		$data['customerinfo'] = $res;
		$data['product_category_name'] = $this->productget($res['product_id']);
		//$data['selectedoption'] = $this->productoptionget($res['attribute'],$res['product_id']);
		$data['customerinfo'] = $res;
		return $data;
	}

	public function checkKnowoutNum($token) {
		$query = $this->db->query("SELECT knockout ,customer_id FROM " . $this->quote_table . " where token = '" . $token . "'");
		return $query->row_array();
	}

	public function productget($productid) {
		if (isset($productid) && !empty($productid)) {
			$query = $this->db->query("SELECT bc_product_id,product_title FROM " . $this->product_table . " WHERE bc_product_id = " . $productid . "");

			$data['product'] = $query->row_array();

			$product_category_query = $this->db->query("SELECT p.name FROM category as p LEFT JOIN product_category as pc ON(p.category_id = pc.category_id) WHERE pc.product_id = " . $productid . " AND p.name != 'Shop' ");

			$category = $product_category_query->result_array();

			if( isset( $category[0] ) && isset( $category[0]['name'] ) ) {
				$data['category_name'] = $category[0]['name'];
			} else {
				$data['category_name'] = '(unknown)';
			}

			return $data;

		}
	}

	public function exceptshop($catgeory = '') {
		echo 'array_filter';
	}

	public function productoptionget($selectedattribute, $product_id) {

		$selected = json_decode($selectedattribute);
		$data = array();
		if (isset($selected) && !empty($selected) && isset($product_id) && !empty($product_id)) {
			foreach ($selected as $key => $value) {

				if (isset($value) && !empty($value) && is_numeric($value)) {
					$query = $this->db->query("SELECT 	option_set_name,option_label FROM " . $this->product_option_table . " WHERE product_id = " . $product_id . " AND attribut_id = " . $key . " AND option_label_value_id = " . $value . " ");
					$data[] = $query->row_array();
				}

			}
		}

		return $data;
	}

	public function thankyoupagedisplay($token, $customer_id) {

		if ($customer_id == 'undefined' || $customer_id == '' || $customer_id == 'false') {
			$query = $this->db->query("SELECT * FROM " . $this->quote_table . " where token = '" . $token . "'");
		} else {
			$query = $this->db->query("SELECT * FROM " . $this->quote_table . " where token = '" . $token . "' and customer_id = '" . $customer_id . "'");
		}

		$res = $query->row_array();
		if (isset($res) && !empty($res)) {
			$html = '';

			$to_time = strtotime($this->currentdate);
			$from_time = strtotime($res['last_update_date']);
			$minutecount = round(abs($to_time - $from_time) / 60, 2);

			if ($minutecount <= 30) {
				$data['customerinfo'] = $res;
				$data['product'] = $this->productget($res['product_id']);
				$data['selectedoption'] = $this->productoptionget($res['attribute'], $res['product_id']);
				$shpping_label = $this->shpping_label($res['id']);
				$data['knockoutvalue'] = $this->knockout($data['selectedoption']);

				if (isset($shpping_label['shipping_image']) & !empty($shpping_label['shipping_image'])) {
					//onclick="imagedownload(\''.$url.'\')";
					header('Access-Control-Allow-Origin: *');
					$url = base_url() . 'application/uploads/ups/shipping/' . $shpping_label['shipping_image'];
					$download_img = $this->ConverImage($url);
					$html .= '<p class="printbutton_uptext">Please download or print the FedEx shipping label below in order to ship your device to the mac me an offfer team.</p>';
					$html .= '<a class = "button" download="' . $shpping_label['shipping_image'] . '" href = "' . $download_img . '">Download shipping label</a>';
					$html .= '<a class = "button" onclick="printImg(\'' . $url . '\')" >Print shipping label</a>';

				}
				$data['shpping_label'] = $html;
			} else {

				$data['expiremessage'] = 'The Thank you page link is expired. Please check your email for shipping label.';
			}

		} else {

			$data['zerorecord'] = '0';
		}

		return $data;
	}

	function ConverImage($src) {
		$im = file_get_contents($src);
		return $imdata = "data:image/jpg;base64," . base64_encode($im);
	}

	public function knockout($array) {
		$knockoutvalue = array_map(function ($array) {return $array['option_label'];}, $array);

		$input = preg_quote('knockout', '~');
		$result = preg_grep('~' . $input . '~', $knockoutvalue);

		return count($result);
	}

	public function shpping_label($id) {
		if (isset($id) && !empty($id)) {
			$query = $this->db->query("SELECT shipping_image FROM " . $this->shipping_label_table . " WHERE quote_id = " . $id . "");
			return $query->row_array();
		}

	}

	public function logintommo($customer, $url) {

		if (isset($customer) && !empty($customer)) {
			$config_data = $this->getBcConfig();
			$bcstoreurl = $config_data['storeurl'];
			$client_id = $config_data['client_id'];
			$store_hash = $config_data['storehas'];
			$auth_token = $config_data['apitoken'];
			$client_secret = $config_data['client_secret'];

			Bigcommerce::configure(array('client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash, 'client_secret' => $client_secret)); // Bc class connection
			Bigcommerce::verifyPeer(false);
			Bigcommerce::failOnError();

			//$customer = $checkcustomerexitornot['bc_id_mmo'];
			$mmttoken = Bigcommerce::getCustomerLoginToken($customer, $url);
			$config_data = $this->getBcConfig();

			$url = $config_data['mmo_url'] . '/login/token/' . $mmttoken;
			$res['suc'] = $url;
		} else {
			$res['error'] = 'customer id not found.';
		}

		return $res;
	}

	// get serial number by propduct id
	public function serial_by_product_id($product_id) {
		$serial_qry = $this->db->query("SELECT `serial` FROM " . $this->serial_table . " WHERE bc_product_id='" . $product_id . "' ");
		return $serial_qry->result_array();

	}

        public function get_product_details_for_quote_tool($product_id) {
            $query = $this->db->query(
                "select p.bc_product_id product_id, p.product_title name, p.product_url, s.id series_id, sc.name series_name, c.category_id, c.name category_name from products p left join serise_product sp on p.bc_product_id = sp.product_id left join serise s on sp.serise_id = s.id left join category sc on s.title = sc.category_id left join category c on s.category_id = c.category_id where sc.name is not null and p.bc_product_id = " . $product_id . ";"
            );
            return $query->result_array();
        }

}
?>
