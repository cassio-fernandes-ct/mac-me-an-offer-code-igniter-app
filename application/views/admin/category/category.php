<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
			Category
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">
		<!--begin::Section-->
		<div class="kt-section">
			<span class="kt-section__info">
				Display All Categories
				<div class="row">
					<div class="col-sm-12 col-md-6">
						<div class="dataTables_length" id="kt_table_1_length">
							<label>Show
								<select name="kt_table_1_length" aria-controls="kt_table_1" class="custom-select custom-select-sm form-control form-control-sm" id="show"  onchange="searchFilter()" >
									<option value="10">10</option>
									<option value="25">25</option>
									<option value="50">50</option>
									<option value="100">100</option>
								</select>
							</label>
						</div>
					</div>
					<div class="col-sm-12 col-md-6">
						<div id="kt_table_1_filter" class="dataTables_filter">
							<label>Search:
								<input type="search" id="keywords" class="form-control form-control-sm" placeholder="Search Title" aria-controls="kt_table_1" onkeyup="searchFilter()">
							</label>
						</div>
					</div>
				</div>
			</span>
			
			<div class="kt-section__content" id="categoryList">
				<?php require_once 'category-pagination-data.php'; ?>
			</div>
		</div>
		<!--end::Section-->
	</div>
	<!--end::Form-->
</div>

<script>
function searchFilter(page_num) {
	page_num = page_num?page_num:0;
	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();
	var showRecordPerPage = $('#show').val();
													if(keywords == 	null)
		keywords='';
	if(sortBy == null)
		sortBy = '';
	if(showRecordPerPage == null)
		showRecordPerPage = '';
	$.ajax({
		type: 'POST',
url: '<?php echo base_url(); ?>category/ajaxPaginationData/'+page_num,
data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy+'&show='+showRecordPerPage,
beforeSend: function () {
$('.overlay-loading-content').css('display', 'block');
},
success: function (html) {
$('#categoryList').html(html);
$('.overlay-loading-content').css('display', 'none');
$('html, body').animate({scrollTop: $("#kt_content").offset().top}, 'slow');
}
});
}
</script>