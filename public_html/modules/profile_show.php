<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		$profile_id = $_REQUEST['profile_id'];
		
		//	read from db to Profile info
		$mysql_err_msg = 'Profile information unavailable';	
		$sql_statement = 'SELECT * FROM mod_profiles WHERE profile_id = "'.$profile_id.'"';
				
		$profile_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

		//	Display Profile if exists
		if ($profile_info)
		{
			echo TAB_8.'<div id="ProfileDisplay_'.$profile_id.'" class="ProfileDisplay" >'."\n";
						
			//	Do Heading (Name + Role)
			if ($profile_info['profile_name'] OR $profile_info['role'])
			{
				$profile_display_heading = $profile_info['profile_name'];
				
				//	Add seperator if both name and role exist
				if ($profile_info['profile_name'] AND $profile_info['role']) {$profile_display_heading .= ' - ';} 
				
				$profile_display_heading .= $profile_info['role'];
				
				echo TAB_9.'<h2 id="ProfileDisplayHeading_'.$profile_id.'" class="ProfileDisplayHeading">'."\n";
				
					echo TAB_10.$profile_display_heading."\n";
					
				echo TAB_9.'</h1>'."\n";
			}
			
			//	Do Profile Image
			$profile_image_path = '_images_user/profile/'.$profile_info['profile_img_file'];			
			
			if 
			(
					$profile_info['display_profile_img'] == 'on' 
				AND $profile_info['profile_img_file'] 
				AND file_exists($profile_image_path)
			)
			{
				
				if($profile_info['can_enlarge_imgs'] == 'on')
				{
					echo TAB_9.'<a href="'.$profile_image_path.'" rel="ColorBoxProfileGallery" title="'.$profile_info['profile_name'].'" >'."\n";
					$end_a_tag = TAB_9.'</a>' . "\n";
				}
				else { $end_a_tag = '';}
				
						
					echo TAB_10.'<img class="ProfileImageProfile" src="'.$profile_image_path.'"'
							.' alt="Profile image of: '.$profile_info['profile_name'].'" />'."\n";
				echo $end_a_tag;

			}
			
			//	Do Long_desc
			if ($profile_info['long_desc'])
			{
				echo TAB_9.'<span id="ProfileDisplayLong_desc_'.$profile_id.'" class="ProfileDisplayLong_desc">'."\n";
				
					echo TAB_10.$profile_info['long_desc']."\n";
					
				echo TAB_9.'</span>'."\n";			
			}
	
			//	Display contact info if set to
			if ($profile_info['display_contact_info'] == 'on')
			{
				echo TAB_9.'<table id="ProfileContactInfo_'.$profile_id.'" class="ProfileContactInfo">'."\n";
				
				if ($profile_info['email'] AND $profile_info['display_email_as'])	
				{
					echo TAB_10.'<tr class="ProfileContactInfoEmail" >'."\n";
						echo TAB_11.'<td class="ProfileContactLabel" >Email:</td>'."\n";
					
						//	Email Link options
						switch ($profile_info['link_email'])
						{
							case "":
								$open_a_tag = '';
								$close_a_tag = '';
							break;
							
							case "mailto":
								$open_a_tag = '<a class="ProfileListEmailLink" href="mailto:'.$profile_info['email'].'"'
												.' title="Click here to send '.$profile_info['profile_name'].' an email" >';
								$close_a_tag = '</a>';
							break;	
							
							case "form":
								$open_a_tag = '<a class="ProfileListEmailLink" href="index.php?p='.$page_id.'&amp;'
												.'emailto='.$profile_info['profile_id'].'"'
													.' title="Click here to send '.$profile_info['profile_name'].' an email" >';
								$close_a_tag = '</a>';
							break;	
							
						}	
						
						//	Email Display type options
						switch ($profile_info['display_email_as'])
						{
							case "":
								$email_display = 'click here to send an email';
							break;
							
							case "text":
								$email_display = $profile_info['email'];
							break;	
							
							case "img":

								$style = 'ProfileListEmailLink';
								
								$email_display = '<img class="Text2Image"'
											.' src="/text_image_email.php?prof_id='.$profile_info['profile_id'].'&style='.$style.'" '
											.'alt="Email address of: '.$profile_info['profile_name'].'" />';
			
							break;	
							
						}	

						echo TAB_11.'<td class="ProfileContactContent" >' ."\n";
							echo TAB_12.$open_a_tag ."\n";
								echo TAB_13.$email_display ."\n";
							echo TAB_12.$close_a_tag ."\n";
						echo TAB_11.'</td>'."\n";

					echo TAB_10.'</tr>'."\n";				
				}

				//	Phone N#  1 - 3
				$set_label = 1;
				$label_str = NULL;
				for ($i=1; $i<4; $i++ )
				{
					if ($profile_info['phone_'.$i])
					{
						if ($set_label == 1)
						{
							$label_str = 'Phone :';
							$set_label = 0;	
						}
						else
						{
							$label_str = NULL;						
						}

						$content_str = $profile_info['phone_'.$i];
						
					}
					else
					{
						$label_str = NULL;
						$content_str = NULL;							
					}
					
					echo TAB_10.'<tr class="ProfileContactInfoPhone" >'. "\n";
						echo TAB_11.'<td class="ProfileContactLabel" >'.$label_str.'</td>'. "\n";							
						echo TAB_11.'<td class="ProfileContactContent">'.$content_str.'</td>'. "\n";
					echo TAB_10.'</tr>'. "\n";											
				}
				
				//	Fax					
				if ($profile_info['fax'])
				{
					echo TAB_10.'<tr class="ProfileContactInfoPhone" >'. "\n";	
						echo TAB_10.'<td class="ProfileContactLabel" >Fax:</td>'. "\n";								
						echo TAB_10.'<td class="ProfileContactContent" >'.$profile_info['fax'].'</td>'. "\n";
					echo TAB_9.'</tr>'. "\n";
				}
				
				//	Website				
				if ($profile_info['website_url'])
				{
					echo TAB_10.'<tr class="ProfileContactInfoPhone" >'. "\n";	
						echo TAB_10.'<td class="ProfileContactLabel" >Website:</td>'. "\n";								
						echo TAB_10.'<td class="ProfileContactContent" >'. "\n";
						
						if ($profile_info['website_display'])
						{
							$url_display = $profile_info['website_display'];
						}
						else
						{
							$url_display = $profile_info['website_url'];
						}
							echo TAB_11.'<a href="http://'.$profile_info['website_url'].'" title="Go to '.$url_display.'" >' . "\n";
								echo TAB_12.$url_display . "\n";
							echo TAB_11.'</a>' . "\n";
						echo TAB_10.'</td>'. "\n";
					echo TAB_9.'</tr>'. "\n";
				}				
			
				echo TAB_9.'</table>'."\n";			
			}	

			//	Display images if set to
			if ($profile_info['display_images'] == 'on')
			{
				
				//	read from db to Profile Image info
				$mysql_err_msg = 'Profile Image information unavailable';	
				$sql_statement = 'SELECT * FROM mod_profile_images WHERE profile_id = "'.$profile_id.'" AND active = "on" ORDER BY seq';
					
				$image_result = ReadDB ($sql_statement, $mysql_err_msg);
				
				$num_images = mysql_num_rows($image_result);
				
				if($profile_info['can_enlarge_imgs'] == 'on' AND $num_images > 0)
				{
					echo TAB_9.'<p class="ClickImgMSG" >Click on an image to enlarge it:</p>'."\n";
				}
				echo TAB_9.'<div id="ProfileImageGallaryDiv_'.$profile_id.'" class="ProfileImageGallaryDiv">'."\n";					
				
				while ($image_info = mysql_fetch_array ($image_result))	
				{

					
					$image_path = '_images_user/profile/'.$image_info['image_file_name'];
					
					if($image_info['image_file_name'] AND file_exists($image_path))
					{
						
						echo TAB_10.'<div>'."\n";
						
						if($profile_info['can_enlarge_imgs'] == 'on')
						{
							echo TAB_11.'<a href="'.$image_path.'" rel="ColorBoxProfileGallery" title="'.$image_info['img_caption'].'" >'."\n";
							$end_a_tag = '</a>';
						}
						else { $end_a_tag = '';}
						
						echo TAB_12.'<img class="ProfileImageGallary" id="ProfileImageGallery_image_'.$image_info['image_id'].'"'."\n"; 
							echo TAB_13.' src="'.$image_path.'" alt="'.$image_info['img_caption'].'" rel="ColorBoxProfileGallery" />'."\n";
							

						
						if ($image_info['img_caption'])
						{
							echo TAB_12.'<span class="ProfileImageCaption">'.HiliteText(nl2br(Space2nbsp($image_info['img_caption']))).'</span>'."\n";
						}
						
						echo TAB_11.$end_a_tag;	
						
						echo TAB_10.'</div>'."\n";
						
					}
					
				}				
					
				echo TAB_9.'</div>'."\n";	
				
			}	

		
			echo TAB_8.'</div>'."\n";
			
		}
		
		else
		{
			echo TAB_8.'<h2 class="ProfileDisplayHeading" ><em>No '.$profiles_settings['profile_alias'].' found...</em></h1>'."\n";
		}


?>