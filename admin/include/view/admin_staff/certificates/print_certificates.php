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
		$COURSE_ID = 0;
		$MULTI_SUB_COURSE_ID = 0;
		$TYPING_COURSE_ID = 0;
		$PRACTICAL_MARK = 0;
		$OBJECTIVE_MARKS = 0;

		$res 	= $exam->list_certificates_requests($id, '', '', '');
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
			$EXAM_TITLE = isset($EXAM_TITLE[0]) ? $EXAM_TITLE[0] : '';
		}

		if ($AICPE_COURSE_AWARD1 != '') {
			$EXAM_TITLE = explode("(", $AICPE_COURSE_AWARD1);
			$EXAM_TITLE = isset($EXAM_TITLE[0]) ? $EXAM_TITLE[0] : '';
		}

		//$EXAM_TITLE = isset($EXAM_TITLE)?$EXAM_TITLE:'';

		$STUD_PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;

		$INSTITUTE_NAME = $db->test($INSTITUTE_NAME);
		$STUDENT_NAME = $db->test($STUDENT_NAME);
		$STUDENT_FNAME = $db->test($STUDENT_FNAME);
		$STUDENT_MNAME = $db->test($STUDENT_MNAME);
		$STUDENT_LNAME = $db->test($STUDENT_LNAME);
		$STUDENT_MOTHERNAME = $db->test($STUDENT_MOTHERNAME);
		//$EXAM_TITLE = $db->test($EXAM_TITLE);
		$SUBJECT = $db->test($SUBJECT);
		if ($MULTI_SUB_COURSE_ID != '') {
			$MULTI_SUB_COURSE_ID = $MULTI_SUB_COURSE_ID;
		} else {
			$MULTI_SUB_COURSE_ID = 0;
		}
		if ($COURSE_ID != '') {
			$COURSE_ID = $COURSE_ID;
		} else {
			$COURSE_ID = 0;
		}
		if ($TYPING_COURSE_ID != '') {
			$TYPING_COURSE_ID = $TYPING_COURSE_ID;
		} else {
			$TYPING_COURSE_ID = 0;
		}
		if ($PRACTICAL_MARKS != '') {
			$PRACTICAL_MARKS = $PRACTICAL_MARKS;
		} else {
			$PRACTICAL_MARKS = 0;
		}
		if ($OBJECTIVE_MARKS != '') {
			$OBJECTIVE_MARKS = $OBJECTIVE_MARKS;
		} else {
			$OBJECTIVE_MARKS = 0;
		}

		$tableName 	= "certificates_details";
		$tabFields 	= "(CERTIFICATE_DETAILS_ID,CERTIFICATE_REQUEST_ID,CERTIFICATE_FILE, ISSUE_DATE, INSTITUTE_ID, STUDENT_ID, COURSE_ID,MULTI_SUB_COURSE_ID,TYPING_COURSE_ID, INSTITUTE_NAME,STUDENT_NAME,STUDENT_FNAME, STUDENT_MNAME, STUDENT_LNAME,STUDENT_MOTHER_NAME, STUDENT_FATHER_NAME,STUDENT_PHOTO,STUDENT_SIGN,STUDENT_DOB, STUD_ID_PROOF_TYPE,STUD_ID_PROOF_NUMBER, COURSE_NAME,SUBJECT,PRACTICAL_MARKS,OBJECTIVE_MARKS,GRADE,MARKS_PER, CREATED_BY, CREATED_ON, CREATED_ON_IP)";

		$insertVals	= "(NULL,'$CERTIFICATE_REQUEST_ID','$certificatefile',NOW(),'$INSTITUTE_ID','$STUDENT_ID','$COURSE_ID','$MULTI_SUB_COURSE_ID','$TYPING_COURSE_ID','$INSTITUTE_NAME','$STUDENT_NAME','$STUDENT_FNAME','$STUDENT_MNAME','$STUDENT_LNAME','$STUDENT_MOTHERNAME','$STUDENT_MNAME','$STUDENT_PHOTO','$STUDENT_SIGN','$STUDENT_DOB','$FILE_CATEGORY','$FILE_DESC','$EXAM_TITLE','$SUBJECT','$PRACTICAL_MARKS','$OBJECTIVE_MARKS','$GRADE','$MARKS_PER','$created_by',NOW(),'$ip_address')";

		$insertSql	= $db->insertData($tableName, $tabFields, $insertVals);
		$exSql		= $db->execQuery($insertSql);

		$last_insert_id = $db->last_id();
		$certificate_prefix = $access->generate_certificate_prefix($INSTITUTE_ID, $STUDENT_ID, $last_insert_id);
		$certicateno = $certificate_prefix . '' . $last_insert_id;

		//QRCODE	
		include('resources/phpqrcode/qrlib.php');
		$text = STUDENT_CERT_QRURL . 'verify_student=1&code=' . $certicateno;
		$path = 'resources/studentCertificatesQR/' . $STUDENT_ID . '/';
		if (!file_exists($path)) {
			@mkdir($path, 0777, true);
		}
		$file = $path . uniqid() . ".png";
		$ecc = 'L';
		$pixel_Size = 100;
		$frame_Size = 100;
		QRcode::png($text, $file, $ecc, $pixel_Size, $frame_size);
		////////////////////////////////////////////////////////////

		$sql1 = "UPDATE certificates_details SET CERTIFICATE_SERIAL_NO='$last_insert_id', CERTIFICATE_PREFIX='$certificate_prefix', CERTIFICATE_NO='$certicateno',QRFILE = '$file' WHERE CERTIFICATE_DETAILS_ID='$last_insert_id'";
		$exSql1		= $db->execQuery($sql1);

		//change certificate request status
		$sql2 = "UPDATE certificate_requests SET REQUEST_STATUS='2' WHERE CERTIFICATE_REQUEST_ID='$CERTIFICATE_REQUEST_ID'";
		$exSql2		= $db->execQuery($sql2);
		//==============================================================

		if ($MULTI_SUB_COURSE_ID != '') {
			$inst_course_id = $db->get_inst_multi_course_id($MULTI_SUB_COURSE_ID, $INSTITUTE_ID);
			$sql24 = "UPDATE student_course_details SET EXAM_STATUS='3' WHERE STUDENT_ID='$STUDENT_ID' AND INSTITUTE_ID ='$INSTITUTE_ID' AND INSTITUTE_COURSE_ID = '$inst_course_id'";
			$exSql24		= $db->execQuery($sql24);
		}

		//==============================================================
		//$mpdf->WriteHTML($html);
		//$mpdf->Output($file,'F');
		//send sms to student
		$inst_mobile = $db->get_user_mobile($INSTITUTE_ID, 2);
		$mobile = $db->get_user_mobile($STUDENT_ID, 4);
		$message = "Dear $STUDENT_NAME\r\nCongratulations !!!\r\nYour DITRP Result of $EXAM_TITLE is Approved and You can view on our official website, The result is $MARKS_PER %, Grade '$GRADE'\r\nKindly collect your Certificate from $INSTITUTE_NAME, $INSTITUTE_CITY after 15 days.\r\nDITRP\r\n" . $inst_mobile;
		//$access->trigger_sms($message,$mobile);
	}
	//send sms to institute
	$no_stud = count($checkstud);

	$inst_owner_name = $db->get_owner_fullname($INSTITUTE_ID, 2);
	$message = "Dear $inst_owner_name\r\nCongratulations !!!\r\nThe result of $no_stud students is Approved. Please order certificate and marksheet as soon as possible.\r\nDITRP\r\n" . WEBSITE_MOBILE1;
	//$access->trigger_sms($message,$inst_mobile);
	header('location:page.php?page=listRequestedCertificates');
	//exit;

}
