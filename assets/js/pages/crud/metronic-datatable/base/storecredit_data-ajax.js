"use strict";
// Class definition

var Storecredit_KTDatatableRemote = function() {
	// Private functions

	// basic demo
	var demo = function() {
		 
		 
		var datatable = $('.kt-datatable').KTDatatable({
			// datasource definition
			data: {
				type: 'remote',
				source: {
					read: {
						url: base_url+'admin/storecredit/dataajax',
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
					field: 'category_id',
					title: '#',
					sortable: 'asc',
					width: 80,
					type: 'number',
					selector: false,
					textAlign: 'center',
				}, {
					field: 'name',
					title: 'Name',
					width: 150,
					
				},{
					field: 'email',
					title: 'Email',
					width: 250,
				
				}, {
					field: 'storecredit',
					title: 'Store Credit',
					width: 80,
				
				},
				{
					field: 'action',
					title: 'Action',
					width: 150,
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
	Storecredit_KTDatatableRemote.init();
});


