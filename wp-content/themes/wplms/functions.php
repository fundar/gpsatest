<?php

// Essentials
include_once 'includes/config.php';
include_once 'includes/init.php';

// Register & Functions
include_once 'includes/register.php';
include_once 'includes/func.php';


include_once 'includes/ratings.php';


// Customizer
include_once 'includes/customizer/customizer.php';
include_once 'includes/customizer/css.php';


include_once 'includes/vibe-menu.php';

include_once 'includes/author.php';

if ( function_exists('bp_get_signup_allowed')) {
    include_once 'includes/bp-custom.php';
}

include_once '_inc/ajax.php';

//Widgets
include_once('includes/widgets/custom_widgets.php');
if ( function_exists('bp_get_signup_allowed')) {
 include_once('includes/widgets/custom_bp_widgets.php');
}
include_once('includes/widgets/advanced_woocommerce_widgets.php');
include_once('includes/widgets/twitter.php');
include_once('includes/widgets/flickr.php');

//Misc
include_once 'includes/sharing.php';
include_once 'includes/tincan.php';

// Options Panel
get_template_part('vibe','options');

/*add bookmark*/
function bookmarks($url = false, $title = false) {
	global $wp;
	
	if($url == false) {
		$current_url = add_query_arg($wp->query_string, '', home_url($wp->request));
		$current_url = explode("?", $current_url);
		$current_url = $current_url[0];
		$title       = get_the_title();
	} else {
		$current_url = $url;
	}
	
	if(isset($_GET["msg"]) and $_GET["msg"] == "successful-bookmark") {
		echo '<span class="bookmark-successful">Bookmark added successfully</span>';
	} else {
		echo '<a class="add-bookmark" href="' . home_url() . '/add-bookmark?page=' . $current_url . '&title=' . $title . '">Add to my favorites</a>';
	}
	return true;
}

/*get bookmarks*/
function getBookmarks() {
	if(function_exists('bp_loggedin_user_link') && is_user_logged_in()) {
		echo '<h3>Bookmarks</h3>';
		
		global $wp;
		global $wpdb;
		
		$user_id = bp_loggedin_user_id();
		$myrows  = $wpdb->get_results("SELECT * FROM wp_bookmarks where user_id=$user_id order by bookmark_id desc");
		
		if($myrows) {
			echo '<ul class="ul-bookmarks">';
				foreach($myrows as $row) {
					echo '<li>';
						echo '<a href="' . $row->url . '" title="' . $row->title . '">' . $row->title . '</a>';
					echo '</li>';
				}
			echo '</ul>';
		} else {
			echo '<h2>Not bookmarks yet</h2>';
		}
	}  else {
		header('Location: '. home_url());
		exit();
	}
}

//Map of roster of practitioners
function getMap() {
	//echo "<script src='http://code.jquery.com/jquery-1.11.0.min.js'></script>";
	echo "<script src='https://api.tiles.mapbox.com/mapbox.js/v1.6.2/mapbox.js'></script>";
	echo "<link href='https://api.tiles.mapbox.com/mapbox.js/v1.6.2/mapbox.css' rel='stylesheet'/>";
	echo "<link href='https://api.tiles.mapbox.com/mapbox.js/v1.6.2/mapbox.css' rel='stylesheet'/>";
	echo "<link href='/map/css/map-style.css' rel='stylesheet'/>";
	echo "<script src='/map/js/gpsa-rosters.geojson.js' type='text/javascript'></script>";
	
	echo "<a name='roster-of-practitioners'></a><div class='pagetitle'><h2>Roster of practitioners</h2></div>";
	echo "<p class='obj'>Click on an expert to find out more about his areas of expertise, working languages, and disponibilities for short-term consultancy work.</p>";
	echo "<div id='map'><div id='themes-layers' class='layers'></div><div id='info'></div></div>";
	echo "<script src='/map/js/map-init.js' type='text/javascript'></script>";
}


function getSurvey() {
	echo "<style>
		#survey {
			display: block;
			height: auto;
			left: 805.5px;
			position: absolute;
			top: 100;
			width: 455px;
			height:235px;
			left: 600;
			outline: 0 none;
			overflow: hidden;
			z-index: 100;
			border: 1px solid #ccc;
		}
		
		.border {
			border-bottom-right-radius: 4px;
			border-bottom-left-radius: 4px;
			border-top-right-radius: 4px;
			border-top-left-radius: 4px;
		}
		
		.title-survey { 
			color:#fff; 
			font-size:1.4em;
			width:100%; 
			height:95px; 
			background-color:#289CD7; 
			border: 1px solid #73b9dc;
			position: relative;
		}
		
		.title-survey span { margin-top:20px; margin-left:20px; float:left;}
		.title-survey img { margin-top:12px; margin-left:-5px; float:left;}
		
		.content-survey {
			font-size:1.1em;
			color: #737373;
			background-color: #f1f1f1;
			height:100%;
		}
		
		.content-survey span { margin-right:20px;  margin-top:20px; margin-left:20px; float:left; }
		.content-survey span { margin-right:20px;  margin-top:20px; margin-left:20px; float:left; }
		
		#yes-survey { margin-left:155px; }
	</style>";
	
	$url_image = get_template_directory_uri() . '/images/admiracion.png';
	
	echo '<div id="survey" class="border">
		<div class="title-survey border">
			<span>Let’s increase our knowledge <br/>about social accountability</span>
			<img src="'.$url_image.'" alt="Let’s increase our knowledge about social accountability"/>
		</div>
		
		<div class="content-survey">
			<span>Help us improve the Knowledge Platform!</span>
			<span>Your feedback is very important for us. So, please give us your opinion on the Knowledge Platform and its different activities through the following survey before December 12, 2014.</span>
			<a class="contorno-morado" id="yes-survey" href="https://www.surveymonkey.com/s/DK35YCR" target="_blank">Yes</a>
			<a class="contorno-morado" href="#close">No</a>
		</div>
	</div>

	<script>
		jQuery(".contorno-morado").click(function () {
			jQuery("#survey").hide();
		});
	</script>';

	return true;
}

//custom category type template
function get_custom_cat_template($single_template) {
    global $post;

    if(in_category( 'expert' )) {
        $single_template = dirname( __FILE__ ) . '/single-expert.php';
    }

    return $single_template;
}

add_filter( "single_template", "get_custom_cat_template" ) ;
/*
//custom post type template
function get_custom_post_type_template($single_template) {
    global $post;

    if ($post->post_type == 'ajde_events') {
         $single_template = dirname( __FILE__ ) . '/single-events.php';
    }
    return $single_template;
}

add_filter( "single_template", "get_custom_post_type_template" ) ;
*/
//fix for cookie error while login.
/*
function set_wp_test_cookie() {
	setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
	if ( SITECOOKIEPATH != COOKIEPATH ) {
		setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);
	}
}
*/

add_filter( 'wp_headers', 'yourprefix_remove_x_pingback' );
function yourprefix_remove_x_pingback( $headers )
{
    unset( $headers['X-Pingback'] );
    return $headers;
}
add_action( 'after_setup_theme', 'set_wp_test_cookie', 101 );
