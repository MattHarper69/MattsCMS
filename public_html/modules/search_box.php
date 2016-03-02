<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
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
	
	$request_q = '';
	if (isset($_REQUEST['q'])) {$request_q = $_REQUEST['q'];}
	
	$request_search_str = '';
	if (isset($_REQUEST['search_str'])) {$request_search_str = $_REQUEST['search_str'];}
	
	//	get the search query str "q" sent from  this page OR "search_str" from search results page  and put in text box
	if (!isset ($request_q )) {$search_str = $request_search_str; }
	else {$search_str = $request_q  ;}

	//	replace codes whith their approp chrs
	$chrs = array("&", "'", '"', "\\" );
	$codes = array("&amp;", "", "", "" );	
	$search_str = str_replace($chrs, $codes, $search_str);
	$search_str = htmlspecialchars ($search_str, ENT_QUOTES, 'utf-8');
	
	echo "\n";			
	echo TAB_7.'<!--	Start Search Box		-->'."\n";		
	echo "\n";

	$div_name = 'SearchBox_'.$mod_id;
	
	if
	(
			isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE 
		AND UserPageAccess($page_id) > 1
	)
	{
		$can_not_clone = 0;
		$edit_enabled = 0;
		$edit_html_enabled = 0;
		$mod_locked = 2;	
		
		//	Display / Hide In-Active Mods
		include ('CMS/cms_inactive_mod_display.php');
					
		$hover_class = ' HoverShow Draggable';
		$on_click = ' onClick="javascript:selectMod2Edit(6, '.$mod_id.', \''.$div_name.'\',0,2);"';
		
		//	CSS layout Dispay (for CMS)
		$CSS_layout = '&lt;div class="<strong>SearchBox</strong>" id="<strong>'.$div_name.'</strong>" &gt;'
						.'<span class="FinePrint"> (MODULE CONTENT HERE) </span>&lt;/div&gt;';			
//class="SearchBox" id="SearchBox_18"		
	}

	else
	{
		$hover_class = '';
		$on_click = '';
	}
	
	echo TAB_7.'<div class="SearchBox'.$hover_class.'" id="'.$div_name.'"'.$on_click.'>'."\n";
	
		echo TAB_8.'<form class="SearchBox" action="'.$action_url.'" method="post" >'."\n";
		
			echo TAB_9.'<p>'."\n";
			
			if ($search_str != '' AND $search_str != NULL)
			{
				echo TAB_10.'<input class="SearchBox" type="text" name="q" value="'.$search_str.'" />'."\n";			
			}

			else
			{
				echo TAB_10.'<input class="SearchBox" type="text" name="q" value="'.SEARCH_BUTTON_LABEL.'"'."\n";
					echo TAB_11.' onfocus="if(this.value==\''.SEARCH_BUTTON_LABEL.'\') {this.value = \'\';}"'."\n";
					echo TAB_11.' onblur="if(this.value==\'\') { this.value=\''.SEARCH_BUTTON_LABEL.'\'}" />'."\n";
							
			}
				echo TAB_10.'<button type="submit" class="SearchBoxButton" name="search_box_submit" >'.SEARCH_BUTTON_LABEL.'</button>'."\n";

			echo TAB_9.'</p>'."\n";
			
			
/* 				
			echo TAB_9.'<p>'."\n";
				if ($_REQUEST['search_footer'] == 'on')	{$checked = 'checked="checked"';}
				else {$checked = '';}
				
				echo TAB_10.'<input class="SearchBoxCheck" type="checkbox" id="SearchBoxCheck"'
							.'name="search_footer" '.$checked.' />'."\n";
				echo TAB_10.'<label for="SearchBoxCheck" >Search header &amp; footer</label>'."\n";			
			echo TAB_9.'<p>'."\n";	
*/

			//	Do a REMOVE Hi-lited Tags link
			if (isset($_REQUEST['remove_hilight']) AND$_REQUEST['remove_hilight'] != "yes" AND $request_search_str != "" )
			{	
				//$return_link = '?p='.$page_id;	//	Method 1	
				$return_link = htmlspecialchars($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']).'&amp;remove_hilight=yes';	//	Method 2	
				echo TAB_9.'<div class="RemoveHiliteLink" id="RemoveHiliteLink_'.$mod_id.'" >'."\n";
					echo TAB_10.'<a class="RemoveHiliteLink" href="'.$return_link.'" >Remove Hi-lighting</a>'."\n";		
				echo TAB_9.'</div>'."\n";
			}
			
			else {unset($_REQUEST['search_str']);}	

		echo TAB_8.'</form>'."\n";
		
	echo TAB_7.'</div>'."\n";
	
	if
	(
			isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE 
		AND UserPageAccess($page_id) > 1
	)
	{
		//	Do mod editing Toolbar
		include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
		
		//	Do Mod Config Panel
		include ('CMS/cms_panels/cms_panel_mod_config.php');		
	}		
	
	echo "\n";			
	echo TAB_7.'<!--	End Search Box		-->'."\n";		
	echo "\n";	


?>