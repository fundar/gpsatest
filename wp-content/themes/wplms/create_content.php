<?php
/**
 * Template Name: Create Content
 */
do_action('wplms_before_create_course_header');
get_header('buddypress');

$user_id=get_current_user_id();

$linkage = vibe_get_option('linkage');
//Default settings
$course_settings = array(
    'vibe_duration' => 10,
    'vibe_course_auto_eval' => 'H',
    'vibe_pre_course' =>'',
    'vibe_course_drip' => 'H',
    'vibe_course_drip_duration' => 1,
    'vibe_course_certificate' =>'S',
    'vibe_course_passing_percentage' => 40,
    'vibe_certificate_template' =>'',
    'vibe_badge' => 'S',
    'vibe_course_badge' => VIBE_URL.'/images/add_image.png',
    'vibe_course_badge_percentage' => 75,
    'vibe_course_badge_title' => '',
    'vibe_max_students' => 9999,
    'vibe_start_date' => date('Y-m-d'),
    'vibe_group' => '',
    'vibe_forum' => '',
    'vibe_course_message' => ' <p>'.__('Enter a course completion message for students passing this course. This message is shown to students when students submit their course. The message is shown above the Course review form','vibe').'</p>',
    );

$course_pricing =array(
    'vibe_course_free' => 'H',
    'vibe_product' => '',
    'vibe_pmpro_membership' =>'',
    'vibe_subscription' => 'H',
    'vibe_duration' => 0
    );

do_action('wplms_before_create_course_page');

$course_settings=apply_filters('wplms_create_course_settings',$course_settings);
$course_pricing=apply_filters('wplms_frontend_create_course_pricing',$course_pricing);

if(isset($_GET['action']) && is_numeric($_GET['action'])){
    $id = $_GET['action']; // Grant Access to edit the Post | Validates if the user is Course Instructor
    $course_cats =wp_get_post_terms( $id, 'course-cat');
    $course_cat_id = $course_cats[0]->term_id;

    if(isset($linkage ) && $linkage ){
        $course_linkage=wp_get_post_terms( $id, 'linkage',array("fields" => "names"));
    }
    
}

?>
<section id="create_content">
    <div class="container">
        <?php do_action( 'content_notices' ); ?>
        <div class="row">
            <div class="col-md-3 col-sm-3">      
                <div id="course_creation_tabs" class="course-create-steps">
                    <ul <?php echo ((isset($_GET['action']))?'class="islive"':'');?>>
                        <li class="active"><i class="icon-book-open"></i><a><?php  (isset($_GET['action'])?_e('EDIT COURSE','vibe'):_e('CREATE COURSE','vibe')); ?><span><?php _e('Start building a course','vibe'); ?></span></a></li>
                        <li><i class="icon-settings-1"></i><a><?php _e('SETTINGS','vibe'); ?><span><?php _e('Course settings','vibe'); ?></span></a></li>
                        <li><i class="icon-file"></i><a><?php _e('SET CURRICULUM','vibe'); ?><span><?php _e('Add Units and Quizzes','vibe'); ?></span></a></li>
                        <?php
                        $enable_pricing = apply_filters('wplms_front_end_pricing',1);
                        if ($enable_pricing) {
                        ?>
                        <li><i class="icon-tag"></i><a><?php _e('PRICING','vibe'); ?><span><?php _e('Set Price for Course','vibe'); ?></span></a></li>
                        <?php
                        }
                        ?>
                        <li><i class="icon-glass"></i><a><?php _e('PUBLISH COURSE','vibe'); ?><span><?php _e('Go Live !','vibe'); ?></span></a></li>
                    </ul>
                </div>
                <?php
                    if(isset($_GET['action']) && is_numeric($_GET['action'])){
                        echo '<a href="'.admin_url( 'post.php?post='.$_GET['action'].'&action=edit').'" class="link">'.__('Edit in Admin Panel','vibe').'</a>';
                    }
                ?>
            </div>
            <div class="col-md-6 col-sm-6">   
                <div class="create_course_content content">
                    <div id="create_course" class="active">
                        <article class="live-edit" data-model="article" data-id="1" data-url="/articles">
                            <label><?php _e('COURSE TITLE','vibe'); ?></label>
                           
                            <h1 id="course-title" data-help-tag="1" data-editable="true" data-max-length="60" data-name="title"> <?php if(isset($_GET['action'])) echo get_the_title($_GET['action']); else _e('ENTER A COURSE TITLE','vibe'); ?></h1>
                            <hr />
                            <label><?php _e('COURSE CATEGORY','vibe'); ?></label>
                            <ul id="course-category" data-help-tag="2">
                                <li>
                                    <select id="course-category-select" class="chosen">
                                        <option><?php _e('Select a Course Category','vibe'); ?></option>
                                        <option value="new"><?php _e('Add new category','vibe'); ?></option>
                                        <?php
                                        $terms = get_terms('course-cat',array('hide_empty' => false));
                                        if(isset($terms) && is_array($terms))
                                        foreach($terms as $term){
                                            $parenttermname='';
                                            if($term->parent){
                                                $parentterm=get_term_by('id', $term->parent, 'course-cat', 'ARRAY_A');
                                                $parenttermname = $parentterm['name'].' &rsaquo; ';
                                            }
                                            echo '<option value="'.$term->ID.'" '.(isset($_GET['action'])?selected($course_cat_id,$term->ID):'').'>'.$parenttermname.$term->name.'</option>';
                                        }
                                        ?>
                                    </select>
                                </li>
                                <li><p id="new_course_category" data-editable="true" data-name="content" data-max-length="250" data-text-options="true"><?php _e('Enter a new Course Category','vibe'); ?></p></li>
                            </ul>
                            
                            <hr class="clear" />
                            <div  id="featured_image" data-help-tag="3">
                                <label><?php _e('COURSE IMAGE','vibe'); ?></label>
                                <div id="course_image" class="upload_image_button" data-input-name="course-image" data-uploader-title="Upload a Course Image" data-uploader-button-text="Set as Course Image" data-uploader-allow-multiple="false">
                                    <?php 
                                    if(isset($_GET['action']) && has_post_thumbnail( $_GET['action'] )){
                                        echo get_the_post_thumbnail($_GET['action'],'thumbnail');
                                    }else{
                                    ?>
                                    <img src="<?php echo VIBE_URL.'/images/add_image.png'; ?>" alt="course image" class="default" />
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <label><?php _e('COURSE DESCRIPTION','vibe'); ?></label>
                            <div id="course_short_description" data-help-tag="4" data-editable="true" data-name="content" data-max-length="250" data-text-options="true">
                            <?php
                            
                            if(isset($_GET['action'])){
                                $the_post = get_post($_GET['action']);                                
                                echo $the_post->post_excerpt; 
                            }else{
                            ?>
                                <p><?php _e('Enter a short description of the course, in less than 30 words. Click on text to edit it and 
                                delete this text. Full course description will be added later on.','vibe'); ?></p>
                            <?php
                            }
                            ?>
                            </div>
                            <br class="clear" />
                            <hr />
                            <?php
                                if(isset($linkage) && $linkage){                                        
                            ?>
                            <label><?php _e('COURSE LINKAGE','vibe'); ?></label>
                            <ul id="course-linkage" data-help-tag="5">
                                <li>
                                    <select id="course-linkage-select" class="chosen">
                                        <option><?php _e('Select Linkage term','vibe'); ?></option>
                                        <?php
                                        $terms = get_terms('linkage',array('hide_empty' => false,'fields' => 'names'));

                                        if(isset($terms) && is_array($terms))
                                        foreach($terms as $term){
                                            echo '<option value="'.$term.'" '.(isset($_GET['action'])?(in_array($term,$course_linkage)?'selected':''):'').'>'.$term.'</option>';
                                        }
                                        ?>
                                        <option value="add_new"><?php _e('Add new Linkage term','vibe'); ?></option>
                                    </select>
                                </li>
                                <li><p id="new_course_linkage" data-editable="true" data-name="content" data-max-length="250" data-text-options="true"><?php _e('Enter a new Linkage','vibe'); ?></p></li>
                            </ul>
                            <hr class="clear" />
                            <?php
                                 
                            }
                                if(isset($_GET['action'])){
                            ?>
                            <div class="clear"></div>
                            <h3 class="course_status" data-help-tag="6"><?php _e('Change Course Status','vibe'); ?><span>
                                    <div class="switch">
                                        <input type="radio" class="switch-input vibe_course_status" name="post_status" value="publish" id="online" <?php checked($the_post->post_status,'publish'); ?>>
                                        <label for="online" class="switch-label switch-label-off"><?php _e('Online','vibe');?></label>
                                        <input type="radio" class="switch-input vibe_course_status" name="post_status" value="draft" id="offline" <?php checked($the_post->post_status,'draft'); ?>>
                                        <label for="offline" class="switch-label switch-label-on"><?php _e('Offline','vibe');?></label>
                                        <span class="switch-selection"></span>
                                      </div>
                                    </span>
                                </h3>
                            <?php
                            }
                            ?>
                        </article>
                        <?php

                        if(isset($_GET['action'])){
                        ?>
                            <a id="save_course_action" class="button hero"><?php _e('SAVE COURSE','vibe'); ?></a>
                        <?php
                        }else{
                        ?>
                            <a id="create_course_action" class="button hero"><?php _e('CREATE COURSE','vibe'); ?></a>
                        <?php
                        }
                        ?>
                    </div>
                    <div id="course_settings">
                        <h3 class="heading"><?php _e('Course Settings','vibe'); ?></h3>
                        <article class="live-edit" data-model="article" data-id="1" data-url="/articles">
                        <ul>
                            <li data-help-tag="7"><label><?php _e('Course Duration','vibe'); ?></label>
                                <h3><?php _e('Maximum Course Duration','vibe'); ?><span><input type="number" id="vibe_duration" class="small_box" value="<?php echo $course_settings['vibe_duration']; ?>" /><?php $course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400); echo calculate_duration_time($course_duration_parameter); ?></span></h3></li>
                            <li data-help-tag="8"><label><?php _e('Course Evaluation','vibe'); ?></label>
                                <h3><?php _e('Course Evaluation Mode','vibe'); ?><span>
                                    <div class="switch">
                                        <input type="radio" class="switch-input vibe_course_auto_eval" name="evaluation" value="H" id="manual" <?php checked($course_settings['vibe_course_auto_eval'],'H'); ?>>
                                        <label for="manual" class="switch-label switch-label-off"><?php _e('Manual','vibe');?></label>
                                        <input type="radio" class="switch-input vibe_course_auto_eval" name="evaluation" value="S" id="automatic" <?php checked($course_settings['vibe_course_auto_eval'],'S'); ?>>
                                        <label for="automatic" class="switch-label switch-label-on"><?php _e('Automatic','vibe');?></label>
                                        <span class="switch-selection"></span>
                                      </div>
                                    </span>
                                </h3>
                            </li>
                            <li data-help-tag="9"><label><?php _e('Pre-required Course','vibe'); ?></label>
                                <h3><?php _e('Set a Pre-required Course','vibe'); ?>
                                <span><select id="vibe_pre_course" class="chosen">
                                    <option value=""><?php _e('None','vibe'); ?></option>
                                    <?php
                                        $args= array(
                                        'post_type'=> 'course',
                                        'numberposts'=> -1
                                        );
                                        $args = apply_filters('wplms_frontend_cpt_query',$args);
                                        $kposts=get_posts($args);
                                        foreach ( $kposts as $kpost ){
                                            echo '<option value="' . $kpost->ID . '" '.selected($course_settings['vibe_pre_course'],$kpost->ID).'>' . $kpost->post_title . '</option>';
                                        }
                                    ?>
                                </select></span>
                                </h3>
                            </li>
                            <li data-help-tag="10"><label><?php _e('Drip Feed','vibe'); ?></label>
                                <h3><?php _e('Drip Feed','vibe'); ?><span>
                                    <div class="switch">
                                        
                                        <input type="radio" class="switch-input vibe_course_drip" name="vibe_course_drip" value="H" id="disable" <?php checked($course_settings['vibe_course_drip'],'H'); ?>>
                                        <label for="disable" class="switch-label switch-label-off"><?php _e('Disable','vibe');?></label>

                                        <input type="radio" class="switch-input vibe_course_drip" name="vibe_course_drip" value="S" id="enable" <?php checked($course_settings['vibe_course_drip'],'S'); ?>>
                                        <label for="enable" class="switch-label switch-label-on"><?php _e('Enable','vibe');?></label>
                                        <span class="switch-selection"></span>
                                      </div>
                                    </span>
                                </h3>
                                <ul>
                                    <li><h5><?php _e('Set Drip Feed Duration','vibe'); ?><span><input type="number" id="vibe_course_drip_duration" class="small_box" value="<?php echo $course_settings['vibe_course_drip_duration']?>" /><?php $drip_duration_parameter = apply_filters('vibe_drip_duration_parameter',86400); echo calculate_duration_time($drip_duration_parameter); ?></span></h5></li>
                                </ul>    
                            </li>
                            <li data-help-tag="11"><label><?php _e('Course Certificate','vibe'); ?></label>
                            <h3><?php _e('Course Certificate','vibe'); ?><span>
                                    <div class="switch">
                                        <input type="radio" class="switch-input vibe_course_certificate" name="vibe_course_certificate" value="H" id="disable1" <?php checked($course_settings['vibe_course_certificate'],'H'); ?>>
                                        <label for="disable1" class="switch-label switch-label-off"><?php _e('Disable','vibe');?></label>
                                        
                                        <input type="radio" class="switch-input vibe_course_certificate" name="vibe_course_certificate" value="S" id="enable1" <?php checked($course_settings['vibe_course_certificate'],'S'); ?>>
                                        <label for="enable1" class="switch-label switch-label-on"><?php _e('Enable','vibe');?></label>
                                        <span class="switch-selection"></span>
                                      </div>
                                    </span>
                                </h3>
                                <ul <?php if($course_settings['vibe_course_certificate'] == 'S'){echo 'style="display:block;"';} ?>>
                                    <li><h5><?php _e('Set Certificate Percentage','vibe'); ?><span><input type="number" id="vibe_course_passing_percentage" class="small_box" value="<?php echo $course_settings['vibe_course_passing_percentage']; ?>"/><?php _e('out of 100','vibe'); ?></span></h5></li>
                                    <li><h5><?php _e('Set Certificate Template','vibe'); ?><span>
                                    <select id="vibe_certificate_template" class="chosen">
                                    <option value=""><?php _e('Default Template','vibe'); ?></option>
                                    <?php
                                        $args= array(
                                            'post_type'=> 'certificate',
                                            'numberposts'=> -1
                                        );
                                        $args = apply_filters('wplms_frontend_cpt_query',$args);
                                        $kposts=get_posts($args);
                                        foreach ( $kposts as $kpost ){
                                            echo '<option value="' . $kpost->ID . '" '.selected($course_settings['vibe_certificate_template'],$kpost->ID).'>' . $kpost->post_title . '</option>';
                                        }
                                    ?>
                                    </select></span></h5></li>
                                </ul> 
                            </li>
                            <li data-help-tag="12"><label><?php _e('Course Badge','vibe'); ?></label>
                            <h3><?php _e('Course Badge','vibe'); ?><span>
                                    <div class="switch show-below">
                                        <input type="radio" class="switch-input vibe_badge" name="vibe_badge" value="H" id="disable2" <?php checked($course_settings['vibe_badge'],'H'); ?>>
                                        <label for="disable2" class="switch-label switch-label-off"><?php _e('Disable','vibe');?></label>
                                        <input type="radio" class="switch-input vibe_badge" name="vibe_badge" value="S" id="enable2" <?php checked($course_settings['vibe_badge'],'S'); ?>>
                                        <label for="enable2" class="switch-label switch-label-on"><?php _e('Enable','vibe');?></label>
                                        <span class="switch-selection"></span>
                                      </div>
                                    </span>
                                </h3>
                                <ul <?php if($course_settings['vibe_badge'] == 'S'){echo 'style="display:block;"';} ?>>
                                    <li><h5><?php _e('Set Badge Percentage','vibe'); ?><span><input type="number" id="vibe_course_badge_percentage" class="small_box" value="<?php echo $course_settings['vibe_course_badge_percentage']; ?>" /><?php _e(' out of 100','vibe'); ?></span></h5></li>
                                    <li><h5><?php _e('Set Badge Title','vibe'); ?><span><input type="text" id="vibe_course_badge_title" class="mid_box"  value="<?php echo $course_settings['vibe_course_badge_title']; ?>" /></span></h5></li>
                                    <li><h5><?php _e('Upload Badge Image','vibe'); ?><span>
                                         <div id="badge_image" class="upload_badge_button" data-input-name="vibe_course_badge" data-uploader-title="Upload a Badge Image" data-uploader-button-text="Set as Badge" data-uploader-allow-multiple="false">
                                            <?php
                                            if(is_numeric($course_settings['vibe_course_badge'])){
                                                $img=wp_get_attachment_image_src($course_settings['vibe_course_badge']);
                                                $img=$img[0];
                                            }else{
                                                $img = VIBE_URL.'/images/add_image.png';
                                            }  
                                            ?>
                                            <img src="<?php echo $img; ?>" />
                                            <input id="vibe_course_badge" type="hidden" value="<?php echo $course_settings['vibe_course_badge']; ?>" data-default="<?php echo VIBE_URL.'/images/add_image.png'; ?>"/>
                                        </div>
                                    </span></h5></li>
                                </ul> 
                            </li>
                            <li data-help-tag="13"><label><?php _e('NUMBER OF STUDENT SEATS','vibe'); ?></label>
                                <h3><?php _e('Number of seats in course','vibe'); ?><span>
                                    <input id="vibe_max_students" type="number" class="small_box" value="<?php echo $course_settings['vibe_max_students']; ?>" />
                                    </span>
                                </h3>
                            </li>
                            <li data-help-tag="14"><label><?php _e('Course Start Date','vibe'); ?></label>
                                <h3><?php _e('Set Course Start Date','vibe'); ?><span>
                                    <input id="vibe_start_date" type="text" class="mid_box date_box" value="<?php echo $course_settings['vibe_start_date']; ?>" />
                                    </span>
                                </h3>
                            </li>
                            <?php
                            if(bp_is_active('groups')){
                            ?>
                            <li data-help-tag="15"><label><?php _e('Course Group','vibe'); ?></label>
                                <h3><?php _e('Connect a Course Group','vibe'); ?><span>
                                    <select id="vibe_group" class="chosen">
                                    <option value=""><?php _e('None','vibe'); ?></option>
                                    <option value="add_new"><?php _e('Add new Group','vibe'); ?></option>
                                    <?php
                                    if(class_exists('BP_Groups_Group')){
                                        $vgroups =  BP_Groups_Group::get(array(
                                        'type'=>'alphabetical',
                                        'per_page'=>999
                                        ));

                                        foreach($vgroups['groups'] as $vgroup){
                                            echo '<option value="'.$vgroup->id.'" '.selected($vgroup->id,$course_settings['vibe_group']).'>'.$vgroup->name.'</option>';
                                        }
                                    }
                                    ?>
                                    </select>
                                    </span>
                                </h3>
                            </li>
                            <?php
                            }
                            if(post_type_exists('forum')){
                            ?>
                            <li data-help-tag="16"><label><?php _e('Course Forum','vibe'); ?></label>
                                <h3><?php _e('Connect a Course Forum','vibe'); ?><span>
                                    <select id="vibe_forum" class="chosen">
                                    <option value=""><?php _e('None','vibe'); ?></option>
                                    <option value="add_group_forum"><?php _e('Connect the Group forum','vibe'); ?></option>
                                    <option value="add_new"><?php _e('Add new forum','vibe'); ?></option>
                                    <?php
                                        $args= array(
                                        'post_type'=> 'forum',
                                        'numberposts'=> -1
                                        );
                                        $args = apply_filters('wplms_frontend_cpt_query',$args);
                                        $kposts=get_posts($args);
                                        foreach ( $kposts as $kpost ){
                                            echo '<option value="' . $kpost->ID . '" '.selected($course_settings['vibe_forum'],$kpost->ID).'>' . $kpost->post_title . '</option>';
                                        }
                                    ?>
                                    </select>
                                    </span>
                                </h3>
                            </li>
                            <?php
                            }
                            ?>
                            <li data-help-tag="17"><label><?php _e('Course Completion Message','vibe'); ?></label>
                            <div id="vibe_course_message" data-editable="true" data-name="content" data-max-length="1000" data-text-options="true">
                            <?php
                                echo  $course_settings['vibe_course_message'];
                            ?>
                            </div>
                            </li>
                        </ul>
                        </article>
                        <a id="save_course_settings" class="button hero"><?php _e('SAVE SETTINGS','vibe'); ?></a>
                    </div>
                    <div id="course_curriculum">
                        <h3 class="heading"><?php _e('Course Curriculum','vibe'); ?></h3>
                        <a id="add_course_section" data-help-tag="14" class="button primary small"><?php _e('ADD SECTION','vibe'); ?></a>
                        <a id="add_course_unit" data-help-tag="15" class="button primary small"><?php _e('ADD UNIT','vibe'); ?></a>
                        <a id="add_course_quiz" data-help-tag="15" class="button primary small"><?php _e('ADD QUIZ','vibe'); ?></a>
                        <ul class="curriculum">
                        <?php 
                        if(isset($_GET['action'])){
                            $curriculum = vibe_sanitize(get_post_meta($_GET['action'],'vibe_course_curriculum',false));
                           
                            if(isset($curriculum) && is_array($curriculum)){
                                foreach($curriculum as $kid){
                                    if(is_numeric($kid)){
                                        if(get_post_type($kid) == 'unit'){
                                            echo '<li><h3 class="title" data-id="'.$kid.'"><i class="icon-file"></i> '.get_the_title($kid).'</h3>
                                                    <div class="btn-group">
                                                    <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="'.get_permalink($kid).'edit/?id='.$_GET['action'].'" target="_blank" class="edit_unit">'.__('Edit Unit','vibe').'</a></li>
                                                        <li><a class="remove">'.__('Remove','vibe').'</a></li>
                                                        <li><a class="delete">'.__('Delete','vibe').'</a></li>
                                                    </ul>
                                                    </div>
                                                </li>';
                                        }else{
                                            echo '<li><h3 class="title" data-id="'.$kid.'"><i class="icon-task"></i> '.get_the_title($kid).'</h3>
                                                    <div class="btn-group">
                                                    <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="'.get_permalink($kid).'edit/?id='.$_GET['action'].'" target="_blank" class="edit_quiz">'.__('Edit Quiz','vibe').'</a></li>
                                                        <li><a class="remove">'.__('Remove','vibe').'</a></li>
                                                        <li><a class="delete">'.__('Delete','vibe').'</a></li>
                                                    </ul>
                                                    </div>
                                                  </li>';
                                        }
                                    }else{
                                        echo '<li class="new_section"><h3>'.$kid.'</h3>
                                                <div class="btn-group">
                                                <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                        <li><a class="remove">'.__('Remove','vibe').'</a></li>
                                                </ul>
                                                </div>
                                              </li>';
                                    }
                                }
                            }
                        }
                        ?>
                        </ul>
                        <ul id="hidden_base">
                            <li class="new_section">
                                <input type="text" class="section " /><a class="rem"><i class="icon-x"></i></a>
                            </li>
                            <li class="new_unit" data-help-tag="16">
                                <select>
                                    <option value=""><?php _e('SELECT A UNIT','vibe'); ?></option>
                                    <option value="add_new"><?php _e('ADD NEW UNIT','vibe'); ?></option>
                                    <?php
                                        $args= array(
                                            'post_type'=> 'unit',
                                            'numberposts'=> 999
                                            );
                                        
                                        $args = apply_filters('wplms_backend_cpt_query',$args);
                                        $posts=get_posts($args);
                                        foreach ( $posts as $post ){
                                            echo '<option value="' . $post->ID . '"  data-link="'.get_permalink($post->ID).'?">' . $post->post_title . '</option>';
                                        }
                                        wp_reset_postdata();
                                    ?>
                                </select>
                                <ul class="new_unit_actions">
                                    <li><input type="text" name="new_unit[]" class="new_unit_title" placeholder="<?php _e('Add a unit title','vibe'); ?>"/></li>
                                    <li>
                                        <div class="btn-group">
                                          <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                          <ul class="dropdown-menu" role="menu">
                                            <li><a class="publish"><?php _e('Publish','vibe'); ?></a></li>
                                            <li><a class="remove_new"><?php _e('Remove','vibe'); ?></a></li>
                                          </ul>
                                        </div>
                                    </li>
                                </ul>
                                <div class="btn-group unit_actions">
                                    <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="" target="_blank" class="edit_unit"><?php _e('Edit Unit','vibe'); ?></a></li>
                                        <li><a class="remove"><?php _e('Remove','vibe') ?></a></li>
                                    </ul>
                                </div>  
                            </li>
                            <li class="new_quiz" data-help-tag="18">
                                <select>
                                    <option value=""><?php _e('SELECT A QUIZ','vibe'); ?></option>
                                    <option value="add_new"><?php _e('ADD NEW QUIZ','vibe'); ?></option>
                                    <?php
                                        $args= array(
                                            'post_type'=> 'quiz',
                                            'numberposts'=> 999
                                            );
                                        
                                        $args = apply_filters('wplms_frontend_cpt_query',$args);
                                        $posts=get_posts($args);
                                        foreach ( $posts as $post ){
                                            echo '<option value="' . $post->ID . '" data-link="'.get_permalink($post->ID).'?id='.$_GET['action'].'&">' . $post->post_title . '</option>';
                                        }
                                        wp_reset_postdata();
                                    ?>
                                </select>
                                <ul class="new_quiz_actions">
                                    <li><input type="text" name="new_quiz[]" class="new_quiz_title" /></li>
                                    <li>
                                        <div class="btn-group">
                                          <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                          <ul class="dropdown-menu" role="menu">
                                            <li><a class="publish"><?php _e('Publish','vibe'); ?></a></li>
                                            <li><a class="remove_new"><?php _e('Remove','vibe'); ?></a></li>
                                          </ul>
                                        </div>
                                    </li>
                                </ul>
                                <div class="btn-group quiz_actions">
                                    <button type="button" class="btn btn-course dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="" target="_blank" class="edit_quiz"><?php _e('Edit Quiz','vibe'); ?></a></li>
                                        <li><a class="remove"><?php _e('Remove','vibe') ?></a></li>
                                    </ul>
                                </div>  
                            </li>
                        </ul>
                        <a id="save_course_curriculum" class="button hero"><?php _e('SAVE CURRICULUM','vibe'); ?></a>
                    </div>
                    <div id="course_pricing">
                        <h3 class="heading"><?php _e('COURSE PRICING','vibe'); ?></h3>
                        <ul>
                            <li><h3><?php _e('Free Course','vibe'); ?><span>
                                        <div class="switch" data-help-tag="19">
                                            <input type="radio" class="switch-input vibe_course_free" name="vibe_course_free" value="H" id="disable_free" <?php checked($course_pricing['vibe_course_free'],'H'); ?>>
                                            <label for="disable_free" class="switch-label switch-label-off"><?php _e('No','vibe');?></label>
                                            <input type="radio" class="switch-input vibe_course_free" name="vibe_course_free" value="S" id="enable_free" <?php checked($course_pricing['vibe_course_free'],'S'); ?>>
                                            <label for="enable_free" class="switch-label switch-label-on"><?php _e('Yes','vibe');?></label>
                                            <span class="switch-selection"></span>
                                          </div>
                                        </span>
                                    </h3> 
                            </li>
                            <?php
                             if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || (function_exists('is_plugin_active_for_network') && is_plugin_active_for_network( 'woocommerce/woocommerce.php'))) {
    
                            ?>
                                <li class="course_product" data-help-tag="19">
                                    <h3><?php _e('Set a Course Product','vibe'); ?><span>
                                        <select id="vibe_product" class="chosen">
                                            <option value=""><?php _e('Select a Course Product','vibe'); ?></option>
                                            <option value="none"><?php _e('No Product','vibe'); ?></option>
                                            <option value="add_new"><?php _e('ADD NEW PRODUCT','vibe'); ?></option>
                                            <?php
                                                $args= array(
                                                    'post_type'=> 'product',
                                                    'numberposts'=> 999
                                                    );
                                                
                                                $args = apply_filters('wplms_fontend_cpt_query',$args);
                                                $posts=get_posts($args);
                                                foreach ( $posts as $post ){
                                                    echo '<option value="' . $post->ID . '" '.selected($course_pricing['vibe_product'],$post->ID).'>' . $post->post_title . '</option>';
                                                }
                                                wp_reset_postdata();
                                            ?>
                                        </select>
                                    </span>
                                    </h3>
                                </li>
                                <li class="new_product">
                                    <h3><?php _e('Set Product type','vibe'); ?><span>
                                        <div class="switch switch-subscription">
                                                <input type="radio" class="switch-input vibe_subscription" name="vibe_subscription" value="H" id="disable_sub" <?php checked($course_pricing['vibe_subscription'],'H'); ?>>
                                                <label for="disable_sub" class="switch-label switch-label-off"><?php _e('Full Course','vibe');?></label>
                                                <input type="radio" class="switch-input vibe_subscription" name="vibe_subscription" value="S" id="enable_sub" <?php checked($course_settings['vibe_subscription'],'S'); ?>>
                                                <label for="enable_sub" class="switch-label switch-label-on"><?php _e('Subscription','vibe');?></label>
                                                <span class="switch-selection"></span>
                                              </div>
                                    </span></h3>
                                </li>
                                <li class="new_product">
                                    <h5><?php _e('Set Product Price','vibe'); ?><span>
                                    <input type="text" id="product_price" class="small_box" />
                                    <?php echo get_woocommerce_currency(); ?></span></h5>
                                    <h5 class="product_duration"><?php _e('Set Subscription duration','vibe')?><span>
                                        <input type="number" id="product_duration" class="small_box" />
                                        <?php $product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400); echo calculate_duration_time($product_duration_parameter); ?>
                                    </span></h5>
                                </li>
                            
                            <?php    
                            }
                            ?>
                            <?php
                                do_action('wplms_front_end_pricing');
                            ?>
                            <?php
                                if ( in_array( 'paid-memberships-pro/paid-memberships-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && function_exists('pmpro_getAllLevels')) { 
                                  $levels=pmpro_getAllLevels(); // Get all the PMPro Levels
                                  ?>
                                  <li class="course_membership"><h3><?php _e('Set Course Memberships','vibe'); ?><span>
                                          <select id="vibe_pmpro_membership" class="chosen" multiple>
                                              <?php
                                              if(isset($levels) && is_array($levels)){
                                                  foreach($levels as $level){
                                                      if(!is_Array($course_pricing['vibe_pmpro_membership']))
                                                          $course_pricing['vibe_pmpro_membership'] = array();
                                                      
                                                  if(is_object($level))
                                                      echo '<option value="'.$level->id.'" '.(in_array($level->id,$course_pricing['vibe_pmpro_membership'])?'selected':'').'>'.$level->name.'</option>';
                                                  }
                                              }
                                              ?>
                                          </select>
                                      </span>
                                      </h3>
                                  </li>
                              <?php    
                              }
                            ?>
                            <li>
                                <a id="save_pricing" class="button hero"><?php _e('Save Course Pricing','vibe'); ?></a>
                            </li>
                        </ul>
                    </div>
                    <div id="course_live">
                        <h3 class="heading"><?php _e('Go Live'); ?></h3>
                        <?php
                            if(isset($_GET['action'])){
                                $post_status = get_post_status($_GET['action']);    
                                echo '<a id="publish_course" class="button big hero">'.__('GO LIVE','vibe').'</a>';
                            }else{
                        ?>
                        <?php   $new_course_status = vibe_get_option('new_course_status');
                                if(isset($new_course_status) && $new_course_status == 'publish')
                                    echo '<a id="publish_course" class="button big hero">'.__('GO LIVE','vibe').'</a>';
                                else
                                    echo '<a id="publish_course" class="button big hero">'.__('SEND FOR APPROVAL','vibe').'</a>';
                            }
                        ?>
                    </div>
                    <?php wp_nonce_field('create_course'.$user_id,'security'); ?>
                    <?php
                    if(isset($_GET['action'])){
                        echo '<input type="hidden" id="course_id" value="'.$_GET['action'].'" />';
                    }
                    ?>
                </div>

            </div>
            <div class="col-md-3 col-sm-3">      
                <div class="course-create-help">
                    <ul id="create_course_help" class="active">
                        <li class="active"><span>1</span><?php _e('Click on text to enter a Course title, delete the existing text in the title','vibe'); ?></li>
                        <li><span>2</span> <?php _e('Select a Course Category, or Add a New one','vibe'); ?><br />&nbsp;</li>
                        <li><span>3</span> <?php _e('Select or upload a course image, this image is used as Course avatar.','vibe'); ?></li>
                        <li><span>4</span> <?php _e('Enter a Short description about the course, the full description can be added later on. Start by deleting the existing text in the title','vibe'); ?></li>
                        <li <?php echo ((isset($linkage) && $linkage)?'':'style="display:none;"'); ?>><span>5</span> <?php _e('Linkage greatly reduces the unit/quiz/question lists loaded on the page. Once a Linkage term is selected, the lists will only units/quizzes/questions connected to the same linkage term. If editing a course , save and refresh after selecting a Linkage term.','vibe'); ?></li>
                        <li <?php echo (isset($_GET['action'])?'':'style="display:none;"'); ?>><span><?php echo ((isset($linkage) && $linkage)?'6':'5'); ?></span> <?php _e('A Offline Course is not visible to students in the course directory.','vibe'); ?></li>
                    </ul>
                    <ul id="course_settings_help">
                        <li class="active"><span>1</span><?php _e('Enter the maximum duration for the course in days. This is the maximum duration within which the student should complete the course. Use 9999 for unlimited access to course.','vibe'); ?></li>
                        <li><span>2</span><?php _e('Set Evaluation mode, Manual evaluation (from Course -> Admin ) or Automatic (* Requires at least 1 quiz in the course ) ','vibe'); ?></li>
                        <li><span>3</span><?php _e('Set a pre-required course. A Pre Course should be completeted before a student can access this course','vibe'); ?></li>
                        <li><span>4</span><?php _e('Enable Drip Feed course. Students see next lesson after x days. X is Duration of Drip. ','vibe'); ?></li>
                        <li><span>5</span><?php _e('Set a Course Certificate. Set certificate percentage marks which a student should achieve to get the course certificate. Select a certificate template.','vibe'); ?></li>
                        <li><span>6</span><?php _e('Set a course Badge. Set percentage marks which a student should get to get the course badge. Upload the Badge image.','vibe'); ?></li>
                        <li><span>7</span><?php _e('Set number of seats available for students to take the course. Once full students can not join the course. Enter 9999 for unlimited seats.','vibe'); ?></li>
                        <li><span>8</span><?php _e('Set a Course Start date. If set to a post date, students can only join the course on/after the start date.','vibe'); ?></li>
                        <li><span>9</span><?php _e('Connect a Course Group OR Create a new one','vibe'); ?></li>
                        <li><span>10</span><?php _e('Connect a Course forum Or Create a new one or Create a Course Group Forum','vibe'); ?></li>
                        <li><span>11</span><?php _e('Enter message which student see after submitting the course.','vibe'); ?></li>
                    </ul>
                    <ul id="course_curriculum_help">
                        <li class="active"><span>1</span><?php _e('Start building curriculum by adding Units, Quizzes and sections.','vibe'); ?></li>
                        <li><span>2</span><?php _e('After adding a new unit or quiz, make sure you publish it','vibe'); ?></li>
                        <li><span>3</span><?php _e('Save the curriculum only when the button is green.','vibe'); ?></li>
                    </ul>
                    <ul id="course_pricing_help">
                        <li class="active"><span>1</span><?php _e('Connect the course with a product. A product defines the pricing of a course.<br /><br />a. Enter the Price of the product to set the price of your course.<br />b. Select the type of product.<br /><br /> If product is set to subscription mode, you need to set the subscription duration for the product. <br />A student purchasing a Product with subscription gets access for the course for the subscription duration.<br /> A student purchasing a product without subscription gets access for the course for full course duration as entered in course settings.','vibe'); ?></li>
                    </ul>
                    <ul id="course_live_help">
                        <li class="active"><span>1</span><?php _e('Go live with your course if "Publish" access is granted by Administrator you course will be live as soon as you click on Go Live button.','vibe'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php
do_action('wplms_after_create_course_page');
get_footer();
?>