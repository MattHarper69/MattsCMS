<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

		
	$this_page = $_SERVER['PHP_SELF'];
	$update_url = '/CMS/cms_update/cms_update_sweeps_Categories.php';
	$file_path_offset = '../../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	
if (isset($_SESSION['access']) AND $_SESSION['access'] < 5 )
{		
	
	require_once (CODE_NAME.'_shop_configs.php');
	require_once (CODE_NAME.'_alert_configs.php');
	require_once ($file_path_offset.'modules/sweeps/sweeps_php_functions.php');
	
	
	// 	Start output buffering
	ob_start();
	
	require_once ('../../cms_includes/cms_head.php');

	echo '<body>'." \n";
	
	
	//	SHUTDOWN msg
	if (SITE_SHUTDOWN == 1)
	{
		echo TAB_4.'<div class="UpdateMsgDiv">'."\n";	
			echo TAB_5.'<p class = "WarningMSG" >The Site is currently SHUT DOWN - Go to Global Settings to Re-Activate</p>'."\n";
		echo TAB_4.'</div>'."\n";			
	}


	if (isset($_REQUEST['cat_id']) AND $_REQUEST['cat_id'] != '')
	{
		
		//---------Update error msg:
		include_once ($file_path_offset.'CMS/cms_includes/cms_msg_update.php');
		
		// get all Category info
		$mysql_err_msg = 'Category information unavailable';	
		$sql_statement = 'SELECT * FROM sweeps_categories WHERE cat_id = "'.$_REQUEST['cat_id'].'"';
							
		$sweeps_cat_result = ReadDB ($sql_statement, $mysql_err_msg);

		$sweeps_cat_info = mysql_fetch_array ($sweeps_cat_result);

		if (mysql_num_rows ($sweeps_cat_result) > 0 OR $_REQUEST['cat_id'] == 'new' OR $_REQUEST['clone'] == 1) 
		{
					
			//	Window Adjust Buttons
			echo TAB_2.'<div style=" float:right;">'. "\n";
				//	Close Window ======================================================
				echo TAB_3.'<a href="javascript:parent.$.fn.colorbox.close()" title="Cancel and Close this window" >' ."\n";
					echo TAB_4.'<img src="/images_misc/icon_closeWin_16x16.png" alt="Close" style="float:right;"/>' ."\n";
				echo TAB_3.'</a>'. "\n";			

				//	Maximize Window	======================================================
				echo TAB_3.'<a href="#" class="MaximizeWindow" title="Maximize this window" >' ."\n";
					echo TAB_6.'<img src="/images_misc/icon_maxWin_16x16.png" alt="Maximize" style="padding-right:2px; float:right;"/>' ."\n";
				echo TAB_3.'</a>'. "\n";
				
				//	Restore Window	======================================================
				echo TAB_3.'<a href="#" class="RestoreWindow" title="Restore this window" >' ."\n";
					echo TAB_4.'<img src="/images_misc/icon_restoreWin_16x16.png" alt="Restore" style=" padding-right:2px; float:right;"/>' ."\n";
				echo TAB_3.'</a>'. "\n";
			echo TAB_2.'</div>'. "\n";		
			
			if (isset($_REQUEST['clone']) AND $_REQUEST['clone'] == 1 )
			{
				echo TAB_2.'<h1>Clone Category: '.$sweeps_cat_info['cat_name'] .'</h1>'."\n";

			}
			elseif ($_REQUEST['cat_id'] == 'new' )
			{
				echo TAB_2.'<h1>Add New Category</h1>' ."\n";

			}		
			else
			{
				echo TAB_2.'<h1>Edit Category: '.$sweeps_cat_info['cat_name'] ."\n";
				
					//	Warn if not Active
					if ($sweeps_cat_info['active'] != 'on')
					{
						echo TAB_3.'<span class="WarningMSG" > ( Not Active )</span>';
					}
								
					//	Clone Category	======================================================
					echo TAB_4.'<a href="'.$this_page.'?cat_id='.$_REQUEST['cat_id'].'&amp;clone=1" title="Clone this Category" >' ."\n";
						echo TAB_5.'<img src="/images_misc/icon_ClonePage_24x24.png" alt="Clone" style="padding-right:10px; float:right;"/>'."\n";
					echo TAB_4.'</a>'. "\n";	
					
					//	Add Category	======================================================
					echo TAB_3.'<a href="'.$this_page.'?cat_id=new" title="Add a new Category" >' ."\n";
						echo TAB_4.'<img src="/images_misc/icon_AddPage_24x24.png" alt="Add" style="padding-right:10px; float:right;"/>' ."\n";
					echo TAB_3.'</a>'. "\n";
					
				echo TAB_2.'</h1>'."\n";
				
			}
			

				
			//	Start Main Update Form
			echo TAB_2.'<form id="Form_EditCategory" action = "'.$update_url.'"  method="post" enctype="multipart/form-data" >'."\n";
				
				echo TAB_3.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
					echo TAB_4.'<legend class="Centered" >'."\n";
						
					if ($_REQUEST['cat_id'] == 'new' OR (isset($_REQUEST['clone']) AND $_REQUEST['clone'] == 1) )
					{
						//-------------ADD Category BUTTON------------------------------------
						echo TAB_5.'<input type="submit" name="submit_update_cat" value="Save and Add this Category" />'."\n";
						if ($_REQUEST['cat_id'] == 'new')
						{
							echo TAB_5.'<input type="hidden" name="update_action" value="new" />'."\n";				
						}
						elseif ($_REQUEST['clone'] == 1)
						{
							echo TAB_5.'<input type="hidden" name="update_action" value="clone" />'."\n";				
						}
						//$_REQUEST['tab'] = 1;		//	???				
					
					}
					
					else
					{
						//-------------UPDATE BUTTON------------------------------------
						echo TAB_5.'<input type="submit" name="submit_update_cat" value="Update ALL Category Data" />'."\n";	
						
						//	Delete BUTTON------------------------------------
						echo TAB_5.'<p style="float:right;">' ."\n";
										
							echo TAB_6.'<a href="#" class="ConfirmDeleteButton" title="Delete this Category: '.$sweeps_cat_info['cat_name'].'" >'."\n";
								echo TAB_7.'<img src="/images_misc/icon_delete_24x24.png" alt="Delete" />' ."\n";
							echo TAB_6.'</a>'. "\n";

						echo TAB_5.'</p>' ."\n";
								
						echo TAB_5.'<p class="WarningMSG HideAtStart" style="border: solid 1px #cccccc; padding:5px;">' ."\n";
										
							//	OK DELETE Mod
							echo TAB_6.'Confirm: <input type="submit" name="submit_delete_cat" style="color:#cc0000;"'."\n";	 
								echo TAB_7.' value="DELETE" title="Delete this Category" /> this Category'."\n";	
										
							//	Cancel link
							echo TAB_6.'<a href="#" class="CloseThisPanel" title="Do NOT Delete">' ."\n";
								echo TAB_7.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="padding:10px; float:right;"/>' ."\n";
							echo TAB_6.'</a>' ."\n";
											
						echo TAB_5.'</p>' ."\n";						
						
					}			

						
					echo TAB_4.'</legend>'."\n";

			
					echo TAB_4.'<fieldset id="UpdateShopEditSettings" class="AdminForm1" style="clear:both;">'."\n";	

					
						//	RESET button ======================================================				
						echo TAB_5.'<a  href="'.$this_page.'?cat_id='.$_REQUEST['cat_id'].'"'."\n";
							echo TAB_6.' title="Reload this page to Reset all Category data" >' ."\n";
							echo TAB_6.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
						echo TAB_5.'</a>'. "\n";	
					
						//	edit Name 		
						echo TAB_5.'<fieldset class="AdminForm3" style="height: 25px;" >'."\n";
							echo TAB_6.'Category Name: <input type="text" name="cat_name" value="'.$sweeps_cat_info['cat_name'].'"' . "\n";
							echo TAB_6.' size="30" title="Add or Edit the Category&#39;s display Name here" /> '."\n";
						echo TAB_5.'</fieldset>'."\n";
												
						//	Active
						if ($sweeps_cat_info['active'] == 'on' OR $_REQUEST['cat_id'] == 'new') { $checked = ' checked="checked"'; }
						else { $checked = '';}
						
						echo TAB_5.'<fieldset class="AdminForm3" style="height: 25px;" >'."\n";
							echo TAB_6.'<input type="checkbox" name="active" '.$checked.' /> : Set this Category as ACTIVE (uncheck to HIDE)'."\n";
						echo TAB_5.'</fieldset>'."\n";	
						
						//	move to another category
						echo TAB_5.'<fieldset class="AdminForm3" >'."\n";
						
						if ($sweeps_cat_info['parent_id'] > 0)
						{
							$sql_statement = 'SELECT cat_name FROM sweeps_categories WHERE cat_id = "'.$sweeps_cat_info['parent_id'].'"';	
							$cat_parent_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	
							$parent_name = $cat_parent_info[0];
						}
						elseif ($sweeps_cat_info['parent_id'] == 0)
						{
							$parent_name = 'Not Set - (This Category is not located in another Category)';
						}						
		
							echo TAB_6.'<p>Parent Category: <strong> '.$parent_name.'</strong></p>' ."\n";
							
							echo TAB_6.'<p>Move to: <select name="new_parent_id" title="Move this Category to another Category">' ."\n";
							
							$list_cat_details = FillTheCatList(0,0);	//-----function to list all cats and sub-cats

								//	default option (existing category)
								echo TAB_7.'<option value="'.$sweeps_cat_info['parent_id'].'" selected="selected" ></option>'."\n";

								echo TAB_7.'<option value="0" >[No Parent]</option>'."\n";
							
							for ($i = 0; $i  < count($list_cat_details); $i++)
							{
								//	Indent Sub Categories
								$indent = '';
								for ($j = 0; $j < $list_cat_details[$i][7]; $j++) 
								{$indent .= '&nbsp;&nbsp;';} 						
												
								if ( $list_cat_details[$i][0] != $sweeps_cat_info['cat_id']) 
								{ 
									echo TAB_7.'<option value="'.$list_cat_details[$i][0].'" >'
												.$indent.'-&nbsp;'.$list_cat_details[$i][1].'</option>'."\n";	
								}

							}
									
							echo TAB_6.'</select></p>' ."\n";
							
							//	need old parent id for adjusting child parent IDs
							echo TAB_3.'<input type="hidden" name="old_parent_id" value="'.$sweeps_cat_info['parent_id'].'" />'."\n";
								
						echo TAB_5.'</fieldset>'."\n";

						//	edit Brief 
						echo TAB_5.'<fieldset class="AdminForm3" >'."\n";
							echo TAB_6.'<p>Brief Description:</p>'. "\n";
							echo TAB_6.'<textarea name="description" onkeyup="AutoResize(this);" onkeydown="AutoResize(this);"'. "\n";
								echo TAB_7.'title="Add or Edit the Category&#39;s Short description here" cols="100" rows="2" >';
								echo $sweeps_cat_info['description'];
							echo '</textarea>' . "\n";		
						echo TAB_5.'</fieldset>'."\n";
							
						//	Image				
						echo TAB_5.'<fieldset class="AdminForm3">'."\n";
								
							if ( $sweeps_cat_info['display_image'] == 'on' OR $_REQUEST['cat_id'] == 'new')
							{ 
								$checked = ' checked="checked"';
								$img_not_on = '';
							}
							else 
							{ 
								$checked = '';
								$img_not_on = '<span class="WarningMSG" >( Images are set NOT to Display )</span>';
							}	
							
							echo TAB_7.'<p><input type="checkbox" name="display_image"'.$checked
										.' title="Check this box to display the set image for this Category" /> '."\n";
							echo TAB_7.'Display Images for this Category'."\n";
							echo TAB_7.$img_not_on.'</p>' . "\n";

				
							//	Upload image file 
							echo TAB_6.'<fieldset class="AdminForm3" >'."\n";
								echo TAB_7.'<input type="hidden" name="MAX_FILE_SIZE" value="'.MAX_FILE_SIZE_CMS.'" />' . "\n";
								echo TAB_7.'Select a new Image file for this Category:<br/>' . "\n"; 
								echo TAB_7.'<input type="file" id="upload_cat_image" name="upload_cat_image"' . "\n"; 
									echo TAB_8.' style="font-size: 12px;" size="30"'
												.' title="Use this to locate and upload an image file for this Category" /> '."\n";

							echo TAB_6.'</fieldset>'."\n";
						
						// Current Image - dont show if NEW cat OR not set
						if ($_REQUEST['cat_id'] != 'new' AND $sweeps_cat_info['image_file'] != '' AND $sweeps_cat_info['image_file'] != NULL)
						{
							echo TAB_6.'<div class="ImageThumbHolder" >'."\n";
							
								echo TAB_7.'<p>Current Image:</p>' ."\n";
																		
								//	Delete
								echo TAB_7.'<p style="float:right;">' ."\n";
								
									echo TAB_8.'<a href="#" class="ConfirmDeleteButton" title="Delete this Category&#39;s Image file" >' ."\n";
										echo TAB_9.'<img src="/images_misc/icon_delete_24x24.png" alt="Delete"/>' ."\n";
									echo TAB_8.'</a>'. "\n";
								echo TAB_7.'</p>' ."\n";
										
								echo TAB_7.'<p class="WarningMSG HideAtStart" style="border: solid 1px #cccccc; padding:5px;">' ."\n";
									
									//	Cancel link
									echo TAB_7.'<a href="#" class="CloseThisPanel" title="Do NOT Delete">' ."\n";
										echo TAB_8.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="float:right;"/>' ."\n";
									echo TAB_7.'</a>' ."\n";							
									
									//	OK DELETE Mod
									echo TAB_7.'Confirm:<input type="submit" name="delete_cat_image" style="color:#cc0000;"'."\n";
											echo TAB_8.' value="DELETE" title="Delete this Category&#39;s Image" />'."\n";		
								
								echo TAB_7.'</p>' ."\n";	

								//	Image Display
								$img_url = '/_images_shop/'.$sweeps_cat_info['image_file'];
								if 
								(
										file_exists('../../../'.$img_url ) 
									AND $sweeps_cat_info['image_file'] != '' 
									AND $sweeps_cat_info['image_file'] != NULL
								)
								{
														
									// get width to height ratio and restrict width if image to 'short and wide'	
									$image_details = getimagesize('../../../'.$img_url);
									
									if ($image_details[0] / $image_details[1] > 1.5) 
									{$dims = 'width="120px"';}
									
									else {$dims = 'height="80px"';}		// otherwize restrict by height by default

									echo TAB_7.'<a href="'.$img_url .'" rel="ColorBoxImage" >' ."\n";
										echo TAB_8.'<img src="'.$img_url.'"'.$dims.' alt="Image of Category: '.$sweeps_cat_info['cat_name'].'" />'."\n";
									echo TAB_7.'</a>' ."\n";

								}
								else
								{
									echo TAB_7.'( Image File Not Found )' ."\n";
								}
							
							echo TAB_6.'</div>'."\n";
							
						}
							
						echo TAB_5.'</fieldset>'."\n";				

					echo TAB_4.'</fieldset>' ."\n";		

				echo TAB_3.'</fieldset>' ."\n";	
				
				echo TAB_3.'<input type="hidden" name="cat_id" value="'.$_REQUEST['cat_id'].'" />'."\n";
			
				$return_url = $this_page;
				echo TAB_3.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";
			
			echo TAB_2.'</form>' ."\n";
		
		}
		
		else	//	Close window
		{
			CloseColorBox();
		}	
		
	}
	
 	else	//	Close window
	{
		CloseColorBox();
	}		

	echo '</body>'." \n";
	echo '</html>'." \n";

	// 	Now flush the output buffer
	ob_end_flush();		
	
}

?>