
<?php
	require('../../../../../wp-load.php');
	
	$shortcode="[logooos ";
	
	if($_POST['columns']!=null) {
		$shortcode.='columns="'.$_POST['columns'].'" ';
	}
	
	if($_POST['backgroundcolor']!=null) {
		$shortcode.='backgroundcolor="'.$_POST['backgroundcolor'].'" ';
	}
	
	if($_POST['layout']!=null) {
		$shortcode.='layout="'.$_POST['layout'].'" ';
	}
	
	if($_POST['num']!=null) {
		$shortcode.='num="'.$_POST['num'].'" ';
	}
	
	if($_POST['category']!=null) {
		$shortcode.='category="'.$_POST['category'].'" ';
	}
	
	if($_POST['orderby']!=null) {
		$shortcode.='orderby="'.$_POST['orderby'].'" ';
	}
	
	if($_POST['order']!=null) {
		$shortcode.='order="'.$_POST['order'].'" ';
	}
	
	if($_POST['marginbetweenitems']!=null) {
		$shortcode.='marginbetweenitems="'.$_POST['marginbetweenitems'].'" ';
	}
	
	if($_POST['tooltip']!=null) {
		$shortcode.='tooltip="'.$_POST['tooltip'].'" ';
	}
	
	if($_POST['responsive']!=null) {
		$shortcode.='responsive="'.$_POST['responsive'].'" ';
	}
	
	if($_POST['grayscale']!=null) {
		$shortcode.='grayscale="'.$_POST['grayscale'].'" ';
	}
	
	if($_POST['border']!=null) {
		$shortcode.='border="'.$_POST['border'].'" ';
	}
	
	if($_POST['bordercolor']!=null) {
		$shortcode.='bordercolor="'.$_POST['bordercolor'].'" ';
	}
	
	if($_POST['borderradius']!=null) {
		$shortcode.='borderradius="'.$_POST['borderradius'].'" ';
	}
	
	
	
	if($_POST['autoplay']!=null) {
		$shortcode.='autoplay="'.$_POST['autoplay'].'" ';
	}
	
	if($_POST['scrollduration']!=null) {
		$shortcode.='scrollduration="'.$_POST['scrollduration'].'" ';
	}
	
	if($_POST['pauseduration']!=null) {
		$shortcode.='pauseduration="'.$_POST['pauseduration'].'" ';
	}
	
	if($_POST['buttonsbordercolor']!=null) {
		$shortcode.='buttonsbordercolor="'.$_POST['buttonsbordercolor'].'" ';
	}
	
	if($_POST['buttonsbgcolor']!=null) {
		$shortcode.='buttonsbgcolor="'.$_POST['buttonsbgcolor'].'" ';
	}
	
	if($_POST['buttonsarrowscolor']!=null) {
		$shortcode.='buttonsarrowscolor="'.$_POST['buttonsarrowscolor'].'" ';
	}
	
	if($_POST['hovereffect']!=null) {
		$shortcode.='hovereffect="'.$_POST['hovereffect'].'" ';
	}
	
	if($_POST['hovereffectcolor']!=null) {
		$shortcode.='hovereffectcolor="'.$_POST['hovereffectcolor'].'" ';
	}
	
	
	
	
	if($_POST['titlefontfamily']!=null) {
		$shortcode.='titlefontfamily="'.$_POST['titlefontfamily'].'" ';
	}
	
	if($_POST['titlefontcolor']!=null) {
		$shortcode.='titlefontcolor="'.$_POST['titlefontcolor'].'" ';
	}
	
	if($_POST['titlefontsize']!=null) {
		$shortcode.='titlefontsize="'.$_POST['titlefontsize'].'" ';
	}
	
	if($_POST['titlefontweight']!=null) {
		$shortcode.='titlefontweight="'.$_POST['titlefontweight'].'" ';
	}
	
	if($_POST['textfontfamily']!=null) {
		$shortcode.='textfontfamily="'.$_POST['textfontfamily'].'" ';
	}
	
	if($_POST['textfontcolor']!=null) {
		$shortcode.='textfontcolor="'.$_POST['textfontcolor'].'" ';
	}
	
	if($_POST['textfontsize']!=null) {
		$shortcode.='textfontsize="'.$_POST['textfontsize'].'" ';
	}
	
	if($_POST['listborder']!=null) {
		$shortcode.='listborder="'.$_POST['listborder'].'" ';
	}
	
	if($_POST['listbordercolor']!=null) {
		$shortcode.='listbordercolor="'.$_POST['listbordercolor'].'" ';
	}
	
	if($_POST['listborderstyle']!=null) {
		$shortcode.='listborderstyle="'.$_POST['listborderstyle'].'" ';
	}
	
	if($_POST['morelinktext']!=null) {
		$shortcode.='morelinktext="'.$_POST['morelinktext'].'" ';
	}
	
	if($_POST['morelinktextcolor']!=null) {
		$shortcode.='morelinktextcolor="'.$_POST['morelinktextcolor'].'" ';
	}
	
	
	
	$shortcode.="]";
	
	echo do_shortcode( $shortcode );
?>