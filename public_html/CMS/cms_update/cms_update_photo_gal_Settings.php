<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );
	
	$file_path_offset = '../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	require_once ('cms_update_common.php');
	require_once ('cms_update_photo_gal_functions.php');

	//	set local vars - avoid errors
	if (!isset($_SESSION['update_success_msg']))
	{
		$_SESSION['update_success_msg'] = '';
	}	

	if (!isset($_SESSION['update_error_msg']))
	{
		$_SESSION['update_error_msg'] = '';
	}
	
	$mod_id = $_POST['mod_id'];
	
	if (isset($_SESSION['access']) AND $_SESSION['access'] < 5 )
	{	

		$error = FALSE;	

		//	Update All setings	
		if ( isset($_POST['settings_update_all']))
		{

			//	Update ALL info
			$mysql_err_msg = 'Up-dating Photo Gallery Settings';
			
			
			// Do validation
			//	remove quotes
			foreach($_POST as $key => $value)
			{	
				$_POST[$key] = str_replace('"', '&quot;', $_POST[$key]);
				$_POST[$key] = str_replace("'", '&#39;' , $_POST[$key]);
			}		

			//	check ACTIVE ticked
			if (isset($_POST['active']))
			{
				$active = 'on';
			}
			else
			{
				$active = '';
			}		
			
			//	check display_name ticked
			if (isset($_POST['display_name']))
			{
				$display_name = 'on';
			}
			else
			{
				$display_name = '';
			}				

			//	check if Gallery Type set otherwise defult to Cycle
			if(isset($_POST['gallery_type']))
			{
				$gallery_type = $_POST['gallery_type'];
			}
			else
			{
				//	Default setting
				$gallery_type = 'jquery_cycle';
			}
			
			//	check pause_on_hover ticked
			if (isset($_POST['pause_on_hover']))
			{
				$pause_on_hover = '1';
			}
			else
			{
				$pause_on_hover = '0';
			}	
		
			//	define cat_menu_type
			if (!isset($_POST['cat_menu_display']))
			{
				$cat_menu_type = 'none';
			}
			else
			{
				if(isset($_POST['cat_menu_type']))
				{
					$cat_menu_type = $_POST['cat_menu_type'];
				}
				else
				{
					//	Default setting
					$cat_menu_type = 'selectIfMobile';
				}
		
			}
			
			//	avoid errors and set to default if not set
			if (!isset($_POST['timeout']) OR $_POST['timeout'] == 0) {$_POST['timeout'] = 4000;}
			if (!isset($_POST['trans_speed']) OR $_POST['trans_speed'] == 0) {$_POST['trans_speed'] = 2000;}
			if (!isset($_POST['resize_img_max_width']) OR $_POST['resize_img_max_width'] == 0) {$_POST['resize_img_max_width'] = 1200;}
			if (!isset($_POST['resize_img_max_height']) OR $_POST['resize_img_max_height'] == 0) {$_POST['resize_img_max_height'] = 1000;}
			if (!isset($_POST['resize_img_mode'])) {$_POST['resize_img_mode'] = 5;}
			
			//	strip non numbers
			$timeout = preg_replace('/\D/', '',$_POST['timeout']);
			$trans_speed = preg_replace('/\D/', '',$_POST['trans_speed']);			
			$resize_img_max_width = preg_replace('/\D/', '',$_POST['resize_img_max_width']);
			$resize_img_max_height = preg_replace('/\D/', '',$_POST['resize_img_max_height']);
			
			
			//	Get all mod_IDs for synching
			$sync_mod_ids_array = GetSyncModIDs($mod_id);
			
			$mod_ids_str = '';
			//while ($syncs = mysql_fetch_array ($sync_result))
			foreach ($sync_mod_ids_array as $sync_id)
			{
				if ($sync_id != $_POST['mod_id'])
				{
					$mod_ids_str .= ' OR mod_id = '.$sync_id;				
				}

			}
				
			$sql_statement = 'UPDATE mod_photo_gal_settings SET'
													
									.'  gallery_type = "'.$gallery_type.'"'
									.', display_name = "'.$display_name.'"'
									.', cat_menu_type = "'.$cat_menu_type.'"'

									.', trans_fx = "'.$_POST['trans_fx'].'"'
									.', trans_speed = "'.$trans_speed.'"'
									.', timeout = "'.$timeout.'"'
									.', pause_on_hover = "'.$pause_on_hover.'"'
									
									.', resize_img_mode = "'.$_POST['resize_img_mode'].'"'
									.', resize_img_max_width = "'.$resize_img_max_width.'"'
									.', resize_img_max_height = "'.$resize_img_max_height.'"'

									.' WHERE mod_id = '.$_POST['mod_id']
									.  $mod_ids_str
									;
								
			if(!UpdateDB ($sql_statement, $mysql_err_msg))
			{
				$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
			}			
			
			//	Update Gallery Name (keep name unique to distinghish)
			$sql_statement = 'UPDATE mod_photo_gal_settings SET  gallery_name = "'.$_POST['gallery_name'].'" WHERE mod_id = '.$_POST['mod_id'];
			if(!UpdateDB ($sql_statement, $mysql_err_msg))
			{
				$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
			}	
			
			//	Update modules ACTIVE statis
			$sql_statement = 'UPDATE modules SET active = "'.$active.'" WHERE mod_id = "'.$_POST['mod_id'].'"';		
			if(!UpdateDB ($sql_statement, $mysql_err_msg))
			{
				$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
			}			

		}
		
	}

	else
	{		
		$_SESSION['update_error_msg'] .= '- Insufficient Privileges to Modify Data \n';		
	}

	//	update the .htacces File
	$error = UpdateHtaccesFile (HOME_PAGE_ID);

	//	update sitemap.xml file
	$error .= UpdateSiteMapFile (HOME_PAGE_ID);

	//	check for errors and print MSG
	if($error == FALSE)
	{
		$_SESSION['update_success_msg'] .= "( Update Succesfull ) \n";
	}
	else
	{
		$_SESSION['update_error_msg'] .= $error;
	}	
	

	//	Re-Direct BACK
	header('location: '.$_POST['return_url']); 
	exit();	
	
	
	
?>