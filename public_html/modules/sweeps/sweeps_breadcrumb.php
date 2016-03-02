<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	Get Parent IDs of Opened link	 and put in array---------- 
	$sweeps_opened_link_ids = GetSweepsCatLinkPath ($cat_id);

	//	Reverse array and add current cat to array
	$sweeps_opened_link_ids = array_reverse($sweeps_opened_link_ids);
	$sweeps_opened_link_ids[] = $cat_id;
	

		echo "\n";	
		echo TAB_7.'<!--	START Shop Bread crumb code 	-->'."\n";
		echo "\n";	
	
		echo TAB_7.'<div id="ShopBreadCrumb" >'."\n";					
	
			echo TAB_8.'<p class="ShopBreadCrumb" >You are here:'."\n";	

			$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;reset_browse=yes';
			
			echo TAB_9.' '.PATH_SEPERATOR_SYMBOL.' '."\n";	//	spacer	
			
			echo TAB_9.'<a class="BreadCrumb" href="'.$href.'" title ="Return to start of the Catalogue" >All Categories</a>'."\n";				
							
			
			foreach ($sweeps_opened_link_ids as $link_id)
			{
							
				if ($link_id != "" AND $link_id != 0 )
				{
					$mysql_err_msg = 'The Menu for this page is unavailable';
					$sql_statement = 'SELECT * FROM sweeps_categories WHERE cat_id = "'.$link_id.'" ';
														
					$breadcrumb_result = ReadDB ($sql_statement, $mysql_err_msg);
						
					while ($breadcrumb_info = mysql_fetch_array ($breadcrumb_result))
					{
						
						echo TAB_9.' '.PATH_SEPERATOR_SYMBOL.' '."\n";	//	spacer
						
						if ( $link_id == $cat_id AND !isset($_REQUEST['prod_id']))
						{													
							//	current page name (no link)
							echo TAB_9.'<span class="BreadCrumbSelected" title="You are at this category already">'
							.$breadcrumb_info['cat_name'].'</span>'."\n";
								
						}
			
						else
						{	 
							//	parent links
							$href = $_SERVER['PHP_SELF'].'?p='.SHOP_PAGE_ID.'&amp;s_cat='.$link_id.'&amp;view=browse';

							echo TAB_9.'<a class="BreadCrumb" href="'.$href.'" title ="'.$breadcrumb_info['description'].'" >'."\n";
								echo TAB_10.$breadcrumb_info['cat_name']."\n";
							echo TAB_9.'</a>'."\n";
							
						}
					}				
				}
			}
			
				
			echo TAB_8.'</p>'."\n";	
		
		echo TAB_7.'</div>'."\n";	

		echo "\n";		
		echo TAB_7.'<!--	END Shop Bread crumb 	-->'."\n";	
		echo "\n";		
	

		
				
//	===========================================================================================================================


	//	get array of link path IDs
	function GetSweepsCatLinkPath ($cat_id)
	{

		$sweeps_opened_link_ids = array();
		$current_id = $cat_id;

		for ( $i = 0; $i < (NUM_NAV_LAYERS - 1); $i++ )
		{
			//	get selected link info
			$mysql_err_msg = 'The Menu for this page is unavailable';
			$sql_statement = 'SELECT parent_id, cat_id FROM sweeps_categories WHERE active = "on" '
																			.'AND cat_id = "'.$current_id.'"';
				

			$cat_nav_row = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
			
			$sweeps_opened_link_ids[] = $cat_nav_row['parent_id'];
			
			$current_id = $cat_nav_row['parent_id'];
			
		}
		
		return $sweeps_opened_link_ids;
		
	}	
		
?>