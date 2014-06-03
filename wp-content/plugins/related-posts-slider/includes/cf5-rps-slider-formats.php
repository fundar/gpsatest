<?php //Slider format functions
//format=default
function cf5_rps_wp_init_default(){
    global $cf5_rps;
	$css="styles/".$cf5_rps['stylesheet'].'/style.css';
	wp_enqueue_style( 'cf5_rps_css', cf5_rps_url( $css ),false, CF5_RPS_VER, 'all'); 
	wp_enqueue_script( 'cf5_easing', cf5_rps_url( 'js/jquery.easing.1.3.js' ),array('jquery'), CF5_RPS_VER, true); 
	wp_enqueue_script('cf5_rps', cf5_rps_url( 'js/cf5.rps.js'), array('cf5_easing'), CF5_RPS_VER, true );
}
function cf5_rps_wp_head_default() {
    global $cf5_rps; 
	$cf5_options=$cf5_rps;
	extract($cf5_options);
  if($cf5_rps['stylesheet']=='default'):
	?>
	<style type="text/css">.rps_sldrtitle{font-family:<?php echo $cf5_rps['stitle_font'];?>;font-size:<?php echo $cf5_rps['stitle_size'];?>px;font-weight:<?php echo $cf5_rps['stitle_weight'];?>;font-style:<?php echo $cf5_rps['stitle_style'];?>;<?php if($stitle_color and !empty($stitle_color)){?>color:<?php echo $cf5_rps['stitle_color'];?>;<?php } ?>}.rps_wrapper{height:<?php echo $cf5_rps['height'];?>px;<?php if($bgcolor and !empty($bgcolor)){?>background:<?php echo $cf5_rps['bgcolor'];?>;<?php } ?><?php if($pcontent_color and !empty($pcontent_color)){?>color:<?php echo $cf5_rps['pcontent_color'];?>;<?php } ?>border:<?php echo $cf5_rps['obrwidth'];?>px solid <?php echo $cf5_rps['obrcolor'];?>;font-family:<?php echo $cf5_rps['pcontent_font'];?>;font-size:<?php echo $cf5_rps['pcontent_size'];?>px;line-height:<?php echo ($cf5_rps['pcontent_size']+4);?>px;}.rps_wrapper div.h1div{font-size:<?php echo $cf5_rps['ptitle_size'];?>px;line-height:<?php echo ($cf5_rps['ptitle_size']+4);?>px;font-family:<?php echo $cf5_rps['ptitle_font'];?>;font-weight:<?php echo $cf5_rps['ptitle_weight'];?>;font-style:<?php echo $cf5_rps['ptitle_style'];?>;border-bottom:<?php echo $cf5_rps['ibrwidth'];?>px solid <?php echo $cf5_rps['ibrcolor'];?>;}.rps_wrapper div.h1div a{<?php if($ptitle_color and !empty($ptitle_color)){?>color:<?php echo $cf5_rps['ptitle_color'];?> !important;<?php } ?>}.rps_wrapper div.h2div{font-family:<?php echo $cf5_rps['ltitle_font'];?>;font-size:<?php echo $cf5_rps['ltitle_size'];?>px;font-weight:<?php echo $cf5_rps['ltitle_weight'];?>;font-style:<?php echo $cf5_rps['ltitle_style'];?>;<?php if($ltitle_color and !empty($ltitle_color)){?>color:<?php echo $cf5_rps['ltitle_color'];?>;<?php } ?>line-height:<?php echo ($cf5_rps['ltitle_size']+4);?>px;}.rps_content{border:<?php echo $cf5_rps['ibrwidth'];?>px solid <?php echo $cf5_rps['ibrcolor'];?>;top:<?php echo ($cf5_rps['height']+10);?>px;<?php if($fgcolor and !empty($fgcolor)){?>background-color:<?php echo $cf5_rps['fgcolor'];?>;<?php } ?>}img.rps_thumb{width:<?php echo $cf5_rps['img_width'];?>% !important;max-height:<?php echo $cf5_rps['img_height'];?>px !important;<?php if($cf5_rps['img_align']=='left') {echo 'float:left;margin:0 5px 5px 0 !important;';}if($cf5_rps['img_align']=='right') {echo 'float:right;margin:0 0 5px 5px !important;';}	?>}.rps_content div.pdiv{border-top:<?php echo $cf5_rps['obrwidth'];?>px solid <?php echo $cf5_rps['obrcolor'];?>;}a.rps_more{<?php if($hvtext_color and !empty($hvtext_color)){?>color:<?php echo $cf5_rps['hvtext_color'];?> !important;<?php } ?>border:<?php echo $cf5_rps['ibrwidth'];?>px solid <?php echo $cf5_rps['ibrcolor'];?>;<?php if($hvcolor and !empty($hvcolor)){?>background-color: <?php echo $cf5_rps['hvcolor'];?>;<?php } ?>}.rps_item{border:<?php echo $cf5_rps['ibrwidth'];?>px solid <?php echo $cf5_rps['ibrcolor'];?>;<?php if($fgcolor and !empty($fgcolor)){?>background:<?php echo $cf5_rps['fgcolor'];?>;<?php } ?>}.rps_item:hover div.h2div,.rps_list .selected div.h2div,.rps_item:active div.h2div{<?php if($hvtext_color and !empty($hvtext_color)){?>color:<?php echo $cf5_rps['hvtext_color'];?>;<?php } ?>}.rps_item:hover, .selected{<?php if($hvcolor and !empty($hvcolor)){?>border-color:<?php echo $cf5_rps['hvcolor'];?>;background-color: <?php echo $cf5_rps['hvcolor'];?>;<?php } ?>}</style>
    <?php endif;
}
function cf5_rps_wp_footer_default(){
    global $cf5_rps; ?>
    <script type="text/javascript">
	  var rps_ht = <?php echo $cf5_rps['height']; ?>
	</script>
<?php }
function cf5_rps_default($echo=true,$rps_posts=array()){
    global $post,$cf5_rps,$rps_slider_shown;
	global $wpdb, $table_prefix;
	$slider='';
if($rps_posts and !$rps_slider_shown):
	$slider='<div class="rps_wrapper">
			 <div id="rps_preview" class="rps_preview">';
		
	if($cf5_rps['img_pick'][0] == '1'){
	 $custom_key = array($cf5_rps['img_pick'][1]);
	}
	else {
	 $custom_key = '';
	}
	
	if($cf5_rps['img_pick'][2] == '1'){
	 $the_post_thumbnail = true;
	}
	else {
	 $the_post_thumbnail = false;
	}
	
	if($cf5_rps['img_pick'][3] == '1'){
	 $attachment = true;
	 $order_of_image = $cf5_rps['img_pick'][4];
	}
	else{
	 $attachment = false;
	 $order_of_image = '1';
	}
	
	if($cf5_rps['img_pick'][5] == '1'){
		 $image_scan = true;
	}
	else {
		 $image_scan = false;
	}
	
	$gti_width = false;
	
	if($cf5_rps['crop'] == '0'){
	 $extract_size = 'full';
	}
	elseif($cf5_rps['crop'] == '1'){
	 $extract_size = 'large';
	}
	elseif($cf5_rps['crop'] == '2'){
	 $extract_size = 'medium';
	}
	else{
	 $extract_size = 'thumbnail';
	}
	
	$i=0;
	foreach($rps_posts as $post_id) {
		if($i==0){$topstyle='style="top:3px;"';}
		else {$topstyle='';}
		
		$posts_table = $table_prefix.'posts'; 
		$result = $wpdb->get_results("SELECT * FROM ".$posts_table." WHERE ID = ".$post_id, OBJECT);
		$rps_post = $result[0];
		
		$permalink=get_permalink($post_id);
		
		$img_args = array(
			'custom_key' => $custom_key,
			'post_id' => $post_id,
			'attachment' => $attachment,
			'size' => $extract_size,
			'the_post_thumbnail' => $the_post_thumbnail,
			'default_image' => false,
			'order_of_image' => $order_of_image,
			'link_to_post' => false,
			'image_class' => 'rps_thumb',
			'image_scan' => $image_scan,
			'width' => $gti_width,
			'height' => false,
			'echo' => false,
			'permalink' => ''
		);
		
		$pcontent = $rps_post->post_content;
		
		if ($cf5_rps['pcontent_from'] == "preview_content") {
		    $pcontent = get_post_meta($post_id, 'preview_content', true);
		}
		if ($cf5_rps['pcontent_from'] == "excerpt") {
		    $pcontent = $rps_post->post_excerpt;
		}
		
		$pcontent = strip_shortcodes( $pcontent );
		$pcontent = stripslashes($pcontent);
		$pcontent = str_replace(']]>', ']]&gt;', $pcontent);

		$pcontent = str_replace("\n","<br />",$pcontent);
        $pcontent = strip_tags($pcontent, $cf5_rps['allowable_tags']);
		
		$content_limit=$cf5_rps['pcontent_words'];
		if(empty($content_limit) or $content_limit==''){$flag=0;}else{$flag=1;}
		if($flag==1){$pcontent = cf5_rps_word_limiter( $pcontent, $limit = $content_limit );}
		if($cf5_rps['no_more']==0) {
		   $more='<a href="'.$permalink.'" target="'.$cf5_rps['target'].'" class="rps_more">'.$cf5_rps['more'].'</a>';
		}
		else{
		   $more='';
		}
			 
	$slider=$slider.'		<div class="rps_content" '.$topstyle.'>
					'.cf5_rps_get_the_image($img_args).'
					<div class="h1div"><a href="'.$permalink.'" target="'.$cf5_rps['target'].'" >'.get_the_title($post_id).'</a></div>
					
					<div class="pdiv">'.$pcontent.'</div>
					
					<div class="cf5_rps_cl"></div>
					<div class="cf5_rps_cr"></div>'.
					$more
				.'</div>';
	$i++;} //end foreach

    $slider=$slider.'		</div>
			<div id="rps_list" class="rps_list">';
	$i=0;
	foreach($rps_posts as $post_id) {
	    $page_html='';$page_close='';$selected='';
		
		$ltitle=get_the_title($post_id);
		$content_limit=$cf5_rps['ltitle_words'];
		if(empty($content_limit) or $content_limit==''){$flag=0;}else{$flag=1;}
		if($flag==1){$ltitle = cf5_rps_word_limiter( $ltitle, $limit = $content_limit, $display_dots = false );}
		
		if($i%$cf5_rps['per_page'] == 0){
			if($i==0){$page_html='<div class="rps_page" style="display:block;">';$selected='selected';}
			else{$page_html='<div class="rps_page">';}
		}
		if($i%$cf5_rps['per_page'] == ($cf5_rps['per_page']-1)){$page_close='</div>';}
			
	    $slider=$slider.$page_html.'<div class="rps_item '.$selected.'">
						<div class="h2div">'.$ltitle.'</div>
					</div>'.$page_close;
     $i++;}
    
	if($page_close=='' or empty($page_close)){$slider=$slider.'</div>';}
	
	$slider=$slider.'<div class="rps_nav">
						<a id="rps_prev" class="rps_prev disabled"></a>
						<a id="rps_next" class="rps_next"></a>
				    </div>
			</div>
		</div>';
		$sldr_title='<div class="rps_sldrtitle">'.$support.$cf5_rps['sldr_title'].'</div><div class="cf5_rps_cr"></div>';
		$rpsslider='<div class="cf5_rps">'.$sldr_title.$slider.'<div class="cf5_rps_cl"></div><div class="cf5_rps_cr"></div></div>';
      if($echo){
		echo $rpsslider;
		$rps_slider_shown = true;
		return $rps_slider_shown;
	  }
	  else {
	    $rps_slider_shown = true;
	    return $rpsslider;
	  }
endif;
}

//format = h_carousel

function cf5_rps_wp_init_h_carousel(){
    global $cf5_rps;
	$css='formats/h_carousel/styles/'.$cf5_rps['format_style'].'/style.css';
	$js="formats/h_carousel/js/rps.js";
	wp_enqueue_style( 'cf5_rps_h_carousel_css', cf5_rps_url( $css ),false, CF5_RPS_VER, 'all'); 
	wp_enqueue_script( 'cf5_rps_h_carousel_js', cf5_rps_url( $js ),array('jquery'), CF5_RPS_VER, true); 
}
function cf5_rps_wp_head_h_carousel() {
    global $cf5_rps; 
	$cf5_options=$cf5_rps;
	extract($cf5_options);
	?>
	    <script type="text/javascript"> 
		jQuery(document).ready(function() {
			jQuery('#rps_hcarousel').cf5Rps({
				items: {
					visible: <?php echo $cf5_rps['per_page']; ?>
				},
				scroll: {
					items: <?php echo $cf5_rps['scroll']; ?>,
					pauseOnHover: true
				},
				prev: {	
					button	: "#rps_prev",
					key		: "left"
				},
				next: { 
					button	: "#rps_next",
					key		: "right"
				},
				auto: 4000
			});
		});
	</script> 
	<?php
	
  if($cf5_rps['format_style']=='default'):
	?>
	<style type="text/css">.rps_sldrtitle{font-family:<?php echo $cf5_rps['stitle_font'];?>;font-size:<?php echo $cf5_rps['stitle_size'];?>px;font-weight:<?php echo $cf5_rps['stitle_weight'];?>;font-style:<?php echo $cf5_rps['stitle_style'];?>;<?php if($stitle_color and !empty($stitle_color)){?>color:<?php echo $cf5_rps['stitle_color'];?>;<?php } ?>}.rps_wrapper{<?php if($bgcolor and !empty($bgcolor)){?>background:<?php echo $cf5_rps['bgcolor'];?>;<?php } ?><?php if($ltitle_color and !empty($ltitle_color)){?>color:<?php echo $cf5_rps['ltitle_color'];?>;<?php } ?>border:<?php echo $cf5_rps['obrwidth'];?>px solid <?php echo $cf5_rps['obrcolor'];?>;font-family:<?php echo $cf5_rps['ltitle_font'];?>;font-size:<?php echo $cf5_rps['ltitle_size'];?>px;line-height:<?php echo ($cf5_rps['ltitle_size']+4);?>px;}img.rps_thumb{width:<?php echo $cf5_rps['img_width'];?>px !important;height:<?php echo $cf5_rps['img_height'];?>px !important;border:<?php echo $cf5_rps['ibrwidth'];?>px solid <?php echo $cf5_rps['ibrcolor'];?>;<?php if($cf5_rps['img_align']=='left') {echo 'float:left;margin:0 5px 5px 0 !important;';}if($cf5_rps['img_align']=='right') {echo 'float:right;margin:0 0 5px 5px !important;';}	?>}.rps_item{<?php if($fgcolor and !empty($fgcolor)){?>background:<?php echo $cf5_rps['fgcolor'];?>;<?php } ?>width:<?php echo $cf5_rps['hwidth'];?>px;height:<?php echo $cf5_rps['height'];?>px;}.rps_item a{<?php if($ltitle_color and !empty($ltitle_color)){?>color:<?php echo $cf5_rps['ltitle_color'];}?> !important;}.rps_item a:hover ,.rps_item a:active {<?php if($hvtext_color and !empty($hvtext_color)){?>color:<?php echo $cf5_rps['hvtext_color'];?> !important;<?php } if($hvcolor and !empty($hvcolor)){?>border-color:<?php echo $cf5_rps['hvcolor'];?>;background-color: <?php echo $cf5_rps['hvcolor'];?>;<?php } ?>}</style>
    <?php endif;
}
function cf5_rps_wp_footer_h_carousel(){
    global $cf5_rps; ?>

<?php }

function cf5_rps_h_carousel($echo=true,$rps_posts=array()){
    global $post,$cf5_rps,$rps_slider_shown;
	global $wpdb, $table_prefix;
	$slider='';
if($rps_posts and !$rps_slider_shown):
	$slider='<div class="rps_wrapper">
	         <div id="rps_hcarousel" class="rps_instance">';
		
	if($cf5_rps['img_pick'][0] == '1'){
	 $custom_key = array($cf5_rps['img_pick'][1]);
	}
	else {
	 $custom_key = '';
	}
	
	if($cf5_rps['img_pick'][2] == '1'){
	 $the_post_thumbnail = true;
	}
	else {
	 $the_post_thumbnail = false;
	}
	
	if($cf5_rps['img_pick'][3] == '1'){
	 $attachment = true;
	 $order_of_image = $cf5_rps['img_pick'][4];
	}
	else{
	 $attachment = false;
	 $order_of_image = '1';
	}
	
	if($cf5_rps['img_pick'][5] == '1'){
		 $image_scan = true;
	}
	else {
		 $image_scan = false;
	}
	
	$gti_width = false;
	
	if($cf5_rps['crop'] == '0'){
	 $extract_size = 'full';
	}
	elseif($cf5_rps['crop'] == '1'){
	 $extract_size = 'large';
	}
	elseif($cf5_rps['crop'] == '2'){
	 $extract_size = 'medium';
	}
	else{
	 $extract_size = 'thumbnail';
	}
	
	foreach($rps_posts as $post_id) {
		
		$posts_table = $table_prefix.'posts'; 
		$result = $wpdb->get_results("SELECT * FROM ".$posts_table." WHERE ID = ".$post_id, OBJECT);
		$rps_post = $result[0];
		
		$permalink=get_permalink($post_id);
		
		$img_args = array(
			'custom_key' => $custom_key,
			'post_id' => $post_id,
			'attachment' => $attachment,
			'size' => $extract_size,
			'the_post_thumbnail' => $the_post_thumbnail,
			'default_image' => false,
			'order_of_image' => $order_of_image,
			'link_to_post' => false,
			'image_class' => 'rps_thumb',
			'image_scan' => $image_scan,
			'width' => $cf5_rps['img_width'],
			'height' => $cf5_rps['img_height'],
			'echo' => false,
			'permalink' => ''
		);

		$ltitle=get_the_title($post_id);
		$content_limit=$cf5_rps['ltitle_words'];
		if(empty($content_limit) or $content_limit==''){$flag=0;}else{$flag=1;}
		if($flag==1){$ltitle = cf5_rps_word_limiter( $ltitle, $limit = $content_limit, $display_dots = false );}
			
	    $slider=$slider.$page_html.'<div class="rps_item"><a href="'.$permalink.'" target="'.$cf5_rps['target'].'" >'.cf5_rps_get_the_image($img_args).'<br />'.$ltitle.'</a></div>';
     }
    
	$slider=$slider.'</div><a class="rps_prev" id="rps_prev" href="#"><span>prev</span></a>
	<a class="rps_next" id="rps_next" href="#"><span>next</span></a></div>';
	
		$sldr_title='<div class="rps_sldrtitle">'.$cf5_rps['sldr_title'].'</div>';
		$rpsslider='<div class="cf5_rps">'.$sldr_title.$slider.'<div class="cf5_rps_cl"></div>'.$support.'<div class="cf5_rps_cr"></div></div>';
      if($echo){
		echo $rpsslider;
		$rps_slider_shown = true;
		return $rps_slider_shown;
	  }
	  else {
	    $rps_slider_shown = true;
	    return $rpsslider;
	  }
endif;
}
?>