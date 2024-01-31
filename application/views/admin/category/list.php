<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Category Management </h3>
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
						Category List
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
            		<div class="kt-portlet__head-wrapper" style="display: none">
						<a href="#" class="btn btn-clean btn-icon-sm">
							<i class="la la-long-arrow-left"></i>
							Back
						</a>
						<div class="dropdown dropdown-inline">
							<button type="button" class="btn btn-brand btn-icon-sm">
								<i class="flaticon2-plus"></i> Add New  	
							</button>
						</div>
					</div>		
				</div>
			</div>
			<form method = "POST" action="<?php echo $this->config->base_url();?>admin/category/orderlist">
			<div class="kt-portlet__body">
				<div class="kt-form kt-form--label-right">
					<div class="row align-items-center">
						<div class="col-xl-8 order-2 order-xl-1">
							<?php $update 	= $this->session->userdata('updatedata'); 
							if(isset($update) && !empty($update)){ ?>
							<div class="alert alert-solid-success alert-bold" role="alert">
			                    <div class="alert-text">Category status updated.</div>
								<div class="alert-close">
			                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			                            <span aria-hidden="true"><i class="la la-close"></i></span>
			                        </button>
			                    </div>
			                </div>
							<?php $this->session->unset_userdata('updatedata'); } ?>
							<div class="row align-items-center">
								<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-input-icon kt-input-icon--left">
										<input type="text" class="form-control" placeholder="Search..." id="generalSearch">
										<span class="kt-input-icon__icon kt-input-icon__icon--left">
											<span><i class="la la-search"></i></span>
										</span>
									</div>
								</div>
								<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-form__group kt-form__group--inline">
										<input type="submit" id="display_order" value="Update Display Order" class="btn btn-brand btn-icon-sm">
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="kt-portlet__body kt-portlet__body--fit">
				
					
				<div class="kt-datatable" id="ajax_data"></div>
				
			</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	
	$('#display_order').click(function(){
    	if($( ".error" ).hasClass( "error_d" ))
    	{
    		$('html, body').animate({
		    		scrollTop: $('.error_d').offset().top-200
			}, 1000);
    		return false;
    	}else{

    	}
	});

	function update(categoryid,status)
	{
		window.location="<?php echo base_url(); ?>admin/category/update/"+categoryid+"/"+status;
	}

	function arraysEqual(arr1, arr2) {
	    if(arr1.length !== arr2.length)
	        return false;
	    for(var i = arr1.length; i--;) {
	        if(arr1[i] !== arr2[i])
	            return false;
	    }

	    return true;
	}

	function myFunction(id){
		var v =  $('#'+id).val(); 
		
		if($('#'+id).val() == '0'){
			 $('#'+id).val('');  
		}else if($('#'+id).val() == ''){
			$("#display_order_error"+id).remove();
			$('#'+id).after('<div class = "error display_order_error error_d" id = "display_order_error'+id+'">Please add display order</div>');
		}else{
			var obj = {

			  "val": v,
			  "id": id,
			  "dishs": []
			};
			$('.display_order_textbox').each(function(i, v) {
			  obj.dishs.push({
			    'dish_id': $(v).attr('id'),
			    'amount': $(v).val()
			  });
			});
			
			var duplicate = [];
			$.each(obj.dishs, function( index, value ) {
			     $.each(obj.dishs, function( index2, value2 ) {
			          if(arraysEqual(value,value2))
			               duplicate.push(value);
			     });
			});
			var uniqueQns = {};
			var uniqueQnObj = [];


			$.each(obj.dishs, function(i, ele) {

			    if (!uniqueQns[ele.amount]) {
			        uniqueQns[ele.amount] = true;
			        uniqueQnObj.push(ele);
			       
			     
			    } else {
			        uniqueQns[ele.amount] = false;
			       
			    }
			});
			
			var url = "<?php echo base_url(); ?>admin/category/checkexistornot";
			 
			$.ajax({
					url: url,  
					data: obj,
					type: 'POST',
	                success: function(result) { 
	                	console.log(result);
	                	if(obj.dishs.length == uniqueQnObj.length){ 
	                		$(".duplicatepage").remove();

		                		if(result > 0)
		                		{ 
		                			$("#display_order_error"+id).remove();
		                			$('#'+id).after('<div class = "error display_order_error error_d" id = "display_order_error'+id+'">Category display order already defined.</div>');
		                		}else{
		                			$('#'+id).next().remove();
		                		}	
		                	
	                	}else{
	                			$('#'+id).next().remove();
	                			// $('#'+id).after('<div class = "error duplicatepage error_d" id = "display_order_error'+id+'" >Category display order already defined.</div>');

	                			if(result > 0)
		                		{ 
		                			$("#display_order_error"+id).remove();
		                			$('#'+id).after('<div class = "error display_order_error error_d" id = "display_order_error'+id+'">Category display order already defined.</div>');
		                		}else{
		                			//$('#'+id).next().remove();
		                		}
		                		
	                	}
	                	     
	                }}); 
		}
	}

	function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>
<style type="text/css">
	
	.error{
		color: red;
	}
</style>