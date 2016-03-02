<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$div_name = 'ProfileListing_'.$mod_id;
	
	echo "\n";			
	echo TAB_7.'<!--	START Profile Listing code 	-->'."\n";
	echo "\n";	

	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{
	
		$can_not_clone = 0;
		$edit_enabled = 0;	
		$mod_locked = 2;			
		
		//	Disable hover show till WISWIG editing for Profile Listing Mod is complete
		//$hover_class = ' HoverShow';	
		$hover_class = '';

		//	Display / Hide In-Active Mods
		include ('CMS/cms_inactive_mod_display.php');

		//	Show Div Mod Button
		echo TAB_7.'<div id="EditDivModDisplay_'.$mod_id.'" class="EditDivModDisplay"' 
					.' title="Click to Edit this &quot;Profile Listing&quot; Module">'."\n";
			
			echo TAB_8.'<p style="background-color:#ffffff;color:#aa44aa; cursor: pointer;"'
			.' onClick="javascript:selectMod2Edit(35, '.$mod_id.',\''.$div_name.'\' ,0, 2);">'
			.'[ &quot;Profile Listing&quot; Module (click to edit) ]<p>'."\n";
			
		echo TAB_7.'</div>'."\n";
	
		//	CSS layout Dispay (for CMS)
		$CSS_layout = '&lt;div id="<strong>'.$div_name.'</strong>" class="<strong>ProfileListing</strong>" &gt;'
						.'<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;<span class="FinePrint"> (MODULE CONTENT HERE) </span>'
						.'<br/><br/>&lt;/div&gt;';

	
		//	Do mod editing Toolbar
		include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
		
		//	Do Mod Config Panel
		include ('CMS/cms_panels/cms_panel_mod_config.php');
		
	}

	else
	{$hover_class = '';}
	
	echo TAB_7.'<div id="ProfileListing_'.$mod_id.'" class="ProfileListing'.$hover_class.'" >'."\n";

	//	read from db to get Profile Config info
	$mysql_err_msg = 'Profile information unavailable';	
	$sql_statement = 'SELECT * FROM mod_profiles_config WHERE mod_id = "'.$mod_id.'"';
			
	$profiles_settings = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));		

	$use_url_alias = $profiles_settings['use_url_alias'];
	
	//	read from db to all Profile info for Main page listing and << PREV / NEXT >> navigation
	$mysql_err_msg = 'Profile information unavailable';	
	$sql_statement = 'SELECT' 
							
								.' profile_name'
								.',profile_id'
								.',url_alias' 
								.',primary_image_id'
								.',profile_img_file'
								.',profile_as_primary'
								.',display_profile_img'
								
								.' FROM mod_profiles'

								.' WHERE mod_id = "'.$mod_id.'"'
								.' AND active = "on"'

								.' ORDER BY seq'

								;

	$all_profiles_result = ReadDB ($sql_statement, $mysql_err_msg);
	
	$prof_nav_array = array();
	$all_profiles_array = array();
	while ($all_profiles_info = mysql_fetch_array($all_profiles_result))
	{
		// create nav info array
		$prof_nav_array[] = $all_profiles_info['profile_id'];
	
		// create array for Profiles Listing:
		$all_profiles_array[$all_profiles_info['profile_id']]['profile_name'] = $all_profiles_info['profile_name'];
		$all_profiles_array[$all_profiles_info['profile_id']]['url_alias'] = $all_profiles_info['url_alias'];
		
		//	Get the Primary image filename (from db)
		if($all_profiles_info['primary_image_id'] AND $all_profiles_info['profile_as_primary'] != 'on')
		{
			$mysql_err_msg = 'Profile primary image information unavailable';	
			$sql_statement = 'SELECT image_file_name'

							.' FROM mod_profile_images'
								
							.' WHERE image_id = "'.$all_profiles_info['primary_image_id'].'"'
							;	
	
			$primary_img_result = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
			$primary_img_filename = $primary_img_result['image_file_name'];

		}
		
		//	OR use profile image
		elseif 
		(
				$all_profiles_info['profile_as_primary'] == 'on' 
			AND file_exists('_images_user/profile/'.$all_profiles_info['profile_img_file'])
		)
		{
			$primary_img_filename = $all_profiles_info['profile_img_file'];
		}
		
		//	OR Default image
		else
		{
			$primary_img_filename = 'profile_default_primary_img.jpg';
		}
		
		//	add to array
		$all_profiles_array[$all_profiles_info['profile_id']]['primary_img_filename'] = $primary_img_filename;
		
	}
			
	
	//	Do Heading
	if ($profiles_settings['heading'])
	{
		echo TAB_8.'<h2 id="ProfilesHeading_'.$mod_id.'" class="ProfilesHeading" >'.$profiles_settings['heading'].'</h1>'."\n";
	}
	


	//	Display selected Profile ?:
	if (isset($_REQUEST['profile_id']) AND $_REQUEST['profile_id'] != 'all')	
	{
				
	
		//	Get << PREV / NEXT >> IDS
		foreach ($prof_nav_array as $key => $x)
		{
			if ( $_REQUEST['profile_id'] == $x)
			{
				//	avoid offsset errors
				if ($key > 0)	
				{$prev_prof_id = $prof_nav_array[$key - 1];}				
				else
				{$prev_prof_id = '';}
				
				if ($key + 1 < count($prof_nav_array))
				{$next_prof_id = $prof_nav_array[$key + 1];}
				else
				{$next_prof_id = '';}
			}
		}
			
	
		//	Do 	<< PREV / NEXT >> navigation (if set)
		//	navbar_location:		0 - no navbar		1 - navbar on top only		2 - navbar at bottom only		3 = navbar both
		if ($profiles_settings['navbar_location'] == 1 OR $profiles_settings['navbar_location'] == 3)
		{	
			prev_next_nav( $page_id , $prev_prof_id , $next_prof_id , $all_profiles_array , $use_url_alias);			
		}		
		
		//	Do Display Profile
		include_once ('profile_show.php');
		
		//	Do 	<< PREV / NEXT >> navigation (if set)
		if ($profiles_settings['navbar_location'] > 1 )
		{
			prev_next_nav( $page_id , $prev_prof_id , $next_prof_id , $all_profiles_array , $use_url_alias);		
		}			
		
	}

	//	Display ALL thumbs
	elseif (isset($_REQUEST['profile_id']) AND $_REQUEST['profile_id'] == 'all')	
	{
		echo TAB_8.'<div class="ProfilePrevNextNav">'."\n";
			echo TAB_9.'<ul class="ProfilePrevNextNav" >' ."\n";
				echo TAB_10.'<li class="ProfilePrevNextNav" >' ."\n";
					echo TAB_11.'<a class="ProfileReturnButton" href="'.$_SERVER['PHP_SELF'].'?p='.$page_id.'"'."\n";
						echo TAB_12.' title="Return to Profile Listing" >Return to Profile Listing' ."\n";
					echo TAB_11.'</a>' ."\n";	
				echo TAB_10.'</li>' ."\n";
			echo TAB_9.'</ul>' ."\n";	
		echo TAB_8.'</div>'."\n";
		
		
		
		$mysql_err_msg = 'Profile information unavailable';	
		$sql_statement = 'SELECT'
		
								.' image_id'
								.',mod_profiles.profile_id'
								.',image_file_name'
								.',img_caption'
								.',profile_name'
								.',url_alias'
		
								.' FROM mod_profile_images, mod_profiles'
								
								.' WHERE mod_profile_images.mod_id = "'.$mod_id.'"'
								.' AND mod_profile_images.active = "on"'
								.' AND mod_profiles.profile_id = mod_profile_images.profile_id'
								
								.' ORDER BY mod_profiles.seq'
								;	
		
		$all_images_result = ReadDB ($sql_statement, $mysql_err_msg);
		
		if($all_images_result)	
		{
			echo TAB_8.'<div class="ProfileAllThumbImageDiv">'."\n";
			
			while ($all_images_info = mysql_fetch_array($all_images_result))
			{	
							
				$image_path = '_images_user/profile/'.$all_images_info['image_file_name'];
						
				if($all_images_info['image_file_name'] AND file_exists($image_path))
				{
					if ($profiles_settings['all_thumbs_link'] == 'colorbox')
					{
						$a_tag = '<a href="'.$image_path.'" rel="ColorBoxProfileGallery" title="'.$all_images_info['img_caption'].'" >';
					}
					else
					{
						
						if ($use_url_alias)
						{
							$href = '/'.$all_images_info['url_alias'];
						}
						else
						{
							$href = $_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;profile_id='.$all_images_info['profile_id'];
						}							
						
						$a_tag = '<a href="'.$href.'" title="Go to '.$all_images_info['profile_name'].'&#39;s Profile" >';
						
					}
					
					echo TAB_9.'<div>'."\n";	

						echo TAB_10.$a_tag . "\n";						
							echo TAB_11.'<img class="ProfileAllThumbImage" id="ProfileAllThumbImage_'.$all_images_info['image_id'].'"'."\n"; 
								echo TAB_12.' src="'.$image_path.'" alt="Image by: '.$all_images_info['profile_name'].'" />'."\n";							
						echo TAB_10.'</a>' . "\n";
					echo TAB_9.'</div>'."\n";
	
				}	
					
			}	
			
			echo TAB_8.'</div>'."\n";
			
		}

				
	}
	
	//	Display list of Profiles
	else
	{
	
		if ($profiles_settings['link_2_all_imgs'])
		{
			echo TAB_8.'<div class="ProfilePrevNextNav">'."\n";	
				echo TAB_9.'<ul class="ProfilePrevNextNav" >' ."\n";
					echo TAB_10.'<li class="ProfilePrevNextNav" >' ."\n";
						echo TAB_11.'<a class="ProfileReturnButton" href="'.$_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;profile_id=all"'."\n";
							echo TAB_12.' title="Display all Images from all Artists" >Display all Images from all Artists' ."\n";
						echo TAB_11.'</a>' ."\n";	
					echo TAB_10.'</li>' ."\n";
				echo TAB_9.'</ul>' ."\n";
			echo TAB_8.'</div>'."\n";	
		}		
		
		
		//	Do text 1
		if ($profiles_settings['text_1'])
		{
			echo TAB_8.'<p id="ProfilesText_1_'.$mod_id.'" class="ProfilesText_1" >'.$profiles_settings['text_1'].'</p>'."\n";
		}			
		
		echo TAB_8.'<div class="ProfileListingDiv">'."\n";
		
		foreach ($all_profiles_array as $key => $profile_info)
		{

			echo TAB_9.'<div>'."\n";
		
				
				if ($use_url_alias)
				{
					$href = '/'.$all_profiles_array[$key]['url_alias'];
				}
				else
				{
					$href = $_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;profile_id='.$key;
				}					
				
				echo TAB_10.'<a href="'.$href.'">' . "\n";				
			
				$image_path = '_images_user/profile/'.$all_profiles_array[$key]['primary_img_filename'];
	
				if( file_exists($image_path) AND $profiles_settings['display_thumbs'] == 'on')
				{
							
					echo TAB_11.'<img class="ProfileImageMainImage" id="ProfileImageMainImage_ProfileId_'.$key.'"'."\n"; 
						echo TAB_12.' src="'.$image_path.'" alt="'.$profile_info['profile_name'].'" />'."\n";							
				}
						

					echo TAB_11.'<span class="ProfileImageCaption">'.$profile_info['profile_name'].'</span>'."\n"; 		
	

				echo TAB_10.'</a>' . "\n";	
						
			echo TAB_9.'</div>'."\n";
			
		}
			
		echo TAB_8.'</div>'."\n";	
		
		//	Do text 2
		if ($profiles_settings['text_2'])
		{
			echo TAB_8.'<p id="ProfilesText_2_'.$mod_id.'" class="ProfilesText_2" >'.$profiles_settings['text_2'].'</p>'."\n";
		}		

	}

			
	echo TAB_7.'</div>'."\n";
	
	echo "\n";			
	echo TAB_7.'<!--	END Profile Listing code	-->'."\n";
	echo "\n";			


	
//	======================================================================================================================	
	
	//		<< PREV / NEXT >> navigation	
	function prev_next_nav( $page_id , $prev_prof_id , $next_prof_id , $all_profiles_array , $use_url_alias)
	{
		echo TAB_8.'<div class="ProfilePrevNextNav" >' ."\n";
		
			echo TAB_9.'<ul class="ProfilePrevNextNav" >' ."\n";
								
			if ($prev_prof_id)
			{

				if ($use_url_alias)
				{
					$href_prev = '/'.$all_profiles_array[$prev_prof_id]['url_alias'];
				}
				else
				{
					$href_prev = $_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;profile_id='.$prev_prof_id;
				}				
				
				echo TAB_10.'<li class="ProfilePrevNextNav" >' ."\n";
					echo TAB_11.'<a class="ProfilePrevButton" href="'.$href_prev.'" ' ."\n";
						echo TAB_12.'title="View the previous profile in this category: '.$all_profiles_array[$prev_prof_id]['profile_name'].'" >'."\n";
						echo TAB_12.' &lt;&lt; Prev. Profile ' ."\n";
					echo TAB_11.'</a>' ."\n";	
				echo TAB_10.'</li>' ."\n";
			}
			else
			{
				echo TAB_10.'<li class="ProfilePrevNextNavBlank" > &lt;&lt; Prev. Profile </li>' ."\n";
			}

			echo TAB_10.'<li class="ProfilePrevNextNav" >' ."\n";
				echo TAB_11.'<a class="ProfileReturnButton"' 
					.' href="'.$_SERVER['PHP_SELF'].'?p='.$page_id.'"'
					.' title="Return to Listing" >' ."\n";
					echo TAB_12.'Return' ."\n";
				echo TAB_11.'</a>' ."\n";	
			echo TAB_10.'</li>' ."\n";
			
			if ($next_prof_id)
			{
				
				if ($use_url_alias)
				{
					$href_next = '/'.$all_profiles_array[$next_prof_id]['url_alias'];
				}
				else
				{
					$href_next = $_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;profile_id='.$next_prof_id;
				}	
				
				echo TAB_10.'<li class="ProfilePrevNextNav" >' ."\n";
					echo TAB_11.'<a class="ProfileNextButton" href="'.$href_next.'" ' ."\n"; 
						echo TAB_12.'title="View the next profile in this category: '.$all_profiles_array[$next_prof_id]['profile_name'].'" >' ."\n";
						echo TAB_12.' Next Profile &gt;&gt; ' ."\n";
					echo TAB_11.'</a>' ."\n";	
				echo TAB_10.'</li>' ."\n";
			}
			else
			{
				echo TAB_10.'<li class="ProfilePrevNextNavBlank" > Next Profile &gt;&gt; </li>' ."\n";
			}			
			echo TAB_9.'</ul>' ."\n";
			
		echo TAB_10.'</div>' ."\n";		
	}

		
	
?>