<?php

	//if (!$_SESSION['db_rollback_num']) {$_SESSION['db_rollback_num'] = 1;}	//	Code to do db "rollBack" (undo SAVE) -  not working atm
	
	$win_pos_y = 0;
	$win_pos_x = 0;
	
	if (isset($_COOKIE["CMSposY"])) 
	{$win_pos_y = $_COOKIE["CMSposY"];}
	
	if (isset($_COOKIE["CMSposX"])) 
	{$win_pos_x = $_COOKIE["CMSposX"];}	
	
	if ($win_pos_x < 0) {$win_pos_x = 0;}
	if ($win_pos_y < 0) {$win_pos_y = 0;}
	
	if (isset($_COOKIE["CMSwinState"]) AND $_COOKIE["CMSwinState"] < 2)
	{
		echo TAB_1.'<div id="CMS_Panel" style="
		
													top:'.$win_pos_y.'px; 
													left:'.$win_pos_x.'px;
													
		'." \n";

		echo TAB_12.TAB_10.'" >'." \n";
	
	}

	else	
	{
		echo TAB_1.'<div id="CMS_Panel"  >'." \n";	

	}
		
	

	
		echo TAB_2.'<div id="CMS_PanelTitleBar" >'." \n";
		
			echo TAB_3.'<h1>Site Administration Tools</h1>'." \n";
			
			//	Pin, Min, Max and Close Buttons
			echo TAB_3.'<div style="position:relative; float:right;">' ."\n";


				//	Pin Window
				echo TAB_4.'<a href="javascript:DisableDrag()" class="CMS_Button_PinPanel" title="Prevent this window from being Dragged" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_pinWin_24x24.png" alt="Pin" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";
				
				//	UnPin Window
				echo TAB_4.'<a href="javascript:EnableDrag()" class="CMS_Button_UnPinPanel" title="Make this window Draggable" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_UnPinWin_24x24.png" alt="Un-Pin" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";
				
				//	Minimize
				echo TAB_4.'<a href="javascript:MinimizePanel()" class="CMS_Button_MinimizePanel" title="Minimize this window" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_minWin_16x16.png" alt="Minimize" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";

				//	Restore
				echo TAB_4.'<a href="javascript:RestorePanel()" class="CMS_Button_RestorePanel" title="Restore this window" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_restoreWin_16x16.png" alt="Restore" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";			
						
				//	Maximize
				echo TAB_4.'<a href="javascript:MaximizePanel()" class="CMS_Button_MaximizePanel" title="Maximize this window" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_maxWin_16x16.png" alt="Maximize" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";

				//	Close
				echo TAB_4.'<a href="http://'.$_SERVER['SERVER_NAME'].$this_page.'&amp;exitadmin=1'
							.'" title="Exit Admin WITHOUT Logging-out" >' ."\n";
					echo TAB_5.'<img src="/images_misc/icon_closeWin_16x16.png" alt="Close" style="border:none;"/>' ."\n";
				echo TAB_4.'</a>'. "\n";
				
			echo TAB_3.'</div>' ."\n";			
			
			
		echo TAB_2.'</div>'." \n";
		//----------Status Bar------------------------------------------------------------		
		include_once ('cms_panels/cms_panel_status_bar.php');

		//----------Main Tool Bar------------------------------------------------------------		
		include_once ('cms_toolbars/cms_toolbar_main.php');	

		//----------Next / Prev Page Buttons------------------------------------------------------------		
		include_once ('cms_includes/cms_common_data.php');
		
		if ($_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1 AND $_SESSION['access'] < 5 )
		{
	
			//----------Page Config Panel------------------------------------------------------------
			include_once ('cms_panels/cms_panel_page_config.php');	
			
			if ($_SESSION['access'] < 4 )
			{
				//----------Nav Options Panel------------------------------------------------------------						
				include_once ('cms_panels/cms_panel_page_nav.php');			
			}

			
		
		}
		
		


		//	================================================================================================
		
		
		
		
		echo TAB_2.'<div class="CMS_ToolBarWrapper" id="CMS_ToolBarWrapper"></div>'." \n";	
		
		if ($_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
		{			
			//	Wysiwyg ToolBar
			include_once ('cms_toolbars/cms_toolbar_wysiwyg.php');
		}
		
	
	echo TAB_1.'</div>'." \n";

	
?>