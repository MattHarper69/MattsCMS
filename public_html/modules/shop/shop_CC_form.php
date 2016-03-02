<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');			
	
	
	if (isset($_REQUEST['paymeth']))
	{
		$payment_method_type = $_REQUEST['paymeth'];
	}
	elseif (isset($_SESSION['paymeth']))
	{
		$payment_method_type = $_SESSION['paymeth'];
	}
	
	$file_path_offset = '';
	
	//	set VARs
	$CC_error = FALSE;
	$CC_name = '';
	$CC_number = '';
	$CC_exp_month = '';
	$CC_exp_year = '';	
	$CC_error_msg_exp_date = '';
	$CC_CVN = '';
	$CC_cardtype = '';
	
	//	do we need CVN:
	$get_cvn = FALSE;
	
	//	eWAY
	if ($payment_method_type == 1 AND EWAY_DEFAULT_PAYMENT_METHOD == 'REAL_TIME_CVN')
	{
		$get_cvn = TRUE;
	}
	
	//	St George IPG API
	if ($payment_method_type == 4 AND STG_API_GET_CC_CVN == 1)
	{
		$get_cvn = TRUE;		
	}

	//	do we need Card Type:
	$get_card_type = FALSE;
	
	//	eWAY
	if ($payment_method_type == 1)
	{
		$get_card_type = TRUE;
	}
	
	//	St George IPG API
	if ($payment_method_type == 4 AND STG_API_GET_CC_TYPE == 1)
	{
		$get_card_type = TRUE;		
	}
	
	if (isset($_SESSION['CC_name']) AND $_SESSION['CC_name'] != '')
	{
		$CC_name = $_SESSION['CC_name'];	
	}
	
	//	if a CC card holders name has not been prev. entered use check out name
	else
	{
		$CC_name = $_SESSION['checkout_name'];
	}
	
	if (isset($_SESSION['CC_cardtype']))
	{	
		$CC_cardtype = $_SESSION['CC_cardtype'];
	}

	
	//	process CC form
	if (isset ($_POST['CC_form_submit']))
	{	
		//	set cc_type as 'n/a' if not sent (not requied)
		if (!isset($_POST['CC_cardtype']))
		{
			$_POST['CC_cardtype'] = 'n/a';
		}
		
		//	set cc_CVN as 'n/a' if not sent (not requied)
		if (!isset($_POST['CC_CVN']))
		{
			$_POST['CC_CVN'] = 'n/a';
		}
		
		//	Or use previous values and re-store in session
		$payment_method_type = $_SESSION['paymeth'] = $_REQUEST['paymeth'];
		$CC_name = $_SESSION['CC_name'] = $_POST['CC_name'];
		$CC_cardtype = $_SESSION['CC_cardtype'] = $_POST['CC_cardtype'];
		$CC_number = $_POST['CC_number'];		
		$CC_exp_month = $_POST['CC_exp_month'];
		$CC_exp_year = $_POST['CC_exp_year'];
		$CC_CVN = $_POST['CC_CVN'];

					
		//	Check for Name for MIN and MAX LENGTH 
		if ( strlen($CC_name) < 3 OR strlen($CC_name) > SHOP_CC_FORM_NAME_MAX_LENGTH OR ctype_alpha(str_replace(' ', '',$CC_name)) == FALSE)
		{
			$CC_error_msg_name = '<br/>Please enter between 3 and '.SHOP_CC_FORM_NAME_MAX_LENGTH.' letters for the name';			
			$CC_error = TRUE;
		}
		else {unset ($CC_error_msg_number);}	
			
		//	Check Card number for MIN and MAX LENGTH and numbers only
		if ( strlen($CC_number) > SHOP_CC_FORM_NUM_MAX_LENGTH OR strlen($CC_number) < SHOP_CC_FORM_NUM_MIN_LENGTH 
				OR is_numeric(str_replace(' ', '',$CC_number)) == FALSE)
		{
			$CC_error_msg_number = '<br/>Please enter between '.SHOP_CC_FORM_NUM_MIN_LENGTH 
									.' and '.SHOP_CC_FORM_NUM_MAX_LENGTH.' numbers only for the card number';			
			$CC_error = TRUE;
		}
		else {unset ($CC_error_msg_number);}

		//	Check Card Expiry Date before sending (if set to On)
		if (SHOP_CC_VALIDATE_CARD_EXPIRY_DATE == 1)
		{
			//	get the last day of the th expiry month
			$exp_last_day_of_month = date("t" , strtotime(date("d M Y", strtotime('20' . $CC_exp_year . '-' . $CC_exp_month . '-28'))));
			$now_last_day_of_month = date("t");	//	last day of the current month
			$expiry_date = strtotime('20' . $CC_exp_year . '-' . $CC_exp_month . '-' . $exp_last_day_of_month);
			$expiry_date = $expiry_date + (24 * 3600);	// add a day to the expiry date
			
			if ($expiry_date < time())
			{
				$CC_error_msg_exp_date = '<br/>This card has already EXPIRED - check the card expiry date again';			
				$CC_error = TRUE;			
			}
			else {$CC_error_msg_exp_date = '';}
		}
		
		//	Check Card CVN for MIN LENGTH and numbers ( only if set to Required)
		if ($get_cvn == TRUE) 
		{
			if ( strlen($CC_CVN) > 4 OR strlen($CC_CVN) < 3 OR is_numeric(str_replace(' ', '',$CC_CVN)) == FALSE)
			{
				$CC_error_msg_CVN = '<br/>Please enter between 3 and 4 numbers only for the card CVN number';			
				$CC_error = TRUE;
			}
			else {unset ($CC_error_msg_CVN);}	
		}

		
		//	Advance user to next STAGE if no error
		if ( $CC_error == FALSE ) 
		{
			//	PROCESS CHECK PAYMENT
			if ($payment_method_type == 1)
			{
				require_once('pay_gate/eway_process.php');						
			}
			
			if ($payment_method_type == 4)
			{
				require_once('pay_gate/stgeorge_process.php');						
			}				
			
		}
		
	}				
			
	echo TAB_10.'<div id="ShopCCform" class="ShopDiv">'."\n";
		echo TAB_11.'<h4 id="ShopCCformHeading" >Please enter your credit card details and click the Button, Below</h4>' ."\n";

		if ($CC_error == TRUE)
		{	
			//-------------Display Error
			echo TAB_11.'<h3 class="WarningMSG" >ERROR: please enter the details again</h3>' ."\n";
		}
			echo TAB_11.'<form id="CC_form" action="'.$_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'" method="post" enctype="multipart/form-data" >'. "\n";
							
				//	send hidden info
				echo TAB_12.'<input type="hidden" name="view" value="checkout" />'. "\n";
				echo TAB_12.'<input type="hidden" name="paymeth" value="'.$payment_method_type.'" />'. "\n";				
							
				echo TAB_12.'<ul class="ShopCCForm" >' ."\n";

					//	Card holders Name
					echo TAB_13.'<li class="ShopCCName RequiredFormElement" >' ."\n";
						echo TAB_14.'<label for="CC_name">'
									.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Card holder&#39;s Name:</label>'. "\n";
						
						if (isset($CC_error_msg_name)) {$CC_error_class = ' ErrorHilight';}
						else 
						{
							$CC_error_class = '';
							$CC_error_msg_name = '';
						}
						
						echo TAB_14.'<input type="text" name="CC_name" class="CheckoutField'.$CC_error_class.'"'
									.' value="'.$CC_name.'" size="32" maxlength="'.SHOP_CC_FORM_NAME_MAX_LENGTH.'" />'. "\n";	
						echo TAB_14.'<span class="WarningMSGSmall" >'.$CC_error_msg_name.'</span>'. "\n";				
				
					echo TAB_13.'</li>' ."\n";				
					
					//	CC card type
					if ($get_card_type == 1)
					{
						echo TAB_13.'<li class="ShopCCcardType RequiredFormElement" >' ."\n";
							echo TAB_14.'<label for="CC_cardtype">'
										.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Card Type:</label>'. "\n";

							echo TAB_14.'<select name="CC_cardtype" class="CheckoutField" />'. "\n";
							
							//	read from db to get All CC cards accepted
							$mysql_err_msg = 'Accepted Credit Card Types information unavailable';	
							$sql_statement = 'SELECT cardtype_name FROM shop_cc_cardtypes' 
											.' WHERE active = "on"'
											.' AND pay_method_id = "'.$payment_method_type.'"'
											.' ORDER BY cardtype_id'
											;
								
							$CC_cardtypes_result = ReadDB ($sql_statement, $mysql_err_msg);
							
							while ($CC_cardtypes_info = mysql_fetch_array($CC_cardtypes_result))
							{						
								if ($CC_cardtypes_info['cardtype_name'] == $CC_cardtype ) {$selected = 'selected="selected"';}
								else {$selected = '';}
								echo TAB_15.'<option value="'.$CC_cardtypes_info['cardtype_name'].'"'.$selected.'>'
											.$CC_cardtypes_info['cardtype_name'].'</option>'. "\n";		
								
							}
		
							echo TAB_14.'</select>'. "\n";		
					
						echo TAB_13.'</li>' ."\n";					
					}


					//	Get Card Number				
					echo TAB_13.'<li class="ShopCCNumber RequiredFormElement" >' ."\n";
						echo TAB_14.'<label for="CC_number">'
									.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Card Number:</label>'. "\n";
						
						if (isset($CC_error_msg_number)) {$CC_error_class = ' ErrorHilight';}
						else 
						{
							$CC_error_class = '';
							$CC_error_msg_number = '';
						}
						
if ($payment_method_type == 1)
{$CC_number = '4444333322221111';}			//	<<========================<<<<<<<<<<<	FOR TESTING ONLY	(eWay))
else{$CC_number = '4111111111111111';}		//	<<========================<<<<<<<<<<<	FOR TESTING ONLY	(St George API)

	
						echo TAB_14.'<input type="text" name="CC_number" class="CheckoutField'.$CC_error_class.'"'
									.' value="'.$CC_number.'" size="20" maxlength="'.SHOP_CC_FORM_NUM_MAX_LENGTH.'" autocomplete="off" />'. "\n";	
						echo TAB_14.'<span class="WarningMSGSmall" >'.$CC_error_msg_number.'</span>'. "\n";				
				
					echo TAB_13.'</li>' ."\n";
										
					echo TAB_13.'<li class="ShopCCExpiry RequiredFormElement" >' ."\n";
						
						//	Get Card Expiry Month
						echo TAB_14.'<label for="CC_exp_month">'
									.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Card Expiry Date:</label>'. "\n";
						
						echo TAB_14.'<select name="CC_exp_month" class="CheckoutField" />'. "\n";
$CC_exp_month = 12;		//	<<========================<<<<<<<<<<<	FOR TESTING ONLY						
						for ($month = 1; $month < 13; $month++)
						{
							if ($month < 10) { $month = '0' . $month;}
							if ($CC_exp_month == $month) {$selected = 'selected="selected"';}
							else {$selected = '';}
							echo TAB_15.'<option value="'.$month.'"'.$selected.'>'.$month.'</option>'. "\n";
						}
						echo TAB_14.'</select>'. "\n";

						echo TAB_14.'&nbsp / &nbsp'. "\n";

						//	Get Card Expiry Year
						echo TAB_14.'<select name="CC_exp_year" class="CheckoutField'.$CC_error_class.'" />'. "\n";
					
						$this_year = date('y');
						for ($year = $this_year; $year < $this_year + 11; $year++)
						{
							if ($CC_exp_year == $year) {$selected = 'selected="selected"';}
							else {$selected = '';}
							echo TAB_15.'<option value="'.$year.'"'.$selected.'>'.$year.'</option>'. "\n";
						}
						echo TAB_14.'</select>'. "\n";

						echo TAB_14.'<span class="WarningMSGSmall" >'.$CC_error_msg_exp_date.'</span>'. "\n";
				
					echo TAB_13.'</li>' ."\n";

					
					//	Get Card CVN if set to Required
					if ($get_cvn == TRUE) 					
					{				
						echo TAB_13.'<li class="ShopCC_CVN RequiredFormElement" >' ."\n";
							echo TAB_14.'<label for="CC_CVN">'
										.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Card CVN:</label>'. "\n";
							
							if (isset($CC_error_msg_CVN)) {$CC_error_class = ' ErrorHilight';}
							else 
							{
								$CC_error_class = '';
								$CC_error_msg_CVN = '';
							}
$CC_CVN = 123;		//	<<========================<<<<<<<<<<<	FOR TESTING ONLY					
							echo TAB_14.'<input type="text" name="CC_CVN" class="CheckoutField'.$CC_error_class.'"'
										.' value="'.$CC_CVN.'" size="4" maxlength="4" autocomplete="off" />'. "\n";

							echo TAB_14.'<a href="/modules/shop/shop_explain_cvn.php" title="What is the CVN" rel="ShopExplainCVN" >'. "\n";
								echo TAB_15.'What is this?'. "\n";
							echo TAB_14.'</a>'. "\n";
						
							echo TAB_14.'<span class="WarningMSGSmall" >'.$CC_error_msg_CVN.'</span>'. "\n";				
					
						echo TAB_13.'</li>' ."\n";
					}				
			
						
					//	Submit Button
					echo TAB_13.'<li class="ShopCheckOutSubmit" >' ."\n";

						echo TAB_14.'<input type="submit" name="CC_form_submit" id="CC_Submit"'
									.' class="ShopCheckOutSubmit HideOnClick" value="Click here to make Payment" />'. "\n";
					
					echo TAB_13.'</li>' ."\n";				
					echo TAB_13.'<li class="ShopCheckOutSubmit ShowOnClick" ></li>' ."\n";					
							
				echo TAB_12.'</ul>' ."\n";
	
			echo TAB_11.'</form>'. "\n";		
			
			//	Change Payment Method Link
			if (count($payment_method_array) > 1)
			{
				echo TAB_11.'<p>' ."\n";	

					$query_str = '?p='.SHOP_PAGE_ID.'&amp;view=checkout&amp;checkstate=2';
					echo TAB_12.'<a class="ShopCheckoutEdit" href="'.$_SERVER['PHP_SELF'].$query_str.'"'. "\n";		
					echo TAB_12.' title="Click to go back and change the Payment Method" >[ Change Payment Method ]</a>'. "\n";		
				echo TAB_11.'</p>' ."\n";		
			}

		
	echo TAB_10.'</div>'."\n";
			
?>
