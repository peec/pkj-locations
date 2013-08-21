
<table class="form-table editcomment">
	<tbody>
	<tr valign="top">
		<td class="first">Search for place</td>
		<td><input type="text" style="width: 80%;" placeholder="Search" id="admin_location_map_search" />
		
		<a class="button" id="admin_location_map_search_btn">Search</a>
		</td>
	</tr>
	</tbody>
</table>
<div id="admin_location_map" style="height: 400px;"></div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	console.log("hello");
	window.Pkj.mapWrap.setPos('<?php echo get_post_meta($post->ID, 'lat', true)?>', '<?php echo get_post_meta($post->ID, 'lon', true)?>');

});
</script>

<?php echo $fields_output ?>



