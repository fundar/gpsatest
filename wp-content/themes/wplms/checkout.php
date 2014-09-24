<?php

/**
 * Template Name: Checkout
 * FILE: checkout.php 
 * Created on Apr 2, 2013 at 3:07:11 PM 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 */


get_header();


if ( have_posts() ) : while ( have_posts() ) : the_post();

$title=get_post_meta(get_the_ID(),'vibe_title',true);
if(vibe_validate($title)){
?>
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php the_title(); ?></h1>
                    <?php the_sub_title(); ?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php
                    $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                    if(vibe_validate($breadcrumbs))
                        vibe_breadcrumbs(); 
                ?>
            </div>
        </div>
    </div>
</section>
<?php
}

    $v_add_content = get_post_meta( $post->ID, '_add_content', true );
 
?>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="<?php echo $v_add_content;?> content">
                    <?php
                        the_content();
                     ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
endwhile;
endif;
?>
</div>
<?php
/* == OLD STEP CEHCKOUT === 
?>

<section class="main">
	<div class="container">
            <div class="row">
            <?php    
                    if (have_posts()) : 
                    while (have_posts()) : the_post(); 
                 ?>
                   <div class="checkout">
                       <div class="col-md-9 col-sm-9"> 
                       <div class="wplms_checkout">
                         <div class="checkoutsteps">
                             <ul class="checkout_steps">
                                 <li class="checkout_begin">
                                     <i class="icon-shopping-cart"></i>
                                 </li>
                                 <li class="active"><a>
                                     <h4><?php _e('Step 1','vibe')?></h4>
                                     <p><?php _e('Login/Register','vibe')?></p>
                                     </a>
                                 </li>
                                 <li><a>
                                     <h4><?php _e('Step 2','vibe')?></h4>
                                     <p><?php _e('Billing & Shipping','vibe')?></p>
                                     </a>
                                 </li>
                                  <li><a>
                                     <h4><?php _e('Step 3','vibe')?></h4>
                                     <p><?php _e('Review Order','vibe')?></p>
                                     </a>
                                 </li>
                                  <li><a>
                                     <h4><?php _e('Step 4','vibe')?></h4>
                                     <p><?php _e('Payment Details','vibe')?></p>
                                     </a>
                                 </li>
                                 <li class="checkout_end">
                                     <h4><?php _e('Step 5','vibe')?></h4>
                                     <p><?php _e('Thank You !','vibe')?></p>
                                 </li>
                             </ul>
                         </div>
                           <div class="checkout_content">
                           <?php
                             the_content();
                            ?>
                         </div>   
                      </div> 
                      </div>     
                       <div class="coupon_checkout col-md-3 col-sm-3">
                          <?php if ( get_option( 'woocommerce_enable_coupons' ) == 'no' || get_option( 'woocommerce_enable_coupon_form_on_checkout' ) == 'no' ){}else{ ?>
                           <div class="coupon">
                           <?php
                           
                            $info_message = apply_filters('woocommerce_checkout_coupon_message', __('Have a coupon?', 'vibe'));
                            ?>

                            <p class="woocommerce_info"><?php echo $info_message; ?> <a href="#" class="showcoupon"><?php _e('Click here to enter your code', 'vibe'); ?></a></p>
                            <form class="checkout_coupon" method="post">

                                <p class="form-row-first">
                                    <input type="text" name="coupon_code" class="input-text" placeholder="<?php _e('Coupon code', 'vibe'); ?>" id="coupon_code" value="" />
                                </p>
        
                                <p class="form-row-last">
                                    <input type="submit" class="button" name="apply_coupon" value="<?php _e('Apply Coupon', 'vibe'); ?>" />
                                </p>
        
                                <div class="clear"></div>
                            </form>
                            </div>
                            <?php
                            }
                            $sidebar = apply_filters('wplms_sidebar','checkout',get_the_ID());
                            if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
                          <?php endif; ?>
                       </div>
                        </div>   
                        <?php
                           endwhile;
                           endif;
                            ?>
            </div>
	</div>
</section>   
<?php
*/
get_footer();
?>
