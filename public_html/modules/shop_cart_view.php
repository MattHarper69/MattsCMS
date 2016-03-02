<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	if(isset($_SESSION['promo_code'])) {$promo_code = $_SESSION['promo_code'];}
	else {$promo_code = '';}
	
	$total_calculated_quantity = 0;
	
	//	double check  cart NOT empty
	if (count($_SESSION['cart_items']) > 0)
	{		
	echo TAB_8.'<div class="ShopDiv" id="ShopCartView" >'."\n";
	
		echo TAB_9.'<div class="ShopDiv" id="ShopCartContents" >'."\n";
		
			echo TAB_9.'<h4 class="Shop" >'.SHOP_CART_MAIN_HEADING.'</h4>' ."\n";
			
			echo TAB_9.'<form action="'.$_SERVER['PHP_SELF'].$query_str.'&amp;view='.$view_set.'" method="post" >' ."\n";
			
				echo TAB_10.'<table class="ShopCartTable" >' ."\n";
			
					echo TAB_11.'<tr class="ShopCartTableHeader" >' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" ></th>' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" >Product:</th>' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" >Price:</th>' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" >Quantity:</th>' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" >Total:</th>' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" >Remove:</th>' ."\n";
					echo TAB_11.'</tr>' ."\n";			
				
				$grand_total_price = 0;
				$grand_total_save = 0;
				//unset($promo_maxlength);
				$promo_maxlength = 0;
				$valid_code_entered = 0;
				
				//	Get Cart Contents:
				foreach ( $_SESSION['cart_items'] as $prod_id => $item_quantity )
				{

					//	read from db to get prices and other display info	----------
					$mysql_err_msg = 'Item information unavailable';	
					$sql_statement = 'SELECT * FROM shop_items, shop_cat_asign'

															.' WHERE shop_cat_asign.prod_id = "'.$prod_id.'"'
															.' AND shop_cat_asign.item_id = shop_items.item_id'
															.' AND active = "on"';
								
					$shop_items_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	
					
					echo TAB_11.'<tr class="ShopCartTableRow" >' ."\n";
					
						$href = $_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;prod_id='.$shop_items_info['prod_id'].'&amp;view=browse';
				
						//	product image (link)
						echo TAB_12.'<td class="ShopCartTableCell" >' ."\n";
							echo TAB_13.'<a class="ShopItemLinkThumb" href="'.$href .'"' ."\n";
								echo TAB_14.' title="Click this link to view this product&#39;s details: '.$shop_items_info['item_name'].'" >' ."\n";
								echo TAB_14.'<img class="TinyThumb" src="/_images_shop/'.$shop_items_info['image_file'].'" '
										.' alt="image of product" />' ."\n";
							echo TAB_13.'</a>' ."\n";
						echo TAB_12.'</td>' ."\n";
					
						//	product Name (link)
						echo TAB_12.'<td class="ShopCartTableCell" >' ."\n";
							echo TAB_13.'<a class="ShopItemLink" href="'.$href.'"' ."\n";
								echo TAB_14.' title="Click this link to view this product&#39;s details: '.$shop_items_info['item_name'].'" >'."\n";
								echo TAB_14.$shop_items_info['item_name'] ."\n";
							echo TAB_13.'</a>' ."\n";
						echo TAB_12.'</td>' ."\n";		
						
						//	product PRICE
						echo TAB_12.'<td class="ShopCartTableCell" >' ."\n";
						
						//	has a coupon / promo code been entered

						$discount = 0;					
						unset($is_code);
//						$code_price = $shop_items_info['price'];					
						for ($p=1; $p<5; $p++)
						{						
							if ( $shop_items_info['promo_code_'.$p] != '' AND $shop_items_info['promo_code_'.$p] != NULL )							
							{					
								$is_code = 1;
																							
								//	use str length of code as max length for input
								if (strlen($shop_items_info['promo_code_'.$p]) >= $promo_maxlength )
								{ $promo_maxlength = strlen($shop_items_info['promo_code_'.$p]); }
								if (strlen($promo_code) >= $promo_maxlength )
								{ $promo_maxlength = strlen($promo_code); }									
								
								if 
								( 
									isset($promo_code) AND $promo_code != '' AND $promo_code != NULL
									AND strtolower($promo_code) == 	strtolower($shop_items_info['promo_code_'.$p])
								)
								{
									$discount = 1;
									$code_price = $shop_items_info['promo_price_'.$p];							
								}
								
							}

						}
						
						//	discount code found and matched !! - apply discunt price
						if ($discount == 1)	
						{
							echo TAB_13.'<span class="Strike" >'
									.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($shop_items_info['price'], 2).SHOP_CURRENCY_SYMBOL_SUFFIX
								.'</span>'."\n";
							echo TAB_13.'<br/>' ."\n";
							echo TAB_13.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($code_price, 2)
										.SHOP_CURRENCY_SYMBOL_SUFFIX ."\n";
										
							$sale_item_price = $code_price;
							$saved_per_item = $item_quantity * ($shop_items_info['price'] - $code_price);
							$valid_code_entered = 1;
								
						}
						else
						{							
							echo TAB_13.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($shop_items_info['price'], 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n";
								
							$sale_item_price = $shop_items_info['price'];
							$saved_per_item = 0;
								
						}
												
						echo TAB_12.'</td>' ."\n";
						
						//	product Quantity INPUT
						if ($shop_items_info['max_quantity_allow'] != 0 AND $shop_items_info['max_quantity_allow'] != '')
						{
							$maxlength = strlen ($shop_items_info['max_quantity_allow']);
						}
						else {$maxlength = 6;}
						
						echo TAB_12.'<td class="ShopCartTableCell" title="Enter a new quantity for this product here" >' ."\n";
							echo TAB_13.'<input class="ShopCartTable" name="quantity_'.$prod_id.'" type="text" value="'.$item_quantity.'"'
									.' size="3" '."\n".TAB_14.'maxlength="'.$maxlength.'" onchange="this.form.submit()" />' ."\n"; // may change
						echo TAB_12.'</td>' ."\n";					

						//	Total of each product
						$item_total_cost = $item_quantity * $sale_item_price;
						echo TAB_12.'<td class="ShopCartTableCell" title="The Total Price for each Product" >' ."\n";
							echo TAB_13.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($item_total_cost, 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n";
						echo TAB_12.'</td>' ."\n";
						
						$grand_total_price = $grand_total_price + $item_total_cost;
						$grand_total_save = $grand_total_save + $saved_per_item;
						
						//	Remove Item (link)
						echo TAB_12.'<td class="ShopCartTableCell" >' ."\n";
							echo TAB_13.'<a class="ShopCartRemoveLink" href="'.$_SERVER['PHP_SELF'].'?p='.$page_id
										.'&amp;cart_remove='.$shop_items_info['prod_id'].'&amp;view='.$view_set.'"' ."\n";
								echo TAB_14.' title="Remove all quantities of: &#39;'.$shop_items_info['item_name'].'&#39; from your cart" >' ."\n";
								echo TAB_14.'<img class="TinyThumb" src="/images_misc/trash_can_red.png" '
										.' alt="remove item from cart" />' ."\n";
							echo TAB_13.'</a>' ."\n";
						echo TAB_12.'</td>' ."\n";
						
					echo TAB_11.'</tr>' ."\n";

					//	Get some shipping info while we are geting Item
					$total_calculated_quantity = $total_calculated_quantity + $shop_items_info['ship_item_quant_value'] * $item_quantity;
					
				}
				
				//	Do any of the On-board cart items have discounted by coupon prices ??
				//	Do coupon / promo entry:
				if ($promo_maxlength > 0)
				{
					
					echo TAB_11.'<tr class="ShopCartTableFooter" >' ."\n";
						echo TAB_12.'<td colspan="6" class="ShopCartTableCell ShopCartTablePromo" >'. "\n";
							echo TAB_13.'<p>Do you have a <span class="Bold" >Promo</span> or <span class="Bold" >'
										.'Coupon Code</span>? - enter it here:</p>' ."\n";
							echo TAB_13.'<input class="ShopCartTable" name="promo_code" type="text" value="'.$promo_code.'"'
									.' size="'.$promo_maxlength.'" maxlength="'.$promo_maxlength.'" onchange="this.form.submit()" />' ."\n";
							echo TAB_13.'<input type="submit" class="CartUpdateButton" value="Enter" />' ."\n"; 	
									
					//	Shop msg if code in valid / invalid				
					if ($promo_code != '' AND $valid_code_entered == 1)
					{
						echo TAB_13.'<span class="WarningMSG" >Valid Code entered !</span>'. "\n";
					}
					if ($promo_code != '' AND $valid_code_entered == 0)
					{
						echo TAB_13.'<span class="WarningMSG" >Code NOT Valid</span>'. "\n";
					}						
						
					if ($grand_total_save > 0)
					{
						echo TAB_13.'<p>You have saved: <span class="ShopItemPrice" >'
									.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($grand_total_save, 2).SHOP_CURRENCY_SYMBOL_SUFFIX
								.'</span> on this order !' ."\n";									
					}
						
						echo TAB_12.'</td>' ."\n";
					echo TAB_11.'</tr>' ."\n";			
				}	
					
					//	TOTALS and UPDATE BUTTON
					$total_calculated_quantity = ceil($total_calculated_quantity);
					
					echo TAB_11.'<tr class="ShopCartTableFooter" >' ."\n";
						echo TAB_12.'<td colspan="3" class="ShopCartTableCell ShopCartTableSpace" >'. "\n";
							echo TAB_13.'<p>est. N# of postage items: <span class="Bold" >'.$total_calculated_quantity.'</span></p>' ."\n";
						echo TAB_12.'</td>' ."\n";
						
						echo TAB_12.'<td class="ShopCartTableTotal ShopCartTableCell" colspan="2" >' ."\n";
							echo TAB_12.'Total: ' ."\n";
						//echo TAB_12.'</td>' ."\n";

						//	Cart Total
							echo TAB_13.'<span class="ShopCartTableTotal" >' ."\n";
								echo TAB_14.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($grand_total_price, 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n";
							echo TAB_13.'</span>' ."\n";
							
						echo TAB_12.'</td>' ."\n";
						
							//	Update Button				
						echo TAB_12.'<td class="ShopCartTableCell" >' ."\n";
							echo TAB_12.'<input type="submit" class="CartUpdateButton" value="Update" />' ."\n";
							echo TAB_12.'<input type="hidden" name="cart_update" value="yes" />' ."\n";						
						echo TAB_12.'</td>' ."\n";
					echo TAB_11.'</tr>' ."\n";
					
				echo TAB_10.'</table>' ."\n";
				
			echo TAB_9.'</form>' ."\n";
			
						
			//	Do LInks / Buttons	-----------------------------------------------------------------------------------------------------------------------------------------------------------------
		
				//	Empty Cart link
				$empty_cart_query_str = $query_str.'&amp;empty_cart=1';
				echo TAB_10.'<p class="ShopEmptyCartButton" >' ."\n";		
					echo TAB_11.'<a class="ShopEmptyCartButton" href="'.$_SERVER['PHP_SELF'].$empty_cart_query_str.'" '		
								. 'title="Completly Empty your Cart" >[ Empty cart ]</a>'. "\n";		
				echo TAB_10.'</p>' ."\n";
				
			echo TAB_9.'</div>'."\n";	//	end 	Cart Contents Div
			
			echo TAB_9.'<div class="ShopDiv" id="ShopNavButtons" >'."\n";
			
				echo TAB_10.'<p class="ShopGoCheckoutButton" >' ."\n";		
				
				//	 Go to Check=out
				if ( $view_set != 'checkout' )
				{
					$view_cart_query_str = $query_str.'&amp;view=checkout';
					echo TAB_11.'<a class="ShopGoCheckoutButton" href="'.$_SERVER['PHP_SELF'].$view_cart_query_str.'" '		
								. 'title="Go to the Checkout" >Proceed to Check-out</a>'. "\n";
					echo TAB_11.'<span class="Large" > OR </span>'. "\n";

					//	OR Continue Shopping
					$view_cart_query_str = $query_str.'&amp;view=browse';
					echo TAB_11.'<a class="ShopGoCheckoutButton" href="'.$_SERVER['PHP_SELF'].$view_cart_query_str.'" '		
								. 'title="Return to the Product Listing to browse more items" >Continue Shopping</a>'. "\n";
				
				}
			
				echo TAB_10.'</p>' ."\n";

			echo TAB_9.'</div>'."\n";	//	end 	Nav buttons Div

		echo TAB_8.'</div>'."\n";		
	
	}
					
?>