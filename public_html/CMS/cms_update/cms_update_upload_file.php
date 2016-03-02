<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );
	
	$file_path_offset = '../../';
	

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	//require_once ('cms_update_common.php');
	
	$remove_tag_list = array ('<span' => '</span>');
	
	$filname_replace_array = array(' ','-');
	$filname_replace_with_array = array('_','_');
	
	$upload_filetype_allow = array 
	(
		'text/plain' => 'txt'
		,'image/gif' => 'gif'
		,'image/jpeg' => 'jpg'
		,'image/pjpeg' => 'jpg'
		,'image/png' => 'png'
		,'image/x-png' => 'png'
		,'application/msword' => 'doc'	
		,'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx'
		,'application/pdf' => 'pdf'
		,'application/x-download' => 'pdf'
		,'audio/mpeg' => 'mp3'
		,'video/mpeg' => 'mpg'	


	);
	
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
		
		$error = FALSE;

		if ($_FILES['upload_and_link_file']['error'] == 0 AND $_FILES['upload_and_link_file']['size'] > MAX_FILE_SIZE_CMS)
		{
			$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_and_link_file']['name']
			.'\' is larger than ' . MAX_FILE_SIZE_CMS / 1000 .'kB and was not uploaded. \n';
			$error = TRUE;
		}
		
		
		//	check file type
		if ( array_key_exists ($_FILES['upload_and_link_file']['type'] , $upload_filetype_allow))
		{						
			//$file_ext = $upload_filetype_allow[$_FILES['upload_and_link_file']['type']];
			$file_type = $_FILES['upload_and_link_file']['type'];
								
		}

		else
		{
			$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_and_link_file']['name']
			.'\' is NOT the correct file type and was not uploaded. \n';
			$error = TRUE;
		}	


		//	check for errors
		if ($_FILES['upload_and_link_file']['error'] > 0)
		{
			$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_and_link_file']['name']
			.'\' was not uploaded due to the following error: '.FileUploadErrorMessage($_FILES['upload_and_link_file']['error']).'. \n';
			$error = TRUE;
		}
				
		if ($error == FALSE)
		{
	
			//	get new filename
			$file_name = $_FILES['upload_and_link_file']['name'];

			//	trim and strip name
			$file_name = str_replace($filname_replace_array, $filname_replace_with_array, trim($file_name));
			
			//	remove ext
			$path_parts = pathinfo($file_name);
			$file_base = $path_parts['basename']; 
			$file_ext  = $path_parts['extension'];
			$new_base = str_replace($file_ext, '', $file_base);
			
			$new_base = preg_replace('/[^A-Za-z0-9_]+/i', '',$new_base);	// remove all but alphanums and underscores

			//	remove excessive underscores that might get created
			while (strpos($new_base , '__'))
			{
				$new_base = str_replace('__', '_', $new_base);
			}
			
			$new_filename = $new_base . '.' . $file_ext;
			$upload_path = $file_path_offset.'_files/';	
			
			// Check a file with the same name already exists ( add a number suffix if it does)
			$n = 1;
			while (file_exists($upload_path . $new_filename))
			{
				$new_filename = $new_base . '-' . $n . '.' . $file_ext;
				$n++;
			}

		
			//	RESIZE IF IMAGE
			if (getimagesize($_FILES['upload_and_link_file']['tmp_name']))
			{
					
				$image_details = getimagesize($_FILES['upload_and_link_file']['tmp_name']);
					
				$width = $image_details[0]; 
				$height = $image_details[0]; 
				
				//	DO RESIZE FILE --- but only if set to and image exceeds dimentions				
				if
				(
						FILE_IMAGE_RESIZE_MODE 
					AND ($width > FILE_IMAGE_MAX_WIDTH OR $height > FILE_IMAGE_MAX_HEIGHT)
				)
				{		
					ResizeImages
					( 
						  $_FILES['upload_and_link_file']['tmp_name']
						, $upload_path.$new_filename
						, $file_ext
						, FILE_IMAGE_RESIZE_MODE
						, FILE_IMAGE_MAX_WIDTH
						, FILE_IMAGE_MAX_HEIGHT
						, $reporting = 0
					);				
				
				}				
					
			}						

			else
			{
		
				//	move file					
				if (!move_uploaded_file($_FILES['upload_and_link_file']['tmp_name'], $upload_path.$new_filename))	 //	failed to upload
				{
					$_SESSION['update_error_msg'] .= '- The File: '.$_FILES['upload_and_link_file']['name']
					.' was not uploaded due to the following error: '
					.FileUploadErrorMessage($_FILES['upload_and_link_file']['error']).'.\n';
				}

				else {@chmod( $upload_path.$new_filename, 0666);}	// 	Set Rights To Uploaded File				
			
			}			


		}	
		
		if (isset($_POST['upload_and_link_file_text']) AND $_POST['upload_and_link_file_text'] != '')
		{
			$mod_id = $_POST['mod_id'];
			$db_table = $_POST['db_table'];
			
			//	update text
			$link_text = $_POST['upload_and_link_file_text'];
			$replace = '[linkMod_id_'.$mod_id.']';
			
			if ($error == FALSE)
			{
				$replace_with = '<a href=\"/_files/'.$new_filename.'\" rel=\"'.$_POST['upload_and_link_file_rel'].'\" >'.$link_text.'</a>';		
			}
			else
			{
				$replace_with = $link_text;
			}

			//	For Tables
			if ($db_table == 'mod_table_data')
			{
				$update_str = 'col_1 = REPLACE(col_1, "'.$replace.'", "'.$replace_with.'")';
				for ($col = 2; $col < 10; $col++)
				{
					$update_str .= ', col_'.$col.' = REPLACE(col_'.$col.', "'.$replace.'", "'.$replace_with.'")';
				}
				
				$sql_statement = 'UPDATE '.$db_table.' SET '.$update_str 
				
													.' WHERE mod_id = "'.$mod_id.'"'
													;					
				
			}
			
			//	for Text / Headings
			else
			{
				$sql_statement = 'UPDATE '.$db_table.' SET text = REPLACE(text, "'.$replace.'", "'.$replace_with.'")'
				
													.' WHERE mod_id = "'.$mod_id.'"'
													//.' WHERE text LIKE "%[linkMod_id_1195]%"'
													;			
			}
			

			//echo $sql_statement;											
			if(!UpdateDB ($sql_statement, $mysql_err_msg))
			{
				$_SESSION['update_error_msg'] .= ' - Database Error: Could not update create link \n';
			}
			else
			{
				$_SESSION['update_success_msg'] .= ' - Link created  \n';
			}	
			
		}

		
		
		if ($error == FALSE)
		{
			$_SESSION['update_success_msg'] .= ' - File uploaded \n';
		}						

	}

	else
	{		
		$_SESSION['update_error_msg'] .= ' - Insufficient Privileges to Modify Data \n';		
	}
	

	//	Re-Direct BACK
	$return_url = $_POST['return_url'];	
	header('location: '.$return_url); 
	exit();	
	
?>