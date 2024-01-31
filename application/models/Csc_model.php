<?php
class Csc_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
           
            $this->STATE_TABLE = "states";
    }
	 
    public function get_state_data($state_code)
    {
        $data =  $this->db->from($this->STATE_TABLE)
                ->where('state_code' , $state_code)
                ->get()
                ->row_array();
        if(empty($data))
        {
            print_r(['error'=>'State not available']);
            exit;
        }
        else
            return $data;
    }

}
