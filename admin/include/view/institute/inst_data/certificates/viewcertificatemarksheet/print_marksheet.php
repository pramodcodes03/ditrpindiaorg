<?php
ob_clean();
error_log(1);
$checkstud = isset($_REQUEST['checkstud']) ? $_REQUEST['checkstud'] : '';
$certreq = isset($_REQUEST['certreq']) ? $_REQUEST['certreq'] : '';
$course = isset($_REQUEST['course']) ? $_REQUEST['course'] : '';
$course_multi_sub = isset($_REQUEST['course_multi_sub']) ? $_REQUEST['course_multi_sub'] : '';
$course_typing = isset($_REQUEST['course_typing']) ? $_REQUEST['course_typing'] : '';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
//$file = "Student Marksheet.pdf";

//$checkstud = array(2);
if ($checkstud != '' && !empty($checkstud)) {
	date_default_timezone_set("Asia/Kolkata");
	///include("include/plugins/pdf/mpdf.php");

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
	include('include/classes/exammultisub.class.php');
	$exammultisub = new exammultisub();
	include('include/classes/coursetypingexam.class.php');
	$coursetypingexam = new coursetypingexam();
	$html = '';

	include_once('include/classes/tools.class.php');
	$tools = new tools();


	$resB = $tools->list_backgroundimages('', $user_id, '');
	if ($resB != '') {
		$srno = 1;
		while ($dataB = $resB->fetch_assoc()) {
			extract($dataB);
			if ($course != '' || $course_multi_sub != '') {
				$marksheet_image      = BACKGROUND_IMAGE_PATH . '/' . $inst_id . '/' . $marksheet_image;
			}
			if ($course_typing != '') {
				$marksheet_image      = BACKGROUND_IMAGE_PATH . '/' . $inst_id . '/' . $typingmarksheet_image;
			}
		}
	}




	//$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
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

	//print_r($data);exit();
	$STUD_PHOTO = STUDENT_DOCUMENTS_PATH . $STUDENT_ID . '/' . $STUDENT_PHOTO;

	$type = pathinfo($STUD_PHOTO, PATHINFO_EXTENSION);
	$cert_photo = $filename . '.' . $type;
	$STUD_PHOTO_CERT = CERTIFICATE_PATH . '/photos/' . $cert_photo;



	//==============================================================
	$file = "";
	if ($QRFILE !== '') {
		$file = HTTP_HOST_ADMIN . '/' . $QRFILE;
	}

	$idproof = '';
	$courseduration = '';
	$cour_dur = '';

	$FILE_DESC = isset($FILE_DESC) ? $FILE_DESC : '';
	$FILE_CATEGORY = isset($FILE_CATEGORY) ? $FILE_CATEGORY : '';

	if ($STUD_ID_PROOF_NUMBER != '' && $STUD_ID_PROOF_TYPE != '') {
		$idproof = '<h4 class="idproof"> ' . htmlspecialchars_decode($STUD_ID_PROOF_TYPE) . ' : ' . htmlspecialchars_decode($STUD_ID_PROOF_NUMBER) . ' </h4>';
	}

	$COURSE_DURATION = $db->get_course_duration($COURSE_ID);
	if ($COURSE_DURATION != '') {
		$cour_dur = $COURSE_DURATION;
		$courseduration = '<h3 class="courseduration"> (COURSE DURATION: ' . htmlspecialchars_decode($MULTI_SUB_COURSE_DURATION) . ')</h3>';
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


	$ISSUE_DATE_FORMAT;
	$date = strtotime($ISSUE_DATE_FORMAT);
	$new_date = strtotime('- ' . $cour_dur, $date);
	$start_date = strtotime('+ 1 day ', $new_date);
	$start_course_date = date('d M Y', $start_date);
	$end_course_date = date('d M Y', $date);


	$html2 =
		'<style>
		body {padding:0;font-family: sans-serif; font-size: 9pt;position:absolute;z-index:0;top:0px;}
	
	.studname{position:absolute;top:25.7%;left:12%;width:50%;}
	.fathername{position:absolute;top:27.2%;left:12%; width:50%}
	.surname{position:absolute;top:28.5%;left:12%; width:50%}
	.institudename{position:absolute;top:35%;left:12%;width:100%;font-size: 10pt;text-transform: uppercase;}
	.dob{position:absolute;top:28.4%;left:56%; width:40%}
	
	.courseperiod{position:absolute;top:30%;left:56%; width:40%;}
	
	.coursedur{position:absolute;top:25%;left:56%; width:35%}
	.coursename{position:absolute;top:31.9%;left:12%; border:1px }
	.mothername{position:absolute;top:30%;left:12%; width:50%}
	.marksheetno{position:absolute;top:26.8%;left:56%; width:30%}
	
	.subject{position:fixed;top:42.2%;left:20%; border:1px;width:35%; text-transform: uppercase; text-align:justify; line-height:20px;}
	
	.practicle{position:fixed;top:44.2%;left:63.2%; height:50px;width:30px;font-size:14px; text-align:center; }
	.subjectmark{position:fixed;top:44.2%;left:70.5%; height:50px;width:30px;font-size:14px; text-align:center;}
	.total{position:fixed;top:44.2%;left:78%; height:50px;width:30px;font-size:14px; text-align:center; }

	.total_bottom{position:absolute;top:69%;left:78%; font-size:16px;height:50px;width:50px; }

	.subject1{position:absolute;left:20%;border:1px;width:30%; text-transform: uppercase; text-align:justify; line-height:20px;}
	.practicle1{position:absolute;left:70.2%; height:50px;width:50px;font-size:14px;}
	.subjectmark1{position:absolute;left:78.2%; height:50px;width:50px;font-size:14px; }
	.srno1{position:absolute;left:18%; border:1px;width:30%; text-transform: uppercase; text-align:justify; line-height:20px;}
    table tr.highlight td{padding-top:5px; padding-bottom:5px; font-size:10px; font-weight:bold;}
    
    .outof_bottom{position:absolute;top:69%;left:45%; font-size:11px;height:50px;width:150px; }
    .percentage_bottom{position:absolute;top:69%;left:18%; font-size:11px;height:50px;width:150px;}
    .grade_bottom{position:absolute;top:69%;left:35%; font-size:11px;height:50px;width:100px;}
    
    .period_date{font-weight:normal;font-size: 9pt; }
    
        .subdetails{text-transform:capitalize; margin-left:25px;}
        
     .weblink{position:absolute;top:92%;left:33%; font-size:12px;}
	 .qrheadtext{position:absolute;top:78.5%;left:57%; font-size:10px;font-weight:900; text-align:center; width:170px;}
	 .qrcodeimage{position:absolute;top:79.5%;width:80px; height:80px; text-align:center; float:right; left:56%;}
     
    .subject_name{position:absolute;width:300px;}
    .speed{position:absolute;width:50px;}
    .minimum_marks{position:absolute;width:50px;}
    .exam_total_marks{position:absolute;width:50px;}
    .marks_obtained{position:absolute;width:50px;}
    
    .tablebody{position:absolute;top:425px;border:2px solid #000; width:500px; left:15%; right:15%; height:343px; padding:15px 25px; }
    
    table thead tr {position:absolute;border:2px solid #000;}
    .fixed-cell {
      position: fixed;
    }
    
    .head1{position:fixed;top:38.5%;left:30%; width:30%; text-transform: uppercase; text-align:justify; font-size:18px; font-weight:bold; letter-spacing:5px;}
	.head2{position:fixed;top:38%;left:60%; width:10%; line-height:14px;font-weight:bold; font-size:10px;text-align:center;}
	.head3{position:fixed;top:38%;left:69%; width:8%; line-height:14px;font-weight:bold; font-size:10px;text-align:center;}
	.head4{position:fixed;top:38%;left:78%; width:7%; line-height:14px;font-weight:bold; font-size:10px;text-align:center;}
	
	
	.outof1{position:fixed;top:41.5%;left:60%; width:10%; line-height:14px;font-weight:bold; font-size:10px;text-align:center;}
	.outof2{position:fixed;top:41.5%;left:69%; width:8%; line-height:14px;font-weight:bold; font-size:10px;text-align:center;}
	.outof3{position:fixed;top:41.5%;left:78%; width:7%; line-height:14px;font-weight:bold; font-size:10px;text-align:center;}
	
	.thead1{position:fixed;top:38.5%;left:25%; width:30%; text-transform: uppercase; text-align:justify; font-size:18px; font-weight:bold; letter-spacing:5px;}
	.thead2{position:fixed;top:38%;left:55.5%; width:8%; line-height:14px;font-weight:bold; font-size:10px;text-align:center;}
	.thead3{position:fixed;top:38%;left:63%;  width:8%; line-height:14px;font-weight:bold; font-size:10px;text-align:center;}
	.thead4{position:fixed;top:38%;left:71%; width:7%; line-height:14px;font-weight:bold; font-size:10px;text-align:center;}
	.thead5{position:fixed;top:38%;left:78%; width:7%; line-height:14px;font-weight:bold; font-size:10px;text-align:center;}
	
	 .headtotal{position:fixed;top:69%;left:68%; width:30%; text-transform: uppercase; text-align:justify; font-size:16px; font-weight:bold; letter-spacing:2px;}
	
    
	</style>';


	if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != 0) {
		$totalmks = $PRACTICAL_MARKS + $OBJECTIVE_MARKS;

		$html .= "<div class='studname'><strong> Student Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : </strong>   $STUDENT_FNAME </div>";
		$html .= "<div class='fathername'><strong>Father / Husband Name &nbsp;: </strong>$STUDENT_MNAME</div>";
		$html .= "<div class='surname'><strong>Surname &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong>$STUDENT_LNAME</div>";
		$html .= "<div class='institudename'><strong>Institute Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong>$INSTITUTE_NAME</div>";
		$html .= "<div class='dob'><strong>Date Of Birth &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $STUDENT_DOB_F</div>";

		$html .= "<div class='courseperiod'><span class='period_date'><strong>Course Period &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $start_course_date To $end_course_date </span></div>";

		$html .= "<div class='coursedur'><strong>Course Duration &nbsp;: </strong> $cour_dur</div>";
		$html .= "<div class='coursename'><strong>Course Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $COURSE_NAME</div>";
		$html .= "<div class='mothername'><strong>Mother Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $STUDENT_MOTHER_NAME</div>";
		$html .= "<div class='marksheetno'><strong>Marksheet No &nbsp;&nbsp;&nbsp;&nbsp; : </strong> $CERTIFICATE_NO</div>";

		$html .= "<div class='head1'>SUBJECT</div>";
		$html .= "<div class='head2'>Objective Marks</div>";
		$html .= "<div class='head3'>Practical Marks</div>";
		$html .= "<div class='head4'>Total Marks</div>";

		$html .= "<div class='outof1'>Out of 50</div>";
		$html .= "<div class='outof2'>Out of 50</div>";
		$html .= "<div class='outof3'>Out of 100</div>";


		$html .= "<div class='subject'>" . $SUBJECT . "</div>";
		$html .= "<div class='practicle'>$OBJECTIVE_MARKS</div>";
		$html .= "<div class='subjectmark'>$PRACTICAL_MARKS</div>";
		$html .= "<div class='total'>$totalmks</div>";

		$html .= "<div class='percentage_bottom'><b> Percentage : $totalmks%</b></div>";
		$html .= "<div class='grade_bottom'><b> Grade : $GRADE</b></div>";
		$html .= "<div class='outof_bottom'><b> Total Marks : $totalmks / 100</b></div>";

		$html .= "<div class='headtotal'>TOTAL: </div>";

		$html .= "<div class='total_bottom'><b>$totalmks</b></div>";
	}
	if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != 0) {
		$MULTI_SUB_COURSE_DURATION = $db->get_course_duration_multi_sub($MULTI_SUB_COURSE_ID);

		$html .= "<div class='studname'><strong> Student Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : </strong>   $STUDENT_FNAME </div>";
		$html .= "<div class='fathername'><strong>Father / Husband Name &nbsp;: </strong>$STUDENT_MNAME</div>";
		$html .= "<div class='surname'><strong>Surname &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong>$STUDENT_LNAME</div>";
		$html .= "<div class='institudename'><strong>Institute Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong>$INSTITUTE_NAME</div>";
		$html .= "<div class='dob'><strong>Date Of Birth &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $STUDENT_DOB_F</div>";

		$html .= "<div class='courseperiod'><span class='period_date'><strong>Course Period &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $start_course_date To $end_course_date </span></div>";

		$html .= "<div class='coursedur'><strong>Course Duration &nbsp;: </strong> $cour_dur</div>";
		$html .= "<div class='coursename'><strong>Course Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $COURSE_NAME</div>";
		$html .= "<div class='mothername'><strong>Mother Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $STUDENT_MOTHER_NAME</div>";
		$html .= "<div class='marksheetno'><strong>Marksheet No &nbsp;&nbsp;&nbsp;&nbsp; : </strong> $CERTIFICATE_NO</div>";


		$html .= "<div class='head1'>SUBJECT</div>";
		$html .= "<div class='head2'>Objective Marks</div>";
		$html .= "<div class='head3'>Practical Marks</div>";
		$html .= "<div class='head4'>Total Marks</div>";

		$html .= "<div class='outof1'>Out of 50</div>";
		$html .= "<div class='outof2'>Out of 50</div>";
		$html .= "<div class='outof3'>Out of 100</div>";

		$res4  = $exammultisub->get_exam_final_id($CERTIFICATE_REQUEST_ID);
		$data4 = $res4->fetch_assoc();
		//print_r($data4); exit();
		$EXAM_RESULT_FINAL_ID 	= $data4['EXAM_RESULT_FINAL_ID'];

		$exam_result_info_multi_sub = $exammultisub->list_student_exam_results_multi_sub($EXAM_RESULT_FINAL_ID, $STUDENT_ID, $INSTITUTE_ID, '', '');
		$data3 = $exam_result_info_multi_sub->fetch_assoc();
		//print_r($data3); exit();
		$STUD_COURSE_ID 	= $data3['STUD_COURSE_ID'];

		$marksHtml = '<div style="position:fixed; top:480px; right:115px; left:125px;">';
		$res2 = $exammultisub->list_student_exam_results_multi_sub_list('', $STUDENT_ID, $INSTITUTE_ID, $STUD_COURSE_ID, '');
		$resultInfo = '';
		if ($res2 != '') {
			$srno1 = 1;

			$marksHtml .= "<table style='width:100%; font-size: 10pt; margin-left:10px'>";
			while ($data2 = $res2->fetch_assoc()) {
				//print_r($data2); exit();
				$EXAM_RESULT_ID1 		= $data2['EXAM_RESULT_ID'];
				$STUDENT_SUBJECT_ID1	= $data2['STUDENT_SUBJECT_ID'];
				$EXAM_ID1 				= $data2['EXAM_ID'];
				$INSTITUTE_COURSE_ID1 	= $data2['INSTITUTE_COURSE_ID'];
				$SUBJECT_NAME1 			= $data2['SUBJECT_NAME'];
				$EXAM_TITLE1 			= $data2['EXAM_TITLE'];
				$MARKS_OBTAINED1 		= $data2['MARKS_OBTAINED'];
				$PRACTICAL_MARKS1 		= $data2['PRACTICAL_MARKS'];

				//$subdetails =$access->get_multisub_inst_subject_details($STUDENT_SUBJECT_ID1,$MULTI_SUB_COURSE_ID,$INSTITUTE_ID);
				//$position =$access->get_multisub_inst_subject_position($STUDENT_SUBJECT_ID1,$MULTI_SUB_COURSE_ID,$INSTITUTE_ID); 


				$totalmks = $MARKS_OBTAINED1 + $PRACTICAL_MARKS1;
				// $marksHtml .="<div class='srno1' style='margin-top:5px;'>($srno1)</div>";			
				// $marksHtml .="<div class='subject1'style='margin-top:5px;'>$SUBJECT_NAME1</div>";
				// $marksHtml .="<div class='practicle1'style='margin-top:5px;'>$MARKS_OBTAINED1</div>";
				// $marksHtml .="<div class='subjectmark1'style='margin-top:5px;'>$PRACTICAL_MARKS1</div>";	
				// $marksHtml .="<div class='subjectmark1'>$totalmks</div>";


				$marksHtml .= "<tr class='highlight'>";
				$marksHtml .= "<td width='30%'>  <div class='fixed-cell' style='width:15% !important;'> $srno1) $SUBJECT_NAME1 </div> </td>";
				$marksHtml .= "<td width='5.6%'>  <div class='fixed-cell' style='width:7% !important;'> $MARKS_OBTAINED1</div> </td>";
				$marksHtml .= "<td width='5.6%'>  <div class='fixed-cell' style='width:7% !important;'> $PRACTICAL_MARKS1</div> </td>";
				$marksHtml .= "<td width='5.6%'>  <div class='fixed-cell' style='width:7% !important;'> $totalmks</div> </td>";
				$marksHtml .= '</tr>';

				$TOTAL_MARKS1 += $totalmks;

				$total_marks_of_all_subjects = 100 * $srno1;

				$srno1++;
			}

			$marksHtml .= "</table>";
			$marksHtml .= '</div>';
		}


		$html .= $marksHtml;
		$html .= "<div class='percentage_bottom'><b> Percentage : $MARKS_PER%</b></div>";

		$html .= "<div class='grade_bottom'><b> Grade : $GRADE</b></div>";

		$html .= "<div class='outof_bottom'><b> Total Marks : $TOTAL_MARKS1 / $total_marks_of_all_subjects</b></div>";

		$html .= "<div class='headtotal'>TOTAL: </div>";

		$html .= "<div class='total_bottom'><b>$TOTAL_MARKS1</b></div>";
	}
	if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != 0) {

		$html .= "<div class='studname'><strong> Student Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : </strong>   $STUDENT_FNAME </div>";
		$html .= "<div class='fathername'><strong>Father / Husband Name &nbsp;: </strong>$STUDENT_MNAME</div>";
		$html .= "<div class='surname'><strong>Surname &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong>$STUDENT_LNAME</div>";
		$html .= "<div class='institudename'><strong>Institute Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong>$INSTITUTE_NAME</div>";
		$html .= "<div class='dob'><strong>Date Of Birth &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $STUDENT_DOB_F</div>";

		$html .= "<div class='courseperiod'><span class='period_date'><strong>Course Period &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $start_course_date To $end_course_date </span></div>";

		$html .= "<div class='coursedur'><strong>Course Duration &nbsp;: </strong> $cour_dur</div>";
		$html .= "<div class='coursename'><strong>Course Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $COURSE_NAME</div>";
		$html .= "<div class='mothername'><strong>Mother Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong> $STUDENT_MOTHER_NAME</div>";
		$html .= "<div class='marksheetno'><strong>Marksheet No &nbsp;&nbsp;&nbsp;&nbsp; : </strong> $CERTIFICATE_NO</div>";

		$html .= "<div class='thead1'>SUBJECT</div>";
		$html .= "<div class='thead2'>SPEED W.P.M</div>";
		$html .= "<div class='thead3'>MINIMUM MARKS</div>";
		$html .= "<div class='thead4'>MAXIMUM MARKS</div>";
		$html .= "<div class='thead5'>MARKS OBTAINED</div>";

		$res4  = $coursetypingexam->get_exam_final_id($certreq);
		$data4 = $res4->fetch_assoc();
		$EXAM_RESULT_FINAL_ID 	= $data4['EXAM_RESULT_TYPING_ID'];

		$exam_result_info_multi_sub = $coursetypingexam->list_student_exam_results_typing($EXAM_RESULT_FINAL_ID, $STUDENT_ID, $INSTITUTE_ID, '', '');
		$data3 = $exam_result_info_multi_sub->fetch_assoc();
		$STUD_COURSE_ID 	= $data3['STUD_COURSE_ID'];

		$marksHtml = '<div style="position:fixed; top:480px; right:115px; left:125px;">';
		$res2 = $coursetypingexam->list_student_exam_results_typing_list('', $STUDENT_ID, $INSTITUTE_ID, $STUD_COURSE_ID, '');
		$resultInfo = '';
		if ($res2 != '') {
			$srno1 = 1;

			$marksHtml .= "<table style='width:100%;margin-left:10px'>";
			while ($data2 = $res2->fetch_assoc()) {
				//print_r($data2); exit();
				$EXAM_RESULT_ID1 		= $data2['EXAM_RESULT_ID'];
				$STUDENT_SUBJECT_ID1	= $data2['STUDENT_SUBJECT_ID'];
				$EXAM_ID1 				= $data2['EXAM_ID'];
				$INSTITUTE_COURSE_ID1 	= $data2['INSTITUTE_COURSE_ID'];
				$SUBJECT_NAME1 			= $data2['SUBJECT_NAME'];
				$TYPING_COURSE_SPEED 	= $data2['TYPING_COURSE_SPEED'];
				$EXAM_TITLE1 			= $data2['EXAM_TITLE'];
				$MARKS_OBTAINED 		= $data2['MARKS_OBTAINED'];
				$EXAM_TOTAL_MARKS 		= $data2['EXAM_TOTAL_MARKS'];
				$TOTAL_MARKS1 			= $data2['TOTAL_MARKS'];
				$MINIMUM_MARKS 			= $data2['MINIMUM_MARKS'];


				$marksHtml .= "<tr class='highlight'>";
				$marksHtml .= "<td class='subject_name' style='font-size:8pt'>$srno1)  $SUBJECT_NAME1</td>";
				$marksHtml .= "<td class='speed' style='font-size:8pt'>$TYPING_COURSE_SPEED</td>";
				$marksHtml .= "<td class='minimum_marks' style='font-size:8pt'>$MINIMUM_MARKS</td>";
				$marksHtml .= "<td class='exam_total_marks' style='font-size:8pt'>$EXAM_TOTAL_MARKS</td>";
				$marksHtml .= "<td class='marks_obtained' style='font-size:8pt'>$MARKS_OBTAINED</td>";
				$marksHtml .= '</tr>';

				$totalObt += $MARKS_OBTAINED;

				$total_marks_of_all_subjects += $EXAM_TOTAL_MARKS;

				$srno1++;
			}
			$marksHtml .= "</table>";
			$marksHtml .= '</div>';
		}


		$html .= $marksHtml;
		$html .= "<div class='percentage_bottom'><b> Percentage : $MARKS_PER%</b></div>";

		$html .= "<div class='grade_bottom'><b> Grade : $GRADE</b></div>";

		$html .= "<div class='outof_bottom'><b> Total Marks : $totalObt / $total_marks_of_all_subjects</b></div>";

		$html .= "<div class='headtotal'>TOTAL: </div>";

		$html .= "<div class='total_bottom'><b>$totalObt</b></div>";
	}
	$html2 .= '<div class="qrcodeimage"><img src="' . $file . '"></div>';
	$html .= "<div class='weblink'> Online Certificate Verification available on : www.ditrpindia.org </div>";

	$html2 .= '<img src="' . $marksheet_image . '" style="width:100%" />';
	$html2 .= $html;

	$mpdf->WriteHTML($html2);
	//$mpdf->Output($file,'I');
	$mpdf->Output($STUDENT_FNAME . ' ' . $STUDENT_LNAME . '_Marksheet.pdf', 'I');
}
ob_end_flush();
