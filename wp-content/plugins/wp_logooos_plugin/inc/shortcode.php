<?php
	
	// logooos
	function logooos_shortcode($atts, $content=null) {  
		extract(shortcode_atts( array(  
			'columns' => '5',
			'backgroundcolor' => 'transparent',
			'layout' => 'grid',
			'num' => '-1',
			'category' => '0',
			'orderby' => 'date',
			'order' => 'DESC',
			'marginbetweenitems' =>'' ,
			'tooltip' => 'disabled',
			'responsive' => 'enabled',
			'grayscale' => 'disabled',
			'border' => 'disabled',
			'bordercolor' => 'transparent',
			'borderradius' => 'logooos_no_radius',
			'autoplay' => 'true',
			'scrollduration' => '1000',
			'pauseduration' => '900',
			'buttonsbordercolor' => '#DCDCDC',
			'buttonsbgcolor' => '#FFFFFF',
			'buttonsarrowscolor' => 'lightgray',
			'hovereffect' => '',
			'hovereffectcolor' => '#DCDCDC',
			'titlefontfamily' => '',
			'titlefontcolor' => '#777777',
			'titlefontsize' => '15px',
			'titlefontweight' => 'bold',
			'textfontfamily' => '',
			'textfontcolor' => '#777777',
			'textfontsize' => '12px',
			'listborder' => 'enabled',
			'listbordercolor' => '#DCDCDC',
			'listborderstyle' => 'dashed',
			'morelinktext' => '',
			'morelinktextcolor' => ''
		), $atts));  
		
		// 	query posts
		
		$args =	array ( 'post_type' => 'logooo',
						'posts_per_page' => $num,
						'orderby' => $orderby,
						'order' => $order );
		
		if($category > 0) {
			$args['tax_query'] = array(array('taxonomy' => 'logooocategory','field' => 'id','terms' => $category ));
		}
		
		$logooos_query = new WP_Query( $args );
		
		$html='';

		if ($logooos_query->have_posts()) {
			
			// ======== Classes ======== //
			$classes='';
			
			//layout
			if($layout=='grid') {
				$classes.='logooos_grid ';
				
				//responsive
				if($responsive=='enabled') {
					$classes.='logooos_responsive ';
				}
			}
			else if($layout=='slider') {
				$classes.='logooos_slider ';
			}
			else if($layout=='list') {
				$classes.='logooos_list ';
				
				//responsive
				if($responsive=='enabled') {
					$classes.='logooos_responsive ';
				}
			}
			
			//tooltip
			if($layout!='list') {
				if($tooltip=='enabled') {
					$classes.='logooos_withtooltip ';
				}
			}
			
			//grayscale
			if($grayscale=='enabled') {
				$classes.='logooos_grayscale ';
			}
			
			//border
			if($border=='enabled') {
				$classes.='logooos_border ';
			}
			else {
				$classes.='logooos_no_border ';
			}
			
			//list border
			if($listborder=='enabled') {
				$classes.='logooos_listborder ';
			}
			
			//border radius
			$classes.=$borderradius.' ';
			
			//hover effect
			$classes.=$hovereffect.' ';
			
			
			
			// ======== Data ======== //
			
			//columns
			if($layout!='list') {
				$data='data-columns="'.$columns.'" ';
			}
			
			//margin between items
			if($layout!='list') {
				$data.='data-marginbetweenitems="'.$marginbetweenitems.'" ';
			}
			
			//hover effect
			$data.='data-hovereffect="'.$hovereffect.'" ';
			
			//hover effect color
			$data.='data-hovereffectcolor="'.$hovereffectcolor.'" ';
			
			//border color
			$data.='data-bordercolor="'.$bordercolor.'" ';
			
			if($layout == 'slider') {
				// autoplay
				$data.='data-autoplay="'.$autoplay.'" ';
				// scroll duration
				$data.='data-scrollduration="'.$scrollduration.'" ';
				// pause duration
				$data.='data-pauseduration="'.$pauseduration.'" ';
				// buttons border color
				$data.='data-buttonsbordercolor="'.$buttonsbordercolor.'" ';
				// buttons background color
				$data.='data-buttonsbgcolor="'.$buttonsbgcolor.'" ';
				
				// buttons arrows color
				if($buttonsarrowscolor == 'darkgray') {
					$data.='data-buttonsarrowscolor="logooos_darkgrayarrows" ';
				}
				else if($buttonsarrowscolor == 'lightgray') {
					$data.='data-buttonsarrowscolor="logooos_lightgrayarrows" ';
				}
				else if($buttonsarrowscolor == 'white') {
					$data.='data-buttonsarrowscolor="logooos_whitearrows" ';
				}
				
			}
			
			$html.='<div class="logooos_container"><div class="logooos '.$classes.'" '.$data.' >';
			
			$i = 0;
			
			while ($i < $logooos_query->post_count) {
			
				$post = $logooos_query->posts;
				$thumbnailsrc="";
				$href='';
				$imgSize='99%';
				$bgSize='99%';
				$link_target='_blank';
					
				if(get_post_meta($post[$i]->ID, 'link', true)!='') { 
					$href='href="http://'.get_post_meta($post[$i]->ID, 'link', true).'"';
				}
				
				if(get_post_meta($post[$i]->ID, 'imageSize', true) !='' ) {
					$imgSize=get_post_meta($post[$i]->ID, 'imageSize', true);
					$bgSize='-webkit-background-size: '.$imgSize.'; -moz-background-size: '.$imgSize.'; background-size: '.$imgSize.';';
				}
				
				// if has post thumbnail		
				if ( has_post_thumbnail($post[$i]->ID)) {
					$thumbnailsrc = wp_get_attachment_url(get_post_meta($post[$i]->ID, '_thumbnail_id', true));
				}
				
				if(get_post_meta($post[$i]->ID, 'link_target', true) !='' ) {
					$link_target=get_post_meta($post[$i]->ID, 'link_target', true);
				}
				
				
				$html.='<div class="logooos_item" data-title="'.$post[$i]->post_title.'" style="background-color:'.$backgroundcolor.'; border-color:'.$bordercolor.'">
						<a '.$href.' target="'.$link_target.'" style="'.$bgSize.'background-image:url('.$thumbnailsrc.'); ">';
				
				if($thumbnailsrc!='') {
					$html.='<img src="'.$thumbnailsrc.'" title="" style="max-width:'.$imgSize.' !important; max-height:'.$imgSize.' !important;" />';
				}
				
				if($hovereffect=='effect2') {
					$html.='<span class="logooos_effectspan"></span>';
				}
								
				$html.='</a>';
				
				
							
				$html.='</div>';
				
				if($layout=='list') {
					
					// title style
					$titleStyle='';
					
					if($titlefontfamily !='') {
						$titleStyle.='font-family:'.$titlefontfamily.'; ';
					}
					if($titlefontcolor !='') {
						$titleStyle.='color:'.$titlefontcolor.'; ';
					}
					if($titlefontsize !='') {
						$titleStyle.='font-size:'.$titlefontsize.'; ';
					}
					if($titlefontweight !='') {
						$titleStyle.='font-weight:'.$titlefontweight.'; ';
					}
					
					
					// text style
					$textStyle='';
					
					if($textfontfamily !='') {
						$textStyle.='font-family:'.$textfontfamily.'; ';
					}
					if($textfontcolor !='') {
						$textStyle.='color:'.$textfontcolor.'; ';
					}
					if($textfontsize !='') {
						$textStyle.='font-size:'.$textfontsize.'; ';
					}
					
					// text container style 
					
					$textContainerStyle = '';
					
					if($listborder =='enabled') {
					
						if($listbordercolor !='') {
							$textContainerStyle.='border-bottom-color:'.$listbordercolor.'; ';
						}
						if($listborderstyle !='') {
							$textContainerStyle.='border-bottom-style:'.$listborderstyle.'; ';
						}
						
					}
					
					$html.='<div class="logooos_textcontainer" style="'.$textContainerStyle.'">
								<div class="logooos_title" style="'.$titleStyle.'">'.$post[$i]->post_title.'</div>
								<div class="logooos_text" style="'.$textStyle.'">'.apply_filters('the_content', get_post_meta($post[$i]->ID, 'description', true));
					if($morelinktext!='' && get_post_meta($post[$i]->ID, 'link', true) !='') {
					
						$linkColor ='';
						if($morelinktextcolor != '') {
							$linkColor ='color:'.$morelinktextcolor;
						}
						
						$html.= '<a '.$href.' target="'.$link_target.'" class="logooos_morelink" style="'.$linkColor.'" >'.$morelinktext.'</a>';
					}
					
					$html.=	'	</div>
							</div>';
				}
				
				$i++;
			}
			
			$html.='</div></div>';
		}
		
		return $html;  
	}  
	add_shortcode('logooos', 'logooos_shortcode');
	
?>