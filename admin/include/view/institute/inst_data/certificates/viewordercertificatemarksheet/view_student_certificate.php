<?php
ob_clean();

$checkstud = isset($_REQUEST['checkstud']) ? $_REQUEST['checkstud'] : '';
$certreq = isset($_REQUEST['certreq']) ? $_REQUEST['certreq'] : '';
$course = isset($_REQUEST['course']) ? $_REQUEST['course'] : '';

$file = "Student Marksheet.pdf";

//$checkstud = array(2);
if ($checkstud != '' && !empty($checkstud)) {
	date_default_timezone_set("Asia/Kolkata");
	include("include/plugins/pdf/mpdf.php");
	include_once('include/classes/exam.class.php');
	$exam 	= new  exam();

	include_once('include/classes/institute.class.php');
	$institute 	= new  institute();

	$html = '';


	$mpdf = new mPDF('c', 'A4', '', '', 0, 0, 0, 0, 16, 13);
	//$id = base64_decode($id);
	$cond = " AND AICPE_COURSE_ID='$course' AND CERTIFICATE_REQUEST_ID='$certreq' AND STUDENT_ID='$checkstud' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1";
	$res = $access->list_order_printed_certificates('', '', $cond);

	//$res 	= $exam->list_certificates_requests($id, '', '', '');
	$data 	= $res->fetch_assoc();

	extract($data);

	$INSTITUTE_CITY = $db->get_institute_city($INSTITUTE_ID);

	$STUD_PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;

	$STUD_SIGN = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_SIGN;

	$type = pathinfo($STUD_PHOTO, PATHINFO_EXTENSION);
	$cert_photo = $filename . '.' . $type;
	$STUD_PHOTO_CERT = CERTIFICATE_PATH . '/photos/' . $cert_photo;

	$sign = $institute->get_institute_docs_all($INSTITUTE_ID, 'sign', false);

	$sign_photo = $sign[0]['file_name'];


	$stamp = $institute->get_institute_docs_all($INSTITUTE_ID, 'stamp', false);

	$stamp_photo = $stamp[0]['file_name'];


	$INSTITUTE_SIGN = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $sign_photo;

	$INSTITUTE_STAMP = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $stamp_photo;

	//==============================================================


	$idproof = '';
	$courseduration = '';
	$cour_dur = '';

	$FILE_DESC = isset($FILE_DESC) ? $FILE_DESC : '';
	$FILE_CATEGORY = isset($FILE_CATEGORY) ? $FILE_CATEGORY : '';

	if ($STUD_ID_PROOF_NUMBER != '' && $STUD_ID_PROOF_TYPE != '') {
		$idproof = '<h4 class="idproof"> ' . htmlspecialchars_decode($STUD_ID_PROOF_TYPE) . ' : ' . htmlspecialchars_decode($STUD_ID_PROOF_NUMBER) . ' </h4>';
	}
	if ($COURSE_DURATION != '') {
		$cour_dur = $COURSE_DURATION;
		$courseduration = '<h3 class="courseduration"> (COURSE DURATION: ' . htmlspecialchars_decode($COURSE_DURATION) . ')</h3>';
	}

	$MULTI_SUB_COURSE_DURATION = $db->get_course_duration_multi_sub($MULTI_SUB_COURSE_ID);
	if ($MULTI_SUB_COURSE_DURATION != '') {
		$cour_dur = $MULTI_SUB_COURSE_DURATION;
		$courseduration = '<h3 class="courseduration"> (COURSE DURATION: ' . htmlspecialchars_decode($MULTI_SUB_COURSE_DURATION) . ')</h3>';
	}
	echo $ISSUE_DATE_FORMAT;
	$date = strtotime($ISSUE_DATE_FORMAT);
	$new_date = strtotime('- ' . $cour_dur, $date);
	$start_date = strtotime('+ 1 day ', $new_date);
	echo $start_course_date = date('d M Y', $start_date);
	echo $end_course_date = date('d M Y', $date);

	if ($COURSE_DURATION != '') {
		$course_period = '<h3 class="courseperiod"> (COURSE PERIOD: ' . $start_course_date . ' TO ' . $end_course_date . ')</h3>';
	}
	if ($MULTI_SUB_COURSE_DURATION != '') {
		$course_period = '<h3 class="courseperiod"> (COURSE PERIOD: ' . $start_course_date . ' TO ' . $end_course_date . ')</h3>';
	}

	$html = '
	<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.studname{position:absolute;top:590px;text-align:center;width:100%;}
	.idproof{position:absolute;top:620px;text-align:center;width:100%;font-weight:normal}
	
	.atcname{position:absolute;top:670px;text-align:center;width:100%; font-size:12px;color:#f90404;}
	
	.grade{position:absolute;top:710px;left:55%; width:100px;}
	.marks{	position:absolute;top:710px;left:69%; width:100px;}
	.coursename{position:absolute;top:775px;text-align:center;width:100%; font-size:16px;}
		.courseduration{position:absolute;top:795px;text-align:center;width:100%; font-size:10px;font-weight:normal;}
	.certicateno{position:absolute;	bottom:75px;left:35%; font-size:13px}
	.date{position:absolute;bottom:55px;left:35%; font-size:13px}
	
	.courseperiod{position:absolute;top:808px;text-align:center;width:100%; font-size:10px;font-weight:normal;}

	.studphoto{position:absolute;top:375px;right:42.9%;width:118px; height:143px;background-image:url("' . $STUD_PHOTO . '"); background-size:118px 143px; background-repeat:no-repeat; border:2px solid #000;}

	.studsign{position:absolute;top:505px;left:41%;width:135px; height:40px;background-image:url("' . $STUD_SIGN . '"); background-size:135px 40px; background-repeat:no-repeat; border:2px solid #000;}

	.instsign{position:absolute;bottom:105px;left:11%;width:135px; height:40px;background-image:url("' . $INSTITUTE_SIGN . '"); background-size:135px 40px; background-repeat:no-repeat; border:0px solid #000;}

	.inststamp{position:absolute;bottom:125px;left:9%;width:135px; height:40px;background-image:url("' . $INSTITUTE_STAMP . '"); background-size:135px 40px; background-repeat:no-repeat;}
    .weblink{position:absolute;bottom:43px;left:56%; font-size:16px;}
    
    .ownername{position:absolute;bottom:80px;left:11%;  font-weight:normal; font-size:12px;}
    
    .line{position:absolute;bottom:90px;left:11%;}
    .bttext{position:absolute;bottom:65px;left:11%; font-weight:600; font-size:12px;}


	</style>';
	$html .= '
	<img src="resources/dist/img/democertificate.jpg" style="width:100%" />
	<div class="studphoto"></div>
	
	<div class="studsign"></div>
		
				<h2 class="studname">' . $STUDENT_NAME . '</h2>
				<h3 class="atcname">ATC : ' . htmlspecialchars_decode($INSTITUTE_NAME) . ' | ' . htmlspecialchars_decode($INSTITUTE_CITY) . '</h3>
				' . $idproof . '
				<h2 class="grade"> ' . $GRADE . ' </h2>
				<h2 class="marks">' . $MARKS_PER . ' %</h2>
				<h3 class="coursename">' . htmlspecialchars_decode($COURSE_NAME) . '</h3>
				' . $courseduration . '<br/> ' . $course_period . '
				<h3 class="certicateno">Certificate No : ' . $CERTIFICATE_NO . '</h3>
				<h3 class="date">  Date Of Issue : ' . $ISSUE_DATE_FORMAT . '</h3>
				<div class="weblink"> www.ditrppro.com </div>
				
			<div class="instsign"></div>

			<div class="line">---------------------------------------------</div>
				<div class="ownername">' . $OWNER_NAME . '</div>
			    <div class="bttext">Controller Of Examination</div>
			    
			
				';

	//==============================================================
	$mpdf->WriteHTML($html);
	$mpdf->Output($file, 'I');
	//send sms to student
	$mobile = $db->get_user_mobile($STUDENT_ID, 4);
	$message = "Dear $STUDENT_NAME\r\nCongratulations !!!\r\nYour DITRP Result of $EXAM_TITLE is Approved. Your score is $MARKS_PER %, Grade '$GRADE'\r\nKindly collect your Certificate from $INSTITUTE_NAME, $INSTITUTE_CITY after 15 days.\r\nDITRP\r\n" . SUPPORT_NO;
	//$access->trigger_sms($message,$mobile);

	//send sms to institute
	$no_stud = count($checkstud);
	$inst_mobile = $db->get_user_mobile($INSTITUTE_ID, 2);
	$inst_owner_name = $db->get_owner_fullname($INSTITUTE_ID, 2);
	$message = "Dear $inst_owner_name\r\nCongratulations !!!\r\nThe result of $no_stud students is processed. We will dispatch the Certificates soon.\r\nDITRP\r\n" . SUPPORT_NO;
	//$access->trigger_sms($message,$inst_mobile);
	//header('location:page.php?page=list-requested-certificates');
	//exit;

}
ob_end_flush();
