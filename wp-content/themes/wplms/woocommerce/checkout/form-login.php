<?php
/**
 * Checkout login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */


if ( is_user_logged_in() ) {
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
if ( get_option('woocommerce_enable_signup_and_login_from_checkout') == "no" ) return;

$info_message  = apply_filters( 'woocommerce_checkout_login_message', __( 'Returning customer?', 'woocommerce' ) );
$info_message .= ' <a href="#" class="showlogin">' . __( 'Click here to login', 'woocommerce' ) . '</a>';
?>
<div class="step step1">
<?php
wc_print_notice( $info_message, 'notice' );
?>
<div class="one_half">
    <div class="column_content first">
        <h3>
      <?php
_e('New Customer','vibe');
?></h3>  
        
        <ul>
            <li><p class="form-row form-row-wide">
			<input class="input-radio" type="radio" id="guestaccount" name="createaccount" value="1" /> <label for="guestaccount" class="checkbox"><?php _e( 'Continue as Guest', 'woocommerce' ); ?></label>
		</p></li>
            <li><?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>

	<?php if ( $checkout->enable_guest_checkout ) : ?>

		<p class="form-row form-row-wide">
			<input class="input-radio" id="createaccount" <?php checked($checkout->get_value('createaccount'), true) ?> type="radio" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e( 'Create a a New Acccount', 'woocommerce' ); ?></label>
		</p>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

	<div class="create-account">
            <h4><?php _e('Enter account details','vibe') ?></h4>
		<?php foreach ($checkout->checkout_fields['account'] as $key => $field) : ?>

			<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

		<?php endforeach; ?>

		<div class="clear"></div>

	</div>

	<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>

<?php endif; ?></li>
        </ul>
    </div>
</div>  
<div class="one_half">
    <div class="column_content">
<h3>
      <?php
_e('Returning Customer','vibe');
?></h3> 
<?php
    woocommerce_login_form(
        array(
            'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.', 'woocommerce' ),
            'redirect' => get_permalink( wc_get_page_id( 'checkout' ) ),
            'hidden'   => true
        )
    );
?>
     
    </div>
</div>         
<br class="clearfix" />
                        <div class="proceed">
                            <a class="btn primary" rel="2"><?php _e('Proceed','vibe'); ?> &rsaquo;</a>
  </div>
</div>