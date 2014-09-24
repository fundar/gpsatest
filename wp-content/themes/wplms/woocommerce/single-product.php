<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

get_header();
?>
<section class="main">
	<div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">   
                <div class="content"> 
    	          <?php while ( have_posts() ) : the_post(); ?>
                    <?php woocommerce_get_template_part( 'content', 'single-product' ); ?>
    	          <?php endwhile; // end of the loop. ?>
                </div>  
            </div>
             <div class="col-md-3 col-sm-4">
                <div class="prev_next_links">
                     <ul class="prev_next">
                        <?php 
                        echo '<li>';previous_post_link('<strong class="prev">%link</strong>'); 
                        echo '</li><li> | </li><li>';
                        next_post_link('<strong class="next">%link</strong>');
                        echo '</li>';
                        ?>
                    </ul>      
                </div>
                <div class="woocart">
                    <?php
                        the_widget('WC_Widget_Cart', 'title=0&hide_if_empty=1');
                    ?>
                </div>
                <div class="sidebar">
                    <?php do_action('woocommerce_sidebar'); ?>
                    <?php
                        do_action('woocommerce_after_main_content');
                    ?>
                </div>
            </div>
        </div>
    </div>    
</section>
<?php get_footer('shop'); ?>