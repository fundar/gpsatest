<?php


function add_vibe_metaboxes(){
	$prefix = 'vibe_';
	$sidebars=$GLOBALS['wp_registered_sidebars'];
	$sidebararray=array();
	foreach($sidebars as $sidebar){
	    $sidebararray[]= array('label'=>$sidebar['name'],'value'=>$sidebar['id']);
	}

	$post_metabox = array(
		 
		
		 array( // Single checkbox
			'label'	=> __('Post Sub-Title','vibe'), // <label>
			'desc'	=> __('Post Sub- Title.','vibe'), // description
			'id'	=> $prefix.'subtitle', // field id and name
			'type'	=> 'textarea', // type of field
	        'std'   => ''
	                ), 

	     array( // Single checkbox
			'label'	=> __('Post Template','vibe'), // <label>
			'desc'	=> __('Select a post template for showing content.','vibe'), // description
			'id'	=> $prefix.'template', // field id and name
			'type'	=> 'select', // type of field
	        'options' => array(
	                    1=>array('label'=>'Default','value'=>''),
	                    2=>array('label'=>'Content on Right','value'=>'right'),
	                    3=>array('label'=>'Content on Left','value'=>'left'),
	        ),
	        'std'   => ''
		),
	     array( // Single checkbox
			'label'	=> __('Sidebar','vibe'), // <label>
			'desc'	=> __('Select a Sidebar | Default : mainsidebar','vibe'), // description
			'id'	=> $prefix.'sidebar', // field id and name
			'type'	=> 'select',
	                'options' => $sidebararray
	                ),
	    array( // Single checkbox
			'label'	=> __('Show Page Title','vibe'), // <label>
			'desc'	=> __('Show Page/Post Title.','vibe'), // description
			'id'	=> $prefix.'title', // field id and name
			'type'	=> 'showhide', // type of field
	                'options' => array(
	                  0 =>'Hide',
	                  1 => 'Show'  
	                ),
	                'std'   => 'S'
	                ),
	    array( // Single checkbox
			'label'	=> __('Show Author Information','vibe'), // <label>
			'desc'	=> __('Author information below post content.','vibe'), // description
			'id'	=> $prefix.'author', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	                            0 =>'Hide',
	                            1 => 'Show'  
	                ),
	                'std'   => 'H'
		),    
	     
	    array( // Single checkbox
			'label'	=> __('Show Breadcrumbs','vibe'), // <label>
			'desc'	=> __('Show breadcrumbs.','vibe'), // description
			'id'	=> $prefix.'breadcrumbs', // field id and name
			'type'	=> 'showhide', // type of field
	                'options' => array(
	                  0 =>'Hide',
	                  1 => 'Show'  
	                ),
	                'std'   => 'S'
	            ),
	    array( // Single checkbox
			'label'	=> __('Show Prev/Next Arrows','vibe'), // <label>
			'desc'	=> __('Show previous/next links on top below the Subheader.','vibe'), // description
			'id'	=> $prefix.'prev_next', // field id and name
			'type'	=> 'showhide', // type of field
	                'options' => array(
	                  0 =>'Hide',
	                  1 => 'Show'  
	                ),
	                'std'   => 'H'
		),
	);

	$page_metabox = array(
			

	        0 => array( // Single checkbox
			'label'	=> __('Show Page Title','vibe'), // <label>
			'desc'	=> __('Show Page/Post Title.','vibe'), // description
			'id'	=> $prefix.'title', // field id and name
			'type'	=> 'showhide', // type of field
	                'options' => array(
	                  0 =>'Hide',
	                  1 => 'Show'  
	                ),
	                'std'   => 'S'
	                ),


	        1 => array( // Single checkbox
			'label'	=> __('Page Sub-Title','vibe'), // <label>
			'desc'	=> __('Page Sub- Title.','vibe'), // description
			'id'	=> $prefix.'subtitle', // field id and name
			'type'	=> 'textarea', // type of field
	        'std'   => ''
	                ),

	        2 => array( // Single checkbox
			'label'	=> __('Show Breadcrumbs','vibe'), // <label>
			'desc'	=> __('Show breadcrumbs.','vibe'), // description
			'id'	=> $prefix.'breadcrumbs', // field id and name
			'type'	=> 'showhide', // type of field
	                'options' => array(
	                  0 =>'Hide',
	                  1 => 'Show'  
	                ),
	                'std'   => 'S'
	            ),
	    3 => array( // Single checkbox
			'label'	=> __('Sidebar','vibe'), // <label>
			'desc'	=> __('Select Sidebar | Sidebar : mainsidebar','vibe'), // description
			'id'	=> $prefix.'sidebar', // field id and name
			'type'	=> 'select',
	                'options' => $sidebararray
	                ),
	    );



	$featured_metabox = array(
	     array( // Select box
			'label'	=> __('Media','vibe'), // <label>
			'id'	=> $prefix.'select_featured', // field id and name
			'type'	=> 'select', // type of field
			'options' => array ( // array of options
	                        'zero' => array ( // array key needs to be the same as the option value
					'label' => __('Disable','vibe'), // text displayed as the option
					'value'	=> 'disable' // value stored for the option
				),
				'one' => array ( // array key needs to be the same as the option value
					'label' => __('Gallery','vibe'), // text displayed as the option
					'value'	=> 'gallery' // value stored for the option
				),
				'two' => array (
					'label' => __('Self Hosted Video','vibe'),
					'value'	=> 'video'
				),
	                        'three' => array (
					'label' => __('IFrame Video','vibe'),
					'value'	=> 'iframevideo'
				),
				'four' => array (
					'label' => __('Self Hosted Audio','vibe'),
					'value'	=> 'audio'
				),
	                        'five' => array (
					'label' => __('Other','vibe'),
					'value'	=> 'other'
				)
			)
		),
	    
	        
	        array( // Repeatable & Sortable Text inputs
			'label'	=> __('Gallery','vibe'), // <label>
			'desc'	=> __('Create a Gallery in post.','vibe'), // description
			'id'	=> $prefix.'slider', // field id and name
			'type'	=> 'gallery' // type of field
		),
	        
		array( // Textarea
			'label'	=> __('Self Hosted Video','vibe'), // <label>
			'desc'	=> __('Select video files (of same Video): xxxx.mp4,xxxx.ogv,xxxx.ogg for max. browser compatibility','vibe'), // description
			'id'	=> $prefix.'featuredvideo', // field id and name
			'type'	=> 'video' // type of field
		),
	        array( // Textarea
			'label'	=> __('IFRAME Video','vibe'), // <label>
			'desc'	=> __('Insert Iframe (Youtube,Vimeo..) embed code of video ','vibe'), // description
			'id'	=> $prefix.'featurediframevideo', // field id and name
			'type'	=> 'textarea' // type of field
		),
	        array( // Text Input
			'label'	=> __('Audio','vibe'), // <label>
			'desc'	=> __('Select audio files (of same Audio): xxxx.mp3,xxxx.wav,xxxx.ogg for max. browser compatibility','vibe'), // description
			'id'	=> $prefix.'featured_audio', // field id and name
			'type'	=> 'audio' // type of field
		),
	        array( // Textarea
			'label'	=> __('Other','vibe'), // <label>
			'desc'	=> __('Insert Shortcode or relevant content.','vibe'), // description
			'id'	=> $prefix.'featuredother', // field id and name
			'type'	=> 'textarea' // type of field
		)
		
	    );




	$course_metabox = array(  
		array( // Single checkbox
			'label'	=> __('Sidebar','vibe'), // <label>
			'desc'	=> __('Select a Sidebar | Default : mainsidebar','vibe'), // description
			'id'	=> $prefix.'sidebar', // field id and name
			'type'	=> 'select',
	        'options' => $sidebararray,
	        'std'=>'coursesidebar'
	        ),
		array( // Text Input
			'label'	=> __('Total Duration of Course','vibe'), // <label>
			'desc'	=> __('Duration of Course (in days).','vibe'), // description
			'id'	=> $prefix.'duration', // field id and name
			'type'	=> 'number', // type of field
			'std'	=> 0,
		),

		array( // Text Input
			'label'	=> __('Total number of Students in Course','vibe'), // <label>
			'desc'	=> __('Total number of Students who have taken this Course.','vibe'), // description
			'id'	=> $prefix.'students', // field id and name
			'type'	=> 'number', // type of field
			'std'	=> 0,
		),
		array( // Text Input
			'label'	=> __('Auto Evaluation','vibe'), // <label>
			'desc'	=> __('Evalute Courses based on Quizes scores available in Course (* Requires atleast 1 Quiz in course)','vibe'), // description
			'id'	=> $prefix.'course_auto_eval', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          0 =>'Hide',
	          1 => 'Show'  
	        ),
	        'std'   => 'H'
		),
		array( // Text Input
			'label'	=> __('Excellence Badge','vibe'), // <label>
			'desc'	=> __('Upload badge image which Students recieve upon course completion','vibe'), // description
			'id'	=> $prefix.'course_badge', // field id and name
			'type'	=> 'image' // type of field
		),

		array( // Text Input
			'label'	=> __('Badge Percentage','vibe'), // <label>
			'desc'	=> __('Badge is given to people passing above percentage (out of 100)','vibe'), // description
			'id'	=> $prefix.'course_badge_percentage', // field id and name
			'type'	=> 'number' // type of field
		),

		array( // Text Input
			'label'	=> __('Badge Title','vibe'), // <label>
			'desc'	=> __('Title is shown on hovering the badge.','vibe'), // description
			'id'	=> $prefix.'course_badge_title', // field id and name
			'type'	=> 'text' // type of field
		),

		array( // Text Input
			'label'	=> __('Completion Certificate','vibe'), // <label>
			'desc'	=> __('Enable Certificate image which Students recieve upon course completion (out of 100)','vibe'), // description
			'id'	=> $prefix.'course_certificate', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	          0 =>'Hide',
	          1 => 'Show'  
	        ),
	        'std'   => 'H'
		),

		array( // Text Input
			'label'	=> __('Passing Percentage','vibe'), // <label>
			'desc'	=> __('Course passing percentage, for completion certificate','vibe'), // description
			'id'	=> $prefix.'course_passing_percentage', // field id and name
			'type'	=> 'number' // type of field
		),
		array( // Text Input
			'label'	=> __('Drip Feed','vibe'), // <label>
			'desc'	=> __('Enable Drip Feed course','vibe'), // description
			'id'	=> $prefix.'course_drip', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          0 =>'Hide',
	          1 => 'Show'  
	        ),
	        'std'   => 'H'
		),
		array( // Text Input
			'label'	=> __('Drip Feed Duration','vibe'), // <label>
			'desc'	=> __('Duration between consecutive Drip feed units (in Days)','vibe'), // description
			'id'	=> $prefix.'course_drip_duration', // field id and name
			'type'	=> 'number', // type of field
		),

		

		array( // Text Input
			'label'	=> __('Course Curriculum','vibe'), // <label>
			'desc'	=> __('Set Course Curriculum, prepare units and quizes before setting up curriculum','vibe'), // description
			'id'	=> $prefix.'course_curriculum', // field id and name
			'post_type1' => 'unit',
			'post_type2' => 'quiz',
			'type'	=> 'curriculum' // type of field
		),
		 
		array( // Text Input
			'label'	=> __('Pre-Required Course','vibe'), // <label>
			'desc'	=> __('Pre Required course for this course','vibe'), // description
			'id'	=> $prefix.'pre_course', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'course'
		), 
		array( // Text Input
			'label'	=> __('Course Forum','vibe'), // <label>
			'desc'	=> __('Connect Forum with Course.','vibe'), // description
			'id'	=> $prefix.'forum', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'forum'
		),
		array( // Text Input
			'label'	=> __('Course Group','vibe'), // <label>
			'desc'	=> __('Connect a Group with Course.','vibe'), // description
			'id'	=> $prefix.'group', // field id and name
			'type'	=> 'groups', // type of field
		),
		array( // Text Input
			'label'	=> __('Course Completion Message','vibe'), // <label>
			'desc'	=> __('This message is shown to users when they Finish submit the course','vibe'), // description
			'id'	=> $prefix.'course_message', // field id and name
			'type'	=> 'editor', // type of field
			'std'	=> 'Thank you for Finish the Course.'
		),
	);

	$course_product_metabox = array(
		array( // Text Input
			'label'	=> __('Free Course','vibe'), // <label>
			'desc'	=> __('Course is Free for all','vibe'), // description
			'id'	=> $prefix.'course_free', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          0 =>'Hide',
	          1 => 'Show'  
	        ),
	        'std'   => 'H'
		),
		array(
			'label'	=> __('Associated Product','vibe'), // <label>
			'desc'	=> __('Associated Product with the Course.','vibe'), // description
			'id'	=> $prefix.'product', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type'=> 'product',
	        'std'   => ''
		),
	);
	$unit_metabox = array(  
		array( // Single checkbox
			'label'	=> __('Unit Description','vibe'), // <label>
			'desc'	=> __('Small Description.','vibe'), // description
			'id'	=> $prefix.'subtitle', // field id and name
			'type'	=> 'textarea', // type of field
	        'std'   => ''
	        ),
		array( // Text Input
			'label'	=> __('Unit Type','vibe'), // <label>
			'desc'	=> __('Select Unit type from Video , Audio , Podcast, General , ','vibe'), // description
			'id'	=> $prefix.'type', // field id and name
			'type'	=> 'select', // type of field
			'options' => array(
	          array( 'label' =>'Video','value'=>'play'),
	          array( 'label' =>'Audio','value'=>'music-file-1'),
	          array( 'label' =>'Podcast','value'=>'podcast'),
	          array( 'label' =>'General','value'=>'text-document'),
	        ),
	        'std'   => 'text-document'
		),
		array( // Text Input
			'label'	=> __('Free Unit','vibe'), // <label>
			'desc'	=> __('Set Free unit, viewable to all','vibe'), // description
			'id'	=> $prefix.'free', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	          0 =>'Hide',
	          1 => 'Show'  
	        ),
	        'std'   => 'H'
		),
		array( // Text Input
			'label'	=> __('Unit Duration','vibe'), // <label>
			'desc'	=> __('Duration in Minutes','vibe'), // description
			'id'	=> $prefix.'duration', // field id and name
			'type'	=> 'number' // type of field
		),
		/*
	    array( // Text Input
			'label'	=> __('Drip Feed Next Unit','vibe'), // <label>
			'desc'	=> __('Enable next unit after X number of days. 0 to disable.','vibe'), // description
			'id'	=> $prefix.'drip_feed', // field id and name
			'type'	=> 'number', // type of field
		),*/
		array( // Text Input
			'label'	=> __('Unit Forum','vibe'), // <label>
			'desc'	=> __('Connect Forum with Unit.','vibe'), // description
			'id'	=> $prefix.'forum', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'forum'
		),
	);


	$question_metabox = array(  
		array( // Text Input
			'label'	=> __('Question Type','vibe'), // <label>
			'desc'	=> __('Select Question type, ','vibe'), // description
			'id'	=> $prefix.'question_type', // field id and name
			'type'	=> 'select', // type of field
			'options' => array(
	          array( 'label' =>'Single Choice','value'=>'single'),
	          array( 'label' =>'Multiple Choice','value'=>'multiple'),
	          array( 'label' =>'Small Text','value'=>'smalltext'),
	          array( 'label' =>'Large Text','value'=>'largetext'),
	        ),
	        'std'   => 'single'
		),
		array( // Text Input
			'label'	=> __('Question Options (For Single/Multiple Choice)','vibe'), // <label>
			'desc'	=> __('Single/Mutiple Choice question options','vibe'), // description
			'id'	=> $prefix.'question_options', // field id and name
			'type'	=> 'repeatable_count' // type of field
		),
	    array( // Text Input
			'label'	=> __('Correct Answer','vibe'), // <label>
			'desc'	=> __('Enter Choice Number (1,2..) or comma saperated Choice numbers (1,2..) or Correct Answer for small text (All possible answers comma saperated) | 0 for No Answer or Manual Check','vibe'), // description
			'id'	=> $prefix.'question_answer', // field id and name
			'type'	=> 'text', // type of field
			'std'	=> 0
		),
	);

	$quiz_metabox = array(  
		array( // Text Input
			'label'	=> __('Quiz Subtitle','vibe'), // <label>
			'desc'	=> __('Quiz Subtitle.','vibe'), // description
			'id'	=> $prefix.'subtitle', // field id and name
			'type'	=> 'text', // type of field
			'std'	=> ''
		),

		array( // Text Input
			'label'	=> __('Quiz Duration','vibe'), // <label>
			'desc'	=> __('Quiz duration in minutes. Enables Timer & auto submits on expire. 0 to disable.','vibe'), // description
			'id'	=> $prefix.'duration', // field id and name
			'type'	=> 'number', // type of field
			'std'	=> 0
		),
		
		array( // Text Input
			'label'	=> __('Auto Evatuate Results','vibe'), // <label>
			'desc'	=> __('Evaluate results as soon as quiz is complete. (* No Large text questions ), Diable for manual evaluate','vibe'), // description
			'id'	=> $prefix.'quiz_auto_evaluate', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          0 =>'Hide',
	          1 => 'Show'  
	        ),
	        'std'   => 'H'
		), 
		/*
		array( // Text Input
			'label'	=> __('Enable Revision','vibe'), // <label>
			'desc'	=> __('Student can change an answer once moved to next question.','vibe'), // description
			'id'	=> $prefix.'quiz_revision', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	          0 =>'Hide',
	          1 => 'Show'  
	        ),
	        'std'   => 'H'
		), */
		array( // Text Input
			'label'	=> __('Send Notification upon evaluation','vibe'), // <label>
			'desc'	=> __('Student recieve notification when quiz is evaluated.','vibe'), // description
			'id'	=> $prefix.'quiz_notification', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	          0 =>'Hide',
	          1 => 'Show'  
	        ),
	        'std'   => 'H'
		),
		array( // Text Input
			'label'	=> __('Post Quiz Message','vibe'), // <label>
			'desc'	=> __('This message is shown to users when they submit the quiz','vibe'), // description
			'id'	=> $prefix.'quiz_message', // field id and name
			'type'	=> 'editor', // type of field
			'std'	=> 'Thank you for Submitting the Quiz. Check Results in your Profile.'
		),
		
	    array( // Text Input
			'label'	=> __('Quiz Questions','vibe'), // <label>
			'desc'	=> __('Quiz questions','vibe'), // description
			'id'	=> $prefix.'quiz_questions', // field id and name
			'type'	=> 'repeatable_selectcpt', // type of field
			'post_type' => 'question',
			'std'	=> 0
		),
	    
		
	);

	$testimonial_metabox = array(  
		array( // Text Input
			'label'	=> __('Author Name','vibe'), // <label>
			'desc'	=> __('Enter the name of the testimonial author.','vibe'), // description
			'id'	=> $prefix.'testimonial_author_name', // field id and name
			'type'	=> 'text' // type of field
		),
	        array( // Text Input
			'label'	=> __('Designation','vibe'), // <label>
			'desc'	=> __('Enter the testimonial author\'s designation.','vibe'), // description
			'id'	=> $prefix.'testimonial_author_designation', // field id and name
			'type'	=> 'text' // type of field
		),
	);




	$product_metabox = array(  
		array( // Text Input
			'label'	=> __('Associated Courses','vibe'), // <label>
			'desc'	=> __('Associated Courses with this product. Enables access to the course.','vibe'), // description
			'id'	=> $prefix.'courses', // field id and name
			'type'	=> 'selectmulticpt', // type of field
			'post_type'=>'course'
		),
	    array( // Text Input
			'label'	=> __('Subscription ','vibe'), // <label>
			'desc'	=> __('Enable if Product is Subscription Type (Price per month)','vibe'), // description
			'id'	=> $prefix.'subscription', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	                    0 =>'Hide',
	                    1 => 'Show'  
	                ),
	                'std'   => 'H'
		),
	    array( // Text Input
			'label'	=> __('Subscription Duration','vibe'), // <label>
			'desc'	=> __('Duration for Subscription Products (in days)','vibe'), // description
			'id'	=> $prefix.'duration', // field id and name
			'type'	=> 'number' // type of field
		),
	);

	$payments_metabox = array(  
		array( // Text Input
			'label'	=> __('From','vibe'), // <label>
			'desc'	=> __('Date on which Payment was done.','vibe'), // description
			'id'	=> $prefix.'date_from', // field id and name
			'type'	=> 'text', // type of field
		),
		array( // Text Input
			'label'	=> __('To','vibe'), // <label>
			'desc'	=> __('Date on which Payment was done.','vibe'), // description
			'id'	=> $prefix.'date_to', // field id and name
			'type'	=> 'text', // type of field
		),
	    array( // Text Input
			'label'	=> __('Instructor and Commissions','vibe'), // <label>
			'desc'	=> __('Instructor commissions','vibe'), // description
			'id'	=> $prefix.'instructor_commissions', // field id and name
			'type'	=> 'payments' // type of field
		),
	);
/*
$certificate_metabox = array(  
		array( // Text Input
			'label'	=> __('Custom Class','vibe'), // <label>
			'desc'	=> __('Add Custom Class over Certificate container.','vibe'), // description
			'id'	=> $prefix.'custom_class', // field id and name
			'type'	=> 'text', // type of field
		),
		array( // Text Input
			'label'	=> __('Custom CSS','vibe'), // <label>
			'desc'	=> __('Add Custom CSS for Certificate','vibe'), // description
			'id'	=> $prefix.'custom_css', // field id and name
			'type'	=> 'textarea', // type of field
		),
	);	
*/
	$post_metabox = new custom_add_meta_box( 'post-settings', 'Post Settings', $post_metabox, 'post', true );
	$page_metabox = new custom_add_meta_box( 'page-settings', 'Page Settings', $page_metabox, 'page', true );

	$course_box = new custom_add_meta_box( 'page-settings', 'Course Settings', $course_metabox, 'course', true );
	$course_product_box = new custom_add_meta_box( 'post-settings', 'Course Product', $course_product_metabox, 'course', true );
	$unit_box = new custom_add_meta_box( 'page-settings', 'Unit Settings', $unit_metabox, 'unit', true );

	$question_box = new custom_add_meta_box( 'page-settings', 'Question Settings', $question_metabox, 'question', true );
	$quiz_box = new custom_add_meta_box( 'page-settings', 'Question Settings', $quiz_metabox, 'quiz', true );

	$product_box = new custom_add_meta_box( 'page-settings', 'Product Course Settings', $product_metabox, 'product', true );
	$testimonial_box = new custom_add_meta_box( 'testimonial-info', 'Testimonial Author Information', $testimonial_metabox, 'testimonials', true );
	$payments_metabox = new custom_add_meta_box( 'page-settings', 'Payments Settings', $payments_metabox, 'payments', true );
	//$certificates_metabox = new custom_add_meta_box( 'page-settings', 'Certificate Template Settings', $certificate_metabox, 'certificate', true );
}
add_action('init','add_vibe_metaboxes');


add_action( 'add_meta_boxes', 'add_vibe_editor' );
if(!function_exists('add_vibe_editor')){
	function add_vibe_editor(){
	    add_meta_box( 'vibe-editor', __( 'Page Builder', 'vibe' ), 'vibe_layout_editor', 'page', 'normal', 'high' );
	}
}
