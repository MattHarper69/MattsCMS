<?php

	
	createThumbs("04Longueville/ConstructionShots/", "04Longueville/ConstructionShots/thumbs/", "yes", 50, 50, "thumb_", "");


function createThumbs( $pathToImages, $pathToThumbs, $do_crop, $thumbWidth, $thumbHeight, $prefix, $suffix ) 
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

		// load image and get image size
		$img = imagecreatefromjpeg( "{$pathToImages}{$fname}" );
		$width = imagesx( $img );
		$height = imagesy( $img );

		if ( $do_crop == "yes" )
		{
			if ( $width - $height > 0)
			{
				$src_x = ($width - $height) / 2;
				$src_y = 0;
				$src_width = $height;
				$src_height = $height;
			}

			else
			{
				$src_y = ($height - $width) / 2;
				$src_x = 0;
				$src_width = $width;
				$src_height = $width;
			}	  	
		}

		else
		{
			$src_x = 0;
			$src_y = 0;
			$src_width = $width;
			$src_height = $height;		
		}

		// calculate thumbnail size
		$new_width = $thumbWidth;
		$new_height = $thumbHeight;


		// create a new tempopary image
		$tmp_img = imagecreatetruecolor( $new_width, $new_height );

		// copy and resize old image into new image 
		imagecopyresampled( $tmp_img, $img, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_width, $src_height );

		// save thumbnail into a file
		$fname = str_replace(".jpg", "", $fname );
		$fname = $prefix.$fname.$suffix.".jpg";
		imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
    }
  }
  // close the directory
  closedir( $dir );
}	
	
	
?>