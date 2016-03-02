<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	if (isset($_REQUEST['mdsa']) AND $_REQUEST['mdsa'] != '')
	{
		$agent_link_code = $_REQUEST['mdsa'];
		
			//--------Get agent info from db:		
			$mysql_err_msg = 'Retrieving Agent Account Info';	
			$sql_statement = 'SELECT *  FROM sweeps_agents'
														.' WHERE agent_link_code = "'.$agent_link_code.'"'	
														.' AND active = "on"'
														;

			$agent_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

			if (mysql_num_rows (ReadDB ($sql_statement, $mysql_err_msg)) > 0)
			{
				$agent_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));
				
				//	store agent name and set login flag
				$_SESSION['agent_login_id'] = $agent_info['agent_id'];
				$_SESSION['agent_login_name'] = $agent_info['agent_name'];	
				$_SESSION['agent_cannot_logout'] = 1;
				
			}
			
	}
	
?>