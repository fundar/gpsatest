	
	<h2 id="logooos_page_title">Do you want to restore old data?</h2>
	
	<?php
		
	// myclients posts
	$args =	array ( 'post_type' => 'myclients', 'posts_per_page' => -1, 'post_status' => 'any');
	$clients_query = new WP_Query( $args );
		
	if($_POST["submitButton"])
	{
		if($_POST["restoreDataAcceptCheckbox"]=='accept') {
			
			if($_POST["restoreDataRadio"]=='yes') {
				
				// clientscategory
				
				global $wpdb;
				
				$wpdb->query( "UPDATE $wpdb->term_taxonomy SET taxonomy = 'logooocategory' WHERE taxonomy = 'clientscategory' ");
				
				if ($clients_query->have_posts()) {
					
					$i = 0;
					
					while ($i < $clients_query->post_count) {
					
						$post = $clients_query->posts;
						
						$client = array('ID' => $post[$i]->ID, 'post_type' => 'logooo');
						wp_update_post( $client );
						
						update_post_meta($post[$i]->ID, 'link', get_post_meta($post[$i]->ID, 'husamrayan_clientWebsite', true));
						update_post_meta($post[$i]->ID, 'link_target', get_post_meta($post[$i]->ID, 'client_link_target', true));
						update_post_meta($post[$i]->ID, 'imageSize', get_post_meta($post[$i]->ID, 'mc_imageSize', true));
						
						$i++;
					}
					
				}
				
				update_option('logooos_data_restored', stripslashes('yes'));
				
				echo '<div class="successMessage">Data was restored successfuly.</div>';
				
			}
			else if($_POST["restoreDataRadio"]=='no') {
				update_option('logooos_data_restored', stripslashes('no'));
				
				echo '<div class="successMessage">Restore data  was ignored.</div>';
			}
				
		}
		else {
			echo '<div class="failMessage">Please select "I Agree" before submit.</div>';
		}
	}

	
	
		
	if($clients_query->post_count > 0 && get_option('logooos_data_restored')=='' ) {
		?>
		<form name="restoreDataForm" id="restoreDataForm" action="" method="post">
		
			<input type="radio" name="restoreDataRadio" id="restoreDataRadio" value="yes" <?php if($_POST["restoreDataRadio"]!='no'){ echo 'checked';} ?> > <b>Yes</b> <br>
			
			<p>Choose this option to restore old data and remove "Restore Old Data" link from the left sidebar.</p>
			
			<input type="radio" name="restoreDataRadio" id="rrestoreDataRadio" value="no" <?php if($_POST["restoreDataRadio"]=='no'){ echo 'checked';} ?> > <b>No</b> <br>
			
			<p>Choose this option to ignore restore old data and remove "Restore Old Data" link from the left sidebar.</p> <br>
			
			<input type="checkbox" name="restoreDataAcceptCheckbox" id="restoreDataAcceptCheckbox" value="accept"> I Agree. <br><br>
			
			<input type="submit" name="submitButton" id="submitButton" value="Submit" class="button-primary" />
			
		</form>
		<?php
	}
	
	?>