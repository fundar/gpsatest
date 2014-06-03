<?php
get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
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
                    if(isset($breadcrumbs) && $breadcrumbs !='' && $breadcrumbs !='H'){
                        vibe_breadcrumbs();
                    }    
                    
                    $data["expert"] = get_post_meta($post->ID, 'expert', true);
                ?>
            </div>
        </div>
    </div>
</section>

<section id="content">
    <div class="container">
        
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="content">
						<div class="expert-label">Expert: <?php echo $data["expert"];?></div><br/><br/>
					    <?php the_content(); ?>
					</div>
				</div>
            </div>
            
            <div class="col-md-3 col-sm-3">
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
</div>

<?php

endwhile;
endif;

get_footer();
