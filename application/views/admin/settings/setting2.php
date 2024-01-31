<?php 
$store_url = array(
        'type'  => 'text',
        'name'  => 'storeurl',
        'id'    => 'storeurl',
        'value' => $settingdata["storeurl"],
        'class' => 'form-control'
);
$store_front_url = array(
        'type'  => 'text',
        'name'  => 'store_front_url',
        'id'    => 'store_front_url',
        'value' => $settingdata["store_front_url"],
        'class' => 'form-control'
);

?>
<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
			Settings
			</h3>
		</div>
	</div>

 	<?php if(!empty(validation_errors())) { ?>  
 		<div class="alert alert-danger ">
 			<h3>Oops!</h3>
 			<ul>
	 			<?php echo validation_errors(); ?>
 			</ul>
 		</div>
 	<?php }   ?> 
	<?php if ($success == 1): ?>
		<div class="alert alert-success">
			<button class="close" data-dismiss="alert">×</button>
			<strong><?php echo $this->lang->line('GENERAL_SUCC'); ?></strong>
		</div>
	<?php endif; ?>




	<!--begin::Form-->
	<?php echo form_open(current_url(),['class'=>'kt-form kt-form--label-right','accept-charset'=>'utf-8']); ?>
	<div class="kt-portlet__body">
		<div class="form-group row">
			<label for="storeurl" class="col-2 col-form-label">Store URL  </label>
			<div class="col-10">

				<?php echo form_input($store_url); ?>
				 
			</div>
		</div>
		<div class="form-group row">
			<label for="store_front_url" class="col-2 col-form-label">Store Front URL</label>
			<div class="col-10">
				<?php echo form_input($store_front_url); ?>
			</div>
		</div>
		<div class="form-group row">
			<label for="apiusername" class="col-2 col-form-label">API Username</label>
			<div class="col-10">
				<input value="<?php echo $settingdata["apiusername"] ?>" id="apiusername" name="apiusername" type="text" class="form-control">
			</div>
		</div>
		<div class="form-group row">
			<label for="apipath" class="col-2 col-form-label">API Path</label>
			<div class="col-10">
				<input value="<?php echo $settingdata["apipath"] ?>" id="apipath" name="apipath" type="text" class="form-control">
			</div>
		</div>
		<div class="form-group row">
			<label for="apitoken" class="col-2 col-form-label">Access Token</label>
			<div class="col-10">
				<input value="<?php echo $settingdata["apitoken"] ?>" id="apitoken" name="apitoken" type="text" class="form-control">
			</div>
		</div>
		<div class="form-group row">
			<label for="storehas" class="col-2 col-form-label">Store Has</label>
			<div class="col-10">
				<input value="<?php echo $settingdata["storehas"] ?>" id="storehas" name="storehas" type="text" class="form-control">
			</div>
		</div>
		<div class="form-group row">
			<label for="client_id" class="col-2 col-form-label">Client Id</label>
			<div class="col-10">
				<input value="<?php echo $settingdata["client_id"] ?>" id="client_id" name="client_id" type="text" class="form-control">
			</div>
		</div>
		<div class="form-group row">
			<label for="client_secret" class="col-2 col-form-label">Client Secret</label>
			<div class="col-10">
				<input value="<?php echo $settingdata["client_secret"] ?>" id="client_secret" name="client_secret" type="text" class="form-control">
			</div>
		</div>

		<div class="form-group row">
			<label class="col-2 col-form-label">Store Logo</label>
			<div class="col-md-10">
				<span class="imagelist" id="files">
					<?php if (isset($settingdata['logo_image']) && !empty($settingdata['logo_image'])) {
					?>
					<div class="image_maindiv" id="<?php echo $settingdata['logo_image']; ?>" style="display:block">
						<img  src='<?php echo $this->config->base_url(); ?>application/uploads/sitelogo/thumb200/<?php echo $settingdata['logo_image']; ?>' border="0" alt="<?php echo $settingdata['logo_image']; ?>" class="group2">
						<a class="btn red delete image_removediv" onclick="removeimage('<?php echo $settingdata['logo_image']; ?>')" ><i class="fa fa-trash"></i><span> Delete</span></a>
					</div>
					<?php
					} ?>
				</span>
				<button id="banner_img" class="btn">Upload</button>
				<span id="status"></span>
			</div>
		</div>

	</div>
	<div class="kt-portlet__foot">
		<div class="kt-form__actions">
			<div class="row">
				<div class="col-2">
				</div>
				<div class="col-10">
					<button type="submit" class="btn btn-success">Submit</button>
					<button type="reset" class="btn btn-secondary">Cancel</button>
				</div>
			</div>
		</div>
	</div>
</form>
</div>

<script src="<?php echo asset_url();?>js/jquery.min.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">
jQuery(document).ready(function() { 
	alert('sdf');
	var btnUpload=$('#banner_img');
	var status=$('#status');
	new AjaxUpload(btnUpload, {
	action: '<?php echo $this->config->site_url(); ?>/admin/settings/ajaxupload/',
	name: 'uploadfile[]',
	multiple: false,
	onSubmit: function(file, ext)
	{
	
	if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
    status.text('Only JPG, PNG or GIF files are allowed');
	return false;
	}status.html('<img src="<?php echo $this->config->base_url(); ?>/assets/img/loader.gif">');
	},
	onComplete: function(file, response)
	{
		status.html('');
		status.text('');
		var responseObj = jQuery.parseJSON(response);
		if(responseObj.status=="success")
		{
			var images_data = responseObj.success_data.original;
			
			$.each(images_data,function(index, value ){
				var  imagename = "'"+value.file_name+"'";
				$('#files').html(''); 
				$('<span></span>').appendTo('#files').html('<div class="image_maindiv" id="'+value.file_name+'" style="display:block"><img src="<?php echo $this->config->base_url().'application/uploads/sitelogo/thumb200/'; ?>'+value.file_name+'" alt=""  /><a class="btn red delete image_removediv" onclick="removeimage('+imagename+')" ><i class="fa fa-trash"></i><span> Delete</span></a><input type="hidden" name="banner_images[]" value="'+value.file_name+'" />').addClass('success');
			});
			
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
<style>
.imagelist .image_maindiv > img {
    border: 1px solid #c2cad8;
    border-radius: 5px;
    display: inline-block;
    padding: 10px;
}
.imagelist .image_removediv {
    margin-bottom: 10px;
    margin-top: 10px;
}
</style>
