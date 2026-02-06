<?php

ob_clean();
ob_start();
ini_set("memory_limit", "128M");

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

include_once('include/classes/tools.class.php');
$tools = new tools();

$resB = $tools->list_backgroundimages('', $user_id, '');
if ($resB != '') {
	$srno = 1;
	while ($dataB = $resB->fetch_assoc()) {
		extract($dataB);
		$hallticket_image     = BACKGROUND_IMAGE_PATH . '/' . $inst_id . '/' . $hallticket_image;
	}
}

include('include/classes/exam.class.php');
$exam = new exam();


$examdate = isset($_REQUEST['examdate']) ? $_REQUEST['examdate'] : '';

$starthh = isset($_REQUEST['starthh']) ? $_REQUEST['starthh'] : '';
$startmm = isset($_REQUEST['startmm']) ? $_REQUEST['startmm'] : '';
$starttime = isset($_REQUEST['starttime']) ? $_REQUEST['starttime'] : '';

$endhh = isset($_REQUEST['endhh']) ? $_REQUEST['endhh'] : '';
$endmm = isset($_REQUEST['endmm']) ? $_REQUEST['endmm'] : '';
$endtime = isset($_REQUEST['endtime']) ? $_REQUEST['endtime'] : '';

//$duration = isset($_REQUEST['duration'])?$_REQUEST['duration']:'';

$reporthh = isset($_REQUEST['reporthh']) ? $_REQUEST['reporthh'] : '';
$reportmm = isset($_REQUEST['reportmm']) ? $_REQUEST['reportmm'] : '';
$reporttime = isset($_REQUEST['reporttime']) ? $_REQUEST['reporttime'] : '';

$checkstud = isset($_REQUEST['checkstud']) ? $_REQUEST['checkstud'] : '';
date_default_timezone_set("Asia/Kolkata");


$arr = rtrim(implode(",", $checkstud), ",");

include("include/plugins/pdf/mpdf.php");
//print_r($_REQUEST);

$mpdf = new mPDF('c', 'A4', '', '', 0, 0, 0, 0, 16, 13);


$file = "sample.pdf";

foreach ($checkstud as $val) {

	$res2 = $access->list_student_details_hallticket($val, ' ORDER BY STUD_COURSE_DETAIL_ID DESC');

	if ($res2 != '') {
		while ($data2 = $res2->fetch_assoc()) {
			//print_r($data2);exit();
			$STUD_COURSE_DETAIL_ID 	= $data2['STUD_COURSE_DETAIL_ID'];
			$STUDENT_ID 	= $data2['STUDENT_ID'];
			$INSTITUTE_ID 		= $data2['INSTITUTE_ID'];
			$INSTITUTE_COURSE_ID 		= $data2['INSTITUTE_COURSE_ID'];

			$COURSE_NAME 	= $db->get_inst_course_name($INSTITUTE_COURSE_ID);

			$duration 	= $db->get_inst_course_duration($INSTITUTE_COURSE_ID);

			$examduration = $db->get_inst_exam_duration($INSTITUTE_COURSE_ID);

			$student_details = $exam->list_hallticket($STUDENT_ID, $INSTITUTE_ID, $INSTITUTE_COURSE_ID);

			$studentData = $student_details->fetch_assoc();

			include_once('include/classes/student.class.php');
			$student = new student();
			$res3 = $student->list_student($STUDENT_ID, $INSTITUTE_ID, '');
			if ($res3 != '') {
				$srno = 1;
				while ($data3 = $res3->fetch_assoc()) {

					$USER_NAME		= $data3['USER_NAME'];



					//	print_r($studentData); exit();

					$STUD_PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $studentData['STUDENT_PHOTO'];

					$html = '
		<style>
		#rcorners{position:absolute; margin:400px; right:55px;border:1px solid #333; top:0.2%;padding:5px; border-radius:25px;width:30%; height:5%; text-align:bottom;	
		};

		  #rcor{position:absolute; margin:20px; border:1px solid #333; top:1%;padding:5px; border-radius:25px;width:30%; height:10%; text-align:bottom;	margin-left:60%;	
		};
	   
	body {font-family:Arial; font-size: 12pt;position:absolute;z-index:0;top:0px; width:100%;}
	.studphoto{position:absolute;  width:135px; height:135px;background-image:url("' . $STUD_PHOTO . '"); background-size:135px 135px; background-repeat:no-repeat; top:23.9%; left:6.5%;}
    
    .studusername {position:absolute; top:355px; left:45%; font-size:10pt;}
    .studpassword {position:absolute; top:380px; left:45%; font-size:10pt;}
    
	.studname {position:absolute; top:430px; left:26%; font-size:10pt;}
	.fathername{position:absolute; top:478px; left:26%; font-size:10pt;}
	.surname{position:absolute; top:525px; left:26%; font-size:10pt;}
	.mothername{position:absolute; top:575px; left:26%; font-size:10pt;}

	.coursename{position:absolute; top:24.9%; left:40%; font-size:8pt; width:35%}

	.courseduration{position:absolute; top:27.5%; left:40%; font-size:8pt; width:35%}
	
	.examcenteraddress{position:absolute; top:55.8%; left:43%; font-size:8px; width:45%; line-height:10px}
	.institutename{position:absolute; top:54.7%; left:43%; font-size:7pt;}
	.centercode{position:absolute; top:48.5%; left:77%; font-size:9pt;}
	
	.examdate{position:absolute; top:38.3%; left:67%; font-size:9pt;}
	.examtime{position:absolute; top:42.5%; left:67%; font-size:9pt;}
	.examduration{position:absolute; top:46.6%; left:67%; font-size:9pt;}
	.reportingtime{position:absolute; top:50.9%; left:67%; font-size:9pt;}
	    
	.institutecontactnumber{position:absolute; top:62.5%; left:77%; font-size:9pt;}


	</style> ';
					$html .= '<img src="' . $hallticket_image . '" style="width:100%" />';
					$html .= "<div class='studphoto'></div>";

					$html .= "<div class='studusername'><b>" . $USER_NAME . "</b></div>";

					$html .= "<div class='studname'><b>" . $studentData['STUDENT_FNAME'] . "</b></div>";
					$html .= "<div class='fathername'><b>" . $studentData['STUDENT_MNAME'] . "</b></div>";
					$html .= "<div class='surname'><b>" . $studentData['STUDENT_LNAME'] . "</b></div>";
					$html .= "<div class='mothername'><b>" . $studentData['STUDENT_MOTHERNAME'] . "</b></div>";

					$html .= "<div class='coursename'><b>" . $COURSE_NAME . "</b></div>";

					$html .= "<div class='courseduration'><b>" . $duration . "</b></div>";

					$html .= "<div class='examcenteraddress'><b>" . $studentData['ADDRESS_LINE1'] . " " . $studentData['TALUKA'] . " " . $studentData['CITY'] . " " . $studentData['STATE'] . "  " . $studentData['POSTCODE'] . ". Contact : " . $studentData['MOBILE'] . "</b></div>";

					$html .= "<div class='institutename'><b>" . $studentData['INSTITUTE_NAME'] . "</b></div>";

					//$html .="<div class='centercode'><b>".$studentData['INSTITUTE_CODE']."</b></div>";

					$html .= "<div class='examdate'><b>" . date("d/m/Y", strtotime($examdate)) . "</b></div>";

					$html .= "<div class='examtime'><b>" . $starthh . ":" . $startmm . " " . $starttime . " TO " . $endhh . ":" . $endmm . " " . $endtime . "</b></div>";

					$html .= "<div class='examduration'><b>" . $examduration . " MINS </b></div>";

					$html .= "<div class='reportingtime'><b>" . $reporthh . ":" . $reportmm . " " . $reporttime . " </b></div>";

					//$html .="<div class='institutecontactnumber'><b>".$studentData['MOBILE']."</b></div>";


				}
			}
		}
		$mpdf->WriteHTML($html);
		$mpdf->AddPage();
	}
}
$mpdf->Output($file, 'I');


ob_end_flush();
ob_flush();
