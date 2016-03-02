<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	
	$file_path_offset = '../';
	
//----Get Common code to all pages

	require_once ( $file_path_offset.'includes/common.php');
	require_once ( CODE_NAME.'_account_configs.php');
	require_once ( CODE_NAME.'_shop_configs.php');
	
//--------Redirect to Shutdown page if site is shutdown
	if (SITE_SHUTDOWN == 1)
	{
		header("location: ../shutdown.php"); 
		exit();
	}	

	$mysql_err_msg = 'Invoice Information unavailable';	
	$sql_statement = 'SELECT * FROM mod_accounts_invoice WHERE unique_num = "'.$_REQUEST['invid'].'" AND unique_num != 0';	//	do not select where
																															//	unique_num is not set	
	$invoice_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

	if ( $invoice_info == '' )
	{
		header("location: ../"); 
		exit();
	}

	//	declar vars
	$invoice_items_code = '';
	$paypal_form_items = '';
	$css_code = '';
	$view_in_browser_code = '';
	$total_owing = 0;
	
	if (INVOICE_PAYPAL_ADD_FEE_PERC)
	{
		$paypal_add_fee_str = ' ( * '.INVOICE_PAYPAL_ADD_FEE_PERC.'% fee will apply ) ';
	}
	else
	{
		$paypal_add_fee_str = '';
	}
	
	if (isset($_REQUEST['email']))
	{

		//	Do Paypal LINK code
		$paypal_code_msg = 
						TAB_7.'<strong>PayPal:</strong> <a href="http://'.SITE_URL.'/invoices/?invid='.$invoice_info['unique_num'].'">' ."\n"
							.TAB_8.'Please follow this link to pay by PayPal '.$paypal_add_fee_str.'&gt;&gt; '
									.'http://'.SITE_URL.'/invoices/?invid='.$invoice_info['unique_num'] ."\n"
						.TAB_7.'</a>' ."\n"
						;
		
		//	code to view in browser
		$view_in_browser_code = TAB_3.'<div class="ViewInBrowserLink" >' ."\n"									
									.TAB_4.'<p>If you are having trouble viewing this invoice, please '										
										.TAB_5.'<a href="http://'.SITE_URL.'/invoices/?invid='.$invoice_info['unique_num'].'">'
									.TAB_4.'click here</a> to view it in your Web-Browser</p>'."\n"
									.TAB_4.'<p>Or go to: <a href="http://'.SITE_URL.'/payment">'.SITE_URL.'/payment</a>'
										.TAB_5.' and enter Your Customer and Invoice numbers.</p>' ."\n"
								.TAB_3.'</div>' ."\n"	
								;
	}

	//	user requests to view invoice and paypal button
	else
	{
		$paypal_code_msg = TAB_7.'Click the &#39;Pay Now&#39; Button above to pay with PayPal '.$paypal_add_fee_str ."\n";
			
	}
	
	//	include the CSS file and get contents
	$css_code_array = file( $file_path_offset.'_themes/'.INVOICE_CSS_FILE);
	
	foreach ($css_code_array as $css_code_line)
	{
		$css_code .= "\t".$css_code_line;
	}

	$amount_payed = $invoice_info['amount_payed'];
	$item_num = 0;
	$line_total = 0;
	$disc_total = 0;
	$sub_total = 0;
	$grand_total = 0;
	$tax = 0;
	$amount_owing = 0;
	
	//	compile Itemized list for Invoice
	$mysql_err_msg = 'Invoice Item Information unavailable';	
	$sql_statement = 'SELECT * FROM mod_invoice_items WHERE invoice_id = "'.$invoice_info['invoice_id'].'" ORDER BY seq';	
	
	$result = ReadDB ($sql_statement, $mysql_err_msg);
	
	while ($invoice_item_info = mysql_fetch_array ($result))
	{
		$line_total = $invoice_item_info['unit_price'] * $invoice_item_info['quantity'] - $invoice_item_info['discount'];
		$disc_total = $disc_total + $invoice_item_info['discount'];
		$sub_total = $sub_total + $line_total;
		$tax = $sub_total * $invoice_info['sales_tax_perc'] / 100;
		$amount_owing = $tax + $sub_total - $amount_payed;
		
		$invoice_items_code .=	

								TAB_7.'<tr class="InvoiceTableItemRow" >' ."\n"
									.TAB_8.'<td class="InvoiceTableItemQty" >' ."\n"
										.TAB_9.$invoice_item_info['quantity'] ."\n"
									.TAB_8.'</td>' ."\n"
									.TAB_8.'<td class="InvoiceTableItemCode" >' ."\n"
										.TAB_9.$invoice_item_info['item_code'] ."\n"
									.TAB_8.'</td>' ."\n"
									.TAB_8.'<td class="InvoiceTableItemDesc" >' ."\n"
										.TAB_9.$invoice_item_info['desc'] ."\n"
									.TAB_8.'</td>' ."\n"
									.TAB_8.'<td class="InvoiceTableItemPrice" >' ."\n"
										.TAB_9.number_format($invoice_item_info['unit_price'], 2) ."\n"
									.TAB_8.'</td>' ."\n"
									.TAB_8.'<td class="InvoiceTableItemDiscount" >' ."\n"
										.TAB_9.'-'.number_format($invoice_item_info['discount'], 2) ."\n"
									.TAB_8.'</td>' ."\n"	
									.TAB_8.'<td class="InvoiceTableItemLineTotal" >' ."\n"
										.TAB_9.number_format( $line_total, 2) ."\n"
									.TAB_8.'</td>' ."\n"										
								.TAB_7.'</tr>' ."\n"
								;
		
		if (!isset($_REQUEST['email']))
		{								
			$item_num++;
			$net_unit_price = $invoice_item_info['unit_price'] - $invoice_item_info['discount'];
			$paypal_form_items .= 
						
						 TAB_10.'<input type="hidden" name="item_name_'.$item_num.'" value="'.$invoice_item_info['desc'].'" />' ."\n"
						.TAB_10.'<input type="hidden" name="quantity_'.$item_num.'" value="'.$invoice_item_info['quantity'].'" />' ."\n"
						.TAB_10.'<input type="hidden" name="amount_'.$item_num.'" value="'.$net_unit_price.'" />' ."\n"
						;				
		}
	}
	

	
	
	
		$invoice_items_code .=	
								TAB_7.'<tr class="InvoiceTableSpacer" >' ."\n"
									.TAB_8.'<td colspan="6" > </td>' ."\n"
								.TAB_7.'</tr>' ."\n"
								.TAB_7.'<tr>' ."\n"
									.TAB_8.'<td colspan="4" align="right" >TOTAL DISCOUNT:</td>' ."\n"
									.TAB_8.'<td class="InvoiceTableTotalDiscount" >' ."\n"
										.TAB_9.'-'.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($disc_total, 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n"
									.TAB_8.'</td>' ."\n"
									.TAB_8.'<td class="InvoiceTableTotalBlank" ></td>' ."\n"
								.TAB_7.'</tr>' ."\n"
								.TAB_7.'<tr>' ."\n"
									.TAB_8.'<td colspan="5" align="right" >SUBTOTAL:</td>' ."\n"
									.TAB_8.'<td class="InvoiceTableTotalSub" >' ."\n"
										.TAB_9.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($sub_total, 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n"
									.TAB_8.'</td>' ."\n"
								.TAB_7.'</tr>' ."\n"
								.TAB_7.'<tr>' ."\n"
									.TAB_8.'<td colspan="5" align="right" >'.$invoice_info['sales_tax_label'].':</td>' ."\n"
									.TAB_8.'<td class="InvoiceTableTotalTax" >' ."\n"
										.TAB_9.SHOP_CURRENCY_SYMBOL_PREFIX.number_format( $tax, 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n"
									.TAB_8.'</td>' ."\n"
								.TAB_7.'</tr>' ."\n"
								.TAB_7.'<tr>' ."\n"
								;
								
	
	//	Do PayPal fee calc.
	$paypal_fee_amount = round( INVOICE_PAYPAL_ADD_FEE_PERC / 100 * ($tax + $sub_total) , 2 );
	
	//	Has Customer made partial Or Full Payment ??
	if ($amount_payed > 0)
	{
		$amount_owing = $tax + $sub_total - $amount_payed;
		
		//	PayPal Item info - "Amount owing"
		$paypal_form_items =
								 TAB_10.'<input type="hidden" name="item_name_1" value="Amount Owing for this Order" />' ."\n"
								.TAB_10.'<input type="hidden" name="quantity_1" value="1" />' ."\n"
								.TAB_10.'<input type="hidden" name="amount_1" value="'.$amount_owing.'" />' ."\n"
								.TAB_10.'<input type="hidden" name="item_name_'.$item_num.'" value="PayPal Fee ( '.INVOICE_PAYPAL_ADD_FEE_PERC.'% )" />' ."\n"
								.TAB_10.'<input type="hidden" name="quantity_'.$item_num.'" value="1" />' ."\n"
								.TAB_10.'<input type="hidden" name="amount_'.$item_num.'" value="'.$paypal_fee_amount.'" />' ."\n"
								;

								
								
	}
								
								
	if (isset($_REQUEST['email']) OR $invoice_info['display_paypal_link'] != 'on' OR $amount_owing == 0 ) 
	{
		$invoice_items_code .=	TAB_8.'<td colspan="4" ></td>' ."\n";
	}
	else
	{
		//	PayPal Form
		$item_num++;
		$invoice_items_code .=	TAB_8.'<td colspan="4" align="center" >' ."\n"
						
									.TAB_9.'<form name="paypal" action="'.SHOP_PAYPAL_SITE_URL.'" method="post">' ."\n"
									
										.TAB_10.'<input type="hidden" name="cmd" value="_cart" />' ."\n"
										.TAB_10.'<input type="hidden" name="upload" value="1" />' ."\n"
										.TAB_10.'<input type="hidden" name="business" value="'.SHOP_PAYPAL_EMAIL.'" />' ."\n"
										
										//.TAB_10.'<input type="hidden" name="shipping_1" value="'.$_SESSION['postage_to_pay'].'" />' ."\n"
										.TAB_10.'<input type="hidden" name="currency_code" value="'.SHOP_CURRENCY_ISO3.'" />' ."\n"
										.TAB_10.'<input type="hidden" name="return" value="http://'.SITE_URL.'" />' ."\n"		
										.TAB_10.'<input type="hidden" name="cancel_return"'
												.' value="http://'.SITE_URL.'/invoices/?invid='.$invoice_info['unique_num'].'" />'."\n"
										.TAB_10.'<input type="hidden" name="rm" value="2" />' ."\n"
										.TAB_10.'<input type="hidden" name="cbt" value="'.SITE_NAME.' Website" />' ."\n"
										.TAB_10.'<input type="hidden" name="invoice" value="'.$invoice_info['invoice_id'].'" />' ."\n"
											
										.$paypal_form_items
										;
		
		if(INVOICE_PAYPAL_ADD_FEE_PERC AND !($amount_payed > 0))
		{
			
			$invoice_items_code .= 
					
					 TAB_10.'<input type="hidden" name="item_name_'.$item_num.'" value="PayPal Fee ( '.INVOICE_PAYPAL_ADD_FEE_PERC.'% )" />' ."\n"
					.TAB_10.'<input type="hidden" name="quantity_'.$item_num.'" value="1" />' ."\n"
					.TAB_10.'<input type="hidden" name="amount_'.$item_num.'" value="'.$paypal_fee_amount.'" />' ."\n"
					;			
		}
		
	
		if ($tax)	
		{
										//	Add TAX
				$invoice_items_code .=	 TAB_10.'<input type="hidden" name="item_name_'.$item_num.'" value="'.INVOICE_SALES_TAX_LABEL.'" />' ."\n"
										.TAB_10.'<input type="hidden" name="quantity_'.$item_num.'" value="1" />' ."\n"
										.TAB_10.'<input type="hidden" name="amount_'.$item_num.'" value="'.$tax.'" />' ."\n"
										;			
		}
				
										
										
				$invoice_items_code .=	 TAB_10.'<!-- customize paypay site with pretty banners and color -->' ."\n"
										.TAB_10.'<input type="hidden" name="cpp_header_image" value="'.SHOP_PAYPAL_BANNER.'" />' ."\n"
										.TAB_10.'<input type="hidden" name="cpp_headerback_color" value="'.SHOP_PAYPAL_HEADER_BG_COLOUR.'" />' ."\n"
										.TAB_10.'<input type="hidden" name="cpp_headerborder_color" value="'.SHOP_PAYPAL_HEADER_BD_COLOUR.'" />'."\n"
										.TAB_10.'<input type="hidden" name="cpp_payflow_color" value="'.SHOP_PAYPAL_HEADER_BG_COLOUR.'" />' ."\n"
										.TAB_10.'<input type="image" name="submit" src="/_images_user/'.SHOP_PAYNOW_BUTTON.'"'
												.' alt="Pay with PayPal" />' ."\n"
												
										.TAB_10.'<br/>'.$paypal_add_fee_str ."\n"
												
									.TAB_9.'</form>' ."\n"				
								.TAB_8.'</td>' ."\n"
								;
	}
	
		$total_owing = SHOP_CURRENCY_SYMBOL_PREFIX.number_format( $tax + $sub_total, 2).SHOP_CURRENCY_SYMBOL_SUFFIX;
		
		$invoice_items_code .=	 TAB_8.'<td align="right" >TOTAL:</td>' ."\n"
								.TAB_8.'<td class="InvoiceTableTotalGrand" >' ."\n"
									.TAB_9.$total_owing."\n"
								.TAB_8.'</td>' ."\n"
							.TAB_7.'</tr>' ."\n"								
							;	

	//	Has Customer made partial Or Full Payment ??
	if ($amount_payed > 0 )
	{
		$invoice_items_code .=	 TAB_7.'<tr>' ."\n"
									.TAB_8.'<td align="right" colspan="5" >AMOUNT RECEIVED:</td>' ."\n"
									.TAB_8.'<td class="InvoiceTableTotalSub" >' ."\n"
										.TAB_9.SHOP_CURRENCY_SYMBOL_PREFIX.number_format( $amount_payed, 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n"
									.TAB_8.'</td>' ."\n"
								.TAB_7.'</tr>' ."\n"
								.TAB_7.'<tr>' ."\n"
									.TAB_8.'<td align="right" colspan="5" >TOTAL OWING:</td>' ."\n"
									.TAB_8.'<td class="InvoiceTableTotalOwing" >' ."\n"
										.TAB_9.SHOP_CURRENCY_SYMBOL_PREFIX.number_format( $amount_owing, 2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n"
									.TAB_8.'</td>' ."\n"
								.TAB_7.'</tr>' ."\n"									
								;
								$total_owing = SHOP_CURRENCY_SYMBOL_PREFIX.number_format( $amount_owing, 2).SHOP_CURRENCY_SYMBOL_SUFFIX;
	}

	
	//	compile invoice
	$invoice_code =  TAB_1.'<!DOCTYPE HTML>' ."\n"
					.TAB_1.'<html lang="en-US" >' ."\n"
						.TAB_2.'<head>' ."\n"
							.TAB_3.'<meta charset="UTF-8" />' ."\n"
							.TAB_3.'<title>INVOICE from: '.INVOICE_COMP_NAME.'</title>' ."\n"
							.TAB_3.'<style>' ."\n"
							
								."\n" .$css_code ."\n\n"
								
							.TAB_3.'</style>' ."\n"
						.TAB_2.'</head>' ."\n"
						.TAB_2.'<body>' ."\n"

							.$view_in_browser_code
						
							.TAB_3.'<table class="InvoiceLayout" >' ."\n"
							;
							
		//	Banner					
		if ( $invoice_info['banner_logo'] != '' AND $invoice_info['banner_logo'] != NULL )
		{
								
			$invoice_code .= 	TAB_4.'<tr class="InvoiceLayout" >' ."\n"
									.TAB_5.'<td class="InvoiceLayout" colspan="2">' ."\n"
										.TAB_6.'<img class="InvoiceBannerImage" src="'.$invoice_info['banner_logo'].'"' ."\n"
											.TAB_7.' alt="'.$invoice_info['company_name'].'" >' ."\n"	
									.TAB_5.'</td>' ."\n"									
								.TAB_4.'</tr>' ."\n"
								;	
		}
								

								
			$invoice_code .=	TAB_4.'<tr>' ."\n"
									
									//	Name, Slogan and ABN
									.TAB_5.'<td align="left">' ."\n"
										.TAB_6.'<h2 class="InvoiceCompName" >'.nl2br(Space2nbsp($invoice_info['company_name'])).'</h2>' ."\n"
										.TAB_6.'<h3 class="InvoiceCompSlogan" >'.nl2br(Space2nbsp($invoice_info['company_slogan'])).'</h3>' ."\n"
										.TAB_6.'<h4 class="InvoiceABN" >'.$invoice_info['company_abn'].'</h4>' ."\n"
									.TAB_5.'</td>' ."\n"
									;
									
									//	"INVOICE" notice
									if ( $amount_owing == 0 ) { $inv_or_recpt = "TAX INVOICE"; }
									else { $inv_or_recpt = "INVOICE"; }
									
			$invoice_code .=		TAB_5.'<td align="right">' ."\n"
										.TAB_6.'<h2 class="InvoiceINVOICE" >'.$inv_or_recpt.'</h2>' ."\n"	
									.TAB_5.'</td>' ."\n"
									
								.TAB_4.'</tr>' ."\n"
								.TAB_4.'<tr>' ."\n"
								
									//	Company Address Block
									.TAB_5.'<td align="left">' ."\n"
										.TAB_6.'<table class="InvoiceCompLayout" >' ."\n"
										;
										
		if ( $invoice_info['company_address'] != '' AND $invoice_info['company_address'] != NULL )
		{
			$invoice_code .=				TAB_7.'<tr class="InvoiceCompAddress">' ."\n"
												.TAB_8.'<td align="right">' ."\n"
													.TAB_9.'<p>Address :</p>' ."\n"	
												.TAB_8.'</td>' ."\n"
												.TAB_8.'<td align="left">' ."\n"
													.TAB_9.'<p>'.nl2br(Space2nbsp($invoice_info['company_address'])).'</p>' ."\n"	
												.TAB_8.'</td>' ."\n"												
											.TAB_7.'</tr>' ."\n"
											;
		}
		
		if ( $invoice_info['company_email'] != '' AND $invoice_info['company_email'] != NULL )
		{		
			$invoice_code .=				TAB_7.'<tr class="InvoiceCompEmail">' ."\n"
												.TAB_8.'<td align="right">' ."\n"
													.TAB_9.'<p>Email : </p>' ."\n"
												.TAB_8.'</td>' ."\n"
												.TAB_8.'<td align="left">' ."\n"
													.TAB_9.'<a href="mailto:'.$invoice_info['company_email'].'" >'
														.$invoice_info['company_email'].'</a>' ."\n"
												.TAB_8.'</td>' ."\n"
											.TAB_7.'</tr>' ."\n"
											;
		}
		
		if ( $invoice_info['company_phone'] != '' AND $invoice_info['company_phone'] != NULL )
		{											
			$invoice_code .=				TAB_7.'<tr class="InvoiceCompPhone">' ."\n"	
												.TAB_8.'<td align="right">' ."\n"
													.TAB_9.'<p>Phone : </p>' ."\n"
												.TAB_8.'</td>' ."\n"
												.TAB_8.'<td align="left">' ."\n"
													.TAB_9.'<p>'.$invoice_info['company_phone'].'</p>' ."\n"
												.TAB_8.'</td>' ."\n"
											.TAB_7.'</tr>' ."\n"
											;
		}
		
		if ($invoice_info['auto_date'] == 'on')
		{
			//	invoice display date
			$date =  date( "j M, Y" );			
			
			//	update db with todays date
			$sql_date = date( "Y-m-d" );
			
			$mysql_err_msg = 'Updating auto date in invoice: '.$invoice_info['invoice_id'];

			$sql_statement = 'UPDATE mod_accounts_invoice SET date = "'.$sql_date.'" WHERE invoice_id = '.$invoice_info['invoice_id'];	
			UpdateDB ($sql_statement, $mysql_err_msg);	
			
			//	Turn off auto date
			$sql_statement = 'UPDATE mod_accounts_invoice SET auto_date = "" WHERE invoice_id = '.$invoice_info['invoice_id'];	
			UpdateDB ($sql_statement, $mysql_err_msg);			

		}
		else { $date = date( "j M, Y", strtotime($invoice_info['date']) );}
		
			$invoice_code .=			TAB_6.'</table>' ."\n"												
									.TAB_5.'</td>' ."\n"
									
									//	invoice N# and Date
									.TAB_5.'<td align="right">' ."\n"
										.TAB_6.'<p class="InvoiceDate" >Date : '.$date.'</p>' ."\n"
										.TAB_6.'<p class="InvoiceNumber" >Invoice N# : '.$invoice_info['invoice_id'].'</p>' ."\n"
										.TAB_6.'<p class="InvoiceCustNum" >Customer N# : '.$invoice_info['customer_id'].'</p>' ."\n"
										.TAB_6.'<p class="InvoiceTotalOwing" >Total Owing : <strong>'.$total_owing.'</strong></p>' ."\n"
									.TAB_5.'</td>' ."\n"
									
								
								.TAB_4.'</tr>' ."\n"
								.TAB_4.'<tr>' ."\n"
								
									//	TO:
									.TAB_5.'<td align="left">' ."\n"
										.TAB_6.'<table class="InvoiceToLayout" >' ."\n"
											.TAB_7.'<tr class="InvoiceToName">' ."\n"
												.TAB_8.'<td align="right">' ."\n"
													.TAB_9.'<p>TO:</p>' ."\n"	
												.TAB_8.'</td>' ."\n"
												.TAB_8.'<td align="left">' ."\n"
													.TAB_9.'<p>'.nl2br(Space2nbsp($invoice_info['to_name'])).'</p>' ."\n"	
												.TAB_8.'</td>' ."\n"												
											.TAB_7.'</tr>' ."\n"
											.TAB_7.'<tr class="InvoiceToCompany">' ."\n"
												.TAB_8.'<td align="right"></td>' ."\n"
												.TAB_8.'<td align="left">' ."\n"
													.TAB_9.'<p>'.nl2br(Space2nbsp($invoice_info['to_company'])).'</p>' ."\n"
												.TAB_8.'</td>' ."\n"
											.TAB_7.'</tr>' ."\n"
											.TAB_7.'<tr class="InvoiceToAddress">' ."\n"
												.TAB_8.'<td align="right"></td>' ."\n"
												.TAB_8.'<td align="left">' ."\n"
													.TAB_9.'<p>'.nl2br(Space2nbsp($invoice_info['to_address'])).'</p>' ."\n"
												.TAB_8.'</td>' ."\n"
											.TAB_7.'</tr>' ."\n"								
										.TAB_6.'</table>' ."\n"									
									.TAB_5.'</td>' ."\n"
									;
									
		if 
		(
				( $invoice_info['post_name'] != '' AND $invoice_info['post_name'] != NULL )
			OR	( $invoice_info['post_company'] != '' AND $invoice_info['post_company'] != NULL )
			OR	( $invoice_info['post_address'] != '' AND $invoice_info['post_address'] != NULL )
		)
		{									
			//	POST TO:
			$invoice_code .=		TAB_5.'<td align="right">' ."\n"
										.TAB_6.'<table class="InvoiceToLayout" >' ."\n"
											.TAB_7.'<tr class="InvoiceToName">' ."\n"
												.TAB_8.'<td align="right">' ."\n"
													.TAB_9.'<p>POST TO :</p>' ."\n"	
												.TAB_8.'</td>' ."\n"
												.TAB_8.'<td align="left">' ."\n"
													.TAB_9.'<p>'.nl2br(Space2nbsp($invoice_info['post_name'])).'</p>' ."\n"	
												.TAB_8.'</td>' ."\n"												
											.TAB_7.'</tr>' ."\n"
											.TAB_7.'<tr class="InvoiceToCompany">' ."\n"
												.TAB_8.'<td align="right"></td>' ."\n"
												.TAB_8.'<td align="left">' ."\n"
													.TAB_9.'<p>'.nl2br(Space2nbsp($invoice_info['post_company'])).'</p>' ."\n"
												.TAB_8.'</td>' ."\n"
											.TAB_7.'</tr>' ."\n"
											.TAB_7.'<tr class="InvoiceToAddress">' ."\n"
												.TAB_8.'<td align="right"></td>' ."\n"
												.TAB_8.'<td align="left">' ."\n"
													.TAB_9.'<p>'.nl2br(Space2nbsp($invoice_info['post_address'])).'</p>' ."\n"
												.TAB_8.'</td>' ."\n"
											.TAB_7.'</tr>' ."\n"								
										.TAB_6.'</table>' ."\n"									
									.TAB_5.'</td>' ."\n"
									;
		}
		
		//	Text 1		
		$invoice_code .=		TAB_4.'</tr>' ."\n"
								.TAB_4.'<tr class="InvoiceText1" >' ."\n"
									.TAB_5.'<td colspan="2" >' ."\n"
										.TAB_6.'<p>'.nl2br(Space2nbsp($invoice_info['text_1'])).'</p>' ."\n"
									.TAB_5.'</td>' ."\n"
								.TAB_4.'</tr>' ."\n"
								;
		// Invoice ITEMS
		$invoice_code .=		TAB_4.'<tr>' ."\n"
									.TAB_5.'<td colspan="2" >' ."\n"
										.TAB_6.'<table class="InvoiceItemsLayout" >' ."\n"
											.TAB_7.'<tr class="InvoiceTableHeader">' ."\n"
												.TAB_8.'<th class="ShopCartTableHeader" >QTY</th>' ."\n"
												.TAB_8.'<th class="ShopCartTableHeader" >ITEM CODE</th>' ."\n"
												.TAB_8.'<th class="ShopCartTableHeader" >DESCRIPTION</th>' ."\n"
												.TAB_8.'<th class="ShopCartTableHeader" >UNIT PRICE</th>' ."\n"
												.TAB_8.'<th class="ShopCartTableHeader" >DISCOUNT</th>' ."\n"
												.TAB_8.'<th class="ShopCartTableHeader" >TOTAL</th>' ."\n"
											.TAB_7.'</tr>' ."\n"
											
											.$invoice_items_code
											
										.TAB_6.'</table>' ."\n"
									.TAB_5.'</td>' ."\n"	
								.TAB_4.'</tr>' ."\n"
								;
								
		//	Text 2						
		$invoice_code .=		TAB_4.'<tr class="InvoiceText2">' ."\n"
									.TAB_5.'<td colspan="2" >' ."\n"
										.TAB_6.'<p>'.nl2br(Space2nbsp($invoice_info['text_2'])).'</p>' ."\n"
									.TAB_5.'</td>' ."\n"
								.TAB_4.'</tr>' ."\n"
								;
	
		
	if ( $amount_owing == 0 )	
	{	
		$invoice_code .=		TAB_4.'<tr class="InvoicePaidMSG">' ."\n"
									.TAB_5.'<td colspan="2" >' ."\n"
										.TAB_6.'<p>'.nl2br(Space2nbsp($invoice_info['pay_received_msg'])).'</p>' ."\n"
									.TAB_5.'</td>' ."\n"
								.TAB_4.'</tr>' ."\n"
								;								

	}
	
	else
	{
	
		//	Payment Methods
		$invoice_code .=		TAB_4.'<tr class="InvoicePayMethodHeading">' ."\n"
									.TAB_5.'<td colspan="2" >' ."\n"
										.TAB_6.'<h3>Payment Methods:</h3>' ."\n"
										.TAB_6.'<ul class="InvoicePayMethods" >' ."\n"
										;
											
		if ( $invoice_info['display_paypal_link'] == 'on' )
		{
			$invoice_code .=				TAB_7.'<li>' ."\n"
										
											.$paypal_code_msg 
											
											.TAB_7.'</li>' ."\n"
											;
		}
										
		if ( $invoice_info['payment_method_1'] != '' AND $invoice_info['payment_method_1'] != NULL )
		{
			$invoice_code .=				TAB_7.'<li>' ."\n"
												.TAB_7.nl2br(Space2nbsp($invoice_info['payment_method_1'])) ."\n"
											.TAB_7.'</li>' ."\n"
											;		
		}
										
		if ( $invoice_info['payment_method_2'] != '' AND $invoice_info['payment_method_2'] != NULL )
		{
			$invoice_code .=				TAB_7.'<li>' ."\n"
												.TAB_7.nl2br(Space2nbsp($invoice_info['payment_method_2'])) ."\n"
											.TAB_7.'</li>' ."\n"
											;		
		}
										
		if ( $invoice_info['payment_method_3'] != '' AND $invoice_info['payment_method_3'] != NULL )
		{
			$invoice_code .=				TAB_7.'<li>' ."\n"
												.TAB_7.nl2br(Space2nbsp($invoice_info['payment_method_3'])) ."\n"
											.TAB_7.'</li>' ."\n"
											;		
		}
		
			$invoice_code .=			TAB_6.'</ul>' ."\n"
									.TAB_5.'</td>' ."\n"
								.TAB_4.'</tr>' ."\n"
								;
	}
	
	
		//	Footer Text						
		$invoice_code .=		TAB_4.'<tr class="InvoiceTextFooter">' ."\n"
									.TAB_5.'<td colspan="2" >' ."\n"
										.TAB_6.'<p>'.nl2br(Space2nbsp($invoice_info['text_footer'])).'</p>' ."\n"
									.TAB_5.'</td>' ."\n"
								.TAB_4.'</tr>' ."\n"
								;
			
		$invoice_code .=	TAB_3.'</table>' ."\n"
						.TAB_2.'</body>' ."\n"
					.TAB_1.'</html>' ."\n"
					;		
		
	// NOW ... EMAIL or DISPLAY invoice
	
	if (isset($_REQUEST['email']))
	{
		//	headers
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.INVOICE_COMP_EMAIL. " \r\n";				
						
		//	to:	--------------------------------
		$to = $invoice_info['email_invoice_to'];

		//	Subject:
		$subject = 'INVOICE from: '.INVOICE_COMP_NAME;
			
		$message = $invoice_code;
			
		//	compile and send email
		mail ( $to, $subject, $message ,$headers);
		
		//echo $invoice_code;
 		
		//	update Invoice db
		$mysql_err_msg = 'Up-dating Invoice sent info';

		$sql_statement = 'UPDATE mod_accounts_invoice SET'
			
								.'  auto_date = ""'
								.', invoiced_dates = "'.$invoice_info['invoiced_dates'].','.date( "Y-m-d" ).'"'
								.', date = "'.date( "Y-m-d" ).'"'
								.' WHERE invoice_id = "'.$invoice_info['invoice_id'].'"'	
								;
										
			ReadDB ($sql_statement, $mysql_err_msg);			

		
		//	now fuck off out of here...
		header("location: /payment"); 
		exit();
	}
	
	else
	{
		echo $invoice_code;

		//	CLick to email LInk (if being viewed by the person doing the invoicing - set by elink=yes ):
		if (isset($_REQUEST['elink']))
		{
			echo TAB_3.'<div class="ViewInBrowserLink" >' ."\n";									
				echo TAB_4.'<a href="http://'.SITE_URL.'/invoices/?invid='.$invoice_info['unique_num'].'&amp;email=yes" >'."\n";
				echo TAB_4.'Click here to email this invoice to your specified email account</a>'."\n";
			echo TAB_3.'</div>' ."\n";		
		
		}
		
	}


?>