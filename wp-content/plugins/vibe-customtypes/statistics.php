<?php



function vibe_lms_stats() {
    $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'overview';
    $subtab = isset( $_GET['subtab'] ) ? $_GET['subtab'] : 'overview';
	lms_stats_tabs($tab);    
	lms_stats_sub_tabs($tab,$subtab);
	lms_stats_tab_content($tab,$subtab);
}


function lms_stats_tabs( $current = 'overview' ) {

	
	if(current_user_can('manage_options')){
	 	$tabs = array( 
    		'overview' => __('Overview','vibe-customtypes'),
    		'course' => __('Course','vibe-customtypes'), 
    		'instructor' => __('Instructor','vibe-customtypes'), 
    		);
	}else{
		$tabs = array( 
    		'my-overview' => __('Overview','vibe-customtypes'),
    		'my-course' => __('My Courses','vibe-customtypes'), 
    		);
	}
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=lms-stats&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}

function lms_stats_tab_content($tab = 'overview',$subtab='day'){
        switch($tab){
        	case 'overview':
        		vibe_course_module_overview($subtab);
        	break;
        	case 'course':
        		lms_stats_course_content($subtab);
        	break;
        	case 'instructor':
        		lms_stats_instructor_content($subtab);
        	break;	
        	case 'my-overview':
        		vibe_my_course_module_overview($subtab);
        	break;
        	case 'my-course':
        		lms_stats_my_course_content($subtab);
        	break;
        }
}

function lms_stats_sub_tabs($tab='overview',$current='overview'){
	$subtabs=array(
		'overview'=>array(
			'overview' => 'Overview',
			'day' => 'Daily',
			'month' => 'Monthly',
			'custom' => 'Custom'
			),
		'course'=>array(
			'overview' => 'Overview',
			'popular' => 'Most Students',
			),
		'instructor'=>array(
			'overview' => 'Overview',
			'popular' => 'Popular'
			),
	);

	echo '<ul class="subsubsub stats">';
    foreach( $subtabs[$tab] as $subtab => $name ){
        $class = ( $subtab == $current ) ? ' current' : '';
        echo "<a class='$class' href='?page=lms-stats&tab=$tab&subtab=$subtab'>$name</a>";
        if($subtab !='custom')
        	echo ' | ';

    }
    echo '</ul>';
}
function vibe_weekend_area_js() {
	?>
	function weekendAreas(axes) {
        var markings = [];
        var d = new Date(axes.xaxis.min);
        // go to the first Saturday
        d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
        d.setUTCSeconds(0);
        d.setUTCMinutes(0);
        d.setUTCHours(0);
        var i = d.getTime();
        do {
            markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
            i += 7 * 24 * 60 * 60 * 1000;
        } while (i < axes.xaxis.max);

        return markings;
    }
    <?php
}

function vibe_tooltip_js() {
	?>
	function showTooltip(x, y, contents) {
        jQuery('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
		    padding: '5px 10px',
		    color: '#FFFFFF',
			background: '#70c0c9'
        }).appendTo("body").fadeIn(200);
    }
    var previousPoint = null;
    jQuery("#placeholder").bind("plothover", function (event, pos, item) {
        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                jQuery("#tooltip").remove();

                if (item.series.label=="<?php echo esc_js( __( 'Subscriptions', 'vibe' ) ) ?>") {

                	var y = item.datapoint[1].toFixed(2);
                	showTooltip(item.pageX, item.pageY, item.series.label + " - " + "" + y);

                } else if (item.series.label=="<?php echo esc_js( __( 'Number of students', 'vibe' ) ); ?>") {

                	var y = item.datapoint[1];
                	showTooltip(item.pageX, item.pageY, item.series.label + " - " + y);

                } else {

                	var y = item.datapoint[1];
                	showTooltip(item.pageX, item.pageY, y);

                }
            }
        }
        else {
            jQuery("#tooltip").remove();
            previousPoint = null;
        }
    });
    <?php
}


function vibe_course_module_overview($subtab='') {
	if( !current_user_can('manage_options' )){
		vibe_my_course_module_overview();
		return;
	}
	global $wpdb, $wp_locale;

	$total_students = $total_instructors = $start_date = $end_date = $total_coursetaken = $total_coursefinished = $total_badges = $total_certificates = 0;

	$result = count_users();

	$start_date = isset( $_POST['start_date'] ) ? $_POST['start_date'] : 0;
	$end_date	= isset( $_POST['end_date'] ) ? $_POST['end_date'] : 0;

	if($subtab =='custom'){
		 echo '<form method="post" action=""><h3>Custom Date Selection</h3>
				<p><label for="from">From:</label> 
				<input type="text" name="start_date" id="from" value="'.(isset($start_date)?$start_date:'2014-01-01').'" class="date-picker-field">
				 <label for="to">&nbsp;&nbsp; To:</label> 
				<input type="text" name="end_date" id="to" value="'.(isset($end_date)?$end_date:'2014-02-01').'" class="date-picker-field">
				<input type="submit" class="button" value="Show"></p>
			</form>';
	}
	foreach($result['avail_roles'] as $role => $count){
		if($role == 'student'){
			$total_students=$count;
		}
		if($role == 'instructor'){
			$total_instructors=$count;
		}
	}
    
   

	$ct=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT SUM(rel.meta_value) as total_students
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'vibe_students'
	"));
	$total_coursetaken=$ct[0]->total_students;
	

	$bg=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT SUM(rel.meta_value) as total_badge
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'badge'
	"));
	

	$total_badges = $bg[0]->total_badge;

	$ps=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT SUM(rel.meta_value) as total_pass
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'pass'
	"));
	$total_certificates=$ps[0]->total_pass;
	?>
	<div id="poststuff" class="vibe-reports-wrap">
		<div class="vibe-reports-sidebar">
			<div class="postbox">
				<h3><span><?php _e( 'Total Students', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ( $total_students > 0 ) echo $total_students; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total Instructors', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ( $total_instructors > 0 ) echo $total_instructors; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total Course Taken', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ( $total_coursetaken > 0 ) echo $total_coursetaken; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total Badges', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ($total_badges>0) echo $total_badges; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total Certificates', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ($total_certificates>0) echo $total_certificates; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
		</div>
		<div class="vibe-reports-main">
			<div class="postbox">
				<?php if($subtab == 'month'){ ?>
				<h3><span><?php _e( 'This year\'s monthly subscriptions', 'vibe' ); ?></span></h3>
				<?php }else{ ?>
				<h3><span><?php _e( 'This month\'s daily subscriptions', 'vibe' ); ?></span></h3>
				<?php } ?>
				<div class="inside chart">
					<div id="placeholder" style="width:100%; overflow:hidden; height:568px; position:relative;"></div>
					<div id="subscription_legend"></div>
				</div>
			</div>
		</div>
	</div>
	<?php

	
	if(isset($start_date) && $start_date !=0){
		$start_date = strtotime( date('Ymd', strtotime( $start_date ) ) );
		$end_date = strtotime( date('Ymd', strtotime( $end_date ) ) );		
	}else{
		$start_date = strtotime( date('Ymd', strtotime( date('Ym', current_time('timestamp') ) . '01' ) ) );
		$end_date = strtotime( date('Ymd', current_time( 'timestamp' ) ) );	
	}
	

	

	// Blank date ranges to begin
	$subscription_counts = $student_nos = array();
	$count = 0;
	$days = ( $end_date - $start_date ) / ( 60 * 60 * 24 );
	
	
	
	if ( $days == 0 )
		$days = 1;

	while ( $count < $days ) {
		if($subtab == 'month')
			$time = strtotime( date( 'Ymd', strtotime( '+ ' . $count . ' MONTH', $start_date ) ) ) . '000';
		else
			$time = strtotime( date( 'Ymd', strtotime( '+ ' . $count . ' DAY', $start_date ) ) ) . '000';

		$subscription_counts[ $time ] = $student_nos[ $time ] = 0;

		$count++;
	}

	// Get order ids and dates in range
	// 
	// Activity Table prefix -> No solution found in BuddyPress, so hardcoding this :((
	
	$table_name = $wpdb->prefix.'bp_'.'activity';
	$courses_started = apply_filters('vibe_reports_sales_overview_orders', $wpdb->get_results( "
		SELECT activity.user_id, activity.date_recorded FROM $table_name AS activity
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'start_course'
		AND 	date_recorded > '" . date('Y-m-d', $start_date ) . "'
		AND 	date_recorded < '" . date('Y-m-d', strtotime('+1 day', $end_date ) ) . "'
		ORDER BY date_recorded ASC
	" ) );

	
	if ( $courses_started ) {
		foreach ( $courses_started as $course_start ) {

			
			$time = strtotime( date( 'Ymd', strtotime( $course_start->date_recorded ) ) ) . '000';
			if ( isset( $subscription_counts[ $time ] ) )
				$subscription_counts[ $time ]++;
			else
				$subscription_counts[ $time ] = 1;


		}
	}

	
	//print_r($subscription_counts);

	$subscription_counts_array =  array();

	foreach ( $subscription_counts as $key => $count )
		$subscription_counts_array[] = array( esc_js( $key ), esc_js( $count ) );

	
	$subscription_data = array( 'subscription_counts' => $subscription_counts_array, );

	$chart_data = json_encode( $subscription_data );
	?>
	<script type="text/javascript">
		jQuery(function(){
			var subscription_data = jQuery.parseJSON( '<?php echo $chart_data; ?>' );

			var d = subscription_data.subscription_counts;

			for (var i = 0; i < d.length; ++i) d[i][0] += 60 * 60 * 1000;

			var placeholder = jQuery("#placeholder");

			var plot = jQuery.plot(placeholder, [ { label: "<?php echo esc_js( __( 'Number of Courses Started', 'vibe' ) ) ?>", data: d } ], {
				legend: {
					container: jQuery('#subscription_legend'),
					noColumns: 2
				},
				series: {
					lines: { show: true, fill: true },
					points: { show: true }
				},
				grid: {
					show: true,
					aboveData: false,
					color: '#aaa',
					backgroundColor: '#fff',
					borderWidth: 2,
					borderColor: '#aaa',
					clickable: false,
					hoverable: true,
					markings: weekendAreas
				},
				xaxis: {
					mode: "time",
					timeformat: "%d %b",
					monthNames: <?php echo json_encode( array_values( $wp_locale->month_abbrev ) ) ?>,
					tickLength: 1,
					minTickSize: [1, "day"]
				},
				yaxes: [ { min: 0, tickSize: 10, tickDecimals: 0 }, { position: "right", min: 0, tickDecimals: 2 } ],
		   		colors: ["#78c8c9"]
		 	});

		 	placeholder.resize();

			<?php vibe_weekend_area_js(); ?>
			<?php vibe_tooltip_js(); ?>
		});
	</script>
	<?php
}


function lms_stats_course_content($subtab='overview'){
	
	global $wpdb;
	$ct=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT SUM(rel.meta_value) as total_students
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'vibe_students'
	"));
	$total_coursetaken=$ct[0]->total_students;


	if($subtab == 'popular')
		$orderby='CAST(students AS UNSIGNED)';
	else
		$orderby='posts.ID';

	$st_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.ID,posts.post_title, rel.meta_value as students
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'vibe_students'
		ORDER BY $orderby DESC
	"));

	

	$bg_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.ID,rel.meta_value as badge
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'badge'
	"));
	$pass_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.ID,rel.meta_value as pass
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'pass'
	"));
	$avg_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.ID,rel.meta_value as avg
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'average'
	"));
	

	
	$table_name = $wpdb->prefix.'bp_'.'activity';
	$cs = apply_filters('vibe_reports_sales_overview_orders', $wpdb->get_results( "
		SELECT count(activity.id) as total FROM $table_name AS activity
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'start_course'
	" ) );

	$courses_started=$cs[0]->total;

	$cf = apply_filters('vibe_reports_sales_overview_orders', $wpdb->get_results( "
		SELECT count(activity.id) as total FROM $table_name AS activity
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'course_evaluated'
	" ) );

	$courses_finished=$cf[0]->total;

	$qe = apply_filters('vibe_reports_sales_overview_orders', $wpdb->get_results( "
		SELECT count(activity.id) as total FROM $table_name AS activity
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'quiz_evaluated'
	" ) );
	$quiz_evaluated = $qe[0]->total;

	$uc = apply_filters('vibe_reports_sales_overview_orders', $wpdb->get_results( "
		SELECT count(activity.id) as total FROM $table_name AS activity
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'unit_complete'
	" ) );
	$unit_complete = $uc[0]->total;
	?>
	<div id="poststuff" class="vibe-reports-wrap">
		<div class="vibe-reports-sidebar">
			<div class="postbox">
				<h3><span><?php _e( 'Total Students taking courses', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ( $total_coursetaken > 0 ) echo $total_coursetaken; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Courses Started By Students', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ( $courses_started > 0 ) echo $courses_started; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total Courses Finished by Students', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ( $courses_finished > 0 ) echo $courses_finished; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total Units Finished by Students', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ($unit_complete>0) echo $unit_complete; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total Quiz Finished by Students', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ($quiz_evaluated>0) echo $quiz_evaluated; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
		</div>
		<div class="vibe-reports-main">
				<div class="postbox course_info">
					<h3><label>Course Title</label><span># Students</span><span># Badges</span><span># Certificates</span><span>Average</span></h3>
					<div class="inside">
						<ul>
						<?php
							$course_info=array();
							foreach($st_num as $st){
								$course_info[$st->ID]=array(
									'title' => $st->post_title,
									'students' => $st->students,
									'badge'=> 'n/a',
									'pass'=>'n/a',
									'avg'=>'n/a'
									);
							}
							foreach($bg_num as $bg){
								if(isset($bg->badge))	
									$course_info[$bg->ID]['badge']=$bg->badge;
							}
							foreach($pass_num as $pass){
								if(isset($pass->pass))	
								$course_info[$pass->ID]['pass']=$pass->pass;
							}
							foreach($avg_num as $avg){
								if(isset($avg->avg))	
								$course_info[$avg->ID]['avg']=$avg->avg;
							}


							foreach($course_info as $course){
								if(isset($course['title']))
								echo '<li><label>'.$course['title'].'</label><span>'.$course['students'].'</span><span>'.$course['badge'].'</span><span>'.$course['pass'].'</span><span>'.$course['avg'].'</span>';	
							}
						?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}


function lms_stats_instructor_content($subtab='overview'){

global $wpdb;
	
	
	$result = count_users();
	foreach($result['avail_roles'] as $role => $count){
		if($role == 'instructor'){
			$total_instructors=$count;
		}
	}

	if($subtab == 'popular')
		$orderby='sum(rel.meta_value)';
	else
		$orderby='posts.post_author';

	$st_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.post_author as instructor,count(posts.ID) as courses, sum(rel.meta_value) as students
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'vibe_students'
		GROUP BY posts.post_author ORDER BY $orderby DESC 
	"));

	

	$bg_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.post_author as instructor,rel.meta_value as badge
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'badge'
		GROUP BY posts.post_author
	"));
	$pass_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.post_author as instructor,rel.meta_value as pass
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'pass'
		GROUP BY posts.post_author 
	"));
	$avg_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.post_author as instructor,rel.meta_value as avg
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'average'
		GROUP BY posts.post_author 
	"));
	

	$table_name = $wpdb->prefix.'bp_'.'activity';
	$cf = apply_filters('vibe_reports_sales_overview_orders', $wpdb->get_results( "
		SELECT count(activity.id) as total FROM $table_name AS activity
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'course_evaluated'
	" ) );

	$courses_finished=$cf[0]->total;

	$qe = apply_filters('vibe_reports_sales_overview_orders', $wpdb->get_results( "
		SELECT count(activity.id) as total FROM $table_name AS activity
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'quiz_evaluated'
	" ) );
	$quiz_evaluated = $qe[0]->total;

	
	?>
	<div id="poststuff" class="vibe-reports-wrap">
		<div class="vibe-reports-sidebar">
			<div class="postbox">
				<h3><span><?php _e( 'Total Instructors', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ( $total_instructors > 0 ) echo $total_instructors; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total Courses Evaluated by Instructor', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ( $courses_finished > 0 ) echo $courses_finished; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total Quiz Evaluated by Instructor', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ($quiz_evaluated>0) echo $quiz_evaluated; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
		</div>
		<div class="vibe-reports-main">
				<div class="postbox course_info">
					<h3><label>Instructor</label><span># Students</span><span># Badges</span><span># Certificates</span><span>Average</span><span># Courses</span></h3>
					<div class="inside">
						<ul>
						<?php
							$course_info=array();
							foreach($st_num as $st){
								$course_info[$st->instructor]=array(
									'instructor' => get_the_author_meta('display_name',$st->instructor),
									'students' => $st->students,
									'badge'=> 'n/a',
									'pass'=>'n/a',
									'avg'=>'n/a',
									'courses'=>$st->courses
									);
							}
							foreach($bg_num as $bg){
								if(isset($bg->badge))	
									$course_info[$st->instructor]['badge']=$bg->badge;
							}
							foreach($pass_num as $pass){
								if(isset($pass->pass))	
								$course_info[$st->instructor]['pass']=$pass->pass;
							}
							foreach($avg_num as $avg){
								if(isset($avg->avg))	
								$course_info[$st->instructor]['avg']=$avg->avg;
							}

							

							foreach($course_info as $course){
								if(isset($course['instructor']))
								echo '<li><label>'.$course['instructor'].'</label><span>'.$course['students'].'</span><span>'.$course['badge'].'</span><span>'.$course['pass'].'</span><span>'.$course['avg'].'</span><span>'.$course['courses'].'</span>';	
							}
						?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}



function vibe_my_course_module_overview($subtab='') {
	global $wpdb, $wp_locale,$bp;

	$total_students = $start_date = $end_date = $total_coursetaken = $total_coursefinished = $total_badges = $total_certificates = 0;

	$user_id = get_current_user_id();
	

	$start_date = isset( $_POST['start_date'] ) ? $_POST['start_date'] : 0;
	$end_date	= isset( $_POST['end_date'] ) ? $_POST['end_date'] : 0;

	if($subtab =='custom'){
		 echo '<form method="post" action=""><h3>Custom Date Selection</h3>
				<p><label for="from">From:</label> 
				<input type="text" name="start_date" id="from" value="'.(isset($start_date)?$start_date:'2014-01-01').'" class="date-picker-field">
				 <label for="to">&nbsp;&nbsp; To:</label> 
				<input type="text" name="end_date" id="to" value="'.(isset($end_date)?$end_date:'2014-02-01').'" class="date-picker-field">
				<input type="submit" class="button" value="Show"></p>
			</form>';
	}

   

	$ct=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT SUM(rel.meta_value) as total_students
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'vibe_students'
		AND     posts.post_author = $user_id
	"));

	$total_coursetaken=$ct[0]->total_students;
	

	$bg=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT SUM(rel.meta_value) as total_badge
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'badge',
		AND     posts.post_author = $user_id
	"));
	

	$total_badges = $bg[0]->total_badge;

	$ps=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT SUM(rel.meta_value) as total_pass
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'pass',
		AND     posts.post_author = $user_id
	"));
	$total_certificates=$ps[0]->total_pass;
	?>
	<div id="poststuff" class="vibe-reports-wrap">
		<div class="vibe-reports-sidebar">
			<div class="postbox">
				<h3><span><?php _e( 'Total Students in My Courses', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ( $total_coursetaken > 0 ) echo $total_coursetaken; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total Badges given in My Courses', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ($total_badges>0) echo $total_badges; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total Certificates given in My Courses', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ($total_certificates>0) echo $total_certificates; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
		</div>
		<div class="vibe-reports-main">
			<div class="postbox">
				<?php if($subtab == 'month'){ ?>
				<h3><span><?php _e( 'This year\'s monthly subscriptions', 'vibe' ); ?></span></h3>
				<?php }else{ ?>
				<h3><span><?php _e( 'This month\'s daily subscriptions', 'vibe' ); ?></span></h3>
				<?php } ?>
				<div class="inside chart">
					<div id="placeholder" style="width:100%; overflow:hidden; height:568px; position:relative;"></div>
					<div id="subscription_legend"></div>
				</div>
			</div>
		</div>
	</div>
	<?php

	
	if(isset($start_date) && $start_date !=0){
		$start_date = strtotime( date('Ymd', strtotime( $start_date ) ) );
		$end_date = strtotime( date('Ymd', strtotime( $end_date ) ) );		
	}else{
		$start_date = strtotime( date('Ymd', strtotime( date('Ym', current_time('timestamp') ) . '01' ) ) );
		$end_date = strtotime( date('Ymd', current_time( 'timestamp' ) ) );	
	}
	

	

	// Blank date ranges to begin
	$subscription_counts = $student_nos = array();
	$count = 0;
	$days = ( $end_date - $start_date ) / ( 60 * 60 * 24 );
	
	
	
	if ( $days == 0 )
		$days = 1;

	while ( $count < $days ) {
		if($subtab == 'month')
			$time = strtotime( date( 'Ymd', strtotime( '+ ' . $count . ' MONTH', $start_date ) ) ) . '000';
		else
			$time = strtotime( date( 'Ymd', strtotime( '+ ' . $count . ' DAY', $start_date ) ) ) . '000';

		$subscription_counts[ $time ] = $student_nos[ $time ] = 0;

		$count++;
	}

	// Get order ids and dates in range
	// 
	// Activity Table prefix -> No solution found in BuddyPress, so hardcoding this :((
	
	$table_name = $wpdb->prefix.'bp_'.'activity';
	$meta_table_name = $wpdb->prefix.'bp_'.'activity_meta';
	$courses_started = apply_filters('vibe_reports_sales_overview_orders', $wpdb->get_results( "
		SELECT activity.user_id, activity.date_recorded 
		FROM $table_name AS activity
		LEFT JOIN $meta_table_name AS rel ON posts.id = rel.activity_id
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'start_course'
		AND 	date_recorded > '" . date('Y-m-d', $start_date ) . "'
		AND 	date_recorded < '" . date('Y-m-d', strtotime('+1 day', $end_date ) ) . "'
		AND     rel.meta_key = 'instructor'
		AND     rel.meta_value = $user_id
		ORDER BY date_recorded ASC
	" ) );

	
	if ( $courses_started ) {
		foreach ( $courses_started as $course_start ) {

			
			$time = strtotime( date( 'Ymd', strtotime( $course_start->date_recorded ) ) ) . '000';
			if ( isset( $subscription_counts[ $time ] ) )
				$subscription_counts[ $time ]++;
			else
				$subscription_counts[ $time ] = 1;


		}
	}

	
	//print_r($subscription_counts);

	$subscription_counts_array =  array();

	foreach ( $subscription_counts as $key => $count )
		$subscription_counts_array[] = array( esc_js( $key ), esc_js( $count ) );

	
	$subscription_data = array( 'subscription_counts' => $subscription_counts_array, );

	$chart_data = json_encode( $subscription_data );
	?>
	<script type="text/javascript">
		jQuery(function(){
			var subscription_data = jQuery.parseJSON( '<?php echo $chart_data; ?>' );

			var d = subscription_data.subscription_counts;

			for (var i = 0; i < d.length; ++i) d[i][0] += 60 * 60 * 1000;

			var placeholder = jQuery("#placeholder");

			var plot = jQuery.plot(placeholder, [ { label: "<?php echo esc_js( __( 'Number of Courses Started', 'vibe' ) ) ?>", data: d } ], {
				legend: {
					container: jQuery('#subscription_legend'),
					noColumns: 2
				},
				series: {
					lines: { show: true, fill: true },
					points: { show: true }
				},
				grid: {
					show: true,
					aboveData: false,
					color: '#aaa',
					backgroundColor: '#fff',
					borderWidth: 2,
					borderColor: '#aaa',
					clickable: false,
					hoverable: true,
					markings: weekendAreas
				},
				xaxis: {
					mode: "time",
					timeformat: "%d %b",
					monthNames: <?php echo json_encode( array_values( $wp_locale->month_abbrev ) ) ?>,
					tickLength: 1,
					minTickSize: [1, "day"]
				},
				yaxes: [ { min: 0, tickSize: 10, tickDecimals: 0 }, { position: "right", min: 0, tickDecimals: 2 } ],
		   		colors: ["#78c8c9"]
		 	});

		 	placeholder.resize();

			<?php vibe_weekend_area_js(); ?>
			<?php vibe_tooltip_js(); ?>
		});
	</script>
	<?php
}


function lms_stats_my_course_content($subtab='overview'){
	
	global $wpdb;
	$user_id = get_current_user_id();
	

	$ct=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT SUM(rel.meta_value) as total_students
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'vibe_students'
		AND     posts.post_author = $user_id
	"));
	$total_coursetaken=$ct[0]->total_students;


	if($subtab == 'popular')
		$orderby='CAST(students AS UNSIGNED)';
	else
		$orderby='posts.ID';

	$st_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.ID,posts.post_title, rel.meta_value as students
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'vibe_students'
		AND     posts.post_author = $user_id
		ORDER BY $orderby DESC
	"));

	

	$bg_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.ID,rel.meta_value as badge
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'badge'
		AND     posts.post_author = $user_id
	"));
	$pass_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.ID,rel.meta_value as pass
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'pass'
		AND     posts.post_author = $user_id
	"));
	$avg_num=apply_filters('vibe_course_module_overview_reports', $wpdb->get_results("
		SELECT posts.ID,rel.meta_value as avg
	    FROM {$wpdb->posts} AS posts
	    LEFT JOIN {$wpdb->postmeta} AS rel ON posts.ID = rel.post_id
	    WHERE 	posts.post_type 	= 'course'
		AND 	posts.post_status 	= 'publish'
		AND 	rel.meta_key   = 'average'
		AND     posts.post_author = $user_id
	"));
	

	
	

	$table_name = $wpdb->prefix.'bp_'.'activity';
	$meta_table_name = $wpdb->prefix.'bp_'.'activity_meta';
	$cs = apply_filters('vibe_reports_sales_overview_orders', $wpdb->get_results( "
		SELECT count(activity.id)
		FROM $table_name AS activity
		LEFT JOIN $meta_table_name AS rel ON activity.id = rel.activity_id
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'start_course'
		AND     rel.meta_key = 'instructor'
		AND     rel.meta_value = $user_id
	" ) );

	$courses_started=$cs[0]->total;

	$cf = apply_filters('vibe_reports_course_evaluated_overview_orders', $wpdb->get_results( "
		SELECT count(activity.id) as total 
		FROM $table_name AS activity
		LEFT JOIN $meta_table_name AS rel ON activity.id = rel.activity_id
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'course_evaluated'
		AND     rel.meta_key = 'instructor'
		AND     rel.meta_value = $user_id
	" ) );

	$courses_finished=$cf[0]->total;

	$qe = apply_filters('vibe_reports_sales_overview_orders', $wpdb->get_results( "
		SELECT count(activity.id) as total 
		FROM $table_name AS activity
		LEFT JOIN $meta_table_name AS rel ON activity.id = rel.activity_id
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'quiz_evaluated'
		AND     rel.meta_key = 'instructor'
		AND     rel.meta_value = $user_id
	" ) );
	$quiz_evaluated = $qe[0]->total;

	$uc = apply_filters('vibe_reports_sales_overview_orders', $wpdb->get_results( "
		SELECT count(activity.id) as total 
		FROM $table_name AS activity
		LEFT JOIN $meta_table_name AS rel ON activity.id = rel.activity_id
		WHERE 	activity.component 	= 'course'
		AND 	activity.type 	= 'unit_complete'
		AND     rel.meta_key = 'instructor'
		AND     rel.meta_value = $user_id
	" ) );
	$unit_complete = $uc[0]->total;
	?>
	<div id="poststuff" class="vibe-reports-wrap">
		<div class="vibe-reports-sidebar">
			<div class="postbox">
				<h3><span><?php _e( 'Total Students taking my courses', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ( $total_coursetaken > 0 ) echo $total_coursetaken; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'My Courses Finished by Students', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ( $courses_finished > 0 ) echo $courses_finished; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'My Units Finished by Students', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ($unit_complete>0) echo $unit_complete; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'My Quiz Finished by Students', 'vibe' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php if ($quiz_evaluated>0) echo $quiz_evaluated; else _e( 'n/a', 'vibe' ); ?></p>
				</div>
			</div>
		</div>
		<div class="vibe-reports-main">
				<div class="postbox course_info">
					<h3><label>Course Title</label><span># Students</span><span># Badges</span><span># Certificates</span><span>Average</span></h3>
					<div class="inside">
						<ul>
						<?php
							$course_info=array();
							foreach($st_num as $st){
								$course_info[$st->ID]=array(
									'title' => $st->post_title,
									'students' => $st->students,
									'badge'=> 'n/a',
									'pass'=>'n/a',
									'avg'=>'n/a'
									);
							}
							foreach($bg_num as $bg){
								if(isset($bg->badge))	
									$course_info[$bg->ID]['badge']=$bg->badge;
							}
							foreach($pass_num as $pass){
								if(isset($pass->pass))	
								$course_info[$pass->ID]['pass']=$pass->pass;
							}
							foreach($avg_num as $avg){
								if(isset($avg->avg))	
								$course_info[$avg->ID]['avg']=$avg->avg;
							}


							foreach($course_info as $course){
								if(isset($course['title']))
								echo '<li><label>'.$course['title'].'</label><span>'.$course['students'].'</span><span>'.$course['badge'].'</span><span>'.$course['pass'].'</span><span>'.$course['avg'].'</span>';	
							}
						?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}

?>