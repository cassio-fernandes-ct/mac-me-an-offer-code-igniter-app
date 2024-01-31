<?php 
	$arrayDecrease‌[0] = str_replace(' ', '', 'Good: $50');
	$arrayDecrease‌[1] = str_replace(' ', '', 'No: $15');
	$arrayDecrease‌[2] = str_replace(' ', '', 'Yes: $85 + knockout');
	$arrayDecrease‌[3] = str_replace(' ', '', 'No: $35');
	$arrayDecrease‌[4] = str_replace(' ', '', 'Yes: $125 + knockout');
	
	$arrayIncrease[0] = str_replace(' ', '', 'Yes: $10');

	$quote_datetime = DateTime::createFromFormat( 'Y-m-d H:i:s', $getquotedetails['customerinfo']['created_date'] );
	$quote_datetime->setTimezone( new DateTimeZone( 'America/New_York' ) );
?>

<style>
	.kt-portlet__head-title__date {
		opacity: 0.5;
	}
</style>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
				Quotes Management   </h3>
				<span class="kt-subheader__separator kt-hidden"></span>
			</div>
		</div>
	</div>
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
						<i class="kt-font-brand flaticon2-line-chart"></i>
					</span>
					<h3 class="kt-portlet__head-title">
					 	View Quotes Management
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper" >
						<a href="<?php  echo $this->config->base_url(); ?>admin/quote" class="btn btn-brand btn-icon-sm">
							<i class="la la-long-arrow-left"></i>
							Back
						</a>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body kt-portlet__body--fit">
				<div class="kt-portlet">
				    <div class="kt-portlet__head">
				        <div class="kt-portlet__head-label">
				            <h3 class="kt-portlet__head-title">
								#<?php echo $getquotedetails['product']['bc_product_id']; ?> | <?php echo $getquotedetails['product']['product_title']; ?> 
								<span class="kt-portlet__head-title__date">(<?php echo $quote_datetime->format( 'Y-m-d g:iA' ); ?>)</span>
							</h3>
				        </div>
				        <div class="kt-portlet__head-toolbar">
							<div class="kt-portlet__head-wrapper">
								 <h3 class="kt-portlet__head-title">PO: <?php echo $getquotedetails['customerinfo']['id'] ?></h3>
							</div>
						</div>
				    </div>
				    <div class="kt-portlet__body">
				        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x nav-tabs-line-success" role="tablist">
				            <li class="nav-item">
				                <a class="nav-link active" data-toggle="tab" href="#kt_tabs_4_1" role="tab">Selected Option</a>
				            </li>
				            <li class="nav-item">
				                <a class="nav-link" data-toggle="tab" href="#kt_tabs_4_3" role="tab">Customer Information</a>
				            </li>
				        </ul>
				        <div class="tab-content">
				        	<?php $update 	= $this->session->userdata('updatedata'); 

							if(isset($update) && !empty($update)){ ?>
				        	<div class="alert alert-solid-success alert-bold" role="alert">
						        <div class="alert-text">The quote approval email notification sent to the customer.</div>
						        <div class="alert-close">
						        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
						        <span aria-hidden="true"><i class="la la-close"></i></span>
						        </button>
						        </div>
					        </div>
					        <?php $this->session->unset_userdata('updatedata'); } ?>
				            <div class="tab-pane active" id="kt_tabs_4_1" role="tabpanel">
				               <div class="kt-portlet__body">
									<div class="kt-widget12">
										<div class="kt-widget12__content">
											<div class="kt-widget12__item" style="margin-bottom: 0;">	
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">Price</span> 
													<span class="kt-widget12__value"><?php echo @$getquotedetails['customerinfo']['price']; ?></span>
												</div>

												<!-- START Edit offered price -->
												<?php @$exp_offeredprice = explode("$", @$getquotedetails['customerinfo']['offered_price']);
												if(isset($getquotedetails['customerinfo']['offered_price']) && !empty($getquotedetails['customerinfo']['offered_price']) && $exp_offeredprice[1] > 0){ ?>
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">Offered Price</span> 
													<span class="kt-widget12__value"><?php echo @$getquotedetails['customerinfo']['offered_price']; ?></span>
												</div>	
												<?php } ?>
												<div class="kt-portlet__head-toolbar" style="margin-right: 5px;">
													<div class="kt-portlet__head-wrapper">
														<a href="javascript:void(0);" class="btn btn-brand btn-icon-sm" data-toggle="modal" data-target="#exampleModalCenter">
															<i class="flaticon-edit"></i>
															 Edit Offered Price
														</a>
													</div>
												</div>
												<!-- END Edit offered price -->

												<?php if($getquotedetails['customerinfo']['knockout'] > 0 && $getquotedetails['customerinfo']['contact_flag'] != 0){ ?>
												<div class="kt-portlet__head-toolbar">
													<div class="kt-portlet__head-wrapper">
														<a href="<?php  echo $this->config->base_url(); ?>admin/quote/emailsend/<?php echo $getquotedetails['customerinfo']['id']?>" class="btn btn-brand btn-icon-sm">
															<i class="flaticon-email"></i>
															 Approved Quote & Send Email
														</a>
													</div>
												</div>
											<?php } ?>
											</div>
											<div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
											
											<?php 
											//echo "<pre>";
											//print_r($getquotedetails['selectedoption']);
											
											if(isset($getquotedetails['selectedoption']) && !empty($getquotedetails['selectedoption'])){ ?>
											<?php $i = 0; ?>
											<?php foreach($getquotedetails['selectedoption'] as $option){?>
											<div class="kt-widget12__item">	
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc"><?php echo $option[0]->option_set_name; ?></span> 
													
													<span class="kt-widget12__value"> 
														<?php $optionvaluesarray = explode(":",$option[0]->option_label); 
														
														echo $optionvaluesarray[0].' : ';
														 ?>
														<?php if (in_array( str_replace(' ', '',  $option[0]->option_label), $arrayIncrease 
														)){ echo "+"; } ?>
														<?php if (in_array( str_replace(' ', '',  $option[0]->option_label), $arrayDecrease‌ 
														)){ echo "-"; } ?>
													 <?php echo @$optionvaluesarray[1]; ?></span>
												</div>

												<div class="kt-widget12__info">
													<span class="kt-widget12__desc"><?php echo @$option[1]->option_set_name; ?></span> 
													<span class="kt-widget12__value">
														<?php $optionvaluesarray = explode(":",@$option[1]->option_label); 
														if(isset($optionvaluesarray[0]) && !empty($optionvaluesarray[0]))
														{
														echo @$optionvaluesarray[0].' : ';
														}
														 ?>
														<?php if (in_array( str_replace(' ', '', @$option[1]->option_label), $arrayDecrease‌)){ echo "-"; } ?>
														<?php if (in_array( str_replace(' ', '',  @$option[1]->option_label), $arrayIncrease 
														)){ echo "+"; } ?>
														 <?php echo @$optionvaluesarray[1]; ?></span>	
												</div>	

											</div>
												<?php $i++; ?>
											<?php } ?>
											<?php } ?>
											<div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
											<div class="kt-widget12__item">	
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">Device Serial Number</span> 
													<span class="kt-widget12__value">
															<?php if($getquotedetails['customerinfo']['form_serial_number']==null||$getquotedetails['customerinfo']['form_serial_number']==''){ echo "N/A"; 
														}else{
															echo $getquotedetails['customerinfo']['form_serial_number']; 
														} ?>
													</span>
												</div>
											</div>
											<div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
											<?php 
											
											if(isset($getquotedetails['qustionans']) && !empty($getquotedetails['qustionans'])){ ?>
											<?php $i = 0; ?>
											<?php foreach($getquotedetails['qustionans'] as $option){?>
											<div class="kt-widget12__item">	
												<div class="kt-widget12__info">	
													 	 
													<span class="kt-widget12__desc"><?php echo $option[0]->option_set_name; ?></span> 
													
													<span class="kt-widget12__value"> 
														
													 <?php echo @$option[0]->option_label; ?></span>
												</div>

												<div class="kt-widget12__info">
													<span class="kt-widget12__desc"><?php echo @$option[1]->option_set_name; ?></span> 
													<span class="kt-widget12__value">
														
														 <?php echo @$option[1]->option_label; ?></span>	
												</div>					 	 	 
											</div>
												<?php $i++; ?>
											<?php } ?>
											<?php } ?>

										</div>
									</div>			 
								</div>
				            </div>	
				            <div class="tab-pane" id="kt_tabs_4_3" role="tabpanel">
				                <div class="kt-portlet__body">
									<div class="kt-widget12">
										<div class="kt-widget12__content">
											<div class="kt-widget12__item" style="margin-bottom: 0;">	
												<div class="kt-widget12__info">				 	 
													
													<!--<span class="kt-widget12__value">-->
														<?php if($getquotedetails['customerinfo']['contact_flag'] == 0){
														$status = '<span><span class="kt-badge  kt-badge--primary kt-badge--inline kt-badge--pill">Abandoned</span></span>';
															}
															else{
																$status = '<span><span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">Completed</span></span>';
															}?>
														
														<span class="kt-widget12__desc">Status : <?php echo $status; ?></span> 
													<!--</span>-->
												</div>				 	 	 
											</div>
											<div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
											<div class="kt-widget12__item">	
												<div class="kt-widget12__info">
													<span class="kt-widget12__desc">First Name</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_first_name']; ?></span>	
												</div>
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">Last Name</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_last_name']; ?></span>
												</div>		 	 	 
											</div>
											<div class="kt-widget12__item">	
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">Street Address</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_street1']; ?></span>
												</div>

												<div class="kt-widget12__info">
													<span class="kt-widget12__desc">Street Address Line 2</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_street2']; ?></span>	
												</div>					 	 	 
											</div>
											<div class="kt-widget12__item">	
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">City</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_city']; ?></span>
												</div>

												<div class="kt-widget12__info">
													<span class="kt-widget12__desc">State</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_state']; ?></span>	
												</div>					 	 	 
											</div>	
											<div class="kt-widget12__item">	
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">Zip Code</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_zip']; ?></span>
												</div>
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">Email Address</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_email_address']; ?></span>
												</div>					 	 	 
											</div>
											<div class="kt-widget12__item">	
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">Phone Number</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_phone_number']; ?></span>
												</div>
												<!-- START Edit phone number -->
                                                                                                <div class="kt-portlet__head-toolbar" style="margin-right: 5px;">
                                                                                                    <div class="kt-portlet__head-wrapper">
                                                                                                        <a href="javascript:void(0);" class="btn btn-brand btn-icon-sm" data-toggle="modal" data-target="#editPhoneNumberModal">
                                                                                                            <i class="flaticon-edit"></i>
                                                                                                            Edit Phone Number
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <!-- END Edit phone number -->
												<!-- <div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">Email Address</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_email_address']; ?></span>
												</div>					 	 	  -->
											</div>

											<div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
											<div class="kt-widget12__item">	
												<!--<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">Device Serial Number</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_serial_number']; ?></span>
												</div>-->			
												<!--
												<div class="kt-widget12__info">
													<span class="kt-widget12__desc">Have you previously sold to us</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['form_prev_sold']; ?></span>	
												</div>		-->			 	 	 
											</div>
											<div class="kt-widget12__item">	
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">How would you like to receive payment?</span> 
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['receive_payment']; ?></span>
												</div>	
												<!-- START Edit payment method -->
                                                                                                <div class="kt-portlet__head-toolbar" style="margin-right: 5px;">
                                                                                                    <div class="kt-portlet__head-wrapper">
                                                                                                        <a href="javascript:void(0);" class="btn btn-brand btn-icon-sm" data-toggle="modal" data-target="#editPaymentMethodModal">
                                                                                                            <i class="flaticon-edit"></i>
                                                                                                            Edit Payment Method
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <!-- END Edit payment method -->
												<?php if(isset($getquotedetails['shpping_label']['shipping_image']) && !empty($getquotedetails['shpping_label']['shipping_image'])){ 
													$url =  base_url().'/application/uploads/ups/shipping/'.$getquotedetails['shpping_label']['shipping_image'];
												?>
												<div class="kt-widget12__info">				 	 
													<span class="kt-widget12__desc">Download Shipping label</span> 
													<span class="kt-widget12__value"><a href="<?php echo $url; ?>" download><?php echo $getquotedetails['shpping_label']['shipping_image'] ?></a></span>
												</div>	
												<?php } ?>		 	 	 
											</div>
											<div class="kt-widget12__item">
												<div class="kt-widget12__info">
													<span class="kt-widget12__desc">Additional Insurance</span>
													<span class="kt-widget12__value"><?php echo $getquotedetails['customerinfo']['insurance'] ? 'Yes' : 'No'; ?></span>
												</div>
											</div>

										</div>
									</div>			 
								</div>
				            </div>
				        </div>
				    </div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- START Offered price Modal-->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Offered Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
            	<div class="alert alert-success d-none successToast" role="alert"></div>
				<div class="alert alert-danger d-none failToast" role="alert"></div>
                <form name="edit_offerprice_form" method="post">
                    <div class="form-group">
						<label>Price</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">$</span>
							</div>
							<?php @$exp_price = explode("$", @$getquotedetails['customerinfo']['price']); ?>
							<input type="text" class="form-control" placeholder="Price" value="<?php echo @$exp_price[1]; ?>" readonly>
						</div>
					</div>
					<div class="form-group">
						<label>Offered Price</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">$</span>
							</div>
							<?php @$exp_offeredprice = explode("$", @$getquotedetails['customerinfo']['offered_price']); ?>
							<input type="number" class="form-control" name="offered_price" id="offered_price" value="<?php echo @$exp_offeredprice[1]; ?>" min="0">
						</div>
					</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveOfferPrice()">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Offered price Modal-->

<!-- START Edit phone number  Modal-->
<div class="modal fade" id="editPhoneNumberModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Phone Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success d-none successToast" role="alert"></div>
                <div class="alert alert-danger d-none failToast" role="alert"></div>
                <form name="edit_phonenumber_form" method="post">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" placeholder="Phone Number" name="phone_number" id="phone_number" value="<?php echo $getquotedetails['customerinfo']['form_phone_number']; ?>">
                        </div>
                     </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="savePhoneNumber()">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Edit phone number Modal-->

<!-- START Edit payment method Modal-->
<div class="modal fade" id="editPaymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Payment Method</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success d-none successToast" role="alert"></div>
                <div class="alert alert-danger d-none failToast" role="alert"></div>
                <form name="edit_paymentmethod_form" method="post">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <div class="input-group">
                            <select class="form-control" name="payment_method" id="payment_method">
                                <option <?php if ( $getquotedetails['customerinfo']['receive_payment'] === 'Check – US First Class Mail (free)' ) { echo 'selected'; } ?>>Check – US First Class Mail (free)</option>
                                <option <?php if ( $getquotedetails['customerinfo']['receive_payment'] === 'PayPal – seller pays applicable PayPal fees' ) { echo 'selected'; } ?>>PayPal – seller pays applicable PayPal fees</option>
                                <option <?php if ( $getquotedetails['customerinfo']['receive_payment'] === 'Zelle' ) { echo 'selected'; } ?>>Zelle</option>
                                <option <?php if ( $getquotedetails['customerinfo']['receive_payment'] === 'macofalltrades.com Store Credit' ) { echo 'selected'; } ?>>macofalltrades.com Store Credit</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="savePaymentMethod()">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Edit payment method Modal-->

<script type="text/javascript">
	function saveOfferPrice(){
		var quote_id = '<?php echo $getquotedetails['customerinfo']['id'] ?>';
		var offered_price = $('#offered_price').val();
		var data = 'offered_price='+offered_price+'&quote_id='+quote_id;
		if(offered_price != '' &&offered_price != undefined && offered_price > 0){
			$.ajax({
					url: '<?php echo base_url(); ?>admin/quote/saveOfferPrice',
					type: 'post',
					data: {offered_price:offered_price, quote_id:quote_id},
					success: function( response ){
						var data = JSON.parse(response);
						if(data.status==1){;
							$('.successToast').html(data.message).removeClass('d-none').show().delay(3500).fadeOut();
							setTimeout(function () {
							    window.location.reload();
						    }, 3500);
						}else{						
							$('.failToast').html(data.message).removeClass('d-none').show().delay(3500).fadeOut();
						}
					},  
				});
		}else if(offered_price <= 0 ){
			$('.failToast').html('Please enter valid offered price.').removeClass('d-none').show().delay(3500).fadeOut();
		}else{						
			$('.failToast').html('Please enter offered price.').removeClass('d-none').show().delay(3500).fadeOut();
		}
	}
        function savePhoneNumber() {
            var quote_id = '<?php echo $getquotedetails['customerinfo']['id'] ?>';
            var new_val = $('#phone_number').val();
            if(new_val != '' &&new_val != undefined){
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/quote/savePhoneNumber',
                    type: 'post',
                    data: {phone_number:new_val, quote_id:quote_id},
                    success: function( response ){
                        var data = JSON.parse(response);
                        if(data.status==1){
                            $('#editPhoneNumberModal .successToast').html(data.message).removeClass('d-none').show().delay(3500).fadeOut();
                            setTimeout(function () {
                                window.location.reload();
                            }, 3500);
                        }else{
                            $('#editPhoneNumberModal .failToast').html(data.message).removeClass('d-none').show().delay(3500).fadeOut();
                        }
                    },
                });
            }else{
                $('#editPhoneNumberModal .failToast').html('Please enter phone number.').removeClass('d-none').show().delay(3500).fadeOut();
            }
        }
        function savePaymentMethod() {
            var quote_id = '<?php echo $getquotedetails['customerinfo']['id'] ?>';
            var new_val = $('#payment_method').val();
            if(new_val != '' &&new_val != undefined){
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/quote/savePaymentMethod',
                    type: 'post',
                    data: {payment_method:new_val, quote_id:quote_id},
                    success: function( response ){
                        var data = JSON.parse(response);
                        if(data.status==1){
                            $('#editPaymentMethodModal .successToast').html(data.message).removeClass('d-none').show().delay(3500).fadeOut();
                            setTimeout(function () {
                                window.location.reload();
                            }, 3500);
                        }else{
                            $('#editPaymentMethodModal .failToast').html(data.message).removeClass('d-none').show().delay(3500).fadeOut();
                        }
                    },
                });
            }else{
                $('#editPaymentMethodModal .failToast').html('Please select payment method.').removeClass('d-none').show().delay(3500).fadeOut();
            }
        }
</script>
