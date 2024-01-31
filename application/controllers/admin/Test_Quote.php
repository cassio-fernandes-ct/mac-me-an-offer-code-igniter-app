<?php
class Test_Quote extends CI_controller {

	public function __construct() {
		parent::__construct();
		$session_data = $this->session->userdata('admin_session');
		if (!isset($session_data) || empty($session_data)) {
			redirect('admin/login');
		}

		$this->load->library('upload');
		$this->load->library('image_lib');
		$this->load->model("admin/Test_Quotemodel");
		$this->load->library('email');

	}

	public function index() { 

		$this->data["title"] = 'Quotes';
		$this->data["page_head"] = 'Quotes';

		$this->load->view('admin/common/leftmenu', $this->data);
		$this->load->view('admin/common/header');
		$this->load->view('admin/quote_test/list');
		$this->load->view('admin/common/footer');
	}

	public function dataajax() {
		$date_type = '';
		if (isset($_REQUEST['query']['date_obj']['selected_type']) && !empty($_REQUEST['query']['date_obj']['selected_type'])) {
			$date_type = $_REQUEST['query']['date_obj']['selected_type'];
		}

		$startdate = '';
		if (isset($_REQUEST['query']['date_obj']['startdate']) && !empty($_REQUEST['query']['date_obj']['startdate'])) {
			$startdate = $_REQUEST['query']['date_obj']['startdate'];
		}

		$enddate = '';
		if (isset($_REQUEST['query']['date_obj']['enddate']) && !empty($_REQUEST['query']['date_obj']['enddate'])) {
			$enddate = $_REQUEST['query']['date_obj']['enddate'];
		}

		$searchtext = '';
		if (isset($_REQUEST['query']['search_text']) && !empty($_REQUEST['query']['search_text'])) {
			$searchtext = $_REQUEST['query']['search_text'];
		}

		$searchdropdown = 'all';
		if (isset($_REQUEST['query']['Status']) && !empty($_REQUEST['query']['Status'])) {
			$searchdropdown = $_REQUEST['query']['Status'];
		}

		$payment_method = 'all';
		if (isset($_REQUEST['query']['payment_method']) && !empty($_REQUEST['query']['payment_method'])) {
			$payment_method = $_REQUEST['query']['payment_method'];
		}

		$payment_status = 'all';
		if (isset($_REQUEST['query']['payment_status']) && $_REQUEST['query']['payment_status'] != '') {
			$payment_status = $_REQUEST['query']['payment_status'];
		}

		$totalcategory = $this->Test_Quotemodel->quotetotal($searchtext, $searchdropdown, $startdate, $enddate, $payment_method, $payment_status, $date_type);

		$records['meta']['total'] = $totalcategory;

		if (isset($_REQUEST['pagination']) && !empty($_REQUEST['pagination'])) {
			$records['meta']['sort'] = $_REQUEST['sort']['sort'];
		}
		$records['meta']['page'] = 1;
		if (isset($_REQUEST['pagination']['page']) && !empty($_REQUEST['pagination']['page'])) {
			$records['meta']['page'] = $_REQUEST['pagination']['page'];
		}

		$records['meta']['perpage'] = 10;
		if (isset($_REQUEST['pagination']['perpage']) && !empty($_REQUEST['pagination']['perpage'])) {
			$records['meta']['perpage'] = $_REQUEST['pagination']['perpage'];
		}

		$records['meta']['pages'] = ceil($records['meta']['total'] / $records['meta']['perpage']);

		if ($records['meta']['pages'] < $records['meta']['page']) {
			$start_from = 0;
			$totalcategory = $this->Test_Quotemodel->get_quote_details($start_from, $records['meta']['perpage'], $searchtext, $searchdropdown, $startdate, $enddate, $payment_method, $payment_status, $date_type);
		} else {
			$start_from = ($records['meta']['page'] - 1) * $records['meta']['perpage'];
			$totalcategory = $this->Test_Quotemodel->get_quote_details($start_from, $records['meta']['perpage'], $searchtext, $searchdropdown, $startdate, $enddate, $payment_method, $payment_status, $date_type);
		}

		//$records['data'] = '';

		if (isset($totalcategory) && !empty($totalcategory)) {
			$i = 0;
			foreach ($totalcategory as $value) {

				if ($value['contact_flag'] == 0) {
					$status = '<span><span class="kt-badge  kt-badge--primary kt-badge--inline kt-badge--pill">Abandoned</span></span>';
				} elseif ($value['knockout'] > 0) {
					$status = '<span style="width: 114px;"><span class="kt-badge kt-badge--brand kt-badge--inline kt-badge--pill">Pending Knockout Quote</span></span>';
				} else {
					$status = '<span><span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">Completed</span></span>';
				}

				if ($value['payment_status'] == 1) {
					$payment_status_html = '<span><span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill ' . $value['id'] . '" onclick="change_payment_status(' . $value['id'] . ',0)" style="cursor: pointer;">Paid</span></span>';
				} else {
					$payment_status_html = '<span style="width: 114px;"><span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill ' . $value['id'] . '" onclick="change_payment_status(' . $value['id'] . ',1)" style="cursor: pointer;">Pend Pay</span></span>';
				}

				$records['data'][$i]['id'] = $value['id'];
				$records['data'][$i]['productname'] = $value['product_title'];
				$records['data'][$i]['email'] = $value['form_email_address'];
				$records['data'][$i]['price'] = $value['price'];
				$records['data'][$i]['status'] = $status;
				$records['data'][$i]['payment_status'] = $payment_status_html;
				$records['data'][$i]['action'] = '<button type="button" class="btn btn-outline-info btn-elevate btn-pill" onclick="update_quote(\'' . $value["id"] . '\')">' .
					'<i class="fab fa-vimeo"></i>View</button><button type="button" class="btn btn-outline-info btn-elevate btn-pill" onclick="export_quote(\'' . $value["id"] . '\')" >' .
					'<i class="fab fa-vimeo"></i>Export</button>';
				$i++;
			}
		} else {
			$records['data'][0]['productname'] = 'No recode found.';
		}
		echo json_encode($records);
	}

	public function View($title) {  
		$this->data["title"] = 'View Quotes';
		$id = $this->uri->segment(4);
		$this->data['getquotedetails'] = $this->Test_Quotemodel->getquotedetails($id);

		$this->load->view('admin/common/leftmenu', $this->data);
		$this->load->view('admin/common/header');
		$this->load->view('admin/quote_test/create', $this->data);
		$this->load->view('admin/common/footer');
	}

	public function emailsend() {

		$this->data["title"] = 'View Quotes';
		$id = $this->uri->segment(4);
		$getquotedetails = $this->Test_Quotemodel->getquotedetails($id);
		if (isset($getquotedetails) && !empty($getquotedetails)) {
			$this->emailtem($getquotedetails, $id);
		}

	}

	public function emailtem($tokenupdate, $id) {
		// $emailtemplete_tmp = $this->Test_Quotemodel->quoteEmailTemplate();
		$emailtemplete = $this->Test_Quotemodel->getBcConfig();
		$option = '';

		// device serial number
		$serial_no = '';
		if (isset($tokenupdate['customerinfo']['form_serial_number']) && !empty($tokenupdate['customerinfo']['form_serial_number'])) {
			$serial_no = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Serial Number: </b> ' . $tokenupdate['customerinfo']['form_serial_number'] . '</p>';
		}
		// offered price & default price
		$offered_price = '';
		$exp_offeredprice = explode("$", $tokenupdate['customerinfo']['offered_price']);
		$exp_price = explode("$", $tokenupdate['customerinfo']['price']);

		if (isset($tokenupdate['customerinfo']['offered_price']) && !empty($tokenupdate['customerinfo']['offered_price']) && $exp_offeredprice[1] > 0) {
			$offered_price = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Offered Price: </b> ' . $tokenupdate['customerinfo']['offered_price'] . '</p>';
		} else if (isset($tokenupdate['customerinfo']['price']) && !empty($tokenupdate['customerinfo']['price']) && $exp_price[1] > 0) {
			$offered_price = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Price: </b> ' . $tokenupdate['customerinfo']['price'] . '</p>';
		}
		// ================================

		$htmlConten = str_replace(array('{{name}}',
			'{{category_name}}',
			'{{product_name}}',
			'{{serial_number}}',
			'{{price}}',
			'{{options}}',
			'{{label_image}}', 'Category Name:', '{{PO}}'),
			array($tokenupdate['customerinfo']['form_first_name'] . ' ' . $tokenupdate['customerinfo']['form_last_name'],
				'',
				$tokenupdate['product']['product_title'],
				$serial_no,
				$offered_price,
				'',
				'',
				'',
				$id,
			)
			, $emailtemplete['email_template']);
		// , $emailtemplete_tmp);

		$shipping_label_file_name = '';
		if (isset($tokenupdate['shpping_label']['shipping_image']) && !empty($tokenupdate['shpping_label']['shipping_image'])) {
			$shipping_label_file_name = $tokenupdate['shpping_label']['shipping_image'];
		}
		$config['protocol'] = $emailtemplete['protocol'];
		$config['smtp_user'] = $emailtemplete['smtp_user'];
		$config['smtp_port'] = $emailtemplete['smtp_port'];
		$config['smtp_host'] = $emailtemplete['smtp_host'];
		$config['smtp_pass'] = $emailtemplete['smtp_pass'];
		$config['smtp_crypto'] = 'ssl';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = true;
		$config['crlf'] = "\n";

		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");
		// $this->email->from('tradein@macofalltrades.com',"Mac Me An Offer");
		$this->email->from($emailtemplete['smtp_user'], "Mac Me An Offer");
		//$this->email->to($tokenupdate['customerinfo']['form_email_address']);
		$this->email->to('development.qatesting@gmail.com');

		//$this->email->reply_to($emailtemplete['admin_email']);
		$this->email->reply_to($emailtemplete['smtp_user']);
		$this->email->subject('Your Estimate is Approved!');
		$this->email->message($htmlConten);
		if (isset($shipping_label_file_name) && !empty($shipping_label_file_name)) {
			$url = base_url() . '/application/uploads/ups/shipping/' . $shipping_label_file_name;
			$this->email->attach($url);
			$pdfurl = base_url() . '/application/uploads/pdf/MMAO_Packing_Instructions.pdf';
			$this->email->attach($pdfurl);
		}

		if ($this->email->send()) {
			//echo 'Your Email has successfully been sent.';
			$emailtemplete = $this->Test_Quotemodel->updateknockout($id);
			$this->session->set_userdata('updatedata', '1');
			redirect('admin/Test_Quote/view/' . $id);
		} else {
			show_error($this->email->print_debugger());
		}
	}

	public function emailtem_bkp($tokenupdate, $id) {
		$emailtemplete = $this->Test_Quotemodel->getBcConfig();

		$option = '';

		$htmlConten = str_replace(array('{{name}}',
			'{{category_name}}',
			'{{product_name}}',
			'{{options}}',
			'{{label_image}}', 'Category Name:', '{{PO}}'),
			array($tokenupdate['customerinfo']['form_first_name'] . ' ' . $tokenupdate['customerinfo']['form_last_name'],
				'',
				$tokenupdate['product']['product_title'],
				'',
				'',
				'',
				$id,
			)
			, $emailtemplete['email_template']);

		$shipping_label_file_name = '';
		if (isset($tokenupdate['shpping_label']['shipping_image']) && !empty($tokenupdate['shpping_label']['shipping_image'])) {
			$shipping_label_file_name = $tokenupdate['shpping_label']['shipping_image'];
		}
		$config['protocol'] = $emailtemplete['protocol'];
		$config['smtp_user'] = $emailtemplete['smtp_user'];
		$config['smtp_port'] = $emailtemplete['smtp_port'];
		$config['smtp_host'] = $emailtemplete['smtp_host'];
		$config['smtp_pass'] = $emailtemplete['smtp_pass'];
		$config['smtp_crypto'] = 'ssl';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = true;

		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");
		// $this->email->from('tradein@macofalltrades.com',"Mac Me An Offer");
		$this->email->from($emailtemplete['smtp_user'], "Mac Me An Offer");
		$this->email->to($tokenupdate['customerinfo']['form_email_address']);
		//$this->email->reply_to($emailtemplete['admin_email']);
		$this->email->reply_to($emailtemplete['smtp_user']);
		$this->email->subject('Your Estimate is Approved!');
		$this->email->message($htmlConten);
		if (isset($shipping_label_file_name) && !empty($shipping_label_file_name)) {
			$url = base_url() . '/application/uploads/ups/shipping/' . $shipping_label_file_name;
			$this->email->attach($url);
			$pdfurl = base_url() . '/application/uploads/pdf/MMAO_Packing_Instructions.pdf';
			$this->email->attach($pdfurl);
		}
		//$this->email->send();

		if ($this->email->send()) {
			//echo 'Your Email has successfully been sent.';
			$emailtemplete = $this->Test_Quotemodel->updateknockout($id);
			$this->session->set_userdata('updatedata', '1');
			redirect('admin/quote/view/' . $id);
		} else {
			show_error($this->email->print_debugger());
		}
	}

	public function emailtem_29122020($tokenupdate, $id) {
		// $id = '101468';
		$tokenupdate = $this->Test_Quotemodel->getquotedetails($id);
		$emailtemplete_tmp = $this->Test_Quotemodel->quoteEmailTemplate();
		$emailtemplete = $this->Test_Quotemodel->getBcConfig();
		$option = '';

		// =================================
		// $serial_data =  $this->Test_Quotemodel->serial_by_product_id($tokenupdate['product']['bc_product_id']);
		// $ser_data =  array_map(function($serial_data){
		//         return $serial_data['serial'];
		//     }, $serial_data);
		// $serial_title = implode(',', $ser_data);
		// $serial_no = '';
		// if(!empty($serial_title)&&$serial_title!=''){
		//     $serial_no = $serial_title;
		// }

		// device serial number
		$serial_no = '';
		if (isset($tokenupdate['customerinfo']['form_serial_number']) && !empty($tokenupdate['customerinfo']['form_serial_number'])) {
			$serial_no = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Serial Number: </b> ' . $tokenupdate['customerinfo']['form_serial_number'] . '</p>';
		}
		// offered price
		$offered_price = '';
		$exp_offeredprice = explode("$", $tokenupdate['customerinfo']['offered_price']);
		if (isset($tokenupdate['customerinfo']['offered_price']) && !empty($tokenupdate['customerinfo']['offered_price']) && $exp_offeredprice[1] > 0) {
			$offered_price = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Offered Price: </b> ' . $tokenupdate['customerinfo']['offered_price'] . '</p>';
		}

		// ================================

		$htmlConten = str_replace(array('{{name}}',
			'{{category_name}}',
			'{{product_name}}',
			'{{serial_number}}',
			'{{price}}',
			'{{options}}',
			'{{label_image}}', 'Category Name:', '{{PO}}'),
			array($tokenupdate['customerinfo']['form_first_name'] . ' ' . $tokenupdate['customerinfo']['form_last_name'],
				'',
				$tokenupdate['product']['product_title'],
				$serial_no,
				$offered_price,
				'',
				'',
				'',
				$id,
			)
			// , $emailtemplete_tmp);
			, $emailtemplete['admin_email_template']);

		$shipping_label_file_name = '';
		if (isset($tokenupdate['shpping_label']['shipping_image']) && !empty($tokenupdate['shpping_label']['shipping_image'])) {
			$shipping_label_file_name = $tokenupdate['shpping_label']['shipping_image'];
		}
		$config['protocol'] = $emailtemplete['protocol'];
		$config['smtp_user'] = $emailtemplete['smtp_user'];
		$config['smtp_port'] = $emailtemplete['smtp_port'];
		$config['smtp_host'] = $emailtemplete['smtp_host'];
		$config['smtp_pass'] = $emailtemplete['smtp_pass'];
		$config['smtp_crypto'] = 'ssl';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = true;

		$this->email->initialize($config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");
		// $this->email->from('tradein@macofalltrades.com',"Mac Me An Offer");
		$this->email->from($emailtemplete['smtp_user'], "Mac Me An Offer");
		$this->email->to($tokenupdate['customerinfo']['form_email_address']);
		//$this->email->reply_to($emailtemplete['admin_email']);
		$this->email->reply_to($emailtemplete['smtp_user']);
		$this->email->subject('Your Estimate is Approved!');
		$this->email->message($htmlConten);
		if (isset($shipping_label_file_name) && !empty($shipping_label_file_name)) {
			$url = base_url() . '/application/uploads/ups/shipping/' . $shipping_label_file_name;
			$this->email->attach($url);
			$pdfurl = base_url() . '/application/uploads/pdf/MMAO_Packing_Instructions.pdf';
			$this->email->attach($pdfurl);
		}

		if ($this->email->send()) {
			//echo 'Your Email has successfully been sent.';

			$emailtemplete = $this->quotemodel->updateknockout($id);
			$this->session->set_userdata('updatedata', '1');
			redirect('admin/quote_test/view/' . $id);
		} else {
			show_error($this->email->print_debugger());
		}
	}

	public function export() {

		$this->Test_Quotemodel->export();
	}

	public function export_quote($title) {

		$id = $this->uri->segment(4);
		$this->data['getquotedetails'] = $this->Test_Quotemodel->export_quote($id);

	}

	// edit offer price
	public function saveOfferPrice() {

		$response = $this->Test_Quotemodel->updateOfferPrice();
		echo $response;
	}

	public function emailitemadmin() {

		$emailtemplete_tmp1 = '<table bgcolor="#eeeeee" cellpadding="0" cellspacing="0" class="main-wrapper" dir="ltr" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fbfafa;" width="100%">
            <tbody>
                <tr>
                    <td style="border-collapse: collapse; padding: 40px 10px 40px 10px;">
                    <table align="center" cellpadding="0" cellspacing="0" class="content-wrapper" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; border: 1px solid #eeeeee;" width="600">
                        <tbody>
                            <tr class="message-header" style="background-color:#e8e8e8">
                                <td style="border-collapse: collapse; padding: 10px 30px 20px 30px; color: #363636; font-family: sans-serif,Helvetica,Arial,sans-serif; font-size: 13px;">
                                <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td style="border-collapse: collapse; color: #363636; font-family: sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; text-align: center;"><a href="https://www.macmeanoffer.com" style="outline: none; color: #379424;"><img alt="macmeanoffer" src="https://app.macmeanoffer.com/assets/logo/mmao-logo.png" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: none; width: 200px;" width="547" /> </a></td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>
                            </tr>
                            <tr class="message-body">
                                <td style="border-collapse: collapse; padding: 30px;">
                                <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Hello {{name}},&nbsp;</b></p>

                                <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">PO: </b> {{PO}}</p>

                                <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Category Name: </b> {{category_name}}</p>

                                <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Product Name: </b> {{product_name}}</p>

                                {{serial_number}}

                                {{options}} {{label_image}}

                                </td>
                            </tr>
                            <tr class="message-body">
                                <td style="padding:0 30px 30px 30px">
                                <p style="color: #00c4ff;padding:0; margin:0; font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 15px; ">Note: This is a system generated mail, Please don&#39;t reply back to this email.</p>
                                </td>
                            </tr>
                            <tr class="message-copyright">
                                <td style="border-collapse: collapse; padding: 0px 30px 10px; color: #363636; font-family: sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; background-color: #757f83;">
                                <table class="copyright" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td style="border-collapse: collapse; color: #363636; font-family: sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; padding: 10px 0 0 0; padding-bottom: 0 !important; color: white; ">Copyright &copy; mac me an offer, All rights reserved.</td>
                                            <td align="right" style="border-collapse: collapse; color: #363636; font-family: sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; padding: 10px 0 0 0; padding-bottom: 0 !important; color: white;">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>';

		$emailtemplete_tmp = '<table bgcolor="#eeeeee" cellpadding="0" cellspacing="0" class="main-wrapper" dir="ltr" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #fbfafa;" width="100%">
            <tbody>
                <tr>
                    <td style="border-collapse: collapse; padding: 40px 10px 40px 10px;">
                    <table align="center" cellpadding="0" cellspacing="0" class="content-wrapper" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #FFFFFF; border: 1px solid #eeeeee;" width="600">
                        <tbody>
                            <tr class="message-header" style="background-color:#e8e8e8">
                                <td style="border-collapse: collapse; padding: 10px 30px 20px 30px; color: #363636; font-family: sans-serif,Helvetica,Arial,sans-serif; font-size: 13px;">
                                <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td style="border-collapse: collapse; color: #363636; font-family: sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; text-align: center;"><a href="https://www.macmeanoffer.com" style="outline: none; color: #379424;"><img alt="macmeanoffer" src="https://app.macmeanoffer.com/assets/logo/mmao-logo.png" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none; border: none; width: 200px;" width="547" /> </a></td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>
                            </tr>
                            <tr class="message-body">
                                <td style="border-collapse: collapse; padding: 30px;">
                                <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Hello {{name}},&nbsp;</b></p>

                                <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Thanks for choosing mac me an offer! Your offer is valid for 14 days.</b></p>

                                <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">PO: </b> {{PO}}</p>

                                <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Category Name: </b> {{category_name}}</p>

                                <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Product Name: </b> {{product_name}}</p>

                                {{serial_number}}

                                {{price}}

                                {{options}} {{label_image}}

                                <p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Please use the attached packing instructions and prepaid label to send your product to us. We will update you once it has been received and inspected!</b></p>
                                </td>
                            </tr>
                            <tr class="message-body">
                                <td style="padding:0 30px 30px 30px">
                                <p style="color: #00c4ff;padding:0; margin:0; font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 15px; ">Note: This is a system generated mail, Please do not reply back to this email.</p>
                                </td>
                            </tr>
                            <tr class="message-copyright">
                                <td style="border-collapse: collapse; padding: 0px 30px 10px; color: #363636; font-family: sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; background-color: #757f83;">
                                <table class="copyright" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                    <tbody>
                                        <tr>
                                            <td style="border-collapse: collapse; color: #363636; font-family: sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; padding: 10px 0 0 0; padding-bottom: 0 !important; color: white; ">Copyright &copy; mac me an offer, All rights reserved.</td>
                                            <td align="right" style="border-collapse: collapse; color: #363636; font-family: sans-serif,Helvetica,Arial,sans-serif; font-size: 13px; padding: 10px 0 0 0; padding-bottom: 0 !important; color: white;">&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
            </tbody>
        </table>';

		$tokenupdate = $this->Test_Quotemodel->getquotedetails('128300');
		$emailtemplete = $this->Test_Quotemodel->getBcConfig();
		$knockoutstat = $tokenupdate['customerinfo']['knockout'];

		// echo "<pre>";print_r($tokenupdate);die;

		// $tokenupdate = $this->productmodel->getquote($token);
		// $emailtemplete = $this->productmodel->getBcConfig();

		$option = '';

		if (isset($tokenupdate['customerinfo']['selectedoption']) && !empty($tokenupdate['customerinfo']['selectedoption'])) {
			/*foreach ($tokenupdate['selectedoption'] as $value) {
				            # code...

				            $option .= '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">'.$value['option_set_name'].': </b> '.$value['option_label'].'</p>';
			*/
			$o = json_decode($tokenupdate['customerinfo']['selectedoption']);

			if (isset($o->option) && !empty($o->option)) {
				foreach ($o->option as $value) {
					# code...

					$option .= '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">' . $value->option_set_name . ': </b> ' . $value->option_label . '</p>';
				}
			}

			if (isset($o->qustionans) && !empty($o->qustionans)) {
				foreach ($o->qustionans as $val) {
					# code...

					$option .= '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">' . $val->option_set_name . ': </b> ' . $val->option_label . '</p>';
				}
			}
		}

		// device serial number
		$serial_no = '';
		if (isset($tokenupdate['customerinfo']['form_serial_number']) && $tokenupdate['customerinfo']['form_serial_number'] != '') {
			$serial_no = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Serial Number: </b> ' . $tokenupdate['customerinfo']['form_serial_number'] . '</p>';
		}
		$quoteprice = '';
		$exp_price = explode("$", $tokenupdate['customerinfo']['price']);
		if ($knockoutstat == 0 && isset($tokenupdate['customerinfo']['price']) && !empty($tokenupdate['customerinfo']['price']) && $exp_price[1] > 0) {
			$quoteprice = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Price: </b> ' . $tokenupdate['customerinfo']['price'] . '</p>';
		}

		//$emailtemplete = $this->emailtemplete();

		$htmlConten = str_replace(array('{{name}}', '{{PO}}',
			'{{category_name}}',
			'{{product_name}}',
			'{{serial_number}}',
			'{{price}}',
			'{{options}}',
			'{{label_image}}',
			'Your estimate is approved and is valid for 14 days. This estimate is for the following product.',
			'Use the attached packing instructions and prepaid label to send your product to us. We will update you once it has been received and inspected!'),
			array('Admin', $tokenupdate['customerinfo']['id'],
				// $tokenupdate['product']['category_name'],
				'test_category',
				$tokenupdate['product']['product_title'],
				$serial_no,
				$quoteprice,
				$option,
				'',
				'',
				'',
			)
			// , $emailtemplete_tmp);
			, $emailtemplete['admin_email_template']);

		echo $htmlConten;die;

	}

	public function test_email_template() {
		$response = $this->Test_Quotemodel->test_email_template();
	}

	public function change_payment_status() {
		$response = $this->Test_Quotemodel->change_payment_status();
	}

	public function delete_quote() {
		$tbl_name = 'quote';
		$quote_id = $this->uri->segment(4);
		if (!isset($quote_id) || $quote_id == '') {
			echo "Please enter QuoteId.";
		} else {
			$this->db->where('id', $quote_id);
			$chk_qut = $this->db->get($tbl_name);
			if ($chk_qut->num_rows() <= 0) {
				echo "Quote not found.";
			} else {
				$this->db->delete($tbl_name, ['id' => $quote_id]);
				$this->db->where('id', $quote_id);
				$query = $this->db->get($tbl_name);
				if ($query->num_rows() > 0) {
					echo "Quote not deleted. Please try later.";
				} else {
					echo "Quote successfully deleted.";
				}
			}
		}
	}

	public function getSpecificQuoteData() { 
		$data = $this->Test_Quotemodel->getquotedetails('151597');
		echo "<pre>";print_r($data);
		die;
	}

}
