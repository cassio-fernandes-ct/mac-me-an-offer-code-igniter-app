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
						Add Webhook
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
            		<div class="kt-portlet__head-wrapper" >
						
					</div>		
				</div>
			</div>
			<div class="kt-portlet__body kt-portlet__body--fit">
				
			<form action="<?php echo $this->config->base_url();?>admin/managewebhook/add" class="form-horizontal form-bordered form-label-stripped" enctype="multipart/form-data" method="post" id="webhook" name="webhook">
				<div class="kt-portlet__body">
					<?php require_once APPPATH.'views/admin/common/display_errors.php' ?>
					<div id = "serial_number_exists" class="" ></div>
					
					<div class="form-group row">
						<label for="title" class="col-2 col-form-label"> Serial Title *</label>
						<div class="col-10">
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
							
						</div>
					</div>

					<div class="form-group row">
						<label for="category_id" class="col-2 col-form-label">Select Product *</label>
						<div class="col-10">
							<?php echo form_input($serise_title);  ?>
							<span class="help-block">
								[Enter Destination URL for e.x <?php echo $this->config->site_url();?>admin/webhookcreate/producthook]
							</span>
						</div>
					</div>
				</div>
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