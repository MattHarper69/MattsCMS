<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	echo TAB_7.'<div class="ShopDiv" id="ShopCentreColumn" >'."\n";			
	
	//	if this is set then the user has arrived via an agents website and the agent must stay logged-in
	if(isset($_SESSION['agent_cannot_logout']) AND $_SESSION['agent_cannot_logout'] == 1)
	{
		echo TAB_8.'<h3 class="WarningMSG" >Access to this page is restricted to Agents only</h3>' ."\n";
	}
	
	else
	{
		$error = false;
		
		//	Do log OUT
		if (isset($_GET['agentexit']) AND $_GET['agentexit'] == 1)
		{
			unset($_SESSION['agent_login_id']);
			unset($_SESSION['agent_login_name']);	
		}
		
		if (isset($_SESSION['agent_login_id']))
		{
			echo TAB_8.'<h3>You are now logged-in as: '.$_SESSION['agent_login_name'] .'&nbsp;&nbsp;-&nbsp;&nbsp;'."\n";
			
				//	log out link
				echo TAB_9.'<a class="ButtonLink" href="'.$_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;agentexit=1'.'" >Log-out</a>'."\n";
				
			echo TAB_8.'</h3>' ."\n";	
		}	
		
		//	process form 
		elseif (isset ($_POST['agent_login_form_submit']) AND $_POST['agent_password'] != '')
		{		
			
			//--------Get agent info from db:		
			$mysql_err_msg = 'Retrieving Agent Account Info';	
			$sql_statement = 'SELECT *  FROM sweeps_agents'
														.' WHERE agent_password = "'.$_POST['agent_password'].'"'	
														.' AND active = "on"'
														;

			$agent_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
						
			//	is entered agent password VALID
			if ($agent_info['agent_password'] == $_POST['agent_password'])
			{	
				$error = false;
				
				//	store agent name and set login flag
				$_SESSION['agent_login_id'] = $agent_info['agent_id'];
				$_SESSION['agent_login_name'] = $agent_info['agent_name'];
	
				//	display welcome msg:
				echo TAB_8.'<h3>Welcome: '.$agent_info['agent_name']. ', you are now logged-in' .'&nbsp;&nbsp;-&nbsp;&nbsp;'."\n";
				
					//	log out link
					echo TAB_9.'<a class="ButtonLink" href="'.$_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;agentexit=1'.'" >Log-out</a>'."\n";
					
				echo TAB_8.'</h3>' ."\n";	
	
				
				//	store details (if set) to auto fill check-out form
				if ($agent_info['pre_fill_checkout'] != 'on')
				{
					if ($agent_info['agent_contact_name'] != '') {$_SESSION['checkout_name'] = $agent_info['agent_contact_name'];}				
					if ($agent_info['agent_email'] != '') {$_SESSION['checkout_email'] = $agent_info['agent_email'];}
					if ($agent_info['agent_phone'] != '') {$_SESSION['checkout_phone'] = $agent_info['agent_phone'];}
					if ($agent_info['agent_country'] != '') {$_SESSION['checkout_country'] = $agent_info['agent_country'];}
					if ($agent_info['agent_address_1'] != '') {$_SESSION['checkout_address_1'] = $agent_info['agent_address_1'];}
					if ($agent_info['agent_address_2'] != '') {$_SESSION['checkout_address_2'] = $agent_info['agent_address_2'];}
					if ($agent_info['agent_state'] != '') {$_SESSION['checkout_state'] = $agent_info['agent_state'];}
					if ($agent_info['agent_postcode'] != '') {$_SESSION['checkout_postcode'] = $agent_info['agent_postcode'];}			
				}


	
			}
			
			else
			{
				$error = true;
			}

		}
					
		if (!isset($_SESSION['agent_login_id']))
		{
					
			echo TAB_8.'<h3>Agents can log-in here with their Agent ID:</h3>' ."\n";	

			echo TAB_8.'<form action="'.$_SERVER['PHP_SELF'].'?p='.$page_id.'" method="post" >'. "\n";
			
				echo TAB_9.'<ul class="ShopCheckOutForm" >' ."\n";
			
					echo TAB_10.'<li>' ."\n";
						echo TAB_11.'<label for="agent_password">Agent ID:</label>'. "\n";
						
						if (isset($error_msg_name)) {$error_class = ' ErrorHilight';}
						else {$error_class = '';}
						
						echo TAB_11.'<input type="text" name="agent_password" class="CheckoutField'.$error_class.'"'
									.' value="" size="32" />'. "\n";				

						//	Submit Button					
						echo TAB_11.'<input type="submit" name="agent_login_form_submit" class="ShopCheckOutSubmit" value="Log-in" />'. "\n";
					echo TAB_10.'</li>' ."\n";					

				echo TAB_9.'</ul>' ."\n";

			echo TAB_8.'</form>'. "\n";
		
			if ($error == true)
			{
				//-------------Display Error
				echo TAB_8.'<h3 class="WarningMSG" >ERROR: This Agent ID was not recognized</h3>' ."\n";			
			}
		}	
	}
	
	
	echo TAB_7.'</div>'."\n";		
?>