<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		
		//	RESET button ======================================================				
		echo TAB_7.'<a  href="'.$this_page.'?event_id='.$_REQUEST['event_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;tab='.$tab.'"'."\n";
			echo TAB_8.' title="Reload this page to Reset all '.$alias.' data" >' ."\n";
			echo TAB_8.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
		echo TAB_7.'</a>'. "\n";	

	
		//	Image 		
		echo TAB_7.'<fieldset class="AdminForm3">'."\n";

			echo '<h1> (Under Construction) </h1>';
			
		echo TAB_7.'</fieldset>'."\n";
		
?>