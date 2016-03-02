<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$default_expiry_time = 2;
	$default_expiry_unit = 'weeks';

	//	need for Calendar Popup
	echo TAB_2.'<script type="text/javascript" >document.write(getCalendarStyles());</script>'."\n";
	echo TAB_2.'<div id="CancelDatePopup" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">'."\n";
	echo TAB_2.'</div>'."\n"; 	
		
		//	RESET button ======================================================				
		echo TAB_7.'<a  href="'.$this_page.'?event_id='.$_REQUEST['event_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;tab='.$tab.'"'."\n";
			echo TAB_8.' title="Reload this page to Reset all '.$alias.' data" >' ."\n";
			echo TAB_8.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
		echo TAB_7.'</a>'. "\n";	

			//	Date and Time
			$time_units_array = array ( 
											 'minutes' 	=> 60
											,'hours' 	=> 3600
											,'days' 	=> 86400
											,'weeks'	=> 604800
											,'months'	=> 2592000
											,'years'	=> 31536000
												
										);			
			

			if ($_REQUEST['event_id'] == 'new') 
			{
				$event_date = date("d-m-Y");
				$event_time = '9:00am';
				$start_date = date("d-m-Y");
				$start_time = '5:00am';
				$stop_date = date("d-m-Y", time() + $time_units_array['months']);
				$stop_time = '12:00am';				
				
			}
			else
			{
				$event_date = date("d-m-Y",strtotime(substr($event_info['time'], 0, 10)));						
				$event_time = date("g:ia",strtotime(substr($event_info['time'], 11, 5)));
				$start_date = date("d-m-Y",strtotime(substr($event_info['active_start'], 0, 10)));						
				$start_time = date("g:ia",strtotime(substr($event_info['active_start'], 11, 5)));
				$stop_date = date("d-m-Y",strtotime(substr($event_info['active_end'], 0, 10)));						
				$stop_time = date("g:ia",strtotime(substr($event_info['active_end'], 11, 5)));		
			}
			
			//	Get expiry time and units from set seconds....
			
			
/////////////////////////////////////////////////		EDITED TO HERE		////////////////////////////////////////////////////////////
			
			if ($_REQUEST['event_id'] == 'new') 
			{				
				$auto_expire_time = $default_expiry_time;
				$auto_expire_unit = $time_units_array[$default_expiry_unit];
			}
			
			else
			{
				$auto_expire_time = $event_info['auto_expire_time'];
				$auto_expire_unit = $event_info['auto_expire_unit'];
			}


			
		//	Event Time		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";
			
			echo TAB_8.$alias.' Date: ' . "\n";
			echo TAB_8.'<input type="text" name="event_date"  value="'.$event_date.'" maxlength="10" size="10"' . "\n";
			echo TAB_9.' title="Insert a date for this '.$alias.' by clicking the date icon" />'."\n";
			
			//	do javascript date picker						
			echo TAB_8.'<script type="text/javascript">var EventDate = new CalendarPopup("CancelDatePopup");'
					  .'</script>' ."\n"; 
							
			echo TAB_8.'<a href="#" onClick="EventDate.select('
						.'document.forms[0].event_date,\'anchor_event_date\',\'dd-MM-yyyy\'); return false;"'
						.' id="anchor_event_date" title="Click to insert a Date" >' ."\n";  
				echo TAB_9.'<img src="/images_misc/icon_calendar_32x32.png" alt="insert date" />' ."\n"; 
			echo TAB_8.'</a>' ."\n";
			
			echo TAB_8.' @ Time: ' . "\n";
			echo TAB_8.'<input type="text" name="event_time" maxlength="7" size="8" value="'.$event_time.'" />' . "\n";			
			
		echo TAB_7.'</fieldset>'."\n";


		//	Start Display Time		
		echo TAB_7.'<fieldset class="AdminForm3" style="clear: left;">'."\n";
			
			echo TAB_8.'Start displaying this '.$alias.' from this Date: ' . "\n";
			echo TAB_8.'<input type="text" name="start_date"  value="'.$start_date.'" maxlength="10" size="10"' . "\n";
			echo TAB_9.' title="Insert a date for this '.$alias.' by clicking the date icon" />'."\n";
			
			//	do javascript date picker						
			echo TAB_8.'<script type="text/javascript">var StartDate = new CalendarPopup("CancelDatePopup");'
					  .'</script>' ."\n"; 
							
			echo TAB_8.'<a href="#" onClick="StartDate.select('
						.'document.forms[0].start_date,\'anchor_start_date\',\'dd-MM-yyyy\'); return false;"'
						.' id="anchor_start_date" title="Click to insert a Date" >' ."\n";  
				echo TAB_9.'<img src="/images_misc/icon_calendar_32x32.png" alt="insert date" />' ."\n"; 
			echo TAB_8.'</a>' ."\n";
			
			echo TAB_8.' @ Time: ' . "\n";
			echo TAB_8.'<input type="text" name="start_time" maxlength="7" size="8" value="'.$start_time.'" />' . "\n";
			
		echo TAB_7.'</fieldset>'."\n";	

		//	Stop Display Time

			//	jQuery text box grey out on radio button switch
			
					echo TAB_6.'<script type="text/javascript">
					
					$(document).ready( function()
					{';								
		
						echo TAB_7.'		
						$("#RadioStopTime, #RadioExpireTime, #RadioNoExpire").click(function() {
							if($("#RadioStopTime").is(":checked"))
							{							
								$("#InputStopDate").css("background-color","#ffffff");
								$("#InputStopTime").css("background-color","#ffffff");
								$("#InputStopDate").css("color","#000000");
								$("#InputStopTime").css("color","#000000");								
								$("#InputExpireTime").css("background-color","#cccccc");
								$(".InputTimeUnits").css("background-color","#cccccc");
								$("#InputExpireTime").css("color","#888888");
								$(".InputTimeUnits").css("color","#888888");									
							}
							
							if($("#RadioExpireTime").is(":checked"))
							{															
								$("#InputStopDate").css("background-color","#cccccc");
								$("#InputStopTime").css("background-color","#cccccc");
								$("#InputStopDate").css("color","#888888");
								$("#InputStopTime").css("color","#888888");								
								$("#InputExpireTime").css("background-color","#ffffff");
								$(".InputTimeUnits").css("background-color","#ffffff");
								$("#InputExpireTime").css("color","#000000");
								$(".InputTimeUnits").css("color","#000000");								
							}
							
							if($("#RadioNoExpire").is(":checked"))
							{															
								$("#InputStopDate").css("background-color","#cccccc");
								$("#InputStopTime").css("background-color","#cccccc");
								$("#InputStopDate").css("color","#888888");
								$("#InputStopTime").css("color","#888888");								
								$("#InputExpireTime").css("background-color","#cccccc");
								$(".InputTimeUnits").css("background-color","#cccccc");
								$("#InputExpireTime").css("color","#888888");
								$(".InputTimeUnits").css("color","#888888");								
							}
								
						});					

					});		
				</script>'."\n";

				
		echo TAB_7.'<fieldset class="AdminForm3" style="clear: left;">'."\n";
			
			echo TAB_8.'<p>Stop displaying this '.$alias.':</p>' . "\n";

			if ($event_info['auto_expire_on'] == 'on')
			{
				$checked_1 = '';
				$checked_2 = 'checked="checked"';
				$checked_3 = '';
				$greyed_1 = 'style="background-color:#cccccc; color:#888888;"';
				$greyed_2 = '';

			}

			elseif ($event_info['auto_expire_on'] == 'no')
			{
				$checked_1 = '';
				$checked_2 = '';
				$checked_3 = 'checked="checked"';
				$greyed_1 = 'style="background-color:#cccccc; color:#888888;"';
				$greyed_2 = 'style="background-color:#cccccc; color:#888888;"';
			}			
			
			else
			{
				$checked_1 = 'checked="checked"';
				$checked_2 = '';
				$checked_3 = '';
				$greyed_1 = '';
				$greyed_2 = 'style="background-color:#cccccc; color:#888888;"';				
			}			
			
			
			//	By Date
			echo TAB_8.'<p><input type="radio" id="RadioStopTime" name="stop_time_select" value="stop_time" '.$checked_1.'/>' . "\n";
			echo TAB_8.' -&raquo; after this Date: <input type="text" name="stop_date"  value="'.$stop_date.'" maxlength="10" size="10"' . "\n";
			echo TAB_9.' title="Insert a date for this '.$alias.' by clicking the date icon" '.$greyed_1.' id="InputStopDate"/>'."\n";
			
			//	do javascript date picker						
			echo TAB_8.'<script type="text/javascript">var StopDate = new CalendarPopup("CancelDatePopup");'
					  .'</script>' ."\n"; 
							
			echo TAB_8.'<a href="#" onClick="StopDate.select('
						.'document.forms[0].stop_date,\'anchor_stop_date\',\'dd-MM-yyyy\'); return false;"'
						.' id="anchor_stop_date" title="Click to insert a Date" >' ."\n";  
				echo TAB_9.'<img src="/images_misc/icon_calendar_32x32.png" alt="insert date" />' ."\n"; 
			echo TAB_8.'</a>' ."\n";
			
			echo TAB_8.' @ Time: ' . "\n";
			echo TAB_8.'<input type="text" name="stop_time" maxlength="7" size="8" value="'.$stop_time.'" '.$greyed_1.' id="InputStopTime"/></p>'. "\n";
			//echo TAB_8.'<p>OR</p>' . "\n";
			
			//	OR by expiry time amount
			echo TAB_8.'<p><input type="radio" name="stop_time_select" value="auto_expire" '.$checked_2.' id="RadioExpireTime"/>' . "\n";
			
			echo TAB_8.'-&raquo; <input type="text" name="auto_expire_time"  value="'.$auto_expire_time.'" maxlength="4" size="4"'. "\n";
				echo TAB_9.' title="Enter a expiry amount time amount here" '.$greyed_2.' id="InputExpireTime" />'."\n";
				
			echo TAB_8.'<select name="auto_expire_unit"  >' . "\n";


										
			foreach ($time_units_array as $time_unit_name => $time_unit)
			{
				if ($auto_expire_unit == $time_unit)	
				{$selected = 'selected="selected"';}
				else {$selected = '';}
				
				echo TAB_7.'<option '.$greyed_2.' class="InputTimeUnits" value="'.$time_unit.'" '.$selected.'>'.$time_unit_name.'</option>' . "\n";
			}
			
			echo TAB_8.'</select> after the specified '.$alias.' date and time (as set above)</p>' . "\n";
			
			//	Does not expire
			echo TAB_8.'<p><input type="radio" id="RadioNoExpire" name="stop_time_select" value="never" '.$checked_3.'/>' . "\n";
			echo TAB_8.' -&raquo; Aways show (does not expire)</p>'."\n";
								
		echo TAB_7.'</fieldset>'."\n";
		
		echo TAB_7.'<p style="clear: left;"><br/> * Enter dates as: dd-mm-yyyyy or 1 jan 2015</p>'."\n";
?>