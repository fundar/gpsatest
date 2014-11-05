<?php
/*
Plugin Name: BuddyPress Captcha
Plugin URI: http://www.trickspanda.com
Description: This plugin adds a reCAPTCHA form to BuddyPress registration form to keep your community spam free.
Version: 1.0
Author: Hardeep Asrani
Author URI: http://www.hardeepasrani.com
Requires at least: WordPress 2.8, BuddyPress 1.2.9
License: GPL2
*/

/* Options */
$public_key = get_option('bpcapt_public');
$private_key = get_option('bpcapt_private');
$theme = get_option('bpcapt_theme');
$lang = get_option('bpcapt_language');
$strError = __('Please check the CAPTCHA code. It\'s not correct.', 'buddypress-recaptcha');

require_once('recaptcha-php-1.11/recaptchalib.php');
require_once('bpcapt-options.php');

function bp_add_code() {
	global $bp, $theme, $lang, $public_key;
	
	$script = "<script type=\"text/javascript\">
 			var RecaptchaOptions = {
    			theme : '".$theme."',
				lang: '".$lang."'
 			};
		</script>";
		
	$html = '<div class="register-section" id="security-section">';
	$html .= '<div class="editfield">';
	$html .= $script;
	$html .= '<label>CAPTCHA code</label>';
	if (!empty($bp->signup->errors['recaptcha_response_field'])) {
		$html .= '<div class="error">';
		$html .= $bp->signup->errors['recaptcha_response_field'];
		$html .= '</div>';
	}
	$html .= recaptcha_get_html($public_key);
	$html .= '</div>';
	$html .= '</div>';
	echo $html;
}

function bp_validate($errors) {
	global $bp, $strError, $private_key;

	if (function_exists('recaptcha_check_answer')) {
		$response = recaptcha_check_answer($private_key, $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);

		if (!$response->is_valid) {
			$bp->signup->errors['recaptcha_response_field'] = $strError;
		}
	}

	return;
}

add_action( 'bp_before_registration_submit_buttons', 'bp_add_code' );
add_action( 'bp_signup_validate', 'bp_validate' );

?>