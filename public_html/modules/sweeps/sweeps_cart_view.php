<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$allowed_countries = array();
	$allowed_states = array();
	
	$valid_code_entered = 0;	
	$can_not_checkout_error = FALSE;
	
	
	//	double check  cart NOT empty
	if (count($_SESSION['cart_items']) > 0)
	{		
	echo TAB_8.'<div class="ShopDiv" id="ShopCartView" >'."\n";
		
		echo TAB_9.'<h4 id="ShopCartHeading" >'.SHOP_CART_MAIN_HEADING .'</h4>' ."\n";	
		
		echo TAB_9.'<div id="ShopCartContents" >'."\n";
		
			echo TAB_9.'<form action="'.$_SERVER['PHP_SELF'].$query_str.'&amp;view='.$view_set.'" method="post" >' ."\n";
			
				echo TAB_10.'<table class="ShopCartTable" >' ."\n";
			
					echo TAB_11.'<tr class="ShopCartTableHeader" >' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" ></th>' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" >'.SHOP_ITEM_ALIAS.':</th>' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" >Price:</th>' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" >No. of tickets:</th>' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" >Total:</th>' ."\n";
						echo TAB_12.'<th class="ShopCartTableHeader" >Remove:</th>' ."\n";
					echo TAB_11.'</tr>' ."\n";			
				
				$grand_total_price = 0;
				$grand_total_save = 0;
				$num_products = 0;
				$total_calculated_quantity = 0;
				$pp_express_item_str = '';
				
				unset($promo_maxlength);
				
				//	Get Cart Contents:
				foreach ( $_SESSION['cart_items'] as $prod_id => $item_quantity )
				{

					//	read from db to get prices and other display info	----------
					$mysql_err_msg = 'Item information unavailable';	

					$sql_statement = 'SELECT'
					
											.'  prod_id'
											.', sweeps_items.item_id'
											.', display_image'
											.', item_name'
											.', primary_image_id'
											.', price'
											.', max_quantity_allow'
											.', promo_price_1'
											.', promo_price_2'
											.', promo_price_3'
											.', promo_price_4'
											.', promo_code_1'
											.', promo_code_2'
											.', promo_code_3'
											.', promo_code_4'
											.', ship_item_quant_value'
					
												//.' FROM sweeps_items, sweeps_cat_asign, sweeps_item_images'
												.' FROM sweeps_items, sweeps_cat_asign'
												
												.' WHERE sweeps_cat_asign.prod_id = "'.$prod_id.'"'
												.' AND sweeps_cat_asign.item_id = sweeps_items.item_id'
												//.' AND sweeps_item_images.image_id = sweeps_items.primary_image_id'
												.' AND sweeps_items.active = "on"'										
												;
						
					$sweeps_items_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	
					
					echo TAB_11.'<tr class="ShopCartTableRow" >' ."\n";
					
						$href = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']
								.'?p='.$page_id.'&amp;prod_id='.$sweeps_items_info['prod_id'].'&amp;view=browse';
				
						//	product image (link)
						$sql_statement = 'SELECT image_file_name FROM sweeps_item_images'

															.' WHERE item_id = "'.$sweeps_items_info['item_id'].'"'
															.' AND image_id = "'.$sweeps_items_info['primary_image_id'].'"'
															;
						
						$primary_image_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
						$primary_image_filename = $primary_image_info['image_file_name'];
						
						$img_url = '_images_shop/'.$primary_image_filename;						
							
						echo TAB_12.'<td class="ShopCartTableCell" >' ."\n";
						if($sweeps_items_info['display_image'] == 'on'  AND file_exists($img_url) AND $primary_image_filename != '')
						{
							echo TAB_13.'<a class="ShopItemLinkThumb" href="'.$href .'"' ."\n";
							echo TAB_14.' title="Click this link to view this '.SHOP_ITEM_ALIAS.'&#39;s details: '.$sweeps_items_info['item_name'].'" >' ."\n";
								echo TAB_14.'<img class="TinyThumb" src="'.$img_url.'" '
										.' alt="image of '.SHOP_ITEM_ALIAS.'" />' ."\n";
							echo TAB_13.'</a>' ."\n";					
						}
						else 
						{
							echo TAB_13.' No Image ' ."\n"; 
						}

						echo TAB_12.'</td>' ."\n";
					
						//	product Name (link)
						echo TAB_12.'<td class="ShopCartTableCell" >' ."\n";
							echo TAB_13.'<a class="ShopItemLink" href="'.$href.'"' ."\n";
							echo TAB_14.' title="Click this link to view this '.SHOP_ITEM_ALIAS.'&#39;s details: '.$sweeps_items_info['item_name'].'" >'."\n";
								echo TAB_14.$sweeps_items_info['item_name'] ."\n";
							echo TAB_13.'</a>' ."\n";
						echo TAB_12.'</td>' ."\n";		
						
						//	product PRICE
						echo TAB_12.'<td class="ShopCartTableCell" >' ."\n";
						
						//	has a coupon / promo code been entered

						$discount = 0;
						$is_code = 0;
//						$code_price = $sweeps_items_info['price'];					
						for ($p=1; $p<5; $p++)
						{						
							if ( $sweeps_items_info['promo_code_'.$p] != '' AND $sweeps_items_info['promo_code_'.$p] != NULL )					
							{					
								$is_code = 1;
																							
								//	use str length of code as max length for input
								if(!isset($promo_maxlength)){$promo_maxlength = 0;}
								
								if ( strlen($sweeps_items_info['promo_code_'.$p]) >= $promo_maxlength )
								{ $promo_maxlength = strlen($sweeps_items_info['promo_code_'.$p]); }
								if ( isset($_SESSION['promo_code']) AND strlen($_SESSION['promo_code']) >= $promo_maxlength )
								{ $promo_maxlength = strlen($_SESSION['promo_code']); }									
								
								if 
								( 
									isset($_SESSION['promo_code']) AND $_SESSION['promo_code'] != '' AND $_SESSION['promo_code'] != NULL
									AND strtolower($_SESSION['promo_code']) == 	strtolower($sweeps_items_info['promo_code_'.$p])
								)
								{
									$discount = 1;
									$code_price = $sweeps_items_info['promo_price_'.$p];							
								}
								
							}

						}
						
						//	discount code found and matched !! - apply discunt price
						if ($discount == 1)	
						{
							echo TAB_13.'<span class="Strike" >'
									.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($sweeps_items_info['price'], 2).SHOP_CURRENCY_SYMBOL_SUFFIX
								.'</span>'."\n";
							echo TAB_13.'<br/>' ."\n";
							echo TAB_13.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($code_price, 2)
										.SHOP_CURRENCY_SYMBOL_SUFFIX ."\n";
										
							$sale_item_price = $code_price;
							$saved_per_item = $item_quantity * ($sweeps_items_info['price'] - $code_price);
							$valid_code_entered = 1;
								
						}
						else
						{							
							echo TAB_13.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($sweeps_items_info['price'], 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n";
								
							$sale_item_price = $sweeps_items_info['price'];
							$saved_per_item = 0;
								
						}
												
						echo TAB_12.'</td>' ."\n";
						
						//	product Quantity INPUT
						if ($sweeps_items_info['max_quantity_allow'] != 0 AND $sweeps_items_info['max_quantity_allow'] != '')
						{
							$maxlength = strlen ($sweeps_items_info['max_quantity_allow']);
						}
						else {$maxlength = 6;}
						
						echo TAB_12.'<td class="ShopCartTableCell" title="Enter a new quantity for this '.SHOP_ITEM_ALIAS.' here" >' ."\n";
							echo TAB_13.'<input class="ShopCartTable" name="quantity_'.$prod_id.'" type="text" value="'.$item_quantity.'"'
									.' size="5" '."\n".TAB_14.'maxlength="'.$maxlength.'" onchange="this.form.submit()" />' ."\n"; // may change
						echo TAB_12.'</td>' ."\n";					

						//	Total of each product
						$item_total_cost = $item_quantity * $sale_item_price;
						echo TAB_12.'<td class="ShopCartTableCell" title="The Total Price for each '.SHOP_ITEM_ALIAS.'" >' ."\n";
							echo TAB_13.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($item_total_cost, 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n";
						echo TAB_12.'</td>' ."\n";
						
						$grand_total_price = $grand_total_price + $item_total_cost;
						$grand_total_save = $grand_total_save + $saved_per_item;
					
						//	Remove Item (link)
						echo TAB_12.'<td class="ShopCartTableCell" >' ."\n";
							echo TAB_13.'<a class="ShopCartRemoveLink" href="http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']
										.'?p='.$page_id.'&amp;cart_remove='.$sweeps_items_info['prod_id'].'&amp;view='.$view_set.'"' ."\n";
								echo TAB_14.' title="Remove all quantities of: &#39;'.$sweeps_items_info['item_name'].'&#39; from your cart" >' ."\n";
								echo TAB_14.'<img src="/_images_user/'.SHOP_ICON_DELETE_IMAGE.'" '
										.' alt="remove item from cart" />' ."\n";
							echo TAB_13.'</a>' ."\n";
						echo TAB_12.'</td>' ."\n";
						
					echo TAB_11.'</tr>' ."\n";

					//	Get some shipping info while we are geting Item
					$total_calculated_quantity = $total_calculated_quantity + $sweeps_items_info['ship_item_quant_value'] * $item_quantity;
					
					
					//	Get any Restricted Countries for these items
					$allowed_countries = GetEventAllowedCountries ($sweeps_items_info['item_id'], $allowed_countries);
		
					//	Get any Restricted States for these items
					$allowed_states = GetEventAllowedStates ($sweeps_items_info['item_id'], $allowed_states);
				
					//	Get any States belonging to Ristricted Countries
					//$more_restricted_states = GetEventStatesCountryRestictions ($sweeps_items_info['item_id'], $allowed_countries);
					
					//	merge arrays
					//$allowed_states = array_merge($allowed_states, $more_restricted_states);
				
					//	store in session so ajax update can access too
					$_SESSION['allowed_states'] = $allowed_states;
					
					//	build Paypal Express checkout NVP string (only used this if chose as a payment option)
					$item_name = urlencode($sweeps_items_info['item_name']);
					$pp_express_item_str 	.= '&L_PAYMENTREQUEST_0_NAME'. $num_products . '=' . $item_name
											.= '&L_PAYMENTREQUEST_0_QTY'. $num_products . '=' . $item_quantity
											.= '&L_PAYMENTREQUEST_0_AMT'. $num_products . '=' . $sale_item_price
											;
										
					$num_products++;

				}
				
				$_SESSION['pp_express_item_str'] = $pp_express_item_str;
				
				//	get country info from db to determin if all items are location restricted and build select box for checkout form
				$select_countries = array();
				
				$mysql_err_msg = 'Country Address Infomation unavailable';	
				$sql_statement = 'SELECT country_name, country_id FROM shop_address_countries'

														.' WHERE active = "on"'
														.' ORDER BY seq, country_name'
														;					

				$countries_result = ReadDB ($sql_statement, $mysql_err_msg);
			
				$all_countries_num = mysql_num_rows($countries_result);

				while( $countries_info = mysql_fetch_array($countries_result) )
				{
					//	add allowed countries
					if (in_array($countries_info['country_id'], array_flip($allowed_countries)))
					{
						$select_countries[$countries_info['country_name']] = $countries_info['country_id'];		
					}
			
				}	
				

				$select_countries = array_flip ($select_countries);

			
				//	get state info from db to determin if all items are location restricted and build select box for checkout form
				$select_states = array();
				
				$mysql_err_msg = 'State Address Infomation unavailable';	
				$sql_statement = 'SELECT state_name, state_id FROM shop_address_states'

														.' WHERE active = "on"'
														.' ORDER BY seq'
														;					

				$states_result = ReadDB ($sql_statement, $mysql_err_msg);
				
				$error_msg_state_restricted = '';

				$all_states_num = mysql_num_rows($states_result);

				while( $states_info = mysql_fetch_array($states_result) )
				{
					
					//	add allowed states
					if (in_array($states_info['state_id'], array_flip($allowed_states)))					
					{
						$select_states[$states_info['state_name']] = $states_info['state_id'];				
					}

				}
				
				$select_states = array_flip ($select_states);
				
				//	if no selectable states (all restricted) print warning
				if (count($select_states) == 0 OR count($select_countries) == 0)
				{
					// DO Restricted states Errorr
					echo TAB_13.'<p class="WarningMSG" >You can NOT Purchase all of these events because of area restrictions'
													.' - Please alter your purchases before proceeding</p>'. "\n";
					$can_not_checkout_error = TRUE;
					
				}	
				

				//	Display warning about location restrictions
				
				$error_msg_country_restricted = '';
				//$error_msg_state_restricted = '';
				$err_msg_area_restriction = '';
			
				if (count($select_countries) != $all_countries_num OR count($select_states) != $all_states_num)
				{
					echo TAB_11.'<tr class="ShopCartTableFooter" >' ."\n";
						echo TAB_12.'<td colspan="6" class="ShopCartTableCell ShopCartTableNotice" >'. "\n";

	
						if (count($select_countries) != $all_countries_num)
						{
							$err_msg_area_restriction = 'Country ';	//	goes in cart
							$error_msg_country_restricted = '* countries restrictions apply';		//	goes in checkout form
						}
						if (count($select_countries) != $all_countries_num AND count($select_states) != $all_states_num)
						{
							$err_msg_area_restriction .= 'and ';	//	goes in cart
						}
					
						if (count($select_states) != $all_states_num)
						{													
							$err_msg_area_restriction .= 'State';	//	goes in cart
							//$error_msg_state_restricted = '* state restrictions apply';		//	goes in checkout form
						}
						if ($num_products < 2)
						{
							$err_msg_area_restriction .= ' restrictions apply to this Event';	//	goes in cart
						}
						else
						{
							$err_msg_area_restriction .= ' restrictions apply to one or more of these Events';	//	goes in cart
						}					
						echo TAB_13.'<p class="Notice" >Notice: '.$err_msg_area_restriction.'</p>'. "\n";
							
						echo TAB_12.'</td>' ."\n";
					echo TAB_11.'</tr>' ."\n";					
				}
				
				//	Do any of the On-board cart items have discounted by coupon prices ??
				//	Do coupon / promo entry:
				if (isset( $promo_maxlength))
				{
					if(isset($_SESSION['promo_code'])) { $enter_promo_code = $_SESSION['promo_code'];}	//	avoid errors
					else {$enter_promo_code = '';}
					echo TAB_11.'<tr class="ShopCartTableFooter" >' ."\n";
						echo TAB_12.'<td colspan="6" class="ShopCartTableCell ShopCartTablePromo" >'. "\n";
							echo TAB_13.'<p>Do you have a <span class="Bold" >Promo</span> or <span class="Bold" >'
										.'Coupon Code</span>? - enter it here:</p>' ."\n";
							echo TAB_13.'<input class="ShopCartTable" name="promo_code" type="text" value="'.$enter_promo_code.'"'
									.' size="'.$promo_maxlength.'" maxlength="'.$promo_maxlength.'" onchange="this.form.submit()" />' ."\n";
							echo TAB_13.'<input type="submit" class="CartUpdateButton" value="Enter" />' ."\n"; 	
									
					//	Shop msg if code in valid / invalid				
					if (isset ($_SESSION['promo_code']) AND $valid_code_entered == 1)
					{
						echo TAB_13.'<span class="WarningMSG" >Valid Code entered !</span>'. "\n";
					}
					if (isset ($_SESSION['promo_code']) AND $valid_code_entered == 0)
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
							//	Empty Cart link
							$empty_cart_query_str = $query_str.'&amp;empty_cart=1';
							echo TAB_13.'<p class="ShopEmptyCartButton" >' ."\n";		
								echo TAB_14.'<a class="ShopEmptyCartButton" href="http://'.$_SERVER['SERVER_NAME']
										.$_SERVER['PHP_SELF'].$empty_cart_query_str.'" '		
											. 'title="Completly Empty your Cart" >[ Empty cart ]</a>'. "\n";		
							echo TAB_13.'</p>' ."\n";
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
			
						
			//	Do LInks / Buttons	------------------------------------------------------------------------------------
		

				
			echo TAB_9.'</div>'."\n";	//	end 	Cart Contents Div
			
			echo TAB_9.'<div class="ShopDiv" id="ShopNavButtons" >'."\n";
			
				echo TAB_10.'<p class="ShopButton" >' ."\n";		
				
				//	 Go to Check=out
				if ( $view_set != 'checkout' )
				{
					if ($can_not_checkout_error != TRUE)
					{
						$view_cart_query_str = $query_str.'&amp;view=checkout';
						echo TAB_11.'<a class="ShopButton" href="'.$_SERVER['PHP_SELF'].$view_cart_query_str.'" '		
									. 'title="Go to the Checkout" >Proceed to Check-out</a>'. "\n";
			
					}

 	
					//	OR Continue Shopping
					if(SHOP_DISPLAY_CONTINUE_SHOPPING == 1)
					{
						$view_cart_query_str = $query_str.'&amp;view=browse';
						echo TAB_11.'<a class="ShopButton" href="http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].$view_cart_query_str.'" '
								. 'title="Return to the '.SHOP_ITEM_ALIAS.' Listing to browse more items" >Continue Shopping</a>'. "\n";
					}


				}
			
				echo TAB_10.'</p>' ."\n";

			echo TAB_9.'</div>'."\n";	//	end 	Nav buttons Div

		echo TAB_8.'</div>'."\n";		
	
	}

?>