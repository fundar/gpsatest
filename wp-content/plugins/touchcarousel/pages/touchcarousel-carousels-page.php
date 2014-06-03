<div class='touchcarousel-admin wrap'>
	<h2> <?php _e('TouchCarousel&#39;s', 'touchcarousel'); ?>
		<a href='<?php echo admin_url( "admin.php?page=touchcarousel&action=add_new" ); ?>' class='add-new-h2'><?php _e('Add New', 'touchcarousel'); ?></a>
	</h2>
	<?php 
		global $wpdb;
		if(isset($_GET['action'])) {
			if ($_GET['action'] == 'delete') {
				echo '<div id="message" class="updated below-h2"><p>'. __('Carousel #', 'touchcarousel') . $_GET['id'] . __(' deleted', 'touchcarousel') . '.</p></div>';
			} else if($_GET['action'] == 'duplicate') {
				echo '<div id="message" class="updated below-h2"><p>'. __('Carousel #', 'touchcarousel') . $_GET['id'] . __(' duplicated. New carousel #', 'touchcarousel') . $wpdb->insert_id . __(' created', 'touchcarousel') . '.</p></div>';
			}
		}
		
	?>
	<table class='touchcarousels touchcarousels-table wp-list-table widefat fixed pages'>
		<thead>
			<tr>
				<th width='5%'><?php _e('ID', 'touchcarousel'); ?></th>
				<th width='50%'><?php _e('Name', 'touchcarousel'); ?></th>
				<th width='30%'><?php _e('Actions', 'touchcarousel'); ?></th>
				<th width='20%'><?php _e('Shortcode', 'touchcarousel'); ?></th>						
			</tr>
		</thead>
		<tbody>
			<?php 				
				$carousels_table = $wpdb->prefix . 'touchcarousels';				
				$carousels = $wpdb->get_results("SELECT * FROM " . $carousels_table . " ORDER BY id");
				
				if (count($carousels) == 0) {					
					echo '<tr>'.
							 '<td colspan="100%">' . __('TouchCarousels\'s not found. Click button below to create new.', 'touchcarousel') . '</td>'.
						 '</tr>';	
				} else {
					$carousel_display_name;
					foreach ($carousels as $carousel) {
						
						$carousel_display_name = $carousel->name;
						if(!$carousel_display_name) {
							$carousel_display_name = 'TouchCarousel #' . $carousel->id . ' (no name)';
						}
						echo '<tr>'.
								'<td>' . $carousel->id . '</td>'.								
								'<td>' . '<a href="' . admin_url('admin.php?page=touchcarousel&action=edit&id=' . $carousel->id) . '" title="' . __('Edit', 'touchcarousel') .'">'.$carousel_display_name.'</a>' . '</td>'.
								'<td>' . '<a href="' . admin_url('admin.php?page=touchcarousel&action=edit&id=' . $carousel->id) . '" title="' . __('Edit this item', 'touchcarousel') .'">' . __('Edit', 'touchcarousel') .'</a> | '.									  
									  '<a class="delete-tcarousel-btn" href="#" data-protected-href="' . wp_nonce_url( admin_url('admin.php?page=touchcarousel&action=delete&id='  . $carousel->id), 'touchcarousel_delete_nonce') . '" title="' . __('Delete carousel permanently', 'touchcarousel') .'" >' . __('Delete', 'touchcarousel') .'</a> | '.
									  '<a href="' . wp_nonce_url( admin_url('admin.php?page=touchcarousel&action=duplicate&id='  . $carousel->id), 'touchcarousel_duplicate_nonce') . '" title="' . __('Duplicate carousel', 'touchcarousel') .'">' . __('Duplicate', 'touchcarousel') .'</a>'.
								'</td>'.
								'<td><input type="text" value="[touchcarousel id=\'' . $carousel->id . '\']"></input></td>'.	
							'</tr>';
					}
				}
			?>
		</tbody>		 
	</table>
	

	<p>			
		<a class='button-primary' href='<?php echo admin_url( "admin.php?page=touchcarousel&action=add_new" ); ?>'><?php _e('Create New Carousel', 'touchcarousel'); ?></a>   
		 
	</p>    	
	<p style="width:380px; float: right; margin-top: -28px;"><?php _e("Use shortcode - <code>[touchcarousel id=\"your_carousel_id\"]</code>, or PHP call - <code>&lt;?php echo get_touchcarousel(your_carousel_id); ?&gt;</code> to insert carousel in your theme.<br/><br/> If you're adding carousel with PHP, or outside page or post, so you must check \"Preload files\" option in carousel general settings.<br/><br/>If you've found bug, or have a question, so please contact me through my <a href=\"http://codecanyon.net/user/Semenov?ref=Semenov\">profile page on codecanyon</a>.", 'touchcarousel'); ?></p>    	
   
</div>
