<?php
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();

$title=get_post_meta(get_the_ID(),'vibe_title',true);
if(isset($title) && $title !='' && $title !='H'){
?>
<style>
#buddypress .activity-list li.new_forum_topic .activity-content .activity-inner {

input[type="file"]{
	margin-bottom:15px;
}


.smallimg img{
	height:20px;
	width:auto;
	border-radius:2px;
	margin-right:8px;
}
/*--------------------------------------------------------------
1 - LOGIN WIDGET
--------------------------------------------------------------*/
#bpavatar{
	float:left;
}
#bpavatar img{
	width:64px;
	height:auto;
	margin:20px 10px 20px 20px;
	border-radius:50%;
}
#vibe_bp_login{
	display: none;
}

#vbp-login-form{
	padding:20px;
}
#headertop #vibe_bp_login li{
	margin: 0;
	padding: 0;
	border:none;
	font-size:12px;
	width:100%;
}

#vibe_bp_login li span{
	background: #fa7252;
	padding: 1px 6px;
	font-size: 12px;
	font-weight: 800;
	border-radius: 20px;
	margin-left: 10px;
}

#headertop #vibe_bp_login ul{
	width: 146px;
	float: right;
	padding: 20px 20px 20px 10px;
}
#headertop #vibe_bp_login ul+ul{
	width:100%;
	padding: 0px 20px;
	background:#313B3D;
	border-bottom-left-radius: 2px;
	border-bottom-right-radius: 2px;
	box-shadow: 0 1px 1px rgba(0,0,0,0.2);
}
#vibe_bp_login ul+ul li a{
	padding: 6px 0;
	display: inline-block;
	width: 100%;
	font-weight:600;
	border-bottom: 1px dotted rgba(255,255,255,0.2);
}
#headertop #vibe_bp_login ul:last-child{
	padding-bottom:20px;
}
#vibe_bp_login li#username{
	font-size: 16px;
	line-height: 1.2;
	margin-bottom:3px;
	text-transform: none;
}
#vibe_bp_login ul+ul li:last-child{
	border:none;
}

#vibe_bp_login ul+ul li i{
	float:right;
}
#vibe_bp_login li#vbplogout a{font-size:11px;color:#000;}
#vibe_bp_login li{
  position: relative;
}

#vibe_bp_login a:hover{
	color:#FFF;
}

/*--------------------------------------------------------------
1.1 - Single HEADER
--------------------------------------------------------------*/

#item-header-avatar{
	border-radius: 2px;
	width:100%;
	border-bottom:2px solid #78c8c9;
}

#content #buddypress{
	margin-top:30px;
}
/*--------------------------------------------------------------
1.1 - ITEM NAV
--------------------------------------------------------------*/
#item-nav{
	clear:both;
}
/*--------------------------------------------------------------
1.1 - Pagination
--------------------------------------------------------------*/
#buddypress div.pagination {
	background: transparent;
	border: none;
	color: #444;
	margin: 0;
	position: relative;
	display: inline-block;
	float: none;
	width: 100%;
	padding: 15px 0;
}

#buddypress #pag-top.pagination{
	padding-top:0;
}
#buddypress #pag-bottom.pagination{
	padding-bottom:0;
}
#buddypress div.pagination .pag-count {
	float: left;
	margin-left: 10px;
	font-size: 12px;
	text-transform: uppercase;
	font-weight: 600;
	color: #999;
}
#buddypress div.pagination .pagination-links {
	float: right;
	margin-right: 10px;
}
#buddypress div.pagination .pagination-links span,
#buddypress div.pagination .pagination-links a {
	font-size: 90%;
	padding: 2px 8px;
}
#buddypress div.pagination .pagination-links a:hover {
	font-weight: bold;
}
#buddypress noscript div.pagination {
	margin-bottom: 15px;
}
#buddypress #nav-above {
	display: none;
}
#buddypress .paged #nav-above {
	display: block;
}
/*--------------------------------------------------------------
2 - WordPress
--------------------------------------------------------------*/
/*--------------------------------------------------------------
2.1 - Images
--------------------------------------------------------------*/
#buddypress img.wp-smiley {
	border: none !important;
	clear: none !important;
	float: none !important;
	margin: 0 !important;
	padding: 0 !important;
}

/*--------------------------------------------------------------
3.0 - BuddyPress
--------------------------------------------------------------*/
#buddypress #create-group-form  div.item-list-tabs{
	float:left;
	margin:0 30px 0px 0;
}

.buddyleftsidebar{
	margin:30px 0;
}

.buddyleftsidebar .widget+.widget{
	margin-top:30px;
}
/*--------------------------------------------------------------
3.1 - Activity
--------------------------------------------------------------*/
#buddypress #activity-stream {
	/*overflow-y: scroll;
	max-height: 400px;*/
}

#buddypress #activity-stream p {
	margin: 5px 0;
}

#item-body{
	position: relative;
	background:#FFF;
	padding:20px;
	border-radius:2px;
}

#item-body .form-allowed-tags{
	display: none;
}
#item-body #commentform{
	margin:30px 0;
}
#item-body .members,
#item-body .messages,
#item-body .groups,
#send_message_form{
	display: inline-block;
	width: 100%;
}

#buddypress #item-body form#whats-new-form {
	margin: 0px;
	padding: 0;
	width:100%;
	display: inline-block;
}
#buddypress .home-page form#whats-new-form {
	border-bottom: none;
	padding-bottom: 0;
}
#buddypress form#whats-new-form #whats-new-avatar {
	float: left;
	margin:10px 20px 0 0;
	max-width: 72px;
}
#buddypress form#whats-new-form #whats-new-avatar img{
	border-radius:50%;
}
#buddypress form#whats-new-form #whats-new-content {
	margin-left: 75px;
	padding: 0 0 20px 20px;
}
#buddypress form#whats-new-form p.activity-greeting {
	line-height: 1.3em;
	font-size:32px;
	font-weight:600;
	margin-bottom: 15px;
	margin-left: 75px;
}
#buddypress form#whats-new-form textarea {
	background: #fff;
	color: #555;
	font-family: inherit;
	font-size: 90%;
	height: 30px;
	padding: 6px;
	width: 98%;
}
body.no-js #buddypress form#whats-new-form textarea {
	height: 50px;
}
#buddypress form#whats-new-form #whats-new-options select {
	max-width: 200px;
	margin-top: 12px;
}
#buddypress form#whats-new-form #whats-new-submit {
	float: right;
	margin-top: 6px;
	margin-right: 15px;
}
#buddypress #whats-new-options {
	overflow: auto;
}

#buddypress #whats-new:focus {
	border-color: rgba(31, 179, 221, 0.9) !important;
	outline-color: rgba(31, 179, 221, 0.9);
}

/*--------------------------------------------------------------
3.1.1 - Activity Listing
--------------------------------------------------------------*/

.custom_content{
	padding:20px 30px 30px;
	border:1px solid #EFEFEF;
	margin-bottom:30px;
	background:#F6F6f6;
	border-radius:2;
}
.custom_content h4,
.certifications h4,
.courses_undertaken h4{
	margin: 0 0 10px;
	border-bottom: 1px solid #EFEFEF;
	padding-bottom: 10px;
	font-weight:600;
}

.courses_undertaken{
	padding:20px 30px 30px;
}

.courses_undertaken li{
	float:left;
	margin: 10px 10px 0 0;
	border:1px solid #EFEFEF;
}

.course_students li,
.quiz_students li,
.assignment_students li{
	position: relative;
}
.course_students li > ul,
.quiz_students li > ul,
.assignment_students li > ul{
	position: absolute;
	right:0;
	top:0;
}
.quiz_students li > ul,
.assignment_students li > ul{top:-40px;}

.course_students li > ul > li,
.quiz_students li > ul > li,
.assignment_students li > ul > li{
	float:left;
	clear:none;
	margin-left:10px;
	padding:0;
	border:none;
	display: inline-block;
	width:auto;
}
.course_students li > ul > li > a,
.quiz_students li > ul > li > a,
.assignment_students li > ul > li > a{
	font-size:24px;
	color:#bbb;
	padding:16px 0 0;
	display: inline-block;
	cursor:pointer;
}
.course_students li > ul > li > a:hover,
.quiz_students li > ul > li > a:hover,
.assignment_students li > ul > li > a:hover{
	color:#78c8ce;
}
.course_students li input[type="checkbox"],
.quiz_students li input[type="checkbox"],
.assignment_students li input[type="checkbox"]{
	float: left;
	margin: 20px 10px 0 0;
}


.badges,.certifications{
	width:50%;
	float:left;
	display: inline-block;
}
.badges li{
	max-width: 64px;
	float:left;
	display: inline-block;
	margin:0 10px 10px 0;
}
.certifications li{
	border: 1px solid #EFEFEF;
	border-radius: 2px;
	max-width: 160px;
	padding: 10px;
	color:#bbb;
	font-size: 12px;
	text-transform: uppercase;
	float: left;
	margin: 0 10px 10px 0;
}
.certifications li i{float:left;font-size:32px;}
.certifications li a{
	font-weight:600;
}
.badges h6,.certifications h6{
	padding-bottom:10px;
	margin:0 0 10px 0;
	border-bottom:3px solid #EFEFEF;
	font-weight:600;
	text-transform: uppercase;
}
.certifications+.profile{
	margin-top:30px;
	clear:both;
	display: inline-block;
	width:100%;
}

table.profile-settings{width:100%;}

div.success{
	padding: 10px;
	background: #70c989;
	display: inline-block;
	color:#FFF;
	font-weight:600;
	width: 100%;
	text-align: center;
}

div.error{
	padding: 10px;
	background: #fa7252;
	display: inline-block;
	color:#FFF;
	font-weight:600;
	width: 100%;
	text-align: center;	
}
#buddypress ul.activity-list li {
	overflow: hidden;
	padding: 15px 0 0;
	list-style: none;
}
#buddypress .activity-list .activity-avatar {
	float: left;
	margin-top:10px;
}
#buddypress ul.item-list.activity-list li.has-comments {
	padding-bottom: 15px;
}
body.activity-permalink #buddypress ul.activity-list li.has-comments {
	padding-bottom: 0;
}
#buddypress .activity-list li.mini {
	position: relative;
}
#buddypress .activity-list li.mini .activity-avatar img.avatar,
#buddypress .activity-list li.mini .activity-avatar img.FB_profile_pic {
	height: 75px;
	margin-left: 0;
	width: 75px;
	border-radius:50%;
}
#buddypress .activity-permalink .activity-list li.mini .activity-avatar img.avatar,
#buddypress .activity-permalink .activity-list li.mini .activity-avatar img.FB_profile_pic {
	height: auto;
	margin-left: 0;
	width: auto;
}
body.activity-permalink #buddypress .activity-list > li:first-child {
	padding-top: 0;
}
#buddypress .activity-list li .activity-content {
	position: relative;
}
#buddypress .activity-list li.mini .activity-content p {
	margin: 0;
}
#buddypress .activity-list li.mini .activity-comments {
	clear: both;
	font-size: 120%;
}

.activity-comments > ul > li > ul {
	margin-top:30px;
}
body.activity-permalink #buddypress li.mini .activity-meta {
	margin-top: 4px;
}
#buddypress .activity-list li .activity-inreplyto {
	color: #444;
	font-size: 80%;
}
#buddypress .activity-list li .activity-inreplyto > p {
	margin: 0;
	display: inline;
}
#buddypress .activity-list li .activity-inreplyto blockquote,
#buddypress .activity-list li .activity-inreplyto div.activity-inner {
	background: none;
	border: none;
	display: inline;
	margin: 0;
	overflow: hidden;
	padding: 0;
}
#buddypress .activity-list .activity-content {
	margin: 0 0 0 92px;
}
body.activity-permalink #buddypress .activity-list li .activity-content {
	border: none;
	font-size: 100%;
	line-height: 150%;
	margin-left: 170px;
	margin-right: 0;
	padding: 0;
}
body.activity-permalink #buddypress .activity-list li .activity-header > p {
	margin: 0;
	padding: 5px 0 0 0;
}
#buddypress .activity-list .activity-content .activity-header,
#buddypress .activity-list .activity-content .comment-header {
	color: #bbb;
	line-height: 220%;
	font-size: 11px;
	text-transform: uppercase;
}
#buddypress .activity-header {
	margin-right: 20px;
}
#buddypress .activity-header a,
#buddypress .comment-meta a,
#buddypress .acomment-meta a {
	text-decoration: none;
}
#buddypress .activity-list .activity-content .activity-header img.avatar {
	float: none !important;
	margin: 0 5px -8px 0 !important;
}
#buddypress a.bp-secondary-action,
#buddypress span.highlight {
	padding: 0;
	font-size:11px ;
	margin-right: 5px;
	text-decoration: none;
	color:#FFF;
	font-weight:600;
	text-transform: uppercase;
}

#buddypress #item-body a.bp-secondary-action,
#buddypress #item-body span.highlight {
	color:#bbb;
}

#buddypress span.highlight a{
	color:#FFF;
}
#buddypress #item-body span.highlight a{
	color:#78c8c9;
}
#item-meta .star-rating{
	font-size:11px;
}
#item-admins h3{
	color:#FFF;
	font-size:18px;
	margin-top:0;
	padding:20px 20px 10px;
	border-top:1px solid rgba(255,255,255,0.1);
}

#item-admins .item-avatar{
	width: 40px;
	float: left;
	margin: 0 20px;
	border-radius: 50%;
}
#item-admins .item-avatar img{
	border-radius: 50% !important;
}
#item-admins h5,
#item-admins h5 a{
	color:#FFF;
}

#item-admins{
	display: inline-block;
	width: 100%;
	margin: 0 0 20px;
}

.course_credits{
	text-align: center;
	text-transform: uppercase;
	color: #70c989;
	font-size: 18px;
	float: right;
}

.course_credits span{
	display: block;
	font-size: 11px;
}

#buddypress .activity-list .activity-content .activity-inner,
#buddypress .activity-list .activity-content blockquote {
	margin: 10px 10px 5px 0;
	overflow: hidden;
}
#buddypress .activity-list .activity-content .activity-header + .activity-inner{
	margin:0;
}
#buddypress .activity-list li.new_forum_post .activity-content .activity-inner,
#buddypress .activity-list li.new_forum_topic .activity-content .activity-inner {
	border-left: 2px solid #EAEAEA;
	margin-left: 5px;
	padding-left: 10px;
}
body.activity-permalink #buddypress .activity-content .activity-inner,
body.activity-permalink #buddypress .activity-content blockquote {
	margin-left: 0;
	margin-top: 5px;
}
#buddypress .activity-inner > p {
	word-wrap: break-word;
}
#buddypress .activity-inner > .activity-inner {
	margin: 0;
}
#buddypress .activity-inner > blockquote {
	margin: 0;
}
#buddypress .activity-list .activity-content img.thumbnail {
	border: 2px solid #eee;
	float: left;
	margin: 0 10px 5px 0;
}
#buddypress .activity-read-more {
	margin-left: 1em;
	white-space: nowrap;
}
#buddypress .activity-list li.load-more {
	background: #f0f0f0;
	font-size: 110%;
	margin: 15px 0;
	padding: 10px 15px;
	text-align: center;
}
#buddypress .activity-list li.load-more a {
	color: #4D4D4D;
}


/*--------------------------------------------------------------
3.1.2 - Single Activity
--------------------------------------------------------------*/
body.activity-permalink .activity{
max-width: 80%;
margin: 30px auto;
}

body.activity-permalink .activity-comments{

clear: both;
padding-top: 15px;
margin-top: 15px;
border-top: 1px solid#EFEFEF;
display: inline-block;
width: 100%;
}

body.activity-permalink .activity-comments ul{
margin-left:80px;
}

body.activity-permalink .acomment-meta{
font-size: 11px;
color: #BBB;
text-transform:uppercase;
font-weight: 600;

}
body.activity-permalink .acomment-meta a{color:#bbb;}

body.activity-permalink .ac-form{
	display: inline-block;
margin: 15px 0;
padding: 10px 0;
width: 100%;
color:#bbb;
}

body.activity-permalink .ac-reply-avatar{
	float: left;
	max-width: 80px; 
}
body.activity-permalink .ac-reply-avatar img{border-radius: 50%; }
body.activity-permalink .ac-form input[type="submit"]{
	margin-top:20px;
	padding: 6px 12px;
	font-size: 11px;
	line-height: 1.6;
	font-weight: 600;
	text-transform: uppercase;
	background-color:#78c8ce;
	color: #FFF;
	border: none;
	border-radius: 2px;
	background-image: url(../images/button.png);
}

body.activity-permalink .ac-reply-content{
	margin-left:100px;
}


/*--------------------------------------------------------------
3.1.2 - COURSE ADMIN, MESSAING 
--------------------------------------------------------------*/
.course_message{
	display: none;
}
.course_bulk_actions{
	clear:both;
	padding:8px 0;
	border-top:1px solid #EFEFEF;
	border-bottom:1px solid #EFEFEF;
	font-size:12px;
	color:#bbb;
	display: inline-block;
	width: 100%;
}
.course_bulk_actions a{
	font-size:12px;
	font-weight:600;
	text-transform: uppercase;
	float:right;
}

.course_bulk_actions a i{
	font-size: 16px;
	float: left;
	margin-right: 5px;
}
/*--------------------------------------------------------------
3.1.2 - Activity Comments
--------------------------------------------------------------*/
#buddypress div.activity-meta {
	margin: 18px 0 0;
}
body.activity-permalink #buddypress div.activity-meta {
	margin-bottom: 6px;
}
#buddypress .activity-content .activity-meta {margin:0;}
#buddypress div.activity-meta a ,#buddypress div.activity-meta a.button{
	padding: 0;
	background:none;
	color:#78c8ce;
	margin:0 5px 0 0;
}
#buddypress a.activity-time-since {
	color: #aaa;
	text-decoration: none;
}
#buddypress a.activity-time-since:hover {
	color: #444;
	text-decoration: underline;
}
#buddypress a.bp-primary-action,
#buddypress #reply-title small a {
	font-size:10px;
	margin-right: 5px;
	text-decoration: none;
	font-weight:600;
	text-transform: uppercase;
}
#buddypress a.bp-primary-action span,
#buddypress #reply-title small a span {
	color: #fff;
	font-size:12px;
	margin-left: 2px;
	color:#999;
}
#buddypress a.bp-primary-action:hover span,
#buddypress #reply-title small a:hover span {
	background: none;
	color: #fa7252;
}
#buddypress div.activity-comments {
	margin: 10px 0 0 70px;
	overflow: hidden;
	position: relative;
	width: auto;
	clear: both;
	padding-top: 10px;
	border-top: 1px dotted #EFEFEF;
}

#buddypress .checkbox input[type="checkbox"],
#buddypress .checkbox input[type="checkbox"]{	
	margin:0 10px 0 0;
}
#buddypress .activity-content + div.activity-comments{
	border:none;
}
body.activity-permalink #buddypress div.activity-comments {
	background: none;
	margin-left: 170px;
	width: auto;
}
#buddypress div.activity-comments > ul {
	padding: 0 0 0 10px;
}
#buddypress div.activity-comments ul,
#buddypress div.activity-comments ul li {
	border: none;
	list-style: none;
}
#buddypress div.activity-comments ul {
	clear: both;
	margin: 0;
}
#buddypress div.activity-comments ul li {
	border-top: 1px solid #eee;
	padding: 10px 0 0;
}
body.activity-permalink #buddypress .activity-list li.mini .activity-comments {
	clear: none;
	margin-top: 0;
}
body.activity-permalink #buddypress div.activity-comments ul li {
	border-width: 1px;
	padding: 10px 0 0 0;
}
#buddypress div.activity-comments > ul > li:first-child {
	border-top: none;
}
#buddypress div.activity-comments ul li:last-child {
	margin-bottom: 0;
}
#buddypress div.activity-comments ul li > ul {
	margin-left: 30px;
	margin-top: 0;
	padding-left: 10px;
}
body.activity-permalink #buddypress div.activity-comments ul li > ul {
	margin-top: 10px;
}
body.activity-permalink #buddypress div.activity-comments > ul {
	padding: 0 10px 0 15px;
}
#buddypress div.activity-comments div.acomment-avatar img {
	border-width: 1px;
	float: left;
	height: 25px;
	margin-right: 10px;
	width: 25px;
}
#buddypress div.activity-comments div.acomment-content {
	font-size: 95%;
	margin: 5px 0 0 40px;
}
#buddypress div.acomment-content .time-since,
#buddypress div.acomment-content .activity-delete-link,
#buddypress div.acomment-content .comment-header {
	display: none;
}
body.activity-permalink #buddypress div.activity-comments div.acomment-content {
	font-size: 90%;
}
#buddypress div.activity-comments div.acomment-meta {
	color: #bbb;
	font-size: 80%;
}
#buddypress div.activity-comments div.acomment-meta a{color:#bbb;font-weight:600;}
#buddypress div.activity-comments form.ac-form {
	display: none;
	padding: 10px;
}
#buddypress div.activity-comments li form.ac-form {
	margin-right: 15px;
	clear: both;
}
#buddypress div.activity-comments form.root {
	margin-left: 0;
}
#buddypress div.activity-comments div#message {
	margin-top: 15px;
	margin-bottom: 0;
}
#buddypress div.activity-comments form .ac-textarea {
	background: #fff;
	border: 1px solid #EFEFEF;
	margin-bottom: 10px;
	padding: 8px;
}

.congrats_badge,
.congrats_certificate,
.congrats_message{
	padding: 15px 15px 15px 48px;
	background: #70c989;
	margin: 10px 0;
	color: #FFF;
	font-size: 11px;
	text-transform: uppercase;
	font-weight:600;
	position: relative;
}

.congrats_badge a,
.congrats_certificate a,
.congrats_message a{
	color:#FFF;
	font-weight:700;
}
.congrats_badge:before,
.congrats_certificate:before,
.congrats_message:before{
	font-family: 'fonticon';
	font-size: 32px;
	position: absolute;
	left: 10px;
	top: 10px;
	opacity: 0.6;
}
.congrats_badge:before {
	top: 8px;
	content: "\e039";
	font-size: 24px;
}
.congrats_certificate:before{content: "\e0e1";}

.congrats_message:before {
content: "\e06b";
top: 6px;
font-size: 24px;
}

#buddypress div.activity-comments form textarea {
	border: none;
	background: transparent;
	box-shadow: none;
	outline: none;
	color: #555;
	font-family: inherit;
	font-size: 100%;
	height: 60px;
	padding: 0;
	margin: 0;
	width: 100%;
}
#buddypress div.activity-comments form input {
	margin-top: 5px;
}
#buddypress div.activity-comments form div.ac-reply-avatar {
	float: left;
}
#buddypress div.ac-reply-avatar img {
	border: 1px solid #eee;
}
#buddypress div.activity-comments form div.ac-reply-content {
	color: #444;
	margin-left: 75px;
	padding-left: 15px;
}
#buddypress div.activity-comments form div.ac-reply-content a {
	text-decoration: none;
}
#buddypress .acomment-options {
	float: left;
	margin: 5px 0 5px 60px;
}
#buddypress .acomment-options a {
	color: #78c8ce;
	text-transform: uppercase;
	font-weight: 600;
}
#buddypress .acomment-options a:hover {
	color: inherit;
}




/*--------------------------------------------------------------
3.3 - Directories - Members, Groups, Blogs, Forums content: "'";
--------------------------------------------------------------*/
#buddypress div.dir-search {
	float: right;
	margin: 0;
	padding:0;
}

.total_students{
	background: #F6F6F6;
	padding: 10px 20px;
	margin: 10px -20px;
}
.total_students span{
	float: right;
	color: #FFF;
	display: inline-block;
	text-align: center;
	padding:1px 7px;
	font-size:11px;
	border-radius: 50%;
	background: #78c8ce;
	font-weight: 600;
}
#buddypress #item-body .total_students+h3{
	margin-top:30px;
}
.course_students,
.quiz_students li,
.assignment_students li{margin:30px 0;}
.course_students li,
.quiz_students li,
.assignment_students li{
	clear:both;
	padding:15px 0;
	border-bottom:1px solid #EFEFEF;
	display: inline-block;
	width:100%;
}
.course_students li h6,
.quiz_students li h6,
.assignment_students li h6{
	margin:0;
}
.course_students li h6+span,
.quiz_students li h6+span,
.assignment_students li h6+span{
	font-size: 11px;
	text-transform: uppercase;
	font-weight: 600;
	color: #bbb;
	line-height: 1;
}
.course_students li img,
.quiz_students li img,
.assignment_students li img{
	float:left;
	max-width:48px;
	height:auto;
	border-radius:50%;
	margin-right:20px;
}

#buddypress #groups-directory-form div.dir-search,
#buddypress #course-directory-form div.dir-search,
#buddypress #members-directory-form div.dir-search {float:none;}

#buddypress div.dir-search input[type=text] {
	font-size: 90%;
	padding: 6px 8px;
	width:172px;
}

#buddypress .dir-form {
	clear: both;
	position: relative;
}
#buddypress .bcrow{
	padding-top:0;
	margin-bottom:30px;
}


/*--------------------------------------------------------------
3.4 - Errors / Success Messages
--------------------------------------------------------------*/
#buddypress div#message{
	margin: 0 0 15px;
}
#buddypress #message.info {
	margin-bottom: 0;
	display: inline-block;
}
#buddypress div#message.updated {
	clear: both;
}

#buddypress #create-group-form div#message.updated {
	clear:none;
	}

.button.create-group-button{
	padding: 27px 40px 28px;
	margin:0;
	font-size: 13px;
	text-transform: uppercase;
	font-weight: 600;
	float: right;
}	
#buddypress div#message p,
#sitewide-notice p {
	font-size: 90%;
	display: block;
	padding: 10px 15px;
	margin:0;
}
#buddypress div#message.error{
	background:none;padding:0;border:none;
}
#buddypress div#message.error p {
	background-color: #fa7252;
	clear: left;
	background-image:url(../images/button.png);
	border: none;
	color: #FFF;
	font-weight: 600;
	text-transform: uppercase;
	border-radius:2px;
	margin-top:0;
}

#buddypress div#message.updated p {
	background-color: #30db6d;
	background-image:url(../images/button.png);
	border: none;
	color: #FFF;
	font-weight: 600;
	text-transform: uppercase;
	border-radius:2px;
}
#buddypress .standard-form#signup_form div div.error {
	background: #faa;
	color: #a00;
	margin: 0 0 10px 0;
	padding: 6px;
	width: 90%;
}
#buddypress div.accept,
#buddypress div.reject {
	float: left;
	margin-left: 10px;
}
#buddypress ul.button-nav li {
	float: left;
	margin: 0 10px 10px 0;
	list-style: none;
}
#buddypress ul.button-nav li.current a {
	font-weight: bold;
}
#sitewide-notice #message {
	left: 2%;
	position: fixed;
	top: 1em;
	width: 96%;
	z-index: 9999;
}
#sitewide-notice.admin-bar-on #message {
	top: 3.3em;
}
#sitewide-notice strong {
	display: block;
	margin-bottom: -1em;
}


/*--------------------------------------------------------------
3.5 - Forms
--------------------------------------------------------------*/
#buddypress .standard-form textarea,
#buddypress .standard-form input[type=text],
#buddypress .standard-form input[type=text],
#buddypress .standard-form input[type=color],
#buddypress .standard-form input[type=date],
#buddypress .standard-form input[type=datetime],
#buddypress .standard-form input[type=datetime-local],
#buddypress .standard-form input[type=email],
#buddypress .standard-form input[type=month],
#buddypress .standard-form input[type=number],
#buddypress .standard-form input[type=range],
#buddypress .standard-form input[type=search],
#buddypress .standard-form input[type=tel],
#buddypress .standard-form input[type=time],
#buddypress .standard-form input[type=url],
#buddypress .standard-form input[type=week],
#buddypress .standard-form select,
#buddypress .standard-form input[type=password],
#buddypress .dir-search input[type=search],
#buddypress .dir-search input[type=text] {
	border: 1px solid #EFEFEF;
	border-radius: 2px;
	color: #444;
	font-size:14px;
	font-weight:400;
	font: inherit;
	font-size: 100%;
	padding: 6px 12px;
}
#buddypress .standard-form select {
	padding: 3px;
}
#buddypress .standard-form input[type=password] {
	margin-bottom: 5px;
}
#buddypress .standard-form label,
#buddypress .standard-form span.label {
	display: block;
	font-weight: bold;
	margin: 15px 0 5px 0;
}
#buddypress .standard-form div.checkbox label,
#buddypress .standard-form div.radio label {
	color: #444;
	font-size: 100%;
	font-weight: normal;
	margin: 5px 0 0 0;
}
#buddypress .standard-form#sidebar-login-form label {
	margin-top: 5px;
}
#buddypress .standard-form input[type=text] {
	width: 100%;
}
#buddypress .standard-form#sidebar-login-form input[type=text],
#buddypress .standard-form#sidebar-login-form input[type=password] {
	padding: 4px;
	width: 95%;
}
#buddypress .standard-form #basic-details-section input[type=password],
#buddypress .standard-form #blog-details-section input#signup_blog_url {
	width: 35%;
}
#buddypress .standard-form#signup_form input[type=text],
#buddypress .standard-form#signup_form textarea,
#buddypress .form-allowed-tags,
#buddypress #commentform input[type=text],
#buddypress #commentform textarea {
	width: 90%;
}
#buddypress .standard-form#signup_form div.submit {
	float: right;
}
#buddypress div#signup-avatar img {
	margin: 0 15px 10px 0;
}
#buddypress .standard-form textarea {
	height: 120px;
}
#buddypress .standard-form textarea#message_content {
	height: 200px;
}
#buddypress .standard-form#send-reply textarea {
	width: 97.5%;
}
#buddypress .standard-form p.description {
	color: #444;
	font-size: 80%;
	margin: 5px 0;
}
#buddypress .standard-form div.submit {
	clear: both;
	padding: 15px 0 0 0;
}
#buddypress .standard-form p.submit {
	margin-bottom: 0;
	padding: 15px 0 0 0;
}
#buddypress .standard-form div.submit input {
	margin-right: 15px;
}
#buddypress .standard-form div.radio ul {
	margin: 10px 0 15px 38px;
	list-style: disc;
}
#buddypress .standard-form div.radio ul li {
	margin-bottom: 5px;
}
#buddypress .standard-form a.clear-value {
	display: block;
	margin-top: 5px;
	outline: none;
}
#buddypress .standard-form #basic-details-section,
#buddypress .standard-form #blog-details-section,
#buddypress .standard-form #profile-details-section {
	float: left;
	width: 48%;
}
.profile-settings tr{
	padding:6px 0;
	border-bottom:1px solid #EFEFEF;
	display: inline-block;
	width: 100%;
}
.profile-settings th:first-child,
.profile-settings td:first-child{
	float:left;
}
.profile-settings th,
.profile-settings td{
	float:right;
}
.profile-settings .field-name,
.profile-settings .field-group-name{
	min-width:160px;
	font-size:12px;
	font-weight:600;
	text-transform: uppercase;
}
#buddypress .standard-form #profile-details-section {
	float: right;
}
#buddypress .standard-form #blog-details-section {
	clear: left;
}
#buddypress .standard-form input:focus,
#buddypress .standard-form textarea:focus,
#buddypress .standard-form select:focus {
	background: #fafafa;
	color: #555;
}
#buddypress form#send-invite-form {
	margin-top: 20px;
}
#buddypress div#invite-list {
	background: #f5f5f5;
	height: 400px;
	margin: 0 0 10px;
	overflow: auto;
	padding: 5px;
	width: 160px;
}
#buddypress button,
#buddypress a.button,
#buddypress input[type=button],
#buddypress input[type=submit],
#buddypress input[type=reset],
#buddypress ul.button-nav li a,
#buddypress div.generic-button a,
#buddypress .comment-reply-link,
a.bp-title-button {
	padding: 7px 12px;
	font-size: 11px;
	line-height: 1.6;
	font-weight: 600;
	text-transform: uppercase;
	background-color:#78c8ce;
	color: #FFF;
	border: none;
	border-radius: 2px;
	background-image: url(../images/button.png);
}
#buddypress button:hover,
#buddypress a.button:hover,
#buddypress a.button:focus,
#buddypress input[type=submit]:hover,
#buddypress input[type=button]:hover,
#buddypress input[type=reset]:hover,
#buddypress ul.button-nav li a:hover,
#buddypress ul.button-nav li.current a,
#buddypress div.generic-button a:hover,
#buddypress .comment-reply-link:hover {
	background-color: #ddd;
	border: none;
	color: #444;
	outline: none;
	text-decoration: none;
}

#buddypress form.standard-form .left-menu {
	float: left;
}
#buddypress form.standard-form .left-menu img{
	width: 230px;
	height: auto;
	margin-right: 20px;
	border-radius: 2px;
}
#buddypress form.standard-form .left-menu #invite-list ul{
	margin:1%;
	list-style: none;
}

#buddypress form.standard-form .left-menu #invite-list ul li {
	margin:0 0 0 1%;
}

#buddypress form.standard-form .main-column {
	margin-left: 190px;
}

#buddypress form.standard-form .main-column ul#friend-list {
	clear:none;
}

#buddypress form.standard-form .main-column ul#friend-list h4 {
	clear:none;
}

/* Overrides for embedded WP editors */
#buddypress .wp-editor-wrap a.button,
#buddypress .wp-editor-wrap button,
#buddypress .wp-editor-wrap input[type=submit],
#buddypress .wp-editor-wrap input[type=button],
#buddypress .wp-editor-wrap input[type=reset] {
	padding: 0 10px 1px;
}

.last.filter label{
	display: none !important;
}
/*--------------------------------------------------------------
3.6 - Ajax Loading
--------------------------------------------------------------*/
#buddypress a.loading,
#buddypress input.loading {
	-webkit-animation: loader-pulsate .5s infinite ease-in-out alternate;
	-moz-animation: loader-pulsate .5s infinite ease-in-out alternate;
	border-color: #aaa;
}
@-webkit-keyframes loader-pulsate {
	from {
		border-color: #aaa;
		-webkit-box-shadow: 0 0 6px #ccc;
		box-shadow: 0 0 6px #ccc;
	}
	to {
		border-color: #ccc;
		-webkit-box-shadow: 0 0 6px #f8f8f8;
		box-shadow: 0 0 6px #f8f8f8;
	}
}
@-moz-keyframes loader-pulsate {
	from {
		border-color: #aaa;
		-moz-box-shadow: 0 0 6px #ccc;
		box-shadow: 0 0 6px #ccc;
	}
	to {
		border-color: #ccc;
		-moz-box-shadow: 0 0 6px #f8f8f8;
		box-shadow: 0 0 6px #f8f8f8;
	}
}

#buddypress a.loading:hover,
#buddypress input.loading:hover {
    color: #777;
}
#buddypress input[type="submit"].pending,
#buddypress input[type="button"].pending,
#buddypress input[type="reset"].pending,
#buddypress input[type="submit"].disabled,
#buddypress input[type="button"].disabled,
#buddypress input[type="reset"].disabled,
#buddypress button.pending,
#buddypress button.disabled,
#buddypress div.pending a,
#buddypress a.disabled {
    border-color: #eee;
    color: #bbb;
    cursor: default;
}
#buddypress input[type="submit"]:hover.pending,
#buddypress input[type="button"]:hover.pending,
#buddypress input[type="reset"]:hover.pending,
#buddypress input[type="submit"]:hover.disabled,
#buddypress input[type="button"]:hover.disabled,
#buddypress input[type="reset"]:hover.disabled,
#buddypress button.pending:hover,
#buddypress button.disabled:hover,
#buddypress div.pending a:hover,
#buddypress a.disabled:hover {
	border-color: #eee;
    color: #bbb;
}

/*--------------------------------------------------------------
3.7 - Forums, Tables and Topics
--------------------------------------------------------------*/
#buddypress ul#topic-post-list {
	margin: 0;
	width: auto;
}
#buddypress ul#topic-post-list li {
	padding: 15px;
	position: relative;
}
#buddypress ul#topic-post-list li.alt {
	background: #f5f5f5;
}
#buddypress ul#topic-post-list li div.poster-meta {
	color: #444;
	margin-bottom: 10px;
}
#buddypress ul#topic-post-list li div.post-content {
	margin-left: 54px;
}
#buddypress div.topic-tags {
	font-size: 80%;
}

#subscription-toggle a:before{
	
	font-size:24px;

}
#buddypress div.admin-links {
	color: #444;
	font-size: 80%;
	position: absolute;
	top: 15px;
	right: 25px;
}
#buddypress div#topic-meta {
	margin: 0;
	padding: 5px 19px 30px;
	position: relative;
}
#buddypress div#topic-meta div.admin-links {
	right: 19px;
	top: -36px;
}
#buddypress div#topic-meta h3 {
	margin: 5px 0;
}
#buddypress div#new-topic-post {
	display: none;
	margin: 20px 0 0 0;
	padding: 1px 0 0 0;
}
#buddypress table.notifications,
#buddypress table.notifications-settings,
#buddypress table.profile-fields,
#buddypress table.wp-profile-fields,
#buddypress table.messages-notices,
#buddypress table.forum {
	width: 100%;
	color:#bbb;
	font-size:12px;
	font-weight:600;
}
#buddypress a.primary,
#buddypress a.secondary{
	font-size:11px;
	color:#78c8c9;
	font-weight:600;
	text-transform: uppercase;
}
#buddypress a.secondary{color:#bbb;}
#buddypress table.notifications thead tr,
#buddypress table.notifications-settings thead tr,
#buddypress table.profile-fields thead tr,
#buddypress table.wp-profile-fields thead tr,
#buddypress table.messages-notices thead tr,
#buddypress table.forum thead tr {
	background: #eaeaea;
}
#buddypress table#message-threads {
	clear: both;
	margin: 0;
	width: auto;
}
#buddypress table.profile-fields {
	margin-bottom: 20px;
}
#buddypress table.profile-fields:last-child {
	margin-bottom: 0;
}
#buddypress table.profile-fields p {
	margin: 0;
	color:#444;
}
#buddypress table.profile-fields p:last-child {
	margin-top: 0;
}
#buddypress table.notifications tr td,
#buddypress table.notifications-settings tr td,
#buddypress table.profile-fields tr td,
#buddypress table.wp-profile-fields tr td,
#buddypress table.messages-notices tr td,
#buddypress table.forum tr td,
#buddypress table.notifications tr th,
#buddypress table.notifications-settings tr th,
#buddypress table.profile-fields tr th,
#buddypress table.wp-profile-fields tr th,
#buddypress table.messages-notices tr th,
#buddypress table.forum tr th {
	padding: 15px;
	vertical-align: middle;
}

#buddypress table.notifications tr td.label,
#buddypress table.notifications-settings tr td.label,
#buddypress table.profile-fields tr td.label,
#buddypress table.wp-profile-fields tr td.label,
#buddypress table.messages-notices tr td.label,
#buddypress table.forum tr td.label {
	border:none;
	font-weight: bold;
	color: #444;
	font-size:100%;
	display: block;
	text-align: left;
	margin: 0;
	min-width:160px;
	padding: 19px 15px;
}
#buddypress table tr td.thread-info p {
	margin: 0;
}
#buddypress table tr td.thread-info p.thread-excerpt {
	color: #444;
	margin-top: 3px;
}
#buddypress table.forum td {
	text-align: center;
}
#buddypress table.notifications tr.alt td,
#buddypress table.notifications-settings tr.alt td,
#buddypress table.profile-fields tr.alt td,
#buddypress table.wp-profile-fields tr.alt td,
#buddypress table.messages-notices tr.alt td,
#buddypress table.forum tr.alt td {
	background: #f5f5f5;
}

.profile-fields tr{
	border-bottom:1px solid #EFEFEF;
}

#buddypress table.notification-settings {
	margin-bottom: 20px;
	text-align: left;
	width:100%;
}
#buddypress #groups-notification-settings {
	margin-bottom: 0;
}
#buddypress table.notifications th.icon,
#buddypress table.notifications td:first-child,
#buddypress table.notification-settings th.icon,
#buddypress table.notification-settings td:first-child {
	display: none;
}
#buddypress table.notification-settings th.title {
	width: 80%;
}
#buddypress table.notification-settings .yes,
#buddypress table.notification-settings .no {
	text-align: center;
	width: 40px;
}
#buddypress table.forum {
	margin: 0;
	width: auto;
	clear: both;
}
#buddypress table.forum tr.sticky td {
	font-size: 110%;
	background: #fff9db;
	border-top: 1px solid #ffe8c4;
	border-bottom: 1px solid #ffe8c4;
}
#buddypress table.forum tr.closed td.td-title {
	padding-left: 35px;
}
#buddypress table.forum td p.topic-text {
	color: #444;
	font-size: 100%;
}
#buddypress table.forum tr > td:first-child,
#buddypress table.forum tr > th:first-child {
	padding-left: 15px;
}
#buddypress table.forum tr > td:last-child,
#buddypress table.forum tr > th:last-child {
	padding-right: 15px;
}
#buddypress table.forum tr th#th-title,
#buddypress table.forum tr th#th-poster,
#buddypress table.forum tr th#th-group,
#buddypress table.forum td.td-poster,
#buddypress table.forum td.td-group,
#buddypress table.forum td.td-title {
	text-align: left;
}
#buddypress table.forum tr td.td-title a.topic-title {
	font-size: 110%;
}
#buddypress table.forum td.td-freshness {
	white-space: nowrap;
}
#buddypress table.forum td.td-freshness span.time-since {
	font-size: 80%;
	color: #444;
}
#buddypress table.forum td img.avatar {
	float: none;
	margin: 0 5px -8px 0;
}
#buddypress table.forum td.td-poster,
#buddypress table.forum td.td-group {
	min-width: 140px;
}
#buddypress table.forum th#th-title {
	width: 80%;
}
#buddypress table.forum th#th-freshness {
	width: 25%;
}
#buddypress table.forum th#th-postcount {
	width: 15%;
}
#buddypress table.forum p.topic-meta {
	font-size: 80%;
	margin: 5px 0 0 0;
}
#title.form_field{
	padding:8px;
}

.activity .activity-avatar{
	max-width:80px;
	float:left;
	margin-right: 30px;
}
.activity .activity-avatar img{
	border-radius:50%;
}
.content .activity-comments ul,
.content ul.single_activity_entry{list-style: none;}

.content .activity-comments ul{
	margin-left:100px;
}

.content .activity-comments li{
	border-top: 1px dotted #EFEFEF;
	border-bottom: 1px dotted #EFEFEF;
	display: inline-block;
	padding: 20px 0;
	width: 100%;
}
.acomment-avatar{
	float:left;
	margin-right:20px;
}
.acomment-avatar img{
	max-width:64px;
	border-radius:50%;
}
.activity-inner a{color:#78c8c9;}
.bp-primary-action,.bp-secondary-action{
	font-size:11px;
	font-weight:600;
	color:#78c8c9;
	text-transform: uppercase;
}
.bp-primary-action.button,.bp-secondary-action.button{color:#FFF;}

#item-body .radio input[type="radio"]{
	margin-left:0;
	margin-right:10px;
}
/*-------------------------------------------------------------------------
3.8 - Headers, Lists and Tabs - Activity, Groups, Blogs, Forums, Profiles
-------------------------------------------------------------------------*/
#buddypress .item-body {
	margin: 20px 0;
	display: inline-block;
	width: 100%;
}
#buddypress #item-body h3{
	margin-top:0;
}
#buddypress span.activity {
	display: block;
	padding: 0;
	color: #bbb;
	font-size: 12px;
	margin-left: 90px;
	text-align: right;
	margin-top: 20px;
	display: inline-block;
	float: right;
}

#buddypress span.user-nicename {
	color: #777;
	display: inline-block;
	font-size: 120%;
	font-weight: bold;
}
#buddypress span.user-nicename+span.activity{
	margin-left:0;
}
#buddypress div#message p,
#sitewide-notice p {
	margin-top: 10px;
	background-color: #F7D743;
	border-radius:2px;
	clear: left;
	background-image:url(../images/button.png);
	border: none;
	color: #440;
	font-weight: 600;
	text-transform: uppercase;
	width:100%;

}
#buddypress div#message a,
#sitewide-notice a{font-weight:600;color:#222;}
#buddypress div#item-header {
	overflow: hidden;
	background: #232b2d;
	padding: 0;
	position: relative;
	z-index: 2;
	overflow:hidden;
	border-top-right-radius: 2px;
	border-top-left-radius: 2px;
}


#groups-dir-list,
#course-dir-list{
	background:#FFF;
	padding:20px;
}
/*
#buddypress div#item-header:after {
	position: absolute;
	bottom:0;
	right:30px;
	font-family: 'Fonticon';
	content: "\e2ea";
	color:#ccc;
	font-size:160px;
	z-index: -1;
}
.dashboard #buddypress div#item-header:after {
	content: "\e2ea";
}
.credits #buddypress div#item-header:after {
	content: "\e0f2";
}
.courses #buddypress div#item-header:after {
	content: "\e2a9";
}
.activity #buddypress div#item-header:after {
	content: "\e2b5";
}

.profile #buddypress div#item-header:after {
	content: "|";
}

.notifications #buddypress div#item-header:after {
	content: "\e005";
}

.messages #buddypress div#item-header:after {
	content: "\e124";
}

.friends #buddypress div#item-header:after {
	content: "\e012";
}

.groups #buddypress div#item-header:after {
	content: "\e24c";
}

.stats #buddypress div#item-header:after {
	content: "\e2db";
}

.forums #buddypress div#item-header:after {
	content: "\e259";
}

.settings #buddypress div#item-header:after {
	content: "\e05f";
}
*/
#buddypress div#item-header div#item-header-content {
	padding:20px 20px 10px;
	font-size:12px;
	color:#FFF;
}

#buddypress div#item-header div#item-header-content h3{
	font-size:20px;
	font-weight:600;
	margin:5px 0 0;
	color:#FFF;
	line-height:1.2;
}
#buddypress div#item-header div#item-header-content h3 a{color:#FFF;font-weight:600;}
#latest-update h6{
	font-size:12px;
	color:#FFF;
	opacity:0.6;
	font-weight:600;
}
#latest-update h6 a{
	font-size:11px;
	text-transform: uppercase;
	color:#78c8c9;
	font-weight:600;
	}
#latest-update h6 a:after{
	content:'+';
}

#buddypress div#item-header h2 {
	line-height: 120%;
	margin: 0 0 15px 0;
}
#buddypress div#item-header h2 a {
	color: #777;
	text-decoration: none;
}
#buddypress div#item-header img.avatar {
	width:100%;
	height:auto;
	border-radius:2px;
}
#buddypress div#item-header h2 {
	margin-bottom: 5px;
}
#buddypress div#item-header h2 span.highlight {
	font-size: 60%;
	font-weight: normal;
	line-height: 170%;
	vertical-align: middle;
	display: inline-block;
}
#buddypress div#item-header h2 span.highlight span {
	background: #a1dcfa;
	color: #fff;
	cursor: pointer;
	font-weight: bold;
	font-size: 80%;
	margin-bottom: 2px;
	padding: 1px 4px;
	position: relative;
	right: -2px;
	top: -2px;
	vertical-align: middle;
}
#buddypress div#item-header-content .location{
	margin: 0;
font-size: 11px;
font-weight: 600;
text-transform: uppercase;
}
#buddypress div#item-header div#item-meta {
	color: #FFF;
	font-weight:600;
	overflow: hidden;
	margin: 5px 0;
}
#buddypress div#item-header .activity+div#item-meta{
	width:100%;

}

#buddypress div#item-header div#item-meta .students{
	margin-top:10px;
}
#buddypress div#item-header div#item-meta .students i{
	float:left;
	font-size:16px;
	margin-right:8px;
}
#buddypress div#item-header div#item-actions {
	margin: 5px 15px;
	color:#FFF;
}
#buddypress div#item-header div#item-actions h3 {
	margin: 0 0 5px 0;
	color: #FFF;
	font-size: 14px;
	text-transform: uppercase;
	font-weight: 600;
}
#buddypress div#item-header ul {
	margin-bottom: 15px;
	overflow: hidden;
}
#buddypress div#item-header ul h5,
#buddypress div#item-header ul span,
#buddypress div#item-header ul hr {
	display: none;
}
#buddypress div#item-header ul li {
	float: left;
	list-style: none;
}
#buddypress div#item-header ul img.avatar,
#buddypress div#item-header ul.avatars img.avatar {
	height: 48px;
	margin: 2px;
	width: 48px;
	border-radius: 40px;
	border: 4px solid #313b3d;
}
#buddypress ul.item-list h4{
	font-size:14px;
}

#buddypress div#item-header div.generic-button,
#buddypress div#item-header a.button {
	float: left;
	margin: 10px 10px 0 0;
}
#buddypress div#item-header div#message.info {
	line-height: 80%;
}
#buddypress ul.item-list {
	border-top: 1px solid #f6f6f6;
	width: 100%;
	display: inline-block;
	list-style: none;
	clear: both;
	margin: 0;
	padding: 0;
}
body.activity-permalink #buddypress ul.item-list,
body.activity-permalink #buddypress ul.item-list li.activity-item {
	border: none;
}
#buddypress ul.item-list li {
	border-bottom: 1px solid #f6f6f6;
	padding: 20px 0;
	margin: 0;
	position: relative;
	list-style: none;
	clear: both;
	display: inline-block;
	width: 100%;
}
#buddypress ul.single-line li {
	border: none;
}

#buddypress ul.item-list li img.avatar {
	float: left;
	max-width:75px;
	height:auto;
	margin: 0 20px 0 0;
	border-radius:50%;
	box-shadow: 0 0 1px rgba(0,0,0,0.1);
}

#buddypress .widget ul.item-list li img.avatar{
	max-width:48px;
}

#buddypress ul.item-list li div.item-title,
#buddypress ul.item-list li h4 {
	font-weight: normal;
	margin: 0;
	text-transform: uppercase;
	font-weight:600;
	font-size:16px;
}
#buddypress ul.item-list li div.item-title a{font-weight:600;}
#buddypress ul.item-list li div.item-title span {
	display: block;
	font-weight: 600;
	font-size: 11px;
	text-transform: uppercase;
	color: #bbb;
}

.item-title i{
	font-size:12px;
	opacity:0.6;
	font-weight:600;
}

#buddypress ul.item-list li div.item-title span.update{
	display: block;
	color:#444;
	font-size:12px;
	text-align: right;
}
#buddypress ul.item-list li div.item-title span.activity-read-more{
	text-align: right;
	float:right;
}
#buddypress ul.item-list li div.item-desc {
	color: #444;
	font-size:14px;
	font-weight:400;
	margin: 5px 0 0;
}
#buddypress ul.item-list li div.action {
	position: absolute;
	top: 15px;
	right: 0;
	text-align: right;
}
#buddypress ul.item-list li div.meta {
	color: #999;
	margin-top: 10px;
	font-weight: 600;
	font-size: 12px;
	border: none;
	text-transform: uppercase;
}
#buddypress ul.item-list li h5 span.small {
	display: block;
	margin-top:5px;
	font-weight: normal;
}
#buddypress div.item-list-tabs {
	background: transparent;
	clear: left;
	display: inline-block;
	overflow: hidden;
	width:100%;
	background:#313B3D;
	padding:0;
	border-top-left-radius: 2px;
	border-top-right-radius: 2px;
}
#buddypress #item-nav div.item-list-tabs{
	border-radius:0;
	border-bottom-left-radius: 2px;
	border-bottom-right-radius: 2px;
	padding:0;
}

#buddypress #item-nav div.item-list-tabs li a{
	padding:12px 20px;
	text-transform: uppercase;
}

#buddypress div.item-list-tabs ul {
	margin: 0;
	padding: 0;
}
#buddypress div.item-list-tabs ul li {
	float: left;
	position: relative;
	margin: 0;
	list-style: none;
	width: 100%;
	border-bottom:1px solid rgba(255,255,255,0.1);
}

#buddypress div.item-list-tabs ul li.current{
	border-color:#78c8ce;
}
/*
#buddypress div.item-list-tabs ul li.current:after{
	position: absolute;
	top: 12px;
	right: 0;
	content: '';
	border: 8px solid;
	border-color: transparent #F9F9F9 transparent transparent;
}
*/

#buddypress #group-create-tabs.item-list-tabs ul li{
	max-width:151px;
	margin-right:10px;
	border:none;
	border-right:1px solid rgba(255,255,255,0.1);
}
#buddypress #group-create-tabs.item-list-tabs ul li:last-child{
	border:none;
}

#buddypress div.item-list-tabs#subnav ul li a{
	color:#444;
	font-size:11px;
	background: #FFF;
	text-transform: uppercase;
	padding: 11px 2px;
	text-transform: uppercase;
	border: 1px solid #EFEFEF;
	border-left: none;
	border-bottom: none;
}

.highlight + h1{
	margin:5px 0;
}
#buddypress div.item-list-tabs#subnav ul li.current a{
	background-color:#78c8ce;
	background-image:url(../images/button.png);
	border-color:#78c8ce;
	color:#FFF;
}
.item-action-buttons{
	margin: 10px 0;
	display: inline-block;
	width:100%;
}
.instructor_action_buttons{
	display: inline-block;
	border-top: 1px solid #EFEFEF;
	border-left: 1px solid #EFEFEF;
}
.instructor_action_buttons li{
	float:left;
	max-width:120px;
	text-align:center;
	border-right: 1px solid #EFEFEF;
	width: auto !important;
	clear: none !important;
	padding: 10px 15px 0 !important;;
}
.action_icon{
	font-size: 64px;
	color: #ddd;
	position: relative;
}

.action_icon span{
	font-size: 11px;
	position: absolute;
	top: -2px;
	right: -6px;
	padding: 2px 6px;
	background: #78c8c9;
	color: #FFF;
	border-radius: 50%;
}

#buddypress div.item-list-tabs#subnav ul li {
	margin:0;
	float: left;
	display: inline-block;
	text-align: center;
	max-width: 120px;
	border-bottom:none;
}

#buddypress div.item-list-tabs#subnav ul li.switch_view{
	width: auto;
	display: inline-block;
	float: right;
}

#buddypress div.item-list-tabs#subnav ul li.switch_view a{cursor:pointer;
	padding: 4px 5px;
	float: left;
	background: #EFEFEF;
	color: #bbb;border:none;
	font-size: 12px;
	line-height: 12px;border-radius: 2px;
}
#buddypress div.item-list-tabs#subnav ul li.switch_view a.active{color:#FFF;background:#78c8c9;}
#buddypress div.item-list-tabs#subnav ul li#instructor-courses-personal-li{max-width:150px;}
#buddypress div.item-list-tabs#subnav ul li:first-child a{
	border-top-left-radius: 2px;
	border-left:1px solid #EFEFEF;
}


#buddypress div.item-list-tabs#subnav ul li:last-child a{
	border-top-right-radius: 2px;
}

#buddypress div.item-list-tabs ul li.last {
	float: right;
	margin: 7px 0 0;
}
#buddypress div.item-list-tabs#subnav ul li.last {
	padding:0;
	float:right;
	color:#FFF;
}

#buddypress div.item-list-tabs ul li.last select {
	width: 100px;
}
#buddypress div.item-list-tabs ul li a,
#buddypress div.item-list-tabs ul li span {
	display: block;
	color:rgba(255,255,255,0.6);
	padding: 18px 24px;
	font-size:11px;
	font-weight:600;
	text-transform: uppercase;
	text-decoration: none;
}
#buddypress div.item-list-tabs ul li a:hover{
	color:#FFF;
}
#buddypress div.item-list-tabs ul li a span {
	font-size:12px;
	display: inline-block;
	padding:2px 7px;
	margin:-2px 0;
	color:#FFF;
	background:#78c8ce;
	border-radius:20px;
	float:right;
}
#buddypress div.item-list-tabs ul li.selected a,
#buddypress div.item-list-tabs ul li.current a {
	color: #FFF;
	background-color:#78c8ce;
	background-image:url(../images/button.png);
}
#buddypress div.item-list-tabs ul li.selected a span,
#buddypress div.item-list-tabs ul li.current a span,
#buddypress div.item-list-tabs ul li a:hover span {
	background-color: #eee;
	color:#78c8ce;
}
#buddypress div.item-list-tabs ul li.selected a span,
#buddypress div.item-list-tabs ul li.current a span {
	background-color: #fff;
}
#buddypress div#item-nav ul li.loading a {
	background-position: 88% 50%;
}
#buddypress div.item-list-tabs#object-nav {
	margin-top: 0;
}
#buddypress div.item-list-tabs#subnav {
	padding: 0;
	margin: 0 0 20px;
	border-bottom: 1px solid #EFEFEF;
	background: none;
	display: inline-block;
	width: 100%;
	font-weight: 600;
}
#buddypress div.item-list-tabs#subnav.notmyprofile{
	position: absolute;
	top: 20px;
	right: 20px;
	border:none;
	display: inline-block;
	z-index: 99;
}

#buddypress div.item-list-tabs+div.item-list-tabs#subnav{
	padding:20px;
	background:#FFF;
}
#buddypress .dir-form  div.item-list-tabs#subnav{
	top:0;
}
#buddypress #admins-list li,
#buddypress #mods-list li,
#buddypress #members-list li {
	overflow: auto;
	list-style: none;
}

/*-------------------------------------------------------------------------
3.8 - BUDDYSIDEBAR
-------------------------------------------------------------------------*/

.buddysidebar .widget+.widget{
	margin-top:30px;
}

.widget .item-options{
	padding:0;
	color:rgba(255,255,255,0.2);
	background: #313b3d;
	text-align: center;
	border-radius: 2px;
}

.widget .item-options a{
	font-weight:600;
	padding:8px;
	color:#FFF;
	text-transform: uppercase;
	display: inline-block;
}

#buddypress .widget_title{
	font-size:16px;
	font-weight:600;
}
#buddypress .widget ul.item-list{
	border:none;
}

#buddypress .widget ul.item-list li{
	border-color:#dfdfdf;
}
#buddypress .widget ul.item-list li div.item-title, #buddypress .widget ul.item-list li h4{
	font-size:14px;
}
#buddypress .widget span.activity{
	font-size: 11px;
	text-transform: uppercase;
	font-weight: 600;
	background: none;
}

.item-list-tabs#subnav label{
	display: inline-block;
	float:left;
	margin:0;
}

.item-list-tabs#subnav .last label{
	display: none;
}

#group-dir-search,
#member-dir-search{
	display: inline-block;
	min-width:250px;
}

.item-list-tabs .message-search label{display: inline-block;}
/*--------------------------------------------------------------
3.9 - Private Messaging Threads
--------------------------------------------------------------*/
#buddypress table#message-threads tr.unread td {
	background: #fff9db;
	border-top: 1px solid #ffe8c4;
	border-bottom: 1px solid #ffe8c4;
	font-weight: bold;
}
#buddypress li span.unread-count,
#buddypress tr.unread span.unread-count {
	background: #fa7252;
	color: #fff;
	font-weight: bold;
	padding: 2px 7px;
	border-radius:20px;
}
#buddypress div.item-list-tabs ul li a span.unread-count {
	padding: 1px 6px;
	color: #fff;
}
#buddypress div.messages-options-nav {
	background: #eee;
	font-weight:600;
	margin: 0;
	padding: 8px 15px;
	text-align: right;
}
#buddypress div.messages-options-nav a{font-weight:600;text-transform: uppercase; color:#fa7252;}
#buddypress div#message-thread div.message-box {
	margin: 0;
	padding: 15px;
}
#buddypress div#message-thread div.alt {
	background: #f4f4f4;
}
#buddypress div#message-thread p#message-recipients {
	margin: 10px 0 20px 0;
}
#buddypress div#message-thread img.avatar {
	float: left;
	margin: 0 10px 0 0;
	vertical-align: middle;
}
#buddypress div#message-thread strong {
	font-size: 100%;
	margin: 0;
}
#buddypress div#message-thread strong a {
	text-decoration: none;
}
#buddypress div#message-thread strong span.activity {
	margin-top: 4px;
}
#buddypress div#message-thread div.message-metadata {
	overflow: hidden;
}
#buddypress div#message-thread div.message-content {
	margin-left: 45px;
}
#buddypress div#message-thread div.message-content a{
	color:#78c8c9;
	font-weight:600;
}
#buddypress div#message-thread div.message-options {
	text-align: right;
}
#buddypress #message-threads img.avatar {
	max-width: 75px;
	border-radius:50%;
}
#buddypress div.message-search {
	float: right;
	margin: 0;
}
#buddypress div.message-search label{margin:0;}
#buddypress div.message-search #messages_search{
	padding: 5px 20px;
	border: 1px solid #EFEFEF;
}
.members.dir-list{
	display: inline-block;
	padding: 20px;
	background:#FFF;
	width:100%;
	border-radius:2px;
}

.activity-read-more a{
	color:#78c8ce;
}
#buddypress .sidebar{
	padding-top:30px;
}
/*--------------------------------------------------------------
3.10 - HORIZONTAL VERSION
--------------------------------------------------------------*/
#buddypress #groups-directory-form  div.item-list-tabs,
#buddypress #course-directory-form  div.item-list-tabs{
	width:100%;
}
#grouptitle,
#memberstitle,
#activitytitle{
	padding-top:30px;
}

#buddypress #groups-directory-form div.item-list-tabs#subnav,
#buddypress #course-directory-form div.item-list-tabs#subnav{
	background:#FFF;
}
#buddypress #groups-directory-form div.item-list-tabs ul li,
#buddypress #course-directory-form div.item-list-tabs ul li,
#buddypress #members-directory-form div.item-list-tabs ul li{
	border:none;
	width: 235px;
	border-right: 1px solid rgba(255,255,255,0.1);
}
#buddypress #members-activity div.activity-type-tabs ul li{
	border:none;
	width: 169px;
	border-right: 1px solid rgba(255,255,255,0.1);
}

#buddypress #whats-new-form div.item-list-tabs{
	width:100%;
	margin:30px 0;
}

#activityform,
.activity_content{
	padding:20px;
	background:#FFF;
	border-radius:2px;
}

/*--------------------------------------------------------------
3.10 - Extended Profiles
--------------------------------------------------------------*/
.button.bp-secondary-action,
.button.bp-primary-action{
	font-size:11px;
	font-weight:600;
	letter-spacing: 0;
}
.action .button,
#buddypress .button.confirm{
	font-size:11px !important;
	font-weight:600;
	padding:4px 12px !important;
	letter-spacing: 0;
	margin:0 2px;
}
#buddypress div.profile h4 {
	margin-bottom: auto;
	margin: 0 0 15px 0;
	padding:0 15px 15px;
	border-bottom:1px solid #EFEFEF;
	font-weight: 600;
	text-transform: uppercase;
}
.bp-widget+.bp-widget{
	margin-top:15px;
}
#buddypress #profile-edit-form ul.button-nav {
	margin-top: 15px;
}
body.no-js #buddypress .field-visibility-settings-toggle,
body.no-js #buddypress .field-visibility-settings-close {
	display: none;
}
#buddypress .field-visibility-settings {
	display: none;
	margin-top: 10px;
}
	body.no-js #buddypress .field-visibility-settings {
		display: block;
	}
#buddypress .current-visibility-level {
	font-weight: bold;
	font-style: normal;
}
#buddypress .field-visibility-settings,
#buddypress .field-visibility-settings-toggle,
#buddypress .field-visibility-settings-notoggle {
	color: #444;
}
#buddypress .field-visibility-settings-toggle a,
#buddypress .field-visibility-settings a {
	font-size: 80%;
}
body.register #buddypress div.page ul {
	list-style: none;
}
#buddypress .standard-form .field-visibility-settings label {
	margin: 0;
	font-weight: normal;
}
#buddypress .field-visibility-settings legend,
#buddypress .field-visibility-settings-toggle {
	font-style: italic;
}

/*--------------------------------------------------------------
3.11 - Widgets
--------------------------------------------------------------*/

.widget.buddypress div.item-avatar img.avatar {
	float: left;
	margin: 0 10px 15px 0;
}

.widget.buddypress span.activity {
	display: inline-block;
	font-size: 80%;
	opacity: 0.8;
	padding: 0;
}

.widget.buddypress div.item-options {
	font-size: 90%;
	margin: 0 0 1em 0;
	padding: 1em 0;
}

.widget.buddypress div.item{
	margin:0 0 1em 0;
}

.widget.buddypress div.item-meta,
.widget.buddypress div.item-content {
	font-size: 11px;
	margin-left: 38px;
}

.widget.buddypress ul.item-list img.avatar {
	height: 20px;
	margin-right: 10px;
	width: 20px;
}
.widget.buddypress div.item-avatar img {
	height: 40px;
	margin: 1px;
	width: 40px;
}

.widget.buddypress div.avatar-block{
	overflow: hidden;
}

.widget.buddypress #bp-login-widget-form label {
	display: block;
	margin: 1rem 0 .5rem;
}

.widget.buddypress #bp-login-widget-form #bp-login-widget-submit {
	margin-right: 10px;
}

.widget.buddypress .bp-login-widget-user-avatar {
	float: left;
	width: 60px;
}

.widget.buddypress .bp-login-widget-user-links > div {
	padding-left: 60px;
}

.widget.buddypress .bp-login-widget-user-links > div {
	margin-bottom: .5rem;
}

.widget.buddypress .bp-login-widget-user-links > div.bp-login-widget-user-link a {
	font-weight: bold;
}



/*-------------------------------------------------------------------------
3.8 - STAR RATING
-------------------------------------------------------------------------*/
#course-dir-list .item-avatar,
#course-list .item-avatar{
	max-width: 280px;
	float: left;
	margin-right: 20px;
}
#buddypress ul.item-list.grid{padding-top:15px;}
#buddypress ul.item-list.grid li{
	width:388px;
	margin:15px 0;
	float:left;
	border: 1px solid #EFEFEF;
	padding: 15px;
	border-radius: 2px;
	clear:none;
}

@media (min-width: 992px) and (max-width: 1200px){
	#buddypress ul.item-list.grid li{
		width:312px;
	}
}

@media (min-width: 768px) and (max-width: 991px){
	#buddypress ul.item-list.grid li{
		width:200px;
	}
}

@media (max-width: 768px){
	#buddypress ul.item-list.grid li{
		width:100%;
	}
}

#buddypress ul.item-list.grid li:nth-child(2n){
	float:left;
	margin-left:15px;
}
#course-list.grid .item-avatar{max-width:100%;}

#buddypress ul.item-list.grid li:nth-child(2n+1){
	clear:both;
	margin-right:15px;
}
#course-list.grid .item-avatar{
	width:auto;
	float:none;
	margin:0 0 20px 0;
}
#course-list.grid .instructor_course .item-avatar{
	float:left;
}
.item-instructor .instructor_course+.instructor_course{
	clear:both;
}

#course-dir-list .item-avatar img{
	border-radius: 2px;
}

#course-list .item{
	margin-left:300px;
}

#course-list.grid .item{
	margin:0;
}

#buddypress #course-list.grid li div.item-desc{display: none;}

#buddypress ul.item-list li .item-instructor img.avatar{
	max-width: 36px;
	border-radius:50% !important;
	margin-right:0;
}

.item-instructor{
	padding:15px 0 0;
	border-top:1px solid #EFEFEF;
}
.course_instructor{
	margin:0;
	font-size: 13px;
	text-transform: uppercase;
	font-weight:600;
}
.course_instructor a{font-weight:600;}
.course_instructor span{
	display: block;
	color: #bbb;
	font-size: 11px;
}

.item-instructor strong{
	float:right;
	display: inline-block;
	min-width:108px;
}
.item-instructor strong span{
	display: block;
	font-size: 11px;
	opacity: 0.5;
	text-transform: uppercase;
}

.item-instructor strong span.amount,
.item-instructor strong{
	color:#5D951F;
	font-size:20px;
	text-align: center;
	opacity: 1;
	margin-right:5px;
}

.item-instructor strong del span.amount{
	text-decoration: line-through;
	opacity: 0.4;
	float:right;
}

/*-------------------------------------------------------------------------
3.8 - STAR RATING
-------------------------------------------------------------------------*/
#course-list .item-meta{
	display: block;

}

#buddypress #course-list li div.item-desc{
	margin:15px 0;
}

.item-meta .students{
	display: inline-block;
	font-size:12px;
	font-weight:600;
	line-height: 16px;
	margin:5px 0;
	color:#bbb;
}
.item-meta .students i{
	line-height: 0;
	float: left;
	font-size: 18px;
	margin:-2px 10px 0 10px;
}
.star-rating{
	margin:5px 0;
	line-height: 16px;
	font-weight:600;
	width:200px;
	display: inline-block;
	color:#bbb;
	font-size:12px;
}
.star-rating span{
	display: inline-block;
	float:left;
	width:16px;
	height:16px;
	background: url(../images/stars.png) 0 100%;
}
.star-rating span:last-child{
	margin-right:5px;
}
.star-rating span.fill{
	display: inline-block;
	width:16px;
	height:16px;
	background: url(../images/stars.png) 0 0;
}

.star-rating span.half{
	display: inline-block;
	width:16px;
	height:16px;
	background: url(../images/stars.png) 0 50%;
}

.course_reviews{
	margin-top:30px;
}
.review_title{
	padding: 10px 0;
	text-align: center;
	background: #EFEFEF;
	margin: 10px -20px;
}
#buddypress .review_title+div#message{margin-top:20px;}
.review_course.unit_button.button {
 	word-break: break-word;
}
/*-------------------------------------------------------------------------
3.8 - ALL Courses
-------------------------------------------------------------------------*/

.single_course{
	display: inline-block;
	width:100%;
	margin:0 0  20px;
	background:#F6F6F6;
	border-radius:2px;
}

.single_course .thumb{
	float:left;
	padding:15px;
	margin-right:15px;
}
.single_course .inside{
	padding:20px 30px 10px;
}

.single_course .inside h4{
	margin:0;
}
.single_course .inside ul{
	display: inline-block;
	margin-top:10px;
}
.single_course .inside ul li{
	float:left;
	margin-right:10px;
}
.single_course .thumb img{
	height:88px;
	width:auto;
	border-radius:2px;
}

.bp_simple_post_uploads_input li{
	clear:both;
	margin:10px 0;
	display: inline-block;
	width:100%;
}
.simple-post-custom-fields li {
	display: inline-block;
	width:100%;
	margin:5px 0;
}
#buddypress .simple-post-custom-fields li label{
	width:240px;
	margin:0 0 10px;
	float:left;
}
.simple-post-custom-fields li small{
	margin-left: 20px;
}
.simple-post-custom-fields{
	background: #F6F6F6;
	padding: 30px;
	margin:30px 0;
	border-radius: 2px;
}
.simple-post-custom-fields h3{
	border-bottom: 1px solid #DDD;
	padding: 0 0 10px 0;
	margin-bottom:20px;
}
.simple-post-custom-fields .chosen-container{
	min-width:240px;
}
#buddypress .simple-post-custom-fields input[type="text"]{
	width:240px;
}
.simple-post-custom-fields .chosen-container-multi .chosen-choices li.search-choice{
	width:auto;
}
.bp_simple_post_uploads_input input[type="file"]{
	display: inline-block;
padding: 20px;
background: #F6f6f6;
border-radius: 2px;
}
.bp_simple_post_uploads_input  label{
	font-size:16px;
	margin:0;
}

.bp_simple_post_uploads_input .thumbimage{
	width:108px;
	float:left;
	margin-right:20px;
}

.bp_simple_post_uploads_input img{
	height:auto;
	border-radius:2px;
}
.single_course{
	border:1px solid #DDD;
}
.single_course .controls{
	clear:both;
	width:100%;
	margin:0 0 -5px;
	display: inline-block;
	border-top:1px solid #DDD;
}
.single_course .controls li{
	float:left;
}
.single_course .controls li a{
	padding:10px 20px;
	font-size:11px;
	background-image: url(../images/button.png);
	display: inline-block;
	font-weight:600;
	border-right:1px solid #DDD;
}
.single_course .controls li a:hover{background-image:none;}
/*-------------------------------------------------------------------------
3.8 - MDOULES
-------------------------------------------------------------------------*/

#add_module_form li{
	margin:15px 0;
}

#module_structure{
	display: block;
	background: #f6f6f6;
	padding: 30px;
	border-radius: 2px;
}

#module_structure ul{
	padding-left:30px;
	margin-left:30px;
	border-left:1px solid #DDD;
}
#module_structure ul li{position: relative;}
#module_structure ul li:after{
	content:'';
}

#add_module_form .form_field{
	width:240px;
}
/*-------------------------------------------------------------------------
3.8 - UNITS
-------------------------------------------------------------------------*/

.unitforum{
	text-align: center;
	padding: 15px 0;
	margin: 30px -20px -20px;
	background: #F6F6F6;
}

.unitattachments + .unitforum{
	margin: 0 -20px -20px -20px;
}
.unitforum a{
	font-size:12px;
	color:#F16645;
	font-weight:600;
	text-transform: uppercase;
}
.single.single-unit .unitforum{
	margin:0 -30px 15px -30px;
}
.unitattachments{
	background: #fbfbfb;
	padding: 20px;
	margin: 20px -20px 0;
}

.single.single-unit .unitattachments{
	margin:20px -30px 0;
}
.unitattachments h4{
	margin:0 0 10px;
	padding: 0 0 10px;
	border-bottom:5px solid #DDD;
}

.unitattachments h4 span{
	float:right;
	color:#78c8ce;
	font-weight:600;
	max-width:48px;
}

.unitattachments h4 span i{
	float: left;
	margin-right: 5px;
	margin-top: 2px;
}

.unitattachments li{
	clear:both;
	display: inline-block;
	width:100%;
	border-bottom:1px dotted #DDD;
	padding:8px 0;
}
.unitattachments li a:after{
	content:'DOWNLOAD';
	float:right;
	margin-top:5px;
	color:#78c8ce;
	font-size:11px;
	text-transform: uppercase;
	font-weight:600;
}
.unitattachments li a{
	font-size:12px;
	text-transform: uppercase;
}

.unitattachments li > i{
	float:left;
	margin-right:30px;
	font-size:16px;
}
.simple-post-tax-wrap.simple-post-tax-module-wrap{
	background: #F6F6F6;
	margin: 30px 0;
	padding: 30px;
	border-radius: 2px;
}
.simple-post-tax{
	max-height:240px;
	overflow-y:scroll;
}

.unit_module{
	padding: 10px 15px 20px;
	background: #Fbfbfb;
	border-radius: 2px;
	margin-bottom: 20px;
}

.unit_module ul{
	margin-left:20px;
	padding-left:20px;
	border-left:1px solid #DDD;
}

.unit_module ul li{
	padding: 10px 20px;
	background: #F6f6f6;
	border-radius: 2px;
	margin-bottom:5px;
}
.unit_module ul.actions{
	float:right;
	} 
.unit_module ul.actions	li{
	float:left;
	margin-left:10px;
	padding:0;
}

.unit_module ul.actions	li span{
	font-size:12px;
	text-transform: uppercase;
	font-weight:600;
	color:#fa7252;
}

.bp-simple-post-form{
	margin:30px 0;
}
/*-------------------------------------------------------------------------
3.8 -SINGLE COURSE
-------------------------------------------------------------------------*/
.course_title{
	position: relative;
}
.course_title h1{
	margin:0;
	font-weight:600;
}
.course_title h6{
	font-size:15px;
	line-height: 1.4;
}
.widget.pricing{
	position: relative;
	background:#FFF;
	padding:10px 20px 10px;
	border-radius:2px;
	box-shadow:0 1px 1px #EEE;
}

.course_sharing{
	display: inline-block;
	padding:0;
	width:100%;
}
.course_sharing a{font-size:20px;} 
.course_sharing > ul.socialicons{
	padding-top:20px;
}
.course_button.button{
	padding: 20px 32px !important;
	background-color: #78c8ce;
	width:100%;
	text-align: center;
}
.widget.pricing .course_sharing .socialicons.round li > a:hover,
.widget.pricing .course_sharing .socialicons.square li > a:hover{
	color:#FFF;
	background:#78c8c9;
}

.unit_button.button{
	padding: 12px 24px !important;
	background-color: #78c8ce;
	width:100%;
	text-align: center;
	border:1px solid #78c8ce;
	margin:0;
}

.unit_content .unit_button.button{
	margin-top:20px !important;
}
.item-credits{
	float:right;
}

.item-credits{
	font-family:'Oswald',sans-serif;
	font-size:16px;
	margin:12px 0 5px 0;
	color:#79c989;
	text-align: center;
}
.item-credits span.subs{
	display: block;
	font-size:11px;
	text-transform: uppercase;
	font-weight:600;
	font-family:sans-serif;
}
del{opacity:0.3;}
.item-credits del{opacity:0.3;float:right;}
.credits,
.credits > strong > span.amount{
	font-family:'Oswald',sans-serif;
	font-size:16px;
	margin:0;
	color:#70c989;
	text-transform: uppercase;
}
.credits span.amount{display: block;}
.credits ins{
	float:left;
	margin-right:5px;
	opacity: 1;
}
.credits del{opacity: 0.3;font-size: 16px;}
.credits > strong > span,
.credits span.subs{
	display: block;
	margin-left:5px;
	font-size:11px;
	font-family:sans-serif;
	font-weight:600;
	line-height: 1;
}

.students_undertaking{
	display: inline-block;
	width:100%;
	margin:20px 0;
	padding:20px 0;
	border-top:1px solid #EFEFEF;
}

.students_undertaking ul{
	display: inline-block;
	float:right;
}
.students_undertaking li{
	float:left;
	margin-left:5px;
}
.students_undertaking strong{
	color:#bbb;
	font-size:12px;
	float:left;
	padding-top:12px;
}
.students_undertaking li img{
	max-width:48px;
	border-radius:50%;
	border:4px solid #F6f6f6;
}

.course_details{
	margin-top:20px;
}

.course_details li{
	font-size: 12px;	
	font-weight: 600;
	text-transform: uppercase;
	border-bottom: 1px solid #ddd;
}

.widget .course_details li{
	padding:6px 0;
	font-size: 12px;
}

.widget .course_details li a{
	font-weight:600;
}
.course_details li i{
	float: right;
	font-size: 16px;
	line-height: 1;
	margin-right: 10px;
}
.course_details li i.icon-wallet-money{
	margin-top:12px;
}
.course_title + #wplms-calendar{
	margin-top: 20px;
}
#wplms-calendar th{
	padding:5px 0;
	text-align: center;
	border: 1px solid #Efefef;
}
/*-------------------------------------------------------------------------
3.8 - COURSE CURRICULUM
-------------------------------------------------------------------------*/

.course_curriculum .course_section{
	display: inline-block;
	width: 100%;
	margin: 10px 0 0;
	padding: 10px 0;
	border-bottom: 4px solid #EFEFEF;
}

.course_lesson{
	display: inline-block;
	width: 100%;
	margin:0;
	padding: 10px 0;
	border-bottom: 1px solid #EFEFEF;
	position: relative;
}


.course_lesson i{
	float: left;
	font-size: 16px;
	margin-right: 10px;
}

.course_lesson h6{
	margin:0 110px 0 20px;
	font-size:15px;
}

.course_lesson h6 a span{
	padding: 4px;
	margin: 0 10px;
	max-width: 48px;
	text-align: center;
	border: 1px solid #70c989;
	background-color: #70c989;
	color: #FFF;
	background-image: url(../images/button.png);
	border-radius: 2px;
	font-weight: 600;
	line-height: 1;
	position: relative;
	top:0;
}
.course_lesson span{
	position: absolute;
	top:10px;
	right: 0;
	font-size: 11px;
	font-weight: 600;
	text-transform: uppercase;
}

/*-------------------------------------------------------------------------
3.8 - QUIZ
-------------------------------------------------------------------------*/

.quiz_results li,
.quiz_questions li{
	padding: 12px 0;
	border-bottom: 1px dotted #EFEFEF;
	display: inline-block;
	width:100%;
}
.quiz_results li span strong{
	display: block;
	font-size: 24px;
	text-align: center;
	color: #444;
}
#total_marks{
	font-size: 24px;
	margin: 15px 0;
}
#total_marks strong{
	float:right;
}
.marking{
	clear:both;
	display: block;
	float:left;
	margin:15px 0 10px;
}
.quiz_results li > span{
	color:#bbb;
	float:right;
	font-size:11px;
	font-weight:600;
	text-transform: uppercase;
	width:120px;
}
.quiz_results li i{
	font-size:16px;
	float: left;
	margin: 0 5px 0 0;
}
.quiz_results li a{
	text-transform: uppercase;
	font-weight: 600;
	font-size: 14px;
}
.orderquestions{
	margin-left:240px;
}
.quiz_questions .q{
	font-size:16px;
	font-weight:600;
}
.quiz_questions strong{
	color:#bbb;
	font-size:12px;
	margin-right:30px;
}
.quiz_questions strong+strong{
	margin-left:30px;
}
.quiz_questions span{
	float:right;
	font-size:12px;
	font-weight:600;
	text-transform: uppercase;
	margin-left:30px;
	color:#bbb;
}

.quiz_questions strong span{
	float:none;
	margin-left:10px;
}

.repeatablelist,
.add_repeatable{
	margin-left:240px;
}

.sort_handle{
display: inline-block;
font-size: 24px;
float: left;
color: #999;
line-height: 0;
margin:2px 5px 0 0;
}

.profile .bp-widget.instructor{display: none;}

.commentrating{
	font-size: 12px;
	font-weight: 600;
	color: #bbb;
}

.commentrating input[type="radio"]{
	margin-right:3px;
}
/*=== Quiz Retake Form ===*/
.quiz_retake_form{
border-top: 1px solid #EFEFEF;
padding-top: 20px;
display: inline-block;
width: 100%;
color:#BBB;
}
.quiz_retake_form input[type="submit"]{
	float:right;
}

#prev_results{
	color:#bbb;
	padding:10px 0 6px;
	border-bottom:5px solid #EFEFEF;
	width:100%;
}
#prev_results a{
	color:#bbb;
	font-weight:600;
}
#prev_results a:after{
	font-family: 'fonticon';
	content: "\e05d";
	color:#BBB;
	float:right;
	font-size:20px;
}
#prev_results a.show:after{
	content: "\e092";
}
.prev_quiz_results{
	display: none;
}
.prev_quiz_results.show{
	display: block;
}
.prev_quiz_results li{
	padding:6px;
	color:#bbb;
	border-bottom:1px solid #EFEFEF;
}

.course_progressbar.progress{
	margin:45px 0 0;
	background:#FFF;
	overflow: visible;
}
.progress.course_progressbar .bar{
	background:#70c989;
	border-radius:20px 0 0 20px;
	padding:4px 2px;
}

.footerwidget .item-options{
padding: 5px;
margin: 0 0 10px;
border-radius: 2px;
text-align: center;
background: rgba(0,0,0,0.2);
color: rgba(255,255,255,0.8);
}
.footerwidget .item-options{
font-weight: 600;
font-size: 11px;
text-transform:uppercase;
color: rgba(255,255,255,0.8);
}
/*-------------------------------------------------------------------------
3.8 - RESPONSIVE FIXES
-------------------------------------------------------------------------*/
@media (max-width: 767px) {
	#buddypress #members-activity div.activity-type-tabs ul li{
		width:100%;
	}
	header #searchicon{
		position: absolute;
		top: -80px;
		right: 100px;
		font-size:20px;
	}
	header #searchform{margin-top:30px;}

	.blogpost .excerpt.thumb,
	.blogpost .excerpt{
		margin-left:0;
	}
	.blogpost .meta{margin:0 10px 10px 0;}

}
@media (max-width: 992px) and (min-width: 767px){
	#course-dir-list .item-avatar,
	#course-list .item-avatar{
		width:200px;
	}
	#course-list .item{
		margin-left: 220px;
	}
	.blogpost .excerpt.thumb{
		margin-left:0;
	}
	.blogpost .excerpt.thumb+h3{
		clear:both;
		margin-top: 20px;
		display: inline-block;
		width: 100%;
	}
}

@media (max-width: 540px) {
	#course-dir-list .item-avatar,
	#course-list .item-avatar{
		width:100%;
		max-width:100%;
	}
	#course-list .item{
		margin:20px 0;
	}
	#buddypress ul.item-list li div.item-title a{
		padding-top: 20px;
		display: inline-block;
	}
	#buddypress ul#groups-list li div.item-title a{
		padding-top:0;
		text-align:right;
	}
	#buddypress span.activity{
		margin:0;
	}
}
@media only screen and (max-width: 320px){
	#course-list .item {
	margin-left: 0;
	display: inline-block;
	width:100%;
	margin-top:20px;
	width: auto;
	}
}

/*--------------------------------------------------------------
4.0 - Media Queries
--------------------------------------------------------------*/
/*--------------------------------------------------------------
4.1 - Smartphones - landscape
--------------------------------------------------------------*/
@media screen and (max-device-width: 480px), screen and (-webkit-min-device-pixel-ratio: 2) {
	-webkit-text-size-adjust: none;
}
@media only screen and (max-width: 480px ){
	#buddypress div.dir-search {
		float: right;
		margin-top: -50px;
		text-align: right;
	}
	#buddypress div.dir-search input[type="text"] {
		margin-bottom: 1em;
	}
	a.bp-title-button {
		margin-left: 10px;
	}
	#buddypress form.standard-form .main-column div.action{
		position: relative;
		margin-bottom:1em;
	}
	#buddypress form.standard-form .main-column ul#friend-list h4{
		width:100%;
	}

	#buddypress div.item-list-tabs#subnav ul li.last{
		clear:both;
		float:left;
	}
	#buddypress div.item-list-tabs#subnav{width:90%;}
	#buddypress div.item-list-tabs ul li.last select{
		width:200px;
	}
	.total_students{font-size:14px;}
	#buddypress ul.item-list li div.item-title{margin-top:30px;}
	#buddypress ul.#members-list li div.item-title{margin-top:0px;}
	
	#buddypress ul.item-list li div.item-desc{clear:both;}
	#buddypress #message-threads img.avatar{
		display: none;
	}
	#buddypress div.item-list-tabs#subnav ul li{
		width:100%;
		border-left:1px solid #EFEFEF;
	}
}

/*--------------------------------------------------------------
4.2 - Smartphones - portrait
--------------------------------------------------------------*/
@media only screen and (max-width: 320px) {
	#buddypress div.dir-search {
		clear: left;
		float: left;
		margin-top: 0;
		text-align: left;
	}
	#buddypress li#groups-order-select {
		clear: left;
		float: left;
		background:#FFF;
	}
	#buddypress span.activity{margin-left:0;}
	#buddypress ul.item-list li div.action {
		clear: left;
		float: left;
		margin-top: 0;
		margin-left: 70px;
		position: relative;
		top: 0;
		right: 0;
		text-align: left;
	}
	#buddypress ul.item-list li div.item-desc {
		clear: left;
		float: left;
		margin: 10px 0 0;
		width: auto;
	}
	#buddypress li div.item {
		margin-left: 105px;
		width: auto;
	}
	#buddypress #course-dir-list li div.item{
		margin-top:20px;
		margin-left:0;
	}
	#buddypress ul.item-list li div.meta {
		margin-top: 0;
	}
	#buddypress .item-desc p {
		margin: 0 0 10px;
	}
	#buddypress div.pagination .pag-count {
		margin-left: 0;
	}
}


/*--------------------------------------------------------------
4.2 - Smartphones - smaller screen sizes
--------------------------------------------------------------*/
@media only screen and (max-width: 240px) {
	#buddypress div.dir-search {
		float: left;
		margin: 0;
	}
	#buddypress div.dir-search input[type="text"] {
		width: 50%;
	}
	#buddypress li#groups-order-select {
		float: left;
	}
	#buddypress ul.item-list li img.avatar {
		width: 30px;
		height: auto;
	}
	#buddypress ul.item-list li div.action,
	#buddypress li div.item{
		margin-left: 45px;
	}
	h1 a.bp-title-button {
		clear: left;
		float: left;
		margin: 10px 0 20px;
	}
}
</style>

<section id="title">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php the_title(); ?></h1>
                    <h5><?php the_sub_title(); ?></h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php
                    $breadcrumbs=get_post_meta(get_the_ID(),'vibe_breadcrumbs',true);
                    if(isset($breadcrumbs) && $breadcrumbs !='' && $breadcrumbs !='H')
                        vibe_breadcrumbs(); 
                ?>
            </div>
        </div>
    </div>
</section>
<?php
}

    $v_add_content = get_post_meta( $post->ID, '_add_content', true );
 
?>

<section id="content"> 
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="<?php echo $v_add_content;?> content">
                    <?php
                        the_content();
                     ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- second part home -->
<section id="content" style="background: none repeat scroll 0% 0% rgb(255, 255, 255);">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                       <div class="content" style="padding: 0px ! important; margin-top: 48px ! important;">
					   <!--Inicio Networking -->
																	<div class="block_home1" style="margin-top: 50px;">
																	 <h4 class="bloque_title"><a class="" href="http://gpsaknowledge.org/networking/">Networking Board</a> </h4>  
																			
																			

																		<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) . '&action=activity_update' .'&max=4') ) : ?>
																			<?php while ( bp_activities() ) : bp_the_activity(); ?>
																				<?php locate_template( array( 'activity/entry2.php' ), true, false ); ?>
																			<?php endwhile; ?>
																		<?php endif; ?>
																				
																		
																		
																		
                                                                    </div>       
						
						
														<!-- Fin de Networking -->
					   
                       <!--webinars and blog --><div class="one_half clearfix">
                                                        <div class="column_content first">
                                                                    <!-- webinars -->
                                                                    <div class="block_home">
                                                                    <?php $service_query = new WP_Query('page_id=171');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="/event-type/webinars/"><?php the_title(); ?></a> </h4>  
                                                                               <a href="/event-type/webinars/"><img class="th_home"  <?php echo get_the_post_thumbnail(); ?></a>                                                                                  
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  
                                                                                <a class="more" href="/event-type/webinars/"><span>Read more</span></a>                                                                                      

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>
                                                                    <!-- blog -->
                                                                    <div class="block_home" style="margin-top: 50px;">
                                                                    <?php $service_query = new WP_Query('page_id=178');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="http://gpsaknowledge.org/category/blog/"><?php the_title(); ?></a> </h4>  
                                                                               <a href="http://gpsaknowledge.org/category/blog/"><img class="th_home"  <?php echo get_the_post_thumbnail(); ?></a>                                                                                 
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  

                                                                                <a class="more" href="http://gpsaknowledge.org/category/blog/"><span>Read more</span></a>

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>                                                         
                                                        </div>
                                                        <?php
                                                        endwhile;
                                                        endif;
                                                        ?>                                       
                                                     </div>        
														
																
              <!--forums and toster and blog --><div class="one_half ">
			  
															  
			  
                                                                  <!-- forums -->
                                                                    <div class="block_home">
                                                                    <?php $service_query = new WP_Query('page_id=182');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="http://gpsaknowledge.org/forums/"><?php the_title(); ?></a> </h4>  
                                                                               <a href="http://gpsaknowledge.org/forums/"><img class="th_home"  <?php echo get_the_post_thumbnail(); ?></a>                                                                                  
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  
                                                                                <a class="more" href="http://gpsaknowledge.org/forums/"><span>Read more</span></a>

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>
                                                                     <!-- roster -->
                                                                    <div class="block_home" style="margin-top: 50px;">
                                                                    <?php $service_query = new WP_Query('page_id=180');
                                                                    while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                                                                       <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                                                   
                                                                               <div class="animate zoom load">
                                                                               <h4 class="bloque_title"><a class="" href="http://gpsaknowledge.org/networking/"><?php the_title(); ?></a> </h4>  
                                                                               <a href="http://gpsaknowledge.org/networking/"><img class="th_home"  <?php echo get_the_post_thumbnail(); ?></a>                                                                                
                                                                               </div> 	<!-- end .post-thumbnail -->					
                                                                               <div class="block_info">						
                                                                                       <?php the_content(); ?>
                                                                               </div> 	<!-- end .post_content -->                                                                                  
                                                                                <a class="more" href="http://gpsaknowledge.org/networking/"><span>Read more</span></a>

                                                                       </article> <!-- end .entry -->
                                                                       <?php endwhile; // end of the loop. ?>
                                                                    </div>                
                                                </div>
                        </div><!--fin 4 entradas: blog, webinar, forum, roster-->
                      
						
					  <!--Inicio carrusel -->
                        
                                <?php $service_query = new WP_Query('page_id=813');
                                while ( $service_query->have_posts() ) : $service_query->the_post(); ?>
                               <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                                     <h2 class="logos_title "><?php the_title(); ?></h4>                                                                  				
                                     <div><?php the_content(); ?></div><!-- end .post_content -->                                                                                                                                                         
                                </article> <!-- end .entry -->
                                <?php endwhile; // end of the loop. ?>
                       
                        <!-- fin carrusel logos ongs -->  
            </div><!--fin colummna derecha -->
            <div class="col-md-3 col-sm-4">
			<div class="sidebar">
				<?php 
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar('homesidebar') ) : ?>
                <?php endif; ?>
			</div>
            </div>
        </div>
    </div>
</section>
<?php
?>
</div>

<?php
get_footer();
?>
