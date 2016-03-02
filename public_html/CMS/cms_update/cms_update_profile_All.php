<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );
	
	$file_path_offset = '../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	require_once ('cms_update_common.php');
	require_once ('cms_update_profile_functions.php');

	
	//	set local vars - avoid errors
	if (!isset($_SESSION['update_success_msg']))
	{
		$_SESSION['update_success_msg'] = '';
	}	

	if (!isset($_SESSION['update_error_msg']))
	{
		$_SESSION['update_error_msg'] = '';
	}	
		
	if (!isset($error))
	{
		$error = '';
	}		
	
	
if (isset($_SESSION['access']) AND $_SESSION['access'] < 6 )
{		

	//	remove quotes
	foreach($_POST as $key => $value)
	{	
		$_POST[$key] = str_replace('"', '&quot;', $_POST[$key]);
		$_POST[$key] = str_replace("'", '&#39;' , $_POST[$key]);
	}	

	//	Update Profiles:
	if(isset($_POST['num_records']))
	{
		for ($count = 1; $count <= $_POST['num_records']; $count++)
		{
			//	Delete individual profile
			if (isset($_POST['submit_delete_profile_'.$count]))
			{					
				DeleteProfile($_POST['profile_id_'.$count]);					
			}
			
			//	Update ALL
			elseif ( isset($_POST['update_all']) AND isset($_POST['profile_id_'.$count]))
			{
				
				//	Delete
				if (!empty($_POST['delete']) AND in_array( $_POST['profile_id_'.$count], $_POST['delete']))
				{
					DeleteProfile($_POST['profile_id_'.$count]);			
				}
				
				//	OR Update
				else
				{
					
					$mysql_err_msg = 'Up-dating Profile info';
					
					//	ACTIVE
					// -----	check the status of 'ACTIVE' check boxes (they are not sent if not ticked)
					
					//	TICKED and sent in active array -- avoid errors by checking that array is not empty first
					if ( !empty ($_POST['active']) )
					{ 
						if (in_array( $_POST['profile_id_'.$count], $_POST['active']) ) 
						{ $active = 'on';} 
						else {$active = '';}
					}
						
					//	NOT TICKED
					else { $active = '';}
					
					$sql_statement = 'UPDATE mod_profiles SET'
					
										.' active = "'.$active.'"'
										.' WHERE profile_id = "'.$_POST['profile_id_'.$count].'"'
										.' AND mod_id = "'.$_POST['mod_id'].'"'										
										;
													
					UpdateDB ($sql_statement, $mysql_err_msg);		

					//	Display Images ?
					//	TICKED and sent in display_images array -- avoid errors by checking that array is not empty first
					if ( !empty ($_POST['display_images']) )
					{ 
						if (in_array( $_POST['profile_id_'.$count], $_POST['display_images']) ) 
						{ $display_images = 'on';} 
						else {$display_images = '';}
					}
						
					//	NOT TICKED
					else { $display_images = '';}
					
					$sql_statement = 'UPDATE mod_profiles SET'
					
										.' display_images = "'.$display_images.'"'
										.' WHERE profile_id = "'.$_POST['profile_id_'.$count].'"'
										.' AND mod_id = "'.$_POST['mod_id'].'"'
										;
													
					UpdateDB ($sql_statement, $mysql_err_msg);
					
					//	need to strip non-numbers
					$_POST['seq_'.$count] = preg_replace('/\D/', '',$_POST['seq_'.$count]);	


					$sql_statement = 'UPDATE mod_profiles SET'
						
											.'  seq = "'.$_POST['seq_'.$count].'"'
											.', role = "'.$_POST['role_'.$count].'"'
											.'  WHERE profile_id = "'.$_POST['profile_id_'.$count].'"'
											.' AND mod_id = "'.$_POST['mod_id'].'"'
											;
										
					if(!UpdateDB ($sql_statement, $mysql_err_msg))
					{
						$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
					}
					
				}				
			
			}		
			
		}	
	}
	
	//	Update "more settings"
	if (isset($_POST['update_more_settings']))
	{
		if 	(isset($_POST['use_url_alias']))
		{$use_url_alias = 'on';}
		else {$use_url_alias = '';}
		
					
		$sql_statement = 'UPDATE mod_profiles_config SET'
			
								.'  listing_name = "'.$_POST['listing_name'].'"'
								.', profile_alias = "'.$_POST['profile_alias'].'"'
								.', heading = "'.$_POST['heading'].'"'
								.', text_1 = "'.$_POST['text_1'].'"'
								.', text_2 = "'.$_POST['text_2'].'"'
								.', navbar_location = "'.$_POST['navbar_location'].'"'
								.', use_url_alias = "'.$use_url_alias.'"'

								.' WHERE mod_id = "'.$_POST['mod_id'].'"'
								;
							
		if(!UpdateDB ($sql_statement, $mysql_err_msg))
		{
			$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
		}		
	
	}
	
	//	Update "Image settings"
	if (isset($_POST['update_image_settings']))
	{
		if 	(isset($_POST['display_thumbs']))
		{$display_thumbs = 'on';}
		else {$display_thumbs = '';}
		
		if 	(isset($_POST['link_2_all_imgs']))
		{$link_2_all_imgs = 'on';}
		else {$link_2_all_imgs = '';}
		
		//	strip non numbers
		$resize_img_max_width = preg_replace('/\D/', '',$_POST['resize_img_max_width']);
		$resize_img_max_height = preg_replace('/\D/', '',$_POST['resize_img_max_height']);		
		
		$sql_statement = 'UPDATE mod_profiles_config SET'
			
								.'  all_thumbs_link = "'.$_POST['all_thumbs_link'].'"'
								.', resize_img_mode = "'.$_POST['resize_img_mode'].'"'
								.', resize_img_max_width = "'.$resize_img_max_width.'"'
								.', resize_img_max_height = "'.$resize_img_max_height.'"'
								.', display_thumbs = "'.$display_thumbs.'"'
								.', link_2_all_imgs = "'.$link_2_all_imgs.'"'

								.' WHERE mod_id = "'.$_POST['mod_id'].'"'
								;
							
		if(!UpdateDB ($sql_statement, $mysql_err_msg))
		{
			$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
		}		
	
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
	
}

else
{
	
	$_SESSION['update_error_msg'] .= ' - Insufficient Privileges to Modify Data \n';
	
}


	//	Re-Direct BACK
	header('location: '.$_POST['return_url']); 
	exit();	
	
?>