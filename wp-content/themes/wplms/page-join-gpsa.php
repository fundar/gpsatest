<?php
get_header();
?>
<section id="title">
	<div class="container">
		<div class="row">
            <div class="col-md-12">
                <div class="pagetitle">
                    <h1>Networking</h1>
                    <h5><?php  echo get_bloginfo('description'); ?></h5>
                </div>
            </div>
        </div>
	</div>
</section>

<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
					<?php getMap();?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>
