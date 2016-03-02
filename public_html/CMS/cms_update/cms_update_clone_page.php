<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	$file_path_offset = '../../';
	
		

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	require_once ('cms_update_common.php');
	require_once ('cms_update_cloning_functions.php');

	if ($_SESSION['CMS_mode'] == TRUE AND $_SESSION['access'] < 4 )
	{


		
		///////
		//////		Do form validation for $_POST['clone_num_copies']
		///////
		
		//	Loop thru
		for ($i = 1; $i < $_POST['clone_num_copies'] + 1; $i++)
		{
			$new_page_id = UpdatePageSettings ($i);
			
			$new_div_mod_id = 0;
			
			CloneModsOnPage ($_POST['source_page_id'], $new_page_id);
			
			UpdateUserAccess ($_POST['source_page_id'], $new_page_id);
		}




		// Update last DB update time
		if (isset($_SESSION['user_id']))
		{
			$mysql_err_msg = "updating Update db timestamp";
			$sql_statement = 'UPDATE 1_user_logins SET' 
													.' last_db_update = "'.date("Y-m-d H:i:s").'"'
													.' WHERE user_id = "'.$_SESSION['user_id'].'"'		
													;
			mysql_query($sql_statement, $connection);			
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
		
		$_SESSION['update_error_msg'] .= '- Insufficient Privileges to Modify Data \n';
		
	}


	$return_url = '../cms_add_clone_page.php?p='.$_POST['source_page_id'];
	
	//	Re-Direct BACK
	header('location: '.$return_url); 
	exit();	

	
	

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	function UpdatePageSettings ($i)
	{
		
		$div_name_array = array(						
								"banner" => "Header",  "side_1" => "Side 1 Column", "side_2" => "Side 2 Column", "footer" => "Footer"
								);

		$menu_name_array = array(		
									"menu_top" => "Header", "menu_side" => "Side 1 Column","menu_foot" => "Footer", 
									"bread_crumb" => "Bread Crumb"								
								);

		$in_menu_name_array = array(		
									"menu_top" => "Header", "menu_side" => "Side 1 Column","menu_foot" => "Footer"
								);				
		
				
		//	build query str for all divs and menus
		$div_active_q_str = '';
		$sync_active_q_str = '';
		$menu_active_q_str = '';
		
		
		//	These must be unique and not left blank
		$page_name = trim($_POST['new_page_name']);
		$url_alias = trim($_POST['url_alias']);
		
		if ($page_name == '')
		{
			if ($url_alias != '')
			{
				$page_name = $url_alias;
			}
			else
			{
				$page_name = $_POST['source_page_name'] . '(copy)';				
			}
		}

		if ($url_alias == '')
		{
			if ($page_name != '')
			{
				$url_alias = $page_name;
			}
			else
			{
				$url_alias = $_POST['source_page_name'] . '_copy';				
			}
		}		
		
		$url_alias = str_replace (' ', '_', $url_alias);
		
		//	==============   Use New (Adjusted) Page settings  ==========================		
		if (isset($_POST['adjust_page_settings']) AND $_POST['adjust_page_settings'] == 'on')
		{

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

			//	build query str for all divs and menus
			$in_menu_q_str = '';
			foreach ($in_menu_name_array as $menu_name => $display_name)
			{
				$in_menu_q_str .= ', in_'.$menu_name.' = "'.$_POST['in_'.$menu_name].'"';
			}

		
			if (isset($_POST['seq']) AND $_POST['seq'] != '')
			{
				$seq = $_POST['seq'];
			}
			else
			{
				$seq = $i;
			}
						
			$send_p_query = '';	  //	if getting from new settings, just make null
			$auto_heading = $_POST['auto_heading'];
			$titleTag_use_global = $_POST['titleTag_use_global'];		
			$titleTag_text = $_POST['titleTag_text'];
			$cms_comments = $_POST['cms_comments'];
			$requires_login = $requires_login;
			$access_code = $_POST['access_code'];
			$menu_text = $_POST['menu_text'];
			$popup_text = $_POST['popup_text'];
			$parent_id = $_POST['parent_id'];
			$include_in_sitemap = $_POST['include_in_sitemap'];
			$priority = $_POST['sitemap_priority'];

		}
		
		
		//	================    Use orginal Page settings  ============================
		else
		{
			
			$mysql_err_msg = 'Error Reading Oringinal Page Data';	
			
			//	get Page info from orginal page
			$sql_statement = 'SELECT * FROM  page_info WHERE page_id = '.$_POST['source_page_id'];
														
			$source_page_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));				
				
			foreach ($div_name_array as $div_name => $display_name)
			{	
				if (isset($source_page_info[$div_name.'_active']) AND $source_page_info[$div_name.'_active'] == 'on')
				{
					$active_set = 'on';
				}
				else
				{
					$active_set = '';
				}			

				if (isset($source_page_info['sync_'.$div_name]) AND $source_page_info['sync_'.$div_name] == 'on')
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

			foreach ($menu_name_array as $menu_name => $display_name)
			{
				if (isset($source_page_info[$menu_name.'_active']))
				{
					$menu_active_q_str .= ', '.$menu_name.'_active = "'.$source_page_info[$menu_name.'_active'].'"';
				}
				else
				{
					$menu_active_q_str .= ', '.$menu_name.'_active = ""';
				}				
							
			}
		
			if (isset($source_page_info['active']) AND $source_page_info['active'] == 'on')
			{
				$active = 'on';
			}
			else
			{
				$active = '';
			}		

			if (isset($source_page_info['requires_login']) AND $source_page_info['requires_login'] == 'on')
			{
				$requires_login = 'on';
			}
			else
			{
				$requires_login = '';
			}	

			if (isset($source_page_info['titleTag_use_siteName']) AND $source_page_info['titleTag_use_siteName'] == 'on')
			{
				$titleTag_use_siteName = 'on';
			}
			else
			{
				$titleTag_use_siteName = '';
			}	

			if (isset($source_page_info['titleTag_use_pageName']) AND $source_page_info['titleTag_use_pageName'] == 'on')
			{
				$titleTag_use_pageName = 'on';
			}
			else
			{
				$titleTag_use_pageName = '';
			}	

			if (isset($source_page_info['titleTag_use_seperator']) AND $source_page_info['titleTag_use_seperator'] == 'on')
			{
				$titleTag_use_seperator = 'on';
			}
			else
			{
				$titleTag_use_seperator = '';
			}

			//	build query str for all divs and menus
			$in_menu_q_str = '';
			foreach ($in_menu_name_array as $menu_name => $display_name)
			{
				$in_menu_q_str .= ', in_'.$menu_name.' = "'.$source_page_info['in_'.$menu_name].'"';
			}
			
			
			$seq = $source_page_info['seq'] + 2;
			$send_p_query = $source_page_info['send_p_query'];		
			$auto_heading = $source_page_info['auto_heading'];
			$titleTag_use_global = $source_page_info['titleTag_use_global'];		
			$titleTag_text = $source_page_info['titleTag_text'];
			$cms_comments = $source_page_info['cms_comments'];
			$requires_login = $requires_login;
			$access_code = $source_page_info['access_code'];
			$menu_text = $source_page_info['menu_text'];
			$popup_text = $source_page_info['popup_text'];
			$parent_id = $source_page_info['parent_id'];
			$include_in_sitemap = $source_page_info['include_in_sitemap'];
			$priority = $source_page_info['priority'];			

		}
	
		//	for name, menu, URL and filename need to suffix for multiple copies
		if ($i > 1)
		{
			$page_name = $page_name . ' ' . $i;
			$url_alias = $url_alias . '_' . $i;

			$seq = $seq + $i * 2;
			
		}
		
		if (isset($_POST['suffix_menu_text']) AND $_POST['suffix_menu_text'] == 'on')
		{
			$menu_text = $menu_text . ' ' . $i;
		}		
		
		//	Detect duplicate page names
		$mysql_err_msg = 'Detecting Duplicate Page Names';
		$sql_statement = 'SELECT page_name from page_info WHERE page_name = "'.$page_name.'"';	
		$duplicate_entry = mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg));
		if ($duplicate_entry)
		{
			$page_name = $page_name . ' 1';
		}
		
		//	Detect duplicate URLs
		$mysql_err_msg = 'Detecting Duplicate URL alias';
		$sql_statement = 'SELECT url_alias from page_info WHERE url_alias = "'.$url_alias.'"';	
		$duplicate_entry = mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg));
		if ($duplicate_entry)
		{
			$url_alias = $url_alias . '_1';
		}

		//	if Menu text is blank, use Page Name
		if (trim($menu_text) == '')
		{
			$menu_text = $page_name;
		}		
		
		//	File Name is URL
		$file_name = $url_alias;
				
		$mysql_err_msg = 'Updating General Page Configuration';
		
		//$sql_statement = $update_insert_str
		$sql_statement = 'INSERT INTO page_info SET'

									.' page_name = "'.addslashes($page_name).'"'
									.', active = "'.$active.'"'
									.', auto_heading = "'.$auto_heading.'"'
									.', titleTag_use_global = "'.$titleTag_use_global.'"'
									.', titleTag_use_siteName = "'.$titleTag_use_siteName.'"'
									.', titleTag_use_pageName = "'.$titleTag_use_pageName.'"'
									.', titleTag_use_seperator = "'.$titleTag_use_seperator.'"'									
									.', titleTag_text = "'.addslashes($titleTag_text).'"'
									.', cms_comments = "'.addslashes($cms_comments).'"'
									.', requires_login = "'.$requires_login.'"'
									.', access_code = "'.$access_code.'"'
									
									.', send_p_query = "'.$send_p_query.'"'	
									
									.$div_active_q_str
									.$menu_active_q_str
									.$sync_active_q_str
									
									.', menu_text = "'.addslashes($menu_text).'"'
									.', url_alias = "'.$url_alias.'"'
									.', file_name = "'.$url_alias.'"'		//	may need to delete or re-work??
									.', popup_text = "'.addslashes($popup_text).'"'
									.', parent_id = "'.$parent_id.'"'
									.', seq = "'.$seq.'"'
									.', include_in_sitemap = "'.$include_in_sitemap.'"'
									.', priority = "'.$priority.'"'
									.$in_menu_q_str									

									;
		//echo '<br><br>'.$sql_statement;													

		if(!ReadDB ($sql_statement, $mysql_err_msg))	// must be ReadDB not UpdateDB to get mysql_insert_id
		{
			$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
		}			
		
		RETURN mysql_insert_id();

	}

	function CloneModsOnPage ($source_page_id, $new_page_id)
	{
		
		$new_div_mod_id = 0;
		$div_num_array = array(													
								"banner" => 1,  "side_1" => 2, "centre" => 3, "side_2" => 4, "footer" => 5
								);		
		
		
		foreach ($div_num_array as $div_name => $div_id)
		{
			
			if (isset($_POST['clone_'.$div_name]) AND $_POST['clone_'.$div_name] == "on")
			{				
				CloneModsInDiv ($source_page_id, $new_page_id, $div_id, $new_div_mod_id);
			}
			
		}
		
	}
	
	
	function UpdateUserAccess ($source_page_id, $new_page_id)
	{
		//	Read existing page access rights		
		$mysql_err_msg = 'Reading existing User Page Access rights';
		$sql_statement = 'SELECT  user_id, access_right FROM user_page_access WHERE page_id = "'.$source_page_id.'"';
		$result = ReadDB ($sql_statement, $mysql_err_msg);
		
		
		while ($user_access_rights = mysql_fetch_array($result))
		{

			$mysql_err_msg = 'Updating User Page Access rights';
			$sql_statement = 'INSERT INTO user_page_access SET'
																.'  user_id = "'.$user_access_rights['user_id'].'"'
																.', access_right = "'.$user_access_rights['access_right'].'"'
																.', page_id = "'.$new_page_id.'"';	

			if(!UpdateDB ($sql_statement, $mysql_err_msg))	// must be ReadDB not UpdateDB to get mysql_insert_id
			{
				$_SESSION['update_error_msg'] = ' - A Database Error occured \n';
			}
			
		}
										
	}	


?>	