<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

//=================================================================================================================================================	



// Detect USER-AGENTS

	function CheckUserAgent ( $type ) 
	{
        if ($_SERVER['HTTP_USER_AGENT'])	
		{
			$user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
			if ( $type == 'bot' ) 
			{
				// matches popular bots
				if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
						return true;
						// watchmouse|pingdom\.com are "uptime services"
				}
			} 
			elseif ( $type == 'browser' ) 
			{
				// matches core browser types
				if ( preg_match ( "/mozilla\/|opera\//", $user_agent )) 
				{
					return true;
				}
			} 
			elseif ( $type == 'mobile' ) 
			{
				// matches popular mobile devices that have small screens and/or touch inputs
				// mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
				// detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
				if ( preg_match ( 
									 "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|"			
									."palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|"
									."webos\/|samsung|sonyericsson|^sie-|nintendo/"
									, $user_agent )) 
				{
					// these are the most common
					return true;
				} 
				elseif ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) 
				{
					// these are less common, and might not be worth checking
					return true;
				}
			}
			
		}

		
        return false;
		
	}

 

	
	//	db read
	//function ReadDB ($sql_statement, $mysql_err_msg, $halt_on_error)
	function ReadDB ($sql_statement, $mysql_err_msg)
	{
		global $connection;
		
		//$halt_on_error = 1;	//for testing
		$halt_on_error = 0;	
		if($result = @mysql_query($sql_statement, $connection))
		{
			return $result;	
		}
		else
		{
			WriteToErrorLog( $mysql_err_msg, mysql_error(), 'MySQL - NORMAL', $sql_statement);
			if ($halt_on_error == 1)
			{
				echo '<h3>An ERROR occured - This section of the Website is Temporary OUT OF ORDER' ."\n";
				echo '<br/>Please check back later - Sorry for the inconvenience.</h3>' ."\n";
				echo '<h2><a href="/">You can Click Here to Return to the Home Page</a></h2>' ."\n";
				//echo '<p><strong>ERROR CODE:</strong> '.mysql_error().'</p>' ."\n";
				exit();
			}
			
		}

	}
	
	//	db Update
	function UpdateDB ($sql_statement, $mysql_err_msg)
	{
		global $connection;

		if($result = @mysql_query($sql_statement, $connection))
		{
			// Update last DB update time
			if (isset($_SESSION['user_id']))
			{
				$mysql_err_msg = "updating Update db timestamp";
				$sql_statement = 'UPDATE 1_user_logins SET' 
														.' last_db_update = "'.date("Y-m-d H:i:s").'"'
														.' WHERE user_id = "'.$_SESSION['user_id'].'"'		
														;
				$result2 = @mysql_query($sql_statement, $connection);			
			}

			
			return $result;	
		}
		else
		{
			WriteToErrorLog( $mysql_err_msg, mysql_error(), 'MySQL - UPDATE', $sql_statement);		
		}
	}
	
//=================================================================================================================================================

	function WriteToErrorLog($mysql_err_msg, $mysql_error, $error_type, $sql_statement)
	{
		$log_filename = '/'.$_SERVER['DOCUMENT_ROOT'].'/../_errors/errors-sql.log';
		
		
		$err_log_str = 
		
		 "\n".'DATE: '.date("D - d M Y - H:i:s T")
		."\n"		 
		."\n".'ERROR: '.$error_type
		."\n".'IP: '.$_SERVER['REMOTE_ADDR']
		."\n".'FILE: '.$_SERVER['SCRIPT_NAME']
		."\n".'ERROR MSG:'.$mysql_err_msg
		."\n".'MySQL error code:'.mysql_error()
		."\n"
		."\n".'Query: '.$sql_statement
		."\n\n".'=========================================================================' . "\n\n"
		;
		

		$fopen = @fopen($log_filename,'a');
		fputs($fopen, $err_log_str);
		fclose($fopen);			
	}
	
//=================================================================================================================================================	
/* 
	//	code HTML data from database
	function DBencode ($data)
	{
		//RETURN nl2br(Space2nbsp(Chr2Code ($data)));	
		RETURN nl2br(Space2nbsp($data));
		//RETURN Space2nbsp($data);
	}

//==============================================================================================================================================
 */		
	function Chr2Code ($data)
	{
/* 	
		$chr_list = array 	(
								//'&'
								 '\''

							);
		$code_list = array 	(
								 //'&amp;'
								 '&#039;'

							);
		

		$CodedString = str_replace( $chr_list, $code_list,  $data );		

	
		RETURN $CodedString;
		 */	
		
		RETURN htmlentities ($data, ENT_NOQUOTES);
	}
	
//==============================================================================================================================================

	//	preserve SPACES
	function Space2nbsp	($data)
	{
		RETURN str_replace ( '  ', ' &nbsp;', $data );
	}

//==============================================================================================================================================
		
	//	Hi-lite TEXT if sent from serach page
	function HiliteText ($text )
	{

		if (isset($_REQUEST['search_str']) AND $_REQUEST['remove_hilight'] != "yes")
		{
			$text = str_ireplace ( $_REQUEST['search_str'], 
			'<span class="HighlightLiteBlue">'.$_REQUEST['search_str'] . '</span>', $text );
		}
		
		RETURN $text;		
	}

//=================================================================================================================================================


	function NumberSuffix($number)
	{	 
		// Validate and translate our input
		if ( is_numeric($number))
		{	 
			// Get the last two digits (only once)
			$n = $number % 100;	 
		} 
		else 
		{
			// If the last two characters are numbers
			if ( preg_match( '/[0-9]?[0-9]$/', $number, $matches ))
			{	 
				// Return the last one or two digits
				$n = array_pop($matches);
			} 
			else 
			{ 
				// Return the string, we can add a suffix to it
				return $number;
			}
		}
	 
		// Skip the switch for as many numbers as possible.
		if ( $n > 3 AND $n < 21 )
			return $number . 'th';
	 
		// Determine the suffix for numbers ending in 1, 2 or 3, otherwise add a 'th'
		switch ( $n % 10 )
		{
			case '1': return $number . 'st';
			case '2': return $number . 'nd';
			case '3': return $number . 'rd';
			default:  return $number . 'th';
		}
	}	
	
//=================================================================================================================================================		
	//	Kill all the logged-in Session info
	function KillLoginInfo ()
	{
		
		if (isset($_SESSION['user_id']))
		{			
			//--------Record Last Log-OUT to db:		
			$mysql_err_msg = 'Recording User Account Last Log-in';	
			$sql_statement = 'UPDATE 1_user_logins SET' 
														.' last_logout = "'.date("Y-m-d H:i:s").'"'
														.' WHERE user_id = "'.$_SESSION['user_id'].'"'		
														;

			ReadDB ($sql_statement, $mysql_err_msg);	//	'Use ReadDB' instead of 'UpdateDB' to stop db update recording when use logs in or out	
		}

						
		unset ($_SESSION['CMS_authorized']);
		unset ($_SESSION['CMS_mode']);
		unset($_SESSION['authorized']);
		unset($_SESSION['load_admin']);		
		unset($_SESSION['user_id']);
		unset($_SESSION['access']);
		unset($_SESSION['expire_time']);	
	
		//	If you log out, kill log-in key so you cant back-up and refresh browser to login again
		unset($_SESSION['login_key']);		
	}

//=================================================================================================================================================

	//	get Module information from db based on what div
	function GetModInfo ($page_id, $div_id, $site_theme_id)
	{
		
		//	Load mobile css if mobile device
		if (CheckUserAgent ('mobile'))
		{
			$device_filter = ' AND screen_only != "on"';
		}
		else
		{
			$device_filter = '';		
		}		
		
		
		global $all_mod_types;
		
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE)
		{
			$active_filter =  '';
		}
		else
		{
			$active_filter =  ' AND modules.active = "on"';
		}
		
		//	 Module info
		$mysql_err_msg = 'Plug-in Module Information not available';	
		$sql_statement = 'SELECT'
										.' modules.mod_id'
										.' ,modules.mod_type_id'
										.',modules.page_id'
										.',modules.div_id'								 	 		 	 	
										.',modules.position'
										.',modules.active'
										.',modules.theme_specific'
										.',modules.sync_id'
										.',modules.class_name'										
										.',_module_types.mod_name'
										.',_module_types.file_name'	
										.',_module_types.cms_edit_filename'
										.',_module_types.mod_db_table'			
										
													
										.' FROM modules, _module_types '
										.' WHERE modules.page_id = "'.$page_id.'"'
										.' AND modules.div_id = "'.$div_id.'"'
										.' AND modules.mod_type_id = _module_types.mod_type_id'											
										.' AND (modules.theme_specific = 0 OR modules.theme_specific = '.$site_theme_id.')'
										
										.  $active_filter
										.  $device_filter
										.' ORDER BY position'
										;	
//echo $sql_statement;
		$last_position = mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg));
		$mod_result = ReadDB ($sql_statement, $mysql_err_msg);	
			
		while ($mod_info = mysql_fetch_array ($mod_result))
		{								
			if ($mod_info['file_name'] != "" AND $mod_info['file_name'] != NULL AND file_exists('modules/'.$mod_info['file_name']))
			{ 				
				if($mod_info['class_name'] != '' AND $mod_info['class_name'] != NULL)
				{
					$add_class = $mod_info['class_name'];
				}
				else { $add_class = '';}
							
				//if ($mod_info['mod_type_id'] != 19 AND $mod_info['mod_type_id'] != 20)
				//{ 
					echo TAB_6.'<div id="Mod_'.$div_id.'_'.$mod_info['mod_id'].'"'
								.' class="Mod_'.$div_id.'_'.$mod_info['position'].' '.$add_class.'">'." \n\n"; 
				//}
									
					$mod_id = $mod_info['mod_id'];
					include ($mod_info['file_name']);
				
				//if ($mod_info['mod_type_id'] != 19 AND $mod_info['mod_type_id'] != 20)
				//{ 
					echo "\n".TAB_6.'</div>'." \n\n"; 
				//}
					
			}
				
		}

	}
	
//==============================================================================================================================================
	//	get array of link path IDs
	function GetLinkPath ($table_name, $page_id)
	{
	
		//$daddy_links = array();
		$opened_link_ids = array();
		$current_id = $page_id;

		for ( $i = 0; $i < (NUM_NAV_LAYERS - 1); $i++ )
		{
			//	get selected link info
			$mysql_err_msg = 'The Menu for this page is unavailable';
			$sql_statement = 'SELECT parent_id, page_id FROM '.$table_name.' WHERE active = "on" '
																			.'AND page_id = "'.$current_id.'"';
				

			$nav_row=mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			
			//$daddy_links[] = $nav_row['parent_id'];
			$opened_link_ids[] = $nav_row['parent_id'];
			
			$current_id = $nav_row['parent_id'];
			
		}
		
		return $opened_link_ids;
		
	}	
	
//==============================================================================================================================================	

	
	function ShortenText($text, $shorten_by) 
	{        			
		$text = $text." ";
		$text = substr($text,0,$shorten_by);
		$text = substr($text,0,strrpos($text,' '));
		$text = $text."...";        
		return $text;
	}	
	
	function ShortenTextPre($text, $shorten_by) 
	{        		
		//$text = " ".$text;	//	dont need ??
			
		$text = substr($text, strlen($text) - $shorten_by, $shorten_by);
		$text = substr($text, strpos($text,' ') );
		
		$text = "...".$text;		
		
		return $text;
	}
	
	
	function ShortenTextPost($text, $shorten_by) 
	{        		
		$text = $text." ";	
		$text = substr($text,0,$shorten_by);
		$text = substr($text,0,strrpos($text,' '));
		$text = $text."..."; 
	
		return $text;
	}

	
	function ShortenTextAroundKeyWord($text, $keyword, $shorten_by, $wrap) 
	{        		
		$exploded = explode($keyword, $text);
			
		$text_before = $text." ";

		$text = $text."..."; 
		$short_text = wordwrap($short_text, $wrap); 	
		return $short_text;
	}		

//==============================================================================================================================================

	function FileUploadErrorMessage($error_code) 
	{
	    switch ($error_code) 
		{ 
	        case UPLOAD_ERR_INI_SIZE: 
	            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; 
	        case UPLOAD_ERR_FORM_SIZE: 
	            return 'The uploaded file exceeds the Maximum File Size limit specified'; 
	        case UPLOAD_ERR_PARTIAL: 
	            return 'The uploaded file was only partially uploaded'; 
	        case UPLOAD_ERR_NO_FILE: 
	            return 'No file was uploaded'; 
	        case UPLOAD_ERR_NO_TMP_DIR: 
	            return 'Missing a temporary folder'; 
	        case UPLOAD_ERR_CANT_WRITE: 
	            return 'Failed to write file to disk'; 
	        case UPLOAD_ERR_EXTENSION: 
	            return 'File upload stopped by extension'; 
	        default: 
            return 'Unknown upload error'; 
		} 
	} 

//===============================================================================================================================================

	//	Read a CSS file
	function ReadCSSFile ($css_filename)
	{
		$lines = file($css_filename);
		
		foreach ($lines as $line_num => $line) 
		{ $css_styles .= trim($line); }
 
		$token = strtok($css_styles, "{}");

		$sarray = array();

		$count = 0;

		while ($token !== false) 
		{
			$sarray[$count] = $token;
			$count++; 
			$token = strtok("{}");
		}

		$size = count($sarray);
  
		$selectors = array();
		$sstyles = array();
  
		$npos = 0;
		$sstl = 0;
  
		for($i = 0; $i < $size; $i++)
		{
			if ($i % 2 == 0) 
			{ 
				$selectors[$npos] = $sarray[$i];
				$npos++;    			
			}
			
			else
			{
				$sstyles[$sstl] = $sarray[$i];
				$sstl++;
			} 
			
		}

		RETURN $selectors;
		//RETURN $sstyles;
		
	}

//===============================================================================================================================================	

	function GetCSSselectorStyles ($css_filename, $selector )
	{		
		//	get all of CSS file and put into array -  line by line ( and trim white space)
		$lines = file($css_filename);
	
		$css_styles = '';
		foreach ($lines as $line_num => $line) 
		{ $css_styles .= trim($line); }

		//	extract specified SELECTOR
		$selector_str = strtok( strstr($css_styles, $selector), '}' );

		//	split and remove everything left of the "{"
		$selectors_array = explode('{', $selector_str );
		$selector_str = $selectors_array[1];

		//	split style into properties
		$selectors_array = explode(';', $selector_str );
		
		// create property / value pair array
		$property_array = array();
		$properties_array = array();
		foreach ($selectors_array as $property_value_pair)
		{			
			$property_value_pair = str_ireplace(' ', '', $property_value_pair);
			$property_array = explode (':', $property_value_pair);

			if ($property_array[0] != '' AND $property_array[1] != '')
			{
				$properties_array [$property_array[0]] = $property_array[1];			
			}
		}
//print_r($properties_array);	
		
		RETURN $properties_array;		
	}

//=================================================================================================================================================

	function GetSiteThemeDir()
	{
		if (  USER_SELECTS_THEME == 'on' AND isset($_SESSION['user_theme_set']) )
		{ $site_theme_id = $_SESSION['user_theme_set']; }
		else { $site_theme_id = SITE_THEME_ID; }
		
		//	fetch Theme Dir
		$sql_statement = 'SELECT dir_name FROM themes WHERE theme_id = "'.$site_theme_id.'"';
		$mysql_err_msg = 'unable to the Sites Theme data';

		$theme_data = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	
		$site_theme_dir = $theme_data['dir_name'];	
				
		return $site_theme_dir;

	}
	

//=================================================================================================================================================	

	function ValidateCMS (  $data_id, $value, $exisiting_value )
	{

		//	 Get Validation info
		$mysql_err_msg = 'Retrieving validation parameters';	
		$sql_statement = 'SELECT * FROM _cms_validation WHERE data_id = "'.$data_id.'"';	
									
		$validate_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

		//	check if value is required but empty
		if ( $validate_info['required'] == 'on' AND $value == '')
		{
			$_SESSION['update_error_msg'] .= '- a required entry: ( '.$validate_info['data_name'].' )was missing \n';
			return FALSE; 
			
		}

		
		//	must be UNIQUE
		if ( $validate_info['is_unique'] == 'on' AND $validate_info['field_name'] != '' AND $validate_info['table_name'] != '' )
		{
			//	 Get Validation info
			$mysql_err_msg = 'Retrieving existing data for UNIQUE validation comparison';	
			$sql_statement = 'SELECT '.$validate_info['field_name'].' FROM '.$validate_info['table_name']
			
												.' WHERE '.$validate_info['field_name'].' = "'.$value.'"';	
								
			$match_found = mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg));

			if( $match_found > 0 AND $value != $exisiting_value )
			{
				$_SESSION['update_error_msg'] .= '- '.$validate_info['data_name'].': ( '.$value.' ) already in USE \n';
				return FALSE;		
			
			}
		}
		
		//	Minimum Characters
		if ( $validate_info['char_min'] != 0 AND $validate_info['char_min'] != '')
		{
			if ( strlen($value) < $validate_info['char_min'] )
			{
				$_SESSION['update_error_msg'] .= '- '.$validate_info['data_name'].': ( '.$value.' )'
													.' must be a least '.$validate_info['char_min'].' characters long \n';
				return FALSE;				
			}
		}

		//	Maximum Characters
		if ( $validate_info['char_max'] != 0 AND $validate_info['char_max'] != '')
		{
			if ( strlen($value) > $validate_info['char_max'] )
			{
				$_SESSION['update_error_msg'] .= '- '.$validate_info['data_name'].': ( '.$value.' )'
													.' must not be more than '.$validate_info['char_max'].' characters long \n';
				return FALSE;				
			}
		}

	
		//	Check Character Type for ALPANUMERIC
		if ( $validate_info['char_type'] == 'alphanum')
		{
			if ( ctype_alnum(str_replace(' ', '',$value)) == FALSE )
			{
				$_SESSION['update_error_msg'] .= '- '.$validate_info['data_name'].': ( '.$value.' )'
													.' must contain only letters and numbers with No spaces \n';
				return FALSE;				
			}
		}	

		//	Check Character Type for NUMERIC
		if ( $validate_info['char_type'] == 'numeric')
		{
			if ( !is_numeric(str_replace(' ', '',$value)) )
			{
				$_SESSION['update_error_msg'] .= '- '.$validate_info['data_name'].': ( '.$value.' )'
													.' must contain only numbers \n';
				return FALSE;				
			}
		}

		//	Check VALID EMAIL
		if ( $validate_info['char_type'] == 'email')
		{	
			if ( !preg_match(EMAIL_REG_EXP_STRING, $value) )
			{
				$_SESSION['update_error_msg'] .= '- '.$validate_info['data_name'].': ( '.$value.' )'
													.' is NOT a valid email address \n';
				return FALSE;				
			}
		}
		
	
		//	check for banned characters
		if ( $validate_info['char_exclude'] != '')
		{			
			$exc_chr_array = str_split($value);
			 
			foreach ($exc_chr_array as $bad_chr) 
			{
				if (strpos($value, $bad_chr)  )
				{
					$_SESSION['update_error_msg'] .= '- '.$validate_info['data_name'].': ( '.$value.' )'
														.' can not contain any of follow the charaters: '.$validate_info['char_exclude'].' \n';
					return FALSE;				
				}		
			}
		}

		//	NO VALIDATION failures
		Return TRUE;
		
	}
//=================================================================================================================================================	

	function CMS_DisplayModPos ($height, $pos, $last_position, $parrent_div_pos, $parrent_div_last_pos)
	{	

		if ($parrent_div_pos == '')
		{
			// For	Normal Mods
			$mod_ht = $height / $last_position;
			$this_mod_ht = $mod_ht; 
			if ($mod_ht < 6) {$this_mod_ht = 6;}			
					
			for ($num_div = 1; $num_div < ($last_position + 1); $num_div++)
			{			

				if ($pos == $num_div)
				{				
					echo TAB_10.'<div class="CMS_MiniPageLayout_ModDiv_Selected" style="height: '.$this_mod_ht.'px;"></div>' ."\n";
				}
				else
				{				
					echo TAB_10.'<div class="CMS_MiniPageLayout_ModDiv" style="height: '.($mod_ht - 1).'px;"></div>' ."\n";
				}		
			}
	
		}
		else
		{
			//For mods in Div Mods
	


			$mod_ht = $height / $parrent_div_last_pos;
			$this_mod_ht = $mod_ht; 
			if ($mod_ht < 20) {$this_mod_ht = 20;}			
					
			for ($num_div = 1; $num_div < ($parrent_div_last_pos + 1); $num_div++)
			{			

				if ($parrent_div_pos == $num_div)
				{
					echo TAB_10.'<div class="CMS_MiniPageLayout_DivMod" style="height: '.$this_mod_ht.'px;">' ."\n";

						CMS_DisplayModPos ($this_mod_ht, $pos, $last_position, '', '');
					
					echo TAB_10.'</div>' ."\n";
				}
				else
				{				
					echo TAB_10.'<div class="CMS_MiniPageLayout_ModDiv" style="height: '.($mod_ht - 1).'px;"></div>' ."\n";
				}	



/* 	
			for ($num_div = 1; $num_div < ($last_position + 1); $num_div++)
			{			

				if ($pos == $num_div)
				{
					echo TAB_10.'<div class="CMS_MiniPageLayout_DivMod">' ."\n";
				}
				else
				{				
					echo TAB_10.'<div class="CMS_MiniPageLayout_ModDiv" style="height: '.($mod_ht - 1).'px;"></div>' ."\n";
				}

				if ($end_div_mod_pos == $num_div)
				{
					echo TAB_10.'</div>' ."\n";
				}				

				
			}
 */				
			
			}			
	
		}
		
	}


//=================================================================================================================================================	
	
	function ResizeImages( $source_file, $dest_file, $file_type, $mode, $set_width, $set_height, $reporting  ) 
	{

		/* 
			Resizing modes:
				0 - do not resize
				1 - specify new width and height and stretch and/or squash to fit
				2 - specify new width and height and crop width or height to fit
				3 - adjust in proportion according set width
				4 - adjust in proportion according set height
				5 - adjust in proportion according set length for width/height which ever is the greatest (sould set both $set_height and $set_height 
					as this lenght for this best results)
			
			Numbering options ($num_pad):
				0 - no numbering (this is over-ridden if an new file name is specified to create unique filenames)
				
				1 - 9:
					1 - no padding ie: "4"
					2 - 2 digit numbering with "0" padding ie: "04"
					3 - 3 digit numbering with "0" padding ie: "004" or "034"
					etc	
		*/

		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		ini_set('memory_limit', '256M');  //	to handle large memory useage
			
		// load image and get image size
		switch ($file_type)
		{
			case ('jpg'):
				$img = ImageCreateFromJPEG( $source_file );
			break;
			
			case ('png'):
				$img = ImageCreateFromPNG( $source_file );
			break;

			case ('gif'):
				$img = ImageCreateFromGIF( $source_file );
			break;				
		}
			
		$width = imagesx( $img );
		$height = imagesy( $img );

		
		switch ($mode)
		{
			
			//	just stretch or squash where needed
			case 1:
				$src_x = 0;
				$src_y = 0;
				$src_width = $width;
				$src_height = $height;
				$new_width = $set_width;
				$new_height = $set_height;
			break;			

			//	Crop the width or height as needed to fit new dimensions
			case 2:

				$ratio_x = $set_width / $width;
				$ratio_y = $set_height / $height;

				if ($ratio_x < $ratio_y )
				{
					$src_y = 0;			
					$src_height = $height;				
										
					$src_width = $set_width / $set_height * $height;
					$src_x = round(($width - $src_width) / 2);					
				}
				
				elseif ( $ratio_x > $ratio_y  )
				{
					$src_x = 0;			
					$src_width = $width;				
									
					$src_height = $set_height / $set_width * $width;
					$src_y = round(($height - $src_height) / 2);								
				}
				
				//	image has been re-sized in proportion - no cropping necessary
				else
				{	
					$src_x = 0;
					$src_y = 0;	
					$src_width = $width;		
					$src_height = $height;							
				}

				$new_width = $set_width;
				$new_height = $set_height;						
								
								
			break;		 
			 
			//	adjust in proportion according set width
			case 3:
				$src_x = 0;
				$src_y = 0;
				$src_width = $width;
				$src_height = $height;
				$new_height = round( $height * ( $set_width / $width ) );
				$new_width = $set_width;
			
			break;	
			
			//	adjust in proportion according set height
			case 4:
				$src_x = 0;
				$src_y = 0;
				$src_width = $width;
				$src_height = $height;
				$new_width = round( $width * ( $set_height / $height ) );	
				$new_height = $set_height;				
			break;	
			
			//	resize (in proportion)according to which ever is dimension is greater
			case 5:
				if ( $width - $height > 0)
				{
					$new_height = round( $height * ( $set_width / $width ) );
					$new_width = $set_width;					
				}

				else
				{
					$new_width = round( $width * ( $set_height / $height ) );
					$new_height = $set_height;					
				}
				
				$src_x = 0;
				$src_y = 0;
				$src_width = $width;
				$src_height = $height;					
				
			break;				
			
		}
			
		
		//	create a new tempopary image
		$tmp_img = imagecreatetruecolor( $new_width, $new_height );


		//	copy and resize old image into new image 
		imagecopyresampled( $tmp_img, $img, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_width, $src_height );

		
		//	save image into a file			
		switch ($file_type)
		{
			case ('jpg'):
				ImageJPEG( $tmp_img, $dest_file );
			break;
			
			case ('png'):
				ImageAlphaBlending($tmp_img, false);
				ImageSaveAlpha($tmp_img, true);

				ImagePNG( $tmp_img, $dest_file );
				
			break;

			case ('gif'):
				ImageGIF( $tmp_img, $dest_file );
			break;				
		}			
			

		//	Free up resources
		ImageDestroy($tmp_img);

		
		//	print statistics:
		if ($reporting)
		{
			echo "\t".'<div class="ResizeImageStatsDisplay" >'. "\n";
			
				echo "\t\t".'<h4>Original File: &#39;'.$source_file.'&#39;</h4>'. "\n";
				echo "\t\t".'<ul>'. "\n";
					echo "\t\t\t".'<li>New filename: '.$dest_file.'</li>'. "\n";
					echo "\t\t\t".'<li>original width = '.$width.'</li>'. "\n";
					echo "\t\t\t".'<li>original height = '.$height.'</li>'. "\n";		
					echo "\t\t\t".'<li>new width = '.$new_width.'</li>'. "\n";
					echo "\t\t\t".'<li>new height = '.$new_height.'</li>'. "\n";
					
					if ($src_x > 0) {$crop_msg = $src_x.'px from left and right';}
					elseif ($src_y > 0) {$crop_msg = $src_y.'px from top and bottom';}
					else {$crop_msg = '(none)';}
						
					echo "\t\t\t".'<li>Cropping: '.$crop_msg.'</li>'. "\n";
				echo "\t\t".'</ul>'. "\n";

				echo '<br/><hr/><br/>';

			echo "\t".'</div>'. "\n";			
		}

	}

//=================================================================================================================================================	
	
	function CloseColorBox() 
	{	
			echo TAB_1.'<script type="text/javascript" language="javascript">
						
					$(document).ready(function(){				
						parent.$.fn.colorbox.close();									
					});

				</script>'."\n";	

	}

//=================================================================================================================================================
	
	function AddCharsToStr ($length, $char)
	{
		$str = '';
		for($i = 0; $i < $length; $i++)
		{
			$str .= $char;
		}
		
		RETURN $str;
		
	}	
?>