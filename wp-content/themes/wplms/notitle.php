<?php
/**
 * Template Name: No Title
 */
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();
?>
<section id="content">
    <div class="container">
            <?php
                the_content();
            endwhile;
            endif;
            ?>
        </div>
    </div>
</section>
</div>

<?php
get_footer();
?>