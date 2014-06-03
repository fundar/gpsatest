<?php
/**
 * Template Name: Certificate
 */


$user_id=$_REQUEST['u'];
$course_id=$_REQUEST['c'];

bp_course_validate_certificate('user_id='.$user_id.'&course_id='.$course_id);

get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();

do_action('wplms_certificate_before_full_content');
?>
<section id="certificate">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content">
                    <?php do_action('wplms_certificate_before_content'); ?>
                    <a href="#" class="printthis"><i class="icon-printer-1"></i></a>
                   	<h1><?php echo apply_filters('wplms_certificate_heading',__('CERTIFICATE OF COMPLETION','vibe')); ?></h1>
                   	<h6><?php echo apply_filters('wplms_certificate_sub_heading',__('Awarded to','vibe')); ?></h6>
                    <?php do_action('wplms_certificate_before_name'); ?>
                   	<h2><?php echo bp_core_get_user_displayname($user_id); ?> 
                    <?php do_action('wplms_certificate_after_name'); ?>
                   	<span><?php echo xprofile_get_field_data( 'Location', $user_id ); ?></span></h2>
                   	<span><?php echo apply_filters('wplms_certificate_before_course_title',__('for successful completion of course','vibe')); ?></span>
                   	<h3><?php echo get_the_title($course_id); ?></h3>
                    <?php do_action('wplms_certificate_after_content'); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php

do_action('wplms_certificate_after_full_content');

get_footer();
?>