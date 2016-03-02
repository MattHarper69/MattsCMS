<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	for Displaying after CART,   Items  from  CAT that last item added resides in - can be multiple CATs ( for  suggestive selling)
	
	//	set last added to cart item as item to look up...only go ahead if last item is set (user may just be viewing their invoice)
	if (isset($_SESSION['last_item_added']))
	{
		
		$prod_id = $_SESSION['last_item_added'];					
		
		
		//	get all cats associated with the item/cat combination last added to cart
		$mysql_err_msg = 'Category to Item Assignment information unavailable';	
		$sql_statement = 'SELECT    sweeps_cat_asign.cat_id FROM sweeps_cat_asign, sweeps_categories'
			
													.' WHERE prod_id = "'.$prod_id.'"'
													.' AND sweeps_cat_asign.cat_id = sweeps_categories.cat_id'
													.' AND active = "on"'
													.' ORDER BY sweeps_cat_asign.seq';
												
		$sweeps_cats_results = ReadDB ($sql_statement, $mysql_err_msg);
					
		while ($sweeps_cats_info = mysql_fetch_array ($sweeps_cats_results))
		{
			$cat_id = $sweeps_cats_info['cat_id'];

			//	now get all items in all CATs that are associated with the original Last added to cart ITEM
			$mysql_err_msg = 'Category to Item Assignment information unavailable';	
			$sql_statement = 'SELECT    sweeps_cat_asign.prod_id FROM sweeps_cat_asign, sweeps_items'
			
													.' WHERE cat_id = "'.$cat_id.'"'
													.' AND sweeps_cat_asign.item_id = sweeps_items.item_id'
													.' AND active = "on"'
													.' AND event_close_date > "'.date("Y-m-d G:i:s").'"'
													.' AND event_start_date < "'.date("Y-m-d G:i:s").'"'
													.' ORDER BY sweeps_cat_asign.seq';
											
			$sweeps_item_results = ReadDB ($sql_statement, $mysql_err_msg);
				
			//	create an array of items from all associated cats that is unique
			$suggest_items = array();
				
			//	put in array  to make unique
			while ($sweeps_cats_info = mysql_fetch_array ($sweeps_item_results))
			{ $suggest_items[] = 	$sweeps_cats_info['prod_id'];}
			
		}
			
		//	only do the following if array not empty... otherwise errors are produced
		if (count ($suggest_items) > 0)					
		{
			//	make array unique ... so items arent listed twice
			$suggest_items = array_unique($suggest_items);

			//	remove items already in Cart
			if (isset ($_SESSION['cart_items']))
			{
				foreach ( $suggest_items as $key => $prod_id )
				{ 
					if ($prod_id == array_key_exists( $prod_id , $_SESSION['cart_items']))
					{ unset ($suggest_items[$key ]);}
				}
			}
			
			//	remove items already recently Purchased
			if (isset ($_SESSION['purchased_items']))
			{
				foreach ( $suggest_items as $key => $prod_id )
				{ 
					if ($prod_id == array_key_exists( $prod_id , $_SESSION['purchased_items']))
					{ unset ($suggest_items[$key ]);}
				}
			}	

		}
		
		if ( count ($suggest_items ) > 0)
		{			
			echo TAB_8.'<div class="ShopDiv" id="ShopItemList" >'."\n";			
				
				echo TAB_9.'<h2 class="ShopItemList" >'.SHOP_SUGGEST_ITEM_LIST_HEADING.'</h2>' ."\n";
						
				echo TAB_9.'<table class="ShopItemListTable" >' ."\n";			
			
				//	display Item info in Listing
				$alt_BG_count = 0;
				foreach ( $suggest_items as $prod_id )
				{ 	

					if ($alt_BG_count % 2)
					{$alt_BG_class = ' ShopItemListAltRow';}
					else {$alt_BG_class = '';}
					
					echo TAB_10.'<tr class="ShopItemList'.$alt_BG_class.'" >' ."\n";			
					
						GetItemInfo ($prod_id);
					
					echo TAB_10.'</tr>' ."\n";	
					
					$alt_BG_count++;				
				}
							
				echo TAB_9.'</table>' ."\n";
					
				//	Show All categories LInk
				echo TAB_9.'<div class="ShopViewAllCatsButton" >' ."\n";
					echo TAB_10.'<a class="ShopButton" href="http://'.$_SERVER['SERVER_NAME']
								.$_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;reset_browse=yes'.'"' ."\n";	
						echo TAB_11.' title="Click this link to view all categories" >View all Categories' ."\n";
					echo TAB_10.'</a>' ."\n";
				echo TAB_9.'</div>' ."\n";			
			
			echo TAB_8.'</div>'."\n";
					
		}	
	
	}

?>