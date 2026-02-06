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

	$cond = " AND A.CREATED_ON BETWEEN '$datefrom1' AND '$dateto1'";
}
?>
<div class="content-wrapper">

	<!-- Content Header (Page header) -->

	<section class="content-header">

		<h1>Wallet Recharge History</h1>

		<ol class="breadcrumb">

			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

			<li class="active">Wallet Recharge History</li>

		</ol>

	</section>

	<section class="content">

		<div class="row">

			<div class="col-xs-12">

				<div class="box box-primary">

					<!-- /.box-header -->

					<div class="box-header">



						<h3 class="box-title">Search By Filters</h3>

					</div>

					<div class="box-body">

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

								<a class="form-control btn btn-sm btn-warning" onclick="pageLoaderOverlay('show'); location.assign('recharge-history')">Clear</a>

							</div>



						</form>

					</div>

				</div>

			</div>

		</div>

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



		<div class="box">

			<div class="box-header with-border">

				<h3 class="box-title">Wallet Details</h3>

				<p style="background-color: yellow;padding: 10px;color: #000;
    font-size: 18px;
    font-weight: 900;">NOTICE:-THIS AMOUNT WILL BE ONLY USED FOR DITRP INDIA CERTIFICATIONS
					THIS AMOUNT CANNOT BE USED FOR ANY OTHER SERVICES. </p>

			</div>

			<div class="box-body">

				<div class="row">

					<div class="col-sm-12">

						<div class="table-responsive">

							<table class="table table-bordered data-tbl">

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

									$history = $admin->get_recharge_history($paymentmode1, $wallet_id, $user_id, $user_role, $cond);

									arsort($history);


									$BONUS_STAUS = '';
									$GST = '';
									$TOTAL_AMOUNT = '';

									$action  = '';

									//print_r($history); 

									$walletres = $access->get_wallet('', '', '');

									//print_r($walletres); exit();

									if (!empty($history)) {

										$sr = 1;

										foreach ($history as $trans => $transArr) {
											if (is_array($transArr) && !empty($transArr)) {
												extract($transArr);

												//print_r($transArr);
												$sr1 = 0;
												$tbl1 = '';
												if ($PAYMENT_MODE != 'ONLINE') {
													$docData1 = $student->payment_student_details($PAYMENT_ID, false);
													if (!empty($docData1)) {
														$tbl1 = '<table class="table table-bordered" style="border:1px solid #000;">';
														$tbl1 .= '<tr style="border:1px solid #000;">
        			                     
        			                        <th style="border:1px solid #000;">Student Name</th>
        			                         <th style="border:1px solid #000;">Exam Fees</th>
        			                        </tr>';
														foreach ($docData1 as $key => $value) {
															extract($value);
															//print_r($value);
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
												}

												if ($USER_ROLE == '2') $USER_ROLE = 'Institute';

												if ($USER_ROLE == '3') $USER_ROLE = 'Employer';

												if ($BONUS_STAUS == '1') {
													$BONUS_STAUS = 'YES';
												}

												if ($BONUS_STAUS == '0') {
													$BONUS_STAUS = 'NO';
												}

												//code for list of student in order by group




												$action = "<a href='page.php?page=View-Bill&id=$PAYMENT_ID' target='_blank' class='btn' title='View Marksheet'><i class='fa fa-file-text-o'></i></a>";





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
												if ($TRANSACTION_TYPE == 'CREDIT' && $STATUS == 'success') {
													echo $action;
												}
												"</td>
        
        									</tr>";
											}

											$sr++;
										}
									}

									?>
								<tbody>

							</table>
						</div>
					</div>
				</div>
				<br><br>
			</div>
			<!-- /.box-footer-->
		</div>
	</section>
</div>
<!--  </body> -->