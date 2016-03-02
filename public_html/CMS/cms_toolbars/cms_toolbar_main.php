<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	
		echo "\n";	
		echo TAB_2.'<!--	START Main ToolBar code 	-->'."\n";
		echo "\n";	
		
		echo TAB_2.'<div class="CMS_ToolBar" id="CMS_MainToolBar" >'."\n";						
		
		
			echo TAB_3.'<fieldset id="CmsNavOptionsButtons" class="AdminForm3">'."\n";

				echo TAB_4.' GO TO:'."\n";	
				
			
				//	Log Out Link
				echo TAB_4.'<a class="ButtonLink" href="http://'.$_SERVER['SERVER_NAME'].$this_page
							.'&amp;logout=1'.'" title="Log-out and Exit Admin">Log-out</a>' ."\n";	

				//	Exit Admin Link
				echo TAB_4.'<a class="ButtonLink" href="http://'.$_SERVER['SERVER_NAME'].$this_page
							.'&amp;exitadmin=1'.'" title="Exit Admin WITHOUT Logging-out">Exit Admin</a>' ."\n";
		
				//	REFRESH button				
				echo TAB_4.'<a href="javascript:location.reload(true)"'."\n";
					echo TAB_5.' title="Reload this page to see latest updates" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Refresh" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";
			
			echo TAB_3.'</fieldset>'."\n";
			
		
		//	custom editing (short-cut) buttons
		$mysql_err_msg = 'CMS Toolbar Custom shortcut buttons Info unavailable';
		$sql_statement = 'SELECT'
											.' button_id'
											.', cms_toolbar_buttons.seq'
											.', cms_toolbar_buttons.mod_id'
											.', cms_toolbar_buttons.name'
											.', cms_toolbar_buttons.icon_image'
											.', cms_toolbar_buttons.rel'
											.', cms_toolbar_buttons.tab'
											.', cms_toolbar_buttons.title'
											.', _module_types.cms_edit_filename'
		
											.' FROM cms_toolbar_buttons, _module_types, modules'
		
											.' WHERE cms_toolbar_buttons.active = "on"'
											.' AND modules.mod_id = cms_toolbar_buttons.mod_id'
											.' AND modules.mod_type_id = _module_types.mod_type_id'
											;
														
		
		if 
		(
				$button_info_result = ReadDB ($sql_statement, $mysql_err_msg)
			AND	$_SESSION['CMS_mode'] == TRUE 
			AND UserPageAccess($page_id) > 1 
			AND $_SESSION['access'] < 6 
		)
		{
		
			echo TAB_3.'<fieldset id="GlobalOptionsButtons" class="AdminForm3">'."\n";

			while ($button_info = mysql_fetch_array ($button_info_result))
			{
				echo TAB_4.'<a href="/CMS/cms_edit_mod_data.php?e='.$button_info['mod_id'].'&tab='.$button_info['tab'].'"' ."\n";
					echo TAB_5.' rel="'.$button_info['rel'].'" title="'.$button_info['title'].'" >' ."\n";
					echo TAB_5.'<img src="/images_misc/'.$button_info['icon_image'].'" alt="'.$button_info['name'].'" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";			
			}
							
			echo TAB_3.'</fieldset>'."\n";

		}
		
		if ($_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1 AND $_SESSION['access'] < 5 )
		{
			echo TAB_3.'<fieldset id="PageOptionsButtons" class="AdminForm3">'."\n";	
				
				echo TAB_4.'THIS PAGE: ' ."\n";

				//	ADD MODULE
				echo TAB_4.'<a href="/CMS/cms_edit_mod_data.php?p='.$page_id.'"' ."\n";
					echo TAB_5.' rel="CMS_ColorBox_AddCloneModData" title="Add a module to this page" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_addMod_24x24.png" alt="Add" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";

		

				//	DRAG MODULE - OPEN
				echo TAB_3.'<a href="javascript:DragModStart()" class="CMS_Button_DragModStart"' ."\n";
					echo TAB_4.' title="Drag Modules to to new positions" >' ."\n";
					echo TAB_4.'<img src="/images_misc/icon_move_24x24.png" alt="Drag Mod" style="border:none;"/>' ."\n";
				echo TAB_3.'</a>'. "\n";			
				
				//	DRAG MODULE - Close
				echo TAB_3.'<a href="javascript:DragModStop()" class="CMS_Button_DragModStop"' ."\n";
					echo TAB_4.' title="Cancel Drag Modules Mode" >' ."\n";
					echo TAB_4.'<img src="/images_misc/icon_move_24x24.png" alt="Drag Mod" style="border:none;"/>' ."\n";
				echo TAB_3.'</a>'. "\n";	
				
		
				//	Page Options button Open	======================================================
				echo TAB_4.'<a href="javascript:OpenPageOptionsPanel()" class="CMS_Button_OpenPageOptionsPanel"'."\n";
					echo TAB_5.' title="Configure this Page" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_page_config_24x24.png" alt="Page Options" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";

				//	Page Options button Close
				echo TAB_4.'<a href="javascript:ClosePageOptionsPanel()" class="CMS_Button_ClosePageOptionsPanel"'."\n";
					echo TAB_5.' title="Close the Page Options Panel" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_page_config_24x24.png" alt="Close Panel" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";

				if ($_SESSION['access'] < 4 )
				{
					//	Page Nav Options button Open	======================================================
					echo TAB_4.'<a href="javascript:OpenPageNavOptionsPanel()" class="CMS_Button_OpenPageNavOptionsPanel"'."\n";
						echo TAB_5.' title="Configure The Navigation Options for this Page" >' ."\n";
						echo TAB_5.'<img src="/images_misc/icon_config_page_nav_24x24.png" alt="Navigation" style="border:none;"/>' ."\n";
					echo TAB_4.'</a>'. "\n";

					//	Page Nav Options button Close
					echo TAB_4.'<a href="javascript:ClosePageNavOptionsPanel()" class="CMS_Button_ClosePageNavOptionsPanel"'."\n";
						echo TAB_5.' title="Close the Page Nav Options Panel" >' ."\n";
						echo TAB_5.'<img src="/images_misc/icon_config_page_nav_24x24.png" alt="Close Panel" style="border:none;"/>' ."\n";
					echo TAB_4.'</a>'. "\n";
					
					//	Delete Page
					echo TAB_4.'<a href="javascript:DeletePageConfirmOpen();"'."\n";
						echo TAB_5.' title="Delete this Page" >' ."\n";				
						echo TAB_5.'<img src="/images_misc/icon_delete_24x24.png" alt="Delete" style="border:none;"/>' ."\n";
					echo TAB_4.'</a>'."\n";				
				}

		
			echo TAB_3.'</fieldset>'."\n";
			
			echo TAB_3.'<fieldset id="OtherPageOptionsButtons" class="AdminForm3">'."\n";
			
				//	Add Page button Open	======================================================
				echo TAB_4.'<a href="/CMS/cms_add_clone_page.php" class="CMS_Button_AddPage"'."\n";
					echo TAB_5.' rel="CMS_ColorBox_EditAddClonePage" title="Add a new Page" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_AddPage_24x24.png" alt="Add Page" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";

				//	Clone Page button Open	======================================================
				echo TAB_4.'<a href="/CMS/cms_add_clone_page.php?p='.$page_id.'" class="CMS_Button_ClonePage"'."\n";
					echo TAB_5.' rel="CMS_ColorBox_EditAddClonePage" title="Clone this Page" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_ClonePage_24x24.png" alt="Clone Page" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";

			
			echo TAB_3.'</fieldset>'."\n";

			if ($_SESSION['access'] < 5 )
			{			
				echo TAB_3.'<fieldset id="GlobalOptionsButtons" class="AdminForm3">'."\n";
								
				if ($_SESSION['access'] < 4 )
				{
						//	Global Settings
					echo TAB_4.'<a href="/CMS/cms_global_settings.php"' ."\n";
						echo TAB_5.' rel="CMS_ColorBox_EditGlobalSettings" title="Configure the sites Global Settings" >' ."\n";
						echo TAB_5.'<img src="/images_misc/icon_global_settings_24x24.png" alt="Settings" style="border:none;"/>' ."\n";
					echo TAB_4.'</a>'. "\n";				
					
					//	Site Navigation
					echo TAB_4.'<a href="/CMS/cms_config_nav.php"' ."\n";
						echo TAB_5.' rel="CMS_ColorBox_EditGlobalSettings" title="Configure the sites Navigation" >' ."\n";
						echo TAB_5.'<img src="/images_misc/icon_nav_chart_24x24.png" alt="Navigation" style="border:none;"/>' ."\n";
					echo TAB_4.'</a>'. "\n";
				}
				
					
				if ($_SESSION['access'] < 3 )
				{
					//	User Accounts
					echo TAB_4.'<a href="/CMS/cms_user_accounts.php"' ."\n";
						echo TAB_5.' rel="CMS_ColorBox_EditGlobalSettings" title="Configure User access account" >' ."\n";
						echo TAB_5.'<img src="/images_misc/icon_user_accounts_24x24.png" alt="Users" style="border:none;"/>' ."\n";
					echo TAB_4.'</a>'. "\n";			
				}
				
				echo TAB_3.'</fieldset>'."\n";			
			}

			

			if ($_SESSION['access'] < 4 )
			{
				//	DELETE PAGE Panel	
				echo TAB_3.'<fieldset id="CMS_ConfirmDeletePage" class="AdminForm2" style="clear:both; display:none;" >'."\n";

					echo TAB_4.'<form action="CMS/cms_update/cms_update_page_config.php" method="post" >'."\n";
						echo TAB_5.'<p class="WarningMSG" >';
						
						if ($page_id != HOME_PAGE_ID)
						{
							echo 'Are you sure you want to DELETE this page titled: &quot;'.$page_info['page_name'].'&quot; ?' ."\n";
					
							//	OK DELETE PAGE
							//echo TAB_5.'<a class="ButtonLink" href="" title="Delete this page"><span class="WarningMSG">DELETE</span></a>' ."\n";	
							echo TAB_6.'<button class="ButtonLink" type="submit" title="Delete this page">'
								.'<span class="WarningMSG">DELETE</span></button>' ."\n";			
						}
						else
						{
							echo 'This page is currently set as the Default &quot;Home Page&quot;'
								.'- you must change this before you can delete it'."\n";
						}
				
							//	Cancel link
							echo TAB_6.'<a class="ButtonLink" href="javascript:DeletePageConfirmClose();" title="Do NOT Delete">Cancel</a>' ."\n";
							
							//	Close Panel
							echo TAB_6.'<a href="javascript:DeletePageConfirmClose();" title="Close This Panel"';
								echo ' style="position:relative; float:right;">' ."\n";
								echo TAB_7.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none;"/>' ."\n";
							echo TAB_6.'</a>'. "\n";
							
						echo TAB_5.'</p>'."\n";
						
						echo TAB_5.'<input name="delete" type="hidden" value="delete" />' ."\n";
						echo TAB_5.'<input name="page_id" type="hidden" value="'.$page_id.'" />' ."\n";
						
					echo TAB_4.'</form>'."\n";
					
				echo TAB_3.'</fieldset>'."\n";
				
			}
			

					
			if ($_SESSION['access'] < 5 )
			{
				
				
				//	===================================================================================
				//	
				//	DO Slide out panel with msg "you can drag modules" to other locations  (or RE-SORT (with jQuery sortable))
				//	- Click this button to Save or Cancel
				//	===================================================================================					
				
				//	Drag Module Mode Panel	
				echo TAB_3.'<fieldset id="CMS_DragModPanel" class="AdminForm2" style="clear:both; display:none;" >'."\n";

					//echo TAB_4.'<form action="CMS/cms_update/cms_update_mod_settings.php" method="post" >'."\n";
						echo TAB_5.'<p>';
							echo 'You can now drag modules to re-position them ' ."\n";
					
							//	Save Changes button
							echo TAB_5.'<a class="ButtonLink" href="javascript:SaveDragModPos()" title="Save">SAVE changes</a>' ."\n";	
							//echo TAB_6.'<button class="ButtonLink" type="submit" title="Delete this page">SAVE changes</button>' ."\n";			
				
							//	Cancel link
							echo TAB_6.'<a class="ButtonLink" href="javascript:DragModStop();" title="Do NOT Delete">Cancel</a>' ."\n";
				
							//	Close Panel
							echo TAB_6.'<a href="javascript:DragModStop();" title="Close This Panel"';
								echo ' style="position:relative; float:right;">' ."\n";
								echo TAB_7.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none;"/>' ."\n";
							echo TAB_6.'</a>'. "\n";
							
							//	RESET button				
							echo TAB_6.'<a class="ButtonLink" href="javascript:location.reload(true)"'."\n";
								echo TAB_7.' title="Reload this page to Reset the Module Positions" >RESET' ."\n";
								echo TAB_7.'<img src="/images_misc/icon_refresh_24x24.png" alt="Refresh" style="border:none;"/>' ."\n";
							echo TAB_6.'</a>'. "\n";

						echo TAB_5.'</p>'."\n";
						
						//echo TAB_5.'<input name="delete" type="hidden" value="delete" />' ."\n";
						//echo TAB_5.'<input name="page_id" type="hidden" value="'.$page_id.'" />' ."\n";
						
					//echo TAB_4.'</form>'."\n";
					
				echo TAB_3.'</fieldset>'."\n";
				
			}			
			
		
			
		}
		
		elseif ($_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1 )
		{
			echo TAB_3.'<fieldset id="NoEditAccessWarning" class="AdminForm3">'."\n";
				echo TAB_3.'<p class="WarningMSG" >(You Do NOT have Edit Page Setting rights) </p>'."\n";
			echo TAB_3.'</fieldset>'."\n";		
		
		}
		
		else
		{
			echo TAB_3.'<fieldset id="NoEditAccessWarning" class="AdminForm3">'."\n";
				echo TAB_3.'<p class="WarningMSG" >(You Do NOT have ANY edit rights to this page) </p>'."\n";
			echo TAB_3.'</fieldset>'."\n";
		}
		
		

		
		
		
		echo TAB_2.'</div>'."\n";	

		echo "\n";		
		echo TAB_2.'<!--	END Main Tool Bar 	-->'."\n";	
		echo "\n";		
	
	
		
?>