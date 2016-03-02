<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	
		echo "\n";	
		echo TAB_2.'<!--	START Status Bar code 	-->'."\n";
		echo "\n";	
		
		echo TAB_2.'<div class="CMS_ToolBar" id="CMS_StatusBar" >'."\n";						

			echo TAB_3.'<p class="StatusBar" >'."\n";			
	
				echo TAB_4.'<span id="LoginStatus" >'."\n";
				
				//	Logged-in Username Display
					//	Get user info from db:		
					$mysql_err_msg = 'Retrieving User Account Info';	
					$sql_statement = 'SELECT * FROM user_accounts WHERE user_id = "'.$_SESSION['user_id'].'"';

					$user_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
					
					if ( $user_info['display_username'] == 'on' )
					{
						echo TAB_5.'<span class="UserNameDisplay" >You are Logged-in as: <strong>'.$user_info['username'].'</strong></span>'."\n";
					}				
					
					//	Display Time to log-out
					if ($user_info['expire_time'] != 0 AND $user_info['expire_time'] != '')
					{	
						$expire_secs = ( $user_info['expire_time'] * 60 ) - 4 ;  //	allow 4 sec margin of error
						
						//	play warning beep
						echo TAB_5.'<embed src="/_media/WarningBeep.wav" width="0" height="0"'
										.' id="WarningBeep" autostart="false" enablejavascript="true">'."\n";
						
						echo TAB_5.'<script type="text/javascript">'."\n";
						echo '
					
			$(document).ready(function(){
				$(\'span.countdown\').countdown({seconds: '.$expire_secs.', callback: \'LoggedOutPage()\'});
			});
			
			function LoggedOutPage() 
			{

				$( "#LoginStatus" ).html(
					  \'<span class="WarningMSG" >Your Log in Session has timed-out...</span>\'
				);
				
			};
			
	';					
						echo TAB_5.'</script>'."\n";			
						echo TAB_5.' -- You will be logged-out in: <span class="countdown"></span> seconds'."\n";
						echo TAB_5.'<a class="ButtonLink" href="'.$this_page.'" >Restart</a>'."\n";
					}
					
				echo TAB_4.'</span>'."\n";
			
			echo TAB_3.'</p>'."\n";
	
			//	SHUTDOWN msg
			if (SITE_SHUTDOWN > 0)
			{
				echo TAB_3.'<div class="UpdateMsgDiv">'."\n";	
					echo TAB_6.'<p class = "WarningMSG" >The Site is currently SHUT DOWN '."\n";
					if ($_SESSION['access'] < 4 )
					{ echo ' - Go to Global Settings to Re-Activate</p>'."\n"; }
					echo '</p>'."\n";
					
				echo TAB_3.'</div>'."\n";			
			}
		
			//---------Update error msg:
			include_once ('CMS/cms_includes/cms_msg_update.php');			
			
		echo TAB_2.'</div>'."\n";
		
				
				/*-----------------------------------------------------*\
				|			Other features May follow					|
				\*-----------------------------------------------------*/
			

		echo "\n";		
		echo TAB_2.'<!--	END Status Bar 	-->'."\n";	
		echo "\n";		
	
	
		
?>