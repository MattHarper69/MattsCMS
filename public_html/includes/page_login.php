<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	
	if (isset($_POST['username']))
	{
		$entered_username = $_POST['username'];
	}
	else
	{
		$entered_username = '';
	}
	
//	Is page active ??	-------

if ($page_info['active'] != "on")
{
	require_once ('page_expired.php');
}

else
{
	$div_id = 3;
	
				echo TAB_3.'<div class="LogInPage" >'."\n";
				
					//echo TAB_4.'<form action="'.$_SERVER['PHP_SELF'].'?p='.$page_id.'" method="post">'."\n";
					echo TAB_4.'<form action="'.htmlspecialchars($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']).'" method="post">'."\n";
						echo TAB_5.'<fieldset class="LoginForm">'."\n";			
							echo TAB_6.'<legend>Please log-in:</legend>'."\n";
							
							echo TAB_6.'<ul class="LoginForm">'."\n";

								echo TAB_7.'<li class="LoginForm">'."\n";
									echo TAB_8.'<label class="LoginForm" for="username">UserName:</label>'."\n";
									echo TAB_8.'<input id="username" name="username" type="text" '
									  .'class="LoginForm" size="25" value="'.$entered_username.'" />'."\n";
								echo TAB_7.'</li>'."\n";
								echo TAB_7.'<li class="LoginForm">'."\n";
									echo TAB_8.'<label class="LoginForm" for="password">Password:</label>'."\n";
									echo TAB_8.'<input id="password" name="password" type="password" '
									  .'class="LoginForm" size="20" value="" />'."\n";

									echo TAB_8.'<input name="normal_login" type="submit" value="Log In" />'."\n";
									
									
								echo TAB_7.'</li>'."\n";

							echo TAB_6.'</ul>'."\n";
						
						echo TAB_5.'</fieldset>'."\n";
				
					echo TAB_4.'</form>' ."\n";

				//----------------ERROR MSG
					echo TAB_4.'<h1 class="RedHeading" >'.$normal_login_error_msg.'</h1>' ."\n";

				//------EXIT Button------
					echo TAB_4.'<form action="../" method="post">' ."\n";
						echo TAB_5.'<p><input type="submit" value="EXIT" /></p>' ."\n";
					echo TAB_4.'</form>' ."\n";	

				
				echo TAB_3.'</div>'."\n";

}	

?>