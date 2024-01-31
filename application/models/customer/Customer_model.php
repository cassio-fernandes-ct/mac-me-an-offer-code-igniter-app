<?php
class Customer_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
            $this->CUSTOMER_TABLE = "customers";
    }
	 
    public function register($data)
    {
        return $this->db->insert($this->CUSTOMER_TABLE , $data);
    }

}