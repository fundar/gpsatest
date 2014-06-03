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
<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                       <div class="content" style="padding: 0px ! important; margin-top: 48px ! important;">
                       <!--webinars and blog --><div class="one_half clearfix">
                                                        <div class="column_content first">
                                                                    <!-- webinars -->
                                                                    <div class="block_home">
                                                                    <?php $service_query = new WP_Query('page_id=2055');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h4>  
                                                                               <img class="th_home"  <?php echo get_the_post_thumbnail(); ?>                                                                                  
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  
                                                                                <a class="more" href="<?php the_permalink(); ?>"><span>Read more</span></a>                                                                                      

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>
                                                                    <!-- webinars -->
                                                                    <div class="block_home" style="margin-top: 50px;">
                                                                    <?php $service_query = new WP_Query('page_id=2055');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h4>  
                                                                               <img class="th_home"  <?php echo get_the_post_thumbnail(); ?>                                                                                  
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  
                                                                                <a class="more" href="<?php the_permalink(); ?>"><span>Read more</span></a>                                                                                      

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>                                                         
                                                        </div>
                                     
                                                     </div>                                                                                                                   
              <!--forums and toster and blog --><div class="one_half ">
                                                                  <!-- forums -->
                                                                    <div class="block_home">
                                                                    <?php $service_query = new WP_Query('page_id=2055');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h4>  
                                                                               <img class="th_home"  <?php echo get_the_post_thumbnail(); ?>                                                                                  
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  
                                                                                <a class="more" href="<?php the_permalink(); ?>"><span>Read more</span></a>                                                                                      

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>
                                                                    <div class="block_home" style="margin-top: 50px;">
                                                                    <?php $service_query = new WP_Query('page_id=2055');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h4>  
                                                                               <img class="th_home"  <?php echo get_the_post_thumbnail(); ?>                                                                                  
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  
                                                                                <a class="more" href="<?php the_permalink(); ?>"><span>Read more</span></a>                                                                                      

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>                
                                                </div>
                                                <?php
                                                endwhile;
                                                endif;
                                                 ?>  
                        </div>                                
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="sidebar">
                    <?php
                    $sidebar=getPostMeta($post->ID,'vibe_sidebar');
                    ((isset($sidebar) && $sidebar)?$sidebar:$sidebar='mainsidebar');
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
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