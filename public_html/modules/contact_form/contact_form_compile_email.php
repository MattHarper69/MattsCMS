<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	$fileatt      = '';
	$fileatt_type = '';
	$fileatt_name = '';		
	
	if (isset($_FILES['fileatt']))
	{
		// 	Obtain file upload vars
		$fileatt      = $_FILES['fileatt']['tmp_name'];
		$fileatt_type = $_FILES['fileatt']['type'];
		$fileatt_name = $_FILES['fileatt']['name'];	
	}

		
	$message = '';	
	for ($i = 0; $i	< count ($email_msg_value); $i++ )
	{
		// 	replace new lines with a space - prevents a user from adding headers:
		$email_msg_value[$i] = preg_replace('/[\r|\n]+/', "<br />", $email_msg_value[$i]);
		
		
 		$message .=  TAB_4.'<li> '. "\n"
						.TAB_5.'<strong>'.$email_msg_label[$i].' </strong>'.TAB_2.'<em>'.nl2br($email_msg_value[$i]).' </em> '. "\n"
					.TAB_4.'</li> '. "\n"
					;
	}
	
	if ($email_from == '') 
	{ 
		$from = SITE_NAME.' Website';
		$subject_line_email_str = '';
		$email_link_str = '<p><em><strong>Email address not supplied</em></p>';
	}
	else
	{ 	
		$from = $email_from;
		$subject_line_email_str = ' - from: '.$email_from;
		$email_link_str = '<p><strong>User&#39;s Email address: </strong>'."\t".'<a href="mailto:'.$email_from.'" >'.$email_from.'</a></p>';		
	}

	//	For FILE ATTACHMENT only	----------------------------------------------------------------
	if ( $file_upload = TRUE AND is_uploaded_file($fileatt)) 
	{
		//	Read the file to be attached ('rb' = read binary)
		$file = fopen($fileatt,'rb');
		$data = fread($file,filesize($fileatt));
		fclose($file);	
				
		// 	Generate a boundary string
		$rand = md5(time());
		$mime_boundary = '==Multipart_Boundary_x{'.$rand.'}x';
		  
		// 	Add the headers for a file attachment
		$headers 	.= "\n".'MIME-Version: 1.0'."\n"
					.'Content-Type: multipart/mixed;'."\n"
					.' boundary="{'.$mime_boundary.'}"'."\n"
					;
	}

	else
	{			
		//	headers
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		//$headers .= 'From: ' .$from . "\r\n";				
	}				
		
		$headers .= 'From: '.$from . " \r\n";				
					

		//	to:	--------------------------------
		$to = $email_to;
						
		//	Sybject:	--------------if $subject value has been sent from email_form.php, than use it - otherwize create here
		if ( $subject == "" )
		{ $subject = 'Message for '.SITE_NAME.' Website'.$subject_line_email_str; }
				
		//	Message:	-------------------------
		$message = 	TAB_1.'<html>' ."\n"
						.TAB_2.'<head>' ."\n"
							.TAB_3.'<title>Message email from: '.SITE_NAME.' Website</title>' ."\n"
						.TAB_2.'</head>' ."\n"
						.TAB_2.'<body>' ."\n"									
							.TAB_3.'<h3>A Message from: '.SITE_NAME.' Website was received...</h3>' ."\n"
							.TAB_3.'<p>'.$form_info['send_email_subject'].'</p>'."\n"
							.TAB_3.'<p>Time of Message:'.TAB_2.$time_sent.'</p>' ."\n\n"
							.TAB_3.'<p> -- Start of Message -- </p>' ."\n"
							.TAB_3.'<ul>' ."\n"
							.TAB_3.$message
							.TAB_3.'</ul>' ."\n"
							.TAB_3.'<p> -- End of Message -- </p>' ."\n"
							.TAB_3.$email_link_str."\n"							
							.TAB_3.'<p><strong>User&#39;s IP address: </strong>'.TAB_2.$_SERVER['REMOTE_ADDR'].'</p>' ."\n"
							.TAB_3.'<p><strong>Message ID N#: </strong>'.TAB_2.$msg_id.'</p>' ."\n"							
							
						.TAB_2.'</body>' ."\n"
					.TAB_1.'</html>' ."\n"
					;	

	if ( $file_upload = TRUE AND is_uploaded_file($fileatt)) 
	{
		  // 	Add a multipart boundary above the plain message	(For FILE ATTACHMENT)
		  $message =  'This is a multi-part message in MIME format' ."\n\n"
		             .'--{'.$mime_boundary.'}' ."\n"
		             .'Content-Type: text/plain; charset="iso-8859-1"' ."\n"
		             .'Content-Transfer-Encoding: 7bit' ."\n\n"
					 
		             .$message . "\n\n";
					 
		  $message .=  '--{'.$mime_boundary.'}' ."\n"
		              .'Content-Type: {'.$fileatt_type.'};' ."\n"
		              .' name="{'.$fileatt_name.'}' ."\n"
		              .'Content-Disposition: attachment;' ."\n"
		              .' filename="{'.$fileatt_name.'}"' ."\n"
		              .'Content-Transfer-Encoding: base64' ."\n\n"
					  
		              .$data . "\n\n"
					  
		              .'--{'.$mime_boundary.'}--' ."\n"
					  ;
	}
				  
	//	wrap msg	---------------------------
	$message = wordwrap($message, 70);
	
	//	compile and send email	---------------------------
	mail ( $to, $subject, $message ,$headers);
	
?>