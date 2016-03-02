<?php 

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$new_win_width = 800;
	$new_win_height = 600;

	//	read from db	----------
	$mysql_err_msg = 'This Page text unavailable';	
	$sql_statement = 'SELECT * FROM mod_text_2_image WHERE mod_id = "'.$mod_id.'" '
													.' AND active="on"';

	$num_results = mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg));
	$text_image_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));		

	if ($num_results > 0)
	{		
		if ($text_image_info['id_or_class'] == "id")
		{
			//	cant send a # in query str so substiute with a ^		
			$style = '^Text2Image_'.$mod_id;
		}
		else {$style = 'Text2Image';}
		
		echo TAB_7."\n";
		echo TAB_7.'<!--  Replace text with Image  --> '. "\n";
		
		$div_name = 'Text2Image_'.$mod_id;
					
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{
			$edit_html_enabled = 0;
			$mod_locked = 2;	
			
			//	Display / Hide In-Active Mods
			include ('CMS/cms_inactive_mod_display.php');
						
			$hover_class = ' HoverShow Draggable';
			$on_click = ' onClick="javascript:selectMod2Edit(8, '.$mod_id.', \''.$div_name.'\',0,2);"';
			
		}

		else
		{
			$hover_class = '';
			$on_click = '';
		}
		
		echo TAB_7.'<div class="Text2Image'.$hover_class.'" id="'.$div_name.'"'.$on_click.'>'."\n";
		
			if ($text_image_info['link_type'] == "email" )
			{
				$href = 'javascript:openWindow(\'modules/email_form_popup.php?p='.$page_id
						.'&amp;emailto='.$text_image_info['contact_id'].'\','.$new_win_width.','.$new_win_height.');';
			}
			if ( $text_image_info['new_window'] == '' OR $text_image_info['new_window'] == NULL ) 
			{ $target = ''; }		
			else { $target = 'rel="external"'; }
		
			if ($text_image_info['link_type'] == "link" )
			{ $href = $text_image_info['link_href'] ;}
			
			if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE)
			{ $href = 'javascript:selectMod2Edit(8, '.$mod_id.', \''.$div_name.'\',0,2);';}
				
			if ($text_image_info['link_type'] != "" OR 	$text_image_info['link_type'] != NULL )
			{ echo TAB_8.'<a class="Text2Image" href="'.$href.'" '.$target.' title="'.$text_image_info['title_text'].'" >'."\n"; }
			
				echo TAB_9.'<img class="Text2Image" src="/text_image.php?mod_id='.$mod_id.'&amp;style='.$style.'&amp;p=x" '
							.'alt="'.$text_image_info['alt_text'].'" />'."\n";
							
			if ($text_image_info['link_type'] != "" OR 	$text_image_info['link_type'] != NULL )
			{ echo TAB_8.'</a>'."\n"; }
				
		echo TAB_7.'</div>'."\n";
		
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{
			//	Do mod editing Toolbar
			include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
			
			//	Do Mod Config Panel
			include ('CMS/cms_panels/cms_panel_mod_config.php');		
		}			
	}
				
?>