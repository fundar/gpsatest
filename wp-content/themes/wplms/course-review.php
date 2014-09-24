<?php

  if(is_user_logged_in()):

    global $post;

    $user_id = get_current_user_id();
    $coursetaken=get_user_meta($user_id,$post->ID,true);

    if(isset($coursetaken) && $coursetaken){
     
      
    $answers=get_comments(array(
      'post_id' => $post->ID,
      'status' => 'approve',
      'user_id' => $user_id
      ));
    if(isset($answers) && is_array($answers) && count($answers)){
        $answer = end($answers);
        $content = $answer->comment_content;
    }else{
        $content='';
    }

    $fields =  array(
        'author' => '<p><label class="comment-form-author clearfix">'.__( 'Name','vibe' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input class="form_field" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" /></p>',
        'email'  => '<p><label class="comment-form-email clearfix">'.__( 'Email','vibe' ) .  ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .          '<input id="email" class="form_field" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"/></p>',
        'url'   => '<p><label class="comment-form-url clearfix">'. __( 'Website','vibe' ) . '</label>' . '<input id="url" name="url" type="text" class="form_field" value="' . esc_attr( $commenter['comment_author_url'] ) . '"/></p>',
         );
        
   $comment_field='<p>' . '<textarea id="comment" name="comment" class="form_field" rows="8" ">'.$content.'</textarea></p>';

   if ( isset($_POST['review']) && wp_verify_nonce($_POST['review'],get_the_ID()) ):

    comment_form(array('fields'=>$fields,'comment_field'=>$comment_field,'label_submit' => __('Post Review','vibe'),'title_reply'=> '<span>'.__('Write a Review','vibe').'</span>','logged_in_as'=>'','comment_notes_after'=>'' ));
    echo '<div id="comment-status" data-quesid="'.$post->ID.'"></div>';
    endif;
  }
  ?>
<?php
  endif;
?>
<h3 class="review_title"><?php _e('Course Reviews','vibe'); ?></h3>
  <?php
  if (get_comments_number()==0) {
    echo '<div id="message" class="notice"><p>';_e('No Reviews found for this course.','vibe');echo '</p></div>';
  }else{
  ?>
  <ol class="reviewlist commentlist"> 
  <?php 
        wp_list_comments('type=comment&avatar_size=120&reverse_top_level=false'); 
        paginate_comments_links( array('prev_text' => '&laquo;', 'next_text' => '&raquo;') )
    ?>  
  </ol> 
<?php
  }
?>

