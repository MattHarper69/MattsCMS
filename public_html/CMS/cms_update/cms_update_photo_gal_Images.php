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

	
	$mysql_err_msg = 'Up-dating Image info';
	
	//	Upload Images	
	if ( isset($_POST['submit_upload_gal_images']) AND $_FILES['upload_photo_gal_images']['name'][0] != '')
	{
			
		//	determin next seq
		$sql_statement = 'SELECT seq '

									.' FROM mod_photo_gal_pics'
									.' WHERE cat_id = "'.$_POST['cat_id'].'"'
									.' ORDER BY seq DESC'
									;
							
		$seq_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
		$last_seq = $seq_info['seq'];


		//	cycle thru all uploaded images
		for ($i = 0; $i < count($_FILES['upload_photo_gal_images']['name']); $i++)
		{
			$error = FALSE;

			if ($_FILES['upload_photo_gal_images']['error'][$i] == 0 AND $_FILES['upload_photo_gal_images']['size'][$i] > MAX_FILE_SIZE_CMS)
			{
				$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_photo_gal_images']['name'][$i]
				.'\' is larger than ' . MAX_FILE_SIZE_CMS / 1000 .'kB and was not uploaded. \n';
				$error = TRUE;
			}
			
				
			//	check file type
			if ($_FILES['upload_photo_gal_images']['type'][$i] == 'image/gif')
			{$file_type = 'gif';}
			elseif ($_FILES['upload_photo_gal_images']['type'][$i] == 'image/jpeg' 
				 OR $_FILES['upload_photo_gal_images']['type'][$i] == 'image/pjpeg')
			{$file_type = 'jpg';}	
			elseif ($_FILES['upload_photo_gal_images']['type'][$i] == 'image/png' 
				 OR $_FILES['upload_photo_gal_images']['type'][$i] == 'image/x-png')
			{$file_type = 'png';}
			
			else
			{
				$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_photo_gal_images']['name'][$i]
				.'\' is NOT the correct file type and was not uploaded. \n';
				$error = TRUE;
			}
			
			//	check for errors
			if ($_FILES['upload_photo_gal_images']['error'][$i] > 0)
			{
				$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_photo_gal_images']['name'][$i]
				.'\' was not uploaded due to the following error: '.FileUploadErrorMessage($_FILES['upload_photo_gal_images']['error'][$i]).'. \n';
				$error = TRUE;
			}
		
			if ($error == FALSE)
			{
				
				//	Do db updates:

				$next_seq = $last_seq + 1+ $i;
				$mysql_err_msg = 'unable to update Image file';
				$sql_statement = 'INSERT INTO mod_photo_gal_pics SET' 
				
													.'  cat_id = '.$_POST['cat_id']
													.' ,seq = "'.$next_seq.'"'
													.' ,active = "on"'
													;
				ReadDB ($sql_statement, $mysql_err_msg);	// Use 'ReadDB' instead of 'UpdateDB' to avoid conflicting the following mysql_insert_id

				$last_insert_id = mysql_insert_id();
				
				//	get new filename
				$new_filename = $last_insert_id . '.' . $file_type;
				
				$sql_statement = 'UPDATE mod_photo_gal_pics SET' 
				
													.' file_name = "'.$new_filename.'"'
													.' WHERE photo_id = "'.$last_insert_id.'"'
													;
				UpdateDB ($sql_statement, $mysql_err_msg);
				
				$upload_path = $file_path_offset.'_images_gallery/';
				
				
				$image_details = getimagesize($_FILES['upload_photo_gal_images']['tmp_name'][$i]);
				
				$width = $image_details[0]; 
				$height = $image_details[0]; 
				
				//	Get Image Resize settings from settings table
				$mysql_err_msg = 'Photo Gallery Image resize settings info unavailable';
				$sql_statement = 'SELECT'
											.' resize_img_mode'
											.',resize_img_max_width' 
											.',resize_img_max_height'
				
											.' FROM mod_photo_gal_settings WHERE mod_id = '.$mod_id;	
				
				$gal_settings = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

				$resize_img_mode = $gal_settings['resize_img_mode'];
				$resize_img_max_width = $gal_settings['resize_img_max_width'];
				$resize_img_max_height = $gal_settings['resize_img_max_height'];
				
				//	DO RESIZE FILE --- but only if set to and image exceeds dimentions				
				if($resize_img_mode AND ($width > $resize_img_max_width OR $height > $resize_img_max_height))
				{		
					ResizeImages
					( 
						  $_FILES['upload_photo_gal_images']['tmp_name'][$i]
						, $upload_path.$new_filename
						, $file_type
						, $resize_img_mode
						, $resize_img_max_width
						, $resize_img_max_height
						, $reporting = 0
					);				
				}
				
				else
				{
			
					//	move file					
					if (!move_uploaded_file($_FILES['upload_photo_gal_images']['tmp_name'][$i], $upload_path.$new_filename))	 //	failed to upload
					{
						$_SESSION['update_error_msg'] .= '- The File: '.$_FILES['upload_photo_gal_images']['name'][$i]
						.' was not uploaded due to the following error: '
						.FileUploadErrorMessage($_FILES['upload_photo_gal_images']['error'][$i]).'.\n';
													
					}
	
					else {@chmod( $upload_path.$new_filename, 0666);}	// 	Set Rights To Uploaded File}				
				
				}
			
				$_SESSION['update_success_msg'] = "Update Succesfull";
				
			}
						
		}
		
	}

	
	//	Update all Images from listing
	elseif ( isset($_POST['update_all_imgs']))
	{
	
		//	Update All Images
		if (count($_POST['image_pos_array']) > 0 OR $_POST['image_pos_array'] != '')	
		{
			$image_pos_array = array_flip (explode ( ',' , $_POST['image_pos_array']));		
			foreach ($image_pos_array as $image_id => $seq )
			{
				$seq = $seq + 1;
				
				//Delete image
				if (isset($_POST['delete_image_'.$image_id]))
				{				
					DeletePhoto($image_id);
				}
			
				
				// Or Update
				else
				{
					
					//	trim and code text
					if (isset($_POST['photo_text_'.$image_id]))
					{
						$photo_text = trim($_POST['photo_text_'.$image_id]);
						$photo_text = htmlentities($photo_text, ENT_QUOTES);
					}
					else	{$photo_text = '';}					
					
							
					//	get active statis
					if (in_array($image_id, $_POST['active']))
					{
						$active = 'on';
					}
					else
					{
						$active = '';
					}
					
				
					$sql_statement = 'UPDATE mod_photo_gal_pics SET' 
					

														.'  active = "'.$active.'"'
														.', seq = '. $seq
														.', photo_text = "'.$photo_text.'"'
														.' WHERE photo_id = '.$image_id
														;					

					if(!UpdateDB ($sql_statement, $mysql_err_msg))
					{
						$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
					}							
					
				}				
															
			}
			
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
				DeletePhoto($image_id);
			}
			
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