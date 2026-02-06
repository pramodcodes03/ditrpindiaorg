<?php
ob_clean();
$course = isset($_GET['course']) ? $_GET['course'] : '';
$studid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$inst_id = $db->get_student_institute_id($studid);
date_default_timezone_set("Asia/Kolkata");
//include("include/plugins/pdf/mpdf.php");
//	$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 

include_once('include/plugins/mpdf8/autoload.php');
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'tempDir' => sys_get_temp_dir() . '/mpdf']);
$mpdf->AddPageByArray([
	'margin-left' => 0,
	'margin-right' => 0,
	'margin-top' => 0,
	'margin-bottom' => 0,
]);

$res = $access->list_printed_certificates('', '', ' AND STUDENT_ID="' . $studid . '" AND COURSE_ID="' . $course . '" ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1');

include_once('include/classes/institute.class.php');
$institute 	= new  institute();

include_once('include/classes/tools.class.php');
$tools = new tools();
$resB = $tools->list_backgroundimages('', '1', '');
if ($resB != '') {
	$srno = 1;
	while ($dataB = $resB->fetch_assoc()) {
		extract($dataB);
		$certificate_image    = BACKGROUND_IMAGE_PATH . '/' . $inst_id . '/' . $certificate_image;
	}
}

if ($res != '') {
	while ($data = $res->fetch_assoc()) {
		//print_r($data); exit();
		$CERTIFICATE_DETAILS_ID 	= $data['CERTIFICATE_DETAILS_ID'];
		$CERTIFICATE_REQUEST_ID 	= $data['CERTIFICATE_REQUEST_ID'];
		$CERTIFICATE_SERIAL_NO 		= $data['CERTIFICATE_SERIAL_NO'];
		$CERTIFICATE_PREFIX 		= $data['CERTIFICATE_PREFIX'];
		$CERTIFICATE_NO 			= $data['CERTIFICATE_NO'];
		$CERTIFICATE_FILE 			= $data['CERTIFICATE_FILE'];
		$ISSUE_DATE 				= $data['ISSUE_DATE'];
		$INSTITUTE_ID 				= $data['INSTITUTE_ID'];
		//$INSTITUTE_CITY 			= $data['INSTITUTE_CITY'];
		$STUDENT_ID 				= $data['STUDENT_ID'];
		$STUDENT_PHOTO 				= $data['STUDENT_PHOTO'];
		$STUD_PHOTO 				= $data['STUD_PHOTO'];
		$STUDENT_SIGN 				= $data['STUDENT_SIGN'];

		$STUDENT_FNAME 				= $data['STUDENT_FNAME'];
		$STUDENT_LNAME 				= $data['STUDENT_LNAME'];

		$COURSE_ID 			= $data['COURSE_ID'];
		$MULTI_SUB_COURSE_ID 			= $data['MULTI_SUB_COURSE_ID'];
		$TYPING_COURSE_ID 			= $data['TYPING_COURSE_ID'];

		$INSTITUTE_NAME 			= $data['INSTITUTE_NAME'];
		$STUDENT_NAME 				= $data['STUDENT_NAME'];
		$STUDENT_FATHER_NAME 		= $data['STUDENT_FATHER_NAME'];
		$STUDENT_MOTHER_NAME 		= $data['STUDENT_MOTHER_NAME'];
		$STUD_ID_PROOF_TYPE 		= $data['STUD_ID_PROOF_TYPE'];
		$STUD_ID_PROOF_NUMBER 		= $data['STUD_ID_PROOF_NUMBER'];
		$COURSE_AWARD 				= isset($data['COURSE_AWARD']) ? $data['COURSE_AWARD'] : '';
		$AICPE_COURSE_AWARD 		= $data['AICPE_COURSE_AWARD'];
		$COURSE_NAME 				= $data['COURSE_NAME'];
		$GRADE 						= $data['GRADE'];
		$MARKS_PER 					= $data['MARKS_PER'];
		$ACTIVE 					= $data['ACTIVE'];
		$CREATED_BY 				= $data['CREATED_BY'];
		$CREATED_ON					= $data['CREATED_ON'];
		$UPDATED_BY 				= $data['UPDATED_BY'];
		$UPDATED_ON 				= $data['UPDATED_ON'];
		$CREATED_ON_IP 				= $data['CREATED_ON_IP'];
		$UPDATED_ON_IP 				= $data['UPDATED_ON_IP'];
		$COURSE_DURATION 		    = $data['COURSE_DURATION'];
		$ISSUE_DATE_FORMAT 				= $data['ISSUE_DATE_FORMAT'];

		$QRFILE 				= $data['QRFILE'];

		$cert_path = CERTIFICATE_PATH . "/" . $CERTIFICATE_FILE;
		$PHOTO = '../uploads/default_user.png';

		$OWNER_NAME = $data['OWNER_NAME'];

		/*RItesh 12-07-2019 certificate photo path */
		/*if($STUD_PHOTO!='')
				$PHOTO = CERTIFICATE_PATH."/photos/$STUD_PHOTO";*/
		if ($STUD_PHOTO != '')
			$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;

		$STUD_SIGN = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_SIGN;

		$INSTITUTE_CITY = $db->get_institute_city($INSTITUTE_ID);

		$GRADE = explode(':', $GRADE);
		$GRADE = isset($GRADE[0]) ? $GRADE[0] : '';
		$EXAM_TITLE = explode("(", $AICPE_COURSE_AWARD);
		$EXAM_TITLE = isset($EXAM_TITLE[0]) ? $EXAM_TITLE[0] : '';
		$auth_signature = '';
		$idproof = '';
		$courseduration = '';
		$FILE_DESC = isset($STUD_ID_PROOF_NUMBER) ? $STUD_ID_PROOF_NUMBER : '';
		$FILE_CATEGORY = isset($STUD_ID_PROOF_TYPE) ? $STUD_ID_PROOF_TYPE : '';

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

		if (file_exists('resources/dist/img/signaturepng.png'))
			$auth_signature = '<img src="resources/dist/img/signaturepng.png" style="width:150px; height:80px;"/>';


		//$file		= $CERTIFICATE_FILE;

		$file = "";
		if ($QRFILE !== '') {
			$file =  HTTP_HOST_ADMIN . '/' . $QRFILE;
		}

		$sign = $institute->get_institute_docs_all($INSTITUTE_ID, 'sign', false);

		$sign_photo = $sign[0]['file_name'];


		$stamp = $institute->get_institute_docs_all($INSTITUTE_ID, 'stamp', false);

		$stamp_photo = $stamp[0]['file_name'];


		$INSTITUTE_SIGN = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $sign_photo;

		$INSTITUTE_STAMP = INSTITUTE_DOCUMENTS_PATH . '/' . $INSTITUTE_ID . '/' . $stamp_photo;

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
	
	.atcname{position:absolute;top:665px;text-align:center;width:100%; font-size:14px; color:#f90404;text-transform:uppercase}
	
	.grade{position:absolute;top:715px;left:55%; width:100px;}
	.marks{	position:absolute;top:715px;left:69.5%; width:100px; font-size:18px}
	.coursename{position:absolute;top:770px;text-align:center;width:100%; font-size:16px;}
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

		$date_issue = strtotime($ISSUE_DATE);

		//$html .= '<img src="'.$certificate_image.'" style="width:100%;" /> ';

		$html .= '
	 
		<img src="' . $certificate_image . '" style="width:100%" />
		 <h3 class="qrheadtext"><b> FOR ONLINE VERIFICATION SCAN </b></h3>
        <div class="qrcodeimage"><img src="' . $file . '"></div> 

		<div class="studphoto"></div>
		<div class="studsign"></div>
					<h2 class="studname">' . $STUDENT_NAME . '</h2>
					<<h3 class="atcname">' . htmlspecialchars_decode($atcName) . '</h3>
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
                    <div class="weblink">Online Certificate Verification available on : www.ditrpindia.org</div>
                    ';
	}

	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename="' . $file . '"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');

	$mpdf->WriteHTML($html);
	$blankpage = $mpdf->page + 1;
	$mpdf->DeletePages($blankpage);
	$mpdf->Output($STUDENT_FNAME . ' ' . $STUDENT_LNAME . '_certificate.pdf', 'I');
} else {
	header('location:page.php?page=index.php');
	exit;
}
ob_end_flush();
