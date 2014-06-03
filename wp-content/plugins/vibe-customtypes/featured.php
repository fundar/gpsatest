<?php


function thumbnail_generator($post,$featured_style,$cols,$n=100,$link=0,$zoom=0){
    $return=$read_more=$class='';
    global $vibe_options;
    
    $more = __('Read more','vibe');
    
    if(strlen($post->post_content) > $n)
                        $read_more= '<a href="'.get_permalink($post->ID).'" class="link">'.$more.'</a>';
    
    switch($featured_style){
            case 'course':
                    global $post;

                    

                   $return .='<div class="block courseitem">';
                    $return .='<div class="block_media">';
                        $return .= '<a href="'.get_permalink($post->ID).'">'.featured_component($post->ID,$cols).'</a>';
                    $return .='</div>';
                    
                    $return .='<div class="block_content">';
                    
                    $heart='';
                    $enable_likes=vibe_get_option('enable_likes');
                    
                    if(isset($enable_likes) && $enable_likes){
                        $likes=getPostMeta($post->ID,'like_count');
                        $heart .='<a class="like" id="'.$post->ID.'" rel="tooltip" data-placement="top" data-original-title="Likes"><i class="icon-heart"></i> '.(isset($likes)?$likes:'0').'</a>';
                    }

                    
                    $return .='<h4 class="block_title"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">'.$post->post_title.'</a></h4>';
                    
                    $category='';
                    if(get_post_type($post->ID) == 'course'){
                        $rating=get_post_meta($post->ID,'average_rating',true);
                        $rating_count=get_post_meta($post->ID,'rating_count',true);
                        $meta = '<div class="star-rating">';
                        for($i=1;$i<=5;$i++){

                            if(isset($rating)){
                                if($rating >= 1){
                                    $meta .='<span class="fill"></span>';
                                }elseif(($rating < 1 ) && ($rating > 0.4 ) ){
                                    $meta .= '<span class="half"></span>';
                                }else{
                                    $meta .='<span></span>';
                                }
                                $rating--;
                            }else{
                                $meta .='<span></span>';
                            }
                        }
                        $meta .= '( '.(isset($rating_count)?$rating_count:'0').' REVIEWS )</div>';

                        $free_course = get_post_meta($post->ID,'vibe_course_free',true);

                        if(isset($free_course) && $free_course && $free_course!='H'){
                            $meta .= '<strong><span class="amount">'.apply_filters('wplms_free_course_price','FREE').'</span></strong>';
                        }else{
                            $cpid=get_post_meta($post->ID,'vibe_product',true);
                            if(isset($cpid) && $cpid !=''){
                                $product = get_product($cpid );
                                $meta .= '<strong>';
                                $credits= $product->get_price_html();
                                $meta .=apply_filters('wplms_course_credits',$credits,$post->id);
                                $meta .='</strong>';
                            }
                        }
                        $meta .='<span class="clear"></span>';


                        if(function_exists('bp_course_get_instructor_avatar'))
                            $meta .= bp_course_get_instructor_avatar();
                        if(function_exists('bp_course_get_instructor'))
                            $meta .=bp_course_get_instructor();
                        
                        $st = get_post_meta($post->ID,'vibe_students',true);
                        if(isset($st) && $st !='')
                            $meta .= '<strong>'.$st.' '.__('Students','vibe').'</strong>';
                        
                        $return .= $meta;
                    }
                    
                    $return .='</div>';
                    $return .='</div>';
                break;

           case 'side':
                   $return .='<div class="block side">';
                    $return .='<div class="block_media">';
                    if(isset($link) && $link)
                        $return .='<span class="overlay"></span>';
                    if(isset($link) && $link)
                    $return .= '<a href="'.get_permalink($post->ID).'" class="hover-link hyperlink"><i class="icon-hyperlink"></i></a>';
                    $featured= getPostMeta($post->ID, 'vibe_select_featured');
                    if(isset($zoom) && $zoom && has_post_thumbnail($post->ID) )
                    $return .= '<a href="'.wp_get_attachment_url( get_post_thumbnail_id($post->ID),$cols ).'" class="hover-link pop"><i class="icon-arrows-out"></i></a>';
                    
                    
                    $return .= featured_component($post->ID,$cols);
                    
                    $category='';
                    if(get_post_type($post->ID) == 'post'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                            foreach($cats as $cat){
                            $category .= '<a href="'.get_category_link($cat->term_id ).'">'.$cat->cat_name.'</a> ';
                            }
                        }
                    }
                    
                    $return .='</div>';
                    
                    $category='';
                    if(get_post_type($post->ID) == 'post'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                            foreach($cats as $cat){
                            $category .= '<a href="'.get_category_link($cat->term_id ).'">'.$cat->cat_name.'</a> ';
                            }
                        }
                    }
                    
                    if(get_post_type($post->ID) == 'portfolio'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                             $category .= '<div class="categories">';
                             $category .= get_the_term_list( $post->ID, 'portfolio-type', ' ', ' ', '' );
                             $category .= '</div>';
                        }
                    }
                    
                    
                    $return .='<div class="block_content">';
                    $return .='<h4 class="block_title"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">'.$post->post_title.'</a></h4>';
                    $return .='<div class="date"><small>'. get_the_time('F d,Y').''.((strlen($category)>2)? ' / '.$category:'').' / '.get_comments_number( '0', '1', '%' ).' Comments</small></div>';
                    $return .='<p class="block_desc">'.custom_excerpt($n,$post->ID).'</p>';
                    $return .='</div>';
                    $return .='</div>';
                break;    
            case 'images_only':
                    $return .='<div class="block">';
                    $return .='<div class="block_media images_only">';
                    if(isset($link) && $link)
                        $return .='<span class="overlay"></span>';
                    
                    if(isset($link) && $link)
                    $return .= '<a href="'.get_permalink($post->ID).'" class="hover-link hyperlink"><i class="icon-hyperlink"></i></a>';
                    
                    if(isset($zoom) && has_post_thumbnail($post->ID) && $zoom )
                    $return .= '<a href="'.wp_get_attachment_url( get_post_thumbnail_id($post->ID),$cols ).'" class="hover-link pop"><i class="icon-arrows-out"></i></a>';
                    $return .= featured_component($post->ID,$cols);
                    $return .='</div>';
                    $return .='</div>';
                break;
            case 'testimonial':
                    $return .='<div class="block testimonials">';
                
                    $author=  getPostMeta($post->ID,'vibe_testimonial_author_name'); 
                    $designation=getPostMeta($post->ID,'vibe_testimonial_author_designation'); 
                    $image=get_the_post_thumbnail($post->ID,'full'); 
                    
                    
                    $return .= '<div class="testimonial_item style2 clearfix">
                                    <div class="testimonial-content">    
                                        <p>'.custom_excerpt($n,$post->ID).$read_more.'</p>
                                       <div class="author">
                                          '.$image.'  
                                          <h4>'.html_entity_decode($author).'</h4>
                                          <small>'.html_entity_decode($designation).'</small>
                                        </div>     
                                    </div>        
                                    
                                </div>';
                    $return .='</div>';
                break;
             case 'blogpost':
                    $return .='<div class="block blogpost">';
                    $return .= '<div class="blog-item">
                                <div class="blog-item-date">
                                    <span class="day">'.get_the_time('d').'</span>
                                    <span class="month">'.get_the_time('M').'</span>
                                </div>
                                <h4><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">'.$post->post_title.'</a></h4>
                                <p>'.custom_excerpt($n,$post->ID).'</p>
                                </div>';
                    $return .='</div>';
                break;   
             case 'listing':
                   global $vibe_options;
                    
                    $cols='medium';

                    $onsale ='';
                     $terms = wp_get_post_terms( $post->ID, 'status');
                     if(isset($terms) && is_array($terms)){
                     foreach($terms as $term){
                         $onsale = '<span class="'.$term->slug.'">'.$term->name.'</span>';
                     }}
                     
                   $return .='<div class="block listing">';
                    $return .='<div class="block_media">'.$onsale;
                    
                    if(isset($link) && $link)
                    $return .= '<a href="'.get_permalink($post->ID).'" class="hover-link hyperlink"><i class="icon-hyperlink"></i></a>';
                   // $featured= getPostMeta($post->ID, 'vibe_select_featured');
                    if(isset($zoom) && $zoom && has_post_thumbnail($post->ID) )
                    $return .= '<a href="'.wp_get_attachment_url( get_post_thumbnail_id($post->ID),$cols ).'" class="hover-link pop"><i class="icon-arrows-out"></i></a>';
                    
                    
                    $return .= featured_component($post->ID,$cols);
                    
                    
                    $featured='';
                                              if(isset($vibe_options['listing_fields']['field_type'])){
                                                  $i = array_search('featured',$vibe_options['listing_fields']['field_type']);
                                                  if(isset($i)){
                                                      $key = 'vibe_'.strtolower(str_replace(' ', '-',$vibe_options['listing_fields']['label'][$i]));
                                                      $featured = getPostMeta($post->ID,$key);
                                                  }
                                              }
                                              
                     if(isset($featured) && $featured == 1){
                         $return .= '<span class="vfeatured"><i class="icon-star" data-rel="tooltip" data-original-title="'.$vibe_options['listing_fields']['label'][$i].'" data-placement="left"></i></span>';
                     }
                     
                    
                     
                    $return .='</div>';
                    
                    $category='';
                    if(get_post_type($post->ID) == 'post'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                            foreach($cats as $cat){
                            $category .= '<a href="'.get_category_link($cat->term_id ).'">'.$cat->cat_name.'</a> ';
                            }
                        }
                    }
                    
                    
                    $return .='<div class="block_content">';
                    $return .='<h4 class="block_title"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">'.$post->post_title.'</a></h4>';
                    if(isset($vibe_options['listing_fields']['feature']) && is_array($vibe_options['listing_fields']['feature'])){
                        $return .= '<ul class="listing_fields">';
                        
                        $return .= '<li class="address_info">'.get_the_term_list( $post->ID, $vibe_options['primary_listing_parameter'], ' ', ', ', '' ).'</li>'; 
                        foreach($vibe_options['listing_fields']['feature'] as $k=>$value){
                                    $label = $vibe_options['listing_fields']['label'][$k];
                                    $class = $vibe_options['listing_fields']['class'][$k];
                                    $field = $vibe_options['listing_fields']['field_type'][$k];
                                    $key = 'vibe_'.strtolower(str_replace(' ', '-',$vibe_options['listing_fields']['label'][$k]));
                                    
                                    if($field == 'select'){
                                        $selectlabel = explode('|',$label);
                                        $vars=explode('|',$key);
                                        if(isset($vars[1])){
                                            $val=getPostMeta($post->ID,$vars[0]);
                                            $return .= '<li class="on"><label><i class="'.$class.'"></i> '.$selectlabel[0].'</label><span>'.$val.'</span></li>';
                                        }
                                    }elseif($class == 'price'){
                                       $return .= '<li class="price"><span class="currency">'.(isset($vibe_options['currency'])?'<i class="'.$vibe_options['currency'].'"></i>':'$').' '.getPostMeta($post->ID,$key).'</span></li>';  
                                       }elseif($class == 'address' || $field == 'available' || $field == 'location' || $field == 'checkbox' || $field == 'featured'){
                                    }else{
                                        $v=getPostMeta($post->ID,$key);
                                        if(!is_array($v))
                                        $return .= '<li class="on"><i class="'.$class.'"></i><label> '.$label.'</label><span>'.$v.' '.(($class == 'area')?$vibe_options['area']:'').'</span></li>'; 
                                    }
                                            
                                }
                                $return .= '</ul>';
                             }
                    $return .='</div>';
                    $return .='</div>';
                break; 
               
            default:
                   $return .='<div class="block">';
                    $return .='<div class="block_media">';
                    
                    if(isset($link) && $link)
                    $return .= '<a href="'.get_permalink($post->ID).'" class="hover-link hyperlink"><i class="icon-hyperlink"></i></a>';
                    $featured= getPostMeta($post->ID, 'vibe_select_featured');
                    if(isset($zoom) && $zoom && has_post_thumbnail($post->ID) )
                    $return .= '<a href="'.wp_get_attachment_url( get_post_thumbnail_id($post->ID),$cols ).'" class="hover-link pop"><i class="icon-arrows-out"></i></a>';
                    
                    
                    $return .= featured_component($post->ID,$cols);
                    
                    $category='';
                    if(get_post_type($post->ID) == 'post'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                            foreach($cats as $cat){
                            $category .= '<a href="'.get_category_link($cat->term_id ).'">'.$cat->cat_name.'</a> ';
                            }
                        }
                    }
                    
                    $return .='</div>';
                    
                    $category='';
                    if(get_post_type($post->ID) == 'post'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                            foreach($cats as $cat){
                            $category .= '<a href="'.get_category_link($cat->term_id ).'">'.$cat->cat_name.'</a> ';
                            }
                        }
                    }
                    
                    if(get_post_type($post->ID) == 'portfolio'){
                        $cats = get_the_category(); 
                        if(is_array($cats)){
                             $category .= '<div class="categories">';
                             if (!is_wp_error( get_the_term_list( $post->ID, 'portfolio-type', ' ', ' ', '' ) ) ) {
                             $category .= get_the_term_list( $post->ID, 'portfolio-type', ' ', ' ', '' );
                             }
                             $category .= '</div>';
                        }
                    }
                    
                    
                    $return .='<div class="block_content">';
                    $return .='<h4 class="block_title"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">'.$post->post_title.'</a></h4>';
                    $return .='<div class="date"><small>'. get_the_time('F d,Y').''.((strlen($category)>2)? ' / '.$category:'').' / '.get_comments_number( '0', '1', '%' ).' Comments</small></div>';
                    $return .='<p class="block_desc">'.custom_excerpt($n,$post->ID).'</p>';
                    $return .='</div>';
                    $return .='</div>';
                break;
            
        }
        return $return;
}


//*=== Featured Component ===*//

function featured_component($post_id,$cols='',$style=''){
global $vibe_options;

if(!in_array($cols,array('big','small','medium','mini','full'))){
    switch($cols){
      case '2':{ $cols = 'big';
      break;}
      case '3':{ $cols = 'medium';
      break;}
      case '4':{ $cols = 'medium';
      break;}
      case '5':{ $cols = 'small';
      break;}
       case '6':{ $cols = 'small';
      break;}  
      default:{ $cols = 'full';
      break;}
    }
}
        $post_thumbnail='';
        
        if(has_post_thumbnail($post_id)){
            $post_thumbnail=  get_the_post_thumbnail($post_id,$cols);
            }else if(isset($vibe_options['default_image']) && $vibe_options['default_image'])
                $post_thumbnail='<img src="'.$vibe_options['default_image'].'" alt="'.the_title_attribute().'" />';
                    
    return $post_thumbnail;   
}        



function generate_likeviews($likes,$post_id){
    global $vibe_options;
    $return = '';
    
    if(isset($vibe_options['enable_likes']) && $vibe_options['enable_likes'])
    $return .='<p class="meta_info"><a class="like" id="'.$post_id.'" rel="tooltip" data-placement="top" data-original-title="Likes"><i class="icon-heart"></i> '.(isset($likes)?$likes:'0').'</a></p>';
    
    return $return;
}


?>