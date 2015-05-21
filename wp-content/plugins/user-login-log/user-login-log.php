<?php
/*
  Plugin Name: User Login Log
  Plugin URI: http://weblizar.com
  Description: This plugin track records of wordpress user login with set of multiple information like ip, date , time, country , city, user name etc.
  Author: weblizar
  Version: 1.8
  Author URI: http://weblizar.com
 */

if( !class_exists( 'UserLoginLog' ) )
{

 class UserLoginLog
 {
    
    public $table = 'user_login_log';
    private $stats_duration = null; //days
    private $opt_name = 'user_login_log';
    private $opt = false;
    private $login_success = 1;
    public $data_labels = array();

     private $values;

    function __construct()
    {
        global $wpdb;

        if ( is_multisite() )
        {
            // get main site's table prefix
            $main_prefix = $wpdb->get_blog_prefix(1);
            $this->table = $main_prefix . $this->table;
        }
        else
        {
            // non-multisite - regular table name
            $this->table = $wpdb->prefix . $this->table;
        }
        $this->opt = get_option($this->opt_name);

      

        add_action( 'admin_menu', array($this, 'ull_admin_menu') );
        add_action('admin_init', array($this, 'settings_api_init') );
        add_action('admin_head', array($this, 'screen_options') );

        

        //Init login actions
        add_action( 'init', array($this, 'init_login_actions') );

        //Init CSV Export
        add_action('admin_init', array($this, 'init_csv_export') );
        add_action('admin_init', array($this, 'delete_all') );

        //Style the stats table
        add_action( 'admin_head', array($this, 'admin_header') );

        //Initialize scheduled events (when some one visits site in front-end)
        add_action( 'wp', array($this, 'init_scheduled_events') );
        add_action('truncate_ull', array($this, 'cron') );

        //Load Locale
        add_action('plugins_loaded', array($this, 'load_locale'), 10 );

        //For translation purposes
        $this->data_labels = array(
            'Successful'        => __('Successful', 'ull'),
            'Failed'            => __('Failed', 'ull'),
            'login'             => __('login', 'ull'),
            'User Agent'        => __('User Agent', 'ull'),
            'login Redirect'    => __('login Redirect', 'ull'),
            'id'                => __('#', 'ull'),
            'uid'               => __('User ID', 'ull'),
            'user_login'        => __('Username', 'ull'),
            'user_role'         => __('User Role', 'ull'),
            'name'              => __('Name', 'ull'),
            'time'              => __('Time', 'ull'),
            'ip'                => __('IP Address', 'ull'),
            'login_result'      => __('login Result', 'ull'),
            'data'              => __('Data', 'ull'),
        );

        //Deactivation hook
        register_deactivation_hook(__FILE__, array($this, 'deactivation') );

    }


     function set($name, $value)
     {
         $this->values[$name] = $value;
     }


     function get($name)
     {
         return (isset($this->values[$name])) ? $this->values[$name] : false;
     }


    function load_locale()
    {
            load_plugin_textdomain( 'ull', false, basename(dirname(__FILE__)) . '/languages/' );
    }


    function cron()
    {
        UserLoginLog::truncate_stats();
    }


    function screen_options()
    {

        //execute only on login_stats page, othewise return null
        $page = ( isset($_GET['page']) ) ? esc_attr($_GET['page']) : false;
        if( 'login_log' != $page )
            return;

        $current_screen = get_current_screen();

        //define options
        $per_page_field = 'per_page';
        $per_page_option = $current_screen->id . '_' . $per_page_field;

        //Save options that were applied
        if( isset($_REQUEST['wp_screen_options']) && isset($_REQUEST['wp_screen_options']['value']) )
        {
            update_option( $per_page_option, esc_html($_REQUEST['wp_screen_options']['value']) );
        }

        //prepare options for display

        //if per page option is not set, use default
        $per_page_val = get_option($per_page_option, 20);
        $args = array('label' => __('Records', 'ull'), 'default' => $per_page_val );

        //display options
        add_screen_option($per_page_field, $args);
        $_per_page = get_option('users_page_login_log_per_page');

        //needs to be initialized early enough to pre-fill screen options section in the upper (hidden) area.
        $this->stats_table = new ull_List_Table;
    }


    function init_login_actions()
    {
        //condition to check if "stats failed attempts" option is selected

        //Action on successful login
        add_action( 'wp_login', array($this, 'login_success') );

        //Action on failed login
        if( isset($this->opt['failed_attempts']) ){
            add_action( 'wp_login_failed', array($this, 'login_failed') );
        }

    }


    function login_success( $user_login )
    {
        $this->login_success = 1;
        $this->login_action( $user_login );
    }


    function login_failed( $user_login )
    {
        $this->login_success = 0;
        $this->login_action( $user_login );
    }


    function init_scheduled_events()
    {

        $stats_duration = get_option('user_login_log');

        if ( $stats_duration && !wp_next_scheduled( 'truncate_ull' ) )
        {
            $start = time();
            wp_schedule_event($start, 'daily', 'truncate_ull');
        }elseif( !$stats_duration || 0 == $stats_duration)
        {
            $timestamp = wp_next_scheduled( 'truncate_ull' );
            (!$timestamp) ? false : wp_unschedule_event($timestamp, 'truncate_ull');

        }
    }


    function deactivation(){
        wp_clear_scheduled_hook('truncate_ull');

        //clean up old cron jobs that no longer exist
        wp_clear_scheduled_hook('truncate_stats');
        wp_clear_scheduled_hook('UserLoginLog::truncate_stats');
    }


    function truncate_stats()
    {
        global $wpdb;

        $opt = get_option('user_login_log');
        $stats_duration = (int)$opt['stats_duration'];

        if( 0 < $stats_duration ){
            $sql = $wpdb->prepare( "DELETE FROM {$this->table} WHERE time < DATE_SUB(CURDATE(),INTERVAL %d DAY)", $stats_duration);
            $wpdb->query($sql);
        }

    }


     function delete_all()
     {
         global $wpdb;

         $nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : false;

         if (!wp_verify_nonce($nonce, 'delete_ull'))
         {
             return;
         }
         else
         {
             $sql = "DELETE FROM {$this->table}";

             if ($wpdb->query($sql))
             {
                 $this->set('deleted', true);
             }
         }
     }



    /**
    * Runs via plugin activation hook & creates a database
    */
    function install()
    {
        global $wpdb;

     
            //if table does't exist, create a new one
            
                $sql = "CREATE TABLE  " . $this->table . "
                    (
                        id INT( 11 ) NOT NULL AUTO_INCREMENT ,
                        uid INT( 11 ) NOT NULL ,
                        user_login VARCHAR( 60 ) NOT NULL ,
                        user_role VARCHAR( 30 ) NOT NULL ,
						name VARCHAR( 100 ) NOT NULL ,
						user_email VARCHAR( 30 ) NOT NULL ,
                        time DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL ,
                        ip VARCHAR( 100 ) NOT NULL ,
						country varchar( 100 ) NOT NULL ,
						city varchar( 100 ) NOT NULL ,
                        login_result VARCHAR (1) ,
                        data LONGTEXT NOT NULL ,
                        PRIMARY KEY ( id ) ,
                        INDEX ( uid, ip, login_result )
                    );";
				    
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);

               
         


    }


   


    //Initializing Settings
    function settings_api_init()
    {
        add_settings_section('user_login_log', __('easy login log', 'ull'), array($this, 'ull_settings'), 'general');
        add_settings_field('field_stats_duration', __('Truncate Log Entries', 'ull'), array($this, 'field_stats_duration'), 'general', 'user_login_log');
        add_settings_field('field_stats_failed_attempts', __('Log Failed Attempts', 'ull'), array($this, 'field_stats_failed_attempts'), 'general', 'user_login_log');
        register_setting( 'general', 'user_login_log' );

    }


    function ull_admin_menu()
    {
        add_submenu_page( 'users.php', __('Easy login Log', 'ull'), __('Login Log', 'ull'), 'list_users', 'login_log', array($this, 'stats_manager') );
    }


    function ull_settings()
    {
        //content that goes before the fields output
    }


    function field_stats_duration()
    {
        $duration = (null !== $this->opt['stats_duration']) ? $this->opt['stats_duration'] : $this->stats_duration;
        $output = '<input type="text" value="' . $duration . '" name="user_login_log[stats_duration]" size="10" class="code" /> ' . __('days and older.', 'ull');
        echo $output;
        echo "<p>" . __("Leave empty or enter 0 if you don't want the stats to be truncated.", 'ull') . "</p>";

        //since we're on the General Settings page - update cron schedule if settings has been updated
        if( isset($_REQUEST['settings-updated']) ){
            wp_clear_scheduled_hook('truncate_ull');
            //$this->init_scheduled_events();
        }
    }


    function field_stats_failed_attempts()
    {
        $failed_attempts = ( isset($this->opt['failed_attempts']) ) ? $this->opt['failed_attempts'] : false;
        echo '<input type="checkbox" name="user_login_log[failed_attempts]" value="1" ' . checked( $failed_attempts, 1, false ) . ' /> ' . __('Log failed attempts where user name and password are entered. Will not Log if at least one of the mentioned fields is empty.', 'ull');
    }


    function admin_header()
    {
        $page = ( isset($_GET['page']) ) ? esc_attr($_GET['page']) : false;
        if( 'login_log' != $page )
            return;

        echo '<style type="text/css">';
        echo 'table.users { table-layout: auto; }';
        echo '</style>';
    }


    //Catch messages on successful login
    function login_action($user_login)
    {

        $userdata = get_user_by('login', $user_login);

        $uid = ($userdata && $userdata->ID) ? $userdata->ID : 0;

        $data[$this->data_labels['login']] = ( 1 == $this->login_success ) ? $this->data_labels['Successful'] : $this->data_labels['Failed'];
        if ( isset( $_REQUEST['redirect_to'] ) ) { $data[$this->data_labels['login Redirect']] = esc_attr( $_REQUEST['redirect_to'] ); }
        $data[$this->data_labels['User Agent']] = esc_attr( $_SERVER['HTTP_USER_AGENT'] );

        $serialized_data = serialize($data);

        //get user role
        $user_role = '';
        if( $uid ){
            $user = new WP_User( $uid );
            if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
                $user_role = implode(', ', $user->roles);
            }
        }
		$current_user = wp_get_current_user();
		$user_email = $user->user_email;
	

		$real_client_ip_address = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? esc_attr($_SERVER['HTTP_X_FORWARDED_FOR']) : esc_attr($_SERVER['REMOTE_ADDR']);
		

		$guest_ip   = $visitor_location['IP'];
		$guest_country = "";
		$guest_city  = "";
		$guest_state = "";
			$user_info = get_userdata($uid);
               $USErname =  $user_info->first_name .  " " . $user_info->last_name;
            

        $values = array(
            'uid'           => $uid,
            'user_login'    => $user_login,
            'user_role'     => $user_role,
			'user_email'	=> $user_email,
            'time'          => current_time('mysql'),
            'ip'            => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? esc_attr($_SERVER['HTTP_X_FORWARDED_FOR']) : esc_attr($_SERVER['REMOTE_ADDR']),
			'country'      => $guest_country,
			'city'			=> $guest_city,
            'login_result'  => $this->login_success,
            'data'          => $serialized_data,
			'name'			=> $USErname
            );

        $format = array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s','%s');

        $this->save_data($values, $format);
    }


    function save_data($values, $format)
    {
        global $wpdb;

        $wpdb->insert( $this->table, $values, $format );
    }


    function make_where_query()
    {
        $where = false;
        if( isset($_GET['filter']) && '' != $_GET['filter'] )
        {
            $filter = esc_attr( $_GET['filter'] );
            $where['filter'] = "(user_login LIKE '%{$filter}%' OR ip LIKE '%{$filter}%')";
        }
        if( isset($_GET['user_role']) && '' != $_GET['user_role'] )
        {
            $user_role = esc_attr( $_GET['user_role'] );
            $where['user_role'] = "user_role = '{$user_role}'";
        }
        if( isset($_GET['result']) && '' != $_GET['result'] )
        {
            $result = esc_attr( $_GET['result'] );
            $where['result'] = "login_result = '{$result}'";
        }
        if( isset($_GET['datefilter']) && '' != $_GET['datefilter'] )
        {
            $datefilter = esc_attr( $_GET['datefilter'] );
            $year = substr($datefilter, 0, 4);
            $month = substr($datefilter, -2);
            $where['datefilter'] = "YEAR(time) = {$year} AND MONTH(time) = {$month}";
        }

        return $where;
    }


    function getLimit()
    {
        return ' LIMIT ' . get_option('users_page_login_log_per_page', 20);
    }


    function stats_get_data($orderby = false, $order = false, $limit = 0, $offset = 0)
    {
        global $wpdb;

        $where = '';

        $where = $this->make_where_query();

        $orderby = (!isset($orderby) || $orderby == '') ? 'time' : $orderby;
        $order = (!isset($order) || $order == '') ? 'DESC' : $order;

        if( is_array($where) && !empty($where) )
            $where = ' WHERE ' . implode(' AND ', $where);

        $sql = "SELECT * FROM $this->table" . $where . " ORDER BY {$orderby} {$order} " . 'LIMIT ' . $limit . ' OFFSET ' . $offset;
        $data = $wpdb->get_results($sql, 'ARRAY_A');

        return $data;
    }


    function stats_manager()
    {
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo  plugins_url('css/els-style.css',__FILE__) ?>" />
		<?php
        $stats_table = $this->stats_table;

        $stats_table->prepare_items();
?>  <style>
			.fag-rate-us span.dashicons{
				width: 30px;
				height: 30px;
				line-height: 1.6 !important;
			}
			.fag-rate-us span.dashicons-star-filled:before {
				content: "\f155";
				font-size: 30px;
				
			}
		
		   .upgrade-to-pro-demo .dashicons, .upgrade-to-pro-demo .dashicons-before:before {

			line-height: 1.6;

			}
			
		   .upgrade-to-pro-demo .button-primary {
				background: #F8504B;
				border-color: #C4322E;
				-webkit-box-shadow: inset 0 1px 0 rgba(248,80,75,.5),0 1px 0 rgba(0,0,0,.15);
				box-shadow: inset 0 1px 0 rgba(248,80,75,.5),0 1px 0 rgba(0,0,0,.15);
			}
			.upgrade-to-pro-demo .button-primary:hover {
				background: rgba(248,80,75,.91);
				border-color: #C4322E;
				-webkit-box-shadow: inset 0 1px 0 rgba(248,80,75,.5),0 1px 0 rgba(0,0,0,.15);
				box-shadow: inset 0 1px 0 rgba(248,80,75,.5),0 1px 0 rgba(0,0,0,.15);
			}
		</style>
		<div align="right" style="margin-right:15px;">
			<p style="margin-right:10px;margin: 0em 0;">If you like our plugin then please show us some love</p> 
			<p style="margin-right:10px;margin: 0em 0;">Rate Us On WordPress</p>
			
		</div>
		<div  class="upgrade-to-pro-demo" style="text-align:center;margin-bottom:10px;margin-top:10px;">
			<a style="float:right;margin-right:15px;" href="http://wordpress.org/plugins/user-login-log/" target="_blank" class="button button-primary button-hero">RATE US <span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span></a>
			
		</div> <?php 
        echo '<div class="wrap srp">';
            echo '<h2>' . __('login Log', 'ull') . '</h2>';

            if ($this->get('deleted'))
            {
                echo '<div class="updated"><p>All records were deleted.</p></div>';
            }

            echo '<div class="tablenav top">';
                echo '<div class="alignleft actions">';
                    echo $this->date_filter();
                echo '</div>';

                $username = ( isset($_GET['filter']) ) ? esc_attr($_GET['filter']) : false;
                echo '<form method="get" class="alignright">';
                    echo '<p class="search-box">';
                        echo '<input type="hidden" name="page" value="login_log" />';
                        echo '<label>' . __('Username:', 'ull') . ' </label><input type="text" name="filter" class="filter-username" value="' . $username . '" /> <input class="button-primary" type="submit" value="' . __('Filter User', 'ull') . '" />';
                        echo '<br />';
                    echo '</p>';
                echo '</form>';
            echo '</div>';
            echo '<div class="tablenav top">';

                //if stats failed attempts is set in the settings, then output views filter
                if( isset($this->opt['failed_attempts']) ){
                    echo '<div class="alignleft actions">';
                            $stats_table->views();
                    echo '</div>';
                }

                echo '<div class="alignright actions">';
                $mode = ( isset($_GET['mode']) ) ? esc_attr($_GET['mode']) : false;
                $stats_table->view_switcher($mode);
                echo '</div>';
            echo '</div>';

            $stats_table->display();

            echo '<form method="get" id="export-login-stats">';
            if ( function_exists('wp_nonce_field') )
                wp_nonce_field('ssl_export_stats');

            echo '<input type="hidden" name="page" value="login_log" />';
            echo '<input type="hidden" name="download-login-stats" value="true" />';
            echo '<p class="submit">';
            echo '<input type="submit" name="submit" id="submit" class="button-primary" value="Export Log to CSV">';
            echo '&nbsp;&nbsp;<a  class="button" id="delete-all" href="' . wp_nonce_url('users.php?page=login_log&action=delete', 'delete_ull') . '" onclick="return confirm(\'IMPORTANT: All User stats records will be deleted.\')">Delete All</a>';
            echo '</p>';
            echo '</form>';
            //if filtered results - add export filtered results button
            $where = false;
            if( isset( $_GET['filter'] ) || isset( $_GET['user_role'] ) || isset( $_GET['datefilter'] ) || isset( $_GET['result'] ) )
            {
                $where = array();
                foreach($_GET as $k => $v)
                {
                    $where[$k] = @esc_attr($v);
                }
                echo '<form method="get" id="export-login-stats">';
                if ( function_exists('wp_nonce_field') )
                    wp_nonce_field('ssl_export_stats');

                echo '<input type="hidden" name="page" value="login_log" />';
                echo '<input type="hidden" name="download-login-stats" value="true" />';
                echo '<input type="hidden" name="where" value="' . esc_attr(serialize($where)) . '" />';
                submit_button( __('Export Current Results to CSV', 'ull'), 'secondary' );
                echo '</form>';

            }

        echo '</div>';
    }


    function date_filter()
    {
        global $wpdb;
        $sql = "SELECT DISTINCT YEAR(time) as year, MONTH(time)as month FROM {$this->table} ORDER BY YEAR(time), MONTH(time) desc";
        $results = $wpdb->get_results($sql);

        if(!$results)
            return;


        $option = '';
        foreach($results as $row)
        {
            //represent month in double digits
            $timestamp = mktime(0, 0, 0, $row->month, 1, $row->year);
            $month = (strlen($row->month) == 1) ? '0' . $row->month : $row->month;
            $datefilter = ( isset($_GET['datefilter']) ) ? $_GET['datefilter'] : false;
            $option .= '<option value="' . $row->year . $month . '" ' . selected($row->year . $month, $datefilter, false) . '>' . date('F', $timestamp) . ' ' . $row->year . '</option>';
        }

        $output = '<form method="get">';
        $output .= '<input type="hidden" name="page" value="login_log" />';
        $output .= '<select name="datefilter"><option value="">' . __('View All', 'ull') . '</option>' . $option . '</select>';
        $output .= '<input class="button" type="submit" value="' . __('Filter', 'ull') . '" />';
        $output .= '</form>';
        return $output;
    }


    function init_csv_export()
    {
        //Check if download was initiated

        $download = (isset($_GET['download-login-stats'])) ? esc_attr($_GET['download-login-stats']) : false;

        if($download)
        {
            check_admin_referer( 'ssl_export_stats' );

            $where = ( isset($_GET['where']) && '' != $_GET['where'] ) ? $_GET['where'] : false;
            $where = maybe_unserialize( stripcslashes($where) );

            if( is_array($where) && !empty($where) )
            {
                foreach($where as $k => $v)
                {
                    $_GET[$k] = esc_attr($v);
                }
            }

            $this->export_to_CSV( $this->make_where_query() );
        }
    }


    function export_to_CSV($where = false){
        global $wpdb;

        //if $where is set, then contemplate WHERE sql query
        if( $where ){

            if( is_array($where) && !empty($where) )
                $where = ' WHERE ' . implode(' AND ', $where);

        }

        $sql = "SELECT * FROM {$this->table}{$where}";
        $data = $wpdb->get_results($sql, 'ARRAY_A');

        if(!$data)
            return;

        //date string to suffix the file nanme: month - day - year - hour - minute
        $suffix = date('n-j-y_H-i');

        // send response headers to the browser
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment;filename=login_log_' . $suffix . '.csv');
        $fp = fopen('php://output', 'w');

        $i = 0;
        foreach($data as $row){
            $tmp = unserialize($row['data']);
            //output header row
            if(0 == $i)
            {
                fputcsv( $fp, array_keys($row) );
            }
            $row_data = (!empty($tmp)) ? array_map(create_function('$key, $value', 'return $key.": ".$value." | ";'), array_keys($tmp), array_values($tmp)) : array();
            $row['data'] = implode($row_data);
            fputcsv($fp, $row);
            $i++;
        }

        fclose($fp);
        die();
    }

 }

}

if( class_exists( 'UserLoginLog' ) )
{
    $ull = new UserLoginLog;
    //Register for activation
    register_activation_hook( __FILE__, array(&$ull, 'install') );

}

if(!class_exists('WP_List_Table'))
{
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class ull_List_Table extends WP_List_Table
{
    private $ullData;

    function __construct()
    {
        global $ull, $_wp_column_headers;

        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'user',     //singular name of the listed records
            'plural'    => 'users',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );

        $this->data_labels = $ull->data_labels;

    }


    function set($name, $value)
    {
        $this->ullData[$name] = $value;
    }


    function get($name)
    {
        return (isset($this->ullData[$name])) ? $this->ullData[$name] : false;
    }


    function column_default($item, $column_name)
    {
        $item = apply_filters('ull-output-data', $item);

        //unset existing filter and pagination
        $args = wp_parse_args( parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY) );
        unset($args['filter']);
        unset($args['paged']);

        switch($column_name){
            case 'id':
            case 'uid':
            case 'time':
			
			
			case 'data':
			return $item[$column_name];
			case 'image':
				$user = new WP_User( $item['uid'] );
				$user_email = $user->user_email;
				return get_avatar( $user_email, 60 );
			case 'user_email':
			return $item[$column_name];
			
            case 'ip':
                return $item[$column_name];
			
            case 'user_login':
                return "<a href='" . add_query_arg( array('filter' => $item[$column_name]), menu_page_url('login_log', false) ) . "' title='" . __('Filter stats by this name', 'ull') . "'>{$item[$column_name]}</a>";
            case 'name':
                $user_info = get_userdata($item['uid']);
                return ( is_object($user_info) ) ? $user_info->first_name .  " " . $user_info->last_name : false;
            case 'login_result':
                if ( '' == $item[$column_name]) return '';
                return ( '1' == $item[$column_name] ) ? __($this->data_labels['Successful'], 'ull') : '<div class="login-failed">' . __($this->data_labels['Failed'], 'ull') . '</div>';
            case 'user_role':
                if( !$item['uid'] )
                    return;

                $user = new WP_User( $item['uid'] );
                if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
                    foreach($user->roles as $role){
                        $roles[] = "<a href='" . add_query_arg( array('user_role' => $role), menu_page_url('login_log', false) ) . "' title='" . __('Filter stats by User Role', 'ull') . "'>{$role}</a>";
                    }
                    return implode(', ', $roles);
                }
                break;
           
            default:
                return $item[$column_name];
        }
    }


    function get_columns()
    {
        global $status;
        $columns = array(
            'id'            => __('#', 'ull'),
			'image'		=> __('Image','ull'),
            'uid'           => __('User ID', 'ull'),
            'user_login'    => __('Username', 'ull'),
            'user_role'     => __('User Role', 'ull'),
			'user_email'	=> __('User Email','ull'),
            'name'          => __('Name', 'ull'),
            'ip'            => __('IP Address', 'ull'),
            'time'          => __('Time', 'ull'),
            'login_result'  => __('login Result', 'ull'),
			'data'			=> __('Data','ull')
           
			
        );
        return $columns;
    }


    function get_sortable_columns()
    {
        $sortable_columns = array(
            //'id'    => array('id',true),     //doesn't sort correctly
            'uid'           => array('uid',false),
            'user_login'    => array('user_login', false),
            'time'          => array('time',true),
            'ip'            => array('ip', false),
        );
        return $sortable_columns;
    }


    function get_views()
    {
        //creating class="current" variables
        if( !isset($_GET['result']) ){
            $all = 'class="current"';
            $success = '';
            $failed = '';
        }else{
            $all = '';
            $success = ( '1' == $_GET['result'] ) ? 'class="current"' : '';
            $failed = ( '0' == $_GET['result'] ) ? 'class="current"' : '';
        }



        //if date filter is set, adjust views label to reflect the date
        $date_label = false;
        if( isset($_GET['datefilter']) && !empty($_GET['datefilter']) ){
            $year = substr($_GET['datefilter'], 0, 4);
            $month = substr($_GET['datefilter'], -2);
            $timestamp = mktime(0, 0, 0, $month, 1, $year);
            $date_label = date('F', $timestamp) . ' ' . $year . ' ';
        }

        //get args from the URL
        $args = wp_parse_args( parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY) );
        //the only arguments we can pass are mode and datefilter
        $param = false;
        if( isset($args['mode']) )
            $param['mode'] = $args['mode'];

        if( isset($args['datefilter']) )
            $param['datefilter'] = $args['datefilter'];

        //creating base url for the views links
        $menu_page_url = menu_page_url('login_log', false);
        ( is_array($param) && !empty($param) ) ? $url = add_query_arg( $param, $menu_page_url) : $url = $menu_page_url;

        //definition for views array
        $views = array(
            'all' => $date_label . __('login Results', 'ull') . ': <a ' . $all . ' href="' . $url . '">' . __('All', 'ull') . '</a>' . '(' .$this->get('allTotal') . ')',
            'success' => '<a ' . $success . ' href="' . $url . '&result=1">' . __('Successful', 'ull') . '</a> (' . $this->get('successTotal') . ')',
            'failed' => '<a ' . $failed . ' href="' . $url . '&result=0">' . __('Failed', 'ull') . '</a>' . '(' . $this->get('failedTotal') . ')',
        );

        return $views;
    }


    function prepare_items()
    {
        global $wpdb, $ull;
		$ull = new UserLoginLog();	
        //get number of successful and failed logins so we can display them in parentheces for each view

        //building a WHERE SQL query for each view
        $where = $ull->make_where_query();
        //we only need the date filter, everything else need to be unset
        if( is_array($where) && isset($where['datefilter']) ){
            $where = array( 'datefilter' =>  $where['datefilter'] );
        }else{
            $where = false;
        }

        $where3 = $where2 = $where1 = $where;
        $where2['login_result'] = "login_result = '1'";
        $where3['login_result'] = "login_result = '0'";

        if(is_array($where1) && !empty($where1)){
            $where1 = 'WHERE ' . implode(' AND ', $where1);
        }
        $where2 = 'WHERE ' . implode(' AND ', $where2);
        $where3 = 'WHERE ' . implode(' AND ', $where3);

        $sql1 = "SELECT count(*) FROM {$ull->table} {$where1}";
        $allTotal = $wpdb->get_var($sql1);
        $sql2 = "SELECT count(*) FROM {$ull->table} {$where2}";
        $successTotal = $wpdb->get_var($sql2);
        $sql3 = "SELECT count(*) FROM {$ull->table} {$where3}";
        $failedTotal = $wpdb->get_var($sql3);

        $this->set('allTotal', $allTotal);
        $this->set('successTotal', $successTotal);
        $this->set('failedTotal', $failedTotal);

        $screen = get_current_screen();

        /**
         * First, lets decide how many records per page to show
         */
        $per_page_option = $screen->id . '_per_page';
        $per_page = get_option($per_page_option, 20);
        $per_page = ($per_page != false) ? $per_page : 20;

        $offset = $per_page * ($this->get_pagenum() - 1);

        $orderby = (isset($_REQUEST['orderby']) && !empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : false;
        $order = (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) ? $_REQUEST['order'] : false;

        $this->items = $ull->stats_get_data($orderby, $order, $per_page, $offset);

        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden_cols = get_user_option( 'manage' . $screen->id . 'columnshidden' );
        $hidden = ( $hidden_cols ) ? $hidden_cols : array();
        $sortable = $this->get_sortable_columns();


        /**
         * REQUIRED. Finally, we build an array to be used by the class for column
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        $columns = get_column_headers( $screen );



        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently
         * looking at. We'll need this later, so you should always include it in
         * your own package classes.
         */
        $current_page = $this->get_pagenum();

        /**
         * REQUIRED for pagination. Let's check how many items are in our data array.
         * In real-world use, this would be the total number of items in your database,
         * without filtering. We'll need this later, so you should always include it
         * in your own package classes.
         */

        if (isset($_GET['result']) && $_GET['result'] == '1')
        {
            $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $ull->table {$where2}");
        }
        else if(isset($_GET['result']) && $_GET['result'] == '0')
        {
            $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $ull->table {$where3}");
        }
        else
        {
            $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $ull->table");
        }

        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );

    }

}