<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	$file_path_offset = '../../';
	
	$div_name_array = array(						
							"banner" => "Header",  "side_1" => "Side 1 Column", "side_2" => "Side 2 Column", "footer" => "Footer"
							);

	$menu_name_array = array(		
								"menu_top" => "Header", "menu_side" => "Side 1 Column","menu_foot" => "Footer", 
								"bread_crumb" => "Bread Crumb"								
							);

	$in_menu_name_array = array(		
								"menu_top" => "Header", "menu_side" => "Side 1 Column","menu_foot" => "Footer", 							
							);
							

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	require_once ('cms_update_common.php');

	
	
	//	Page parent ID and page order select box - Ajax request
	if(isset($_POST['update_parent_id']))
	{

		$mysql_err_msg = 'Fetching Page Info for Updating Menu Order Select box';
		$sql_statement = 'SELECT page_id, page_name FROM page_info'
	
													.' WHERE parent_id = "'.$_POST['update_parent_id'].'"'
													.' ORDER BY seq'
													;	
														
		$result = ReadDB ($sql_statement, $mysql_err_msg);
		$num_pages = mysql_num_rows ($result);
		if ($num_pages > 0)
		{
			$page_seq = 1;
			while ($page_info = mysql_fetch_array ($result))
			{
				$Remove_place = '';
				$position = ($page_seq + 1) / 2;
				if ($_POST['current_page_seq'] != $page_seq + 1 AND $Remove_place != 1)
				{
					
					echo TAB_8.'<option class="BG_white" value="'.$page_seq.'" title="Move to Here - pos: '.$position.'">'
								.'&raquo; '.$position.' _______________</option>' ."\n";
								
				}				

				if ( $_POST['current_page_seq'] == $page_seq + 1)
				{ 
					echo TAB_8.'<option  class="BG_white" value="'.$page_seq.'" selected="selected" >'
								.'( current position:  '.$position.' )</option>' ."\n";
					$Remove_place = 1;
				}
				else 
				{ 
					
					echo TAB_8.'<option disabled="disabled" >'
								.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;('.$page_info['page_name'].')</option>' ."\n";
					$Remove_place = '';			
																	
				}				

				$page_seq = $page_seq + 2;

					
			}
			
				if ($_POST['current_page_seq'] != $num_pages * 2)
				{										
					$position = ($page_seq + 1) / 2;
					echo TAB_8.'<option class="BG_white" value="'.$page_seq.'" title="Move to Here - pos: '.$position.'">'
						.'&raquo; '.$position.' _______________</option>' ."\n";	
				}	
		
		}


		
		else
		{
			echo TAB_8.'<option disabled="disabled" value="1">( no where to move )</option>' ."\n";
		}

		exit;

	}	
	
	
	
	
	
	
//echo 'cms mode = '.$_SESSION['CMS_mode'].' UserPageAccess = '.UserPageAccess($_POST['page_id']).' access = '.$_SESSION['access'];
	
if ($_SESSION['CMS_mode'] == TRUE AND UserPageAccess($_POST['page_id']) > 1 AND $_SESSION['access'] < 5 )
{
	//	DELETE page
	if(isset($_POST['delete']) AND $_SESSION['access'] < 4 )
	{

		$mysql_err_msg = 'Deleting Page';
		$sql_statement = 'DELETE FROM page_info WHERE page_id = '.$_POST['page_id'];													
		UpdateDB ($sql_statement, $mysql_err_msg);

		
		// delete modules
		$sql_statement = 'Select mod_id FROM modules WHERE page_id = '.$_POST['page_id'];										
		$mod_result = ReadDB ($sql_statement, $mysql_err_msg);	
		
		while ($mod_info = mysql_fetch_array ($mod_result))
		{
			$mysql_err_msg = 'Obtain Database table names for Deleting Modules';
			$sql_statement = 'SHOW TABLES';								
										
			$something = ReadDB ($sql_statement, $mysql_err_msg);
			while ($table_names_info = mysql_fetch_array ($something))
			{
				$sql_statement = 'SHOW COLUMNS FROM '.$table_names_info[0].' LIKE "mod_id"';
				$test_for_column = ReadDB ($sql_statement, $mysql_err_msg);
				$exists = (mysql_num_rows($test_for_column))?TRUE:FALSE;
				if($exists) 
				{					
					$sql_statement = 'DELETE FROM '.$table_names_info[0].' WHERE mod_id = '.$mod_info[0];
											
					//echo '<p>'.$sql_statement.'</p>';										
					UpdateDB ($sql_statement, $mysql_err_msg);									
				}
			
			}
			
		}
		
		//exit();
		
		$mysql_err_msg = 'Deleting Modules in Deleted Page';
		$sql_statement = 'DELETE FROM modules WHERE page_id = '.$_POST['page_id'];	
													
		UpdateDB ($sql_statement, $mysql_err_msg);		
		
		
		// delete asscociated user page access rights
		$mysql_err_msg = 'Deleting Pages user access rights';
		$sql_statement = 'DELETE FROM user_page_access WHERE page_id = '.$_POST['page_id'];	
													
		UpdateDB ($sql_statement, $mysql_err_msg);
		
		//	default to return to home page when deleted
		$_POST['page_id'] = HOME_PAGE_ID;
		
	}
	
	


	
	
	
	//	Update General Page configs
	if(isset($_POST['update_page_config_submit']))
	{
		$mysql_err_msg = 'Updating General Page Configuration';
		
		//	build query str for all divs and menus
		$div_active_q_str = '';
		$sync_active_q_str = '';		
		
		foreach ($div_name_array as $div_name => $display_name)
		{	
			if (isset($_POST[$div_name.'_active']) AND $_POST[$div_name.'_active'] == 'on')
			{
				$active_set = 'on';
			}
			else
			{
				$active_set = '';
			}			

			if (isset($_POST['sync_'.$div_name]) AND $_POST['sync_'.$div_name] == 'on')
			{
				$sync_set = 'on';
			}
			else
			{
				$sync_set = '';
			}	
			
			$div_active_q_str .= ', '.$div_name.'_active = "'.$active_set.'"';	
			$sync_active_q_str .= ', sync_'.$div_name.' = "'.$sync_set.'"';
		}
		
		$menu_active_q_str = '';
		
		foreach ($menu_name_array as $menu_name => $display_name)
		{
			if (isset($_POST[$menu_name.'_active']))
			{
				$menu_active_q_str .= ', '.$menu_name.'_active = "'.$_POST[$menu_name.'_active'].'"';
			}
			else
			{
				$menu_active_q_str .= ', '.$menu_name.'_active = ""';
			}				
			
			
		}
	
		if (isset($_POST['page_active']) AND $_POST['page_active'] == 'on')
		{
			$active = 'on';
		}
		else
		{
			$active = '';
		}		

		if (isset($_POST['requires_login']) AND $_POST['requires_login'] == 'on')
		{
			$requires_login = 'on';
		}
		else
		{
			$requires_login = '';
		}	

		if (isset($_POST['titleTag_use_siteName']) AND $_POST['titleTag_use_siteName'] == 'on')
		{
			$titleTag_use_siteName = 'on';
		}
		else
		{
			$titleTag_use_siteName = '';
		}	

		if (isset($_POST['titleTag_use_pageName']) AND $_POST['titleTag_use_pageName'] == 'on')
		{
			$titleTag_use_pageName = 'on';
		}
		else
		{
			$titleTag_use_pageName = '';
		}	

		if (isset($_POST['titleTag_use_seperator']) AND $_POST['titleTag_use_seperator'] == 'on')
		{
			$titleTag_use_seperator = 'on';
		}
		else
		{
			$titleTag_use_seperator = '';
		}

		
		$sql_statement = 'UPDATE page_info SET'

									.' page_name = "'.$_POST['page_name'].'"'
									.', active = "'.$active.'"'
									.', auto_heading = "'.$_POST['auto_heading'].'"'
									.', titleTag_use_global = "'.$_POST['titleTag_use_global'].'"'
									.', titleTag_use_siteName = "'.$titleTag_use_siteName.'"'
									.', titleTag_use_pageName = "'.$titleTag_use_pageName.'"'
									.', titleTag_use_seperator = "'.$titleTag_use_seperator.'"'									
									.', titleTag_text = "'.$_POST['titleTag_text'].'"'
									.', cms_comments = "'.$_POST['cms_comments'].'"'
									.', requires_login = "'.$requires_login.'"'
									.', access_code = "'.$_POST['access_code'].'"'
									.$div_active_q_str
									.$menu_active_q_str
									.$sync_active_q_str
									
									.' WHERE page_id = "'.$_POST['page_id'].'"'	
									;
		//echo $sql_statement;													
		UpdateDB ($sql_statement, $mysql_err_msg);	
	
	}
	
	//	Update Page NAV configs
	if(isset($_POST['update_page_nav_submit']) AND $_SESSION['access'] < 4 )
	{
		$mysql_err_msg = 'Updating Page Navigation Configuration';
		
		//	build query str for all divs and menus
		$in_menu_q_str = '';
		foreach ($in_menu_name_array as $menu_name => $display_name)
		{
			if (isset($_POST['in_'.$menu_name]) AND $_POST['in_'.$menu_name] == 'on')
			{
				$in_menu_q_str .= ', in_'.$menu_name.' = "'.$_POST['in_'.$menu_name].'"';
			}
			else
			{
				$in_menu_q_str .= ', in_'.$menu_name.' = ""';
			}				
			
		}
	
		if (isset($_POST['include_in_sitemap']) AND $_POST['include_in_sitemap'] == 'on')
		{
			$include_in_sitemap = 'on';
		}
		else
		{
			$include_in_sitemap = '';
		}
		
		$sql_statement = 'UPDATE page_info SET'

									.' menu_text = "'.$_POST['menu_text'].'"'
									.', url_alias = "'.str_replace (' ', '_', $_POST['url_alias']).'"'
									.', file_name = "'.str_replace (' ', '_', $_POST['url_alias']).'"'		//	may need to delete or re-work??
									.', popup_text = "'.$_POST['popup_text'].'"'
									.', parent_id = "'.$_POST['parent_id'].'"'
									.', seq = "'.$_POST['seq'].'"'
									.', include_in_sitemap = "'.$include_in_sitemap.'"'
									.', priority = "'.$_POST['sitemap_priority'].'"'
									.$in_menu_q_str
									
									.' WHERE page_id = "'.$_POST['page_id'].'"'	
									;
												
		UpdateDB ($sql_statement, $mysql_err_msg);	

		

		//	Re-Order page nav order
		$mysql_err_msg = 'Fetching Page Info for Updating';
		$sql_statement = 'SELECT page_id, seq from page_info' 
	
								.' WHERE parent_id = "'.$_POST['parent_id'].'"'
								.' ORDER BY seq'
								;

		$result = ReadDB ($sql_statement, $mysql_err_msg);
		
		$mysql_err_msg = 'Updating Page order';
		$page_seq = 2;
		
		while ($seq_update = mysql_fetch_array ($result))
		{
			$sql_statement = 'UPDATE page_info SET' 
	
								.' seq = "'.$page_seq.'"'
								.' WHERE page_id = "'.$seq_update['page_id'].'"'
								;
								
			UpdateDB ($sql_statement, $mysql_err_msg);
			
			$page_seq = $page_seq + 2;								
		
		}
		
		
	}	
	
	
	//	update the .htacces File
	$error = UpdateHtaccesFile (HOME_PAGE_ID);

	//	update sitemap.xml file
	$error .= UpdateSiteMapFile (HOME_PAGE_ID);	

	if ($error == FALSE)
	{
		$_SESSION['update_success_msg'] = "Update Succesfull";
	}
	else
	{
		$_SESSION['update_error_msg'] = $error;	
	}
			
}
	
else
{	
	$_SESSION['update_error_msg'] = '- Insufficient Privileges to Modify Data \n';	
}

	$return_url = '/index.php?p='.$_POST['page_id'];
	
	//	Re-Direct BACK
	header('location: '.$return_url); 
	exit();	
	
?>