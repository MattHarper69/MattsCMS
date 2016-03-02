<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'] . '&amp;tab='.$_GET['tab'];
	
	$update_url = '/CMS/cms_update/cms_update_profile_All.php';
		
	//$return_url ='../cms_edit_mod_data.php?e='.$_GET['e'] . '&tab='.$_GET['tab'];
	
	$edit_profile_href = 'cms_edit_mods/profiles/cms_profile_edit_profile_index.php';

	//---------Update error msg:
	include_once ('cms_includes/cms_msg_update.php');

	
	echo TAB_2.'<form action="'.$this_page.'" method="post" enctype="multipart/form-data" >'."\n";
		
		echo TAB_3.'<fieldset class="AdminForm2" style="height:20px; margin-bottom:10px;">' ."\n";	
				

			//	Add new profile Link
			echo TAB_4.'<a class="ButtonLink" href="'.$edit_profile_href.'?profile_id=new&amp;mod_id='.$_GET['e'].'"'
						.' rel="CMS_ColorBox_EditProfile" title="Add a new '.$profile_settings['profile_alias'].'" > Add New '.$profile_settings['profile_alias'].'</a>' ."\n";
		
		echo TAB_3.'</fieldset>' ."\n";	
		
		
	echo TAB_2.'</form>' ."\n";
					


		$sql_statement = 'SELECT'
										.'  profile_id'
										.', profile_name'
										.', role'
										.', display_contact_info'
										.', display_images'
										.', primary_image_id'
										.', profile_as_primary'
										.', seq'
										.', active'
										
										.' FROM mod_profiles'

									.' WHERE mod_id = "'.$_GET['e'].'"'
									.' ORDER BY seq'
									;
	
	
	
	
	$mysql_err_msg = $profile_settings['profile_alias'].' Listing Information not found';			

	$profile_info_result = ReadDB ($sql_statement, $mysql_err_msg);		
		
	$num_profiles_found = mysql_num_rows($profile_info_result);
	
	if($num_profiles_found < 1)
	{
		echo TAB_3.'<fieldset class="AdminForm2">' ."\n";
			echo TAB_4.'<h2>No '.$profile_settings['profile_alias'].'s found</h2>' ."\n";
		echo TAB_3.'</fieldset>' ."\n";	
	}

	elseif ($num_profiles_found > 0)
	{
		
		echo TAB_3.'<form action = "'.$update_url.'" method="post" enctype="multipart/form-data" >'."\n";
			echo TAB_4.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
				echo TAB_5.'<legend class="Centered" >'."\n";
					
					//-------------UPDATE BUTTON------------------------------------
					echo TAB_6.'<input type="submit" name="update_all" value="Update ALL displayed '.$profile_settings['profile_alias'].'s" />'."\n";			
				echo TAB_5.'</legend>'."\n";

				//	RESET button ======================================================				
				echo TAB_5.'<a  href="'.$this_page.'"'."\n";
					echo TAB_6.' title="Reload this page to Reset all '.$profile_settings['profile_alias'].' data" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
				echo TAB_5.'</a>'. "\n";				


				echo TAB_5.'<script type="text/javascript">
					
					$(document).ready( function()
					{
						//	Data Table
						$("#ProfileListing").dataTable({
							"bJQueryUI": true,
							"sPaginationType": "full_numbers"
						});
						
						//	Warning All selected for Deletion
						$("#CheckAllDeleteMaster:checkbox").click(function() 
						{
							if($("#CheckAllDeleteMaster").is(":checked"))
							{
								alert("You have selected Delete ALL:"
									+ "\n - Clicking the Update Button will Delete ALL displayed '.$profile_settings['profile_alias'].'s");
							}
								
						});

					});
				</script>'."\n";
	
				echo TAB_5.'<table id="ProfileListing" class="display">'."\n";

					echo TAB_6.'<thead>'."\n";
						echo TAB_7.'<tr>'."\n";
							echo TAB_8.'<th></th>'."\n";
							echo TAB_8.'<th>Name</th>'."\n";
							echo TAB_8.'<th>Role</th>'."\n";
							echo TAB_8.'<th>Order</th>'."\n";
							echo TAB_8.'<th>Active</th>'."\n";
							echo TAB_8.'<th>Display<br/>Images?</th>'."\n";
							echo TAB_8.'<th>Main<br/>Image</th>'."\n";
							echo TAB_8.'<th>Edit</th>'."\n";
							echo TAB_8.'<th>Delete</th>'."\n";
						echo TAB_7.'</tr>'."\n";
					echo TAB_6.'</thead>'."\n";
					
					echo TAB_6.'<tbody>'."\n";

				$count = 1;
				$all_checked_active = 1;
				$all_checked_display_images = 1;
				
				while ($profile_info = mysql_fetch_array($profile_info_result))
				{				
					echo TAB_7.'<tr>'."\n";
											
						//	Order
						echo TAB_8.'<td align="left">'."\n";
							//	send profile ID
							echo TAB_9.'<input type="hidden" name="profile_id_'.$count.'" value="'.$profile_info['profile_id'].'" />'."\n";	
				
							//	pad $count with zeros so that the corect order is achieved in the dataTable
							echo TAB_9.str_pad($count, 4, '0', STR_PAD_LEFT) ."\n";

						echo TAB_8.'</td>'."\n";
						
						//	profile name
						echo TAB_8.'<td align="left">'."\n";
							echo TAB_9.'<a href="'.$edit_profile_href.'?mod_id='.$_GET['e']
								.'&amp;profile_id='.$profile_info['profile_id'].'&amp;tab=1"'."\n";
								echo TAB_10.' rel="CMS_ColorBox_EditProfile-Name"'."\n";
								echo TAB_10.' title="Edit '.$profile_settings['profile_alias'].': '
								.$profile_info['profile_name'].'">'.$profile_info['profile_name'].'</a>'."\n";	
						echo TAB_8.'</td>'."\n";	
						
						//	profile code
						echo TAB_8.'<td align="center">'."\n";
							//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
							echo TAB_9.'<span style="display: none;">'.$profile_info['role'].'</span>'. "\n";
							echo TAB_9.'<input type="text" name="role_'.$count.'"'
										.' value="'.$profile_info['role'].'" size="24" maxlength="255" />'. "\n";	
						echo TAB_8.'</td>'."\n";	

						
						//	ORDER
						echo TAB_8.'<td align="center">'."\n";
							//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
							echo TAB_9.'<span style="display: none;">'.$profile_info['seq'].'</span>'. "\n";

							echo TAB_9.'<input type="text" name="seq_'.$count.'"'
										.' value="'.$profile_info['seq'].'" size="6" maxlength="11" />'. "\n";	
						echo TAB_8.'</td align="center">'."\n";							
						
						
						//	Active
						echo TAB_8.'<td align="center">'."\n";	
							

							if ($profile_info['active'] == 'on')
							{
								$checked = ' checked="checked"';
							}
							else 
							{ 
								$checked = '';
								$all_checked_active = 0;	//	not all checked
							}
							
							//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort						
							echo TAB_9.'<span style="display: none;">'.$checked.'</span>'. "\n";
							echo TAB_9.'<input type="checkbox" name="active[]" class="CheckAllActive" '
							.$checked.' value="'.$profile_info['profile_id'].'"/>'. "\n";	
						echo TAB_8.'</td>'."\n";
												
						echo TAB_8.'</td>'."\n";								

						//	Display images ?
						echo TAB_8.'<td align="center">'."\n";	
							

							if ($profile_info['display_images'] == 'on')
							{
								$checked = ' checked="checked"';
							}
							else 
							{ 
								$checked = '';
								$all_checked_display_images = 0;	//	not all checked
							}
							
							//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort						
							echo TAB_9.'<span style="display: none;">'.$checked.'</span>'. "\n";
							echo TAB_9.'<input type="checkbox" name="display_images[]" class="CheckAllDisplayImages"'
							.$checked.' value="'.$profile_info['profile_id'].'"/>'. "\n";
						echo TAB_8.'</td>'."\n";

						//	Main Image
						echo TAB_8.'<td align="center">'."\n";
						
						//	Get the Primary image filename (from db)
						if($profile_info['primary_image_id'] AND $profile_info['profile_as_primary'] != 'on')
						{
							$mysql_err_msg = 'Profile primary image information unavailable';	
							$sql_statement = 'SELECT image_file_name'

											.' FROM mod_profile_images'
												
											.' WHERE image_id = "'.$profile_info['primary_image_id'].'"'
											;	
					
							$primary_img_result = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
							$primary_img_filename = $primary_img_result['image_file_name'];

						}
						
						//	OR use profile image
						elseif ($profile_info['profile_as_primary'] == 'on')
						{
							$primary_img_filename = 'profileID_'.$profile_info['profile_id'].'.jpg';
						}
						
						//	OR use Default image
						else
						{
							$primary_img_filename = 'profile_default_primary_img.jpg';
						}
						
						$image_path = '../_images_user/profile/'.$primary_img_filename;													
							

							//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
							echo TAB_9.'<span style="display: none;">'.$primary_img_filename.'</span>'. "\n";						
							echo TAB_9.'<a href="'.$edit_profile_href.'?mod_id='.$_GET['e']
								.'&amp;profile_id='.$profile_info['profile_id'].'&amp;tab=4"'."\n";	
							echo TAB_9.' rel="CMS_ColorBox_EditProfile-Image" title="Edit '
								.$profile_settings['profile_alias'].': '.$profile_info['profile_name'].'">'."\n";
								
							if (file_exists($image_path))
							{
								echo TAB_10.'<img class="Icon30x30" src="'.$image_path.'" />'."\n";
							}
							else 
							{
								echo TAB_10.'<img src="/images_misc/icon_No_image_24.jpg" />'."\n";
							}

							echo TAB_9.'</a>'."\n";	
						echo TAB_8.'</td>'."\n";
						
						//	Edit link
						echo TAB_8.'<td align="center">'."\n";
							echo TAB_9.'<a href="'.$edit_profile_href.'?mod_id='.$_GET['e']
								.'&amp;profile_id='.$profile_info['profile_id'].'&amp;tab=1"'."\n";
							echo TAB_9.'rel="CMS_ColorBox_EditProfile" title="Edit '.$profile_settings['profile_alias'].': '
								.$profile_info['profile_name'].'">'."\n";	
								echo TAB_10.'<img src="/images_misc/icon_edit_16x16.png" />'."\n";
							echo TAB_9.'</a>'."\n";	
						echo TAB_8.'</td>'."\n";
						
					
						//	Delete
						echo TAB_8.'<td align="center">'."\n";

							echo TAB_9.'<p>' ."\n";
							
								//	check-box
								echo TAB_10.'<input type="checkbox" name="delete[]" class="CheckAllDelete" value="'
									.$profile_info['profile_id'].'" /> ' ."\n";			
								echo TAB_10.'<a href="#" class="ConfirmDeleteButton" title="Delete '.$profile_settings['profile_alias']
									.': '.$profile_info['profile_name'].'">'."\n";	
									echo TAB_11.'<img src="/images_misc/icon_delete_16x16.png" />'."\n";
								echo TAB_10.'</a>'."\n";								
							echo TAB_9.'</p>'."\n";
							
							echo TAB_9.'<p class="WarningMSGSmall HideAtStart" style="border: solid 1px #cccccc; padding:5px;">' ."\n";
								
								//	Cancel link
								echo TAB_10.'<a href="#" class="CloseThisPanel" title="Do NOT Delete">' ."\n";
									echo TAB_11.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="float:right;"/>' ."\n";
								echo TAB_11.'</a>' ."\n";							
								
								//	OK DELETE Mod		
								echo TAB_11.'Confirm:<input type="submit" name="submit_delete_profile_'.$count.'" style="color:#cc0000;"'."\n";
									echo TAB_12.' value="DELETE" title="Delete this '.$profile_settings['profile_alias'].'" />'."\n";
								
							echo TAB_9.'</p>' ."\n";	

						echo TAB_8.'</td>'."\n";						
						
					echo TAB_7.'</tr>'."\n";
				
					$num_records = $count;
					$count++;
				
				}
					echo TAB_6.'<tfoot>'."\n";
						echo TAB_7.'<tr>'."\n";
							echo TAB_8.'<td colspan="4"></td>'."\n";
							
							echo TAB_8.'<td align="center">'."\n";
								if ( $all_checked_active == 1)
								{ $checked = 'checked="checked"'; }
								else { $checked = ''; }
							
								echo TAB_9.'<input type="checkbox" name="active[]" class="CheckAll CheckAllActive" value="all" '.$checked.'/>'."\n";
								echo TAB_9.'<br/>select all'."\n";
							echo TAB_8.'</td>'."\n";

							echo TAB_8.'<td align="center">'."\n";
								if ( $all_checked_display_images == 1)
								{ $checked = 'checked="checked"'; }
								else { $checked = ''; }
							
								echo TAB_9.'<input type="checkbox" name="display_images[]" class="CheckAll CheckAllDisplayImages"'."\n";
									echo TAB_10.' value="all" '.$checked.'/>'."\n";
								echo TAB_9.'<br/>select all'."\n";
							echo TAB_8.'</td>'."\n";
							
							echo TAB_8.'<td colspan="2"></td>'."\n";
							
							echo TAB_8.'<td align="center">'."\n";
							
								echo TAB_9.'<input type="checkbox" name="delete[]" class="CheckAll CheckAllDelete"'
									.' id="CheckAllDeleteMaster" value="all" />'."\n";
								echo TAB_9.'<br/>select all'."\n";
	
							echo TAB_8.'</td>'."\n";
							
						echo TAB_7.'</tr>'."\n";
					echo TAB_6.'</tfoot>'."\n";	
					
				echo TAB_5.'</table>'."\n";
	
			echo TAB_4.'</fieldset>' ."\n";	
			
			//	used to do db update and determin correct n# of total checkboxs
			echo TAB_4.'<input type="hidden" name="num_records" value="'.$num_records.'" />'."\n";	

			$return_url = $this_page;
			echo TAB_3.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";
			echo TAB_3.'<input type="hidden" name="mod_id" value="'.$_GET['e'].'" />'."\n";			

		echo TAB_3.'</form>' ."\n";	
	}
			
?>