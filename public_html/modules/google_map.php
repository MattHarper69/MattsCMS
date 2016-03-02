<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	//	Do Google Map Get Direction	-------------
	$mysql_err_msg = 'Google Map Address Information not available';	
	$sql_statement = 'SELECT * FROM mod_google_map WHERE mod_id = "'.$mod_id.'"	'										  
												  .'AND active = "on"';
											  
	$google_map_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
	
	if ( $google_map_info['active'] == "on" )
	{
		echo "\n";
		echo TAB_7.'<!--	START Google Map 	-->'."\n";
		echo "\n";
		
		echo TAB_7.'<div class="GoogleMap" id="GoogleMap_'.$mod_id.'" >'. "\n";
		
		if ( $google_map_info['link_to_window'] == "on" ) 	
		{	
			//	Google Map in new window
		
			//	calculate dimensions of new window
			if ($google_map_info['map_width'] != "" OR $google_map_info['map_width'] != 0 ) {$new_win_width = $google_map_info['map_width'] * 1.1; }
			else {$new_win_width = GOOGLE_MAP_DEFAULT_NEW_WIN_WIDTH; }
			if ($google_map_info['map_height'] != "" OR $google_map_info['map_height'] != 0 ) {$new_win_height = $google_map_info['map_height'] * 1.1 +40;}
			else {$new_win_height = GOOGLE_MAP_DEFAULT_NEW_WIN_HEIGHT; }
			
			//	add extra height if other items are present in new window
			if ($google_map_info['get_dir_in_new_win'] == 'on') { $new_win_height += 90;}
			if ($google_map_info['heading'] != '' ) { $new_win_height += 20;}
			if ($google_map_info['text_1'] != '' ) { $new_win_height += 40;}
			if ($google_map_info['text_2'] != '' ) { $new_win_height += 40;}
			
			//	Do LInk
			echo TAB_8.'<a class="GoogleMapLinkText" '
					.'href="javascript:openWindow(\'modules/google_map/google_map_new_window.php?p='.$page_id.'&amp;map='.$google_map_info['mod_id'].'\','
							.$new_win_width.','.$new_win_height.');" title="'.$google_map_info['new_link_text'].'" >'. "\n";
				echo TAB_9.$google_map_info['new_link_text']. "\n";
			echo TAB_8.'</a>'. "\n";
			
			if ($google_map_info['new_link_thumb'] != "" OR $google_map_info['new_link_thumb'] != NULL )
			{ 
				echo TAB_8.'<a class="GoogleMapLinkIcon" '
					.'href="javascript:openWindow(\'modules/google_map/google_map_new_window.php?p='.$page_id.'&amp;map='.$google_map_info['mod_id'].'\','
							.$new_win_width.','.$new_win_height.');" title="'.$google_map_info['new_link_text'].'" >'. "\n";								
					echo TAB_9.'<img class="GoogleMapLinkThumb" id="GoogleMapLinkThumb_'.$mod_id.'"'
							  .' src="/_images_user/'.$google_map_info['new_link_thumb'].'" alt="link to google map lcon" />'. "\n";		
				echo TAB_8.'</a>'. "\n";	
			}			
			
		}
		
		else
		{
			
			//	Do heading and Text 1
			if ($google_map_info['heading'] != "" OR $google_map_info['heading'] != NULL ) 
			{ echo TAB_8.'<h3 class="GoogleMapHeading" >'.HiliteText($google_map_info['heading']).'</h3>'. "\n"; } 

			if ($google_map_info['text_1'] != "" OR $google_map_info['text_1'] != NULL ) 
			{ echo TAB_8.'<p class="GoogleMapText" >'.HiliteText($google_map_info['text_1']).'</p>'. "\n"; } 
			
			//	Do Google Map Div
			echo TAB_8.'<div class="GoogleMapCanvas" id="GoogleMapCanvas_'.$google_map_info['mod_id'].'" ></div>'. "\n";
		
			//	Do Text 2
			if ($google_map_info['text_2'] != "" OR $google_map_info['text_2'] != NULL ) 
			{ echo TAB_8.'<p class="GoogleMapText" >'.HiliteText($google_map_info['text_2']).'</p>'. "\n"; } 			
			
		}
		
					
		//	Do Get Directions to location	If enabled...				
		if 
		(		
				$google_map_info['get_directions'] == "on" 
			AND $google_map_info['directions_address'] != "" 
			AND $google_map_info['directions_address'] != NULL 
		)
		{ include ('google_map/google_map_directions.php');}		
			
				
		echo TAB_7.'</div>'. "\n";
		
		echo "\n";
		echo TAB_7.'<!--	END Google Map 	-->'."\n";
		echo "\n";

	}

		
?>