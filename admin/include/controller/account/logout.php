<?php

/* logout */
/*if(isset($_GET['logout'])){
	if($access->user_logout())
		header('location:login.php');
}*/
if(isset($_GET['logout'])){
	$old_session = isset($_SESSION['old_session'])?$_SESSION['old_session']:'';
	//print_r($old_session); exit();
	if($access->user_logout())
	{
		if($old_session!='')
		{
			@session_start();

			$_SESSION = $old_session;
			if($_SESSION['user_role']==1)
			header('location:index.php');
			else
			header('location:index.php');
		}else{
			header('location:login.php');
		}
	}
}

?>