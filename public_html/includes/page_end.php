<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

					echo TAB_5.'</div>'."\n";	//------end CentreColumn
						
						echo "\n";				
					echo TAB_5.'<!--	END Main Content code 	-->'."\n";
					echo "\n";
					
				//---------Do Right Side Strip:-----
				if ($page_info['side_2_active'] == "on")
				{
					echo TAB_5.'<!--	START '.DIV_4_NAME.' code 	-->'."\n";
					echo "\n";
					
					if ( DIV_1_HAS_UNIQUE_ID == 4 )
					{ $page_id_tag = '_'.$page_id;}
					else { $page_id_tag = '';}
					
					echo TAB_5.'<div class="'.DIV_4_NAME.' sortable sortable4" id="'.DIV_4_NAME.$page_id_tag.'" >'."\n";

						//	Module info
						GetModInfo ($page_id, 4, $site_theme_id);

					echo TAB_5.'</div>'." \n";
					
					echo "\n";		
					echo TAB_5.'<!--	END '.DIV_4_NAME.' code 	-->'."\n";					
					echo "\n";					
				}
			
				echo TAB_4.'</div>'." \n";	//-------end Wrapper 3	
				echo "\n";
				
				echo TAB_4.'<div class="WrapperBottom" id="WrapperBottom_'.$page_id.'" >'." \n";
				echo "\n";				
				
				//---------Do Footer:-------------
				if ($page_info['footer_active'] == "on" )
				{										
					echo "\n";	
					echo TAB_5.'<!--	START '.DIV_5_NAME.' code 	-->'."\n";		
					echo "\n";
					
					if ( DIV_5_HAS_UNIQUE_ID == 1 )
					{ $page_id_tag = '_'.$page_id;}
					else { $page_id_tag = '';}	
					
					echo TAB_5.'<div class="'.DIV_5_NAME.' sortable sortable5" id="'.DIV_5_NAME.$page_id_tag.'">'." \n";
					echo "\n";
					
					//----------------Footer Links ( if activated )
					if ($page_info['menu_foot_active'] == 'on')
					{				
						include_once ('menu_foot.php');			
					}
					
					elseif ($page_info['menu_foot_active'] == "select")
					{
						include_once ('menu_foot_select.php');	
					}
					
					elseif ($page_info['menu_foot_active'] == "selectIfMobile")
					{
						
						if (CheckUserAgent ('mobile'))
						{
							include_once ('menu_foot_select.php');	
						}
						else
						{
							include_once ('menu_foot.php');	
						}								

					}
						
						//	Module info
						GetModInfo ($page_id, 5, $site_theme_id);
					
					echo TAB_5.'</div>'." \n";
					
					echo "\n";		
					echo TAB_5.'<!--	END '.DIV_5_NAME.' code 	-->'."\n";							
					echo "\n";			
				}
					
				echo TAB_4.'</div>'."\n";	//-------end WrapperBottom		
				echo "\n";	
				
			echo TAB_3.'</div>'." \n";	//-------end Wrapper 2	
			echo "\n";
				
		echo TAB_2.'</div>'." \n";	//-------end Wrapper 1
		echo "\n";
				
	echo TAB_1.'</div>'."\n";	//---------end Container
	echo "\n";
					
		//--------------exrta divs
		echo TAB_1.'<div id="extraDiv1"><span></span></div><div id="extraDiv2"><span></span></div><div id="extraDiv3"><span></span></div>'."\n";
		echo TAB_1.'<div id="extraDiv4"><span></span></div><div id="extraDiv5"><span></span></div><div id="extraDiv6"><span></span></div>'."\n";
		echo "\n";
		
	echo '</body>'."\n";

echo '</html>'."\n";

include_once ( 'credits.php' );

?>