<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

		
	$this_page = $_SERVER['PHP_SELF'];
	$update_url = '/CMS/cms_update/cms_update_sweeps_Product_data.php';
	$file_path_offset = '../../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	
if (isset($_SESSION['access']) AND $_SESSION['access'] < 6 )
{		
	
	require_once (CODE_NAME.'_shop_configs.php');
	require_once (CODE_NAME.'_alert_configs.php');
	require_once ($file_path_offset.'modules/sweeps/sweeps_php_functions.php');
	
	//------------set default time zone
	date_default_timezone_set(SHOP_TIME_ZONE);
	
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

		
	if (isset($_REQUEST['item_id']) AND $_REQUEST['item_id'] != '')
	{
		
		//---------Update error msg:
		include_once ($file_path_offset.'CMS/cms_includes/cms_msg_update.php');
		
		// get all item info
		$mysql_err_msg = ''.SHOP_ITEM_ALIAS.' information unavailable';	
		$sql_statement = 'SELECT * FROM sweeps_items WHERE item_id = "'.$_REQUEST['item_id'].'"';
							
		$sweeps_item_result = ReadDB ($sql_statement, $mysql_err_msg);

		$sweeps_item_info = mysql_fetch_array ($sweeps_item_result);

		if (mysql_num_rows ($sweeps_item_result) > 0 OR $_REQUEST['item_id'] == 'new' OR $_REQUEST['clone'] == 1) 
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
				echo TAB_2.'<h1>Clone '.SHOP_ITEM_ALIAS.': '.$sweeps_item_info['item_name'] .'</h1>'."\n";

			}
			elseif ($_REQUEST['item_id'] == 'new' )
			{
				echo TAB_2.'<h1>Add New '.SHOP_ITEM_ALIAS.'</h1>' ."\n";
				$not_asigned = TRUE;
			}		
			else
			{
				echo TAB_2.'<h1>Edit '.SHOP_ITEM_ALIAS.': '.$sweeps_item_info['item_name'] ."\n";
				
					//	Warn if not Active
					if ($sweeps_item_info['active'] != 'on')
					{
						echo TAB_3.'<span class="WarningMSG" > (Not Active)</span>';
					}	

				
					//	Clone Product	======================================================
					echo TAB_4.'<a href="'.$this_page.'?item_id='.$_REQUEST['item_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;clone=1"'
						.' title="Clone this '.SHOP_ITEM_ALIAS.'" >' ."\n";
						echo TAB_5.'<img src="/images_misc/icon_ClonePage_24x24.png" alt="Clone" style="padding-right:10px; float:right;"/>'."\n";
					echo TAB_4.'</a>'. "\n";				
					
					//	Add Product	======================================================
					echo TAB_4.'<a href="'.$this_page.'?item_id=new&amp;mod_id='.$_GET['mod_id'].'" title="Add a new '.SHOP_ITEM_ALIAS.'" >' ."\n";
						echo TAB_5.'<img src="/images_misc/icon_AddPage_24x24.png" alt="Add" style="padding-right:10px; float:right;"/>' ."\n";
					echo TAB_4.'</a>'. "\n";
					
				echo TAB_2.'</h1>'."\n";
				
			}
			
			
			// get all assigned cats and determine if Item been assigned ?
			$mysql_err_msg = ''.SHOP_ITEM_ALIAS.' information unavailable';	
			$sql_statement = 'SELECT sweeps_categories.cat_id, cat_name FROM sweeps_cat_asign, sweeps_categories'
			
									.' WHERE item_id = "'.$_REQUEST['item_id'].'"'
									.' AND sweeps_cat_asign.cat_id = sweeps_categories.cat_id'
									.' ORDER BY sweeps_categories.seq'
									;
			$asign_cat_result = ReadDB ($sql_statement, $mysql_err_msg);
			while ($asign_cat_info = mysql_fetch_array($asign_cat_result))
			{
				$asign_cat_info_array[$asign_cat_info['cat_id']] = $asign_cat_info['cat_name'];
			}
			if (mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg)) < 1 )				
			{
				$not_asigned = TRUE;
				echo TAB_3.'<span class="WarningMSG" > (Not Assigned)</span>';
			}
			else
			{
				$not_asigned = FALSE;
			}			
			
			
			//	Start Main Form
			echo TAB_2.'<form id="Form_EditProduct" action = "'.$update_url.'"  method="post" enctype="multipart/form-data" >'."\n";
				echo TAB_3.'<fieldset id="UpdateAll" class="AdminForm">'."\n";	
					echo TAB_4.'<legend class="Centered" >'."\n";
						
					if ((isset($_REQUEST['item_id']) AND $_REQUEST['item_id'] == 'new') OR (isset($_REQUEST['clone']) AND $_REQUEST['clone'] == 1) )
					{
						//-------------ADD PRODUCT BUTTON------------------------------------
						echo TAB_5.'<input type="submit" name="update_all" value="Save and Add this '.SHOP_ITEM_ALIAS.'" />'."\n";
						echo TAB_3.'<input type="hidden" name="update_action" value="new" />'."\n";
						$_REQUEST['tab'] = 1;					
					
					}
					
					else
					{
						//-------------UPDATE BUTTON------------------------------------
						echo TAB_5.'<input type="submit" name="update_all" value="Update ALL '.SHOP_ITEM_ALIAS.' Data" />'."\n";

						//	Delete BUTTON------------------------------------
						echo TAB_5.'<p style="float:right;">' ."\n";
										
							echo TAB_6.'<a href="#" class="ConfirmDeleteButton" title="Delete this '.SHOP_ITEM_ALIAS.': '.$sweeps_item_info['item_name'].'" >'."\n";
								echo TAB_7.'<img src="/images_misc/icon_delete_24x24.png" alt="Delete" />' ."\n";
							echo TAB_6.'</a>'. "\n";

						echo TAB_5.'</p>' ."\n";
								
						echo TAB_5.'<p class="WarningMSG HideAtStart" style="border: solid 1px #cccccc; padding:5px;">' ."\n";
										
							//	OK DELETE Mod
							echo TAB_6.'Confirm: <input type="submit" name="submit_delete_item" style="color:#cc0000;"'."\n";	 
								echo TAB_7.' value="DELETE" title="Delete this '.SHOP_ITEM_ALIAS.'" /> This '.SHOP_ITEM_ALIAS."\n";	
										
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
											,2 => 'Pricing'			
											,3 => 'Description'
											,4 => 'Images'
											,5 => 'Postage'
											,6 => 'Restrictions'
											,7 => 'Ticketing'

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
								require_once('products_edit/cms_sweeps_product_edit_'.$file_suffix.'.php');
								
							echo TAB_6.'</div>'."\n";
						}	
					
						echo TAB_5.'</div>'."\n";
					
					echo TAB_4.'</fieldset>' ."\n";		

				echo TAB_3.'</fieldset>' ."\n";	
					
				echo TAB_3.'<input type="hidden" name="item_id" value="'.$_REQUEST['item_id'].'" />'."\n";
				
				$return_url = $this_page.'?mod_id='.$_GET['mod_id'].'&tab='.$_REQUEST['tab'];
				echo TAB_3.'<input type="hidden" name="return_url" value="'.$return_url.'" />'."\n";
				echo TAB_3.'<input type="hidden" name="mod_id" value="'.$_GET['mod_id'].'" />'."\n";
				
			echo TAB_2.'</form>' ."\n";	
		
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