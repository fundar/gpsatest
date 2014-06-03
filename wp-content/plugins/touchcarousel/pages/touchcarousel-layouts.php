<?php	

/* It this file you can edit default layouts or add your own. */

?>	
	var layoutsArr = [ 	
	
	
		{
			id : "tc-layout-1",
			label : "<?php _e("Item layout 1 620x350", "touchcarousel") ?>",
						
			html :  <?php 
$out = <<<EOT
<a class="tc-image-holder" href="[tco]permalink[/tco]">[tco width="620" height="350"]thumbnail[/tco]</a>
<div class="tc-desc">
	<h4><a href="[tco]permalink[/tco]">[tco]Title[/tco]</a></h4>
	<span class="tc-meta">[tco]date[/tco], [tco]comments-popup-link[/tco]</span>
</div>
EOT;
echo json_encode($out);
				    ?>,
				    
			css : <?php
$out = <<<EOT
.touchcarousel.tc-layout-1 .touchcarousel-item {
	width: 620px;
	height: 350px;
	position: relative;
}
.touchcarousel.tc-layout-1 .touchcarousel-item p {
	margin: 0;
	padding: 0;
}
.touchcarousel.tc-layout-1 .touchcarousel-item a img {
	max-width: none;
}
.touchcarousel.tc-layout-1 .tc-desc {
	position: absolute;
	left: 0;
	bottom: 0;
	width: 100%;
	height: 65px;
	
	background: #333;
	background: rgba(0,0,0,0.65);
}
.touchcarousel.tc-layout-1 .tc-desc h4 {
	margin: 7px 15px 0 15px;
	padding: 0;
}
.touchcarousel.tc-layout-1 .tc-desc h4 a {
	color: #FFF;
	font-size: 20px;
	line-height: 1.4em;
	font-weight: normal;
	font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
	border: 0;
	text-decoration: none;
}
.touchcarousel.tc-layout-1 .tc-meta {
	margin-left: 15px;
	display: block;
}
.touchcarousel.tc-layout-1 .tc-meta,
.touchcarousel.tc-layout-1 .tc-meta a {
	font-family: Georgia, serif;
	font-size: 14px;
	line-height: 1.4em;
	font-weight: normal;
	font-style: italic;
	color: #BBB;
}
EOT;
echo json_encode($out); ?>
		},
		
		
		
		
		
		{	
			id : "tc-layout-2",
			label : "<?php _e("Item layout 2 620x270", "touchcarousel") ?>",
			
			html :  <?php 
$read_more = __('Read more','touchcarousel');
$out = <<<EOT
<div class="tc-desc">
	<h4><a href="[tco]permalink[/tco]">[tco]Title[/tco]</a></h4>
	<div class="tc-meta"><span>[tco]date[/tco], </span>[tco]comments-popup-link[/tco]</div>
	<p class="tc-excerpt">[tco length="30"]excerpt[/tco]</p>
</div>
<a class="tc-image-holder" href="[tco]permalink[/tco]">[tco width="400" height="270"]thumbnail[/tco]</a>
EOT;
echo json_encode($out); ?>,
				    
			css : <?php
$out = <<<EOT
.touchcarousel.tc-layout-2 .touchcarousel-item {
	width: 620px;
	height: 270px;
	position: relative;
	background: #2c2d2e;
	margin-right: 10px;
	overflow: hidden;
}
.touchcarousel.tc-layout-2 .tc-desc {
	width: 190px;
	padding: 10px 15px;
	color: #FFF;
	font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
	display: block;
	float: left;
}
.touchcarousel.tc-layout-2 .tc-image-holder {
	display: block;
	width: 400px;
	height: 270xp;
	float: left;
}
.touchcarousel.tc-layout-2 h4 a {
	font-size: 22px;
	line-height: 1.3em;
	font-weight: bold;
	color: #FFF;
	border: 0;
	text-decoration: none;
}
.touchcarousel.tc-layout-2 .tc-meta a,
.touchcarousel.tc-layout-2 .tc-meta span {
	font-size: 12px;
	font-weight: normal;
	color: #e3e5e3;
	font-family: Georgia, serif;
	font-style: italic;
}
.touchcarousel.tc-layout-2 span {
	color: #FFF;
}
.touchcarousel.tc-layout-2 p {
	color: #EEE;
	font-size: 14px;
	line-height: 1.4em;
	margin: 7px 0 2px 0;
	pading: 0;
}
.touchcarousel.tc-layout-2 a img {
	max-width: none;
	margin: 0;
	padding: 0;
}
EOT;
echo json_encode($out);  ?>
		},
		
		
			
			
		{
			id : "tc-layout-3",
			label : "<?php _e("Item layout 3 226x70", "touchcarousel") ?>",
			
			html :  <?php 
$out = <<<EOT
<a href="[tco]permalink[/tco]">
	[tco width="60" height="60"]thumbnail[/tco]
	<div class="tc-block">
		<h4>[tco]title[/tco]</h4>
		<span class="meta">[tco]date[/tco]</span>
	</div>
</a>
EOT;
echo json_encode($out);
				    ?>,
				    
			css : <?php
$out = <<<EOT
.touchcarousel.tc-layout-3 .touchcarousel-item {
	width: 226px;
	min-height: 70px;
	margin-right: 12px;
	position: relative;
}

.touchcarousel.tc-layout-3 .touchcarousel-item img, 
.touchcarousel.tc-layout-3 .touchcarousel-item h4,
.touchcarousel.tc-layout-3 .touchcarousel-item span {
	position: relative;
	padding: 0;	
	border: 0;			
}
.touchcarousel.tc-layout-3 .touchcarousel-item img {
	max-width: none;
	margin: 5px 0 0 5px;
	padding: 0;
	border: 0;
	float: left;
}
.touchcarousel.tc-layout-3 .touchcarousel-item  .tc-block {
	display: inline-block;
	margin: 5px 0 0 8px;
	width: 153px;
}
.touchcarousel.tc-layout-3 .touchcarousel-item a {
	color: #3e4245;
	display: block;
	min-height: 70px;
	width: 226px;
	background: #ebf3f3;
	border: 0;
	
	-webkit-transition: background-color 0.2s ease-out; 
    -moz-transition: background-color 0.2s ease-out;  
    -ms-transition: background-color 0.2s ease-out; 
    -o-transition: background-color 0.2s ease-out; 
    transition: background-color 0.2s ease-out;
}
.touchcarousel.tc-layout-3 .touchcarousel-item a:hover {
	background-color: #d4dfdf;
}

.touchcarousel.tc-layout-3 .touchcarousel-item h4 {
	color: #3c4342;
	font-weight: bold;
	font-size: 14px;
	line-height: 1.4em;
	margin: 0;
	padding: 0;
}


.touchcarousel.tc-layout-3 .touchcarousel-item span {
	font-size: 13px;
	color: #777;
	margin-top: 2px;
	line-height: 1.4em;
	display: block;
	font-family: Georgia, sans-serif;
	font-style: italic;
}

EOT;
echo json_encode($out); ?>
		},			
		
		
			
			
		{
			id : "tc-layout-4",
			label : "<?php _e("Item layout 4 170x170", "touchcarousel") ?>",
			
			html :  <?php 
$out = <<<EOT
<a class="tc-state" href="[tco]permalink[/tco]">
	[tco width="170" height="120"]thumbnail[/tco]
	<div class="tc-block">
		<h4>[tco]title[/tco]</h4>	
	</div>
</a>
EOT;
echo json_encode($out);
				    ?>,
				    
			css : <?php
$out = <<<EOT
.touchcarousel.tc-layout-4 .touchcarousel-item {
	width: 170px;
	min-height: 170px;
	margin-right: 5px;
	position: relative;
	overflow: hidden;
	background: #ebf3f3;

}
.touchcarousel.tc-layout-4 .touchcarousel-item p {
	margin: 0;
	padding: 0;
}
.touchcarousel.tc-layout-4 .touchcarousel-item a.tc-state {
	display: block;
	width: 170px;
	min-height: 170px;
	position: relative;
	text-decoration: none;
	color: #3e4245;
	
	-webkit-transition: color 0.2s ease-out; 
    -moz-transition: color 0.2s ease-out;  
    -ms-transition: color 0.2s ease-out; 
    -o-transition: color 0.2s ease-out; 
    transition: color 0.2s ease-out;
}
.touchcarousel.tc-layout-4 .touchcarousel-item img {
	max-width: none;
	border: 0;
	margin: 0;
}
.touchcarousel.tc-layout-4 .touchcarousel-item img, 
.touchcarousel.tc-layout-4 .touchcarousel-item h4,
.touchcarousel.tc-layout-4 .touchcarousel-item span {
	position: relative;
	margin: 0;
	padding: 0;	
	border: 0;			
}
.touchcarousel.tc-layout-4 .tc-block {
	margin: 0 4px 3px 8px
}

.touchcarousel.tc-layout-4 .touchcarousel-item h4 {
	font-size: 14px;
	line-height: 1.4em;
	padding: 0;
	text-decoration: none;
	font-family: 'Helvetica Neue', Arial, serif;
	
}
.touchcarousel.tc-layout-4 .touchcarousel-item a.tc-state:hover {
	color: #13937a;
}
.touchcarousel.tc-layout-4 .touchcarousel-item span {
	font-size: 12px;
	color: #666;
}

EOT;
echo json_encode($out); ?>
		},
	];