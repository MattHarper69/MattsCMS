<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

		
	$this_page = $_SERVER['PHP_SELF'];
	$update_url = '/CMS/cms_update/cms_update_event_list_Event_data.php';
	$file_path_offset = '../../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	
if (isset($_SESSION['access']) AND $_SESSION['access'] < 6 )
{		
	
	// 	Start output buffering
	ob_start();
	
	require_once ('../../cms_includes/cms_head.php');

	echo '<body class="CMS">'." \n";

	
	//	SHUTDOWN msg
	if (SITE_SHUTDOWN == 1)
	{
		echo TAB_4.'<div class="UpdateMsgDiv">'."\n";	
			echo TAB_5.'<p class = "WarningMSG" >The Site is currently SHUT DOWN - Go to Global Settings to Re-Activate</p>'."\n";
		echo TAB_4.'</div>'."\n";			
	}
	
	//---------Update error msg:
	include_once ($file_path_offset.'CMS/cms_includes/cms_msg_update.php');
		
	//	 get event listing settings
	$mysql_err_msg = 'Event Listing Settings unavailable';	
	$sql_statement = 'SELECT * FROM mod_event_list_config'

												.' WHERE mod_id = "'.$_GET['mod_id'].'"'
												;
												
	if 
	(
			isset($_REQUEST['event_id']) AND $_REQUEST['event_id'] != ''
		AND $event_settings = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg))
	)
	{
		
		//------------set default time zone
		date_default_timezone_set($event_settings['time_zone']);
		$alias = $event_settings['event_alias'];

		// get all event info
		$mysql_err_msg = $alias.' information unavailable';	
		$sql_statement = 'SELECT * FROM mod_event_list WHERE event_id = "'.$_REQUEST['event_id'].'"';
							
		$event_info_result = ReadDB ($sql_statement, $mysql_err_msg);

		$event_info = mysql_fetch_array ($event_info_result);

		if (mysql_num_rows ($event_info_result) > 0 OR $_REQUEST['event_id'] == 'new' OR $_REQUEST['clone'] == 1) 
		{
	
			//	Window Adjust Buttons
			echo TAB_2.'<div style=" float:right;">'. "\n";
				//	Close Window ======================================================
				echo TAB_3.'<a href="javascript:parent.$.fn.colorbox.close()" title="Cancel and Close this window" >' ."\n";
					echo TAB_4.'<img src="/images_misc/icon_closeWin_16x16.png" alt="Close" style="float:right;"/>' ."\n";
				echo TAB_3.'</a>'. "\n";			

				//	Maximize Window	======================================================
				echo TAB_3.'<a href="#" class="MaximizeWindow" title="Maximize this window" >' ."\n";
					echo TAB_6.'<img src="/images_misc/icon_maxWin_16x16.png" alt="Maximize" style="padding-right:2px; float:right;"/>' ."\n";
				echo TAB_3.'</a>'. "\n";
				
				//	Restore Window	======================================================
				echo TAB_3.'<a href="#" class="RestoreWindow" title="Restore this window" >' ."\n";
					echo TAB_4.'<img src="/images_misc/icon_restoreWin_16x16.png" alt="Restore" style=" padding-right:2px; float:right;"/>' ."\n";
				echo TAB_3.'</a>'. "\n";
			echo TAB_2.'</div>'. "\n";
			
			if (isset($_REQUEST['clone']) AND $_REQUEST['clone'] == 1 )
			{
				echo TAB_2.'<h1>Clone '.$alias.': '.$event_info['name'] .'</h1>'."\n";

			}
			elseif ($_REQUEST['event_id'] == 'new' )
			{
				echo TAB_2.'<h1>Add New '.$alias.'</h1>' ."\n";
				$not_asigned = TRUE;
			}		
			else
			{
				echo TAB_2.'<h1>Edit '.$alias.': '.$event_info['name'] ."\n";
				
					//	Warn if not Active
					if ($event_info['active'] != 'on')
					{
						echo TAB_3.'<span class="WarningMSG" > (Not Active)</span>';
					}	

				
					//	Clone Product	======================================================
					echo TAB_4.'<a href="'.$this_page.'?event_id='.$_REQUEST['event_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;clone=1"'
						.' title="Clone this '.$alias.'" >' ."\n";
						echo TAB_5.'<img src="/images_misc/icon_ClonePage_24x24.png" alt="Clone" style="padding-right:10px; float:right;"/>'."\n";
					echo TAB_4.'</a>'. "\n";				
					
					//	Add Product	======================================================
					echo TAB_4.'<a href="'.$this_page.'?event_id=new&amp;mod_id='.$_GET['mod_id'].'" title="Add a new '.$alias.'" >' ."\n";
						echo TAB_5.'<img src="/images_misc/icon_AddPage_24x24.png" alt="Add" style="padding-right:10px; float:right;"/>' ."\n";
					echo TAB_4.'</a>'. "\n";
					
				echo TAB_2.'</h1>'."\n";
				
			}
					
			//	Start Main Form
			echo TAB_2.'<form id="Form_EditProduct" action = "'.$update_url.'"  method="post" enctype="multipart/form-data" >'."\n";
				echo TAB_3.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
					echo TAB_4.'<legend class="Centered" >'."\n";
						
					if ((isset($_REQUEST['event_id']) AND $_REQUEST['event_id'] == 'new') OR (isset($_REQUEST['clone']) AND $_REQUEST['clone'] == 1) )
					{
						//-------------ADD PRODUCT BUTTON------------------------------------
						echo TAB_5.'<input type="submit" name="update_all" value="Save and Add this '.$alias.'" />'."\n";
						echo TAB_3.'<input type="hidden" name="update_action" value="new" />'."\n";
						$_REQUEST['tab'] = 1;					
					
					}
					
					else
					{
						//-------------UPDATE BUTTON------------------------------------
						echo TAB_5.'<input type="submit" name="update_all" id="UpdateEventSubmit" value="Update ALL '.$alias.' Data"/>'."\n";

						//	Delete BUTTON------------------------------------
						echo TAB_5.'<p style="float:right;">' ."\n";
										
							echo TAB_6.'<a href="#" class="ConfirmDeleteButton" title="Delete this '.$alias.': '.$event_info['name'].'" >'."\n";
								echo TAB_7.'<img src="/images_misc/icon_delete_24x24.png" alt="Delete" />' ."\n";
							echo TAB_6.'</a>'. "\n";

						echo TAB_5.'</p>' ."\n";
								
						echo TAB_5.'<p class="WarningMSG HideAtStart" style="border: solid 1px #cccccc; padding:5px;">' ."\n";
										
							//	OK DELETE Mod
							echo TAB_6.'Confirm: <input type="submit" name="submit_delete_event" style="color:#cc0000;"'."\n";	 
								echo TAB_7.' value="DELETE" title="Delete this '.$alias.'" /> This '.$alias."\n";	
										
							//	Cancel link
							echo TAB_6.'<a href="#" class="CloseThisPanel" title="Do NOT Delete">' ."\n";
								echo TAB_7.'<img src="/images_misc/icon_close_16x16.png" alt="Close" style="padding:10px; float:right;"/>' ."\n";
							echo TAB_6.'</a>' ."\n";
											
						echo TAB_5.'</p>' ."\n";				
										
					}			
						
					echo TAB_4.'</legend>'."\n";

		
					echo TAB_4.'<fieldset id="UpdateShopEditSettings" class="AdminForm1" style="clear:both;">'."\n";	
					
					$tab_nav_array = array(
											 1 => 'General'
											,2 => 'Image'			
											,3 => 'Timing'
											,4 => 'More Info...'

										);
					//	Do Tab navigation	
						echo TAB_5.'<ul id="TabNavConfigMod" class="TabPanelNavLinks">' ."\n";

						foreach ($tab_nav_array as $key => $value)
						{
							if ($_REQUEST['tab'] == $key) { $current = 'class="current"';}
							else { $current = '';}
							echo TAB_6.'<li id="OpenTabPanel_'.$key.'" '.$current.'><a href="#TabPanel_'.$key.'" >'.$value.'</a></li>' ."\n";
						}
					
						echo TAB_5.'</ul>' ."\n";


						//	Panel	
						echo TAB_5.'<div id="TabNavConfigMod_Tabs" class="TabPanelContainer">'."\n";
					
						foreach ($tab_nav_array as $key => $value)
						{
							if ($_REQUEST['tab'] == $key)	{$display = 'style="display: block;"';}
							else {$display = 'style="display: none;"';}
							echo TAB_6.'<div id="TabPanel_'.$key.'" class="AdminFormTabPanel" '.$display.' >'."\n";
									
								$tab = $key;
								
								$file_suffix = str_replace(' ', '_' , $value);
								require_once('events_edit/cms_event_list_event_edit_'.$file_suffix.'.php');
								
							echo TAB_6.'</div>'."\n";
						}	
					
						echo TAB_5.'</div>'."\n";
					
					echo TAB_4.'</fieldset>' ."\n";		

				echo TAB_3.'</fieldset>' ."\n";	
					
				echo TAB_3.'<input type="hidden" name="event_id" value="'.$_REQUEST['event_id'].'" />'."\n";
				
				$return_url = $this_page.'?mod_id='.$_GET['mod_id'].'&tab='.$_REQUEST['tab'];
				echo TAB_3.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";
				echo TAB_3.'<input type="hidden" name="mod_id" value="'.$_GET['mod_id'].'" />'."\n";
				
			echo TAB_2.'</form>' ."\n";	
			
/* 			
			//	Tool Bar
			if ($_REQUEST['tab'] == 4 AND $Toolbar_WysiwygMini == 'show')
			{
							
			}

			 */
		
		}
		
		else	//	Close window
		{
			CloseColorBox();
		}
		
	}
	
	else	//	Close window
	{
		CloseColorBox();
	}

	echo '</body>'." \n";
	echo '</html>'." \n";

	// 	Now flush the output buffer
	ob_end_flush();		
	
}

?>