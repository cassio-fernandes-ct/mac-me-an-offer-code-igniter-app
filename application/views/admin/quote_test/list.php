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
						Beta Quotes List
					</h3>


				</div>
				<div class="kt-portlet__head-toolbar">
					<a style="display: none;" class="btn btn-brand btn-icon-sm" onclick="printImg('https://app.macmeanoffer.com/application/uploads/ups/shipping/1601300603mmao.gif')">Print shipping label</a>
					<script type="text/javascript">
						function printImg(url) {
 
							 // var win = window.open('about:blank', "_new");
							 //    win.document.open();
							 //    win.document.write([
							 //        '<html>',
							 //        '   <head>',
							 //        '   </head>',
							 //        '   <body onload="window.print()" onafterprint="window.close()">',
							 //        '       <img src="' + url + '"/>',
							 //        '   </body>',
							 //        '</html>'
							 //    ].join(''));
							 //    win.document.close();
							 // ========================================
							var popup;
							function closePrint () {
							    if ( popup ) {
							        popup.close();
							    }
							}

							popup = window.open(url);
							popup.onbeforeunload = closePrint;
							popup.onafterprint = closePrint;
							popup.focus();
							popup.print();

							// var win = window.open('');
							// win.document.write('<img src="' + url + '" onload="window.print();window.close();" />');
							// win.focus();


						}
						function VoucherSourcetoPrint(source) {
							return "<html><head><script>function step1(){\n" +
									"setTimeout('step2()', 10);}\n" +
									"function step2(){window.print();window.close()}\n" +
									"</scri" + "pt></head><body onload='step1()'>\n" +
									"<img src='" + source + "' /></body></html>";
						}
						function printImg1(source) {
							Pagelink = "about:blank";
							var pwa = window.open(Pagelink, "_new");
							pwa.document.open();
							pwa.document.write(VoucherSourcetoPrint(source));
							pwa.document.close();
						}
					</script>
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
				<form id = "myform" method = "POST" action="https://app.macmeanoffer.com/admin/Test_Quote/export">

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
														<option value="abandoned">Abandoned</option>
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

					<div class="row align-items-center custome_margin">
						<div class="col-xl-12 order-2 order-xl-1">
							<div class="row align-items-center"> 
								<div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<div class="kt-form__label">
											<label>Payment Method:</label>
										</div>
										<div class="kt-form__control">
											<!-- <div class="dropdown1323 bootstrap-select1323 form-control"> -->
												
												<select id="kt_form_paymnt_method1"  name="payment_method[]" class="form-control selectpicker kt_form_paymnt_method1"  multiple data-selected-text-format="count" >
													<!-- data-actions-box="true" -->
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

<!-- <script src="<?php echo $this->config->base_url(); ?>assets/js/pages/crud/metronic-datatable/base/quote-data-ajax.js" type="text/javascript"></script> -->
<script src="<?php echo $this->config->base_url(); ?>assets/js/pages/components/extended/sweetalert2.js" type="text/javascript"></script>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";


	function export_quote(title)
	{
		window.location=base_url+'admin/Test_Quote/export_quote/'+title;
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
		// window.location=base_url+'admin/quote/View/'+title;
		window.location=base_url+'admin/Test_Quote/View/'+title;		
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
		
		// $('#kt_datetimepicker_6').val('');
		// $('#kt_datetimepicker_6_1').val('');
		// $('#generalSearch').val('');
		// $('#kt_form_status option:first').prop('selected',true);

		window.location.reload(true);	
	});

</script>

<script type="text/javascript">
	"use strict";
// Class definition

	var quote_data_ajax = function() {
		var demo = function() {
			var datatable = $('.quote_data_ajax').KTDatatable({
				// datasource definition
				data: {
					type: 'remote',
					source: {
						read: {
							url: base_url+'admin/Test_Quote/dataajax',
							// sample custom headers
							headers: {'x-my-custokt-header': 'some value', 'x-test-header': 'the value'},
							map: function(raw) {
								// sample data mapping
								var dataSet = raw;
								if(dataSet.meta.total==0){
									$('.cust_export_bulk').css('display','none');
								}else{
									$('.cust_export_bulk').css('display','inline-block');
								}
								if (typeof raw.data !== 'undefined') {
									dataSet = raw.data;
								}
								return dataSet;
							},
						},
					},
					pageSize: 10,
					serverPaging: true,
					serverFiltering: true,
					serverSorting: true,
				},

				// layout definition
				layout: {
					scroll: false,
					footer: false,
				},

				// column sorting
				sortable: false,

				pagination: true,

				search: {
					input: $('#generalSearch'),
				},
				// columns definition
				columns: [
					{
						field: 'id',
						title: '#',
						sortable: 'asc',
						width: 100,
						type: 'number',
						selector: false,
						textAlign: 'left',
					}, {
						field: 'productname',
						title: 'Product Name',
					}, {
						field: 'email',
						title: 'Email',
						
					},{
						field: 'price',
						title: 'Price',
					}, 
					{
						field: 'status',
						title: 'Status',
					},
					{
						field: 'payment_status',
						title: 'Payment Status',
					},
					{
						field: 'action',
						title: 'Action',	
					}],

			});

		    $('#kt_form_status').on('change', function() {
		      datatable.search($(this).val().toLowerCase(), 'Status');
		    });

		    $('#kt_form_type').on('change', function() {
		      datatable.search($(this).val().toLowerCase(), 'Type');
		    });

		  //   $('#kt_datetimepicker_6_1').on('change', function() {
		  //   	if($('#modified_enddate').val() != '' || $('#modified_startdate').val() != ''){
		  //   		$('#modified_enddate').val('');
		  //   		$('#modified_startdate').val('');
		  //   		$('#onclick_event').val('c');
		  //   	}	    	
				
				// var startdate = $('#kt_datetimepicker_6').val();
				// var enddate = $(this).val();
				// var date_input_obj = {};
				// if(startdate!=undefined&&startdate!=''&&enddate!=undefined&&enddate!=''){
				// 	if (startdate < enddate){
				// 		$("#errormessage").text('');
				// 		date_input_obj['onclick']=$('#onclick_event').val();
				// 		// date_input_obj['startDate']=startdate;
				// 		// date_input_obj['endDate']=enddate;
				// 		date_input_obj['c_startDate']=startdate;
				// 		date_input_obj['c_endDate']=enddate;
				// 		date_input_obj['m_startDate']=$('#modified_enddate').val();
				// 		date_input_obj['m_endDate']=$('#modified_startdate').val();
				// 		datatable.search(date_input_obj, 'date_obj');
				// 	}else{
				// 		$("#errormessage").text('Start date greater than the end date.');
				// 		$(this).val('');
				// 	}
				// }else{
				// 	$("#errormessage").text('Please select Start date.');
				// 	$(this).val('');
				// }
		  //   });

		    $('.kt_form_paymnt_method1').on('change', function() {
				var paymnt_mthd_txt = $(this).val(); 
				
				/*
				var thisObj = $(this);
				var selectedOptions = (thisObj.val())?thisObj.val():[];
				var selectedOptionsLength = selectedOptions.length;
								
				if($.inArray('all', paymnt_mthd_txt) != -1 && selectedOptionsLength > 0){  
					thisObj.find('option').prop('selected', false).parent().selectpicker('refresh');	
					thisObj.find('option[value="all"]').prop('selected', true).parent().selectpicker('refresh');	
				} else{
					// thisObj.find('option[value="all"]').prop('selected',false).parent().selectpicker('refresh');	 
				}
				*/

		      	datatable.search($(this).val(), 'payment_method');
		    });

		    $('#kt_form_payment_status').on('change', function() { 
		      datatable.search($(this).val(), 'payment_status');
		    });
 
 
			$('.end_dt_evnt').on('change', function() { 

		    	var startdate = $(this).parent().parent().parent().siblings().find('input').val();
		    	var enddate = $(this).val();
		    	var selected_type = $(this).data('datetype'); 

		    	if(selected_type == 'modified'){
		    		$('#kt_datetimepicker_6').val('');
		    		$('#kt_datetimepicker_6_1').val('');
		    		var error_msg_class = 'm_errormessage';
		    	}else{
		    		$('#modified_enddate').val('');
		    		$('#modified_startdate').val('');
		    		var error_msg_class = 'errormessage';
		    	}

				var date_input_obj = {};
				if(startdate!=undefined&&startdate!=''&&enddate!=undefined&&enddate!=''){
					if (startdate < enddate){
						$("#"+error_msg_class).text('');
						date_input_obj['selected_type']=selected_type;
						date_input_obj['startdate']=startdate;
						date_input_obj['enddate']=enddate;
						datatable.search(date_input_obj, 'date_obj');
					}else{
						$("#"+error_msg_class).text('Start date greater than the end date.');
						$(this).val('');
					}				
				}else{
					$("#"+error_msg_class).text('Please select start date.');
					$(this).val('');
				}
		    }); 

		  //   $('#modified_enddate').on('change', function() {
		  //   	if($('#kt_datetimepicker_6').val() != '' || $('#kt_datetimepicker_6_1').val() != ''){
		  //   		$('#kt_datetimepicker_6').val('');
		  //   		$('#kt_datetimepicker_6_1').val('');	   	
		  //   		$('#onclick_event').val('m');	
		  //   	}
		    	
				// var m_startdate = $('#modified_startdate').val();
				// var m_enddate = $(this).val();
				// var date_input_obj = {};
				// if(m_startdate!=undefined&&m_startdate!=''&&m_enddate!=undefined&&m_enddate!=''){
				// 	if (m_startdate < m_enddate){
				// 		$("#m_errormessage").text('');
				// 		date_input_obj['onclick']=$('#onclick_event').val();
				// 		date_input_obj['m_startDate']=m_startdate;
				// 		date_input_obj['m_endDate']=m_enddate;
				// 		date_input_obj['c_startDate']=$('#kt_datetimepicker_6').val();
				// 		date_input_obj['c_endDate']=$('#kt_datetimepicker_6_1').val();
				// 		datatable.search(date_input_obj, 'modified_date_obj');
				// 	}else{
				// 		$("#m_errormessage").text('Start date greater than the end date.');
				// 		$(this).val('');
				// 	}				
				// }else{
				// 	$("#m_errormessage").text('Please select start date.');
				// 	$(this).val('');
				// }
		  //   }); 

		    $('#kt_form_status,#kt_form_type,#kt_form_paymnt_method,#kt_form_payment_status').selectpicker();

		};		

		return {
			// public functions
			init: function() {
				demo();
			},
		};

	}();

jQuery(document).ready(function() {
	quote_data_ajax.init();
});
</script>

<script type="text/javascript"> 

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
			       url: base_url+'admin/Test_Quote/change_payment_status',
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