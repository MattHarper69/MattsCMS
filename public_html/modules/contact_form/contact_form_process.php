<?php	

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	//print_r ($_POST);

	$failed_captcha = '';
	$readback_label = array();
	$readback_value = array();
	$email_msg_label = array();
	$email_msg_value = array();
	
	
	
//	Process all Posted Inputs
foreach ( $_POST as $key => $value)
{
	$valueText = 0;

	if ( strstr($key, "inputID") )
	{
		$element_id = substr($key, 8);
			
		//	Get Form element details - read from db	----------
		$mysql_err_msg = 'This Contact Form Validation Infomation unavailable';	
		$sql_statement = 'SELECT * FROM mod_contact_form_elements WHERE element_id = "'.$element_id.'"';

		$result = ReadDB ($sql_statement, $mysql_err_msg);
		$element_info = mysql_fetch_array ($result);

		
		//	Check if all required fields are entered and supply error MSG if not		------------------
		if ($element_info['required'] == 'on' AND ($value == NULL OR $value == ''))
		{
			${"error_msg_element_".$element_id} = '<br/>'.$element_info['error_msg'];
						
			$error = TRUE;
		}
			
		//	basic validation for  email address	------------------ 		
		if ($element_info['rule_1'] == 'email' AND $value != '' AND !preg_match(EMAIL_REG_EXP_STRING, $value))
		{
			${"error_msg_element_".$element_id} = '<br/>Please enter a VALID email address';
				
			$error = TRUE;
		}
	
		//	Check for ALPHANUMERIC  validation
		if ($element_info['rule_1'] == 'alphanum' AND $value != '' AND ctype_alnum(str_replace(' ', '',$value)) == FALSE 
		AND $element_info['required'] == 'on')
		{
			${"error_msg_element_".$element_id} = '<br/>Please enter letters and numbers only (No spaces) for: '.$element_info['name'];
				
			$error = TRUE;
		}

		//	Check for NUMERIC  validation
		if ($element_info['rule_1'] == 'numeric' AND $value != '' AND is_numeric(str_replace(' ', '',$value)) == FALSE 
		AND $element_info['required'] == 'on')
		{
			${"error_msg_element_".$element_id} = '<br/>Please enter numbers only for: '.$element_info['name'];
				
			$error = TRUE;
		}
		
		//	Check for MIN LENGTH  validation
		if ( substr($element_info['rule_2'], 0, 8) == 'minchrs_' AND $value != '' AND $element_info['required'] == 'on')
		{
			$num_min_chrs = substr($element_info['rule_2'], 8);
			if ( strlen($value) < $num_min_chrs )
			{
				${"error_msg_element_".$element_id} = '<br/>Please enter at least '.$num_min_chrs.' characters for: '.$element_info['name'];
				
				$error = TRUE;
			}
		}
		
		//	Check for MAX LENGTH  validation
		if ( substr($element_info['rule_3'], 0, 8) == 'maxchrs_' AND $value != '' )
		{
			$num_max_chrs = substr($element_info['rule_3'], 8);
			if ( strlen($value) > $num_max_chrs AND $element_info['required'] == 'on')
			{
				${"error_msg_element_".$element_id} = '<br/>Please enter less than '.$num_max_chrs.' characters for: '.$element_info['name'];
				
				$error = TRUE;
			}
		}
			
		//	CAPTCHA validation	------------------ 
		$captcha = str_replace(' ', '', trim($value));
		if ( $element_info['element'] == 'captcha' AND strtolower($captcha) != strtolower($_SESSION["captcha_code"]) )
		{
			${"error_msg_element_".$element_id} = '<br/>'.$element_info['error_msg'];
				
			$error = TRUE;
			if ($captcha == '') { $failed_captcha = '(CAPTCHA not entered)';}
			else {$failed_captcha = $captcha.' ( correct code was: '.$_SESSION["captcha_code"].' )';}	

		}


		//	======= check that submited (value text) != default (value text) if so fail validate 
		//	this should be applied as a RULE named "valuetextOK" ---- this is because suggest text may be exactly what the user needs to enter 
		//	(only do this if the field is required)
		if 
		( 
				substr($element_info['rule_4'], 0, 11) != 'valuetextOK' AND $value != '' AND $value == $element_info['value'] 
			AND $element_info['required'] == 'on'
		)
		{
			${"error_msg_element_".$element_id} = '<br/>'.$element_info['error_msg'];
			
			$error = TRUE;		
		}
		if ( $value == $element_info['value'] )
		{
			$valueText = 1;
		}
		
		//	check if 'To email' is selected by user....
		if ( substr($element_info['attrib_4'], 0, 12) == 'set_email_to')
		{
			$overide_email_to = $value;	
		}

		//	check if 'return email' is specified when user selects 'To email'....
		if ( substr($element_info['attrib_5'], 0, 16) == 'set_email_return')
		{
			$overide_email_return = $value;	
		}		

		// Check for bad Things....
		$bad_email_found = 0;
		$bad_ip_found = 0;
		$bad_text_found = 0;
		
		//	Email Filtering (BLACK-LISTED)	------------------ 		
		if 
		(
			   ($element_info['rule_1'] == 'email_filter' AND $value != '')
			OR ($element_info['rule_2'] == 'email_filter' AND $value != '')
			OR ($element_info['rule_3'] == 'email_filter' AND $value != '')
			OR ($element_info['rule_4'] == 'email_filter' AND $value != '')
		)
		{

			
			include_once ('email_blacklist.php');
			
			if (in_array($value, $email_black_list))
			{				
				$bad_email_found = 1;				
			}
			
			
			foreach ($email_black_list_like as $bad_name)
			{
				
				$pos = stripos($value, $bad_name);
				if ($pos !== FALSE)
				{
					$bad_text_found = 1;				
				}
				
			}

		}
		
		if 
		(
			   ($element_info['rule_1'] == 'ip_filter')
			OR ($element_info['rule_2'] == 'ip_filter')
			OR ($element_info['rule_3'] == 'ip_filter')
			OR ($element_info['rule_4'] == 'ip_filter')
		)
		{
			if (in_array($_SERVER['REMOTE_ADDR'], $email_black_list_ip))
			{						
				$bad_ip_found = 1;
			}	
			
		}

		//	get email address
		if ($element_info['element'] == 'email') { $email_from = $value; }
		
		if($bad_email_found == 1 OR $bad_ip_found == 1 OR $bad_text_found == 1)
		{
			${"error_msg_element_".$element_id} = '<br/>'.EMAIL_BLACK_LIST_MSG;
			
			$error = TRUE;				
			
			if(ALERT_CONTACT_FORM_BAD_EMAIL)
			{
				
				$textarea_text = $_POST['inputID_' . $text_area_id];
				
				include_once ('contact_form_bad_email_alert.php');			
			}
			
		}		
		
		
//	===========================	EDITED TO HERE	=================================================================================
		
		//	If No ERROR - Process email data 
		//	OR do Logging of unsuccessful captcha attttempts Here  ---  record in Table: 2_contact_form_recieved_data  
		//	( IF no fields are entered, "include_in_email"  for CAPTCHA needs to be SET to ON to record it in db)
		//if ( $error != TRUE )
		if ( $error != TRUE OR $failed_captcha != '')						//  new code
		{
			//	Do Read back / Auto reply MSG and email MSG Details	-------------------------------------
			if ($element_info['include_in_email'] == 'on')	
			{				
				if ( $value == '' OR $valueText == 1) 
				
				{$display_value = '[ Not Specified / Left Blank ]';}
				else {$display_value = $value;}
				$email_msg_value[] = $display_value;
				$email_msg_label[] = $element_info['send_label'];
			}
			
			if ($element_info['confirm_msg_readback'] == 'on')
			{
				if ( $value == '' OR $valueText == 1) 
				{$display_value = '[ Not Specified / Left Blank ]';}
				else {$display_value = $value;}				
				$readback_value[] = $display_value;
				$readback_label[] = $element_info['send_label'];
			}
			
			//	get email address
			if ($element_info['element'] == 'email') { $email_from = $value; }
			
			//	For File Attachments
			if ($element_info['type'] == 'email')		////////		should read 'file'  NOT 'email"   ????????? 
			{ 				
				$file_upload = TRUE; 
			}
			
		}

	}


	//	Check Validation of CheckBoxes - since checboxes are not posted if un-checked, send a hidden input to inform of their presence and un-checked state
	if (strstr($key, "ReqdCheckBox") )
	{	
		$element_id = $value;
			
		//	Get Form element details - read from db	----------
		$mysql_err_msg = 'This Contact Form Validation Infomation unavailable';	
		$sql_statement = 'SELECT * FROM mod_contact_form_elements WHERE element_id = "'.$element_id.'"';

		$result = ReadDB ($sql_statement, $mysql_err_msg);
		$element_info = mysql_fetch_array ($result);

		if ($element_info['required'] == 'on' AND ($_POST['inputID_'.$element_id] == NULL OR $_POST['inputID_'.$element_id] == ''))
		{
			${"error_msg_element_".$element_id} = '<br/>'.$element_info['error_msg'];
						
			$error = TRUE;
		}
		
	}
	
}

//	overide email to ?	
if (isset($overide_email_to))
{
	$email_to = $overide_email_to;
}

else
{
	$email_to = $form_info['email_to'];
}
	
//	overide email return ?	
if (isset($overide_email_return))
{
	$email_return = $overide_email_return;
}

else
{
	$email_return = $form_info['email_from'];
}

?>