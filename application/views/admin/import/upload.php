<?php 
$que_import = 200;
?>
<style type="text/css">
	.total_box{
		max-width: 300px;

	}
	.padding_left_div{
		padding-left: 28px;
	}
</style>

<div class="kt-portlet__body">
	
	<div  class="kt-portlet__head-label">
    	<button onclick="sendRquest()" type="button" class="btn btn-primary btn-lg"><i class="fab fa-google-play"></i> Start</button>
    	<button style = "display: none;" id = "start_stop_action" onclick="pauseRquest()" type="button" class="btn btn-success btn-lg">
    		<i class="fa fa-pause"></i>Pause</button>
	</div>
	<div  style = "padding-top: 25px;" class="kt-portlet__head-label" >
		<div class="progress">
			<div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 0%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
	</div>
</div>
<div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
						Serial Number File Import 
					</h3>
                </div>
                <div class="kt-portlet__head-toolbar">
            				
				</div>
            </div>
	<div style = "display: none"class="kt-portlet__head ">
		<div class="kt-portlet__head-label padding_left_div">
			<span> Import Data List <?php echo $total_import_data ?> / </span><div id = "total_imported_product" class="kt-portlet__head-title">0</div>
		</div>
	</div>
	<div class="kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--loaded" id="local_data" style="">
	    <table class="kt-datatable__table" id = "ajax_data" style="display: block;">
	        <thead class="kt-datatable__head">
	            <tr class="kt-datatable__row" style="left: 0px;">
	               	 <th class="kt-datatable__cell kt-datatable__cell--sort"><span style="width: 50px;">#</span></th>
	                <th class="kt-datatable__cell kt-datatable__cell--sort"><span style="width: 60px;">Serial</span></th>
	                <th  class="kt-datatable__cell kt-datatable__cell--sort"><span style="width: 400px;">Product Name</span></th>
	               
	                <th data-field="Actions" data-autohide-disabled="false" class="kt-datatable__cell kt-datatable__cell--sort"><span style="width: 300px;">Status</span></th>
	            </tr>
	        </thead>
	        <tbody class="kt-datatable__body" style="">
	           
	           <?php if(isset($importdata) && !empty($importdata))
	           	{ $no=1;
	           		foreach($importdata as $value){?>
	            <tr data-row="9" data-code="<?php echo $no; ?>" class="<?php if($no <= $que_import) { echo 'start_process'; } ?> kt-datatable__row kt-datatable__row--even" style="left: 0px;">
	               	 <td data-field="OrderID" class="kt-datatable__cell "><span style="width: 50px;"><?php echo $no; ?></span></td>
	                <td data-field="OrderID" class="kt-datatable__cell "><span style="width: 60px;"><?php echo $value['serial'] ?></span></td>
	                <td data-field="Country" class="kt-datatable__cell"><span style="width: 400px;"><?php echo $value['product'] ?></span></td>
	                <td data-field="ShipDate" class="kt-datatable__cell"><span style="width: 300px;" class="numeric respose_tag" ></span></td>
	                
	            </tr>
	            <?php $no++; } }  ?>
	        </tbody>
	    </table>
	    <input type="hidden" name="request_action" id="request_action" value="start"  />
	</div>



<script>

	
jQuery('.welcomemessage').hide();
function pauseRquest()
{
	if(jQuery('#request_action').val()=='start'){
		jQuery('#request_action').val('stop');			
		//jQuery('#start_stop_action').removeClass('glyphicon-pause');
		jQuery('#start_stop_action').html('<i class="fa fa-play"></i>Play');
		console.log('stop');

	}else{
		sendRquest();
		jQuery('#request_action').val('start');
		//jQuery('#start_stop_action').removeClass('glyphicon-play');
		jQuery('#start_stop_action').html('<i class="fa fa-pause"></i>Pause');
		console.log('stop');										
	}
	return false;
}
	
function sendRquest(){
	
	jQuery('#start_stop_action').show();
	jQuery('#start_stop_controller').show();
	jQuery('#start_stop_action').html('<i class="fa fa-pause"></i>Pause');
	//jQuery('#start_stop_action').addClass('glyphicon-pause');										
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
			console.log('errro');
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
			for(var i=1;i<'<?php echo @$que_import?>';i++){
				$('.start_process').last().next().addClass('start_process');
			}
			var totproduct='<?php echo @$total_import_data ?>';
			completed = eval($('#total_imported_product').html())*100/eval(totproduct);
			jQuery('.progress-bar-striped').css('width',completed+'%');
			jQuery('.completed_rationg').html(completed+' % Completed');
			if(completed >= 100){
				setTimeout(function(){
					jQuery('.processing').removeClass('processing');
					jQuery('.welcomemessage').show();
				}, 1000);	
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