<?php
// no direct access
	defined('SITE_KEY') or die('File not found');

	
//	jQuery files
	define ( 'JQUERY_MASTER_FILE', 'jquery-1.9.1.min.js' );
	define ( 'JQUERY_UI_FILE', 'jquery-ui-1.9.2.min.js' );
	
//	misc display text

	define ( 'SEARCH_BUTTON_LABEL', 'Search' );
	define ( 'THEME_SELECTOR_LABEL', 'Please choose a Design for this Website:' );

//	Google Maps data

	define ( 'GOOGLE_MAP_API_KEY', '###' );
		
	define ( 'GOOGLE_MAP_VERSION', '2' );
	define ( 'GOOGLE_MAP_SENSOR', 'false' );
	define ( 'GOOGLE_MAP_DIRECTIONS_URL', 'http://maps.google.com/maps' );
	define ( 'GOOGLE_MAP_DEFAULT_NEW_WIN_WIDTH', '620' );	
	define ( 'GOOGLE_MAP_DEFAULT_NEW_WIN_HEIGHT', '420' );

// Stop Hot Link file extentions:
	define ( 'HOT_LINK_FILE_EXTS', 'gif|jpg|jpeg|bmp|zip|rar|mp3|flv|swf|xml|png|css|pdf');	
	
//	Alternate Domain names
	$hot_link_allow_urls = 
				
				 'newsite.lin'
				.',siteofhand.com.au'
				.',engadinewebdesign.com'
				.',sutherlandshireweb.com.au'
				.',sydneysouthwebdesign.com'
				;
	
	define ( 'HOT_LINK_ALLOW_URLS', $hot_link_allow_urls );
	
?>