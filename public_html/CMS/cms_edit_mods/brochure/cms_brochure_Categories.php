<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'] . '&amp;tab='.$_GET['tab'];
	
	$update_url = '/CMS/cms_update/cms_update_sweeps_Products_list.php';
	
	$return_url ='../cms_edit_mod_data.php?e='.$_GET['e'] . '&tab='.$_GET['tab'];
	
	$edit_cat_href = 'cms_edit_mods/sweeps/cms_sweeps_edit_product_index.php';


	echo TAB_2.'<script type="text/javascript">
		
		$(document).ready( function()
		{
			//	Data Table
			$("#ItemListing").dataTable({
				"bJQueryUI": true,
				"sPaginationType": "full_numbers"
			});
			
			//	Warning All selected for Deletion
			$("#CheckAllDelete:checkbox").click(function() {
				if($("#CheckAllDelete:checkbox").attr("checked"))
				{
					alert("You have selected Delete ALL:"
						+ "\n - Clicking the Update Button will Delete ALL displayed Categories");
				}
					
			});			
	
		});
	</script>'."\n";

	//---------Update error msg:
	include_once ('cms_includes/cms_msg_update.php');
	
	
	//	Add a new Product	
	echo TAB_3.'<fieldset class="AdminForm2" style="margin-bottom:10px;">' ."\n";	

		echo TAB_4.'<form action="'.$this_page.'" method="post" enctype="multipart/form-data" >'."\n";
		
			echo TAB_5.'<h3" >Add A New Category:</h3>' ."\n";
			
			//echo TAB_5.'<table id="ItemListing" class="display">'."\n";
			
			//	name
			echo TAB_3.'<fieldset class="AdminForm3">' ."\n";
				echo TAB_5.'Name: <input type="text" name="cat_name_add"  size="30" maxlength="255" />'. "\n";	
				echo TAB_5.'<br/>'. "\n";	

				//	display name
				echo TAB_5.'<input type="checkbox" name="display_name_add" checked="checked"/> - display this name' . "\n";
			echo TAB_3.'</fieldset>' ."\n";
			
			//	Active
			echo TAB_3.'<fieldset class="AdminForm3">' ."\n";
				echo TAB_5.'Active: <input type="checkbox" name="active_add"  checked="checked" />'. "\n";		
				//	order
				echo TAB_5.'<br/><br/>'. "\n";
			echo TAB_5.'Order: <input type="text" name="seq_add"  size="3" maxlength="6" value="1" />'. "\n";			

			echo TAB_3.'</fieldset>' ."\n";
			
			//	description
			echo TAB_3.'<fieldset class="AdminForm3">' ."\n";
				echo TAB_9.'Desctiption (optional)<br/>'. "\n";
				echo TAB_9.' <textarea name="description_add" cols="70" onkeyup="AutoResize(this);" onkeydown="AutoResize(this);"></textarea>'."\n";
			echo TAB_3.'</fieldset>' ."\n";
			
			echo TAB_3.'<fieldset class="AdminForm3">' ."\n";
				echo TAB_5.'<input type="submit" name="submit_add" value="Add" />'. "\n";	
			echo TAB_3.'</fieldset>' ."\n";	
			
		echo TAB_4.'</form>'."\n";
		
		//	Do add new cat form...
		
		
		
	echo TAB_3.'</fieldset>' ."\n";	
		
		
	
		$sql_statement = 'SELECT'
										.'  cat_id'
										.', cat_name'
										.', active'									
										.', seq'
										.', display_name'
										.', description'
										
										.' FROM mod_brochure_cats' 

									.' WHERE mod_id = "'.$mod_id.'"'
									.' ORDER BY seq'
									;
		

		//echo $sql_statement;	
		$mysql_err_msg = $settings_info['item_alias'].' Category Listing Information not found';			

		$cat_info_result = ReadDB ($sql_statement, $mysql_err_msg);		
			
		$num_cats_found = mysql_num_rows($cat_info_result);
		
		if($num_cats_found < 1)
		{
			echo TAB_3.'<fieldset class="AdminForm2">' ."\n";
				echo TAB_4.'<h2>No Categories found</h2>' ."\n";
			echo TAB_3.'</fieldset>' ."\n";	
		}

		else
		{
		
			echo TAB_3.'<form action = "'.$update_url.'" method="post" enctype="multipart/form-data" >'."\n";
				echo TAB_4.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
					echo TAB_5.'<legend class="Centered" >'."\n";
						
						//-------------UPDATE BUTTON------------------------------------
						echo TAB_6.'<input type="submit" name="update_all" value="Update ALL displayed Categories" />'."\n";
					echo TAB_5.'</legend>'."\n";

					//	RESET button ======================================================				
					echo TAB_5.'<a  href="'.$this_page.'"'."\n";
						echo TAB_6.' title="Reload this page to Reset all '.$settings_info['item_alias'].' data" >' ."\n";
						echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
					echo TAB_5.'</a>'. "\n";				
					
					echo TAB_5.'<table id="ItemListing" class="display">'."\n";

						echo TAB_6.'<thead>'."\n";
							echo TAB_7.'<tr>'."\n";
								echo TAB_8.'<th></th>'."\n";
								echo TAB_8.'<th>Name</th>'."\n";
								echo TAB_8.'<th>Order</th>'."\n";
								echo TAB_8.'<th>Active</th>'."\n";							
								echo TAB_8.'<th>Description</th>'."\n";
								//echo TAB_8.'<th>Edit</th>'."\n";
								echo TAB_8.'<th>Delete</th>'."\n";
							echo TAB_7.'</tr>'."\n";
						echo TAB_6.'</thead>'."\n";
						
						echo TAB_6.'<tbody>'."\n";

					$count = 1;
					$all_checked_active = 1;
					$all_checked_display_name = 1;	
					
					while ($cat_info = mysql_fetch_array($cat_info_result))
					{				
						echo TAB_7.'<tr>'."\n";
												
							//	Order
							echo TAB_8.'<td align="left">'."\n";
								//	send cat ID
								echo TAB_9.'<input type="hidden" name="cat_id_'.$count.'" value="'.$cat_info['cat_id'].'" />'."\n";					
					
								echo TAB_9.$count ."\n";

							echo TAB_8.'</td>'."\n";
							
							//	Category name
							echo TAB_8.'<td align="left">'."\n";
								//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
								echo TAB_9.'<span style="display: none;">'.$cat_info['cat_name'].'</span>'. "\n";	

								echo TAB_9.' <input type="text" name="cat_name_'.$count.'" value="'.$cat_info['cat_name']
								.'" size="30" maxlength="255" />'. "\n";	
								
								echo TAB_9.'<br/>'. "\n";								

								if ($cat_info['display_name'] == 'on')
								{
									$checked = ' checked="checked"';
								}
								else 
								{ 
									$checked = '';
									$all_checked_display_name = 0;	//	not all checked
								}
								
								echo TAB_9.'<input type="checkbox" name="display_name[]" '.$checked.' value="'.$cat_info['cat_id'].'"/>' . "\n";
								echo TAB_9.' - display this name'. "\n";											
							echo TAB_8.'</td>'."\n";	
							
							//	Category Order
							echo TAB_8.'<td align="center">'."\n";
								//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
								echo TAB_9.'<span style="display: none;">'.$cat_info['seq'].'</span>'. "\n";	

								echo TAB_9.' <input type="text" name="seq_'.$count.'" value="'.$cat_info['seq'].'" size="3" maxlength="6" />'
										. "\n";	
							echo TAB_8.'</td>'."\n";	

							//	Active
							echo TAB_8.'<td align="center">'."\n";	
								

								if ($cat_info['active'] == 'on')
								{
									$checked = ' checked="checked"';
								}
								else 
								{ 
									$checked = '';
									$all_checked_active = 0;	//	not all checked
								}
								
								//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort						
								echo TAB_9.'<span style="display: none;">'.$checked.'</span>'. "\n";
								echo TAB_9.'<input type="checkbox" name="active[]" '.$checked.' value="'.$cat_info['cat_id'].'"/>'. "\n";	
							echo TAB_8.'</td>'."\n";
													
							//	Category Description
							echo TAB_8.'<td align="center">'."\n";	
								//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
								echo TAB_9.'<span style="display: none;">'.$cat_info['description'].'</span>'. "\n";	

								echo TAB_9.' <textarea name="description_'.$count.'" cols="65" onkeyup="AutoResize(this);" onkeydown="AutoResize(this);">'.$cat_info['description'].' </textarea>'
										. "\n";	
							echo TAB_8.'</td>'."\n";								


/* 							
							//	Edit link
							echo TAB_8.'<td align="center">'."\n";
								echo TAB_9.'<a href="'.$edit_cat_href.'?mod_id='.$_GET['e'].'&amp;cat_id='.$cat_info['cat_id'].'&amp;tab=1"'."\n";
								echo TAB_9.'rel="CMS_ColorBox_EditShopItem" title="Edit '.$settings_info['item_alias'].': '.$cat_info['cat_name'].'">'."\n";	
									echo TAB_10.'<img src="/images_misc/icon_edit_16x16.png" />'."\n";
								echo TAB_9.'</a>'."\n";	
							echo TAB_8.'</td>'."\n";
							 */
						
							//	Delete
							echo TAB_8.'<td align="center">'."\n";

								echo TAB_9.'<p>' ."\n";
								
									//	check-box
									echo TAB_10.'<input type="checkbox" name="delete[]" value="'.$cat_info['cat_id'].'" /> ' ."\n";			
									echo TAB_10.'<a href="#" class="ConfirmDeleteButton" title="Delete '.$settings_info['item_alias'].': '.$cat_info['cat_name'].'">'."\n";	
										echo TAB_11.'<img src="/images_misc/icon_delete_16x16.png" />'."\n";
									echo TAB_10.'</a>'."\n";								
								echo TAB_9.'</p>'."\n";
								
								echo TAB_9.'<p class="WarningMSGSmall HideAtStart" style="border: solid 1px #cccccc; padding:5px;">' ."\n";
									
									//	Cancel link
									echo TAB_10.'<a href="#" class="CloseThisPanel" title="Do NOT Delete">' ."\n";
										echo TAB_11.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="float:right;"/>' ."\n";
									echo TAB_11.'</a>' ."\n";							
									
									//	OK DELETE Mod		
									echo TAB_11.'Confirm:<input type="submit" name="submit_delete_cat_'.$count.'" style="color:#cc0000;"'."\n";
										echo TAB_12.' value="DELETE" title="Delete this '.$settings_info['item_alias'].'" />'."\n";
									
								echo TAB_9.'</p>' ."\n";	

							echo TAB_8.'</td>'."\n";						
							
						echo TAB_7.'</tr>'."\n";
					
						$num_records = $count;
						$count++;
					
					}
						echo TAB_6.'<tfoot>'."\n";
							echo TAB_7.'<tr>'."\n";
								echo TAB_8.'<td></td>'."\n";
								
								
								echo TAB_8.'<td align="left">'."\n";
									if ( $all_checked_display_name == 1)
									{ $checked = 'checked="checked"'; }
									else { $checked = ''; }
								
									echo TAB_9.'<input class="check_all" name="display_name[]" type="checkbox" value="all" '.$checked.' style="width: 20px;"/>'."\n";
									echo TAB_9.'select all'."\n";
								echo TAB_8.'</td>'."\n";								
								
								echo TAB_8.'<td></td>'."\n";
								
								echo TAB_8.'<td align="center">'."\n";
									if ( $all_checked_active == 1)
									{ $checked = 'checked="checked"'; }
									else { $checked = ''; }
								
									echo TAB_9.'<input class="check_all" name="active[]" type="checkbox" value="all" '.$checked.'/>'."\n";
									echo TAB_9.'select all'."\n";
								echo TAB_8.'</td>'."\n";
								
								echo TAB_8.'<td></td>'."\n";
								
								echo TAB_8.'<td align="center">'."\n";
								
									echo TAB_9.'<input id="CheckAllDelete" class="check_all" name="delete[]" type="checkbox" value="all" />'."\n";
									echo TAB_9.'<br/>select all'."\n";
		
								echo TAB_8.'</td>'."\n";
								
							echo TAB_7.'</tr>'."\n";
						echo TAB_6.'</tfoot>'."\n";	
						
					echo TAB_5.'</table>'."\n";
		
				echo TAB_4.'</fieldset>' ."\n";	
				
				echo TAB_4.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";
				
				//	used to do db update and determin correct n# of total checkboxs
				echo TAB_4.'<input type="hidden" name="num_records" value="'.$num_records.'" />'."\n";			

			echo TAB_3.'</form>' ."\n";	
		}
	
	
		
?>