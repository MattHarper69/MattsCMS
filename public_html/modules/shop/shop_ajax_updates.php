<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	$file_path_offset = '../../';
						

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');

	require_once ($file_path_offset.'includes/access.php');


	
	
	//	 Configure Postcode box and re-populate STATE select box on changing COUNTRY- Ajax request
	if(isset($_POST['update_country']))
	{
		
			// get Postcode validation config info for Validation and label in form
			$mysql_err_msg = 'Country Postcode validation config Infomation unavailable';	
			$sql_statement = 'SELECT * FROM shop_address_countries'

									.' WHERE country_name = "'.$_POST['update_country'].'"'
									.' AND active = "on"'
									.' ORDER BY seq'
									;					

			$postcode_config_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			if (count($postcode_config_info) !=0 )
			{
				$has_states = $postcode_config_info['has_states'];
				$has_postcodes = $postcode_config_info['has_postcodes'];
				$state_or_prov = $postcode_config_info['state_or_prov'];				
				$zip_or_post = $postcode_config_info['zip_or_post'];
				$pcode_min_length = $postcode_config_info['pcode_min_length'];
				$pcode_max_length = $postcode_config_info['pcode_max_length'];
				$postcode_only_num = $postcode_config_info['postcode_only_num'];
				$postcode_required = $postcode_config_info['postcode_required'];
				$state_required = $postcode_config_info['state_required'];				
				
			}
			else //	default for failsafe
			{
				$has_states = '';
				$has_postcodes = 'on';
				$state_or_prov = 'State';
				$zip_or_post = 'Postcode';
				$pcode_min_length = 3;
				$pcode_max_length = 10;
				$postcode_only_num = '';
				$postcode_required = '';
				$state_required = '';			
			}
		
		
	
		//	Get state
		if ($has_states == 'on')
		{
			
			if ($state_required)
			{
				$RequiredFormElement = ' RequiredFormElement';
				$required_symbol = FORM_REQD_FIELD_SYMBOL;
			}
			else
			{
				$RequiredFormElement = '';
				$required_symbol = '&nbsp;&nbsp;';
			}			
			
			
			echo TAB_11.'<li class="ShopCheckOutStatePostCode'.$RequiredFormElement.'" >' ."\n";
				echo TAB_12.'<label for="checkout_state">'
					.'<span class="WarningMSG" >'.$required_symbol.'</span><span id="PostCodeOrZip">'.$state_or_prov
					.':</span></label>'. "\n";	
						


			//	get states from db
			$mysql_err_msg = 'State Address Infomation unavailable';	
			$sql_statement = 'SELECT state_name, state_display_name FROM shop_address_states, shop_address_countries'

													.' WHERE country_name = "'.$_POST['update_country'].'"'
													.' AND shop_address_countries.country_id = shop_address_states.country_id'
													.' AND shop_address_states.active = "on"'
													.' ORDER BY shop_address_states.seq'
													;					

			$states_result = ReadDB ($sql_statement, $mysql_err_msg);
			$states = array();
			
			if (mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg)) == 0)
			{						
				//	default to text box if no states / counties found
				echo TAB_12.'<input type=text name="checkout_state" class="CheckoutField" size="20";>'. "\n";
			}
			else
			{
				echo TAB_12.'<select name="checkout_state" class="CheckoutField" >'. "\n";
			
				$error_msg_state_restricted = '';
				while( $states_info = mysql_fetch_array($states_result) )
				{
					
					//	select last chosen state if set
					if (isset($_SESSION['checkout_state']) AND $states_info['state_name'] == $_SESSION['checkout_state']) 
					{$selected = 'selected="selected"';}
					else {$selected = '';}
					
					if (in_array($states_info['state_name'], $_SESSION['allowed_states']))
					{
						echo TAB_13.'<option value="'.$states_info['state_display_name'].'" '.$selected.'>'.$states_info['state_name']
								.'</option>'. "\n";
					}
					else 						
					{
						$error_msg_state_restricted = '* state restrictions apply';
					}
				}							
			
				
				echo TAB_12.'</select>'. "\n";	

				if ($error_msg_state_restricted != '' AND $has_states != '')
				{
					echo TAB_12.'<span class="Notice" >'.$error_msg_state_restricted.'</span>'. "\n";
				}	
				
			}

			echo TAB_11.'</li>' ."\n";
			
		}


		//	Do Postcode
		if ($has_postcodes == 'on')
		{							
	
			if ($postcode_required)
			{
				$RequiredFormElement = ' RequiredFormElement';
				$required_symbol = FORM_REQD_FIELD_SYMBOL;
			}
			else
			{
				$RequiredFormElement = '';
				$required_symbol = '&nbsp;&nbsp;';
			}
					
			echo TAB_11.'<li class="ShopCheckOutStatePostCode'.$RequiredFormElement.'" >' ."\n";
			
				echo TAB_12.'<label for="checkout_state">'
							.'<span class="WarningMSG" >'.$required_symbol.'</span><span id="PostCodeOrZip">'.$zip_or_post
							.':</span></label>'. "\n";						
				
						
				echo TAB_12.'<input type="text" name="checkout_postcode" class="CheckoutField"'
							.' value="" size="'.$pcode_max_length.'" maxlength="'.$pcode_max_length.'" />'. "\n";
							//	clear postcard field when changing countries
			echo TAB_11.'</li>' ."\n";			
		}	

			
	
			
	}
	
	
?>