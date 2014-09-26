<?php
get_header();
?>
<section id="title">
	<div class="container">
		<div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php single_cat_title(); ?></h1>
                    <h5><?php echo category_description(); ?></h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php vibe_breadcrumbs(); ?> 
            </div>
        </div>
	</div>
</section>
<!-- Carrusel -->
<section class="stripe top">
	<div class="container">
		<div class="row carruselcat7">	
	               <?php echo get_touchcarousel(2); ?>
		</div>
	</div>
</section>
<section class="stripe sort-serach">
	<div class="container">
		<div class="row">	
			<?php 
				if(function_exists('sbc')) {
					sbc();
				}
			?>    
		</div>
	</div>
</section>
<section id="content">	
	<div class="container">
		<h2 class="subt">All Materials</h2>
		<div class="row">
			<div class="content">
				<?php
                    if ( have_posts() ) : while ( have_posts() ) : the_post();

                    $categories = get_the_category();
                    $cats='<ul>';
                    if($categories){
                        foreach($categories as $category) {
                            $cats .= '<li><a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a></li>';
                        }
                    }
                    $cats .='</ul>';
                        
                       echo ' <div class="blogpost">
                            '.(has_post_thumbnail(get_the_ID())?'
                            <div class="featured2">
                                <a href="'.get_post_permalink().'">'.get_the_post_thumbnail(get_the_ID(),'full').'</a>
                            </div>':'').'
                            <div class="excerpt2 '.(has_post_thumbnail(get_the_ID())?'':'').'">
                                <h3><a href="'.get_post_permalink().'">'.get_the_title().'</a></h3>
								<div class="box-bookmark-repository">';
								
								bookmarks(get_permalink(), get_the_title());
									
								echo '</div>
                                <div class="cats">
                                    '.$cats.'
                                    <p>| 
                                    <a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.get_the_author_meta( 'display_name' ).'</a>
                                    </p>
                                </div>
                                <p class="resumen">'.get_the_excerpt(100).'</p>
                            </div>
			    
                        </div>';
                    endwhile;
                    endif;
                    pagination();
                ?>
		
                <div class="row repositorio">
                <a class="wpcf7-submit" href="/submit-your-materials">Submit your materials</a>

		
		
		
		<div class="row">
			</div>
		</div>
	</div>
</section>
<script>
	jQuery(document).ready(function() {
		jQuery("#sbc-submit").click(function(){
			if(jQuery("#sbc-search > #s").val() == "" && jQuery("#sbc-search > #cat:selected option:selected").val() != 0) {
				var query     = jQuery("#sbc-search > #cat option:selected").val();
				var firstPart = "<?php echo get_site_url();?>/?&cat=";
				window.location.href = firstPart + query;
				return false;
			}
		});
	});
</script>
<?php
get_footer();
?>
