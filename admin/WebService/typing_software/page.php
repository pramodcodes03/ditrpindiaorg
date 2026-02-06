<?php
header('Access-Control-Allow-Headers:Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Origin: *');

include('../../include/classes/database_results.class.php');
include('../../include/classes/access.class.php');
include('../../include/classes/account.class.php');
include('../../include/classes/typing.class.php');

//json_encode($_REQUEST,true);
//exit();

$db   = new  database_results();
$access = new  access();
$account = new account();
$typing= new typing();

//print_r($_REQUEST);
$request = isset($_REQUEST['req'])?$_REQUEST['req']:'';
$data=array();
switch($request)
{	
	case('add-typing-institute'):
		$data = $typing->add_typing_institute();
		break;

        case('verify-typing-institute'):
		$data = $typing->verify_typing_institute();
		break;	
		
	default:
		$data['success']=false;
		$data['message']='Invalid Request!';
		$data = json_encode($data);
		break;
}
echo $data;
?>