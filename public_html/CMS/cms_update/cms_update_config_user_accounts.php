<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	$file_path_offset = '../../';
	
					

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');

	require_once ($file_path_offset.'includes/access.php');

	//	set local vars - avoid errors
	if (!isset($_SESSION['update_success_msg']))
	{
		$_SESSION['update_success_msg'] = '';
	}	

	if (!isset($_SESSION['update_error_msg']))
	{
		$_SESSION['update_error_msg'] = '';
	}
	
	
if ($_SESSION['CMS_mode'] == TRUE AND $_SESSION['access'] < 4 )
{

	
	//	Update if form sbmited
	if ( isset($_REQUEST['update_all']))
	{

		//	Update ALL info
		$mysql_err_msg = 'Up-dating User Account info';

		for ($num = 1; $num <= $_POST['num_records']; $num++)
		{
			//	Do check boxes and selects first
			
			// -----	check the status of 'ACCOUNT ACTIVE' check boxes (they are not sent if not ticked)
			
			//	DISABLED - sent as Hidden Input
			if (isset($_POST['active_'.$num]) AND $_POST['active_'.$num] == 'on') { $active = 'on'; }
			
			//	TICKED and sent in active array -- avoid errors by checking that array is not empty first
			elseif ( !empty ($_POST['active']) )
			{ 
				if (in_array( $_POST['user_id_'.$num], $_POST['active']) ) 
				{ $active = 'on'; } 
				else { $active = '';}
			}
					
			//	NOT TICKED
			else { $active = '';}
			
			//	----	check the status of 'login_with_email' check boxes (they are not sent if not ticked)
			
			//	TICKED and sent in active array -- avoid errors by checking that array is no empty first
			if ( !empty ($_POST['login_with_email']) )
			{ 
				if (in_array( $_POST['user_id_'.$num], $_POST['login_with_email']) ) 
				{ $login_with_email = 'on'; }
				else { $login_with_email = ''; }
			}
			
			//	NOT TICKED
			else { $login_with_email = ''; }					
		
			$sql_statement = 'UPDATE user_accounts SET'
			
								.'  account_id = "'.$_POST['account_type_'.$num].'"'
								.', active = "'.$active.'"'
								.', login_with_email = "'.$login_with_email.'"'
								.' WHERE user_id = "'.$_POST['user_id_'.$num].'"'	
								;
													
			UpdateDB ($sql_statement, $mysql_err_msg);					

										
			//	 Now Take care of EMAIL  ===========================================
			
			//	remove all spaces at end and beginning
			$_POST['email_'.$num] = trim($_POST['email_'.$num]);
		
			//	Check Validation for Email
			if ( ValidateCMS (2, $_POST['email_'.$num], $_POST['existing_email_'.$num]) )
			{

				$sql_statement = 'UPDATE user_accounts SET'
					
										.' email = "'.htmlspecialchars ($_POST['email_'.$num]).'"'
										.' WHERE user_id = "'.$_POST['user_id_'.$num].'"'	
										;
															
				UpdateDB ($sql_statement, $mysql_err_msg);		
									
			}
							
						
		}	
	
		$_SESSION['update_success_msg'] = "Update Succesfull";
	

	}

	if (isset($_REQUEST['ResetLogOut']) AND isset($_REQUEST['user']))
	{
 		//--------Record Last Log-OUT to db:		
		$mysql_err_msg = 'Recording User Account Last Log-in';	
		$sql_statement = 'UPDATE 1_user_logins SET' 
													.' last_logout = "'.date("Y-m-d H:i:s").'"'
													.' WHERE user_id = "'.$_REQUEST['user'].'"'		
													;

		ReadDB ($sql_statement, $mysql_err_msg);	//	'Use ReadDB' instead of 'UpdateDB' to stop db update recording when use logs in or out

	}
	
	//	Delete a user
	if ( isset($_REQUEST['del']))
	{	
//	===========================	EDITED TO HERE	=======================================================================			
		
		// do warning - look at jQuery / javascript options ??
		


	}
	
	//	Reset password email	
	if ( isset($_REQUEST['reset']))
	{	
		
//	===========================	EDITED TO HERE	=======================================================================			
		
		// do warning - look at jQuery / javascript options ??	

	
	}	
	

}

else
{
	
	$_SESSION['update_error_msg'] .= '- Insufficient Privileges to Modify Data \n';
	
}


	//$return_url = '/index.php?p='.$_POST['page_id'];
	$return_url = '../cms_user_accounts.php';
	
	//	Re-Direct BACK
	header('location: '.$return_url); 
	exit();	
	
?>