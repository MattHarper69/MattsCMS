<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	echo "\n";					
	echo TAB_5.'<!--	START Top Menu code 	-->'."\n";			
	echo "\n";

	$tab = '&nbsp;&nbsp;-&nbsp;';
	
	//	Get all Page Parent IDs For Opend links, parent links
	$mysql_err_msg = 'The Menu for this page is unavailable';
	$sql_statement = 'SELECT page_id, parent_id FROM page_info WHERE in_menu_top = "on" '	
																.'AND active = "on" ';		
	$result = ReadDB ($sql_statement, $mysql_err_msg);
	$daddy_links = array();
	

	while ($nav_row=mysql_fetch_array ($result))
	{
		$daddy_links[] = $nav_row['parent_id'];
		
	}


	
	//	Fetch Top level Links and data:
	$mysql_err_msg = 'The Menu for this page is unavailable';
	$sql_statement = 'SELECT * FROM page_info WHERE parent_id = 0 '
											.'AND in_menu_top = "on" '	
											.'AND active = "on" '
											.'ORDER BY seq';
										
	$result = ReadDB ($sql_statement, $mysql_err_msg);
	
	//	Need to determin  if fly-outs should go to the left
	//	Get the center link (center of page)  by getting number of top links and dividing by 2
	$centre_link = round(mysql_num_rows($result)/2);
	$count_links = 1;
	
	echo TAB_5.'<div id="MenuTopDivSelect" >'."\n";

		echo TAB_6.'<label class="MenuTopLabel" for="MenuTop">Navigation: </label>'."\n";
	
		echo TAB_6.'<select id="MenuTop" onChange="self.location=this.options[this.selectedIndex].value">'."\n";
		
		while ($nav_row=mysql_fetch_array ($result))
		{	

			
			//	is this the currently Selected Link ??
			if ( $nav_row['page_id'] == $page_id ) 
			{ 
				$selected = ' selected="selected"';
				$current_page_name = $nav_row['menu_text'];
			}
			else 
			{ 
				$selected = '';
				$current_page_name = '';
			}

		
			//	Do Link	---------------------------
			if ($nav_row['send_p_query'] == "on" ) {$query_str = '?p='.$nav_row['page_id'];}
			else {$query_str = '';}	
			
			echo TAB_7.'<option class="MenuTopSelect" id="MenuTopPageId_'.$nav_row['page_id'].'" '
			.$selected.' value="'.$nav_row['file_name'].$query_str.'">'."\n";


			echo TAB_8.$nav_row['menu_text']."\n";	

												
			echo TAB_7.'</option>'."\n";
					
			$parent_id = $nav_row['page_id'];						
			SubTopMenu ($page_id, $parent_id, $daddy_links);		
			
			$count_links++;

		}	
		
		echo TAB_6.'</select>'."\n";
		
		echo TAB_6.'<span class="MenuTopLabel MenuTopLabelCurrentPage">'.$current_page_name.'</span>'."\n";
		
	echo TAB_5.'</div>'."\n";

	
	


	
//-------Dropdown Sub Menu Function:

	function SubTopMenu ($page_id, $parent_id, $daddy_links)
	{			
		global $connection, $tab;
		
		//	read from db to check if there are sub links	----------
		$mysql_err_msg = 'The Menu for this page is unavailable';
		$sql_statement = 'SELECT * FROM page_info WHERE '
														.'parent_id = "'.$parent_id.'" '
														.'AND in_menu_top = "on" '							
														.'AND active = "on" '
														.'ORDER BY seq';
											
		$result = ReadDB ($sql_statement, $mysql_err_msg);
	
		//------check if there are sub links:
		if (mysql_num_rows($result) > 0 )
		{
			
			while ($nav_row=mysql_fetch_array ($result))
			{		
				//	determin CLASS selectors
					
				//	is this the currently Selected Link ??
				if ( $nav_row['page_id'] == $page_id ) 
				{ $selected = 'selected="Selected"';}				
				else {$selected = '';}
				
				
				//	Do Link	---------------------------
				if ($nav_row['send_p_query'] == "on" ) {$query_str = '?p='.$nav_row['page_id'];}
				else {$query_str = '';}	
					
					echo TAB_9.'<option class="MenuTopSelect" id="MenuTopPageId_'.$nav_row['page_id'].'" '
						.$selected.' value="'.$nav_row['file_name'].$query_str.'">'."\n";


						echo TAB_10.$tab.$nav_row['menu_text']."\n";	

												
					echo TAB_9.'</option>'."\n";
					
					
					
					$parent_id = $nav_row['page_id'];						
					$tab .= "&nbsp;&nbsp;-&nbsp;";	
					
					SubTopMenu ($page_id, $parent_id, $daddy_links);
					
					$tab = substr($tab, 19);		
				

																					
			}	
			

		}				

	}				
?>