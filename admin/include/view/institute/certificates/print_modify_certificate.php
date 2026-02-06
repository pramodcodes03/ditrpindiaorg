<?php
ini_set("memory_limit", "128M");

// Define the password for date changes (you should store this securely)
define('DATE_CHANGE_PASSWORD', 'rGrCGzNYaOFsrFt'); // Change this to your desired password

// Initialize variables first to avoid undefined variable errors
$cert_detail_id = isset($_GET['cert_detail_id']) ? $db->test($_GET['cert_detail_id']) : '';
$action = isset($_REQUEST['print_certificate']) ? $_REQUEST['print_certificate'] : '';

// Initialize all variables that will be used
$CERTIFICATE_DETAILS_ID = '';
$CERTIFICATE_REQUEST_ID = '';
$CERTIFICATE_SERIAL_NO = '';
$CERTIFICATE_PREFIX = '';
$CERTIFICATE_NO = '';
$CERTIFICATE_FILE = '';
$ISSUE_DATE = '';
$INSTITUTE_ID = '';
$INSTITUTE_CITY = '';
$STUDENT_ID = '';
$STUDENT_PHOTO = '';
$STUD_PHOTO = '';
$AICPE_COURSE_ID = '';
$INSTITUTE_NAME = '';
$STUDENT_NAME = '';
$STUDENT_FATHER_NAME = '';
$STUDENT_MOTHER_NAME = '';
$STUDENT_MOTHER_NAME = '';
$STUD_ID_PROOF_TYPE = '';
$STUD_ID_PROOF_NUMBER = '';
$COURSE_AWARD = '';
$AICPE_COURSE_AWARD = '';
$COURSE_NAME = '';
$GRADE = '';
$PRACTICAL_MARKS = '';
$OBJECTIVE_MARKS = '';
$SUBJECT = '';
$STUDENT_SIGN = '';
$STUDENT_FNAME = '';
$STUDENT_MNAME = '';
$STUDENT_LNAME = '';
$STUDENT_DOB = '';
$MARKS_PER = '';
$ACTIVE = '';
$CREATED_BY = '';
$CREATED_ON = '';
$UPDATED_BY = '';
$UPDATED_ON = '';
$CREATED_ON_IP = '';
$UPDATED_ON_IP = '';
$COURSE_DURATION = '';
$ISSUE_DATE_FORMAT = '';
$PHOTO = '../uploads/resources/dummy/dummy-photo.png';
$SIGN = '../uploads/resources/dummy/dummy-signature.png';
$EXAM_TITLE = '';

// Initialize variables for error handling
$errors = array();
$success = false;
$message = '';

// Initialize required variables that may be used in the form processing
$created_by = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$ip_address = $_SERVER['REMOTE_ADDR'] ?? '';

if ($action != '') {
	date_default_timezone_set("Asia/Kolkata");
	include("include/plugins/pdf/mpdf.php");

	$CERTIFICATE_DETAILS_ID = $db->test(isset($_REQUEST['certificate_details_id']) ? $_REQUEST['certificate_details_id'] : '');
	$STUDENT_ID = $db->test(isset($_REQUEST['stud_id']) ? $_REQUEST['stud_id'] : '');
	$STUDENT_NAME = $db->test(isset($_REQUEST['studname']) ? $_REQUEST['studname'] : '');
	$FILE_CATEGORY = $db->test(isset($_REQUEST['stud_photo_id_type']) ? $_REQUEST['stud_photo_id_type'] : '');
	$FILE_DESC = $db->test(isset($_REQUEST['stud_photo_id']) ? $_REQUEST['stud_photo_id'] : '');
	$GRADE = $db->test(isset($_REQUEST['grade']) ? $_REQUEST['grade'] : '');
	$MARKS_PER = $db->test(isset($_REQUEST['marks']) ? $_REQUEST['marks'] : '');
	$EXAM_TITLE = $db->test(isset($_REQUEST['exam_title']) ? $_REQUEST['exam_title'] : '');
	$certicatedate = $db->test(isset($_REQUEST['certicatedate']) ? $_REQUEST['certicatedate'] : '');
	$certificate_file = $db->test(isset($_REQUEST['certificate_file']) ? $_REQUEST['certificate_file'] : '');
	$STUDENT_PHOTO = $db->test(isset($_REQUEST['stud_photo']) ? $_REQUEST['stud_photo'] : '');

	$PRACTICAL_MARKS = $db->test(isset($_REQUEST['marksobtpract']) ? $_REQUEST['marksobtpract'] : '');
	$OBJECTIVE_MARKS = $db->test(isset($_REQUEST['marksobt']) ? $_REQUEST['marksobt'] : '');
	$SUBJECT = $db->test(isset($_REQUEST['subject']) ? $_REQUEST['subject'] : '');

	$STUDENT_FNAME = $db->test(isset($_REQUEST['fname']) ? $_REQUEST['fname'] : '');
	$STUDENT_MNAME = $db->test(isset($_REQUEST['mname']) ? $_REQUEST['mname'] : '');
	$STUDENT_LNAME = $db->test(isset($_REQUEST['lname']) ? $_REQUEST['lname'] : '');
	$STUDENT_MOTHER_NAME = $db->test(isset($_REQUEST['mothername']) ? $_REQUEST['mothername'] : '');
	$STUDENT_DOB = $db->test(isset($_REQUEST['dob']) ? $_REQUEST['dob'] : '');

	// Get the original certificate date for comparison
	$original_date = '';
	if (!empty($CERTIFICATE_DETAILS_ID)) {
		$original_cert_query = "SELECT ISSUE_DATE FROM certificates_details WHERE CERTIFICATE_DETAILS_ID='$CERTIFICATE_DETAILS_ID'";
		$original_result = $db->execQuery($original_cert_query);
		if ($original_result && $original_result->num_rows > 0) {
			$original_data = $original_result->fetch_assoc();
			$original_date = $original_data['ISSUE_DATE'];
		}
	}

	$stud_dob = $STUDENT_DOB;
	if ($stud_dob != '') {
		$stud_dob = date('Y-m-d', strtotime($stud_dob));
	}

	$certicatedate_f = $certicatedate;
	if ($certicatedate_f != '') {
		$certicatedate_f = date('Y-m-d', strtotime($certicatedate_f));
	}

	// Check if certificate date has changed
	$date_changed = false;
	if (!empty($certicatedate_f) && !empty($original_date)) {
		// Normalize both dates to Y-m-d format for comparison
		$original_date_normalized = date('Y-m-d', strtotime($original_date));
		$new_date_normalized = date('Y-m-d', strtotime($certicatedate_f));

		if ($original_date_normalized != $new_date_normalized) {
			$date_changed = true;
		}
	}

	// If date changed, validate password
	if ($date_changed) {
		$entered_password = $db->test(isset($_REQUEST['date_change_password']) ? $_REQUEST['date_change_password'] : '');

		if (empty($entered_password)) {
			$errors['date_change_password'] = 'Password is required when changing certificate date.';
		} elseif ($entered_password !== DATE_CHANGE_PASSWORD) {
			$errors['date_change_password'] = 'Invalid password for changing certificate date.';
		}
	}

	// Proceed only if no errors
	if (empty($errors)) {
		// Your existing file upload code here
		$STUD_PHOTO_EDITED = isset($_FILES['studphoto']['name']) ? $_FILES['studphoto']['name'] : '';
		$STUD_SIGN_EDITED = isset($_FILES['stud_sign']['name']) ? $_FILES['stud_sign']['name'] : '';

		// Check if required constants are defined
		if (!defined('STUDENT_DOCUMENTS_PATH')) {
			$errors['system'] = 'System configuration error: STUDENT_DOCUMENTS_PATH not defined.';
		}
		if (!defined('STUD_PHOTO')) {
			$errors['system'] = 'System configuration error: STUD_PHOTO constant not defined.';
		}
		if (!defined('STUD_PHOTO_SIGN')) {
			$errors['system'] = 'System configuration error: STUD_PHOTO_SIGN constant not defined.';
		}

		if (empty($errors)) {
			$courseImgPathDir = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/';
			$tableName3 = "student_files";

			// Upload files (your existing code)
			if ($STUD_PHOTO_EDITED != '') {
				$ext = pathinfo($_FILES["studphoto"]["name"], PATHINFO_EXTENSION);
				$file_name = STUD_PHOTO . '_' . mt_rand(0, 123456789) . '.' . $ext;

				$sqlUpd = "UPDATE student_files SET DELETE_FLAG=0, ACTIVE=0 WHERE STUDENT_ID='$STUDENT_ID' AND FILE_LABEL='" . STUD_PHOTO . "'";
				$exec311 = $db->execQuery($sqlUpd);

				$tabFields3 = "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,ACTIVE,CREATED_ON)";
				$insertVals3 = "(NULL, '$STUDENT_ID', '$file_name','" . STUD_PHOTO . "','1',NOW())";
				$insertSql3 = $db->insertData($tableName3, $tabFields3, $insertVals3);
				$exec3 = $db->execQuery($insertSql3);

				$courseImgPathFile = $courseImgPathDir . '' . $file_name;
				@mkdir($courseImgPathDir, 0777, true);
				
				// Check if access object exists and has the method
				if (isset($access) && method_exists($access, 'create_thumb_img')) {
					$access->create_thumb_img($_FILES["studphoto"]["tmp_name"], $courseImgPathFile, $ext, 800, 750);
				}
			}

			if ($STUD_SIGN_EDITED != '') {
				$ext = pathinfo($_FILES["stud_sign"]["name"], PATHINFO_EXTENSION);
				$file_name = STUD_PHOTO_SIGN . '_' . mt_rand(0, 123456789) . '.' . $ext;

				$sqlUpd1 = "UPDATE student_files SET DELETE_FLAG=0, ACTIVE=0 WHERE STUDENT_ID='$STUDENT_ID' AND FILE_LABEL='" . STUD_PHOTO_SIGN . "'";
				$exec811 = $db->execQuery($sqlUpd1);

				$tabFields8 = "(FILE_ID,STUDENT_ID,FILE_NAME,FILE_LABEL,ACTIVE,CREATED_ON)";
				$insertVals8 = "(NULL, '$STUDENT_ID', '$file_name','" . STUD_PHOTO_SIGN . "','1',NOW())";
				$insertSql8 = $db->insertData($tableName3, $tabFields8, $insertVals8);
				$exec8 = $db->execQuery($insertSql8);

				$courseImgPathFile = $courseImgPathDir . '' . $file_name;
				@mkdir($courseImgPathDir, 0777, true);
				
				// Check if access object exists and has the method
				if (isset($access) && method_exists($access, 'create_thumb_img')) {
					$access->create_thumb_img($_FILES["stud_sign"]["tmp_name"], $courseImgPathFile, $ext, 800, 750);
				}
			}

			$cert_photo = $STUDENT_PHOTO;
			if (isset($access) && method_exists($access, 'getRandomCode')) {
				$rand = $access->getRandomCode(6);
			} else {
				$rand = mt_rand(100000, 999999);
			}
			
			if ($STUD_PHOTO_EDITED != '') {
				$STUD_PHOTO = isset($_FILES['studphoto']['tmp_name']) ? $_FILES['studphoto']['tmp_name'] : '';
				$type = pathinfo($_FILES['studphoto']['name'], PATHINFO_EXTENSION);
				$cert_photo = $STUDENT_NAME . '_' . $rand . '.' . $type;
			}

			// Update database
			$tableName = "certificates_details";
			$setValues = "ISSUE_DATE='$certicatedate_f',STUDENT_NAME='$STUDENT_NAME',STUDENT_FNAME='$STUDENT_FNAME',STUDENT_MNAME='$STUDENT_MNAME',STUDENT_LNAME='$STUDENT_LNAME',STUDENT_MOTHER_NAME='$STUDENT_MOTHER_NAME',STUDENT_DOB='$stud_dob',STUD_ID_PROOF_TYPE='$FILE_CATEGORY', STUD_ID_PROOF_NUMBER='$FILE_DESC', COURSE_NAME='$EXAM_TITLE',OBJECTIVE_MARKS='$OBJECTIVE_MARKS',SUBJECT='$SUBJECT',PRACTICAL_MARKS='$PRACTICAL_MARKS',GRADE='$GRADE', MARKS_PER='$MARKS_PER', UPDATED_BY='$created_by', UPDATED_ON=NOW(), UPDATED_ON_IP='$ip_address'";

			if ($cert_photo != '') {
				$setValues .= ",STUDENT_PHOTO='$cert_photo'";
			}

			$whereClause = " WHERE CERTIFICATE_DETAILS_ID='$CERTIFICATE_DETAILS_ID'";
			$updateSql = $db->updateData($tableName, $setValues, $whereClause);
			$exSql = $db->execQuery($updateSql);

			if ($exSql) {
				// Log the date change if it occurred
				if ($date_changed) {
					$log_message = "Certificate date changed from $original_date to $certicatedate_f for Certificate ID: $CERTIFICATE_DETAILS_ID by User: $created_by";
					error_log($log_message); // Log to PHP error log

					// You can also insert into a custom audit log table:
					/*
					$audit_sql = "INSERT INTO certificate_audit_log (CERTIFICATE_ID, ACTION, OLD_VALUE, NEW_VALUE, CHANGED_BY, CHANGED_ON, IP_ADDRESS) 
								 VALUES ('$CERTIFICATE_DETAILS_ID', 'DATE_CHANGE', '$original_date', '$certicatedate_f', '$created_by', NOW(), '$ip_address')";
					$db->execQuery($audit_sql);
					*/
				}

				$tableName1 = "certificate_requests";
				$setValues1 = "OBJECTIVE_MARKS='$OBJECTIVE_MARKS',PRACTICAL_MARKS='$PRACTICAL_MARKS',SUBJECT='$SUBJECT', GRADE='$GRADE',MARKS_PER='$MARKS_PER'";
				$whereClause1 = " WHERE STUDENT_ID='$STUDENT_ID'";
				$updateSql1 = $db->updateData($tableName1, $setValues1, $whereClause1);
				$exSql1 = $db->execQuery($updateSql1);

				if ($exSql1) {
					$tableName2 = "exam_result";
					$setValues2 = "MARKS_OBTAINED='$OBJECTIVE_MARKS',PRACTICAL_MARKS='$PRACTICAL_MARKS',SUBJECT='$SUBJECT', GRADE='$GRADE',MARKS_PER='$MARKS_PER'";
					$whereClause2 = " WHERE STUDENT_ID='$STUDENT_ID'";
					$updateSql2 = $db->updateData($tableName2, $setValues2, $whereClause2);
					$exSql2 = $db->execQuery($updateSql2);
				}
			}

			if ($exSql) {
				$success = true;
				$message = 'Certificate updated successfully!';
				header('location:page.php?page=listRequestedCertificates');
				exit();
			} else {
				$success = false;
				$message = 'Error updating certificate.';
			}
		}
	} else {
		$success = false;
		$message = 'Please correct the errors below.';
	}
}

// Fetch certificate data only if cert_detail_id is provided
if (!empty($cert_detail_id)) {
	// Check if access object and method exist
	if (isset($access) && method_exists($access, 'list_printed_certificates')) {
		$res = $access->list_printed_certificates($cert_detail_id, '', ' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1');
		if ($res != '' && $res->num_rows > 0) {
			while ($data = $res->fetch_assoc()) {
				// Your existing data fetching code...
				$CERTIFICATE_DETAILS_ID = $data['CERTIFICATE_DETAILS_ID'] ?? '';
				$CERTIFICATE_REQUEST_ID = $data['CERTIFICATE_REQUEST_ID'] ?? '';
				$CERTIFICATE_SERIAL_NO = $data['CERTIFICATE_SERIAL_NO'] ?? '';
				$CERTIFICATE_PREFIX = $data['CERTIFICATE_PREFIX'] ?? '';
				$CERTIFICATE_NO = $data['CERTIFICATE_NO'] ?? '';
				$CERTIFICATE_FILE = $data['CERTIFICATE_FILE'] ?? '';
				$ISSUE_DATE = $data['ISSUE_DATE'] ?? '';
				$INSTITUTE_ID = $data['INSTITUTE_ID'] ?? '';
				$INSTITUTE_CITY = $data['INSTITUTE_CITY'] ?? '';
				$STUDENT_ID = $data['STUDENT_ID'] ?? '';
				$STUDENT_PHOTO = $data['STUDENT_PHOTO'] ?? '';
				$STUD_PHOTO = $data['STUD_PHOTO'] ?? '';
				$AICPE_COURSE_ID = $data['AICPE_COURSE_ID'] ?? '';
				$INSTITUTE_NAME = $data['INSTITUTE_NAME'] ?? '';
				$STUDENT_NAME = $data['STUDENT_NAME'] ?? '';
				$STUDENT_FATHER_NAME = $data['STUDENT_FATHER_NAME'] ?? '';
				$STUDENT_MOTHER_NAME = $data['STUDENT_MOTHER_NAME'] ?? '';
				$STUD_ID_PROOF_TYPE = $data['STUD_ID_PROOF_TYPE'] ?? '';
				$STUD_ID_PROOF_NUMBER = $data['STUD_ID_PROOF_NUMBER'] ?? '';
				$COURSE_AWARD = $data['COURSE_AWARD'] ?? '';
				$AICPE_COURSE_AWARD = $data['AICPE_COURSE_AWARD'] ?? '';
				$COURSE_NAME = $data['COURSE_NAME'] ?? '';
				$GRADE = $data['GRADE'] ?? '';
				$PRACTICAL_MARKS = $data['PRACTICAL_MARKS'] ?? '';
				$OBJECTIVE_MARKS = $data['OBJECTIVE_MARKS'] ?? '';
				$SUBJECT = $data['SUBJECT'] ?? '';
				$STUDENT_SIGN = $data['STUDENT_SIGN'] ?? '';
				$STUDENT_FNAME = $data['STUDENT_FNAME'] ?? '';
				$STUDENT_MNAME = $data['STUDENT_MNAME'] ?? '';
				$STUDENT_LNAME = $data['STUDENT_LNAME'] ?? '';
				$STUDENT_DOB = $data['STUDENT_DOB'] ?? '';

				if ($STUDENT_DOB != '') {
					$STUDENT_DOB = date('d-m-Y', strtotime($STUDENT_DOB));
				}

				$MARKS_PER = $data['MARKS_PER'] ?? '';
				$ACTIVE = $data['ACTIVE'] ?? '';
				$CREATED_BY = $data['CREATED_BY'] ?? '';
				$CREATED_ON = $data['CREATED_ON'] ?? '';
				$UPDATED_BY = $data['UPDATED_BY'] ?? '';
				$UPDATED_ON = $data['UPDATED_ON'] ?? '';
				$CREATED_ON_IP = $data['CREATED_ON_IP'] ?? '';
				$UPDATED_ON_IP = $data['UPDATED_ON_IP'] ?? '';
				$COURSE_DURATION = $data['COURSE_DURATION'] ?? '';
				$ISSUE_DATE_FORMAT = $data['ISSUE_DATE_FORMAT'] ?? '';
				
				if ($ISSUE_DATE_FORMAT != '') {
					$ISSUE_DATE_FORMAT = date('Y-m-d', strtotime($ISSUE_DATE_FORMAT));
				}

				// Check if CERTIFICATE_PATH constant exists
				if (defined('CERTIFICATE_PATH') && $CERTIFICATE_FILE != '') {
					$cert_path = CERTIFICATE_PATH . "/" . $CERTIFICATE_FILE;
				}
				
				$threeyearbackdate = date('Y-m-d', strtotime('-3 years'));

				$PHOTO = '../uploads/resources/dummy/dummy-photo.png';
				if ($STUDENT_PHOTO != '' && defined('STUDENT_DOCUMENTS_PATH')) {
					$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;
				}

				$SIGN = '../uploads/resources/dummy/dummy-signature.png';
				if ($STUDENT_SIGN != '' && defined('STUDENT_DOCUMENTS_PATH')) {
					$SIGN = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_SIGN;
				}

				$GRADE = explode(':', $GRADE);
				$GRADE = isset($GRADE[0]) ? $GRADE[0] : '';
				$EXAM_TITLE = explode("(", $AICPE_COURSE_AWARD);
				$EXAM_TITLE = isset($EXAM_TITLE[0]) ? $EXAM_TITLE[0] : '';
			}
		}
	} else {
		$errors['system'] = 'System error: Unable to load certificate data.';
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Certificate Update Form</title>
	<style>
		.password-field {
			display: none;
			background-color: #fff3cd;
			border: 1px solid #ffeaa7;
			padding: 10px;
			margin: 10px 0;
			border-radius: 4px;
		}

		.password-field.show {
			display: block;
		}

		.password-warning {
			color: #856404;
			font-size: 12px;
			margin-top: 5px;
		}

		.has-error input {
			border-color: #dc3545;
		}

		.help-block {
			color: #dc3545;
			font-size: 12px;
		}
	</style>
</head>

<body>
	<div class="content-wrapper">
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title">Update Certificate</h4>
						<form class="forms-sample" action="" method="post" enctype="multipart/form-data">
							<?php if (isset($success) && ($success !== false || !empty($errors))): ?>
								<div class="row">
									<div class="col-md-12">
										<div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
											<h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
											<?= isset($message) ? $message : 'Please correct the errors.'; ?>
											<?php if (!empty($errors)): ?>
												<ul>
													<?php foreach ($errors as $error): ?>
														<li><?= htmlspecialchars($error) ?></li>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>
										</div>
									</div>
								</div>
							<?php endif; ?>

							<div class="row">
								<input type="hidden" value="<?= htmlspecialchars($CERTIFICATE_DETAILS_ID) ?>" name="certificate_details_id" />
								<input type="hidden" value="<?= htmlspecialchars($CERTIFICATE_SERIAL_NO) ?>" name="certificate_serial_no" />
								<input type="hidden" value="<?= htmlspecialchars($STUDENT_PHOTO) ?>" name="stud_photo" />
								<input type="hidden" value="<?= htmlspecialchars($STUDENT_ID) ?>" name="stud_id" />
								<input type="hidden" value="<?= isset($ISSUE_DATE_FORMAT) ? htmlspecialchars($ISSUE_DATE_FORMAT) : '' ?>" id="original_date" />

								<div class="form-group col-md-4 <?= (isset($errors['studphoto'])) ? 'has-error' : '' ?>">
									<label>Student Photo</label>
									<input class="form-control" id="studphoto" name="studphoto" type="file" onchange="readURL(this);" />
									<span class="help-block"><?= isset($errors['studphoto']) ? htmlspecialchars($errors['studphoto']) : '' ?></span>
									<img src="<?= htmlspecialchars($PHOTO) ?>" class="img img-responsive" id="img_preview" style="height:100px;width:100px;" />
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['stud_sign'])) ? 'has-error' : '' ?>">
									<label>Student Signature</label>
									<input class="form-control" id="stud_sign" name="stud_sign" type="file" onchange="readURL(this);" />
									<span class="help-block"><?= isset($errors['stud_sign']) ? htmlspecialchars($errors['stud_sign']) : '' ?></span>
									<img src="<?= htmlspecialchars($SIGN) ?>" class="img img-responsive" id="img_preview" style="height:100px;" />
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['studname'])) ? 'has-error' : '' ?>">
									<label>Student Name</label>
									<input class="form-control" id="studname" name="studname" placeholder="Student name" value="<?= isset($_POST['studname']) ? htmlspecialchars($_POST['studname']) : htmlspecialchars($STUDENT_NAME) ?>" type="text" />
									<span class="help-block"><?= isset($errors['studname']) ? htmlspecialchars($errors['studname']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['fname'])) ? 'has-error' : '' ?>">
									<label>Student Name On Marksheet</label>
									<input class="form-control" id="fname" name="fname" placeholder="Student Name On Marksheet" value="<?= isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : htmlspecialchars($STUDENT_FNAME) ?>" type="text" />
									<span class="help-block"><?= isset($errors['fname']) ? htmlspecialchars($errors['fname']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['mname'])) ? 'has-error' : '' ?>">
									<label>Father Name / Husband Name</label>
									<input class="form-control" id="mname" name="mname" placeholder="Father Name / Husband Name" value="<?= isset($_POST['mname']) ? htmlspecialchars($_POST['mname']) : htmlspecialchars($STUDENT_MNAME) ?>" type="text" />
									<span class="help-block"><?= isset($errors['mname']) ? htmlspecialchars($errors['mname']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['lname'])) ? 'has-error' : '' ?>">
									<label>Surname Name</label>
									<input class="form-control" id="lname" name="lname" placeholder="Surname Name" value="<?= isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : htmlspecialchars($STUDENT_LNAME) ?>" type="text" />
									<span class="help-block"><?= isset($errors['lname']) ? htmlspecialchars($errors['lname']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['mothername'])) ? 'has-error' : '' ?>">
									<label>Mother Name</label>
									<input class="form-control" id="mothername" name="mothername" placeholder="Mother Name" value="<?= isset($_POST['mothername']) ? htmlspecialchars($_POST['mothername']) : htmlspecialchars($STUDENT_MOTHER_NAME) ?>" type="text" />
									<span class="help-block"><?= isset($errors['mothername']) ? htmlspecialchars($errors['mothername']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['dob'])) ? 'has-error' : '' ?>">
									<label>Date Of Birth</label>
									<input class="form-control" id="" name="dob" placeholder="Date Of Birth" value="<?= isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : htmlspecialchars($STUDENT_DOB) ?>" type="text" />
									<span class="help-block"><?= isset($errors['dob']) ? htmlspecialchars($errors['dob']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['stud_photo_id_type'])) ? 'has-error' : '' ?>">
									<label>Student Photo ID Type</label>
									<input class="form-control" id="stud_photo_id_type" name="stud_photo_id_type" placeholder="Student Photo ID Type" value="<?= isset($_POST['stud_photo_id_type']) ? htmlspecialchars($_POST['stud_photo_id_type']) : htmlspecialchars($STUD_ID_PROOF_TYPE) ?>" type="text" />
									<span class="help-block"><?= isset($errors['stud_photo_id_type']) ? htmlspecialchars($errors['stud_photo_id_type']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['stud_photo_id'])) ? 'has-error' : '' ?>">
									<label>Student Photo ID</label>
									<input class="form-control" id="stud_photo_id" name="stud_photo_id" placeholder="Student Photo ID Type" value="<?= isset($_POST['stud_photo_id']) ? htmlspecialchars($_POST['stud_photo_id']) : htmlspecialchars($STUD_ID_PROOF_NUMBER) ?>" type="text" />
									<span class="help-block"><?= isset($errors['stud_photo_id']) ? htmlspecialchars($errors['stud_photo_id']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['marksobt'])) ? 'has-error' : '' ?>">
									<label>Objective Marks</label>
									<input class="form-control" id="marksobt" placeholder="Total objective Marks obtained" type="number" name="marksobt" onkeyup="this.value = minmax(this.value, 0, 50)" value="<?= isset($_POST['marksobt']) ? htmlspecialchars($_POST['marksobt']) : htmlspecialchars($OBJECTIVE_MARKS) ?>">
									<span class="help-block"><?= (isset($errors['marksobt'])) ? htmlspecialchars($errors['marksobt']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['marksobtpract'])) ? 'has-error' : '' ?>">
									<label>Practical Marks</label>
									<input class="form-control" id="marksobtpract" placeholder="Total practical Marks obtained" type="number" name="marksobtpract" maxlength="50" onkeyup="this.value = minmax(this.value, 0, 50)" value="<?= isset($_POST['marksobtpract']) ? htmlspecialchars($_POST['marksobtpract']) : htmlspecialchars($PRACTICAL_MARKS) ?>">
									<span class="help-block"><?= (isset($errors['marksobtpract'])) ? htmlspecialchars($errors['marksobtpract']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['subject'])) ? 'has-error' : '' ?>">
									<label>Subjects</label>
									<textarea class="form-control" id="subject" placeholder="SUBJECT" type="text" name="subject"><?= isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : htmlspecialchars($SUBJECT) ?></textarea>
									<span class="help-block"><?= (isset($errors['subject'])) ? htmlspecialchars($errors['subject']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['grade'])) ? 'has-error' : '' ?>">
									<label>Grade</label>
									<input class="form-control" id="grade" name="grade" placeholder="Grade" value="<?= isset($_POST['grade']) ? htmlspecialchars($_POST['grade']) : htmlspecialchars($GRADE) ?>" type="text" />
									<span class="help-block"><?= isset($errors['grade']) ? htmlspecialchars($errors['grade']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['marks'])) ? 'has-error' : '' ?>">
									<label>Marks</label>
									<input class="form-control" id="marks_per" name="marks" placeholder="Marks" value="<?= isset($_POST['marks']) ? htmlspecialchars($_POST['marks']) : htmlspecialchars($MARKS_PER) ?>" type="text" />
									<span class="help-block"><?= isset($errors['marks']) ? htmlspecialchars($errors['marks']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['exam_title'])) ? 'has-error' : '' ?>">
									<label>Exam Title</label>
									<input class="form-control" id="exam_title" name="exam_title" placeholder="Exam Title" value="<?= isset($_POST['exam_title']) ? htmlspecialchars($_POST['exam_title']) : htmlspecialchars($COURSE_NAME) ?>" type="text" />
									<span class="help-block"><?= isset($errors['exam_title']) ? htmlspecialchars($errors['exam_title']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['certicateno'])) ? 'has-error' : '' ?>">
									<label>Certificate No.</label>
									<input class="form-control" id="certicateno" name="certicateno" placeholder="Certificate No" value="<?= isset($_POST['certicateno']) ? htmlspecialchars($_POST['certicateno']) : htmlspecialchars($CERTIFICATE_NO) ?>" type="text" readonly/>
									<span class="help-block"><?= isset($errors['certicateno']) ? htmlspecialchars($errors['certicateno']) : '' ?></span>
								</div>

								<div class="form-group col-md-4 <?= (isset($errors['certicatedate'])) ? 'has-error' : '' ?>">
									<label>Certificate Date</label>
									<input class="form-control" id="certicatedate" name="certicatedate" placeholder="Certificate Date" value="<?= isset($_POST['certicatedate']) ? htmlspecialchars($_POST['certicatedate']) : htmlspecialchars($ISSUE_DATE_FORMAT) ?>" type="date" onchange="checkDateChange()" />
									<span class="help-block"><?= isset($errors['certicatedate']) ? htmlspecialchars($errors['certicatedate']) : '' ?></span>
								</div>

								<!-- Password field for date changes -->
								<div class="col-md-12">
									<div class="password-field" id="passwordField">
										<div class="form-group <?= (isset($errors['date_change_password'])) ? 'has-error' : '' ?>">
											<label><strong>Password Required for Date Change</strong></label>
											<input class="form-control" id="date_change_password" name="date_change_password" type="password" placeholder="Enter password to change certificate date" />
											<span class="help-block"><?= isset($errors['date_change_password']) ? htmlspecialchars($errors['date_change_password']) : '' ?></span>
											<div class="password-warning">
												<i class="fa fa-warning"></i> You are changing the certificate issue date. Please enter the authorization password to proceed.
											</div>
										</div>
									</div>
								</div>
							</div>

							<a href="page.php?page=listRequestedCertificates" class="btn btn-danger btn1">Cancel</a>
							<input type="submit" name="print_certificate" class="btn btn-primary btn1" value="Update Certificate" />
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		function minmax(value, min, max) {
			if (parseInt(value) < min || isNaN(parseInt(value)))
				return min;
			else if (parseInt(value) > max)
				return max;
			else return value;
		}

		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					var img = input.parentNode.querySelector('img');
					if (img) {
						img.src = e.target.result;
					}
				}
				reader.readAsDataURL(input.files[0]);
			}
		}

		function checkDateChange() {
			const originalDate = document.getElementById('original_date').value;
			const currentDate = document.getElementById('certicatedate').value;
			const passwordField = document.getElementById('passwordField');

			// Convert dates to comparable format and check if they're different
			if (originalDate && currentDate && originalDate !== currentDate) {
				passwordField.classList.add('show');
				document.getElementById('date_change_password').required = true;
			} else {
				passwordField.classList.remove('show');
				document.getElementById('date_change_password').required = false;
				document.getElementById('date_change_password').value = '';
			}
		}

		// Check on page load if there's already a date change
		document.addEventListener('DOMContentLoaded', function() {
			checkDateChange();
		});
	</script>

</body>

</html>