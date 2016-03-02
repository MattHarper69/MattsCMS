<?php

//------------------THIS IS PAGE IS DISPLAYED WHEN THE SITE IS SHUT-DOWN---------------------------------------

		echo TAB_1.'<div id="Container">'. "\n";
			echo TAB_2.'<div id="Shutdown">'. "\n";
			
			echo TAB_3.'<br />'. "\n";
			
			//-----------Text
			if (SITE_SHUTDOWN == 1)
			{
				echo TAB_3.'<em>We&#39;re Very Sorry...</em>'. "\n";
				echo TAB_3.'<br />'. "\n";

				echo TAB_3.'<strong>'.SITE_NAME.' Website</strong>'. "\n";	
				echo TAB_3.'<br />' . "\n";
										
				echo TAB_3.'is temporarily closed due to Maintainence.'. "\n";
				echo TAB_3.'<br />' . "\n";
			
				echo TAB_3.'Please check back in a few minutes...'. "\n";
			}
			elseif (SITE_SHUTDOWN == 2)
			{
				echo TAB_3.'<strong>The '.SITE_NAME.' Website</strong>'. "\n";	
				echo TAB_3.'<br />' . "\n";
										
				echo TAB_3.'is closed indefinitely till further notice.'. "\n";
				echo TAB_3.'<br />' . "\n";					
			}


							
				echo TAB_3.'<br /><br />'. "\n";
							
			//-----------Image
				echo TAB_3.'<img class = "Image" src="images_misc/test_patern.gif" alt="the Test Patern (as seen on TV)" />'. "\n";
				
			echo TAB_2.'</div>'. "\n";
		echo TAB_1.'</div>'. "\n";

	echo '</body>'. "\n";
	echo '</html>'. "\n";

	include_once ( "credits.php" );

	exit(); 

 //----------------end website----------So there.....

 ?>