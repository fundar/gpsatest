<?php

/**
 * FILE: ajaxcalls.php 
 * Created on Oct 31, 2013 at 3:33:49 PM 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 */

if(!function_exists('getPostMeta')){
    /// POST Views
function getPostMeta($postID,$count_key = 'post_views_count'){
    $count = get_post_meta($postID, $count_key, true);
    
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
   }
   return $count;
}

}    


if(!function_exists('wp_get_attachment_info')){
function wp_get_attachment_info( $attachment_id ) {
       
	$attachment = get_post( $attachment_id );
        if(isset($attachment)){
	return array(
		'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
		'caption' => $attachment->post_excerpt,
		'description' => $attachment->post_content,
		'href' => get_permalink( $attachment->ID ),
		'src' => $attachment->guid,
		'title' => $attachment->post_title
	);
       }
}


}
add_action( 'wp_ajax_vibe_popup', 'vibe_ajax_popup' );
add_action( 'wp_ajax_nopriv_vibe_popup', 'vibe_ajax_popup' );
	function vibe_ajax_popup(){ 

                $id = stripslashes($_GET['id']);
                $npopup = get_page($id);
                $post_content=apply_filters('the_content', $npopup->post_content);;
                echo '<div class="popup_content">';
                echo do_shortcode($post_content).'</div>';
                die();
}

//Ajax Handle Contact Form

add_action('wp_ajax_vibe_form_submission', 'vibe_form_submission');
add_action( 'wp_ajax_nopriv_vibe_form_submission', 'vibe_form_submission' );

function vibe_form_submission() {
    global $vibe_options;	
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "From: get_bloginfo('name')<$to>". "\r\n";

    $isocharset = $_POST['isocharset'];
    if($isocharset){
        $data = json_decode(stripslashes(urldecode($_POST['data'])));
        $labels = json_decode(stripslashes(urldecode($_POST['label'])));
        $headers .= "Content-type: text/html; charset=utf8" . "\r\n";
    }else{
        $data = json_decode(stripslashes(utf8_decode($_POST['data'])));
        $labels = json_decode(stripslashes(utf8_decode($_POST['label'])));
        $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
    }

    $subject=stripslashes($_POST['subject']);
    if(!isset($subject))
        $subject = __('Contact Form Submission','vibe-shortcodes');
    
    $to=stripslashes($_POST['to']);
    if(!isset($to))
        $to = get_option('admin_email'); 


    for($i=1;$i<count($data);$i++){
        $message .= $labels[$i].' : '.$data[$i].' <br />';
    }
   
   
    $flag=wp_mail( $to, $subject, $message, $headers );
    if ( $flag ) {
        echo "<span style='color:#0E7A00;'>".__("Message sent!","vibe-shortcodes")." </span>";
    }else{
    	echo __("Unable to send message! Please try again later..","vibe-shortcodes");
    	}
die();
}





//Vibe Grid Infinite Scroll
add_action( 'wp_ajax_grid_scroll', 'vibe_grid_scroll' );
add_action( 'wp_ajax_nopriv_grid_scroll', 'vibe_grid_scroll' );
    function vibe_grid_scroll(){ 
            $atts = json_decode(stripslashes($_POST['args']),true);
            $output ='';
            $paged = stripslashes($_POST['page']);
            $paged++;
            
        if(!isset($atts['post_ids']) || count($atts['post_ids']) > 0){
            if(isset($atts['term']) && isset($atts['taxonomy']) && $atts['term'] !='nothing_selected'){
               
            if(isset($atts['taxonomy']) && $atts['taxonomy']!=''){
                         if($atts['taxonomy'] == 'category'){
                             $atts['taxonomy']='category_name'; 
                             }
                          if($atts['taxonomy'] == 'tag'){
                             $atts['taxonomy']='tag_name'; 
                             }   
                     }
           
                             
          $query_args=array( 'post_type' => $atts['post_type'],$atts['taxonomy'] => $atts['term'], 'posts_per_page' => $atts['grid_number'],'paged' => $paged);
          
        }else
           $query_args=array('post_type'=>$atts['post_type'], 'posts_per_page' => $atts['grid_number'],'paged' => $paged);
        
        $style= '';
        if(isset($atts['masonry']) && $atts['masonry']){
            $style= 'style="width:'.$atts['column_width'].'px;"'; 
        }
        
        $query_args =  apply_filters('wplms_grid_course_filters',$query_args);

        query_posts($query_args);
        while ( have_posts() ) : the_post();
        global $post;
        $output .= '<li '.(isset($atts['grid_columns'])?'class="'.$atts['grid_columns'].'"':'').' '.$style.'>';
        $output .= thumbnail_generator($post,$atts['featured_style'],$atts['grid_columns'],$atts['grid_excerpt_length'],$atts['grid_link'],$atts['grid_lightbox']);
        $output .= '</li>';
        
        endwhile;
        wp_reset_query();
        wp_reset_postdata();
        
        echo $output;
        }else{
            echo '0';
        }
                die();
}



if(!function_exists('animation_effects')){
function animation_effects(){
    $animate=array(
                                        ''=>'none',
                                        'cssanim flash'=> 'Flash',
                                        'zoom' => 'Zoom',
                                        'scale' => 'Scale',
                                        'slide' => 'Slide (Height)', 
                                        'expand' => 'Expand (Width)',
                                        'cssanim shake'=> 'Shake',
                                        'cssanim bounce'=> 'Bounce',
                                        'cssanim tada'=> 'Tada',
                                        'cssanim swing'=> 'Swing',
                                        'cssanim wobble'=> 'Flash',
                                        'cssanim wiggle'=> 'Flash',
                                        'cssanim pulse'=> 'Flash',
                                        'cssanim flip'=> 'Flash',
                                        'cssanim flipInX'=> 'Flip Left',
                                        'cssanim flipInY'=> 'Flip Top',
                                        'cssanim fadeIn'=> 'Fade',
                                        'cssanim fadeInUp'=> 'Fade Up',
                                        'cssanim fadeInDown'=> 'Fade Down',
                                        'cssanim fadeInLeft'=> 'Fade Left',
                                        'cssanim fadeInRight'=> 'Fade Right',
                                        'cssanim fadeInUptBig'=> 'Fade Big Up',
                                        'cssanim fadeInDownBig'=> 'Fade Big Down',
                                        'cssanim fadeInLeftBig'=> 'Fade Big Left',
                                        'cssanim fadeInRightBig'=> 'Fade Big Right',
                                        'cssanim bounceInUp'=> 'Bounce Up',
                                        'cssanim bounceInDown'=> 'Bounce Down',
                                        'cssanim bounceInLeft'=> 'Bounce Left',
                                        'cssanim bounceInRight'=> 'Bounce Right',
                                        'cssanim rotateIn'=> 'Rotate',
                                        'cssanim rotateInUpLeft'=> 'Rotate Up Left',
                                        'cssanim rotateInUpRight'=> 'Rotate Up Right',
                                        'cssanim rotateInDownLeft'=> 'Rotate Down Left',
                                        'cssanim rotateInDownRight'=> 'Rotate Down Right',
                                        'cssanim speedIn'=> 'Speed In',
                                        'cssanim rollIn'=> 'Roll In',
                                        'ltr'=> 'Left To Right',
                                        'rtl' => 'Right to Left', 
                                        'btt' => 'Bottom to Top',
                                        'ttb'=>'Top to Bottom',
                                        'smallspin'=> 'Small Spin',
                                        'spin'=> 'Infinite Spin'
                                        );
    return $animate;
}
}
?>