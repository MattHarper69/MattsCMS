<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//$mod_id = $_GET['e'];
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$mod_id . '&tab='.$_GET['tab'];
	$return_url = $this_page . '&mod_id='.$mod_id;
	
	if (isset($_POST['submit_delete_cat']))
	{
		require_once ('cms_update/cms_update_photo_gal_functions.php');
		DeleteCategory ($_POST['del_cat_id']);
		
		//	Re-Direct BACK
		header('location: '.$this_page); 
		exit();	
	}


	//---------Update error msg:
	include_once ('cms_includes/cms_msg_update.php');
	
	$update_url = '/CMS/cms_update/cms_update_photo_gal_Categories.php';
	echo TAB_2.'<form action="'.$update_url.'" name="update" method="post" enctype="multipart/form-data" >'."\n";
		
		echo TAB_3.'<fieldset class="AdminForm2">' ."\n";	
				
			$mysql_err_msg = 'Category information unavailable';
			$sql_statement = 'SELECT'
									
									.' mod_photo_gal_cats.gal_cat_id'
									.', cat_name'
									.', active'
									//.',cat_sync_id'
									
									.' FROM mod_photo_gal_cats, mod_photo_gal_mod_cat_asign'
									
									.' WHERE mod_photo_gal_mod_cat_asign.mod_id = '.$mod_id
									.' AND mod_photo_gal_mod_cat_asign.gal_cat_id = mod_photo_gal_cats.gal_cat_id'

									.' ORDER BY seq'
									;
					
			$result = ReadDB ($sql_statement, $mysql_err_msg);
				
			$num_cats = mysql_num_rows($result);
		
			if ($num_cats < 1)
			{
				echo TAB_5.'<span class="WarningMSG" >( There are no Categories created ) </span> ';
			}
			

						
			//	Add new Category:
			echo TAB_6.'<span class="Bold">Add a New Category: </span>' . "\n";
			echo TAB_6.' - Category Name: <input type="text" name="new_cat_name"' . "\n";
			echo TAB_6.' size="40" title="Enter Category Name here and click &quot;Add&quot; ot add a New Category" /> '."\n";			
			
			echo TAB_5.'<input type="submit" name="add_new_cat" value="Add" />' ."\n";				
				

		echo TAB_3.'</fieldset>' ."\n";		
		
	
	
	if ($num_cats > 0)
	{


		echo TAB_3.'<script type="text/javascript" language="javascript">
					
				$(document).ready(function(){
					
					//	Warning All selected for Deletion
					$("#CheckAllDeleteMaster:checkbox").click(function() {
						if($("#CheckAllDeleteMaster").is(":checked"))
						{
							alert("You have selected Delete ALL:"
								+ "\n - Clicking the Update Button will Delete ALL displayed Categories");
						}
							
					});
					
					//	Toggle Edit Cat Name Input Box
					$(".OpenEditNamePanel").click(	
						function() 
						{
							$(this).parent().hide();						
							$(this).parent().next().show(400);						


						}
					)	
					$(".CloseEditNamePanel").click(	
						function() 
						{
							$(this).parent().hide();
							$(this).parent().prev().show(400);
							var CatName = $(this).prev().val();
							//alert(CatName);
							//$(this).parent().prev("span.CatName").html(CatName);
							//$(this).parent().prev().child("span.CatName").html(CatName);
						}
					)	
					
										
				});							

			</script>'."\n";	
		
		echo TAB_3.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
			echo TAB_4.'<legend class="Centered" >'."\n";
				
				//-------------UPDATE BUTTON------------------------------------
				echo TAB_5.'<input type="submit" name="update_all_cats" value="Update ALL displayed Categories" />'."\n";			
			echo TAB_4.'</legend>'."\n";

			//	RESET button ======================================================				
			echo TAB_4.'<a href="'.$this_page.'"'."\n";
				echo TAB_5.' title="Reload this page to Reset all Category data" >' ."\n";
				echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
			echo TAB_4.'</a>'. "\n";					
			

			if($num_cats > 1)
			{
				echo TAB_4.'<p class="Small" >( You can drag these Categories to change their order )</p>'."\n";
				$sort_class = ' class="sortableItems"';
			}
			else
			{
				$sort_class = '';
			}
				

			echo TAB_4.'<ol '.$sort_class.' style="margin-left: 25px;" >'."\n";
		
			$count = 1;
			$all_checked_active = 1;
			$CatPosArray = '';
			while ($cat_details = mysql_fetch_array ($result))
			{						

				$tab = array_search('Images', $tab_nav_array);
				$edit_images_url = $_SERVER['PHP_SELF'] . '?e='.$mod_id . '&tab='.$tab . '&cat_id=' . $cat_details['gal_cat_id'];
				
				echo TAB_5.'<li id="'.$cat_details['gal_cat_id'].'" >'."\n";			

					echo TAB_6.'<span class="ListItemHolder">'."\n";
					

						//	Do Cycle thru Thumb images for each category	========================
						
						$sql_statement = 'SELECT file_name FROM mod_photo_gal_pics'
						
													.' WHERE cat_id = "'.$cat_details['gal_cat_id'].'"'										
													;
													
						$num_pics_found = mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg));						
						$pix_result = ReadDB ($sql_statement, $mysql_err_msg);
						
						if ($num_pics_found > 0)
						{
						
							$cycle_div = 'PhotoGalCycleThumbPreview_'.$cat_details['gal_cat_id'];
							
							$js_code = TAB_7.'<script type="text/javascript">'."\n\n";

								$js_code .= TAB_8.'$(\'#'.$cycle_div.'\').cycle({'. "\n";
									
									$js_code .= TAB_9.'fx:     "fade",'."\n";
									$js_code .= TAB_9.'speed:   1000,'."\n";
									$js_code .= TAB_9.'timeout: 1000,'."\n";
									$js_code .= TAB_9.'pause:   1'."\n\n";

								$js_code .= TAB_8.'});'."\n";

							$js_code .= TAB_7.'</script>'."\n";								
							
							echo $js_code;
							
							echo TAB_7.'<a href="'.$edit_images_url.'" title="Edit Images in this Category"  style="float: left;">'."\n";
							
								echo TAB_8.'<div id="'.$cycle_div.'" class="PhotoGalCycleThumbPreview">'. "\n";
																		
								while ($pic_details = mysql_fetch_array ($pix_result))
								{
									$file_path = '_images_gallery/'.$pic_details['file_name'];
									if (file_exists('../' . $file_path))
									{
										echo TAB_8.'<img src="/'.$file_path.'"'
											.' alt="thumb preview of images in this category" />'. "\n";					
									}										
									
								}
							
								echo TAB_8.'</div>'. "\n";
							
							echo TAB_7.'</a>'."\n";	
							
						}
						
						//	Warn if not Active
						if ($cat_details['active'] != 'on')
						{
							$not_active = '<span class="WarningMSGSmall" > (Not Active)</span>';
						}
						else
						{
							$not_active = '';						
						}
				
						//	Cat name						
						echo TAB_7.'<span style="float: left; margin: 15px;" >'."\n";							
							

							//	Display Name
							echo TAB_8.'<span class="ShowEditInput">'."\n";
							
								echo TAB_9.'<span><span class="CatName">'.$cat_details['cat_name'].'</span>'.$not_active.'</span>'."\n";

								echo TAB_9.'<a href="#" class="OpenEditNamePanel" title="Edit Category Name">' ."\n";
									echo TAB_10.'<img src="/images_misc/icon_edit_16x16.png" alt="Edit" style="padding-right:10px;"/>' ."\n";
								echo TAB_9.'</a>'. "\n";
							echo TAB_8.'</span>'."\n";	

							
 								
							//	Edit Name
							echo TAB_8.'<span class="ShowEditInput HideAtStart">'."\n";				
								echo TAB_9.'<input type="text" name="cat_name_'.$cat_details['gal_cat_id'].'"'
										.' size="30" value="'.$cat_details['cat_name'].'" /> '."\n";	
								
								echo TAB_9.'<a href="#" class="CloseEditNamePanel" title="Cancel Edit Name">' ."\n";
									echo TAB_10.'<img src="/images_misc/icon_close_16x16.png" alt="Cancel" style="padding-right:10px;"/>' ."\n";
								echo TAB_9.'</a>' ."\n";
							echo TAB_8.'</span>'."\n";						
					
						
						echo TAB_7.'</span>'."\n";	
						
						
						//	Delete this category (select)
						echo TAB_7.'<span class="WarningMSG" style="float: right; cursor:default;">'."\n";					
							
							echo TAB_8.'<input type="checkbox" name="delete[]" class="CheckAllDelete"'
								.' value="'.$cat_details['gal_cat_id'].'" title="Tick this box to Delete this Category" /> Delete'."\n";

						echo TAB_7.'</span>'."\n";
						
						//	Active
						echo TAB_7.'<span style="float: right; cursor:default; margin-right:15px;">'."\n";					
							
							if ($cat_details['active'] == 'on')
							{
								$checked = ' checked="checked"';
							}
							else 
							{ 
								$checked = '';
								$all_checked_active = 0;	//	not all checked
							}								
														
							echo TAB_8.'<input type="checkbox" name="active[]" class="CheckAllActive"  value="'.$cat_details['gal_cat_id'].'" '
								."\n";
								echo TAB_9.'title="Tick this box to Delete this Category"'.$checked.'/> Active'."\n";
						
							echo TAB_8.'<br/>'."\n";	
								
							//	N# of Images
							echo TAB_8.'<a href="'.$edit_images_url.'" title="Edit Images in this Category" >'."\n";
								echo TAB_9.'[ '.$num_pics_found.' images <img src="/images_misc/icon_edit_16x16.png" alt="Edit" />]</a>'."\n";
													
						
						echo TAB_7.'</span>'."\n";
					
					echo TAB_6.'</span>'."\n";	
				
				echo TAB_5.'</li>'."\n";
				
				if ($count == 1) {$CatPosArray .= $cat_details['gal_cat_id'];}
				else {$CatPosArray .= ','.$cat_details['gal_cat_id'];}
								
				$count++;					
								
			}
				
			echo TAB_4.'</ol>'."\n";

		if($num_cats > 1)
		{
			
			//	Select all to Active
			echo TAB_4.'<fieldset class="AdminForm3" style="margin-left: 400px;" title="tick this to select all Categories Active" >'."\n";
				
				if ( $all_checked_active == 1)
				{ $checked = ' checked="checked"'; }
				else { $checked = ''; }					
				
				echo TAB_5.'<input type="checkbox" name="active[]" class="CheckAll CheckAllActive" id="CheckAllActiveMaster" value="all"'
							.$checked.'/>'."\n";
				echo TAB_5.'<span>ALL Active</span>'."\n";
			echo TAB_4.'</fieldset>'."\n";						
			

			//	Select all to Delete
			echo TAB_4.'<fieldset class="AdminForm3" title="tick this to select all Categories for Deletion" >'."\n";
				echo TAB_5.'<input type="checkbox" name="delete[]" class="CheckAll CheckAllDelete" id="CheckAllDeleteMaster" value="all" />'."\n";
				echo TAB_5.'<span class="WarningMSG" >Delete ALL</span>'."\n";
			echo TAB_4.'</fieldset>'."\n";			
		

		}
				
		echo TAB_3.'</fieldset>' ."\n";	

		
		//	this is used to update Category order
		echo TAB_3.'<input type="hidden" id="ItemPosArray" name="cat_pos_array" value="' . $CatPosArray . '" />'."\n";

			
	}

		echo TAB_3.'<input type="hidden" name="mod_id" value="'.$mod_id.'" />'."\n";
		echo TAB_3.'<input type="hidden" name="mod_type_id" value="'.$mod_type_info['mod_type_id'].'" />'."\n";
		echo TAB_3.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";		
	
	echo TAB_2.'</form>' ."\n";	
		
?>