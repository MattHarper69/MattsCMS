<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

		
	$this_page = $_SERVER['PHP_SELF'];	
	$file_path_offset = '../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');

	require_once ($file_path_offset.'includes/access.php');
	
	
	
	// 	Start output buffering
	//ob_start();
	
	require_once ($file_path_offset.'includes/head.php');

echo '<body>'." \n";
	
	//	SHUTDOWN msg
	if (SITE_SHUTDOWN == 1)
	{
	

	/////////////////////////////////	EDITED TO HERE		/////////////////////////////////////////////////////////////////////
	
	}
				//	Close Window ======================================================
				echo TAB_1.'<a href="javascript:parent.$.fn.colorbox.close()" title="Cancel and Close this window" >' ."\n";
					echo TAB_2.'<img src="/images_misc/icon_closeWin_16x16.png" alt="Close" style="float:right;padding:5px;"/>' ."\n";
				echo TAB_1.'</a>'. "\n";		

	
	echo TAB_1.'<div class="NewWindow" >'." \n";

	
		if (isset($_GET['event_id']) AND $_GET['event_id'] != '' AND isset($_GET['mod_id']) AND $_GET['mod_id'] != '')	
		{
		
			$mysql_err_msg = 'Event Listing unavailable';	
			$sql_statement = 'SELECT * FROM mod_event_list'
			
													.' WHERE event_id = '.$_GET['event_id']
													.' AND active = "on"'
													;
															
			$event_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			
			//	 get event listing settings
			$mysql_err_msg = 'Event Listing Settings unavailable';	
			$sql_statement = 'SELECT * FROM mod_event_list_config'

														.' WHERE mod_id = "'.$_GET['mod_id'].'"'
														;
			
			$event_settings = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			
			
			if ($event_settings['event_alias'])
			{
				$event_alias = $event_settings['event_alias'];
			}
			else
			{
				$event_alias = '&quot;Event&quot;';
			}
			
			

	
			
				
				

		
				if ($event_info)
				{
	
					//	Heading
					echo TAB_2.'<h1> '.$event_alias.': ';
					
					if ($event_info['display_name'] != '' AND $event_info['display_name'] != NULL)	
					{
						echo $event_info['display_name'];					
					}
					
					echo '</h1>' ." \n";

					echo TAB_2.'<div class="EventListContent" >'." \n";
					
					if 
					(
							$event_settings['display_image'] == 'on' 
						AND file_exists('../_images_user/_list_event_id_' .$event_info['event_id']. '.jpg')
					)	
					{
						echo TAB_3.'<img class="EventListImage" src="/_images_user/_list_event_id_' .$event_info['event_id']. '.jpg"' ." \n";
						echo TAB_4.' alt="image for: '.$event_info['display_name'].'"/>'." \n";				
					}

					//	Details (fields)
					if ($event_info['display_fields_in_more_info'] == 'on')	
					{
						echo TAB_4.'<ul class="EventListDetails">' ."\n";
						
						//	Date
						if ($event_settings['display_date'] == 'on')
						{
							echo TAB_5.'<li>Date: '.date($event_settings['date_format'],strtotime($event_info['time'])).'</li>' ."\n";
						}					
						if ($event_settings['display_time'] == 'on')
						{
							echo TAB_5.'<li>Time: '.date('h:ia', strtotime($event_info['time'])).'</li>'."\n";
						}

						//	misc Fields	
						for ($i = 1; $i < 6; $i++)
						{
							if ($event_settings['field_active_'.$i] == 'on')
							{
								echo TAB_5.'<li>' .$event_settings['field_head_'.$i]. ': '; 
								
								if ($event_info['field_'.$i] != '' AND $event_info['field_'.$i] != NULL)
								{
									echo $event_info['field_'.$i];
								}
								
								echo'</li>'."\n";
							}					
						
						}	
						
						echo TAB_4.'</ul>' ."\n";
						
					}
					
					//	Full Description
					if ($event_info['long_desc'] != '' AND $event_info['long_desc'] != NULL)	
					{
						echo TAB_3.'<p class="EventListDesc">'.$event_info['long_desc'].'</p>'." \n";				
					}					
					echo TAB_2.'</div>'." \n";
					
				}
				else
				{
					echo TAB_2.'<p>This '.$event_alias.' has expired or no longer exists<p>'." \n";
				}
		





		/////////////////////////////////	EDITED TO HERE		/////////////////////////////////////////////////////////////////////



			
		}
	
		else
		{
			echo TAB_2.'<p>This '.$event_alias.' has expired or no longer exists<p>'." \n";
		}
		
		//Do Link back to main page if no javascript
		echo TAB_1.'<noscript>'." \n";				
							
			echo TAB_2.'<a class="ButtonLink" href="/index.php?p='.$_GET['p'].'" > Return to '.$event_alias.' Listing </a>'." \n";					
		echo TAB_1.'</noscript>'." \n";
		
	echo TAB_1.'</div>'." \n";	

echo '</body>'." \n";
echo '</html>'." \n";

	// 	Now flush the output buffer
	//ob_end_flush();		
	

	
?>