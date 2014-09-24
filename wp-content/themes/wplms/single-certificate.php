<?php
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();

$print=get_post_meta($post->ID,'vibe_print',true);


$class=get_post_meta($post->ID,'vibe_custom_class',true);
$css=get_post_meta($post->ID,'vibe_custom_css',true);

$bgimg_id=get_post_meta($post->ID,'vibe_background_image',true);

$bgimg=wp_get_attachment_info( $bgimg_id );


do_action('wplms_certificate_before_full_content');
?>
<section id="certificate">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php do_action('wplms_certificate_before_content'); ?>
                    <div class="extra_buttons">
                        <?php do_action('wplms_certificate_extra_buttons');
                        if(vibe_validate($print)) 
                            echo '<a href="#" class="certificate_print"><i class="icon-printer-1"></i></a>';
                        ?>
                    </div>
                    <div class="certificate_content <?php echo $class;?>" style="<?php
                            if(isset($bgimg_id) && $bgimg_id && isset($bgimg['src']))
                                echo 'background:url('.$bgimg['src'].');';
                        ?>">
                        <?php echo (isset($css)?'<style>'.$css.'</style>':'');?>
                        <?php
                            the_content(); 
                        ?>
                         <?php do_action('wplms_certificate_after_content'); ?>
                    </div>
                </div>
                <?php
                
                endwhile;
                endif;
                ?>
            </div>
        </div>
    </div>
</section>
<?php
do_action('wplms_certificate_after_full_content');
?>
</div>
<?php
get_footer();
?>