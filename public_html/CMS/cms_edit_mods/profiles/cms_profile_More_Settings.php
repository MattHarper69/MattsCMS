<?php// no direct access	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');		$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'] . '&amp;tab='.$_GET['tab'];		$update_url = '/CMS/cms_update/cms_update_profile_All.php';			//$return_url ='../cms_edit_mod_data.php?e='.$_GET['e'] . '&tab='.$_GET['tab'];		//$edit_profile_href = 'cms_edit_mods/profiles/cms_profile_edit_profile_index.php';	//---------Update error msg:	include_once ('cms_includes/cms_msg_update.php');		echo TAB_2.'<form action="'.$update_url.'" name="update" method="post" enctype="multipart/form-data" >'."\n";		echo TAB_3.'<fieldset id="UpdateAll" class="AdminForm">'."\n";				echo TAB_4.'<legend class="Centered" >'."\n";									//-------------UPDATE BUTTON------------------------------------				echo TAB_5.'<input type="submit" name="update_more_settings" value="Update ALL displayed Information" />'."\n";						echo TAB_4.'</legend>'."\n";				//	RESET button ======================================================							echo TAB_4.'<a href="'.$this_page.'"'."\n";				echo TAB_5.' title="Reload this page to Reset all Settings" >' ."\n";				echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";			echo TAB_4.'</a>'. "\n";					//=====================================================================================================================			//	Profile Listing name				echo TAB_4.'<fieldset class="AdminForm3">'."\n";				echo TAB_5.'<strong>Name</strong> for this Profile Listing (optional): '. "\n";				echo TAB_5.'<input type="text" name="listing_name" value="'.$profile_settings['listing_name'].'" size="30"'. "\n";				echo TAB_5.' maxlength="50" title="Add or Edit the '.$profile_settings['profile_alias'].' Listing Name here (optional) " /> '."\n";			echo TAB_4.'</fieldset>'."\n";						//	Profile Alias				echo TAB_4.'<fieldset class="AdminForm3">'."\n";				echo TAB_5.'<strong>Profile Alias</strong>: '. "\n";				echo TAB_5.'<input type="text" name="profile_alias" value="'.$profile_settings['profile_alias'].'" size="30"'. "\n";				echo TAB_5.' maxlength="50" title="Add or Edit the Profile Alias (used for reference) " /> '."\n";			echo TAB_4.'</fieldset>'."\n";							//	edit Heading				echo TAB_4.'<fieldset class="AdminForm3">'."\n";				echo TAB_5.'Listing <strong>Heading</strong> (optional): <input type="text" name="heading" value="'					.$profile_settings['heading'].'"' . "\n";				echo TAB_5.' size="50" title="Add or Edit the '.$profile_settings['profile_alias'].' Listing Heading here (optional) " /> '."\n";			echo TAB_4.'</fieldset>'."\n";						//	edit Text 1 and 2			echo TAB_4.'<fieldset class="AdminForm3" style="clear: left;">'."\n";								echo TAB_5.'<p><strong>Text 1</strong> (displayed before listing - optional): </p>'. "\n";							echo TAB_5.'<textarea name="text_1" cols="120" rows="1" onkeyup="AutoResize(this);" onkeydown="AutoResize(this);"'. "\n";					echo TAB_6.'title="Add or Edit the the text that is displayed before the '.$profile_settings['profile_alias'].' listing" >';					echo $profile_settings['text_1'];				echo '</textarea>' . "\n";				echo TAB_5.'<p><strong>Text 2</strong> (displayed after listing - optional): </p>'. "\n";							echo TAB_5.'<textarea name="text_2" cols="120" rows="1" onkeyup="AutoResize(this);" onkeydown="AutoResize(this);"'. "\n";					echo TAB_6.'title="Add or Edit the the text that is displayed before the '.$profile_settings['profile_alias'].' listing" >';					echo $profile_settings['text_2'];				echo '</textarea>' . "\n";							echo TAB_4.'</fieldset>'."\n";				echo TAB_4.'<fieldset class="AdminForm3" >'. "\n";					echo TAB_5.'<p>Display the <strong><< Next / Prev >></strong> '.$profile_settings['profile_alias'].' Navigation Bar:</p>'."\n";								// make array and loop thru options: ============================================================				$navbar_location_modes = array(											 0 => 'Do NOT Display'											,1 => 'At the TOP of the Profile'											,2 => 'At the BOTTOM of the Profile'											,3 => 'BOTH Top and Bottom of the Profile'				);								foreach ( $navbar_location_modes as $mode => $desc)				{					if ($profile_settings['navbar_location'] == $mode) { $checked = ' checked="checked"'; }					else { $checked = '';}											echo TAB_5.'<input type="radio" name="navbar_location" value="'.$mode.'" '.$checked.' /> '.$desc.'<br/>'."\n";									}			echo TAB_4.'</fieldset>'. "\n";				echo TAB_4.'<fieldset class="AdminForm3">'."\n";											if ($profile_settings['use_url_alias'] == 'on')				{					$checked = ' checked="checked"';				}				else 				{ 					$checked = '';				}								echo TAB_5.'<input type="checkbox" name="use_url_alias" '.$checked.'/>'. "\n";						echo TAB_6.' : Use <strong>Friendly URLs</strong> for '.$profile_settings['profile_alias'].' pages?'					.'<br/>(Example: &quot;http://'.SITE_URL.'<strong>/john_citizen</strong>&quot;)'. "\n";									echo TAB_4.'</fieldset>'."\n";								echo TAB_3.'</fieldset>'."\n";			$return_url = $this_page;		echo TAB_3.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";		echo TAB_3.'<input type="hidden" name="mod_id" value="'.$_GET['e'].'" />'."\n";			echo TAB_2.'</form>'."\n";	?>