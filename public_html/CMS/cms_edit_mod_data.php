<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

		
	$this_page = $_SERVER['PHP_SELF'];	
	$file_path_offset = '../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');

	require_once ($file_path_offset.'includes/access.php');
	
if (isset($_SESSION['access']) AND $_SESSION['access'] < 6 )
{		
	
	// 	Start output buffering
	ob_start();
	
	require_once ('cms_includes/cms_head.php');

	echo '<body class="CMS">'." \n";
	
	//	SHUTDOWN msg
	if (SITE_SHUTDOWN == 1)
	{
		echo TAB_4.'<div class="UpdateMsgDiv">'."\n";	
			echo TAB_5.'<p class = "WarningMSG" >The Site is currently SHUT DOWN - Go to Global Settings to Re-Activate</p>'."\n";
		echo TAB_4.'</div>'."\n";			
	}
	

	//	FOR  editing Mod	
	if (isset($_GET['e']))	
	{
	
		$mysql_err_msg = 'Module CMS edit filename Info unavailable';
		$sql_statement = 'SELECT'
		
								.'  cms_edit_filename'
								.', mod_name'
								.', modules.active'
								.', modules.mod_type_id'
								.', modules.sync_id'
								
								.' FROM _module_types, modules'
		
								.' WHERE modules.mod_id = '.$_GET['e']
								.' AND modules.mod_type_id = _module_types.mod_type_id'
								;
										
		$mod_type_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
		
		echo TAB_3.'<div class="CMS_Heading" >'." \n";
			echo TAB_4.'<h1> Edit &quot;'.$mod_type_info['mod_name'].'&quot; Module ( ID: '.$_GET['e'].' )'." \n";
			
				
				
				//	Window Adjust Buttons
				//	Close Window ======================================================
				echo TAB_5.'<a href="javascript:parent.$.fn.colorbox.close()" title="Cancel and Close this window" >' ."\n";
					echo TAB_6.'<img src="/images_misc/icon_closeWin_16x16.png" alt="Close" style="float:right;"/>' ."\n";
				echo TAB_5.'</a>'. "\n";			
	
				//	Maximize Window	======================================================
				echo TAB_5.'<a href="#" class="MaximizeWindow" title="Maximize this window" >' ."\n";
					echo TAB_6.'<img src="/images_misc/icon_maxWin_16x16.png" alt="Maximize" style="padding-right:2px; float:right;"/>' ."\n";
				echo TAB_5.'</a>'. "\n";
				
				//	Restore Window	======================================================
				echo TAB_5.'<a href="#" class="RestoreWindow" title="Restore this window" >' ."\n";
					echo TAB_6.'<img src="/images_misc/icon_restoreWin_16x16.png" alt="Restore" style=" padding-right:2px; float:right;"/>' ."\n";
				echo TAB_5.'</a>'. "\n";
				
			echo TAB_4.'</h1>'." \n";
		echo TAB_3.'</div>'." \n";		

		if($mod_type_info['active'] != 'on')
		{
			echo TAB_5.'<p class = "WarningMSG" >(The Module is currently De-Activated)</p>'."\n";		
		}

		
		require_once('cms_edit_mods/'.$mod_type_info['cms_edit_filename']);
				
	}
	
	
	//	FOR cloning Mod
	elseif (isset($_GET['c']))	
	{
		echo '<h1> Clone Mod_id: '.$_GET['c'].'</h1>';
	}

	//	FOR changing Mod  type
	elseif (isset($_GET['ct']))	
	{
		echo '<h1> Change Mod type of Mod_id: '.$_GET['ct'].'</h1>';
		
				//	===================================================================================
				echo '<h3>Select the Module Type from the Images below:</h3>';
				//	===================================================================================				
		
	}
	
	//	FOR Adding Mod
	else
	{
		echo '<h1> ADD a MOD</h1>';
	}	
	
	
	echo '</body>'." \n";
	echo '</html>'." \n";

	// 	Now flush the output buffer
	ob_end_flush();		
	
}
	
?>