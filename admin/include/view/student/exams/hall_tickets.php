<?php
if(isset($_POST['print']))
{

	$scd = isset($_REQUEST['scd'])?$_REQUEST['scd']:'';
	if($scd!='')
	{
		include_once('include/classes/exam.class.php');
		$exam 	= new  exam();
		$sql 	= "SELECT *, get_student_name(A.STUDENT_ID) AS STUDENT_NAME FROM student_course_details A LEFT JOIN student_details B On A.STUDENT_ID=B.STUDENT_ID LEFT JOIN institute_details C ON A.INSTITUTE_ID=C.INSTITUTE_ID WHERE STUD_COURSE_DETAIL_ID='$scd' LIMIT 0,1";
		$res 	= $db->execQuery($sql);
		$data 	= $res->fetch_assoc();

		$student_name	= $data['STUDENT_NAME'];
		$institute_code	= $data['INSTITUTE_CODE'];
		$course_info	= $db->get_inst_course_info($data['INSTITUTE_COURSE_ID']);
		$course_name 	= $course_info['COURSE_NAME'];
		$course_id 		= $course_info['COURSE_ID'];
		$total_que		= $db->get_exam_total_marks($course_id);
		$file_name_que	= $db->rename_offline_paper_pdf($course_name, 'question_paper');
		$file_name_ans	= $db->rename_offline_paper_pdf($course_name, 'answer_paper');
		$today_date		= date('d/m/Y');
	}
	
 $printid = isset($_POST['printid'])?$_POST['printid']:'';

 if($printid!='')
 {
	$str	= implode(",", $printid);
	$str = rtrim($str,",");

	$sql = "SELECT * FROM tbl_import WHERE id IN ($str)";
	//$sql = "SELECT * FROM tbl_import WHERE 1";
	$res = mysql_query($sql);
	$html='';
	while($data = mysql_fetch_assoc($res))
	{
		extract($data);
	$photopath = "images/defaultuser.jpg";
	if($photo!='')
		$photopath = "upload/imported/$photo";
	if($sets=='') $sets=' &nbsp;&nbsp;&nbsp;';
	$html .='
	<div class="card-layout">

	<table>
	<tbody>
	<tr>
	<td colspan="3" class="heading" align="center">MAHARASHTRA BOARD ASSESSMENT TEST SERIES (M-BATS), NAGPUR</td>
	</tr>

	<tr><td colspan="3" align="center" class="title">Admission Card Of Test Series</td>
	</tr>
	</tbody></table>


	<table class="location">
		<tr>
			<th>City</th>
			<th>Location</th>
			<th>Test Center</th>
			<th>Seat No</th>
			<th>Board</th>
		</tr>
		<tr>
			<td>'.$city.'</td>
			<td>'.$center.'</td>
			<td>'.$centerno.'</td>
			<td>'.$seat.'</td>
			<td>'.$board.'</td>
		</tr>
	</table>
	<div style="width:500px;float:left;">
	<table>
		<tr>
			<td width="20%"><strong>Name:</strong></td>
			<td>'.$name.'</td>
		</tr>
		<tr>
			<td><strong>Mother Name:</strong></td>
			<td>'.$mname.'</td>
		</tr>
		<tr>
			<td><strong>School Name:</strong></td>
			<td>'.$school.'</td>
		</tr>
	</table>

	<table class="exam">
		<tr>
			<th style="padding:20px">Subject Name(Abbreviation)</th>
			<td rowspan="2" style="margin:0; padding:0;">
				<table class="exam-subtable mar-0">
					<tr>
						<td>I Lang</td>
						<td>II Lang</td>
						<td>III Lang</td>
						<td>Maths</td>
						<td>Science</td>
						<td rowspan="2">Social Science</td>
					</tr>
					<tr>
						<td>ENG</td>
						<td>H/M/S</td>
						<td>Mara</td>
						<td>Maths</td>
						<td></td>
					</tr>
					<tr>
						<td>'.$lang1.'</td>
						<td>'.$lang2.'</td>
						<td>'.$lang3.'</td>
						<td>'.$maths.'</td>
						<td>'.$science.'</td>
						<td>'.$ss.'</td>
					</tr>
				</table>
			</td>
			<td rowspan="2" style="border:none;">
				<table>
					<tr>
						<td>SETS</td>					
					</tr>
					<tr>
						<td>'.$sets.'</td>					
					</tr>
			
				</table>
			</td>
		</tr>
		<tr>
			<th>Language Of Answer</th>
			
		</tr>
	</table>
	</div>
	<div class="photo-container">
		<div class="photo">
			<img src="'.$photopath.'" />
		</div>
		<div class="sign"></div>
	</div>
	
	<div class="note">
	 <strong>Note:</strong> Candidate must preserve and provide this card at each session of examination, without ID-CARD students are not allowed in examination hall.<br>	
	<strong>IMPORTANT</strong>: Maharashtra Board Assessment Test Series is a private body conducting examination, this firm has nothing to do with 10th Maharashtra board. None of our Documents, Color, Format are related with boards. All rights reserved with M-BATS , PARISHRAM &nbsp;PUBLICATIONS 	
	</div>
	</div>
	
	<div class="last-clear"></div>
	<br>
	';
	}
 }
//==============================================================
//==============================================================
//==============================================================
include("include/plugins/pdf/mpdf.php");

$mpdf=new mPDF('c','A4','','',5,5,5,5,16,13); 


$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
//$stylesheet = file_get_contents('css/hallticket.css');
//$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);

$mpdf->Output('HallTicket.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================
}

?>