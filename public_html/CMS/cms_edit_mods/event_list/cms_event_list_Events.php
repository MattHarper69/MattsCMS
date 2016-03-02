<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'] . '&tab='.$_GET['tab'];
	
	//---------Update error msg:
	include_once ('cms_includes/cms_msg_update.php');
	
	$update_url = '/CMS/cms_update/cms_update_event_list_All.php';	
	
	//	need for Calendar Popup
	echo TAB_2.'<script type="text/javascript" >document.write(getCalendarStyles());</script>'."\n";
	echo TAB_2.'<div id="CancelDatePopup" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">'."\n";
	echo TAB_2.'</div>'."\n"; 
	
	echo TAB_2.'<form action="'.$update_url.'" name="update" method="post" enctype="multipart/form-data" >'."\n";

		echo TAB_3.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
			echo TAB_4.'<legend class="Centered" >'."\n";
					
				//-------------UPDATE BUTTON------------------------------------
				echo TAB_5.'<input type="submit" name="update_events" value="Update ALL displayed Information" />'."\n";			
			echo TAB_4.'</legend>'."\n";
	
			//	RESET button ======================================================				
			echo TAB_4.'<a href="'.$this_page.'"'."\n";
				echo TAB_5.' title="Reload this page to Reset all Settings" >' ."\n";
				echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
			echo TAB_4.'</a>'. "\n";	
			
			//=====================================================================================================================			

			//	edit Name 		
			echo TAB_4.'<fieldset class="AdminForm3">'."\n";
				echo TAB_5.'Listing Name: <input type="text" name="name" value="'.$event_settings['name'].'"' . "\n";
				echo TAB_5.' size="30" title="Add or Edit the '.$event_settings['event_alias'].' Listing Name here (not displayed) " /> '."\n";
			echo TAB_4.'</fieldset>'."\n";

			//	edit Event Alias 		
			echo TAB_4.'<fieldset class="AdminForm3">'."\n";
				echo TAB_5.'Event Alias : <input type="text" name="event_alias" value="'.$event_settings['event_alias'].'"' . "\n";
				echo TAB_5.' size="30" title="Add or Edit the Event Alias (what the events are refered as) " /> '."\n";
			echo TAB_4.'</fieldset>'."\n";			
			
			//	Add new Event Link
			$edit_event_href = 'cms_edit_mods/event_list/cms_event_list_edit_event_index.php';
			
			echo TAB_4.'<fieldset class="AdminForm3">'."\n";
				echo TAB_4.'<a class="ButtonLink" href="'.$edit_event_href.'?event_id=new&amp;mod_id='.$_GET['e'].'"'
						.' rel="CMS_ColorBox_EditEvent" title="Add a new '.$event_settings['event_alias'].'" >'
						.' Add New '.$event_settings['event_alias'].'</a>' ."\n";
			echo TAB_4.'</fieldset>'."\n";			
			
			echo TAB_4.'<fieldset class="AdminForm3" style="clear: left;">'."\n";

						echo TAB_6.'<script type="text/javascript">
							
							$(document).ready( function()
							{';								
								//	Hide / Display Heading Titles row
								echo TAB_7.'		
								$("#DisplayHeadTitles").click(function() {
									if (!this.checked)
									{							
										$("#FieldNamesRow").hide();							
									}
									else
									{															
										$("#FieldNamesRow").show();															
									}
										
								});							
					
							});		
						</script>'."\n";				
			
				if ($event_settings['display_heads'] == 'on')
				{
					$checked = 'checked="checked"';
					$hidden = '';
				}
				else 
				{
					$checked = '';
					$hidden = 'style="display: none;"';
				}						
									
				echo TAB_5.'<p><input type="checkbox" name="display_heads" '.$checked.' id="DisplayHeadTitles" />'. "\n";
				echo TAB_5.' - Display the Heading Titles in the Listing:</p>'. "\n";
					
				echo TAB_5.'<table class="CMS_EventListing">' . "\n";
					
					//	Headers
					echo TAB_6.'<tr>' . "\n";
										
						echo TAB_6.'<th><hr/></th>' . "\n";						
						echo TAB_6.'<th style="padding:5px 10px; text-align:center;">IMAGE</th>' . "\n";										
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">DATE</th>' . "\n";					
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">TIME</th>' . "\n";	
						for ($i = 1; $i < 6; $i++)
						{ echo TAB_6.'<th style="padding:5px 5px; text-align:center;">'.$i.'</th>' . "\n"; }
						
						echo TAB_6.'<th colspan="4"><hr/></th>' . "\n";
						
					echo TAB_6.'</tr>' . "\n";					
					
					//	Active
					echo TAB_6.'<tr>' . "\n";
					
						echo TAB_6.'<th style="padding:5px 5px; text-align:right">Display Field --&raquo; </th>' . "\n";
						
						if ($event_settings['display_image'] == 'on')
						{
							$checked = 'checked="checked"';
							$image_hidden = '';
						}
						else 
						{
							$checked = '';
							$image_hidden = 'style="display: none;"';
						}						

							
						echo TAB_6.'<td align="center">' . "\n";
							echo TAB_7.'<input type="checkbox" id="ActiveField_image" name="display_image" '.$checked.'/>' . "\n";
						echo TAB_6.'</td>' . "\n";
						
						//echo TAB_6.'<td class="Small" align="center">(not displayed)</td>' . "\n";
						
						if ($event_settings['display_date'] == 'on')
						{
							$checked = 'checked="checked"';
							$date_hidden = '';
						}
						else 
						{
							$checked = '';
							$date_hidden = 'style="display: none;"';
						}						
						
						echo TAB_6.'<td align="center"><input type="checkbox" id="ActiveField_date" name="display_date" '.$checked.'/></td>' . "\n";
	
						if ($event_settings['display_time'] == 'on')
						{
							$checked = 'checked="checked"';
							$time_hidden = '';
						}
						else 
						{
							$checked = '';
							$time_hidden = 'style="display: none;"';
						}						
						
						echo TAB_6.'<td align="center"><input type="checkbox" id="ActiveField_time" name="display_time" '.$checked.'/></td>' . "\n";
	
						for ($i = 1; $i < 6; $i++)
						{
							if ($event_settings['field_active_'.$i] == 'on')
							{$checked = 'checked="checked"';}
							else {$checked = '';}
							
							echo TAB_6.'<td align="center"><input type="checkbox" name="field_active_'.$i.'" id="ActiveField_'.$i.'" '
										.$checked.'/></td>' . "\n";
						}
						
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";
						
					echo TAB_6.'</tr>' . "\n";
					
					//	select Order By:
					echo TAB_6.'<tr>' . "\n";
					
						echo TAB_6.'<th style="padding:5px 5px; text-align:right">Order by --&raquo; </th>' . "\n";
										
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";						
						
						if ($event_settings['display_by'] == 'time')
						{$checked = 'checked="checked"';}
						else {$checked = '';}						
						
						echo TAB_6.'<td colspan="2" align="center" >' . "\n";
							echo TAB_7.'<input type="radio" name="display_by" id="DisplayByTime" value="time" '.$checked.'/>' . "\n";
						echo TAB_6.'</td>' . "\n";
						
						for ($i = 1; $i < 6; $i++)
						{

							if ($event_settings['display_by'] == 'field_'.$i)
							{$checked = 'checked="checked"';}
							else {$checked = '';}
							
							echo TAB_6.'<td align="center"><input type="radio" name="display_by" id="DisplayBy_'.$i.'" '
										.' value="field_'.$i.'" '.$checked.'/></td>' . "\n";
						}
						
						if ($event_settings['display_by'] == "seq")
						{$checked = 'checked="checked"';}
						else {$checked = '';}						
						
						echo TAB_6.'<td align="center" >' . "\n";
							echo TAB_7.'<input type="radio" name="display_by" id="DisplayBySeq" value="seq" '.$checked.'/>' . "\n";
						echo TAB_6.'</td>' . "\n";
						
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";
					
					echo TAB_6.'</tr>' . "\n";
			
					//	field name
					echo TAB_6.'<tr id="FieldNamesRow" '.$hidden.'>' . "\n";
					
						echo TAB_6.'<th style="padding:5px 5px; text-align:right">Heading Titles --&raquo; </th>' . "\n";						
						echo TAB_6.'<th style="padding:5px; text-align:center;">-</th>' . "\n";											
						echo TAB_6.'<td style="padding:5px; text-align:center; background-color:#eeeeee; border:solid 1px #fff;">' . "\n";	
							echo TAB_7.'<span class="DateShow" '.$date_hidden.'>Date</span>' . "\n";	
						echo TAB_6.'</td>' . "\n";				
						echo TAB_6.'<td style="padding:5px; text-align:center; background-color:#eeeeee; border:solid 1px #fff;">' . "\n";	
							echo TAB_7.'<span class="TimeShow" '.$time_hidden.'>Time</span>' . "\n";	
						echo TAB_6.'</td>' . "\n";
	
						for ($i = 1; $i < 6; $i++)
						{
							if ($event_settings['field_active_'.$i] != 'on')
							{$hidden = 'style="display: none;"';}
							else {$hidden = '';}
							
							echo TAB_6.'<th style="padding:5px 10px; text-align:center; background-color:#eeeeee; border:solid 1px #fff;">' . "\n";
								echo TAB_7.'<input type="text" name="field_head_'.$i.'" id="FieldNameInput_'.$i.'" '
										.$hidden.' value="'.$event_settings['field_head_'.$i].'" size="16"/>' . "\n";
							echo TAB_6.'</th>' . "\n";
						}

						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";					
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";						
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";						
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">-</th>' . "\n";
						
					echo TAB_6.'</tr>' . "\n";					
					

					
					//	Start Event Listing								
					echo TAB_6.'<tr>' . "\n";					
						echo TAB_6.'<th>'.$event_settings['event_alias'].' Name</th>' . "\n";												
						echo TAB_6.'<th colspan="8" style="background-color:#eeeeee;"><hr/></th>' . "\n";						
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">Order</th>' . "\n";					
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">Active</th>' . "\n";					
						echo TAB_6.'<th style="padding:5px 5px; text-align:center;">Edit</th>' . "\n";						
						echo TAB_6.'<th class="WarningMSGSmall" style="padding:5px 5px; text-align:center;">Delete</th>' . "\n";
					echo TAB_6.'</tr>' . "\n";
					
			//	 get all events to display
			$mysql_err_msg = 'Event Listing unavailable';	
			$sql_statement = 'SELECT * FROM mod_event_list'
											
											.' WHERE mod_id = "'.$_GET['e'].'"'
											.' ORDER BY '.$event_settings['display_by']
											;
					
			$event_listing_result = ReadDB ($sql_statement, $mysql_err_msg);

			if (mysql_num_rows ($event_listing_result) > 0)
			{
				$count = 1;
				$all_checked_active = 1;
				
				//	display Event info in Listing
				while ($event_listing_info = mysql_fetch_array ($event_listing_result))
				{							

					if ($count % 2)
					{$alt_BG_class = ' ShopItemListAltRow';}
					else {$alt_BG_class = '';}
						
					echo TAB_6.'<tr class="EventListRow'.$alt_BG_class.'" >'."\n";
					
						//	Event Name
						echo TAB_6.'<th style="background-color:#eeeeee;">' . "\n";
						
							//	send event ID
							echo TAB_9.'<input type="hidden" name="event_id_'.$count.'" value="'.$event_listing_info['event_id'].'" />'."\n";
						
							echo TAB_7.'<textarea name="display_name_'.$count.'" cols="20" rows="3" onkeyup="AutoResize(this);"'; 
								echo TAB_8.' onkeydown="AutoResize(this);" >'.$event_listing_info['display_name'];
							echo '</textarea>' . "\n";
													
						echo TAB_6.'</th>' . "\n";
						
						//	Event Image				
						echo TAB_6.'<td style="background-color:#eeeeee; border:solid 1px #fff;">' . "\n";

						echo TAB_6.'<script type="text/javascript">
							
							$(document).ready( function()
							{';								
				
								echo TAB_7.'		
								$("#ActiveField_image").click(function() {
									if(!$("#ActiveField_image").is(":checked"))
									{if (!this.checked)							
										$(".ShowImage").hide("slow");							
									}
									else
									{															
										$(".ShowImage").show("slow");															
									}
										
								});					

								$("#ActiveField_date").click(function() {
									if (!this.checked)
									{							
										$(".DateShow").hide("slow");	
									}
									else
									{															
										$(".DateShow").show("slow");									
									}
										
								});	

								$("#ActiveField_time").click(function() {
									if (!this.checked)
									{							
										$(".TimeShow").hide("slow");									
									}
									else
									{															
										$(".TimeShow").show("slow");
									}
										
								});'."\n";							
						echo TAB_6.'						
							});		
						</script>'."\n";							
												
							echo TAB_7.'<a class="ShowImage" '.$image_hidden.'  rel="CMS_ColorBox_EditEventImage"' . "\n";
							echo TAB_8.' href="'.$edit_event_href.'?mod_id='.$_GET['e'].'&amp;event_id='.$event_listing_info['event_id'].'&amp;tab=2"' . "\n";
								echo TAB_8.' title="Edit '.$event_settings['event_alias'].': '.$event_listing_info['name'].'">' . "\n";
							
							if (file_exists('../_images_user/thumbs/_list_event_id_' .$event_listing_info['event_id']. '.jpg')	)
							{
								echo TAB_8.'<img class="TinyThumb"'."\n";
									echo TAB_9.' src="/_images_user/thumbs/_list_event_id_' .$event_listing_info['event_id']. '.jpg"'."\n";
									echo TAB_9.' alt="thumbnail image for: '.$event_listing_info['display_name'].'" />'."\n";
							
							}
							
							echo TAB_7.'</a>' . "\n";
						
						echo TAB_6.'</td>' . "\n";
						
						//	Date and Time
						$event_date = date("d-m-Y",strtotime(substr($event_listing_info['time'], 0, 10)));
						
						$event_time = date("g:ia",strtotime(substr($event_listing_info['time'], 11, 5)));
						
						echo TAB_6.'<td style="padding:5px 10px; text-align:center; background-color:#eeeeee; border:solid 1px #fff;">' . "\n";
						
						//if ($event_settings['display_date'] != 'on')
						//{$hidden = 'style="display: none;"';}
						//else {$hidden = '';}
						
						echo TAB_6.'<span class="DateShow" '.$date_hidden.'>' . "\n";
							echo TAB_7.'<input type="text" name="event_date_'.$count.'"  value="'.$event_date.'" maxlength="10" size="10"' . "\n";
							echo TAB_8.' title="Insert a date for this '.$event_settings['event_alias'].' by clicking the date icon" />'."\n";
							
							//	do javascript date picker						
							echo TAB_7.'<script type="text/javascript">var SetDate_'.$count.' = new CalendarPopup("CancelDatePopup");'
									  .'</script>' ."\n"; 
											
							echo TAB_7.'<a href="#" onClick="SetDate_'.$count.'.select('
										.'document.forms[0].event_date_'.$count.',\'anchor_event_date_'.$count.'\',\'dd-MM-yyyy\'); return false;"'
										.' id="anchor_event_date_'.$count.'" title="Click to insert a Date" >' ."\n";  
								echo TAB_8.'<br/><img src="/images_misc/icon_calendar_32x32.png" alt="insert date" />' ."\n"; 
							echo TAB_7.'</a>' ."\n";

						echo TAB_6.'</span>' . "\n";	
						echo TAB_6.'</td>' . "\n";
						
										
						echo TAB_6.'<td style="padding:5px 10px; text-align:center; background-color:#eeeeee; border:solid 1px #fff;">' . "\n";
							
						//if ($event_settings['display_time'] != 'on')
						//{$hidden = 'style="display: none;"';}
						//else {$hidden = '';}
						echo TAB_6.'<span class="TimeShow" '.$time_hidden.'>' . "\n";
							echo TAB_9.'<input class="TimeShow" type="text" name="event_time_'.$count.'" '.$time_hidden
										.' maxlength="7" size="7" value="'.$event_time.'" />' . "\n";
						
						echo TAB_6.'</span>' . "\n";
						echo TAB_6.'</td>' . "\n";
						
						
						
						//	Misc Fields
						for ($i = 1; $i < 6; $i++)
						{
							
							
							
				echo TAB_5.'<script type="text/javascript">
					
					$(document).ready( function()
					{';								
						

					
						echo TAB_6.'		
						$("#ActiveField_'.$i.'").click(function() {
							if(!$("#ActiveField_'.$i.'").is(":checked"))
							{
								
								$("#FieldNameInput_'.$i.'").hide("slow");
								$(".Field_'.$i.'").hide("slow");							

							}
							else
							{
								//$("#DisplayBy_'.$i.'").removeAttr("disabled");								
								$("#FieldNameInput_'.$i.'").show("slow");																
								$(".Field_'.$i.'").show("slow");															
							}
								
						});'."\n";						
					


				echo TAB_5.'						
					});		
				</script>'."\n";							
							
							
							
							
							echo TAB_6.'<td style="text-align:center; background-color:#eeeeee; border:solid 1px #fff;">' . "\n";
								
								if ($event_settings['field_active_'.$i] != 'on')
								{$hidden = 'style="display: none;"';}
								else {$hidden = '';}
								
								echo TAB_7.'<textarea name="field_'.$i.'_'.$count.'" class="Field_'.$i.'" cols="16" rows="3" onkeyup="AutoResize(this);" onkeydown="AutoResize(this);" '.$hidden.'>';
									echo $event_listing_info['field_'.$i];
								echo '</textarea>' . "\n";							

							echo TAB_6.'</td>' . "\n";					
						
						}

						// Order
						echo TAB_6.'<td>' . "\n";
								echo TAB_7.'<input type="text" name="seq_'.$count.'" value="'.$event_listing_info['seq'].'" size="3"/>' . "\n";
						echo TAB_6.'</td>' . "\n";
						
						//	Active
						if ($event_listing_info['active'] == 'on')
						{
							$checked = 'checked="checked"';
						}
						else 
						{
							$checked = '';
							$all_checked_active = 0;	//	not all checked
						}
							
						echo TAB_6.'<td align="center">' . "\n";		
							echo TAB_7.'<input type="checkbox" name="active[]" class="CheckAllActive" value="'
								.$event_listing_info['event_id'].'" ' .$checked.'/>' . "\n";
						echo TAB_6.'</td>' . "\n";						
						
							
						//	Edit link
						echo TAB_6.'<td align="center">'."\n";
							echo TAB_7.'<a href="'.$edit_event_href.'?mod_id='.$_GET['e'].'&amp;event_id='.$event_listing_info['event_id'].'&amp;tab=1"'."\n";
							echo TAB_7.'rel="CMS_ColorBox_EditEvent" title="Edit '.$event_settings['event_alias'].': '.$event_listing_info['name'].'">'."\n";	
								echo TAB_8.'<img src="/images_misc/icon_edit_16x16.png" />'."\n";
							echo TAB_7.'</a>'."\n";	
						echo TAB_6.'</td>'."\n";
						
					
						//	Delete
						echo TAB_6.'<td align="center">'."\n";

							echo TAB_7.'<p>' ."\n";
							
								//	check-box
								echo TAB_10.'<input type="checkbox" name="delete[]" class="CheckAllDelete" value="'
										.$event_listing_info['event_id'].'" /> ' ."\n";
								
								echo TAB_8.'<a href="#" class="ConfirmDeleteButton"'
											.' title="Delete '.$event_settings['event_alias'].': '.$event_listing_info['name'].'">'."\n";	
						
									echo TAB_9.'<img src="/images_misc/icon_delete_16x16.png" />'."\n";
								echo TAB_8.'</a>'."\n";								
							echo TAB_7.'</p>'."\n";
							
							echo TAB_7.'<p class="WarningMSGSmall HideAtStart" style="border: solid 1px #cccccc; padding:5px;">' ."\n";
								
								//	Cancel link
								echo TAB_8.'<a href="#" class="CloseThisPanel" title="Do NOT Delete">' ."\n";
									echo TAB_9.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="float:right;"/>' ."\n";
								echo TAB_8.'</a>' ."\n";							
								
								//	OK DELETE Mod		
								echo TAB_8.'Confirm:<input type="submit" name="submit_delete_event_'.$count.'" style="color:#cc0000;"'."\n";
									echo TAB_9.' value="DELETE" title="Delete this '.$event_settings['event_alias'].'" />'."\n";
								
							echo TAB_7.'</p>' ."\n";	

						echo TAB_8.'</td>'."\n";
						
						
					echo TAB_6.'</tr>'. "\n";
				
					$num_records = $count;
					$count++;
				
				}				
				
					echo TAB_6.'<script type="text/javascript">
						
						$(document).ready( function()
						{

							//	Warning All selected for Deletion
							$("#CheckAllDeleteMaster").click(function() {
								if($("#CheckAllDeleteMaster").is(":checked"))
								{
									alert("You have selected Delete ALL:"
										+ "\n - Clicking the Update Button will Delete ALL '.$event_settings['event_alias'].'s");
								}
									
							});			
					
						});
					</script>'."\n";		
			
						
					echo TAB_6.'<tfoot>'."\n";
						echo TAB_7.'<tr class="Small">'."\n";
							echo TAB_8.'<td></td>'."\n";
							echo TAB_8.'<td>' . "\n";
								echo TAB_7.'<span class="ShowImage" '.$image_hidden.'>Click image<br/>to Edit</span>' . "\n";
							echo TAB_8.'</td>'."\n";
							echo TAB_8.'<td>'."\n";
								echo TAB_7.'<span class="DateShow" '.$date_hidden.'>dd-mm-yyyyy<br/>OR: 1 jan 2015</span>' . "\n";
							echo TAB_8.'</td>'."\n";
							echo TAB_8.'<td></td>'."\n";
							echo TAB_8.'<td colspan="5">* Note: You must set a field to "Display" <br/>before you can edit it</td>'."\n";
							echo TAB_8.'<td></td>'."\n";
							echo TAB_8.'<td align="center">'."\n";
								if ( $all_checked_active == 1)
								{ $checked = 'checked="checked"'; }
								else { $checked = ''; }
								echo TAB_9.'<input type="checkbox" name="active[]" class="CheckAll CheckAllActive" value="all" '.$checked.'/>'."\n";
								echo TAB_9.'<br/>select all'."\n";
							echo TAB_8.'</td>'."\n";
							
							echo TAB_8.'<td></td>'."\n";
							
							echo TAB_8.'<td align="center">'."\n";
							
								echo TAB_9.'<input type="checkbox" name="delete[]" class="CheckAll CheckAllDelete"'
									.' id="CheckAllDeleteMaster" value="all" />'."\n";
								echo TAB_9.'<br/>select all'."\n";
	
							echo TAB_8.'</td>'."\n";
							
						echo TAB_7.'</tr>'."\n";
					echo TAB_6.'</tfoot>'."\n";			
			

			
				echo TAB_5.'</table>' . "\n";		

					//	used to do db update and determin correct n# of total checkboxs
				echo TAB_5.'<input type="hidden" name="num_records" value="'.$num_records.'" />'."\n";					


			}
			
			else
			{
					// no events msg
					echo TAB_6.'<tr><td colspan="13" align="center">There are no '.$event_settings['event_alias'].'s set - ' . "\n";
						echo TAB_7.'<a href="'.$edit_event_href.'?event_id=new&amp;mod_id='.$_GET['e'].'"'. "\n";
							echo TAB_8.' rel="CMS_ColorBox_EditEvent" title="Add a new '.$event_settings['event_alias'].'">'. "\n";
							echo TAB_8.'[click to Add a New '.$event_settings['event_alias'].']</a>'. "\n";
					echo TAB_6.'</td></tr>' . "\n";
				
				echo TAB_5.'</table>' . "\n";					
			}
					

				
				$return_url = $this_page;
				echo TAB_5.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";
				echo TAB_5.'<input type="hidden" name="mod_id" value="'.$_GET['e'].'" />'."\n";			


	
			echo TAB_4.'</fieldset>'."\n";
			
			
		echo TAB_3.'</fieldset>'."\n";	
				
	
	echo TAB_2.'</form>'."\n";
	
?>