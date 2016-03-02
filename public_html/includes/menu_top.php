<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	echo "\n";					
	echo TAB_5.'<!--	START Top Menu code 	-->'."\n";			
	echo "\n";

	$tab = '';
	
	//	Get all Page Parent IDs For Opend links, parent links
	$mysql_err_msg = 'The Menu for this page is unavailable';
	$sql_statement = 'SELECT page_id, parent_id FROM page_info WHERE in_menu_top = "on" '	
																.'AND active = "on" ';		
	$result = ReadDB ($sql_statement, $mysql_err_msg);
	$daddy_links = array();
	
	$theres_daddys = 0;
	while ($nav_row=mysql_fetch_array ($result))
	{
		$daddy_links[] = $nav_row['parent_id'];
		
		if ($nav_row['parent_id'] != 0)
		{$theres_daddys = 1;}
	}

	//	if there are parent links add javascript
	if ($theres_daddys == 1)
	{
		//	need script to teach IE6 how to hover ULs
		echo TAB_5.'<!--[if lte IE 6]>'."\n";
		
			echo TAB_6.'<script type="text/javascript" src="includes/javascript/MenuHoverIE6FixTop.js"></script>'."\n";	

		echo TAB_5.'<![endif]-->'."\n";	
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
	
	echo TAB_5.'<div id="MenuTopDiv" >'."\n";

		echo TAB_6.'<ul id="MenuTop" >'."\n";
		
		while ($nav_row=mysql_fetch_array ($result))
		{	
			//	determin CLASS selectors (RESET first)
			$li_class = 'NoClass';
						
			//	does this link need an arrow pointing down ??			
			if ( in_array($nav_row['page_id'], $daddy_links)) 
			{ $a_class = 'class="Daddy"';}	
			else {$a_class = '';}
			
			//	is this link to the RIGHT of CENTRE ??
			if ( $count_links > $centre_link )
			{$li_class = 'FlyLeft';}
			
			//	is this the currently Selected Link ??
			if ( $nav_row['page_id'] == $page_id ) 
			{ $li_class .= ' Selected';}

		
			//	Do Link	---------------------------
			echo TAB_7.'<li class="'.$li_class.'" id="MenuTopPageId_'.$nav_row['page_id'].'" >'."\n";

				if ($nav_row['send_p_query'] == "on" ) {$query_str = '?p='.$nav_row['page_id'];}
				else {$query_str = '';}
				
				echo TAB_8.$tab.'<a '.$a_class.' href="'.$nav_row['file_name'].$query_str.'" '
					.'title ="'.$nav_row['popup_text'].'" '.$nav_row['a_tag_attrib'].'>'
					//.nl2br(Space2nbsp($nav_row['menu_text']))
					.$nav_row['menu_text']
				.'</a>'."\n"; 
				
				$parent_id = $nav_row['page_id'];						
				SubTopMenu ($page_id, $parent_id, $daddy_links);
												
			echo TAB_7.'</li>'."\n";
			
			$count_links++;

		}	
		
		echo TAB_6.'</ul>'."\n";		

	echo TAB_5.'</div>'."\n";

	
	
	//	Do a sub Menu listing if IE 6 and below and Javascript turned off
	echo "\n";
	echo TAB_5.'<!-- Render Sub Menu listing if IE 6 and Javascript turned off -->'."\n";
	echo "\n";
	
	echo TAB_5.'<!--[if lte IE 6]>'."\n";
		echo "\n";	
		echo TAB_6.'<noscript>'."\n";

		$parent_id = $page_id;
				
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
			echo TAB_7.'<div id="SubMenuTopDiv">'."\n";
								
				echo TAB_8.'<ul id="SubMenuTop" >'."\n";				
				
				$count = 1;	//-----use to put divider between links
				while ($nav_row=mysql_fetch_array ($result))
				{							
					//	Do Link	---------------------------
					echo TAB_9.'<li class="SubMenuTop" >'."\n";
					
					if ( $count == '1' ) {echo TAB_10;}

					else { echo TAB_10.' | '; }	//-------put a divider between links ( don't need one in front of first link ) 
					
						if ($nav_row['send_p_query'] == "on" ) {$query_str = '?p='.$nav_row['page_id'];}
						else {$query_str = '';}	
						
						echo '<a href="'.$nav_row['file_name'].$query_str.'" '
							.'title ="'.$nav_row['popup_text'].'" '.$nav_row['a_tag_attrib'].'>'
							//.nl2br(Space2nbsp($nav_row['menu_text']))
							.$nav_row['menu_text']
						.'</a>'."\n"; 
														
					echo TAB_9.'</li>'."\n";
					
					$count++;				
				}

				echo TAB_8.'</ul>'."\n";	
				
			echo TAB_7.'</div>'."\n";
					
		}
				
		echo TAB_6.'</noscript>'."\n";
		echo "\n";
		
	echo TAB_5.'<![endif]-->'."\n";
	
	echo "\n";
	echo TAB_5.'<!-- End IE6\'s SPECIAL Menu BAr -->'."\n";
	echo "\n";
	

	echo "\n";		
	echo TAB_5.'<!--	END Top Menu code 	-->'."\n";	
	echo "\n";		


	
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
			echo TAB_8.$tab.'<ul>'."\n";
			while ($nav_row=mysql_fetch_array ($result))
			{		
				//	determin CLASS selectors
		
				//	does this link need an arrow pointing left or right ??
				if ( in_array($nav_row['page_id'], $daddy_links)) 
				{ $a_class = 'class="Daddy"';}				
				else {$a_class = '';}
				
				//	is this the currently Selected Link ??
				if ( $nav_row['page_id'] == $page_id ) 
				{ $li_class = 'class="Selected"';}				
				else {$li_class = '';}
				
				
				//	Do Link	---------------------------
				echo TAB_9.$tab.'<li '.$li_class.' id="MenuTopPageId_'.$nav_row['page_id'].'" >'."\n";
				
					if ($nav_row['send_p_query'] == "on" ) {$query_str = '?p='.$nav_row['page_id'];}
					else {$query_str = '';}	
					
					echo TAB_10.$tab.'<a '.$a_class.' href="'.$nav_row['file_name'].$query_str.'" '
						.'title ="'.$nav_row['popup_text'].'" >'
						//.nl2br(Space2nbsp($nav_row['menu_text']))
						.$nav_row['menu_text']
					.'</a>'."\n"; 
					
					$parent_id = $nav_row['page_id'];						
					$tab .= "    ";	
					
					SubTopMenu ($page_id, $parent_id, $daddy_links);
					
					$tab = substr($tab, 4);		
				
				echo TAB_9.$tab.'</li>'. "\n";
																					
			}	
			
			echo TAB_8.$tab.'</ul>'."\n";

		}				

	}				
?>