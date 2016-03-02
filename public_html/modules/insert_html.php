<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	$div_name = 'InsertHtml_'.$mod_id;
	
	$mysql_err_msg = 'User inserted HTML not available';

	//$sql_statement = 'SELECT * FROM mod_insert_html WHERE head_or_body = "body" and active = "on" ORDER BY seq';
	$sql_statement = 'SELECT * FROM mod_insert_html'
	
						.' WHERE mod_id = "'.$mod_id.'"'
						.' AND head_or_body = "body"'
						.' AND active = "on"'
						.' ORDER BY seq'
						;
						
	$html_result = ReadDB ($sql_statement, $mysql_err_msg);	

	echo "\n";			
	echo TAB_7.'<!--	Start Sub User inserted HTML		-->'."\n";		
	echo "\n";			
		
		while ( $code_info = mysql_fetch_array ($html_result))
		{
			echo $code_info['code'] ."\n";		
		}

	echo "\n";			
	echo TAB_7.'<!--	End Sub User inserted HTML		-->'."\n";		
	echo "\n";		
	
?>