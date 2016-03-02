<?php

	// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	//	set HTTPS   if not already   (SHOP_CHECKOUT_WITH_HTTPS  == 1  is  https mode on)
	if(SHOP_CHECKOUT_WITH_HTTPS AND !isset($_SERVER['HTTPS']))
	{
		header('location: https://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
		exit();		
	}

	//	reset validation error flag	
	$error = FALSE;

		
	//	strip and trim 
	foreach($_REQUEST as $key => $value )
	{$_REQUEST[$key] = trim(strip_tags($value));}
	
	//////// MAY NEED TO REMOVE WHEN IN CMS mode ????
	foreach($_POST as $key => $value )
	{
		$value = str_replace('"', '', $value);
		$_POST[$key] = trim(strip_tags($value));	
	}

	foreach($_GET as $key => $value )
	{$_GET[$key] = trim(strip_tags($value));}	

	echo TAB_8.'<script type="text/javascript" src="/_javascript_custom/shop.jquery.js"></script>'."\n";
	
	echo TAB_8.'<div id="ShopCheckOut" >'."\n";
	

	//	user has selected a PAYMENT option - advance to STAGE 3
	if (isset ($_GET['checkstate']) AND $_GET['checkstate'] == 3 AND isset($_SESSION['check_out_stage']) AND isset($_REQUEST['paymeth']))
	{			
		$_SESSION['check_out_stage'] = 3;
		$_SESSION['pay_method_type'] = $_REQUEST['paymeth'];
	}
	
	//	user has selected change PAYMENT option - set to STAGE 2
	if (isset ($_GET['checkstate']) AND $_GET['checkstate'] == 2 AND $_SESSION['check_out_stage'] > 2)
	{
		$_SESSION['check_out_stage'] = 2;
	}
	
	//	user has selected Edit Details - set to STAGE 1
	if (isset ($_GET['checkstate']) AND $_GET['checkstate'] == 1 )
	{
		unset($_SESSION['check_out_stage']);;
	}
	
	if (isset($_SESSION['pay_method_type'])){$payment_method_type = $_SESSION['pay_method_type'];}
	else {$payment_method_type = '';}		
		
///////////////////////		New code	\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\		

	//	Remember last entered settings	///
	
	//	name
	if (isset($_POST['checkout_name']))
	{
		$checkout_name = $_SESSION['checkout_name'] = $_POST['checkout_name'];
	}	
	elseif (isset($_SESSION['checkout_name']))
	{
		$checkout_name = $_SESSION['checkout_name'];
	}
	else
	{
		$checkout_name = '';
	}
	
	//	email	
	if (isset($_POST['checkout_email']))
	{
		$checkout_email = $_SESSION['checkout_email'] = $_POST['checkout_email'];
	}	
	elseif (isset($_SESSION['checkout_email']))
	{
		$checkout_email = $_SESSION['checkout_email'];
	}
	else
	{
		$checkout_email = '';
	}
	
	//	phone	
	if (isset($_POST['checkout_phone']))
	{
		$checkout_phone = $_SESSION['checkout_phone'] = $_POST['checkout_phone'];
	}	
	elseif (isset($_SESSION['checkout_phone']))
	{
		$checkout_phone = $_SESSION['checkout_phone'];
	}
	else
	{
		$checkout_phone = '';
	}	
	
	//	address 1	
	if (isset($_POST['checkout_address_1']))
	{
		$checkout_address_1 = $_SESSION['checkout_address_1'] = $_POST['checkout_address_1'];
	}	
	elseif (isset($_SESSION['checkout_address_1']))
	{
		$checkout_address_1 = $_SESSION['checkout_address_1'];
	}
	else
	{
		$checkout_address_1 = '';
	}	
	
	//	address 2	
	if (isset($_POST['checkout_address_2']))
	{
		$checkout_address_2 = $_SESSION['checkout_address_2'] = $_POST['checkout_address_2'];
	}	
	elseif (isset($_SESSION['checkout_address_2']))
	{
		$checkout_address_2 = $_SESSION['checkout_address_2'];
	}
	else
	{
		$checkout_address_2 = '';
	}	

	//	state	
	if (isset($_POST['checkout_state']))
	{
		$checkout_state = $_SESSION['checkout_state'] = $_POST['checkout_state'];
	}	
	elseif (isset($_SESSION['checkout_state']))
	{
		$checkout_state = $_SESSION['checkout_state'];
	}
	else
	{
		$checkout_state = '';
	}	
	
	//	postcode	
	if (isset($_POST['checkout_postcode']))
	{
		$checkout_postcode = $_SESSION['checkout_postcode'] = $_POST['checkout_postcode'];
	}	
	elseif (isset($_SESSION['checkout_postcode']))
	{
		$checkout_postcode = $_SESSION['checkout_postcode'];
	}
	else
	{
		$checkout_postcode = '';
	}	

		
	//	Check if Have read rules checkbox checked
	if (SHOP_CHECKOUT_CHECK_READ_RULES == 1)
	{
		if (isset($_POST['checkout_read_rules']) AND $_POST['checkout_read_rules'] == 'on')
		{
		
			$checkout_read_rules = $_SESSION['checkout_read_rules'] = 'on';
		}	
		elseif (isset($_SESSION['checkout_read_rules']) AND $_SESSION['checkout_read_rules'] == 'on')
		{
			$checkout_read_rules = 'on';
				
		}
		else
		{
			$checkout_read_rules = '';
		
		}		
	}

	
	
	//	country	
	
	if (isset($_POST['checkout_country']))
	{
		$checkout_country = $_SESSION['checkout_country'] = $_POST['checkout_country'];
	}	
	elseif (isset($_SESSION['checkout_country']))
	{
		$checkout_country = $_SESSION['checkout_country'];
	}	
	else
	{
		//$checkout_country = current($select_countries);
		$checkout_country = SHOP_CHECKOUT_DEFAULT_COUNTRY;	
	}	
	
	//	only use prev. selected country if available
	if (!in_array($checkout_country, $select_countries))
	{
		$checkout_country = current($select_countries);

	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 
	// get Postal config info for Validation and customizing form
	$mysql_err_msg = 'Country Postal config Infomation unavailable';	
	$sql_statement = 'SELECT * FROM shop_address_countries'

							.' WHERE country_name = "'.$checkout_country.'"'
							.' AND active = "on"'
							.' ORDER BY country_name'
							;					

	$postal_config_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
	if (count($postal_config_info) != 0 )
	{
		$has_states = $postal_config_info['has_states'];
		$has_postcodes = $postal_config_info['has_postcodes'];
		$state_or_prov = $postal_config_info['state_or_prov'];				
		$zip_or_post = $postal_config_info['zip_or_post'];
		$pcode_min_length = $postal_config_info['pcode_min_length'];
		$pcode_max_length = $postal_config_info['pcode_max_length'];
		$postcode_only_num = $postal_config_info['postcode_only_num'];
		$postcode_required = $postal_config_info['postcode_required'];
		$state_required = $postal_config_info['state_required'];
	}
	else //	default for failsafe
	{
		$has_states = '';
		$has_postcodes = 'on';
		$state_or_prov = 'State';
		$zip_or_post = 'Postcode';
		$pcode_min_length = 3;
		$pcode_max_length = 10;
		$postcode_only_num = '';
		$postcode_required = '';
		$state_required = '';
	}			

	//	countries with no states neet to be blank
	if ( $has_states == '')
	{
		$checkout_state = $_SESSION['checkout_state'] = '';
	}
//==================================================================================================

	
	//	process form ( STAGE 1 )
	if (isset ($_POST['checkout_form_submit']))
	{	
		//$_SESSION['check_out_stage'] = 2;
		
		//	validate checkout STAGE 1 form
		require_once('shop/shop_checkout_form_validate.php');


		//	Advance user to STAGE 2
		if ( $error == FALSE) 
		{
			$_SESSION['check_out_stage'] = 2;
		}	
		
	}

//==================================================================================================		
	
	//	CHECK-OUT - STAGE 2 - (Details entered correctly, confirm and choose a payment option)
	If ( isset( $_SESSION['check_out_stage']) AND $_SESSION['check_out_stage'] > 1)
	{

		
		if ($_SESSION['check_out_stage'] > 2)
		{
			echo TAB_9.'<div class="ShopDiv">'."\n";
				echo TAB_10.'<h4 id="ShopDetailsHeading" >Your Details: '.PATH_SEPERATOR_SYMBOL.' ' ."\n";
				
					$query_str = '?p='.SHOP_PAGE_ID.'&amp;view=checkout&amp;checkstate=1';
					echo TAB_11.'<a class="ShopCheckoutEdit" href="'.$_SERVER['PHP_SELF'].$query_str.'"'. "\n";		
					echo TAB_11.' title="Edit your details" >[ Edit these Details ]</a>'. "\n";
				
				echo TAB_10.'</h4>' ."\n";
				
				echo TAB_10.'<div id="ShopCheckOutReadbackHide" >'."\n";
		}
		else
		{
			echo TAB_9.'<h2 class="ShopCheckOut" >Please complete your purchase by confirming these details'
											. ' and clicking a Button below:</h2>'."\n";	
		
		
		}
		
			//	confirm email and post details
			echo TAB_9.'<div id="ShopCheckOutReadback" >'."\n";
				
				echo TAB_10.'<p><label>Name: </label><span class="Bold">'.$checkout_name.'</span></p>'. "\n";
				echo TAB_10.'<p><label>Email: </label><span class="Bold">'.$checkout_email.'</span></p>'. "\n";
				
				if ($checkout_phone != '')
				{
					echo TAB_10.'<p><label>Phone: </label><span class="Bold">'.$checkout_phone.'</span></p>'. "\n";
				}
				
				echo TAB_10.'<p><label>Address: </label><span class="Bold">'.$checkout_address_1.'</span></p>'. "\n";
				if ($checkout_address_2 != ''  AND $checkout_address_2 != NULL )
				{
					echo TAB_10.'<p><label><span class="WarningMSG" ></span></label><span class="Bold">'.$checkout_address_2.'</span></p>'. "\n";	
				}
				$checkout_address_3 = $checkout_postcode;
				if ($checkout_state != '' AND $checkout_state != NULL)
				{
					$checkout_address_3 = $checkout_state . ' - ' . $checkout_address_3;
				}
				echo TAB_10.'<p><label><span class="WarningMSG" ></span></label><span class="Bold">'.$checkout_address_3.'</span></p>'. "\n";
				echo TAB_10.'<p><label><span class="WarningMSG" ></span></label><span class="Bold">'.$checkout_country.'</span></p>'. "\n";
			echo TAB_9.'</div>'."\n";
				

			//	Edit details link
			echo TAB_9.'<p>' ."\n";	

				$query_str = '?p='.SHOP_PAGE_ID.'&amp;view=checkout&amp;checkstate=1';
				echo TAB_10.'<a class="ShopCheckoutEdit" href="'.$_SERVER['PHP_SELF'].$query_str.'"'. "\n";		
				echo TAB_10.' title="Edit the above details" >[ Edit these Details ]</a>'. "\n";	
				
			echo TAB_9.'</p>' ."\n";
			
		if ($_SESSION['check_out_stage'] > 2)
		{
				echo TAB_10.'</div>'."\n";
			echo TAB_9.'</div>'."\n";
		}	
		
			//	Display grand total
			//$grand_total_price += $post_calc_cost;
			
			echo TAB_9.'<h3 class="ShopCheckOut" >Total Cost: '."\n";
				echo TAB_10.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($grand_total_price, 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n";
			echo TAB_9.'</h3>' ."\n";


//==================================================================================================

		if ($_SESSION['check_out_stage'] < 3)
		{
	
			//	Pay Now Buttons
			if (count($payment_method_array) > 1)
			{
				echo TAB_10.'<h4>Please choose a payment option:</h4>'. "\n";	
			}
			
			echo TAB_9.'<ul id="ShopCheckOutPaynowButtons">' ."\n";

			foreach ($payment_method_array as $payment_method_type)
			{
				echo TAB_10.'<li>' ."\n";
				
				switch($payment_method_type)
				{
					case 1:	//	eWay - merchant hosted
					
						$query_str = '?p='.SHOP_PAGE_ID.'&amp;view=checkout&amp;checkstate=3&amp;paymeth='.$payment_method_type;
						echo TAB_11.'<a class="ShopCheckoutPayNow" href="'.$_SERVER['PHP_SELF'].$query_str.'"'. "\n";			
						echo TAB_11.' title="Click here to confirm purchase and make payment with a credit card." >'. "\n";
							echo TAB_12.'<img class="PayNow" src="/_images_user/'.EWAY_BUTTON_PAYNOW.'" alt="PAY with a Credit Card" />' ."\n";
						echo TAB_11.'</a>'. "\n";
						
					break;
					
					case 2:	//	PayPay - Standard Checkout
					
						$query_str = '?p='.SHOP_PAGE_ID.'&amp;view=checkout&amp;checkstate=3&amp;paymeth='.$payment_method_type;
						echo TAB_11.'<a class="ShopCheckoutPayNow HideOnClick" href="'.$_SERVER['PHP_SELF'].$query_str.'"'. "\n";
						echo TAB_11.' title="Click here to confirm purchase and checkout with PayPal." >'. "\n";
							echo TAB_12.'<img class="PayNow" src="/_images_user/'.PAYPAL_BUTTON_CHECKOUT_WITH.'" alt="PAY with PayPal" />' ."\n";
						echo TAB_11.'</a>'. "\n";
						echo TAB_11.'<span class="ShowOnClick" ></span>' ."\n";
						
					break;
					
					case 3:	//	PayPal - Express Checkout 
						
						$query_str = '?p='.SHOP_PAGE_ID.'&amp;view=checkout&amp;checkstate=3&amp;paymeth='.$payment_method_type;
						echo TAB_11.'<a class="ShopCheckoutPayNow HideOnClick" href="'.$_SERVER['PHP_SELF'].$query_str.'"'. "\n";		
						echo TAB_11.' title="Click here to confirm purchase and checkout with PayPal." >'. "\n";
							echo TAB_12.'<img class="PayNow" src="/_images_user/'.PAYPAL_BUTTON_CHECKOUT_WITH.'" alt="PAY with PayPal" />' ."\n";
						echo TAB_11.'</a>'. "\n";
						echo TAB_11.'<span class="ShowOnClick" ></span>' ."\n";
						
					break;

					case 4:	//	St George Bank IPG API
					
						$query_str = '?p='.SHOP_PAGE_ID.'&amp;view=checkout&amp;checkstate=3&amp;paymeth='.$payment_method_type;
						echo TAB_11.'<a class="ShopCheckoutPayNow" href="'.$_SERVER['PHP_SELF'].$query_str.'"'. "\n";			
						echo TAB_11.' title="Click here to confirm purchase and make payment with a credit card." >'. "\n";
							echo TAB_12.'<img class="PayNow" src="/_images_user/'.STG_IPG_BUTTON_PAYNOW.'" alt="PAY with a Credit Card" />' ."\n";
						echo TAB_11.'</a>'. "\n";
						
					break;					
				}
				
				echo TAB_10.'</li>' ."\n";
				
			}
			
			echo TAB_9.'</ul>' ."\n";	
		}
		
//===================================================================================================================	
		
		elseif ($_SESSION['check_out_stage'] == 3)
		{
	
			checkQuantity();
			
			//	CHECK-OUT - STAGE 3 - (payment type chosen, enter CC details or access paypal)
			switch($payment_method_type)
			{
				case 1:		//	eWay - merchant hosted
				case 4:		//	St George Bank IPG API
				
					$_SESSION["Payment_Amount"] = $grand_total_price;
					
					//	Do CC form and process
					require_once ('shop/shop_CC_form.php');						

					
				break;
				
				case 2:	//	PayPay - Standard Checkout
				
					
					
				break;
				
				case 3:	//	PayPal - Express Checkout
				
					$_SESSION["Payment_Amount"] = $grand_total_price;
					if(SHOP_CHECKOUT_WITH_HTTPS)
					{
						$htpref = 'https://'.$_SERVER['SERVER_NAME'];
					}
					else
					{
						$htpref = '';
					}
					
					header('location: '.$htpref.'/modules/pay_gate/paypal_express_process.php?paymeth='.$payment_method_type);

				break;											
			}			

		}
		elseif ($_SESSION['check_out_stage'] == 4)
		{
			//	CHECK-OUT - STAGE 4 - (confirm details from Paypal and click to make payment)
			//echo TAB_8.'<div id="ShopCheckOut" >'."\n";	
	
				require_once ('pay_gate/paypal_express_confirm_order.php');

			//echo TAB_8.'</div>'."\n";				
		}
		
	}


//===================================================================================================================	

	//	CHECK-OUT - STAGE 1 - (enter personal details)
	If ( !isset( $_SESSION['check_out_stage']) OR $_SESSION['check_out_stage'] < 2)
	{		
		require_once('shop/shop_checkout_form.php');
	}
		
	echo TAB_8.'</div>'."\n";	


?>