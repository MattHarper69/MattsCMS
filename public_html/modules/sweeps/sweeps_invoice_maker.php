<?php

	// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');		
		

	$address_str_1 = '';
	$address_str_2 = '';
	$address_str_3 = '';

	$invoice_html = 
	
	TAB_9.'<div class="ShopReceiptDisplay" >'."\n";

		$invoice_html .= TAB_10.'<p><label class="Bold">Invoice: </label><span>'.$invoice_num.'</span></p>'. "\n";
				
		$invoice_html .=  TAB_10.'<p><label class="Bold">Date: </label><span>'.$time_of_order.'</span></p>'. "\n";

		if ($_SESSION['checkout_name'] != '' AND $_SESSION['checkout_name'] != NULL)
		{$invoice_html .=  TAB_10.'<p><label class="Bold">Name: </label><span>'.$_SESSION['checkout_name'].'</span></p>'. "\n";}
		
		if ($_SESSION['checkout_email'] != '' AND $_SESSION['checkout_email'] != NULL)
		{$invoice_html .=  TAB_10.'<p><label class="Bold">Email: </label><span>'.$_SESSION['checkout_email'].'</span></p>'. "\n";}
		
		if ($_SESSION['checkout_phone'] != '' AND $_SESSION['checkout_phone'] != NULL)
		{$invoice_html .=  TAB_10.'<p><label class="Bold">Phone: </label><span>'.$_SESSION['checkout_phone'].'</span></p>'. "\n";}

		$address_str_1 = $_SESSION['checkout_address_1'];
		if ($_SESSION['checkout_address_2'] != '' AND $_SESSION['checkout_address_2'] != NULL)
		{$address_str_2 = $_SESSION['checkout_address_2'];}
		if ($_SESSION['checkout_state'] != '' AND $_SESSION['checkout_state'] != NULL)
		{$address_str_3 = $_SESSION['checkout_state'];}
		if ($_SESSION['checkout_postcode'] != '' AND $_SESSION['checkout_postcode'] != NULL)
		{$address_str_3 .= ' - '.$_SESSION['checkout_postcode'];}
		

		$invoice_html .=  TAB_10.'<p><label class="Bold">Address: </label><span>'.$address_str_1.'</span></p>'. "\n";
		if ($address_str_2 != '')
		{
			$invoice_html .=  TAB_10.'<p><label class="Bold"><span class="Spacer" ></span></label><span>'.$address_str_2.'</span></p>'. "\n";			
		}
	$invoice_html .=  TAB_10.'<p><label class="Bold"><span class="Spacer" ></span></label><span>'.$address_str_3.'</span></p>'. "\n";
	$invoice_html .=  TAB_10.'<p><label class="Bold"><span class="Spacer" ></span></label><span>'.$_SESSION['checkout_country'].'</span></p>'. "\n";
	$invoice_html .=  TAB_10.'<hr/>'. "\n";
		
		$invoice_html .= TAB_10.'<p><span class="Bold">Purchased Tickets:</span></p>'. "\n";

		$invoice_html .= TAB_10.'<table class="ShopReceiptTable" >' ."\n";
	
			$invoice_html .= TAB_11.'<tr class="ShopReceiptTableHeader" >' ."\n";
				$invoice_html .= TAB_12.'<th>Event name</th>' ."\n";
				$invoice_html .= TAB_12.'<th>Event code</th>' ."\n";
				$invoice_html .= TAB_12.'<th>Event Date</th>' ."\n";
				$invoice_html .= TAB_12.'<th>Quantity</th>' ."\n";
				$invoice_html .= TAB_12.'<th>Ticket Numbers</th>' ."\n";
				$invoice_html .= TAB_12.'<th>';
				if ($discount_exists == TRUE)
				{
					$invoice_html .= 'Discount';	
				}				
				$invoice_html .= '</th>' ."\n";
				$invoice_html .= TAB_12.'<th>Cost:</th>' ."\n";
				
			$invoice_html .= TAB_11.'</tr>' ."\n";	
			
			//	add itemized listing:  - compiled in sweeps_process_order.php
			$invoice_html .= $inv_html_item_listing;

			$invoice_html .= TAB_11.'<tr class="ShopReceiptTableFooter" >' ."\n";
				$invoice_html .= TAB_12.'<td>Total:</td>'. "\n";
				$invoice_html .= TAB_12.'<td colspan="5"></td>'. "\n";
				
				$grand_total_show = SHOP_CURRENCY_SYMBOL_PREFIX.number_format($grand_total, 2) . SHOP_CURRENCY_SYMBOL_SUFFIX;
				$invoice_html .= TAB_12.'<td align="right" >'.$grand_total_show.'</td>'. "\n";
			$invoice_html .= TAB_11.'</tr>' ."\n";
					
		$invoice_html .= TAB_10.'</table>' ."\n";
						
	$invoice_html .= TAB_9.'</div>'."\n";


		
?>