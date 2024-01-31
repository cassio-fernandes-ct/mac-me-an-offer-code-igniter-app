<?php

class SerialNumberSearchBar extends CI_Controller{
	public function SerialNumberSearchBar()
    {
        parent::__construct();
        $this->load->model("serialNumberSearchBar_model");
        header('Access-Control-Allow-Origin: *'); 

    }
    public function index()
    {
        
        $this->load->view('serialNumberSearchBar');
        
    }
    public function getserialproduct()
    {
        $search = $this->input->post('search');
        //$search = '5RU';
        $html = '';
        $serialNumberSearchBar = $this->serialNumberSearchBar_model->get_serial_detaill($search);
        $getBcConfig = $this->serialNumberSearchBar_model->getBcConfig();
        
        if (isset($serialNumberSearchBar['product']) && !empty( $serialNumberSearchBar['product'])) {
            foreach ($serialNumberSearchBar['product'] as  $value) {
               // $productdata = unserialize(base64_decode($value['product_data']));

                $product_title = $value['product_title'];
                $product_url = $value['product_url'];
                $html .= '<div class = "search_results_row"><a href = "'.$getBcConfig['mmo_url'].''.$product_url.'"> <strong>'. $product_title.'</strong></a></div>';
            }
        }else{

             $html .= '<div class = "search_results_row" ><a href = "javascript:void(0)"> <strong>No found product.</strong></a></div>';
        }
        
        $seraiproductlhtml['html'] = $html;
        echo json_encode($seraiproductlhtml);
    }

    public function gethomecategory(){

        $windowsize =  $this->input->post('windowsize');
        
        $homepagecategory = $this->serialNumberSearchBar_model->gethomecategory();
        if($windowsize <= 479 && $windowsize >= 320){
            $html = '';
            if(isset($homepagecategory) && !empty($homepagecategory))
            {
                $sameclasswarpping = 1;
                $count = 1;
                foreach ($homepagecategory as $value) {
                    $category_id = $value['category_id'];
                    $image = 'https://cdn11.bigcommerce.com/s-ilhtqzrn07/product_images/f/placeholder%20(5)__79069.png';
                    if(isset($value['image']) && !empty($value['image']))
                    {
                        $image = $value['image'];
                    }
                    $html .= '<div class="home-gettingstarted-box catgeoryhomepage" onclick="getserisee(\''.$sameclasswarpping.'\',\''.$category_id.'\')" id = "catgeoryhomepage" data-category="'.$category_id.'" data-target="'.$sameclasswarpping.'">
                                <img src="'.$image.'" class="getting-started-img">
                                <div class="getting-started-wrapper">
                                    <div class="gettingstarted-box-title">'.$value['name'].'</div>
                                </div>
                            </div>';
                   
                         $html .= '<div class="estimator-panel" id="'.$sameclasswarpping.'" style="display:none;">
                                    <div class="get-started-form" id = "serise'.$sameclasswarpping.'"> 
                                    </div>
                                    </div>';
                            
                         $sameclasswarpping++;
                    
                    $count++;
                }
                
            }
            $homepagecategoryhtml['html'] = $html;
            echo json_encode($homepagecategoryhtml);
           
        }else{
       
            if($windowsize > 479 && $windowsize < 767){

                $c = 2;
            }else{
                $c = 3;
            }
           
            $html = '';
            if(isset($homepagecategory) && !empty($homepagecategory))
            {
                $sameclasswarpping = 1;
                $count = 1;
                foreach ($homepagecategory as $value) {
                    $category_id = $value['category_id'];
                    $image = 'https://cdn11.bigcommerce.com/s-ilhtqzrn07/product_images/f/placeholder%20(5)__79069.png';
                    if(isset($value['image']) && !empty($value['image']))
                    {
                        $image = $value['image'];
                    }
                    $html .= '<div class="home-gettingstarted-box catgeoryhomepage" onclick="getserisee(\''.$sameclasswarpping.'\',\''.$category_id.'\')" id = "catgeoryhomepage" data-category="'.$category_id.'" data-target="'.$sameclasswarpping.'">
                                <img src="'.$image.'" class="getting-started-img">
                                <div class="getting-started-wrapper">
                                    <div class="gettingstarted-box-title">'.$value['name'].'</div>
                                </div>
                            </div>';
                    if ($count%$c == 0)
                    {
                         $html .= '<div class="estimator-panel" id="'.$sameclasswarpping.'" style="display:none;">
                                    <div class="get-started-form" id = "serise'.$sameclasswarpping.'"> 
                                    </div>
                                    </div>';
                            
                         $sameclasswarpping++;
                    }
                    $count++;
                }
                if($count%$c != 1)  $html .= '<div class="estimator-panel" id="'.$sameclasswarpping.'" style="display:none;">
                                    <div class="get-started-form" id = "serise'.$sameclasswarpping.'"> 
                                    </div>
                                    </div>';
            }else{

            }
            $homepagecategoryhtml['html'] = $html;
            echo json_encode($homepagecategoryhtml);
        }
    }

    public function getserise(){
        $category_id = $this->input->post('category_id');
        $divid       = $this->input->post('divid');
       
       $html = '';
       if(isset($category_id) && !empty($category_id))
       {
            $getserise = $this->serialNumberSearchBar_model->getserise($category_id);
           
            $html .= '<select onchange="getproductt(this.value,\''.$divid.'\')" class="select-series" name="model"> <option value=""> Select a Series </option>';
            if(isset($getserise) && !empty($getserise))
            {
                foreach ($getserise as $value) {
                     
                     $html .= '<option value="'.$value['id'].'"> '.$value['name'].' </option>';
                }   
            }
            $html .= '</select><div class="loading" style="display: none;">
                            <img src="{{cdn "webdav:loading.gif"}}" alt="Loading..."> 
                        </div>';
       }
       
        $seraiproductlhtml['html'] = $html;
        echo json_encode($seraiproductlhtml);
    }

    public function getproduct(){
         $series_id = $this->input->post('series_id');
         $html = '';
         if(isset($series_id) && !empty($series_id))
         {
            $getproduct = $this->serialNumberSearchBar_model->getproduct($series_id);
            $html .= ' <div class="select-model-dropdown"> 
                            <select onchange="productpage(this.value)" class="select-model" name="model">
                                <option value=""> Select a model </option>';
            foreach ($getproduct as $value) {
                        $html .= '<option value="'.$value['product_url'].'">'.$value['product_title'].'</option>';
            }
            $html .= ' </select></div>';
           
         }
          $seraiproductlhtml['html'] = $html;
        echo json_encode($seraiproductlhtml);
    }

    public function serpageresult(){

        $series_id = $this->input->post('search_serial_number');
        
        $pagenumber = 1;
        $offset = 0;
        $page = $this->input->post('page');
        if(isset($page) && !empty($page))
        {
            $pagenumber = $page;   
        }
       
        $offset = ($pagenumber-1)*20;
        $getBcConfig = $this->serialNumberSearchBar_model->getBcConfig();
        $html = '';
        // $html .= '<a class="button csr_BACK_Button" href = "/?index=getstart" >Go back</a>';
         $html .= '<div class="csr_BACK_Button_Block"><a class="button csr_BACK_Button" href="/?index=getstart">Go back</a></div>';
        if(isset($series_id) && !empty($series_id))
        {
            $serialNumberSearchBar = $this->serialNumberSearchBar_model->get_serial_detail($series_id,$offset);

            $serialNumberSearchBartotal = $this->serialNumberSearchBar_model->get_serial_detail_total($series_id);
            
            if(isset($serialNumberSearchBar['product']) && !empty($serialNumberSearchBar['product']))
            {
                foreach ($serialNumberSearchBar['product'] as $value) {
                    # code...
               
                 $html .= ' <div class="csr_pro_grid"  >
                    <div class="csr_grid_inner">
                        <div class="csr_pro_img">
                            <a href="'.$value['product_url'].'">
                                <img src="'.$value['image'].'" alt="'.$value['image'].'">
                            </a>
                        </div>
                        <div class="csr_pro_block">
                            <h3 class="csr_pro_title">
                                <a href="'.$value['product_url'].'">'.$value['product_title'].'</a>
                            </h3>
                        </div>
                    </div>
                </div>';
                }
            }else{
                
                 $html .= '<p class="csr_products_NoFound">No Product Found.</p>';

            }

        }
        $paginationhtml = '';
        if(isset($serialNumberSearchBartotal) && !empty($serialNumberSearchBartotal))
        {
            $totalpages = round($serialNumberSearchBartotal['product_total']/20);
            $paginationhtml = '';
            if($totalpages > 1){
                $p =  $pagenumber - 1;
                $v =  $pagenumber + 1;
                $display = '';if($pagenumber == '1'){$display = 'style="display:none;"';}
                $paginationhtml .= '<ul class = "pagination-list">';
                 $paginationhtml .= '<li  '. $display.' class="pagination-item pagination-item--previous "><a class="pagination-link" href="'.$getBcConfig['mmo_url'].'/search-result/?search_serial_number='.$series_id.'&page='.$p.'"><i class="icon" aria-hidden="true"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chevron-left"></use></svg></i>Previous</a></li>';
               for($i = 1; $i <= $totalpages; $i++) {
                    $active = ''; if($pagenumber == $i){$active = 'pagination-item--current';}
                    $paginationhtml .= '<li class = "pagination-item '.$active.'"><a class="pagination-link" href = "'.$getBcConfig['mmo_url'].'/search-result/?search_serial_number='.$series_id.'&page='.$i.'">'.$i.'</a></li>';

                }
                 $displayy = 'ee';if($pagenumber == $totalpages){$displayy = 'style="display:none;"';}
                $paginationhtml .= '<li '. $displayy.' class="pagination-item pagination-item--next "><a class="pagination-link" href="'.$getBcConfig['mmo_url'].'/search-result/?search_serial_number='.$series_id.'&page='.$v.'">Next<i class="icon" aria-hidden="true"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chevron-right"></use></svg></i></a></li>';
                $paginationhtml .= '</ul>';
            }
        }

       
        $seraiproductlhtml['serialtotal'] = $paginationhtml;
        $seraiproductlhtml['searchtxt'] = "'".$series_id."'";
        $seraiproductlhtml['html'] = $html;
        $seraiproductlhtml['totalnumberofproduct'] = $serialNumberSearchBartotal;
        echo json_encode($seraiproductlhtml);
    }
}