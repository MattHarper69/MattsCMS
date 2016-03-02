<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

//	has a contact been chosen to Send Email???
if (isset ($_REQUEST['emailto']) AND $_REQUEST['emailto'] != "" AND $_REQUEST['emailto'] != NULL )
{ 
	include ('email_form.php');
}

//	has a contact been chosen to view???
elseif (isset ($_REQUEST['conid']) AND $_REQUEST['conid'] != "" AND $_REQUEST['conid'] != NULL )
{
	PrintContactDetails ( NULL, $_REQUEST['conid'], NULL, $page_id, $mod_id);
}

else
{ 
	//	Get the settings for the Module
	$mysql_err_msg = 'This ContactInfomation unavailable';	
	$sql_statement = 'SELECT * FROM mod_contact_settings WHERE mod_id="'.$mod_id.'"'
														.' ORDER BY seq';
	
	$num_found = mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg));
	if ($num_found > 0)
	{

		
		$contact_settings_result = ReadDB ($sql_statement, $mysql_err_msg);
		while ($contact_settings_info = mysql_fetch_array ($contact_settings_result))	
		{
		
			if($contact_settings_info['display_group_name'] == "on")
			{
				//	Get Group Name and print
				$mysql_err_msg = 'Contact Groups Infomation unavailable';	
				$sql_statement = 'SELECT name FROM mod_contact_groups WHERE group_id = "'.$contact_settings_info['group_id'].'"';
				$group_name_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
				if ($group_name_info['name'] != "" AND $group_name_info['name'] != NULL )
				{ echo TAB_8.'<h3 class="ContactGroupName">'.HiliteText ($group_name_info['name'] ).'</h3>'. "\n"; }
			}
			
			PrintContactDetails 
			(
				  $contact_settings_info['group_id']
				, $contact_settings_info['contact_id']
				, $contact_settings_info['info_in_new_win']
				, $page_id
				, $mod_id
			);
			
			
			echo TAB_8.'<hr class="ContactName" />'. "\n";
			
		}
							
			
	}		

}


//=======================================================================================================


	function PrintContactDetails ( $group_id, $contact_id, $info_in_new_win, $page_id, $mod_id)
	{								
	
		if (isset($_REQUEST['search_str']))
		{
			$search_str = $_REQUEST['search_str'];
		}
		else
		{
			$search_str = '';
		}		
		
		if (isset($_REQUEST['remove_hilight']))
		{
			$remove_hilight = $_REQUEST['remove_hilight'];
		}
		else
		{
			$remove_hilight = '';
		}
		
		//	Get Contact Listings settings - read from db	----------
		$mysql_err_msg = 'Contact List Infomation unavailable';	
			
		//	Looking up Group or Individual Contact ??? ---- if the "contact_id" contains a value, get that contact and not the whole group
		if ($contact_id != 0 AND $contact_id != NULL )
		{
			//	Looke up individual Contact
			$sql_statement = 'SELECT * FROM mod_contact_items WHERE contact_id = "'.$contact_id.'"'
																.' AND mod_contact_items.active = "on"';
		}
			
		else
		{
			//	Look up whole Group
			$sql_statement = 'SELECT * FROM mod_contact_items, mod_contact_group_asign'
														.' WHERE mod_contact_group_asign.group_id = "'.$group_id.'"'
														.' AND mod_contact_group_asign.contact_id = mod_contact_items.contact_id'
														.' AND mod_contact_items.active = "on"'													
														.' ORDER BY mod_contact_items.seq';
				
		}
					
		$num_found = mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg));


		if ($num_found > 0)
		{			
			
			//-------start LISTING ------------------------------------------
			echo TAB_7."\n";
			echo TAB_7.'<!--  Start Contact Listing  --> '. "\n";
			echo TAB_7."\n";	
				
			if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
			{$hover_class = ' HoverShow';}

			else
			{$hover_class = '';}
			
			echo TAB_7.'<div class="ContactList'.$hover_class.'" >'."\n";			
			
			
			$contact_items_result = ReadDB ($sql_statement, $mysql_err_msg);
			while ($mod_contact_items = mysql_fetch_array ($contact_items_result))		
			{	
				
				//	name and Role
				if ( $mod_contact_items['role'] != '' AND $mod_contact_items['role'] != NULL )
				{$role = HiliteText ($mod_contact_items['role'] ).': ';}
					
				else {$role = "";}
					
				if ( $mod_contact_items['name'] != '' AND $mod_contact_items['name'] != NULL )
				{$name = HiliteText ($mod_contact_items['name'], $search_str, $remove_hilight );}
					
				else {$name = "";}	
					
				if ( $info_in_new_win == "on" )
				{
					$open_a_tag = '<a class="ContactListMoreInfoLink"'
									.' href="index.php?p='.$page_id.'&amp;conid='.$mod_contact_items['contact_id'].'"'
					//$open_a_tag = '<a class="ContactNameLink" href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&amp;conid='.$mod_contact_items['contact_id'].'"'
										.' title="Click here for the contact details of: '.$mod_contact_items['name'].'" >';
					$close_a_tag = '</a>';
				}
				
				elseif 
				( 
						$mod_contact_items['link_email'] == "mailto"
					AND	$mod_contact_items['email'] != "" AND  $mod_contact_items['email'] != NULL	
				)
				{
					$open_a_tag = '<a class="ContactListEmailLink" href="mailto:'.$mod_contact_items['contact_id'].'"'
					//$open_a_tag = '<a class="ContactNameLink" href="mailto:'.$mod_contact_items['email'].'"'// 	<<<<------------------edit 
										.' title="Click here to send '.$mod_contact_items['name'].' an email" >';
					$close_a_tag = '</a>';
				}
				
				elseif 
				( 		$mod_contact_items['link_email'] == "form" 
					AND	$mod_contact_items['email'] != "" AND  $mod_contact_items['email'] != NULL
				)
				{
					$open_a_tag = '<a class="ContactListEmailLink" href="index.php?p='.$page_id.'&amp;emailto='.$mod_contact_items['contact_id'].'"'
					//$open_a_tag = '<a class="ContactNameLink" href="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&amp;emailto='.$mod_contact_items['contact_id'].'"'
										.' title="Click here to send '.$mod_contact_items['name'].' an email" >';
					$close_a_tag = '</a>';
				}
				
				else
				{
					$open_a_tag = '<span class="ContactListName" >';
					$close_a_tag = '</span>';					
				}
					
				echo TAB_8.'<h4 class="ContactName" id="Group_id_'.$group_id.'" >'.$role. "\n";
					echo TAB_9.$open_a_tag. "\n";
						echo TAB_10.$name. "\n";
					echo TAB_9.$close_a_tag. "\n";
				echo TAB_8.'</h4>'. "\n";
				
				if ( $info_in_new_win != "on" )
				{			
					echo TAB_8.'<table class="ContactListTable" >'. "\n";
					
					//	Image
					if ( $mod_contact_items['image'] != "" AND $mod_contact_items['image'] != NULL 
							AND file_exists('_images_user/'.$mod_contact_items['image'] )
						)
					{
						$show_image = TRUE;
						echo TAB_9.'<tr class="ContactListRowImage" id="ContactListRowImage_'.$mod_contact_items['contact_id'].'" >'. "\n";			
							echo TAB_10.'<td class="ContactListTableCellLeft" colspan="2"></td>'. "\n";
								
							echo TAB_10.'<td class="ContactListTableCellRight" rowspan="5" >' ."\n";
							//	  Calculate W x H of window from size of image			
							list($img_width, $img_height) = getimagesize('_images_user/'.$mod_contact_items['image']);
								
							$new_win_width = $img_width * 1.1;
							$new_win_height = $img_height + 70;
								
								echo TAB_11.'<a class="ContactListThumb" href="javascript:openWindow(\'includes/image_show.php?img='
									.$mod_contact_items['image'].'\','.$new_win_width.','.$new_win_height.');" >'."\n";							
									echo TAB_12.'<img class="Thumbnail96" src="/_images_user/'.$mod_contact_items['image'].'"'. "\n";
									echo TAB_12.' title="Click to enlarge" alt="image of: '.$mod_contact_items['name'].'" />'. "\n";
								echo TAB_11.'</a>'. "\n";
								
							echo TAB_10.'</td>'. "\n";
						echo TAB_9.'</tr>'. "\n";
					}
					else { $show_image = FALSE; }
						
					//	Phone N#  1 - 3
					for ($i=1; $i<4; $i++ )
					{
						if ( $mod_contact_items['phone_'.$i] != "" AND  $mod_contact_items['phone_'.$i] != NULL )
						{
							$label_str = 'Phone :';
							$content_str = $mod_contact_items['phone_'.$i];
						}
						else
						{
							$label_str = NULL;
							$content_str = NULL;							
						}
						
						echo TAB_9.'<tr class="ContactListRowPhone_'.$i.'"'
									.' id="ContactListRowphone_'.$i.'_'.$mod_contact_items['contact_id'].'" >'. "\n";
							echo TAB_10.'<td class="ContactListTableCellLeft" >'.$label_str.'</td>'. "\n";							
							echo TAB_10.'<td class="ContactListTableCellRight" >'.$content_str.'</td>'. "\n";
						echo TAB_9.'</tr>'. "\n";											
					}
					
					//	Fax					
					if ( $mod_contact_items['fax'] != "" AND  $mod_contact_items['fax'] != NULL )
					{
						$label_str = 'Fax:';
						$content_str = $mod_contact_items['fax'];
					}
					else
					{
						$label_str = NULL;
						$content_str = NULL;							
					}
					
					echo TAB_9.'<tr class="ContactListRowFax" id="ContactListRowFax_'.$mod_contact_items['contact_id'].'" >'. "\n";	
						echo TAB_10.'<td class="ContactListTableCellLeft" >'.$label_str.'</td>'. "\n";								
						echo TAB_10.'<td class="ContactListTableCellRight" >'.$content_str.'</td>'. "\n";
					echo TAB_9.'</tr>'. "\n";

					// 	if there is an image to display, colspan the remaining items to "wrap under the image
					if ( $show_image == TRUE ) { $colspan = 'colspan="2" ';}
					else 	{ $colspan = '';}
					
					
					//	email
					if ( $mod_contact_items['email'] != "" AND  $mod_contact_items['email'] != NULL)
					{					
						if ($mod_contact_items['display_email'] == "" AND $mod_contact_items['link_email'] == ""){}
						else
						{
							echo TAB_9.'<tr class="ContactListRowEmail" id="ContactListRowEmail_'.$mod_contact_items['contact_id'].'" >'. "\n";	
								echo TAB_10.'<td class="ContactListTableCellLeft" >Email:</td>'. "\n";								
								echo TAB_10.'<td class="ContactListTableCellRight" '.$colspan.'>'. "\n";

							
							switch ($mod_contact_items['link_email'])
							{
								case "":
									$open_a_tag = '';
									$close_a_tag = '';
								break;
								
								case "mailto":
									$open_a_tag = '<a class="ContactListEmailLink" href="mailto:'.$mod_contact_items['email'].'"'
													.' title="Click here to send '.$mod_contact_items['name'].' an email" >';
									$close_a_tag = '</a>';
								break;	
								
								case "form":
									$open_a_tag = '<a class="ContactListEmailLink" href="index.php?p='.$page_id.'&amp;'
													.'emailto='.$mod_contact_items['contact_id'].'"'
									//$open_a_tag = '<a class="ContactNameLink" href="'.$_SERVER['PHP_SELF']
														//.'?'.$_SERVER['QUERY_STRING'].'&amp;emailto='.$mod_contact_items['contact_id'].'"'
														.' title="Click here to send '.$mod_contact_items['name'].' an email" >';
									$close_a_tag = '</a>';
								break;	
								
							}	
							
							switch ($mod_contact_items['display_email'])
							{
								case "":
									$email_display = 'click here to send an email';
								break;
								
								case "text":
									$email_display = $mod_contact_items['email'];
								break;	
								
								case "img":

									$style = 'ContactListEmailLink';
									
									$email_display = '<img class="Text2Image"'
												.' src="/text_image_email.php?con_id='.$mod_contact_items['contact_id'].'&style='.$style.'" '
												.'alt="Email address of: '.$mod_contact_items['name'].'" />';
				
								break;	
								
							}										

									echo TAB_11.$open_a_tag. "\n";
										echo TAB_12.$email_display. "\n";									
									echo TAB_11.$close_a_tag."\n";
			
								echo TAB_10.'</td>'. "\n";
								
							echo TAB_9.'</tr>'. "\n";
						}
					}


					//	Misc Lines  1 - 3
					for ($i=1; $i<4; $i++ )
					{
						if ( $mod_contact_items['misc_'.$i] != "" AND  $mod_contact_items['misc_'.$i] != NULL )
						{
							echo TAB_9.'<tr class="ContactListRowMisc_'.$i.'"'
										.' id="ContactListRowMisc_'.$i.'_'.$mod_contact_items['contact_id'].'" >'. "\n";	
								echo TAB_10.'<td class="ContactListTableCellLeft" >'.$mod_contact_items['misc_label_'.$i].'</td>'. "\n";
								echo TAB_10.'<td class="ContactListTableCellRight" '.$colspan.'>'.$mod_contact_items['misc_'.$i].'</td>'. "\n";
							echo TAB_9.'</tr>'. "\n";						
						}
						
					}


		//	Associated groups
		
					$mysql_err_msg = 'Contact&#39;s Group association Infomation unavailable';	
					$sql_statement = 'SELECT name, mod_contact_groups.group_id FROM mod_contact_groups, mod_contact_group_asign'
						
															.' WHERE contact_id = "'.$contact_id.'"'
															.' AND mod_contact_groups.group_id = mod_contact_group_asign.group_id'
															.' ORDER BY seq';
															
															
					$goAheadWithThis = mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg));									
		
					if ( $goAheadWithThis > 0 )
					{
						$count = 1;
						$groups4contact = ReadDB ($sql_statement, $mysql_err_msg);
						while ($groups4contact_info = mysql_fetch_array ($groups4contact))
						{
							if ($count == 1) { $associated_with = 'Associations with:';}
							else { $associated_with = '';}
							

							$mysql_err_msg = 'Contact&#39;s Group association Link Infomation unavailable';	
							$sql_statement = 'SELECT page_id FROM modules, mod_contact_settings'
								
																	.' WHERE group_id = "'.$groups4contact_info['group_id'].'"'
																	.' AND mod_contact_settings.group_id = "'.$groups4contact_info['group_id'].'"'
																	.' AND modules.mod_id = mod_contact_settings.mod_id';	
															
							$getPageId = ReadDB ($sql_statement, $mysql_err_msg);
							$getPageId_info = mysql_fetch_array ($getPageId);
				
							$href_str = '<a href="index.php?p='.$getPageId_info['page_id'].'" >'.$groups4contact_info['name'].'</a>';
	
							echo TAB_9.'<tr class="ContactListRowGroups"'
											.' id="ContactListRowGroups_'.$i.'_'.$mod_contact_items['contact_id'].'" >'. "\n";	
								echo TAB_10.'<td class="ContactListTableCellLeft" >'.$associated_with.'</td>'. "\n";
								echo TAB_10.'<td class="ContactListTableCellRight" '.$colspan.'>'.$href_str.'</td>'. "\n";
							echo TAB_9.'</tr>'. "\n";
							
							$count++;
							
						}
					}

					echo TAB_8.'</table>'. "\n";
					
					//	Comments
					if ( $mod_contact_items['comment'] != "" AND  $mod_contact_items['comment'] != NULL )
					{						
						echo TAB_8.'<div class="ContactListComments" >'. "\n";	
							echo TAB_9.'<p class="ContactListComments" >'.$mod_contact_items['comment'].'</p>'. "\n";
						echo TAB_8.'</div>'. "\n";
					}					
					
				}	


			}

			echo TAB_7.'</div>'."\n";
			
			if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
			{
				$div_name = 'ContactList_'.$mod_id;
				$can_not_clone = 0;

				//	Do mod editing Toolbar
				include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
				
				//	Do Mod Config Panel
				include ('CMS/cms_panels/cms_panel_mod_config.php');					
			}
			
			echo TAB_7."\n";
			echo TAB_7.'<!--  End Contact Info Listing  --> '. "\n";
			echo TAB_7."\n";
		
		}
		
	}	

?>