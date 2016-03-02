<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	require_once (CODE_NAME.'_email_form_configs.php');
	
	$contact_id = $_REQUEST['emailto'];
	
	$error_msg_email = FALSE;
	$error_msg_message = FALSE;
	$error_msg_captcha 	= FALSE;
	
	$form_info['confirm_msg_display'] = EMAIL_FORM_CONFIRM_MSG_DISPLAY;
	
	//	Get Forms settings - read from db	----------
	$mysql_err_msg = 'This Contact Form Infomation unavailable';	
	$sql_statement = 'SELECT name, email, display_email, active FROM mod_contact_items WHERE contact_id = "'.$contact_id.'" ';

	$mod_contact_items = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
	$email_to = $mod_contact_items['email'];
	$to_name = $mod_contact_items['name'];
	$display_email = $mod_contact_items['display_email'];

	if ( $mod_contact_items['active'] != "on" )
	{
		echo TAB_7.'<h1>This Person&#39;s Contact Details have been Removed.</h1>'. "\n";
		exit;
	}
	
	
	//	================    Check if Form has been submited		======================================================================
	
	$error = FALSE;
	
	//	has form been submited ( or CAPTCHA Reloaded ) ????
	if (isset($_POST['contact_form_submit']) OR isset($_POST['reload_captcha']))
	{									
		//----------time--------------------------------------------------------------
		//------------set default time zone
		date_default_timezone_set(EMAIL_FORM_DATE_DEFAULT_TIMEZONE); 	//-----------only works on PHP 5.1 +

		$time_sent = date("D - d M Y - H:i T");	//---for email 
		$sql_time = date("Y-m-d H:i:s");		//----for mySQL
						
		$failed_captcha = '';
		
		//	has form been submited ( or CAPTCHA Reloaded ) ????
		if (isset($_POST['contact_form_submit']))
		{
				
			//	=====================		Validate and process Form for email		=============================================
			$emailPattern = '/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/';
				
			//$readback_label = array();
			//$readback_value = array();
			$email_msg_label = array();
			$email_msg_value = array();
			
			//	basic validation for  email address	------------------ 		
			if ($_POST['email_from'] != '' AND !preg_match($emailPattern, $_POST['email_from']))
			{
				$error_msg_email = '<br/>Please enter a VALID email address';
				
				//$value_email = $_POST['email_from'];
					
				$error = TRUE;
			}
			else 
			{
				$email_msg_value[0] = $_POST['email_from'];
				$email_msg_label[0] = 'From:';
				//$readback_value[0] = $_POST['email_from'];
				//$readback_label[0] = 'your email:';				
			}	
			
			//	validation for  Message	------------------ 		
			if ( strlen($_POST['message']) < EMAIL_FORM_MESSAGE_MIN_CHRS )
			{
				$error_msg_message = '<br/>Please enter at least '.EMAIL_FORM_MESSAGE_MIN_CHRS.' characters for your message';
				
				//$value_message = $_POST['message'];
							
				$error = TRUE;
			}
			else 
			{
				$email_msg_value[2] = $_POST['message'];
				$email_msg_label[2] = 'message:';
				//$readback_value[2] = $_POST['message'];
				//$readback_label[2] = 'message:';				
			}						
			
			//	CAPTCHA validation	------------------ 		
			if ($_POST['captcha_input'] != strtolower($_SESSION["captcha_code"]))
			{
				$error_msg_captcha = '<br/>The Letters you entered were incorrect';
							
				$error = TRUE;

				if ($_POST['captcha_input'] == '') { $failed_captcha = '(CAPTCHA not entered)';}
				else {$failed_captcha = $_POST['captcha_input'].' ( correct code was: '.$_SESSION["captcha_code"].' )';}	
				
			}
			
		
		
			//	If No ERROR - Process email data 
			//	OR do Logging of unsuccessful captcha attttempts Here  ---  record in Table: contact_form_recieved_data  
			//if ( $error != TRUE )
			if ( $error != TRUE OR $failed_captcha != '')						
			{
				if ($_POST['subject'] == "") {$subject = '(No Subject)';}
				else {$subject = $_POST['subject'];}
				$email_msg_value[1] = $subject;
				$email_msg_label[1] = 'subject:';
				//$readback_value[1] = $subject;
				//$readback_label[1] = 'subject:';	
												
				//	get email address
				$email_from = $_POST['email_from']; 						
						
			}
			//	use contact id as Form id - needs to be stored as "$form_info['mod_id']" for use in "contact_form_db_write.php"
			$form_info['mod_id'] = $contact_id;			
			$form_info['email_to'] = $email_to;
			$form_info['send_email_subject'] = $subject;
									
		}
		else { $error = TRUE;}
		
		if ( $error == FALSE)
		{
			//	write msg details to db (if no error)
			
			//----get message ID ( random n#)
			$msg_id = rand(1,99999999);

			$mysql_err_msg = 'writing recieved contact form data to database';
				
			for ($i = 0; $i	< count ($email_msg_value); $i++ )
			{
				$sql_statement = 'insert into 2_contact_form_recieved_data SET'
						
									.'	form_id = "'.$form_info['mod_id'].'"'
									.',	msg_id = "'.$msg_id.'"'							
									.', time_sent = "'.$sql_time.'"'
									.', ip_add = "'.$_SERVER['REMOTE_ADDR'].'"'
									.', failed_captcha = "'.$failed_captcha.'"'	//  new code
									.', label = "'.$email_msg_label[$i].'"'
									.', value = "'.nl2br(htmlspecialchars ($email_msg_value[$i])).'"';

				UpdateDB ($sql_statement, $mysql_err_msg);
									
			}			
				
			//----------Compile email	and Send----------------------------------------
			require_once ('contact_form/contact_form_compile_email.php');
				
			//	re-direct to stop user Refreshing page and sending email again
			header('location: '.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'&msg='.$msg_id); 
			exit();
				
		} 
			
	}


	//-----------Start PAGE content---------==========================--------------------------------------------------------------
		
//	Print Confirm MSG and details	
if (isset($_REQUEST['msg']) AND $error == FALSE) 
{
		//	Confirm MSG sen and Readback details
		$return_link = $_SERVER['PHP_SELF'].'?p='.$page_id.'&conid='.$contact_id;
		include_once ('contact_form/contact_form_readback.php');
}
		
else 
{
	

	//-------start FORM-------------------------------------------
	echo TAB_7."\n";
	echo TAB_7.'<!--  Start Email To: Contact Form  --> '. "\n";
	echo TAB_7."\n";	
	
	if ($_SESSION['authorized'] == TRUE AND $_SESSION['load_admin'] == TRUE)
	{$hover_class = ' HoverShow';}

	else
	{$hover_class = '';}	
		
	echo TAB_7.'<div class="ContactForm'.$hover_class.'" id="ContactForm_conactID_'.$contact_id.'" >'."\n";
	
		echo TAB_8.'<h2 class="ContactForm">Send an Email to: '.$to_name.'</h2>'. "\n";

		
		if ($error != FALSE AND !isset($_POST['reload_captcha']))
		{
			//-------------Display Error
			echo TAB_8.'<h3 class="WarningMSG" >ERROR: Your Message could not be sent</h3>' ."\n";
		}
			
		echo TAB_8.'<fieldset class="ContactForm" >' ."\n";	
			
			echo TAB_9.'<form class="ContactForm" method="post" action="'.$_SERVER['PHP_SELF'].'?'.htmlentities($_SERVER['QUERY_STRING'])
						.'" enctype="multipart/form-data"> '. "\n";
				
				echo TAB_10.'<ul class="ContactForm" > '. "\n";

					//	Hidden Submit Button -- for useablity
					echo TAB_11.'<li class="HiddenSubmitButton" > '. "\n";
						echo TAB_12.'<input type="submit" name="contact_form_submit" value="" />'. "\n";
					echo TAB_11.'</li> '. "\n";				
				
					//	TO Email ( for display ONLY )
					echo TAB_11.'<li class="ContactForm" id="ContactFormEmailTo" > '. "\n";
			
					switch ($display_email)
					{
						case "":
							$email_display_str = '<input type="text" disabled="disabled" value="'.$to_name.'" />';
						break;
								
						case "text":
							$email_display_str  = '<input type="text" disabled="disabled" value="'.$email_to.'" /><span> ( '.$to_name.' )</span>';
						break;	
							
						case "img":

							$style = 'ContactListEmailLink';
									
							$email_display_str  = '<img class="Text2Image"'
												.' src="/text_image_email.php?con_id='.$contact_id.'&amp;style='.$style.'"'
												.' alt="Email address of: '.$to_name.'" />'."\n".TAB_12.'<span> ( '.$to_name.' )</span>';
				
						break;	
					}
						
						
						echo TAB_12.'<label class="ContactForm" >To: </label>'. "\n";
						echo TAB_12.'<span>'.$email_display_str.'</span>'. "\n";
						
					echo TAB_11.'</li> '. "\n";
					
					
					//	FROM Email ( input )
					echo TAB_11.'<li class="ContactForm RequiredFormElement" id="ContactFormEmailFrom" > '. "\n";

						echo TAB_12.'<label for="email_from" class="ContactForm" >'
									.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>From: </label>'. "\n";
						
						//	Get Value already submitted or default	
						if (isset($_POST['contact_form_submit']) OR isset($_POST['reload_captcha'])) 
						{ $value_email = $_POST['email_from']; }
						else { $value_email = 'Please enter your Email here...'; }
						
						if ($error_msg_email != FALSE) {$error_class = 'ErrorHilight';}
						else {$error_class = '';}
						
						echo TAB_12.'<input type="text" name="email_from" size="'.EMAIL_FORM_FROM_INPUT_SIZE.'"'
									.' title="Please enter a valid email that we can use to contact you, here" '. "\n";
						echo TAB_12.'value="'.$value_email.'" tabindex="1" class="'.$error_class.'"'. "\n";
						//	do auto hidding suggestive text in text window
						echo TAB_12.' onfocus="if(this.value==&quot;'.$value_email.'&quot;)this.value = \'\';" />'. "\n";
						
						//	print ERROR MSG if needed
						echo TAB_12.'<span class="WarningMSGSmall" >'.$error_msg_email.'</span>'. "\n";
				
					echo TAB_11.'</li> '. "\n";	
					
					//	SUBJECT( input )
					echo TAB_11.'<li class="ContactForm" id="ContactFormSubject" > '. "\n";

						echo TAB_12.'<label for="subject" class="ContactForm" >Subject: </label>'. "\n";
						
						//	Get Value already submitted or default	
						if (isset($_POST['contact_form_submit']) OR isset($_POST['reload_captcha'])) 
						{ $value_subject = $_POST['subject']; }
						else { $value_subject = ''; }
						
						echo TAB_12.'<input type="text" name="subject" size="'.EMAIL_FORM_SUBJECT_INPUT_SIZE.'"'
									.' title="Please enter a subject line here (not required)" '. "\n";
						echo TAB_12.'value="'.$value_subject.'" tabindex="2"'. "\n";
						//	do auto hidding suggestive text in text window
						echo TAB_12.' onfocus="if(this.value==&quot;'.$value_subject.'&quot;)this.value = \'\';" />'. "\n";
				
					echo TAB_11.'</li> '. "\n";	

					//	Message ( input )
					echo TAB_11.'<li class="ContactForm RequiredFormElement" id="ContactFormMessage" > '. "\n";

						echo TAB_12.'<label for="message" class="ContactForm" >'
									.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Message: </label>'. "\n";
						
						//	Get Value already submitted or default	
						if (isset($_POST['contact_form_submit']) OR isset($_POST['reload_captcha'])) 
						{ $value_message = $_POST['message']; }
						else { $value_message = ''; }
						
						if ($error_msg_message != FALSE) {$error_class = 'ErrorHilight';}
						else {$error_class = '';}
															
						echo TAB_12.'<textarea name="message" id="message" class="resizable '.$error_class.'" '
							.' cols="'.EMAIL_FORM_MESSAGE_INPUT_WIDTH.'" rows="'.EMAIL_FORM_MESSAGE_INPUT_HEIGHT.'"'. "\n";
							echo TAB_13.' title="Please type your message here" tabindex="3" >'.$value_message.'</textarea>'. "\n";
				
						//	print ERROR MSG if needed
						echo TAB_12.'<span class="WarningMSGSmall" >'.$error_msg_message.'</span>'. "\n";
				
					echo TAB_11.'</li> '. "\n";								



					//	CAPTCHA	-------------------------------
					echo TAB_11.'<li class="ContactForm RequiredFormElement" id="ContactFormCaptcha" > '. "\n";	
					
						echo TAB_12.'<div class="CaptchaDivContainer" > '. "\n";

							//	Input Code
							echo TAB_13.'<div class="CaptchaDivInput" > '. "\n";
								echo TAB_14.'<label for="captcha_input" >'
										.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Please enter the security code:</label>'. "\n";
						
								if ($error_msg_captcha != FALSE) {$error_class = 'ErrorHilight';}
								else {$error_class = '';}
											
								echo TAB_14.'<input type="text" name="captcha_input" size="5" tabindex="4" class="'.$error_class.'" '. "\n";
								echo TAB_14.'title="Enter the Letters A-Z or Numbers that you see in the Seecurity Code" />'. "\n";
							echo TAB_13.'</div> '. "\n";
							
							//	Do CAPTCHA IMAGE and associated links
							echo TAB_13.'<div class="CaptchaDivImage" > '. "\n";
	
								$captcha_id = EMAIL_FORM_CAPTCHA_ID;
								$element_count = 5;
								
								include ('captcha/captcha.php');
		
							echo TAB_13.'</div> '. "\n";
							
							//	Error MSG
							echo TAB_13.'<div class="CaptchaDivErrorMSG" > '. "\n";								
								echo TAB_14.'<span class="WarningMSGSmall" >'.$error_msg_captcha.'</span>'. "\n";
							echo TAB_13.'</div> '. "\n";
					
							
						echo TAB_12.'</div> '. "\n";
						
					echo TAB_11.'</li> '. "\n";					

					
			//	SUBMIT Button	-------------------------------
					echo TAB_11.'<li class="ContactForm RequiredFormElement" id="ContactFormSubmit" > '. "\n";	

						echo TAB_12.'<input type="submit" name="contact_form_submit" id="ContactFormSubmitButton"'. "\n";
						echo TAB_12.' title="Click to Send Message" value="Send Message"';

						echo ' />'. "\n";
						
					echo TAB_11.'</li> '. "\n";	
					
					//	Required Field notice	
					echo TAB_11.'<li class="RequiredFormElement" > '. "\n";
						echo TAB_12.'<p><span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span> Indicates a Required entry</p> '. "\n";	
					echo TAB_11.'</li> '. "\n";
			
	
				echo TAB_10.'</ul> '. "\n";
				
			echo TAB_9.'</form> '. "\n";	

		echo TAB_8.'</fieldset>' ."\n";
		
	echo TAB_7.'</div>'."\n";	
	
	if ($_SESSION['authorized'] == TRUE AND $_SESSION['load_admin'] == TRUE)
	{
		$div_name = 'ContactForm_conactID_'.$contact_id;
		include ('admin/cms_includes/cms_edit_mod_panel.php');
	}	
		
	echo TAB_7."\n";
	echo TAB_7.'<!--  End Email To: Contact Form  --> '. "\n";
	echo TAB_7."\n";	

}

?>