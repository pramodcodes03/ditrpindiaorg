<?php
ob_clean();
ob_start();

$checkstud = isset($_REQUEST['checkstud']) ? $_REQUEST['checkstud'] : '';
$certreq = isset($_REQUEST['certreq']) ? $_REQUEST['certreq'] : '';
$course = isset($_REQUEST['course']) ? $_REQUEST['course'] : '';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$course_multi_sub = isset($_REQUEST['course_multi_sub']) ? $_REQUEST['course_multi_sub'] : '';
$course_typing = isset($_REQUEST['course_typing']) ? $_REQUEST['course_typing'] : '';

//$checkstud = array(2);
if ($checkstud != '' && !empty($checkstud)) {
	date_default_timezone_set("Asia/Kolkata");
	//include("include/plugins/pdf/mpdf.php");
	include_once('include/plugins/mpdf8/autoload.php');
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
	$mpdf->AddPageByArray([
		'margin-left' => 0,
		'margin-right' => 0,
		'margin-top' => 0,
		'margin-bottom' => 0,
	]);
	include_once('include/classes/exam.class.php');
	$exam 	= new  exam();

	include_once('include/classes/institute.class.php');
	$institute 	= new  institute();

	include_once('include/classes/tools.class.php');
	$tools = new tools();

	$html = '';

	$resB = $tools->list_backgroundimages('', $user_id, '');
	if ($resB != '') {
		$srno = 1;
		while ($dataB = $resB->fetch_assoc()) {
			extract($dataB);
			$imageId = $dataB['inst_id'];
			$certificate_image = $dataB['certificate_image'];
			$certificate_image    = BACKGROUND_IMAGE_PATH . '/' . $imageId . '/' . $certificate_image;
		}
	}


	///$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
	//$id = base64_decode($id);
	if ($course !== '' && !empty($course)) {
		$cond = " AND COURSE_ID='$course' AND CERTIFICATE_REQUEST_ID='$certreq' AND STUDENT_ID='$checkstud' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1";
	}
	if ($course_multi_sub !== '' && !empty($course_multi_sub)) {
		$cond = " AND MULTI_SUB_COURSE_ID='$course_multi_sub' AND CERTIFICATE_REQUEST_ID='$certreq' AND STUDENT_ID='$checkstud' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1";
	}
	if ($course_typing !== '' && !empty($course_typing)) {
		$cond = " AND TYPING_COURSE_ID='$course_typing' AND CERTIFICATE_REQUEST_ID='$certreq' AND STUDENT_ID='$checkstud' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1";
	}

	$res = $access->list_printed_certificates('', $certreq, $cond);

	//$res 	= $exam->list_certificates_requests($id, '', '', '');
	$data 	= $res->fetch_assoc();

	extract($data);
	//print_r($data); exit();

	$INSTITUTE_CITY = $db->get_institute_city($INSTITUTE_ID);

	$type = pathinfo($STUDENT_PHOTO, PATHINFO_EXTENSION);
	$cert_photo = $filename . '.' . $type;

	$PHOTO = '../uploads/default_user.png';

	if ($STUDENT_PHOTO != '')
		$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;
	//==============================================================
	$STUD_SIGN = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_SIGN;

	$sign = $institute->get_institute_docs_all($INSTITUTE_ID, 'sign', false);

	$sign_photo = $sign[0]['file_name'];


	$stamp = $institute->get_institute_docs_all($INSTITUTE_ID, 'stamp', false);

	$stamp_photo = $stamp[0]['file_name'];


	$INSTITUTE_SIGN = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $sign_photo;

	$INSTITUTE_STAMP = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $stamp_photo;

	$file = "";
	if ($QRFILE !== '') {
		$file = HTTP_HOST_ADMIN . '/' . $QRFILE;
	}

	$idproof = '';
	$courseduration = '';
	$FILE_DESC = isset($FILE_DESC) ? $FILE_DESC : '';
	$FILE_CATEGORY = isset($FILE_CATEGORY) ? $FILE_CATEGORY : '';

	if ($STUD_ID_PROOF_NUMBER != '' && $STUD_ID_PROOF_TYPE != '') {
		$idproof = '<h4 class="idproof"> ' . htmlspecialchars_decode($STUD_ID_PROOF_TYPE) . ' : ' . htmlspecialchars_decode($STUD_ID_PROOF_NUMBER) . ' </h4>';
	}
	$COURSE_DURATION = $db->get_course_duration($COURSE_ID);
	if ($COURSE_DURATION != '') {
		$cour_dur = $COURSE_DURATION;
		$courseduration = '<h3 class="courseduration"> (COURSE DURATION: ' . htmlspecialchars_decode($COURSE_DURATION) . ')</h3>';
	}

	$MULTI_SUB_COURSE_DURATION = $db->get_course_duration_multi_sub($MULTI_SUB_COURSE_ID);
	if ($MULTI_SUB_COURSE_DURATION != '') {
		$cour_dur = $MULTI_SUB_COURSE_DURATION;
		$courseduration = '<h3 class="courseduration"> (COURSE DURATION: ' . htmlspecialchars_decode($MULTI_SUB_COURSE_DURATION) . ')</h3>';
	}

	$TYPING_COURSE_DURATION = $db->get_course_duration_typing($TYPING_COURSE_ID);
	if ($TYPING_COURSE_DURATION != '') {
		$cour_dur = $TYPING_COURSE_DURATION;
		$courseduration = '<h3 class="courseduration"> (COURSE DURATION: ' . htmlspecialchars_decode($TYPING_COURSE_DURATION) . ')</h3>';
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
	if ($TYPING_COURSE_DURATION != '') {
		$course_period = '<h3 class="courseperiod"> (COURSE PERIOD: ' . $start_course_date . ' TO ' . $end_course_date . ')</h3>';
	}

	if ($INSTITUTE_ID != 1) {
		$mainInstituteName = $db->get_institute_name(1);
	} else {
		$mainInstituteName = $INSTITUTE_NAME;
	}

	if ($INSTITUTE_ID != 1) {
		$atcName = " ATC : " . $INSTITUTE_NAME . " | $INSTITUTE_CITY ";
	} else {
		$atcName = $INSTITUTE_NAME . " | $INSTITUTE_CITY ";
	}

	$html = '
	<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.studname{position:absolute;top:605px;text-align:center;width:100%;}
	.idproof{position:absolute;top:625px;text-align:center;width:100%;font-weight:normal}
	
	.atcname{position:absolute;top:674px;text-align:center;width:100%; font-size:14px; color:#f90404;text-transform:uppercase}
	
	.grade{position:absolute;top:720.7px;left:55%; width:100px;}
	.marks{	position:absolute;top:724px;left:70.5%; width:100px; font-size:18px}
	.coursename{position:absolute;top:771px;text-align:center;width:100%; font-size:16px;}
		.courseduration{position:absolute;top:795px;text-align:center;width:100%; font-size:10px;font-weight:normal;}
	.certicateno{position:absolute;	bottom:80px;left:39%; font-size:13px;}
	.date{position:absolute;bottom:60px;left:39%; font-size:13px;}
	
	.courseperiod{position:absolute;top:810px;text-align:center;width:100%; font-size:10px;font-weight:normal;}
	
	.studphoto{position:absolute;top:420px;right:42.9%;width:80px; height:100px;background-image:url("' . $PHOTO . '"); background-size:80px 100px; background-repeat:no-repeat; border:2px solid #000;}

	.studsign{position:absolute;top:520px;left:45%;width:100px; height:35px;background-image:url("' . $STUD_SIGN . '"); background-size:100px 35px; background-repeat:no-repeat; border:2px solid #000;}

	.instsign{position:absolute;bottom:105px;left:8%;width:135px; height:40px;background-image:url("' . $INSTITUTE_SIGN . '"); background-size:135px 40px; background-repeat:no-repeat; border:0px solid #000;}

	.inststamp{position:absolute;bottom:125px;left:9%;width:135px; height:40px;background-image:url("' . $INSTITUTE_STAMP . '"); background-size:135px 40px; background-repeat:no-repeat;}
    .weblink{position:absolute;top:94%;left:29%; font-size:12px;}
    
    .ownername{position:absolute;bottom:80px;left:8%;  font-weight:normal; font-size:12px;}
    
    .line{position:absolute;bottom:90px;left:8%;}
    .bttext{position:absolute;bottom:65px;left:8%; font-weight:600; font-size:12px;}

	.qrheadtext{position:absolute;top:30.5%;left:73.5%; font-size:12px;font-weight:900; text-align:center; width:150px;}
    .qrcodeimage{position:absolute;top:35.1%;width:65px; height:65px; text-align:center; float:right; left:78%;}
    
    .bottomatcname{position:absolute;bottom:230px;text-align:center;width:100%; font-size:14px; color:#f90404;text-transform:uppercase}
	</style>';
	$html .= '
<!-- <img src="' . $certificate_image . '" style="width:100%" /> -->
 <h3 class="qrheadtext"><b> FOR ONLINE VERIFICATION SCAN </b></h3>
<div class="qrcodeimage"><img src="' . $file . '"></div> 
	<div class="studphoto"></div>
	<div class="studsign"></div>
				<h2 class="studname">' . $STUDENT_NAME . '</h2>
				<h3 class="atcname">' . htmlspecialchars_decode($atcName) . '</h3>
				' . $idproof . '
				<h2 class="grade"> ' . $GRADE . ' </h2>
				<h2 class="marks">' . $MARKS_PER . ' %</h2>
				<h3 class="coursename">' . htmlspecialchars_decode($COURSE_NAME) . '</h3>
				' . $courseduration . '<br/> ' . $course_period . '
			
				<h3 class="certicateno"> Certificate No : ' . $CERTIFICATE_NO . '</h3>
				<h3 class="date"> Date Of Issue : ' . $ISSUE_DATE_FORMAT . '</h3>
				
				<div class="instsign"></div>
				<div class="line">---------------------------------------------</div>
				<div class="ownername">' . $OWNER_NAME . '</div>
			    <div class="bttext">Controller Of Examination</div> 
			    <div class="weblink">Online Certificate Verification available on : www.ditrp.digitalnexstep.com</div>
			
				';


	//==============================================================
	$mpdf->WriteHTML($html);
	$mpdf->Output($STUDENT_FNAME . ' ' . $STUDENT_LNAME . '_certificate.pdf', 'I');
	//send sms to student
	$mobile = $db->get_user_mobile($STUDENT_ID, 4);
	$message = "Dear $STUDENT_NAME\r\nCongratulations !!!\r\nYour DITRP Result of $EXAM_TITLE is Approved. Your score is $MARKS_PER %, Grade '$GRADE'\r\nKindly collect your Certificate from $INSTITUTE_NAME, $INSTITUTE_CITY after 15 days.\r\nDITRP\r\n" . SUPPORT_NO;
	//	$access->trigger_sms($message,$mobile);

	//send sms to institute
	$no_stud = count($checkstud);
	$inst_mobile = $db->get_user_mobile($INSTITUTE_ID, 2);
	$inst_owner_name = $db->get_owner_fullname($INSTITUTE_ID, 2);
	$message = "Dear $inst_owner_name\r\nCongratulations !!!\r\nThe result of $no_stud students is processed. We will dispatch the Certificates soon.\r\nDITRP\r\n" . SUPPORT_NO;
	//$access->trigger_sms($message,$inst_mobile);
	//header('location:page.php?page=list-requested-certificates');
	//exit;

}
ob_flush();
ob_end_flush();
