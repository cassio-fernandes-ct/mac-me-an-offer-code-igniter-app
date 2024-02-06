<style>
    .kt-datatable__toggle-detail { display: none!important; }
    .kt-datatable__table { overflow-x: scroll!important; }
    .kt-datatable__table::-webkit-scrollbar { -webkit-appearance: none; }
    .kt-datatable__table::-webkit-scrollbar:horizontal { height: 11px; }
    .kt-datatable__table::-webkit-scrollbar-thumb {
        border-radius: 8px;
        border: 2px solid white; /* should match background, can't be transparent */
        background-color: rgba(0, 0, 0, .5);
    }
    .kt-datatable__table::-webkit-scrollbar-track {
        background-color: #fff;
        border-radius: 8px;
    }
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Quotes Management  </h3>
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
						Quotes List
					</h3>


				</div>
				<div class="kt-portlet__head-toolbar">

				</div>
			</div>
			<div class="kt-portlet__body">
				<?php $update = $this->session->userdata('updatedata');
if (isset($update) && !empty($update)) {?>
					<div class="alert alert-solid-success alert-bold" role="alert">
						<div class="alert-text">Serial updated successfully.</div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
					</div>
				<?php $this->session->unset_userdata('updatedata');}?>
				<?php $insered = $this->session->userdata('insertdata');
if (isset($insered) && !empty($insered)) {?>
					<div class="alert alert-solid-success alert-bold" role="alert">
						<div class="alert-text">Serial inserted successfully.</div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
					</div>
				<?php $this->session->unset_userdata('insertdata');}?>
				<?php $deleted = $this->session->userdata('deletedata');
if (isset($deleted) && !empty($deleted)) {?>
					<div class="alert alert-solid-danger alert-bold" role="alert">
						<div class="alert-text">Serial deleted successfully.</div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
					</div>
				<?php $this->session->unset_userdata('deletedata');}?>

				<div class="kt-form kt-form--label-right">
				<form id = "myform" method = "POST" action="https://app.macmeanoffer.com/admin/quote/export">

					<div class="row align-items-center">
						<div class="col-xl-12 order-2 order-xl-1">
							<div class="row align-items-center">
								<div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-input-icon kt-input-icon--left">
										<input type="text" class="form-control" name="search_text" placeholder="Search..." id="generalSearch">
										<span class="kt-input-icon__icon kt-input-icon__icon--left">
											<span><i class="la la-search"></i></span>
										</span>
									</div>
								</div>

								<div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label">
											<label>Status:</label>
										</div>
										<div class="kt-form__control">
											<div class="dropdown bootstrap-select form-control">
												<select name = "status" class="form-control bootstrap-select" id="kt_form_status" tabindex="-98">
														<option value="all">All</option>
														<!-- <option value="abandoned">Abandoned</option> -->
														<option value="completed">Completed</option>
														<option value="knockoutapproval">Pending Knockout Quotes</option>
												</select>
											</div>
										</div>
									</div>
								</div> 
							</div>
						</div>
					</div>


					<?php // WebFX | Rob | added 2022.09.06 ?>
					<div class="row align-items-center custome_margin">
						<div class="col-xl-12 order-2 order-xl-1">
							<div class="row align-items-center"> 
								<div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label">
											<label>Check No:</label>
										</div>
										<div class="kt-form__control">
											<input type="text" class="form-control" name="check_no" placeholder="Check No..." id="searchCheckNo" />
										</div>
									</div>
								</div>
								<div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label">
											<label>Payee:</label>
										</div>
										<div class="kt-form__control">
											<input type="text" class="form-control" name="payee" placeholder="Payee..." id="searchPayee">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="row align-items-center custome_margin">
						<div class="col-xl-12 order-2 order-xl-1">
							<div class="row align-items-center"> 
								<div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label">
											<label>Payment Method:</label>
										</div>
										<div class="kt-form__control">
											<!-- <div class="dropdown bootstrap-select form-control"> -->
												<select id="kt_form_paymnt_method1"  name="payment_method[]" class="form-control selectpicker kt_form_paymnt_method1"  multiple data-selected-text-format="count">
														<option value="all" selected>All</option>
														<option value="us_first_class_mail">Check – US First Class Mail</option>
														<option value="paypal">PayPal – seller pays applicable PayPal fees</option>
														<option value="macofalltrades_store_credit">macofalltrades.com Store Credit</option>
														<option value="zelle">Zelle</option>
													</select> 
											<!-- </div> -->
										</div>
									</div>
								</div>
								<div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label">
											<label>Payment Status:</label>
										</div>
										<div class="kt-form__control">
											<div class="dropdown bootstrap-select form-control">
												<select name="payment_status" class="form-control bootstrap-select" id="kt_form_payment_status" >
													<option value="all">All</option>
													<option value="0">Pend Pay</option>
													<option value="1">Paid</option> 
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row align-items-center custome_margin">
						<div class="col-xl-12 order-2 order-xl-1">
							<div class="row align-items-center">
								<div class="col-md-1 kt-margin-b-20-tablet-and-mobile"><b>Creation</b></div>
								<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label custome_width">
											<label>Start Date:</label>
										</div>
										<div class="kt-form__control">
										<input type="text" value = "" name = "startdate" class="form-control" placeholder="Select date" id="kt_datetimepicker_6">
										<span id = "errormessage"> </spn>
										</div>
									</div>
								</div>
								<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label custome_width">
											<label>End Date:</label>
										</div>
										<div class="kt-form__control">
										<input type="text" value = "" name = "enddate" class="form-control end_dt_evnt" data-datetype="creation" placeholder="Select date" id="kt_datetimepicker_6_1">

										</div>
									</div>
								</div>
								<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<a  id = "clear" class="btn btn-brand btn-icon-sm">
											Clear
										</a>
										&nbsp;

										<a target="blank" onclick="validation('normal')" class="btn btn-brand btn-icon-sm cust_export_bulk">
											<i class="flaticon2-plus"></i>
											Export Quotes
										</a>
										</div>
										<!-- <input class  = "btn btn-brand btn-icon-sm" type="submit" value="Export Quotes">
										<span class="kt-input-icon__icon kt-input-icon__icon--left">
											<span><i class="flaticon2-plus"></i></span>
										</span> -->

								</div>
							</div>
						</div>
					</div>

					<div class="row align-items-center custome_margin">
						<div class="col-xl-12 order-2 order-xl-1">
							<div class="row align-items-center">
								<div class="col-md-1 kt-margin-b-20-tablet-and-mobile"><b>Last Modified</b></div>
								<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label custome_width">
											<label>Start Date:</label>
										</div>
										<div class="kt-form__control">
										<input type="text" value = "" name = "modified_startdate" class="form-control" placeholder="Select date" id="modified_startdate">
										<span id = "m_errormessage"> </spn>
										</div>
									</div>
								</div>
								<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label custome_width">
											<label>End Date:</label>
										</div>
										<div class="kt-form__control">
											<input type="text" value = "" name = "modified_enddate" class="form-control end_dt_evnt" data-datetype="modified" placeholder="Select date" id="modified_enddate">
										</div>
									</div>
								</div> 
								<div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline"> 
										<input type="hidden" name="exporttype" id="exporttype">
										<a target="blank" onclick="validation('wellsfargo')" class="btn btn-brand btn-icon-sm cust_export_bulk">
											<i class="flaticon2-plus"></i>
											Export Wells-Fargo
										</a>
									</div> 
								</div> 
							</div>
						</div>
					</div>

					
					</form>
				</div>
			</div>

			<div class="kt-portlet__body kt-portlet__body--fit">
				<div class="quote_data_ajax" id="ajax_data"></div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo $this->config->base_url(); ?>assets/js/pages/crud/forms/widgets/bootstrap-datetimepicker.js?v=7.0.6"></script>

	<script src="<?php echo $this->config->base_url(); ?>assets/js/pages/crud/metronic-datatable/base/quote-data-ajax.js?v=20220718111730" type="text/javascript"></script>
	<script src="<?php echo $this->config->base_url(); ?>assets/js/pages/components/extended/sweetalert2.js" type="text/javascript"></script>
	<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";


	function export_quote(title)
	{
		window.location=base_url+'admin/quote/export_quote/'+title;
	}
	function update(categoryid,status)
	{
		window.location="<?php echo base_url(); ?>admin/category/update/"+categoryid+"/"+status;
	}

	function ask_confirmation_for_delete_serise(id,title)
	{

		swal.fire({
			title:"are you sure you want to delete the serial \""+title+"\" ?",
			text:"You won't be able to revert this!",
			type:"warning",
			showCancelButton:!0,
			confirmButtonText:"Yes, delete it!"
		}).then(function(e){
			e.value&&swal.fire("Deleted!","Your file has been deleted.","success");
			if(e.value)
			{
				delete_serise(title);
			}
		});

	}


	function update_quote(title)
	{
		window.location=base_url+'admin/quote/View/'+title;
	}




	function validation(type)
	{
		
		$('#exporttype').val(type);
		
		var startDate = new Date($('#kt_datetimepicker_6').val());
		var endDate = new Date($('#kt_datetimepicker_6_1').val());

		if(startDate != "Invalid Date" || endDate != "Invalid Date")
		{


			if (startDate < endDate){
				$("#errormessage").text('');
				$("#myform").submit();
			}else{
				$("#errormessage").text('Start date greater than the end date.');

			}
		}else{

			$("#errormessage").text('');
			$("#myform").submit();
			//$("#errormessage").text('Start date and end date both fields are mandatory.');
		}
	}

</script>

<style>
	.custome_width{
		width :31%;
	}

	.custome_margin{
		margin-top: 20px;
	}
	[data-field="status"] > span:first-child  {
		width: 164px !important;	
	}
	[data-field="payment_status"] > span:first-child  {
		width: 100px !important;	
	}
	[data-field="price"] > span:first-child  {
		width: 140px !important;	
	}
	[data-field="action"] > span:first-child  {
		width: 164px !important;	
	}
	/*.view_btn{
		margin-bottom: 2px; 
	}*/
	#m_errormessage {
    font-size: 10px;
    color: #ff0000;
    position: absolute;
    left: 0;
    min-width: 250px;
}
</style>

<script type="text/javascript"> 
	$( "#clear" ).click(function() {
		window.location.reload(true);		
	}); 

	function change_payment_status(quote_id, payment_status){ 
		swal.fire({
			title:"Are you sure you want to change the payment status?", 
			type:"warning",
			showCancelButton:!0,
			confirmButtonText:"Yes, Change it!"
		}).then(function(e){
			e.value&&swal.fire("Changed!","Payment status has been changed.","success");
			if(e.value)
			{ 
 				$.ajax({
			       url: base_url+'admin/quote/change_payment_status',
			       cache: false,
			       type: 'POST',
			       data: {'quote_id':quote_id,'payment_status':payment_status},
			       async: false,
			       success: function(data) {
			         var res = JSON.parse(data);
			         if(res.status && res.status == 1){ 
			         	if(payment_status == 1){ 
			         		$('.'+quote_id).text('Paid').addClass('kt-badge--success').removeClass('kt-badge--warning').attr("onclick","change_payment_status('"+quote_id+"','0')"); 
			         	}else if(payment_status == 0){
			         		$('.'+quote_id).text('Pend Pay').removeClass('kt-badge--success').addClass('kt-badge--warning').attr("onclick","change_payment_status('"+quote_id+"','1')"); 			         		
			         	}
	         			$('#kt_form_payment_status').trigger("change");
			         }else{
			         	alert('Payment status has not been changed. Please try again.')
			         }
			       }
			    });
			}
		});
	} 
</script>
