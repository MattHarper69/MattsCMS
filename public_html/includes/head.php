<?php

	// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	//	DocType etc code...
	echo DOCTYPE_TAG_CODE . "\n\n";
	echo TAB_4 . '<!-- ' . HTML_CODE_COMMENT . ' -->' . "\n\n";
	echo HTML_OPEN_TAG_CODE . "\n\n";
	
echo '<head>'."\n\n";

	//	http-equiv and charset
	echo TAB_1 . META_HTTP_EQUIV_TAG_CODE . "\n";
	
//--------------Adjustable META Content

	// REMOVE empty meta records
	$mysql_err_msg = 'removing blank META data';
	$sql_statement = 'DELETE FROM meta_settings WHERE name = "" ';

	ReadDB ($sql_statement, $mysql_err_msg);
	
	// loop thru meta_settings table to get all meta data	
	$sql_statement = 'SELECT * FROM meta_settings WHERE active = "on" ORDER BY id';
	$mysql_err_msg = 'unable to fetch META data';
	
	if ($result = ReadDB ($sql_statement, $mysql_err_msg))	
	{
		while ($meta_row = mysql_fetch_array ($result))
		{
			echo TAB_1.'<meta name="'.$meta_row['name'].'" content="'.$meta_row['content'].'" />'." \n";
		}
	}

	
//--------------CSS Style Sheet and any JS files
	
	$mysql_err_msg = 'unable to fetch the Sites Theme data';		
	$sql_statement =  'SELECT dir_name'
							.', javascript_file_1'
							.', javascript_file_2'
							.', javascript_file_3'
							.', javascript_file_4'
							
					.' FROM themes WHERE theme_id = "'.$site_theme_id.'"';


	$theme_data = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	
	$site_theme_dir = $theme_data['dir_name'];
	
	//	Load mobile css if mobile device
	//if(CheckUserAgent ('mobile'))
	if( $mobile_detect->isMobile() && !$mobile_detect->isTablet() )
	{
		$css_file_prefix = 'mobile';
		$media = 'Screen';
	}
	else
	{
		$css_file_prefix = '';
		$media = 'Screen';
	}	
	
	echo "\n";	
	
	if (file_exists('_themes/'.$theme_data['dir_name'].'/'.$css_file_prefix.'_css.css'))
	{		
		echo TAB_1.'<link type="text/css" rel="stylesheet"' 
				  .' href="/_themes/'.$theme_data['dir_name'].'/'.$css_file_prefix.'_css.css" media="'.$media.'" />'." \n";	
	}
	else
	{
		echo TAB_1.'<link type="text/css" rel="stylesheet" href="/_themes/'.$theme_data['dir_name'].'/_css.css" media="Screen" />'." \n";		
	}

//--------------Get any external CSS---------
	// REMOVE empty records
	$mysql_err_msg = 'removing blank CSS Include file data';
	$sql_statement = 'DELETE FROM css_includes WHERE file_path = "" ';

	ReadDB ($sql_statement, $mysql_err_msg);	//	'Use ReadDB' instead of 'UpdateDB' to stop db update recording when use logs in or out
	
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
	if ((isset($_SESSION['load_admin']) AND $_SESSION['load_admin'] == TRUE) OR (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE))
	{
		echo TAB_1.'<link type="text/css" rel="stylesheet" href="/CMS/cms_css/'.ADMIN_THEME_FILE.'" media="screen" />'." \n";
	}	

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
	if ((isset($_SESSION['load_admin']) AND $_SESSION['load_admin'] == TRUE) OR (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE))
	{
		echo TAB_1.'<script type="text/javascript" src="/CMS/cms_includes/cms_javascript.js" ></script>'."\n";

		if (JQUERY_UI_FILE)
		{
			echo TAB_1.'<script type="text/javascript" src="/includes/javascript/'.JQUERY_UI_FILE.'" ></script>'."\n";
		}
		
		
		echo TAB_1.'<script type="text/javascript" src="/includes/javascript/jquery.countDown.js" ></script>'."\n";
		//echo TAB_1.'<script type="text/javascript" src="/CMS/cms_includes/JQuery.moreSelectors.js" ></script>'."\n";	// Not needed (notworking) ?
	}		
	
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
	if 
	(
		   (isset($_SESSION['load_admin']) AND $_SESSION['load_admin'] == TRUE) 
		OR (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE)		
	)
	{
		echo "\n";
		echo '<!--		START Dynamically Driven JavaScript Code 	-->'. "\n\n";
		
		echo TAB_1.'<script type="text/javascript" >' ."\n";
			
			echo TAB_2.'EditHilightStyle = "'.CMS_EDIT_OUTLINE_STYLE.'";' ."\n";
			echo TAB_2.'CMSPanelWindowWidth = "'.CMS_PANEL_WIN_WIDTH.'";' ."\n";
			
			// do not include if this Head.php is used within image_show.php
			if ($_SERVER['PHP_SELF'] != "/includes/image_show.php")
			{
				include ('CMS/cms_includes/cms_javascript_html_replace_arrays.php'); 			
			}

			
		echo TAB_1.'</script>'."\n";
		
		echo '<!--		END Dynamically Driven JavaScript Code 	-->'. "\n";
		echo "\n";
	}
//---------------Title---------------
	echo "\n";
	
	if ( $page_info['titleTag_use_global'] != "" )
	{
		//	Display Seperator ?
		if ( $page_info['titleTag_use_seperator'] == "on" AND ( TITLE_SEPERATOR_SYMBOL != '' AND TITLE_SEPERATOR_SYMBOL != NULL ))
		{
			$title_seperator = TITLE_SEPERATOR_SYMBOL;
		}
		else {$title_seperator = '';}
		
		//	Display Site Name ?
		if ( $page_info['titleTag_use_siteName'] == "on" AND ( SITE_NAME != '' AND SITE_NAME != NULL )) 
		{
			$title_site_name = SITE_NAME . $title_seperator;
		}
		else {$title_site_name = '';}
		
		//	use Global or Unique Tag ?
		if ( $page_info['titleTag_use_global'] == "global" AND ( SITE_TITLE_TAGLINE != '' AND SITE_TITLE_TAGLINE != NULL ))
		{
			$title_unique = SITE_TITLE_TAGLINE;
		}
		else {$title_unique = $page_info['titleTag_text'];}
		
		if ( $page_info['titleTag_use_pageName'] == "on" )
		{
			$title_page_name = $title_seperator . $page_info['page_name'];
		}
		else {$title_page_name = '';}
		
		//	Build Title Tag
		$title_tag = $title_site_name . $title_unique  . $title_page_name;
		
		//	Display Tag	
		if ($title_tag != "")
		{
			echo TAB_1.'<title>'.$title_tag.'</title>'." \n\n"; 	
		}
	}
	
echo '</head>'." \n";

echo " \n";

?>