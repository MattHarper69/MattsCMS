<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		
		//	RESET button ======================================================				
		echo TAB_7.'<a  href="'.$this_page.'?profile_id='.$_REQUEST['profile_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;tab='.$tab.'"'."\n";
			echo TAB_8.' title="Reload this page to Reset all '.$profile_settings['profile_alias'].' data" >' ."\n";
			echo TAB_8.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
		echo TAB_7.'</a>'. "\n";	




				
					
			echo TAB_8.'<script type="text/javascript">
					
				$(document).ready( function()
				{'."\n";								
					
		if ($_REQUEST['profile_id'] == 'new') 
		{					
			echo TAB_8.'
					$("#ProfileName").keyup(function()
					{
						var text = this.value;
						text = text.replace(" ","_")
						
						$("#ProfileURL").val(text);
					});
						
						
						'."\n";
		}	
			
			echo TAB_8.'		
					//	Hide / Show Link Profile Image options
					$("#CheckShowProfileImage").click(function() {
						if($("#CheckShowProfileImage").is(":checked"))
						{
							$("#CurrentProfileImage").show();
							$("#UploadProfileImage").show();
							$("#DisplayProfileAsMain").show();
						}
						else
						{
							$("#CurrentProfileImage").hide();
							$("#UploadProfileImage").hide();
							$("#DisplayProfileAsMain").hide();
						}									
					});	

					
					
				});	
				
			</script>'."\n";				
		



		
		//	edit Name 		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";
			echo TAB_8.$profile_settings['profile_alias'].' Name: '
				.'<input type="text" name="profile_name" id="ProfileName" value="'.$profile_info['profile_name'].'"'. "\n";
			echo TAB_8.' size="30" title="Add or Edit the '.$profile_settings['profile_alias'].'&#39;s display Name here" /> '."\n";
		echo TAB_7.'</fieldset>'."\n";

		//	edit Role		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";
			echo TAB_8.$profile_settings['profile_alias'].' Role: '
				.'<input type="text" name="role" value="'.$profile_info['role'].'"'. "\n";
			echo TAB_8.' size="30" title="Add or Edit the '.$profile_settings['profile_alias'].'&#39;s Role here" /> (optional)'."\n";
		echo TAB_7.'</fieldset>'."\n";
		
		//	Active
		if ($profile_info['active'] == 'on' OR $_REQUEST['profile_id'] == 'new') 
		{ $checked = ' checked="checked"'; }
		else { $checked = '';}
		
		echo TAB_7.'<fieldset class="AdminForm3" title="Uncheck this box to hide this '.$profile_settings['profile_alias'].'" >'."\n";
			echo TAB_8.'<input type="checkbox" name="active" '.$checked.' /> : Set this '.$profile_settings['profile_alias'].' as ACTIVE'."\n";
		echo TAB_7.'</fieldset>'."\n";	

		//	URL Alias
		// if in Clone mode,  URL alias  should be unique
		if (!isset($_REQUEST['clone']))
		{ 
			if ($profile_info['url_alias'])
			{
				$url_alias = $profile_info['url_alias']; 
			}
			else
			{
				$url_alias = $profile_info['profile_name'];
			}
			
		}
		else 
		{ $url_alias = $profile_info['url_alias'] . '2'; }
		
		$url_alias = str_replace(' ', '_', trim($url_alias));
		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";
		
			//	is URL alias set ?
			if (!$profile_settings['use_url_alias'])
			{
				$greyed = ' class="Greyed"';
				$msg = ' - (Not Configured to use)';
			}
			else
			{
				$greyed = '';
				$msg = '';
			}
			
			echo TAB_8.$profile_settings['profile_alias'].'&#39;s display Page (Friendly URL): &quot;http://'.SITE_URL.' / '
				.'<input type="text" name="url_alias" id="ProfileURL" '.$greyed . "\n";
			echo TAB_9.' value="'.$url_alias.'" size="20" title="Add or Edit the '.$profile_settings['profile_alias'].'&#39;s Page URL here" />'
				.'&quot;'. $msg ."\n";
		echo TAB_7.'</fieldset>'."\n";			
				
		
		// Profile Image
		echo TAB_7.'<fieldset class="AdminForm3" >'."\n";						

			echo TAB_8.'<script type="text/javascript">
					
				$(document).ready( function()
				{								
					
					//	Hide / Show Link Profile Image options
					$("#CheckShowProfileImage").click(function() {
						if($("#CheckShowProfileImage").is(":checked"))
						{
							$("#CurrentProfileImage").show();
							$("#UploadProfileImage").show();
							$("#DisplayProfileAsMain").show();
						}
						else
						{
							$("#CurrentProfileImage").hide();
							$("#UploadProfileImage").hide();
							$("#DisplayProfileAsMain").hide();
						}									
					});				
					
				});	
			</script>'."\n";	
			
			
			//--------------------------------------------------------

			if ($profile_info['display_profile_img'] == 'on') 
			{ 
				$ShowImg_checked = ' checked="checked"'; 
				$hide = '';
			}
			
			else 
			{ 
				$ShowImg_checked = '';
				$hide = ' display: none;"';
			}	
			
			$image_path = '../../../_images_user/profile/';
			
			// Current Image - dont show if NEW Profile OR not set
			if($_REQUEST['profile_id'] == 'new' OR $_REQUEST['profile_id'] == 'clone')
			{ $profile_image = 'profile_default_profile_img.jpg';}
			elseif ($profile_info['profile_img_file'])
			{ $profile_image = $profile_info['profile_img_file'];}
			else {$profile_image = '<NoFile>';}
			
			echo TAB_8.'<div class="ImageThumbHolder" id="CurrentProfileImage" style="float: right;'.$hide.'">'."\n";	
			
			$image_url = $image_path.$profile_image;
			if (file_exists($image_url))
			{

				
				echo TAB_9.'<p>Current Image:</p>' ."\n";
														
				//	Delete
				echo TAB_9.'<p style="float:right;">' ."\n";
				
					echo TAB_10.'<a href="#" class="ConfirmDeleteButton" title="Delete this '.$profile_settings['profile_alias'].'&#39;s Image file" >' ."\n";
						echo TAB_11.'<img src="/images_misc/icon_delete_24x24.png" alt="Delete"/>' ."\n";
					echo TAB_10.'</a>'. "\n";
				echo TAB_9.'</p>' ."\n";
						
				echo TAB_9.'<p class="WarningMSG HideAtStart" style="border: solid 1px #cccccc; padding:5px;">' ."\n";
					
					//	Cancel link
					echo TAB_10.'<a href="#" class="CloseThisPanel" title="Do NOT Delete">' ."\n";
						echo TAB_11.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="float:right;"/>' ."\n";
					echo TAB_10.'</a>' ."\n";							
					
					//	OK DELETE Mod
					echo TAB_10.'Confirm:<input type="submit" name="delete_profile_image_profile" style="color:#cc0000;"'."\n";
							echo TAB_11.' value="DELETE" title="Delete this '.$profile_settings['profile_alias'].'&#39;s Image" />'."\n";		
				
				echo TAB_9.'</p>' ."\n";	

				//	Image Display

										
				// get width to height ratio and restrict width if image to 'short and wide'	
				$image_details = getimagesize($image_url);
				
				if ($image_details[0] / $image_details[1] > 1.5) 
				{$dims = 'width="120px"';}
				
				else {$dims = 'height="80px"';}		// otherwize restrict by height by default

				echo TAB_9.'<a href="/_images_user/profile/'.$profile_image .'" rel="ColorBoxImage" >' ."\n";
					echo TAB_8.'<img src="/_images_user/profile/'.$profile_image .'"'.$dims.' alt="Profile Image of '
						.$profile_settings['profile_alias'].': '.$profile_info['profile_name'].'" />'."\n";
				echo TAB_9.'</a>' ."\n";
				
				$no_image = 0;

			}

			else
			{
				echo TAB_9.'( No Image File Found )' ."\n";
				
				$no_image = 1;
			}
			
			echo TAB_8.'</div>'."\n";
			
			//--------------------------------------------------------			

			echo TAB_8.'<h3 style="float: left;">'.$profile_settings['profile_alias'].'&#39;s &quot;Profile Image&quot;:</h3>'."\n";				
			
			//	Display Profile Image ?
			echo TAB_8.'<fieldset class="AdminForm3" style="clear: left;">'."\n";
		
				echo TAB_9.'<input type="checkbox" name="display_profile_img" id="CheckShowProfileImage"'.$ShowImg_checked.' />'."\n";
				echo TAB_9.' : Display the '.$profile_settings['profile_alias'].'&#39;s Profile Image? '."\n";
			echo TAB_8.'</fieldset>'."\n";

			//	Upload image file 
			echo TAB_8.'<fieldset class="AdminForm3" id="UploadProfileImage" style="clear: left;'.$hide.'">'."\n";
				echo TAB_9.'<input type="hidden" name="MAX_FILE_SIZE" value="'.MAX_FILE_SIZE_CMS.'" />' . "\n";
				echo TAB_9.'Select a new Profile Image file for this '.$profile_settings['profile_alias'].':<br/>' . "\n"; 
				
				echo TAB_9.'<input type="file" id="upload_profile_image" name="upload_profile_image"' . "\n"; 
					echo TAB_10.' style="font-size: 12px;" size="60"' . "\n"; 
					echo TAB_10.' title="Use this to locate and upload a New Profile Image file for this '
					.$profile_settings['profile_alias'].'" /> '."\n";

			echo TAB_8.'</fieldset>'."\n";			
			

			if ($no_image != 1)
			{
				//	Display Profile Image as Primary Img
				echo TAB_8.'<fieldset class="AdminForm3" id="DisplayProfileAsMain" style="clear: both;'.$hide.'" >'."\n";
					if ($profile_info['profile_as_primary'] == 'on') { $checked = ' checked="checked"'; }
					else { $checked = '';}			

					echo TAB_9.'<input type="checkbox" name="profile_as_primary" '.$checked.' />'."\n";
					echo TAB_9.' : Display this Profile Image as the '.$profile_settings['profile_alias'].'&#39;s main thumbnail image?'."\n";
				echo TAB_8.'</fieldset>'."\n";			
			}
			
		
		echo TAB_7.'</fieldset>'."\n";	
			

		
?>