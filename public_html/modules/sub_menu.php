<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	Do a Sub Menu if there are child links

		//	read from db	----------
		$mysql_err_msg = 'Cannot Access Footer Navigation Information';
		$sql_statement = 'SELECT * FROM page_info WHERE parent_id ="'.$page_id.'" '.
				
												'AND active="on" '.
												'ORDER BY seq';
												
		$result = ReadDB ($sql_statement, $mysql_err_msg);
		
		//------check if there are sub links:
		if (mysql_num_rows($result) > 0 )
		{		
			echo "\n";			
			echo TAB_7.'<!--	Start Sub Menu List		-->'."\n";		
			echo "\n";	
			
			echo TAB_7.'<div class="SubMenuMod" id="SubMenuMod_'.$mod_id.'" >'."\n";
			
				echo TAB_8.'<ul>'."\n";

				while ($nav_row=mysql_fetch_array ($result))
				{	
					echo TAB_9.'<li class="SubMenuMod" id="SubMenuModPage_'.$nav_row['page_id'].'">'. "\n";

						if ($nav_row['send_p_query'] == "on" ) {$query_str = '?p='.$nav_row['page_id'];}
						else {$query_str = '';}					
					
						echo TAB_10.'<a class="SubMenuMod" href="'.$nav_row['file_name'].$query_str.'" '."\n";

							echo TAB_11.'title ="'.$nav_row['popup_text'].'" >'."\n";

							if ($nav_row['icon_image'] != "")
							{ $icon_image = $nav_row['icon_image']; }
					
							else
							{ $icon_image = DEFAULT_NAV_ICON;}
							
							echo TAB_11.'<img class="NavIcon" id="NavIcon_page_'.$nav_row['page_id'].'" '							
								.'src="/_images_user/'.$icon_image.'" alt="'.$icon_image.'" />'."\n";						

							echo TAB_11.'<span class="NavText" >'.$nav_row['menu_text'].'</span>'."\n";
											
						echo TAB_10.'</a>'."\n"; 

					echo TAB_9.'</li>'. "\n";

				}	
				
				echo TAB_8.'</ul>'."\n";		

			echo TAB_7.'</div>'."\n";
			
			echo "\n";			
			echo TAB_7.'<!--	End Menu List		-->'."\n";
			
		}
	
?>