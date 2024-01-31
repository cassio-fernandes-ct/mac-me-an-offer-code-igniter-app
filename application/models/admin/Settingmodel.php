<?php

class Settingmodel extends CI_Model
{
    public function __construct()
    {
        $this->setting_table = 'setting';
         $this->load->database();
    }

    public function getSettingData($id)
    {
        $query = $this->db->get_where($this->setting_table, array('id' => $id));

        return $query->row_array();
    }


    /**
     * Get list of user accounts
     * 
     * @return array Array of user objects
     */
    public function get_user_accounts()
    {
        $user_accounts = [];
        
        $this->db->select( 'id, username, firstname, lastname, email, role' );
        $query = $this->db->get_where( 
            'users',
            [
                'status' => 'yes'
            ]
        );

        foreach( $query->result() as $user_account ) {
            $user_accounts[] = $user_account;
        }

        return $user_accounts;
    }


    public function delete_images($image)
    {
        $uploaddir = FCPATH.'application/uploads/sitelogo/';
        @unlink($uploaddir.'original/'.$image);
        @unlink($uploaddir.'thumb400/'.$image);
        @unlink($uploaddir.'thumb300/'.$image);
        @unlink($uploaddir.'thumb200/'.$image);
        @unlink($uploaddir.'thumb100/'.$image);
        @unlink($uploaddir.'thumb50/'.$image);

        $query = $this->db->query('select id from '.$this->setting_table." WHERE logo_image  ='".$image."'");
        $product_id = $query->row_array();
        $data = array(
            'logo_image ' => '',
        );
        $this->db->where('id', $product_id['id']);
        $this->db->update($this->setting_table, $data);
    }

    public function update_record()
    {
        $currentdate = date('Y-m-d H:i:s');
		
        $data = array(
            'storeurl' => $this->input->post('storeurl'),
			'store_front_url' => $this->input->post('store_front_url'),
			'apiusername' => $this->input->post('apiusername'),
			'apipath' => $this->input->post('apipath'),
			'apitoken' => $this->input->post('apitoken'),
			'storehas' => $this->input->post('storehas'),
			'client_id' => $this->input->post('client_id'),
			'client_secret' => $this->input->post('client_secret'),
           
            'date' => $currentdate,
        );
		
        $this->db->where('id', '1');
        $this->db->update($this->setting_table, $data);
		
		$bannerimages = $this->input->post("banner_images");
		if(isset($bannerimages) && !empty($bannerimages))
		{
			foreach($bannerimages as $banner_image)
			{
				$this->db->query("update ".$this->setting_table." set logo_image ='".$banner_image."' where id ='1'");
			}
		}
		$id = '1';
		return $id;
	}

    public function update_shipping_settings($data)
    {
        $this->db->where('id', '1');
        $this->db->update($this->setting_table, $data);
    }


    public function update_trands_api()
    {
        $data = $this->input->post();
       
        $this->db->where('id', '1');
        $this->db->update($this->setting_table, $data);
    }

    public function update_generalsetting()
    {
        $data = $this->input->post();
        $this->db->where('id', '1');
        $this->db->update($this->setting_table, $data);
    }
}
