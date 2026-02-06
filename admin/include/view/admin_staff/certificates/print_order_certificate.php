<?php
ini_set("memory_limit", "128M");

$checkstud = isset($_REQUEST['checkstud']) ? $_REQUEST['checkstud'] : '';
//print_r($checkstud); exit();
//$checkstud = array(2);
if ($checkstud != '' && !empty($checkstud)) {
	date_default_timezone_set("Asia/Kolkata");
	//include("include/plugins/pdf/mpdf.php");
	include_once('include/classes/exam.class.php');
	$exam 	= new  exam();
	$html = '';

	foreach ($checkstud as $id) {
		//$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
		//$id = base64_decode($id);
		$res 	= $exam->list_order_certificates_requests($id, '', '', '');
		$data 	= $res->fetch_assoc();

		extract($data);
		//print_r($data); exit();
		$GRADE = ($GRADE == '' || empty($GRADE)) ? '' : $GRADE;


		$created_by  = $_SESSION['user_fullname'];
		$ip_address  = $_SESSION['ip_address'];
		$today_date		= date('d.m.Y');
		$filepath		= CERTIFICATE_PATH;
		if (!file_exists($filepath)) {
			@mkdir($filepath, 0777, true);
		}
		$rand = $access->getRandomCode(6);
		$filename = $STUDENT_CODE . '_' . $INSTITUTE_CODE . '_' . $rand;
		$certificatefile = $filename . '.pdf';
		$file		= $filepath . '/' . $certificatefile;

		$FILE_NAME = '';
		$FILE_CATEGORY = '';
		$FILE_DESC = '';
		$sql = "SELECT FILE_NAME,FILE_DESC,FILE_LABEL,FILE_CATEGORY FROM student_files WHERE STUDENT_ID='$STUDENT_ID' AND FILE_LABEL='photo_identity' AND  ACTIVE=1 AND DELETE_FLAG=0 LIMIT 0,1";
		$res = $db->execQuery($sql);
		if ($res && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				$FILE_NAME = $data['FILE_NAME'];
				$FILE_DESC = $data['FILE_DESC'];
				$FILE_CATEGORY = $data['FILE_CATEGORY'];
				$FILE_LABEL = $data['FILE_LABEL'];
			}
		}
		$GRADE = explode(':', $GRADE);
		$GRADE = isset($GRADE[0]) ? $GRADE[0] : '';
		if ($AICPE_COURSE_AWARD != '') {
			$EXAM_TITLE = explode("(", $AICPE_COURSE_AWARD);
		}

		if ($AICPE_COURSE_AWARD1 != '') {
			$EXAM_TITLE = explode("(", $AICPE_COURSE_AWARD1);
		}

		$EXAM_TITLE = isset($EXAM_TITLE[0]) ? $EXAM_TITLE[0] : '';

		/*$STUD_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;*/

		$STUD_PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;

		$type = pathinfo($STUD_PHOTO, PATHINFO_EXTENSION);
		$cert_photo = $filename . '.' . $type;
		$STUD_PHOTO_CERT = CERTIFICATE_PATH . '/photos/' . $cert_photo;

		@copy($STUD_PHOTO, $STUD_PHOTO_CERT);

		$INSTITUTE_NAME = $db->test($INSTITUTE_NAME);
		$STUDENT_NAME = $db->test($STUDENT_NAME);
		$STUDENT_FNAME = $db->test($STUDENT_FNAME);
		$STUDENT_MNAME = $db->test($STUDENT_MNAME);
		$STUDENT_LNAME = $db->test($STUDENT_LNAME);
		$STUDENT_MOTHERNAME = $db->test($STUDENT_MOTHERNAME);
		$EXAM_TITLE = $db->test($EXAM_TITLE);
		$SUBJECT = $db->test($SUBJECT);

		$tableName 	= "certificates_order_details";
		$tabFields 	= "(CERTIFICATE_DETAILS_ID,CERTIFICATE_REQUEST_ID,CERTIFICATE_FILE, ISSUE_DATE, INSTITUTE_ID, STUDENT_ID, COURSE_ID,MULTI_SUB_COURSE_ID, INSTITUTE_NAME,STUDENT_NAME,STUDENT_FNAME, STUDENT_MNAME, STUDENT_LNAME,STUDENT_MOTHER_NAME, STUDENT_FATHER_NAME,STUDENT_PHOTO,STUDENT_SIGN,STUDENT_DOB, STUD_ID_PROOF_TYPE,STUD_ID_PROOF_NUMBER, COURSE_NAME,SUBJECT,PRACTICAL_MARKS,OBJECTIVE_MARKS,GRADE,MARKS_PER, CREATED_BY, CREATED_ON, CREATED_ON_IP)";

		$insertVals	= "(NULL,'$CERTIFICATE_REQUEST_ID','$certificatefile',NOW(),'$INSTITUTE_ID','$STUDENT_ID','$COURSE_ID','$MULTI_SUB_COURSE_ID','$INSTITUTE_NAME','$STUDENT_NAME','$STUDENT_FNAME','$STUDENT_MNAME','$STUDENT_LNAME','$STUDENT_MOTHERNAME','$STUDENT_MNAME','$STUDENT_PHOTO','$STUDENT_SIGN','$STUDENT_DOB','$FILE_CATEGORY','$FILE_DESC','$EXAM_TITLE','$SUBJECT','$PRACTICAL_MARKS','$OBJECTIVE_MARKS','$GRADE','$MARKS_PER','$created_by',NOW(),'$ip_address')";

		$insertSql	= $db->insertData($tableName, $tabFields, $insertVals);
		$exSql		= $db->execQuery($insertSql);

		$last_insert_id = $db->last_id();
		//$certificate_prefix= $access->generate_certificate_prefix($INSTITUTE_ID,$STUDENT_ID, $last_insert_id);
		//$certicateno = $certificate_prefix.''.$last_insert_id;

		$res1 	= $exam->list_certificates_requests('', $STUDENT_ID, $INSTITUTE_ID, '');
		$data1 	= $res1->fetch_assoc();
		$cert_req_id        = $data1['CERTIFICATE_REQUEST_ID'];
		$cert_req_master_id = $data1['CERTIFICATE_REQUEST_MASTER_ID'];

		$res2 	= $access->list_printed_certificates('', $cert_req_id, '');
		$data2 	= $res2->fetch_assoc();
		$certificate_prefix        = $data2['CERTIFICATE_PREFIX'];
		$certicateno               = $data2['CERTIFICATE_NO'];

		$sql1 = "UPDATE certificates_order_details SET CERTIFICATE_SERIAL_NO='$last_insert_id', CERTIFICATE_PREFIX='$certificate_prefix', CERTIFICATE_NO='$certicateno' WHERE CERTIFICATE_DETAILS_ID='$last_insert_id'";
		$exSql1		= $db->execQuery($sql1);

		//change certificate request status
		$sql2 = "UPDATE certificate_order_requests SET REQUEST_STATUS='2' WHERE CERTIFICATE_REQUEST_ID='$CERTIFICATE_REQUEST_ID'";
		$exSql2		= $db->execQuery($sql2);
		//==============================================================	


		//==============================================================
		//$mpdf->WriteHTML($html);
		//$mpdf->Output($file,'F');
		//send sms to student
		// $inst_mobile = $db->get_user_mobile($INSTITUTE_ID,2);
		// $mobile = $db->get_user_mobile($STUDENT_ID,4);
		// $message = "Dear $STUDENT_NAME\r\nCongratulations !!!\r\nYour DITRP Result of $EXAM_TITLE is Approved, The result is $MARKS_PER %, Grade '$GRADE'\r\nKindly collect your Certificate from $INSTITUTE_NAME, $INSTITUTE_CITY after 15 days.\r\nDITRP\r\n".$inst_mobile;
		//$access->trigger_sms($message,$mobile);
	}
	//send sms to institute
	//$no_stud = count($checkstud);

	//$inst_owner_name = $db->get_owner_fullname($INSTITUTE_ID,2);
	//$message = "Dear $inst_owner_name\r\nCongratulations !!!\r\nThe result of $no_stud students is processed. We will dispatch the Certificates very shortly.\r\nDITRP\r\n".WEBSITE_MOBILE1;
	//$access->trigger_sms($message,$inst_mobile);
	header('location:page.php?page=listOrderRequestedCertificates');
	//exit;

}
