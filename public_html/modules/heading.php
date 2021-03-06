<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	$div_name = 'ModHeading_'.$mod_id;
	$db_table = 'mod_heading';
	
	//	read from db	----------
	$mysql_err_msg = 'This Page heading unavailable';	
	$sql_statement = 'SELECT * FROM mod_heading WHERE mod_id = "'.$mod_id.'" ';

	if ($mod_data_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg)))
	{
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{			

			$can_not_clone = 0;
			
			if ($mod_data_info['locked'] != 'on')
			{			
				
				$edit_enabled = 2;
				$mod_locked = 0;				

				//	Display / Hide In-Active Mods
				include ('CMS/cms_inactive_mod_display.php');
				
				//	Do View/edit HTML display
				echo "\n".TAB_7.'<div class="CMS_EditHTMLPanel" id="CMS_EditHTMLPanel_'.$mod_id.'" style="display:none;">'." \n";		
					echo TAB_8.'<textarea id="EditHtmlTextArea_'.$mod_id.'" class="EditHtmlTextArea UpdateData"></textarea>'. "\n";
				echo TAB_7.'</div>'." \n\n";

				//	Editable area
				echo TAB_7.'<'.$mod_data_info['heading_element'].' id="'.$div_name.'" class="ModHeading HoverShow Draggable"'
					.' onClick="javascript:selectMod2Edit(14, '.$mod_id.',\'ModHeading_'.$mod_id.'\',2,0);"' ."\n";
					echo TAB_8.' title="Click to Edit Heading">'."\n";
					echo TAB_8.'<span id="ModData_'.$mod_id.'" class="UpdateMe"'."\n";	
						echo TAB_9.' onFocus="javascript:TextContentChangedFocus(\''.$div_name.'\');"'
						.' onBlur="javascript:TextContentChangedBlur(\''.$div_name.'\');" >'."\n";
						
						echo TAB_9.$mod_data_info['text'] ."\n";
				
					echo TAB_8.'</span>' ."\n";
						
			}

			else
			{
				$edit_enabled = 0;	
				$mod_locked = 1;		

				//	Display / Hide In-Active Mods
				include ('CMS/cms_inactive_mod_display.php');
				
				echo TAB_7.'<'.$mod_data_info['heading_element'].' id="'.$div_name.'" class="ModHeading HoverShow Draggable"'
					.' onClick="javascript:selectMod2Edit(14, '.$mod_id.',\'ModHeading_'.$mod_id.'\',0,1);"'."\n";
					echo TAB_8.' title="Mod is Locked from editing - Click for options" >' ."\n";
					echo TAB_8.$mod_data_info['text'] ."\n";
			}
								
			echo TAB_7.'</'.$mod_data_info['heading_element'].'>'."\n";	
			
			//	CSS layout Dispay (for CMS)
			$CSS_layout = '&lt;'.$mod_data_info['heading_element'].' id="<strong>'.$div_name.'</strong>" class="<strong>ModHeading</strong>" &gt;'
							.'<span class="FinePrint"> (MODULE CONTENT HERE) </span>&lt;/'.$mod_data_info['heading_element'].'&gt;';		
			
			//	Do mod editing Toolbar
			include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
			
			//	Do Mod Config Panel
			include ('CMS/cms_panels/cms_panel_mod_config.php');			
		}
		
		else
		{
			if ($mod_info['active'] == 'on')
			{			
				echo TAB_8.'<'.$mod_data_info['heading_element'].' id="'.$div_name.'" class="ModHeading" >' ."\n";							
					echo TAB_9.HiliteText($mod_data_info['text']) ."\n";					
				echo TAB_8.'</'.$mod_data_info['heading_element'].'>' ."\n";				
			}

		}	
	}

	
?>