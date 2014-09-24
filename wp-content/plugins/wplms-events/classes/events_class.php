<?php

include_once 'wplms_events.php';
if(!class_exists('WPLMS_Events_Interface'))
{   
    class WPLMS_Events_Interface extends WPLMS_Events  // We'll use this just to avoid function name conflicts 
    {
            
        public function __construct(){   
        	global $bp;
        	global $wpdb;
        	
        	add_action('wp_enqueue_scripts',array($this,'enqueue_scripts'));
        	add_filter('wplms_course_nav_menu',array($this,'wplms_events_menu_link'),1,10);

            add_action('wplms_before_event', array($this,'wplms_gmap_enqueue'));
            
            add_action( 'wp_ajax_display_event_list',  array($this,'display_event_list') );
            add_action( 'wp_ajax_nopriv_display_event_list',  array($this,'display_event_list') );
           
            add_filter('wplms_events_invite_buttons',array($this,'wplms_events_invite_buttons'));
            add_action( 'wplms_single_event_messages',  array($this,'wplms_send_event_invitations') );
            add_action( 'wplms_single_event_messages',  array($this,'wplms_send_event_reminder') );

            add_action('wplms_event_after_content',array($this,'wplms_event_access_message') );
            add_filter('wplms_event_access_flag',array($this,'wplms_event_access_flag'));
            add_action( 'wplms_event_after_content',  array($this,'wplms_event_show_invitations_status') );
            
            add_action( 'wplms_before_single_event',  array($this,'wplms_event_accept_reject_invitation') );

            /*if ( !defined( 'BP_AVATAR_THUMB_WIDTH' ) )
            	define( 'BP_AVATAR_THUMB_WIDTH', 150 ); //change this with your desired thumb width
            	*/
           
        } // END public function __construct


        /**
         * Objective: Register & Enqueue your Custom scripts
         * Developer notes:
         * Hook you custom scripts required for the plugin here.
         */
        function enqueue_scripts(){

            wp_enqueue_style( 'wplms-events-css', plugins_url( '../css/wplms-events.css' , __FILE__ ));
            wp_enqueue_script( 'wplms-events-js', plugins_url( '../js/wplms-events.js' , __FILE__ ));

            if(is_singular('wplms-event')){
             $protocol = is_ssl() ? 'https' : 'http';
              wp_enqueue_script( 'wplms-events-gmap-js', $protocol.'://maps.google.com/maps/api/js?sensor=false');
            }
        }
        
        /**
         * Get number of days since the start of the week.
         *
         * @since 1.5.0
         *
         * @param int $num Number of day.
         * @return int Days since the start of the week.
         */
        function calendar_week_mod($num) {
            $base = 7;
            return ($num - $base*floor($num/$base));
        }

        /**
         * MODIFIED VERSION OF WP CALENDAR
         * Display calendar with days that have posts as links.
         *
         *
         * @since 1.0.0
         * @uses calendar_week_mod()
         *
         * @param bool $initial Optional, default is true. Use initial calendar names.
         * @param bool $echo Optional, default is true. Set to false for return.
         * @return string|null String when retrieving, null when displaying.
         */
        function wplms_event_calendar($course=NULL) {
            global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
            $initial = false; $echo = true;
            if ( isset($_GET['w']) )
                $w = ''.intval($_GET['w']);

            // week_begins = 0 stands for Sunday
            $week_begins = intval(get_option('start_of_week'));

            // Let's figure out when we are
            if ( !empty($monthnum) && !empty($year) ) {
                $thismonth = ''.zeroise(intval($monthnum), 2);
                $thisyear = ''.intval($year);
            } elseif ( !empty($w) ) {
                // We need to get the month from MySQL
                $thisyear = ''.intval(substr($m, 0, 4));
                $d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
                $thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')");
            } elseif ( !empty($m) ) {
                $thisyear = ''.intval(substr($m, 0, 4));
                if ( strlen($m) < 6 )
                        $thismonth = '01';
                else
                        $thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
            } else {
                $thisyear = gmdate('Y', current_time('timestamp'));
                $thismonth = gmdate('m', current_time('timestamp'));
            }

            if(isset($_GET['month'])){
                $thismonth= $_GET['month'];
            }
            if(isset($_GET['year'])){
                $thisyear= $_GET['year'];
            }

            $unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
            $last_day = date('t', $unixmonth);


            

            $premonth = zeroise(($thismonth -1),2);
            $preyear = $nextyear = $thisyear;

            if($thismonth == 1){
                $premonth=12;
                $preyear=$thisyear-1;
            }

            $nextmonth = zeroise(($thismonth + 1),2);
            if($thismonth == 12){
                $nextmonth='01';
                $nextyear=$thisyear+1;
            }

           $previous = $next=1;    
            
            /* translators: Calendar caption: 1: month name, 2: 4-digit year */
            $calendar_caption = _x('%1$s %2$s', 'calendar caption');
            $calendar_output = '<table id="wplms-calendar">
            <caption>'.__('EVENTS IN ','wplms-events') . sprintf($calendar_caption, $wp_locale->get_month($thismonth), date('Y', $unixmonth)) . '</caption>
            <thead>
            <tr>';

            $myweek = array();

            for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
                $myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
            }

            foreach ( $myweek as $wd ) {
                $day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
                $wd = esc_attr($wd);
                $calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
            }

            $calendar_output .= '
            </tr>
            </thead>

            <tfoot>
            <tr>';

            if ( $previous ) {
                if($thismonth == 1){
                    $newmonth=12;
                    $newyear=$thisyear-1;
                    $monthstring='month=12&year='.$newyear;
                }else{
                    $newmonth=zeroise(($thismonth-1),2);
                    $newyear=$thisyear;
                    $monthstring='month='.$newmonth;
                    if(isset($_GET['year']))
                        $monthstring .='&year='.$newyear;
                }
                $calendar_output .= "\n\t\t".'<td colspan="3" id="prev"><a href="?action=events&'.$monthstring.'" title="' . esc_attr( sprintf(__('View Events for %1$s %2$s'), $wp_locale->get_month($newmonth), date('Y', mktime(0, 0 , 0, $newmonth, 1, $newyear)))) . '"> &lsaquo; ' . $wp_locale->get_month_abbrev($wp_locale->get_month($newmonth)) .'</a></td>';
            } else {
                $calendar_output .= "\n\t\t".'<td colspan="3" id="prev" class="pad">&nbsp;</td>';
            }

            $calendar_output .= "\n\t\t".'<td class="pad">&nbsp;</td>';


            if ( $next ) {
                if($thismonth == 12){
                    $newmonth=01;
                    $newyear=$thisyear+1;
                    $monthstring='month=01&year='.($thisyear+1);
                }else{
                    $newmonth=zeroise(($thismonth+1),2);
                    $newyear=$thisyear;
                    $monthstring='month='.$newmonth;
                    if(isset($_GET['year']))
                        $monthstring .='&year='.$newyear;
                }

                $calendar_output .= "\n\t\t".'<td colspan="3" id="next"><a href="?action=events&'.$monthstring.'" title="' . esc_attr( sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($newmonth), date('Y', mktime(0, 0 , 0, $newmonth, 1, $newyear))) ) . '"> '. $wp_locale->get_month_abbrev($wp_locale->get_month($newmonth)) . ' &rsaquo;</a></td>';
            } else {
                $calendar_output .= "\n\t\t".'<td colspan="3" id="next" class="pad">&nbsp;</td>';
            }

            $calendar_output .= '
            </tr>
            </tfoot>

            <tbody>
            <tr>';


            $result = strtotime("{$thisyear}-{$thismonth}-01");
            $result = strtotime('-1 second', strtotime('+1 month', $result));
            $end_date_month= date('Y-m-d', $result);
            $result = strtotime("{$thisyear}-{$thismonth}-01");
            $start_date_month = date('Y-m-d', $result);
            
            

            if(isset($course) && $course !=''){
                $eventdaysquery = $wpdb->get_results($wpdb->prepare("SELECT start.post_id as id, start.meta_value as start_date, end.meta_value as end_date
                FROM {$wpdb->postmeta} AS start
                INNER JOIN {$wpdb->postmeta} AS end
                ON start.post_id=end.post_id
                WHERE start.post_id IN (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'vibe_event_course' AND meta_value = %d)
                AND start.meta_key = 'vibe_start_date'
                AND end.meta_key = 'vibe_end_date'
                AND start.meta_value <= DATE('$end_date_month')
                AND end.meta_value >=  DATE('$start_date_month')
                ",$course));

            }else{
                $eventdaysquery = $wpdb->get_results("SELECT start.post_id as id, start.meta_value as start_date, end.meta_value as end_date
                FROM {$wpdb->postmeta} AS start
                INNER JOIN {$wpdb->postmeta} AS end
                ON start.post_id=end.post_id
                WHERE start.meta_key = 'vibe_start_date'
                AND end.meta_key = 'vibe_end_date'
                AND start.meta_value <= '$end_date_month'
                AND end.meta_value  >=  '$start_date_month'
                ");    
            }
            


            $eventdays=array();
            foreach($eventdaysquery as $event){
                $start=(($event->start_date <= $start_date_month)?$start_date_month:$event->start_date);
                $end=(($event->end_date >= $end_date_month)?$end_date_month:$event->end_date);

                
                $startTime = intval(date('d',strtotime($start))); 
                $endTime = intval(date('d',strtotime($end))); 

                 while ($startTime <= $endTime){

                    if(!isset($eventdays[$startTime]))
                        $eventdays[$startTime]=1;
                    else
                        $eventdays[$startTime]++;

                    $startTime++;
                }

            }
            

            // See how much we should pad in the beginning
            $pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
            if ( 0 != $pad )
                $calendar_output .= "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

            $daysinmonth = intval(date('t', $unixmonth));
            for ( $day = 1; $day <= $daysinmonth; ++$day ) {
                if ( isset($newrow) && $newrow )
                    $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
                $newrow = false;

                if ( $day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp')) )
                    $calendar_output .= '<td id="today">';
                else
                    $calendar_output .= '<td>';

                if ( isset($eventdays[$day]) ) // any posts today?
                        $calendar_output .= '<a href="#" class="event_list" data-course="'.((get_post_type() == BP_COURSE_SLUG)?get_the_ID():'*').'" data-day="'.$day.'" data-month="'.$thismonth.'" data-year="'.$thisyear.'">'.$day.' <span>'.$eventdays[$day].'</span></a>';
                else
                    $calendar_output .= $day;
                $calendar_output .= '</td>';

                if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
                    $newrow = true;
            }

            $pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
            if ( $pad != 0 && $pad != 7 )
                $calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';

            $calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";

            

            if ( $echo )
                echo apply_filters( 'wplms_event_calendar',  $calendar_output );
            else
                return apply_filters( 'wplms_event_calendar',  $calendar_output );

        }

        function wplms_events_menu_link($nav_menu){
        	$nav_menu['events'] = array(
                        'id' => 'events',
                        'label'=>__('Events ','wplms-events'),
                        'action' => 'events',
                        'link'=>bp_get_course_permalink(),
                    );
        	return $nav_menu;
        }


        function display_event_list(){

            $course_id = intval($_POST['course']);
            $day = intval($_POST['day']);
            $month = intval($_POST['month']);
            $year = intval($_POST['year']);
             
            if(checkdate ($month ,$day ,$year )){
                $date = $year.'-'.zeroise($month,2).'-'.zeroise($day,2);
                if(is_numeric($course_id)){
                    $this->get(array(
                    'course' => $course_id,
                    'date' => $date,
                    'posts_per_page' => -1
                    ));
                }else{
                    $this->get(array(
                    'date' => $date,
                    'posts_per_page' => -1
                    ));
                }
                
               
               echo '<div class="events_this_day">
               <h3 class="heading">'.__('Events ','wplms-events').'<span>'.$this->query->found_posts.'</span></h3>';
               if($this->have_posts()):
                echo '<ul class="events_list">';
               while($this->have_posts()):$this->the_post();
               $icon =  get_post_meta(get_the_ID(),'vibe_icon',true);
               $color =  get_post_meta(get_the_ID(),'vibe_color',true);
               $start_time =  get_post_meta(get_the_ID(),'vibe_start_time',true);
               $end_time =  get_post_meta(get_the_ID(),'vibe_end_time',true);
               echo '<li><a href="'.get_permalink().'">'.(isset($icon)?'<strong style="background-color:'.$color.'"><i class="'.$icon.'"></i></strong>':'').get_the_title().' <span>From : '.$start_time.' - To : '.$end_time.'</span></a></li>';
               endwhile;
               echo '</ul>';
               endif;
               echo '</div>';
            }else{
                echo '<div class="message">'.__('Incorrect Date selected','wplms-events').'</div>';
            }
            die();
        }

        function wplms_send_event_invitations(){
            if(!isset($_POST['send_invitations']))
                return;

            if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe'.$course_id) ){
                 echo '<div class="error">';_e('Security check Failed. Contact Administrator.','wplms-events'); echo '</div>';
                 return;
            }
            
            if(isset($_POST['invitation_subject']) && $_POST['invitation_subject'] !='')
                $subject = html_entity_decode($_POST['invitation_subject']);
            else{
                 echo '<div class="error">';_e('Please enter a Invitation subject','wplms-events');echo '</div>';
                 return;
            }

            if(isset($_POST['invitation_message']) && $_POST['invitation_message'] !='')
                $message = html_entity_decode($_POST['invitation_message']);
            else{
                 echo '<div class="error">';_e('Please enter a Invitation Message','wplms-events');echo '</div>';
                 return;
            }

            $event_id=get_the_ID();
            $course_id=get_post_meta($event_id,'vibe_event_course',true);
            
            $message .='<br /><ul>
            <li>'.__('EVENT : ','wplms-events').get_the_title($event_id).'<a href="'.get_permalink($event_id).'">'.__('view event','wplms-events').'</a></li>
            <li>'.__('COURSE : ','wplms-events').get_the_title($course_id).'<a href="'.get_permalink($course_id).'">'.__('view course','wplms-events').'</a></li>';
            

            $students=bp_course_get_students_undertaking($course_id);
            foreach($students as $student){
                $links='';
                $check_invite=get_post_meta($event_id,$student,true);
                if(!isset($check_invite) || $check_invite=='' ){
                    $acceptnoncelink = wp_nonce_url(get_permalink($event_id).'?accept','vibe_'.$event_id.$student,'security');
                    $rejectnoncelink = wp_nonce_url(get_permalink($event_id).'?reject','vibe_'.$event_id.$student,'security');
                    $links ='<li>
                    <a href="'.$acceptnoncelink.'" class="button small"><span> '.__('ACCEPT','wplms-events').'</span></a>
                    <a href="'.$rejectnoncelink.'" class="button small"><span> '.__('REJECT','wplms-events').'</span></a>
                    </li></ul>';
                    messages_new_message( array(
                        'sender_id' => get_current_user_id(), 
                        'subject' => $subject, 
                        'content' => $message.$links,   
                        'recipients' => $student 
                        ) ); 
                }
            }
            $vsi = get_post_meta($event_id,'vibe_send_invitation',true);
            if(!isset($vsi))$vsi=0;
            $vsi++;
            update_post_meta($event_id,'vibe_send_invitation',$vsi);

            echo '<div class="success">';_e('Invitation Message successfully sent to ','wplms-events'); 
            echo count($students).__(' Students','wplms-events');echo '</div>';
            return;
        }

        function wplms_send_event_reminder(){
            if(!isset($_POST['send_reminder']))
                return;

            if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe'.$course_id) ){
                 echo '<div class="error">';_e('Security check Failed. Contact Administrator.','wplms-events'); echo '</div>';
                 return;
            }

            if(isset($_POST['reminder_subject']) && $_POST['reminder_subject'] !='')
                $subject = html_entity_decode($_POST['reminder_subject']);
            else{
                 echo '<div class="error">';_e('Please enter a reminder subject','wplms-events');echo '</div>';
                 return;
            }

            if(isset($_POST['reminder_message']) && $_POST['reminder_message'] !='')
                $message = html_entity_decode($_POST['reminder_message']);
            else{
                 echo '<div class="error">';_e('Please enter a reminder Message','wplms-events');echo '</div>';
                 return;
            }

            $event_id=get_the_ID();
            $vsi = get_post_meta($event_id,'vibe_send_invitation',true);
            if(!isset($vsi) || $vsi ==''){
                echo '<div class="error">';_e('Please send an invitation to course students.','wplms-events'); echo '</div>';
                 return;
            }
            $students=bp_course_get_students_undertaking($course_id);

            $to = array();
            foreach($students as $student){
                $status = get_post_meta(get_the_ID(),$student,true);
                if(isset($status) && $status!=''){
                    if($status ==1){
                        $user_info = get_userdata($student);
                        $to[]=$user_info->user_email;
                    }
                }
            }
            if(count($to)){
                $current_user = wp_get_current_user();
                $headers = 'From: '.$current_user->display_name.' <'.$current_user->user_email.'>' . "\r\n";
                wp_mail($to,$subject,$message,$headers);
                echo '<div class="success">';_e('Reminder Message successfully sent to ','wplms-events'); 
                echo count($to).__(' Students','wplms-events');echo '</div>';
            }

        }


        function wplms_event_accept_reject_invitation(){
            
            if(!isset($_GET['security']))
                return;

            $security=$_GET['security'];
            $event_id=get_the_ID();
            $user_id=get_current_user_id();

            if ( !isset($security)){ //|| !wp_verify_nonce($security,'vibe_'.$event_id.$user_id )//Validate NONCE
                 echo '<div class="error">';_e('Security check Failed. Contact Administrator.','wplms-events'); echo '</div>';
                 return;
            }

            if(isset($_GET['accept'])){
                if(update_post_meta($event_id,$user_id,1)){
                    echo '<div class="success">';_e('Invitation accepted.','wplms-events'); echo '</div>';
                    do_action('wplms_event_invitation_accepted');
                }else{
                    echo '<div class="error">';_e('Unable to accept invitation.','wplms-events'); echo '</div>';
                }
                bp_course_record_activity(array(
                  'action' => __('Student Accepted Event Invitation ','wplms-events').get_the_title($event_id),
                  'content' => __('Student ','wplms-events').bp_core_get_userlink( $user_id ).__(' accepted invitation for Event ','wplms-events').get_the_title($event_id),
                  'type' => 'invitation_response',
                  'primary_link' => get_permalink($event_id),
                  'item_id' => $event_id,
                  'secondary_item_id' => $user_id
                ));
            }
            if(isset($_GET['reject'])){
                if(update_post_meta($event_id,$user_id,2)){
                    echo '<div class="success">';_e('Invitation rejected.','wplms-events'); echo '</div>';
                    do_action('wplms_event_invitation_rejected');
                    bp_course_record_activity(array(
                      'action' => __('Student Rejected Event Invitation ','wplms-events').get_the_title($event_id),
                      'content' => __('Student ','wplms-events').bp_core_get_userlink( $user_id ).__(' accepted invitation for Event ','wplms-events').get_the_title($event_id),
                      'type' => 'invitation_response',
                      'primary_link' => get_permalink($event_id),
                      'item_id' => $event_id,
                      'secondary_item_id' => $user_id
                    ));
                }else{
                    echo '<div class="error">';_e('Unable to reject invitation.','wplms-events'); echo '</div>';
                }
            }
        }

        function wplms_event_show_invitations_status(){
            
            $accepted = $rejected = $awaited = 0;
            $sent = get_post_meta(get_the_ID(),'vibe_send_invitation',true);

            if(isset($sent) && $sent && current_user_can('edit_posts')){
                echo '<h3 class="heading">'.__('Invitation Responses','wplms-events').'</h3>';
                $course_id=get_post_meta(get_the_ID(),'vibe_event_course',true);
                $students=bp_course_get_students_undertaking($course_id);
                echo '<ul class="invitation_students">';
                foreach($students as $student){
                    $status = get_post_meta(get_the_ID(),$student,true);
                    if(isset($status) && $status!=''){
                        if($status ==1)
                            $accepted++;
                        else
                            $rejected++;
                    }else{
                        $awaited++;
                    }
                    echo '<li><label>'.bp_core_get_userlink( $student ).'</label><span>'.((isset($status) && $status!='')?(($status==1)?'<i class="icon-check tip" title="'.__('ACCEPTED','wplms-events').'"></i>':'<i class="icon-x tip" title="'.__('REJECTED','wplms-events').'"></i>'):'<i class="icon-alarm tip" title="'.__('AWAITING RESPONSE','wplms-events').'"></i>').'</span></li>';
                }
                echo '</ul>';
                echo '<ul class="invitation_results">
                <li><strong>'.__('INVITATION RESULTS','wplms-events').'</strong></li>
                <li><label>'.__('ACCEPTED','wplms-events').'</label><span>'.$accepted.'</span></li>
                 <li><label>'.__('REJECTED','wplms-events').'</label><span>'.$rejected.'</span></li>
                 <li><label>'.__('AWAITING RESPONSE','wplms-events').'</label><span>'.$awaited.'</span></li>
                </ul>';
            }
        }
        /**
         * Activate the plugin
         */
        public static function activate()
        {
          flush_rewrite_rules(false );
        } // END public static function activate

        /**
         * Deactivate the plugin
         */     
        public static function deactivate()
        {
            flush_rewrite_rules(false );
        } // END public static function deactivate

        function wplms_event_access_flag($access_flag=1){
            global $post;
            $current_user = wp_get_current_user();
            
            if($current_user->ID == $post->post_author)
                return 1;

            $product=get_post_meta(get_the_ID(),'vibe_product',true);
            if(isset($product) && $product){  
                // determine if customer has bought product
                
                if( wc_customer_bought_product( $current_user->email, $current_user->ID, $product ) ){
                   return 1;
                }else{
                    return 0;
                }
            }
            return $access_flag;
        }
        function wplms_event_access_message(){
            if(!$this->wplms_event_access_flag()){
                echo '<div class="event_not_accessible">';
                $product=get_post_meta(get_the_ID(),'vibe_product',true);

                if(isset($product) && $product){
                    echo '<div class="access_link">
                    <a href="'.get_permalink($product).'" class="button full create-group-button">'.__('GET ACCESS TO THIS EVENT','wplms-events').'</a>
                    </div>';
                }
                echo '</div>';
            }    
        }

        function wplms_events_invite_buttons($invite){ //1 Means show breadcrumbs, 0 means show buttons
            $private_event = get_post_meta(get_the_ID(),'vibe_private_event',true);
            

            if(isset($private_event) && $private_event=='S'){
                global $post;
                $current_user = wp_get_current_user();
                $product=get_post_meta(get_the_ID(),'vibe_product',true);
                if(isset($product) && $product){  
                    // get user attributes
                    
                    // determine if customer has bought product
                    if( wc_customer_bought_product( $current_user->email, $current_user->ID, $product->id ) ){
                       return 0;
                    }else{
                        return 1;
                    }
                }
                return 1;

            }

            return $invite;
        }

    } // END class WPLMS_Customizer_Class
} // END if(!class_exists('WPLMS_Customizer_Class'))

?>