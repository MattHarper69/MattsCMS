<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
//------ -- START BODY-------------------------------------------------------------------------------------------------

	echo '<body class="CMS" id="CMS_'.$cms_page_info['page_id'].'">'." \n"; 	
	echo "\n";

//--------START BOUNDARY-----------------------------------------------------------------
	echo TAB_1.'<div class="CMS_Container" id="CMS_Container_'.$cms_page_info['page_id'].'">'." \n";
		
		//-----------BANNER--------------------------------------------------------------
		include_once ('cms_banner.php');
			
		//----------Menu Bar (Top)------------------------------------------------------------
		require_once ('cms_menu_top.php');	
		
		//----------Bread Crumb------------------------------------------------------------		
		include_once ('cms_bread_crumb.php');	
		
		//----------Status Bar------------------------------------------------------------		
		include_once ('cms_status_bar.php');	
		
		//------------Wrapper----------------------------------------------------------------------
		echo TAB_2.'<div class="CMS_Wrapper" id="CMS_Wrapper_'.$cms_page_info['page_id'].'" >'." \n";

		//----------Menu Side-----------------------------------------------------------
		require_once ('cms_menu_side.php');	
		
		//----------START MAINPAGE container--------------------------------------------
		echo TAB_3.'<div class="CMS_CentreColumn" id="CMS_CentreColumn_'.$cms_page_info['page_id'].'" >'." \n";
			
		
		//========================  Start Unique Page Content  ======================================================

	
?>