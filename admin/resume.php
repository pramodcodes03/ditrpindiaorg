<?php 
session_start(); ob_start();

include('include/classes/database_results.class.php');
include('include/classes/access.class.php');

$db 	= new  database_results();
$access = new  access();



 if(!isset($_SESSION['user_login_id']))
{
	header('location:login.php');
}
 $path= isset($_GET['r'])?$_GET['r']:'';


if($path!='')
{
	
echo	$path = base64_decode($path);
	if(file_exists($path))
	{	
	  $file = $path;
	  $filename = 'resume.pdf';
	  header('Content-type: application/pdf');
	  header('Content-Disposition: inline; filename="' . $filename . '"');
	  header('Content-Transfer-Encoding: binary');
	  header('Accept-Ranges: bytes');
	  @readfile($file);
	}else{
	header('location:index.php');	
	}
}else{
	header('location:index.php');	
}

?>	
