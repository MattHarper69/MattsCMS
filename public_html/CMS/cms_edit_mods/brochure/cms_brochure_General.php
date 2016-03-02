<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'] . '&tab='.$_GET['tab'];
	$update_url = '/CMS/cms_update/cms_update_brochure_general.php';
	
	//---------Update error msg:
	include_once ('cms_includes/cms_msg_update.php');

	
	echo TAB_2.'<form action="'.$update_url.'" name="update" method="post" enctype="multipart/form-data" >'."\n";

		echo TAB_3.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
			echo TAB_4.'<legend class="Centered" >'."\n";
					
				//-------------UPDATE BUTTON------------------------------------
				echo TAB_5.'<input type="submit" name="update_settings" value="Update ALL displayed Information" />'."\n";			
			echo TAB_4.'</legend>'."\n";
	
			//	RESET button ======================================================				
			echo TAB_4.'<a href="'.$this_page.'"'."\n";
				echo TAB_5.' title="Reload this page to Reset all Settings" >' ."\n";
				echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
			echo TAB_4.'</a>'. "\n";	
	
			//=====================================================================================================================

			//	edit Name	
			echo TAB_4.'<fieldset class="AdminForm3">'."\n";
				echo TAB_5.'Brochure Name: <input type="text" name="brochure_name" value="'.$settings_info['brochure_name'].'"' . "\n";
				echo TAB_5.' size="50" title="Edit the Brochures&#39;s Name" /> '."\n";
			echo TAB_4.'</fieldset>'."\n";
		
			//	edit Item Alias	
			echo TAB_4.'<fieldset class="AdminForm3">'."\n";
				echo TAB_5.'Item Alias	: <input type="text" name="item_alias" value="'.$settings_info['item_alias'].'"' . "\n";
				echo TAB_5.' size="30" title="Edit the Item Alias (what the '.$settings_info['item_alias'].'s are refered as) " /> '."\n";
			echo TAB_4.'</fieldset>'."\n";
			
			//	edit Heading	
			echo TAB_4.'<fieldset class="AdminForm3" style="clear: left;">'."\n";
				echo TAB_5.'Brochure Heading (optional): <br/><input type="text" name="heading" value="'.$settings_info['heading'].'"' . "\n";
				echo TAB_5.' size="60" title="Add or Edit the '.$settings_info['item_alias'].' Listing Heading here (optional)" /> '."\n";
			echo TAB_4.'</fieldset>'."\n";
			
			//	Max number of chrs shown
			echo TAB_4.'<fieldset class="AdminForm3">'."\n";
				echo TAB_6.'Abbreviate the Text for each entry in this listing to: ' . "\n";
					echo TAB_7.'<input type="text" name="max_chrs_display" value="'.$settings_info['max_chrs_display'].'"' . "\n";
					echo TAB_7.' size="4" title="Specify the number of Characters for the &quot;show more&quot; switch" /> 
					Characters (Default is 300)'."\n";	
					
			echo TAB_4.'</fieldset>'."\n";
			
			echo TAB_4.'<fieldset class="AdminForm3" style="clear: left;">'."\n";			
				
				echo TAB_5.'<h3>Category Options:</h3>'."\n";
					
					//	Default Category
					echo TAB_5.'<fieldset class="AdminForm3" style="clear: left;">'."\n";			


								
					echo TAB_6.'Default displayed Category: ' . "\n";
					echo TAB_6.'<select name="default_cat" title="Select the Category that will be displayed by default" > '."\n";

						if ($settings_info['default_cat'] == 0) {$selected = ' selected="selected"';}						
						else {$selected = '';}					
						
						echo TAB_7.'<option'.$selected.' value="0" >ALL Categories</option>'."\n";
						while ($cat_row = mysql_fetch_array($cat_result))
						{
							if ($cat_row['cat_id'] == $settings_info['default_cat']) 
							{
								$selected = ' selected="selected"';
								
								if (!$cat_row['active'])
								{
									$default_cat_not_active = TAB_6.'<br/><span class="WarningMSGSmall">(The Category set as default is not Active)</span>';
								}
								else
								{
									$default_cat_not_active = '';
								}
							}						
							else {$selected = '';}
						
							echo TAB_7.'<option'.$selected.' value="'.$cat_row['cat_id'].'" >'.$cat_row['cat_name'].'</option>'."\n";
						}
						
					echo TAB_6.'</select> '."\n";
					
					echo $default_cat_not_active;
				
				echo TAB_5.'</fieldset>'."\n";

				//	Select Category Options
				echo TAB_5.'<fieldset class="AdminForm3">'."\n";
					
					if ($settings_info['can_select_cat'] == 'on')
					{
						$checked = ' checked="checked"';
						$style = '';
					}
					else 
					{
						$checked = '';
						$style = ' style="display: none;"';
					}
					
					echo TAB_6.'<script type="text/javascript">
							
						$(document).ready( function()
						{								
								$("#CheckCanSelectCat").click(function() {
									if($("#CheckCanSelectCat").attr("checked"))
									{
										$(".SelectCatOptions").show();
									}
									else
									{
										$(".SelectCatOptions").hide();
									}									
								});				
						});
						
					</script>'."\n";
					
					echo TAB_6.'<p><input type="checkbox" name="can_select_cat" id="CheckCanSelectCat"'.$checked.' />' . "\n";
					echo TAB_6.' : The user can Select the Category to display from a Select Box</p>' . "\n";
					
					//	Choose cat text
					echo TAB_6.'<fieldset class="AdminForm3 SelectCatOptions" style="clear: both;">'."\n";
						echo TAB_7.'Select Box Text: <input type="text" name="select_cat_text" value="'.$settings_info['select_cat_text'].'"' . "\n";
						echo TAB_7.' size="50" title="Specify the text that is displayed next to the Category Select Box" /> '."\n";	
							
					echo TAB_6.'</fieldset>'."\n";	

					// Can Select ALL cats ?
					echo TAB_6.'<fieldset class="AdminForm3 SelectCatOptions" style="clear: left;">'."\n";
					
						if ($settings_info['select_all'] == 'on')
						{
							$checked = ' checked="checked"';
							$style = '';
						}
						else 
						{
							$checked = '';
							$style = ' style="display: none;"';
						}
					
						echo TAB_7.'<p><input type="checkbox" name="select_all" '.$checked.' />' . "\n";
						echo TAB_7.' : The user can select ALL categories to display from Category Select Box </p>' . "\n";					
						
					echo TAB_6.'</fieldset>'."\n";
					
				
				echo TAB_5.'</fieldset>'."\n";
			echo TAB_4.'</fieldset>'."\n";



		echo TAB_3.'</fieldset>'."\n";	

		$return_url = $this_page;
		echo TAB_3.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";
		echo TAB_3.'<input type="hidden" name="mod_id" value="'.$mod_id.'" />'."\n";
		
	echo TAB_2.'</form>'."\n";
	
?>