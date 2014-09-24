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

?>