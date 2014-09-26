<?php
//// image size check
// if image is too big, resize it

$img_url = $_GET['src'];
$max_w = $_GET['max_w'];
$max_h = $_GET['max_h'];


////////////////////////////
// if the folder is not writable - returns the original image
if(!is_writable('tt_cache/index.html')) {
	header( "HTTP/1.1 301 Moved Permanently" ); 
	header('location:'.$img_url);
	die();	
}
////////////////////////////


$q = 80;
$size = 10000;

// curl executer
function lcnb_use_curl($url) {
	global $size;
	$ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);   

    $data = curl_exec($ch);
	$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    curl_close($ch);
	
	return $data;
}


// current url 
function lcnb_curr_url() {
	$pageURL = 'http';
	
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://" . $_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
	
	$clean = explode('?', $pageURL);
	$clean_url = $clean[0];
	
	$clean = explode('/', $clean_url);
	array_pop($clean);
	$pageURL = implode('/', $clean) . '/';
	
	return $pageURL;
}


// get image sizes
$raw = lcnb_use_curl($img_url);
$im = imagecreatefromstring($raw);
$width = imagesx($im);
$height = imagesy($im);



// resize if is bigger - otherwise return a thumb with original sizes
if($width > $max_w && $height > $max_h) {
	$_GET['w'] = $max_w;
	$_GET['h'] = $max_h;
}
elseif($width > $max_w && $height <= $max_h) {
	$_GET['w'] = $max_w;
}
elseif($width <= $max_w && $height > $max_h) {
	$_GET['h'] = $max_h;
}
else {
	$_GET['w'] = $width;
	$_GET['h'] = $height;
}


$_GET['q'] = $q;
require_once('nb_thumbs.php');
die();

?>