"use strict";
// Class definition

var Serial_KTDatatableRemote = function() {
	// Private functions

	// basic demo
	var demo = function() {
		 
		 
		var datatable = $('.serise_data_ajax').KTDatatable({
			// datasource definition
			data: {
				type: 'remote',
				source: {
					read: {
						url: base_url+'admin/serial/dataajax',
						// sample custom headers
						headers: {'x-my-custokt-header': 'some value', 'x-test-header': 'the value'},
						map: function(raw) {
							// sample data mapping
							var dataSet = raw;
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
					width: 40,
					type: 'number',
					selector: false,
					textAlign: 'center',
				}, {
					field: 'serial',
					title: 'Serial Title',
				},
				 	{
					field: 'action',
					title: 'Action',
				},
				],
		});
 
    $('#kt_form_type').on('change', function() {
      datatable.search($(this).val().toLowerCase(), 'Type');
    });

    $('#kt_form_status,#kt_form_type').selectpicker();

	};

	return {
		// public functions
		init: function() {
			demo();
		},
	};
}();

jQuery(document).ready(function() {
	Serial_KTDatatableRemote.init();
});


