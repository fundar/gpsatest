<div id="evcal_3" class="postbox evcal_admin_meta">	
	
	<div class="inside">
		<h2><?php _e('Add your own custom styles','eventon');?></h2>
		<p><i><?php _e('Please use text area below to write your own CSS styles to override or fix style/layout changes in your calendar. <br/>These styles will be appended into the dynamic styles sheet loaded on the front-end.','eventon')?></i></p>
		<table width='100%'>
			<tr><td colspan='2'>
				<textarea style='width:100%; height:350px' name='evcal_styles'><?php echo get_option('evcal_styles');?></textarea>				
			</tr>		
		</table>			
	</div>
</div>
<input type="submit" class="evo_admin_btn btn_prime" value="<?php _e('Save Changes') ?>" />
</form>