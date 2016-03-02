<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

//----Get Common code to all pages
	require_once ('includes/common.php');

	require_once ('includes/access.php');

//--------Redirect to home page if page not set
	if (!isset($_REQUEST['p']) OR $_REQUEST['p'] == '')
	{
		header("location: ?p=".HOME_PAGE_ID); 
		exit();
	}

	//	This page's URL - 
	//	this needs to go here and not in the common.php file
	// 	as the common file is used in other type files such as create_captcha
	if(!empty($_SERVER['QUERY_STRING'])) 
	{
		$this_page = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
		$_SESSION['last_known_page'] = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	}
	else
	{
		$this_page = $_SERVER['PHP_SELF'];
		$_SESSION['last_known_page'] = $_SERVER['PHP_SELF'];
	}

	//	**** do "self-Destruct site" code here and redirect to "Site closed - please contact..." page HERE ****  ?????
	
//	HTML goes below...........

// 	Start output buffering
	ob_start();
	


//---------Get Head:
	include_once ('includes/head.php');  
	echo "\n";
	
//---------Get The common TOP of page code:
	require_once (PAGE_START_FILE); 
	echo "\n";
	
//=====================CENTRE OF PAGE CONTENT STARTS HERE=====================================

	//	CMS mode:
	if ( isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE )	
	{
		if (UserPageAccess($page_id) > 0)
		{
			//	Display Page
			GetModInfo ($page_id, 3, $site_theme_id);
		}
				
		else
		{
			// no access allowed
			require_once ('not_authorized.php');
			echo "\n";		
		}
	}	
	
	//	Normal Mode
	else 
	{ 
	
		//------------Need to Log-in??
		if ( $page_info['requires_login'] == "on" AND $_SESSION['authorized'] != TRUE)	
		{
			require_once ('page_login.php');
			echo "\n";
		}
		
		// User Access to this page allowed?? - check page is set to 'require login', the user to page rights and the page's access code	
		elseif 
		( 
				$page_info['requires_login'] == "on" AND $_SESSION['authorized'] == TRUE 	
			AND ( !UserPageAccess($page_id) > 0  OR $page_info['access_code'] < $_SESSION['access'] )
		)
		
		{
			// no access allowed
			require_once ('not_authorized.php');
			echo "\n";
		}
		
		else
		{
			//	Is page active ??	-------
			if ($page_info['active'] != "on")
			{
				require_once ('page_expired.php');
			}

			else
			{
				GetModInfo ($page_id, 3, $site_theme_id);
			}
			
			echo "\n";
		}
	
	}

//=====================CENTRE OF PAGE CONTENT ENDS HERE=====================================
	
//---------Get The common BOTTOM of page code:
	require_once (PAGE_END_FILE);	
	echo "\n";
	
// 	Now flush the output buffer
	ob_end_flush();	
	

?>