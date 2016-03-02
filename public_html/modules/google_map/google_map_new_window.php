<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	
$file_path_offset = '../../';

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');

	require_once ($file_path_offset.'includes/access.php');
	
//---------Get Head:
	include_once ($file_path_offset.'includes/head.php'); 
	
	echo "\n";

	//	Do Google Map 	-------------
	$mysql_err_msg = 'Google Map Address Information not available';	
	$sql_statement = 'SELECT * FROM mod_google_map WHERE mod_id = "'.$_REQUEST['map'].'" AND active = "on"';
											  
	$google_map_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

	
//------ -- START BODY-------------------------------------------------------------------------------------------------

	echo '<body class="MainSite" id="GoogleMapNewWindow" '.$body_onload_info.' >'." \n"; 	
	echo "\n";
	
		//--------START BOUNDARY-----------------------------------------------------------------
	
		echo TAB_1.'<div class="NewWindow" >'." \n";		
		echo "\n";
	
			//-------------CLOSE Button
			echo TAB_2.'<div class="Button" onclick="javascript:window.close();" title="Click here to close this Window" >'." \n";		
				echo TAB_3.'<input type="button" value="Close this Window" '
							.'onclick="window.close();" title="Click here to close this Window" />' ."\n";
			echo TAB_2.'</div>' ."\n";


			echo TAB_2.'<div class="GoogleMap" id="GoogleMap_'.$google_map_info['mod_id'].'" >'. "\n";	
			
				//	Do heading and Text 1
				if ($google_map_info['heading'] != "" AND $google_map_info['heading'] != NULL ) 
				{ echo TAB_3.'<h3 class="GoogleMapHeading" >'.HiliteText($google_map_info['heading']).'</h3>'. "\n"; } 

				if ($google_map_info['text_1'] != "" AND $google_map_info['text_1'] != NULL ) 
				{ echo TAB_3.'<p class="GoogleMapText" >'.HiliteText($google_map_info['text_1']).'</p>'. "\n"; } 
					
				//	Do Google Map Div
				echo TAB_3.'<div class="GoogleMapCanvas" id="GoogleMapCanvas_'.$google_map_info['mod_id'].'" ></div>' ."\n";
						
				//	Do Text 2
				if ($google_map_info['text_2'] != "" AND $google_map_info['text_2'] != NULL ) 
				{ echo TAB_3.'<p class="GoogleMapText" >'.HiliteText($google_map_info['text_2']).'</p>'. "\n"; } 	

				
			//	Do Get Directions to location	If enabled...				
			if 
			(		
					$google_map_info['get_dir_in_new_win'] == "on" 
				AND $google_map_info['directions_address'] != "" 
				AND $google_map_info['directions_address'] != NULL 
			)
			{ include ('google_map_directions.php');}				

			echo TAB_2.'</div>'. "\n";	
		
		echo TAB_1.'</div>'."\n";	//---------end Container
		echo "\n";
							
	echo '</body>'."\n";

echo '</html>'."\n";
	
?>