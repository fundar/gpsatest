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
        
    /*    
    ob_start();
    wp_editor( $content, 'comment', array(
        'wpautop' => true,
        'media_buttons' => true,
        'teeny' => true,
        'textarea_rows' => '9',
        'tinymce' => array(
                        'theme_advanced_buttons1' => 'save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect',
                        'theme_advanced_buttons2' => "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                        'theme_advanced_buttons3' => "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                        'theme_advanced_buttons4' => "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
                        'theme_advanced_text_colors' => '0f3156,636466,0486d3',
                    ),
        'quicktags' => array(
            'buttons' => 'b,i,ul,ol,li,link,close'
        )
    ) );
    
    $comment_field='<p>'. ob_get_clean().'</p>';
  */
    $comment_field='<p>' . '<textarea id="comment" name="comment" class="form_field" rows="15" ">'.$content.'</textarea></p>';
    comment_form(array('fields'=>$fields,'comment_field'=>$comment_field,'label_submit' => __('Save Answer'),'title_reply'=> '<span>'.__('Answer','vibe').'</span>','logged_in_as'=>'','comment_notes_after'=>'' ));
    echo '<div id="comment-status" data-quesid="'.$post->ID.'"></div>';

  if(current_user_can('publish_posts')):
  ?>
<h5><?php _e('Previous Answers to this Question','vibe'); ?></h5>
  <ol class="commentlist"> 
  <?php 
        wp_list_comments('type=comment&avatar_size=120&reverse_top_level=true'); 
    ?>  
  </ol> 

<?php
    endif;
    
  endif;
  ?>