<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	read from db	----------
	$mysql_err_msg = 'Image information unavailable';	
	$sql_statement = 'SELECT * FROM mod_image_1 WHERE mod_id = "'.$mod_id.'" ';

	$image_1_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	

	if 
	(	//	check db entry and if file exists 
			$image_1_info['image_file'] != "" AND $image_1_info['image_file'] != NULL 
		AND file_exists('_images_user/'.$image_1_info['image_file'])
		
	)
	{	

		echo TAB_7.'<div class="Image1" id="Image1_'.$mod_id.'" >'."\n";
		
		//	Is there a link associated with this image
		if ($image_1_info['image_href'] != "" AND $image_1_info['image_href'] != NULL AND $image_1_info['image_click'] == "link")
		{			
			//	Open in New window
			if ( $image_1_info['image_href_target'] == 'new_win') 
			{ $target = 'rel="external"'; }
			
			elseif ( $image_1_info['image_href_target'] == 'colorbox') 
			{ $target = 'rel="ColorBoxLink"'; }	
			
			else { $target = ''; }
			
			echo TAB_8.'<a href="'.$image_1_info['image_href'].'" '.$target.' >'."\n";
			
			$a_end_tag = TAB_8.'</a>'."\n";
		}
		
		elseif ( $image_1_info['image_click'] == 'new_win' )
		{
			//	  Calculate W x H of window from size of image			
			list($img_width, $img_height) = getimagesize('_images_user/'.$image_1_info['image_file']);
			
			$new_win_width = $img_width * 1.1;
			$new_win_height = $img_height + 70;
			
			echo TAB_8.'<a href="javascript:openWindow(\'includes/image_show.php?img=/_images_user/'
					.$image_1_info['image_file'].'\','.$new_win_width.','.$new_win_height.');" >'."\n";
						
			$a_end_tag = TAB_8.'</a>'."\n";		
		}
		
		elseif ( $image_1_info['image_click'] == 'colorbox' )
		{
			echo TAB_8.'<a href="/_images_user/'.$image_1_info['image_file'].'" rel="ColorBoxImage">'."\n";
						
			$a_end_tag = TAB_8.'</a>'."\n";		
		}
		else
		{
			$a_end_tag = '';
		}
		
				echo TAB_9.'<img class="Image1" src="/_images_user/'.$image_1_info['image_file'].'" '."\n";
				echo TAB_9.'title="'.$image_1_info['image_title'].'" alt="'.$image_1_info['image_alt_text'].'" />'."\n";

		
			if ($image_1_info['image_caption'] != "" AND $image_1_info['image_caption'] != NULL )
			{			
				//	Print Capton Text	
				echo TAB_9.'<span class="Image1Caption" >'.HiliteText(nl2br(Space2nbsp($image_1_info['image_caption']))).'</span>'."\n";	
			}
			
			echo $a_end_tag;			
		
		echo TAB_7.'</div>'."\n";
		
	}
	
?>