<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'];
	
	echo TAB_4.'<fieldset id="UpdateEventListEditSettings" class="AdminForm1" style="clear:both;">'."\n";
	
	//	 get event listing settings
	$mysql_err_msg = 'Event Listing Settings unavailable';	
	$sql_statement = 'SELECT * FROM mod_event_list_config'

												.' WHERE mod_id = "'.$_GET['e'].'"'
												;
					
	if ($event_settings = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg)))
	{
	
		//------------set default time zone
		date_default_timezone_set($event_settings['time_zone']);

		if (!isset($_GET['tab']) OR $_GET['tab'] == ''  OR $_GET['tab'] == 0) {$_GET['tab'] = 1;}
		
		
				
				$tab_nav_array = array(
				
										 1 => 'Events'
										,2 => 'More Settings'
										
									);
									
			//	Do Tab navigation	
			echo TAB_5.'<ul id="EventListEditSettingsTabNav" class="TabPanelNavLinks">' ."\n";

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
					require_once ('event_list/cms_event_list_'.$file_suffix.'.php');
							
				echo TAB_5.'</div>'."\n";
						
			echo TAB_5.'</div>'."\n";
	}
	
	echo TAB_4.'</fieldset>'."\n";					


?>