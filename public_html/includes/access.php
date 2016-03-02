<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		$enter_username = '';
		$enter_password = '';

	//	get User's rights to view the page
	function UserPageAccess ($page_id)
	{
		//	Now check page access for this user
		$mysql_err_msg = 'Retrieving User&#38;s Page access Info';	
		$sql_statement = 'SELECT access_right FROM user_page_access WHERE user_id = "'.$_SESSION['user_id'].'"'
										
																	.' AND page_id = "'.$page_id.'" '
																	;

		$page_access_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
				
		//if ($page_access_info['access_right'] > 0)
		//{ return true; }
		
		return $page_access_info['access_right'];
		
	}


// Process CMS login attempt

	if (isset ($_POST['cms_login']))
	{
		$enter_username = trim($_POST['cms_username']);
		$enter_password = md5($_POST['cms_password']);
		
		if ($enter_username == "" OR $enter_password == "") { $cms_login_error_msg = "No User Name or Password entered"; }
		
		//	Check that user dosent just refresh an already entered POSTED username and password to gain access
		elseif ($_POST['login_key'] != $_SESSION ['login_key']) 		
		{ $cms_login_error_msg = "Please enter your Username and Password to gain access"; }
	
		else
		{
			//--------Get user info from db:		
			$mysql_err_msg = 'Retrieving User Account Info';	
			$sql_statement = 'SELECT user_id, active, username, password, email, account_id, login_with_email, expire_time'
			
														.' FROM user_accounts'
														.' WHERE username = "'.$enter_username.'"'
														.' OR email = "'.$enter_username.'"'		
														;

			$user_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			
			//	get Access code from db
			$sql_statement = 'SELECT access_code FROM _cms_user_access_types WHERE account_id = "'.$user_info['account_id'].'"';
			$access_code_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			$access_code = $access_code_info['access_code'];
			
			//	is entered username VALID
			if ( ($enter_username == $user_info['username'])
			
				OR 
				
				($user_info['login_with_email'] == "on" AND $enter_username == $user_info['email'])	//	can enter email as username 
			
			)
			
			{
				// User good, check if account active	
				if ($user_info['active'] == 'on')
				{			
								
					//	Now check password
					if ($enter_password == trim($user_info['password'])) 							
					{
					
						//	Account is Active and Password good ...now Check User's Admin access Rights
						if ( $access_code < 10 )
						{
							
							//	Check that CMS is not shutdown...Super admins can still access
							if (! SITE_CMS_SHUTDOWN OR $access_code < 2)
							{
								//	All Good !!!
								
								$_SESSION['CMS_authorized'] = TRUE;
								$_SESSION['CMS_mode'] = TRUE;
								$_SESSION['authorized'] = TRUE;
								$_SESSION['access'] = $access_code;
								$_SESSION['expire_time'] = $user_info['expire_time'];					
								$_SESSION['user_id'] = $user_info['user_id'];	//--------used for "update user account info" page
								$_SESSION['last_activity'] = time();
				
								//--------Record Last login to db:		
								$mysql_err_msg = 'Recording User Account Last Log-in';	
								$sql_statement = 'UPDATE 1_user_logins SET' 
																			.' last_login = "'.date("Y-m-d H:i:s").'"'
																			.',login_from_ip = "'.$_SERVER['REMOTE_ADDR'].'"'
																			.' WHERE user_id = "'.$user_info['user_id'].'"'		
																			;

								ReadDB ($sql_statement, $mysql_err_msg);	//	'Use ReadDB' instead of 'UpdateDB' 
																			//	to stop db update recording when use logs in or out
								
								$cms_login_error_msg = FALSE;
								
								//	now redirect to prevent user logging back in by refreshing page
								if(!empty($_SERVER['QUERY_STRING'])) {$query_str = '?'.$_SERVER['QUERY_STRING'];}
								
								header("location: ".$_SERVER['PHP_SELF'].$query_str); 

								exit;
								
							}
							
							else { $cms_login_error_msg = "The Admin Area is temporarily unavailable due to maintenance</br>
															Please check back in a few minutes..."; }
							
						}
						
						else { $cms_login_error_msg = "insufficient access rights to Admin Area"; }	
						
					}

					else { $cms_login_error_msg = "Password incorrect"; }	
					
				}
				
				else { $cms_login_error_msg = "This Login Account is currently NOT Active"; }
				
			}
			
			else { $cms_login_error_msg = "No User Found with that User Name"; }

		}

	}
	else
	{
		$cms_login_error_msg = FALSE;
	}

// Process normal login attempt
	$normal_login_error_msg = '';
	
	if (isset ($_POST['normal_login']))
	{
		$enter_username = trim($_POST['username']);
		$enter_password = md5($_POST['password']);
		
		if ($enter_username == "" OR $enter_password == "") { $normal_login_error_msg = "No User Name or Password entered"; }
		
		//	Check that user dosent just refresh an already entered POSTED username and password to gain access
		//elseif ($_POST['login_key'] != $_SESSION ['login_key']) 		
		//{ $normal_login_error_msg = "Please enter your Username and Password to gain access"; }
	
		else
		{
			//--------Get user info from db:		
			$mysql_err_msg = 'Retrieving User Account Info';	
			$sql_statement = 'SELECT user_id, active, username, password, email, account_id, login_with_email, expire_time'
			
														.' FROM user_accounts'
														.' WHERE username = "'.$enter_username.'"'
														.' OR email = "'.$enter_username.'"'		
														;

			$user_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			
			//	get Access code from db
			$sql_statement = 'SELECT access_code FROM _cms_user_access_types WHERE account_id = "'.$user_info['account_id'].'"';
			$access_code_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			$access_code = $access_code_info['access_code'];
			
			//	is entered username VALID
			if ( ($enter_username == trim($user_info['username']))
			
				OR 
				
				($user_info['login_with_email'] == "on" AND $enter_username == $user_info['email'])	//	can enter email as username 
			
			)
			
			{
					
				// User good, check if account active	
				if ($user_info['active'] == 'on')
				{			
					
					//	Now check password
					if ($enter_password == trim($user_info['password']))								
					{
						//	User and password all good...
						$_SESSION['authorized'] = TRUE;
						$_SESSION['access'] = $access_code;
						$_SESSION['expire_time'] = $user_info['expire_time'];					
						$_SESSION['user_id'] = $user_info['user_id'];	//--------used for "update user account info" page
						$_SESSION['last_activity'] = time();
						
		
						//--------Record Last login to db:		
						$mysql_err_msg = 'Recording User Account Last Log-in';	
						$sql_statement = 'UPDATE 1_user_logins SET' 
																	.' last_login = "'.date("Y-m-d H:i:s").'"'
																	.',login_from_ip = "'.$_SERVER['REMOTE_ADDR'].'"'
																	.' WHERE user_id = "'.$user_info['user_id'].'"'		
																	;
						ReadDB ($sql_statement, $mysql_err_msg);	//	'Use ReadDB' instead of 'UpdateDB' 
																	//	to stop db update recording when use logs in or out
						
						//	now redirect to prevent user logging back in by refreshing page
						if(!empty($_SERVER['QUERY_STRING'])) {$query_str = '?'.$_SERVER['QUERY_STRING'];}
						
						header("location: ".$_SERVER['PHP_SELF'].$query_str); 

						exit;
							
					}
					
					else { $normal_login_error_msg = "Password incorrect"; }
					
				}
				
				else { $cms_login_error_msg = "This Login Account is currently NOT Active"; }					
				
			}
			
			else { $normal_login_error_msg = "No User Found with that User Name"; }

		}

	}	
	
	
	
//$_SESSION['authorized'] = TRUE;	//	<<<<<----------------------========<<<<<<<<<<<<<<<<<<<<<<<	Bypass password

	//------------------Process logout
	if (isset($_REQUEST['logout'])) 		
	{
		KillLoginInfo ();

		//	now refresh page and drop the "&logout=1" to stop user not being able to log back in again from this page
		$query_str = str_replace ('&logout=1', '', $_SERVER['QUERY_STRING']);
			
		$href = $_SERVER['PHP_SELF'].'?'.$query_str;
		header("location: ".$href); 
		
		exit();
			
	}

	//------------------Process Exit Admin
	if (isset($_REQUEST['exitadmin'])) 		
	{
		unset($_SESSION['CMS_mode']);
		unset($_SESSION['load_admin']);
	
		//	now refresh page and drop the "&exitadmin=1" to stop user not being able to log back in again from this page
		$query_str = str_replace ('&exitadmin=1', '', $_SERVER['QUERY_STRING']);
			
		$href = $_SERVER['PHP_SELF'].'?'.$query_str;
		header("location: ".$href); 
		
		exit();
			
	}	
	
	//	Auto Log-out after set time
	if (isset($_SESSION['expire_time']) AND $_SESSION['expire_time'] != 0 AND $_SESSION['expire_time'] != '')
	{
		if (isset($_SESSION['last_activity']) AND (time() - $_SESSION['last_activity'] > $_SESSION['expire_time'] * 60)) 
		{ 
			KillLoginInfo ();
		}

	}
	
	// update last activity time stamp 
	$_SESSION['last_activity'] = time(); 	
?>