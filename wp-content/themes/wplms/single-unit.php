<?php


get_header();

global $post;
$flag=0;
$free=get_post_meta(get_the_ID(),'vibe_free',true);

if(vibe_validate($free)){
    $flag=1;
}else if((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && is_user_logged_in())){
    $flag=1;
}else if(current_user_can('edit_posts')){
    $flag=1;
    $instructor_privacy = vibe_get_option('instructor_content_privacy');
    $user_id=get_current_user_id();
    if(isset($instructor_privacy) && $instructor_privacy){
        if($user_id != $post->post_author)
          $flag=0;
    }
}

$flag = apply_filters('wplms_before_unit',$flag);

if($flag || current_user_can('manage_options')){

    if ( have_posts() ) : while ( have_posts() ) : the_post();
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
                if(isset($_GET['id']))
                  echo '<a href="'.get_permalink($_GET['id']).'?action=curriculum" class="course_button button full">'.__('Back to Course','vibe').'</a>';
                
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
                    <div class="single_unit_content">
                    <?php if(has_post_thumbnail()){ ?>
                    <div class="featured">
                        <?php the_post_thumbnail(get_the_ID(),'full'); ?>
                    </div>
                    <?php
                    }
                        the_content();
                    ?>
                    <?php wp_link_pages('before=<div class="unit-page-links page-links"><div class="page-link">&link_before=<span>&link_after=</span>&after=</div></div>'); 
                    do_action('wplms_after_every_unit',get_the_ID());
                    ?>
                    </div> 
                    <div class="tags">
                    <?php the_unit_tags('<ul><li>','</li><li>','</li></ul>'); ?>
                    </div>   
                    <?php
                      $attachments =& get_children( 'post_type=attachment&output=ARRAY_N&orderby=menu_order&order=ASC&post_parent='.$id);
                      if($attachments && count($attachments)){
                        $att= '';

                        $count=0;
                      foreach( $attachments as $attachmentsID => $attachmentsPost ){
                      
                      $type=get_post_mime_type($attachmentsID);

                      if($type != 'image/jpeg' && $type != 'image/png' && $type != 'image/gif'){
                          
                          if($type == 'application/zip')
                            $type='icon-compressed-zip-file';
                          else if($type == 'video/mpeg' || $type== 'video/mp4' || $type== 'video/quicktime')
                            $type='icon-movie-play-file-1';
                          else if($type == 'text/csv' || $type== 'text/plain' || $type== 'text/xml')
                            $type='icon-document-file-1';
                          else if($type == 'audio/mp3' || $type== 'audio/ogg' || $type== 'audio/wmv')
                            $type='icon-music-file-1';
                          else if($type == 'application/pdf')
                            $type='icon-text-document';
                          else
                            $type='icon-file';

                          $count++;

                          $att .='<li><i class="'.$type.'"></i>'.wp_get_attachment_link($attachmentsID).'</li>';
                        }
                      }
                        if($count){
                          echo '<div class="unitattachments"><h4>'.__('Attachments','vibe').'<span><i class="icon-download-3"></i>'.$count.'</span></h4><ul id="attachments">';
                          echo $att;
                         echo '</ul></div>';
                        }
                      }

                      $forum=get_post_meta($id,'vibe_forum',true);
                      if(isset($forum) && $forum){
                        echo '<div class="unitforum"><a href="'.get_permalink($forum).'">'.__('Have Questions ? Ask in the Unit Forums','vibe').'</a></div>';
                      }
                     ?>
                     
                 
                </div>
                <?php

                endwhile;
                endif;
                ?>
                <?php
                do_action('wplms_unit_end_front_end_controls');
                ?>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php
                global $wp_query;
                if(isset($_GET['edit']) || isset($wp_query->query_vars['edit'])){
                    do_action('wplms_front_end_unit_controls');
                }else{
                    $sidebar = apply_filters('wplms_sidebar','coursesidebar',get_the_ID());
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) {}
                }
                ?>
            </div>
        </div>
    </div>
</section>
</div>

<?php
}else{
?>
<section id="title">
    <div class="container">
        <?php echo apply_filters('wplms_direct_access_not_allowed',__('<h1>.Direct Access to Units is not allowed</h1>','vibe')); ?>
        <?php
        do_action('wplms_direct_access_not_allowed');
        ?>
    </div>
</section>
<?php
}
get_footer();
?>