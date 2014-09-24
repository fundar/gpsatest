<?php
/**
 * Template Name: All Instructors
 */
get_header();


$no=999;
$args = array(
                'role' => 'instructor', // instructor
    			'number' => $no, 
                'orderby' => 'post_count', 
                'order' => 'DESC' 
    		);
$user_query = new WP_User_Query( $args );

$args = array(
                'role' => 'administrator', // instructor
                'number' => $no, 
                'orderby' => 'post_count', 
                'order' => 'DESC' 
            );
$flag = apply_filters('wplms_show_admin_in_instructors',1);
if(isset($flag))
    $admin_query = new WP_User_Query( $args );

$ifield = vibe_get_option('instructor_field');
if(!isset($ifield) || $ifield =='')$ifield='Expertise';

$title=get_post_meta(get_the_ID(),'vibe_title',true);
if(vibe_validate($title)){
?>
<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php the_title(); ?></h1>
                    <?php the_sub_title(); ?>
                </div>
            </div>
             <div class="col-md-3 col-sm-4">
            	<?php
                    $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                    if(vibe_validate($breadcrumbs)){
                     vibe_breadcrumbs();
                    }
                ?>
            </div>
        </div>
    </div>
</section>
<?php
}

?>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="content padding_adjusted">
                <?php
                   
					if ( isset($admin_query) && !empty( $admin_query->results ) ) {
                        foreach ( $admin_query->results as $user ) {
                             ?>
                                <div class="col-md-4 col-sm-4 clear3">
                                    <div class="instructor">
                                        <?php echo bp_core_fetch_avatar( array( 'item_id' => $user->ID,'type'=>'full', 'html' => true ) ); ?>
                                        <span><?php 
                                        if(bp_is_active('xprofile'))
                                        echo bp_get_profile_field_data( 'field='.$ifield.'&user_id=' .$user->ID ); 
                                        ?></span>
                                        <h3><?php echo bp_core_get_userlink( $user->ID ); ?></h3>
                                        <strong><a href="<?php echo get_author_posts_url( $user->ID ); ?>"><?php _e('Courses by Instructor ','vibe'); echo  '<span>'.count_user_posts_by_type($user->ID,'course').'</span></a>'; ?></strong>
                                    </div>
                                    <?php
                                        
                                    ?>
                                </div>

                             <?php
                        }
                    }

				    if ( !empty( $user_query->results ) ) {
				        foreach ( $user_query->results as $user ) {
				             ?>
				             	<div class="col-md-4 col-sm-4 clear3">
				             		<div class="instructor">
										<?php echo bp_core_fetch_avatar( array( 'item_id' => $user->ID,'type'=>'full', 'html' => true ) ); ?>
										<span><?php 
                                        if(bp_is_active('xprofile'))
                                        echo bp_get_profile_field_data( 'field='.$ifield.'&user_id=' .$user->ID ); 
                                        ?></span>
										<h3><?php echo bp_core_get_userlink( $user->ID ); ?></h3>
										<strong><a href="<?php echo get_author_posts_url( $user->ID ); ?>"><?php _e('Courses by Instructor ','vibe'); echo  '<span>'.count_user_posts_by_type($user->ID,'course').'</span></a>'; ?></strong>
									</div>
				             		<?php
				             			
				             		?>
				             	</div>

				             <?php
				        }
				    }else {
					 echo '<div id="message"><p>'.__('No Instructors found.','vibe').'</p></div>';
					}
                 ?>
            </div>
        </div>
    </div>
</section>
<?php

?>
</div>
<?php
get_footer();

?>