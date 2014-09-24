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
            	<select class="def pagefiltertype" name="<?php echo $description->get_name(); ?>_filtertype" id="<?php echo $description->get_name(); ?>_filtertype"/>
            		<option selected value="equalsto">Equals to</option>
            		<option value="beginswith">Begins with</option>
            	</select>
                <input type="text" id="<?php echo $description->get_name(); ?>_text" class="def addinput pagefilterinput" value="" />
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
						$url = $val;
						if(substr($val,-1) == "*"){
							$label = "Begins with";
							
							$val = substr($val,0,-1);

						} else{
							$label = "Equals to";
						}
					//If edit this, you need to rewrite Ajax handler!!!! -> src/class-gc-message-bar-admin-controller.php -> create_specified_pages_element()
				?>
						<div class="pageitem" id="gc-message-bar-specified-page-<?php echo $key;?>">
                            <div class="specified-close-page"></div>
                            <div class="specified-label"><?php echo $label;?></div>
                            <?php /* <div class="specified-label">Equals to</div> */ ?>
                            <?php /* Equals to | Begins with */ ?>
                            <input type="hidden" id="gc-message-bar-specified-page-key-<?php echo $key;?>" value="<?php echo $url;?>" data-url="<?php echo $url;?>"/>
                            <div class="filter-specified-page" title="<?php echo $val; ?>"><?php 
                            if (strlen($val)>60) {
                            	echo "...";
                            	echo substr($val, strlen($val)-60); 
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
		jQuery(document).ready(function(){
			
			jQuery('#<?php echo $description->get_name(); ?>_add').on('click', function() {
				http_data = {
					  'action':'gc-message-bar-add-page',
					  'data':jQuery('#<?php echo $description->get_name(); ?>_text').val(),
					  'filtertype':jQuery('#<?php echo $description->get_name(); ?>_filtertype').val()
				   };				
				jQuery.ajax({
				   url:ajaxurl,
				   data: http_data,
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
			var $specifiedObject = null;
			jQuery('#gc-message-bar-specified-pages').on('click', '.specified-close-page', function() {
				$specifiedObject = jQuery(this); //close button
				jQuery.ajax({
				   url:ajaxurl, 
				   data:{
					  'action':'gc-message-bar-remove-page',
					  'data':$specifiedObject.next().next().val()
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

