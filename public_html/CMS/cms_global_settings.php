<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	
	$this_page = $_SERVER['PHP_SELF'];	
	$file_path_offset = '../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');

	require_once ($file_path_offset.'includes/access.php');

if (isset($_SESSION['access']) AND $_SESSION['access'] < 4 )
{	
	
//-----	THIS PAGE SPECIFIC INFO	========================================================	
	
	//	max upload for icon
	define ('MAX_ICON_IMAGE_FILE_SIZE', 100000);	// bytes

	//	specify $path_seperator_symbols array	
	$path_seperator_symbols = array( 
	
		  "(blank)" => ""
		, "(1 space)" => "&nbsp;"
		, "(2 space)" => "&nbsp;&nbsp;"
		, "," => ","
		, "." => "."
		, "-" => "-"
		, "--" => "--"
		, "&gt;" => "&gt;"
		, "&gt;&gt;" => "&gt;&gt;"
		, "-&gt;" => "-&gt;"
		, "--&gt;" => "--&gt;"
		, "&nbsp;&raquo;&nbsp;" => "&nbsp;&raquo;&nbsp;"
		, "&nbsp;&raquo;&raquo;&nbsp;" => "&nbsp;&raquo;&raquo;&nbsp;"
		, "&nbsp;::&nbsp;" => "&nbsp;::&nbsp;"
	);


	//	Do Head
	require_once ('cms_includes/cms_head.php');

	echo '<body>'." \n";
	
//----------------Get all CSS DIRs from db---------------------------------------------

	//	read from db	----------
	$mysql_err_msg = 'Theme selection info unavailable';	
	$sql_statement = 'SELECT * FROM themes ORDER BY seq';

	$num_results = mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg));		
	$theme_data = ReadDB ($sql_statement, $mysql_err_msg);
	
	
//----------------Get all CSS Files from CMS_CSS Dir----------------------------------------------

	$dir   = "cms_css";

	$path = opendir($dir);
	while (false !== ($file = readdir($path))) 
	{
		if($file!="." && $file!="..")
		{
			$nameparts = pathinfo($dir."/".$file);
			if(is_file($dir."/".$file) AND $nameparts['extension'] == "css" AND $file[0] == '_' )
			{$cms_css_files[]=$file;}			
		}
	}
					  
	closedir($path);
	natcasesort($cms_css_files); //-----Sort alphabetacally	

//----------------Get all Images from User images Dir----------------------------------------------

	$dir   = "../_images_user";

	$path = opendir($dir);
	while (false !== ($file = readdir($path))) 
	{
		if($file!="." && $file!="..")
		{
			$nameparts = pathinfo($dir."/".$file);
			if
			(
					is_file($dir."/".$file) 
				AND
				(
						$nameparts['extension'] == "jpg"
					OR  $nameparts['extension'] == "jpeg"
					OR	$nameparts['extension'] == "gif"
					OR	$nameparts['extension'] == "png"
					OR	$nameparts['extension'] == "bmp"
				)
			)
			
			{$image_files[]=$file;}			
		}
	}
					  
	closedir($path);
	natcasesort($image_files); //-----Sort alphabetacally	
	
	//	SHUTDOWN msg
	if (SITE_SHUTDOWN > 0)
	{
		echo TAB_3.'<div class="UpdateMsgDiv">'."\n";	
			echo TAB_4.'<p class = "WarningMSG" >The Site is currently SHUT DOWN</p>'."\n";
		echo TAB_3.'</div>'."\n";			
	}	

//parent.$.fn.colorbox.close();
	echo TAB_3.'<div class="CMS_Heading" >'." \n";
		echo TAB_4.'<h1>Site Global Settings</h1>'." \n";
	echo TAB_3.'</div>'." \n";

	
	echo TAB_3.'<form action = "cms_update/cms_update_config_global_settings.php"  method="post" enctype="multipart/form-data" >'."\n";
		echo TAB_4.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
			echo TAB_5.'<legend class="Centered" >'."\n";
				//--- hidden inputs		
				echo TAB_6.'<input type = "hidden" value = "'.MAX_ICON_IMAGE_FILE_SIZE.'" name = "MAX_FILE_SIZE" />'."\n";
				
				//-------------UPDATE BUTTON------------------------------------
				echo TAB_6.'<input type="submit"  name="update" value="Update ALL displayed info" />'."\n";			
			echo TAB_5.'</legend>'."\n";

			//---------Update error msg:
			include_once ('cms_includes/cms_msg_update.php');
		
			echo TAB_5.'<fieldset id="site_info" class="AdminForm">'."\n";
				echo TAB_6.'<legend>Website Info:</legend>'."\n";
				
				echo TAB_6.'<ul class="AdminForm">'."\n";
				
					//	Site Name	-----------
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="site_name">Website Name:</label>'."\n";
						echo TAB_8.'<input id="site_name" name="site_name" type="text" '
						  .'class="AdminForm" size="80" value="'.SITE_NAME.'" />'."\n";
					echo TAB_7.'</li>'."\n";
					
					//	Hidden heading	-----------
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="site_name">Hidden Heading:</label>'."\n";
						echo TAB_8.'<input id="hidden_heading" name="hidden_heading" type="text" '
						  .'class="AdminForm" size="80" value="'.HIDDEN_HEADING.'" />'."\n";
						  echo TAB_8.'<p class="Small" >( This is the hidden main heading - used for SEO Accessibility issues )</p>'."\n";
					echo TAB_7.'</li>'."\n";
					
					//	Site URL	----------
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="site_url">Websites Address:</label>'."\n";
						echo TAB_8.'<input id="site_url" name="site_url" type="text" '
						  .'class="AdminForm" size="80" value="'.SITE_URL.'" />'."\n";
					echo TAB_7.'</li>'."\n";
					
					//	Tagline	----------
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="site_title_tagline">Title Tagline:</label>'."\n";
						echo TAB_8.'<input id="site_title_tagline" name="site_title_tagline" type="text" '
						  .'class="AdminForm" size="80" value="'.SITE_TITLE_TAGLINE.'" />'."\n";
						echo TAB_8.'<p class="Small" >( This will appear in the title bar at the top of the page )</p>'."\n";
					echo TAB_7.'</li>'."\n";

				echo TAB_6.'</ul>'."\n";
			echo TAB_5.'</fieldset>'."\n";

			echo TAB_5.'<fieldset id="Design" class="AdminForm">'."\n";
				echo TAB_6.'<legend>Design:</legend>'."\n";
				
				echo TAB_6.'<ul class="AdminForm">'."\n";	
				
					//	Theme set	-----
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="site_theme_id">Website Theme:</label>'."\n";									
						echo TAB_8.'<select name = "site_theme_id" >'."\n";					
					
							while ( $theme_info = mysql_fetch_array ($theme_data))
							{
								if ($theme_info['theme_id'] == SITE_THEME_ID) { $selected = 'selected = "selected" ';}
								else { $selected = '';}
										
								echo TAB_9.'<option '.$selected.'value="'.$theme_info['theme_id'].'">'.$theme_info['name'].'</option>'."\n";							
							}

						echo TAB_8.'</select>'."\n";
					echo TAB_7.'</li>'."\n";
					
					//	Let uer set Theme 	-----
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="user_selects_theme">Let user select the theme:</label>'."\n";

						if ( USER_SELECTS_THEME == 'on' ) { $checked = 'checked="checked"'; }
						else { $checked = ''; }
						echo TAB_8.'<input type="checkbox" name = "user_selects_theme" '.$checked.' />'."\n";											

					echo TAB_7.'</li>'."\n";
					
					//	Admin Theme St	-------
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="admin_theme_file">Admin Centre Theme:</label>'."\n";										
						echo TAB_8.'<select name = "admin_theme_file" >'."\n";									
						
						$replace = array(".css", "_" );
						$with = array("", " " );

						for ($i = 0; $i < count($cms_css_files); $i++)
						{
							$name = str_replace($replace, $with, $cms_css_files[$i]);
							
							if ($cms_css_files[$i] == ADMIN_THEME_FILE) { $selected = 'selected = "selected" ';}
							else { $selected = '';}
							
							echo TAB_9.'<option '.$selected.' value="'.$cms_css_files[$i].'">'.$name.'</option>'."\n";							
						}
											
						echo TAB_8.'</select>'."\n";	
					echo TAB_7.'</li>'."\n";
					
				echo TAB_6.'</ul>'."\n";			

				//	Set Title Bar seperator Symbol	----------
				echo TAB_6.'<fieldset id="SetTitleSeperatorSymbol" class="AdminForm">'."\n";
					echo TAB_7.'<legend>Set the Title Seperator Symbol:</legend>'."\n";													
					echo TAB_7.'<select name = "title_seperator_symbol" >'."\n";
						
					foreach ($path_seperator_symbols as $path_seperator_symbol_display => $path_seperator_symbol)
					{
						if ($path_seperator_symbol == TITLE_SEPERATOR_SYMBOL) { $selected = 'selected = "selected" ';}
						else { $selected = '';}
							
						echo TAB_8.'<option '.$selected.' value="'.$path_seperator_symbol.'">'.$path_seperator_symbol_display.'</option>'."\n";
					}
						
					echo TAB_7.'</select>'."\n";
										
					echo TAB_7.'<p class="Small" >( Used to seperate items names in the "Title Bar" at the top of your browser )</p>'."\n";
					
				echo TAB_6.'</fieldset>'."\n";			
				
			echo TAB_5.'</fieldset>'."\n";
			
			echo TAB_5.'<fieldset id="Navigation" class="AdminForm">'."\n";
				echo TAB_6.'<legend>Navigation:</legend>'."\n";

				echo TAB_6.'<ul class="AdminForm">'."\n";	
					
					//	Home page set	-----
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="home_page_id">Home Page:</label>'."\n";									
						echo TAB_8.'<select name = "home_page_id" >'."\n";
							
						$mysql_err_msg = 'retrieving page names';
						$sql_statement = 'SELECT * FROM page_info WHERE active = "on" ORDER BY seq '; 			
						$result = ReadDB ($sql_statement, $mysql_err_msg);
			
						while ($row = mysql_fetch_array ($result))
						{
							if ( $row['page_id'] == HOME_PAGE_ID ){ $selected = 'selected = "selected" ';}						
							else{$selected = '';}
							echo TAB_9.'<option '.$selected.' value = "'.$row['page_id'].'" >'.$row['page_name'].'</option>'."\n";					
						}
						
						echo TAB_8.'</select>'."\n";
					echo TAB_7.'</li>'."\n";
					
					//	Shutdown site	----------
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="site_shutdown">Shutdown Website:</label>'."\n";										
						echo TAB_8.'<select name = "site_shutdown" >'."\n";
							
						if ( SITE_SHUTDOWN == 0 ) //	No shutdown
						{ 
							echo TAB_9.'<option value = "0" selected="selected">no</option>'."\n";
							echo TAB_9.'<option value = "1" class = "RedHeading" >YES (Maintainence)</option>'."\n";
							echo TAB_9.'<option value = "2" class = "RedHeading" >YES (Other)</option>'."\n";
						}
							
						elseif ( SITE_SHUTDOWN == 1 ) //	for Maintainence
						{ 
							echo TAB_9.'<option value = "0">no</option>'."\n";
							echo TAB_9.'<option value = "1" class = "RedHeading" selected="selected">YES (Maintainence)</option>'."\n";
							echo TAB_9.'<option value = "2" class = "RedHeading" >YES (Other)</option>'."\n";
						}	
						
						else	//	Untill further notice
						{ 
							echo TAB_9.'<option value = "0">no</option>'."\n";
							echo TAB_9.'<option value = "1" class = "RedHeading">YES (Maintainence)</option>'."\n";
							echo TAB_9.'<option value = "2" class = "RedHeading" selected="selected">YES (Other)</option>'."\n";
						}	
						
						echo TAB_8.'</select>'."\n";	
					echo TAB_7.'</li>'."\n";	
	
				//	Shutdown CMS	----------
				if (isset($_SESSION['access']) AND $_SESSION['access'] < 2 )
				{	
					
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="CMS_shutdown">Shutdown Admin Area:</label>'."\n";									
						echo TAB_8.'<select name = "CMS_shutdown" >'."\n";
							
						if ( SITE_CMS_SHUTDOWN == 0 ) 
						{ 
							echo TAB_9.'<option value = "0" >no</option>'."\n";
							echo TAB_9.'<option value = "1" class = "RedHeading" >YES</option>'."\n";
						}
							
						else
						{ 
							echo TAB_9.'<option value = "1" class = "RedHeading" >YES</option>'."\n";
							echo TAB_9.'<option value = "0" >no</option>'."\n";
						}							
							
						echo TAB_8.'</select>'."\n";


						//	Link: Mailto User to notify

						$msg_subject = 'The '.SITE_NAME.' website\'s Admin Area is going OFF-LINE';
						
						$msg_body = 'This is message to inform users that the Admin Area of this website: '.SITE_URL.' will be temporarily'
									. ' unavailable, due to maintenance - This is expected to only last for a few minutes'
									. ' - you can reply to this email for more information';
						
						$mysql_err_msg = 'Admin User info unavailable';	
						$sql_statement = 'SELECT email FROM user_accounts' 
						
														.' WHERE active = "on"'
														.' AND user_id != "'.$_SESSION['user_id'].'"'
														;
			
						if ($user_result = ReadDB ($sql_statement, $mysql_err_msg))
						{
							$emails  = '';	

							while($email_info = mysql_fetch_array($user_result ))
							{
								$emails .= $email_info['email'] . ',';
							}
							
							substr_replace($emails , '' , -1);
							
							$href = 'mailto:' . $emails . '?subject=' . $msg_subject . '&body=' . $msg_body;
					
							echo TAB_8.'<a href="'.$href.'" title send an ALERT email to: '.$emails.'>'."\n";
								echo TAB_9.'<img src="/images_misc/icon_email.png" class="IconMed" alt="[Send Email]" />'."\n";
							echo TAB_8.'</a>'."\n";
							
						}

				}
				//	This is needed so value for "CMS_shutdown" is sent
				else
				{
					echo TAB_8.'<li><input type="hidden" name = "CMS_shutdown" value="0" /></li>'."\n";
				}
						
					//	Allow Hot linking	----------
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="allow_hotlinking">Allow Hotlinking:</label>'."\n";	
						echo TAB_8.'<select name = "allow_hotlinking" >'."\n";
							
						if ( ALLOW_HOTLINKING == 1 ) 
						{ 							
							echo TAB_9.'<option value = "1" class = "RedHeading" >YES</option>'."\n";
							echo TAB_9.'<option value = "0" >no</option>'."\n";														
						}
							
						else
						{ 
							echo TAB_9.'<option value = "0" >no</option>'."\n";
							echo TAB_9.'<option value = "1" class = "RedHeading" >YES</option>'."\n";
						}							
							
						echo TAB_8.'</select>'."\n";	
					echo TAB_7.'</li>'."\n";						
					
					echo TAB_7.'</li>'."\n";	

	
					//	Number of layers in Navigation System allowed	----------
					echo TAB_7.'<li class="AdminForm">'."\n";
						echo TAB_8.'<label class="AdminForm" for="num_nav_layers">N# of Navigation Layers allowed:</label>'."\n";										
						echo TAB_8.'<select name = "num_nav_layers" >'."\n";
						
						for ( $num_nav_layers=1; $num_nav_layers<6; $num_nav_layers++ )
						{
							if ( NUM_NAV_LAYERS == $num_nav_layers ) 
							{ $selected = 'selected="selected" ';}		
							else{ $selected = '';}
							
							echo TAB_9.'<option '.$selected.' value = "'.$num_nav_layers.'" >'.$num_nav_layers.'</option>'."\n";	
							
						}
						echo TAB_8.'</select>'."\n";	
					echo TAB_7.'</li>'."\n";
					
				echo TAB_6.'</ul>'."\n";
				
				//	Set Default Nav Icon	----------
				echo TAB_6.'<fieldset id="SetDefaultNavIcon" class="AdminForm">'."\n";
					echo TAB_7.'<legend>Set the Default Menu Icon:</legend>'."\n";
					
					//	upload file
					echo TAB_7.'Upload: <input id="UploadDefaultNavIcon" class="AdminForm" type="file" name="upload_nav_icon" />'."\n";
					echo TAB_7.'<br/>OR<br/>'."\n";
					
					//	OR select from list
					echo TAB_7.'Select: <select name = "default_nav_icon" >'."\n";
						
					for ($i = 0; $i < count($image_files); $i++)
					{
						if ($image_files[$i] == DEFAULT_NAV_ICON) { $selected = 'selected = "selected" ';}
						else { $selected = '';}
							
						echo TAB_8.'<option '.$selected.' value="'.$image_files[$i].'">'.$image_files[$i].'</option>'."\n";
					}
						
					echo TAB_7.'</select>'."\n";
					
					//	preview image
					if (DEFAULT_NAV_ICON != "" AND file_exists("../_images_user/".DEFAULT_NAV_ICON))
					{echo TAB_7.'<img class="NavIcon" src="../_images_user/'.DEFAULT_NAV_ICON.'" alt="Nav Icon:'.DEFAULT_NAV_ICON.'"/>'."\n";}
					
					echo TAB_7.'<p class="Small" >( Used when no specific image is asigned to a page )</p>'."\n";
					
				echo TAB_6.'</fieldset>'."\n";
				
				//	Set Bread-Crumb seperator Symbol	----------
				echo TAB_6.'<fieldset id="SetPathSeperatorSymbol" class="AdminForm">'."\n";
					echo TAB_7.'<legend>Set the Nav. Path Seperator Symbol:</legend>'."\n";													
					echo TAB_7.'<select name = "path_seperator_symbol" >'."\n";
						
					foreach ($path_seperator_symbols as $path_seperator_symbol_display => $path_seperator_symbol)
					{
						if ($path_seperator_symbol == PATH_SEPERATOR_SYMBOL) { $selected = 'selected = "selected" ';}
						else { $selected = '';}
							
						echo TAB_8.'<option '.$selected.' value="'.$path_seperator_symbol.'">'.$path_seperator_symbol_display.'</option>'."\n";
					}
						
					echo TAB_7.'</select>'."\n";
										
					echo TAB_7.'<p class="Small" >( Used to seperate path names in the "Bread-Crumb" Navigation Menu )</p>'."\n";
					
				echo TAB_6.'</fieldset>'."\n";
				
			echo TAB_5.'</fieldset>'."\n";
					
		echo TAB_4.'</fieldset>'."\n";

	echo TAB_3.'</form>'."\n";

	echo '</body>'." \n";
	echo '</html>'." \n";	
	
}

?>