<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	$mysql_err_msg = 'Setting preset theme for this page';
	$sql_statement = 'SELECT * FROM theme_override WHERE mod_id = "'.$mod_id.'"';

	$set_theme_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
	
	
			echo "\n";			
			echo TAB_7.'<!--	Auto Theme set to: 	'.$set_theme_info['theme_id'].'	-->'."\n";		
			echo "\n";	
			
		
	if ( $set_theme_info['active'] == 'on')
	{
		if ( $set_theme_info['default_or_defined'] == 'default')
		{
			$set_theme_id = SITE_THEME_ID;
		}
		
		elseif ($set_theme_info['default_or_defined'] == 'defined' AND $set_theme_info['theme_id'] != 0)
		{
			$set_theme_id = $set_theme_info['theme_id'];			
		}
		
		else {}

		if ($_SESSION['user_theme_set'] != $set_theme_id)
		{
			//	Update Theme	and Reload	----------------------------
			$_SESSION['user_theme_set'] = $set_theme_id;
			

			if ( isset($_SERVER['REQUEST_URI']) ) {$this_page = $_SERVER['REQUEST_URI'];}
			else { $this_page = $_SERVER['PHP_SELF'].'?p='.$page_id; }		
			
			header('location: '.$this_page ); 
			exit();		
		
		}
	}
	
?>