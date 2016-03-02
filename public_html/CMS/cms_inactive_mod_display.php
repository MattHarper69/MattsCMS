<?php

		if ($mod_info['active'] != 'on')
		{
				echo TAB_7.'<script type="text/javascript" >


				$(document).ready(function()
				{
					if (readCookie("CMS_ShowInActive_'.$mod_id.'") == 1)
					{
						$("#'.$div_name.'").show();	
						
						$("#CMS_Button_showHideInActiveMod_'.$mod_id.' img").attr({
						
											 src: "/images_misc/Button_hide.gif"
											,alt: "Hide"
										});	

					}
					else
					{
						$("#'.$div_name.'").hide();
						
						$("#CMS_Button_showHideInActiveMod_'.$mod_id.' img").attr({
						
											 src: "/images_misc/Button_show.gif"
											,alt: "Show"
										});	

					}
				});
				
			</script>'."\n";			 
			 
			 
			 echo TAB_7.'<div id="InActiveModDisplay_'.$mod_id.'" class="InActiveModDisplay" title="Click to Show / Hide In-Active Modules">'."\n";
				//	Show Mod Button
				echo TAB_8.'<button id="CMS_Button_showHideInActiveMod_'.$mod_id.'" class="CMS_WysiwygButton CMS_Button_showHideInActiveMod"'
					.' onClick="showHideInActiveMod(\''.$mod_id.'\',\''.$div_name.'\', '.$edit_enabled.')"'
					.' title="Click to Show / Hide In-Active Modules" >'."\n";
					echo TAB_9.'<img src="/images_misc/Button_show.gif" alt="Show" style="border:none;outline: none;"/>' ."\n";
				echo TAB_8.'</button>'."\n";	
				
			 echo TAB_7.'</div>'."\n";
			 
			 $mod_active = 0;
		}
		
		else { $mod_active = 1;}

?>