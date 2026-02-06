<?php

include('/home4/kzqhujmy/public_html/ditrpselfstudy/admin/include/classes/database_results.class.php');
include('/home4/kzqhujmy/public_html/ditrpselfstudy/admin/include/classes/access.class.php');

$db 	= new  database_results();
$access = new  access();

//error_log(print_r($_POST,true));

$status         =$_POST["status"];
$firstname      =$_POST["firstname"];
$amount         =$_POST["amount"];
$txnid          =$_POST["txnid"];
$posted_hash    =$_POST["hash"];
$key            =$_POST["key"];
$productinfo    =$_POST["productinfo"];
$email          =$_POST["email"];

$phone          =$_POST["phone"];
$user_id        =$_POST["udf1"];
$course_id      =$_POST["udf2"];
$referal_code   =$_POST["udf3"];
$inst_id        =$_POST["udf4"];
$couponcode     =$_POST["udf5"];

$referal_id = '';

//error_log(print_r($_POST, TRUE));

if($referal_code != ''){
$sql = "SELECT STUDENT_ID  from student_details WHERE STUDENT_CODE = '$referal_code' AND  DELETE_FLAG = '0' ORDER BY STUDENT_ID ASC";
$res = $db -> execQuery($sql);

//error_log(print_r($sql, TRUE));

$data1 = $res->fetch_assoc();
$referal_id = $data1['STUDENT_ID'];
}
$sql143 = "SELECT VOLUNTEER  from student_details WHERE STUDENT_ID = '$user_id' AND  DELETE_FLAG = '0' ORDER BY STUDENT_ID ASC";
$res143 = $db -> execQuery($sql143);

//error_log(print_r($sql143, TRUE));

$data143 = $res143->fetch_assoc();
$volunteer_status = $data143['VOLUNTEER'];

$sql2 = "SELECT COURSE_FEES from aicpe_courses WHERE COURSE_ID = '$course_id' AND  DELETE_FLAG = '0' ORDER BY COURSE_ID ASC";
$res2 = $db -> execQuery($sql2);

//error_log(print_r($sql2, TRUE));

$data2 = $res2->fetch_assoc();
$courseamount = $data2['COURSE_FEES'];

$couponamount = 0;

if(!empty($couponcode) && $couponcode !=''){
 $sql13 = "SELECT DISCOUNT_PRICE from discount_coupons WHERE COUPON_NAME = '$couponcode' AND  DELETE_FLAG = '0' ORDER BY COUPON_ID ASC";
$res13 = $db -> execQuery($sql13);

//error_log(print_r($sql13, TRUE));


$data13 = $res13->fetch_assoc();
$couponamount = $data13['DISCOUNT_PRICE'];
}
	
$total = $courseamount - $couponamount;
$gst = $total * 18/100;

if(isset($_POST["txnid"]) && !empty($_POST["txnid"]))
{
         $sql147 = "INSERT INTO online_payments (PAYMENT_ID,TRANSACTION_ID,USER_ID,USER_ROLE,USER_FULLNAME,USER_EMAIL,USER_MOBILE,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,ACTIVE,CREATED_BY,CREATED_ON,TRANSACTION_TYPE,INSTITUTE_ID,COURSE_ID,REFERAL_ID,REFERAL_CODE,COURSE_FEES,GST,DISCOUNT_CODE,DISCOUNT_AMOUNT)
		 VALUES(NULL,'$txnid','$user_id','4','$firstname','$email','$phone','$amount','ONLINE',NOW(),'$status','1','$firstname',NOW(),'DEBIT','$inst_id','$course_id','$referal_id','$referal_code','$courseamount','$gst','$couponcode','$couponamount')";
		 $res147 = $db->execQuery($sql147);
		 
		 //error_log(print_r($sql147, TRUE));
		 
		$payment_id = $db->last_id();
        					    
		$tableName2 	= "student_course_details";
		$tabFields2 	= "(STUD_COURSE_DETAIL_ID, STUDENT_ID, INSTITUTE_ID, INSTITUTE_COURSE_ID,COURSE_FEES,TOTAL_COURSE_FEES,GST,ONLINE_PAYMENT_ID, DEMO_COUNT,DEMO_ATTEMPT,ACTIVE,DELETE_FLAG, CREATED_BY,CREATED_ON,REFERAL_CODE,REFERAL_ID)";
		$insertVals2	= "(NULL, '$user_id', '$inst_id', '$course_id','$courseamount','$amount','$gst','$payment_id','30','0','1','0','$firstname',NOW(),'$referal_code','$referal_id')";
		$insertSql2		= $db->insertData($tableName2,$tabFields2,$insertVals2);
		$exSql2			= $db->execQuery($insertSql2);
        	
        //	error_log(print_r($insertSql2, TRUE));
        	
        $institute_amount ='';	
        $student_amount = '';
        if($volunteer_status == '0' || $volunteer_status == '2'){
             $institute_amount = $total * 40/100;
        }elseif($volunteer_status == '1'){
            $institute_amount = $total * 20/100;
        }
		$student_amount = 200;
		
		 if($exSql2){
		     
    		 $sql10 = "INSERT INTO offline_payments (PAYMENT_ID,TRANSACTION_NO,USER_ID,USER_ROLE,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,PAYMENT_REMARK,ACTIVE,CREATED_ON,TRANSACTION_TYPE,CREATED_BY)
    		 VALUES(NULL,'COMI$txnid','$inst_id','2','$institute_amount','ONLINE',NOW(),'$status','Institute Commission','1',NOW(),'CREDIT','$firstname')";
    		 $res10 = $db->execQuery($sql10);
    		 
    		 //	error_log(print_r($sql10, TRUE));
		     
	     	$updSql = "UPDATE wallet SET TOTAL_BALANCE = TOTAL_BALANCE + $institute_amount WHERE USER_ID='$inst_id' AND USER_ROLE = '2'";
			$exsql = $db->execQuery($updSql);
			
			//	error_log(print_r($updSql, TRUE));
				
			$sql11 = "INSERT INTO offline_payments (PAYMENT_ID,TRANSACTION_NO,USER_ID,USER_ROLE,PAYMENT_AMOUNT,PAYMENT_MODE,PAYMENT_DATE,PAYMENT_STATUS,PAYMENT_REMARK,ACTIVE,CREATED_ON,TRANSACTION_TYPE,CREATED_BY)
    		 VALUES(NULL,'REF$txnid','$referal_id','4','$student_amount','ONLINE',NOW(),'$status','Referal Amount','1',NOW(),'CREDIT','$firstname')";
    		 $res11 = $db->execQuery($sql11);
    		 
    		 	//error_log(print_r($sql11, TRUE));
			
			$updSql1 = "UPDATE wallet SET TOTAL_BALANCE = TOTAL_BALANCE + $student_amount WHERE USER_ID='$referal_id' AND USER_ROLE = '4'";
			
			//	error_log(print_r($updSql1, TRUE));
				
			$exsql1 = $db->execQuery($updSql1);
		 }
    
    $data['response'] = "y";
    $data['error'] = false;
    $data['message'] = "Course purchased successfully";
    echo json_encode($data);
}
else
{
    $data["response"] = 'n';
    $data['error'] = true;
    $data['message'] = "All field required";
    echo json_encode($data);
}
?>