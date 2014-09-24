<?php


add_action( 'widgets_init', 'wplms_events_register_widgets' );

function wplms_events_register_widgets() {
    register_widget('WPLMS_Events_Widget');
}

class WPLMS_Events_Widget extends WP_Widget {

	function WPLMS_Events_Widget() {
	  $widget_ops = array( 'classname' => 'Events Widget', 'description' => 'Displays Upcoming/Popular events in all/single course' );
	  $control_ops = array( 'width' => 250, 'height' => 350,'id_base' => 'wplms_events_widget');
	  $this->WP_Widget( 'wplms_events_widget',  __('WPLMS Events Widget','wplms-events'), $widget_ops, $control_ops);
	  }

	function widget( $args, $instance ) {
		global $wpdb,$bp;

		extract( $args );

		extract( $instance, EXTR_SKIP );
		echo $before_widget;
		if(isset($title) && $title !='')
		echo $before_title .
		     $title .
		     $after_title; 

		     //Preparing Query
		     

		     if(isset($ids) && $ids !='' && strlen($ids) > 3){
		     	$course_ids = explode(',',$ids);
		     	$the_query= new WP_QUERY(array( 'post_type' => WPLMS_EVENTS_CPT, 'post__in' => $course_ids ) );
		     }else{
		     	$current_date = date('Y-m-d');

		     	if(!isset($date_range) || !is_numeric($date_range))
		     		$date_range=1;

		     	$range_date = date('Y-m-d', (time()+86400*$date_range));

		     	$qargs = array('post_type' => WPLMS_EVENTS_CPT,'post_status' =>'publish');

		     	if(isset($event_type) && $event_type !='' && $event_type != 'none'){
		     		$qargs['event_type'] = $event_type;
		     	}


		     	if(isset($date_range) && $date_range !=''){
		     		$qargs['meta_query']=array(
		     			'relation' => '"AND"',
	     			);

	     			if(isset($show_start_events) && $show_start_events ){
	     				$qargs['meta_query'][]=array(
		                    'key' => 'vibe_start_date',
		                    'compare' => 'BETWEEN',
		                    'value' => array($current_date,$range_date),
		                    'type' => 'DATE'
		                );
	     			}
	     			if(isset($show_end_events) && $show_end_events ){
	     				$qargs['meta_query'][]=array(
		                    'key' => 'vibe_end_date',
		                    'compare' => 'BETWEEN',
		                    'value' => array($current_date,$range_date),
		                    'type' => 'DATE'
		                );
	     			}
		     	}
		     	
		     	if(isset($show_course_events) && $show_course_events){
		     		if(is_single() && get_post_type() == 'course'){
			     		$qargs['meta_query'][]=array(
		                    'key' => 'vibe_event_course',
		                    'compare' => '=',
		                    'value' => get_the_ID(),
		                    'type' => 'DECIMAL'
		                );
		     		}
		     	}

		     	if($orderby =='name' || $orderby == 'comment_count' || $orderby == 'date' || $orderby == 'title' || $orderby == 'rand'){
		     		$qargs['orderby'] = $orderby;
		     	}else if ($orderby !='default'){
		     		$qargs['orderby']='meta_value';
		     		$qargs['meta_key'] = $orderby;
		     	}

		     	$qargs['posts_per_page'] = $max_items;
		     	$qargs['order'] = $order;


		     	$the_query= new WP_Query($qargs);
		     }

		     ?>     
	<?php
	if($the_query->have_posts()):

		switch($style){
	     	case 'list':
	     		echo '<ul class="widget_event_list no-ajax">';
	     	case 'carousel':
	     		echo '<div class="widget_carousel flexslider  no-ajax"><ul class="slides">';
	     	break;	
	    }	

	while($the_query->have_posts()):$the_query->the_post();
	global $post;
	$icon = get_post_meta($post->ID,'vibe_icon',true);
	$color = get_post_meta($post->ID,'vibe_color',true);
	$start_date = get_post_meta($post->ID,'vibe_start_date',true);
	$end_date = get_post_meta($post->ID,'vibe_end_date',true);
	switch($style){
     	case 'list':
     	echo '<li><a href="'.get_permalink($post->ID).'">
     	<i class="'.$icon.'" style="color:'.$color.'"></i>
     	<strong>'.get_the_title($post->ID).'<span>
     	<i class="icon-calendar"></i> '.date('dS, M',strtotime($start_date)).' '.__('To','wplms-events').' '.date('dS, M',strtotime($end_date)).'
     	</span>
     	</strong>
     	</a></li>';
     	break;
     	case 'carousel':
     	echo '<li>';
     	echo thumbnail_generator($post,'event_card','3','0',true,true);
     	echo '</li>';
     	break;
     	default:
     	echo '<div class="single_event">';
     	echo thumbnail_generator($post,'event_card','3','0',true,true);
     	echo '</div>';
     	break;
     }
	endwhile;
		switch($style){
	     	case 'list':
	     		echo '</ul>';
	     	break;
	     	case 'carousel':
	     		echo '</ul></div>';
	     	break;
	     }

	else:
		echo '<div class="error">'.__('No Events in next ','wplms-events').$date_range.__(' days','wplms-events').'</div>';
	endif;
	wp_reset_postdata();
	 echo $after_widget; ?>
	<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['date_range'] = strip_tags( $new_instance['date_range'] );
		$instance['style'] = strip_tags( $new_instance['style'] );
		$instance['event_type'] = strip_tags( $new_instance['category'] );
		$instance['orderby'] = strip_tags( $new_instance['orderby'] );
		$instance['order'] = strip_tags( $new_instance['order'] );
		$instance['ids'] = strip_tags( $new_instance['ids'] );
		$instance['max_items'] = strip_tags( $new_instance['max_items'] );
		$instance['show_course_events'] = strip_tags( $new_instance['show_course_events'] );
		$instance['show_start_events'] = strip_tags( $new_instance['show_start_events'] );
		$instance['show_end_events'] = strip_tags( $new_instance['show_end_events'] );
		

		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 
			'title'=> 'Events',
			'date_range' =>7,
			'show_start_events'=>1,
			'show_end_events' =>1,
			'style' => 'single',
			'orderby'=>'name',
			'order'=>'ASC',
			'event_type'=>'',
			'ids'=>'', 
			'course'=>0, 
			'max_items' => 5 
		);

		$instance = wp_parse_args( (array) $instance, $defaults );
		
		

		extract( $instance, EXTR_SKIP );

		?>
		<p><label for="wplms-event-widget-ids"><?php _e( 'Widget Title', 'vibe' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" style="width: 30%" /></label></p>
		<p><label for="wplms-event-widget-date"><?php _e( 'Date Range (from Today to X Days)', 'wplms-event' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'date_range' ); ?>" name="<?php echo $this->get_field_name( 'date_range' ); ?>" type="text" value="<?php echo esc_attr( $date_range ); ?>" style="width: 30%" /></label></p>
		<p>
			<label for="wplms-event-widget-start-events"><input class="checkbox" id="<?php echo $this->get_field_id( 'show_start_events' ); ?>" name="<?php echo $this->get_field_name( 'show_start_events' ); ?>" type="checkbox" value="1" <?php CHECKED( $show_start_events,1,true ); ?> /><?php _e( 'Show Events Starting in range', 'vibe' ); ?> </label>
		</p>
		<p>
			<label for="wplms-event-widget-end-events"><input class="checkbox" id="<?php echo $this->get_field_id( 'show_end_events' ); ?>" name="<?php echo $this->get_field_name( 'show_end_events' ); ?>" type="checkbox" value="1" <?php CHECKED( $show_end_events,1,true ); ?> /><?php _e( 'Show Events Ending in range', 'vibe' ); ?> </label>
		</p>
		<p><label for="wplms-event-widget-style"><?php _e( 'Style', 'vibe' ); ?> 
		<select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
			<option value="list" <?php selected('list',esc_attr( $style )); ?>><?php _e('List'); ?></option>
			<option value="single" <?php selected('single',esc_attr( $style )); ?>><?php _e('Single'); ?></option>
			<option value="carousel" <?php selected('carousel',esc_attr( $style )); ?>><?php _e('Carousel'); ?></option>
		</select>
		</p>
		<p><label for="wplms-event-widget-category"><?php _e( 'Select Event type (optional)', 'vibe' ); ?> 
		<select id="<?php echo $this->get_field_id( 'event_type' ); ?>" name="<?php echo $this->get_field_name( 'event_type' ); ?>">
			<option value="">None</option>
		<?php
		
		$event_types=get_terms('event-type','orderby=count&hide_empty=0');

		
		if (!empty($event_types) && !is_wp_error($event_types)) {
			foreach($event_types as $eventtype){
				echo '<option value="'.$eventtype->slug.'" '.selected($eventtype->slug,esc_attr( $event_type )).'>'.$eventtype->name.'</option>';
			}
		}	
		?>
		</select>
		</p>
		<p><label for="wplms-event-widget-orderby"><?php _e( 'Order By', 'vibe' ); ?> 
		<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
			<option value="default" <?php selected('default',$orderby ); ?>><?php _e('Default (Publish Date)','wplms-events'); ?></option>
			<option value="rand" <?php selected('rand',$orderby); ?>><?php _e('Random','wplms-events'); ?></option>
			<option value="name" <?php selected('name',$orderby); ?>><?php _e('Name','wplms-events'); ?></option>
		</select>
		</p>
		<p><label for="wplms-event-widget-order"><?php _e( 'Sort ', 'vibe' ); ?> 
		<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
			<option value="ASC" <?php selected('ASC',esc_attr( $order )); ?>><?php _e('Ascending'); ?></option>
			<option value="DESC" <?php selected('DESC',esc_attr( $order )); ?>><?php _e('Decending'); ?></option>
		</select>
		</p>
		<p><label for="wplms-event-widget-ids"><?php _e( 'Specific Events (enter comma saperated ids)', 'vibe' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'ids' ); ?>" name="<?php echo $this->get_field_name( 'ids' ); ?>" type="text" value="<?php echo esc_attr( $ids ); ?>" style="width: 30%" /></label></p>
		<p><label for="wplms-event-widget-max"><?php _e( 'Maxmium Number of Events to show', 'wplms-event' ); ?> <input class="widefat" id="<?php echo $this->get_field_id( 'max_items' ); ?>" name="<?php echo $this->get_field_name( 'max_items' ); ?>" type="text" value="<?php echo esc_attr( $max_items ); ?>" style="width: 30%" /></label></p>
		<p>
			<label for="wplms-event-widget-category"><input class="checkbox" id="<?php echo $this->get_field_id( 'show_course_events' ); ?>" name="<?php echo $this->get_field_name( 'show_course_events' ); ?>" type="checkbox" value="1" <?php CHECKED( $show_course_events,1,true ); ?> /><?php _e( 'Show Course Events (on Course pages)', 'vibe' ); ?> </label>
		</p>
	<?php
	}
}


?>