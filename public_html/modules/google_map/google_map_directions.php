<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$to_address = strip_tags ($google_map_info['directions_address']);
	
	echo "\n";
	echo TAB_7.'<!--	START Google Map  Directions Finder		-->'."\n";
	echo "\n";
							
	echo TAB_7.'<div class="GoogleDirections" >'. "\n";
							
		echo TAB_8.'<h4>Get Driving Directions:</h4>'. "\n";	
		echo TAB_8.'<form action="'.GOOGLE_MAP_DIRECTIONS_URL.'" method="get" >'. "\n";	
			echo TAB_9.'<ul> '. "\n";
				echo TAB_10.'<li> '. "\n";							
					echo TAB_11.'<label>To:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>'. "\n";							
					echo TAB_11.'<input type="text" name="daddr" id="EndAddr_'.$google_map_info['mod_id'].'"'
								.' value="'.$to_address.'"  readonly="readonly" size="50" />'. "\n";						
				echo TAB_10.'</li> '. "\n";				
				echo TAB_10.'<li> '. "\n";							
					echo TAB_11.'<label>From:</label>'. "\n";	
					echo TAB_11.'<input type="text" name="saddr" id="StartAddr_'.$google_map_info['mod_id'].'" value="" size="50" />'. "\n";
					echo TAB_11.'<input type="submit" value="Get Directions" />'. "\n";
				echo TAB_10.'</li> '. "\n";	
			echo TAB_9.'</ul> '. "\n";	
		echo TAB_8.'</form>'. "\n";	
								
	echo TAB_7.'</div>'. "\n";
								
	echo "\n";
	echo TAB_7.'<!--	END Google Map  Directions Finder		-->'."\n";
	echo "\n";					
		

?>