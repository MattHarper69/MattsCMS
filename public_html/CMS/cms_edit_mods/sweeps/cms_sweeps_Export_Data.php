<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'] . '&tab='.$_GET['tab'];
					
	// SAVE, DELETE or EXPORT
	if (isset($_POST['submit_export_data'])  OR isset($_POST['submit_save_profile']) OR isset($_POST['submit_delete_profile']))
	{
		//	validate if SAVING or EXPORTING
		if (!isset($_POST['submit_delete_profile']))
		{
			///////////////		DO VALIDATION				/////////////////////
	//	===========================	EDITED TO HERE	=======================================================================				
		}

		
		require_once ('cms_update_export_data.php');
	}
					
					
	$cat_type_array = array(
							 0 => 'Orders'
							,1 => 'Products'			
						);	

	$file_type_array = array(
							 0 => 'csv'
							,1 => 'txt'
							,2 => 'sql'								
						);	

	//	Must copy the following array to "cms_ajax_updates.php"
	$file_suffix_array = array(
								 0 => '(none)'
								,1 => 'date (dd-mm-yy)'
								,2 => 'date (yy-mm-dd)'
								,3 => 'date (ddmmyy)'
								,4 => 'date (yymmdd)'								
						);	

	$operators_array = array(
							 '=' => 'equal to'
							,'!=' => 'not equal to'
							,'>' => 'greater than'
							,'<' => 'less than'							
							,'>=' => '> or equal to'
							,'<=' => '< or equal to'
							,'LIKE' => 'like'							
						);	
	
	//	Update DB tables to reflect hard coded arrays
	
	$value_str = '';
	foreach ($cat_type_array as $value)
	{
		$value_str .= '"'.$value.'",';
	}
	
	$value_str = substr( $value_str, 0 , -1);	
	$sql_statement = 'ALTER TABLE sweeps_export_data_profiles CHANGE category category SET( '.$value_str.' ) NOT NULL';
	ReadDB ($sql_statement, $mysql_err_msg);
	
	$value_str = '';
	foreach ($file_type_array as $value)
	{
		$value_str .= '"'.$value.'",';
	}
	
	$value_str = substr( $value_str, 0 , -1);	
	$sql_statement = 'ALTER TABLE sweeps_export_data_profiles CHANGE file_type file_type SET( '.$value_str.' ) NOT NULL';
	ReadDB ($sql_statement, $mysql_err_msg);
	
	$value_str = '';
	foreach ($file_suffix_array as $value)
	{
		$value_str .= '"'.$value.'",';
	}
	
	$value_str = substr( $value_str, 0 , -1);	
	$sql_statement = 'ALTER TABLE sweeps_export_data_profiles CHANGE filename_suffix filename_suffix SET( '.$value_str.' ) NOT NULL';
	ReadDB ($sql_statement, $mysql_err_msg);
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//	need for Calendar Popup
	echo TAB_2.'<script type="text/javascript" >document.write(getCalendarStyles());</script>'."\n";
	echo TAB_2.'<div id="CancelDatePopup" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">'."\n";
	echo TAB_2.'</div>'."\n"; 

	
	//	Select a profile
	
	$profile_id = '';
	if(isset($_GET['profile_id']))
	{
		$profile_id = $_GET['profile_id'];
	}
	
	echo TAB_2.'<form action = "'.$this_page.'&amp;profile_id='.$profile_id.'"'
				.' method="post" enctype="multipart/form-data" >'."\n";
				
		echo TAB_3.'<fieldset class="AdminForm2" style="height:20px; margin-bottom:10px;">' ."\n";	
			
			echo TAB_4.'Select a Profile: <select onchange="location.href=this.value;">' ."\n";
			
				$mysql_err_msg = 'Export data profile listing';
				$sql_statement = 'SELECT profile_id, name FROM sweeps_export_data_profiles';
																
				if (!isset($_GET['profile_id']) OR $_GET['profile_id'] == 'new')
				{
					echo TAB_5.'<option selected="selected"></option>'."\n";	
				}
				
				$export_profile_result = ReadDB ($sql_statement, $mysql_err_msg);	
				while ($export_profile_info = mysql_fetch_array ($export_profile_result))
				{
					if ( $export_profile_info['profile_id'] == $profile_id) { $selected = ' selected="selected"';}
					else { $selected = '';}
					echo TAB_5.'<option value="'.$this_page.'&amp;profile_id='.$export_profile_info['profile_id'].'"'.$selected.' >'
								.$export_profile_info['name'].'</option>'."\n";	
				}
					
			echo TAB_4.'</select>' ."\n";

			//	Add new Profile Link
			echo TAB_4.' OR <a class="ButtonLink" href="'.$this_page.'&amp;profile_id=new" title="Add a new Profile" >Add New Profile</a>' ."\n";
			
			if (isset($_GET['profile_id']))
			{	
				//	Save Profile Button
				echo TAB_4.'<button type="submit" name="submit_save_profile" title="Save this Profile" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_save_16x16.png" alt="Save Profile" />' ."\n";
				echo TAB_4.'</button>' ."\n";

				//	Delete Profile Button
				echo TAB_4.'<button type="submit" name="submit_delete_profile" title="Delete this Profile" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_delete_16x16.png" alt="Delete Profile" />' ."\n";
				echo TAB_4.'</button>' ."\n";
				
				//	Export Button
				echo TAB_4.'<button type="submit" name="submit_export_data" style="float: right;"' 
							.' title="Save this Profile and Export this data" >EXPORT DATA</button>' ."\n";
			
			}
	//	===========================	EDITED TO HERE	=======================================================================	
			//// DO confirm Delete Profile ??
	
		echo TAB_3.'</fieldset>' ."\n";		
	
	//	Profile Details
	if (isset($_GET['profile_id']))
	{
		echo TAB_3.'<fieldset class="AdminForm2">' ."\n";			
			
		if ($_GET['profile_id'] == 'new')
		{
			// use defaults
			$export_profile_info['name'] = 'new profile '.rand(100,200);
			$export_profile_info['category'] = 'Orders';
			$export_profile_info['file_type'] = 'csv';
			$export_profile_info['file_name'] = 'orders';			
			$export_profile_info['filename_suffix'] = 'date (dd-mm-yy)';
		}
		else
		{
			$mysql_err_msg = 'Export data profile Information';
			$sql_statement = 'SELECT * FROM sweeps_export_data_profiles WHERE profile_id = "'.$_GET['profile_id'].'"';
																
			$export_profile_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));		
		}
		
		echo TAB_3.'<input type="hidden" name="export_data_profile_id" value="'.$_GET['profile_id'].'" />'."\n";

			echo TAB_4.'<ul class="AdminForm2 Horizontal">' ."\n";	
			
				//	Profile Name
				echo TAB_5.'<li>Profile Name: <input type="text" name="export_data_profile_name" value="'.$export_profile_info['name'].'" />'
							.'</li>' ."\n";
				
				//	Data to output ?
				echo TAB_5.'<li>Data from: <select name="export_data_category">' ."\n";
				
				foreach ($cat_type_array as $value)
				{
					if ($export_profile_info['category'] == $value){$selected = ' selected="selected"';}
					else { $selected = '';}					
					echo TAB_6.'<option '.$selected.'>'.$value.'</option>' ."\n";				
				}
			
				echo TAB_5.'</select></li>' ."\n";
				
				
				//	Display Headers
				if (isset($export_profile_info['print_headers']) AND $export_profile_info['print_headers'] == 'on')
				{$checked = ' checked="checked"';}
				else { $checked = '';}	
				echo TAB_5.'<li><input type="checkbox" name="export_data_print_headers" '.$checked.' />Display Column Titles</li>' ."\n";

			echo TAB_4.'</ul>' ."\n";	
			echo TAB_4.'<ul class="AdminForm2 Horizontal">' ."\n";
			
				//	FileName
				
				//	Ajax to update filename preview
				echo TAB_5.'<script type="text/javascript">
				$(document).ready(function()
				{
					$("#ExportDataFileType, #ExportDataFilename, #ExportDataFileSuffix").change
					(function()
					{
						var dataString = "filetype=" + $("#ExportDataFileType").val()
							+ "&filename=" + $("#ExportDataFilename").val() + "&filesuffix=" + $("#ExportDataFileSuffix").val();
						
						$.ajax
						({
							type: "POST",
							url: "/CMS/cms_ajax_updates.php",
							data: dataString,
							cache: false,
							success: function(html)
							{
								$("#ExportDataPreviewFilename").html(html);
							} 
						});

					});

				});
				</script>' ."\n";					
								
				//	File Type
				echo TAB_5.'<li>File Type: <select id="ExportDataFileType" name="export_data_file_type">' ."\n";
				
				foreach ($file_type_array as $value)
				{
					if ($export_profile_info['file_type'] == $value){$selected = ' selected="selected"';}
					else { $selected = '';}					
					echo TAB_6.'<option '.$selected.'>'.$value.'</option>' ."\n";				
				}
			
				echo TAB_5.'</select></li>' ."\n";
				
				//	File Name

				echo TAB_5.'<li>File Name: <input type="text" id="ExportDataFilename" name="export_data_filename"' 
							.' value="'.$export_profile_info['file_name'].'" /></li>' ."\n";
				
				//	File Name Suffix
				echo TAB_5.'suffix: <select id="ExportDataFileSuffix" name="export_data_file_suffix">' ."\n";
				
				foreach ($file_suffix_array as $value)
				{
					if ($export_profile_info['filename_suffix'] == $value){$selected = ' selected="selected"';}
					else { $selected = '';}					
					echo TAB_6.'<option '.$selected.'>'.$value.'</option>' ."\n";				
				}
			
				echo TAB_5.'</select></li>' ."\n";
				
				//	File Name Preview
				switch ($export_profile_info['filename_suffix'])
				{
					case $file_suffix_array[0]:
						$suffix = '';				
					break;
					case $file_suffix_array[1]:
						$suffix = '_' . date("d-m-y");				
					break;					
					case $file_suffix_array[2]:
						$suffix = '_' . date("y-m-d");				
					break;					
					case $file_suffix_array[3]:
						$suffix = '_' . date("dmy");				
					break;
					case $file_suffix_array[4]:
						$suffix = '_' . date("ymd");				
					break;					
				}
				
				$filename = $export_profile_info['file_name'] . $suffix . '.' .$export_profile_info['file_type'];
				
				echo TAB_5.'<li id="ExportDataPreviewFilename" >' ."\n";	
				
					echo TAB_5 . $filename ."\n";
									
				echo TAB_5.'</li>' ."\n";
			echo TAB_4.'</ul>' ."\n";

			
			//	Get All available FIELDS
			switch ($export_profile_info['category'])
			{
				case 'Orders':				
					$from_tables = 'sweeps_orders, sweeps_ordered_carts';
				break;
				case 'Products':				
					$from_tables = 'sweeps_items, sweeps_categories';
				break;
			}
			
			echo TAB_3.'<input type="hidden" name="export_data_from_tables" value="'.$from_tables.'" />'."\n";
			
			$mysql_err_msg = 'all fields available Information';			
			$sql_statement = 'SELECT * FROM '.$from_tables;	

			$result = ReadDB ($sql_statement, $mysql_err_msg);
			
			$all_fields_array = array();		
			for ($i=1; $i < mysql_num_fields($result); $i++)
			{
				$all_fields_array[$i] = mysql_field_name($result, $i);
			}			
			
		
			// Choose Fields export
			echo TAB_4.'<p>Select Data Fields to export:</p>' ."\n";
			echo TAB_4.'<ul class="AdminForm2 Horizontal">' ."\n";

				$mysql_err_msg = 'Export data Information';
				$sql_statement = 'SELECT fields_selected FROM sweeps_export_data_fields'
				
														.' WHERE profile_id = "'.$_GET['profile_id'].'"'
														.' ORDER BY seq'
														;
				$export_fields_result = ReadDB ($sql_statement, $mysql_err_msg);
				
				$num = 1;
				while ($selected_fields = mysql_fetch_array ($export_fields_result))
				{
					echo TAB_5.'<li><select name="export_data_selected_field_'.$num.'">' ."\n";

						echo TAB_6.'<option value="" class="WarningMSG" >[Not selected / Remove]</option>' ."\n";
						foreach ($all_fields_array as $value)
						{
							if ($selected_fields['fields_selected'] == $value){$selected = ' selected="selected"';}
							else { $selected = '';}					
							echo TAB_6.'<option '.$selected.'>'.$value.'</option>' ."\n";				
						}
				
					echo TAB_5.'</select></li>' ."\n";
					$num++;
				}
					
	//	===========================	EDITED TO HERE	=======================================================================	
		///	Use jQuery / Ajaax to add a another FIELD selector ??
					//	add a spare field
					echo TAB_5.'<li><select name="export_data_selected_field_'.$num.'">' ."\n";

						echo TAB_6.'<option value="" class="WarningMSG" >[Not selected / Remove]</option>' ."\n";
						foreach ($all_fields_array as $value)
						{		
							echo TAB_6.'<option>'.$value.'</option>' ."\n";				
						}
				
					echo TAB_5.'</select></li>' ."\n";
		
			echo TAB_4.'</ul>' ."\n";
			
			echo TAB_3.'<input type="hidden" name="export_data_num_fields" value="'.$num.'" />'."\n";
						
			// Choose Field Filters to export
			echo TAB_4.'<p>Select Data Filters to export:</p>' ."\n";
			echo TAB_4.'<table class="AdminForm2">' ."\n";

				for ($num = 1; $num < 4; $num++)
				{
					$sql_statement = 'SELECT * FROM sweeps_export_data_filters' 
					
														.' WHERE profile_id = "'.$_GET['profile_id'].'"' 
														.' AND filter_id = "'.$num.'"';
														
					$selected_filters = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
					
					echo TAB_5.'<tr>' ."\n";
						
						echo TAB_6.'<td>' ."\n";

							if ($selected_filters['active'] == 'on'){$checked = ' checked="checked"';}
							else { $checked = '';}	
							echo TAB_5.'<input type="checkbox" name="export_data_filter_active_'.$num.'" '.$checked.' />' ."\n";

						echo TAB_6.'</td>' ."\n";

//	===========================	EDITED TO HERE	=======================================================================	
				/////////	use ajax to grey-out and disable the following when not checked		


					if ($num > 1) { echo TAB_6.'<td> AND </td>' ."\n";}				
						else {echo TAB_6.'<td> WHERE </td>' ."\n";}
					
						echo TAB_6.'<td>' ."\n";

							//	filter field name
							echo TAB_7.'<select name="export_data_filter_name_'.$num.'">' ."\n";
								
								echo TAB_6.'<option value="" class="WarningMSG" >[Not selected / Remove]</option>' ."\n";
								foreach ($all_fields_array as $value)
								{
									if ($selected_filters['filter_name'] == $value){$selected = ' selected="selected"';}
									else { $selected = '';}					
									echo TAB_8.'<option '.$selected.'>'.$value.'</option>' ."\n";				
								}
								
							echo TAB_7.'</select>' ."\n";
						
						echo TAB_6.'</td>' ."\n";
						echo TAB_6.'<td>' ."\n";
						
							//	filter operator
							echo TAB_7.' IS <select name="export_data_filter_operator_'.$num.'">' ."\n";

							foreach ($operators_array as $key => $value)
							{
								if ($selected_filters['filter_operator'] == $key){$selected = ' selected="selected"';}
								else { $selected = '';}					
								echo TAB_8.'<option '.$selected.' value="'.$key.'" >'.$value.'</option>' ."\n";				
							}				
							echo TAB_7.'</select>' ."\n";

						echo TAB_6.'</td>' ."\n";
						echo TAB_6.'<td>' ."\n";
						
							//	filter data
							echo TAB_7.'<input type="text" name="export_data_filter_data_'.$num.'"'
										.' value="'.$selected_filters['filter_data'].'" />' ."\n";
						
						echo TAB_6.'</td>' ."\n";							
						echo TAB_6.'<td>' ."\n";

							//	do javascript date picker						
							echo TAB_7.'<script type="text/javascript" id="jscal_1">var calendar_'.$num.' = new CalendarPopup("CancelDatePopup");'
									  .'</script>' ."\n"; 
											
							echo TAB_7.'<a href="#" onClick="calendar_'.$num.'.select('
										.'document.forms[0].export_data_filter_data_'.$num.',\'anchor_'.$num.'\',\'yyyy-MM-dd\'); return false;"'
										.' id="anchor_'.$num.'" title="Click to insert a Date" >' ."\n";  
								echo TAB_8.'<img src="/images_misc/icon_calendar_32x32.png" alt="insert date" />' ."\n"; 
							echo TAB_7.'</a>' ."\n";  						

						echo TAB_6.'</td>' ."\n";
					
					echo TAB_5.'</tr>' ."\n";
									
				}
				
			echo TAB_4.'</table>' ."\n";

			// Choose ORDER BY export
			echo TAB_4.'<p>Select Data Fields to Order by:</p>' ."\n";
			echo TAB_4.'<ul class="AdminForm2 Horizontal">' ."\n";

				for ($num = 1; $num < 4; $num++)
				{
					$sql_statement = 'SELECT fields_order_by FROM sweeps_export_data_order_by'

															.' WHERE profile_id = "'.$_GET['profile_id'].'"'
															.' AND order_by_id = "'.$num.'"';
															
					$selected_order_by = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));					
					
					echo TAB_5.'<li>'.NumberSuffix($num).' : <select name="export_data_order_by_'.$num.'">' ."\n";

					foreach ($all_fields_array as $value)
					{
						if ($selected_order_by['fields_order_by'] == $value){$selected = ' selected="selected"';}
						else { $selected = '';}					
						echo TAB_6.'<option '.$selected.'>'.$value.'</option>' ."\n";				
					}
				
					echo TAB_5.'</select></li>' ."\n";

				}

			echo TAB_4.'</ul>' ."\n";				
				
		echo TAB_3.'</fieldset>' ."\n";	
	
	}
				
	echo TAB_2.'</form>'."\n";
						
	

?>