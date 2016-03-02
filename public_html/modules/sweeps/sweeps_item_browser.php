<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	if ITEM set, Do show Item Info
	if (isset ($prod_id))
	{		
		include ('sweeps_item_show.php');
		
		$_SESSION['last_item_id_viewed'] = $prod_id;
		$_SESSION['last_cat_id_viewed'] = $cat_id;				

	}

	
	//	if CAT specified...look Up: all SUB CATs and ITEMS
	elseif (isset ($cat_id) AND $cat_id != '' AND $cat_id != NULL )
	{
		//	if there is only one category DO not show Cat name heading
		if (isset($sweeps_num_cats) AND $sweeps_num_cats > 1)
		{ echo TAB_8.'<h4 class="Shop" >'.$sweeps_cats_name.'</h4>' ."\n";}

		$_SESSION['last_cat_id_viewed'] = $cat_id;

		GetItemsInCat($cat_id);

		//	list all SUb CATS

		ListCatsInCat($cat_id);	

	}
	
	//	if CAT unknown , look Up available CATs
	else
	{
		ListCatsInCat(0);	
	}


			
		//	if there is only one category DO not do "list all cats"
		if (isset($sweeps_num_cats) AND $sweeps_num_cats > 1)
		{
			//	Show All categories LInk
			echo TAB_9.'<div class="ShopViewAllCatsButton" >' ."\n";
				echo TAB_10.'<a class="ShopButton" href="'.$_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;reset_browse=yes"' ."\n";	
					echo TAB_11.' title="Click this link to view all categories" >View all Categories' ."\n";
				echo TAB_10.'</a>' ."\n";
			echo TAB_9.'</div>' ."\n";	
		}		
		
//	================================================================================================================		
		
	function GetItemsInCat($cat_id)
	{

		echo TAB_8.'<div id="ShopItemList" >'."\n";
						
			echo TAB_9.'<table class="ShopItemListTable" >' ."\n";		
			
			//	 get all items in the selected  / last viewed  CAT
			$mysql_err_msg = 'Category Listing unavailable';	
			$sql_statement = 'SELECT * FROM sweeps_items, sweeps_cat_asign'

														.' WHERE cat_id = "'.$cat_id.'"'
														.' AND sweeps_cat_asign.item_id = sweeps_items.item_id'
														.' AND active = "on"'
														.' ORDER BY seq';
								
			$sweeps_item_listing_result = ReadDB ($sql_statement, $mysql_err_msg);

			//	display Item info in Listing
			$alt_BG_count = 1;
			while ($sweeps_item_listing_info = mysql_fetch_array ($sweeps_item_listing_result))
			{ 

				if ($alt_BG_count % 2)
				{$alt_BG_class = ' ShopItemListAltRow';}
				else {$alt_BG_class = '';}
				
				echo TAB_10.'<tr class="ShopItemList'.$alt_BG_class.'" >' ."\n";			
				
				GetItemInfo ($sweeps_item_listing_info['prod_id']);
				
				echo TAB_10.'</tr>' ."\n";	
				
				$alt_BG_count++;
				
			}
					
			echo TAB_9.'</table>' ."\n";
				
		echo TAB_8.'</div>'."\n";
	}

	
	function ListCatsInCat($cat_id)
	{
		//	read from db to get all parent cats	----------
		$mysql_err_msg = 'Category information unavailable';	
		$sql_statement = 'SELECT * FROM sweeps_categories'
				
										.' WHERE active = "on"'
										.' AND parent_id = "'.$cat_id.'"'
										.' ORDER BY seq';
				
		$sweeps_cats_result = ReadDB ($sql_statement, $mysql_err_msg);

		$sweeps_num_cats = mysql_num_rows ($sweeps_cats_result);
		
		//	if only one Cat in Root display items in it
		if ($cat_id == 0 AND $sweeps_num_cats == 1 )
		{
			$sweeps_cats_info = mysql_fetch_array ($sweeps_cats_result);
			$cat_id = $sweeps_cats_info['cat_id'];
					
			GetItemsInCat($cat_id);
		}
		
		//	if more than one CAT, display list
		elseif ($cat_id == 0 AND $sweeps_num_cats > 1 )		
		{
			echo TAB_8.'<h2 class="ShopCatListingHeading" >'.SHOP_CAT_LIST_HEADING.'</h2>' ."\n";
			
			echo TAB_8.'<ul class="ShopCatListing" >' ."\n";
			
			while ($sweeps_cats_info = mysql_fetch_array ($sweeps_cats_result))
			{
		
				echo TAB_9.'<li class="ShopCatListing" >' ."\n";
					
				$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;s_cat='.$sweeps_cats_info['cat_id'].'&amp;view=browse';
			
				//	Category name and image (link)

					echo TAB_10.'<a class="ShopLink" href="'.$href .'"' ."\n";
						echo TAB_11.' title="Click this link to view items in the '.$sweeps_cats_info['cat_name'].' category" >' ."\n";
						if 
						(
								$sweeps_cats_info['display_image'] == 'on'
							AND $sweeps_cats_info['image_file'] != '' 
							AND $sweeps_cats_info['image_file'] != NULL
							AND file_exists('_images_shop/'.$sweeps_cats_info['image_file'])
						)
						{
							echo TAB_11.'<img class="TinyThumb" src="/_images_shop/'.$sweeps_cats_info['image_file'].'" '
								.' alt="image for this category" />' ."\n";						
						}
		
						echo TAB_11.$sweeps_cats_info['cat_name'] ."\n";		
					echo TAB_10.'</a>' ."\n";					

				echo TAB_9.'</li>' ."\n";
			}
		
			echo TAB_8.'</ul>' ."\n";
			
		}

		elseif ( $sweeps_num_cats > 0 )	
		{
		
			echo TAB_8.'<h2 class="ShopCatListingHeading" >More Categories:</h2>' ."\n";
			
			echo TAB_8.'<ul class="ShopCatListing" >' ."\n";
			
			while ($sweeps_cats_info = mysql_fetch_array ($sweeps_cats_result))
			{
		
				echo TAB_9.'<li class="ShopCatListing" >' ."\n";
					
				$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;s_cat='.$sweeps_cats_info['cat_id'].'&amp;view=browse';
			
				//	Category name and image (link)

					echo TAB_10.'<a class="ShopLink" href="'.$href .'"' ."\n";
						echo TAB_11.' title="Click this link to view items in the '.$sweeps_cats_info['cat_name'].' category" >' ."\n";
						if 
						(
								$sweeps_cats_info['display_image'] == 'on'
							AND $sweeps_cats_info['image_file'] != '' 
							AND $sweeps_cats_info['image_file'] != NULL
							AND file_exists('_images_shop/'.$sweeps_cats_info['image_file'])
						)
						{
							echo TAB_11.'<img class="TinyThumb" src="/_images_shop/'.$sweeps_cats_info['image_file'].'" '
								.' alt="image for this category" />' ."\n";					
						}
							
						echo TAB_11.$sweeps_cats_info['cat_name'] ."\n";		
					echo TAB_10.'</a>' ."\n";					

				echo TAB_9.'</li>' ."\n";
			}
		
			echo TAB_8.'</ul>' ."\n";
			
		}		
	}
?>