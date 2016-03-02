<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
		//	RESET button ======================================================				
		echo TAB_7.'<a  href="'.$this_page.'?event_id='.$_REQUEST['event_id'].'&amp;mod_id='.$_GET['mod_id'].'&amp;tab='.$tab.'"'."\n";
			echo TAB_8.' title="Reload this page to Reset all '.$alias.' data" >' ."\n";
			echo TAB_8.'<img src="/images_misc/icon_refresh_24x24.png" alt="Reset" style="padding-right:10px; float:right;"/>' ."\n";
		echo TAB_7.'</a>'. "\n";	

		
		echo TAB_7.'<script type="text/javascript">
			
			$(document).ready( function()
			{	
				//	Show / Hide More Info window							
				$("#MoreInfoOn").click(function() {
					if(!$("#MoreInfoOn").is(":checked"))
					{
						$("#InputMoreInfo").hide("slow");
					}
					else
					{
						$("#InputMoreInfo").show("slow");
					}
						
				});

				var EventListMoreInfoHtmlOpen = 0;
				
				//	Show / Hide wysiwyg buttons							
				$("#CMS_Button_OpenHTMLPanel_MoreInfo").click(function() {

						$("#Toolbar_WysiwygMini").hide();
						EventListMoreInfoHtmlOpen = 1;
				});
				
				$("#CMS_Button_OpenTEXTPanel_MoreInfo").click(function() {

						$("#Toolbar_WysiwygMini").show();
						EventListMoreInfoHtmlOpen = 0;
				});
				

				//	update Text box value on submit - if not last accessed
				$("#UpdateEventSubmit").click(function() {
					
					if(EventListMoreInfoHtmlOpen == 0)
					{
						var LongDescHTML = $("#ModData_MoreInfo" ).html();
 
						if (LongDescHTML != null)
						{
							LongDescHTML = ReplaceTags(LongDescHTML); 	// tidy up HTML
							LongDescHTML = HTMLCharEncode(LongDescHTML); 			// Encode HTML Chars			
						}

						$("#EditHtmlTextArea_MoreInfo").val(LongDescHTML);				
						//alert(EventListMoreInfoHtmlOpen);					
					}
					
				});
				
				if
				( $("#ModData_MoreInfo").html() == "")
				{
					$("#ModData_MoreInfo").html("click here to add text...");					
				}
				
				
				
				$("#ModData_MoreInfo").click(function(){
				
					var MoreInfoHtml = $("#ModData_MoreInfo").html();
					if
					( 
							MoreInfoHtml == "click here to add text..."		
					)
					{
						$("#ModData_MoreInfo").html("");
						
					}
					//alert(MoreInfoHtml);
				});
				
				$("#EditHtmlTextArea_MoreInfo").click(function(){
					if( $("#EditHtmlTextArea_MoreInfo").val() == "click here to add text...")
					{
						$("#EditHtmlTextArea_MoreInfo").val("");					
					}
				});
/* 				
				$("#ModData_MoreInfo").blur(function(){
				
					var MoreInfoHtml = $("#ModData_MoreInfo").html();
					if
					( 
							MoreInfoHtml == ""				
					)
					{
						$("#ModData_MoreInfo").html("click here to add text...");
						
					}
					//alert(MoreInfoHtml);
				});
 */
				$("#EditHtmlTextArea_MoreInfo").blur(function(){
				
					if
					( 
						$("#EditHtmlTextArea_MoreInfo").val() == ""				
					)
					{
						$("#EditHtmlTextArea_MoreInfo").val("click here to add text...");
						
					}
					//alert(MoreInfoHtml);
				});	
			});		
		</script>'."\n";
		
		//	Display more info	
		if ($event_info['more_info_on'] == 'on') 
		{ 
			$checked = ' checked="checked"'; 
			$hidden = '';
			$Toolbar_WysiwygMini = 'show';
		}
		else 
		{ 
			$checked = '';
			$hidden = 'style="display: none;"';	
			$Toolbar_WysiwygMini = '';
		}
		
		echo TAB_7.'<fieldset class="AdminForm3" title="Check this box to Add the &quot;More Info&quot; link to display the extra information on this '.$alias.'" >'."\n";
			echo TAB_8.'<input type="checkbox" name="more_info_on" '.$checked.' id="MoreInfoOn" />' . "\n";
			echo TAB_8.' - Add the &quot;More Info&quot; link to display the extra information on this '.$alias.', as specified below:' . "\n";
		echo TAB_7.'</fieldset>'."\n";
		
		echo TAB_7.'<fieldset class="AdminForm3" id="InputMoreInfo" '.$hidden.' style="clear: left;">'."\n";
		
			echo TAB_7.'<div class="AdminForm3" style="clear: both;">'."\n";


			echo TAB_8.'<p>'." \n";			
				
				//	View / Edit HTML
				echo TAB_9.'<a href="javascript:openEditHTML(35,\'MoreInfo\',\'MoreInfo\')"'
					.' class="CMS_Button_OpenHTMLPanel" id="CMS_Button_OpenHTMLPanel_MoreInfo" title="View / Edit the HTML" >' ."\n";
					echo TAB_10.'<img src="/images_misc/icon_html_20x16.png" alt="HTML" style="border:none;"/>' ."\n";
				echo TAB_9.'</a>'. "\n";			
	 
				//	View / Edit TEXT
				echo TAB_9.'<a href="javascript:CloseEditHTML(35,\'MoreInfo\',\'MoreInfo\')"'
					.' class="CMS_Button_OpenTEXTPanel" id="CMS_Button_OpenTEXTPanel_MoreInfo" title="View / Edit the Plain Text" >' ."\n";
					echo TAB_10.'<img src="/images_misc/icon_text_20x16.png" alt="Text" style="border:none;"/>' ."\n";
				echo TAB_9.'</a>'. "\n";

				echo TAB_9.'<span id="Toolbar_WysiwygMini">'." \n";

					echo TAB_10.'<button type="button" onclick="document.execCommand(\'formatBlock\',false,\'<p>\');">P</button>'. "\n";
					
					for ($h = 1; $h < 5; $h++)
					{
						echo TAB_10.'<button type="button" onclick="document.execCommand(\'formatBlock\',false,\'<h'.$h.'>\');">'
									.'H'.$h.'</button>'. "\n";				
					}
											
					echo TAB_10.'<button type="button" onclick="javascript:Bold();" 
						onfocus="javascript:FocusOnMod();"><b>B</b></button>'. "\n";
					echo TAB_10.'<button type="button" onclick="javascript:Italic();" 
						onfocus="javascript:FocusOnMod();"><i>I</i></button>'. "\n";
					echo TAB_10.'<button type="button" onclick="javascript:Underline();" 
						onfocus="javascript:FocusOnMod();"><u>U</u></button>'. "\n";
					echo TAB_10.'<button type="button" onclick="javascript:Strike();" 
						onfocus="javascript:FocusOnMod();"><strike>S</strike></button>'. "\n";
					echo TAB_10.'<button type="button" onclick="javascript:Subscript();" 
						onfocus="javascript:FocusOnMod();">X<sub>x</sub></button>'. "\n";
					echo TAB_10.'<button type="button" onclick="javascript:Superscript();" 
						onfocus="javascript:FocusOnMod();">X<sup>x</sup></button>'. "\n";
					
					
					echo TAB_10.'<button type="button" onclick="document.execCommand(\'justifyleft\',false,null);">left</button>'. "\n";
					echo TAB_10.'<button type="button" onclick="document.execCommand(\'justifycenter\',false,null);">centre</button>'. "\n";
					echo TAB_10.'<button type="button" onclick="document.execCommand(\'justifyright\',false,null);">right</button>'. "\n";

					echo TAB_10.'<button type="button" onclick="document.execCommand(\'InsertUnorderedList\',false,null);">&bull; __</button>'. "\n";
					echo TAB_10.'<button type="button" onclick="document.execCommand(\'InsertOrderedList\',false,null);">1. __</button>'. "\n";

					echo TAB_10.'<button type="button" onclick="document.execCommand(\'Undo\',false,null);">undo</button>'. "\n";				

				echo TAB_9.'</span>'." \n";	
				
			echo TAB_8.'</p>'." \n";
							
				//	Do View/edit HTML display
				echo TAB_7.'<div class="CMS_EditHTMLPanel" id="CMS_EditHTMLPanel_MoreInfo" style="display:none; clear: both;">'." \n";		
					echo TAB_8.'<textarea id="EditHtmlTextArea_MoreInfo" name="long_desc" class="EditHtmlTextArea UpdateData">'. "\n";
						echo $event_info['long_desc'].'</textarea>'."\n";			
				echo TAB_7.'</div>'." \n\n";			
		
				//	Editable area
				echo TAB_7.'<div id="MoreInfo" class="AdminForm3 EventListContent" style="width: 800px; clear: both;">'."\n";
				
								
					echo TAB_8.'<span id="ModData_MoreInfo" class="UpdateMe" title="You can edit this text" contenteditable="true">'."\n";
						
						echo TAB_9.$event_info['long_desc'] ."\n";					

					echo TAB_8.'</span>' ."\n";	
		
			echo TAB_7.'</div>'."\n";
				
		echo TAB_7.'</fieldset>'."\n";		
		
?>