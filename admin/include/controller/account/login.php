<?php

$action =  isset($_POST['login'])?$_POST['login']:'';

if($action!='')

{

	

	$uname	= $db->test(isset($_POST['uname'])?$_POST['uname']:'');

	$pword	= $db->test(isset($_POST['pword'])?$_POST['pword']:'');
	
	$result	= $access->user_login($uname,$pword);

	$result = json_decode($result, true);

	$success= isset($result['success'])?$result['success']:'';

	$message= isset($result['message'])?$result['message']:'';

	$errors = isset($result['errors'])?$result['errors']:'';

	if($success==true)

	{

		header('location:index.php');

	}

}



?>