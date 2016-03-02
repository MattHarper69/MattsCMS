<?php
	 
	// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	 
	if (isset($_POST['required_comments']))
	{
		$_SESSION['required_comments'] = $required_comments;
	}	
	
//print_r($_POST);
//exit();	
	
	if (isset($_POST['add2cart_quantity']))
	{				
		$add2cart_quantity = str_replace(' ', '', trim($_POST['add2cart_quantity']));		
		$add2cart_quantity = preg_replace("/[^0-9]/","",$add2cart_quantity);	
	}
	 
	if ($add2cart_quantity == '')
	{
		$add2cart_error = 'Please specify a quantity';
		$error_step = $_POST['last_step'];
		$error = TRUE;		
	}
	
	//	read from db to Build info
	$mysql_err_msg = 'Custom Build information unavailable';	
	$sql_statement = 'SELECT shop_cat_id, item_name_prefix, image_file FROM custom_build WHERE mod_id = "'.$mod_id.'"';
			
	$custom_build_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));		
	

	//======================== BESPOKE CODE =====================================
	//	Check if all required options are entered
	
	
	//	custom colour needed to be specified if custom option chosen for deck
	if (strpos($_POST['3-DeckColour'],'Custom colour') !==  FALSE AND $_POST['3-DeckColorCustom'] == '')
	{
		$error_step_3 = TRUE;
		$error_step = 3;
		$error = TRUE;		
	}
	
	//	custom colour needed to be specified if custom option chosen edge
	if (strpos($_POST['2-EdgeType'], 'Custom - T mould') !==  FALSE AND $_POST['2-EdgeType_Tmould_colour'] == '')
	{
		$error_step_2 = TRUE;
		$error_step = 2;
		$error = TRUE;
	}

	//	custom dims need to be specified if custom size option chosen
	if (strpos($_POST['1-DeckSize'],'Custom size') !==  FALSE AND $_POST['1-DeckSizeCustom'] == '')
	{
		$error_step_1 = TRUE;
		$error_step = 1;
		$error = TRUE;
	}
	
/* 	Not needed (input values removed by javascript):
	//	Remove specified DeckDize dims if Custom size not chosed
	if (strpos($_POST['1-DeckSize'],'Custom size') !==  TRUE)
	{
		$_POST['1-DeckSizeCustom'] = '[Default Size selected]';
	}	

	if (strpos($_POST['2-EdgeType'], 'Custom - T mould') !==  TRUE )
	{
		$_POST['2-EdgeType_Tmould_colour'] = '[Default Edge selected]';
	}
	
	if (strpos($_POST['3-DeckColour'],'Custom colour') !==  TRUE )
	{
		$_POST['3-Select-DeckColour'] == '[Natual Colour selected]';
	}
*/
	if (isset($_POST['add2cart']))
	{
		foreach($_POST as $option => $value )
		{
			if (strstr($value, '[$??]'))
			{
				$add2cart_error = 'can not add to cart, a quote for this custom build is needed';
				$error_step = 6;
				$error = TRUE;				
			}		
		}
		
	}

	
	//==================================================================================
	
	if($error == FALSE)
	{
		
		//	Create new Item
		
		// Get all Options
		$total_price = 0;
		$item_desc = '<ul>';

		foreach($_POST as $option => $value )
		{
			if 
			(
					$option != 'mod_id'
				AND $option != 'last_step'					
				AND $option != 'add2cart'  
				AND $option != 'add2cart_quantity'
				AND $option != 'get_quote'
				AND $option != 'get_quote_name'
				AND $option != 'get_quote_email'
				AND !strstr($option, 'option_price_')
				
			)
			{
				// item description (custom settings
				if ($value == '')	{$value = '[Not Specified]';}
				if ($option == 'CustomBuildOption_extra_comments')
				{
					$option = 'Other Options / Requirements';
				}
				$item_desc .= '<li>' . $option . ' = ' . $value . '</li>';
				
				//	Get Prices:
				if (strstr($value, '[$'))
				{
					$parts = list($a, $option_price) = explode('[$', $value);
					$option_price = trim($option_price, ']');
					$total_price += $option_price;				
				}
				
			}
			
		}
		
		$item_desc .= '</ul>';
		
				
//echo $item_desc;
//echo $total_price;		
//exit();
	
		// 	Lock items table 
		$mysql_err_msg = 'Locking tables';
		$sql_statement = 'LOCK TABLES shop_items WRITE, shop_cat_asign WRITE';
		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));		
	
		//	add Item to items table
		$mysql_err_msg = 'Custom Build Item add to Item db failed';		
		$sql_statement = 'INSERT INTO shop_items SET'
		
												.' mod_id = '.SHOP_MOD_ID	//	pick any field to (just insert record and get insert ID)
												;
												
		mysql_query (UpdateDB ($sql_statement, $mysql_err_msg));
		
		$item_id = mysql_insert_id();		
		
				
		//	get table field names
		$sql_statement = 'SHOW COLUMNS FROM shop_items';								
							
		$show_col_result = ReadDB ($sql_statement, $mysql_err_msg);

		while ($field_names_info = mysql_fetch_array ($show_col_result))
		{
								
			if ( $field_names_info['Field'] != 'item_id' )
			{

				//	get specific mod info
				$sql_statement = 'SELECT '.$field_names_info['Field'].' FROM shop_items'

													.' WHERE model_name = "'.$_POST['model_name'].'"';
													
													
				$source_row_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

				//	Write new Specific Mod info
				$sql_statement = 'UPDATE shop_items'
				
										.' SET '.$field_names_info['Field'].' = "'.$source_row_info[0].'"'

										.' WHERE item_id = '.$item_id
										;

				ReadDB ($sql_statement, $mysql_err_msg);
									
			}							
									
		}	
		
		//	Over ride default model settings"
	
		//	trim and strip URL
		$item_name = $custom_build_info['item_name_prefix'] . $item_id . '('. $_POST['model_name'] .')';
		$url_alias = $custom_build_info['item_name_prefix'] . $item_id;
		$url_alias = str_replace(' ', '_', trim($url_alias));
		$url_alias = preg_replace('/[^A-Za-z0-9_]+/i', '',$url_alias);	// remove all but alphanums and underscores
		$url_alias = str_replace('__', '_', $url_alias);

		if(!$source_row_info)
		{
			$over_ride_sql = 	  '  display_buynow = "on"'
								.', display_rating = "on"'
								.', in_stock = "100"'
								;		
		}	
		else
		{
			$over_ride_sql = '';
		}
		
		$mysql_err_msg = 'Custom Build Item add to Item db failed';		
		$sql_statement = 'UPDATE shop_items SET'
														
												.' mod_id = '.SHOP_MOD_ID
												.', description = "'.$item_desc.'"'
												.', price = '.$total_price
												.', image_file = "'.$custom_build_info['image_file'].'"'
												.', item_name = "'. $item_name .'"'
												.', url_alias = "'. $url_alias .'"'
												.', model_name = "C-'. $_POST['model_name'] .'"'
												.', active = "on"'
												.$over_ride_sql
																								
												.' WHERE item_id = '.$item_id
												;
												
		mysql_query (UpdateDB ($sql_statement, $mysql_err_msg));
		
		//	add Product to items_cat table
		$mysql_err_msg = 'Custom Build Item add to Item db failed';		
		$sql_statement = 'INSERT INTO shop_cat_asign SET'
		
												.' cat_id = '.$custom_build_info['shop_cat_id']
												.',item_id = "'.$item_id.'"'
												;
												
		mysql_query (UpdateDB ($sql_statement, $mysql_err_msg));
		$prod_id = mysql_insert_id();
		
		// unlock table
		$mysql_err_msg = 'unlocking tables';
		$sql_statement = 'UNLOCK TABLES';
		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));	
		

		if (isset($_POST['add2cart']))
		{
			//	Add new Product to cart....
			header ("location: /index.php?p=".SHOP_PAGE_ID.'&add2cart='.$prod_id.'&add2cart_quantity='.$add2cart_quantity);
			exit();			
		}
		
		// OR get quote and send email....	
		elseif (isset($_POST['get_quote']))
		{
			$quote_error = FALSE;
			//	Validate and check for name and email...
			$quote_error_msg = '';
			$cust_name = $_SESSION['cust_name'] = trim($_POST['get_quote_name']);				
			if ( strlen($cust_name) < 2 )
			{
				$quote_error_msg = '<br/>Please enter your name';				
				$quote_error = TRUE;
			}			

			$cust_email = $_SESSION['cust_email'] = trim($_POST['get_quote_email']);				
			if ( $cust_email == '' OR !preg_match(EMAIL_REG_EXP_STRING, $cust_email))
			{
				$quote_error_msg .= '<br/>Please enter a VALID email address';				
				$quote_error = TRUE;
			}

			if ($quote_error == FALSE)			
			{

				//------------set default time zone
				date_default_timezone_set(DEFAULT_TIME_ZONE); 	//-----------only works on PHP 5.1 +

				$time_sent = date("D - d M Y - H:i T");	//---for email 
				$sql_time = date("Y-m-d H:i:s");		//----for mySQL

				//----get message ID ( random n#)
				$msg_id = rand(1,99999999);
				
				//	write msg details to db (if no error)
				$mysql_err_msg = 'writing recieved Quote Request data to database';
			
				$sql_statement = 'INSERT INTO 2_contact_form_recieved_data SET'
						
									.'	form_id = "'.$mod_id.'"'
									.',	msg_id = "'.$msg_id.'"'							
									.', time_sent = "'.$sql_time.'"'
									.', ip_add = "'.$_SERVER['REMOTE_ADDR'].'"'
									.', label = "Quote for: '.$item_name.'"'
									.', value = "'.$item_desc.'"';

				UpdateDB ($sql_statement, $mysql_err_msg);
				
				$sql_statement = 'INSERT INTO 2_contact_form_recieved_data SET'
						
									.'	form_id = "'.$mod_id.'"'
									.',	msg_id = "'.$msg_id.'"'							
									.', time_sent = "'.$sql_time.'"'
									.', ip_add = "'.$_SERVER['REMOTE_ADDR'].'"'
									.', label = "Name:"'
									.', value = "'.$cust_name.'"';

				UpdateDB ($sql_statement, $mysql_err_msg);				
				
				$sql_statement = 'INSERT INTO 2_contact_form_recieved_data SET'
						
									.'	form_id = "'.$mod_id.'"'
									.',	msg_id = "'.$msg_id.'"'							
									.', time_sent = "'.$sql_time.'"'
									.', ip_add = "'.$_SERVER['REMOTE_ADDR'].'"'
									.', label = "Email:"'
									.', value = "'.$cust_email.'"';

				UpdateDB ($sql_statement, $mysql_err_msg);					
				
				require_once ('custom_build_get_quote.php');

				//	re-direct to stop user Refreshing page and sending email again
				header ("location: /index.php?p=".$page_id.'&quote_sent=TRUE');
				exit();					
			
			}
		
		}
	
	}

	else
	{
		//	Return user to the step where the first error was made:

		echo TAB_7.'<script type="text/javascript" >
                $(document).ready(function() 
                {
					$("#CustomBuildStepContainer_1031").cycle('.($error_step - 1).');
					localStorage["build_step_id"] = '.$error_step.';
				});	
                </script>'."\n";
	}

	
?>	