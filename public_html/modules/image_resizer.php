<?php

	ini_set('memory_limit', '256M');  //	to handle large memory useage

////////////////////////////////////////////////	CONFIGURE YOUR SETTINGS HERE	//////////////////////////////////////////////////////////

	$source_dir = "images/gallary";						//	the source dir for the images to be resized
	$dest_dir =  "images/gallary/thumbs";				//	the destination dir for the new image files
	$mode = 2;											//	resizing and cropping mode - see below for details 
	$set_width = 50;									//	new image width in pixels
	$set_height = 60;									//	new image height in pixels
	$new_name = "";  									//	asign an new name to the new image file (uses old name if left blank)
	$prefix = "thumb_";									//	add text to the very start of the new file name 
	$suffix = "";										//	add text to the very end of the new file name
	$num_pad = 0;										//	add numbering at the end ( and before the suffix) of the new file name - see below 

	/* 
		Resizing modes:
			1 - specify new width and height and stretch and/or squash to fit
			2 - specify new width and height and crop width or height to fit
			3 - adjust in proportion according set width
			4 - adjust in proportion according set height
			5 - adjust in proportion according set length for width/height which ever is the greatest (sould set both $set_height and $set_height 
				as this lenght for this best results)
		
		Numbering options ($num_pad):
			0 - no numbering (this is over-ridden if an new file name is specified to create unique filenames)
			
			1 - 9:
				1 - no padding ie: "4"
				2 - 2 digit numbering with "0" padding ie: "04"
				3 - 3 digit numbering with "0" padding ie: "004" or "034"
				etc	
	*/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	//	did they leave off the trailing "/" ?
	if ( substr($source_dir, -1) != "/") {$source_dir .= "/";}
	if ( substr($dest_dir, -1) != "/") {$dest_dir .= "/";}
	
	//	did they suplly a new filename /prefix / suffix with naughty characters ?
	$pattern = "/[^a-z0-9_\-\.]/i";
	$new_name = preg_replace($pattern, '', $new_name);
	$prefix = preg_replace($pattern, '', $prefix);									
	$suffix = preg_replace($pattern, '', $suffix);		
	
	
//	Do it !	
resizeImages( $source_dir, $dest_dir, $mode, $set_width, $set_height, $new_name, $prefix, $suffix, $num_pad );



//	======================================================================================================================
 
function resizeImages( $source_dir, $dest_dir, $mode, $set_width, $set_height, $new_name, $prefix, $suffix, $num_pad  ) 
{
  
  //	does destination dir exist ?
  if (!file_exists($dest_dir)) { mkdir ($dest_dir); }
  
  
  // open the directory
  $dir = opendir( $source_dir );
  
  $count = 1;

  // loop through it, looking for any/all JPG files:
	while (false !== ($fname = readdir( $dir ))) 
	{
		// parse path for the extension
		$info = pathinfo($source_dir . $fname);
		
		//	Remove windows thumbnail db file if present
		if ($fname == "Thumbs.db"){unlink($source_dir . $fname);}
			
		//	continue only if this is a JPEG image
		if ( strtolower($info['extension']) == 'jpg' ) 
		{

			// load image and get image size
			$img = imagecreatefromjpeg( "{$source_dir}{$fname}" );
			$width = imagesx( $img );
			$height = imagesy( $img );

			switch ($mode)
			{
				
				//	just stretch or squash where needed
				case 1:
					$src_x = 0;
					$src_y = 0;
					$src_width = $width;
					$src_height = $height;
					$new_width = $set_width;
					$new_height = $set_height;
				break;			

				//	Crop the width or height as needed to fit new dimensions
				case 2:

					$ratio_x = $set_width / $width;
					$ratio_y = $set_height / $height;

					if ($ratio_x < $ratio_y )
					{
						$src_y = 0;			
						$src_height = $height;				
											
						$src_width = $set_width / $set_height * $height;
						$src_x = round(($width - $src_width) / 2);					
					}
					
					elseif ( $ratio_x > $ratio_y  )
					{
						$src_x = 0;			
						$src_width = $width;				
										
						$src_height = $set_height / $set_width * $width;
						$src_y = round(($height - $src_height) / 2);								
					}
					
					//	image has been re-sized in proportion - no cropping necessary
					else
					{	
						$src_x = 0;
						$src_y = 0;	
						$src_width = $width;		
						$src_height = $height;							
					}

					$new_width = $set_width;
					$new_height = $set_height;						
									
									
				break;		 
				 
				//	adjust in proportion according set width
				case 3:
					$src_x = 0;
					$src_y = 0;
					$src_width = $width;
					$src_height = $height;
					$new_height = round( $height * ( $set_width / $width ) );
					$new_width = $set_width;
				
				break;	
				
				//	adjust in proportion according set height
				case 4:
					$src_x = 0;
					$src_y = 0;
					$src_width = $width;
					$src_height = $height;
					$new_width = round( $width * ( $set_height / $height ) );	
					$new_height = $set_height;				
				break;	
				
				//	resize (in proportion)according to which ever is dimension is greater
				case 5:
					if ( $width - $height > 0)
					{
						$new_height = round( $height * ( $set_width / $width ) );
						$new_width = $set_width;					
					}

					else
					{
						$new_width = round( $width * ( $set_height / $height ) );
						$new_height = $set_height;					
					}
					
					$src_x = 0;
					$src_y = 0;
					$src_width = $width;
					$src_height = $height;					
					
				break;				
				
			}
				
			
			//	create a new tempopary image
			$tmp_img = imagecreatetruecolor( $new_width, $new_height );

			//	copy and resize old image into new image 
			imagecopyresampled( $tmp_img, $img, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_width, $src_height );

			
			//	build new file name --------------
			
			//	new or existing file name ?
			if ( $new_name != "" ) {$name = $new_name;}
						
			else {$name = str_replace(".jpg", "", $fname );}
			
			//	add numbering if set OR a new filename is used (to create unique file name)
			if ( $num_pad > 0 )
			{
				$add_num = str_pad($count, $num_pad , "0", STR_PAD_LEFT);
			}
			
			elseif ( $new_name != "" )
			{
				$add_num = str_pad($count, 4, "0", STR_PAD_LEFT);
			}
			else { $add_num = "";}
			
			$filename = $prefix.$name.$add_num.$suffix.".jpg";
	
	
			//	save image into a file			
			imagejpeg( $tmp_img, "{$dest_dir}{$filename}" );
			
			//	Free up resources
			ImageDestroy($tmp_img);
	
	
			//	print statistics:
			
			echo "\t".'<div class="ResizeImageStatsDisplay" >'. "\n";
			
				echo "\t\t".'<h4>Original File: &#39;'.$fname.'&#39;</h4>'. "\n";
				echo "\t\t".'<ul>'. "\n";
					echo "\t\t\t".'<li>New filename: '.$filename.'</li>'. "\n";
					echo "\t\t\t".'<li>original width = '.$width.'</li>'. "\n";
					echo "\t\t\t".'<li>original height = '.$height.'</li>'. "\n";		
					echo "\t\t\t".'<li>new_width = '.$new_width.'</li>'. "\n";
					echo "\t\t\t".'<li>new_height = '.$new_height.'</li>'. "\n";
					
					if ($src_x > 0) {$crop_msg = $src_x.'px from left and right';}
					elseif ($src_y > 0) {$crop_msg = $src_y.'px from top and bottom';}
					else {$crop_msg = '(none)';}
						
					echo "\t\t\t".'<li>Cropping: '.$crop_msg.'</li>'. "\n";
				echo "\t\t".'</ul>'. "\n";

				echo '<br/><hr/><br/>';

			echo "\t".'</div>'. "\n";			
			

			$count++;
			
		}
		
	}
  
  // close the directory
  closedir( $dir );
  
}	
	
	
?>