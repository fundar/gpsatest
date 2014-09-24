<div id="comments">
    <a href="<?php echo get_post_comments_feed_link(' '); ?>" class="comments_rss"><i class="icon-rss-1"></i></a>
    <h3 class="comments-title"><?php comments_number('0','1','%'); echo __(' Responses on ','vibe'); global $post; echo $post->post_title; ?>"</h3>
   		<ol class="commentlist"> 
   		<?php 
            wp_list_comments('type=comment&avatar_size=120'); 
        ?>	
   		</ol>	
       <div class="navigation">
          <div class="alignleft"><?php previous_comments_link() ?></div>
          <div class="alignright"><?php next_comments_link() ?></div>
      </div>
      <?php
                        
$fields =  array(
        'author' => '<p>' . '<input class="form_field" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="'.__( 'Name','vibe' ) . ( $req ? '*' : '' ) . '"/></p>',
        'email'  => '<p>' .          '<input id="email" class="form_field" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="'.__( 'Email','vibe' ) .  ( $req ? '*' : '' ) . '"/></p>',
        'url'   => '<p>' . '<input id="url" name="url" type="text" class="form_field" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="'. __( 'Website','vibe' ) . '"/></p>',
         );

$comment_field='<p>' . '<textarea id="comment" name="comment" class="form_field" rows="8" " placeholder="'. __( 'Comment','vibe' ) . '"></textarea></p>';

comment_form(array('fields'=>$fields,'comment_field'=>$comment_field,'title_reply'=> '<span>'.__('Leave a Message','vibe').'</span>'));
                ?>
</div>

