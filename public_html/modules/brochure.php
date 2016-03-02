<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	

	//	Get settings
	$mysql_err_msg = 'Cannot Access Brochure settings';
	$sql_statement = 'SELECT * FROM mod_brochure_settings'.
			
											 ' WHERE mod_id = "'.$mod_id.'"'
											.' AND active = "on" '
											;
											
	$settings_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));

	if (isset($_REQUEST['cat_id']))
	{
		$cat_id = $_REQUEST['cat_id'];
	}
	
	else
	{
		$cat_id = $settings_info['default_cat'];
	}
		
	
	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{			
		$div_name = 'BrochureList_'.$mod_id;
		$can_not_clone = 0;
		$edit_enabled = 1;
		$mod_locked = 2;
	
		//	Display / Hide In-Active Mods
		include ('CMS/cms_inactive_mod_display.php');
		
		echo TAB_7.'<div class="BrochureList HoverShow" id="BrochureList_'.$mod_id.'" >'."\n";

		//	Show Div Mod Button
		echo TAB_7.'<div id="EditDivModDisplay_'.$mod_id.'" class="EditDivModDisplay"' 
					.' title="Click to Edit this &quot;Event Listing&quot; Module">'."\n";
			
			echo TAB_8.'<p style="background-color:#ffffff;color:#aa44aa; cursor: pointer;"'
			.' onClick="javascript:selectMod2Edit(37, '.$mod_id.',\''.$div_name.'\' ,0, 2);">'
			.'[ &quot;Event Listing&quot; Module (click to edit) ]<p>'."\n";
			
		echo TAB_7.'</div>'."\n";
		
		//	Do mod editing Toolbar
		include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
		
		//	Do Mod Config Panel
		include ('CMS/cms_panels/cms_panel_mod_config.php');
		
		require_once('CMS/cms_wysiwyg_mods/brochure/brochure_heading_CMS.php');
		require_once('CMS/cms_wysiwyg_mods/brochure/brochure_listing_CMS.php');
	}
	else
	{	
		echo TAB_7.'<div class="BrochureList" id="BrochureList_'.$mod_id.'" >'."\n";
		
		require_once('brochure/brochure_heading.php');
		require_once('brochure/brochure_listing.php');
	}	
	
	
	echo TAB_7.'</div>'."\n";
		
?>