<?php
use Bigcommerce\Api\Client as Bigcommerce;
class Guestcustomoffer extends CI_Controller{
	public function __construct()
    {
        parent::__construct();
       
        $this->load->model('guestcustomoffermodel');
        $this->load->library('bigcommerceapi');
		$this->load->library('mcurl');
		//ini_set('display_errors','On');
		//error_reporting(E_ALL);
		include(APPPATH.'third_party/bcapi/vendor/autoload.php');
        header('Access-Control-Allow-Origin: *'); 

    }
    public function index()
    {
        $this->load->view('serialNumberSearchBar');
    }

    public function checkexistemail()
    {
        $email = $this->input->post('email');
        //$email = 'testing@1digitalagency.com';

        $customerdetails = $this->guestcustomoffermodel->checkexistemail($email);
        if(isset($customerdetails['bc_id_mmo']) && !empty($customerdetails['bc_id_mmo']))
        {
            $data = $this->getcustomer($customerdetails['bc_id_mmo']);
        }else
        {
            $data = '';
        }
        echo json_encode($data);
        exit;
    }

    public function getcustomer($bc_id_mmo)
    {
        $config_data = $this->guestcustomoffermodel->getBcConfig();
        $bcstoreurl		= $config_data['storeurl'];
		$client_id		= $config_data['client_id'];
		$store_hash		= $config_data['storehas'];
		$auth_token		= $config_data['apitoken'];

	
		Bigcommerce::configure(array( 'client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash )); 			
		Bigcommerce::verifyPeer(false); 
        Bigcommerce::failOnError(); 
        
        $getcustomer = Bigcommerce::getCustomer($bc_id_mmo);
        $getcustomeraddress = Bigcommerce::getCustomerAddresses($bc_id_mmo);
        
        $customerdata = array();
        if(isset($getcustomer) && !empty($getcustomer))
        {
            $customerdata['bc_id_mmo'] = $bc_id_mmo;
            $customerdata['first_name'] = $getcustomer->first_name;
            $customerdata['last_name']  = $getcustomer->last_name;
           
        }

        if(isset($getcustomeraddress) && !empty($getcustomeraddress))
        {
            $customerdata['phone']  = $getcustomeraddress{0}->phone;
            $customerdata['street_1']  = $getcustomeraddress{0}->street_1;
            $customerdata['street_2']  = $getcustomeraddress{0}->street_2;
            $customerdata['city']  = $getcustomeraddress{0}->city;
            $customerdata['country_iso2']  = $getcustomeraddress{0}->country_iso2;
            $customerdata['state']  = $this->statearray($getcustomeraddress{0}->state);
            $customerdata['zip']  = $getcustomeraddress{0}->zip;
        }
        return  $customerdata;
    }
    
    public function statearray($state)
    {

        $data['Alabama'] = 'AL';
        $data['Alaska'] = 'AK';
        $data['Arizona'] = 'AZ';
        $data['Arkansas'] = 'AR';
        $data['California'] = 'CA';
        $data['Colorado'] = 'CO';
        $data['Connecticut'] = 'CT';
        $data['Delaware'] = 'DE';
        $data['District of Columbia'] = 'DC';
        $data['Florida'] = 'FL';
        $data['Georgia'] = 'GA';
        $data['Georgia'] = 'GA';
        $data['Hawaii'] = 'HI';
        $data['Idaho'] = 'ID';
        $data['Illinois'] = 'IL';
        $data['Indiana'] = 'IN';
        $data['Iowa'] = 'IA';
        $data['Kansas'] = 'KS';
        $data['Kentucky'] = 'KY';
        $data['Louisiana'] = 'LA';
        $data['Maine'] = 'ME';
        $data['Maryland'] = 'MD';
        $data['Massachusetts'] = 'MA';
        $data['Michigan'] = 'MI';
        $data['Minnesota'] = 'MN';
        $data['Mississippi'] = 'MS';
        $data['Missouri'] = 'MO';
        $data['Montana'] = 'MT';
        $data['Nebraska'] = 'NE';
        $data['Nevada'] = 'NV';
        $data['New Hampshire'] = 'NH';
        $data['New Jersey'] = 'NJ';
        $data['New Mexico'] = 'NM';
        $data['New York'] = 'NY';
        $data['North Carolina'] = 'NC';
        $data['North Dakota'] = 'ND';
        $data['Ohio'] = 'OH';
        $data['Oklahoma'] = 'OK';
        $data['Oregon'] = 'OR';
        $data['Pennsylvania'] = 'PA';
        $data['Rhode Island'] = 'RI';
        $data['South Carolina'] = 'SC';
        $data['South Dakota'] = 'SD';
        $data['Tennessee'] = 'TN';
        $data['Texas'] = 'TX';
        $data['Utah'] = 'UT';
        $data['Vermont'] = 'VT';
        $data['Virginia'] = 'VA';
        $data['Washington'] = 'WA';
        $data['West Virginia'] = 'WV';
        $data['Wisconsin'] = 'WI';
        $data['Wyoming'] = 'WY';

        return $data[$state];
    }
}