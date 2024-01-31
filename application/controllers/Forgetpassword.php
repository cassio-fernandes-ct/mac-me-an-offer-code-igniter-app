<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
use Bigcommerce\Api\Client as Bigcommerce;
class Forgetpassword extends CI_Controller {
    protected $perPage;

	function __construct()
	{	
		parent::__construct();
		header('Access-Control-Allow-Origin: *'); 
		$this->load->model("forgetpasswordmodel");
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');
		$this->load->library('email');
		ini_set('display_errors','On');
		error_reporting(E_ALL);
		include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function index(){

	}

    public function urldaynamicdemo(){

        $data['store'] = 'mmo';
        $data['email'] = 'development.qatesting@gmail.com';
        $customerIdAndToken    = $this->forgetpasswordmodel->getCustomerIdFromEmail($data);
        $this->emailitemadmin($customerIdAndToken,$data['email'],$data['store']);
    }

	public function forgetPasswordEmailSend()
	{
		$data['email'] = $this->input->post('email');
		$data['store'] = $this->input->post('store');

		//$data['store'] = 'mmo';
		//$data['email'] = 'testing@1digitalagency.com';

		$customerIdAndToken    = $this->forgetpasswordmodel->getCustomerIdFromEmail($data);
		$this->emailitemadmin($customerIdAndToken,$data['email'],$data['store']);
        
	}


	public function emailitemadmin($customerIdAndToken,$email,$store)
    {
     
     	
        $emailtemplete = $this->forgetpasswordmodel->getBcConfig();
       
        if(isset($customerIdAndToken['customer_id']) && !empty($customerIdAndToken['customer_id']) && isset($customerIdAndToken['forget_password_token']) && !empty($customerIdAndToken['forget_password_token']))
        {
            if($store == 'mmo')
            {
                $url = $emailtemplete['mmo_url'].'/login.php?action=change_password&c='.$customerIdAndToken['customer_id'].'&t='.$customerIdAndToken['forget_password_token'];

                $subject = 'Password change request for Mac Me An Offer';
                $name = 'Mac Me An Offer';
            }else{

                $url = $emailtemplete['mmt_url'].'/login.php?action=change_password&c='.$customerIdAndToken['customer_id'].'&t='.$customerIdAndToken['forget_password_token'];

                $subject = 'Password change request for Mac Of All Trades';
                 $name = 'Mac Of All Trades';
            }
           

            $htmlConten = '<p>To change your customer account password at '.$name.' please click this link or copy and paste it into your browser: </p><br><br><br>'.$url;

            $config['protocol']    = $emailtemplete['protocol'];
            $config['smtp_user']   = $emailtemplete['smtp_user'];
            $config['smtp_port']   = $emailtemplete['smtp_port'];
            $config['smtp_host']   = $emailtemplete['smtp_host'];
            $config['smtp_pass']   = $emailtemplete['smtp_pass'];
            $config['smtp_crypto'] = 'ssl';
            $config['charset']     = 'iso-8859-1';
            $config['wordwrap']    = TRUE; 

            $this->email->initialize($config);
            $this->email->set_mailtype("html");
            $this->email->set_newline("\r\n");
            $this->email->from('tradein@macofalltrades.com',$name);
            $this->email->to($email);
            $this->email->reply_to('info@mac-me-an-offer.mybigcommerce.com');
            
            $this->email->subject($subject);
            $this->email->message($htmlConten);
           
            if ($this->email->send()) {
                echo 'Your Email has successfully been sent.';
            } else {
                show_error($this->email->print_debugger());
            }
        }else{

            echo "<pre>";
            print_r($customerIdAndToken);
            exit;
        }
    }

    public function forgetPasswordChange()
    {
        $password = $this->input->post();

       /*$password['password'] = 'test@321';
        $password['store']    = 'mmo';
        $password['c']        = '190082';
        $password['t']        = 'c7CyJ9eodf5DgnwThXQkHAI3iOM80Kb2xVPRmqa1lNLYvtspZS'; */
        //$password['password'] = '';
        //$password['store']    = 'mmo';

        $response = array();
        if(isset($password['password']) && !empty($password['password']) && isset($password['c']) && !empty($password['c']))
        {
           $response['customerIdAndToken']    = $this->forgetpasswordmodel->forgetPasswordChange($password);
        }
       
        echo json_encode($response);  
    }

    public function checklinkvalid(){

       $password = $this->input->post();
        if(isset($password['c']) && !empty($password['c']))
        {
           $response = $this->forgetpasswordmodel->tokenIsValidOrNot($password);
           echo json_encode($response);  
          
       }
    }

    public function logintommt(){
    
        $data = $this->input->post();
        
        $response = $this->forgetpasswordmodel->logintommt($data);
         echo json_encode($response);  
    }

    public function logintommo(){
    
        $data = $this->input->post();
        
        $response = $this->forgetpasswordmodel->logintommo($data);
         echo json_encode($response);  
    }

}
?>