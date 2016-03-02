<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$href_url = 'http://siteofhand.com.au';
	$on_click = '';
	
	$div_name = 'SiteCredit_'.$mod_id;
	
	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{
		$edit_enabled = 0;
		$mod_locked = 2;	
			
		//	Display / Hide In-Active Mods
		include ('CMS/cms_inactive_mod_display.php');
						
		$hover_class = ' HoverShow Draggable';
		$on_click = ' onClick="javascript:selectMod2Edit('.$mod_id.', \''.$div_name.'\',0,2);"';
		
		$href_url = '#';
	}

	else
	{$hover_class = '';}	
	
	echo TAB_6.'<!--	obligatory Credit for hard-working Web-developers will follow...	-->'."\n\n";
	
	echo TAB_7.'<div class="SiteCredit'.$hover_class.'" id="'.$div_name.'"'.$on_click.' >'."\n";
		echo TAB_8.'<p>'."\n";
			echo TAB_9.'<span class="SiteCreditTag" >'.SITE_CREDIT_TAGLINE.'</span>'."\n";
			echo TAB_9.'<span><a href="'.$href_url.'">Site of Hand Website Design</a></span>'."\n";
		echo TAB_8.'</p>'."\n";
	echo TAB_7.'</div>'."\n";
	
	
	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{
		//	Do mod editing Toolbar
		include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
		
		//	Do Mod Config Panel
		include ('CMS/cms_panels/cms_panel_mod_config.php');		
		}
	
?>