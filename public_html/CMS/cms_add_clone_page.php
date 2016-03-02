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
	
	include_once ('cms_includes/cms_common_data.php');
	
	// 	Start output buffering
	ob_start();
	
	require_once ('cms_includes/cms_head.php');

	echo '<body class="CMS">'." \n";
	
	//	SHUTDOWN msg
	if (SITE_SHUTDOWN == 1)
	{
		echo TAB_4.'<div class="UpdateMsgDiv">'."\n";	
			echo TAB_5.'<p class = "WarningMSG" >The Site is currently SHUT DOWN - Go to Global Settings to Re-Activate</p>'."\n";
		echo TAB_4.'</div>'."\n";			
	}
	

	//	FOR  editing Mod	
	if (isset($_GET['p']) AND $_GET['p'] != '')	
	{




		echo TAB_4.'<script type="text/javascript">
				
			$(document).ready( function()
			{								
				
				$("#ClonePageSettingsAdjustCheck").attr("checked", false);	// Uncheck box - needed for Returning from update page
				
				$("#ClonePageSettingsAdjustCheck").click(function()
				{	
					if ($("#ClonePageSettingsAdjustCheck").is(":checked") ) 
					{
						$("#ClonePageSettings").show();
					}
					
					else
					{
						$("#ClonePageSettings").hide();
					}	
				});				
				
				//== TITLE TAG OPIONS
				
				//	Hide / Show options as checked
				$("input[name=\'titleTag_use_global\']").click(function() 
				{
					if($("#TileTagDisplayGlobal").is(":checked"))
					{						
						$("#TitleTagPartsDiv").show();
						$("#TitleTagTextDiv").hide();
						$("#TitleTagPreview").show();
						$("#TitleTagTextGlobal").show();
						$("#TitleTagTextUnique").hide();										
					}
					
					if($("#TileTagDisplayUnique").is(":checked"))
					{						
						$("#TitleTagPartsDiv").show();
						$("#TitleTagTextDiv").show();
						$("#TitleTagPreview").show();
						$("#TitleTagTextGlobal").hide();
						$("#TitleTagTextUnique").show();	
					}	
					
					if($("#TileTagDisplayNone").is(":checked"))
					{						
						$("#TitleTagPartsDiv").hide();
						$("#TitleTagTextDiv").hide();
						$("#TitleTagPreview").hide();						
					}									
				});
				
				$("#titleTagUseSiteName").click(function() 
				{
					if($(this).is(":checked"))
					{
						$("#TitleTageSiteName").show();
					}
					else
					{
						$("#TitleTageSiteName").hide();
					}																						
				});								
				
				$("#titleTagUsePageName").click(function() 
				{
					if($(this).is(":checked"))
					{
						$("#TitleTagePageName").show();
					}
					else
					{
						$("#TitleTagePageName").hide();
					}																						
				});									
			
				$("#titleTagUseSeperator").click(function() 
				{
					if($(this).is(":checked"))
					{
						$("#TitleTagSeperator1").show();
						$("#TitleTagSeperator2").show();
					}
					else
					{
						$("#TitleTagSeperator1").hide();
						$("#TitleTagSeperator2").hide();
					}																						
				});	


				//	Adjust Unique text Title tag Preview when typed
				$("#titleTagText").keyup(function()
				{
					$("#TitleTagTextUnique").text($(this).val());
				});

				//	==== OTHER OPTIONS ============================
				
				// Security Tab - Requires Log in option
				if (!$("#ClonePageRequiresLogin").is(":checked") ) 
				{
					$("#ClonePageAccessCode").hide();
				}
				
				$("#ClonePageRequiresLogin").click(function()
				{	
					if ($("#ClonePageRequiresLogin").is(":checked") ) 
					{
						$("#ClonePageAccessCode").show();
					}
					
					else
					{
						$("#ClonePageAccessCode").hide();
					}	
				});									

				//	Adjust New Page Name in Title tag preview and Menu / URL input tags
				$("#NewPageNameText").keyup(function()
				{
					$(".NewPageNameTextInsert").text($(this).val());
					$(".NewPageNameTextInsert").val($(this).val());
					
					//	Replace spaces " " with underscores "_" in URL
					var str = $(this).val();										
					var res = str.replace(/[ ]/g, "_");
					$("#CloneURL").val(res);
				});
				
				//	Replace spaces " " with underscores "_" in URL
				$("#CloneURL").keyup(function()
				{
					var str = $(this).val();
					var res = str.replace(/[ ]/g, "_");
					$(this).val(res);
				});
				
				
				//	Show "Suffix the New Pages Menu Text with a number" check box whe number of clones is > 1 
				
				// Hide / show - needed for Returning from update page
				if ($("#CloneNumCopies").val() > 1)
				{
					$("#SuffixMenuText").show();
				}
				else
				{
					$("#SuffixMenuText").hide();
				}				
								
				$("#CloneNumCopies").keyup(function()
				{
					if ($(this).val() > 1)
					{
						$("#SuffixMenuText").show();
					}
					else
					{
						$("#SuffixMenuText").hide();
					}
				});

			});
																	
		</script>'."\n";
							



		echo TAB_3.'<div class="CMS_Heading" >'." \n";
			echo TAB_4.'<h1> Clone this page: &quot;'.$page_info['page_name'].'&quot; ( ID: '.$page_id.' )'." \n";
			
				//	REFRESH button				
				echo TAB_6.'<a href="javascript:location.reload(true)"'."\n";
					echo TAB_7.' title="Reload this page without making updates" >' ."\n";
					echo TAB_7.'<img src="/images_misc/icon_refresh_24x24.png" alt="Refresh" style="border:none;"/>' ."\n";
				echo TAB_6.'</a>'. "\n";					
				
				//	Window Adjust Buttons
				//	Close Window ======================================================
				echo TAB_5.'<a href="javascript:parent.$.fn.colorbox.close()" title="Cancel and Close this window" >' ."\n";
					echo TAB_6.'<img src="/images_misc/icon_closeWin_16x16.png" alt="Close" style="float:right;"/>' ."\n";
				echo TAB_5.'</a>'. "\n";			
	
				//	Maximize Window	======================================================
				echo TAB_5.'<a href="#" class="MaximizeWindow" title="Maximize this window" >' ."\n";
					echo TAB_6.'<img src="/images_misc/icon_maxWin_16x16.png" alt="Maximize" style="padding-right:2px; float:right;"/>' ."\n";
				echo TAB_5.'</a>'. "\n";
				
				//	Restore Window	======================================================
				echo TAB_5.'<a href="#" class="RestoreWindow" title="Restore this window" >' ."\n";
					echo TAB_6.'<img src="/images_misc/icon_restoreWin_16x16.png" alt="Restore" style=" padding-right:2px; float:right;"/>' ."\n";
				echo TAB_5.'</a>'. "\n";
				
			echo TAB_4.'</h1>'." \n";
		echo TAB_3.'</div>'." \n";		

		if($page_info['active'] != 'on')
		{
			echo TAB_5.'<p class = "WarningMSG" >(This Page is currently De-Activated)</p>'."\n";		
		}

		
		echo "\n";	
		echo TAB_3.'<!--	START Clone Page Panel code 	-->'."\n";
		echo "\n";
								
		
	$div_name_array = array(						
							"banner" => "Header",  "side_1" => "Side 1 Column", "side_2" => "Side 2 Column", "footer" => "Footer"
							);

	$menu_name_array = array(		
								"menu_top" => "Header", "menu_side" => "Side 1 Column","menu_foot" => "Footer", 
								"bread_crumb" => "Bread Crumb"								
							);

	$menu_active_types_array = array(		
								"" => "off", "on" => "Links or Buttons style","select" => "&quot;Drop-down&quot; style", 
								"selectIfMobile" => "&quot;Drop-down&quot; if Mobile"								
							);		
		
		
		echo TAB_3.'<form action="cms_update/cms_update_clone_page.php" method="post" >'."\n";
						
			//	Clone Options
			echo TAB_4.'<fieldset id="CLonePageNumberName" class="AdminForm3">'."\n";

				echo TAB_5.'<div style="float:left;">'."\n";	
					
					echo TAB_6.'<fieldset class="AdminForm3" >'."\n";	
						
						echo TAB_7.'<p>New page name: <input type="text" name="new_page_name" id="NewPageNameText"'
									.' value="'.$page_info['page_name'].'(copy)" size="40" autocomplete="off" /></p>'."\n";
						echo TAB_7.'<p class="FinePrint">* Multiple New page Names and URLs with will be suffixed with a numbers</p>' ."\n";
						echo TAB_7.'<p>Number of Copies to make: '."\n";
							echo TAB_7.'<input type="text" id="CloneNumCopies" class="ValidateNumbersOnly" name="clone_num_copies" value="1" size="2"
							autocomplete="off"/></p>'."\n";
						echo TAB_7.'<p style="display: none;" id="SuffixMenuText"><input type="checkbox" name="suffix_menu_text" /> '."\n";
							echo TAB_8.': Suffix the New Page&#39;s Menu Text with a number</p>'."\n";							
					
					echo TAB_6.'</fieldset>'."\n";
									
					//	Adjust New Page(s) Settings before cloning
					echo TAB_6.'<fieldset class="AdminForm3" style="clear:both;">'."\n";						
						echo TAB_7.'<p><input type="checkbox" name="adjust_page_settings" id="ClonePageSettingsAdjustCheck" /> '."\n";
						echo TAB_8.': Adjust New Page(s) Settings before cloning</p>'."\n";												
					echo TAB_6.'</fieldset>'."\n";		
					
					//  Clone Page Options Button
					echo TAB_6.'<p><button type="submit" class="ButtonLink" name="update_page_config_submit">Clone Page</button></p>'."\n";	
					
					
					//---------Update error msg:
					include_once ('cms_includes/cms_msg_update.php');	

					
				echo TAB_5.'</div>'."\n";
				
				//	Areas to Clone
				echo TAB_5.'<fieldset class="AdminForm3" style="float:right;">'."\n";
					echo TAB_6.'<h3> Page AREAS to Clone: </h3>'."\n";

					foreach ($div_name_array as $div_name => $display_name)
					{

						if ($page_info[$div_name.'_active'] == 'on') { $checked = ' checked="checked"'; }
						else { $checked = '';}
						
						echo TAB_6.'<p><input type="checkbox" name="clone_'.$div_name.'" '.$checked.'/>'."\n";
							echo TAB_7.' : Clone this Page&#39;s <strong>'.$display_name.'</strong>.</p>'."\n";	
					
					}
				
					echo TAB_6.'<fieldset class="AdminForm3">'."\n";				
						echo TAB_7.'<p><input type="checkbox" name="clone_centre" />'."\n";
							echo TAB_8.' : Clone this Page&#39;s <strong>Centre (Main Content) Column</strong>.</p>'."\n";
					echo TAB_6.'</fieldset>'."\n";
					
				echo TAB_5.'</fieldset>'."\n"; 					
				
			echo TAB_4.'</fieldset>'."\n";			
			
			
			
			//	Adjust Page Settings ============================================================================================
			echo TAB_4.'<div id="ClonePageSettings" style="clear:both; display:none;">'."\n";	
				echo TAB_4.'<h3>Adjust New Page(s) Settings:</h3>'." \n";
					
				echo TAB_4.'<fieldset id="ClonePageAdjustSettings" class="AdminForm1" style="clear:both;">'."\n";	

					//	Tabbed Nav
					echo TAB_5.'<ul id="ClonePageTabNav" class="TabPanelNavLinks">' ."\n";
						echo TAB_6.'<li id="OpenTabPanel_Clone_1"><a href="#TabPanel_Clone_1"'
									.' onclick="javascript:SetOpenTabPanel(1);">General</a></li>' ."\n";
						echo TAB_6.'<li id="OpenTabPanel_Clone_2"><a href="#TabPanel_Clone_2"'
									.' onclick="javascript:SetOpenTabPanel(2);">Active Menus and Areas</a></li>'."\n";
						echo TAB_6.'<li id="OpenTabPanel_Clone_3"><a href="#TabPanel_Clone_3"'
									.' onclick="javascript:SetOpenTabPanel(3);">Location</a></li>'."\n";						
						echo TAB_6.'<li id="OpenTabPanel_Clone_4"><a href="#TabPanel_Clone_4"'
									.' onclick="javascript:SetOpenTabPanel(3);">Title Tag</a></li>'."\n";
						echo TAB_6.'<li id="OpenTabPanel_Clone_5"><a href="#TabPanel_Clone_5"' 
									.' onclick="javascript:SetOpenTabPanel(4);">Syncronization</a></li>'."\n";
						echo TAB_6.'<li id="OpenTabPanel_Clone_6"><a href="#TabPanel_Clone_6"'
									.' onclick="javascript:SetOpenTabPanel(5);">Security</a></li>' ."\n";
						echo TAB_6.'<li id="OpenTabPanel_Clone_7"><a href="#TabPanel_Clone_7"'
									.' onclick="javascript:SetOpenTabPanel(5);">Site Map</a></li>' ."\n";									
					echo TAB_5.'</ul>' ."\n";
					
					
					echo TAB_5.'<div class="TabPanelContainer" id="ClonePageTabs" >' ."\n";
					
						
						//	General Settings
						echo TAB_5.'<div id="TabPanel_Clone_1" class="AdminFormTabPanel">'."\n";
						
							//	Active
							if ($page_info['active'] == 'on') { $checked = ' checked="checked"'; }
							else { $checked = '';}
							
							echo TAB_6.'<fieldset id="ClonePagePageActive" class="AdminForm3">'."\n";
								echo TAB_7.'<input type="checkbox" name="page_active" '.$checked.' />'
									.' : Set The New Page as ACTIVE (uncheck to HIDE)'."\n";
							echo TAB_6.'</fieldset>'."\n";						
							
							//	Menu Text
							echo TAB_6.'<fieldset id="ClonePageName" class="AdminForm3">'."\n";
								echo TAB_7.'Edit Menu Text: <input type="text" class="NewPageNameTextInsert" name="menu_text"' ."\n";
									echo TAB_8.' value="'.$page_info['menu_text'].'(copy)" size="64" maxlength="64" /> '."\n";
								echo TAB_7.'<p class="FinePrint">* As displayed in the Navigation Menu</p>' ."\n";
							echo TAB_6.'</fieldset>'."\n";

							//	Url alias
							echo TAB_6.'<fieldset id="ClonePageURL" class="AdminForm3">'."\n";
								echo TAB_7.'URL ALIAS: &quot;http://'.SITE_URL.'/' ."\n";
								echo TAB_7.'<input type="text" id="CloneURL" class="NewPageNameTextInsert" name="url_alias"' ."\n";
									echo TAB_8.' value="'.$page_info['url_alias'].'_copy" size="64"/>&quot; '."\n";
								echo TAB_7.'<p class="FinePrint">* target destination</p>' ."\n";
							echo TAB_6.'</fieldset>'."\n";
														
							echo TAB_6.'<fieldset class="AdminForm3" id="AutoHeadingDiv" style="clear:left;">'."\n";
								echo TAB_7.'<p>Auto Page Heading:</p>' ."\n";
								
								if ( $page_info['auto_heading'] == "name")
								{
									$name_checked = ' checked="checked"';
									$menu_checked = '';
									$no_checked = '';
								}
								elseif ( $page_info['auto_heading'] == "menu")
								{
									$name_checked = '';
									$menu_checked = ' checked="checked"';
									$no_checked = '';
								}	
								else
								{
									$name_checked = '';
									$menu_checked = '';
									$no_checked = ' checked="checked"';								
								}
							
								echo TAB_7.'<p title="Display the Page&#39;s Name &quot;'
											.$page_info['page_name'].'&quot as a Heading at the top of the new page" >'. "\n";						
									echo TAB_8.'<input type="radio" id="AutoHeadingName" name="auto_heading" value="name"'
											.$name_checked.'/>' . "\n";
									echo TAB_8.'<span>&raquo; Display the Page&#39;s Name: <strong>&quot;'.$page_info['page_name'].
												'&quot</strong> as a Heading at the top of the new page</span>' . "\n";
								echo TAB_7.'</p>' . "\n";						
							
								echo TAB_7.'<p title="Display the Page&#39;s Menu Title &quot;'
											.$page_info['menu_text'].'&quot as a Heading at the top of the new page" >'. "\n";
									echo TAB_8.'<input type="radio" id="AutoHeadingName" name="auto_heading" value="menu"'
											.$menu_checked.'/>' . "\n";
									echo TAB_8.'<span>&raquo; Display the Page&#39;s Menu Title: <strong>&quot;'.$page_info['menu_text'].
												'&quot</strong> as a Heading at the top of the new page</span>' . "\n";
								echo TAB_7.'</p>' . "\n";	

								echo TAB_7.'<p title="Do NOT Display a Heading at the top of the new page" >'. "\n";
									echo TAB_8.'<input type="radio" id="AutoHeadingName" name="auto_heading" value=""'
											.$no_checked.'/>' . "\n";
									echo TAB_8.'<span>&raquo; Do <strong>NOT</strong> Display a Heading at the top of the new page</span>' . "\n";
								echo TAB_7.'</p>' . "\n";						
								
							echo TAB_6.'</fieldset>'."\n";							
							
							//	Comments						
							echo TAB_6.'<fieldset id="ClonePageComments" class="AdminForm3"  style="clear:left;">'."\n";
								echo TAB_7.'<p>Edit Comments:</p>' ."\n";
								echo TAB_7.'<textarea name="cms_comments" class="CMStextArea" cols="64" rows="2"'
										  .' onkeyup="AutoResize(this);" onkeydown="AutoResize(this);"' ."\n";
									echo TAB_8.'>'.$page_info['cms_comments'].'</textarea>' ."\n";
								echo TAB_7.'<p class="FinePrint">* For reference only (not displayed)</p>' ."\n";
							echo TAB_6.'</fieldset>'."\n"; 
							
						
							//	Pop-up text						
							echo TAB_6.'<fieldset id="UpdatePageOptionsPopUpText" class="AdminForm3">'."\n";
								echo TAB_7.'<p>Pop-up (hint) Text:</p>' ."\n";
								echo TAB_7.'<textarea name="popup_text" class="CMStextArea" cols="70" rows="2"'
										  .' onkeyup="AutoResize(this);" onkeydown="AutoResize(this);"' ."\n";
									echo TAB_8.'>'.$page_info['popup_text'].'</textarea>' ."\n";
								echo TAB_7.'<p class="FinePrint">* This is displayed when a viewer hovers over the menu link</p>' ."\n";
							echo TAB_6.'</fieldset>'."\n"; 
							
						echo TAB_5.'</div>'."\n";
						
								
						//	DIVs ACTIVE ??										
						echo TAB_5.'<div id="TabPanel_Clone_2" class="AdminFormTabPanel">'."\n";
						
							echo TAB_6.'<fieldset class="AdminForm3" >'."\n";
								echo TAB_7.'<h3> PAGE AREAS ACTIVATION (Display / Hide): </h3>'."\n";

								//echo TAB_7.'<ul>' ."\n";
								
								foreach ($div_name_array as $div_name => $display_name)
								{
									
									echo TAB_8.'<p>' ."\n";

										if ($page_info[$div_name.'_active'] == 'on') { $checked = ' checked="checked"'; }
										else { $checked = '';}
										
										echo TAB_9.'<input type="checkbox" name="'.$div_name.'_active" id="SetActive_'.$div_name.'"'.$checked.'"/>'."\n";
											echo TAB_10.' : Set The New Page&#39;s <strong>'.$display_name.'</strong> as ACTIVE.'."\n";	

									echo TAB_8.'</p>' ."\n";
									
								}
									
								//echo TAB_7.'</ul>' ."\n";

							
							echo TAB_6.'</fieldset>'."\n"; 
							
							echo TAB_6.'<fieldset class="AdminForm3" >'."\n";
								echo TAB_7.'<h3> NAVIGATION MENUS ACTIVATION (Display / Hide): </h3>'."\n";	
								
								//echo TAB_7.'<ul>' ."\n";
																	
								foreach ($menu_name_array as $menu_name => $display_name)
								{	
									echo TAB_8.'<p>' ."\n";

										echo TAB_10.'Set The New Page&#39;s <strong>'.$display_name.' Menu </strong> as : '."\n";
										
										echo TAB_9.'<select name="'.$menu_name.'_active" >'."\n";
										
										foreach ($menu_active_types_array as $option_type => $option_display)
										{
											if ($page_info[$menu_name.'_active'] == $option_type)
											{
												$selected = ' selected="selected"';
											}
											else
											{
												$selected = '';
											}
											
											echo TAB_10.'<option value="'.$option_type.'"'.$selected.'>'.$option_display.'</option>'."\n";
										}
											
										echo TAB_9.'</select>'."\n";
		
									echo TAB_8.'</p>' ."\n";
					
								}
								
								//echo TAB_7.'</ul>' ."\n";
							
							echo TAB_6.'</fieldset>'."\n"; 					
																
						echo TAB_5.'</div>'."\n";


						//	Location
						echo TAB_5.'<div id="TabPanel_Clone_3" class="AdminFormTabPanel" >'."\n";					
					
							//	Parent Page
							echo TAB_6.'<fieldset id="UpdatePageOptionsParent" class="AdminForm3">'."\n";
								echo TAB_7.'Parent Page - ( The New page will be in a Sub-Group of:' ."\n";
								
								echo TAB_7.'<script type="text/javascript">
								$(document).ready(function()
								{
									$("#SelectParentId").change(function()
									{
										var id=$(this).val();
										var dataString = "current_page_id='.$page_id.'&current_page_name='.$page_info['page_name']
										.'&current_page_seq='.$page_info['seq'].'&update_parent_id=" + id;
										
										$.ajax
										({
											type: "POST",
											url: "'.$file_path_offset.'CMS/cms_update/cms_update_page_config.php",
											data: dataString,
											cache: false,
											success: function(html)
											{
												$("#SelectPageSeq").html(html);
											} 
										});

									});

								});
								</script>';
								

								echo TAB_7.'<select id="SelectParentId" name="parent_id" >' ."\n";
								
									echo TAB_8.'<option value="0">( No Parent Page)</option>' ."\n";
									//reset($all_pages);
									foreach ($all_pages as $page_id_drop => $page_name_drop)
									{
										if ( $page_info['parent_id'] == $page_id_drop)
										{ $selected = ' selected="selected"';}
										else { $selected = '';}

										echo TAB_8.'<option value="'.$page_id_drop.'"'.$selected.' >'.$page_name_drop.'</option>' ."\n";
									}
								
								echo TAB_7.'</select> )' ."\n";
							echo TAB_6.'</fieldset>'."\n";
							
							//	Page Order
							echo TAB_6.'<fieldset id="UpdatePageOptionsSeq" class="AdminForm3">'."\n";
								echo TAB_7.'Menu Link Order:' ."\n";

								echo TAB_7.'<select id="SelectPageSeq" name="seq" >' ."\n";
								
								if (count($sub_group_pages) > 1)
								{
									$Remove_place = 0;
									$page_seq = 1;
									foreach ($sub_group_pages as $page_name_drop)
									{
										
										$position = ($page_seq + 1) / 2;
										if ($page_info['seq'] != $page_seq + 1 AND $Remove_place != 1)
										{
											
											$position = ($page_seq + 1) / 2;
											echo TAB_8.'<option class="BG_white" value="'.$page_seq.'" title="Move to Here - pos: '.$position.'">'
														.'&raquo; '.$position.' _______________</option>' ."\n";
														
										}
											
										
										if ( $page_info['seq'] == $page_seq + 1)
										{ 										
											echo TAB_8.'<option  class="BG_white" value="'.$page_seq.'" selected="selected" >'
														.'( current position:  '.$position.' )</option>' ."\n";
											$Remove_place = 1;
										}
										else 
										{ 
											
											echo TAB_8.'<option disabled="disabled" >'
														.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;('.$page_name_drop.')</option>' ."\n";
											$Remove_place = 0;			
																							
										}
											
										$page_seq = $page_seq + 2;
											
									}
								
									if ($page_info['seq'] != count($sub_group_pages) * 2)
									{
										
										$position = ($page_seq + 1) / 2;
										echo TAB_8.'<option class="BG_white" value="'.$page_seq.'" title="Move to Here - pos: '.$position.'">'
											.'&raquo; '.$position.' _______________</option>' ."\n";	
									}		
								}
							
								else
								{
									echo TAB_8.'<option disabled="disabled" value="1">( no where to move )</option>' ."\n";
								}
								
								echo TAB_7.'</select>' ."\n";
							echo TAB_6.'</fieldset>'."\n";							
						
						
							//	Include in which Menu??				
							echo TAB_6.'<fieldset id="UpdatePageDivActiveOptions" class="AdminForm3" >'."\n";
							
								echo TAB_7.'<h3> NAVIGATION MENU ASSIGNMENTS (Include / exclude): </h3>'."\n";	
								
								//echo TAB_7.'<ul>' ."\n";
								
								$menu_name_array = array("top" => "Header",  "side" => "Side 1 Column", "foot" => "Footer");	
								foreach ($menu_name_array as $menu_name => $display_name)
								{

									echo TAB_7.'<p>' ."\n";

										if ($page_info['in_menu_'.$menu_name] == 'on') { $checked = ' checked="checked"'; }
										else { $checked = '';}
										
										echo TAB_8.'<input type="checkbox" name="in_menu_'.$menu_name.'" '.$checked.' />'."\n";
										echo TAB_8.' : Include The New Page in the <strong>'.$display_name.'</strong> Navigation Menu.'."\n";	

									echo TAB_7.'</p>' ."\n";
									
								}
								
								//echo TAB_7.'</ul>' ."\n";
																					
							echo TAB_6.'</fieldset>'."\n";
						
						echo TAB_5.'</div>' ."\n";						
						
						
						//	edit Title Tag Options			
						echo TAB_5.'<div id="TabPanel_Clone_4" class="AdminFormTabPanel">'."\n";
						
							echo TAB_6.'<fieldset id="ClonePageTitleTag" class="AdminForm3" style="clear: left;">'."\n";
								echo TAB_7.'<p>Title Tag:</p>'."\n";
								
								//	Select what title tag to display			
								echo TAB_7.'<fieldset class="AdminForm3" id="TitleTageDisplayDiv">'."\n";	

									//	Global		
									if ( $page_info['titleTag_use_global'] == "global")
									{
										$checked = ' checked="checked"';
										$style_global = '';
										$style_unique = ' style="display: none;"';	//	for hiding unique text in preview and unique text input
									}
									else
									{
										$checked = '';
										$style_global = ' style="display: none;"';	//	for hiding global text in preview
										$style_unique = '';
									}								
									
					
									echo TAB_8.'<p title="Display the Site&#39;s Global Text &quot;'.SITE_TITLE_TAGLINE.'&quot in the Title Tag - Go to Global Settings to define it" >'. "\n";							
										echo TAB_9.'<input type="radio" id="TileTagDisplayGlobal" name="titleTag_use_global" value="global"'
												.$checked.'/>' . "\n";
										echo TAB_9.'<span>&raquo; Use Global Title Tag</span>' . "\n";
									echo TAB_8.'</p>' . "\n";
									
									// Unique
									if ($page_info['titleTag_use_global'] == 'unique') { $checked = ' checked="checked"'; }
									else { $checked = '';}
									
									echo TAB_8.'<p title="Display unique text for The New Page in the Title Tag" >'. "\n";	
										echo TAB_9.'<input type="radio" id="TileTagDisplayUnique" name="titleTag_use_global" value="unique"'
													.$checked.'/>' . "\n";
										echo TAB_9.'<span>&raquo; Use Unique Title Tag</span>' . "\n";	
									echo TAB_8.'</p>' . "\n";
									
									//	Do not display
									if ($page_info['titleTag_use_global'] == '') 
									{ 
										$checked = ' checked="checked"';
										$style_unique = ' style="display: none;"';	//	for hiding unique text in preview and unique text input
										$style_dont_display = ' display: none;';	//	for hiding parts to display panel and preview panel
									}
									else 
									{ 
										$checked = '';
										$style_dont_display = '';
									}
									
									echo TAB_8.'<p><input type="radio" id="TileTagDisplayNone" name="titleTag_use_global" value=""'
												.$checked.'/>'. "\n";
									echo TAB_8.'<span>&raquo; Do NOT Display Title Tag</span></p>' . "\n";	
						
				
								echo TAB_7.'</fieldset>'."\n";								
										
								//	Select what parts of the Title Tag to display							
								echo TAB_7.'<fieldset class="AdminForm3" id="TitleTagPartsDiv" style="'.$style_dont_display.'">'."\n";
												
									//	display Site Name ?
									if ($page_info['titleTag_use_siteName'] == "on") 
									{ 
										$checked = ' checked="checked"';
										$style_site_name = '';
									}
									else 
									{ 
										$checked = '';
										$style_site_name = ' style="display: none;"';
									}

									echo TAB_8.'<p title="Display the Site&#39;s Name &quot;' . SITE_NAME .'&quot; in Title Tag" >'
												.'<input type="checkbox" id="titleTagUseSiteName" name="titleTag_use_siteName" '.$checked 
												.' /><span> : Display Site Name in Title Tag</span></p>'."\n";
											
									//	display Page Name ?
									if ($page_info['titleTag_use_pageName'] == "on") 
									{ 
										$checked = ' checked="checked"'; 
										$style_page_name = '';
									}
									else 
									{ 
										$checked = '';
										$style_page_name = ' style="display: none;"';
									}

									echo TAB_8.'<p title="Display The New Page&#39;s Name &quot;' . $page_info['page_name'] .'&quot; in Title Tag" >'
												.'<input type="checkbox" id="titleTagUsePageName" name="titleTag_use_pageName" '.$checked
												.' /><span> : Display Page Name in Title Tag</span></p>'."\n";
									
									//	display Seperator ?
									if ($page_info['titleTag_use_seperator'] == "on") 
									{ 
										$checked = ' checked="checked"';
										$style_title_seperator = '';
									}
									else 
									{ 
										$checked = '';
										$style_title_seperator =  ' style="display: none;"';
									}

									echo TAB_8.'<p title="Display the &quot;Seperator Symbol&quot; in Title Tag - Go to Global Settings to define it">'
												.'<input type="checkbox" id="titleTagUseSeperator" name="titleTag_use_seperator" '
												.$checked.' /><span> : Display Seperator in Title Tag</span></p>'."\n";
																				
								echo TAB_7.'</fieldset>'."\n";			

								//	Enter unique Title Text
								echo TAB_7.'<fieldset title="Add the unique Title Tag text here..." class="AdminForm3" id="TitleTagTextDiv"'
										.$style_unique.'>'."\n";	
									echo TAB_8.'<p>Add the Unique Title Tag Text:</p>' ."\n";
									echo TAB_8.'<textarea name="titleTag_text" id="titleTagText" class="CMStextArea" cols="30" rows="2"'
											  .' onkeyup="AutoResize(this);" onkeydown="AutoResize(this);"' ."\n";
										echo TAB_9.'>'.$page_info['titleTag_text'].'</textarea>' ."\n";							
								echo TAB_7.'</fieldset>'."\n";

								//	Title Text Preview:							
								echo TAB_7.'<fieldset class="AdminForm3" id="TitleTagPreview"  style="clear: both;'.$style_dont_display.'">'."\n";
										
									echo TAB_7.'<p style="font-weight: bold;">Preview:</p>'."\n";
									
									//	Do Site Name
									$title_site_name = SITE_NAME . '<span id="TitleTagSeperator1"'.$style_title_seperator.'><b>' 
														. TITLE_SEPERATOR_SYMBOL . '</b></span>';
									
									//	Do Page Name
									$title_page_name = '<span id="TitleTagSeperator2"'.$style_title_seperator.'><b>' 
														. TITLE_SEPERATOR_SYMBOL . '</b></span><apan class="NewPageNameTextInsert">' . $page_info['page_name'] . '</span>';
									
									//	Build Title Tag
									$title_tag = '<span id="TitleTageSiteName"'.$style_site_name.'>' . $title_site_name . '</span>'; 
																				
									$title_tag .= '<span id="TitleTagTextGlobal"'.$style_global.'>' . SITE_TITLE_TAGLINE . '</span>'
												. '<span id="TitleTagTextUnique"'.$style_unique.'><i>' . $page_info['titleTag_text'] . '</i></span>';
																									
									$title_tag .= '<span id="TitleTagePageName"'.$style_page_name.'>' . $title_page_name . '</span>';
									
									//	Display Tag	
									echo TAB_8.'<b>&lt;title&gt;</b>' . $title_tag . '<b>&lt;/title&gt;</b>' . " \n"; 	
		
								echo TAB_7.'</fieldset>'."\n";
														
							echo TAB_6.'</fieldset>'."\n";
						
						echo TAB_5.'</div>'."\n";

						
						//	SYNCRONIZATION
						echo TAB_5.'<div id="TabPanel_Clone_5" class="AdminFormTabPanel">'."\n";
						
							echo TAB_6.'<h3>SYNCRONIZE The New Page&#39;S AREAS with other Pages:</h3>'."\n";
						
							//echo TAB_6.'<ul>' ."\n";
							
							foreach ($div_name_array as $div_name => $display_name)
							{
								echo TAB_7.'<p>' ."\n";

									if ($page_info['sync_'.$div_name] == 'on')
									{ $checked = ' checked="checked"'; }
									else { $checked = '';}
									
									echo TAB_8.'<input type="checkbox" name="sync_'.$div_name.'" '.$checked.' />'."\n";
									echo TAB_8.' - Update the New Page&#39;s <strong>'.$display_name.'</strong>'
												.' when other page&#39;s '.$display_name.'s are updated.'."\n";							
			
								echo TAB_7.'</p>' ."\n";
											
							}
							
							//echo TAB_6.'</ul>' ."\n";
						
						echo TAB_5.'</div>'."\n";
						
						//	SECURITY
						echo TAB_5.'<div id="TabPanel_Clone_6" class="AdminFormTabPanel" >'."\n";
						
							echo TAB_6.'<h3>PAGE SECURITY SETTINGS:</h3>'."\n";		

							echo TAB_6.'<fieldset id="PageRequiresLogin" class="AdminForm3">'."\n";
								//	Requires Log-in
								if ($page_info['requires_login'] == 'on') { $checked = ' checked="checked"'; }
								else { $checked = '';}						
							
								echo TAB_7.'<input type="checkbox" name="requires_login" id="ClonePageRequiresLogin"'.$checked. "\n";
									echo TAB_8.' /> : The New Page requires a Log-in to be viewed'."\n";
								echo TAB_7.'<p class="FinePrint">* Only the Centre Column of the page will be hidden</p>'."\n";
							echo TAB_6.'</fieldset>'."\n";
							
						
							//	Access code								
							echo TAB_6.'<fieldset id="ClonePageAccessCode" class="AdminForm3">'."\n";
								echo TAB_7.'Security Access Level: <select name="access_code" >' ."\n";
								
									$mysql_err_msg = 'Security Access Codes';					
									$sql_statement = 'SELECT name, access_code, description FROM _cms_user_access_types ORDER BY access_code';
									
									$access_code_result = ReadDB ($sql_statement, $mysql_err_msg);
									while ($access_code_info = mysql_fetch_array ($access_code_result))
									{
										if ($access_code_info['access_code'] == $page_info['access_code'])
										{ $selected = ' selected="selected"';}
										else { $selected = '';}
										
										//	Filter out Access code levels that are higher than the current user's Access code
										if ($_SESSION['access'] <= $access_code_info['access_code'])
										{
											echo TAB_8.'<option'.$selected.' value="'.$access_code_info['access_code'].'"' ."\n";
											echo TAB_9.'title="'.$access_code_info['description'].'">'.$access_code_info['name'] ."\n";
											echo TAB_8.'</option>' ."\n";									
										}
									
									}
									
								echo TAB_7.'</select>'."\n";								

								echo TAB_7.'<p class="FinePrint">* Security Levels only apply when the page requires a Log-in</p>' ."\n";
							echo TAB_6.'</fieldset>'."\n"; 
							
						echo TAB_5.'</div>'."\n";
					
					
						// include in site map
						echo TAB_5.'<div id="TabPanel_Clone_7" class="AdminFormTabPanel" >'."\n";					
							
							echo TAB_6.'<h3> SITE MAP (Used for Search engine Indexing): </h3>'."\n";
							
							echo TAB_6.'<fieldset id="UpdatePageOptionsInSiteMap" class="AdminForm3">'."\n";	
							
								if ($page_info['include_in_sitemap'] == 'on') { $checked = ' checked="checked"'; }
								else { $checked = '';}						
							
								echo TAB_7.'<input type="checkbox" name="include_in_sitemap" id="PageOptionsIncludeInSiteMap"'.$checked. "\n";
									echo TAB_8.'onclick="javascript:ShowPagePriority();"/>' ."\n";
								echo TAB_7.': Include The New Page in the Site Map'."\n";
									
							echo TAB_6.'</fieldset>'."\n";
													
							// site map priority
							echo TAB_6.'<fieldset id="UpdatePageOptionsPriority" class="AdminForm3" >'."\n";
								echo TAB_7.'Priority: <select name="sitemap_priority" > '."\n";
								
								for ($priority = 10; $priority > -1; $priority--)
								{
									if ($priority == $page_info['priority'])
									{ $selected = ' selected="selected"';}
									else { $selected = '';}

									echo TAB_8.'<option'.$selected.' value="'.$priority.'">'.$priority.'</option>' ."\n";					
								}

								echo TAB_7.'</select > (10 = Highest)'."\n";
									
								echo TAB_7.'<p class="FinePrint">* For use in Search Engine Indexing</p>' ."\n";							
							echo TAB_6.'</fieldset>'."\n";
							
						echo TAB_5.'</div>' ."\n";
					
					echo TAB_5.'</div>' ."\n";				
					
					echo TAB_5.'<input type="hidden" name="source_page_id" value = "'.$page_id.'" />'."\n";
					echo TAB_5.'<input type="hidden" name="source_page_name" value = "'.$page_info['page_name'].'" />'."\n";
					//echo TAB_5.'<input type="hidden" name="source_page_seq" value = "'.$page_info['seq'].'" />'."\n";

				echo TAB_4.'</fieldset>'."\n";					
				
			echo TAB_4.'</div>'."\n";	//	end Adjust Page settings (hide dive)
				
		echo TAB_3.'</form>'."\n";
				
	}
	
	
	
	//	FOR Adding Page
	else
	{
		echo '<h1> ADD a Page</h1>';
	}	
	
	
	echo '</body>'." \n";
	echo '</html>'." \n";

	// 	Now flush the output buffer
	ob_end_flush();		
	
}
	
?>