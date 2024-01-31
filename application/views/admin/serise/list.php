<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Series Management  </h3>
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
						Series List
					</h3>

					
				</div>
				<div class="kt-portlet__head-toolbar">
            		<div class="kt-portlet__head-wrapper" >
						<a href="<?php  echo $this->config->base_url(); ?>admin/series/create" class="btn btn-brand btn-icon-sm">
							<i class="flaticon2-plus"></i>
							Add Series
						</a>

					</div>		
				</div>
			</div>
			<div class="kt-portlet__body">
				<?php $insertdata 	= $this->session->userdata('insertdata'); 
				if(isset($insertdata) && !empty($insertdata)){ ?>
				<div class="alert alert-solid-success alert-bold" role="alert">
				<div class="alert-text">Series inserted successfully.</div>
				<div class="alert-close">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
				</button>
				</div>
				</div>
				<?php $this->session->unset_userdata('insertdata'); } ?>

				<?php $updatedata 	= $this->session->userdata('updatedata'); 
				if(isset($updatedata) && !empty($updatedata)){ ?>
				<div class="alert alert-solid-success alert-bold" role="alert">
				<div class="alert-text">Series updated successfully.</div>
				<div class="alert-close">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
				</button>
				</div>
				</div>
				<?php $this->session->unset_userdata('updatedata'); } ?>

				<?php $deleted 	= $this->session->userdata('deleted'); 
				if(isset($deleted) && !empty($deleted)){ ?>
					<div class="alert alert-solid-danger alert-bold" role="alert">
						<div class="alert-text">series deleted successfully.</div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
					</div>
					<!--<div class="swal2-container swal2-center swal2-shown okalert" style="overflow-y: auto;">
					    <div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex;">
					        <div class="swal2-header">
					            <ul class="swal2-progress-steps" style="display: none;"></ul>
					            <div class="swal2-icon swal2-error" style="display: none;">
					                <span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span>
					                <span class="swal2-x-mark-line-right"></span></span>
					            </div>
					            <div class="swal2-icon swal2-question" style="display: none;"></div>
					            <div class="swal2-icon swal2-warning" style="display: none;"></div>
					            <div class="swal2-icon swal2-info" style="display: none;"></div>
					            <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
					                <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div><span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span>
					                <div class="swal2-success-ring"></div>
					                <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
					                <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
					            </div><img class="swal2-image" style="display: none;">
					            <h2 class="swal2-title" id="swal2-title" style="display: flex;">Deleted!</h2>
					            <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
					        </div>
					        <div class="swal2-content">
					            <div id="swal2-content" style="display: block;">Your file has been deleted.</div>
					            <input class="swal2-input" style="display: none;">
					            <input type="file" class="swal2-file" style="display: none;">
					            <div class="swal2-range" style="display: none;">
					                <input type="range">
					                <output></output>
					            </div>
					            <select class="swal2-select" style="display: none;"></select>
					            <div class="swal2-radio" style="display: none;"></div>
					            <label for="swal2-checkbox" class="swal2-checkbox" style="display: none;">
					                <input type="checkbox"><span class="swal2-label"></span></label>
					            <textarea class="swal2-textarea" style="display: none;"></textarea>
					            <div class="swal2-validation-message" id="swal2-validation-message"></div>
					        </div>
					        <div class="swal2-actions">
					            <button id  = "confirm_me" type="button" class="swal2-confirm btn btn-brand" aria-label="" style="display: inline-block;">Ok</button>
					            <button type="button" class="swal2-cancel" aria-label="" style="display: none;">Cancel</button>
					        </div>
					        <div class="swal2-footer" style="display: none;"></div>
					    </div>
					</div>-->
				<?php $this->session->unset_userdata('deleted'); } ?>

				<div class="kt-form kt-form--label-right">
					<div class="row align-items-center">
						<div class="col-xl-8 order-2 order-xl-1">
							<div class="row align-items-center">
								<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
									<div class="kt-input-icon kt-input-icon--left">
										<input type="text" class="form-control" placeholder="Search..." id="generalSearch">
										<span class="kt-input-icon__icon kt-input-icon__icon--left">
											<span><i class="la la-search"></i></span>
										</span>
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
		</div>
	</div>
</div>
<script src="<?php echo $this->config->base_url();?>assets/js/pages/crud/metronic-datatable/base/serise_data-ajax.js" type="text/javascript"></script>
 <script src="<?php echo $this->config->base_url();?>assets/js/pages/components/extended/sweetalert2.js" type="text/javascript"></script>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";	
	function update(categoryid,status)
	{
		window.location="<?php echo base_url(); ?>admin/category/update/"+categoryid+"/"+status;
	}
	function ask_confirmation_for_delete_serise(serise_id,title)
	{

		swal.fire({
			title:"are you sure you want to delete the series \""+title+"\" ?",
			text:"You won't be able to revert this!",
			type:"warning",
			showCancelButton:!0,
			confirmButtonText:"Yes, delete it!"
		}).then(function(e){
			e.value&&swal.fire("Deleted!","Your series has been deleted.","success");
			if(e.value)
			{
				delete_serise(serise_id);
			}
		});

	}
	
	function delete_serise(serise_id)
	{
		window.location=base_url+'admin/series/delete/'+serise_id;
	}
	function update_serise(serise_id)
	{
		window.location=base_url+'admin/series/update/'+serise_id;
	}

	$('#confirm_me').click(function()
	{
		console.log('hello me !');
		$('.okalert').hide();
	});
</script>


