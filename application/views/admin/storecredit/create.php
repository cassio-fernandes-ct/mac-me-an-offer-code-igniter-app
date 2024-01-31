<?php

$storecredit = array(
        'type'  => 'text',
        'name'  => 'storecredit',
        'id'    => 'storecredit',
        'value' => number_format($getstorecreditdata['storecredit'],2),
        'class' => 'form-control',
        'onkeypress'=>"return isNumberKey(this, event);",
);


//empty($serise['data']['title'])?: $serise_title['readonly'] = 'readonly' ;

?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
				Store Credit Management   </h3>
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
						Update Store Credit 
					</h3>

					
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper" >
						<a href="<?php  echo $this->config->base_url(); ?>admin/storecredit" class="btn btn-brand btn-icon-sm">
							<i class="la la-long-arrow-left"></i>
							Back
						</a>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body kt-portlet__body--fit">
				<?php if( isset($storecredit_error['mmt']['error']) && !empty($storecredit_error['mmt']['error']) && isset($storecredit_error['mmt']['error']) && !empty($storecredit_error['mmt']['error']) )
						{ ?>
				<div class="alert alert-danger ">
					<h3>Oops!</h3>
					<ul><li> 
						<?php if(isset($storecredit_error['mmo']['error']) && !empty($storecredit_error['mmo']['error']))
						{
							echo 'Mac me an offer '.$storecredit_error['mmo']['error'];
						} ?>
						</li>
						<li>
						<?php if(isset($storecredit_error['mmt']['error']) && !empty($storecredit_error['mmt']['error']))
						{
							echo 'Mac Of All Trades '.$storecredit_error['mmt']['error'];
						} ?>
						</li>
					</ul>
				</div>
				<?php } ?>
				<?php echo form_open(); ?>
				<div class="kt-portlet__body">
					
					<div class="form-group row"> 
						<label for="title" class="col-2 col-form-label">Email</label>
						<div class="col-10">
							<label for="title" class="col-2 col-form-label"><?php echo $getstorecreditdata['email']?></label>
						</div>
					</div>
					<div class="form-group row"> 
						<label for="title" class="col-2 col-form-label">First Name</label>
						<div class="col-10">
							<label for="title" class="col-2 col-form-label"><?php echo $getstorecreditdata['firstname'].' '.$getstorecreditdata['lastname']?></label>
						</div>
					</div>
					
					<div class="form-group row"> 
						<label for="title" class="col-2 col-form-label">Mac Me An Offer ID</label>
						<div class="col-10">
							<label for="title" class="col-2 col-form-label"><?php echo $getstorecreditdata['bc_id_mmo']?></label>
						</div>
					</div>
					<div class="form-group row"> 
						<label for="title" class="col-2 col-form-label">Mac Of All Trades ID</label>
						<div class="col-10">
							<label for="title" class="col-2 col-form-label"><?php echo $getstorecreditdata['bc_id_mmt']?></label>
						</div>
					</div>
					<div class="form-group row">
						<label for="title" class="col-2 col-form-label">Store Credit</label>
							<div class="input-group-prepend col-10">
								<span class="input-group-text">$</span>
								
								<?php echo form_input($storecredit); ?>

							</div>
					</div>
				
				</div>
				<div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-2">
							</div>
							<div class="col-10">
							<button type="submit" class="btn btn-success">Save</button>
								<a href="<?php echo base_url(); ?>admin/storecredit" class="btn btn-secondary">Cancel</a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	
	function isNumberKey(txt, evt) {

var charCode = (evt.which) ? evt.which : evt.keyCode;

if(charCode == 46 ) {
    //Check if the text already contains the . character
    if (txt.value.indexOf('.') === -1 ) {
        return true;
    } else {
        return false;
    }
} else {
    if (charCode > 31
         && (charCode < 48 || charCode > 57))
        return false;
}

return true;

}

</script>

