<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	put focus in the correct field
	if(isset($cms_login_error_msg) AND $cms_login_error_msg == "Password incorrect")
	{
		$focus_on_field = '#cms_password';
	}
	else
	{
		$focus_on_field = '#cms_username';
	}
		
	//	Do some jQuery for form animation and focus
	echo TAB_1.'<script type="text/javascript">'." \n";			
		echo TAB_2.'$(document).ready(function(){'." \n";
			echo TAB_3.'$("'.$focus_on_field.'").focus();'." \n";
		echo TAB_2.'});'." \n";			
	echo TAB_1.'</script>'." \n\n";


	//	Slide out Log-in panel - but not when a log-in error is displayed
	if(isset($cms_login_error_msg) AND $cms_login_error_msg == FALSE OR !isset($cms_login_error_msg))
	{
		$slide_out_class = ' SlideDownShow';
	}
	else
	{
		$slide_out_class = '';
	}
	
	
				
		//	Log-in Panel		
		echo TAB_2.'<div id="CMSLogInPage" class="AdminForm1 CMSLogInPage'.$slide_out_class.'" >'."\n";
		
			echo TAB_3.'<form action="'.htmlspecialchars($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']).'" method="post">'."\n";
				echo TAB_4.'<fieldset class="AdminForm">'."\n";			
					echo TAB_5.'<legend>Please log-in:</legend>'."\n";
					
					echo TAB_5.'<ul class="AdminForm">'."\n";

						echo TAB_6.'<li class="AdminForm">'."\n";
							echo TAB_7.'<label class="AdminForm" for="cms_username">UserName:</label>'."\n";
							
							$username = '';
							if(isset($_POST['cms_username']))
							{
								$username = $_POST['cms_username'];
							}
							echo TAB_7.'<input id="cms_username" name="cms_username" type="text" '
							  .'class="AdminForm" size="25" value="'.$username.'" tabindex="0" />'."\n";
						echo TAB_6.'</li>'."\n";
						echo TAB_6.'<li class="AdminForm">'."\n";
							echo TAB_7.'<label class="AdminForm" for="cms_password">Password:</label>'."\n";
							echo TAB_7.'<input id="cms_password" name="cms_password" type="password" '
							  .'class="AdminForm" size="20" value="" />'."\n";

							echo TAB_7.'<input name="cms_login" type="submit" value="Log In" />'."\n";
							
							echo TAB_7.'<a id="ButtonCancelLogin" class="ButtonLink Cancel" '
										.'href="http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']
										.'?'.$_SERVER['QUERY_STRING'].'&amp;logout=1'.'" >Cancel</a>'."\n";
										
							//	use a ramdom number to generate key to login
							$login_key = rand(0,1000000);								
							$_SESSION['login_key'] = $login_key;						
							echo TAB_7.'<input type="hidden" name="login_key" value="'.$login_key.'" />'."\n";
							
						echo TAB_6.'</li>'."\n";

					echo TAB_5.'</ul>'."\n";
					
					//	Error MSG
					echo TAB_5.'<p class="RedHeading" >'.$cms_login_error_msg.'</p>' ."\n";
					
				echo TAB_4.'</fieldset>'."\n";
		
			echo TAB_3.'</form>' ."\n";

		echo TAB_2.'</div>'."\n";




?>