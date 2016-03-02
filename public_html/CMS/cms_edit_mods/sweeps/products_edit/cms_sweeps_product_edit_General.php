<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		
		//	RESET button ======================================================				
		echo TAB_7.'<a  href="'.$this_page.'?item_id='.$_REQUEST['item_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;tab='.$tab.'"'."\n";
			echo TAB_8.' title="Reload this page to Reset all '.SHOP_ITEM_ALIAS.' data" >' ."\n";
			echo TAB_8.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
		echo TAB_7.'</a>'. "\n";	

	
		//	edit Name 		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";
			echo TAB_8.SHOP_ITEM_ALIAS.' Name: <input type="text" name="item_name" value="'.$sweeps_item_info['item_name'].'"' . "\n";
			echo TAB_8.' size="30" title="Add or Edit the '.SHOP_ITEM_ALIAS.'&#39;s display Name here" /> '."\n";
		echo TAB_7.'</fieldset>'."\n";
	
		//	edit Product code 		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";
			echo TAB_8.''.SHOP_ITEM_ALIAS.' Code: <input type="text" name="item_code" value="'.$sweeps_item_info['item_code'].'"' . "\n";
			echo TAB_8.' size="10" title="Add or Edit the '.SHOP_ITEM_ALIAS.'&#39;s reference Code here" /> '."\n";
		echo TAB_7.'</fieldset>'."\n";
				
		//	Active
		if ($sweeps_item_info['active'] == 'on' OR $_REQUEST['item_id'] == 'new') { $checked = ' checked="checked"'; }
		else { $checked = '';}
		
		echo TAB_7.'<fieldset class="AdminForm3" title="Uncheck this box to hide this '.SHOP_ITEM_ALIAS.'" >'."\n";
			echo TAB_8.'<input type="checkbox" name="active" '.$checked.' /> : Set this '.SHOP_ITEM_ALIAS.' as ACTIVE'."\n";
		echo TAB_7.'</fieldset>'."\n";	

		//	URL Alias
		// if in Clone mode,  URL alias  should be unique
		if (!isset($_REQUEST['clone']))
		{ $url_alias = $sweeps_item_info['url_alias']; }
		else 
		{ $url_alias = $sweeps_item_info['url_alias'] . '2'; }
		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";
			echo TAB_8.SHOP_ITEM_ALIAS.'&#39;s display Page URL: &quot;http://'.SITE_URL.' / <input type="text" name="url_alias"' . "\n";
			echo TAB_9.'value="'.$url_alias.'" size="20" title="Add or Edit the '.SHOP_ITEM_ALIAS.'&#39;s Page URL here" />&quot; '."\n";
		echo TAB_7.'</fieldset>'."\n";			
		
		//	Asign a cat OR Select The Main (primary) Cat 
		echo TAB_7.'<fieldset class="AdminForm3" style="clear: left;">'."\n";

			$all_cat_details = FillTheCatList(0,0);	//-----function to list all cats and sub-cats			
			
			//	NO Categories created
			if (count($all_cat_details) < 1)
			{
				echo TAB_5.'<span class="WarningMSGSmall" >No Categories created'
							.' - you will need to create at least one Category before any '.SHOP_ITEM_ALIAS.'s can be assigned to it.</span> ';
			}	
			
			//	Asign a cat
			elseif ($not_asigned OR $_REQUEST['item_id'] == 'new')
			{
				echo TAB_4.'Assign a Category to this '.SHOP_ITEM_ALIAS.': <select name="asign_a_cat" >' ."\n";
					
					$cat_details = FillTheCatList(0,0);	//-----function to list all cats and sub-cats

					echo TAB_6.'<option selected="selected" ></option>'."\n";					
					
					for ($i = 0; $i  < count($cat_details); $i++)
					{
						//	Indent Sub Categories
						$indent = '';
						for ($j = 0; $j < $cat_details[$i][7]; $j++) {$indent .= '&nbsp;&nbsp;';} 						
						{				
							echo TAB_6.'<option value="'.$cat_details[$i][0].'" >'.$indent.'-&nbsp;'.$cat_details[$i][1].'</option>'."\n";	
						}				

					}
							
				echo TAB_4.'</select>' ."\n";
				
			}
			
			//	Select The Main (primary) Cat
			else
			{
				echo TAB_4.'Select this '.SHOP_ITEM_ALIAS.'&#39;s main Category: <select name="primary_cat_id" >' ."\n";

				foreach ($asign_cat_info_array as $cat_id => $cat_name)
				{
					
					if ( $cat_id == $sweeps_item_info['primary_cat_id']) 
					{ 
						$selected = ' selected="selected"';
					}
					else 
					{ 
						$selected = '';	
					}
					echo TAB_6.'<option value="'.$cat_id.'"'.$selected.' >'.$cat_name.'</option>'."\n";	

				}
							
				echo TAB_4.'</select>' ."\n";
				
			}
			
		echo TAB_7.'</fieldset>'."\n";			
		
		// display InStock
		echo TAB_7.'<fieldset class="AdminForm3" >'."\n";						
			if ($sweeps_item_info['display_instock'] == 'on') { $checked = ' checked="checked"'; }
			else { $checked = '';}			

			echo TAB_8.'<input type="checkbox" name="display_instock" '.$checked.' />'."\n";
			echo TAB_8.' : Display the '.SHOP_ITEM_ALIAS.'&#39;s In-stock value of : '."\n";

			if ($sweeps_item_info['in_stock'] == 0 AND $_REQUEST['item_id'] != 'new'){$red_text = ' style="color:red;"';}
			else {$red_text = '';}	
			if($_REQUEST['item_id'] == 'new'){$in_stock = 10;}
			else { $in_stock = $sweeps_item_info['in_stock'];}
				
			echo TAB_8.'<input type="text" name="in_stock" value="'.$in_stock.'"'.$red_text.' size="8" maxlength="11" />'."\n";
			
		echo TAB_7.'</fieldset>'."\n";	
			
		// display Buy Now button
		echo TAB_7.'<fieldset class="AdminForm3" title="Check this box to display a &#39;Buy Now&#39; this '.SHOP_ITEM_ALIAS.'">'."\n";

			if ($sweeps_item_info['display_buynow'] == 'on' OR $_REQUEST['item_id'] == 'new') { $checked = ' checked="checked"'; }
			else { $checked = '';}			
			echo TAB_8.'<input type="checkbox" name="display_buynow" '.$checked.' /> : Display the '.SHOP_ITEM_ALIAS.'&#39;s BUY NOW button'."\n";
		echo TAB_7.'</fieldset>'."\n";

		//	Promotional List
		if ($sweeps_item_info['promo_display'] == 'on') { $checked = ' checked="checked"'; }
		else { $checked = '';}
		
		echo TAB_7.'<fieldset class="AdminForm3" title="Checking this box to display this '.SHOP_ITEM_ALIAS.' in the online shop promo page">'."\n";
			echo TAB_8.'<input type="checkbox" name="promo_display" '.$checked.' /> : List this '.SHOP_ITEM_ALIAS.' in The Promo Listing'."\n";
		echo TAB_7.'</fieldset>'."\n";			


?>