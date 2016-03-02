<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	function DeleteProfile($profile_id)
	{
		
		$error = FALSE;
		
		if(isset($_SESSION['update_success_msg']))
		{
			$update_success_msg = $_SESSION['update_success_msg'];
		}
		else {$update_success_msg = '';}

		if(isset($_SESSION['update_error_msg']))
		{
			$update_error_msg = $_SESSION['update_error_msg'];
		}
		else {$update_error_msg = '';}

		// get profile name	
		$mysql_err_msg = 'can not get profile info for deletion';
		$sql_statement = 'SELECT profile_name FROM mod_profiles WHERE mod_profiles.profile_id = "'.$profile_id.'"';
		$name_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
		$profile_name = $name_info['profile_name'];
		
		// create an array of image_ids to delete all associate images	
		$sql_statement = 'SELECT image_file_name FROM mod_profiles, mod_profile_images'
		
												.' WHERE mod_profiles.profile_id = "'.$profile_id.'"'
												.' AND mod_profiles.profile_id = mod_profile_images.profile_id'
												;
												
//echo $sql_statement;										
		$image_result = ReadDB ($sql_statement, $mysql_err_msg);
		
		$image_files_array = array();
		while ($image_info = mysql_fetch_array($image_result))
		{		
			$image_files_array[] = $image_info['image_file_name'];
		}

		//	delete from profiles table
		$mysql_err_msg = 'deleting from profile table';
		$sql_statement = 'DELETE FROM mod_profiles WHERE profile_id = "'.$profile_id.'"';
		if(!UpdateDB ($sql_statement, $mysql_err_msg)) { $error = TRUE;}
					
		
		//	delete from mod_profile_images table
		$mysql_err_msg = 'deleting from image table';
		$sql_statement = 'DELETE FROM mod_profile_images WHERE profile_id = "'.$profile_id.'"';
		if(!UpdateDB ($sql_statement, $mysql_err_msg)) { $error = TRUE;}		
			
		//	Delete Image Files
		if (count($image_files_array) > 1)
		{
			foreach ($image_files_array as $image_file_name)
			{
				$img_file_path = '../../_images_user/profile/'.$image_file_name;
				if (file_exists($img_file_path) AND $image_file_name != '')
				{	
					if(!unlink($img_file_path))
					{
						$_SESSION['update_error_msg'] .= ' - Image file for; '.$profile_name.' ('.$image_file_name.') could not be deleted ! \n';
					}
					
				}
				
			}
			
		}

		
		//	check for errors and print MSG
		if($error == FALSE)
		{
			$update_success_msg .= ' - profile: '.$profile_name.' Succesfully deleted \n';
			$_SESSION['update_success_msg'] = $update_success_msg;
		}
		else
		{
			$update_error_msg .= ' - A Database Error occured \n';
			$_SESSION['update_error_msg'] = $update_error_msg;
		}
		
	}


	function DeleteProfileImage($image_id)
	{
		//	get profile image details
		$mysql_err_msg = 'unable to get profile Image info for deletion';
		$sql_statement = 'SELECT image_file_name, mod_profiles.profile_id, primary_image_id'

									.' FROM mod_profile_images, mod_profiles'
									.' WHERE image_id = "'.$image_id.'"'
									.' AND mod_profiles.profile_id = mod_profile_images.profile_id'
									;
		
		$image_file_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
		$image_file_name = $image_file_info['image_file_name'];
		$profile_id = $image_file_info['profile_id'];
		$primary_image_id = $image_file_info['primary_image_id'];

		
		//	are we deleting the primary image ?
		if ($image_id == $primary_image_id)
		{
			//	Get next image in line to use as primary
			$sql_statement = 'SELECT image_id FROM mod_profile_images'			
			
											.' WHERE image_id != "'.$image_id.'"'
											.' AND profile_id = "'.$profile_id.'"'
											.' ORDER BY seq'
											;

			$new_primary_image_id = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			$new_primary_image_id = $new_primary_image_id['image_id'];
			
			//	if no image found, default to 1
			if($new_primary_image_id == '') 
			{$new_primary_image_id = 0;}
			
			$sql_statement = 'UPDATE mod_profiles SET primary_image_id = "'.$new_primary_image_id.'" WHERE profile_id = "'.$profile_id.'"';
											
			UpdateDB ($sql_statement, $mysql_err_msg);	
			
		}
					
		//	remove entry from db
		$mysql_err_msg = 'unable to DELETE record of profile Image';
		$sql_statement = 'DELETE FROM mod_profile_images WHERE image_id = "'.$image_id.'"';				
		
		UpdateDB ($sql_statement, $mysql_err_msg);
		
		
		//	now remove file..
		$img_file_path = '../../_images_user/profile/'.$image_file_name;
		if (file_exists($img_file_path) AND $image_file_name != '')
		{	
			unlink($img_file_path);						
		}			

	}

	
?>