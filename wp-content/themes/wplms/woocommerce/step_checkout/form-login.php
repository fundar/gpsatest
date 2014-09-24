<?php
/**
 * Checkout login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in() ) {
    echo '<span class="checkout"></span>';
    $current_user = wp_get_current_user();?>
    <div class="step step1">
    <?php
    echo '<h5>'.__('Click proceed to continue','vibe').'</h5>';       
    echo '<a href="'.wp_logout_url().'">'.__('Click here to logout and Signin as different user','vibe').'</a>';
        
    ?>
    <br class="clearfix" />
    <div class="proceed" >
        <a class="btn primary" rel="2"><?php _e('Proceed','vibe'); ?> &rsaquo;</a>
    </div>
    </div>
    <?php
    return;
}



if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) return;

$info_message  = apply_filters( 'woocommerce_checkout_login_message', __( 'Returning customer?', 'vibe' ) );
$info_message .= ' <a href="#" class="showlogin">' . __( 'Click here to login', 'vibe' ) . '</a>';
wc_print_notice( $info_message, 'notice' );

?>

<div class="step step1">
<div class="one_half">
    <div class="column_content first">
        <h3  class="heading">
              <?php
        _e('Returning Customer','vibe');
        ?></h3> 
        <?php
            woocommerce_login_form(
                array(
                    'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below.', 'vibe' ),
                    'redirect' => get_permalink( wc_get_page_id( 'checkout' ) ),
                    'hidden'   => true
                )
            );
        ?>
     
    </div>
</div>  
<?php
if ( !is_user_logged_in()){
    $get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() ); 

?>
<form name="checkout" method="post" class="checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">
<div class="one_half">
    <div class="column_content">
        <h3 class="heading"><?php _e('New Customer','vibe'); ?></h3>  
        <ul>
        <?php if ( $checkout->enable_guest_checkout ) :  ?>
            <li><p class="form-row form-row-wide">
			<input class="input-radio" type="radio" id="guestaccount" name="createaccount" value="1" /> <label for="guestaccount" class="checkbox"><?php _e( 'Continue as Guest', 'vibe' ); ?></label>
	        </p></li>
        <?php endif; ?>

        <?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>
            <li>

        <?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

        <?php if ( ! empty( $checkout->checkout_fields['account'] ) ) : ?>

            <div class="create-account">

                <p><input class="input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true) ?> type="checkbox" name="createaccount" value="1" /> <?php _e( 'Create an account by entering the information below.', 'vibe' ); ?></p>

                <?php foreach ( $checkout->checkout_fields['account'] as $key => $field ) : ?>

                    <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

                <?php endforeach; ?>

                <div class="clear"></div>

            </div>

        <?php endif; ?>

        <?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
        </li>
    <?php endif; ?>
        </ul>
    </div>
</div>  
<?php
 }
?>


<br class="clearfix" />
<div class="proceed">
    <a class="btn primary" rel="2"><?php _e('Proceed','vibe'); ?> &rsaquo;</a>
  </div>
</div>