<?php

	// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
		
	// 	Generate a boundary string
	$rand = md5(time());
	$mime_boundary = '_==_'.$rand;
		
	$headers .=     'MIME-Version: 1.0' . PHP_EOL;
	
	$header_text =   'Content-Type: text/plain; charset="ISO-8859-1"' . PHP_EOL 
					.'Content-Transfer-Encoding: 8bit' 				. PHP_EOL 
					;
	
	$header_html =   'Content-type: text/html; charset="ISO-8859-1"' . PHP_EOL
					.'Content-Transfer-Encoding: QUOTED-PRINTABLE' . PHP_EOL 
					;	
					
	$header_image =  'Content-type: image/jpeg; name="site_logo.jpg"' . PHP_EOL
					.'Content-Transfer-Encoding: base64' . PHP_EOL
					.'Content-ID:'.$image_cid . PHP_EOL
					.'Content-Disposition: inline; filename="site_logo.jpg"' . PHP_EOL
					;
	
	$header_multi_alt =  'Content-Type: multipart/alternative;' . PHP_EOL
							.'     boundary="P2' . $mime_boundary . '"'
							;
						 
	if (SHOP_ORDERS_EMAIL_AS_HTML == 1)
	{
		$notice_text = 'This is a multi-part message in MIME format.';
				
		$headers  .= 'Content-Type: multipart/related;' . PHP_EOL;
		$headers  .= '     boundary="P1' . $mime_boundary . '"'
		;

		//	image file embedded
		if (SHOP_ORDERS_EMAIL_IMAGE_SOURCE == 'embedded' AND file_exists($image_file_path . '/' . SHOP_ORDERS_EMAIL_IMAGE))
		{
			$image_file_embedded = chunk_split(base64_encode(file_get_contents($image_file_path . SHOP_ORDERS_EMAIL_IMAGE))); 		
		}

		
		//	compile message
		$body  = $notice_text . PHP_EOL;

		$body .= PHP_EOL;

		$body .= '--P1'.$mime_boundary . PHP_EOL;		
		$body .= $header_multi_alt . PHP_EOL;		
				
		$body .= PHP_EOL;
		
		$body .= '--P2'.$mime_boundary . PHP_EOL;
			$body .= $header_text . PHP_EOL;

			$body .= PHP_EOL;
		
			$body .= $plain_text . PHP_EOL . PHP_EOL;

			$body .= PHP_EOL;

		$body .= '--P2'.$mime_boundary . PHP_EOL;	
			$body .= $header_html . PHP_EOL;
			
			$body .= PHP_EOL;
			
			$body .= $html_text . PHP_EOL;

			$body .= PHP_EOL;

		$body .= '--P2' . $mime_boundary . '--' . PHP_EOL;
		
		//	image file embedded
		if (SHOP_ORDERS_EMAIL_IMAGE_SOURCE == 'embedded' AND file_exists($image_file_path . '/' . SHOP_ORDERS_EMAIL_IMAGE))
		{		
			$body .= '--P1' . $mime_boundary . PHP_EOL;
				$body .= $header_image . PHP_EOL;
				
				$body .= PHP_EOL;

				$body .= $image_file_embedded . PHP_EOL;
		}
						
		$body .= '--P1' . $mime_boundary . '--' . PHP_EOL;	
		
	}
	else
	{
		$headers .= $header_text;
		
		$body = $plain_text;
	}
	
//echo $body;		
	//	compile and send email
	if (!mail ( $to, $subject, $body ,$headers, $ext_par))
	{
		// Log error - send email
	}


?>