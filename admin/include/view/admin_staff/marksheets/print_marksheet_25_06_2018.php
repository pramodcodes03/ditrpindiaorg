<?php
	ob_clean(); 
	$certReqId= isset($_GET['id'])?$_GET['id']:'';
	$checkstud = isset($_REQUEST['checkstud'])?$_REQUEST['checkstud']:'';
$certreq = isset($_REQUEST['certreq'])?$_REQUEST['certreq']:'';
$course = isset($_REQUEST['course'])?$_REQUEST['course']:'';
	include('include/plugins/pdf/mpdf.php');
	$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 

			$sql = "SELECT 
certificate_requests.EXAM_RESULT_ID, 
certificate_requests.STUDENT_ID,certificate_requests.MARKS_PER,certificate_requests.GRADE,
certificate_requests.INSTITUTE_ID,
exam_result.MARKS_OBTAINED,certificates_details.SUBJECT,certificates_details.PRACTICAL_MARKS,certificates_details.OBJECTIVE_MARKS,
student_details.STUDENT_FNAME,student_details.STUDENT_MNAME, student_details.STUDENT_LNAME, DATE_FORMAT(student_details.STUDENT_DOB,'%d-%m-%Y') as dob, student_details.STUDENT_CODE,
institute_details.INSTITUTE_CODE, institute_details.INSTITUTE_NAME,
get_course_title_modify(certificate_requests.AICPE_COURSE_ID) AS COURSE_NAME,
courses.COURSE_DURATION,student_enquiry.STUDENT_MOTHERNAME,certificates_details.CERTIFICATE_NO
FROM certificate_requests
INNER JOIN certificates_details ON certificate_requests.CERTIFICATE_REQUEST_ID=certificates_details.CERTIFICATE_REQUEST_ID 
INNER JOIN exam_result ON certificate_requests.EXAM_RESULT_ID=exam_result.EXAM_RESULT_ID  
INNER JOIN student_details ON certificate_requests.STUDENT_ID=student_details.STUDENT_ID
INNER JOIN institute_details ON certificate_requests.INSTITUTE_ID=institute_details.INSTITUTE_ID
INNER JOIN marksheet_requests ON certificate_requests.CERTIFICATE_REQUEST_ID=marksheet_requests.CERTIFICATE_REQUEST_ID

INNER JOIN courses ON certificate_requests.AICPE_COURSE_ID= courses.COURSE_ID
INNER JOIN student_enquiry ON certificate_requests.INSTITUTE_ID=student_enquiry.INSTITUTE_ID
			WHERE certificate_requests.CERTIFICATE_REQUEST_ID=$certreq";
	$res = $db->execQuery($sql);


	if($res && $res->num_rows>0)
	{

	while($data = $res->fetch_assoc())
	{
		extract($data);
	//print_r($data);exit();
	}


	
	//==============================================================
}

	$html2 = 
	'<style>
	body {padding:0;font-family: sans-serif; font-size: 8pt;position:absolute;z-index:0;top:0px;}
	.studname{position:absolute;top:26.0%;left:34%;width:23%}
	.fathername{position:absolute;top:27.6%;left:34%; width:23%}
	.surname{position:absolute;top:29.0%;left:34%; width:23%}
	.institudename{position:absolute;top:35.2%;left:34%;width:20%}
	.dob{position:absolute;top:29.1%;left:73.4%; width:15%}
	.coursedur{position:absolute;top:26.0%;left:73.4%; width:15%}
	.coursename{position:absolute;top:32.1%;left:34%; border:1px }
	.mothername{position:absolute;top:30.5%;left:34%; width:23%}
	.marksheetno{position:absolute;top:27.6%;left:73.4%; width:15%}
	.subject{position:absolute;top:44.2%;left:20%; border:1px}
	.subjectmark{position:absolute;top:44.2%;left:71.2%; height:50px;width:50px }
	.total{position:absolute;top:44.2%;left:78.2%; height:50px;width:50px }
	.practicle{position:absolute;top:44.2%;left:63.2%; height:50px;width:50px }
	</style>';
	
	$html .="<div class='studname'>$STUDENT_FNAME </div>";
	$html .="<div class='fathername'>$STUDENT_MNAME</div>";
	$html .="<div class='surname'>$STUDENT_LNAME</div>";
	$html .="<div class='institudename'>$INSTITUTE_NAME</div>";
	$html .="<div class='dob'>$dob</div>";
	$html .="<div class='coursedur'>$COURSE_DURATION</div>";
	$html .="<div class='coursename'>$COURSE_NAME</div>";
	$html .="<div class='mothername'>$STUDENT_MOTHERNAME</div>";
	$html .="<div class='marksheetno'>$CERTIFICATE_NO</div>";
	$html .="<div class='subject'>$SUBJECT Subject</div>";
	$html .="<div class='practicle'>$OBJECTIVE_MARKS 12</div>";
	$html .="<div class='subjectmark'>$PRACTICAL_MARKS 10</div>";
	$html .="<div class='total'>$PRACTICAL_MARKS + $OBJECTIVE_MARKS 30</div>";

	// $html .= "<p>Student Code:  $STUDENT_CODE</p>";
	// $html .= "<p>Student DOB:  $STUDENT_DOB</p>";
	// $html .= "<p>Course Name:  $COURSE_NAME</p>";
	// $html .= "<p>Course Duration:  $COURSE_DURATION</p>";
	

	$html2 .= '<img src="resources/dist/img/marksheet.JPG" style="width:100%" />';
	$html2 .= $html;

	$mpdf->WriteHTML($html2);
	$mpdf->Output('test.pdf','I');
	
?>