<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'] . '&tab='.$_GET['tab'];
	
	if (isset($_POST['submit_delete_cat']))
	{
		require_once ('cms_update/cms_update_sweeps_functions.php');
		DeleteCategory ($_POST['del_cat_id']);
		
		//	Re-Direct BACK
		header('location: '.$this_page); 
		exit();	
	}

	
	$cat_id = '';
	if (isset($_REQUEST['cat_id']))
	{
		$cat_id = $_REQUEST['cat_id'];
	}
	
	$return_url = $this_page . '&cat_id='.$cat_id;

	
	//---------Update error msg:
	include_once ('cms_includes/cms_msg_update.php');
	
	echo TAB_2.'<form action="'.$this_page.'" method="post" enctype="multipart/form-data" >'."\n";
		
		echo TAB_3.'<fieldset class="AdminForm2">' ."\n";	
				
			//	Select Categories by Category
			$all_cat_details = FillTheCatList(0,0);	//-----function to list all cats and sub-cats			
			
			if (count($all_cat_details) < 1)
			{
				echo TAB_5.'<span class="WarningMSG" >( There are no Categories created ) : </span> ';
			}
			
			else
			{
						
				echo TAB_4.'List Categories by the Parent Category: <select onchange="location.href=this.value;">' ."\n";
 
				if (!isset($_REQUEST['cat_id']))
				{					
					echo TAB_5.'<option value="" selected="selected" ></option>'."\n";					
				}
				

				
				if ($cat_id == 0 AND $cat_id != '') { $selected = ' selected="selected"';}
				else { $selected = '';}
				echo TAB_5.'<option value="'.$this_page.'&amp;cat_id=0"'.$selected.' >(all categories)</option>'."\n";
					
				for ($i = 0; $i  < count($all_cat_details); $i++)
				{
					//	Indent Sub Categories
					$indent = '';
					for ($j = 0; $j < $all_cat_details[$i][7]; $j++) 
					{$indent .= '&nbsp;&nbsp;';} 						
		
					if ( $all_cat_details[$i][0] == $cat_id) 
					{ 
						$selected = ' selected="selected"';
					}
					else 
					{ 
						$selected = '';	
					}
					echo TAB_5.'<option value="'.$this_page.'&amp;cat_id='.$all_cat_details[$i][0].'"'.$selected.' >'
								.$indent.'-&nbsp;'.$all_cat_details[$i][1].'</option>'."\n";	

				}
							
				echo TAB_4.'</select> OR ' ."\n";
		
			}
			
				
			
			//	Add new Category Link
			$href = '/CMS/cms_edit_mods/sweeps/cms_sweeps_edit_category.php';
			echo TAB_5.'<a class="ButtonLink" href="'.$href.'?cat_id=new"' ."\n";
				echo TAB_6.' rel="CMS_ColorBox_EditShopCat" title="Add a new Category" >Add New Category</a>' ."\n";				
				

			
			if ($cat_id !='' AND $cat_id != 0)
			{

				$sql_statement = 'SELECT cat_name, image_file, active FROM sweeps_categories'

											.' WHERE cat_id = "'.$cat_id.'"'
											;

				$mysql_err_msg = ''.SHOP_ITEM_ALIAS.' Listing Information not found';						
				$cat_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
				
				$cat_exists = mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg));
				$this_cat_name = $cat_info['cat_name'];
				$img_url = '/_images_shop/'.$cat_info['image_file'];
					
				if ($cat_exists)
				{
					echo TAB_3.'<h2>' ."\n";
					
						//	Do thumb image of cat
						if (file_exists('..'.$img_url) AND $cat_info['image_file'] != '')
						{
								echo TAB_5.'<img class="Icon20x20" src="'.$img_url.'" />'."\n";			
						}
						
						//	name of cat
						echo TAB_5.' <span class="Notice">&quot'.$this_cat_name.'&quot</span>' ."\n";

						//	in-Active warning
						if ($cat_info['active'] == '')
						{
							echo TAB_5.'<span class="WarningMSG" >( Not Active )</span>';
						}
						

							
						//	Edit Cat button ------------------------------			
						$href = '/CMS/cms_edit_mods/sweeps/cms_sweeps_edit_category.php';
						echo TAB_5.'<a  href="'.$href.'?cat_id='.$cat_id.'"' ."\n";
							echo TAB_6.' rel="CMS_ColorBox_EditShopCat" title="Edit this Category&#39;s data" >' ."\n";
							echo TAB_6.'<img src="/images_misc/icon_edit_16x16.png" alt="Reset" style="margin-left:10px;"/>' ."\n";
						echo TAB_5.'</a>'. "\n";
						
						//	Delete BUTTON------------------------------------
						echo TAB_5.'<span style="float:right;">' ."\n";
										
							echo TAB_6.'<a href="#" class="ConfirmDeleteButton" title="Delete this Category: '.$this_cat_name.'" >'."\n";
								echo TAB_7.'<img src="/images_misc/icon_delete_24x24.png" alt="Delete" />' ."\n";
							echo TAB_6.'</a>'. "\n";

						echo TAB_5.'</span>' ."\n";
								
						echo TAB_5.'<span class="WarningMSG HideAtStart" style="border: solid 1px #cccccc; padding:5px;">' ."\n";
										
							//	OK DELETE Mod
							echo TAB_6.'Confirm: <input type="submit" name="submit_delete_cat" style="color:#cc0000;"'."\n";	 
								echo TAB_7.' value="DELETE" title="Delete this Category" /> this Category'."\n";	
										
							//	Cancel link
							echo TAB_6.'<a href="#" class="CloseThisPanel" title="Do NOT Delete">' ."\n";
								echo TAB_7.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="padding:10px;"/>' ."\n";
							echo TAB_6.'</a>' ."\n";
											
						echo TAB_5.'</span>' ."\n";	
						echo TAB_5.'<input type="hidden" name="del_cat_id" value="'.$cat_id.'" />'."\n";
						
					echo TAB_3.'</h2>' ."\n";
						
						
				}
							
				
			}

		echo TAB_3.'</fieldset>' ."\n";		
		
	echo TAB_2.'</form>' ."\n";	
	
	
	
	if ($cat_id != '')
	{
		$update_url = '/CMS/cms_update/cms_update_sweeps_Categories.php';
		echo TAB_2.'<form action="'.$update_url.'" name="update" method="post" enctype="multipart/form-data" >'."\n";

		$cats_in_cat_details = FillTheCatList($cat_id,0);	//-----function to list all cats and sub-cats	
	
		if (count($cats_in_cat_details) < 1)
		{
			echo TAB_3.'<fieldset class="AdminForm2">' ."\n";
				echo TAB_4.'<h2>There are No Categories listed in this Category</h2>' ."\n";
			echo TAB_3.'</fieldset>' ."\n";	
		}

		else
		{		
			echo TAB_3.'<script type="text/javascript" language="javascript">
					
				$(document).ready(function(){
					
					//	Warning All selected for Deletion
					$("#CheckAllDeleteMaster:checkbox").click(function() 
					{
						if($("#CheckAllDeleteMaster").is(":checked"))							
						{
							alert("You have selected Delete ALL:"
								+ "\n - Clicking the Update Button will Delete ALL displayed Categories");
						}
							
					});
										
				});							

			</script>'."\n";	
		
			echo TAB_3.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
				echo TAB_4.'<legend class="Centered" >'."\n";
					
					//-------------UPDATE BUTTON------------------------------------
					echo TAB_5.'<input type="submit" name="update_all" value="Update ALL displayed Categories" />'."\n";			
				echo TAB_4.'</legend>'."\n";

				//	RESET button ======================================================				
				echo TAB_4.'<a href="'.$this_page.'&cat_id='.$cat_id.'"'."\n";
					echo TAB_5.' title="Reload this page to Reset all Category data" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";					
				

				if(count($cats_in_cat_details > 1))
				{
					echo TAB_4.'<p class="Small" >( You can drag these Categories to change their order )</p>'."\n";
					$sort_class = ' class="sortableItems"';
				}
					
				SubCatListDisplay ($cat_id, 0);
					
				if(count($cats_in_cat_details > 1))
				{
					//	Select all to REMOVE
					echo TAB_4.'<fieldset class="AdminForm3" style="clear: both;" title="tick this to select all '.SHOP_ITEM_ALIAS.'s for Removal" >'."\n";
						echo TAB_5.'<input type="checkbox" name="delete[]" class="CheckAll CheckAllDelete" id="CheckAllDeleteMaster" value="all" />'."\n";
						echo TAB_5.'<span class="WarningMSG" >Delete ALL</span>'."\n";
					echo TAB_4.'</fieldset>'."\n";			
				}
					
			echo TAB_3.'</fieldset>' ."\n";	

			echo TAB_3.'<p class="Small" >* Deleted Categories will have all '.SHOP_ITEM_ALIAS.'s and Categories removed from them (But not Deleted)</p>'."\n";
			
		}
		

			//	this is used to update image order
			//echo TAB_3.'<input type="hidden" id="ItemPosArray" name="cat_pos_array" value="' . $CatPosArray . '" />'."\n";
			
			echo TAB_3.'<input type="hidden" name="cat_id" value="'.$cat_id.'" />'."\n";		// needed for adding items
			echo TAB_3.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";	
			
		echo TAB_2.'</form>' ."\n";	
		
				
	}
	
//====================================================================================================
	
//------- Sub Category Function:

	function SubCatListDisplay ($parent_id, $level)
	{

		$mysql_err_msg = 'Category information unavailable';
		$sql_statement = 'SELECT * FROM sweeps_categories WHERE parent_id = '.$parent_id.' ORDER BY seq';
					
		$result = ReadDB ($sql_statement, $mysql_err_msg);
	
		$tab = '';
		if(mysql_num_rows($result) > 0)
		{
		
			for($t = 0; $t < $level; $t++)	
			{ $tab .= '  ';}
			
			echo TAB_4.$tab.'<ol class="sortableItems_'.$parent_id.'" style="margin-left: 25px;" >'."\n";
		
			$count = 1;
			$CatPosArray = '';
			while ($cat_details = mysql_fetch_array ($result))
			{						
	//	===========================	EDITED TO HERE	=======================================================================

			//		Do hide / show child categories ????
	
				echo TAB_5.$tab.'<li id="'.$cat_details['cat_id'].'" >'."\n";			

					echo TAB_6.$tab.'<span class="ListItemHolder">'."\n";
					
						$img_file = $cat_details['image_file'];
						if ($img_file != '' AND $img_file != NULL AND file_exists('../_images_shop/'.$img_file))
						{
							echo TAB_7.$tab.'<img class="Icon20x20" src="/_images_shop/'.$img_file.'" />'."\n";
						}
						else 
						{
							echo TAB_7.$tab.'<img src="/images_misc/icon_No_image_24.jpg" />'."\n";
						}	
						
						//	item name
						echo TAB_7.$tab.'<span style="margin: 0 10px;" >'.$cat_details['cat_name'].'</span>'."\n";					

										//	Warn if not Active
						if ($cat_details['active'] != 'on')
						{
							echo TAB_7.'<span class="WarningMSGSmall" > (Not Active)</span>';
						}
						
						//	Edit Category button ------------------------------
						$href = '/CMS/cms_edit_mods/sweeps/cms_sweeps_edit_category.php';
						echo TAB_7.$tab.'<a href="'.$href.'?cat_id='.$cat_details['cat_id'].'"' ."\n";
							echo TAB_8.$tab.' rel="CMS_ColorBox_EditShopCat"'
									.' title="Edit this Category: &quot;'.$cat_details['cat_name'].'&quot;" >' ."\n";
							echo TAB_8.$tab.'<img src="/images_misc/icon_edit_16x16.png" alt="Edit" style="padding-right:10px;"/>' ."\n";
						echo TAB_7.$tab.'</a>'. "\n";

						
						//	N# of Items
						$sql_statement = 'SELECT * FROM sweeps_cat_asign WHERE cat_id = "'.$cat_details['cat_id'].'"';					
						$num_prods_found = mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg));						
						
						if ($num_prods_found > 0)
						{
							$href = '/CMS/cms_edit_mod_data.php?e='.$_GET['e'].'&amp;tab=4&amp;cat_id='.$cat_details['cat_id'];
							echo TAB_7.$tab.'<a class="Small" href="'.$href.'"' ."\n";
								echo TAB_8.$tab.'title="Configure this Category&#38;s assigned '.SHOP_ITEM_ALIAS.'s" >' ."\n";
								echo TAB_8.$tab.'('.$num_prods_found.' '.SHOP_ITEM_ALIAS.'s)' ."\n";							
							echo TAB_7.$tab.'</a>'. "\n";							
						}

						
						//	Delete this category (select)
						echo TAB_7.$tab.'<span class="WarningMSGSmall" style="float: right;margin: 0 5px; cursor:default;" >'."\n";						
							echo TAB_8.$tab.'<input type="checkbox" name="delete[]" class="CheckAllDelete" value="'.$cat_details['cat_id'].'"'
								.' title="Tick this box to Delete this Category" /> Delete'."\n";
						echo TAB_7.$tab.'</span>'."\n";
		

						//	move to another category	
						echo TAB_7.$tab.'<span class="Small" style="float: right;margin: 0 5px; cursor:default;" >Move to:'."\n";
						
							echo TAB_8.$tab.'<select name="move_cat_'.$cat_details['cat_id'].'" onChange="this.form.submit();"'
											.' title="Move this Category to another Category">' ."\n";
							
							$list_cat_details = FillTheCatList(0,0);	//-----function to list all cats and sub-cats

							//	default option (existing category)
							echo TAB_9.$tab.'<option value="dontmove" selected="selected" ></option>'."\n";

							echo TAB_9.$tab.'<option value="0" >[No Parent]</option>'."\n";
							
							for ($i = 0; $i  < count($list_cat_details); $i++)
							{
								//	Indent Sub Categories
								$indent = '';
								for ($j = 0; $j < $list_cat_details[$i][7]; $j++) 
								{$indent .= '&nbsp;&nbsp;';} 						
												
								if ( $list_cat_details[$i][0] != $cat_details['cat_id']) 
								{ 
									echo TAB_9.$tab.'<option value="'.$list_cat_details[$i][0].'" >'
												.$indent.'-&nbsp;'.$list_cat_details[$i][1].'</option>'."\n";	
								}

							}
									
							echo TAB_8.$tab.'</select>' ."\n";
							
							//	need old parent id for adjusting child parent IDs
							echo TAB_8.'<input type="hidden" name="old_parent_for_'.$cat_details['cat_id'].'" value="'.$cat_details['parent_id'].'" />'."\n";

						echo TAB_7.$tab.'</span>'."\n";	
						
					echo TAB_6.$tab.'</span>'."\n";	
							
					$level = $level + 2;
					
					SubCatListDisplay ($cat_details['cat_id'], $level);
					
					$level = $level - 2;
					
				echo TAB_5.$tab.'</li>'."\n";
				
				if ($count == 1) {$CatPosArray .= $cat_details['cat_id'];}
				else {$CatPosArray .= ','.$cat_details['cat_id'];}
					
				$count++;					
								
			}
				
			echo TAB_4.$tab.'</ol>'."\n";
			
			echo TAB_4.$tab.'<script type="text/javascript" language="javascript">
					
				$(document).ready(function(){
					
					$( ".sortableItems_'.$parent_id.'" ).sortable(
					{
						 opacity: 0.6
						,stop: function(){

						
							var ItemPosArray = $(".sortableItems_'.$parent_id.'").sortable("toArray");
							$("#ItemPosArray_'.$parent_id.'").attr("value", ItemPosArray)

						}
						,revert: true
					});	
	
				$(".sortableItems_'.$parent_id.'").css("cursor", "move");

				});	

			</script>'."\n";				
	
			
			//	this is used to update Category order
			echo TAB_4.$tab.'<input type="hidden" id="ItemPosArray_'.$parent_id.'"'
							.' name="cat_pos_array_'.$parent_id.'" value="' . $CatPosArray . '" />'."\n";
			
		}

	}	
	
?>