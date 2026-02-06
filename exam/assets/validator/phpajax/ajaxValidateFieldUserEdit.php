<?php
session_start();
$conn=mysql_connect('localhost','cgryorg_rimi','amarrimi2013') or die("Database Error");
mysql_select_db('cgryorg_rimi1',$conn);
/* RECEIVE VALUE */
$validateValue=$_REQUEST['fieldValue'];
$validateId=$_REQUEST['fieldId'];

$QueryCon=" select * from `ri_user` where email = '".$validateValue."' and id != ".$_SESSION['user_id']."";
//echo $QueryCon;exit;
$rs1=mysql_query($QueryCon);
$num1=mysql_num_rows($rs1);
$fetch1=mysql_fetch_array($rs1);

$validateError= "This email is already taken";
$validateSuccess= "This email is available";



	/* RETURN VALUE */
	$arrayToJs = array();
	$arrayToJs[0] = $validateId;

if($num1>0){		// validate??
	$arrayToJs[1] = false;			// RETURN TRUE
	echo json_encode($arrayToJs);			// RETURN ARRAY WITH success
}else{
	for($x=0;$x<1000000;$x++){
		if($x == 990000){
			$arrayToJs[1] = true;
			echo json_encode($arrayToJs);		// RETURN ARRAY WITH ERROR
		}
	}
	
}

?>