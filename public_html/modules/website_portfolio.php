<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

if (isset($_REQUEST['cat_id']))
{
	$cat_id = $_REQUEST['cat_id'];
}
else
{
	$cat_id = '';
}

if (isset($_REQUEST['site_id']))
{
	$site_id = $_REQUEST['site_id'];
}
else
{
	$site_id = '';
}

if ($site_id == "" OR $site_id == null )
{
	echo TAB_7.'<div class="WebPortfolioList" id="WebPortfolioList_'.$mod_id.'" >'."\n";

		//	Do Category Selection Box
		echo TAB_8.'<div class="WebPortfolioSelectBox"  >'."\n";

			//echo TAB_9.'<form action="'.$_SERVER['REQUEST_URI'].'" method="post" >'."\n";
			echo TAB_9.'<form action="?p='.$page_id.'" method="post" >'."\n";
				echo TAB_10.'<fieldset class="WebPortfolioSelectBox">'."\n";			
					echo TAB_11.'<label for="WebPortfolioSelectBox_'.$mod_id.'" >Choose a Category: </label>'."\n";
					echo TAB_11.'<select class="WebPortfolioSelectBox" name="cat_id" onchange="this.form.submit()" '
								.'id="WebPortfolioSelectBox_'.$mod_id.'" >'."\n";
						
						echo TAB_12.'<option value="" >ALL</option>'."\n";
					
					//	Get all Categories
					$mysql_err_msg = 'Cannot Access Website Portfolio Information(Categories)';
					$sql_statement = 'SELECT * FROM mod_web_port_cats WHERE '.
							
															'active="on" '.
															'ORDER BY seq';
															
			 		$result = ReadDB ($sql_statement, $mysql_err_msg);
				
					$num_rows = mysql_num_rows($result);
					while ($cat_row = mysql_fetch_array($result))
					{
						if ($cat_row['cat_id'] == $cat_id) {$selected = 'selected="selected"';}
						else {$selected = '';}
					
						echo TAB_12.'<option '.$selected.' value="'.$cat_row['cat_id'].'" >'.$cat_row['name'].'</option>'."\n";
					}
				
					echo TAB_11.'</select >'."\n";
					
					//	Do Button if Javascript turned Off ( note  <ins> tags are used to Validate only )
					echo TAB_11.'<noscript><ins><input type="submit" value="Change" /></ins></noscript> '."\n";
				echo TAB_10.'</fieldset >'."\n";				
			echo TAB_9.'</form >'."\n";	
															
		echo TAB_8.'</div >'."\n";	
		
		
		//	has a category been chosen OR asigned ??
		if ($cat_id == "" OR $cat_id == null )
		{ $cat_id_selector_str = '';}

		else {$cat_id_selector_str = ' AND cat_id ="'.$cat_id.'"';}

		//	Extract all Active entries from db
		
		// --	remove blank departments:
		$mysql_err_msg = 'Deleting blank entries in Website Portfolio';
		$sql_statement = 'DELETE FROM mod_website_portfolio WHERE name = ""';
		ReadDB ($sql_statement, $mysql_err_msg);	
		
		//	read from db	----------
		$mysql_err_msg = 'Cannot Access Website Portfolio Information';
		$sql_statement = 'SELECT * FROM mod_website_portfolio WHERE' 
												
												.' mod_id = '.$mod_id		
												.$cat_id_selector_str
												.' AND active="on"'
												.' ORDER BY seq'
												;
												
		$result = ReadDB ($sql_statement, $mysql_err_msg);
		$num_rows = mysql_num_rows($result);
		
	if ($num_rows < 1) { include ('info_not_found.php');}
	else
	{		
		//echo TAB_8.'<ul class="WebPortfolioList"  >'."\n";		
		while ($site_row = mysql_fetch_array($result))
		{				
			//	Open Link to Website in New window ???
			if ($site_row['link_in_new_window'] == 'on')
			{ 
				$target = ' rel="external" ';
				$title_text = ': [This opens in a New Window]';
			}
			else 
			{
				$target = '';
				$title_text = '';
			}
					
			if ( $site_row['link_to_site'] == 'on')	//	do link to Website ???
			{ 
				$href_open_tag = '<a class="WebPortfolioLink" href="'.$site_row['url'].'" '  
								.$target .' title="Click to Visit the Website of: '.$site_row['name'].$title_text.'" >';

				$href_open_tag_img = '<a class="WebPortfolioLink" href="'.$site_row['url'].'" rel="SiteNewWin"'  
								.' title="'.$site_row['name'].' - '.$site_row['url'].'" >';
								
				$href_close_tag = '</a>';
			}
					
			elseif ( $site_row['more_info_set'] == 'on')	//	Do Link to "more Info" on the website
			{ 
				$href_open_tag = '<a class="WebPortfolioLink" href="index.php?p='.$page_id
								.'&amp;cat_id='.$cat_id.'&amp;site_id='.$site_row['site_id'].'" '
								.' title="Click to for more info on the Website of: '.$site_row['name'].'" >';
				
				$href_open_tag_img = '<a class="WebPortfolioLink" href="index.php?p='.$page_id
								.'&amp;cat_id='.$cat_id.'&amp;site_id='.$site_row['site_id'].'" rel="SiteNewWin"'
								.' title="Click to for more info on the Website of: '.$site_row['name'].'" >';  
		
				
				$href_close_tag = '</a>';
			}

			else	//	no link
			{ 
				$href_open_tag = '';			
				$href_open_tag_img = '';
				$href_close_tag = '';			
			}			

			
			echo TAB_8.'<div class="WebPortfolioSite" id="WebPortfolioSite_'.$site_row['site_id'].'" >'."\n";
			
				//	Thumbnail Image			
				echo TAB_9.'<div class="WebPortfolioThumb" >'."\n";
				
				if ( $site_row['image_file_thumb'] != "" AND $site_row['image_file_thumb'] != NULL )
				{$thumb_image = $site_row['image_file_thumb'];}
					
				//	attempt to use Large image if exists and no thumbnail image availabe
				elseif ( $site_row['image_file'] != "" AND $site_row['image_file'] != NULL )
				{$thumb_image = $site_row['image_file'];}

				else {$thumb_image = NULL;}
				if ( $thumb_image != "" AND $thumb_image != NULL )
				{
					echo TAB_10.$href_open_tag_img."\n";					
						echo TAB_11.'<img class="WebPortfolioThumb" src="/_images_user/'.$thumb_image.'" '
							.'alt="Thumbnail Image of the Website of: '.$site_row['name'].'" />'."\n";						
					echo TAB_10.$href_close_tag."\n";
				}
				
				echo TAB_9.'</div >'."\n";	
					
				echo TAB_9.'<div class="WebPortfolioListInfo" >'."\n";
					
					//	Heading
					echo TAB_10.'<h4 class="WebPortfolioListHeading" >'."\n";					
						echo TAB_11.$href_open_tag."\n";
							echo TAB_12. HiliteText($site_row['name'])."\n";
						echo TAB_11.$href_close_tag."\n";				
					echo TAB_10.'</h4>'."\n";
				
					//	URL	(link to Site)
					echo TAB_10.'<p class="WebPortfolioListUrl" >'."\n";
					
					if ( $site_row['link_to_site'] == 'on')
					{ 
						echo TAB_11.'<a class="WebPortfolioLink" href="'.$site_row['url'].'" '
								.$target.' title="Click to Visit the Website of: '.$site_row['name'].$title_text.'" >'."\n";
							echo TAB_12. HiliteText($site_row['display_url'])."\n";
						echo TAB_11.'</a>'."\n";
					}
					
					else	//	 Link to "more Info" on the website
					{ 
						echo TAB_11.$href_open_tag."\n";
							echo TAB_12. HiliteText($site_row['display_url'])."\n";
						echo TAB_11.$href_close_tag."\n"; 
					}
						
					echo TAB_10.'</p>'."\n";
					
					//	Short Description
					if ( $site_row['short_text'] != "" AND $site_row['short_text'] != NULL )
					{
						echo TAB_11.'<p class="WebPortfolioListDesc" >'."\n";
							echo TAB_12. HiliteText($site_row['short_text'])."\n";						
						echo TAB_11.'</p>'."\n";
					}
					
					//	List Features
					echo TAB_10.'<ul class="WebPortfolioFeatures" >'."\n";
					for ($count =1 ; $count < 9; $count++ )
					{
						if ($site_row['feature_'.$count] == "") {}
						else
						{
							echo TAB_11.'<li class="WebPortfolioFeatures" >'.HiliteText($site_row['feature_'.$count]).'</li>'."\n";
						}
					}
					echo TAB_10.'</ul >'."\n";
					
					//	Links
					echo TAB_10.'<p class="WebPortfolioListMoreInfoLink" >'."\n";
					if ( $site_row['link_to_site'] == 'on' ) 
					{ 	
						//	link to Website
						echo TAB_11.'<a href="'.$site_row['url'].'" '
								.$target.' title="Click to Visit the Website of: '.$site_row['name'].$title_text.'" >'."\n";
							echo TAB_12.'Visit This Website'."\n";
						echo TAB_11.'</a>'."\n";
					}
					if ( $site_row['more_info_set'] == 'on' ) 
					{ 	
						//	Do Link to "more Info" on the website
						echo TAB_11.'<a href="index.php?p='.$page_id.'&amp;site_id='.$site_row['site_id'].'&amp;cat_id='.$cat_id.'" '
								.' title="Click to for more info on the Website of: '.$site_row['name'].'" >'."\n";
							echo TAB_12.'More Info...'."\n";
						echo TAB_11.'</a>'."\n";
					}					

					echo TAB_10.'</p>'."\n";						
				echo TAB_9.'</div >'."\n";
					

			
			echo TAB_8.'</div >'."\n";
			
		}
	
	}
		
	echo TAB_7.'</div>'."\n";
	
}		

else
{
	echo TAB_7.'<div class="WebPortfolioMoreInfo" id="WebPortfolioMoreInfo_'.$mod_id.'" >'."\n";
		
	//	read from db	----------
	$mysql_err_msg = 'Cannot Access Website Portfolio Information';
	$sql_statement = 'SELECT * FROM mod_website_portfolio WHERE ' 
		
											.'site_id = "'.$site_id.'" '
											.'AND active="on" ';

												
	$result = ReadDB ($sql_statement, $mysql_err_msg);
	$num_rows = mysql_num_rows($result);
	$site_row = mysql_fetch_array($result);
	
	if ($num_rows < 1 OR $site_row['more_info_set'] != "on" ) { include ('info_not_found.php');}
	
	else
	{


		//	Open Link to Website in New window ???
		if ($site_row['link_in_new_window'] == 'on')
		{ 
			$target = ' rel="external" ';
			$title_text = ': [This opens in a New Window]';
		}
		else 
		{
			$target = '';
			$title_text = '';
		}		
					
		if ( $site_row['link_to_site'] == 'on' )	//	do link to Website ???
		{ 
			$href_open_tag = '<a class="WebPortfolioLink" href="'.$site_row['url'].'" '  
							.$target.' title="Click to Visit the Website of: '.$site_row['name'].$title_text.'" >';
									
			$href_close_tag = '</a>';
			
			$href_open_tag_img = '<a class="WebPortfolioLink" href="'.$site_row['url'].'" rel="SiteNewWin"'  
							.' title="'.$site_row['name'].' - '.$site_row['url'].'" >';			
			
		}	
		else
		{
			$href_open_tag = '';									
			$href_close_tag = '';
			$href_open_tag_img = '';
		}
		

							
		//	Heading
		echo TAB_8.'<h4 class="WebPortfolioHeading" >'."\n";					
			echo TAB_9.$href_open_tag."\n";
				echo TAB_10. HiliteText($site_row['name'])."\n";
			echo TAB_9.$href_close_tag."\n";				
		echo TAB_8.'</h4>'."\n";
		
		//	URL
		echo TAB_8.$href_open_tag."\n";
			echo TAB_9. HiliteText($site_row['display_url'])."\n";
		echo TAB_8.$href_close_tag."\n";

		//	Long Description
		if ( $site_row['long_text'] != "" AND $site_row['long_text'] != NULL )
		{
			echo TAB_8.'<p class="WebPortfolioDesc" >'."\n";
				echo TAB_9. HiliteText($site_row['long_text'])."\n";								
			echo TAB_8.'</p>'."\n";
		}
		
		//	Show Short Text if exists and no Long text specified
		elseif ( $site_row['short_text'] != "" AND $site_row['short_text'] != NULL )
		{
			echo TAB_8.'<p class="WebPortfolioDesc" >'."\n";
				echo TAB_9. HiliteText($site_row['short_text'])."\n";						
			echo TAB_8.'</p>'."\n";
		}
		
		else {}
		
		//	List Features
		echo TAB_8.'<ul class="WebPortfolioFeaturesMore" >'."\n";
		for ($count =1 ; $count < 9; $count++ )
		{
			if ($site_row['feature_'.$count] == "") {}
			else
			{
				echo TAB_9.'<li class="WebPortfolioFeaturesMore" >'.HiliteText($site_row['feature_'.$count]).'</li>'."\n";
			}
		}
		echo TAB_8.'</ul >'."\n";
			echo TAB_8.'<p class="WebPortfolioListMoreInfoLink" >'."\n";
			
			//	link to Website
			if ( $site_row['link_to_site'] == 'on' ) 
			{ 							
				echo TAB_9.'<a href="'.$site_row['url'].'" '				
						.$target.' title="Click to Visit the Website of: '.$site_row['name'].$title_text.'" >'."\n";
					echo TAB_10.'Visit This Website'."\n";
				echo TAB_9.'</a>'."\n";
			}	
				//	Return to Listing LINK
				echo TAB_9.'<a href="?p='.$page_id.'&amp;cat_id='.$cat_id.'" '.
					'title="Click to Return to the previous Website Listing" >'."\n";
					echo TAB_10.'Return to Listing'."\n";
				echo TAB_9.'</a>'."\n";	
				
		echo TAB_8.'</p>'."\n";	
	
	
		if ( $site_row['image_file'] != "" AND $site_row['image_file'] != NULL )		
		{		
			//	Large Image
			echo TAB_8.'<div class="WebPortfolioLarge" >'."\n";
				echo TAB_9.$href_open_tag_img."\n";					
					echo TAB_10.'<img class="WebPortfolioLarge" src="/_images_user/'.$site_row['image_file'].'" '
						.'alt="Image of the Website of: '.$site_row['name'].'" />'."\n";						
				echo TAB_9.$href_close_tag."\n";				
			echo TAB_8.'</div >'."\n";		
		}
		
		//	attempt to use thumbnail image if exists and no Large image availabe
		elseif ( $site_row['image_file_thumb'] != "" AND $site_row['image_file_thumb'] != NULL )		
		{		
			//	Thumbnail Image
			echo TAB_8.'<div class="WebPortfolioThumb" >'."\n";
				echo TAB_9.$href_open_tag_img."\n";					
					echo TAB_10.'<img class="WebPortfolioThumb" src="/_images_user/'.$site_row['image_file_thumb'].'" '
						.'alt="Thumbnail Image of the Website of: '.$site_row['name'].'" />'."\n";						
				echo TAB_9.$href_close_tag."\n";				
			echo TAB_8.'</div >'."\n";		
		}

		else {}
		
	}
	
	echo TAB_7.'</div>'."\n";		
	
}
		
?>