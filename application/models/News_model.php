<?php
class News_model extends CI_Model {
    public function __construct()
    {
            $this->load->database();
    }
	public function get_news($slug = FALSE)
	{
	        if ($slug === FALSE)
	        {
	                $query = $this->db->get('news');
	                return $query->result_array();
	        }

	        $query = $this->db->get_where('news', array('slug' => $slug));
	        return $query->row_array();
	}        
	public function set_news()
	{
	    $this->load->helper('url');
	    $slug = url_title($this->input->post('title'), 'dash', TRUE);
	    $data = array(
	        'title' => $this->input->post('title'),
	        'slug' => $slug,
	        'text' => $this->input->post('text')
	    );
	    return $this->db->insert('news', $data);
	}



	function getRows($params = array()){
        $this->db->select('*');
        $this->db->from('news');
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like('title',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('title',$params['search']['sortBy']);
        }else{
            $this->db->order_by('id','desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() >= 0)?$query->result_array():FALSE;
    }

}