<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	if ITEM set, Do show Item Info
	if (isset ($prod_id))
	{		
		include ('shop_item_show.php');
		
		$_SESSION['last_item_id_viewed'] = $prod_id;
		$_SESSION['last_cat_id_viewed'] = $cat_id;				

	}

	
	//	if CAT specified...look Up: all SUB CATs and ITEMS
	elseif (isset ($cat_id) AND $cat_id != '' AND $cat_id != NULL )
	{
		//	if there is only one category DO not show Cat name heading
		if ($shop_num_cats > 1)
		{ echo TAB_8.'<h4 class="Shop" >'.$shop_cats_name.'</h4>' ."\n";}

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
		if ($shop_num_cats > 1)
		{
			//	Show All categories LInk
			echo TAB_10.'<a class="ShopLink" href="'.$_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;reset_browse=yes'.'"' ."\n";	
				echo TAB_11.' title="Click this link to view all categories" >View all Categories and Items' ."\n";
			echo TAB_10.'</a>' ."\n";		
		}
		
//	================================================================================================================================================================		
		
	function GetItemsInCat($cat_id)
	{

		echo TAB_8.'<div class="ShopDiv" id="ShopItemList" >'."\n";
						
			echo TAB_9.'<table class="ShopItemListTable" >' ."\n";		
			
			//	 get all items in the selected  / last viewed  CAT
			$mysql_err_msg = 'Category Listing unavailable';	
			$sql_statement = 'SELECT * FROM shop_items, shop_cat_asign'

														.' WHERE cat_id = "'.$cat_id.'"'
														.' AND shop_cat_asign.item_id = shop_items.item_id'
														.' AND active = "on"'
														.' ORDER BY seq';
								
			$shop_item_listing_result = ReadDB ($sql_statement, $mysql_err_msg);

			//	display Item info in Listing
			while ($shop_item_listing_info = mysql_fetch_array ($shop_item_listing_result))
			{ GetItemInfo ($shop_item_listing_info['prod_id']); }
					
			echo TAB_9.'</table>' ."\n";
				
		echo TAB_8.'</div>'."\n";
	}

	
	function ListCatsInCat($cat_id)
	{
		//	read from db to get all parent cats	----------
		$mysql_err_msg = 'Category information unavailable';	
		$sql_statement = 'SELECT * FROM shop_categories'
				
										.' WHERE active = "on"'
										.' AND parent_id = "'.$cat_id.'"'
										.' ORDER BY seq';
				
		$shop_cats_result = ReadDB ($sql_statement, $mysql_err_msg);

		$shop_num_cats = mysql_num_rows ($shop_cats_result);
		
		//	if only one Cat in Root display items in it
		if ($cat_id == 0 AND $shop_num_cats == 1 )
		{
			$shop_cats_info = mysql_fetch_array ($shop_cats_result);
			$cat_id = $shop_cats_info['cat_id'];
					
			GetItemsInCat($cat_id);
		}
		
		//	if more than one CAT, display list
		elseif ($cat_id == 0 AND $shop_num_cats > 1 )		
		{
			echo TAB_8.'<h2 class="ShopCatListingHeading" >'.SHOP_CAT_LIST_HEADING.'</h2>' ."\n";
			
			echo TAB_8.'<ul class="ShopCatListing" >' ."\n";
			
			while ($shop_cats_info = mysql_fetch_array ($shop_cats_result))
			{
		
				echo TAB_9.'<li class="ShopCatListing" >' ."\n";
					
				$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;s_cat='.$shop_cats_info['cat_id'].'&amp;view=browse';
			
				//	Category name and image (link)

					echo TAB_10.'<a class="ShopLink" href="'.$href .'"' ."\n";
						echo TAB_11.' title="Click this link to view items in the '.$shop_cats_info['cat_name'].' category" >' ."\n";
						if ($shop_cats_info['image_file'] != '' AND $shop_cats_info['image_file'] != NULL)
						{						
							echo TAB_11.'<img class="TinyThumb" src="/_images_shop/'.$shop_cats_info['image_file'].'" '
								.' alt="image for this category" />' ."\n";
						}
						echo TAB_11.$shop_cats_info['cat_name'] ."\n";		
					echo TAB_10.'</a>' ."\n";					

				echo TAB_9.'</li>' ."\n";
			}
		
			echo TAB_8.'</ul>' ."\n";
			
		}

		elseif ( $shop_num_cats > 0 )	
		{
		
			echo TAB_8.'<h2 class="ShopCatListingHeading" >More Categories:</h2>' ."\n";
			
			echo TAB_8.'<ul class="ShopCatListing" >' ."\n";
			
			while ($shop_cats_info = mysql_fetch_array ($shop_cats_result))
			{
		
				echo TAB_9.'<li class="ShopCatListing" >' ."\n";
					
				$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;s_cat='.$shop_cats_info['cat_id'].'&amp;view=browse';
			
				//	Category name and image (link)

					echo TAB_10.'<a class="ShopLink" href="'.$href .'"' ."\n";
						echo TAB_11.' title="Click this link to view items in the '.$shop_cats_info['cat_name'].' category" >' ."\n";
						echo TAB_11.'<img class="TinyThumb" src="/_images_shop/'.$shop_cats_info['image_file'].'" '
								.' alt="image for this category" />' ."\n";
						echo TAB_11.$shop_cats_info['cat_name'] ."\n";		
					echo TAB_10.'</a>' ."\n";					

				echo TAB_9.'</li>' ."\n";
			}
		
			echo TAB_8.'</ul>' ."\n";
			
		}		
	}
?>