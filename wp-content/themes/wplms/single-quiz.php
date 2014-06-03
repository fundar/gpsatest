<?php
get_header('buddypress');
$user_id = get_current_user_id();
$quiztaken=get_user_meta($user_id,get_the_ID(),true);
if ( have_posts() ) : while ( have_posts() ) : the_post();

?>
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-9">
                <div class="pagetitle">
                    <h1><?php the_title(); ?></h1>
                    <h5><?php the_sub_title(); ?></h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="quiz_next">
        <?php
            if(is_user_logged_in()){
                if(isset($quiztaken) && $quiztaken){
                    if($quiztaken > time()){
                        echo '<a class="button create-group-button full begin_quiz" data-quiz="'.get_the_ID().'"> '.__('Continue Quiz','vibe').'</a>';
                            wp_nonce_field('start_quiz','start_quiz');
                    }else{

                        $quiz_unfinished_check=get_post_meta(get_the_ID(),$user_id,true);
                        if(!isset($quiz_unfinished_check) || $quiz_unfinished_check ==''){
                            add_post_meta(get_the_ID(),$user_id,0);
                        }
                        
                        echo '<a href="'.bp_loggedin_user_domain().'course/course-results/?action='.get_the_ID().'" class="button create-group-button full"> '.__('Check Quiz Results','vibe').'</a>';
                    }
                }else{
                    echo '<a class="button create-group-button full begin_quiz" data-quiz="'.get_the_ID().'"> '.__('Start Quiz','vibe').'</a>';
                     wp_nonce_field('start_quiz','start_quiz');
                }
            }else{
                echo '<a class="button create-group-button full"> '.__('Take a Course to Start the Quiz','vibe').'</a>';
                     
            }
        ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="content">
                    <?php
                        the_quiz();
                    ?>
                </div>
            </div>
            <div class="col-md-3 quiz-sidebar">
                <div class="quiz_details">
                 <?php
                    the_quiz_timer(NULL);
                    the_quiz_timeline(NULL);
                ?>
                </div>
            </div>
             <?php
                endwhile;
                endif;
                ?>
        </div>
    </div>
</section>
</div>

<?php
get_footer('buddypress');
?>