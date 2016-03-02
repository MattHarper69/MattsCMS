<?php

	echo TAB_6.'<fieldset class="CMS_MiniPageLayout_Window">'."\n";
								
		echo TAB_7.'<div id="CMS_MiniPageLayout_Container" class="CMS_MiniPageLayout">' ."\n";
		
			echo TAB_8.'<div id="CMS_MiniPageLayout_banner'.$id_tag_prefix.'" class="CMS_MiniPageLayout Hilight"' ."\n";
				echo TAB_9.' onClick="javascript:SwitchMiniLayoutDivsClick(\'banner\');"' ."\n"; 
				echo TAB_9.' title="Activate / De-activate the HEADER"></div>' ."\n";
				
			echo TAB_8.'<div id="CMS_MiniPageLayout_Wrapper2" class="CMS_MiniPageLayout">' ."\n";

				echo TAB_9.'<div id="CMS_MiniPageLayout_Wrapper3" class="CMS_MiniPageLayout">' ."\n";

					echo TAB_10.'<div id="CMS_MiniPageLayout_side_1'.$id_tag_prefix.'" class="CMS_MiniPageLayout Hilight"' ."\n";
						echo TAB_11.' onClick="javascript:SwitchMiniLayoutDivsClick(\'side_1\');"' ."\n"; 
						echo TAB_11.' title="Activate / De-activate the SIDE 1 Column">' ."\n";
					echo TAB_10.'</div>' ."\n";
					
					echo TAB_10.'<div id="CMS_MiniPageLayout_side_2'.$id_tag_prefix.'" class="CMS_MiniPageLayout Hilight"' ."\n";
						echo TAB_11.' onClick="javascript:SwitchMiniLayoutDivsClick(\'side_2\');"' ."\n"; 
						echo TAB_11.' title="Activate / De-activate the SIDE 2 Column">' ."\n";
					
				echo TAB_9.'</div>' ."\n";	
				
				echo TAB_9.'<div id="CMS_MiniPageLayout_footer'.$id_tag_prefix.'" class="CMS_MiniPageLayout Hilight"' ."\n";
					echo TAB_10.' onClick="javascript:SwitchMiniLayoutDivsClick(\'footer\');"' ."\n"; 
					echo TAB_10.' title="Activate / De-activate the FOOTER">' ."\n";

				echo TAB_9.'</div>' ."\n";				
				
			echo TAB_8.'</div>' ."\n";
		
		echo TAB_7.'</div>' ."\n";
								
	echo TAB_6.'</fieldset>'."\n";		
	
	
?>