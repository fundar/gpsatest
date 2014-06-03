<h1><?php _e('Variables', 'touchcarousel'); ?></h1>
<p>You can use TouchCarousel variables to create custom content. Each variable must be wraped in [tco]variable_name[/tco] tag. You can use post custom fields as variable names (see list of available fields for selected post below). For example to get Jigoshop price use [tco]price[/tco] tag.</p>
<h2>Built-in variables</h2>
<p>Built-in variables are formatted with default WordPress filters, if not otherwise stated. You can add your own custom variables, or format current in TouchCarouselAdmin.php, function "format_variables".</p>
<table class="tc-variables-table">
	<thead>
		<tr>
			<th width="350">Variable</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>[tco]title[/tco]</td>
			<td><?php _e('Post title.', 'touchcarousel'); ?></td>
		</tr>
		<tr>
			<td>[tco]excerpt[/tco]</td>
			<td><?php _e('Post excerpt.', 'touchcarousel'); ?></td>
		</tr>
		<tr>
			<td>[tco length="30"]excerpt[/tco]</td>
			<td><?php _e('Post excerpt, with custom word count. Change "30" to number of words that you need.', 'touchcarousel'); ?></td>
		</tr>
		
		<tr>
			<td>[tco]content[/tco]</td>
			<td><?php _e('Post content.', 'touchcarousel'); ?></td>
		</tr>
		
		<tr>
			<td>[tco]permalink[/tco]</td>
			<td><?php _e('URL link to post.', 'touchcarousel'); ?></td>
		</tr>
		
		<tr>
			<td>[tco]date[/tco]</td>
			<td><?php _e('Post publish date.', 'touchcarousel'); ?></td>
		</tr>
		
		<tr>
			<td>[tco]time[/tco]</td>
			<td><?php _e('Post publish time.', 'touchcarousel'); ?></td>
		</tr>
		
		<tr>
			<td>[tco width="350" height="220"]thumbnail[/tco]</td>
			<td><?php _e('Post thumbnail image element with custom width and height. Image is resized once using built-in WordPress functions.', 'touchcarousel'); ?></td>
		</tr>
		
		<tr>
			<td>[tco wp-size="medium"]thumbnail[/tco]</td>
			<td><?php _e('Post thumbnail image element. WordPress thumbnail size keyword is used (default are - thumbnail, medium, large or full), but you can use also other registered types. Use this method rather than above method to optimize performance.', 'touchcarousel'); ?></td>
		</tr>
		
		<tr>
			<td>[tco]comments-popup-link[/tco]</td>
			<td><?php _e('Anchor element with link to post comments .', 'touchcarousel'); ?></td>
		</tr>
		
		<tr>
			<td>[tco]comments-url[/tco]</td>
			<td><?php _e('URL link to post comments.', 'touchcarousel'); ?></td>
		</tr>
		
		<tr>
			<td>[tco]author-name[/tco]</td>
			<td><?php _e('Post author name.', 'touchcarousel'); ?></td>
		</tr>
		
		<tr>
			<td>[tco]author-url[/tco]</td>
			<td><?php _e('URL link to post author.', 'touchcarousel'); ?></td>
		</tr>
		
		<tr>
			<td>[tco]tags[/tco]</td>
			<td><?php _e('Comma-separated tags anchor elements.', 'touchcarousel'); ?></td>
		</tr>
		<tr>
			<td>[tco]categories[/tco]</td>
			<td><?php _e('Comma-separated categories anchor elements.', 'touchcarousel'); ?></td>
		</tr>
	</tbody>
</table>


<?php 

// TODO

if(!$this->get_carousel($id,true)) {
	echo "<p>Custom fields: You need to have at least one post of selected post type to get correct list of available custom fields.</p>";
	wp_reset_postdata();
	return;

}
global $post;
?>
<h2>Custom fields for "<?php echo $post->post_title ?>" post</h2>
<p>These fields are automatically generated from selected post type and selected taxonomies. Make sure that carousel is saved to get correct results here.</p>

<table class="tc-variables-table">
	<thead>
		<tr>
			<th width="350">Variable</th>
			<th>Value for test queried post</th>
		</tr>
	</thead>
	<tbody>
<?php
$custom_fields = get_post_custom($post->ID);
foreach($custom_fields as $key => $custom_field) {
	echo "<tr>\n";
		echo "<td>\n";
		echo "[tco]{$key}[/tco]\n";
		echo "</td>\n";
		echo "<td>{$custom_field[0]}</td>\n";		
	echo "</tr>\n";
}
echo "</tbody>\n";
echo "</table>\n";

wp_reset_postdata();

?>




































