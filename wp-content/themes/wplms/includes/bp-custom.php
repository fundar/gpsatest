<?php


add_action( 'bp_setup_nav', 'vibe_bp_hide_tabs', 15 );

function vibe_bp_hide_tabs(){

   $flag=1;
   $hide=vibe_get_option('activity_tab');
   if(isset($hide)){
      $flag=0;
      switch($hide){
         case 1:
            if(is_user_logged_in()) $flag=1;
         break;
         case 2:
            if(current_user_can('edit_posts')) $flag=1;
         break;
         case 3:
            if(current_user_can('manage_options')) $flag=1;
         break;
         case 0: $flag=1;
         break;
      }
   }
   if(!$flag && bp_is_active( 'groups' ) && !bp_is_my_profile()){
      bp_core_remove_nav_item('activity');   
   }

   $flag=1;
   $hide=vibe_get_option('groups_tab');
   if(isset($hide)){
      $flag=0;
      switch($hide){
         case 1:
            if(is_user_logged_in()) $flag=1;
         break;
         case 2:
            if(current_user_can('edit_posts')) $flag=1;
         break;
         case 3:
            if(current_user_can('manage_options')) $flag=1;
         break;
         case 0: $flag=1;
         break;
      }
   }
   if(!$flag && bp_is_active( 'groups' ) && !bp_is_my_profile()){
      bp_core_remove_nav_item('groups');   
   }
   
   $flag=1;
   $hide=vibe_get_option('forums_tab');
   if(isset($hide)){
      $flag=0;
      switch($hide){
         case 1:
            if(is_user_logged_in()) $flag=1;
         break;
         case 2:
            if(current_user_can('edit_posts')) $flag=1;
         break;
         case 3:
            if(current_user_can('manage_options')) $flag=1;
         break;
         case 0: $flag=1;
         break;
      }
   }
   if(!$flag && bp_is_active( 'groups' ) && !bp_is_my_profile()){
      bp_core_remove_nav_item('forums');   
   }
}

add_action('bp_before_profile_content','show_profile_snapshot');

function show_profile_snapshot(){
   global $bp;

   
   $user_id=bp_displayed_user_id();

  $bids=vibe_sanitize(get_user_meta($user_id,'badges',false));
  if(isset($bids) && is_Array($bids) && count($bids)){
      echo '<div class="badges"><h6>'.__('Badges','vibe').'</h6>';
      echo '<ul>';
        foreach($bids as $bid){

            $b=bp_get_course_badge($bid);
            $badge=wp_get_attachment_info($b); 
            $badge_url=wp_get_attachment_image_src($b);
            if(isset($badge) && is_numeric($b))
            echo '<li><a class="tip" title="'.get_post_meta($bid,'vibe_course_badge_title',true).'"><img src="'.$badge_url[0].'" title="'.$badge['title'].'"/></li>';

        }
      echo '</ul>';
      echo '</div>';
   }   

  
   $certis=vibe_sanitize(get_user_meta($user_id,'certificates',false));
   
     if(isset($certis) && is_Array($certis) && count($certis)){
          echo '<div class="certifications"><h6>'.__('Certifications','vibe').'</h6><ul>';
          if(isset($certis) && is_Array($certis)) 
           foreach($certis as $certi){
                  echo '<li><a href="'.bp_get_course_certificate('user_id='.$user_id.'&course_id='.$certi).'" class="ajax-certificate"><i class="icon-certificate-file"></i><span>'.get_the_title($certi).'</span></a></li>';

           }
         echo '</ul></div>';  
      }
   

   if(user_can($user_id,'edit_posts')){
      echo '<div class="instructor_line">
      <h3><a href="'.get_author_posts_url($user_id).'">'.__('Check all Courses created by ','vibe').bp_core_get_user_displayname($user_id).' <i class="icon-plus-1"></i></a></h3>
      </div>';
   }
}

add_action('bp_group_options_nav','vibe_course_group_link',1,1);

function vibe_course_group_link(){
   global $bp,$wpdb;
   $course_query= $wpdb->get_results( $wpdb->prepare("SELECT post_id from {$wpdb->postmeta} WHERE meta_key='vibe_group' AND meta_value='%d'",$bp->groups->current_group->id));
   if(is_array($course_query) && isset($course_query[0]->post_id))
      echo '<li id="course-li"><a id="admin" href="'.get_permalink($course_query[0]->post_id).'" title="'.get_the_title($course_query[0]->post_id).'">'.__('Course ','vibe').'</a></li>';
}

function bp_dtheme_setup() {
   if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
      // Group buttons
      if ( bp_is_active( 'groups' ) ) {
         $groups_check = vibe_get_option('enable_groups_join_button');

         if(isset($groups_check) && $groups_check)
          add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
      }

   }
}

add_action( 'after_setup_theme', 'bp_dtheme_setup' );

?>