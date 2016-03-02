<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	$invoice_error = '';
	//	Check that customer_id and invoice_id are valid and re-direct to "/invoices/?invid=unique_num

	if (isset($_POST['invoice_viewer_submit']) AND $_REQUEST['customer_id'] != '' AND $_REQUEST['invoice_id'] !='')
	{

		$mysql_err_msg = 'Retrieving Invoice and customer number';	
		$sql_statement = 'SELECT customer_id, unique_num FROM mod_accounts_invoice 
																	
																WHERE invoice_id = "'.$_REQUEST['invoice_id'].'"'
																;

		$invoice_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

		if ($invoice_info['customer_id'] == $_REQUEST['customer_id'])
		{
			$unique_num = $invoice_info['unique_num'];
			
			echo $url = 'location: /invoices/?invid='.$unique_num;
			header($url); 
			exit();			
		}
		else
		{
			$invoice_error = TAB_8.'<p class="WarningMSG">No invoice exists for this customer</p>'."\n";;
		}
		
	}
	
	
		//	Do Form:
		echo TAB_7.'<div class="InvoiceViewer" id="InvoiceViewer_'.$mod_id.'" >'."\n";
		
			echo $invoice_error;
		
			echo TAB_8.'<form class="InvoiceViewer" action="'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'" method="post" >'."\n";
				echo TAB_9.'<ul>'."\n";
					echo TAB_10.'<li>'."\n";
						echo TAB_11.'<label for="customer_id" >Customer ID:</label>'."\n";
						echo TAB_11.'<input class="customerId" type="password" name="customer_id" value="" />'."\n";
					echo TAB_10.'</li>' ."\n";
					echo TAB_10.'<li>'."\n";
						echo TAB_11.'<label for="invoice_id" >Invoice N#:</label>'."\n";
						echo TAB_11.'<input class="invoiceId" type="text" name="invoice_id" value="" />'."\n";
					echo TAB_10.'</li>' ."\n";
					echo TAB_10.'<li>'."\n";
						echo TAB_11.'<input class="SubmitButton" type="submit" name="invoice_viewer_submit" value="Enter" />'."\n";
					echo TAB_10.'</li>' ."\n";
				echo TAB_9.'</ul>'."\n";

	
			echo TAB_8.'</form>'."\n";
			
		echo TAB_7.'</div>'."\n";	
	
?>