/* *** NEWS BOX -  WP DYNAMIC CUSTOM THEME *** */
<?php 
include_once(NB_DIR . '/functions.php');
$imb_base = NB_URL . '/js/nb/';

$img_border = get_option('nb_img_margin', 0);
$box_margin = get_option('nb_box_margin', 6);
?>

.lcnb_wpdt_theme .lcnb_loading {
	background: url('<?php echo $imb_base; ?>/img/loader_<?php echo get_option('nb_loader_style', 'l') ?>.gif') no-repeat center center transparent;		
}
.lcnb_wpdt_theme article.lcnb_news,
.lcnb_wpdt_theme .lcnb_exp_block {
	background-color: <?php echo get_option('nb_bg_color', '#FFFFFF') ?>; 
}
.lcnb_wpdt_theme.lcnb_wrap.lcnb_uniblock .lcnb_inner_wrapper {
	border-radius: <?php echo get_option('nb_border_radius', 2) ?>px;	
}
.lcnb_wpdt_theme.lcnb_wrap.lcnb_uniblock .lcnb_news,
.lcnb_wpdt_theme.lcnb_wrap.lcnb_boxed article.lcnb_news,
.lcnb_wpdt_theme.lcnb_wrap.lcnb_uniblock .lcnb_exp_block { 
	border: <?php echo get_option('nb_border_w', 1) ?>px solid <?php echo get_option('nb_border_color', '#D5D5D5') ?>;
    
    -moz-transition: 	box-shadow .2s linear, border-color .2s linear; 
	-webkit-transition: box-shadow .2s linear, border-color .2s linear;  
	-o-transition: 		box-shadow .2s linear, border-color .2s linear;  
	-ms-transition: 	box-shadow .2s linear, border-color .2s linear;  
	transition: 		box-shadow .2s linear, border-color .2s linear; 
}
.lcnb_wpdt_theme.lcnb_wrap.lcnb_uniblock .lcnb_news:hover,
.lcnb_wpdt_theme.lcnb_wrap.lcnb_boxed article.lcnb_news:hover { 
	border-color: <?php echo get_option('nb_border_color_h', '#C4C4C4') ?>;
}

/* IMPORANT - USE THE SAME VALUE OF THE .lcnb_news BORDER */
.lcnb_wpdt_theme.lcnb_vertical.lcnb_wrap.lcnb_uniblock .lcnb_news { 
	margin-top: -<?php echo get_option('nb_border_w', 1) ?>px;
}
.lcnb_wpdt_theme.lcnb_vertical.lcnb_wrap.lcnb_uniblock .lcnb_news:first-child { 
	margin-top: 0px;
}
.lcnb_wpdt_theme.lcnb_horizontal.lcnb_wrap.lcnb_uniblock .lcnb_news { 
	margin-left: -<?php echo get_option('nb_border_w', 1) ?>px;
}
.lcnb_wpdt_theme.lcnb_horizontal.lcnb_wrap.lcnb_uniblock .lcnb_news:first-child { 
	margin-left: 0px;
}
/* **** */


.lcnb_wpdt_theme.lcnb_wrap.lcnb_boxed article.lcnb_news,
.lcnb_wpdt_theme.lcnb_boxed .lcnb_exp_block {
	border: <?php echo get_option('nb_border_w', 1) ?>px solid <?php echo get_option('nb_border_color', '#D5D5D5') ?>;
	border-radius: <?php echo get_option('nb_border_radius', 2) ?>px;
	box-shadow: none;
}
<?php if(get_option('nb_use_shadows')) : ?>
.lcnb_wpdt_theme.lcnb_wrap.lcnb_boxed article.lcnb_news:hover {
	box-shadow: 0px 0px 3px rgba(25, 25, 25, 0.2);
}
<?php endif; ?>

.lcnb_wpdt_theme .lcnb_title {
	color: <?php echo get_option('nb_title_color', '#444444') ?> !important;	
	border-bottom: 1px solid <?php echo get_option('nb_sep_color', '#CFCFCF') ?>;
	font-weight: bold;
}
.lcnb_wpdt_theme .lcnb_txt,
.lcnb_wpdt_theme .lcnb_exp_txt {
	color: <?php echo get_option('nb_txt_color', '#444444') ?>;
}
.lcnb_wpdt_theme .lcnb_txt a {
	color: <?php echo get_option('nb_link_color', '#111111') ?> !important;
}
.lcnb_wpdt_theme .lcnb_img {
	background: url('<?php echo $imb_base ?>/img/loader_<?php echo get_option('nb_loader_style', 'l') ?>.gif') no-repeat center center transparent;	
}

.lcnb_wpdt_theme .lcnb_social_box li {
	color: <?php echo get_option('nb_btn_color', '#5F5F5F') ?>;
}


/* navigation commands */
.lcnb_wpdt_theme.lcnb_has_cmd .lcnb_prev, 
.lcnb_wpdt_theme.lcnb_has_cmd .lcnb_next {
	border: 1px solid <?php echo get_option('nb_border_color', '#D5D5D5') ?>;
	background-color: <?php echo get_option('nb_bg_color', '#FFFFFF') ?>;
}
.lcnb_wpdt_theme.lcnb_has_cmd .lcnb_prev:hover, 
.lcnb_wpdt_theme.lcnb_has_cmd .lcnb_next:hover {
	border-color: <?php echo get_option('nb_border_color_h', '#C4C4C4') ?>;
}
.lcnb_wpdt_theme .lcnb_cmd span:before {
	color: <?php echo get_option('nb_btn_color', '#5F5F5F') ?>;
}

<?php $sl_style = (get_option('nb_loader_style', 'l') == 'light') ? 'light' : 'dark'; ?>
/* source logos */
.lcnb_wpdt_theme.lcnb_src_logo .lcnb_type_twitter > div {
	background-image: url("<?php echo $imb_base; ?>/img/social_src_logos/<?php echo $sl_style ?>/twitter.png");
}
.lcnb_wpdt_theme.lcnb_src_logo .lcnb_type_rss > div {
	background-image: url("<?php echo $imb_base; ?>/img/social_src_logos/<?php echo $sl_style ?>/rss.png");
}
.lcnb_wpdt_theme.lcnb_src_logo .lcnb_type_pinterest > div {
	background-image: url("<?php echo $imb_base; ?>/img/social_src_logos/<?php echo $sl_style ?>/pinterest.png");
}
.lcnb_wpdt_theme.lcnb_src_logo .lcnb_type_facebook > div {
	background-image: url("<?php echo $imb_base; ?>/img/social_src_logos/<?php echo $sl_style ?>/facebook.png");
}
.lcnb_wpdt_theme.lcnb_src_logo .lcnb_type_google > div {
	background-image: url("<?php echo $imb_base; ?>/img/social_src_logos/<?php echo $sl_style ?>/google.png");
}
.lcnb_wpdt_theme.lcnb_src_logo .lcnb_type_youtube > div {
	background-image: url("<?php echo $imb_base; ?>/img/social_src_logos/<?php echo $sl_style ?>/youtube.png");
}
.lcnb_wpdt_theme.lcnb_src_logo .lcnb_type_soundcloud > div {
	background-image: url("<?php echo $imb_base; ?>/img/social_src_logos/<?php echo $sl_style ?>/soundcloud.png");
}
.lcnb_wpdt_theme.lcnb_src_logo .lcnb_type_tumblr > div {
	background-image: url("<?php echo $imb_base; ?>/img/social_src_logos/<?php echo $sl_style ?>/tumblr.png");
}

/* bottom ant top bar */
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_date,
.lcnb_wpdt_theme .lcnb_top_bar .lcnb_date,
.lcnb_wpdt_theme .lcnb_exp_date time,
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_rm_btn,
.lcnb_wpdt_theme .lcnb_top_bar .lcnb_rm_btn {
	background-color: <?php echo get_option('nb_date_bg', '#F3F3F3') ?>;
	color: <?php echo get_option('nb_title_color', '#444444') ?>;
	padding: 4px 8px;
	border-radius: 2px;
}
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_link, .lcnb_wpdt_theme .lcnb_top_bar .lcnb_link,
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_social_trigger, .lcnb_wpdt_theme .lcnb_top_bar .lcnb_social_trigger,
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_btn_expand, .lcnb_wpdt_theme .lcnb_top_bar .lcnb_btn_expand {
	border-right: 1px solid <?php echo get_option('nb_sep_color', '#CFCFCF') ?>;
	box-shadow: none;
}
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_link, .lcnb_wpdt_theme .lcnb_top_bar .lcnb_link,
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_social_trigger, .lcnb_wpdt_theme .lcnb_top_bar .lcnb_social_trigger,
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_btn_expand, .lcnb_wpdt_theme .lcnb_top_bar .lcnb_btn_expand {
	color: <?php echo get_option('nb_btn_color', '#5F5F5F') ?>;
}
.lcnb_wpdt_theme .lcnb_btm_bar.lcnb_narrow_txt,
.lcnb_wpdt_theme .lcnb_top_bar.lcnb_narrow_txt {
	background: <?php echo get_option('nb_bg_color', '#FFFFFF') ?>;
	background: <?php echo nb_hex2rgba(get_option('nb_bg_color', '#FFFFFF'), '0.9') ?>;
}
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_link, .lcnb_wpdt_theme .lcnb_top_bar .lcnb_link, 
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_social_trigger, .lcnb_wpdt_theme .lcnb_top_bar .lcnb_social_trigger, 
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_btn_expand, .lcnb_wpdt_theme .lcnb_top_bar .lcnb_btn_expand {
    opacity: 0.8;
	filter: alpha(opacity=80);
}
.lcnb_wpdt_theme .lcnb_rm_btn:hover {
	background-color: <?php echo get_option('nb_date_bg_h', '#E8E8E8') ?> !important;
	color: <?php echo get_option('nb_date_txt_col_h', '#303030') ?> !important;	
}


/* social flap */
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_social_box,
.lcnb_wpdt_theme .lcnb_top_bar .lcnb_social_box,
.lcnb_wpdt_theme .lcnb_exp_data .lcnb_social_box {
	background-color: <?php echo get_option('nb_bg_color', '#FFFFFF'); ?>;
	border: 1px solid <?php echo get_option('nb_border_color', '#D5D5D5') ?>;
}
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_social_box:after,
.lcnb_wpdt_theme .lcnb_exp_data .lcnb_social_box:after {
	border-top-color: <?php echo get_option('nb_border_color', '#D5D5D5') ?>;	
}
.lcnb_wpdt_theme .lcnb_top_bar .lcnb_social_box:before {
	border-bottom-color: <?php echo get_option('nb_border_color', '#D5D5D5') ?>;		
}
.lcnb_wpdt_theme .lcnb_btm_bar .lcnb_social_box > li,
.lcnb_wpdt_theme .lcnb_top_bar .lcnb_social_box > li,
.lcnb_wpdt_theme .lcnb_exp_data .lcnb_social_box > li {
	border-bottom: 1px solid <?php echo get_option('nb_border_color', '#D5D5D5') ?>; 	
}

/* lightbox overlays */
.lcnb_wpdt_theme .lcnb_lb_icon:before {
	color: <?php echo get_option('nb_ol_icon_color', '#333333') ?>;
}
.lcnb_wpdt_theme .lcnb_lb_overlay {
	background-color: <?php echo get_option('nb_ol_bg_color', '#FFFFFF') ?>;
	opacity: 0.7;
	filter: alpha(opacity=70);
}


/*** vertical ***/
.lcnb_wpdt_theme.lcnb_vertical.lcnb_side_cmd .lcnb_prev span {
	background-position: -132px 7px;
}
.lcnb_wpdt_theme.lcnb_vertical.lcnb_side_cmd .lcnb_next span {
	background-position: -168px 5px;
}
.lcnb_wpdt_theme.lcnb_vertical .lcnb_img {
	border-width: <?php echo $img_border ?>px 0px <?php echo $img_border ?>px <?php echo $img_border ?>px;
	border-style: solid;
	border-color: transparent;
}
.lcnb_wpdt_theme.lcnb_vertical .lcnb_img .lcnb_img_lb, 
.lcnb_wpdt_theme.lcnb_vertical .lcnb_img .lcnb_video_lb  {
	margin: <?php echo $img_border ?>px 0 0 <?php echo $img_border ?>px; /* IMPORANT - set the same value as .lcnb_img border */
}

.lcnb_wpdt_theme.lcnb_vertical .lcnb_buttons {
	background-color: <?php echo get_option('nb_bg_color', '#FFFFFF') ?>;
	background-color: <?php echo nb_hex2rgba(get_option('nb_bg_color', '#FFFFFF'), '0.85'); ?>;
	border-right: 1px solid <?php echo get_option('nb_sep_color', '#CFCFCF') ?>;
    border-radius: <?php echo get_option('nb_border_radius', 2) ?>px 0 0 <?php echo get_option('nb_border_radius', 2) ?>px;
}
.lcnb_wpdt_theme.lcnb_vertical .lcnb_buttons > div {
	border-color: <?php echo get_option('nb_sep_color', '#CFCFCF') ?>;
	border-left-color: <?php echo get_option('nb_sep_color', '#CFCFCF') ?>;	
	opacity: 0.9;
	filter: alpha(opacity=90);
}
.lcnb_wpdt_theme.lcnb_vertical .lcnb_buttons > div:hover {
	opacity: 1;
	filter: alpha(opacity=100);
}
.lcnb_wpdt_theme.lcnb_vertical .lcnb_buttons {
	color: <?php echo get_option('nb_btn_color', '#5F5F5F') ?>;
}
.lcnb_wpdt_theme.lcnb_vertical .lcnb_buttons .lcnb_social_trigger.socials_shown {
	background-color: <?php echo get_option('nb_bg_color', '#FFFFFF') ?>;
}
.lcnb_wpdt_theme.lcnb_vertical .lcnb_buttons ul.lcnb_social_box {
	background-color: <?php echo get_option('nb_bg_color', '#FFFFFF') ?>;
	border: 1px solid <?php echo get_option('nb_sep_color', '#CFCFCF') ?>;
}
.lcnb_wpdt_theme.lcnb_vertical .lcnb_buttons .lcnb_social_box li {
	border-right: 1px solid <?php echo get_option('nb_sep_color', '#CFCFCF') ?>;
}
.lcnb_wpdt_theme.lcnb_vertical .lcnb_buttons .lcnb_social_box li:last-child {
	border-right: none !important; 	
}
.lcnb_wpdt_theme.lcnb_vertical .lcnb_buttons > div:hover,
.lcnb_wpdt_theme.lcnb_vertical .lcnb_buttons > div.lcnb_active {
	background-color: <?php echo get_option('nb_bg_color', '#FFFFFF') ?>;
}


/*** horizontal ***/
.lcnb_wpdt_theme.lcnb_horizontal.lcnb_side_cmd .lcnb_prev span {
	background-position: -85px center;
}
.lcnb_wpdt_theme.lcnb_horizontal.lcnb_side_cmd .lcnb_next span {
	background-position: -115px center;
}
.lcnb_wpdt_theme.lcnb_horizontal .lcnb_img {
	border-width: <?php echo $img_border ?>px <?php echo $img_border ?>px 0;
	border-style: solid;
	border-color: transparent;
}


/**** EXPANDED NEWS ****/
.lcnb_wpdt_theme .lcnb_exp_block .lcnb_close:before {
	color: <?php echo get_option('nb_btn_color', '#5F5F5F') ?>;
}
.lcnb_wpdt_theme .lcnb_exp_data {
	border-top: 1px solid <?php echo get_option('nb_sep_color', '#CFCFCF') ?>;
}
.lcnb_wpdt_theme .lcnb_exp_data .lcnb_social_trigger,
.lcnb_wpdt_theme .lcnb_exp_data .lcnb_link {
	border-left: 1px solid <?php echo get_option('nb_sep_color', '#CFCFCF') ?>;
	color: <?php echo get_option('nb_btn_color', '#5F5F5F') ?>;
}
.lcnb_wpdt_theme .lcnb_exp_img_wrap,
.lcnb_wpdt_theme .lcnb_exp_body_img > div:first-child {
    border: <?php echo get_option('nb_exp_img_padding', 3) ?>px solid <?php echo get_option('nb_exp_img_bg', '#FFFFFF') ?>;
    box-shadow: 0 0 1px <?php echo get_option('nb_exp_img_border_col', '#AAAAAA') ?>;
}

<?php 
if($box_margin != 6) : ?>
/* *** */
.lcnb_wpdt_theme.lcnb_horizontal.lcnb_boxed article.lcnb_news {
 	margin-left: <?php echo $box_margin ?>px;
    margin-right: <?php echo $box_margin ?>px;
}
.lcnb_wpdt_theme.lcnb_vertical.lcnb_boxed article.lcnb_news {
    margin-top: <?php echo $box_margin ?>px;
    margin-bottom: <?php echo $box_margin ?>px;
}
<?php endif; ?>