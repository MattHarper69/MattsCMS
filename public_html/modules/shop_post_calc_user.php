<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
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
	
	if ( isset($_REQUEST['view']) AND $_REQUEST['view'] != '' AND $_REQUEST['view'] != NULL )
	{
		$query_str .= '&amp;view='.$_REQUEST['view'];
	}	
	
	//	Remember setting last entered
	if(isset($_SESSION['last_entered_pcode'])) {$post_destination = $_SESSION['last_entered_pcode'];}
	else {$post_destination = '';}
	if(isset($_SESSION['last_entered_method'])) {$post_method = $_SESSION['last_entered_method'];}
	else {$post_method = '';}
	if(isset($_SESSION['last_entered_quantity'])) {$post_quantity = $_SESSION['last_entered_quantity'];}
	else {$post_quantity = '';}	
	
	//	Or use previous values
	if (isset ($_POST['post_calc_submit']))
	{
		if(isset($_POST['post_destination'])) {$post_destination = $_POST['post_destination'];}
		else {$post_destination = '';}
		if(isset($_POST['post_method'])) {$post_method = $_POST['post_method'];}
		else {$post_method = '';}
		if(isset($_POST['post_quantity'])) {$post_quantity = $_POST['post_quantity'];}
		else {$post_quantity = '';}
		
		if (!isset ($_SESSION['check_out_state_2']))
		{
			//	remember their postcode & method  - BUT only if not in final stage of checkout - as updating here also updates actual postage rates
			$_SESSION['last_entered_pcode'] = $_POST['post_destination'];
			$_SESSION['last_entered_method'] = $_POST['post_method'];
		}
		
		$_SESSION['last_entered_quantity'] = $_POST['post_quantity'];
	}	
	
		
	echo TAB_8.'<div class="ShopDiv" id="ShopPostCalculator" >'."\n";	
	
		if (SHOP_POST_CALC_HEADING != "" AND SHOP_POST_CALC_HEADING != NULL )	
		{echo TAB_9.'<h4 class="Shop" >'.SHOP_POST_CALC_HEADING.'</h4>' ."\n";}
		
		echo TAB_9.'<form action="'.$_SERVER['PHP_SELF'].$query_str.'" method="post" >'. "\n";
		
			echo TAB_10.'<ul>'. "\n";

				//	Enter Postcode			//	WILL NEED MODIFYING FOR INTL DELIVERY - insert country code instead ?????
				echo TAB_11.'<li>'. "\n";
					echo TAB_12.'<label for="post_destination">Your Postcode:</label>'. "\n";
					echo TAB_12.'<input type="text" name="post_destination" class="ShopPostcodeInput" value="'.$post_destination.'"'
								.' size="'.SHOP_POST_CALC_INPUT_PCODE_SIZE.'" maxlength="'.SHOP_POST_CALC_INPUT_PCODE_MAX.'"  />'. "\n";
				echo TAB_11.'</li>'. "\n";

			if ( SHOP_POST_CALC_INPUT_METHOD_YES == 1)
			{
				//	Select Postage Method
				echo TAB_11.'<li>'. "\n";
					echo TAB_12.'<label for="post_method" >Send by:</label>'. "\n";
					echo TAB_12.'<select name="post_method" class="ShopPostSelectMethod" >'. "\n";
					
					//	get shipping item info from db
					$mysql_err_msg = 'Postage Methods Infomation unavailable';	
					$sql_statement = 'SELECT method_name, method_code FROM shop_ship_methods'

										.' WHERE carrier_id = "'.SHOP_POST_CARRIER_ID.'"'
										.' AND active = "on"'
										.' ORDER BY seq';					
					
					$ship_methods_result = ReadDB ($sql_statement, $mysql_err_msg);
					while( $ship_methods_info = mysql_fetch_array($ship_methods_result) )
					{
						if ($ship_methods_info['method_code'] == $post_method) {$selected = 'selected="selected"';}
						else	{$selected = '';}

						echo TAB_13.'<option value="'.$ship_methods_info['method_code'].'" '.$selected.'>'
									.$ship_methods_info['method_name'].'</option>'. "\n";
					}
					
					echo TAB_12.'</select>'. "\n";
				echo TAB_11.'</li>'. "\n";
				
			}
			
			if ( SHOP_POST_CALC_INPUT_ITEMS_YES == 1)
			{			
				//	Select N# of Items
				echo TAB_11.'<li>'. "\n";
					echo TAB_12.'<label for="post_quantity" >'.SHOP_POST_CALC_ITEMS_TEXT.'</label>'. "\n";
					echo TAB_12.'<select name="post_quantity" class="ShopPostSelectQuantity" >'. "\n";

					for ($i = 1; $i < SHOP_POST_CALC_INPUT_ITEMS_MAX + 1; $i++)
					{
						if ($i == $post_quantity) {$selected = 'selected="selected"';}
						else	{$selected = '';}

						echo TAB_13.'<option '.$selected.'>'.$i.'</option>'. "\n";
					
					}
					
					echo TAB_12.'</select>'. "\n";
				echo TAB_11.'</li>'. "\n";			
			}
				//	Submit Button
				echo TAB_11.'<li>'. "\n";
					echo TAB_12.'<input type="submit" name="post_calc_submit" value="'.SHOP_POST_CALC_SUBMIT_TEXT.'"'
								.' class="ShopPostSubmit" />'."\n";	
				echo TAB_11.'</li>'. "\n";
			
			echo TAB_10.'</ul>'. "\n";	

			
		echo TAB_9.'</form >'. "\n";	
	
	//	Now caluculate and display Postage
	if (isset ($_POST['post_calc_submit']))
	{ 
		require ('shop/shop_calc_post.php'); 
	
		echo TAB_9.'<div class="ShopPostCalcResult" >'. "\n";	
		
		//	if no error print cost and time
		if ( trim(strtolower($post_calc_error_msg)) == "ok" )
		{
			//	put the plural in "days"
			if ($post_calc_eta > 1) {$days = 's';}
			else {$days = '';}

			echo TAB_9.'<p>Postage cost: <span class="Bold" >'
					.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($post_calc_cost,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</span></p>'. "\n";
			echo TAB_9.'<p>Estimated delivery time: <span class="Bold">'.$post_calc_eta.'</span> day'.$days.'</p>'. "\n";

		}
		//	print error msg
		else {echo TAB_9.'<p class="WarningMSG" >ERROR: <span class="Bold">'.$post_calc_error_msg.'!</span></p>'. "\n";}

		echo TAB_9.'</div>'. "\n";	
	}
		

	if (SHOP_POST_CALC_SHOW_LOGO == 1)
	{ 
		//	Do post companies logo
		$mysql_err_msg = 'Postage Company Infomation unavailable';	
		$sql_statement = 'SELECT * FROM shop_ship_carriers WHERE carrier_id = "'.SHOP_POST_CARRIER_ID.'"';
		
		$carrier_logo_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));		
		
		echo TAB_9.'<img class="ShopPostCalcLogo" src="/_images_user/'.$carrier_logo_info['icon_image'].'"'
					.' alt="'.$carrier_logo_info['carrier_name'].' logo" />' ."\n";	
	}
	
	echo TAB_8.'</div>'."\n";	
			
?>