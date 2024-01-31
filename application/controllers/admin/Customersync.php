<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//we need to call PHP's session object to access it through CI
use Bigcommerce\Api\Client as Bigcommerce;
class Customersync extends CI_Controller {
    protected $perPage;

	function __construct()
	{	
		parent::__construct();
		$this->load->model("admin/customersyncmodel");
		$this->load->library('bigcommerceapi');
		$this->load->library('mcurl');
		
		include(APPPATH.'third_party/bcapi/vendor/autoload.php');
	}

	public function index()
	{
		$this->data['title'] = 'Customer';

		$this->data['totalcustomermmo']  = $this->customersyncmodel->getTotalNuamberOfCustomerOrder_mmo();
		$this->data['totalcustomermmt']  = $this->customersyncmodel->getTotalNuamberOfCustomerOrder_mmt();
		$this->data['noSyncMmtIdGet']    = $this->customersyncmodel->noSyncMmtIdGet();
		$this->data['noSyncMmoIdGet']    = $this->customersyncmodel->noSyncMmoIdGet();
		$this->data['noSyncMmtIdGetD']    = $this->customersyncmodel->noSyncMmtIdGetD();
		$this->data['noSyncMmoIdGetD']    = $this->customersyncmodel->noSyncMmoIdGetD();
		
		$this->load->view('admin/common/leftmenu',$this->data);
        $this->load->view('admin/common/header');
        $this->load->view('admin/customersync/list',$this->data);
        $this->load->view('admin/common/footer');
	}
}
?>