<?php

class Orders extends CI_Controller{

    protected $shipping_label_file_name = '';

	public function __construct()
    {
        parent::__construct();
        $this->load->model("ordersmodel");
        $this->load->model('admin/settingmodel');
        $this->load->library('email');
        header('Access-Control-Allow-Origin: *');
    }

   

    public function list()
    {
    	$post = $this->input->post();
    	
    	
    	$orders = $this->ordersmodel->getorder($post);
    	
    	$totalpage = ceil($orders['ordertotal'] / $post['limit']);

    	$ordercount = count($orders['orderdetails']);
    	$orderlist = '';
    	if(isset($orders['orderdetails']) && !empty($orders['orderdetails']))
    	{
    		$orderlist .= '<section class="account-content">
						    <h3 class="account-heading">Orders</h3>

						    <ul class="account-list">';
						    foreach ($orders['orderdetails'] as $value) {
						    
						    
			$orderlist .= '     <li class="account-listItem">
						            <div class="account-product">
						                <div class="account-product-figure">
						                    <img class="account-product-image lazyautosizes lazyloaded" data-sizes="auto" src="'.$value['product_thumbnail_url'].'" alt="" title="" sizes="70px">
						                </div>
						                <div class="account-product-body">
						                    <div class="account-orderStatus">
						                        <h6 class="account-orderStatus-label">'.$value['status'].'</h6>
						                    </div>

						                    <h5 class="account-product-title">
						                    <a onclick = "viewOrderDeatils(\''.$value['orderId'].'\')">Order #'.$value['orderId'].'</a>
						                </h5>
						                    <p class="account-product-description">'.$value['items_total'].' product totaling $'.number_format($value['total_amount'],2).'</p>

						                    <div class="account-product-details">
						                        <div class="account-product-detail">
						                            <h6 class="account-product-detail-heading">Order Placed</h6>
						                            <span>'.$value['date_created'].'</span>
						                        </div>
						                        <div class="account-product-detail">
						                            <h6 class="account-product-detail-heading">Last Update</h6>
						                            <span>'.$value['date_modified'].'</span>
						                        </div>
						                    </div>
						                </div>
						            </div>
						        </li>';
						    }
						   $next = $post['page'] + 1 ;
			$orderlist .= ' </ul>
						    <div class="pagination">
						        <ul class="pagination-list pagination-list--small"> '; 
						        if($post['page'] != 1){
			$orderlist .= '       <li class="pagination-item pagination-item--previous">
						                <a class="pagination-link" href="/account.php?action=order_status&limit='.$post['limit'].'&page=1" data-faceted-search-facet="">
						                    <i class="icon" aria-hidden="true">
						                        <svg>
						                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chevron-left"></use>
						                        </svg>
						                    </i>
						                    Previous
						                </a>
						            </li>';
						            }
			$orderlist .= '       <li class="pagination-item">Page '.$post['page'].' of '.$totalpage.'</li>';
									 if($post['page'] != $totalpage){
			$orderlist .= '       <li class="pagination-item pagination-item--next">
						                <a class="pagination-link" href="/account.php?action=order_status&limit='.$post['limit'].'&page='.$next.'" data-faceted-search-facet="">
						                    Next
						                    <i class="icon" aria-hidden="true">
						                        <svg>
						                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-chevron-right"></use>
						                        </svg>
						                    </i>
						                </a>
						            </li>';
						            }
			$orderlist .= '     </ul>
						    </div>

						</section>';
    	}else{
    		$orderlist .= '
    		<section class="account-content">
			    <div class="alertBox alertBox--info">
			        <div class="alertBox-column alertBox-icon">
			            <icon glyph="ic-success" class="icon" aria-hidden="true">
			                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
			                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
			                </svg>
			            </icon>
			        </div>
			        <p class="alertBox-column alertBox-message">
			            <span>You haven\'t placed any orders with us. When you do, their status will appear on this page.</span>
			        </p>
			    </div>
			</section>';

    	}
		$quoteHtmll['ordercount'] = $ordercount;
		$quoteHtmll['orderlist'] = $orderlist;
        echo json_encode($quoteHtmll);  
    	
    }

    function viewOrderDeatils(){
    	$post = $this->input->post();
    	$orders = $this->ordersmodel->viewOrderDeatils($post);

    	$ordershtml['orders'] = $orders;
        echo json_encode($ordershtml); 
    }

    function ConverImage($src){
		$im = file_get_contents($src);
		return $imdata = "data:image/jpg;base64,".base64_encode($im);
	}

	function addresses(){

		$storename = $this->input->post('storename');
		$data = json_decode(stripslashes($this->input->post('idss')));

		//$storename = 'mmo';
		//$data[0] = '181031';
		//$data[1] = '181032';

		$orders['da'] = $this->ordersmodel->addresses($data,$storename);
		echo json_encode($orders); 

	}

	function accountsetting(){
		$data = $this->input->post();
		//$data['customer_id'] = '190152';
		//$data['storename']   = 'mmo';
		$orders['da'] = $this->ordersmodel->accountsetting($data);
		echo json_encode($orders); 
	}
}