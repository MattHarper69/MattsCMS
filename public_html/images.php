<?php

$output = '';

$filepath = "images";

$handle = opendir($filepath);

while (false != ($file = readdir($handle))) 
{
	
	//-------add possible "non Image" file names that may reside in images DIR
	if 
	(		
				$file != "." 
		AND 	$file != ".." 
		AND 	$file != ".DS_Store" 
		AND 	$file != "index.html" 
		AND 	$file != "index.htm" 
		AND 	$file != "index.php"
		AND 	$file != "php_photo_browser.php"
		AND 	$file != "Thumbs.db"
	) 
		
		{ 	
			if ($output) $output .= "|";
	        $output .= "$file";
		}
}

closedir($handle);
echo "&files=$output&";

?>