<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	echo "\n";					
	echo TAB_6.'<!--	START Side Menu code 	-->'."\n";			
	echo "\n";
		
	$tab = '';
	
	//	Get Parent IDs of Opened link	 and put in array---------- 
	$opened_link_ids = GetLinkPath ('page_info', $page_id);

	//	Get all Page Parent IDs For Opend links, parent links
	$mysql_err_msg = 'The Menu for this page is unavailable';
	$sql_statement = 'SELECT page_id, parent_id FROM page_info WHERE in_menu_side = "on" '	
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
		echo TAB_6.'<!--[if lte IE 6]>'."\n";
		
			echo TAB_7.'<script type="text/javascript" src="includes/javascript/MenuHoverIE6FixSide.js"></script>'."\n";	

		echo TAB_4.'<![endif]-->'."\n";	
	}
			
	//	get top level link details
	
	$mysql_err_msg = 'The Menu for this page is unavailable';
	$sql_statement = 'SELECT * FROM page_info WHERE parent_id = 0 '
											.'AND in_menu_side = "on" '	
											.'AND active = "on" '
											.'ORDER BY seq';
											
	$result = ReadDB ($sql_statement, $mysql_err_msg);

	echo TAB_6.'<div id="MenuSideDiv" >'."\n";

		echo TAB_7.'<ul id="MenuSide" >'."\n";
	
		while ($nav_row=mysql_fetch_array ($result))
		{	

			//	determine CLASS selectors
			
			//	is this link the currently selected link ??
			if ( $nav_row['page_id'] == $page_id ) { $li_class = ' class="Selected"';}			
			else { $li_class = '';}
			
			//	does this link need an arrow pointing down ??
			if 
			(
				in_array($nav_row['page_id'], $opened_link_ids)
			 OR	($nav_row['page_id'] == $page_id AND in_array($nav_row['page_id'], $daddy_links))
			)
			{ $a_class = 'class="DaddyOpen"';}
			
			//	does this link need an arrow pointing right	??	
			elseif ( in_array($nav_row['page_id'], $daddy_links)) 
			{ $a_class = 'class="Daddy"';}
				
			else { $a_class = '';}	
			
			//	Do Link	---------------------------
			echo TAB_8.'<li'.$li_class.' id="MenuSidePageId_'.$nav_row['page_id'].'" >'."\n";
							
				if ($nav_row['send_p_query'] == "on" ) {$query_str = '?p='.$nav_row['page_id'];}
				else {$query_str = '';}

				echo TAB_9.'<a '.$a_class.' href="http://'.$_SERVER['SERVER_NAME'].'/'.$nav_row['file_name'].$query_str.'" '
					.'title ="'.$nav_row['popup_text'].'" '.$nav_row['a_tag_attrib'].'>'
					//.nl2br(Space2nbsp($nav_row['menu_text']))
					.$nav_row['menu_text']
				.'</a>'."\n"; 

				$parent_id = $nav_row['page_id'];						
				SubSideMenu ($page_id, $opened_link_ids, $parent_id, $daddy_links);
												
			echo TAB_8.'</li>'."\n";

		}	
		
		echo TAB_7.'</ul>'."\n";		

	echo TAB_6.'</div>'."\n";

	echo "\n";		
	echo TAB_6.'<!--	END Side Menu code 	-->'."\n";	
	echo "\n";		
	
	
//------- Sub Menu Function:

	function SubSideMenu ($page_id, $opened_link_ids, $parent_id, $daddy_links)
	{			
		global $tab;	

		//	read from db to check if there are sub links	----------
		$mysql_err_msg = 'The Menu for this page is unavailable';
		$sql_statement = 'SELECT * FROM page_info WHERE '
														.'parent_id = "'.$parent_id.'" '
														.'AND in_menu_side = "on" '							
														.'AND active = "on" '
														.'ORDER BY seq';
		
		$result = ReadDB ($sql_statement, $mysql_err_msg);
		
		//------check if there are sub links:
		if (mysql_num_rows($result) > 0 )
		{
			//-------check if link needs to be open
			if 
			(
					in_array($parent_id, $opened_link_ids) 
				OR ($opened_link_ids[0] == 0 AND $parent_id == $page_id)
			)	
			{echo TAB_9.$tab.'<ul class="MenuSideOpen" >'."\n";}
			else
			{echo TAB_9.$tab.'<ul class="MenuSideSub" >'."\n";}
				
			while ($nav_row=mysql_fetch_array ($result))
			{		
				//	determine CLASS selectors
			
				//	is this link the currently selected link ??		
				if ( $nav_row['page_id'] == $page_id ) { $li_class = ' class="Selected"';}
				else { $li_class = '';}
				
				//	does this link need an arrow pointing down ??
				if (in_array($nav_row['page_id'], $opened_link_ids))
				{ $a_class = 'class="DaddyOpen"';}
				
				//	does this link need an arrow pointing right ??
				elseif ( in_array($nav_row['page_id'], $daddy_links)) 
				{ $a_class = 'class="Daddy"';}
				
				else { $a_class = '';}	
			
				echo TAB_10.$tab.'<li'.$li_class.' id="MenuSidePageId_'.$nav_row['page_id'].'" >'. "\n";
						
					if ($nav_row['send_p_query'] == "on" ) {$query_str = '?p='.$nav_row['page_id'];}
					else {$query_str = '';}					
					
					echo TAB_11.$tab.'<a '.$a_class.' href="http://'.$_SERVER['SERVER_NAME'].'/'.$nav_row['file_name'].$query_str.'" '
						.'title ="'.$nav_row['popup_text'].'" '.$nav_row['a_tag_attrib'].'>'
						//.nl2br(Space2nbsp($nav_row['menu_text']))
						.$nav_row['menu_text']
					.'</a>'."\n"; 
								
					$parent_id = $nav_row['page_id'];					
					$tab .= "    ";	
					
					SubSideMenu ($page_id, $opened_link_ids, $parent_id, $daddy_links);
					
					$tab = substr($tab, 4);										

				echo TAB_10.$tab.'</li>'. "\n";
																			
			}
			
			echo TAB_9.$tab.'</ul>'."\n";
			
		}	

	}	
	
?>