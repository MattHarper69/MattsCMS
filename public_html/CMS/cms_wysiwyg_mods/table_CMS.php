<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
				
		$update_data_string = '';

	
		//	CSS layout Dispay (for CMS)
		$CSS_layout = '&lt;div id="<strong>'.$div_name.'</strong>" class="<strong>TableDiv</strong>" &gt;' ."\n"
						
					.'<p>&nbsp;&nbsp;&lt;h2 id="<strong>Table_'.$mod_id.'_Heading</strong>" class="<strong>TableHeading</strong>" &gt;' ."\n"
					.'<span class="FinePrint"> (CONTENT HERE) </span>&lt;/h2&gt;<sup>1</sup></p>' ."\n"

					.'<p>&nbsp;&nbsp;&lt;p id="<strong>Table_'.$mod_id.'_Text_1</strong>" class="<strong>TableText_1</strong>" &gt;' ."\n"
					.'<span class="FinePrint"> (CONTENT HERE) </span>&lt;/p&gt;<sup>1</sup></p>' ."\n"

					.'<p>&nbsp;&nbsp;&lt;tr id="<strong>Table_'.$mod_id.'_Row_<em>row ID</em></strong>"' ."\n"
         			.' class="<strong>TableRow</strong>" &gt;</p>' ."\n"
					
					.'<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;td id="<strong>Table_'.$mod_id.'_Row_<em>row ID</em>_col_<em>col n#</em></strong>"'."\n"
					.' class="<strong>Table_td</strong>" &gt;</p>'."\n"
					
					.'<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="FinePrint"> (CELL CONTENT HERE) </span></p>'	."\n"				

					.'<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;/td&gt;</p>'."\n"
					
					.'<p>&nbsp;&nbsp;&lt;/tr&gt;</p>'."\n"					
						
					.'<p>&nbsp;&nbsp;&lt;p id="<strong>Table_'.$mod_id.'_Text_2</strong>" class="<strong>TableText_2</strong>" &gt;'."\n"
					.'<span class="FinePrint"> (CONTENT HERE) </span>&lt;/p&gt;<sup>1</sup></p>'."\n"
										
					.'&lt;/div&gt;';
				

		//	Do mod editing Toolbar
		include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
		
		//	Do Mod Config Panel
		include ('CMS/cms_panels/cms_panel_mod_config.php');
		
		
		
		
		


				
	//	Table Heading
	//if ($mod_settings_info['heading'] != '' AND $mod_settings_info['heading'] != NULL)
	//{
		echo TAB_8.'<h2 id="Table_'.$mod_id.'_Heading" class="TableHeading  HoverShow"' 
			.' onClick="javascript:selectMod2Edit(38, '.$mod_id.', \'Table_'.$mod_id.'_Heading\',2,0);" >'  ."\n";
						
			echo TAB_9.'<span id="ModData_'.$mod_id.'_Heading" class="TableEditArea UpdateMe" title="Click to Edit Table Heading here"'."\n";
			
				echo TAB_10.' onFocus="javascript:TextContentChangedFocus(\'TableHeading_'.$mod_id.'\');"'
						.' onBlur="javascript:TextContentChangedBlur(\'TableHeading_'.$mod_id.'\');" >'."\n";
						
				echo TAB_10.$mod_settings_info['heading']."\n";
					
			echo TAB_9.'</span>' ."\n";
			
			//	Do View/edit HTML display
			echo "\n".TAB_9.'<span class="CMS_EditHTMLTable" id="CMS_EditHTMLTable_'.$mod_id.'_Heading" style="display:none;">'." \n";		
				echo TAB_10.'<textarea id="Table_'.$mod_id.'_Heading_EditHtml" class="TableEditHtmlTextArea"></textarea>'. "\n";
			echo TAB_9.'</span>'." \n\n";
		
		echo TAB_8.'</h2>'."\n";

	

		
	//}
		
	//	Text 1
	//if ($mod_settings_info['text_1'] != '' AND $mod_settings_info['text_1'] != NULL)
	//{
		echo TAB_8.'<p id="Table_'.$mod_id.'_Text_1" class="TableText_1  HoverShow"'
			.' onClick="javascript:selectMod2Edit(38, '.$mod_id.', \'Table_'.$mod_id.'_Text_1\',2,0);" >'  ."\n";

			echo TAB_9.'<span id="ModData_'.$mod_id.'_Text_1" class="TableEditArea UpdateMe" title="Click to Edit Table Header Text here"'."\n";
			
				echo TAB_10.' onFocus="javascript:TextContentChangedFocus(\'Text_1_'.$mod_id.'\');"'
						.' onBlur="javascript:TextContentChangedBlur(\'Text_1_'.$mod_id.'\');" >'."\n";		
				echo TAB_10. $mod_settings_info['text_1'] ."\n";
		
			echo TAB_9.'</span>' ."\n";
			
			//	Do View/edit HTML display
			echo "\n".TAB_9.'<span class="CMS_EditHTMLTable" id="CMS_EditHTMLTable_'.$mod_id.'_Text_1" style="display:none;">'." \n";		
				echo TAB_10.'<textarea id="Table_'.$mod_id.'_Text_1_EditHtml" class="TableEditHtmlTextArea"></textarea>'. "\n";
			echo TAB_9.'</span>'." \n\n";
		
		echo TAB_8.'</p>'."\n";
		
		
	//}			
	
	//	Get Table Data - read from db	----------
	$mysql_err_msg = 'This Table data unavailable';	
	$sql_statement = 'SELECT * FROM mod_table_data' 
	
									.' WHERE mod_id = "'.$mod_id.'" '
									.' AND display = "on"'
									.' ORDER BY '.$mod_settings_info['order_by']
									;

	if($mod_data_result = ReadDB ($sql_statement, $mysql_err_msg))
	{

		$num_rows = mysql_num_rows ($mod_data_result);
	
		echo TAB_8.'<table id="Table_'.$mod_id.'" class="Table" >'."\n";
		
		$row = 1;
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
				
				// Jquery Ajax update data string...
				$update_data_string 
				
				.= ',Row_'.$mod_data['row_id'].'_'.$col.' : GetTableDataHtml ("#Table_Row_'.$mod_data['row_id'].'_col_'.$col.'")' ."\n".TAB_10;
				
				echo TAB_10.'<'.$mod_data['row_type'].' id="Table_Row_'.$mod_data['row_id'].'_col_'.$col.'"' 
							.' class="Table_'.$mod_data['row_type'].'  HoverShow"' ."\n";
						echo TAB_10.' onClick="javascript:selectMod2Edit(38, '.$mod_id.', \'Table_Row_'.$mod_data['row_id'].'_col_'.$col.'\',2,0);" >'."\n";

					echo TAB_11.'<span id="ModData_Table_'.$mod_id.'_Row_'.$mod_data['row_id'].'_col_'.$col.'" class="TableEditArea UpdateMe"'."\n";
						echo TAB_12.' onFocus="javascript:TextContentChangedFocus(\'Table_Row_'.$mod_data['row_id'].'_col_'.$col.'\');"'."\n";
						echo TAB_12.' onBlur="javascript:TextContentChangedBlur(\'Table_Row_'.$mod_data['row_id'].'_col_'.$col.'\');" >'."\n";
							
						echo TAB_12.$mod_data['col_'.$col] ."\n";
					
					echo TAB_11.'</span>' ."\n";	

					
					//	Do View/edit HTML display
					echo "\n".TAB_11.'<span class="CMS_EditHTMLTable"'
						.' id="CMS_EditHTMLTable_'.$mod_id.'_Row_'.$mod_data['row_id'].'_col_'.$col.'" style="display:none;">'." \n";		
						echo TAB_12.'<textarea id="Table_Row_'.$mod_data['row_id'].'_col_'.$col.'_EditHtml"'
						.' class="TableEditHtmlTextArea"></textarea>'. "\n";
					echo TAB_11.'</span>'." \n\n";				
					
	
				echo TAB_10.'</'.$mod_data['row_type'].'>'."\n";
			}
		
			//	add new col ?
			if ($row == 1)
			{
				echo TAB_10.'<td id="Table_add_col" class="Table_add_col HoverShow" rowspan="'.$num_rows.'"'."\n";
					echo TAB_11.' onClick="javascript:ModTableAddColRow('.$mod_id.',\'col\');" title="Click to ADD a new Column" ></td>'."\n";	
			}

			echo TAB_9.'</tr>'."\n";
			
			$row++;
					
		}

			echo TAB_9.'<tr>'."\n";	
				echo TAB_10.'<td id="Table_add_row" class="Table_add_row HoverShow" colspan="'.$mod_settings_info['num_cols'].'"'."\n";
					echo TAB_11.' onClick="javascript:ModTableAddColRow('.$mod_id.',\'row\');" title="Click to ADD a new Row" ></td>'."\n";
			echo TAB_9.'</tr>'."\n";			

		echo TAB_8.'</table>'."\n";
		
	}
	

	
	//	Text 2
	//if ($mod_settings_info['text_2'] != '' AND $mod_settings_info['text_2'] != NULL)
	//{
		echo TAB_8.'<p id="Table_'.$mod_id.'_Text_2" class="TableText_2  HoverShow"'
		.' onClick="javascript:selectMod2Edit(38, '.$mod_id.', \'Table_'.$mod_id.'_Text_2\',2,0);" >'  ."\n";

			echo TAB_9.'<span id="ModData_'.$mod_id.'_Text_2" class="TableEditArea UpdateMe" title="Click to Edit Table Footer Text here"'."\n";
			
				echo TAB_10.' onFocus="javascript:TextContentChangedFocus(\'Text_2_'.$mod_id.'\');"'
						.' onBlur="javascript:TextContentChangedBlur(\'Text_2_'.$mod_id.'\');" >'."\n";		
				echo TAB_10. $mod_settings_info['text_2'] ."\n";
		
			echo TAB_9.'</span>' ."\n";
			
			//	Do View/edit HTML display
			echo "\n".TAB_9.'<span class="CMS_EditHTMLTable" id="CMS_EditHTMLTable_'.$mod_id.'_Text_2" style="display:none;">'." \n";		
				echo TAB_10.'<textarea id="Table_'.$mod_id.'_Text_2_EditHtml" class="TableEditHtmlTextArea"></textarea>'. "\n";
			echo TAB_9.'</span>'." \n\n";	
		
		echo TAB_8.'</p>'."\n";
		
		
		
	//}
	

	// Javascript for updating

	echo TAB_8."\n";

	echo TAB_8.'<script type="text/javascript" >

		function SaveModDataTable(mod_id, reload)
		{				 			
			function GetTableDataHtml (field)
			{
				
				if (text_html_mode == "text")
				{
					//	get content from editable <tag>
					var HTMLcontent = $(field + " span").html();	
				}
				else
				{
					//	get html content from <textarea> if set open
					var HTMLcontent = $("textarea#" + field + "_EditHtml").val();		
				}				
				
				if (HTMLcontent != null)
				{
					HTMLcontent = ReplaceTags(HTMLcontent); 	// tidy up HTML
					HTMLcontent = HTMLCharEncode(HTMLcontent); 			// Encode HTML Chars			
				}

				return HTMLcontent;
			
			};
	
			$.ajax({            
				 url: "CMS/cms_update/cms_update_wysiwyg.php"           
				,type: "POST"            
				,data:
				{ 
					 mod_id : '.$mod_id .'	
					,heading : GetTableDataHtml ("#Table_'.$mod_id.'_Heading")
					,text_1 : GetTableDataHtml ("#Table_'.$mod_id.'_Text_1")
					,text_2 : GetTableDataHtml ("#Table_'.$mod_id.'_Text_2")
				    '. $update_data_string . '	
			   
				}
	
				,success: function()
				{
					if (reload)
					{
						location.reload(true)					
					}
				}
	
			}); 	
		
		
		};
		
		function ModTableAddColRow(mod_id, row_or_col)
		{
			SaveModDataTable(mod_id);
			
/////////////////////////////// edited to here ///////////////////////////////////////////////////////
		}
		
		'."\n";	
		
	
	echo TAB_7.'</script>'."\n";	

				
?>				