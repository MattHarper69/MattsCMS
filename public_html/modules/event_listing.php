<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	$div_name = 'EventListing_'.$mod_id;

	
	echo "\n";
	echo TAB_7.'<!--	START Event Listing code 	-->'."\n";
	echo "\n";
		
	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{
	
		$can_not_clone = 0;
		$edit_enabled = 1;	
		$mod_locked = 2;			
		
		$hover_class = ' HoverShow';		

		//	Display / Hide In-Active Mods
		include ('CMS/cms_inactive_mod_display.php');

		//	Show Div Mod Button
		echo TAB_7.'<div id="EditDivModDisplay_'.$mod_id.'" class="EditDivModDisplay"' 
					.' title="Click to Edit this &quot;Event Listing&quot; Module">'."\n";
			
			echo TAB_8.'<p style="background-color:#ffffff;color:#aa44aa; cursor: pointer;"'
			.' onClick="javascript:selectMod2Edit(35, '.$mod_id.',\''.$div_name.'\' ,0, 2);">'
			.'[ &quot;Event Listing&quot; Module (click to edit) ]<p>'."\n";
			
		echo TAB_7.'</div>'."\n";
		
		//	Do mod editing Toolbar
		include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
		
		//	Do Mod Config Panel
		include ('CMS/cms_panels/cms_panel_mod_config.php');
		
	}

	else
	{$hover_class = '';}
	
	echo TAB_7.'<div class="EventListing'.$hover_class.'" id="EventListing_'.$mod_id.'" >'."\n";	
	
	$theres_events = FALSE;

	//	 get event listing settings
	$mysql_err_msg = 'Event Listing Settings unavailable';	
	$sql_statement = 'SELECT * FROM mod_event_list_config'

												.' WHERE mod_id = "'.$mod_id.'"'
												;
					
	//if ($event_settings = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg)))
	$event_settings = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
	//{
		
		//------------set default time zone
		date_default_timezone_set($event_settings['time_zone']);
		


			//	 get all events to display
			$mysql_err_msg = 'Event Listing unavailable';	
			$sql_statement = 'SELECT * FROM mod_event_list'
										
										.' WHERE mod_id = "'.$mod_id.'"'
										.' AND active = "on"'
										.' ORDER BY '.$event_settings['display_by']
										;
				
			$event_listing_result = ReadDB ($sql_statement, $mysql_err_msg);

		$event_html = '';



		//	Do Listing Heading
		if ($event_settings['heading'] != '' AND $event_settings['heading'] != NULL)
		{
			echo TAB_8.'<h2 id="EventListHeading" >'.$event_settings['heading'].'</h1>'."\n";
		}

		//	Do text 1
		if ($event_settings['text_1'] != '' AND $event_settings['text_1'] != NULL)
		{
			echo TAB_8.'<p id="EventListText_1" >'.$event_settings['text_1'].'</p>'."\n";
		}	



		
		if (mysql_num_rows ($event_listing_result) > 0)
		{

	
			//	Start Table				
			$event_html .= TAB_8.'<table class="EventListTable" >' ."\n";
			
			if ($event_settings['display_heads'] == 'on')
			{
				$event_html .= TAB_9.'<tr class="EventListHead" >'."\n";
			
					$event_html .= TAB_10.'<th></th>'."\n";
					$event_html .= TAB_10.'<th>'.$event_settings['event_alias'].'</th>'."\n";
				
				if ($event_settings['display_date'] == 'on')
				{
					$event_html .= TAB_10.'<th>Date</th>'."\n";
				}					
				if ($event_settings['display_time'] == 'on')
				{
					$event_html .= TAB_10.'<th>Time</th>'."\n";
				}	

				for ($i = 1; $i < 6; $i++)
				{
					if ($event_settings['field_active_'.$i] == 'on')
					{
						$event_html .= TAB_10.'<th>' . $event_settings['field_head_'.$i] . '</th>'."\n";
					}					
				
				}
					$event_html .= TAB_10.'<th></th>'."\n";
					
				$event_html .= TAB_9.'</tr>'."\n";
		
			}
			

			$alt_BG_count = 1;
			//	display Item info in Listing
			while ($event_listing_info = mysql_fetch_array ($event_listing_result))
			{							


				
				//	check if with time and date and not expired...............
				$time_now = time();
									
				//	Start time
				$start_time = strtotime($event_listing_info['active_start']);
								
				//	set finish time or auto expiry
				if ($event_listing_info['auto_expire_on'] == 'on')
				{
					$end_time = strtotime($event_listing_info['time']) 
								+ $event_listing_info['auto_expire_time'] * $event_listing_info['auto_expire_unit'];
				}
				else
				{
					$end_time = strtotime($event_listing_info['active_end']);
				}
			
				if( ($time_now > $start_time AND $time_now < $end_time) OR $event_listing_info['auto_expire_on'] == 'no')
				{
					
					if ($alt_BG_count % 2)
					{$alt_BG_class = ' ShopItemListAltRow';}
					else {$alt_BG_class = '';}
					
					$event_html .= TAB_9.'<tr class="EventListRow'.$alt_BG_class.'" >'."\n";
					
						//	create Link code
						if ($event_listing_info['more_info_on'] == 'on')
						{
							$href = 'modules/event_listing_more.php?p='.$page_id
									.'&amp;mod_id='.$mod_id.'&amp;event_id='.$event_listing_info['event_id'];
							$href_tag1 = TAB_11.'<a href="'.$href.'" rel="EventListMoreInfo1" >'."\n" . TAB_1;
							$href_tag2 = TAB_11.'<a href="'.$href.'" rel="EventListMoreInfo2" >'."\n" . TAB_1;
							$href_tag3 = TAB_11.'<a href="'.$href.'" rel="EventListMoreInfo3" >'.$event_settings['more_info_text'].'</a>'."\n";
							$href_end_tag = TAB_11.'</a>'."\n";
						}
						else
						{
							$href_tag1 = '';
							$href_tag2 = '';
							$href_tag3 = '';
							$href_end_tag = ''."\n";								
						}						
					
					
					//	Thumbnail image
						$event_html .= TAB_10.'<td>'."\n";
						

						
						if 
						(
								$event_settings['display_image'] == 'on' 
							AND file_exists('_images_user/thumbs/_list_event_id_' .$event_listing_info['event_id']. '.jpg')	
						)
						
						{
							$event_html .= $href_tag1;
							$event_html .= TAB_11.'<img src="/_images_user/thumbs/_list_event_id_' .$event_listing_info['event_id']. '.jpg"'."\n";
								$event_html .= TAB_13.' alt="thumbnal image for: '.$event_listing_info['display_name'].'" />'."\n";
							$event_html .= $href_end_tag;
						
						}
						
						$event_html .= TAB_10.'</td>'."\n";
						
					//	Display name
						$event_html .= TAB_10.'<td class="EventListEventName">'."\n";
						
							if($event_listing_info['display_name'] != '' AND $event_listing_info['display_name'] != NULL)
							{
								$event_html .= TAB_11;
								$event_html .= $href_tag2;
									$event_html .= $event_listing_info['display_name'];
								$event_html .= $href_end_tag ."\n";
							}
						
						$event_html .= TAB_10.'</td>'."\n";
			
					//	Date
						if ($event_settings['display_date'] == 'on')
						{
							$event_html .= TAB_10.'<td>'.date($event_settings['date_format'],strtotime($event_listing_info['time'])).'</td>' ."\n";
						}					
						if ($event_settings['display_time'] == 'on')
						{
							$event_html .= TAB_10.'<td>'.date($event_settings['time_format'], strtotime($event_listing_info['time'])).'</td>'."\n";
						}

					//	misc Fields	
						for ($i = 1; $i < 6; $i++)
						{
							if ($event_settings['field_active_'.$i] == 'on')
							{
								$event_html .= TAB_10.'<td>'; 
								
								if ($event_listing_info['field_'.$i] != '' AND $event_listing_info['field_'.$i] != NULL)
								{
									$event_html .= $event_listing_info['field_'.$i];
								}
								
								$event_html .='</td>'."\n";
							}					
						
						}	

					//	more info link
					$event_html .= TAB_10.'<td>'."\n";
					$event_html .= $href_tag3;							
					$event_html .= TAB_10.'</td>'."\n";
						
					$event_html .= TAB_9.'</tr>'."\n";
					
					$theres_events = TRUE;
										
					$alt_BG_count++;
		
				}
				
			}
			
			$event_html .= TAB_8.'</table>' ."\n";
			

			
		}
			
		if ($theres_events == TRUE)
		{
			echo $event_html;
		}
		
		else
		{
			echo TAB_8.'<p id="EventListTextMsg" >'.$event_settings['no_events_msg'].'</p>'."\n";
		}
		
		
		//	Do text 2
		if ($event_settings['text_2'] != '' AND $event_settings['text_2'] != NULL)
		{
			echo TAB_8.'<p id="EventListText_2" >'.$event_settings['text_2'].'</p>'."\n";
		}		
		
	//}

	
	echo TAB_7.'</div>'."\n";
	
	echo "\n";
	echo TAB_7.'<!--	END Event Listing code 	-->'."\n";
	echo "\n";	
		
?>