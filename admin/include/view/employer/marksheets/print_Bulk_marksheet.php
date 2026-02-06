<?php
ini_set("memory_limit", "128M");

$checkstud = isset($_REQUEST['checkstud']) ? $_REQUEST['checkstud'] : '';
//$checkstud = array(2,1,5);
if ($checkstud != '' && !empty($checkstud)) {
	date_default_timezone_set("Asia/Kolkata");
	//include("include/plugins/pdf/mpdf.php");
	include_once('include/classes/exam.class.php');
	$exam 	= new exam();
	$html = '';
	foreach ($checkstud as $id) {
		//$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
		//$id = base64_decode($id);

		$res 	= $exam->list_marksheet_requests($id, '', '', '');
		$data 	= $res->fetch_assoc();

		extract($data);
		//  print_r($data);exit();

		$GRADE = ($GRADE == '' || empty($GRADE)) ? '' : $GRADE;


		$created_by  = $_SESSION['user_fullname'];
		$ip_address  = $_SESSION['ip_address'];
		$today_date		= date('d.m.Y');


		$GRADE = explode(':', $GRADE);
		$GRADE = isset($GRADE[0]) ? $GRADE[0] : '';
		$EXAM_TITLE = explode("(", $AICPE_COURSE_AWARD);


		$tableName 	= "marksheet_details";
		$tabFields 	= "(MARKSHEET_DETAILS_ID,MARKSHEET_REQUEST_ID, ISSUE_DATE, INSTITUTE_ID, STUDENT_ID,INSTITUTE_NAME,STUDENT_NAME,STUDENT_FATHER_NAME,STUDENT_MOTHER_NAME,MARKS_OBTAINED,MARKSHEET_MARKS,MARKSHEET_SUBJECT,COURSE_NAME,GRADE,COURSE_DURATION,MARKS_PER,CREATED_BY, CREATED_ON, CREATED_ON_IP)";

		$insertVals	= "(NULL,'$MARKSHEET_REQUEST_ID',NOW(),'$INSTITUTE_ID','$STUDENT_ID','$INSTITUTE_NAME','$STUDENT_NAME','$STUDENT_FATHER_NAME','$STUDENT_MOTHERNAME','$MARKS_OBTAINED','$MARKSHEET_MARKS','$MARKSHEET_SUBJECT','$COURSE_NAME','$GRADE','$COURSE_DURATION','$MARKS_PER','$created_by',NOW(),'$ip_address')";

		$insertSql	= $db->insertData($tableName, $tabFields, $insertVals);
		$exSql		= $db->execQuery($insertSql);

		$last_insert_id = $db->last_id();
		$marksheet_prefix = $access->generate_marksheet_prefix($INSTITUTE_ID, $STUDENT_ID, $last_insert_id);
		$marksheetno = $marksheet_prefix . '' . $last_insert_id;

		$sql1 = "UPDATE marksheet_details SET MARKSHEET_SERIAL_NO='$last_insert_id', MARKSHEET_PREFIX='$marksheet_prefix', MARKSHEET_NO='$marksheetno' WHERE MARKSHEET_DETAILS_ID='$last_insert_id'";
		$exSql1		= $db->execQuery($sql1);

		//change certificate request status
		$sql1 = "UPDATE marksheet_requests SET REQUEST_STATUS='2' WHERE MARKSHEET_REQUEST_ID=$MARKSHEET_REQUEST_ID";
		$exSql1		= $db->execQuery($sql1);
		//==============================================================




		//==============================================================
		//$mpdf->WriteHTML($html);
		//$mpdf->Output($file,'F');
		//send sms to student
		// $mobile = $db->get_user_mobile($STUDENT_ID,4);
		// $message = "Dear $STUDENT_NAME\r\nCongratulations !!!\r\nYour DITRP Result of $EXAM_TITLE is Approved, The result is $MARKS_PER %, Grade '$GRADE'\r\nKindly collect your Certificate from $INSTITUTE_NAME, $INSTITUTE_CITY after 15 days.\r\nDITRP\r\n".WEBSITE;
		// $access->trigger_sms($message,$mobile);
	}
	//send sms to institute
	$no_stud = count($checkstud);
	$inst_mobile = $db->get_user_mobile($INSTITUTE_ID, 2);
	$inst_owner_name = $db->get_owner_fullname($INSTITUTE_ID, 2);
	$message = "Dear $inst_owner_name\r\nCongratulations !!!\r\nThe result of $no_stud students is processed. We will dispatch the Certificates very shortly.\r\nDITRP\r\n" . WEBSITE_MOBILE1;
	$access->trigger_sms($message, $inst_mobile);
	header('location:page.php?page=list-requested-msheet');
	exit;
}
