<?php
/**
 * 
 * Template Name: Thankyou page
 */

global $woocommerce;

//ORDER PROCESSING
$url=$_SERVER['REQUEST_URI'];
$uris=explode('/',$url);
$endurl=$uris[(count($uris)-1)];
$order = explode('?',$endurl);

$order = new WC_Order($order[0]);

get_header();
?>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content">
                    <div class="step">
						<?php if ($order) : ?>

							<?php if (in_array($order->status, array('failed'))) : ?>

								<p class="order_fail"><?php _e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce'); ?></p>

								<p class="order_fail"><?php
									if (is_user_logged_in()) :
										_e('Please attempt your purchase again or go to your account page.', 'woocommerce');
									else :
										_e('Please attempt your purchase again.', 'woocommerce');
									endif;
								?></p>

								<p>
									<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e('Pay', 'woocommerce') ?></a>
									<?php if (is_user_logged_in()) : ?>
									<a href="<?php echo esc_url( get_permalink(woocommerce_get_page_id('myaccount')) ); ?>" class="button pay"><?php _e('My Account', 'woocommerce'); ?></a>
									<?php endif; ?>
								</p>

							<?php else : ?>

								<p class="order_success"><?php _e('Thank you. Your order has been received.', 'woocommerce'); ?></p>

								<ul class="order_details">
									<li class="order">
										<?php _e('Order:', 'woocommerce'); ?>
										<strong><?php echo $order->get_order_number(); ?></strong>
									</li>
									<li class="date">
										<?php _e('Date:', 'woocommerce'); ?>
										<strong><?php echo date_i18n(get_option('date_format'), strtotime($order->order_date)); ?></strong>
									</li>
									<li class="total">
										<?php _e('Total:', 'woocommerce'); ?>
										<strong><?php echo $order->get_formatted_order_total(); ?></strong>
									</li>
									<?php if ($order->payment_method_title) : ?>
									<li class="method">
										<?php _e('Payment method:', 'woocommerce'); ?>
										<strong><?php
											echo $order->payment_method_title;
										?></strong>
									</li>
									<?php endif; ?>
								</ul>
								<div class="clear"></div>
								<?php
									$items = $order->get_items();
									foreach($items as $item){
										$product_name = $item['name'];
    									$product_id = $item['product_id'];
    									$subs='';

    									$subscribed=get_post_meta($product_id,'vibe_subscription',true);
    									if(isset($subscribed) && $subscribed !='' && $subscribed!='H'){

    										$duration=get_post_meta($product_id,'vibe_duration',true);

											$date=tofriendlytime($duration*86400);
											$subs= '<strong>'.__('COURSE SUBSCRIBED FOR ','vibe').' : <span>'.$date.'</span></strong>';
    									}else{	
    										$subs= '<strong>'.__('SUSBSCRIBED FOR FULL COURSE','vibe').'</strong>';
    									}

    									$vcourses=vibe_sanitize(get_post_meta($product_id,'vibe_courses',false));
    									if(count($vcourses)){
    										echo '<h4>'.__('Courses Subscribed','vibe').'</h4>
    										<ul class="order_details">
    										<li><a>'.__('COURSE','vibe').'</a>
    											<strong>'.__('SUBSCRIPTION','vibe').'</strong></li>';


    											if($order->status == 'complete'){
    												$ostatus=__('START COURSE','vibe');
    											}else if($order->status == 'pending'){
    												$ostatus=__('WAITING FOR ORDER CONFIRMATION TO START COURSE','vibe');
    											}else{
    												$ostatus=__('WAITING FOR ADMIN APPROVAL','vibe');
    											}
    										foreach($vcourses as $course){
    											echo '<li>
													  <a class="course_name">'.get_the_title($course).'</a>
													  <a href="'.get_permalink($course).'"  class="button">
													  '.$ostatus.'</a>'.$subs.
													  '</li>';  
    										}
    										echo '</ul>';
    									}
    									
									}


								?>
								<div class="clear"></div>
							<?php endif; ?>

							<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
							
							<div class="expand">
								<a class="minmax"><i class="icon-plus-1"></i></a>
								<?php do_action( 'woocommerce_thankyou', $order->id ); ?>
							</div>
						<?php else : ?>

							<p><?php _e('Thank you. Your order has been received.', 'woocommerce'); ?></p>

						<?php endif; ?>
						</div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php
get_footer();
?>