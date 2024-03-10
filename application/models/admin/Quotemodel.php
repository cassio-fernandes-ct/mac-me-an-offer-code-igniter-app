<?php

class Quotemodel extends CI_Model
{
    public function __construct()
    {  
        $this->serial_table = "serial";
        $this->quote_table = "quote";
        $this->category_table = "category";
        $this->category_product_table = "product_category";
        $this->serise_product_table = "serise_product";
        $this->product_table = "products";
        $this->product_option_table = "product_option";
        $this->shipping_label_table = "shipping_label";
        $this->currentdate = date('Y-m-d H:i:s');
        $this->load->database();

        $this->pay_method_arr = ['us_first_class_mail'=>'Check – US First Class Mail (free)','paypal'=>'PayPal – seller pays applicable PayPal fees','macofalltrades_store_credit'=>'macofalltrades.com Store Credit','ach'=>'ACH','zelle'=>'Zelle'];

    }

    public function quotetotal(
        $search, 
        $searchdropdown, 
        $startdate, 
        $enddate,
        $payment_method,
        $payment_status,
        $date_type,
        $check_no = '',
        $payee = ''
    )
    {
        if ($searchdropdown == 'all') {
            $filter = "WHERE (q.contact_flag = 1 OR q.contact_flag = 0)";
        }
        if ($searchdropdown == 'abandoned') {
            $filter = "WHERE q.contact_flag = 0 ";
        }
        if ($searchdropdown == 'completed') {
            $filter = "WHERE q.contact_flag = 1 AND q.knockout = 0";
        }

        if ($searchdropdown == 'knockoutapproval') {
            $filter = "WHERE q.knockout > 0 AND q.contact_flag = 1";
        }

        $payment_filter = '';
        $pay_method_arr = $this->pay_method_arr; 
        $i=1;
        $tmp_pay_method_arr = array();
        if(!empty($payment_method)&&$payment_method!='all'&&!in_array("all", $payment_method)){
            $cnt_pay_method = count($payment_method);
            foreach($payment_method as $pay_method){
                if (array_key_exists($pay_method,$pay_method_arr)){  
                    if(empty($tmp_pay_method_arr)){ $payment_filter .= "AND ("; }else{ $payment_filter .= " OR "; }
                    $payment_filter .= "q.receive_payment = '".$pay_method_arr[$pay_method]."'";
                    if($i==$cnt_pay_method){ $payment_filter .= ")"; } 
                    $tmp_pay_method_arr[] = $pay_method;
                }
                $i++;
            }
        }

        /*
        $payment_filter = '';
        if ($payment_method == 'us_first_class_mail') {
            $payment_filter = "AND (q.receive_payment = 'Check – US First Class Mail (free)')";
        }
        if ($payment_method == 'paypal') {
            $payment_filter = "AND (q.receive_payment = 'PayPal – seller pays applicable PayPal fees')";
        }
        if ($payment_method == 'macofalltrades_store_credit') {
            $payment_filter = "AND (q.receive_payment = 'macofalltrades.com Store Credit')";
        }
        */

        $pay_status_filter = '';
        if ($payment_status != 'all') {
            $pay_status_filter = "AND (q.payment_status = '".$payment_status."')";
        }

        $search_query = '';
        if (isset($search) && !empty($search)) {
            $search_e = explode(' ', $search);
            $i = 1;
            foreach ($search_e as $search_e_s) {
                if ($i == 1) {
                    $search_query .= ' AND (p.product_title LIKE "%' . $search_e_s . '%" OR q.form_email_address LIKE "%' . $search_e_s . '%" OR q.id LIKE "%' . $search_e_s . '%" OR q.form_serial_number LIKE "%' . $search_e_s . '%")';
                } else {
                    $search_query .= ' AND (p.product_title LIKE "%' . $search_e_s . '%"OR q.form_email_address LIKE "%' . $search_e_s . '%" OR q.id LIKE "%' . $search_e_s . '%" OR q.form_serial_number LIKE "%' . $search_e_s . '%")';
                }
                $i++;
            }
        }

        // Creation & Modified date filter
        if (isset($startdate) && !empty($startdate) && isset($enddate) && !empty($enddate) && $date_type == 'creation') {
            $search_query .= " AND DATE(q.created_date) >= '".$startdate."' AND DATE(q.created_date) <= '".$enddate."'";
        }else if (isset($startdate) && !empty($startdate) && isset($enddate) && !empty($enddate) && $date_type == 'modified') {
            $search_query .= " AND DATE(q.modified_date) >= '".$startdate."' AND DATE(q.modified_date) <= '".$enddate."'";
        } 

        // update query to check for customer name
        if( !empty( $payee ) ) {
            $search_query .= " AND ( q.form_first_name LIKE '%" . $payee . "%' OR q.form_last_name LIKE '%" . $payee . "%' ) ";
        }

        // optionally update query to target specific quote
        if( !empty( $check_no ) ) {
            $search_query .= " AND q.chkno = '" . $check_no . "' ";
        }

        $sql = "SELECT q.id FROM " . $this->product_table . " as p RIGHT JOIN " . $this->quote_table . " as q ON(p.bc_product_id = q.product_id) " . $filter . " " . $search_query . " ".$payment_filter." ".$pay_status_filter." ORDER BY q.id DESC ";

        $query = $this->db->query( $sql );

        return $query->num_rows();
    }

    public function get_quote_details($startform, $limit, $search, $searchdropdown, $startdate, $enddate,$payment_method,$payment_status, $date_type, $check_no = '', $payee = '' )
    {
        if ($searchdropdown == 'all') {
            $filter = "WHERE (q.contact_flag = 1 OR q.contact_flag = 0)";
        }
        if ($searchdropdown == 'abandoned') {
            $filter = "WHERE q.contact_flag = 0 ";
        }
        if ($searchdropdown == 'completed') {
            $filter = "WHERE q.contact_flag = 1 AND q.knockout = 0";
        }

        if ($searchdropdown == 'knockoutapproval') {
            $filter = "WHERE q.knockout > 0 AND q.contact_flag = 1";
        }

        $payment_filter = '';  
        $pay_method_arr = $this->pay_method_arr; 
        $i=1;
        $tmp_pay_method_arr = array();
        if(!empty($payment_method)&&$payment_method!='all'&&!in_array("all", $payment_method)){
            $cnt_pay_method = count($payment_method);
            foreach($payment_method as $pay_method){
                if (array_key_exists($pay_method,$pay_method_arr)){  
                    if(empty($tmp_pay_method_arr)){ $payment_filter .= "AND ("; }else{ $payment_filter .= " OR "; }
                    $payment_filter .= "q.receive_payment = '".$pay_method_arr[$pay_method]."'";
                    if($i==$cnt_pay_method){ $payment_filter .= ")"; } 
                    $tmp_pay_method_arr[] = $pay_method;
                }
                $i++;
            }
        }

        /*
        $payment_filter = '';
        if ($payment_method == 'us_first_class_mail') {
            $payment_filter = "AND (q.receive_payment = 'Check – US First Class Mail (free)')";
        }
        if ($payment_method == 'paypal') {
            $payment_filter = "AND (q.receive_payment = 'PayPal – seller pays applicable PayPal fees')";
        }
        if ($payment_method == 'macofalltrades_store_credit') {
            $payment_filter = "AND (q.receive_payment = 'macofalltrades.com Store Credit')";
        }
        */

        $pay_status_filter = '';
        if ($payment_status != 'all') {
            $pay_status_filter = "AND (q.payment_status = '".$payment_status."')";
        }

        $search_query = '';
        if (isset($search) && !empty($search)) {
            $search_e = explode(' ', $search);
            $i = 1;
            foreach ($search_e as $search_e_s) {
                if ($i == 1) {
                    $search_query .= ' AND (p.product_title LIKE "%' . $search_e_s . '%" OR q.form_email_address LIKE "%' . $search_e_s . '%" OR q.id LIKE "%' . $search_e_s . '%" OR q.form_serial_number LIKE "%' . $search_e_s . '%")';
                } else {
                    $search_query .= ' AND (p.product_title LIKE "%' . $search_e_s . '%"OR q.form_email_address LIKE "%' . $search_e_s . '%" OR q.id LIKE "%' . $search_e_s . '%" OR q.form_serial_number LIKE "%' . $search_e_s . '%")';
                }
                $i++;
            }
        }

        // Creation & Modified date filter
        if (isset($startdate) && !empty($startdate) && isset($enddate) && !empty($enddate) && $date_type == 'creation') { 
            $search_query .= " AND DATE(q.created_date) >= '".$startdate."' AND DATE(q.created_date) <= '".$enddate."'";
        }else if (isset($startdate) && !empty($startdate) && isset($enddate) && !empty($enddate) && $date_type == 'modified') { 
            $search_query .= " AND DATE(q.modified_date) >= '".$startdate."' AND DATE(q.modified_date) <= '".$enddate."'";
        }

        // update query to check for customer name
        if( !empty( $payee ) ) {
            $search_query .= " AND ( q.form_first_name LIKE '%" . $payee . "%' OR q.form_last_name LIKE '%" . $payee . "%' ) ";
        }

        // optionally update query to target specific quote
        if( !empty( $check_no ) ) {
            $search_query .= " AND q.chkno = '" . $check_no . "' ";
        }        

        $sql = "SELECT q.id,p.product_title,q.form_email_address,q.price,q.contact_flag,q.knockout,q.payment_status,q.created_date FROM " . $this->product_table . " as p RIGHT JOIN " . $this->quote_table . " as q ON(p.bc_product_id = q.product_id) " . $filter . " " . $search_query . " ".$payment_filter." ".$pay_status_filter." ORDER BY q.id DESC limit " . $startform . " ," . $limit . " ";
     
        $query = $this->db->query( $sql );

        return $query->result_array();

    }

    public function getquotedetails($id)
    {
        $query = $this->db->query("SELECT * FROM " . $this->quote_table . " WHERE id = " . $id . "");
        $res = $query->row_array();

        $data['customerinfo'] = $res;
        $data['product'] = $this->productget($res['product_id']);
        //$data['selectedoption'] = $this->productoptionget($res['attribute'],$res['product_id']);
        $da = json_decode($res['selectedoption']);

        $data['selectedoption'] = array_chunk($da->option ?? [], 2);
        $data['qustionans'] = array();
        if (isset($da->qustionans) && !empty($da->qustionans)) {
            $data['qustionans'] = array_chunk(@$da->qustionans, 2);
        }
        $data['shpping_label'] = $this->shpping_label($id);

        return $data;
    }

    public function shpping_label($id)
    {
        if (isset($id) && !empty($id)) {
            $query = $this->db->query("SELECT shipping_image FROM " . $this->shipping_label_table . " WHERE quote_id = " . $id . "");
            return $query->row_array();
        }

    }

    public function productget($productid)
    {
        if (isset($productid) && !empty($productid)) {
            $query = $this->db->query("SELECT bc_product_id,product_title FROM " . $this->product_table . " WHERE bc_product_id = " . $productid . "");
            return $query->row_array();

        }
    }

    public function productoptionget($selectedattribute, $product_id)
    {

        $selected = json_decode($selectedattribute);
        $data = array();
        if (isset($selected) && !empty($selected) && isset($product_id) && !empty($product_id)) {
            foreach ($selected as $key => $value) {

                $query = $this->db->query("SELECT   option_set_name,option_label FROM " . $this->product_option_table . " WHERE product_id = " . $product_id . " AND attribut_id = " . $key . " AND option_label_value_id = " . $value . " ");
                $d = $query->row_array();
                if (isset($d) && !empty($d)) {
                    $data[] = $d;
                }
            }
        }

        return array_chunk($data, 2);

        //$query = $this->db->query("SELECT product_title FROM ".$this->product_option_table." WHERE bc_product_id = ".$productid."");
        //return $query->row_array();
    }

    public function getBcConfig()
    {
        $query = $this->db->query("SELECT * FROM setting ");
        return $query->row_array();
    }

    public function updateknockout($id)
    {
        $data = array(
            'knockout' => 0,
        );

        $this->db->where('id', $id);
        $this->db->update($this->quote_table, $data);
    }

    public function export_quote($id)
    {
        $query = $this->db->query("SELECT DISTINCT quote.*, shipping_label.shipping_image,p.product_title FROM $this->quote_table LEFT JOIN shipping_label ON (quote.id = shipping_label.quote_id) LEFT JOIN " . $this->product_table . " as p ON (p.bc_product_id = quote.product_id) where quote.id = " . $id . "");
        $query_results = $query->result_array();

        // Duplicates have appeared at least once as a result of this query
        // Therefore we loop through and remove any duplicates to prevent it from happening again
        $query_results_tmp = [];
        foreach( $query_results as $query_result ) {
            $query_results_tmp[$query_result['id']] = $query_result;
        }
        ksort( $query_results_tmp, SORT_NUMERIC );
        $query_results = array_reverse( $query_results_tmp );

        if (isset($query_results) && !empty($query_results)) {
            $i = 1;
            $result[0][] = 'Check No.';
            $result[0][] = 'Payee';
            $result[0][] = 'Bank Account';
            $result[0][] = 'Mailing Address';
            $result[0][] = 'Date';
            $result[0][] = 'Location';
            $result[0][] = 'Memo';
            $result[0][] = 'Print Later';
            $result[0][] = 'Type';
            $result[0][] = 'Category/Account';
            $result[0][] = 'Product/Service';
            $result[0][] = 'Qty';
            $result[0][] = 'Rate';
            $result[0][] = 'Description';
            $result[0][] = 'Amount';
            $result[0][] = 'Billable';
            $result[0][] = 'Customer/Project';
            $result[0][] = 'Tax Rate';
            $result[0][] = 'Class';
            $result[0][] = 'Serial Number';
            $result[0][] = 'Selected Option';
            $result[0][] = 'Status';
            $result[0][] = 'Email Address';
            $result[0][] = 'How would you like to receive payment?';
            $result[0][] = 'Shipping label';
            $result[0][] = 'Payment Status';
            $result[0][] = 'Product Name';

            foreach ($query_results as $query_result) {
                $selectedoption = json_decode($query_result['selectedoption']);

                $selectedoption_text = '';
                if (isset($selectedoption) && !empty($selectedoption)) {
                    foreach ($selectedoption->option as $selected_option) {

                        $selectedoption_text .= "Q. " . $selected_option->option_set_name . "\n";
                        $selectedoption_text .= "A. " . $selected_option->option_label . "\n";
                    }
                }

                $status = 'Abandoned';
                if ($query_result['contact_flag'] != 0 && $query_result['knockout'] > 0) {
                    $status = 'Pending Knockout Quote';
                } else if ($query_result['contact_flag'] == 1) {
                    $status = 'Completed';
                }
                
                $payment_status = 'Paid';
                if ($query_result['payment_status']==0) {
                    $payment_status = 'Pend Pay';
                }

                $label = '';
                if (isset($query_result['shipping_image']) && !empty($query_result['shipping_image'])) {
                    $label = 'https://app.macmeanoffer.com//application/uploads/ups/shipping/' . $query_result['shipping_image'];
                }

                $form_street1 = $query_result['form_street1']!='' ? $query_result['form_street1']."\n" : "";
                $form_street2 = $query_result['form_street2']!='' ? $query_result['form_street2']."\n" : "";

                $off_price = explode('$', $query_result['offered_price']);
                $price_field = ($off_price[1] > 0) ? $query_result['offered_price'] : $query_result['price'];

                $result[$i][] = $query_result['id'];
                $result[$i][] = $query_result['form_first_name']." ".$query_result['form_last_name'];
                $result[$i][] = 'Checking Account';
                $result[$i][] = $form_street1.$form_street2.$query_result['form_city'].", ".$query_result['form_state']." ".$query_result['form_zip'];
                // $result[$i][] = date("m/d/Y", strtotime($query_result['created_date']));
                $result[$i][] = date("m/d/Y");
                $result[$i][] = '';
                $result[$i][] = $query_result['id'];
                $result[$i][] = 'TRUE';
                $result[$i][] = 'Category Details';
                $result[$i][] = 'SELLERS:Individual Sellers';
                $result[$i][] = '';
                $result[$i][] = '';
                $result[$i][] = '';
                $result[$i][] = '';
                $result[$i][] = str_replace(",", "", $price_field);
                $result[$i][] = '';
                $result[$i][] = '';
                $result[$i][] = '';
                $result[$i][] = '';
                $result[$i][] = "\r".$query_result['form_serial_number'];
                $result[$i][] = $selectedoption_text;
                $result[$i][] = $status;
                $result[$i][] = $query_result['form_email_address'];
                $result[$i][] = str_replace("–", "", $query_result['receive_payment']);
                $result[$i][] = $label;
                $result[$i][] = $payment_status;
                $result[$i][] = $query_result['product_title'];

                $i++;
            }

        $result = array_values($result);

            $this->array_to_csv_download($result,'quote_'.$query_result['id'].'.csv',';');
        }
    }

    public function export($isCli = false)
    { 
        //ini_set( 'display_errors', true);
        //error_reporting(E_ALL);
        $post = $this->input->post();
        $filter = '';
        
        if($isCli) {
            $post = [
                'search_text' => '',
                'status' => 'all',
                'check_no' => '',
                'payee' => '',
                'payment_method' => ['all'],
                'payment_status' => 'all',
                'startdate' => date('Y/m/d', strtotime('-1 day')),
                'enddate' => date('Y/m/d'),
                'modified_startdate' => '',
                'modified_enddate' => '',
                'exporttype' => 'new-template'
            ];
        }

        $payment_filter = '';
        $pay_method_arr = $this->pay_method_arr; 
        $exp_type = $post['exporttype'];
        $pay_mthd = $post['payment_method'];

        if ($post['status'] == 'all') {

            // The new template will not have any abandoned
            if($exp_type == 'new-template'){
                $filter = "WHERE (q.contact_flag = 1 )"; //TO REMOVE abandoned
            } else {
                $filter = "WHERE (q.contact_flag = 1 OR q.contact_flag = 0)";
            }
        }
        if ($post['status'] == 'abandoned') {
            $filter = "WHERE q.contact_flag = 0 ";
        }
        if ($post['status'] == 'completed') {
            $filter = "WHERE q.contact_flag = 1 AND q.knockout = 0";
        }

        if ($post['status'] == 'knockoutapproval') {
            $filter = "WHERE q.knockout > 0 AND q.contact_flag = 1";
        }

        
        $i=1;
        $tmp_pay_method_arr = array();
        if(!empty($pay_mthd)&&$pay_mthd!='all'&&!in_array("all", $pay_mthd)){
            $cnt_pay_method = count($pay_mthd);
            foreach($pay_mthd as $pay_method){
                if (array_key_exists($pay_method,$pay_method_arr)){  
                    if(empty($tmp_pay_method_arr)){ $payment_filter .= "AND ("; }else{ $payment_filter .= " OR "; }
                    $payment_filter .= "q.receive_payment = '".$pay_method_arr[$pay_method]."'";
                    if($i==$cnt_pay_method){ $payment_filter .= ")"; } 
                    $tmp_pay_method_arr[] = $pay_method;
                }
                $i++;
            }
        }
        
        /*
        // commented at 14-2-2022 by HS
        if ($post['payment_method'] == 'us_first_class_mail') {
            $payment_filter = "AND (q.receive_payment = 'Check – US First Class Mail (free)')";
        }
        if ($post['payment_method'] == 'paypal') {
            $payment_filter = "AND (q.receive_payment = 'PayPal – seller pays applicable PayPal fees')";
        }
        if ($post['payment_method'] == 'macofalltrades_store_credit') {
            $payment_filter = "AND (q.receive_payment = 'macofalltrades.com Store Credit')";
        }
        */

        $pay_status_filter = '';
        if ($post['payment_status'] != 'all') {
            $pay_status_filter = "AND (q.payment_status = '".$post['payment_status']."')";
        }

        $search_query = '';
        if (isset($post['search_text']) && !empty($post['search_text'])) {
            // search by SERIAL NO.
            $serial_qry  = $this->db->query("SELECT `bc_product_id` FROM ".$this->serial_table." WHERE serial LIKE '%".$post['search_text']."%' ");
            $serial_data =  $serial_qry->result_array();
            $ser_data =  array_map(function($serial_data){
                    return $serial_data['bc_product_id'];
                }, $serial_data);
            $pro_ids = implode(',', $ser_data);
            $serial_filter = '';
            if(!empty($pro_ids)&&$pro_ids!=''){
                $serial_filter = " OR q.product_id IN (".$pro_ids.")";
            }
            // END search by SERIAL NO.

            $search_e = explode(' ', $post['search_text']);
            $i = 1;
            foreach ($search_e as $search_e_s) {
                if ($i == 1) {
                    $search_query .= ' AND (p.product_title LIKE "%' . $search_e_s . '%" OR q.form_email_address LIKE "%' . $search_e_s . '%" OR q.id LIKE "%' . $search_e_s . '%" OR q.form_serial_number LIKE "%' . $search_e_s . '%")';
                } else {
                    $search_query .= ' AND (p.product_title LIKE "%' . $search_e_s . '%"OR q.form_email_address LIKE "%' . $search_e_s . '%" OR q.id LIKE "%' . $search_e_s . '%" OR q.form_serial_number LIKE "%' . $search_e_s . '%")';
                }
                $i++;
            }
        }

        // Creation & Modified date filter
        if (isset($post['startdate']) && !empty($post['startdate']) && isset($post['enddate']) && !empty($post['enddate'])) {
            $search_query .= " AND DATE(q.created_date) >= '".$post['startdate']."' AND DATE(q.created_date) <= '".$post['enddate']."'";
        } else if(isset($post['modified_startdate']) && !empty($post['modified_startdate']) && isset($post['modified_enddate']) && !empty($post['modified_enddate'])) {
            $search_query .= " AND DATE(q.modified_date) >= '".$post['modified_startdate']."' AND DATE(q.modified_date) <= '".$post['modified_enddate']."'";
        }
 
        // =====================================
        $query = $this->db->query("SELECT q.*, shipping_label.shipping_image, shipping_label_beta.shipping_image AS shipping_label, p.product_title FROM " . $this->quote_table . " as q LEFT JOIN shipping_label_beta ON (q.id = shipping_label_beta.quote_id) LEFT JOIN shipping_label ON (q.id = shipping_label.quote_id) LEFT JOIN " . $this->product_table . " as p ON (p.bc_product_id = q.product_id) " . $filter . " " . $search_query . " ".$payment_filter." ".$pay_status_filter." ORDER BY q.id DESC ");
        $query_results = $query->result_array();
       
        // Duplicates have appeared at least once as a result of this query
        // Therefore we loop through and remove any duplicates to prevent it from happening again
        $query_results_tmp = [];
        foreach( $query_results as $query_result ) {
            $query_results_tmp[$query_result['id']] = $query_result;
        }
        ksort( $query_results_tmp, SORT_NUMERIC );
        $query_results = array_reverse( $query_results_tmp );

        if (isset($query_results) && !empty($query_results)) {
            $i = 1;
            $total_price=0;
            $data_cnt = count($query_results);

            // New Export Template's columns
            if($exp_type == "new-template") {
                $result[0][] = 'TRANNO';
                $result[0][] = 'Vendor Name';
                $result[0][] = 'Vendor Address 1';
                $result[0][] = 'Vendor Address 2';
                // $result[0][] = 'Vendor Address 3';
                $result[0][] = 'Vendor City';
                $result[0][] = 'Vendor State';
                $result[0][] = 'Vendor Zip';
                $result[0][] = 'Vendor Country';
                $result[0][] = 'CHKNO';
                $result[0][] = 'Quote Created Date';
                $result[0][] = 'Offer Amount';
                $result[0][] = 'Vendor Email';
                $result[0][] = 'Status';
                $result[0][] = 'Selected Model';
                $result[0][] = 'Expected Condition';
                $result[0][] = 'Original Accessories';
                $result[0][] = 'Battery OK';
                $result[0][] = 'Upgrades';
                $result[0][] = 'Hardware Issues';
                $result[0][] = 'Requested Payment Method';
                $result[0][] = 'Shipping Label Link';
                $result[0][] = 'Additional Insurance';
            } else {
                $result[0][] = ($exp_type=='normal') ? 'Check No.' : 'PAYMTHD';
                $result[0][] = ($exp_type=='normal') ? 'Payee' : 'CRDDBTFL';
                $result[0][] = ($exp_type=='normal') ? 'Bank Account' : 'TRANNO';
                $result[0][] = ($exp_type=='normal') ? 'Mailing Address' : 'VALDT';
                $result[0][] = ($exp_type=='normal') ? 'Date' : 'PAYAMT'; 
                $result[0][] = ($exp_type=='normal') ? 'Location' : 'ORIGACCTTY';
                $result[0][] = ($exp_type=='normal') ? 'Memo' : 'ORIGACCT';
                $result[0][] = ($exp_type=='normal') ? 'Print Later' : 'ORIGBNKIDTY';
                $result[0][] = ($exp_type=='normal') ? 'Type' : 'ORIGBNKID';
                $result[0][] = ($exp_type=='normal') ? 'Category/Account' : 'ORIGTORCVPRTYINF';
                $result[0][] = ($exp_type=='normal') ? 'Product/Service' : 'ORIGPRTYNM';
                $result[0][] = ($exp_type=='normal') ? 'Qty' : 'ORIGPRTYADDR1';
                $result[0][] = ($exp_type=='normal') ? 'Rate' : 'ORIGPRTYCTY';
                $result[0][] = ($exp_type=='normal') ? 'Description' : 'ORIGPRTYSTPRO';
                $result[0][] = ($exp_type=='normal') ? 'Amount' : 'ORIGPRTYPSTCD';
                $result[0][] = ($exp_type=='normal') ? 'Billable' : 'ORIGPRTYCTRYCD';
                $result[0][] = ($exp_type=='normal') ? 'Customer/Project' : 'RCVPRTYNM';
                $result[0][] = ($exp_type=='normal') ? 'Tax Rate' : 'RCVPRTYADDR1';
                $result[0][] = ($exp_type=='normal') ? 'Class' : 'RCVPRTYADDR2';
                $result[0][] = ($exp_type=='normal') ? 'Serial Number' : 'RCVPRTYADDR3';
                $result[0][] = ($exp_type=='normal') ? 'Selected Option' : 'RCVPRTYCTY';
                $result[0][] = ($exp_type=='normal') ? 'Status' : 'RCVPRTYSTPRO';
                $result[0][] = ($exp_type=='normal') ? 'Email Address' : 'RCVPRTYPSTCD';
                $result[0][] = ($exp_type=='normal') ? 'How would you like to receive payment?' : 'RCVPRTYCTRYCD';
                $result[0][] = ($exp_type=='normal') ? 'Shipping label' : 'CHKNO';
                $result[0][] = ($exp_type=='normal') ? 'Payment Status' : 'DOCTMPLNO';
                $result[0][] = ($exp_type=='normal') ? 'Product Name' : 'CHKDELCD';
            }
            
            if($exp_type == 'normal'){
                $result[0][] = 'Additional Insurance';
            } else if($exp_type == 'new-template'){ 
                $result[0][] = 'EPFSTNM';  
                $result[0][] = 'EPLSTNM';  
                $result[0][] = 'EPEML';    
                $result[0][] = 'Serial Number';   
                $result[0][] = 'Zelle Phone Number';              
                $result[0][] = 'INVDT';    
                $result[0][] = 'INVDESC';                 
                $result[0][] = 'Quote Number';  
            } else {
                $result[0][] = 'EPBNKID';
                $result[0][] = 'EPFSTNM';
                $result[0][] = 'EPLSTNM';
                $result[0][] = 'EPEML';
                $result[0][] = 'EPDESC';
                $result[0][] = 'EPPHNNO';
                $result[0][] = 'INVNO';
                $result[0][] = 'INVDT';
                $result[0][] = 'INVDESC';
                $result[0][] = 'PONUM';
                $result[0][] = 'INVTYPE';
                $result[0][] = 'INVONLYREC';
            }

            $expected_condition = '';
            $original_accessories = '';
            $battery_ok = '';
            $upgrades = '';
            $hardware_issues = '';
            $requested_payment_method = '';
            $shipping_label_link= '';

            foreach ($query_results as $query_result) {
                $selectedoption = json_decode($query_result['selectedoption']);

                $selectedoption_text = '';
                if (isset($selectedoption) && !empty($selectedoption) && isset($selectedoption->option) && !empty($selectedoption->option)) {
                    $selected_opt = $selectedoption->option;
                    foreach ($selected_opt as $selected_option) {
                        $selectedoption_text .= "Q. " . $selected_option->option_set_name . "\n";
                        $selectedoption_text .= "A. " . $selected_option->option_label . "\n";

                        if ($exp_type == 'new-template') {
                            if($selected_option->option_set_name == 'What is the condition of your product?')
                            {
                                $expected_condition = $selected_option->option_label;
                            }
    
                            if($selected_option->option_set_name == 'Does your product have a fully functional battery?')
                            {
                                $battery_ok = $selected_option->option_label;
                            }
    
                            if($selected_option->option_set_name == 'Will you be including all original accessories?')
                            {
                                $original_accessories = $selected_option->option_label;
                            }

                            if($selected_option->option_set_name == 'Does your product have any extraordinary upgrades?')
                            {
                                $upgrades = $selected_option->option_label;
                            }

                            if($selected_option->option_set_name == 'Does your device have any noticeable cosmetic or functionality issues? Examples include failing hardware, cracks or dents, etc.')
                            {
                                $hardware_issues = $selected_option->option_label;
                            }
                        }
                    }
                }

                $status = 'Abandoned';
                if ($query_result['contact_flag'] != 0 && $query_result['knockout'] > 0) {
                    $status = 'Pending Knockout Quote';
                } else if ($query_result['contact_flag'] == 1) {
                    $status = 'Completed';
                }

                $payment_status = 'Paid';
                if ($query_result['payment_status']==0) {
                    $payment_status = 'Pend Pay';
                }

                $label = '';
                if (isset($query_result['shipping_image']) && !empty($query_result['shipping_image'])) {
                    $label = 'https://app.macmeanoffer.com//application/uploads/ups/shipping/' . $query_result['shipping_image'];
                }
                
                if ($exp_type == 'new-template'){
                    if (isset($query_result['shipping_label']) && !empty($query_result['shipping_label'])) {
                        $shipping_label_link = 'https://app.macmeanoffer.com//application/uploads/ups/shipping/' . $query_result['shipping_label'];
                    }
                }

                $form_street1 = $query_result['form_street1']!='' ? $query_result['form_street1']."\n" : "";
                $form_street2 = $query_result['form_street2']!='' ? $query_result['form_street2']."\n" : "";

                $off_price = explode('$', $query_result['offered_price']);
                $price_field = ($off_price[1] > 0) ? $query_result['offered_price'] : $query_result['price'];
                
                if($exp_type=='normal'){
                    $result[$i][] = $query_result['id'];
                    $result[$i][] = $query_result['form_first_name']." ".$query_result['form_last_name'];
                    $result[$i][] = 'Checking Account';
                    $result[$i][] = $form_street1.$form_street2.$query_result['form_city'].", ".$query_result['form_state']." ".$query_result['form_zip'];
                    // $result[$i][] = date("m/d/Y", strtotime($query_result['created_date']));
                    $result[$i][] = date("m/d/Y");
                    $result[$i][] = '';
                    $result[$i][] = $query_result['id'];
                    $result[$i][] = 'TRUE';
                    $result[$i][] = 'Category Details';
                    $result[$i][] = 'SELLERS:Individual Sellers';
                    $result[$i][] = '';
                    $result[$i][] = '';
                    $result[$i][] = '';
                    $result[$i][] = '';
                    $result[$i][] = str_replace(",", "", $price_field);
                    $result[$i][] = '';
                    $result[$i][] = '';
                    $result[$i][] = '';
                    $result[$i][] = '';
                    $result[$i][] = "\r".$query_result['form_serial_number'];
                    $result[$i][] = $selectedoption_text;
                    $result[$i][] = $status;
                    $result[$i][] = $query_result['form_email_address'];
                    $result[$i][] = str_replace("–", "", $query_result['receive_payment']);
                    $result[$i][] = $label;
                    $result[$i][] = $payment_status;
                    $result[$i][] = $query_result['product_title'];
		            $result[$i][] = $query_result['insurance'] ? 'Yes' : 'No'; 
                } else if($exp_type == 'new-template') {
                    $insted_str   = array("\r\n", "\n", "\r", ",");
                    $replace_str = '';

                    // Check if the price_field has the currency symbol and remove it
                    $without_currency = strstr($price_field, '$') ? explode('$', $price_field) : $price_field;
                    $pric = str_replace(",", "", isset($without_currency[1]) ? $without_currency[1] : $without_currency);
                    $qut_price = number_format(floatval($pric),2,".",""); 
                    $total_price = $total_price + $qut_price;
                    
                    if($query_result['receive_payment']!=''&&$query_result['receive_payment']=='Zelle'){ 
                        $PAYMTHD = 'CHK';
                        $zelle_phone_number = $query_result['form_phone_number'];
                    }else{ 
                        $PAYMTHD = 'CHK'; //str_replace("–", "", $query_result['receive_payment']);
                        $zelle_phone_number = '';
                    }

                    if($query_result['chkno']!=''&&$query_result['chkno']>0){
                        $CHKNO = $query_result['chkno'];
                    }else{ 
                        $CHKNO = ''; 
                    }

                    $result[$i][] = $query_result['id'];
                    $result[$i][] = $query_result['form_first_name']." ".$query_result['form_last_name'];
                    // $result[$i][] = preg_replace ('/\r\n|\r|\n|\,/', ' ', $query_result['form_street1']);
                    $result[$i][] = str_replace($insted_str, $replace_str, $query_result['form_street1']);
                    $result[$i][] = str_replace($insted_str, $replace_str, $query_result['form_street2']);
                    $result[$i][] = str_replace(",", " ", $query_result['form_city']);
                    $result[$i][] = str_replace(",", " ", $query_result['form_state']);
                    $result[$i][] = $query_result['form_zip'];
                    $result[$i][] = 'US';
                    $result[$i][] = (string) trim($CHKNO); // frontend customization remaining

                    //NEW COLUMN VALUES
                    $result[$i][] = date("m/d/Y", strtotime($query_result['created_date']));
                    $result[$i][] = str_replace(",", "", $price_field);
                    $result[$i][] = $query_result['form_email_address'];
                    $result[$i][] = $status;
                    $result[$i][] = $query_result['product_title'];
                    $result[$i][] = $expected_condition;
                    $result[$i][] = $original_accessories;
                    $result[$i][] = $battery_ok;
                    $result[$i][] = $upgrades;
                    $result[$i][] = $hardware_issues;
                    $result[$i][] = str_replace("–", "", $query_result['receive_payment']); //$requested_payment_method;
                    $result[$i][] = $shipping_label_link;

                    $result[$i][] = $query_result['insurance'] ? 'Yes' : 'No';

                    $result[$i][] = $query_result['form_first_name'];
                    $result[$i][] = $query_result['form_last_name'];

                    $result[$i][] = '';
                    $result[$i][] = (string) trim($query_result['form_serial_number']); 
                    $result[$i][] = (string) trim($zelle_phone_number);  
                    $result[$i][] = date("m/d/Y", strtotime($query_result['created_date']));
                    $result[$i][] = $query_result['form_serial_number'];
                    $result[$i][] = $query_result['id'];
                } else {
                    $insted_str   = array("\r\n", "\n", "\r", ",");
                    $replace_str = ' ';

                    // Check if the price_field has the currency symbol and remove it
                    $without_currency = strstr($price_field, '$') ? explode('$', $price_field) : $price_field;
                    $pric = str_replace(",", "", isset($without_currency[1]) ? $without_currency[1] : $without_currency);
                    $qut_price = number_format(floatval($pric),2,".",""); 
                    $total_price = $total_price + $qut_price;

                    $ORIGBNKID = '061000227';
                    $ORIGACCT = '2000021927018';
                    if($query_result['receive_payment']!=''&&$query_result['receive_payment']=='Zelle'){ 
                        $PAYMTHD = 'CHK';
                        $EPPHNNO = $query_result['form_phone_number'];
                    }else{ 
                        $PAYMTHD = 'CHK'; //str_replace("–", "", $query_result['receive_payment']);
                        $EPPHNNO = '';
                    } 
                    if($query_result['chkno']!=''&&$query_result['chkno']>0){
                        $CHKNO = $query_result['chkno'];
                    }else{ $CHKNO = ''; }

                    $result[$i][] = $PAYMTHD;
                    $result[$i][] = 'C';
                    $result[$i][] = $query_result['id'];
                    $result[$i][] = date("m/d/Y");
                    $result[$i][] = $qut_price;
                    $result[$i][] = 'D';
                    $result[$i][] = (string) trim($ORIGACCT);
                    $result[$i][] = 'ABA';
                    $result[$i][] = (string) trim($ORIGBNKID);
                    $result[$i][] = 'PO '.$query_result['id'];
                    $result[$i][] = 'Mac of All Trades LLC';
                    $result[$i][] = '14474 Carlson Circle';
                    $result[$i][] = 'Tampa';
                    $result[$i][] = 'FL';
                    $result[$i][] = '33626';
                    $result[$i][] = 'US';
                    $result[$i][] = $query_result['form_first_name']." ".$query_result['form_last_name'];
                    // $result[$i][] = preg_replace ('/\r\n|\r|\n/', ' ', $query_result['form_street1']);
                    $result[$i][] = str_replace($insted_str, $replace_str, $query_result['form_street1']);
                    $result[$i][] = str_replace($insted_str, $replace_str, $query_result['form_street2']); 
                    $result[$i][] = '';
                    $result[$i][] = str_replace(",", " ", $query_result['form_city']);
                    $result[$i][] = str_replace(",", " ", $query_result['form_state']);
                    $result[$i][] = $query_result['form_zip'];
                    $result[$i][] = 'US';
                    $result[$i][] = (string) trim($CHKNO); // frontend customization remaining
                    $result[$i][] = 'SR0000CL2920CS3672';
                    $result[$i][] = '100';
                    $result[$i][] = (string) trim($ORIGBNKID);
                    $result[$i][] = $query_result['form_first_name'];
                    $result[$i][] = $query_result['form_last_name'];
                    /*
                    if($query_result['receive_payment'] == 'Zelle'){
                        $result[$i][] = '';
                    }else{ 
                        if($query_result['form_email_address']=='false'||$query_result['form_email_address']==''||$query_result['form_email_address']==null)
                        { $result[$i][] = ''; }else{ $result[$i][] = $query_result['form_email_address']; }
                        // $result[$i][] = 'emailtokenaddress@email.com'; 
                    } */
                    $result[$i][] = '';
                    $result[$i][] = (string) trim($query_result['form_serial_number']); 
                    $result[$i][] = (string) trim($EPPHNNO);  
                    $result[$i][] = $query_result['id'];
                    $result[$i][] = date("m/d/Y", strtotime($query_result['created_date']));
                    $result[$i][] = $query_result['form_serial_number'];
                    $result[$i][] = $query_result['id'];
                    $result[$i][] = 'IV';
                    $result[$i][] = 'N';
                }
                $i++;
                
                if($exp_type!='normal' && $i>$data_cnt){
                    $result[$i][] = 'TRAILER';  
                    $result[$i][] = (string) trim($data_cnt); 
                    $result[$i][] = $total_price;    
                    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = ''; 
                    $result[$i][] = ''; $result[$i][] = '';  $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';    $result[$i][] = '';
                   
                }
            }

            
 
            $result = array_values($result);

            if($exp_type == 'normal'){
                $export_type="bulk";
                $csv_name = 'bulk_quotes.csv';
            } else if($exp_type == 'new-template') {
                $export_type="new_template";
                $csv_name = 'exports/acumatica.'.date('HisYmd').'.csv';
            }else{
                $export_type="well_fargo";
                $csv_name = 'wells_fargo_exports/mact.'.date('HisYmd').'.csv';
            }
 
            $this->array_to_csv_download($result,$csv_name,$export_type);
        }

    }

    public function array_to_csv_download($array, $filename, $export_type, $delimiter = ";"){
        if($export_type == 'well_fargo' || $export_type == "new_template"){ 
            $directory = $export_type == "new_template" ? "exports" : "wells_fargo_exports";

            if (!is_dir($directory)) {
                mkdir($directory, 0755, true); // 'true' for recursive creation if needed
            }

            $fh = fopen($filename, 'w');

            foreach($array as $data){ 
                $num = count($data) ;    
                $last = $num - 1;
                for($i = 0; $i < $num; $i++) {            
                    fwrite($fh, $data[$i]);                       
                    if ($i != $last) {
                        fwrite($fh, ",");
                    }
                }                                                                 
                fwrite($fh, "\r"); //\n
            }
            fclose($fh);

            $fileNameOnly = explode('/', $filename);
            $fileNameOnly = $fileNameOnly[$this->array_key_last($fileNameOnly)];

            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment;filename="' . $fileNameOnly . '";');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            // header('Content-Length: ' . filesize($file));
            // header("Content-Type: text/plain");
            header("Content-Type: text/csv");
            readfile($filename);

        }else{  
            header('Content-Encoding: UTF-8');
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');

            print $CSVData = $this->arrayToCSV($array);
            exit; 

            $f = fopen('php://output', 'wt');  
            foreach ($array as $line) {
                fputcsv($f, $line);
            }
            fclose($f);  
            exit;
        }

    }

    public function array_to_csv_download_old($array, $filename = "export.csv", $delimiter = ";")
    {
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="exports/' . $filename . '";');

        // header('Content-Description: File Transfer');
        // header('Content-Type: application/octet-stream');
        // header('Content-Disposition: attachment; filename=file.csv');
        // header('Content-Transfer-Encoding: binary');
        // header('Expires: 0');
        // header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        // header('Pragma: public');
        // open the "output" stream
        // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
        $f = fopen('php://output', 'w');

        foreach ($array as $line) {
            fputcsv($f, $line);
        }

        exit;
    }

    public function arrayToCSV($inputArray){
        $csvFieldRow = array();
        foreach ($inputArray as $CSBRow) {
            $csvFieldRow[] = $this->str_putcsv($CSBRow);
        }
        $csvData = implode("\r", $csvFieldRow);
        return $csvData;
    }
    
    public function str_putcsv($input, $delimiter = ',', $enclosure = '"'){
         
        // Open a memory "file" for read/write
        $fp = fopen('php://output', 'wt');
        // Write the array to the target file using fputcsv()
        fputcsv($fp, $input, $delimiter, $enclosure);
        // Rewind the file
        // rewind($fp);
        // File Read
        $data = fread($fp, 1048576);
        fclose($fp); 
        // Ad line break and return the data
        return rtrim($data, "\r");
    }

    // get serial number by propduct id
    public function serial_by_product_id($product_id)
    {
        $serial_qry = $this->db->query("SELECT `serial` FROM ".$this->serial_table." WHERE bc_product_id='".$product_id."' ");
        return $serial_qry->result_array();

    }

    public function updatePhoneNumber()
    {
        $post = $this->input->post();
        if($post['phone_number']=='') {
            $response = array('message' => 'Please enter phone number.', 'status' => 0);
        } else {
            $getquotedetails = $this->getquotedetails($post['quote_id']);
            $data = array('form_phone_number' => $post['phone_number'], 'modified_date' => $this->currentdate);
            $this->db->where('id', $post['quote_id']);
            $this->db->update($this->quote_table, $data);
            $this->db->trans_complete();
            if($this->db->trans_status() === FALSE) {
                $response = array('message' => 'Phone number not updated successfully.', 'status' => 0);
            } else {
                $response = array('message' => "Phone number updated successfully.",'status' => 1);
            }
        }
        return json_encode($response);
    }

    public function updatePaymentMethod()
    {
        $payment_methods = [
            'Check – US First Class Mail (free)',
            'PayPal – seller pays applicable PayPal fees',
            'Zelle',
            'macofalltrades.com Store Credit',
        ];
        $post = $this->input->post();
        if($post['payment_method']=='') {
            $response = array('message' => 'Please select a payment method.', 'status' => 0);
        } elseif(!in_array($post['payment_method'], $payment_methods)) {
            $response = array('message' => 'The selected payment method was not valid.', 'status' => 0);
        } else {
            $getquotedetails = $this->getquotedetails($post['quote_id']);
            $data = array('receive_payment' => $post['payment_method'], 'modified_date' => $this->currentdate);
            $this->db->where('id', $post['quote_id']);
            $this->db->update($this->quote_table, $data);
            $this->db->trans_complete();
            if($this->db->trans_status() === FALSE) {
                $response = array('message' => 'Payment method not updated successfully.', 'status' => 0);
            } else {
                $response = array('message' => "Payment method updated successfully.",'status' => 1);
            }
        }
        return json_encode($response);
    }
    
    // update offered price
    public function updateOfferPrice()
    {
        $post = $this->input->post();
        if($post['offered_price']==''){
            $response = array('message' => "Please enter offered price.",'status' => 0); 
        }else if (isset($post['offered_price']) && $post['offered_price'] <= 0) {
            $response = array('message' => "Please enter valid offered price.",'status' => 0); 
        }else{
            $getquotedetails = $this->getquotedetails($post['quote_id']);
            $emailtemplete_tmp = $this->quoteEmailTemplate();
            $emailtemplete = $this->getBcConfig();
            $offered_price_tmp = '$'.number_format($post['offered_price'],2,".","");
            $data = array('offered_price' => $offered_price_tmp, 'modified_date' => $this->currentdate);

            // device serial number
           /* $serial_no = '';
            if (isset($getquotedetails['customerinfo']['form_serial_number']) && !empty($getquotedetails['customerinfo']['form_serial_number'])) {
                $serial_no = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Serial Number: </b> '.$getquotedetails['customerinfo']['form_serial_number'].'</p>';
            }
            // offered price
            $offered_price = '';
            if (isset($getquotedetails['customerinfo']['offered_price']) && !empty($getquotedetails['customerinfo']['offered_price'])) {
                $offered_price = '<p style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif; font-size: 13px;"><b style="color: rgb(54, 54, 54); font-family: Arial, sans-serif, Helvetica, Arial, sans-serif;">Offered Price: </b> '.$offered_price_tmp.'</p>';
            }

            $htmlConten = str_replace(array('{{name}}',
                '{{category_name}}',
                '{{product_name}}',
                '{{serial_number}}',
                '{{price}}',
                '{{options}}',
                '{{label_image}}', 'Category Name:', '{{PO}}'),
                array($getquotedetails['customerinfo']['form_first_name'] . ' ' . $getquotedetails['customerinfo']['form_last_name'],
                    '',
                    $getquotedetails['product']['product_title'],
                    $serial_no,
                    $offered_price,
                    '',
                    '',
                    '',
                    $post['quote_id'],
            ), $emailtemplete_tmp);
           */

            $this->db->where('id', $post['quote_id']);
            $this->db->update($this->quote_table, $data);
            $this->db->trans_complete();
            if($this->db->trans_status() === FALSE)
            {
                $response = array('message' => "Offered Price not updated successfully.",'status' => 0); 
            }else{
                $no_knockout = '';
                $approved_stat = '';
                $response = array('message' => "Offered Price updated successfully.",'status' => 1);
                
                /*if ($getquotedetails['customerinfo']['knockout'] == 0 && $getquotedetails['customerinfo']['contact_flag'] == 1) {
                    // No Knockout
                    $no_knockout = 1;
                }else{
                    // Knockout
                    if($getquotedetails['customerinfo']['knockout'] > 0 && $getquotedetails['customerinfo']['contact_flag'] == 1){
                        //not approved
                        $approved_stat = 0;
                    }else{
                        //approved
                        $approved_stat = 1;
                    }
                }

                if($no_knockout === 1 || $approved_stat === 1){
                    // echo $htmlConten;
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
                    $this->email->from($emailtemplete['smtp_user'], "Mac Me An Offer");
                    $this->email->to($getquotedetails['customerinfo']['form_email_address']);
                    $this->email->reply_to($emailtemplete['smtp_user']);
                    $this->email->subject('Your Estimate is Approved!');
                    $this->email->message($htmlConten);
                    if ($this->email->send()) {
                        // echo 'Your Email has been successfully sent.';
                    } else { 
                        // show_error($this->email->print_debugger()); 
                    }
                }*/

            }
        }
        return json_encode($response);
    }

    public function quoteEmailTemplate(){
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
        return $emailtemplete_tmp;
    }

    public function change_payment_status(){
        $post = $this->input->post();
        $set_data = array('payment_status' => $post['payment_status'], 'modified_date' => $this->currentdate);
        $this->db->where('id', $post['quote_id']);
        $this->db->update($this->quote_table, $set_data);
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE)
        {
            $response = array('message' => "Payment status not updated successfully.",'status' => 0); 
        }else{ 
            $response = array('message' => "Payment status updated successfully.",'status' => 1); 
        }
        echo json_encode($response);
    }

    public function purgeOldExports()
    {
        $exportsFolder = 'exports/';
        $daysThreshold = 14;
        $currentTimestamp = time();
         
        $files = glob($exportsFolder . '*');
        foreach ($files as $file) {
             
            if (is_file($file)) {
                 
                $modifiedTimestamp = filemtime($file);
                $differenceInSeconds = $currentTimestamp - $modifiedTimestamp;
                $differenceInDays = floor($differenceInSeconds / (60 * 60 * 24));
                
                if ($differenceInDays > $daysThreshold) {
                    unlink(FCPATH . $file);
                    echo "Deleted file: ".FCPATH.$file."\n";
                }
            }
        }
    }

    protected function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }
        
        return array_keys($array)[count($array)-1];
    }

}
 
