<?php
include('include/classes/exam.class.php');
$exam = new exam();

include('include/classes/exammultisub.class.php');
$exammultisub = new exammultisub();

/* apply for certificates */
$action = isset($_POST['action']) ? $_POST['action'] : '';
$checkstud = isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
if ($action == 'printcertificates') {
	$result = $exam->print_certificates();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = isset($result['message']) ? $result['message'] : '';
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
	}
}


/* display exam results details */
$institute 	= $db->test(isset($_REQUEST['institute']) ? $_REQUEST['institute'] : '');
$studid 		= $db->test(isset($_REQUEST['studid']) ? $_REQUEST['studid'] : '');
$examtitle	 	= $db->test(isset($_REQUEST['examtitle']) ? $_REQUEST['examtitle'] : '');
$requeststatus 	= $db->test(isset($_REQUEST['requeststatus']) ? $_REQUEST['requeststatus'] : '');
$course 		= $db->test(isset($_REQUEST['course']) ? $_REQUEST['course'] : '');
$cond = '';
if ($institute != '') $cond .= " AND A.INSTITUTE_ID='" . intval($institute) . "'";


?>

<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">All Certificate Requests
				</h4>
				<?php
				if (isset($_SESSION['msg'])) {
					$message = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
					$msg_flag = $_SESSION['msg_flag'];
				?>
					<div class="row">
						<div class="col-sm-12">
							<div class="alert alert-<?= ($msg_flag == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
								<h4><i class="icon fa fa-check"></i> <?= ($msg_flag == true) ? 'Success' : 'Error' ?>:</h4>
								<?= ($message != '') ? $message : 'Sorry! Something went wrong!'; ?>
							</div>
						</div>
					</div>
				<?php
					unset($_SESSION['msg']);
					unset($_SESSION['msg_flag']);
				}
				?>
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-header">
								<h5 class="box-title">Search By Filters</h5>
							</div>
							<div class="box-body">
								<form action="" method="post" onsubmit="pageLoaderOverlay('show')">
									<input type="hidden" name="page" value="listRequestedCertificates" />
									<div class="row">
										<div class="form-group col-md-3">
											<label>Student</label>
											<select class="form-control select2" name="studid" id="studid">
												<option value="">--select--</option>
												<?php
												$stud_sql = "SELECT DISTINCT A.STUDENT_ID, CONCAT(SD.STUDENT_FNAME,' ',IFNULL(SD.STUDENT_MNAME,''),' ',IFNULL(SD.STUDENT_LNAME,'')) AS STUDENT_NAME
													FROM certificate_requests A
													INNER JOIN student_details SD ON A.STUDENT_ID = SD.STUDENT_ID
													WHERE A.DELETE_FLAG=0 ORDER BY SD.STUDENT_FNAME LIMIT 500";
												$stud_res = $db->execQuery($stud_sql);
												if ($stud_res && $stud_res->num_rows > 0) {
													while ($srow = $stud_res->fetch_assoc()) {
														$sel = ($srow['STUDENT_ID'] == $studid) ? 'selected' : '';
														echo '<option value="' . $srow['STUDENT_ID'] . '" ' . $sel . '>' . htmlspecialchars(strtoupper($srow['STUDENT_NAME'])) . '</option>';
													}
												}
												?>
											</select>
										</div>

										<div class="form-group col-sm-3">
											<label>Institute</label>
											<select class="form-control select2" name="institute" id="institute">
												<option value="">--select--</option>
												<?php
												$inst_sql = "SELECT DISTINCT A.INSTITUTE_ID, I.INSTITUTE_NAME
													FROM certificate_requests A
													INNER JOIN institute_details I ON A.INSTITUTE_ID = I.INSTITUTE_ID
													WHERE A.DELETE_FLAG=0 ORDER BY I.INSTITUTE_NAME";
												$inst_res = $db->execQuery($inst_sql);
												if ($inst_res && $inst_res->num_rows > 0) {
													while ($irow = $inst_res->fetch_assoc()) {
														$sel = ($irow['INSTITUTE_ID'] == $institute) ? 'selected' : '';
														echo '<option value="' . $irow['INSTITUTE_ID'] . '" ' . $sel . '>' . htmlspecialchars(strtoupper($irow['INSTITUTE_NAME'])) . '</option>';
													}
												}
												?>
											</select>
										</div>
										<div class="form-group col-sm-2">
											<label>Request Status</label>
											<select class="form-control" name="requeststatus" id="requeststatus">
												<?php echo $db->MenuItemsDropdown('certificate_requests_status_master', "REQUEST_STATUS_ID", "REQUEST_STATUS", "REQUEST_STATUS_ID, REQUEST_STATUS", $requeststatus, " WHERE REQUEST_STATUS_ID!=3"); ?>
											</select>
										</div>

										<div class="form-group col-sm-2">
											<label>Course</label>
											<select class="form-control" name="course" id="course">
												<option value="">--select--</option>
												<?php
												$course_sql = "SELECT DISTINCT A.COURSE_ID, B.COURSE_NAME
													FROM certificate_requests A
													INNER JOIN courses B ON A.COURSE_ID=B.COURSE_ID
													WHERE A.DELETE_FLAG=0 AND A.COURSE_ID IS NOT NULL AND A.COURSE_ID != 0
													ORDER BY B.COURSE_NAME";
												$course_res = $db->execQuery($course_sql);
												if ($course_res && $course_res->num_rows > 0) {
													while ($crow = $course_res->fetch_assoc()) {
														$sel = ($crow['COURSE_ID'] == $course) ? 'selected' : '';
														echo '<option value="' . $crow['COURSE_ID'] . '" ' . $sel . '>' . htmlspecialchars(strtoupper($crow['COURSE_NAME'])) . '</option>';
													}
												}
												?>
											</select>
										</div>

										<div class="form-group col-sm-1">
											<label> &nbsp;</label>
											<input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />
										</div>
										<div class="form-group col-sm-1">
											<label> &nbsp;</label>
											<a class="form-control btn btn-sm btn-warning" onclick="pageLoaderOverlay('show'); location.assign('page.php?page=listRequestedCertificates')">Clear</a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<form action="page.php?page=print-certificate" method="post" onsubmit="return confirm('Confirm! Print certificates?'); pageLoaderOverlay('show')">
							<p style="color:red; padding:5px; background-color:yellow; font-weight:900; font-size:14px;">
								After Applying For Approve Certificate You Will Able To View Student Certificates And Marksheets.
							</p>

							<div class="box-body">
								<div class="table-responsive">
									<?php
									/* Pagination Setup */
									$rec_limit = 50;
									$current_page = isset($_GET['pg']) ? max(1, intval($_GET['pg'])) : 1;
									$offset = ($current_page - 1) * $rec_limit;

									/* Build filter query string for pagination links */
									$filter_params = '';
									if ($institute != '') $filter_params .= '&institute=' . urlencode($institute);
									if ($studid != '') $filter_params .= '&studid=' . urlencode($studid);
									if ($examtitle != '') $filter_params .= '&examtitle=' . urlencode($examtitle);
									if ($requeststatus != '') $filter_params .= '&requeststatus=' . urlencode($requeststatus);
									if ($course != '') $filter_params .= '&course=' . urlencode($course);

									/* Count query - optimized with JOINs instead of stored functions */
									$count_sql = "SELECT COUNT(*) as total
										FROM certificate_requests_master A
										INNER JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID
										WHERE A.DELETE_FLAG=0 AND (B.USER_ROLE=2 OR B.USER_ROLE=8) $cond";
									$exc = $db->execQuery($count_sql);
									$rec = $exc->fetch_assoc();
									$rec_count = $rec['total'];
									$total_pages = max(1, ceil($rec_count / $rec_limit));
									if ($current_page > $total_pages) $current_page = $total_pages;
									$offset = ($current_page - 1) * $rec_limit;

									$pageUrl = 'page.php?page=listRequestedCertificates' . $filter_params;

									/* Main data query - optimized: replaced MySQL stored functions with JOINs */
									$sql = "SELECT A.CERTIFICATE_REQUEST_MASTER_ID, A.INSTITUTE_ID,
										I.INSTITUTE_CODE, I.INSTITUTE_NAME, I.PRIMEMEMBER, I.MOBILE AS INST_MOBILE,
										DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,
										CI.CITY_NAME AS INSTITUTE_CITY,
										A.TOTAL_EXAM_FEES,
										(SELECT COUNT(*) FROM certificate_requests WHERE CERTIFICATE_REQUEST_MASTER_ID=A.CERTIFICATE_REQUEST_MASTER_ID AND DELETE_FLAG=0) AS TOTAL_REQ,
										B.USER_NAME, B.USER_LOGIN_ID, B.PASS_TEXT,
										C.DISPATCH_ID, C.RECEIPTNO, C.DISPATCH_DATE, C.NO_OF_CERTIFICATE, C.MODE_OF_DISPATCH, C.PREVIEW_SMS, C.DISPATCH_STATUS
										FROM certificate_requests_master A
										INNER JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID
										LEFT JOIN institute_details I ON A.INSTITUTE_ID=I.INSTITUTE_ID
										LEFT JOIN city_master CI ON I.CITY_ID=CI.CITY_ID
										LEFT JOIN postal_dispatch C ON A.CERTIFICATE_REQUEST_MASTER_ID = C.CERTIFICATE_REQUEST_MASTER_ID
										WHERE A.DELETE_FLAG=0 AND (B.USER_ROLE=2 OR B.USER_ROLE=8) $cond
										ORDER BY A.CREATED_ON DESC LIMIT $offset, $rec_limit";

									$res1 = $db->execQuery($sql);

									if ($res1 != '' && $res1->num_rows > 0) {
										$sr = $offset + 1;

										/* Pagination Navigation - Top */
										$start_record = $offset + 1;
										$end_record = min($offset + $rec_limit, $rec_count);
										?>
										<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; flex-wrap:wrap; gap:8px;">
											<span class="badge badge-info" style="font-size:13px; padding:8px 12px;">
												Showing <?= $start_record ?> - <?= $end_record ?> of <?= $rec_count ?> records | Page <?= $current_page ?> of <?= $total_pages ?>
											</span>
											<?php if ($total_pages > 1) { ?>
											<nav>
												<ul class="pagination" style="margin:0;">
													<?php if ($current_page > 1) { ?>
														<li class="page-item"><a class="page-link" href="<?= $pageUrl ?>&pg=1">&laquo; First</a></li>
														<li class="page-item"><a class="page-link" href="<?= $pageUrl ?>&pg=<?= $current_page - 1 ?>">&lsaquo; Prev</a></li>
													<?php } ?>
													<?php
													$start_pg = max(1, $current_page - 2);
													$end_pg = min($total_pages, $current_page + 2);
													for ($p = $start_pg; $p <= $end_pg; $p++) {
														if ($p == $current_page) { ?>
															<li class="page-item active"><span class="page-link"><?= $p ?></span></li>
														<?php } else { ?>
															<li class="page-item"><a class="page-link" href="<?= $pageUrl ?>&pg=<?= $p ?>"><?= $p ?></a></li>
														<?php }
													} ?>
													<?php if ($current_page < $total_pages) { ?>
														<li class="page-item"><a class="page-link" href="<?= $pageUrl ?>&pg=<?= $current_page + 1 ?>">Next &rsaquo;</a></li>
														<li class="page-item"><a class="page-link" href="<?= $pageUrl ?>&pg=<?= $total_pages ?>">Last &raquo;</a></li>
													<?php } ?>
												</ul>
											</nav>
											<?php } ?>
										</div>
										<?php

										$maintbl = '
												<table class="table table-bordered table-hover table-striped" style="margin: 5px 0px;">
												<tr>
													<th></th>
													<th>#</th>
													<th>Institute Code</th>
													<th>Institute Name</th>
													<th>Total Exam Fees</th>
													<th>Date</th>
												</tr>
												';
										while ($data = $res1->fetch_assoc()) {
											extract($data);
											$PASS_TEXT 			= $data['PASS_TEXT'];
											$USER_NAME 			= $data['USER_NAME'];
											$INSTITUTE_CODE		= $data['INSTITUTE_CODE'];
											$INSTITUTE_NAME		= $data['INSTITUTE_NAME'];
											$PRIMEMEMBER		= $data['PRIMEMEMBER'];
											$INST_MOBILE		= $data['INST_MOBILE'];

											if ($TOTAL_REQ > 0) {
												$printCert = "<a href='page.php?page=printFranchiseAddress&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Address' target='_blank'><i class=' mdi mdi-message-text'></i></a>";

												//Institute Login
												$params = "'$USER_NAME','" . md5($PASS_TEXT) . "'";
												$loginBtn = "<a href='javascript:void(0)' class='btn btn-primary btn-xs' title='LOGIN' onclick=\"loginToInst($params)\"><i class=' fa fa-sign-in'></i>Login</a>";

												$parcel_status = "";
												if ($DISPATCH_STATUS == 1) {
													$parcel_status = "<a class='btn btn-primary'> Dispatch From Admin </a>";
												} elseif ($DISPATCH_STATUS == 2) {
													$parcel_status = "<a class='btn btn-danger'> Received By Institute </a>";
												}

												$color = '';
												if ($PRIMEMEMBER == 1 && $PRIMEMEMBER != NULL && $PRIMEMEMBER != 0) {
													$color = "style='color:red; font-weight:bold;'";
												}

												$maintbl .= '
													<tr id="req-' . $CERTIFICATE_REQUEST_MASTER_ID . '">
														<td><a href="javascript:void(0)" onclick="loadSubRows(' . $sr . ',' . $CERTIFICATE_REQUEST_MASTER_ID . ',' . $INSTITUTE_ID . ')" class="btn btn-xs btn-primary"><i class="mdi mdi-arrow-down-drop-circle-outline" aria-hidden="true"></i>
														</a><a href="javascript:void(0)" onclick="loadSubRows(' . $sr . ',' . $CERTIFICATE_REQUEST_MASTER_ID . ',' . $INSTITUTE_ID . ')" class="label label-default pull-right">' . $TOTAL_REQ . '</a></td>
														<td>' . $sr . '</td>
														<td>' . $INSTITUTE_CODE . ' <p>' . $printCert . ' ' . $loginBtn . '</p></td>
														<td ' . $color . '>' . $INSTITUTE_NAME . '</td>
														<td>' . $TOTAL_EXAM_FEES . '</td>
														<td>' . $CREATED_DATE . '</td>
													</tr>
													<tr style="display:none" id="row-' . $sr . '"><td colspan="6">
														<div id="subdata-' . $sr . '" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>
													</td></tr>
													';

												$sr++;
											}
										}
										echo	$maintbl .= '</table>';
									} else {
										echo '<p class="text-muted">No certificate requests found.</p>';
									}

									/* Pagination Navigation - Bottom */
									if ($rec_count > 0 && $total_pages > 1) {
									?>
									<div style="display:flex; justify-content:space-between; align-items:center; margin-top:10px; flex-wrap:wrap; gap:8px;">
										<span class="badge badge-info" style="font-size:13px; padding:8px 12px;">
											Showing <?= $start_record ?> - <?= $end_record ?> of <?= $rec_count ?> records | Page <?= $current_page ?> of <?= $total_pages ?>
										</span>
										<nav>
											<ul class="pagination" style="margin:0;">
												<?php if ($current_page > 1) { ?>
													<li class="page-item"><a class="page-link" href="<?= $pageUrl ?>&pg=1">&laquo; First</a></li>
													<li class="page-item"><a class="page-link" href="<?= $pageUrl ?>&pg=<?= $current_page - 1 ?>">&lsaquo; Prev</a></li>
												<?php } ?>
												<?php
												$start_pg = max(1, $current_page - 2);
												$end_pg = min($total_pages, $current_page + 2);
												for ($p = $start_pg; $p <= $end_pg; $p++) {
													if ($p == $current_page) { ?>
														<li class="page-item active"><span class="page-link"><?= $p ?></span></li>
													<?php } else { ?>
														<li class="page-item"><a class="page-link" href="<?= $pageUrl ?>&pg=<?= $p ?>"><?= $p ?></a></li>
													<?php }
												} ?>
												<?php if ($current_page < $total_pages) { ?>
													<li class="page-item"><a class="page-link" href="<?= $pageUrl ?>&pg=<?= $current_page + 1 ?>">Next &rsaquo;</a></li>
													<li class="page-item"><a class="page-link" href="<?= $pageUrl ?>&pg=<?= $total_pages ?>">Last &raquo;</a></li>
												<?php } ?>
											</ul>
										</nav>
									</div>
									<?php } ?>


								</div>

						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
var loadedRows = {};
function loadSubRows(sr, certMasterId, instId) {
	var row = document.getElementById('row-' + sr);
	// Toggle visibility
	if (row.style.display === 'none' || row.style.display === '') {
		row.style.display = 'table-row';
	} else {
		row.style.display = 'none';
		return;
	}
	// If already loaded, don't reload
	if (loadedRows[sr]) return;

	$.ajax({
		url: '/admin/include/classes/ajax.php',
		type: 'POST',
		dataType: 'json',
		data: {
			action: 'get_certificate_sub_rows',
			cert_master_id: certMasterId,
			inst_id: instId,
			studid: '<?= addslashes($studid) ?>',
			requeststatus: '<?= addslashes($requeststatus) ?>',
			course: '<?= addslashes($course) ?>',
			sr: sr
		},
		success: function(response) {
			if (response.html) {
				$('#subdata-' + sr).html(response.html);
			} else if (response.error) {
				$('#subdata-' + sr).html('<p class="text-danger">' + response.error + '</p>');
			}
			loadedRows[sr] = true;
		},
		error: function() {
			$('#subdata-' + sr).html('<p class="text-danger">Failed to load data. Please try again.</p>');
		}
	});
}
</script>
