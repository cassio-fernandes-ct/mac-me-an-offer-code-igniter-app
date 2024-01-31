<?php
class News extends Admin_Controller {
    protected $data = array();
    protected $perPage;
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('news_model');
        $this->load->helper('url_helper');
        $this->data['title'] = 'News';
        $this->data['add_button'][] = ['Add News','admin/news/create'];
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push('News', base_url('news'));
        $this->load->library('Ajax_pagination');
        $this->perPage = 5;        
    }
    public function index()
    {
        // $this->data['news'] = $this->news_model->get_news();
        $this->data['sub_title'] = 'News archive';
        $this->data['page'] = 'news/index';

        $totalRec = count($this->news_model->getRows());
        
        //pagination configuration
        $config['target']      = '#newsList';
        $config['base_url']    = base_url().'news/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
        $this->ajax_pagination->initialize($config);
        //get the posts data
        $this->data['news'] = $this->news_model->getRows(array('limit'=>$this->perPage));

        $this->load->view('layouts/default', $this->data);
    }
    public function view($slug = NULL)
    {
        $this->data['news_item'] = $this->news_model->get_news($slug);
        if (empty($this->data['news_item']))
        {
                show_404();
        }
        $this->data['sub_title'] = $slug;
        $this->data['page'] = 'news/view';
        $this->load->view('layouts/default', $this->data);
    }
    public function create()
    {
        // $this->load->helper('form');
        $this->data['title'] = 'Add News Item';
        $this->data['sub_title'] = 'Add News';
        if ($this->form_validation->run('news_create') === FALSE)
        {
            $this->data['page'] = 'news/create';
        }
        else
        {
            $this->news_model->set_news();
            $this->data['page'] = 'news/success';
        }
        $this->load->view('layouts/default', $this->data);
    }



    function ajaxPaginationData(){
        
        $conditions = array();
        
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        //set conditions for search
        $keywords = $this->input->post('keywords');
        $sortBy = $this->input->post('sortBy');
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }

        $check_count = $this->news_model->getRows($conditions);
        if($check_count)
        {
            //total rows count
            $totalRec = count($this->news_model->getRows($conditions));
        }
        else
        {
            $totalRec = 0;
        }
        
        //pagination configuration
        $config['target']      = '#newsList';
        $config['base_url']    = base_url().'news/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        //get posts data
        $this->data['news'] = $this->news_model->getRows($conditions);
        //load the view
        $this->load->view('news/ajax-pagination-data', $this->data, false);
    }

}