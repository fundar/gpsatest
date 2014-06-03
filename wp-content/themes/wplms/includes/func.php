<?php

/**
 * FILE: func.php 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 */

/// BEGIN AJAX HANDELERS
add_action( 'wp_ajax_reset_googlewebfonts', 'reset_googlewebfonts' );
	function reset_googlewebfonts(){ 
            echo "reselecting..";
            $r = get_option('google_webfonts');
            if(isset($r)){
                delete_option('google_webfonts');
            }
                die();
}


add_filter('wp_title', 'set_page_title');

function set_page_title($title) { 
  if(is_404()){
      if (is_page_template('thankyou.php') || isset($_GET['key'])) 
     return __('Thank You for Purchasing, you order has been recieved, ','vibe');   
  else
     return $title;
  }
}

add_action( 'pre_get_posts', 'course_search_results' );

function course_search_results($query){

  if(!$query->is_search())
    return $query;

  $course_cat = $_GET['course-cat'];
  if(isset($course_cat) && $course_cat !='*' && $course_cat !=''){
    $query->set('course-cat', $course_cat);
  }

  $instructor = $_GET['instructor'];
  if(isset($instructor) && $instructor !='*' && $instructor !=''){
    $query->set('author', $instructor);
  }


  return $query;
}


function restrict_questions_answers( $comments_query ) {

    if(get_post_type($comments_query->post_id) != 'question')
      return $comments_query;
}

add_action( 'pre_get_comments', 'restrict_questions_answers' );


add_action( 'template_redirect', 'vibe_product_woocommerce_direct_checkout' );
function vibe_product_woocommerce_direct_checkout()
{   
  if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
        $check=vibe_get_option('direct_checkout');

    if(isset($check) && $check == '2' || $check == 2){
      if( is_single() && get_post_type() == 'product' ){
          global $woocommerce;
          $woocommerce->cart->add_to_cart( get_the_ID() );
          $checkout_url = $woocommerce->cart->get_checkout_url();
          wp_redirect( $checkout_url);
          exit();
      }
    }
  }
}


add_action( 'wp_ajax_tour_number', 'inc_tour_number' );
  function inc_tour_number(){ 
            $r = get_option('tour_number');
            if(isset($r)){ $r++;
                update_option('tour_number', $r);
            }else
                add_option('tour_number', 0);
                die();
}
            
add_action( 'wp_ajax_import_data', 'import_data' );
  function import_data(){
    $name = stripslashes($_POST['name']);
                $code = base64_decode(trim($_POST['code'])); 
                if(is_string($code))
                    $code = unserialize ($code);
                
                $value = get_option($name);
                if(isset($value)){
                                update_option($name,$code);
                }else{
                    echo "Error, Option does not exist !";
                }
                die();
            }
add_action( 'wp_ajax_import_sample_data', 'import_sample_data' );
function import_sample_data(){
    $file = stripslashes($_POST['file']);
                include 'importer/vibeimport.php';
                vibe_import($file);
                die();
}


add_filter('get_avatar','change_avatar_css');

function change_avatar_css($class) {
$class = str_replace("class='avatar", "class='retina_avatar zoom animate", $class) ;
return $class;
}

function the_sub_title($id=NULL){
  global $post;
  if(isset($id)){
    $return=getPostMeta($id,'vibe_subtitle');
  }else{
    $return=getPostMeta($post->ID,'vibe_subtitle');  
  }
  
  if(!isset($return) || !$return){
    $return=0;
  }else{
    echo $return;  
  }
  
}

if(!function_exists('vibe_socialicons')){
    function vibe_socialicons(){
        global $vibe_options; $html='';
        $social_icons = vibe_get_option('social_icons');
        $html = '<ul class="social">';
        if(is_array($social_icons) && is_array($social_icons['social'])){
         foreach($social_icons['social'] as $icon){ 
          $i=key($social_icons['social']);
            $url=$social_icons['url'][$i];
            $html .= '<li><a href="'.$url.'" title="'.$icon.'" class="'.$icon.'"><i class="icon-'.$icon.'"></i></a></li>';
            }
         }
        $html .= '</ul>';
        return $html;  
     } 
}

if(!function_exists('vibe_inpagemenu')){
    function vibe_inpagemenu(){
        global $post;
        $show_menu=getPostMeta($post->ID,'vibe_show_inpagemenu');
        if($show_menu && $show_menu == 'S'){
            $inpage_menu=getPostMeta($post->ID,'vibe_inpage_menu');
            $inpage=array();
            foreach($inpage_menu as $item){
                $nitem = preg_replace('/[^a-zA-Z0-9\']/', '_', $item);
                $nitem = str_replace("'", '', $nitem);
                $inpage[$nitem]=$item;
            }
            return $inpage;
        }else
            return 0;
    }
    
}


if(!function_exists('get_all_taxonomy_terms')){
    function get_all_taxonomy_terms(){
        $taxonomies=get_taxonomies('','objects'); 
        $termchildren = array();
        foreach ($taxonomies as $taxonomy ) {
            $toplevelterms = get_terms($taxonomy->name, 'hide_empty=0&hierarchical=0&parent=0');
          foreach ($toplevelterms as $toplevelterm) {
                    $termchildren[ $toplevelterm->slug] = $taxonomy->name.' : '.$toplevelterm->name;
            }
            }
            
    return $termchildren;  
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


function set_wpmenu(){
    echo '<p style="padding:20px 0 10px;text-align:center;">Setup Menus</p>';
}



function get_image_id($image_url) {
    global $wpdb;
    
    $attachment = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid='%s'",$image_url));
  if($attachment)
        return $attachment;
    else
        return false;
}


/*==== End Show Values ====*/


add_filter('widget_text', 'do_shortcode');


function custom_excerpt($chars=0, $id = NULL) {
	global $post;
        if(!isset($id)) $id=$post->ID;
	$text = get_post($id);
        
	if(strlen($text->post_excerpt) > 10)
            $text = $text->post_excerpt . " ";
        else
            $text = $text->post_content . " ";
        
	$text = strip_tags($text);
        $ellipsis = false;
        $text = strip_shortcodes($text);
	if( strlen($text) > $chars )
		$ellipsis = true;

	$text = substr($text,0,$chars);

	$text = substr($text,0,strrpos($text,' '));

	if( $ellipsis == true && $chars > 1)
		$text = $text . "...";
        
	return $text;
}



  


if(!function_exists('pagination')){
function pagination($pages = '', $range = 4)
{  
     $showitems = ($range * 2)+1;  
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
 
     if(1 != $pages)
     {
         echo "<div class=\"pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</div>\n";
     }
}
}

if(!function_exists('get_current_post_type')){
function get_current_post_type() {
  global $post, $typenow, $current_screen;
  
  //lastly check the post_type querystring
  if( isset( $_REQUEST['post_type'] ) )
    return sanitize_key( $_REQUEST['post_type'] );
  
  elseif ( isset( $_REQUEST['post'] ) )
    return get_post_type($_REQUEST['post']);
  
  elseif ( $post && $post->post_type )
    return $post->post_type;
  
  elseif( $typenow )
    return $typenow;

  //check the global $current_screen object - set in sceen.php
  elseif( $current_screen && $current_screen->post_type )
    return $current_screen->post_type;

  //we do not know the post type!
  return 'post';
}
}

if(!function_exists('vibe_sanitize')){
  function vibe_sanitize($array){
    if(isset($array[0]) && is_array($array[0]))
      return $array[0];
  }
}

if(!function_exists('all_course_page_title')){
  function all_course_page_title(){
    echo '
    <h1>'.__('Course Directory','vibe').'</h1>
    <h5>'.__('All Courses by all instructors','vibe').'</h5>
    ';
  }
}

function vibe_breadcrumbs() {  
    global $post;
   
    /* === OPTIONS === */  
    $text['home']     = 'Home'; // text for the 'Home' link  
    $text['category'] = '%s'; // text for a category page  
    $text['search']   = '%s'; // text for a search results page  
    $text['tag']      = '%s'; // text for a tag page  
    $text['author']   = '%s'; // text for an author page  
    $text['404']      = 'Error 404'; // text for the 404 page  
  
    $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show  
    $showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show  
    $delimiter   = ''; // delimiter between crumbs  
    $before      = '<li class="current">'; // tag before the current crumb  
    $after       = '</li>'; // tag after the current crumb  
    /* === END OF OPTIONS === */  
  
    global $post;  
    $homeLink = home_url();  
    $linkBefore = '<li>';  
    $linkAfter = '</li>';  
    $linkAttr = ' rel="v:url" property="v:title"';  
    $link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;  
  
    if (is_home() || is_front_page()) {  
  
        if ($showOnHome == 1) echo '<div id="crumbs"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';  
  
    } else {  
  
        echo '<ul class="breadcrumbs">' . sprintf($link, $homeLink, $text['home']) . $delimiter;  
  
        if ( is_category() ) {  
            $thisCat = get_category(get_query_var('cat'), false);  
            if ($thisCat->parent != 0) {  
                $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);  
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
                echo $cats;  
            }  
            echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;  
  
        } elseif ( is_search() ) {  
            echo $before . sprintf($text['search'], get_search_query()) . $after;  
  
        } elseif ( is_day() ) {  
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;  
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;  
            echo $before . get_the_time('d') . $after;  
  
        } elseif ( is_month() ) {  
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;  
            echo $before . get_the_time('F') . $after;  
  
        } elseif ( is_year() ) {  
            echo $before . get_the_time('Y') . $after;  
  
        } elseif ( is_single() && !is_attachment() ) {  

            if ( get_post_type() != 'post' ) {  

                if(get_post_type() == 'product'){

                    $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
                    $post_type = get_post_type_object(get_post_type());  
                    printf($link, $homeLink . '/' . basename($shop_page_url) . '/', $post_type->labels->singular_name);  
                    if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after; 

                }else{

                    $post_type = get_post_type_object(get_post_type());  
                    $slug = $post_type->rewrite;  
                    printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);  
                    if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after; 

                }
            } else {  

                $cat = get_the_category(); $cat = $cat[0];  
                $cats = get_category_parents($cat, TRUE, $delimiter);  
                if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);  
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
                echo $cats;  
                if ($showCurrent == 1) echo $before . get_the_title() . $after;  
            }  
  
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {  
            $post_type = get_post_type_object(get_post_type());  

            echo $before . $post_type->labels->singular_name . $after;  
  
        } elseif ( is_attachment() ) {  
            $parent = get_post($post->post_parent);  
            $cat = get_the_category($parent->ID); 
            if(isset($cat[0])){
            $cat = $cat[0];  
            $cats = get_category_parents($cat, TRUE, $delimiter);  
            $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);  
            $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);  
            echo $cats;  
            }
            printf($link, get_permalink($parent), __('Attachment','vibe'));  
            
            if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;  
  
        } elseif ( is_page() && !$post->post_parent ) {  
            if ($showCurrent == 1) echo $before . get_the_title() . $after;  
  
        } elseif ( is_page() && $post->post_parent ) {  
            $parent_id  = $post->post_parent;  
            $breadcrumbs = array();  
            while ($parent_id) {  
                $page = get_page($parent_id);  
                $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));  
                $parent_id  = $page->post_parent;  
            }  
            $breadcrumbs = array_reverse($breadcrumbs);  
            for ($i = 0; $i < count($breadcrumbs); $i++) {  
                echo $breadcrumbs[$i];  
                if ($i != count($breadcrumbs)-1) echo $delimiter;  
            }  
            if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;  
  
        } elseif ( is_tag() ) {  
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;  
  
        } elseif ( is_author() ) {  
            global $author;  
            $userdata = get_userdata($author);  
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;  
  
        } elseif ( is_404() ) {  
            echo $before . $text['404'] . $after;  
        }  
  
        if ( get_query_var('paged') ) {  
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';  
            echo __('Page','vibe') . ' ' . get_query_var('paged');  
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';  
        }  
  
        echo '</ul>';  
  
    }  
} // end vibe_breadcrumbs()  


function get_all_testimonials(){
  $args=array(
    'post_type' => 'testimonials',
    'orderby'   => 'modified',
    'numberposts' => 999
    );

  $testimonials=get_posts($args);

  $testimonial_array=array();
  foreach($testimonials as $testimonial){
    $testimonial_array[$testimonial->ID]=$testimonial->post_title;
  }
  return $testimonial_array;
}


function vbp_current_user_notification_count() {
    $notifications = bp_notifications_get_notifications_for_user(bp_loggedin_user_id(), 'object');
    $count = !empty($notifications) ? count($notifications) : 0;
   return $count;
}

// WOO COMMERCE  HANDLES
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
add_action('woocommerce_before_main_content','vibe_breadcrumbs',20,0);

remove_action('woocommerce_pagination', 'woocommerce_pagination', 10);
add_action( 'woocommerce_pagination', 'pagination', 10);

/**
* Set custom add to cart redirect
*/


add_action('woocommerce_init', 'vibe_woocommerce_direct_checkout');

if(!function_exists('vibe_woocommerce_direct_checkout')){
  function vibe_woocommerce_direct_checkout(){
    if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
        $check=vibe_get_option('direct_checkout');
        if(isset($check) && ($check == 1  || $check == '1')){
          update_option('woocommerce_cart_redirect_after_add', 'no');
          update_option('woocommerce_enable_ajax_add_to_cart', 'no');
          add_filter('add_to_cart_redirect', 'vcustom_add_to_cart_redirect');
        }
    }else
      return;
  }
}

function vcustom_add_to_cart_redirect() {
    return get_permalink(get_option('woocommerce_checkout_page_id'));
}


add_action('wplms_course_unit_meta','vibe_custom_print_button');
if(!function_exists('vibe_custom_print_button')){
    function vibe_custom_print_button(){
      $print_html='<a href="#" class="print_unit"><i class="icon-printer-1"></i></a>';
        echo apply_filters('wplms_unit_print_button',$print_html);  
      }
}

?>
