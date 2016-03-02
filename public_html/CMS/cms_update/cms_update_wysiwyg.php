<?php
 
//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	
	$remove_tag_list = array ('<span' => '</span>');

	$mod_id = $_POST['mod_id'];	
	
	$mysql_err_msg = 'Up-dating Content';

	
	$file_path_offset = '../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	require_once ('cms_update_common.php');
	

	$mysql_err_msg = 'Fetching Module info for Updating';
	$sql_statement = 'SELECT ' 
	
								.' mod_db_table'
								.',modules.mod_type_id'
								.',div_id'
								.',page_id'
								//.',position'
								.',sync_id'
								.',modules.active'
								
								.' from modules, _module_types' 
	
								.' WHERE mod_id = '.$mod_id
								.' AND modules.mod_type_id = _module_types.mod_type_id'
								;

	$mod_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
	
		$mod_db_table = $mod_info['mod_db_table'];	
		$mod_type_id = $mod_info['mod_type_id'];
		$page_id = $mod_info['page_id'];
		$div_id = $mod_info['div_id'];
		//$position = $mod_info['position'];
		$sync_id = $mod_info['sync_id'];
		$active = $mod_info['active'];
	
		//	get Div Name
		switch ($div_id)
		{
			case '1': $div_name = 'banner'; break;
			case '2': $div_name = 'side_1'; break;
			case '3': break;
			case '4': $div_name = 'side_2'; break;
			case '5': $div_name = 'footer'; break;
			
		}
		
if ($_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1 AND $_SESSION['access'] < 6 )
{	
	
	///////	Do UNDO SQLs not functioning atm	//////////////////////////////////////
		
		$sql_statement = 'SET AUTOCOMMIT=0';
		//UpdateDB ($sql_statement, $mysql_err_msg);		
			
		$sql_statement = 'START TRANSACTION';
		//UpdateDB ($sql_statement, $mysql_err_msg);
		
		//$sql_statement = 'SAVEPOINT undo_'.$_SESSION['db_rollback_num'];
		//UpdateDB ($sql_statement, $mysql_err_msg);

	//////////////////////////////////////////////////////////////////////////////////
	

	
	/* 	
	//	for List Item Mods
	if ($mod_db_table == "mod_list_items")
	{

		foreach ($_POST as $ids => $texts)
		{
			if ($ids != 'mod_id')
			{
				$li_id = substr( $ids, 5);
			
				$text = CleanHtml($texts);
					
				//	need to remove any extra "</span>" tags to prevent parts of the text mod become un-editable		
				$text = remove_end_tags($text, $remove_tag_list);

				//	in IE "null" is displayed if string empty
				if ($text == 'null'){ $text = '';}	

				$sql_statement = 'UPDATE '.$mod_db_table.' SET text = "'.$text.'"'
				
										.' WHERE li_id = "'.$li_id.'"'	
										;
										

				UpdateDB ($sql_statement, $mysql_err_msg);
		
		
		
				//	sync to other mods ?
				if($div_id != 3 AND $div_id < 10)
				{
					// get the ID of the mod that needs updating
					$sql_statement = 'SELECT page_id FROM page_info'
						
														.' WHERE sync_'.$div_name.' = "on"'
														//.' AND '.$div_name.'_active = "on"'	// all divs should be modifide even when deactivated
														;
						
					$result =  ReadDB ($sql_statement, $mysql_err_msg);
						
					while ($mod_sync_info = mysql_fetch_array($result))
					{
						$sql_statement = 'SELECT mod_id FROM modules'

														.' WHERE sync_id = '.$sync_id
														.' AND page_id = '.$mod_sync_info['page_id']
														;
															
						$mods_2_sync = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

						$sql_statement = 'SELECT seq FROM '.$mod_db_table

														.' WHERE li_id = '.$li_id
														;
															
						$get_seq_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));						
							
						//	need to check if li record exists already for this mod / seq - create if doesn't
						$sql_statement = 'SELECT li_id FROM '.$mod_db_table
							
												.' WHERE mod_id = "'.$mods_2_sync['mod_id'].'"'	
												.' and seq = "'.$get_seq_info['seq'].'"'
												;
												
				$err_log_str .= "\n\n".$sql_statement	;														
						$li_exists = mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg));
					
						if ($li_exists < 1)
						{
							//	Doesnt exist - create new entry
							$sql_statement = 'INSERT INTO '.$mod_db_table.' SET'

												.' text = "'.$text.'"'
												.',mod_id = "'.$mods_2_sync['mod_id'].'"'	
												.',seq = "'.$get_seq_info['seq'].'"'
												;
												
						}
						
						else
						{
							//	or update existing
							$sql_statement = 'UPDATE '.$mod_db_table.' SET text = "'.$text.'"'
						
												.' WHERE mod_id = "'.$mods_2_sync['mod_id'].'"'	
												.' and seq = "'.$get_seq_info['seq'].'"'
												;				
						}
						
				//$err_log_str .= "\n\n".$sql_statement	;														
						UpdateDB ($sql_statement, $mysql_err_msg);			
							
					}

				}
					
			}

		}
	
	}
	
	 */

	//	for Table Mods
	if ($mod_db_table == "mod_table_config")
	{
		$data_array[] = array();

		foreach ($_POST as $field => $data)
		{
			$data = CleanHtml($data);
			
			//	need to remove any extra "</span>" tags to prevent parts of the text mod become un-editable		
			$data = remove_end_tags($data, $remove_tag_list);

			//	in IE "null" is displayed if string empty
			if ($data == 'null'){ $data = '';}			
			
				
			if ($field[0] == 'R')	// ie is a Cell data "Row_yy_xx"
			{
				$field_parts = explode("_",$field);
				
				$data_array[$field_parts[1]][$field_parts[2]] = $data;
			}
			else	// is mod_id. heading, text etc
			{
				$data_array[0][$field] = $data;				
			}		
			
		}

		//	Update heading and text
		$sql_statement = 'UPDATE '.$mod_db_table.' SET'

								.' heading = "'.$data_array[0]['heading'].'"'
								.',text_1 = "'.$data_array[0]['text_1'].'"'
								.',text_2 = "'.$data_array[0]['text_2'].'"'
								
								.' WHERE mod_id = "'.$mod_id.'"'	
								;
									
		UpdateDB ($sql_statement, $mysql_err_msg);
		
		// Update Cell Data
		foreach ($data_array as $row => $data)
		{
			if ($row != 0)
			{
				
				
				$sql_statement = 'UPDATE mod_table_data SET'
				
										.' col_1 = "'.$data[1].'"';

				for ($col = 2; $col <= 9; $col++)
				{
					if (array_key_exists($col, $data))
					{
						$sql_statement .= ' ,col_'.$col.' = "'.$data[$col].'"';					
					}

				}

										
					$sql_statement .=	 ' WHERE mod_id = "'.$mod_id.'"'
										.' AND row_id = "'.$row.'"'
										;
											
				UpdateDB ($sql_statement, $mysql_err_msg);			
			
			}
		
		}
		
			
		
	//////////////////////////	Updated to Here	////////////////////////////////////////////////////////////////////////
	
	
/* 
		$posts = print_r($_POST, TRUE);

		$log_filename = '/'.$_SERVER['DOCUMENT_ROOT'].'/../_errors/test_errors.log';
		$fopen = @fopen($log_filename,'w');
		$str = 'testing output from: cms_update_wysiwyg.php......'."\n".$posts."\n";
		fputs($fopen, $str);
		fclose($fopen);	
*/
	 
	}
	
	
	
	
	//	for Text and Heading mods
	else
	{
		$text = $_POST['content'];
		
		$text = CleanHtml($text);
		
		//	need to remove any extra "</span>" tags to prevent parts of the text mod become un-editable		
		$text = remove_end_tags($text, $remove_tag_list);

		//	in IE "null" is displayed if string empty
		if ($text == 'null'){ $text = '';}	
	
		$sql_statement = 'UPDATE '.$mod_db_table.' SET'
				
									.' text = "'.$text.'"'
									.' WHERE mod_id = "'.$mod_id.'"'	
									;
													
		UpdateDB ($sql_statement, $mysql_err_msg);
		
		//	sync to other mods ?
		if($div_id != 3 AND $div_id < 10)
		{
			// get all pages that have this div active and set to sync
			$sql_statement = 'SELECT page_id FROM page_info'
			
													.' WHERE sync_'.$div_name.' = "on"'
													//.' AND '.$div_name.'_active = "on"'	// all divs should be modifide even when deactivated
													;
			
			$result =  ReadDB ($sql_statement, $mysql_err_msg);
			
			while ($mod_sync_info = mysql_fetch_array($result))
			{
				//	Check User/page permisions
				if (UserPageAccess($mod_sync_info['page_id']) > 1)
				{
					// get the ID of the mod that needs updating
					$sql_statement = 'SELECT mod_id FROM modules'

													.' WHERE sync_id = '.$sync_id
													.' AND page_id = '.$mod_sync_info['page_id']
													;
													
					$mods_2_sync = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

					//	update all Mods that are set to sync
					$sql_statement = 'UPDATE '.$mod_db_table.' SET'
							
												.' text = "'.$text.'"'
												.' WHERE mod_id = "'.$mods_2_sync['mod_id'].'"'	
												;
													
				//$err_log_str .= "\n\n".$sql_statement	;														
						UpdateDB ($sql_statement, $mysql_err_msg);
					
				}
										
			}	
		
		}
			
	}
	

	
	
	
	
 
 
//	===================================================================================
//	Structure Update Error reporting the same as file: "cms_update_mod_settings.php"	
//	===================================================================================	
 

	$_SESSION['update_success_msg'] = "Update Succesfull";

	///////	Do UNDO SQLs not functioning atm	//////////////////////////////////////
	//$_SESSION['db_rollback_num']++;

/* 	
	/////////	ERROR LOOGING - FOR testing	////////////////////////////////////////////////////
		$err_log_str = 
		 "\n".'page_id - '.$page_id
		."\n".'mod_id - '.$mod_id 	 
		."\n".'mod_db_table - '.$mod_db_table
		."\n".'mod_type_id - '.$mod_type_id 
		."\n".'div_id - '.$div_id 
		."\n".'position - '.$position 
		."\n".'sync_id - '.$sync_id
		."\n".'active - '.$active
		."\n".'UserPageAccess($page_id) = '.UserPageAccess($page_id)
		."\n".'$_SESSION[access] = '.$_SESSION['access']
		;


		$fopen = @fopen('error_log.txt','w');
		$str .= 'testing output from: cms_update_wysiwyg.php......'."\n".$err_log_str."\n";
		fputs($fopen, $str);
		fclose($fopen);	
	/////////////////////////////////////////////////////////////////////////////////////////////
 */

 
}


//	===================================================================================
//	Structure Update Error reporting the same as file: "cms_update_mod_settings.php"	
//	===================================================================================	
	
else
{
	
	$_SESSION['update_error_msg'] .= '- Insufficient Privileges to Modify Data \n';
	
} 
 	
	
?>