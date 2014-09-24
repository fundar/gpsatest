<?php
if ( !defined( 'ABSPATH' ) ) exit;

function add_vibe_metaboxes(){
	$prefix = 'vibe_';
	$sidebars=$GLOBALS['wp_registered_sidebars'];
	$sidebararray=array();
	foreach($sidebars as $sidebar){
	    $sidebararray[]= array('label'=>$sidebar['name'],'value'=>$sidebar['id']);
	}
	$course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400);
	$drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400);
	$unit_duration_parameter = apply_filters('vibe_unit_duration_parameter',60);
	$quiz_duration_parameter = apply_filters('vibe_quiz_duration_parameter',60);
	$product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400);
	$assignment_duration_parameter = apply_filters('vibe_assignment_duration_parameter',86400);

	$post_metabox = array(
		 
		
		 array( // Single checkbox
			'label'	=> __('Post Sub-Title','vibe-customtypes'), // <label>
			'desc'	=> __('Post Sub- Title.','vibe-customtypes'), // description
			'id'	=> $prefix.'subtitle', // field id and name
			'type'	=> 'textarea', // type of field
	        'std'   => ''
	                ), 

	     array( // Single checkbox
			'label'	=> __('Post Template','vibe-customtypes'), // <label>
			'desc'	=> __('Select a post template for showing content.','vibe-customtypes'), // description
			'id'	=> $prefix.'template', // field id and name
			'type'	=> 'select', // type of field
	        'options' => array(
	                    1=>array('label'=>__('Default','vibe'),'value'=>''),
	                    2=>array('label'=>__('Content on Right','vibe'),'value'=>'right'),
	                    3=>array('label'=>__('Content on Left','vibe'),'value'=>'left'),
	        ),
	        'std'   => ''
		),
	     array( // Single checkbox
			'label'	=> __('Sidebar','vibe-customtypes'), // <label>
			'desc'	=> __('Select a Sidebar | Default : mainsidebar','vibe-customtypes'), // description
			'id'	=> $prefix.'sidebar', // field id and name
			'type'	=> 'select',
	                'options' => $sidebararray
	                ),
	    array( // Single checkbox
			'label'	=> __('Show Page Title','vibe-customtypes'), // <label>
			'desc'	=> __('Show Page/Post Title.','vibe-customtypes'), // description
			'id'	=> $prefix.'title', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	                'std'   => 'S'
	                ),
	    array( // Single checkbox
			'label'	=> __('Show Author Information','vibe-customtypes'), // <label>
			'desc'	=> __('Author information below post content.','vibe-customtypes'), // description
			'id'	=> $prefix.'author', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	                'std'   => 'H'
		),    
	     
	    array( // Single checkbox
			'label'	=> __('Show Breadcrumbs','vibe-customtypes'), // <label>
			'desc'	=> __('Show breadcrumbs.','vibe-customtypes'), // description
			'id'	=> $prefix.'breadcrumbs', // field id and name
			'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	                'std'   => 'S'
	            ),
	    array( // Single checkbox
			'label'	=> __('Show Prev/Next Arrows','vibe-customtypes'), // <label>
			'desc'	=> __('Show previous/next links on top below the Subheader.','vibe-customtypes'), // description
			'id'	=> $prefix.'prev_next', // field id and name
			'type'	=> 'showhide', // type of field
	         'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	                'std'   => 'H'
		),
	);
	$post_metabox = apply_filters('wplms_post_metabox',$post_metabox);
	$page_metabox = array(
			

	        0 => array( // Single checkbox
			'label'	=> __('Show Page Title','vibe-customtypes'), // <label>
			'desc'	=> __('Show Page/Post Title.','vibe-customtypes'), // description
			'id'	=> $prefix.'title', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	                'std'   => 'S'
	                ),


	        1 => array( // Single checkbox
			'label'	=> __('Page Sub-Title','vibe-customtypes'), // <label>
			'desc'	=> __('Page Sub- Title.','vibe-customtypes'), // description
			'id'	=> $prefix.'subtitle', // field id and name
			'type'	=> 'textarea', // type of field
	        'std'   => ''
	                ),

	        2 => array( // Single checkbox
			'label'	=> __('Show Breadcrumbs','vibe-customtypes'), // <label>
			'desc'	=> __('Show breadcrumbs.','vibe-customtypes'), // description
			'id'	=> $prefix.'breadcrumbs', // field id and name
			'type'	=> 'showhide', // type of field
	         'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	                'std'   => 'S'
	            ),
	    3 => array( // Single checkbox
			'label'	=> __('Sidebar','vibe-customtypes'), // <label>
			'desc'	=> __('Select Sidebar | Sidebar : mainsidebar','vibe-customtypes'), // description
			'id'	=> $prefix.'sidebar', // field id and name
			'type'	=> 'select',
	                'options' => $sidebararray
	                ),
	    );


	$page_metabox = apply_filters('wplms_page_metabox',$page_metabox);


	$course_metabox = array(  
		array( // Single checkbox
			'label'	=> __('Sidebar','vibe-customtypes'), // <label>
			'desc'	=> __('Select a Sidebar | Default : mainsidebar','vibe-customtypes'), // description
			'id'	=> $prefix.'sidebar', // field id and name
			'type'	=> 'select',
	        'options' => $sidebararray,
	        'std'=>'coursesidebar'
	        ),
		array( // Text Input
			'label'	=> __('Total Duration of Course','vibe-customtypes'), // <label>
			'desc'	=> sprintf(__('Duration of Course (in %s)','vibe-customtypes'),calculate_duration_time($course_duration_parameter)), // description
			'id'	=> $prefix.'duration', // field id and name
			'type'	=> 'number', // type of field
			'std'	=> 10,
		),
		
		array( // Text Input
			'label'	=> __('Total number of Students in Course','vibe-customtypes'), // <label>
			'desc'	=> __('Total number of Students who have taken this Course.','vibe-customtypes'), // description
			'id'	=> $prefix.'students', // field id and name
			'type'	=> 'number', // type of field
			'std'	=> 0,
		),
		array( // Text Input
			'label'	=> __('Auto Evaluation','vibe-customtypes'), // <label>
			'desc'	=> __('Evalute Courses based on Quizes scores available in Course (* Requires atleast 1 Quiz in course)','vibe-customtypes'), // description
			'id'	=> $prefix.'course_auto_eval', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		),
		array( // Text Input
			'label'	=> __('Course Start Date','vibe-customtypes'), // <label>
			'desc'	=> __('Date from which Course Begins','vibe-customtypes'), // description
			'id'	=> $prefix.'start_date', // field id and name
			'type'	=> 'date', // type of field
		),
		array( // Text Input
			'label'	=> __('Maximum Students in Course','vibe-customtypes'), // <label>
			'desc'	=> __('Maximum number of students who can pursue the course at a time.','vibe-customtypes'), // description
			'id'	=> $prefix.'max_students', // field id and name
			'type'	=> 'number', // type of field
		),
		array( // Text Input
			'label'	=> __('Excellence Badge','vibe-customtypes'), // <label>
			'desc'	=> __('Upload badge image which Students recieve upon course completion','vibe-customtypes'), // description
			'id'	=> $prefix.'course_badge', // field id and name
			'type'	=> 'image' // type of field
		),

		array( // Text Input
			'label'	=> __('Badge Percentage','vibe-customtypes'), // <label>
			'desc'	=> __('Badge is given to people passing above percentage (out of 100)','vibe-customtypes'), // description
			'id'	=> $prefix.'course_badge_percentage', // field id and name
			'type'	=> 'number' // type of field
		),

		array( // Text Input
			'label'	=> __('Badge Title','vibe-customtypes'), // <label>
			'desc'	=> __('Title is shown on hovering the badge.','vibe-customtypes'), // description
			'id'	=> $prefix.'course_badge_title', // field id and name
			'type'	=> 'text' // type of field
		),

		array( // Text Input
			'label'	=> __('Completion Certificate','vibe-customtypes'), // <label>
			'desc'	=> __('Enable Certificate image which Students recieve upon course completion (out of 100)','vibe-customtypes'), // description
			'id'	=> $prefix.'course_certificate', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		),

		array( // Text Input
			'label'	=> __('Certificate Template','vibe-customtypes'), // <label>
			'desc'	=> __('Select a Certificate Template','vibe-customtypes'), // description
			'id'	=> $prefix.'certificate_template', // field id and name
			'type'	=> 'selectcpt', // type of field
	        'post_type' => 'certificate'
		),

		array( // Text Input
			'label'	=> __('Passing Percentage','vibe-customtypes'), // <label>
			'desc'	=> __('Course passing percentage, for completion certificate','vibe-customtypes'), // description
			'id'	=> $prefix.'course_passing_percentage', // field id and name
			'type'	=> 'number' // type of field
		),
		array( // Text Input
			'label'	=> __('Drip Feed','vibe-customtypes'), // <label>
			'desc'	=> __('Enable Drip Feed course','vibe-customtypes'), // description
			'id'	=> $prefix.'course_drip', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		),
		array( // Text Input
			'label'	=> __('Drip Feed Duration','vibe-customtypes'), // <label>
			'desc'	=> __('Duration between consecutive Drip feed units (in ','vibe-customtypes').calculate_duration_time($drip_duration_parameter).' )', // description
			'id'	=> $prefix.'course_drip_duration', // field id and name
			'type'	=> 'number', // type of field
		),

		

		array( // Text Input
			'label'	=> __('Course Curriculum','vibe-customtypes'), // <label>
			'desc'	=> __('Set Course Curriculum, prepare units and quizes before setting up curriculum','vibe-customtypes'), // description
			'id'	=> $prefix.'course_curriculum', // field id and name
			'post_type1' => 'unit',
			'post_type2' => 'quiz',
			'type'	=> 'curriculum' // type of field
		),
		 
		array( // Text Input
			'label'	=> __('Pre-Required Course','vibe-customtypes'), // <label>
			'desc'	=> __('Pre Required course for this course','vibe-customtypes'), // description
			'id'	=> $prefix.'pre_course', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'course'
		), 
		array( // Text Input
			'label'	=> __('Course Forum','vibe-customtypes'), // <label>
			'desc'	=> __('Connect Forum with Course.','vibe-customtypes'), // description
			'id'	=> $prefix.'forum', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'forum'
		),
		array( // Text Input
			'label'	=> __('Course Group','vibe-customtypes'), // <label>
			'desc'	=> __('Connect a Group with Course.','vibe-customtypes'), // description
			'id'	=> $prefix.'group', // field id and name
			'type'	=> 'groups', // type of field
		),
		array( // Text Input
			'label'	=> __('Course Completion Message','vibe-customtypes'), // <label>
			'desc'	=> __('This message is shown to users when they Finish submit the course','vibe-customtypes'), // description
			'id'	=> $prefix.'course_message', // field id and name
			'type'	=> 'editor', // type of field
			'std'	=> 'Thank you for Finish the Course.'
		),
	);
	
	$course_metabox = apply_filters('wplms_course_metabox',$course_metabox);

	$course_product_metabox = array(
		array( // Text Input
			'label'	=> __('Free Course','vibe-customtypes'), // <label>
			'desc'	=> __('Course is Free for all Members','vibe-customtypes'), // description
			'id'	=> $prefix.'course_free', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		)
	);


if ( in_array( 'paid-memberships-pro/paid-memberships-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && function_exists('pmpro_getAllLevels')) {	
	$levels=pmpro_getAllLevels();
	foreach($levels as $level){
		$level_array[]= array('value' =>$level->id,'label'=>$level->name);
	}
	$course_product_metabox[] =array(
			'label'	=> __('PMPro Membership','vibe-customtypes'), // <label>
			'desc'	=> __('Required Membership levle for this course','vibe-customtypes'), // description
			'id'	=> $prefix.'pmpro_membership', // field id and name
			'type'	=> 'multiselect', // type of field
			'options' => $level_array,
		);
}
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists('is_plugin_active_for_network') && is_plugin_active_for_network( 'woocommerce/woocommerce.php'))) {
	$instructor_privacy = vibe_get_option('instructor_content_privacy');
	$flag=1;
    if(isset($instructor_privacy) && $instructor_privacy && !current_user_can('manage_options')){
    	$flag=0;
    }
    if($flag){
		$course_product_metabox[] =array(
			'label'	=> __('Associated Product','vibe-customtypes'), // <label>
			'desc'	=> __('Associated Product with the Course.','vibe-customtypes'), // description
			'id'	=> $prefix.'product', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type'=> 'product',
	        'std'   => ''
		);
	}
}

$course_product_metabox = apply_filters('wplms_course_product_metabox',$course_product_metabox);

$unit_types = apply_filters('wplms_unit_types',array(
                      array( 'label' =>__('Video','vibe-customtypes'),'value'=>'play'),
                      array( 'label' =>__('Audio','vibe-customtypes'),'value'=>'music-file-1'),
                      array( 'label' =>__('Podcast','vibe-customtypes'),'value'=>'podcast'),
                      array( 'label' =>__('General','vibe-customtypes'),'value'=>'text-document'),
                    ));

	$unit_metabox = array(  
		array( // Single checkbox
			'label'	=> __('Unit Description','vibe-customtypes'), // <label>
			'desc'	=> __('Small Description.','vibe-customtypes'), // description
			'id'	=> $prefix.'subtitle', // field id and name
			'type'	=> 'textarea', // type of field
	        'std'   => ''
	        ),
		array( // Text Input
			'label'	=> __('Unit Type','vibe-customtypes'), // <label>
			'desc'	=> __('Select Unit type from Video , Audio , Podcast, General , ','vibe-customtypes'), // description
			'id'	=> $prefix.'type', // field id and name
			'type'	=> 'select', // type of field
			'options' => $unit_types,
	        'std'   => 'text-document'
		),
		array( // Text Input
			'label'	=> __('Free Unit','vibe-customtypes'), // <label>
			'desc'	=> __('Set Free unit, viewable to all','vibe-customtypes'), // description
			'id'	=> $prefix.'free', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		),
		array( // Text Input
			'label'	=> __('Unit Duration','vibe-customtypes'), // <label>
			'desc'	=> __('Duration in ','vibe-customtypes').calculate_duration_time($unit_duration_parameter), // description
			'id'	=> $prefix.'duration', // field id and name
			'type'	=> 'number' // type of field
		),
		array( // Text Input
			'label'	=> __('Connect Assignments','vibe-customtypes'), // <label>
			'desc'	=> __('Select an Assignment which you can connect with this Unit','vibe-customtypes'), // description
			'id'	=> $prefix.'assignment', // field id and name
			'type'	=> 'selectmulticpt', // type of field
			'post_type' => 'wplms-assignment'
		),
		array( // Text Input
			'label'	=> __('Unit Forum','vibe-customtypes'), // <label>
			'desc'	=> __('Connect Forum with Unit.','vibe-customtypes'), // description
			'id'	=> $prefix.'forum', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'forum'
		),
	);

	$unit_metabox = apply_filters('wplms_unit_metabox',$unit_metabox);

	$question_types = apply_filters('wplms_question_types',array(
              array( 'label' =>__('True or False','vibe'),'value'=>'truefalse'),  
              array( 'label' =>__('Multiple Choice','vibe'),'value'=>'single'),
              array( 'label' =>__('Multiple Correct','vibe'),'value'=>'multiple'),
              array( 'label' =>__('Sort Answers','vibe'),'value'=>'sort'),
              array( 'label' =>__('Match Answers','vibe'),'value'=>'match'),
              array( 'label' =>__('Fill in the Blank','vibe'),'value'=>'fillblank'),
              array( 'label' =>__('Dropdown Select','vibe'),'value'=>'select'),
              array( 'label' =>__('Small Text','vibe'),'value'=>'smalltext'),
              array( 'label' =>__('Large Text','vibe'),'value'=>'largetext')
            ));
	$question_metabox = array(  
		array( // Text Input
			'label'	=> __('Question Type','vibe-customtypes'), // <label>
			'desc'	=> __('Select Question type, ','vibe-customtypes'), // description
			'id'	=> $prefix.'question_type', // field id and name
			'type'	=> 'select', // type of field
			'options' => $question_types,
	        'std'   => 'single'
		),
		array( // Text Input
			'label'	=> __('Question Options (For Single/Multiple/Sort/Match Question types)','vibe-customtypes'), // <label>
			'desc'	=> __('Single/Mutiple Choice question options','vibe-customtypes'), // description
			'id'	=> $prefix.'question_options', // field id and name
			'type'	=> 'repeatable_count' // type of field
		),
	    array( // Text Input
			'label'	=> __('Correct Answer','vibe-customtypes'), // <label>
			'desc'	=> __('Enter (1 = True, 0 = false ) or Choice Number (1,2..) or comma saperated Choice numbers (1,2..) or Correct Answer for small text (All possible answers comma saperated) | 0 for No Answer or Manual Check','vibe-customtypes'), // description
			'id'	=> $prefix.'question_answer', // field id and name
			'type'	=> 'text', // type of field
			'std'	=> 0
		),
		array( // Text Input
			'label'	=> __('Answer Hint','vibe-customtypes'), // <label>
			'desc'	=> __('Add a Hint/clue for the answer to show to student','vibe-customtypes'), // description
			'id'	=> $prefix.'question_hint', // field id and name
			'type'	=> 'textarea', // type of field
			'std'	=> ''
		),
		array( // Text Input
			'label'	=> __('Answer Explaination','vibe-customtypes'), // <label>
			'desc'	=> __('Add Answer explaination','vibe-customtypes'), // description
			'id'	=> $prefix.'question_explaination', // field id and name
			'type'	=> 'editor', // type of field
			'std'	=> ''
		),
	);
	
	$question_metabox = apply_filters('wplms_question_metabox',$question_metabox);

	$quiz_metabox = array(  
		array( // Text Input
			'label'	=> __('Quiz Subtitle','vibe-customtypes'), // <label>
			'desc'	=> __('Quiz Subtitle.','vibe-customtypes'), // description
			'id'	=> $prefix.'subtitle', // field id and name
			'type'	=> 'text', // type of field
			'std'	=> ''
		),
        array( // Text Input
			'label'	=> __('Connected Course','vibe-customtypes'), // <label>
			'desc'	=> __('Adds a Back to Course button, on quiz submission.','vibe-customtypes'), // description
			'id'	=> $prefix.'quiz_course', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'course'
		),
		array( // Text Input
			'label'	=> __('Quiz Duration','vibe-customtypes'), // <label>
			'desc'	=> __('Quiz duration in ','vibe-customtypes').calculate_duration_time($quiz_duration_parameter).__(' Enables Timer & auto submits on expire. 9999 to disable.','vibe-customtypes'), // description
			'id'	=> $prefix.'duration', // field id and name
			'type'	=> 'number', // type of field
			'std'	=> 0
		),
		
		array( // Text Input
			'label'	=> __('Auto Evatuate Results','vibe-customtypes'), // <label>
			'desc'	=> __('Evaluate results as soon as quiz is complete. (* No Large text questions ), Diable for manual evaluate','vibe-customtypes'), // description
			'id'	=> $prefix.'quiz_auto_evaluate', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		), 
		
		array( // Text Input
			'label'	=> __('Number of Extra Quiz Retakes','vibe-customtypes'), // <label>
			'desc'	=> __('Student can reset and start the quiz all over again. Number of Extra retakes a student can take.','vibe-customtypes'), // description
			'id'	=> $prefix.'quiz_retakes', // field id and name
			'type'	=> 'number', // type of field
	        'std'   => 0
		), 
		array( // Text Input
			'label'	=> __('Post Quiz Message','vibe-customtypes'), // <label>
			'desc'	=> __('This message is shown to users when they submit the quiz','vibe-customtypes'), // description
			'id'	=> $prefix.'quiz_message', // field id and name
			'type'	=> 'editor', // type of field
			'std'	=> 'Thank you for Submitting the Quiz. Check Results in your Profile.'
		),
		array( // Text Input
			'label'	=> __('Dynamic Quiz','vibe-customtypes'), // <label>
			'desc'	=> __('Dynamic quiz automatically selects questions.','vibe-customtypes'), // description
			'id'	=> $prefix.'quiz_dynamic', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		),
		array( // Text Input
			'label'	=> __('Dynamic Quiz Question tags','vibe-customtypes'), // <label>
			'desc'	=> __('Select Question tags from where questions will be selected for the quiz.(required if dynamic enabled)','vibe-customtypes'), // description
			'id'	=> $prefix.'quiz_tags', // field id and name
			'type'	=> 'dynamic_taxonomy', // type of field
			'taxonomy' => 'question-tag',
	        'std'   => 0
		),
		array( // Text Input
			'label'	=> __('Number of Questions in Dynamic Quiz','vibe-customtypes'), // <label>
			'desc'	=> __('Enter the number of Questions in the dynamic quiz. (required if dynamic enabled).','vibe-customtypes'), // description
			'id'	=> $prefix.'quiz_number_questions', // field id and name
			'type'	=> 'number', // type of field
	        'std'   => 0
		),
		array( // Text Input
			'label'	=> __('Marks per Question in Dynamic Quiz','vibe-customtypes'), // <label>
			'desc'	=> __('Enter the number of marks per Questions in the dynamic quiz. (required if dynamic enabled).','vibe-customtypes'), // description
			'id'	=> $prefix.'quiz_marks_per_question', // field id and name
			'type'	=> 'number', // type of field
	        'std'   => 0
		),
		array( // Text Input
			'label'	=> __('Randomize Quiz Questions','vibe-customtypes'), // <label>
			'desc'	=> __('Random Question sequence for every quiz','vibe-customtypes'), // description
			'id'	=> $prefix.'quiz_random', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		),
	    array( // Text Input
			'label'	=> __('Quiz Questions','vibe-customtypes'), // <label>
			'desc'	=> __('Quiz questions for Static Quiz only','vibe-customtypes'), // description
			'id'	=> $prefix.'quiz_questions', // field id and name
			'type'	=> 'repeatable_selectcpt', // type of field
			'post_type' => 'question',
			'std'	=> 0
		),
	    
		
	);
	
	$quiz_metabox = apply_filters('wplms_quiz_metabox',$quiz_metabox);

	$testimonial_metabox = array(  
		array( // Text Input
			'label'	=> __('Author Name','vibe-customtypes'), // <label>
			'desc'	=> __('Enter the name of the testimonial author.','vibe-customtypes'), // description
			'id'	=> $prefix.'testimonial_author_name', // field id and name
			'type'	=> 'text' // type of field
		),
	        array( // Text Input
			'label'	=> __('Designation','vibe-customtypes'), // <label>
			'desc'	=> __('Enter the testimonial author\'s designation.','vibe-customtypes'), // description
			'id'	=> $prefix.'testimonial_author_designation', // field id and name
			'type'	=> 'text' // type of field
		),
	);

	$testimonial_metabox = apply_filters('wplms_course_product_metabox',$testimonial_metabox);

	$product_metabox = array(  
		array( // Text Input
			'label'	=> __('Associated Courses','vibe-customtypes'), // <label>
			'desc'	=> __('Associated Courses with this product. Enables access to the course.','vibe-customtypes'), // description
			'id'	=> $prefix.'courses', // field id and name
			'type'	=> 'selectmulticpt', // type of field
			'post_type'=>'course'
		),
	    array( // Text Input
			'label'	=> __('Subscription ','vibe-customtypes'), // <label>
			'desc'	=> __('Enable if Product is Subscription Type (Price per month)','vibe-customtypes'), // description
			'id'	=> $prefix.'subscription', // field id and name
			'type'	=> 'showhide', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	                'std'   => 'H'
		),
	    array( // Text Input
			'label'	=> __('Subscription Duration','vibe-customtypes'), // <label>
			'desc'	=> __('Duration for Subscription Products (in ','vibe-customtypes').calculate_duration_time($product_duration_parameter).')', // description
			'id'	=> $prefix.'duration', // field id and name
			'type'	=> 'number' // type of field
		),
	);

	$product_metabox = apply_filters('wplms_product_metabox',$product_metabox);

$wplms_events_metabox = array(  
		array( // Single checkbox
			'label'	=> __('Event Sub-Title','vibe-customtypes'), // <label>
			'desc'	=> __('Event Sub-Title.','vibe-customtypes'), // description
			'id'	=> $prefix.'subtitle', // field id and name
			'type'	=> 'textarea', // type of field
	        'std'   => ''
	                ), 
		array( // Text Input
			'label'	=> __('Course','vibe-customtypes'), // <label>
			'desc'	=> __('Select Course for which the event is valid','vibe-customtypes'), // description
			'id'	=> $prefix.'event_course', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'course'
		),
		array( // Text Input
			'label'	=> __('Connect an Assignment','vibe-customtypes'), // <label>
			'desc'	=> __('Select an Assignment which you can connect with this Event','vibe-customtypes'), // description
			'id'	=> $prefix.'assignment', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'wplms-assignment'
		),
		array( // Text Input
			'label'	=> __('Event Icon','vibe-customtypes'), // <label>
			'desc'	=> __('Click on icon to  select an icon for the event','vibe-customtypes'), // description
			'id'	=> $prefix.'icon', // field id and name
			'type'	=> 'icon', // type of field
		),
		array( // Text Input
			'label'	=> __('Event Color','vibe-customtypes'), // <label>
			'desc'	=> __('Select color for Event','vibe-customtypes'), // description
			'id'	=> $prefix.'color', // field id and name
			'type'	=> 'color', // type of field
		),
		array( // Text Input
			'label'	=> __('Start Date','vibe-customtypes'), // <label>
			'desc'	=> __('Date from which Event Begins','vibe-customtypes'), // description
			'id'	=> $prefix.'start_date', // field id and name
			'type'	=> 'date', // type of field
		),
		array( // Text Input
			'label'	=> __('End Date','vibe-customtypes'), // <label>
			'desc'	=> __('Date on which Event ends.','vibe-customtypes'), // description
			'id'	=> $prefix.'end_date', // field id and name
			'type'	=> 'date', // type of field
		),
		array( // Text Input
			'label'	=> __('Start Time','vibe-customtypes'), // <label>
			'desc'	=> __('Date from which Event Begins','vibe-customtypes'), // description
			'id'	=> $prefix.'start_time', // field id and name
			'type'	=> 'time', // type of field
		),
		array( // Text Input
			'label'	=> __('End Time','vibe-customtypes'), // <label>
			'desc'	=> __('Date on which Event ends.','vibe-customtypes'), // description
			'id'	=> $prefix.'end_time', // field id and name
			'type'	=> 'time', // type of field
		),
		array( // Text Input
			'label'	=> __('Show Location','vibe-customtypes'), // <label>
			'desc'	=> __('Show Location and Google map with the event','vibe-customtypes'), // description
			'id'	=> $prefix.'show_location', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		),
	    array( // Text Input
			'label'	=> __('Location','vibe-customtypes'), // <label>
			'desc'	=> __('Location of event','vibe-customtypes'), // description
			'id'	=> $prefix.'location', // field id and name
			'type'	=> 'gmap' // type of field
		),
		array( // Text Input
			'label'	=> __('Additional Information','vibe-customtypes'), // <label>
			'desc'	=> __('Point wise Additional Information regarding the event','vibe-customtypes'), // description
			'id'	=> $prefix.'additional_info', // field id and name
			'type'	=> 'repeatable' // type of field
		),
		array( // Text Input
			'label'	=> __('More Information','vibe-customtypes'), // <label>
			'desc'	=> __('Supports HTML and shortcodes','vibe-customtypes'), // description
			'id'	=> $prefix.'more_info', // field id and name
			'type'	=> 'editor' // type of field
		),
		array( // Text Input
			'label'	=> __('Private Event','vibe-customtypes'), // <label>
			'desc'	=> __('Only Invited participants can see the Event','vibe-customtypes'), // description
			'id'	=> $prefix.'private_event', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		),
	);
	
	

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists('is_plugin_active_for_network') && is_plugin_active_for_network( 'woocommerce/woocommerce.php'))) {
	
	$wplms_events_metabox[] =array(
			'label'	=> __('Associated Product for Event Access','vibe-customtypes'), // <label>
			'desc'	=> __('Purchase of this product grants Event access to the member.','vibe-customtypes'), // description
			'id'	=> $prefix.'product', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type'=> 'product',
	        'std'   => ''
		);
}

$wplms_events_metabox = apply_filters('wplms_events_metabox',$wplms_events_metabox);

$payments_metabox = array(  
		array( // Text Input
			'label'	=> __('From','vibe-customtypes'), // <label>
			'desc'	=> __('Date on which Payment was done.','vibe-customtypes'), // description
			'id'	=> $prefix.'date_from', // field id and name
			'type'	=> 'text', // type of field
		),
		array( // Text Input
			'label'	=> __('To','vibe-customtypes'), // <label>
			'desc'	=> __('Date on which Payment was done.','vibe-customtypes'), // description
			'id'	=> $prefix.'date_to', // field id and name
			'type'	=> 'text', // type of field
		),
	    array( // Text Input
			'label'	=> __('Instructor and Commissions','vibe-customtypes'), // <label>
			'desc'	=> __('Instructor commissions','vibe-customtypes'), // description
			'id'	=> $prefix.'instructor_commissions', // field id and name
			'type'	=> 'payments' // type of field
		),
	);

$payments_metabox = apply_filters('wplms_payments_metabox',$payments_metabox);

$certificate_metabox = array(  
		array( // Text Input
			'label'	=> __('Background Image/Pattern','vibe-customtypes'), // <label>
			'desc'	=> __('Add background image','vibe-customtypes'), // description
			'id'	=> $prefix.'background_image', // field id and name
			'type'	=> 'image', // type of field
		),
		array( // Text Input
			'label'	=> __('Enable Print','vibe-customtypes'), // <label>
			'desc'	=> __('Displays a Print Button on top right corner of certificate','vibe-customtypes'), // description
			'id'	=> $prefix.'print', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		),
		array( // Text Input
			'label'	=> __('Custom Class','vibe-customtypes'), // <label>
			'desc'	=> __('Add Custom Class over Certificate container.','vibe-customtypes'), // description
			'id'	=> $prefix.'custom_class', // field id and name
			'type'	=> 'text', // type of field
		),
		array( // Text Input
			'label'	=> __('Custom CSS','vibe-customtypes'), // <label>
			'desc'	=> __('Add Custom CSS for Certificate.','vibe-customtypes'), // description
			'id'	=> $prefix.'custom_css', // field id and name
			'type'	=> 'textarea', // type of field
		),
		array( // Text Input
			'label'	=> __('NOTE:','vibe-customtypes'), // <label>
			'desc'	=> __(' USE FOLLOWING SHORTCODES TO DISPLAY RELEVANT DATA : <br />1. <strong>[certificate_student_name]</strong> : Displays Students Name<br />2. <strong>[certificate_course]</strong> : Displays Course Name<br />3. <strong>[certificate_student_marks]</strong> : Displays Students Marks in Course<br />4. <strong>[certificate_student_date]</strong>: Displays date on which Certificate was awarded to the Student<br />5. <strong>[certificate_student_email]</strong>: Displays registered email of the Student<br />6. <strong>[certificate_code]</strong>: Generates unique code for Student which can be validated from Certificate page.','vibe-customtypes'), // description
			'id'	=> $prefix.'note', // field id and name
			'type'	=> 'note', // type of field
		),
	);	

$certificate_metabox = apply_filters('wplms_certificate_metabox',$certificate_metabox);

$max_upload = (int)(ini_get('upload_max_filesize'));
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$upload_mb = min($max_upload, $max_post, $memory_limit);

$wplms_assignments_metabox = array(  
	array( // Single checkbox
			'label'	=> __('Assignment Sub-Title','vibe-customtypes'), // <label>
			'desc'	=> __('Assignment Sub-Title.','vibe-customtypes'), // description
			'id'	=> $prefix.'subtitle', // field id and name
			'type'	=> 'textarea', // type of field
	        'std'   => ''
	                ), 
	array( // Single checkbox
			'label'	=> __('Sidebar','vibe-customtypes'), // <label>
			'desc'	=> __('Select a Sidebar | Default : mainsidebar','vibe-customtypes'), // description
			'id'	=> $prefix.'sidebar', // field id and name
			'type'	=> 'select',
	                'options' => $sidebararray
	                ),
	array( // Text Input
		'label'	=> __('Assignment Maximum Marks','vibe-customtypes'), // <label>
		'desc'	=> __('Set Maximum marks for the assignment','vibe-customtypes'), // description
		'id'	=> $prefix.'assignment_marks', // field id and name
		'type'	=> 'number', // type of field
		'std' => '10'
	),
	array( // Text Input
		'label'	=> __('Assignment Maximum Time limit','vibe-customtypes'), // <label>
		'desc'	=> __('Set Maximum Time limit for Assignment ( in ','vibe-customtypes').calculate_duration_time($assignment_duration_parameter).' )', // description
		'id'	=> $prefix.'assignment_duration', // field id and name
		'type'	=> 'number', // type of field
		'std' => '10'
	),
	array( // Text Input
			'label'	=> __('Include in Course Evaluation','vibe-customtypes'), // <label>
			'desc'	=> __('Include assignment marks in Course Evaluation','vibe-customtypes'), // description
			'id'	=> $prefix.'assignment_evaluation', // field id and name
			'type'	=> 'yesno', // type of field
	        'options' => array(
	          array('value' => 'H',
	                'label' =>'Hide'),
	          array('value' => 'S',
	                'label' =>'Show'),
	        ),
	        'std'   => 'H'
		),
	array( // Text Input
			'label'	=> __('Include in Course','vibe-customtypes'), // <label>
			'desc'	=> __('Assignments marks will be shown/used in course evaluation','vibe-customtypes'), // description
			'id'	=> $prefix.'assignment_course', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'course'
		),
	array( // Single checkbox
			'label'	=> __('Assignment Submissions','vibe-customtypes'), // <label>
			'desc'	=> __('Select type of assignment submissions','vibe-customtypes'), // description
			'id'	=> $prefix.'assignment_submission_type', // field id and name
			'type'	=> 'select', // type of field
	        'options' => array(
	                    1=>array('label'=>'Upload file','value'=>'upload'),
	                    2=>array('label'=>'Text Area','value'=>'textarea'),
	        ),
	        'std'   => ''
		),
	array( // Text Input
			'label'	=> __('Attachment Type','vibe-customtypes'), // <label>
			'desc'	=> __('Select valid attachment types ','vibe-customtypes'), // description
			'id'	=> $prefix.'attachment_type', // field id and name
			'type'	=> 'multiselect', // type of field
			'options' => array(
				array('value'=> 'JPG','label' =>'JPG'),
				array('value'=> 'GIF','label' =>'GIF'),
				array('value'=> 'PNG','label' =>'PNG'),
				array('value'=> 'PDF','label' =>'PDF'),
				array('value'=> 'DOC','label' =>'DOC'),
				array('value'=> 'DOCX','label' => 'DOCX'),
				array('value'=> 'PPT','label' =>'PPT'),
				array('value'=> 'PPTX','label' => 'PPTX'),
				array('value'=> 'PPS','label' =>'PPS'),
				array('value'=> 'PPSX','label' => 'PPSX'),
				array('value'=> 'ODT','label' =>'ODT'),
				array('value'=> 'XLS','label' =>'XLS'),
				array('value'=> 'XLSX','label' => 'XLSX'),
				array('value'=> 'MP3','label' =>'MP3'),
				array('value'=> 'M4A','label' =>'M4A'),
				array('value'=> 'OGG','label' =>'OGG'),
				array('value'=> 'WAV','label' =>'WAV'),
				array('value'=> 'WMA','label' =>'WMA'),
				array('value'=> 'MP4','label' =>'MP4'),
				array('value'=> 'M4V','label' =>'M4V'),
				array('value'=> 'MOV','label' =>'MOV'),
				array('value'=> 'WMV','label' =>'WMV'),
				array('value'=> 'AVI','label' =>'AVI'),
				array('value'=> 'MPG','label' =>'MPG'),
				array('value'=> 'OGV','label' =>'OGV'),
				array('value'=> '3GP','label' =>'3GP'),
				array('value'=> '3G2','label' =>'3G2'),
				array('value'=> 'FLV','label' =>'FLV'),
				array('value'=> 'WEBM','label' =>'WEBM'),
				array('value'=> 'APK','label' =>'APK '),
				array('value'=> 'RAR','label' =>'RAR'),
				array('value'=> 'ZIP','label' =>'ZIP'),
	        ),
	        'std'   => 'single'
		),
		array( // Text Input
		'label'	=> __('Attachment Size (in MB)','vibe-customtypes'), // <label>
		'desc'	=> __('Set Maximum Attachment size for upload ( set less than ','vibe' ).$upload_mb.' MB)', // description
		'id'	=> $prefix.'attachment_size', // field id and name
		'type'	=> 'number', // type of field
		'std' => '2'
		),

);

$dwqna_custom_metabox = array(  
		array( // Text Input
			'label'	=> __('Connected Course','vibe-customtypes'), // <label>
			'desc'	=> __('Connect this question to a course','vibe-customtypes'), // description
			'id'	=> $prefix.'question_course', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'course'
		),
		array( // Text Input
			'label'	=> __('Connected Unit','vibe-customtypes'), // <label>
			'desc'	=> __('Connect this question to a Unit','vibe-customtypes'), // description
			'id'	=> $prefix.'question_unit', // field id and name
			'type'	=> 'selectcpt', // type of field
			'post_type' => 'unit'
		),  
	);

$payments_metabox = apply_filters('wplms_payments_metabox',$payments_metabox);

$wplms_assignments_metabox = apply_filters('wplms_assignments_metabox',$wplms_assignments_metabox);

	$post_metabox = new custom_add_meta_box( 'post-settings', __('Post Settings','vibe-customtypes'), $post_metabox, 'post', true );
	$page_metabox = new custom_add_meta_box( 'page-settings', __('Page Settings','vibe-customtypes'), $page_metabox, 'page', true );

	$course_box = new custom_add_meta_box( 'page-settings', __('Course Settings','vibe-customtypes'), $course_metabox, 'course', true );

	$course_product = __('Course Product','vibe-customtypes');
	if(function_exists('pmpro_getAllLevels')){
		$course_product = __('Course Membership','vibe-customtypes');
	}
	$course_product_box = new custom_add_meta_box( 'post-settings', $course_product, $course_product_metabox, 'course', true );
	$unit_box = new custom_add_meta_box( 'page-settings', __('Unit Settings','vibe-customtypes'), $unit_metabox, 'unit', true );

	$question_box = new custom_add_meta_box( 'page-settings', __('Question Settings','vibe-customtypes'), $question_metabox, 'question', true );
	$quiz_box = new custom_add_meta_box( 'page-settings', __('Quiz Settings','vibe-customtypes'), $quiz_metabox, 'quiz', true );
	
	if(post_type_exists( 'dwqa-question' ))
		$dwqna_custom_box = new custom_add_meta_box( 'page-settings', __('Settings','vibe-customtypes'), $dwqna_custom_metabox, 'dwqa-question', false );

	$testimonial_box = new custom_add_meta_box( 'testimonial-info', __('Testimonial Author Information','vibe-customtypes'), $testimonial_metabox, 'testimonials', true );
	$payments_metabox = new custom_add_meta_box( 'page-settings', __('Payments Settings','vibe-customtypes'), $payments_metabox, 'payments', true );
	$certificates_metabox = new custom_add_meta_box( 'page-settings', __('Certificate Template Settings','vibe-customtypes'), $certificate_metabox, 'certificate', true );
	
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists('is_plugin_active_for_network') && is_plugin_active_for_network( 'woocommerce/woocommerce.php'))) {
		$product_box = new custom_add_meta_box( 'page-settings', __('Product Course Settings','vibe-customtypes'), $product_metabox, 'product', true );
	}

	if ( in_array( 'wplms-events/wplms-events.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		$events_metabox = new custom_add_meta_box( 'page-settings', __('WPLMS Events Settings','vibe-customtypes'), $wplms_events_metabox, 'wplms-event', true );
	}

	
	if ( in_array( 'wplms-assignments/wplms-assignments.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		$eassignments_metabox = new custom_add_meta_box( 'page-settings', __('WPLMS Assignments Settings','vibe-customtypes'), $wplms_assignments_metabox, 'wplms-assignment', true );
	}
}
add_action('init','add_vibe_metaboxes');


add_action( 'add_meta_boxes', 'add_vibe_editor' );
if(!function_exists('add_vibe_editor')){
	function add_vibe_editor(){
	    add_meta_box( 'vibe-editor', __( 'Page Builder', 'vibe' ), 'vibe_layout_editor', 'page', 'normal', 'high' );
	}
}

function attachment_getMaximumUploadFileSize(){
    $maxUpload      = (int)(ini_get('upload_max_filesize'));
    $maxPost        = (int)(ini_get('post_max_size'));
    $memoryLimit    = (int)(ini_get('memory_limit'));
    return min($maxUpload, $maxPost, $memoryLimit);
}
