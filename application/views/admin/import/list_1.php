<div class="page-container">
	<?php echo $left_nav;?>
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- END STYLE CUSTOMIZER -->
			<!-- BEGIN PAGE HEADER-->
			<div class="row">
				<div class="col-md-12">
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo $this->config->site_url();?>/admin/dashboard">
								<?php echo $this->lang->line('HOME');?>
							</a>
						</li>
						<li>
							Bulk Import/Export Management
						</li>
					</ul>
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<div class="row">
				 <div class="col-md-12">
						<div class="portlet">
							<div class="welcomemessage" style="display:none">
								  <div id="prefix_347077923304" style="font-size:15px" class="app-alerts alert alert-success fade in">
									  <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
									  <i class="fa-lg fa fa-check"></i>&nbsp;Filter data import successfully...
								  </div>
							</div>
							<?php 
							$logfilemsg = $this->session->userdata('logfileremove');
							if(isset($logfilemsg) && !empty($logfilemsg)){ ?>
								<div class="alert alert-success fade in">
									  <button class="close" data-close="alert"></button> 
									  <i class="fa-lg fa fa-check"></i>&nbsp; File deleted successfully...
									 <?php $this->session->unset_userdata('logfileremove'); ?>
								</div>
							<?php }	?>
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-upload"></i> Bulk Import/Export Management
								</div>
								<div class="action" style="text-align:right">
									<a href="<?php echo $this->config->site_url();?>/admin/import/exportfilterdata" class="btn green"><i class="fa fa-download" aria-hidden="true"></i> Export Data</a>
									<a class="btn green" href="<?php echo $this->config->base_url();?>application/sample.csv" download="sample.csv"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Sample CSV</a>
								</div>
							</div>	
							<div class="portlet-body">
								<form action="" method="post" enctype="multipart/form-data">
									<div class="table-responsive">
										<div class="form-group">
											<label class="control-label col-md-3"><i class="fa fa-file-excel-o"></i> Upload CSV File</label>
											<div class="col-md-9">
												<button id="csv_import" class="btn">Select file</button>
												<span id="status"></span>
												<span id="files"></span>
												<span class="help-block">[Please upload only .CSV file.]</span>
											</div>	
										 </div>
									</div>
								</form>
							</div>
						</div>
					</div>
			</div>
			<div class="completed_rationg"></div>
			<div id="list_csv_order_data"></div>
			
			<?php if(isset($import_log_data) && !empty($import_log_data)){  ?>
				<div class="portlet box green">
				 <div class="portlet-title">
					<div class="caption">
						<i class="fa fa-history"></i> View imported files
					</div>
					<div class="tools">
						<a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
						<a href="#portlet-config" data-toggle="modal" class="config" data-original-title="" title=""> </a>
						<a href="javascript:;" class="reload" data-original-title="" title=""> </a>
						<a href="javascript:;" class="remove" data-original-title="" title=""> </a>
					</div>
				 </div>
				 <div class="portlet-body flip-scroll">
					<table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1" role="grid">
						<thead class="flip-content">
							<tr>
								<th>
									 #
								</th>
								<th>
									Date
								</th>
								<th>
									File Name
								</th>
								<th>
									 Action
								</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if(isset($import_log_data) && !empty($import_log_data)){ 
									$nos = 1;
									foreach($import_log_data as $import_log_data_s){ ?>
										<tr>
											<td><?php echo $nos; ?></td>
											<td><?php echo date('Y-m-d H:i:s',strtotime($import_log_data_s['date']));?></td>
											<td><?php echo $import_log_data_s['file_name']; ?></td>
											<td>
												<a href="<?php echo $this->config->base_url();?>application/uploads/import/<?php echo $import_log_data_s['file_name']; ?>" download="<?php echo $import_log_data_s['file_name']; ?>" class="btn btn-outline btn-circle red btn-sm blue">
                                                    <i class="fa fa-download"></i> Download
												</a>
												<a onclick="return confirm('Are you sure you want to delete this entry?');" href="<?php echo $this->config->site_url();?>/admin/import/deletelogfile?id=<?php echo $import_log_data_s['id']; ?>&file_name=<?php echo $import_log_data_s['file_name']; ?>" class="btn btn-outline btn-circle dark btn-sm black">
                                                     <i class="fa fa-trash-o"></i> Delete 
												</a>
											</td>
										</tr>
										<?php $nos ++; }
								}else{ ?>
									<tr>
										<td  colspan="4" class="numeric respose_tag">Please try again</td>
									</tr>
							   <?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
	
<script language="javascript" type="text/javascript">
jQuery(document).ready(function() { 
	jQuery('.pleasewait').hide();
	var btnUpload = $('#csv_import');
	var status = $('#status');
	
	new AjaxUpload(btnUpload, {
		action: '<?php echo $this->config->site_url();?>/admin/import/uploadcsv',
		name: 'uploadfile[]',
		multiple: false,
		onSubmit: function(file, ext)
		{
			jQuery('.pleasewait').show();
			if (! (ext && /^(csv|CSV)$/.test(ext))){ 
				status.text('Only CSV file are allowed');
				return false;
			}status.html('<img src="<?php echo $this->config->base_url();?>assets/img/loader.gif">');
		},
		onComplete: function(file, response)
		{
			status.html('');
			status.text('');
			var responseObj = jQuery.parseJSON(response);
			if(responseObj.status=="success")
			{
				var filename = responseObj.field_name;
				$.ajax({
					type: "POST",
					url: "<?php echo $this->config->site_url();?>/admin/import/getorderdata",
					data: {filename: filename},
					dataType : 'html', 
					success: function(response){
						jQuery('.pleasewait').hide();
						$('#list_csv_order_data').html(response);
					}
				});
			}
			else
			{
				$('<span></span>').appendTo('#files').text(response.error_data).addClass('error');
			}
		}
	});
});
	
</script>
<style>
.processing td {
	background-color:#32c5d2;
	font-color:white;
}
.label.label-sm{ margin:2px;display:inline-block;padding:5px; margin-right:0px}
.label-success a,.completed .label-success a{ color:#FFF}
.completed a{ color:#000}
.processing td ,.processing th,.processing td a{ color:#FFF}
.handler{ cursor:pointer}
.completed_rationg {
	display:none;
    background-color: #7bc200;
    border: 1px solid #7bc200;
    color: white;
    margin-bottom: 10px;
    text-align: center;
}
</style>
<div class="pleasewait" style="background:url('/assets/img/loading.gif') no-repeat center center rgba(0, 0, 0, 0.4);  position:fixed; width:100% ; height:100%; top:0px; left:0px; z-index:9999; text-align:center; color:#FFFFFF; vertical-align:middle; padding-top:21%; font-size:15px; font-weight:bold; display:none"> Please wait.. </div>
	