<?php
ob_clean();
ob_start();
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

include_once('include/classes/tools.class.php');
$tools = new tools();
$resB = $tools->list_backgroundimages('', $user_id, '');
if ($resB != '') {
	$srno = 1;
	while ($dataB = $resB->fetch_assoc()) {
		extract($dataB);
		$imageId = $dataB['inst_id'];
		$certificate_image = $dataB['certificate_image'];
		$certificate_image    = BACKGROUND_IMAGE_PATH . '/' . $imageId . '/' . $certificate_image;
	}
}

$PHOTO = 'resources/dummy/dummy-photo.png';
$STUD_SIGN = 'resources/dummy/dummy-signature.png';
$file = 'resources/dummy/dummy_qr.png';
$idproof = '<h4 class="idproof"> Aadhar Card Number : 440044004400 </h4>';
$courseduration = '<h3 class="courseduration"> (COURSE DURATION: 1 YEAR )</h3>';
$course_period = '<h3 class="courseperiod"> ((COURSE PERIOD: 29 Jul 2022 TO 28 Jul 2023))</h3>';

$html = '
	<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.studname{position:absolute;top:605px;text-align:center;width:100%;}
	.idproof{position:absolute;top:625px;text-align:center;width:100%;font-weight:normal}
	
	.atcname{position:absolute;top:665px;text-align:center;width:100%; font-size:14px; color:#f90404;text-transform:uppercase}
	
	.grade{position:absolute;top:715px;left:55%; width:100px;}
	.marks{	position:absolute;top:715px;left:69.5%; width:100px; font-size:18px}
	.coursename{position:absolute;top:765px;text-align:center;width:100%; font-size:16px;}
		.courseduration{position:absolute;top:790px;text-align:center;width:100%; font-size:10px;font-weight:normal;}
	.certicateno{position:absolute;	bottom:80px;left:39%; font-size:13px;}
	.date{position:absolute;bottom:60px;left:39%; font-size:13px;}
	
	.courseperiod{position:absolute;top:805px;text-align:center;width:100%; font-size:10px;font-weight:normal;}
	
	.studphoto{position:absolute;top:420px;right:42.9%;width:80px; height:100px;background-image:url("' . $PHOTO . '"); background-size:80px 100px; background-repeat:no-repeat; border:2px solid #000;}

	.studsign{position:absolute;top:520px;left:45%;width:100px; height:35px;background-image:url("' . $STUD_SIGN . '"); background-size:100px 35px; background-repeat:no-repeat; border:2px solid #000;}

	.instsign{position:absolute;bottom:105px;left:8%;width:135px; height:40px;background-image:url("' . $INSTITUTE_SIGN . '"); background-size:135px 40px; background-repeat:no-repeat; border:0px solid #000;}

	.inststamp{position:absolute;bottom:125px;left:9%;width:135px; height:40px;background-image:url("' . $INSTITUTE_STAMP . '"); background-size:135px 40px; background-repeat:no-repeat;}
    .weblink{position:absolute;bottom:48px;left:55%; font-size:14px;}
    
    .ownername{position:absolute;bottom:80px;left:8%;  font-weight:normal; font-size:12px;}
    
    .line{position:absolute;bottom:90px;left:8%;}
    .bttext{position:absolute;bottom:65px;left:8%; font-weight:600; font-size:12px;}

	.qrheadtext{position:absolute;top:30.5%;left:73.5%; font-size:12px;font-weight:900; text-align:center; width:150px;}
    .qrcodeimage{position:absolute;top:35.1%;width:65px; height:65px; text-align:center; float:right; left:78%;}
    
    .bottomatcname{position:absolute;bottom:230px;text-align:center;width:100%; font-size:14px; color:#f90404;text-transform:uppercase}
	</style>';
$html .= '
				<img src="' . $certificate_image . '" style="width:100%" />
				<h3 class="qrheadtext"><b> FOR ONLINE VERIFICATION SCAN </b></h3>
				<div class="qrcodeimage"><img src="' . $file . '"></div> 
				<div class="studphoto"></div>
				<div class="studsign"></div>
				<h2 class="studname">RAHUL DAS S/O RAM DAS</h2>
				<h3 class="atcname">NEXSTEP COMPUTER ACADEMY</h3>
				' . $idproof . '
				<h2 class="grade"> A+ </h2>
				<h2 class="marks">87.00 %</h2>
				<h3 class="coursename"> ADVANCE DIPLOMA IN COMPUTER APPLICATION </h3>
				' . $courseduration . '<br/> ' . $course_period . '
				
				<h3 class="certicateno"> Certificate No : JR7IWFBG1 </h3>
				<h3 class="date"> Date Of Issue : 10-08-2023 </h3>	
				
					<div class="instsign"></div>
				<div class="line">---------------------------------------------</div>
				<div class="ownername">NEXSTEP COMPUTER ACADEMY</div>
			    <div class="bttext">Controller Of Examination</div>
			     <div class="weblink">www.ditrp.digitalnexstep.com</div>
			
				';
//==============================================================
$mpdf->WriteHTML($html);

$mpdf->Output('certificate.pdf', 'I');

ob_flush();
ob_end_flush();
