<?php
/**
 * Checkout billing information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="woocommerce-billing-fields">
	<?php if ( WC()->cart->ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

		<h3><?php _e( 'Billing &amp; Shipping', 'vibe' ); ?></h3>

	<?php else : ?>

		<h3 class="heading"><?php _e( 'Billing Address', 'vibe' ); ?></h3>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<?php foreach ( $checkout->checkout_fields['billing'] as $key => $field ) : ?>

		<?php 
		woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

	<?php endforeach; ?>

	<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>
	
</div>