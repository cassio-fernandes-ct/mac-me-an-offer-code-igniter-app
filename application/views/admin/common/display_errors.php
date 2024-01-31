<?php if(!empty(validation_errors())) { ?>
<div class="alert alert-danger ">
	<h3>Oops!</h3>
	<ul>
		<?php echo validation_errors(); ?>
	</ul>
</div>
<?php }   ?>

