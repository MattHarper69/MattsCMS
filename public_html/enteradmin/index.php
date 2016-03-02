<?php

	//	Start the Session
	session_start();

	$_SESSION['load_admin'] = TRUE;
	
	if (isset($_SESSION['last_known_page']))
	{
		header("location: ".$_SESSION['last_known_page']); 
	}
	else
	{
		header("location: /"); 
	}

	exit();
		
?>