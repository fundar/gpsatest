<?php

if(!class_exists('WPLMS_Customizer_Plugin_Class'))
{   
    class WPLMS_Customizer_Plugin_Class  // We'll use this just to avoid function name conflicts 
    {
            
        public function __construct()
        {   
            add_action('init', array($this,'define_buddypress_constants'));
            
            add_action('wplms_single_course_details',array($this,'custom_course_details'));
            add_action('wplms_course_unit_meta',array($this,'custom_unit_meta'));

            add_action('bp_init',array($this,'manage_profile_snapshot')); 

            add_action('bp_before_directory_course_page',array($this,'redirect_if_not_loggedin'));

            add_action('bp_init',array($this,'wplms_remove_instructor_button'));  // Removes the Become and Instructor Button

            add_action('wplms_be_instructor_button',array($this,'wplms_be_instructor_button')); // Adds Extra function to run in place of Instructor button

            
            add_action('wplms_course_sidebar_hook',array($this,'wplms_course_search_sidebar'));

            add_action('bp_sidebar_me',array($this,'wplms_woocommerce_orders_link'));

            add_filter('vibe_shortcodes_team_social',array($this,'vibe_shortcodes_custom_team_social'));
           
           //add_filter('wplms_unit_mark_complete',array($this,'custom_wplms_unit_mark_complete'),1,3);
            
            add_filter('wplms_course_credits',array($this,'wplms_customizer_course_credits'),1,2);
            add_filter('wplms_course_nav_menu',array($this,'wplms_course_nav_menu'),1,1);

            add_filter('wplms_display_course_instructor',array($this,'wplms_display_course_instructor'),1,2);
            add_filter('wplms_display_course_instructor_avatar',array($this,'wplms_display_course_instructor_avatar'),1,2);

            add_filter('vibe_thumb_instructor_meta',array($this,'vibe_thumb_instructor_meta'),1,2);

            add_filter('wplms_free_course_price',array($this,'wplms_free_course_price'),1,1);

            add_filter('wplms_unit_print_button',array($this,'wplms_unit_print_button'),1,1);
            add_filter('wplms_course_meta',array($this,'wplms_course_meta'),1,1);

            add_filter('wplms_friendly_time',array($this,'wplms_friendly_time'),1,2);

            add_filter('wplms_course_excerpt_limit',array($this,'wplms_course_excerpt_limit'),1,1);

            //add_filter('wplms_display_course_instructor',array($this,'wplms_multiple_instructors'),1,2);

            add_filter('wplms_course_product_id',array($this,'wplms_course_product_id'),1,2);  


            add_filter('login_redirect',array($this, 'buddypress_login_redirection'),100,3);  


            // Edit Thumbnail Functions
            add_filter('vibe_thumb_date',array($this,'vibe_thumb_date'),1,2);
            add_filter('vibe_thumb_heading',array($this,'vibe_thumb_heading'),1,2);
            add_filter('vibe_thumb_featured_image',array($this,'vibe_thumb_featured_image'),1,2);
            add_filter('vibe_thumb_rating',array($this,'vibe_thumb_rating'),1,3);
            add_filter('vibe_thumb_reviews',array($this,'vibe_thumb_reviews'),1,2);

           
        } // END public function __construct

        /**
         * DEFINE CONSTANTS
         */
        function define_buddypress_constants(){
            // Redefine BuddyPress Constants
            if ( !defined( 'BP_AVATAR_THUMB_WIDTH' ) )
            define( 'BP_AVATAR_THUMB_WIDTH', 150 ); //change this with your desired thumb width

            if ( !defined( 'BP_AVATAR_THUMB_HEIGHT' ) )
            define( 'BP_AVATAR_THUMB_HEIGHT', 150 ); //change this with your desired thumb height

            if ( !defined( 'BP_AVATAR_FULL_WIDTH' ) )
            define( 'BP_AVATAR_FULL_WIDTH', 460 ); //change this with your desired full size,weel I changed it to 260 :) 

            if ( !defined( 'BP_AVATAR_FULL_HEIGHT' ) )
            define( 'BP_AVATAR_FULL_HEIGHT', 460 ); //change this to default height for full avatar

            if ( ! defined( 'BP_DEFAULT_COMPONENT' ) )
            define( 'BP_DEFAULT_COMPONENT', 'profile' );
        }


        
         /**
         * Objective : Show Extra Meta information for units.
         * Developer Notes: 
         * Show hide, Print button using this control
         * returns : THE PRINT HTML
         */
        function custom_unit_meta(){
          
        }
        function wplms_course_search_sidebar(){
           /* echo '<div class="col-md-3 right">';
            if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar('mainsidebar') ) : ?>
            <?php endif;
            echo '</div>'; */
        }
        /**
         * Objective : Show Custom Details for a course
         * Developer Notes: 
         * Show custom details for a course, Add List items in the below format. 
         * Grab the icon from the shortcode icon generator
         * returns : '<li><i class="icon-certificate-file"></i>  '.__('Course Certificate','vibe').'</li>'
         */
        function custom_course_details(){
          
        }

        
        /**
         * Objective : This function modifies the front Course Menu. 
         * Developer Notes: 
         * To Remove / ADD custom link simply ADD/Remove item from the returning array
         ** returns : The Menu Array
         */
        
        /*
                THE DEFAULT MENU ARRAY

                $defaults = array(
              'Home' => array(
                                'id' => 'home',
                                'label'=>__('Home','vibe'),
                                'action' => '',
                                'link'=>bp_course_permalink(),
                            ),
              'curriculum' => array(
                                'id' => 'curriculum',
                                'label'=>__('Curriculum','vibe'),
                                'action' => 'curriculum',
                                'link'=>bp_course_permalink(),
                            ),
              'members' => array(
                                'id' => 'members',
                                'label'=>__('Members','vibe'),
                                'action' => 'members',
                                'link'=>bp_course_permalink(),
                            ),
              );
         */
        
        function wplms_course_nav_menu($menu_array){
            //unset($menu_array['members']);
            return $menu_array;
        }
        function wplms_course_product_id($pid,$id=NULL){
            return $pid; // This is the Page ID of the page with pricing table
        }  
        /**
         * Objective : This function modifies the Instructor shown for each course. 
         * Developer Notes: 
         * To grab all instructors in a course, grab the course curriculum,
         * returns : The HTML for Instructors
         * Default Code:
         * $special = bp_get_profile_field_data('field='.$field.'&user_id='.$instructor_id);
         * $instructor_title = '<h5 class="course_instructor"><a href="'.bp_core_get_user_domain($instructor_id) .'">'.$displayname.'<span>'.$special.'</span></a></h5>';
         */
        function wplms_display_course_instructor($instructor_title,$course_id=NULL){
            //$instructor_title='';
            return $instructor_title;
        }
        function wplms_display_course_instructor_avatar($instructor_avatar,$course_id=NULL){
            //$instructor_avatar='';
            return $instructor_avatar;
        }

        function vibe_thumb_instructor_meta($instructor_meta,$featured_style=NULL){
           // $instructor_meta='';
            return $instructor_meta;
        }
        /**
         * Objective : This function modifies the Price value shown for each course. 
         * Developer Notes: 
         * Here's how to connect with your custom eCommerce plugin.
         * Use your custom eCommerce Plugin, create a product in it and set a custom field with Course ID.
         * Similarly set a custom field for the course with the product id. 
         * In this function: Grab the Product price from the associated product for the course.
         * returns : The price HTML
         */
        
        function wplms_customizer_course_credits($price_html,$course_id=NULL){
            return $price_html;
        }

        /**
         * Objective : Shows the Price value for Free Courses
         * Developer Notes: 
         * Enter the Text value: Free, Livre etc.. to be shown in place for Free Courses
         * returns : The price value for free course
         */
        function wplms_free_course_price($price_text){
            //$price_text= 'Livre';
            return $price_text;
        }

        /**
         * Objective : This controls print button for each unit in course
         * Developer Notes: 
         * Show hide, Print button using this control
         * returns : THE PRINT HTML
         */
        function wplms_unit_print_button($print_html){
            return $print_html;
        }


        function wplms_woocommerce_orders_link(){
           /* $myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
            if ( $myaccount_page_id ) {
              echo '<ul>
                       <li><a href="'.get_permalink( $myaccount_page_id ).'"><i class="icon-bag"></i> '.__('Orders','vibe').'</a></li>
                    </ul>';
            } */
        }
        /**
         * Objective : This controls Course meta Information: Ratings & Student Count
         * Developer Notes: 
         * Show hide, Print button using this control
         * returns : THE COURSE META HTML
         */
        function wplms_course_meta($meta_html){
            return $meta_html;
        }
        /**
         * Objective : This controls accessibility of All course page
         * Developer Notes: 
         * Enable the commented code to disable the accessibility of course directory
         * returns : Redirects the user to the Site home page
         */
        function redirect_if_not_loggedin(){
            /*
            if(!is_user_logged_in()){
                $location = get_site_url();
                echo '<script type="text/javascript">
                       <!--
                          window.location= "'. $location .'";
                       //-->
                       </script>';
                exit();
            }*/
        }
        /**
         * Objective : Control the Visbility of the Profile Certificates and Badges to users
         * Developer Notes: 
         * Enable the commented code and add extra parameters in if condition to enable the certificate and Badge visibility
         * in students profile section
         * returns : The Badges and Certificates
         */
        function manage_profile_snapshot(){
            //if(!current_user_can('edit_posts'))
              //  remove_action('bp_before_profile_content','show_profile_snapshot');
        }  
        /**
         * Objective : This controls visibility of "Become an Instructor button"
         * Developer Notes: 
         * Enable the commented code to disable the Instructor button
         * returns : Disables the Instructor button
         */
        function wplms_remove_instructor_button(){
           //remove_action('wplms_be_instructor_button','wplms_be_instructor_button'); 
        }

        /**
         * Objective : This controls the Instructor Buttons section
         * Developer Notes: 
         * Add some HTML here to be shown in Instructor Button area in 
         * returns : Show extra HTML in the Instructor button section in Course Directory etc.
         */
        function wplms_be_instructor_button(){
               //Echo something to show in place of instructor button
        }
        /**
         * Objective : This controls the excerpt length for the Course description in Course page
         * Developer Notes: 
         * Change the $no value to change the visibile description area like $no = 800;
         * returns : Controls the display of the Course description before the read more link.
         */
        function wplms_course_excerpt_limit($no){
            return $no;
        }

        /**
         * Objective : This controls the time durations shown throughout the WPLMS
         * Developer Notes: 
         * Conver $seconds into readable format like days and weeks and overwrite $time.
         * returns : $time which is the HTML value of calculated time
         */
        function wplms_friendly_time($time,$seconds){

           // if($seconds > 604800)
            //$time = round($seconds/604800).' weeks';
            // Convert Time in seconds to More readable time
            return $time;
        }
        
        /**
         * Objective : This controls the display of date in Thumbnail styles in WPLMS
         * Developer Notes: 
         * Disable enable date using this, return '' to disable date display in a particular featured style
         * Relevant featured style = side, blogpost and ''
         * returns : Date HTML for the post
         */
        function vibe_thumb_date($date_html,$featured_style){
            //if($featured_style == '') // Removes the Date for default featured style
            //    return '';
            return $date_html;
        }
        /**
         * Objective : This controls the display of post Heading in thumbnail styles in WPLMS
         * Developer Notes: 
         * Disable enable heading using this, return '' to disable date display in a particular featured style. 
         * Add extra icons besides the heading : example : http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms/8486-add-icon-to-download-pdf-file
         * Relevant featured style = course,side, blogpost and ''
         * returns : Heading HTML for the post
         */
        function vibe_thumb_heading($heading_html,$featured_style){
               // if($featured_style == '')
                 //   return $heading_html.'<a herf="'.get_permalink().'"><i class="icon-download"></i></a>';
                return $heading_html;
        }
        /**
         * Objective : This controls the display of Featured iamge in Thumbnail styles in WPLMS
         * Developer Notes: 
         * Disable enable featured image using this, return '' to disable date display in a particular featured style,
         * Add extra parameters, display a free label for Free courses.
         * Relevant featured style = course,side, blogpost,images_only and ''
         * returns : Featured Image HTML for the post
         */
        function vibe_thumb_featured_image($featured_html,$featured_style){
            // Code to Add a Free label over courses
            /*if($featured_style == 'course'){ // Just a validation check

                 $course_id=get_the_ID();
                 $free_course = false;
                 $free_course=get_post_meta($course_id,'vibe_course_free',true);
                 if(function_exists('vibe_validate') && vibe_validate($free_course)){
                        $featured_html .='<span class="free_label">FREE</span>';
                 }
            } */
            return $featured_html; 
        }
        /**
         * Objective : This controls the display of rating in Thumbnail styles in WPLMS
         * Developer Notes: 
         * Disable enable rating using this, return '' to disable ratings display. Change the rating into a textual mode
         * Relevant featured style = course
         * returns : Rating HTML for the post
         */
        function vibe_thumb_rating($rating_html,$featured_style,$rating){
               // Rating is -5, -4 and Featured style is course
                return $rating_html;
        }


        function buddypress_login_redirection($redirect_url,$request_url,$user){
            global $bp;
            if ( defined( 'BP_COURSE_SLUG' ) ){
                $custom_redirect_url=bp_core_get_user_domain($user->ID).'/'.BP_COURSE_SLUG;
                return $custom_redirect_url;    
            }
            return $redirect_url;
            
        }  
        /**
         * Objective : This controls the display of review count in Thumbnail styles in WPLMS
         * Developer Notes: 
         * Disable enable date using this, return '' to disable review count display in a particular featured style
         * Relevant featured style = course
         * returns : Reviews HTML for the post
         */
        function vibe_thumb_reviews($review_html,$featured_style){
               // Return '' to hide reviews in thumbnails, Featured style is course
                return $review_html;
        }


        function vibe_shortcodes_custom_team_social($text){
            /*if($text == 'evernote'){
                $text = 'Evernote';
            }*/
            return $text;
        }

        function custom_wplms_unit_mark_complete($mark_unit_html,$unit_id,$course_id){
           /* $units = bp_course_get_curriculum_units($course_id);
            $key = array_search($unit_id,$units);
            $flag=0;

            if($key == 0)  //Check for First Unit
                return $mark_unit_html;


            for($i=0;$i<$key;$i++){ 
                if(!bp_course_check_unit_complete($units[$i]) ){
                    $flag=1;
                }
            }

            if($flag)
                return '';*/

                return $mark_unit_html;
        }

        function wplms_multiple_instructors($instructor_html,$course_id){
            /*$units=array();
            if(function_exists('bp_course_get_curriculum_units')) //Check function available (from version 1.5+ )
            $units = bp_course_get_curriculum_units($course_id);
            $primary_instructor =  get_post_field( 'post_author', $course_id);
            $instructors = array();
            if(count($units)){
                foreach($units as $unit){
                    $instructor=get_post_field( 'post_author', $unit);
                    if(!in_array($instructor,$instructors))
                    $instructors[] = $instructor;
                }
            }
            if(count($instructors)){
                foreach($instructors as $instructor_id){
                    if(function_exists('vibe_get_option'))
                        $field = vibe_get_option('instructor_field');
                    
                    $displayname = bp_core_get_user_displayname($instructor_id);
                    $special = bp_get_profile_field_data('field='.$field.'&user_id='.$instructor_id);

                    
                    if(is_single()){
                        $instructor_avatar='<hr /><div class="item-avatar">'.bp_course_get_instructor_avatar('item_id='.$instructor_id).'</div>';
                    }else{
                        $instructor_avatar=bp_course_get_instructor_avatar('item_id='.$instructor_id);
                    }
                    $instructor_html .= '<span class="clearfix"></span>'.$instructor_avatar.'<h5 class="course_instructor"><a href="'.bp_core_get_user_domain($instructor_id) .'">'.$displayname.'<span>'.$special.'</span></a></h5>';
                }
            } */
            return $instructor_html;
        }
        /**
         * Activate the plugin
         */
        public static function activate()
        {
            // Do stuff you want on plugin activation
        } // END public static function activate

        /**
         * Deactivate the plugin
         */     
        public static function deactivate()
        {
            // Do stuff you want on plugin deactivation
        } // END public static function deactivate


    } // END class WPLMS_Customizer_Class
} // END if(!class_exists('WPLMS_Customizer_Class'))

?>