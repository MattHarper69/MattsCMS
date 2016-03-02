<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
		//	 Module info
		$mysql_err_msg = 'HTML element Information not available';	
		$sql_statement = 'SELECT mod_type_id FROM modules '
													.' WHERE modules.mod_id = "'.$mod_id.'"'
													.' AND modules.active = "on"'
													;	
	
		$element_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			
		switch ($element_info['mod_type_id'])
		{
				case "":
				
					echo TAB_7. "\n"; 
												
					echo TAB_7. "\n";
				
				break;
						
		}
			
			