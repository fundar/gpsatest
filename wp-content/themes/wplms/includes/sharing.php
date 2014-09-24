<?php

/**
 * FILE: sharing.php 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 */

function social_sharing_links(){
$social_sharing = array(
    'Facebook' => 'http://www.facebook.com/share.php?u=[URL]',
    'Twitter' => 'http://twitter.com/share?url=[URL]',
    'Digg' => 'http://www.digg.com/submit?phase=2&url=[URL]&title=[TITLE]',
    'Pinterest' => 'http://pinterest.com/pin/create/button/?url=[URL]',
    'Stumbleupon' => 'http://www.stumbleupon.com/submit?url=[URL]&title=[TITLE]',
    'Delicious' => 'http://del.icio.us/post?url=[URL]&title=[TITLE]]&notes=[DESCRIPTION]',
    'Google plus' => 'https://plus.google.com/share?url=[URL]',
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
    $social_share = vibe_get_option('social_share');
    $social_icons_type= vibe_get_option('social_icons_type');
    $output='';
    if(isset($social_share) && is_array($social_share)){
        $output ='<ul class="socialicons '.$social_icons_type.'">';
        $social_sharing = social_sharing_links();
        
        foreach($social_share as $social){
             global $post;
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
            $show_social_tooltip = vibe_get_option('show_social_tooltip');
            if(isset($show_social_tooltip) && $show_social_tooltip)
                $tip='data-placement="'.$tip_direction.'" title="'.__('Share on ','vibe').$social.'"';
           
            
            $output .='<li>';
            $output .= '<a href="'.$social_sharing[$social].'" '.$tip.' target="_blank" class="'.strtolower($social).((isset($show_social_tooltip) && $show_social_tooltip)?' tip':'').'"><i class="icon-'.strtolower($social).'"></i></a>';
            $output .='</li>';
        }
        $output .= '</ul>';
    }
    return $output;
}
?>
