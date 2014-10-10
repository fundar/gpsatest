<?php
function country() {
     global $bp;

	$user_id = bp_get_activity_user_id();
	$country = xprofile_get_field_data('Country', $user_id);

	echo '<div class="country">'. $country . '</div>';
     }
add_action( 'showcountry', 'country' );
function organization() {
     global $bp;
	$user_id = bp_get_activity_user_id();
	$organization = xprofile_get_field_data('Organization', $user_id);

	echo '<div class="country">'. $organization . '</div>';
     }
add_action( 'showorganization', 'organization' );
function country_forum() {
	$user_id = bbp_get_reply_author_id();
	$country = xprofile_get_field_data('Country', $user_id);
	echo '<div class="country">'. $country . '</div>';
}
 add_action( 'bbp_theme_after_reply_author_details', 'country_forum' );
function country_networking() {
   global $bp;
	$user_id = bp_get_activity_user_id();
	$country = xprofile_get_field_data('Organization', $user_id); // field ID or name
 	echo '<div class="Country">'. $country . '</div>';
}
//add_action( 'bp_get_activity_content_body', 'country_networking' );
function bbg_my_groups_activity_default( $qs ) {
if ( empty( $qs ) && empty( $_POST ) ) {
$qs = 'action=activity_update';
}

return $qs;
}
add_filter( 'bp_ajax_querystring', 'bbg_my_groups_activity_default', 999 );
//grupos
function bpfr_add_page_to_group() {

	if ( class_exists( 'BP_Group_Extension' ) ) :

class My_Custom_Group_Extension extends BP_Group_Extension {

    function __construct() {
         $args = array(
            'slug' => 'Gpsa-space',
            'name' => 'GPSA SPACE',
        );
        parent::init( $args );
    }


     function settings_screen( $group_id ) {
		// don't remove this function
           }
          function display() {


			// grab page or post ID
			$id = 1315;
			$p = get_post($id);

			// output the title
			echo '<h3>'.apply_filters('the_content', $p->post_title).'</h3>';
			// output the post
			echo apply_filters('the_content', $p->post_content);

			// end option
			}
} // end of class


/* display content only in one group*/


    // check for a group ID
        if( bp_has_groups() ) {
            // Grab current group ID
            bp_the_group();
            $group_id = bp_get_group_ID();
        }


    /* apply our changes only to this group */
        // conditionnal action
        if ( $group_id == 3) {
            bp_register_group_extension( 'My_Custom_Group_Extension' );
        }


    endif;
}
add_filter('bp_groups_default_extension', 'bpfr_add_page_to_group' );