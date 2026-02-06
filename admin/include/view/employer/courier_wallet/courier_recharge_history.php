<?php

$search 	= isset($_POST['search']) ? $_POST['search'] : '';

$wallet_id 	= isset($_REQUEST['wallet']) ? $_REQUEST['wallet'] : '';

$user_id 	= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

$user_role 	= isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

$datefrom 	= isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : '';

$dateto 	= isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : '';

$paymentmode1 = isset($_REQUEST['paymentmode1']) ? $_REQUEST['paymentmode1'] : '';

$cond = '';


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
									<input type="date" class="form-control" name="datefrom" id="dob" value="<?= $datefrom ?>" />
								</div>
								<div class="form-group col-sm-2">
									<label>Date To</label>
									<input type="date" class="form-control" name="dateto" id="doj" value="<?= $dateto ?>" />
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
									<a class="form-control btn btn-sm btn-warning" onclick="pageLoaderOverlay('show'); location.assign('rechargeHistoryCourier')">Clear</a>
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

										<th>Mode</th>

										<th>Status</th>

										<th>Details</th>

										<th>Transaction Type</th>

										<th>Bonus (Yes/No)</th>

										<th>Trasaction By</th>

										<th>Recharge Date</th>

										<th>Amount</th>

										<th>GST</th>

										<th>Total Amount</th>

										<th>View Bill</th>
									</tr>
								</thead>
								<tbody>
									<?php
									include('include/classes/admin.class.php');
									$admin = new admin();

									include('include/classes/exam.class.php');
									$exam = new exam();

									include('include/classes/student.class.php');
									$student = new student();

									include('include/classes/exammultisub.class.php');
									$exammultisub = new exammultisub();

									$history = $admin->get_courier_recharge_history($paymentmode1, $wallet_id, $user_id, $user_role, $cond);
									//echo $paymentmode1."/".$wallet_id."/".$user_id."/".$user_role."/".$cond;
									$created_on = array_column($history, 'CREATED_ON');
									array_multisort($created_on, SORT_DESC, $history);
									//arsort($history);


									$BONUS_STAUS = '';
									$GST = '';
									$TOTAL_AMOUNT = '';

									$action  = '';

									//print_r($history); 

									$walletres = $access->get_courier_wallet('', $user_id, $user_role, '');

									//print_r($walletres); exit();

									if (!empty($history)) {
										$sr = 1;

										foreach ($history as $trans => $transArr) {
											if (is_array($transArr) && !empty($transArr)) {
												extract($transArr);
												//print_r($transArr);

												$docData1 = $student->payment_student_details($PAYMENT_ID, false);
												//print_r($docData1);
												$sr1 = 0;
												$tbl1 = '';
												if (!empty($docData1)) {
													$tbl1 = '<table class="table table-bordered" style="border:1px solid #000;">';
													$tbl1 .= '<tr style="border:1px solid #000;">
									
									<th style="border:1px solid #000;">Student Name</th>
										<th style="border:1px solid #000;">Exam Fees</th>
									</tr>';
													foreach ($docData1 as $key => $value) {
														extract($value);
														$tbl1 .= '<tr style="border:1px solid #000;">';

														$tbl1 .= '<td style="border:1px solid #000;">';
														$tbl1 .= $STUDENT_FULLNAME;
														$tbl1 .= '</td>';
														$tbl1 .= '<td style="border:1px solid #000;">';
														$tbl1 .= $EXAM_FEES;
														$tbl1 .= '</td>';
														$tbl1 .= '</tr>';
													}
													$tbl1 .= '</table>';
												}


												if ($BONUS_STAUS == '1') {
													$BONUS_STAUS = 'YES';
												}

												if ($BONUS_STAUS == '0') {
													$BONUS_STAUS = 'NO';
												}

												$action = "<a href='page.php?page=View-Bill&id=$PAYMENT_ID' target='_blank' class='btn btn-warning btn1' title='View Bill'><i class='mdi mdi-file-pdf'></i>View</a>";

												echo "<tr>

										<td>$sr</td>

										<td>#$TRANSACTION_NO</td>										

										<td>$PAYMENT_MODE</td>

										<td>$STATUS</td>
									
										<td> $tbl1 </td>
										
										<td>$TRANSACTION_TYPE</td>
										
										<td>$BONUS_STAUS</td>

										<td>$CREATED_BY</td>

										<td>$CREATED_DATE</td>

										<td>$AMOUNT</td>
										
										<td>$GST</td>
										
										<td>$TOTAL_AMOUNT</td>
										
										<td>";
												if ($TRANSACTION_TYPE == 'CREDIT') {
													echo $action;
												}
												"</td>

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