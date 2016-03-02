<?php
				
			
			
	echo TAB_7.'<script type="text/javascript" >

		function SaveModDataListItems_'.$mod_id.'()
		{
			function GetListItemHtml (mod_id, li_id)
			{
				var HTMLcontent = $("#ListItems_" + '.$mod_id.' + "_" + li_id + " span").html();
			
				if (HTMLcontent != null)
				{
					HTMLcontent = ReplaceTags(HTMLcontent); 	// tidy up HTML
					HTMLcontent = HTMLCharEncode(HTMLcontent); 			// Encode HTML Chars			
				}

				return HTMLcontent;
			
			};
	
			$.ajax({            
				 url: "CMS/cms_update/cms_update_wysiwyg.php"           
				,type: "POST"            
				,data:
				{ 
					 mod_id : '.$mod_id ."\n";	
				 
	 
	for ($i = 0; $i < count($li_id_array); $i++ )
	{
		echo '					,liid_'.$li_id_array[$i].' : GetListItemHtml ('.$mod_id.', '.$li_id_array[$i].')'."\n";		
	}
				
				
	echo '				}
	
				,success: function()
				{
					location.reload(true)
				
				}
	
			}); 	
		
			//if (refresh == 1)
			//{	
				//location.reload(true);
			//}
		
		};'."\n";	
		
	
	echo TAB_7.'</script>'."\n";				
			

?>