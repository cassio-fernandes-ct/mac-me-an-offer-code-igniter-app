<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
				Settings Management  </h3>
				<span class="kt-subheader__separator kt-hidden"></span>
				<div class="kt-subheader__breadcrumbs" style = "display: none">
					<a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
					<span class="kt-subheader__breadcrumbs-separator"></span>
					<a href="" class="kt-subheader__breadcrumbs-link">
					KTDatatable </a>
					<span class="kt-subheader__breadcrumbs-separator"></span>
					<a href="" class="kt-subheader__breadcrumbs-link">
					Base </a>
					<span class="kt-subheader__breadcrumbs-separator"></span>
					<a href="" class="kt-subheader__breadcrumbs-link">
					Ajax Data </a>
					<!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
				</div>
			</div>
			<div class="kt-subheader__toolbar">
				
			</div>
		</div>
	</div>
	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
					<i class="kt-font-brand flaticon2-console"></i> Settings
					</h3>
				</div>
				<div class="kt-subheader__toolbar">
					<div class="kt-portlet__head-wrapper" style="padding-top: 10px">
						<a target="blank" href="<?php echo $this->config->base_url(); ?>admin/product/importproductdatabase?run_hash=f06691038d85a6de710b38ba80291fa38f076cc827159d453aa1c4abe8345166" class="btn btn-brand btn-icon-sm">
							<i class="flaticon2-plus"></i>
							Product Sync
						</a>
					</div>
				</div>
			</div>
			
			<div class="kt-portlet__body">
				<ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x nav-tabs-line-success" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#kt_tabs_4_1" role="tab">General Settings</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#kt_tabs_4_4" role="tab">Bigcommerce API Settings</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#kt_tabs_4_3" role="tab">Shipping Settings</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#kt_tabs_4_5" role="tab">User Management</a>
					</li>					
				</ul>
				<div class="tab-content">
					<?php
					$store_url = array(
					'type'  => 'text',
					'name'  => 'storeurl',
					'id'    => 'storeurl',
					'value' => $settingdata["storeurl"],
					'class' => 'form-control'
					);
					$store_front_url = array(
					'type'  => 'text',
					'name'  => 'store_front_url',
					'id'    => 'store_front_url',
					'value' => $settingdata["store_front_url"],
					'class' => 'form-control'
					);
					?>
					
					<?php if(!empty(validation_errors())) { ?>
					<div class="alert alert-danger ">
						<h3>Oops!</h3>
						<ul>
							<?php echo validation_errors(); ?>
						</ul>
					</div>
					<?php }   ?>
					<?php if ($success == 1): ?>
					<div class="alert alert-solid-success alert-bold" role="alert">
						<div class="alert-text"><?php echo $this->lang->line('GENERAL_SUCC'); ?></div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
					</div>
					<?php endif; ?>
					<?php $update 	= $this->session->userdata('updatedataproductdata');
					if(isset($update) && !empty($update)){ ?>
					<div class="alert alert-solid-danger alert-bold" role="alert">
						<div class="alert-text">The BigCommerce product update process has been completed successfully !!!</div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
					</div>
					<?php $this->session->unset_userdata('updatedataproductdata'); } ?>
					<?php $updatee 	= $this->session->userdata('updatedata');
					if(isset($updatee) && !empty($updatee)){ ?>
					<div class="alert alert-solid-success alert-bold" role="alert">
						<div class="alert-text">Settings saved successfully.</div>
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
											
										</div>
									</div>
									
									<form action="https://app.macmeanoffer.com/admin/settings/generalsetting" class="kt-form kt-form--label-right" accept-charset="utf-8" method="post">
										<div class="kt-portlet__body">
											<div class="form-group row">
												<label for="protocol" class="col-2 col-form-label">Protocol  </label>
												<div class="col-10">
													<input value="<?php echo $settingdata["protocol"] ?>" id="protocol" name="protocol" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="smtp_user" class="col-2 col-form-label">Smtp User  </label>
												<div class="col-10">
													<input value="<?php echo $settingdata["smtp_user"] ?>" id="smtp_user" name="smtp_user" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="smtp_port" class="col-2 col-form-label">Smtp Port</label>
												<div class="col-10">
													<input value="<?php echo $settingdata["smtp_port"] ?>" id="smtp_port" name="smtp_port" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="smtp_host" class="col-2 col-form-label">Smtp Host  </label>
												<div class="col-10">
													<input value="<?php echo $settingdata["smtp_host"] ?>" id="smtp_host" name="smtp_host" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="smtp_pass" class="col-2 col-form-label">Smtp Pass  </label>
												<div class="col-10">
													<input value="<?php echo $settingdata["smtp_pass"] ?>" id="smtp_pass" name="smtp_pass" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="smtp_pass" class="col-2 col-form-label">Email Address</label>
												<div class="col-10">
													<input value="<?php echo $settingdata["admin_email"] ?>" id="admin_email" name="admin_email" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="client_secret" class="col-2 col-form-label">Email Template</label>
												<div class="col-10">
													<textarea id="email_template" name="email_template" rows="6" class="ckeditor"><?php echo $settingdata["email_template"] ?></textarea>
												</div>
											</div>
											<div class="form-group row">
												<label for="client_secret" class="col-2 col-form-label">Admin Email Template</label>
												<div class="col-10">
													<textarea id="admin_email_template" name="admin_email_template" rows="6" class="ckeditor"><?php echo $settingdata["admin_email_template"] ?></textarea>
												</div>
											</div>
											<div class="kt-portlet__head">
											</div>
											<div class="form-group row"></div>
											<div class="form-group row">
												<label for="smtp_user" class="col-2 col-form-label">Mac Me An Offer </label>
												<div class="col-10">
													<input value="<?php echo $settingdata["mmo_url"] ?>" id="mmo_url" name="mmo_url" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="smtp_user" class="col-2 col-form-label">Mac Of All Trades </label>
												<div class="col-10">
													<input value="<?php echo $settingdata["mmt_url"] ?>" id="mmt_url" name="mmt_url" type="text" class="form-control">
												</div>
											</div>
										</div>
										<div class="kt-portlet__foot">
											<div class="kt-form__actions">
												<div class="row">
													<div class="col-2">
													</div>
													<div class="col-10">
														<button type="submit" class="btn btn-success">Submit</button>
														<!--<button type="reset" class="btn btn-secondary">Cancel</button>-->
													</div>
												</div>
											</div>
										</div>
									</form>
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

										<?php if(!empty(validation_errors())) { ?>
										<div class="alert alert-danger ">
											<h3>Oops!</h3>
											<ul>
												<?php echo validation_errors(); ?>
											</ul>
										</div>
										<?php }   ?>
										<?php echo form_open(base_url().'admin/settings/update/shipping',['class'=>'kt-form kt-form--label-right','accept-charset'=>'utf-8']); ?>
											<div class="kt-portlet__body">
												<!--<?php if ($success == 1): ?>
												
												<div class="alert alert-solid-success alert-bold" role="alert">
													<div class="alert-text"><?php echo $this->lang->line('GENERAL_SUCC'); ?></div>
													<div class="alert-close">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true"><i class="la la-close"></i></span>
														</button>
													</div>
												</div>
												<?php endif; ?>
												<?php $update 	= $this->session->userdata('updatedataproductdata');
												if(isset($update) && !empty($update)){ ?>
												<div class="alert alert-solid-danger alert-bold" role="alert">
													<div class="alert-text">The big-commerce product import/update process had completed successfully !!!</div>
													<div class="alert-close">
														<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true"><i class="la la-close"></i></span>
														</button>
													</div>
												</div>
												<?php $this->session->unset_userdata('updatedataproductdata'); } ?> -->
												<div class="kt-portlet__head">
													<div class="kt-portlet__head-label">
														<h3 class="kt-portlet__head-title">
													  Enable Or Disable Shipping Label 
														</h3>
													</div>
												</div>
												<br>
												<div class="form-group row">
													<label for="storehas" class="col-3 col-form-label">Generate Shipping Label</label>
													<div class="col-9">
														<div class="bootstrap-switch-on bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-animate" style="width: 194px;">
															<div class="bootstrap-switch-container" style="width: 288px; margin-left: 0px;">
																<input data-switch="true" name="enable" value="1" type="checkbox" <?php echo $settingdata["is_shipping_module_enabled"] ? 'checked="checked"' : '';    ?>   data-on-text="Enabled" data-handle-width="70" data-off-text="Disabled" data-on-color="brand">
															</div>
														</div>
													</div>
												</div>	

												<!-- START : FedEx settings  -->
												<div class="kt-portlet__head" style="display:none">
													<div class="kt-portlet__head-label">
														<h3 class="kt-portlet__head-title">
													  		FedEx Account Information
														</h3>
													</div>
												</div>
												<br>
												<div class="form-group row" style="display:none">
													<label for="fedex_api_key" class="col-2 col-form-label">API Key</label>
													<div class="col-10">
														<input value="<?php echo $settingdata["fedex_api_key"] ?>" id="fedex_api_key" name="fedex_api_key" type="text" class="form-control">
													</div>
												</div>
												<div class="form-group row" style="display:none">
													<label for="fedex_api_secret" class="col-2 col-form-label">API Secret </label>
													<div class="col-10">
														<input value="<?php echo $settingdata["fedex_api_secret"] ?>" id="fedex_api_secret" name="fedex_api_secret" type="text" class="form-control">
													</div>
												</div>
												<!-- END : FedEx settings  -->

												<!-- START : UPS settings  -->
												<div class="kt-portlet__head" style="">
													<div class="kt-portlet__head-label">
														<h3 class="kt-portlet__head-title">
													  		UPS Account Information
														</h3>
													</div>
												</div>
												<br>
												<div class="form-group row" style="">
													<label for="ups_name" class="col-2 col-form-label">Email Or User ID</label>
													<div class="col-10">
														<input value="<?php echo $settingdata["ups_user_id"] ?>" id="ups_name" name="ups_name" type="text" class="form-control">
													</div>
												</div>
												<div class="form-group row" style="">
													<label for="ups_password" class="col-2 col-form-label">Password </label>
													<div class="col-10">
														<input value="<?php echo $settingdata["ups_password"] ?>" id="ups_password" name="ups_password" type="text" class="form-control">
													</div>
												</div>	
												<div class="form-group row" style="">
													<label for="ups_shipper_id" class="col-2 col-form-label">Shipper Number</label>
													<div class="col-10">
														<input value="<?php echo $settingdata["ups_shipper_id"] ?>" id="ups_shipper_id" name="ups_shipper_id" type="text" class="form-control">
													</div>
												</div>	
												<div class="form-group row" style="">
													<label for="ups_access_key" class="col-2 col-form-label">Access Key</label>
													<div class="col-10">
														<input value="<?php echo $settingdata["ups_access_key"] ?>" id="ups_access_key" name="ups_access_key" type="text" class="form-control">
													</div>
												</div>	
												<div class="form-group row" style="">
													<label for="ups_environment" class="col-2 col-form-label">Mode </label>
													<div class="col-10">
														<select name="ups_environment" class="form-control">
															<option <?php echo  $settingdata["ups_environment"] == 'Testing'? 'selected="selected"' : '' ; ?> > Testing </option>
															<option <?php echo  $settingdata["ups_environment"] == 'Production'? 'selected="selected"' : '' ; ?> > Production </option>

														</select>
													</div>
												</div>	
												<div class="kt-portlet__head">
													<div class="kt-portlet__head-label">
														<h3 class="kt-portlet__head-title">
													  Default Ship To Address
														</h3>
													</div>
												</div>
												<br>
												<div class="form-group row">
													<label for="name" class="col-2 col-form-label">Name</label>
													<div class="col-10">
														<input value="<?php echo $settingdata["shipto_name"] ?>" id="name" name="name" type="text" class="form-control">
													</div>
												</div>	
												<div class="form-group row">
													<label for="address1" class="col-2 col-form-label">Address Line 1</label>
													<div class="col-10">
														<input value="<?php echo $settingdata["shipto_address_line_1"] ?>" id="address1" name="address1" type="text" class="form-control">
													</div>
												</div>	
												<div class="form-group row">
													<label for="address2" class="col-2 col-form-label">Address Line 2</label>
													<div class="col-10">
														<input value="<?php echo $settingdata["shipto_address_line_2"] ?>" id="address2" name="address2" type="text" class="form-control">
													</div>
												</div>					  
												 
												<div class="form-group row">
													<label for="city" class="col-2 col-form-label">City </label>
													<div class="col-10">
														<input value="<?php echo $settingdata["shipto_city"] ?>" id="city" name="city" type="text" class="form-control">
													</div>
												</div>					  
												<div class="form-group row">
													<label for="state" class="col-2 col-form-label">State </label>
													<div class="col-10">
														<input value="<?php echo $settingdata["shipto_state"] ?>" id="state" name="state" type="text" class="form-control">
													</div>
												</div>	
												<div class="form-group row">
													<label for="country" class="col-2 col-form-label">Country </label>
													<div class="col-10">
														<input value="<?php echo $settingdata["shipto_country"] ?>" id="country" name="country" type="text" class="form-control">
													</div>
												</div>
												<div class="form-group row">
													<label for="pincode" class="col-2 col-form-label">Pincode </label>
													<div class="col-10">
														<input value="<?php echo $settingdata["shipto_pincode"] ?>" id="pincode" name="pincode" type="text" class="form-control">
													</div>
												</div>		
												<div class="form-group row">
													<label for="number" class="col-2 col-form-label">Contact Number </label>
													<div class="col-10">
														<input value="<?php echo $settingdata["shipto_number"] ?>" id="number" name="number" type="text" class="form-control">
													</div>
												</div>	
												<div class="form-group row">
													<label for="snumber" class="col-2 col-form-label">Shipper Number </label>
													<div class="col-10">
														<input value="<?php echo $settingdata["shipper_number"] ?>" id="snumber" name="snumber" type="text" class="form-control">
													</div>
												</div>
											</div>
											<div class="kt-portlet__foot">
												<div class="kt-form__actions">
													<div class="row">
														<div class="col-2">
														</div>
														<div class="col-10">
															<button type="submit" class="btn btn-success">Submit</button>
															<!--<button type="reset" class="btn btn-secondary">Cancel</button>-->
														</div>
													</div>
												</div>
											</div>
										</form>

									</div>
								</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="kt_tabs_4_4" role="tabpanel" >
						<div class="kt-portlet__body">
							<div class="kt-widget12">
								<div class="kt-widget12__content">
									<div class="kt-widget12__item" style="margin-bottom: 0;">
										<div class="kt-widget12__info">
											
										</div>
									</div>
									<!--begin::Form-->
									<?php $url = current_url().'/tradesapi' ?>
									<?php echo form_open($url,['class'=>'kt-form kt-form--label-right','accept-charset'=>'utf-8']); ?>
										
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title">
											 		Mac Of All Trades API Configuration
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body">
											<div class="form-group row">
												<label for="storeurltrades" class="col-2 col-form-label">Store URL  </label>
												<div class="col-10">
													<input value="<?php echo @$settingdata["storeurltrades"] ?>" id="storeurltrades" name="storeurltrades" type="text" class="form-control">
													
												</div>
											</div>
											<div class="form-group row">
												<label for="store_front_url_trades" class="col-2 col-form-label">Store Front URL</label>
												<div class="col-10">
													<input value="<?php echo @$settingdata["store_front_url_trades"] ?>" id="store_front_url_trades" name="store_front_url_trades" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="apiusernametrades" class="col-2 col-form-label">API Username</label>
												<div class="col-10">
													<input value="<?php echo @$settingdata["apiusernametrades"] ?>" id="apiusernametrades" name="apiusernametrades" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="apipathtrades" class="col-2 col-form-label">API Path</label>
												<div class="col-10">
													<input value="<?php echo @$settingdata["apipathtrades"] ?>" id="apipathtrades" name="apipathtrades" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="apitokentrades" class="col-2 col-form-label">Access Token</label>
												<div class="col-10">
													<input value="<?php echo @$settingdata["apitokentrades"] ?>" id="apitokentrades" name="apitokentrades" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="storehastrades" class="col-2 col-form-label">Store Has</label>
												<div class="col-10">
													<input value="<?php echo @$settingdata["storehastrades"] ?>" id="storehastrades" name="storehastrades" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="client_idtrades" class="col-2 col-form-label">Client Id</label>
												<div class="col-10">
													<input value="<?php echo @$settingdata["client_idtrades"] ?>" id="client_idtrades" name="client_idtrades" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="client_secrettrades" class="col-2 col-form-label">Client Secret</label>
												<div class="col-10">
													<input value="<?php echo @$settingdata["client_secrettrades"] ?>" id="client_secrettrades" name="client_secrettrades" type="text" class="form-control">
												</div>
											</div>
										</div>
										
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title">
											 		Mac Me An Offer API Configuration
												</h3>
											</div>
										</div>
										<div class="kt-portlet__body">
											<div class="form-group row">
												<label for="storeurl" class="col-2 col-form-label">Store URL  </label>
												<div class="col-10">
													<?php echo form_input($store_url); ?>
													
												</div>
											</div>
											<div class="form-group row">
												<label for="store_front_url" class="col-2 col-form-label">Store Front URL</label>
												<div class="col-10">
													<?php echo form_input($store_front_url); ?>
												</div>
											</div>
											<div class="form-group row">
												<label for="apiusername" class="col-2 col-form-label">API Username</label>
												<div class="col-10">
													<input value="<?php echo $settingdata["apiusername"] ?>" id="apiusername" name="apiusername" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="apipath" class="col-2 col-form-label">API Path</label>
												<div class="col-10">
													<input value="<?php echo $settingdata["apipath"] ?>" id="apipath" name="apipath" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="apitoken" class="col-2 col-form-label">Access Token</label>
												<div class="col-10">
													<input value="<?php echo $settingdata["apitoken"] ?>" id="apitoken" name="apitoken" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="storehas" class="col-2 col-form-label">Store Has</label>
												<div class="col-10">
													<input value="<?php echo $settingdata["storehas"] ?>" id="storehas" name="storehas" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="client_id" class="col-2 col-form-label">Client Id</label>
												<div class="col-10">
													<input value="<?php echo $settingdata["client_id"] ?>" id="client_id" name="client_id" type="text" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label for="client_secret" class="col-2 col-form-label">Client Secret</label>
												<div class="col-10">
													<input value="<?php echo $settingdata["client_secret"] ?>" id="client_secret" name="client_secret" type="text" class="form-control">
												</div>
											</div>
											<div style = "display: none;" class="form-group row">
												<label class="col-2 col-form-label">Store Logo</label>
												<div class="col-md-10">
													<span class="imagelist" id="files">
														<?php if (isset($settingdata['logo_image']) && !empty($settingdata['logo_image'])) {
														?>
														<div class="image_maindiv" id="<?php echo $settingdata['logo_image']; ?>" style="display:block">
															<img  src='<?php echo $this->config->base_url(); ?>application/uploads/sitelogo/thumb200/<?php echo $settingdata['logo_image']; ?>' border="0" alt="<?php echo $settingdata['logo_image']; ?>" class="group2">
															<a class="btn red delete image_removediv" onclick="removeimage('<?php echo $settingdata['logo_image']; ?>')" ><i class="fa fa-trash"></i><span> Delete</span></a>
														</div>
														<?php
														} ?>
													</span>
													<button id="banner_img" class="btn">Upload</button>
													<span id="status"></span>
												</div>
											</div>
										</div>
										<div class="kt-portlet__foot">
											<div class="kt-form__actions">
												<div class="row">
													<div class="col-2">
													</div>
													<div class="col-10">
														<button type="submit" class="btn btn-success">Submit</button>
														<!--<button type="reset" class="btn btn-secondary">Cancel</button>-->
													</div>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>

					<div class="tab-pane" id="kt_tabs_4_5" role="tabpanel">
						<div class="kt-portlet__body">
							<div class="kt-widget12">
								<div class="kt-widget12__content">
									<div class="kt-widget12__item" style="margin-bottom: 0;">
										<div class="kt-widget12__info">
											
										</div>
									</div>

									<div class="kt-actions">
										<div class="kt-actions__add-user">
											<a 
												class="btn btn-brand btn-icon-sm btn-create-new-user"
												data-toggle="modal" 
												data-target="#modal-create-new-user" 												
											>
												<i class="flaticon2-plus"></i>
												Create New User
											</a>

											<div 
												id="modal-create-new-user"
												class="modal fade"
											>
												<div class="modal-dialog modal-dialog-centered">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">Create New User</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<i aria-hidden="true" class="ki ki-close"></i>
															</button>
														</div>
														<div class="modal-body">
															<div class="alert alert-success d-none successToast" role="alert"></div>
															<div class="alert alert-danger d-none failToast" role="alert"></div>
															<form id="form-create-user" class="form-create-user js-form-create-user" method="post">
																<div class="form-group">
																	<label>Username<span class="input-group__required">*</span></label>
																	<div class="input-group">
																		<input 
																			type="text" 
																			class="form-control" 
																			placeholder="Username" 
																			name="username" 
																			required
																		/>
																		<p>Username should consist only of letters and numbers (no special characters).</p>
																	</div>
																</div>
																<div class="form-group">
																	<label>Role<span class="input-group__required">*</span></label>
																	<div class="input-group">
																		<select class="form-control" name="role">
																			<option value="user" selected>User</option>
																			<option value="admin">Admin</option>
																		</select>
																	</div>				
																</div>	
																<div class="form-group">
																	<label>First Name (optional)</label>
																	<div class="input-group">
																		<input 
																			type="text" 
																			class="form-control" 
																			placeholder="First Name" 
																			name="firstname"
																		/>
																	</div>
																</div>
																<div class="form-group">
																	<label>Last Name (optional)</label>
																	<div class="input-group">
																		<input 
																			type="text" 
																			class="form-control" 
																			placeholder="Last Name" 
																			name="lastname" 
																		/>
																	</div>	
																</div>
																<div class="form-group">
																	<label>Email<span class="input-group__required">*</span></label>
																	<div class="input-group">
																		<input 
																			type="email" 
																			class="form-control" 
																			placeholder="Email" 
																			name="email" 
																			required
																		/>
																	</div>		
																</div>													
																<div class="form-group">
																	<label>Password<span class="input-group__required">*</span></label>
																	<div class="input-group">
																		<input 
																			id="new_user_password"
																			type="password" 
																			class="form-control" 
																			placeholder="Password" 
																			name="password" 
																			required
																		/>
																	</div>
																</div>
																<button class="btn btn-success" type="submit">Create User</button>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<!-- List of users -->
									<div class="kt-datatable kt-datatable--default kt-datatable--loaded kt-datatable--users js-kt-datatable-ignore-ajax">
										<table class="kt-datatable__table">
											<thead class="kt-datatable__head">
												<tr class="kt-datatable__row kt-datatable__row--fixed">
													<th class="kt-datatable__cell">Username</th>
													<th class="kt-datatable__cell">First Name</th>
													<th class="kt-datatable__cell">Last Name</th>
													<th class="kt-datatable__cell">Email</th>
													<th class="kt-datatable__cell">Actions</th>
												</tr>
											</thead>
											<tbody class="kt-datatable__body">
												<?php foreach( $user_accounts as $user_account ): ?>
													<?php $user_account_id = abs( (int)$user_account->id ); ?>
													<tr class="kt-datatable__row kt-datatable__row--fixed" data-user-row="<?php echo $user_account_id; ?>">
														<td class="kt-datatable__cell"><?php echo htmlspecialchars( $user_account->username ); ?></td>
														<td class="kt-datatable__cell"><?php echo htmlspecialchars( $user_account->firstname ); ?></td>
														<td class="kt-datatable__cell"><?php echo htmlspecialchars( $user_account->lastname ); ?></td>
														<td class="kt-datatable__cell kt-datatable__cell--email"><?php echo htmlspecialchars( $user_account->email ); ?></td>
														<td class="kt-datatable__cell">
															<a 
																class="btn btn-outline-info btn-elevate btn-pill" 
																data-toggle="modal" 
																data-target="<?php printf( '#modal-update-password-user-%s', $user_account_id ); ?>" 
																type="button"
																tabindex
															>Update Password</a>
															
															<?php if( 'admin' !== $user_account->role ): ?>
																<a 
																	class="btn btn-outline-danger btn-elevate btn-pill kt-mt-10" 
																	data-toggle="modal" 
																	data-target="<?php printf( '#modal-delete-user-%s', $user_account_id ); ?>" 
																	type="button"
																	tabindex
																>Delete User</a>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>

										<?php foreach( $user_accounts as $user_account ): ?>
											<?php $user_account_id = abs( (int)$user_account->id ); ?>
											<?php // modal HTML taken from Quote view ?>
											<div 
												id="<?php printf( 'modal-update-password-user-%s', $user_account_id ); ?>"
												class="modal fade"
											>
												<div class="modal-dialog modal-dialog-centered">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">Update Password</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<i aria-hidden="true" class="ki ki-close"></i>
															</button>
														</div>
														<div class="modal-body">
															<div class="alert alert-success d-none successToast" role="alert"></div>
															<div class="alert alert-danger d-none failToast" role="alert"></div>
															<form 
																class="form-update-password" 
																onsubmit="updateUserPassword( <?php echo $user_account_id; ?> ); return false;"
															>
																<div class="form-group">
																	<label>Password</label>
																	<div class="input-group">
																		<input 
																			type="password" 
																			class="form-control" 
																			placeholder="Password" 
																			name="password" 
																		/>
																	</div>
																</div>
															</form>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-success" onclick="updateUserPassword( <?php echo $user_account_id; ?> )">Update</button>
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
														</div>
													</div>
												</div>
											</div>

											<?php if( 'admin' !== $user_account->role ): ?>
											<div 
												id="<?php printf( 'modal-delete-user-%s', $user_account_id ); ?>"
												class="user-account-modal modal fade"
											>
												<div class="modal-dialog modal-dialog-centered">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">Delete User</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<i aria-hidden="true" class="ki ki-close"></i>
															</button>
														</div>
														<div class="modal-body">
															<div class="alert alert-success d-none successToast" role="alert"></div>
															<div class="alert alert-danger d-none failToast" role="alert"></div>
															<p>Are you sure you want to delete user: <strong><?php echo htmlspecialchars( $user_account->username ); ?></strong>? This cannot be undone.</p>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-success js-btn-user-delete" onclick="deleteUser( <?php echo $user_account_id; ?> )">Delete</button>
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
														</div>
													</div>
												</div>
											</div>
											<?php endif; ?>
										<?php endforeach; ?>

									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>
<!--form end-->
</div>
</div>


<script src="<?php echo base_url(); ?>assets/js/pages/crud/forms/widgets/bootstrap-switch.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">
jQuery(document).ready(function() {
	
	var btnUpload=$('#banner_img');
	var status=$('#status');
	new AjaxUpload(btnUpload, {
action: '<?php echo $this->config->site_url(); ?>/admin/settings/ajaxupload/',
name: 'uploadfile[]',
multiple: false,
onSubmit: function(file, ext)
{
if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
status.text('Only JPG, PNG or GIF files are allowed');
return false;
}status.html('<img src="<?php echo $this->config->base_url(); ?>/assets/img/loader.gif">');
},
onComplete: function(file, response)
{
status.html('');
status.text('');
var responseObj = jQuery.parseJSON(response);
if(responseObj.status=="success")
{
var images_data = responseObj.success_data.original;
$.each(images_data,function(index, value ){
var  imagename = "'"+value.file_name+"'";
$('#files').html('');
$('<span></span>').appendTo('#files').html('<div class="image_maindiv" id="'+value.file_name+'" style="display:block"><img src="<?php echo $this->config->base_url().'application/uploads/sitelogo/thumb200/'; ?>'+value.file_name+'" alt=""  /><a class="btn red delete image_removediv" onclick="removeimage('+imagename+')" ><i class="fa fa-trash"></i><span> Delete</span></a><input type="hidden" name="banner_images[]" value="'+value.file_name+'" />').addClass('success');
});
}
else
{
$('<span></span>').appendTo('#files').text(response.error_data).addClass('error');
}
}});
});
function removeimage(str)
{
var status=$('#status');
status.html('<img src="<?php echo $this->config->base_url(); ?>/assets/img/loader.gif">');
if (window.XMLHttpRequest)
{
xmlhttp=new XMLHttpRequest();
}
else
{
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function()
{
if (xmlhttp.readyState==4 && xmlhttp.status==200)
{
status.html('');
$("#banner_images").val('');
document.getElementById(str).style.display="none";
}
}
var url="<?php echo $this->config->site_url(); ?>/admin/setting/ajaxdelete";
url=url+"?imgname="+str;
xmlhttp.open("GET",url,true);
xmlhttp.send();
return false;
}
</script>


<!-- @todo refactor to be more DRY -->
<script>
function updateUserPassword( userAccountId ) {
	const modal = document.getElementById( `modal-update-password-user-${userAccountId}` )

	if( !modal ) {
		return
	}

	const form = modal.querySelector('.form-update-password')
	
	if( !form || !( form instanceof HTMLFormElement) ) {
		return
	}

	const elSuccessToast = modal.querySelector('.successToast')
	const elFailToast = modal.querySelector('.failToast')
	
	const formData = new FormData( form )

	const password = formData.get( 'password' ).trim()

	// confirm password is at least 12 characters
	if( 12 > password.length ) {
		return showError( 'Please enter a password that is at least 12 characters long.' )
	}

	$.ajax({
		url: '<?php printf( '%s/admin/settings/updatePassword', $this->config->site_url() ); ?>',
		type: 'POST',
		data: {
			userId: userAccountId,
			password
		},
		beforeSend() {
			resetModalState()
		},
		success( res ) {
			res = JSON.parse( res )

			if( !res.success ) {
				showError( res.data )
			} else {
				showSuccess( res.data )
			}
		}, 
		error( err ) {
			if( 'string' === typeof err ) {
				showError( err )
			} else {
				console.warn( err )
				showError( 'We hit an unexpected error. Please refresh the page and try again.' )
			}
		}
	})

	function resetModalState() {
		elSuccessToast.classList.add( 'd-none' )
		elFailToast.classList.add( 'd-none' )

		elSuccessToast.textContent = ''
		elFailToast.textContent = ''
	}

	function showError( errorMsg ) {
		elFailToast.classList.remove( 'd-none' )
		elFailToast.textContent = errorMsg
	}

	function showSuccess( successMsg ) {
		elSuccessToast.classList.remove( 'd-none' )
		elSuccessToast.textContent = successMsg
	}
}


function deleteUser( userAccountId ) {
	const modal = document.getElementById( `modal-delete-user-${userAccountId}` )

	if( !modal ) {
		return
	}

	const elSuccessToast = modal.querySelector('.successToast')
	const elFailToast = modal.querySelector('.failToast')

	$.ajax({
		url: '<?php printf( '%s/admin/settings/deleteUser', $this->config->site_url() ); ?>',
		type: 'POST',
		data: {
			userId: userAccountId,
		},
		beforeSend() {
			resetModalState()
		},
		success( res ) {
			res = JSON.parse( res )

			if( !res.success ) {
				showError( res.data )
				
			} else {
				showSuccess( res.data )

				// remove user row from UI
				$(`.kt-datatable__row[data-user-row="${userAccountId}"]`).remove()

				// disable button to prevent user trying to submit again
				const btn = modal.querySelector('.js-btn-user-delete')
				if( btn ) {
					btn.setAttribute( 'disabled', true )
				}
			}
		}, 
		error( err ) {
			if( 'string' === typeof err ) {
				showError( err )
			} else {
				console.warn( err )
				showError( 'We hit an unexpected error. Please refresh the page and try again.' )
			}
		}
	})

	function resetModalState() {
		elSuccessToast.classList.add( 'd-none' )
		elFailToast.classList.add( 'd-none' )

		elSuccessToast.textContent = ''
		elFailToast.textContent = ''
	}

	function showError( errorMsg ) {
		elFailToast.classList.remove( 'd-none' )
		elFailToast.textContent = errorMsg
	}

	function showSuccess( successMsg ) {
		elSuccessToast.classList.remove( 'd-none' )
		elSuccessToast.textContent = successMsg
	}
}



( () => {
	const $modal = $('#modal-create-new-user')
	const $form = $('#form-create-user')

	if( !$modal.length || !$form.length ) {
		return
	}

	const $elSuccessToast = $modal.find('.successToast')
	const $elFailToast = $modal.find('.failToast')	

	$.validator.addMethod( 'alphaNumericOnly', value => {
		return /^[A-Za-z0-9]*$/.test( value )
	}, 'Username must contain letters and numbers only.' )

	$form.validate({
		rules: {
			username: {
				minlength: 5,
				alphaNumericOnly: true,
			},
			password: {
				minlength: 12
			},
		},
		submitHandler( form, e ) {
			e.preventDefault()

			$.ajax({
				url: '<?php printf( '%s/admin/settings/createUser', $this->config->site_url() ); ?>',
				type: 'POST',
				data: $(form).serialize(),
				beforeSend() {
					resetModalState()
				},
				success( res ) {
					res = JSON.parse( res )

					if( !res.success ) {
						showError( res.data )
					} else {
						form.reset()

						showSuccess( res.data )

						// refresh page
						setTimeout( () => {
							window.location.href = '<?php printf( '%s/admin/settings', $this->config->site_url() ); ?>'
						}, 2000 )
					}
				}, 
				error( err ) {
					if( 'string' === typeof err ) {
						showError( err )
					} else {
						console.warn( err )
						showError( 'We hit an unexpected error. Please refresh the page and try again.' )
					}
				}
			})
		}
	})


	function resetModalState() {
		$elSuccessToast.addClass( 'd-none' )
		$elFailToast.addClass( 'd-none' )

		$elSuccessToast.html( '' )
		$elFailToast.html( '' )
	}

	function showError( errorMsg ) {
		$elFailToast.removeClass( 'd-none' )
		$elFailToast.html( errorMsg )
	}

	function showSuccess( successMsg ) {
		$elSuccessToast.removeClass( 'd-none' )
		$elSuccessToast.html( successMsg )
	}	

}) ()

</script>




<style type="text/css">
	
#kt_tabs_4_3 .kt-portlet__body br
{ margin-bottom: 15px;
}
</style>