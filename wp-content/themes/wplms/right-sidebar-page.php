<?php
/**
 * Template Name: Right Sidebar Page
 */

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
?>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="content">
                    <?php
                        the_content();
                     ?>
                </div>
                <?php
                
                endwhile;
                endif;
                ?>
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
</div>

<?php
get_footer();
?>