<?php
$msg='';
$forgetaction = isset($_POST['action'])?$_POST['action']:'';
if($forgetaction!='')
{
	$email = $db->test($_POST['email'])?$_POST['email']:'';
	$role = $db->test($_POST['role'])?$_POST['role']:'';
	$result = $access->forgot_pass($email,$role);
	
	$result = json_decode($result, true);
	$success_f= isset($result['success'])?$result['success']:'';
	$message_f= isset($result['message'])?$result['message']:'';
	$errors_f = isset($result['errors'])?$result['errors']:'';
	
	$msg=$message_f;
}
?>