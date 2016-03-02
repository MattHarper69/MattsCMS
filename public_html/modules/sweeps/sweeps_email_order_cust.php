<?php

	// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	//	image file embedded	
	$image_file_path = '../../_images_user/';		
	$image_cid = md5(SHOP_ORDERS_EMAIL_IMAGE);


	//-----Customer PHONE $string
	if ( $_SESSION['checkout_phone'] != '' AND $_SESSION['checkout_phone'] != NULL)
	{
		$phone_str = TAB_3.'Phone: '.$_SESSION['checkout_phone'].'<br/>' .PHP_EOL;	
	}	
	
	//-----build customers address $strings
	$address_str_1 = $_SESSION['checkout_address_1'];
	if ($_SESSION['checkout_address_2'] != '' AND $_SESSION['checkout_address_2'] != NULL)
	{$address_str_2 = TAB_1.'               ' . $_SESSION['checkout_address_2'] . PHP_EOL;}
	if ($_SESSION['checkout_state'] != '' AND $_SESSION['checkout_state'] != NULL)
	{$address_str_3 = TAB_1 . '               ' . $_SESSION['checkout_state'];}
	if ($_SESSION['checkout_postcode'] != '' AND $_SESSION['checkout_postcode'] != NULL)
	{$address_str_3 .= ' - '.$_SESSION['checkout_postcode'] . PHP_EOL;}
	else {$address_str_3 .= PHP_EOL;}
		
		//--------------------------------------------message body TEXT:-------------------------------------	

		$plain_text  = 	PHP_EOL		
						. '=================='
						. AddCharsToStr (strlen(SITE_NAME), '=')
						. '========='
						. PHP_EOL
						;
							
							                                  
		$plain_text .=	TAB_1.'Order from the '.SITE_NAME.' Website' . PHP_EOL;
		
		$plain_text .= 			
						  '=================='
						. AddCharsToStr (strlen(SITE_NAME), '=')
						. '========='
						. PHP_EOL
						;
							
		$plain_text .=	PHP_EOL
		
						. TAB_1 . 'Hello '.$_SESSION['checkout_name'].', here are the details of your Order:' . PHP_EOL
						
						. PHP_EOL
						
						. TAB_1 . '*   Invoice:   ' . $invoice_num . PHP_EOL

						. TAB_1 . '*   Date:      ' . $time_of_order . PHP_EOL
				
						. TAB_1 . '*   Name:      ' . $_SESSION['checkout_name'] . PHP_EOL
						
						. TAB_1 . '*   Email:     ' . $_SESSION['checkout_email'] . PHP_EOL
						
						. TAB_1 . '*   Phone:     ' . $_SESSION['checkout_phone'] . PHP_EOL
						
						. TAB_1 . '*   Address:   ' . $address_str_1 . PHP_EOL
						
						. $address_str_2
						
						. $address_str_3
						
						. TAB_1 . '               ' . $_SESSION['checkout_country'] . PHP_EOL						
						
						. PHP_EOL
						
						. '----------------------------------------------------------------------' . PHP_EOL
						
						. PHP_EOL
						
						. TAB_1 . 'Purchased Tickets:' . PHP_EOL

						. '===========================================================================' . PHP_EOL
						. ' Event Name               Event     Event     Quantity   Tickets      Cost ' . PHP_EOL
						. '                          Code      Date                 Numbers           ' . PHP_EOL						
						. '===========================================================================' . PHP_EOL
						
						. $inv_text_item_listing
						
					  //. 'A Sample Event            1234    20/12/2012     100    7161363    $1000.00' . PHP_EOL
					  //. '                                                       - 7161363           ' . PHP_EOL
					  
						. '===========================================================================' . PHP_EOL
						. '                                                  TOTAL AMOUNT:'           
						. addCharsToStr (12 - strlen($grand_total_show), ' ') .      $grand_total_show  . PHP_EOL
						. '                                                ===========================' . PHP_EOL
						
						. PHP_EOL . PHP_EOL
						
						. TAB_1 .' You can view and print your reciept by clicking this link:' . PHP_EOL	
						. TAB_2 .'[https://'.SITE_URL.'/index.php?p='.SHOP_PAGE_ID.'&view=paymade&invoice='.$hash_inv_num.']' .PHP_EOL
						
						. PHP_EOL
						
						. TAB_1 . 'Thank you for your support,' . PHP_EOL	
						. TAB_1 . 'The '.SITE_NAME.' team.' . PHP_EOL
						
						. PHP_EOL	
						
						. TAB_1 . '------ END of MESSAGE ------' .PHP_EOL
					
						;		
		
		$plain_text = wordwrap($plain_text, 80);
	
		//--------------------------------------------message HTML body:-------------------------------------

		//	html email head
		
		//	Read CSS file to get Invoice styles
		$css_file = '../../_themes/'.GetSiteThemeDir().'/shop_invoice.css';
		$style_code = '';
		if (file_exists($css_file))
		{		
			$css_code = file_get_contents($css_file);
			$style_code =
							TAB_3.'<style>' . PHP_EOL
							
								. PHP_EOL
								
								. $css_code . PHP_EOL
								
								. PHP_EOL	
								
							.TAB_3.'</style>' . PHP_EOL	
							;

		}		
		
		//	Do Logo Banner image
		if (file_exists($image_file_path . '/' . SHOP_ORDERS_EMAIL_IMAGE))
		{
			if (SHOP_ORDERS_EMAIL_IMAGE_SOURCE == 'embedded')
			{
				$logo_image_code = TAB_3.'<img src="cid:'.$image_cid.'" />' . PHP_EOL;		
			}
			elseif (SHOP_ORDERS_EMAIL_IMAGE_SOURCE == 'external')
			{
				$logo_image_code = TAB_3.'<img src="https://'.SITE_URL.'/_images_user/'.SHOP_ORDERS_EMAIL_IMAGE.'" />' . PHP_EOL;
			}
			else
			{
				$logo_image_code = '';
			}

		}
		
		//	compile html email
		$html_text = 
					DOCTYPE_TAG_CODE . PHP_EOL
					
					.HTML_OPEN_TAG_CODE . PHP_EOL
					
						.TAB_2.'<head>' . PHP_EOL
							.TAB_3 . META_HTTP_EQUIV_TAG_CODE . PHP_EOL
										
							.TAB_3.'<title>Order from: '.SITE_NAME.'</title>' . PHP_EOL
							
							. $style_code

						.TAB_2.'</head>' . PHP_EOL	
					
						.TAB_2.'<body>' . PHP_EOL
												
							//	Do header Logo image
							.$logo_image_code
							
							.TAB_3.'<h3>Order from the '.SITE_NAME.' Website...</h3>' . PHP_EOL
							
							.TAB_3.'<p>Hello '.$_SESSION['checkout_name'].', here are the details of your Order ( n#: '.$invoice_num.' )</p>' 
							. PHP_EOL
							
							. $invoice_html . PHP_EOL
							
							.TAB_3.'<p>You can view and print your reciept on-line by clicking 
							<a href="https://'.SITE_URL.'/index.php?p='.SHOP_PAGE_ID.'&view=paymade&invoice='.$hash_inv_num.'">here</a></p>'.PHP_EOL
							
							.TAB_3.'<p>Thank you for your support,</br>The '.SITE_NAME.' team.</p>' . PHP_EOL	
							
							.TAB_3.'<p>------ END of MESSAGE ------</p>' . PHP_EOL
						
						.TAB_2.'</body>' . PHP_EOL
					.TAB_1.'</html>' . PHP_EOL					
					;		
					
		//-------------------------------------------------------------------------------------------------------------------

	
	//	compile headers

	//	to:
	$to = $_SESSION['checkout_email'];

	//	Subject:
	$subject = 'Order: '.$invoice_num.' from: ' .SITE_NAME;
	
	$ext_par = '-f ' . SHOP_ORDERS_EMAIL_FROM;
	
	//	headers:
	if (SHOP_ORDERS_EMAIL_FROM != '')
	{
		$headers  = 'From: "'. SITE_NAME . '" <' . SHOP_ORDERS_EMAIL_FROM . '>' . PHP_EOL;
		$headers .= 'Return-Path: '.SHOP_ORDERS_EMAIL_FROM . PHP_EOL;		
	}
	else
	{
		$headers = 'From: '.SITE_URL. PHP_EOL;
	}

	
	//	compile and send email
	require('sweeps_email_order_send.php');
	
?>