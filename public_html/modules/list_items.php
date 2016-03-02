<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	$div_name = 'ListItems_'.$mod_id;
	
		//	read from db	----------
		$mysql_err_msg = 'This Page text unavailable';	
		$sql_statement = 'SELECT * FROM mod_list_items WHERE mod_id = "'.$mod_id.'" '
													.' AND active="on"'
													.' ORDER BY seq';

		$num_results = mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg));
		$text_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));		
		$list_style = $text_info['style'];	
	
	
	
	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{		
		$edit_enabled = 1;
		$mod_locked = 2;
		

		//	Display / Hide In-Active Mods
		include ('CMS/cms_inactive_mod_display.php');
	
		echo TAB_7.'<div class="ListItems ContentEditable HoverShow Draggable" id="ListItems_'.$mod_id.'" >'."\n";

			echo TAB_8.'<'.$list_style.' class="ListItems" >'."\n";			
			
			$li_id_array = array();
			$list_item_result = ReadDB ($sql_statement, $mysql_err_msg);
			while ($text_info = mysql_fetch_array ($list_item_result))
			{	
				$li_id_array[] = $text_info['li_id'];
				
				echo TAB_9.'<li id="ListItems_'.$mod_id.'_'.$text_info['li_id'].'" class="ListItems ContentEditable HoverShow"'."\n";	
					echo TAB_10.'onClick="javascript:selectMod2Edit(2, '.$mod_id.',\''.$div_name.'\',1,2);" title="Click to Edit" >'."\n";
				
					echo TAB_10.'<span id="'.$mod_info['mod_type_id'].'_'.$mod_id.'_'.$text_info['li_id'].'"'
								.' class="UpdateMe"' ."\n";
			
						echo TAB_11.' onFocus="javascript:TextContentChangedFocus(\''.$div_name.'\');"'
						.' onBlur="javascript:TextContentChangedBlur(\''.$div_name.'\');" >'."\n";								

								
						echo TAB_11.$text_info['text'] ."\n";
				
					echo TAB_10.'</span>' ."\n";
					
				echo TAB_9.'</li>' ."\n";				
			}
			
			echo TAB_8.'</'.$list_style.'>' ."\n";	

		echo TAB_7.'</div>'."\n";	
	
		//	CSS layout Dispay (for CMS)
		$CSS_layout = 	'&lt;div id="<strong>'.$div_name.'</strong>" class="<strong>ListItems</strong>" &gt;'
							.'<ul style="margin-left:2em;">'
								.'<li>&lt;'.$list_style.' class="<strong>ListItems</strong>" &gt;</li>'
									.'<ul style="margin-left:2em;">'
									.'<li></li>'
									.'<li><span class="FinePrint"><em>Sample listing...</em></span></li>'
									.'<li></li>'
									.'<li>&lt;li id="<strong>ListItems_'.$mod_id.'_'.$li_id_array[0].'</strong>"'
									.' class="<strong>ListItems</strong>"><span class="FinePrint"> (MODULE CONTENT HERE) </span>&lt/li&gt;</li>'
									.'<li>....</li>'
									.'<li>&lt;li id="<strong>ListItems_'.$mod_id.'_'.$li_id_array[count($li_id_array) - 1].'</strong>"'
									.' class="<strong>ListItems</strong>"><span class="FinePrint"> (MODULE CONTENT HERE) </span>&lt/li&gt;</li>'
									.'</ul>
								<li>&lt;/'.$list_style.'&gt;</li>'
							.'</ul>'
						.'&lt;/div&gt;';			
		
		//	Do mod editing Toolbar
		include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
		
		//	Do Mod Config Panel
		include ('CMS/cms_panels/cms_panel_mod_config.php');
		
		//	create jQuery function for updating this mod
		include ('CMS/cms_includes/cms_javascript_list_items_update_code.php');
		
	}

	else
	{
		if($num_results > 0)
		{
			echo TAB_7.'<div id="'.$div_name.'" class="ListItems" >'."\n";			
				
				echo TAB_8.'<'.$list_style.' class="ListItems" >'."\n";
				
				$list_item_result = ReadDB ($sql_statement, $mysql_err_msg);
				while ($text_info = mysql_fetch_array ($list_item_result))
				{
					echo TAB_9.'<li id="ListItems_'.$mod_id.'_'.$text_info['li_id'].'" class="ListItems">' ."\n";
								
						echo TAB_10.HiliteText($text_info['text']) ."\n";
					
					echo TAB_9.'</li>' ."\n";	
				
				}
				echo TAB_8.'</'.$list_style.'>' ."\n";	

			echo TAB_7.'</div>'."\n";			
		}

	
	}
	
	



////////////////////////////////////////////////////////////////////////////////////
	

		


			if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1 AND $text_info['locked'] != 'on')
			{						
			

			}
			




	
	
?>