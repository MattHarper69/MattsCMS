<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	if ( isset($_REQUEST['view']) AND $_REQUEST['view'] != 'browse' )
	{
		
		//	Build the query str for Re-direction
		$query_str = '?p='.SHOP_PAGE_ID;
						
		if ( isset($_REQUEST['s_cat']) AND $_REQUEST['s_cat'] != '' AND $_REQUEST['s_cat'] != NULL )
		{
			$query_str .= '&amp;s_cat='.$_REQUEST['s_cat'];
		}
			
		if ( isset($_REQUEST['prod_id']) AND $_REQUEST['prod_id'] != '' AND $_REQUEST['prod_id'] != NULL )
		{
			$query_str .= '&amp;prod_id='.$_REQUEST['prod_id'];
		}			
		
		echo TAB_8.'<div class="ShopDiv" id="ShopContShopButton" >'."\n";		
		
			//	OR Continue Shopping
			$view_cart_query_str = $query_str.'&amp;view=browse';
			echo TAB_9.'<a class="ShopGoCheckoutButton" href="'.$_SERVER['PHP_SELF'].$view_cart_query_str.'" '		
							. 'title="Return to the Product Listing to browse more items" >Continue Shopping</a>'. "\n";
						
		echo TAB_8.'</div>'."\n";						
	}
						
?>