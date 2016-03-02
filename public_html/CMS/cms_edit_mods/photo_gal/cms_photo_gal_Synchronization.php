<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
		
	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$mod_id . '&tab='.$_GET['tab'];
	$return_url = $this_page . '&mod_id='.$mod_id;

	
	$mysql_err_msg = 'Photo Gallery information unavailable';
	$sql_statement = 'SELECT'

								.'  modules.mod_id'
								//.', modules.mod_type_id'
								.', modules.active'
								.', modules.sync_id'
								.', gallery_name'
								.', gallery_type' 
								.', page_name' 
								.', themes.name'
								
								.' FROM mod_photo_gal_settings, modules, page_info, themes' 
								.' WHERE mod_type_id = '.$mod_type_info['mod_type_id']
								.' AND modules.mod_id = mod_photo_gal_settings.mod_id'
								.' AND modules.page_id = page_info.page_id'
								.' AND modules.theme_specific = themes.theme_id'
								.' ORDER BY'
								.'  FIELD(modules.mod_id, '.$mod_id.') DESC'
								.', FIELD(sync_id, "'.$mod_type_info['sync_id'].'") DESC'
								.', gallery_type, modules.mod_id'
								;	
	
			$gal_results = ReadDB ($sql_statement, $mysql_err_msg);
	
			$num_gal_mods = mysql_num_rows($gal_results);
	
	if ($num_gal_mods > 1)	
	{
			//---------Update error msg:
		include_once ('cms_includes/cms_msg_update.php');	
		
		$update_url = '/CMS/cms_update/cms_update_photo_gal_Synchronization.php';
		echo TAB_2.'<form action="'.$update_url.'" name="update" method="post" enctype="multipart/form-data" >'."\n";

			echo TAB_3.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
				echo TAB_4.'<legend class="Centered" >'."\n";
					
					//-------------UPDATE BUTTON------------------------------------
					echo TAB_5.'<input type="submit" name="synchronization_update" value="Update ALL displayed Information" />'."\n";			
				echo TAB_4.'</legend>'."\n";

				//	RESET button ======================================================				
				echo TAB_4.'<a href="'.$this_page.'" title="Reload this page to Reset all Category data" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";	
				//	===================================================================	

				echo TAB_4.'<h4>All Photo Galleries:</h4>' ."\n";
				echo TAB_4.'<p>checked galleries are currently synced to this Gallery</p>' ."\n";
				
				echo TAB_4.'<table class="CMS_PhotoGalSyncs AdminForm3">' ."\n";
				
					echo TAB_4.'<tr>' ."\n";
						echo TAB_5.'<th>Synced</th><th>Sync Code</th><th>Gallery ID</th>' ."\n";
						echo TAB_5.'<th>Name</th><th>Gallery Type</th><th>Page Name</th><th>Theme<br/>attached to</th><th>Active</th>' ."\n";
					echo TAB_4.'</tr>' ."\n";	
						
				$all_checked_sync = 1;
				while ($synced_gals = mysql_fetch_array($gal_results))
				{								
					//	is synced (checked)
					if($synced_gals['sync_id'] == $mod_type_info['sync_id'] AND $synced_gals['sync_id'] != 0) 
					{
						$checked = ' checked="checked"';
					}
					else 
					{
						$checked = '';
						$all_checked_sync = 0;	//	not all checked
					}					
					
					//	is this the current gallery selected ?
					if ($mod_id == $synced_gals['mod_id'])
					{
						$highlite = ' style="font-weight: bold; background: yellow;"';
						$a_link = '';
						$b_link = '';
						$input = '';
						//$input = '<input type="hidden" name="syncs[]" value="'.$synced_gals['mod_id'].'" />';
						//$disabled = ' disabled="disabled"';
					}
					else
					{
						$highlite = '';
						$a_link = '<a href="'.$_SERVER['PHP_SELF'] . '?e='.$synced_gals['mod_id'] . '&tab='.$_GET['tab'].'"'
									.' title="click name to change to this Gallery">';
						$b_link = '</a>';
						$input = '<input type="checkbox" class="CheckAllSync" name="syncs[]" value="'.$synced_gals['mod_id'].'"'.$checked.'/>';

						//$disabled = '';
					}

					
					$sync_id = $synced_gals['sync_id'];
						
					echo TAB_4.'<tr'.$highlite.'>' ."\n";	
					
						echo TAB_5.'<td>' ."\n";
							echo TAB_6.$input."\n";
							echo TAB_6.'<input type="hidden" name="all_mod_ids[]" value="'.$synced_gals['mod_id'].'" />'."\n";
							echo TAB_6.'<input type="hidden" name="old_sync_mod_'.$synced_gals['mod_id'].'" value="'.$sync_id.'" />'."\n";
						echo TAB_5.'</td>' ."\n";
						
						if ($sync_id == 0){$sync_id = ' - ';}	
						
						echo TAB_5.'<td>'.$sync_id.'</td>' ."\n";
						echo TAB_5.'<td>'.$synced_gals['mod_id'].'</td>'."\n";					
						echo TAB_5.'<td>'.$a_link.$synced_gals['gallery_name'].$b_link.'</td>' ."\n";
						
						switch($synced_gals['gallery_type'])
						{
							case('jquery_cycle'):
								$gallery_type = 'Auto Slide Show';
							break;
							
							case('jquery_galleria'):
								$gallery_type = 'Photo Album ';
							break;							
						}
						echo TAB_5.'<td>'.$gallery_type.'</td>' ."\n";
						
						echo TAB_5.'<td>'.$synced_gals['page_name'].'</td>'."\n";
						echo TAB_5.'<td>'.$synced_gals['name'].'</td>'."\n";
						
						if ($synced_gals['active'] == 'on') {$active = 'Active';}
						else {$active = '<span class="WarningMSG">In-Active</span>';}
						echo TAB_5.'<td>'.$active.'</td>' ."\n";
					

					echo TAB_4.'</tr>' ."\n";						
						
				}
					//	check to Sync All
					if ( $all_checked_sync == 1)
					{ $checked = ' checked="checked"'; }
					else { $checked = ''; }	
					
					
					echo TAB_4.'<tr>' ."\n";
						echo TAB_5.'<td colspan="2">' ."\n";
							echo TAB_6.'<input type="checkbox" class="CheckAll CheckAllSync" name="sync[]" value="all"'.$checked.' />'."\n";
							echo TAB_6.'<span>Sync ALL</span>' ."\n";
						echo TAB_5.'</td>'."\n";					
					echo TAB_4.'</tr>' ."\n";
				
				echo TAB_4.'</table>' ."\n";
				
			echo TAB_3.'</fieldset>'."\n";

			echo TAB_3.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";
			echo TAB_3.'<input type="hidden" name="mod_id" value="'.$mod_id.'" />'."\n";
			//echo TAB_3.'<input type="hidden" name="selected_sync_id" value="'.$mod_type_info['sync_id'].'" />'."\n";
			//echo TAB_3.'<input type="hidden" name="mod_type_id" value="'.$mod_type_info['mod_type_id'].'" />'."\n";
			
		echo TAB_2.'</form>' ."\n";	
					
		echo TAB_2.'<p class="Small" >Galleries that share the same &quot;Sync Codes&quot; are synced together</p>'."\n";

	}
	else
	{
		echo TAB_2.'<h4>( No other Photo Galleries Configured )</h4>'."\n";
	}
	
?>