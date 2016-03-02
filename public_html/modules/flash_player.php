<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	$div_name = 'FlashPlayer_'.$mod_id;
	
	
	//	Get Forms settings - read from db	----------
	$mysql_err_msg = 'Flash Movie Infomation unavailable';	
	$sql_statement = 'SELECT * FROM mod_flash_player WHERE mod_id = "'.$mod_id.'" ';

	$flash_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
	
	if ($flash_info['file_name'] != "" AND $flash_info['file_name'] != NULL)
	{ 
	
		echo TAB_7.'<!--	START Flashplayer code 	-->'."\n";
		echo "\n";
		
		$div_name = 'FlashPlayer_'.$mod_id;
		
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{
			$edit_enabled = 1;
			$mod_locked = 2;
			$can_not_clone = 0;			
			
			//	Display / Hide In-Active Mods
			include ('CMS/cms_inactive_mod_display.php');
						
			$hover_class = ' HoverShow Draggable';
			$on_click = ' onClick="javascript:selectMod2Edit(18, '.$mod_id.', \''.$div_name.'\',0,2);"';
			
		}

		else
		{
			$hover_class = '';
			$on_click = '';
		}
		
		echo TAB_7.'<div class="FlashPlayer'.$hover_class.'" id="'.$div_name.'"'.$on_click.'>'."\n\n";
		
			echo TAB_8.'<object class="FlashPlayer" type="application/x-shockwave-flash"  '."\n";
				echo TAB_9.'data="/_media/'.$flash_info['file_name'].'?'.$flash_info['file_query_str'].'" '
							.'width="'.$flash_info['width'].'" height="'.$flash_info['height'].'"'.$on_click.'>'."\n";
							 
				echo TAB_9.'<param name="movie" value="/_media/'.$flash_info['file_name'].'?'.$flash_info['file_query_str'].'" />'."\n";
				echo TAB_9.'<param name="quality" value="'.$flash_info['quality'].'"/>'."\n";
				
				for ($i=1; $i<5; $i++)
				{
					if ($flash_info['param_'.$i] != "" AND $flash_info['param_'.$i] != NULL ) 
					{	
						echo TAB_9.'<param name="'.$flash_info['param_'.$i].'" value="'.$flash_info['param_value_'.$i].'" />'."\n";
					}
				}
				
			if ($flash_info['defult_bg_img'] != "" AND $flash_info['defult_bg_img'] != NULL)
			{
				echo TAB_9.'<a href="http://get.adobe.com/flashplayer/">'."\n";
					echo TAB_10.'<img src="/_images_user/'.$flash_info['defult_bg_img'].'" class="NoBorder" '."\n"; 
					echo TAB_10.'alt="You need to have a &quot;Flash Player&quot; '
								.'installed on your computer to properly view this part of the website" '."\n";
					echo TAB_10.'title="Click here to download a free a &quot;Flash Player&quot; '
								.'to properly view this part of the website" />'."\n";
				echo TAB_9.'</a>'."\n";		
			}
			
			else
			{

				echo TAB_9.'<a href="http://get.adobe.com/flashplayer/">'."\n";
					echo TAB_10.'<img src="/images_misc/noflash.png" class="NoBorder" height="'.$flash_info['height'].'"'."\n"; 
					echo TAB_10.'alt="You need to have a &quot;Flash Player&quot; '
								.'installed on your computer to properly view this part of the website" '."\n";
					echo TAB_10.'title="Click here to download a free a &quot;Flash Player&quot; '
								.'to properly view this part of the website" />'."\n";
				echo TAB_9.'</a>'."\n";
			}	
			
			echo TAB_8.'</object>'."\n\n";
		echo TAB_7.'</div>'."\n";
	
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{
			
			//	CSS layout Dispay (for CMS)
			$CSS_layout = '&lt;div id="<strong>'.$div_name.'</strong>" class="<strong>FlashPlayer</strong>" &gt;'
						.'<span class="FinePrint"> (MODULE CONTENT HERE) </span>&lt;/div&gt;';			
			
			//	Do mod editing Toolbar
			include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
			
			//	Do Mod Config Panel
			include ('CMS/cms_panels/cms_panel_mod_config.php');		
		}
	
		echo "\n";		
		echo TAB_7.'<!--	END Flashplayer code	-->'."\n";
		
	}
	
?>