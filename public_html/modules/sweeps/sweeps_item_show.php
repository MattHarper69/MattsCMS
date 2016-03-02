<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	// get all item info
	$mysql_err_msg = 'Product information unavailable';	
	$sql_statement = 'SELECT * FROM sweeps_items, sweeps_cat_asign'

												.' WHERE sweeps_cat_asign.prod_id = "'.$prod_id.'"'
												.' AND sweeps_cat_asign.item_id = sweeps_items.item_id'
												.' AND active = "on"';
	
	$sweeps_item_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

	$cat_id = $sweeps_item_info['cat_id'];
	
	if ($sweeps_item_info != '')
	{
		//	now get all items in cat for << PREV / NEXT >> navigation
		$mysql_err_msg = 'Product information unavailable';	
		$sql_statement = 'SELECT sweeps_cat_asign.prod_id FROM sweeps_items, sweeps_cat_asign'

												.' WHERE sweeps_cat_asign.cat_id = "'.$cat_id.'"'
												.' AND sweeps_cat_asign.item_id = sweeps_items.item_id'
												.' AND active = "on"'
												.' ORDER BY seq';
						
		$sweeps_next_item_result = ReadDB ($sql_statement, $mysql_err_msg);

		while ($sweeps_next_item_info = mysql_fetch_array ($sweeps_next_item_result))
		{
			$items_in_cat_array[] = $sweeps_next_item_info[0];
		}
	
		foreach ($items_in_cat_array as $key => $next_prod_id)
		{
			if ( $prod_id == $next_prod_id)
			{
				//	avoid offsset errors
				if ($key > 0)	
				{$prev_item_id = $items_in_cat_array[$key - 1];}				
				else
				{$prev_item_id = '';}
				
				if ($key + 1 < count($items_in_cat_array))
				{$next_item_id = $items_in_cat_array[$key + 1];}
				else
				{$next_item_id = '';}
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
						.' title="View the previous '.SHOP_ITEM_ALIAS.' in this category" >' ."\n";
						echo TAB_13.' &lt;&lt; Prev. '.SHOP_ITEM_ALIAS.' ' ."\n";
					echo TAB_12.'</a>' ."\n";	
				echo TAB_11.'</li>' ."\n";
			}
			
			if ( $next_item_id != '' AND $next_item_id != NULL )
			{
				echo TAB_11.'<li class="ShopItemShowNav" >' ."\n";
					echo TAB_12.'<a class="ShopItemShowNav"' 
						.' href="'.$_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;prod_id='.$next_item_id.'&amp;view=browse"'
						.' title="View the next '.SHOP_ITEM_ALIAS.' in this category" >' ."\n";
						echo TAB_13.' Next '.SHOP_ITEM_ALIAS.' &gt;&gt; ' ."\n";
					echo TAB_12.'</a>' ."\n";	
				echo TAB_11.'</li>' ."\n";
			}
			
			echo TAB_11.'</ul>' ."\n";
			
		echo TAB_10.'</div>' ."\n";	
			
		//	START Item Info
		echo TAB_10.'<table class="ShopItemShowTable" >' ."\n";
		
			echo TAB_11.'<tr class="ShopItemShow" >' ."\n";
				
				$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;prod_id='.$sweeps_item_info['prod_id'].'&amp;view=browse';
				
				//	product Name (link)
				echo TAB_12.'<td colspan="2" class="ShopCartTableCell ShopItemShowName" >' ."\n";

					echo TAB_13.'<h1 class="ShopItemShowName" >'.$sweeps_item_info['item_name'].'</h1>' ."\n";

				echo TAB_12.'</td>' ."\n";
				
				//	add to Cart
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowAdd2Cart" >' ."\n";			
				
				if ($sweeps_item_info['display_buynow'] == 'on' AND $sweeps_item_info['in_stock'] > 0)
				{
						
					if 
					( 
							isset($_SESSION['cart_items'])
						AND	$sweeps_item_info['max_quantity_allow'] != 0 AND $sweeps_item_info['max_quantity_allow'] != ''
						AND	$_SESSION['cart_items'][$prod_id] >= $sweeps_item_info['max_quantity_allow']
					)
					{					
						echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="maximum allowed already in cart" alt="disabled Add to Cart button" />'."\n";						
						echo TAB_13.'<p class="WarningMSGSmall" >( maximum allowed already in cart )</p>'."\n";;
					}
					
					///		DISABLE if not with in Sale date-time Range
					elseif ( time() < strtotime($sweeps_item_info['event_start_date']) AND $sweeps_item_info['event_start_date'] != 0)
					{					
						echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="This event is now closed" alt="disabled Add to Cart button" />'."\n";
						$start_date = date("D jS M, Y @ g:ia", strtotime($sweeps_item_info['event_start_date']));
						echo TAB_13.'<p class="WarningMSG" >This '.SHOP_ITEM_ALIAS.' goes on sale:<br/>'.$start_date.'</p>'."\n";
					}					
					elseif ( time() > strtotime($sweeps_item_info['event_close_date']) AND $sweeps_item_info['event_close_date'] != 0)
					{					
						echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="This event is now closed" alt="disabled Add to Cart button" />'."\n";						
						echo TAB_13.'<p class="WarningMSG" >This '.SHOP_ITEM_ALIAS.' is now closed</p>'."\n";
					}					
					
					else
					{	
						echo TAB_13.'<a href="index.php?p='.SHOP_PAGE_ID.'&amp;add2cart='.$sweeps_item_info['prod_id'].'" >'."\n";

							echo TAB_14.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON.'" '."\n";							
							echo TAB_14.'title="Click here to add: '.$sweeps_item_info['item_name'].' to your Shopping Cart." alt="Add to Cart" />'."\n";

							echo TAB_14.'<br/><span>add to cart</span>'."\n";
							
						echo TAB_13.'</a>'."\n";
					}
						
				}
				
				//	Item Not in stock
				//if ($sweeps_item_info['display_buynow'] == 'on' AND $sweeps_item_info['in_stock'] < 1)
				else
				{
					echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="This event is Sold out" alt="disabled Add to Cart button" />'."\n";					
					echo TAB_13.'<h4 class="Notice	" >Sorry, Sold out</h1>' ."\n";
				}
				
				echo TAB_12.'</td>' ."\n";
				
			echo TAB_11.'</tr>' ."\n";
			
			echo TAB_11.'<tr class="ShopItemShow" >' ."\n";
			
				//	product PRICE
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowPrice" >' ."\n";
			
				if 
				(
						$sweeps_item_info['display_list_price'] != ''
					AND $sweeps_item_info['list_price'] != '' 
					AND $sweeps_item_info['list_price'] != NULL 
					AND $sweeps_item_info['list_price'] != 0 
				)
				{ 
					$list_price = $sweeps_item_info['list_price']; 
					echo TAB_13.'<p class="ShopItemListPrice">RRP: <span class="Strike" >'
							.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($list_price, 2).SHOP_CURRENCY_SYMBOL_SUFFIX
					.'</span></p>' ."\n";				
				}
				else {$list_price = '';}

				if ($sweeps_item_info['price'] != '' AND $sweeps_item_info['price'] != NULL )
				{
					$item_price = $sweeps_item_info['price'];
					echo TAB_13.'<p>'.SHOP_LABEL_PRICE.'</p>' ."\n";
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
						
				//	product image
				if ($sweeps_item_info['display_rating'] != 'on' )
				{ $extra_col = 'colspan="2"'; }
				else { $extra_col = '';}
					echo TAB_12.'<td class="ShopCartTableCell ShopItemShowImage" '.$extra_col.' >' ."\n";
				
						if($sweeps_item_info['display_image'] == 'on'  )
						{
							$sql_statement = 'SELECT image_file_name FROM sweeps_item_images'

																.' WHERE item_id = "'.$sweeps_item_info['item_id'].'"'
																.' AND image_id = "'.$sweeps_item_info['primary_image_id'].'"'
																;
							
							$primary_image_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
							$primary_image_filename = $primary_image_info['image_file_name'];
							
							$img_url = '_images_shop/'.$primary_image_filename;

							if (SHOP_CAN_ENLARGE_ITEM_IMAGE == 1)	
							{
								if (file_exists($img_url) AND $primary_image_filename != '')
								{									
									echo TAB_13.'<a href="'.$img_url .'" rel="ColorBoxImageShop" >' ."\n";	
										echo TAB_14.'<img class="ShopItemShowImage" src="'.$img_url.'"'
											.' alt="image of product: '.$sweeps_item_info['item_name'].'" />' ."\n";
									echo TAB_13.'</a>' ."\n";
									echo TAB_13.'<br/>' ."\n";							
								}
								

								//	get other item images
								$mysql_err_msg = 'Other Product image information unavailable';	
								$sql_statement = 'SELECT image_file_name FROM sweeps_item_images'

												.' WHERE item_id = "'.$sweeps_item_info['item_id'].'"'
												.' AND image_id != "'.$sweeps_item_info['primary_image_id'].'"'
												.' ORDER BY seq, image_id'
												;
								$image_info_result = ReadDB ($sql_statement, $mysql_err_msg);

								while ($item_image_info = mysql_fetch_array ($image_info_result))
								{
									$thumb_img_url = '_images_shop/'.$item_image_info['image_file_name'];
									if (file_exists($thumb_img_url))
									{
										echo TAB_13.'<a href="'.$thumb_img_url .'" rel="ColorBoxImageShop" >' ."\n";
											echo TAB_14.'<img class="TinyThumb" src="'.$thumb_img_url.'"'
													.' alt="Thumbnail image of product: '.$sweeps_item_info['item_name'].'" />' ."\n";
										echo TAB_13.'</a>' ."\n";								
									}

								}
								
							}
								
							else	
							{						
								echo TAB_14.'<img class="ShopItemShowImage" src="'.$img_url.'"'
											.' alt="image of product: '.$sweeps_item_info['item_name'].'" />' ."\n";
							}					
						}
						else 
						{
							echo TAB_13.' No Image ' ."\n"; 
						}

				
					echo TAB_12.'</td>' ."\n";
				
				//	product (customer) RATING
				if ($sweeps_item_info['display_rating'] == 'on' )
				{ 
					echo TAB_12.'<td class="ShopCartTableCell ShopItemShowRating" >' ."\n";
					
						$rating = $sweeps_item_info['rating'];
						
						if ($sweeps_item_info['votes'] != '' AND $sweeps_item_info['votes'] != NULL AND $sweeps_item_info['votes'] != 0 )
						{						
							echo TAB_13.'<h4 class="ShopItemShowRating" >Customer<br/>Rating:<br/>'.round($rating,1).'</h4>' ."\n";
						}
						
						else
						{						
							echo TAB_13.'<h4 class="ShopItemShowRating" >(This '.SHOP_ITEM_ALIAS.' has not been rated yet)</h4>' ."\n";
						}					
							
						$item_id = $sweeps_item_info['item_id'];
						$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;prod_id='.$prod_id.'&amp;view=browse';					
							
						for ( $rate = 1; $rate<6; $rate++ )						
						{	
							if ($rate > round($rating) ) {$star_class = 'NegativeStar';}
							else {$star_class = 'PositiveStar';}
								
							echo TAB_14.'<a class="'.$star_class.'"' ."\n";
							echo TAB_14.'href="'.$href.'&amp;rate='.$rate.'"' ."\n";
							echo TAB_14.' title="Rate this '.SHOP_ITEM_ALIAS.': &#39;'.$rate.'&#39; out of 5" >&nbsp;&nbsp;&nbsp;&nbsp; </a>' ."\n";
										
						}
							
						if ($sweeps_item_info['votes'] != '' AND $sweeps_item_info['votes'] != NULL AND $sweeps_item_info['votes'] != 0)
						{
							echo TAB_13.'<br/><p class="ShopItemShowRating" >( '.$sweeps_item_info['votes'].' Votes )</p>' ."\n";
						}
							
						echo TAB_13.'<p class="ShopItemShowRating" >Click a  star to rate <br/>this '.SHOP_ITEM_ALIAS.'</p>' ."\n";
				
					echo TAB_12.'</td>' ."\n";				
				}

			echo TAB_11.'</tr>' ."\n";
			
			echo TAB_11.'<tr class="ShopItemShow" >' ."\n";
			
				//	Product Description
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowDesc" colspan="3" >' ."\n";

				if ($sweeps_item_info['description'] != '' AND $sweeps_item_info['description'] != NULL )
				{
					echo TAB_13.'<p class="ShopItemShowDesc" >'.HiliteText(nl2br(Space2nbsp($sweeps_item_info['description']))).'</p>' ."\n";
				}
				
				//	event starts date
				if (time() < strtotime($sweeps_item_info['event_start_date']) AND $sweeps_item_info['event_start_date'] != 0)
				{						
					echo TAB_13.'<p class="ShopItemShowDesc" >On Sale: '
									.date("D jS M, Y @ g:ia", strtotime($sweeps_item_info['event_start_date'])).'</p>' ."\n";			
				}
				
				//	event closing date
				if ($sweeps_item_info['event_close_date'] != 0)
				{						
					echo TAB_13.'<p class="ShopItemShowDesc" >Closes: '
									.date("D jS M, Y @ g:ia", strtotime($sweeps_item_info['event_close_date'])).'</p>' ."\n";			
				}
											
				echo TAB_12.'</td>' ."\n";
			
			echo TAB_11.'</tr>' ."\n";
			
			echo TAB_11.'<tr class="ShopItemShow" >' ."\n";
			
				//	Display in-stock
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowInStock" >' ."\n";
			
				if ($sweeps_item_info['display_instock'] == 'on' )
				{
					echo TAB_13.'<p class="ShopItemShowInStock" >N# of items in stock: '.$sweeps_item_info['in_stock'].'</p>' ."\n";
				}			
			
				echo TAB_12.'</td>' ."\n";
			
				//	Shipping statistics
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowInStock" >' ."\n";
			
				if ($sweeps_item_info['display_ship_measures'] == 'on' )
				{
					echo TAB_13.'<p class="ShopItemShowShip" >Postage Measurements:</p>' ."\n";
					
					if (
							$sweeps_item_info['ship_length'] != 0 
						AND $sweeps_item_info['ship_length'] != '' 
						AND $sweeps_item_info['ship_length'] != NULL
					)
					{$length = $sweeps_item_info['ship_length'] . SHOP_POST_SIZE_UNITS;}
					else {$length = 'n/a';}
					echo TAB_13.'<p class="ShopItemShowShip" >Length: '.$length ."\n";
					
					if (
							$sweeps_item_info['ship_width'] != 0 
						AND $sweeps_item_info['ship_width'] != '' 
						AND $sweeps_item_info['ship_width'] != NULL
					)
					{$width = $sweeps_item_info['ship_width'] . SHOP_POST_SIZE_UNITS;}
					else {$width = 'n/a';}
					echo TAB_13.' - Width: '.$width.'</p>' ."\n";

					if (
							$sweeps_item_info['ship_height'] != 0 
						AND $sweeps_item_info['ship_height'] != '' 
						AND $sweeps_item_info['ship_height'] != NULL
					)
					{$height = $sweeps_item_info['ship_height'] . SHOP_POST_SIZE_UNITS;}
					else {$height = 'n/a';}
					echo TAB_13.'<p class="ShopItemShowShip" >Height: '.$height ."\n";

					if (
							$sweeps_item_info['ship_weight'] != 0 
						AND $sweeps_item_info['ship_weight'] != '' 
						AND $sweeps_item_info['ship_weight'] != NULL
					)
					{$weight = $sweeps_item_info['ship_weight'] . SHOP_POST_WEIGHT_UNITS;}
					else {$weight = 'n/a';}
					echo TAB_13.' - Weight: '.$weight.'</p>' ."\n";
					
				}	
				
				echo TAB_12.'</td>' ."\n";	
				
				//	Display Maximum Quantity
				echo TAB_12.'<td class="ShopCartTableCell ShopItemShowMaxQuant" >' ."\n";
				
				if ( $sweeps_item_info['max_quantity_allow'] != 0 AND $sweeps_item_info['max_quantity_allow'] != '')
				{
					echo TAB_13.'<p class="ShopItemShowShip" >Maximum of: '.$sweeps_item_info['max_quantity_allow'] .' per order</p>' ."\n";
				}
				echo TAB_12.'</td>' ."\n";
			
			echo TAB_11.'</tr>' ."\n";	
			
		echo TAB_10.'</table>' ."\n";

	}

		if (isset ($_SESSION['cart_items']))
		{
			echo TAB_9.'<div id="ShopNavButtons" >'."\n";
			
				echo TAB_10.'<p class="ShopButton" >' ."\n";		
				
				$view_cart_query_str = $query_str.'&amp;view=checkout';
				echo TAB_11.'<a class="ShopButton" href="'.$_SERVER['PHP_SELF'].$view_cart_query_str.'"'. "\n";		
				echo TAB_11.' title="Go to the Checkout" >Proceed to Check-out</a>'. "\n";

				if(SHOP_DISPLAY_CONTINUE_SHOPPING == 1)
				{	
					//	OR Continue Shopping
					$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;view=browse';
					echo TAB_11.'<a class="ShopButton" href="'.$href.'"'. "\n";		
					echo TAB_11.' title="Return to the Product Listing to browse more items" >Continue Shopping</a>'. "\n";
				}
				
				echo TAB_10.'</p>' ."\n";

			echo TAB_9.'</div>'."\n";	//	end 	Nav buttons Div
		}
?>		