<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	// define Transition types
	$transition_fx_array = array
	(
		 'Fade' => 'fade'
		,'Scroll Right' => 'scrollRight'	
		,'Zoom' => 'zoom'
		,'Shuffle' => 'shuffle'
		,'Turn Down' => 'turnDown'
		,'Turn Up' => 'turnUp'
		,'Curtain (horizontal)' => 'curtainX'
		,'Curtain (vertical)' => 'curtainY'
	);
	

	$this_page = $_SERVER['PHP_SELF'] . '?e='.$mod_id . '&tab='.$_GET['tab'];
	$return_url = $this_page . '&mod_id='.$mod_id;


			echo TAB_4.'<script type="text/javascript">
					
				$(document).ready( function()
				{								
					
					//	Hide / Show Photo Trans. settings and display correct description
					$("input[name=\'gallery_type\']").click(function() 
					{
						if($("#GalleryTypeGalleria").is(":checked"))
						{
							$("#GalleryType_1").show();
							$("#GalleryType_2").hide();
							$("#GalTransSetsDiv").hide();
						}
						else
						{
							$("#GalleryType_2").show();
							$("#GalleryType_1").hide();
							$("#GalTransSetsDiv").show();
						}									
					});						
					
					//	Hide / Show Category Menu Type
					$("#GalleryMenuDisplay").click(function() 
					{
						if($(this).is(":checked"))
						{
							$("#GalCatMenuTypeDiv").show();
						}
						else
						{
							$("#GalCatMenuTypeDiv").hide();
						}									
					});				

	
					//	show correct Resize Image mode description
					$("#ImageResizeMode").change(function()
					{
						var mode = $(this).val();
						$(".ResizeModeDescs").hide();
						$("#ResizeModeDesc_" + mode).show();
						
						$(".ResizeDim").hide();
						if ( mode == 1 || mode == 2 || mode == 3 || mode ==5 )
						{
							$("#ResizeWidth").show();
						}
						if ( mode == 1 || mode == 2 || mode == 4 || mode ==5 )
						{
							$("#ResizeHeight").show();
						}

					});
					
				});	
			</script>'."\n";		
	
	
	
	
	//---------Update error msg:
	include_once ('cms_includes/cms_msg_update.php');	
	
	$update_url = '/CMS/cms_update/cms_update_photo_gal_Settings.php';
	echo TAB_2.'<form action="'.$update_url.'" name="update" method="post" enctype="multipart/form-data" >'."\n";

		echo TAB_3.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
			echo TAB_4.'<legend class="Centered" >'."\n";
				
				//-------------UPDATE BUTTON------------------------------------
				echo TAB_5.'<input type="submit" name="settings_update_all" value="Update ALL displayed Information" />'."\n";			
			echo TAB_4.'</legend>'."\n";

			//	RESET button ======================================================				
			echo TAB_4.'<a href="'.$this_page.'" title="Reload this page to Reset all Category data" >' ."\n";
				echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
			echo TAB_4.'</a>'. "\n";	
			//	===================================================================	
			//	Active
			if ($mod_type_info['active'] == 'on') { $checked = ' checked="checked"'; }
			else { $checked = '';}
			
			echo TAB_4.'<fieldset class="AdminForm3" title="Uncheck this box to hide this Photo Gallery Module" >'."\n";
				echo TAB_5.'<input type="checkbox" name="active" '.$checked.' /><span> : Set this Photo Gallery Module as ACTIVE</span>'."\n";
			echo TAB_4.'</fieldset>'."\n";					
			
			//	edit Name 		
			echo TAB_4.'<fieldset class="AdminForm3">'."\n";
				echo TAB_5.'Photo Gallery Name: <input type="text" name="gallery_name" value="'.$gal_settings['gallery_name'].'"' . "\n";
				echo TAB_5.' size="40" title="Add or Edit the Photo Gallery&#39;s display Name here" /> '."\n";
				
			//	Display Name
			if ($gal_settings['display_name'] == 'on') { $checked = ' checked="checked"'; }
			else { $checked = '';}
			
				echo TAB_5.' : <input type="checkbox" name="display_name" '.$checked.' /><span> : Display as heading</span>'."\n";
			
			echo TAB_4.'</fieldset>'."\n";

			//	Gallery Type
			echo TAB_4.'<fieldset class="AdminForm3" style="clear: left">'."\n";
				echo TAB_5.'<h4>Gallery Type:</h4>'."\n";
				
				$style1 = '';
				$style2 = '';
				$style3 = '';
				
				//	Galleria
				if ($gal_settings['gallery_type'] == 'jquery_galleria') 
				{ 
					$checked = ' checked="checked"'; 
					$style2 = ' style="display: none"';
					$style3 = ' style="display: none"';
				}
				else 
				{ $checked = '';}
				
				echo TAB_5.'<p title="A user controlled &quot;slide show&quot; Photo Gallery">' . "\n";
					echo TAB_6.'<input type="radio" id="GalleryTypeGalleria" name="gallery_type" value="jquery_galleria"'.$checked.'/>' . "\n";
					echo TAB_6.'<span>&raquo; Photo Album (with User controls)</span>' . "\n";
				echo TAB_5.'</p>' . "\n";
				
				//	Cycle
				if ($gal_settings['gallery_type'] == 'jquery_cycle') 
				{ 
					$checked = ' checked="checked"'; 
					$style1 = ' style="display: none"';
				}
				else { $checked = '';}
				
				echo TAB_5.'<p title="Auto changing Image display for backgrounds or all size windows">' . "\n";
					echo TAB_6.'<input type="radio" id="GalleryTypeCycle" name="gallery_type" value="jquery_cycle"'.$checked.'/>' . "\n";
					echo TAB_6.'<span>&raquo; Auto Slide Show</span>' . "\n";
				echo TAB_5.'</p>' . "\n";
				
				//	Gallery Type Desc.
				echo TAB_5.'<fieldset class="AdminForm3" style="clear: left">'."\n";						
					echo TAB_6.'<span id="GalleryType_1"'.$style1.'>A user controlled &quot;slide show&quot; Photo Gallery</span>' . "\n";
					echo TAB_6.'<span id="GalleryType_2"'.$style2.'>Auto changing Image display for backgrounds<br/>or all size windows</span>' 
					. "\n";
				echo TAB_5.'</fieldset>'."\n";
				
			echo TAB_4.'</fieldset>'."\n";
		
			// Category Menu Display? and Type
			echo TAB_4.'<fieldset class="AdminForm3">'."\n";
				echo TAB_5.'<h4>Category Menu:</h4>'."\n";
				
				if ($gal_settings['cat_menu_type'] != 'none') 
				{ 
					$checked = ' checked="checked"'; 
					$style = '';				
				}
				else 
				{ 
					$checked = '';
					$style = ' style="display: none; clear:both;"';					
				}
				
				echo TAB_5.'<p><input type="checkbox" id="GalleryMenuDisplay" name="cat_menu_display" '.$checked.'/>' . "\n";
				echo TAB_5.'<span> : Display Category Menu</span></p>' . "\n";
								
				echo TAB_5.'<fieldset class="AdminForm3" id="GalCatMenuTypeDiv"'.$style.'>'."\n";	

					//	Menu Type
					echo TAB_6.'<p>Menu Type:</p>'."\n";
				
					//	link
					if ($gal_settings['cat_menu_type'] == 'link') { $checked = ' checked="checked"'; }
					else { $checked = '';}
					
					echo TAB_6.'<p><input type="radio" id="GalleryMenuTypeLink" name="cat_menu_type" value="link"'.$checked.'/>' . "\n";
					echo TAB_6.'<span>&raquo; Links or Buttons style Menu</span></p>' . "\n";
					
					// select
					if ($gal_settings['cat_menu_type'] == 'select') { $checked = ' checked="checked"'; }
					else { $checked = '';}
					
					echo TAB_6.'<p><input type="radio" id="GalleryMenuTypeSelect" name="cat_menu_type" value="select"'.$checked.'/>' . "\n";
					echo TAB_6.'<span>&raquo; &quot;Drop-down&quot; style Menu</span></p>' . "\n";	

					//	select If Mobile
					if ($gal_settings['cat_menu_type'] == 'selectIfMobile') { $checked = ' checked="checked"'; }
					else { $checked = '';}
					
					echo TAB_6.'<p><input type="radio" id="GalleryMenuTypesMobile" name="cat_menu_type" value="selectIfMobile"'.$checked.'/>'. "\n";
					echo TAB_6.'<span>&raquo; &quot;Drop-down&quot; if Mobile Device (default)</span></p>' . "\n";	
					
			
				echo TAB_5.'</fieldset>'."\n";			
				
			echo TAB_4.'</fieldset>'."\n";
			
			//	Transition Settings
			echo TAB_4.'<fieldset class="AdminForm3" id="GalTransSetsDiv"'.$style3.'>'."\n";
				echo TAB_5.'<h4>Photo transition settings:</h4>'."\n";
				
				//	Transition Effect
				echo TAB_5.'<p><span>Transition Effect: </span>'. "\n";
					echo TAB_6.'<select name="trans_fx"  >' . "\n";
									
					foreach ($transition_fx_array as $transition_fx_name => $transition_fx)
					{
						if ($transition_fx == $gal_settings['trans_fx'])	
						{$selected = 'selected="selected"';}
						else {$selected = '';}
						
						echo TAB_7.'<option value="'.$transition_fx.'" '.$selected.'>'.$transition_fx_name.'</option>' . "\n";
					}
					
					echo TAB_6.'</select>' . "\n";
				echo TAB_5.'</p>' . "\n";				
				
				//	timeout
				echo TAB_5.'<p><span>Display Time for each Photo: </span>'. "\n";
					echo TAB_6.'<input type="text" name="timeout" value="'.$gal_settings['timeout'].'" style="margin-left: 2.1em;"'
						. "\n";
						echo TAB_7.' size="5" title="Adjust the time for each photo to display here" /> (ms) '."\n";				
				echo TAB_5.'</p>' . "\n";					
				
				//	Transition speed
				echo TAB_5.'<p><span>Transition Time between Photos: </span>'. "\n";
					echo TAB_6.'<input type="text" name="trans_speed" value="'.$gal_settings['trans_speed'].'"' . "\n";
						echo TAB_7.' size="5" title="Adjust the time it takes to transition between photos here" /> (ms) '."\n";				
				echo TAB_5.'</p>' . "\n";				
				
				//	pause_on_hover
				if ($gal_settings['pause_on_hover'] == '1') { $checked = ' checked="checked"'; }
				else { $checked = '';}				
	
				echo TAB_5.'<p title="Selecting this will cause the transistions to pause when the mouse or cursor is placed over the image">'."\n";
					echo TAB_6.'<span>Pause when curser hovers-over: </span>'. "\n";
					echo TAB_6.'<input type="checkbox" name="pause_on_hover"'.$checked.'/>' . "\n";		
				echo TAB_5.'</p>' . "\n";

				echo TAB_4.'<p class="Small" >( 1000 ms = 1 second )</p>'."\n";
				
			echo TAB_4.'</fieldset>'."\n";	
			
			//	Image Upload Settings
			echo TAB_4.'<fieldset class="AdminForm3" style="clear:both;">'."\n";
			
				echo TAB_5.'<h4>Upload Image settings:</h4>'."\n";
				
				echo TAB_5.'<fieldset class="AdminForm3">'."\n";	
				
					//	Image Resize Mode				
					echo TAB_6.'Image upload <strong>Resize Mode</strong>: <select name="resize_img_mode" id="ImageResizeMode">' . "\n";
					
					$mysql_err_msg = 'Resize image mode information unavailable';	
					$sql_statement = 'SELECT mode_id, mode_name, mode_desc FROM _cms_resize_img_modes WHERE active="on" ORDER BY seq';			
					$resize_mode_result = ReadDB ($sql_statement, $mysql_err_msg);
					
					$resize_mode_array = array();
					while ($resize_mode_info = mysql_fetch_array($resize_mode_result))				
					{
						//	create array for display below:
						$resize_mode_array[$resize_mode_info['mode_id']] = $resize_mode_info['mode_desc'];
						
						if ($resize_mode_info['mode_id'] == $gal_settings['resize_img_mode'])
						{
							$selected = 'selected="selected"';
						}
						else {$selected = '';}
						
						echo TAB_7.'<option '.$selected. ' value="'.$resize_mode_info['mode_id'].'">'.$resize_mode_info['mode_name'].'</option>'
						. "\n";
					}

					echo TAB_6.'</select>' . "\n";
					
				echo TAB_5.'</fieldset>'."\n";
									
				echo TAB_5.'<fieldset class="AdminForm3 ResizeDim" id="ResizeWidth" >'."\n";
					echo TAB_6.'Resize <strong>Width:</strong> &nbsp;<input type="text" name="resize_img_max_width" value="'
					.$gal_settings['resize_img_max_width'].'"' . "\n";
					echo TAB_6.' size="5" maxlength="5" title="Enter the set Width for resizing of Uploaded images" /> px' . "\n";
				echo TAB_5.'</fieldset>'."\n";

				echo TAB_5.'<fieldset class="AdminForm3 ResizeDim" id="ResizeHeight">' . "\n";
					echo TAB_6.'Resize <strong>Height:</strong> <input type="text" name="resize_img_max_height" value="'
					.$gal_settings['resize_img_max_height'].'"' . "\n";
					echo TAB_6.' size="5" maxlength="5" title="Enter the set Height for resizing of Uploaded images" /> px' . "\n";
				echo TAB_5.'</fieldset>'."\n";	
				
				echo TAB_5.'<fieldset class="AdminForm3" style="float: left; clear: both;">'."\n";
					echo TAB_6.'<p>This will: '."\n";
					foreach ($resize_mode_array as $mode_id => $mode_desc)				
					{						
						if ($mode_id == $gal_settings['resize_img_mode'])
						{$style = '';}
						else {$style = ' style="display: none"';}
						
						echo TAB_7.'<span class="ResizeModeDescs" id="ResizeModeDesc_'.$mode_id.'"' . $style . '>'. "\n";
							echo TAB_8.$mode_desc. "\n";
						echo TAB_7.'</span>'. "\n";
					}
					echo TAB_6.'</p>'."\n";
				
				echo TAB_5.'</fieldset>'."\n";

			echo TAB_4.'</fieldset>'."\n";

			
		echo TAB_3.'</fieldset>'."\n";

		echo TAB_3.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";
		echo TAB_3.'<input type="hidden" name="mod_id" value="'.$mod_id.'" />'."\n";
		echo TAB_3.'<input type="hidden" name="mod_type_id" value="'.$mod_type_info['mod_type_id'].'" />'."\n";
		
	echo TAB_2.'</form>' ."\n";	
	
?>