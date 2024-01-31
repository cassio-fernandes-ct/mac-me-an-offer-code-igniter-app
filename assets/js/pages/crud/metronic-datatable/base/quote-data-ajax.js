"use strict";
// Class definition

var quote_data_ajax = function () {

	// Private functions
	// basic demo
	var demo = function () {

		var datatable = $('.quote_data_ajax').KTDatatable({
			// datasource definition
			data: {
				type: 'remote',
				source: {
					read: {
						url: base_url + 'admin/quote/dataajax',
						// sample custom headers
						headers: { 'x-my-custokt-header': 'some value', 'x-test-header': 'the value' },
						map: function (raw) {
							// sample data mapping
							var dataSet = raw;
							if (dataSet.meta.total == 0) {
								$('.cust_export_bulk').css('display', 'none');
							} else {
								$('.cust_export_bulk').css('display', 'inline-block');
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


			rows: {
				afterTemplate( $row, data ) {
					$row.attr( 'data-created-date', data.created_datetime )
				}
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
					autoHide: false,
				},
				// {
				// 	field: 'created_datetime',
				// 	title: 'Quote Date',
				// 	autoHide: false,
				// },
				{
					field: 'productname',
					title: 'Product Name',
					autoHide: false,
				}, {
					field: 'email',
					title: 'Email',
					autoHide: false,

				}, {
					field: 'price',
					title: 'Price',
					autoHide: false,
				},
				{
					field: 'status',
					title: 'Status',
					autoHide: false,
				},
				{
					field: 'payment_status',
					title: 'Payment Status',
					autoHide: false,
				},
				{
					field: 'action',
					title: 'Action',
					autoHide: false,
				}],

		});

		$('#kt_form_status').on('change', function () {
			datatable.search($(this).val().toLowerCase(), 'Status');
		});

		$('#kt_form_type').on('change', function () {
			datatable.search($(this).val().toLowerCase(), 'Type');
		});

		if ($('#searchCheckNo').length) {
			$('#searchCheckNo').on('keyup', function () {
				datatable.search($(this).val().toLowerCase(), 'checkno')
			})
		}

		if ($($('#searchPayee').length)) {
			$('#searchPayee').on('keyup', function () {
				datatable.search($(this).val().toLowerCase(), 'searchpayee')
			})
		}

		//   $('#kt_datetimepicker_6_1').on('change', function() {
		// var startdate = $('#kt_datetimepicker_6').val();
		// var enddate = $(this).val();
		// var date_input_obj = {};
		// if(startdate!=undefined&&startdate!=''&&enddate!=undefined&&enddate!=''){
		// 	if (startdate < enddate){
		// 		$("#errormessage").text('');
		// 		date_input_obj['startDate']=startdate;
		// 		date_input_obj['endDate']=enddate;
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

		$('#kt_form_paymnt_method1').on('change', function () {
			datatable.search($(this).val(), 'payment_method');
		});

		$('#kt_form_payment_status').on('change', function () {
			datatable.search($(this).val(), 'payment_status');
		});

		$('.end_dt_evnt').on('change', function () {

			var startdate = $(this).parent().parent().parent().siblings().find('input').val();
			var enddate = $(this).val();
			var selected_type = $(this).data('datetype');

			if (selected_type == 'modified') {
				$('#kt_datetimepicker_6').val('');
				$('#kt_datetimepicker_6_1').val('');
				var error_msg_class = 'm_errormessage';
			} else {
				$('#modified_enddate').val('');
				$('#modified_startdate').val('');
				var error_msg_class = 'errormessage';
			}

			var date_input_obj = {};
			if (startdate != undefined && startdate != '' && enddate != undefined && enddate != '') {
				if (startdate < enddate) {
					$("#" + error_msg_class).text('');
					date_input_obj['selected_type'] = selected_type;
					date_input_obj['startdate'] = startdate;
					date_input_obj['enddate'] = enddate;
					datatable.search(date_input_obj, 'date_obj');
				} else {
					$("#" + error_msg_class).text('Start date greater than the end date.');
					$(this).val('');
				}
			} else {
				$("#" + error_msg_class).text('Please select start date.');
				$(this).val('');
			}
		});

		$('#kt_form_status,#kt_form_type,#kt_form_paymnt_method,#kt_form_payment_status').selectpicker();

	};

	return {
		// public functions
		init: function () {
			demo();
		},
	};
}();

jQuery(document).ready(function () {
	quote_data_ajax.init();
});
