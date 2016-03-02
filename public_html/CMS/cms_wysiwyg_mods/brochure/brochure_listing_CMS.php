<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	
	//	Extract all Active Item info from db
		
	//	has a category been chosen OR asigned ??
	if ($cat_id == 0 OR $cat_id == "" OR $cat_id == null )
	{ 
		$select_cat_id_str = '';
	}

	else
	{ 
		$select_cat_id_str = ' AND mod_brochure_items.cat_id ="'.$cat_id.'"';
	}

	$mysql_err_msg = 'Cannot Access brochure Information';	
	$sql_statement = 'SELECT * FROM mod_brochure_items, mod_brochure_cats' 
														
									.' WHERE mod_brochure_cats.mod_id = "'.$mod_id.'"'
									.  $select_cat_id_str
									.' AND mod_brochure_items.cat_id = mod_brochure_cats.cat_id'
									.' AND mod_brochure_items.active = "on"'
									.' ORDER BY mod_brochure_items.seq'
									;	
	//echo $sql_statement;														
	$item_result = ReadDB ($sql_statement, $mysql_err_msg);
	$num_rows = mysql_num_rows($item_result);
	

	if ($num_rows < 1) { include ('info_not_found.php');}
	else
	{		
			
		while ($item_info = mysql_fetch_array($item_result))
		{				
			
			echo TAB_8.'<div class="BrochureItem" id="BrochureItem_'.$item_info['item_id'].'" >'."\n";
													
			//	Item Heading
			if ($item_info['heading'])
			{
				echo TAB_9.'<h3 class="BrochureItemHeading" id="BrochureItemHeading_'.$item_info['item_id'].'" >'
				.$item_info['heading'].'</h3>'."\n";
			}														
													
			//	Thumbnail Image			
			if ( $item_info['image_file'] )
			{
				echo TAB_9.'<div class="BrochureThumb" >'."\n";				
					
					//	Is there a link associated with this image
					if ($item_info['image_href'] != '' AND $item_info['image_href'] != NULL AND $item_info['image_click'] == 'link')
					{			
						//	Open in New window
						if ( $item_info['image_href_target'] == 'new_win') 
						{ $target = 'rel="external"'; }
						
						elseif ( $item_info['image_href_target'] == 'colorbox') 
						{ $target = 'rel="ColorBoxLink"'; }	
						
						else { $target = ''; }
						
						echo TAB_10.'<a href="'.$item_info['image_href'].'" '.$target.' >'."\n";
						
						$a_end_tag = TAB_10.'</a>'."\n";
					}
					
					elseif ( $item_info['image_click'] == 'new_win' )
					{
						//	  Calculate W x H of window from size of image			
						list($img_width, $img_height) = getimagesize('_images_user/brochure/'.$item_info['image_file']);
						
						$new_win_width = $img_width * 1.1;
						$new_win_height = $img_height + 70;
						
						echo TAB_10.'<a href="javascript:openWindow(\'includes/image_show.php?img=/_images_user/brochure/'
								.$item_info['image_file'].'\','.$new_win_width.','.$new_win_height.');" >'."\n";
									
						$a_end_tag = TAB_10.'</a>'."\n";		
					}
					
					elseif ( $item_info['image_click'] == 'colorbox' )
					{
						echo TAB_10.'<a href="/_images_user/brochure/'.$item_info['image_file'].'" rel="ColorBoxImageBrochure">'."\n";
									
						$a_end_tag = TAB_10.'</a>'."\n";		
					}
					else
					{
						$a_end_tag = '';
					}
					
							echo TAB_11.'<img class="Image1" src="/_images_user/brochure/'.$item_info['image_file'].'" '."\n";
							echo TAB_11.'title="'.$item_info['image_title'].'" alt="'.$item_info['image_alt_text'].'" />'."\n";

					
						if ($item_info['image_caption'] != '' AND $item_info['image_caption'] != NULL )
						{			
							//	Print Capton Text	
							echo TAB_11.'<span class="BrochureImageCaption" >'.HiliteText(nl2br(Space2nbsp($item_info['image_caption'])))
									  .'</span>'."\n";	
						}
					
					echo $a_end_tag;

						
						
				echo TAB_9.'</div >'."\n";	

				
			}

			
			//	Item Text
			if ($item_info['text'])
			{
				echo TAB_9.'<div class="BrochureItemText" id="BrochureItemText_'.$item_info['item_id'].'" >'
				.$item_info['text'].'</div>'."\n";
			}						
				
		
			echo TAB_8.'</div >'."\n";
			
		}
		
		if($settings_info['max_chrs_display'])
		{
			echo TAB_8.'<script type="text/javascript">'."\n";
			echo TAB_9.'$(".BrochureItemText").ShowMoreLess({  "showChars" : '.$settings_info['max_chrs_display'].' });'."\n";
			echo TAB_8.'</script>'."\n";
		}
		
	}
	
?>		