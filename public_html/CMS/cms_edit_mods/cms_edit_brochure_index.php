<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'];
	$mod_id = $_GET['e'];
	
	//require_once (CODE_NAME.'_shop_configs.php');
	//require_once (CODE_NAME.'_alert_configs.php');
	require_once ('../modules/sweeps/sweeps_php_functions.php');
	
	//------------set default time zone
	//date_default_timezone_set(SHOP_TIME_ZONE);
	
	//	Get settings
	$mysql_err_msg = 'Cannot Access Brochure settings';
	$sql_statement = 'SELECT * FROM mod_brochure_settings'.
			
											 ' WHERE mod_id = "'.$mod_id.'"'
											;
											
	$settings_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
	
	$mysql_err_msg = 'Cannot Access Brochure Information(Categories)';
	$sql_statement = 'SELECT * FROM mod_brochure_cats WHERE'
							
															.' mod_id="'.$mod_id.'"'
															.' ORDER BY seq'
															;
															
	$cat_result = ReadDB ($sql_statement, $mysql_err_msg);
	
	if (!isset($_GET['tab']) OR $_GET['tab'] == '') {$_GET['tab'] = 1;}
	
	echo TAB_4.'<fieldset id="UpdateBrochureEditSettings" class="AdminForm1" style="clear:both;">'."\n";	
			
			$tab_nav_array = array(
									 1 => 'General'
									,2 => 'Items'			
									,3 => 'Categories'
									//,4 => 'Assignments'
									//,5 => 'Postage'
									//,6 => 'Orders'
									//,7 => 'Alerts'
									//,8 => 'Export Data'

								);
		//	Do Tab navigation	
		echo TAB_5.'<ul id="BrochureEditSettingsTabNav" class="TabPanelNavLinks">' ."\n";

		foreach ($tab_nav_array as $key => $value)
		{
			if ($key == 2) {$value = $settings_info['item_alias'].'s';}
			
			if ($_GET['tab'] == $key) { $current = 'class="current"';}
			else { $current = '';}
			echo TAB_6.'<li '.$current.'><a href="'.$this_page.'&amp;tab='.$key.'">'.$value.'</a></li>' ."\n";
		}

		echo TAB_5.'</ul>' ."\n";	

		//	Panel
		echo TAB_5.'<div class="TabPanelContainer" id="BrochureEditSettingsTabs" >' ."\n";
				
			echo TAB_5.'<div class="AdminFormTabPanel">'."\n";
					
				$file_suffix = str_replace(' ', '_' , $tab_nav_array[$_GET['tab']]);
				require_once ('brochure/cms_brochure_'.$file_suffix.'.php');
						
			echo TAB_5.'</div>'."\n";
					
		echo TAB_5.'</div>'."\n";

	echo TAB_4.'</fieldset>'."\n";					

	
?>