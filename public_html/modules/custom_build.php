<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	//	test for cookies enabled (except when displaying reciept linked from order email)
	if(!isset($_COOKIE['PHPSESSID']))
	{ 
		if (!isset($_GET['nocookies']) )
		{
			header ("location: ".$_SERVER['PHP_SELF']. '?' .$_SERVER['QUERY_STRING'].'&nocookies=1'); 
			exit();			
		}
		else
		{
			echo TAB_8.'<h2 class="Notice" >Notice: Cookies must be enabled in your browser for this facility to function.</h2>'."\n";
			//echo TAB_8.'<h2 class="Notice" >If this notice persists, please enable Cookies to continue.</h2>'."\n"; 
			echo TAB_8.'<h2 class="Notice" >Please enable Cookies to continue.</h2>'."\n"; 
		}

	}	
	
	
	//	strip and trim 
	foreach($_REQUEST as $key => $value )
	{$_REQUEST[$key] = trim(strip_tags($value));}
	
	//////// MAY NEED TO REMOVE WHEN IN CMS mode ????
	foreach($_POST as $key => $value )
	{$_POST[$key] = trim(strip_tags($value));}

	foreach($_GET as $key => $value )
	{$_GET[$key] = trim(strip_tags($value));}
	
	require_once (CODE_NAME.'_shop_configs.php');
	
	$error = FALSE;

	if (isset($_SESSION['cust_email'])) {$cust_email = $_SESSION['cust_email'];} else {$cust_email = '';}
	if (isset($_SESSION['cust_name'])) {$cust_name = $_SESSION['cust_name'];} else {$cust_name = '';}
	
	if (isset($_SESSION['required_comments']))
	{
		$required_comments = $_SESSION['required_comments'];
	}
	else
	{
		$required_comments = '';
	}	
		
	if (isset($_POST['add2cart']) OR isset($_POST['get_quote']))
	{
		require_once('custom_build_process.php');
	}
	
	
	echo "\n";			
	echo TAB_7.'<!--	START Custom Build code 	-->'."\n";
	echo "\n";
	
	
	//	FOR CMS MODE ONLY	================================================================================
	$div_name = 'Mod_'.$div_id.'_'.$mod_info['mod_id'];
	
	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{			
		
		//	Display / Hide In-Active Mods
		include ('CMS/cms_inactive_mod_display.php');

		//	Show Div Mod Button
		echo TAB_7.'<div id="EditDivModDisplay_'.$mod_id.'" class="EditDivModDisplay"' 
					.' title="Click to Edit this Module">'."\n";
			
			echo TAB_8.'<p style="background-color:#ffffff;color:#aa44aa; cursor: pointer;"'
			.' onClick="javascript:selectMod2Edit('.$mod_id.',\''.$div_name.'\' ,0, 2);">'
			.'[ Custom Build Module (click to edit) ]<p>'."\n";
			
		echo TAB_7.'</div>'."\n";
	
	}

	//======================================================================================================	

	
	
	
	

	

	//	read from db to Build info
	$mysql_err_msg = 'Custom Build information unavailable';	
	$sql_statement = 'SELECT * FROM custom_build WHERE mod_id = "'.$mod_id.'"';
			
	$custom_build_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	

	//	Header
	echo TAB_7.'<div class="ShopDiv" id="CustomShopHeader" >'."\n";
		
		//	Heading
		echo TAB_8.'<h1 id="CustomBuildHeading_'.$mod_id.'"  >'.$custom_build_info['heading'].'</h1>'."\n";
	
		//	text_1
		echo TAB_8.'<p id="CustomBuildText_1_'.$mod_id.'"  >' ."\n";	
			echo TAB_9.$custom_build_info['text_1'] ."\n";	
		echo TAB_8.'</p>' ."\n";
		
    echo TAB_7.'</div>'."\n";
	
	//	Left side colunm
	echo TAB_7.'<div class="ShopDiv" id="CustomShopLeftColumn" >'."\n";
	echo "\n";		


		//	 Do  plug in Module s for the MOD's Left column
		GetModInfo ($page_id, 12, $site_theme_id);
	
	echo TAB_7.'</div>'."\n";			


	//	Centre colunm
	echo TAB_7.'<div class="ShopDiv" id="CustomShopCentreColumn" >'."\n";
	
	
		//	read from db to get Steps
		$mysql_err_msg = 'Custom Build information unavailable';	
		$sql_statement = 'SELECT * FROM custom_build_steps WHERE mod_id = "'.$mod_id.'" AND active = "on" ORDER BY step_seq';		
		$custom_step_result = ReadDB ($sql_statement, $mysql_err_msg);
		$num_steps = mysql_num_rows($custom_step_result);
		
		echo TAB_8.'<form name="CustomBuild" id="CustomBuild" action="'.$_SERVER['PHP_SELF'].'?p='.$page_id.'" method="post">'."\n";
		
		echo TAB_8.'<div class="StepNav">'."\n";
			echo TAB_9.'<a id="NavPrev" onClick="javascript:NavPrev('.$num_steps.');" ><button>&lt;&lt;-- Prev. Step</button></a> '."\n";
			echo TAB_9.'<a id="NavNext" onClick="javascript:NavNext('.$num_steps.');" ><button>Next Step --&gt;&gt;</button></a>'."\n";			
		
			echo TAB_9.'<input id="ResetButton" type="reset" value="Reset" onClick="javascript:ResetForm();"/>'."\n";
		echo TAB_8.'</div>'."\n";
		
	
			echo TAB_9.'<input type="hidden" name="mod_id" value="'.$mod_id.'"/>'."\n";			
	
			
			echo TAB_9.'<div id="CustomBuildStepContainer_'.$mod_id.'" >'."\n";
		
			
			$step = 1;
			while ($custom_step_info = mysql_fetch_array ($custom_step_result))
			{
				echo TAB_10.'<div class="CustomBuildStep" id="CustomBuildStep_'.$custom_step_info['step_id'].'" >'."\n";
				
					if (isset(${'error_step_'.$step}) AND ${'error_step_'.$step} == TRUE)
					{
						echo TAB_11.'<span class="WarningMSG Error" >ERROR : '.$custom_step_info['error_msg'].'</span>'. "\n";				
					}
					
					echo TAB_11.'<h2 class="CustomBuildHeading" >Step '.$step.' of '.$num_steps.' : '.$custom_step_info['step_heading'].'</h2>'."\n";
					
					echo TAB_11.'<p class="CustomBuildDesc" >'.$custom_step_info['step_desc'].'</p>'."\n";

					$img_url = '_images_user/'.$custom_step_info['image_file'];
					if ($custom_step_info['image_file'] AND file_exists($img_url))
					{
						echo TAB_11.'<img class="CustomBuildStepImage" id="CustomBuildStepImage_'.$custom_step_info['step_id'].'"'."\n";
							echo TAB_12.' src="'.$img_url.'" alt="step: '.$step.' - '.$custom_step_info['image_file'].' - image">'."\n";			
					}

					
				//=====================================================================================			
					
				$tab = '';
				CustomBuildOpions(0, $custom_step_info['step_id'], $custom_step_info['step_input_type'], $custom_step_info['step_name'], $tab);
					
				//=====================================================================================					
					
					echo TAB_11.'<div class="CustomBuildOptionSelected" id="CustomBuildOptionSelected_'.$custom_step_info['step_id'].'">'
							.'</div>'."\n";
					
					
				echo TAB_10.'</div>'."\n";

				$step++;
				
			}
			
				echo TAB_10.'<input type="hidden" name="last_step" value="'.$step.'"/>'."\n";
				
				//	Model Number
				echo TAB_10.'<input id="ModelNameInput" type="hidden" name="model_name" value=""/>'."\n";
			
				//	Last Step				
				echo TAB_10.'<div class="CustomBuildStep" id="CustomBuildStep_Last" >'."\n";
				
					//	================	Do Summery of options ====================================
					$sql_statement = 'SELECT * FROM custom_build_steps WHERE mod_id = "'.$mod_id.'" AND active = "on" ORDER BY step_seq';
					$custom_step_result = ReadDB ($sql_statement, $mysql_err_msg);
					$num_steps = mysql_num_rows($custom_step_result);	
					
					echo TAB_12.'<div id="CustomBuildStepSummeryDiv">'."\n";
					
						echo TAB_13.'<ol id="CustomBuildStepSummeryList">'."\n";						
						
						while ($custom_step_info = mysql_fetch_array ($custom_step_result))
						{
							$step = $custom_step_info['step_id'];
							echo TAB_14.'<li class="CustomBuildStepSummery" id="CustomBuildStepSummery_'.$step.'" >'."\n";
							
								echo TAB_15.$custom_step_info['step_name'] . ': ' ."\n";
								echo TAB_15.'<span id="SummeryOption_'.$step.'-'.$custom_step_info['step_name']
								.'"></span>' ."\n";
								
							echo TAB_14.'</li>'."\n";
													
						}
						
						echo TAB_13.'</ol>'."\n";
						
						echo TAB_13.'<p>( Model Code: <span id="ModelNameBox"></span> )</p>' ."\n";
						
						echo TAB_13.'<div id="CustomBuildSummaryPrice"></div>' ."\n";
						
					echo TAB_12.'</div>'."\n";	
						
			
					//	Other REQ. comments
					echo TAB_11.'<label for="CustomBuildOption_extra_comments">Other Requirements / comments:</label>'."\n";
					echo TAB_11.'<textarea id="CustomBuildOption_extra_comments" name="CustomBuildOption_extra_comments">';
						echo $required_comments;
					echo '</textarea>'."\n";
					
					//	Add to Cart
					echo TAB_11.'<span id="CustomBuild_Add2Cart">'."\n";
						echo TAB_12.'<fieldset id="CustomBuild_Add2CartFieldset">'."\n";
							echo TAB_13.'<h3>Add to Cart:</h3>'."\n";
						
							//	Quantitiy (add to cart button)
							if (!isset($add2cart_quantity))
							{
								$add2cart_quantity = 1;
							}
								
							echo TAB_13.'<label for="add2cart_quantity">Quantity: </label>'."\n";
							echo TAB_13.'<input type="text" id="add2cart_quantity" name="add2cart_quantity"'
										.' value="'.$add2cart_quantity.'" size="3" maxlength="5" />'."\n";
							
							//	submit (add to cart button)
							echo TAB_13.'<input type="submit" name="add2cart" value="Add to Cart" />'."\n";
							
							if (isset($add2cart_error) AND $add2cart_error == TRUE)
							{
								echo TAB_13.'<br/><span class="WarningMSG Error" >ERROR : '.$add2cart_error.'</span>'. "\n";				
							}								
							
							
						echo TAB_12.'</fieldset>'."\n";																	
						
						
						echo TAB_12.'<h3>OR</h3>'."\n";
					
					echo TAB_11.'</span>'."\n";
					
					//	Get Quote
					echo TAB_11.'<fieldset id="CustomBuild_GetQuote">'."\n";
						
						echo TAB_12.'<h3>Get Quote:</h3>'."\n";
						
						echo TAB_12.'<label for="GetQuote_name">Your Name: </label>'."\n";
						echo TAB_12.'<input type="text" id="GetQuote_name" name="get_quote_name" value="'.$cust_name.'" size="16" />'."\n";

						echo TAB_12.'<label for="GetQuote_email">Your Email: </label>'."\n";
						echo TAB_12.'<input type="text" id="GetQuote_email" name="get_quote_email" value="'.$cust_email.'" size="25" />'."\n";
						
						//	submit (add to cart button)
						echo TAB_12.'<input type="submit" name="get_quote" value="Get a Quote" />'."\n";
						
						
						if (isset($_POST['get_quote']) AND $error == FALSE)
						{
							//	print errors
							if (isset($quote_error) AND $quote_error == TRUE)
							{
								echo TAB_12.'<br/><span class="WarningMSG Error" >ERROR : '.$quote_error_msg.'</span>'. "\n";				
							}
							
						}
						
						// confirm message
						elseif (isset($_REQUEST['quote_sent']))
						{
							echo TAB_12.'<fieldset id="CustomBuild_QuoteSent">'."\n";
								echo TAB_13.'<h3>Thank you '.$cust_name.', the details for a quote have been sent.</h4>'. "\n";	
								echo TAB_13.'<h3>You will hear from us shortly...</h4>'. "\n";
							echo TAB_11.'</fieldset>'."\n";	
						}					
											
					echo TAB_11.'</fieldset>'."\n";	
								
				echo TAB_10.'</div>'."\n";
				
			echo TAB_9.'</div>'."\n";
			
		
			
		echo TAB_8.'</form>'."\n";
			
		echo TAB_8.'<div id="PageIndex"></div>'."\n";			
	
		//	 Do  plug in Module s for the sweeps MOD's Centre column
		GetModInfo ($page_id, 13, $site_theme_id);	
	
	//	Footer
	echo TAB_7.'<div class="ShopDiv" id="CustomShopFooter" >'."\n";	
		
		//	text_2
		echo TAB_8.'<p id="CustomBuildText_2_'.$mod_id.'"  >' ."\n";	
			echo TAB_9.$custom_build_info['text_2'] ."\n";	
		echo TAB_8.'</p>' ."\n";	
	
	echo TAB_7.'</div>'."\n";


	echo TAB_7.'</div>'."\n";	
	echo "\n";	
	
	//	right side colunm
	echo TAB_7.'<div id="CustomShopRightColumn" >'."\n";
	echo "\n";		

		echo TAB_8.'<div class="ShopDiv" id="CustomBuildSummary" >'."\n";
	
			echo TAB_9.'<h4 class="Shop" >Summary:</h4>' ."\n";
			
			echo TAB_9.'<div id="CustomBuildSummaryDetails"></div>' ."\n";
			echo TAB_9.'<div id="CustomBuildSummaryPriceBox"></div>' ."\n";
			
		echo TAB_8.'</div>'."\n";
	

		//	 Do and plug in Module s for the sweeps MOD's Right column
		GetModInfo ($page_id, 14, $site_theme_id);	

	echo TAB_7.'</div>'."\n";		
	




////////////////////////	KILL SESSION FOR TESTING  ////////////////////////////////////////////////////////////////////////////
/* 
echo TAB_10.'<a class="ShopLink" href="http://'.$_SERVER['SERVER_NAME']
	.$_SERVER['PHP_SELF'].'?p='.$page_id.'&amp;session_destroy=yes'.'"' ."\n";
	echo TAB_11.'  >RESET ALL' ."\n";
echo TAB_10.'</a>' ."\n";	
*/
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





	
	//	FOR CMS MODE ONLY	================================================================================
	if (isset($_SESSION['CMS_mode']) AND $_SESSION['CMS_mode'] == TRUE AND UserPageAccess($page_id) > 1)
	{	
		$edit_enabled = 1;
		$mod_locked = 2;
		$can_not_clone = 1;

		//	CSS layout Dispay (for CMS)
		$CSS_layout = '&lt;div id="Mod_'.$div_id.'_'.$mod_info['mod_id'].'" class="DivMod_'.$mod_info['mod_id'].'" &gt;';
		
		//	Do mod editing Toolbar
		include ('CMS/cms_toolbars/cms_toolbar_edit_mod.php');
		
		//	Do Mod Config Panel
		include ('CMS/cms_panels/cms_panel_mod_config.php');
							

	}
	//	========================================================================================================
		
	
	echo "\n";			
	echo TAB_7.'<!--	END Custom Build code 	-->'."\n";
	echo "\n";		
	


function CustomBuildOpions($option_parent_id, $step_id, $step_input_type, $step_name, $tab )
{
	
	echo TAB_11.'<div id="CustomBuildOptionContainer_'.$option_parent_id.'" class="CustomBuildOptionContainer">' ."\n";
	
	if ($step_input_type == 'select')
	{
		echo $tab.TAB_11.'<select id="CustomBuildOptions_Step_'.$option_parent_id.'-'.$step_id.'"'
			.' class="CustomBuildSelect" name="'.$step_id.'-Select-'.$step_name.'" >'."\n";
			echo $tab.TAB_12.'<option id="SelectOptionBLANK_'.$step_id.'-'.$option_parent_id.'" value="[not selected]">choose...</option>'."\n";
		$end_tag = '</select>';				
	}
	else
	{
		echo $tab.TAB_11.'<ul class="CustomBuildOptions" id="CustomBuildOptions_Step_'.$option_parent_id.'-'.$step_id.'" >'."\n";
		$end_tag = '</ul>';
	}
	
		
	//	read from db to get Options
	$mysql_err_msg = 'Custom Build information unavailable';	
	$sql_statement = 'SELECT * FROM custom_build_options'
	
										.' WHERE step_id = "'.$step_id.'"'
										.' AND option_parent_id = '.$option_parent_id
										.' AND active = "on"'
										.' ORDER BY option_seq'
										;		
	
	
	$custom_option_result = ReadDB ($sql_statement, $mysql_err_msg);
	$num_options = mysql_num_rows($custom_option_result);			

	
	while ($option_info = mysql_fetch_array ($custom_option_result))
	{
		

		switch ($option_info['price_type'])
		{
				
			case 'NA':
			
				$option_price_str = '';
				$option_price = '';
		
			break;
		
			case 'other':
			
				$option_price_str = ' : [ ' . $option_info['price'] . ' ]';
				$option_price = '??';
		
			break;	
				
			case 'neg':
			
				$option_price_str = ' : [ - ' .SHOP_CURRENCY_SYMBOL_PREFIX.number_format($option_info['price'],2).SHOP_CURRENCY_SYMBOL_SUFFIX. ' ]';
				$option_price = '-'.number_format($option_info['price'],2);
			
			break;	
			
			case 'pos':
			case 'zero':
			
				$option_price_str = ' : [ + ' .SHOP_CURRENCY_SYMBOL_PREFIX.number_format($option_info['price'],2).SHOP_CURRENCY_SYMBOL_SUFFIX. ' ]';
				$option_price = number_format($option_info['price'],2);
			
			break;	
			
		}
		
		if ($option_info['option_checked'] == 'on')
		{
			$checked = ' checked="checked"';
			$selected = ' selected="selected"';
		}
		else
		{
			$checked = '';
			$selected = '';				
		}
		
		
		if($option_info['more_info'])
		{
			$more_info_class = ' MoreInfo';
		}
		else
		{
			$more_info_class = '';
		}
		
		
		switch ($step_input_type)
		{
			
			case '':
				echo $tab.TAB_12.'<li class="CustomOptionLi'.$more_info_class.'" id="CustomBuildOption_'.$option_info['option_id'].'"' 
					.' title="'.$option_info['tooltip_text'].'" >'."\n";
					echo $tab.TAB_13.'<label for="CustomBuildInput_'.$option_info['option_id'].'" >'.$option_info['option_label'].'</label>'."\n";
				echo $tab.TAB_12.'</li>'."\n";
			break;
			
			case 'radio':
			case 'checkbox':
			
			
				echo $tab.TAB_12.'<li class="CustomOptionLi'.$more_info_class.'" id="CustomBuildOption_'.$option_info['option_id'].'"'
					.' title="'.$option_info['tooltip_text'].'">'."\n";
					echo $tab.TAB_13.'<input type="'.$step_input_type.'" id="CustomBuildInput_'.$option_info['option_id'].'"'
					.' class="CustomBuildInput_'.$option_parent_id.'-'.$step_id.' CustomOption" name="'.$step_id.'-'.$option_info['option_name'].'"'
							.' value="'.$option_info['option_value'].' [$'.$option_price.']"'.$checked.' />'."\n";	
					echo TAB_12.'<span style="display:none;" name="'.$option_info['option_code'].'" ></span>'."\n";	

					echo TAB_12.'<span style="display:none;" id="OptionCode_'.$option_info['option_id'].'" >'
					.$option_info['option_code'].'</span>'."\n";		
					
					echo $tab.TAB_13.'<label for="CustomBuildInput_'.$option_info['option_id'].'">'
					. $option_info['option_label'] . $option_price_str.'</label>'."\n";
		
				echo $tab.TAB_12.'</li>'."\n";			
			break;
			
			case 'text':
			
				echo $tab.TAB_12.'<li class="CustomOptionLi'.$more_info_class.'" id="CustomBuildOption_'.$option_info['option_id'].'"'
					.' title="'.$option_info['tooltip_text'].'">'."\n";
					echo $tab.TAB_13.'<label for="CustomBuildInput_'.$option_info['option_id'].'">'
						. $option_info['option_label'] . $option_price_str.'</label>'."\n";
					echo $tab.TAB_13.'<input type="'.$step_input_type.'" id="CustomBuildInput_'.$option_info['option_id'].'"'
							.' class="CustomBuildInput_'.$option_parent_id.'-'.$step_id.' CustomOption"' 
							.' name="'.$step_id.'-'.$option_info['option_name'].'" value="'.$option_info['option_value'].'" />'."\n";
										
				echo $tab.TAB_12.'</li>'."\n";
			break;
			
			case 'select':
			
				echo $tab.TAB_12.'<option id="SelectOption_'.$option_info['option_id'].'" class="CustomOption"'
					.'value="'.$option_info['option_value'].' [$'.$option_price.']"'.$selected.' title="'.$option_info['tooltip_text'].'">'."\n";
					echo $tab.TAB_13.$option_info['option_value']. $option_price_str ."\n";
				echo $tab.TAB_12.'</option>'."\n";
			break;
			
			case 'textarea':
			
				echo $tab.TAB_12.'<textarea class="CustomOption'.$more_info_class.'" id="CustomBuildOption_'.$option_info['option_id'].'">'."\n";
					echo $tab.TAB_13.$option_info['option_value']."\n";
				echo $tab.TAB_12.'</textarea>'."\n";
			break;			
										
		}
		
		//		Add hover over More info
		if($option_info['more_info'] OR $option_info['option_img'])
		{
			echo $tab.TAB_13.'<div class="CustomBuildOptionMoreInfo" id="CustomBuildOptionMoreInfo_'.$option_info['option_id'].'">'."\n";
				
				echo $tab.TAB_14.'<p>'.$option_info['option_label'].'</p>' . "\n";
				
				echo $tab.TAB_14.'<p>'.$option_info['more_info'].'</p>'."\n";
			
			$img_url = '_images_shop/'.$option_info['option_img'];
			if 
			(
					$option_info['option_img'] 
				AND ($option_info['display_img'] == 'more_info' OR $option_info['display_img'] == 'both')
				AND file_exists($img_url)
			)
			{
				echo $tab.TAB_14.'<img class="CustomBuildOptionMoreInfoImg" id="CustomBuildOptionMoreInfoImg_'.$option_info['option_id'].'"'
					.' src="'.$img_url.'" />'."\n";
			}
				
			echo $tab.TAB_13.'</div>'."\n";
		}
		
		if($option_info['option_sub_input'])
		{

			$tab .= '  ';
			CustomBuildOpions($option_info['option_id'], $step_id, $option_info['option_sub_input'], $option_info['option_name'], $tab);
			$tab = substr($tab, 2);	
		}


		
			
	}
	
	echo $tab.TAB_11.$end_tag."\n";
	
	echo TAB_11.'</div>' ."\n";
	
}


	
?>	