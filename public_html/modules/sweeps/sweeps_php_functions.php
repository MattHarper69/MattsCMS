<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	//	Manage Session stuff for Shopping Cart
	function SweepsCartManage( $query_str )
	{	


		//--------- Empty CART
		if (isset($_GET['empty_cart'])) 
		{
			EmptyCartAndReset();
			header('location: '.$_SERVER['PHP_SELF'].$query_str.'&view=browse');
			exit();
		}

			
		// --------Add item to the the CART  array
		if (isset($_REQUEST['add2cart']))	
		{
			
			//	get item_id of product add - see if it exists
			$mysql_err_msg = 'Product ID unavailable';	
			$sql_statement = 'SELECT item_id FROM sweeps_cat_asign WHERE prod_id = "'.$_REQUEST['add2cart'].'"';
											
			$result = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			$new_item_id = $result['item_id'];			
			
			// only go ahead if item exists
			if ($new_item_id != '' AND $new_item_id != NULL)
			{

				//	if cart is empty just add the product
				if (!isset($_SESSION['cart_items']) )
				{
					//$_SESSION['cart_items'][$_REQUEST['add2cart']]++;
					$_SESSION['cart_items'][$_REQUEST['add2cart']] = 1;
				}

				//	if not empty check contents for item_id already existing
				else
				{
					//	if this prod id already exists...just increase it
					if (isset($_SESSION['cart_items'][$_REQUEST['add2cart']]))
					{
						$_SESSION['cart_items'][$_REQUEST['add2cart']]++;
				
					}
					
					//	OR perform prod_id to item id matching
					else
					{


						$match_found = FALSE;	
						foreach ( $_SESSION['cart_items'] as $prod_id => $what_ever )
						{	
							$sql_statement = 'SELECT item_id FROM sweeps_cat_asign WHERE prod_id = "'.$prod_id.'"';
													
							$result2 = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
							
							
							if ($new_item_id == $result2['item_id']) 
							{
								$match_found = TRUE;
								$old_prod_id = $prod_id;
							}
			
						}
						
						//	If No match... just increse it
						if ($match_found != TRUE )
						{
							if (isset($_SESSION['cart_items'][$_REQUEST['add2cart']]))
							{
								$_SESSION['cart_items'][$_REQUEST['add2cart']]++;						
							}
							else
							{
								$_SESSION['cart_items'][$_REQUEST['add2cart']] = 1;
							}
								
						}
							
						//	if a prod_id already exists with the same item_id.....Merge prod_ids to new one and remove the old one
						else	
						{
							$_SESSION['cart_items'][$_REQUEST['add2cart']] = $_SESSION['cart_items'][$old_prod_id] + 1;
							unset ($_SESSION['cart_items'][$old_prod_id]);
						}
						
					}
				
				}
				

				$_SESSION['last_item_added'] = $_REQUEST['add2cart'];

				//	Check not over Maximum Qunatity and not past event_close_date
				checkQuantity();
				
				//	Do email alert if set to on
				if (ALERT_ADD_TO_CART == 1)
				{
					include ('sweeps_add_item_alert.php');
				}
				
				unset($_SESSION['check_out_stage']);	//	force user to enter details again to be aware of possible location restictions
				
				$query_str .= '&view=cart';
				header('location: '.$_SERVER['PHP_SELF'].$query_str); 
				exit();	 		
			
			}
			  
 			$query_str .= '&view=browse';
			header('location: '.$_SERVER['PHP_SELF'].$query_str); 
			exit();	 
			
		}
		
		// --------Delete item from the CART  array
		if (isset($_REQUEST['cart_remove'])) 
		{
			//	remove item
			unset ( $_SESSION['cart_items'][$_REQUEST['cart_remove']]);
			
			//	now unset cart if no items onboard - and set view to browse
			if (count($_SESSION['cart_items']) < 1) 
			{
				unset ( $_SESSION['cart_items']);
				unset ( $_SESSION['checkout_read_rules']);
				$query_str .= '&view=browse';
				header('location: '.$_SERVER['PHP_SELF'].$query_str); 
				exit();	  				
			}
			
			unset($_SESSION['check_out_stage']);	//	force user to enter details again to be aware of possible location restictions
			
			$query_str .= '&view='.$_REQUEST['view'];
			header('location: '.$_SERVER['PHP_SELF'].$query_str); 
			exit();	  

		}

		// --------Update CART  array
		if (isset($_REQUEST['cart_update']) AND $_REQUEST['cart_update'] == 'yes' AND isset($_SESSION['cart_items'])) 
		{

			unset($_SESSION['check_out_stage']);
		
			foreach ( $_SESSION['cart_items'] as $prod_id => $what_ever )
			{	
				$new_quantity = $_REQUEST['quantity_'.$prod_id];
				
				//	remove item if quantity Zero or no entry			
				if ($new_quantity == 0 OR $new_quantity == "")
				{ unset ( $_SESSION['cart_items'][$prod_id]);}			
		
				else 
				{	
					//	need to strip non-numbers
					$new_quantity = preg_replace('/\D/', '',$new_quantity);	
					
					if (is_numeric($new_quantity ))						
					{ 
						$_SESSION['cart_items'][$prod_id] = $new_quantity;

						//	Check not over Maximum Qunatity and not past event_close_date
						checkQuantity();
						
					}	
		
				}

			}

			//	has a promo CODE been entered ??
			if 
			( 
					isset ($_REQUEST['promo_code']) AND isset($_SESSION['cart_items']) 
				AND $_REQUEST['promo_code'] != '' AND $_REQUEST['promo_code'] != NULL
			)
			{ 
				$valid_code_entered = 0;
				$_SESSION['promo_code'] = trim($_REQUEST['promo_code']); 
				
				//	Do alert if CODE is incorrect				
				foreach ( $_SESSION['cart_items'] as $prod_id => $item_quantity )
				{
					
					
					for ($p=1; $p<5; $p++)
					{							
						
						$mysql_err_msg = 'Product Coupon Code Info unavailable';	
						$sql_statement = 'SELECT promo_code_'.$p.' FROM sweeps_cat_asign, sweeps_items WHERE'
						
														.' sweeps_cat_asign.prod_id = "'.$prod_id.'"'
														.' AND sweeps_cat_asign.item_id = sweeps_items.item_id'
														;

					
						$result3 = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
				
						if (strtolower($result3['promo_code_'.$p]) == strtolower($_SESSION['promo_code']))
						{ $valid_code_entered = 1;}
					}
					
				}
					
				if ($valid_code_entered != 1)
				{echo TAB_5.'<script language="Javascript" >alert ("The Promo / Coupon Code you entered was invalid")</script>';}
				
			}
			else {unset($_SESSION['promo_code']);}
			

			
			//	now unset cart if no items onboard
			if (count($_SESSION['cart_items']) < 1) {unset ( $_SESSION['cart_items']);}
			
			$query_str .= '&view='.$_REQUEST['view'];
			//header('location: '.$_SERVER['PHP_SELF'].$query_str); 
			//exit();

	

		}

		//	update Voting on product
		if (isset($_REQUEST['rate'])) 
		{		
			//	get the Item ID
			$mysql_err_msg = 'Product ID for voting unavailable';	
			$sql_statement = 'SELECT sweeps_items.item_id, votes FROM sweeps_items, sweeps_cat_asign'

													.' WHERE sweeps_cat_asign.prod_id = "'.$_REQUEST['prod_id'].'"'
													.' AND sweeps_cat_asign.item_id = sweeps_items.item_id'
													.' AND active = "on"';
							
			$result = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));

			$item_id = 	$result['item_id'];
			$existing_votes = 	$result['votes'];			
				
			if (!isset($_SESSION['vote_item_id'])) {$_SESSION['vote_item_id'] = array();}
		
			//	check if user already voted on this item
			if (in_array($item_id, $_SESSION['vote_item_id']))
			{
				echo '<script language="Javascript">alert ("Sorry, One Vote per '.SHOP_ITEM_ALIAS.' Allowed")</script>';							
			}
			
			else
			{ 

				$_SESSION['vote_item_id'][] = $item_id;
				
				//---update rating and Votes for item
				$mysql_err_msg = 'Updating customer rating information';
			
				// calculate new rating
				if ($existing_votes) { $sql = ' rating = ('.$_REQUEST['rate'].' + (rating * votes))/(votes + 1)';}
				else { $sql = ' rating = "'.$_REQUEST['rate'].'"';}		//	if no existing votes, use new rate
				$sql_statement = 'UPDATE sweeps_items SET'

														. $sql
														.', votes = votes + 1'														
														.' WHERE item_id = "'.$item_id.'"';

				ReadDB ($sql_statement, $mysql_err_msg);
				
				$query_str = str_replace ('rate='.$_REQUEST['rate'], '', $query_str);
				$query_str .= '&view=browse';				
				header('location: '.$_SERVER['PHP_SELF'].$query_str); 
				exit();	 			
			}
					
		}

		//	 Reset last browser view settings
		if (isset($_GET['reset_browse'])) 
		{
			unset($_SESSION['last_cat_id_viewed']);
			unset($_SESSION['last_item_id_viewed']);
			
			$query_str = '?p='.SHOP_PAGE_ID;
			
			header('location: '.$_SERVER['PHP_SELF'].$query_str.'&view=browse');
			exit();
		}
		
		//	KILL THE SESSION
		if (isset($_GET['session_destroy'])) 
		{
			session_destroy();
			header('location: '.$_SERVER['PHP_SELF'].$query_str);
			exit();			
		}		

		//--------- UNSET PAYNOW BUTTON
		if (isset($_GET['unsetpaynow'])) 
		{
			unset($_SESSION['canx_paypal']);
			header('location: '.$_SERVER['PHP_SELF'].$query_str);
			exit();
		}		
		
		
		
	}
	
	
//==================================================================================================================================	
	

	//	Check not over Maximum Qunatity, items in stock sufficient and not past event_close_date
	function checkQuantity()
	{
		foreach ( $_SESSION['cart_items'] as $prod_id => $quantity )
		{	
			//	get the Items MAX quantity allowed
			$mysql_err_msg = 'Product ID for quantity checking unavailable';	
			$sql_statement = 'SELECT '
											.' max_quantity_allow'
											.',limit_stock_active'
											.',in_stock'
											.',event_close_date'
											.',event_start_date'
											.' FROM sweeps_items, sweeps_cat_asign'

											.' WHERE sweeps_cat_asign.prod_id = "'.$prod_id.'"'
											.' AND sweeps_cat_asign.item_id = sweeps_items.item_id';
									
			$result = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			$max_quantity = $result['max_quantity_allow'];
			$in_stock = $result['in_stock'];
			$event_close_date = $result['event_close_date'];
			$event_start_date = $result['event_start_date'];
			
			//	remove from cart if past event_close_date
			if 
			(
					time() > strtotime($event_close_date) AND $event_close_date != 0 
				OR 	time() < strtotime($event_start_date) AND $event_start_date != 0
				
			)
			
			{
				
				unset ($_SESSION['cart_items'][$prod_id]);
				unset ($_SESSION['check_out_stage']);
				
				//	now unset cart if no items onboard
				if (count($_SESSION['cart_items']) < 1) {unset ( $_SESSION['cart_items']);}				
				
				$query_str = '?p='.SHOP_PAGE_ID;
				
				header('location: '.$_SERVER['PHP_SELF'].$query_str.'&view=browse');
				exit();					
				//echo '<script language="Javascript" >alert ("Sorry, an '.SHOP_ITEM_ALIAS.' in you shopping cart has now closed")</script>';
					
			}				

			//	Check if in-stock
			if ($in_stock != '' AND $result['event_close_date'] != 'on' AND $quantity > $in_stock)
			{

				$_SESSION['cart_items'][$prod_id] = $in_stock;
					
				echo '<script language="Javascript" >alert ("Sorry, there are currently only '.$in_stock.' tickets left")</script>';
					
			}				
			
			// set quantity to MAX if over
			elseif ($max_quantity != 0 AND $max_quantity != '' AND $quantity > $max_quantity)	
			{

				$_SESSION['cart_items'][$prod_id] = $max_quantity;
					
				echo '<script language="Javascript" >alert ("Sorry, a maximum of '.$max_quantity.' per customer is allowed")</script>';
					
			}					
							
		}					
		
	}
		
		
//=================================================================================================================================================	
	
	//	function to display brief item info as listing in <table> format with add 2 cart button
	function GetItemInfo ($prod_id)
	{
		// get all item info
		$mysql_err_msg = 'Product information unavailable';	
	$sql_statement = 'SELECT'
	
							.'   sweeps_items.item_id'
							.',  prod_id'
							.', display_image'
							.', item_name'
							.', event_close_date'
							.', event_start_date'
							.', price'
							.', list_price'
							.', display_list_price'
							.', display_buynow'
							.', in_stock'							
							.', max_quantity_allow'
	
								.' FROM sweeps_items, sweeps_cat_asign'

								.' WHERE sweeps_cat_asign.prod_id = "'.$prod_id.'"'
								.' AND sweeps_cat_asign.item_id = sweeps_items.item_id'
								.' AND active = "on"'
								.' ORDER BY seq'
								;						
		$sweeps_item_result = ReadDB ($sql_statement, $mysql_err_msg);

		
		while ($sweeps_item_info = mysql_fetch_array ($sweeps_item_result))
		{

			//echo TAB_10.'<tr class="ShopItemList" >' ."\n";
				
				$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;prod_id='.$sweeps_item_info['prod_id'].'&amp;view=browse';
			
				//	product image (link)
				echo TAB_11.'<td class="ShopCartTableCell ShopItemListImage" >' ."\n";
				
				$sql_statement = 'SELECT image_file_name FROM sweeps_item_images, sweeps_items'

															.' WHERE sweeps_items.primary_image_id = sweeps_item_images.image_id'
															.' AND sweeps_items.item_id = '.$sweeps_item_info['item_id'];
					
				$sweeps_image_image_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
				$image_filename = $sweeps_image_image_info['image_file_name'];
				
				if ($sweeps_item_info['display_image'] == 'on' AND file_exists('_images_shop/'.$image_filename) AND $image_filename != '')
				{
					echo TAB_12.'<a class="ShopItemLinkThumb" href="'.$href .'"' ."\n";
						echo TAB_13.' title="View the details of '.SHOP_ITEM_ALIAS.':&quot;'.$sweeps_item_info['item_name'].'&quot;" >' . "\n";
						echo TAB_13.'<img class="TinyThumb" src="/_images_shop/'.$image_filename.'" '
								.' alt="image of product" /><br/>more info...' ."\n";
					echo TAB_12.'</a>' ."\n";			
				}

					
				echo TAB_11.'</td>' ."\n";
				
				//	product Name (link)
				echo TAB_11.'<td class="ShopCartTableCell ShopItemListName" >' ."\n";
					echo TAB_12.'<a class="ShopItemLink" href="'.$href.'"' ."\n";
						echo TAB_13.' title="View the details of '.SHOP_ITEM_ALIAS.':&quot;'.$sweeps_item_info['item_name'].'&quot;" >' ."\n";
						echo TAB_13.$sweeps_item_info['item_name'] ."\n";
					echo TAB_12.'</a>' ."\n";
				echo TAB_11.'</td>' ."\n";	

				//	product PRICE
				echo TAB_11.'<td class="ShopCartTableCell ShopItemPrice" >' ."\n";
					echo TAB_12.'<p class="ShopItemPrice" >'
							.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($sweeps_item_info['price'], 2).SHOP_CURRENCY_SYMBOL_SUFFIX 
						.'</p>' ."\n";
					
				if 
				(
						$sweeps_item_info['display_list_price'] != ''
					AND $sweeps_item_info['list_price'] != '' 
					AND $sweeps_item_info['list_price'] != NULL 
					AND $sweeps_item_info['list_price'] != 0 
				)
				{ 
					$list_price = $sweeps_item_info['list_price']; 
					echo TAB_12.'<p class="ShopItemListPrice" >RRP:<span class="Strike" >'
							.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($list_price, 2).SHOP_CURRENCY_SYMBOL_SUFFIX
					.'</span></p>' ."\n";				
				}
				
				echo TAB_11.'</td>' ."\n";
				
				//	add to Cart
				echo TAB_11.'<td class="ShopCartTableCell ShopItemListAdd2Cart" >' ."\n";
				
				if ($sweeps_item_info['display_buynow'] == 'on' AND $sweeps_item_info['in_stock'] > 0)
				{
					if
					( 
							isset($_SESSION['cart_items'][$prod_id])						
						AND	$sweeps_item_info['max_quantity_allow'] != 0 AND $sweeps_item_info['max_quantity_allow'] != ''
						AND	$_SESSION['cart_items'][$prod_id] >= $sweeps_item_info['max_quantity_allow']
					)					
					{
						echo TAB_12.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="maximum allowed already in cart" alt="disabled Add to Cart button" />'."\n";						
						echo TAB_12.'<p class="WarningMSGSmall" >( maximum allowed already in cart )</p>'."\n";;
					}
					
					///		DISABLE if after event close date-time
					elseif ( time() < strtotime($sweeps_item_info['event_start_date']) AND $sweeps_item_info['event_start_date'] != 0)
					{					
						echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="This '.SHOP_ITEM_ALIAS.' is now closed" alt="disabled Add to Cart button" />'."\n";
						$start_date = date("D jS M, Y @ g:ia", strtotime($sweeps_item_info['event_start_date']));									
						echo TAB_13.'<p class="WarningMSG" >This '.SHOP_ITEM_ALIAS.' goes on sale:<br/>'.$start_date.'</p>'."\n";	
					}					
					elseif ( time() > strtotime($sweeps_item_info['event_close_date']) AND $sweeps_item_info['event_close_date'] != 0)
					{					
						echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="This '.SHOP_ITEM_ALIAS.' is now closed" alt="disabled Add to Cart button" />'."\n";					
						echo TAB_13.'<p class="WarningMSG" >This '.SHOP_ITEM_ALIAS.' is now closed</p>'."\n";
					}
					
					else
					{
						echo TAB_12.'<a href="index.php?p='.SHOP_PAGE_ID.'&amp;add2cart='.$sweeps_item_info['prod_id'].'" >'."\n";

							echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON.'" '."\n";							
							echo TAB_13.'title="Click here to add: '.$sweeps_item_info['item_name'].' to your Shopping Cart." alt="Add to Cart" />'."\n";

						echo TAB_12.'</a>'."\n"; 
					}
					
				}
				
				//	Item Not in stock
				if ($sweeps_item_info['display_buynow'] == 'on' AND $sweeps_item_info['in_stock'] < 1)
				{
					echo TAB_12.'<h4 class="Notice" >Sold out</h1>' ."\n";
				}
				
				echo TAB_11.'</td>' ."\n";								

			//echo TAB_10.'</tr>' ."\n";

			
			
		}	
	
	}

	function EmptyCartAndReset()
	{
		//	EMPTY the CART			
		unset ($_SESSION['cart_items']);

		//	Reset all
		unset($_SESSION['check_out_stage']);
		unset($_SESSION['pay_method_type']);	
		unset($_SESSION['Payment_Amount']);
		unset($_SESSION['invoice_num']);
		unset($_SESSION['PaymentType']);
		unset($_SESSION['nvpReqArray']);
		unset($_SESSION['pp_token']);	
		unset($_SESSION['payer_id']); 
		unset($_SESSION['currencyCodeType']); 	
		unset($_SESSION['txn_id']);	
		//unset($_SESSION['total_payment_amount_payed']);	//	not needed ??
		unset($_SESSION['total_amount_payed']);
		unset($_SESSION['payment_status']);
		unset($_SESSION['checkout_read_rules']);
	}



//=================================================================================================================================================

	// create a list or all categories arranged in by parent sub categories
	function FillTheCatList($parent_id, $level) //completely expand category tree
	{

		$mysql_err_msg = 'Category information unavailable';
		$sql_statement = 'SELECT * FROM sweeps_categories WHERE parent_id = "'.$parent_id.'" ORDER BY seq';
					
		$result = ReadDB ($sql_statement, $mysql_err_msg);
			
		$a = array(); //parents
		
		while ($row = mysql_fetch_array ($result))
		
		{
			$row[7] = $level;
			$a[] = $row;
			
			//process subcategories
			$b = FillTheCatList($row[0], $level + 1);
			
			//add $b[] to the end of $a[]
			for ($j = 0; $j < count($b); $j++)
			{
				$a[] = $b[$j];
			}
		}
		return $a;	
	}


//=================================================================================================================================================	
	
	
	//	Get any Restricted Countries for items in cart and put in array
	function GetEventAllowedCountries ($item_id, $allowed_countries)
	{
		$more_allowed_countries = array();
		
		$mysql_err_msg = 'failed to retrieve '.SHOP_ITEM_ALIAS.' - Country restriction info';
		$sql_statement = 'SELECT'					
									.'  shop_address_countries.country_id'
									.', country_name'
									
									.' FROM sweeps_item_location_restrictions'
									.', shop_address_countries'
		
									.' WHERE item_id = "'.$item_id.'"'
									.' AND shop_address_countries.country_id = sweeps_item_location_restrictions.country_id'
									.' ORDER BY seq'
									;
								
		$restricted_countries_result = ReadDB ($sql_statement, $mysql_err_msg);

		while($restricted_countries_info = mysql_fetch_array ($restricted_countries_result))
		{
			$more_allowed_countries[$restricted_countries_info['country_id']] = $restricted_countries_info['country_name'];
						
		}
		
		//$more_allowed_countries = array_unique ($more_allowed_countries);
	
		if (count($more_allowed_countries) > 0 AND count($allowed_countries) > 0 )
		{
			
			$new_allowed_countries = array_intersect_assoc ($more_allowed_countries, $allowed_countries);
		}

		else
		{
			$new_allowed_countries = $more_allowed_countries;	
		}

		return $new_allowed_countries;
		
	}					
					
	//	Get any Restricted States for these items
	function GetEventAllowedStates ($item_id, $allowed_states)				
	{
		$more_allowed_states = array();
		
		$mysql_err_msg = 'failed to retrieve '.SHOP_ITEM_ALIAS.' - State restriction info';
		$sql_statement = 'SELECT'
									.'  shop_address_states.state_id'
									.', state_name' 
									
									.' FROM sweeps_item_location_restrictions'
									.', shop_address_states'
		
									.' WHERE item_id = "'.$item_id.'"'
									.' AND shop_address_states.state_id = sweeps_item_location_restrictions.state_id'
									.' ORDER BY shop_address_states.state_id,seq'
									;
						
		$restricted_states_result = ReadDB ($sql_statement, $mysql_err_msg);

		while($restricted_states_info = mysql_fetch_array ($restricted_states_result))
		{
			$more_allowed_states[$restricted_states_info['state_id']] = $restricted_states_info['state_name'];
				
		}
	
		//$more_allowed_states = array_unique ($more_allowed_states);
	
		if (count($more_allowed_states) > 0 AND count($allowed_states) > 0 )
		{			
			$new_allowed_states = array_intersect_assoc ($more_allowed_states, $allowed_states);
		}

		else
		{
			$new_allowed_states = $more_allowed_states;	
		}
	
		return $new_allowed_states;
	
	}
	
?>