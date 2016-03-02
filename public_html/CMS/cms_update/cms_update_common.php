<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	
//	UPDATE HTACCESS FILE	========================================================================================================	
	
	function UpdateHtaccesFile ($home_page_id) 
	{
		$error = FALSE;
		
		//	Get all Page URL Aliases
		$mysql_err_msg = 'creating URL Aliases';
		$sql_statement = 'SELECT page_id, url_alias FROM page_info WHERE url_alias != ""';	
		$result = ReadDB ($sql_statement, $mysql_err_msg);
		
		$rewriterule_str = '';
		while ($nav_row = mysql_fetch_array ($result))
		{
			if ($nav_row['page_id'] != $home_page_id) 			
			{$rewriterule_str .= 'RewriteRule ^'.$nav_row['url_alias'].'$ index.php?p='.$nav_row['page_id'].' [L]'."\n";}	
			
			else
			{
				$rewriterule_str .= 'RewriteRule ^$ index.php?p='.$nav_row['page_id'].' [L]'."\n";
				$rewriterule_str .= 'RewriteRule ^'.$nav_row['url_alias'].'$ index.php?p='.$nav_row['page_id'].' [L]'."\n";		
			}


			if ($nav_row['page_id'] == SHOP_PAGE_ID ) 
			{
				//	Get all ITEMs in Shop URL Aliases
				$mysql_err_msg = 'creating Shop Item URL Aliases';
				$sql_statement = 'SELECT prod_id, url_alias FROM '.SHOP_DB_NAME_PREFIX.'_cat_asign'
																.', '.SHOP_DB_NAME_PREFIX.'_items'
																.', '.SHOP_DB_NAME_PREFIX.'_categories'
																
														.' WHERE '.SHOP_DB_NAME_PREFIX.'_items.item_id = '.SHOP_DB_NAME_PREFIX.'_cat_asign.item_id' 
														.' AND '.SHOP_DB_NAME_PREFIX.'_categories.cat_id = '.SHOP_DB_NAME_PREFIX.'_cat_asign.cat_id'
														.' AND url_alias != ""'	
														.' AND '.SHOP_DB_NAME_PREFIX.'_items.active = "on"'
														;		
	//echo $sql_statement;	
				if(!$shop_result = ReadDB ($sql_statement, $mysql_err_msg))
				{$error = ' - Updating .htaccess File Failed! \n';}
				
				else
				{
					
					while ($item_info = mysql_fetch_array ($shop_result))
					{

						
						$rewriterule_str .= 'RewriteRule ^'.$item_info['url_alias'].'$'
											.' index.php?p='.SHOP_PAGE_ID.'&prod_id='.$item_info['prod_id'].'&view=browse [L]'."\n";
									
					}
					
				}
					
			}
		
		}
		
		//	are there any Profile pages ?
		$mysql_err_msg = 'creating Profile URL Aliases';
		$sql_statement = 'SELECT page_id, mod_id FROM modules'
	
												.' WHERE mod_type_id = 39' 	
												.' AND active = "on"'
												;				

		if($profile_mod_result = ReadDB ($sql_statement, $mysql_err_msg))
		{
			while ($profile_mod_info = mysql_fetch_array ($profile_mod_result))
			{
				$prof_page_id = $profile_mod_info['page_id'];
				
				$sql_statement = 'SELECT profile_id, url_alias FROM mod_profiles'
	
												.' WHERE mod_id = "'.$profile_mod_info['mod_id'].'"' 	
												.' AND active = "on"'
												;					
				
				
				if(!$profiles_result = ReadDB ($sql_statement, $mysql_err_msg))
				{$error = ' - Updating .htaccess File Failed! \n';}
				
				else
				{
					while ($profiles_info = mysql_fetch_array ($profiles_result))
					{

						if($profiles_info['url_alias'])
						{
							$rewriterule_str .= 'RewriteRule ^'.$profiles_info['url_alias'].'$'
											.' index.php?p='.$prof_page_id.'&profile_id='.$profiles_info['profile_id'].' [L]'."\n";	
						}

									
					}
					
				}
				
			}
			
		}

		//	Stop Hotlinking
		if 
		(
				 isset($_POST['allow_hotlinking']) AND $_POST['allow_hotlinking'] == 0 
			OR 	!isset($_POST['allow_hotlinking']) AND ALLOW_HOTLINKING == 0
		)
		{
			if (substr($_SERVER['SERVER_NAME'], 0,4) == 'www.')
			{
				$site_url = str_replace ('www.', '', $_SERVER['SERVER_NAME']);
			}
			else { $site_url = $_SERVER['SERVER_NAME'];}
			
			//	extra URLs
			if (defined('HOT_LINK_ALLOW_URLS'))
			{
				
				$all_URLS = explode(',', HOT_LINK_ALLOW_URLS);
				
				$all_urls_str = '';
				foreach($all_URLS as $url)
				{
					$all_urls_str .= 
										 'RewriteCond %{HTTP_REFERER} !^http://'.$url.'$ [NC]' . "\n"
										.'RewriteCond %{HTTP_REFERER} !^http://'.$url.'/.*$ [NC]' . "\n"
										.'RewriteCond %{HTTP_REFERER} !^http://www.'.$url.'$ [NC]' . "\n"
										.'RewriteCond %{HTTP_REFERER} !^http://www.'.$url.'/.*$ [NC]' . "\n"
										.'RewriteCond %{HTTP_REFERER} !^https://'.$url.'$ [NC]' . "\n"
										.'RewriteCond %{HTTP_REFERER} !^https://'.$url.'/.*$ [NC]' . "\n"
										.'RewriteCond %{HTTP_REFERER} !^https://www.'.$url.'$ [NC]' . "\n"
										.'RewriteCond %{HTTP_REFERER} !^https://www.'.$url.'/.*$ [NC]' . "\n"										
										;
				}
			
			}
			else {$all_urls_str = ''; }
			
			$stop_hotlink = 		
							 'RewriteCond %{HTTP_REFERER} !^$' . "\n"
							.'RewriteCond %{HTTP_REFERER} !^http://'.$site_url.'$ [NC]' . "\n"
							.'RewriteCond %{HTTP_REFERER} !^http://'.$site_url.'/.*$ [NC]' . "\n"
							.'RewriteCond %{HTTP_REFERER} !^http://www.'.$site_url.'$ [NC]' . "\n"
							.'RewriteCond %{HTTP_REFERER} !^http://www.'.$site_url.'/.*$ [NC]' . "\n"
							.'RewriteCond %{HTTP_REFERER} !^https://'.$site_url.'$ [NC]' . "\n"
							.'RewriteCond %{HTTP_REFERER} !^https://'.$site_url.'/.*$ [NC]' . "\n"
							.'RewriteCond %{HTTP_REFERER} !^https://www.'.$site_url.'$ [NC]' . "\n"
							.'RewriteCond %{HTTP_REFERER} !^https://www.'.$site_url.'/.*$ [NC]' . "\n"	

							.$all_urls_str . "\n" 
							.'RewriteRule \.('.HOT_LINK_FILE_EXTS.')$ - [F]'
							;	
		}
		else
		{
			$stop_hotlink = '';
		}


		

		///////////////////////////  EDIT TO HERE /////////////////////////////////
		
		
		
		//===================================================
		
		if (!is_writable("../../.htaccess"))

		{
			$error .= ' - Writing to .htaccess File Failed! \n';

		}
			
		else
		{
			$fopen = @fopen("../../.htaccess","w");
			$str = 			
					 'Options All -Indexes'."\n\n"
					.'RewriteEngine on'."\n\n"
	
					.$rewriterule_str . "\n" 
					.$stop_hotlink;

			fputs($fopen, $str);
			fclose($fopen);

		}

		//echo $error;
		
		RETURN $error;
		
	}
	
	
//	UPDATE Sitemap.xml file	===============================================================================================
	
	function UpdateSiteMapFile ($home_page_id) 
	{
		$xmlns = 'http://www.sitemaps.org/schemas/sitemap/0.9';
		$changefreq = 'hourly';
		
		$error = FALSE;
		
		$add_array = array();

		//	Get all Page URL Aliases
		$mysql_err_msg = 'creating URL Aliases';
		$sql_statement = 'SELECT page_id, priority, url_alias, active FROM page_info WHERE url_alias != ""'

																					.' AND include_in_sitemap = "on"'
																					.' AND active = "on"'
																					;		
		if(!$result = ReadDB ($sql_statement, $mysql_err_msg))
		{$error = ' - Updating Site Map File Failed! \n';}
		else
		{
			$url_strings = '';
			while ($nav_row = mysql_fetch_array ($result))
			{
				if ($nav_row['page_id'] != $home_page_id) 			
				{ $address = $nav_row['url_alias']; }	
				
				else
				{ $address = ''; }
				
				if ( !in_array($address, $add_array))
				{
					$url_strings .= 	 TAB_1.'<url>'."\n"
										.TAB_2.'<loc>http://'.SITE_URL.'/'.$address.'</loc>'."\n"
										.TAB_2.'<lastmod>'.date('Y-m-d').'</lastmod>'."\n"
										.TAB_2.'<changefreq>'.$changefreq.'</changefreq>'."\n"
										.TAB_2.'<priority>'.number_format($nav_row['priority'] / 10, 1) .'</priority>'."\n"
										.TAB_1.'</url>'."\n"
										;
										
					$add_array[] = $address;						
				}
									
				if ($nav_row['page_id'] == SHOP_PAGE_ID AND $nav_row['active'] == 'on') 
				{
					//	Get all ITEMs in Shop URL Aliases
					$mysql_err_msg = 'creating Shop Item URL Aliases';
					$sql_statement = 'SELECT url_alias FROM '.SHOP_DB_NAME_PREFIX.'_items WHERE url_alias != "" AND '
																.SHOP_DB_NAME_PREFIX.'_items.active = "on"';		

					if(!$shop_result = ReadDB ($sql_statement, $mysql_err_msg))					
					{$error = ' - Updating Site Map File Failed! \n';}
					else
					{
						while ($item_info = mysql_fetch_array ($shop_result))
						{
							
							$address = $item_info['url_alias'];
							if ( !in_array($address, $add_array))
							{
							
								$url_strings .= 	 TAB_1.'<url>'."\n"
														.TAB_2.'<loc>http://'.SITE_URL.'/'.$address.'</loc>'."\n"
														.TAB_2.'<lastmod>'.date('Y-m-d').'</lastmod>'."\n"
														.TAB_2.'<changefreq>'.$changefreq.'</changefreq>'."\n"
														.TAB_2.'<priority>'.number_format($nav_row['priority'] / 10, 1) .'</priority>'."\n"
														.TAB_1.'</url>'."\n"
														;
														
								$add_array[] = $address;						
														
							}
							
						}
						
					}
					
				}

			}
			
		}


		//	are there any Profile pages ?
		$mysql_err_msg = 'creating Profile URL Aliases';
		$sql_statement = 'SELECT mod_id FROM modules'
	
												.' WHERE mod_type_id = 39' 	
												.' AND active = "on"'
												;				

		if($profile_mod_result = ReadDB ($sql_statement, $mysql_err_msg))
		{
			while ($profile_mod_info = mysql_fetch_array ($profile_mod_result))
			{				
				$sql_statement = 'SELECT url_alias FROM mod_profiles'
	
												.' WHERE mod_id = "'.$profile_mod_info['mod_id'].'"' 	
												.' AND active = "on"'
												;					
				
				
				if(!$profiles_result = ReadDB ($sql_statement, $mysql_err_msg))
				{$error = ' - Updating Site Map File Failed! \n';}
				
				else
				{
					while ($profiles_info = mysql_fetch_array ($profiles_result))
					{

						if($profiles_info['url_alias'])
						{
							$url_strings .= 	 TAB_1.'<url>'."\n"
													.TAB_2.'<loc>http://'.SITE_URL.'/'.$profiles_info['url_alias'].'</loc>'."\n"
													.TAB_2.'<lastmod>'.date('Y-m-d').'</lastmod>'."\n"
													.TAB_2.'<changefreq>'.$changefreq.'</changefreq>'."\n"
													.TAB_2.'<priority>7</priority>'."\n"	// set to 7 by default
													.TAB_1.'</url>'."\n"
													;
													
						}
									
					}
					
				}
				
			}
			
		}		

		if (!is_writable("../../Sitemap.xml"))

		{$error .= ' - Writing to Site Map File Failed! \n';}
			
		else
		{
			$fopen = @fopen("../../Sitemap.xml","w");
			$str = 
				
						 '<?xml version="1.0" encoding="UTF-8"?>'."\n"
						.'<urlset xmlns="'.$xmlns.'">'."\n"

						.$url_strings
						
						.'</urlset>'
						;

			//echo $str;
			
			fputs($fopen, $str);
			fclose($fopen);
			


			}
			
		//echo $error;
		
		RETURN $error;

	}	
	
	

//	UPDATE Robots.txt file	==================================================================================================	
	
	function UpdateRobotsTxtFile () 
	{

		$error = FALSE;

		//----------------Get all Dirs in Root Dir to disallow----------------------------------------------

		//	disallowthese dir names:

		$disallow_dirs = array('_SYNCAPP','CMS','enteradmin','invoices');


		$disallow_str = '';
		foreach ($disallow_dirs as $dir) 
		{
			$disallow_str .= 'Disallow: /'.$dir.'/'."\n";		
		}
						  
		if (!is_writable("../../robots.txt"))

		{$error = ' - Updating robots.txt File Failed! \n';}
			
		else
		{
			$fopen = @fopen("../../robots.txt","w");
			$str = 
				
						 'User-agent: *'."\n"
						.'Allow: /'."\n"

						.$disallow_str
						
						.'Sitemap:http://'.SITE_URL.'/Sitemap.xml'

						;
			//echo '<p>Updating &#39;robots.txt&#39; file</p>'."\n";
			//echo $str;
			
			fputs($fopen, $str);
			fclose($fopen);
			


			}
			
		//echo $error;

		RETURN $error;
		
	}


	// replace bad tags and chrs	==============================================================
	function CleanHtml($text)
	{
		// create replacement Tag arrays from db
		$replaceHTML = array();
		$withHTML = array(); 
		
		$mysql_err_msg = 'Fetching Html Replacement Tags';	
		$sql_statement = 'SELECT * FROM _cms_html_replace_list';
		$result = ReadDB ($sql_statement, $mysql_err_msg);

		while ($replace_html_list = mysql_fetch_array ($result))
		{		
			$replaceHTML[] 	= $replace_html_list['find_str'];
			$withHTML[] 	= $replace_html_list['replace_str'];
		}
		//	Replace dodgy tags with ones that will validate
		$text = str_replace($replaceHTML, $withHTML, $text);

	//	escape quotes manually if magic quotes is OFF
	if (!get_magic_quotes_gpc())
	{
/* 			
	//	escape quotes
		$chr_list = array  (
								"'"
							   ,'"'
							   
							);
							
		$code_list = array (							 
								 '\''						
								,'\"'

							);

		
		$text = str_replace($chr_list, $code_list, $text);
	 */
	 
		$text = addslashes($text);
	 
	}


		return $text;
	
	}		
		
		
	//	Remove orphaned end tags	==============================================================
	function remove_end_tags($text, $remove_tag_list) 
	{     
		$stripped = '';
		foreach ( $remove_tag_list as $start_tag => $end_tag)
		{
			$num_tags_to_replace = substr_count($text, $end_tag) - substr_count($text, $start_tag);			
		
			$bits = explode($end_tag , $text );			

			$last_tag_pos = count($bits) - $num_tags_to_replace - 1 ;
	
			for ($i = 0; $i < count($bits); $i++)
			{
				if ($i < $last_tag_pos )
				{
					$stripped .= $bits[$i] . $end_tag;			
				}
				
				else
				{
					$stripped .= $bits[$i];				
				}
								
			}

			$text = $stripped;
			$stripped = '';
			
		}
		

		return $text;
	
	}	
	
?>