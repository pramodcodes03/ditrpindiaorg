 <?php
	//print_r($_SESSION);exit;
	$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
	$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
	if ($user_role == 5) {
		$institute_id = $db->get_parent_id($user_role, $user_id);
		$staff_id = $user_id;
	} else {
		$institute_id = $user_id;
		$staff_id = 0;
	}

	include('include/classes/exam.class.php');
	$exam = new exam();

	/* display exam results details */
	$institute 	= $db->test(isset($_REQUEST['institute']) ? $_REQUEST['institute'] : '');
	$studid 		= $db->test(isset($_REQUEST['studid']) ? $_REQUEST['studid'] : '');
	$examtitle	 	= $db->test(isset($_REQUEST['examtitle']) ? $_REQUEST['examtitle'] : '');
	$requeststatus 	= $db->test(isset($_REQUEST['requeststatus']) ? $_REQUEST['requeststatus'] : '');
	$course 		= $db->test(isset($_REQUEST['course']) ? $_REQUEST['course'] : '');
	$cond = '';
	if ($institute != '') $cond .= " AND A.INSTITUTE_ID='$institute'";
	?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<h1>
 			Parcel Track System
 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li><a <a href="#"> Setting</a></li>
 			<li class="active"> Parcel Track</li>
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
 			<form action="print-certificate" method="post" class="form-inline" onsubmit="return confirm('Confirm! Print certificates?'); pageLoaderOverlay('show')">
 				<div class="col-xs-12">
 					<div class="box box-warning">
 						<div class="box-header">

 							<a href="page.php?page=https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx" class="btn btn-primary" target="blank">View Parcel Status</a>

 							<div class="clearfix"></div>

 						</div>
 						<div class="box-body">
 							<?php
								$sql = "SELECT A.CERTIFICATE_REQUEST_MASTER_ID, A.INSTITUTE_ID, get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME,DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,get_institute_city(A.INSTITUTE_ID) AS INSTITUTE_CITY, A.TOTAL_EXAM_FEES, (SELECT COUNT(*) FROM certificate_order_requests WHERE CERTIFICATE_REQUEST_MASTER_ID=A.CERTIFICATE_REQUEST_MASTER_ID AND DELETE_FLAG=0) AS TOTAL_REQ, B.DISPATCH_ID,B.RECEIPTNO,B.PREVIEW_SMS,B.DISPATCH_STATUS,B.DISPATCH_DATE, B.RECEIVED_DATE FROM certificate_order_requests_master A LEFT JOIN postal_dispatch B ON A.CERTIFICATE_REQUEST_MASTER_ID= B.CERTIFICATE_REQUEST_MASTER_ID WHERE A.DELETE_FLAG=0 AND A.INSTITUTE_ID=$institute_id ORDER BY A.CREATED_ON DESC";
								$res1 = $db->execQuery($sql);

								if ($res1 != '') {
									$sr = 1;

									$maintbl = '
				            <div class="table-responsive">
				            <table class="table table-bordered table-hover table-striped">
							<tr>
								<th></th>
								<th>#</th>
								<th>Institute Code</th>
								<th>Institute Name</th>
								<th>Total Exam Fees</th>
								<th>Consignment No.</th>
								<!--<th>Created Date</th>-->
								<th>Dispatch Date</th>
								<th>Received Date</th>								
								<th>Parcel Status</th>
							</tr>
							';
									while ($data = $res1->fetch_assoc()) {
										extract($data);
										//print_r($data);


										$DISPATCH_DATE = ($DISPATCH_DATE != '') ? date('d-m-Y', strtotime($DISPATCH_DATE)) : '';

										$RECEIVED_DATE = ($RECEIVED_DATE != '') ? date('d-m-Y', strtotime($RECEIVED_DATE)) : '';
										if ($TOTAL_REQ > 0) {
											$addpayment  = '<a href="page.php?page=add-examfees-payment&reqid=' . $CERTIFICATE_REQUEST_MASTER_ID . '">Add Payment</a>';
											if ($db->permission('delete_certificate'))

												$inst_mobile = $db->get_user_mobile($INSTITUTE_ID, 2);

											$action1 = "<a href='javascript:void(0)' class='btn btn-primary send-parcel-details' title='View Parcel Status' data-toggle='modal' data-target='.cert_parcel-details' data-receipt='$RECEIPTNO' data-smsmessage='$PREVIEW_SMS' data-receiveddate ='$RECEIVED_DATE' data-status='$DISPATCH_STATUS' data-dispatchid='$DISPATCH_ID' ><i class=' fa fa-envelope'> View</i></a>";

											$parcel_status = "";
											$received = "";

											if ($DISPATCH_STATUS == 1) {
												$parcel_status = "<a class='btn btn-primary'> Dispatch From DITRP </a>";
											} elseif ($DISPATCH_STATUS == 2) {
												$parcel_status = "<a class='btn btn-danger'> Received</a>";
											}
											/*if($DISPATCH_STATUS!=2)	{
						$received ="<a class='btn btn-success' href='javascript:void(0)' onclick='receivedParcelStatus($DISPATCH_ID)' title='Change Status To Received'>Not Received</a>";
					}
						*/
											if ($DISPATCH_STATUS != 2) {
												$received = "<a class='btn btn-success' href='javascript:void(0)' title='Not Received'>Not Received</a>";
											}

											$srno = 1;
											$maintbl .= '
								<tr id="req-' . $CERTIFICATE_REQUEST_MASTER_ID . '">
									<td><a href="javascript:void(0)" onclick="toggleRow(' . $sr . ')" class="btn btn-xs btn-primary"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
									</a><a href="javascript:void(0)" onclick="toggleRow(' . $sr . ')" class="label label-default pull-right">' . $TOTAL_REQ . '</a></td>
									<td>' . $sr . '</td>
									<td>' . $INSTITUTE_CODE . '</td>
									<td>' . $INSTITUTE_NAME . '</td>									
									<td>' . $TOTAL_EXAM_FEES . '</td>
									<td>' . $RECEIPTNO . '</td>
									<!-- <td>' . $CREATED_DATE . '</td> -->
									<td>' . $DISPATCH_DATE . '</td>
									<td>' . $RECEIVED_DATE . '</td>	
									<td>' . $action1 . ' ' . $received . ' ' . $parcel_status . '</tr>								
								';

											$condition = " AND A.CERTIFICATE_REQUEST_MASTER_ID=$CERTIFICATE_REQUEST_MASTER_ID";
											if ($requeststatus != '')
												$condition .= " AND A.REQUEST_STATUS=$requeststatus ";
											if ($course != '')
												$condition .= " AND A.AICPE_COURSE_ID=$course ";

											$res 	= $exam->list_order_certificates_requests('', $studid, $INSTITUTE_ID, $condition);
											if ($res != '') {
												$subtable = '
								<tr style="display:none" id="row-' . $sr . '"><td colspan="9">						
								<table class="table table-bordered">
								<tr class="success">							
								<th>#</th>
								<th>Photo</th>
								<th>Student</th>
								<th>Course</th>				
								<th>Exam Fees</th>				
								<th>Marks</th> 
								<th>Result</th>	
								<th>Date</th>
								<th>Admission Date</th>
							';
												while ($data = $res->fetch_assoc()) {
													extract($data);
													//print_r($data);
													$PHOTO = '../uploads/default_user.png';
													if ($STUDENT_PHOTO != '')
														$PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;
													$EXAM_TYPE_NAME = !empty($EXAM_TYPE_NAME) ? $EXAM_TYPE_NAME : '-';
													$GRADE = !empty($GRADE) ? $GRADE : '-';
													$action = '';
													//check the certificate is printed or not
													$check_print = $access->list_order_printed_certificates('', $CERTIFICATE_REQUEST_ID, ' ORDER BY CERTIFICATE_DETAILS_ID DESC LIMIT 0,1');

													$stud_admission_date = ($stud_admission_date != '') ? date('d-m-Y', strtotime($stud_admission_date)) : '';
													if ($db->permission('delete_certificate'))
														$action .= "<a href='javascript:void(0)' onclick='deleteCertificateRequest($CERTIFICATE_REQUEST_ID)' id='result$CERTIFICATE_REQUEST_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";


													$subtable .= "<tr id='irow-$CERTIFICATE_REQUEST_ID'>								
									<td>$srno</td>
									<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>							
									<td>$STUDENT_NAME</td>
									<td>$EXAM_TITLE</td>							
									<td>$EXAM_FEES</td>	
									<td>$MARKS_PER  % </td>
									<td>$RESULT_STATUS </td> 											
									<td>$CREATED_DATE</td>
									<td>$stud_admission_date</td>
								
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
 <div class="modal fade cert_parcel-details" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
 	<img src="resources/dist/img/loader.gif" class="loader-mg-modal" />
 	<div class="modal-dialog modal-md" role="document">
 		<div class="modal-content">

 			<div class="box box-primary modal-body">
 				<div class="">
 					<div class="box-header with-border">
 						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 						<h3 class="box-title">Parcel Status Form</h3>
 					</div>
 					<!-- /.box-header -->
 					<form id="receive_parcel_status" method="post" class="form-horizontal">
 						<input type="hidden" name="dispatchid" id="dispatchid" value="" />
 						<input type="hidden" name="action" id="action" value="receive_parcel_status_form" />
 						<div class="box-body">
 							<div class="form-group">
 								<label for="reciept_no" class="col-sm-3 control-label">Consignment No.</label>
 								<div class="col-sm-9">
 									<input class="form-control" type="text" placeholder="Consignment Number" name="reciept_no" id="reciept_no" required="required" />
 								</div>
 							</div>
 							<div class="form-group">
 								<label for="message" class="col-sm-3 control-label">Preview SMS</label>
 								<div class="col-sm-9">
 									<textarea class="form-control" name="sms_message" style="height: 150px" readonly id="sms_message">
								</textarea>
 								</div>
 							</div>
 							<div class="form-group">
 								<label for="reciept_no" class="col-sm-5 control-label">Select Parcel Received Date</label>
 								<div class="col-sm-4">
 									<input class="form-control receiveddate datepicker" type="text" name="receiveddate" id="receiveddate" required="required" value="" />
 								</div>
 							</div>
 							<div class="form-group">
 								<label for="status" class="col-sm-5 control-label">Parcel Status</label>
 								<div class="col-sm-4">
 									<select class="form-control" name="status" id="status">
 										<option value="">
 											--Select--
 										</option>
 										<option value="2">
 											Received
 										</option>
 										<option value="1">
 											Delivered
 										</option>
 									</select>
 								</div>
 							</div>
 							<div class="form-group" id="msg-error">
 								<p class="help-block"></p>
 							</div>
 						</div>

 						<!-- /.box-body -->
 						<div class="box-footer">
 							<div class="pull-right">
 								<button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
 								<button type="submit" name="send" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Save</button>
 							</div>
 						</div>
 					</form>
 					<!-- /.box-footer -->
 				</div>
 			</div>
 		</div>
 	</div>
 </div>