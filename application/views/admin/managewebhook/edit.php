<?php
$serise_title = array(
	'type'  => 'text',
	'id'  => 'destination',
	'name'  => 'destination',
	'value'  =>   '',
	'class' => 'form-control',
);
?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
						Managewebhook Management  </h3>
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
						Update Webhook
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
            		<div class="kt-portlet__head-wrapper" >
						
					</div>		
				</div>
			</div>
			<div class="kt-portlet__body kt-portlet__body--fit">
				
			<form action="<?php echo $this->config->base_url();?>admin/managewebhook/update" class="form-horizontal form-bordered form-label-stripped" enctype="multipart/form-data" method="post" id="webhook" name="webhook">
				<div class="kt-portlet__body">
					<?php require_once APPPATH.'views/admin/common/display_errors.php' ?>
					<div id = "serial_number_exists" class="" ></div>
					
					<div class="form-group row">
						<label for="title" class="col-2 col-form-label"> Scope</label>
						<div class="col-10">
							<input readonly="readonly" type="text" class="form-control"  id="scope" name="scope" value="<?php echo $formdata["scope"]; ?>"/>
							<span class="help-block">[Enter Scope for e.x store/product/*]</span>
						</div>
					</div>

					<div class="form-group row">
						<label for="category_id" class="col-2 col-form-label">Destination</label>
						<div class="col-10">
							<input type="text" id="destination" name="destination" value="<?php echo $formdata['destination'];?>" class="form-control"/>
							<span class="help-block">
								 [Enter Destination URL for e.x <?php echo $this->config->site_url();?>/admin/webhookcreate/producthook]
							</span>
						</div>
					</div>
				</div>
				<input type="hidden" name="hook_id" id="hook_id" value="<?php echo $formdata["hook_id"]; ?>"/>
				<div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-2">
							</div>
							<div class="col-10">
							<button type="submit" id = "submit" class="btn btn-success">
								Submit
							</button>
								<a href="<?php echo base_url(); ?>admin/managewebhook" class="btn btn-secondary">Cancel</a>
							</div>
						</div>
					</div>
				</div>
			</form>
			
		</div>
	</div>
</div>

