<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	if (isset($_COOKIE['CMS_OpenTab']) AND $_COOKIE['CMS_OpenTab'] > 0 AND $_COOKIE['CMSwinState'] != 3)
	{

		if ($_COOKIE['CMS_OpenTab'] < 6)
		{
			$function = 'OpenPageOptionsPanel';
		}
		if ($_COOKIE['CMS_OpenTab'] > 5)
		{
			$function = 'OpenPageNavOptionsPanel';
		}
		
		$tab_id = $_COOKIE['CMS_OpenTab'];
		
		echo TAB_1.'<script type="text/javascript" >' ."\n";	
			echo TAB_2.'$(document).ready(function() ' ."\n";	
			echo TAB_2.'{' ."\n";
				echo TAB_3.$function.'();' ."\n";
				echo TAB_3.'$( "#PageGeneralSettingsTabs div:not(#TabPanel_'.$tab_id.')" ).hide()' ."\n";
				echo TAB_3.'$( "#PageNavSettingsTabs div:not(#TabPanel_'.$tab_id.')" ).hide()' ."\n";
				echo TAB_3.'$( "#OpenTabPanel_'.$tab_id.'").addClass("current");' ."\n";
				echo TAB_3.'$(".CMS_MiniPageLayout").show();' ."\n";
			echo TAB_2.'});	' ."\n";	
			
		echo TAB_1.'</script>'."\n";

	}
	
	//	Open Drag Module panel if left open
	if (isset($_COOKIE['CMS_drag_mod_mode']) AND $_COOKIE['CMS_drag_mod_mode'] == 1 AND $_COOKIE['CMSwinState'] != 3)
	{
		echo TAB_1.'<script type="text/javascript" >' ."\n";	
			echo TAB_2.'$(document).ready(function() ' ."\n";	
			echo TAB_2.'{' ."\n";
				echo TAB_3.'DragModStart();' ."\n";
			echo TAB_2.'});	' ."\n";	
			
		echo TAB_1.'</script>'."\n";
	}
/* 	DONT NEED ??
	//	Get Other page data from db and store in arrays						
	$all_mod_types = array();
	
	$mysql_err_msg = 'Fetching Mod Types Info';					
	$sql_statement = 'SELECT mod_type_id, mod_name FROM _module_types ORDER BY seq';
	
	$page_info_result = ReadDB ($sql_statement, $mysql_err_msg);
	while ($all_mod_types_info = mysql_fetch_array ($page_info_result))
	{
		$all_mod_types[$all_mod_types_info['mod_type_id']] = $all_mod_types_info['mod_name'];
	}
		 */
	//	Get Other page data from db and store in arrays						
	$all_pages = array();
	$sub_group_pages = array();
	
	$mysql_err_msg = 'Fetching Other Page Info';					
	$sql_statement = 'SELECT page_id, page_name, parent_id, seq FROM page_info ORDER BY seq, page_id';
	
	$page_info_result = ReadDB ($sql_statement, $mysql_err_msg);
	while ($all_pages_info = mysql_fetch_array ($page_info_result))
	{
		$all_pages[$all_pages_info['page_id']] = $all_pages_info['page_name'];
		
		if ($all_pages_info['parent_id'] == $page_info['parent_id'])
		{
			$sub_group_pages[$all_pages_info['seq']] = $all_pages_info['page_name'];
		}
	}



	$prev_page_id = NavPrevItem($all_pages, $page_id);
	$next_page_id = NavNextItem($all_pages, $page_id);

	$prev_page_name = $all_pages[$prev_page_id];
	$next_page_name = $all_pages[$next_page_id];
	
	function NavPrevItem(&$all_pages, $page_id) 
	{     
		end($all_pages);     
		$prev = key($all_pages);
		
		do     
		{         
			$tmp_key = key($all_pages);         
			$res = prev($all_pages);     
		} 
		while 
		( ($tmp_key != $page_id) AND $res );
		
		if( $res )     
		{         
			$prev = key($all_pages);     
		} 
		
		return $prev; 
	} 

	function NavNextItem(&$all_pages, $page_id) 
	{     
		$next = 1;     
		reset($all_pages);
		
		do     
		{         
			$tmp_key = key($all_pages);         
			$res = next($all_pages);     
		} 
		while 
		( ($tmp_key != $page_id) AND $res ); 
		
		if( $res )     
		{        
			$next = key($all_pages);     
		} 
		
		return $next;
		
	}  




?>