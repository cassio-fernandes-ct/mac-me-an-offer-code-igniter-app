<div class="kt-portlet">
	
	<div class="kt-portlet__body">
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet box green"">
					<div class="portlet-title">
						<div class="caption">
							<span> Total Categories : <?php echo $total_category ?> </span>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="10%">
										CategoryID
									</th>
									<th width="10%">
										Image
									</th>
									<th width="40%">
										Title
									</th>
									<th width="30%">
										Status
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if(isset($category_tree_data) && !empty($category_tree_data) && count($category_tree_data)>0)
									{
										$no=1;
										foreach($category_tree_data as $category_tree_data_s)
											{
								?>

									<tr>
										<td> <?php echo $category_tree_data_s['category_id'];  ?> </td>
										<td>image</td>
										<td><?php echo $category_tree_data_s['name'];  ?> </td>
										<td ><?php echo $category_tree_data_s['status'];  ?> </td>

									</tr>


									<?php
								}
								}else{ ?>
								<tr>
									<td  colspan="3" class="numeric respose_tag">Please try again</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>
<script language="javascript">
function pauseRquest()
{
	if(jQuery('#request_action').val()=='start'){
					jQuery('#request_action').val('stop');
		jQuery('#start_stop_action').removeClass('glyphicon-pause');
												jQuery('#start_stop_action').addClass('glyphicon-play');
	}else{
		sendRquest();
		jQuery('#request_action').val('start');
		jQuery('#start_stop_action').removeClass('glyphicon-play');
												jQuery('#start_stop_action').addClass('glyphicon-pause');
	}
	return false;
}
function sendRquest()
{
	jQuery('#start_stop_controller').show();
	jQuery('#start_stop_action').removeClass('glyphicon-play');
											jQuery('#start_stop_action').addClass('glyphicon-pause');
	var code=jQuery('.start_process').attr('data-code');
	if(code){
		jQuery('.processing').removeClass('processing');
		jQuery('.start_process').find('.respose_tag').html('Please wait...');
		jQuery('.start_process').addClass('processing');
		$.ajax({
url: '<?php echo $this->config->site_url();?>/admin/category/ImportCategory',
data: {
code: code,
send:'yes'
},
error: function() {
var obj=jQuery('.start_process');
obj.addClass('error');
obj.next().addClass('start_process');
obj.removeClass('start_process');
sendRquest();
},
success: function(data) {
$('#total_imported_product').html( eval($('#total_imported_product').html())+1);
var obj=jQuery('.start_process');
obj.find('.respose_tag').html(data);
obj.next().addClass('start_process');
obj.removeClass('start_process');
obj.addClass('completed');
if(jQuery('#request_action').val()=='start'){
sendRquest();
}
},
type: 'GET'
});
}
return false;
}
</script>