<?php

class Account extends CI_Controller{

    protected $shipping_label_file_name = '';

	public function __construct()
    {
        parent::__construct();
        $this->load->model("accountmodel");
        $this->load->model('admin/settingmodel');
        $this->load->library('email');
        header('Access-Control-Allow-Origin: *');
    }

   

    public function list()
    {
    	//$customer_id = $this->input->post('customer_id');
    	//$store = $this->input->post('store');
    	$post = $this->input->post();
    	
    	/*$post['customer_id'] = '152';
    	$post['store'] = 'mmt';
    	$post['selected'] = 'All';
    	$post['page'] = '1';
    	$post['limit'] = '20'; */
    	
    	$tokenupdate = $this->accountmodel->getquote($post);
    	$getSettingData = $this->settingmodel->getSettingData($id = '1');	


    	$quoteHtml = '';
    	$quoteHtml .= '<input type = "hidden" id = "page" name = "page" value = "page"/>
    					<table class="table QuoteContent_table">
					    <thead>
					        <tr>
					            <th>Product Name</th>
					            
					            
					            <th>Status</th>
					            <th>Actions</th>
					        </tr>
					    </thead>
					    <tbody>';
					    if(isset($tokenupdate['quotedata']) && !empty($tokenupdate['quotedata']))
					    {
					    	foreach ($tokenupdate['quotedata'] as  $value) {
					    	if($value['contact_flag'] == 0){
								$status = '<span class="QC_item_status Lbl_abandoned">Abandoned</span>';
							
							}
							else{

								$status = '<span class="QC_item_status Lbl_completed">Completed</span>';
							}
							$quoteHtml .= '<tr class="QC_item">
								            <td class="QC_item_block QC_Name_section">
								                <div class="QC_item_img_block">
								                    <img class="QC_item_img" src="'.$value['image'].'" alt="">
								                </div>
								                <h4 class="QC_item_name_block">
													<a class="QC_item_pro_name" href="'.$getSettingData['mmo_url'].''.$value['product_url'].'">'.$value['product_title'].'</a>
												</h4>
								            </td>
								            
								            <td class="QC_item_block QC_Status_section">
								                <div class="QC_item_pro_status">
								                   '.$status.'
								                </div>
								            </td>
								            <td class="QC_item_block QC_Action_section">
								                <div class="QC_item_pro_action">
								                    <span class="QC_item_action">
														<a class="QA_action_button button" onclick="viewquote(\''.$value['id'].'\')">View</a>
													</span>
								                </div>
								            </td>
								        </tr>';
					    	}
					    }else{

					    	$quoteHtml .= '<tr class = "QuoteContent_table_noPro" ><td colspan="5" >No match found.</td></tr>';
					    }
		$quoteHtml .='</tbody>
					</table>';

		$totalpage = ceil($tokenupdate['quotetotal'] / $post['limit']);
		$paginationHtml = '';
		if($totalpage > 1)
		{
			
			$paginationHtml .= '<ul class="pagination-list">';
			 if($post['page'] > 1 ) {
			$paginationHtml .= '<li class="pagination-item pagination-item--previous">
								        <a class="pagination-link" onclick="accountpagepagination(\'1\')">
								            <i class="icon" aria-hidden="true">
								                <svg>
								                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chevron-left"></use>
								                </svg>
								            </i>
								            Previous
								        </a>
								    </li>';
								   }
								   for($i = 1; $i <= $totalpage; $i++) {
								   	$classpaginationlink = ''; if($post['page'] == $i ){ $classpaginationlink = 'pagination-item--current';}
				$paginationHtml .= '<li class="pagination-item '.$classpaginationlink.'">
								        <a class="pagination-link" onclick="accountpagepagination(\''.$i.'\')">'.$i.'</a>
								    </li>';
								    
								   	}
								   	if($post['page'] != $totalpage ) {
				$paginationHtml .= '<li class="pagination-item pagination-item--next">
								        <a class="pagination-link" onclick="accountpagepagination(\''.$totalpage.'\')">
								            Next
								            <i class="icon" aria-hidden="true">
								                <svg>
								                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chevron-right"></use>
								                </svg>
								            </i>
								        </a>
								    </li>';
									}
				$paginationHtml .= '</ul>';
			}

		 $quoteHtmll['quoteHtml'] = $quoteHtml;
		 $quoteHtmll['paginationHtml'] = $paginationHtml;
		 $quoteHtmll['quotetotalHtml'] = 'Total '.$tokenupdate['quotetotal'];	
		 $quoteHtmll['selected'] = $post['selected'];	
		 $quoteHtmll['numbertotal'] = $tokenupdate['quotetotal'];
         echo json_encode($quoteHtmll);  

    }

    public function viewquote()
    {
    	$post = $this->input->post();
    	//$post['quoteid'] = '1';
    	$tokenupdate = $this->accountmodel->viewquote($post);
    	
    	$prduct_title = '';
    	if(isset($tokenupdate['product']['product_title']) && !empty($tokenupdate['product']['product_title']))
    	{
    		$prduct_title = $tokenupdate['product']['product_title'];
    	}
    	
    	$quoteoption = '';
    	if(isset($tokenupdate['selectedoption']) && !empty($tokenupdate['selectedoption']))
    	{
    		$quoteoption .= '<div class="DB_TabPanel_inner"><div class="DB_Common_Cnt"><ul>
							<li class="DB_TabPanel_first">
								
							</li>';
			
			foreach ($tokenupdate['selectedoption'] as  $value) {

			$ret = explode(':', $value->option_label);

			$quoteoption .= '<li>
								<div class="TabPanel_lblVal">
									<span class="TabPanel_lbl">'.$value->option_set_name.'</span>
									<span class="TabPanel_val">'.$ret[0].'</span>
								</div>
							</li>';
			}			
			if(isset($tokenupdate['qustionans']) && !empty($tokenupdate['qustionans']))
			{
				foreach ($tokenupdate['qustionans'] as  $value) {

				$ret = explode(':', $value->option_label);

				$quoteoption .= '<li>
									<div class="TabPanel_lblVal">
										<span class="TabPanel_lbl">'.$value->option_set_name.'</span>
										<span class="TabPanel_val">'.$value->option_label.'</span>
									</div>
								</li>';
				}	
			}	
			$quoteoption .= '</ul></div></div>'; 
    	}



    	$status = '';
    	if($tokenupdate['customerinfo']['contact_flag'] == 0){
			$status = '<span class="TabPanel_val Lbl_abandoned">Abandoned</span>';
		
		}
		else{

			$status = '<span class="TabPanel_val Lbl_completed">Completed</span>';
		}

    	$CustomerInformation = '';
    	$CustomerInformation .= '<div class="DB_TabPanel_inner">
									<div class="DB_Common_Cnt">
										<ul>
											<li class="DB_TabPanel_first">
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">Status:</span>
													'.$status.'
												</div>
											</li>';
											if(isset($tokenupdate['customerinfo']['form_first_name']) && !empty($tokenupdate['customerinfo']['form_first_name']))
											{
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">First Name:</span>
													<span class="TabPanel_val">'.$tokenupdate['customerinfo']['form_first_name'].'</span>
												</div>
											</li>';
											}
											if(isset($tokenupdate['customerinfo']['form_last_name']) && !empty($tokenupdate['customerinfo']['form_last_name']))
											{
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">Last Name:</span>
													<span class="TabPanel_val">'.$tokenupdate['customerinfo']['form_last_name'].'</span>
												</div>
											</li>';
											}
											if(isset($tokenupdate['customerinfo']['form_street1']) && !empty($tokenupdate['customerinfo']['form_street1']))
											{
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">Street Address:</span>
													<span class="TabPanel_val">'.$tokenupdate['customerinfo']['form_street1'].'</span>
												</div>
											</li>';
											}
											if(isset($tokenupdate['customerinfo']['form_street2']) && !empty($tokenupdate['customerinfo']['form_street2']))
											{
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">Street Address Line 2:</span>
													<span class="TabPanel_val">'.$tokenupdate['customerinfo']['form_street2'].'</span>
												</div>
											</li>';
											}
											if(isset($tokenupdate['customerinfo']['form_city']) && !empty($tokenupdate['customerinfo']['form_city']))
											{
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">City:</span>
													<span class="TabPanel_val">'.$tokenupdate['customerinfo']['form_city'].'</span>
												</div>
											</li>';
											}
											if(isset($tokenupdate['customerinfo']['form_state']) && !empty($tokenupdate['customerinfo']['form_state']))
											{
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">State:</span>
													<span class="TabPanel_val">'.$tokenupdate['customerinfo']['form_state'].'</span>
												</div>
											</li>';
											}
											if(isset($tokenupdate['customerinfo']['form_zip']) && !empty($tokenupdate['customerinfo']['form_zip']))
											{
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">Zip Code:</span>
													<span class="TabPanel_val">'.$tokenupdate['customerinfo']['form_zip'].'</span>
												</div>
											</li>';
											}
											if(isset($tokenupdate['customerinfo']['form_email_address']) && !empty($tokenupdate['customerinfo']['form_email_address']))
											{
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">Email Address:</span>
													<span class="TabPanel_val">'.$tokenupdate['customerinfo']['form_email_address'].'</span>
												</div>
											</li>';
											}
											if(isset($tokenupdate['customerinfo']['form_serial_number']) && !empty($tokenupdate['customerinfo']['form_serial_number']))
											{
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">Device Serial Number:</span>
													<span class="TabPanel_val">'.$tokenupdate['customerinfo']['form_serial_number'].'</span>
												</div>
											</li>';
											}
											if(isset($tokenupdate['customerinfo']['form_prev_sold']) && !empty($tokenupdate['customerinfo']['form_prev_sold']))
											{
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">Have you previously sold to us:</span>
													<span class="TabPanel_val">'.$tokenupdate['customerinfo']['form_prev_sold'].'</span>
												</div>
											</li>';
											}
											if(isset($tokenupdate['customerinfo']['receive_payment']) && !empty($tokenupdate['customerinfo']['receive_payment']))
											{
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">How would you like to receive payment?:</span>
													<span class="TabPanel_val">'.$tokenupdate['customerinfo']['receive_payment'].'</span>
												</div>
											</li>';
											}
											if(isset($tokenupdate['shpping_label']['shipping_image']) && !empty($tokenupdate['shpping_label']['shipping_image']))
											{
												$url =  base_url().'application/uploads/ups/shipping/'.$tokenupdate['shpping_label']['shipping_image'];
												$download_img = $this->ConverImage($url);
					$CustomerInformation .= '<li>
												<div class="TabPanel_lblVal">
													<span class="TabPanel_lbl">Download Shipping label:</span>
													<span class="TabPanel_val">
														<a download class="TabPanel_download_btn button" href="'.$download_img.'">'.$tokenupdate['shpping_label']['shipping_image'].'</a>
													</span>
												</div>
											</li>';
											}
					$CustomerInformation .= '</ul>
									</div>
								</div>';

    	$quoteHtmll['prduct_title'] = $prduct_title;	
		$quoteHtmll['quoteoption']  = $quoteoption; 
		$quoteHtmll['CustomerInformation']  = $CustomerInformation; 
        echo json_encode($quoteHtmll);  
    }

    function ConverImage($src){
		$im = file_get_contents($src);
		return $imdata = "data:image/jpg;base64,".base64_encode($im);
	}
}