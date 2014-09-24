<?php

$flag=0;
if(!current_user_can('edit_posts')){
    $flag=1;
}else{
    $flag=0;
    $instructor_privacy = vibe_get_option('instructor_content_privacy');
    $user_id=get_current_user_id();
    if(isset($instructor_privacy) && $instructor_privacy){
        if($user_id != $post->post_author)
          $flag=1;
    }
}

if($flag){
    wp_die(__('DIRECT ACCESS TO QUESTIONS IS NOT ALLOWED','vibe'),__('DIRECT ACCESS TO QUESTIONS IS NOT ALLOWED','vibe'),array('back_link'=>true));
}


get_header('buddypress');
if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <div class="pagetitle">
                    <h1><?php the_title(); ?></h1>
                    <?php the_sub_title(); ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="postdate">
                    <i class="icon-calendar"></i> <?php the_date(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="content">
    <div class="container">
        <div class="bcrow">
            <div class="col-md-8">
                 <?php vibe_breadcrumbs(); ?>
            </div>
            <div class="col-md-4">
                <div class="share">
                    <?php 
                    if(function_exists('sharing_display')){
                        echo sharing_display();  // Jetpack Integration
                        } ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="content">
                    <?php if(has_post_thumbnail()){ ?>
                    <div class="featured">
                        <?php the_post_thumbnail(get_the_ID(),'full'); ?>
                    </div>
                    <?php
                    }
                    ?>
                    <?php
                        the_question();
                    ?>
                </div>
                <?php
                endwhile;
                endif;

                do_action('wplms_front_end_question_controls');
                ?>
            </div>
        </div>
    </div>
</section>
</div>

<?php
get_footer();
?>