<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

//-----------Page Expired MSG---------
	
	echo TAB_6.'<div class="ExpiredPage" id="ExpiredPage_'.$page_id.'" >'."\n";
	
		echo TAB_7.'<h2>'."\n";
				
			echo TAB_8.'The Page you are requesting has expired'."\n";

			echo TAB_8.'<br />or'."\n";

			echo TAB_8.'<br />is temporarily unavailable'."\n";

		echo TAB_7.'</h2>'."\n";
				
		echo TAB_7.'<br />'."\n";

		echo TAB_7.'<h2>'."\n";
				
			echo TAB_8.'<a href="/index.php">Please click here to return to the home page... </a>'."\n";
					
		echo TAB_7.'</h2>'."\n";
		
	echo TAB_6.'</div>'."\n";

?>