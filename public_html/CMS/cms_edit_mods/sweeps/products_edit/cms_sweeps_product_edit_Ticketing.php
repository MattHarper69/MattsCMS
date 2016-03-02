<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		
	//	RESET button ======================================================				
	echo TAB_7.'<a  href="'.$this_page.'?item_id='.$_REQUEST['item_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;tab='.$tab.'"'."\n";
		echo TAB_8.' title="Reload this page to Reset all '.SHOP_ITEM_ALIAS.' data" >' ."\n";
		echo TAB_8.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
	echo TAB_7.'</a>'. "\n";	

	//	Max Quantity
	echo TAB_7.'<fieldset class="AdminForm3">'."\n";
	
				echo TAB_9.'<script type="text/javascript">
					
					$(document).ready( function()
					{								
						$("#CheckSetTicketStart").click(function() {
							if(!$("#CheckSetTicketStart").attr("checked"))
							{
								//$("#TicketStart").val("");
								$("#SetTicketStart").hide();
							}
							else
							{
								$("#SetTicketStart").show();
							}
								
						});
						
/* 						$("#TicketStart").keyup(function() {
							if($(this).val() != "")
							{
								$("#CheckSetTicketStart").attr("checked", true);
							}
							else
							{
								$("#CheckSetTicketStart").attr("checked", false);
							}
						});	 */				
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
		
		echo TAB_9.'<input type="checkbox" name="CheckSetTicketStart" id="CheckSetTicketStart"'.$checked.' />' . "\n";		
		echo TAB_8.' - Alter the Ticket Start number for this '.SHOP_ITEM_ALIAS . "\n"; 
		echo TAB_8.'<span id="SetTicketStart"'.$style.' > to : '. "\n";
		echo TAB_8.'<input type="text" name="ticket_start" id="TicketStart" value="'.$sweeps_item_info['ticket_start'].'"'."\n";
			echo TAB_9.' maxlength="11" size="10" title="Set a new Ticket Start Number for this '.SHOP_ITEM_ALIAS.'" />'."\n";
		echo TAB_8.'</span>' . "\n";
	echo TAB_7.'</fieldset>'."\n";	

	//	numbers of qualifiers
	echo TAB_7.'<fieldset class="AdminForm3" style="clear: left;">'."\n";		
		echo TAB_8.'Number of Qualifiers for this '.SHOP_ITEM_ALIAS .' : ' . "\n"; 
		echo TAB_8.'<input type="text" name="num_qualifiers" id="TicketStart" value="'.$sweeps_item_info['num_qualifiers'].'"'."\n";
			echo TAB_9.' maxlength="11" size="10" title="Set the Number of Qualifiers for this '.SHOP_ITEM_ALIAS.'" />'."\n";
	echo TAB_7.'</fieldset>'."\n";	

	//	Starters per qualifier
	echo TAB_7.'<fieldset class="AdminForm3" style="clear: left;">'."\n";		
		echo TAB_8.'Number of Starters per Qualifier for this '.SHOP_ITEM_ALIAS .' : ' . "\n"; 
		echo TAB_8.'<input type="text" name="starters_per_qualifier" id="TicketStart" value="'.$sweeps_item_info['starters_per_qualifier'].'"'."\n";
			echo TAB_9.' maxlength="11" size="10" title="Set the number of Starters per Qualifier for this '.SHOP_ITEM_ALIAS.'" />'."\n";
	echo TAB_7.'</fieldset>'."\n";	
	
?>