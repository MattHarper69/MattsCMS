<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );
	
	$file_path_offset = '../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	require_once ('cms_update_common.php');

	//	set local vars - avoid errors
	if (!isset($_SESSION['update_success_msg']))
	{
		$_SESSION['update_success_msg'] = '';
	}	

	if (!isset($_SESSION['update_error_msg']))
	{
		$_SESSION['update_error_msg'] = '';
	}
	
	
	if (isset($_SESSION['access']) AND $_SESSION['access'] < 5 )
	{	

		$error = FALSE;	

		//	Update All setings
		if ( isset($_POST['synchronization_update']))
		{

			$syncs = array();
			if (isset($_POST['syncs']))
			{
				$syncs = $_POST['syncs'];
			}
			else
			{
				$syncs[] = NULL;	
			}
	
			
			//	get sync id of sellected Gallery for new sync id
			$mysql_err_msg = 'Up-dating Photo Gallery Synchronization Settings';
			$sql_statement = 'SELECT sync_id FROM modules WHERE mod_id = '.$_POST['mod_id'];
			$sync_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			$current_sync_id = $sync_info['sync_id'];
			
			if (!isset($current_sync_id) OR $current_sync_id == 0)
			{
				//	get next sync_id from modules table to use as new sync id
				$sql_statement = 'SELECT MAX(sync_id) FROM modules';
				$new_sync_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));					
			
				$new_sync_id = $new_sync_info[0] + 1;
		
			}
			else
			{
				$new_sync_id = $current_sync_id;
			}


			//	cycle through all mods
			foreach ($_POST['all_mod_ids'] as $update_mod_id)
			{			
				
				//	is this the Gallery Selected ?
				if ($update_mod_id == $_POST['mod_id'])
				{
					// have all Galleries been un-synced
					if ($syncs[0] == NULL)
					{
						$set_new_sync_id = 0;
			
					}						
					else
					{
						$set_new_sync_id = $new_sync_id;
					}
					
				}
				
				//	all other Galleries
				else
				{
					//	Is mod id synced (checked)
					if(in_array($update_mod_id, $syncs))
					{				
						$set_new_sync_id = $new_sync_id;
					}

					//	need to reset syncs that are no longer synced (unchecked)				
					else
					{
						if($_POST['old_sync_mod_'.$update_mod_id] AND $_POST['old_sync_mod_'.$update_mod_id] != $new_sync_id)
						{
							$set_new_sync_id = $_POST['old_sync_mod_'.$update_mod_id];										
						}
						
						else
						{
							$set_new_sync_id = 0;					
						}				
					
					}
					
				}

				$sql_statement = 'UPDATE modules SET sync_id = "'.$set_new_sync_id.'" WHERE mod_id = "'.$update_mod_id.'"';	
					
				if(!UpdateDB ($sql_statement, $mysql_err_msg))
				{
					$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
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