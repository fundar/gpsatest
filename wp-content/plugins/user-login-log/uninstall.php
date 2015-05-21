<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit ();

global $wpdb;
//Delete table
$sql = "DROP TABLE {$wpdb->prefix}user_login_log";
$wpdb->query($sql);

//Delete options
delete_option('ull_db_ver');
delete_option('user_login_log');

//remove cron jobs
wp_clear_scheduled_hook('truncate_sll');