<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	$pathToThumbs = "thumbs/";
	
	createThumbs("images/", "thumbs/",100);

	echo "\n";
	echo TAB_7.'<!--	START Flash Photo Browser code 	-->'."\n";
	echo "\n";
	
	echo TAB_7.'<div class="PhotoBrowserFlash" id="PhotoBrowserFlash_'.$mod_id.'" >'."\n";
		echo TAB_8.'<object type="application/x-shockwave-flash" data="browser.swf"  width="100%" height="100%">'."\n";
			echo TAB_9.'<param name="movie" value="browser.swf" />'."\n";
			echo TAB_9.'<param name="quality" value="high" />'."\n";
			echo TAB_9.'<param name="wmode" value="transparent" />'."\n";
			echo TAB_9.'<a href="http://get.adobe.com/flashplayer/" >'."\n";
				echo TAB_10.'<img src="/images_misc/noflash.png" class="NoBorder" '."\n"; 
				echo TAB_10.'alt="You need to have a &quot;Flash Player&quot; installed on your computer to properly view this part of the website" />'."\n";
			echo TAB_9.'</a>'."\n";
		echo TAB_8.'</object>'."\n";
	echo TAB_7.'</div>'."\n";
	
	echo "\n";	
	echo TAB_7.'<!--	END Flash Photo Browser code 	-->'."\n";
	echo "\n";

	
	
//========== put THumbnail creation CODE in admin UPDATE / UPLOAD image page ?? - creating thumbs live is too slow ========================

function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth ) 
{
  // open the directory
  $dir = opendir( $pathToImages );

  // loop through it, looking for any/all JPG files:
  while (false !== ($fname = readdir( $dir ))) {
    // parse path for the extension
    $info = pathinfo($pathToImages . $fname);
	
	//--------------Remove windows thumbnail db file if present  ---   need to REFRESH pag
	if ($fname == "Thumbs.db"){unlink($pathToImages . $fname);}
		
    // continue only if this is a JPEG image
    if ( strtolower($info['extension']) == 'jpg' ) 
    {
    //  echo "Creating thumbnail for {$fname} <br />";

      // load image and get image size
      $img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
      $width = imagesx( $img );
      $height = imagesy( $img );

      // calculate thumbnail size
      $new_width = $thumbWidth;
      $new_height = floor( $height * ( $thumbWidth / $width ) );

      // create a new tempopary image
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      // copy and resize old image into new image 
      imagecopyresampled( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      // save thumbnail into a file
      imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
    }
  }
  // close the directory
  closedir( $dir );
}	
	
	
?>