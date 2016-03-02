<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	$file_path_offset = '../../';
	
					

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');
	require_once ('cms_update_common.php');

if ($_SESSION['CMS_mode'] == TRUE AND $_SESSION['access'] < 4 )
{


	//---------------------do IMAGE UPLOAD:--------------------------------------------------------

		//-----------------check if s file is  selected for upload and check file type
		if (isset($_FILES['upload_nav_icon']) AND $_FILES['upload_nav_icon']['name'])
		{			
			if (preg_match('/\.(jpg|jpeg|gif|jpe|pcx|bmp|png)$/i', $_FILES['upload_nav_icon']['name']))	
			{	

				//------get image file name and strip spaces
				$new_image = str_replace(" ","_", $_FILES['upload_nav_icon']['name']);			
		
				$upload_path = $file_path_offset.'_images_user/';
				if (!move_uploaded_file($_FILES['upload_nav_icon']['tmp_name'], $upload_path.$new_image))	 //	failed to upload
				{
					$_SESSION['update_error_msg'] = "ERROR - File Upload Failed! - ".FileUploadErrorMessage($_FILES['upload_nav_icon']['error']); 
					$new_image = $_POST['default_nav_icon'];
				}
						
				else {@chmod( $upload_path.$new_image, 0666);}	// 	Set Rights To Uploaded File}
			}
			
			else {$_SESSION['update_error_msg'] = "ERROR - File Type Not allowed";}

		}

		else { $new_image = $_POST['default_nav_icon'];}	//	asign selected image
	
	
	
	//-------update settings config file------------------------
		if (!is_writable('../../../_include_files/'.CODE_NAME.'_configs.php'))

		{$_SESSION['update_error_msg'] = "ERROR - Rewrite of Config File Failed!";}
		
		else
		{
			$fopen = @fopen('../../../_include_files/'.CODE_NAME.'_configs.php','w');
			$str = 
			
"<?php

	// no direct access
	defined('SITE_KEY') or die('File not found');

	define('DEFAULT_TIME_ZONE', 'Australia/Sydney');	
	define('HIDDEN_HEADING', '".Space2nbsp (htmlspecialchars ($_POST['hidden_heading'], ENT_QUOTES))."');
	define('SITE_NAME', '".Space2nbsp (htmlspecialchars ($_POST['site_name'], ENT_QUOTES))."');
	define('SITE_TITLE_TAGLINE', '".Space2nbsp (htmlspecialchars ($_POST['site_title_tagline'], ENT_QUOTES))."');
	define('SITE_CREDIT_TAGLINE', '".SITE_CREDIT_TAGLINE."');
	define('SITE_URL', '".$_POST['site_url']."');
	define('SITE_THEME_ID', ".$_POST['site_theme_id'].");
	define('HOME_PAGE_ID', ".$_POST['home_page_id'].");	
	define('SEARCH_PAGE_ID', ".SEARCH_PAGE_ID.");
	define('SHOP_PAGE_ID', ".SHOP_PAGE_ID.");
	define('SHOP_MOD_ID', ".SHOP_MOD_ID.");
	define('SHOP_DB_NAME_PREFIX', '".SHOP_DB_NAME_PREFIX."');
	define('SITE_SHUTDOWN', ".$_POST['site_shutdown'].");
	define('SITE_CMS_SHUTDOWN', ".$_POST['CMS_shutdown'].");
	define('ALLOW_HOTLINKING', ".$_POST['allow_hotlinking'].");
	
	define('ADMIN_THEME_FILE', '_cms_fixed_white.css');
	define('USER_SELECTS_THEME', '".$_POST['user_selects_theme']."');
	define('NUM_NAV_LAYERS', ".$_POST['num_nav_layers'].");
	define('DEFAULT_NAV_ICON', '".$new_image."');
	define('PATH_SEPERATOR_SYMBOL', '".htmlentities ($_POST['path_seperator_symbol'], ENT_QUOTES, "UTF-8")."');
	define('TITLE_SEPERATOR_SYMBOL', '".htmlentities ($_POST['title_seperator_symbol'], ENT_QUOTES, "UTF-8")."');
	define('FORM_REQD_FIELD_SYMBOL', '".FORM_REQD_FIELD_SYMBOL."');	
	define('CMS_USE_HTTPS', ".CMS_USE_HTTPS.");
	define('CMS_EDIT_OUTLINE_STYLE', '".CMS_EDIT_OUTLINE_STYLE."');
	define('CMS_PANEL_WIN_WIDTH', '".CMS_PANEL_WIN_WIDTH."');
	define('MAX_FILE_SIZE', '".MAX_FILE_SIZE."');
	define('MAX_FILE_SIZE_CMS', '".MAX_FILE_SIZE_CMS."');
	define('FILE_IMAGE_RESIZE_MODE', ".FILE_IMAGE_RESIZE_MODE.");
	define('FILE_IMAGE_MAX_WIDTH', ".FILE_IMAGE_MAX_WIDTH.");
	define('FILE_IMAGE_MAX_HEIGHT', ".FILE_IMAGE_MAX_HEIGHT.");
	
?>";

			fputs($fopen, $str);
			fclose($fopen);
			
			$_SESSION['update_success_msg'] = "Update Succesfull";

		}
		
	//	when changing home page ID, the updated home page id needs to used
	$home_page_id = $_POST['home_page_id'];

	//	update the .htacces File
	$error = UpdateHtaccesFile ($home_page_id);

	//	update sitemap.xml file
	$error .= UpdateSiteMapFile ($home_page_id);	

	//	update robots.txt file....this reaally only needs updating when directory structures change
	$error .= UpdateRobotsTxtFile ();	
	
	$_SESSION['update_success_msg'] = "Update Succesfull";	
		


}

else
{
	
	$_SESSION['update_error_msg'] .= '- Insufficient Privileges to Modify Data \n';
	
}


	//$return_url = '/index.php?p='.$_POST['page_id'];
	$return_url = '../cms_global_settings.php';
	
	//	Re-Direct BACK
	header('location: '.$return_url); 
	exit();	
	
?>