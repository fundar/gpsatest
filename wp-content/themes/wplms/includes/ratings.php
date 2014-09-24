<?php
// COMMENTS RATING

// Add fields after default fields above the comment box, always visible
add_action('comment_post','calculate_ratings',99,2);

//if(is_singular('course')){
add_action( 'comment_form_logged_in_after', 'additional_fields' );
add_action( 'comment_form_after_fields', 'additional_fields' );
add_action( 'comment_post', 'save_comment_meta_data' );
add_action( 'add_meta_boxes_comment', 'extend_comment_add_meta_box' );
add_action( 'edit_comment', 'extend_comment_edit_metafields' );
add_filter( 'comment_text', 'modify_comment');
//}

function additional_fields () {
  global $post;
  if(is_singular('course')){
      echo '<p class="comment-form-title">'.
      '<label for="title">' . __( 'Review Title','vibe' ) . '</label>'.
      '<input id="title" name="review_title" class="form_field" type="text" size="30"  tabindex="5" /></p>';

      echo '<p class="comment-form-rating">'.
      '<label for="rating">'. __('Review Rating','vibe') . '<span class="required">*</span></label>
      <span class="commentratingbox">';
      
      for( $i=1; $i <= 5; $i++ )
      echo '<span class="commentrating">
            <input type="radio" name="review_rating" id="rating" value="'. $i .'"/>'. $i .'
            </span>';

      echo'</span></p>';
  }

}

// Save the comment meta data along with comment


function save_comment_meta_data( $comment_id ) {

if(get_post_type($_POST['comment_post_ID']) != 'course')    
                return;

  if ( ( isset( $_POST['review_title'] ) ) && ( $_POST['review_title'] != '') ){
  $title = wp_filter_nohtml_kses($_POST['review_title']);
  add_comment_meta( $comment_id, 'review_title', $title );
  }

  if ( ( isset( $_POST['review_rating'] ) ) && ( $_POST['review_rating'] != '') ){
  $rating = wp_filter_nohtml_kses($_POST['review_rating']);
  add_comment_meta( $comment_id, 'review_rating', $rating );
  }
}


// Add the filter to check if the comment meta data has been filled or not



//Add an edit option in comment edit screen  


function extend_comment_add_meta_box() {
    add_meta_box( 'title', __( 'Review Details','vibe' ), 'extend_comment_meta_box', 'comment', 'normal', 'high' );
}
 
function extend_comment_meta_box ( $comment ) {

    if(get_post_type($comment->comment_post_ID) != 'course')  
      return;

    $title = get_comment_meta( $comment->comment_ID, 'review_title', true );
    $rating = get_comment_meta( $comment->comment_ID, 'review_rating', true );
    wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
    ?>
    <p>
        <label for="title"><?php _e( 'Review Title','vibe' ); ?></label>
        <input type="text" name="review_title" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
    </p>
    <p>
        <label for="rating"><?php _e( 'Rating ','vibe' ); ?></label>
      <span class="commentratingbox">
      <?php for( $i=1; $i <= 5; $i++ ) {
        echo '<span class="commentrating"><input type="radio" name="review_rating" id="rating" value="'. $i .'"';
        if ( $rating == $i ) echo ' checked="checked"';
        echo ' />'. $i .' </span>'; 
        }
      ?>
      </span>
    </p>
    <?php
}

// Update comment meta data from comment edit screen 


function extend_comment_edit_metafields( $comment_id ) {

  if(get_post_type($_POST['comment_post_ID']) != 'course')    
                return;

  if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;
 
  if ( ( isset( $_POST['review_title'] ) ) && ( $_POST['review_title'] != '') ):
  $title = wp_filter_nohtml_kses($_POST['review_title']);
  update_comment_meta( $comment_id, 'review_title', $title );
  else :
  delete_comment_meta( $comment_id, 'review_title');
  endif;

  if ( ( isset( $_POST['review_rating'] ) ) && ( $_POST['review_rating'] != '') ):
  $rating = wp_filter_nohtml_kses($_POST['review_rating']);
  update_comment_meta( $comment_id, 'review_rating', $rating );
  else :
  delete_comment_meta( $comment_id, 'review_rating');
  endif;
  
}

// Add the comment meta (saved earlier) to the comment text 
// You can also output the comment meta values directly in comments template  


function modify_comment( $text ){

  global $comment;

  if(get_post_type($comment->comment_post_ID) == 'course'){
    if( $commenttitle = get_comment_meta( get_comment_ID(), 'review_title', true ) ) {
      $commenttitle = '<strong>' . esc_attr( $commenttitle ) . '</strong><br/>';
      $text = $commenttitle . $text;
    } 
    if( $commentrating = get_comment_meta( get_comment_ID(), 'review_rating', true ) ) {
      $text .= '<div class="comment-rating star-rating">';
      for($i=0;$i<5;$i++){
        if($commentrating > $i)
          $text .='<span class="fill"></span>';
        else
          $text .='<span></span>';
      }
      $text .='</div>';
      return $text;   
    }  
  }
  return $text;   
}

function calculate_ratings($comment_id) {
  $comment_object =get_comment($comment_id);
  if(get_post_type($comment_object->comment_post_ID) == 'course'){
     
      if(function_exists('bp_course_get_course_reviews')){
          $calculate_reviews=bp_course_get_course_reviews('id='.$comment_object->comment_post_ID);
      }
  }
}