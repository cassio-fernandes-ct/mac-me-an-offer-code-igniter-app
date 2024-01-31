<div class="clearfix">
</div>
<div class="page-container">
	<?php echo $left_nav;?>
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- END STYLE CUSTOMIZER -->
			<!-- BEGIN PAGE HEADER-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PAGE TITLE & BREADCRUMB-->
					<h3 class="page-title">
					<?php echo $page_title; ?>
					</h3>
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo $this->config->site_url();?>/admin/dashboard">
								<?php echo $this->lang->line('HOME');?>
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="javascript::void(0);">View WebHooks</a>
						</li>
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<?php if(isset($app_data) && !empty($app_data)){?>
				<div class="note note-success">
					<p><h4>APP Details</h4></p>
					<p>Username ID: <b><?php echo @$app_data['user_id'];?></b> &nbsp;&nbsp;&nbsp; Useremail: <b><?php echo @$app_data['user_email'];?></b></p>
					<p>Scope: <b><?php echo @$app_data['scope'];?></b> &nbsp;&nbsp;&nbsp; Context: <b><?php echo @$app_data['context'];?></b></p>
					<p>Access Token: <b><?php echo @$app_data['access_token'];?></b></p>
					<p>View Live Web Hook: <b><a href="<?php echo $this->config->site_url();?>/admin/managewebhook/getwebhooklive" target="_blank">Click here...</a></b></p>
				</div>
			<?php } ?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
			
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<?php
					$succe = $this->session->userdata('succe');
					if(isset($succe) && !empty($succe)){ ?>
						<div class="alert alert-success">
							<button class="close" data-dismiss="alert">×</button>
							<strong>Record Inset Successfully...<?php $this->session->unset_userdata('succe');?></strong>
						</div>
				    <?php } 
					$error = $this->session->userdata('error');
					if(isset($error) && !empty($error)){ ?>
						<div class="alert alert-success">
							<button class="close" data-dismiss="alert">×</button>
							<strong><?php echo $error; $this->session->unset_userdata('error');?></strong>
						</div>
				    <?php } 
					$updatescc = $this->session->userdata('updatescc');
					if(isset($updatescc) && !empty($updatescc)){ ?>
						<div class="alert alert-success">
							<button class="close" data-dismiss="alert">×</button>
							<strong>Record Update Successfully...<?php $this->session->unset_userdata('updatescc');?></strong>
						</div>
				    <?php } 
					$succ_delete = $this->session->userdata('succ_delete');
					if(isset($succ_delete) && !empty($succ_delete)){ ?>
						<div class="alert alert-success">
							<button class="close" data-dismiss="alert">×</button>
							<strong>Record Delete Successfully...<?php $this->session->unset_userdata('succ_delete');?></strong>
						</div>
				    <?php } ?> 
					
					<div class="portlet box green">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-shopping-cart"></i>&nbsp;
								<?php echo $page_head; ?>
							</div>	
						</div>
						
						<div class="portlet-body">
							<div class="btn-group">
								<button id="sample_editable_1_new" class="btn green" onclick="window.location='<?php echo $this->config->site_url();?>/admin/managewebhook/add'" >
									Add New <i class="fa fa-plus"></i>
								</button>
							</div>
							<div class="row"><div class="col-md-12">&nbsp;</div></div>
							<table class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>WebHook ID</th>
										<th>Scope</th>
										<th>Destination</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									if(isset($webhook_data) && !empty($webhook_data)){									
										foreach($webhook_data as $pdata){?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $pdata['hook_id']; ?></td>
											<td><?php echo $pdata['scope']; ?></td>
											<td><?php echo $pdata['destination']; ?></td>
											<td>
												<a class="btn default btn-xs green-stripe" href="<?php echo $this->config->site_url();?>/admin/managewebhook/edit?id=<?php echo $pdata['id']; ?>"><i class="fa fa-edit"></i> Edit</a> &nbsp;&nbsp; 
												<a onclick="return confirm('<?php echo $this->lang->line('DELETE_COMFIRM'); ?>');"  class="btn default btn-xs green-stripe" href="<?php echo $this->config->site_url();?>/admin/managewebhook/delete?id=<?php echo $pdata['hook_id']; ?>"><i class="fa fa-trash-o"></i> Delete</a>
											</td>
										</tr>
										<?php $i++;} 
									}?>
								</tbody> 
							</table>	
					    </div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
		</div>
	</div>
</div>

