<?php


	//	File Name Preview
	if (isset($_POST['filetype']))
	{	
	
		$file_suffix_array = array(
								 0 => '(none)'
								,1 => 'date (dd-mm-yy)'
								,2 => 'date (yy-mm-dd)'
								,3 => 'date (ddmmyy)'
								,4 => 'date (yymmdd)'							
							);		

		switch ($_POST['filesuffix'])
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
		
		$filename = $_POST['filename'] . $suffix . '.' .$_POST['filetype'];
		
		echo $filename ."\n";
	
	}
	
?>