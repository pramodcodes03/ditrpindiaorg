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
		//header('location:page.php?page=list-exams');
	}
}


/* display exam results details */
$institute 	= $db->test(isset($_REQUEST['institute']) ? $_REQUEST['institute'] : '');
$studid 		= $db->test(isset($_REQUEST['studid']) ? $_REQUEST['studid'] : '');
$examtitle	 	= $db->test(isset($_REQUEST['examtitle']) ? $_REQUEST['examtitle'] : '');
$requeststatus 	= $db->test(isset($_REQUEST['requeststatus']) ? $_REQUEST['requeststatus'] : '');
$course 		= $db->test(isset($_REQUEST['course']) ? $_REQUEST['course'] : '');
$cond = '';
if ($institute != '') $cond .= " AND A.INSTITUTE_ID='$institute'";
//if($requeststatus!='') $cond .= " AND A.REQUEST_STATUS='$requeststatus'";
//if($course!='') $cond .= " AND A.AICPE_COURSE_ID='$course'";
//if($examtitle!='') $cond .= " AND A.EXAM_TITLE='$examtitle'";


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
												<?php echo $db->MenuItemsDropdown('certificate_requests', "STUDENT_ID", "STUDENT_NAME", "DISTINCT STUDENT_ID, get_student_name(STUDENT_ID) AS STUDENT_NAME", $studid, " WHERE DELETE_FLAG=0"); ?>
											</select>
										</div>

										<div class="form-group col-sm-3">
											<label>Institute</label>
											<select class="form-control select2" name="institute" id="institute">
												<?php echo $db->MenuItemsDropdown('certificate_requests', "INSTITUTE_ID", "INSTITUTE_NAME", "DISTINCT INSTITUTE_ID, get_institute_name(INSTITUTE_ID) AS INSTITUTE_NAME", $institute, " WHERE DELETE_FLAG=0"); ?>
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
												<?php echo $db->MenuItemsDropdown('certificate_requests A LEFT JOIN courses B ON A.AICPE_COURSE_ID=B.COURSE_ID', "AICPE_COURSE_ID", "COURSE_NAME", "DISTINCT A.AICPE_COURSE_ID, B.COURSE_NAME ", $course, " WHERE A.DELETE_FLAG=0"); ?>
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

							<!-- <div class="box-header" style="margin:10px 0px">
								<?php if ($db->permission('print_certificate')) { ?>
									<input type="submit" class="btn btn-primary" name="submit"  value="Approve Certificates" />
									<input type="hidden" class="btn btn-sm btn-primary" name="action" value="printcertificates">
								<?php } ?>
							</div> -->
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

									/* Count query matching main query conditions */
									$count_sql = "SELECT COUNT(*) as total
										FROM certificate_requests_master A
										LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID
										WHERE A.DELETE_FLAG=0 AND (B.USER_ROLE=2 OR B.USER_ROLE=8) $cond";
									$exc = $db->execQuery($count_sql);
									$rec = $exc->fetch_assoc();
									$rec_count = $rec['total'];
									$total_pages = max(1, ceil($rec_count / $rec_limit));
									if ($current_page > $total_pages) $current_page = $total_pages;
									$offset = ($current_page - 1) * $rec_limit;

									$pageUrl = 'page.php?page=listRequestedCertificates' . $filter_params;

									/* Main data query */
									$sql = "SELECT A.CERTIFICATE_REQUEST_MASTER_ID, A.INSTITUTE_ID, get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE, get_institute_name(A.INSTITUTE_ID) AS
									INSTITUTE_NAME, get_prime_member(A.INSTITUTE_ID) AS PRIMEMEMBER, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE, (SELECT F.CITY_NAME as city_name FROM city_master F WHERE A.INSTITUTE_ID=F.CITY_ID) AS INSTITUTE_CITY, A.TOTAL_EXAM_FEES,
									(SELECT COUNT(*) FROM certificate_requests WHERE CERTIFICATE_REQUEST_MASTER_ID=A.CERTIFICATE_REQUEST_MASTER_ID AND DELETE_FLAG=0) AS TOTAL_REQ, B.USER_NAME,
									B.USER_LOGIN_ID, B.PASS_TEXT, C.DISPATCH_ID, C.RECEIPTNO, C.DISPATCH_DATE, C.NO_OF_CERTIFICATE, C.MODE_OF_DISPATCH, C.PREVIEW_SMS, C.DISPATCH_STATUS
									FROM certificate_requests_master A LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID LEFT JOIN postal_dispatch C ON
									A.CERTIFICATE_REQUEST_MASTER_ID = C.CERTIFICATE_REQUEST_MASTER_ID WHERE A.DELETE_FLAG=0 AND (B.USER_ROLE=2 OR B.USER_ROLE=8) $cond ORDER BY A.CREATED_ON DESC LIMIT $offset, $rec_limit";

									$res1 = $db->execQuery($sql);

									if ($res1 != '') {
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
													<!-- <th>Payments</th> -->
													<th>Date</th>
													<!-- <th>Action</th> -->
												</tr>
												';
										while ($data = $res1->fetch_assoc()) {
											extract($data);
											$PASS_TEXT 			= $data['PASS_TEXT'];
											$USER_NAME 			= $data['USER_NAME'];

											//print_r($data);exit();
											if ($TOTAL_REQ > 0) {
												$addpayment  = '<a href="page.php?page=add-examfees-payment&reqid=' . $CERTIFICATE_REQUEST_MASTER_ID . '">Add Payment</a>';
												$action1 = '';

												//Print Address of Institute

												$printCert = "<a href='page.php?page=printFranchiseAddress&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Address' target='_blank'><i class=' mdi mdi-message-text'></i></a>";
												$inst_mobile = $db->get_user_mobile($INSTITUTE_ID, 2);

												$action1 = "<a href='javascript:void(0)' class='btn btn-link' title='SMS FOR ORDER CERTIFICATE' onclick=\"orderbeforeSMS($inst_mobile)\"><i class='fa fa-envelope'>SMS</i></a>";

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
												if ($PRIMEMEMBER = 1 && $PRIMEMEMBER != NULL && $PRIMEMEMBER != 0) {
													$color = "style='color:red; font-weight:bold;'";
												}


												$srno = 1;
												$maintbl .= '
													<tr id="req-' . $CERTIFICATE_REQUEST_MASTER_ID . '">
														<td><a href="javascript:void(0)" onclick="toggleRow(' . $sr . ')" class="btn btn-xs btn-primary"><i class="mdi mdi-arrow-down-drop-circle-outline" aria-hidden="true"></i>
														</a><a href="javascript:void(0)" onclick="toggleRow(' . $sr . ')" class="label label-default pull-right">' . $TOTAL_REQ . '</a></td>
														<td>' . $sr . '</td>
														<td>' . $INSTITUTE_CODE . ' <p>' . $printCert . ' ' . $loginBtn . '</p></td>
														<td ' . $color . '>' . $INSTITUTE_NAME . '</td>
														<td>' . $TOTAL_EXAM_FEES . '</td>
														<!-- <td>' . $addpayment . '</td> -->
														<td>' . $CREATED_DATE . '</td>
														<!-- <td>' . $action1 . ' ' . $parcel_status . '</td> -->
													</tr>

													';

												$condition = " AND A.CERTIFICATE_REQUEST_MASTER_ID=$CERTIFICATE_REQUEST_MASTER_ID";
												if ($requeststatus != '')
													$condition .= " AND A.REQUEST_STATUS=$requeststatus ";
												if ($course != '')
													$condition .= " AND A.COURSE_ID=$course ";

												$res 	= $exam->list_certificates_requests('', $studid, $INSTITUTE_ID, $condition);

												$subtable = '';
												if ($res != '') {
													$subtable = '
													<tr style="display:none" id="row-' . $sr . '"><td colspan="7">
													<table class="table table-bordered">
													<tr class="success">
													<th><input type="checkbox" name="selectall" id="checkCert' . $sr . '" class="selectall_cert"  /></th>
													<th>#</th>
													<th>Action</th>
													<th>Photo</th>
													<th>Student</th>
													<th>Course</th>
													<th>Exam Fees</th>
													<th>Marks</th>
													<th>Result</th>
												<!-- <th>Certificate Status</th> -->
													<th>Date</th>
												 </tr>';
													while ($data = $res->fetch_assoc()) {
														extract($data);
														//print_r($data);exit();

														$backClr = "";

														if ($TYPING_COURSE_ID != "" || $TYPING_COURSE_ID != 0 || $TYPING_COURSE_ID != NULL) {
															$backClr = "style='background-color:yellow'";
														}

														$PHOTO = '../uploads/default_user.png';
														if ($STUDENT_PHOTO != '')
															$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;

														$EXAM_TYPE_NAME = !empty($EXAM_TYPE_NAME) ? $EXAM_TYPE_NAME : '-';
														$GRADE = !empty($GRADE) ? $GRADE : '-';
														$action = '';
														//check the certificate is printed or not
														$check_print = $access->list_printed_certificates('', $CERTIFICATE_REQUEST_ID, ' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1');
														if ($check_print != '') {
															$check_cert = $check_print->fetch_assoc();
															$CERTIFICATE_FILE = $check_cert['CERTIFICATE_FILE'];
															$CERTIFICATE_DETAILS_ID = $check_cert['CERTIFICATE_DETAILS_ID'];
															$MARKS_PER = $check_cert['MARKS_PER'];
															$path = CERTIFICATE_PATH . "/" . $CERTIFICATE_FILE;

															if ($db->permission('view_certificate'))
																$action .= "<a href='page.php?page=view-student-certificate&checkstud=$STUDENT_ID&certreq=$CERTIFICATE_REQUEST_ID&course=$COURSE_ID&course_multi_sub=$MULTI_SUB_COURSE_ID&course_typing=$TYPING_COURSE_ID' target='_blank' class='btn btn-primary table-btn' title='View Certificate'><i class='mdi mdi-eye'></i></a>";
															$action .= "<a href='page.php?page=print-requested-marksheet&checkstud=$STUDENT_ID&certreq=$CERTIFICATE_REQUEST_ID&course=$COURSE_ID&course_multi_sub=$MULTI_SUB_COURSE_ID&course_typing=$TYPING_COURSE_ID' target='_blank' class='btn btn-primary table-btn' title='View Marksheet'><i class='mdi mdi-file-pdf'></i></a>";

															$action .= "<a href='page.php?page=certificatePrint&checkstud=$STUDENT_ID&certreq=$CERTIFICATE_REQUEST_ID&course=$COURSE_ID&course_multi_sub=$MULTI_SUB_COURSE_ID&course_typing=$TYPING_COURSE_ID' target='_blank' class='btn btn-warning table-btn' title='Print Certificate'><i class='mdi mdi-eye'></i></a>";
															$action .= "<a href='page.php?page=marksheetPrint&checkstud=$STUDENT_ID&certreq=$CERTIFICATE_REQUEST_ID&course=$COURSE_ID&course_multi_sub=$MULTI_SUB_COURSE_ID&course_typing=$TYPING_COURSE_ID' target='_blank' class='btn btn-warning table-btn' title='Print Marksheet'><i class='mdi mdi-file-pdf'></i></a>";

															$action .= "<a href='page.php?page=print-modify-certificate&cert_detail_id=$CERTIFICATE_DETAILS_ID' target='_blank' class='btn btn-primary table-btn' title='Update Certificate'><i class='mdi mdi-grease-pencil'></i></a>";
														} else {
															if ($db->permission('print_certificate'))
																$action .= "<a href='page.php?page=print-certificate&checkstud[]=$CERTIFICATE_REQUEST_ID' class='btn btn-primary btn1' title='Print'><i class='mdi mdi-download'></i> Approve </a>";
														}

														$subtable .= "<tr id='irow-$CERTIFICATE_REQUEST_ID' $backClr >
														<td> <input type='checkbox' name='checkstud[]' id='checkstud$CERTIFICATE_REQUEST_ID' value='$CERTIFICATE_REQUEST_ID' class='checkCert$sr' /> </td>

														<td>$srno</td>
															<td>$action</td>
														<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>
														<td>$STUDENT_NAME</td>
														<td>$EXAM_TITLE</td>
														<td>$EXAM_FEES</td>
														<td>$MARKS_PER  % </td>
														<td>$RESULT_STATUS </td>
														<!-- <td>$REQUEST_STATUS_NAME</td>	 -->
														<td>$CREATED_DATE</td>

														</tr>
														";
														$srno++;
													}
													$subtable .= '</table></td></tr>';
												}


												$maintbl .= $subtable;
												$sr++;
											}
										}
										echo	$maintbl .= '</table>';
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
