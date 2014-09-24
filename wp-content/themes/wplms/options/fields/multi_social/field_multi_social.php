<?php
class VIBE_Options_multi_social extends VIBE_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since VIBE_Options 1.0.5
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since VIBE_Options 1.0.5
	*/
	function render(){
		
		$class = (isset($this->field['class']))?$this->field['class']:'regular-text';
		echo '<ul id="'.$this->field['id'].'-ul">';
		
		if(isset($this->value) && is_array($this->value)){
			foreach($this->value['social'] as $k=>$value){ 
				if($value != ''){
					echo '<li>
                                                <select id="'.$this->field['id'].'-'.$k.'[social]" name="'.$this->args['opt_name'].'['.$this->field['id'].'][social]['.$k.']">
                                                    <option value="twitter" '.(($value =='twitter')?'selected="selected"':'').'>Twitter</option>
                                                    <option value="facebook" '.(($value =='facebook')?'selected="selected"':'').'>Facebook</option>
                                                    <option value="github" '.(($value =='github')?'selected="selected"':'').'>Github</option>
                                                    <option value="pinterest" '.(($value =='pinterest')?'selected="selected"':'').'>Pinterest</option>
                                                    <option value="fontawesome-webfont-5" '.(($value =='fontawesome-webfont-5')?'selected="selected"':'').'>Youtube</option>
                                                    <option value="vimeo" '.(($value =='vimeo')?'selected="selected"':'').'>Vimeo</option>
                                                    <option value="pinboard" '.(($value =='pinboard')?'selected="selected"':'').'>pinboard</option>    
                                                    <option value="gplus" '.(($value =='gplus')?'selected="selected"':'').'>Google Plus</option>
                                                    <option value="google" '.(($value =='google')?'selected="selected"':'').'>Google</option>
                                                    <option value="gmail" '.(($value =='gmail')?'selected="selected"':'').'>Gmail</option>
                                                    <option value="tumblr" '.(($value =='tumblr')?'selected="selected"':'').'>Tumblr</option>
                                                    <option value="foursquare" '.(($value =='foursquare')?'selected="selected"':'').'>Foursquare</option>    
                                                    <option value="linkedin" '.(($value =='linkedin')?'selected="selected"':'').'>Linkedin</option>
                                                    <option value="dribbble"'.(($value =='dribbble')?'selected="selected"':'').'>Dribbble</option>
                                                    <option value="forrst"'.(($value =='forrst')?'selected="selected"':'').'>Forrst</option>    
                                                    <option value="stumbleupon" '.(($value =='stumbleupon')?'selected="selected"':'').'>Stumbleupon</option>
                                                    <option value="digg" '.(($value =='digg')?'selected="selected"':'').'>Digg</option>     
                                                    <option value="flickr" '.(($value =='flickr')?'selected="selected"':'').'>flickr</option>      
                                                    <option value="disqus" '.(($value =='disqus')?'selected="selected"':'').'>disqus</option> 
                                                    <option value="yahoo" '.(($value =='yahoo')?'selected="selected"':'').'>yahoo</option> 
                                                    <option value="rss-1" '.(($value =='rss-1')?'selected="selected"':'').'>rss</option> 
                                                    <option value="chrome" '.(($value =='chrome')?'selected="selected"':'').'>chrome</option>         
                                                    <option value="lastfm" '.(($value =='lastfm')?'selected="selected"':'').'>LastFM</option>
                                                    <option value="delicious" '.(($value =='delicious')?'selected="selected"':'').'>Delicious</option>
                                                    <option value="reddit" '.(($value =='reddit')?'selected="selected"':'').'>Reddit</option>     
                                                    <option value="blogger" '.(($value =='blogger')?'selected="selected"':'').'>Blogger</option>     
                                                    <option value="spotify" '.(($value =='spotify')?'selected="selected"':'').'>Spotify</option>
                                                    <option value="instagram" '.(($value =='instagram')?'selected="selected"':'').'>Instagram</option>
                                                    <option value="flattr" '.(($value =='flattr')?'selected="selected"':'').'>Flattr</option>
                                                    <option value="skype" '.(($value =='skype')?'selected="selected"':'').'>Skype</option>
                                                    <option value="dropbox" '.(($value =='dropbox')?'selected="selected"':'').'>Dropbox</option>
                                                    <option value="evernote" '.(($value =='evernote')?'selected="selected"':'').'>Evernote</option>
                                                    <option value="paypal" '.(($value =='paypal')?'selected="selected"':'').'>Paypal</option>
                                                    <option value="soundcloud" '.(($value =='soundcloud')?'selected="selected"':'').'>SoundCloud</option>
                                                    <option value="xing" '.(($value =='xing')?'selected="selected"':'').'>Xing</option>       
                                                    <option value="behance"'.(($value =='behance')?'selected="selected"':'').'>Behance</option>
                                                    <option value="smashing" '.(($value =='smashing')?'selected="selected"':'').'>Smashing Magazine</option>
                                                    <option value="bitcoin" '.(($value =='bitcoin')?'selected="selected"':'').'>Bitcoin</option>    
                                                    <option value="w3c" '.(($value =='w3c')?'selected="selected"':'').'>W3C</option>  
                                                    <option value="html5" '.(($value =='html5')?'selected="selected"':'').'>HTML 5</option> 
                                                    <option value="wordpress" '.(($value =='wordpress')?'selected="selected"':'').'>wordpress</option>     
                                                    <option value="android" '.(($value =='android')?'selected="selected"':'').'>Android</option> 
                                                    <option value="appstore" '.(($value =='appstore')?'selected="selected"':'').'>Appstore</option> 
                                                    <option value="macstore" '.(($value =='macstore')?'selected="selected"':'').'>Macstore</option>     
                                                    <option value="podcast" '.(($value =='podcast')?'selected="selected"':'').'>Podcast</option> 
                                                    <option value="amazon" '.(($value =='amazon')?'selected="selected"':'').'>Amazon</option>     
                                                    <option value="ebay" '.(($value =='ebay')?'selected="selected"':'').'>eBay</option>     
                                                    <option value="googleplay" '.(($value =='googleplay')?'selected="selected"':'').'>Google play</option>     
                                                    <option value="itunes" '.(($value =='itunes')?'selected="selected"':'').'>itunes</option>     
                                                    <option value="songkick" '.(($value =='songkick')?'selected="selected"':'').'>songkick</option>     
                                                    <option value="plurk" '.(($value =='plurk')?'selected="selected"':'').'>plurk</option>     
                                                        <option value="steam" '.(($value =='steam')?'selected="selected"':'').'>steam</option>     
                                                    <option value="wikipedia" '.(($value =='wikipedia')?'selected="selected"':'').'>Wikipedia</option> 
                                                    <option value="lanyrd" '.(($value =='lanyrd')?'selected="selected"':'').'>Lanyrd</option> 
                                                    <option value="fivehundredpx" '.(($value =='fivehundredpx')?'selected="selected"':'').'>Fivehundredpx</option> 
                                                    <option value="gowalla" '.(($value =='gowalla')?'selected="selected"':'').'>gowalla</option> 
                                                    <option value="klout" '.(($value =='klout')?'selected="selected"':'').'>klout</option> 
                                                        <option value="viadeo" '.(($value =='viadeo')?'selected="selected"':'').'>viadeo</option> 
                                                        <option value="meetup" '.(($value =='meetup')?'selected="selected"':'').'>meetup</option> 
                                                        <option value="vk" '.(($value =='vk')?'selected="selected"':'').'>vk</option>    
                                                    <option value="ninetyninedesigns" '.(($value =='ninetyninedesigns')?'selected="selected"':'').'>99 Designs</option>       
                                                    <option value="sina-weibo" '.(($value =='sina-weibo')?'selected="selected"':'').'>Sina weibo</option>
                                                    <option value="openid" '.(($value =='openid')?'selected="selected"':'').'>openid</option>
                                                    <option value="posterous" '.(($value =='posterous')?'selected="selected"':'').'>posterous</option>
                                                    <option value="yelp" '.(($value =='yelp')?'selected="selected"':'').'>yelp</option>
                                                    <option value="scribd" '.(($value =='scribd')?'selected="selected"':'').'>scribd</option>
                                                </select>
                                                <input type="text" id="'.$this->field['id'].'-'.$k.'-url" name="'.$this->args['opt_name'].'['.$this->field['id'].'][url]['.$k.']" value="'.esc_attr($this->value['url'][$k]).'" class="'.$class.'" /> 
                                                <a href="javascript:void(0);" class="vibe-opts-multi-social-remove">'.__('Remove', 'vibe').'</a>
                                             </li>';
					
				}//if
				
			}//foreach
		}else{
				
		}//if
		
		echo '<li style="display:none;">
                    <select id="'.$this->field['id'].'[social]" rel-name="'.$this->args['opt_name'].'['.$this->field['id'].'][social][]">
                                                    <option value="twitter">Twitter</option>
                                                    <option value="facebook">Facebook</option>
                                                    <option value="github">Github</option>
                                                    <option value="pinterest">Pinterest</option>
                                                    <option value="fontawesome-webfont-5">Youtube</option>
                                                    <option value="vimeo">Vimeo</option>
                                                    <option value="pinboard">pinboard</option>    
                                                    <option value="gplus">Google Plus</option>
                                                    <option value="google">Google</option>
                                                    <option value="gmail">Gmail</option>
                                                    <option value="tumblr">Tumblr</option>
                                                    <option value="foursquare">Foursquare</option>    
                                                    <option value="linkedin">Linkedin</option>
                                                    <option value="dribbble">Dribbble</option>
                                                    <option value="forrst">Forrst</option>    
                                                    <option value="stumbleupon">Stumbleupon</option>
                                                    <option value="digg">Digg</option>     
                                                    <option value="flickr">flickr</option>      
                                                    <option value="disqus">disqus</option> 
                                                    <option value="yahoo">yahoo</option> 
                                                    <option value="rss">rss</option> 
                                                    <option value="chrome">chrome</option>         
                                                    <option value="lastfm">LastFM</option>
                                                    <option value="delicious">Delicious</option>
                                                    <option value="reddit">Reddit</option>     
                                                    <option value="blogger">Blogger</option>     
                                                    <option value="spotify">Spotify</option>
                                                    <option value="instagram">Instagram</option>
                                                    <option value="flattr">Flattr</option>
                                                    <option value="skype">Skype</option>
                                                    <option value="dropbox">Dropbox</option>
                                                    <option value="evernote">Evernote</option>
                                                    <option value="paypal">Paypal</option>
                                                    <option value="soundcloud">SoundCloud</option>
                                                    <option value="xing">Xing</option>       
                                                    <option value="behance">Behance</option>
                                                    <option value="smashing">Smashing Magazine</option>
                                                    <option value="bitcoin">Bitcoin</option>    
                                                    <option value="w3c">W3C</option>  
                                                    <option value="html5">HTML 5</option> 
                                                    <option value="wordpress">wordpress</option>     
                                                    <option value="android">Android</option> 
                                                    <option value="appstore">Appstore</option> 
                                                    <option value="macstore">Macstore</option>     
                                                    <option value="podcast">Podcast</option> 
                                                    <option value="amazon">Amazon</option>     
                                                    <option value="ebay">eBay</option>     
                                                    <option value="googleplay">Google play</option>     
                                                    <option value="itunes">itunes</option>     
                                                    <option value="songkick">songkick</option>     
                                                    <option value="plurk">plurk</option>     
                                                    <option value="steam">steam</option>     
                                                    <option value="wikipedia">Wikipedia</option> 
                                                    <option value="lanyrd">Lanyrd</option> 
                                                    <option value="fivehundredpx">Fivehundredpx</option> 
                                                    <option value="gowalla">gowalla</option> 
                                                    <option value="klout">klout</option> 
                                                    <option value="viadeo">viadeo</option> 
                                                    <option value="meetup">meetup</option> 
                                                    <option value="vk">vk</option>    
                                                    <option value="ninetyninedesigns">99 Designs</option>       
                                                    <option value="sina-weibo">Sina weibo</option>
                                                    <option value="openid">openid</option>
                                                    <option value="posterous">posterous</option>
                                                    <option value="yelp">yelp</option>
                                                    <option value="scribd">scribd</option>
                                                </select>
                                                <input type="text" id="'.$this->field['id'].'[url]" name="" placeholder="'.__('Enter Full URL of the page','vibe').'" value="" class="'.$class.'" rel-name="'.$this->args['opt_name'].'['.$this->field['id'].'][url][]" /> 
                                                    <a href="javascript:void(0);" class="vibe-opts-multi-social-remove">'.__('Remove', 'vibe').'</a>
                                                </li>';
		
		echo '</ul>';
		
		echo '<a href="javascript:void(0);" class="vibe-btn green vibe-opts-multi-social-add " rel-id="'.$this->field['id'].'-ul">'.__('Add Social Icons', 'vibe').'</a><br/>';
		
		echo (isset($this->field['desc']) && !empty($this->field['desc']))?' <span class="description">'.$this->field['desc'].'</span>':'';
		
	}//function
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since VIBE_Options 1.0.5
	*/
	function enqueue(){
		
		wp_enqueue_script(
			'vibe-opts-field-multi-social-js', 
			VIBE_OPTIONS_URL.'fields/multi_social/field_multi_social.js', 
			array('jquery'),
			time(),
			true
		);
		
	}//function
	
}//class
?>