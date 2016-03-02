<?php

//-----Sanitiser ( this is useded to stop hackers altering the database )---------------------------

//-----copyright Jim Frazer & Matt Harper 2007-------------------------------------------------------

// no direct access
	defined('SITE_KEY') or die('File not found');
	

class sanitiser
{



	var $sql_banlist = array 
	(	
		"/insert into/i",
		"/select from/i",
		"/update /i",
	    "/delete from/i",
	    "/create table/i",
	    "/distinct /i",
	    "/alter database/i",
	    "/alter table/i",
	    "/alter index/i",
	    "/drop table/i",
	    "/drop database/i",
	    "/order by /i",
	    "/group by /i",
	    "/asc /i",
	    "/desc /i",
	);

	var  $sql_replacelist = array 
	(
		  "",
	      "",
	      "",
	      "",
	      "",
	      "",
	      "",
	      "",
	      "",
	      "",
	      "",
	      "",
	      "",
	      "",
	      "", 
	);

	
	var $general_illegal_chars_get  = array('~', '`', '!',      '#', '$', 
	'%', '^',      '*',                '+', '=', '{', '}', '[', ']', '\'', '"', 
	';', ':',                '>',      '<', '|', '\\'  );
	//var $general_illegal_chars_post = array('~', '`',                     
	//	 '^',                               '=', '{', '}', '[', ']',           
	//										'|', '\\'  );
	var $cookie_illegal_chars       = array('~', '`', '!', '@', '#', '$', 
	'%', '^', '&', '*', '(', ')', '_', '+', '=', '{', '}', '[', ']', '\'', '"', 
	';', ':', '/', '?', '.', '>', ',', '<', '|', '\\'  );



	function process($string)
	{

		// Sanitise any direct sql injection attempts 
		$string = strtolower($string);   //----make all lower case
		$sanitizedString = str_replace($this->sql_banlist, $this->sql_replacelist, trim($string));

		$sanitizedString = preg_replace( $this->sql_banlist, $this->sql_replacelist,  trim($string) );

		//	Now sanitise the sql sanitised string from other illegal chars
		// 	Note that this doesn't look after any base64 encoded strings or any string encoded in anything other than ascii...
		//	you need to look after the more advanced naughty stuff yourself


		$sanitizedString = str_replace($this->general_illegal_chars_get, $this->sql_replacelist, $sanitizedString);

		return $sanitizedString;  
	}

	
 	function processPost($string)
	{

		if (is_array($string))
		{
			$sanitizedString = preg_replace( $this->sql_banlist, $this->sql_replacelist,  $string );
		}
		else
		{
			$sanitizedString = preg_replace( $this->sql_banlist, $this->sql_replacelist,  trim($string) );
		}
				
		//$sanitizedString = str_replace($this->general_illegal_chars_post, $this->sql_replacelist, $sanitizedString);
		  
		return $sanitizedString;  
	} 


}

?>