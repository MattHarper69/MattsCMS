<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$this_page = $_SERVER['PHP_SELF'] . '?e='.$_GET['e'] . '&amp;tab='.$_GET['tab'];

	$cat_details = array();
	$cat_id = '';
	if (isset($_REQUEST['cat_id']))
	{
		$cat_id = $_REQUEST['cat_id'];
	}
	
	$return_url = $this_page . '&cat_id='.$cat_id;

	
	//---------Update error msg:
	include_once ('cms_includes/cms_msg_update.php');

	echo TAB_2.'<form action="'.$this_page.'" method="post" enctype="multipart/form-data" >'."\n";
		
		echo TAB_3.'<fieldset class="AdminForm2" style="height:20px; margin-bottom:10px;">' ."\n";	
				
			//	Get All items
			$sql_statement = 'SELECT item_id , item_name FROM sweeps_items'

									.' WHERE mod_id = "'.$_GET['e'].'"'
									.' ORDER BY item_name'
									;

			$mysql_err_msg = ''.SHOP_ITEM_ALIAS.' Listing Information not found';						
			$all_items_result = ReadDB ($sql_statement, $mysql_err_msg);

			$num_all_items = mysql_num_rows($all_items_result);			
			
			//	Select Categories by Category
			$all_cat_details = FillTheCatList(0,0);	//-----function to list all cats and sub-cats			
			
			if (count($all_cat_details) < 1)
			{
				echo TAB_5.'<span class="WarningMSGSmall" >No Categories created'
							.' - you will need to create at least one Category before any '.SHOP_ITEM_ALIAS.'s can be assigned to it.</span> ';
			}
			elseif ($num_all_items < 1)
			{
				echo TAB_5.'<span class="WarningMSGSmall" >No '.SHOP_ITEM_ALIAS.'s created'
							.' - you will need to create at least one '.SHOP_ITEM_ALIAS.' before it can be assigned.</span> ';
			}			
			else
			{
				if (count($all_cat_details) == 1)
				{
					$cat_id = $all_cat_details[0][0];				
				}
				
				else
				{
					echo TAB_4.'List Category Assignments by Category: <select onchange="location.href=this.value;">' ."\n";
					
					$cat_details = FillTheCatList(0,0);	//-----function to list all cats and sub-cats

					if (!isset($_REQUEST['cat_id']))
					{
						echo TAB_5.'<option value=""></option>'."\n";					
					}

					for ($i = 0; $i  < count($cat_details); $i++)
					{
						//	Indent Sub Categories
						$indent = '';
						for ($j = 0; $j < $cat_details[$i][7]; $j++) {$indent .= '&nbsp;&nbsp;';} 						
						{				
							if ( $cat_details[$i][0] == $cat_id) 
							{ 
								$selected = ' selected="selected"';
							}
							else 
							{ 
								$selected = '';	
							}
							echo TAB_6.'<option value="'.$this_page.'&amp;cat_id='.$cat_details[$i][0].'"'.$selected.' >'
										.$indent.'-&nbsp;'.$cat_details[$i][1].'</option>'."\n";	

						}				

					}
							
				echo TAB_4.'</select>' ."\n";			
				}
				

			}
			

				
			if ($cat_id != '')
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
					//	Do thumb image of cat
					if (file_exists('..'.$img_url) AND $cat_info['image_file'] != '')
					{
						echo TAB_5.'<img class="Icon20x20" src="'.$img_url.'" />'."\n";			
					}
					//echo TAB_4.'<strong>' ."\n";				
					//	name of cat
					echo TAB_5.' <span class="Notice">&quot'.$this_cat_name.'&quot</span>' ."\n";

					//	in-Active warning
					if ($cat_info['active'] == '')
					{
						echo TAB_5.'<span class="WarningMSG" >( Not Active )</span>';
					}
				
					//echo TAB_4.'</strong>' ."\n";
					
					//	Edit Cat button ------------------------------			
					$href = '/CMS/cms_edit_mods/sweeps/cms_sweeps_edit_category.php';
					echo TAB_5.'<a  href="'.$href.'?cat_id='.$cat_id.'"' ."\n";
						echo TAB_6.'rel="CMS_ColorBox_EditShopCat" title="Edit this Category&#39;s data" >' ."\n";
						echo TAB_6.'<img src="/images_misc/icon_edit_16x16.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
					echo TAB_5.'</a>'. "\n";
					
				}
									
			}

		echo TAB_3.'</fieldset>' ."\n";		
		
	echo TAB_2.'</form>' ."\n";		


	if ($cat_id != '' AND $cat_exists)
	{

		$update_url = '/CMS/cms_update/cms_update_sweeps_Assignments.php';
		echo TAB_2.'<form action="'.$update_url.'"  method="post" enctype="multipart/form-data" >'."\n";		
		
			//	ADD an item to this category	========================================================================
			echo TAB_3.'<p><a class="OpenCloseNextDiv" href="#">'."\n";
				echo TAB_4.'<img src="/images_misc/icon_add_24x24.png" alt="Clone" />'."\n";
				echo TAB_4.'[ Add New '.SHOP_ITEM_ALIAS.'s to this Category ]'."\n";
			echo TAB_3.'</a></p>'."\n";	
			echo TAB_3.'<div class="AdminForm2 HideAtStart" >'."\n";
				echo TAB_4.'<p>Add '.SHOP_ITEM_ALIAS.'s to this Category: &quot'.$this_cat_name.'&quot</p> '."\n";
									
				if($num_all_items > 0)
				{	


					echo TAB_4.'<script type="text/javascript">

						$(document).ready(function() {

							$("#AddItems").asmSelect({
								addItemTarget: "bottom",
								animate: true,
								highlight: true,
								sortable: true

								}).after($("<a href=\'#\'>[ Select All ]</a>").click(function() {
									$("#AddItems").children().attr("selected", "selected").end().change();
									return false;
								})); 

							$("#SubmitAddProd").css("display" , "none");
							$("#AddItems").change( function(){
							
								$("#SubmitAddProd").css("display" , "inline");
							
							});
							
							
						}); 

					</script>' ."\n";


				
					echo TAB_4.'<select id="AddItems" name="add_item[]" multiple="multiple" title="Select '.SHOP_ITEM_ALIAS.'s to add" >' ."\n";

						//echo TAB_5.'<option value="" selected="selected" ></option>'."\n";

						while ($all_items = mysql_fetch_array($all_items_result))
						{
							echo TAB_5.'<option value="'.$all_items['item_id'].'">'.$all_items['item_name'].'</option>'."\n";
						}
						
		
					echo TAB_4.'</select>' ."\n";

					echo TAB_4.'<input type="submit" id="SubmitAddProd" name="submit_add_prod"'
								.' value="Add Selected '.SHOP_ITEM_ALIAS.'s" title="Add this '.SHOP_ITEM_ALIAS.' to: '.$this_cat_name.'" />' . "\n";
				}
				else
				{
					echo TAB_4.'<span class="WarningMSG" >( No '.SHOP_ITEM_ALIAS.'s have been created )</span>';
				}
				
			echo TAB_3.'</div>'."\n";		
		
			//	get all products (Item assignments) from db
			$mysql_err_msg = ''.SHOP_ITEM_ALIAS.' Listing Information not found';			
			$sql_statement = 'SELECT'
										.'  sweeps_items.item_id'
										.', item_name'
										.', prod_id'
										.', primary_image_id'
										.', active'
										
											.' FROM sweeps_items, sweeps_cat_asign'

									.' WHERE sweeps_cat_asign.cat_id = '.$cat_id
									.' AND sweeps_items.item_id = sweeps_cat_asign.item_id'
									.' AND mod_id = "'.$_GET['e'].'"'
									.' ORDER BY sweeps_cat_asign.seq'
									;

			$prod_info_result = ReadDB ($sql_statement, $mysql_err_msg);		
				
			$num_prods_found = mysql_num_rows($prod_info_result);
			
		if($num_prods_found < 1)
		{
			echo TAB_3.'<fieldset class="AdminForm2">' ."\n";
				echo TAB_4.'<h2>No Assignments found</h2>' ."\n";
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
							alert("You have selected Remove ALL:" 
									+ "\n - Clicking the Update Button will Remove ALL displayed '.SHOP_ITEM_ALIAS.'s from this Category");
						}
							
					});					
				});							

			</script>'."\n";			
			
			echo TAB_3.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
				echo TAB_4.'<legend class="Centered" >'."\n";
					
					//-------------UPDATE BUTTON------------------------------------
					echo TAB_5.'<input type="submit" name="update_all" value="Update ALL displayed Assignments" />'."\n";			
				echo TAB_4.'</legend>'."\n";

				//	RESET button ======================================================				
				echo TAB_4.'<a  href="'.$this_page.'&cat_id='.$cat_id.'"'."\n";
					echo TAB_5.' title="Reload this page to Reset all '.SHOP_ITEM_ALIAS.' data" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";					
				
				$sort_class = '';
				if($num_prods_found == 1)
				{
					echo TAB_4.'<h3>1 '.SHOP_ITEM_ALIAS.' is Assigned to this Category: &quot;'.$this_cat_name.'&quot;</h3>'."\n";
				}
							
				elseif($num_prods_found > 1)
				{
					echo TAB_4.'<h3>'.$num_prods_found.' '.SHOP_ITEM_ALIAS.'s are Assigned to this Category: &quot;'.$this_cat_name.'&quot;</h3>'."\n";
					echo TAB_4.'<p class="Small" >( You can drag these '.SHOP_ITEM_ALIAS.'s to change their order )</p>'."\n";
					$sort_class = ' class="sortableItems"';
				}
							
				echo TAB_4.'<ol'.$sort_class.' style="margin:20px;" >'."\n";
				
				
				$count = 1;
				$prodPosArray = '';
				while ($prod_info = mysql_fetch_array($prod_info_result))
				{						
					echo TAB_5.'<li id="'.$prod_info['prod_id'].'" class="ListItemHolder" >'."\n";
							
						//	item primary image
						$sql_statement = 'SELECT image_file_name FROM sweeps_item_images, sweeps_items'

																.' WHERE sweeps_items.primary_image_id = sweeps_item_images.image_id'
																.' AND sweeps_items.item_id = '.$prod_info['item_id'];
							
						$image_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

						if ($image_info['image_file_name'] != '' AND $image_info['image_file_name'] != NULL)
						{
							echo TAB_6.'<img class="Icon20x20" src="/_images_shop/'.$image_info['image_file_name'].'" />'."\n";
						}
						else 
						{
							echo TAB_6.'<img src="/images_misc/icon_No_image_24.jpg" />'."\n";
						}	
						
						//	item name
						echo TAB_6.'<span style="margin: 0 10px;" >'.$prod_info['item_name'].'</span>'."\n";
					
						//	Edit item button ------------------------------
						$href = '/CMS/cms_edit_mods/sweeps/cms_sweeps_edit_product_index.php';
						echo TAB_6.'<a  href="'.$href.'?item_id='.$prod_info['item_id'].'&amp;mod_id='.$_GET['e'].'&amp;tab=1"' ."\n";
							echo TAB_7.' rel="CMS_ColorBox_EditShopItem" title="Edit this '.SHOP_ITEM_ALIAS.'s&#39; data" >' ."\n";
							echo TAB_7.'<img src="/images_misc/icon_edit_16x16.png" alt="Edit" style="padding-right:10px;"/>' ."\n";
						echo TAB_6.'</a>'. "\n";
						
						//	remove from category (link)
						echo TAB_6.'<a href="'.$update_url.'?e='.$_GET['e'].'&tab='.$_GET['tab'].'&cat_id='.$cat_id.'&remove_prod='.$prod_info['prod_id'].'"'
									.' title="Remove this '.SHOP_ITEM_ALIAS.' from the '.$this_cat_name.' category">'."\n";
							echo TAB_7.'<img src="/images_misc/icon_close_16x16.png" alt="Remove"' 
									.' style="padding-right:10px; float:right;"/>' ."\n";
						echo TAB_6.'</a>' ."\n";
						
						//	remove from category (select)
						echo TAB_6.'<span style="float: right;margin: 0 15px; cursor:default;" >'."\n";								
							echo TAB_7.'<input type="checkbox" name="delete[]" class="CheckAllDelete" value="'.$prod_info['prod_id'].'"'
								.' title="Tick this box to Remove this '.SHOP_ITEM_ALIAS.' from the '.$this_cat_name.' category" /> Remove'."\n";
						echo TAB_6.'</span>'."\n";
												
						//	move to another category	
						echo TAB_6.'<span style="float: right;margin: 0 10px; cursor:default;" >Move to: '."\n";
						
							echo TAB_7.'<select name="move_prod_'.$prod_info['prod_id'].'"  title="Move this '.SHOP_ITEM_ALIAS.' to another Category">' ."\n";

								//	default option (existing category)
								echo TAB_8.'<option value="'.$cat_id.'" selected="selected" ></option>'."\n";					

							for ($i = 0; $i  < count($cat_details); $i++)
							{
								//	Indent Sub Categories
								$indent = '';
								for ($j = 0; $j < $cat_details[$i][7]; $j++) 
								{$indent .= '&nbsp;&nbsp;';} 						
												
								if ( $cat_details[$i][0] != $cat_id) 
								{ 
									echo TAB_8.'<option value="'.$cat_details[$i][0].'" >'.$indent.'-&nbsp;'.$cat_details[$i][1].'</option>'."\n";	
								}

							}
									
							echo TAB_7.'</select>' ."\n";

						echo TAB_6.'</span>'."\n";
						
					echo TAB_5.'</li>'."\n";
					
					//$prodPosArray = '';
					if ($count == 1) {$prodPosArray .= $prod_info['prod_id'];}
					else {$prodPosArray .= ','.$prod_info['prod_id'];}
					
					$count++;						
					
				}

				echo TAB_4.'</ol>'."\n";
				
				if($num_prods_found > 1)
				{
					//	Select all to REMOVE
					echo TAB_4.'<fieldset class="AdminForm3" style="clear: both;" title="tick this to select all '.SHOP_ITEM_ALIAS.'s for Removal" >'."\n";
						echo TAB_5.'<input type="checkbox" name="delete[]" class="CheckAll CheckAllDelete"'
							.' id="CheckAllDeleteMaster" value="all" />'."\n";
						echo TAB_5.'<span class="WarningMSG" >Remove ALL</span>'."\n";
					echo TAB_4.'</fieldset>'."\n";			
				}

			
			echo TAB_3.'</fieldset>' ."\n";

			echo TAB_3.'<p class="Small" >* Removed '.SHOP_ITEM_ALIAS.'s are only Removed from the Category and not Deleted</p>'."\n";
					
			//	this is used to update image order
			echo TAB_3.'<input type="hidden" id="ItemPosArray" name="prod_pos_array" value="' . $prodPosArray . '" />'."\n";
			
		}

			
			echo TAB_3.'<input type="hidden" name="cat_id" value="'.$cat_id.'" />'."\n";		// needed for adding items
			echo TAB_3.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";	
			
		echo TAB_2.'</form>' ."\n";		

	}

?>