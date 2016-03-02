<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	

		//	Label For Captcha Image			
		if ($captcha_info['show_label'] == 'on' ) 
		{				
			
			echo TAB_12.'<div class="CaptchaLabel" id="CaptchaLabel_'.$captcha_id.'"> '. "\n";
				echo TAB_13.'<label for="ImageCaptcha_'.$captcha_id.'" >'.$captcha_info['label'].'</label>'. "\n";						
			echo TAB_10.'</div> '. "\n";
		}						
			echo TAB_12.'<a href="/modules/captcha/captcha_explain_msg.php?id='.$captcha_id.'" ' ."\n";
				echo TAB_13.'title="why we are asking for the code" rel="CaptchaNewWin" >' ."\n";						
				echo TAB_13.'<img class="ImageCaptcha" id="ImageCaptcha_'.$captcha_id.'" '
					.'src="/create_captcha.php'

					.'?captcha_id='.$captcha_id.'" '."\n";
					echo TAB_14.'alt="Security Code Image Failed - please REFRESH page" '. "\n";
					
					if ($captcha_info['width'] != '0' AND $captcha_info['width'] != '') 
					{$width_str = 'width="'.$captcha_info['width'].'"';}
					else { $width_str = ''; }
					
					if ($captcha_info['height'] != '0' AND $captcha_info['height'] != '') 
					{$height_str = 'height="'.$captcha_info['height'].'"';}										
					else { $height_str = ''; }
					
					echo TAB_14.$width_str.' '.$height_str. "\n";											
				echo TAB_13.'title="Enter the letters you see Here ( A - Z )"  />'. "\n";
			echo TAB_12.'</a>' ."\n";
			
			//	Do "Reload Code" and "whats this" links		
			if ($captcha_info['show_new_code_link'] == 'on' OR $captcha_info['show_help_link'] == 'on')
			{
				echo TAB_12.'<ul class="CaptchaExplain" >' ."\n";
				
				//	Captcha Reload Link								
				if ($captcha_info['show_new_code_link'] == 'on' ) 
				{			
					echo TAB_13.'<li>' ."\n";
															
						echo TAB_14.'<input name="reload_captcha" type="submit" class="CaptchaReload" id="CaptchaReload_'.$captcha_id.'" '
							.'value="Get New Code" tabindex="'.$element_count.'" '. "\n";						
						echo TAB_14.'title="Click here to Reload a new Code - '
									.'If you are having difficulty reading the current one" />'."\n";
					echo TAB_13.'</li>' ."\n";
				}
				
				//	Captcha Explain Link								
				if ($captcha_info['show_help_link'] == 'on' ) 
				{
					echo TAB_13.'<li>' ."\n";		
						echo TAB_14.'<a rel="CaptchaNewWin" class="CaptchaExplainLink" id="CaptchaExplainLink_'.$captcha_id.'" '
								//.'href="javascript:openWindow(\'/modules/captcha/captcha_explain_msg.php?id='.$captcha_id.'\',400,280 );" '
								.'href="/modules/captcha/captcha_explain_msg.php?id='.$captcha_id.'" '
							.' >What is this ?</a>' ."\n";
						echo TAB_14.'<ul>' ."\n";
							echo TAB_15.'<li class="CaptchaExplainText" >'.$captcha_info['explain_text'].'</li>' ."\n";
						echo TAB_14.'</ul>' ."\n";	
					echo TAB_13.'</li>' ."\n";							
				}
					//echo TAB_13.'<li></li>' ."\n";	//	chuck an LI in to validate
				
				echo TAB_12.'</ul>' ."\n";	
				
			}

	
	
?>