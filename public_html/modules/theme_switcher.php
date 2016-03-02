<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$on_click = '';
	$this_page = '';
	$original_theme = '';

	
if ( isset($_POST['site_theme_id']))
{
	//	Update Theme	and Reload	----------------------------
	$_SESSION['user_theme_set'] = $_POST['site_theme_id'];
	
	if ( isset($_SERVER['REQUEST_URI']) ) {$this_page = $_SERVER['REQUEST_URI'];}
	else { $this_page = $_SERVER['PHP_SELF'].'?p='.$page_id; }

	require_once ( CODE_NAME.'_alert_configs.php');
	
	global $ip_alert_exceptions;
	
	if (ALERT_THEME_CHANGE == 1 AND !in_array($_SERVER['REMOTE_ADDR'], $ip_alert_exceptions) )
	{AlertOnThemeChange ($_POST['original_theme']);}

	header('location: '.$this_page ); 
	exit();
}

else
{

	if (CheckUserAgent ('mobile'))
	{
		$user_select_query = ' AND user_select = "on"';
	}
	else
	{
		$user_select_query = ' AND user_select = "on" OR user_select = "screenOnly"';		
	}
	
	//	read from db	----------
	$mysql_err_msg = 'Theme selection info unavailable';	
	$sql_statement = 'SELECT * FROM themes WHERE active = "on"'
										.$user_select_query
										.' ORDER BY seq';

	$num_results = mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg));		
	$theme_data = ReadDB ($sql_statement, $mysql_err_msg);
	
	if ( $num_results != 0 )
	{
	
		if (  USER_SELECTS_THEME == 'on')
		{		
			echo "\n";			
			echo TAB_7.'<!--	Start User Theme Switcher		-->'."\n";		
			echo "\n";

			$div_name = 'ThemeSelector_'.$mod_id;
			
			if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
			{
				$edit_html_enabled = 0;
				$mod_locked = 2;	
				
				//	Display / Hide In-Active Mods
				include ('CMS/cms_inactive_mod_display.php');
							
				$hover_class = ' HoverShow Draggable';
				$on_click = ' onClick="javascript:selectMod2Edit('.$mod_id.', \''.$div_name.'\',0,2);"';
				
			}

			else
			{$hover_class = '';}			
			
			echo TAB_7.'<div class="ThemeSelector'.$hover_class.'" id="'.$div_name.'" '
						.'title="this is used to change the look and design of the website"'.$on_click.'>'."\n";
			
				echo TAB_8.'<form action="'.$this_page.'"  method="post"  >'."\n";
				
					echo TAB_9.'<p class="Form" >'."\n";
						echo TAB_10.'<label class="Form" >'.THEME_SELECTOR_LABEL.'</label>'."\n";									
						echo TAB_10.'<select class="Form" name="site_theme_id" onchange="this.form.submit()">'."\n";
						
						if ( isset ($_SESSION['user_theme_set']) AND USER_SELECTS_THEME == 'on') { $site_theme_id = $_SESSION['user_theme_set']; }
						else { $site_theme_id = SITE_THEME_ID; }	

							while ( $theme_info = mysql_fetch_array ($theme_data))
							{
								if ($theme_info['theme_id'] == $site_theme_id) 
								{ 
									$selected = 'selected = "selected" ';
									$original_theme = $theme_info['name'];
								}
								else { $selected = '';}
										
								echo TAB_11.'<option '.$selected.'value="'.$theme_info['theme_id'].'">'.$theme_info['name'].'</option>'."\n";
								
							}

						echo TAB_10.'</select>'."\n";
						
						echo TAB_10.'<input type="hidden" name="original_theme" value="'.$original_theme.'" /> '."\n";
					
					echo TAB_9.'</p>'."\n";
					
					echo TAB_9.'<noscript><p><input type="submit" name="ok" value="Change" /></p></noscript> '."\n";
						
				echo TAB_8.'</form >'."\n";	

			echo TAB_7.'</div>'."\n";	

			if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
			{
				$edit_enabled = 0;
				$can_not_clone = 0;
				//	CSS layout Dispay (for CMS)
				$CSS_layout = '&lt;div id="<strong>ThemeSelector_'.$mod_id.'</strong>" class="<strong>ThemeSelector</strong>" &gt;';
				
				//	Do mod editing Toolbar
				include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
				
				//	Do Mod Config Panel
				include ('CMS/cms_panels/cms_panel_mod_config.php');		
			}			

			
			echo "\n";			
			echo TAB_7.'<!--	End User Theme Switcher		-->'."\n";		
			echo "\n";		
		
		}
		
	}
	
}	


function AlertOnThemeChange ($original_theme)
{

	//	read from db	----------
	$mysql_err_msg = 'Theme selection info unavailable for email alerting';	
	$sql_statement = 'SELECT name FROM themes WHERE theme_id = '.$_SESSION['user_theme_set'];

	$theme_data = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
	$theme_name = $theme_data['name'];
	if (isset($_SESSION['cust_email']))
	{
		$email_from = 'From: '.$_SESSION['cust_email'];
	}
	else
	{
		$email_from = '';
	}
	
	//	headers
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= $email_from. " \r\n";				

				
	//	to:	--------------------------------
	$to = ALERT_EMAIL;
	//$to = 'mattharper69@gmail.com';
	
	//---Sybject:
	$subject = 'Theme Change alert - from: '.SITE_NAME;	
	
	//	email supplied ??
	if (isset($_SESSION['last_entered_email']))
	{
		$email_str = TAB_3.'<p>Email: <a href="mailto:'.$_SESSION['last_entered_email'].'" >'.$_SESSION['last_entered_email'].'</a></p>' ."\n";
	}
	else {$email_str = '';}



				
		//--------------------------------------------message body:-------------------------------------------------------------
		
		$message = 	TAB_1.'<html>' ."\n"
						.TAB_2.'<head>' ."\n"
							.TAB_3.'<title>Theme Change alert - from: '.SITE_NAME.'</title>' ."\n"
						.TAB_2.'</head>' ."\n"
						.TAB_2.'<body>' ."\n"	
						
							.TAB_3.'<h3>A User changed Themes on the '.SITE_NAME.' Website...</h3>' ."\n"

							.TAB_3.'<p>URL: '.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'</p>' ."\n"
							
							.TAB_3.'<p>Time: '.date("D - d M Y - H:i T").'</p>' ."\n"
							
							.TAB_3.'<p>From: '.$original_theme.'</p>' ."\n"
							.TAB_3.'<h3>To  : '.$theme_name.'</h3>' ."\n"


							.$email_str
							
							.TAB_3.'<p><strong>User&#39;s IP address: </strong>'.TAB_2.$_SERVER['REMOTE_ADDR'].'</p>' ."\n"
							
							.TAB_3.'<p>------ END of MESSAGE ------</p>' ."\n"
						
						.TAB_2.'</body>' ."\n"
					.TAB_1.'</html>'
					;	
				//--------------------------------------------------------------------------------------------------------------------------------------------------
				
			//echo $message;
				
				$message = wordwrap($message, 70);
			
				//----------compile and send email
				mail ( $to, $subject, $message ,$headers);
				

}

?>