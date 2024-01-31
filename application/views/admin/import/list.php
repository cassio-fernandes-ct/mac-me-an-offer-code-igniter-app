<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
					Serial Number Import/Export Module </h3>
                <span class="kt-subheader__separator kt-hidden"></span>
                <div class="kt-subheader__breadcrumbs" style=" display: none">
                    <a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
						KTDatatable </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
						Base </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="" class="kt-subheader__breadcrumbs-link">
						Ajax Data </a>

                    <!-- <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Active link</span> -->
                </div>
            </div>
            <div class="kt-subheader__toolbar">
            	

            </div>
        </div>
    </div>
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
						Serial Number File Upload 
					</h3>
                </div>
                <div class="kt-portlet__head-toolbar">
            		<div class="kt-portlet__head-wrapper">
						<a href="<?php echo $this->config->base_url();?>admin/import/exportfilterdata" class="btn btn-brand btn-icon-sm">
							<i class="fa fa-file-export"></i>
							Export
						</a>
						<link href="<?php echo $this->config->base_url();?>assets/admin/admin.css" media="all" rel="stylesheet" />
					</div>		
				</div>
            </div>


            <!--begin::Form-->
            <form class="kt-form kt-form--label-right">
                <div class="kt-portlet__body">
                	
					<div class="alert alert-solid-success alert-bold welcomemessage" style="display: none" role="alert">
		                <div class="alert-text">Serial number import/update process has been completed successfully !!!
		                </div>
						<div class="alert-close">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="la la-close"></i></span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group form-group-last row">
                        <label class="col-lg-3 col-form-label">Upload Files:</label>
                        <div class="col-lg-9">
                            <a id="csv_import" class="btn btn-outline-primary">Attach files</a>
                            <span id="status" class="form-text text-muted "></span>
                        </div>
                    </div>

                </div>


            </form>
            <div id = "list_csv_order_data" > 
            	<div class="kt-portlet__head">
	                <div class="kt-portlet__head-label">
	                    <h3 class="kt-portlet__head-title">
							View Imported Files
						</h3>
	                </div>
	                <div class="kt-portlet__head-toolbar">
	            		
					</div>
	            </div>
	            <div class="kt-portlet__body kt-portlet__body--fit">
					<div class="import_file_log" id="import_file_log"></div>
				</div>
			</div>

        </div>
    </div>
</div>

<script language="javascript" type="text/javascript">
jQuery(document).ready(function() { 
	
	var btnUpload=$('#csv_import');
	var status=$('#status');
	new AjaxUpload(btnUpload, {
	action: '<?php echo $this->config->site_url(); ?>/admin/import/uploadcsv',
	name: 'uploadfile[]',
	multiple: false,
	onSubmit: function(file, ext)
	{
	
	if (ext != 'csv'){ 
    status.text('Only csv file are allowed');
   
	return false;
	}status.html('<div style = "padding:15px;" class="kt-spinner kt-spinner--sm kt-spinner--brand"></div>');
	},
	onComplete: function(file, response)
	{
		status.html('');
		status.text('');
		var responseObj = jQuery.parseJSON(response);
		if(responseObj.status=="success")
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
		else
		{
			$('<span></span>').appendTo('#files').text(response.error_data).addClass('error');
		}
	}});
});

function removeimage(str)
{
  var status=$('#status');
  status.html('<img src="<?php echo $this->config->base_url(); ?>/assets/img/loader.gif">');
  if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest();
  }
  else
  {
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function()
  {
	 if (xmlhttp.readyState==4 && xmlhttp.status==200)
	{
		 status.html('');
		 $("#banner_images").val('');
		 document.getElementById(str).style.display="none";
	}
  }
  var url="<?php echo $this->config->site_url(); ?>/admin/setting/ajaxdelete";
  url=url+"?imgname="+str;
  xmlhttp.open("GET",url,true);
  xmlhttp.send();
  return false;

}
</script>

<style type="text/css">
	.kt-widget17{
		margin-left: 24px;
	
}.kt-widget17 .kt-widget17__stats {
    display: column;
    margin: 0;    
}


</style>