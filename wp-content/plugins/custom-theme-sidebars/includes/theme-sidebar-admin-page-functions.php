<?php
/**
 * Theme Sidebar Admin Page Functions
 *
 * This file contains all of the necessary functions that
 * are rquired in order for the sidebar admin screen to
 * function correctly.
 * 
 * @package 	WordPress
 * @subpackage 	Custom_Theme_Sidebars
 * @author 		Sunny Johal - Titanium Themes
 * @copyright 	Copyright (c) 2014, Titanium Themes
 * @version 	1.4
 * 
 */

/**
 * CUSTOM SIDEBAR BACKEND FUNCTIONS
 * =================================
 */

/**
 * Setup Sidebar Metaboxes
 * 
 * Creates a new array item in the global $wp_meta_boxes and
 * then modify this data so that it is ready for the admin
 * page.
 *
 * @uses 	master_sidebar_post_type_meta_boxes() 			defined in includes/theme-sidebar-admin-page-functions.php
 * @uses 	master_sidebar_category_posts_metabox() 		defined in includes/theme-sidebar-admin-page-functions.php
 * @uses 	master_sidebar_taxonomy_meta_boxes() 			defined in includes/theme-sidebar-admin-page-functions.php
 * @uses 	master_sidebar_author_meta_box() 				defined in includes/theme-sidebar-admin-page-functions.php
 * @uses 	master_sidebar_template_hierarchy_meta_box() 	defined in includes/theme-sidebar-admin-page-functions.php
 *
 * @global array $wp_meta_boxes
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_setup_sidebar_metaboxes() {	
	global $wp_meta_boxes;
	
	master_sidebar_post_type_meta_boxes(); 			// Output Posttype Metaboxes
	master_sidebar_category_posts_metabox(); 		// Output Category Posts Metabox
	master_sidebar_taxonomy_meta_boxes();  			// Output Taxonomy Metaboxes
	master_sidebar_author_meta_box();      			// Output Author Archive Metabox
	master_sidebar_template_hierarchy_meta_box();	// Output Custom Template Hierarchy Metabox 
}

/**
 * Register Category Posts Metabox
 *
 * Registers the custom metabox that is added in order
 * to cater for the posts with category feature.
 *
 * @link 	http://codex.wordpress.org/Function_Reference/add_meta_box 		add_meta_box()
 * 
 * @since 1.1
 * @version 1.4
 * 
 */
function master_sidebar_category_posts_metabox() {

	$admin_page_name = 'appearance_page_custom_theme_sidebars';

	add_meta_box( 
		'master-add-category-posts-metabox', 
		__( 'All Posts In Category', 'theme-translate' ), 
		'master_sidebar_render_category_posts_metabox', 
		$admin_page_name, 
		'side', 
		'default'
	);
}

/**
 * Display Category Posts Metabox
 *
 * This function contains the output for the category 
 * posts metabox on the admin page of this plugin.
 *
 * @link 	http://codex.wordpress.org/Function_Reference/get_terms 						get_terms()
 * @link 	http://codex.wordpress.org/Function_Reference/get_taxonomy						get_taxonomy()
 * @link 	http://codex.wordpress.org/Function_Reference/is_wp_error						is_wp_error()
 * @link 	http://codex.wordpress.org/Function_Reference/wp_count_terms					wp_count_terms()
 * @link 	http://codex.wordpress.org/Function_Reference/esc_url							esc_url()
 * @link 	http://codex.wordpress.org/Function_Reference/esc_attr_e						esc_attr_e()
 * @link 	http://codex.wordpress.org/Function_Reference/admin_url							admin_url()
 * @link 	http://codex.wordpress.org/Function_Reference/add_query_arg						add_query_arg()
 * @link 	http://codex.wordpress.org/Function_Reference/paginate_links					paginate_links()
 * @link 	http://codex.wordpress.org/Function_Reference/is_taxonomy_hierarchical			is_taxonomy_hierarchical()
 *
 * @since 1.1
 * @version 1.4
 * 
 */
function master_sidebar_render_category_posts_metabox() {
	global $nav_menu_selected_id;
	$taxonomy_name = 'category';

	// Paginate browsing for large numbers of objects
	$per_page = 50;

	// Check if any variables have been passed in the URL
	$pagenum  = isset( $_REQUEST['custom-item-type'] ) && isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
	$offset   = 0 < $pagenum ? $per_page * ( $pagenum - 1 ) : 0;

	// Define query args for pagination items 
	$args = array(
		'child_of'     => 0,
		'exclude'      => '',
		'hide_empty'   => false,
		'hierarchical' => 1,
		'include'      => '',
		'number'       => $per_page,
		'offset'       => $offset,
		'order'        => 'ASC',
		'orderby'      => 'name',
		'pad_counts'   => false,
	);

	// Get taxonomy terms and object
	$terms        = get_terms( $taxonomy_name, $args );
	$taxonomy_obj = get_taxonomy( $taxonomy_name );

	// Display feedback message if there are no categories
	if ( ! $terms || is_wp_error($terms) ) {
		echo '<p>' . __( 'No items.', 'theme-translate' ) . '</p>';
		return;
	}

	// Determine number of pages
	$num_pages = ceil( wp_count_terms( $taxonomy_name , array_merge( $args, array('number' => '', 'offset' => '') ) ) / $per_page );

	// Define admin page url
	$admin_url  = esc_url( 
					add_query_arg( 
						array( 
							'page' => 'custom_theme_sidebars' 
						), 
						admin_url( 'themes.php' ) 
					) 
				);

	// Generate pagination
	$page_links = paginate_links( array(
		'base' => add_query_arg(
			array(
				$taxonomy_name . '-tab' => 'all',
				'paged'                 => '%#%',
				'item-type'             => 'taxonomy',
				'item-object'           => $taxonomy_name,
				'custom-item-type'		=> 'category_posts',
			), $admin_url
		),
		'format'    => '',
		'prev_text' => __('&laquo;'),
		'next_text' => __('&raquo;'),
		'total'     => $num_pages,
		'current'   => $pagenum
	));

	$db_fields = false;
	if ( is_taxonomy_hierarchical( $taxonomy_name ) ) {
		$db_fields = array( 
			'parent' => 'parent', 
			'id'     => 'term_id', 
		);
	}

	// Define our own custom walker
	$walker = new Master_Walker_Sidebar_Checklist( $db_fields );

	// Determine the current tab to use
	$current_tab = 'most-used';
	if ( isset( $_REQUEST[$taxonomy_name . '-tab'] ) && in_array( $_REQUEST[$taxonomy_name . '-tab'], array('all', 'most-used', 'search') ) ) {
		$current_tab = $_REQUEST[$taxonomy_name . '-tab'];
	}

	if ( ! empty( $_REQUEST['quick-search-taxonomy-' . $taxonomy_name] ) ) {
		$current_tab = 'search';
	}

	$removed_args = array(
		'action',
		'customlink-tab',
		'edit-menu-item',
		'menu-item',
		'page-tab',
		'_wpnonce',
	);
	?>
	<div id="taxonomy-<?php echo $taxonomy_name; ?>-custom-category" class="taxonomydiv">
		
		<!-- Tab Panel Tabs -->
		<ul id="taxonomy-<?php echo $taxonomy_name; ?>-tabs-custom-category" class="taxonomy-tabs add-menu-item-tabs">
			<li <?php echo ( 'most-used' == $current_tab ? ' class="tabs"' : '' ); ?>>
				<a class="nav-tab-link" href="<?php if ( $nav_menu_selected_id ) echo esc_url(add_query_arg($taxonomy_name . '-tab', 'most-used', remove_query_arg($removed_args))); ?>#tabs-panel-<?php echo $taxonomy_name; ?>-pop-custom-category">
					<?php _e('Most Used'); ?>
				</a>
			</li>
			<li <?php echo ( 'all' == $current_tab ? ' class="tabs"' : '' ); ?>>
				<a class="nav-tab-link" href="<?php if ( $nav_menu_selected_id ) echo esc_url(add_query_arg($taxonomy_name . '-tab', 'all', remove_query_arg($removed_args))); ?>#tabs-panel-<?php echo $taxonomy_name; ?>-all-custom-category">
					<?php _e('View All'); ?>
				</a>
			</li>
			<li <?php echo ( 'search' == $current_tab ? ' class="tabs"' : '' ); ?>>
				<a class="nav-tab-link" href="<?php if ( $nav_menu_selected_id ) echo esc_url(add_query_arg($taxonomy_name . '-tab', 'search', remove_query_arg($removed_args))); ?>#tabs-panel-search-taxonomy-<?php echo $taxonomy_name; ?>-custom-category">
					<?php _e('Search'); ?>
				</a>
			</li>
		</ul>

		<!-- Tab Panels -->
		<div id="tabs-panel-<?php echo $taxonomy_name; ?>-pop-custom-category" class="tabs-panel <?php
			echo ( 'most-used' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
		?>">
			<ul id="<?php echo $taxonomy_name; ?>checklist-pop" class="categorychecklist form-no-clear" >
				<?php
					
					// Get popular terms
					$popular_terms = get_terms( 
										$taxonomy_name, 
										array( 
											'orderby'      => 'count', 
											'order'        => 'DESC', 
											'number'       => 10, 
											'hierarchical' => false,
										)
									);
					
					// Use the custom walker
					$args['walker'] = $walker;

					// Set custom array index to indicate a custom data type
					$args['custom_item_type'] = 'category_posts';

					// Output menu markup
					echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $popular_terms), 0, (object) $args );
				?>
			</ul>
		</div><!-- /.tabs-panel -->

		<div id="tabs-panel-<?php echo $taxonomy_name; ?>-all-custom-category" class="tabs-panel tabs-panel-view-all <?php
			echo ( 'all' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
		?>">
			<?php if ( ! empty( $page_links ) ) : ?>
				<div class="add-menu-item-pagelinks">
					<?php echo $page_links; ?>
				</div>
			<?php endif; ?>
			<ul id="<?php echo $taxonomy_name; ?>checklist" data-wp-lists="list:<?php echo $taxonomy_name?>" class="categorychecklist form-no-clear">
				<?php
					// Use the custom walker
					$args['walker'] = $walker;

					// Set custom array index to indicate a custom data type
					$args['custom_item_type'] = 'category_posts';

					echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $terms), 0, (object) $args );
				?>
			</ul>
			<?php if ( ! empty( $page_links ) ) : ?>
				<div class="add-menu-item-pagelinks">
					<?php echo $page_links; ?>
				</div>
			<?php endif; ?>
		</div><!-- /.tabs-panel -->

		<div class="tabs-panel <?php
			echo ( 'search' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
		?>" id="tabs-panel-search-taxonomy-<?php echo $taxonomy_name; ?>-custom-category">
			<?php
			if ( isset( $_REQUEST['quick-search-taxonomy-' . $taxonomy_name] ) ) {
				$searched = esc_attr( $_REQUEST['quick-search-taxonomy-' . $taxonomy_name] );
				$search_results = get_terms( $taxonomy_name, array( 'name__like' => $searched, 'fields' => 'all', 'orderby' => 'count', 'order' => 'DESC', 'hierarchical' => false ) );
			} else {
				$searched = '';
				$search_results = array();
			}
			?>
			<p class="quick-search-wrap">
				<input type="search" class="quick-search input-with-default-title" title="<?php esc_attr_e('Search'); ?>" value="<?php echo $searched; ?>" name="quick-search-taxonomy-<?php echo $taxonomy_name; ?>-custom-category" />
				<span class="spinner"></span>
				<?php submit_button( __( 'Search' ), 'button-small quick-search-submit button-secondary hide-if-js', 'submit', false, array( 'id' => 'submit-quick-search-taxonomy-' . $taxonomy_name ) ); ?>
			</p>

			<ul id="<?php echo $taxonomy_name; ?>-search-checklist" data-wp-lists="list:<?php echo $taxonomy_name?>" class="categorychecklist form-no-clear">
			<?php if ( ! empty( $search_results ) && ! is_wp_error( $search_results ) ) : ?>
				<?php
					// Use the custom walker
					$args['walker'] = $walker;

					// Set custom array index to indicate a custom data type
					$args['custom_item_type'] = 'category_posts';
					
					echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $search_results), 0, (object) $args );
				?>
			<?php elseif ( is_wp_error( $search_results ) ) : ?>
				<li><?php echo $search_results->get_error_message(); ?></li>
			<?php elseif ( ! empty( $searched ) ) : ?>
				<li><?php _e('No results found.'); ?></li>
			<?php endif; ?>
			</ul>
		</div><!-- /.tabs-panel -->

		<p class="button-controls">
			<span class="list-controls">
				<a href="<?php
					echo esc_url(add_query_arg(
						array(
							$taxonomy_name . '-tab' => 'all',
							'selectall' => 1,
						),
						remove_query_arg($removed_args)
					));
				?>#taxonomy-<?php echo $taxonomy_name; ?>-custom-category" class="select-all"><?php _e( 'Select All', 'theme-translate' ); ?></a>
			</span>

			<span class="add-to-menu">
				<input type="submit"<?php disabled( $nav_menu_selected_id, 0 ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Sidebar', 'theme-translate' ); ?>" name="add-taxonomy-menu-item" id="submit-taxonomy-<?php echo $taxonomy_name; ?>-custom-category" />
				<span class="spinner"></span>
			</span>
		</p>

	</div><!-- /.taxonomydiv -->
	<?php
}

/**
 * Retrieve All Post Type Metaboxes
 *
 * Gets all posttypes that are currently registered
 * with the currently active WordPress theme and 
 * registers metaboxes for each posttype for use on
 * the Sidebar Admin Page.
 *
 * @link 	http://codex.wordpress.org/Function_Reference/get_post_types 	get_post_types()
 * @link 	http://codex.wordpress.org/Function_Reference/apply_filters 	apply_filters()
 * @link 	http://codex.wordpress.org/Function_Reference/add_meta_box 		add_meta_box()
 *
 * @uses 	master_sidebar_item_post_type_meta_box() 	defined in includes/theme-sidebar-admin-page-functions.php
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_sidebar_post_type_meta_boxes() {
	
	$post_types      = get_post_types( array( 'show_in_nav_menus' => true ), 'object' );
	$admin_page_name = 'appearance_page_custom_theme_sidebars';

	if ( ! $post_types ) {
		return;
	}

	// Add metabox for each posttype
	foreach ( $post_types as $post_type ) {
		$post_type = apply_filters( 'master_sidebar_meta_box_object', $post_type );
		if ( $post_type ) {
			$id = $post_type->name;
			add_meta_box( 
				"master-add-{$id}", 
				$post_type->labels->name, 
				'master_sidebar_item_post_type_meta_box', 
				$admin_page_name, 
				'side', 
				'default', 
				$post_type 
			);
		}
	}
}

/**
 * Displays a Metabox for a Post Type Sidebar item.
 *
 * This function outputs the sidebar checklist metabox
 * that is used on the admin page.
 * 
 * @link 	http://codex.wordpress.org/Function_Reference/apply_filters 				apply_filters()
 * @link 	http://codex.wordpress.org/Function_Reference/get_post_type_object			get_post_type_object()
 * @link 	http://codex.wordpress.org/Function_Reference/paginate_links				paginate_links()
 * @link 	http://codex.wordpress.org/Function_Reference/add_query_arg					add_query_arg()
 * @link 	http://codex.wordpress.org/Function_Reference/is_post_type_hierarchical		is_post_type_hierarchical()
 * @link 	http://codex.wordpress.org/Function_Reference/esc_attr						esc_attr()
 * @link 	http://codex.wordpress.org/Function_Reference/get_post						get_post()
 * @link 	http://codex.wordpress.org/Function_Reference/get_posts						get_posts()
 * @link 	http://codex.wordpress.org/Function_Reference/submit_button					submit_button()
 * @link 	http://codex.wordpress.org/Function_Reference/is_wp_error					is_wp_error()
 * @link 	http://codex.wordpress.org/Function_Reference/get_option					get_option()
 *
 * @uses  class Master_Walker_Sidebar_Checklist 	defined in includes/classes/class-master-walker-sidebar-checklist.php
 * 
 * @global $_nav_menu_placeholder
 * @global $nav_menu_selected_id
 * @param string $object Not used.
 * @param string $post_type The post type object.
 *
 * @since 1.0
 * @version 1.4
 */
function master_sidebar_item_post_type_meta_box( $object, $post_type ) {
	global $_nav_menu_placeholder, $nav_menu_selected_id;

	$post_type_name = $post_type['args']->name;

	// paginate browsing for large numbers of post objects
	$per_page = 50;
	$pagenum  = isset( $_REQUEST[$post_type_name . '-tab'] ) && isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
	$offset   = 0 < $pagenum ? $per_page * ( $pagenum - 1 ) : 0;

	$args = array(
		'offset'                 => $offset,
		'order'                  => 'ASC',
		'orderby'                => 'title',
		'posts_per_page'         => $per_page,
		'post_type'              => $post_type_name,
		'suppress_filters'       => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false
	);

	if ( isset( $post_type['args']->_default_query ) )
		$args = array_merge($args, (array) $post_type['args']->_default_query );

	// @todo transient caching of these results with proper invalidation on updating of a post of this type
	$get_posts = new WP_Query;
	$posts     = $get_posts->query( $args );

	if ( ! $get_posts->post_count ) {
		echo '<p>' . __( 'No items.', 'theme-translate' ) . '</p>';
		return;
	}

	$post_type_object = get_post_type_object( $post_type_name );
	$num_pages        = $get_posts->max_num_pages;

	$page_links = paginate_links( array(
		'base' => add_query_arg(
			array(
				$post_type_name . '-tab' => 'all',
				'paged'                  => '%#%',
				'item-type'              => 'post_type',
				'item-object'            => $post_type_name,
			)
		),
		'format'    => '',
		'prev_text' => __('&laquo;'),
		'next_text' => __('&raquo;'),
		'total'     => $num_pages,
		'current'   => $pagenum
	));

	if ( !$posts )
		$error = '<li id="error">'. $post_type['args']->labels->not_found .'</li>';

	$db_fields = false;
	if ( is_post_type_hierarchical( $post_type_name ) ) {
		$db_fields = array( 'parent' => 'post_parent', 'id' => 'ID' );
	}

	$walker = new Master_Walker_Sidebar_Checklist( $db_fields );

	$current_tab = 'most-recent';
	if ( isset( $_REQUEST[$post_type_name . '-tab'] ) && in_array( $_REQUEST[$post_type_name . '-tab'], array('all', 'search') ) ) {
		$current_tab = $_REQUEST[$post_type_name . '-tab'];
	}

	if ( ! empty( $_REQUEST['quick-search-posttype-' . $post_type_name] ) ) {
		$current_tab = 'search';
	}

	$removed_args = array(
		'action',
		'customlink-tab',
		'edit-menu-item',
		'menu-item',
		'page-tab',
		'_wpnonce',
	);

	?>
	<div id="posttype-<?php echo $post_type_name; ?>" class="posttypediv">
		<ul id="posttype-<?php echo $post_type_name; ?>-tabs" class="posttype-tabs add-menu-item-tabs">
			<li <?php echo ( 'most-recent' == $current_tab ? ' class="tabs"' : '' ); ?>><a class="nav-tab-link" href="<?php if ( $nav_menu_selected_id ) echo esc_url(add_query_arg($post_type_name . '-tab', 'most-recent', remove_query_arg($removed_args))); ?>#tabs-panel-posttype-<?php echo $post_type_name; ?>-most-recent"><?php _e('Most Recent'); ?></a></li>
			<li <?php echo ( 'all' == $current_tab ? ' class="tabs"' : '' ); ?>><a class="nav-tab-link" href="<?php if ( $nav_menu_selected_id ) echo esc_url(add_query_arg($post_type_name . '-tab', 'all', remove_query_arg($removed_args))); ?>#<?php echo $post_type_name; ?>-all"><?php _e('View All'); ?></a></li>
			<li <?php echo ( 'search' == $current_tab ? ' class="tabs"' : '' ); ?>><a class="nav-tab-link" href="<?php if ( $nav_menu_selected_id ) echo esc_url(add_query_arg($post_type_name . '-tab', 'search', remove_query_arg($removed_args))); ?>#tabs-panel-posttype-<?php echo $post_type_name; ?>-search"><?php _e('Search'); ?></a></li>
		</ul>

		<div id="tabs-panel-posttype-<?php echo $post_type_name; ?>-most-recent" class="tabs-panel <?php
			echo ( 'most-recent' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
		?>">
			<ul id="<?php echo $post_type_name; ?>checklist-most-recent" class="categorychecklist form-no-clear">
				
				<!-- All Post Type Checkbox -->
				<li>
					<label class="menu-item-title">
						<input type="checkbox" value="1" name="menu-item[-1111][menu-item-object-id]" class="menu-item-checkbox">
						<strong><?php echo sprintf( __( 'All %s', 'theme-translate' ), $post_type_object->labels->name ); ?></strong>
					</label>
					<input class="menu-item-db-id" type="hidden" value="0" name="menu-item[-1111][menu-item-db-id]">
					<input class="menu-item-object" type="hidden" value="<?php echo $post_type_object->name; ?>" name="menu-item[-1111][menu-item-object]">
					<input class="menu-item-parent-id" type="hidden" value="0" name="menu-item[-1111][menu-item-parent-id]">
					<input class="menu-item-type" type="hidden" value="post_type_all" name="menu-item[-1111][menu-item-type]">
					<input class="menu-item-title" type="hidden" value="<?php echo sprintf( __( 'All %s', 'theme-translate' ), $post_type_object->labels->name ); ?>" name="menu-item[-1111][menu-item-title]">
				</li>

				<!-- Posttype Archive Checkbox -->
				<?php if ( 'post' != $post_type_object->name && 'page' != $post_type_object->name ) : ?>
					<li>
						<label class="menu-item-title">
							<input type="checkbox" value="1" name="menu-item[-1112][menu-item-object-id]" class="menu-item-checkbox">
							<strong><?php echo sprintf( __( '%s Archive', 'theme-translate' ), $post_type_object->labels->singular_name ); ?></strong>

						</label>
						<input class="menu-item-db-id" type="hidden" value="0" name="menu-item[-1112][menu-item-db-id]">
						<input class="menu-item-object" type="hidden" value="<?php echo $post_type_object->name; ?>" name="menu-item[-1112][menu-item-object]">
						<input class="menu-item-parent-id" type="hidden" value="0" name="menu-item[-1112][menu-item-parent-id]">
						<input class="menu-item-type" type="hidden" value="post_type_archive" name="menu-item[-1112][menu-item-type]">
						<input class="menu-item-title" type="hidden" value="<?php echo sprintf( __( '%s Archive', 'theme-translate' ), $post_type_object->labels->singular_name ); ?>" name="menu-item[-1112][menu-item-title]">					
					</li>
				<?php endif; ?>

				<?php
				$recent_args = array_merge( $args, array( 'orderby' => 'post_date', 'order' => 'DESC', 'posts_per_page' => 15 ) );
				$most_recent = $get_posts->query( $recent_args );
				$args['walker'] = $walker;
				echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $most_recent), 0, (object) $args );
				?>
			</ul>
		</div><!-- /.tabs-panel -->

		<div class="tabs-panel <?php
			echo ( 'search' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
		?>" id="tabs-panel-posttype-<?php echo $post_type_name; ?>-search">
			<?php
			if ( isset( $_REQUEST['quick-search-posttype-' . $post_type_name] ) ) {
				$searched = esc_attr( $_REQUEST['quick-search-posttype-' . $post_type_name] );
				$search_results = get_posts( array( 's' => $searched, 'post_type' => $post_type_name, 'fields' => 'all', 'order' => 'DESC', ) );
			} else {
				$searched = '';
				$search_results = array();
			}
			?>
			<p class="quick-search-wrap">
				<input type="search" class="quick-search input-with-default-title" title="<?php esc_attr_e('Search'); ?>" value="<?php echo $searched; ?>" name="quick-search-posttype-<?php echo $post_type_name; ?>" />
				<span class="spinner"></span>
				<?php submit_button( __( 'Search' ), 'button-small quick-search-submit button-secondary hide-if-js', 'submit', false, array( 'id' => 'submit-quick-search-posttype-' . $post_type_name ) ); ?>
			</p>

			<ul id="<?php echo $post_type_name; ?>-search-checklist" data-wp-lists="list:<?php echo $post_type_name?>" class="categorychecklist form-no-clear">
			<?php if ( ! empty( $search_results ) && ! is_wp_error( $search_results ) ) : ?>
				<?php
				$args['walker'] = $walker;
				echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $search_results), 0, (object) $args );
				?>
			<?php elseif ( is_wp_error( $search_results ) ) : ?>
				<li><?php echo $search_results->get_error_message(); ?></li>
			<?php elseif ( ! empty( $searched ) ) : ?>
				<li><?php _e('No results found.'); ?></li>
			<?php endif; ?>
			</ul>
		</div><!-- /.tabs-panel -->

		<div id="<?php echo $post_type_name; ?>-all" class="tabs-panel tabs-panel-view-all <?php
			echo ( 'all' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
		?>">
			<?php if ( ! empty( $page_links ) ) : ?>
				<div class="add-menu-item-pagelinks">
					<?php echo $page_links; ?>
				</div>
			<?php endif; ?>
			<ul id="<?php echo $post_type_name; ?>checklist" data-wp-lists="list:<?php echo $post_type_name?>" class="categorychecklist form-no-clear">
				
				<!-- All Post Type Checkbox -->
				<li>
					<label class="menu-item-title">
						<input type="checkbox" value="1" name="menu-item[-1111][menu-item-object-id]" class="menu-item-checkbox">
						<strong><?php echo sprintf( __( 'All %s', 'theme-translate' ), $post_type_object->labels->name ); ?></strong>
					</label>
					<input class="menu-item-db-id" type="hidden" value="0" name="menu-item[-1111][menu-item-db-id]">
					<input class="menu-item-object" type="hidden" value="<?php echo $post_type_object->name; ?>" name="menu-item[-1111][menu-item-object]">
					<input class="menu-item-parent-id" type="hidden" value="0" name="menu-item[-1111][menu-item-parent-id]">
					<input class="menu-item-type" type="hidden" value="post_type_all" name="menu-item[-1111][menu-item-type]">
					<input class="menu-item-title" type="hidden" value="<?php echo sprintf( __( 'All %s', 'theme-translate' ), $post_type_object->labels->name ); ?>" name="menu-item[-1111][menu-item-title]">
				</li>

				<!-- Posttype Archive Checkbox -->
				<?php if ( 'post' != $post_type_object->name && 'page' != $post_type_object->name ) : ?>
					<li>
						<label class="menu-item-title">
							<input type="checkbox" value="1" name="menu-item[-1112][menu-item-object-id]" class="menu-item-checkbox">
							<strong><?php echo sprintf( __( '%s Archive', 'theme-translate' ), $post_type_object->labels->singular_name ); ?></strong>

						</label>
						<input class="menu-item-db-id" type="hidden" value="0" name="menu-item[-1112][menu-item-db-id]">
						<input class="menu-item-object" type="hidden" value="<?php echo $post_type_object->name; ?>" name="menu-item[-1112][menu-item-object]">
						<input class="menu-item-parent-id" type="hidden" value="0" name="menu-item[-1112][menu-item-parent-id]">
						<input class="menu-item-type" type="hidden" value="post_type_archive" name="menu-item[-1112][menu-item-type]">
						<input class="menu-item-title" type="hidden" value="<?php echo sprintf( __( '%s Archive', 'theme-translate' ), $post_type_object->labels->singular_name ); ?>" name="menu-item[-1112][menu-item-title]">					
					</li>
				<?php endif; ?>
				
				<?php
				$args['walker'] = $walker;

				// if we're dealing with pages, let's put a checkbox for the front page at the top of the list
				if ( 'page' == $post_type_name ) {
					$front_page = 'page' == get_option('show_on_front') ? (int) get_option( 'page_on_front' ) : 0;
					if ( ! empty( $front_page ) ) {
						$front_page_obj = get_post( $front_page );
						$front_page_obj->front_or_home = true;
						array_unshift( $posts, $front_page_obj );
					} else {
						$_nav_menu_placeholder = ( 0 > $_nav_menu_placeholder ) ? intval($_nav_menu_placeholder) - 1 : -1;
						array_unshift( $posts, (object) array(
							'front_or_home' => true,
							'ID'            => 0,
							'object_id'     => $_nav_menu_placeholder,
							'post_content'  => '',
							'post_excerpt'  => '',
							'post_parent'   => '',
							'post_title'    => _x('Home', 'nav menu home label'),
							'post_type'     => 'nav_menu_item',
							'type'          => 'custom',
							'url'           => home_url('/'),
						) );
					}
				}

				$posts = apply_filters( 'master_sidebar_items_'.$post_type_name, $posts, $args, $post_type );
				$checkbox_items = walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $posts), 0, (object) $args );

				if ( 'all' == $current_tab && ! empty( $_REQUEST['selectall'] ) ) {
					$checkbox_items = preg_replace('/(type=(.)checkbox(\2))/', '$1 checked=$2checked$2', $checkbox_items);

				}

				echo $checkbox_items;
				?>
			</ul>
			<?php if ( ! empty( $page_links ) ) : ?>
				<div class="add-menu-item-pagelinks">
					<?php echo $page_links; ?>
				</div>
			<?php endif; ?>
		</div><!-- /.tabs-panel -->

		<p class="button-controls">
			<span class="list-controls">
				<a href="<?php
					echo esc_url(add_query_arg(
						array(
							$post_type_name . '-tab' => 'all',
							'selectall' => 1,
						),
						remove_query_arg($removed_args)
					));
				?>#posttype-<?php echo $post_type_name; ?>" class="select-all"><?php _e( 'Select All', 'theme-translate' ); ?></a>
			</span>

			<span class="add-to-menu">
				<input type="submit"<?php disabled( $nav_menu_selected_id, 0 ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Sidebar', 'theme-translate' ); ?>" name="add-post-type-menu-item" id="submit-posttype-<?php echo $post_type_name; ?>" />
				<span class="spinner"></span>
			</span>
		</p>

	</div><!-- /.posttypediv -->
	<?php
}

/**
 * Retrieve All Taxonomy Metaboxes
 *
 * Gets all taxonomies that are currently registered
 * with the currently active WordPress theme and 
 * registers metaboxes for each taxonomy for use on
 * the Sidebar Admin Page.
 *
 * @link 	http://codex.wordpress.org/Function_Reference/get_taxonomies 	get_taxonomies()
 * @link 	http://codex.wordpress.org/Function_Reference/apply_filters 	apply_filters()
 * @link 	http://codex.wordpress.org/Function_Reference/add_meta_box 		add_meta_box()
 *
 * @uses 	master_sidebar_item_post_type_meta_box() 	defined in includes/theme-sidebar-admin-page-functions.php
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_sidebar_taxonomy_meta_boxes() {

	$taxonomies      = get_taxonomies( array( 'show_in_nav_menus' => true ), 'object' );
	$admin_page_name = 'appearance_page_custom_theme_sidebars';

	if ( ! $taxonomies ) {
		return;
	}

	foreach ( $taxonomies as $tax ) {
		$tax = apply_filters( 'master_sidebar_meta_box_object', $tax );
		if ( $tax ) {
			$id = $tax->name;
			add_meta_box( 
				"master-add-{$id}", 
				$tax->labels->name, 
				'master_sidebar_item_taxonomy_meta_box', 
				$admin_page_name, 
				'side', 
				'default', 
				$tax 
			);			
		}
	}
}

/**
 * Displays a metabox for a taxonomy menu item.
 *
 * This function outputs the sidebar checklist metabox
 * that is used on the admin page.
 * 
 * @link 	http://codex.wordpress.org/Function_Reference/apply_filters 				apply_filters()
 * @link 	http://codex.wordpress.org/Function_Reference/get_post_type_object			get_post_type_object()
 * @link 	http://codex.wordpress.org/Function_Reference/paginate_links				paginate_links()
 * @link 	http://codex.wordpress.org/Function_Reference/add_query_arg					add_query_arg()
 * @link 	http://codex.wordpress.org/Function_Reference/is_post_type_hierarchical		is_post_type_hierarchical()
 * @link 	http://codex.wordpress.org/Function_Reference/esc_attr						esc_attr()
 * @link 	http://codex.wordpress.org/Function_Reference/get_terms						get_terms()
 * @link 	http://codex.wordpress.org/Function_Reference/get_taxonomy					get_taxonomy()
 * @link 	http://codex.wordpress.org/Function_Reference/submit_button					submit_button()
 * @link 	http://codex.wordpress.org/Function_Reference/is_wp_error					is_wp_error()
 * @link 	http://codex.wordpress.org/Function_Reference/get_option					get_option()
 * @link 	http://codex.wordpress.org/Function_Reference/wp_count_terms					wp_count_terms()
 *
 * @global $nav_menu_selected_id
 * 
 * @uses  class Master_Walker_Sidebar_Checklist 	defined in includes/classes/class-master-walker-sidebar-checklist.php
 *
 * @param string $object Not used.
 * @param string $taxonomy The taxonomy object.
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_sidebar_item_taxonomy_meta_box( $object, $taxonomy ) {
	global $nav_menu_selected_id;
	$taxonomy_name = $taxonomy['args']->name;

	// paginate browsing for large numbers of objects
	$per_page = 50;
	$pagenum  = isset( $_REQUEST[$taxonomy_name . '-tab'] ) && isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
	$offset   = 0 < $pagenum ? $per_page * ( $pagenum - 1 ) : 0;

	$args = array(
		'child_of'     => 0,
		'exclude'      => '',
		'hide_empty'   => false,
		'hierarchical' => 1,
		'include'      => '',
		'number'       => $per_page,
		'offset'       => $offset,
		'order'        => 'ASC',
		'orderby'      => 'name',
		'pad_counts'   => false,
	);

	$terms = get_terms( $taxonomy_name, $args );
	$taxonomy_obj = get_taxonomy( $taxonomy_name );

	if ( ! $terms || is_wp_error($terms) ) {
		echo '<p>' . __( 'No items.' ) . '</p>';
		return;
	}

	$num_pages = ceil( wp_count_terms( $taxonomy_name , array_merge( $args, array('number' => '', 'offset' => '') ) ) / $per_page );

	$admin_url  = esc_url( 
					add_query_arg( 
						array( 
							'page' => 'custom_theme_sidebars' 
						), 
						admin_url( 'themes.php' ) 
					) 
				);

	$page_links = paginate_links( array(
		'base' => add_query_arg(
			array(
				$taxonomy_name . '-tab' => 'all',
				'paged'                 => '%#%',
				'item-type'             => 'taxonomy',
				'item-object'           => $taxonomy_name
			), $admin_url
		),
		'format'    => '',
		'prev_text' => __('&laquo;'),
		'next_text' => __('&raquo;'),
		'total'     => $num_pages,
		'current'   => $pagenum
	));

	$db_fields = false;
	if ( is_taxonomy_hierarchical( $taxonomy_name ) ) {
		$db_fields = array( 'parent' => 'parent', 'id' => 'term_id' );
	}

	$walker = new Master_Walker_Sidebar_Checklist( $db_fields );

	$current_tab = 'most-used';
	if ( isset( $_REQUEST[$taxonomy_name . '-tab'] ) && in_array( $_REQUEST[$taxonomy_name . '-tab'], array('all', 'most-used', 'search') ) ) {
		$current_tab = $_REQUEST[$taxonomy_name . '-tab'];
	}

	if ( ! empty( $_REQUEST['quick-search-taxonomy-' . $taxonomy_name] ) ) {
		$current_tab = 'search';
	}

	$removed_args = array(
		'action',
		'customlink-tab',
		'edit-menu-item',
		'menu-item',
		'page-tab',
		'_wpnonce',
	);

	?>
	<div id="taxonomy-<?php echo $taxonomy_name; ?>" class="taxonomydiv">
		<ul id="taxonomy-<?php echo $taxonomy_name; ?>-tabs" class="taxonomy-tabs add-menu-item-tabs">
			<li <?php echo ( 'most-used' == $current_tab ? ' class="tabs"' : '' ); ?>><a class="nav-tab-link" href="<?php if ( $nav_menu_selected_id ) echo esc_url(add_query_arg($taxonomy_name . '-tab', 'most-used', remove_query_arg($removed_args))); ?>#tabs-panel-<?php echo $taxonomy_name; ?>-pop"><?php _e('Most Used'); ?></a></li>
			<li <?php echo ( 'all' == $current_tab ? ' class="tabs"' : '' ); ?>><a class="nav-tab-link" href="<?php if ( $nav_menu_selected_id ) echo esc_url(add_query_arg($taxonomy_name . '-tab', 'all', remove_query_arg($removed_args))); ?>#tabs-panel-<?php echo $taxonomy_name; ?>-all"><?php _e('View All'); ?></a></li>
			<li <?php echo ( 'search' == $current_tab ? ' class="tabs"' : '' ); ?>><a class="nav-tab-link" href="<?php if ( $nav_menu_selected_id ) echo esc_url(add_query_arg($taxonomy_name . '-tab', 'search', remove_query_arg($removed_args))); ?>#tabs-panel-search-taxonomy-<?php echo $taxonomy_name; ?>"><?php _e('Search'); ?></a></li>
		</ul>

		<div id="tabs-panel-<?php echo $taxonomy_name; ?>-pop" class="tabs-panel <?php
			echo ( 'most-used' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
		?>">
			<ul id="<?php echo $taxonomy_name; ?>checklist-pop" class="categorychecklist form-no-clear" >
				<!-- All Taxonomies Checkbox -->
				<li>
					<label class="menu-item-title">
						<input type="checkbox" value="1" name="menu-item[-1111][menu-item-object-id]" class="menu-item-checkbox">
						<strong><?php echo sprintf( __( 'All %s', 'theme-translate' ), $taxonomy_obj->labels->name ); ?></strong>
					</label>
					<input class="menu-item-db-id" type="hidden" value="0" name="menu-item[-1111][menu-item-db-id]">
					<input class="menu-item-object" type="hidden" value="<?php echo $taxonomy_obj->name; ?>" name="menu-item[-1111][menu-item-object]">
					<input class="menu-item-parent-id" type="hidden" value="0" name="menu-item[-1111][menu-item-parent-id]">
					<input class="menu-item-type" type="hidden" value="taxonomy_all" name="menu-item[-1111][menu-item-type]">
					<input class="menu-item-title" type="hidden" value="<?php echo sprintf( __( 'All %s', 'theme-translate' ), $taxonomy_obj->labels->name ); ?>" name="menu-item[-1111][menu-item-title]">
				</li>

				<?php
				$popular_terms = get_terms( $taxonomy_name, array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hierarchical' => false ) );
				$args['walker'] = $walker;
				echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $popular_terms), 0, (object) $args );
				?>
			</ul>
		</div><!-- /.tabs-panel -->

		<div id="tabs-panel-<?php echo $taxonomy_name; ?>-all" class="tabs-panel tabs-panel-view-all <?php
			echo ( 'all' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
		?>">
			<?php if ( ! empty( $page_links ) ) : ?>
				<div class="add-menu-item-pagelinks">
					<?php echo $page_links; ?>
				</div>
			<?php endif; ?>
			<ul id="<?php echo $taxonomy_name; ?>checklist" data-wp-lists="list:<?php echo $taxonomy_name?>" class="categorychecklist form-no-clear">
				<!-- All Taxonomies Checkbox -->
				<li>
					<label class="menu-item-title">
						<input type="checkbox" value="1" name="menu-item[-1111][menu-item-object-id]" class="menu-item-checkbox">
						<strong><?php echo sprintf( __( 'All %s', 'theme-translate' ), $taxonomy_obj->labels->name ); ?></strong>
					</label>
					<input class="menu-item-db-id" type="hidden" value="0" name="menu-item[-1111][menu-item-db-id]">
					<input class="menu-item-object" type="hidden" value="<?php echo $taxonomy_obj->name; ?>" name="menu-item[-1111][menu-item-object]">
					<input class="menu-item-parent-id" type="hidden" value="0" name="menu-item[-1111][menu-item-parent-id]">
					<input class="menu-item-type" type="hidden" value="taxonomy_all" name="menu-item[-1111][menu-item-type]">
					<input class="menu-item-title" type="hidden" value="<?php echo sprintf( __( 'All %s', 'theme-translate' ), $taxonomy_obj->labels->name ); ?>">
				</li>
				<?php
				$args['walker'] = $walker;
				echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $terms), 0, (object) $args );
				?>
			</ul>
			<?php if ( ! empty( $page_links ) ) : ?>
				<div class="add-menu-item-pagelinks">
					<?php echo $page_links; ?>
				</div>
			<?php endif; ?>
		</div><!-- /.tabs-panel -->

		<div class="tabs-panel <?php
			echo ( 'search' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
		?>" id="tabs-panel-search-taxonomy-<?php echo $taxonomy_name; ?>">
			<?php
			if ( isset( $_REQUEST['quick-search-taxonomy-' . $taxonomy_name] ) ) {
				$searched = esc_attr( $_REQUEST['quick-search-taxonomy-' . $taxonomy_name] );
				$search_results = get_terms( $taxonomy_name, array( 'name__like' => $searched, 'fields' => 'all', 'orderby' => 'count', 'order' => 'DESC', 'hierarchical' => false ) );
			} else {
				$searched = '';
				$search_results = array();
			}
			?>
			<p class="quick-search-wrap">
				<input type="search" class="quick-search input-with-default-title" title="<?php esc_attr_e('Search'); ?>" value="<?php echo $searched; ?>" name="quick-search-taxonomy-<?php echo $taxonomy_name; ?>" />
				<span class="spinner"></span>
				<?php submit_button( __( 'Search' ), 'button-small quick-search-submit button-secondary hide-if-js', 'submit', false, array( 'id' => 'submit-quick-search-taxonomy-' . $taxonomy_name ) ); ?>
			</p>

			<ul id="<?php echo $taxonomy_name; ?>-search-checklist" data-wp-lists="list:<?php echo $taxonomy_name?>" class="categorychecklist form-no-clear">
			<?php if ( ! empty( $search_results ) && ! is_wp_error( $search_results ) ) : ?>
				<?php
				$args['walker'] = $walker;
				echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $search_results), 0, (object) $args );
				?>
			<?php elseif ( is_wp_error( $search_results ) ) : ?>
				<li><?php echo $search_results->get_error_message(); ?></li>
			<?php elseif ( ! empty( $searched ) ) : ?>
				<li><?php _e('No results found.'); ?></li>
			<?php endif; ?>
			</ul>
		</div><!-- /.tabs-panel -->

		<p class="button-controls">
			<span class="list-controls">
				<a href="<?php
					echo esc_url(add_query_arg(
						array(
							$taxonomy_name . '-tab' => 'all',
							'selectall' => 1,
						),
						remove_query_arg($removed_args)
					));
				?>#taxonomy-<?php echo $taxonomy_name; ?>" class="select-all"><?php _e( 'Select All', 'theme-translate' ); ?></a>
			</span>

			<span class="add-to-menu">
				<input type="submit"<?php disabled( $nav_menu_selected_id, 0 ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Sidebar', 'theme-translate' ); ?>" name="add-taxonomy-menu-item" id="submit-taxonomy-<?php echo $taxonomy_name; ?>" />
				<span class="spinner"></span>
			</span>
		</p>

	</div><!-- /.taxonomydiv -->
	<?php
}

/**
 * Register Template Author Metabox
 *
 * Registers the custom metabox that is added in order
 * to cater for WordPress author archive templates.
 *
 * @link 	http://codex.wordpress.org/Function_Reference/add_meta_box 		add_meta_box()
 * 
 * @since 1.1
 * @version 1.4
 * 
 */
function master_sidebar_author_meta_box() {

	$admin_page_name = 'appearance_page_custom_theme_sidebars';

	add_meta_box( 
		'master-add-author-archive-metabox', 
		__('Author Archives'), 
		'master_sidebar_render_author_meta_box', 
		$admin_page_name, 
		'side', 
		'default'
	);
}

/**
 * Render Author Archive Metabox
 *
 * This function outputs the sidebar checklist metabox
 * that is used in the Author Archive metabox on the 
 * admin page.
 * 
 * @link 	http://codex.wordpress.org/Function_Reference/get_users 				get_users()
 * @link 	http://codex.wordpress.org/Function_Reference/esc_url					esc_url()
 * @link 	http://codex.wordpress.org/Function_Reference/admin_url					admin_url()
 * @link 	http://codex.wordpress.org/Function_Reference/add_query_arg				add_query_arg()
 *
 * @global $nav_menu_selected_id
 * 
 * @uses  class Master_Walker_Sidebar_Checklist 	defined in includes/classes/class-master-walker-sidebar-checklist.php
 *
 * @param string $object Not used.
 * @param string $taxonomy The taxonomy object.
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_sidebar_render_author_meta_box() {
	
	global $nav_menu_selected_id;
	

	// Paginate browsing for large numbers of objects
	$per_page = 50;

	// Check if any variables have been passed in the URL
	$pagenum  = isset( $_REQUEST['custom-item-type'] ) && isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
	$offset   = 0 < $pagenum ? $per_page * ( $pagenum - 1 ) : 0;

	// Define query args for author query 
	$roles = array( 'Administrator', 'Editor', 'Author' ); 
	
	$all_authors = array();

	// Get all users that have author privaleges 
	foreach ( $roles as $role ) {
		$args = array(
			'role'        => $role,
			'orderby'     => 'display_name',
			'order'       => 'ASC',
			'offset'      => '',
			'search'      => '',
			'number'      => '',
			'count_total' => false,
			'fields'      => 'all',
			'who'         => ''
		);

		$authors = get_users( $args );

		if ( is_array( $authors ) ) {
			$all_authors = array_merge( $all_authors, $authors );
		}
	}
	
	// Display feedback message if there are no categories
	if ( ! $all_authors || is_null( $all_authors ) ) {
		echo '<p>' . __( 'No items.', 'theme-translate' ) . '</p>';
		return;
	}

	// Determine number of pages
	$num_pages = count( $all_authors ) / $per_page;

	// Define admin page url
	$admin_url  = esc_url( 
					add_query_arg( 
						array( 
							'page' => 'custom_theme_sidebars' 
						), 
						admin_url( 'themes.php' ) 
					) 
				);

	// Generate pagination
	$page_links = paginate_links( array(
		'base' => add_query_arg(
			array(
				'author-tab' => 'all',
				'paged'                 => '%#%',
				'item-type'             => 'author_archive',
				'item-object'           => 'author_archive',
				'custom-item-type'		=> 'author_archive',
			), $admin_url
		),
		'format'    => '',
		'prev_text' => __('&laquo;'),
		'next_text' => __('&raquo;'),
		'total'     => $num_pages,
		'current'   => $pagenum
	) );

	$db_fields = false;

	// Define our own custom walker
	$walker = new Master_Walker_Sidebar_Checklist( $db_fields );

	// Determine the current tab to use
	$current_tab = 'all';
	if ( isset( $_REQUEST['author-tab'] ) && in_array( $_REQUEST['author-tab'], array('all', 'most-used', 'search') ) ) {
		$current_tab = $_REQUEST['author-tab'];
	}

	if ( ! empty( $_REQUEST['quick-search-author-custom-author'] ) ) {
		$current_tab = 'search';
	}

	$removed_args = array(
		'action',
		'customlink-tab',
		'edit-menu-item',
		'menu-item',
		'page-tab',
		'_wpnonce',
	);

	?>
	<div id="master-author-archive" class="taxonomydiv">
		
		<!-- Tab Panel Tabs -->
		<ul id="author-archive-tabs" class="author-tabs add-menu-item-tabs">
			<li <?php echo ( 'all' == $current_tab ? ' class="tabs"' : '' ); ?>>
				<a href="#tabs-panel-master-author-archive-all" class="nav-tab-link">
					<?php _e('View All'); ?>
				</a>
			</li>
			<li <?php echo ( 'search' == $current_tab ? ' class="tabs"' : '' ); ?>>
				<a href="#tabs-panel-master-author-archive-search" class="nav-tab-link">
					<?php _e('Search'); ?>
				</a>
			</li>
		</ul>
		
		<!-- Tab Panel All -->
		<div id="tabs-panel-master-author-archive-all" class="tabs-panel tabs-panel-view-all <?php
			echo ( 'all' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
		?>">
			<?php if ( ! empty( $page_links ) ) : ?>
				<div class="add-menu-item-pagelinks">
					<?php echo $page_links; ?>
				</div>
			<?php endif; ?>
			<ul id="author-checklist" data-wp-lists="list:author" class="categorychecklist form-no-clear">
				<?php
					// Use the custom walker
					$args['walker'] = $walker;

					// Set custom array index to indicate a custom data type
					$args['custom_item_type'] = 'author_archive';	

					$db_id = -1222;

					$author_count = 0;
					$total_count  = 0;
				?>
				<?php foreach ( $all_authors as $author ) : ?>
					<?php if ( $offset == $author_count && $total_count < $per_page ) : ?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" value ="1" name="menu-item[<?php echo $db_id; ?>][menu-item-object-id]" class="menu-item-checkbox"> <?php echo $author->display_name; ?>
							</label>
							<input class="menu-item-db-id" type="hidden" value="0" name="menu-item[<?php echo $db_id; ?>][menu-item-db-id]">
							<input class="menu-item-object" type="hidden" value="<?php echo $author->ID; ?>" name="menu-item[<?php echo $db_id; ?>][menu-item-object]">
							<input class="menu-item-parent-id" type="hidden" value="0" name="menu-item[<?php echo $db_id; ?>][menu-item-parent-id]">
							<input class="menu-item-type" type="hidden" value="author_archive" name="menu-item[<?php echo $db_id; ?>][menu-item-type]">
							<input class="menu-item-title" type="hidden" value="<?php echo $author->display_name; ?>" name="menu-item[<?php echo $db_id; ?>][menu-item-title]">
							<input class="menu-item-url" type="hidden" value="<?php echo $author->user_url; ?>" name="menu-item[<?php echo $db_id; ?>][menu-item-url]">
						</li>
						<?php $total_count++; ?>
					<?php else : ?>
						<?php $author_count++; ?>
					<?php endif; ?>
				<?php $db_id++; endforeach; ?>

			</ul>
			<?php if ( ! empty( $page_links ) ) : ?>
				<div class="add-menu-item-pagelinks">
					<?php echo $page_links; ?>
				</div>
			<?php endif; ?>
		</div><!-- /.tabs-panel -->
		
		<!-- Tab Panel Search -->
		<div class="tabs-panel <?php
			echo ( 'search' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' );
		?>" id="tabs-panel-master-author-archive-search">
			<?php 
				$searched = '';
				$search_results = array();
			 ?>

			<p class="quick-search-wrap">
				<input type="search" class="quick-search input-with-default-title" title="<?php esc_attr_e('Search'); ?>" value="<?php echo $searched; ?>" name="quick-search-author-archive" />
				<span class="spinner"></span>
				<?php submit_button( __( 'Search' ), 'button-small quick-search-submit button-secondary hide-if-js', 'submit', false, array( 'id' => 'submit-quick-search-author-archive' ) ); ?>
			</p>
			<ul id="author-archive-search-checklist" data-wp-lists="list:author-archive" class="categorychecklist form-no-clear">
			</ul>

		</div><!-- /.tabs-panel -->

		<p class="button-controls">
			<span class="list-controls">
				<a href="#" class="select-all"><?php _e( 'Select All', 'theme-translate' ); ?></a>
			</span>

			<span class="add-to-menu">
				<input type="submit"<?php disabled( $nav_menu_selected_id, 0 ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Sidebar', 'theme-translate' ); ?>" name="add-author-archive-item" id="submit-master-author-archive" />
				<span class="spinner"></span>
			</span>
		</p>
	</div>
	<?php
}

/**
 * Register Template Hierachy Metabox
 *
 * Registers the custom metabox that is added in order
 * to cater for WordPress templates that are not either
 * a post type or taxonomy.
 *
 * @link 	http://codex.wordpress.org/Function_Reference/add_meta_box 		add_meta_box()
 *
 * @uses 	master_sidebar_item_post_type_meta_box() 	defined in includes/theme-sidebar-admin-page-functions.php
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_sidebar_template_hierarchy_meta_box() {
	
	$admin_page_name = 'appearance_page_custom_theme_sidebars';
	
	add_meta_box( 
		'master-add-page-hierachy', 
		__('Template Hierarchy'), 
		'master_sidebar_render_template_hierarchy_meta_box', 
		$admin_page_name, 
		'side', 
		'default'
	);
}

/**
 * Render Template Hierachy Metabox
 *
 * This function generates and outputs the required html
 * markup required for the Template Hierarchy metabox that
 * is displayed on the Admin Page.
 *
 * @link 	http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link 	http://codex.wordpress.org/Function_Reference/wp_get_theme 			wp_get_theme()
 * @link 	http://codex.wordpress.org/Function_Reference/get_page_templates 	get_page_templates()
 * 
 * @since 1.0
 * @version 1.4
 * 
 */
function master_sidebar_render_template_hierarchy_meta_box() {

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) )
		wp_die( -1 );

	// Create array to hold template hierachy items
	$template_items   = array();
	$checklist_output = '';
	$db_id = -1111;

	// Add 404 page
	$template_items[] = array(
		'menu-item-db-id'     => $db_id,
		'menu-item-title'     => __( '404 - Page Not Found', 'theme-translate' ),
		'menu-item-object'    => '404',
		'menu-item-parent-id' => $db_id,
		'menu-item-type'      => 'template_hierarchy',
		'menu-item-url'       => '#'	
	);
	$db_id++;

	// Add author archive
	$template_items[] = array(
		'menu-item-db-id'     => $db_id,
		'menu-item-title'     => __( 'Author Archive', 'theme-translate' ),
		'menu-item-object'    => 'author_archive_all',
		'menu-item-parent-id' => $db_id,
		'menu-item-type'      => 'template_hierarchy',
		'menu-item-url'       => '#'
	);
	$db_id++;
	
	// Add index page
	$template_items[] = array(
		'menu-item-db-id'     => $db_id,
		'menu-item-title'     => __( 'Blog Index Page', 'theme-translate' ),
		'menu-item-object'    => 'index_page',
		'menu-item-parent-id' => $db_id,
		'menu-item-type'      => 'template_hierarchy',
		'menu-item-url'       => '#'
	);
	$db_id++;

	// Add date archive
	$template_items[] = array(
		'menu-item-db-id'     => $db_id,
		'menu-item-title'     => __( 'Date Archive', 'theme-translate' ),
		'menu-item-object'    => 'date_archive',
		'menu-item-parent-id' => $db_id,
		'menu-item-type'      => 'template_hierarchy',
		'menu-item-url'       => '#'
	);
	$db_id++;

	// Add page templates 
	$page_templates = wp_get_theme()->get_page_templates();

	foreach ( $page_templates as $template_name => $template_filename ) {
		//echo "$template_name ($template_filename)<br />";
		$template_items[] = array(
			'menu-item-db-id'     => $db_id,
			'menu-item-title'     => sprintf( __( 'Page Template: %s', 'theme-translate' ), $template_filename ),
			'menu-item-object'    => 'page-template',
			'menu-item-parent-id' => $db_id,
			'menu-item-type'      => 'template_hierarchy',
			'menu-item-url'       => '#'				
		);
		$db_id++;
	}

	// Add index page
	$template_items[] = array(
		'menu-item-db-id'     => $db_id,
		'menu-item-title'     => __( 'Search Results', 'theme-translate' ),
		'menu-item-object'    => 'search_results',
		'menu-item-parent-id' => $db_id,
		'menu-item-type'      => 'template_hierarchy',
		'menu-item-url'       => '#'
	);
	$db_id++;

	?>
	<div id="master-page-hierachy" class="posttypediv">
		
		<!-- Tabs -->
		<ul id="template-hierarchy-tabs" class="posttype-tabs add-menu-item-tabs">
			<li class="tabs"><a href="#tabs-panel-post_tag-all" class="nav-tab-link"><?php _e( 'View All', 'theme-translate' ); ?></a></li>
		</ul><!-- END #template-hierarchy-tabs -->

		<!-- Panels -->
		<div id="master-page-hierachy" class="tabs-panel tabs-panel-view-all tabs-panel-active">
			
			<ul class="categorychecklist form-no-clear" data-wp-lists="list:testimonials" id="testimonialschecklist">
				<!-- All Post Type Checkbox -->
				<?php foreach ( $template_items as $template_item ) : ?>
					<li>
						<label class="menu-item-title">
							<input type="checkbox" value ="1" name="menu-item[<?php echo $template_item['menu-item-db-id']; ?>][menu-item-object-id]" class="menu-item-checkbox"> <?php echo $template_item['menu-item-title']; ?>
						</label>
						<input class="menu-item-db-id" type="hidden" value="0" name="menu-item[<?php echo $template_item['menu-item-db-id']; ?>][menu-item-db-id]">
						<input class="menu-item-object" type="hidden" value="<?php echo $template_item['menu-item-object']; ?>" name="menu-item[<?php echo $template_item['menu-item-db-id']; ?>][menu-item-object]">
						<input class="menu-item-parent-id" type="hidden" value="0" name="menu-item[<?php echo $template_item['menu-item-db-id']; ?>][menu-item-parent-id]">
						<input class="menu-item-type" type="hidden" value="<?php echo $template_item['menu-item-type']; ?>" name="menu-item[<?php echo $template_item['menu-item-db-id']; ?>][menu-item-type]">
						<input class="menu-item-title" type="hidden" value="<?php echo $template_item['menu-item-title']; ?>" name="menu-item[<?php echo $template_item['menu-item-db-id']; ?>][menu-item-title]">
						<input class="menu-item-url" type="hidden" value="<?php echo $template_item['menu-item-url']; ?>" name="menu-item[<?php echo $template_item['menu-item-db-id']; ?>][menu-item-url]">
					</li>
				<?php endforeach; ?>
			</ul>
		</div><!-- END .tabs-panel -->

		<p class="button-controls">
			<span class="list-controls">
				<a href="#" class="select-all"><?php _e( 'Select All', 'theme-translate' ); ?></a>
			</span>

			<span class="add-to-menu">
				<input type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Sidebar', 'theme-translate' ); ?>" name="add-page-heirachy-item" id="master-submit-page-hierachy" />
				<span class="spinner"></span>
			</span>
		</p>

	</div><!-- END #template-hierarchy -->

	<?php
}

/**
 * Get HTML Sidebar Attachment Data Output
 *
 * This function is responsible for generating and 
 * outputting the html list item markup on the admin
 * page which is used to display existing attachments.
 * This funciton requires nav-menu.php functions.  
 *
 * @link 	http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link 	http://codex.wordpress.org/Function_Reference/wp_die 				wp_die()
 * @link 	http://codex.wordpress.org/Function_Reference/get_post 				get_post()
 * @link 	http://codex.wordpress.org/Function_Reference/get_post_meta 		get_post_meta()
 * @link 	http://codex.wordpress.org/Function_Reference/apply_filters 		apply_filters()
 *
 * @uses master_get_sidebar_instance() 	defined in includes/theme-sidebar-functions.php
 * @uses master_sidebar_author_quick_search()
 * 
 * @since  1.0
 * @version 1.4
 * 
 */
function master_get_sidebar_attachment_markup( $sidebar_id ) {

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) )
		wp_die( -1 );
	
	$sidebar             = master_get_sidebar_instance( $sidebar_id );
	$sidebar_attachments = array();
	$sidebar_data        = array();
	$output              = '';

	if ( $sidebar ) {

		// Get sidebar attachment data
		$sidebar_attachments = get_post_meta( $sidebar->ID, 'sidebar_attachments', true );

		// Build sidebar data
		foreach ( $sidebar_attachments as $attachment ) {

			// Check what type of object we are working with
			if ( 
				! empty( $attachment['menu-item-type'] )      &&
				! empty( $attachment['menu-item-object-id'] ) &&
				'custom' != $attachment['menu-item-type'] 
			) {
				switch ( $attachment['menu-item-type'] ) {
					case 'post_type':
						$_object = get_post( $attachment['menu-item-object-id'] );
						// unset( $attachment['menu-item-title'] );
						break;
					
					case 'taxonomy':
						$_object = get_term( $attachment['menu-item-object-id'], $attachment['menu-item-object'] );
						//unset( $attachment['menu-item-title'] );
						break;
				} // end switch

				// Prepare object if it exists
				if ( $attachment['menu-item-type'] == 'post_type' || $attachment['menu-item-type'] == 'taxonomy') {
					$sidebar_items = array_map( 'wp_setup_nav_menu_item', array( $_object ) );
					$sidebar_item  = array_shift( $sidebar_items );
				}
			}

			$sidebar_data[] = $attachment;
		}

		$sidebar_ids = wp_save_nav_menu_items( 0, $sidebar_data );

		if ( is_wp_error( $sidebar_ids ) ) {
			wp_die( 0 );
		}

		$sidebar_items = array();

		foreach ( $sidebar_ids as $sidebar_item_id ) {
			$menu_obj = get_post( $sidebar_item_id );
			if ( ! empty( $menu_obj->ID ) ) {
				$menu_obj        = wp_setup_nav_menu_item( $menu_obj );
				$menu_obj->label = $menu_obj->title; // don't show "(pending)" in ajax-added items
				$sidebar_items[] = $menu_obj;
			}			
		}

		$walker_class_name = apply_filters( 'master_edit_sidebar_walker', 'Master_Walker_Sidebar_Edit', $sidebar_attachments );

		if ( ! class_exists( $walker_class_name ) ) {
			wp_die( 0 );
		}
		
		if ( ! empty( $sidebar_items ) ) {
			$args = array(
				'after'       => '',
				'before'      => '',
				'link_after'  => '',
				'link_before' => '',
				'walker'      => new $walker_class_name,
				'pending'     => false
			);

			$output .= walk_nav_menu_tree( $sidebar_items, 0, (object) $args );
		}		

	} // end if

	return $output;
}

/**
 * Create Accordion Section in Admin Sidebar UI
 *
 * This function is responsible for generating and 
 * outputting the accordion used to display all pages,
 * posts, taxonomies and posttypes in the admin options
 * page for custom sidebars. 
 * 
 * @link 	http://codex.wordpress.org/Function_Reference/get_current_screen 		get_current_screen()
 *
 * @global $wp_meta_boxes
 * @uses master_setup_sidebar_metaboxes() 	defined in includes/theme-sidebar-admin-page-functions.php
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_do_accordion_sections() {
	master_setup_sidebar_metaboxes();

	global $wp_meta_boxes;

	$screen  = get_current_screen();
	$page    = $screen->id;
	$context = 'side';
	$object  = null;
	$hidden  = get_hidden_meta_boxes( $screen );

	?>
	<div id="side-sortables" class="accordion-container">
		<ul class="outer-border">

	<?php
	$i = 0;
	do {
		if ( ! isset( $wp_meta_boxes ) || ! isset( $wp_meta_boxes[$page] ) || ! isset( $wp_meta_boxes[$page][$context] ) )
			break;
		foreach ( array( 'high', 'sorted', 'core', 'default', 'low' ) as $priority ) {
			if ( isset( $wp_meta_boxes[$page][$context][$priority] ) ) {
				foreach ( $wp_meta_boxes[$page][$context][$priority] as $box ) {
					$i++;
					$hidden_class = in_array( $box['id'], $hidden ) ? 'hidden' : 'visible';
					?>
					<li class="control-section accordion-section <?php echo $hidden_class; ?> <?php echo esc_attr( $box['id'] ); ?>" id="<?php echo esc_attr( $box['id'] ); ?>">
						<h3 class="accordion-section-title hndle" tabindex="0" title="<?php echo esc_attr( $box['title'] ); ?>"><?php echo esc_html( $box['title'] ); ?></h3>
						<div class="accordion-section-content <?php postbox_classes( $box['id'], $page ); ?>">
							<div class="inside">
								<?php call_user_func( $box['callback'], $object, $box ); ?>
							</div><!-- .inside -->
						</div><!-- .accordion-section-content -->
					</li><!-- .accordion-section -->
					<?php
				}
			}
		}
	} while(0);
	?>
		</ul><!-- .outer-border -->
	</div><!-- .accordion-container -->
	<?php
	return $i;
}

/**
 * Genereate Quick Search Response
 *
 * Takes the user input as an array parameter and generates
 * a list of posts/taxonomies based on the result.
 *
 * @link 	http://codex.wordpress.org/Function_Reference/post_type_exists 			post_type_exists()
 * @link 	http://codex.wordpress.org/Function_Reference/get_post 					get_post()
 * @link 	http://codex.wordpress.org/Function_Reference/get_post_type 			get_post_type()
 * @link 	http://codex.wordpress.org/Function_Reference/get_the_title 			get_the_title()
 * @link 	http://codex.wordpress.org/Function_Reference/taxonomy_exists 			taxonomy_exists()
 * @link 	http://codex.wordpress.org/Function_Reference/get_term 					get_term()
 * @link 	http://codex.wordpress.org/Function_Reference/get_terms					get_terms()
 * @link 	http://codex.wordpress.org/Function_Reference/get_post_type_object		get_post_type_object()
 * @link 	http://codex.wordpress.org/Function_Reference/get_the_ID 				get_the_ID()
 * @link 	http://codex.wordpress.org/Function_Reference/get_the_title				get_the_title()
 * @link 	http://codex.wordpress.org/Function_Reference/have_posts				have_posts()
 * @link 	http://codex.wordpress.org/Function_Reference/the_post					the_post()
 * 
 * @uses 	class Master_Walker_Sidebar_Checklist 	defined in includes/classes/class-master-walker-sidebar-edit.php
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_sidebar_quick_search( $request = array() ) {

	$args            = array();
	$type            = isset( $request['type'] ) ? $request['type'] : '';
	$object_type     = isset( $request['object_type'] ) ? $request['object_type'] : '';
	$query           = isset( $request['q'] ) ? $request['q'] : '';
	$response_format = isset( $request['response-format'] ) && in_array( $request['response-format'], array( 'json', 'markup' ) ) ? $request['response-format'] : 'json';

	/**
	 * Change $type if it is a custom category metabox 
	 * that shows the All Posts In Category items.
	 */
	if ( 'quick-search-taxonomy-category-custom-category' == $type ) {
		$type = 'quick-search-taxonomy-category';
	}

//print_r($request);

	if ( 'markup' == $response_format ) {
		$args['walker'] = new Master_Walker_Sidebar_Checklist;
	}

	if ( 'get-post-item' == $type ) {
		if ( post_type_exists( $object_type ) ) {
			if ( isset( $request['ID'] ) ) {
				$object_id = (int) $request['ID'];
				if ( 'markup' == $response_format ) {
					echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', array( get_post( $object_id ) ) ), 0, (object) $args );
				} elseif ( 'json' == $response_format ) {
					$post_obj = get_post( $object_id );
					echo json_encode(
						array(
							'ID'         => $object_id,
							'post_title' => get_the_title( $object_id ),
							'post_type'  => get_post_type( $object_id )
						)
					);
					echo "\n";
				}
			}
		} elseif ( taxonomy_exists( $object_type ) ) {
			if ( isset( $request['ID'] ) ) {
				$object_id = (int) $request['ID'];
				if ( 'markup' == $response_format ) {
					echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', array( get_term( $object_id, $object_type ) ) ), 0, (object) $args );
				} elseif ( 'json' == $response_format ) {
					$post_obj = get_term( $object_id, $object_type );
					echo json_encode(
						array(
							'ID'         => $object_id,
							'post_title' => $post_obj->name,
							'post_type'  => $object_type
						)
					);
					echo "\n";
				}
			}

		}

	} elseif ( preg_match('/quick-search-(posttype|taxonomy)-([a-zA-Z_-]*\b)/', $type, $matches) ) {
		if ( 'posttype' == $matches[1] && get_post_type_object( $matches[2] ) ) {
			query_posts(array(
				'posts_per_page' => 10,
				'post_type'      => $matches[2],
				's'              => $query
			));
			if ( ! have_posts() )
				return;
			while ( have_posts() ) {
				the_post();
				if ( 'markup' == $response_format ) {
					$var_by_ref = get_the_ID();

					echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', array( get_post( $var_by_ref ) ) ), 0, (object) $args );
				} elseif ( 'json' == $response_format ) {
					echo json_encode(
						array(
							'ID'         => get_the_ID(),
							'post_title' => get_the_title(),
							'post_type'  => get_post_type()
						)
					);
					echo "\n";
				}
			}
		} elseif ( 'taxonomy' == $matches[1] ) {
			$terms = get_terms( $matches[2], array(
				'name__like' => $query,
				'number'     => 10
			));
			if ( empty( $terms ) || is_wp_error( $terms ) )
				return;
			foreach( (array) $terms as $term ) {
				if ( 'markup' == $response_format ) {

					/**
					 * Change Object Type Before Output
					 * 
					 * Checks if the search result is for the 'All Posts In Category'
					 * metabox and adds an argument to the $args array before the
					 * walker outputs the items.
					 */
					if ( isset( $request['type'] ) && 'quick-search-taxonomy-category-custom-category' == $request['type'] ) {
						$args['custom_item_type'] = 'category_posts';
					} 

					// Walk through the results and echo back to the client
					echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', array( $term ) ), 0, (object) $args );

				} elseif ( 'json' == $response_format ) {
					echo json_encode(
						array(
							'ID'         => $term->term_id,
							'post_title' => $term->name,
							'post_type'  => $matches[2]
						)
					);
					echo "\n";
				}
			}
		}
	} elseif ('quick-search-author-archive' == $type ) {
		master_sidebar_author_quick_search( $request );
	}
}

/**
 * Genereate Author Archive Quick Search Results
 *
 * Takes the user search query input passed as a request
 * and attempts to return authors that match the search
 * query. Please note: When we are using author in this
 * context we are not talking about the WordPress author
 * role, rather we are talking about anyone with the ability
 * to publish posts ( i.e )
 *
 * @link 	http://codex.wordpress.org/Class_Reference/WP_User_Query 			WP_User_Query()
 *
 * @since 1.1
 * @version 1.4
 * 
 */
function master_sidebar_author_quick_search( $request = array() ) {


	if ( ! empty( $request ) && isset( $request['q'] ) ) {

		// Define query args for author query 
		$roles = array( 'Administrator', 'Editor', 'Author' ); 
		$db_id = -9999;

		// Get all users that have author priviledges
		foreach ( $roles as $role ) {

			$search_query = $request['q'];

			$args = array(
				'search_columns' => array( 'ID', 'user_login', 'user_nicename', 'user_email' ),
				'role'           => $role,
			);
	
			// The Query
			$user_query = new WP_User_Query( $args );

			// Output search results
			if ( ! empty( $user_query->results ) ) {
				foreach ( $user_query->results as $user ) {
					if ( false !== stripos( $user->data->display_name, $search_query ) ) {
						?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" value ="1" name="menu-item[<?php echo $db_id; ?>][menu-item-object-id]" class="menu-item-checkbox"> <?php echo $user->data->display_name; ?>
							</label>
							<input class="menu-item-db-id" type="hidden" value="0" name="menu-item[<?php echo $db_id; ?>][menu-item-db-id]">
							<input class="menu-item-object" type="hidden" value="<?php echo $user->data->ID; ?>" name="menu-item[<?php echo $db_id; ?>][menu-item-object]">
							<input class="menu-item-parent-id" type="hidden" value="0" name="menu-item[<?php echo $db_id; ?>][menu-item-parent-id]">
							<input class="menu-item-type" type="hidden" value="author_archive" name="menu-item[<?php echo $db_id; ?>][menu-item-type]">
							<input class="menu-item-title" type="hidden" value="<?php echo $user->data->display_name; ?>" name="menu-item[<?php echo $db_id; ?>][menu-item-title]">
							<input class="menu-item-url" type="hidden" value="<?php echo $user->data->user_url; ?>" name="menu-item[<?php echo $db_id; ?>][menu-item-url]">
						</li>						
						<?php
					}

					$db_id++;
				}
			}
		}
	} // endif
}

