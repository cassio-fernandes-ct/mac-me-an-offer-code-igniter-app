<table class="table">
	<thead>
		<tr>
			<th> # </th>
			<th> Image </th>
			<th> Title </th>
			<th> Status </th>
		</tr>
	</thead>
	<tbody>
		<?php
			if(isset($category_tree_data) && !empty($category_tree_data) && count($category_tree_data)>0)
			{
				$no=1;
				foreach($category_tree_data as $category_tree_data_s)
					{
		?>
		<tr>
			<th scope="row"> <?php echo $category_tree_data_s['category_id'];  ?> </th>
			<td> <img src="<?php echo $category_tree_data_s['image'];  ?>" alt="Image Not Found" height=100>
			</td>
			<td><?php echo $category_tree_data_s['name'];  ?> </td>
			<td > <?php get_category_status_detail($category_tree_data_s['category_id'],$category_tree_data_s['is_visible']); ?> </td>
		</tr>
		<?php
		}
		}else{ ?>
		<tr>
			<td  colspan="3" class="numeric respose_tag">No Record Found.</td>
		</tr>
		<?php } ?>
	</tbody>
</table>


<div class="kt-section">
	<?php echo $this->ajax_pagination->create_links(); ?>
</div>	
</div>




<div class="loading-content">
	<div class="overlay">
		<div class="overlay-loading-content">
			<img src="<?php echo asset_url(); ?>media/loading/ajax-loader.gif" alt="Loading..."/>
		</div>
	</div>
</div>
<?php function get_category_status_detail($id,$is_visible) { if($is_visible == false ): ?>
<span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill cursor" onclick="category_status_update(<?php echo $id; ?>,1)">
	Not visible on homepage
</span>
<?php else: ?>
<span class="kt-badge kt-badge--brand kt-badge--inline kt-badge--pill cursor" onclick="category_status_update(<?php echo $id; ?>,0)">
	Show the category on Home page
</span>
<?php endif ?>
<?php } ?>
<script  type="text/javascript">
	function category_status_update(id,is_visible)
	{
		window.location="<?php echo base_url(); ?>admin/category/update/"+id+"/"+is_visible;
	}
</script>