<div class='touchcarousel-admin wrap'>
	<a href="admin.php?page=touchcarousel" class="back-to-list-link">&larr; <?php _e('Back to carousels list', 'touchcarousel'); ?></a>
	<h2 id="edit-slider-text"><?php
		
		if(isset($_GET['id']) && $_GET['id'] > -1) {
			$is_new = false;
			echo __('Edit TouchCarousel #', 'touchcarousel') . $_GET['id'];
		} else {
			$is_new = true;
			_e('Add New TouchCarousel', 'touchcarousel');
		}
	?></h2>
	<div id="poststuff" class="metabox-holder has-right-sidebar">



		<div class="sortable-slides-body">
			<div class="sortable-slides-container">
				<div id="titlediv">
					<div id="titlewrap">
						<input type="text" name="title" size="40" maxlength="255" placeholder="<?php _e('Type carousel name here', 'touchcarousel'); ?>" id="title" value="<?php if($slider_row) echo $slider_row['name']; ?>"/>
					</div>
				</div>
				<label>
				</label>
				
			
												
				<h4><?php _e('Content to include in carousel:', 'touchcarousel'); ?></h4>	
				<table class="settings-table">
					
					<tr>
						<td><label for="post-types-select"><?php _e('Post type', 'touchcarousel'); ?></label></td>
						<td>
							<select id="post-types-select">
								
							<?php 
							
							
							$selected_post_type = $slider_row ? $slider_row['post_type'] : 'post';
							
							$post_types = $this->get_carousel_post_types(); 
							foreach ($post_types  as $post_type ) {
							  	$selected = "";
							  	$label = $this->get_carousel_singular_post_type_name($post_type);
							  
						  		if($post_type == $selected_post_type) {
						  			$selected = "selected=\"selected\"";
						  		}
							    echo "<option value=\"$post_type\" $selected>$label</option>\n";
							}
							?>
							</select>
						</td>
					</tr>
					



					<tr>
						<td width="150px"><label><?php _e('Post taxonomies', 'touchcarousel'); ?></label></td>
						<td>
							<select id="post-categories-select" multiple="multiple">
								
				        	</select>
				        	<select id="post-taxonomy-relation">
				        		<option <?php if(!$slider_row || $slider_row['post_relation'] == 'OR') echo "selected=\"selected\""; ?> value="OR"><?php _e('Match any', 'touchcarousel'); ?></option>
				        		<option <?php if($slider_row['post_relation'] == 'AND') echo "selected=\"selected\""; ?> value="AND"><?php _e('Match all', 'touchcarousel'); ?></option>
				        	</select>
						</td>
					</tr>
					
					<tr>
						<td width="150px"><label for="max-posts-input"><?php _e('Max posts to include', 'touchcarousel'); ?></label></td>
						<td>
							<input id="max-posts-input" type="number" min="1" max="40" value="<?php if($slider_row) echo $slider_row['max_posts']; else echo '5'; ?>"></input>
						</td>
					</tr>
					<tr>
						<td width="150px"><label><?php _e('Order by', 'touchcarousel'); ?></label></td>
						<td class="radio-buttons">
							<label><input type="radio" <?php if(!$slider_row || $slider_row['post_orderby'] == 'post_date') echo "checked=\"checked\""; ?> value="post_date" name="post-order-radio"><?php _e('Date', 'touchcarousel'); ?></label>
							<label><input type="radio" <?php if($slider_row['post_orderby'] == 'comment_count') echo "checked=\"checked\""; ?> value="comment_count" name="post-order-radio"><?php _e('Popularity (comments)', 'touchcarousel'); ?></label>
						</td>
					</tr>
				</table>	
							
				
				
							
				



				<h4 class="layout-info-text"><?php _e('Carousel item layout type:' , 'touchcarousel'); ?></h4>
				<p class="description"><?php _e('Each layout represents structure of single carousel item. Numbers (for example 620x350) are sizes of this item. Don\'t forget to change size of carousel in general options to fit selected layout. Layouts may vary depending on your CSS and WP settings. If your post featured image is smaller than image in carousel, so it may not resized correctly.', 'touchcarousel'); ?></p>
				<ul id="layout-type-group">
					
				</ul>
				<div class="clear"></div>
				<table class="settings-table">
					<tr>
						<td width="150px"><label for="layout-text-input" class="layout-info-text"><?php _e('Item layout HTML:', 'touchcarousel'); ?></td>
						<td><textarea id="layout-text-input" rows="8"><?php echo stripslashes($slider_row['layout_code']); ?></textarea><span class="description"><a id="view-vars-list" href="#"><?php _e('View complete list of available variables','touchcarousel'); ?></a>  </label></span></td>
					</tr>
					<tr>
						<td width="150px"><label for="layout-css-text-input" class="layout-info-text"><?php _e('Item layout CSS:', 'touchcarousel'); ?></label></td>
						<td><textarea id="layout-css-text-input" rows="8"><?php echo stripslashes($slider_row['layout_css']); ?></textarea></td>
					</tr>
					<tr>
						<td><label for="carousel-css-classes" class="layout-info-text"><?php _e('Carousel CSS classes:', 'touchcarousel'); ?></label></td>
					
						<td><input id="carousel-css-classes" type="text" value="<?php echo stripslashes($slider_row['css_classes']); ?>" /><span class="description">&nbsp;<?php _e('Separated by space.', 'touchcarousel'); ?></span></td>
					</tr>
				</table>
				
			</div>
		</div>

		<div id="side-info-column" class="options-sidebar">
			<p class="tc-tip description"><?php _e('Tip: Hover over labels to learn more about options.', 'touchcarousel'); ?></p>
			<div class="postbox action actions-holder">
				<a class="alignright button-primary button80" id="save-slider" href="#"><?php if($is_new) _e('Create Carousel', 'touchcarousel');  else _e('Save Carousel', 'touchcarousel'); ?></a>
				<div id="save-progress" class="waiting ajax-saved" style="background-image: url(<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>)" >
				</div>
				<br class="clear">
			</div>
			<div id="touchcarousel-options" class="meta-box-sortables ui-sortable"><?php include('touchcarousel-options.php');  ?></div>
		</div>
	</div>
</div>

<script type="text/javascript">
	<?php 
		if($slider_row) {
			echo "var startLayoutName = '{$slider_row['layout_name']}'; \n";
		} else {
			echo "var startLayoutName = '';";
		}
	?>	
	
	<?php include_once($this->path . '/pages/touchcarousel-layouts.php'); ?>
	
	var customLayoutCode = {id : "tc-layout-custom", cssClasses: "tc-layout-custom", label: "<?php _e('Custom layout', 'touchcarousel'); ?>", html:'<!-- Enter custom item layout HTML code here. -->', css:"/* Use unique CSS selector to avoid conflicts */\n.touchcarousel.tc-layout-custom .touchcarousel-item {\n\n}"};

	var sliderSettings = <?php echo json_encode(stripslashes($slider_row['js_settings'])); ?>;
	
	var sliderID = parseInt(<?php if($slider_row) echo $slider_row['id']; else echo '-1'; ?>);

</script>