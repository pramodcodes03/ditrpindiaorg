<?php
ob_clean();
print_r($_REQUEST);exit;

$checkstud = isset($_REQUEST['checkstud'])?$_REQUEST['checkstud']:'';
$certreq = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:'';
$course = isset($_REQUEST['course'])?$_REQUEST['course']:'';

//$checkstud = array(2);

	date_default_timezone_set("Asia/Kolkata");
	include("include/plugins/pdf/mpdf.php");
	include_once('include/classes/exam.class.php');
	$exam 	= new  exam();
	$html='';	
	
	
	$mpdf=new mPDF('c','A4','','',0,0,0,0,16,13); 
	//$id = base64_decode($id);
	$cond = " AND AICPE_COURSE_ID='$course' AND STUDENT_ID='$user_id' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1";
	$res = $exam->list_halltickets('','', $cond);	

	//$res 	= $exam->list_certificates_requests($id, '', '', '');
	$data 	= $res->fetch_assoc();
	
	extract($data);
	
	$STUD_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;
	
	$type = pathinfo($STUD_PHOTO, PATHINFO_EXTENSION);
	$cert_photo = $filename.'.'.$type;
	$STUD_PHOTO_CERT = CERTIFICATE_PATH.'/photos/'.$cert_photo;
	
	
	
	//==============================================================

	
	$idproof='';
	$courseduration='';
	$FILE_DESC = isset($FILE_DESC)?$FILE_DESC:'';
	$FILE_CATEGORY = isset($FILE_CATEGORY)?$FILE_CATEGORY:'';
	
	if($STUD_ID_PROOF_NUMBER!='' && $STUD_ID_PROOF_TYPE!='')
	{
		$idproof = '<h4 class="idproof"> '.htmlspecialchars_decode($STUD_ID_PROOF_TYPE).' : '.htmlspecialchars_decode($STUD_ID_PROOF_NUMBER).' </h4>';
	}
	if($COURSE_DURATION!='')
	{
		$courseduration = '<h3 class="courseduration"> (COURSE DURATION: '.htmlspecialchars_decode($COURSE_DURATION).')</h3>';
	}
	
	
	$html = '
	<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.studname{position:absolute;top:590px;text-align:center;width:100%;}
	.idproof{position:absolute;top:620px;text-align:center;width:100%;font-weight:normal}
	
	.atcname{position:absolute;top:670px;text-align:center;width:100%; font-size:12px;}
	
	.grade{position:absolute;top:710px;left:53%; width:100px;}
	.marks{	position:absolute;top:710px;left:71%; width:100px;}
	.coursename{position:absolute;top:775px;text-align:center;width:100%; font-size:16px;}
		.courseduration{position:absolute;top:800px;text-align:center;width:100%; font-size:10px;font-weight:normal;}
	.certicateno{position:absolute;	bottom:75px;left:44%;}
	.date{position:absolute;bottom:55px;left:44%;}
	
	.studphoto{position:absolute;top:385px;margin-right:5.9%;width:1px; height:143px;background-image:url("'.$STUD_PHOTO.'"); background-size:118px 143px; background-repeat:no-repeat;}
	</style>';
	$html .= '
	<!-- <img src="resources/dist/img/democertificate.jpg" style="width:100%" />  -->
	<div class="studphoto"></div>
				<h2 class="studname">'.$STUDENT_NAME.'</h2>
				<h3 class="atcname">ATC : '.htmlspecialchars_decode($INSTITUTE_NAME).' | '.htmlspecialchars_decode($INSTITUTE_CITY).'</h3>
				'.$idproof.'
				<h2 class="grade"> '.$GRADE.' </h2>
				<h2 class="marks">'.$MARKS_PER.' %</h2>
				<h3 class="coursename">'.htmlspecialchars_decode($COURSE_NAME).'</h3>
				'.$courseduration.'
				<h3 class="certicateno">'.$CERTIFICATE_NO.'</h3>
				<h3 class="date">'.$ISSUE_DATE_FORMAT.'</h3>
			
				';

	//==============================================================
	$mpdf->WriteHTML($html);
	$mpdf->Output($file,'I');
	
	

ob_end_flush();
?>