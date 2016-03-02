<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	read from db	----------
	$mysql_err_msg = 'Link Info unavailable';	
	$sql_statement = 'SELECT * FROM mod_links WHERE mod_id = "'.$mod_id.'" ';

	$link_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));		
 	
	
	echo TAB_7.'<div class="Link" id="Link_'.$mod_id.'" >'."\n";
	
		if ( $link_info['new_window'] == '' OR $link_info['new_window'] == NULL ) 
		{ $target = ''; }
		else { $target = 'rel="external"'; }
						
		echo TAB_8.'<a class="Link" href="'.$link_info['url'].'" '.$target
					.' title="'.$link_info['title'].'" > '. "\n";
		
			//	Image ???
			if ( $link_info['image_file'] != '' AND $link_info['image_file'] != NULL )
			{echo TAB_9.'<img class="LinkImage" src="/_images_user/'.$link_info['image_file'].'" alt="'.$link_info['alt_text'].'" /> '. "\n";}
			
			if ( $link_info['display_url'] != '' AND $link_info['display_url'] != NULL )
			{echo TAB_9.'<span>'.HiliteText($link_info['display_url']).'</span>'. "\n";}
		
		echo TAB_8.'</a>'. "\n";	
		
	echo TAB_7.'</div>'. "\n";
	
?>