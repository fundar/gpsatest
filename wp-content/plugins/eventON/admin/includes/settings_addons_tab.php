<?php
	// EventON Settings tab - Addons and licenses
	// version: 0.2
?>
<div id="evcal_4" class="postbox evcal_admin_meta">	
	
	<?php
		/*			
			LICENSES Section			
		*/	
	?>
	<div class='licenses_list' id='eventon_licenses'>
		
		
		<?php
			$admin_url = admin_url();
			$show_license_msg = true;

			// REMOVE license
			if(isset($_GET['lic']) && $_GET['lic']=='remove')
				delete_option('_evo_licenses');

			$evo_licenses = get_option('_evo_licenses');
			
			
			
			// running for the first time
			if(empty($evo_licenses)){
				
				$lice = array(
					'eventon'=>array(
						'name'=>'EventON',
						'current_version'=>$eventon->version,
						'type'=>'plugin',
						'status'=>'inactive',
						'key'=>'',
					));
				update_option('_evo_licenses', $lice);
				
				$evo_licenses = get_option('_evo_licenses');				
			}
			
			// render existing licenses
			if(!empty($evo_licenses) && count($evo_licenses)>0){
				foreach($evo_licenses as $slug=>$evl){
					
					// new version text
					$new_update_text = (!empty($evl['has_new_update']) && $evl['has_new_update'])?
						"<span class='version remote' title='There is a newer version of eventON available now!'>".$evl['remote_version']."<em>Latest version</em></span>":null;
					
					// if activated already
					if($evl['status']=='active'){
						
						echo "<h2 class='heading'>myEventON <span>License</span> <em>activated</em></h2>";
						
						$new_update_details_btn = (!empty($evl['has_new_update']) && $evl['has_new_update'])?
							"<a class='evo_admin_btn btn_prime' href='".$admin_url."update-core.php'>Update Now</a>  <a class='evo_admin_btn btn_prime thickbox' href='".BACKEND_URL."plugin-install.php?tab=plugin-information&plugin=eventon&section=changelog&TB_iframe=true&width=600&height=400'>Version Details</a> ":null;
						
						echo "
						<p class='versions'>
							<span class='version'>{$evl['current_version']}<em>Your version</em></span>".$new_update_text."	
						</p>
						<p class='clear padb10'></p>
							
						<p>".$new_update_details_btn." <a href='". $admin_url."admin.php?page=eventon&tab=evcal_4&lic=remove' class='evo_admin_btn btn_noBG'>Remove License</a></p>";
						
						$show_license_msg = false;
					
					// NOT Activated yet
					}else{
						
						echo "<h2 class='heading'>myEventON <span>License</span> <em>not activated</em></h2>";
													
						echo "
						<p class='versions'>
							<span class='version'>{$evl['current_version']}<em>Your version</em></span>".$new_update_text."	
						</p>
						<p class='clear padb10'></p>
						
						<p><a class='eventon_popup_trig evo_admin_btn btn_prime' dynamic_c='1' content_id='eventon_pop_content_001' poptitle='Activate EventON License'>Activate Now</a></p>						
						
						<div id='eventon_pop_content_001' class='evo_hide_this'>
							<p>License Key <span class='evoGuideCall'>?<em>Read: <a href='http://www.myeventon.com/documentation/how-to-find-eventon-license-key/' target='_blank'>How to find eventON license key</a></em></span><br/>
							<input class='eventon_license_key_val' type='text' style='width:100%'/>
							<input class='eventon_slug' type='hidden' value='{$slug}' /></p>
							<input class='eventon_license_div' type='hidden' value='license_{$evl['name']}' /></p>
							<p><a class='eventon_submit_license evo_admin_btn btn_prime'>Activate Now</a></p>
						</div>";
						
					}
				}
			}
		?>
		
	
		<div class='clear'></div>
		
		<?php if($show_license_msg):?>
		<p><?php _e('Activate your copy of EventON to get free automatic plugin updates direct to your site!'); ?></p>
		<?php endif;?>
	</div>
	
	<div class="inside eventon_addons">
		
	<?php		
		
		$evo_installed_addons ='';
		$count =1;
		$eventon_addons_opt = get_option('eventon_addons');
		
		global $wp_version; 
		
		echo "<h2 class='heading'><a href='http://www.myeventon.com/addons/' target='_blank'>myEventON Addons</a></h2>";
		
		$url = 'http://update.myeventon.com/addons.php';
		$response = wp_remote_post(
            $url,
            array(
                'body' => array(
                    'action'     => 'evo_get_addons',
                    'api-key' => md5(get_bloginfo('url'))
                ),
                'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
            )
        );

        if ( !is_wp_error( $response ) ) {

        	//print_r($response['body']);

        	$request = unserialize($response['body']);

        	if(!empty($request)){
				
				// installed addons
				if(!empty($eventon_addons_opt) and count($eventon_addons_opt)>0 ){
					foreach($eventon_addons_opt as $tt=>$yy){
						$evo_installed_addons[]=$tt;
					}
				}else{	$evo_installed_addons=false;	}
				
				
				echo "<div class='evo_addons_list'>";
				
				// EACH ADDON
				foreach($request as $slug=>$addons){
					// Icon Image for the addon
					$img = ($addons['iconty'] == 'local')? AJDE_EVCAL_URL.'/'.$addons['icon']: $addons['icon'];
					
					
					// Check if addon is installed in the website
					$_has_addon = ($evo_installed_addons && in_array($slug, $evo_installed_addons))?true:false;
					if($_has_addon){
						$_addon_options_array = $eventon_addons_opt[(string)$slug];				
					}
					
					
					$guide = ($_has_addon && !empty($_addon_options_array['guide_file']) )? "<span class='evo_admin_btn btn_prime eventon_guide_btn eventon_popup_trig' ajax_url='{$_addon_options_array['guide_file']}' poptitle='How to use {$addons['name']}'>Guide</span>":null;
					
					$_this_version = ($_has_addon)? "<span class='evoa_ver' title='My Version'>".$_addon_options_array['version']."</span>": null;
					
					$_hasthis_btn = ($_has_addon)? "  <span class='evo_admin_btn btn_triad'>You have this</span>":null;
					
					?>
					<div class='evoaddon_box'>
						<div class='evoa_boxe'>
						<div class='evoaddon_box_in'>	
							<div class='evoa_content'>
								<h5 style='background-image:url(<?php echo $img;?>)'><?php echo $addons['name'].' '.$_this_version;?></h5>
								<p><?php echo $addons['desc'];?></p>						
							</div>
							<div class='clear'></div>
							<a class='evo_admin_btn btn_prime' target='_blank' href='<?php echo $addons['link'];?>'>Learn more</a>  <?php echo $guide;?><?php echo $_hasthis_btn;?>
							<?php if(!$_has_addon):?> <a class='evo_admin_btn btn_secondary' target='_blank' href='<?php echo $addons['download'];?>'>Download</a><?php endif;?>
						</div>
						</div>
					</div>
					<?php			
						echo ($count%2==0)?"<div class='clear'></div>":null;
					$count++;
				}
				
				echo "<div class='clear'></div></div>";

			}

        }


	?>
		
	</div>
	
	
	
	
	
	<?php
		// Throw the output popup box html into this page
		echo $eventon->output_eventon_pop_window(array('content'=>'Loading...', 'type'=>'padded'));
	?>
</div>