<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


get_header('shop');
?>
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1>KInowledge Repository</h1>
                    <h5><?php the_sub_title(); ?></h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
            	<div class="vibecrumbs">
                <?php
					/**
					 * woocommerce_before_main_content hook
					 *
					 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
					 * @hooked woocommerce_breadcrumb - 20
					
					do_action('woocommerce_before_main_content');
					 */
                ?> 
                </div>
            </div>
        </div>      
    </div>
</section>

<section class="main">
	<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="shop_products content padder">
					<?php do_action( 'woocommerce_archive_description' ); ?>

					<?php if ( is_tax() ) : ?>
						<?php do_action( 'woocommerce_taxonomy_archive_description' ); ?>
					<?php elseif ( ! empty( $shop_page ) && is_object( $shop_page ) ) : ?>
						<?php do_action( 'woocommerce_product_archive_description', $shop_page ); ?>
					<?php endif; ?>
			                 
			                
					<?php if ( have_posts() ) : ?>

				<div class="shop_countsorter">			
					<?php do_action('woocommerce_before_shop_loop'); ?>
				</div>	
				<ul class="products">

					<?php woocommerce_product_subcategories(); ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php woocommerce_get_template_part( 'content', 'product' ); ?>

					<?php endwhile; // end of the loop. ?>

				</ul>

				<?php do_action('woocommerce_after_shop_loop'); ?>

				<?php else : ?>

					<?php if ( ! woocommerce_product_subcategories( array( 'before' => '<ul class="products">', 'after' => '</ul>' ) ) ) : ?>

						<p><?php _e( 'No products found which match your selection.', 'woocommerce' ); ?></p>

					<?php endif; ?>

				<?php endif; ?>

				<div class="clear"></div>

					<?php
						/**
						 * woocommerce_pagination hook
						 *
						 * @hooked woocommerce_pagination - 10
						 * @hooked woocommerce_catalog_ordering - 20
						 */
						do_action( 'woocommerce_pagination' );
					?>
					<?php
					/**
					 * woocommerce_after_main_content hook
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					do_action('woocommerce_after_main_content');
					?>
				</div>
     		</div> 
		    
		 </div>
  	</div> 
</section>
	

<?php get_footer('shop'); ?>
               