-- phpMyAdmin SQL Dump
-- version 2.11.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 04, 2013 at 01:06 PM
-- Server version: 5.0.77
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `_newsite`
--

-- --------------------------------------------------------

--
-- Table structure for table `1_user_logins`
--

DROP TABLE IF EXISTS `1_user_logins`;
CREATE TABLE IF NOT EXISTS `1_user_logins` (
  `user_id` int(11) NOT NULL,
  `last_login` datetime NOT NULL,
  `last_logout` datetime NOT NULL,
  `login_from_ip` varchar(64) NOT NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `1_user_logins`
--

INSERT INTO `1_user_logins` (`user_id`, `last_login`, `last_logout`, `login_from_ip`) VALUES
(1, '2013-05-23 02:02:46', '0000-00-00 00:00:00', '192.168.0.9'),
(2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(5, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(6, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(7, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(8, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(9, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(10, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(11, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(12, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(13, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(14, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(15, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(16, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(17, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(18, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(19, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(20, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `2_contact_form_recieved_data`
--

DROP TABLE IF EXISTS `2_contact_form_recieved_data`;
CREATE TABLE IF NOT EXISTS `2_contact_form_recieved_data` (
  `id` int(11) NOT NULL auto_increment,
  `form_id` int(11) NOT NULL,
  `msg_id` int(11) NOT NULL,
  `time_sent` datetime default NULL,
  `ip_add` varchar(50) collate latin1_general_ci default NULL,
  `label` varchar(255) collate latin1_general_ci default NULL,
  `value` longtext collate latin1_general_ci,
  `failed_captcha` varchar(50) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `2_contact_form_recieved_data`
--


-- --------------------------------------------------------

--
-- Table structure for table `cms_toolbar_buttons`
--

DROP TABLE IF EXISTS `cms_toolbar_buttons`;
CREATE TABLE IF NOT EXISTS `cms_toolbar_buttons` (
  `button_id` int(11) NOT NULL auto_increment,
  `seq` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `icon_image` varchar(50) NOT NULL,
  `rel` varchar(50) NOT NULL,
  `tab` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  PRIMARY KEY  (`button_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cms_toolbar_buttons`
--

INSERT INTO `cms_toolbar_buttons` (`button_id`, `seq`, `mod_id`, `name`, `icon_image`, `rel`, `tab`, `title`, `active`) VALUES
(1, 1, 100, 'Shop', 'icon_shop_24x24.png', 'CMS_ColorBox_EditGlobalSettings', 1, 'Configure the On-Shop Settings', ''),
(2, 2, 9999, 'Event Listing', 'icon_event_24x24.png', 'CMS_ColorBox_EditGlobalSettings', 0, 'Configure the Event Listing Settings', '');

-- --------------------------------------------------------

--
-- Table structure for table `css_includes`
--

DROP TABLE IF EXISTS `css_includes`;
CREATE TABLE IF NOT EXISTS `css_includes` (
  `include_id` int(11) NOT NULL auto_increment,
  `file_path` varchar(255) collate latin1_general_ci NOT NULL,
  `media_type` varchar(255) collate latin1_general_ci NOT NULL default 'screen',
  `ie_condition` varchar(50) collate latin1_general_ci NOT NULL,
  `seq` int(11) NOT NULL,
  `active` set('on','') character set utf8 NOT NULL default 'on',
  `desc` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`include_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `css_includes`
--

INSERT INTO `css_includes` (`include_id`, `file_path`, `media_type`, `ie_condition`, `seq`, `active`, `desc`) VALUES
(1, '/_themes/print.css', 'print', '', 10, 'on', ''),
(2, '/includes/javascript/colorbox.css', 'screen', '', 20, 'on', '');

-- --------------------------------------------------------

--
-- Table structure for table `js_includes`
--

DROP TABLE IF EXISTS `js_includes`;
CREATE TABLE IF NOT EXISTS `js_includes` (
  `include_id` int(11) NOT NULL auto_increment,
  `file_path` varchar(255) collate latin1_general_ci NOT NULL,
  `seq` int(11) NOT NULL,
  `active` set('on','') character set utf8 NOT NULL default 'on',
  `desc` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`include_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `js_includes`
--

INSERT INTO `js_includes` (`include_id`, `file_path`, `seq`, `active`, `desc`) VALUES
(1, '/includes/javascript/NewWindowScripts.js', 50, 'on', 'New Window js'),
(3, '/includes/javascript/jquery.textarearesizer.js', 30, 'on', 'Jquery Text Area Resizer'),
(4, '/includes/javascript/jquery.FormFunctions.js', 40, 'on', 'Common Form Functions, textarea expand, higligher etc'),
(5, '/includes/javascript/jquery.easing.1.3.js', 20, 'on', 'Jquery animation'),
(6, '/_javascript_custom/google_anyalytics.js', 60, '', 'Google Analytics Code'),
(8, '/includes/javascript/jquery.colorbox-min.js', 70, 'on', '');

-- --------------------------------------------------------

--
-- Table structure for table `meta_settings`
--

DROP TABLE IF EXISTS `meta_settings`;
CREATE TABLE IF NOT EXISTS `meta_settings` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate latin1_general_ci default NULL,
  `content` longtext collate latin1_general_ci,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `meta_settings`
--

INSERT INTO `meta_settings` (`id`, `name`, `content`, `active`) VALUES
(1, 'keywords', '', 'on'),
(2, 'ROBOTS', 'FOLLOW', 'on'),
(3, 'google-site-verification', '', ''),
(4, 'description', '', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `mod_id` int(11) NOT NULL auto_increment,
  `mod_type_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `div_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `theme_specific` int(11) NOT NULL default '0',
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `sync_id` int(11) NOT NULL default '0',
  `class_name` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`mod_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1066 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`mod_id`, `mod_type_id`, `page_id`, `div_id`, `position`, `theme_specific`, `active`, `sync_id`, `class_name`) VALUES
(9, 10, 4, 3, 6, 0, 'on', 0, 'Shadow'),
(10, 7, 100, 3, 2, 0, 'on', 0, ''),
(18, 6, 1, 1, 1, 0, 'on', 1, ''),
(19, 6, 2, 1, 1, 0, 'on', 1, ''),
(20, 6, 3, 1, 1, 0, 'on', 1, ''),
(21, 6, 4, 1, 1, 0, 'on', 1, ''),
(22, 6, 100, 1, 1, 0, 'on', 1, ''),
(29, 4, 1, 5, 3, 0, 'on', 7, ''),
(30, 4, 2, 5, 3, 0, 'on', 7, ''),
(31, 4, 3, 5, 3, 0, 'on', 7, ''),
(32, 4, 4, 5, 3, 0, 'on', 7, ''),
(34, 4, 100, 5, 3, 0, 'on', 7, ''),
(35, 3, 1, 5, 2, 0, 'on', 9, ''),
(36, 3, 2, 5, 2, 0, 'on', 9, ''),
(37, 3, 3, 5, 2, 0, 'on', 9, ''),
(38, 3, 4, 5, 2, 0, 'on', 9, ''),
(40, 3, 100, 5, 2, 0, 'on', 9, ''),
(45, 14, 100, 3, 1, 0, 'on', 0, ''),
(61, 1, 1, 5, 1, 0, 'on', 8, ''),
(62, 1, 2, 5, 1, 0, 'on', 8, ''),
(63, 1, 3, 5, 1, 0, 'on', 8, ''),
(64, 1, 4, 5, 1, 0, 'on', 8, ''),
(66, 1, 100, 5, 1, 0, 'on', 8, ''),
(67, 1, 5, 5, 1, 0, 'on', 8, ''),
(1016, 1, 1, 3, 2, 0, 'on', 0, ''),
(1017, 14, 5, 3, 1, 0, 'on', 0, ''),
(1025, 19, 4, 3, 1, 0, 'on', 0, ''),
(1026, 14, 4, 1025, 1, 0, 'on', 0, ''),
(1027, 1, 4, 1025, 2, 0, 'on', 0, ''),
(1030, 8, 4, 1025, 5, 0, 'on', 0, ''),
(1031, 8, 4, 1025, 6, 0, 'on', 0, ''),
(1033, 6, 5, 1, 1, 0, 'on', 0, ''),
(1037, 4, 5, 5, 3, 0, 'on', 7, ''),
(1038, 3, 5, 5, 2, 0, 'on', 9, ''),
(1039, 1, 5, 5, 1, 0, 'on', 0, ''),
(1051, 14, 3, 3, 1, 0, 'on', 0, ''),
(1064, 14, 2, 3, 1, 0, 'on', 0, ''),
(1065, 14, 1, 3, 1, 0, 'on', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `mod_accounts_customer`
--

DROP TABLE IF EXISTS `mod_accounts_customer`;
CREATE TABLE IF NOT EXISTS `mod_accounts_customer` (
  `customer_id` int(11) NOT NULL auto_increment,
  `seq` int(11) NOT NULL,
  `username` varchar(20) collate latin1_general_ci NOT NULL,
  `password` varchar(50) collate latin1_general_ci NOT NULL,
  `first_name` varchar(30) collate latin1_general_ci default NULL,
  `last_name` varchar(30) collate latin1_general_ci default NULL,
  `company` varchar(255) collate latin1_general_ci default NULL,
  `email_1` varchar(255) collate latin1_general_ci default NULL,
  `email_2` varchar(255) collate latin1_general_ci default NULL,
  `phone_1` varchar(32) collate latin1_general_ci default NULL,
  `phone_2` varchar(32) collate latin1_general_ci default NULL,
  `phone_3` varchar(32) collate latin1_general_ci default NULL,
  `fax` varchar(32) collate latin1_general_ci default NULL,
  `country` varchar(30) collate latin1_general_ci default NULL,
  `pcode` varchar(30) collate latin1_general_ci default NULL,
  `state` varchar(30) collate latin1_general_ci default NULL,
  `city` varchar(30) collate latin1_general_ci default NULL,
  `address_1` varchar(255) collate latin1_general_ci default NULL,
  `address_2` varchar(255) collate latin1_general_ci default NULL,
  `post_country` varchar(32) collate latin1_general_ci default NULL,
  `post_pcode` varchar(32) collate latin1_general_ci default NULL,
  `post_state` varchar(32) collate latin1_general_ci default NULL,
  `post_city` varchar(32) collate latin1_general_ci default NULL,
  `post_address_1` varchar(255) collate latin1_general_ci default NULL,
  `post_address_2` varchar(255) collate latin1_general_ci default NULL,
  `bill_country` varchar(30) collate latin1_general_ci default NULL,
  `bill_pcode` varchar(30) collate latin1_general_ci default NULL,
  `bill_state` varchar(30) collate latin1_general_ci default NULL,
  `bill_city` varchar(30) collate latin1_general_ci default NULL,
  `bill_address_1` varchar(255) collate latin1_general_ci default NULL,
  `bill_address_2` varchar(255) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`customer_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_accounts_customer`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_accounts_invoice`
--

DROP TABLE IF EXISTS `mod_accounts_invoice`;
CREATE TABLE IF NOT EXISTS `mod_accounts_invoice` (
  `invoice_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `seq` int(11) NOT NULL,
  `date` date NOT NULL,
  `auto_date` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `banner_logo` varchar(255) collate latin1_general_ci default NULL,
  `company_name` text collate latin1_general_ci,
  `company_slogan` text collate latin1_general_ci,
  `company_abn` varchar(32) collate latin1_general_ci default NULL,
  `company_email` varchar(255) collate latin1_general_ci default NULL,
  `company_phone` varchar(32) collate latin1_general_ci default NULL,
  `company_fax` varchar(32) collate latin1_general_ci default NULL,
  `company_address` text collate latin1_general_ci,
  `to_name` text collate latin1_general_ci,
  `to_company` text collate latin1_general_ci,
  `to_address` text collate latin1_general_ci,
  `post_name` text collate latin1_general_ci,
  `post_company` text collate latin1_general_ci,
  `post_address` text collate latin1_general_ci,
  `sales_tax_perc` float NOT NULL,
  `sales_tax_label` varchar(32) collate latin1_general_ci default NULL,
  `text_1` longtext collate latin1_general_ci,
  `text_2` longtext collate latin1_general_ci,
  `pay_received_msg` text collate latin1_general_ci NOT NULL,
  `payment_method_1` text collate latin1_general_ci,
  `payment_method_2` text collate latin1_general_ci,
  `payment_method_3` text collate latin1_general_ci,
  `display_paypal_link` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `text_footer` varchar(255) collate latin1_general_ci default NULL,
  `amount_payed` float NOT NULL,
  `email_invoice_to` varchar(255) collate latin1_general_ci default NULL,
  `unique_num` int(11) NOT NULL,
  `invoiced_dates` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`invoice_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mod_accounts_invoice`
--

INSERT INTO `mod_accounts_invoice` (`invoice_id`, `customer_id`, `seq`, `date`, `auto_date`, `banner_logo`, `company_name`, `company_slogan`, `company_abn`, `company_email`, `company_phone`, `company_fax`, `company_address`, `to_name`, `to_company`, `to_address`, `post_name`, `post_company`, `post_address`, `sales_tax_perc`, `sales_tax_label`, `text_1`, `text_2`, `pay_received_msg`, `payment_method_1`, `payment_method_2`, `payment_method_3`, `display_paypal_link`, `text_footer`, `amount_payed`, `email_invoice_to`, `unique_num`, `invoiced_dates`) VALUES
(1, 0, 1, '0000-00-00', 'on', 'http:///_images_user/_invoice_banner.jpg', '', NULL, 'ABN: ', '', '', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 10, 'GST', 'sample text 1', 'sample text 2', 'Full Payment Received - Thank You !', '<strong>Direct Transfer:</strong>\r\nPayee Name: \r\nBSB: \r\nAccount N#: ', '<strong>Cheque:</strong> please make all cheques out to: ', NULL, 'on', 'Thank you for your business !', 0, NULL, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `mod_add2cart_button`
--

DROP TABLE IF EXISTS `mod_add2cart_button`;
CREATE TABLE IF NOT EXISTS `mod_add2cart_button` (
  `mod_id` int(11) NOT NULL,
  `button_name` varchar(255) collate latin1_general_ci NOT NULL,
  `display_text` longtext collate latin1_general_ci NOT NULL,
  `prod_id` int(11) NOT NULL,
  `image_file` varchar(255) collate latin1_general_ci NOT NULL,
  `title_text` longtext collate latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `mod_add2cart_button`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_brochure_asign`
--

DROP TABLE IF EXISTS `mod_brochure_asign`;
CREATE TABLE IF NOT EXISTS `mod_brochure_asign` (
  `item_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `seq` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_brochure_asign`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_brochure_cats`
--

DROP TABLE IF EXISTS `mod_brochure_cats`;
CREATE TABLE IF NOT EXISTS `mod_brochure_cats` (
  `mod_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL auto_increment,
  `cat_name` varchar(255) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  `seq` int(11) NOT NULL,
  `display_name` set('on','') NOT NULL default 'on',
  `description` text NOT NULL,
  PRIMARY KEY  (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_brochure_cats`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_brochure_items`
--

DROP TABLE IF EXISTS `mod_brochure_items`;
CREATE TABLE IF NOT EXISTS `mod_brochure_items` (
  `item_id` int(11) NOT NULL auto_increment,
  `mod_id` int(11) NOT NULL,
  `item_name` varchar(255) collate latin1_general_ci NOT NULL,
  `heading` varchar(255) collate latin1_general_ci NOT NULL,
  `text` longtext collate latin1_general_ci NOT NULL,
  `image_file` varchar(255) collate latin1_general_ci default NULL,
  `active` set('on','') collate latin1_general_ci default 'on',
  `cat_id` int(11) NOT NULL default '0',
  `seq` int(11) NOT NULL,
  `image_caption` longtext collate latin1_general_ci,
  `image_title` longtext collate latin1_general_ci,
  `image_alt_text` longtext collate latin1_general_ci,
  `image_click` set('none','link','new_win','colorbox') collate latin1_general_ci NOT NULL default 'none',
  `image_href` varchar(255) collate latin1_general_ci default NULL,
  `image_href_target` set('parent','new_win','colorbox') collate latin1_general_ci default 'parent',
  PRIMARY KEY  (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_brochure_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_brochure_settings`
--

DROP TABLE IF EXISTS `mod_brochure_settings`;
CREATE TABLE IF NOT EXISTS `mod_brochure_settings` (
  `mod_id` int(11) NOT NULL,
  `brochure_name` varchar(255) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  `layout` set('1_col','2_col','grid') NOT NULL default '1_col',
  `heading` varchar(255) NOT NULL,
  `item_alias` varchar(255) NOT NULL default 'Item',
  `can_select_cat` set('on','') NOT NULL default 'on',
  `default_cat` int(11) NOT NULL default '0',
  `select_all` set('on','') NOT NULL default 'on',
  `select_cat_text` varchar(255) NOT NULL,
  `max_chrs_display` int(5) NOT NULL default '300',
  `max_items_per_page` int(5) NOT NULL default '15',
  PRIMARY KEY  (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_brochure_settings`
--

INSERT INTO `mod_brochure_settings` (`mod_id`, `brochure_name`, `active`, `layout`, `heading`, `item_alias`, `can_select_cat`, `default_cat`, `select_all`, `select_cat_text`, `max_chrs_display`, `max_items_per_page`) VALUES
(1109, 'Work examples', 'on', '1_col', 'Examples of our work:', 'Work example', 'on', 1, 'on', 'Choose a Category:', 300, 15);

-- --------------------------------------------------------

--
-- Table structure for table `mod_captcha`
--

DROP TABLE IF EXISTS `mod_captcha`;
CREATE TABLE IF NOT EXISTS `mod_captcha` (
  `captcha_id` int(11) NOT NULL auto_increment,
  `width` int(5) NOT NULL default '120',
  `height` int(5) NOT NULL default '30',
  `num_chrs` int(1) NOT NULL default '5',
  `num_lines` int(3) NOT NULL default '0',
  `font_file` varchar(50) NOT NULL default 'Arial_Black_36_bold.gdf',
  `css_selector` varchar(50) NOT NULL default 'ImageCaptcha',
  `default_bg_color` varchar(6) NOT NULL default 'cccccc',
  `default_font_color` varchar(6) NOT NULL default '5555ff',
  `show_label` set('on','') NOT NULL default 'on',
  `show_new_code_link` set('on','') NOT NULL default 'on',
  `show_help_link` set('on','') NOT NULL default 'on',
  `label` varchar(255) NOT NULL default 'Security code:',
  `explain_text` longtext NOT NULL,
  PRIMARY KEY  (`captcha_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mod_captcha`
--

INSERT INTO `mod_captcha` (`captcha_id`, `width`, `height`, `num_chrs`, `num_lines`, `font_file`, `css_selector`, `default_bg_color`, `default_font_color`, `show_label`, `show_new_code_link`, `show_help_link`, `label`, `explain_text`) VALUES
(1, 100, 25, 5, 0, 'Arial_Black_36_bold.gdf', 'ImageCaptcha', 'cccccc', '5555ff', '', '', '', 'Security code:', 'This challenge question is used to prevent automated software from performing malicious practice on this form, such as spamming.\r\n\r\nBy entering this code correctly, you are proving that you are a legitimate user of this site, that is, you are human and not a computer.\r\n\r\nIf you are having trouble reading the letters in the image, you can get another code by clicking the &quot;Get New Code&quot; button. ');

-- --------------------------------------------------------

--
-- Table structure for table `mod_contact_form`
--

DROP TABLE IF EXISTS `mod_contact_form`;
CREATE TABLE IF NOT EXISTS `mod_contact_form` (
  `mod_id` int(11) NOT NULL auto_increment,
  `form_name` varchar(100) collate latin1_general_ci NOT NULL,
  `active` set('on','') collate latin1_general_ci NOT NULL,
  `email_to` varchar(255) collate latin1_general_ci default NULL,
  `email_from` varchar(255) collate latin1_general_ci NOT NULL,
  `time_zone` varchar(100) collate latin1_general_ci default NULL,
  `auto_reply` set('on','') collate latin1_general_ci default NULL,
  `heading` varchar(255) collate latin1_general_ci default NULL,
  `text_1` longtext collate latin1_general_ci,
  `text_2` longtext collate latin1_general_ci,
  `confirm_msg_display` longtext collate latin1_general_ci,
  `auto_reply_msg` longtext collate latin1_general_ci,
  `send_email_subject` longtext collate latin1_general_ci,
  `error_msg` varchar(255) collate latin1_general_ci default 'ERROR: Your Message could not be sent',
  `show_reqd_field_label` set('on','') collate latin1_general_ci NOT NULL default 'on',
  PRIMARY KEY  (`mod_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `mod_contact_form`
--

INSERT INTO `mod_contact_form` (`mod_id`, `form_name`, `active`, `email_to`, `email_from`, `time_zone`, `auto_reply`, `heading`, `text_1`, `text_2`, `confirm_msg_display`, `auto_reply_msg`, `send_email_subject`, `error_msg`, `show_reqd_field_label`) VALUES
(9, 'contact us', 'on', 'matt@siteofhand.com.au', 'matt@siteofhand.com.au', 'Australia/Sydney', 'on', 'Quick Contact:', '', '', 'Thank You, your message has been sent', 'Your message has been received, thank you', 'Message from your website', 'ERROR: Your Message could not be sent', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `mod_contact_form_elements`
--

DROP TABLE IF EXISTS `mod_contact_form_elements`;
CREATE TABLE IF NOT EXISTS `mod_contact_form_elements` (
  `element_id` int(11) NOT NULL auto_increment,
  `mod_id` int(11) NOT NULL,
  `name` varchar(50) collate latin1_general_ci NOT NULL,
  `element` varchar(30) collate latin1_general_ci NOT NULL,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `seq` int(11) default '0',
  `type` varchar(255) collate latin1_general_ci default NULL,
  `value` longtext collate latin1_general_ci,
  `label` longtext collate latin1_general_ci,
  `title` longtext collate latin1_general_ci,
  `required` set('on','') collate latin1_general_ci NOT NULL,
  `width` int(3) default '30',
  `height` int(3) default '1',
  `include_in_email` set('on','') collate latin1_general_ci default 'on',
  `confirm_msg_readback` set('on','') collate latin1_general_ci default NULL,
  `send_label` varchar(255) collate latin1_general_ci default NULL,
  `error_msg` varchar(255) collate latin1_general_ci default NULL,
  `attrib_1` varchar(255) collate latin1_general_ci default NULL,
  `attrib_2` varchar(255) collate latin1_general_ci default NULL,
  `attrib_3` varchar(255) collate latin1_general_ci default NULL,
  `attrib_4` varchar(255) collate latin1_general_ci default NULL,
  `attrib_5` varchar(255) collate latin1_general_ci default NULL,
  `rule_1` varchar(255) collate latin1_general_ci default NULL,
  `rule_2` varchar(255) collate latin1_general_ci default NULL,
  `rule_3` varchar(255) collate latin1_general_ci default NULL,
  `rule_4` varchar(255) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`element_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=123 ;

--
-- Dumping data for table `mod_contact_form_elements`
--

INSERT INTO `mod_contact_form_elements` (`element_id`, `mod_id`, `name`, `element`, `active`, `seq`, `type`, `value`, `label`, `title`, `required`, `width`, `height`, `include_in_email`, `confirm_msg_readback`, `send_label`, `error_msg`, `attrib_1`, `attrib_2`, `attrib_3`, `attrib_4`, `attrib_5`, `rule_1`, `rule_2`, `rule_3`, `rule_4`) VALUES
(92, 9, 'Name', 'text', 'on', 10, 'text', 'Name', '', 'please enter your name here', 'on', 30, 0, 'on', 'on', 'Name:', 'Please supply your name', 'on', NULL, NULL, NULL, NULL, '', 'minchrs_2', 'maxchrs_32', ''),
(93, 9, 'Email', 'email', 'on', 20, 'text', 'Email', '', 'Please enter a valid email that we can use to contact you here', 'on', 30, 0, 'on', 'on', 'Email:', 'Please provide us with a valid email address', 'on', NULL, NULL, NULL, NULL, 'email', 'email_filter', 'ip_filter', NULL),
(94, 9, 'message', 'textarea', 'on', 30, NULL, 'Message', '', 'Please enter your inquiry here', 'on', 40, 3, 'on', 'on', 'Message:', 'Please type in your message / enquiry', 'on', 'on', 'on', '', '', '', 'minchrs_9', '', NULL),
(95, 9, 'Security code', 'captcha', 'on', 40, 'text', '', 'Enter Code: ', 'Enter the numbers and letters you see here - ( letters a-z )', 'on', 5, 0, '', NULL, NULL, 'The Letters you entered were incorrect', '1', '', '', '', NULL, NULL, NULL, NULL, NULL),
(96, 9, 'Submit Button 1', 'submit', 'on', 50, 'submit', 'Send Message', NULL, 'Click here to send off your message', '', 30, 1, '', NULL, NULL, 'Error Sending MSG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(122, 9, 'Submit Button 2', 'submit', 'on', 1, 'submit', NULL, NULL, NULL, '', 0, 0, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mod_contact_form_options`
--

DROP TABLE IF EXISTS `mod_contact_form_options`;
CREATE TABLE IF NOT EXISTS `mod_contact_form_options` (
  `id` int(11) NOT NULL auto_increment,
  `element_id` int(11) NOT NULL,
  `seq` int(11) NOT NULL,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `display_text` varchar(100) collate latin1_general_ci NOT NULL,
  `value_text` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_contact_form_options`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_contact_groups`
--

DROP TABLE IF EXISTS `mod_contact_groups`;
CREATE TABLE IF NOT EXISTS `mod_contact_groups` (
  `group_id` int(11) NOT NULL auto_increment,
  `mod_id` int(11) NOT NULL,
  `name` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `active` set('on','') character set latin1 collate latin1_general_ci NOT NULL default 'on',
  `seq` int(11) NOT NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_contact_groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_contact_group_asign`
--

DROP TABLE IF EXISTS `mod_contact_group_asign`;
CREATE TABLE IF NOT EXISTS `mod_contact_group_asign` (
  `contact_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_contact_group_asign`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_contact_items`
--

DROP TABLE IF EXISTS `mod_contact_items`;
CREATE TABLE IF NOT EXISTS `mod_contact_items` (
  `contact_id` int(11) NOT NULL auto_increment,
  `mod_id` int(11) NOT NULL default '0',
  `name` varchar(255) character set latin1 collate latin1_general_ci default NULL,
  `role` varchar(255) character set latin1 collate latin1_general_ci default NULL,
  `phone_1` varchar(50) character set latin1 collate latin1_general_ci default NULL,
  `phone_2` varchar(50) character set latin1 collate latin1_general_ci default NULL,
  `phone_3` varchar(50) character set latin1 collate latin1_general_ci default NULL,
  `fax` varchar(50) character set latin1 collate latin1_general_ci default NULL,
  `email` varchar(100) character set latin1 collate latin1_general_ci default NULL,
  `display_email` set('','text','img') character set latin1 collate latin1_general_ci NOT NULL default 'img',
  `link_email` set('','mailto','form') character set latin1 collate latin1_general_ci NOT NULL default 'form',
  `misc_label_1` varchar(255) character set latin1 collate latin1_general_ci default NULL,
  `misc_label_2` varchar(255) character set latin1 collate latin1_general_ci default NULL,
  `misc_label_3` varchar(255) character set latin1 collate latin1_general_cs default NULL,
  `misc_1` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `misc_2` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `misc_3` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `image` varchar(255) character set latin1 collate latin1_general_ci default NULL,
  `comment` longtext character set latin1 collate latin1_general_ci,
  `seq` int(11) default '0',
  `active` set('on','') character set latin1 collate latin1_general_ci NOT NULL default 'on',
  PRIMARY KEY  (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_contact_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_contact_settings`
--

DROP TABLE IF EXISTS `mod_contact_settings`;
CREATE TABLE IF NOT EXISTS `mod_contact_settings` (
  `mod_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL default '0',
  `info_in_new_win` set('on','') character set latin1 collate latin1_general_ci NOT NULL,
  `display_group_name` set('on','') character set latin1 collate latin1_general_ci NOT NULL,
  `seq` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_contact_settings`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_div`
--

DROP TABLE IF EXISTS `mod_div`;
CREATE TABLE IF NOT EXISTS `mod_div` (
  `mod_id` int(11) NOT NULL,
  `apply_jquery` set('','open','drag') collate latin1_general_ci NOT NULL default '',
  `description` varchar(255) collate latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `mod_div`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_event_list`
--

DROP TABLE IF EXISTS `mod_event_list`;
CREATE TABLE IF NOT EXISTS `mod_event_list` (
  `mod_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL auto_increment,
  `seq` int(5) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  `locked` set('on','') NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  `more_info_on` set('on','') NOT NULL,
  `display_fields_in_more_info` set('on','') NOT NULL,
  `long_desc` longtext NOT NULL,
  `field_1` varchar(255) NOT NULL,
  `field_2` varchar(255) NOT NULL,
  `field_3` varchar(255) NOT NULL,
  `field_4` varchar(255) NOT NULL,
  `field_5` varchar(255) NOT NULL,
  `active_start` datetime NOT NULL,
  `active_end` datetime NOT NULL,
  `auto_expire_on` set('no','on','') NOT NULL default 'no',
  `auto_expire_time` int(11) NOT NULL,
  `auto_expire_unit` int(11) NOT NULL,
  PRIMARY KEY  (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_event_list`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_event_list_config`
--

DROP TABLE IF EXISTS `mod_event_list_config`;
CREATE TABLE IF NOT EXISTS `mod_event_list_config` (
  `mod_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `event_alias` varchar(50) NOT NULL default 'Event',
  `heading` varchar(255) NOT NULL,
  `text_1` longtext NOT NULL,
  `text_2` longtext NOT NULL,
  `more_info_text` varchar(255) NOT NULL default 'more info...',
  `no_events_msg` text NOT NULL,
  `display_heads` set('on','') NOT NULL,
  `display_by` varchar(50) NOT NULL default 'time',
  `display_image` set('on','') NOT NULL default 'on',
  `display_date` set('on','') NOT NULL default 'on',
  `display_time` set('on','') NOT NULL default 'on',
  `time_zone` varchar(100) NOT NULL default 'Australia/Sydney',
  `date_format` varchar(20) NOT NULL,
  `time_format` varchar(20) NOT NULL default 'g:ia',
  `field_active_1` set('on','') NOT NULL default 'on',
  `field_active_2` set('on','') NOT NULL default 'on',
  `field_active_3` set('on','') NOT NULL default 'on',
  `field_active_4` set('on','') NOT NULL default 'on',
  `field_active_5` set('on','') NOT NULL default 'on',
  `field_head_1` varchar(255) NOT NULL,
  `field_head_2` varchar(255) NOT NULL,
  `field_head_3` varchar(255) NOT NULL,
  `field_head_4` varchar(255) NOT NULL,
  `field_head_5` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_event_list_config`
--

INSERT INTO `mod_event_list_config` (`mod_id`, `name`, `event_alias`, `heading`, `text_1`, `text_2`, `more_info_text`, `no_events_msg`, `display_heads`, `display_by`, `display_image`, `display_date`, `display_time`, `time_zone`, `date_format`, `time_format`, `field_active_1`, `field_active_2`, `field_active_3`, `field_active_4`, `field_active_5`, `field_head_1`, `field_head_2`, `field_head_3`, `field_head_4`, `field_head_5`) VALUES
(1095, 'Workshops', 'Workshop', '', '', 'Please click on the Workshop for the full details of these workshops', 'more info...', 'There are currently no Workshops scheduled, please check back later...', 'on', 'seq', '', '', '', 'Australia/Sydney', 'D, j M Y', 'G:i', 'on', 'on', 'on', '', '', 'Date', 'Time', 'Cost', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `mod_flash_player`
--

DROP TABLE IF EXISTS `mod_flash_player`;
CREATE TABLE IF NOT EXISTS `mod_flash_player` (
  `mod_id` int(11) NOT NULL,
  `name` varchar(50) character set latin1 collate latin1_general_ci NOT NULL default 'flash movie',
  `file_name` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `file_query_str` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `xml_file` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `width` varchar(50) NOT NULL,
  `height` varchar(50) NOT NULL,
  `defult_bg_img` varchar(255) default NULL,
  `quality` varchar(50) character set latin1 collate latin1_general_ci NOT NULL default 'high',
  `param_1` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `param_2` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `param_3` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `param_4` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `param_value_1` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `param_value_2` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `param_value_3` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `param_value_4` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_flash_player`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_google_map`
--

DROP TABLE IF EXISTS `mod_google_map`;
CREATE TABLE IF NOT EXISTS `mod_google_map` (
  `mod_id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL,
  `map_name` varchar(255) collate latin1_general_ci default NULL,
  `heading` text collate latin1_general_ci,
  `text_1` longtext collate latin1_general_ci,
  `text_2` longtext collate latin1_general_ci,
  `active` set('on','') collate latin1_general_ci default 'on',
  `link_to_window` set('on','') collate latin1_general_ci default NULL,
  `new_link_text` varchar(255) collate latin1_general_ci default 'Click here to View Location Map in a new window',
  `new_link_thumb` varchar(255) collate latin1_general_ci NOT NULL,
  `centre_lat` double NOT NULL,
  `centre_long` double NOT NULL,
  `zoom_level` int(11) NOT NULL,
  `map_width` int(11) default NULL,
  `map_height` int(11) default NULL,
  `directions_address` longtext collate latin1_general_ci,
  `get_directions` set('on','') collate latin1_general_ci default 'on',
  `get_dir_in_new_win` set('on','') collate latin1_general_ci default 'on',
  PRIMARY KEY  (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_google_map`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_google_map_markers`
--

DROP TABLE IF EXISTS `mod_google_map_markers`;
CREATE TABLE IF NOT EXISTS `mod_google_map_markers` (
  `marker_id` int(11) NOT NULL auto_increment,
  `mod_id` int(11) NOT NULL,
  `marker_name` varchar(255) collate latin1_general_ci default NULL,
  `active` set('on','') collate latin1_general_ci default 'on',
  `marker_lat` double NOT NULL,
  `marker_long` double NOT NULL,
  `map_icon_file` varchar(255) collate latin1_general_ci default 'map_marker.png',
  `map_icon_shadow_file` varchar(255) collate latin1_general_ci default 'map_marker_shadow_60.png',
  `map_icon_width` int(4) NOT NULL default '63',
  `map_icon_height` varchar(4) collate latin1_general_ci NOT NULL default '53',
  `show_info_on_open` set('on','') collate latin1_general_ci NOT NULL,
  `map_info_heading` longtext collate latin1_general_ci,
  `map_info_text` longtext collate latin1_general_ci,
  `map_info_address` longtext collate latin1_general_ci,
  PRIMARY KEY  (`marker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_google_map_markers`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_heading`
--

DROP TABLE IF EXISTS `mod_heading`;
CREATE TABLE IF NOT EXISTS `mod_heading` (
  `mod_id` int(11) NOT NULL auto_increment,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `text` longtext collate latin1_general_ci NOT NULL,
  `heading_element` set('h1','h2','h3','h4','h5','h6') collate latin1_general_ci NOT NULL default 'h1',
  `locked` set('on','') collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`mod_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1066 ;

--
-- Dumping data for table `mod_heading`
--

INSERT INTO `mod_heading` (`mod_id`, `active`, `text`, `heading_element`, `locked`) VALUES
(45, 'on', 'Search this site:', 'h1', ''),
(1017, 'on', 'Page 5&nbsp;', 'h1', ''),
(1026, 'on', 'Contact Us', 'h1', ''),
(1051, 'on', 'About&nbsp;Us', 'h1', ''),
(1064, 'on', 'Services We Offer', 'h1', ''),
(1065, 'on', 'About Us&nbsp;', 'h1', '');

-- --------------------------------------------------------

--
-- Table structure for table `mod_image_1`
--

DROP TABLE IF EXISTS `mod_image_1`;
CREATE TABLE IF NOT EXISTS `mod_image_1` (
  `mod_id` int(2) NOT NULL,
  `image_file` varchar(255) collate latin1_general_ci default NULL,
  `active` set('on','') collate latin1_general_ci default 'on',
  `image_caption` longtext collate latin1_general_ci,
  `image_title` longtext collate latin1_general_ci,
  `image_alt_text` longtext collate latin1_general_ci,
  `image_click` set('none','link','new_win','colorbox') collate latin1_general_ci NOT NULL default 'none',
  `image_href` varchar(255) collate latin1_general_ci default NULL,
  `image_href_target` set('parent','new_win','colorbox') collate latin1_general_ci default 'parent',
  PRIMARY KEY  (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `mod_image_1`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_insert_file`
--

DROP TABLE IF EXISTS `mod_insert_file`;
CREATE TABLE IF NOT EXISTS `mod_insert_file` (
  `mod_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `file_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`mod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_insert_file`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_insert_html`
--

DROP TABLE IF EXISTS `mod_insert_html`;
CREATE TABLE IF NOT EXISTS `mod_insert_html` (
  `mod_id` int(11) NOT NULL auto_increment,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `seq` int(11) NOT NULL,
  `head_or_body` set('head','body') collate latin1_general_ci NOT NULL default 'body',
  `code` longtext collate latin1_general_ci NOT NULL,
  UNIQUE KEY `mod_id` (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_insert_html`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_invoice_items`
--

DROP TABLE IF EXISTS `mod_invoice_items`;
CREATE TABLE IF NOT EXISTS `mod_invoice_items` (
  `item_id` int(11) NOT NULL auto_increment,
  `invoice_id` int(11) NOT NULL,
  `seq` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `item_code` varchar(255) collate latin1_general_ci default NULL,
  `desc` varchar(255) collate latin1_general_ci default NULL,
  `unit_price` float NOT NULL,
  `discount` float default NULL,
  PRIMARY KEY  (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_invoice_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_invoice_services`
--

DROP TABLE IF EXISTS `mod_invoice_services`;
CREATE TABLE IF NOT EXISTS `mod_invoice_services` (
  `service_id` int(11) NOT NULL auto_increment,
  `seq` int(11) NOT NULL,
  `service_code` varchar(255) collate latin1_general_ci default NULL,
  `service_desc` varchar(255) collate latin1_general_ci default NULL,
  `unit_price` float default NULL,
  PRIMARY KEY  (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_invoice_services`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_links`
--

DROP TABLE IF EXISTS `mod_links`;
CREATE TABLE IF NOT EXISTS `mod_links` (
  `mod_id` int(11) NOT NULL,
  `active` set('on','') character set latin1 collate latin1_general_ci NOT NULL default 'on',
  `url` varchar(255) character set latin1 collate latin1_general_ci NOT NULL,
  `display_url` longtext character set latin1 collate latin1_general_ci,
  `image_file` varchar(255) character set latin1 collate latin1_general_ci default NULL,
  `new_window` set('on','') character set latin1 collate latin1_general_ci NOT NULL default 'on',
  `title` mediumtext character set latin1 collate latin1_general_ci,
  `alt_text` longtext character set latin1 collate latin1_general_ci,
  UNIQUE KEY `mod_id` (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_links`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_list_items`
--

DROP TABLE IF EXISTS `mod_list_items`;
CREATE TABLE IF NOT EXISTS `mod_list_items` (
  `mod_id` int(11) NOT NULL,
  `li_id` int(11) NOT NULL auto_increment,
  `seq` int(11) default NULL,
  `style` set('ul','ol') collate latin1_general_ci default 'ul',
  `text` text collate latin1_general_ci,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `locked` set('on','') collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`li_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_list_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_page_redirect`
--

DROP TABLE IF EXISTS `mod_page_redirect`;
CREATE TABLE IF NOT EXISTS `mod_page_redirect` (
  `mod_id` int(11) NOT NULL auto_increment,
  `to_url` varchar(255) NOT NULL,
  `type` set('html','php') NOT NULL default 'html',
  `delay` int(11) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY  (`mod_id`),
  KEY `id` (`mod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_page_redirect`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_photo_gal_asign`
--

DROP TABLE IF EXISTS `mod_photo_gal_asign`;
CREATE TABLE IF NOT EXISTS `mod_photo_gal_asign` (
  `photo_id` int(11) NOT NULL,
  `mod_id` int(11) NOT NULL,
  `gal_cat_id` int(11) NOT NULL,
  `seq` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_photo_gal_asign`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_photo_gal_cats`
--

DROP TABLE IF EXISTS `mod_photo_gal_cats`;
CREATE TABLE IF NOT EXISTS `mod_photo_gal_cats` (
  `gal_cat_id` int(11) NOT NULL auto_increment,
  `cat_name` varchar(255) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  `seq` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`gal_cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_photo_gal_cats`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_photo_gal_pics`
--

DROP TABLE IF EXISTS `mod_photo_gal_pics`;
CREATE TABLE IF NOT EXISTS `mod_photo_gal_pics` (
  `photo_id` int(11) NOT NULL auto_increment,
  `file_name` varchar(255) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  `photo_title_text` text NOT NULL,
  `photo_alt_text` text NOT NULL,
  PRIMARY KEY  (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_photo_gal_pics`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_photo_gal_settings`
--

DROP TABLE IF EXISTS `mod_photo_gal_settings`;
CREATE TABLE IF NOT EXISTS `mod_photo_gal_settings` (
  `mod_id` int(11) NOT NULL,
  `gallery_name` varchar(255) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  `gallery_type` varchar(255) NOT NULL,
  `display_name` set('on','') NOT NULL default 'on',
  `trans_fx` varchar(255) NOT NULL default 'fade',
  `trans_speed` int(11) NOT NULL default '2000',
  `timeout` int(11) NOT NULL default '4000',
  `pause_on_hover` set('1','0') NOT NULL default '0',
  PRIMARY KEY  (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_photo_gal_settings`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_profiles`
--

DROP TABLE IF EXISTS `mod_profiles`;
CREATE TABLE IF NOT EXISTS `mod_profiles` (
  `profile_id` int(11) NOT NULL auto_increment,
  `mod_id` int(11) NOT NULL default '0',
  `profile_name` varchar(255) character set latin1 collate latin1_general_ci default NULL,
  `role` varchar(255) character set latin1 collate latin1_general_ci default NULL,
  `display_contact_info` set('on','') NOT NULL default 'on',
  `phone_1` varchar(50) character set latin1 collate latin1_general_ci default NULL,
  `phone_2` varchar(50) character set latin1 collate latin1_general_ci default NULL,
  `phone_3` varchar(50) character set latin1 collate latin1_general_ci default NULL,
  `fax` varchar(50) character set latin1 collate latin1_general_ci default NULL,
  `email` varchar(100) character set latin1 collate latin1_general_ci default NULL,
  `website_url` varchar(255) NOT NULL,
  `website_display` varchar(255) NOT NULL,
  `display_email_as` set('','text','img') character set latin1 collate latin1_general_ci NOT NULL default 'img',
  `link_email` set('','mailto','form') character set latin1 collate latin1_general_ci NOT NULL default 'form',
  `primary_image_id` int(11) NOT NULL,
  `profile_img_file` varchar(50) NOT NULL,
  `profile_as_primary` set('on','') NOT NULL,
  `display_profile_img` set('on','') NOT NULL,
  `display_images` set('on','') character set latin1 collate latin1_general_ci default 'on',
  `can_enlarge_imgs` set('on','') NOT NULL default 'on',
  `long_desc` longtext character set latin1 collate latin1_general_ci,
  `seq` int(11) default '0',
  `active` set('on','') character set latin1 collate latin1_general_ci NOT NULL default 'on',
  `url_alias` varchar(255) NOT NULL,
  PRIMARY KEY  (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_profiles`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_profiles_config`
--

DROP TABLE IF EXISTS `mod_profiles_config`;
CREATE TABLE IF NOT EXISTS `mod_profiles_config` (
  `mod_id` int(11) NOT NULL,
  `listing_name` varchar(50) NOT NULL,
  `profile_alias` varchar(50) NOT NULL default 'Profile',
  `heading` varchar(255) NOT NULL,
  `text_1` longtext NOT NULL,
  `text_2` longtext NOT NULL,
  `display_thumbs` set('on','') NOT NULL default 'on',
  `link_2_all_imgs` set('on','') NOT NULL,
  `all_thumbs_link` set('colorbox','profile') NOT NULL default 'colorbox',
  `navbar_location` int(1) NOT NULL default '1',
  `use_url_alias` set('on','') NOT NULL default 'on',
  `resize_img_mode` set('0','1','2','3','4','5') NOT NULL default '5',
  `resize_img_max_width` int(5) NOT NULL,
  `resize_img_max_height` int(5) NOT NULL,
  `img_thumb_max_width` int(5) NOT NULL,
  `img_thumb_max_height` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_profiles_config`
--

INSERT INTO `mod_profiles_config` (`mod_id`, `listing_name`, `profile_alias`, `heading`, `text_1`, `text_2`, `display_thumbs`, `link_2_all_imgs`, `all_thumbs_link`, `navbar_location`, `use_url_alias`, `resize_img_mode`, `resize_img_max_width`, `resize_img_max_height`, `img_thumb_max_width`, `img_thumb_max_height`) VALUES
(9999, 'Artists Profiles', 'Artist Profile', '', '', '', 'on', 'on', 'profile', 1, 'on', '5', 700, 500, 100, 100);

-- --------------------------------------------------------

--
-- Table structure for table `mod_profile_images`
--

DROP TABLE IF EXISTS `mod_profile_images`;
CREATE TABLE IF NOT EXISTS `mod_profile_images` (
  `mod_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL,
  `image_file_name` varchar(16) NOT NULL,
  `img_caption` text NOT NULL,
  `seq` int(11) NOT NULL default '1',
  `active` set('on','') NOT NULL default 'on',
  PRIMARY KEY  (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_profile_images`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_search_fields`
--

DROP TABLE IF EXISTS `mod_search_fields`;
CREATE TABLE IF NOT EXISTS `mod_search_fields` (
  `id` int(11) NOT NULL auto_increment,
  `table_name` varchar(100) collate latin1_general_ci default NULL,
  `field_name` varchar(100) collate latin1_general_ci default NULL,
  `active` set('on','') collate latin1_general_ci default 'on',
  `seq` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=31 ;

--
-- Dumping data for table `mod_search_fields`
--

INSERT INTO `mod_search_fields` (`id`, `table_name`, `field_name`, `active`, `seq`) VALUES
(3, 'mod_text_1', 'text', 'on', 20),
(4, 'mod_list_items', 'text', 'on', 30),
(5, 'mod_heading', 'text', 'on', 10),
(7, 'mod_website_portfolio', 'name', 'on', 100),
(8, 'mod_website_portfolio', 'display_url', 'on', 101),
(9, 'mod_website_portfolio', 'short_text', 'on', 102),
(10, 'mod_website_portfolio', 'long_text', 'on', 103),
(11, 'mod_website_portfolio', 'feature_1', 'on', 104),
(12, 'mod_website_portfolio', 'feature_2', 'on', 105),
(13, 'mod_website_portfolio', 'feature_3', 'on', 106),
(14, 'mod_website_portfolio', 'feature_4', 'on', 107),
(16, 'mod_contact_form_elements', 'label', 'on', 72),
(17, 'mod_contact_form', 'heading', 'on', 70),
(18, 'mod_contact_form', 'text_1', 'on', 71),
(19, 'mod_contact_form', 'text_2', 'on', 75),
(20, 'mod_image_1', 'image_caption', 'on', 50),
(22, 'mod_google_map', 'heading', 'on', 80),
(23, 'mod_google_map', 'text_1', 'on', 81),
(24, 'mod_google_map', 'text_2', 'on', 82),
(25, 'mod_contact_items', 'name', 'on', 62),
(26, 'mod_contact_items', 'role', 'on', 61),
(27, 'mod_contact_groups', 'name', 'on', 60),
(28, 'mod_links', 'display_url', 'on', 40),
(29, 'shop_items', 'item_name', 'on', 0),
(30, 'shop_items', 'description', 'on', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mod_table_config`
--

DROP TABLE IF EXISTS `mod_table_config`;
CREATE TABLE IF NOT EXISTS `mod_table_config` (
  `mod_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `num_cols` int(4) NOT NULL,
  `num_rows` int(11) NOT NULL,
  `order_by` varchar(255) NOT NULL default 'seq',
  `heading` varchar(255) NOT NULL,
  `text_1` text NOT NULL,
  `text_2` text NOT NULL,
  PRIMARY KEY  (`mod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_table_config`
--

INSERT INTO `mod_table_config` (`mod_id`, `name`, `num_cols`, `num_rows`, `order_by`, `heading`, `text_1`, `text_2`) VALUES
(9999, 'Committee', 4, 0, 'seq', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `mod_table_data`
--

DROP TABLE IF EXISTS `mod_table_data`;
CREATE TABLE IF NOT EXISTS `mod_table_data` (
  `mod_id` int(11) NOT NULL,
  `row_id` int(11) NOT NULL auto_increment,
  `row_type` set('th','td') NOT NULL default 'td',
  `display` set('on','') NOT NULL default 'on',
  `seq` int(11) NOT NULL,
  `col_1` text NOT NULL,
  `col_2` text NOT NULL,
  `col_3` text NOT NULL,
  `col_4` text NOT NULL,
  `col_5` text NOT NULL,
  `col_6` text NOT NULL,
  `col_7` text NOT NULL,
  `col_8` text NOT NULL,
  `col_9` text NOT NULL,
  `merged` varchar(20) NOT NULL,
  PRIMARY KEY  (`row_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_table_data`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_text_1`
--

DROP TABLE IF EXISTS `mod_text_1`;
CREATE TABLE IF NOT EXISTS `mod_text_1` (
  `mod_id` int(2) NOT NULL,
  `text` longtext collate latin1_general_ci,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `locked` set('on','') collate latin1_general_ci NOT NULL default '',
  `align` set('',' AlignLeft',' AlignRight',' AlignCenter',' AlignJustify') collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `mod_text_1`
--

INSERT INTO `mod_text_1` (`mod_id`, `text`, `active`, `locked`, `align`) VALUES
(61, 'Copyright &#169; 2012&nbsp;', 'on', '', ''),
(62, 'Copyright &#169; 2012&nbsp;', 'on', '', ''),
(63, 'Copyright &#169; 2012&nbsp;', 'on', '', ''),
(64, 'Copyright &#169; 2012&nbsp;', 'on', '', ''),
(66, 'Copyright &#169; 2012&nbsp;', 'on', '', ''),
(67, 'Copyright &#169; 2012&nbsp;', 'on', '', ''),
(1016, '<span id=ModData_1016 class=UpdateMe > \r\n<p>Morbi eu sem a sapien iaculisbhb ornare vel eu augue. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc imperdiet pulvinar diam et suscipit. Suspendisse et ante vel odio commodo posuere. Donec vhe MMMMM el ultricies nisl. Fusce vitae neque nunc. In libero turpis, interdum ut sollicitudin non, varius ut velit. </p>\r\n<p>Nulla convallis, felis in pretium ullamcorper, lectus neque vulputate diam, vitae consequat mauris diam nec dolor. Vivamus sed faucibus risus.In vehicula vestibulum velit rutrum faucibus. Integer est nisi, commodo sed gravida in, fringilla at massa. Aliquam justo ipsum, accumsan sed lacinia at, sollicitudin id turpis. Pellentesque sagittis egestas condimentum. Curabitur gravida magna vel eros placerat tempor. Aliquam eget ultricies leo. Quisque vitae vestibulum elit. </p></span>&nbsp;', 'on', '', ''),
(1027, '<p>Feel free to contact us with any questions that you may have:</p>\n<p>&nbsp;</p>', 'on', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `mod_text_2_image`
--

DROP TABLE IF EXISTS `mod_text_2_image`;
CREATE TABLE IF NOT EXISTS `mod_text_2_image` (
  `mod_id` int(2) NOT NULL,
  `text` longtext collate latin1_general_ci,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `from_css` set('on','') collate latin1_general_ci default NULL,
  `id_or_class` set('id','class') collate latin1_general_ci NOT NULL default 'id',
  `link_type` set('','link','email') collate latin1_general_ci NOT NULL default '',
  `bg_colour` varchar(6) collate latin1_general_ci default NULL,
  `font_colour` varchar(6) collate latin1_general_ci default NULL,
  `font_size` int(6) default NULL,
  `font_name` varchar(32) collate latin1_general_ci default NULL,
  `font_style` varchar(32) collate latin1_general_ci default 'normal',
  `link_href` varchar(255) collate latin1_general_ci NOT NULL,
  `new_window` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `title_text` longtext collate latin1_general_ci NOT NULL,
  `alt_text` longtext collate latin1_general_ci NOT NULL,
  `contact_id` int(11) NOT NULL,
  `display_text_in_form` set('on','') collate latin1_general_ci NOT NULL default 'on',
  PRIMARY KEY  (`mod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `mod_text_2_image`
--

INSERT INTO `mod_text_2_image` (`mod_id`, `text`, `active`, `from_css`, `id_or_class`, `link_type`, `bg_colour`, `font_colour`, `font_size`, `font_name`, `font_style`, `link_href`, `new_window`, `title_text`, `alt_text`, `contact_id`, `display_text_in_form`) VALUES
(1030, 'P: 02 9525 9999', 'on', NULL, 'id', '', NULL, NULL, NULL, NULL, 'normal', '', 'on', '', '', 0, 'on'),
(1031, 'E: info@newsite.com', 'on', NULL, 'id', '', NULL, NULL, NULL, NULL, 'normal', '', 'on', '', '', 0, 'on');

-- --------------------------------------------------------

--
-- Table structure for table `mod_website_portfolio`
--

DROP TABLE IF EXISTS `mod_website_portfolio`;
CREATE TABLE IF NOT EXISTS `mod_website_portfolio` (
  `mod_id` int(11) NOT NULL,
  `site_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate latin1_general_ci NOT NULL,
  `url` varchar(1000) collate latin1_general_ci NOT NULL,
  `display_url` varchar(1000) collate latin1_general_ci NOT NULL,
  `seq` int(11) NOT NULL,
  `active` set('on','') collate latin1_general_ci NOT NULL,
  `cat_id` int(11) NOT NULL,
  `more_info_set` set('on','') collate latin1_general_ci NOT NULL,
  `link_to_site` set('on','') collate latin1_general_ci default 'on',
  `link_in_new_window` set('on','') collate latin1_general_ci default 'on',
  `short_text` text collate latin1_general_ci NOT NULL,
  `long_text` longtext collate latin1_general_ci NOT NULL,
  `feature_1` varchar(1000) collate latin1_general_ci NOT NULL,
  `feature_2` varchar(1000) collate latin1_general_ci NOT NULL,
  `feature_3` varchar(1000) collate latin1_general_ci NOT NULL,
  `feature_4` varchar(1000) collate latin1_general_ci NOT NULL,
  `feature_5` varchar(1000) collate latin1_general_ci NOT NULL,
  `feature_6` varchar(1000) collate latin1_general_ci NOT NULL,
  `feature_7` varchar(1000) collate latin1_general_ci NOT NULL,
  `feature_8` varchar(1000) collate latin1_general_ci NOT NULL,
  `image_file` varchar(255) collate latin1_general_ci NOT NULL,
  `image_file_thumb` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_website_portfolio`
--


-- --------------------------------------------------------

--
-- Table structure for table `mod_web_port_cats`
--

DROP TABLE IF EXISTS `mod_web_port_cats`;
CREATE TABLE IF NOT EXISTS `mod_web_port_cats` (
  `cat_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate latin1_general_ci default NULL,
  `active` set('on','') collate latin1_general_ci default 'on',
  `seq` int(11) default NULL,
  `description` varchar(1000) collate latin1_general_ci default NULL,
  PRIMARY KEY  (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mod_web_port_cats`
--


-- --------------------------------------------------------

--
-- Table structure for table `page_info`
--

DROP TABLE IF EXISTS `page_info`;
CREATE TABLE IF NOT EXISTS `page_info` (
  `page_id` int(11) NOT NULL auto_increment,
  `page_name` varchar(50) collate latin1_general_ci NOT NULL,
  `auto_heading` set('name','menu','') collate latin1_general_ci NOT NULL default '',
  `menu_text` varchar(64) collate latin1_general_ci default NULL,
  `url_alias` varchar(64) collate latin1_general_ci default NULL,
  `active` set('on','') collate latin1_general_ci default 'on',
  `requires_login` set('on','') collate latin1_general_ci default NULL,
  `access_code` int(3) default '10',
  `seq` int(11) default NULL,
  `priority` int(2) NOT NULL default '5',
  `include_in_sitemap` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `parent_id` int(11) default '0',
  `file_name` varchar(255) collate latin1_general_ci default NULL,
  `send_p_query` set('on','') collate latin1_general_ci default 'on',
  `a_tag_attrib` varchar(255) collate latin1_general_ci NOT NULL,
  `icon_image` varchar(255) collate latin1_general_ci default NULL,
  `in_menu_top` set('on','') collate latin1_general_ci default 'on',
  `in_menu_foot` set('on','') collate latin1_general_ci default NULL,
  `in_menu_side` set('on','') collate latin1_general_ci default 'on',
  `menu_top_active` set('on','') collate latin1_general_ci default NULL,
  `menu_foot_active` set('on','') collate latin1_general_ci default NULL,
  `menu_side_active` set('on','') collate latin1_general_ci default NULL,
  `bread_crumb_active` set('on','') collate latin1_general_ci default 'on',
  `popup_text` varchar(255) collate latin1_general_ci default NULL,
  `banner_active` set('on','') collate latin1_general_ci default 'on',
  `footer_active` set('on','') collate latin1_general_ci default 'on',
  `side_1_active` set('on','') collate latin1_general_ci default NULL,
  `side_2_active` set('on','') collate latin1_general_ci default NULL,
  `sync_banner` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `sync_footer` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `sync_side_1` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `sync_side_2` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `cms_comments` longtext collate latin1_general_ci,
  PRIMARY KEY  (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=101 ;

--
-- Dumping data for table `page_info`
--

INSERT INTO `page_info` (`page_id`, `page_name`, `auto_heading`, `menu_text`, `url_alias`, `active`, `requires_login`, `access_code`, `seq`, `priority`, `include_in_sitemap`, `parent_id`, `file_name`, `send_p_query`, `a_tag_attrib`, `icon_image`, `in_menu_top`, `in_menu_foot`, `in_menu_side`, `menu_top_active`, `menu_foot_active`, `menu_side_active`, `bread_crumb_active`, `popup_text`, `banner_active`, `footer_active`, `side_1_active`, `side_2_active`, `sync_banner`, `sync_footer`, `sync_side_1`, `sync_side_2`, `cms_comments`) VALUES
(1, 'Home', '', 'Home', 'home', 'on', '', 10, 2, 10, 'on', 0, 'home', '', '', NULL, 'on', 'on', 'on', 'on', 'on', '', 'on', 'Return to the ''Home'' Page', 'on', 'on', '', '', 'on', 'on', 'on', 'on', 'This is the home page'),
(2, 'services', '', 'Services', 'services', 'on', '', 10, 4, 5, 'on', 0, 'services', '', '', NULL, 'on', 'on', 'on', 'on', 'on', '', 'on', '', 'on', 'on', '', '', 'on', 'on', 'on', 'on', ''),
(3, 'about us', '', 'About Us', 'about', 'on', '', 10, 6, 5, 'on', 0, 'about', '', '', NULL, 'on', 'on', 'on', 'on', 'on', '', 'on', '', 'on', 'on', '', '', 'on', 'on', 'on', 'on', ''),
(4, 'Contact Page', '', 'Contact Us', 'contact', 'on', '', 10, 10, 8, 'on', 0, 'contact', '', '', NULL, 'on', 'on', 'on', 'on', 'on', '', 'on', 'Information on Contacting Us', 'on', 'on', '', '', 'on', 'on', 'on', 'on', ''),
(5, 'Page 5', '', 'Page 5', 'page', 'on', '', 10, 8, 8, 'on', 0, 'page', '', '', NULL, 'on', 'on', 'on', 'on', 'on', '', '', 'Information on the various Workshops available', 'on', 'on', '', '', 'on', 'on', 'on', 'on', ''),
(100, 'Search Results', '', 'Search', 'search', 'on', '', 10, 12, 5, '', 0, 'search', '', '', NULL, '', '', '', 'on', 'on', '', 'on', 'Search for text on this site', 'on', 'on', '', '', 'on', 'on', 'on', 'on', 'This is the sites default search results page and must stay ACTIVE for any search features to work - no content needs to be added here but a ''search Box'' is desirable');

-- --------------------------------------------------------

--
-- Table structure for table `shop_address_countries`
--

DROP TABLE IF EXISTS `shop_address_countries`;
CREATE TABLE IF NOT EXISTS `shop_address_countries` (
  `country_id` int(11) NOT NULL auto_increment,
  `country_name` varchar(50) NOT NULL,
  `country_code` varchar(10) NOT NULL,
  `has_states` set('on','') NOT NULL,
  `state_required` set('on','') NOT NULL,
  `state_or_prov` set('State','Province','County','State/Prov') NOT NULL,
  `has_postcodes` set('on','') NOT NULL default 'on',
  `postcode_required` set('on','') NOT NULL,
  `zip_or_post` set('Postcode','Zipcode','Post/Zip Code') NOT NULL default 'Post/Zip Code',
  `pcode_min_length` int(2) NOT NULL default '3',
  `pcode_max_length` int(2) NOT NULL default '12',
  `postcode_only_num` set('numbers','numbers and letters') NOT NULL default 'numbers and letters',
  `seq` int(11) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  PRIMARY KEY  (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1229 ;

--
-- Dumping data for table `shop_address_countries`
--

INSERT INTO `shop_address_countries` (`country_id`, `country_name`, `country_code`, `has_states`, `state_required`, `state_or_prov`, `has_postcodes`, `postcode_required`, `zip_or_post`, `pcode_min_length`, `pcode_max_length`, `postcode_only_num`, `seq`, `active`) VALUES
(1, 'Australia', 'AUS', 'on', 'on', 'State', 'on', 'on', 'Postcode', 4, 4, 'numbers', 1000, 'on'),
(2, 'United States of America', 'US', 'on', 'on', 'State', 'on', 'on', 'Zipcode', 3, 10, 'numbers', 1000, 'on'),
(3, 'United Kingdom', 'GB', 'on', 'on', 'County', 'on', 'on', 'Postcode', 5, 7, 'numbers and letters', 1000, 'on'),
(4, 'New Zealand', 'NZ', '', '', 'State/Prov', 'on', '', 'Postcode', 4, 4, 'numbers', 1000, 'on'),
(5, 'Canada', 'CAN', 'on', 'on', 'Province', 'on', 'on', 'Postcode', 6, 7, 'numbers and letters', 1000, 'on'),
(1000, 'Aland Islands', 'AX', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1001, 'Albania', 'AL', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1002, 'Algeria', 'DZ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1003, 'American Samoa', 'AS', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1004, 'Andorra', 'AD', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1005, 'Angola', 'AO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1006, 'Anguilla', 'AI', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1007, 'Antarctica', 'AQ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1008, 'Antigua and Barbuda', 'AG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1009, 'Argentina', 'AR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1010, 'Armenia', 'AM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1011, 'Aruba', 'AW', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1012, 'Austria', 'AT', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1013, 'Azerbaijan', 'AZ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1014, 'Bahamas', 'BS', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1015, 'Bahrain', 'BH', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1016, 'Bangladesh', 'BD', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1017, 'Barbados', 'BB', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1018, 'Belarus', 'BY', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1019, 'Belgium', 'BE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1020, 'Belize', 'BZ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1021, 'Benin', 'BJ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1022, 'Bermuda', 'BM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1023, 'Bhutan', 'BT', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1024, 'Bolivia, Plurinational State of', 'BO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1025, 'Bonaire, Sint Eustatius and Saba', 'BQ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1026, 'Bosnia and Herzegovina', 'BA', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1027, 'Botswana', 'BW', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1028, 'Bouvet Island', 'BV', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1029, 'Brazil', 'BR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1030, 'British Indian Ocean Territory', 'IO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1031, 'Brunei Darussalam', 'BN', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1032, 'Bulgaria', 'BG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1033, 'Burkina Faso', 'BF', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1034, 'Burundi', 'BI', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1035, 'Cambodia', 'KH', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1036, 'Cameroon', 'CM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1037, 'Cape Verde', 'CV', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1038, 'Cayman Islands', 'KY', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1039, 'Central African Republic', 'CF', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1040, 'Chad', 'TD', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1041, 'Chile', 'CL', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1042, 'China', 'CN', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1043, 'Christmas Island', 'CX', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1044, 'Cocos (Keeling) Islands', 'CC', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1045, 'Colombia', 'CO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1046, 'Comoros', 'KM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1047, 'Congo', 'CG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1048, 'Congo, The Democratic Republic of the', 'CD', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1049, 'Cook Islands', 'CK', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1050, 'Costa Rica', 'CR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1051, 'Croatia', 'HR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1052, 'Curacao', 'CW', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1053, 'Cyprus', 'CY', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1054, 'Czech Republic', 'CZ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1055, 'Denmark', 'DK', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1056, 'Djibouti', 'DJ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1057, 'Dominica', 'DM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1058, 'Dominican Republic', 'DO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1059, 'Ecuador', 'EC', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1060, 'Egypt', 'EG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1061, 'El Salvador', 'SV', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1062, 'Equatorial Guineav', 'GQ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1063, 'Estonia', 'EE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1064, 'Ethiopia', 'ET', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1065, 'Falkland Islands (Malvinas)', ' FK', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1066, 'Faroe Islands', 'FO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1067, 'Fiji', 'FJ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1068, 'Finland', ' FI', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1069, 'France', 'FR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1070, 'French Guiana', ' GF', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1071, 'French Polynesia', 'PF', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1072, 'French Southern Territories', 'TF', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1073, 'Gabon', 'GA', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1074, 'Gambia', 'GM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1075, 'Georgia', 'GE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1076, 'Germany', 'DE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1077, 'Ghana', 'GH', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1078, 'Gibraltar', 'GI', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1079, 'Greece', 'GR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1080, 'Greenland', 'GL', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1081, 'Grenada', ' GD', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1082, 'Guadeloupe', ' GP', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1083, 'Guam', 'GU', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1084, 'Guatemala', 'GT', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1085, 'Guernsey', 'GG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1086, 'Guinea-Bissau', 'GW', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1087, 'Guyana', 'GY', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1088, 'Haiti', 'HT', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1089, 'Heard Island and McDonald Islands', 'HM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1090, 'Holy See (Vatican City State)', 'VA', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1091, 'Honduras', 'HN', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1092, 'Hong Kong', 'HK', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1093, 'Hungary', 'HU', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1094, 'Iceland', 'IS', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1095, 'India', 'IN', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1096, 'Indonesia', 'ID', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1097, 'Ireland', 'IE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1098, 'Isle of Man', 'IM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1099, 'Israel', 'IL', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1100, 'Italy', 'IT', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1101, 'Jamaica', 'JM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1102, 'Japan', 'JP', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1103, 'Jersey', ' JE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1104, 'Jordan', 'JO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1105, 'Kazakhstan', 'KZ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1106, 'Kenya', 'KE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1107, 'Kiribati', ' KI', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1108, 'Korea, Republic of', 'KR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1109, 'Kuwait', 'KW', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1110, 'Kyrgyzstan', 'KG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1111, 'Lao Peoples Democratic Republic', 'LA', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1112, 'Latvia', 'LV', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1113, 'Lebanon', 'LB', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1114, 'Lesotho', 'LS', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1115, 'Libyan Arab Jamahiriya', 'LY', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1116, 'Liechtenstein', 'LI', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1117, 'Lithuania', ' LT', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1118, 'Luxembourg', ' LU', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1119, 'Macao', ' MO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1120, 'Macedonia, The Former Yugoslav Republic of', ' MK', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1121, 'Madagascar', ' MG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1122, 'Malawi', 'MW', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1123, 'Malaysia', 'MY', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1124, 'Maldives', 'MV', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1125, 'Mali', 'ML', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1126, 'Malta', 'MT', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1127, 'Marshall Islands', 'MH', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1128, 'Martinique', 'MQ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1129, 'Mauritania', 'MR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1130, 'Mauritius', 'MU', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1131, 'Mayotte', 'YT', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1132, 'Mexico', 'MX', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1133, 'Micronesia, Federated States of', 'FM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1134, 'Moldova, Republic of', 'MD', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1135, 'Monaco', 'MC', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1136, 'Mongolia', 'MN', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1137, 'Montenegro', 'ME', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1138, 'Montserrat', 'MS', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1139, 'Morocco', 'MA', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1140, 'Mozambique', 'MZ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1141, 'Namibia', 'NA', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1142, 'Nauru', 'NR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1143, 'Nepal', 'NP', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1144, 'Netherlands', 'NL', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1145, 'Netherlands Antilles', 'AN', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1146, 'New Caledonia', 'NC', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1147, 'Nicaragua', 'NI', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1148, 'Niger', 'NE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1149, 'Nigeria', 'NG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1150, 'Niue', 'NU', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1151, 'Norfolk Island', 'NF', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1152, 'Northern Mariana Islands', 'MP', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1153, 'Norway', 'NO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1154, 'Oman', 'OM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1155, 'Pakistan', 'PK', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1156, 'Palau', 'W', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1157, 'Palestinian Territory, Occupied', 'PS', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1158, 'Panama', 'PA', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1159, 'Papua New Guinea', 'PG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1160, 'Paraguay', 'PY', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1161, 'Peru', 'PE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1162, 'Philippines', 'PH', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1163, 'Pitcairn', 'PN', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1164, 'Poland', 'PL', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1165, 'Portugal', 'PT', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1166, 'Puerto Rico', 'PR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1167, 'Qatar', 'QA', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1168, 'Reunion', ' RE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1169, 'Romania', 'RO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1170, 'Russian Federation', 'RU', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1171, 'Saint Barthelemy', 'BL', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1172, 'Saint Helena, Ascension and Tristan Da Cunha', 'SH', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1173, 'Saint Kitts and Nevis', 'KN', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1174, 'Saint Lucia', 'LC', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1175, 'Saint Martin (French Part)', 'MF', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1176, 'Saint Pierre and Miquelon', 'PM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1177, 'Saint Vincent and The Grenadines', 'VC', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1178, 'Samoa', 'WS', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1179, 'San Marino', 'SM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1180, 'Sao Tome and Principe', 'ST', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1181, 'Saudi Arabia', 'SA', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1182, 'Senegal', 'SN', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1183, 'Serbia', 'RS', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1184, 'Seychelles', 'SC', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1185, 'Singapore', 'SG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1186, 'Sint Maarten (Dutch part)', 'SX', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1187, 'Slovakia', 'SK', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1188, 'Slovenia', 'SI', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1189, 'Solomon Islands', 'SB', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1190, 'Somalia', 'SO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1191, 'South Africa', 'ZA', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1192, 'South Georgia and The South Sandwich Islands', 'GS', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1193, 'Spain', 'ES', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1194, 'Sri Lanka', 'LK', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1195, 'Suriname', 'SR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1196, 'Svalbard and Jan Mayen', 'SJ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1197, 'Swaziland', 'SZ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1198, 'Sweden', 'SE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1199, 'Switzerland', 'CH', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1200, 'Tajikistan', ' TJ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1201, 'Tanzania, United Republic of', 'TZ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1202, 'Thailand', 'TH', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1203, 'Timor-Leste', 'TL', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1204, 'Togo', 'TG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1205, 'Tokelau', ' TK', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1206, 'Tonga', 'TO', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1207, 'Trinidad and Tobago', 'TT', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1208, 'Tunisia', 'TN', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1209, 'Turkey', 'TR', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1210, 'Turkmenistan', ' TM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1211, 'Turks and Caicos Islands', 'TC', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1212, 'Tuvalu', ' TV', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1213, 'Uganda', 'UG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1214, 'Ukraine', 'UA', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1215, 'United Arab Emirates', 'AE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1216, 'United States Minor Outlying Islands', 'UM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1217, 'Uruguay', 'UY', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1218, 'Uzbekistan', 'UZ', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1219, 'Vanuatu', ' VU', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1220, 'Venezuela, Bolivarian Republic of', ' VE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1221, 'Vietnam', ' VN', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1222, 'Virgin Islands, British', ' VG', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1223, 'Virgin Islands, U.S.', 'VI', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1224, 'Wallis and Futuna', ' WF', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1225, 'Western Sahara', ' EH', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1226, 'Yemen', 'YE', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1227, 'Zambia', 'ZM', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on'),
(1228, 'Zimbabwe', 'ZW', 'on', '', 'State/Prov', 'on', '', 'Post/Zip Code', 3, 12, 'numbers and letters', 1000, 'on');

-- --------------------------------------------------------

--
-- Table structure for table `shop_address_states`
--

DROP TABLE IF EXISTS `shop_address_states`;
CREATE TABLE IF NOT EXISTS `shop_address_states` (
  `state_id` int(11) NOT NULL auto_increment,
  `state_name` varchar(50) NOT NULL,
  `state_display_name` varchar(50) NOT NULL,
  `country_id` int(11) NOT NULL,
  `seq` int(11) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  PRIMARY KEY  (`state_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=348 ;

--
-- Dumping data for table `shop_address_states`
--

INSERT INTO `shop_address_states` (`state_id`, `state_name`, `state_display_name`, `country_id`, `seq`, `active`) VALUES
(1, 'New South Wales', 'NSW', 1, 1, 'on'),
(2, 'Victoria', 'VIC', 1, 2, 'on'),
(3, 'Queensland', 'QLD', 1, 3, 'on'),
(4, 'South Australia', 'SA', 1, 4, 'on'),
(5, 'Western Australia', 'WA', 1, 5, 'on'),
(6, 'Northern Territory', 'NT', 1, 6, 'on'),
(7, 'Tasmania', 'TAS', 1, 7, 'on'),
(8, 'ACT', 'ACT', 1, 8, 'on'),
(320, 'Oklahoma', 'OK', 2, 37, 'on'),
(321, 'Oregon', 'OR', 2, 38, 'on'),
(322, 'Pennsylvania', 'PA', 2, 39, 'on'),
(323, 'Rhode Island', 'RI', 2, 40, 'on'),
(319, 'Ohio', 'OH', 2, 36, 'on'),
(318, 'North Dakota', 'ND', 2, 35, 'on'),
(317, 'North Carolina', 'NC', 2, 34, 'on'),
(316, 'New York', 'NY', 2, 33, 'on'),
(315, 'New Mexico', 'NM', 2, 32, 'on'),
(314, 'New Jersey', 'NJ', 2, 31, 'on'),
(313, 'New Hampshire', 'NH', 2, 30, 'on'),
(312, 'Nevada', 'NV', 2, 29, 'on'),
(311, 'Nebraska', 'NE', 2, 28, 'on'),
(310, 'Montana', 'MT', 2, 27, 'on'),
(309, 'Missouri', 'MO', 2, 26, 'on'),
(302, 'Louisiana', 'LA', 2, 19, 'on'),
(301, 'Kentucky', 'KY', 2, 18, 'on'),
(300, 'Kansas', 'KS', 2, 17, 'on'),
(299, 'Iowa', 'IA', 2, 16, 'on'),
(298, 'Indiana', 'IN', 2, 15, 'on'),
(297, 'Illinois', 'IL', 2, 14, 'on'),
(296, 'Idaho', 'ID', 2, 13, 'on'),
(295, 'Hawaii', 'HI', 2, 12, 'on'),
(294, 'Georgia', 'GA', 2, 11, 'on'),
(293, 'Florida', 'FL', 2, 10, 'on'),
(292, 'District Of Columbia', 'DC', 2, 9, 'on'),
(291, 'Delaware', 'DE', 2, 8, 'on'),
(290, 'Connecticut', 'CT', 2, 7, 'on'),
(289, 'Colorado', 'CO', 2, 6, 'on'),
(288, 'California', 'CA', 2, 5, 'on'),
(287, 'Arkansas', 'AR', 2, 4, 'on'),
(286, 'Arizona', 'AZ', 2, 3, 'on'),
(285, 'Alaska', 'AK', 2, 2, 'on'),
(284, 'Alabama', 'AL', 2, 1, 'on'),
(308, 'Mississippi', 'MS', 2, 25, 'on'),
(307, 'Minnesota', 'MN', 2, 24, 'on'),
(306, 'Michigan', 'MI', 2, 23, 'on'),
(305, 'Massachusetts', 'MA', 2, 22, 'on'),
(304, 'Maryland', 'MD', 2, 21, 'on'),
(303, 'Maine', 'ME', 2, 20, 'on'),
(324, 'South Carolina', 'SC', 2, 41, 'on'),
(325, 'South Dakota', 'SD', 2, 42, 'on'),
(326, 'Tennessee', 'TN', 2, 43, 'on'),
(327, 'Texas', 'TX', 2, 44, 'on'),
(328, 'Utah', 'UT', 2, 45, 'on'),
(329, 'Vermont', 'VT', 2, 46, 'on'),
(330, 'Virginia', 'VA', 2, 47, 'on'),
(331, 'Washington', 'WA', 2, 48, 'on'),
(332, 'West Virginia', 'WV', 2, 49, 'on'),
(333, 'Wisconsin', 'WI', 2, 50, 'on'),
(334, 'Wyoming', 'WY', 2, 51, 'on'),
(335, 'Ontario', 'ON', 5, 9, 'on'),
(336, 'Quebec', 'QC', 5, 11, 'on'),
(337, 'Nova Scotia', 'NS', 5, 7, 'on'),
(338, 'New Brunswick', 'NB', 5, 4, 'on'),
(339, 'Manitoba', 'MB', 5, 3, 'on'),
(340, 'British Columbia', 'BC ', 5, 2, 'on'),
(341, 'Prince Edward Island', 'PE', 5, 10, 'on'),
(342, 'Saskatchewan', 'SK', 5, 12, 'on'),
(343, 'Alberta', 'AB', 5, 1, 'on'),
(344, 'Newfoundland', 'NL', 5, 5, 'on'),
(345, 'Northwest Territories', 'NT', 5, 6, 'on'),
(346, 'Yukon', 'YT', 5, 13, 'on'),
(347, 'Nunavut', 'NU', 5, 8, 'on');

-- --------------------------------------------------------

--
-- Table structure for table `shop_categories`
--

DROP TABLE IF EXISTS `shop_categories`;
CREATE TABLE IF NOT EXISTS `shop_categories` (
  `cat_id` int(11) NOT NULL auto_increment,
  `cat_name` varchar(255) collate latin1_general_ci default NULL,
  `seq` int(11) default NULL,
  `parent_id` int(11) default NULL,
  `products_count` int(11) default NULL,
  `description` text collate latin1_general_ci,
  `image_file` varchar(30) collate latin1_general_ci default NULL,
  `products_count_admin` int(11) default NULL,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  PRIMARY KEY  (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_cat_asign`
--

DROP TABLE IF EXISTS `shop_cat_asign`;
CREATE TABLE IF NOT EXISTS `shop_cat_asign` (
  `prod_id` int(11) NOT NULL auto_increment,
  `cat_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY  (`prod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_cat_asign`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_cc_cardtypes`
--

DROP TABLE IF EXISTS `shop_cc_cardtypes`;
CREATE TABLE IF NOT EXISTS `shop_cc_cardtypes` (
  `cardtype_id` int(11) NOT NULL,
  `pay_method_id` int(11) NOT NULL,
  `cardtype_name` varchar(32) NOT NULL,
  `active` set('on','') NOT NULL default 'on'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shop_cc_cardtypes`
--

INSERT INTO `shop_cc_cardtypes` (`cardtype_id`, `pay_method_id`, `cardtype_name`, `active`) VALUES
(1, 1, 'MasterCard', 'on'),
(2, 1, 'Visa', 'on'),
(3, 1, 'Amex', ''),
(4, 1, 'Diners Club', 'on'),
(5, 4, 'MasterCard', 'on'),
(6, 4, 'Visa', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `shop_items`
--

DROP TABLE IF EXISTS `shop_items`;
CREATE TABLE IF NOT EXISTS `shop_items` (
  `item_id` int(11) NOT NULL auto_increment,
  `mod_id` int(11) NOT NULL,
  `item_name` varchar(255) collate latin1_general_ci default NULL,
  `url_alias` varchar(255) collate latin1_general_ci NOT NULL,
  `seq` int(11) default NULL,
  `active` set('on','') collate latin1_general_ci default 'on',
  `primary_cat_id` int(11) NOT NULL,
  `price` float default NULL,
  `list_price` float default NULL,
  `promo_price_1` float default NULL,
  `promo_price_2` float default NULL,
  `promo_price_3` float default NULL,
  `promo_price_4` float default NULL,
  `promo_code_1` varchar(32) collate latin1_general_ci default NULL,
  `promo_code_2` varchar(32) collate latin1_general_ci default NULL,
  `promo_code_3` varchar(32) collate latin1_general_ci default NULL,
  `promo_code_4` varchar(32) collate latin1_general_ci default NULL,
  `image_file` varchar(30) collate latin1_general_ci default NULL,
  `thumb_image` varchar(30) collate latin1_general_ci default NULL,
  `display_buynow` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `display_rating` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `display_instock` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `display_ship_measures` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `rating` float default NULL,
  `votes` int(11) default NULL,
  `in_stock` int(11) default '5',
  `items_sold` int(11) default NULL,
  `max_quantity_allow` int(11) NOT NULL default '100',
  `brief` text collate latin1_general_ci,
  `description` text collate latin1_general_ci,
  `product_code` char(25) collate latin1_general_ci default NULL,
  `ship_item_quant_value` float NOT NULL default '1',
  `ship_weight_kg` float default '0.4',
  `ship_length_mm` int(11) default NULL,
  `ship_width_mm` int(11) default NULL,
  `ship_height_mm` int(11) default NULL,
  `ship_total_amount` float default NULL,
  `ship_add_amount` float default NULL,
  PRIMARY KEY  (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_ordered_carts`
--

DROP TABLE IF EXISTS `shop_ordered_carts`;
CREATE TABLE IF NOT EXISTS `shop_ordered_carts` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_name` varchar(255) collate latin1_general_ci default NULL,
  `product_code` int(11) NOT NULL,
  `discount` float default NULL,
  `price` float default NULL,
  `quantity` int(11) default NULL,
  PRIMARY KEY  (`item_id`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `shop_ordered_carts`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_orders`
--

DROP TABLE IF EXISTS `shop_orders`;
CREATE TABLE IF NOT EXISTS `shop_orders` (
  `order_id` int(11) NOT NULL auto_increment,
  `order_time` datetime default NULL,
  `cust_id` int(11) default NULL,
  `cust_email` varchar(255) collate latin1_general_ci NOT NULL,
  `ship_method` varchar(20) collate latin1_general_ci default NULL,
  `ship_cost` float default NULL,
  `paypal_form` longtext collate latin1_general_ci NOT NULL,
  `paid` set('on','') collate latin1_general_ci default NULL,
  PRIMARY KEY  (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `shop_orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `shop_pay_methods_set`
--

DROP TABLE IF EXISTS `shop_pay_methods_set`;
CREATE TABLE IF NOT EXISTS `shop_pay_methods_set` (
  `id` int(11) NOT NULL auto_increment,
  `pay_method_id` int(11) NOT NULL,
  `seq` int(11) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `shop_pay_methods_set`
--

INSERT INTO `shop_pay_methods_set` (`id`, `pay_method_id`, `seq`, `active`) VALUES
(1, 1, 1, 'on'),
(2, 3, 2, 'on'),
(3, 4, 1, 'on');

-- --------------------------------------------------------

--
-- Table structure for table `shop_ship_carriers`
--

DROP TABLE IF EXISTS `shop_ship_carriers`;
CREATE TABLE IF NOT EXISTS `shop_ship_carriers` (
  `carrier_id` int(11) NOT NULL auto_increment,
  `carrier_name` varchar(255) collate latin1_general_ci NOT NULL,
  `seq` int(11) NOT NULL,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `icon_image` varchar(255) collate latin1_general_ci NOT NULL,
  `post_calc_url` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`carrier_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `shop_ship_carriers`
--

INSERT INTO `shop_ship_carriers` (`carrier_id`, `carrier_name`, `seq`, `active`, `icon_image`, `post_calc_url`) VALUES
(1, 'Australia Post', 1, 'on', 'logo_austpost.gif', 'drc.edeliver.com.au/ratecalc.asp');

-- --------------------------------------------------------

--
-- Table structure for table `shop_ship_methods`
--

DROP TABLE IF EXISTS `shop_ship_methods`;
CREATE TABLE IF NOT EXISTS `shop_ship_methods` (
  `method_id` int(11) NOT NULL auto_increment,
  `carrier_id` int(11) default NULL,
  `method_name` varchar(255) collate latin1_general_ci default NULL,
  `method_code` varchar(255) collate latin1_general_ci NOT NULL,
  `seq` int(11) default NULL,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `comment` longtext collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`method_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `shop_ship_methods`
--

INSERT INTO `shop_ship_methods` (`method_id`, `carrier_id`, `method_name`, `method_code`, `seq`, `active`, `comment`) VALUES
(1, 1, 'Standard', 'standard', 1, 'on', 'estimated Delivery Time: 1-2 Business day '),
(2, 1, 'Express', 'express', 2, 'on', 'Delivery Time: Guaranteed Next Business Day');

-- --------------------------------------------------------

--
-- Table structure for table `sweeps_agents`
--

DROP TABLE IF EXISTS `sweeps_agents`;
CREATE TABLE IF NOT EXISTS `sweeps_agents` (
  `agent_id` int(11) NOT NULL auto_increment,
  `agent_name` varchar(50) NOT NULL,
  `agent_password` varchar(10) NOT NULL,
  `agent_contact_name` varchar(50) NOT NULL,
  `agent_email` varchar(50) NOT NULL,
  `agent_phone` varchar(20) NOT NULL,
  `agent_address_1` varchar(50) NOT NULL,
  `agent_address_2` varchar(50) NOT NULL,
  `agent_country` varchar(3) NOT NULL,
  `agent_state` varchar(3) NOT NULL,
  `agent_postcode` varchar(10) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  `pre_fill_checkout` set('on','') NOT NULL default 'on',
  `agent_link_code` varchar(50) NOT NULL,
  PRIMARY KEY  (`agent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sweeps_agents`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_categories`
--

DROP TABLE IF EXISTS `sweeps_categories`;
CREATE TABLE IF NOT EXISTS `sweeps_categories` (
  `cat_id` int(11) NOT NULL auto_increment,
  `cat_name` varchar(255) collate latin1_general_ci default NULL,
  `seq` int(11) default NULL,
  `parent_id` int(11) default '0',
  `description` text collate latin1_general_ci,
  `display_image` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `image_file` varchar(30) collate latin1_general_ci default NULL,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  PRIMARY KEY  (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sweeps_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_cat_asign`
--

DROP TABLE IF EXISTS `sweeps_cat_asign`;
CREATE TABLE IF NOT EXISTS `sweeps_cat_asign` (
  `prod_id` int(11) NOT NULL auto_increment,
  `cat_id` int(11) NOT NULL default '1',
  `item_id` int(11) NOT NULL,
  `seq` int(11) NOT NULL,
  PRIMARY KEY  (`prod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sweeps_cat_asign`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_export_data_fields`
--

DROP TABLE IF EXISTS `sweeps_export_data_fields`;
CREATE TABLE IF NOT EXISTS `sweeps_export_data_fields` (
  `profile_id` int(11) NOT NULL,
  `seq` int(11) NOT NULL,
  `fields_selected` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sweeps_export_data_fields`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_export_data_filters`
--

DROP TABLE IF EXISTS `sweeps_export_data_filters`;
CREATE TABLE IF NOT EXISTS `sweeps_export_data_filters` (
  `profile_id` int(11) NOT NULL,
  `filter_id` int(11) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  `filter_name` varchar(50) NOT NULL,
  `filter_operator` varchar(50) NOT NULL,
  `filter_data` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sweeps_export_data_filters`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_export_data_order_by`
--

DROP TABLE IF EXISTS `sweeps_export_data_order_by`;
CREATE TABLE IF NOT EXISTS `sweeps_export_data_order_by` (
  `profile_id` int(11) NOT NULL,
  `order_by_id` int(11) NOT NULL,
  `fields_order_by` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sweeps_export_data_order_by`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_export_data_profiles`
--

DROP TABLE IF EXISTS `sweeps_export_data_profiles`;
CREATE TABLE IF NOT EXISTS `sweeps_export_data_profiles` (
  `profile_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `category` set('Orders','Products') NOT NULL,
  `file_type` set('csv','txt','sql') NOT NULL,
  `file_name` varchar(50) NOT NULL,
  `filename_suffix` set('(none)','date (dd-mm-yy)','date (yy-mm-dd)','date (ddmmyy)','date (yymmdd)') NOT NULL,
  `print_headers` set('on','') NOT NULL default 'on',
  PRIMARY KEY  (`profile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sweeps_export_data_profiles`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_items`
--

DROP TABLE IF EXISTS `sweeps_items`;
CREATE TABLE IF NOT EXISTS `sweeps_items` (
  `item_id` int(11) NOT NULL auto_increment,
  `mod_id` int(11) NOT NULL,
  `item_name` varchar(255) collate latin1_general_ci default NULL,
  `url_alias` varchar(255) collate latin1_general_ci NOT NULL,
  `active` set('on','') collate latin1_general_ci default 'on',
  `primary_cat_id` int(11) NOT NULL default '1',
  `price` float default NULL,
  `display_list_price` set('on','') collate latin1_general_ci NOT NULL,
  `list_price` float default NULL,
  `promo_price_1` float default NULL,
  `promo_price_2` float default NULL,
  `promo_price_3` float default NULL,
  `promo_price_4` float default NULL,
  `promo_code_1` varchar(32) collate latin1_general_ci default NULL,
  `promo_code_2` varchar(32) collate latin1_general_ci default NULL,
  `promo_code_3` varchar(32) collate latin1_general_ci default NULL,
  `promo_code_4` varchar(32) collate latin1_general_ci default NULL,
  `primary_image_id` int(11) default '1',
  `display_image` set('on','') collate latin1_general_ci default 'on',
  `display_buynow` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `display_rating` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `display_instock` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `display_ship_measures` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `rating` float default NULL,
  `votes` int(11) default NULL,
  `in_stock` int(11) default '5',
  `items_sold` int(11) default NULL,
  `max_quantity_allow` int(11) NOT NULL default '100',
  `brief` text collate latin1_general_ci,
  `description` text collate latin1_general_ci,
  `item_code` char(25) collate latin1_general_ci default NULL,
  `ship_active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `ship_item_quant_value` float NOT NULL default '1',
  `ship_weight` float NOT NULL,
  `ship_length` int(11) NOT NULL,
  `ship_width` int(11) NOT NULL,
  `ship_height` int(11) NOT NULL,
  `ship_total_amount` float NOT NULL,
  `ship_add_amount` float NOT NULL,
  `ticket_start` int(11) NOT NULL default '1',
  `num_qualifiers` int(11) NOT NULL,
  `starters_per_qualifier` int(11) NOT NULL,
  `promo_display` set('on','') collate latin1_general_ci NOT NULL,
  `event_date` date NOT NULL,
  `event_start_date` datetime NOT NULL,
  `event_close_date` datetime NOT NULL,
  PRIMARY KEY  (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sweeps_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_item_images`
--

DROP TABLE IF EXISTS `sweeps_item_images`;
CREATE TABLE IF NOT EXISTS `sweeps_item_images` (
  `image_id` int(11) NOT NULL auto_increment,
  `item_id` int(11) NOT NULL,
  `image_file_name` varchar(16) NOT NULL,
  `seq` int(11) NOT NULL default '1',
  PRIMARY KEY  (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sweeps_item_images`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_item_location_restrictions`
--

DROP TABLE IF EXISTS `sweeps_item_location_restrictions`;
CREATE TABLE IF NOT EXISTS `sweeps_item_location_restrictions` (
  `item_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sweeps_item_location_restrictions`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_ordered_carts`
--

DROP TABLE IF EXISTS `sweeps_ordered_carts`;
CREATE TABLE IF NOT EXISTS `sweeps_ordered_carts` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_name` varchar(255) collate latin1_general_ci default NULL,
  `product_code` int(11) NOT NULL,
  `discount` float default NULL,
  `price` float default NULL,
  `quantity` int(11) default NULL,
  `item_total` float NOT NULL,
  `ticket_start` int(11) NOT NULL,
  `ticket_end` int(11) NOT NULL,
  PRIMARY KEY  (`item_id`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `sweeps_ordered_carts`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_orders`
--

DROP TABLE IF EXISTS `sweeps_orders`;
CREATE TABLE IF NOT EXISTS `sweeps_orders` (
  `order_id` int(11) NOT NULL auto_increment,
  `invoice_num` int(11) NOT NULL,
  `test_mode` varchar(20) collate latin1_general_ci NOT NULL,
  `order_time` datetime default NULL,
  `IP_add` varchar(30) collate latin1_general_ci NOT NULL,
  `agent_id` int(11) NOT NULL,
  `agent_name` varchar(50) collate latin1_general_ci NOT NULL,
  `cust_name` varchar(255) collate latin1_general_ci default NULL,
  `cust_email` varchar(255) collate latin1_general_ci NOT NULL,
  `cust_phone` varchar(255) collate latin1_general_ci default NULL,
  `cust_address_1` varchar(255) collate latin1_general_ci default NULL,
  `cust_address_2` varchar(255) collate latin1_general_ci default NULL,
  `cust_state` varchar(3) collate latin1_general_ci default NULL,
  `cust_pcode` varchar(10) collate latin1_general_ci default NULL,
  `cust_country` varchar(30) collate latin1_general_ci NOT NULL,
  `total_payment` float NOT NULL,
  `total_amount_payed` float NOT NULL,
  `currency_code` varchar(3) collate latin1_general_ci NOT NULL,
  `promo_code_entered` varchar(32) collate latin1_general_ci default NULL,
  `payment_method` varchar(50) collate latin1_general_ci NOT NULL,
  `payment_transaction_id` varchar(255) collate latin1_general_ci NOT NULL,
  `payment_status` varchar(20) collate latin1_general_ci NOT NULL,
  `hash_inv_num` varchar(100) collate latin1_general_ci NOT NULL,
  `invoice_html` longtext collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sweeps_orders`
--


-- --------------------------------------------------------

--
-- Table structure for table `sweeps_promo_list_config`
--

DROP TABLE IF EXISTS `sweeps_promo_list_config`;
CREATE TABLE IF NOT EXISTS `sweeps_promo_list_config` (
  `mod_id` int(11) NOT NULL,
  `listing_heading` varchar(255) NOT NULL,
  `show_image` set('on','') NOT NULL default 'on',
  `show_close_date` set('on','') NOT NULL default 'on',
  `show_brief` set('on','') NOT NULL default 'on',
  `show_desc` set('on','') NOT NULL,
  `show_price` set('on','') NOT NULL,
  `show_buynow` set('on','') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sweeps_promo_list_config`
--


-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
CREATE TABLE IF NOT EXISTS `themes` (
  `theme_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate latin1_general_ci NOT NULL,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `seq` int(11) NOT NULL,
  `dir_name` varchar(255) collate latin1_general_ci NOT NULL,
  `javascript_file_1` varchar(255) collate latin1_general_ci NOT NULL,
  `javascript_file_2` varchar(255) collate latin1_general_ci NOT NULL,
  `javascript_file_3` varchar(255) collate latin1_general_ci NOT NULL,
  `javascript_file_4` varchar(255) collate latin1_general_ci NOT NULL,
  `user_select` set('on','') collate latin1_general_ci NOT NULL default 'on',
  PRIMARY KEY  (`theme_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`theme_id`, `name`, `active`, `seq`, `dir_name`, `javascript_file_1`, `javascript_file_2`, `javascript_file_3`, `javascript_file_4`, `user_select`) VALUES
(1, 'No Style', 'on', 3, 'No_Style', '', '', '', '', 'on'),
(2, 'default style', 'on', 1, 'default', '', '', '', '', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `theme_override`
--

DROP TABLE IF EXISTS `theme_override`;
CREATE TABLE IF NOT EXISTS `theme_override` (
  `mod_id` int(11) NOT NULL,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  `default_or_defined` set('default','defined') collate latin1_general_ci NOT NULL default 'defined',
  `theme_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `theme_override`
--


-- --------------------------------------------------------

--
-- Table structure for table `used_invoice_number`
--

DROP TABLE IF EXISTS `used_invoice_number`;
CREATE TABLE IF NOT EXISTS `used_invoice_number` (
  `invoice_num` int(11) NOT NULL auto_increment,
  `used` varchar(11) NOT NULL,
  PRIMARY KEY  (`invoice_num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `used_invoice_number`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

DROP TABLE IF EXISTS `user_accounts`;
CREATE TABLE IF NOT EXISTS `user_accounts` (
  `user_id` int(11) NOT NULL auto_increment,
  `username` varchar(16) character set latin1 collate latin1_general_ci NOT NULL default '',
  `password` varchar(100) character set latin1 collate latin1_general_ci NOT NULL default '',
  `account_id` int(11) NOT NULL default '10',
  `active` set('on','') character set latin1 collate latin1_general_ci default 'on',
  `email` varchar(255) character set latin1 collate latin1_general_ci default NULL,
  `login_with_email` set('on','') NOT NULL default 'on',
  `created` datetime NOT NULL,
  `expire_time` int(3) NOT NULL default '15',
  `display_username` set('','on') NOT NULL default 'on',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`user_id`, `username`, `password`, `account_id`, `active`, `email`, `login_with_email`, `created`, `expire_time`, `display_username`) VALUES
(1, 'mharper', '005f8821d951a5c7ccbd779b3c730bdd', 1, 'on', 'mattharper69@gmail.com', 'on', '0000-00-00 00:00:00', 0, 'on'),
(2, 'content_edit', '005f8821d951a5c7ccbd779b3c730bdd', 5, 'on', 'user@email.com', 'on', '0000-00-00 00:00:00', 0, 'on');

-- --------------------------------------------------------

--
-- Table structure for table `user_page_access`
--

DROP TABLE IF EXISTS `user_page_access`;
CREATE TABLE IF NOT EXISTS `user_page_access` (
  `user_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `access_right` int(2) NOT NULL,
  PRIMARY KEY  (`user_id`,`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `user_page_access`
--

INSERT INTO `user_page_access` (`user_id`, `page_id`, `access_right`) VALUES
(1, 1, 2),
(1, 2, 2),
(1, 3, 2),
(1, 4, 2),
(1, 5, 2),
(1, 6, 2),
(1, 100, 2),
(2, 1, 2),
(2, 2, 2),
(2, 3, 2),
(2, 4, 2),
(2, 6, 2),
(2, 100, 2);

-- --------------------------------------------------------

--
-- Table structure for table `_cms_html_replace_list`
--

DROP TABLE IF EXISTS `_cms_html_replace_list`;
CREATE TABLE IF NOT EXISTS `_cms_html_replace_list` (
  `tag_id` int(11) NOT NULL auto_increment,
  `find_str` varchar(255) collate latin1_general_ci NOT NULL,
  `replace_str` varchar(255) collate latin1_general_ci NOT NULL,
  `active` set('on','') collate latin1_general_ci NOT NULL default 'on',
  PRIMARY KEY  (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=45 ;

--
-- Dumping data for table `_cms_html_replace_list`
--

INSERT INTO `_cms_html_replace_list` (`tag_id`, `find_str`, `replace_str`, `active`) VALUES
(1, '<P>', '<p>', 'on'),
(2, '</P>', '</p>', 'on'),
(3, '<BR>', '<br/>', 'on'),
(4, '<br>', '<br/>', 'on'),
(5, '<EM>', '<em>', 'on'),
(6, '</EM>', '</em>', 'on'),
(7, '<STRONG>', '<strong>', 'on'),
(8, '</STRONG>', '</strong>', 'on'),
(9, '<U>', '<span class="Underline">', 'on'),
(10, '<u>', '<span class="Underline">', 'on'),
(11, '</U>', '</span>', 'on'),
(12, '</u>', '</span>', 'on'),
(13, '<STRIKE>', '<span class="Strike">', 'on'),
(14, '<strike>', '<span class="Strike">', 'on'),
(15, '</STRIKE>', '</span>', 'on'),
(16, '</strike>', '</span>', 'on'),
(17, 'contentEditable=true', '', 'on'),
(19, '</SPAN>', '</span>', 'on'),
(18, '<SPAN', '<span', 'on'),
(20, 'class=Strike', 'class="Strike"', 'on'),
(21, 'class=Underline', 'class="Underline"', 'on'),
(22, '<s>', '<span class="Srtike">', 'on'),
(23, '</s>', '</span>', 'on'),
(24, 'contenteditable="true"', '', 'on'),
(25, '<i>', '<em>', 'on'),
(26, '</i>', '</em>', 'on'),
(27, '<I>', '<em>', 'on'),
(28, '</I>', '</em>', 'on'),
(29, '<b>', '<strong>', 'on'),
(30, '</b>', '</strong>', 'on'),
(31, '<B>', '<strong>', 'on'),
(32, '</B>', '</strong>', 'on'),
(33, '<UL>', '<ul>', 'on'),
(34, '</UL>', '</ul>', 'on'),
(35, '<OL>', '<ol>', 'on'),
(36, '</OL>', '</ol>', 'on'),
(37, '<LI>', '<li>', 'on'),
(38, '</LI>', '</li>', 'on'),
(39, '<br >', '<br/>', 'on'),
(40, '<BR >', '<br/>', 'on'),
(41, '<hr>', '<hr/>', 'on'),
(42, '<HR>', '<hr/>', 'on'),
(43, '<A ', '<a ', 'on'),
(44, '</A>', '</a>', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `_cms_html_tags`
--

DROP TABLE IF EXISTS `_cms_html_tags`;
CREATE TABLE IF NOT EXISTS `_cms_html_tags` (
  `tag_id` int(11) NOT NULL auto_increment,
  `tag_name` varchar(50) collate latin1_general_ci NOT NULL,
  `start_start` varchar(255) collate latin1_general_ci NOT NULL,
  `end_tag` varchar(255) collate latin1_general_ci NOT NULL,
  `ie7_start` varchar(255) collate latin1_general_ci NOT NULL,
  `ie7_end` varchar(255) collate latin1_general_ci NOT NULL,
  `ie8_start` varchar(255) collate latin1_general_ci NOT NULL,
  `ie8_end` varchar(255) collate latin1_general_ci NOT NULL,
  `FF_start` varchar(255) collate latin1_general_ci NOT NULL,
  `FF_end` varchar(255) collate latin1_general_ci NOT NULL,
  `chrome_start` varchar(255) collate latin1_general_ci NOT NULL,
  `chrome_end` varchar(255) collate latin1_general_ci NOT NULL,
  `safari_start` varchar(255) collate latin1_general_ci NOT NULL,
  `safari_end` varchar(255) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `_cms_html_tags`
--

INSERT INTO `_cms_html_tags` (`tag_id`, `tag_name`, `start_start`, `end_tag`, `ie7_start`, `ie7_end`, `ie8_start`, `ie8_end`, `FF_start`, `FF_end`, `chrome_start`, `chrome_end`, `safari_start`, `safari_end`) VALUES
(1, 'paragraph', '<p', '</p>', '<P', '</P>', '<P', '</P>', '<p', '</p>', '<p', '</p>', '<p', '</p>'),
(2, 'Line Break', '<br/>', '', '<BR>', '', '<BR>', '', '<br>', '', '<br>', '', '<br>', ''),
(3, 'Bold', '<span class="Bold">', '</span>', '<STRONG>', '</STRONG>', '<STRONG>', '</STRONG>', '<span style="font-weight: bold;">', '</span>', '<b>', '</b>', '<b>', '</b>'),
(4, 'un-Bold', '', '', '</STRONG>', '<STRONG>', '</STRONG>', '<STRONG>', '', '', '<b>', '<b>', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `_cms_resize_img_modes`
--

DROP TABLE IF EXISTS `_cms_resize_img_modes`;
CREATE TABLE IF NOT EXISTS `_cms_resize_img_modes` (
  `mode_id` int(11) NOT NULL,
  `mode_name` varchar(50) NOT NULL,
  `seq` int(11) NOT NULL,
  `active` set('on','') NOT NULL default 'on',
  `mode_desc` varchar(255) NOT NULL,
  PRIMARY KEY  (`mode_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_cms_resize_img_modes`
--

INSERT INTO `_cms_resize_img_modes` (`mode_id`, `mode_name`, `seq`, `active`, `mode_desc`) VALUES
(1, 'Stretch/Reduce to size', 1, 'on', 'specify new width and height and STRETCH and/or REDUCE to fit'),
(2, 'Crop to size', 2, 'on', 'specify new width and height and CROP  width or height to fit'),
(3, 'Adjust in proportion by (Width)', 3, 'on', 'adjust in proportion according set width'),
(4, 'Adjust in proportion by (Height)', 4, 'on', 'adjust in proportion according set height'),
(5, 'adjust in proportion according width of Height', 5, 'on', 'adjust in proportion according set length for width/height which ever is the greatest'),
(0, 'No Resize', 0, 'on', 'Do Not resize');

-- --------------------------------------------------------

--
-- Table structure for table `_cms_user_access_types`
--

DROP TABLE IF EXISTS `_cms_user_access_types`;
CREATE TABLE IF NOT EXISTS `_cms_user_access_types` (
  `account_id` int(11) NOT NULL auto_increment,
  `name` varchar(50) collate latin1_general_ci NOT NULL,
  `access_code` int(2) NOT NULL,
  `description` varchar(1000) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `_cms_user_access_types`
--

INSERT INTO `_cms_user_access_types` (`account_id`, `name`, `access_code`, `description`) VALUES
(1, 'Super Administrator', 1, 'has access all areas'),
(2, 'User Account Admin', 2, 'has access all areas except the System Admin area'),
(3, 'Site Administrator', 3, 'Has access to: Site Settings, Navigation and Edit pages'),
(4, 'Page Editor', 4, 'Has access to: Edit Page Content and Settings'),
(5, 'Content Editor', 5, 'Has access to: Edit page Content Only'),
(10, 'Public Area', 10, 'Can not log in to the Admin area - Only Public Area');

-- --------------------------------------------------------

--
-- Table structure for table `_cms_validation`
--

DROP TABLE IF EXISTS `_cms_validation`;
CREATE TABLE IF NOT EXISTS `_cms_validation` (
  `data_id` int(1) NOT NULL auto_increment,
  `data_name` varchar(50) collate latin1_general_ci NOT NULL,
  `required` set('on','') collate latin1_general_ci NOT NULL default '',
  `is_unique` set('on','') collate latin1_general_ci NOT NULL default '',
  `char_min` int(6) NOT NULL,
  `char_max` int(6) NOT NULL,
  `char_type` set('all','email','alphanum','numeric') collate latin1_general_ci NOT NULL,
  `char_exclude` varchar(255) collate latin1_general_ci NOT NULL,
  `field_name` varchar(100) collate latin1_general_ci NOT NULL,
  `table_name` varchar(100) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`data_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `_cms_validation`
--

INSERT INTO `_cms_validation` (`data_id`, `data_name`, `required`, `is_unique`, `char_min`, `char_max`, `char_type`, `char_exclude`, `field_name`, `table_name`) VALUES
(1, 'Log-in User name', 'on', 'on', 4, 20, 'all', '<>/\\?|', 'username', 'user_accounts'),
(2, 'Log-in Email', 'on', 'on', 8, 256, 'email', '', 'email', 'user_accounts');

-- --------------------------------------------------------

--
-- Table structure for table `_module_cats`
--

DROP TABLE IF EXISTS `_module_cats`;
CREATE TABLE IF NOT EXISTS `_module_cats` (
  `mod_cat_id` int(11) NOT NULL auto_increment,
  `mod_cat_name` varchar(50) collate latin1_general_ci NOT NULL,
  `seq` int(11) NOT NULL,
  `active` set('on','') collate latin1_general_ci default 'on',
  PRIMARY KEY  (`mod_cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `_module_cats`
--

INSERT INTO `_module_cats` (`mod_cat_id`, `mod_cat_name`, `seq`, `active`) VALUES
(1, 'static content', 10, 'on'),
(2, 'navigation', 20, 'on'),
(3, 'Image Browsers', 30, 'on'),
(4, 'miscellaneous', 40, 'on'),
(5, 'Site Credit Links', 50, 'on'),
(6, 'Forms', 60, 'on'),
(7, 'Audio / Video', 70, 'on'),
(8, 'Shops &amp; Brochures', 80, 'on'),
(9, 'Accounting', 90, 'on'),
(10, 'Design', 100, 'on');

-- --------------------------------------------------------

--
-- Table structure for table `_module_types`
--

DROP TABLE IF EXISTS `_module_types`;
CREATE TABLE IF NOT EXISTS `_module_types` (
  `mod_type_id` int(11) NOT NULL auto_increment,
  `seq` int(11) NOT NULL default '1',
  `active` set('on','') collate latin1_general_ci default 'on',
  `mod_name` varchar(50) collate latin1_general_ci NOT NULL,
  `category` int(11) NOT NULL,
  `file_name` varchar(255) collate latin1_general_ci NOT NULL,
  `file_name_pre` varchar(255) collate latin1_general_ci default NULL,
  `cms_edit_filename` varchar(50) collate latin1_general_ci NOT NULL,
  `mod_db_table` varchar(50) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`mod_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=107 ;

--
-- Dumping data for table `_module_types`
--

INSERT INTO `_module_types` (`mod_type_id`, `seq`, `active`, `mod_name`, `category`, `file_name`, `file_name_pre`, `cms_edit_filename`, `mod_db_table`) VALUES
(1, 0, 'on', 'Text  1', 1, 'text_1.php', NULL, '', 'mod_text_1'),
(2, 0, 'on', 'List Items', 1, 'list_items.php', NULL, '', 'mod_list_items'),
(3, 0, 'on', 'Site Design By', 5, 'site_credits.php', NULL, '', ''),
(4, 0, 'on', 'W3C Validated Links', 5, 'w3c_validate_links.php', NULL, '', ''),
(5, 0, 'on', 'Sub Menu List', 2, 'sub_menu.php', NULL, '', ''),
(6, 0, 'on', 'Search Site Box', 2, 'search_box.php', NULL, '', ''),
(7, 0, 'on', 'Search Site Page', 2, 'search_page.php', NULL, '', ''),
(8, 0, 'on', 'Display Text as an Image', 1, 'text_2_image.php', NULL, '', 'mod_text_2_image'),
(9, 0, 'on', 'Log-out Link', 2, 'logout_link.php', NULL, '', ''),
(10, 0, 'on', 'Contact Us Form', 6, 'contact_form.php', '', '', ''),
(11, 110, 'on', 'Single Image', 1, 'image_1.php', NULL, '', 'mod_image_1'),
(12, 1, 'on', 'Photo Browser - Php', 3, 'photo_browser_php.php', NULL, '', ''),
(13, 130, 'on', 'Photo Browser - Flash', 3, 'photo_browser_flash.php', NULL, '', ''),
(14, 100, 'on', 'Heading', 1, 'heading.php', NULL, '', 'mod_heading'),
(15, 1, 'on', 'Theme Switcher', 6, 'theme_switcher.php', NULL, '', ''),
(16, 160, 'on', 'Google Map', 4, 'google_map.php', 'google_map/google_map_head_info.php', '', 'mod_google_map'),
(17, 170, 'on', 'Insert HTML', 1, 'insert_html.php', 'insert_html_head.php', '', 'mod_insert_html'),
(18, 180, 'on', 'Flash Player', 7, 'flash_player.php', NULL, '', 'mod_flash_player'),
(19, 190, 'on', 'DIV start', 8, 'div_mod.php', NULL, '', ''),
(20, 200, 'on', 'DIV end', 8, 'div_mod.php', NULL, '', ''),
(21, 210, 'on', 'Link', 1, 'link.php', NULL, '', 'mod_links'),
(22, 220, 'on', 'Contact Listing', 4, 'contact_list.php', NULL, '', ''),
(23, 52, 'on', 'Sub Menu List (2 Columns)', 2, 'sub_menu_2col.php', NULL, '', ''),
(24, 240, 'on', 'Online Shop', 8, 'shop_index.php', NULL, '', ''),
(26, 250, 'on', 'Add to Cart Button', 8, 'shop_add2cart_button.php', NULL, '', 'mod_add2cart_button'),
(27, 270, 'on', 'Shopping Cart SideBar', 8, 'shop_cart_side.php', NULL, '', ''),
(28, 280, 'on', 'Postage Calculator SideBar', 8, 'shop_post_calc_user.php', NULL, '', ''),
(29, 290, 'on', 'Continue Shopping Button', 8, 'shop_cont_shop_button.php', NULL, '', ''),
(30, 300, 'on', 'PayNow Side Button (Canceled)', 8, 'shop_canx_paynow_side.php', NULL, '', ''),
(31, 400, 'on', 'Invoice Viewer', 9, 'invoice_viewer.php', NULL, '', ''),
(32, 320, 'on', 'Theme Override', 10, 'theme_override.php', NULL, '', ''),
(33, 330, 'on', 'Site Map', 2, 'sitemap.php', NULL, '', ''),
(34, 100, 'on', 'Photo Gallery', 3, 'photo_gal_index.php', 'photo_gal_index_head.php', '', 'mod_photo_gal_settings'),
(35, 350, 'on', 'Event Listing', 4, 'event_listing.php', NULL, 'cms_edit_event_list_index.php', 'mod_event_list'),
(36, 1, 'on', 'Insert a File', 1, 'insert_file.php', NULL, '', 'mod_insert_file'),
(37, 370, 'on', 'Brochure / Photo album', 8, 'brochure.php', NULL, 'cms_edit_brochure_index.php', 'mod_brochure_settings'),
(38, 380, 'on', 'Table', 1, 'table.php', NULL, '', 'mod_table_config'),
(39, 390, 'on', 'Profile Listing', 4, 'profiles.php', NULL, 'cms_edit_profile_index.php', 'mod_profiles'),
(40, 400, 'on', 'mod_url_referer_alert', 4, 'url_referer_alert.php', NULL, '', ''),
(41, 410, 'on', 'Custom Build', 8, 'custom_build.php', NULL, '', 'custom_build'),
(42, 420, 'on', 'Page Redirect', 4, 'page_redirect.php', NULL, '', 'mod_page_redirect'),
(101, 1000, 'on', 'Website Portfolio', 4, 'website_portfolio.php', NULL, '', 'mod_website_portfolio'),
(102, 1020, 'on', 'Sweeps shop', 8, 'sweeps_index.php', NULL, 'cms_edit_sweeps_index.php', ''),
(103, 1030, 'on', 'Sweeps Cart SideBar', 8, 'sweeps_cart_side.php', NULL, '', ''),
(104, 1040, 'on', 'Sweeps Promo Listing', 8, 'sweeps_promo_list.php', NULL, '', 'sweeps_items'),
(105, 1050, 'on', 'Sweeps Agent Login', 8, 'sweeps_agent_login.php', NULL, '', 'sweeps_agents'),
(106, 1060, 'on', 'Sweeps Agent Auto Login', 8, 'sweeps_agent_auto_login.php', NULL, '', 'sweeps_agent_login.php');

-- --------------------------------------------------------

--
-- Table structure for table `_shop_pay_types`
--

DROP TABLE IF EXISTS `_shop_pay_types`;
CREATE TABLE IF NOT EXISTS `_shop_pay_types` (
  `pay_method_id` int(11) NOT NULL auto_increment,
  `pay_method_name` varchar(50) NOT NULL,
  `config_file_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`pay_method_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `_shop_pay_types`
--

INSERT INTO `_shop_pay_types` (`pay_method_id`, `pay_method_name`, `config_file_name`) VALUES
(1, 'eWay - Merchant Hosted', '_eway_configs.php'),
(2, 'PayPal -  Standard', '_paypal_configs.php'),
(3, 'PayPal - Express Checkout', '_paypal_configs.php'),
(4, 'St George IPG API', '_stgeorgeAPI_configs.php');
