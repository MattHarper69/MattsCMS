<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	//	strip and trim 
	foreach($_REQUEST as $key => $value )
	{$_REQUEST[$key] = trim(strip_tags($value));}
	
	//////// MAY NEED TO REMOVE WHEN IN CMS mode ????
	foreach($_POST as $key => $value )
	{$_POST[$key] = trim(strip_tags($value));}

	foreach($_GET as $key => $value )
	{$_GET[$key] = trim(strip_tags($value));}

	//	get required data
	require_once (CODE_NAME.'_shop_configs.php');	
	require_once (CODE_NAME.'_alert_configs.php');
	require_once ('shop/shop_php_functions.php');	
	
	if (SHOP_PAYMENT_ONLINE)
	{
		//	read from db to get All Payment methods and info
		$mysql_err_msg = 'Payment Methods information unavailable';	
		$sql_statement = 'SELECT _shop_pay_types.pay_method_id'
							.', _shop_pay_types.config_file_name'
		
						.' FROM shop_pay_methods_set, _shop_pay_types'

						.' WHERE shop_pay_methods_set.active = "on"'
						.' AND shop_pay_methods_set.pay_method_id = _shop_pay_types.pay_method_id'
						.' ORDER BY seq'
						;
			
		$payment_method_result = ReadDB ($sql_statement, $mysql_err_msg);
		
		$payment_method_array = array();
		while ($payment_method_info = mysql_fetch_array($payment_method_result))
		{
			$payment_method_array[] = $payment_method_info['pay_method_id'];
			
			//	get required data
			require_once (CODE_NAME.$payment_method_info['config_file_name']);
		}
		
	}

		
	//------------set default time zone
	date_default_timezone_set(SHOP_TIME_ZONE);
	
	
	//	===========VVVVVVVVVVVVVVVV=============SWEEPS CODE======================VVVVVVVVVVVVVVVVV================================
	//	Auto Empty cart and reset after set time (if EXPIRY TIME set)
	if (SHOP_TIME_OUT_EXPIRY != 0 AND SHOP_TIME_OUT_EXPIRY != '')
	{	
		if 
		(
				isset($_SESSION['last_shop_activity']) 
			AND (time() - $_SESSION['last_shop_activity'] > SHOP_TIME_OUT_EXPIRY * 60)
			AND isset($_SESSION['cart_items'])
			AND count($_SESSION['cart_items']) != 0		//	Do not reset if cart aready empty
		) 
		{ 			
			EmptyCartAndReset();
			$_SESSION['last_shop_activity'] = time();	//	reset time stamp to avoid endless looooops  !!
			header ("location: http://".$_SERVER['SERVER_NAME']."/index.php?p=".SHOP_PAGE_ID."&view=browse");
			exit();
		}
		
		// update last activity time stamp
		$_SESSION['last_shop_activity'] = time();

	}	
	//	===========^^^^^^^^^^^^^=============SWEEPS CODE======================^^^^^^^^^^^^^^^^==================================
	
	if (isset($_REQUEST['view'])) {$view_set = $_REQUEST['view'];}
	else { $view_set = '';}

	//	determine which category to display
	if (isset($_REQUEST['s_cat']))	{$cat_id = $_REQUEST['s_cat'];}
	elseif (isset($_SESSION['last_cat_id_viewed']))	{$cat_id = $_SESSION['last_cat_id_viewed'];}	
	else { $cat_id = '';}


	//	OR which item to display
	if (isset($_REQUEST['prod_id']))	
	{
		$prod_id = $_REQUEST['prod_id'];
		
		//	read from db to get CAT id
		$mysql_err_msg = 'Category information unavailable';	
		$sql_statement = 'SELECT cat_id FROM shop_cat_asign WHERE prod_id = "'.$prod_id.'"';
				
		$get_cat_id_result = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	
		$cat_id = $get_cat_id_result['cat_id'];
		
	}
				
	else { unset ($prod_id); }	
	
	
	//	Build the query str for Re-direction
	$query_str = '?p='.SHOP_PAGE_ID;
					
	if ( isset($cat_id) AND $cat_id != '' AND $cat_id != NULL )
	{
		$query_str .= '&s_cat='.$cat_id;
	}
		
	if ( isset($prod_id) AND $prod_id != '' AND $prod_id != NULL )
	{
		$query_str .= '&prod_id='.$prod_id;
	}	
	
	//	do ADD 2 Cart and other cart session stuff and redirect
	ShopCartManage( $query_str );

	//	get category parent id  and Cat Name
	if (isset($cat_id))
	{
		//	read from db to get parent id	and   Cat Name----------
		$mysql_err_msg = 'Category information unavailable';	
		$sql_statement = 'SELECT * FROM shop_categories'
				
										.' WHERE cat_id = "'.$cat_id.'"'
										.' AND active = "on"'
										.' ORDER BY seq';
				
		$shop_cat_result = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
		
		//	determine if there is only one parent CAT
		if ($cat_id == 0)
		{$shop_num_cats = mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg));}
		else {$shop_num_cats = 2;}	//	set $shop_num_cats greater than 1 to trigger other events
		
		$shop_cats_name = $shop_cat_result['cat_name'];
		
	}

		
	echo "\n";			
	echo TAB_7.'<!--	START Online Shop code 	-->'."\n";
	echo "\n";
	
	
		//	FOR CMS MODE ONLY	================================================================================
		$div_name = 'Mod_'.$div_id.'_'.$mod_info['mod_id'];
		
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{			
			
			//	Display / Hide In-Active Mods
			include ('CMS/cms_inactive_mod_display.php');

			//	Show Div Mod Button
			echo TAB_7.'<div id="EditDivModDisplay_'.$mod_id.'" class="EditDivModDisplay"' 
						.' title="Click to Edit this &quot;Shop&quot; Module">'."\n";
				
				echo TAB_8.'<p style="background-color:#ffffff;color:#aa44aa; cursor: pointer;"'
				.' onClick="javascript:selectMod2Edit('.$mod_id.',\''.$div_name.'\' ,0, 2);">'
				.'[ Shop Module (click to edit) ]<p>'."\n";
				
			echo TAB_7.'</div>'."\n";
		
		}

		//======================================================================================================	

	//	test for cookies enabled (except when displaying reciept linked from order email)
	if(!isset($_COOKIE['PHPSESSID']) AND !isset($_GET['invoice']))
	{ 
		if (!isset($_GET['nocookies']) )
		{
			header ("location: /index.php".$query_str.'&nocookies=1'); 
			exit();			
		}
		else
		{
			echo TAB_8.'<h2 class="Notice" >Notice: Cookies must be enabled in your browser for this shopping facility to function.</h2>'."\n";
			//echo TAB_8.'<h2 class="Notice" >If this notice persists, please enable Cookies to continue.</h2>'."\n"; 
			echo TAB_8.'<h2 class="Notice" >Please enable Cookies to continue.</h2>'."\n"; 
		}

	}
	
	//	only show Breadcrumb if "turned on" and in browse view
	if ( 
				SHOP_DISPLAY_BREADCRUMB == 1 

			AND ( $view_set == 'browse' OR $view_set == '' )
			AND isset($cat_id)
			AND $cat_id != '' 
			AND $cat_id != NULL
		)
		
	{ include_once ('shop/shop_breadcrumb.php'); }	
	
	//	Left side colunm
	echo TAB_7.'<div class="ShopDiv" id="ShopLeftColumn" >'."\n";
	echo "\n";		


		//	 Do  plug in Module s for the SHOP MOD's Left column
		GetModInfo ($page_id, 12, $site_theme_id);
	
	echo TAB_7.'</div>'."\n";	
	
	
	//	Centre colunm
	echo TAB_7.'<div class="ShopDiv" id="ShopCentreColumn" >'."\n";

		//	determin what to display:	PRODUCT BROWSING / CART / CHECKOUT / PALPAL RETURN PAGE

		if ($view_set == 'paymade' AND isset($_REQUEST['invoice']) AND $_REQUEST['invoice'] != '')
		{ 
			//	Show order confirmation details	
			require ('shop/shop_receipt_display.php');	
	
			include ('shop/shop_item_suggest.php');	
					
		}			
		
		elseif ($view_set == 'PPreturn_canx' AND isset($_SESSION['canx_paypal'])) 
		{ 
			echo TAB_8.'<div class="ShopDiv" id="ShopCheckOut" >'."\n";	
			
				echo TAB_9.'<h2 class="ShopCheckOut" >Please complete your purchase by clicking the "Pay Now" Button:</h2>'."\n";
					
				echo $_SESSION['PPcanx_paypal'];

			echo TAB_8.'</div>'."\n";	
			
		}

		elseif ($view_set == 'PPreturn' ) 
		{ 
			echo TAB_8.'<h4 class="ShopReturnMSG" >'.PAYPAL_RETURN_MSG.'</h4>' ."\n";
			unset ($_SESSION['canx_paypal']);
			include ('shop_item_suggest.php');		
		}		
		
		
		elseif ($view_set == 'cart' AND isset($_SESSION['cart_items'])) 	
		{
			require ('shop_cart_view.php');
			include ('shop_item_suggest.php');			
		}
		
		elseif ($view_set == 'checkout' AND isset($_SESSION['cart_items']))
		{ 
			require ('shop_cart_view.php');	
			
			if (!isset($can_not_checkout_error) OR $can_not_checkout_error != TRUE)
			{
				require ('shop/shop_checkout.php');				
			}
		
		}
		
		elseif ($view_set == 'browse' )										
		{ 				
			require ('shop_item_browser.php');	
		}

		else					
		{ 
			//	Default setting: Returning from another page....	

			//	items in cart
			if (isset($_SESSION['cart_items'])) 	
			{
				require ('shop_cart_view.php');
				require ('shop_item_suggest.php');	
			}
			
			//	no items in cart. browse
			else {require ('shop_item_browser.php');}		
			
		}

		//	Footer text (optional)
		if (SHOP_LABEL_FOOTER AND SHOP_LABEL_FOOTER != '')
		{
			echo TAB_8.'<p>'.SHOP_LABEL_FOOTER.'</p>'."\n";	
		}	
		
		//	 Do  plug in Module s for the SHOP MOD's Centre column
		GetModInfo ($page_id, 13, $site_theme_id);


	echo TAB_7.'</div>'."\n";	
	echo "\n";	
	
	//	right side colunm
	echo TAB_7.'<div class="ShopDiv" id="ShopRightColumn" >'."\n";
	echo "\n";		

		//	 Do and plug in Module s for the SHOP MOD's Right column
		GetModInfo ($page_id, 14, $site_theme_id);	

	echo TAB_7.'</div>'."\n";		


////////////////////////	KILL SESSION FOR TESTING  ////////////////////////////////////////////////////////////////////////////
/* 
echo TAB_10.'<a class="ShopLink" href="http://'.$_SERVER['SERVER_NAME']
	.$_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;session_destroy=yes'.'"' ."\n";
	echo TAB_11.'  >RESET ALL' ."\n";
echo TAB_10.'</a>' ."\n";
	 */
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
 
 		//	FOR CMS MODE ONLY	================================================================================
		if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{	
			$edit_enabled = 1;
			$mod_locked = 2;
			$can_not_clone = 1;

			//	CSS layout Dispay (for CMS)
			$CSS_layout = '&lt;div id="Mod_'.$div_id.'_'.$mod_info['mod_id'].'" class="DivMod_'.$mod_info['mod_id'].'" &gt;';
			
			//	Do mod editing Toolbar
			include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
			
			//	Do Mod Config Panel
			include ('CMS/cms_panels/cms_panel_mod_config.php');
								

		}
		//	========================================================================================================

		
 	echo "\n";			
	echo TAB_7.'<!--	END Online Shop code 	-->'."\n";
	echo "\n";
	
?>