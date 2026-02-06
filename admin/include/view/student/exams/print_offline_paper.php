<?php
ini_set("memory_limit", "128M");
$scd = isset($_REQUEST['scd']) ? $_REQUEST['scd'] : '';
if ($scd != '') {
	date_default_timezone_set("Asia/Kolkata");
	include_once('include/classes/exam.class.php');
	$exam 	= new  exam();
	$scd = base64_decode($scd);
	$sql 	= "SELECT *, get_student_name(A.STUDENT_ID) AS STUDENT_NAME FROM student_course_details A LEFT JOIN student_details B On A.STUDENT_ID=B.STUDENT_ID LEFT JOIN institute_details C ON A.INSTITUTE_ID=C.INSTITUTE_ID WHERE STUD_COURSE_DETAIL_ID='$scd' LIMIT 0,1";
	$res 	= $db->execQuery($sql);
	$data 	= $res->fetch_assoc();

	$STUD_COURSE_DETAIL_ID	= $data['STUD_COURSE_DETAIL_ID'];
	$EXAM_SECRETE_CODE	= $data['EXAM_SECRETE_CODE'];
	$student_name	= $data['STUDENT_NAME'];
	$STUDENT_ID		= $data['STUDENT_ID'];

	$student_code		= $data['STUDENT_CODE'];

	$INSTITUTE_ID	= $data['INSTITUTE_ID'];
	$INSTITUTE_COURSE_ID	= $data['INSTITUTE_COURSE_ID'];
	$institute_code	= $data['INSTITUTE_CODE'];
	$institute_name	= ucwords(strtoupper($data['INSTITUTE_NAME']));
	$course_info	= $db->get_inst_course_info($INSTITUTE_COURSE_ID);
	$course_name 	= $course_info['COURSE_NAME'];
	$course_id 		= $course_info['COURSE_ID'];
	$course_code 		= $course_info['COURSE_CODE'];
	$total_que		= $db->get_exam_total_marks($course_id);
	$file_name_que	= $db->rename_offline_paper_pdf($course_name, 'question_paper');
	$file_name_ans	= $db->rename_offline_paper_pdf($course_name, 'answer_paper');
	$today_date		= date('d/m/Y');
	$filepath		= EXAM_OFFLINE_PAPER_PATH . '/' . $STUDENT_ID;
	if (!file_exists($filepath)) {
		@mkdir($filepath, 0777, true);
	}

	$rand = $access->getRandomCode(6);
	$filename = $student_code . '_' . $course_code . '_' . $rand;
	$quefile = $filename . '_questions.pdf';
	$ansfile = $filename . '_answers.pdf';
	$ansfileblank = $filename . '_answersheet.pdf';

	$que_paper		= $filepath . '/' . $quefile;
	$ans_ppr		= $filepath . '/' . $ansfile;
	$blank_ppr		= $filepath . '/' . $ansfileblank;
	$created_by  = $_SESSION['user_fullname'];
	$ip_address  = $_SESSION['ip_address'];

	$examrules 		= $exam->get_exam_strucutre($course_id);
	$EXAM_ID 		= $examrules['EXAM_ID'];

	$sql = "INSERT INTO exam_offline_papers (OFFLINE_PAPER_ID,STUD_COURSE_ID,STUDENT_ID,INSTITUTE_ID,EXAM_ID,INSTITUTE_COURSE_ID,EXAM_SECRETE_CODE,QUESTION_PAPER,ANSWER_PAPER,BLANK_ANSWER_PAPER,EXAM_TITLE,EXAM_TOTAL_QUE,EXAM_TOTAL_MARKS,EXAM_PASSING_MARKS,EXAM_TIME,EXAM_MARKS_PER_QUE,EXAM_STATUS,CREATED_BY,CREATED_ON,CREATED_ON_IP) VALUES(NULL, '$STUD_COURSE_DETAIL_ID','$STUDENT_ID','$INSTITUTE_ID','$EXAM_ID','$INSTITUTE_COURSE_ID','$EXAM_SECRETE_CODE','$quefile','$ansfile','$ansfileblank','" . $examrules['EXAM_TITLE'] . "','" . $examrules['TOTAL_QUESTIONS'] . "','" . $examrules['TOTAL_MARKS'] . "','" . $examrules['PASSING_MARKS'] . "','" . $examrules['EXAM_TIME'] . "','" . $examrules['MARKS_PER_QUE'] . "','2','$created_by',NOW(),'$ip_address')";
	$res = $db->execQuery($sql);

	$html			= '<div class="container">';
	$head			= '';
	$body			= '';
	$foot			= '';
	$html_ansheet 	= '';
	$html_correct_ansheet = '';
	$date = date('d/m/Y @ h:i:a');
	$head = '<table style="width:100%;" >
				<tr>
					<td align="center"><img src="resources/dist/img/logo_pdf.png"></td>
				</tr>
				<tr align="middle">
					<td align="center"><h3 class="text-center" style="font-family: serif;">ALL INDIA COUNCIL FOR PROFESSIONAL EXCELLENCE</h3></td>
				</tr>
		</table>';

	$head .= '<table cellspacing="10" style="width:100%; margin-top:20px; padding:10px">
				<tr>
					<td width="18%"><strong>Course Name :</strong></td>
					<td width="45%">' . $course_name . '</td>
					<td width="15%"><strong>Date :</strong></td>
					<td>' . $date . '</td>
				</tr>
				<tr>
					<td><strong>Student Name :</strong></td>
					<td>' . $student_name . '</td>
					<td><strong>Student Code:</strong></td>
					<td>' . $student_code . '</td>
				</tr>
				<tr>
					<td><strong>Institute Name :</strong></td>
					<td>' . $institute_name . '</td>
					<td><strong>Institute Code :</strong></td>
					<td>' . $institute_code . '</td>
				</tr>
		</table>';
	$head .= '<hr />';

	$html_ansheet .= $head;
	$html_correct_ansheet .= $head;

	$head .= '<table cellspacing="10" style="width:100%;">
				<tr>
					<td width="10%"><strong>Exam Duration</strong></td>
					<td width="2%">:</td>
					<td width="75%">' . $examrules['EXAM_TIME'] . ' Minutes</td>					
				</tr>
				<tr>
					<td width="10%"><strong>Total Questions</strong></td>
					<td width="2%">:</td>
					<td width="75%">' . $examrules['TOTAL_QUESTIONS'] . '</td>					
				</tr>
				<tr>
					<td width="10%"><strong>Type of Questions</strong></td>
					<td width="2%">:</td>
					<td width="75%">Multiple Choice, Single Answer</td>					
				</tr>
				<tr>
					<td width="10%"><strong>Total Marks</strong></td>
					<td width="2%">:</td>
					<td width="75%">' . $examrules['TOTAL_MARKS'] . '</td>					
				</tr>
				<tr>
					<td width="10%"><strong>Passing Marks</strong></td>
					<td width="2%">:</td>
					<td width="75%">' . $examrules['PASSING_MARKS'] . '</td>					
				</tr>
				<tr>
					<td width="10%"><strong>Marks/Qusstion</strong></td>
					<td width="2%">:</td>
					<td width="75%">' . $examrules['MARKS_PER_QUE'] . '</td>					
				</tr>
				
		</table>';
	$head .= '<hr />';

	$examdata 		= $exam->generate_offline_paper($course_id, $examrules['TOTAL_QUESTIONS']);
	$body .= '<table class="table" style="width:100%">';

	$srno = 1;
	$correct = array();
	while ($data1 = $examdata->fetch_assoc()) {

		$QUESTION 	= htmlspecialchars_decode($access->utf_encode(($data1['QUESTION'])));
		$OPTION_A 	= htmlspecialchars_decode($access->utf_encode(($data1['OPTION_A'])));
		$OPTION_B 	= htmlspecialchars_decode($access->utf_encode(($data1['OPTION_B'])));
		$OPTION_C 	= htmlspecialchars_decode($access->utf_encode(($data1['OPTION_C'])));
		$OPTION_D 	= htmlspecialchars_decode($access->utf_encode(($data1['OPTION_D'])));
		$IMAGE 		= $data1['IMAGE'];
		$CORRECT_ANS 		= $data1['CORRECT_ANS'];


		$correct[$srno] = $CORRECT_ANS;
		$body .= '<tr>
						<td colspan="4"><strong>' . $srno . '. ' . $QUESTION . '</strong></td>
					</tr>
					<tr>
						<td  style="padding:10px 0px 25px 0px"><input type="text" class="form-control" style="width:12px;"> ' . $OPTION_A . '</td>
						<td><input type="text" class="form-control" style="width:12px;"> ' . $OPTION_B . '</td>
						<td><input type="text" class="form-control" style="width:12px;">  ' . $OPTION_C . '</td>
						<td><input type="text" class="form-control" style="width:12px;"> ' . $OPTION_D . '</td>
					</tr>';
		$srno++;
	}
	$body .= '</table>';
	/*-------generate answer paper------*/
	$srno = $srno - 1;
	$html_ansheet .= '<h4 class="pull-left"> Answer Sheet</h4> <strong class="pull-right">Signature Of Student:_______________</strong>';
	$html_correct_ansheet .= '<h4 class="text-center">Model Correct Answer Sheet</h4>';
	$html_ansheet .= '<table class="html_ansheet"><tr>';
	$html_correct_ansheet .= '<table class="html_ansheet"><tr>';
	$html_ansheet_body1 = '';
	$html_ansheet_head1 = '<td><table width="70%" style="font-size:10px; border-spacing: 4px; float:left">
				<thead>
						<tr>
							<th width="60%"  valign="top"> </th>
							<th> A</th>
							<th> B</th>
							<th> C</th>
							<th> D</th>
						</tr>
					</thead>';
	$html_ansheet_foot1 = '</table></td>';
	$router = 1;
	$i = 1;
	$end = 0;
	//for($i=1; $i<=$srno; $i++)

	while ($i <= $srno) {
		if ($router == 1) {
			$html_ansheet .= $html_ansheet_head1;
			$html_correct_ansheet .= $html_ansheet_head1;
		}
		$html_ansheet .= '<tr class="">
						<td width="60%"  valign="top"><strong>' . $i . '.</strong></td>
						<td valign="top"><input type="text" class="form-control" style="border: 1px solid #f00; width:10px; background-color:black;" value="" /></td>
						<td valign="top"><input type="text" class="form-control" style="border: 1px solid #000; width:10px; " /></td>
						<td valign="top"><input type="text" class="form-control" style="border: 1px solid #000; width:10px; " /></td>
						<td valign="top"><input type="text" class="form-control" style="border: 1px solid #000; width:10px; " /></div></td>
					</tr>		
					';
		$correct_ans = isset($correct[$i]) ? $correct[$i] : '';
		$option_a = $option_b = $option_c = $option_d = '';
		switch ($correct_ans) {
			case ('option_a'):
				$option_a = 'checked="checked"';
				break;
			case ('option_b'):
				$option_b = 'checked="checked"';
				break;
			case ('option_c'):
				$option_c = 'checked="checked"';
				break;
			case ('option_d'):
				$option_d = 'checked="checked"';
				break;
		}
		$html_correct_ansheet .= '<tr>
						<td width="60%"  valign="top"><strong>' . $i . '.</strong></td>
						<td valign="top"><input type="checkbox" class="form-control" style="border: 1px solid #f00; width:10px; background-color:black;" ' . $option_a . '/></td>
						<td valign="top"><input type="checkbox" class="form-control" style="border: 1px solid #000; width:10px; " ' . $option_b . '/></td>
						<td valign="top"><input type="checkbox" class="form-control" style="border: 1px solid #000; width:10px; " ' . $option_c . ' /></td>
						<td valign="top"><input type="checkbox" class="form-control" style="border: 1px solid #000; width:10px; " ' . $option_d . '/></div></td>
					</tr>';
		$router++;
		if ($router == 36) {
			$html_ansheet .= $html_ansheet_foot1;
			$html_correct_ansheet .= $html_ansheet_foot1;
			$router = 1;
			$end = 1;
		}


		$i++;
	}

	if ($router == 1 || $router < 36)  $html_ansheet .= $html_ansheet_foot1;
	if ($router == 1 || $router < 36)  $html_correct_ansheet .= $html_ansheet_foot1;


	$html_ansheet .= '</tr></table>';
	$html_correct_ansheet .= '</tr></table>';


	/*-------generate answer paper------*/




	$html .= $head;
	$html .= $body;
	$html .= '</div>';
	//==============================================================
	//==============================================================
	//==============================================================
	include("include/plugins/pdf/mpdf.php");

	/* --- Print question sheet ------------------- */

	$mpdf = new mPDF('c', 'A4', '', '', 10, 10, 5, 5, 16, 13);
	$mpdf->simpleTables = true;
	$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
	// LOAD a stylesheet

	$stylesheet = file_get_contents('resources/bootstrap/css/bootstrap.css');
	$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
	$mpdf->WriteHTML($html, 2);
	$mpdf->Output($que_paper, 'F');

	/* --- Print blank answer sheet ------------------- */
	// LOAD a stylesheet

	$mpdf = new mPDF('c', 'A4', '', '', 10, 10, 5, 5, 16, 13);
	$mpdf->simpleTables = true;
	$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
	$stylesheet = file_get_contents('resources/bootstrap/css/bootstrap.css');
	$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
	$mpdf->WriteHTML($html_ansheet, 2);
	$mpdf->Output($blank_ppr, 'F');


	/* --- Print correct answer sheet ------------------- */
	$mpdf = new mPDF('c', 'A4', '', '', 10, 10, 5, 5, 16, 13);
	$mpdf->simpleTables = true;
	$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
	$stylesheet = file_get_contents('resources/bootstrap/css/bootstrap.css');
	$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
	$mpdf->WriteHTML($html_correct_ansheet, 2);
	$mpdf->Output($ans_ppr, 'F');
	header('location:page.php?page=download-offline-papers');
	exit;
	//==============================================================
	//==============================================================
	//==============================================================
}
