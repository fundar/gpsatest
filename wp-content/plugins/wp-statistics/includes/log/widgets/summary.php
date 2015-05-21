<?php
	function wp_statistics_generate_summary_postbox($ISOCountryCode, $search_engines) {
	
		global $wpdb, $WP_Statistics;
?>		
				<div class="postbox">
					<div class="handlediv" title="<?php _e('Click to toggle', 'wp_statistics'); ?>"><br /></div>
					<h3 class="hndle"><span><?php _e('Summary', 'wp_statistics'); ?></span></h3>
					<div class="inside">
					<?php wp_statistics_generate_summary_postbox_content($search_engines); ?>
					</div>
				</div>
<?php
	}

	function wp_statistics_generate_summary_postbox_content($search_engines, $search = true, $time = true) {
	
		global $wpdb, $WP_Statistics;
		
		$show_visitors = $WP_Statistics->get_option('visitor');
?>		
						<table width="100%" class="widefat table-stats" id="summary-stats">
							<tbody>
<?php if( $WP_Statistics->get_option('useronline') ) {?>							
								<tr>
									<th><?php _e('User(s) Online', 'wp_statistics'); ?>:</th>
									<th colspan="2" id="th-colspan">
										<span><a href="admin.php?page=wps_online_menu"><?php echo wp_statistics_useronline(); ?><a></span> 
									</th>
								</tr>
<?php }

if( $WP_Statistics->get_option('visitors') || $WP_Statistics->get_option('visits') ) {
?>								
								<tr>
									<th width="60%"></th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visitors') ) { _e('Visitor', 'wp_statistics'); } else { echo ''; }?></th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visits') ) { _e('Visit', 'wp_statistics'); } else { echo ''; }?></th>
								</tr>
								
								<tr>
									<th><?php _e('Today', 'wp_statistics'); ?>:</th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visitors') ) { echo '<a href="admin.php?page=wps_visitors_menu&hitdays=1"><span>' . number_format_i18n(wp_statistics_visitor('today',null,true)) . '</span></a>'; } else { echo ''; }?></th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visits') ) { echo '<a href="admin.php?page=wps_hits_menu&hitdays=1"><span>' . number_format_i18n(wp_statistics_visit('today')) . '</span></a>'; } else { echo ''; }?></th>
								</tr>
								
								<tr>
									<th><?php _e('Yesterday', 'wp_statistics'); ?>:</th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visitors') ) { echo '<a href="admin.php?page=wps_visitors_menu&hitdays=1"><span>' . number_format_i18n(wp_statistics_visitor('yesterday',null,true)) . '</span></a>'; } else { echo ''; }?></th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visits') ) { echo '<a href="admin.php?page=wps_hits_menu&hitdays=1"><span>' . number_format_i18n(wp_statistics_visit('yesterday')) . '</span></a>'; } else { echo ''; }?></th>
								</tr>
								
								<tr>
									<th><?php _e('Last 7 Days (Week)', 'wp_statistics'); ?>:</th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visitors') ) { echo '<a href="admin.php?page=wps_visitors_menu&hitdays=7"><span>' . number_format_i18n(wp_statistics_visitor('week',null,true)) . '</span></a>'; } else { echo ''; }?></th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visits') ) { echo '<a href="admin.php?page=wps_hits_menu&hitdays=7"><span>' .  number_format_i18n(wp_statistics_visit('week')) . '</span></a>'; } else { echo ''; }?></th>
								</tr>
								
								<tr>
									<th><?php _e('Last 30 Days (Month)', 'wp_statistics'); ?>:</th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visitors') ) { echo '<a href="admin.php?page=wps_visitors_menu&hitdays=30"><span>' . number_format_i18n(wp_statistics_visitor('month',null,true)) . '</span></a>'; } else { echo ''; }?></th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visits') ) { echo '<a href="admin.php?page=wps_hits_menu&hitdays=30"><span>' . number_format_i18n(wp_statistics_visit('month')) . '</span></a>'; } else { echo ''; }?></th>
								</tr>
								
								<tr>
									<th><?php _e('Last 365 Days (Year)', 'wp_statistics'); ?>:</th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visitors') ) { echo '<a href="admin.php?page=wps_visitors_menu&hitdays=365"><span>' . number_format_i18n(wp_statistics_visitor('year',null,true)) . '</span></a>'; } else { echo ''; }?></th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visits') ) { echo '<a href="admin.php?page=wps_hits_menu&hitdays=365"><span>' . number_format_i18n(wp_statistics_visit('year')) . '</span></a>'; } else { echo ''; }?></th>
								</tr>
								
								<tr>
									<th><?php _e('Total', 'wp_statistics'); ?>:</th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visitors') ) { echo '<a href="admin.php?page=wps_visitors_menu&hitdays=365"><span>' . number_format_i18n(wp_statistics_visitor('total',null,true)) . '</span></a>'; } else { echo ''; }?></th>
									<th class="th-center"><?php if( $WP_Statistics->get_option('visits') ) { echo '<a href="admin.php?page=wps_hits_menu&hitdays=365"><span>' . number_format_i18n(wp_statistics_visit('total')) . '</span></a>'; } else { echo ''; }?></th>
								</tr>
								
<?php 
	}
	
if( $search == true && $WP_Statistics->get_option('visitors' )) {
	
		if( $WP_Statistics->get_option('visitors') || $WP_Statistics->get_option('visits') || $WP_Statistics->get_option('useronline') ) {
?>
								<tr>
									<th colspan="3"><br><hr></th>
								</tr>
<?php		}?>
								<tr>
									<th colspan="3" style="text-align: center;"><?php _e('Search Engine Referrals', 'wp_statistics'); ?></th>
								</tr>
								
								<tr>
									<th width="60%"></th>
									<th class="th-center"><?php _e('Today', 'wp_statistics'); ?></th>
									<th class="th-center"><?php _e('Yesterday', 'wp_statistics'); ?></th>
								</tr>
								
								<?php
								$se_today_total = 0;
								$se_yesterday_total = 0;
								foreach( $search_engines as $se ) {
								?>
								<tr>
									<th><img src='<?php echo plugins_url('wp-statistics/assets/images/' . $se['image'] );?>'> <?php _e($se['name'], 'wp_statistics'); ?>:</th>
									<th class="th-center"><span><?php $se_temp = wp_statistics_searchengine($se['tag'], 'today'); $se_today_total += $se_temp; echo number_format_i18n($se_temp);?></span></th>
									<th class="th-center"><span><?php $se_temp = wp_statistics_searchengine($se['tag'], 'yesterday'); $se_yesterday_total += $se_temp; echo number_format_i18n($se_temp);?></span></th>
								</tr>
								
								<?php
								}
								?>
								<tr>
									<th><?php _e('Daily Total', 'wp_statistics'); ?>:</th>
									<td id="th-colspan" class="th-center"><span><?php echo number_format_i18n($se_today_total); ?></span></td>
									<td id="th-colspan" class="th-center"><span><?php echo number_format_i18n($se_yesterday_total); ?></span></td>
								</tr>

								<tr>
									<th><?php _e('Total', 'wp_statistics'); ?>:</th>
									<th colspan="2" id="th-colspan"><span><?php echo number_format_i18n(wp_statistics_searchengine('all')); ?></span></th>
								</tr>
<?php 
	}

if( $time == true ) {
?>
								<tr>
									<th colspan="3"><br><hr></th>
								</tr>

								<tr>
									<th colspan="3" style="text-align: center;"><?php _e('Current Time and Date', 'wp_statistics'); ?> <span id="time_zone"><a href="<?php echo admin_url('options-general.php'); ?>"><?php _e('(Adjustment)', 'wp_statistics'); ?></a></span></th>
								</tr>

								<tr>
									<th colspan="3"><?php echo sprintf(__('Date: %s', 'wp_statistics'), '<code dir="ltr">' . $WP_Statistics->Current_Date_i18n(get_option('date_format')) . '</code>'); ?></th>
								</tr>

								<tr>
									<th colspan="3"><?php echo sprintf(__('Time: %s', 'wp_statistics'), '<code dir="ltr">' .$WP_Statistics->Current_Date_i18n(get_option('time_format')) . '</code>'); ?></th>
								</tr>
<?php }?>
							</tbody>
						</table>
<?php
	}

