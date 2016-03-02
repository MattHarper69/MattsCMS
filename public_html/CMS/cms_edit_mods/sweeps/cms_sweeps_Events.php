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
	
	$edit_item_href = 'cms_edit_mods/sweeps/cms_sweeps_edit_product_index.php';


	echo TAB_2.'<script type="text/javascript">
		
		$(document).ready( function()
		{
			//	Data Table
			$("#ItemListing").dataTable({
				"bJQueryUI": true,
				"sPaginationType": "full_numbers"
			});
			
			//	Warning All selected for Deletion
				$("#CheckAllDeleteMaster:checkbox").click(function() 
				{
					if($("#CheckAllDeleteMaster").is(":checked"))
				{
					alert("You have selected Delete ALL:"
						+ "\n - Clicking the Update Button will Delete ALL displayed '.SHOP_ITEM_ALIAS.'s");
				}
					
			});			
	
		});
	</script>'."\n";

	//---------Update error msg:
	include_once ('cms_includes/cms_msg_update.php');
	
	echo TAB_2.'<form action="'.$this_page.'" method="post" enctype="multipart/form-data" >'."\n";
		
		echo TAB_3.'<fieldset class="AdminForm2" style="height:20px; margin-bottom:10px;">' ."\n";	
				
	
			//	Select Products by Category
			$cat_details = FillTheCatList(0,0);	//-----function to list all cats and sub-cats
		
			if (count($cat_details) < 2)
			{
				$cat_id = 'all';
			}
			else
			{
				echo TAB_4.'List '.SHOP_ITEM_ALIAS.'s by Category: <select onchange="location.href=this.value;">' ."\n";
				
					if ($cat_id == '')
					{
						echo TAB_5.'<option value=""></option>'."\n";					
					}

						if ( $cat_id == 'all') { $selected = ' selected="selected"';}
						else { $selected = '';}
						echo TAB_5.'<option value="'.$this_page.'&amp;cat_id=all"'.$selected.' >(all categories)</option>'."\n";
						
					for ($i = 0; $i  < count($cat_details); $i++)
					{
						//	Indent Sub Categories
						$indent = '';
						for ($j = 0; $j < $cat_details[$i][7]; $j++) 
						{$indent .= '&nbsp;&nbsp;';} 						
										
						if ( $cat_details[$i][0] == $cat_id) { $selected = ' selected="selected"';}
						else { $selected = '';}
						echo TAB_5.'<option value="'.$this_page.'&amp;cat_id='.$cat_details[$i][0].'"'.$selected.' >'
										.$indent.'-&nbsp;'.$cat_details[$i][1].'</option>'."\n";	

					}
						
				echo TAB_4.'</select> OR ' ."\n";			
			}


			
			//	Add new Product Link
			echo TAB_4.'<a class="ButtonLink" href="'.$edit_item_href.'?item_id=new&amp;mod_id='.$_GET['e'].'"'
						.' rel="CMS_ColorBox_EditShopItem" title="Add a new '.SHOP_ITEM_ALIAS.'" > Add New '.SHOP_ITEM_ALIAS.'</a>' ."\n";
		
		echo TAB_3.'</fieldset>' ."\n";	
		
		
	echo TAB_2.'</form>' ."\n";
					
	if ($cat_id != '' AND $cat_id != 'all')
	{

		$sql_statement = 'SELECT'
										.'  sweeps_items.item_id'
										.', item_name'
										.', item_code'
										.', price'
										.', in_stock'
										.', primary_image_id'
										.', active'
										
											.' FROM sweeps_items, sweeps_cat_asign'

									.' WHERE cat_id = '.$cat_id
									.' AND sweeps_items.item_id = sweeps_cat_asign.item_id'
									.' AND mod_id = "'.$_GET['e'].'"'
									.' ORDER BY sweeps_cat_asign.seq'
									;
	}
	
	elseif ($cat_id == 'all')
	{
		$sql_statement = 'SELECT DISTINCT'

										.'  sweeps_items.item_id'
										.', item_name'
										.', item_code'
										.', price'
										.', in_stock'
										.', primary_image_id'
										.', active'

									.' FROM sweeps_items'
									
									.' WHERE mod_id = "'.$_GET['e'].'"' 
									.' ORDER BY primary_cat_id, item_name'
									;
	}		
	
	$mysql_err_msg = ''.SHOP_ITEM_ALIAS.' Listing Information not found';			

	$item_info_result = ReadDB ($sql_statement, $mysql_err_msg);		
		
	$num_items_found = mysql_num_rows($item_info_result);
	
	if($num_items_found < 1)
	{
		echo TAB_3.'<fieldset class="AdminForm2">' ."\n";
			echo TAB_4.'<h2>No '.SHOP_ITEM_ALIAS.'s found</h2>' ."\n";
		echo TAB_3.'</fieldset>' ."\n";	
	}

	elseif ($cat_id !='' AND $num_items_found > 0)
	{
		
		echo TAB_3.'<form action = "'.$update_url.'" method="post" enctype="multipart/form-data" >'."\n";
			echo TAB_4.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
				echo TAB_5.'<legend class="Centered" >'."\n";
					
					//-------------UPDATE BUTTON------------------------------------
					echo TAB_6.'<input type="submit" name="update_all" value="Update ALL displayed '.SHOP_ITEM_ALIAS.'s" />'."\n";			
				echo TAB_5.'</legend>'."\n";

				//	RESET button ======================================================				
				echo TAB_5.'<a  href="'.$this_page.'&amp;cat_id='.$cat_id.'"'."\n";
					echo TAB_6.' title="Reload this page to Reset all '.SHOP_ITEM_ALIAS.' data" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
				echo TAB_5.'</a>'. "\n";				
				
				echo TAB_5.'<table id="ItemListing" class="display">'."\n";

					echo TAB_6.'<thead>'."\n";
						echo TAB_7.'<tr>'."\n";
							echo TAB_8.'<th></th>'."\n";
							echo TAB_8.'<th>Name</th>'."\n";
							echo TAB_8.'<th>Code</th>'."\n";
							echo TAB_8.'<th>Active</th>'."\n";							
							echo TAB_8.'<th>Price</th>'."\n";
							echo TAB_8.'<th>In Stock</th>'."\n";
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
							echo TAB_9.' title="Edit '.SHOP_ITEM_ALIAS.': '.$item_info['item_name'].'">'.$item_info['item_name'].'</a>'."\n";	
						echo TAB_8.'</td>'."\n";	
						
						//	item code
						echo TAB_8.'<td align="center">'."\n";
							echo TAB_9.'<a href="'.$edit_item_href.'?mod_id='.$_GET['e'].'&amp;item_id='.$item_info['item_id'].'&amp;tab=1"'."\n";
								echo TAB_10.' rel="CMS_ColorBox_EditShopItemCode"'."\n";	
							echo TAB_9.' title="Edit '.SHOP_ITEM_ALIAS.': '.$item_info['item_name'].'">'.$item_info['item_code'].'</a>'."\n";	
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
							echo TAB_9.'<input type="checkbox" name="active[]" class="CheckAllActive" '
								.$checked.' value="'.$item_info['item_id'].'"/>'. "\n";	
						echo TAB_8.'</td>'."\n";
												
						//	price
						echo TAB_8.'<td align="center">'."\n";	
							//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
							echo TAB_9.'<span style="display: none;">'.$item_info['price'].'</span>'. "\n";	

							if ($item_info['price'] == 0){$red_text = ' style="color:red;"';}
							else {$red_text = '';}
							
							echo TAB_9.SHOP_CURRENCY_SYMBOL_PREFIX.' <input type="text" name="price_'.$count.'"'.$red_text
									.' value="'.number_format($item_info['price'], 2).'" size="10" maxlength="11" />'
									.SHOP_CURRENCY_SYMBOL_SUFFIX. "\n";	
						echo TAB_8.'</td>'."\n";								

						//	In Stock
						echo TAB_8.'<td align="center">'."\n";
							//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
							echo TAB_9.'<span style="display: none;">'.$item_info['in_stock'].'</span>'. "\n";

							if ($item_info['in_stock'] == 0){$red_text = ' style="color:red;"';}
							else {$red_text = '';}
							
							echo TAB_9.'<input type="text" name="in_stock_'.$count.'"'.$red_text
										.' value="'.$item_info['in_stock'].'" size="8" maxlength="11" />'. "\n";	
						echo TAB_8.'</td align="center">'."\n";	

						//	Image
						echo TAB_8.'<td align="center">'."\n";
						
							$sql_statement = 'SELECT image_file_name FROM sweeps_item_images, sweeps_items'

																		.' WHERE sweeps_items.primary_image_id = sweeps_item_images.image_id'
																		.' AND sweeps_items.item_id = '.$item_info['item_id'];
								
							$image_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));						
						
						
							//	need to put (but hide from viewer) data outside of input so that jquery.tabledata can sort
							echo TAB_9.'<span style="display: none;">'.$image_info['image_file_name'].'</span>'. "\n";								
							echo TAB_9.'<a href="'.$edit_item_href.'?mod_id='.$_GET['e'].'&amp;item_id='.$item_info['item_id'].'&amp;tab=4"'."\n";	
							echo TAB_9.' rel="CMS_ColorBox_EditShopItemImage" title="Edit '.SHOP_ITEM_ALIAS.': '.$item_info['item_name'].'">'."\n";
							if ($image_info['image_file_name'] != '' AND $image_info['image_file_name'] != NULL)
							{
								echo TAB_10.'<img class="Icon30x30" src="/_images_shop/'.$image_info['image_file_name'].'" />'."\n";
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
							echo TAB_9.'rel="CMS_ColorBox_EditShopItem" title="Edit '.SHOP_ITEM_ALIAS.': '.$item_info['item_name'].'">'."\n";	
								echo TAB_10.'<img src="/images_misc/icon_edit_16x16.png" />'."\n";
							echo TAB_9.'</a>'."\n";	
						echo TAB_8.'</td>'."\n";
						
					
						//	Delete
						echo TAB_8.'<td align="center">'."\n";

							echo TAB_9.'<p>' ."\n";
							
								//	check-box
								echo TAB_10.'<input type="checkbox" name="delete[]" class="CheckAllDelete" value="'.$item_info['item_id'].'" /> ' ."\n";			
								echo TAB_10.'<a href="#" class="ConfirmDeleteButton" title="Delete '.SHOP_ITEM_ALIAS.': '.$item_info['item_name'].'">'."\n";	
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
									echo TAB_12.' value="DELETE" title="Delete this '.SHOP_ITEM_ALIAS.'" />'."\n";
								
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
							
								echo TAB_9.'<input type="checkbox" name="active[]" class="CheckAll CheckAllActive"' ."\n";
									echo TAB_10.' value="all" '.$checked.'/>'."\n";
								echo TAB_9.'<br/>select all'."\n";
							echo TAB_8.'</td>'."\n";
							
							echo TAB_8.'<td colspan="4"></td>'."\n";
							
							echo TAB_8.'<td align="center">'."\n";
							
								echo TAB_9.'<input type="checkbox" name="delete[]" class="CheckAll CheckAllDelete"' ."\n";
									echo TAB_10.'id="CheckAllDeleteMaster" value="all" />'."\n";
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