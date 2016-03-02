<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		$prev_item_id = '';
		$next_item_id = '';
		$list_price = '';
		
	// get all item info
	$mysql_err_msg = 'Product information unavailable';	
	$sql_statement = 'SELECT * FROM shop_items, shop_cat_asign'

												.' WHERE shop_cat_asign.prod_id = "'.$prod_id.'"'
												.' AND shop_cat_asign.item_id = shop_items.item_id'
												.' AND active = "on"';
						
	$shop_item_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

	$cat_id = $shop_item_info['cat_id'];
	
	if ($shop_item_info != '')
	{
		//	now get all items in cat for << PREV / NEXT >> navigation
		$mysql_err_msg = 'Product information unavailable';	
		$sql_statement = 'SELECT shop_cat_asign.prod_id FROM shop_items, shop_cat_asign'

												.' WHERE shop_cat_asign.cat_id = "'.$cat_id.'"'
												.' AND shop_cat_asign.item_id = shop_items.item_id'
												.' AND active = "on"'
												.' ORDER BY seq';
						
		$shop_next_item_result = ReadDB ($sql_statement, $mysql_err_msg);

		while ($shop_next_item_info = mysql_fetch_array ($shop_next_item_result))
		{
			$items_in_cat_array[] = $shop_next_item_info[0];
		}
	

		
		foreach ($items_in_cat_array as $key => $next_prod_id)
		{
			if ( $prod_id == $next_prod_id)
			{
				if ($key != 0) {$prev_item_id = $items_in_cat_array[$key - 1];}
				if ($key != count($items_in_cat_array) - 1) {$next_item_id = $items_in_cat_array[$key + 1];}
			}
		}


		//	Do 	<< PREV / NEXT >> navigation
		echo TAB_10.'<div class="ShopItemShowNav" >' ."\n";
		
			echo TAB_11.'<ul class="ShopItemShowNav" >' ."\n";
			
			if ( $prev_item_id != '' AND $prev_item_id != NULL )
			{
				echo TAB_11.'<li class="ShopItemShowNav" >' ."\n";
					echo TAB_12.'<a class="ShopItemShowNav"' 
						.' href="'.$_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;prod_id='.$prev_item_id.'&amp;view=browse"'
						.' title="View the previous item in this category" >' ."\n";
						echo TAB_13.' &lt;&lt; Prev. Item ' ."\n";
					echo TAB_12.'</a>' ."\n";	
				echo TAB_11.'</li>' ."\n";
			}
			
			if ( $next_item_id != '' AND $next_item_id != NULL )
			{
				echo TAB_11.'<li class="ShopItemShowNav" >' ."\n";
					echo TAB_12.'<a class="ShopItemShowNav"' 
						.' href="'.$_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;prod_id='.$next_item_id.'&amp;view=browse"'
						.' title="View the next item in this category" >' ."\n";
						echo TAB_13.' Next Item &gt;&gt; ' ."\n";
					echo TAB_12.'</a>' ."\n";	
				echo TAB_11.'</li>' ."\n";
			}
			
			echo TAB_11.'</ul>' ."\n";
			
		echo TAB_10.'</div>' ."\n";	
			
		//	START Item Info
		echo TAB_10.'<table class="ShopItemShowTable" >' ."\n";
		
			echo TAB_11.'<tr class="ShopItemShow" >' ."\n";
				
				$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;prod_id='.$shop_item_info['prod_id'].'&amp;view=browse';
				
				//	product Name (link)
				echo TAB_12.'<td colspan="2" class="ShopCartTableCell ShopItemShowName" >' ."\n";

					echo TAB_13.'<h1 class="ShopItemShowName" >'.$shop_item_info['item_name'].'</h1>' ."\n";

				echo TAB_12.'</td>' ."\n";
				
				//	add to Cart
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowAdd2Cart" >' ."\n";			
				
				if ($shop_item_info['display_buynow'] == 'on' AND $shop_item_info['in_stock'] > 0)
				{
						
					if 
					( 
							$shop_item_info['max_quantity_allow'] != 0 AND $shop_item_info['max_quantity_allow'] != ''
						AND	isset($_SESSION['cart_items'][$prod_id])	
						AND	$_SESSION['cart_items'][$prod_id] >= $shop_item_info['max_quantity_allow']
						
					)
					{
						
						echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="maximum allowed already in cart" alt="disabled Add to Cart button" />'."\n";						
						echo TAB_13.'<p class="WarningMSGSmall" >( maximum allowed already in cart )</p>'."\n";;
					}
					else
					{	
						echo TAB_13.'<a href="index.php?p='.SHOP_PAGE_ID.'&amp;add2cart='.$shop_item_info['prod_id'].'" >'."\n";

							echo TAB_14.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON.'" '."\n";							
							echo TAB_14.'title="Click here to add: '.$shop_item_info['item_name'].' to your Shopping Cart." alt="Add to Cart" />'."\n";

							echo TAB_14.'<br/><span>add to cart</span>'."\n";
							
						echo TAB_13.'</a>'."\n";
					}
						
				}
				
				//	Item Not in stock
				if ($shop_item_info['display_buynow'] == 'on' AND $shop_item_info['in_stock'] < 1)
				{
					echo TAB_13.'<h4 class="Notice	" >Item Not in stock</h1>' ."\n";
				}
				
				echo TAB_12.'</td>' ."\n";
				
			echo TAB_11.'</tr>' ."\n";
			
			echo TAB_11.'<tr class="ShopItemShow" >' ."\n";
			
				//	product PRICE
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowPrice" >' ."\n";
			
				if ($shop_item_info['list_price'] != '' AND $shop_item_info['list_price'] != NULL AND $shop_item_info['list_price'] != 0 )
				{ 
					$list_price = $shop_item_info['list_price']; 
					echo TAB_13.'<p class="ShopItemListPrice">RRP: <span class="Strike" >'
							.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($list_price, 2).SHOP_CURRENCY_SYMBOL_SUFFIX
					.'</span></p>' ."\n";				
				}

				if ($shop_item_info['price'] != '' AND $shop_item_info['price'] != NULL )
				{
					$item_price = $shop_item_info['price'];
					echo TAB_13.'<p>Our Price: </p>' ."\n";
					echo TAB_13.'<p class="ShopItemPrice" >'
									.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($item_price, 2).SHOP_CURRENCY_SYMBOL_SUFFIX
							  .'</p>' ."\n";
				}
					
				//	Saving amount and %
				$save_price = $list_price - $item_price;
			
				if (is_numeric($save_price) AND $save_price > 0)
				{
					$save_perc = round ($save_price / $list_price * 100 );

					echo TAB_13.'<p>You Save: <span class="ShopItemSavePrice" >'
						.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($save_price, 2).SHOP_CURRENCY_SYMBOL_SUFFIX.' ('.$save_perc.'%)'
					.'</span></p>' ."\n";					
				}						
			
				echo TAB_12.'</td>' ."\n";			
						
				//	product image (link)
				if ($shop_item_info['display_rating'] != 'on' )
				{ $extra_col = 'colspan="2"'; }
				else{$extra_col = '';}
				
					echo TAB_12.'<td class="ShopCartTableCell ShopItemShowImage" '. $extra_col . ' >' ."\n";
					
					list($img_width, $img_height) = getimagesize('_images_shop/'.$shop_item_info['image_file']);
					
					$new_win_width = $img_width * 1.1;
					$new_win_height = $img_height + 70;
					
					$href = 'javascript:openWindow(\'includes/image_show.php?img=/_images_shop/'.$shop_item_info['image_file'].'\','
							.$new_win_width.','.$new_win_height.');';					
					
					if (SHOP_CAN_ENLARGE_ITEM_IMAGE == 1)	
					{
						echo TAB_13.'<a class="ShopItemShowImage" href="'.$href .'"' ."\n";
							echo TAB_14.' title="Click to enlarge this image" >' ."\n";				
					}

							echo TAB_14.'<img class="ShopItemShowImage" src="/_images_shop/'.$shop_item_info['image_file'].'"'
									.' alt="image of product" />' ."\n";
						
					if (SHOP_CAN_ENLARGE_ITEM_IMAGE == 1)	
					{						
						echo TAB_14.'<br/>click to enlarge...' ."\n";
						echo TAB_13.'</a>' ."\n";		
					}						

										
					echo TAB_12.'</td>' ."\n";
				
				//	product (customer) RATING
				if ($shop_item_info['display_rating'] == 'on' )
				{ 
					echo TAB_12.'<td class="ShopCartTableCell ShopItemShowRating" >' ."\n";
					
						$rating = $shop_item_info['rating'];
						
						if ($shop_item_info['votes'] != '' AND $shop_item_info['votes'] != NULL AND $shop_item_info['votes'] != 0 )
						{						
							echo TAB_13.'<h4 class="ShopItemShowRating" >Customer<br/>Rating:<br/>'.round($rating,1).'</h4>' ."\n";
						}
						
						else
						{						
							echo TAB_13.'<h4 class="ShopItemShowRating" >(This product has not been rated yet)</h4>' ."\n";
						}					
							
						$item_id = $shop_item_info['item_id'];
						$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;prod_id='.$prod_id.'&amp;view=browse';					
							
						for ( $rate = 1; $rate<6; $rate++ )						
						{	
							if ($rate > round($rating) ) {$star_class = 'NegativeStar';}
							else {$star_class = 'PositiveStar';}
								
							echo TAB_14.'<a class="'.$star_class.'"' ."\n";
							echo TAB_14.'href="'.$href.'&amp;rate='.$rate.'"' ."\n";
							echo TAB_14.' title="Rate this product: &#39;'.$rate.'&#39; out of 5" >&nbsp;&nbsp;&nbsp; </a>' ."\n";
										
						}
							
						if ($shop_item_info['votes'] != '' AND $shop_item_info['votes'] != NULL AND $shop_item_info['votes'] != 0)
						{
							echo TAB_13.'<br/><p class="ShopItemShowRating" >( '.$shop_item_info['votes'].' Votes )</p>' ."\n";
						}
							
						echo TAB_13.'<p class="ShopItemShowRating" >Click a  star to rate <br/>this Product</p>' ."\n";
				
					echo TAB_12.'</td>' ."\n";				
				}

			echo TAB_11.'</tr>' ."\n";
			
			echo TAB_11.'<tr class="ShopItemShow" >' ."\n";
			
				//	Product Description
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowDesc" colspan="3" >' ."\n";
			
				if ($shop_item_info['description'] != '' AND $shop_item_info['description'] != NULL )
				{
					echo TAB_13.'<p class="ShopItemShowDesc" >'.HiliteText(nl2br(Space2nbsp($shop_item_info['description']))).'</p>' ."\n";
				}			
			
				echo TAB_12.'</td>' ."\n";
			
			echo TAB_11.'</tr>' ."\n";
			
			echo TAB_11.'<tr class="ShopItemShow" >' ."\n";
			
				//	Display in-stock
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowInStock" >' ."\n";
			
				if ($shop_item_info['display_instock'] == 'on' )
				{
					echo TAB_13.'<p class="ShopItemShowInStock" >N# of items in stock: '.$shop_item_info['in_stock'].'</p>' ."\n";
				}			
			
				echo TAB_12.'</td>' ."\n";
			
				//	Shipping statistics
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowInStock" >' ."\n";
			
				if ($shop_item_info['display_ship_measures'] == 'on' )
				{
					echo TAB_13.'<p class="ShopItemShowShip" >Postage Measurements:</p>' ."\n";
					
					if (
							$shop_item_info['ship_length_mm'] != 0 
						AND $shop_item_info['ship_length_mm'] != '' 
						AND $shop_item_info['ship_length_mm'] != NULL
					)
					{$length = $shop_item_info['ship_length_mm'] / 10 .'cm';}
					else {$length = 'n/a';}
					echo TAB_13.'<p class="ShopItemShowShip" >Length: '.$length ."\n";
					
					if (
							$shop_item_info['ship_width_mm'] != 0 
						AND $shop_item_info['ship_width_mm'] != '' 
						AND $shop_item_info['ship_width_mm'] != NULL
					)
					{$width = $shop_item_info['ship_width_mm'] / 10 .'cm';}
					else {$width = 'n/a';}
					echo TAB_13.' - Width: '.$width.'</p>' ."\n";

					if (
							$shop_item_info['ship_height_mm'] != 0 
						AND $shop_item_info['ship_height_mm'] != '' 
						AND $shop_item_info['ship_height_mm'] != NULL
					)
					{$height = $shop_item_info['ship_height_mm'] / 10 .'cm';}
					else {$height = 'n/a';}
					echo TAB_13.'<p class="ShopItemShowShip" >Height: '.$height ."\n";

					if (
							$shop_item_info['ship_weight_kg'] != 0 
						AND $shop_item_info['ship_weight_kg'] != '' 
						AND $shop_item_info['ship_weight_kg'] != NULL
					)
					{$weight = $shop_item_info['ship_weight_kg'].'kg';}
					else {$weight = 'n/a';}
					echo TAB_13.' - Weight: '.$weight.'</p>' ."\n";
					
				}	
				
				echo TAB_12.'</td>' ."\n";	
				
				//	Display Maximum Quantity
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowMaxQuant" >' ."\n";
				
				if ( $shop_item_info['max_quantity_allow'] != 0 AND $shop_item_info['max_quantity_allow'] != '')
				{
					echo TAB_13.'<p class="ShopItemShowShip" >Maximum of: '.$shop_item_info['max_quantity_allow'] .' per order</p>' ."\n";
				}
				echo TAB_12.'</td>' ."\n";
			
			echo TAB_11.'</tr>' ."\n";	
			
		echo TAB_10.'</table>' ."\n";

	}

		if (isset ($_SESSION['cart_items']))
		{
			echo TAB_9.'<div class="ShopDiv" id="ShopNavButtons" >'."\n";
			
				echo TAB_10.'<p class="ShopGoCheckoutButton" >' ."\n";		
				
				$view_cart_query_str = $query_str.'&amp;view=checkout';
				echo TAB_11.'<a class="ShopGoCheckoutButton" href="'.$_SERVER['PHP_SELF'].$view_cart_query_str.'"'. "\n";		
				echo TAB_11.' title="Go to the Checkout" >Proceed to Check-out</a>'. "\n";
				echo TAB_11.'<span class="Large" > OR </span>'. "\n";

				//	OR Continue Shopping
				$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;view=browse';
				echo TAB_11.'<a class="ShopGoCheckoutButton" href="'.$href.'"'. "\n";		
				echo TAB_11.' title="Return to the Product Listing to browse more items" >Continue Shopping</a>'. "\n";

				echo TAB_10.'</p>' ."\n";

			echo TAB_9.'</div>'."\n";	//	end 	Nav buttons Div
		}
?>		