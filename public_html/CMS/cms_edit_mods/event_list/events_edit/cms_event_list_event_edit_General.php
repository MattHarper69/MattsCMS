<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		
		//	RESET button ======================================================				
		echo TAB_7.'<a  href="'.$this_page.'?event_id='.$_REQUEST['event_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;tab='.$tab.'"'."\n";
			echo TAB_8.' title="Reload this page to Reset all '.$alias.' data" >' ."\n";
			echo TAB_8.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
		echo TAB_7.'</a>'. "\n";	

	
		//	edit Name 		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";
			echo TAB_8.$alias.' Name: <input type="text" name="name" value="'.$event_info['name'].'"' . "\n";
			echo TAB_8.' size="50" title="Add or Edit the '.$alias.'&#39;s display Name here" /> '."\n";
		echo TAB_7.'</fieldset>'."\n";
	
		//	Active
		if ($event_info['active'] == 'on' OR $_REQUEST['event_id'] == 'new') { $checked = ' checked="checked"'; }
		else { $checked = '';}
		
		echo TAB_7.'<fieldset class="AdminForm3" title="Uncheck this box to hide this '.$alias.'" >'."\n";
			echo TAB_8.'<input type="checkbox" name="active" '.$checked.' /> : Set this '.$alias.' as ACTIVE'."\n";
		echo TAB_7.'</fieldset>'."\n";	

		//	Fields
		echo TAB_7.'<fieldset class="AdminForm3" title="Uncheck this box to hide this '.$alias.'"  style="clear: left;">'."\n";
			//echo TAB_8.'<p></p>'."\n";
			
			echo TAB_8.'<table class="CMS_EventListing">' . "\n";
				
				echo TAB_9.'<tr>' . "\n";
					echo TAB_10.'<th style="background-color:#eeeeee;"></th>' . "\n";
					echo TAB_10.'<th style="padding:5px 5px;">Customizable Fields:</th>' . "\n";	
				echo TAB_9.'<tr>' . "\n";
				
				//	Headers
				echo TAB_9.'<tr>' . "\n";
				
					echo TAB_10.'<th style="background-color:#efefef;">Display Name:</th>' . "\n";
										
				for ($i = 1; $i < 6; $i++)
				{ 
					if ($event_settings['field_active_'.$i] != 'on')
					{$greyed = 'class="Greyed"';}
					else {$greyed = '';}
					
					echo TAB_10.'<th style="padding:5px 5px; text-align:centre;" '.$greyed.'>'.$event_settings['field_head_'.$i].'</th>' . "\n";
				}
	
				echo TAB_9.'</tr>' . "\n";	

				echo TAB_9.'<tr>' . "\n";
				
					echo TAB_10.'<td style="padding:5px 5px; text-align:centre;">' . "\n";

						echo TAB_11.'<textarea name="display_name" cols="20" rows="3" onkeyup="AutoResize(this);" onkeydown="AutoResize(this);" >';
							echo $event_info['display_name'];
						echo '</textarea>' . "\n";	
						
						echo TAB_10.'</td>' . "\n"; 					
				
										
					for ($i = 1; $i < 6; $i++)
					{ 
						if ($event_settings['field_active_'.$i] != 'on')
						{$greyed = 'style="background-color:#cccccc;"';}
						else {$greyed = '';}
						
						echo TAB_10.'<td style="padding:5px 5px; text-align:centre;">' . "\n";

								echo TAB_11.'<textarea name="field_'.$i.'" class="Field_'.$i.'" cols="20" rows="3" onkeyup="AutoResize(this);" onkeydown="AutoResize(this);" '.$greyed.'>';
									echo $event_info['field_'.$i];
								echo '</textarea>' . "\n";	
						
						echo TAB_10.'</td>' . "\n"; 						
					}
	
				echo TAB_9.'</tr>' . "\n";

				echo TAB_9.'<tr>' . "\n";
					echo TAB_10.'<td style="background-color:#eeeeee;"></td>' . "\n";
					echo TAB_10.'<td colspan="5" style="padding:5px 5px;">' . "\n";
					
					//	display_fields_in_more_info
					if ($event_info['display_fields_in_more_info'] == 'on') { $checked = ' checked="checked"'; }
					else { $checked = '';}
					

					echo TAB_8.'<input type="checkbox" name="display_fields_in_more_info" '.$checked ."\n";
						echo TAB_9.' title="Check this box to Display these fields in the &quot;More Info&quot; Page"/>' ."\n";
						echo TAB_9.' - Display the Customizable Fields in the &quot;More Info&quot; Page' ."\n";

							
					echo TAB_10.'</td>' . "\n";
					
				echo TAB_9.'</tr>' . "\n";
				
			echo TAB_8.'</table>' . "\n";


		echo TAB_7.'</fieldset>'."\n";
		
?>