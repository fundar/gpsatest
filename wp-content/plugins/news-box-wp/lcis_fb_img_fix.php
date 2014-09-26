<?php
/******************************************************
 *** RETURN THE FACEBOOK IMAGE TO AVOID /FEED BLOCK ***
 ******************************************************/

if(!isset($_GET['u']) || empty($_GET['u'])) {die();}
$url = urldecode($_GET['u']);


// check against facebook CDN image - but works in any case
//if(strpos($url, 'fbcdn.net') === false || strpos($url, 'akamaihd.net') === false || 1==1) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: ".$url);
	die();
//}
?>