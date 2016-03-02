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

		//	Get all mod_IDs for synching
		$sync_mod_ids_array = GetSyncModIDs($mod_id);

		$mysql_err_msg = 'Up-dating Category info';
		
		//	Update or add a Single Category	
		if ( isset($_POST['add_new_cat']))
		{
					
			//	get next seq from mod_photo_gal_cats table to use as new seq
			$mysql_err_msg = 'retrieving next category order info';
			$sql_statement = 'SELECT MAX(seq) FROM mod_photo_gal_cats, mod_photo_gal_mod_cat_asign'

												.' WHERE mod_photo_gal_mod_cat_asign.mod_id = '.$_POST['mod_id']
												.' AND   mod_photo_gal_mod_cat_asign.gal_cat_id = mod_photo_gal_cats.gal_cat_id'
												;
												
			$new_seq_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));					
		
			$new_seq = $new_seq_info[0] + 1;		

			$new_cat_name = ValidateName($_POST['new_cat_name'], 0);
		
			if ($new_cat_name)
			{
				// insert new cat into cat table
				$mysql_err_msg = 'inserting new category';
				$sql_statement = 'INSERT INTO mod_photo_gal_cats SET'
															
											.'  cat_name = "'.$new_cat_name.'"'
											.', active = "on"'
											.', seq = "'.$new_seq.'"'
											;				


				if(!ReadDB ($sql_statement, $mysql_err_msg))	// Use 'ReadDB' instead of 'UpdateDB' to avoid conflicting the following 
																//	mysql_insert_id
				{
					$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
				}
								
				$new_gal_cat_id = mysql_insert_id();
				
				// insert entry into cat-mod-asign table
				
				if (empty($sync_mod_ids_array))
				{
					// if not synced				
					$sql_statement = 'INSERT INTO mod_photo_gal_mod_cat_asign SET'
																
												.'  mod_id = "'.$mod_id.'"'
												.', gal_cat_id = '.$new_gal_cat_id;
												;				

					if(!UpdateDB ($sql_statement, $mysql_err_msg))
					{
						$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
					}				
					
				}
						
				else
				{
								
					foreach($sync_mod_ids_array as $sync_mod_id)	
					{
						//	if synced
						$sql_statement = 'INSERT INTO mod_photo_gal_mod_cat_asign SET'
																	
													.'  mod_id = "'.$sync_mod_id.'"'
													.', gal_cat_id = '.$new_gal_cat_id;
													;				

						if(!UpdateDB ($sql_statement, $mysql_err_msg))
						{
							$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
						}	
									
					}
					
				}
				
			}
						
		}

		
		//	Update all Cats from listing
		elseif ( isset($_POST['update_all_cats']))
		{
		
			//	Update All Categories
			if (count($_POST['cat_pos_array']) > 0 OR $_POST['cat_pos_array'] != '')	
			{
				$cat_pos_array = array_flip (explode ( ',' , $_POST['cat_pos_array']));		
				foreach ($cat_pos_array as $cat_id => $seq )
				{
					$seq = $seq + 1;
					
					//Delete Cat
					if (isset($_POST['delete']) AND in_array($cat_id, $_POST['delete']))
					{

						//	remove entry from db
						$mysql_err_msg = 'unable to DELETE record of Category';
							
						// Delete from Cat table
						$sql_statement = 'DELETE FROM mod_photo_gal_cats WHERE gal_cat_id = "'.$cat_id.'"';					
						
						if(!UpdateDB ($sql_statement, $mysql_err_msg))
						{
							$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
						}
						
						// Delete from Cat-Mod-asign table
						$sql_statement = 'DELETE FROM mod_photo_gal_mod_cat_asign WHERE gal_cat_id = "'.$cat_id.'"';					
						
						if(!UpdateDB ($sql_statement, $mysql_err_msg))
						{
							$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
						}					

						//	Remove ALL images from this Category
						$mysql_err_msg = 'image information unavailable';	
						$sql_statement = 'SELECT photo_id FROM mod_photo_gal_pics WHERE cat_id = "'.$cat_id.'"';
											
						$image_info_result = ReadDB ($sql_statement, $mysql_err_msg);

						while ($image_info = mysql_fetch_array ($image_info_result))
						{
							$image_id = $image_info['photo_id'];
							DeletePhoto($image_id);
						}
											
					}
					
					// Or Update
					else
					{
						//	get active statis
						if (in_array($cat_id, $_POST['active']))
						{
							$active = 'on';
						}
						else
						{
							$active = '';
						}
						
						$new_cat_name = ValidateName($_POST['cat_name_'.$cat_id], $cat_id);
						if ($new_cat_name)
						{					
							$sql_statement = 'UPDATE mod_photo_gal_cats SET' 
							
																.' cat_name = "'.$new_cat_name.'"'

																.', active = "'.$active.'"'
																.', seq = '. $seq														
																.' WHERE gal_cat_id = '.$cat_id
																;					
					
							if(!UpdateDB ($sql_statement, $mysql_err_msg))
							{
								$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
							}
						
						}
															
					}				
																
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