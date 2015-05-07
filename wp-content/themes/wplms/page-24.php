<?php
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();

$title=get_post_meta(get_the_ID(),'vibe_title',true);
if(isset($title) && $title !='' && $title !='H'){
?>

<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php the_title(); ?></h1>
                    <h5><?php the_sub_title(); ?></h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php
                    $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                    if(isset($breadcrumbs) && $breadcrumbs !='' && $breadcrumbs !='H')
                        vibe_breadcrumbs(); 
                ?>
            </div>
        </div>
    </div>
</section>
<?php
}

    $v_add_content = get_post_meta( $post->ID, '_add_content', true );
 
?>
<?php 
removeimages(); 

add_filter( 'bp_activity_excerpt_length', 'cc_custom_excerpt_length' );
add_filter('wp_trim_excerpt', 'new_excerpt_more');        

?>

<section id="content"> 
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="<?php echo $v_add_content;?> content">
                    <?php
                        the_content();
                     ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- second part home -->
<section id="content" style="background: none repeat scroll 0% 0% rgb(255, 255, 255);">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                       <div class="content" style="padding: 0px ! important; margin-top: 48px ! important;">
					   <!--Inicio Networking -->
																	<div class="block_home1" style="margin-top: 50px;">
																	 <h4 class="bloque_title"><a class="" href="http://gpsaknowledge.org/networking/">Networking Board</a> </h4>  
																			
																			

																		<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) . '&action=activity_update' .'&max=4') ) : ?>
																			
																			<?php while ( bp_activities() ) : bp_the_activity(); ?>
																				<?php locate_template( array( 'activity/entry2.php' ), true, false ); ?>
																			<?php endwhile; ?>
																		<?php endif; ?>
																				
																		
																		
																		
                                                                    </div>       
						
						
														<!-- Fin de Networking -->
					   
                       <!--webinars and blog --><div class="one_half clearfix">
                                                        <div class="column_content first">
                                                                    <!-- webinars -->
                                                                    <div class="block_home">
                                                                    <?php $service_query = new WP_Query('page_id=171');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="/event-type/webinars/"><?php the_title(); ?></a> </h4>  
                                                                               <a href="/event-type/webinars/"><img class="th_home"  <?php echo get_the_post_thumbnail(); ?></a>                                                                                  
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  
                                                                                <a class="more" href="/event-type/webinars/"><span>Read more</span></a>                                                                                      

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>
                                                                    <!-- blog -->
                                                                    <div class="block_home" style="margin-top: 50px;">
                                                                    <?php $service_query = new WP_Query('page_id=178');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="http://gpsaknowledge.org/category/blog/"><?php the_title(); ?></a> </h4>  
                                                                               <a href="http://gpsaknowledge.org/category/blog/"><img class="th_home"  <?php echo get_the_post_thumbnail(); ?></a>                                                                                 
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  

                                                                                <a class="more" href="http://gpsaknowledge.org/category/blog/"><span>Read more</span></a>

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>                                                         
                                                        </div>
                                                        <?php
                                                        endwhile;
                                                        endif;
                                                        ?>                                       
                                                     </div>        
														
																
              <!--forums and toster and blog --><div class="one_half ">
			  
															  
			  
                                                                  <!-- forums -->
                                                                    <div class="block_home">
                                                                    <?php $service_query = new WP_Query('page_id=182');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="http://gpsaknowledge.org/forums/"><?php the_title(); ?></a> </h4>  
                                                                               <a href="http://gpsaknowledge.org/forums/"><img class="th_home"  <?php echo get_the_post_thumbnail(); ?></a>                                                                                  
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  
                                                                                <a class="more" href="http://gpsaknowledge.org/forums/"><span>Read more</span></a>

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>
                                                                     <!-- roster -->
                                                                    <div class="block_home" style="margin-top: 50px;">
                                                                    <?php $service_query = new WP_Query('page_id=180');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="http://gpsaknowledge.org/networking/"><?php the_title(); ?></a> </h4>  
                                                                               <a href="http://gpsaknowledge.org/networking/"><img class="th_home"  <?php echo get_the_post_thumbnail(); ?></a>                                                                                
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  
                                                                                <a class="more" href="http://gpsaknowledge.org/networking/"><span>Read more</span></a>

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>                
                                                </div>
                        </div><!--fin 4 entradas: blog, webinar, forum, roster-->
                      
						
					  <!--Inicio carrusel -->
                        
                                <?php $service_query = new WP_Query('page_id=813');
                                while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                               <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                     <h2 class="logos_title "><?php the_title(); ?></h4>                                                                  				
                                     <div><?php the_content(); ?></div><!-- end .post_content -->                                                                                                                                                         
                                </article> <!-- end .entry -->
                                <?php endwhile; // end of the loop. ?>
                       
                        <!-- fin carrusel logos ongs -->  
            </div><!--fin colummna derecha -->
            <div class="col-md-3 col-sm-4">
			<div class="sidebar">
				<?php 
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar('homesidebar') ) : ?>
                <?php endif; ?>
			</div>
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
