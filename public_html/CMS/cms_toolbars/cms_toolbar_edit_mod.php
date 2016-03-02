<?php

	
		echo "\n".TAB_2.'<!-- Start Tool Bar for mod: '.$div_name.' -->'." \n\n";
	
		echo TAB_2.'<div class="CMS_EditModToolBar" id="CMS_EditModToolBar_'.$mod_id.'" style="width:750px;">'." \n";


			echo TAB_3.'<p style="position:relative; float:left;">'."\n";	

				echo TAB_4.'Selected MODULE OPTIONS: '."\n";			


		//	================================================================================================
			
		//	save data before locking or de-activating Mods	
			
		//	update data for List Items Mods  -----   /////		List Items MODULE to be Depreciated
		if ($mod_info['mod_type_id'] == 2)
		{ $onClickSaveModData = 'onclick="javascript:SaveModDataListItems_'.$mod_id.'(); return true;"'; }
		
		//	update data for Table Mods
		if ($mod_info['mod_type_id'] == 38)
		{ $onClickSaveModData = 'onclick="javascript:SaveModDataTable('.$mod_id.'); return true;"'; }			

		//	update data for Text Mods
		else { $onClickSaveModData = 'onclick="javascript:SaveModDataText('.$mod_id.'); return true;"'; }			
				
				
			
		if ($mod_locked != 1 AND $_SESSION['access'] < 5 )
		{
 
			if ($mod_active == 1)
			{		
				//	In-ACTIVATE MODULE
				echo TAB_3.'<a href="CMS/cms_update/cms_update_mod_settings.php?p='.$page_id.'&amp;m='.$mod_id.'&amp;a=deactivate"'
					//.' class="CMS_Button_DeActivateMod UpdateModContentFunction" id="CMS_Button_DeActivateMod_'.$mod_id.'"' ."\n";
					.' class="CMS_Button_DeActivateMod" id="CMS_Button_DeActivateMod_'.$mod_id.'"' ."\n";
					echo TAB_4.' title="De-activate this Module (Hide it)" '.$onClickSaveModData.'>' ."\n";
					echo TAB_4.'<img src="/images_misc/icon_turnOff_16x16.png" alt="Hide" style="border:none;"/>' ."\n";
				echo TAB_3.'</a>'. "\n";
			}
			
			else
			{
				//	ACTIVATE MODULE
				echo TAB_3.'<a href="CMS/cms_update/cms_update_mod_settings.php?p='.$page_id.'&amp;m='.$mod_id.'&amp;a=activate"'
					//.' class="CMS_Button_ActivateMod UpdateModContentFunction" id="CMS_Button_ActivateMod_'.$mod_id.'"' ."\n";
					.' class="CMS_Button_ActivateMod" id="CMS_Button_ActivateMod_'.$mod_id.'"' ."\n";
					echo TAB_4.' title="Activate this Module (Display it)"'.$onClickSaveModData.'>' ."\n";
					echo TAB_4.'<img src="/images_misc/icon_activate_Tick_16x16.png" alt="Hide" style="border:none;"/>' ."\n";
				echo TAB_3.'</a>'. "\n";		
			}
		}					
				
		//	================================================================================================				
				
		if ($mod_locked != 1  AND $_SESSION['access'] < 5)
		{
			//	Config MODULE
			echo TAB_3.'<a href="javascript:OpenModConfigPanel('.$mod_id.')" class="CMS_Button_ModConfigOpen"' ."\n";
				echo TAB_4.' title="Configure this &quot;'.$mod_info['mod_name'].'&quot; Module" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_config_16x16.png" alt="Configure" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";	

			echo TAB_3.'<a href="javascript:CloseModConfigPanel('.$mod_id.')" class="CMS_Button_ModConfigClose"' ."\n";
				echo TAB_4.' title="Close the Configure Module Panel" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_config_16x16.png" alt="Configure" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";

			
		}
		elseif ($mod_locked == 1  OR $_SESSION['access'] == 5)
		{

			//	Get MODULE INFO
			echo TAB_3.'<a href="javascript:OpenModConfigPanel('.$mod_id.')" class="CMS_Button_ModInfoOpen"' ."\n";
				echo TAB_4.' title="Information on this &quot;'.$mod_info['mod_name'].'&quot; Module" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_info_16x16.png" alt="Info" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";

			echo TAB_3.'<a href="javascript:CloseModConfigPanel('.$mod_id.')" class="CMS_Button_ModInfoClose"' ."\n";
				echo TAB_4.' title="Close the Module Panel" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_info_16x16.png" alt="Info" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";	
			
		}
		
		if ($mod_locked != 1 AND $edit_enabled != 0)
		{		
			//	Edit MOD Data
			echo TAB_3.'<a href="/CMS/cms_edit_mod_data.php?e='.$mod_id.'" class="CMS_Button_ModEdit"' ."\n";
				echo TAB_4.' rel="CMS_ColorBox_EditModData" title="Edit the Data for this &quot;'.$mod_info['mod_name'].'&quot; Module" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_edit_16x16.png" alt="Edit Data" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";
		}

			

		

		//	================================================================================================
		//	$mod_locked:	0 = unlocked,	1 = Locked, 2 = unlockable
		
		if ($mod_locked == 2) {}

		elseif ($mod_locked == 1)
		{
			//	UN-LOCK MODULE
			echo TAB_3.'<a href="CMS/cms_update/cms_update_mod_settings.php?p='.$page_id.'&amp;m='.$mod_id.'&amp;a=unlock"'
				.' class="CMS_Button_UnLockMod" id="CMS_Button_UnLockMod_'.$mod_id.'"' ."\n";
				echo TAB_4.' title="Un-Lock the Content in this Module" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_unlock_16x16.png" alt="Un-Lock" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";	
		}
		
		else
		{
			//	LOCK MODULE
			echo TAB_3.'<a href="CMS/cms_update/cms_update_mod_settings.php?p='.$page_id.'&amp;m='.$mod_id.'&amp;a=lock"' 			
				//.' class="CMS_Button_LockMod UpdateModContentFunction" id="CMS_Button_LockMod_'.$mod_id.'"' ."\n";
				.' class="CMS_Button_LockMod CMS_Button_ModUpdate" id="CMS_Button_LockMod_'.$mod_id.'"' ."\n";
				echo TAB_4.' title="SAVE and Lock the Content in this Module" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_lock_16x16.png" alt="Lock" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";	
		}	



		
			//	================================================================================================	
			
			//	Save Modules   ---------------------------------------
			
			//	$edit_enabled:	0 = no edit,	1 = edit wysiwig only, 	2 = edit wysiwig and Html
			
			if ($edit_enabled > 0)
			{
			
				//////////////////////////		List Items MODULE to be Depreciated		///////////////////////////////////////////////////	
				if ($mod_info['mod_type_id'] == 2)	
				{
					//	SAVE List Items MODULE 
					echo TAB_3.'<a href="javascript:SaveModDataListItems_'.$mod_id.'()"' ."\n";
						echo TAB_4.' class="CMS_Button_SaveModDataListItems" id="CMS_Button_SaveModDataListItems_'.$mod_id.'"'
							.' title="Save this Content" >' ."\n";
						echo TAB_4.'<img src="/images_misc/icon_save_16x16.png" alt="Save" style="border:none;"/>' ."\n";
					echo TAB_3.'</a>'. "\n";			
				}


				elseif ($mod_info['mod_type_id'] == 38)	
				{
					//	SAVE Table MODULE 
					echo TAB_3.'<a href="javascript:SaveModDataTable('.$mod_id.', true)"' ."\n";
						echo TAB_4.' class="CMS_Button_SaveModDataTable" id="CMS_Button_SaveModDataTable_'.$mod_id.'"'
							.' title="Save this Content" >' ."\n";
						echo TAB_4.'<img src="/images_misc/icon_save_16x16.png" alt="Save" style="border:none;"/>' ."\n";
					echo TAB_3.'</a>'. "\n";			
				}
				
				else
				{
					//	SAVE MODULE (TEXT)
					echo TAB_3.'<a href="javascript:SaveModDataText('.$mod_id.', true)"' ."\n";
						echo TAB_4.' class="CMS_Button_SaveModText" id="CMS_Button_SaveModText_'.$mod_id.'" title="Save this Content" >' ."\n";
						echo TAB_4.'<img src="/images_misc/icon_save_16x16.png" alt="Save" style="border:none;"/>' ."\n";
					echo TAB_3.'</a>'. "\n";
					
					//	SAVE MODULE (HTML) 
					echo TAB_3.'<a href="javascript:SaveModDataTextHtml('.$mod_id.')"' ."\n";
						echo TAB_4.' class="CMS_Button_SaveModHtml" id="CMS_Button_SaveModHtml_'.$mod_id.'" title="Save this Content" >' ."\n";
						echo TAB_4.'<img src="/images_misc/icon_save_16x16.png" alt="Save" style="border:none;"/>' ."\n";
					echo TAB_3.'</a>'. "\n";
				
				}	

			}

		if ($edit_enabled == 2)
		{		
			//	View / Edit HTML
			echo TAB_3.'<a href="javascript:openEditHTML(\''.$mod_info['mod_type_id'].'\',\''.$mod_id.'\',\''.$div_name.'\')"'
				.' class="CMS_Button_OpenHTMLPanel" id="CMS_Button_OpenHTMLPanel_'.$mod_id.'" title="View / Edit the HTML" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_html_20x16.png" alt="HTML" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";			
 
			//	View / Edit TEXT
			echo TAB_3.'<a href="javascript:CloseEditHTML(\''.$mod_info['mod_type_id'].'\',\''.$mod_id.'\',\''.$div_name.'\')"'
				.' class="CMS_Button_OpenTEXTPanel" id="CMS_Button_OpenTEXTPanel_'.$mod_id.'" title="View / Edit the Plain Text" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_text_20x16.png" alt="Text" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";
		}	
	

		//=======================================================================================================================================

		
		if ($edit_enabled == 2 AND $mod_locked != 1  AND $_SESSION['access'] < 6)
		{
			//	Upload and link to a File	
			echo TAB_3.'<a href="javascript:OpenModUploadAndLinkPanel('.$mod_id.')" class="CMS_Button_ModUploadAndLinkOpen"' ."\n";
				echo TAB_4.' title="Upload and Link a file to this &quot;'.$mod_info['mod_name'].'&quot; Module" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_upload_file_16x16.png" alt="Upload and Link a file" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";	

			echo TAB_3.'<a href="javascript:CloseModUploadAndLinkPanel('.$mod_id.')" class="CMS_Button_ModUploadAndLinkClose"' ."\n";
				echo TAB_4.' title="Close the Upload and Link file Panel" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_upload_file_16x16.png" alt="Upload and Link a file" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";
			
		}


		//======================================================================================================================
	if ($_SESSION['access'] < 5 )
	{
		//	MOVE Mods
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

		
		if($can_not_clone != 1)
		{
			//	CLONE MODULE
			echo TAB_3.'<a href="/CMS/cms_edit_mod_data.php?c='.$mod_id.'" class="CMS_Button_ModCloneOpen"' ."\n";
				echo TAB_4.' rel="CMS_ColorBox_AddCloneModData" title="Clone this &quot;'.$mod_info['mod_name'].'&quot; Module" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_CloneMod_16x16.png" alt="Clone" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";			
		
		}
			//======================================================================================================================
			
		//	DELETE MODULE
		if ($mod_locked != 1)
		{
			echo TAB_3.'<a href="javascript:DeleteModConfirmOpen()"' ."\n";
				echo TAB_4.' title="Delete this &quot;'.$mod_info['mod_name'].'&quot; Module" >' ."\n";
				echo TAB_4.'<img src="/images_misc/icon_delete_16x16.png" alt="Delete" style="border:none;"/>' ."\n";
			echo TAB_3.'</a>'. "\n";		
		}
	
	}


			echo TAB_3.'</p>'."\n";	
			

		//	================================================================================================
		
		
			//	CLOSE PANEL		
			echo TAB_3.'<div style="position:relative; float:right;">' ."\n";			
				echo TAB_4.'<a href="javascript:CloseEditModPanel(\''.$mod_id.'\',\''.$div_name.'\')"'
					.' class="CMS_CloseEditModPanel" title="Close This Panel" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";	
			echo TAB_3.'</div>' ."\n";
			
		if ($_SESSION['access'] < 5 )
		{			
	
			//	Move MOD Panel
			echo TAB_3.'<fieldset class="CMS_ConfirmMovMod AdminForm2" style="display:none; clear:both;" >'."\n";
					
					echo TAB_5.'<p class="WarningMSG" >This will MOVE this &quot;'.$mod_info['mod_name'].'&quot; Module: ';
						echo '<span class="UpORDown"></span> 1 position '.PATH_SEPERATOR_SYMBOL.' ' ."\n";
			
						//	OK MOVE Mod
						echo TAB_6.'<a class="ButtonLink Button_ModUp" href="CMS/cms_update/cms_update_mod_settings.php?modup='.$mod_id.'"';
							echo ' title="Move this Module Up" style="display:none;">Move UP</a>' ."\n";			
						echo TAB_6.'<a class="ButtonLink Button_ModDown" href="CMS/cms_update/cms_update_mod_settings.php?moddown='.$mod_id.'"';
							echo ' title="Move this Module Down" style="display:none;">Move DOWN</a>' ."\n";
							
						//	Cancel link
						echo TAB_6.'<a class="ButtonLink" href="javascript:MoveModConfirmClose();" title="Do NOT Move">Cancel</a>' ."\n";
						
						//	Close Panel
						echo TAB_6.'<a href="javascript:MoveModConfirmClose();" title="Close This Panel"';
							echo ' style="position:relative; float:right;">' ."\n";
							echo TAB_7.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none;"/>' ."\n";
						echo TAB_6.'</a>'. "\n";
						
					echo TAB_5.'</p>'."\n";
							

	
				
			echo TAB_3.'</fieldset>'."\n";	
	
			//	DELETE MOD Panel
			echo TAB_3.'<fieldset class="CMS_ConfirmDeleteMod AdminForm2" style="display:none; clear:both;" >'."\n";

				echo TAB_4.'<form action="CMS/cms_update/cms_update_mod_settings.php" method="post" >'."\n";
					echo TAB_5.'<p class="WarningMSG" >';
					
						echo 'Are you sure you want to DELETE this &quot;'.$mod_info['mod_name'].'&quot; Module ?' ."\n";
				
						//	OK DELETE Mod
						echo TAB_6.'<button class="ButtonLink" type="submit" title="Delete this Module">'
							.'<span class="WarningMSG">DELETE</span></button>' ."\n";			

						//	Cancel link
						echo TAB_6.'<a class="ButtonLink" href="javascript:DeleteModConfirmClose();" title="Do NOT Delete">Cancel</a>' ."\n";
						
						//	Close Panel
						echo TAB_6.'<a href="javascript:DeleteModConfirmClose();" title="Close This Panel"';
							echo ' style="position:relative; float:right;">' ."\n";
							echo TAB_7.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none;"/>' ."\n";
						echo TAB_6.'</a>'. "\n";
						
					echo TAB_5.'</p>'."\n";
					
					echo TAB_5.'<input name="delete" type="hidden" value="'.$mod_id.'" />' ."\n";
					
				echo TAB_4.'</form>'."\n";
				
			echo TAB_3.'</fieldset>'."\n";					
		
		}
		
		if ($edit_enabled == 2)
		{	
			//	Upload and link to a File	===========================================
			$update_url = '/CMS/cms_update/cms_update_upload_file.php';
			$return_url = $_SERVER['PHP_SELF'].'?p='.$page_id;
			
			//	update data for Table Mods
			if ($mod_info['mod_type_id'] == 38)
			{ $onClickSaveModData = ' onclick="javascript:AddHrefAndSave_table('.$mod_id.'); return true;"'; }			

			//	update data for Text Mods
			else { $onClickSaveModData = ' onclick="javascript:AddHrefAndSave_text('.$mod_id.'); return true;"'; }			
			
			
			
			echo TAB_3.'<script type="text/javascript" language="javascript">
					
				$(document).ready(function()
				{

					//	Display upload button and instructions
					$(".UploadFileToLinkSubmit").css("display" , "none");

					$(".UploadAndLinkFile").change(function()
					{
						$(".UploadFileToLinkSubmit").css("display" , "inline");												
					});					

					//	Get Link text (that the user has highlited)
					$(".UpdateMe").click(function()
					{					
						var LinkTextVal = getSelectionText();
						var LinkTextValDisplay = " Link Text: <strong>" + LinkTextVal + "</strong> ";
						
						$("#upload_and_link_file_text").val(LinkTextVal);						
						$("#upload_and_link_file_text_display").html(LinkTextValDisplay);

					});
								
					
				});							

			</script>'."\n";		


			echo TAB_3.'<div class="CMS_ModUploadAndLinkPanel AdminForm1" id="CMS_ModUploadAndLinkPanel_'
				.$mod_id.'" style="width:auto; float:left; display:none;">'." \n";

				echo TAB_5.'<a href="javascript:CloseModUploadAndLinkPanel('.$mod_id.')" title="Close This Panel" >'."\n";
					echo TAB_6.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none; float:right;"/>'."\n";
				echo TAB_5.'</a>'."\n";	
				
				echo TAB_5.'<p>To Upload and Link a File:</p>' . "\n";
				
				echo TAB_5.'<hr/>'."\n";
			
				echo TAB_5.'<form id="Form_UploadAndLinkFile" action = "'.$update_url.'"  method="post" enctype="multipart/form-data" >'."\n";	
					echo TAB_6.'<input type="hidden" name="MAX_FILE_SIZE" value="'.MAX_FILE_SIZE_CMS.'" />' . "\n";
					echo TAB_6.'<p>1. Create the text for the link (if not already created)</p>' . "\n";
					echo TAB_6.'<p>2. Select a File:' . "\n"; 
				
					echo TAB_6.'<input type="file" class="UploadAndLinkFile" name="upload_and_link_file"' . "\n"; 
						echo TAB_7.' style="font-size: 12px;" size="60"'
								.' title="Use this to locate and upload a File for linking to" /></p>'."\n";
					

					echo TAB_6.'<input type="hidden" id="upload_and_link_file_text" name="upload_and_link_file_text"'."\n";
						echo TAB_7.' size="30" title="Specify the Link text to be displayed here:" /> '."\n";
/* 						
					//	use IMAGE for Link ===== (TBA)
					echo TAB_6.' OR use an image:<input type="file" id="upload_and_link_img" name="upload_and_link_img"' . "\n"; 
						echo TAB_7.' style="font-size: 12px;" size="30"'
								.' title="Use this to locate and upload a image File for the linl" /><br/>'."\n";
*/
					echo TAB_6.'<span class="UploadFileToLinkSubmit">'."\n";
					
					
						echo TAB_7.'<p>3. Select the Link Target Window: <select name="upload_and_link_file_rel" >' . "\n"; 
							echo TAB_8.'<option value="" >Same window</option>' . "\n";
							echo TAB_8.'<option value="external" >New window</option>' . "\n";
							echo TAB_8.'<option value="ColorBoxLink" >ColorBox</option>' . "\n"; 
							echo TAB_8.'<option value="ColorBoxNewsletter" >ColorBox - Newsletter</option>' . "\n"; 

						echo TAB_7.'</select></<p>' . "\n"; 						
						
						echo TAB_7.'<p>4. Use the curser to Highlight the Link text on the page.</p>' . "\n"; 
		
						echo TAB_7.'<p>5. Click: '."\n"; 		
					
							echo TAB_8.'<input type="submit" name="submit_upload_file_to_link" value="Upload selected File"'. "\n";
								echo TAB_9.$onClickSaveModData . '>' . "\n";
							
							echo TAB_7.'<span id="upload_and_link_file_text_display" style="border:solid 1px #666;" ></span>'."\n";
							
						echo TAB_7.'</p>'."\n"; 
							
					echo TAB_6.'</span>'."\n";
					
					echo TAB_6.'<input type="hidden" name="mod_id" value="'.$mod_id.'" />'."\n";
					echo TAB_6.'<input type="hidden" name="db_table" value="'.$db_table.'" />'."\n";
					echo TAB_6.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";
					
				echo TAB_5.'</form>'."\n";			

			echo TAB_3.'</div>'." \n";
		}
			
		echo TAB_2.'</div>'." \n";

		echo "\n".TAB_2.'<!-- END Tool Bar for mod: '.$div_name.' -->'." \n\n";		
	
?>