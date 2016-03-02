<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	get required data
	require_once (CODE_NAME.'_shop_configs.php');
	require_once ('sweeps/sweeps_php_functions.php');	

	//------------set default time zone
	date_default_timezone_set(SHOP_TIME_ZONE);
	
	//	 get all items set to display in the promo list
	$mysql_err_msg = 'Promo Event Listing unavailable';	
	$sql_statement = 'SELECT'
	
							.'  sweeps_items.item_id'
							.', prod_id'
							.', display_image'
							.', item_name'
							.', event_close_date'
							.', event_start_date'
							.', brief'
							.', description'
							.', price'
							.', list_price'
							.', display_list_price'
							.', display_buynow'
							.', max_quantity_allow'
							.', limit_stock_active'
							.', in_stock'
	
								.' FROM sweeps_items, sweeps_cat_asign'
								
								.' WHERE promo_display = "on"'
								.' AND sweeps_cat_asign.item_id = sweeps_items.item_id'
								.' AND sweeps_cat_asign.cat_id = sweeps_items.primary_cat_id'
								.' AND active = "on"'
								.' ORDER BY sweeps_cat_asign.seq'
								;
		
	$sweeps_promo_listing_result = ReadDB ($sql_statement, $mysql_err_msg);

if (mysql_num_rows ($sweeps_promo_listing_result) > 0)
{
	
	echo TAB_8.'<div id="ShopPromoItemList" >'."\n";
					

		//	 get promo listing settings
		$mysql_err_msg = 'Promo Event Listing Settings unavailable';	
		$sql_statement = 'SELECT * FROM sweeps_promo_list_config'

													.' WHERE mod_id = "'.$mod_id.'"'
													;
						
		$sweeps_promo_settings = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

		//	Do Listing Heading
		if ($sweeps_promo_settings['listing_heading'] != '' AND $sweeps_promo_settings['listing_heading'] != NULL)
		{
			echo TAB_10.'<h1 id="ShopPromoListHeading" >'.$sweeps_promo_settings['listing_heading'].'</h1>'."\n";
		}
		
		echo TAB_9.'<table class="SweepsPromListTable" >' ."\n";	
		
		$alt_BG_count = 1;
		//	display Item info in Listing
		while ($sweeps_promo_listing_info = mysql_fetch_array ($sweeps_promo_listing_result))
		{

			if ($alt_BG_count % 2)
			{$alt_BG_class = ' ShopItemListAltRow';}
			else {$alt_BG_class = '';}
			
			echo TAB_10.'<tr class="ShopItemList'.$alt_BG_class.'" >' ."\n";
				
				$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;prod_id='.$sweeps_promo_listing_info['prod_id'].'&amp;view=browse';
			
				//	product image (link)
				echo TAB_11.'<td class="ShopCartTableCell ShopItemListImage" rowspan="2">' ."\n";

				$sql_statement = 'SELECT image_file_name FROM sweeps_item_images, sweeps_items'

															.' WHERE sweeps_items.primary_image_id = sweeps_item_images.image_id'
															.' AND sweeps_items.item_id = '.$sweeps_promo_listing_info['item_id'];
					
				$sweeps_promo_image_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
				$image_filename = $sweeps_promo_image_info['image_file_name'];											
				if 
				(
						$sweeps_promo_settings['show_image'] == 'on' AND $sweeps_promo_listing_info['display_image'] == 'on'
					 AND file_exists('_images_shop/'.$image_filename)
					 AND $image_filename != ''
				)
				{
					echo TAB_12.'<a class="ShopItemLinkThumb" href="'.$href .'"' ."\n";
						echo TAB_13.' title="Click this link to view this '.SHOP_ITEM_ALIAS.'&#39;s details: '.$sweeps_promo_listing_info['item_name'].'" >' ."\n";
						echo TAB_13.'<img class="PromoList" src="/_images_shop/'.$image_filename.'" '
								.' alt="image of '.SHOP_ITEM_ALIAS.'" />' ."\n";					
					echo TAB_12.'</a>' ."\n";			
				}

					
				echo TAB_11.'</td>' ."\n";
				
				//	product Name (link)
				echo TAB_11.'<td class="ShopCartTableCell ShopItemListName" colspan="2">' ."\n";
					
					echo TAB_12.'<h2><a class="ShopItemLink" href="'.$href.'"' ."\n";
						echo TAB_13.' title="Click this link to view this '.SHOP_ITEM_ALIAS.'&#39;s details: '.$sweeps_promo_listing_info['item_name'].'" >' ."\n";
						echo TAB_13.$sweeps_promo_listing_info['item_name'] ."\n";				
					echo TAB_12.'</a></h2>' ."\n";
					
				//	Close date and time			
				if ($sweeps_promo_settings['show_close_date'] == 'on')
				{
					echo TAB_12.'<p class="ShopItemShowDesc" >Closes: '
					.date("D jS M, Y @ g:ia", strtotime($sweeps_promo_listing_info['event_close_date'])).'</p>' ."\n";	
				}	
				
				//	Description			
				if ($sweeps_promo_settings['show_brief'] == 'on')
				{
					echo TAB_12.'<p class="ShopItemShowDesc" >'.HiliteText(nl2br(Space2nbsp($sweeps_promo_listing_info['brief']))).'</p>' ."\n";	
				}
				
				elseif ($sweeps_promo_settings['show_desc'] == 'on')
				{
					echo TAB_12.'<p class="ShopItemShowDesc" >'.HiliteText(nl2br(Space2nbsp($sweeps_promo_listing_info['description'])))
								.'</p>' ."\n";				
				}				
					echo TAB_12.'<a class="ShopItemLinkThumb" href="'.$href .'"' ."\n";
						echo TAB_13.' title="Click this link to view this '.SHOP_ITEM_ALIAS.'&#39;s details: '.$sweeps_promo_listing_info['item_name'].'" >' ."\n";
										
					echo TAB_12.'more info...</a>' ."\n";					
				echo TAB_11.'</td>' ."\n";
			
			echo TAB_10.'</tr>' ."\n";
			echo TAB_10.'<tr class="ShopItemList'.$alt_BG_class.'" >' ."\n";

			
			//	product PRICE
				echo TAB_11.'<td>' ."\n";
					
				if ($sweeps_promo_settings['show_price'] == 'on')
				{				
					echo TAB_12.'<p class="ShopItemPrice" >'
							.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($sweeps_promo_listing_info['price'], 2).SHOP_CURRENCY_SYMBOL_SUFFIX 
						 .'</p>' ."\n";
					
					if 
					(
							$sweeps_promo_listing_info['display_list_price'] != ''
						AND $sweeps_promo_listing_info['list_price'] != '' 
						AND $sweeps_promo_listing_info['list_price'] != NULL 
						AND $sweeps_promo_listing_info['list_price'] != 0 
					)
					{ 
						$list_price = $sweeps_promo_listing_info['list_price']; 
						echo TAB_12.'<p class="ShopItemListPrice" >RRP:<span class="Strike" >'
								.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($list_price, 2).SHOP_CURRENCY_SYMBOL_SUFFIX
						.'</span></p>' ."\n";				
					}
					
				}
				
				echo TAB_11.'</td>' ."\n";
				
				//	add to Cart
				echo TAB_11.'<td class="ShopCartTableCell SweepsPromListAdd2Cart" >' ."\n";
				
				if ($sweeps_promo_listing_info['display_buynow'] == 'on' AND $sweeps_promo_settings['show_buynow'] == 'on')
				{
					//	Item Not in stock
					if ($sweeps_promo_listing_info['limit_stock_active'] == 'on' AND $sweeps_promo_listing_info['in_stock'] < 1)
					{
						echo TAB_12.'<h4 class="Notice" >Sold out</h1>' ."\n";
					}					
									
					elseif
					( 
							isset($_SESSION['cart_items'][$sweeps_promo_listing_info['prod_id']])
						AND	$sweeps_promo_listing_info['max_quantity_allow'] != 0 AND $sweeps_promo_listing_info['max_quantity_allow'] != ''
						AND	$_SESSION['cart_items'][$sweeps_promo_listing_info['prod_id']] >= $sweeps_promo_listing_info['max_quantity_allow']
					)					
					{
						echo TAB_12.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="maximum allowed already in cart" alt="disabled Add to Cart button" />'."\n";						
						echo TAB_12.'<p class="WarningMSGSmall" >( maximum allowed already in cart )</p>'."\n";;
					}
					
					///		DISABLE if not with in Sale date-time Range
					elseif 
					( 	
							time() < strtotime($sweeps_promo_listing_info['event_start_date']) 
							AND $sweeps_promo_listing_info['event_start_date'] != 0
					)
					{					
						echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="This event is now closed" alt="disabled Add to Cart button" />'."\n";
						$start_date = date("D jS M, Y @ g:ia", strtotime($sweeps_promo_listing_info['event_start_date']));
						echo TAB_13.'<p class="WarningMSG" >This '.SHOP_ITEM_ALIAS.' goes on sale:<br/>'.$start_date.'</p>'."\n";
					}					
					elseif 
					( 
							time() > strtotime($sweeps_promo_listing_info['event_close_date']) 
						AND $sweeps_promo_listing_info['event_close_date'] != 0
					)
					{					
						echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="This event is now closed" alt="disabled Add to Cart button" />'."\n";						
						echo TAB_13.'<p class="WarningMSG" >This '.SHOP_ITEM_ALIAS.' is now closed</p>'."\n";
					}						
					
					else
					{
						echo TAB_12.'<a href="index.php?p='.SHOP_PAGE_ID.'&amp;add2cart='.$sweeps_promo_listing_info['prod_id'].'" >'."\n";

							echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON.'" '."\n";							
							echo TAB_13.'title="Click here to add: '.$sweeps_promo_listing_info['item_name'].' to your Shopping Cart."'
										.' alt="Add to Cart" />'."\n";

						echo TAB_12.'</a>'."\n"; 
					}
					
				}
	
				echo TAB_11.'</td>' ."\n";		

			echo TAB_10.'</tr>' ."\n";

			$alt_BG_count++;
			
		}	
				
		echo TAB_9.'</table>' ."\n";
		
	
	echo TAB_8.'</div>'."\n";	
	
}


?>