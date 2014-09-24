<?php
if(class_exists('Gc_Message_Bar_Content_Renderer')) {
	return;
}
class Gc_Message_Bar_Content_Renderer {
	protected $options;
	protected $namespace;
	const hour = 3600000;
	const EV_RENDER_INNER_CONTENT = ".render_bar_inner_content";
	const EV_RENDER_METRIX_TRACKER_EVENT = ".render_bar_metrix_tracker_event";
	
	public function __construct($options, $namespace) {
		$this->options = $options;
		$this->namespace = $namespace;
        $this->metrix_code = $options->get("metrix_code");
        $this->metrix_enable = ($this->metrix_code->get_value() != "");
        $this->event_manager = Gc_MessageBar_Service_Locator::get(GC_MESSAGE_BAR_SL_EVENT_MANAGER);
        $this->initialize();

    }
    public function initialize(){
        $this->event_manager->listen(GC_MESSAGE_BAR_NAME.self::EV_RENDER_INNER_CONTENT,array($this,"on_render_inner"));
        $this->event_manager->listen(GC_MESSAGE_BAR_NAME.self::EV_RENDER_METRIX_TRACKER_EVENT,array($this,"on_render_metrix_tracker_event"));

	}
	
	public function render() {
		//Cookie initialization
		if (!isset($_COOKIE[$this->namespace.'cookie'])) {
			if ($this->options->get("default_state")->get_value() == '1') {
				$_COOKIE[$this->namespace.'cookie'] = 'opened';
			} else {
				$_COOKIE[$this->namespace.'cookie'] = 'closed';
			}
		}
		$content = '';
		$content .= $this->render_close_buttons('open');
		$content .= $this->decide_location();
		$switcher = $this->get_switcher('opened', '2');
		$content .= $this->render_bar_content($switcher);
		if ($this->options->get('enable_shortcode')->get_value() == "1") {
			$content = do_shortcode($content);
		}

		return $content;
	}

	
	public function render_close_buttons($button, $place=null, $state = null) {
		return $this->_render_open_close_button($button, $place, $state);
	}
	public function on_render_metrix_tracker_event($event){
        if(!$this->metrix_enable){
        	return;
        }
        $metrix_code = $event->get_param("metrix_code")->get_value();

        if($this->options->get("action_target")->get_value() == "1"){
             $event->set_result("
             	jQuery('#gc_message_bar_button_a').click(function(e){
                        MXTracker.trackClick('".$metrix_code."');
                });
                ");

        } else{
            $event->set_result("
            	jQuery('#gc_message_bar_button_a').click(function(e){
                        MXTracker.trackHref(e.currentTarget.href,'".$metrix_code."');
                        e.preventDefault();
                        return false;
                });
                ");

        }

	}
	
	public function decide_location($state = "opened") {
		$content = '';
		if ($this->options->get('location')->get_value() == "1") {
			$content .= '<div id="gc_message_bar" class="gc_message_bar_top';
				if ( $this->options->get('enable_animation')->get_value() == "2" && $state == 'opened' ) {
					$content .= '_open';
				}
				if ( is_admin_bar_showing() ) {
					$content .= ' add_adminbar';
				}
			$content .= '">';
		} else {
			$content .= '<div id="gc_message_bar" class="gc_message_bar_bottom';
			if ( $this->options->get('enable_animation')->get_value() == "2" && $state == 'opened' ) {
				$content .= '_open';
			}
			$content .= '">';
		}
        $event = new Gc_MessageBar_Event(array("options" => $this->options));
        $this->event_manager->dispatch(GC_MESSAGE_BAR_NAME.self::EV_RENDER_INNER_CONTENT,$event).
		$content .= 
				'<div id="'.$this->namespace.'layout">'.
					'<div id="'.$this->namespace.'wrapper">'.
					$event->get_result().
					'</div>'.
				'</div>'.
			'</div>';
		return $content;
	}
	
	public function get_switcher($status, $value) {
		if (isset($_COOKIE[$this->namespace.'cookie'])) {
			if ($_COOKIE[$this->namespace.'cookie'] == $status) {
				$switcher = true; //closed
			} else {
				$switcher = false; //open
			}
		} else {
			if ($this->options->get('default_state')->get_value() == $value) {
				$switcher = false; //open
			} else {
				$switcher = true; //closed
			}
		}
		return $switcher;
	}
	
	public function render_bar_content($switcher) {
		$content = '<script type="text/javascript">';
		$expires = ($this->options->get('state_cookie_time')->get_value()) ? $this->options->get('state_cookie_time')->get_value() : 0;
		$content .= $this->_render_scripts();
		if ($this->_is_delayed()) {
			$delay = $this->options->get('delay')->get_value();
			if (($this->options->get('enable_close_button')->get_value() == "1" && $switcher) || $this->options->get('enable_close_button')->get_value() != "1") { //if close button is enabled and opened OR close buttons disabled, then open in delay
				if ($this->options->get('location')->get_value() == "1") {
					$content .= 
						'jQuery("html")
							.css("margin-top",GC_Message_Bar_htmlMarginTop)
							.delay('.$delay.'*1000)
							.animate(
								{
									"margin-top" : (parseInt(jQuery("html").css("margin-top")) + gc_height) + "px"
								},
								250
							);
						gc_status = "open";
						jQuery("#gc_message_bar")
							.delay('.$delay.'*1000)
							.animate(
								{
									"top": parseInt(jQuery("#gc_message_bar").css("top"),10) + 5 + parseInt(jQuery("#gc_message_bar").height(),10) + "px"
								}, 
								250, 
								function() {';
				} else {
					$content .= 
						'jQuery("body")
							.delay('.$delay.'*1000)
							.animate(
								{
									"margin-bottom" : (parseInt(jQuery("body").css("margin-bottom")) + gc_height) + "px"
								},
								250 
							);
						gc_status = "open";
						jQuery("#gc_message_bar")
							.delay('.$delay.'*1000)
							.animate(
								{
									"bottom": parseInt(jQuery("#gc_message_bar").css("bottom"),10) + 5 + parseInt(jQuery("#gc_message_bar").height(),10) + "px"
								},
								250, 
								function() {';
				}
				
				if ($this->options->get('location')->get_value() == "1") {
					$content .= 
									'jQuery("body").addClass("gcmessagebar");';
				}
				$content .= $this->_get_cookie_set_js_code($expires,"opened");
				$content .= '
								}
							);';
			}
		} else {
			if (($switcher && $this->options->get('enable_close_button')->get_value() == "1") || $this->options->get('enable_close_button')->get_value() != "1") { //if close button is disabled or enabled and opened
				$content .= 
						'jQuery(window).bind("scroll", function(){
							if( gc_status == "open" ) {
								return false;
							}
							if(jQuery(this).scrollTop() > '.$this->options->get("reach_scroll")->get_value().') {';
				if ($this->options->get("location")->get_value() == "1") {
					$content .= 
								'if (jQuery("html").css("margin-top") != (parseInt(GC_Message_Bar_htmlMarginTop) + gc_height + "px")) {
									gc_status = "open";
									jQuery("html")
										.css("margin-top",GC_Message_Bar_htmlMarginTop)
										.animate(
											{
												"margin-top" : (parseInt(GC_Message_Bar_htmlMarginTop) + gc_height) + "px"
											},
											250);
									}
									jQuery("#gc_message_bar")
										.animate(
											{
												"top": parseInt(jQuery("#gc_message_bar").css("top"),10) + 5 + parseInt(jQuery("#gc_message_bar").height(),10) + "px"
											},
											250,
											function() {';
				} else {
					$content .= 
								'gc_status = "open";
								jQuery("#gc_message_bar")
										.animate(
											{
												"bottom": parseInt(jQuery("#gc_message_bar").css("bottom"),10) + 5 + parseInt(jQuery("#gc_message_bar").height(),10) + "px"
											},
											250, 
											function() {';
				}
					
				if ($this->options->get('location')->get_value() == "1") {
				$content .= 
												'jQuery("body").addClass("gcmessagebar");';
				} else {
					$content .= 
												'if (jQuery("body").css("margin-bottom") != (parseInt(GC_Message_Bar_htmlMarginBottom) + gc_height) + "px") {
													jQuery("body")
														.animate(
															{
																marginBottom : (parseInt(GC_Message_Bar_htmlMarginBottom) + gc_height) + "px"
															},
															250); 
														}';
				}
				$content .=  $this->_get_cookie_set_js_code($expires,'opened');
				$content .=   '
											}
										);
									}
								}
							);';
			}
		}
		$event = new Gc_MessageBar_Event(array(
			"options" => $this->options,
			"metrix_enable" => $this->metrix_enable,
			"metrix_code" => $this->metrix_code
			));
		$this->event_manager->dispatch(GC_MESSAGE_BAR_NAME.self::EV_RENDER_METRIX_TRACKER_EVENT,$event);
		$content .= $event->get_result();
		$content .= 
			'});
		</script>';

		return $content;
	}
	
	
	///////////////////////////* PROTECTED FUNCTIONS *///////////////////////////
	
	protected function _render_open_close_button($button, $place, $state=null) {
		$location = $this->options->get('location')->get_value();
		if ($this->options->get('enable_close_button')->get_value() != "1") {
			return '';
		}
		$content = '';
		if (($button == 'open' || $place == 'pre') && $this->options->get('button_align')->get_value() == "1") {
			$content .= '<a id="'.$this->namespace.$button.'" class="';
			$content .= 'left ';
			$content = $this->_get_button_classes($content, $state);
			$request = (array)Gc_MessageBar_CF::create("Request");
			$query = parse_url($this->get_current_url(), PHP_URL_QUERY);
			if (!isset($request["data"][$button])) {
				if( $query ) {
					$url = $this->get_current_url() . '&' . $button;
				}
				else {
					$url = $this->get_current_url() . '?' . $button;
				}
			} else {
                $url = $this->get_current_url();
            }
            $url = str_replace("open&close", "close", $url);
            $url = str_replace("close&open", "open", $url);
			$content .= '" '.(($this->options->get('enable_animation')->get_value() == "2") ? 'href="'.$url.'"' : '').'><span class="icon"></span></a>';
		}  
		if (($button == 'open' || $place == 'last') && $this->options->get('button_align')->get_value() == "2") {
			$content = '<a id="'.$this->namespace.$button.'" class="';
			$content .= 'right ';
			$content = $this->_get_button_classes($content, $state);
            $request = (array)Gc_MessageBar_CF::create("Request");
			$query = parse_url($this->get_current_url(), PHP_URL_QUERY);
			if (!isset($request["data"][$button])) {
				if( $query ) {
					$url = $this->get_current_url() . '&' . $button;
				}
				else {
					$url = $this->get_current_url() . '?' . $button;
				}	
			} else {
                $url = $this->get_current_url();
            }
            $url = str_replace("open&close", "close", $url);
            $url = str_replace("close&open", "open", $url);
			$content .= '" '.(($this->options->get('enable_animation')->get_value() == "2") ? 'href="'.$url.'"' : '').'><span class="icon"></span></a>';
		}
		return $content;
	}
	
	protected function _get_button_classes($content, $state=null) {
		if ($this->options->get('close_icon_color')->get_value() == "1") {
			$content .= 'light ';
		} else {
			$content .= 'dark ';
		}
		if (is_admin_bar_showing()) {
			$content .= 'adminbar ';
		}
		if ($this->options->get('location')->get_value() == "1") {
			$content .= 'top ';
            if ($state == 'closed' && ($this->options->get('enable_animation')->get_value() == "2")) {
                $content .= 'showopentop ';
            }
		} else {
			$content .= 'bottom ';
            if ($state == 'closed' && ($this->options->get('enable_animation')->get_value() == "2")) {
                $content .= 'showopenbottom ';
            }
		}
		return $content;
	}
	
	protected function _is_delayed() {
		return ($this->options->get('trigger')->get_value() == 'delay');
	}
	
	protected function _render_scripts() {
		return '
			var GC = {};
			GC.Sticky = {
				Pos : 0,
				Scroll : function() {
					var sticky = jQuery("#sticky");
					if(sticky.length == 0){
						return;
					}
					
					if (window.pageYOffset > GC.Sticky.Pos) {
						sticky.addClass("stickystyle");
						jQuery("#navbar-height").addClass("show");
					} else {
						sticky.removeClass("stickystyle");
						jQuery("#navbar-height").removeClass("show");
					}
				},
				CreatePos: function () {
					var sticky = jQuery("#sticky");
					if(sticky.length == 0){
						return;
					}
					if (jQuery("body").hasClass("adminbar")) {
						return sticky.offset().top - jQuery("#wpadminbar").height();
					} else {
						return sticky.offset().top;
					}
				},
				Init : function(){
					GC.Sticky.Pos = GC.Sticky.CreatePos();
					jQuery(window).scroll(GC.Sticky.Scroll);
				}
			};
			Gc_MessageBar_MarginCleaner = function(){
				var tmp_margin_top = jQuery("html").css("margin-top");
				jQuery("style[media=\'screen\']").each(function(){
					if(jQuery(this).text().indexOf("html { margin-top: 28px !important; }") > 0){
						jQuery(this).text(jQuery(this).text().replace("html { margin-top: 28px !important; }","html { margin-top: 28px; }"));
					}
					if(jQuery(this).text().indexOf("html { margin-top: 32px !important; }") > 0){
						jQuery(this).text(jQuery(this).text().replace("html { margin-top: 32px !important; }","html { margin-top: 32px; }"));
					}
					if(jQuery(this).text().indexOf("html { margin-top: 46px !important; }") > 0){
						jQuery(this).text(jQuery(this).text().replace("html { margin-top: 46px !important; }","html { margin-top: 46px; }"));	
					}
				});
			};

			jQuery(document).ready(function() {
				Gc_MessageBar_MarginCleaner();
				GC.Sticky.Init();
				var GC_Message_Bar_htmlMarginTop = (jQuery("html").css("margin-top")) ? (jQuery("html").css("margin-top")) : 0;
				var GC_Message_Bar_htmlMarginBottom = (jQuery("html").css("margin-bottom")) ? (jQuery("html").css("margin-bottom")) : 0;
				var GC_Message_Bar_OpenTop = parseInt(jQuery("#'.$this->namespace.'open").css("top"),10);
				var GC_Message_Bar_OpenTop_opened = GC_Message_Bar_OpenTop + parseInt(jQuery("#'.$this->namespace.'open").height(),10);
				var GC_Message_Bar_OpenBottom = parseInt(jQuery("#'.$this->namespace.'open").css("bottom"),10);
				var GC_Message_Bar_OpenBottom_opened = GC_Message_Bar_OpenBottom + parseInt(jQuery("#'.$this->namespace.'open").height(),10);'.
				$this->_render_open_close_functions().
				(($this->options->get('enable_close_button')->get_value() == "1") ? '
					jQuery("#'.$this->namespace.'close")
						.click(function() {
							closeMessageBar();
						});
					jQuery("#'.$this->namespace.'open")
						.click(function() {
							openMessageBar();
						});' : '').
				$this->_render_button_init();					
	}
	protected function _render_button_init() {
		if ($this->options->get("enable_close_button")->get_value() == "1" && $this->options->get("location")->get_value() == "1" && ($_COOKIE[$this->namespace.'cookie'] == "closed" || ($_COOKIE[$this->namespace.'cookie'] != "opened" && $this->options->get("default_state")->get_value() == "2"))) {
			return 
				'jQuery("#'.$this->namespace.'open")
					.delay(500).animate(
						{
							"top": GC_Message_Bar_OpenTop_opened + "px"
						},
						1000,
						"easeOutElastic"
					);';
		}
		if ($this->options->get("enable_close_button")->get_value() == "1" && $this->options->get("location")->get_value() == "2" && ($_COOKIE[$this->namespace.'cookie'] == "closed" || ($_COOKIE[$this->namespace.'cookie'] != "opened" && $this->options->get("default_state")->get_value() == "2"))) {
			return 
				'jQuery("#'.$this->namespace.'open")
					.animate(
						{
							"bottom": GC_Message_Bar_OpenBottom_opened + "px"
						},
						1000,
						"easeOutElastic"
					);';
		}
	}
	
	protected function _render_open_close_functions() {
		$content = '';
		$expires = ($this->options->get("state_cookie_time")->get_value()) ? $this->options->get("state_cookie_time")->get_value() : 0;
		if ($this->options->get('enable_close_button')->get_value() != "1") {
			return '';
		}
		$content .=
			'function openMessageBar() {
				if (gc_status == "open" || gc_animating == true) {
					return false;
				}
				gc_status = "open"; gc_animating = true;';
		if ($this->options->get('location')->get_value() == "1") {

			$content .= '
				jQuery("#'.$this->namespace.'open")
					.animate({
						"top": GC_Message_Bar_OpenTop + "px"
					}, 300, "swing");
				
				jQuery("html").css("margin-top",GC_Message_Bar_htmlMarginTop)
					.delay(200)
					.animate({
							"margin-top" : (parseInt(GC_Message_Bar_htmlMarginTop) + gc_height) + "px"
					}, 600, "easeOutExpo");
				jQuery("#gc_message_bar")
					.delay(200)
					.animate({
						"top": parseInt(jQuery("#gc_message_bar").css("top"),10) +  5 + parseInt(jQuery("#gc_message_bar").height(),10) + "px"}, 
						600, 
						"easeOutExpo",
						function() { gc_animating = false;'; //more callback coming from the next if-else
		} else {
			$content .= '
				jQuery("#'.$this->namespace.'open")
					.animate({
						"bottom": parseInt(jQuery("#'.$this->namespace.'open").css("bottom"),10) -  parseInt(jQuery("#'.$this->namespace.'open").height(),10)}, 
							300, 
							"swing", 
							function() {gc_animating = false;});
					
				jQuery("#gc_message_bar")
					.delay(400)
					.animate({"bottom": parseInt(jQuery("#gc_message_bar").css("bottom"),10) + 5 + parseInt(jQuery("#gc_message_bar").height(),10) + "px"},
						250,
						function() {'; //more callback coming from the next if-else
		}
		if ($this->options->get('location')->get_value() == "1") {
			$content .= '
							jQuery("body").addClass("gcmessagebar");
						}
					);';
		} else {
			$content .= '
							if (jQuery("body").css("margin-bottom") != (parseInt(GC_Message_Bar_htmlMarginBottom) + gc_height) + "px") {
								jQuery("body")
									.animate({
											marginBottom : (parseInt(GC_Message_Bar_htmlMarginBottom) + gc_height) + "px"
										},
										250
									);
							}
						}
					);';
		}
		
		$content .= $this->_get_cookie_set_js_code($expires,'opened');
		$content .= '
			}
			
			function closeMessageBar() {
				if (gc_status == "close" || gc_animating == true) {
					return false;
				}
				gc_status = "close"; gc_animating = true;';
		if ($this->options->get('location')->get_value() == "1") {
			$content .= '
				jQuery("#'.$this->namespace.'open")
					.delay(600)
					.animate({
							"top": GC_Message_Bar_OpenTop_opened + "px"
						},
						1000,
						"easeOutElastic",
						function() { 
							gc_animating = false;
						}
					);
				jQuery("html")
					.animate(
						{
							"margin-top" : parseInt(GC_Message_Bar_htmlMarginTop) + "px"
						},
						600,
						"easeInExpo"
					);
				jQuery("#gc_message_bar")
					.animate(
						{
							"top": parseInt(jQuery("#gc_message_bar").css("top"),10) - 5 - parseInt(jQuery("#gc_message_bar").height(),10) + "px"
						},
						600,
						"easeInExpo",
						function() {'; //more callback coming from the next if-else
		} else {
			$content .= '
				jQuery("#'.$this->namespace.'open")
					.delay(300)
					.animate(
						{
							"bottom": parseInt(jQuery("#'.$this->namespace.'open").css("bottom"),10) + parseInt(jQuery("#'.$this->namespace.'open").height(),10)
						},
						1000,
						"easeOutElastic",
						function() {
							gc_animating = false;
						}
					);
				jQuery("#gc_message_bar")
					.animate(
						{
							"bottom": parseInt(jQuery("#gc_message_bar").css("bottom"),10) - 5 - parseInt(jQuery("#gc_message_bar").height(),10) + "px"
						}, 
						250, 
						function() {'; //more callback coming from the next if-else
		}
		if ($this->options->get('location')->get_value() == "1") {
			$content .= '
							jQuery("body")
								.removeClass("gcmessagebar");
						}
					);';
		} else {
			$content .= '
							jQuery("body")
								.animate(
									{
										marginBottom : "0px"
									}, 
									250
								);
						}
					);';
		}
		$content .= $this->_get_cookie_set_js_code($expires,'closed'); //closeMessageBar end
		$content .= '
 								jQuery(window).unbind("scroll");
                                jQuery(window).scroll(GC.Sticky.Scroll);
                        }'; //closeMessageBar end
		return $content;
	}
	protected function _get_cookie_set_js_code($expires,$state = 'closed'){
		return '
						var date = new Date();
						date.setTime(date.getTime() + '.self::hour * $expires.');
						if ( wpCookies.get("'.$this->namespace.'cookie") ) { 
							wpCookies.set("'.$this->namespace.'cookie", null, 0, "/") ;
						}
						wpCookies.set("'.$this->namespace.'cookie", "'.$state.'", date, "/");
		';

	}

	public function on_render_inner($event) {
		$message = $this->options->get("message_text")->get_value();
		if (function_exists("icl_register_string")) {
			icl_register_string('plugin gc-message-bar', 'Message Text for GC Message Bar', $message);
			$message = icl_t('plugin gc-message-bar', 'Message Text for GC Message Bar', $message);
		}
		if ($this->options->get("content_align")->get_value() < 5) {
			$event->set_result(
				'<div id="'.$this->namespace.'content" class="'.$this->namespace.'contentSetting'.$this->options->get("content_align")->get_value().'">'.
					$this->_render_open_close_button("close","pre") .
				
					(!empty($message) 
						? '<span id="'.$this->namespace.'message" class="'.$this->namespace.'messageSetting'.$this->options->get("content_align")->get_value().'">' . (($this->options->get('message_text')->is_formatting_enabled()) ? $this->add_html_formatting($message) : $message) . '</span>' 
						: '' 
					).
					
					$this->_render_button("buttonSetting".$this->options->get("content_align")->get_value()).
					$this->_render_open_close_button("close","last").
				'</div>');
		}
		else {
			$event->set_result(
				'<div id="'.$this->namespace.'content" class="'.$this->namespace.'contentSetting'.$this->options->get("content_align")->get_value().'">'.
					$this->_render_open_close_button("close","pre") .
					$this->_render_button("buttonSetting".$this->options->get("content_align")->get_value()) .
					
					(!empty($message) 
						? '<span id="'.$this->namespace.'message" class="'.$this->namespace.'messageSetting'.$this->options->get("content_align")->get_value().'">' . (($this->options->get('message_text')->is_formatting_enabled()) ? $this->add_html_formatting($message) : $message). '</span>' 
						: '' 
					).
					
					$this->_render_open_close_button("close","last").
				'</div>');
		}
	}
	
	protected function _render_button($class = null) {
		$content = '';
		$message = $this->options->get('action_button_text')->get_value();
		if (function_exists("icl_register_string")) {
			icl_register_string('plugin gc-message-bar', 'Button Text for GC Message Bar', $message);
			$message = icl_t('plugin gc-message-bar', 'Button Text for GC Message Bar', $message);
		}
		if (empty($message)) {
			return '';
		}
		
		if ($this->options->get('action_target')->get_value() == "1") {
			$content = 
				'<a target="_blank" ';
		} else if ($this->options->get('action_target')->get_value() == "2") {
			$content = 
				'<a target="_top" ';
		}
		if ($this->options->get('action_nofollow')->is_checked()) {
			$content .= "rel=\"nofollow\" ";
		}
		$content .= 'id="gc_message_bar_button_a" class="gc_message_bar_'.$class.'" ';
		if ($this->options->get('enable_cloaking')->get_value() == "1") {
			$content .= 'href="?gc_message_bar_redirect">';
		} else {
			$href = $this->options->get("action_url")->get_value();
			if (function_exists("icl_register_string")) {
				icl_register_string('plugin gc-message-bar', 'Button Url for GC Message Bar', $href);
				$href = icl_t('plugin gc-message-bar', 'Button Url for GC Message Bar', $href);
			}

			$content .= 'href="'.$href.'">';
		}
		$content .=     '<span id="'.$this->namespace.'button">'.
							'<span id="'.$this->namespace.'buttontext">'.(($this->options->get('action_button_text')->is_formatting_enabled()) ? $this->add_html_formatting($message) : $message).'</span>'.
						'</span>'.
				'</a>';
		return $content;
	}
	
	protected function add_html_formatting($text) {
		$text = preg_replace('/&lt;b&gt;(.*?)&lt;\/b&gt;/i', '<b>${1}</b>', $text);
		$text = preg_replace('/&lt;s&gt;(.*?)&lt;\/s&gt;/i', '<s>${1}</s>', $text);
		$text = preg_replace('/&lt;i&gt;(.*?)&lt;\/i&gt;/i', '<i>${1}</i>', $text);
		$text = preg_replace('/&lt;u&gt;(.*?)&lt;\/u&gt;/i', '<u>${1}</u>', $text);
		return $text;
	}
	
	/******* NO ANIM ********/
	public function render_no_anim() {
		if (!isset($_COOKIE[$this->namespace.'cookie'])) {
			if ($this->options->get("default_state")->get_value() == '1') {
				$_COOKIE[$this->namespace.'cookie'] = 'opened';
			} else {
				$_COOKIE[$this->namespace.'cookie'] = 'closed';
			}
		} 
		$state = $_COOKIE[$this->namespace.'cookie'];      
		
		$content = '';
		$content .= $this->render_close_buttons('open', null, $state);
		$content .= $this->decide_location($state);
		if ($this->options->get('enable_shortcode')->get_value() == "1") {
			$content = do_shortcode($content);
		}
		if($this->options->get('groups')->get_value() == 2){
			return $content;
		}
		if(!is_plugin_active('groups'.DIRECTORY_SEPARATOR .'groups.php')) {
			return $content;
		}
		$groups = implode(",",array_keys(unserialize(htmlspecialchars_decode($this->options->get('groups_list')->get_value()))));
		$content = do_shortcode("[groups_member group=\"$groups\"]".$content."[/groups_member]");
		return $content;
	}
	
	protected function get_current_url() {
        $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } 
        else 
        {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

}