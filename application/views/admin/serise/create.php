<?php

$serise_title_value = empty($serise['data']['title'])?set_value('title'):$serise['data']['title'];
$category_id_value = empty($serise['data']['category_id'])?set_value('category_id'):$serise['data']['category_id'];
$serise_id_value = empty($serise['data']['id'])?'': $serise['data']['id'];

$serise_title = array(
	'type'  => 'text',
	'id'  => 'title',
	'value'  =>   $serise_title_value,
	'class' => 'form-control',
);
//empty($serise['data']['title'])?: $serise_title['readonly'] = 'readonly' ;


$category_attr = array(
'id'       => 'category_id',
'class' => 'form-control kt-select2 is-valid',
'onChange' => 'load_category_in_multipleselect(this.value);',
);

$product_options =[];
$product_attr = array(
'id'       => 'product_ids',
'class' => 'form-control kt-select2 is-valid',
'multiple' => "multiple",
);

$product_att = array(
'id'       => 'product_ids',
'class' => 'form-control kt-select2 is-valid',
'multiple' => "multiple",
);

$category_att = array(
'id'       => 'category_ids',
'class' => 'form-control kt-select2 is-valid',
'onChange' => 'load_products_in_multipleselect(this.value);',
);

?>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
				Series Management   </h3>
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
					 	Update Series
					 	<?php else: ?>
					 		Add Series
					 	<?php endif; ?>
					</h3>

					
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper" >
						<a href="<?php  echo $this->config->base_url(); ?>admin/series" class="btn btn-brand btn-icon-sm">
							<i class="la la-long-arrow-left"></i>
							Back
						</a>
					</div>
				</div>
			</div>
			<div class="kt-portlet__body kt-portlet__body--fit">
				
				<div class="kt-portlet__body">
					<?php require_once APPPATH.'views/admin/common/display_errors.php' ?>
					<?php echo form_open(); ?>
					 <?php if($serise_id_value): ?> 
					 	    <input type="hidden"  name="updateform" id = "formtype" value="updateform">
					 	<?php else: ?>
					 		<input type="hidden"  name="insertform" id = "formtype" value="insertform">
					 	<?php endif; ?>
					<div class="form-group row">
						<label for="category_id" class="col-2 col-form-label">Select Category <span style="color: red;">*</span></label>
						<div class="col-10">
							<?php echo form_dropdown('category_id', $categories,0,$category_attr);  ?>
						</div>
					</div>

					<div class="form-group row">
						<label for="title" class="col-2 col-form-label">Series Title <span style="color: red;">*</span></label>
						<div class="col-10">
							<?php //echo form_input($serise_title);  ?>
							<?php //echo form_dropdown('title',@$serise['subcategory'],[],$category_att);  ?>
							<?php echo form_dropdown('title',@$serise['subcategory'],[],$category_att);  ?>
							<!--<select class="form-control kt-select2 is-valid" id="kt_select2_1_validate" name="param">
												</select>-->
						</div>
						
					</div>
					
					<div class="form-group row">
						<label for="category_id" class="col-2 col-form-label">Select Product <span style="color: red;">*</span></label>
						<div class="col-10">
							<?php// echo form_dropdown('product_ids[]',$products,[],$product_attr);  ?>

							<?php echo form_dropdown('product_ids[]',@$serise['products'],[],$product_att);  ?>
						</div>
					</div>

				</div>
				<div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-2">
							</div>
							<div class="col-10">
							<button type="submit" class="btn btn-success">
								<?php if($serise_id_value): ?> 
							 		Save
							 	<?php else: ?>
							 		Add Series
							 	<?php endif; ?>
							</button>
								<a href="<?php echo base_url(); ?>admin/series" class="btn btn-secondary">Cancel</a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>



<?php
$productselected = '';
 if(isset($serise['productselected']) && !empty($serise['productselected']))
{
	$productselected = $serise['productselected'];
}?>
	
<?php
$subcategory = '';
 if(isset($serise['data']['title']) && !empty($serise['data']['title']))
{
	$subcategory = $serise['data']['title'];
}
?>



<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";	
	var js_serise_id = "<?php echo $serise_id_value; ?>";
	 
	function load_products_in_multipleselect(category_id)
	{
		$.ajax({
			url: base_url+'/admin/series/get_product/'+category_id,
			type: 'GET',
			data: {category_id: category_id},
			error: function() {
				$("#product_ids").empty();
				alert('Please Select category');
			},
			success: function(data) {
				// console.log(data);
				if(data === 'false')
				{
					$("#product_ids").empty();
					alert('There are no Product(s) in this category');
				}
				else{
					var product_options = '';
					var count = 1;
					jQuery.each(JSON.parse(data), function(i, val) {
						product_options=product_options+'<option value="'+i+'">'+val+'</option>';
						count++;
					});
					$("#product_ids").empty();
					$("#product_ids").append(product_options);
					$('#product_ids').attr('size', count);
					if(js_serise_id)
					{
						//assign_selected_product_if_update("<?php //echo $serise_id_value; ?>");
					}
				}
			}
		});
	}

	function load_category_in_multipleselect(category_id)
	{
		$.ajax({
			url: base_url+'/admin/series/get_category/'+category_id,
			type: 'GET',
			data: {category_id: category_id},
			error: function() {
				$("#category_ids").empty();
				alert('Please Select category');
			},
			success: function(data) {
				// console.log(data);
				if(data === 'false')
				{
					//$("#category_ids").empty();

					//alert('There are no Product(s) in this category');
					product_options = '<option value="0">Select series</option>';

					$("#category_ids").html(product_options);
					$('#category_ids option[value="0"]').prop('selected', true);
				}
				else{
					
					var product_options = '';
					var count = 1;
					jQuery.each(JSON.parse(data), function(i, val) {
						product_options=product_options+'<option value="'+i+'">'+val+'</option>';
						count++;
					});
					$("#category_ids").empty();
					$("#category_ids").append(product_options);
					$('#category_ids').attr('size', count);
					
					if($('#category_id').val() != '' && $('#formtype').val() == 'insertform' )
					{
						$('#category_ids option[value="0"]').prop('selected', true);
					}
					if(js_serise_id)
					{
						//assign_selected_product_if_update("<?php //echo $serise_id_value; ?>");
					}
				}
			}
		});
	}


	$(window).bind("load", function() { 
		if($( ".alert" ).hasClass( "alert-danger" ))
		{
   			 
	   		if($('#category_id').val() == '' )
			{
				//product_options = '<option value="">select Serise</option>';
				//$("#category_ids").append(product_options);
			}else{
				if($('#formtype').val() == 'insertform'){
				load_category_in_multipleselect($('#category_id').val());
			}
			}
		}
});
	$(document).ready(function() {
		console.log('hi');

		/*if($( ".alert" ).hasClass( "alert-danger" ))
		{
			
			load_category_in_multipleselect($('#category_id').val());
		}*/
		product_options = '<option value="">Select series</option>';
		$("#category_ids").append(product_options);

		set_category_old_selected_value();
		set_product_selected_value();
		set_category_selected_value();
		if($('#category_id').val() != '' && $('#formtype').val() == 'insertform' )
		{
			load_products_in_multipleselect($('#category_id').val());
		}
		 $('#category_id').select2({
           // placeholder: "Select a state"
        });
		  $('#product_ids').select2({
           // placeholder: "Select a state"
        });
		  $('#category_ids').select2({
           // placeholder: "Select a state"
        });

		 
		  //set_product_selected_value();
		 
	});


	function set_product_selected_value()
	{
		
			var selected_category = <?php echo json_encode($productselected); ?>;
			console.log(selected_category);

			var i;
			for (i = 0; i < selected_category.length; i++) {
				$("#product_ids option[value='"+selected_category[i]+"']").attr('selected', 'selected');
			}
		
	}

	function set_category_selected_value()
	{
		
			var selected_category = <?php echo json_encode($subcategory); ?>;
			console.log(selected_category);
			$("#category_ids option[value='"+selected_category+"']").attr('selected', 'selected');
			
		
	}

	function set_category_old_selected_value()
	{
		var selected_category = "<?php echo $category_id_value; ?>";


		if(selected_category)
		{
			$('#category_id option[value='+selected_category+']').prop('selected', true);
			//load_products_in_multipleselect(selected_category);
			//if(js_serise_id)
			//{
				//$('#category_id').find("option").prop("hidden", true);
			//}
		}
	}

	function assign_selected_product_if_update(serise_id)
	{
		$.ajax({
			url: base_url+'/admin/series/get_selected_product/'+serise_id,
			type: 'GET',
			data: {serise_id: serise_id},
			error: function() {
				 
				alert('Something went wrong');
			},
			success: function(data) {
				var selected_product_ids = JSON.parse(data);

				console.log(selected_product_ids);
				console.log('--------------------------------');
				if(data === 'false')
				{
					alert('There are no selected Product(s) in this category');
				}
				else{
					var i;
					for (i = 0; i < selected_product_ids.length; i++) {

						//$("#product_ids option[value='"+selected_product_ids[i]+"']").attr('selected', 'selected');
						console.log(selected_product_ids[i]);
						$('#product_ids option[value="'+selected_product_ids[i]+'"]').prop('selected', true);
					}
				}
			}
		});
	}

</script>


