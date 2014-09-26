/* ------------------------------------------------------------------------
	* News Box - News Box - jQuery contents slider and viewer
	*
	* @version: 	1.24
	* @requires:	jQuery v1.5 or later (v1.7 to use the lightbox)
	* @author:		Luca Montanari (LCweb) (http://projects.lcweb.it)
------------------------------------------------------------------------- */

(function ($) {
	var lc_NewsBox = function(element, lcnb_settings) {
		
		var settings = $.extend({
			src: [], 				// news sources - array of objects
			theme: 'light',			// newsbox theme to use - CSS file name
			layout: 'horizontal', 	// news layout - horizontal/vertical
			
			height: 300,			// box height 
			width: '100%',			// box width - percentage - integer number
			min_news_h: 100,		// minimum news height for vertical layout - integer number
			min_news_w: 150,		// minimum news width for horizontal layout - integer number
			min_horiz_w: 400,		// minimum width of the horizontal layout for responsive websites - integer number
	
			horiz_img_h: false,		// image height for horizontal layout, false to use the CSS one - integer number
			vert_img_w: false,		// image width for vertical layout, false to use the CSS one - integer number
			
			read_more_txt: '..',	// text to put at the end of shortened news description
			boxed_news: false,		// display each news into a separated box
			buttons_position: 'bottom', // link and socials position - bottom, top, side (only for vertical and boxed layout) 
			btn_over_img: false,	// if the news image exists, puts side buttons over it - bool
			
			max_news: 6, 			// maximum news number to fetch - integer
			news_per_time: 3, 		// news visible in the box - integer
			cache_news: false,		// if use the AJAX caching option - bool
			social_share: true,		// shows the social share buttons - bool 
			hide_elements: '',		// list of elements to hide - date, title, image, link
			show_src_logo: false,	// Show the news source's logo in the box - bool (only for vertical layout)
			
			script_basepath: false, // basepath to the plugin folder
			touchswipe: true,		// enable the touchswipe support for mobile devices - bool
			lightbox: true,			// enable the lightbox for big images and youtube videos
			fb_share_fix: 'http://www.lcweb.it/lcis_fb_img_fix.php', // script to get rid of the facebook block on facebook CDN images

			title_behavior: 'expand',	// set title behavior on click - none, expand, link
			img_behavior: 'lightbox', 	// set image behavior on click - none, expand, link, lightbox
			
			date_format: "d mmmm yyyy",	// date format shown - SS, MM, H, HH, HHH, d, dd, ddd, dddd, m, mm, mmm, mmmm, yy, yyyy
			elapsed_time: false,	// shows the elapsed time instead of the news date - bool
			
			read_more_btn: false,	// replace date box with a read more button - bool
			read_more_btn_txt: 'Read more', // text displayed in the read more button
			
			nav_arrows: false,		// shows the navigation arrows and their position - false / side / top_l / top_c / top_r / bottom_l / bottom_c / bottom_r 
			autoplay: false,		// start the slideshow - bool
			animation_time: 700, 	// animation timing in millisecods / 1000 = 1sec
			slideshow_time: 6000, 	// interval time of the slideshow in milliseconds / 1000 = 1sec	
			carousel: false,		// if use the infinite carousel mode - bool
			
			expandable_news: true,	// add the button to expand news and see full contents - bool
			scroll_exp_elem: true,	// keep image and close button always visible on high expanded news - bool
			exp_main_img_pos: 'inside', // set news main image position in expanded mode - inside, side, hidden
			manage_exp_images: true,	// manage news images to become sizable and add lightbox support - bool 
			exp_img_w: '1_2',		// images container width for expanded layout - 1_1, 1_2, 1_3, 1_4  
			exp_img_h: 225,			// images container width for expanded layout in pixels - integer / auto
			
			// date strings - for internationalization
			short_d_names: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
			full_d_names : ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
			short_m_names: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			full_m_names : ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
			elapsed_names: ["ago", "seconds", "minute", "minutes", "hour", "hours", "day", "days", "week", "weeks", "month", "months"]
				 
		}, lcnb_settings);

	
		// Global variables accessible only by the plugin
		var vars = { 
			lcnb_news_array : [],
			lcnb_ordered_news : [],
			lcnb_done_fetches : 0,
			lcnb_news_number : 0,
			lcnb_inline_news_count : 0,
			lcnb_orig_texts: [],
			
			lcnb_news_w : 0,
			lcnb_news_h : 0,
			lcnb_news_to_show : 0,
			lcnb_show_offset : 0,
			
			lcnb_is_playing : false,
			lcnb_is_expanded : false,
			lcnb_lightbox_ready : false,
			lcnb_carousel_obj : false,
			lcnb_resizing_timeout : false,
			lcnb_event_type : 'click',
			lcnb_wrap_width : 0,
			lcnb_css3_browser : false
		};	
		
		
		// .data() system to avoid issues on multi instances
		var $lcnb_wrap_obj = $(element);
		$lcnb_wrap_obj.data('lcnb_vars', vars);
		$lcnb_wrap_obj.data('lcnb_settings', settings);		
			
		/////////////////////////////////////////////////////////////////	

		// parse inline news
		var parse_inline_news = function() {
			if( $lcnb_wrap_obj.find('.nb_news_wrap').size() > 0 ) {
					
				$lcnb_wrap_obj.find('.nb_news_wrap').each(function() {
					var $wrap = $(this);
					var img_max_w = parseInt( $(this).attr('img_max_w') );
					var img_max_h = parseInt( $(this).attr('img_max_h') );
					
					$(this).find('article').each(function(i, v) {
                    	
						// img size checker
						if(img_max_w && img_max_h) {
							$(this).find('section img').each(function() {
								var src = $(this).attr('src');
								var new_url = lcnb_script_basepath + 'php_assets/img_size_check.php?src='+ encodeURIComponent(src) +'&max_w='+ img_max_w +'&max_h='+ img_max_h;
								$(this).attr('src', new_url);
							});
						}
							
						var date = $(this).attr('datetime');
						var title = $(this).find('header').text();
						var txt = $(this).find('section').html();
						var link = $(this).find('.lcnb_inline_link').attr('href'); /* delete it */ $(this).find('.lcnb_inline_link').remove();
						var img = img_size_check_url( $(this).find('img').first().attr('src'), img_max_w, img_max_h);
						var video = $(this).find('.lcnb_video').attr('src');
						
					    if( typeof(date) != 'undefined' && typeof(txt) != 'undefined' && $.trim(txt) != '' ) {
							
							var d = new Date(date);
							var news_obj = {
								type	: 'inline', 
								time	: d.getTime(),
								date 	: date,
								title	: title,
								txt		: txt,
								link	: link,
								s_link	: link,
								img		: img,
								video	: video,
								exp_img_pos	: (typeof($wrap.attr('exp_img_pos')) != 'undefined') ? $wrap.attr('exp_img_pos') : false,
								exp_img_w	: (typeof($wrap.attr('exp_img_w')) != 'undefined') ? $wrap.attr('exp_img_w') : false,
								exp_img_h	: (typeof($wrap.attr('exp_img_h')) != 'undefined') ? $wrap.attr('exp_img_h') : false
							};
							
							// exclude chosen parameters
							if(settings.hide_elements.indexOf('title') != -1) {news_obj.title = '';}
							if(settings.hide_elements.indexOf('link') != -1) {news_obj.link = '';}
							if(settings.hide_elements.indexOf('image') != -1) {news_obj.img = '';} 

							vars.lcnb_news_array.push(news_obj);
							vars.lcnb_inline_news_count = vars.lcnb_inline_news_count + 1;
							
							if(i == (settings.max_news - 1)) {return false;}
						}
                    });
				});  
			}	
		};
			
			
		// handle the sources
		var handle_sources = function() {
			// if no external sources - initialize with the local ones
			if(settings.src.length == 0) {news_date_sort($lcnb_wrap_obj);}
			
			var g_proxy = 'http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num='+ settings.max_news +'&callback=?&q=';
			$.each(settings.src, function(index, v) {
				var src_obj = this;
				var params = '';
				var to_call = '';
				
				// find the url to call to get the data
				switch(this.type) {
					case 'facebook' : 
						to_call = 'https://www.facebook.com/feeds/page.php?id='+ src_obj.id +'&format=rss20';
						break;
						
					case 'twitter' : 
						var rts = (typeof(src_obj.include_retweet) == 'undefined' || src_obj.include_retweet === true) ? 'true' : 'false';
						to_call = lcnb_script_basepath + 'php_assets/twitter_oauth.php';
						params = { url : 'statuses/user_timeline.json?screen_name='+ src_obj.id +'&exclude_replies=true&include_rts='+ rts +'&count='+ (settings.max_news + 2)};
						break;	
						
					case 'google' : 
						// get the user ID
						if(src_obj.id.indexOf('/') > -1) {
							var pos = src_obj.id.indexOf('?');
							if(pos > -1) {
								src_obj.id = src_obj.id.substring(0, pos);
							}
							var arr = src_obj.id.split('/');
							var clean_id = arr[ (arr.length - 2) ];
						}
						else {var clean_id = src_obj.id;}
						
						to_call = 'https://www.googleapis.com/plus/v1/people/'+ clean_id +'/activities/public?key=AIzaSyCalvBYOVexkTeFT5aIELfgpTVlyGCIvvA';
						params = { 
							maxResults: settings.max_news,
							prettyprint: false,
							fields: 'items(actor(image), annotation, object(content, attachments(content,fullImage,image,thumbnails,objectType)), published, url, verb)'
						};
						break;	
						
					case 'youtube' : 
						var yt_max_res = (settings.max_news > 50) ? 50 : settings.max_news;
						to_call = 'http://gdata.youtube.com/feeds/api/users/'+ src_obj.id +'/uploads?alt=jsonc&v=2&start-index=1&max-results='+ yt_max_res;
						break;
						
					case 'pinterest' : 
						var raw_url = src_obj.url;
						if( raw_url.substr(raw_url.length - 1) == '/' ) {raw_url = raw_url.slice(0, -1);}
					
						to_call = raw_url + '.rss';
						break;	
						
					case 'soundcloud' : 
						var raw_url = src_obj.url;
						if( raw_url.substr(raw_url.length - 1) == '/' ) {raw_url = raw_url.slice(0, -1);}
						
						var arr = raw_url.split('/');
						var username = arr[arr.length-1];

						to_call = 'http://api.soundcloud.com/users/'+ username +'/tracks.json?client_id=4bc0297066dd5e45babd36ce10075160';
						break;			
					
					case 'tumblr' : 
						var raw_url = src_obj.url;
						var url_arr = raw_url.toLowerCase().replace('http://', '').replace('https://', '').split('/');
						var blog_id = url_arr[0];

						to_call = 'http://api.tumblr.com/v2/blog/'+blog_id+'/posts?api_key=pcCK9NCjhSoA0Yv9TGoXI0vH6YzLRiqKPul9iC6OQ6Pr69l2MV&filter=text&limit='+settings.max_news;
						break;	
						
					default:
						to_call = src_obj.url;
						break;		 	
				}
				if(this.type != 'twitter' && this.type != 'google' && this.type != 'youtube' && this.type != 'soundcloud' && this.type != 'tumblr') {
					to_call = g_proxy + encodeURIComponent(to_call);
				} 
				
				
				
				//fetch_news(index, this.type, to_call, exclude, fixed_img, max_img_size, this.link_target, this.author, params);
				fetch_news(index, this.type, to_call, params, src_obj);
			});
		}
			
		
		// fetch news from the sources	
		//var fetch_news = function(index, type, src, exclude, fixed_img, max_img_size, link_target, author, params) {	
		var fetch_news = function(index, type, src, params, src_opts) {
			
			// data to exclude 
			var exclude = (typeof(settings.hide_elements) == 'undefined') ? '' : settings.hide_elements;
			if(typeof(src_opts.hide_elements) == 'string') {exclude = exclude + ',' + src_opts.hide_elements.replace('date.', '');}
			
			// fixed image
			var fixed_img = (src_opts.img == undefined) ? false : src_opts.img; 
			
			// images max sizes
			var max_img_size = (typeof(src_opts.max_img_size) == 'object') ? src_opts.max_img_size : false; 
			
			
			// fetch
			var dataType = (type == "twitter") ? "json" : "jsonp";
			$.ajax({
				url: src,
				data: params,
				dataType: dataType,
				cache: settings.cache_news,
				success: function(resp) {
					var error = false;
					
					switch(type) {
						case 'twitter' : 
							if(typeof(resp.errors) != 'undefined') {error = resp.errors;}
							break;	
						
						case 'google' :
							resp = resp.items;
							if(typeof(resp.error) != 'undefined') {error = resp.error;}
							break;	
						
						case 'youtube': 
							if (typeof(resp.error) != 'undefined')  {error = resp.error.message;}
							else {resp = resp.data.items;}
							break;
						
						case 'soundcloud': 
							if (typeof(resp.errors) != 'undefined')  {error = resp.errors.error_message;}
							else {resp = resp;}

							break;
							
						case 'tumblr': 
							if (resp.meta.status == 200)  {resp = resp.response.posts;}
							else {error = resp.response;}

							break;
						
						default: // rss, facebook and pinterest
							if (resp.responseStatus == 200)  {resp = resp.responseData.feed.entries;}
							else {error = resp.responseDetails;}
							break;		 	
					}
					
					if(!error && typeof(resp) != 'undefined') {
						$.each(resp, function(i, news) {
							switch(type) {
								case 'facebook' : 
									var d = new Date(news.publishedDate);
									var managed_data = man_feed_descr(news.content, fixed_img, max_img_size, '', 'img');
									var title = $("<div/>").html( news.author.replace('&amp;','&') ).text();
									
									var img_url = managed_data.img;
									if(!img_url) {
										img_url = '';
									}
									else if((img_url.indexOf('fbcdn.net') != -1 || img_url.indexOf('akamaihd.net') != -1) && img_url.indexOf('?') == -1) {
										img_url = img_url.replace('_s.', '_o.');
									}
									
									var news_obj = {
										time	: d.getTime(),
										date 	: news.publishedDate,
										title	: title,
										txt		: managed_data.txt.replace(/ <br> /g, '<br/>').replace('/profile.php', 'https://www.facebook.com/profile.php'),
										link	: news.link,
										s_link	: news.link,
										img		: img_url 
									};
									break;
									
								case 'twitter' :
									var fixed_date = normalize_date_str(news.created_at);
									var d = new Date(fixed_date);
									var img = (!fixed_img) ? news.user.profile_image_url : fixed_img;
									if(max_img_size) {img = img_loading_fix(img, max_img_size.w, max_img_size.h);}
									
									// add links to the text - regular expression
									var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
									
									var news_obj = {
										time	: d.getTime(),
										date 	: fixed_date,
										title	: '',
										txt		: news.text.replace(exp, '<a href="$1">$1</a>'),
										link	: news.user.url,
										s_link  : '',
										img		: img.replace('_normal', ''),
										user_id	: settings.src[index].id,
										tweet_id: news.id_str
									}; 
									break;	
									
								case 'google' : 
									var fixed_date = normalize_date_str(news.published);
									var d = new Date(fixed_date);
									var img = (exclude.indexOf('image') != -1) ? '' : get_google_img(news, fixed_img);
									if(max_img_size) {img = img_loading_fix(img, max_img_size.w, max_img_size.h);}
									
									// get text
									var txt = (news.verb == 'share') ? news.annotation : news.object.content;
									var news_obj = {
										time	: d.getTime(),
										date 	: fixed_date,
										title	: '',
										txt		: txt,
										link	: news.url,
										s_link	: news.url,
										img		: img
									};
									break;	
									
									
								case 'youtube' : 
									var fixed_date = normalize_date_str(news.uploaded);
									var d = new Date(fixed_date);
									var img = (fixed_img) ? fixed_img : news.thumbnail.hqDefault;
									if(max_img_size) {img = img_loading_fix(img, max_img_size.w, max_img_size.h);}
									
									// add links to the text - regular expression
									var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
									
									// video URL - fix for IE8
									if(navigator.appVersion.indexOf("MSIE 8.") != -1) {
										var link = (typeof(news.player.mobile) == 'undefined') ? '' : news.player.mobile.replace('m.youtube', 'www.youtube').replace('/details?', '/watch?');
									}
									else {
										var link = (typeof(news.player.default) == 'undefined') ? '' : news.player.default.replace('&feature=youtube_gdata_player', '');		
									}
									
									var news_obj = {
										time	: d.getTime(),
										date 	: fixed_date,
										title	: news.title,
										txt		: news.description.replace(/\n/g, '<br/>').replace(exp, '<a href="$1">$1</a>'),
										link	: link,
										s_link	: link,
										img		: img
									};
									break;
									
									
								case 'pinterest' : 
									var d = new Date(news.publishedDate);
									var managed_data = man_feed_descr(news.content, fixed_img, max_img_size, '', 'a:first-child');
									
									var news_obj = {
										time	: d.getTime(),
										date 	: news.publishedDate,
										title	: '',
										txt		: managed_data.txt,
										link	: news.link,
										s_link	: news.link,
										img		: managed_data.img.replace('/192x/', '/550x/')
									};
									break;	
								
								case 'soundcloud' : 
									var d = new Date(news.created_at);
									
									// add links to the text - regular expression
									var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
			
									// iframe URL to embed
									var embed_url = 'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'+ news.id +'&auto_play=true&hide_related=true&visual=true';
									
									// image
									if(news.artwork_url) {var img = news.artwork_url.replace('-large.', '-t500x500.');}
									else if (news.user.avatar_url) {var img = news.user.avatar_url.replace('-large.', '-t500x500.');}
									else {var img = '';}
									
									var news_obj = {
										time	: d.getTime(),
										date 	: news.created_at,
										title	: news.title,
										txt		: news.description.replace(/\n/g, '<br/>').replace(exp, '<a href="$1">$1</a>'),
										link	: embed_url,
										s_link	: news.permalink_url,
										img		: img
									};
									break;	
									
								case 'tumblr' : 
									if(news.type != 'photo' && news.type != 'link' && news.type != 'text') {
										var news_obj = {};	
									}
									else {
										if(news.type == 'photo') {
											var title = '';
											var link = news.short_url;
											
											var n_data = man_feed_descr( $.trim(news.caption), fixed_img, max_img_size, settings.src[index].strip_tags, settings.src[index].remove_tags);
											var descr = n_data.txt;
											var img = (fixed_img) ? fixed_img : news.photos[0].original_size.url;
											if(max_img_size) {img = img_loading_fix(img, max_img_size.w, max_img_size.h);}
										} 
										else if(news.type == 'link') {
											var title = news.title;
											var link = news.url;
		
											var n_data = man_feed_descr( $.trim(news.description), fixed_img, max_img_size, settings.src[index].strip_tags, settings.src[index].remove_tags);
											var descr = n_data.txt;
											var img = n_data.img
										}
										else { // text type
											var title = news.title;
											var link = news.short_url;
											
											var n_data = man_feed_descr( $.trim(news.body), fixed_img, max_img_size, settings.src[index].strip_tags, settings.src[index].remove_tags);
											var descr = n_data.txt;
											var img = n_data.img
										}
										
										
										
										var js_timestamp = news.timestamp * 1000;
										var news_obj = {
											time	: js_timestamp,
											date 	: new Date(js_timestamp),
											title	: title,
											txt		: descr,
											link	: link,
											s_link	: (news.type == 'link') ? news.short_url : link,
											img		: img
										};
									}
									break;	
									
								default: // rss
									var fixed_date = normalize_date_str(news.publishedDate);
									var d = new Date(fixed_date);
									var managed_data = man_feed_descr( $.trim(news.content), fixed_img, max_img_size, settings.src[index].strip_tags, settings.src[index].remove_tags);

									var news_obj = {
										time	: d.getTime(),
										date 	: fixed_date,
										title	: news.title,
										txt		: managed_data.txt,
										link	: news.link,
										s_link	: news.link,
										img		: managed_data.img
									};
									break;		 	
							}
							
							// exclude chosen parameters
							if(exclude.indexOf('title') != -1) {news_obj.title = '';}
							if(exclude.indexOf('link') != -1) {news_obj.link = '';}
							if(exclude.indexOf('image') != -1) {news_obj.img = '';} 
							
							// common parameters
							news_obj.type = type;
							news_obj.link_target = src_opts.link_target;
							//news_obj.color = (typeof(color) == 'undefined') ? 'none' : color;
							news_obj.author = (typeof(src_opts.author) == 'undefined') ? false : src_opts.author
							
							// expanded mode images - custom setup
							var eim = (typeof(src_opts.exp_img_manag) != 'undefined') ? src_opts.exp_img_manag : {};
							news_obj.exp_img_pos= (typeof(eim.pos) != 'undefined') ? eim.pos : false;
							news_obj.exp_img_w	= (typeof(eim.w) != 'undefined') ? eim.w : false;
							news_obj.exp_img_h	= (typeof(eim.h) != 'undefined') ? eim.h : false;
							
							// if has text - add as proper news
							if(typeof(news_obj.txt) != 'undefined' && news_obj.txt != '') {
								vars.lcnb_news_array.push(news_obj);
							}

							// having reached news limit - stop loop
							if( (vars.lcnb_news_array.length - vars.lcnb_inline_news_count) == (settings.max_news * (vars.lcnb_done_fetches + 1) )) {
								return false;
							}
						});
					}
					
					// once fetches are ended - process the final array
					vars.lcnb_done_fetches = vars.lcnb_done_fetches + 1;
					if(vars.lcnb_done_fetches == settings.src.length) {
						news_date_sort($lcnb_wrap_obj);	
					}
					
				}
			});
		}
		
		
		// manage the RSS description to get the clean news and the image
		var man_feed_descr = function(raw_txt, fix_img, max_img_size, to_strip, to_remove) {

			// image management before append - to avoid useless loading
			if(raw_txt.indexOf('<img') != -1) {
				if(typeof(to_remove) != 'undefined' && to_remove.indexOf("img") !== -1 && to_remove.indexOf("img:first") === -1 ) {
					raw_txt = img_loading_fix(raw_txt, max_img_size, true);
				} else {
					raw_txt = img_loading_fix(raw_txt, max_img_size);
				}
			}
			
			// create
			var $demo = $('<div />');
			
			// remove double spaces
			/*while( raw_txt.indexOf('\n\n') != -1 ) {
				raw_txt = raw_txt.replace(/\n\n/g, '\n');
			}*/
			
			$demo.append('<div id="lcnb_util" style="display: none !important;">'+ raw_txt.replace(/\n/g, '<br/>') +'</div>');

			if(!fix_img && $demo.find('#lcnb_util img').size() > 0) {
				var main_img = $demo.find('#lcnb_util img:first-child').attr('fake-src');	
			} else {
				var main_img = (max_img_size) ? img_loading_fix(fix_img, max_img_size.w, max_img_size.h) : fix_img;
			}

			// remove unwanted tags
			if(typeof(to_remove) != 'undefined' && $.trim(to_remove).length > 0) { 
				var rm_blacklist = to_remove.split(',');
				$.each(rm_blacklist, function(i, v) { 
					$demo.find('#lcnb_util '+ v).remove();
				});
			}

			// strip unwanted tags
			if(to_strip == 'all') {
				$demo.find('#lcnb_util *').not('p, br').each(function() {
					var content = $(this).contents();
					$(this).replaceWith(content);
				});	
			}
			else if(typeof(to_strip) != 'undefined' && $.trim(to_strip).length > 0) { 
				var strip_blacklist = to_strip.split(',');
				$.each(strip_blacklist, function(i, v) { 
					$demo.find('#lcnb_util '+v).each(function() {
                        var content = $(this).contents();
    					$(this).replaceWith(content);
                    });
				});	
			}
				
			// clean html and remove empty tags
			$demo.find('#lcnb_util *').removeAttr('style');
			$demo.find('*:empty').not('br, img').remove(); 
			
			
			// remove duplicated <br> and <br> after a paragraph
			for(x=0; x<2; x++) {
				// if first child is BR - remove it
				if($demo.find('.lcnb_txt > *:first').is('br')) {
					$demo.find('.lcnb_txt > *:first').remove();	
				}
				
				$demo.find('br').each(function() {
					if( $(this).next().is('br') ) {
						$(this).next().remove();	
					}
					if( $(this).prev().is('p')) {
						$(this).remove();
					}
				});
			}
			
			
			// get final text
			var clean_txt = $.trim( $demo.find('#lcnb_util').html());
			return {txt: $.trim(clean_txt), img : main_img};
		}
		
		
		// get the resized image url
		var img_loading_fix = function(content, max_img_size, remove_images) {
			if(!content) {return content;}
			
			var img = $.makeArray();
			var fake = content;
			
			// create fake container and encapsulate images to avoid content loss
			var $fake = $('<div />');
			$fake.append('<div id="lcnb_fake_util" style="display: none !important;">'+ fake +'</div>');
			$fake.find('img').wrap('<span></span>');
			
			// replace <img tag with fake one
			var man_fake = $fake.find('#lcnb_fake_util').html();
			$fake.html('<div id="lcnb_fake_util" style="display: none !important;">'+ man_fake.replace(/<img/g, '<fake-img') +'</div>');
			
			// turn fake img into an image with fake source
			$fake.find('fake-img').each(function() {
				var src = $(this).attr('src');
				
				// check against facebook safe-image small picures
				if(typeof(src) != 'undefined' && src.indexOf('safe_image.php') != -1) {
					src = get_url_param(src, 'url');
				}
				
				$('<img fake-src="'+ src +'" />').insertBefore(this);
				$(this).remove();
			});

			// if will show images and they needs to be resized
			if(typeof(remove_images) == 'undefined' && max_img_size) {
				$fake.find('img').each(function() {
					var src = $(this).attr('fake-src');
					var new_url = lcnb_script_basepath + 'php_assets/img_size_check.php?src='+ encodeURIComponent(src) +'&max_w='+ max_img_size.w +'&max_h='+ max_img_size.h;
					$(this).attr('fake-src', new_url);
				});
			}

			return $fake.find('#lcnb_fake_util').html();
		}
		
		
		// image url to resized one
		var img_size_check_url = function(url, max_w, max_h) {
			if(parseInt(max_w) && parseInt(max_h)) {
				return lcnb_script_basepath + 'php_assets/img_size_check.php?src='+ encodeURIComponent(url) +'&max_w='+ max_w +'&max_h='+ max_h;	
			}
			else {return url;}
		}
		
		// sort news by date
		var news_date_sort = function(subj) {
			var vars = subj.data('lcnb_vars');
			
			if(vars.lcnb_ordered_news.length == settings.max_news || vars.lcnb_news_array.length == 0) {
				vars.lcnb_news_number = vars.lcnb_ordered_news.length;
				
				final_news_management();
				return true;	
			}
			else {
				var mr = {index: 0, time : 0};
				
				$.each(vars.lcnb_news_array, function(i, v) {
					if(v.time > mr.time) {mr = {index: i, time : v.time};}
				});
				
				vars.lcnb_ordered_news.push( vars.lcnb_news_array[mr.index] );
				vars.lcnb_news_array.splice(mr.index, 1);
				news_date_sort(subj);
			}
		}
		
		
		// final news sorting and management
		var final_news_management = function() {
			
			// check max news to show value
			if(settings.news_per_time > vars.lcnb_news_number) {settings.news_per_time = vars.lcnb_news_number;}

			// build
			$.each(vars.lcnb_ordered_news, function(i, v) {

				//// buttons
				// social share
				if(settings.social_share == true) {
					var socials = social_share_code(v.type, v);
				} else {var socials = '';}	
				
				// link
				if(typeof(v.link) != 'undefined' && v.link != '') {
					 var trueLink = (v.type == 'soundcloud') ? v.s_link : v.link;
					 var link = '<div class="lcnb_link"><a href="'+ trueLink +'" target="_blank"></a></div>';
				} else {
					var link = '';
					if(settings.title_behavior == 'link')	{settings.title_behavior = 'none';}
					if(settings.img_behavior == 'link') 	{settings.img_behavior = 'none';}
				}
					 
				// date
				if(settings.hide_elements.indexOf('date') == -1) {
					var visibility = (settings.read_more_btn) ? 'style="display: none;"' : '';
					var date = '<div class="lcnb_btn_time" '+visibility+'><time class="lcnb_date" pubdate="pubdate" datetime="'+ v.date +'">'+ date_format(v.date) +'</time></div>';
				} else {var date = '';}
				
				
				// read more button
				if(settings.read_more_btn && typeof(v.link) != 'undefined' && v.link != '') {
					var rm_btn = '<div class="lcnb_btn_time lcnb_rm_btn_wrap"><a class="lcnb_rm_btn" href="'+ trueLink +'">'+ settings.read_more_btn_txt +'</a></div>';
					link = '';
				} else {var rm_btn = '';}
				
				
				// expand news button
				if(settings.expandable_news == true) {
					var expand = '<div class="lcnb_btn_expand noSwipe"></div>';
				} else {
					var expand = '';
					if(settings.title_behavior == 'expand')	{settings.title_behavior = 'none';}
					if(settings.img_behavior == 'expand') 	{settings.img_behavior = 'none';}
				}	
				
				
				//// buttons position
				var btn_bar = '', top_box = '', btm_box = '';
				
				// side
				if(settings.buttons_position == 'side' && settings.layout == 'vertical' && settings.boxed_news) {
					if(
						settings.social_share == true || 
						link ||
						settings.hide_elements.indexOf('date') == -1
					) {
						var btn_position = (settings.btn_over_img != true  || typeof(v.img) == 'undefined' || v.img == '') ? 'relative' : 'absolute';
						btn_bar = '<div class="lcnb_buttons noSwipe" style="position: '+ btn_position +';">' + expand + link + socials + '</div>';
					}
				
					// date always in bottom position
					if(settings.hide_elements.indexOf('date') == -1 || rm_btn) {
						btm_box = '<div class="lcnb_btm_bar">' + date  + rm_btn + '</div>';
					} 
				}
				
				// top
				else if(settings.buttons_position == 'top') {
					if(
						settings.social_share == true || 
						link ||
						settings.hide_elements.indexOf('date') == -1 ||
						rm_btn
					) {
						var top_box = '<div class="lcnb_top_bar">' + expand + socials + link + date + rm_btn + '</div>';
					}	
				}
				
				// bottom 
				else {
					if(
						settings.social_share == true || 
						link ||
						settings.hide_elements.indexOf('date') == -1 ||
						rm_btn
					) {
						var btm_box = '<div class="lcnb_btm_bar">' + expand + socials + link + date + rm_btn + '</div>';
					}
				}
				
				
				///////////////////////////
				// title
				if(v.title == '' || v.type == 'facebook') {var title = '';}
				else {
					if(settings.title_behavior == 'link') {
						var title =  '<h3 class="lcnb_title"><a href="'+trueLink+'" class="lcnb_linked_title">'+ v.title +'</a></h3>';
					} else {
						var exp_class = (settings.title_behavior == 'expand') ? 'lcnb_expand_trig noSwipe' : '';
						var title =  '<h3 class="lcnb_title '+exp_class+'">'+ v.title +'</h3>';
					}
				}
				
				// author
				var author = (typeof(v.author) == 'undefined' || v.author == '') ? '' : '<span class="lcnb_author">'+ v.author +'</span> ';
				
				// image - lightbox code
				if(settings.lightbox || (!settings.lightbox && settings.img_behavior != 'none' && settings.img_behavior != 'lightbox')) {
					var lb_class = (v.type == 'youtube' || v.type == 'soundcloud') ? 'lcnb_video_lb' : 'lcnb_img_lb';		
					if(v.type == 'inline' && typeof(v.video) != 'undefined') {lb_class = 'lcnb_video_lb';}
					
					if(settings.img_behavior == 'expand' && settings.expandable_news) {lb_class += ' lcnb_expand_trig';}
					if(settings.img_behavior == 'link') {lb_class += ' lcnb_linked_img';}
					
					if(v.type == 'youtube') {var lb_src =  v.s_link;}
					else if(v.type == 'soundcloud') {var lb_src = v.link;}
					else if(v.type == 'inline' && typeof(v.video) != 'undefined') {var lb_src = v.video;}
					else {var lb_src = get_url_param(v.img, 'src');}
					
					if(!lb_src) {var lb_code = '';}
					else {
						var img_link = (settings.img_behavior == 'link') ? '<a href="'+ trueLink +'"></a>' : '';
						var lb_code = '<div class="'+ lb_class +' noSwipe" data-mfp-src="'+ lb_src +'"><div class="lcnb_lb_icon">'+img_link+'</div><div class="lcnb_lb_overlay"></div></div>';
					}
				} else {var lb_code = '';}
				
				var img = (typeof(v.img) == 'undefined' || v.img == '') ? '' : '<div class="lcnb_img"><div><img src="'+ v.img +'"/></div>'+ lb_code +'</div>';
				
				// contents box
				var contents_box = '<section class="lcnb_contents"><div><div class="lcnb_contents_inner">'+ title +' <div class="lcnb_txt">'+ author + v.txt +'</div></div></div></section>';

				// custom exp img options
				var exp_img_param = '';
				if(v.exp_img_pos) 	{exp_img_param += ' lcnb_exp_img_pos="'+v.exp_img_pos+'"';}
				if(v.exp_img_w) 	{exp_img_param += ' lcnb_exp_img_w="'+v.exp_img_w+'"';}
				if(v.exp_img_h) 	{exp_img_param += ' lcnb_exp_img_h="'+v.exp_img_h+'"';}

				////////////////////////////
				// append news with boxes
				var pre_styles = 'style="opacity: 0; filter: alpha(opacity=0); top: 30px;"'
				var news_box = '<article class="lcnb_news lcnb_type_'+ v.type +'" '+ pre_styles +' rel="'+ i +'" '+exp_img_param+'><div class="lcnb_news_inner">'+ 
									btn_bar + img + top_box + contents_box + btm_box 
								+'</div></article>'; 
				$lcnb_wrap_obj.find('.lcnb_inner').append(news_box);
				
				// save original text in array
				vars.lcnb_orig_texts[i] = author + v.txt;

				// change the link target
				if(typeof(v.link_target) != 'undefined') {
					$lcnb_wrap_obj.find('.lcnb_news:last-child a').attr('target', v.link_target);
				}

				if(i == (vars.lcnb_news_number - 1)) {
					// size, adjust and execute
					size_boxes();
					news_img_adjust();	
					show_with_fx();
					news_txt_shortening(); 
					
					if(settings.touchswipe) {lcnb_touchswipe();}
				}
			});
		}
		
		
		// show news with effects
		var show_with_fx = function() {
			$lcnb_wrap_obj.find('.lcnb_wrap').removeClass('lcnb_loading');
			var vars = $lcnb_wrap_obj.data('lcnb_vars');
			
			$lcnb_wrap_obj.find('.lcnb_news').each(function(i, v) {
				if((i + 1) > vars.lcnb_news_to_show) {i = 0;} // effect only ones to show at the beginning
				
				var $news = $(this);
				setTimeout(function() { 
					$news.animate({'opacity': 1, 'top': 0}, 550);
				}, (300 * i));
			});	
			
			// if on small screen trigger resize to fix img size delay issues on mobile
			if($(window).width() < 500) {
				setTimeout(function() {
					size_boxes();
				}, 200);
			}
			
			// autoplay
			if(settings.autoplay && vars.lcnb_is_playing === false) {
				start_slideshow($lcnb_wrap_obj);	
			}
		}
		 

		
		// get google news image
		var get_google_img = function(news, fix_img) {
			if(fix_img || typeof(news.object.attachments) == 'undefined') {
				var img = '';
			} else {
				if(news.object.attachments[0].objectType == 'album') {
					var img = news.object.attachments[0].thumbnails[0].image.url;
				}
				else if(news.object.attachments[0].objectType == 'video') {
					var img = news.object.attachments[0].image.url;
				}
				else {
					if(typeof(news.object.attachments[0].fullImage.url) == 'undefined' || news.object.attachments[0].fullImage.url == '') {
						var img = news.actor.image.url.replace('?sz=50', '');
					} else {
						var img = news.object.attachments[0].fullImage.url;
					}
				}
			}
			
			return img;
		}
		

		// normalize date for IE8 and 9
		var normalize_date_str = function(str) {
			if( navigator.appVersion.indexOf("MSIE") != -1 ) {
				
				// set RFC 3339
				if(str.indexOf('Z') != -1 || str.indexOf('T') != -1) {
					var regexp = /(\d\d\d\d)(-)?(\d\d)(-)?(\d\d)(T)?(\d\d)(:)?(\d\d)(:)?(\d\d)(\.\d+)?(Z|([+-])(\d\d)(:)?(\d\d))/;
			 		var d_obj = new Date();
					
					if (str.toString().match(new RegExp(regexp))) {
						var d = str.match(new RegExp(regexp));
						var offset = 0;
						 
						d_obj.setUTCDate(1);
						d_obj.setUTCFullYear(parseInt(d[1],10));
						d_obj.setUTCMonth(parseInt(d[3],10) - 1);
						d_obj.setUTCDate(parseInt(d[5],10));
						d_obj.setUTCHours(parseInt(d[7],10));
						d_obj.setUTCMinutes(parseInt(d[9],10));
						d_obj.setUTCSeconds(parseInt(d[11],10));
						
						if (d[12]) d_obj.setUTCMilliseconds(parseFloat(d[12]) * 1000);
						else d_obj.setUTCMilliseconds(0);
						
						if (d[13] != 'Z') {
							offset = (d[15] * 60) + parseInt(d[17],10);
							offset *= ((d[14] == '-') ? -1 : 1);
							d_obj.setTime(d_obj.getTime() - offset * 60 * 1000);
						}
					}
					else {d_obj.setTime(Date.parse(str));}
					
					return d_obj;
				}
				else {
					
					// check and eventually try to compose the UTC format (for twitter)
					if(isNaN( new Date(str) )) {	
						var t = str.split(' ');
						return new Date(Date.parse(t[1] + " " + t[2] + ", " + t[5] + " " + t[3] + " UTC"));
					} 
					else {
						return str;	
					}
				}
			}
			else {return str;}
		}
		
		
		// manage the date object
		var date_format = function(utc_str) {
			var d = new Date(utc_str);
			
			// standard date 
			if(!settings.elapsed_time) {
				var clean_date = settings.date_format;
				var formats = ['SS', 'MM', 'HHH', 'HH', 'H', 'dddd', 'ddd', 'dd', 'd', 'mmmm', 'mmm', 'mm', 'm', 'yyyy', 'yy']
				var prefix = '';

				// seconds
				if(clean_date.indexOf('SS') > -1) {
					prefix = (d.getSeconds() < 10) ? '0' : '';
					clean_date = clean_date.replace('SS', prefix + d.getSeconds());
				}
				
				// minutes
				if(clean_date.indexOf('MM') > -1) {
					prefix = (d.getMinutes() < 10) ? '0' : '';
					clean_date = clean_date.replace('MM', prefix + d.getMinutes());
				}
				
				// hour
				if(clean_date.indexOf('HHH') > -1) { // 12H format
					prefix = (d.getHours() >= 12) ? 'PM' : 'AM';
					clean_date = clean_date.replace('HHH', (d.getHours() % 12) +' '+ prefix); 
				}
				else if(clean_date.indexOf('HH') > -1) { // 00-23 format
					prefix = (d.getHours() < 10) ? '0' : '';
					clean_date = clean_date.replace('HH', prefix + d.getHours());
				}
				else if(clean_date.indexOf('H') > -1) { // 00-23 format
					clean_date = clean_date.replace('H', d.getHours());
				}
				
				// day
				if(clean_date.indexOf('dddd') > -1) { // monday-sunday format
					clean_date = clean_date.replace('dddd', settings.full_d_names[ d.getDay() ]);
				}
				else if(clean_date.indexOf('ddd') > -1) { // mon-sun format
					clean_date = clean_date.replace('ddd', settings.short_d_names[ d.getDay() ]);
				}
				else if(clean_date.indexOf('dd') > -1) { // 01-31 format
					prefix = (d.getDate() < 10) ? '0' : '';
					clean_date = clean_date.replace('dd', prefix + d.getDate());
				}
				else if(clean_date.indexOf('d') > -1) { // 1-31 format
					clean_date = clean_date = clean_date.replace('d', d.getDate());
				}
				
				// month
				if(clean_date.indexOf('mmmm') > -1) { // january-december format
					clean_date = clean_date.replace('mmmm', settings.full_m_names[ d.getMonth() ]);
				}
				else if(clean_date.indexOf('mmm') > -1) { // jan-dec formatt
					clean_date = clean_date.replace('mmm', settings.short_m_names[ d.getMonth() ]);
				}
				else if(clean_date.indexOf('mm') > -1) { // 01-12 format
					prefix = ((d.getMonth() + 1) < 10) ? '0' : '';
					clean_date = clean_date.replace('mm', prefix + (d.getMonth() + 1));
				}
				else if(clean_date.indexOf('m') > -1) { // 1-12 format
					clean_date = clean_date.replace('m', (d.getMonth() + 1));
				}
				
				// year
				if(clean_date.indexOf('yyyy') > -1) { // four digits format
					clean_date = clean_date.replace('yyyy', d.getFullYear());
				}
				else if(clean_date.indexOf('yy') > -1) { // two digits format
					var full_y = d.getFullYear().toString();
					clean_date = clean_date.replace('yy', full_y.substr(2));
				}
			}
			
			//elapsed time
			else {
				var n = new Date();
				var diff = Math.ceil( (n.getTime() - d.getTime()) / 1000);
				
				// posted seconds ago
				if		(diff < 60) {
					var clean_date = diff +' '+ settings.elapsed_names[1];
				}
				
				// posted one minute ago
				else if (diff >= 60 && diff < 120) {
					var clean_date = '1 ' + settings.elapsed_names[2];
				}
				
				// posted minutes ago
				else if (diff < 3600) {
					var val = Math.floor( diff / 60); 
					var clean_date = val +' '+ settings.elapsed_names[3];
				}
				
				// posted one hour ago
				else if (diff >= 3600 && diff < 7200) {
					var clean_date = '1 ' + settings.elapsed_names[4];
				}
				
				// posted hours ago
				else if (diff < 86400) {
					var val = Math.floor( (diff / 60) / 60 ); 
					var clean_date = val +' '+ settings.elapsed_names[5];
				}
				
				// posted one day ago
				else if (diff >= 86400 && diff < 172800) {
					var clean_date = '1 ' + settings.elapsed_names[6];
				}
				
				// posted days ago
				else if (diff < 604800) {
					var val = Math.floor( ((diff / 60) / 60) / 24 );
					var clean_date = val +' '+ settings.elapsed_names[7];
				}
				
				// posted one week ago
				else if (diff >= 604800 && diff < 1209600) { 
					var clean_date = '1 ' + settings.elapsed_names[8];
				}
				
				// posted weeks ago - max 4 weeks
				else if (diff < 2592000) { 
					var val = Math.floor( (((diff / 60) / 60) / 24) / 7 );
					var clean_date = val +' '+ settings.elapsed_names[9];
				}
				
				// posted one month ago
				else if (diff >= 2592000 && diff < 5184000) {
					var clean_date = '1 ' + settings.elapsed_names[10];
				}
				
				// posted months ago
				else {
					var val = Math.floor( (((diff / 60) / 60) / 24) / 30 );
					var clean_date = val +' '+ settings.elapsed_names[11];
				}

				clean_date = clean_date +' '+ settings.elapsed_names[0];
			}
			
			return clean_date;
		}
			
		
		// size boxes	
		var size_boxes = function(forced_per_time) {
			var settings = $lcnb_wrap_obj.data('lcnb_settings');
			var vars = $lcnb_wrap_obj.data('lcnb_vars');
			
			var $news_wrap = $lcnb_wrap_obj.find('.lcnb_wrap');
			var $news_box = $news_wrap.find('.lcnb_news');
			var pp = (typeof(forced_per_time) == 'undefined') ? settings.news_per_time : forced_per_time;
			
			// cmd visibility
			if($lcnb_wrap_obj.find('.lcnb_news').size() <= pp) {
				$lcnb_wrap_obj.find('.lcnb_wrap').removeClass('lcnb_has_cmd');	
			} else {
				if($lcnb_wrap_obj.find('.lcnb_cmd').size() > 0) {
					$lcnb_wrap_obj.find('.lcnb_wrap').addClass('lcnb_has_cmd');	
				}
			}
			
			// left/top margins if is uniblock
			if($news_wrap.hasClass('lcnb_uniblock')) {
				if($news_wrap.hasClass('lcnb_horizontal')) {
					var uniblock_trick = get_int( $news_wrap.find('.lcnb_news').last().css('border-left-width')) * -1;
				} else {
					var uniblock_trick = get_int( $news_wrap.find('.lcnb_news').last().css('border-top-width')) * -1;
				}
			}
			else {var uniblock_trick = 0;}
			
			// reset fix for uniblocks
			$news_wrap.css('width', '');
			
			// if uniblock - count the wrapper and first box borders
			if($news_wrap.hasClass('lcnb_uniblock')) {
				var wrap_w = $news_wrap.width() - get_int( $news_wrap.css('border-left-width')) - get_int( $news_wrap.css('border-right-width')) + uniblock_trick;	
				var wrap_h = settings.height - get_int( $news_wrap.css('border-top-width')) - get_int( $news_wrap.css('border-bottom-width')) + uniblock_trick;	
			} else {
				var wrap_w = $news_wrap.width();
				var wrap_h = settings.height;	
			}

			//////////////////////////////
			// horizontal layout
			if($news_wrap.hasClass('lcnb_horizontal')) {
				var box_w = (wrap_w / pp) - get_int( $news_box.css('margin-left')) - get_int( $news_box.css('margin-right'));
				if($news_wrap.hasClass('lcnb_uniblock')) {
					box_w = box_w + get_int( $news_box.css('border-right-width')) - get_int( $news_box.css('border-left-width')) - uniblock_trick;
				}
				
				// box width
				if(box_w < settings.min_news_w && pp > 1)	{
					size_boxes( pp - 1 );
					return false;
				} else {
					var w = Math.floor(box_w);
				}
				
				var h = $news_wrap.height() - get_int( $news_box.css('margin-top')) - get_int( $news_box.css('margin-bottom'));
				
				// remove/add 1px margin to wrapper if smaller than shown news
				var shown_news_w = pp * (w + get_int( $news_box.css('margin-left')) + get_int( $news_box.css('margin-right')));
				if($news_wrap.hasClass('lcnb_uniblock')) {shown_news_w = shown_news_w + (pp * uniblock_trick) - uniblock_trick;}
				
				var wrap_total_w = $news_wrap.width() + get_int( $news_wrap.css('border-top-width')) + get_int( $news_wrap.css('border-bottom-width'));
				
				if(wrap_total_w < shown_news_w) {
					$news_wrap.css('margin-right', ( get_int($news_wrap.css('margin-right')) - 1));	
				}
				if(wrap_total_w > shown_news_w) {
					$news_wrap.css('margin-right', ( get_int($news_wrap.css('margin-right')) + 1));	
				}
				
				vars.lcnb_news_w = w;
				vars.lcnb_news_h = h;
				vars.lcnb_news_to_show = pp;
				
				// if uniblock - check wrapper width against shown items
				if($news_wrap.hasClass('lcnb_uniblock')) {
					var diff = $news_wrap.width() - ((w*pp) - ((pp - 1) * get_int( $news_box.css('border-left-width'))));
					if(diff != 0) {
						$news_wrap.css('width', ($news_wrap.width() - diff));	
					}
				}
				
				// if passing from vertical to horizontal and has top bar - remove top padding
				if(settings.buttons_position == 'top') {
					$news_wrap.find('.lcnb_contents_inner').removeAttr('style');	
				}
			}
			
			//////////////////////////////
			// vertical layout
			else {
				var box_h = (wrap_h / pp) - get_int( $news_box.css('margin-top')) - get_int( $news_box.css('margin-bottom'));
				if($news_wrap.hasClass('lcnb_uniblock')) {
					box_h = box_h + get_int( $news_box.css('border-bottom-width')) - get_int( $news_box.css('border-top-width')) - uniblock_trick;
				}

				// box height
				if(box_h < settings.min_news_h && pp > 1)	{
					size_boxes( pp - 1 );
					return false;
				} else {
					var h = Math.round(box_h);
				}

				w = wrap_w - get_int( $news_box.css('margin-left')) - get_int( $news_box.css('margin-right'));
				if($news_wrap.hasClass('lcnb_uniblock')) {w = w - uniblock_trick;}
				
				// retouch wrapper height if smaller than shown news
				var shown_news_h = pp * (h + get_int( $news_box.css('margin-top')) + get_int( $news_box.css('margin-bottom')));
				if($news_wrap.hasClass('lcnb_uniblock')) {shown_news_h = shown_news_h + (pp * uniblock_trick) - uniblock_trick;}
				
				var wrap_total_h = settings.height + get_int( $news_wrap.css('border-top-width')) + get_int( $news_wrap.css('border-bottom-width'));
				
				if(!vars.lcnb_is_expanded) {
					if(wrap_total_h < shown_news_h) {
						$news_wrap.css('height', shown_news_h);	
					} else {
						$news_wrap.css('height', settings.height);	
					}
				}
				
				
				vars.lcnb_news_w = 100; // fake value
				vars.lcnb_news_h = h;
				vars.lcnb_news_to_show = pp;
			}
			
			
			// text block height
			if($news_wrap.hasClass('lcnb_horizontal')) {
				$news_box.find('.lcnb_contents').css('max-height', 'none');
				$news_box.find('.lcnb_contents').children().css('height','auto');
			} else {
				var cont_h = h - ($news_box.outerHeight(true) - $news_box.height());
				$news_box.find('.lcnb_contents').css('max-height', cont_h);
				
				var txt_h = cont_h - $news_box.find('.lcnb_btm_bar, .lcnb_top_bar').outerHeight(true) - 
									 ($news_box.find('.lcnb_contents').outerHeight(true) - $news_box.find('.lcnb_contents').height());
									 
				if($news_box.find('.lcnb_btm_bar, .lcnb_top_bar').size() > 0) {
					txt_h = txt_h - $news_box.find('.lcnb_btm_bar, .lcnb_top_bar').outerHeight(true);
				}
				
				$news_box.find('.lcnb_contents').children().css('height', txt_h);
			}
			
			
			//// image block sizes
			// reset
			$news_box.find('.lcnb_img, .lcnb_img_lb, .lcnb_video_lb').removeAttr('style');
			$news_box.find('.lcnb_img').children().removeAttr('style');
			
			if($news_wrap.hasClass('lcnb_horizontal')) {
				var img_h = (settings.horiz_img_h) ? settings.horiz_img_h : '';
				$news_box.find('.lcnb_img, .lcnb_img_lb, .lcnb_video_lb').css('width', '100%').css('max-width', 'none').css('height', img_h);
				$news_box.find('.lcnb_img').children().css('max-height', 'none').css('width', '100%').css('height', img_h);
			} 
			else {
				var img_h = h - ( $news_box.find('.lcnb_img').outerHeight(true) - $news_box.find('.lcnb_img').height()) -
								get_int( $news_box.css('border-top-width')) - get_int( $news_box.css('border-bottom-width'));
				
				var h_borders = $news_box.find('.lcnb_img').outerHeight(true) - $news_box.find('.lcnb_img').height();
				var w_border = get_int( $news_box.find('.lcnb_img').css('border-left-width'));

				
				if(settings.vert_img_w) {
					$news_box.find('.lcnb_img').css('width', settings.vert_img_w).css('max-width', settings.vert_img_w);	
					$news_box.find('.lcnb_img_lb, .lcnb_video_lb').css('min-width', (settings.vert_img_w - w_border)).css('max-width', (settings.vert_img_w - w_border));
				} else {
					$news_box.find('.lcnb_img').css('width', '').css('max-width', '');	
					$news_box.find('.lcnb_img_lb, .lcnb_video_lb').css('max-width', (get_int( $news_box.find('.lcnb_img').css('max-width')) - w_border));
				}
				
				// if text block is too narrow - set a very low width				
				if($news_box.find('.lcnb_contents').width() < 130) {
					$news_box.find('.lcnb_img').css('width', 85).css('max-width', 85);					
					$news_box.find('.lcnb_img_lb, .lcnb_video_lb').css('min-width', (85 - w_border)).css('max-width', (85 - w_border));
				}
		
				
				$news_box.find('.lcnb_img').css('height', '100%').css('max-height', 'none');
				$news_box.find('.lcnb_img').children().css('max-height', img_h).css('width', $news_box.find('.lcnb_img').width());
			}
			
			
			//// vertical buttons bar
			if($news_box.find('.lcnb_buttons > div').size() > 0) {
				$news_box.find('.lcnb_buttons').each(function() {
					var buttons_num = $(this).children('div').size();
					var right_h = h - get_int( $news_box.css('border-top-width')) - get_int( $news_box.css('border-bottom-width'));
					
					if( $(this).css('position') != 'absolute' ) {
						$(this).css('height', right_h);
						$(this).css('max-height',right_h); 
					}
					
					$(this).children('div').css('height', Math.floor(100 / buttons_num) + '%');
					if(buttons_num == 3) { $(this).children('div').last().css('height', '34%'); }
				});
				
				// if not btn_over_img add left position to image overlay
				if(!settings.btn_over_img) {
					var left_pos = get_int( $news_box.find('.lcnb_buttons').css('max-width'));	
					$news_box.find('.lcnb_img_lb, .lcnb_video_lb').css('left', left_pos);
				}
			}
			$news_box.css('width', w).css('height', h);
			
			
			// bottom and top bar - width and layout - narrow txt flag
			if($news_box.find('.lcnb_btm_bar, .lcnb_top_bar').size() > 0) {
				$news_box.find('.lcnb_btm_bar, .lcnb_top_bar').removeClass('lcnb_narrow_txt'); // reset narrow_txt class
				
				if($news_wrap.hasClass('lcnb_horizontal')) {
					$news_box.find('.lcnb_btm_bar, .lcnb_top_bar').css('max-width', 'none');
					$(this).find('.lcnb_contents_inner').removeAttr('style'); // reset top bar adjustment
				} 
				else {
					$news_box.find('.lcnb_contents').each(function() {
					  	var $bar = $(this).parents('.lcnb_news').find('.lcnb_btm_bar, .lcnb_top_bar');
						
						var bb_max_w = $(this).outerWidth(false);
						$bar.css('max-width', bb_max_w);

						// set layout class if too narrow because of image
						if($(this).parents('.lcnb_news').find('.lcnb_img').size() > 0 ) {
							if( $bar.height() > 28) {
								$bar.addClass('lcnb_narrow_txt');
								
								// if has side buttons bar - set a maximum width
								if($news_box.find('.lcnb_buttons > div').size() > 0) {
									var bar_max_w = $news_box.width() - get_int( $news_box.find('.lcnb_buttons').css('max-width'));
									$bar.css('max-width', bar_max_w);
								} else {
									$bar.css('max-width', 'none');	
								}
							}
							else {
								var bb_max_w = $(this).outerWidth(false);
								$bar.css('max-width', bb_max_w);
							}
						}
						
						// if is vertival and has top bar - adjust txt contents top padding
						if( $bar.hasClass('lcnb_top_bar') ) {
							$(this).find('.lcnb_contents_inner').css('padding-top', ($bar.outerHeight(false) - 3));
						} else {
							$(this).find('.lcnb_contents_inner').removeAttr('style');	
						}
					});
				}
			}
		
			return true;
		}
		
		
		// adjust news image size and position
		var news_img_adjust = function() {
			$lcnb_wrap_obj.find('.lcnb_news').each(function(i) {
				if( $(this).find('.lcnb_img').size() > 0 ) {
					var $news = $(this);
					var $img_wrap = $(this).find('.lcnb_img');
					var $img = $img_wrap.find('img');
					
					$('<img/>').bind("load",function(){ 
						var wrap_w = $img_wrap.width();
						var wrap_h = $img_wrap.height();
						var img_w = this.width;
						var img_h = this.height;
						
						// reset css
						$img.removeAttr('style');
						
						// difference allowed to stretch
						var allow_diff = 100;
						
						// if both sides are bigger
						if(img_h > wrap_h && img_w > wrap_w) {
							
							var ratio = Math.max(wrap_w/img_w, wrap_h/img_h);
							var new_w = Math.ceil(img_w * ratio);
							var new_h = Math.ceil(img_h * ratio);
							
							var t_offset = Math.floor( (new_h - wrap_h) / 2) * -1;
							var l_offset = Math.floor( (new_w - wrap_w) / 2) * -1; 
							
							if(t_offset > 0) {t_offset = 0;}
							if(l_offset > 0) {l_offset = 0;}
							
							$img.css('width', new_w).css('height', new_h).css('margin-left', l_offset).css('margin-top', t_offset);
						}
						
						
						// if sides are bigger or the smallest one is similar to the wrapper
						else if (
							(img_w >= wrap_w && img_h < wrap_h && img_h + allow_diff >= wrap_h) || 
							(img_h > wrap_h && img_w < wrap_w && img_w + allow_diff >= wrap_w) || 
							(img_h < wrap_h && img_w < wrap_w && img_w + allow_diff >= wrap_w && img_h + allow_diff >= wrap_h)
						) {
							var ratio = Math.max(wrap_w/img_w, wrap_h/img_h);
							var new_w = Math.ceil(img_w * ratio);
							var new_h = Math.ceil(img_h * ratio);	
							
							var t_offset = Math.floor( (new_h - wrap_h) / 2) * -1;
							var l_offset = Math.floor( (new_w - wrap_w) / 2) * -1;
							if(l_offset > 0) {l_offset = 0;}
								
							$img.css('width', new_w).css('height', new_h).css('margin-left', l_offset).css('margin-top', t_offset);
						}
						
						
						// if only one side is bigger
						else if(img_w > wrap_w && img_h <= wrap_h) {	
							$img.css('max-width', '100%').css('max-height', '').css('margin-left', 0).css('margin-top', 0);;	
						}
						else if(img_h > wrap_h && img_w <= wrap_w) {
							$img.css('max-height', '100%').css('max-width', '').css('margin-left', 0).css('margin-top', 0);;	
						}
						
						
						// basic position
						else {
							var t_margin = Math.floor( (wrap_h - img_h) / 2)
							
							$img.css('width', 'auto').css('height', 'auto').css('margin-top', t_margin);	
							if(settings.lightbox && $news.find('.lcnb_video_lb').size() == 0) { 
								$img_wrap.addClass('lcnb_no_lightbox'); 
							}
						}
						
						// remove the loader and show
						if( !$img_wrap.hasClass('lcnb_shown_img') ) {
							$img_wrap.addClass('lcnb_shown_img');
						}
					}).attr('src', $img.attr('src'));	
				}
			});
		}
			
		
		// news text lenght control - text shortening
		var news_txt_shortening = function() {
			var $news_wrap = $lcnb_wrap_obj.find('.lcnb_wrap');
			var settings = $lcnb_wrap_obj.data('lcnb_settings');
			var vars = $lcnb_wrap_obj.data('lcnb_vars');

			$lcnb_wrap_obj.find('.lcnb_news').each(function() {
				var $news_box = $(this);
				var nid = $(this).attr('rel');
				
				// clean empty elements in description
				$news_box.find('.lcnb_txt *:empty').not('br, img').remove();

				// set global var with orig texts
				var $news_txt = $news_box.find('.lcnb_txt');		
				if(typeof(vars.lcnb_orig_texts[nid]) == 'undefined') {vars.lcnb_orig_texts[nid] = $news_txt.html();}

				// reset
				$news_box.find('.lcnb_txt').html(vars.lcnb_orig_texts[nid]).removeClass('lcnb_shorten');

				// horizontal - calculate sizes
				if($news_wrap.hasClass('lcnb_horizontal')) {
					var wrap_h = $news_box.outerHeight(false) - 
								 ( get_int( $news_box.find('.lcnb_contents').css('padding-top')) + get_int( $news_box.find('.lcnb_contents').css('padding-bottom')) );

					if($news_box.find('.lcnb_img').size() > 0) {
						wrap_h = wrap_h - $news_box.find('.lcnb_img').outerHeight(true);	
					}
					if($news_box.find('.lcnb_btm_bar, .lcnb_top_bar').size() > 0) {
						wrap_h = wrap_h - $news_box.find('.lcnb_btm_bar, .lcnb_top_bar').outerHeight(false);	
					}
					
					var txt_h = $news_box.find('.lcnb_contents').height();
				}
				
				// vertical - calculate sizes
				else {
					var wrap_h = parseFloat( $news_box.find('.lcnb_contents').height());
					var txt_h = parseFloat( $news_box.find('.lcnb_contents_inner').outerHeight(false));
					
					if($news_box.find('.lcnb_btm_bar, .lcnb_top_bar').size() > 0) {
						wrap_h = wrap_h - $news_box.find('.lcnb_btm_bar, .lcnb_top_bar').outerHeight(false);
					}
				}
					
				// if is higher
				if(wrap_h < txt_h) {
					// search news author
					if( $news_box.find('.lcnb_txt .lcnb_author').size() > 0 ) {
						var author = $news_box.find('.lcnb_txt .lcnb_author').clone().wrap('<div>').parent().html();
						$news_box.find('.lcnb_txt .lcnb_author').remove();
					} else  {
						var author = '';
					}

					// leave only paragraphs and links to avoid slowdowns
					$news_box.find('.lcnb_txt *').not('a, p, br').each(function() {
						var content = $(this).contents();
						$(this).replaceWith(content);
					});
					
					// clean the attribues
					$news_box.find('.lcnb_txt *').lcnb_remove_all_attr();
					
					var orig_contents = $news_box.find('.lcnb_txt').html();
					var exploded = orig_contents.split(' ');
					var new_contents = '';
					var right_h_txt = '';
					
					var txt_h = 0;
					var a = 0;

					while(txt_h < wrap_h && a < exploded.length) {
						if( typeof(exploded[a]) != 'undefined') {
							right_h_txt = new_contents;
							new_contents = new_contents + exploded[a] + ' ';	
							
							// append and clean	
							$news_box.find('.lcnb_txt').html(author + new_contents + '<span class="lcnb_read_more">'+ settings.read_more_txt +'</span>');	
							
							// remove duplicated <br> and <br> after a paragraph
							for(x=0; x<2; x++) {
								// if first child is BR - remove it
								if($news_box.find('.lcnb_txt > *:first').is('br')) {
									$news_box.find('.lcnb_txt > *:first').remove();	
								}
								
								$news_box.find('.lcnb_txt br').each(function() {
									if( $(this).next().is('br') ) {
										$(this).next().remove();	
									}
									if( $(this).prev().is('p')) {
										$(this).remove();
									}
								});
							}
							
							// remove BR before the "read more" text
							while( $news_box.find('.lcnb_txt').html().indexOf('<br\> <span class="lcnb_read_more">') != -1 ) {
								$news_box.find('.lcnb_read_more').prev().remove();	
							}

							txt_h = ($news_wrap.hasClass('lcnb_horizontal')) ? $news_box.find('.lcnb_contents').height() : $news_box.find('.lcnb_contents_inner').height();
							a++;
						}
					}
					
					
					// check unclosed tags 
					var tags = ['a', 'p'];
					$.each(tags, function(i, v) {
						var open_count = right_h_txt.match('<'+v, 'g');  
						var close_count = right_h_txt.match('</'+v, 'g');
						
						if(open_count != null) {
							if(open_count != null && close_count == null || open_count.length > close_count.length) {
								right_h_txt = right_h_txt + '</'+ v +'>';
							}
						}
						
						if(i == (tags.length - 1)) {
							$news_box.find('.lcnb_txt').html(author + right_h_txt + '<span class="lcnb_read_more">'+ settings.read_more_txt +'</span>');	
							$news_box.find('.lcnb_txt *:empty').not('br').remove();
							
							// remove duplicated <br> and <br> after a paragraph
							for(x=0; x<2; x++) {
								// if first child is BR - remove it
								if($news_box.find('.lcnb_txt > *:first').is('br')) {
									$news_box.find('.lcnb_txt > *:first').remove();	
								}
								
								$news_box.find('.lcnb_txt br').each(function() {
									if( $(this).next().is('br') ) {
										$(this).next().remove();	
									}
									if( $(this).prev().is('p')) {
										$(this).remove();
									}
								});
							}
							
							// remove BR before the "read more" text
							while( $news_box.find('.lcnb_txt').html().indexOf('<br\> <span class="lcnb_read_more">') != -1 ) {
								$news_box.find('.lcnb_read_more').prev().remove();	
							}
						}
					});

					// last P tag fix
					$news_box.find('.lcnb_txt p').last().css('display', 'inline');

					// save the real text and add class
					$news_txt.addClass('lcnb_shorten');
				}
			});
		}
			
		
		// remove all the tags attributes except the link href and target
		$.fn.lcnb_remove_all_attr = function() {
			return this.each(function() {
				var attributes = $.map(this.attributes, function(item) {
				  return item.name;
				});
				
				var obj = $(this);
				$.each(attributes, function(i, item) {
					if( item != "href" && item != "target") {
						obj.removeAttr(item);
					}
				});
			});
		}
		
			
		// dynamic box layout
		var dynamic_layout = function() {
			var settings = $lcnb_wrap_obj.data('lcnb_settings');
			var $news_wrap = $lcnb_wrap_obj.find('.lcnb_wrap');
			var min_w = (settings.min_horiz_w < settings.min_news_w) ? settings.min_news_w : settings.min_horiz_w;
	
			// horizontal to vertical
			if($news_wrap.width() < min_w) {
				if($news_wrap.hasClass('lcnb_horizontal')) {
					$news_wrap.removeClass('lcnb_horizontal').addClass('lcnb_vertical');	
					vars.lcnb_news_to_show = settings.news_per_time;
					if(settings.touchswipe) {lcnb_touchswipe(true);}
				}
			}
			
			// vertical to Horizontal
			else {
				if(settings.layout == 'horizontal' && !$news_wrap.hasClass('lcnb_horizontal')) {
					$news_wrap.removeClass('lcnb_vertical').addClass('lcnb_horizontal');
					vars.lcnb_news_to_show = settings.news_per_time;
					if(settings.touchswipe) {lcnb_touchswipe(true);}
				}
			}
			
			return true;
		}
			
		
		// get the current news box size (with margins)
		var get_box_size = function(subj) {
			var vars = subj.data('lcnb_vars');
			var $news_wrap = $('.lcnb_wrap', subj);
			var $news_box = $('.lcnb_news', subj);
			
			// box sizes
			if($news_wrap.hasClass('lcnb_horizontal')) {
				var size = vars.lcnb_news_w + get_int( $news_box.css('margin-left')) + get_int( $news_box.css('margin-right')); 
				if($news_wrap.hasClass('lcnb_uniblock')) {size = size - get_int( $news_wrap.find('.lcnb_news').css('border-left-width'));}
			} 
			else {
				var size = vars.lcnb_news_h + get_int( $news_box.css('margin-top')) + get_int( $news_box.css('margin-bottom')); 
				if($news_wrap.hasClass('lcnb_uniblock')) {size = size - get_int( $news_wrap.find('.lcnb_news').css('border-top-width'));}	 
			}
			
			return size;
		}	
		

		// news slide
		lcnb_news_slide = function(subj, direction) {
			var vars = subj.data('lcnb_vars');
			var settings = subj.data('lcnb_settings');
			var $news_wrap = $('.lcnb_wrap', subj);
			
			if(vars.lcnb_news_to_show < vars.lcnb_news_number) {
				
				// infinite carousel management
				if(settings.carousel && vars.lcnb_news_to_show < vars.lcnb_news_number) {

					// if is the first time - save the boxes code to be appended
					if(vars.lcnb_carousel_obj === false) {
						vars.lcnb_carousel_obj = [];
						
						$news_wrap.find('.lcnb_news').each(function(i, v) {
							vars.lcnb_carousel_obj[i] = $(this).clone().wrap('<div>').parent().html();
						});	
					}
					var tot_news = vars.lcnb_carousel_obj.length - 1;
					
					// perform
					if(direction == 'next') {
						var first_rel = get_int( $news_wrap.find('.lcnb_news').not('.lcnb_cloned_n').first().attr('rel'));
						$news_wrap.find('.lcnb_news').not('.lcnb_cloned_n').first().addClass('lcnb_cloned_n');
						
						if($news_wrap.find('.lcnb_cloned_p[rel="'+ first_rel +'"]').size() > 0) {
							$news_wrap.find('.lcnb_news[rel="'+ first_rel +'"]').not('.lcnb_cloned_n').first().removeClass('lcnb_cloned_p');
						} else {
							$news_wrap.find('.lcnb_inner').append( vars.lcnb_carousel_obj[ first_rel ] );
						}
					}
					else {	
						var last_rel = get_int( $news_wrap.find('.lcnb_news').not('.lcnb_cloned_p').last().attr('rel'));
						$news_wrap.find('.lcnb_news').not('.lcnb_cloned_p').last().addClass('lcnb_cloned_p');
						
						if($news_wrap.find('.lcnb_cloned_n[rel="'+ last_rel +'"]').size() > 0) {
							$news_wrap.find('.lcnb_cloned_n[rel="'+ last_rel +'"]').last().removeClass('lcnb_cloned_n');
						} else {
							$news_wrap.find('.lcnb_inner').prepend( vars.lcnb_carousel_obj[ last_rel ] );
						}

						// add dynamically the margin to be smooth
						if(vars.lcnb_show_offset == 0) {
							var size = get_box_size(subj);
							var pos_subj = ( $('.lcnb_wrap', subj).hasClass('lcnb_horizontal')) ? 'left' : 'top';
							
							var new_offset = $('.lcnb_inner', subj).lcnb_get_pos(pos_subj) - size;
							$('.lcnb_inner', subj).lcnb_set_pos(pos_subj, new_offset);
						}
					}
					
					$news_wrap.find('.lcnb_news').lcnb_clone_fix(vars);
				}
				
				// calculate the offset and execute
				if(direction == 'next') {
					if( (vars.lcnb_news_to_show + vars.lcnb_show_offset) < vars.lcnb_news_number || settings.carousel) {
						vars.lcnb_show_offset = vars.lcnb_show_offset + 1;
					} 
					else { // return to the first news
						vars.lcnb_show_offset = 0;
					}
				}
				else {
					if(!settings.carousel) {
						vars.lcnb_show_offset = (vars.lcnb_show_offset <= 0) ? 0 :  vars.lcnb_show_offset - 1;
					} else {
						vars.lcnb_show_offset = 0 + $news_wrap.find('.lcnb_cloned_n').size();
					}
				}

				// if is expanded
				if(vars.lcnb_is_expanded) {
					var $wrapper = $('.lcnb_inner_wrapper', subj);
					
					if(direction == 'next') {
						var $next = $wrapper.find('.expanded').next('.lcnb_news');	
						expand_news($wrapper, $next, true);
					} else {
						var $prev = $wrapper.find('.expanded').prev('.lcnb_news');	
						expand_news($wrapper, $prev, true);
					}
				}

				if(!settings.carousel) {
					arrows_visibility(subj);
				}	
				news_offset(subj);
				
				
				// if carousel - remove the cloned element
				if(settings.carousel && vars.lcnb_news_to_show < vars.lcnb_news_number ) {
					if(typeof(carousel_timeout) != 'undefined') {clearTimeout(carousel_timeout);}
					
					carousel_timeout = setTimeout(function() {
						$news_wrap.find('.lcnb_cloned_n, .lcnb_cloned_p').remove();

						vars.lcnb_show_offset = 0;
						news_offset(subj, true);
						
					}, (settings.animation_time + 30)); 
				}
			}
		}
		
		
		// adjust the news wrapper offset
		var news_offset = function(subj, no_animation) {
			var vars = subj.data('lcnb_vars');
			var settings = subj.data('lcnb_settings');
			
			var $news_wrap = $('.lcnb_wrap', subj);
			var $news_box = $('.lcnb_news', subj);
			var size = get_box_size(subj);
			
			// calculate
			var px_offset = Math.ceil(size * vars.lcnb_show_offset);
			if(px_offset != 0) {px_offset = px_offset * -1;}

			if(!settings.carousel) {
				// avoid too much offset passing from horizontal to vertical
				if($news_wrap.hasClass('lcnb_vertical') && $lcnb_wrap_obj.find('.lcnb_inner').lcnb_get_pos('top') == 0 ) {
					var max_offset = (size * (vars.lcnb_news_number - vars.lcnb_news_to_show)) * -1;
					if (px_offset < max_offset) {px_offset = max_offset;}
				}
	
				// avoid too much offset passing from vertical to horizontal
				if($news_wrap.hasClass('lcnb_horizontal') && $lcnb_wrap_obj.find('.lcnb_inner').lcnb_get_pos('left') == 0 ) {
					var max_offset = (size * (vars.lcnb_news_number - vars.lcnb_news_to_show)) * -1;
					if (px_offset < max_offset) {px_offset = max_offset;}
				}
			}
	
			// animation easing
			var easing = (settings.slideshow_time == 0 && vars.lcnb_is_playing) ? 'linear' : 'swing';

			// animation time
			var duration = (typeof(no_animation) != 'undefined') ? 0 : settings.animation_time; 

			// animation type - slide
			if($news_wrap.hasClass('lcnb_horizontal')) {
				$('.lcnb_inner', subj).lcnb_animate_pos('left', px_offset, duration, easing);
			} 
			else {
				$('.lcnb_inner', subj).lcnb_animate_pos('top', px_offset, duration, easing);
			}		
		}
		
		
		// navigation arrows visibility
		var arrows_visibility = function(subj) {
			var vars = subj.data('lcnb_vars');
			
			if(vars.lcnb_news_to_show >= vars.lcnb_news_number) {
				$('.lcnb_next', subj).addClass('lcnb_disabled');
				$('.lcnb_prev', subj).addClass('lcnb_disabled');	
			}
			else {
				if(vars.lcnb_show_offset > 0) {
					$('.lcnb_prev', subj).removeClass('lcnb_disabled');
				} else {
					$('.lcnb_prev', subj).addClass('lcnb_disabled');
				}
				
				
				if((vars.lcnb_news_to_show + vars.lcnb_show_offset) < vars.lcnb_news_number) {
					$('.lcnb_next', subj).removeClass('lcnb_disabled');	
				} else {
					$('.lcnb_next', subj).addClass('lcnb_disabled');	
				}
			}
		}
		
		
		// opacity and position fix
		$.fn.lcnb_clone_fix = function(obj_vars) {
			var $clone = this;
			$clone.css('opacity', 1).css('filter', 'alpha(opacity=100)');
			
			$clone.each(function() {
				var img_opacity = ( get_int( $(this).find('.lcnb_img img').css('opacity')) == 0) ? 0 : 1;
				$(this).find('.lcnb_img img').css('opacity', img_opacity).css('filter', 'alpha(opacity='+ img_opacity +'00)');	
				
				if(img_opacity == 0) {news_img_adjust();}
			});
			
			//$clone.css('top', 0);
			$clone.lcnb_animate_pos('top', 0, 0);
			
			return $clone;	
		}
		
		
		// start the slideshow
		var start_slideshow = function($elem) {
			var vars = $elem.data('lcnb_vars');	
			var settings = $elem.data('lcnb_settings');	
			var add_delay = (settings.slideshow_time == 0) ? 1200 : 0;
			
			// start immediately if slideshow time == 0 
			if(settings.slideshow_time == 0) {
				setTimeout(function() {
					vars.lcnb_is_playing = true;
					lcnb_news_slide($elem, 'next');	
				}, add_delay);
			}
			
			setTimeout(function() {
				vars.lcnb_is_playing = setInterval(function() {
					lcnb_news_slide($elem, 'next');
				}, (settings.slideshow_time + settings.animation_time));	
			}, add_delay + 10);
		}
		
		
		// stop the slideshow
		var stop_slideshow = function($elem, elem_is_wrapper) {
			if(typeof(elem_is_wrapper) == 'undefined') {	
				var vars = $elem.parents('.lcnb_wrap').parent().data('lcnb_vars');
				var settings = $elem.parents('.lcnb_wrap').parent().data('lcnb_settings');
			} 
			else {
				var vars = $elem.data('lcnb_vars');	
				var settings = $elem.data('lcnb_settings');
			}
			
			clearInterval(vars.lcnb_is_playing);
			vars.lcnb_is_playing = null;
			
			// limit animation time for manual changes
			if(settings.autoplay && settings.slideshow_time == 0 && settings.animation_time > 1800) {
				var orig_time = settings.animation_time;
				settings.animation_time = 1800;
				
				// return to normal animation time
				setTimeout(function() {
					settings.animation_time = orig_time;	
				}, 200);
			}
		}
		
		
		// html to tweet text 
		var html_to_tweet = function(txt) { 
			// strip text
			var $demo = $('<div/>');	
			$demo.append('<div id="lcnb_util_2" style="display: none !important;">'+ txt +'</div>');
			
			$demo.find("*").each(function() {
				var content = $(this).contents();
				$(this).replaceWith(content);
			});
			
			// get the short text
			var short_txt = $demo.text().substring(0, 117);
			return encodeURIComponent(short_txt).replace(/'/g,"\\'");
		}
		
		
		// social share code
		var social_share_code = function share(type, v) {
			var settings = $lcnb_wrap_obj.data('lcnb_settings');
			
			// normalize code and remove author
			var $fc = $('<div />').append(v.txt);
			$fc.find('.lcnb_author').remove();
			$fc.find('*').not('a').each(function() {
				var content = $(this).contents();
				$(this).replaceWith(content);
			});	
			var s_txt_orig = $fc.html();
			
			var s_title = encodeURIComponent(v.title).replace(/'/g,"\\'");
			var s_txt = encodeURIComponent( s_txt_orig.substring(0,1000) ).replace(/'/g,"\\'");
			var s_link = (typeof(v.s_link) == 'undefined' || v.s_link == '') ? encodeURIComponent(location.href) : encodeURIComponent(v.s_link);
			var s_fb_image = (typeof(v.img) == 'undefined' || v.img == '') ? '' : '&picture='+ settings.fb_share_fix +'?u='+encodeURIComponent(v.img).replace(/'/g,"\\'");
			
			var code = '<div class="lcnb_social_trigger noSwipe"><ul class="lcnb_social_box">';
			
			// fb
			code += '<li class="lcnb_share_fb" title="share on Facebook" onClick="window.open(\'https://www.facebook.com/dialog/feed?app_id=425190344259188&display=popup&name='+ s_title +'&description='+ s_txt +'&nbsp;'+ s_fb_image +'&link='+ s_link +'&redirect_uri=http://www.lcweb.it/lcis_redirect.php\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');" ontouchstart="window.open(\'https://www.facebook.com/dialog/feed?app_id=425190344259188&display=popup&name='+ s_title +'&description='+ s_txt +'&nbsp;'+ s_fb_image +'&link='+ s_link +'&redirect_uri=http://www.lcweb.it/lcis_redirect.php\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');"></li>';
			
			// twitter
			if(type == 'twitter') { // retweet code
				code += '<li class="lcnb_share_tw lcnb_retweet" title="share on Twitter" onClick="window.open(\'https://twitter.com/intent/retweet?tweet_id='+ v.tweet_id +'&amp;via='+ v.user_id +'\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');" ontouchstart="window.open(\'https://twitter.com/intent/retweet?tweet_id='+ v.tweet_id +'&amp;via='+ v.user_id +'\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');"></li>';
			} 
			else {
				code += '<li class="lcnb_share_tw" title="share on Twitter" onClick="window.open(\'https://twitter.com/share?text='+ html_to_tweet(s_txt_orig) +'&amp;url='+ s_link +'\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');" ontouchstart="window.open(\'https://twitter.com/share?text='+ html_to_tweet(s_txt_orig) +'&amp;url='+ s_link +'\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');"></li>';
			}
			
			// google plus
			if(typeof(v.s_link) != 'undefined' && v.s_link != '') {
				code += '<li class="lcnb_share_gg" title="share on Google+" onClick="window.open(\'https://plus.google.com/share?url='+ s_link +'\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');" ontouchstart="window.open(\'https://plus.google.com/share?url='+ s_link +'\',\'sharer\',\'toolbar=0,status=0,width=548,height=325\');"></li>';
			}
			
			code += '</ul>';
			
			return code + '</ul></div>';
		}
		
		
		// vertical button bar - toggle news socials
		$.fn.lcnb_btn_bar_toggle_socials = function(action) {
			return this.each(function() {
				var $sb = $(this);
				var $nb = $(this).parents('.lcnb_news');
				var $flap = $(this).find('.lcnb_social_box');				
				var offset = $(this).width();
				
				if(action == 'show' && $flap.is(':hidden')) {
					$flap.css('width', (36 * $flap.children().size())); // social block width + 1px border
					
					var border_w = get_int( $sb.parent('.lcnb_buttons').children().first().css('border-bottom-width'));
					var h = $sb.height() + (border_w * 2);
					if(border_w > 0) {$flap.css('top', (border_w * -1));}
					
					$sb.addClass('socials_shown');
					$flap.css('display', 'inline-block').css('height', h).lcnb_set_pos('left', ($flap.width() * -1)).lcnb_animate_pos('left', offset, 400, 'linear');
				}
				else {
					var w = $flap.width(); 
					var left = w * -1;

					$flap.lcnb_animate_pos('left', left, 400, 'linear');
					
					setTimeout(function() {
						$sb.removeClass('socials_shown');
						$flap.hide();
					}, 400);
				}
			});
		}

		
		// toggle socials 
		$.fn.lcnb_toggle_socials = function(action) {
			return this.each(function() {
				var $sb = $(this);
				var $nb = $(this).parents('.lcnb_news');
				var $flap = $(this).find('.lcnb_social_box');				

				// for bottom bar and expanded
				if( $sb.parent().hasClass('lcnb_btm_bar') || $sb.parents('.lcnb_wrap ').find('.lcnb_exp_data').size() > 0) {
					var correct_pos = 25 + (29 * $flap.children('li').size());
					
					if(action == 'show' && $flap.is(':hidden')) {
						$flap.parent().addClass('socials_shown');
						$flap.show();
	
						// set bottom position the first time
						if( get_int($flap.css('bottom')) == 9) {
							$flap.css('bottom', correct_pos + 5);	
						}
						
						$nb.css('overflow', 'visible').css('z-index', 60); // avoid hidden parts on small boxes
						$flap.clearQueue().animate({'opacity': 1, 'bottom': correct_pos}, 200);
					}
					else {
						$flap.clearQueue().animate({'opacity': 0, 'bottom': correct_pos + 5}, 200);
						
						setTimeout(function() {
							$nb.css('overflow', 'hidden').css('z-index', '');
							$flap.parent().removeClass('socials_shown');
							$flap.hide();
						}, 200);
					}
				}
				
				// for top bar
				else {
					var correct_pos = -11 + $sb.outerHeight();
					
					if(action == 'show' && $flap.is(':hidden')) {
						$flap.parent().addClass('socials_shown');
						$flap.show();
	
						// set top position the first time
						if( get_int($flap.css('top')) == 9) {
							$flap.css('top', correct_pos + 5);	
						}
						
						$nb.css('overflow', 'visible').css('z-index', 60); // avoid hidden parts on small boxes
						$flap.clearQueue().animate({'opacity': 1, 'top': correct_pos}, 200);
					}
					else {
						$flap.clearQueue().animate({'opacity': 0, 'top': correct_pos + 5}, 200);
						
						setTimeout(function() {
							$nb.css('overflow', 'hidden').css('z-index', '');
							$flap.parent().removeClass('socials_shown');
							$flap.hide();
						}, 200);
					}	
				}
			});
		}
		
		
		// toggle socials on click
		$('.lcnb_social_trigger').unbind('click');
		$lcnb_wrap_obj.delegate('.lcnb_social_trigger', vars.lcnb_event_type, function() {
			var $subj = $(this);
			if(typeof(lcnb_one_click) != 'undefined') {clearTimeout(lcnb_one_click);}
			
			lcnb_one_click = setTimeout(function() {
				stop_slideshow($subj);
				
				// if button bar
				if($subj.parent().hasClass('lcnb_buttons')) {
					if($subj.find('.visible_socials').size() > 0) {
						$subj.lcnb_btn_bar_toggle_socials('hide');
					} else {
						$subj.lcnb_btn_bar_toggle_socials('show');	
						
						// hide other ones
						$('.socials_shown', $lcnb_wrap_obj).not($subj).lcnb_btn_bar_toggle_socials('hide');
					}	
				}
				
				// bottom bar
				else {
					if($subj.find('.visible_socials').size() > 0) {
						$subj.lcnb_toggle_socials('hide');
					} else {
						$subj.lcnb_toggle_socials('show');	
						
						// hide other ones
						$('.socials_shown', $lcnb_wrap_obj).not($subj).lcnb_toggle_socials('hide');
					}	
				}
			}, 5);
		});
	
	
		// expand news
		var expand_news = function($wrapper, $news, nav_trigger) {
			var vars = $wrapper.parents('.lcnb_wrap').parent().data('lcnb_vars');
			var settings = $wrapper.parents('.lcnb_wrap').parent().data('lcnb_settings');
			
			if(!vars.lcnb_is_expanded || typeof(nav_trigger) != 'undefined') {
				vars.lcnb_is_expanded = true;	
				
				// exp img management - position
				var exp_img_pos = (typeof($news.attr('lcnb_exp_img_pos')) != 'undefined') ? $news.attr('lcnb_exp_img_pos') : settings.exp_main_img_pos;
				
				// set expanded class
				$wrapper.find('.lcnb_news').removeClass('expanded');
				$news.addClass('expanded');
				
				// if is changing displayed news
				if(typeof(nav_trigger) != 'undefined') { 
					$wrapper.find('.lcnb_exp_block').last().css('z-index', 150).fadeTo(300, 0);

					setTimeout(function() {
						$wrapper.find('.lcnb_exp_block').last().remove();
					}, 300);
				}
				
				// image block
				var has_img = ($news.find('.lcnb_img').size() > 0) ? true : false;

				// force side img hiding
				if(exp_img_pos == 'hidden' || exp_img_pos == 'inside') { 
					has_img = false;
				}
				var img = (has_img) ? '<div class="lcnb_exp_img_wrap">'+ $news.find('.lcnb_img').html() +'</div>' : '';
				var fulltxt_class = (!has_img) ? 'lcnb_only_text' : ''; // fulltext class if image does not exists
				
				// clean image block
				if(has_img && img) {
					var $img_code = $(img);

					// remove no-lightbox elements 
					if($news.find('.lcnb_img').hasClass('lcnb_no_lightbox')) { 
						$img_code.find('.lcnb_img_lb, .lcnb_video_lb').remove();
					} 
					img = $img_code.clone().wrap('<div>').parent().html();
				}
				
				// data block
				var socials = (settings.social_share) ? $news.find('.lcnb_social_trigger').clone().removeAttr('style').wrap('<div>').parent().html() : '';
				var link = ($news.find('.lcnb_link').size() > 0) ? $news.find('.lcnb_link').clone().removeAttr('style').wrap('<div>').parent().html() : '';
				var date = ($news.find('.lcnb_btn_time').not('.lcnb_rm_btn_wrap').size() > 0) ? '<div class="lcnb_exp_date">' + $news.find('.lcnb_btn_time').html() + '</div>' : '';
				var data_block = '<div class="lcnb_exp_data">' + date + socials + link + '</div>';
				
				// text box
				var title = ($news.find('.lcnb_title').size() > 0) ? $news.find('.lcnb_title').clone().removeAttr('style').wrap('<div>').parent().html() : '';
				var txt = vars.lcnb_orig_texts[ $news.attr('rel') ].replace(/fake-src="/g, 'src="');
				
				// if main image to put inside - prepend
				if(exp_img_pos == 'inside' && $news.find('.lcnb_img').size() > 0) {
					txt = '<p><img src="'+ $news.find('.lcnb_img img').attr('src') +'" class="lcnb_exp_main_img" /></p>' + txt;	
				}
				
				// advanced images management for expanded layout
				if(settings.manage_exp_images) {txt = txt_img_management(txt, settings, $news);}
	
				// append the expanded block
				var exp_block = '<div class="lcnb_exp_block noSwipe '+ fulltxt_class +'">' + 
								'<span class="lcnb_close"></span>'+ img +'<div class="lcnb_exp_txt">'+ title + txt + data_block +'</div><div style="clear:both;"></div></div>';
				$wrapper.css('position', 'relative').prepend(exp_block);
				
				// expanded initial height
				if(settings.boxed_news) {
					var $obj = $wrapper.find('.lcnb_exp_block').first();
					
					var bn_margin = get_int($obj.css('margin-top')) + get_int($obj.css('margin-bottom'));
					var exp_h = settings.height - bn_margin;
				} else {
					var exp_h = settings.height;
				}
				$wrapper.find('.lcnb_exp_block').first().css('min-height', exp_h);
				
				// if has images, trigger preloader
				if( $wrapper.find('.lcnb_exp_txt:first img').size() > 0 ) {
					exp_img_loading( $wrapper.parents('.lcnb_wrap') );
				}
				
				if(has_img) {
					// clean cloned inline css 
					var $img_wrap = $wrapper.find('.lcnb_exp_img_wrap:first img').parent();
					$img_wrap.css('height', 'auto').css('max-height', 'none');
					$img_wrap.find('img').removeAttr('style').css('opacity', 0); // hide image until everything is created to avoid bad sizing on opening
					$wrapper.find('.lcnb_exp_img_wrap .lcnb_img_lb, .lcnb_exp_img_wrap .lcnb_video_lb').removeAttr('style').removeClass('lcnb_expand_trig lcnb_linked_img');
				}

				// hide news
				if(typeof(nav_trigger) == 'undefined') {
					$wrapper.find('.lcnb_news').parent().fadeTo(500, 0);
					$wrapper.find('.lcnb_news').lcnb_animate_pos('top', '80%', 500);
				}
				
				// show the block
				setTimeout(function() {
					$wrapper.find('.lcnb_exp_block').stop().animate({'opacity': 1, 'top': 0}, 400);
					
					setTimeout(function() {
						man_expanded_layout( $wrapper.parents('.lcnb_wrap') );
					}, 200);
				}, 100);
				
				// show the text
				setTimeout(function() {
					$wrapper.find('.lcnb_exp_txt').fadeIn(200);
				}, 400);
			}
		}
		$lcnb_wrap_obj.delegate('.lcnb_btn_expand, .lcnb_expand_trig', vars.lcnb_event_type, function() {
			var $news = $(this).parents('.lcnb_news');
			var $wrapper = $(this).parents('.lcnb_inner_wrapper');
			
			stop_slideshow( $(this));
			
			// close any social box
			if( $wrapper.find('.lcnb_buttons').size() > 0) { // if button bar
				$wrapper.find('.socials_shown').lcnb_btn_bar_toggle_socials('hide');
			}
			else { // bottom bar
				$wrapper.find('.socials_shown').lcnb_toggle_socials('hide');
			}
			
			var delay = ($wrapper.find('.socials_shown').size() > 0) ? 250 : 0; 
			setTimeout(function() {
				expand_news($wrapper, $news);
			}, delay); 
			
			// check and adjust window offset to show beginning of expanded box
			var offset = $wrapper.offset();
			if($(window).scrollTop() > (offset.top - 20)) {
				$('html, body').animate({'scrollTop' : (offset.top - 10)}, 600, 'linear');	
			}
		});
		
		
		// advanced images management in news texts
		var txt_img_management = function(txt, settings, $news) {
			var $fake = $('<div class="outer_wrap">'+ txt +'</div>');
			
			$fake.find('img').each(function(i, v) {
				if(!$(this).parent().hasClass('outer_wrap')) {
					var $img = $(this);
					var $parent = $(this).parent();

					$fake.children().each(function() {
						if( $(this).find($img).length > 0) {
							$parent = $(this);		
							return false;
						}
					});
					$(this).remove();
					
					// get full URL for lightbox
					var code = '<div>'+ $img.clone().wrap('<div>').parent().html() +'</div>';
					if(settings.lightbox) {	
						
						// switch to display youtube/soundcloud players
						if($news.find('.lcnb_video_lb').size() > 0) {
							var lb_class = 'lcnb_video_lb';
							var lb_url = $news.find('.lcnb_video_lb').attr('data-mfp-src');
						}
						else {
							var lb_class = 'lcnb_img_lb';
							var lb_url = get_url_param($img.attr('src'), 'src');	
						}
	
						code += '<div class="'+lb_class+' noSwipe" data-mfp-src="'+ lb_url +'">' + 
							'<div class="lcnb_lb_icon"></div><div class="lcnb_lb_overlay"></div>' +
						'</div>';
					}
					$parent.before('<div class="lcnb_exp_body_img"><div>' + code + '</div></div>');
				}
				else {
					var $img = $(this);
					var code = '<div>'+ $img.clone().wrap('<div>').parent().html() +'</div>';
					
					if(settings.lightbox) {	
						var img_url = get_url_param($img.attr('src'), 'src');
						code += '<div class="lcnb_img_lb noSwipe" data-mfp-src="'+ img_url +'">' + 
							'<div class="lcnb_lb_icon"></div><div class="lcnb_lb_overlay"></div>' +
						'</div>';
					}
					$(this).replaceWith('<div class="lcnb_exp_body_img"><div>' + code + '</div></div>');	
				}
			});
			
			// clean empty elements in description
			$fake.find('*:empty').not('br, img, .lcnb_exp_body_img *').remove();
			$fake.find('.lcnb_exp_body_img img').addClass('lcnb_exp_man_img');
			
			// exp sizes for the news
			var exp_img_w = (typeof($news.attr('lcnb_exp_img_w')) != 'undefined') ? $news.attr('lcnb_exp_img_w') : settings.exp_img_w;
			var exp_img_h = (typeof($news.attr('lcnb_exp_img_h')) != 'undefined') ? $news.attr('lcnb_exp_img_h') : settings.exp_img_h;
			
			if(exp_img_h != 225) {
				$fake.find('.lcnb_exp_body_img').css('height', exp_img_h);	
			}
			
			if(exp_img_h == 'auto') {
				$fake.find('.lcnb_exp_body_img img').css('max-width', '100%').css('min-width', 0).css('max-height', '100%').css('min-height', 0);	
			}

			// sizing class
			$fake.find('.lcnb_exp_body_img').addClass('lcnb_exp_img_'+exp_img_w);
			
			return $fake.html(); 	
		}
		
		
		// manage expanded layout and newsbox height
		var man_expanded_layout = function($subj) {
			var settings = $subj.parent().data('lcnb_settings');	
			var treshold = ($subj.find('.lcnb_exp_txt:first .lcnb_exp_body_img') > 1) ? 450 : 350;
			
			// responsive class
			if( $subj.find('.lcnb_exp_txt').width() < treshold) {
				$subj.find('.lcnb_exp_block').addClass('lcnb_exp_mobile');	
				var is_mobile = true;
			} 
			else {
				$subj.find('.lcnb_exp_block').removeClass('lcnb_exp_mobile');	
				var is_mobile = false;
			}
			
			// adjust image wrapper height
			if( $subj.find('.lcnb_exp_block .lcnb_exp_img_wrap:first').size() > 0) {
				var $img_wrap = $subj.find('.lcnb_exp_block .lcnb_exp_img_wrap:first img').parent();
				$('<img />').bind("load",function(){ 
					$img_wrap.css('height', ''); // reset
					
					// control image height
					if(!is_mobile) {
						var txt_h = $subj.find('.lcnb_exp_txt').outerHeight(true);
						var exp_min_h = get_int( $subj.find('.lcnb_exp_block').css('min-height')) - ($subj.find('.lcnb_exp_block').outerHeight() - $subj.find('.lcnb_exp_block').height());
						
						var max_h = (txt_h < exp_min_h) ? exp_min_h : txt_h;
						var img_h = $img_wrap.find('img').height();
						
						if(img_h > max_h) {
							$img_wrap.css('height', max_h);
						} else {
							$img_wrap.css('height', img_h);
						}
					}
					else {
						$img_wrap.css('height', 'auto');		
					}
					
					// show image after being sized
					if( $subj.find('.lcnb_exp_img_wrap img').css('opacity') == 0 ) {
						$subj.find('.lcnb_exp_img_wrap img').fadeTo(450, 1);	
					}
					
					$img_wrap.find('img').lcnb_center_img();
				}).attr('src', $img_wrap.find('img').attr('src') );	
			}
			
			
			// wait 300ms for CSS animations
			setTimeout(function() {
				// expanded box height
				var exp_h = $subj.find('.lcnb_exp_txt').outerHeight(true) + ($subj.find('.lcnb_exp_block ').outerHeight(false) - $subj.find('.lcnb_exp_block ').height());	
				
				// if image exists
				if(is_mobile && typeof($img_wrap) != 'undefined' && $img_wrap.size() > 0) {
					exp_h = exp_h + $img_wrap.find('img').outerHeight(true);
				}
				
				// boxed news margin to show shadows
				var nb_margin = (settings.boxed_news) ? get_int($subj.css('margin-top')) + get_int($subj.css('margin-bottom')) : 0;
				
				// animate newsbox wrapper height	
				if((exp_h + nb_margin) > settings.height) {	
					if( $subj.height() != (exp_h + nb_margin) ) {
					
						// check to trigger smooth trick
						if((exp_h + nb_margin) - settings.height > 70) {
							var smooth_timing = Math.round( (((exp_h + nb_margin) - 400) / 30) * 17);
							$subj.clearQueue().animate({'height': exp_h + nb_margin}, (400 + smooth_timing));
						}
						else {
							$subj.clearQueue().animate({'height': exp_h + nb_margin}, 400);
						}
					}
				} 
				else {
					if( $subj.height() != settings.height ) {
						$subj.clearQueue().animate({'height': settings.height}, 400);
					}
				}
			}, 300);
		}
		
		
		// manage images loading on expanded mode
		var exp_img_loading = function($wrapper) {
			var exp_preload = $.makeArray();
			
			$wrapper.find('.lcnb_exp_txt:first img').each(function(i, v) {
            	var $subj = $(this);
			   
				exp_preload[i] = setTimeout(function() {
					$subj.addClass('lcnb_exp_img_preload');
		 		}, 120);

				$(this).bind("load",function(){ 
					clearTimeout(exp_preload[i]);
					
					if($subj.hasClass('lcnb_exp_man_img')) {
						if(this.width < this.height) {$subj.addClass('lcnb_portrait_img');}
						else {
							
							// IE fix on managed images with fixed height
							if(navigator.appVersion.indexOf("MSIE") != -1 || navigator.appVersion.indexOf("rv:11.") != -1) {
								if( $wrapper.find('.lcnb_exp_body_img').first().css('height') != 'auto' ) {
									var wrap_h = get_int($wrapper.find('.lcnb_exp_body_img').first().css('height')); 
									var new_w = Math.ceil( (wrap_h * this.width) / this.height);
									
									$(this).css('width', new_w);	
								}
							}
						}

						setTimeout(function() {
							$subj.lcnb_center_img();
							$subj.parents('.lcnb_exp_body_img').addClass('lcnb_loaded');
						}, 400);
					}
					
					if( $(this).hasClass('lcnb_exp_img_preload') ) {
						$(this).removeClass('lcnb_exp_img_preload');
						man_expanded_layout($wrapper);	
					}
				});
        	});
		}
		
		
		// center images in their wrapper
		$.fn.lcnb_center_img = function() {
			return this.each(function() {
				var $subj = $(this);
				var $wrap = $(this).parent();
				
				var w_diff = $subj.width() - $wrap.width();
				if(w_diff > 1) {$subj.css('margin-left', (Math.floor(w_diff/2) * -1) );} 
				else {$subj.css('margin-left', 0);}
				
				var h_diff = $subj.height() - $wrap.height();
				if(h_diff > 1) {$subj.css('margin-top', (Math.floor(h_diff/2) * -1) );} 
				else {$subj.css('margin-top', 0);}
			});
		}
		
		
		// close expanded news 
		var close_expanded = function($wrapper) {
			var settings = $wrapper.parents('.lcnb_wrap').parent().data('lcnb_settings');
			var vars = $wrapper.parents('.lcnb_wrap').parent().data('lcnb_vars');
			vars.lcnb_is_expanded = false;
			
			//// return to the original newsbox height
			var exp_h = $wrapper.find('.lcnb_exp_block').height();
			
			// check for smoother trick
			if(exp_h - settings.height > 70) {
				var timing = 500 + Math.round( (exp_h / 30) * 18);
			} else {
				var timing = 500;	
			}
			
			$wrapper.parents('.lcnb_wrap').stop().animate({'height': settings.height}, 500);
			
			// if is scroll expanded items - check and adjust window offset
			if(settings.scroll_exp_elem){
				var offset = $wrapper.offset();
				if($(window).scrollTop() > (offset.top + settings.height - 20)) {
					$('html, body').animate({'scrollTop' : (offset.top - 10)}, 600, 'linear');	
				}
			}
			
			// hide the block
			$wrapper.find('.lcnb_exp_block').clearQueue().animate({ 'top': '-' + (exp_h + 100)  }, timing);
			$wrapper.find('.lcnb_exp_block').clearQueue().animate({'opacity': 0}, 500);
			
			// show news
			setTimeout(function() {
				$wrapper.find('.lcnb_news').parent().fadeTo(400, 1);
				$wrapper.find('.lcnb_news').lcnb_animate_pos('top', 0, 400);
			}, 100);
			
			// clean
			setTimeout(function() {
				$wrapper.find('.lcnb_news').removeClass('expanded');
				$wrapper.find('.lcnb_exp_block').remove();
				$(window).trigger('resize'); // avoid bottom part cut-off
			}, 550);
			
		}
		$lcnb_wrap_obj.delegate('.lcnb_exp_block .lcnb_close', vars.lcnb_event_type, function() {
			var $wrapper = $(this).parents('.lcnb_inner_wrapper');
			close_expanded($wrapper);
		});
		

		// lightbox integration
		$lcnb_wrap_obj.delegate('.lcnb_img_lb:not(.lcnb_expand_trig, .lcnb_linked_img)', 'click ontouchstart', function() {
			stop_slideshow( $(this));
			
			var src = $(this).attr('data-mfp-src');
			var $zoom_subj = $(this).parent().find('img');
			
			$.magnificPopup.open({
			  items: {
				src: src
			  },
			  type: 'image',
			  mainClass: 'mfp-with-zoom',
			  closeOnContentClick: true,
			  image: {
				verticalFit: true
			  },
			  zoom: {
				enabled: true, 
				duration: 300,
				easing: 'ease-in-out',
				opener: function(element) {
				  return $zoom_subj;
				}
			  }
			}, 0);	
		});
		$lcnb_wrap_obj.delegate('.lcnb_video_lb:not(.lcnb_expand_trig, .lcnb_linked_img)', 'click ontouchstart', function() {
			stop_slideshow( $(this));
			var src = $(this).attr('data-mfp-src');

			$.magnificPopup.open({
			  items: {
				src: src
			  },
			  type: 'iframe',
			  iframe: {
				patterns: {
					youtube: {
					  src: '//www.youtube.com/embed/%id%?autoplay=1&rel=0'
					}
				}
			  }
			}, 0);
		});
		
		
		// touchswipe integration
		var lcnb_touchswipe = function(changing_layout) {
			if(typeof(changing_layout) != 'undefined') {
				$lcnb_wrap_obj.find('.lcnb_touchswipe .lcnb_inner_wrapper').swipe("destroy");
			}
			
			// vertical layout
			$lcnb_wrap_obj.find('.lcnb_vertical.lcnb_touchswipe .lcnb_inner_wrapper').swipe( {
				swipe:function(event, direction) {
					stop_slideshow( $(this) );
	
					if(direction == 'up') {
						lcnb_news_slide($lcnb_wrap_obj, 'next');
					}
					if(direction == 'down') {
						lcnb_news_slide($lcnb_wrap_obj, 'prev');	
					}
				},
				threshold: 80,
				allowPageScroll: "horizontal"
			});
			
			// horizontal layout
			$lcnb_wrap_obj.find('.lcnb_horizontal.lcnb_touchswipe .lcnb_inner_wrapper').swipe( {
				swipe:function(event, direction) {
					stop_slideshow( $(this) );
	
					if(direction == 'right') {
						lcnb_news_slide($lcnb_wrap_obj,'prev');
					}
					if(direction == 'left') {
						lcnb_news_slide($lcnb_wrap_obj,'next');	
					}
				},
				threshold: 80,
				allowPageScroll: "vertical"
			});	
		}
		
		
		// parseInt function with fallback for IE8
		var get_int = function(val) {
			var raw = parseInt( val );
			return (isNaN(raw)) ? 0 : raw;
		}

		
		// animate object position
		$.fn.lcnb_animate_pos = function(side, val, duration, easing) {
			return this.each(function() {
				var $obj = $(this);
				if(typeof(easing) == 'undefined') {easing = 'swing';}
				
				$obj.animate({lcnbSlide : $obj.lcnb_get_pos(side)}, 0).stop().animate({lcnbSlide : val}, {	
					easing: easing,
					duration: duration,
					step: function(now, fx) {
						$obj.lcnb_set_pos(side, now);
					}
				});
			});
		}


		// set object position for CSS2 or CSS3
		$.fn.lcnb_set_pos = function(side, val) {
			return this.each(function() {
				var $obj = $(this);
				var vars = $obj.parents('.lcnb_wrap').parent().data('lcnb_vars');
				
				if(typeof(vars) == 'undefined') {
					var vars = $lcnb_wrap_obj.data('lcnb_vars');
				}
				
				if(vars.lcnb_css3_browser) {
					if(side == 'left') {
						$obj.css('-webkit-transform', 'translate3d('+ val +'px, 0px, 0px)');	
						$obj.css('transform', 'translate3d('+ val +'px, 0px, 0px)');	
					} else {
						$obj.css('-webkit-transform', 'translate3d(0px, '+ val +'px, 0px)');	
						$obj.css('transform', 'translate3d(0px, '+ val +'px, 0px)');		
					}
				}
				else {
					$obj.css(side, val);
				}
			});
		}
		

		// get object position for CSS2 or CSS3
		$.fn.lcnb_get_pos = function(side) {
			var $obj = this;
			var val = 0;
			var vars = $obj.parents('.lcnb_wrap').parent().data('lcnb_vars');
			
			if(typeof(vars) == 'undefined') {
				var vars = $lcnb_wrap_obj.data('lcnb_vars');
			}
			
			if(vars.lcnb_css3_browser) {
				var matrix = $obj.css("-webkit-transform") || $obj.css("transform");			 
				if(matrix == 'none') {return 0;}
				
				var values = matrix.split('(')[1];
				values = values.split(')')[0];
				values = values.split(',');
				
				if(side == 'left') {
					val = Math.round(values[4]);	
				} else {
					val = Math.round(values[5]);	
				}
			}
			else {
				if(side == 'left') {
					val = parseFloat( $obj.css('left'));	
				} else {
					val = parseFloat( $obj.css('top'));	
				}
				
				if(isNaN(val)) {return 0;}
			}

			return val;
		}
		
		
		// given an url - get a specific parameter or return original url
		var get_url_param = function(url, param) {
			var results = new RegExp('[\?&]' + param + '=([^&#]*)').exec(url);
			if (results == null){
			   return url;
			}else{
			   return decodeURIComponent(results[1]) || 0;
			}
		}
		
		
		// check if browser supports CSS property
		var CSS3_transform = function() {
		  if(
		  	navigator.appVersion.indexOf("MSIE") != -1 ||
			navigator.appVersion.indexOf("rv:11.") != -1 ||
			navigator.userAgent.indexOf("Opera") != -1 ||
			(navigator.appVersion.indexOf("Safari") != -1 && (navigator.appVersion.indexOf("Version/5.") != -1 || navigator.appVersion.indexOf("Version/4.") != -1))
		  ) {return false;}
		  
		  var $obj = $('<div/>');
		  $obj.css('-webkit-transform', 'translate3d(100px, 0px, 0px)');	
		  $obj.css('transform', 'translate3d(100px, 0px, 0px)');	
		  
		  var matrix = $obj.css("-webkit-transform") || $obj.css("transform");
		  			 
		  return (matrix == 'none') ? false : true;
		}
		  
		
		// check if mobile browser
		var is_mobile = function() {
			if( /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent) ) 
			{ return true;}
			else { return false; }
		}


		////////////////////////////////////////////
		
		// next news - click event
		$('.lcnb_next').unbind('click');
		$lcnb_wrap_obj.delegate('.lcnb_next:not(.lcnb_disabled)', 'click', function() {
			var $subj = $(this);
			if(typeof(lcnb_one_click) != 'undefined') {clearTimeout(lcnb_one_click);}
			
			lcnb_one_click = setTimeout(function() {
				stop_slideshow($subj);
				lcnb_news_slide($lcnb_wrap_obj, 'next');	
			}, 5);
		});


		// prev news - click event
		$('.lcnb_prev').unbind('click');
		$lcnb_wrap_obj.delegate('.lcnb_prev:not(.lcnb_disabled)', 'click', function() {
			var $subj = $(this);
			if(typeof(lcnb_one_click) != 'undefined') {clearTimeout(lcnb_one_click);}
			
			lcnb_one_click = setTimeout(function() {
				stop_slideshow($subj);
				lcnb_news_slide($lcnb_wrap_obj, 'prev');	
			}, 5);
		});
		
		////////////////////////////////////////////
		
		
		// debounce resize to trigger only once
		var lcnb_debouncer = function($,cf,of, interval){
			var debounce = function (func, threshold, execAsap) {
				var timeout;
				
				return function debounced () {
					var obj = this, args = arguments;
					function delayed () {
						if (!execAsap) {func.apply(obj, args);}
						timeout = null;
					}
				
					if (timeout) {clearTimeout(timeout);}
					else if (execAsap) {func.apply(obj, args);}
					
					timeout = setTimeout(delayed, threshold || interval);
				};
			};
			jQuery.fn[cf] = function(fn){ return fn ? this.bind(of, debounce(fn)) : this.trigger(cf); };
		};
		lcnb_debouncer(jQuery,'lcnb_smartresize', 'resize', 100);
		
		// responsive behaviors
		$(window).lcnb_smartresize(function() {
			var vars = $lcnb_wrap_obj.data('lcnb_vars');
			var settings = $lcnb_wrap_obj.data('lcnb_settings');
			
			if(!vars.lcnb_is_playing || settings.slideshow_time >= 50) {
				dynamic_layout();
				size_boxes();
				news_img_adjust();
				
				// perform only if wrapper width is different and not expanded
				if(!vars.lcnb_is_expanded && vars.lcnb_wrap_width != $lcnb_wrap_obj.width()) {
					vars.lcnb_wrap_width = $lcnb_wrap_obj.width();
					news_offset($lcnb_wrap_obj);
					news_txt_shortening();
				}
				
				// if is expanded
				if(vars.lcnb_is_expanded) {
					man_expanded_layout( $lcnb_wrap_obj.find('.lcnb_wrap') );
					$lcnb_wrap_obj.find('.lcnb_exp_man_img').lcnb_center_img();
				}
			}
		});
		
		
		// debounce scroll to trigger only once
		lcnb_debouncer(jQuery,'lcnb_smartscroll', 'scroll', 50);
		
		// expanded news - scroll events
		$(window).lcnb_smartscroll(function() {
			var settings = $lcnb_wrap_obj.data('lcnb_settings');	
			
			if($lcnb_wrap_obj.find('.lcnb_exp_block').size() > 0 && settings.scroll_exp_elem) {
				var $subj = $lcnb_wrap_obj.find('.lcnb_exp_block').first();
				var offset = $subj.offset();
				
				if($subj.height() > settings.height + 30 && $(window).scrollTop() > (offset.top + 30)) {
					
					// for the closing button
					if($(window).scrollTop() < (offset.top + ($subj.height() - 35)) ) {
						var margin_top = Math.floor( $(window).scrollTop() - offset.top - 7); 
						$subj.find('.lcnb_close').css('margin-top', margin_top);
					} 
					
					// for the image
					if($(window).scrollTop() < (offset.top + ($subj.height() - ($subj.find('.lcnb_exp_img_wrap' ).height() - 25) )) ) {
						if($subj.hasClass('lcnb_exp_mobile')) {
							$subj.find('.lcnb_exp_img_wrap').css('margin-top', 0);
						}
						else {
							var margin_top = Math.floor( $(window).scrollTop() - offset.top); 
							$subj.find('.lcnb_exp_img_wrap').css('margin-top', margin_top);
						}
					}
				} 
				else {
					$subj.find('.lcnb_close, .lcnb_exp_img_wrap').css('margin-top', 0);
				}
			}
		});
		
		
		//////////////////////////////////////////
		
		// script and styles loader
		var assets_loader = function() {
			
			// retrieve basepath
			if( typeof(lcnb_script_basepath) == 'undefined' ) {
				if(settings.script_basepath) { 
					lcnb_script_basepath = settings.script_basepath;
				} 
				else {
					$('script').each(function(index, element) {
                        var src = $(this).attr('src');
						if( typeof(src) != 'undefined' && src.indexOf('news-box') != -1 ) {
							var src_arr = src.split('/');
							var lastEl = src_arr[src_arr.length - 1];	
							lcnb_script_basepath = src.replace(lastEl, '');
						}
                    });	
				}
			}
			
			// load theme
			if( typeof(lcnb_loaded_themes) == 'undefined' ) {lcnb_loaded_themes = $.makeArray();}
			if( $.inArray(settings.theme, lcnb_loaded_themes) == -1 ) {
				lcnb_loaded_themes.push(settings.theme);
				$('head').append('<link rel="stylesheet" href="'+ lcnb_script_basepath +'themes/'+ settings.theme +'.css">');	
			}
			
			//// load scripts
			if( typeof(lcnb_loaded_scripts) == 'undefined' ) {lcnb_loaded_scripts = $.makeArray();}
			
			// magnific popup
			if(settings.lightbox && $.inArray('magnific_popup', lcnb_loaded_scripts) == -1 && !eval("typeof magnificPopup == 'function'")) {
				lcnb_loaded_scripts.push('magnific_popup');
				$('head').append('<link rel="stylesheet" href="'+ lcnb_script_basepath +'js_assets/magnific-popup/magnific-popup-style.css">');
				$('body').append('<script src="'+ lcnb_script_basepath +'js_assets/magnific-popup/magnific-popup.min.js" type="text/javascript"></script>');	
			}
			
			// touchswipe
			if(settings.touchswipe && $.inArray('touchswipe', lcnb_loaded_scripts) == -1 && !eval("typeof swipe == 'function'")) {
				lcnb_loaded_scripts.push('touchswipe');
				$('body').append('<script src="'+ lcnb_script_basepath +'js_assets/TouchSwipe/jquery.touchSwipe.min.js" type="text/javascript"></script>');	
			}
			
			return true;
		}
		
		//////////////////////////////////////////
		//////////////////////////////////////////
		
		// set the wrapper width for responsive behavior
		vars.lcnb_wrap_width = $lcnb_wrap_obj.width();
		
		// load styles and scripts
		var result = assets_loader();
		
		//// Initialize the news box - create the html structure
		parse_inline_news();
		
		//// CLASSES
		
		// theme class
		var theme_class = 'lcnb_'+ settings.theme +'_theme';
		
		// nav_arrows class
		if(!settings.nav_arrows) {var cmd_class = '';}
		else {
			var cmd_class = 'lcnb_has_cmd lcnb_'+ settings.nav_arrows +'_cmd';
			
			if(settings.nav_arrows.match(/top/g)) {cmd_class += ' lcnb_top_cmd';}
			if(settings.nav_arrows.match(/bottom/g)) {cmd_class += ' lcnb_bottom_cmd';}
		}

		// source logos class
		var src_logo_class = (!settings.show_src_logo || settings.layout == 'horizontal') ? '' : 'lcnb_src_logo'; 
		
		// boxed news class
		var boxed_class = (!settings.boxed_news) ? 'lcnb_uniblock' : 'lcnb_boxed';
		
		// touchswipe class
		var ts_class = (!settings.touchswipe) ? '' : 'lcnb_touchswipe';
		
		var wrap_classes = theme_class +' '+ cmd_class +' '+ ts_class +' '+ src_logo_class +' '+ boxed_class;
		
		///////////////////////////////
		// structure init
		var structure = '<div class="lcnb_wrap lcnb_loading lcnb_'+settings.layout+' '+ wrap_classes +'" style="height: '+settings.height+'px;">';
		
		// command box
		if(settings.nav_arrows) {
			var disabled_class = (!settings.carousel) ? 'lcnb_disabled' : '';
			structure += '<div class="lcnb_cmd"><div class="lcnb_prev '+ disabled_class +'"><span></span></div><div class="lcnb_next"><span></span></div></div>';	
		}
		
		// inner wrapper and inner 
		structure += '<div class="lcnb_inner_wrapper"><div class="lcnb_inner" style="height: '+settings.height+'px;"></div></div>';
		
		$lcnb_wrap_obj.html(structure + '</div>');
		
		//////////////////////////////
		
		// CSS fixes for IE8 - 9
		if(typeof(lcnb_IE_css_appended) == 'undefined' && (navigator.appVersion.indexOf("MSIE 8.") != -1 || navigator.appVersion.indexOf("MSIE 9.") != -1)) {
			lcnb_IE_css_appended = true;
			$('head').append('<style>.lcnb_btm_bar .lcnb_social_box:after, .lcnb_exp_data .lcnb_social_box:after, .lcnb_top_bar .lcnb_social_box:before {display: none !important;</style>');		
		}
		
		// event type definition for mobile
		if(is_mobile()) {vars.lcnb_event_type = 'tap';}
		
		//// browser test against CSS3 3D translation
		// if carousel, autoplay and slideshow_time < 50 set to false to avoid CSS3 bug
		if((settings.carousel && settings.autoplay && settings.slideshow_time <= 50) || settings.max_news > 10) {
			vars.lcnb_css3_browser = false;	
		} else {
			vars.lcnb_css3_browser = CSS3_transform();
		}

		///////////////////////////////

		// execution
		setTimeout(function() {
			dynamic_layout();
			handle_sources();
		}, 50);

		return this;
	};	
		
	////////////////////////////////////////////
	
	// init
	$.fn.lc_news_box = function(lcnb_settings) {

		// destruct
		$.fn.lcnb_destroy = function() {
			var $elem = $(this);
			
			// clear interval
			var vars = $elem.data('lcnb_vars');
			if(vars.lcnb_is_playing) {clearInterval(vars.lcnb_is_playing); }
			
			// destroy touchswipe
			var settings = $elem.data('lcnb_settings');
			if(settings.touchswipe) {$elem.find('.lcnb_touchswipe .lcnb_inner_wrapper').swipe("destroy");}
			
			// undelegate events
			$elem.undelegate('.lcnb_social_trigger', vars.lcnb_event_type);
			$elem.undelegate('.lcnb_btn_expand', vars.lcnb_event_type);
			$elem.undelegate('.lcnb_exp_block .lcnb_close', vars.lcnb_event_type);
			$elem.undelegate('.lcnb_img_lb, .lcnb_video_lb', 'click ontouchstart');
			$elem.find('.lcnb_next, .lcnb_prev').undelegate('click');
			
			// remove stored data
			$elem.removeData('lcnb_vars');
			$elem.removeData('lcnb_settings');
			$elem.removeData('lc_newsbox');
			
			return true;
		};	
		
		
		// start slideshow
		$.fn.lcnb_start_slideshow = function() {
			var $elem = $(this);
			
			var vars = $elem.data('lcnb_vars');	
			var settings = $elem.data('lcnb_settings');	

			// start immediately if slideshow time == 0 
			if(settings.slideshow_time == 0) {
				vars.lcnb_is_playing = true;
				lcnb_news_slide($elem, 'next');	
			}
			
			vars.lcnb_is_playing = setInterval(function() {
				lcnb_news_slide($elem, 'next');
			}, (settings.slideshow_time + settings.animation_time));	
			
			return true;
		};
		
		
		// stop the slideshow
		$.fn.lcnb_stop_slideshow = function() {
			var $elem = $(this);

			var vars = $elem.data('lcnb_vars');	
			var settings = $elem.data('lcnb_settings');
			
			clearInterval(vars.lcnb_is_playing);
			vars.lcnb_is_playing = null;
			
			// limit animation time for manual changes
			if(settings.autoplay && settings.slideshow_time == 0 && settings.animation_time > 1800) {
				var orig_time = settings.animation_time;
				settings.animation_time = 1800;
				
				// return to normal animation time
				setTimeout(function() {
					settings.animation_time = orig_time;	
				}, 200);
			}
			
			return true;
		};


		// construct
		return this.each(function(){
            // Return early if this element already has a plugin instance
            if ( $(this).data('lc_newsbox') ) { return $(this).data('lc_newsbox'); }
			
            // Pass options to plugin constructor
            var news_box = new lc_NewsBox(this, lcnb_settings);
			
            // Store plugin object in this element's data
            $(this).data('lc_newsbox', news_box);
        });
	};			
	
})(jQuery);