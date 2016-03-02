<?php
// no direct access
	defined('SITE_KEY') or die('File not found');
	
	//	Head data
	define ('DOCTYPE_TAG_CODE', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"' . PHP_EOL 
			. '    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
	
	define ('HTML_OPEN_TAG_CODE', '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">');

	define ('META_HTTP_EQUIV_TAG_CODE', '<meta http-equiv="content-type" content="text/html; charset=utf-8" />');
	
	define ('HTML_CODE_COMMENT', 'Created with the Notepad++  Text editor (   go to: http://notepad-plus.sourceforge.net   for a free copy )');
	
	//define ('EMAIL_REG_EXP_STRING', '/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/');
	define ('EMAIL_REG_EXP_STRING', "^[a-z0-9!#$%&'*+/=?_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)\b^");
	//define ('EMAIL_REG_EXP_STRING', "/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,4}$/" );

	//	list of Top level daomains found @ http://en.wikipedia.org/wiki/List_of_Internet_top-level_domains
	
?>