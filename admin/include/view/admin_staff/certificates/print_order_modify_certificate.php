<?php
ini_set("memory_limit", "128M");
$cert_detail_id = $db->test(isset($_GET['cert_detail_id']) ? $_GET['cert_detail_id'] : '');
$action 		= isset($_REQUEST['print_certificate']) ? $_REQUEST['print_certificate'] : '';


if ($action != '') {
	date_default_timezone_set("Asia/Kolkata");
	include("include/plugins/pdf/mpdf.php");

	$CERTIFICATE_DETAILS_ID = $db->test(isset($_REQUEST['certificate_details_id']) ? $_REQUEST['certificate_details_id'] : '');
	$STUDENT_ID 			= $db->test(isset($_REQUEST['stud_id']) ? $_REQUEST['stud_id'] : '');
	$STUDENT_NAME 			= $db->test(isset($_REQUEST['studname']) ? $_REQUEST['studname'] : '');
	$INSTITUTE_NAME 		= $db->test(isset($_REQUEST['instname']) ? $_REQUEST['instname'] : '');
	$INSTITUTE_CITY 		= $db->test(isset($_REQUEST['instcity']) ? $_REQUEST['instcity'] : '');
	$FILE_CATEGORY 			= $db->test(isset($_REQUEST['stud_photo_id_type']) ? $_REQUEST['stud_photo_id_type'] : '');
	$FILE_DESC				= $db->test(isset($_REQUEST['stud_photo_id']) ? $_REQUEST['stud_photo_id'] : '');
	$GRADE 					= $db->test(isset($_REQUEST['grade']) ? $_REQUEST['grade'] : '');
	$MARKS_PER 				= $db->test(isset($_REQUEST['marks']) ? $_REQUEST['marks'] : '');
	$EXAM_TITLE 			= $db->test(isset($_REQUEST['exam_title']) ? $_REQUEST['exam_title'] : '');
	$COURSE_DURATION 			= $db->test(isset($_REQUEST['course_duration']) ? $_REQUEST['course_duration'] : '');
	$certicateno 			= $db->test(isset($_REQUEST['certicateno']) ? $_REQUEST['certicateno'] : '');
	$certicatedate 			= $db->test(isset($_REQUEST['certicatedate']) ? $_REQUEST['certicatedate'] : '');
	$certificate_file 			= $db->test(isset($_REQUEST['certificate_file']) ? $_REQUEST['certificate_file'] : '');
	$STUDENT_PHOTO 			= $db->test(isset($_REQUEST['stud_photo']) ? $_REQUEST['stud_photo'] : '');

	$PRACTICAL_MARKS 		= $db->test(isset($_REQUEST['marksobtpract']) ? $_REQUEST['marksobtpract'] : '');
	$OBJECTIVE_MARKS 		= $db->test(isset($_REQUEST['marksobt']) ? $_REQUEST['marksobt'] : '');
	$SUBJECT 				= $db->test(isset($_REQUEST['subject']) ? $_REQUEST['subject'] : '');

	$STUDENT_FNAME 			= $db->test(isset($_REQUEST['fname']) ? $_REQUEST['fname'] : '');
	$STUDENT_MNAME 			= $db->test(isset($_REQUEST['mname']) ? $_REQUEST['mname'] : '');
	$STUDENT_LNAME 			= $db->test(isset($_REQUEST['lname']) ? $_REQUEST['lname'] : '');
	$STUDENT_MOTHER_NAME	= $db->test(isset($_REQUEST['mothername']) ? $_REQUEST['mothername'] : '');
	$STUDENT_DOB 			= $db->test(isset($_REQUEST['dob']) ? $_REQUEST['dob'] : '');

	$stud_dob = $STUDENT_DOB;
	if ($stud_dob != '')
		$stud_dob = date('Y-m-d', strtotime($stud_dob));

	$certicatedate_f = $certicatedate;
	if ($certicatedate_f != '')
		$certicatedate_f = date('Y-m-d', strtotime($certicatedate_f));


	/*$STUD_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;*/

	$STUD_PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;


	$STUD_PHOTO_EDITED = isset($_FILES['studphoto']['name']) ? $_FILES['studphoto']['name'] : '';
	$cert_photo = $STUDENT_PHOTO;
	$rand = $access->getRandomCode(6);
	if ($STUD_PHOTO_EDITED != '') {

		$STUD_PHOTO = isset($_FILES['studphoto']['tmp_name']) ? $_FILES['studphoto']['tmp_name'] : '';
		$type = pathinfo($_FILES['studphoto']['name'], PATHINFO_EXTENSION);
		$cert_photo = $certicateno . '_' . $rand . '.' . $type;
		/*
		//RItesh 12-07-2019 certificate photo path 
		$STUD_PHOTO_CERT = CERTIFICATE_PATH.'/photos/'.$cert_photo;
		@copy($STUD_PHOTO,$STUD_PHOTO_CERT);
		$STUD_PHOTO = $STUD_PHOTO_CERT;
		*/
	}
	/*
	//Commented this code to save the extra images load of unnecessary copying 
	else{
		
		$type = pathinfo($STUD_PHOTO, PATHINFO_EXTENSION);
		if($STUDENT_PHOTO!='')
			$cert_photo = $certicateno.'_'.$rand.'.'.$type;
	
		$STUD_PHOTO_CERT = CERTIFICATE_PATH.'/photos/'.$cert_photo;	
		@copy($STUD_PHOTO,$STUD_PHOTO_CERT);
			$STUD_PHOTO = $STUD_PHOTO_CERT;
			
	}
	*/


	/*   $STUD_PHOTO_EDITED = isset($_FILES['studphoto']['name'])?$_FILES['studphoto']['name']:'';
	$cert_photo='';
	$rand = $access->getRandomCode(6);
	if($STUD_PHOTO_EDITED!='')
	{
		
		$STUD_PHOTO = isset($_FILES['studphoto']['tmp_name'])?$_FILES['studphoto']['tmp_name']:'';
		$type = pathinfo($_FILES['studphoto']['name'], PATHINFO_EXTENSION);
		$cert_photo = $certicateno.'_'.$rand.'.'.$type;
		$STUD_PHOTO_CERT = CERTIFICATE_PATH.'/photos/'.$cert_photo;
		@copy($STUD_PHOTO,$STUD_PHOTO_CERT);
		$STUD_PHOTO = $STUD_PHOTO_CERT;
	}else{
		$type = pathinfo($STUD_PHOTO, PATHINFO_EXTENSION);
		if($STUDENT_PHOTO!='')
			$cert_photo = $certicateno.'_'.$rand.'.'.$type;
	
		$STUD_PHOTO_CERT = CERTIFICATE_PATH.'/photos/'.$cert_photo;	
		@copy($STUD_PHOTO,$STUD_PHOTO_CERT);
			$STUD_PHOTO = $STUD_PHOTO_CERT;
	}*/


	$html = '';
	$mpdf = new mPDF('c', 'A4', '', '', 0, 0, 0, 0, 16, 13);
	$created_by  = $_SESSION['user_fullname'];
	$ip_address  = $_SESSION['ip_address'];

	$filepath		= CERTIFICATE_PATH;
	if (!file_exists($filepath)) {
		@mkdir($filepath, 0777, true);
	}

	$file		= $filepath . '/' . $certificate_file;
	$tableName 	= "certificates_order_details";
	$setValues 	= "CERTIFICATE_NO='$certicateno',CERTIFICATE_FILE='$certificate_file',ISSUE_DATE='$certicatedate_f', INSTITUTE_NAME='$INSTITUTE_NAME',STUDENT_NAME='$STUDENT_NAME',STUD_ID_PROOF_TYPE='$FILE_CATEGORY', STUD_ID_PROOF_NUMBER='$FILE_DESC', COURSE_NAME='$EXAM_TITLE',OBJECTIVE_MARKS='$OBJECTIVE_MARKS',SUBJECT='$SUBJECT',PRACTICAL_MARKS='$PRACTICAL_MARKS',GRADE='$GRADE', MARKS_PER='$MARKS_PER', UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$ip_address'";

	if ($cert_photo != '')
		$setValues .= ",STUDENT_PHOTO='$cert_photo'";

	$whereClause = " WHERE CERTIFICATE_DETAILS_ID='$CERTIFICATE_DETAILS_ID'";
	$updateSql	= $db->updateData($tableName, $setValues, $whereClause);
	$exSql		= $db->execQuery($updateSql);

	if ($exSql) {
		$tableName1 	= "certificate_order_requests";
		$setValues1 	= "OBJECTIVE_MARKS='$OBJECTIVE_MARKS',PRACTICAL_MARKS='$PRACTICAL_MARKS',SUBJECT='$SUBJECT', GRADE='$GRADE',MARKS_PER='$MARKS_PER'";
		$whereClause1 = " WHERE STUDENT_ID='$STUDENT_ID'";
		$updateSql1	= $db->updateData($tableName1, $setValues1, $whereClause1);
		$exSql1		= $db->execQuery($updateSql1);

		if ($exSql1) {
			$tableName2 	= "exam_result";
			$setValues2 	= "MARKS_OBTAINED='$OBJECTIVE_MARKS',PRACTICAL_MARKS='$PRACTICAL_MARKS',SUBJECT='$SUBJECT', GRADE='$GRADE',MARKS_PER='$MARKS_PER'";
			$whereClause2 = " WHERE STUDENT_ID='$STUDENT_ID'";
			$updateSql2	= $db->updateData($tableName2, $setValues2, $whereClause2);
			$exSql2		= $db->execQuery($updateSql2);
		}
	}

	$auth_signature = '';
	$idproof = '';
	$courseduration = '';
	$FILE_DESC = isset($FILE_DESC) ? $FILE_DESC : '';
	$FILE_CATEGORY = isset($FILE_CATEGORY) ? $FILE_CATEGORY : '';
	$COURSE_DURATION = isset($COURSE_DURATION) ? $COURSE_DURATION : '';
	if ($FILE_DESC != '' && $FILE_CATEGORY != '') {
		//	$idproof = '<h4 class="idproof"> '.htmlspecialchars_decode($FILE_CATEGORY).' NO: '.htmlspecialchars_decode($FILE_DESC).' </h4>';
	}
	if ($COURSE_DURATION != '') {
		$courseduration = '<h3 class="courseduration"> (COURSE DURATION: ' . htmlspecialchars_decode($COURSE_DURATION) . ')</h3>';
	}
	if (file_exists('resources/dist/img/signaturepng.png'))
		$auth_signature = '<img src="resources/dist/img/signaturepng.png" style="width:150px; height:80px;"/>';
	$html = '
	<style>
	body {padding:0;font-family: sans-serif; font-size: 10pt;position:absolute;z-index:0;top:0px;border:1px solid #f00;}
	.studname{position:absolute;top:480px;text-align:center;width:100%;}
	.idproof{position:absolute;top:480px;text-align:center;width:100%;}
	
	.atcname{position:absolute;top:550px;text-align:center;width:100%; font-size:12px;}
	
	.grade{position:absolute;top:615px;left:52%; width:100px;}
	.marks{	position:absolute;top:615px;left:73.5%; width:100px;}
	.coursename{position:absolute;top:705px;text-align:center;width:100%; font-size:16px;}
		.courseduration{position:absolute;top:730px;text-align:center;width:100%; font-size:10px;font-weight:normal;}
	.certicateno{position:absolute;	bottom:58px;left:44%;}
	.date{position:absolute;bottom:37px;left:44%;}
	.signature{position:absolute;bottom:63px;right:7%;width:150px;}
	.studphoto{position:absolute;top:265px;right:6%;width:140px; height:180px;background-image:url("' . $STUD_PHOTO . '"); background-size:140px 180px; background-repeat:no-repeat;}
	</style>';
	$html .= '
	<!-- <img src="resources/dist/img/democertificate.jpg" style="" /> -->
	<div class="studphoto"></div>
				<h2 class="studname">' . $STUDENT_NAME . '</h2>
				<h3 class="atcname">ATC : ' . htmlspecialchars_decode($INSTITUTE_NAME) . ' | ' . htmlspecialchars_decode($INSTITUTE_CITY) . '</h3>
				' . $idproof . '
				<h2 class="grade"> ' . $GRADE . ' </h2>
				<h2 class="marks">' . $MARKS_PER . ' %</h2>
				<h3 class="coursename">' . htmlspecialchars_decode($EXAM_TITLE) . '</h3>
				' . $courseduration . '
				<h3 class="certicateno">' . $certicateno . '</h3>
				<h3 class="date">' . $certicatedate . '</h3>
				<div class="signature">' . $auth_signature . '</div>
				';

	//==============================================================
	$mpdf->WriteHTML($html);
	//$mpdf->Output($file,'F');

	header('location:page.php?page=list-print-approval-certificate');
	exit;
}

$res = $access->list_order_printed_certificates($cert_detail_id, '', ' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1');
if ($res != '') {

	while ($data = $res->fetch_assoc()) {

		//print_r($data);
		$CERTIFICATE_DETAILS_ID 	= $data['CERTIFICATE_DETAILS_ID'];
		$CERTIFICATE_REQUEST_ID 	= $data['CERTIFICATE_REQUEST_ID'];
		$CERTIFICATE_SERIAL_NO 		= $data['CERTIFICATE_SERIAL_NO'];
		$CERTIFICATE_PREFIX 		= $data['CERTIFICATE_PREFIX'];
		$CERTIFICATE_NO 			= $data['CERTIFICATE_NO'];
		$CERTIFICATE_FILE 			= $data['CERTIFICATE_FILE'];
		$ISSUE_DATE 				= $data['ISSUE_DATE'];
		$INSTITUTE_ID 				= $data['INSTITUTE_ID'];
		$INSTITUTE_CITY 			= $data['INSTITUTE_CITY'];
		$STUDENT_ID 				= $data['STUDENT_ID'];
		$STUDENT_PHOTO 				= $data['STUDENT_PHOTO'];
		$STUD_PHOTO 				= $data['STUD_PHOTO'];
		$AICPE_COURSE_ID 			= $data['AICPE_COURSE_ID'];
		$INSTITUTE_NAME 			= $data['INSTITUTE_NAME'];
		$STUDENT_NAME 				= $data['STUDENT_NAME'];
		$STUDENT_FATHER_NAME 		= $data['STUDENT_FATHER_NAME'];
		$STUDENT_MOTHER_NAME 		= $data['STUDENT_MOTHER_NAME'];
		$STUD_ID_PROOF_TYPE 		= $data['STUD_ID_PROOF_TYPE'];
		$STUD_ID_PROOF_NUMBER 		= $data['STUD_ID_PROOF_NUMBER'];
		$COURSE_AWARD 				= isset($data['COURSE_AWARD']) ? $data['COURSE_AWARD'] : '';
		$AICPE_COURSE_AWARD 		= $data['AICPE_COURSE_AWARD'];
		$COURSE_NAME 				= $data['COURSE_NAME'];
		$GRADE 						= $data['GRADE'];
		$MARKS_OBTAINED 		    = $data['MARKS_OBTAINED'];
		$PRACTICAL_MARKS 		    = $data['PRACTICAL_MARKS'];
		$OBJECTIVE_MARKS 		    = $data['OBJECTIVE_MARKS'];
		$SUBJECT		             = $data['SUBJECT'];

		$STUDENT_FNAME		        = $data['STUDENT_FNAME'];
		$STUDENT_MNAME		        = $data['STUDENT_MNAME'];
		$STUDENT_LNAME		        = $data['STUDENT_LNAME'];
		$STUDENT_DOB		        = $data['STUDENT_DOB'];
		if ($STUDENT_DOB != '')
			$STUDENT_DOB = date('d-m-Y', strtotime($STUDENT_DOB));



		$MARKS_PER 					= $data['MARKS_PER'];
		$ACTIVE 					= $data['ACTIVE'];
		$CREATED_BY 				= $data['CREATED_BY'];
		$CREATED_ON					= $data['CREATED_ON'];
		$UPDATED_BY 				= $data['UPDATED_BY'];
		$UPDATED_ON 				= $data['UPDATED_ON'];
		$CREATED_ON_IP 				= $data['CREATED_ON_IP'];
		$UPDATED_ON_IP 				= $data['UPDATED_ON_IP'];
		$COURSE_DURATION 		= $data['COURSE_DURATION'];
		$ISSUE_DATE_FORMAT 				= $data['ISSUE_DATE_FORMAT'];


		/*	$cert_path = CERTIFICATE_PATH."/".$CERTIFICATE_FILE;
		$PHOTO = '../uploads/default_user.png';					
		if($STUDENT_PHOTO!='')
			$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;
		if($STUD_PHOTO!='')
				$PHOTO = CERTIFICATE_PATH."/photos/$STUD_PHOTO";*/

		/*$cert_path = CERTIFICATE_PATH."/".$CERTIFICATE_FILE;*/
		$cert_path = CERTIFICATE_PATH . "/" . $CERTIFICATE_FILE;

		/*	$PHOTO = '../uploads/default_user.png';					
		if($STUDENT_PHOTO!='')
			$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/thumb/'.$STUDENT_PHOTO;
		if($STUD_PHOTO!='')
				$PHOTO = CERTIFICATE_PATH."/photos/$STUD_PHOTO";*/

		$PHOTO = '../uploads/default_user.png';
		if ($STUDENT_PHOTO != '')
			$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;
		/*	if($STUD_PHOTO!='')
				$PHOTO = CERTIFICATE_PATH."/photos/$STUD_PHOTO";*/



		$GRADE = explode(':', $GRADE);
		$GRADE = isset($GRADE[0]) ? $GRADE[0] : '';
		$EXAM_TITLE = explode("(", $AICPE_COURSE_AWARD);
		$EXAM_TITLE = isset($EXAM_TITLE[0]) ? $EXAM_TITLE[0] : '';
	}
}
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1> Update Certificate</h1>
		<ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="page.php?page=list-institutes">Certificate</a></li>
			<li class="active">Update Certificate</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<form class="form-horizontal form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');">

			<!-- left column -->
			<?php
			if (isset($success)) {
			?>
				<div class="row">
					<div class="col-sm-12">
						<div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
							<h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
							<?= isset($message) ? $message : 'Please correct the errors.'; ?>
						</div>
					</div>
				</div>
			<?php
			}
			?>

			<div class="row">


				<div class="col-md-7">
					<!-- general form elements -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Update Certificate Details</h3>
						</div>
						<div class="box-body">
							<input type="hidden" value="<?= $CERTIFICATE_DETAILS_ID ?>" name="certificate_details_id" />
							<input type="hidden" value="<?= $CERTIFICATE_SERIAL_NO ?>" name="certificate_serial_no" />
							<input type="hidden" value="<?= $CERTIFICATE_FILE ?>" name="certificate_file" />
							<input type="hidden" value="<?= $STUDENT_PHOTO ?>" name="stud_photo" />
							<input type="hidden" value="<?= $STUDENT_ID ?>" name="stud_id" />


							<div class="form-group <?= (isset($errors['studphoto'])) ? 'has-error' : '' ?>">
								<label for="studphoto" class="col-sm-3 control-label">Student Photo</label>
								<div class="col-sm-4">
									<input class="form-control" id="studphoto" name="studphoto" type="file" onchange="readURL(this);" />
									<span class="help-block"><?= isset($errors['studphoto']) ? $errors['studphoto'] : '' ?></span>
								</div>
								<div class="col-sm-4">
									<img src="<?= $PHOTO ?>" class="img img-responsive" id="img_preview" style="height:100px;width:100px;" />
								</div>
							</div>

							<div class="form-group <?= (isset($errors['studname'])) ? 'has-error' : '' ?>">
								<label for="studname" class="col-sm-3 control-label">Student Name</label>
								<div class="col-sm-9">
									<input class="form-control" id="studname" name="studname" placeholder="Student name" value="<?= isset($_POST['studname']) ? $_POST['studname'] : $STUDENT_NAME ?>" type="text" />
									<span class="help-block"><?= isset($errors['studname']) ? $errors['studname'] : '' ?></span>
								</div>
							</div>


							<div class="form-group <?= (isset($errors['fname'])) ? 'has-error' : '' ?>">
								<label for="fname" class="col-sm-3 control-label">Student Name On Marksheet</label>
								<div class="col-sm-9">
									<input class="form-control" id="fname" name="fname" placeholder="Student Name On Marksheet" value="<?= isset($_POST['fname']) ? $_POST['fname'] : $STUDENT_FNAME ?>" type="text" />
									<span class="help-block"><?= isset($errors['fname']) ? $errors['fname'] : '' ?></span>
								</div>
							</div>

							<div class="form-group <?= (isset($errors['mname'])) ? 'has-error' : '' ?>">
								<label for="mname" class="col-sm-3 control-label">Father Name / Husband Name </label>
								<div class="col-sm-9">
									<input class="form-control" id="mname" name="mname" placeholder="Father Name / Husband Name " value="<?= isset($_POST['mname']) ? $_POST['mname'] : $STUDENT_MNAME ?>" type="text" />
									<span class="help-block"><?= isset($errors['mname']) ? $errors['mname'] : '' ?></span>
								</div>
							</div>

							<div class="form-group <?= (isset($errors['lname'])) ? 'has-error' : '' ?>">
								<label for="lname" class="col-sm-3 control-label">Surname Name </label>
								<div class="col-sm-9">
									<input class="form-control" id="lname" name="lname" placeholder="Surname Name" value="<?= isset($_POST['lname']) ? $_POST['lname'] : $STUDENT_LNAME ?>" type="text" />
									<span class="help-block"><?= isset($errors['lname']) ? $errors['lname'] : '' ?></span>
								</div>
							</div>

							<div class="form-group <?= (isset($errors['mothername'])) ? 'has-error' : '' ?>">
								<label for="mothername" class="col-sm-3 control-label"> Mother Name </label>
								<div class="col-sm-9">
									<input class="form-control" id="mothername" name="mothername" placeholder="Mother Name" value="<?= isset($_POST['mothername']) ? $_POST['mothername'] : $STUDENT_MOTHER_NAME ?>" type="text" />
									<span class="help-block"><?= isset($errors['mothername']) ? $errors['mothername'] : '' ?></span>
								</div>
							</div>

							<div class="form-group <?= (isset($errors['dob'])) ? 'has-error' : '' ?>">
								<label for="dob" class="col-sm-3 control-label"> Date Of Birth </label>
								<div class="col-sm-9">
									<input class="form-control" id="" name="dob" placeholder="Date Of Birth" value="<?= isset($_POST['dob']) ? $_POST['dob'] : $STUDENT_DOB ?>" type="text" />
									<span class="help-block"><?= isset($errors['dob']) ? $errors['dob'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['instname'])) ? 'has-error' : '' ?>">
								<label for="instname" class="col-sm-3 control-label">Institute Name</label>
								<div class="col-sm-9">
									<input class="form-control" id="instname" name="instname" placeholder="Institute name" value="<?= isset($_POST['instname']) ? $_POST['instname'] : $INSTITUTE_NAME ?>" type="text" />
									<span class="help-block"><?= isset($errors['instname']) ? $errors['instname'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['instcity'])) ? 'has-error' : '' ?>">
								<label for="instname" class="col-sm-3 control-label">Institute City</label>
								<div class="col-sm-9">
									<input class="form-control" id="instcity" name="instcity" placeholder="Institute City" value="<?= isset($_POST['instcity']) ? $_POST['instcity'] : $INSTITUTE_CITY ?>" type="text" />
									<span class="help-block"><?= isset($errors['instcity']) ? $errors['instcity'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['stud_photo_id_type'])) ? 'has-error' : '' ?>">
								<label for="stud_photo_id_type" class="col-sm-3 control-label">Student Photo ID Type</label>
								<div class="col-sm-9">
									<input class="form-control" id="stud_photo_id_type" name="stud_photo_id_type" placeholder="Student Photo ID Type" value="<?= isset($_POST['stud_photo_id_type']) ? $_POST['stud_photo_id_type'] : $STUD_ID_PROOF_TYPE ?>" type="text" />
									<span class="help-block"><?= isset($errors['stud_photo_id_type']) ? $errors['stud_photo_id_type'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['stud_photo_id'])) ? 'has-error' : '' ?>">
								<label for="stud_photo_id" class="col-sm-3 control-label">Student Photo ID</label>
								<div class="col-sm-9">
									<input class="form-control" id="stud_photo_id" name="stud_photo_id" placeholder="Student Photo ID Type" value="<?= isset($_POST['stud_photo_id']) ? $_POST['stud_photo_id'] : $STUD_ID_PROOF_NUMBER ?>" type="text" />
									<span class="help-block"><?= isset($errors['stud_photo_id']) ? $errors['stud_photo_id'] : '' ?></span>
								</div>
							</div>

							<div class="form-group <?= (isset($errors['marksobt'])) ? 'has-error' : '' ?>">
								<label for="inputEmail3" class="col-sm-3 control-label">Objective Marks</label>
								<div class="col-sm-9">
									<input class="form-control" id="marksobt" placeholder="Total  objective Marks obtained" type="number" name="marksobt" onkeyup="this.value = minmax(this.value, 0, 50)" value="<?= isset($_POST['marksobt']) ? $_POST['marksobt'] : $OBJECTIVE_MARKS ?>">

									<span class="help-block"><?= (isset($errors['marksobt'])) ? $errors['marksobt'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['marksobt'])) ? 'has-error' : '' ?>">
								<label for="inputEmail3" class="col-sm-3 control-label">Practical Marks</label>
								<div class="col-sm-9">
									<input class="form-control" id="marksobtpract" placeholder="Total practicle  Marks obtained" type="number" name="marksobtpract" maxlength="50" onkeyup="this.value = minmax(this.value, 0, 50)" value="<?= isset($_POST['marksobtpract']) ? $_POST['marksobtpract'] : $PRACTICAL_MARKS ?>">

									<span class="help-block"><?= (isset($errors['marksobtpract'])) ? $errors['marksobtpract'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['marksobt'])) ? 'has-error' : '' ?>">
								<label for="inputEmail3" class="col-sm-3 control-label">Subjects</label>
								<div class="col-sm-9">
									<textarea class="form-control" id="subject" placeholder="SUBJECT" type="text" name="subject" value=""><?= isset($_POST['subject']) ? $_POST['subject'] : $SUBJECT ?></textarea>

									<span class="help-block"><?= (isset($errors['subject'])) ? $errors['subject'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['grade'])) ? 'has-error' : '' ?>">
								<label for="grade" class="col-sm-3 control-label">Grade</label>
								<div class="col-sm-9">
									<input class="form-control" id="grade" name="grade" placeholder="Grade" value="<?= isset($_POST['grade']) ? $_POST['grade'] : $GRADE ?>" type="text" />
									<span class="help-block"><?= isset($errors['grade']) ? $errors['grade'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['marks'])) ? 'has-error' : '' ?>">
								<label for="marks" class="col-sm-3 control-label">Marks</label>
								<div class="col-sm-9">
									<input class="form-control" id="marks_per" name="marks" placeholder="Marks" value="<?= isset($_POST['marks']) ? $_POST['marks'] : $MARKS_PER ?>" type="text" />
									<span class="help-block"><?= isset($errors['marks']) ? $errors['marks'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['exam_title'])) ? 'has-error' : '' ?>">
								<label for="exam_title" class="col-sm-3 control-label">Exam Title</label>
								<div class="col-sm-9">
									<input class="form-control" id="exam_title" name="exam_title" placeholder="Exam Title" value="<?= isset($_POST['exam_title']) ? $_POST['exam_title'] : $COURSE_NAME ?>" type="text" />
									<span class="help-block"><?= isset($errors['exam_title']) ? $errors['exam_title'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['course_duration'])) ? 'has-error' : '' ?>">
								<label for="course_duration" class="col-sm-3 control-label">Course Duration</label>
								<div class="col-sm-9">
									<input class="form-control" id="course_duration" name="course_duration" placeholder="Course Duration" value="<?= isset($_POST['course_duration']) ? $_POST['course_duration'] : $COURSE_DURATION ?>" type="text" />
									<span class="help-block"><?= isset($errors['course_duration']) ? $errors['course_duration'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['certicateno'])) ? 'has-error' : '' ?>">
								<label for="certicateno" class="col-sm-3 control-label">Certificate No.</label>
								<div class="col-sm-9">
									<input class="form-control" id="certicateno" name="certicateno" placeholder="Exam Title" value="<?= isset($_POST['certicateno']) ? $_POST['certicateno'] : $CERTIFICATE_NO ?>" type="text" />
									<span class="help-block"><?= isset($errors['certicateno']) ? $errors['certicateno'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['certicatedate'])) ? 'has-error' : '' ?>">
								<label for="certicatedate" class="col-sm-3 control-label">Certificate Date</label>
								<div class="col-sm-9">
									<input class="form-control" id="certicatedate" name="certicatedate" placeholder="Certificate Date" value="<?= isset($_POST['certicatedate']) ? $_POST['certicatedate'] : $ISSUE_DATE_FORMAT ?>" type="text" />
									<span class="help-block"><?= isset($errors['certicatedate']) ? $errors['certicatedate'] : '' ?></span>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer text-center">
							<a href="page.php?page=list-print-approval-certificate" class="btn btn-default">Cancel</a>
							<input type="submit" name="print_certificate" class="btn btn-info" value="Update Certificate" />
						</div>
					</div>
				</div>




			</div>
		</form>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<script type="text/javascript">
	function minmax(value, min, max) {
		if (parseInt(value) < min || isNaN(parseInt(value)))
			return 0;
		else if (parseInt(value) > max)
			return 50;
		else {
			calPracticalResult();
			return value;
		}
	}
</script>