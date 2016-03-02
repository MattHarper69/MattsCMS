<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	$div_name = 'ContactForm_'.$mod_id; 
	
	//	unset Flag to print " * Indicates a Required entry " Notice at the end of form
	$theres_reqd_fields = FALSE;
		
	//	Get Forms settings - read from db	----------
	$mysql_err_msg = 'This Contact Form Infomation unavailable';	
	$sql_statement = 'SELECT * FROM mod_contact_form WHERE mod_id = "'.$mod_id.'" ';

	$form_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

	//	Check if Form has been submited	------------
	
	$error = FALSE;
	$subject = '';
	
	//	has form been submited ( or CAPTCHA Reloaded ) ????
	if (isset($_POST['contact_form_submit']) OR isset($_POST['reload_captcha']))
	{									
		//----------time--------------------------------------------------------------
		//------------set default time zone
		date_default_timezone_set($form_info['time_zone']); 	//-----------only works on PHP 5.1 +

		$time_sent = date("D - d M Y - H:i T");	//---for email 
		$sql_time = date("Y-m-d H:i:s");		//----for mySQL
						
		$failed_captcha = '';
		
		//	has form been submited ( or CAPTCH Reloaded ) ????
		if (isset($_POST['contact_form_submit']))
		{
			//	Validate and process Form for email
			require_once ('contact_form/contact_form_process.php');	
		}
		else { $error = TRUE;}
		
		if ( $error == FALSE OR $failed_captcha != '')
		{

			//	write msg details to db (if no error OR CAPTCHA failed)
		
			//----get message ID ( random n#)
			$msg_id = rand(1,99999999);

			$mysql_err_msg = 'writing recieved contact form data to database';
			
			for ($i = 0; $i	< count ($email_msg_value); $i++ )
			{
				$sql_statement = 'insert 2_contact_form_recieved_data SET'
						
									.'	form_id = "'.$form_info['mod_id'].'"'
									.',	msg_id = "'.$msg_id.'"'							
									.', time_sent = "'.$sql_time.'"'
									.', ip_add = "'.$_SERVER['REMOTE_ADDR'].'"'
									.', failed_captcha = "'.$failed_captcha.'"'	//  new code
									.', label = "'.$email_msg_label[$i].'"'
									.', value = "'.nl2br(htmlspecialchars ($email_msg_value[$i])).'"';

				UpdateDB ($sql_statement, $mysql_err_msg);
									
			}			
			
			// 	if CAPTCHA error (as above):Write Failded CAPTCHA attempt info to db...
			//	if NO CAPTCHA error: send mail and other stuff etc
			if ($failed_captcha == '')
			{	
				//----------Compile email	and Send----------------------------------------
				require_once ('contact_form/contact_form_compile_email.php');
					
					
				//	send auto reply	--------------------------
				if ($form_info['auto_reply'] == 'on' AND $email_from != '') 
				{ 
					$subject = 'Message for '.SITE_NAME.' Website has been received';
					include_once ('contact_form/contact_form_auto_reply.php'); 
				}
				
				//	re-direct to stop user Refreshing page and sending email again
				header('location: '.$_SERVER['PHP_SELF'].'?p='.$page_id.'&msg='.$msg_id); 
				exit();		
			}

				
		} 
		
		
	}


	//-------======================--------Start PAGE content---------==========================---------------------------------------------
		
//	Print Confirm MSG and details	
if (isset($_REQUEST['msg']) AND $_REQUEST['msg'] != '' AND $error == FALSE) 
{
		//	Confirm MSG sen and Readback details
		$return_link = $_SERVER['PHP_SELF'].'?p='.$page_id;
		include_once ('contact_form/contact_form_readback.php');
}
		
else 
{
	

	//-------start FORM-------------------------------------------
	echo TAB_6."\n";
	echo TAB_6.'<!--  Start Customized Contact Form  --> '. "\n";
	echo TAB_6."\n";	

	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{
	
		$hover_class = ' HoverShow';
		$edit_enabled = 0;
		$mod_locked = 0;
		$can_not_clone = 0;
		$CSS_layout = 'tbc';

		//	Display / Hide In-Active Mods
		include ('CMS/cms_inactive_mod_display.php');
		
		//	Do mod editing Toolbar
		include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
		
		//	Do Mod Config Panel
		include ('CMS/cms_panels/cms_panel_mod_config.php');
		
	}

	else
	{$hover_class = '';}
		
	echo TAB_6.'<div class="ContactForm'.$hover_class.'" id="ContactForm_'.$mod_id.'" >'."\n";
	
		if ( $form_info['heading'] != '' AND $form_info['heading'] != NULL )
		{
			echo TAB_7.'<h2 class="ContactForm">'.HiliteText($form_info['heading']).'</h2>'. "\n";
		}
		
		if ($error != FALSE AND !isset($_POST['reload_captcha']))
		{
			//-------------Display Error
			echo TAB_7.'<h3 class="WarningMSG" >'.$form_info['error_msg'].'</h3>' ."\n";
		}
			
		if ( $form_info['text_1'] != '' AND $form_info['text_1'] != NULL )
		{
			echo TAB_7.'<p class="ContactForm">'.HiliteText($form_info['text_1']).'</p>'. "\n";
		}			
				
			echo TAB_7.'<form class="ContactForm" method="post" action="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']
						.'" enctype="multipart/form-data"> '. "\n";
				
				echo TAB_8.'<ul class="ContactForm" > '. "\n";
	
	//	Get Form element details - read from db	----------
		$mysql_err_msg = 'This Contact Form Infomation unavailable';	
		$sql_statement = 'SELECT * FROM mod_contact_form_elements WHERE mod_id = "'.$form_info['mod_id'].'" '
				
																.' AND active = "on" '
																.' ORDER BY seq ';	

		
		$num_elements = mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg));
		$result = ReadDB ($sql_statement, $mysql_err_msg);
	
		$element_count = 1;
		$file_upload_count = 1;

		while ( $form_elements = mysql_fetch_array ($result))
		{ 

		//	need REQUIRD star and set if form has any requird fields
			if ($form_elements['required'] == 'on')
			{
				$required_star = FORM_REQD_FIELD_SYMBOL;
				$reqd_input_box_style_str = 'RequiredFormElement';
				$theres_reqd_fields = TRUE;
			}
		
			else
			{
				$required_star = '&nbsp;';
				$reqd_input_box_style_str = '';
			}
			
			switch ($form_elements['element'])
			{

			//	TEXT  /  EMAIL	----------------------------------------
				case "text": 
				case "email":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";		
												
						echo TAB_10.'<label for="element_'.$element_count.'" >'
							.'<span class="WarningMSG" >'.$required_star.'</span>'
							.HiliteText($form_elements['label']).'</label>'. "\n";
							
						//	Get Value already submitted or default	
						if (isset($_POST['contact_form_submit']) OR isset($_POST['reload_captcha'])) 
						{ $value = $_POST['inputID_'.$form_elements['element_id']]; }
						else { $value = $form_elements['value']; }
						
						if (isset(${"error_msg_element_".$form_elements['element_id']})) 
						{
							$error_msg = ${"error_msg_element_".$form_elements['element_id']};
							$error_class = 'ErrorHilight';
						}
						else 
						{
							$error_msg = '';
							$error_class = '';
						}
						
						echo TAB_10.'<input type="'.$form_elements['type'].'" name="inputID_'.$form_elements['element_id']
							.'" id="element_'.$element_count.'" class="'.$error_class.'"'
							.' size="'.$form_elements['width'].'" '. "\n";
							echo TAB_11.'title="'.$form_elements['title'].'" '. "\n";
							echo TAB_11.'value="'.$value.'" tabindex="'.$element_count.'" '. "\n";
							
							//if (!isset($_POST['contact_form_submit']) AND $form_elements['attrib_1'] != '')
							if ($form_elements['attrib_1'] != '')
							{ 
								echo TAB_11.'onfocus="if(this.value==\''.$form_elements['value'].'\') {this.value = \'\';}"'. "\n";
								echo TAB_11.'onblur="if(this.value==\'\') { this.value=\''.$form_elements['value'].'\'}"';
							}
							
							echo ' />'. "\n";
							
						echo TAB_10.'<span class="WarningMSGSmall" >'.$error_msg.'</span>'. "\n";
						
					echo TAB_9.'</li> '. "\n";
				
				break;
				
			//	FILE UPLOAD	----------------------------------------
				case "file": 
			
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	
						
						echo TAB_10.'<label for="element_'.$element_count.'" >'
							.'<span class="WarningMSG" >'.$required_star.'</span>'
							.HiliteText($form_elements['label']).'</label>'. "\n";	//	<------- can be required ???
						
						if (isset(${"error_msg_element_".$form_elements['element_id']})) 
						{
							$error_msg = ${"error_msg_element_".$form_elements['element_id']};
							$error_class = 'ErrorHilight';
						}
						else 
						{
							$error_msg = '';
							$error_class = '';
						}
						
						echo TAB_10.'<input type="'.$form_elements['type'].'" name="file_'.$file_upload_count.'" id="element_'.$element_count.'" '
							.'size="'.$form_elements['width'].'" class="'.$error_class.'"'. "\n";
						echo TAB_10.'title="'.$form_elements['title'].'" tabindex="'.$element_count.'" />'. "\n";
							
						echo TAB_10.'<span class="WarningMSGSmall" >'.$error_msg.'</span>'. "\n";
							
					echo TAB_9.'</li> '. "\n";
					
					$file_upload_count++;
				
				break;

			//	CHECKBOX  /  RADIO Button	-------------------------------
				case "checkbox":
				case "radio":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" >'. "\n";
						
						echo TAB_10.'<label for="element_'.$element_count.'" >'
							.'<span class="WarningMSG" >'.$required_star.'</span>'
							.HiliteText($form_elements['label']).'</label>'. "\n";
							
						//	Get Value already submitted or default	
						if (isset($_POST['contact_form_submit']) OR isset($_POST['reload_captcha'])) 
						{ 
							if ($_POST['inputID_'.$form_elements['element_id']] != NULL ) { $checked = 'checked="checked"'; } 
							else { $checked = ''; }
						}
						else { $checked = $form_elements['attrib_1']; }							
						
						if (isset(${"error_msg_element_".$form_elements['element_id']})) 
						{
							$error_msg = ${"error_msg_element_".$form_elements['element_id']};
							$error_class = 'ErrorHilight';
						}
						else 
						{
							$error_msg = '';
							$error_class = '';
						}
						
						echo TAB_10.'<input type="'.$form_elements['type'].'" name="inputID_'
							.$form_elements['element_id'].'" id="element_'.$element_count.'" class="'.$error_class.'" '
							.$checked.' size="'.$form_elements['width'].'" '. "\n";
						echo TAB_10.'title="'.$form_elements['title'].'" '
							.'value="'.$form_elements['value'].'" tabindex="'.$element_count.'" />'. "\n";
							
						echo TAB_10.'<span class="WarningMSGSmall" >'.$error_msg.'</span>'. "\n";
						
						//	Send hidden input for required CheckBoxes - since checboxes are not posted if un-checked, send a hidden input to inform of their presence and un-checked state
						if ($form_elements['required'] == 'on')
						{ 
							echo TAB_10.'<input type="hidden" name="ReqdCheckBox_'.$form_elements['element_id'].'" '
								.'value="'.$form_elements['element_id'].'" /> '. "\n"; 
						}
						
					echo TAB_9.'</li> '. "\n";
									
				break;

			//	TEXTAREA		--------------------------
				case "textarea":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	
						
						echo TAB_10.'<label for="element_'.$element_count.'" >'
							.'<span class="WarningMSG" >'.$required_star.'</span>'
							.HiliteText($form_elements['label']).'</label>'. "\n";
											
						//	Get Value already submitted or default	
						if (isset($_POST['contact_form_submit']) OR isset($_POST['reload_captcha'])) 
						{ $value = $_POST['inputID_'.$form_elements['element_id']]; }
						else { $value = $form_elements['value']; }
						
						if (isset(${"error_msg_element_".$form_elements['element_id']})) 
						{
							$error_msg = ${"error_msg_element_".$form_elements['element_id']};
							$error_class = 'ErrorHilight';
						}
						else 
						{
							$error_msg = '';
							$error_class = '';
						}
						
						echo TAB_10.'<textarea name="inputID_'.$form_elements['element_id'].'"'
							.' id="element_'.$element_count.'"  '
							.'cols="'.$form_elements['width'].'" rows="'.$form_elements['height'].'" '. "\n";
							echo TAB_11.'title="'.$form_elements['title'].'" tabindex="'.$element_count.'" '. "\n";							
							//	check for expandible TextArea options:
							if ( $form_elements['attrib_2'] != '' AND $form_elements['attrib_2'] != NULL )
							{ $class = ' resizable'; }
							if ( $form_elements['attrib_3'] != '' AND $form_elements['attrib_3'] != NULL )
							{ echo TAB_11.'onkeyup="AutoResize(this);" onkeydown="AutoResize(this);" '. "\n"; }
							if (
									$form_elements['attrib_4'] != '' AND $form_elements['attrib_4'] != NULL 
								AND $form_elements['attrib_5'] != '' AND $form_elements['attrib_5'] != NULL 
								)
							{
								echo TAB_11.'onclick="this.cols='.$form_elements['attrib_4'].'; this.rows='.$form_elements['attrib_5'].'" '
								.'onblur="this.cols='.$form_elements['width'].';this.rows='.$form_elements['height'].'" '. "\n";
							}
						
							echo TAB_11.'class="ContactForm'.$class.' '.$error_class.'"'. "\n";
					
							if ($form_elements['attrib_1'] != '')
							{ 
								echo TAB_11.'onfocus="if(this.value==\''.$form_elements['value'].'\') {this.value = \'\';}"'. "\n";
								echo TAB_11.'onblur="if(this.value==\'\') { this.value=\''.$form_elements['value'].'\'}"';
							}
							
							echo ' >';
							echo $value.'</textarea>'. "\n";

						echo TAB_10.'<span class="WarningMSGSmall" >'.$error_msg.'</span>'. "\n";	
						
					echo TAB_9.'</li> '. "\n";
			
				break;	

			//	SUBMIT / RESET / IMAGE Button	-------------------------------
				case "submit":
				case "reset":
				case "image":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	
						
						echo TAB_10.'<label for="element_'.$element_count.'" >'
							.HiliteText($form_elements['label']).'</label>'. "\n";
						echo TAB_10.'<input type="'.$form_elements['type'].'" name="contact_form_submit" id="element_'.$element_count.'" '
							.' class="ContactFormSubmit"'
							.$form_elements['attrib_1'].' '.$form_elements['attrib_2'].' size="'.$form_elements['width'].'" '. "\n";
						echo TAB_10.'title="'.$form_elements['title'].'" value="'.$form_elements['value'].'" tabindex="'.$element_count.'" ';
							
							if ((isset($_POST['contact_form_submit']) OR isset($_POST['reload_captcha'])) AND $form_elements['type'] == 'reset') 
							{ echo 'onclick="location.href=&quot;'.$_SERVER['PHP_SELF'].'?p='.$page_id.'&quot;"'; }

						echo ' />'. "\n";
						
					echo TAB_9.'</li> '. "\n";
										
				break;	

	//===========================	MORE INPUT TYPES to be added below .......	=============================================
	
			//	DATE	-------------------------------
				case "date":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	

					echo TAB_9.'</li> '. "\n";
					
				break;		
	
			//	SELECT	-------------------------------
				case "select":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	
						
						echo TAB_10.'<label for="element_'.$element_count.'" >'
							.'<span class="WarningMSG" >'.$required_star.'</span>'
							.HiliteText($form_elements['label']).'</label>'. "\n";						
											
						if (isset(${"error_msg_element_".$form_elements['element_id']})) 
						{
							$error_msg = ${"error_msg_element_".$form_elements['element_id']};
							$error_class = 'ErrorHilight';
						}
						else 
						{
							$error_msg = '';
							$error_class = '';
						}
						
						if ($form_elements['attrib_2'] == 'multiple')
						{
							$multiple = ' multiple="multiple"';
						}
						else
						{
							$multiple = '';
						}

						if ($form_elements['attrib_3'] == 'disabled')
						{
							$disabled = ' disabled="disabled"';
						}
						else
						{
							$disabled = '';
						}
						
						echo TAB_10.'<select name="inputID_'.$form_elements['element_id']
							.'" id="element_'.$element_count.'" class="'.$error_class.'"' . "\n";
							echo TAB_11.' size="'.$form_elements['height'] . '"'. $multiple . $disabled . ' tabindex="'.$element_count.'"' . "\n";
							echo TAB_11.' title="'.$form_elements['title'].'" >'. "\n";
							
///////////////////////////////////////////////////////////////////////////////////////////////////////
						if ($form_elements['type'] == 'mod_contact_form_options')
						{
							//	Get Form Select Options - read from db	----------
							$mysql_err_msg = 'Select Options for Contact Form Infomation unavailable';	
							$sql_statement = 'SELECT value_text, display_text FROM mod_contact_form_options 
							
																					WHERE element_id = "'.$form_elements['element_id'].'" '
									
																					.' AND active = "on" '
																					.' ORDER BY seq ';	

							
							$select_result = ReadDB ($sql_statement, $mysql_err_msg);
							while ( $select_options = mysql_fetch_array ($select_result))
							{ 
								//	Get Value already submitted or default	
								if (isset($_POST['contact_form_submit']) OR isset($_POST['reload_captcha'])) 
								{ $selected_option = $_POST['inputID_'.$form_elements['element_id']]; }
								else 
								{ 
									$selected_option = $form_elements['value']; 
								}

								if ( $select_options['value_text'] == $selected_option )
								{
									$selected = 'selected="selected"';
								}
								else
								{
							
									$selected = '';
								}
								
								echo TAB_11.'<option value="'.$select_options['value_text'].'" '.$selected.'>'.$select_options['display_text']
								.'</option>' . "\n";
							
							}
							
						}
						//	Custom db oprions
						else
						{
						
						}
							
						echo TAB_10.'</select>'. "\n";
							
						echo TAB_10.'<span class="WarningMSGSmall" >'.$error_msg.'</span>'. "\n";						
						
						
					echo TAB_9.'</li> '. "\n";
				
				break;		
	
			//	IMAGE BUTTON	-------------------------------
				case "image":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	
						
						//	incorporate into "SUBMIT / RESET" ???
					echo TAB_9.'</li> '. "\n";
				
				break;			
	
			//	FILE	-------------------------------
				case "file":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	
						
						//	incorporate into "INPUT / EMAIL" ???
					echo TAB_9.'</li> '. "\n";
				
				break;
	
			//	CAPTCHA	-------------------------------
				case "captcha":
				
					$captcha_id = $form_elements['attrib_1'];
					
					//	read from db	----------
					$mysql_err_msg = 'This captcha info unavailable';	
					$sql_statement = 'SELECT * FROM mod_captcha WHERE captcha_id = "'.$captcha_id.'" ';	
					
					$captcha_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));					
					
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	
					
						echo TAB_10.'<div class="CaptchaDivContainer" > '. "\n";
							
							//	Input Code
							echo TAB_11.'<div class="CaptchaDivInput" > '. "\n";
								echo TAB_12.'<label for="element_'.$element_count.'" >'
									.'<span class="WarningMSG" >'.$required_star.'</span>'
									.HiliteText($form_elements['label']).'</label>'. "\n";
									
								if (isset(${"error_msg_element_".$form_elements['element_id']})) 
								{
									$error_msg = ${"error_msg_element_".$form_elements['element_id']};
									$error_class = 'ErrorHilight';
								}
								else 
								{
									$error_msg = '';
									$error_class = '';
								}	
									
								if(!$form_elements['width'] OR $form_elements['width'] == 0)
								{
									$input_width = $captcha_info['num_chrs'] + 1;						
								}
								else
								{
									$input_width = $form_elements['width'];
								}
									
								echo TAB_12.'<input type="'.$form_elements['type'].'" name="inputID_'
									.$form_elements['element_id'].'" id="element_'.$element_count.'" class="CaptchaInput '.$error_class.'"'
									.' size="'.$input_width.'" tabindex="'.$element_count.'" '. "\n";							
								echo TAB_12.'title="'.$form_elements['title'].'" maxlength="'.$captcha_info['num_chrs']
								.'" autocomplete="off" />'. "\n";
								
							echo TAB_11.'</div> '. "\n";
							
							//	Do CAPTCHA IMAGE and associated links
							echo TAB_11.'<div class="CaptchaDivImage" > '. "\n";
	
								
								
								include ('captcha/captcha.php');
		
							echo TAB_11.'</div> '. "\n";
							
							
							//	Error MSG
							echo TAB_11.'<div class="CaptchaDivErrorMSG" > '. "\n";								
								echo TAB_12.'<span class="WarningMSGSmall" >'.$error_msg.'</span>'. "\n";
							echo TAB_11.'</div> '. "\n";
							
						echo TAB_10.'</div> '. "\n";
						
					echo TAB_9.'</li> '. "\n";
				
				break;
				
			//	HIDDEN	-------------------------------
				case "hidden":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	

					echo TAB_9.'</li> '. "\n";
				
				break;
				
			//	STATIC ELEMENT TYPES ( non INPUT )	----------------------------------
			
			//	STATIC Text	-----------------------------------------
				case "static":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	
						echo TAB_10.'<p class="ContactForm" id="element_'.$element_count.'" title="'.$form_elements['title'].'" >'. "\n";
							echo TAB_11.HiliteText($form_elements['label']). "\n";
						echo TAB_10.'</p>'. "\n";
					echo TAB_9.'</li> '. "\n";
					
				break;


	
			//	FIELDSET <start tag>	------------------------------------		
				case "fieldset_open":
														
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	
						
						if ( $form_elements['width'] != "" AND $form_elements['width'] != 0 )
						{ $style_str_width = 'width: '.$form_elements['width'].'px;';}
						else { $style_str_width = '';}
						if ( $form_elements['height'] != "" AND $form_elements['height'] != 0 )
						{ $style_str_height = ' height: '.$form_elements['height'].'px;';}
						else { $style_str_height = '';}
						
						echo TAB_10.'<fieldset id="element_'.$element_count.'" style="'.$style_str_width.$style_str_height.'" >'. "\n";
							echo TAB_11.'<legend>'.HiliteText($form_elements['label']).'</legend>'. "\n";
							echo TAB_11.'<ul class="ContactForm" > '. "\n";
							
							echo TAB_11."\n";
							echo TAB_11.'<!--  Start - user inserted fieldset  --> '. "\n";
							echo TAB_11."\n";	
							
				break;

			//	FIELDSET <close tag>	------------------------------------	
				case "fieldset_close":
				
							echo TAB_11."\n";
							echo TAB_11.'<!--  End - user inserted fieldset  --> '. "\n";
							echo TAB_11."\n";	
							
							echo TAB_11.'</ul> '. "\n";
						echo TAB_10.'</fieldset>'. "\n";
					echo TAB_9.'</li> '. "\n";
					
				//	need to close and re-open <ul> tag if not the last form element
				if ($element_count != $num_elements)
				{
					echo TAB_8.'</ul> '. "\n";
					echo TAB_8.'<ul class="ContactForm" > '. "\n";			
				}


				break;
				
			//	<HR> <start tag>	------------------------------------		
				case "hr":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	;
						echo TAB_10.'<hr id="element_'.$element_count.'" /> '. "\n";
					echo TAB_9.'</li> '. "\n";
					
				break;				
	
			//	LINK		------------------------------------		
				case "link":
				
					echo TAB_9.'<li class="'.$reqd_input_box_style_str.'" ' 
						.'id="ContactFormElement_'.$form_info['mod_id'].'_'.$form_elements['element_id'].'" > '. "\n";	
					
						if ( $form_elements['attrib_1'] == '' OR $form_elements['attrib_1'] == NULL ) 
						{ $target = ''; }
						else { $target = 'rel="external"'; }
						
						echo TAB_10.'<a id="element_'.$element_count.'" href="'.$form_elements['value'].'" '.$target
							.' title="'.$form_elements['title'].'" tabindex="'.$element_count.'" > '. "\n";
						
						if ($form_elements['attrib_2'] != '' AND $form_elements['attrib_2'] != NULL )
						{
							echo TAB_11.'<img class="ContactFormImageLink" '
								.'src="/_images_user/'.$form_elements['attrib_2'].'" alt="'.$form_elements['attrib_3'].'" '
								.'width="'.$form_elements['width'].'" height="'.$form_elements['height'].'" /> '. "\n";
						}
						else { echo HiliteText($form_elements['label']); }
						
						echo TAB_10.'</a> '. "\n";						
					echo TAB_9.'</li> '. "\n";
					
				break;	
			}

				
			$element_count++;
			
		} 


			//	Do Required Field notice	-----------------
			if ( $theres_reqd_fields == TRUE AND $form_info['show_reqd_field_label'] == 'on')
			{			
				echo TAB_9.'<li class="RequiredFormElement" > '. "\n";
					echo TAB_10.'<p><span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Required entry</p> '. "\n";	
				echo TAB_9.'</li> '. "\n";
			}				

					
			echo TAB_8.'</ul> '. "\n";
				
		echo TAB_7.'</form> '. "\n";	
				
			
		if ( $form_info['text_2'] != '' AND $form_info['text_2'] != NULL )
		{
			echo TAB_7.'<p class="ContactForm">'.HiliteText($form_info['text_2']).'</p>'. "\n";
		}	
			
	echo TAB_6.'</div>'."\n";
	
	
	echo TAB_6."\n";
	echo TAB_6.'<!--  End Customized Contact Form  --> '. "\n";
	echo TAB_6."\n";	
							
}	//------end Form----------------------------------------------	

?>