<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$div_name = 'PhotoGallery_'.$mod_id;
	
	echo "\n";
	echo TAB_7.'<!--	START Photo Gallery code 	-->'."\n";
	echo "\n";
 	
	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{
	
		$can_not_clone = 0;
		$edit_enabled = 1;	
		$mod_locked = 2;			
		
		$hover_class = ' HoverShow';		

		//	Display / Hide In-Active Mods
		include ('CMS/cms_inactive_mod_display.php');

		//	Show Div Mod Button
		echo TAB_7.'<div id="EditDivModDisplay_'.$mod_id.'" class="EditDivModDisplay"' 
					.' title="Click to Edit this &quot;Photo Gallery&quot; Module">'."\n";
			
			echo TAB_8.'<p style="background-color:#ffffff;color:#aa44aa; cursor: pointer;"'
			.' onClick="javascript:selectMod2Edit(35, '.$mod_id.',\''.$div_name.'\' ,0, 2);">'
			.'[ &quot;Photo Gallery&quot; Module (click to edit) ]<p>'."\n";
			
		echo TAB_7.'</div>'."\n";
		
		
		//	CSS layout Dispay (for CMS)
		$CSS_layout = '&lt;div id="<strong>'.$div_name.'</strong>" class="<strong>PhotoGallery"</strong>" &gt;'
						.'<span class="FinePrint"> (MODULE CONTENT HERE) </span>&lt;/div&gt;';		
		
		//	Do mod editing Toolbar
		include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
		
		//	Do Mod Config Panel
		include ('CMS/cms_panels/cms_panel_mod_config.php');
		
	}

	else
	{$hover_class = '';}

	echo TAB_7.'<div id="'.$div_name.'" class="PhotoGallery">'. "\n";	
	
	$mysql_err_msg = 'Photo Gallery Information not available';	
	$sql_statement = 'SELECT * FROM mod_photo_gal_settings WHERE mod_id = "'.$mod_id.'"';
	
	$gal_type_result = ReadDB ($sql_statement, $mysql_err_msg);
	
	while ($gallery_info = mysql_fetch_array ($gal_type_result))
	{
		
		if ($gallery_info['display_name'] == 'on' AND $gallery_info['gallery_name'] != '')
		{
			echo TAB_8.'<h3 id="GalleryName_'.$mod_id.'" class="GalleryName" >'.$gallery_info['gallery_name'].'</h3>'. "\n";
		}		
		
		//	Do Category Menu
		if (isset($_GET['gal_cat'])) {$selected_cat_id = $_GET['gal_cat'];}

		//	Category info
		$mysql_err_msg = 'Photo Gallery Category Information not available';	
		$sql_statement = 'SELECT DISTINCT mod_photo_gal_cats.gal_cat_id, mod_photo_gal_cats.cat_name'
															.' FROM mod_photo_gal_mod_cat_asign, mod_photo_gal_cats'
																
															.' WHERE mod_photo_gal_mod_cat_asign.mod_id = "'.$mod_id.'"'
															.' AND mod_photo_gal_cats.gal_cat_id = mod_photo_gal_mod_cat_asign.gal_cat_id'
															.' AND mod_photo_gal_cats.active = "on"'
															.' ORDER BY mod_photo_gal_cats.seq'
															;

												
		$all_cats_info_result = ReadDB ($sql_statement, $mysql_err_msg);
		$num_cats = mysql_num_rows($all_cats_info_result);
		
		if (!isset($_GET['gal_cat']))
		{
			//	Get the first category to show if one is not already selected by user
			$all_cats = mysql_fetch_array ($all_cats_info_result);
			$selected_cat_id = $all_cats['gal_cat_id'];			

		}

		if ($num_cats > 1)
		{
			echo TAB_8.'<div id="GalleryCatNav_'.$mod_id.'" class="GalleryCatNav" >'. "\n";
				
			if 
			(
					$gallery_info['cat_menu_type'] == 'link'
				OR 	($gallery_info['cat_menu_type'] == 'selectIfMobile' AND !CheckUserAgent ('mobile'))	
			)	
			{

				echo TAB_9.'<ul>'. "\n";
				
				$all_cats_info_result = ReadDB ($sql_statement, $mysql_err_msg);
				$i = 1;
				while ($all_cats = mysql_fetch_array ($all_cats_info_result))
				{
					
					if ($all_cats['gal_cat_id'] == $selected_cat_id)
					{ $class = 'class="GalCatNavSelected"'; }
					else { $class = ''; }
					
					echo TAB_10.'<li '.$class.'>'."\n";
						//if ($i != 1) { echo ' | ';}
						echo TAB_11.'<a href="'.$_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;gal_cat='.$all_cats['gal_cat_id'].'"'."\n";
						echo TAB_11.' title="View the category: '.$all_cats['cat_name'].'" >'.$all_cats['cat_name'].'</a>'. "\n";
						
						
					echo TAB_10.'</li>'. "\n";
					
					$i++;

				}
				
				echo TAB_9.'</ul>'. "\n";
			
			}
				
			elseif 
			(
					$gallery_info['cat_menu_type'] == 'select' 
				OR 	($gallery_info['cat_menu_type'] == 'selectIfMobile' AND CheckUserAgent ('mobile'))
			)
			{
				
				echo TAB_9.'<label class="GalleryCatMenuLabel" for="GalleryCatMenu">Select a Category: </label>'."\n";
				
				echo TAB_9.'<select id="GalleryCatMenu" onChange="self.location=this.options[this.selectedIndex].value">'."\n";
				
				$all_cats_info_result = ReadDB ($sql_statement, $mysql_err_msg);
				
				while ($all_cats = mysql_fetch_array ($all_cats_info_result))
				{
					
					if ($all_cats['gal_cat_id'] == $selected_cat_id)
					{ 
						$selected = ' selected="selected"';
						$current_cat_name = $all_cats['cat_name'];
					}
					else { $selected = '';}
					
					echo TAB_10.'<option class="GalleryCatMenuSelect" id="GalleryCatMenuCatId_'.$all_cats['gal_cat_id'].'" '."\n";
					echo TAB_11.$selected.' value="'.$_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;gal_cat='.$all_cats['gal_cat_id'].'">'."\n";

					echo TAB_11.$all_cats['cat_name']."\n";	

														
					echo TAB_10.'</option>'."\n";

				}						
				
				echo TAB_9.'</select>'."\n";

			}
																	
			echo TAB_8.'</div>'. "\n";	
			
		}
		
		switch ($gallery_info['gallery_type'])
		{	
			case 'jquery_galleria':
		
				//	Get Photo info
				$mysql_err_msg = 'Photo Gallery Image Information not available';					
				$sql_statement = 'SELECT * FROM mod_photo_gal_pics WHERE cat_id = "'.$selected_cat_id.'"'
															.' AND mod_photo_gal_pics.active = "on"'
															.' ORDER BY seq'
															;	
															
				$gal_photo_result = ReadDB ($sql_statement, $mysql_err_msg);
				
				
				echo TAB_8.'<div id="Galleria_'.$mod_id.'" class="Galleria">'. "\n";
			

				while ($gal_photo_info = mysql_fetch_array ($gal_photo_result))
				{

					//	set image alt text
					if ($gal_photo_info['photo_text'] != '') 
					{ $photo_text = $gal_photo_info['photo_text']; }
					
					else
					{ $photo_text = 'Photo Galery image'; }
					
					$file_path = '_images_gallery/'.$gal_photo_info['file_name'];
					if (file_exists($file_path))
					{
						echo TAB_9.'<img src="/'.$file_path.'"'
							.' alt="'.$photo_text.'"'. "\n";
						echo TAB_9.' title="'.$photo_text.'" />'. "\n";					
					}
			
				}			

				if 
				(
						($gallery_info['show_gal_thumbs'] == 'notIfMobile' AND CheckUserAgent ('mobile')) 
					OR $gallery_info['show_gal_thumbs'] == '')				
				{
					$no_thums_code = TAB_10 . '$("#Galleria_'.$mod_id.'").galleria({thumbnails: "false"}); '. "\n";
				}
				else
				{
					$no_thums_code = '';
				}
				echo TAB_9.
				'<script type="text/javascript">
					
					' . $no_thums_code . '					
										
			Galleria.loadTheme("includes/javascript/galleria/themes/classic/galleria.classic.min.js");
			Galleria.run("#Galleria_'.$mod_id.'");
					
		</script>'."\n";
				
				echo TAB_8.'</div>'. "\n";	
	
			break;
			
			
			//================ jQuery CYCLE BROWSER  =====================================
			case 'jquery_cycle':
							
				echo TAB_8.'<div id="GalleryCycle_'.$mod_id.'" class="GalleryCycle">'. "\n";
				
					//	Get Photo info
					$mysql_err_msg = 'Photo Gallery Image Information not available';															
					$sql_statement = 'SELECT * FROM mod_photo_gal_pics WHERE cat_id = "'.$selected_cat_id.'"'
																.' AND mod_photo_gal_pics.active = "on"'
																.' ORDER BY seq'
																;
					 
					$gal_photo_result = ReadDB ($sql_statement, $mysql_err_msg);
					
					while ($gal_photo_info = mysql_fetch_array ($gal_photo_result))
					{	
						
						//	set image alt text
						if ($gal_photo_info['photo_text'] != '') 
						{ $photo_text = $gal_photo_info['photo_text']; }
						
						else
						{ $photo_text = 'Photo Galery image'; }						
											
						echo TAB_10.'<img src="/_images_gallery/'.$gal_photo_info['file_name'].'"'
									.' alt="'.$photo_text.'"'. "\n";
						echo TAB_10.' title="'.$photo_text.'" />'. "\n";
									
					}

					
				echo TAB_8.'</div>'. "\n";

			break;			
			
		}
		
	}

	echo TAB_7.'</div>'. "\n";		
 
 	echo "\n";
	echo TAB_7.'<!--	END Photo Gallery code 	-->'."\n";
	echo "\n";
 	
 ?>