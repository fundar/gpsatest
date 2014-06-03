<?php

/**
 * Master_Walker_Sidebar_Edit
 * 
 * Create HTML list of sidebar input items. This is 
 * used to generate the markup used to output the 
 * sortable list items used in the admin page
 *
 * @package 	WordPress
 * @subpackage 	Custom_Theme_Sidebars
 * @author 		Sunny Johal - Titanium Themes
 * @copyright 	Copyright (c) 2014, Titanium Themes
 * @since  		1.0
 * @version 	1.4
 * 
 * @uses Walker_Nav_Menu
 * 
 */
class Master_Walker_Sidebar_Edit extends Walker_Nav_Menu {

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		global $_wp_nav_menu_max_depth;
		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		// Start output buffer
		ob_start();
		$item_id   = esc_attr( $item->ID );
		$page_name = 'custom_theme_sidebars';
		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);

		// Set the title
		$original_title = '';

		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) ) {
				$original_title = false;
			}

		} elseif ( 'taxonomy_all' == $item->type ) {
			$tax = get_taxonomy( $item->object );
			if( $tax ) {
				
				// Add 'All' prefix
				if ( isset( $args->pending ) ) {
					if ( ! $args->pending ) {
						$item->label = sprintf( __( 'All %s', 'theme-translate' ), $item->label );
					}
				}
				
				$original_title   = $tax->labels->name;
				$item->type_label = $tax->labels->name;
				$item->url        = esc_url(
										add_query_arg(
											array(
												'taxonomy' => $tax->name
											),
										admin_url( 'edit-tags.php' )
										)
									);
			}

		} elseif ( 'author_archive' == $item->type ) {

			$user = get_user_by( 'id', $item->object );

			if ( $user ) {
				$original_title   = $user->display_name;
				$item->type_label = __( 'Author Archive', 'theme-translate' );
				$item->url        = esc_url( get_author_posts_url( $item->object ) ); 

				// Set title if pending
				if ( isset( $args->pending ) ) {
					if ( ! $args->pending ) {
						$item->label = $user->display_name;
					}
				}

			}


		} elseif ( 'category_posts' == $item->type ) {
			$category       = get_category( $item->object_id );
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			$item->label    = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			// Handle wp_error case
			if ( is_wp_error( $original_title ) ) {
				$original_title = false;
			}

			$item->type_label = __( 'All Posts In Category', 'theme-translate' );
			$item->url        = esc_url(
									add_query_arg(
										array(
											'category_name' => $category->slug
										),
									admin_url( 'edit.php' )
									)
								);


		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title  = $original_object->post_title;

		} elseif ( 'post_type_all' == $item->type ) {
			$post_type = get_post_type_object( $item->object );
			if ( $post_type ) {
				
				// Add 'All' prefix
				if ( isset( $args->pending ) ) {
					if ( ! $args->pending ) {
						$item->label = sprintf( __( 'All %s', 'theme-translate' ), $item->label );
					}
				}

				$original_title   = $post_type->labels->name;
				$item->type_label = $post_type->labels->name;
				$item->url        = esc_url(
										add_query_arg(
											array(
												'post_type' => $post_type->name
											),
										admin_url( 'edit.php' )
										)
									);
			}
		} elseif ( 'post_type_archive' ) {
			$post_type = get_post_type_object( $item->object );
			if ( $post_type ) {

				// Add 'Archive Prefix' to existing items
				if ( isset( $args->pending ) ) {
					if ( ! $args->pending ) {
						$item->label = sprintf( __( '%s Archive', 'theme-translate' ), $post_type->labels->singular_name );
					}
				}				

				$original_title   = sprintf( __( '%s Archive', 'theme-translate' ), $post_type->labels->singular_name );
				$item->type_label = __( 'Archive', 'theme-translate' );
				$item->url = esc_url( get_post_type_archive_link( $post_type->name ) );
			}
		} elseif ( 'template_hierarchy' == $item->type ) {
			$original_title   = $item->title;
			$item->label      = $item->title;
			$item->type_label = __( 'Template', 'theme-translate' );
			$item->url        = esc_url(
									add_query_arg(
										array(
											'post_type' => 'page'
										),
									admin_url( 'edit.php' )
									)
								);
		}

		// Add any classes
		$classes = array(
			'menu-item menu-item-depth-' . $depth,
			'menu-item-' . esc_attr( $item->object ),
			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			/* translators: %s: title of menu item which is invalid */
			$title = sprintf( __( '%s (Invalid)' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {

			if ( isset( $args->pending ) ) {
				
				if ( ! $args->pending ) {
					$classes[] = 'not-pending';
				} else {
					$classes[] = 'pending';
				}

			} else {
				$classes[] = 'pending';
			}
			
			/* translators: %s: title of menu item in draft status */
			$title = sprintf( __('%s (Pending)'), $item->title );
		}

		$title = empty( $item->label ) ? $title : $item->label;

		?>
		<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title"><?php echo esc_html( $title ); ?></span>
					<span class="item-controls">
						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
						<a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Sidebar Item', 'theme-translate'); ?>" href="#">
							<?php _e( 'Edit Sidebar Item', 'theme-translate' ); ?>
						</a>
					</span>
				</dt>
			</dl>

			<div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
				
				<div class="menu-item-actions description-wide submitbox">
					<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
						<p class="link-to-original">
							<?php printf( __('Original: %s'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
						</p>
					<?php endif; ?>
					<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
					echo wp_nonce_url(
						add_query_arg(
							array(
								'action'       => 'delete-sidebar-item',
								'sidebar-item' => $item_id,
								'page'         => $page_name
							),
							remove_query_arg($removed_args, admin_url( 'themes.php' ) )
						),
						'delete-menu_item_' . $item_id
					); ?>"><?php _e('Remove'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo $item_id; ?>" href="<?php	echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'themes.php' ) ) ) );
						?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel'); ?></a>
				</div>
				
				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php esc_html_e( $item_id ); ?>]" value="<?php esc_html_e( $item_id ); ?>" />
				<input class="menu-item-title" type="hidden" name="menu-item-title[<?php esc_html_e( $original_title ); ?>]" value="<?php esc_html_e( $original_title ); ?>">
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		<?php
		$output .= ob_get_clean();
	}
}

