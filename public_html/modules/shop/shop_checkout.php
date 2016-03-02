<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//$emailPattern = '/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/';
	
	$error = FALSE;
	$error_msg_postcode = '';
	$error_msg_email = '';
	$post_destination = '';
	$post_method = '';
	$checkout_email = '';
	//$checkout_email4paypal = '';
	$checkout_phone = '';
	
	echo TAB_8.'<div class="ShopDiv" id="ShopCheckOut" >'."\n";

	
	//	Remember setting last entered
	if(isset($_SESSION['last_entered_pcode'])) {$post_destination = $_SESSION['last_entered_pcode'];}
	if(isset($_SESSION['last_entered_method'])) {$post_method = $_SESSION['last_entered_method'];}
	if(isset($_SESSION['last_entered_email'])) {$checkout_email = $_SESSION['last_entered_email'];}
	//if(isset($_SESSION['last_entered_email4paypal'])) {$checkout_email4paypal = $_SESSION['last_entered_email4paypal'];}
	if(isset($_SESSION['last_entered_phone'])) {$checkout_phone = $_SESSION['last_entered_phone'];}
	
	//	process form ( STAGE 1 )
	if (isset ($_POST['checkout_form_submit']))
	{	
		
		//	strip and trim
		foreach($_POST as $key => $value )
		{
			$_POST[$key] = trim(strip_tags($value));
		}		
		
		//	Or use previous values
		$post_destination = $_POST['post_destination'];
		$post_method = $_POST['post_method'];
		$checkout_email = $_POST['checkout_email'];
		//$checkout_email4paypal = $_POST['checkout_email4paypal'];
		$checkout_phone = $_POST['checkout_phone'];
	
		//	remember their postcode
		$_SESSION['last_entered_pcode'] = $_POST['post_destination'];
		$_SESSION['last_entered_method'] = $_POST['post_method'];
		$_SESSION['last_entered_email'] = $_POST['checkout_email'];
		//$_SESSION['last_entered_email4paypal'] = $_POST['checkout_email4paypal'];
		$_SESSION['last_entered_phone'] = $_POST['checkout_phone'];

		//	Do email alert if set to on
		if (ALERT_ADD_TO_CART == 1)
		{
			include ('shop_add_item_alert.php');
		}
		
		
		if ( $post_method != 'pick-up' )
		{
			//	calculate Postage
			$post_quantity = $total_calculated_quantity;
			require ('shop_calc_post.php');
		
			//	post calc error
			if ( trim(strtolower($post_calc_error_msg)) != "ok" )
			{
				$error_msg_postcode = '<p>'.$post_calc_error_msg.'</p>';
				$error = TRUE;
			}
			else {$error_msg_postcode = '';}
		}
		
		//	basic validation for  email address	------------------ 		
		if (SHOP_CHECKOUT_REQ_EMAIL == 1 AND !preg_match(EMAIL_REG_EXP_STRING, $checkout_email))
		{
			$error_msg_email = '<p>Please enter a VALID email address</p>';
			$error = TRUE;
		}
		else {$error_msg_email = '';}

		//	Advance user to STAGE 2
		if ( $error == FALSE ) 
		{
			$_SESSION['check_out_state_2'] = 1;
		}	
		
	}	
		
	//	CHECK-OUT - STAGE 2
	If ( isset($_SESSION['check_out_state_2']) )
	{
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*\
	|																	|																		
	|		Relace the following with an <img> alt="Please complete your purchase by conf........."            	|
	|																	|
	\*___________________________________________________________________________*/
	
		echo TAB_9.'<h2 class="ShopCheckOut" >Please complete your purchase by confirming these details and clicking the "Pay Now" Button:</h2>'."\n";
			
		//	calculate Postage

		
		//	confirm email and post details
		echo TAB_9.'<p>Your Email Address: <span class="Bold">'.$checkout_email.'</span></p>'. "\n";
		echo TAB_9.'<p>Your Phone: <span class="Bold">'.$checkout_phone.'</span></p>'. "\n";
		echo TAB_9.'<p class="Bold">Delivery Option:<p>'. "\n";
		
		if ( $post_method != 'pick-up' )
		{
			$post_quantity = $total_calculated_quantity;
			require ('shop_calc_post.php');	
			
			echo TAB_9.'<p><span class="Bold">'.$post_method.'</span> delivery to'
							.' Postcode:<span class="Bold">'.$post_destination.'</span></p>'. "\n";	
					
			//	put the plural in "days"
			if ($post_calc_eta > 1) {$days = 's';}
			else {$days = '';}
			
			//	ETA and Post cost		
			echo TAB_9.'<p>Estimated delivery time: <span class="Bold">'.$post_calc_eta.'</span> day'.$days.'</p>'. "\n";			
			echo TAB_9.'<h4>Total Postage cost: <span class="Bold" >'
						.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($post_calc_cost,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</span></h4>'. "\n";
		
		}
		
		else
		{
			$post_calc_cost = 0;
			
			echo TAB_9.'<p>Pick-up from: <span class="Bold">'.SHOP_POST_PICKUP_ADDRESS.'</span></p>'. "\n";
		}
		
		//	Edit details link
		echo TAB_9.'<p>' ."\n";	

			$query_str = '?p='.SHOP_PAGE_ID.'&amp;view=checkoutedit';
			echo TAB_10.'<a class="ShopCheckoutEdit" href="'.$_SERVER['PHP_SELF'].$query_str.'"'. "\n";		
			echo TAB_10.' title="Edit the above details" >[ Edit these Details ]</a>'. "\n";		
		echo TAB_9.'</p>' ."\n";
		
		//	Display grand total
		$grand_total_price += $post_calc_cost;
		
		echo TAB_9.'<h3 class="ShopCheckOut" >Total Cost: '."\n";
			echo TAB_10.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($grand_total_price, 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n";
		echo TAB_9.'</h3>' ."\n";
		
//=====		MODIFY for multiple payment types	=============================================================================================		
		//	Pay Now Button 
		echo TAB_9.'<p>' ."\n";	
			echo TAB_10.'<a class="ShopCheckoutPayNow" href="/modules/shop/shop_checkout_process.php?paymeth=2"'. "\n";//=====		MODIFY for multiple payment types		
			echo TAB_10.' title="Click here to confirm purchase and make payment with PayPal." >'. "\n";
				echo TAB_10.'<img class="NoBorder" src="/_images_user/'.PAYPAL_BUTTON_PAYNOW.'" />' ."\n";
			echo TAB_10.'</a>'. "\n";		
		echo TAB_9.'</p>' ."\n";
//===================================================================================================================	
		
		//	set settings in SESSION
		$_SESSION['cust_email'] = $checkout_email;
		$_SESSION['cust_postcode'] = $post_destination;	
		$_SESSION['postage_to_pay'] = $post_calc_cost;
		$_SESSION['postage_method'] = $post_method;
		//$_SESSION['email_4paypal'] = $checkout_email4paypal;
		$_SESSION['cust_phone'] = $checkout_phone;


	}	


	//	CHECK-OUT - STAGE 1
	If ( !isset($_SESSION['check_out_state_2']))
	{
		echo TAB_9.'<h2 class="ShopCheckOut" >Please provide us with the following:</h2>' ."\n";

		if ($error == TRUE)
		{	
			//-------------Display Error
			echo TAB_10.'<h3 class="WarningMSG" >ERROR: please enter the values again</h3>' ."\n";
		}
			echo TAB_10.'<form action="'.$_SERVER['PHP_SELF'].$query_str.'&amp;view=checkout" method="post" >'. "\n";
			
				echo TAB_11.'<ul class="ShopCheckOutForm" >' ."\n";



				if ( SHOP_POST_CALC_INPUT_METHOD_YES == 1)
				{
					//	Select Postage Method
					echo TAB_11.'<li class="ShopCheckOutPostMethod RequiredFormElement" >'. "\n";
						echo TAB_12.'<label for="select_post_method" >'
									.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Choose option:</label>'. "\n";
						echo TAB_12.'<select name="post_method" class="ShopPostSelectMethod" id="select_post_method" >'. "\n";
						
						if (SHOP_POST_ALLOW_PICKUP == 1)
						{ 
							if ($post_method == 'pick-up') {$selected = 'selected="selected"';}
							else	{$selected = '';}

							echo TAB_13.'<option class="WarningMSG" value="pick-up" '.$selected.'>Pick-up</option>'. "\n";						
						}
						
						//	get shipping item info from db
						$mysql_err_msg = 'Postage Methods Infomation unavailable';	
						$sql_statement = 'SELECT method_name, method_code FROM shop_ship_methods'

											.' WHERE carrier_id = "'.SHOP_POST_CARRIER_ID.'"'
											.' AND active = "on"'
											.' ORDER BY seq';					
						
						$ship_methods_result = ReadDB ($sql_statement, $mysql_err_msg);
						while( $ship_methods_info = mysql_fetch_array($ship_methods_result) )
						{
							if ($ship_methods_info['method_code'] == $post_method) {$selected = 'selected="selected"';}
							else	{$selected = '';}
							
							if ($post_method == '') {$selected = 'selected="selected"';}	//	default if not specified
							
							echo TAB_13.'<option value="'.$ship_methods_info['method_code'].'" '.$selected.'>Deliver by: '
										.$ship_methods_info['method_name'].'</option>'. "\n";
						}
						
						echo TAB_12.'</select>'. "\n";
					echo TAB_11.'</li>'. "\n";
					
				}
				
					//	Enter Postcode			//	WILL NEED MODIFYING FOR INTL DELIVERY - insert country code instead ?????
					echo TAB_11.'<li class="ShopCheckOutPostcode RequiredFormElement" >'. "\n";
						echo TAB_12.'<label for="post_destination">'
									.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Your Postcode:</label>'. "\n";
									
						if ($error_msg_postcode != '') {$error_class = ' ErrorHilight';}
						else {$error_class = '';}
						
						echo TAB_12.'<input type="text" name="post_destination"'
									.' class="ShopPostcodeInput'.$error_class.'" value="'.$post_destination.'"'
									.' size="'.SHOP_POST_CALC_INPUT_PCODE_SIZE.'" maxlength="'.SHOP_POST_CALC_INPUT_PCODE_MAX.'"  />'. "\n";
						echo TAB_12.'<span class="Notice" >(Not required for PICK-UP)</span>'. "\n";			
						echo TAB_12.'<span class="WarningMSGSmall" >'.$error_msg_postcode.'</span>'. "\n";
					echo TAB_11.'</li>'. "\n";
					
				//	Get Email
				if ( SHOP_CHECKOUT_REQ_EMAIL == 1 )
				{
					echo TAB_11.'<li class="ShopCheckOutEmail RequiredFormElement" >' ."\n";
						echo TAB_12.'<label for="ShopEnterEmail">'
									.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Your Email:</label>'. "\n";
						
						if ($error_msg_email != '') {$error_class = ' ErrorHilight';}
						else {$error_class = '';}
						
						echo TAB_12.'<input type="text" name="checkout_email" class="ShopCheckOutEmail'.$error_class.'"'
									.' value="'.$checkout_email.'" size="32" />'. "\n";	
						echo TAB_12.'<span class="WarningMSGSmall" >'.$error_msg_email.'</span>'. "\n";				
/* 
						if ($checkout_email4paypal == 'on' ) {$checked = 'checked="checked"';}
						else{$checked = '';}
						echo TAB_12.'<br/><input type="checkbox" name="checkout_email4paypal" class="ShopCheckOutEmail" '.$checked.' />'. "\n";	
						echo TAB_12.'<span class="Notice" >Tick this box if you use this email to log into Paypal</span>'. "\n";	 
*/		
					echo TAB_11.'</li>' ."\n";
					
				}
				
				//	Get Phone
				if ( SHOP_CHECKOUT_OPTIONAL_PHONE == 1 )
				{
					echo TAB_11.'<li class="ShopCheckOutPhone" >' ."\n";
						echo TAB_12.'<label for="ShopEnterPhone">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Your Phone:</label>'. "\n";	
															
						echo TAB_12.'<input type="text" name="checkout_phone" class="ShopCheckOutPhone"'
									.' value="'.$checkout_phone.'" size="16" />'. "\n";										
					echo TAB_11.'</li>' ."\n";
					
				}	
					//	Submit Button
					echo TAB_11.'<li class="ShopCheckOutSubmit" >' ."\n";
					
						//	Continue OR Update Button					
						If ( isset($_SESSION['check_out_state_2']) )
						{ $button_text = 'Edit and Continue';}
						else { $button_text = 'Submit and Continue';}
					
						echo TAB_12.'<input type="submit" name="checkout_form_submit" class="ShopCheckOutSubmit" value="'.$button_text.'" />'. "\n";
					echo TAB_11.'</li>' ."\n";					
	
				echo TAB_11.'</ul>' ."\n";
	
			echo TAB_10.'</form>'. "\n";
	
	}
		
	echo TAB_8.'</div>'."\n";	
	

		

	
?>