<?php

	// re-direct to here on first include (lets sql update go ahead before re-directing)
	if (isset($sent_key) AND $sent_key == 1)
	{
		header('location: /CMS/cms_edit_mods/sweeps/cms_export_data.php?profile_id='.$profile_id);
	}
	else
	{	
		//--------Set key to start include files
		define( 'SITE_KEY', 1 );
		
		$file_path_offset = '../../../';

		//----Get Common code to all pages
		require_once ($file_path_offset.'includes/common.php');

		require_once ($file_path_offset.'includes/access.php');		
		
	}
	
	$file_suffix_array = array(
								 0 => '(none)'
								,1 => 'date (dd-mm-yy)'
								,2 => 'date (yy-mm-dd)'
								,3 => 'date (ddmmyy)'
								,4 => 'date (yymmdd)'							
							);			
		
	$this_page = $_SERVER['PHP_SELF'];	

	
if (isset($_SESSION['access']) AND $_SESSION['access'] < 5 )
{	
	

	//	Get config settings for export data
	$mysql_err_msg = 'Unable to get settings for exporting Orders data';	
	$sql_statement = 'SELECT * from sweeps_export_data_profiles WHERE profile_id = "'.$_REQUEST['profile_id'].'"';		

	$export_profile_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
	
	//	Get required FIELDS
	$sql_statement = 'SELECT fields_selected from sweeps_export_data_fields' 
												
												.' WHERE profile_id = "'.$_REQUEST['profile_id'].'"' 
												.' ORDER BY seq'
												;	
	$export_data_fields_result = ReadDB ($sql_statement, $mysql_err_msg);	
	
	$i = 1;
	$export_data_fields_str = '';
	while ($export_data_fields = mysql_fetch_array($export_data_fields_result))
	{
		if ($export_data_fields['fields_selected'] != '')
		{
			if ($i == 1)
			{ 
				$export_data_fields_str .= ' ' . $export_data_fields['fields_selected'];
			}
			else
			{
				$export_data_fields_str .= ', ' . $export_data_fields['fields_selected'];
			}	
		}

	
		$i++;
	}

	//	Get required FILTERS
	$sql_statement = 'SELECT * from sweeps_export_data_filters WHERE profile_id = "'.$_REQUEST['profile_id'].'" AND active = "on"';	
	$export_data_filters_result = ReadDB ($sql_statement, $mysql_err_msg);	
	
	$export_data_filters_str = '';
	while ($export_data_filters = mysql_fetch_array($export_data_filters_result))
	{
		
		if ($export_data_filters['filter_operator'] == 'LIKE') {$like_str = '%';}
		else {$like_str = '';}
		
		
		$export_data_filters_str .= ' AND ' . $export_data_filters['filter_name']. 	' ' . $export_data_filters['filter_operator'] . ' "' 
											. $like_str.$export_data_filters['filter_data'].$like_str.'"';

	}

	//	Get required ORDER_BY
	$sql_statement = 'SELECT fields_order_by from sweeps_export_data_order_by'

												.' WHERE profile_id = "'.$_REQUEST['profile_id'].'"'
												.' ORDER BY order_by_id'
												;			
	$export_data_order_by_result = ReadDB ($sql_statement, $mysql_err_msg);	

	

	
	$i = 1;
	$export_data_order_by_str = '';
	while ($export_data_order_by = mysql_fetch_array($export_data_order_by_result))
	{
		if ($i == 1)
		{ 
			$export_data_order_by_str .= ' ORDER BY ' . $export_data_order_by['fields_order_by'];
		}
		else
		{
			$export_data_order_by_str .= ', ' . $export_data_order_by['fields_order_by'];
		}
	
		$i++;
	}
	
	switch ($export_profile_info['category'])
	{
		case 'Orders':				
			$from_tables = 'sweeps_orders, sweeps_ordered_carts';
		break;
		case 'Products':				
			$from_tables = 'sweeps_items, sweeps_categories';
		break;
	}

	//	build SQL statement
	$mysql_err_msg = 'Unable to get Order info for Invoice';	
	$sql_statement = 'SELECT' 	
									
								.  $export_data_fields_str
		
								.' FROM '.$from_tables 
	
								//.' WHERE sweeps_ordered_carts.item_id = sweeps_items.item_id'	
								.' WHERE sweeps_ordered_carts.order_id = sweeps_orders.order_id'
								.  $export_data_filters_str
								.  $export_data_order_by_str										
								;

	$order_items_result = ReadDB ($sql_statement, $mysql_err_msg);


	switch ($export_profile_info['filename_suffix'])
	{
		case $file_suffix_array[0]:
			$suffix = '';				
		break;
		case $file_suffix_array[1]:
			$suffix = '_' . date("d-m-y");				
		break;					
		case $file_suffix_array[2]:
			$suffix = '_' . date("y-m-d");				
		break;					
		case $file_suffix_array[3]:
			$suffix = '_' . date("dmy");				
		break;
		case $file_suffix_array[4]:
			$suffix = '_' . date("ymd");				
		break;					
	}
			
	$filename = $export_profile_info['file_name'] . $suffix . '.' .$export_profile_info['file_type'];
	
	switch ($export_profile_info['file_type'])
	{
		case 'csv';
		case 'txt';
		
			csv_from_mysql_resource($order_items_result, $filename, $export_profile_info['print_headers']);
			
		break;
			
		case 'sql':

			$size_in_bytes = strlen($sql_statement);
			header("Content-type: application/vnd.ms-excel");
			header("Content-disposition:  attachment; filename=".$filename . "; size=".$size_in_bytes);
	 
		// send output
		print $sql_statement;
		exit;			
		
		break;
	}
	
}

	
	// takes a database resource returned by a query
	function csv_from_mysql_resource($resource, $filename, $print_headers)
	{
		
		//	lock table while getting next invoice number	//	just to be sure....
		$mysql_err_msg = 'Locking tables';
		$sql_statement = 'LOCK TABLES sweeps_orders WRITE';
		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));	
	

		$output = '';
		$headers_printed = false;
	 
		while($row = mysql_fetch_array($resource, MYSQL_ASSOC))
		{
			// print out column names as the first row
			if(!$headers_printed and $print_headers)
			{
				$output .= join(',', array_keys($row)) ."\n";
				$headers_printed = true;
			}
	 
			// remove newlines from all the fields and
			// surround them with quote marks
			foreach ($row as &$value)
			{
				$value = str_replace("\r\n", '', $value);
				$value = '"' . $value . '"';
			}
	 
			$output .= join(',', $row)."\n";
	 
		}

		// unlock table
		$mysql_err_msg = 'unlocking tables';
		$sql_statement = 'UNLOCK TABLES';
		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));			
		
		// set the headers
		$size_in_bytes = strlen($output);
		
		$path_parts = pathinfo($filename);
		if ($path_parts['extension'] == 'csv')
		{
			header("Content-type: application/vnd.ms-excel");		
		}

		header("Content-disposition:  attachment; filename=".$filename . "; size=".$size_in_bytes);
	 
		// send output
		print $output;
		exit;
	}

	
?>