<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	///-------------------PUT IN db ?????? 
	$shorten_by = 80;
	//$wrap = 1000;
	$limit = 10;
	
	$search_str = '';
	if (isset($_REQUEST['search_footer']) AND $_REQUEST['search_footer'] == 'on')
	{
		$search_footer = $_REQUEST['search_footer'];
	}
	else
	{
		$search_footer = '';
	}

	echo TAB_7.'<div class="SearchResults" id="SearchMod_'.$mod_id.'" >'."\n";
				
		//----------	 Do Search Box	-------------------------------------------------------------
		//	need page id of Page with search mod installed
		$search_page_id = SEARCH_PAGE_ID;
		
		//	get dynamicly
		$mysql_err_msg = 'Cannot Access Search Page ID';

		$sql_statement = 'SELECT page_id FROM modules, _module_types WHERE modules.mod_type_id = _module_types.mod_type_id'
														.' AND _module_types.file_name = "search_page.php"';
									
		$search_page_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
		$search_page_id = $search_page_info['page_id'];

		//$action_url = '/index.php?p='.$search_page_id;		
		$action_url = '/search';		
		
		//	get the search query str "q" sent from  this page OR "search_str" from search results page  and put in text box
		if (!isset ($_REQUEST['q']) AND isset($_REQUEST['search_str'])) {$search_str = $_REQUEST['search_str']; }
		elseif (isset($_REQUEST['q'])){$search_str = $_REQUEST['q'] ;}

		//	replace codes whith their approp chrs
		$chrs = array("&", "'", '"', "\\" );
		$codes = array("&amp;", "", "", "" );	
		//$search_str = str_replace($chrs, $codes, $search_str);
		$search_str = htmlspecialchars ($search_str, ENT_QUOTES, 'utf-8');
		
		echo "\n";			
		echo TAB_8.'<!--	Start Search Box		-->'."\n";		
		echo "\n";	
		
		echo TAB_8.'<div class="SearchBox" id="SearchBox_'.$mod_id.'" >'."\n";
		
			echo TAB_9.'<form class="SearchBox" action="'.$action_url.'" method="post" >'."\n";
				echo TAB_10.'<p>'."\n";
				
				if ($search_str != '' AND $search_str != NULL)
				{
					echo TAB_11.'<input class="SearchBox" type="text" name="q" value="'.$search_str.'" />'."\n";			
				}

				else
				{
					echo TAB_11.'<input class="SearchBox" type="text" name="q" value="'.SEARCH_BUTTON_LABEL.'"'."\n";
						echo TAB_12.' onfocus="if(this.value==\''.SEARCH_BUTTON_LABEL.'\') {this.value = \'\';}"'."\n";
						echo TAB_12.' onblur="if(this.value==\'\') { this.value=\''.SEARCH_BUTTON_LABEL.'\'}" />'."\n";
				}
					echo TAB_11.'<button type="submit" class="SearchBoxButton" name="search_box_submit" >'.SEARCH_BUTTON_LABEL.'</button>'."\n";

				echo TAB_10.'</p>'."\n";
				
				echo TAB_10.'<p>'."\n";
				
					if (isset($search_footer) AND $search_footer == 'on')	{$checked = 'checked="checked"';}
					else {$checked = '';}
					
					echo TAB_11.'<input class="SearchBoxCheck" type="checkbox" id="SearchBoxCheck_'.$mod_id.'"'
								.' name="search_footer" '.$checked.' />'."\n";
					echo TAB_11.'<label for="SearchBoxCheck_'.$mod_id.'" >Search header &amp; footer</label>'."\n";			
				echo TAB_10.'</p>'."\n";	
/* 	
				//	Do a REMOVE Hi-lited Tags link
				if 
				(	
					$remove_hilight != "yes" AND $search_str != "" 
					AND $_REQUEST['q'] != "" AND $_REQUEST['q'] != null AND strlen($_REQUEST['q']) > 1
				)
				{	
					//$return_link = '?p='.$page_id;	//	Method 1	
					$return_link = htmlspecialchars($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']).'&amp;remove_hilight=yes';	//	Method 2	
					echo TAB_10.'<div class="RemoveHiliteLink" id="RemoveHiliteLink_'.$mod_id.'" >'."\n";
						echo TAB_11.'<a class="RemoveHiliteLink" href="'.$return_link.'" >Remove Hi-lighting</a>'."\n";		
					echo TAB_10.'</div>'."\n";
				}
				
				else {unset($_REQUEST['search_str']);}	
 */	
			echo TAB_9.'</form>'."\n";
			
		echo TAB_8.'</div>'."\n";
		
	 	echo "\n";			
		echo TAB_8.'<!--	End Search Box		-->'."\n";		
		echo "\n";	
			
			
	//-------------------------------------------
	if (isset($_REQUEST['s']) AND $_REQUEST['s'] != "" AND $_REQUEST['s'] != null ) {$s = $_REQUEST['s'];}
	else {$s = 1;}

	//if ($_REQUEST['q'] == "" OR $_REQUEST['q'] == null OR strlen($_REQUEST['q']) < 2) 
	if (isset($_REQUEST['q']) AND $_REQUEST['q'] != "" AND $_REQUEST['q'] != null AND strlen($_REQUEST['q']) > 1) 
	{	
		$search_str = $_REQUEST['q'] ;				
		$search_str = str_replace($chrs, $codes, $search_str); //	replace chrs with their approp codes
		$search_str = htmlspecialchars ($search_str, ENT_QUOTES, 'utf-8');
		$trimmed_search_str = trim($search_str); //	trim whitespace from the stored variable	
		

		//	read from db to get all searchable fields from tables	----------
		$search_info = array();
		$mysql_err_msg = 'Available Search area info not available';	
		$sql_statement = 'SELECT table_name, field_name FROM mod_search_fields WHERE active = "on" ORDER BY seq';

		$result1 = ReadDB ($sql_statement, $mysql_err_msg);
		
		//	Main Search Query	--------
		
		$num_found_pages = array();
		$num_found = 0;
		$count = 1;	

		while ($field_info = mysql_fetch_array ($result1))		
		{
			$mysql_err_msg = 'Searching in TABLE: '.$field_info['table_name'].' failed';
			
			if (isset($search_footer) AND $search_footer == 'on')
			{ $not_in_footer_str = ''; }
			else { $not_in_footer_str = 'AND modules.div_id != 1 AND modules.div_id != 5'; }
			
			//	Insert extra select query string ( FOR SHOP ITEM SEARCHING - ONLY)
			if ( $field_info['table_name'] == 'shop_items')
			{
				$select_prod_id_str = ', shop_cat_asign.prod_id ';
				$select_shop_tables_str = ' ,shop_cat_asign';
				$where_shop_clause_str = ' AND shop_cat_asign.item_id = shop_items.item_id';
				$add_query_str = '&amp;prod_id=';
					
			}
			else
			{
				$select_prod_id_str = '';
				$select_shop_tables_str = '';
				$where_shop_clause_str = '';
				$add_query_str = '';
			}
			
			
			//  Get the Page ID and Page Menu Text as well as found searched entry text
			$sql_statement = 'SELECT 	page_info.page_id'
									.', page_info.menu_text'
									.', page_info.url_alias'
								    .', '.$field_info['table_name'].'.'.$field_info['field_name']
									.$select_prod_id_str

									.' FROM page_info '
									.', modules'
									.', '.$field_info['table_name']
									.$select_shop_tables_str
					
									.' WHERE '.$field_info['table_name'].'.'.$field_info['field_name'].' like "%'.$trimmed_search_str .'%"'
									.' AND page_info.page_id = modules.page_id'
									.' AND modules.mod_id = '.$field_info['table_name'].'.mod_id'
									.' AND modules.active = "on"'
									.' AND page_info.active = "on"'
									.' AND '.$field_info['table_name'].'.active = "on"'
									.$not_in_footer_str
									.$where_shop_clause_str
								
									.' ORDER BY page_id'
									;

//echo '<p>'.$sql_statement.'<br>';
			$result2 = ReadDB ($sql_statement, $mysql_err_msg);
			
			$num_results = mysql_num_rows($result2);
			$num_found = $num_found + $num_results;

			while ($found_info = mysql_fetch_array ($result2))
			{
				//	Get $NEEDLE and $HAYSTACK and Strip em !!
				$haystack = strip_tags($found_info[$field_info['field_name']],'<p><strong><em><ul><ol><li>');
				$needle = strip_tags($search_str,'<p><strong><em><ul><ol><li>');
					
				//	replace codes whith their approp chrs
				$haystack = str_replace( $codes, $chrs,$haystack);
				$needle = str_replace( $codes,$chrs, $needle);		
					
				//	convert to Lower case
				$haystack_lower = strtolower($haystack);
				$needle_lower = strtolower($needle);
				
				$exploded = explode($needle_lower, $haystack_lower);

				$complete_text = "";	
				$i = 0;
				$needle_start_pos = 0;
				
				$needle_matches_count = 0;
				while ( $i < count($exploded)-1 )
				{					
					//	text BEFORE needle	
					$piece_text = substr ($haystack, $needle_start_pos, strlen ($exploded[$i]));
					$piece_text = ShortenTextPre( $piece_text, $shorten_by );	//	trim text
						
					// create a string of <li>s					
					$complete_text .= TAB_11.'<li>'.$piece_text;	//	add to compiled string
					
					//	move start positionnext piece of the text string
					$needle_start_pos = $needle_start_pos + strlen ($exploded[$i]);
							
					//	The NEEDLE
					$piece_text = substr ($haystack, $needle_start_pos, strlen ($needle));
				
					$complete_text .= '<span class="SearchResultsHilight" >'.$piece_text.'</span>';	//	add to compiled string with BOLD
					$needle_matches_count++;	
						
					//	move start positionnext piece of the text string
					$needle_start_pos = $needle_start_pos + strlen ($needle);										
					$i++;
						
					//	text AFTER needle
					$piece_text = substr ($haystack, $needle_start_pos, strlen ($exploded[$i]));						
					$piece_text = ShortenTextPost ( $piece_text, $shorten_by );	//	trim text

					$complete_text .= $piece_text.'</li>'."\n";	//	add to compiled string with Line brk

				}

				//$complete_text = wordwrap($complete_text, $wrap);	//	wrap text 								
				$complete_text = str_replace($chrs, $codes, $complete_text); //	replace chrs with their approp codes	
				

				//	add the Prod_id to the query str ( FOR SHOP ITEM SEARCHING - ONLY)
				if (isset($found_info['prod_id']) AND $found_info['prod_id'] != '')
				{
					$shop_prod_id_str = $add_query_str.$found_info['prod_id'];				
				}
				else {$shop_prod_id_str = '';}
				
				
				//	complile list to display
				$search_info[$count]['display'] = $complete_text;
				$search_info[$count]['page_id'] = $found_info['page_id'].$shop_prod_id_str;	//add the Prod_id to the query str ( FOR SHOP ITEM SEARCHING - ONLY)
				$search_info[$count]['url_alias'] = $found_info['url_alias'];
				$search_info[$count]['menu_text'] = $found_info['menu_text'];
				
				//	count num of pages to link to
				$num_found_pages[] = $found_info['page_id'];
								
				$count++;
			}
			
		}
			
				
		if ( $num_found < 1 )
		{	
			//	No results found				
				echo TAB_8.'<h3>Sorry, Your Search for: &quot;'.$search_str.'&quot; found <strong>NO</strong> Results:</h3>'."\n";				
			echo TAB_7.'</div>'."\n";
		}
				
		else
		{
			$num_found_pages = array_unique($num_found_pages);	//	remove duplicates

	
																		
				//	Display search statistics
				echo TAB_8.'<h3>Your Search for: &quot;'.$search_str.'&quot; found:</h3>'."\n";
						
				echo TAB_8.'<p>';
					echo '<strong>'.$num_found.'</strong> section(s) of text containing a total of'		
					.' <strong>'.$needle_matches_count.'</strong> match(s)'
					.' in <strong>'.count($num_found_pages).'</strong> page(s).';
				echo '</p>'."\n";
			

				//	if more than specified "limit" per page do Navigation
				if ($num_found > $limit) 
				{ doSearchNav($num_found, $limit, $s);}
				
				$last_in_page = $s + $limit;

				//	but if greater than last number stop there
				if ($last_in_page > count($search_info))
				{ $last_in_page = count($search_info) +1;}
				
				echo TAB_8.'<hr class="SearchResults" />'."\n";		

				echo TAB_8.'<ul class="SearchResults" >'."\n";

				for ($search_num = $s; $search_num < $last_in_page; $search_num++ )
				{	
							
					echo TAB_9.'<li class="SearchResults">'.$search_num.') Page: '."\n";
						//	page heading (link to page)
						echo TAB_10.'<a class="SearchPageHeading" href="index.php?p='.$search_info[$search_num]['page_id']
								.'&amp;search_str='.urlencode($search_str).'" >'
								.$search_info[$search_num]['menu_text']."\n";
						echo TAB_10.'</a>'."\n";					
						
						//	found content	
						echo TAB_10.'<ol class="SearchResults" >'."\n";
							echo $search_info[$search_num]['display']."\n";
						echo TAB_10.'</ol>'."\n";
						
						//	Link to page
						echo TAB_10.'<p class="SearchLink" > &#187; Link to Page: '."\n";
							echo TAB_11.'<a class="SearchLink" href="index.php?p='.$search_info[$search_num]['page_id']
								.'&amp;search_str='.urlencode($search_str).'" >'
								.SITE_URL.'/'.$search_info[$search_num]['url_alias']."\n";
							echo TAB_11.'</a>'."\n";
						echo TAB_10.'</p>'."\n";
		
					echo TAB_9.'</li>'."\n";			
				}
					
				echo TAB_8.'</ul>'."\n";

				if ($num_found > $limit) { doSearchNav($num_found, $limit, $s);}
				
			echo TAB_7.'</div>'."\n";	
			
		}		
		
	}		
			
	else	
	{
			//-------------Display Error
			echo TAB_8.'<h3 class="WarningMSG" >Please provide 2 or more characters for your search</h3>' ."\n";
			
		echo TAB_7.'</div>'."\n";	
	
	}

//------------		Function to Do Search Page Navigation	-------------------------------------
	
	function doSearchNav($num_found, $limit, $s)
	{
		
		if (isset($_REQUEST['search_footer']) AND $_REQUEST['search_footer'] == 'on')
		{
			$search_footer = $_REQUEST['search_footer'];
		}
		else
		{
			$search_footer = '';
		}
		
		global $page_id;
		
		echo TAB_8.'<div class="SearchResultsNav" >'."\n";
		
			echo TAB_9.'<ul class="SearchResultsNav" >'."\n";
			$num_pages = intval ($num_found / $limit);
			if ($num_found % $limit) {$num_pages++;}
			
			// 	Do PREV link bypass at first page
			if ( $s > 1) 
			{
				
				$prevs = $s - $limit;
				
				echo TAB_10.'<li class="SearchResultsNav" >'."\n";
					echo TAB_11.'<a class="SearchResultsNav" href="index.php?p='.$page_id
					.'&amp;q='.urlencode($_REQUEST['q']).'&amp;s='.$prevs
					.'&amp;search_footer='.$search_footer.'">&#171; Prev '.$limit.'</a>'."\n";
				echo TAB_10.'</li >'."\n";					
					
			}
			
			//	Do links to individual pages
			if ( $num_pages > 2)
			{
				for ( $page = 1; $page < $num_pages+1; $page++ )
				{
					$start = $limit * ($page - 1) + 1;
					$stop =  $page * $limit;
					if ($stop > $num_found ) {$stop = $num_found;}
					
					echo TAB_10.'<li class="SearchResultsNav" >'."\n";
					
					//	Do not so link if already at this page
					if ($s >= $start AND $s <= $stop )
					{ echo TAB_11.'<span class="SearchResultsNav" >'.$start.' - '.$stop.'</span>'."\n"; }
					else
					{
						echo TAB_11.'<a  class="SearchResultsNav" href="index.php?p='.$page_id.
							'&amp;q='.urlencode($_REQUEST['q']).'&amp;s='.$start.'&amp;search_footer='.$search_footer.'" >'."\n";
							echo TAB_12.$start.' - '.$stop."\n";
						echo TAB_11.'</a>'."\n";
					}	
						
					echo TAB_10.'</li >'."\n";					
				}
			}
	
			// 	Do NEXT link ( bypass  if at last page )
			if ( $s + $limit <= $num_found) 
			{				
				$next = $s + $limit;
				
				echo TAB_10.'<li class="SearchResultsNav" >'."\n";
					echo TAB_11.'<a class="SearchResultsNav" href="index.php?p='.$page_id
					.'&amp;q='.urlencode($_REQUEST['q']).'&amp;s='.$next
					.'&amp;search_footer='.$search_footer.'"> Next '.$limit.' &#187;</a>'."\n";
				echo TAB_10.'</li >'."\n";					
					
			}
			echo TAB_9.'</ul >'."\n";
			
		echo TAB_8.'</div>'."\n";

	}
		
?>