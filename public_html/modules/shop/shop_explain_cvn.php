<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	
$file_path_offset = '../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');

	require_once ($file_path_offset.'includes/access.php');
	
//---------Get Head:
	include_once ($file_path_offset.'includes/head.php'); 
	
	echo "\n";

//------ -- START BODY-------------------------------------------------------------------------------------------------

	echo '<body class="MainSite" >'." \n"; 	
	echo "\n";
	
		//--------START BOUNDARY-----------------------------------------------------------------
	
		echo TAB_1.'<div class="NewWindow" >'." \n";		
		echo "\n";
		
			echo TAB_2.'<div class="NewWinText" >'." \n";	

				echo TAB_3.'<h1>What is a CVN number?</h1>' ."\n";
				echo TAB_3.'<p>The CVN, sometimes referred to as the CCV, is a security number or code which is printed on your credit card.'
							.' The CVN provides an additional level of security which ensures that you'
							.' physically have the card in your possession. ' ."\n";

				echo TAB_3.'<p>VISA and Mastercard credit cards have the CVN printed on the back of the card in the signature box.'
							.' It&#39;s the 3 digit number printed to the right of the credit card number.'
							.' Please refer to the image below.</p>' ."\n";

				echo TAB_3.'<img src="/images_misc/what_is_CVN.gif" />' ."\n";
				
				echo TAB_3.'<noscript>' ."\n";
					echo TAB_4.'<p><a href="/index.php?p='.SHOP_PAGE_ID.'&amp;view=checkout" ><< Go Back</a></p>' ."\n";
				echo TAB_3.'</noscript>' ."\n";
				
			echo TAB_2.'</div>' ."\n";
					
		echo TAB_1.'</div>'."\n";	//---------end Container
		echo "\n";
							
	echo '</body>'."\n";

echo '</html>'."\n";