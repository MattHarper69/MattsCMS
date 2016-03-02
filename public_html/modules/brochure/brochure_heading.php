<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	
	$default_cat_is_active = 0;
	
	if ($settings_info['heading'])
	{
		echo TAB_8.'<h1 class="BrochureListHeading" id="BrochureListHeading_'.$mod_id.'" >'.$settings_info['heading'].'</h1>'."\n";
	}
	
	if ($settings_info['can_select_cat'])
	{
		
		//	Do Category Selection Box
		echo TAB_8.'<div class="BrochureSelectBox"  >'."\n";


			echo TAB_9.'<form action="" method="post" >'."\n";
				echo TAB_10.'<fieldset class="BrochureSelectBox">'."\n";			
					echo TAB_11.'<label for="BrochureSelectBox_'.$mod_id.'" >' . $settings_info['select_cat_text'] . '</label>'."\n";
					echo TAB_11.'<select class="BrochureSelectBox" name="cat_id" onchange="this.form.submit()" '
								.'id="BrochureSelectBox_'.$mod_id.'" >'."\n";
						
					if ($settings_info['select_all'])
					{	
						if ($cat_id == '0') {$selected = ' selected="selected"';}
						else {$selected = '';}
						
						echo TAB_12.'<option'.$selected.' value="0" >all categories</option>'."\n";
					}
					elseif	($cat_id == '0')
					{	
						echo TAB_12.'<option selected="selected" value="0" ></option>'."\n";
					}
					
					//	Get all Categories
					$mysql_err_msg = 'Cannot Access Brochure Information(Categories)';
					$sql_statement = 'SELECT * FROM mod_brochure_cats WHERE'
							
															.' mod_id="'.$mod_id.'"'
															.' AND active="on"'
															.' ORDER BY seq'
															;
															
			 		$cat_result = ReadDB ($sql_statement, $mysql_err_msg);

					$default_cat_is_active = 0;
					while ($cat_row = mysql_fetch_array($cat_result))
					{
						if ($cat_row['cat_id'] == $cat_id) 
						{
							$selected = ' selected="selected"';
							$default_cat_is_active = 1;
						}						
						else {$selected = '';}
					
						echo TAB_12.'<option'.$selected.' value="'.$cat_row['cat_id'].'" >'.$cat_row['cat_name'].'</option>'."\n";

					}
				
					echo TAB_11.'</select >'."\n";
					
					//	Do Button if Javascript turned Off ( note  <ins> tags are used to Validate only )
					echo TAB_11.'<noscript><ins><input type="submit" value="Change" /></ins></noscript> '."\n";
				echo TAB_10.'</fieldset >'."\n";				
			echo TAB_9.'</form >'."\n";	
															
		echo TAB_8.'</div >'."\n";		
	}
	
	//	check if default cat is active
	if($default_cat_is_active != 1)
	{
		$cat_id = 0; 	//	default to ALL
	}
	
	if ($cat_id != 0 AND $cat_id != "" AND $cat_id != null )
	{
		//	Get selected Cat info
		$mysql_err_msg = 'Cannot Access brochure Information';	
		$sql_statement = 'SELECT * FROM mod_brochure_cats'

									.' WHERE cat_id = '.$cat_id
									.' AND active = "on"'
									;
		//echo $sql_statement;		
		$cat_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
				
		if ($cat_info['display_name'])
		{
			echo TAB_8.'<h2 class="BrochureCatHeading" id="BrochureCatHeading_'.$cat_id.'" >'.$cat_info['cat_name'].'</h2>'."\n";
		}

		if ($cat_info['description'])
		{
			echo TAB_8.'<p class="BrochureCatDesc" id="BrochureCatDesc_'.$cat_id.'" >'.$cat_info['description'].'</p>'."\n";
		}		

		
	}	
	
?>