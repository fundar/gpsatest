<?php


  if(is_user_logged_in()):

    global $post;

    $user_id = get_current_user_id();

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
        
    $comment_field='<p>' . '<textarea id="comment" name="comment" class="form_field" rows="15" ">'.$content.'</textarea></p>';
    comment_form(array('fields'=>$fields,'comment_field'=>$comment_field,'label_submit' => __('Answer Assignment'),'title_reply'=> '<span>'.__('Answer','vibe').'</span>','logged_in_as'=>'','comment_notes_after'=>'' ));
    

  if(current_user_can('edit_posts') || ($post->post_author == $user_id)):
  ?>
<h3 class="heading"><?php _e('Assignment Submissions','vibe'); ?></h3>
  <ol class="commentlist"> 
  <?php 
        wp_list_comments('type=comment&avatar_size=120&reverse_top_level=true'); 
    ?>  
  </ol> 

<?php
    endif;
    
  endif;
  ?>