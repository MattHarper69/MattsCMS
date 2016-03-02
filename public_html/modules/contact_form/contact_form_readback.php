<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	echo TAB_7."\n";
	echo TAB_7.'<!--  Start MSG Confirm Readback  --> '. "\n";
	echo TAB_7."\n";	
		
	echo TAB_7.'<div class="ContactFormConfirmMSG" id="ContactFormConfirmMSG_'.$mod_id.'" >'."\n";	
		echo TAB_8.'<h3>'.$form_info['confirm_msg_display'].'</h3> '. "\n";	
		
		ContactFormConfirmReadBack ($_REQUEST['msg']);
		
		echo TAB_8.'<h3><a class="ButtonLink" href="'.$return_link.'" >RETURN</a></h3> '. "\n";	
	echo TAB_7.'</div>'."\n";
			
	echo TAB_7."\n";
	echo TAB_7.'<!--  End MSG Confirm Readback  --> '. "\n";
	echo TAB_7."\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	function ContactFormConfirmReadBack ($msg_id)
	{
	
		$mysql_err_msg = 'Reading confirmation sent details';	
		$sql_statement = 'SELECT * FROM 2_contact_form_recieved_data WHERE msg_id = "'.$msg_id.'" ORDER BY id';		
		$result = ReadDB ($sql_statement, $mysql_err_msg);
		
		//	MSG details not found...
		if (mysql_num_rows($result) < 1)
		{
			echo TAB_8.'<p class="Notice" >error: message information not found !</p>'. "\n";
		}
		else
		{
					
			echo TAB_8.'<fieldset class="ContactFormConfirmMSG" > '. "\n";
				echo TAB_9.'<legend>Information sent:</legend> '. "\n";
						
				echo TAB_9.'<ul class="ContactFormConfirmMSG" > '. "\n";
					

					
				
				while ($readback =  mysql_fetch_array ($result))
				{
					echo TAB_10.'<li> '. "\n";
						echo TAB_11.'<span class="Left">'.$readback['label'].' </span> '. "\n";		
						echo TAB_11.'<span class="Right">'.$readback['value'].' </span> '. "\n";
					echo TAB_10.'</li> '. "\n";
				}
					
				echo TAB_9.'</ul> '. "\n";
				
			echo TAB_8.'</fieldset> '. "\n";			
		}			
						
	}
	
?>