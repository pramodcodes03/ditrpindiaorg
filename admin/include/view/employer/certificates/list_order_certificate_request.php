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
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<h1>
 			All Order Certificate Requests

 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li><a href="#"> Certificates</a></li>
 			<li class="active"> All Order Certificate Requests</li>
 		</ol>
 	</section>

 	<!-- Main content -->
 	<section class="content">
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
 			<div class="col-xs-12">
 				<div class="box box-primary">
 					<!-- /.box-header -->
 					<div class="box-header">

 						<h3 class="box-title">Search By Filters</h3>
 					</div>
 					<div class="box-body">
 						<form action="" method="post" onsubmit="pageLoaderOverlay('show')">
 							<input type="hidden" name="page" value="list-requested-certificates" />

 							<div class="form-group col-sm-3">
 								<label>Student</label>
 								<select class="form-control select2" name="studid" id="studid">
 									<?php echo $db->MenuItemsDropdown('certificate_order_requests', "STUDENT_ID", "STUDENT_NAME", "DISTINCT STUDENT_ID, get_student_name(STUDENT_ID) AS STUDENT_NAME", $studid, " WHERE DELETE_FLAG=0"); ?>
 								</select>
 							</div>

 							<div class="form-group col-sm-3">
 								<label>Institute</label>
 								<select class="form-control select2" name="institute" id="institute">
 									<?php echo $db->MenuItemsDropdown('certificate_order_requests', "INSTITUTE_ID", "INSTITUTE_NAME", "DISTINCT INSTITUTE_ID, get_institute_name(INSTITUTE_ID) AS INSTITUTE_NAME", $institute, " WHERE DELETE_FLAG=0"); ?>
 								</select>
 							</div>
 							<div class="form-group col-sm-2">
 								<label>Request Status</label>
 								<select class="form-control" name="requeststatus" id="requeststatus">
 									<?php echo $db->MenuItemsDropdown('certificate_order_requests_status_master', "REQUEST_STATUS_ID", "REQUEST_STATUS", "REQUEST_STATUS_ID, REQUEST_STATUS", $requeststatus, " WHERE REQUEST_STATUS_ID!=3"); ?>
 								</select>
 							</div>

 							<div class="form-group col-sm-2">
 								<label>Course</label>
 								<select class="form-control" name="course" id="course">
 									<?php echo $db->MenuItemsDropdown('certificate_order_requests A LEFT JOIN courses B ON A.AICPE_COURSE_ID=B.COURSE_ID', "AICPE_COURSE_ID", "COURSE_NAME", "DISTINCT A.AICPE_COURSE_ID, B.COURSE_NAME ", $course, " WHERE A.DELETE_FLAG=0"); ?>
 								</select>
 							</div>

 							<div class="form-group col-sm-1">
 								<label> &nbsp;</label>
 								<input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />
 							</div>
 							<div class="form-group col-sm-1">
 								<label> &nbsp;</label>
 								<a class="form-control btn btn-sm btn-warning" onclick="pageLoaderOverlay('show'); location.assign('list-print-approval-certificate')">Clear</a>
 							</div>
 						</form>
 					</div>
 				</div>
 			</div>
 		</div>
 		<div class="row">
 			<form action="print-order-certificate" method="post" class="form-inline" onsubmit="return confirm('Confirm! Print certificates?'); pageLoaderOverlay('show')">
 				<div class="col-xs-12">
 					<div class="box box-warning">
 						<div class="box-header">
 							<?php if ($db->permission('print_certificate')) { ?>
 								<input type="submit" class="btn btn-primary" name="submit" value="Print Order Certificates" />
 								<input type="hidden" class="btn btn-sm btn-primary" name="action" value="printcertificates">
 							<?php } ?>

 							<a href="https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx" class="btn btn-warning" target="blank" style="float:right;">View Parcel Status</a>

 							<div class="clearfix"></div>

 						</div>
 						<!-- /.box-header -->
 						<div class="box-body">
 							<?php
								//$sql = "SELECT A.CERTIFICATE_REQUEST_MASTER_ID, A.INSTITUTE_ID, get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME,DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,get_institute_city(A.INSTITUTE_ID) AS INSTITUTE_CITY, A.TOTAL_EXAM_FEES, (SELECT COUNT(*) FROM certificate_order_requests WHERE CERTIFICATE_REQUEST_MASTER_ID=A.CERTIFICATE_REQUEST_MASTER_ID AND DELETE_FLAG=0) AS TOTAL_REQ FROM certificate_order_requests A WHERE A.DELETE_FLAG=0 $cond ORDER BY A.CREATED_ON DESC";

								/* Pagination Code */
								$rec_limit = 50;

								$sql = "SELECT COUNT(certificate_order_requests_master.CERTIFICATE_REQUEST_MASTER_ID) as total FROM certificate_order_requests_master WHERE certificate_order_requests_master.DELETE_FLAG=0";
								$exc = $db->execQuery($sql);
								$rec = $exc->fetch_assoc();
								$rec_count = $rec['total'];

								if (isset($_GET['pg'])) {
									$page = $_GET['pg'] + 1;
									$offset = $rec_limit * $page;
								} else {
									$page = 0;
									$offset = 0;
								}
								$left_rec = $rec_count - ($page * $rec_limit);
								$pageUrl = 'list-print-approval-certificate';
								/* Pagination COde */

								//$sql = "SELECT A.CERTIFICATE_REQUEST_MASTER_ID, A.INSTITUTE_ID, get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_name(A.INSTITUTE_ID) AS 
								//INSTITUTE_NAME,get_prime_member(A.INSTITUTE_ID) AS PRIMEMEMBER,DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,get_institute_city(A.INSTITUTE_ID) AS INSTITUTE_CITY, A.TOTAL_EXAM_FEES, 
								//(SELECT COUNT(*) FROM certificate_order_requests WHERE CERTIFICATE_REQUEST_MASTER_ID=A.CERTIFICATE_REQUEST_MASTER_ID AND DELETE_FLAG=0) AS TOTAL_REQ, B.USER_NAME,
								//B.USER_LOGIN_ID , C.DISPATCH_ID, C.RECEIPTNO, C.DISPATCH_DATE, C.NO_OF_CERTIFICATE, C.MODE_OF_DISPATCH, C.PREVIEW_SMS, C.DISPATCH_STATUS,A.PRINT_CERT 
								//FROM certificate_order_requests_master A LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID LEFT JOIN postal_dispatch C ON 
								//A.CERTIFICATE_REQUEST_MASTER_ID = C.CERTIFICATE_REQUEST_MASTER_ID WHERE A.DELETE_FLAG=0  AND B.USER_ROLE=2 $cond ORDER BY A.CREATED_ON DESC LIMIT $offset, $rec_limit";

								$sql = "SELECT A.CERTIFICATE_REQUEST_MASTER_ID, A.INSTITUTE_ID, get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_name(A.INSTITUTE_ID) AS 
			 INSTITUTE_NAME,get_prime_member(A.INSTITUTE_ID) AS PRIMEMEMBER,DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,(SELECT F.CITY_NAME as city_name FROM city_master F WHERE A.INSTITUTE_ID=F.CITY_ID) AS INSTITUTE_CITY, A.TOTAL_EXAM_FEES, 
			 (SELECT COUNT(*) FROM certificate_order_requests WHERE CERTIFICATE_REQUEST_MASTER_ID=A.CERTIFICATE_REQUEST_MASTER_ID AND DELETE_FLAG=0) AS TOTAL_REQ, B.USER_NAME,
			 B.USER_LOGIN_ID,B.PASS_WORD , C.DISPATCH_ID, C.RECEIPTNO, C.DISPATCH_DATE, C.NO_OF_CERTIFICATE, C.MODE_OF_DISPATCH, C.PREVIEW_SMS, C.DISPATCH_STATUS,A.PRINT_CERT 
			 FROM certificate_order_requests_master A LEFT JOIN user_login_master B ON A.INSTITUTE_ID=B.USER_ID LEFT JOIN postal_dispatch C ON 
			 A.CERTIFICATE_REQUEST_MASTER_ID = C.CERTIFICATE_REQUEST_MASTER_ID WHERE A.DELETE_FLAG=0  AND B.USER_ROLE=2 $cond ORDER BY A.CREATED_ON DESC LIMIT $offset, $rec_limit";



								$res1 = $db->execQuery($sql);

								if ($res1 != '') {
									$sr = 1;
									/* Pagination */
									if ($page > 0) {
										$last = $page - 2;
										echo "<a class='btn btn-success' href = \"$pageUrl&pg=$last\">Last 50 Records</a> |";
										echo "<a class='btn btn-success' href = \"$pageUrl&pg=$page\">Next 50 Records</a>";
									} else if ($page == 0) {
										echo "<a class='btn btn-success' href = \"$pageUrl&pg=$page\">Next 50 Records</a>";
									} else if ($left_rec < $rec_limit) {
										$last = $page - 2;
										echo "<a class='btn btn-success' href = \"$pageUrl&pg=$last\">Last 50 Records</a>";
									}
									/* Pagination */

									$maintbl = '
				            <div class="table-responsive">
				            <table class="table table-bordered table-hover table-striped">
							<tr>
								<th></th>
								<th>#</th>
								<th>Institute Code</th>
								<th>Institute Name</th>
								<th>Total Exam Fees</th>
								<!-- <th>Payments</th> -->
								 <th>Date</th>
								 <th>Consignment No.</th>
								 <th>Action</th>
							</tr>
							';
									while ($data = $res1->fetch_assoc()) {
										extract($data);
										//print_r($data);
										$PASS_WORD 			= $data['PASS_WORD'];
										$USER_NAME 			= $data['USER_NAME'];

										//print_r($data);exit();
										if ($TOTAL_REQ > 0) {
											$addpayment  = '<a href="page.php?page=add-examfees-payment&reqid=' . $CERTIFICATE_REQUEST_MASTER_ID . '">Add Payment</a>';
											$action1 = '';
											/*	if($db->permission('delete_certificate'))	
					
					$action1 = "<a href='javascript:void(0)' onclick='deleteCertificateRequestAllOrder($CERTIFICATE_REQUEST_MASTER_ID, $sr)' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";
					*/

											//Print Address of Institute

											$printCert = "<a page.php?page=print-franchise-address&inst[]=$INSTITUTE_ID' class='btn btn-link' title='Print Address' target='_blank'><i class=' fa fa-envelope'></i></a>";
											$inst_mobile = $db->get_user_mobile($INSTITUTE_ID, 2);
											$action1 .= "<a href='javascript:void(0)' class='btn btn-link send-cert-dispatch-sms send-parcel-details' title='Send Dispatch SMS' data-toggle='modal' data-target='.cert_disptach_sms' data-total='$TOTAL_REQ' data-id='$CERTIFICATE_REQUEST_MASTER_ID' data-name='$INSTITUTE_NAME' data-mobile='$inst_mobile' data-instid='$INSTITUTE_ID' data-receipt='$RECEIPTNO' data-smsmessage='$PREVIEW_SMS' data-status='$DISPATCH_STATUS' data-dispatchid='$DISPATCH_ID'><i class=' fa fa-envelope'></i></a>";

											//$action1 .= "<a href='javascript:void(0)' class='btn btn-link send-cert-dispatch-sms' title='Send Dispatch SMS' data-toggle='modal' data-target='.cert_disptach_sms' data-total='$TOTAL_REQ' data-id='$CERTIFICATE_REQUEST_MASTER_ID' data-name='$INSTITUTE_NAME' data-mobile='$inst_mobile'><i class='fa fa-address-card'></i></a>";
											//Institute Login
											$params = "'$USER_NAME','" . $PASS_WORD . "'";
											$loginBtn = "<a href='javascript:void(0)' class='btn btn-primary btn-xs' title='LOGIN' onclick=\"loginToInst($params)\"><i class=' fa fa-sign-in'></i>Login</a>";
											//	$loginBtn='';

											$parcel_status = "";

											if ($DISPATCH_STATUS == 1) {
												$parcel_status = "<a class='btn btn-primary'> Dispatch From Admin </a>";
											} elseif ($DISPATCH_STATUS == 2) {
												$parcel_status = "<a class='btn btn-danger'> Received By Institute </a>";
											}

											//print certificate status only for staff
											if ($PRINT_CERT == 1)
												$PRINT_CERT = '<a class="btn btn-success btn-xs" href="javascript:void(0)" style="color:#FFF" onclick="changePrintStatus(' . $CERTIFICATE_REQUEST_MASTER_ID . ',0)"><i class="fa fa-check"></i> YES PRINTED</a>';
											elseif ($PRINT_CERT == 0)
												$PRINT_CERT = '<a class="btn btn-danger btn-xs" href="javascript:void(0)" style="color:#FFF" onclick="changePrintStatus(' . $CERTIFICATE_REQUEST_MASTER_ID . ',1)"><i class="fa fa-times"></i> NO PRINT</a>';


											$color = '';
											if ($PRIMEMEMBER = 1 && $PRIMEMEMBER != NULL && $PRIMEMEMBER != 0) {
												$color = "style='color:red; font-weight:bold;'";
											}

											$srno = 1;
											$maintbl .= '
								<tr id="req-' . $CERTIFICATE_REQUEST_MASTER_ID . '">
									<td><a href="javascript:void(0)" onclick="toggleRow(' . $sr . ')" class="btn btn-xs btn-primary"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
									</a><a href="javascript:void(0)" onclick="toggleRow(' . $sr . ')" class="label label-default pull-right">' . $TOTAL_REQ . '</a></td>
									<td>' . $sr . '</td>
									<td>' . $INSTITUTE_CODE . ' <p>' . $printCert . ' ' . $loginBtn . '</p></td>
									<td ' . $color . '>' . $INSTITUTE_NAME . '</td>									
									<td>' . $TOTAL_EXAM_FEES . '</td>									
									<!-- <td>' . $addpayment . '</td> -->
									<td>' . $CREATED_DATE . '</td>	
									<td>' . $RECEIPTNO . '</td>
									<td id="print-' . $CERTIFICATE_REQUEST_MASTER_ID . '">' . $action1 . ' ' . $parcel_status . ' ' . $PRINT_CERT . '</td>			
								</tr>
								
								';

											$condition = " AND A.CERTIFICATE_REQUEST_MASTER_ID=$CERTIFICATE_REQUEST_MASTER_ID";
											if ($requeststatus != '')
												$condition .= " AND A.REQUEST_STATUS=$requeststatus ";
											if ($course != '')
												$condition .= " AND A.AICPE_COURSE_ID=$course ";

											$res 	= $exam->list_order_certificates_requests('', $studid, $INSTITUTE_ID, $condition);

											$subtable = '';
											if ($res != '') {
												$subtable = '
								<tr style="display:none" id="row-' . $sr . '"><td colspan="8">						
								<table class="table table-bordered">
								<tr class="success">
								<th><input type="checkbox" name="selectall" id="checkCert' . $sr . '" class="selectall_cert"  /></th>
								<th>#</th>
								<th>Photo</th>
								<th>Student</th>
								<th>Course</th>				
								<th>Exam Fees</th>				
								<th>Marks</th> 
								<th>Result</th>
							<!-- <th>Certificate Status</th> -->		
								<th>Date</th>
								<th>Action</th> </tr>';
												while ($data = $res->fetch_assoc()) {
													extract($data);
													//print_r($data);


													/*$PHOTO = '../uploads/default_user.png';					
							if($STUDENT_PHOTO!='')
								$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;*/

													$PHOTO = '../uploads/default_user.png';
													if ($STUDENT_PHOTO != '')
														$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;


													$EXAM_TYPE_NAME = !empty($EXAM_TYPE_NAME) ? $EXAM_TYPE_NAME : '-';
													$GRADE = !empty($GRADE) ? $GRADE : '-';
													$action = '';
													//check the certificate is printed or not
													$check_print = $access->list_order_printed_certificates('', $CERTIFICATE_REQUEST_ID, ' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1');
													if ($check_print != '') {
														$check_cert = $check_print->fetch_assoc();
														$CERTIFICATE_FILE = $check_cert['CERTIFICATE_FILE'];
														$CERTIFICATE_DETAILS_ID = $check_cert['CERTIFICATE_DETAILS_ID'];

														$MARKS_PER = $check_cert['MARKS_PER'];
														$path = CERTIFICATE_PATH . "/" . $CERTIFICATE_FILE;
														if ($db->permission('view_certificate'))
															$action .= "<a href='page.php?page=view-order-student-certificate&checkstud=$STUDENT_ID&certreq=$CERTIFICATE_REQUEST_ID&course=$AICPE_COURSE_ID&course_multi_sub=$MULTI_SUB_COURSE_ID' target='_blank' class='btn' title='View Certificate'><i class='fa fa-eye'></i></a>";
														$action .= "<a href='page.php?page=print-order-requested-marksheet&checkstud=$STUDENT_ID&certreq=$CERTIFICATE_REQUEST_ID&course=$AICPE_COURSE_ID&course_multi_sub=$MULTI_SUB_COURSE_ID' target='_blank' class='btn' title='View Marksheet'><i class='fa fa-file-text-o'></i></a>";
														$action .= "<a href='page.php?page=print-order-modify-certificate&cert_detail_id=$CERTIFICATE_DETAILS_ID' target='_blank' class='btn' title='Update Certificate'><i class='fa fa-pencil'></i></a>";
													} else {
														if ($db->permission('print_certificate'))
															$action .= "<a href='print-order-certificate&checkstud[]=$CERTIFICATE_REQUEST_ID' class='btn' title='Print'><i class='fa fa-print'></i></a>";
													}

													//	if($db->permission('delete_certificate'))						
													//	$action .= "<a href='javascript:void(0)' onclick='deleteStudentResult(this.id)' id='result$CERTIFICATE_REQUEST_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";
													/*if($db->permission('delete_certificate'))				
						$action .= "<a href='javascript:void(0)' onclick='deleteCertificateRequestOrder($CERTIFICATE_REQUEST_ID)' id='result$CERTIFICATE_REQUEST_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";	
						*/

													$subtable .= "<tr id='irow-$CERTIFICATE_REQUEST_ID'>
									<td> <input type='checkbox' name='checkstud[]' id='checkstud$CERTIFICATE_REQUEST_ID' value='$CERTIFICATE_REQUEST_ID' class='checkCert$sr' /> </td>
									<td>$srno</td>
									<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>							
									<td>$STUDENT_NAME</td>
									<td>$EXAM_TITLE</td>							
									<td>$EXAM_FEES</td>	
									 <td>$MARKS_PER  % </td> 																	
									<td>$RESULT_STATUS </td> 																	
									<!-- <td>$REQUEST_STATUS_NAME</td>	 -->												
									<td>$CREATED_DATE</td>
									<td>$action</td>
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
								/* Pagination */
								if ($page > 0) {
									$last = $page - 2;
									echo "<a class='btn btn-success' href = \"$pageUrl&pg=$last\">Last 50 Records</a> |";
									echo "<a class='btn btn-success' href = \"$pageUrl&pg=$page\">Next 50 Records</a>";
								} else if ($page == 0) {
									echo "<a class='btn btn-success' href = \"$pageUrl&pg=$page\">Next 50 Records</a>";
								} else if ($left_rec < $rec_limit) {
									$last = $page - 2;
									echo "<a class='btn btn-success' href = \"$pageUrl&pg=$last\">Last 50 Records</a>";
								}
								/* Pagination */

								?>

 						</div>
 					</div>
 					<!-- /.box-body -->
 				</div>
 				<!-- /.box -->
 				<!-- /.box -->
 		</div>
 		<!-- /.col -->
 		</form>
 </div>
 <!-- /.row -->
 </section>
 <!-- /.content -->
 </div>
 <!-- modal to send email -->
 <div class="modal fade cert_disptach_sms" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
 	<img src="resources/dist/img/loader.gif" class="loader-mg-modal" />
 	<div class="modal-dialog" role="document">
 		<div class="modal-content">

 			<div class="box box-primary modal-body">
 				<div class="">
 					<div class="box-header with-border">
 						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 						<h3 class="box-title">Send Disptach Detail SMS</h3>
 					</div>
 					<!-- /.box-header -->
 					<form id="send_cert_dispatch_sms_form" method="post" class="form-horizontal">
 						<input type="hidden" name="institute_id" id="institute_id" value="institute_id" readonly />
 						<input type="hidden" name="cert_req_mast_id" id="cert_req_mast_id" value="" />
 						<input type="hidden" name="action" id="action" value="send_cert_dispatch_sms" />
 						<div class="box-body">
 							<div class="form-group">
 								<label for="reciept_no" class="col-sm-3 control-label">Consignment No.</label>
 								<div class="col-sm-9">
 									<input class="form-control" type="text" placeholder=" Consignment No." id="reciept_no" name="reciept_no" required="required" />
 								</div>
 							</div>
 							<div class="form-group">
 								<label for="date_dispatch" class="col-sm-3 control-label">Dispatch Date:</label>
 								<div class="col-sm-9">
 									<input class="form-control date_dispatch" type="text" placeholder="Date Of Dispatch" id="doj" name="date_dispatch" required="required" value="<?= date('d-m-Y'); ?>" />
 								</div>
 							</div>
 							<div class="form-group">
 								<label for="total_cert" class="col-sm-3 control-label">No. Of Certifiates:</label>
 								<div class="col-sm-9">
 									<input class="form-control" type="text" placeholder="NO. of certificates" id="total_cert" name="total_cert" required="required" />
 								</div>
 							</div>
 							<div class="form-group">
 								<label for="dispatch_mode" class="col-sm-3 control-label">Mode Of Dispatch</label>
 								<div class="col-sm-9">
 									<input class="form-control" type="text" placeholder="Reciept No" id="dispatch_mode" name="dispatch_mode" value="SPEED POST" />
 								</div>
 							</div>

 							<div class="form-group">
 								<label for="inst_name" class="col-sm-3 control-label">Institute Name</label>
 								<div class="col-sm-9">
 									<input class="form-control" type="text" placeholder="Instititue Name" id="inst_name" name="inst_name" readonly>
 								</div>
 							</div>
 							<div class="form-group">
 								<label for="inst_mobile" class="col-sm-3 control-label">Mobile No:</label>
 								<div class="col-sm-9">
 									<input class="form-control" type="text" placeholder="Mobile No" id="inst_mobile" name="inst_mobile" value="" />
 								</div>
 							</div>

 							<div class="form-group">
 								<label for="message" class="col-sm-3 control-label">Preview SMS</label>
 								<div class="col-sm-9">
 									<textarea class="form-control" name="message" id="message" style="height: 150px" readonly>
								</textarea>
 								</div>
 							</div>
 							<div class="form-group" id="msg-error">
 								<p class="help-block"></p>
 							</div>
 						</div>

 						<!-- /.box-body -->
 						<div class="box-footer">
 							<div class="pull-right">
 								<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
 								<button type="submit" name="send" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send SMS</button>
 							</div>
 						</div>
 					</form>
 					<!-- /.box-footer -->
 				</div>
 			</div>
 		</div>
 	</div>
 </div>