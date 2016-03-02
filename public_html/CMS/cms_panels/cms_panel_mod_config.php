<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	//	style short-hand
	$under = '<span class="Underline">';
	$span = '</span>';	

if ($_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1 AND $_SESSION['access'] < 6 )
{

	echo TAB_2.'<div class="CMS_ModConfigPanel AdminForm1" id="CMS_ModConfigPanel_'.$mod_id.'" style="width:auto; float:left; display:none;">'." \n";

		//	read from db	----------
		$mysql_err_msg = 'This Module information not available';
		
		//	get page name info
		$page_names = array();
		$sql_statement = 'SELECT page_id, page_name, menu_text FROM page_info';	
		$page_result = ReadDB ($sql_statement, $mysql_err_msg);
		while ($pages_info = mysql_fetch_array ($page_result))
		{
			$page_names[$pages_info['page_id']] = $pages_info['page_name'];				
		}				
		
			
		//	get Theme info
		$themes = array();
		$sql_statement = 'SELECT theme_id, name FROM themes';	
		$theme_result = ReadDB ($sql_statement, $mysql_err_msg);
		while ($theme_info = mysql_fetch_array ($theme_result))
		{
			$themes[$theme_info['theme_id']] = $theme_info['name'];				
		}
		
		if ($mod_info['theme_specific'] == 0)
		{
			$theme_sum_str = 'with: '.$under.'ALL'.$span.' <em>Themes</em>';
			$theme_set_str = '<strong>ALL</strong>';					
		}
		else
		{
			$theme_sum_str = 'only with the: &quot;'.$under.$themes[$mod_info['theme_specific']].$span.'&quot; <em>Theme</em>';
			$theme_set_str = '&quot;<strong>'.$themes[$mod_info['theme_specific']].'</strong>&quot;';					
		}				
	/* 	DONT NEED ??			
		//	get Module Data
		$sql_statement = 'SELECT * FROM '.$mod_info['mod_db_table']
			
								.' WHERE mod_id = '.$mod_id 
								;	
		if ($mod_info['mod_db_table'] != '' AND $mod_info['mod_db_table'] != NULL)
		{
			$mod_data = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));				
		}

	*/
	
	
		//	Get location
		if ($div_id < 11)
		{
			$location = ' in the:  &quot;'.$under.constant('DIV_'.$div_id.'_NAME').$span.'&quot; <em>Area</em>';		
		}
		else
		{
			$location = ' in the:  '.'DIV Module: '.$under.$div_id.$span;		
		}		

		//	CLOSE PANEL
		echo TAB_3.'<a href="javascript:CloseModConfigPanel('.$mod_id.')"'
			.' class="CMS_CloseModConfigPanel" title="Close This Panel" >' ."\n";
			echo TAB_4.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none; float:right;"/>' ."\n";
		echo TAB_3.'</a>'. "\n";
			
			
		//echo TAB_3.'<fieldset>'."\n";	
		//echo TAB_3.'<fieldset class="AdminForm2">'."\n";
		
			//	Tabbed Nav
			echo TAB_5.'<ul id="ConfigModOptionsTabNav" class="TabPanelNavLinks">' ."\n";
				echo TAB_6.'<li class="current"><a href="#TabConfigModPanelSummary_'.$mod_id.'">Summary</a></li>' ."\n";
				echo TAB_6.'<li><a href="#TabConfigModPanelLocation_'.$mod_id.'">Location</a></li>'."\n";			
				echo TAB_6.'<li><a href="#TabConfigModPanelContent_'.$mod_id.'">Content / Data</a></li>' ."\n";
				echo TAB_6.'<li><a href="#TabConfigModPanelCSS_'.$mod_id.'">Design (CSS) Elements</a></li>'."\n";
			echo TAB_5.'</ul>' ."\n";


		echo TAB_4.'<div id="ModConfigSettingsTabs" class="TabPanelContainer" >' ."\n";

			//	Module Summary
			echo TAB_5.'<div id="TabConfigModPanelSummary_'.$mod_id.'" class="AdminFormTabPanel OpenFirst" >'."\n";			
			
				echo TAB_6.'<h1>Module Summary:</h2>' ."\n";
				
				echo TAB_6.'<ul>' ."\n";
				
				//	mod id
				echo TAB_7.'<li class="Bullet">Module ID: <strong>'.$mod_id.'</strong></li>' ."\n";
				
				//	Mod Type
				echo TAB_7.'<li class="Bullet">Module Type: &quot;<strong>'.$mod_info['mod_name'].'</strong>&quot;' ."\n";
				
				if ($mod_locked != 1 AND $_SESSION['access'] < 5 )	
				{ 
					echo TAB_8.' &nbsp;&laquo;&laquo;&nbsp; <a href="#" class="OpenCloseNextDiv">[ change Type ]</a></p>' ."\n";
					//echo TAB_8.' &nbsp;&laquo;&laquo;&nbsp; <a href="/CMS/cms_edit_mod_data.php?ct='.$mod_id.'" rel="CMS_ColorBox_EditChangeModType" >[ change Type ]</a></p>' ."\n";
					
				
					echo TAB_7.'<li class="AdminForm2 HideAtStart" style="height:25px; margin-bottom:10px;">' ."\n";
					
						echo TAB_8.'<p>You can change the Type of this Module - Results will vary, depending on the new Type specified: ' ."\n";
							echo TAB_9.'<a class="ButtonLink" rel="CMS_ColorBox_EditChangeModType" href="/CMS/cms_edit_mod_data.php?ct='.$mod_id."\n";
							echo TAB_10.'" title="you can select from a list of Module Types to change to">Select Type</a>' ."\n";
						echo TAB_8.'</p>'."\n";

						
						//	CLOSE PANEL			
						echo TAB_8.'<a href="#" class="CloseThisPanel" title="Close This Panel" >' ."\n";
							echo TAB_9.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none; float:right;"/>' ."\n";
						echo TAB_8.'</a>'. "\n";
							
					echo TAB_7.'</li>' ."\n";

					
					
				}
				else {echo TAB_7.'</li>' ."\n";}
				
				//	Active?
				if ($mod_info['active'] == 'on') 
				{
					$active_statis = '<strong>Active</strong> (displayed)';
					$set_active_link = ' &nbsp;&laquo;&laquo;&nbsp; <a class="CMS" href="CMS/cms_update/cms_update_mod_settings.php?p='.$page_id
						.'&amp;m='.$mod_id.'&amp;a=deactivate">'.'[ De-Activate (hide) ]</a>';						
				}
				else
				{
					$active_statis = '<span class="WarningMSG">De-Activated (hidden)</span>';
					$set_active_link = ' &nbsp;&laquo;&laquo;&nbsp; <a href="CMS/cms_update/cms_update_mod_settings.php?p='.$page_id.'&amp;m='.$mod_id.'&amp;a=activate">'.'[ Activate (display) ]</a>';			
				}
				
				echo TAB_7.'<li class="Bullet">Module is: '.$active_statis;
				
				if ($mod_locked != 1 AND $_SESSION['access'] < 5 )	
				{ 
					echo $set_active_link.'</li>' ."\n";
				}
				else {echo TAB_7.'</li>' ."\n";}	
				
				//	Locked / Un-Locked
				if (isset($mod_data_info['locked']) AND $mod_data_info['locked'] == 'on')
				{
					echo TAB_7.'<li class="Bullet">This module is LOCKED from editing &nbsp;&laquo;&laquo;&nbsp; ' ."\n";
					echo '<a href="CMS/cms_update/cms_update_mod_settings.php?p='.$page_id.'&amp;m='.$mod_id.'&amp;a=unlock">'
						.' [ Un-Lock ] </a></li>';
				}
				
				echo TAB_6.'</ul>' ."\n";
				
				//	Location Summery
				echo TAB_6.'<h4>Location Summary:</h3>' ."\n";
				echo TAB_6.'<ul>' ."\n";	
					echo TAB_7.'<li class="Bullet">This Module is <em>Positioned:</em> '.$under.NumberSuffix($mod_info['position']).$span
							.' of '.$last_position.' Modules'
							.', '.$location
							.', of the  &quot;'.$under.$page_names[$page_id].$span.'&quot; <em>Page</em></li>' ."\n";
					echo TAB_7.'<li class="Bullet">And is <em>Associated</em> '.$theme_sum_str.'.</li>' ."\n";
				echo TAB_6.'</ul>' ."\n";
			
			echo TAB_5.'</div>' ."\n";					
					
			//	Mod Location
			echo TAB_5.'<div class="AdminFormTabPanel"  id="TabConfigModPanelLocation_'.$mod_id.'">'."\n";
				echo TAB_6.'<h2>Module Location:</h2>' ."\n";
				
				echo TAB_6.'<ul style="float:left">' ."\n";
				
					//	Theme Specific
					echo TAB_7.'<li class="Bullet">Theme association: '.$theme_set_str ."\n";;
					if ($mod_locked != 1 AND $_SESSION['access'] < 5 )	
					{ 
						echo TAB_8.' &nbsp;&laquo;&laquo;&nbsp; <a href="#" class="OpenCloseNextDiv">[ change ]</a></li>' ."\n";

					}
					else {echo TAB_7.'</li>' ."\n";}					


					echo TAB_7.'<li class="AdminForm2 HideAtStart" style="height:25px; margin-bottom:10px;">' ."\n";
							
						echo TAB_8.'<form class="CMS_ModConfig_Theme_form" action="CMS/cms_update/cms_update_mod_settings.php" >' ."\n";

							echo TAB_9.'Change Theme Association to: <select name="change_mod_theme">' ."\n";
										
								echo TAB_10.'<option value="0" >ALL Themes</option>'."\n";
							
								foreach ($themes as $themes_id_drop => $themes_name_drop)
								{
					
									if ( $mod_info['theme_specific'] == $themes_id_drop)
									{ 
										echo TAB_10.'<option value="'.$themes_id_drop.'" selected="selected" >'
										.$themes_name_drop.' &nbsp;&laquo;&laquo;&nbsp; (current setting)</option>'."\n";
									}
									else { echo TAB_10.'<option value="'.$themes_id_drop.'" >'.$themes_name_drop.'</option>'."\n";}

								}
										
							echo TAB_9.'</select>' ."\n";	
							echo TAB_6.'<button type="submit" class="ButtonLink" name="update_mod_theme_submit">Change</button>' ."\n";
						echo TAB_8.'</form>' ."\n";	
						
						//	CLOSE PANEL			
						echo TAB_8.'<a href="#" class="CloseThisPanel" title="Close This Panel" >' ."\n";
							echo TAB_9.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none; float:right;"/>' ."\n";
						echo TAB_8.'</a>'. "\n";
						
					echo TAB_7.'</li>' ."\n";	
					
					//	page
					echo TAB_7.'<li class="Bullet">Page: <strong>'.$page_names[$page_id].'</strong></li>' ."\n";
										
					//	Div
					echo TAB_7.'<li class="Bullet">Area: <strong>'.$location .'</strong>' ."\n";
					if ($mod_locked != 1 AND $_SESSION['access'] < 5 )	
					{ 
						echo TAB_8.' &nbsp;&laquo;&laquo;&nbsp; <a href="#" class="OpenCloseNextDiv">[ Move ]</a></li>' ."\n";
						
					}
					else {echo TAB_7.'</li>' ."\n";}
					
					echo TAB_7.'<li class="AdminForm2 HideAtStart" style="height:25px; margin-bottom:10px;">' ."\n";
							
						echo TAB_8.'<form class="CMS_ModConfig_MoveMod_form" action="CMS/cms_update/cms_update_mod_settings.php" >' ."\n";
						
						//	===================================================================================
						//	Slide out window with dropdowns 'choose page' and 'choose div'  (hide / show with jQuery '[change]' link)	
						//	===================================================================================

						echo TAB_8.'</form>' ."\n";	
						
						//	CLOSE PANEL			
						echo TAB_8.'<a href="#" class="CloseThisPanel" title="Close This Panel" >' ."\n";
							echo TAB_9.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none; float:right;"/>' ."\n";
						echo TAB_8.'</a>'. "\n";
						
					echo TAB_7.'</li>' ."\n";						
					
					//	Position
					echo TAB_7.'<li class="Bullet">Order: <strong>'.$mod_info['position'].'</strong> of <strong>'.$last_position.'</strong>' ."\n";	
						// $last_position is set in php_function.php
					

					if ($mod_locked != 1 AND $_SESSION['access'] < 5 )	
					{ 
						echo TAB_8.' &nbsp;&laquo;&laquo;&nbsp; <a href="#" class="OpenCloseNextDiv">[ change Order ]</a></li>' ."\n";
						
					}
					else {echo TAB_7.'</li>' ."\n";}					

					echo TAB_7.'<li class="AdminForm2 HideAtStart" style="height:40px; margin-bottom:10px;">' ."\n";
									
						echo TAB_8.'<form class="CMS_ModConfig_Theme_form" action="CMS/cms_update/cms_update_mod_settings.php" >' ."\n";
						
						//	===================================================================================
						//	Slide out window with dropdown 'choose Position'	(hide / show with jQuery '[change]' link)
						//	===================================================================================
							echo TAB_9.'move to: <select name="change_mod_position">' ."\n";
										
								for ($new_pos = 1; $new_pos < $last_position + 1; $new_pos++ )
								{
					
									if ( $mod_info['position'] == $new_pos)
									{ 
										echo TAB_10.'<option value="'.$new_pos.'" selected="selected" >'.PATH_SEPERATOR_SYMBOL.' '.$new_pos
										.'</option>'."\n";
									}
									else { echo TAB_10.'<option value="'.$new_pos.'">'.$new_pos.'</option>'."\n";}
								}
										
							echo TAB_9.'</select>' ."\n";	
							echo TAB_6.'<button type="submit" class="ButtonLink" name="update_mod_theme_submit">Change</button>' ."\n";
						echo TAB_8.'</form>' ."\n";	
						
					
						//	MOVE Mod up / down buttons
						if ($mod_info['position'] != 1)		
						{
							//	MOVE MODULE UP
							echo TAB_3.'<a href="javascript:MoveModConfirmOpen(\'Up\');"';
								echo ' title="Move this &quot;'.$mod_info['mod_name'].'&quot; Module UP one position" >' ."\n";
								echo TAB_4.'<img src="/images_misc/icon_arrow_up_16x16.png" alt="Move Up" style="border:none;"/>' ."\n";
							echo TAB_3.'</a>'. "\n";			
						}
										
						if ($mod_info['position'] != $last_position)
						{
							//	MOVE MODULE DOWN
							echo TAB_3.'<a href="javascript:MoveModConfirmOpen(\'Down\');"';
								echo TAB_4.' title="Move this &quot;'.$mod_info['mod_name'].'&quot; Module DOWN one position" >' ."\n";
								echo TAB_4.'<img src="/images_misc/icon_arrow_down_16x16.png" alt="Move Down" style="border:none;"/>' ."\n";
							echo TAB_3.'</a>'. "\n";	
						}
				
						//	CLOSE PANEL			
						echo TAB_8.'<a href="#" class="CloseThisPanel" title="Close This Panel" >' ."\n";
							echo TAB_9.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none; float:right;"/>' ."\n";
						echo TAB_8.'</a>'. "\n";
						
					echo TAB_7.'</li>' ."\n";	
					
				echo TAB_6.'</ul>' ."\n";
				
				//	do graphical representation of of div and position
				echo TAB_5.'<div id="ConfigModPositionDisplay_'.$mod_id.'" style="float: right">'."\n";
					echo TAB_6.'<p>Current Location of Module on this Page:</p>' ."\n";
					
											
					include('CMS/cms_includes/mini_layout_mod_pos.php');	
					
				echo TAB_5.'</div>'."\n";	
					
			echo TAB_5.'</div>' ."\n";

			//	Mod Content
			echo TAB_5.'<div id="TabConfigModPanelContent_'.$mod_id.'" class="AdminFormTabPanel" >'."\n";
			
				echo TAB_6.'<h1>Module Content:</h2>' ."\n";
			
				//	===================================================================================
				//	switch {} case: include ('CMS/cms_config_mods/*.php) <- get from _mod_types.file_name.edit ??
				//	===================================================================================						
		
			
			echo TAB_5.'</div>' ."\n";
			
			//	Mod Design (CSS) details
			echo TAB_5.'<div id="TabConfigModPanelCSS_'.$mod_id.'" class="AdminFormTabPanel" >'."\n";

				echo TAB_6.'<h1>Design (CSS) Elements</h2>' ."\n";
	
				echo TAB_6.'<ul>'. "\n";
					
				if ($mod_info['mod_type_id'] != 19 AND $mod_info['mod_type_id'] != 20)
				{ 
				
					echo TAB_7.'<li>&lt;div id=&quot;<strong>Mod_'.$div_id.'_'.$mod_info['mod_id'].'</strong>&quot;'. "\n";
						echo TAB_8.' class=&quot;<strong>Mod_'.$div_id.'_'.$mod_info['position'].' &nbsp; '.$add_class.'</strong>&quot; &gt;'. "\n";
						
				//	===================================================================================
				//	Do   <-- "Add a Class" and "delete a class"  options here	
				//	===================================================================================								
					if ($mod_locked != 1 AND $_SESSION['access'] < 5 )	
					{ 
						echo TAB_8.' &nbsp;&laquo;&laquo;&nbsp; <a href="#" class="OpenCloseNextDiv">[ Add/Remove a Class ]</a>' ."\n";

					}
					echo TAB_7.'<li class="AdminForm2 HideAtStart" style="height:30px; margin-bottom:10px;">' ."\n";
							
						echo TAB_8.'<form class="CMS_ModConfig_AddDelClass_form" action="CMS/cms_update/cms_update_mod_settings.php" >' ."\n";

							
							//	add a class:
							echo TAB_9.'<fieldset style="float:left;padding:5px" >' ."\n";
								echo TAB_10.'Add a Class: <input type="text"  name="add_mod_class"/>' ."\n";
								echo TAB_10.'<button type="submit" class="ButtonLink" name="add_mod_class_submit">Add</button>' ."\n";
							echo TAB_9.'</fieldset>' ."\n";
							
							//	remove a class						
						if ($add_class != '')						
						{
							$mod_classes = explode(' ',$add_class);

							echo TAB_9.'<fieldset style="float: left;margin-left:50px;padding:5px;" >' ."\n";
								echo TAB_10.'Remove a Class: <select name="remove_mod_class">' ."\n";

								foreach ($mod_classes as $class_name)
								{
									echo TAB_11.'<option value="'.$class_name.'" >'.$class_name.'</option>'."\n";
								}
											
								echo TAB_10.'</select>' ."\n";							
								echo TAB_10.'<button type="submit" class="ButtonLink" name="remove_mod_class_submit">'
											.'<span class="WarningMSG">Remove</span></button>' ."\n";
							echo TAB_9.'</fieldset>' ."\n";
							
						}
						
						echo TAB_8.'</form>' ."\n";	
						
						//	CLOSE PANEL			
						echo TAB_8.'<a href="#" class="CloseThisPanel" title="Close This Panel" >' ."\n";
							echo TAB_9.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none; float:right;"/>' ."\n";
						echo TAB_8.'</a>'. "\n";
						
					echo TAB_7.'</li>' ."\n";							
						
						echo TAB_8.'<li style="margin: 1em 2em;">'.$CSS_layout.'</li>'. "\n";
					echo TAB_7.'<li>&lt;/div&gt;</li>'. "\n";

				}
				else
				{
					echo TAB_7.'<li>'.$CSS_layout.'</li>'. "\n";
						echo TAB_8.'<li style="margin: 1em 2em;">...</li>'. "\n";
					echo TAB_7.'<li>&lt;/div&gt;</li>'. "\n";
				}
				
				echo TAB_6.'</ul>'. "\n";				
							
				
				echo TAB_6.'<p class="FinePrint"><br/>* These CSS/HTML Classes and IDs are shown for reference Only.' ."\n";
					echo TAB_7.'<br/>They can be used when designing CSS theme (design) files.</p>' ."\n";
				echo TAB_6.'<p class="FinePrint"><br/><sup>1</sup> These tags are optional depending on the Module settings.</p>' ."\n";			
		
			echo TAB_5.'</div>' ."\n";

			
			//	Mod Syncronisation ???
			echo TAB_5.'<div class="CMS_ModConfigSyncSettings" >'." \n";				
				//	===================================================================================
				//	do display syncronisation settings for the mod / div combo  ?????????????
				//	===================================================================================				
			echo TAB_5.'</div>' ."\n";			
			
			
			
			//	Mod preview ???
			echo TAB_5.'<div class="CMS_ModConfigDataPreview" >'." \n";

				//	===================================================================================
				//	display a preview of the content panel	  ?????????????
				//	===================================================================================					
			echo TAB_5.'</div>' ."\n";
		
		
		
		
		echo TAB_4.'</div>' ."\n";	
			
		//echo TAB_3.'</fieldset>'."\n";
		//echo TAB_3.'</div>' ."\n";
		
	echo TAB_2.'</div>' ."\n";

		

	if (isset($_GET['refresh']) AND $_GET['refresh'] == 1)
	{
		echo TAB_1.'<script type="text/javascript" >' ."\n";

			echo TAB_2.'alert("You will need to Refresh this page to see any updates for this page\n\n"' ."\n";
			echo TAB_2.' + "( Hit the F5 key to do this )\n\n");' ."\n";

		echo TAB_1.'</script>' ."\n";					
	}	
		
	
}

	
?>