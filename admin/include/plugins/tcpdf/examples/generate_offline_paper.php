<?php

if(!isset($_SESSION)) session_start();

//include connections

include_once($_SERVER['DOCUMENT_ROOT'].'/app/include/classes/config.php');



/*

if(!isset($_SESSION['user_login_id']))

{

	$redirect = HOST.'/login.php';

	header('location:'.$redirect);

}

*/



$action = isset($_REQUEST['generate_exam'])?$_REQUEST['generate_exam']:'';



if($action!='')

{



include_once(ROOT.'/include/classes/database_results.class.php');

include_once(ROOT.'/include/classes/access.class.php');

include_once(ROOT.'/include/classes/exam.class.php');

$db 	= new  database_results();

$access = new  access();

$exam 	= new  exam();



$course_id		= $db->test(isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'');

$student_id		= $db->test(isset($_REQUEST['student_id'])?$_REQUEST['student_id']:'');

$institute_id	= $db->test(isset($_REQUEST['institute_id'])?$_REQUEST['institute_id']:'');



$student_name	= $db->get_stud_name($student_id);

$institute_code	= $db->get_institute_code($institute_id);

$course_name	= $db->get_course_name($course_id);



$total_que		= $db->get_exam_total_marks($course_id);



$file_name_que	= $db->rename_offline_paper_pdf($course_name, 'question_paper');

$file_name_ans	= $db->rename_offline_paper_pdf($course_name, 'answer_paper');



$today_date		= date('d/m/Y');	

$res_que 			= $exam->generate_offline_paper($institute_id,$course_id,$total_que);

// Include the main TCPDF library (search for installation path).

require_once('tcpdf_include.php');



// Extend the TCPDF class to create custom Header and Footer

class MYPDF extends TCPDF {



	//Page header

	public function Header() {

		// Logo

/*	

	$image_file = K_PATH_IMAGES.'logo.png';		

		$this->setImageScale(2);

		$this->Image($image_file, 'C', 6, '', '', 'PNG', false, 'C', false, 300, 'C', false, false, 0, false, false, false);

		// Set font

		$this->SetFont('helvetica', 'B', 15);

		// Title

		$this->setCellPaddings(0,25,0,0);

		$this->setCellMargins(0,22,0,0);

		$this->Cell(0, 15, 'DIGITAL INFORMATION TECHNOLOGY and RESEARCH FOR PROFESSIONALS', 'B', false, 'C', 0, '', 0, false, 'M', 'M');

		include_once(ROOT.'/include/classes/database_results.class.php');

		$db = new  database_results();

		$course_id		= isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'';

		// Set font

		$today_date		= date('d/m/Y');

		$course_name	= $db->get_course_name($course_id);

		$this->SetFont('helvetica', 'B', 11);

		$this->setCellMargins(0,30,0,0);

		$this->Cell(0, 15, ' Course: '.$course_name.'                                                                                                      Date: '.$today_date, '', false, 'R', 0, '', 0, false, 'M', 'C');

		

		//$this->setCellMargins(0,30,0,0);

	//	$this->Cell(0, 15, ' Date: '.$today_date, 1, false, 'L', 0, '', 0, false, 'M', 'C');

	//	$this->Cell(0, 15, ' Date: '.$today_date, 1, false, 'L', 0, '', 0, false, 'M', 'C');

	//	$this->Cell(0, 15, ' Date: '.$today_date, 1, false, 'L', 0, '', 0, false, 'M', 'C');

		*/

	}



	// Page footer

	public function Footer() {

		// Position at 15 mm from bottom

		$this->SetY(-15);

		// Set font

		$this->SetFont('helvetica', 'I', 8);

		// Page number

		//$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

	}

}



// create new PDF document

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



// set document information

$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('DITRP');

$pdf->SetTitle('dsdTCPDF Example 003');

$pdf->SetSubject('TCPDF Tutorial');

$pdf->SetKeywords('TCPDF, PDF, example, test, guide');



// set default header data

//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);



// set header and footer fonts

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));



// set default monospaced font

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



// set margins

$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);

$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);



// set auto page breaks

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



// set image scale factor

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// set some language-dependent strings (optional)

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {

	require_once(dirname(__FILE__).'/lang/eng.php');

	$pdf->setLanguageArray($l);

}



// ---------------------------------------------------------



// set font

//$pdf->SetFont('times', 'BI', 12);



// add a page

$pdf->AddPage();

$srno=1;

$html_que = '';

$html_ansheet = '';

$html_ansheet_correct = '';

$html_ans = '';

$logo = ROOT.'/resources/dist/img/logo_pdf.png';

$check = ROOT.'/resources/dist/img/checkbox.png';

$CORRECT_ANS_ARR = array();

while($data = $res_que->fetch_assoc())

{

	$QUESTION 	= htmlspecialchars_decode($access->utf_encode(($data['QUESTION'])));

	$IMAGE 		= $data['IMAGE'];

	$OPTION_A 	= htmlspecialchars_decode($access->utf_encode(($data['OPTION_A'])));

	$OPTION_B 	= htmlspecialchars_decode($access->utf_encode(($data['OPTION_B'])));

	$OPTION_C 	= htmlspecialchars_decode($access->utf_encode(($data['OPTION_C'])));

	$OPTION_D 	= htmlspecialchars_decode($access->utf_encode(($data['OPTION_D'])));

	$CORRECT_ANS= htmlspecialchars_decode($access->utf_encode(($data['CORRECT_ANS'])));

	$CORRECT_ANS_ARR[$srno] = $CORRECT_ANS;

	$EXAM_NAME 	= $data['EXAM_NAME'];

	$CREATED_DATE = $data['CREATED_DATE'];

	

	$TOTAL_MARKS = $data['TOTAL_MARKS'];

	$TOTAL_QUESTIONS 	= $data['TOTAL_QUESTIONS'];

	$PASSING_MARKS= $data['PASSING_MARKS'];

	$MARKS_PER_QUE= $data['MARKS_PER_QUE'];

	$EXAM_TIME 	  = $data['EXAM_TIME'];

	if($srno<=$TOTAL_QUESTIONS)

	{

	$html_que .='<table class="que-ppr">

			<!-- Questions -->

			<tr>

				<td colspan="4">

					<strong>'.$srno.'.</strong> '.$QUESTION.'

				</td>

			</tr>

			<tr class="ans-opts">			

				<td>					

					<table>

						<tr>

							<td width="10%" valign="top">

								A.

							</td>

							<td> '.$OPTION_A.'</td>

						</tr>

					</table>

				</td>

				<td>					

					<table>

						<tr>

							<td width="10%" valign="top">

								B.

							</td>

							<td> '.$OPTION_B.'</td>

						</tr>

					</table>

				</td>

				<td>					

					<table>

						<tr> 

							<td width="10%" valign="top">

								C.

							</td>

							<td> '.$OPTION_C.'</td>

						</tr>

					</table>

				</td>

				<td>					

					<table>

						<tr>

							<td width="10%" valign="top">

								D.

							</td>

							<td> '.$OPTION_D.'</td>

						</tr>

					</table>

				</td>

			</tr>		

		</table>';

		

	}

		$srno++;

}



 $preColm = round($srno/5);

$arr = array($preColm,$preColm*2,$preColm*3,$preColm*4,$preColm*5);

$bg = 'background-color:#000';

$html_ansheet .='<table class="html_ansheet"><tr>';





$html_ansheet .='<td><table width="70%" style="font-size:10px; border-spacing: 4px; float:left">

				<thead>

						<tr>

							<th width="60%"  valign="top"> </th>

							<th> A</th>

							<th> B</th>

							<th> C</th>

							<th> D</th>

						</tr>

					</thead>

				';

				for($i=1; $i<=$arr[0]; $i++)

				{

					

					$html_ansheet .='<tr class="">

										<td width="60%"  valign="top"><strong>'.$i.'.</strong></td>

										<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

										<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

										<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

										<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

									</tr>';

				}

$html_ansheet .= '</table></td>';





$html_ansheet .='<td><table width="70%" style="font-size:10px; border-spacing: 4px; float:left">

				<thead>

						<tr>

							<th width="60%"  valign="top"> </th>

							<th> A</th>

							<th> B</th>

							<th> C</th>

							<th> D</th>

						</tr>

					</thead>

				';

for($i=$arr[0]+1; $i<=$arr[1]; $i++)

{

	

	

	$html_ansheet .='<tr class="">

						<td width="60%"  valign="top"><strong>'.$i.'.</strong></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; "></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

					</tr>		

					';

}

$html_ansheet .= '</table></td>';



$html_ansheet .='<td><table width="70%" style="font-size:10px; border-spacing: 4px; float:left">

				<thead>

						<tr>

							<th width="60%"  valign="top"> </th>

							<th> A</th>

							<th> B</th>

							<th> C</th>

							<th> D</th>

						</tr>

					</thead>

				';

for($i=$arr[1]+1; $i<=$arr[2]; $i++)

{

	

	

	$html_ansheet .='<tr class="">

						<td width="60%"  valign="top"><strong>'.$i.'.</strong></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; "></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

					</tr>		

					';

}

$html_ansheet .= '</table></td>';



$html_ansheet .='<td><table width="70%" style="font-size:10px; border-spacing: 4px; float:left">

				<thead>

						<tr>

							<th width="60%"  valign="top"> </th>

							<th> A</th>

							<th> B</th>

							<th> C</th>

							<th> D</th>

						</tr>

					</thead>

				';

for($i=$arr[2]+1; $i<=$arr[3]; $i++)

{

	

	

	$html_ansheet .='<tr class="">

						<td width="60%"  valign="top"><strong>'.$i.'.</strong></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; "></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

					</tr>		

					';

}

$html_ansheet .= '</table></td>';





$html_ansheet .='<td><table width="70%" style="font-size:10px; border-spacing: 4px; float:left">

				<thead>

						<tr>

							<th width="60%"  valign="top"> </th>

							<th> A</th>

							<th> B</th>

							<th> C</th>

							<th> D</th>

						</tr>

					</thead>

				';

for($i=$arr[3]+1; $i<=$arr[4]; $i++)

{

	

	$html_ansheet .='<tr class="">

						<td width="60%"  valign="top"><strong>'.$i.'.</strong></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; "></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;"></div></td>

					</tr>		

					';

}

$html_ansheet .= '</table></td>';



$html_ansheet .= '</tr></table>';



/* ------------------Generate correct answersheet -------------------------- */

$html_ansheet_correct .='<table class="html_ansheet"><tr>';

$html_ansheet_correct .='<td><table width="70%" style="font-size:10px; border-spacing: 4px; float:left">

				<thead>

						<tr>

							<th width="60%"  valign="top"> </th>

							<th> A</th>

							<th> B</th>

							<th> C</th>

							<th> D</th>

						</tr>

					</thead>

				';

				for($i=1; $i<=$arr[0]; $i++)

				{

					$correct_opt = isset($CORRECT_ANS_ARR[$i])?$CORRECT_ANS_ARR[$i]:'';

					$option_a = ($correct_opt=='option_a')?$bg:'';

					$option_b = ($correct_opt=='option_b')?$bg:'';

					$option_c = ($correct_opt=='option_c')?$bg:'';

					$option_d = ($correct_opt=='option_d')?$bg:'';

					$html_ansheet_correct .='<tr class="">

										<td width="60%"  valign="top"><strong>'.$i.'.</strong></td>

										<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_a.'"></div></td>

										<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_b.'"></div></td>

										<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_c.'"></div></td>

										<td valign="top"><div style="height: 2px; border: 1px solid #000;'.$option_d.'"></div></td>

									</tr>';

				}

$html_ansheet_correct .= '</table></td>';





$html_ansheet_correct .='<td><table width="70%" style="font-size:10px; border-spacing: 4px; float:left">

				<thead>

						<tr>

							<th width="60%"  valign="top"> </th>

							<th> A</th>

							<th> B</th>

							<th> C</th>

							<th> D</th>

						</tr>

					</thead>

				';

for($i=$arr[0]+1; $i<=$arr[1]; $i++)

{

	

	$correct_opt = isset($CORRECT_ANS_ARR[$i])?$CORRECT_ANS_ARR[$i]:'';

	$option_a = ($correct_opt=='option_a')?$bg:'';

	$option_b = ($correct_opt=='option_b')?$bg:'';

	$option_c = ($correct_opt=='option_c')?$bg:'';

	$option_d = ($correct_opt=='option_d')?$bg:'';

	$html_ansheet_correct .='<tr class="">

						<td width="60%"  valign="top"><strong>'.$i.'.</strong></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_a.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_b.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_c.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;'.$option_d.'"></div></td>

					</tr>		

					';

}

$html_ansheet_correct .= '</table></td>';



$html_ansheet_correct .='<td><table width="70%" style="font-size:10px; border-spacing: 4px; float:left">

				<thead>

						<tr>

							<th width="60%"  valign="top"> </th>

							<th> A</th>

							<th> B</th>

							<th> C</th>

							<th> D</th>

						</tr>

					</thead>

				';

for($i=$arr[1]+1; $i<=$arr[2]; $i++)

{

	$correct_opt = isset($CORRECT_ANS_ARR[$i])?$CORRECT_ANS_ARR[$i]:'';

	$option_a = ($correct_opt=='option_a')?$bg:'';

	$option_b = ($correct_opt=='option_b')?$bg:'';

	$option_c = ($correct_opt=='option_c')?$bg:'';

	$option_d = ($correct_opt=='option_d')?$bg:'';

	

	$html_ansheet_correct .='<tr class="">

						<td width="60%"  valign="top"><strong>'.$i.'.</strong></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_a.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_b.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_c.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;'.$option_d.'"></div></td>

					</tr>		

					';

}

$html_ansheet_correct .= '</table></td>';



$html_ansheet_correct .='<td><table width="70%" style="font-size:10px; border-spacing: 4px; float:left">

				<thead>

						<tr>

							<th width="60%"  valign="top"> </th>

							<th> A</th>

							<th> B</th>

							<th> C</th>

							<th> D</th>

						</tr>

					</thead>

				';

for($i=$arr[2]+1; $i<=$arr[3]; $i++)

{

	$correct_opt = isset($CORRECT_ANS_ARR[$i])?$CORRECT_ANS_ARR[$i]:'';

	$option_a = ($correct_opt=='option_a')?$bg:'';

	$option_b = ($correct_opt=='option_b')?$bg:'';

	$option_c = ($correct_opt=='option_c')?$bg:'';

	$option_d = ($correct_opt=='option_d')?$bg:'';

	

	$html_ansheet_correct .='<tr class="">

						<td width="60%"  valign="top"><strong>'.$i.'.</strong></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_a.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_b.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_c.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;'.$option_d.'"></div></td>

					</tr>		

					';

}

$html_ansheet_correct .= '</table></td>';





$html_ansheet_correct .='<td><table width="70%" style="font-size:10px; border-spacing: 4px; float:left">

				<thead>

						<tr>

							<th width="60%"  valign="top"> </th>

							<th> A</th>

							<th> B</th>

							<th> C</th>

							<th> D</th>

						</tr>

					</thead>

				';

for($i=$arr[3]+1; $i<=$arr[4]; $i++)

{

	$correct_opt = isset($CORRECT_ANS_ARR[$i])?$CORRECT_ANS_ARR[$i]:'';

	$option_a = ($correct_opt=='option_a')?$bg:'';

	$option_b = ($correct_opt=='option_b')?$bg:'';

	$option_c = ($correct_opt=='option_c')?$bg:'';

	$option_d = ($correct_opt=='option_d')?$bg:'';

	

	$html_ansheet_correct .='<tr class="">

						<td width="60%"  valign="top"><strong>'.$i.'.</strong></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_a.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_b.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000; '.$option_c.'"></div></td>

						<td valign="top"><div style="height: 2px; border: 1px solid #000;'.$option_d.'"></div></td>

					</tr>		

					';

}

$html_ansheet_correct .= '</table></td>';



$html_ansheet_correct .= '</tr></table>';



/* ------------------Generate correct answersheet -------------------------- */

// set some text to print

$question_tpl = <<<EOD

<!DOCTYPE html>

<html>

<head>

<title>PDF</title>

<style type="text/css">

 *{color:#000;} table{border-collapse: collapse; width:100%; margin:auto; padding:auto;} .logo, .inst-name{text-align:center;} .logo img{height:30px;} .inst-name h2{text-transform: capitalize;} .sub-table{width:100%;} .course-name{text-align:left ;} .exam-date{text-align:right;} .answers{list-style:none;} .answers li{float:left; margin-right:30px} .b-btm{border-bottom: 0.5em solid;} .ans-opts td{padding: 20px 0px 30px 0px;} .que-ppr{margin-bottom:50px;font-size:12px; font-weight:bold} .que-ppr tr td{height:40px;} .sub-table td{padding:10px} .check{height: 17px; width: 17px; border: 1px solid #000; margin-right: 5px;} .check, .check-lbl{float:left;} .clear{clear:both;} .html_ansheet thead th{font-weight:bold}

</style>

</head>

<body>

<table class="main-table" cellpadding="2">

<tr>

	<td class="logo">

		<img src="$logo" />

	</td>

</tr>

<tr>	

	<td class="inst-name">

		<h2>DIGITAL INFORMATION TECHNOLOGY and RESEARCH FOR PROFESSIONALS</h2>

	</td>	

</tr>



<tr>



<td class="b-btm">

	<table class="sub-table">

		<tr>

			<td class="course-name"><strong>Course Name:</strong> $course_name </td>

			<td class="exam-date"><strong>Date:</strong> $today_date </td>

		</tr>

		<tr>

			<td class="course-name"><strong>Student Name:</strong> $student_name </td>

			<td class="exam-date"><strong>ATC Code:</strong> $institute_code </td>

		</tr>

		

	</table>

</td>

</tr>

<tr>

<td colspan="2"></td>

</tr>

<tr>

	<td class="b-btm">

		<ul>

			<li><strong>Exam Duration:</strong> $EXAM_TIME Minutes</li>

			<li><strong>Total Questions:</strong> $TOTAL_QUESTIONS</li>

			<li><strong>Type of Questions:</strong>Multiple Choice, Single Answer</li>

			<li><strong>Total Marks:</strong> $TOTAL_MARKS</li>

			<li><strong>Passing Marks:</strong> $PASSING_MARKS</li>

			<li><strong>Marks/Question:</strong> $MARKS_PER_QUE</li>

		</ul>

	</td>

</tr>

<tr>

	<td style="padding-top:20px;">

		$html_que

	</td>

</tr>



</table>

</body>

</html>

EOD;

/* -------------------------- answer sheet blank pdf ------------------------------- */

// set some text to print

$answer_bubble_tpl = <<<EOD

<!DOCTYPE html>

<html>

<head>

<title>PDF</title>

<style type="text/css">

 *{color:#000;} table{border-collapse: collapse; width:100%; margin:auto; padding:auto;} .logo, .inst-name{text-align:center;} .logo img{height:20px;} .inst-name h2{text-transform: capitalize;} .sub-table{width:100%;} .course-name{text-align:left ;} .exam-date{text-align:right;} .answers{list-style:none;} .answers li{float:left; margin-right:30px} .b-btm{border-bottom: 0.5em solid;} .ans-opts td{padding: 20px 0px 30px 0px;} .que-ppr{margin-bottom:50px;font-size:12px; font-weight:bold} .que-ppr tr td{height:40px;} .sub-table td{padding:10px} .check{height: 17px; width: 17px; border: 1px solid #000; margin-right: 5px;} .check, .check-lbl{float:left;} .clear{clear:both;}

</style>

</head>

<body>

<table class="main-table"  cellspacing="10">

<tr>

	<td class="logo">

		<img src="$logo" />

	</td>

</tr>

<tr>	

	<td class="inst-name">

		<h2>DIGITAL INFORMATION TECHNOLOGY and RESEARCH FOR PROFESSIONALS</h2>

	</td>	

</tr>



<tr>



<td class="b-btm" >

	<table class="sub-table" cellpadding="5">

		<tr>

			<td class="course-name"><strong>Course Name:</strong> $course_name </td>

			<td class="exam-date"><strong>Date:</strong> $today_date </td>

		</tr>

		<tr>

			<td class="course-name"><strong>Student Name:</strong> $student_name </td>

			<td class="exam-date"><strong>ATC Code:</strong> $institute_code </td>

		</tr>

		

	</table>

</td>

</tr>

<tr>

<td colspan="2" valign="center"><h3 style="text-align:center; text-decoration:underline; color:#000">ANSWER SHEET</h3></td>

</tr>

<tr>

	<td>

		$html_ansheet

	</td>

</tr>

</table>

</body>

</html>

EOD;

/* ####### answer sheet correct copy ########### */

// set some text to print

$answer_correct_bubble_tpl = <<<EOD

<!DOCTYPE html>

<html>

<head>

<title>PDF</title>

<style type="text/css">

 *{color:#000;} table{border-collapse: collapse; width:100%; margin:auto; padding:auto;} .logo, .inst-name{text-align:center;} .logo img{height:20px;} .inst-name h2{text-transform: capitalize;} .sub-table{width:100%;} .course-name{text-align:left ;} .exam-date{text-align:right;} .answers{list-style:none;} .answers li{float:left; margin-right:30px} .b-btm{border-bottom: 0.5em solid;} .ans-opts td{padding: 20px 0px 30px 0px;} .que-ppr{margin-bottom:50px;font-size:12px; font-weight:bold} .que-ppr tr td{height:40px;} .sub-table td{padding:10px} .check{height: 17px; width: 17px; border: 1px solid #000; margin-right: 5px;} .check, .check-lbl{float:left;} .clear{clear:both;}

</style>

</head>

<body>

<table class="main-table"  cellspacing="10">

<tr>

	<td class="logo">

		<img src="$logo" />

	</td>

</tr>

<tr>	

	<td class="inst-name">

		<h2>DIGITAL INFORMATION TECHNOLOGY and RESEARCH FOR PROFESSIONALS</h2>

	</td>	

</tr>



<tr>



<td class="b-btm" >

	<table class="sub-table" cellpadding="5">

		<tr>

			<td class="course-name"><strong>Course Name:</strong> $course_name </td>

			<td class="exam-date"><strong>Date:</strong> $today_date </td>

		</tr>

		<tr>

			<td class="course-name"><strong>Student Name:</strong> $student_name </td>

			<td class="exam-date"><strong>ATC Code:</strong> $institute_code </td>

		</tr>

		

	</table>

</td>

</tr>

<tr>

<td colspan="2" valign="center"><h3 style="text-align:center; text-decoration:underline; color:#000">CORRECT ANSWER SHEET</h3></td>

</tr>

<tr>

	<td>

		$html_ansheet_correct

	</td>

</tr>

</table>

</body>

</html>

EOD;





/* -------------------------- answer sheet pdf ------------------------------- */

// print a block of text using Write()

$pdf->writeHTML($question_tpl, true, false, true, false, '');

// ---------------------------------------------------------



// print a block of text using Write()

$pdf->AddPage();

$pdf->writeHTML($answer_bubble_tpl, true, false, true, false, '');



$pdf->AddPage();

$pdf->writeHTML($answer_correct_bubble_tpl, true, false, true, false, '');



//Close and output PDF document

//$pdf->Output(EXAM_OFFLINE_PAPER_PATH.'/test.pdf', 'F');

$pdf->Output('file_name_que.pdf', 'I');

$pdf->Output('file_name_ans.pdf', 'I');





//============================================================+

// END OF FILE

//============================================================+

}else{

	echo "Invalid Request!";

}