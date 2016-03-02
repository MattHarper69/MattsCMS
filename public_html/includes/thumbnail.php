<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	// define directory path
		//$dir = ".";
		//$image = exif_thumbnail($dir . "/" . $_GET['file']);
		$image = $_GET['file'];
		$image = str_replace("\'", "'", $image);	//------------removes the: " \' "		
		$image = exif_thumbnail($image);
		header("Content-Type: image/jpeg");
		echo $image;
?> 