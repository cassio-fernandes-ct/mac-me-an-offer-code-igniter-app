<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container  kt-container--fluid ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">
						Managewebhook Management  </h3>
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
						WebHooks List
					</h3>

					
				</div>
				<div class="kt-portlet__head-toolbar">
            		<div class="kt-portlet__head-wrapper" >
						<a href="<?php  echo $this->config->base_url(); ?>admin/managewebhook/add" class="btn btn-brand btn-icon-sm">
							<i class="flaticon2-plus"></i>
							Add WebHook
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
							
						</div>
					</div>
				</div>
		
				<div class="kt-portlet__body kt-portlet__body--fit">
					<div class="webhookclass" id="">
						<div class="kt-portlet__body">
			                <div class="kt-section">
			                    <div class="kt-section__content kt-section__content--solid">
			                        <div class="row">
			                            <div class="col-md-10">
			                          
			                                <h4>APP Details</h4>
			                                <div class="kt-space-10"></div>
			                                <h6>Context : <b><?php echo @$app_data['storehas'];?></b></h6>
			                                <div class="kt-space-10"></div>
			                                <h6>Access Token : <b><?php echo @$app_data['apitoken'];?></b></h6>
			                                <div class="kt-space-10"></div>
			                                <h6>View Live Web Hook: <b><a href="<?php echo $this->config->base_url();?>admin/managewebhook/getwebhooklive" target="_blank">Click here...</a></b></h6>
			                            </div>
			                          
			                        </div>

			                    </div>
			                </div>
			            </div>
					</div>
				</div>	
			</div>
			<div class="kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--loaded" id="ajax_data" style="">
			    <table class="kt-datatable__table" style="display: block;">
			        <thead class="kt-datatable__head">
			            <tr class="kt-datatable__row" style="left: 0px;">
			                <th data-field="category_id" class="kt-datatable__cell--center kt-datatable__cell"><span style="width: 30px;">#</span></th>
			                <th data-field="title" class="kt-datatable__cell"><span style="width: 100px;">WebHook ID</span></th>
			                <th data-field="category" class="kt-datatable__cell"><span style="width: 150px;">Scope</span></th>
			                <th data-field="action" class="kt-datatable__cell"><span style="width: 465px;">Destination</span></th>
			                <th data-field="action" class="kt-datatable__cell"><span style="width: 244px;">Action</span></th>
			            </tr>
			        </thead>
			        <tbody class="kt-datatable__body" style="">
			        	<?php if(isset($webhook_data) && !empty($webhook_data)){?>
			        		<?php foreach ($webhook_data as $value) {  
								if($value['store']=='mmo'){ ?>
			        		
				            <tr data-row="0" class="kt-datatable__row" style="left: 0px;">
				                <td class="kt-datatable__cell--center kt-datatable__cell" data-field="category_id"><span style="width: 30px;"><?php echo $value['id']; ?></span></td>
				                
				                <td data-field="category" class="kt-datatable__cell"><span style="width: 100px;"><?php echo $value['hook_id']; ?></span></td>
				                <td data-field="title" class="kt-datatable__cell"><span style="width: 150px;"><?php echo $value['scope']; ?></span></td>
				                <td data-field="title" class="kt-datatable__cell"><span style="width: 465px;"><?php echo $value['destination']; ?></span></td>
				                <td data-field="action" class="kt-datatable__cell"><span style="width: 244px;">
				                	<a href="<?php echo $this->config->base_url();?>admin/managewebhook/edit?id=<?php echo $value['id']; ?>" class="btn btn-outline-info btn-elevate btn-pill">
										<i class="flaticon-edit-1"></i>Update
									</a>
									<a onclick="return confirm('<?php echo $this->lang->line('DELETE_COMFIRM'); ?>');"  class="btn 	btn-outline-danger  btn-elevate btn-pill"  href="<?php echo $this->config->site_url();?>admin/managewebhook/delete?id=<?php echo $value['hook_id']; ?>" class="btn btn-outline-danger  btn-elevate btn-pill">
										<i class="flaticon-edit-1"></i>Delete
									</a>
				                </td>
				            </tr>
				        	<?php } } ?>
			            <?php } ?>
			        </tbody>
			    </table>
			</div>
		</div>
	</div>
</div>