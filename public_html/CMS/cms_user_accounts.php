<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

		
	$this_page = $_SERVER['PHP_SELF'];	
	$file_path_offset = '../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');

	require_once ($file_path_offset.'includes/access.php');
	
if (isset($_SESSION['access']) AND $_SESSION['access'] < 3 )
{		
	

	//	SHUTDOWN msg
	if (SITE_SHUTDOWN == 1)
	{
		echo TAB_4.'<div class="UpdateMsgDiv">'."\n";	
			echo TAB_5.'<p class = "WarningMSG" >The Site is currently SHUT DOWN - Go to Global Settings to Re-Activate</p>'."\n";
		echo TAB_4.'</div>'."\n";			
	}
	
	require_once ('cms_includes/cms_head.php');

	echo '<body>'." \n";
	
	echo TAB_3.'<div class="CMS_Heading" >'." \n";
		echo TAB_4.'<h1>'." \n";
		
			//	REFRESH button				
			echo TAB_4.'<a href="javascript:location.reload(true)"'."\n";
				echo TAB_5.' title="Reload this page to see latest updates" >' ."\n";
				echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Refresh" style="border:none;"/>' ."\n";
			echo TAB_4.'</a>'. "\n";
			
		echo TAB_4.'User Account Settings</h1>'." \n";
		
	echo TAB_3.'</div>'." \n";
	
//	===========================	EDITED TO HERE	=======================================================================			
	
	//	DO ACTIONS tool bar - for "add new user" and "edit users page permisions" (select box) ??	
	
	
	
	
	//	has a user or "add new user" been selected
	if ( isset($_REQUEST['user']))
	{
		//	do edit user details OR add new user
	
		// use include file ???
	
	}

	//	display all users
	else
	{
		echo TAB_3.'<form action = "cms_update/cms_update_config_user_accounts.php"  method="post" enctype="multipart/form-data" >'."\n";
			echo TAB_4.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
				echo TAB_5.'<legend class="Centered" >'."\n";
					
					//-------------UPDATE BUTTON------------------------------------
					echo TAB_6.'<input type="submit" name="update_all" value="Update ALL displayed info" />'."\n";			
				echo TAB_5.'</legend>'."\n";

				//---------Update error msg:
				include_once ('cms_includes/cms_msg_update.php');

				echo TAB_5.'<table id="UserListing" class="AdminForm" >'."\n";
				
					echo TAB_6.'<tr class="TableHeader" >'."\n";
					
						echo TAB_7.'<th></th><th>Edit<br/>User</th><th>UserName:</th><th>Account Type:</th>'."\n";
						echo TAB_7.'<th>Account<br/>active:</th><th colspan="2">Email:</th><th>Log-in<br/>with email?</th>'."\n";
						echo TAB_7.'<th>Last Log-in:</th><th>Last Log-out:</th><th>Last Update</th><th>Reset<br/>Password</th><th>Delete</th>'."\n";
												
					echo TAB_6.'</tr>'."\n";

				$mysql_err_msg = 'User Account Info unavailable';
				$sql_statement = 'SELECT * FROM user_accounts, 1_user_logins'
				
									.' WHERE account_id >= '.$_SESSION['access']
									.' AND user_accounts.user_id = 1_user_logins.user_id'
									.' ORDER BY account_id, user_accounts.user_id';
														
				$result = ReadDB ($sql_statement, $mysql_err_msg);
		
				
				$all_active_checked = 1;
				$all_email_checked = 1;
				$count = 1;
				while ($user_info_list = mysql_fetch_array ($result))
				{
					echo TAB_6.'<tr>'."\n";
					
						//	Number
						echo TAB_7.'<td>'.$count.')</td>'."\n";
						
						//	Edit link
						echo TAB_7.'<td>'."\n";
							echo TAB_8.'<a href="'.$this_page.'&amp;user='.$user_info_list['user_id'].'"'
								.' title="click to edit more Settings for User: '.$user_info_list['username'].'">'."\n";
								echo TAB_9.'<img src="/images_misc/icon_edit.jpg" class="IconMed" alt="[EDIT]" />'."\n";
							echo TAB_8.'</a>'."\n";
						echo TAB_7.'</td>'."\n";
						
						//	Name
						echo TAB_7.'<td>'."\n";
							echo TAB_8.'<span class="Bold" >'.$user_info_list['username'].'</span>' ."\n";
						echo TAB_7.'</td>'."\n";						

						//	Account Type
						echo TAB_7.'<td>'."\n";
						
							//	can not change account type for current logged-in user or a Super Admin account - 
							//  Can change super admin accoun types in the EDIT USER page
							//	there must always be at least 1 Super admin account
							if ($_SESSION['user_id'] == $user_info_list['user_id'] OR $user_info_list['account_id'] == 1 ) 
							{
								$disabled = 'disabled="disabled"';
								$title = 'You must use the &#39;Edit User&#39; button to change the account type';
								//	disable inputs are not POSTED - need to create HIDDEN ones instead
								$hidden_input = TAB_8.'<input type="hidden" name="account_type_'.$count.'"'
												.' value="'.$user_info_list['account_id'].'" />'."\n";
							}
							else 
							{
								$disabled = '';
								$title = 'Select the Account Type for User: '.$user_info_list['username'].'';
								$hidden_input = '';
							}
							
							echo TAB_8.'<select name="account_type_'.$count.'" class="SelectAccountType" '.$disabled."\n";
								echo TAB_9.' title="'.$title.'" >'."\n";
								
							$mysql_err_msg = 'User Account Type Info unavailable';
							$sql_statement = 'SELECT * FROM _cms_user_access_types WHERE access_code >= '.$_SESSION['access']
							
																				.' ORDER BY access_code, account_id';
																		
							$acc_type_result = ReadDB ($sql_statement, $mysql_err_msg);
						
							while ($account_type_info = mysql_fetch_array ($acc_type_result))
							{	
								if ($account_type_info['account_id'] == $user_info_list['account_id'])
								{ 
									$selected = 'selected="selected"';
									$class = 'class="Selected"';
								}
								else 
								{ 
									$selected = ''; 
									$class = '';
								}
								
								echo TAB_9.'<option value="'.$account_type_info['account_id'].'" '.$class.' '.$selected."\n";
									echo TAB_10.' title="'.$account_type_info['description'].'" >'."\n";
									echo TAB_10.$account_type_info['name']."\n";
								echo TAB_9.'</option>'."\n";
							}
							
							echo TAB_8.'</select>'."\n";
							
							echo $hidden_input;
							
						echo TAB_7.'</td>'."\n";
						
						//	Active
						echo TAB_7.'<td>'."\n";
						
							if ( $user_info_list['active'] == 'on')
							{ $checked = 'checked="checked"'; }
							else 
							{ 
								$checked = ''; 
								$all_active_checked = 0;	//	not all checked
							}
							
							//	can not Disable current logged-in user or a Super Admin account
							//  Can de-activate super admin account types in the EDIT USER page
							//	there must always be at least 1 Super admin accountACCTIVE						
							if 
							(
							    ($_SESSION['user_id'] == $user_info_list['user_id'] OR $user_info_list['account_id'] == 1)
								 
								AND $user_info_list['active'] == 'on'
							) 
							{
								$disabled = 'disabled="disabled"';
								$name = 'active_readonly[]';
								$title = 'You can not de-activate your OWN Account OR a Super Administrator Account';
								//	disable inputs are not POSTED - need to create HIDDEN ones instead
								$hidden_input = TAB_8.'<input type="hidden" name="active_'.$count.'"'
												.' value="'.$user_info_list['active'].'" />'."\n";										
							}
							else 
							{
								$disabled = '';
								$name = 'active[]';
								$title = 'Uncheck this to de-activate User: '.$user_info_list['username'].'&#39;s account';
								$hidden_input = '';
							}
							
							echo TAB_8.'<input type="checkbox" name="'.$name.'" class="InputActive" '.$checked.' '.$disabled."\n";	
								echo TAB_9.' value="'.$user_info_list['user_id'].'" title="'.$title.'" />'."\n";	

							echo $hidden_input;
							
						echo TAB_7.'</td>'."\n";
											
						//	mailto link	
						echo TAB_7.'<td>'."\n";
							echo TAB_8.'<a href="mailto:'.$user_info_list['email'].'"'
								.' title="click here to email User: '.$user_info_list['username'].'">'."\n";
								echo TAB_9.'<img src="/images_misc/icon_email.png" class="IconMed" alt="[Send Email]" />'."\n";
							echo TAB_8.'</a>'."\n";
						echo TAB_7.'</td>'."\n";
						
						//	Email
						echo TAB_7.'<td>'."\n";
							echo TAB_8.'<input type="text" name="email_'.$count.'" class="InputEmail" value="'.$user_info_list['email'].'"'."\n";
								echo TAB_9.' title="You can edit the Email address for User: '.$user_info_list['username'].'" size="30" />'."\n";
															//	POST existing username to compare with new username to determine whether to update
							echo TAB_8.'<input type="hidden" name="existing_email_'.$count.'" value="'.$user_info_list['email'].'" />'."\n";	
						echo TAB_7.'</td>'."\n";						
						
						//	Login in with email
						echo TAB_7.'<td>'."\n";
				 		
							if ( $user_info_list['login_with_email'] == 'on')
							{ $checked = 'checked="checked"'; }
							else 
							{ 
								$checked = ''; 
								$all_email_checked = 0;	//	not all checked
							}	
								
							echo TAB_8.'<input type="checkbox" name="login_with_email[]" class="InputUseEmail" '
								.$checked.' value="'.$user_info_list['user_id'].'"'."\n";
								echo TAB_9.' title="If this is checked, the user can log-in with their email as their username" />'."\n";
						echo TAB_7.'</td>'."\n";

						//	Last log-in
						if ($user_info_list['last_login'] == 0) 
						{ 
							$date_in_str = 'never';
						}						
						
						else 
						{ 	
							$date_in = strtotime($user_info_list['last_login']);
							
							if(date("j M Y", $date_in) == date("j M Y"))
							{
								$warnSpan = ' class="WarningMSG"';
							}
							else
							{
								$warnSpan = '';
							}
							
							$date_in_str = '<span'.$warnSpan.'>'.date("jS M Y", $date_in).'</span></br>@ '.date("g:ia", $date_in); 	 
						
							if ($user_info_list['login_from_ip'])
							{
								$date_in_str .= '<br/>from: '.$user_info_list['login_from_ip'];
							}
							else
							{
								$date_in_str .= '<br/>from: (unknown)';
							}
							
						}
						
						echo TAB_7.'<td>'.$date_in_str.'</td>'."\n";						

						
						//	Last log-out
						if ($user_info_list['last_logout'] == 0)
						{ 
							$date_out_str = '(unknown)'; 					
						}						
						else 
						{ 
							$date_out = strtotime($user_info_list['last_logout']);
							$date_out_str = date("jS M Y", $date_out).'<br/>@ '.date("g:ia", $date_out);

							if ($date_out >= $date_in OR $_SESSION['user_id'] == $user_info_list['user_id'])
							{
								$date_out_str .= '<img src="/images_misc/icon_accept_16x16.png" alt="logged-out" />';
							}
							else
							{
								$date_out_str .= '<br/><span class="WarningMSG">LOGGED-IN ?</span>';
								
								//	Force Log out
								$date_out_str .= '<br/><a href="cms_update/cms_update_config_user_accounts.php?user='.$user_info_list['user_id']
								.'&amp;ResetLogOut=1" title="click here to Set Last Logout for User: '.$user_info_list['username'].'" >'
								.'[Reset Log Out]</a>';
								
							}	
							
						}
					
						echo TAB_7.'<td>'.$date_out_str.'</td>'."\n";	

						//	Last db Update
						if ($user_info_list['last_db_update'] == 0)
						{ 
							$db_update_str = '(unknown)'; 					
						}						
						else 
						{
							$db_update = strtotime($user_info_list['last_db_update']);
							$db_update_str = date("jS M Y", $db_update) . '<br/>@ '. date("g:ia", $db_update);
						}
						
						echo TAB_7.'<td>'.$db_update_str.'</td>'."\n";
						
						//	Reset password and email	
						echo TAB_7.'<td>'."\n";
							echo TAB_8.'<a href="'.$this_page.'&amp;user='.$user_info_list['user_id'].'&amp;reset=1" class="ButtonLink"'."\n";
								echo TAB_9.' title="click here to Reset Password for User: '.$user_info_list['username'].' and email them" >'."\n";
								echo TAB_9.' RESET '."\n";
							echo TAB_8.'</a>'."\n";
						echo TAB_7.'</td>'."\n";
											
						//	Delete Account	
						echo TAB_7.'<td>'."\n";
						
						//	can not Delete current logged-in user or a Super Admin account
						//  Can DELETE super admin accounts in the EDIT USER page
						//	there must always be at least 1 Super admin account					
						if ($_SESSION['user_id'] == $user_info_list['user_id'] OR $user_info_list['account_id'] == 1 ) 
						{
							echo TAB_8.'<img src="/images_misc/icon_delete_faded.png" class="IconMed" alt="(deletion disabled)" />'."\n";
						}
						else
						{					
							echo TAB_8.'<a href="'.$this_page.'&amp;user='.$user_info_list['user_id'].'&amp;del=1"'
								.' title="click here to DELETE the user account for: '.$user_info_list['username'].'" >'."\n";
								echo TAB_9.'<img src="/images_misc/icon_delete.png" class="IconMed" alt="[DELETE]" />'."\n";
							echo TAB_8.'</a>'."\n";
						}
						
							//	send user ID
							echo TAB_8.'<input type="hidden" name="user_id_'.$count.'" value="'.$user_info_list['user_id'].'" />'."\n";
							
						echo TAB_7.'</td>'."\n";
						
					echo TAB_6.'</tr>'."\n";
					
					$num_records = $count;
					$count++;
				}
				
					echo TAB_6.'<tr class="TableFooter" >'."\n";
					
						echo TAB_7.'<td colspan="4"></td>'."\n";
						
						//	Check ALL for Active
						echo TAB_7.'<td>'."\n";
						
							if ( $all_active_checked == 1)
							{ $checked = 'checked="checked"'; }
							else { $checked = ''; }
							
							echo TAB_8.'<input class="check_all" name="active[]" type="checkbox" value="all" '.$checked.'/>'."\n";
							echo TAB_8.'<br/><span>select all</span>'."\n";

						echo TAB_7.'</td>'."\n";

						echo TAB_7.'<td colspan="2"></td>'."\n";
						
						//	Check ALL for Login in with email
						echo TAB_7.'<td>'."\n";
						
							if ( $all_email_checked == 1)
							{ $checked = 'checked="checked"'; }
							else { $checked = ''; }
							
							echo TAB_8.'<input class="check_all" name="login_with_email[]" type="checkbox" value="all" '.$checked.'/>'."\n";
							echo TAB_8.'<br/><span>select all</span>'."\n";
							
							//	used to do db update and determin correct n# of total checkboxs
							echo TAB_8.'<input type="hidden" name="num_records" value="'.$num_records.'" />'."\n";
							
						echo TAB_7.'</td>'."\n";
						
					echo TAB_6.'</tr>'."\n";				
					
				echo TAB_5.'</table>'."\n";
			
			echo TAB_4.'</fieldset>'."\n";
			

			
		echo TAB_3.'</form>'."\n";
	
	}
	
	echo '</body>'." \n";
	echo '</html>'." \n";
	
}
	
?>