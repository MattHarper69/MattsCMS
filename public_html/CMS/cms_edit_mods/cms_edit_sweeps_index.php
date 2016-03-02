<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'];
	
	require_once (CODE_NAME.'_shop_configs.php');
	require_once (CODE_NAME.'_alert_configs.php');
	require_once ('../modules/sweeps/sweeps_php_functions.php');
	
	//------------set default time zone
	date_default_timezone_set(SHOP_TIME_ZONE);

	if (!isset($_GET['tab']) OR $_GET['tab'] == '') {$_GET['tab'] = 1;}
	
	echo TAB_4.'<fieldset id="UpdateShopEditSettings" class="AdminForm1" style="clear:both;">'."\n";	
			
			$tab_nav_array = array(
									 1 => 'General'
									,2 =>  SHOP_ITEM_ALIAS.'s'			
									,3 => 'Categories'
									,4 => 'Assignments'
									,5 => 'Postage'
									,6 => 'Orders'
									,7 => 'Alerts'
									,8 => 'Export Data'

								);
		//	Do Tab navigation	
		echo TAB_5.'<ul id="ShopEditSettingsTabNav" class="TabPanelNavLinks">' ."\n";

		foreach ($tab_nav_array as $key => $value)
		{
			if ($_GET['tab'] == $key) { $current = 'class="current"';}
			else { $current = '';}
			echo TAB_6.'<li '.$current.'><a href="'.$this_page.'&amp;tab='.$key.'">'.$value.'</a></li>' ."\n";
		}

		echo TAB_5.'</ul>' ."\n";	

		//	Panel
		echo TAB_5.'<div class="TabPanelContainer" id="ShopEditSettingsTabs" >' ."\n";
				
			echo TAB_5.'<div class="AdminFormTabPanel">'."\n";
					
				$file_suffix = str_replace(' ', '_' , $tab_nav_array[$_GET['tab']]);
				require_once ('sweeps/cms_sweeps_'.$file_suffix.'.php');
						
			echo TAB_5.'</div>'."\n";
					
		echo TAB_5.'</div>'."\n";

	echo TAB_4.'</fieldset>'."\n";					


?>