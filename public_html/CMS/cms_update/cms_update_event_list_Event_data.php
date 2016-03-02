<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	$file_path_offset = '../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	//require_once (CODE_NAME.'_shop_configs.php');
	require_once ('cms_update_common.php');
	//require_once ('cms_update_sweeps_functions.php');

	
	
	
	//------------set default time zone
	//date_default_timezone_set(SHOP_TIME_ZONE);
			
			//	set local vars - avoid errors
			if (!isset($_SESSION['update_success_msg']))
			{
				$_SESSION['update_success_msg'] = '';
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
			//$_POST[$key] = str_replace('"', '&quot;', $_POST[$key]);	// this causes problems in the WYSIWYG with '<span class="underline"> etc
			$_POST[$key] = str_replace("'", '&#39;' , $_POST[$key]);	
			$_POST[$key] = addslashes($_POST[$key]);
		}		
				
		//print_r ($_POST);
		

		//	Delete this Item
		if ( isset($_POST['submit_delete_event']))
		{
			$mysql_err_msg = 'deleting Event';
			$sql_statement = 'DELETE FROM mod_event_list WHERE event_id = "'.$_POST['event_id'].'"';
				
			if(!UpdateDB ($sql_statement, $mysql_err_msg)) { $error = TRUE;}
			else 
			{
				$_SESSION['update_success_msg'] = 'Event Deleted';
			}
		}
		
		//	Update if form sbmited
		if ( isset($_POST['update_all']))
		{
			//	Update ALL info
			$mysql_err_msg = 'Up-dating Event info'; // get alias

			//	New OR Cloned Product
			if(isset($_POST['update_action']) AND $_POST['update_action'] == 'new')		
			{
				$sql_1 = 'INSERT INTO';
				$sql_2 = '';				
			}
			
			//	Update existing Product
			else
			{			
				$sql_1 = 'UPDATE';
				$sql_2 = ' WHERE event_id = "'.$_POST['event_id'].'"';			
			}	
			
	/////////////////////////////////////////////////		EDITED TO HERE		///////////////////////////////////////////////////////////
	
			 
			//	Long Desc.
			$long_desc = $_POST['long_desc'];
			
			if ($long_desc == 'click here to add text...'){$long_desc = '';}

			//////////		Replace bad HTML
			$long_desc = CleanHtml($long_desc);
			
			//	need to remove any extra "</span>" tags to prevent parts of the text mod become un-editable		
			$long_desc = remove_end_tags($long_desc, $remove_tag_list);

			//	in IE "null" is displayed if string empty
			if ($long_desc == 'null'){ $long_desc = '';}	
	
			//	loop thru all fields
			$sql_str = '';
			for ($i = 1; $i < 6; $i++)
			{
				$sql_str .= ', field_'.$i.' = "'.$_POST['field_'.$i].'"';						
			}	
	
			//	process dates and times
			
			$event_date = date("Y-m-d",strtotime($_POST['event_date']));
			$event_time = date("H:i",strtotime($event_date.' '.$_POST['event_time']));
			$time = $event_date.' '.$event_time . ':00';	
			
			$start_date = date("Y-m-d",strtotime($_POST['start_date']));
			$start_time = date("H:i",strtotime($start_date.' '.$_POST['start_time']));
			$active_start = $start_date.' '.$start_time . ':00';	

			$stop_date = date("Y-m-d",strtotime($_POST['stop_date']));
			$stop_time = date("H:i",strtotime($stop_date.' '.$_POST['stop_time']));
			$active_end = $stop_date.' '.$stop_time . ':00';				

			//	strip non numbers
			$auto_expire_time = preg_replace('/\D/', '',$_POST['auto_expire_time']);

			//	set local vars - avoid errors
			if (isset($_POST['active']) AND $_POST['active'] == 'on')
			{$active = 'on';}
			else	{$active = '';}

			if (isset($_POST['more_info_on']) AND $_POST['more_info_on'] == 'on')
			{$more_info_on = 'on';}
			else	{$more_info_on = '';}	

			if (isset($_POST['display_fields_in_more_info']) AND $_POST['display_fields_in_more_info'] == 'on')
			{$display_fields_in_more_info = 'on';}
			else	{$display_fields_in_more_info = '';}
			
			if (isset($_POST['stop_time_select']) AND $_POST['stop_time_select'] == 'auto_expire')
			{$auto_expire_on = 'on';}
			elseif (isset($_POST['stop_time_select']) AND $_POST['stop_time_select'] == 'never')
			{$auto_expire_on = 'no';}
			else	{$auto_expire_on = '';}
			
			
			$sql_statement = $sql_1.' mod_event_list SET'
			

									.'  mod_id = "'.$_REQUEST['mod_id'].'"'
									.', active = "'.$active.'"'
									.', name = "'.$_POST['name'].'"'
									.', display_name = "'.$_POST['display_name'].'"'								
									.', time = "'.$time.'"'
									.', more_info_on = "'.$more_info_on.'"'
									.', display_fields_in_more_info = "'.$display_fields_in_more_info.'"'									
									.', long_desc = "'.$long_desc.'"'
									
									.', active_start = "'.$active_start.'"'
									.', active_end = "'.$active_end.'"'
									.', auto_expire_on = "'.$auto_expire_on.'"'
									.', auto_expire_time = "'.$auto_expire_time.'"'
									.', auto_expire_unit = "'.$_POST['auto_expire_unit'].'"'


									.	$sql_str
									
									.   $sql_2	
									;
			if(!ReadDB ($sql_statement, $mysql_err_msg))	// Use 'ReadDB' instead of 'UpdateDB' to avoid conflicting the following mysql_insert_id
			{
				$_SESSION['update_error_msg'] = ' - Database Error: Could not Update / Add '.SHOP_ITEM_ALIAS.' \n';
			}

			$new_event_id = mysql_insert_id();	
			
			if(isset($_POST['update_action']) AND $_POST['update_action'] == 'new')
			{
				$event_id = $new_event_id;
			}
			else
			{
				$event_id = $_POST['event_id'];
			}
			
	
			$_SESSION['update_success_msg'] = "Update Succesfull";
			
		}
		
		//	image upload
		if (isset($_POST['submit_upload_event_image']) AND $_FILES['upload_event_image']['name'][0] != '')
		{
		
			//	determin next file name
			$mysql_err_msg = 'unable to get '.SHOP_ITEM_ALIAS.' Image info for uploading new image file';
			$sql_statement = 'SELECT image_file_name '

										.' FROM sweeps_item_images'
										.' ORDER BY image_file_name DESC'
										;
									
			$filename_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			$last_filename = $filename_info['image_file_name'];	
			$last_filename = substr($last_filename, 0, -4);	// remove extension
			
			//	determin next seq
			$sql_statement = 'SELECT seq '

										.' FROM sweeps_item_images'
										.' WHERE event_id = "'.$_POST['event_id'].'"'
										.' ORDER BY seq DESC'
										;
								
			$seq_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			$last_seq = $seq_info['seq'];	
			
			for ($i = 0; $i < count($_FILES['upload_item_images']['name']); $i++)
			{
				$error = FALSE;

				if ($_FILES['upload_item_images']['error'][$i] == 0 AND $_FILES['upload_item_images']['size'][$i] > MAX_FILE_SIZE_CMS)
				{
					$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_item_images']['name'][$i]
					.'\' is larger than ' . MAX_FILE_SIZE_CMS / 1000 .'kB and was not uploaded. \n';
					$error = TRUE;
				}
				//	check file type
				if ($_FILES['upload_item_images']['type'][$i] == 'image/gif')
				{$file_type = 'gif';}
				elseif ($_FILES['upload_item_images']['type'][$i] == 'image/jpeg' OR $_FILES['upload_item_images']['type'][$i] == 'image/pjpeg')
				{$file_type = 'jpg';}	
				elseif ($_FILES['upload_item_images']['type'][$i] == 'image/png' OR $_FILES['upload_item_images']['type'][$i] == 'image/x-png')
				{$file_type = 'png';}
				
				else
				{
					$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_item_images']['name'][$i]
					.'\' is NOT the correct file type and was not uploaded. \n';
					$error = TRUE;
				}
				
				//	check for errors
				if ($_FILES['upload_item_images']['error'][$i] > 0)
				{
					$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_item_images']['name'][$i]
					.'\' was not uploaded due to the following error: '.FileUploadErrorMessage($_FILES['upload_item_images']['error'][$i]).'. \n';
					$error = TRUE;
				}
				
				if ($error == FALSE)
				{
					
					//	Do db updates:

					$next_seq = $last_seq + 1+ $i;
					$mysql_err_msg = 'unable to update '.SHOP_ITEM_ALIAS.' Image file';
					$sql_statement = 'INSERT INTO sweeps_item_images SET' 
					
														.'  event_id = '.$_POST['event_id']
														.' ,seq = "'.$next_seq.'"'
														;
					ReadDB ($sql_statement, $mysql_err_msg);	// Use 'ReadDB' instead of 'UpdateDB' to avoid conflicting the following mysql_insert_id

					$last_insert_id = mysql_insert_id();	
					
					//	get new filename
					$new_filename = $last_insert_id . '.' . $file_type;
					
					$sql_statement = 'UPDATE sweeps_item_images SET' 
					
														.' image_file_name = "'.$new_filename.'"'
														.' WHERE image_id = "'.$last_insert_id.'"'
														;
					UpdateDB ($sql_statement, $mysql_err_msg);
					
					$upload_path = $file_path_offset.'_images_shop/';
					
					
					$image_details = getimagesize($_FILES['upload_item_images']['tmp_name'][$i]);
					
					$width = $image_details[0]; 
					$height = $image_details[0]; 
					
			
					//	DO RESIZE FILE --- but only if set to and image exceeds dimentions				
					if(SHOP_IMAGE_RESIZE_MODE AND ($width > SHOP_IMAGE_MAX_WIDTH OR $height > SHOP_IMAGE_MAX_WIDTH))
					{		
						ResizeImages
						( 
							  $_FILES['upload_item_images']['tmp_name'][$i]
							, $upload_path.$new_filename
							, $file_type
							, SHOP_IMAGE_RESIZE_MODE
							, SHOP_IMAGE_MAX_WIDTH
							, SHOP_IMAGE_MAX_HEIGHT
							, $reporting = 0
						);				
					}
					
					else
					{
				
						//	move file					
						if (!move_uploaded_file($_FILES['upload_item_images']['tmp_name'][$i], $upload_path.$new_filename))	 //	failed to upload
						{
							$_SESSION['update_error_msg'] .= '- The File: '.$_FILES['upload_item_images']['name'][$i]
							.' was not uploaded due to the following error: '
							.FileUploadErrorMessage($_FILES['upload_item_images']['error'][$i]).'.\n';
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

			
		}

		
		//	Delete single image
		elseif (isset($_POST['image_pos_array']) AND (count($_POST['image_pos_array']) > 0 OR $_POST['image_pos_array'] != ''))	
		{
			$image_pos_array = explode ( ',' , $_POST['image_pos_array']);			
			
			foreach ($image_pos_array as $image_id )
			{
				if (isset($_POST['delete_image_'.$image_id]))
				{
					DeleteItemImage($image_id);
				}
				
			}
		
		}
		
	}
	
	else
	{
		
		$_SESSION['update_error_msg'] .= '- Insufficient Privileges to Modify Data \n';
		
	}
		

	//	check for errors and print MSG
	if($error == FALSE)
	{
		$_SESSION['update_success_msg'] .= "( Update Succesfull ) \n";
	}
	else
	{
		$_SESSION['update_error_msg'] .= $error;
	}				
			
			
	//	Get Item ID to return to after update	
	if(isset($_POST['update_action']) AND $_POST['update_action'] == 'new')		
	{
		$return_event_id = $new_event_id;	
	}
	
	elseif ( isset($_POST['submit_delete_item']))
	{
		$return_event_id = NULL;
	}		
	
	
	else
	{
		$return_event_id = $_POST['event_id'];
	}		
		



	//	Re-Direct BACK
	$return_url = $_POST['return_url'].'&event_id='.$return_event_id;	
	header('location: '.$return_url); 
	exit();	

	
?>