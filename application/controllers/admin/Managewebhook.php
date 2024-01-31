<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class Managewebhook extends CI_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('bigcommerceapi');
        $this->load->library('mcurl');
        $this->load->model('admin/managewebhookmodel');
        //$this->load->model('admin/bigcommerceimportmodel');
        $this->created_hook  	 = "created_hook";
    }

    public function index()
    {
        $this->data["page_title"] = 'View WebHooks';
        $this->data["page_head"] = 'View WebHooks';

        $this->data["app_data"] = $this->managewebhookmodel->GetAPPdetails();
        $this->data["webhook_data"] = $this->managewebhookmodel->GetWebHooks();

        $this->load->view('admin/common/leftmenu', $this->data);
        $this->load->view("admin/common/header", $this->data);
        $this->load->view("admin/managewebhook/list", $this->data);
        $this->load->view("admin/common/footer");
    }

    public function add()
    {

        $this->data["page_title"] = 'Add New WebHooks';
        $this->data["page_head"] = 'Add New WebHooks';
        $this->data["page_view"] = 'Add New WebHooks';

        $this->data["formdata"] = array(
            "id" => "",
            "scope" => "",
            "destination" => "",
        );

        $this->form_validation->set_rules("scope", "scope", "required");

        if ($this->form_validation->run() == true) {

            $id = $this->managewebhookmodel->insert_record();

            if (isset($id['status']) && !empty($id['status']) && $id['status'] == 'Success') {
                $this->session->set_userdata('succe', '1');
                redirect('admin/managewebhook');
            } else if (isset($id['status']) && !empty($id['status']) && $id['status'] == 'Error') {
                $this->session->set_userdata('error', $id['Message']);
                redirect('admin/managewebhook');
            }

        }

        $scope = $this->input->post("scope");

        if (isset($scope) && !empty($scope)) {
            $this->data["formdata"] = array(
                "id" => "",
                "scope" => $this->input->post("scope"),
                "destination" => $this->input->post("destination"),
            );
        }

        $this->load->view('admin/common/leftmenu', $this->data);
        $this->load->view("admin/common/header", $this->data);
        $this->load->view("admin/managewebhook/add", $this->data);
        $this->load->view("admin/common/footer");

    }

    public function edit()
    {
        $this->data["page_title"] = 'Edit WebHooks';
        $this->data["page_head"] = 'Edit WebHooks';
        $this->data["page_view"] = 'Edit WebHooks';

        $id = $this->input->get('id');

        if (!empty($id)) {
            $this->data["formdata"] = $this->managewebhookmodel->single_webhook($id);
        }

        $this->load->view('admin/common/leftmenu', $this->data);
        $this->load->view("admin/common/header", $this->data);
        $this->load->view("admin/managewebhook/edit", $this->data);
        $this->load->view("admin/common/footer");

    }

    public function update()
    {
        $this->form_validation->set_rules("scope", "scope", "required");

        $hook_id = $this->input->post('hook_id');

        if ($this->form_validation->run() == true) {

            $id = $this->managewebhookmodel->update_record($hook_id);

            if (isset($id['status']) && !empty($id['status']) && $id['status'] == 'Success') {
                $this->session->set_userdata('updatescc', '1');
                redirect('admin/managewebhook');
            } else if (isset($id['status']) && !empty($id['status']) && $id['status'] == 'Error') {
                $this->session->set_userdata('error', $id['Message']);
                redirect('admin/managewebhook');
            }

        }

    }

    public function delete($id = '')
    {

        $hook_id = $this->input->get('id');

        if (isset($hook_id) && !empty($hook_id)) {

            $id = $this->managewebhookmodel->delete_record($hook_id);

            if (isset($id['status']) && !empty($id['status']) && $id['status'] == 'Success') {
                $this->session->set_userdata('succ_delete', '1');
                redirect('admin/managewebhook');
            } else if (isset($id['status']) && !empty($id['status']) && $id['status'] == 'Error') {
                $this->session->set_userdata('error', $id['Message']);
                redirect('admin/managewebhook');
            }
        } else {
            redirect('admin/managewebhook');
        }
    }

    public function getwebhooklive()
    {
        $get_live_hook_details = $this->managewebhookmodel->GetLiveWebhook();

        echo '<pre>';
        print_r($get_live_hook_details);
        echo '</pre>';
    }

    public function createhookmmt()
    {
        $product_hook = array();
        //$product_hook['scope']          = 'store/customer/*';
        $product_hook['scope'] = 'store/order/*';

        $product_hook['is_active'] = true;
        $product_hook['destination'] = 'https://app.macmeanoffer.com/admin/webhookcustomer/orderwebhook';

        //$product_hook['destination'] = 'https://app.macmeanoffer.com/admin/webhookcustomer/customerhookmmt';

        $store_url = 'https://api.bigcommerce.com/';
        $store_hash = 'stores/xt5en0q8kf';
        $product_hook_create_url = $store_url . $store_hash . '/v2/hooks';
        $client_id = '46s39g803b8wd2rexb6oecgo0bio898';
        $apitoken = '9dbhxept77e8besu4i1x1vp55w06ifk';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $product_hook_create_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', 'X-Auth-Client: ' . $client_id . '', 'X-Auth-Token: ' . $apitoken));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($product_hook));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        $product_hook_res = json_decode($response);

        echo "<pre>";
        print_r($product_hook_res);
        exit;

    }

    public function gethookmmt()
    {
        $store_url = 'https://api.bigcommerce.com/';
        $store_hash = 'stores/xt5en0q8kf';
        $product_hook_create_url = $store_url . $store_hash . '/v2/hooks';
        $client_id = '46s39g803b8wd2rexb6oecgo0bio898';
        $apitoken = '9dbhxept77e8besu4i1x1vp55w06ifk';

        $api_url = $product_hook_create_url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-Auth-Client: ' . $client_id . '', 'X-Auth-Token: ' . $apitoken));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $product_hook_res = json_decode($response);

        echo "<pre>";
        print_r($product_hook_res);
        exit;
    }

    public function deletehookmmt()
    {

        $store_url = 'https://api.bigcommerce.com/';
        $store_hash = 'stores/4bcsbfgatc';
        $product_hook_create_url = $store_url . $store_hash . '/v2/hooks/20420316';
        $client_id = '5fh1l0dx29or3vsdlit8ehvlx35zhzy';
        $apitoken = '4vzn4a5ivyt8rdbc80qppgvwn7bdgv3';

        $api_url = $product_hook_create_url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-Auth-Client: ' . $client_id . '', 'X-Auth-Token: ' . $apitoken));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $product_hook_res = json_decode($response);

        echo "<pre>";
        print_r($product_hook_res);
        exit;
    }

    public function createhookida()
    {
        $product_hook = array();
        $product_hook['scope'] = 'store/customer/*';
        //$product_hook['scope']          = 'store/order/*';

        $product_hook['is_active'] = true;
        //$product_hook['destination'] = 'https://app.macmeanoffer.com/admin/webhookcustomer/orderwebhook';

        $product_hook['destination'] = 'https://app.macmeanoffer.com/admin/webhookcustomer/customergroup';

        $store_url = 'https://api.bigcommerce.com/';
        $store_hash = 'stores/fo3wvak';
        $product_hook_create_url = $store_url . $store_hash . '/v2/hooks';
        $client_id = '4n9jkt363m13lcwp6ujmf7aklqwjphw';
        $apitoken = '1d4lugaf0a9ii8gatbdmkt1atpq3827';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $product_hook_create_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', 'X-Auth-Client: ' . $client_id . '', 'X-Auth-Token: ' . $apitoken));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($product_hook));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        $product_hook_res = json_decode($response);

        echo "<pre>";
        print_r($product_hook_res);
        exit;

    }

    public function gethookmmtida()
    {
        $store_url = 'https://api.bigcommerce.com/';
        $store_hash = 'stores/fo3wvak';
        $product_hook_create_url = $store_url . $store_hash . '/v2/hooks';
        $client_id = '4n9jkt363m13lcwp6ujmf7aklqwjphw';
        $apitoken = '1d4lugaf0a9ii8gatbdmkt1atpq3827';

        $api_url = $product_hook_create_url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-Auth-Client: ' . $client_id . '', 'X-Auth-Token: ' . $apitoken));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $product_hook_res = json_decode($response);

        echo "<pre>";
        print_r($product_hook_res);
        exit;
    }

    public function deletehookmmtida()
    {

        $store_url = 'https://api.bigcommerce.com/';
        $store_hash = 'stores/fo3wvak';
        $product_hook_create_url = $store_url . $store_hash . '/v2/hooks/20120936';
        $client_id = '4n9jkt363m13lcwp6ujmf7aklqwjphw';
        $apitoken = '1d4lugaf0a9ii8gatbdmkt1atpq3827';
        $api_url = $product_hook_create_url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-Auth-Client: ' . $client_id . '', 'X-Auth-Token: ' . $apitoken));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $product_hook_res = json_decode($response);

        echo "<pre>";
        print_r($product_hook_res);
        exit;
    }

    public function updatecronfile()
    {
        $store_url = 'https://api.bigcommerce.com/';
        $store_hash = 'stores/xt5en0q8kf';
        $product_hook_create_url = $store_url . $store_hash . '/v2/hooks';
        $client_id = '46s39g803b8wd2rexb6oecgo0bio898';
        $apitoken = '9dbhxept77e8besu4i1x1vp55w06ifk';

        $api_url = $product_hook_create_url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-Auth-Client: ' . $client_id . '', 'X-Auth-Token: ' . $apitoken));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $webhookupdate = json_decode($response);
        $this->updatedata($webhookupdate);
    }

    public function updatedata($webhookupdate)
    {
        if (isset($webhookupdate) && !empty($webhookupdate)) {
            foreach ($webhookupdate as $webhook_update) {
               
        echo "<pre>";
        print_r($webhook_update);
        exit;
            }   
        }

    }

    public function updateAllwebhook(){
		$config_data = $this->managewebhookmodel->GetAPPdetails();
		$data = $this->db->query("SELECT * FROM `".$this->created_hook."` ");
		$hookdata = $data->result_array(); 

        // echo "<pre>";print_r($config_data);print_r($hookdata);die;
         
        foreach ($hookdata as $hookvalue) {
		
            if($hookvalue['store']=='mmo'){
                $apipath = $config_data['apipath'];
                $apitoken = $config_data['apitoken'];
            }else if($hookvalue['store']=='mmt'){
                $apipath = $config_data['apipathtrades'];
                $apitoken = $config_data['apitokentrades'];
            }
 
			$curl = curl_init(); 
			curl_setopt_array($curl, array(
			CURLOPT_URL => $apipath."hooks/".$hookvalue['hook_id'],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST=> 0,
			CURLOPT_SSL_VERIFYPEER=> 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "PUT",
			CURLOPT_POSTFIELDS => "{\"is_active\":true}",
			CURLOPT_HTTPHEADER => array(
			"accept: application/json",
			"content-type: application/json",
			"x-auth-token: ".$apitoken
			),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				echo "cURL Error #:" . $err;
			} else { 
                $res = json_encode( json_decode( $response ), JSON_PRETTY_PRINT );
				echo $res; 
			}
		}
	}


}
