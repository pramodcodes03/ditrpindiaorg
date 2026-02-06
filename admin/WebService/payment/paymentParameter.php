<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

require('/home4/kzqhujmy/public_html/ditrpselfstudy/admin/include/classes/database_results.class.php');
require('/home4/kzqhujmy/public_html/ditrpselfstudy/admin/include/classes/access.class.php');


$db 	= new  database_results();
$access = new  access();

$furl = "https://ditrpself-study.com/admin/WebService/payment/failure.php";

if(isset($_GET['course_id']) && $_GET['course_id']!="" && isset($_GET['user_id']) && $_GET['user_id']!="" && isset($_GET['amount']) && $_GET['amount']!="")
{
    //&& isset($_GET['type']) && $_GET['type']!=""
    $user_id        =$_GET['user_id'];
    $course_id      =$_GET['course_id'];
    $amount         =$_GET['amount'];
    $referal_code   =$_GET['referal_code'];
    $couponcode     =$_GET['couponcode'];
    
    // error_log(print_r($_GET, TRUE));
    
	$sql6 = "SELECT STUD_COURSE_DETAIL_ID from student_course_details WHERE STUDENT_ID = '$user_id' AND  INSTITUTE_COURSE_ID = '$course_id' ORDER BY STUD_COURSE_DETAIL_ID ASC";
	$res6 = $db -> execQuery($sql6);

    if($res6->num_rows == 0)
	{

    
	$sql = "SELECT STUDENT_ID, INSTITUTE_ID, STUDENT_FNAME, STUDENT_EMAIL,STUDENT_MOBILE from student_details WHERE STUDENT_ID = '$user_id' AND  DELETE_FLAG = '0' ORDER BY STUDENT_ID ASC";
	$res = $db -> execQuery($sql);
	$data = $res->fetch_assoc();
	
	$STUDENT_FNAME = $data['STUDENT_FNAME'];
	$STUDENT_EMAIL = $data['STUDENT_EMAIL'];
	$STUDENT_MOBILE = $data['STUDENT_MOBILE'];
	$INSTITUTE_ID = $data['INSTITUTE_ID'];
	
	 if($referal_code != ''){
     	$sql5 = "SELECT STUDENT_ID, STUDENT_CODE  from student_details WHERE STUDENT_CODE = '$referal_code' AND  DELETE_FLAG = '0' ORDER BY STUDENT_ID ASC";
    	$res5 = $db -> execQuery($sql5);
    	$data5 = $res5->fetch_assoc();
    	$REFERAL_ID = $data5['STUDENT_ID'];
    	$REFERAL_CODE = $data5['STUDENT_CODE'];
    	
    	if($REFERAL_ID != $user_id && $referal_code == $REFERAL_CODE){
    	    $referal_code = $referal_code;
    	}else{
    	  
    	    $data['message'] = "You Can Not Use Your Code As Referal Code Or Your Enter Code Is Invalid. Please Refer Code To Your Friends To Get Benifits.";
    	     $data['success'] = false;
            $data['response'] = "n";
            $data['error'] = true;
            //error_log(print_r('Location: '.$furl.'?msg='.$data['message'], TRUE));
            header('Location: '.$furl.'?msg='.$data['message']);
            //echo json_encode($data);
    
    	}
    }

?>
<html>
    <head>

  </head>
  <body onload="submitPayuForm()">
    <form name='fr' action='payTestSubscription.php' method='POST'>
        <input type='hidden' name='amount' value='<?=$amount;?>'>
        <input type='hidden' name='firstname' value='<?=$STUDENT_FNAME;?>'>
        <input type='hidden' name='email' value='<?=$STUDENT_EMAIL;?>'>        
        <input type='hidden' name='phone' value='<?=$STUDENT_MOBILE;?>'>        
        <input type='hidden' name='user_id' value='<?=$user_id;?>'>   
        <input type='hidden' name='course_id' value='<?=$course_id;?>'> 
        <input type='hidden' name='user_role' value='4'>   
        <input type='hidden' name='referal_code' value='<?=$referal_code;?>'>  
        <input type='hidden' name='service_provider' value='payu_paisa'> 
        <input type='hidden' name='productinfo' value='Course Purchase'> 
        <input type='hidden' name='inst_id' value='<?= $INSTITUTE_ID ?>'>
        <input type='hidden' name='couponcode' value='<?= $couponcode ?>'>
        </form>
        <script type='text/javascript'>
        function submitPayuForm()
        {
            document.fr.submit();    
        }
        
        </script>
    </body>
</html>
<?php
}else{
    $data['success'] = false;
    $data['response'] = "n";
    $data['error'] = true;
    $data['message'] = "You already purchase this course.";
    //error_log(print_r('Location: '.$furl.'?msg='.$data['message'], TRUE));
    header('Location: '.$furl.'?msg='.$data['message']);
    //echo json_encode($data);
}
}
else
{
    $data['success'] = false;
    $data['response'] = "n";
    $data['error'] = true;
    $data['message'] = "All field required";
   //error_log(print_r('Location: '.$furl.'?msg='.$data['message'], TRUE));
    header('Location: '.$furl.'?msg='.$data['message']);
   // echo json_encode($data);
}
?>