<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );
	
//----Get Common code to all pages
	require_once ('includes/common.php');

//	Send a generated image to the browser 
	CreateCaptcha(); 
	exit(); 

function CreateCaptcha() 
{ 
    //	Fallback settings USED in an emergancy:
	$fallback_font = 'Arial_Black_36_bold';	
	$fallback_bg_colour = 'cccccc';	
	$fallback_font_colour = '5555ff';
	$fallback_num_chrs = 5;
	//-------------------------------
	
	unset ($_SESSION["captcha_code"]);
	
	$captcha_id = $_REQUEST['captcha_id'];
	
	//	read from db
	$mysql_err_msg = 'This captcha info unavailable';	
	$sql_statement = 'SELECT * FROM mod_captcha WHERE captcha_id = "'.$captcha_id.'"';	

	$captcha_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

	//	Fall back to Defaults set above if defaults not set in db
	if ( isset($captcha_info['default_bg_colour'])) { $default_bg_colour = $captcha_info['default_bg_colour']; }
	else { $default_bg_colour = $fallback_bg_colour; }

	if ( isset($captcha_info['default_font_colour'] )) { $default_font_colour = $captcha_info['default_font_colour']; }
	else { $default_font_colour = $fallback_font_colour; }
	
	   
	//	Get N# of CHRs
	if ($captcha_info['num_chrs'] != '' AND is_numeric($captcha_info['num_chrs']) ) 
	{ $num_chrs = $captcha_info['num_chrs']; }
	else { $num_chrs = $fallback_num_chrs; }	

	//	Get N# of lines
	$num_lines = $captcha_info['num_lines'];
	
	//	Get Font File
	if ($captcha_info['font_file'] != '' AND $captcha_info['font_file'] != NULL AND file_exists('gd_fonts/'.$captcha_info['font_file'].'.gdf')) 
	{ $font = $captcha_info['font_file']; }
	else { $font = $fallback_font; }
	
	//	Get CSS selector
	$css_selector = $captcha_info['css_selector'];
	
	
	// GET Font colour and background colours from CSS file
 
		//	===============		obtain from reading CSS file	===================================
		if (file_exists('_themes/'.GetSiteThemeDir().'/_css.css'))
		{					
			$properties_array =  GetCSSselectorStyles ('_themes/'.GetSiteThemeDir().'/_css.css', $css_selector);
			$bg_colour = str_ireplace( '#', '', $properties_array['background-color']);
			$font_colour = str_ireplace( '#', '', $properties_array['color']);
		}


		

	//	set default colours if no css match is found
	if (strlen($bg_colour) < 3)
	{ 
		$bg_colour = $default_bg_colour;	
		$line_colour = $default_line_colour;		
	}
			
	if (strlen($font_colour) < 3)
	{
		$font_colour = $default_font_colour;	
	}
	
	//	convert Colour Hec codes to 3 x 2 decimal values:
	$bg_red = hexdec(substr($bg_colour, 0, 2));
	$bg_green = hexdec(substr($bg_colour, 2, 2));
	$bg_blue = hexdec(substr($bg_colour, 4, 2));
				
	$font_red = hexdec(substr($font_colour, 0, 2));
	$font_green = hexdec(substr($font_colour, 2, 2));
	$font_blue = hexdec(substr($font_colour, 4, 2));
	

	//	make line colour the mid way between the font and background colour
	$line_red = (hexdec(substr($bg_colour, 0, 2)) + hexdec(substr($font_colour, 0, 2))) / 2;
	$line_green = (hexdec(substr($bg_colour, 0, 2)) + hexdec(substr($font_colour, 0, 2))) / 2;
	$line_blue = (hexdec(substr($bg_colour, 0, 2)) + hexdec(substr($font_colour, 0, 2))) / 2;	



	
	//	Get  CHRs for Code 
	$captcha_array = array('A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','U','W','X','Y','Z');
	
	$security_code = '';
	for ($i=0; $i < $num_chrs; $i++ )
	{
		$security_code .= $captcha_array[rand(1, count($captcha_array)) - 1];
	}
	//	set the font
	$font = imageloadfont('gd_fonts/'.$font.'.gdf');
	
    //	Set the session to store the security code
    $_SESSION["captcha_code"] = $security_code;

    //	Set the image width and height 
    $width = imagefontwidth ($font) * 11;
    $height = imagefontheight($font) * 1.3;  

    //	Create the image resource 
    $image = ImageCreate($width, $height);  

    //	Allocate Colours

    //$black = ImageColorAllocate($image, 0, 0, 0); 
    $set_bg_colour = ImageColorAllocate($image, $bg_red, $bg_green, $bg_blue);
	$set_chr_colour = ImageColorAllocate($image, $font_red, $font_green, $font_blue);	
    $set_line_colour = ImageColorAllocate($image, $line_red, $line_green, $line_blue); 
	
    //	Make the background:
	ImageFill($image, 0, 0, $set_bg_colour);
	  
	//	Add randomly generated string in to the image
	$x_pos = 0;
	for ($i = 0; $i < $num_chrs; $i++)
	{
		$x_pos = $x_pos + rand(imagefontwidth ($font),imagefontwidth ($font)*2);
		$y_pos = rand(2, ($height - imagefontheight($font) - 2));	
		$char = substr($security_code, $i, 1);
	    ImageString($image, $font, $x_pos, $y_pos, $char, $set_chr_colour); 
	}

     //	Throw in some lines to make it a little bit harder for any bots to break 
	 if ($num_lines > 1)
	 {
		//	 horizontal lines
		for ($i=0; $i < ($num_lines/2 ); $i++) 
		{
			$y_start = rand(5,$height-5);
			$y_stop = rand(5,$height-5);
			imageline($image, 0, $y_start, $width, $y_stop, $set_line_colour);
		}	
		
		//	 vertical lines
		for ($i=0; $i < ($num_lines/2 ); $i++)
		{
			$x_start = rand(10,$width-10);
			$x_stop = rand(10,$width-10);
			imageline($image, $x_start, 0, $x_stop, $height, $set_line_colour);
		} 
	 }
	 
    //	Tell the browser what kind of file is come in 
    header("Content-Type: image/jpeg"); 

    //	Output the newly created image in jpeg format 
    ImageJpeg($image); 
    
    //	Free up resources
    ImageDestroy($image); 
} 
?>