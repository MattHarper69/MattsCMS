<?php

$source_id = 2;

$div_to_clone = array(0,1,2,4,5);

$start_id = 19;

$last_id = 19;

$page_title = "*";	// use '*' to copy original titles


//--------Set key to start include files
	define( 'SITE_KEY', 1 );

//----Get Common code to all pages
	require_once ('includes/common.php');

	require_once ('includes/access.php');
	


	
//	HTML goes below...........

// 	Start output buffering
	ob_start();
	
//---------Get Head:
	include_once ('includes/head.php');  
	echo "\n";
	
//---------Get The common TOP of page code:
	require_once ('page_start.php'); 

	
	$mysql_err_msg = 'something fucked-up';	
	
	//	get Page info from orginal page
	$sql_statement = 'SELECT * FROM  page_info WHERE page_id = '.$source_id;
												
	$source_page_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	
	

		



	for ($i = $start_id; $i < $last_id + 1; $i++)
	{
	
		echo '<h1>Writing new page info for Page id = '.$i.'.......</h1>'. "\n";		
		
		if ( $page_title == '*' ) { $new_name = $source_page_info['page_name'].'_'.$i;}
		else {$new_name = $page_title.'_'.$i;}	
	
		//	Write new Page Info
		$sql_statement = 'INSERT INTO page_info SET' 
			
												.'  page_id = '.$i
												.', page_name = "'.$new_name.'"'
												.', menu_text = "'.$new_name.'"'
												.', url_alias = "'.$new_name .'"'	
												.', active = "'.$source_page_info['active'].'"'	
												.', requires_login = "'.$source_page_info['requires_login'].'"' 	
												.', access_code = "'.$source_page_info['access_code'].'"'	
												.', seq = '.$i * 10	
												.', parent_id = "'.$source_page_info['parent_id'].'"'	
												.', file_name = "'.$new_name .'"'
												.', send_p_query = "'.$source_page_info['send_p_query'].'"' 	
												.', icon_image = "'.$source_page_info['icon_image'].'"'
												.', in_menu_top = "'.$source_page_info['in_menu_top'].'"'	
												.', in_menu_foot = "'.$source_page_info['in_menu_foot'].'"'	
												.', in_menu_side  = "'.$source_page_info['in_menu_side'].'"'
												.', menu_top_active = "'.$source_page_info['menu_top_active'].'"'	
												.', menu_foot_active = "'.$source_page_info['menu_foot_active'].'"'
												.', menu_side_active = "'.$source_page_info['menu_side_active'].'"'	
												.', bread_crumb_active = "'.$source_page_info['bread_crumb_active'].'"'
												.', popup_text = "'.$source_page_info['popup_text'].'"'
												.', banner_active = "'.$source_page_info['banner_active'].'"'	
												.', footer_active = "'.$source_page_info['footer_active'].'"'	
												.', side_1_active = "'.$source_page_info['side_1_active'].'"'	
												.', side_2_active = "'.$source_page_info['side_2_active'].'"'	
												.', cms_comments = "'.$source_page_info['cms_comments'].'"'
												;
//echo '<p>'.$sql_statement.'</p>';													
		ReadDB ($sql_statement, $mysql_err_msg);

		$last_page_id = mysql_insert_id();
		
			

			
		//	get Mod info from orginal page
		foreach ($div_to_clone as $div_id)
		{
			$sql_statement = 'SELECT * FROM  modules WHERE page_id = '.$source_id.' AND div_id = '.$div_id;
//echo '<p>'.$sql_statement.'</p>';
			
			$something1 = ReadDB ($sql_statement, $mysql_err_msg);
			while ($source_mod_info = mysql_fetch_array ($something1))
			{
			
				echo '<h2>Writing new Mod into modules for Page id = '.$last_page_id.'.......</h2>'. "\n";
				
				//	Write new Mod info
				$sql_statement = 'INSERT INTO modules SET'
				
														.'  page_id = '.$last_page_id
														.', mod_type_id = '.$source_mod_info['mod_type_id']
														.', div_id = '.$div_id
														.', position = '.$source_mod_info['position']
														.', active = "'.$source_mod_info['active'].'"'
														.', sync_id = "'.$source_mod_info['sync_id'].'"'
														.', class_name = "'.$source_mod_info['class_name'].'"'
														;
//echo '<p>'.$sql_statement.'</p>';				
				ReadDB ($sql_statement, $mysql_err_msg);
				
				$last_mod_id = mysql_insert_id();
				

				//	get db table name info for each mod
				$sql_statement = 'SELECT mod_db_table FROM _module_types WHERE mod_type_id = '.$source_mod_info['mod_type_id'];
//echo '<p>'.$sql_statement.'</p>';				
				$mod_type_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
				
				if ($mod_type_info[0] != '')
				{
					
					switch ($mod_type_info[0])
					{				
						case 'mod_text_1':
						case 'mod_text_2_image':
						case 'mod_website_portfolio':
						case 'mod_image_1':
						case 'mod_heading':
						case 'mod_google_map':
						case 'mod_insert_html':
						case 'mod_flash_player':
						case 'mod_links':
						case 'mod_add2cart_button':
						
					
							//	Write new Entry into the Module
							$sql_statement = 'INSERT INTO '.$mod_type_info[0].' SET mod_id = '.$last_mod_id;
//echo '<p>'.$sql_statement.'</p>';
							ReadDB ($sql_statement, $mysql_err_msg);
							
							//	get table field names
							$sql_statement = 'SHOW COLUMNS FROM '.$mod_type_info[0];								
//echo '<p>'.$sql_statement.'</p>';									
							
							$something2 = ReadDB ($sql_statement, $mysql_err_msg);
							while ($field_names_info = mysql_fetch_array ($something2))
							{
//echo '<li>'.$field_names_info['Field'].'</li>';														
								
								if ( $field_names_info['Field'] != 'mod_id' )
								{

									//	get specific mod info
									$sql_statement = 'SELECT '.$field_names_info['Field'].' FROM '.$mod_type_info[0]

																		.' WHERE mod_id = '.$source_mod_info['mod_id'];
																		
																		
									$source_row_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

									echo '<h4>Writing Specific Module Info: '.$field_names_info['Field'].' = "'.$source_row_info[0].'"</h4>'. "\n";
									
									//	Write new Specific Mod info
									$sql_statement = 'UPDATE '.$mod_type_info[0]
									
															.' SET '.$field_names_info['Field'].' = "'.$source_row_info[0].'"'
		
															.' WHERE mod_id = "'.$last_mod_id.'"'
															;

//echo '<p>'.$sql_statement.'</p>';							
								
									ReadDB ($sql_statement, $mysql_err_msg);
									
								}							
									
							}							

							
						break;
						
						//	List Items
						case 'mod_list_items':

							//	get specific mod info
							$sql_statement = 'SELECT * FROM '.$mod_type_info[0]

																		.' WHERE mod_id = '.$source_mod_info['mod_id'];
																		
							$something3 = ReadDB ($sql_statement, $mysql_err_msg);
							while ($ist_items_info = mysql_fetch_array ($something3))					
							{
								//	Write new Specific Mod info
								$sql_statement = 'INSERT INTO '.$mod_type_info[0].' SET'

															.'  mod_id = "'.$last_mod_id.'"'
															.', seq = "'.$ist_items_info['seq'].'"'
															.', style = "'.$ist_items_info['style'].'"'
															.', text = "'.$ist_items_info['text'].'"'
															.', active = "'.$ist_items_info['active'].'"'
															;

//echo '<p>'.$sql_statement.'</p>';							
								
								ReadDB ($sql_statement, $mysql_err_msg);								
							
							}
						
						
						break;
						
					} 
						
				}
							
								
			}
			
		}
	
		echo '<hr/>'. "\n";	
		//==============================================================================================================
	}	

	//	Update the nav .htaccess file
	//require ('admin/update_htaccess_file.php');
	
//---------Get The common BOTTOM of page code:
	require_once ('page_end.php');	
	echo "\n";
	
// 	Now flush the output buffer
	ob_end_flush();	
	

	
?>