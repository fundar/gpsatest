<?php

/**
 * FILE: sharing.php 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 */

function social_sharing_links(){
$social_sharing = array(
    'Like' => 'like',
    'Facebook' => 'http://www.facebook.com/share.php?u=[URL]',
    'Twitter' => 'http://twitter.com/share?url=[URL]',
    'Digg' => 'http://www.digg.com/submit?phase=2&url=[URL]&title=[TITLE]',
    'Stumbleupon' => 'http://www.stumbleupon.com/submit?url=[URL]&title=[TITLE]',
    'Delicious' => 'http://del.icio.us/post?url=[URL]&title=[TITLE]]&notes=[DESCRIPTION]',
    'GoogleBuzz' => 'http://www.google.com/reader/link?title=[TITLE]&url=[URL]',
    'LinkedIn' => 'http://www.linkedin.com/shareArticle?mini=true&url=[URL]&title=[TITLE]&source=[DOMAIN]',
    'SlashDot' => 'http://slashdot.org/bookmark.pl?url=[URL]&title=[TITLE]',
    'Technorati' => 'http://technorati.com/faves?add=[URL]&title=[TITLE]',
    'Posterous' => 'http://posterous.com/share?linkto=[URL]',
    'Tumblr' => 'http://www.tumblr.com/share?v=3&u=[URL]&t=[TITLE]',
    'Reddit' => 'http://www.reddit.com/submit?url=[URL]&title=[TITLE]',
    'GoogleBookmarks' => 'http://www.google.com/bookmarks/mark?op=edit&bkmk=[URL]&title=[TITLE]&annotation=[DESCRIPTION]',
    'NewsVine' => 'http://www.newsvine.com/_tools/seed&save?u=[URL]&h=[TITLE]',
    'PingFm' => 'http://ping.fm/ref/?link=[URL]&title=[TITLE]&body=[DESCRIPTION]',
    'Evernote' => 'http://www.evernote.com/clip.action?url=[URL]&title=[TITLE]',
    'FriendFeed' => 'http://www.friendfeed.com/share?url=[URL]&title=[TITLE]'
);
return $social_sharing;
}
//Social Sharing Function
function social_sharing($tip_direction='top'){
    global $vibe_options;
    $output='';
    if(isset($vibe_options['social_share']) && is_array($vibe_options['social_share'])){
        $output ='<ul class="socialicons '.$vibe_options['social_icons_type'].'">';
        $social_sharing = social_sharing_links();
        
        foreach($vibe_options['social_share'] as $social){
             global $post;
             if($social == 'Like'){
                 $likes=getPostMeta($post->ID,'like_count');
                 $output .='<li><a class="like" id="'.$post->ID.'"><i class="icon-heart"></i><span>'.(isset($likes)?$likes:'0').'</span></a></li>';
             }else{
             $title = get_the_title(); 
             $url = get_permalink(); 
             $description = strip_tags(get_the_excerpt()); 
             $domain = get_site_url(); 
            /*=== Preparing Sharing Link ====*/
            
             $social_sharing[$social] = str_replace('[TITLE]',$title,$social_sharing[$social]);
             $social_sharing[$social] = str_replace('[URL]',$url,$social_sharing[$social]);
             $social_sharing[$social] = str_replace('[DESCRIPTION]',$description,$social_sharing[$social]);
             $social_sharing[$social] = str_replace('[DOMAIN]',$domain,$social_sharing[$social]);
             
             $tip='';
            /*=== END Preparing Sharing Link ====*/
            if($vibe_options['show_social_tooltip'])
                $tip='rel="tooltip" data-placement="'.$tip_direction.'" title="Share on '.$social.'"';
           
            
            $output .='<li>';
            $output .= '<a href="'.$social_sharing[$social].'" '.$tip.' class="'.strtolower($social).'"><i class="icon-'.strtolower($social).'"></i></a>';
            $output .='</li>';
             }
        }
        $output .= '</ul>';
    }
    return $output;
}
?>
