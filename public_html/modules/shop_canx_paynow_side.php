<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	//	Build the query str for Re-direction
	$query_str = '?p='.SHOP_PAGE_ID;
					
	if ( isset($_REQUEST['s_cat']) AND $_REQUEST['s_cat'] != '' AND $_REQUEST['s_cat'] != NULL )
	{
		$query_str .= '&s_cat='.$_REQUEST['s_cat'];
	}
		
	if ( isset($_REQUEST['prod_id']) AND $_REQUEST['prod_id'] != '' AND $_REQUEST['prod_id'] != NULL )
	{
		$query_str .= '&prod_id='.$_REQUEST['prod_id'];
	}
	

	//	is button set ??
	If ( isset($_SESSION['canx_paypal']) AND (isset($_REQUEST['view']) AND $_REQUEST['view'] != 'PPreturn'))
	{
		echo TAB_8.'<div class="ShopDiv" id="ShopCheckOut" >'."\n";	
		
			echo TAB_9.'<p class="ShopPaynowSide" >Click to pay un-paid order:</h2>'."\n";
				
			echo $_SESSION['canx_paypal'];

			$query_str = $query_str.'&amp;unsetpaynow=1';

		echo TAB_8.'</div>'."\n";	
	}	

?>