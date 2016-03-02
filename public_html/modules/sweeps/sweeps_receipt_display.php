<?php

	//	IS this in PRINT or View on site Mode ?

	if (isset($_GET['printinv']) AND $_GET['printinv'] != '')
	{
		$hash_inv_num = $_GET['printinv'];		
		
		//--------Set key to start include files
		define( 'SITE_KEY', 1 );

		$file_path_offset = '../../';
		
		//----Get Common code to all pages
		require_once ($file_path_offset.'includes/common.php');
		require_once ($file_path_offset.'includes/access.php');	
		
		require_once (CODE_NAME.'_shop_configs.php');
		//require_once ('sweeps_php_functions.php');	
		
		//	CSS Style Sheet file
		if ( isset ($_SESSION['user_theme_set']) AND USER_SELECTS_THEME == 'on') { $site_theme_id = $_SESSION['user_theme_set']; }
		else { $site_theme_id = SITE_THEME_ID; }
		
		$mysql_err_msg = 'unable to fetch the Sites Theme data';		
		$sql_statement =  'SELECT dir_name FROM themes WHERE theme_id = "'.$site_theme_id.'"';

		$theme_data = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	
		$site_theme_dir = $theme_data['dir_name'];
		
		//	DocType etc code...
		echo DOCTYPE_TAG_CODE . "\n\n";
		echo TAB_4 . '<!-- ' . HTML_CODE_COMMENT . ' -->' . "\n\n";
		echo HTML_OPEN_TAG_CODE . "\n\n";	
		echo '<head>'."\n\n";
		echo TAB_1 . META_HTTP_EQUIV_TAG_CODE . "\n";
		echo TAB_1.'<link type="text/css" rel="stylesheet" href="/_themes/'.$theme_data['dir_name'].'/_css.css"  media="print" />'." \n";
		echo TAB_1.'<title>Invoice from: '.SITE_NAME.'</title>'." \n";
		echo '</head>'." \n";
		echo '<body onload="window.print();" >'." \n\n";
		
		echo TAB_1.'<img src="/_images_user/site_logo.jpg" alt="the '.SITE_NAME.' logo" />'." \n";
		echo "\n";
	}

	elseif (isset($_GET['invoice']) AND $_GET['invoice'] != '')
	{
		$hash_inv_num = $_GET['invoice'];
		
		echo TAB_9.'<h1 class="ShopCheckOut" >'.SHOP_HEADING_PAYMENT_CONFIRMED.'</h1>'."\n";				
		echo TAB_9.'<p class="ShopCheckOut" >Please find the details of your purchase below:</p>'."\n";	
	
	}

	else
	{
		echo '<p class="WarningMSG" >Error! - no invoice information found...</p>'."\n";
	}

	// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	
	//	read from db to get the invoice details
	$mysql_err_msg = 'Invoice Order information unavailable';	
	$sql_statement = 'SELECT * FROM sweeps_orders WHERE hash_inv_num = "'.$hash_inv_num.'"';

	$invoice_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
	if(!mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg)))
	{
		echo TAB_9.'<p class="WarningMSG" >Error! - no invoice information found...</p>'."\n";
	
	}
	else
	{

		//	Display the Invoice
		echo htmlspecialchars_decode($invoice_info['invoice_html']);
		

		if (!isset($_GET['printinv']))
		{			
			echo TAB_10.'<div class="ShopPrintReceiptButton" >' ."\n";
				
				//	Print Receipt button
				echo TAB_11.'<a class="ShopButton" href="/modules/sweeps/sweeps_receipt_display.php?printinv='.$hash_inv_num.'"' ."\n";	
					echo TAB_12.' title="Click here Print Receipt" >Print Receipt' ."\n";
				echo TAB_11.'</a>' ."\n";
				
				//	Return to the shop home page button
				echo TAB_11.'<a class="ShopButton" href="http://'.$_SERVER['SERVER_NAME'].'/index.php?p='.SHOP_PAGE_ID.'"' ."\n";	
					echo TAB_12.' title="Click here to return to the '.SHOP_ITEM_ALIAS.'s page" >Return to '.SHOP_ITEM_ALIAS.'s' ."\n";
				echo TAB_11.'</a>' ."\n";				
			echo TAB_10.'</div>' ."\n";	
		}	

		else
		{
			echo "\n";
			echo '</body>' . "\n";
			echo '</html>' . "\n";			
		}
	
	
	}


?>