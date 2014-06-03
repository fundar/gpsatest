<?php

	/*========================================================================================================================================================================
		Register logooos Post Type
	========================================================================================================================================================================*/
	
	add_action('init', 'logooos_init');
	function logooos_init() 
	{
		/*----------------------------------------------------------------------
			logooo Post Type Labels
		----------------------------------------------------------------------*/
		
		$labels = array(
			'name' => _x('Logos', 'Post type general name'),
			'singular_name' => _x('Logos', 'Post type singular name'),
			'add_new' => _x('Add new logo', 'logo Item'),
			'add_new_item' => __('Add new logo'),
			'edit_item' => __('Edit logo'),
			'new_item' => __('New logo'),
			'all_items' => __('All logos'),
			'view_item' => __('View'),
			'search_items' => __('Search'),
			'not_found' =>  __('No logos found.'),
			'not_found_in_trash' => __('No logos found.'), 
			'parent_item_colon' => '',
			'menu_name' => 'Logos'
		);
		
		/*----------------------------------------------------------------------
			logooo Post Type Properties
		----------------------------------------------------------------------*/
		
		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true, 
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title', 'thumbnail', 'page-attributes')
		);
		
		
		/*----------------------------------------------------------------------
			logooo Post Type Categories Register
		----------------------------------------------------------------------*/
		
		register_taxonomy(
			'logooocategory',
			array('logooo'),
			array(
				'hierarchical' => true,
				'labels' => array( 'name'=>'Categories', 'add_new_item' => 'Add New Category', 'parent_item' => 'Parent Category'),
				'query_var' => true,
				'rewrite' => array( 'slug' => 'logooocategory' )
			)
		);
		
		/*----------------------------------------------------------------------
			Register logooo Post Type Function
		----------------------------------------------------------------------*/
		
		register_post_type('logooo',$args);
		
		//Enabling Support for Post Thumbnails
		add_theme_support( 'post-thumbnails');
	}
	
	
	/*========================================================================================================================================================================
		logooo Post Type All Themes Table Columns
	========================================================================================================================================================================*/
	
	/*----------------------------------------------------------------------
		logooos Declaration Function
	----------------------------------------------------------------------*/
	function logooos_columns($logooos_columns){
		
		$order='asc';
		
		if($_GET['order']=='asc') {
			$order='desc';
		}
		
		$logooos_columns = array(

			"cb" => "<input type=\"checkbox\" />",
		
			"thumbnail" => "Image",
			
			"order" => "<a href='?post_type=logooo&orderby=menu_order&order=".$order."'>
								<span>Order</span>
								<span class='sorting-indicator'></span>
							</a>",

			"title" => "Title",
			
			"logoooscategories" => "Categories",

			"author" => "Author",
			
			"date" => "Date",

		);

		return $logooos_columns;

	}
	
	/*----------------------------------------------------------------------
		logooos Value Function
	----------------------------------------------------------------------*/
	function logooos_columns_display($logooos_columns, $post_id){
		
		global $post;
		
		$width = (int) 200;
		$height = (int) 200;
		
		if ( 'thumbnail' == $logooos_columns ) {
			
			if ( has_post_thumbnail($post_id)) {
				$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
				$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
				echo $thumb;
			}
			else 
			{
				echo __('None');
			}

		}
		
		if ( 'order' == $logooos_columns ) {
			echo $post->menu_order;
		}
		
		if ( 'logoooscategories' == $logooos_columns ) {
			
			$terms = get_the_terms( $post_id , 'logooocategory');
			$count = count($terms);
			
			if ( $terms ){
				
				$i = 0;
				
				foreach ( $terms as $term ) {
					echo '<a href="'.admin_url( 'edit.php?post_type=logooo&logooocategory='.$term->slug ).'">'.$term->name.'</a>';	
					
					if($i+1 != $count) {
						echo " , ";
					}
					$i++;
				}
				
			}
		}
		
	}
	
	/*----------------------------------------------------------------------
		Add manage_logooo_posts_columns Filter 
	----------------------------------------------------------------------*/
	add_filter("manage_logooo_posts_columns", "logooos_columns");
	
	/*----------------------------------------------------------------------
		Add manage_logooo_posts_custom_column Action
	----------------------------------------------------------------------*/
	add_action("manage_logooo_posts_custom_column",  "logooos_columns_display", 10, 2 );
	
	/*========================================================================================================================================================================
		Add Meta Box For logooo Post Type
	========================================================================================================================================================================*/
	
	/*----------------------------------------------------------------------
		add_meta_boxes Action For logooo Post Type
	----------------------------------------------------------------------*/
	
	add_action( 'add_meta_boxes', 'logooos_add_custom_box' );
	
	/*----------------------------------------------------------------------
		Properties Of logooos Options Meta Box 
	----------------------------------------------------------------------*/
	
	function logooos_add_custom_box() {
		add_meta_box( 
			'logooos_sectionid',
			__( 'Options', 'logooos_textdomain' ),
			'logooos_inner_custom_box',
			'logooo'
		);
	}
	
	/*----------------------------------------------------------------------
		Content Of logooos Options Meta Box 
	----------------------------------------------------------------------*/
	
	function logooos_inner_custom_box( $post ) {

		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'logooos_noncename' );
		
		?>
		
		<!-- Description -->
							
		<p><label for="description_text_input"><strong>Description</strong></label></p>
		
		<textarea type="text" name="description_text_input" id="description_text_input" class="regular-text code" rows="5" cols="40" ><?php echo get_post_meta($post->ID, 'description', true); ?></textarea>
		
		<hr class="horizontalRuler"/>
		
		
		<!-- Link Url -->
							
		<p><label for="link_input"><strong>Link Url</strong></label></p>
		
		http:// <input type="text" name="link_input" id="link_input" class="regular-text code" value="<?php echo get_post_meta($post->ID, 'link', true); ?>" />
							
		<p><span class="description">Example: (www.example.com)</span></p>
		
		<hr class="horizontalRuler"/>
		
		
		<p><label for="link_target_list"><strong>Link Target</strong></label></p>
			
		<select id="link_target_list" name="link_target_list">
			<option value="_blank" <?php if(get_post_meta($post->ID, 'link_target', true)=='_blank') { echo 'selected'; } ?> >blank</option>
			<option value="_self" <?php if(get_post_meta($post->ID, 'link_target', true)=='_self') { echo 'selected'; } ?> >self</option>
        </select>
		
		<hr class="horizontalRuler"/>
		
		<!-- Image Size -->
		
		<p><label for="imageSize_list"><strong>Image Size</strong></label></p>
			
		<select id="imageSize_list" name="imageSize_list">
			<option value="99%">99%</option>
			<?php 
			
			for($i=95 ; $i>=10 ; $i-=5) { 
				echo '<option ';
				
				if(get_post_meta($post->ID, 'imageSize', true) == '' && $i == 70)
				{
					echo 'selected ';
				}
				else if( get_post_meta($post->ID, 'imageSize', true) == $i.'%' )
				{
					echo 'selected ';
				}
				
				echo 'value="'.$i.'%">'.$i.'%</option>';
			} ?>
			
        </select>
		
		
		
		
		<?php
	}
	
	/*========================================================================================================================================================================
		Save logooos Options Meta Box Function
	========================================================================================================================================================================*/
	
	function logooos_save_meta_box($post_id) 
	{
		
		/*----------------------------------------------------------------------
			Description
		----------------------------------------------------------------------*/
		if(isset($_POST['description_text_input'])) {
			update_post_meta($post_id, 'description', $_POST['description_text_input']);
		}
		
		
		/*----------------------------------------------------------------------
			Link
		----------------------------------------------------------------------*/
		if(isset($_POST['link_input'])) {
			update_post_meta($post_id, 'link', $_POST['link_input']);
		}
		
		/*----------------------------------------------------------------------
			link target
		----------------------------------------------------------------------*/
		if(isset($_POST['link_target_list'])) {
			update_post_meta($post_id, 'link_target', $_POST['link_target_list']);
		}
		
		/*----------------------------------------------------------------------
			Image Size
		----------------------------------------------------------------------*/
		if(isset($_POST['imageSize_list'])) {
			update_post_meta($post_id, 'imageSize', $_POST['imageSize_list']);
		}
		
	}
	
	/*----------------------------------------------------------------------
		Save logooos Options Meta Box Action
	----------------------------------------------------------------------*/
	add_action('save_post', 'logooos_save_meta_box');

?>