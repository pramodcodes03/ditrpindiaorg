<?php
ob_clean();

$checkstud = isset($_REQUEST['checkstud'])?$_REQUEST['checkstud']:'';
$certreq = isset($_REQUEST['certreq'])?$_REQUEST['certreq']:'';
$course = isset($_REQUEST['course'])?$_REQUEST['course']:'';
$course_multi_sub = isset($_REQUEST['course_multi_sub'])?$_REQUEST['course_multi_sub']:'';

//$checkstud = array(2);
if($checkstud!='' && !empty($checkstud))
{
	date_default_timezone_set("Asia/Kolkata");
	include("include/plugins/pdf/mpdf.php");
	include_once('include/classes/exam.class.php');
	$exam 	= new  exam();
	include('include/classes/exammultisub.class.php');
	$exammultisub = new exammultisub();
	$html='';	
	
	
	$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
	//$id = base64_decode($id);
if($course!=='' && !empty($course)){
	$cond = " AND AICPE_COURSE_ID='$course' AND CERTIFICATE_REQUEST_ID='$certreq' AND STUDENT_ID='$checkstud' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1";
}
if($course_multi_sub!=='' && !empty($course_multi_sub)){
	$cond = " AND MULTI_SUB_COURSE_ID='$course_multi_sub' AND CERTIFICATE_REQUEST_ID='$certreq' AND STUDENT_ID='$checkstud' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1";
}
	$res = $access->list_order_printed_certificates('','', $cond);	

	//$res 	= $exam->list_certificates_requests($id, '', '', '');
	$data 	= $res->fetch_assoc();
    
	extract($data);

	//print_r($data);exit();
	$STUD_PHOTO = SHOW_IMG_AWS.STUDENT_DOCUMENTS_PATH.$STUDENT_ID.'/'.$STUDENT_PHOTO;
	
	$type = pathinfo($STUD_PHOTO, PATHINFO_EXTENSION);
	$cert_photo = $filename.'.'.$type;
	$STUD_PHOTO_CERT = SHOW_IMG_AWS.CERTIFICATE_PATH.'/photos/'.$cert_photo;
	
	
	
	//==============================================================

	
	$idproof='';
	$courseduration='';
	$cour_dur ='';
	
	$FILE_DESC = isset($FILE_DESC)?$FILE_DESC:'';
	$FILE_CATEGORY = isset($FILE_CATEGORY)?$FILE_CATEGORY:'';
	
	if($STUD_ID_PROOF_NUMBER!='' && $STUD_ID_PROOF_TYPE!='')
	{
		$idproof = '<h4 class="idproof"> '.htmlspecialchars_decode($STUD_ID_PROOF_TYPE).' : '.htmlspecialchars_decode($STUD_ID_PROOF_NUMBER).' </h4>';
	}
    if($COURSE_DURATION!='')
	{
	    $cour_dur = $COURSE_DURATION;
		$courseduration = '<h3 class="courseduration"> (COURSE DURATION: '.htmlspecialchars_decode($COURSE_DURATION).')</h3>';
	}
	
	$MULTI_SUB_COURSE_DURATION = $db->get_course_duration_multi_sub($MULTI_SUB_COURSE_ID);
	if($MULTI_SUB_COURSE_DURATION!='')
	{
	    $cour_dur = $MULTI_SUB_COURSE_DURATION;
		$courseduration = '<h3 class="courseduration"> (COURSE DURATION: '.htmlspecialchars_decode($MULTI_SUB_COURSE_DURATION).')</h3>';
	}
	echo $ISSUE_DATE_FORMAT;
	$date = strtotime($ISSUE_DATE_FORMAT);
	$new_date = strtotime('- '.$cour_dur, $date);
	$start_date = strtotime('+ 1 day ',$new_date);
	echo $start_course_date = date('d M Y', $start_date); 
	echo $end_course_date = date('d M Y', $date); 
	
	if($COURSE_DURATION!='')
	{
		$course_period = '<h3 class="courseperiod"> (COURSE PERIOD: '.$start_course_date.' TO '.$end_course_date.')</h3>';
	}
	if($MULTI_SUB_COURSE_DURATION!='')
	{
		$course_period = '<h3 class="courseperiod"> (COURSE PERIOD: '.$start_course_date.' TO '.$end_course_date.')</h3>';
	}
	
	
	$html2 = 
	'<style>
	body {padding:0;font-family: sans-serif; font-size: 8pt;position:absolute;z-index:0;top:0px;}
	.studname{position:absolute;top:25.6%;left:34%;width:35%}
	.fathername{position:absolute;top:27.2%;left:34%; width:35%}
	.surname{position:absolute;top:28.7%;left:34%; width:35%}
	.institudename{position:absolute;top:35%;left:34%;width:100%}
	.dob{position:absolute;top:28.9%;left:74.4%; width:15%}
	
	.courseperiod{position:absolute;top:30.2%;left:57.4%; width:40%; font-size:12px; font-weight:normal;}
	
	
	.coursedur{position:absolute;top:25.5%;left:74.4%; width:15%}
	.coursename{position:absolute;top:32%;left:34%; border:1px }
	.mothername{position:absolute;top:30.2%;left:34%; width:23%}
	.marksheetno{position:absolute;top:27%;left:74.4%; width:15%}
	.subject{position:absolute;top:42.2%;left:20%; border:1px;width:30%; text-transform: uppercase; text-align:justify; line-height:20px;}
	.subjectmark{position:absolute;top:44.2%;left:71.2%; height:50px;width:50px;font-size:14px; }
	.total{position:absolute;top:44.2%;left:78.2%; height:50px;width:50px;font-size:14px; }
	.practicle{position:absolute;top:44.2%;left:63.2%; height:50px;width:50px;font-size:14px; }
	.total_bottom{position:absolute;top:69%;left:78.6%; font-size:16px;height:50px;width:50px; }

	.subject1{position:absolute;left:20%;border:1px;width:30%; text-transform: uppercase; text-align:justify; line-height:20px;}
	.practicle1{position:absolute;left:63.2%; height:50px;width:50px;font-size:14px;}
	.subjectmark1{position:absolute;left:71.2%; height:50px;width:50px;font-size:14px; }
	.srno1{position:absolute;left:18%; border:1px;width:30%; text-transform: uppercase; text-align:justify; line-height:20px;}
    table tr.highlight td{padding-top:1%; padding-bottom:1%;}
    
    .outof_bottom{position:absolute;top:69.5%;left:45%; font-size:11px;height:50px;width:150px; }
    .percentage_bottom{position:absolute;top:69.5%;left:18%; font-size:11px;height:50px;width:150px;}
    .grade_bottom{position:absolute;top:69.5%;left:35%; font-size:11px;height:50px;width:100px;}
    
    .period_date{font-weight:normal;font-size: 8pt; }
    .subdetails{text-transform:capitalize; margin-left:25px;}
    
	</style>';
	
	if($AICPE_COURSE_ID!='' && !empty($AICPE_COURSE_ID) && $AICPE_COURSE_ID!=0){
		$totalmks=$PRACTICAL_MARKS + $OBJECTIVE_MARKS;
		$html .="<div class='studname'>$STUDENT_FNAME </div>";
		$html .="<div class='fathername'>$STUDENT_MNAME</div>";
		$html .="<div class='surname'>$STUDENT_LNAME</div>";
		$html .="<div class='institudename'>$INSTITUTE_NAME</div>";
		$html .="<div class='dob'>$STUDENT_DOB_F</div>";
		
		$html .="<div class='courseperiod'>Course Period &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span class='period_date'>&nbsp;&nbsp;$start_course_date To $end_course_date </span></div>";
		
		
		$html .="<div class='coursedur'>$COURSE_DURATION</div>";
		$html .="<div class='coursename'>$COURSE_NAME</div>";
		$html .="<div class='mothername'>$STUDENT_MOTHER_NAME</div>";
		$html .="<div class='marksheetno'>$CERTIFICATE_NO</div>";
		$html .="<div class='subject'>$SUBJECT</div>";
		$html .="<div class='practicle'>$OBJECTIVE_MARKS</div>";
		$html .="<div class='subjectmark'>$PRACTICAL_MARKS</div>";
		$html .="<div class='total'>$totalmks</div>";	
		$html .="<div class='total_bottom'><b>$totalmks</b></div>";
	}
	if($MULTI_SUB_COURSE_ID!='' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID!=0){
		$MULTI_SUB_COURSE_DURATION = $db->get_course_duration_multi_sub($MULTI_SUB_COURSE_ID);
		
		$html .="<div class='studname'>$STUDENT_FNAME </div>";
		$html .="<div class='fathername'>$STUDENT_MNAME</div>";
		$html .="<div class='surname'>$STUDENT_LNAME</div>";
		$html .="<div class='institudename'>$INSTITUTE_NAME</div>";
		$html .="<div class='dob'>$STUDENT_DOB_F</div>";
		
		$html .="<div class='courseperiod'>Course Period &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span class='period_date'>&nbsp;&nbsp;$start_course_date To $end_course_date </span></div>";
		
		
		$html .="<div class='coursedur'>$MULTI_SUB_COURSE_DURATION</div>";
		$html .="<div class='coursename'>$COURSE_NAME</div>";
		$html .="<div class='mothername'>$STUDENT_MOTHER_NAME</div>";
		$html .="<div class='marksheetno'>$CERTIFICATE_NO</div>";

		$marksHtml='<div style="position:absolute; top:485px; right:100px; left:140px;">';
		$res2 = $exammultisub->list_student_exam_results_multi_sub_list('',$STUDENT_ID,$INSTITUTE_ID,'','');
					$resultInfo = ''; 
					if($res2!='')
					{	
					$srno1=1;
					
					$marksHtml .= "<table style='width:100%; font-size: 10pt;'>";
					while($data2 = $res2->fetch_assoc())
					{
						//print_r($data2);
						$EXAM_RESULT_ID1 		= $data2['EXAM_RESULT_ID'];
						$STUDENT_SUBJECT_ID1	= $data2['STUDENT_SUBJECT_ID'];
						$EXAM_ID1 				= $data2['EXAM_ID'];
						$INSTITUTE_COURSE_ID1 	= $data2['INSTITUTE_COURSE_ID'];
						$SUBJECT_NAME1 			= $data2['SUBJECT_NAME'];
						$EXAM_TITLE1 			= $data2['EXAM_TITLE'];
						$MARKS_OBTAINED1 		= $data2['MARKS_OBTAINED'];
						$PRACTICAL_MARKS1 		= $data2['PRACTICAL_MARKS'];
						
						$subdetails =$access->get_multisub_inst_subject_details($STUDENT_SUBJECT_ID1,$MULTI_SUB_COURSE_ID,$INSTITUTE_ID);
				    	$position =$access->get_multisub_inst_subject_position($STUDENT_SUBJECT_ID1,$MULTI_SUB_COURSE_ID,$INSTITUTE_ID); 
					
						
						$totalmks=$MARKS_OBTAINED1 + $PRACTICAL_MARKS1;				
						// $marksHtml .="<div class='srno1' style='margin-top:5px;'>($srno1)</div>";			
						// $marksHtml .="<div class='subject1'style='margin-top:5px;'>$SUBJECT_NAME1</div>";
						// $marksHtml .="<div class='practicle1'style='margin-top:5px;'>$MARKS_OBTAINED1</div>";
						// $marksHtml .="<div class='subjectmark1'style='margin-top:5px;'>$PRACTICAL_MARKS1</div>";	
						// $marksHtml .="<div class='subjectmark1'>$totalmks</div>";	

						$marksHtml .= "<tr class='highlight'>";								
						$marksHtml .="<td width='30%'>$srno1)  $SUBJECT_NAME1 <br/> &nbsp;&nbsp;&nbsp;&nbsp;<span class='subdetails'>$subdetails </span> </td>";
						$marksHtml .="<td width='3%'>$MARKS_OBTAINED1</td>";
						$marksHtml .="<td width='3%'>$PRACTICAL_MARKS1</td>";	
						$marksHtml .="<td width='3%'>$totalmks</td>";	
						$marksHtml .= '</tr>';

						$TOTAL_MARKS1 +=$totalmks;
						
						$total_marks_of_all_subjects = 100 * $srno1;
						 
						$srno1++;			
					
					}	
					$marksHtml .= "</table>";
					$marksHtml .= '</div>';	
					}	
        
         
		$html .= $marksHtml;
		$html .="<div class='percentage_bottom'><b> Percentage : $MARKS_PER%</b></div>";
		
		$html .="<div class='grade_bottom'><b> Grade : $GRADE</b></div>";
		
		$html .="<div class='outof_bottom'><b> Total Marks : $TOTAL_MARKS1 / $total_marks_of_all_subjects</b></div>";
		
		$html .="<div class='total_bottom'><b>$TOTAL_MARKS1</b></div>";
	}
	$html2 .= '<img src="resources/dist/img/marksheet.JPG" style="width:100%" />';
	$html2 .= $html;

	$mpdf->WriteHTML($html2);
	$mpdf->Output('test.pdf','I');
		
}
ob_end_flush();
?>