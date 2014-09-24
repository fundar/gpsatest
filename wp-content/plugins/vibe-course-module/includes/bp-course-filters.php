<?php

/**
 * In this file you'll want to add filters to the template tag output of your component.
 * You can use any of the built in WordPress filters, and you can even create your
 * own filter functions in this file.
 */

 /**
  * Some WP filters you may want to use:
  *  - wp_filter_kses() VERY IMPORTANT see below.
  *  - wptexturize()
  *  - convert_smilies()
  *  - convert_chars()
  *  - wpautop()
  *  - stripslashes_deep()
  *  - make_clickable()
  */

/**
 * --- NOTE ----
 * It's very very important that you use the wp_filter_kses() function to filter all
 * input AND output in your plugin. This will stop users adding malicious scripts and other
 * bad things onto any page.
 */

/**
 * In all your template tags that output data, you should have an apply_filters() call, you can
 * then use those filters to automatically add the wp_filter_kses() call.
 * The third parameter "1" adds the highest priority to the filter call.
 */


add_action('wp_ajax_course_filter','course_filter');
add_action('wp_ajax_nopriv_course_filter','course_filter');
function course_filter(){
	global $bp;

	$args=array('post_type' => BP_COURSE_CPT);
	if(isset($_POST['filter'])){
		$filter = $_POST['filter'];
		switch($filter){
			case 'popular':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'vibe_students';
			break;
			case 'newest':
				$args['orderby'] = 'date';
			break;
			case 'rated':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'average_rating';
			break;
			case 'alphabetical':
				$args['orderby'] = 'title';
				$args['order'] = 'ASC';
			break;
			default:
				$args['orderby'] = '';
			break;
		}
	}

	if(isset($_POST['search_terms']) && $_POST['search_terms'])
		$args['search_terms'] = $_POST['search_terms'];

	if(isset($_POST['page']))
		$args['paged'] = $_POST['page'];

	if(isset($_POST['scope']) && $_POST['scope'] == 'personal'){
		$uid=get_current_user_id();
		$args['meta_query'] = array(
			array(
				'key' => $uid,
				'compare' => 'EXISTS'
				)
			);
	}

	if(isset($_POST['scope']) && $_POST['scope'] == 'instructor'){
		$uid=get_current_user_id();
		$args['instructor'] = $uid;
	}


$loop_number=vibe_get_option('loop_number');
isset($loop_number)?$loop_number:$loop_number=5;

$args['per_page'] = $loop_number;
?>

<?php do_action( 'bp_before_course_loop' ); ?>

<?php 

if ( bp_course_has_items( $args ) ) : ?>

	<div id="pag-top" class="pagination ">

		<div class="pag-count" id="course-dir-count-top">

			<?php bp_course_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="course-dir-pag-top">

			<?php bp_course_item_pagination(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_course_list' ); ?>

	<ul id="course-list" class="item-list" role="main">

	<?php while ( bp_course_has_items() ) : bp_course_the_item(); ?>

		<li>
			<div class="item-avatar">
				<?php bp_course_avatar(); ?>

			</div>

			<div class="item">
				<div class="item-title"><?php bp_course_title(); ?></div>
				<div class="item-meta"><?php bp_course_meta(); ?></div>
				<div class="item-desc"><?php bp_course_desc(); ?></div>
				<div class="item-credits">
					<?php bp_course_credits(); ?>
				</div>
				<div class="item-instructor">
					<?php bp_course_instructor(); ?>
				</div>
				<div class="item-action"><?php bp_course_action(); ?></div>
				<?php do_action( 'bp_directory_course_item' ); ?>

			</div>

			<div class="clear"></div>
		</li>

	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_course_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="course-dir-count-bottom">

			<?php bp_course_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="course-dir-pag-bottom">

			<?php bp_course_item_pagination(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'No Courses found.', 'vibe' ); ?></p>
	</div>

<?php endif;  ?>


<?php do_action( 'bp_after_course_loop' ); ?>
<?php

	die();
}


add_action('bp_ajax_querystring','filtering_instructor_custom',20,2);
function filtering_instructor_custom($qs=false,$object=false){
 //list of users to exclude
 
 $args=array('role' => 'Instructor','fields' => 'ID');
 $users = new WP_User_Query($args);

 $included_user = implode(',',$users->results);
 //$included_user='1,2,3';//comma separated ids of users whom you want to exclude


 if($object!='members')//hide for members only
 return $qs;
 
 $args=wp_parse_args($qs);
 if(!isset($args['scope']) || $args['scope'] != 'instructors')
 	return $qs;
 //check if we are searching  or we are listing friends?, do not exclude in this case
 if(!empty($args['user_id'])||!empty($args['search_terms']))
 return $qs;
 
 if(!empty($args['include']))
 $args['include']=$args['include'].','.$included_user;
 else
 $args['include']=$included_user;


 $qs=build_query($args);
 
 return $qs;
 
}


/*
add_action('wp_ajax_instructors_filter','instructors_filter');
add_action('wp_ajax_no_priv_instructors_filter','instructors_filter');
function instructors_filter($query){
	global $bp;
	$args=array('role' => 'Instructor','fields' => 'ID');
	$users = new WP_User_Query($args);
	$query_array->query_vars['user_ids'] = $users->results;

	return $query_array;
	die();
}*/
//  bp_course_get_item_pagination
?>