<?php

	// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	

	
	//=========================================================================================
	
	if ($error != TRUE)
	{
		echo TAB_9.'<h2 class="ShopCheckOut" >Please provide us with the following:</h2>' ."\n";	
	}


	else
	{	
		//-------------Display Error
		echo TAB_9.'<h3 class="WarningMSG" >ERROR: please enter the details again</h3>' ."\n";
	}
		echo TAB_9.'<form action="'.$_SERVER['PHP_SELF'].$query_str.'&amp;view=checkout" method="post" >'. "\n";
		
			echo TAB_11.'<ul class="ShopCheckOutForm" >' ."\n";
		

				//	Name
				echo TAB_11.'<li class="ShopCheckOutName RequiredFormElement" >' ."\n";
					echo TAB_12.'<label for="checkout_name">'
								.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Name:</label>'. "\n";
					
					if (isset($error_msg_name)) {$error_class = ' ErrorHilight';}
					else 
					{
						$error_class = ''; 
						$error_msg_name = ''; 
					}
					
					echo TAB_12.'<input type="text" name="checkout_name" class="CheckoutField'.$error_class.'"'
								.' value="'.$checkout_name.'" size="35" />'. "\n";	
					echo TAB_12.'<span class="WarningMSGSmall" >'.$error_msg_name.'</span>'. "\n";				
			
				echo TAB_11.'</li>' ."\n";				
				
				//	Get Email				
				echo TAB_11.'<li class="ShopCheckOutEmail RequiredFormElement" >' ."\n";
					echo TAB_12.'<label for="checkout_email">'
								.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Email:</label>'. "\n";
					
					if (isset($error_msg_email)) {$error_class = ' ErrorHilight';}
					else 
					{
						$error_class = ''; 
						$error_msg_email = ''; 
					}
					
					echo TAB_12.'<input type="text" name="checkout_email" class="CheckoutField'.$error_class.'"'
								.' value="'.$checkout_email.'" size="35" />'. "\n";	
					echo TAB_12.'<span class="WarningMSGSmall" >'.$error_msg_email.'</span>'. "\n";				
			
				echo TAB_11.'</li>' ."\n";

				//	Get Phone				
				echo TAB_11.'<li class="ShopCheckOutPhone" >' ."\n";
					echo TAB_12.'<label for="checkout_phone">'
								.'<span class="WarningMSG" >&nbsp;&nbsp;</span>Phone:</label>'. "\n";
					
					if (isset($error_msg_phone)) {$error_class = ' ErrorHilight';}
					else 
					{
						$error_class = ''; 
						$error_msg_phone = ''; 
					}
					
					echo TAB_12.'<input type="text" name="checkout_phone" class="CheckoutField'.$error_class.'"'
								.' value="'.$checkout_phone.'" size="35" />'. "\n";	
					echo TAB_12.'<span class="WarningMSGSmall" >'.$error_msg_phone.'</span>'. "\n";				
			
				echo TAB_11.'</li>' ."\n";
				
				//	Get Address_1				
				echo TAB_11.'<li class="ShopCheckOutAddress_1 RequiredFormElement" >' ."\n";
					echo TAB_12.'<label for="checkout_address_1">'
								.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Address:</label>'. "\n";
					
					if (isset($error_msg_address_1)) {$error_class = ' ErrorHilight';}
					else 
					{
						$error_class = ''; 
						$error_msg_address_1 = ''; 
					}
					
					echo TAB_12.'<input type="text" name="checkout_address_1" class="CheckoutField'.$error_class.'"'
								.' value="'.$checkout_address_1.'" size="35" />'. "\n";	
					echo TAB_12.'<span class="WarningMSGSmall" >'.$error_msg_address_1.'</span>'. "\n";				
			
				echo TAB_11.'</li>' ."\n";

				//	Get Address_2				
				echo TAB_11.'<li class="ShopCheckOutAddress_2" >' ."\n";
					echo TAB_12.'<label for="checkout_address_2">'
								.'<span class="WarningMSG" >&nbsp;</span>City/Area:</label>'. "\n";

					echo TAB_12.'<input type="text" name="checkout_address_2" class="CheckoutField"'
								.' value="'.$checkout_address_2.'" size="35" />'. "\n";	
						
				echo TAB_11.'</li>' ."\n";					
									

				//	Get Country if more than one country active
				echo TAB_11.'<li class="ShopCheckOutCountry RequiredFormElement" >' ."\n";
					
					echo TAB_12.'<label for="checkout_country">'
								.'<span class="WarningMSG" >'.FORM_REQD_FIELD_SYMBOL.'</span>Country:</label>'. "\n";
					
					///if ($has_states == 'on')
					//{
						//	Ajax to update state list when selecting a new country
						echo TAB_7.'<script type="text/javascript">
						$(document).ready(function()
						{
							$("#SelectCountry").change(function()
							{
								var id = $(this).val();
								var dataString = "update_country=" + id;
								
								$.ajax
								({
									type: "POST",
									url: "modules/shop/shop_ajax_updates.php",
									data: dataString,
									cache: false,
									success: function(html)
									{
										$("#ShopCheckOutStatePostCode").html(html);
									} 
								});

							});

						});
						</script>'. "\n";							
						
					//}
					
					echo TAB_12.'<select name="checkout_country" id="SelectCountry" class="CheckoutField'.$error_class.'" >'. "\n";
								
					foreach ($select_countries as $country)
					{
						if ($country == $checkout_country) {$selected = 'selected="selected"';}					
						else {$selected = '';}
						
						echo TAB_13.'<option '.$selected.'>'.$country.'</option>'. "\n";						
					}
					echo TAB_12.'</select>'. "\n";
					
					echo TAB_12.'<noscript><input type="submit" value="Reload" /></noscript>'. "\n";
					
					echo TAB_12.'<span class="Notice" >'.$error_msg_country_restricted.'</span>'. "\n";
					
				echo TAB_11.'</li>' ."\n";					
					
				
				echo TAB_11.'<span id="ShopCheckOutStatePostCode" >' ."\n";		//	span for Axax
				
				//	Get state
				if ($has_states == 'on')
				{
					$error_msg_state_restricted = '';
					
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

															.' WHERE country_name = "'.$checkout_country.'"'
															.' AND shop_address_countries.country_id = shop_address_states.country_id'
															.' AND shop_address_states.active = "on"'
															.' ORDER BY shop_address_states.seq'
															;					
		
					$states_result = ReadDB ($sql_statement, $mysql_err_msg);
					$states = array();
					
					if (mysql_num_rows(ReadDB ($sql_statement, $mysql_err_msg)) == 0)
					{						
						//	default to text box if no states / counties found
						echo TAB_12.'<input type="text" name="checkout_state" class="CheckoutField" size="20";>'. "\n";
					}
					else
					{
						echo TAB_12.'<select name="checkout_state" class="CheckoutField" >'. "\n";
					
						while( $states_info = mysql_fetch_array($states_result) )
						{
							
							if ($states_info['state_display_name'] == $checkout_state) {$selected = 'selected="selected"';}
							else {$selected = '';}
						
							if (in_array($states_info['state_name'], $allowed_states))
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


					}
					
					
					if ($error_msg_state_restricted != '' AND $has_states != '')
					{
						echo TAB_12.'<span class="Notice" >'.$error_msg_state_restricted.'</span>'. "\n";
					}					
					
					
					echo TAB_11.'</li>' ."\n";
				
				}
				
				//	Get Postcode
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
					
					if (isset($error_msg_postcode)) {$error_class = ' ErrorHilight';}
					else 
					{
						$error_class = ''; 
						$error_msg_postcode = ''; 
					}				
					
					
						echo TAB_12.'<label for="checkout_state">'
								.'<span class="WarningMSG" >'.$required_symbol.'</span><span id="PostCodeOrZip">'.$zip_or_post
								.':</span></label>'. "\n";						
									
						echo TAB_12.'<input type="text" name="checkout_postcode" class="CheckoutField'.$error_class.'"'
								.' value="'.$checkout_postcode.'" size="'.$pcode_max_length.'" maxlength="'.$pcode_max_length.'" />'. "\n";	
				
						echo TAB_12.'<span class="WarningMSGSmall" >'.$error_msg_postcode.'</span>'. "\n";


					echo TAB_11.'</li>' ."\n";
				}
				
				echo TAB_11.'</span>' ."\n";		//	span for Axax
				
				//	Read the terms and rules
				if (SHOP_CHECKOUT_CHECK_READ_RULES == 1)
				{					
				
					echo TAB_11.'<li class="RequiredFormElement" >' ."\n";
				
					if (isset($error_msg_check_read_rules)) {$error_class = ' ErrorHilight';}
					else 
					{
						$error_class = ''; 
						$error_msg_check_read_rules = ''; 
					}				
				
						
						echo TAB_12.'<label for="checkout_state" style="width: 2em;"><span class="WarningMSG" >'
									.FORM_REQD_FIELD_SYMBOL.'</span></label>' . "\n";						
					
					if ($checkout_read_rules == 'on')
					{
						$checked = ' checked="checked"';
						$disabled = ' disabled="disabled"';
					}
					else 
					{ 
						$checked = '';
						$disabled = '';
					}
					
					echo TAB_12.'<input type="checkbox" name="checkout_read_rules" class="CheckoutField'
								.$error_class.'"'.$checked.$disabled.' />'. "\n";	
					echo TAB_12.'<span>'.SHOP_CHECKOUT_CHECK_READ_RULES_TEXT.'</span>'. "\n";

				
					echo TAB_12.'<span class="WarningMSGSmall" >'.$error_msg_check_read_rules.'</span>'. "\n";

					
			
					echo TAB_11.'</li>' ."\n";
				}	
				
				//	Submit Button
				echo TAB_11.'<li class="ShopCheckOutSubmit" >' ."\n";

					echo TAB_12.'<label><span class="WarningMSG" ></span></label>'. "\n";
				
					//	Continue OR Update Button					
					echo TAB_12.'<input type="submit" name="checkout_form_submit"'
								.' class="ShopCheckOutSubmit" value="Submit and Continue" />'. "\n";
				echo TAB_11.'</li>' ."\n";					

			echo TAB_11.'</ul>' ."\n";

		echo TAB_10.'</form>'. "\n";
		
?>