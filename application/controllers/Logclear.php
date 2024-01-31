<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('max_execution_time', 300000			);
ini_set('post_max_size', '99999M');
ini_set('upload_max_filesize', '99999M');
ini_set('memory_limit', '-1');

class Logclear extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//$this->load->model('logclearmodel');
	}

	public function index()
	{
		$path = '/var/www/html/application/uploads/ups/shipping/';
	    if ($handle = opendir($path)) {

	        while (false !== ($file = readdir($handle))) 
	        { 
	            $filelastmodified = filemtime($path . $file);
	            
	            //check if file older than 90 days
	            if((time() - $filelastmodified) > (60 * 60 * 24 * 90))
	            {
	                if( $file != '.' and $file != '..' )
	                {
	                    unlink($path . $file);
	                    echo $path . $file.' Deleted. <br>';
	                }
	            }

	        }
	        closedir($handle); 
	    }        

	}

	
}
