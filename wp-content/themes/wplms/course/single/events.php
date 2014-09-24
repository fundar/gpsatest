<?php
global $post;
if(class_exists('WPLMS_Events_Interface')){
?>

<div class="course_title">
	<h1><?php the_title(); _e(' Events','vibe')  ?></h1>
</div>
<?php
	    $events_interface = new WPLMS_Events_Interface;
		$events_interface->wplms_event_calendar(get_the_ID());
		
 }
?>