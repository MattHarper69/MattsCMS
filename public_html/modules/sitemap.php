 <?php

	echo TAB_6."\n";
	echo TAB_6.'<!--  Start Site Map Listing  --> '. "\n";
	echo TAB_6."\n";	
			
	echo TAB_6.'<div class="SiteMap" id="SiteMap_'.$mod_id.'" >'."\n";

	if (file_exists ('../_include_files/'.CODE_NAME.'_shop_configs.php'))
	{require_once (CODE_NAME.'_shop_configs.php');}

	//	get top level link details	
	$mysql_err_msg = 'Link info for the site map unavailable';
	$sql_statement = 'SELECT * FROM page_info WHERE parent_id = 0 '
											.'AND active = "on" '
											.'ORDER BY seq';
											
	$result = ReadDB ($sql_statement, $mysql_err_msg);


		echo TAB_7.'<ul class="SiteMap">'."\n";
	
		while ($sitemap_info = mysql_fetch_array ($result))
		{	

			//	Do Link	---------------------------
			echo TAB_8.'<li id="SiteMapPageId_'.$sitemap_info['page_id'].'" >'."\n";
			
				if ($sitemap_info['send_p_query'] == "on" ) {$query_str = '?p='.$sitemap_info['page_id'];}
				else {$query_str = '';}
				echo TAB_9.'<a class="SiteMapLink" href="'.$sitemap_info['file_name'].$query_str.'"'
					.' title ="'.$sitemap_info['popup_text'].'" >'."\n";
					echo TAB_10.nl2br(Space2nbsp($sitemap_info['menu_text']))."\n"; 
				echo TAB_9.'</a>'."\n"; 

				if ($sitemap_info['page_id'] == SHOP_PAGE_ID AND SHOP_DISPLAY_ITEMS_IN_SITEMAP == 1) 
				{ SiteMapShopItems (); }
				
				$parent_id = $sitemap_info['page_id'];						
				SiteMapSubLink ($parent_id);
												
			echo TAB_8.'</li>'."\n";
			
		}	
		
		echo TAB_7.'</ul>'."\n";

	echo TAB_6.'</div>'."\n";		

	echo TAB_6."\n";
	echo TAB_6.'<!--  Start Site Map Listing  --> '. "\n";
	echo TAB_6."\n";	



//------- Site Map Sub Menu Function:

	function SiteMapSubLink ($parent_id)
	{			
		global $connection, $tab;	

		//	read from db to check if there are sub links	----------
		$mysql_err_msg = 'Link info for the site map unavailable';
		$sql_statement = 'SELECT * FROM page_info WHERE '
														.'parent_id = "'.$parent_id.'" '						
														.'AND active = "on" '
														.'ORDER BY seq';
		
		$result = ReadDB ($sql_statement, $mysql_err_msg);
		
		//------check if there are sub links:
		if (mysql_num_rows($result) > 0 )
		{
			echo TAB_9.$tab.'<ul class="SiteMap">'."\n";
				
			while ($sitemap_info = mysql_fetch_array ($result))
			{		
			
				echo TAB_10.$tab.'<li id="SiteMapPageId_'.$sitemap_info['page_id'].'" >'. "\n";
						
					if ($sitemap_info['send_p_query'] == "on" ) {$query_str = '?p='.$sitemap_info['page_id'];}
					else {$query_str = '';}					
					
					echo TAB_11.$tab.'<a class="SiteMapLink" href="'.$sitemap_info['file_name'].$query_str.'" '
						.'title ="'.$sitemap_info['popup_text'].'" >'."\n"; 
						echo TAB_12.$tab.nl2br(Space2nbsp($sitemap_info['menu_text']))."\n"; 
					echo TAB_11.$tab.'</a>'."\n"; 
					
					if ($sitemap_info['page_id'] == SHOP_PAGE_ID AND SHOP_DISPLAY_ITEMS_IN_SITEMAP == 1) 
					{ SiteMapShopItems (); }
				
					$parent_id = $sitemap_info['page_id'];					
					$tab .= "    ";	
					
					SiteMapSubLink ($parent_id);
					
					$tab = substr($tab, 4);										

				echo TAB_10.$tab.'</li>'. "\n";
																			
			}
			
			echo TAB_9.$tab.'</ul>'."\n";
			
		}	

	}		

//-------- Do Shop Item listing

	function SiteMapShopItems ()
	{
		$add_array = array();
		
		echo TAB_9.'<ul class="SiteMap">'."\n";
		
		//	Get all ITEMs in Shop URL Aliases
		$mysql_err_msg = 'creating Shop Item URL Aliases';
		$sql_statement = 'SELECT * FROM shop_items WHERE url_alias != "" AND shop_items.active = "on"';		
		
		$result = ReadDB ($sql_statement, $mysql_err_msg);

		while ($item_info = mysql_fetch_array ($result))
		{			
			$url = $item_info['url_alias'];
			if ( !in_array($url, $add_array))
			{
				echo TAB_10.$tab.'<li id="SiteMapShopTtemId_'.$item_info['item_id'].'" >'. "\n";
					echo TAB_11.$tab.'<a class="SiteMapLink" href="'.$item_info['url_alias'].'" '
						.'title ="'.$item_info['description'].'" >'. "\n";
						echo TAB_12.$tab.nl2br(Space2nbsp($item_info['item_name'])). "\n";
					echo TAB_11.$tab.'</a>'."\n"; 
				echo TAB_10.$tab.'</li>'. "\n";
				
				$add_array[] = $url;						
											
			}
				
		}
			
		echo TAB_9.'</ul>'."\n";	
	}
?>