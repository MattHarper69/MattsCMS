

		<style type="text/css">
		
			.Arial {
			
				color: #000000;
				font-family: Arial;
				
					
			}
			
			.Arial_Black {
			
				color: #000000;
				font-family: Arial black;
				
					
			}

			.Comic_Sans_MS {
			
				color: #000000;
				font-family: Comic Sans MS;
				
					
			}

			.Courier_New {
			
				color: #000000;
				font-family: Courier New;
				
					
			}
			
			.Geneva {
			
				color: #000000;
				font-family: Geneva;
				
					
			}	
			
			.Georgia {
			
				color: #000000;
				font-family: Georgia;
				
					
			}	
			.Helvetica {
			
				color: #000000;
				font-family: Helvetica;
				
					
			}
			.Impact {
			
				color: #000000;
				font-family: Impact;
				
					
			}	
			.Sans-serif {
			
				color: #000000;
				font-family: sans-serif;
				
					
			}
			
			.Times_New_Roman {
			
				color: #000000;
				font-family: Times New Roman;
				
					
			}	
			.Trebuchet_MS {
			
				color: #000000;
				font-family: Trebuchet MS;
				
					
			}	
			
			.Verdana {
			
				color: #000000;
				font-family: Verdana;
				
					
			}			
		</style>		
		


	<?php
	
		
		$font_faces = array
		(
			"Arial","Arial_Black","Comic Sans MS","Courier New","Geneva","Georgia",
			"Helvetica","Impact","Sans-serif","Times New Roman","Trebuchet MS","Verdana" 
		);
		
		$font_weights = array
		(
			"normal","bold","bolder","lighter","100","200",
			"300","400","500","600","700","800","900" 
		);

		$font_styles = array
		(
			"normal","italic","oblique"
		);

		$font_tags = array
		(
			"h1","h2","h3","h4","p"
		);
		
echo '<div style = "padding:20px;">';	
	
	echo '<form action="'.$_SERVER['PHP_SELF'].'?p='.$page_id.'" method="post"> '."\n";
	
		echo '<p>';
		
			echo 'Font Size:<input name="font_size" type="text" size="2" value="'.$_POST['font_size'].'" />em';

			echo ' | ';
			
			echo 'Font weight:<select name="font_weight" onchange="this.form.submit()" > '."\n";
	
			reset ($font_weights);
			foreach ($font_weights as $font_weight)
			{ 
				if ($font_weight == $_POST['font_weight']) {$selected = 'selected="selected"';}
				else {$selected = '';}
			
				echo '<option '.$selected.' value="'.$font_weight.'" >'.$font_weight.'</option>';		
			}
			
			echo '</select> '."\n";	
			
			echo ' | ';
			
			echo 'Font Style:<select name="font_style" onchange="this.form.submit()" > '."\n";
	
			reset ($font_styles);
			foreach ($font_styles as $font_style)
			{ 
				if ($font_style == $_POST['font_style']) {$selected = 'selected="selected"';}
				else {$selected = '';}
			
				echo '<option '.$selected.' value="'.$font_style.'" >'.$font_style.'</option>';		
			}
			
			echo '</select> '."\n";	

			echo ' | ';
			
			echo 'Tag:<select name="font_tag" onchange="this.form.submit()" > '."\n";
	
			reset ($font_tags);
			foreach ($font_tags as $font_tag)
			{ 
				if ($font_tag == $_POST['font_tag']) {$selected = 'selected="selected"';}
				else {$selected = '';}
			
				echo '<option '.$selected.' value="'.$font_tag.'" >'.$font_tag.'</option>';		
			}
			
			echo '</select> '."\n";				
			echo '<input type="submit" value="Change" /> '."\n";

		echo '</p> '."\n";

	echo '</form> '."\n";

		echo '<div style = "background-color:#FFFFFF; padding:20px;" >';	
			
			reset ($font_faces);
			foreach ($font_faces as $font_face)
			{ 

				echo '<'.$_POST['font_tag'].' style="color: #000000; font-family:'.$font_face.'; '.
					
						'font-size:'.$_POST['font_size'].'em; font-style:'.$_POST['font_style'].'; '.
						'font-weight:'.$_POST['font_weight'].'; " >'.
						
						$font_face.' - The quick brown fox jumps over the lazy dog</'.$_POST['font_tag'].'>'."\n";
				
			};
		echo '</div>';
	

echo '</div>';





