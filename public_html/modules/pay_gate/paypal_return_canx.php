<?php
	

	session_start();
	
	//	return user to check-out STAGE 2
	$_SESSION['check_out_stage'] = 2;

	//	return to the check out page...
	header ("location: /index.php?p=".$_GET['p']."&view=checkout");
	exit();

	
?>