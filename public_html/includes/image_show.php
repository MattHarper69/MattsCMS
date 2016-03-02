<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

$file_path_offset = '../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');

	require_once ($file_path_offset.'includes/access.php');
	
//---------Get Head:
	include_once ($file_path_offset.'includes/head.php'); 
	
	echo "\n";
	
//------ -- START BODY-------------------------------------------------------------------------------------------------

	echo '<body class="MainSite" onclick="javascript:window.close();" title="Click anywhere in this Window to close this it" >'." \n"; 	
	echo "\n";
	
		//--------START BOUNDARY-----------------------------------------------------------------
	
		echo TAB_1.'<div class="NewWindow" onclick="javascript:window.close();" title="Click anywhere HERE to close this Window" >'." \n";		
		echo "\n";
		
			//-----------Display Image
			echo TAB_2.'<a class="ImageView" href="javascript:window.close();" title="Click HERE to close this Window" >' ."\n";	
				echo TAB_3.'<img class="ImageView" src="'.$_REQUEST['img'].'" ' ."\n";
				echo TAB_3.'title="Click: to close this Window" alt="image file:'.$_REQUEST['img'].'" />' ."\n";
			echo TAB_2.'</a>' ."\n";
			
			echo TAB_2.'<br/>' ."\n";
			
			//-------------CLOSE Button
			echo TAB_2.'<input type="button" value="Click anywhere above: to close this Window" '
						.'onclick="window.close();" title="Click HERE to close this Window"/>' ."\n";

		echo TAB_1.'</div>'."\n";	//---------end Container
		echo "\n";
							
	echo '</body>'."\n";

echo '</html>'."\n";
	
?>