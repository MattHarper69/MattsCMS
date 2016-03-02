<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	//	get required data
	require_once (CODE_NAME.'_shop_configs.php');
	
	$edit_enabled = 0;
	
	//	read from db	----------
	$mysql_err_msg = 'Add to Cart Button information unavailable';	
	$sql_statement = 'SELECT ' . SHOP_DB_NAME_PREFIX . '_cat_asign.prod_id'
							.', mod_add2cart_button.image_file'
							.', item_name'						
							.', title_text'
							.', display_text'
							.', max_quantity_allow'
							.', limit_stock_active'
							.', in_stock'
							.', event_close_date'
							.', event_start_date'
							
							.' FROM ' . SHOP_DB_NAME_PREFIX . '_cat_asign'
							.', mod_add2cart_button'
							.', ' . SHOP_DB_NAME_PREFIX . '_items'
							
							.' WHERE mod_add2cart_button.mod_id = "'.$mod_id.'"'
							.' AND mod_add2cart_button.prod_id = ' . SHOP_DB_NAME_PREFIX . '_cat_asign.prod_id'
							.' AND ' . SHOP_DB_NAME_PREFIX . '_cat_asign.item_id = ' . SHOP_DB_NAME_PREFIX . '_items.item_id';

	$button_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	

	if ($button_info['image_file'] != "" AND $button_info['image_file'] != NULL AND file_exists('_images_user/'.$button_info['image_file']))
	{	

		echo TAB_7.'<div class="Add2Cart" id="Add2Cart_'.$mod_id.'" >'."\n";
			
			
			
			
			
			
					//	Item Not in stock
					if 
					($button_info['limit_stock_active'] == 'on' AND $button_info['in_stock'] < 1)
					{
						echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="This event is now closed" alt="disabled Add to Cart button" />'."\n";						
						echo TAB_13.'<p class="WarningMSG" >This '.SHOP_ITEM_ALIAS.' is Sold out</p>'."\n";
					}					
									
					elseif
					( 
							isset($_SESSION['cart_items'][$button_info['prod_id']])
						AND	$button_info['max_quantity_allow'] != 0 AND $button_info['max_quantity_allow'] != ''
						AND	$_SESSION['cart_items'][$button_info['prod_id']] >= $button_info['max_quantity_allow']
					)					
					{
						echo TAB_12.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="maximum allowed already in cart" alt="disabled Add to Cart button" />'."\n";						
						echo TAB_12.'<p class="WarningMSGSmall" >( maximum allowed already in cart )</p>'."\n";;
					}
					
					///		DISABLE if not with in Sale date-time Range
					elseif 
					( 	
							time() < strtotime($button_info['event_start_date']) 
							AND $button_info['event_start_date'] != 0
					)
					{					
						echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="This event is now closed" alt="disabled Add to Cart button" />'."\n";
						$start_date = date("D jS M, Y @ g:ia", strtotime($button_info['event_start_date']));
						echo TAB_13.'<p class="WarningMSG" >This '.SHOP_ITEM_ALIAS.' goes on sale:<br/>'.$start_date.'</p>'."\n";
					}					
					elseif 
					( 
							time() > strtotime($button_info['event_close_date']) 
						AND $button_info['event_close_date'] != 0
					)
					{					
						echo TAB_13.'<img class="Add2Cart" src="/_images_user/'.SHOP_ADD2CART_BUTTON_DISABLED.'" '							
									.'title="This event is now closed" alt="disabled Add to Cart button" />'."\n";						
						echo TAB_13.'<p class="WarningMSG" >This '.SHOP_ITEM_ALIAS.' is now closed</p>'."\n";
					}				
			
			
			
			
			
			
			else
			{
			
				echo TAB_8.'<a href="index.php?p='.SHOP_PAGE_ID.'&amp;add2cart='.$button_info['prod_id'].'" >'."\n";

					echo TAB_9.'<img class="Add2Cart" src="/_images_user/'.$button_info['image_file'].'" '."\n";
					
					$button_alt_text = 'Add to Cart';
					if ($button_info['title_text'] != "" AND $button_info['title_text'] != NULL)
					{ $button_title = $button_info['title_text']; }
					
					else { $button_title = 'Click here to add: '.$button_info['item_name'].' to your Shopping Cart.'; }
					echo TAB_9.'title="'.$button_title.'" alt="'.$button_alt_text.'" />'."\n";
					
							
					if ($button_info['display_text'] != "" AND $button_info['display_text'] != NULL )
					{			
						//	Print Capton Text	
						echo TAB_9.'<span class="Add2Cart" >'.HiliteText(nl2br(Space2nbsp($button_info['display_text']))).'</span>'."\n";
					}
					
				echo TAB_8.'</a>'."\n";
				
			}
				


				
				
		echo TAB_7.'</div>'."\n";
		
	}	
?>