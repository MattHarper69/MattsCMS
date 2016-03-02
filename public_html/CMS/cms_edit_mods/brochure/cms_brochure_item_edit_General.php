<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		
		//	RESET button ======================================================				
		echo TAB_7.'<a  href="'.$this_page.'?item_id='.$_REQUEST['item_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;tab='.$tab.'"'."\n";
			echo TAB_8.' title="Reload this page to Reset all '.$item_alias.' data" >' ."\n";
			echo TAB_8.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
		echo TAB_7.'</a>'. "\n";	

	
		//	edit Name 		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";
			echo TAB_8.$item_alias.' Name: <input type="text" name="item_name" value="'.$item_info['item_name'].'"' . "\n";
			echo TAB_8.' size="30" title="Add or Edit the '.$item_alias.'&#39;s display Name here" /> '."\n";
		echo TAB_7.'</fieldset>'."\n";
	

				
		//	Active
		if ($item_info['active'] == 'on' OR $_REQUEST['item_id'] == 'new') { $checked = ' checked="checked"'; }
		else { $checked = '';}
		
		echo TAB_7.'<fieldset class="AdminForm3" title="Uncheck this box to hide this '.$item_alias.'" >'."\n";
			echo TAB_8.'<input type="checkbox" name="active" '.$checked.' /> : Set as ACTIVE'."\n";
		echo TAB_7.'</fieldset>'."\n";	

		//	Category
		echo TAB_7.'<fieldset class="AdminForm3" title="Select the Category for this '.$item_alias.'" >'."\n";
								
			echo TAB_9.'Category: <select name="cat_id">' ."\n";
								
				//	get the categories
				$mysql_err_msg = 'Cannot Access Brochure Information(Categories)';
				$sql_statement = 'SELECT cat_id, cat_name FROM mod_brochure_cats WHERE mod_id="'.$_GET['mod_id'].'" ORDER BY seq';
																	
				$cat_result = ReadDB ($sql_statement, $mysql_err_msg);	
				
				if ( $item_info['cat_id'] == '0' OR $item_info['cat_id'] == '') 
				{ $selected = ' selected="selected"';}
				else { $selected = '';}
				echo TAB_5.'<option value="0"'.$selected.' >(all categories)</option>'."\n";

				while ($cat_info = mysql_fetch_array($cat_result))
				{
					if ($cat_info['cat_id'] == $item_info['cat_id']) 
					{$selected = ' selected="selected"';}						
					else {$selected = '';}
				
					echo TAB_7.'<option'.$selected.' value="'.$cat_info['cat_id'].'" >'.$cat_info['cat_name'].'</option>'."\n";
							
				}
											
								
			echo TAB_9.'</select>' ."\n";	
			
		echo TAB_7.'</fieldset>'."\n";			
		
		//	edit Heading 		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";
			echo TAB_8.''.$item_alias.' Heading (optional): <input type="text" name="heading" value="'.$item_info['heading'].'"' . "\n";
			echo TAB_8.' size="80" title="Add or Edit the '.$item_alias.'&#39;s Heading here" /> '."\n";
		echo TAB_7.'</fieldset>'."\n";		
			

		//	edit Text 		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";
			echo TAB_8.' <textarea name="text" cols="120" rows="5" onkeyup="AutoResize(this);" onkeydown="AutoResize(this);">'."\n";
			echo TAB_8.$item_info['text'].'</textarea>'."\n";
		echo TAB_7.'</fieldset>'."\n";					

												

?>