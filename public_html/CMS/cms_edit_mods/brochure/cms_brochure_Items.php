<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'] . '&amp;tab='.$_GET['tab'];
	
	$update_url = '/CMS/cms_update/cms_update_sweeps_Products_list.php';
	
	$cat_id = '';
	if (isset($_REQUEST['cat_id']))
	{
		$cat_id = $_REQUEST['cat_id'];
	}
	
	$return_url ='../cms_edit_mod_data.php?e='.$_GET['e'] . '&tab='.$_GET['tab'] . '&cat_id='.$cat_id;
	
	$edit_item_href = 'cms_edit_mods/brochure/cms_brochure_edit_item_index.php';


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
						+ "\n - Clicking the Update Button will Delete ALL displayed Items");
				}
					
			});			
	
		});
	</script>'."\n";

	//---------Update error msg:
	include_once ('cms_includes/cms_msg_update.php');
	
	echo TAB_2.'<form action="'.$this_page.'" method="post" enctype="multipart/form-data" >'."\n";
		
		echo TAB_3.'<fieldset class="AdminForm2" style="height:20px; margin-bottom:10px;">' ."\n";	


			$cat_listing = array();
			while ($cat_row = mysql_fetch_array($cat_result))
			{
				$cat_listing[$cat_row['cat_id']] = $cat_row['cat_name'];
			}


			//	Select Items by Category
		
			if (mysql_num_rows($cat_result) < 2)
			{
				$cat_id = 'all';
			}
			else
			{
				echo TAB_4.'List '.$settings_info['item_alias'].'s by Category: <select onchange="location.href=this.value;">' ."\n";
				
					if ($cat_id == '')
					{
						echo TAB_5.'<option value=""></option>'."\n";					
					}

					if ( $cat_id == '0') { $selected = ' selected="selected"';}
					else { $selected = '';}
					echo TAB_5.'<option value="'.$this_page.'&amp;cat_id=0"'.$selected.' >(all categories)</option>'."\n";

					foreach ($cat_listing as $list_cat_id => $list_cat_name)
					{
						if ($list_cat_id == $cat_id) 
						{$selected = ' selected="selected"';}						
						else {$selected = '';}
					
						echo TAB_7.'<option'.$selected.' value="'.$this_page.'&amp;cat_id='.$list_cat_id.'" >'.$list_cat_name.'</option>'."\n";
								
					}
						
				echo TAB_4.'</select> OR ' ."\n";			
						
			}

			
			//	Add new Product Link
			echo TAB_4.'<a class="ButtonLink" href="'.$edit_item_href.'?item_id=new&amp;mod_id='.$_GET['e'].'"'
						.' rel="CMS_ColorBox_EditShopItem" title="Add a new '.$settings_info['item_alias'].'" > Add New '.$settings_info['item_alias'].'</a>' ."\n";
		
		echo TAB_3.'</fieldset>' ."\n";	
		
		
	echo TAB_2.'</form>' ."\n";
					
	if ($cat_id == '' OR $cat_id == NULL)
	{
		
	}
	
	else
	{
		

		if ($cat_id == '0' )
		{
			$select_cat_id_str = '';
		}
		
		else
		{
			$select_cat_id_str = ' AND mod_brochure_items.cat_id ="'.$cat_id.'"';
		}


		
			$sql_statement = 'SELECT'
											.'  item_id'
											.', item_name'
											.', heading'
											.', cat_id'
											.', seq'
											.', image_file'
											.', active'
											
											.' FROM mod_brochure_items' 

										.' WHERE mod_id = "'.$mod_id.'"'
										.  $select_cat_id_str
										.' ORDER BY cat_id, seq'
										;
		

		//echo $sql_statement;	
		$mysql_err_msg = $settings_info['item_alias'].' Listing Information not found';			

		$item_info_result = ReadDB ($sql_statement, $mysql_err_msg);		
			
		$num_items_found = mysql_num_rows($item_info_result);
		
		if($num_items_found < 1)
		{
			echo TAB_3.'<fieldset class="AdminForm2">' ."\n";
				echo TAB_4.'<h2>No '.$settings_info['item_alias'].'s found</h2>' ."\n";
			echo TAB_3.'</fieldset>' ."\n";	
		}

		elseif ($cat_id !='' AND $num_items_found > 0)
		{
		
			echo TAB_3.'<form action = "'.$update_url.'" method="post" enctype="multipart/form-data" >'."\n";
				echo TAB_4.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
					echo TAB_5.'<legend class="Centered" >'."\n";
						
						//-------------UPDATE BUTTON------------------------------------
						echo TAB_6.'<input type="submit" name="update_all" value="Update ALL displayed '.$settings_info['item_alias'].'s" />'."\n";
					echo TAB_5.'</legend>'."\n";

					//	RESET button ======================================================				
					echo TAB_5.'<a  href="'.$this_page.'&amp;cat_id='.$cat_id.'"'."\n";
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
								echo TAB_8.'<th>Heading</th>'."\n";
								echo TAB_8.'<th>Category</th>'."\n";
								echo TAB_8.'<th>Image</th>'."\n";
								echo TAB_8.'<th>Edit</th>'."\n";
								echo TAB_8.'<th>Delete</th>'."\n";
							echo TAB_7.'</tr>'."\n";
						echo TAB_6.'</thead>'."\n";
						
						echo TAB_6.'<tbody>'."\n";

					$count = 1;
					$all_checked_active = 1;	
					while ($item_info = mysql_fetch_array($item_info_result))
					{				
						echo TAB_7.'<tr>'."\n";
												
							//	Order
							echo TAB_8.'<td align="left">'."\n";
								//	send item ID
								echo TAB_9.'<input type="hidden" name="item_id_'.$count.'" value="'.$item_info['item_id'].'" />'."\n";					
					
								echo TAB_9.$count ."\n";

							echo TAB_8.'</td>'."\n";
							
							//	item name
							echo TAB_8.'<td align="left">'."\n";
								echo TAB_9.'<a href="'.$edit_item_href.'?mod_id='.$_GET['e'].'&amp;item_id='.$item_info['item_id'].'&amp;tab=1"'."\n";
									echo TAB_10.' rel="CMS_ColorBox_EditShopItemName"'."\n";
								echo TAB_9.' title="Edit '.$settings_info['item_alias'].': '.$item_info['item_name'].'">'.$item_info['item_name'].'</a>'."\n";	
							echo TAB_8.'</td>'."\n";	
							
							//	item Order
							echo TAB_8.'<td align="center">'."\n";
								//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
								echo TAB_9.'<span style="display: none;">'.$item_info['seq'].'</span>'. "\n";	

								echo TAB_9.' <input type="text" name="seq_'.$count.'" value="'.$item_info['seq'].'" size="3" maxlength="6" />'
										. "\n";	
							echo TAB_8.'</td>'."\n";	

							//	Active
							echo TAB_8.'<td align="center">'."\n";	
								

								if ($item_info['active'] == 'on')
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
								echo TAB_9.'<input type="checkbox" name="active[]" '.$checked.' value="'.$item_info['item_id'].'"/>'. "\n";	
							echo TAB_8.'</td>'."\n";
													
							//	Heading
							echo TAB_8.'<td align="center">'."\n";	
								//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
								echo TAB_9.'<span style="display: none;">'.$item_info['heading'].'</span>'. "\n";	

								echo TAB_9.' <input type="text" name="heading_'.$count.'" value="'.$item_info['heading'].'" size="30"'
								. ' maxlength="255" />'. "\n";	
							echo TAB_8.'</td>'."\n";								

							//	Category
							echo TAB_8.'<td align="center">'."\n";
								//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
								echo TAB_9.'<span style="display: none;">'.$item_info['cat_id'].'</span>'. "\n";

								echo TAB_9.'<select name="cat_id_'.$count.'">' ."\n";
								

									if ( $cat_id == '0') { $selected = ' selected="selected"';}
									else { $selected = '';}
									echo TAB_5.'<option value="0"'.$selected.' >(all categories)</option>'."\n";

									foreach ($cat_listing as $list_cat_id => $list_cat_name)
									{
										if ($list_cat_id == $item_info['cat_id']) 
										{$selected = ' selected="selected"';}						
										else {$selected = '';}
									
										echo TAB_7.'<option'.$selected.' value="'.$list_cat_id.'" >'.$list_cat_name.'</option>'."\n";
												
									}
										
								echo TAB_9.'</select>' ."\n";	
				
							echo TAB_8.'</td align="center">'."\n";	

							//	Image
							echo TAB_8.'<td align="center">'."\n";
							

								//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
								echo TAB_9.'<span style="display: none;">'.$item_info['image_file'].'</span>'. "\n";
								
								echo TAB_9.'<a href="'.$edit_item_href.'?mod_id='.$_GET['e'].'&amp;item_id='.$item_info['item_id'].'&amp;tab=2"'."\n";	
								echo TAB_9.' rel="CMS_ColorBox_EditShopItemImage" title="Edit '.$settings_info['item_alias'].': '.$item_info['item_name'].'">'."\n";
								if ($item_info['image_file'] != '' AND $item_info['image_file'] != NULL)
								{
									echo TAB_10.'<img class="Icon20x20" src="/_images_user/brochure/'.$item_info['image_file'].'" />'."\n";
								}
								else 
								{
									echo TAB_10.'<img src="/images_misc/icon_No_image_24.jpg" />'."\n";
								}

								echo TAB_9.'</a>'."\n";	
							echo TAB_8.'</td>'."\n";
							
							//	Edit link
							echo TAB_8.'<td align="center">'."\n";
								echo TAB_9.'<a href="'.$edit_item_href.'?mod_id='.$_GET['e'].'&amp;item_id='.$item_info['item_id'].'&amp;tab=1"'."\n";
								echo TAB_9.'rel="CMS_ColorBox_EditShopItem" title="Edit '.$settings_info['item_alias'].': '.$item_info['item_name'].'">'."\n";	
									echo TAB_10.'<img src="/images_misc/icon_edit_16x16.png" />'."\n";
								echo TAB_9.'</a>'."\n";	
							echo TAB_8.'</td>'."\n";
							
						
							//	Delete
							echo TAB_8.'<td align="center">'."\n";

								echo TAB_9.'<p>' ."\n";
								
									//	check-box
									echo TAB_10.'<input type="checkbox" name="delete[]" value="'.$item_info['item_id'].'" /> ' ."\n";			
									echo TAB_10.'<a href="#" class="ConfirmDeleteButton" title="Delete '.$settings_info['item_alias'].': '.$item_info['item_name'].'">'."\n";	
										echo TAB_11.'<img src="/images_misc/icon_delete_16x16.png" />'."\n";
									echo TAB_10.'</a>'."\n";								
								echo TAB_9.'</p>'."\n";
								
								echo TAB_9.'<p class="WarningMSGSmall HideAtStart" style="border: solid 1px #cccccc; padding:5px;">' ."\n";
									
									//	Cancel link
									echo TAB_10.'<a href="#" class="CloseThisPanel" title="Do NOT Delete">' ."\n";
										echo TAB_11.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="float:right;"/>' ."\n";
									echo TAB_11.'</a>' ."\n";							
									
									//	OK DELETE Mod		
									echo TAB_11.'Confirm:<input type="submit" name="submit_delete_item_'.$count.'" style="color:#cc0000;"'."\n";
										echo TAB_12.' value="DELETE" title="Delete this '.$settings_info['item_alias'].'" />'."\n";
									
								echo TAB_9.'</p>' ."\n";	

							echo TAB_8.'</td>'."\n";						
							
						echo TAB_7.'</tr>'."\n";
					
						$num_records = $count;
						$count++;
					
					}
						echo TAB_6.'<tfoot>'."\n";
							echo TAB_7.'<tr>'."\n";
								echo TAB_8.'<td colspan="3"></td>'."\n";
								
								echo TAB_8.'<td align="center">'."\n";
									if ( $all_checked_active == 1)
									{ $checked = 'checked="checked"'; }
									else { $checked = ''; }
								
									echo TAB_9.'<input class="check_all" name="active[]" type="checkbox" value="all" '.$checked.'/>'."\n";
									echo TAB_9.'<br/>select all'."\n";
								echo TAB_8.'</td>'."\n";
								
								echo TAB_8.'<td colspan="4"></td>'."\n";
								
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
	
	}
		
?>