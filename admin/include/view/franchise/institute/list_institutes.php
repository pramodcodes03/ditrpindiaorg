<?php

/* display exam results details */

$atccode 	= $db->test(isset($_REQUEST['atccode']) ? $_REQUEST['atccode'] : '');
$institute = $db->test(isset($_REQUEST['institute']) ? $_REQUEST['institute'] : '');
$mobile	= $db->test(isset($_REQUEST['mobile']) ? $_REQUEST['mobile'] : '');
$state 	= $db->test(isset($_REQUEST['state']) ? $_REQUEST['state'] : '');

$cond = '';
if ($institute != '') $cond .= " AND A.INSTITUTE_ID='$institute'";
if ($atccode != '') $cond  .= " AND A.INSTITUTE_ID='$atccode'";
if ($mobile != '') $cond .= " AND A.INSTITUTE_ID='$mobile'";
if ($state != '') $cond .= " AND A.STATE='$state'";


?>


<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">List Franchise
					<a href="page.php?page=addFranchise" class="btn btn-primary" style="float: right">Add Franchise</a>
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
								<h4><i class="icon mdi mdi-check"></i> <?= ($msg_flag == true) ? 'Success' : 'Error' ?>:</h4>
								<?= ($message != '') ? $message : 'Sorry! Something went wrong!'; ?>
							</div>
						</div>
					</div>
				<?php
					unset($_SESSION['msg']);
					unset($_SESSION['msg_flag']);
				}
				?>
				<div class="table-responsive pt-3">
					<table id="order-listing" class="table">
						<thead>
							<tr>
								<th>Sr.</th>
								<th>Approved</th>
								<th>Action</th>
								<th>Logo</th>
								<th>Institute Name</th>
								<th>No Of Student</th>
								<th>State</th>
								<th>City</th>
								<th>ATC Code</th>
								<th>Username</th>

								<th>Mobile</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
							//$cond = " ";
							$verified = isset($_REQUEST['verified']) ? $_REQUEST['verified'] : '';
							if ($verified != '') {
								$cond .= " AND A.VERIFIED='$verified'";
							}
							//$cond .=  " ORDER BY A.CREATED_ON DESC";

							include_once('include/classes/institute.class.php');
							include_once('include/classes/student.class.php');
							$institute = new institute();
							$student = new student();

							/* Pagination Code */
							$rec_limit = 50;

							$sql = "SELECT COUNT(institute_details.INSTITUTE_ID) as total FROM institute_details WHERE institute_details.DELETE_FLAG=0";
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
							$pageUrl = 'list-institutes';

							$cond .= " AND B.USER_ROLE=8";

							$res = $institute->list_institute('', $cond);
							if ($res != '') {
								$srno = 1;

								while ($data = $res->fetch_assoc()) {
									$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
									$USER_LOGIN_ID 		= $data['USER_LOGIN_ID'];
									$REG_DATE 			= $data['REG_DATE'];
									$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];
									$INSTITUTE_NAME 	= $data['INSTITUTE_NAME'];
									$INSTITUTE_OWNER_NAME = $data['INSTITUTE_OWNER_NAME'];
									$EMAIL 				= $data['EMAIL'];
									$MOBILE 			= $data['MOBILE'];
									$CREDIT 			= $data['CREDIT'];
									$CREDIT_BALANCE 	= $data['CREDIT_BALANCE'];
									$USER_NAME 			= $data['USER_NAME'];
									$ACTIVE 			= $data['ACTIVE'];

									$AMC_CODE 			= $data['AMC_CODE'];

									$STATE_NAME 			= $data['STATE_NAME'];
									$CITY 			= $data['CITY'];
									$VERIFIED 			= $data['VERIFIED'];
									$verify_flag 			= $data['VERIFIED'];

									$GSTNO 			    = $data['GSTNO'];
									$PRIMEMEMBER 		= $data['PRIMEMEMBER'];
									$color = '';
									if ($PRIMEMEMBER = 1 && $PRIMEMEMBER != NULL && $PRIMEMEMBER != 0) {
										$color = "style='color:red; font-weight:bold;'";
									}

									$count = '';
									$cond21  = " AND INSTITUTE_ID = $INSTITUTE_ID";
									$count = $student->get_admission_count($cond21);

									// $res123 = $student->list_student('',$INSTITUTE_ID,''); 
									// $count = ($res123!='')?$res123->num_rows:0;

									if ($db->permission('update_institute')) {
										if ($ACTIVE == 1)
											$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstStatus(' . $INSTITUTE_ID . ',0)"><i class="mdi mdi-check"></i> YES</a>';
										elseif ($ACTIVE == 0)
											$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstStatus(' . $INSTITUTE_ID . ',1)"><i class="mdi mdi-close"></i> NO</a>';
									} else {
										if ($ACTIVE == 1)
											$ACTIVE = '<span style="color:#3c763d"><i class="mdi mdi-check"></i> YES</span>';
										elseif ($ACTIVE == 0)
											$ACTIVE = '<span style="color:#f00"><i class="mdi mdi-close"></i> NO</span>';
									}

									$performanceCert = "";

									// $performanceCert .= "<a href='print-performance-cert&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Performance Certificate' target='_blank'><i class='fa fa-trophy'></i></a>";

									// $performanceCert .= "<a href='print-performance-cert-cover&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Performance Certificate' target='_blank'><i class='fa fa-file-image-o'></i></a>";

									$printCert = "";
									if ($db->permission('verify_institute')) {
										if ($VERIFIED == 1) {
											$VERIFIED = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstVerify(' . $INSTITUTE_ID . ',0)"><i class="mdi mdi-check"></i> YES</a> ';

											$printCert = "<a href='page.php?page=printFranchiseCertificate&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Certificate' target='_blank'><i class=' mdi mdi-certificate'></i></a>";

											$printCert .= "<a href='page.php?page=printFranchiseAddress&inst[]=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Print Address' target='_blank'><i class=' mdi mdi-message-text'></i></a>";
										} elseif ($VERIFIED == 0)
											$VERIFIED = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstVerify(' . $INSTITUTE_ID . ',1)"><i class="mdi mdi-close"></i> NO</a> ';
									} else {
										if ($VERIFIED == 1)
											$VERIFIED = '<span style="color:#3c763d"><i class="mdi mdi-check"></i> YES</span>';
										elseif ($VERIFIED == 0)
											$VERIFIED = '<span style="color:#f00"><i class="mdi mdi-close"></i> NO</span>';
									}

									$changepassFunParams = "'$USER_LOGIN_ID', '$EMAIL'";
									$changepassFun = 'onclick="changePass(' . $changepassFunParams . ')"';
									/*$PHOTO = '../uploads/default_user.png';*/
									$logo = '../uploads/default_user.png';
									//if($STAFF_PHOTO!='')
									//	$PHOTO = INSTITUTE_DOCUMENTS_PATH.'/'.$INSTITUTE_ID.'/thumb/'.$STAFF_PHOTO;

									$editLink = "";
									if ($db->permission('update_institute'))
										$editLink .= "<a href='page.php?page=updateFranchise&id=$INSTITUTE_ID' class='btn btn-primary table-btn' title='Edit'><i class=' mdi mdi-grease-pencil'></i></a>";

									if ($db->permission('delete_institute'))
										$deleteLink = " <a href='javascript:void(0)' class='btn btn-danger table-btn' title='Delete' onclick='deleteInstitute($INSTITUTE_ID)'><i class='  mdi mdi-delete'></i></a>";

									/*
					$editLink .= "<a href='javascript:void(0)' class='btn btn-primary table-btn send-email-inst' title='Send Email' data-toggle='modal' data-target='.bs-example-modal-md' data-email='$EMAIL' data-id='$INSTITUTE_ID' data-name='$INSTITUTE_NAME'><i class=' mdi mdi-message-text'></i></a>";					
					$editLink .="<a href='javascript:void(0)' class='btn btn-primary table-btn' title='Change Password' $changepassFun><i class=' fa fa-key'></i></a>
					";
					*/

									//    $editLink .="<a href='javascript:void(0)' class='btn btn-primary table-btn' title='Password Recovery SMS' onclick='forgotPassSMS($INSTITUTE_ID,2)' ><i class='mdi mdi-message-text'></i></a>
									// 	";	

									// if($verify_flag==0)
									// $editLink .="<a href='javascript:void(0)' class='btn btn-primary table-btn' title='Documents Reminder SMS' onclick='uploadDocsSMS($INSTITUTE_ID,2)' ><i class=' fa fa-bell'></i></a>
									// ";

									$editLink .= $printCert;

									$loginBtn = "<a href='javascript:void(0)' class='btn btn-primary btn-xs' title='LOGIN' onclick=\"loginToInst($params)\"><i class=' fa fa-sign-in'></i>Login</a>";
									//$editLink .=$loginBtn;

									$logo = $institute->get_institute_docs_single($INSTITUTE_ID, 'logo');


									$editLink .= $performanceCert;

									echo " <tr id='row-$INSTITUTE_ID' $color>
							<td>$srno</td>
							<td id='verify-$INSTITUTE_ID'>$VERIFIED</td>
							<td>$editLink</td>
                            <td>$logo <p>$loginBtn</p></td>
							<td>$INSTITUTE_NAME</td>
							<td>$count</td>
							<td>$STATE_NAME</td>
							<td>$CITY</td>
							<td>$INSTITUTE_CODE</td>
							<td>$USER_NAME</td>
							<!-- <td>$EMAIL</td> -->
							<td>$MOBILE</td>
                            <td id='status-$INSTITUTE_ID'>$ACTIVE $deleteLink</td>
                           </tr>";
									$srno++;
								}
							}

							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>