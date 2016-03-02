<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	Do a Sub Menu if there are child links

		//	read from db	----------
		$mysql_err_msg = 'Cannot Access Footer Navigation Information';
		$sql_statement = 'SELECT * FROM page_info WHERE parent_id ="'.$page_id.'" '.
				
												'AND active="on" '.
												'ORDER BY seq';
		
		$num_links = mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg));
		$result = ReadDB ($sql_statement, $mysql_err_msg);

		//------check if there are sub links:
		if ($num_links > 0 )
		{		
			echo "\n";			
			echo TAB_7.'<!--	Start Sub Menu List		-->'."\n";		
			echo "\n";	
			
			echo TAB_7.'<div class="SubMenuMod" id="SubMenuMod_'.$mod_id.'" >'."\n";
			
				//echo TAB_8.'<ul class="SubMenuMod" >'."\n";

			$count = 1;
			while ($nav_row=mysql_fetch_array ($result))
			{	
					if ($count == 1) 
					{
						echo TAB_8.'<div class="SubMenuModCol_1" id="SubMenuModCol1_'.$mod_id.'" >'."\n"; 
							echo TAB_9.'<ul>'."\n";
					}
					if ($count == round($num_links/2)+1) 
					{
						echo TAB_8.'<div class="SubMenuModCol_2" id="SubMenuModCol2_'.$mod_id.'" >'."\n"; 
							echo TAB_9.'<ul>'."\n";
					}	
					
						echo TAB_10.'<li class="SubMenuMod" id="SubMenuModPage_'.$nav_row['page_id'].'" >'. "\n";

							if ($nav_row['send_p_query'] == "on" ) {$query_str = '?p='.$nav_row['page_id'];}
							else {$query_str = '';}					
						
							echo TAB_11.'<a class="SubMenuMod" href="'.$nav_row['file_name'].$query_str.'" '."\n";

								echo TAB_12.'title ="'.$nav_row['popup_text'].'" >'."\n";

								if ($nav_row['icon_image'] != "")
								{ $icon_image = $nav_row['icon_image']; }
						
								else
								{ $icon_image = DEFAULT_NAV_ICON;}
								
								echo TAB_12.'<img class="NavIcon" id="NavIcon_page_'.$nav_row['page_id'].'" '							
									.'src="/_images_user/'.$icon_image.'" alt="'.$icon_image.'" />'."\n";						

								echo TAB_12.'<span class="NavText" >'.$nav_row['menu_text'].'</span>'."\n";
												
							echo TAB_11.'</a>'."\n"; 

						echo TAB_10.'</li>'. "\n";
					
				if ($count == $num_links OR $count == round($num_links/2)) 
				{
						echo TAB_9.'</ul>'."\n"; 
					echo TAB_8.'</div>'."\n";
				}
					
				$count++;

			}	
					

			echo TAB_7.'</div>'."\n";
			
			echo "\n";			
			echo TAB_7.'<!--	End Menu List		-->'."\n";
			
		}
	
?>