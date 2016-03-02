<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		
	//	RESET button ======================================================				
	echo TAB_7.'<a  href="'.$this_page.'?item_id='.$_REQUEST['item_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;tab='.$tab.'"'."\n";
		echo TAB_8.' title="Reload this page to Reset all '.SHOP_ITEM_ALIAS.' data" >' ."\n";
		echo TAB_8.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
	echo TAB_7.'</a>'. "\n";	

	//	need for Calendar Popup
	echo TAB_7.'<script type="text/javascript" >document.write(getCalendarStyles());</script>'."\n";
	echo TAB_7.'<div id="CancelDatePopup" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">'."\n";
	echo TAB_7.'</div>'."\n"; 		
	
	//	Max Quantity
	echo TAB_7.'<fieldset class="AdminForm3">'."\n";
	
				echo TAB_9.'<script type="text/javascript">
					
					$(document).ready( function()
					{								
						$("#CheckSetMaxQuantity").click(function() {
							if(!$("#CheckSetMaxQuantity").attr("checked"))
							{
								$("#MaxQuantity").val("");
								$("#SetMaxQuantity").hide();
							}
							else
							{
								$("#SetMaxQuantity").show();
							}
								
						});
						
						$("#MaxQuantity").keyup(function() {
							if($(this).val() != "")
							{
								$("#CheckSetMaxQuantity").attr("checked", true);
							}
							else
							{
								$("#CheckSetMaxQuantity").attr("checked", false);
							}
						});					
					});		
				</script>'."\n";				
				
		if ($sweeps_item_info['max_quantity_allow'] != '' AND $sweeps_item_info['max_quantity_allow'] != 0)
		{
			$checked = ' checked="checked"';
			$style = '';
		}
		else 
		{
			$checked = '';
			$style = ' style="display: none;"';
		}
		
		echo TAB_9.'<input type="checkbox" name="CheckSetMaxQuantity" id="CheckSetMaxQuantity"'.$checked.' />' . "\n";		
		echo TAB_8.' - Set the maximum allowable quantity per order for this '.SHOP_ITEM_ALIAS . "\n"; 
		echo TAB_8.'<span id="SetMaxQuantity"'.$style.' > of : '. "\n";
		echo TAB_8.'<input type="text" name="max_quantity_allow" id="MaxQuantity" value="'.$sweeps_item_info['max_quantity_allow'].'"'."\n";
			echo TAB_9.' maxlength="11" size="10" title="Set the maximum allowable quantity per order for this '.SHOP_ITEM_ALIAS.'" />'."\n";
		echo TAB_8.'</span>' . "\n";
	echo TAB_7.'</fieldset>'."\n";	
	
	//	Timing:		
	echo TAB_7.'<fieldset class="AdminForm3" style="clear: left;" >'."\n";
	
		echo TAB_8.'<h4>Timing:</h4>'. "\n";
		
		//	Event Date
		echo TAB_8.'<fieldset class="AdminForm3">'."\n";
			echo TAB_9.'Date of '.SHOP_ITEM_ALIAS.' : '."\n"; 
			
			if ($_REQUEST['item_id'] == 'new') {$event_date = date("d-m-Y");}
			else {$event_date = date("d-m-Y",strtotime($sweeps_item_info['event_date']));}
			
			echo TAB_9.'<input type="text" name="event_date" value="'.$event_date.'" maxlength="10" size="10"' . "\n";
			echo TAB_9.' title="Insert a date for this '.SHOP_ITEM_ALIAS.' by clicking the date icon" />'."\n";
			
			//	do javascript date picker						
			echo TAB_9.'<script type="text/javascript">var EventDate = new CalendarPopup("CancelDatePopup");'
					  .'</script>' ."\n"; 
							
			echo TAB_9.'<a href="#" onClick="EventDate.select('
						.'document.forms[0].event_date,\'anchor_EventDate\',\'dd-MM-yyyy\'); return false;"'
						.' id="anchor_EventDate" title="Click to insert a Date" >' ."\n";  
				echo TAB_10.'<img src="/images_misc/icon_calendar_32x32.png" alt="insert date" />' ."\n"; 
			echo TAB_9.'</a>' ."\n";
			
		echo TAB_8.'</fieldset>'."\n";
		
		//	Sale start date
		echo TAB_8.'<fieldset class="AdminForm3" style="clear: both;" >'."\n";
			echo TAB_9.'Start Date of Sale: '."\n"; 
			
			if ($_REQUEST['item_id'] == 'new') {$start_date = date("d-m-Y");}
			else {$start_date = date("d-m-Y",strtotime(substr($sweeps_item_info['event_start_date'], 0, 10)));}
			 
			echo TAB_9.'<input type="text" name="event_start_date" value="'.$start_date.'" maxlength="10" size="10"' . "\n";
			echo TAB_9.' title="Insert a date for this '.SHOP_ITEM_ALIAS.' to go on sale by clicking the date icon" />'."\n";
			
			//	do javascript date picker						
			echo TAB_9.'<script type="text/javascript">var StartDate = new CalendarPopup("CancelDatePopup");'
					  .'</script>' ."\n"; 
							
			echo TAB_9.'<a href="#" onClick="StartDate.select('
						.'document.forms[0].event_start_date,\'anchor_StartDate\',\'dd-MM-yyyy\'); return false;"'
						.' id="anchor_StartDate" title="Click to insert a Date" >' ."\n";  
				echo TAB_10.'<img src="/images_misc/icon_calendar_32x32.png" alt="insert date" style="line-height: 0;"/>' ."\n"; 
			echo TAB_9.'</a>' ."\n";
			
			//	Time
			echo TAB_9.' @ Time: '."\n"; 
			
			if ($_REQUEST['item_id'] == 'new') {$start_time = '9:00am';}
			else{$start_time = date("g:ia", strtotime(substr($sweeps_item_info['event_start_date'], 11, 5)));}
			 
			echo TAB_9.'<input type="text" name="event_start_time" value="'.$start_time.'" maxlength="7" size="8"' . "\n";
			echo TAB_9.' title="Insert a date for this '.SHOP_ITEM_ALIAS.' to go on sale by clicking the date icon" />'."\n";				
		echo TAB_8.'</fieldset>'."\n";			

		//	Sale Close date
		echo TAB_8.'<fieldset class="AdminForm3" style="clear: both;" >'."\n";
			echo TAB_9.'Close Date of Sale: '."\n"; 
			
			if ($_REQUEST['item_id'] == 'new') {$close_date = date("d-m-Y", time() + 2419200);}
			else {$close_date = date("d-m-Y",strtotime(substr($sweeps_item_info['event_close_date'], 0, 10)));}
			
			echo TAB_9.'<input type="text" name="event_close_date" value="'.$close_date.'" maxlength="10" size="10"' . "\n";
			echo TAB_9.' title="Insert a date for this '.SHOP_ITEM_ALIAS.' to go on sale by clicking the date icon" />'."\n";
			
			//	do javascript date picker						
			echo TAB_9.'<script type="text/javascript">var CloseDate = new CalendarPopup("CancelDatePopup");'
					  .'</script>' ."\n"; 
							
			echo TAB_9.'<a href="#" onClick="CloseDate.select('
						.'document.forms[0].event_close_date,\'anchor_CloseDate\',\'dd-MM-yyyy\'); return false;"'
						.' id="anchor_CloseDate" title="Click to insert a Date" >' ."\n";  
				echo TAB_10.'<img src="/images_misc/icon_calendar_32x32.png" alt="insert date" />' ."\n"; 
			echo TAB_9.'</a>' ."\n";
			
			//	Time
			echo TAB_9.' @ Time: '."\n"; 
			
			if ($_REQUEST['item_id'] == 'new') {$close_time = '5:00pm';}
			else{$close_time = date("g:ia", strtotime(substr($sweeps_item_info['event_close_date'], 11, 5)));}
			 
			echo TAB_9.'<input type="text" name="event_close_time" value="'.$close_time.'" maxlength="7" size="8"' . "\n";
			echo TAB_9.' title="Insert a date for this '.SHOP_ITEM_ALIAS.' to go on sale by clicking the date icon" />'."\n";				
		echo TAB_8.'</fieldset>'."\n";			

		echo TAB_8.'<p class="Small" style="clear: both;">* Dates should be in the format of dd-mm-yyyyy or 1 jan </p>'."\n";
		
	echo TAB_7.'</fieldset>'."\n";		


	//	Location:		
	echo TAB_7.'<fieldset class="AdminForm3">'."\n";
	
		echo TAB_8.'<h4>Location Ristrictions:</h4>'. "\n";

		////	Enabled Countries  ---------------------------------
		//	get all enabled Countries for this item from db;			
		$sql_statement = 'SELECT'
									.'  shop_address_countries.country_id'
									.', country_name'
								
									.' FROM shop_address_countries, sweeps_item_location_restrictions'

									.' WHERE sweeps_item_location_restrictions.item_id = "'.$_REQUEST['item_id'].'"'
									.' AND sweeps_item_location_restrictions.country_id = shop_address_countries.country_id'
									.' AND shop_address_countries.active = "on"'
									//.' AND mod_id = "'.$_GET['e'].'"'
									.' ORDER BY country_name'
									;

		$exc_countries = FALSE;							
		$exc_country_result = ReadDB ($sql_statement, $mysql_err_msg);
		if (mysql_num_rows($exc_country_result) > 0)
		{
			$exc_countries = array();
			while ($exc_country_info = mysql_fetch_array($exc_country_result))
			{
				$exc_countries[$exc_country_info['country_id']] = $exc_country_info['country_name'];
			}		
		}

		
		//	get all Countries from db
		$mysql_err_msg = SHOP_ITEM_ALIAS.' Excuded Countries Information not found';
		//	get all enabled Countries for this item from db;			
		$sql_statement = 'SELECT'
									.'  country_id'
									.', country_name'
								
									.' FROM shop_address_countries'
								
									.' ORDER BY country_name'
									;

		$all_country_result = ReadDB ($sql_statement, $mysql_err_msg);	

		$num_countries_found = mysql_num_rows($all_country_result);
		if (!$num_countries_found)
		{
			echo TAB_9.'<p>( There are no Countries configured )</p>'. "\n";
		}
		else
		{
			
			echo TAB_8.'<fieldset class="AdminForm3">'."\n";
				echo TAB_9.'<p>Enabled Countries:</p>'. "\n";
				echo TAB_9.'<p><a class="OpenCloseNextDiv" href="#">'."\n";
					echo TAB_10.'<img src="/images_misc/icon_add_24x24.png" alt="Add" />'."\n";
					echo TAB_10.'[ Add Countries to this list ]'."\n";
				echo TAB_9.'</a></p>'."\n";
				echo TAB_9.'<fieldset class="AdminForm3 HideAtStart">'."\n";

					echo TAB_10.'<script type="text/javascript">

						$(document).ready(function() {

							//	Country Multi Select
							$("#AddCountries").asmSelect({
								addItemTarget: "bottom",
								animate: true,
								highlight: false,
								sortable: true

								}).after($("<a href=\'#\'>[ Select All ]</a>").click(function() {
									$("#AddCountries").children().attr("selected", "selected").end().change();
									return false;
								})); 

							$("#SubmitAddCountries").css("display" , "none");
							$("#AddCountries").change( function(){
							
								$("#SubmitAddCountries").css("display" , "inline");
							
							});
							
							
							//	Select all Check Boxes
							$("#country_check_all_master:checkbox").change(function() {
								$(".country_check_all").attr("checked", $(this).attr("checked"));	
							});
							
							//	Warning for Select all Check Boxes 
							$("#country_check_all_master:checkbox").click(function() {
								if($("#country_check_all_master:checkbox").attr("checked"))
								{
									alert("You have selected Remove ALL:"
										+ "\n - Clicking the Update Button will Remove ALL Enabled Countries for this '.SHOP_ITEM_ALIAS.'"
										+ "\n - at least one Enabled Country needs to be set per '.SHOP_ITEM_ALIAS.'");
								}					
							});							
																					
						}); 

					</script>' ."\n";
/* 				
					echo TAB_10.'<input type="submit" id="SubmitAddCountries" name="submit_add_countries"' ."\n";
						echo TAB_11.' value="Add Selected Countries" title="Add these Countries to the exclusion list for this '
						.SHOP_ITEM_ALIAS.'" />'. "\n";	
 */
					echo TAB_10.'<p class="Small" id="SubmitAddCountries">Click the Update Button to Add these Countries</p>'. "\n";	
						
					echo TAB_10.'<select id="AddCountries" name="add_countries[]" multiple="multiple" title="Select Countries to add" >' ."\n";
				
					$flipped_coutries = FALSE;
					if($exc_countries) {$flipped_coutries = array_flip($exc_countries);}
					
					while ($all_countries = mysql_fetch_array($all_country_result))
					{
						if($flipped_coutries)
						{
							if (!in_array($all_countries['country_id'], $flipped_coutries))
							{
								echo TAB_10.'<option value="'.$all_countries['country_id'].'">'.$all_countries['country_name'].'</option>'."\n";
							}						
						}

						else
						{
							echo TAB_10.'<option value="'.$all_countries['country_id'].'">'.$all_countries['country_name'].'</option>'."\n";
						}
							
					}
							
					echo TAB_10.'</select>' ."\n";
					
					echo TAB_10.'<input type="checkbox" name="load_all_countries_states" checked="cnecked" />' ."\n";
					echo TAB_10.'<span> - Enable all States/Provinces<br/>for selected Countries</span>' ."\n";					

				echo TAB_9.'</fieldset>'."\n";
				
				//	current enabled countries list
				echo TAB_9.'<fieldset class="AdminForm3" style="clear: both;">'."\n";
					
				$num_countries_found = mysql_num_rows($exc_country_result);
				if (!$num_countries_found)
				{
					echo TAB_10.'<p class="WarningMSG">No Countries Enabled for this '.SHOP_ITEM_ALIAS.'</p>'. "\n";
				}
				else
				{
					echo TAB_10.'<table>'. "\n";
					
						echo TAB_11.'<tr><th style="padding-right: 20px;" >Enabled Countries:</th><th>remove</th></tr>'. "\n";
					
					$count = 0;
					foreach ($exc_countries as $id => $name)
					{
						echo TAB_11.'<tr>'. "\n";
							echo TAB_12.'<td>'.$name.'</td>' . "\n";
							echo TAB_12.'<td align="center" >' . "\n";
								echo TAB_13.'<input type="checkbox" class="country_check_all" name="remove_country_'.$count.'"' ."\n"; 
								echo TAB_13.' title="Remove this Country from the enabled List" />'."\n";
								echo TAB_13.'<input type="hidden" name="country_id_'.$count.'" value="'.$id.'" />' ."\n";
							echo TAB_12.'</td>'."\n";
						echo TAB_11.'</tr>'. "\n";
						
						$count++;
					}
					
						echo TAB_11.'<tr><th align="right">remove ALL:</th>'. "\n";
							echo TAB_12.'<th>'. "\n";
								echo TAB_13.'<input type="checkbox" id="country_check_all_master"'. "\n";
								echo TAB_13.' title="Remove ALL Countries from the enabled List" />'. "\n";
								echo TAB_13.'<input type="hidden" name="num_cunts" value="'.$count .'" />' ."\n";
							echo TAB_12.'</th>'. "\n";
						echo TAB_11.'</tr>'. "\n";
						
					echo TAB_10.'</table>'. "\n";
				}
									
				echo TAB_9.'</fieldset>'."\n";				
				
			echo TAB_8.'</fieldset>'."\n";
		
		}
		
		////	Enabled States  ---------------------------------
		//	get all enabled States for this item from db;			
		$sql_statement = 'SELECT'
									.'  shop_address_states.state_id'
									.', state_name'
								
									.' FROM shop_address_states, sweeps_item_location_restrictions'

									.' WHERE sweeps_item_location_restrictions.item_id = "'.$_REQUEST['item_id'].'"'
									.' AND sweeps_item_location_restrictions.state_id = shop_address_states.state_id'
									.' AND shop_address_states.active = "on"'
									//.' AND mod_id = "'.$_GET['e'].'"'
									.' ORDER BY shop_address_states.country_id, state_name'
									;

		$exc_state_result = ReadDB ($sql_statement, $mysql_err_msg);	
		while ($exc_state_info = mysql_fetch_array($exc_state_result))
		{
			$exc_states[$exc_state_info['state_id']] = $exc_state_info['state_name'];
		}
		
		//	get all States from db
		$mysql_err_msg = SHOP_ITEM_ALIAS.' Excuded States Information not found';
		//	get all enabled States for this item from db;			
		$sql_statement = 'SELECT'
									.'  state_id'
									.', state_name'
								
									.' FROM shop_address_states'
								
									.' ORDER BY country_id, state_name'
									;

		$all_state_result = ReadDB ($sql_statement, $mysql_err_msg);	

		$num_states_found = mysql_num_rows($all_state_result);
		if (!$num_states_found)
		{
			echo TAB_9.'<p>( There are no States configured )</p>'. "\n";
		}
		else
		{
			
			echo TAB_8.'<fieldset class="AdminForm3" style="float: right;" >'."\n";
				echo TAB_9.'<p>Enabled States/Provinces:</p>'. "\n";	
				echo TAB_9.'<p><a class="OpenCloseNextDiv" href="#">'."\n";
					echo TAB_10.'<img src="/images_misc/icon_add_24x24.png" alt="Add" />'."\n";
					echo TAB_10.'[ Add States to this list ]'."\n";
				echo TAB_9.'</a></p>'."\n";
				echo TAB_9.'<fieldset class="AdminForm3 HideAtStart">'."\n";

					echo TAB_10.'<script type="text/javascript">

						$(document).ready(function() {

							$("#AddStates").asmSelect({
								addItemTarget: "bottom",
								animate: true,
								highlight: false,
								sortable: true

								}).after($("<a href=\'#\'>[ Select All ]</a>").click(function() {
									$("#AddStates").children().attr("selected", "selected").end().change();
									return false;
								})); 

							$("#SubmitAddStates").css("display" , "none");
							$("#AddStates").change( function(){
							
								$("#SubmitAddStates").css("display" , "inline");
							
							});
							
							
							//	Select all Check Boxes
							$("#state_check_all_master:checkbox").change(function() {
								$(".state_check_all").attr("checked", $(this).attr("checked"));	
							});
							
							//	Warning for Select all Check Boxes 
							$("#state_check_all_master:checkbox").click(function() {
								if($("#state_check_all_master:checkbox").attr("checked"))
								{
									alert("You have selected Remove ALL:"
										+ "\n - Clicking the Update Button will Remove ALL Enabled States for this '.SHOP_ITEM_ALIAS.'"
										+ "\n - at least one Enabled State needs to be set per '.SHOP_ITEM_ALIAS.'");
								}					
							});	
							
						}); 

					</script>' ."\n";
/* 				
					echo TAB_10.'<input type="submit" id="SubmitAddStates" name="submit_add_states"' ."\n";
						echo TAB_11.' value="Add Selected States" title="Add these States to the exclusion list for this '
						.SHOP_ITEM_ALIAS.'" />'. "\n";	
 */	
					echo TAB_10.'<p class="Small" id="SubmitAddStates">Click the Update Button to Add these States</p>'. "\n";
					
					echo TAB_10.'<select id="AddStates" name="add_states[]" multiple="multiple" title="Select States to add" >' ."\n";
			
					if (isset($exc_states)) {$flipped_states = array_flip($exc_states);}
					while ($all_states = mysql_fetch_array($all_state_result))
					{
						if(isset($flipped_states))
						{
							if (!in_array($all_states['state_id'], $flipped_states))
							{
								echo TAB_10.'<option value="'.$all_states['state_id'].'">'.$all_states['state_name'].'</option>'."\n";				
							}
			
						}
						else
						{
							echo TAB_10.'<option value="'.$all_states['state_id'].'">'.$all_states['state_name'].'</option>'."\n";
						}

					}
							
					echo TAB_10.'</select>' ."\n";
					
				echo TAB_9.'</fieldset>'."\n";
				
				//	current enabled states list
				echo TAB_9.'<fieldset class="AdminForm3" style="clear: both;">'."\n";
					
				$num_states_found = mysql_num_rows($exc_state_result);
				if (!$num_states_found)
				{
					echo TAB_10.'<p class="WarningMSG" >No States Enabled for this '.SHOP_ITEM_ALIAS.'</p>'. "\n";
				}
				else
				{
					echo TAB_10.'<table>'. "\n";
					
						echo TAB_11.'<tr><th style="padding-right: 20px;" >Enabled States:</th><th>remove</th></tr>'. "\n";
					
					$count = 0;
					foreach ($exc_states as $id => $name)
					{
						echo TAB_11.'<tr>'. "\n";
							echo TAB_12.'<td>'.$name.'</td>' . "\n";
							echo TAB_12.'<td align="center" >' . "\n";
								echo TAB_13.'<input type="checkbox" class="state_check_all" name="remove_state_'.$count.'"' ."\n"; 
								echo TAB_13.' title="Remove this State from the enabled List" />'."\n";
								echo TAB_13.'<input type="hidden" name="state_id_'.$count.'" value="'.$id.'" />' ."\n";
							echo TAB_12.'</td>'."\n";
						echo TAB_11.'</tr>'. "\n";
						
						$count++;
					}
					
						echo TAB_11.'<tr><th align="right">remove ALL:</th>'. "\n";
							echo TAB_12.'<th>'. "\n";
								echo TAB_13.'<input type="checkbox" id="state_check_all_master"'. "\n";
								echo TAB_13.' title="Remove ALL States from the enabled List" />'. "\n";
								echo TAB_13.'<input type="hidden" name="num_states" value="'.$count .'" />' ."\n";
							echo TAB_12.'</th>'. "\n";
						echo TAB_11.'</tr>'. "\n";
						
					echo TAB_10.'</table>'. "\n";
				}
									
				echo TAB_9.'</fieldset>'."\n";				
				
			echo TAB_8.'</fieldset>'."\n";
		
		}
					
	echo TAB_7.'</fieldset>'."\n";			

?>