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

//------ -- START BODY-------------------------------------------------------------------------------------------------

	echo '<body class="MainSite">'." \n"; 	
	echo "\n";
	
		//--------START BOUNDARY-----------------------------------------------------------------
	
		echo TAB_1.'<div class="NewWindow" >'." \n";		
		echo "\n";
		
			//-----------Display Image
			echo TAB_2.'<div class="NewWinText" >'." \n";	

				//	read from db	----------
				$mysql_err_msg = 'This captcha info unavailable';	
				$sql_statement = 'SELECT explain_text FROM mod_captcha WHERE captcha_id = "'.$_REQUEST['id'].'" ';	
				
				$captcha_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			
			
				//echo TAB_3.'<p>'.HiliteText(nl2br(Space2nbsp($captcha_info['explain_text']))).'</p>' ."\n";
				echo TAB_3.'<p>'.HiliteText(nl2br($captcha_info['explain_text'])).'</p>' ."\n";
				
			echo TAB_2.'</div>' ."\n";
					
		echo TAB_1.'</div>'."\n";	//---------end Container
		echo "\n";
							
	echo '</body>'."\n";

echo '</html>'."\n";
	
?>