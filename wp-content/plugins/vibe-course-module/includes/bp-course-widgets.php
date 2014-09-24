<?php

/**
 * In this file you should create and register widgets for your component.
 *
 * Widgets should be small, contained functionality that a site administrator can drop into
 * a widget enabled zone (column, sidebar etc)
 *
 * Good courses of suitable widget functionality would be short lists of updates or featured content.
 *
 * For course the friends and groups components have widgets to show the active, newest and most popular
 * of each.
 */


add_action( 'widgets_init', 'bp_course_register_widgets' );

function bp_course_register_widgets() {
    register_widget('BP_Course_Widget');
    register_widget('BP_Instructor_Widget');
    register_widget('BP_Course_Search_Widget');
    register_widget('BP_Course_Stats_Widget');
}

class BP_Course_Widget extends WP_Widget {



	function BP_Course_widget() {
	  $widget_ops = array( 'classname' => 'BuddyPress Course Widget', 'description' => 'Displays Courses in single, list & carousel formats.' );
	  $control_ops = array( 'width' => 250, 'height' => 350,'id_base' => 'bp_course_widget');
	  $this->WP_Widget( 'bp_course_widget',  __('BuddyPress Course Widget','vibe'), $widget_ops, $control_ops);
	  }

	function widget( $args, $instance ) {
		global $bp;

		extract( $args );

		extract( $instance, EXTR_SKIP );
		echo $before_widget;
		if(isset($title) && $title !='')
		echo $before_title .
		     $title .
		     $after_title; 

		     //Preparing Query
		     

		     if(isset($ids) && $ids !='' && strlen($ids) > 5){
		     	$course_ids = explode(',',$ids);
		     	$the_query= new WP_QUERY(array( 'post_type' => 'course', 'post__in' => $course_ids ) );
		     }else{

		     	$qargs = array('post_type' => 'course');
		     	if(isset($category) && $category !='' && $category != 'none'){
		     		$qargs['course-cat'] = $category;
		     	}
		     	if($orderby =='name' || $orderby == 'comment_count' || $orderby == 'date' || $orderby == 'title' || $orderby == 'rand'){
		     		$qargs['orderby'] = $orderby;
		     	}else{
		     		$qargs['orderby']='meta_value';
		     		$qargs['meta_key'] = $orderby;
		     	}

		     	$qargs['posts_per_page'] = $max_items;
		     	$qargs['order'] = $order;


		     	$the_query= new WP_Query($qargs);
		     }

		     switch($style){
		     	case 'list':
		     	case 'list1':
		     		echo '<ul class="widget_course_list no-ajax">';
		     	break;
		     	case 'carousel':
		     		echo '<div class="widget_carousel flexslider  no-ajax"><ul class="slides">';
		     	break;
		     }
		     ?>     
	<?php

	while($the_query->have_posts()):$the_query->the_post();
	global $post;
	switch($style){
		     	case 'list':
		     	echo '<li><a href="'.get_permalink($post->ID).'">'.get_the_post_thumbnail($post->ID,'thumbnail').'<h6>'.get_the_title($post->ID).'<span>'.__('by','vibe').' '.bp_core_get_user_displayname($post->post_author).'</span></h6></a></li>';
		     	break;
		     	case 'list1':
		     	echo '<li><a href="'.get_permalink($post->ID).'">'.get_the_post_thumbnail($post->ID,'thumbnail').'<h6>'.get_the_title($post->ID).'<span>'.bp_course_get_course_meta().'</span></h6></a></li>';
		     	break;
		     	case 'carousel':
		     	echo '<li>';
		     	echo thumbnail_generator($post,'course','3','0',true,true);
		     	echo '</li>';
		     	break;
		     	default:
		     	echo '<div class="single_course">';
		     	echo thumbnail_generator($post,'course','3','0',true,true);
		     	echo '</div>';
		     	break;
		     }

	endwhile;
	wp_reset_postdata();
	?>
	<?php
		switch($style){
				case 'list1':
		     	case 'list':
		     		echo '</ul>';
		     	break;
		     	case 'carousel':
		     		echo '</ul></div>';
		     	break;
		     }
	?>
	<?php echo $after_widget; ?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['style'] = strip_tags( $new_instance['style'] );
		$instance['category'] = strip_tags( $new_instance['category'] );
		$instance['orderby'] = strip_tags( $new_instance['orderby'] );
		$instance['order'] = strip_tags( $new_instance['order'] );
		$instance['ids'] = strip_tags( $new_instance['ids'] );
		$instance['max_items'] = strip_tags( $new_instance['max_items'] );
		

		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title'=> 'Course','style' => 'single','orderby'=>'name','order'=>'ASC','category'=>'','ids'=>'', 'max_items' => 5 );

		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$course_cats=get_terms('course-cat','orderby=count&hide_empty=0');

		extract( $instance, EXTR_SKIP );

		?>
		<p><label for="bp-course-widget-ids"><?php _e( 'Widget Title', 'vibe' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 30%" /></label></p>
		<p><label for="bp-course-widget-style"><?php _e( 'Style', 'vibe' ); ?> 
		<select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
			<option value="single" <?php selected('single',esc_attr( $style )); ?>><?php _e('Single'); ?></option>
			<option value="list" <?php selected('list',esc_attr( $style )); ?>><?php _e('List'); ?></option>
			<option value="list1" <?php selected('list1',esc_attr( $style )); ?>><?php _e('Ratings List'); ?></option>
			<option value="carousel" <?php selected('carousel',esc_attr( $style )); ?>><?php _e('Carousel'); ?></option>
		</select>
		</p>
		<p><label for="bp-course-widget-category"><?php _e( 'Select Course Category', 'vibe' ); ?> 
		<select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>">
			<option value="">None</option>
		<?php
			foreach($course_cats as $course_cat){
				echo '<option value="'.$course_cat->slug.'" '.selected($course_cat->slug,esc_attr( $category )).'>'.$course_cat->name.'</option>';
			}
		?>
		</select>
		</p>
		<p><label for="bp-course-widget-orderby"><?php _e( 'Order By', 'vibe' ); ?> 
		<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
			<option value="rand" <?php selected('rand',$orderby); ?>><?php _e('Random'); ?></option>
			<option value="name" <?php selected('name',$orderby); ?>><?php _e('Name'); ?></option>
			<option value="title" <?php selected('title',$orderby ); ?>><?php _e('Course Title'); ?></option>
			<option value="comment_count" <?php selected('comment_count', $orderby ); ?>><?php _e('Number of Reviews'); ?></option>
			<option value="date" <?php selected('date',$orderby ); ?>><?php _e('Date Published'); ?></option>
			<option value="average_rating" <?php selected('average_rating',$orderby ); ?>><?php _e('Rating'); ?></option>
			<option value="vibe_students" <?php selected('vibe_students',$orderby ); ?>><?php _e('Number of Students'); ?></option>
		</select>
		</p>
		<p><label for="bp-course-widget-order"><?php _e( 'Sort ', 'vibe' ); ?> 
		<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
			<option value="ASC" <?php selected('ASC',esc_attr( $order )); ?>><?php _e('Ascending'); ?></option>
			<option value="DESC" <?php selected('DESC',esc_attr( $order )); ?>><?php _e('Decending'); ?></option>
		</select>
		</p>
		<p><label for="bp-course-widget-ids"><?php _e( 'Specific Courses (enter comma saperated ids)', 'vibe' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'ids' ); ?>" name="<?php echo $this->get_field_name( 'ids' ); ?>" type="text" value="<?php echo esc_attr( $ids ); ?>" style="width: 30%" /></label></p>
		<p><label for="bp-course-widget-max"><?php _e( 'Number of Courses to show', 'bp-course' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_items' ); ?>" name="<?php echo $this->get_field_name( 'max_items' ); ?>" type="text" value="<?php echo esc_attr( $max_items ); ?>" style="width: 30%" /></label></p>
	<?php
	}
}



class BP_Instructor_Widget extends WP_Widget {



	function BP_Instructor_Widget() {
	  $widget_ops = array( 'classname' => 'BuddyPress Instructor Widget', 'description' => 'Displays Current Instructor details widget.' );
	  $control_ops = array( 'width' => 250, 'height' => 350,'id_base' => 'bp_instructor_widget');
	  $this->WP_Widget( 'bp_instructor_widget',  __('BuddyPress Instructor Widget','vibe'), $widget_ops, $control_ops);
	  }

	function widget( $args, $instance ) {
		global $bp;

		extract( $args );

		extract( $instance, EXTR_SKIP );
		echo $before_widget;
		if(isset($title) && $title !='')
		echo $before_title .
		     $title .
		     $after_title; 

		     if(is_single()){
		     	global $post;
				$instructor=$post->post_author;
		     }

		    echo '<div class="course_instructor_widget">';
		    echo bp_course_get_instructor('instructor_id='.$instructor);
		    echo '<div class="description">'.bp_course_get_instructor_description('instructor_id='.$instructor).'</div>';
		    echo '<a href="'.get_author_posts_url($instructor).'" class="tip" title="'.__('Check all Courses created by ','vibe').bp_core_get_user_displayname($instructor).'"><i class="icon-plus-1"></i></a>';
		    echo '<h5>'.__('More Courses by ','vibe').bp_core_get_user_displayname($instructor).'</h5>';
		    echo '<ul class="widget_course_list">';
		    $query = new WP_Query( 'post_type=course&author='.$instructor.'&posts_per_page='.$max_items );
		    while($query->have_posts()):$query->the_post();
		    global $post;
		    echo '<li><a href="'.get_permalink($post->ID).'">'.get_the_post_thumbnail($post->ID,'thumbnail').'<h6>'.get_the_title($post->ID).'<span>'.__('by','vibe').' '.bp_core_get_user_displayname($post->post_author).'</span></h6></a>';
		    endwhile;
		    wp_reset_postdata();
		    echo '</ul>';
		    echo '</div>'; 
		     //Preparing Query
		    
	 echo $after_widget; ?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['instructor'] = strip_tags( $new_instance['instructor'] );
		$instance['max_items'] = strip_tags( $new_instance['max_items'] );
		

		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title'=> 'Instructor Details',
			'instructor' => '1','max_items' => 5 );

		$instance = wp_parse_args( (array) $instance, $defaults );

		extract( $instance, EXTR_SKIP );
		$title = esc_attr($instance['title']);
		?>
		<p><label for="bp-instructor-widget-title"><?php _e( 'Widget Title', 'vibe' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 30%" /></label></p>
		<p><label for="bp-instructor-widget-title"><?php _e( 'Fallback Instructor ID', 'vibe' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'instructor' ); ?>" name="<?php echo $this->get_field_name( 'instructor' ); ?>" type="text" value="<?php echo esc_attr( $instructor ); ?>" style="width: 30%" /></label></p>
		<p><label for="bp-instructor-widget-max"><?php _e( 'Number of Courses by the Instructor to show', 'vibe' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_items' ); ?>" name="<?php echo $this->get_field_name( 'max_items' ); ?>" type="text" value="<?php echo esc_attr( $max_items ); ?>" style="width: 30%" /></label></p>
	<?php
	}
}



class BP_Course_Search_Widget extends WP_Widget {



	function BP_Course_Search_Widget() {
	  $widget_ops = array( 'classname' => 'buddypress-course-search-widget', 'description' => 'Displays Advanced search for Courses.' );
	  $control_ops = array( 'width' => 250, 'height' => 350,'id_base' => 'bp_course_search_widget');
	  $this->WP_Widget( 'bp_course_search_widget',  __('BuddyPress Course Search Widget','vibe'), $widget_ops, $control_ops);
	  }

	function widget( $args, $instance ) {
		global $bp;

		extract( $args );

		extract( $instance, EXTR_SKIP );
		echo $before_widget;
		if(isset($title) && $title !='')
		echo $before_title .
		     $title .
		     $after_title; 

		     $html .='<form role="search" method="get" id="searchform" action="'.home_url( '/' ).'">
		     			<input type="hidden" name="post_type" value="'.BP_COURSE_SLUG.'" />
		     			<ul>';

		     if(isset($cats) && $cats == 1){

		     	$cat_val=$_GET['course-cat'];

		     	$course_cats = get_terms('course-cat');
		     	$html .= '<li><select name="course-cat" class="chosen chzn-select">';
		     	$html .='<option value="">'.__('Select Course Category','vibe').'</option>';
		     	foreach($course_cats as $term){
		     		$html .='<option value="'.$term->slug.'" '.(isset($cat_val)?selected($cat_val,$term->slug,false):'').'>'.$term->name.'</option>';
		     	}
		     	$html .= '</select></li>'; 
		     }

		     if(isset($instructors) && $instructors == 1){
		     	$args = array(
	                'role' => 'instructor' // instructor
	    		);
				$user_query = new WP_User_Query( $args );

				if ( !empty( $user_query->results ) ) {
					$inst_val = $_GET['instructor'];
					$html .='<li><select name="instructor" class="chosen chzn-select">';
					$html .='<option value="">'.__('Select Instructor','vibe').'</option>';
			        foreach ( $user_query->results as $user ) {
			        	$html .='<option value="'.$user->ID.'" '.(isset($inst_val)?selected($inst_val,$user->ID,false):'').'>'.$user->display_name.'</option>';
			        }
					$html .='</select></li>';        
				}	        
		     }
		     $leveloption = vibe_get_option('level'); 
		     if(isset($leveloption) && $leveloption && isset($level) && $level == 1){
		     	$level_val=$_GET['level'];

		     	$level_vals = get_terms('level');
		     	$html .= '<li><select name="level" class="chosen chzn-select">';
		     	$html .='<option value="">'.__('Select Course Level','vibe').'</option>';
		     	foreach($level_vals as $term){
		     		$html .='<option value="'.$term->slug.'" '.(isset($level_val)?selected($level_val,$term->slug,false):'').'>'.$term->name.'</option>';
		     	}
		     	$html .= '</select></li>';
		     }

				$html .='<li><input type="text" value="'.(isset($_GET['s'])?$_GET['s']:'').'" name="s" id="s" placeholder="'.__('Type Keywords..','vibe').'" /></li>
					     <li><input type="submit" id="searchsubmit" value="'.__('Search','vibe').'" /></li></ul>
					</form>';

					echo $html;
		    
	 echo $after_widget; ?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cats'] = strip_tags( $new_instance['cats'] );
		$instance['instructors'] = strip_tags( $new_instance['instructors'] );
		$instance['level'] = strip_tags( $new_instance['level'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 
			'title'=> 'Advanced Course Search Widget',
			'instructors' => 1,
			'cats' => 1,
			'level' => 1,
			 );

		$instance = wp_parse_args( (array) $instance, $defaults );
		$leveloption = vibe_get_option('level'); 
		extract( $instance, EXTR_SKIP );
		$title = esc_attr($instance['title']);
		$cats = esc_attr($instance['cats']);
		$instructors = esc_attr($instance['instructors']);
		$level = esc_attr($instance['level']);
		if(isset($leveloption) && $leveloption)
			$level = esc_attr($instance['level']);
		?>
		<p><label for="bp-course-search-widget-title"><?php _e( 'Widget Title', 'vibe' ); ?> <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 30%" /></label></p>
		<p><label for="bp-course-cat-dropdown"><?php _e( 'Show Course Category Dropdown', 'vibe' ); ?> <input class="checkbox" id="<?php echo $this->get_field_id( 'cats' ); ?>" name="<?php echo $this->get_field_name( 'cats' ); ?>" type="checkbox" value="1" <?php checked($cats,1,true) ?>/></label></p>
		<p><label for="bp-instructor-dropdown"><?php _e( 'Show Instructor Dropdown', 'vibe' ); ?> <input class="checkbox" id="<?php echo $this->get_field_id( 'instructors' ); ?>" name="<?php echo $this->get_field_name( 'instructors' ); ?>" type="checkbox" value="1"  <?php checked($instructors,1,true) ?>/></label></p>
		<?php 
		if(isset($leveloption) && $leveloption){
		?>
			<p><label for="bp-instructor-level"><?php _e( 'Show Level Dropdown', 'vibe' ); ?> <input class="checkbox" id="<?php echo $this->get_field_id( 'level' ); ?>" name="<?php echo $this->get_field_name( 'level' ); ?>" type="checkbox" value="1"  <?php checked($level,1,true) ?>/></label></p>
		<?php
		}
	}
}


class BP_Course_Stats_Widget extends WP_Widget {



	function BP_Course_Stats_Widget() {
	  $widget_ops = array( 'classname' => 'buddypress-course-stats-widget', 'description' => 'Displays Stats for Course.' );
	  $control_ops = array( 'width' => 250, 'height' => 350,'id_base' => 'bp_course_stats_widget');
	  $this->WP_Widget( 'bp_course_stats_widget',  __('BuddyPress Course Stats Widget','vibe'), $widget_ops, $control_ops);
	  }

	function widget( $args, $instance ) {
		global $bp,$wpdb;

		extract( $args );

		extract( $instance, EXTR_SKIP );
		echo $before_widget;
		if(isset($title) && $title !='')
		echo $before_title .
		     $title .
		     $after_title; 

		if($course == 'current')     
			$course = get_the_ID();

		if(is_numeric($course)){
			if($students){
				$ct=$wpdb->get_results("
						SELECT SUM(rel.meta_value) as total_students
					    FROM {$wpdb->posts} AS posts
					    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
					    WHERE 	posts.post_type 	= 'course'
					    AND     posts.ID = $course
						AND 	posts.post_status 	= 'publish'
						AND 	rel.meta_key   = 'vibe_students'
					");
				$total_students=(empty($ct[0]->total_students)?0:$ct[0]->total_students);
			}
			if($badgecertificates){
				$total_badges = get_post_meta($course,'badge',true);
				$total_certificates= get_post_meta($course,'pass',true);
			}
		}else{
			if($students){
				$ct=$wpdb->get_results("
						SELECT SUM(rel.meta_value) as total_students
					    FROM {$wpdb->posts} AS posts
					    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
					    WHERE 	posts.post_type 	= 'course'
						AND 	posts.post_status 	= 'publish'
						AND 	rel.meta_key   = 'vibe_students'
					");
				$total_students=(empty($ct[0]->total_students)?0:$ct[0]->total_students);
			}
			if($badgecertificates){
				$ct=$wpdb->get_results("
							SELECT count(rel.meta_value) as badge
						    FROM {$wpdb->posts} AS posts
						    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
						    WHERE 	posts.post_type 	= 'course'
							AND 	posts.post_status 	= 'publish'
							AND 	rel.meta_key   = 'badge'
						");
				$total_badges = (empty($ct[0]->badge)?0:$ct[0]->badge);
				$ct=$wpdb->get_results("
							SELECT count(rel.meta_value) as certificates
						    FROM {$wpdb->posts} AS posts
						    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
						    WHERE 	posts.post_type 	= 'course'
							AND 	posts.post_status 	= 'publish'
							AND 	rel.meta_key   = 'pass'
						");
				$total_certificates= (empty($ct[0]->certificates)?0:$ct[0]->certificates);
			}
		}    
		 
		echo '<div class="stat_num">';
		if($students)
		    echo '<strong class="tip" title="'.__('TOTAL STUDENTS','vibe').'"><i class="icon-myspace-alt"></i><span>'.$total_students.'</span></strong>';
		if($badgecertificates)
		    echo '<strong  class="tip" title="'.__('BADGES','vibe').'"><i class="icon-award-stroke"></i><span>'.$total_badges.'</span></strong>
		        <strong  class="tip" title="'.__('CERTIFICATES','vibe').'"><i class="icon-certificate-file"></i><span>'.$total_certificates.'</span></strong>';

		    echo '</div>';                
	 echo $after_widget; ?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['course'] = strip_tags( $new_instance['course'] );
		$instance['students'] = strip_tags( $new_instance['students'] );
		$instance['badgecertificates'] = strip_tags( $new_instance['badgecertificates'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 
			'title'=> 'Course Stats Widget',
			'course' => '',
			 );

		$instance = wp_parse_args( (array) $instance, $defaults );
		extract( $instance, EXTR_SKIP );
		$title = esc_attr($instance['title']);
		$course = esc_attr($instance['course']);
		$students = esc_attr($instance['students']);
		$badgecertificates = esc_attr($instance['badgecertificates']);
		?>
		<p><label for="bp-course-stats-widget-title"><?php _e( 'Widget Title', 'vibe' ); ?> <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 30%" /></label></p>
		<p><label for="bp-course-stats-dropdown"><?php _e( 'Select Course', 'vibe' ); ?> 
			<select id="<?php echo $this->get_field_id( 'course' ); ?>" name="<?php echo $this->get_field_name( 'course' ); ?>">
				<option value="" <?php selected('',$course); ?>><?php _e('All','vibe'); ?></option>
				<option value="current" <?php selected('current',$course); ?>><?php _e('Current Course (defaults to all)','vibe'); ?></option>
				<?php
					$args = array('post_type'=>'course','posts_per_page'=>-1);
					$the_query= new WP_QUERY($args);
					while($the_query->have_posts()):$the_query->the_post();
					echo '<option value="'.get_the_ID().'" '.selected('current',$course).'>'.get_the_title().'</option>';
					endwhile;
					wp_reset_postdata();
				?>
			</select>
		</label></p>
		<p><label for="bp-course-stats-level"><?php _e( 'Show Students Stats', 'vibe' ); ?> <input class="checkbox" id="<?php echo $this->get_field_id( 'students' ); ?>" name="<?php echo $this->get_field_name( 'students' ); ?>" type="checkbox" value="1"  <?php checked($students,1,true) ?>/></label></p>
		<p><label for="bp-course-certificates-level"><?php _e( 'Show Badge/Certificates Stats', 'vibe' ); ?> <input class="checkbox" id="<?php echo $this->get_field_id( 'badgecertificates' ); ?>" name="<?php echo $this->get_field_name( 'badgecertificates' ); ?>" type="checkbox" value="1"  <?php checked($badgecertificates,1,true) ?>/></label></p>
		<?php
	}
}

?>