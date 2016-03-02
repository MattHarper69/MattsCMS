<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	$div_name = 'InsertFile_'.$mod_id;
	
	$can_not_clone = 0;
	$edit_enabled = 0;
	$mod_locked = 2;	
			
	//	read from db	----------
	$mysql_err_msg = 'This File unavailable';	
	$sql_statement = 'SELECT * FROM mod_insert_file WHERE mod_id = "'.$mod_id.'" ';

	$mod_data_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
	
	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{			
		

			

		//	FOR CMS MODE ONLY	================================================================================
		
			
			//	Display / Hide In-Active Mods
			include ('CMS/cms_inactive_mod_display.php');

			//	Show Div Mod Button
			echo TAB_7.'<div id="EditDivModDisplay_'.$mod_id.'" class="EditDivModDisplay"' 
						.' title="Click to Edit this &quot;Inserted File&quot; Module">'."\n";
				
				echo TAB_8.'<p style="background-color:#ffffff;color:#aa44aa; cursor: pointer;"'
				.' onClick="javascript:selectMod2Edit(36, '.$mod_id.',\''.$div_name.'\' ,0, 2);">'
				.'[ Inserted File Module (click to edit) ]<p>'."\n";
				
			echo TAB_7.'</div>'."\n";
		
		
			echo TAB_7.'<div id="'.$div_name.'" class="InsertFile" >'."\n\n";

				include ('/_files/' . $mod_data_info['file_name']) ."\n\n";
	
			echo TAB_7.'</div>'."\n";			

		
	}
	
	else
	{
		if ($mod_info['active'] == 'on')
		{			
			echo TAB_7.'<div id="'.$div_name.'" class="InsertFile" >'."\n\n";
			echo TAB_7.'<!-- Start User File Inserted -->'."\n\n";
				include ('_files/' . $mod_data_info['file_name']);
			echo TAB_7.'<!-- End User File Inserted -->'."\n\n";	
			echo TAB_7.'</div>'."\n";					
		}

	}
	
	
		//	FOR CMS MODE ONLY	================================================================================
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{	


			//	CSS layout Dispay (for CMS)
			$CSS_layout = '&lt;div id="Mod_'.$div_id.'_'.$mod_info['mod_id'].'" class="DivMod_'.$mod_info['mod_id'].'" &gt;';
			
			//	Do mod editing Toolbar
			include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
			
			//	Do Mod Config Panel
			include ('CMS/cms_panels/cms_panel_mod_config.php');
								

		}
	
		
?>