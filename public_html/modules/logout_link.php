<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	$_SESSION['authorized'] = '';
	
if ($_SESSION['authorized'] == TRUE)
{ 
	//	for preview in ADMIN:
	if ( $_SESSION['CMS_mode'] == TRUE )
	{ 
		//	TO DO: treat this as a "hidden" mod and display a "!" icon - same as div_mods ??????????????????????????
		
		echo TAB_7.'<div class="LogoutLink HoverShow Attention" id="LogoutLink_'.$mod_id.'" >'."\n";
		
			echo TAB_8.'<a class="ButtonLink" href="#" >Log-out</a>'."\n";

		echo TAB_7.'</div>'."\n";	
	}
	
	//	The REAL thing:
	else
	{	

		echo "\n";			
		echo TAB_7.'<!--	Start Log-Out Link		-->'."\n";		
		echo "\n";	
		
		echo TAB_7.'<div class="LogoutLink" id="LogoutLink_'.$mod_id.'" >'."\n";
		
			echo TAB_8.'<a class="ButtonLink" href="'.htmlspecialchars($_SERVER['PHP_SELF'].
						'?'.$_SERVER['QUERY_STRING']).'&amp;logout=1'.'" >Log-out</a>'."\n";

		echo TAB_7.'</div>'."\n";
		
		echo "\n";			
		echo TAB_7.'<!--	End Log-Out Link		-->'."\n";		
		echo "\n";	
	}
	
}	
?>