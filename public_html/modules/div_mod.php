<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	//	Div mod id must be > 10 - to avoid conflict with div_id (header,footer etc))
	
		$div_name = 'Mod_'.$div_id.'_'.$mod_info['mod_id'];
		
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{			
			$edit_enabled = 0;
			$mod_locked = 2;
			$can_not_clone = 1;	
			
			//	Display / Hide In-Active Mods
			include ('CMS/cms_inactive_mod_display.php');
	
			//	Show Div Mod Button
			echo TAB_7.'<div id="EditDivModDisplay_'.$mod_id.'" class="EditDivModDisplay"' 
						.' title="Click to Edit this &quot;Div&quot; Module">'."\n";
				
				echo TAB_8.'<p style="background-color:#ffffff;color:#aa44aa;"'
				.' onClick="javascript:selectMod2Edit(19, '.$mod_id.',\''.$div_name.'\' ,0, 2);">'
				.'[ Inserted Div (click to edit) ]<p>'."\n";
				
			echo TAB_7.'</div>'."\n";
			
			GetModInfo ($page_id, $mod_id, $site_theme_id);
				
				


			//	CSS layout Dispay (for CMS)
			$CSS_layout = '&lt;div id="Mod_'.$div_id.'_'.$mod_info['mod_id'].'" class="DivMod_'.$mod_info['mod_id'].'" &gt;';
				
			//	Do mod editing Toolbar
			include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
				
			//	Do Mod Config Panel
			include ('CMS/cms_panels/cms_panel_mod_config.php');
					
		}

		else
		{
			if ($mod_info['active'] == 'on')
			{		
				GetModInfo ($page_id, $mod_id, $site_theme_id);
			}
			
		}

	
?>