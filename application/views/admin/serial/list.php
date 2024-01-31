<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
					Serial Management  </h3>
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
						Serial List
					</h3>

					
				</div>
				<div class="kt-portlet__head-toolbar">
            		<div class="kt-portlet__head-wrapper" >
						<a href="<?php  echo $this->config->base_url(); ?>admin/serial/create" class="btn btn-brand btn-icon-sm">
							<i class="flaticon2-plus"></i>
							Add Serial
						</a>

					</div>		
				</div>
			</div>
			<div class="kt-portlet__body">
				<?php $update 	= $this->session->userdata('updatedata'); 
					if(isset($update) && !empty($update)){ ?>
					<div class="alert alert-solid-success alert-bold" role="alert">
						<div class="alert-text">Serial updated successfully.</div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
					</div>
				<?php $this->session->unset_userdata('updatedata'); } ?>
				<?php $insered 	= $this->session->userdata('insertdata'); 
					if(isset($insered) && !empty($insered)){ ?>
					<div class="alert alert-solid-success alert-bold" role="alert">
						<div class="alert-text">Serial inserted successfully.</div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
					</div>
				<?php $this->session->unset_userdata('insertdata'); } ?>
				<?php $deleted 	= $this->session->userdata('deletedata'); 
					if(isset($deleted) && !empty($deleted)){ ?>
					<div class="alert alert-solid-danger alert-bold" role="alert">
						<div class="alert-text">Serial deleted successfully.</div>
						<div class="alert-close">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true"><i class="la la-close"></i></span>
							</button>
						</div>
					</div>
				<?php $this->session->unset_userdata('deletedata'); } ?>
				
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
				<div class="serise_data_ajax" id="ajax_data"></div>
			</div>	
		</div>
	</div>
</div>
<script src="<?php echo $this->config->base_url();?>assets/js/pages/crud/metronic-datatable/base/serial_data-ajax.js" type="text/javascript"></script>
 <script src="<?php echo $this->config->base_url();?>assets/js/pages/components/extended/sweetalert2.js" type="text/javascript"></script>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";	
	function update(categoryid,status)
	{
		window.location="<?php echo base_url(); ?>admin/category/update/"+categoryid+"/"+status;
	}
	
	function ask_confirmation_for_delete_serise(id,title)
	{

		swal.fire({
			title:"are you sure you want to delete the serial \""+title+"\" ?",
			text:"You won't be able to revert this!",
			type:"warning",
			showCancelButton:!0,
			confirmButtonText:"Yes, delete it!"
		}).then(function(e){
			e.value&&swal.fire("Deleted!","Your file has been deleted.","success");
			if(e.value)
			{
				delete_serise(title);
			}
		});

	}
	
	function delete_serise(title)
	{
		
		window.location=base_url+'admin/serial/delete/'+title;
	}
	function update_serise(title)
	{
		window.location=base_url+'admin/serial/update/'+title;
	}

</script>


