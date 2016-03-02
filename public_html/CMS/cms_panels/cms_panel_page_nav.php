<?php

    // no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

if ($_SESSION['access'] < 4 )
{
	//$num_of_pages = mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg));

	echo "\n";	
	echo TAB_2.'<!--	START Page Navigation Config Panel code 	-->'."\n";
	echo "\n";
	
	echo TAB_2.'<div class="CMS_SubPanel" id="CMS_PageNavOptionsPanel" style="clear:both; display:none;">'."\n";

		//	Display Name and ID
		if ($page_info['active'] != 'on') { $deactivated = ' [ De-Activated ]';}
		else { $deactivated = '';}
		echo TAB_3.'<h3 style="position:relative; float:left;">NAVIGATION OPTIONS FOR PAGE: '."\n";			
			
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
			echo TAB_4.'<a href="javascript:ClosePageNavOptionsPanel()" title="Close This Panel" >' ."\n";
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

		
		//	Config Page NAV options
		echo TAB_3.'<form action="CMS/cms_update/cms_update_page_config.php" method="post" >'."\n";
						
			echo TAB_4.'<fieldset id="UpdatePageNavOptions" class="AdminForm1" style="clear:both;">'."\n";	

				//	Tabbed Nav
				echo TAB_5.'<ul id="PageNavSettingsTabNav" class="TabPanelNavLinks">' ."\n";
					echo TAB_6.'<li id="OpenTabPanel_6"><a href="#TabPanel_6" onclick="javascript:SetOpenTabPanel(6);">General</a></li>' ."\n";
					echo TAB_6.'<li id="OpenTabPanel_7"><a href="#TabPanel_7" onclick="javascript:SetOpenTabPanel(7);">Location</a></li>' ."\n";
					echo TAB_6.'<li id="OpenTabPanel_8"><a href="#TabPanel_8" onclick="javascript:SetOpenTabPanel(8);">Site Map</a></li>' ."\n";
				echo TAB_5.'</ul>' ."\n";
								
				echo TAB_5.'<div class="TabPanelContainer" id="PageNavSettingsTabs" >' ."\n";
				
					//  UPDATE Page Options Button
					echo TAB_6.'<button type="submit" class="ButtonLink" name="update_page_nav_submit">'."\n";	
						echo TAB_7.'Update Page Navigation Options</button>'."\n";	
						
					echo TAB_5.'<div id="TabPanel_6" class="AdminFormTabPanel">'."\n";
					
						//	Menu Text
						echo TAB_6.'<fieldset id="UpdatePageOptionsName" class="AdminForm3">'."\n";
							echo TAB_7.'Edit Menu Text: <input type="text" name="menu_text" value="'.$page_info['menu_text'].'"  size="64" maxlength="64"/> '."\n";
							echo TAB_7.'<p class="FinePrint">* As displayed in the Navigation Menu</p>' ."\n";
						echo TAB_6.'</fieldset>'."\n";

						//	Url alias 
						echo TAB_6.'<script type="text/javascript">
									
							$(document).ready( function()
							{								
						
								//	Replace spaces " " with underscores "_" in URL
								$("#URLAlias").keyup(function()
								{
									var str = $(this).val();
									var res = str.replace(/[ ]/g, "_");
									$(this).val(res);
								});						
							});
																						
							</script>'."\n";							
						
						echo TAB_6.'<fieldset id="UpdatePageOptionsURL" class="AdminForm3">'."\n";
							echo TAB_7.'URL ALIAS: &quot;http://'.SITE_URL.'/' ."\n";
							echo TAB_7.'<input type="text" id="URLAlias" name="url_alias" value="'.$page_info['url_alias'].'"  size="64" />&quot; '."\n";
							echo TAB_7.'<p class="FinePrint">* target destination</p>' ."\n";
						echo TAB_6.'</fieldset>'."\n";
/* 	
						//	Nav Icon	//	To Be Added Later ??????????????????????????????????????????
						echo TAB_6.'<fieldset id="UpdatePageOptionsIcon" class="AdminForm3">'."\n";
							echo TAB_7.'<p>Page&#39;s Icon:</p>' ."\n";
							
							if ($page_info['icon_image'] != "" AND $page_info['icon_image'] != NULL)
							{ $icon_image = $page_info['icon_image'];}
							else { $icon_image = DEFAULT_NAV_ICON;}
							
							echo TAB_7.'<img class="NavIcon" src="/_images_user/'.$icon_image.'" alt="'.$icon_image.'" />'."\n";
		//	Edit this		echo TAB_7.'<a class="ButtonLink" href="OPEN POP_UP DIV with UPLOAD or CHOOSE image options"> CHANGE </a>'."\n";
						
							echo TAB_7.'<p class="FinePrint">* For use in Nav. Menus</p>' ."\n";
						echo TAB_6.'</fieldset>'."\n"; 
*/					 
						
						//	Pop-up text						
						echo TAB_6.'<fieldset id="UpdatePageOptionsPopUpText" class="AdminForm3">'."\n";
							echo TAB_7.'<p>Pop-up (hint) Text:</p>' ."\n";
							echo TAB_7.'<textarea name="popup_text" class="CMStextArea" cols="70" rows="2"'
									  .' onkeyup="AutoResize(this);" onkeydown="AutoResize(this);"' ."\n";
								echo TAB_8.'>'.$page_info['popup_text'].'</textarea>' ."\n";
							echo TAB_7.'<p class="FinePrint">* This is displayed when a viewer hovers over the menu link</p>' ."\n";
						echo TAB_6.'</fieldset>'."\n"; 

					echo TAB_5.'</div>'."\n";
					

					
	//////////////////////		EDITED TO HERE		////////////////////////////////////////////////////////////////////
	

				
					echo TAB_5.'<div id="TabPanel_7" class="AdminFormTabPanel">' ."\n";		
					
					
						//	Parent Page
						echo TAB_6.'<fieldset id="UpdatePageOptionsParent" class="AdminForm3">'."\n";
							echo TAB_7.'Parent Page - ( This page is in a Sub-Group of:' ."\n";
							
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
										url: "CMS/cms_update/cms_update_page_config.php",
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
									if ( $page_info['page_id'] == $page_id_drop)
									{ $disabled = ' disabled="disabled"';}
									else { $disabled = '';}										
									
									echo TAB_8.'<option value="'.$page_id_drop.'"'.$selected.$disabled.' >'.$page_name_drop.'</option>' ."\n";
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
									echo TAB_8.' : Include this page in the <strong>'.$display_name.'</strong> Navigation Menu.'."\n";	
		
								//	===================================================================================
								//	do LInks: "Include ALL Pages in the Header menus" and  "Remove ALL pages from the Header Menu"  etc
								//	===================================================================================	
								echo TAB_7.'</p>' ."\n";
								
							}
							
							//echo TAB_7.'</ul>' ."\n";
																				
						echo TAB_6.'</fieldset>'."\n";

					echo TAB_5.'</div>'."\n";
					
					echo TAB_5.'<div id="TabPanel_8" class="AdminFormTabPanel">' ."\n";
					
						// include in site map 
						echo TAB_6.'<h3> SITE MAP (Used for Search engine Indexing): </h3>'."\n";
						
						echo TAB_6.'<fieldset id="UpdatePageOptionsInSiteMap" class="AdminForm3">'."\n";	
						
							if ($page_info['include_in_sitemap'] == 'on') { $checked = ' checked="checked"'; }
							else { $checked = '';}						
						
							echo TAB_7.'<input type="checkbox" name="include_in_sitemap" id="PageOptionsIncludeInSiteMap"'.$checked. "\n";
								echo TAB_8.'onclick="javascript:ShowPagePriority();"/>' ."\n";
							echo TAB_7.': Include this page in the Site Map'."\n";
								
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
						
					///////////		To be added later .......
						//echo TAB_6.'<p class="FinePrint" style="clear:both;">'
						//	.' * More Site Map options are available in the Site Navigation Section</p>'."\n";
						
					echo TAB_5.'</div>'."\n";
					
				echo TAB_5.'</div>'."\n";
					
				//	Send page ID
				echo TAB_5.'<input type="hidden" name="page_id" value = "'.$page_id.'" />'."\n";
				
			echo TAB_4.'</fieldset>'."\n";					
		echo TAB_3.'</form>'."\n";

	echo TAB_2.'</div>'."\n";		
}
	
		
?>