<?php 
$que_import = 200;
?>

<div class="row"> 
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat green">
				<div class="visual">
					<i class="glyphicon glyphicon-import"></i>
				</div>
				<div class="details">
					<div class="number">
						 <?php echo $total_import_data; ?>
					</div>
					<div class="desc">
						 Start Import
					</div>
				</div>
				<a class="more" href="#" onclick="return sendRquest()">
					 Start Import <i class="m-icon-swapright m-icon-white"></i>
				</a>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="display:none" id="start_stop_controller">
			<div class="dashboard-stat green">
				<div class="visual">
					<i class="glyphicon glyphicon-play" id="start_stop_action"></i>
				</div>
				<div class="details">
					<div class="number">
						 <?php echo $total_import_data ?>
					</div>
					<div class="desc">
						 Pause
					</div>
				</div>
				<a class="more" href="#" onclick="return pauseRquest()">
					 Pause <i class="m-icon-swapright m-icon-white"></i>
				</a>
			</div>
		</div>
		<input type="hidden" name="request_action" id="request_action" value="start"  />
</div>
<div class="portlet box green">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Import Data List 
			<span><?php echo $total_import_data ?> / </span>
			<span id="total_imported_product">0</span>
		</div>
	</div>
	<div class="portlet-body flip-scroll">
		<table class="table table-bordered table-striped table-condensed flip-content" id="ajax_data" width="100%">
			<thead class="flip-content">
				<tr>
					<th width="5%">
						SKU
					</th>
					<th width="12%">
						Make
					</th>
					<th width="12%">
						Year
					</th>
					<th width="12%">
						Model
					</th>
					<th width="12%">
						Size
					</th>
					<th width="12%">
						Location
					</th>
					<th width="18%">
						Status
					</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if(isset($importdata) && !empty($importdata) && count($importdata) > 0)
					{
						$no=1;
						foreach($importdata as $d){ ?>
							<tr <?php if($no <= $que_import) { echo 'class="start_process"'; } ?> data-code="<?php echo $no; ?>"  >
								<td><?php echo $d['product_sku'];?></td>
								<td><?php echo($d['make'])?></td>
								<td><?php echo($d['year'])?></td>
								<td><?php echo($d['model'])?></td>
								<td><?php echo($d['size'])?></td>
								<td><?php echo($d['location'])?></td>
								<!--<td><?php echo(trim($d['note']))?></td>-->
								<td class="numeric respose_tag">Pending</td>
							</tr>
						<?php $no++; }
					}else{ ?>
						<tr>
							<td  colspan="6" class="numeric respose_tag">Please try again</td>
						</tr>
				<?php } ?>
			</tbody>
		</table>
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
	
function sendRquest(){
	
	jQuery('.progress').show();
	jQuery('#start_stop_controller').show();
	jQuery('#start_stop_action').removeClass('glyphicon-play');
	jQuery('#start_stop_action').addClass('glyphicon-pause');										
	var data_code_array=new Array();
	var arrayIndex=0;
	jQuery('.start_process').each(function(){
		data_code_array[arrayIndex]=jQuery(this).attr('data-code');
		arrayIndex++;
	});
	
	if(data_code_array.length > 0){
		jQuery('.processing').removeClass('processing');
		jQuery('.start_process').find('.respose_tag').html('Please wait...');
		jQuery('.start_process').addClass('processing');
		$.ajax({
		url: '<?php echo $this->config->site_url();?>/admin/import/importsingledata',
		data: {
			code: data_code_array,
			send:'yes'
		},
		dataType: 'json',
		error: function() {
			
			var obj=jQuery('.start_process');
			obj.addClass('error');
			obj.next().addClass('start_process');
			obj.removeClass('start_process');
			sendRquest();
		},
		success: function(data) {
			
			console.log(data);
			$.each(data, function (index, value) {
				var obj = jQuery('tr[data-code="'+value['code']+'"]');
				obj.find('.respose_tag').html(value['response']);
				obj.removeClass('start_process');
				obj.removeClass('processing');
				obj.addClass('completed');
				$('#total_imported_product').html( eval($('#total_imported_product').html())+1);
			});
			
			$('.completed').last().next().addClass('start_process');
			for(var i=1;i<'<?php echo $que_import?>';i++){
				$('.start_process').last().next().addClass('start_process');
			}
			var totproduct='<?php echo $total_import_data ?>';
			completed = eval($('#total_imported_product').html())*100/eval(totproduct);
			jQuery('.progress-bar-success').css('width',completed+'%');
			jQuery('.completed_rationg').html(completed+' % Completed');
			if(completed >= 100){
				jQuery('.processing').removeClass('processing');
				jQuery('.welcomemessage').show();	
			}
			
			if(jQuery('#request_action').val()=='start'){
				if(jQuery('#ajax_data tr').hasClass('start_process')){
						sendRquest();
				}
			}
		},
		type: 'GET'
		});
	}
	return false;
}
		
/*function sendRquest()
{
	jQuery('#start_stop_controller').show();
	jQuery('#start_stop_action').removeClass('glyphicon-play');
	jQuery('#start_stop_action').addClass('glyphicon-pause');										
	
	var code     = jQuery('.start_process').attr('data-code');
	
	if(code){
		jQuery('.processing').removeClass('processing');
		jQuery('.start_process').find('.respose_tag').html('Please wait...');
		jQuery('.start_process').addClass('processing');
		$.ajax({
			url: '<?php echo $this->config->site_url();?>/admin/import/importsingledata',
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
			type: 'POST'
		});
	}
	return false;
}*/
</script>