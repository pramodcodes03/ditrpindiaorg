<?php
ob_clean();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

include_once('include/plugins/mpdf8/autoload.php');
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
$mpdf->AddPageByArray([
	'margin-left' => 0,
	'margin-right' => 0,
	'margin-top' => 0,
	'margin-bottom' => 0,
]);
$html = '';

include_once('include/classes/tools.class.php');
$tools = new tools();

$resB = $tools->list_backgroundimages('', '1', '');
if ($resB != '') {
	$srno = 1;
	while ($dataB = $resB->fetch_assoc()) {
		extract($dataB);
		$marksheet_image      = BACKGROUND_IMAGE_PATH . '/' . $inst_id . '/' . $marksheet_image;
	}
}
$file = 'resources/dummy/dummy_qr.png';

$html2 =
	'<style>
	body {padding:0;font-family: sans-serif; font-size: 9pt;position:absolute;z-index:0;top:0px;}
	.studname{position:absolute;top:25.7%;left:29%;width:35%}
	.fathername{position:absolute;top:27.2%;left:29%; width:35%}
	.surname{position:absolute;top:28.5%;left:29%; width:35%}
	.institudename{position:absolute;top:35%;left:29%;width:100%;font-size: 10pt;text-transform: uppercase;}
	.dob{position:absolute;top:28.4%;left:72%; width:15%}
	
	.courseperiod{position:absolute;top:30%;left:72%; width:40%;}
	
	.coursedur{position:absolute;top:25%;left:72%; width:15%}
	.coursename{position:absolute;top:31.9%;left:29%; border:1px }
	.mothername{position:absolute;top:30%;left:29%; width:23%}
	.marksheetno{position:absolute;top:26.8%;left:72%; width:30%}
	.subject{position:absolute;top:42.2%;left:20%; border:1px;width:30%; text-transform: uppercase; text-align:justify; line-height:20px;}
	
	.practicle{position:absolute;top:44.2%;left:63.2%; height:50px;width:50px;font-size:14px; }
	.subjectmark{position:absolute;top:44.2%;left:70.5%; height:50px;width:50px;font-size:14px; }
	.total{position:absolute;top:44.2%;left:78%; height:50px;width:50px;font-size:14px; }

	.total_bottom{position:absolute;top:69%;left:78%; font-size:16px;height:50px;width:50px; }

	.subject1{position:absolute;left:20%;border:1px;width:30%; text-transform: uppercase; text-align:justify; line-height:20px;}
	.practicle1{position:absolute;left:70.2%; height:50px;width:50px;font-size:14px;}
	.subjectmark1{position:absolute;left:78.2%; height:50px;width:50px;font-size:14px; }
	.srno1{position:absolute;left:18%; border:1px;width:30%; text-transform: uppercase; text-align:justify; line-height:20px;}
    table tr.highlight td{padding-top:5px; padding-bottom:5px; font-size:10px; font-weight:bold;}
    
    .outof_bottom{position:absolute;top:68.5%;left:45%; font-size:11px;height:50px;width:150px; }
    .percentage_bottom{position:absolute;top:68.5%;left:18%; font-size:11px;height:50px;width:150px;}
    .grade_bottom{position:absolute;top:68.5%;left:35%; font-size:11px;height:50px;width:100px;}
    
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
	</style>';

$html .= "<div class='studname'>RAHUL</div>";
$html .= "<div class='fathername'>RAM</div>";
$html .= "<div class='surname'>DAS</div>";
$html .= "<div class='institudename'>NEXSTEP COMPUTER ACADEMY</div>";
$html .= "<div class='dob'>28-08-1999</div>";

$html .= "<div class='courseperiod'><span class='period_date'>29 Jul 2022 To 28 Jul 2023 </span></div>";

$html .= "<div class='coursedur'>1 YEAR</div>";
$html .= "<div class='coursename'>CERTIFICATE IN COMPUTER APPLICATION</div>";
$html .= "<div class='mothername'>PUJA DAS</div>";
$html .= "<div class='marksheetno'>JR7IWFBG1</div>";

$marksHtml = '<div style="position:absolute; top:490px; right:115px; left:135px;">';
$marksHtml .= "<table style='width:100%; font-size: 10pt; margin-left:10px'>";

$marksHtml .= "<tr class='highlight'>";
$marksHtml .= "<td width='25%'>  MS-OFFICE (Word, Excel, Power-Point) </td>";
$marksHtml .= "<td width='7%'>44</td>";
$marksHtml .= "<td width='7%'>43</td>";
$marksHtml .= "<td width='7%'>87</td>";
$marksHtml .= '</tr>';



$marksHtml .= "</table>";
$marksHtml .= '</div>';

$html .= $marksHtml;
$html .= "<div class='percentage_bottom'><b> Percentage : 87.00%</b></div>";

$html .= "<div class='grade_bottom'><b> Grade : A+ </b></div>";

$html .= "<div class='outof_bottom'><b> Total Marks : 87 / 100</b></div>";

$html .= "<div class='total_bottom'><b>870</b></div>";


$html2 .= '<div class="qrcodeimage"><img src="' . $file . '"></div>';
$html .= "<div class='weblink'> Online Certificate Verification available on : www.ditrp.digitalnexstep.com </div>";

$html2 .= '<img src="' . $marksheet_image . '" style="width:100%" />';
$html2 .= $html;

$mpdf->WriteHTML($html2);
//$mpdf->Output($file,'I');
$mpdf->Output('Marksheet.pdf', 'I');


ob_end_flush();
