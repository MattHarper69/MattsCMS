<?php
	
	$mysql_err_msg = 'Creating Javascript Arrays for cleaning HTML';	
	$sql_statement = 'SELECT * FROM _cms_html_replace_list';
	$result = ReadDB ($sql_statement, $mysql_err_msg);

	$replaceHTML_str = '';
	$withHTML_str = '';
	
	while ($replace_html_list = mysql_fetch_array ($result))
	{
		
		$replaceHTML_str .= "\n".TAB_4.'"'.str_replace ('"', '\"', $replace_html_list['find_str']).'",';
		$withHTML_str 	 .= "\n".TAB_4.'"'.str_replace ('"', '\"', $replace_html_list['replace_str']).'",';

	}
	
	$replaceHTML_str = substr($replaceHTML_str,0,-1);
	$withHTML_str = substr($withHTML_str,0,-1);

	
	echo TAB_2.'replaceHTML = new Array ('.$replaceHTML_str ."\n"; 
	echo TAB_2.');' ."\n"; 
	
	echo TAB_2.'withHTML = new Array ('.$withHTML_str ."\n"; 
	echo TAB_2.');' ."\n"; 	

?>