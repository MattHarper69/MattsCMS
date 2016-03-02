<?php		
		
		echo TAB_2.'<div class="CMS_WysiwygToolbar" id="CMS_WysiwygToolbar"  style="width:750px;">'." \n";

			echo TAB_3.'<fieldset class="AdminForm3" id="WysiwygButtons" style="clear: both;">'."\n";

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
					
					$font_colour = array
					(
						 '#000000'
						,'#ffffff'
						,'#ff0000'
						,'#008800'
						,'#0000ff'
						,'#0077ff'
						,'#ff00ff'
					);

					foreach ($font_colour as $font_colour)
					{
							echo TAB_10.'<button type="button" onclick="javascript:FontColour(\''.$font_colour.'\');" 
							onfocus="javascript:FocusOnMod();" style="background-color:'.$font_colour.';">A</button>'. "\n";						
					}
					
					echo TAB_10.'<button type="button" onclick="document.execCommand(\'justifyleft\',false,null);">left</button>'. "\n";
					echo TAB_10.'<button type="button" onclick="document.execCommand(\'justifycenter\',false,null);">centre</button>'. "\n";
					echo TAB_10.'<button type="button" onclick="document.execCommand(\'justifyright\',false,null);">right</button>'. "\n";
					echo TAB_10.'<button type="button" onclick="document.execCommand(\'justifyFull\',false,null);">Justify</button>'. "\n";

					echo TAB_10.'<button type="button" onclick="document.execCommand(\'InsertUnorderedList\',false,null);">&bull; __</button>'. "\n";
					echo TAB_10.'<button type="button" onclick="document.execCommand(\'InsertOrderedList\',false,null);">1. __</button>'. "\n";

					echo TAB_10.'<button type="button" onclick="document.execCommand(\'Undo\',false,null);">undo</button>'. "\n";
					


					
					
		//echo TAB_3.'<button  onclick="document.execCommand(\'forecolor\',false,\'black\');">Black</button>'. "\n";
		//echo TAB_3.'<button  onclick="document.execCommand(\'forecolor\',false,\'green\');">Green</button>'. "\n";
		
		/*  		
			echo TAB_3.'<button  onclick="document.execCommand(\'bold\',false,null);"><b>B</b></button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'italic\',false,null);"><i>I</i></button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'underline\',false,null);"><u>U</u></button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'strikethrough\',false,null);"><strike>S</strike></button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'subscript\',false,null);">X<sub>x</sub></button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'superscript\',false,null);">X<sup>x</sup></button>'. "\n";


			echo TAB_3.'<button  onclick="javascript:Bold();" onfocus="javascript:FocusOnMod();"><b>B</b></button>'. "\n";
			echo TAB_3.'<button  onclick="javascript:Italic();" onfocus="javascript:FocusOnMod();"><i>I</i></button>'. "\n";
			echo TAB_3.'<button  onclick="javascript:Underline();" onfocus="javascript:FocusOnMod();"><u>U</u></button>'. "\n";
			echo TAB_3.'<button  onclick="javascript:Strike();" onfocus="javascript:FocusOnMod();"><strike>S</strike></button>'. "\n";
			echo TAB_3.'<button  onclick="javascript:Subscript();" onfocus="javascript:FocusOnMod();">X<sub>x</sub></button>'. "\n";
			echo TAB_3.'<button  onclick="javascript:Superscript();" onfocus="javascript:FocusOnMod();">X<sup>x</sup></button>'. "\n";			
			
			
			
			
			echo TAB_3.'<button  onclick="document.execCommand(\'justifyleft\',false,null);">left</button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'justifycenter\',false,null);">centre</button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'justifyright\',false,null);">right</button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'Indent\',false,null);">-&gt;</button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'Outdent\',false,null);">&lt;-</button>'. "\n";

			echo TAB_3.'<button  onclick="document.execCommand(\'backcolor\',false,\'lime\');">hilite green</button>'. "\n";


			echo TAB_3.'<button  onclick="document.execCommand(\'InsertUnorderedList\',false,null);">ul</button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'InsertOrderedList\',false,null);">ol</button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'InsertHorizontalRule\',false,\'UserInsertedHR\');">-</button>'. "\n";
 */	
			echo TAB_3.'<button  onclick="document.execCommand(\'createlink\',true);">href</button>'. "\n";
			echo TAB_3.'<button  onclick="document.execCommand(\'Unlink\',true);"><strike>href</strike></button>'. "\n";
				
			echo TAB_3.'<button  onclick="document.execCommand(\'insertimage\',true);">image</button>'. "\n";
echo TAB_3.'<button  onclick="document.execCommand(\'insertHorizontalRule\',true);">_</button>'. "\n";			
			//echo TAB_3.'<button  onclick="document.execCommand(\'Undo\',false,null);">undo</button>'. "\n";
			//echo TAB_3.'<button  onclick="document.execCommand(\'Redo\',false,null);">redo</button>'. "\n";	

			//echo TAB_3.'<button  onclick="document.execCommand(\'IDM_HORIZONTALLINE\',false,null);">FontSize7</button>'. "\n";
			//echo TAB_3.'<button  onclick="document.execCommand(\'inserthtml\',true, \'<span>\');">span</button>'. "\n";	// for non IE
			echo TAB_3.'<button  onclick="document.selection.createRange().pasteHTML(\'&copy;\');">&copy;</button>'. "\n";	// for IE
			//echo TAB_3.'<button  onclick="document.execCommand(\'inserthtml\',false, \'&copy;\');">&copy;</button>'. "\n";	// for non IE
			//echo TAB_3.'<button  id="addSpan">Span</button>'. "\n";	

			echo TAB_3.'</fieldset>'."\n";
			
			

			
		echo TAB_2.'</div>'." \n";
		
?>