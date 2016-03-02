<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );
	
	$file_path_offset = '../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	require_once (CODE_NAME.'_shop_configs.php');
	require_once ('cms_update_common.php');
	require_once ('cms_update_sweeps_functions.php');
	require_once ($file_path_offset.'modules/sweeps/sweeps_php_functions.php');	
	
if (isset($_SESSION['access']) AND $_SESSION['access'] < 5 )
{	

	$error = FALSE;	

	//	Delete this Category
	if ( isset($_POST['submit_delete_cat']))
	{
		DeleteCategory ($_POST['cat_id']);

		$return_url = $_POST['return_url'];
		
	}
	
	//	Delete Cat Image
	elseif ( isset($_POST['delete_cat_image']))
	{

		$mysql_err_msg = 'unable to DELETE record of Category';
		
		//	Get file_name
		$sql_statement = 'SELECT image_file FROM sweeps_categories WHERE cat_id = "'.$_POST['cat_id'].'"';
		$image_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
		$image_file = $image_info['image_file'];

		//	remove entry from db		
		$sql_statement = 'UPDATE sweeps_categories SET image_file = "" WHERE cat_id = "'.$_POST['cat_id'].'"';					
		if(!UpdateDB ($sql_statement, $mysql_err_msg))
		{
			$error = TRUE;
		}
		
		//	remove image file..
		$img_file_path = '../../_images_shop/'.$image_file;
		if (file_exists($img_file_path) AND $image_file != '')
		{	
			if(!unlink($img_file_path))
			{
				$error = TRUE;
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

		$return_url = $_POST['return_url'].'?cat_id='.$_POST['cat_id'];
		
	}
	
	//	Update or add a Single Category	
	elseif ( isset($_POST['submit_update_cat']))
	{

		//	Update ALL info
		$mysql_err_msg = 'Up-dating Category info';

		//	New OR Cloned Product
		if($_POST['update_action'] == 'new' OR $_POST['update_action'] == 'clone')		
		{
			$sql_1 = 'INSERT INTO';
			$sql_2 = '';				
		}
		
		//	Update existing Product
		else
		{			
			$sql_1 = 'UPDATE';
			$sql_2 = ' WHERE cat_id = "'.$_POST['cat_id'].'"';			
		}	


//	===========================	EDITED TO HERE	=======================================================================
		// Do validation

		//	DO FUNCTION TO adjust parent IDs of Cats if ( new parent ID is an existing Child ID)
		AdjustParentIDs ($_POST['cat_id'], $_POST['old_parent_id'], $_POST['new_parent_id']);

		$sql_statement = $sql_1.' sweeps_categories SET'
		
								.'  cat_name = "'.$_POST['cat_name'].'"'
								.', active = "'.$_POST['active'].'"'
								.', parent_id = "'.$_POST['new_parent_id'].'"'
								.', description = "'.$_POST['description'].'"'
								//.', image_file = "'.$_POST['image_file'].'"'
								.', display_image = "'.$_POST['display_image'].'"'

			
								.	$sql_2	
								;
//echo $sql_statement;												
		if(!ReadDB ($sql_statement, $mysql_err_msg))	// Use 'ReadDB' instead of 'UpdateDB' to avoid conflicting the following mysql_insert_id
		{
			$_SESSION['update_error_msg'] = ' - Database Error: Could not Update / Add Category \n';
		}
	
		//	new or existing cat update ?
		if($_POST['update_action'] == 'new' OR $_POST['update_action'] == 'clone')		
		{
			$cat_id = mysql_insert_id();				
		}
		else
		{
			$cat_id = $_POST['cat_id'];				
		}	


		//	image upload
		if ($_FILES['upload_cat_image']['name'] != '')
		{
			$error = FALSE;

			if ($_FILES['upload_cat_image']['error'] == 0 AND $_FILES['upload_cat_image']['size'] > MAX_FILE_SIZE_CMS)
			{
				$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_cat_image']['name']
				.'\' is larger than ' . MAX_FILE_SIZE_CMS / 1000 .'kB and was not uploaded. \n';
				$error = TRUE;
			}
			//	check file type
			if ($_FILES['upload_cat_image']['type'] == 'image/gif')
			{$file_type = 'gif';}
			elseif ($_FILES['upload_cat_image']['type'] == 'image/jpeg' OR $_FILES['upload_cat_image']['type'] == 'image/pjpeg')
			{$file_type = 'jpg';}	
			elseif ($_FILES['upload_cat_image']['type'] == 'image/png' OR $_FILES['upload_cat_image']['type'] == 'image/x-png')
			{$file_type = 'png';}
			
			else
			{
				$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_cat_image']['name']
				.'\' is NOT the correct file type and was not uploaded. \n';
				$error = TRUE;
			}	

			//	check for errors
			if ($_FILES['upload_cat_image']['error'] > 0)
			{
				$_SESSION['update_error_msg'] .= '- The File: \''.$_FILES['upload_cat_image']['name']
				.'\' was not uploaded due to the following error: '.FileUploadErrorMessage($_FILES['upload_cat_image']['error']).'. \n';
				$error = TRUE;
			}
			if ($error == FALSE)
			{

				
				//	get new filename
				$new_filename = 'cat_'.$cat_id . '.' . $file_type;
				
				//	update db
				$sql_statement = 'UPDATE sweeps_categories SET' 
				
													.' image_file = "'.$new_filename.'"'
													.' WHERE cat_id = "'.$cat_id.'"'
													;
				UpdateDB ($sql_statement, $mysql_err_msg);	


				$upload_path = $file_path_offset.'_images_shop/';										
				$image_details = getimagesize($_FILES['upload_cat_image']['tmp_name']);
				
				$width = $image_details[0]; 
				$height = $image_details[0]; 
							
				//	DO RESIZE FILE --- but only if set to and image exceeds dimentions				
				if(SHOP_IMAGE_RESIZE_MODE AND ($width > SHOP_IMAGE_MAX_WIDTH OR $height > SHOP_IMAGE_MAX_WIDTH))
				{		
					ResizeImages
					( 
						  $_FILES['upload_cat_image']['tmp_name']
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
					if (!move_uploaded_file($_FILES['upload_cat_image']['tmp_name'], $upload_path.$new_filename))	 //	failed to upload
					{
						$_SESSION['update_error_msg'] .= '- The File: '.$_FILES['upload_cat_image']['name']
						.' was not uploaded due to the following error: '
						.FileUploadErrorMessage($_FILES['upload_cat_image']['error']).'.\n';
					}
	
					else {@chmod( $upload_path.$new_filename, 0666);}	// 	Set Rights To Uploaded File}				
				
				}
				
				$_SESSION['update_success_msg'] .= 'Image Updated \n';
				
//	===========================	EDITED TO HERE	=======================================================================	
			}
				
	
		}
				
		$return_url = $_POST['return_url'].'?cat_id='.$cat_id;		
	
	}

	
	//	Update all Cats from listing
	//if ( isset($_POST['update_all']))
	else
	{
		//	Update ALL info
		$mysql_err_msg = 'Up-dating Category info';

		UpdateAllCategories($_POST['cat_id']);
						
		$return_url = $_POST['return_url'];
		
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
	header('location: '.$return_url); 
	exit();	
	
	
	
?>