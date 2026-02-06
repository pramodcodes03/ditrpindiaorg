<?php
$search 	= isset($_POST['search']) ? $_POST['search'] : '';
$wallet_id 	= isset($_REQUEST['wallet']) ? $_REQUEST['wallet'] : '';
$user_id 	= isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
$user_role 	= isset($_REQUEST['user_role']) ? $_REQUEST['user_role'] : '';
$datefrom 	= isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : '';
$dateto 	= isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : '';
$paymentmode1 = isset($_REQUEST['paymentmode1']) ? $_REQUEST['paymentmode1'] : '';

$cond = '';

$trantype = isset($_REQUEST['trantype']) ? $_REQUEST['trantype'] : '';

if ($trantype != '') {
	$cond = " AND TRANSACTION_TYPE='$trantype'";
}


if ($datefrom != '' && $dateto != '') {
	$datefrom1 = date('Y-m-d', strtotime($datefrom));
	$dateto1 = date('Y-m-d', strtotime($dateto));
	$dateto1 = $dateto1 . " 23:59:59";
	$cond = " AND A.CREATED_ON BETWEEN '$datefrom1' AND '$dateto1'";
}

?>
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Courier Wallet Recharge History</h4>
					<form class="forms-sample" action="" method="post" enctype="multipart/form-data">
						<?php
						if (isset($success)) {
						?>
							<div class="row">
								<div class="col-sm-12">
									<div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
										<h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
										<?= isset($message) ? $message : 'Please correct the errors.'; ?>
										<?php
										if (!empty($errors)) {
											echo '<ul>';
											foreach ($errors as $err) {
												echo '<li>' . $err . '</li>';
											}
											echo '</ul>';
										}
										?>
									</div>
								</div>
							</div>
						<?php
						}
						?>
						<div class="row col-md-12">
							<form action="" method="post" onsubmit="pageLoaderOverlay('show')">
								<input type="hidden" name="page" value="recharge-history" />
								<div class="form-group col-sm-2">
									<label>Date From</label>
									<input type="text" class="form-control" name="datefrom" id="dob" value="<?= $datefrom ?>" />
								</div>
								<div class="form-group col-sm-2">
									<label>Date To</label>
									<input type="text" class="form-control" name="dateto" id="doj" value="<?= $dateto ?>" />
								</div>
								<div class="form-group col-sm-2">
									<label>Role</label>
									<select class="form-control" name="user_role" onchange="getUserListByRole(this.value)">
										<?php
										echo $db->MenuItemsDropdown('user_role_master', 'USER_ROLE_ID', 'STATUS_NAME', 'USER_ROLE_ID,STATUS_NAME', $user_role, " WHERE USER_ROLE_ID IN(8)");
										?>
									</select>
								</div>

								<div class="form-group col-sm-4">
									<label>User</label>
									<select class="form-control select2" name="user_id" id="userlist" onchange="getUserDetails(this.value)">
										<?php
										if ($user_role == 8)
											echo $db->MenuItemsDropdown('institute_details', 'INSTITUTE_ID', 'INSTITUTE_NAME', 'INSTITUTE_ID,CONCAT(INSTITUTE_NAME," - ",INSTITUTE_CODE) AS INSTITUTE_NAME', $user_id, " WHERE DELETE_FLAG=0 AND ACTIVE=1");
										else if ($user_role == 3)
											echo $db->MenuItemsDropdown('employer_details', 'EMPLOYER_ID', 'EMPLOYER_COMPANY_NAME', 'EMPLOYER_ID,EMPLOYER_COMPANY_NAME', $user_id, " WHERE DELETE_FLAG=0 AND ACTIVE=1");
										else if ($user_role == 4)
											echo $db->MenuItemsDropdown('student_details', 'STUDENT_ID', 'get_student_name(STUDENT_ID)', 'STUDENT_ID,get_student_name(STUDENT_ID)', $user_id, " WHERE DELETE_FLAG=0 AND ACTIVE=1");
										?>
									</select>
								</div>
								<div class="form-group col-sm-2">
									<label>Payment Mode</label>
									<select class="form-control" name="paymentmode1">
										<option value="">--select--</option>
										<option value="ONLINE" <?= ($paymentmode1 == 'ONLINE') ? 'selected="selected"' : '' ?>>ONLINE</option>
										<option value="OFFLINE" <?= ($paymentmode1 == 'OFFLINE') ? 'selected="selected"' : '' ?>>OFFLINE</option>
									</select>
								</div>
								<div class="form-group col-sm-1">
									<label> &nbsp;</label>
									<input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />
								</div>
								<div class="form-group col-sm-1">
									<label> &nbsp;</label>
									<a class="form-control btn btn-sm btn-warning" onclick="pageLoaderOverlay('show'); location.assign('courierWalletHistory')">Clear</a>
								</div>
								<!--
				   <div class="form-group col-sm-1">
				   <label> &nbsp;</label>				 
				   <a href="page.php?page=recharge-wallet" class="btn bg-maroon btn-flat pull-left">Recharge</a>
				   </div>
				   -->
							</form>
						</div>

						<div class="table-responsive pt-3">
							<table id="order-listing" class="table">
								<thead>
									<tr>
										<th>#</th>
										<th>Transaction No</th>
										<th>ATC Code</th>
										<th>Name</th>
										<th>GST Number</th>
										<th>State</th>
										<!--<th>Role</th>-->
										<th>Mode</th>
										<th>Status</th>

										<th>Transaction Type</th>
										<th>Bonus (Yes/No)</th>
										<th>Recharge Date</th>
										<th>Amount</th>
										<th>GST</th>
										<th>Total Amount</th>

										<th>Recharge By</th>
										<th>Recharge Lead</th>
										<!-- <th>Action</th> -->
									</tr>
								</thead>
								<tbody>
									<?php
									include('include/classes/admin.class.php');
									$admin = new admin();

									/*include('include/classes/student.class.php');
					$student = new student();
*/
									$BONUS_STAUS = '';
									$history = $admin->get_courier_recharge_history($paymentmode1, $wallet_id, $user_id, '', $cond);
									//echo $paymentmode1."/".$wallet_id."/".$user_id."/".$user_role."/".$cond;
									$created_on = array_column($history, 'CREATED_ON');
									array_multisort($created_on, SORT_DESC, $history);

									//arsort($history);
									//print_r($history);
									$walletres = $access->get_courier_wallet('', '', '');

									$GST = "";
									$TOTAL_AMOUNT = "";

									if (!empty($history)) {
										$sr = 1;
										foreach ($history as $trans => $transArr) {
											if (is_array($transArr) && !empty($transArr)) {
												extract($transArr);

												/*	$docData1 = $student->payment_student_details($PAYMENT_ID, false);  
									$sr1=0;
									$tbl1='';
									if(!empty($docData1))
									{
									$tbl1 = '<table class="table table-bordered">';
									$tbl1 .= '<tr>
									<th>Sr.No</th>
									<th>Student Name</th>
									</tr>';
									foreach($docData1 as $key=>$value)
									{
										extract($value);
										$tbl1 .='<tr>';
										$tbl1 .= '<td>'.++$sr1.'</td>';
										$tbl1 .='<td>';
										$tbl1 .= $STUDENT_FULLNAME;
										$tbl1 .='</td>';
										$tbl1 .='</tr>';
									}
									$tbl1 .='</table>';
									}
*/
												if ($USER_ROLE == '2') $USER_ROLE = 'Institute';
												if ($USER_ROLE == '3') $USER_ROLE = 'Employer';
												if ($BONUS_STAUS == '1') {
													$BONUS_STAUS = 'YES';
												}
												if ($BONUS_STAUS == '0') {
													$BONUS_STAUS = 'NO';
												}
												$inst_code = $db->get_institute_code($USER_ID);
												$inst_state_id = $db->get_institute_state($USER_ID);
												$inst_state_name = $db->get_institute_state_name($inst_state_id);

												$gst_no = $db->get_institute_gstnumber($USER_ID);
												echo "<tr>
										<td>$sr</td>
										<td>#$TRANSACTION_NO</td>
										<td>$inst_code</td>
										<td>$USER_FULLNAME</td>
										<td>$gst_no</td>
										<td>$inst_state_name</td>
										<!--<td>$USER_ROLE</td>-->
										<td>$PAYMENT_MODE</td>
										<td>$STATUS</td>
									
										<td>$TRANSACTION_TYPE</td>
										<td>$BONUS_STAUS</td>
										<td>$CREATED_DATE</td>
										<td>$AMOUNT</td>
										<td>$GST</td>
										<td>$TOTAL_AMOUNT</td>
										<td>$RECHARG_BY</td>
										<td>$LEAD_BY</td>
									</tr>";
											}
											$sr++;
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