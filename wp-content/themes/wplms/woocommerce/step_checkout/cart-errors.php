<?php
/**
 * Cart errors page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
?>


<?php wc_print_notices(); ?>

<p><?php _e('There are some issues with the items in your cart (shown above). Please go back to the cart page and resolve these issues before checking out.', 'vibe') ?></p>

<?php do_action('woocommerce_cart_has_errors'); ?>

<p><a class="button" href="<?php echo get_permalink(woocommerce_get_page_id('cart')); ?>"><?php _e('&larr; Return To Cart', 'vibe') ?></a></p>