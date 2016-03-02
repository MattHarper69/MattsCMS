<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	Get all mod_IDs for synching
	function GetSyncModIDs($mod_id)
	{
		
		$mysql_err_msg = 'retrieving synced module info';
		$sql_statement = 'SELECT sync_id FROM modules WHERE mod_id = '.$mod_id;
		$sync_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
		$sync_id = $sync_info['sync_id'];									
												
		$sql_statement = 'SELECT mod_id FROM modules'

											.' WHERE sync_id = '.$sync_id
											.' AND sync_id != 0'
											.' AND mod_type_id = '.$_POST['mod_type_id']
											;
											
		$sync_result = ReadDB ($sql_statement, $mysql_err_msg);
		
		$sync_mod_ids_array = array();
		while ($syncs = mysql_fetch_array ($sync_result))
		{
			$sync_mod_ids_array[] = $syncs['mod_id'];				
		}
		
		return $sync_mod_ids_array;
		
	}



		
	//	Delete an Image
	function DeletePhoto($image_id)
	{

		//	get image file name to delete
		$mysql_err_msg = 'unable to get image filename info for deletion';
		$sql_statement = 'SELECT file_name FROM mod_photo_gal_pics WHERE photo_id = "'.$image_id.'"';

		$image_file_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
		$image_file_name = $image_file_info['file_name'];
		
		//	delete from Image table db 
		$mysql_err_msg = 'unable to DELETE record of Image';	
		$sql_statement = 'DELETE FROM mod_photo_gal_pics WHERE photo_id = "'.$image_id.'"';
								
		if(!UpdateDB ($sql_statement, $mysql_err_msg))
		{
			$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
		}
		
		// delete image file	
		$img_file_path = '../../_images_gallery/'.$image_file_name;
			
		if (file_exists($img_file_path) AND $image_file_name != '')
		{	
			unlink($img_file_path);						
		}	
		
	}
	
	
	// Do validation	
	function ValidateName($new_name, $cat_id)
	{

		//	trim and code text
		$new_cat_name = trim($new_name);
		$new_cat_name = htmlentities($new_cat_name, ENT_QUOTES);
		
		//	check not empty
		if($new_cat_name == '')
		{
			$_SESSION['update_error_msg'] .= ' - Please Type a name for the Category \n';
			
			//	Re-Direct BACK
			header('location: '.$_POST['return_url']); 
			exit();				
		}

		else
		{
			// check cat name is unique for that mod
			$mysql_err_msg = 'retrieving existing category names';
			$sql_statement = 'SELECT cat_name FROM mod_photo_gal_cats, mod_photo_gal_mod_cat_asign'

											.' WHERE cat_name = "'.$new_cat_name.'" '
											.' AND mod_photo_gal_mod_cat_asign.mod_id = '.$_POST['mod_id']
											.' AND mod_photo_gal_mod_cat_asign.gal_cat_id = mod_photo_gal_cats.gal_cat_id'
											.' AND mod_photo_gal_cats.gal_cat_id != '.$cat_id
											;	
												
												
			if (mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg)))
			{
				$_SESSION['update_error_msg'] .= ' - The Category Name already exists, Please enter another name for the Category \n';

				//	Re-Direct BACK
				header('location: '.$_POST['return_url']); 
				exit();					
			}
			
			else
			{
				return $new_cat_name;
			}
			
		}
			
	}

	
?>