<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'];
	
	$mod_id = $_GET['e'];	

	if (!isset($_GET['tab']) OR $_GET['tab'] == '') {$_GET['tab'] = 1;}
	
	//	Get Global Settings
	$mysql_err_msg = 'Photo Gallery information unavailable';
	$sql_statement = 'SELECT * FROM mod_photo_gal_settings WHERE mod_id = '.$mod_id;	
	
	$gal_settings = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	
	
	
	echo TAB_1.'<fieldset id="UpdatePhotoGalSettings" class="AdminForm1" style="clear:both;">'."\n";	
			
			$tab_nav_array = array(
									 1 => 'Settings'			
									,2 => 'Categories'
									,3 => 'Images'
									,4 => 'Synchronization'

								);
		//	Do Tab navigation	
		echo TAB_2.'<ul id="ShopEditSettingsTabNav" class="TabPanelNavLinks">' ."\n";

		foreach ($tab_nav_array as $key => $value)
		{
			if ($_GET['tab'] == $key) { $current = 'class="current"';}
			else { $current = '';}
			echo TAB_3.'<li '.$current.'><a href="'.$this_page.'&amp;tab='.$key.'">'.$value.'</a></li>' ."\n";
		}

		echo TAB_2.'</ul>' ."\n";	

		//	Panel
		echo TAB_2.'<div class="TabPanelContainer" id="ShopEditSettingsTabs" >' ."\n";
				
			echo TAB_3.'<div class="AdminFormTabPanel">'."\n\n";
					
				echo TAB_4.'<h4>Gallery: '.$gal_settings['gallery_name'].' ( ID: '.$gal_settings['mod_id'].')</h4>' ."\n";	
				$file_suffix = str_replace(' ', '_' , $tab_nav_array[$_GET['tab']]);
				require_once ('photo_gal/cms_photo_gal_'.$file_suffix.'.php');
						
			echo TAB_3.'</div>'."\n";
					
		echo TAB_2.'</div>'."\n";

	echo TAB_1.'</fieldset>'."\n";					


?>