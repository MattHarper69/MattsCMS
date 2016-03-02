<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	if (PAYPAL_SANDBOX_FLAG == TRUE)
	{
		define ('PAYPAL_STANDARD_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');		//	FOR TESTING	
	}
	else
	{
		define ('PAYPAL_STANDARD_URL', 'https://www.paypal.com/cgi-bin/webscr');
	}
	
/* 	
	// 	has user supplied their paypal email ??	 
	if ($_SESSION['email_4paypal'] == 'on')
	{ $email_4paypal_str = TAB_4.'<input type="hidden" name="email" value="'.$cust_email.'" />'."\n"; }
	else { $email_4paypal_str = '';}
*/
 
	$paypal_form_user_details = '';
	
	$paypal_form = 
		
		TAB_3.'<form name="paypal" action="'.PAYPAL_STANDARD_URL.'" method="post">' ."\n"
			.TAB_4.'<input type="hidden" name="cmd" value="_cart" />' ."\n"
			.TAB_4.'<input type="hidden" name="upload" value="1" />' ."\n"
			.TAB_4.'<input type="hidden" name="business" value="'.PAYPAL_PRIMARY_EMAIL.'" />' ."\n"
			
			.TAB_4.'<input type="hidden" name="shipping_1" value="'.$postage_to_pay.'" />' ."\n"
			.TAB_4.'<input type="hidden" name="currency_code" value="'.SHOP_CURRENCY_ISO3.'" />' ."\n"
			.TAB_4.'<input type="hidden" name="return" value="http://'.SITE_URL.'/index.php?p='.SHOP_PAGE_ID.'&amp;view=PPreturn" />' ."\n"		
			//.TAB_4.'<input type="hidden" name="notify_url" value="http://'.SITE_URL.'/index.php?p='.SHOP_PAGE_ID.'&amp;view=PPreturn" />' ."\n"
			.TAB_4.'<input type="hidden" name="cancel_return" value="http://'.SITE_URL.'/index.php?p='.SHOP_PAGE_ID.'&amp;view=PPreturn_canx" />' ."\n"
			.TAB_4.'<input type="hidden" name="rm" value="2" />' ."\n"
			.TAB_4.'<input type="hidden" name="cbt" value="'.SITE_NAME.' Website" />' ."\n"

			.TAB_4.'<input type="hidden" name="invoice" value="'.$order_id.':" />' ."\n"
		
			//.TAB_4.'<input type="hidden" name="page_style" value="primary" />' ."\n"			
			//.$email_4paypal_str;
			;
			
	if (PAYPAL_OVERRIDE_USER_DETAILS == 1)
	{
		$paypal_form_user_details =
			 TAB_4.'<!-- Override and customize paypay site with users Name and Address detals from db -->' ."\n"
			.TAB_4.'<input type="hidden" name="address_override" value="1" />' ."\n"
			.TAB_4.'<input type="hidden" name="first_name" value="'.$first_name.'" />' ."\n"
			.TAB_4.'<input type="hidden" name="last_name" value="'.$last_name.'" />' ."\n"
			.TAB_4.'<input type="hidden" name="address1" value="'.$address_1.'" />' ."\n"
			.TAB_4.'<input type="hidden" name="address2" value="'.$address_2.'" />' ."\n"
			.TAB_4.'<input type="hidden" name="city" value="'.$city.'" />' ."\n"
			.TAB_4.'<input type="hidden" name="state" value="'.$state.'" />' ."\n"
			.TAB_4.'<input type="hidden" name="zip" value="'.$zip.'" />' ."\n"
			.TAB_4.'<input type="hidden" name="country" value="'.$country_code.'" />' ."\n";
	}
		
	$paypal_form .= $paypal_form_user_details
	
			.TAB_4.'<!-- customize paypay site with pretty banners and color -->' ."\n"
			.TAB_4.'<input type="hidden" name="cpp_header_image" value="'.PAYPAL_BANNER.'" />' ."\n"
			.TAB_4.'<input type="hidden" name="cpp_headerback_color" value="'.PAYPAL_HEADER_BG_COLOUR.'" />' ."\n"
			.TAB_4.'<input type="hidden" name="cpp_headerborder_color" value="'.PAYPAL_HEADER_BD_COLOUR.'" />' ."\n"
		
			.$paypal_form_items;
	
	$check_out_paypal_form = $paypal_form
	 
			 .TAB_4.'<noscript>' ."\n"
				.TAB_5.'<!-- Do Button (if Javascript turned off-->' ."\n"
				.TAB_5.'<h2>You have disabled Javascript, please click the "PayNow" button to continue</h2>' ."\n"
				.TAB_5.'<input type="image" name="submit" src="/_images_user/'.PAYPAL_BUTTON_PAYNOW.'" alt="Pay with PayPal" />' ."\n"
			.TAB_4.'</noscript>' ."\n"
			
		.TAB_3.'</form>' ."\n";
	
	$stored_paypal_form	= $paypal_form
	
			.TAB_4.'<input type="image" name="submit" src="/_images_user/'.PAYPAL_BUTTON_PAYNOW.'" alt="Pay with PayPal" />' ."\n"
		.TAB_3.'</form>' ."\n";	
	

	
	
		
?>