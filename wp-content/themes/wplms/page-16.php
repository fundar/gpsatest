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
    <!-- inicio documents -->
</section>
<section id="content">
	<div class="container">
		<div class="col-md-9 col-sm-8">
			<div class="content">
				<?php
                    if ( have_posts() ) : while ( have_posts() ) : the_post();

                    $categories = array(101);
                    $cats='<ul>';
                    if($categories){
                        foreach($categories as $category) {
                            $cats .= '<li><a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a></li>';
                        }
                    }
                    $cats .='</ul>';
                        
                       echo ' <div class="blogpost">
                            <div class="meta">
                               <div class="date">
                                <p class="day"><span>'.get_the_time('j').'</span></p>
                                <p class="month">'.get_the_time('M').'</p>
                               </div>
                            </div>
                            '.(has_post_thumbnail(get_the_ID())?'
                            <div class="featured">
                                <a href="'.get_post_permalink().'">'.get_the_post_thumbnail(get_the_ID(),'full').'</a>
                            </div>':'').'
                            <div class="excerpt '.(has_post_thumbnail(get_the_ID())?'thumb':'').'">
                                <h3><a href="'.get_post_permalink().'">'.get_the_title().'</a></h3>
                                <div class="cats">
                                    '.$cats.'
                                    <p>| 
                                    <a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.get_the_author_meta( 'display_name' ).'</a>
                                    </p>
                                </div>
                                <p>'.get_the_excerpt().'</p>
                                <a href="'.get_permalink().'" class="link">Read More</a>
                            </div>
                        </div>';
                    endwhile;
                    endif;
                    pagination();
                ?>
			</div>
		</div>
		<div class="col-md-3 col-sm-4">
			<div class="sidebar">
				<?php 
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar('mainsidebar') ) : ?>
                <?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php
endwhile;
endif;
?>
</div>

<?php
get_footer();
?>