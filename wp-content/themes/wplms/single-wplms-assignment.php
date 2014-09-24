<?php
do_action('wplms_before_single_assignment');
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();

$user_id = get_current_user_id();
$marks=get_post_meta($post->ID,'vibe_assignment_marks',true);
$course=get_post_meta($post->ID,'vibe_assignment_course',true);

$time=get_post_meta($post->ID,'vibe_assignment_duration',true);
$evaluation=get_post_meta($post->ID,'vibe_assignment_evaluation',true);
$assignment_submission_type=get_post_meta($post->ID,'vibe_assignment_submission_type',true);


if(is_user_logged_in()){
    $assignment_taken = get_user_meta($user_id,$post->ID,true);
    $assignment_finished = get_post_meta($post->ID,$user_id,true);
}
$flag=0;
if(isset($assignment_taken) && $assignment_taken !=''){
    $assignment_duration_parameter = apply_filters('vibe_assignment_duration_parameter',86400);
    $timelimit = $assignment_taken + $time*$assignment_duration_parameter;
    if($timelimit > time())
        $flag=1;
    else
        $flag=3;
}
if(isset($assignment_finished) && $assignment_finished !='')
    $flag=2;

if($user_id == $post->post_author || current_user_can('manage_options'))
    $flag=1;
?>
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <span><?php echo '<a href="'.get_permalink($course).'">'.get_the_title($course).'</a>'; ?></span>
                    <h1>
                        <?php the_title(); ?>
                    </h1>
                    <?php the_sub_title(); ?>
                </div>
            </div>
             <div class="col-md-3 col-sm-4">
                <?php
                if(isset($marks) && is_numeric($marks)){
                    echo '<div class="assignment_marks">';
                    echo '<h2>'.$marks.'<span>'.__('Maximum Marks','vibe').'</span></h2>';
                    echo '</div>';
                }else {
                   vibe_breadcrumbs(); 
                }    
                ?>
            </div>
        </div>
    </div>
</section>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                    <div class="content">
                        <div id="assignment">
                            <?php do_action('wplms_assignment_before_content'); 
                            ?>
                            <?php
                            switch($flag){
                                case 0:
                                the_excerpt();
                                assignment_start_button();
                                break;
                                case 1:
                                the_content(); 
                                if(isset($assignment_submission_type)){
                                    switch($assignment_submission_type){
                                        case 'upload': comments_template('/assignment-upload.php',true); 
                                        break;
                                        case 'textarea': comments_template('/assignment-textarea.php',true); 
                                        break;
                                        
                                    }
                                 }else{
                                    comments_template('/assignment-upload.php',true); 
                                 }
                                echo '<a class="button right" id="clear_previous_submissions" data-id="'.get_the_ID().'" data-security="'.wp_create_nonce('user'.$user_id).'">'.__('CLEAR PREVIOUS SUBMISSIONS').'</a>';
                                assignment_submit_button();
                                break;
                                case 2:
                                the_content(); 
                                assignment_results_button();
                                break;
                                case 3:
                                the_content(); 
                                assignment_submit_button();
                                break;
                            }
                            if(current_user_can('manage_options') || ($user_id == $post->post_author)){
                                echo '<h3 class="heading">'.__('All Assignment Submissions').'</h3>';?>
                                <div class="assignment_submissionlist"> 
                                <?php 
                                  comments_template();
                                  //wp_list_comments('type=comment&avatar_size=120&callback=assignment_comment_handle'); 
                                ?>  
                                </div> <?php
                            }
                            ?>
                             <?php do_action('wplms_event_after_content'); ?>
                        </div> 
                    </div>
                <?php
                
                endwhile;
                endif;

                ?>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php
                    if(isset($time) && is_numeric($time)){
                        echo '<div class="assignment_duration">';
                        the_assignment_timer($time);
                        echo '</div>';
                    }
                       
                    if(isset($_GET['edit'])){
                        do_action('wplms_front_end_assignment_controls');    
                    }else{
                        
                    $sidebar = apply_filters('wplms_sidebar','coursesidebar',get_the_ID());
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) {}
                   }
                   ?>
            </div>
        </div>
    </div>
</section>
<?php
do_action('wplms_after_event');
?>
</div>
<?php
get_footer();
?>