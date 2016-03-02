<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//========================  End Unique Page Content  ======================================================
				echo TAB_3.'</div>'." \n";	//------end CentreColumn
			
			echo TAB_2.'</div>'." \n";	//-------end Wrapper	
			
		//---------Do Footer:-------------
			include_once ('cms_footer.php');

		echo TAB_1.'</div>'." \n";	//---------end Container

		
	echo '</body>'." \n";

echo '</html>';

?>