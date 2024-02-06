<?php
class Quote extends CI_controller
{
    public function __construct()
    {
        parent::__construct();
        // $session_data = $this->session->userdata('admin_session');
        // if (!isset($session_data) || empty($session_data)) {
        //     redirect('admin/login');
        // }

        ini_set('memory_limit', '-1');
        $this->load->library('upload');
        $this->load->library('image_lib');
        $this->load->model("admin/quotemodel");
        $this->load->library('email');
        ini_set('error_reporting', 'E_ALL');
        ini_set('display_errors', 'On');


    }

    public function index()
    {
        $this->data["title"] = 'Quotes';
        $this->data["page_head"] = 'Quotes';

        $this->load->view('admin/common/leftmenu', $this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/quote/list');
        $this->load->view('admin/common/footer');
    }

    public function dataajax()
    {   

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

        $searchcheckno = '';
        if (isset($_REQUEST['query']['checkno']) && !empty($_REQUEST['query']['checkno'])) {
            $searchcheckno = trim( filter_var( $_REQUEST['query']['checkno'], FILTER_SANITIZE_NUMBER_INT ) );
        }        

        $searchpayee = '';
        if (isset($_REQUEST['query']['searchpayee']) && !empty($_REQUEST['query']['searchpayee'])) {
            $searchpayee = trim( addslashes( $_REQUEST['query']['searchpayee'] ) );
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
        if (isset($_REQUEST['query']['payment_status']) && $_REQUEST['query']['payment_status']!='') {
            $payment_status = $_REQUEST['query']['payment_status'];
        }

        $totalcategory = $this->quotemodel->quotetotal($searchtext, $searchdropdown, $startdate, $enddate, $payment_method, $payment_status, $date_type, $searchcheckno, $searchpayee );

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
            $totalcategory = $this->quotemodel->get_quote_details($start_from, $records['meta']['perpage'], $searchtext, $searchdropdown, $startdate, $enddate, $payment_method, $payment_status, $date_type, $searchcheckno, $searchpayee );
        } else {
            $start_from = ($records['meta']['page'] - 1) * $records['meta']['perpage'];
            $totalcategory = $this->quotemodel->get_quote_details($start_from, $records['meta']['perpage'], $searchtext, $searchdropdown, $startdate, $enddate, $payment_method, $payment_status, $date_type, $searchcheckno, $searchpayee );
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
                    $payment_status_html = '<span><span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill '.$value['id'].'" onclick="change_payment_status('.$value['id'].',0)" style="cursor: pointer;">Paid</span></span>';
                } else {
                    $payment_status_html = '<span style="width: 114px;"><span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill '.$value['id'].'" onclick="change_payment_status('.$value['id'].',1)" style="cursor: pointer;">Pend Pay</span></span>';
                }

                $quote_created_datetime = DateTime::createFromFormat( 'Y-m-d H:i:s', $value['created_date'] );
                $quote_created_datetime->setTimezone( new DateTimeZone( 'America/New_York' ) );

                $records['data'][$i]['id'] = $value['id'];
                $records['data'][$i]['productname'] = $value['product_title'];
                $records['data'][$i]['email'] = $value['form_email_address'];
                $records['data'][$i]['price'] = $value['price'];
                $records['data'][$i]['status'] = $status;
                $records['data'][$i]['payment_status'] = $payment_status_html; 
                $records['data'][$i]['created_datetime'] = $quote_created_datetime->format( 'Y-m-d g:iA' );
                $records['data'][$i]['action'] = '<button type="button" class="btn btn-outline-info btn-elevate btn-pill" onclick="update_quote(\'' . $value["id"] . '\')" >' .
                    '<i class="fab fa-vimeo"></i>View</button><button type="button" class="btn btn-outline-info btn-elevate btn-pill" onclick="export_quote(\'' . $value["id"] . '\')" >' .
                    '<i class="fab fa-vimeo"></i>Export</button>';
                $i++;
            }
        } else {
            $records['data'][0]['productname'] = 'No recode found.';
        }
        echo json_encode($records);
    }

    public function View($title)
    {
        $this->data["title"] = 'View Quotes';
        $id = $this->uri->segment(4);
        $this->data['getquotedetails'] = $this->quotemodel->getquotedetails($id);

        $this->load->view('admin/common/leftmenu', $this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/quote/create', $this->data);
        $this->load->view('admin/common/footer');
    }

    public function emailsend()
    {

        $this->data["title"] = 'View Quotes';
        $id = $this->uri->segment(4);
        $getquotedetails = $this->quotemodel->getquotedetails($id);
        if (isset($getquotedetails) && !empty($getquotedetails)) {
            $this->emailtem($getquotedetails, $id);
        }

    }

    public function emailtem($tokenupdate, $id)
    {
        // $emailtemplete_tmp = $this->quotemodel->quoteEmailTemplate();
        $emailtemplete = $this->quotemodel->getBcConfig();
        $option = '';

        // device serial number
        $serial_no = '';
        if (isset($tokenupdate['customerinfo']['form_serial_number']) && !empty($tokenupdate['customerinfo']['form_serial_number'])) {
            $serial_no = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Serial Number: </b> '.$tokenupdate['customerinfo']['form_serial_number'].'</p>';
        }

        // offered price & default price
        $offered_price = '';
        $exp_offeredprice = explode("$", $tokenupdate['customerinfo']['offered_price']);
        $exp_price = explode("$", $tokenupdate['customerinfo']['price']);

        if (isset($tokenupdate['customerinfo']['offered_price']) && !empty($tokenupdate['customerinfo']['offered_price']) && $exp_offeredprice[1] > 0) {
            $offered_price = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Offered Price: </b> '.$tokenupdate['customerinfo']['offered_price'].'</p>';
        }else if(isset($tokenupdate['customerinfo']['price']) && !empty($tokenupdate['customerinfo']['price']) && $exp_price[1] > 0){
            $offered_price = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Price: </b> '.$tokenupdate['customerinfo']['price'].'</p>';
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
            $emailtemplete = $this->quotemodel->updateknockout($id);
            $this->session->set_userdata('updatedata', '1');
            redirect('admin/quote/view/' . $id);
        } else {
            show_error($this->email->print_debugger());
        }
    }

    public function export()
    {
        $this->quotemodel->export();
    }

    public function export_quote($title)
    {
      
        $id = $this->uri->segment(4);
        $this->data['getquotedetails'] = $this->quotemodel->export_quote($id);

    }

    // edit offer price
    public function saveOfferPrice(){
        $response = $this->quotemodel->updateOfferPrice();
        echo $response;
    }

    // edit phone number
    public function savePhoneNumber(){
        $response = $this->quotemodel->updatePhoneNumber();
        echo $response;
    }

    // edit payment method
    public function savePaymentMethod(){
        $response = $this->quotemodel->updatePaymentMethod();
        echo $response;
    }

    public function change_payment_status(){ 
        $response = $this->quotemodel->change_payment_status();
    }

    public function purgeOldExports()
    {
        $this->quotemodel->purgeOldExports();
    }

}
