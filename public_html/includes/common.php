<?php

//-----This file contains code that is used at the start of all pages.....

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
//---ERRORS: display errors when on a local server
	if (substr($_SERVER['SERVER_ADDR'], 0, 8) == "192.168." )
	{		
		ini_set('display_errors', '1');	
		ERROR_REPORTING(E_ALL);

	}
	else
	{
		ini_set('display_errors', '0');
////////////////////////////////////////////////////////////////////////		
//ini_set('display_errors', '1');	
//ERROR_REPORTING(E_ALL);		
////////////////////////////////////////////////////////////////////////		
	}
	
	//---check if Windoze or Unix to   1-get path_delimiter, and other stuff latter....
	$is_windows = false; 
	$path_delimiter = ':'; 
	$operating_system = PHP_OS; 
	
	if( strcmp( $operating_system, 'WINNT' ) == 0 ) 
	{ 
		$is_windows = true; 
		$path_delimiter = ';'; 
	} 
	
	if (!isset($file_path_offset)) { $file_path_offset = ''; }

	if (!defined('PHP_EOL'))
	{
		if ($is_windows == true) { define ('PHP_EOL', "\r\n"); }
		else { define ('PHP_EOL', "\n"); }
	}
	
	
//----set include paths for LIVE and LOCAL	
	ini_set ('include_path', 

			'.'															/*  ' this directory' */	
			.$path_delimiter.$file_path_offset.'../_include_files/'		/* outside Root Dir */
			.$path_delimiter.$file_path_offset.'includes/'				/* inside Root Dir */
			.$path_delimiter.$file_path_offset.'modules/'				/* custom page content */						
		);
		
//------Cache Control		
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
	header('Cache-Control: no-store, no-cache, must-revalidate'); 
	header('Cache-Control: post-check=0, pre-check=0', FALSE); 
	header('Pragma: no-cache'); 
	
		
//---------Start the Session
	session_start();

//---------Set Cookie for testing	
	setcookie('foo', 'bar', time()+3600);

	

//-----------Get websites CODE Name	
	require_once ( 'code_name.php' );	
	
//-----------Get General Settings	
	require_once ( CODE_NAME.'_configs.php' );
	require_once ( CODE_NAME.'_alert_configs.php' );
	require_once ( 'constants.php' );
	
//-------Get sanitizer...
	require_once ( 'sanitizer.php' );

//---get database and ftp info.....
	require_once ( CODE_NAME.'_db_connect.php' );

//---get Page Layout info.....
	require_once ( CODE_NAME.'_page_layout.php' );
	
//-----------Misc  STRINGs		
	include_once ( CODE_NAME.'_strings.php' );
	include_once ('tab_spaces.php');
	
//---get functions
	require_once ( 'php_functions.php');

//---get Mobile Detect function
	require_once ( 'MobileDetect/Mobile_Detect.php');
	$mobile_detect = new Mobile_Detect;
	
//------------set default time zone
	if (defined('DEFAULT_TIME_ZONE'))
	{
		date_default_timezone_set(DEFAULT_TIME_ZONE); 	//-----------only works on PHP 5.1 +	
	}

	
//-----Get  Theme ID
	if ( isset ($_SESSION['user_theme_set']) AND USER_SELECTS_THEME == 'on') { $site_theme_id = $_SESSION['user_theme_set']; }
	else { $site_theme_id = SITE_THEME_ID; }
	
	
//-----------Supply database connect varibles
	$mysql_err_msg = 'Cannot connect to MySQL';
	$connection = mysql_connect($hostname, $username, $password)
	or die 
	(
		'<h4>ERROR: '.$mysql_err_msg.' - This Website is Temporary OUT OF ORDER'.
		'<br/>Sorry for the inconvenience.</h4>'
	);
	
	$mysql_err_msg = 'Cannot connect to the Database';
	$db_selected = @mysql_select_db($db_name, $connection)
	or die 
	(
		'<h4>ERROR: '.$mysql_err_msg.' - This section of the Website is Temporary OUT OF ORDER'.
		'<br/>Sorry for the inconvenience.</h4>'.
		'<p>ERROR CODE: '.mysql_error().'</p>'
	);
 
//-------------Page SETTINGS-------------------------------
	$page_id = '';
	if (isset($_REQUEST['p']))
	{
		$page_id = $_REQUEST['p'];
	}

	//	read from db	----------
	$mysql_err_msg = 'This Page information unavailable';	
	$sql_statement = 'SELECT * FROM page_info WHERE page_id ="'.$page_id.'"';

	$page_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));			

	
	
	
//-----------Get any Required Pre-Head Modules
	$mod_head_code = '';
	$body_onload_info = '';
	$_SESSION['included_files'] = array();
	$_SESSION['included_head_code'] = array();
	
	$mysql_err_msg = 'Plug-in Module Head Code Information not available';	
	$sql_statement = 'SELECT file_name_pre, mod_id FROM modules, _module_types '
											.' WHERE modules.page_id = "'.$page_id.'"'
											.' AND modules.mod_type_id = _module_types.mod_type_id'
											.' AND modules.active = "on"'
											.' AND (modules.theme_specific = 0 OR modules.theme_specific = '.$site_theme_id.')'
											.' AND _module_types.file_name_pre != ""'
											.' ORDER BY position';	
	

	$head_code_result = ReadDB ($sql_statement, $mysql_err_msg);

	while ($mod_head_info = mysql_fetch_array ($head_code_result))		
	{
		$pre_head_mod_id = $mod_head_info['mod_id'];
		if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/modules/'.$mod_head_info['file_name_pre']))
		{
			require ($mod_head_info['file_name_pre']);		
		}
			
	}

//-----Sanitise ( this is useded to stop hackers stuffing the database )---------------------------

	$sanitiser = new sanitiser();

	foreach($_GET as $key => $value)
	{
		$_GET[$key] = $sanitiser->process($value);
	}
			
	foreach($_POST as $key => $value)
	{
		$_POST[$key] = $sanitiser->processPost($value);
	}
	
	foreach($_COOKIE as $key => $value)
	{
		$_COOKIE[$key] = $sanitiser->process($value);
	}
				
?>
