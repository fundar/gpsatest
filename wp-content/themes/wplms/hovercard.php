<?php if ( bp_has_members( "include={$_POST['userid']}&max=1") ) : ?>

    <?php while ( bp_members() ) : bp_the_member();
       // global $members_template; ?>

        <?php /* The loop for the member you're showing a hovercard for is set up. Place hovercard code here */ ?>

        <div class="tipsy-avatar">
             <?php echo  xprofile_get_field_data( 'Organization', bp_get_member_user_id() ) ; ?>
        </div>


        <div class="clear">

    <?php endwhile; ?>

<?php endif; ?>