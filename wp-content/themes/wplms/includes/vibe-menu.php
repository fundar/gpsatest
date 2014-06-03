<?php




class vibe_menu {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {

		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'vibe_add_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'vibe_update_nav_fields'), 10, 3 );
		
		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'vibe_edit_walker'), 10, 2 );

	} // end constructor
	
	
	
	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function vibe_add_nav_fields( $menu_item ) {
	
	    	$menu_item->sidebar = get_post_meta( $menu_item->ID, '_menu_item_sidebar', true );
            $menu_item->columns = get_post_meta( $menu_item->ID, '_menu_item_columns', true );
	    return $menu_item;
	    
	}
	
	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function vibe_update_nav_fields( $menu_id, $menu_item_db_id, $args ) {
	
	    // Check if element is properly sent
	    if ( isset($_REQUEST['menu-item-sidebar']) && is_array( $_REQUEST['menu-item-sidebar']) ) {
	        $sidebar_value = $_REQUEST['menu-item-sidebar'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_sidebar', $sidebar_value );
	    }
	    if ( isset($_REQUEST['menu-item-columns']) && is_array( $_REQUEST['menu-item-columns']) ) {
	        $sidebar_columns = $_REQUEST['menu-item-columns'][$menu_item_db_id];
	        update_post_meta( $menu_item_db_id, '_menu_item_columns', $sidebar_columns );
	    }
	}
	
	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function vibe_edit_walker($walker,$menu_id) {
	
	    return 'Walker_Nav_Menu_Edit_Custom';
	    
	}

}

// instantiate plugin's class
$GLOBALS['vibe_menu'] = new vibe_menu();


include_once( 'menu/edit_custom_walker.php' );
include_once( 'menu/custom_walker.php' );