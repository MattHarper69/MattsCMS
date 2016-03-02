<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	define ( 'GOOGLE_MAP_SCRIPT_URL', '//maps.googleapis.com/maps/api/js' );
	
	$mod_head_code .= MakeGoogleMapObject( $page_id,$file_path_offset);
	//$body_onload_info = 'onload="initialize()" onunload="GUnload()" ';

function MakeGoogleMapObject( $page_id, $file_path_offset )
{		
	//	Get map data from db	-------------
	$mysql_err_msg = 'Google Map Information not available';	
	
	//	Is this in the Index.php OR in a new window ??
	if ($_SERVER['SCRIPT_NAME'] == '/index.php' ) 
	{
		//$sql_statement = 'SELECT * FROM mod_google_map WHERE page_id = "'.$page_id.'" and active = "on"';
		$sql_statement = 'SELECT * FROM mod_google_map, modules WHERE modules.page_id = "'.$page_id.'"'
															.' AND mod_google_map.mod_id = modules.mod_id'
															.' AND mod_google_map.active = "on"'
															;
	}
	
	else { $sql_statement = 'SELECT * FROM mod_google_map WHERE mod_id = "'.$_REQUEST['map'].'" and active = "on"'; }

	$result = ReadDB ($sql_statement, $mysql_err_msg);	
	$num_results = mysql_num_rows($result);

	//	set vars
	$mod_head_code = '';
	$map_style_str = '';
	if ($num_results > 0)
	{
		//	start of  script code
		if (defined('GOOGLE_MAP_API_KEY') AND GOOGLE_MAP_API_KEY != NULL)
		{
			$key_str = '&key=' . GOOGLE_MAP_API_KEY;
		}
		else {$key_str = '';}
		
		if (defined('GOOGLE_MAP_VERSION') AND GOOGLE_MAP_VERSION != NULL)
		{
			$key_str .= '&v=' . GOOGLE_MAP_VERSION;
		}
		
		$script_url = GOOGLE_MAP_SCRIPT_URL . '?sensor=' . GOOGLE_MAP_SENSOR . $key_str;
		
		$mod_head_code .= 	TAB_1.'<script src="'.$script_url.'" ' ."\n"
							 .TAB_1.'type="text/javascript"></script>' ."\n\n"
							 .TAB_1.'<script type="text/javascript"><!--//--><![CDATA[' ."\n\n"    
								 .TAB_2.'function initialize()' ."\n" 
								 .TAB_2.'{' ."\n";     

									
		while ( $google_map_info = mysql_fetch_array ($result))
		{
			//	Do NOT do google javascript stuff if map is to go in a new page instead......otherwise errors are shown
			if ($_SERVER['SCRIPT_NAME'] == '/index.php' AND $google_map_info['link_to_window'] == 'on') {}
			else
			{
				//	Create a Map					
				$mod_head_code .= TAB_3.'var map_'.$google_map_info['mod_id']
							   .' = new google.maps.Map(document.getElementById("GoogleMapCanvas_'.$google_map_info['mod_id'].'"),'."\n";        
				$mod_head_code .= TAB_3.'{'."\n";
								
				//	define centre of Map
				$mod_head_code .= TAB_4.' center: new google.maps.LatLng('.$google_map_info['centre_lat'].','.$google_map_info['centre_long'].')'."\n"
								 .TAB_4.',zoom: '.$google_map_info['zoom_level'] ."\n"
								 .TAB_4.',mapTypeId: google.maps.MapTypeId.ROADMAP' ."\n"
							
							.TAB_3.'});'."\n\n";
			
				//	Get map data from db	-------------
				$mysql_err_msg = 'Google Map Information not available';	
				$sql_statement = 'SELECT * FROM mod_google_map_markers WHERE mod_id = "'.$google_map_info['mod_id'].'" AND active = "on"';
				$marker_result = ReadDB ($sql_statement, $mysql_err_msg);	
				//$num_results = mysql_num_rows($marker_result);
			
				while ( $marker_info = mysql_fetch_array ($marker_result))
				{
					//	display Map Marker	
					if ( $marker_info['map_icon_file'] == "" OR !file_exists($file_path_offset.'_images_user/'.$marker_info['map_icon_file']))
					{
						//	Use Default Marker													  
						$icon_image_file = 'http://maps.google.com/mapfiles/marker.png';
						$icon_shadow_image_file = 'http://www.google.com/mapfiles/shadow50.png';
						$icon_size = '20,34';
						$icon_offset = '0,20';
						
					}
			
					else
					{
						//	Use Custom Marker
						$icon_image_file = '/_images_user/'.$marker_info['map_icon_file'];
						$icon_shadow_image_file = '/_images_user/'.$marker_info['map_icon_shadow_file'];
						$icon_size = $marker_info['map_icon_width'].','.$marker_info['map_icon_height'];
						$icon_offset = $marker_info['marker_offset_x'].','.$marker_info['marker_offset_y'];
					}
					
					$mod_head_code .=  TAB_3.'var Icon_'.$marker_info['marker_id'].' = new google.maps.MarkerImage'."\n"
								      .TAB_3.'('."\n"
								
										.TAB_4.'"'.$icon_image_file.'"'."\n"
										.TAB_4.',new google.maps.Size('.$icon_size.')'."\n"
										.TAB_4.',new google.maps.Point(0,0)'."\n"
										.TAB_4.',new google.maps.Point('.$icon_offset.')'."\n"
									
									.TAB_3.');'."\n"
									."\n"
									;
					 
					$mod_head_code  .= TAB_3.'var IconShadow_'.$marker_info['marker_id'].' = new google.maps.MarkerImage'."\n"
									  .TAB_3.'('."\n"
								
										.TAB_4.'"'.$icon_shadow_image_file.'"'."\n"
										.TAB_4.',new google.maps.Size('.$icon_size.')'."\n"
										.TAB_4.',new google.maps.Point(0,0)'."\n"
										.TAB_4.',new google.maps.Point('.$icon_offset.')'."\n"
									
									.TAB_3.');'."\n"
									."\n"
									;					 
							
							
					$mod_head_code  .= TAB_3.'var marker_'.$marker_info['marker_id'].' = new google.maps.Marker'."\n"
									  .TAB_3.'({'."\n"
										
										.TAB_4.'position: new google.maps.LatLng('.$marker_info['marker_lat']
										.', '.$marker_info['marker_long'].')' ."\n"
										.TAB_4.',icon: Icon_'.$marker_info['marker_id'] ."\n"
										.TAB_4.',shadow: IconShadow_'.$marker_info['marker_id'] ."\n"	
										.TAB_4.',map: map_'.$google_map_info['mod_id'] ."\n"
									
									.TAB_3.'});'."\n"
									."\n"
									;									
							
			

					
					//	Info Window Text						  
					if ( $marker_info['map_info_heading'] != "" OR $marker_info['map_info_text'] != "" OR $marker_info['map_info_address'] != "")
					{			
						
						
						$mod_head_code .= 	TAB_3.'InfoHtml_'.$marker_info['marker_id']
													.' = "<h4 class=\"GoogleMapInfoBox\">'
													.$marker_info['map_info_heading'].'</h4>"' ."\n"
										   .TAB_8.' + "<p class=\"GoogleMapInfoBoxAddress\">'.$marker_info['map_info_address'].'</p>"' ."\n"
										   .TAB_8.' + "<p class=\"GoogleMapInfoBox\">'.$marker_info['map_info_text'].'</p>";' ."\n";
		 
						//	Pop-up Info Window when clicked
						$mod_head_code .= 	TAB_3.'var InfoWindow_'.$marker_info['marker_id']
											.' = new google.maps.InfoWindow({content: InfoHtml_'.$marker_info['marker_id'].'})' ."\n"		
											.TAB_3.'google.maps.event.addListener (marker_'.$marker_info['marker_id'].', "click", function ()'
											.'{InfoWindow_'.$marker_info['marker_id'].'.open(map_'.$google_map_info['mod_id']
											.', marker_'.$marker_info['marker_id'].')})' ."\n"
											;
						

					}
				
				}
				
				//	add new line between maps code
				$mod_head_code .= "\n";
							
				//	user specified Width and height of Map div via Style rules
				$map_style_str .= TAB_2.'div#GoogleMapCanvas_'.$google_map_info['mod_id'] ."\n"
								 .TAB_2.'{' ."\n";
										
					if ($google_map_info['map_width'] != "" AND $google_map_info['map_width'] != 0 AND $google_map_info['map_width'] != NULL )		
					{ $map_style_str .=	 TAB_3.'width: '.$google_map_info['map_width'].'px;' ."\n"; }
					
					if ($google_map_info['map_height'] != "" AND $google_map_info['map_height'] != 0 AND $google_map_info['map_height'] != NULL )	
					{ $map_style_str .=	 TAB_3.'height: '.$google_map_info['map_height'].'px;' ."\n"; }
					
				$map_style_str .=	 TAB_2.'}' ."\n\n";	
				
			}
		}

		//	End of  script code							
		$mod_head_code .= 	TAB_2.'}' ."\n\n" 

						.TAB_2.'google.maps.event.addDomListener(window, "load", initialize);' ."\n\n" 
		
						.TAB_1.'//--><!]]></script>' ."\n\n" ;
						
		//	user specified Width and height of Map div via Style rules
		$mod_head_code .= TAB_1.'<!-- User specified Google Map Dimensions -->' ."\n\n"
						 .TAB_1.'<style type="text/css">' ."\n\n" . $map_style_str . TAB_1.'</style>' ."\n" ;	
		
			
		RETURN $mod_head_code;
		
	}				
}
					
 ?>