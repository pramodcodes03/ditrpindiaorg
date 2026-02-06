<?php
	ob_clean(); 
	$certReqId= isset($_GET['id'])?$_GET['id']:'';
	include('include/plugins/pdf/mpdf.php');
	$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 

			$sql = "SELECT 
certificate_requests.EXAM_RESULT_ID, 
certificate_requests.STUDENT_ID, 
certificate_requests.INSTITUTE_ID, 
exam_result.MARKS_OBTAINED,
marksheet_requests.MARKSHEET_MARKS,marksheet_requests.MARKSHEET_NO,
marksheet_requests.MARKSHEET_SUBJECT,
student_details.STUDENT_FNAME, student_details.STUDENT_MNAME, student_details.STUDENT_LNAME, DATE_FORMAT(student_details.STUDENT_DOB,'%d-%m-%Y') as dob, student_details.STUDENT_CODE,
institute_details.INSTITUTE_CODE, institute_details.INSTITUTE_NAME,
get_course_title_modify(certificate_requests.AICPE_COURSE_ID) AS COURSE_NAME,
courses.COURSE_DURATION,student_enquiry.STUDENT_MOTHERNAME
FROM certificate_requests
INNER JOIN exam_result ON certificate_requests.EXAM_RESULT_ID=exam_result.EXAM_RESULT_ID  
INNER JOIN student_details ON certificate_requests.STUDENT_ID=student_details.STUDENT_ID
INNER JOIN institute_details ON certificate_requests.INSTITUTE_ID=institute_details.INSTITUTE_ID
INNER JOIN marksheet_requests ON certificate_requests.CERTIFICATE_REQUEST_ID=marksheet_requests.CERTIFICATE_REQUEST_ID

INNER JOIN courses ON certificate_requests.AICPE_COURSE_ID= courses.COURSE_ID
INNER JOIN student_enquiry ON certificate_requests.INSTITUTE_ID=student_enquiry.INSTITUTE_ID
			WHERE certificate_requests.CERTIFICATE_REQUEST_ID=$certReqId";
	$res = $db->execQuery($sql);
	if($res && $res->num_rows>0)
	{

	while($data = $res->fetch_assoc())
	{
		extract($data);
	}
	$html2 = 
	'<style>
	body {padding:0;font-family: sans-serif; font-size: 8pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.studname{position:absolute;top:25.8%; width:24%;left:34%;}
	.fathername{position:absolute;top:27.4%;left:34%; border:1px}
	.surname{position:absolute;top:29.0%;left:34%; border:1px}
	.institudename{position:absolute;top:35.2%;left:34%; border:1px}
	.dob{position:absolute;top:29.1%;left:89.4%; border:1px}
	.coursedur{position:absolute;top:26.0%;left:89.4%; border:1px}
	.coursename{position:absolute;top:32.1%;left:34%; border:1px}
	.mothername{position:absolute;top:30.5%;left:34%; border:1px}
	.marksheetno{position:absolute;top:27.6%;left:89.4%; border:1px}
	.subject{position:absolute;top:44.2%;left:20%; border:1px}
	.subjectmark{position:absolute;top:44.2%;left:72%; border:1px; width:25px}
	.objmark{position:absolute;top:44.2%;left:65%; border:1px; width:25px}
	.totalmark{position:absolute;top:44.2%;left:80%; border:1px; width:25px}
	</style>';
	$total='';
	$total=$MARKS_OBTAINED+$MARKSHEET_MARKS;
	$html .="<div class='studname'>$STUDENT_FNAME </div>";
	$html .="<div class='fathername'>$STUDENT_MNAME</div>";
	$html .="<div class='surname'>$STUDENT_LNAME</div>";
	$html .="<div class='institudename'>$INSTITUTE_NAME</div>";
	$html .="<div class='dob'>$dob</div>";
	$html .="<div class='coursedur'>$COURSE_DURATION</div>";
	$html .="<div class='coursename'>$COURSE_NAME</div>";
	$html .="<div class='mothername'>$STUDENT_MOTHERNAME</div>";
	$html .="<div class='marksheetno'>$MARKSHEET_NO</div>";
	$html .="<div class='subject'>$MARKSHEET_SUBJECT</div>";
	$html .="<div class='subjectmark'>$MARKSHEET_MARKS</div>";
	$html .="<div class='objmark'>$MARKS_OBTAINED</div>";
     $html .="<div class='totalmark'>$total</div>";
		

	// $html .= "<p>Student Code:  $STUDENT_CODE</p>";
	// $html .= "<p>Student DOB:  $STUDENT_DOB</p>";
	// $html .= "<p>Course Name:  $COURSE_NAME</p>";
	// $html .= "<p>Course Duration:  $COURSE_DURATION</p>";
	

	$html2 .= '<img src="resources/dist/img/marksheet.JPG" style="width:100%" />';
	$html2 .= $html;

	$mpdf->WriteHTML($html2);
	$mpdf->Output('test.pdf','I');
	}
?>