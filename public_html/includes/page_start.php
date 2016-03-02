<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

//------ -- START BODY-------------------------------------------------------------------------------------------------
	if (!isset($body_onload_info))	{ $body_onload_info = '';}	//	avoid error notice
	
	$sessionCMS_authorized = FALSE;
	if (isset($_SESSION['CMS_authorized'])) {$sessionCMS_authorized = $_SESSION['CMS_authorized'];}
	
	echo '<body class="MainSite" id="MainSite_'.$page_id.'" '.$body_onload_info.' >'." \n"; 	
	echo "\n";

	//	attempt to load the CMS
	if (isset($_SESSION['load_admin']) AND $_SESSION['load_admin'] == TRUE)
	{

		//	set HTTPS   if not already   (SHOP_CHECKOUT_WITH_HTTPS  == 1  is  https mode on)
		if(CMS_USE_HTTPS AND !isset($_SERVER['HTTPS']))
		{
			header('location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
			exit();		
		}	
		
		
		
		//if ( $_SESSION['CMS_authorized'] != TRUE )
		if ( $sessionCMS_authorized != TRUE )
		{
			//	Do CMS log-in
			include_once ('CMS/cms_login.php');	
		}
		else
		{
			//	Put in CMS MODE
			$_SESSION['CMS_mode'] = TRUE;
			include_once ('CMS/cms_panel.php');	
		}

	}	
		
	if 
	(
			isset($_SESSION['CMS_authorized']) AND $_SESSION['CMS_authorized'] == TRUE 
		AND isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE 
	)
	{
		//	Put in CMS MODE
		include_once ('CMS/cms_panel.php');	
		
		//	Does User have edit access to this page ??	-------
		if (UserPageAccess($page_id) < 2)
		{
			require_once ('CMS/cms_no_edit_access_alert.php');	
		}		
			
		//	Is page active ??	-------
		if ($page_info['active'] != "on")
		{
			require_once ('CMS/cms_inactive_page_alert.php');
		}		
	}	

	else
	{
		//	Redirect to Shutdown page if site is shutdown
		if (SITE_SHUTDOWN > 0)
		{
			//header("location: shutdown.php"); 
			include_once ('shutdown.php');
			exit();
		}	
	}

		
		//--------START BOUNDARY-----------------------------------------------------------------
	
		echo TAB_1.'<div class="Container" id="Container_'.$page_id.'" >'." \n";		
		echo "\n";

			echo TAB_2.'<div class="HiddenHeading" id="HiddenHeading_'.$page_id.'" >'." \n";
				echo TAB_3.'<h1 class="HiddenHeading" >'.HIDDEN_HEADING.'</h1>'."\n";
			echo TAB_2.'</div>'." \n";	
			echo "\n";		
			
		//-------Exrea wrapper if required-----------------------
			echo TAB_2.'<div class="Wrapper1" id="Wrapper1_'.$page_id.'" >'." \n";
			echo "\n";
					
				echo TAB_3.'<div class="WrapperTop" id="WrapperTop_'.$page_id.'" >'." \n";
				echo "\n";	
				
				//-----------BANNER--------------------------------------------------------------
				if ($page_info['banner_active'] == "on" )
				{		
					echo "\n";			
					echo TAB_4.'<!--	START '.DIV_1_NAME.' code 	-->'."\n";
					echo "\n";
					
					if ( DIV_1_HAS_UNIQUE_ID == 1 )
					{ $page_id_tag = '_'.$page_id;}
					else { $page_id_tag = '';}
					
					echo TAB_4.'<div class="'.DIV_1_NAME.' sortable sortable1" id="'.DIV_1_NAME.$page_id_tag.'" >'." \n";
					echo "\n";
					
						GetModInfo ($page_id, 1, $site_theme_id);

					echo TAB_4.'</div>'."\n";
										
					echo "\n";		
					echo TAB_4.'<!--	END '.DIV_1_NAME.' code 	-->'."\n";	
					echo "\n";		
				}
				
				echo TAB_3.'</div>'."\n";	
				echo "\n";	
			
			//-------Exrea wrapper if required-----------------------
				echo TAB_3.'<div class="Wrapper2" id="Wrapper2_'.$page_id.'" >'." \n";				
				echo "\n";
			
				//-------Exrea wrapper if required-----------------------
					echo TAB_4.'<div class="WrapperMenuTop" id="WrapperMenuTop_'.$page_id.'" >'." \n";				
					echo "\n";				
					
						//----------Menu Bar------------------------------------------------------------
						if ($page_info['menu_top_active'] == 'on')
						{				
							include_once ('menu_top.php');			
						}
						
						elseif ($page_info['menu_top_active'] == "select")
						{
							include_once ('menu_top_select.php');	
						}
						
						elseif ($page_info['menu_top_active'] == "selectIfMobile")
						{
							
							//if (CheckUserAgent ('mobile'))
							if( $mobile_detect->isMobile() && !$mobile_detect->isTablet() )
							{
								include_once ('menu_top_select.php');	
							}
							else
							{
								include_once ('menu_top.php');	
							}								

						}
					
						//----------Bread Crumb------------------------------------------------------------
						if ($page_info['bread_crumb_active'] == "on")
						{				
							include_once ('bread_crumb.php');		
						}			

					echo TAB_4.'</div>		<!--	End "WrapperMenuTop" Div	-->'."\n";
					echo "\n";
					
				//-------Exrea wrapper if required-----------------------
					echo TAB_4.'<div class="Wrapper3" id="Wrapper3_'.$page_id.'" >'." \n";				
					echo "\n";
					
					//----------Left Column------------------------------------------------------------
					if ($page_info['side_1_active'] == "on" OR $page_info['menu_side_active'])
					{
						echo TAB_5.'<!--	START '.DIV_2_NAME.' code 	-->'."\n";
						echo "\n";

						if ( DIV_2_HAS_UNIQUE_ID == 1 )
						{ $page_id_tag = '_'.$page_id;}
						else { $page_id_tag = '';}
						
						echo TAB_5.'<div class="'.DIV_2_NAME.' sortable sortable2" id="'.DIV_2_NAME.$page_id_tag.'" >'." \n";
						
							if ($page_info['menu_side_active'] == 'on')
							{				
								include_once ('menu_side.php');			
							}
							
							elseif ($page_info['menu_side_active'] == "select")
							{
								include_once ('menu_side_select.php');	
							}
							
							elseif ($page_info['menu_side_active'] == "selectIfMobile")
							{
								
								//if (CheckUserAgent ('mobile'))
								if( $mobile_detect->isMobile() && !$mobile_detect->isTablet() )
								{
									include_once ('menu_side_select.php');	
								}
								else
								{
									include_once ('menu_side.php');	
								}								

							}
							
							if ($page_info['side_1_active'] == "on" )
							{	
								//	Module info
								GetModInfo ($page_id, 2, $site_theme_id);
							}
								
						echo TAB_5.'</div>'." \n";
						
						echo "\n";		
						echo TAB_5.'<!--	END '.DIV_2_NAME.' code 	-->'."\n";
						echo "\n";					
					}
					
					//----------START MAINPAGE container--------------------------------------------
						echo "\n";					
						echo TAB_5.'<!--	START Main Content ('.DIV_3_NAME.') code 	-->'."\n";
						echo "\n";
						
						if ( DIV_3_HAS_UNIQUE_ID == 1 )
						{ $page_id_tag = '_'.$page_id;}
						else { $page_id_tag = '';}
						
						echo TAB_5.'<div class="'.DIV_3_NAME.' sortable sortable3" id="'.DIV_3_NAME.$page_id_tag.'">'."\n";	
						
						//---------Display Page Name as Page Heading (auto_heading)------------------------------
						switch ($page_info['auto_heading'])
						{
							case 'name':
							if ($page_info['page_name'] != NULL AND $page_info['page_name'] != '')
							{
								echo TAB_6.'<h1 class="AutoPageHeading" id="AutoPageHeading_'.$page_id.'">'.$page_info['page_name'].'</h1>'."\n";
								break;
							}
							
							case 'menu':
							if ($page_info['menu_text'] != NULL AND $page_info['menu_text'] != '')
							{
								echo TAB_6.'<h1 class="AutoPageHeading" id="AutoPageHeading_'.$page_id.'">'.$page_info['menu_text'].'</h1>'."\n";
								break;
							}							
						}
						
			
?>