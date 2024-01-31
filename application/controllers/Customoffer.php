<?php

class Customoffer extends CI_Controller{
	public function __construct()
    {
        parent::__construct();
        $this->load->model("serialNumberSearchBar_model");
        $this->load->model('productmodel');
        $this->load->model('admin/settingmodel');
        header('Access-Control-Allow-Origin: *'); 
	header('Content-Type: text/html');

    }
    public function index()
    {
        
        $this->load->view('serialNumberSearchBar');
        
    }
    
    public function getprevselectedoptions() {
        $selected_model_id    = $this->input->post('model_id');
        $selected_category_id = 0;
        $selected_series_id   = 0;
        $product_model        = $this->productmodel->get_product_details_for_quote_tool($selected_model_id);
        if ( is_array($product_model) && count($product_model) > 0 ) {
            $product_model = $product_model[0];
            $selected_category_id = $product_model['category_id'];
            $selected_series_id = $product_model['series_id'];
        }
        $homepagecategory = $this->serialNumberSearchBar_model->gethomecategory();
        $model_html = '';
        if ( $product_model ) {
            $getproduct = $this->serialNumberSearchBar_model->getproduct($selected_series_id);
            $text  = "3. Describe Your Item's Condition";
            $model_html .= '<label class="form-label form-label--alternate form-label--inlineSmall" for="TP_attribute_select_3">'.$text.'</label>
                <select onchange="producturl(this.value)" class="form-select form-select--small" name="receive_payment" id="TP_attribute_select_3">
                    <option value="">Select a Model</option>';
            foreach ($getproduct as $value) {
                $model_html .= '<option value = "'.$value['bc_product_id'].'"  name="'.$value['product_url'].'"'.($selected_model_id == $value['bc_product_id'] ? ' selected' : '').'>'.$value['product_title'].'</option>';
            }
            $model_html .= '</select>';
        }
        $series_html = '';
        if ( $selected_model_id && $selected_category_id && $model_html !== '' ) {
            $getserise = $this->serialNumberSearchBar_model->getserise($selected_category_id);
            $text  = "2. Describe Your Item's Condition";
            $series_html .= '    <label class="form-label form-label--alternate form-label--inlineSmall" for="TP_attribute_select_2">'.$text.'</label>
                <select class="form-select form-select--small" onchange="getproduct(this.value)" name="receive_payment" id="TP_attribute_select_2">
                    <option value="">Select a Series</option>';
            if(isset($getserise) && !empty($getserise))
            {
                foreach ($getserise as $value) {
                    $series_html .= '<option value="'.$value['id'].'"'.($selected_series_id == $value['id'] ? ' selected' : '').'> '.$value['name'].' </option>';
                }
            }
            $series_html .= '</select>';
        }
        $cat_html = '';
        if(isset($homepagecategory) && !empty($homepagecategory))
        {
            $cat_html .= '<label class="form-label form-label--alternate form-label--inlineSmall" for="TP_attribute_select_1">1. Select your Apple product</label>  
            <select class="form-select form-select--small" onchange="getserise(this.value)" name="receive_payment" id="TP_attribute_select_1"><option value="">Select Categories</option>';
            foreach ($homepagecategory as $value) {
                $category_id = $value['category_id'];
                $cat_html .= '
                <option value="'.$category_id.'"'.($selected_category_id == $category_id ? ' selected' : '').'>'.$value['name'].'</option>';
            }
            $cat_html .= '</select>';
        }
        echo json_encode(['category' => $cat_html, 'series' => $series_html, 'model' => $model_html]);
    }

    public function gethomecategory(){
        $homepagecategory = $this->serialNumberSearchBar_model->gethomecategory();
        $html = '';
        if(isset($homepagecategory) && !empty($homepagecategory))
        {
             $html .= '<label class="form-label form-label--alternate form-label--inlineSmall" for="TP_attribute_select_1">1. Select your Apple product</label>  
             <select class="form-select form-select--small" onchange="getserise(this.value)" name="receive_payment" id="TP_attribute_select_1"><option value="">Select Categories</option>';
            foreach ($homepagecategory as $value) {

            $category_id = $value['category_id'];
                       
             $html .= '
                <option value="'.$category_id.'">'.$value['name'].'</option>';
            }
            $html .= '</select>';
        }else{

        }
        $homepagecategoryhtml['html'] = $html;
        echo json_encode($homepagecategoryhtml);
    }

    public function getserise(){
        $category_id = $this->input->post('category_id');
        $divid       = $this->input->post('divid');
       
       $html = '';
       if(isset($category_id) && !empty($category_id))
       {
            $getserise = $this->serialNumberSearchBar_model->getserise($category_id);
             $text = "2. Describe Your Item's Condition";
            $html .= '    <label class="form-label form-label--alternate form-label--inlineSmall" for="TP_attribute_select_2">'.$text.'</label>
                        <select class="form-select form-select--small" onchange="getproduct(this.value)" name="receive_payment" id="TP_attribute_select_2">
                            <option value="">Select a Series</option>';
            if(isset($getserise) && !empty($getserise))
            {
                foreach ($getserise as $value) {
                            $html .= '<option value="'.$value['id'].'"> '.$value['name'].' </option>';
               }   
            }

            $html .= '</select>';
       }
       
        $seraiproductlhtml['html'] = $html;
        echo json_encode($seraiproductlhtml);
    }

    public function getproduct(){
       // $getSettingData = $this->settingmodel->getSettingData($id = '1');
         $series_id = $this->input->post('series_id');
         $html = '';
         if(isset($series_id) && !empty($series_id))
         {
            $getproduct = $this->serialNumberSearchBar_model->getproduct($series_id);
             $text = "3. Describe Your Item's Condition";
            $html .= '<label class="form-label form-label--alternate form-label--inlineSmall" for="TP_attribute_select_3">'.$text.'</label>
                        <select onchange="producturl(this.value)" class="form-select form-select--small" name="receive_payment" id="TP_attribute_select_3">
                            <option value="">Select a Model</option>';
            foreach ($getproduct as $value) {
                        $html .= '<option value = "'.$value['bc_product_id'].'"  name="'.$value['product_url'].'">'.$value['product_title'].'</option>';
            }
            $html .= '</select>';
           
         }
        $seraiproductlhtml['html'] = $html;
        echo json_encode($seraiproductlhtml);
    }

    public function serpageresult(){

        $series_id = $this->input->post('search_serial_number');
        $html = '';
        if(isset($series_id) && !empty($series_id))
        {
            $serialNumberSearchBar = $this->serialNumberSearchBar_model->get_serial_detail($series_id);
            if(isset($serialNumberSearchBar) && !empty($serialNumberSearchBar))
            {
                foreach ($serialNumberSearchBar as $value) {
                    # code...
               
                 $html .= ' <div class="csr_pro_grid"  onclick="productpage(\''.$value['product_url'].'\')" >
                    <div class="csr_grid_inner">
                        <div class="csr_pro_img">
                            <a href="javascript:void(0)">
                                <img src="'.$value['image'].'" alt="'.$value['image'].'">
                            </a>
                        </div>
                        <div class="csr_pro_block">
                            <h3 class="csr_pro_title">
                                <a href="javascript:void(0)">'.$value['product_title'].'</a>
                            </h3>
                        </div>
                    </div>
                </div>';
                }
            }else{
                 $html .= 'No Product Found.';

            }
        }
        
        $seraiproductlhtml['searchtxt'] = "'".$series_id."'";
        $seraiproductlhtml['html'] = $html;
        echo json_encode($seraiproductlhtml);
    }
}
