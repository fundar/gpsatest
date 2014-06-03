<?php
/**
 * Admin taxonomy functions
 *
 *
 * @author 		Ashan Jay
 * @category 	Admin
 * @package 	eventon/Admin/Taxonomies
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// ==========================
//	TAXONOMY
function event_type_description() {
	echo wpautop( __( 'Event categories for events can be managed here. To change the order of categories on the front-end you can drag and drop to sort them. To see more categories listed click the "screen options" link at the top of the page.', 'eventon' ) );
}
add_action( 'event_type_pre_add_form', 'event_type_description' );



// add ID field to event type taxonomy pages
// event type 1
add_filter( 'manage_edit-event_type_columns', 'event_type_edit_columns',5 );
add_filter( 'manage_event_type_custom_column', 'event_type_custom_columns',5,3 );


// event type 2
add_filter( 'manage_edit-event_type_2_columns', 'event_type_edit_columns',5 );
add_filter( 'manage_event_type_2_custom_column', 'event_type_custom_columns',5,3 );
function event_type_edit_columns($defaults){
    $defaults['event_type_id'] = __('ID');
    return $defaults;
}   

function event_type_custom_columns($value, $column_name, $id){
	if($column_name == 'event_type_id'){
		return (int)$id;
	}
}






// ==========================
//	TAXONOMY - event location

// remove some columns
add_filter("manage_edit-event_location_columns", 'eventon_evLocation_theme_columns');  
function eventon_evLocation_theme_columns($theme_columns) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        'event_location' => __('Address','eventon'),
//      'description' => __('Description'),
        'slug' => __('Slug')
        );
    return $new_columns;
}
// Add event location address field  
add_filter("manage_event_location_custom_column", 'eventon_manage_evLocation_columns', 10, 3); 
function eventon_manage_evLocation_columns($out, $column_name, $term_id) {
    //$theme = get_term($term_id, 'event_location');
    switch ($column_name) {
        case 'event_location': 
            
        	$term_meta = get_option( "taxonomy_$term_id" );

        	$out = "<p>".esc_attr( $term_meta['location_address'] ) ? esc_attr( $term_meta['location_address'] ) : ''."</p>";
           
            break;
 
        default:
            break;
    }
    return $out;    
}


// add term page
function eventon_taxonomy_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="term_meta[location_address]"><?php _e( 'Location Address', 'eventon' ); ?></label>
		<input type="text" name="term_meta[location_address]" id="term_meta[location_address]" value="">
		<p class="description"><?php _e( 'Enter a location address','eventon' ); ?></p>
	</div>
	<div class="form-field">
		<label for="term_meta[location_lon]"><?php _e( 'Longitude', 'eventon' ); ?></label>
		<input type="text" name="term_meta[location_lon]" id="term_meta[location_lon]" value="">
		<p class="description"><?php _e( '(Optional) longitude for address','eventon' ); ?></p>
	</div>
	<div class="form-field">
		<label for="term_meta[location_lat]"><?php _e( 'Latitude', 'eventon' ); ?></label>
		<input type="text" name="term_meta[location_lat]" id="term_meta[location_lat]" value="">
		<p class="description"><?php _e( '(Optional) latitude for address','eventon' ); ?></p>
	</div>
<?php
}
add_action( 'event_location_add_form_fields', 'eventon_taxonomy_add_new_meta_field', 10, 2 );

// Edit term page
function eventon_taxonomy_edit_meta_field($term) {
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[location_address]"><?php _e( 'Location Address', 'eventon' ); ?></label></th>
		<td>
			<input type="text" name="term_meta[location_address]" id="term_meta[location_address]" value="<?php echo esc_attr( $term_meta['location_address'] ) ? esc_attr( $term_meta['location_address'] ) : ''; ?>">
			<p class="description"><?php _e( 'Enter a location address','eventon' ); ?></p>
		</td>
	</tr>

	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[location_lon]"><?php _e( 'Longitude', 'eventon' ); ?></label></th>
		<td>
			<input type="text" name="term_meta[location_lon]" id="term_meta[location_lon]" value="<?php echo esc_attr( $term_meta['location_lon'] ) ? esc_attr( $term_meta['location_lon'] ) : ''; ?>">
			<p class="description"><?php _e( '(Optional) longitude for address','eventon' ); ?></p>
		</td>
	</tr>

	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta[location_lat]"><?php _e( 'Latitude', 'eventon' ); ?></label></th>
		<td>
			<input type="text" name="term_meta[location_lat]" id="term_meta[location_lat]" value="<?php echo esc_attr( $term_meta['location_lat'] ) ? esc_attr( $term_meta['location_lat'] ) : ''; ?>">
			<p class="description"><?php _e( '(Optional) latitude for address','eventon' ); ?></p>
		</td>
	</tr>
<?php
}

add_action( 'event_location_edit_form_fields', 'eventon_taxonomy_edit_meta_field', 10, 2 );


// Save extra taxonomy fields callback function.
function evo_save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_event_location', 'evo_save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_event_location', 'evo_save_taxonomy_custom_meta', 10, 2 );

?>