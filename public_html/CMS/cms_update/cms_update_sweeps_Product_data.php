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

	//------------set default time zone
	date_default_timezone_set(SHOP_TIME_ZONE);
			
			//	set local vars - avoid errors
			if (!isset($_SESSION['update_success_msg']))
			{
				$_SESSION['update_success_msg'] = '';
			}
			if (!isset($error))
			{
				$error = '';
			}			
			
			if (isset($_POST['active']) AND $_POST['active'] == 'on')
			{$active = 'on';}
			else	{$active = '';}

			if (isset($_POST['display_image']) AND $_POST['display_image'] == 'on')
			{$display_image = 'on';}
			else	{$display_image = '';}	

			if (isset($_POST['display_buynow']) AND $_POST['display_buynow'] == 'on')
			{$display_buynow = 'on';}
			else	{$display_buynow = '';}
	
			if (isset($_POST['display_rating']) AND $_POST['display_rating'] == 'on')
			{$display_rating = 'on';}
			else	{$display_rating = '';}

			if (isset($_POST['display_instock']) AND $_POST['display_instock'] == 'on')
			{$display_instock = 'on';}
			else	{$display_instock = '';}

			if (isset($_POST['promo_display']) AND $_POST['promo_display'] == 'on')
			{$promo_display = 'on';}
			else	{$promo_display = '';}

			if (isset($_POST['display_list_price']) AND $_POST['display_list_price'] == 'on')
			{$display_list_price = 'on';}
			else	{$display_list_price = '';}
						
			if (isset($_POST['primary_image_id']))
			{
				$primary_image_id = $_POST['primary_image_id'];
			}
			else{$primary_image_id = '0';}
			

	if (isset($_SESSION['access']) AND $_SESSION['access'] < 5 )
	{		
		
		//print_r ($_POST);

		//	Delete this Item
		if ( isset($_POST['submit_delete_item']))
		{
			DeleteItem ($_POST['item_id']);
		}
		
		//	Update if form sbmited
		if ( isset($_POST['update_all']))
		{
			//	Update ALL info
			$mysql_err_msg = 'Up-dating '.SHOP_ITEM_ALIAS.' info';

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
				$sql_2 = ' WHERE item_id = "'.$_POST['item_id'].'"';			
			}	
			
			


			//	need to strip non-numbers
			$_POST['price'] = str_replace(',', '',$_POST['price']);
			$_POST['price'] = preg_replace("/[a-zA-Z]/", '', $_POST['price']);
			$_POST['list_price'] = str_replace(',', '',$_POST['list_price']);
			$_POST['list_price'] = preg_replace("/[a-zA-Z]/", '', $_POST['list_price']);			
			$_POST['in_stock'] = preg_replace('/\D/', '',$_POST['in_stock']);
			$_POST['max_quantity_allow'] = preg_replace('/\D/', '',$_POST['max_quantity_allow']);
			
	//	===========================	EDITED TO HERE	=======================================================================

			$promo_sql = '';		
			for ($count = 1; $count < 5; $count++)
			{
				$promo_price = str_replace(',', '',$_POST['promo_price_'.$count]);
				$promo_price = preg_replace("/[a-zA-Z]/", '', $promo_price);
				$promo_sql .= ', promo_price_'.$count.' = "'.$promo_price.'"';
				$promo_sql .= ', promo_code_'.$count.' = "'.$_POST['promo_code_'.$count].'"';
			}

			//	process dates and times
			
			$event_date = date("Y-m-d",strtotime($_POST['event_date']));
			
			$event_start_date = date("Y-m-d",strtotime($_POST['event_start_date']));
			$event_start_time = date("H:i",strtotime($event_start_date.' '.$_POST['event_start_time']));
			$event_start = $event_start_date.' '.$event_start_time . ':00';	

			$event_close_date = date("Y-m-d",strtotime($_POST['event_close_date']));
			$event_close_time = date("H:i",strtotime($event_close_date.' '.$_POST['event_close_time']));
			$event_close = $event_close_date.' '.$event_close_time . ':00';				
				
			$sql_statement = $sql_1.' sweeps_items SET'
			
									.'  item_name = "'.$_POST['item_name'].'"'
									.', mod_id = "'.$_REQUEST['mod_id'].'"'
									.', item_code = "'.$_POST['item_code'].'"'
									.', url_alias = "'.$_POST['url_alias'].'"'
									.', primary_cat_id = "'.$_POST['primary_cat_id'].'"'
									.', active = "'.$active.'"'
									.', brief = "'.$_POST['brief'].'"'
									.', description = "'.$_POST['description'].'"'
									.', primary_image_id = "'.$primary_image_id.'"'
									.', display_image = "'.$display_image.'"'
									.', display_buynow = "'.$display_buynow.'"'
									.', display_rating = "'.$display_rating.'"'
									.', display_instock = "'.$display_instock.'"'
									.', in_stock = "'.$_POST['in_stock'].'"'
									.', votes = "'.$_POST['votes'].'"'
									.', price = "'.$_POST['price'].'"'
									.', list_price = "'.$_POST['list_price'].'"'
									.', display_list_price = "'.$display_list_price.'"'
									.', max_quantity_allow = "'.$_POST['max_quantity_allow'].'"'
									.   $promo_sql
									.', promo_display = "'.$promo_display.'"'
									.', event_date = "'.$event_date.'"'
									.', event_start_date = "'.$event_start.'"'
									.', event_close_date = "'.$event_close.'"'
									.', ticket_start = "'.$_POST['ticket_start'].'"'
									.', num_qualifiers = "'.$_POST['num_qualifiers'].'"'
									.', starters_per_qualifier = "'.$_POST['starters_per_qualifier'].'"'
	//	===========================	EDITED TO HERE	=======================================================================				
									.   $sql_2	
									;
			if(!ReadDB ($sql_statement, $mysql_err_msg))	// Use 'ReadDB' instead of 'UpdateDB' to avoid conflicting the following mysql_insert_id
			{
				$_SESSION['update_error_msg'] = ' - Database Error: Could not Update / Add '.SHOP_ITEM_ALIAS.' \n';
			}

			$new_item_id = mysql_insert_id();	
			
			if(isset($_POST['update_action']) AND $_POST['update_action'] == 'new')
			{
				$item_id = $new_item_id;
			}
			else
			{
				$item_id = $_POST['item_id'];
			}
			
			//	Update adjust Rating
			if (isset($_POST['rating']) AND $_POST['rating'] != 'dontchange')
			{
				$sql_statement = 'UPDATE sweeps_items SET rating = "'.$_POST['rating'].'" WHERE item_id = "'.$item_id.'"';
				if(!UpdateDB ($sql_statement, $mysql_err_msg))
				{
					$_SESSION['update_error_msg'] = ' - Database Error: Could not Adjust '.SHOP_ITEM_ALIAS.'s Rating \n';
				}			
			}
				
			//	Update an assign a cat and set primary cat id
			if (isset($_POST['asign_a_cat']) AND $_POST['asign_a_cat'] != '' AND $_POST['asign_a_cat'] != 0)
			{					

				$sql_statement = 'INSERT INTO sweeps_cat_asign SET cat_id = "'.$_POST['asign_a_cat'].'", item_id = "'.$item_id.'"';
				if(!UpdateDB ($sql_statement, $mysql_err_msg))
				{
					$_SESSION['update_error_msg'] = ' - Database Error: Could not assign '.SHOP_ITEM_ALIAS.' to a Category \n';
				}
				$sql_statement = 'UPDATE sweeps_items SET primary_cat_id = "'.$_POST['asign_a_cat'].'" WHERE item_id = "'.$item_id.'"';
				if(!UpdateDB ($sql_statement, $mysql_err_msg))
				{
					$_SESSION['update_error_msg'] = ' - Database Error: Could not assign '.SHOP_ITEM_ALIAS.' to a Category \n';
				}				
			}

			
			//	Update Item Image order OR delete image: (if there are images set)
			if (isset($_POST['image_pos_array']) AND (count($_POST['image_pos_array']) > 0 OR $_POST['image_pos_array'] != ''))	
			{
				$image_pos_array = array_flip (explode ( ',' , $_POST['image_pos_array']));		
				foreach ($image_pos_array as $image_id => $seq )
				{
					$seq = $seq + 1;
					if (!isset($_POST['delete_image_'.$image_id]))
					{
						//	Update Item Image order
						$sql_statement = 'UPDATE sweeps_item_images SET seq = '. $seq .' WHERE image_id = '.$image_id;
						UpdateDB ($sql_statement, $mysql_err_msg);
					}
					
					else
					{
						DeleteItemImage($image_id);
					}
																				
				}
				
			}
			
			//	update Country and State Restrictions	========================================================
			
			//	add Countries
			if (isset($_POST['add_countries']) AND $_POST['add_countries'] > 0)
			{
				foreach($_POST['add_countries'] as $country_id)
				{
					if ($country_id != '')
					{
						$sql_statement = 'INSERT INTO sweeps_item_location_restrictions SET' 
									
																		.'  country_id = "'.$country_id.'"'
																		.', item_id = '.$item_id													
																		;
							
						if(!UpdateDB ($sql_statement, $mysql_err_msg))
						{
							$_SESSION['update_error_msg'] = ' - Database Error: Could not Add Country to Enabled list \n';
						}
						
					}
					
					//	do auto insertion on states for selected countries
					if (isset($_POST['load_all_countries_states']) AND $_POST['load_all_countries_states'] == 'on')
					{
						//	get states from db
						$mysql_err_msg = 'State Address Infomation unavailable';	
						$sql_statement = 'SELECT state_id FROM shop_address_states'

																.' WHERE country_id = "'.$country_id.'"'
																;					
			
						$get_cunt_states_result = ReadDB ($sql_statement, $mysql_err_msg);

						while ($all_cunt_states = mysql_fetch_array ($get_cunt_states_result))
						{						
							$sql_statement = 'INSERT INTO sweeps_item_location_restrictions SET' 
										
																			.'  state_id = "'.$all_cunt_states['state_id'].'"'
																			.', item_id = "'.$item_id.'"'						
																			;
								
							if(!UpdateDB ($sql_statement, $mysql_err_msg))
							{
								$_SESSION['update_error_msg'] = ' - Database Error: Could not Add State to Enabled list \n';
							}							
						}
						
					}
								
				}
				
			}			
		
			//	add States
			if (isset($_POST['add_states']) AND $_POST['add_states'] > 0)
			{
				foreach($_POST['add_states'] as $state_id)
				{
					if ($state_id != '')
					{
						$sql_statement = 'INSERT INTO sweeps_item_location_restrictions SET' 
									
																		.'  state_id = "'.$state_id.'"'
																		.', item_id = '.$item_id													
																		;
							
						if(!UpdateDB ($sql_statement, $mysql_err_msg))
						{
							$_SESSION['update_error_msg'] = ' - Database Error: Could not Add State to Enabled list \n';
						}
						
					}
				
				}

			}		
			
			//	Delete Countries
			if ($_POST['num_cunts'] > 0 AND $_POST['num_cunts'] != '')	
			{	
				for ($count = 0; $count < $_POST['num_cunts']; $count++ )
				{
					if (isset($_POST['remove_country_'.$count]))
					{						
						$mysql_err_msg = 'unable to REMOVE Country from Enabled list';
						$sql_statement = 'DELETE FROM sweeps_item_location_restrictions WHERE item_id = "'.$item_id.'"'
						
																				.' AND country_id = "'.$_POST['country_id_'.$count].'"'
																				;						
						
						if(!UpdateDB ($sql_statement, $mysql_err_msg))
						{
							$_SESSION['update_error_msg'] = ' - Database Error: Could not REMOVE Country from Enabled list \n';
						}
						
						//	remove all associated states
						$mysql_err_msg = 'State Address Infomation unavailable';	
						$sql_statement = 'SELECT state_id FROM shop_address_states'

																.' WHERE country_id = "'.$_POST['country_id_'.$count].'"'
																;					
			
						$get_cunt_states_result = ReadDB ($sql_statement, $mysql_err_msg);

						while ($all_cunt_states = mysql_fetch_array ($get_cunt_states_result))
						{						
							$sql_statement = 'DELETE FROM sweeps_item_location_restrictions WHERE item_id = "'.$item_id.'"'
						
																				.' AND state_id = "'.$all_cunt_states['state_id'].'"'
																				;	
								
							if(!UpdateDB ($sql_statement, $mysql_err_msg))
							{
								$_SESSION['update_error_msg'] = ' - Database Error: Could not remove State from Enabled list \n';
							}							
						}
						
					}
																									
				}
				
			}			
						
			//	Delete States
			if ($_POST['num_states'] > 0 AND $_POST['num_states'] != '')	
			{	
				for ($count = 0; $count < $_POST['num_states']; $count++ )
				{
					if (isset($_POST['remove_state_'.$count]))
					{						
						$mysql_err_msg = 'unable to REMOVE State from Enabled list';
						$sql_statement = 'DELETE FROM sweeps_item_location_restrictions WHERE item_id = "'.$item_id.'"'
						
																				.' AND state_id = "'.$_POST['state_id_'.$count].'"'
																				;						
						
						if(!UpdateDB ($sql_statement, $mysql_err_msg))
						{
							$_SESSION['update_error_msg'] = ' - Database Error: Could not REMOVE State from Enabled list \n';
						}				
					
					}
													
																	
				}
				
			}


			
			//	insert entries for cat_asign and item_images
			if(isset($_POST['update_action']) AND $_POST['update_action'] == 'new')		
			{
	//	===========================	EDITED TO HERE	=======================================================================				
			}

			//	update the .htacces File
			$error = UpdateHtaccesFile (HOME_PAGE_ID);

			//	update sitemap.xml file
			$error .= UpdateSiteMapFile (HOME_PAGE_ID);	

	
			$_SESSION['update_success_msg'] = "Update Succesfull";
			
		}
		
		//	image upload
		if (isset($_POST['submit_upload_item_images']) AND $_FILES['upload_item_images']['name'][0] != '')
		{
		
			//	determin next file name
			$mysql_err_msg = 'unable to get '.SHOP_ITEM_ALIAS.' Image info for uploading new image file';
			$sql_statement = 'SELECT image_file_name '

										.' FROM sweeps_item_images'
										.' ORDER BY CAST (image_file_name as SIGNED INTEGER) DESC'
										;
									
			$filename_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			$last_filename = $filename_info['image_file_name'];	
			$last_filename = substr($last_filename, 0, -4);	// remove extension
			
			//	determin next seq
			$sql_statement = 'SELECT seq '

										.' FROM sweeps_item_images'
										.' WHERE item_id = "'.$_POST['item_id'].'"'
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
					
														.'  item_id = '.$_POST['item_id']
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
					if(SHOP_IMAGE_RESIZE_MODE AND ($width > SHOP_IMAGE_MAX_WIDTH OR $height > SHOP_IMAGE_MAX_HEIGHT))
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
/* 
			if (isset($_POST['image_pos_array']))
			{
				$post_image_pos_array = $_POST['image_pos_array'];
			}
			else {$post_image_pos_array = '';}
*/


			//	if no existing Primary image id set, use first uploaded image
			if ($primary_image_id == NULL OR $primary_image_id == 0)
			{							
					
				$sql_statement = 'UPDATE sweeps_items SET primary_image_id = "'.$new_primary_image_id.'" WHERE item_id = "'.$_POST['item_id'].'"';
											
				UpdateDB ($sql_statement, $mysql_err_msg);											
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
		
	
	

	
					
	//	===========================	EDITED TO HERE	=======================================================================			
			

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
		$return_item_id = $new_item_id;	
	}
	
	elseif ( isset($_POST['submit_delete_item']))
	{
		$return_item_id = NULL;
	}		
	
	
	else
	{
		$return_item_id = $_POST['item_id'];
	}		
		

	//	return to correct tab
	$tab = $_POST['tab'];

	//	Re-Direct BACK
	$return_url = $_POST['return_url'].'&item_id='.$return_item_id . '&tab=' . $tab;
	header('location: '.$return_url); 
	exit();	

	
?>