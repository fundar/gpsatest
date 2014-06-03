
<?php get_header( 'buddypress' ); ?>
<section id="content">
	<div id="buddypress">
	    <div class="container">
	        <div class="row">
	            <div class="col-md-3 col-sm-3">
					<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

					<?php do_action( 'bp_before_group_plugin_template' ); ?>

					<div id="item-header" role="complementary">

						<?php locate_template( array( 'groups/single/group-header.php' ), true ); ?>

					</div><!-- #item-header -->
			
				<div id="item-nav">
					<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
						<ul>

							<?php bp_get_options_nav(); ?>

							<?php do_action( 'bp_group_options_nav' ); ?>

						</ul>
					</div>
				</div><!-- #item-nav -->
			</div>
			<div class="col-md-9 col-sm-9">	
				<div class="padder">
					

					<div id="item-body">

						<?php do_action( 'bp_before_group_body' ); ?>

						<?php do_action( 'bp_template_content' ); ?>

						<?php do_action( 'bp_after_group_body' ); ?>
					</div><!-- #item-body -->

					<?php do_action( 'bp_after_group_plugin_template' ); ?>

					<?php endwhile; endif; ?>

				</div><!-- .padder -->
			</div><!-- #content -->
		</div>
	</div>
	</div>			
</section>	
<?php get_footer( 'buddypress' ); ?>