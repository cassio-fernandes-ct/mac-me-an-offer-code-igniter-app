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
							Edit New WebHooks
						</li>
					</ul>
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
						 <form action="<?php echo $this->config->site_url();?>/admin/managewebhook/update" class="form-horizontal form-bordered form-label-stripped" enctype="multipart/form-data" method="post" id="bannerfrm" name="bannerfrm">
							 <div class="form-body">
									<div class="form-group">
										<label  class="control-label col-md-3">Scope<span class="required">* </span></label>
										<div class="col-md-9">
											<input readonly="readonly" type="text" class="form-control"  id="scope" name="scope" value="<?php echo $formdata["scope"]; ?>"/>
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
								<input type="hidden" name="hook_id" id="hook_id" value="<?php echo $formdata["hook_id"]; ?>"/>
							</form>	
						</div>
                    </div>
               </div>
          </div>
    </div>
</div>
