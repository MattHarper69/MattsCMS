<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	$mysql_err_msg = 'User inserted HTML (for head) not available';

	$sql_statement = 'SELECT * FROM mod_insert_html'
	
						.' WHERE mod_id = "'.$pre_head_mod_id.'"'
						.' AND head_or_body = "head"'
						.' AND active = "on"'
						.' ORDER BY seq'
						;

	$insert_html_result = ReadDB ($sql_statement, $mysql_err_msg);	

	while ( $code_info = mysql_fetch_array ($insert_html_result))
	{
		$mod_head_code .= 	TAB_1.$code_info['code'] ."\n";
	
	}
	
?>