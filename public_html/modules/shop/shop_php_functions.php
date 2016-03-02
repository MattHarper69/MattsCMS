<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	//	Manage Session stuff for Shopping Cart
	function ShopCartManage( $query_str )
	/* function ShopCartManage() */	
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
			//	Get quantity to add if specified
			if (isset($_REQUEST['add2cart_quantity']))
			{
				$add2cart_quantity = $_REQUEST['add2cart_quantity'];
			}
			else
			{
				$add2cart_quantity = 1;
			}
			
			
			//	if cart is empty just add the product
			if (!isset($_SESSION['cart_items']))
			{
				//$_SESSION['cart_items'][$_REQUEST['add2cart']] = 1;
				$_SESSION['cart_items'][$_REQUEST['add2cart']] = $add2cart_quantity;				
			}

			//	if not empty check contents for item_id already existing
			else
			{
				//	if this prod id already exists...just increase it
				if (isset($_SESSION['cart_items'][$_REQUEST['add2cart']]))
				{
					//$_SESSION['cart_items'][$_REQUEST['add2cart']]++;
					$_SESSION['cart_items'][$_REQUEST['add2cart']] = $_SESSION['cart_items'][$_REQUEST['add2cart']] + $add2cart_quantity;
				}
				
				//	OR perform prod_id to item id matching
				else
				{

					$mysql_err_msg = 'Product ID unavailable';	
					$sql_statement = 'SELECT item_id FROM shop_cat_asign WHERE prod_id = "'.$_REQUEST['add2cart'].'"';
												
					$result = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
					$new_item_id = $result['item_id'];
					
					$match_found = FALSE;	
					foreach ( $_SESSION['cart_items'] as $prod_id => $what_ever )
					{	
						$sql_statement = 'SELECT item_id FROM shop_cat_asign WHERE prod_id = "'.$prod_id.'"';
												
						$result2 = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
						
						
						if ($new_item_id == $result2['item_id']) 
						{
							$match_found = TRUE;
							$old_prod_id = $prod_id;
						}
		
					}
					
					//	If No match... just increse it
					if ($match_found != TRUE)
					{
						
						if (isset($_SESSION['cart_items'][$_REQUEST['add2cart']]))
						{
							//$_SESSION['cart_items'][$_REQUEST['add2cart']]++;
							$_SESSION['cart_items'][$_REQUEST['add2cart']] = $_SESSION['cart_items'][$_REQUEST['add2cart']] + $add2cart_quantity;
						}
						else
						{
							//$_SESSION['cart_items'][$_REQUEST['add2cart']] = 1;
							$_SESSION['cart_items'][$_REQUEST['add2cart']] = $add2cart_quantity;
						}						
					}
						
					//	if a prod_id already exists with the same item_id.....Merge prod_ids to new one and remove the old one
					else	
					{
						//$_SESSION['cart_items'][$_REQUEST['add2cart']] = $_SESSION['cart_items'][$old_prod_id] + 1;
						$_SESSION['cart_items'][$_REQUEST['add2cart']] = $_SESSION['cart_items'][$old_prod_id] + $add2cart_quantity;
						unset ($_SESSION['cart_items'][$old_prod_id]);
					}
					
				}
			
			}
			

			$_SESSION['last_item_added'] = $_REQUEST['add2cart'];

			checkQuantity();
			
			//	Do email alert if set to on
			if (ALERT_ADD_TO_CART == 1)
			{
				include ('shop_add_item_alert.php');
			}
	  
			$query_str .= '&view=cart';
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
				$query_str .= '&view=browse';
				header('location: '.$_SERVER['PHP_SELF'].$query_str); 
				exit();	  				
			}
			
			$query_str .= '&view='.$_REQUEST['view'];
			header('location: '.$_SERVER['PHP_SELF'].$query_str); 
			exit();	  

		}

		// --------Update CART  array
		if (isset($_REQUEST['cart_update']) AND $_REQUEST['cart_update'] == 'yes' AND isset($_SESSION['cart_items']) ) 
		{

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
						$sql_statement = 'SELECT promo_code_'.$p.' FROM shop_cat_asign, shop_items WHERE'
						
														.' shop_cat_asign.prod_id = "'.$prod_id.'"'
														.' AND shop_cat_asign.item_id = shop_items.item_id'
														;

					
						$result3 = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
				
						if (strtolower($result3['promo_code_'.$p]) == strtolower($_SESSION['promo_code']))
						{ $valid_code_entered = 1;}
					}
					
				}
					
				if ($valid_code_entered != 1)
				{echo '<script language="Javascript" >alert ("The Promo / Coupon Code you entered was invalid")</script>';}
				
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
			$sql_statement = 'SELECT shop_items.item_id FROM shop_items, shop_cat_asign'

													.' WHERE shop_cat_asign.prod_id = "'.$_REQUEST['prod_id'].'"'
													.' AND shop_cat_asign.item_id = shop_items.item_id'
													.' AND active = "on"';
							
			$result = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));

			$item_id = 	$result['item_id'];	
				
			if (!isset($_SESSION['vote_item_id'])) {$_SESSION['vote_item_id'] = array();}
		
			//	check if user already voted on this item
			if (in_array($item_id, $_SESSION['vote_item_id']))
			{
				echo '<script language="Javascript">alert ("Sorry, One Vote per Product Allowed")</script>';			
							
			}
			
			else
			{ 
			
				$_SESSION['vote_item_id'][] = $item_id;
				
				//---update rating and Votes for item
				$mysql_err_msg = 'Updating customer rating information';
				$sql_statement = 'UPDATE shop_items SET'

														.' rating = ('.$_REQUEST['rate'].' + (rating * votes))/(votes + 1)'
														.', votes = votes + 1'														
														.' WHERE item_id = "'.$item_id.'"';

				ReadDB ($sql_statement, $mysql_err_msg);
				
				$query_str = str_replace ('rate='.$_REQUEST['rate'], '', $query_str);
				$query_str .= '&view=browse';				
				header('location: '.$_SERVER['PHP_SELF'].$query_str); 
				exit();	 			
			}
					
		}
		
		//	Go Back to edit details in checkout
		if (isset($_GET['view']) AND $_GET['view'] == 'checkoutedit')
		{
			unset($_SESSION['check_out_state_2']);
			
			$query_str = '?p='.SHOP_PAGE_ID;
			
			header('location: '.$_SERVER['PHP_SELF'].$query_str.'&view=checkout');
			exit();		
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
	
	
//============================================================================================================================================================	
	

	//	Check not over Maximum Qunatity
	function checkQuantity()
	{
		foreach ( $_SESSION['cart_items'] as $prod_id => $quantity )
		{	
			//	get the Items MAX quantity allowed
			$mysql_err_msg = 'Product ID for quantity checking unavailable';	
			$sql_statement = 'SELECT max_quantity_allow FROM shop_items, shop_cat_asign'

														.' WHERE shop_cat_asign.prod_id = "'.$prod_id.'"'
														.' AND shop_cat_asign.item_id = shop_items.item_id';
									
			$result = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			$max_quantity = $result['max_quantity_allow'];

			// set quantity to MAX if over
			if ($max_quantity != 0 AND $max_quantity != '' AND $quantity > $max_quantity)	
			{

				$_SESSION['cart_items'][$prod_id] = $max_quantity;
					
				echo '<script language="Javascript" >alert ("Sorry, a maximum of '.$max_quantity.' per customer is allowed")</script>';
					
			}					
							
		}					
		
	}
		
		
//============================================================================================================================================================	
	
	//	function to display brief item info as listing in <table> format with add 2 cart button
	function GetItemInfo ($prod_id)
	{
		// get all item info
		$mysql_err_msg = 'Product information unavailable';	
		$sql_statement = 'SELECT * FROM shop_items, shop_cat_asign'

												.' WHERE shop_cat_asign.prod_id = "'.$prod_id.'"'
												.' AND shop_cat_asign.item_id = shop_items.item_id'
												.' AND active = "on"';
						
		$shop_item_result = ReadDB ($sql_statement, $mysql_err_msg);

		while ($shop_item_info = mysql_fetch_array ($shop_item_result))
		{

			echo TAB_10.'<tr class="ShopItemList" >' ."\n";
				
				$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;prod_id='.$shop_item_info['prod_id'].'&amp;view=browse';
			
				//	product image (link)
				echo TAB_11.'<td class="ShopCartTableCell ShopItemListImage" >' ."\n";
					echo TAB_12.'<a class="ShopItemLinkThumb" href="'.$href .'"' ."\n";
						echo TAB_13.' title="Click this link to view this product&#39;s details: '.$shop_item_info['item_name'].'" >' ."\n";
						echo TAB_13.'<img class="TinyThumb" src="/_images_shop/'.$shop_item_info['image_file'].'" '
								.' alt="image of product" /><br/>more info...' ."\n";
					echo TAB_12.'</a>' ."\n";
				echo TAB_11.'</td>' ."\n";
				
				//	product Name (link)
				echo TAB_11.'<td class="ShopCartTableCell ShopItemListName" >' ."\n";
					echo TAB_12.'<a class="ShopItemLink" href="'.$href.'"' ."\n";
						echo TAB_13.' title="Click this link to view this product&#39;s details: '.$shop_item_info['item_name'].'" >' ."\n";
						echo TAB_13.$shop_item_info['item_name'] ."\n";
					echo TAB_12.'</a>' ."\n";
				echo TAB_11.'</td>' ."\n";	

				//	product PRICE
				echo TAB_11.'<td class="ShopCartTableCell ShopItemPrice" >' ."\n";
					echo TAB_12.'<p class="ShopItemPrice" >'
							.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($shop_item_info['price'], 2).SHOP_CURRENCY_SYMBOL_SUFFIX 
						.'</p>' ."\n";
					
				if ($shop_item_info['list_price'] != '' AND $shop_item_info['list_price'] != NULL AND $shop_item_info['list_price'] != 0 )
				{ 
					$list_price = $shop_item_info['list_price']; 
					echo TAB_12.'<p class="ShopItemListPrice" >RRP:<span class="Strike" >'
							.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($list_price, 2).SHOP_CURRENCY_SYMBOL_SUFFIX
					.'</span></p>' ."\n";				
				}
				
				echo TAB_11.'</td>' ."\n";
				
				//	add to Cart
				echo TAB_11.'<td class="ShopCartTableCell ShopItemListAdd2Cart" >' ."\n";
				
				if ($shop_item_info['display_buynow'] == 'on' AND $shop_item_info['in_stock'] > 0 )
				{
					if
					( 
							$shop_item_info['max_quantity_allow'] != 0 AND $shop_item_info['max_quantity_allow'] != ''
						AND	isset($_SESSION['cart_items'][$prod_id])
						AND	$_SESSION['cart_items'][$prod_id] >= $shop_item_info['max_quantity_allow']
					)					
					{
						echo TAB_12.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="maximum allowed already in cart" alt="disabled Add to Cart button" />'."\n";						
						echo TAB_12.'<p class="WarningMSGSmall" >( maximum allowed already in cart )</p>'."\n";;
					}
					else
					{
						echo TAB_12.'<a href="index.php?p='.SHOP_PAGE_ID.'&amp;add2cart='.$shop_item_info['prod_id'].'" >'."\n";

							echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON.'" '."\n";							
							echo TAB_13.'title="Click here to add: '.$shop_item_info['item_name'].' to your Shopping Cart." alt="Add to Cart" />'."\n";

						echo TAB_12.'</a>'."\n"; 
					}
					
				}
				
				//	Item Not in stock
				if ($shop_item_info['display_buynow'] == 'on' AND $shop_item_info['in_stock'] < 1)
				{
					echo TAB_12.'<h4 class="Notice	" >Item Not in stock</h1>' ."\n";
				}
				
				echo TAB_11.'</td>' ."\n";								

			echo TAB_10.'</tr>' ."\n";

			
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
	
?>