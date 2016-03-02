<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );
	
	$file_path_offset = '../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	require_once ('cms_update_common.php');
	require_once ('cms_update_profile_functions.php');

	
	$remove_tag_list = array ('<span' => '</span>');
	
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
		
		//	read from db to get Profile Config info
		$mysql_err_msg = 'Profile information unavailable';	
		$sql_statement = 'SELECT' 
		
							.'  profile_alias'
							.', resize_img_mode'
							.', resize_img_max_width'
							.', resize_img_max_height'
		
							.' FROM mod_profiles_config WHERE mod_id = "'.$_REQUEST['mod_id'].'"';
				
		$profiles_settings = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));		

		$profile_alias = $profiles_settings['profile_alias'];		
		
		//	remove quotes
		foreach($_POST as $key => $value)
		{				
			$_POST[$key] = str_replace("'", '&#39;' , $_POST[$key]);
			if ($key != 'long_desc')
			{
				$_POST[$key] = str_replace('"', '&quot;', $_POST[$key]);
			}
		}		
	
		//	Check Boxes:
		if (isset($_POST['active']) AND $_POST['active'] == 'on')
		{$active = 'on';}
		else	{$active = '';}

		if (isset($_POST['display_contact_info']) AND $_POST['display_contact_info'] == 'on')
		{$display_contact_info = 'on';}
		else	{$display_contact_info = '';}

		if (isset($_POST['display_profile_img']) AND $_POST['display_profile_img'] == 'on')
		{$display_profile_img = 'on';}
		else	{$display_profile_img = '';}			
		
		if (isset($_POST['profile_as_primary']) AND $_POST['profile_as_primary'] == 'on')
		{$profile_as_primary = 'on';}
		else	{$profile_as_primary = '';}	
		
		if (isset($_POST['display_images']) AND $_POST['display_images'] == 'on' OR $_POST['update_action'] == 'new')
		{$display_images = 'on';}
		else	{$display_images = '';}

		if (isset($_POST['can_enlarge_imgs']) AND $_POST['can_enlarge_imgs'] == 'on' OR $_POST['update_action'] == 'new')
		{$can_enlarge_imgs = 'on';}
		else	{$can_enlarge_imgs = '';}

		if (isset($_POST['link_email']) AND $_POST['link_email'] != '')
		{$link_email = $_POST['link_email'];}
		else	{$link_email = '';}
		
		if (isset($_POST['primary_image_id']))
		{
			$primary_image_id = $_POST['primary_image_id'];
		}
		else{$primary_image_id = '0';}		
		
		
		if ($_POST['url_alias'])
		{
			$url_alias = $_POST['url_alias'];
		}
		else
		{
			$url_alias = $_POST['profile_name'];
		}
		
		//	trim and strip URL
		$url_alias = str_replace(' ', '_', trim($url_alias));
		$url_alias = preg_replace('/[^A-Za-z0-9_-]+/i', '',$url_alias);	// remove all but alphanums and underscores
		
		$website_url = str_replace(' ', '', trim($_POST['website_url']));
		
		//	Long Desc.
		$long_desc = $_POST['long_desc'];
		
		if ($long_desc == 'click here to add text...'){$long_desc = '';}

		$long_desc = CleanHtml($long_desc);
		
		//	need to remove any extra "</span>" tags to prevent parts of the text mod become un-editable		
		$long_desc = remove_end_tags($long_desc, $remove_tag_list);

		//	in IE "null" is displayed if string empty
		if ($long_desc == 'null'){ $long_desc = '';}			
		
		
		
		//	Delete this profile
		if ( isset($_POST['submit_delete_profile']))
		{
			DeleteProfile ($_POST['profile_id']);
		}
		
		//	Update if form sbmited
		if ( isset($_POST['update_all']))
		{
			//	update only if name entered	
			if ($_POST['profile_name'])
			{
					//	Update ALL info
				$mysql_err_msg = 'Up-dating '.$profile_alias.' info';

				//	New OR Cloned Profile
				if(isset($_POST['update_action']) AND $_POST['update_action'] == 'new')		
				{
					//	get last seq used
					$sql_statement = 'SELECT seq FROM mod_profiles WHERE mod_id = "'.$_REQUEST['mod_id'].'"ORDER BY seq DESC';

					$seq_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));				
					$last_profile_seq = $seq_info['seq'];
					$new_seq = $last_profile_seq + 10;
					
					$sql_1 = 'INSERT INTO';
					$sql_2 = ', seq = "' .$new_seq. '"';	//	next seq
					$sql_3 = '';				
				}
				
				//	Update existing Profile
				else
				{			
					$sql_1 = 'UPDATE';
					$sql_2 = '';
					$sql_3 = ' WHERE profile_id = "'.$_POST['profile_id'].'"';			
				}	

				$sql_statement = $sql_1.' mod_profiles SET'
				
										.'  profile_name = "'.$_POST['profile_name'].'"'
										.', mod_id = "'.$_REQUEST['mod_id'].'"'
										.', url_alias = "'.$url_alias.'"'
										.', role = "'.$_POST['role'].'"'
										.', active = "'.$active.'"'
										.', display_contact_info = "'.$display_contact_info.'"'
										.', phone_1 = "'.$_POST['phone_1'].'"'
										.', phone_2 = "'.$_POST['phone_2'].'"'
										.', phone_3 = "'.$_POST['phone_3'].'"'
										.', fax = "'.$_POST['fax'].'"'
										.', email = "'.$_POST['email'].'"'
										.', display_email_as = "'.$_POST['display_email_as'].'"'
										.', link_email = "'.$link_email.'"'
										.', website_url = "'.$website_url.'"'
										.', website_display = "'.$_POST['website_display'].'"'
										.', primary_image_id = "'.$primary_image_id.'"'
										.', display_images = "'.$display_images.'"'
										.', display_profile_img = "'.$display_profile_img.'"'
										.', profile_as_primary = "'.$profile_as_primary.'"'
										.', can_enlarge_imgs = "'.$can_enlarge_imgs.'"'
										.', long_desc = "'.$long_desc.'"'
					
										.   $sql_2
										.   $sql_3	
										;

				if(!ReadDB ($sql_statement, $mysql_err_msg))	//	Use 'ReadDB' instead of 'UpdateDB' to avoid conflicting the following 
																//	mysql_insert_id
				{
					$_SESSION['update_error_msg'] = ' - Database Error: Could not Update / Add '.$profile_alias.'' .' \n';
				}

				$new_profile_id = mysql_insert_id();	
				
				if(isset($_POST['update_action']) AND $_POST['update_action'] == 'new')
				{
					$profile_id = $new_profile_id;
				}
				else
				{
					$profile_id = $_POST['profile_id'];
				}

				
					
				//	Profile image upload
				if ($_FILES['upload_profile_image']['name'] != '')
				{
					$error = FALSE;

					if ($_FILES['upload_profile_image']['error'] == 0 AND $_FILES['upload_profile_image']['size'] > MAX_FILE_SIZE_CMS)
					{
						$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_profile_image']['name']
						.'\' is larger than ' . MAX_FILE_SIZE_CMS / 1000 .'kB and was not uploaded. \n';
						$error = TRUE;
					}
					//	check file type
					if ($_FILES['upload_profile_image']['type'] == 'image/gif')
					{$file_type = 'gif';}
					elseif ($_FILES['upload_profile_image']['type'] == 'image/jpeg' OR $_FILES['upload_profile_image']['type'] == 'image/pjpeg')
					{$file_type = 'jpg';}	
					elseif ($_FILES['upload_profile_image']['type'] == 'image/png' OR $_FILES['upload_profile_image']['type'] == 'image/x-png')
					{$file_type = 'png';}
					
					else
					{
						$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_profile_image']['name']
						.'\' is NOT the correct file type and was not uploaded. \n';
						$error = TRUE;
					}	

					//	check for errors
					if ($_FILES['upload_profile_image']['error'] > 0)
					{
						$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_profile_image']['name']
						.'\' was not uploaded due to the following error: '.FileUploadErrorMessage($_FILES['upload_profile_image']['error']).'. \n';
						$error = TRUE;
					}
					if ($error == FALSE)
					{

						
						//	get new filename
						$new_filename = 'profileID_'.$profile_id . '.' . $file_type;
						
						//	update db
						$sql_statement = 'UPDATE mod_profiles SET' 
						
															.' profile_img_file = "'.$new_filename.'"'
															.' WHERE profile_id = "'.$profile_id.'"'
															;
						UpdateDB ($sql_statement, $mysql_err_msg);	


						$upload_path = $file_path_offset.'_images_user/profile/';										
						$image_details = getimagesize($_FILES['upload_profile_image']['tmp_name']);
						
						$width = $image_details[0]; 
						$height = $image_details[0]; 

						//	DO RESIZE FILE --- but only if set to and image exceeds dimentions				
						if
						(
								$profiles_settings['resize_img_mode'] 
							AND ($width > $profiles_settings['resize_img_max_width'] OR $height > $profiles_settings['resize_img_max_height'])
						)
						{		
							ResizeImages
							( 
								  $_FILES['upload_profile_image']['tmp_name']
								, $upload_path.$new_filename
								, $file_type
								, $profiles_settings['resize_img_mode']
								, $profiles_settings['resize_img_max_width']
								, $profiles_settings['resize_img_max_height']
								, $reporting = 0
							);				
						}

						else
						{
					
							//	move file					
							if (!move_uploaded_file($_FILES['upload_profile_image']['tmp_name'], $upload_path.$new_filename))	 //	failed to upload
							{
								$_SESSION['update_error_msg'] .= '- The File: '.$_FILES['upload_profile_image']['name']
								.' was not uploaded due to the following error: '
								.FileUploadErrorMessage($_FILES['upload_profile_image']['error']).'.\n';
							}
			
							else {@chmod( $upload_path.$new_filename, 0666);}	// 	Set Rights To Uploaded File}				
						
						}
						
						$_SESSION['update_success_msg'] .= 'Image Updated \n';
						
					}
						
				}
				
				
				//	(Update profile Image order AND Caption) OR delete image: (if there are images set)
				if (isset($_POST['image_pos_array']) AND (count($_POST['image_pos_array']) > 0 OR $_POST['image_pos_array'] != ''))	
				{
					$image_pos_array = array_flip (explode ( ',' , $_POST['image_pos_array']));		
					foreach ($image_pos_array as $image_id => $seq )
					{
						$seq = $seq + 1;
						if (!isset($_POST['delete_image_'.$image_id]))
						{
							$img_caption = strip_tags($_POST['img_caption_'.$image_id], '<strong><em><br><br/>');
							
							//	Update profile Image order and Caption:
							$sql_statement = 'UPDATE mod_profile_images SET'
							
														.'  seq = '. $seq
														.', img_caption = "'.$img_caption.'"'
														.'  WHERE image_id = '.$image_id
														;
							
							UpdateDB ($sql_statement, $mysql_err_msg);
						}
						
						else
						{
							DeleteProfileImage($image_id);
						}
																					
					}
					
				}
				
			}

			else
			{
				$_SESSION['update_error_msg'] .= ' - You must specify a Profile Name \n';
			}

			

				
		}
		
		//	images upload - submit_upload_profile_images
		elseif ( isset($_POST['submit_upload_profile_images']) AND $_FILES['upload_profile_images']['name'][0] != '')
		{
			

			//	determin next file name
			$mysql_err_msg = 'unable to get '.$profile_alias.' Image info for uploading new image file';
			$sql_statement = 'SELECT image_file_name '

										.' FROM mod_profile_images'
										.' ORDER BY image_file_name DESC'
										;
									
			$filename_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			$last_filename = $filename_info['image_file_name'];	
			$last_filename = substr($last_filename, 0, -4);	// remove extension
			
			//	determin next seq
			$sql_statement = 'SELECT seq '

										.' FROM mod_profile_images'
										.' WHERE profile_id = "'.$_POST['profile_id'].'"'
										.' ORDER BY seq DESC'
										;
								
			$seq_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			$last_seq = $seq_info['seq'];	
			
			for ($i = 0; $i < count($_FILES['upload_profile_images']['name']); $i++)
			{
				$error = FALSE;

				if ($_FILES['upload_profile_images']['error'][$i] == 0 AND $_FILES['upload_profile_images']['size'][$i] > MAX_FILE_SIZE_CMS)
				{
					$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_profile_images']['name'][$i]
					.'\' is larger than ' . MAX_FILE_SIZE_CMS / 1000 .'kB and was not uploaded. \n';
					$error = TRUE;
				}
				//	check file type
				if ($_FILES['upload_profile_images']['type'][$i] == 'image/gif')
				{$file_type = 'gif';}
				
				elseif 
				(
						$_FILES['upload_profile_images']['type'][$i] == 'image/jpeg' 
					 OR $_FILES['upload_profile_images']['type'][$i] == 'image/pjpeg'
				)
				{$file_type = 'jpg';}
				
				elseif 
				(
						$_FILES['upload_profile_images']['type'][$i] == 'image/png' 
					 OR $_FILES['upload_profile_images']['type'][$i] == 'image/x-png'
				)
				{$file_type = 'png';}
				
				else
				{
					$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_profile_images']['name'][$i]
					.'\' is NOT the correct file type and was not uploaded. \n';
					$error = TRUE;
				}
				
				//	check for errors
				if ($_FILES['upload_profile_images']['error'][$i] > 0)
				{
					$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_profile_images']['name'][$i]
					.'\' was not uploaded due to the following error: '.FileUploadErrorMessage($_FILES['upload_profile_images']['error'][$i]).'. \n';
					$error = TRUE;
				}
				
				if ($error == FALSE)
				{
					
					//	Do db updates:

					$next_seq = $last_seq + 1+ $i;
					$mysql_err_msg = 'unable to update '.$profile_alias.' Image file';
					$sql_statement = 'INSERT INTO mod_profile_images SET' 
					
														.'  profile_id = '.$_POST['profile_id']
														.' ,mod_id = '.$_REQUEST['mod_id']
														.' ,active = "on"'
														.' ,seq = "'.$next_seq.'"'
														;
					ReadDB ($sql_statement, $mysql_err_msg);	//	Use 'ReadDB' instead of 'UpdateDB' to avoid conflicting the following 
																//	mysql_insert_id

					$last_insert_id = mysql_insert_id();
					
					//	get new filename
					$new_filename = $last_insert_id . '.' . $file_type;
					
					$sql_statement = 'UPDATE mod_profile_images SET' 
					
														.' image_file_name = "'.$new_filename.'"'
														.' WHERE image_id = "'.$last_insert_id.'"'
														;
					UpdateDB ($sql_statement, $mysql_err_msg);
					
					$upload_path = $file_path_offset.'_images_user/profile/';
					
					
					$image_details = getimagesize($_FILES['upload_profile_images']['tmp_name'][$i]);
					
					$width = $image_details[0]; 
					$height = $image_details[0]; 
					
			
					//	DO RESIZE FILE --- but only if set to and image exceeds dimentions				
					if
					(
							$profiles_settings['resize_img_mode'] 
						AND ($width > $profiles_settings['resize_img_max_width'] OR $height > $profiles_settings['resize_img_max_height'])
					)
					{		
						ResizeImages
						( 
							  $_FILES['upload_profile_images']['tmp_name'][$i]
							, $upload_path.$new_filename
							, $file_type
							, $profiles_settings['resize_img_mode']
							, $profiles_settings['resize_img_max_width']
							, $profiles_settings['resize_img_max_height']
							, $reporting = 0
						);				
					}
					
					else
					{
				
						//	move file					
						if (!move_uploaded_file($_FILES['upload_profile_images']['tmp_name'][$i], $upload_path.$new_filename))//failed to upload
						{
							$_SESSION['update_error_msg'] .= '- The File: '.$_FILES['upload_profile_images']['name'][$i]
							.' was not uploaded due to the following error: '
							.FileUploadErrorMessage($_FILES['upload_profile_images']['error'][$i]).'.\n';
						}
		
						else {@chmod( $upload_path.$new_filename, 0666);}	// 	Set Rights To Uploaded File}				
					
					}


					//	get the first uploaded image to use if no primary image set
					if($i == 0)
					{
						$new_primary_image_id = $last_insert_id;						
					}			

				
					$_SESSION['update_success_msg'] = "Update Succesfull";
					
				}
							

			}

			//	if no existing Primary image id set, use first uploaded image
			if ($primary_image_id == NULL OR $primary_image_id == 0)
			{							

				$sql_statement = 'UPDATE mod_profiles SET primary_image_id = "'.$new_primary_image_id.'"'

									.' WHERE profile_id = "'.$_POST['profile_id'].'"';
											
				UpdateDB ($sql_statement, $mysql_err_msg);											
			}	

			$return_tab = 4;	//	hard-coded

		}
	
		elseif ( isset($_POST['delete_profile_image_profile']))
		{
			
			
			$exts = array('jpg','png','gif');
			foreach($exts as $ext)
			{
				//	remove image file..
				$img_file_path = '../../_images_user/profile/profileID_'.$_POST['profile_id'].'.'.$ext;
				
				
				if (file_exists($img_file_path))
				{	
					if(!unlink($img_file_path))
					{
						$error = TRUE;
					}		
				}		
			}

			
			//	check for errors and print MSG
			if($error == FALSE)
			{
				$_SESSION['update_success_msg'] .= ' - Image Succesfully deleted \n';
			}
			else
			{
				$_SESSION['update_error_msg'] .= ' - An Error occured \n';
			}	
			
		}
	
	
		//	Delete single image
		elseif (isset($_POST['image_pos_array']) AND (count($_POST['image_pos_array']) > 0 OR $_POST['image_pos_array'] != ''))	
		{
			$image_pos_array = explode ( ',' , $_POST['image_pos_array']);			
			
			foreach ($image_pos_array as $image_id )
			{
				if (isset($_POST['delete_image_'.$image_id]))
				{
					DeleteProfileImage($image_id);
					
					$return_tab = 4;	//	hard-coded
				}
				
			}
	
		}		

		

		
		//	update the .htacces File
		$error = UpdateHtaccesFile (HOME_PAGE_ID);

		//	update sitemap.xml file
		$error .= UpdateSiteMapFile (HOME_PAGE_ID);			
		
	
	}

	else
	{		
		$_SESSION['update_error_msg'] .= ' - Insufficient Privileges to Modify Data \n';		
	}

	
	
	//	Get profile ID to return to after update	
	if(isset($_POST['update_action']) AND $_POST['update_action'] == 'new' AND isset($new_profile_id))		
	{
		$return_profile_id = $new_profile_id;	
	}
	
	elseif ( isset($_POST['submit_delete_profile']))
	{
		$return_profile_id = NULL;
	}		
	
	
	else
	{
		$return_profile_id = $_POST['profile_id'];
	}	
	
	//	return to correct tab: if images are uploaded, $return_tab is set to 4, if not get it from POST
	if (!isset($return_tab))
	{
		$return_tab = $_POST['tab'];	
	}

	
	//	Re-Direct BACK
	$return_url = $_POST['return_url'].'&profile_id='.$return_profile_id . '&tab=' . $return_tab;	
	header('location: '.$return_url); 
	exit();	
	
?>