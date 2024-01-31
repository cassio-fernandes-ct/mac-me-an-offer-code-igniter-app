"use strict";
// Class definition

var import_file_log_ajax = function() {
	// Private functions

	// basic demo
	var demo = function() {

		var datatable = $('.import_file_log').KTDatatable({
			// datasource definition
			data: {
				type: 'remote',
				source: {
					read: {
						url: base_url+'admin/import/dataajax',
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
					width: 30,
					type: 'number',
					selector: false,
					textAlign: 'center',
				}, {
					field: 'file_name',
					title: 'File Name',
				},
				{
					field: 'date',
					title: 'Date',
				}, 
				{
					field: 'action',
					title: 'Action',
				},],

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
	import_file_log_ajax.init();
});