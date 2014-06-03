<?php
get_header();
?>
<section id="title">
	<div class="container">
		<div class="row">
            <div class="col-md-12">
                <div class="pagetitle">
                    <h1><?php echo get_bloginfo('name'); ?></h1>
                    <h5><?php  echo get_bloginfo('description'); ?></h5>
                </div>
            </div>
        </div>
	</div>
</section>
<section id="content">
	<div class="container">
		<div class="col-md-9 col-sm-8">
			<div class="content">
				<?php getBookmarks();?>
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
get_footer();
?>
