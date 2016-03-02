<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

		<!-- 	Created with the Notepad++  Text editor (   go to: http://notepad-plus.sourceforge.net   for a free copy )	-->	
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

echo '<head>'." \n";

//-------------http-equiv and charset
	echo TAB_1.'<meta http-equiv="content-type" content="text/html; charset=utf-8" />'."\n";
	//echo TAB_1.'<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />'."\n";

//---------------other Meta tags	

	echo TAB_1.'<meta name="robots" content="noindex,nofollow" />' ." \n";

	
//--------------CSS Style Sheet and any JS files

	if ( isset ($_SESSION['user_theme_set']) AND USER_SELECTS_THEME == 'on') { $site_theme_id = $_SESSION['user_theme_set']; }
	else { $site_theme_id = SITE_THEME_ID; }
	
	$mysql_err_msg = 'unable to fetch the Sites Theme data';		
	$sql_statement =  'SELECT dir_name'
							.', javascript_file_1'
							.', javascript_file_2'
							.', javascript_file_3'
							.', javascript_file_4'
							
					.' FROM themes WHERE theme_id = "'.$site_theme_id.'"';


	$theme_data = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	
	$site_theme_dir = $theme_data['dir_name'];
	
	echo "\n";
	echo TAB_1.'<link type="text/css" rel="stylesheet" href="/_themes/'.$theme_data['dir_name'].'/_css.css"  media="screen" />'." \n";
	
//--------------Get any external CSS---------
	// REMOVE empty records
	$mysql_err_msg = 'removing blank CSS Include file data';
	$sql_statement = 'DELETE FROM css_includes WHERE file_path = "" ';

	ReadDB ($sql_statement, $mysql_err_msg);
	
	// loop thru head_includes to get all include files	
	$sql_statement = 'SELECT * FROM css_includes WHERE active = "on" ORDER BY seq';
	$mysql_err_msg = 'unable to fetch css include file data';
	$result = ReadDB ($sql_statement, $mysql_err_msg);		
	
	while ($css_files = mysql_fetch_array ($result))
	{
		if ($css_files['ie_condition'] != '' AND $css_files['ie_condition'] != NULL )
		{
			echo TAB_1.'<!--[if '.$css_files['ie_condition'].']>'."\n";
			$extra_tab = '  ';
			$end_tag = TAB_1.'<![endif]-->'."\n";
		}
		else
		{
			$extra_tab = '';
			$end_tag = '';	
		}
		
		echo $extra_tab.TAB_1.'<link type="text/css" rel="stylesheet" href="'.$css_files['file_path'].'"'
								.' media="'.$css_files['media_type'].'" />'."\n";
		echo $end_tag;
	}
	
	//	Get CMS CSS if logged in to admin
	//if ($_SESSION['load_admin'] == TRUE OR $_SESSION['CMS_mode'] == TRUE)
	//{
		echo TAB_1.'<link type="text/css" rel="stylesheet" href="/CMS/cms_css/'.ADMIN_THEME_FILE.'" media="screen" />'." \n";
	//}	

//--------------Get any JavaScripts---------
	echo "\n";
	
	if (JQUERY_MASTER_FILE)
	{
		echo TAB_1.'<script type="text/javascript" src="/includes/javascript/'.JQUERY_MASTER_FILE.'" ></script>'."\n";
	}		
		
	// REMOVE empty records
	$mysql_err_msg = 'removing blank Include file data';	
	$sql_statement = 'DELETE FROM js_includes WHERE file_path = "" ';

	ReadDB ($sql_statement, $mysql_err_msg);
	
	// loop thru head_includes to get all include files	
	$sql_statement = 'SELECT * FROM js_includes WHERE active = "on" ORDER BY seq';
	$mysql_err_msg = 'unable to fetch js include file data';
	$result = ReadDB ($sql_statement, $mysql_err_msg);		
	
	echo "\n";

	while ($js_files = mysql_fetch_array ($result))
	{
		echo TAB_1.'<script type="text/javascript" src="'.$js_files['file_path'].'" ></script>'."\n";
	}
	
	//	from theme data
	if ($theme_data['javascript_file_1'] != '')
	{ echo TAB_1.'<script type="text/javascript" src="'.$theme_data['javascript_file_1'].'" ></script>'."\n"; }
	if ($theme_data['javascript_file_2'] != '')
	{ echo TAB_1.'<script type="text/javascript" src="'.$theme_data['javascript_file_2'].'" ></script>'."\n"; }
	if ($theme_data['javascript_file_3'] != '')
	{ echo TAB_1.'<script type="text/javascript" src="'.$theme_data['javascript_file_3'].'" ></script>'."\n"; }
	if ($theme_data['javascript_file_4'] != '')
	{ echo TAB_1.'<script type="text/javascript" src="'.$theme_data['javascript_file_4'].'" ></script>'."\n"; }	

	//	Get CMS JavaScript if logged in to admin
	//if ($_SESSION['load_admin'] == TRUE OR $_SESSION['CMS_mode'] == TRUE)
	//{
		echo TAB_1.'<script type="text/javascript" src="/CMS/cms_includes/cms_javascript.js" ></script>'."\n";
		
		if (JQUERY_UI_FILE)
		{
			echo TAB_1.'<script type="text/javascript" src="/includes/javascript/'.JQUERY_UI_FILE.'" ></script>'."\n";
		}
		
		echo TAB_1.'<script type="text/javascript" src="/includes/javascript/jquery.countDown.js" ></script>'."\n";
		echo TAB_1.'<script type="text/javascript" src="/includes/javascript/CalendarPopup.js" ></script>'."\n";
		echo TAB_1.'<script type="text/javascript" src="/includes/javascript/jquery.dataTables.min.js" ></script>'."\n";
		echo TAB_1.'<script type="text/javascript" src="/includes/javascript/jquery.MultiFile.pack.js" ></script>'."\n";
		echo TAB_1.'<script type="text/javascript" src="/includes/javascript/jquery.asmselect.js" ></script>'."\n";
	//}		
	
//---------------bookmark icon---
	echo "\n";
	echo TAB_1.'<link rel="shortcut icon" href="/favicon.ico" />'." \n";	

	
//-----------Get any extra Code needed that is specific to Modules	
	if (isset($mod_head_code))
	{	
		echo "\n";
		echo '<!--		START Module Specific Head code 	-->'. "\n\n";
		echo $mod_head_code. "\n";
		echo '<!--		END Module Specific Head code 	-->'. "\n";
		echo "\n";
	}

//-----------Do any Dynamically Driven JavaScript Code needed for CMS
	//if ($_SESSION['load_admin'] == TRUE OR $_SESSION['CMS_mode'] == TRUE)
	//{
		echo "\n";
		echo '<!--		START Dynamically Driven JavaScript Code 	-->'. "\n\n";
		echo TAB_1.'<script type="text/javascript" >' ."\n";
			echo TAB_2.'EditHilightStyle = "'.CMS_EDIT_OUTLINE_STYLE.'";' ."\n";
			include ('cms_javascript_html_replace_arrays.php');
		echo TAB_1.'</script>'."\n";
		echo '<!--		END Dynamically Driven JavaScript Code 	-->'. "\n";
		echo "\n";
	//}
//---------------Title---------------
	echo "\n";
	echo TAB_1.'<title>'.SITE_NAME.' - Administration Area</title>'." \n";  	
	
echo '</head>'." \n";

echo " \n";

?>