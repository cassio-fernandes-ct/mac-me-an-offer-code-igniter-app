<?php
class Shippinglabel_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
            $this->SHIPPING_LABEL_TABLE = "shipping_label";
            $this->SHIPPING_LABEL_TABLE_BETA = "shipping_label_beta";
    }
	 
    public function add_new_shipping($data)
    {
        $this->db->insert($this->SHIPPING_LABEL_TABLE , $data);
        return $this->db->insert_id();
    }

    public function add_new_shipping_beta($data)
    {
        $this->db->insert($this->SHIPPING_LABEL_TABLE_BETA , $data);
        return $this->db->insert_id();
    }

}
