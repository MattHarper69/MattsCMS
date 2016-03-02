<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		
		//	RESET button ======================================================				
		echo TAB_7.'<a  href="'.$this_page.'?profile_id='.$_REQUEST['profile_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;tab='.$tab.'"'."\n";
			echo TAB_8.' title="Reload this page to Reset all '.$profile_settings['profile_alias'].' data" >' ."\n";
			echo TAB_8.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
		echo TAB_7.'</a>'. "\n";	


		echo TAB_7.'<script type="text/javascript">
				
			$(document).ready( function()
			{								
				
				//	Hide / Show Link Thumbnail to radio boxes
				$("#CheckDisplayContact").click(function() {
					if($("#CheckDisplayContact").is(":checked"))
					{
						$("#ContactInfo").show();
					}
					else
					{
						$("#ContactInfo").hide();
					}									
				});				
				
			});	
		</script>'."\n";		
		
		
		//	Display Contact info ?
		echo TAB_7.'<fieldset class="AdminForm3" title="Un-check this box to hide this '.$profile_settings['profile_alias'].'&#39;s Contact Info" >'."\n";		
			if ($profile_info['display_contact_info'] == 'on' OR $_REQUEST['profile_id'] == 'new') 
			{ 
				$checked = ' checked="checked"'; 
				$hide = '';
			}
			else 
			{ 
				$checked = '';
				$hide = ' display: none;"';
			}
		
			echo TAB_8.'<input type="checkbox" name="display_contact_info" '.$checked.' id="CheckDisplayContact"/> : Display this '
			.$profile_settings['profile_alias'].'&#39;s Contact Info'."\n";
		echo TAB_7.'</fieldset>'."\n";

		
	if ($profile_info['display_contact_info'] == 'on' OR $_REQUEST['profile_id'] == 'new')
	{
		echo TAB_7.'<fieldset class="AdminForm3" id="ContactInfo" style="clear: both;'.$hide.'">'."\n";	
			
			//	edit Email 		
			echo TAB_8.'<fieldset class="AdminForm3">'."\n";
			
				echo TAB_9.'Email address: <input type="text" name="email" value="'.$profile_info['email'].'"'. "\n";
				echo TAB_9.' size="50" title="Add or Edit a '.$profile_settings['profile_alias'].'&#39;s Email address here" /><br/> '."\n";		
			
				//	Display Email as ?
				echo TAB_9.'<fieldset class="AdminForm3">'."\n";
				
					echo TAB_10.'<p>Choose how to Display this '.$profile_settings['profile_alias'].'&#39;s Email:</p>'."\n";
					
					if (!$profile_info['display_email_as']) { $checked = ' checked="checked"'; }
					else { $checked = '';}
			
					echo TAB_10.'<input type="radio" name="display_email_as" value="" '.$checked.' />  Do NOT Display Email<br/>'."\n";

					if ($profile_info['display_email_as'] == 'img' OR $_REQUEST['profile_id'] == 'new') { $checked = ' checked="checked"'; }
					else { $checked = '';}	
					
					echo TAB_10.'<input type="radio" name="display_email_as" value="img" '.$checked.' /> IMAGE: Display this Email as an Image<br/>'."\n";
					
					if ($profile_info['display_email_as'] == 'text') { $checked = ' checked="checked"'; }
					else { $checked = '';}	
					
					echo TAB_10.'<input type="radio" name="display_email_as" value="text" '.$checked.' />  TEXT: Display this Email as text<br/>'."\n";				
				echo TAB_9.'</fieldset>'."\n";

				//	Link Email to ?
				echo TAB_9.'<fieldset class="AdminForm3">'."\n";
				
					echo TAB_10.'<p>Choose what this Email links to when clicked:</p>'."\n";
					
					if (!$profile_info['link_email']) { $checked = ' checked="checked"'; }
					else { $checked = '';}
			
					echo TAB_10.'<input type="radio" name="link_email" value="" '.$checked.' />  Do NOT Link<br/>'."\n";

					if ($profile_info['link_email'] == 'mailto' OR $_REQUEST['profile_id'] == 'new') { $checked = ' checked="checked"'; }
					else { $checked = '';}	
					
					echo TAB_10.'<input type="radio" name="link_email" value="mailto" '.$checked.' />'
						.' MAILTO : opens an email to write in the user&#39;s email program<br/>'."\n";
					
					if ($profile_info['link_email'] == 'form') { $checked = ' checked="checked"'; }
					else { $checked = '';}	
					
					echo TAB_10.'<input type="radio" name="link_email" value="form" '.$checked.' disabled="disabled"/>'	//	<< Not Implimented yet
						.' CONTACT FORM: opens a &quot;contact form&quot;<br/>'."\n";				
				echo TAB_9.'</fieldset>'."\n";

				
			echo TAB_8.'</fieldset>'."\n";	
			
			//	edit Phones + Fax 		
			echo TAB_8.'<fieldset class="AdminForm3">'."\n";
			
				for ($i=1; $i<4; $i++)
				{
					echo TAB_9.'Phone '.$i.': <input type="text" name="phone_'.$i.'" value="'.$profile_info['phone_'.$i].'"'. "\n";
					echo TAB_9.' size="20" title="Add or Edit a '.$profile_settings['profile_alias'].'&#39;s Phone Number here" /><br/> '."\n";		
				}
					echo TAB_9.'Fax N#&nbsp;: <input type="text" name="fax" value="'.$profile_info['fax'].'"'. "\n";
					echo TAB_9.' size="20" title="Add or Edit the '.$profile_settings['profile_alias'].'&#39;s Fax Number here" /> '."\n";		

			echo TAB_8.'</fieldset>'."\n";



			//	edit Website 		
			echo TAB_8.'<fieldset class="AdminForm3">'."\n";
			

				echo TAB_9.'Website address: &nbsp;&nbsp;<strong>http://</strong>'."\n";	
				echo TAB_9.'<input type="text" name="website_url" value="'.$profile_info['website_url'].'"'. "\n";
				echo TAB_9.' size="30" title="Add or Edit a '.$profile_settings['profile_alias'].'&#39;s Website address here" /><br/> '."\n";		
			
				echo TAB_9.'Website display link text:&nbsp;'."\n";	
				echo TAB_9.'<input type="text" name="website_display" value="'.$profile_info['website_display'].'"'. "\n";
				echo TAB_9.' size="30" title="Add or Edit the Website Link&#39;s display text" /> '."\n";
				echo TAB_9.'<span class="FinePrint">(optional)</span> '."\n";

			echo TAB_8.'</fieldset>'."\n";
			
		echo TAB_7.'</fieldset>'."\n";
		
	}
			
			

?>