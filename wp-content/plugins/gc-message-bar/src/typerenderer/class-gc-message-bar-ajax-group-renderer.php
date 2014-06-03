<?php
if(class_exists("Gc_MessageBar_Ajax_Group_Renderer")){
    return;
}

class Gc_MessageBar_Ajax_Group_Renderer extends Gc_MessageBar_Abstract_Renderer{
       public function __construct(){

       }

       public function render($description) {
      ?>
	  
      <div class="item displayfilter <?php /* echo $this->get_descriptor_param("css_class"); */ ?>" <?php if(!$description->is_visible()){ echo 'style="display:none"';}?> id="<?php echo $description->get_name(); ?>_cnt">
            <div class="label">
                <label><?php echo $description->get_text(); ?></label>
            </div>
            <div class="edit">
                <input type="text" id="<?php echo $description->get_name(); ?>_text" class="def addinput" value="" />
				<input type="hidden" id="<?php echo $description->get_name(); ?>" name="post[<?php echo $description->get_name(); ?>]" class="def" value="<?php echo htmlspecialchars($description->get_value()); ?>" />
				<input class="addbutton" type="button" id="<?php echo $description->get_name(); ?>_add" value="Add" />
                <div class="clear"></div>
            </div>
            <?php if ($description->get_description()) { ?>
            <div class="desc">
                <label><?php echo $description->get_description(); ?></label>
            </div>
			<div class="pagelist" id="gc-message-bar-specified-pages">
				<?php 
					$pages = unserialize(htmlspecialchars_decode($description->get_value()));
					$pages = ($pages == false) ? array() : $pages;
					foreach ($pages as $key => $val):
					//If edit this, you need to rewrite Ajax handler!!!! -> src/class-gc-message-bar-admin-controller.php -> create_specified_pages_element()
				?>
						<div class="pageitem" id="gc-message-bar-specified-page-<?php echo $key;?>">
                            <div class="specified-close-page"></div>
                            <input type="hidden" id="gc-message-bar-specified-page-key-<?php echo $key;?>" value="<?php echo $val;?>"/>
                            <div class="filter-specified-page" title="<?php echo $val; ?>"><?php 
                            if (strlen($val)>70) {
                            	echo "...";
                            	echo substr($val, strlen($val)-70); 
                            }else{
                            	echo $val;
                            }?></div>
                            <div class="clear"></div>
                        </div>
				<?php endforeach; ?>
			</div>
            <?php } ?>
            <div class="clear"></div>
        </div>
		<script type="text/javascript">
		jQuery('#<?php echo $description->get_name(); ?>_add').on('click', function() {
			jQuery.ajax({
			   url:ajaxurl,
			   data:{
				  'action':'gc-message-bar-add-page',
				  'data':jQuery('#<?php echo $description->get_name(); ?>_text').val()
			   },
			   type:'POST',
			   dataType:'json'
			}).done(function(response){
				if (response.success) {
					jQuery('#gc-message-bar-specified-pages').append(response.result);
					jQuery('#<?php echo $description->get_name(); ?>').val(response.serialized);
					jQuery('#<?php echo $description->get_name(); ?>_text').val('');
				}else{
					alert(response.reason);
				}
			}).fail(function(response){
				alert('Something went wrong. Please try again later.');
			});
		});
		jQuery(document).ready(function(){
			var $specifiedObject = null;
			jQuery('#gc-message-bar-specified-pages').on('click', '.specified-close-page', function() {
				$specifiedObject = jQuery(this); //close button
				jQuery.ajax({
				   url:ajaxurl, 
				   data:{
					  'action':'gc-message-bar-remove-page',
					  'data':$specifiedObject.next().val()
				   },
				   type:'POST',
				   dataType:'json'
				}).done(function(response){
					if (response.success) {
						$specifiedObject.parent().remove();
						jQuery('#<?php echo $description->get_name(); ?>').val(response.serialized);
					}else{
						alert('Something went wrong. Please try again later.');
					}
			    }).fail(function(response){
					alert('Something went wrong. Please try again later.');
				});
			});
		});
	  </script>
        <?php
       }

}

