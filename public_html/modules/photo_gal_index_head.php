<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	//$jquery_file = 'jquery.galleria-1.2.9.js';
	$jquery_file = 'galleria-1.4.2.min.js';
	
	//	Get Photo Gallary data from db	-------------
	$mysql_err_msg = 'Photo Gallery Head Code Information not available';	
	$sql_statement = 'SELECT * FROM mod_photo_gal_settings, modules'
	
																.' WHERE mod_photo_gal_settings.mod_id = modules.mod_id'
																.' AND modules.page_id = "'.$page_id.'"'
																//.' AND mod_photo_gal_settings.active = "on"'
																.' AND modules.active = "on"'
																.' AND (modules.theme_specific = 0 OR modules.theme_specific = '.$site_theme_id.')'
																;
//echo $sql_statement;	
	$galhead_type_result = ReadDB ($sql_statement, $mysql_err_msg);
	
	while ($gallery_info = mysql_fetch_array ($galhead_type_result))
	{
		switch ($gallery_info['gallery_type'])
		{	
			//	add include files for mods using the 'Galleria' jQuery Photo Gallery
			case 'jquery_galleria':
			
				//	check if this file has already been included first
				if(!in_array($jquery_file, $_SESSION['included_files']))
				{
					$mod_head_code .= TAB_1.'<script type="text/javascript" src="/includes/javascript/'.$jquery_file.'" ></script>'."\n";
					$_SESSION['included_files'][] = $jquery_file;
				}
			
			break;
			
			//	add script code for mods using the 'Cycle' jQuery Photo Gallery
			case 'jquery_cycle':
									
				$this_code = TAB_1.'<script type="text/javascript">'."\n\n";

					$this_code .= TAB_2.'$(\'#GalleryCycle_'.$gallery_info['mod_id'].'\').cycle({'. "\n";
						
						$this_code .= TAB_3.'fx:     \''.$gallery_info['trans_fx'].'\','."\n";
						$this_code .= TAB_3.'speed:   '.$gallery_info['trans_speed'].','."\n";
						$this_code .= TAB_3.'timeout: '.$gallery_info['timeout'].','."\n";
						$this_code .= TAB_3.'pause:   '.$gallery_info['pause_on_hover']."\n\n";

					$this_code .= TAB_1.'});'."\n";

				$this_code .= TAB_1.'</script>'."\n";
				
				//	check if this code has already been included first
				if(!in_array($this_code, $_SESSION['included_head_code']))
				{	
					$mod_head_code .= $this_code;
					
					$_SESSION['included_head_code'][] = $this_code;
				}
				
			break;			
		}
		
	}

 ?>