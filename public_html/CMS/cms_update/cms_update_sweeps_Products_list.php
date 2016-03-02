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

	for ($count = 1; $count <= $_POST['num_records']; $count++)
	{
		//	Delete individual Item
		if (isset($_POST['submit_delete_item_'.$count]))
		{					
			DeleteItem($_POST['item_id_'.$count]);					
		}
		
		//	Update ALL
		elseif ( isset($_POST['update_all']))
		{
			
			//	Delete
			if (!empty($_POST['delete']) AND in_array( $_POST['item_id_'.$count], $_POST['delete']))
			{
				DeleteItem($_POST['item_id_'.$count]);			
			}
			
			//	OR Update
			else
			{
				
				$mysql_err_msg = 'Up-dating '.SHOP_ITEM_ALIAS.' info';
				
				// -----	check the status of 'ACTIVE' check boxes (they are not sent if not ticked)
				
				//	TICKED and sent in active array -- avoid errors by checking that array is not empty first
				if ( !empty ($_POST['active']) )
				{ 
					if (in_array( $_POST['item_id_'.$count], $_POST['active']) ) 
					{ $active = 'on';} 
					else {$active = '';}
				}
					
				//	NOT TICKED
				else { $active = '';}
				
				$sql_statement = 'UPDATE sweeps_items SET'
				
									.' active = "'.$active.'"'
									.' WHERE item_id = "'.$_POST['item_id_'.$count].'"'	
									;
												
				UpdateDB ($sql_statement, $mysql_err_msg);		

				//	need to strip non-numbers
				$_POST['price_'.$count] = str_replace(',', '',$_POST['price_'.$count]);
				$_POST['price_'.$count] = preg_replace("/[a-zA-Z]/", '', $_POST['price_'.$count]);
				$_POST['in_stock_'.$count] = preg_replace('/\D/', '',$_POST['in_stock_'.$count]);	


				$sql_statement = 'UPDATE sweeps_items SET'
					
										.'  price = "'.$_POST['price_'.$count].'"'
										.', in_stock = "'.$_POST['in_stock_'.$count].'"'
										.'  WHERE item_id = "'.$_POST['item_id_'.$count].'"'	
										;
									
				if(!UpdateDB ($sql_statement, $mysql_err_msg))
				{
					$_SESSION['update_error_msg'] .= ' - A Database Error occured \n';
				}
				
			}				
		
		}		
		
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
	
}

else
{
	
	$_SESSION['update_error_msg'] .= ' - Insufficient Privileges to Modify Data \n';
	
}


	//	Re-Direct BACK
	header('location: '.$_POST['return_url']); 
	exit();	
	
?>