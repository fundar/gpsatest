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
                    
                    $data["name"]              = get_post_meta($post->ID, 'Name', true);
                    $data["languages"]         = get_post_meta($post->ID, 'Languages', true);
                    $data["organization"]      = get_post_meta($post->ID, 'Organization', true);
                    $data["others_themes"]     = get_post_meta($post->ID, 'Others_themes', true);
                    $data["regions_countries"] = get_post_meta($post->ID, 'regions_countries', true);
                    $data["residence"]         = get_post_meta($post->ID, 'Residence', true);
                    $data["themes"]            = get_post_meta($post->ID, 'Themes', true);
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
					    <div id="item-body-experts">
						<div class="bp-widget wp-profile">
							
							<!--<h4><?php echo $data["name"];?></h4>-->

							<table class="wp-profile-fields">

								<?php if($data["organization"]) : ?>

									<tr id="wp_displayname" class="divgris">
										<td class="label-default">Works</td>
										<td class="data"><?php echo $data["organization"]; ?></td>
									</tr>

								<?php endif; ?>
								
								<?php if ($data["residence"]) : ?>

									<tr id="wp_displayname" class="divgris claro">
										<td class="label-default">Country of Residence</td>
										<td class="data"><?php echo $data["residence"]; ?></td>
									</tr>

								<?php endif; ?>

								<?php if ($data["languages"]) : ?>

									<tr id="wp_desc" class="divgris">
										<td class="label-default ">Languages</td>
										<td class="data"><?php echo $data["languages"]; ?></td>
									</tr>

								<?php endif; ?>

								<?php if ($data["themes"]) : ?>

									<tr id="wp_website" class="divgris claro">
										<td class="label-default">Areas of Expertise</td>
										<td class="data"><?php echo $data["themes"]; ?></td>
									</tr>

								<?php endif; ?>

								<?php if ($data["themes"]) : ?>

									<tr id="wp_jabber" class="divgris">
										<td class="label-default">Others themes</td>
										<td class="data"><?php echo $data["others_themes"]; ?></td>
									</tr>

								<?php endif; ?>

								<?php if ($data["regions_countries"]) : ?>

									<tr id="wp_aim" class="claro">
										<td class="label-default">Regions and Countries of Expertise</td>
										<td class="data"><?php echo $data["regions_countries"]; ?></td>
									</tr>

								<?php endif; ?>
							<?php endwhile;?>
							</table>
						</div>
					    </div>
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

endif;

get_footer();
