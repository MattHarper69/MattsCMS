<?php

	// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


		//	CHECK BASIC FIELD VALIDATION
		
		//	Check for Name for MIN LENGTH and a least on letter
		if ( strlen($checkout_name) < 3 OR ctype_alpha(str_replace(' ', '',$checkout_name)) == FALSE)
		{
			$error_msg_name = '<br/>Please enter only letters (at least 3) for your name';			
			$error = TRUE;
		}
		else {unset ($error_msg_name);}	
		
		//	basic validation for  email address	------------------ 		
		if (SHOP_CHECKOUT_REQ_EMAIL == 1 AND !preg_match(EMAIL_REG_EXP_STRING, $checkout_email))
		{
			$error_msg_email = '<br/>Please enter a VALID email address';
			$error = TRUE;
		}
		else {unset ($error_msg_email);}
		
		//	Check for Phone for MIN LENGTH
		if (SHOP_CHECKOUT_REQ_PHONE == 1 AND strlen($checkout_phone) < 6)
		{
			$error_msg_phone = '<br/>Please enter at least 6 numbers for your phone number';
			$error = TRUE;			
		}		
		elseif (SHOP_CHECKOUT_REQ_PHONE == 1 AND is_numeric(str_replace(' ', '',$checkout_phone)) == FALSE)
		{
			$error_msg_phone = '<br/>Please enter only numbers (at least 6) for your phone number';			
			$error = TRUE;
		}
		else {unset ($error_msg_phone);}

		//	Check for address_1 for MIN LENGTH 
		if ( strlen($checkout_address_1) < 6 )
		{
			$error_msg_address_1 = '<br/>Please enter at least 6 letters for your address';			
			$error = TRUE;
		}
		else {unset ($error_msg_address_1);}


		//	Check for postcode for MIN LENGTH and or numeric
		if ($has_postcodes == 'on' AND $postcode_required == 'on')
		{		
			if 
			(
				( strlen($checkout_postcode) < $pcode_min_length OR strlen($checkout_postcode) > $pcode_max_length)
				OR ($postcode_only_num == 'numbers' AND is_numeric(str_replace(' ', '',$checkout_postcode)) == FALSE)
				OR ($postcode_only_num == 'numbers and letters' AND ctype_alnum(str_replace(' ', '',$checkout_postcode)) == FALSE)
			)
			{
				if ($pcode_max_length == $pcode_min_length) {$pcode_length_str = $pcode_max_length;}
				else {$pcode_length_str = 'between '.$pcode_min_length.' and '.$pcode_max_length;}
				$error_msg_postcode = '<br/>Please enter '.$pcode_length_str.' '.$postcode_only_num.' for your postcode';	
				$error = TRUE;				
			}
		
			else {unset ($error_msg_postcode);}					
		
		}	

		//	Check if Have read rules checkbox checked
		if (SHOP_CHECKOUT_CHECK_READ_RULES == 1 AND $checkout_read_rules != 'on')
		{
			$error_msg_check_read_rules = '<br/>Please read the Terms and Conditions and check this box';
			$error = TRUE;
		}
		
?>