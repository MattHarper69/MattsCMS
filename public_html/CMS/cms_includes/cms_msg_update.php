<?php
		
	echo TAB_5.'<div class="UpdateMsgDiv" style="display: none;">'."\n";	
	
	$error = FALSE;
	
	//---------Update error msg:
	if (isset ($_SESSION['update_error_msg']) AND $_SESSION['update_error_msg'] != "" )
	{
	
		echo TAB_6.'<script language="javascript" type="text/javascript">alert("'.$_SESSION['update_error_msg'].'")</script>'."\n";
		echo TAB_6.'<p class = "WarningMSG" >Could NOT update all info Please check:</p>'."\n";
		echo TAB_6.'<p class = "WarningMSG" >'.str_replace ('\n','<br/>',$_SESSION['update_error_msg']).'</p>'."\n";
		
		$error = true;
		
	}
	
	//---------Update Success msg:
	if (isset ($_SESSION['update_success_msg']) AND $_SESSION['update_success_msg'] != "" AND $error != TRUE )
	{				
		echo TAB_6.'<p class = "OkMSG" >'.str_replace ('\n','<br/>',$_SESSION['update_success_msg']).'</p>'."\n";


	}		
		
	//	now delete succss msg
	unset($_SESSION['update_error_msg']);
	unset($_SESSION['update_success_msg']);	
	
	
	echo TAB_5.'</div>'."\n";
	
?>