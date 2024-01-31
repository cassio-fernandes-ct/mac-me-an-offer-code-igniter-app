<div class="clearfix">
</div>
<div class="page-container">
   <?php echo $left_nav;?>
	<div class="page-content-wrapper">
		<div class="page-content">
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					<?php echo $this->lang->line('BANNER_TITLE');?>
					</h3>
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo $this->config->site_url();?>/admin/dashboard">
								<?php echo $this->lang->line('HOME');?>
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="<?php echo $this->config->site_url();?>/admin/managewebhook">
								WebHooks
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							Add New WebHooks
						</li>
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>	
			<div class="tab-pane" id="tab_7">
				 <div class="portlet box green ">
					  <div class="portlet-title">
							<div class="caption">
								<?php echo $page_view; ?>
							</div>
					  </div>
					  
					  <div class="portlet-body form">
						 <?php
							$inserterror = $this->session->userdata('inserterror');							
							?>
							<?php if(isset($inserterror) && !empty($inserterror)){ ?>
							<div class="alert alert-danger">
								  <button class="close" data-dismiss="alert">×</button>
								  <strong>There are some errors. Please correct them and submit again.</strong>
						   </div>
						   <?php $this->session->unset_userdata('inserterror'); } ?>
						 <form onsubmit="webhookvalidation();" action="<?php echo $this->config->site_url();?>/admin/managewebhook/add" class="form-horizontal form-bordered form-label-stripped" enctype="multipart/form-data" method="post" id="webhook" name="webhook">
							<div class="form-body">
									<div class="form-group">
										<label  class="control-label col-md-3">Scope<span class="required">* </span></label>
										<div class="col-md-9">
											
											<select class="select2me form-control "  id="scope" name="scope">
											
												<option value="">--Choose a Scope--</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/order/*'){echo 'selected="selected"';}?> value="store/order/*">store/order/*</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/order/created'){echo 'selected="selected"';}?> value="store/order/created">store/order/created</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/order/updated'){echo 'selected="selected"';}?> value="store/order/updated">store/order/updated</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/order/archived'){echo 'selected="selected"';}?> value="store/order/archived">store/order/archived</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/order/statusUpdated'){echo 'selected="selected"';}?> value="store/order/statusUpdated">store/order/statusUpdated</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/order/message/created'){echo 'selected="selected"';}?> value="store/order/message/created">store/order/message/created</option>
												
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/product/*'){echo 'selected="selected"';}?> value="store/product/*">store/product/*</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/product/created'){echo 'selected="selected"';}?> value="store/product/created">store/product/created</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/product/updated'){echo 'selected="selected"';}?> value="store/product/updated">store/product/updated</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/product/deleted'){echo 'selected="selected"';}?> value="store/product/deleted">store/product/deleted</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/product/inventory/updated'){echo 'selected="selected"';}?> value="store/product/inventory/updated">store/product/inventory/updated</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/product/inventory/order/updated'){echo 'selected="selected"';}?> value="store/product/inventory/order/updated">store/product/inventory/order/updated</option>
												
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/product/inventory/*'){echo 'selected="selected"';}?> value="store/product/inventory/*">store/product/inventory/*</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/product/inventory/updated'){echo 'selected="selected"';}?> value="store/product/inventory/updated">store/product/inventory/updated</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/product/inventory/order/updated'){echo 'selected="selected"';}?> value="store/product/inventory/order/updated">store/product/inventory/order/updated</option>
												
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == '>store/category/*'){echo 'selected="selected"';}?> value="store/category/*">store/category/*</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/category/created'){echo 'selected="selected"';}?> value="store/category/created">store/category/created</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/category/updated'){echo 'selected="selected"';}?> value="store/category/updated">store/category/updated</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/category/deleted'){echo 'selected="selected"';}?> value="store/category/deleted">store/category/deleted</option>
												
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/sku/*'){echo 'selected="selected"';}?> value="store/sku/*">store/sku/*</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/sku/created'){echo 'selected="selected"';}?> value="store/sku/created">store/sku/created</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/sku/updated'){echo 'selected="selected"';}?> value="store/sku/updated">store/sku/updated</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/sku/deleted'){echo 'selected="selected"';}?> value="store/sku/deleted">store/sku/deleted</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/sku/inventory/updated'){echo 'selected="selected"';}?> value="store/sku/inventory/updated">store/sku/inventory/updated</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/sku/inventory/order/updated'){echo 'selected="selected"';}?> value="store/sku/inventory/order/updated">store/sku/inventory/order/updated</option>
												
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/sku/inventory/*'){echo 'selected="selected"';}?> value="store/sku/inventory/*">store/sku/inventory/*</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/sku/inventory/updated'){echo 'selected="selected"';}?> value="store/sku/inventory/updated">store/sku/inventory/updated</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/sku/inventory/order/updated'){echo 'selected="selected"';}?> value="store/sku/inventory/order/updated">store/sku/inventory/order/updated</option>
												
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/customer/*'){echo 'selected="selected"';}?> value="store/customer/*">store/customer/*</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/customer/created'){echo 'selected="selected"';}?> value="store/customer/created">store/customer/created</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/customer/updated'){echo 'selected="selected"';}?> value="store/customer/updated">store/customer/updated</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/customer/deleted'){echo 'selected="selected"';}?> value="store/customer/deleted">store/customer/deleted</option>
												
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/information/updated'){echo 'selected="selected"';}?> value="store/information/updated">store/information/updated</option>
												
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/shipment/*'){echo 'selected="selected"';}?> value="store/shipment/*">store/shipment/*</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/shipment/created'){echo 'selected="selected"';}?> value="store/shipment/created">store/shipment/created</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/shipment/updated'){echo 'selected="selected"';}?> value="store/shipment/updated">store/shipment/updated</option>
												<option <?php if(isset($formdata["scope"]) && !empty($formdata["scope"]) && $formdata["scope"] == 'store/shipment/deleted'){echo 'selected="selected"';}?> value="store/shipment/deleted">store/shipment/deleted</option>
											</select>
											<span class="help-block">[Enter Scope for e.x store/product/*]</span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Destination<span class="required">* </span></label>
										<div class="col-md-9">
											<input type="text" id="destination" name="destination" value="<?php echo $formdata['destination'];?>" class="form-control"/>
											<span class="help-block">
												 [Enter Destination URL for e.x <?php echo $this->config->site_url();?>/admin/webhookcreate/producthook]
											</span>
										</div>
									</div>
								</div>
								<div class="form-actions fluid">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-offset-3 col-md-9">
												<button class="btn green" type="submit"><i class="fa fa-check"></i> Submit</button>
												<button class="btn default" onClick="window.location='<?php echo $this->config->site_url();?>/admin/managewebhook';" type="button">Cancel</button>
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