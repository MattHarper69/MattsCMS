<?php

    // no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
if ($_SESSION['access'] < 5 )	
{
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
							
	echo "\n";	
	echo TAB_2.'<!--	START Page General Settings Panel code 	-->'."\n";
	echo "\n";
								
	echo TAB_2.'<div class="CMS_SubPanel" id="CMS_PageOptionsPanel"  style="clear:both; display:none;">'."\n";
	
		//	Display Name and ID
		if ($page_info['active'] != 'on') { $deactivated = ' [ De-Activated ]';}
		else { $deactivated = '';}
		echo TAB_3.'<h3 style="position:relative; float:left;">GENERAL SETTINGS FOR PAGE: '."\n";			

			//echo TAB_4.'<select onchange="window.open(this.options[this.selectedIndex].value,\'_top\');">' ."\n";
			echo TAB_4.'<select onchange="location.href=this.value;">' ."\n";
			
			foreach ($all_pages as $page_id_drop => $page_name_drop)
			{
				if ( $page_info['page_id'] == $page_id_drop)
				{ $selected = ' selected="selected"';}
				else { $selected = '';}
				echo TAB_5.'<option value="index.php?p='.$page_id_drop.'"'.$selected.' >'.$page_name_drop.'</option>'."\n";
			}
						
			echo TAB_4.'</select>' ."\n";				
			echo TAB_4.' -- (Page ID:<span class="Hilight"> '.$page_info['page_id'].' </span>)' ."\n";
			echo TAB_4.'<span class="RedHeading">'.$deactivated.'</span>' ."\n";
		echo TAB_3.'</h3>' ."\n";	
			
		//	CLOSE PANEL Button			
		echo TAB_3.'<div style="position:relative; float:right;">' ."\n";			
			echo TAB_4.'<a href="javascript:ClosePageOptionsPanel()" title="Close This Panel" >' ."\n";
				echo TAB_5.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="border:none;"/>' ."\n";
			echo TAB_4.'</a>'. "\n";	
		echo TAB_3.'</div>' ."\n";	
		
		//	Prev / Next Page buttons							
		echo TAB_3.'<p class="CMS_PrevNextPageBar"  style="position:relative; float:right;">'."\n";			

			//	<< PREV. Page Options button	======================================================
			echo TAB_4.'<a href="index.php?p='.$prev_page_id.'" class="CMS_Button_PrevPageOptionsPanel"'."\n";
				echo TAB_5.' title="Previous Page: &quot;'.$prev_page_name.'&quot;" >' ."\n";
				echo TAB_5.'<img src="/images_misc/icon_config_prev_32x32.png" alt="Prev Page" style="border:none;"/>' ."\n";
			echo TAB_4.'</a>'. "\n";

			//	NEXT >> Options button Close
			echo TAB_4.'<a href="index.php?p='.$next_page_id.'" class="CMS_Button_PrevPageOptionsPanel"'."\n";
				echo TAB_5.' title="Next Page: &quot;'.$next_page_name.'&quot;" >' ."\n";
				echo TAB_5.'<img src="/images_misc/icon_config_next_32x32.png" alt="Next Page" style="border:none;"/>' ."\n";
			echo TAB_4.'</a>'. "\n";
			
		echo TAB_3.'</p>'."\n";


		
		//	Config Page options
		echo TAB_3.'<form action="CMS/cms_update/cms_update_page_config.php" method="post" >'."\n";
						
			echo TAB_4.'<fieldset id="UpdatePageOptions" class="AdminForm1" style="clear:both;">'."\n";	

				//	Tabbed Nav
				echo TAB_5.'<ul id="PageGeneralSettingsTabNav" class="TabPanelNavLinks">' ."\n";
					echo TAB_6.'<li id="OpenTabPanel_1"><a href="#TabPanel_1" onclick="javascript:SetOpenTabPanel(1);">General</a></li>' ."\n";
					echo TAB_6.'<li id="OpenTabPanel_2"><a href="#TabPanel_2" onclick="javascript:SetOpenTabPanel(2);">Title Tag</a></li>' ."\n";
					echo TAB_6.'<li id="OpenTabPanel_3"><a href="#TabPanel_3" onclick="javascript:SetOpenTabPanel(3);">Active Areas</a></li>'."\n";
					echo TAB_6.'<li id="OpenTabPanel_4"><a href="#TabPanel_4" onclick="javascript:SetOpenTabPanel(4);">Active Menus</a></li>'."\n";
					echo TAB_6.'<li id="OpenTabPanel_5"><a href="#TabPanel_5" onclick="javascript:SetOpenTabPanel(5);">Syncronization</a></li>'."\n";
					echo TAB_6.'<li id="OpenTabPanel_6"><a href="#TabPanel_6" onclick="javascript:SetOpenTabPanel(6);">Security</a></li>' ."\n";
				echo TAB_5.'</ul>' ."\n";
				
				
				echo TAB_5.'<div class="TabPanelContainer" id="PageGeneralSettingsTabs" >' ."\n";
				
					//  UPDATE Page Options Button
					echo TAB_6.'<button type="submit" class="ButtonLink" name="update_page_config_submit">Update Page Options</button>'."\n";
					
					//	General Settings
					echo TAB_5.'<div id="TabPanel_1" class="AdminFormTabPanel">'."\n";
					
						//	edit Name 				
						echo TAB_6.'<fieldset id="UpdatePageOptionsName" class="AdminForm3">'."\n";
							echo TAB_7.'Edit page name: <input type="text" name="page_name" value="'.$page_info['page_name'].'" size="64" /> '."\n";
							echo TAB_7.'<p class="FinePrint">* For reference only (not displayed)</p>' ."\n";
						echo TAB_6.'</fieldset>'."\n";
						
						//	Active
						if ($page_info['active'] == 'on') { $checked = ' checked="checked"'; }
						else { $checked = '';}
						
						echo TAB_6.'<fieldset id="UpdatePageOptionsPageActive" class="AdminForm3">'."\n";
							echo TAB_7.'<input type="checkbox" name="page_active" '.$checked.' />'
								.' : Set this page as ACTIVE (uncheck to HIDE)'."\n";
						echo TAB_6.'</fieldset>'."\n";
						
						echo TAB_6.'<fieldset class="AdminForm3" id="AutoHeadingDiv">'."\n";
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
										.$page_info['page_name'].'&quot as a Heading at the top of this page" >'. "\n";							
								echo TAB_8.'<input type="radio" id="AutoHeadingName" name="auto_heading" value="name"'
										.$name_checked.'/>' . "\n";
								echo TAB_8.'<span>&raquo; Display the Page&#39;s Name: <strong>&quot;'.$page_info['page_name'].
											'&quot</strong> as a Heading at the top of this page</span>' . "\n";
							echo TAB_7.'</p>' . "\n";						
						
							echo TAB_7.'<p title="Display the Page&#39;s Menu Title &quot;'
										.$page_info['menu_text'].'&quot as a Heading at the top of this page" >'. "\n";							
								echo TAB_8.'<input type="radio" id="AutoHeadingName" name="auto_heading" value="menu"'
										.$menu_checked.'/>' . "\n";
								echo TAB_8.'<span>&raquo; Display the Page&#39;s Menu Title: <strong>&quot;'.$page_info['menu_text'].
											'&quot</strong> as a Heading at the top of this page</span>' . "\n";
							echo TAB_7.'</p>' . "\n";	

							echo TAB_7.'<p title="Do NOT Display a Heading at the top of this page" >'. "\n";
								echo TAB_8.'<input type="radio" id="AutoHeadingName" name="auto_heading" value=""'
										.$no_checked.'/>' . "\n";
								echo TAB_8.'<span>&raquo; Do <strong>NOT</strong> Display a Heading at the top of this page</span>' . "\n";
							echo TAB_7.'</p>' . "\n";						
							
						echo TAB_6.'</fieldset>'."\n";
						
						//	Comments						
						echo TAB_6.'<fieldset id="UpdatePageOptionsComments" class="AdminForm3"  style="clear:left;">'."\n";
							echo TAB_7.'<p>Edit Comments:</p>' ."\n";
							echo TAB_7.'<textarea name="cms_comments" class="CMStextArea" cols="64" rows="2"'
									  .' onkeyup="AutoResize(this);" onkeydown="AutoResize(this);"' ."\n";
								echo TAB_8.'>'.$page_info['cms_comments'].'</textarea>' ."\n";
							echo TAB_7.'<p class="FinePrint">* For reference only (not displayed)</p>' ."\n";
						echo TAB_6.'</fieldset>'."\n"; 
										
					echo TAB_5.'</div>'."\n";
					
					//	edit Title Tag Options
					echo TAB_5.'<div id="TabPanel_2" class="AdminFormTabPanel">'."\n";
						
						echo TAB_6.'<script type="text/javascript">
								
							$(document).ready( function()
							{								
								
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


								$(function(){
								  $("#titleTagText").keyup(function(){
									 $("#TitleTagTextUnique").text($(this).val());
								  });
								});								
							
							});
							
						</script>'."\n";
					
					
						echo TAB_6.'<h3>TITLE TAG SETTINGS:</h3>'."\n";
						
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
							
							echo TAB_8.'<p title="Display unique text for this page in the Title Tag" >'. "\n";	
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

							echo TAB_8.'<p title="Display this Page&#39;s Name &quot;' . $page_info['page_name'] .'&quot; in Title Tag" >'
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
												. TITLE_SEPERATOR_SYMBOL . '</b></span>' . $page_info['page_name'];
							
							//	Build Title Tag
							$title_tag = '<span id="TitleTageSiteName"'.$style_site_name.'>' . $title_site_name . '</span>'; 
																		
							$title_tag .= '<span id="TitleTagTextGlobal"'.$style_global.'>' . SITE_TITLE_TAGLINE . '</span>'
										. '<span id="TitleTagTextUnique"'.$style_unique.'><i>' . $page_info['titleTag_text'] . '</i></span>';
																							
							$title_tag .= '<span id="TitleTagePageName"'.$style_page_name.'>' . $title_page_name . '</span>';
							
							//	Display Tag	
							echo TAB_8.'<b>&lt;title&gt;</b>' . $title_tag . '<b>&lt;/title&gt;</b>' . " \n"; 	

						echo TAB_7.'</fieldset>'."\n";
				
					echo TAB_5.'</div>'."\n";
					
					//	DIVs ACTIVE ??											
					echo TAB_5.'<script type="text/javascript">
								
						//	Show / Hide Mini Page Layout when "Active" CHECKED
						function SwitchMiniLayoutDivsCheck(div_name)
						{
							if ($("#SetActive_" + div_name).is(":checked") ) 
							{
								$("#CMS_MiniPageLayout_" + div_name).css("background-color", "transparent");
							}
							
							else
							{
								$("#CMS_MiniPageLayout_" + div_name).css("background-color", "#ffffff");
							}		
						}
											
						//	Show / Hide Mini Page Layout when "Active" Div Clicked
						function SwitchMiniLayoutDivsClick(div_name)
						{
							if ($("#SetActive_" + div_name).is(":checked") ) 
							{
								$("#CMS_MiniPageLayout_" + div_name).css("background-color", "#ffffff");			
								$("#SetActive_" + div_name).prop(\'checked\', false);
							}
							
							else
							{
								$("#CMS_MiniPageLayout_" + div_name).css("background-color", "transparent");
								$("#SetActive_" + div_name).prop(\'checked\', true);
							}		
						}	
						
					</script>'."\n";
					
					echo TAB_5.'<div id="TabPanel_3" class="AdminFormTabPanel">'."\n";
					
						echo TAB_6.'<fieldset class="AdminForm3" >'."\n";
							echo TAB_7.'<h3> PAGE AREAS ACTIVATION (Display / Hide): </h3>'."\n";

							//echo TAB_7.'<ul>' ."\n";
							
							foreach ($div_name_array as $div_name => $display_name)
							{
								echo TAB_8.'<script type="text/javascript" >' ."\n";	
									echo TAB_9.'$(document).ready(function() ' ."\n";	
									echo TAB_9.'{SwitchMiniLayoutDivsCheck(\''.$div_name.'\');});	' ."\n";				
								echo TAB_8.'</script>'."\n";
								
								echo TAB_8.'<p>' ."\n";

									if ($page_info[$div_name.'_active'] == 'on') { $checked = ' checked="checked"'; }
									else { $checked = '';}
									
									echo TAB_9.'<input type="checkbox" name="'.$div_name.'_active" id="SetActive_'.$div_name.'"'.$checked."\n";	
										echo TAB_10.' onclick="javascript:SwitchMiniLayoutDivsCheck(\''.$div_name.'\');"/>'."\n";
										echo TAB_10.' : Set this Page&#39;s <strong>'.$display_name.'</strong> as ACTIVE.'."\n";	
		
						//	===================================================================================
						//	do LInks: "ACTIVATE Headers on ALL Pages" and  "DE-ACTIVATE Headers on ALL Pages" etc
						//	===================================================================================
		
								echo TAB_8.'</p>' ."\n";
											
							}
								
							//echo TAB_7.'</ul>' ."\n";

						
						echo TAB_6.'</fieldset>'."\n"; 
						
						//	Active Divs Preview
						$id_tag_prefix = '';	//	Used to separate function names and ID tag name in JS (if Needed)
						include_once ('CMS/cms_includes/mini_layout_divs.php');
						
					echo TAB_5.'</div>'."\n";


					
					//	MENUS ACTIVE ??				
					echo TAB_5.'<div id="TabPanel_4" class="AdminFormTabPanel">'."\n";
					
						echo TAB_6.'<fieldset class="AdminForm3" >'."\n";
							echo TAB_7.'<h3> NAVIGATION MENUS ACTIVATION (Display / Hide): </h3>'."\n";	
							
							//echo TAB_7.'<ul>' ."\n";
																
							foreach ($menu_name_array as $menu_name => $display_name)
							{
			
								echo TAB_8.'<script type="text/javascript" >	
									
						$(document).ready(function()
						{
							
							//	hi-light mini div menus in preview when hover overing each div name and select box
							$("#MiniPageLayoutHover_'.$menu_name.'").mouseover(function()
							{
								$("#CMS_MiniPageLayout_'.$menu_name.'").addClass("CMS_MiniPageLayout_HilightBorder");
							});

								 

							$("#MiniPageLayoutHover_'.$menu_name.'").mouseout(function()
							{
							  $("#CMS_MiniPageLayout_'.$menu_name.'").removeClass("CMS_MiniPageLayout_HilightBorder");

							});
							
						
						});

									
		</script>'."\n";
								
								echo TAB_8.'<p id="MiniPageLayoutHover_'.$menu_name.'">' ."\n";

/* 
									if ($page_info[$menu_name.'_active'] == 'on') { $checked = ' checked="checked"'; }
									else { $checked = '';}
									
									echo TAB_9.'<input type="checkbox" name="'.$menu_name.'_active" id="SetActive_'.$menu_name.'"'."\n";
										echo TAB_10.$checked.' onclick="javascript:SwitchMiniLayoutDivsCheck(\''.$menu_name.'\');"/>'."\n";
										echo TAB_10.' : Set this Page&#39;s <strong>'.$display_name.' Menu </strong> as ACTIVE.'."\n";	
 */
 
 									echo TAB_10.'Set this Page&#39;s <strong>'.$display_name.' Menu </strong> as : '."\n";
									
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
 
 
							//	===================================================================================
							//	do LInks: "ACTIVATE menus on ALL Pages" and  "DE-ACTIVATE menues on ALL Pages" etc
							//	===================================================================================
							
								echo TAB_8.'</p>' ."\n";
				
							}
							
							//echo TAB_7.'</ul>' ."\n";
						
						echo TAB_6.'</fieldset>'."\n"; 
										
						//	Active Menus Preview
						include_once ('CMS/cms_includes/mini_layout_menus.php');

					echo TAB_5.'</div>'."\n";

					//	SYNCRONIZATION
					echo TAB_5.'<div id="TabPanel_5" class="AdminFormTabPanel">'."\n";
					
						echo TAB_6.'<h3>SYNCRONIZE THIS PAGE&#39;S AREAS WITH OTHER PAGES:</h3>'."\n";
					
						//echo TAB_6.'<ul>' ."\n";
						
						$d = 1;
						foreach ($div_name_array as $div_name => $display_name)
						{
							echo TAB_7.'<p>' ."\n";

								if ($page_info['sync_'.$div_name] == 'on')
								{ $checked = ' checked="checked"'; }
								else { $checked = '';}
								
								echo TAB_8.'<input type="checkbox" name="sync_'.$div_name.'" '.$checked.' />'."\n";
								echo TAB_8.' - Update this Page&#39;s <strong>'.$display_name.'</strong>'
											.' when other page&#39;s '.$display_name.'s are updated.'."\n";
							
								//	check to see if other pages have syn turned on for this div
								$mysql_err_msg = 'Fetching Other Pages Module sync statis';					
								$sql_statement = 'SELECT sync_'.$div_name.' FROM page_info'

																		.' WHERE sync_'.$div_name.' = "on"'
																		.' AND page_id != '.$page_id
																		;
											
								$num_other_syncs = mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg));
								if($num_other_syncs > 0)
								{
									echo TAB_8.' - <a class="ButtonLink"'
												.' href="CMS/cms_update/cms_update_mod_sync.php?p='.$page_id.'&amp;d='.$d.'" >'
												.'Sync this '.$display_name.' now</a>'."\n";							
								}
		
							echo TAB_7.'</p>' ."\n";

							
							//	$d is div id
							if ($d != 2) {$d++;}
							else {$d += 2;}
										
						}
						
						//echo TAB_6.'</ul>' ."\n";
					
					echo TAB_5.'</div>'."\n";
					
					//	SECURITY
					echo TAB_5.'<div id="TabPanel_6" class="AdminFormTabPanel" >'."\n";
					
						echo TAB_6.'<h3>PAGE SECURITY SETTINGS:</h3>'."\n";		

						echo TAB_6.'<fieldset id="UpdatePageOptionsRequiresLogin" class="AdminForm3">'."\n";
							//	Requires Log-in
							if ($page_info['requires_login'] == 'on') { $checked = ' checked="checked"'; }
							else { $checked = '';}						
						
							echo TAB_7.'<input type="checkbox" name="requires_login" id="PageOptionsRequiresLogin"'.$checked. "\n";
								echo TAB_8.' onclick="javascript:ShowPageAccessCode();" /> : This Page requires a Log-in to be viewed'."\n";
							echo TAB_7.'<p class="FinePrint">* Only the Centre Column of the page will be hidden</p>'."\n";
						echo TAB_6.'</fieldset>'."\n";
						
					
						//	Access code								
						echo TAB_6.'<fieldset id="UpdatePageOptionsAccessCode" class="AdminForm3">'."\n";
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
					
				echo TAB_5.'</div>' ."\n";
				
				echo TAB_5.'<input type="hidden" name="page_id" value = "'.$page_id.'" />'."\n";

			echo TAB_4.'</fieldset>'."\n";					
		echo TAB_3.'</form>'."\n";

	echo TAB_2.'</div>'."\n";	
	
}

		
?>