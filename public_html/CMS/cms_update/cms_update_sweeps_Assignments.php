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

	
if (isset($_SESSION['access']) AND $_SESSION['access'] < 5 )
{		

	//	Remove a Product
	if ( isset($_GET['remove_prod']) AND $_GET['remove_prod'] != '')
	{
		//	remove entry from db
		$mysql_err_msg = 'unable to DELETE record of '.SHOP_ITEM_ALIAS.'';
		$sql_statement = 'DELETE FROM sweeps_cat_asign WHERE prod_id = "'.$_GET['remove_prod'].'"';					
		
		if(!UpdateDB ($sql_statement, $mysql_err_msg))
		{
			$_SESSION['update_error_msg'] = ' - Database Error: Could not remove '.SHOP_ITEM_ALIAS.' \n';
		}

		$return_url = '../cms_edit_mod_data.php?e='.$_GET['e'].'&tab='.$_GET['tab'].'&cat_id='.$_GET['cat_id'];
				
	}
	
	//	Add a Product
	if ( isset($_POST['submit_add_prod']))
	{
		if ($_POST['add_item'] > 0)
		{
			foreach($_POST['add_item'] as $seq => $item_id)
			{
				if ($item_id != '')
				{
					$sql_statement = 'INSERT INTO sweeps_cat_asign SET' 
								
																	.'  cat_id = '.$_POST['cat_id']
																	.', item_id = '.$item_id
																	.', seq = '.$seq;														
																	;
						
					if(!UpdateDB ($sql_statement, $mysql_err_msg))
					{
						$_SESSION['update_error_msg'] = ' - Database Error: Could not Add '.SHOP_ITEM_ALIAS.' \n';
					}
					
				}
			
			}
				
					
			$_SESSION['update_success_msg'] = "Update Succesfull";
			
		}
	
		$return_url = $_POST['return_url'];
		
	}
	
	
	//	Update if form submited
	if ( isset($_POST['update_all']))
	{
		//	Update ALL info
		$mysql_err_msg = 'Up-dating '.SHOP_ITEM_ALIAS.' Assignment info';

		//	Update Product assignment order and data: (if there are assignments set)
		if (count($_POST['prod_pos_array']) > 0 OR $_POST['prod_pos_array'] != '')	
		{
			$prod_pos_array = array_flip (explode ( ',' , $_POST['prod_pos_array']));		
			foreach ($prod_pos_array as $prod_id => $seq )
			{
				$seq = $seq + 1;
				//if (!isset($_POST['delete_'.$prod_id]))
				if (!in_array($prod_id, $_POST['delete']))
				{
					//	Update Item Image order
					$sql_statement = 'UPDATE sweeps_cat_asign SET' 
					
														.'  seq = '. $seq
														.', cat_id = '.$_POST['move_prod_'.$prod_id]
														.'  WHERE prod_id = '.$prod_id
														;
			

					
				}
				
				else
				{
					$mysql_err_msg = 'unable to DELETE record of '.SHOP_ITEM_ALIAS.'';
					$sql_statement = 'DELETE FROM sweeps_cat_asign WHERE prod_id = "'.$prod_id.'"';										
				}
				
				if(!UpdateDB ($sql_statement, $mysql_err_msg))
				{
					$_SESSION['update_error_msg'] = ' - Database Error: Could not update '.SHOP_ITEM_ALIAS.' assignments \n';
				}					
																
			}
			
		}
			
	
		$return_url = $_POST['return_url'];
		
	}

	//	update the .htacces File
	$error = UpdateHtaccesFile (HOME_PAGE_ID);

	//	update sitemap.xml file
	$error .= UpdateSiteMapFile (HOME_PAGE_ID);

	
	if (isset($_SESSION['update_success_msg'])) 
	{$_SESSION['update_success_msg'] = $_SESSION['update_success_msg'];}
	else
	{$_SESSION['update_success_msg'] = '';}
		
	if (isset($_SESSION['update_error_msg'])) 
	{$_SESSION['update_error_msg'] = $_SESSION['update_error_msg'];}
	else
	{$_SESSION['update_error_msg'] = '';}	
	
	
	//	check for errors and print MSG
	if($error == FALSE)
	{
		$_SESSION['update_success_msg'] .= "( Update Succesfull ) \n";
	}
	else
	{
		$_SESSION['update_error_msg'] .= $error;
	}		
		
}

else
{
	
	$_SESSION['update_error_msg'] .= '- Insufficient Privileges to Modify Data \n';
	
}


	//	Re-Direct BACK
	header('location: '.$return_url); 
	exit();	
	
?>