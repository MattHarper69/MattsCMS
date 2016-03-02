<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$can_not_clone = 0;
	$edit_enabled = 2;
	$mod_locked = 2;	
	
	$div_name = 'TableDiv_'.$mod_id;
	$db_table = 'mod_table_data';
	
	//	Get Table settings - read from db	----------
	$mysql_err_msg = 'This Table data unavailable';	
	$sql_statement = 'SELECT * FROM mod_table_config WHERE mod_id = "'.$mod_id.'" ';

	$mod_settings_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
	

	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{			
		//	Display / Hide In-Active Mods
		include ('CMS/cms_inactive_mod_display.php');			
		
		//	Editable area
		echo TAB_7.'<div id="'.$div_name.'" class="TableDiv HoverShow" title="Click to Edit">'."\n";
		
			require_once('CMS/cms_wysiwyg_mods/table_CMS.php');
 							
		echo TAB_7.'</div>'."\n";
			
	}
	
	else
	{
		if ($mod_info['active'] == 'on')
		{			
		
			echo TAB_7.'<div id="'.$div_name.'" class="TableDiv">'."\n"; 
			
				//	Table Heading
				if ($mod_settings_info['heading'] != '' AND $mod_settings_info['heading'] != NULL)
				{
					echo TAB_8.'<h2 id="Table_'.$mod_id.'_Heading" class="TableHeading" >' . $mod_settings_info['heading'] .'</h2>'."\n";
				}
					
				//	Text 1
				if ($mod_settings_info['text_1'] != '' AND $mod_settings_info['text_1'] != NULL)
				{
					echo TAB_8.'<p id="Table_'.$mod_id.'_Text_1" class="TableText_1" >' . $mod_settings_info['text_1'] .'</p>'."\n";
				}			
				
				//	Get Table Data - read from db	----------
				$mysql_err_msg = 'This Table data unavailable';	
				$sql_statement = 'SELECT * FROM mod_table_data' 
				
												.' WHERE mod_id = "'.$mod_id.'" '
												.' AND display = "on"'
												.' ORDER BY '.$mod_settings_info['order_by']
												;

				if($mod_data_result = ReadDB ($sql_statement, $mysql_err_msg))
				{
		
					echo TAB_8.'<table id="Table_'.$mod_id.'" class="Table" >'."\n";
					
					while($mod_data = mysql_fetch_array ($mod_data_result))
					{
						if($mod_data['row_type'] == 'th')
						{
							echo TAB_9.'<tr id="Table_'.$mod_id.'_Row_'.$mod_data['row_id'].'" class="TableRowHead" >'."\n";
						}
						else
						{
							echo TAB_9.'<tr id="Table_'.$mod_id.'_Row_'.$mod_data['row_id'].'" class="TableRow" >'."\n";
						}
						
						for ($col = 1; $col <= $mod_settings_info['num_cols']; $col++)
						{
							echo TAB_10.'<'.$mod_data['row_type'].' id="Table_Row_'.$mod_data['row_id'].'_col_'.$col.'"' 
										.' class="Table_'.$mod_data['row_type'].' Table_col_'.$col.'" >'."\n";
							
								echo TAB_11.$mod_data['col_'.$col] ."\n";
							
							echo TAB_10.'</'.$mod_data['row_type'].'>'."\n";
						}
					
					
						echo TAB_9.'</tr>'."\n";
					
					}

					echo TAB_8.'</table>'."\n";
					
				}
									
				
				//	Text 2
				if ($mod_settings_info['text_2'] != '' AND $mod_settings_info['text_2'] != NULL)
				{
					echo TAB_8.'<p id="Table_'.$mod_id.'_Text_2" class="TableText_2" >' . $mod_settings_info['text_2'] .'</p>'."\n";
				}

			echo TAB_7.'</div>'."\n"; 	
				
				
		}

	}
		
?>