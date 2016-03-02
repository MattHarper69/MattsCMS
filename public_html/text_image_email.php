<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

//----Get Common code to all pages
	require_once ('includes/common.php');
	
	//	cant send a # in query str so substiute with a ^
	$style = str_replace ("^", "#", $_REQUEST['style']);
	
	if (isset($_REQUEST['prof_id']))
	{
		$user_id = $_REQUEST['prof_id'];
		$mod_type = 'profiles';
	}
	elseif (isset($_REQUEST['con_id']))
	{
		$user_id = $_REQUEST['con_id'];
		$mod_type = 'contact';	
	}

	CreateText2Image ($user_id, $mod_type, $style);

	exit();
	
	function CreateText2Image ($user_id, $mod_type, $style)
	{
		//	read from db	----------
		$mysql_err_msg = 'This text unavailable';

		switch($mod_type)
		{
		
			case 'profiles':
				$sql_statement = 'SELECT email FROM mod_profiles WHERE profile_id = "'.$user_id.'" ';
			break;
			
			case 'contact':
				$sql_statement = 'SELECT email FROM mod_contact_items WHERE contact_id = "'.$user_id.'" ';
			break;			
			
		}
		

		$text_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
		

			//	===============		obtain from reading CSS file	===================================
		
			if (  USER_SELECTS_THEME == 'on' AND isset($_SESSION['user_theme_set']) )
			{ $site_theme_id = $_SESSION['user_theme_set']; }
			else { $site_theme_id = SITE_THEME_ID; }
			
			//	fetch Theme Dir
			$sql_statement = 'SELECT dir_name FROM themes WHERE theme_id = "'.$site_theme_id.'"';
			$mysql_err_msg = 'unable to the Sites Theme data';

			$theme_data = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	
			$site_theme_dir = $theme_data['dir_name'];	

			if (file_exists('_themes/'.$site_theme_dir.'/_css.css'))
			{			
				$properties_array =  GetCSSselectorStyles ('_themes/'.$site_theme_dir.'/_css.css', $style );
				$bg_colour = str_ireplace( '#', '', $properties_array['background-color']);
				$font_colour = str_ireplace( '#', '', $properties_array['color']);

				//	process Font Family Value
				if ($properties_array['font-family'] == '') {$font_family_str = "verdana";}
				else {$font_family_str = $properties_array['font-family'];}
				
				//	process Font Size Value
				$font_size_types = array('px','pt', 'em');
				$font_size_str = str_ireplace( $font_size_types, '', $properties_array['font-size']);
				if ($font_size_str == '') {$font_size_str = '14';}
				
				//	process Font Weight Value
				if ($properties_array['font-weight'] == 'normal') {$font_weight_str = '';}			
				if ($properties_array['font-weight'] == 'bold' OR $properties_array['font-weight'] == 'bolder') {$font_weight_str = '_bold';} 
				
				//	process Font Style Value
				if ($properties_array['font-style'] == 'normal') {$font_style_str = '';}			
				if ($properties_array['font-style'] == 'italic' OR $properties_array['font-style'] == 'oblique') {$font_style_str = '_italic';} 
			
			}			
			
			$font_filename = 'gd_fonts/'.$font_family_str.'_'.$font_size_str.$font_weight_str.$font_style_str.'.gdf';



		if (!file_exists ($font_filename) OR !file_exists('_themes/'.$site_theme_dir.'/_css.css'))
		{
			//	failsafe if Font File or CSS file don't exist -- use defaults
			$bg_colour = '';
			$font_colour = 000000;
			$font_filename = 'gd_fonts/verdana_14.gdf';			
		}
	
		if ($text_info['email'] == "" OR $text_info['email'] == NULL ) {}
		else
		{	
			$text = $text_info['email'];
			$font_size = imageloadfont($font_filename);
			
			$width = imagefontwidth($font_size) * strlen($text) + 1;
			$height = imagefontheight($font_size) + 1; 
				
			$image = @ImageCreate($width, $height); 
			 		
			$bg_red = hexdec(substr($bg_colour, 0, 2));
			$bg_green = hexdec(substr($bg_colour, 2, 2));
			$bg_blue = hexdec(substr($bg_colour, 4, 2));
				
			$font_red = hexdec(substr($font_colour, 0, 2));
			$font_green = hexdec(substr($font_colour, 2, 2));
			$font_blue = hexdec(substr($font_colour, 4, 2));
				
			$set_bg_colour = ImageColorAllocate($image, $bg_red, $bg_green, $bg_blue); 
			$set_font_colour = ImageColorAllocate($image, $font_red, $font_green, $font_blue); 

			if ( $bg_colour == "" OR $bg_colour == NULL OR $bg_colour == "transparent" )
			{ImageColorTransparent($image, $set_bg_colour);}

			ImageFill($image, 0, 0, $set_bg_colour); 
					
			$x_pos = 1;
			$y_pos = 0;	
				
			ImageString($image, $font_size, $x_pos, $y_pos, $text, $set_font_colour);

			header("Content-Type: image/jpeg"); 

			ImagePng($image); 
			    
			ImageDestroy($image); 
				
		}
		
	}	
	
?>