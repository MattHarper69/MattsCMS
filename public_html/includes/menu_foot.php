<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		// --	remove blank departments:
		//$mysql_err_msg = 'Deleting blank Navigation Data for Footer Menu';
		//$sql_statement = 'DELETE FROM page_info WHERE menu_text = ""';
		//ReadDB ($sql_statement, $mysql_err_msg);	
		
		//	read from db	----------
		$mysql_err_msg = 'Cannot Access Footer Navigation Information';
		$sql_statement = 'SELECT * FROM page_info WHERE in_menu_foot ="on" '.
				
												'AND active="on" '.
												'ORDER BY seq';
												
		$result = ReadDB ($sql_statement, $mysql_err_msg);

		echo TAB_6.'<div class="MenuFooter" id="MenuFooter_'.$page_id.'" >'."\n";
		
			echo TAB_7.'<ul class="MenuFooter" >'."\n";

				$count = 1;	//-----use to put divider between links			
				while ($nav_row=mysql_fetch_array ($result))
				{	
					echo TAB_8.'<li class="MenuFooter"  id="MenuFootPageId_'.$nav_row['page_id'].'" >'. "\n";
					
					if ( $count == '1' ) {echo TAB_8;}

					else { echo TAB_9.' | '; }	//-------put a divider between links ( don't need one in front of first link ) 

						if ($nav_row['send_p_query'] == "on" ) {$query_str = '?p='.$nav_row['page_id'];}
						else {$query_str = '';}
						
						echo '<a class="MenuFooter" href="'.$nav_row['file_name'].$query_str.'" '
							.'title ="'.$nav_row['popup_text'].'" '.$nav_row['a_tag_attrib'].'>'
							//.nl2br(Space2nbsp($nav_row['menu_text']))
							.$nav_row['menu_text']
						.'</a>'."\n"; 

					echo TAB_8.'</li>'. "\n";
					
					$count++;
				}	
			
			echo TAB_7.'</ul>'."\n";		

		echo TAB_6.'</div>'."\n";
		echo "\n";

?>