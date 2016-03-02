<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	//	Get Parent IDs of Opened link	 and put in array---------- 
	$opened_link_ids = GetLinkPath ('page_info', $page_info['page_id']);

	//	Reverse array and add current page to array
	$opened_link_ids = array_reverse($opened_link_ids);
	$opened_link_ids[] = $page_id;
	
	//	no bread crumb needed if current page is  a parent page
	if ($page_info['parent_id'] != 0)
	{
	
		echo "\n";	
		echo TAB_5.'<!--	START Bread crumb code 	-->'."\n";
		echo "\n";	
		
		echo TAB_5.'<div class="BreadCrumbDiv" id="BreadCrumbDiv_'.$page_id.'" >'."\n";						
		
			echo TAB_6.'<p class="BreadCrumb" >You are here:'."\n";			
				
			foreach ($opened_link_ids as $link_id)
			{

				if ($link_id != "" AND $link_id != 0 )
				{
					$mysql_err_msg = 'The Menu for this page is unavailable';
					$sql_statement = 'SELECT * FROM page_info WHERE page_id = "'.$link_id.'" ';
														
					$result = ReadDB ($sql_statement, $mysql_err_msg);
						
					while ($nav_row=mysql_fetch_array ($result))
					{
						if ( $link_id == $page_id )
						{
							//	current page name (no link)
							echo TAB_7.'<span class="BreadCrumbSelected" title="You are at this page already">'
							.$nav_row['menu_text'].'</span>'."\n";
								
						}
						else
						{	
							//	parent links
							if ($nav_row['send_p_query'] == "on" ) {$query_str = '?p='.$nav_row['page_id'];}
							else {$query_str = '';}
							
							echo TAB_7.'<a class="BreadCrumb" href="'.$nav_row['file_name'].$query_str.'" title ="'.$nav_row['popup_text'].'" >'
							.$nav_row['menu_text']
							.'</a>'."\n";

							//	spacer
							echo TAB_6.' '.PATH_SEPERATOR_SYMBOL.' '."\n";
								
						}
					}				
				}
			}
				
			echo TAB_6.'</p>'."\n";	
		
		echo TAB_5.'</div>'."\n";	

		echo "\n";		
		echo TAB_5.'<!--	END Top Bread crumb 	-->'."\n";	
		echo "\n";		
	
	}
		
?>