<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	get shipping company info from db
	$mysql_err_msg = 'Postage Company Infomation unavailable';	
	$sql_statement = 'SELECT post_calc_url, icon_image FROM shop_ship_carriers'

										.' WHERE carrier_id = "'.SHOP_POST_CARRIER_ID.'"';
	
	$carrier_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

	//	get shipping item info from db
	$mysql_err_msg = 'Postage Calculation Infomation unavailable';	
	$sql_statement = 'SELECT * FROM shop_items'

										.' WHERE item_id = "'.SHOP_POST_CALC_DEFAULT_ITEM.'"';

	$ship_item_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));		
	
	
		//==================customized Formulas===================================
		//include ( CODE_NAME.'_custom_postage_function.php');

	
		//	build query str to send to Post company website
		$query = 'Pickup_Postcode='.SHOP_POST_PICKUP;
		$query .= '&Destination_Postcode='.$post_destination;
		$query .= '&Country='.SHOP_POST_DEFAULT_COUNTRY;		//	WILL NEED MODIFYING FOR INTL DELIVERY
		$query .= '&Weight='.$ship_item_info['ship_weight_kg'] * 1000;
		$query .= '&Service_Type='.$post_method;
		$query .= '&Length='.$ship_item_info['ship_length_mm'];
		$query .= '&Width='.$ship_item_info['ship_width_mm'];
		$query .= '&Height='.$ship_item_info['ship_height_mm'];
		$query .= '&Quantity='.$post_quantity;

		$post_calc_file = file('http://'.$carrier_info['post_calc_url'].'?'.$query);

		//	splt up values in returned array
		//	Cost
		$post_calc_file_array = explode('=',$post_calc_file[0]);	
		$post_calc_cost = $post_calc_file_array[1];
		
		//	add other user specified amounts
		$post_calc_cost = $post_calc_cost * (1 + SHOP_POST_ADD_PERCENT/100) 
							+ $ship_item_info['ship_add_amount'] * $post_quantity 
							+ SHOP_POST_ADD_AMOUNT;
		
		//	round up/down if set
		if (SHOP_POST_CALC_ROUND_AMOUNT == 1)
		{ $post_calc_cost = round($post_calc_cost);}
		
		

		//	ETA ( + specified days)
		$post_calc_file_array = explode('=',$post_calc_file[1]);
		$post_calc_eta = round(ceil($post_calc_file_array[1]) + SHOP_POST_ETA_ADD_DAYS);

		//	error MSG
		$post_calc_file_array = explode('=',$post_calc_file[2]);
		$post_calc_error_msg = $post_calc_file_array[1];
	
	
?>