<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		$check_XHTML_url = 'http://validator.w3.org/check?uri=referer" rel="W3CNewWin';
		$check_CSS_url = 'http://jigsaw.w3.org/css-validator/check/referer" rel="W3CNewWin';

		$on_click = '';
		
		//----------------------Validation links & Logos
		echo "\n";			
		echo TAB_7.'<!--	START W3C Validate Logos and Links code 	-->'."\n";
		echo "\n";
		
		$div_name = 'ValidateLinks_'.$mod_id;
		
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{
			$edit_enabled = 0;
			$mod_locked = 2;	
				
			//	Display / Hide In-Active Mods
			include ('CMS/cms_inactive_mod_display.php');
							
			$hover_class = ' HoverShow Draggable';
			$on_click = ' onClick="javascript:selectMod2Edit(4, '.$mod_id.', \''.$div_name.'\',0,2);"';
			
			$check_XHTML_url = '#';
			$check_CSS_url = '#';
		}

		else
		{$hover_class = '';}
		
		echo TAB_7.'<div class="ValidateLinks'.$hover_class.'" id="'.$div_name.'"'.$on_click.' >'."\n";	
			
			//-----XHTML---------------------------------------------------
			echo TAB_8.'<div class="ValidateLinksXHTML" >'."\n";
				echo TAB_9.'<a href="'.$check_XHTML_url.'" >'." \n";
					echo TAB_10.'<img class = "NoBorder" src="/images_misc/W3C_valid_HTML.png" '." \n";
					echo TAB_10.'alt="Valid XHTML 1.0 Strict" height="31" width="88" />'." \n";
				echo TAB_9.'</a>'." \n";
			echo TAB_8.'</div>'."\n";

			//-----CSS---------------------------------------------------
			echo TAB_8.'<div class="ValidateLinksCSS" >'."\n";
				echo TAB_9.'<a href="'.$check_CSS_url.'" >'." \n";
					echo TAB_10.'<img class = "NoBorder" src="/images_misc/W3C_valid_CSS.gif" '." \n";
					echo TAB_10.'alt="Valid CSS!" height="31" width="88" />'." \n";
				echo TAB_9.'</a>'." \n";
			echo TAB_8.'</div>'."\n";
			
		echo TAB_7.'</div>'."\n";
		
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{
			//	Do mod editing Toolbar
			include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
			
			//	Do Mod Config Panel
			include ('CMS/cms_panels/cms_panel_mod_config.php');		
		}
		
		echo "\n";			
		echo TAB_7.'<!--	END W3C Validate Logos and Links code 	-->'."\n";
		echo "\n";		
		
?>