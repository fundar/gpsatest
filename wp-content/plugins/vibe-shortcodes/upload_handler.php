<?php
class WPLMS_ZIP_UPLOAD_HANDLER{

	function __construct(){
		add_action( 'wp_ajax_zip_upload', array($this,'wp_ajax_zip_upload' ));
		add_action( 'wp_ajax_del_dir', array($this,'wp_ajax_del_dir' ));
		add_action( 'wp_ajax_rename_dir', array($this, 'wp_ajax_rename_dir'));
		add_action('media_upload_upload',array($this,'media_upload_upload'));
		add_action( 'media_buttons', array($this,'wp_zip_upload_media_button'),100);
		add_action('wp_enqueue_scripts', array($this,'add_media_upload_scripts'));
	}


function wp_zip_upload_media_button() {
	$wp_myplugin_media_button_image = VIBE_PLUGIN_URL.'/vibe-shortcodes/images/media-upload-zip.gif';
	echo '<a href="media-upload.php?type=upload&TB_iframe=true&tab=upload" class="thickbox button">
  <div class="dashicons dashicons-upload"></div> '.__('Upload ZIP','vibe-shortcodes').'</a>';
}

function add_media_upload_scripts() {
    if ( is_admin() ) {
         return;
       }
    if(isset($_GET['edit']))   
    wp_enqueue_media();   
}

function media_upload_upload(){

	if(isset($_GET['tab']) && $_GET['tab']=='zip'){
	wp_iframe( "media_upload_zip_content" );
	}
	else{
		wp_iframe( "media_upload_zip_form" );
	}
}
function zip_tabs($tabs) {
	$newtab1 = array('zip' => __('Upload Library','vibe-shortcodes'));
	$newtab2 = array('upload' => __('Upload File','vibe-shortcodes'));
	return array_merge( $newtab2,$newtab1);
}

function print_tabs(){ 
	add_filter('media_upload_tabs', array($this,'zip_tabs'));
	media_upload_header();
}

function print_page_navi($num_records){
				//$num_records;	#holds total number of record
				$page_size;		#holds how many items per page
				$page;			#holds the curent page index
				$num_pages; 	#holds the total number of pages
				$page_size = 15;
				#get the page index
				if (empty($_GET[npage]) || !is_numeric($_GET[npage]))
				{$page = 1;}
				else
				{$page = $_GET[npage];}
				
				#caluculate number of pages to display
				if(($num_records%$page_size))
				{
					$num_pages = (floor($num_records/$page_size) + 1);
				}else{
					$num_pages = (floor($num_records/$page_size));
				}
		
				if ($num_pages != 1)
				{
					for ($i = 1; $i <= $num_pages; ++$i)
					{
						if ($i == $page){
							echo "$i";	
						}else{
							echo "<a href=\"media-upload.php?type=upload&tab=zip&npage=$i\">$i</a>";
			
						}
						if($i != $num_pages)
						{
							echo " | ";
						}
					}
				}
		
				#calculate boundaries for limit query
				$upper_bound = (($page_size * ($page-1)) + $page_size);/*$page_size;*/
				$lower_bound = ($page_size * ($page-1));
				$bound=array($lower_bound,$upper_bound,);
				return $bound;
}



function print_detail_form($num, $tab="upload", $file_url="", $dirname=""){
	?>
	<div id="upload_detail_<?php echo $num ?>" style="display:none; margin-bottom:30px; margin-top:20px;">
		<input type="hidden" size="40" name="file_url_<?php echo $num ?>" id="file_url_<?php echo $num ?>" value="<?php echo $file_url ?>" />
		<input type="hidden" size="40" name="dir_name_<?php echo $num ?>" id="dir_name_<?php echo $num ?>" value="<?php echo $dirname ?>" />
		<?php if($tab=='upload'){ ?>
		<input type="hidden" id="file_name_<?php echo $num ?>" value="" size="20" />
		<br /><label for="title"><strong>Title:</strong></label> <input type="text" size="20" name="title" id="title" value="" />
		<?php }?>		
		<div>
		<hr />
		<input type="button" class="button" name="insert_<?php echo $num ?>" id="insert_<?php echo $num ?>" value="<?php _e('Insert Into Post','vibe-shortcodes'); ?>" onclick="add_to_post(<?php echo $num ?>)" /> 
		<span id="insert_msg_<?php echo $num ?>"></span>
			
		</div>
	</div>
<?php
}




function printInsertForm(){

	$dirs = $this->getDirs();
	if (count($dirs)>0)
	{
	$this->print_js("zip");
	?>
	<title><?php _e('Upload Library','vibe-shortcodes'); ?></title>
	<?php
	 $uploadDirUrl=$this->getUploadsUrl();
	 //START PAGIGNATION
	 ?>
	 <div class="upload_dirs_navigation"> 
	 <?php  $bound= $this->print_page_navi(count($dirs)); // print the pagignation and return upper and lower bound ?>
	 </div>
	 <?php
	 
	  $lower_bound=$bound[0];
	  $upper_bound=$bound[1]; 
	  echo '<div style="text-align:right; margin:5px 20px;padding-right:10px;">Showing Content '.$lower_bound.' - '.$upper_bound.' of '.count($dirs);echo '</div>';
	  //$dirs = array_slice($dirs, $lower_bound, $upper_bound);
	  $dirs = array_slice($dirs, $lower_bound, 15);
	  //END PAGIGNATION
	 	
		echo "<table class='widefat'>";
			foreach ($dirs as $i=>$dir):
				extract($dir);
				$dir1 = str_replace("_"," " ,$dir);


				echo '<tr id="content_item_'.$i.'" class="'; if ($i % 2 == 1)echo 'alternate '; echo 'iedit">
						<td>
						<div>';
						echo $dir1;
						echo '<span style="float:right">';
						echo '<span id="show_button_'.$i.'" flag="1" onclick="show_hide_detail( '.$i.' )" style="text-decoration:none; color:#000099;font-size:11px; cursor:pointer;"><i class="dashicons dashicons-plus"></i></span> | ';
						echo '<span id="delete_button_'.$i.'"  onclick="delete_dir( '.$i.' )" style="text-decoration:none; color:#990000;font-size:11px; cursor:pointer;"><i class="dashicons dashicons-no"></i></span>';
						echo '<span id="loading_box_'.$i.'"></span>';
						echo '</span>';
						echo '</div>';
						$this->print_detail_form($i, "zip" , $uploadDirUrl.$dir."/".$file, $dir);
						echo '
						</td>
					 </tr>';

			endforeach;
		echo "</table>";
	
	}
	else
	{
	echo "no directories available";
	}
	
}

function getUploadsPath(){
	$dir = wp_upload_dir();
	return ($dir['basedir'] . "/package_uploads/");
}
function getUploadsUrl(){
$dir = wp_upload_dir();
return $dir['baseurl'] . "/package_uploads/";
}

function getDirs(){
	$myDirectory = opendir($this->getUploadsPath());
	$dirArray = array();
	$i=0;
	// get each entry
	while($entryName = readdir($myDirectory)) {
		if ($entryName != "." && $entryName !=".." && is_dir($this->getUploadsPath().$entryName)):
		$dirArray[$i]['dir'] = $entryName;
		// store the filenames - need to iterate to get story.html or player.html
		$dirArray[$i]['file'] = $this->getFile($this->getUploadsPath().$entryName);
		$i++;
		endif;
	}
	// close directory
	closedir($myDirectory);
	return $dirArray;
}

function getFile($dir)
{
	$myDirectory = opendir($dir);
	$fileArray = array();
	// get each entry
	while($entryName = readdir($myDirectory)) {
		if ($entryName != "." && $entryName !="..")
		{
		$f = $this->getUploadsPath().$entryName;
		
		// need to get the filename without the extension
		$fname = pathinfo ($f, PATHINFO_FILENAME);
		// need the extension as well
		$ext = pathinfo ($f,PATHINFO_EXTENSION);
		
		// CHECKS THE FILE NAME FOR IFRAME
		if (($fname == "player" || $fname == "story" || $fname == "engage" || $fname == "index" || $fname =="quiz" || $fname =="index") && $ext == "html"): 
		closedir($myDirectory);
		return $entryName;
		endif;
		}
	}
	return false;
}

function print_js($tab="upload"){ 
	wp_enqueue_script("jquery");

	?>
	<script src="<?php echo VIBE_PLUGIN_URL."/vibe-shortcodes/js/jquery.form.js";?>" ></script>
	<script>
	jQuery(document).ready(function() { 
 
				jQuery("#media_loading").hide();
				
	            jQuery('#zip_upload_form').ajaxForm(
				{
				success : function(data) { 
					data = eval('(' + data + ')');
	              if (data[0] == "uploaded")
				  {
					dir = data[1];
					var uploaded_dir_neme=data[2];
					var win = window.dialogArguments || opener || parent || top; 
					jQuery("#file_url_1").val(dir);
					jQuery("#dir_name_1").val(uploaded_dir_neme);
					jQuery("#file_name_1").val(data[3]);
					<?php if($tab=="upload"){?>
					var regex = new RegExp('_', 'g');
					jQuery("#title").val(uploaded_dir_neme.replace(regex," "));
					<?php }?>
					win.send_to_editor("[iframe height='800']"+dir+"[/iframe]");
					jQuery("#media_loading").hide();
					console.log('1');
					tb_remove();
				  }else{
				  	jQuery("#media_loading").hide();
				  	alert(data);
				  }
				  
	            },
				beforeSubmit: function()
				{
					jQuery("#media_loading").show();
				}
				
				});  
	});
 
	function show_detail(number){
		jQuery("#upload_detail_"+number+"").show('slow');
	}
 
	function show_hide_detail(number){
	var flag=jQuery("#show_button_"+number+"").attr("flag");
		if(flag=="1")
		{
		jQuery("#show_button_"+number+"").attr("flag", "2");
		jQuery("#show_button_"+number+"").html('<i class="dashicons dashicons-minus"></i>');
		jQuery("#upload_detail_"+number+"").show('slow');
		}
		else
		{
		jQuery("#show_button_"+number+"").attr("flag", "1");
		jQuery("#show_button_"+number+"").html('<i class="dashicons dashicons-plus"></i>');
		jQuery("#upload_detail_"+number+"").hide('slow');
		
		}
	}
 
 
	function show_box(box, number){
	jQuery("#"+box+"_"+number+"").show('slow');
	}
 
	function hide_box(box, number){
	jQuery("#"+box+"_"+number+"").hide();
	}
 
	function insert_as_clicked(number){
	var insert_as= parseInt(jQuery('input[name=insert_as_'+number+']:checked').val());
 
	switch(insert_as)
		{
		 case 1:
		  {
		  hide_box("lightbox_option_box", number);
		  hide_box("new_window_option_box", number);
		  hide_box("same_window_option_box", number);
		  break;
		  }
		 case 2:
		  {
		  show_box("lightbox_option_box", number);
		  hide_box("new_window_option_box", number);
		  hide_box("same_window_option_box", number);
		  break;
		  }
		 case 3:
		  {
		  hide_box("lightbox_option_box", number);
		  show_box("new_window_option_box", number);
		  hide_box("same_window_option_box", number);
		  break;
		  }
		 case 4:
		  {
		  hide_box("lightbox_option_box", number);
		  hide_box("new_window_option_box", number);
		  show_box("same_window_option_box", number);
		  break;
		  }	  
		}// end switch
	}
 
	function lightbox_option_clicked(number){
	var lightbox_option= parseInt(jQuery('input[name=lightbox_option_'+number+']:checked').val());
		switch(lightbox_option)
		{
		  case 1:
		  {
		  show_box("lightbox_title", number);
		  break;
		  }
		  case 2:
		  {
		  hide_box("lightbox_title", number);
		  break;
		  }
		}
	}
 
	function more_lightbox_option_clicked(number){
	var more_lightbox_option= parseInt(jQuery('input[name=more_lightbox_option_'+number+']:checked').val());
		switch(more_lightbox_option)
		{
		  case 1:
		  {
		  show_box("lightbox_link_text", number);
		  break;
		  }
		  case 2:
		  {
		  hide_box("lightbox_link_text", number);
		  break;
		  }
		}
	}
 
	function open_new_window_option_clicked(number){
	var open_new_window_option= parseInt(jQuery('input[name=open_new_window_option_'+number+']:checked').val());
		switch(open_new_window_option)
		{
		  case 1:
		  {
		  show_box("open_new_window_link_text", number);
		  break;
		  }
		  case 2:
		  {
		  hide_box("open_new_window_link_text", number);
		  break;
		  }
		}
 
	}
 
	function open_same_window_option_clicked(number){
	var open_same_window_option= parseInt(jQuery('input[name=open_same_window_option_'+number+']:checked').val());
		switch(open_same_window_option)
		{
		  case 1:
		  {
		  show_box("open_same_window_link_text", number);
		  break;
		  }
		  case 2:
		  {
		  hide_box("open_same_window_link_text", number);
		  break;
		  }
		}
 
	}
 
	function add_to_post(number){
		<?php if($tab=="upload"){?>
		  //rename action will fired.
		  var old_name=jQuery("#dir_name_1").val();
		  var regex = new RegExp('_', 'g');
		  var temp=old_name.replace(regex," ");
		  var new_name=jQuery.trim(jQuery("#title").val());
		  if(new_name!="" && new_name!=temp)
		  {
		  rename_dir(old_name, new_name);
		  }
		  else
		  {
		  insert_into_post(number);
		  }
		<?php }else{?>insert_into_post(number); <?php }?>
	}
 
 
	function insert_into_post(number){
		var uploaded_file_url=jQuery("#file_url_"+number+"").val();
		if(uploaded_file_url==""){alert("Please Upload A Zip File"); return;}
		var win = window.dialogArguments || opener || parent || top; 
		win.send_to_editor("[iframe height='800']"+uploaded_file_url+"[/iframe]");
		var p = parent;
		p.jQuery("#TB_window").remove();
		p.jQuery('#TB_overlay').remove();
	}
 
	function rename_dir(old_name, new_name){
 
		var loading_text='<img src="<?php echo VIBE_PLUGIN_URL;?>/vibe-shortcodes/images/loading.gif"  /> Saving....';
		jQuery('#insert_msg_1').html(loading_text);	
		jQuery.getJSON("<?PHP echo vibe_site_url(); ?>/wp-admin/admin-ajax.php?action=rename_dir&dir_name="+old_name+"&title="+new_name, function(data) {
			
				//alert(data[0]); 
				if(data[0]=="success")
				{
				 var new_renamed_dir_name=data[1];
				 var old_file_name = jQuery('#file_name_1').val();
				 jQuery('#file_url_1').val("<?php echo $this->getUploadsUrl();?>"+new_renamed_dir_name+"/"+old_file_name);	
				 jQuery('#insert_msg_1').html("");
				 insert_into_post(1);
				}
				else
				{
				jQuery('#insert_msg_1').html("");
				alert(data[1])
				}
			});
	}
 
	function delete_dir(number){
 
		var dir_name=jQuery("#dir_name_"+number+"").val();
		var loading_image='&nbsp;&nbsp;<img src="<?php echo VIBE_PLUGIN_URL;?>/vibe-shortcodes/images/loading.gif" alt="delete" />&nbsp;Deleting..'
		var loading_text='<img src="<?php echo VIBE_PLUGIN_URL;?>/vibe-shortcodes/images/loading.gif" alt="Loading" /> Deleting....';
		
		
		if(dir_name!="")
		{			
			if (confirm("Are you sure?")){
 
			jQuery("#delete_button_"+number+"").hide();
			jQuery("#loading_box_"+number+"").html(loading_image);
			jQuery("#insert_msg_"+number+"").html(loading_text);
			
			jQuery.post("admin-ajax.php",{dir : dir_name,action:'del_dir'},function(data){
				//alert("Deleted");
				<?php if($tab=="upload"){?>
				jQuery("#insert_msg_"+number+"").html("");
				jQuery("#upload_detail_"+number+"").remove();
				location.reload();
				<?php }else{?>
				jQuery("#content_item_"+number+"").remove();
				<?php }?>
					
				
				});
			}
		}else{alert("No Data Found To Delete");}
						
	}
			
	</script>
	<?php
	return;
	}

	function print_upload(){
		$this->print_js();
		?>
		<form enctype="multipart/form-data" id="zip_upload_form" action="admin-ajax.php" method="POST">
		<input type="hidden" name="action" value="zip_upload" />
		<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
		<table style="margin-left:-15px;">
		<tr><td>
		<strong>Choose a file to upload:</strong></td><td> <input name="uploadedfile"  id="uploadedfile" type="file" /></td></tr>
		<tr><td>&nbsp;</td><td><input type="submit" value="Upload File" class="button" /></td></tr>
		</table>
		</form>
		<p><i><?php _e('Please choose a .zip package file','vibe-shortcodes'); ?></i></p>
		<img id="media_loading" style='display:none;' src= "<?php echo VIBE_PLUGIN_URL . '/vibe-shortcodes/images/loading.gif' ;?>" /><br />
		<?php $this->print_detail_form(1);?>
		<p/>
		<?php
	}



	function wp_ajax_del_dir(){
		$dir = $this->getUploadsPath().$_POST['dir'];
		$this->rrmdir($dir);
		die();
	}


	function wp_ajax_rename_dir(){
	$arr=array();
		if(isset($_REQUEST['dir_name']) && $_REQUEST['dir_name']!="")
		{
		$target = $this->getUploadsPath().$_REQUEST['dir_name'];
			if(file_exists($target))
			{
				
				if(isset($_REQUEST['title']) && $_REQUEST['title']!="")
				{
				$title=trim($_REQUEST['title']);
					if($title)
					{   
						$title=str_replace(" ","_" , $title);
						$new_file= $this->getUploadsPath().$title;
						while(file_exists($new_file))
						{
						$r = rand(1,10);
						$new_file .= $r;
						$title .= $r;
						}
						rename($target, $new_file);
						$arr[0]="success";
						$arr[1]=$title;
					}
					else
					{
					$arr[0]="error";
					$arr[1]="Failed: New Title Was Not Given";
					}
				}
				else
				{
				$arr[0]="error";
				$arr[1]="Failed: New Title Was Not Given";
				}
			}
			else
			{
			$arr[0]="error";
			$arr[1]="Failed: Given File is Not Exits";
			}
		}
		else
		{
		$arr[0]="error";
		$arr[1]="Failed: Targeted Directory Name Was Not Given";
		}
	echo json_encode($arr);	
		
	die();
	}

	function wp_ajax_zip_upload(){
		$arr = array();
		
		$file = $_FILES['uploadedfile']['tmp_name'];
		$dir = explode(".",$_FILES['uploadedfile']['name']);
		$dir[0] = str_replace(" ","_",$dir[0]);
		$target = $this->getUploadsPath().$dir[0];
		$index = count($dir) -1;

		if (!isset($dir[$index]) || $dir[$index] != "zip")
			$arr[0] = __('The Upload file must be zip archive','vibe-shortcodes');
		else{
			while(file_exists($target)){
				$r = rand(1,10);
				$target .= $r;
				$dir[0] .= $r;
			}
			if (!empty($file))
				$arr = $this->extractZip($file,$target,$dir[0]);
			else
				$arr[0] ="file is too big";
		}
			echo json_encode($arr);
		die();
	}

	function extractZip($fileName,$target,$dir){
			 $arr = array();
	 	 $zip = new ZipArchive;
	     $res = $zip->open($fileName);
	     if ($res === TRUE) {
	         $zip->extractTo($target);
	         $zip->close();
			 $file = $this->getFile($target);
			if($file){
				 $arr[0] = 'uploaded'; 
				 $arr[1] = $this->getUploadsUrl().$dir."/".$file; 
				 $arr[2] = $dir;
				 $arr[3] =$file;
			 }else{
				 $arr[0] = __('Please upload zip file','vibe-shortcodes');
				 rrmdir($target);
			 }
	     }else{
			$arr[0] ="file upload failed";
	     }
		 return  $arr;
	}

	function rrmdir($dir) {
		if (is_dir($dir)) {
		 $objects = scandir($dir);
		 foreach ($objects as $object) {
		   if ($object != "." && $object != "..") {
		     if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
		   }
		 }
		 reset($objects);
		 rmdir($dir);
		}
	} 

}


function media_upload_zip_form(){
	$wplmsthis = new WPLMS_ZIP_UPLOAD_HANDLER;
	$wplmsthis->print_tabs();
	echo '<div class="upload_directory">';
	echo '<h2>'.__('Upload File','vibe-shortcodes').'</h2>';
	$wplmsthis->print_upload();
	echo "</div>";
}

function media_upload_zip_content(){
	$wplmsthis = new WPLMS_ZIP_UPLOAD_HANDLER;
	$wplmsthis->print_tabs();
	echo '<div class="upload_directory">';
	echo '<h2>'.__('Upload Library','vibe-shortcodes').'</h2>';
	
	$wplmsthis->printInsertForm();
	echo '</div>';
}

?>