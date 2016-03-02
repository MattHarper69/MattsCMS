<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	for Displaying after CART,   Items  from  CAT that last item added resides in - can be multiple CATs ( for  suggestive selling)
	
	//	set last added to cart item as item to look up...
	$prod_id = $_SESSION['last_item_added'];					
	//unset ($_SESSION['last_item_added']);	//	keep last item added ???
		
	
	//	get all cats associated with the item/cat combination last added to cart
	$mysql_err_msg = 'Category to Item Assignment information unavailable';	
	$sql_statement = 'SELECT    shop_cat_asign.cat_id FROM shop_cat_asign, shop_categories'
		
												.' WHERE prod_id = "'.$prod_id.'"'
												.' AND shop_cat_asign.cat_id = shop_categories.cat_id'
												.' AND active = "on"'
												.' ORDER BY seq';
											
	$shop_cats_results = ReadDB ($sql_statement, $mysql_err_msg);
				
	while ($shop_cats_info = mysql_fetch_array ($shop_cats_results))
	{
		$cat_id = $shop_cats_info['cat_id'];

		//	now get all items in all CATs that are associated with the original Last added to cart ITEM
		$mysql_err_msg = 'Category to Item Assignment information unavailable';	
		$sql_statement = 'SELECT    shop_cat_asign.prod_id FROM shop_cat_asign, shop_items'
		
												.' WHERE cat_id = "'.$cat_id.'"'
												.' AND shop_cat_asign.item_id = shop_items.item_id'
												.' AND active = "on"'
												.' ORDER BY seq';
										
		$shop_item_results = ReadDB ($sql_statement, $mysql_err_msg);
			
		//	create an array of items from all associated cats that is unique
		$suggest_items = array();
			
		//	put in array  to make unique
		while ($shop_cats_info = mysql_fetch_array ($shop_item_results))
		{ $suggest_items[] = 	$shop_cats_info['prod_id'];}
		
	}
		
	//	only do the following if array not empty... otherwise errors are produced
	if (isset($suggest_items) AND count ($suggest_items) > 0)					
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

	if ( isset($suggest_items) AND count ($suggest_items ) > 0)
	{			
		echo TAB_8.'<div class="ShopDiv" id="ShopItemList" >'."\n";			
			
			echo TAB_9.'<h2 class="ShopItemList" >'.SHOP_SUGGEST_ITEM_LIST_HEADING.'</h2>' ."\n";
					
			echo TAB_9.'<table class="ShopItemListTable" >' ."\n";			
		
			//	display Item info in Listing
			foreach ( $suggest_items as $prod_id )
			{ 	
				GetItemInfo ($prod_id); 
			}
						
			echo TAB_9.'</table>' ."\n";
				
		echo TAB_8.'</div>'."\n";
		
	}	

?>