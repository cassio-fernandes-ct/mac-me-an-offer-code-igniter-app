<?php

$serise_title_value = empty($serial['serial'])?set_value('serial'):$serial['serial'];
$product_ids        = empty($serial['product_ids'])?set_value('product_ids'):$serial['product_ids'];
$id                 = empty($serial['id'])?set_value('id'):$serial['id'];
$serise_id_value    = empty($serial['serial_id'])?'': $serial['serial_id'];

$serise_title = array(
	'type'  => 'text',
	'id'  => 'serial',
	'name'  => 'serial',
	'value'  =>   $serise_title_value,
	'class' => 'form-control',
	'data-original-title'=>'Tooltip title',
);

$product_attr = array(
'id'       => 'products',
'class' => 'form-control kt-select2 is-valid',
'multiple' => "multiple",
);

$product_options =[];


?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
				Serial Management   </h3>
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
					 <?php if($serise_id_value): ?> 
					 	Update Serial
					 	<?php else: ?>
					 		Add Serial
					 	<?php endif; ?>
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper" >
						<a href="<?php  echo $this->config->base_url(); ?>admin/serial" class="btn btn-brand btn-icon-sm">
							<i class="la la-long-arrow-left"></i>
							Back
						</a>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body kt-portlet__body--fit">
				
				<?php echo form_open(); ?>
				<div class="kt-portlet__body">
					<?php require_once APPPATH.'views/admin/common/display_errors.php' ?>
					<div id = "serial_number_exists" class="" ></div>
					
					<div class="form-group row">
						<label for="title" class="col-2 col-form-label"> Serial Title <span style="color: red;">*</span></label>
						<div class="col-10">
							<?php echo form_input($serise_title);  ?>
							
						</div>
					</div>

					<div class="form-group row">
						<label for="category_id" class="col-2 col-form-label">Select Product <span style="color: red;">*</span></label>
						<div class="col-10">
							<?php echo form_dropdown('product_ids[]',$products,[],$product_attr);  ?>
						</div>
					</div>
				</div>
				<div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-2">
							</div>
							<div class="col-10">
							<button type="submit" id = "submit" class="btn btn-success">
								<?php if($serise_id_value): ?> 
							 		Save
							 	<?php else: ?>
							 		Add Serial
							 	<?php endif; ?>
							</button>
								<a href="<?php echo base_url(); ?>admin/serial" class="btn btn-secondary">Cancel</a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">

	$('input').on('keypress', function(e) {
        if (e.which == 32)
            return false;
    });

	var base_url = "<?php echo base_url(); ?>";	
	
	
	$(document).ready(function() {

		set_product_selected_value();
		//$("div").removeClass("serial_number_exists");
		 $('#products').select2({
           // placeholder: "Select a state"
        });

	});

		
	$("#submit").click(function() {
	    if ($(".serial_number_exists").length > 0) {
	        return false;
	    }
	});

	function set_product_selected_value()
	{
		var selected_category = <?php echo json_encode($product_ids); ?>;
		console.log(selected_category);

		var i;
		for (i = 0; i < selected_category.length; i++) {
			$("#products option[value='"+selected_category[i]+"']").attr('selected', 'selected');
		}
	}

	var typingTimer; 
	var doneTypingInterval = 1000; 
	var input_n = $('#serial');
	input_n.on('keyup', function () { 
		$("div").addClass("serial_number_exists");
		//alert('hello');
	 clearTimeout(typingTimer);
	 var search_val 	   = $(this).val();
	  typingTimer = setTimeout(doneTyping, doneTypingInterval);
	});
	input_n.on('keydown', function () {
	  clearTimeout(typingTimer);
	});

	function doneTyping(){
		var serial = '';
		var serial = $("#serial").val();

		if(serial != '')
		{
			var id  = <?php echo json_encode($id ); ?>;
			if(id == '' )
			{
				var data = 'search='+serial;
			}else{
				var data = 'search='+serial+'&id='+id;
			}
			
			$.ajax({
				url: base_url+'admin/serial/existserial',
				type: 'post',
				data: data,
				success: function( response ){

					console.log(response);
					if(response != 0){

						$("#serial_number_exists").html('<div class="alert alert-danger serial_number_exists"  role="alert"><div class="alert-text">serial already exist!</div></div>');
						$("div").addClass("serial_number_exists");

					}else{
						
						$("#serial_number_exists").html('');
						$("div").removeClass("serial_number_exists");
						//$("#ajax_view_load").val('');
					}
				},  
			});
		}else{
			$("#serial_number_exists").html('<div class="alert alert-danger serial_number_exists"  role="alert"><div class="alert-text">Please add serial number.</div></div>');
			$("#ajax_view_load").html('');

		}
	}
	
</script>


