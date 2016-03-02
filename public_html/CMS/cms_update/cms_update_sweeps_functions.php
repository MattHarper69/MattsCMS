<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
//=================================================================================================	
//	Products	================================================================================
//=================================================================================================	
	
	function DeleteItem($item_id)
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

		// get item name	
		$mysql_err_msg = 'can not get item info for deletion';
		$sql_statement = 'SELECT item_name FROM sweeps_items WHERE sweeps_items.item_id = "'.$item_id.'"';
		$name_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
		$item_name = $name_info['item_name'];
		
		// create an array of image_ids to delete all associate images	
		$sql_statement = 'SELECT image_file_name FROM sweeps_items, sweeps_item_images'
		
												.' WHERE sweeps_items.item_id = "'.$item_id.'"'
												.' AND sweeps_items.item_id = sweeps_item_images.item_id'
												;
												
//echo $sql_statement;										
		$image_result = ReadDB ($sql_statement, $mysql_err_msg);
		while ($image_info = mysql_fetch_array($image_result))
		{		
			$image_files_array[] = $image_info['image_file_name'];
		}

		//	delete from items table
		$mysql_err_msg = 'deleting from item table';
		$sql_statement = 'DELETE FROM sweeps_items WHERE item_id = "'.$item_id.'"';
		if(!UpdateDB ($sql_statement, $mysql_err_msg)) { $error = TRUE;}
				
		//	delete from sweeps_cat_asign table
		$mysql_err_msg = 'deleting from asignment table';
		$sql_statement = 'DELETE FROM sweeps_cat_asign WHERE item_id = "'.$item_id.'"';
		if(!UpdateDB ($sql_statement, $mysql_err_msg)) { $error = TRUE;}		
		
		//	delete from sweeps_item_images table
		$mysql_err_msg = 'deleting from image table';
		$sql_statement = 'DELETE FROM sweeps_item_images WHERE item_id = "'.$item_id.'"';
		if(!UpdateDB ($sql_statement, $mysql_err_msg)) { $error = TRUE;}		
		
		//	delete from sweeps_item_location_restrictions table
		$mysql_err_msg = 'deleting from restrictions table';
		$sql_statement = 'DELETE FROM sweeps_item_location_restrictions WHERE item_id = "'.$item_id.'"';
		if(!UpdateDB ($sql_statement, $mysql_err_msg)) { $error = TRUE;}			
		
		//	Delete Image Files
		if (isset($image_files_array) AND count($image_files_array) > 1)
		{
			foreach ($image_files_array as $image_file_name)
			{
				$img_file_path = '../../_images_shop/'.$image_file_name;
				if (file_exists($img_file_path) AND $image_file_name != '')
				{	
					if(!unlink($img_file_path))
					{
						$_SESSION['update_error_msg'] .= ' - Image file for; '.$item_name.' ('.$image_file_name.') could not be deleted ! \n';
					}
					
				}
				
			}
			
		}

		
		//	check for errors and print MSG
		if($error == FALSE)
		{
			$update_success_msg .= ' - '.SHOP_ITEM_ALIAS.': '.$item_name.' Succesfully deleted \n';
			$_SESSION['update_success_msg'] = $update_success_msg;
		}
		else
		{
			$update_error_msg .= ' - A Database Error occured \n';
			$_SESSION['update_error_msg'] = $update_error_msg;
		}
		
	}


	function DeleteItemImage($image_id)
	{
		//	get product image details
		$mysql_err_msg = 'unable to get '.SHOP_ITEM_ALIAS.' Image info for deletion';
		$sql_statement = 'SELECT image_file_name, sweeps_items.item_id, primary_image_id'

									.' FROM sweeps_item_images, sweeps_items'
									.' WHERE image_id = "'.$image_id.'"'
									.' AND sweeps_items.item_id = sweeps_item_images.item_id'
									;
		
		$image_file_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
		$image_file_name = $image_file_info['image_file_name'];
		$item_id = $image_file_info['item_id'];
		$primary_image_id = $image_file_info['primary_image_id'];

		
		//	are we deleting the primary image ?
		if ($image_id == $primary_image_id)
		{
			//	Get next image in line to use as primary
			$sql_statement = 'SELECT image_id FROM sweeps_item_images'			
			
											.' WHERE image_id != "'.$image_id.'"'
											.' AND item_id = "'.$item_id.'"'
											.' ORDER BY seq'
											;

			$new_primary_image_id = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			$new_primary_image_id = $new_primary_image_id['image_id'];
			
			//	if no image found, default to 1
			if($new_primary_image_id == '') 
			{$new_primary_image_id = 0;}
			
			$sql_statement = 'UPDATE sweeps_items SET primary_image_id = "'.$new_primary_image_id.'" WHERE item_id = "'.$item_id.'"';
											
			UpdateDB ($sql_statement, $mysql_err_msg);	
			
		}
					
		//	remove entry from db
		$mysql_err_msg = 'unable to DELETE record of '.SHOP_ITEM_ALIAS.' Image';
		$sql_statement = 'DELETE FROM sweeps_item_images WHERE image_id = "'.$image_id.'"';				
		
		UpdateDB ($sql_statement, $mysql_err_msg);
		
		
		//	now remove file..
		$img_file_path = '../../_images_shop/'.$image_file_name;
		if (file_exists($img_file_path) AND $image_file_name != '')
		{	
			unlink($img_file_path);						
		}			

	}


	
	
//=================================================================================================
//	Categories	===================================================================================
//=================================================================================================

	function UpdateAllCategories($parent_id)
	{
		//	Update All Categories
		if (count($_POST['cat_pos_array_'.$parent_id]) > 0 OR $_POST['cat_pos_array_'.$parent_id] != '')	
		{
			$cat_pos_array = array_flip (explode ( ',' , $_POST['cat_pos_array_'.$parent_id]));		
			foreach ($cat_pos_array as $cat_id => $seq )
			{
				$seq = $seq + 1;
				//if (!isset($_POST['delete_'.$cat_id]))
				if (!in_array($cat_id, $_POST['delete']))
				{
					if ($_POST['move_cat_'.$cat_id] == "dontmove")
					{
						$parent_sql = '';
					}
					else
					{
						//	has this cats 'move to parent' been deleted ?? - if so set as 0
						$sql_statement = 'SELECT cat_id FROM sweeps_categories WHERE cat_id = '.$_POST['move_cat_'.$cat_id];
						$test_exist = ReadDB ($sql_statement, $mysql_err_msg);
						
						if(mysql_num_rows($test_exist) > 0)	
						{$parent_sql = ', parent_id = '.$_POST['move_cat_'.$cat_id];}
						else {$parent_sql = ', parent_id = "0"';}	
						
						AdjustParentIDs ($cat_id, $_POST['old_parent_for_'.$cat_id], $_POST['move_cat_'.$cat_id]);						
					}

		

										
					//	Update cat data
					$sql_statement = 'UPDATE sweeps_categories SET' 
					
														.' seq = '. $seq
														.  $parent_sql
														.' WHERE cat_id = '.$cat_id
														;
//echo '<li>'.$sql_statement;				
					UpdateDB ($sql_statement, $mysql_err_msg);
					

					
				}
				
				else
				{
					DeleteCategory ($cat_id);								
				}
				
				if (count($_POST['cat_pos_array_'.$cat_id]) > 0 OR $_POST['cat_pos_array_'.$cat_id] != '')
				{
					UpdateAllCategories($cat_id);
				}
																
			}
			
		}	
	
	}

	
	function DeleteCategory ($cat_id)
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
		
		// get catagory name, parent and image file	
		$mysql_err_msg = 'unable to get info on the Category';
		$sql_statement = 'SELECT cat_name, parent_id, image_file FROM sweeps_categories WHERE cat_id = "'.$cat_id.'"';
		$cat_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
		$cat_name = $cat_info['cat_name'];
		$parent_id = $cat_info['parent_id'];
		$image_file = $cat_info['image_file'];
		
		//	remove entry from db
		$mysql_err_msg = 'unable to DELETE record of Category';
			
		//	delete from category table
		$sql_statement = 'DELETE FROM sweeps_categories WHERE cat_id = "'.$cat_id.'"';					
		UpdateDB ($sql_statement, $mysql_err_msg);
		
		//	delete product assignment
		$sql_statement = 'DELETE FROM sweeps_cat_asign WHERE cat_id = "'.$cat_id.'"';		
		UpdateDB ($sql_statement, $mysql_err_msg);

		//	Update Child Cats Parents - (grand parents adopt grand-children)
		$sql_statement = 'UPDATE sweeps_categories SET parent_id = "'.$parent_id.'" WHERE parent_id = '.$cat_id;	
		UpdateDB ($sql_statement, $mysql_err_msg);

		//	remove image file..
		$img_file_path = '../../_images_shop/'.$image_file;
		if (file_exists($img_file_path) AND $image_file != '')
		{	
			unlink($img_file_path);						
		}
		
		//	check for errors and print MSG
		if($error == FALSE)
		{
			$update_success_msg .= ' - Category: '.$cat_name.' Succesfully deleted \n';
			$_SESSION['update_success_msg'] = $update_success_msg;
		}
		else
		{
			$update_error_msg .= ' - A Database Error occured \n';
			$_SESSION['update_error_msg'] = $update_error_msg;
		}	
	
	}

	function AdjustParentIDs ($cat_id, $old_parent_id, $new_parent_id)
	{
		
		//	Get all existing IDs for Children and grandChilden etc  of this cat
		$children = FillTheCatList($cat_id, 0);
		
		$update_req = FALSE;
		foreach ($children as $childs)
		{
			//	if this cat is moved to a child or grand-child, an 'adoption' is required
			if($new_parent_id == $childs[$cat_id])
			{
				$move_req = TRUE;
			}
		}
		
		if (isset($move_req) AND $move_req == TRUE)
		{
			$sql_statement = 'SELECT cat_id FROM sweeps_categories WHERE parent_id = "'.$cat_id.'"';
			$child_result = ReadDB ($sql_statement, $mysql_err_msg);

			while ($children = mysql_fetch_array($child_result))
			{
				// update or imediate children's parent to the moving cat's orginal parent (grand parents now adopt !!)
				$sql_statement = 'UPDATE sweeps_categories SET parent_id = "'.$old_parent_id.'" WHERE cat_id = '.$children['cat_id'];			
				UpdateDB ($sql_statement, $mysql_err_msg);	
			}
			
		}

	}
	
?>