<?php	

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	//----Not Authorised

	echo TAB_6.'<div id="NotAuthPage" >'." \n"; 
			
		echo TAB_6.'<h1 class ="RedHeading " >You are not Authorised to access this page - please use the menu</h1>' ."\n";
			
		//	when in Admin, Hide Log-out Link
		if ($_SESSION['CMS_mode'] != TRUE)
		{						
			echo TAB_6.'<h1 >OR</h1>' ."\n";
			echo TAB_6.'<a class="ButtonLink" href="'.$this_page.'&amp;logout=1'.'" >Log-out</a>'."\n";
		}
	
	echo TAB_6.'</div>' ."\n";

		
?>