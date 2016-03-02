<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	if (isset($_REQUEST['view'])) {$view_set = $_REQUEST['view'];}
	else { $view_set = '';}	
	
	//	Build the query str for Re-direction
	$query_str = '?p='.SHOP_PAGE_ID;
					
	if ( isset($_REQUEST['s_cat']) AND $_REQUEST['s_cat'] != '' AND $_REQUEST['s_cat'] != NULL )
	{
		$query_str .= '&s_cat='.$_REQUEST['s_cat'];
	}
		
	if ( isset($_REQUEST['prod_id']) AND $_REQUEST['prod_id'] != '' AND $_REQUEST['prod_id'] != NULL )
	{
		$query_str .= '&prod_id='.$_REQUEST['prod_id'];
	}	
		
	echo TAB_8.'<div class="ShopDiv" id="ShopCartSide" >'."\n";
	
		echo TAB_9.'<h4 class="Shop" >'.SHOP_CART_SIDE_HEADING.'</h4>' ."\n";
		
		if (isset($_SESSION['cart_items']))
		{		
			//	Get Cart Contents:
			$cart_num_items = 0;
			$total_cart_cost = 0;
			foreach ( $_SESSION['cart_items'] as $prod_id => $item_quantity )
			{
	
				//	calculate number of items
				$cart_num_items = $cart_num_items + $item_quantity;
				
				
				//	read from db to get prices and other display info	----------
				$mysql_err_msg = 'Item information unavailable';	
				$sql_statement = 'SELECT * FROM shop_items, shop_cat_asign'

															.' WHERE shop_cat_asign.prod_id = "'.$prod_id.'"'
															.' AND shop_cat_asign.item_id = shop_items.item_id'
															.' AND active = "on"';
				
				$shop_items_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));					
						
				//	has a coupon / promo code been entered
				
				unset($discount);
				$price = $shop_items_info['price'];
				for ($p=1; $p<5; $p++)
				{																																
					if 
					( 					
							$shop_items_info['promo_code_'.$p] != '' AND $shop_items_info['promo_code_'.$p] != NULL 
						AND	isset($_SESSION['promo_code']) AND $_SESSION['promo_code'] != '' AND $_SESSION['promo_code'] != NULL
						AND strtolower($_SESSION['promo_code']) == 	strtolower($shop_items_info['promo_code_'.$p])					
					)
								
					{ $price = $shop_items_info['promo_price_'.$p]; }
	
				}

				
				//	caluclate total price
				$total_cart_cost = $total_cart_cost + $item_quantity * $price;
				
			}
			
			//	Display Number of Items
			echo TAB_9.'<p class="Shop" >Contains:</p>' ."\n";
			if ($cart_num_items == 1) {echo TAB_9.'<p class="Shop" ><span class="Bold" >1</span> item</p>' ."\n";}
			if ($cart_num_items > 1)  {echo TAB_9.'<p class="Shop" ><span class="Bold" >'.$cart_num_items.'</span> items</p>' ."\n";}			
			
			//	Display Number Total price
			echo TAB_9.'<p class="Shop" >Total Cost: <span class="Bold" >'
					.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($total_cart_cost , 2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</span></p>' ."\n";

		//	Do LInks / Buttons	-----------------------------------------------------------------------------------------------------------------------------------------------------------------			
			
			//	Empty Cart link
			$empty_cart_query_str = $query_str.'&amp;empty_cart=1';
			echo TAB_10.'<p class="ShopEmptyCartButton" >' ."\n";		
				echo TAB_11.'<a class="ShopEmptyCartButton" href="'.$_SERVER['PHP_SELF'].$empty_cart_query_str.'"'. "\n";		
				echo TAB_11.' title="Completly Empty your Cart" >[ Empty cart ]</a>'. "\n";		
			echo TAB_10.'</p>' ."\n";
		
			//	 View Cart Link			
			if ($view_set != 'checkout' AND $view_set != 'cart')
			{
				$view_cart_query_str = $query_str.'&amp;view=cart';
				echo TAB_10.'<p class="ShopViewCartButton" >' ."\n";		
					echo TAB_11.'<a class="ShopViewCartButton" href="'.$_SERVER['PHP_SELF'].$view_cart_query_str.'"'. "\n";		
					echo TAB_11.' title="View detailed contents of your Cart" >View cart</a>'. "\n";		
				echo TAB_10.'</p>' ."\n";
			}
			
			//	 Go to Check=out
			$view_cart_query_str = $query_str.'&amp;view=checkout';
			echo TAB_10.'<p class="ShopGoCheckoutButton" >' ."\n";		
				echo TAB_11.'<a class="ShopGoCheckoutButton" href="'.$_SERVER['PHP_SELF'].$view_cart_query_str.'"'. "\n";		
				echo TAB_11.' title="Go to the Checkout" >Check-out</a>'. "\n";		
			echo TAB_10.'</p>' ."\n";
			
		}
		
		else {echo TAB_9.'<p class="Shop" ><em>...is empty</em></p>' ."\n";}
			
	echo TAB_8.'</div>'."\n";	
			
?>