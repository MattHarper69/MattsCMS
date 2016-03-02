<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


//	CLONE A MODULE (UNDER CONSTRUCTION)	============================================================================================

/////////////////////////////////////////////////////////////////////////////////////
///		To be used for just Cloning a module (without Cloning a page)	///////////////
/////////////////////////////////////////////////////////////////////////////////////

	function CloneModule ()
	{
		// NEEDS function: CloneModuleData	
			
			//^^^^^^^^^^^^^^^^^^	EDITED TO HERE	^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^	
			
			//	Add entry to 'modules' table
			//$sql_statement = 'INSERT INTO modules SET'
			
			//		etc....
			
			//====================================================================

		CloneModuleData ($source_mod_id, $new_mod_id, $mod_db_table_name);
			
	}
	

//	CLONE ALL MODS IN A DIV (PAGE OR USER DEFINED) ===============================================================================

	function CloneModsInDiv ($source_page_id, $new_page_id, $div_id, $new_div_mod_id)
	{
		
		//	get Mod info from orginal page
		$mysql_err_msg = 'Reading Source Page Mod Configuration';
		$sql_statement = 'SELECT * FROM  modules WHERE page_id = '.$source_page_id.' AND div_id = '.$div_id;

		$all_mod_info_result = ReadDB ($sql_statement, $mysql_err_msg);
		while ($source_mod_info = mysql_fetch_array ($all_mod_info_result))
		{
						
			//	IF last mod inserted was a div mod than div id is this div and not banner, footer etc
			if ($new_div_mod_id != 0)
			{
				$div_id	= $new_div_mod_id;
			}
			
			//	Write new Mod info
			$mysql_err_msg = 'Updating New Page Mod Configuration';
			$sql_statement = 'INSERT INTO modules SET'
			
													.'  page_id = '.$new_page_id
													.', mod_type_id = '.$source_mod_info['mod_type_id']
													.', div_id = '.$div_id
													.', position = '.$source_mod_info['position']
													.', theme_specific = "'.$source_mod_info['theme_specific'].'"'
													.', screen_only = "'.$source_mod_info['screen_only'].'"'
													.', active = "'.$source_mod_info['active'].'"'
													.', sync_id = "'.$source_mod_info['sync_id'].'"'
													.', class_name = "'.$source_mod_info['class_name'].'"'
													;
			
			ReadDB ($sql_statement, $mysql_err_msg);
			
			$new_mod_id = mysql_insert_id();						
			

			//	get db table name info for each mod
			$mysql_err_msg = 'Database table name for new Module: '.$new_mod_id;
			$sql_statement = 'SELECT mod_db_table FROM _module_types WHERE mod_type_id = '.$source_mod_info['mod_type_id'];
	
			$mod_type_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			$mod_db_table_name = $mod_type_info[0];
			
			if ($mod_db_table_name != '')
			{
				
				//	Write new Entry into the Module

				CloneModuleData ($source_mod_info['mod_id'], $new_mod_id, $mod_db_table_name);

				//	Div Mod -- IF last mod inserted was a div mod than need to check for contained modules
				if ($mod_db_table_name = 'mod_div')
				{
					CloneModsInDiv ($source_page_id, $new_page_id, $source_mod_info['mod_id'], $new_mod_id);							
				}		
				
			}
	
		}
					
	}
	
	
	//	CLONE MODULES SPECIFIC DATA (DOES NOT ENTER MOD INTO MODULES TABLE)	 =========================
	
	function CloneModuleData ($source_mod_id, $new_mod_id, $mod_db_table_name)
	{
		//	Write new Entry into the Module
		$mysql_err_msg = 'Inserting new Module: '.$new_mod_id;
		$sql_statement = 'INSERT INTO '.$mod_db_table_name.' SET mod_id = '.$new_mod_id;

		ReadDB ($sql_statement, $mysql_err_msg);
		
		//	get table field names
		$mysql_err_msg = 'Obtain Database table field names for new Module: '.$new_mod_id;
		$sql_statement = 'SHOW COLUMNS FROM '.$mod_db_table_name;								
										
		$something2 = ReadDB ($sql_statement, $mysql_err_msg);
		while ($field_names_info = mysql_fetch_array ($something2))
		{
																
			if ( $field_names_info['Field'] != 'mod_id' )
			{

				//	get specific mod info
				$sql_statement = 'SELECT '.$field_names_info['Field'].' FROM '.$mod_db_table_name

													.' WHERE mod_id = '.$source_mod_id;
													
				$source_row_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			

				$data = addslashes($source_row_info[0]);
												
				//	Write new Specific Mod info
				$sql_statement = 'UPDATE '.$mod_db_table_name
				
										.' SET '.$field_names_info['Field'].' = "'.$data.'"'

										.' WHERE mod_id = "'.$new_mod_id.'"'
										;

				if (!UpdateDB ($sql_statement, $mysql_err_msg))
				{
					$_SESSION['update_error_msg'] = ' - A Database Error occured \n';
				}
				
			}							
				
		}	
	
		
		switch ($mod_db_table_name)
		{	

			//	Contact Form
			case 'mod_contact_form':

				CloneContactForm ($source_mod_id, $new_mod_id);
			
			break;
		
			
			//	Brochure
			case 'mod_brochure_settings':
			//vvvvvvvvvvvvvvvvvvvvvvvvvvvv	EDITED TO HERE	vvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			
			//====================================================================				
			break;


			//	Photo Gallery
			case 'mod_photo_gal_settings':
			//vvvvvvvvvvvvvvvvvvvvvvvvvvvv	EDITED TO HERE	vvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
			
			//====================================================================				
			break;
			
		}	
	
	}
	

//	CLONE CONTACT FORMS ELEMENTS TABLE (DOES NOT ENTER MOD INTO MODULES TABLE)	 =========================

	function CloneContactForm ($source_mod_id, $new_mod_id)
	{
		
		//	get table field names
		$mysql_err_msg = 'Obtain Database table field names for new Contact Form Module: '.$new_mod_id;
		$sql_statement = 'SHOW COLUMNS FROM mod_contact_form_elements';								
										
		$field_names_result = ReadDB ($sql_statement, $mysql_err_msg);
		
		$field_names = array();
		while($field_names_info = mysql_fetch_array ($field_names_result))
		{
			if ($field_names_info[0] != 'element_id' AND $field_names_info[0] != 'mod_id')
			{
				$field_names[] = $field_names_info[0];				
			}
	
		}
			
		//	get source mod contact form element info
		$sql_statement = 'SELECT * FROM mod_contact_form_elements WHERE mod_id = '.$source_mod_id;

		$result = ReadDB ($sql_statement, $mysql_err_msg);
		while ($source_row_info = mysql_fetch_array ($result))
		{
			
			//	Write new  mod contact form element info
			$is_select_element = 0;
			$insert_set_str = '';
			foreach ($field_names as $field_name)
			{
				$insert_set_str .= ', '.$field_name.' = "'.$source_row_info[$field_name].'"';
				
				//	while looping thru field, check for select boxes. if found will need to update contact_form_options table
				if ( $field_name == 'element' AND $source_row_info[$field_name] == 'select')
				{
					$is_select_element = 1;
				
				}					
			}
							
			$mysql_err_msg = 'Updating New Mod contact form element info';
			$sql_statement = 'INSERT INTO mod_contact_form_elements SET'

										.' mod_id = "'.$new_mod_id.'"'
										.$insert_set_str
										;

		
			
			if (!ReadDB ($sql_statement, $mysql_err_msg))	// must be ReadDB not UpdateDB to get mysql_insert_id
			{
				$_SESSION['update_error_msg'] = ' - A Database Error occured \n';
			}
						
			if ($is_select_element)
			{
				// will need to update contact_form_options table
				
				$source_element_id = $source_row_info['element_id'];
				$new_element_id = mysql_insert_id();
				
				//	get table field names
				$mysql_err_msg = 'Obtain Database table field names for new Contact Form Module element options';
				$sql_statement = 'SHOW COLUMNS FROM mod_contact_form_options';								
												
				$field_names_result_2 = ReadDB ($sql_statement, $mysql_err_msg);
				
				$field_names = array();
				while($field_names_info_2 = mysql_fetch_array ($field_names_result_2))
				{
					if ($field_names_info_2[0] != 'id' AND $field_names_info_2[0] != 'element_id')
					{
						$field_names[] = $field_names_info_2[0];				
					}
			
				}

			
				//	get source mod contact form element Options info
				$sql_statement = 'SELECT * FROM mod_contact_form_options WHERE element_id = '.$source_element_id;

				$result_2 = ReadDB ($sql_statement, $mysql_err_msg);
				while ($source_row_info_2 = mysql_fetch_array ($result_2))
				{
					
					//	Write new  mod contact form element Opions info

					$insert_set_str = '';
					foreach ($field_names as $field_name)
					{
						$insert_set_str .= ', '.$field_name.' = "'.$source_row_info_2[$field_name].'"';
										
					}
									
					$mysql_err_msg = 'Updating New Mod mod_contact_form_options info';
					$sql_statement = 'INSERT INTO mod_contact_form_options SET'

												.' element_id = "'.$new_element_id.'"'
												.$insert_set_str
												;

				
					
					if (!UpdateDB ($sql_statement, $mysql_err_msg))
					{
						$_SESSION['update_error_msg'] = ' - A Database Error occured \n';
					}
						
				}
				
			}
			
		}
		
	}
	
?>