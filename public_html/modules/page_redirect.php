<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	$div_name = 'PageRedirect_'.$mod_id;
	$db_table = 'mod_page_redirect';
	
	//	read from db	----------
	$mysql_err_msg = 'This Page Redirect info unavailable';	
	$sql_statement = 'SELECT * FROM '.$db_table.' WHERE mod_id = "'.$mod_id.'" ';

	$mod_data_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
	
	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{			
		
		$can_not_clone = 0;

		$edit_enabled = 0;
		$mod_locked = 1;
			
		//	Display / Hide In-Active Mods
		include ('CMS/cms_inactive_mod_display.php');
			
	/////////	CMS CONFIG VIA WYSIWYG /////////////////////

		
		//	CSS layout Dispay (for CMS)
		if ($mod_data_info['type'] == 'html')
		{
			$CSS_layout = '&lt;div id="<strong>'.$div_name.'</strong>" class="<strong>PageRedirect</strong>" &gt;'
						.'<span class="FinePrint"> &lt;h3&gt;&lt;a href=""&gt;'.$mod_data_info['message'].'... </span>&lt;/div&gt;';			
		}
		else
		{
			$CSS_layout = '[ No Html]';		
		}

		
		//	Do mod editing Toolbar
		include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
		
		//	Do Mod Config Panel
		include ('CMS/cms_panels/cms_panel_mod_config.php');	
		

		
	}
	
	else
	{
		if ($mod_info['active'] == 'on')
		{			
			if ($mod_data_info['type'] == 'html')
			{
				echo TAB_7.'<div id="'.$div_name.'" class="PageRedirect" >'."\n";
				
					echo TAB_8.'<h3><a href="'.$mod_data_info['to_url'].'">'.$mod_data_info['message'].'</a></h3>' ."\n";
					
					echo TAB_8.'<meta http-equiv= "refresh" content="'.$mod_data_info['delay'].'; url='.$mod_data_info['to_url'].'" />' ."\n";	
					
				echo TAB_7.'</div>'."\n";

			}
			
			else
			{
				header('location: '.$mod_data_info['to_url']); 
				exit();		
			}			
			
			
		}


	}
		
?>