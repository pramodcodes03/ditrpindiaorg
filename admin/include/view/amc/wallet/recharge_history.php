<?php

$search 	= isset($_POST['search']) ? $_POST['search'] : '';

$wallet_id 	= isset($_REQUEST['wallet']) ? $_REQUEST['wallet'] : '';

$user_id 	= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

$user_role 	= isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

$datefrom 	= isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : '';

$dateto 	= isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : '';

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



			</div>

			<div class="box-body">

				<div class="row">

					<div class="col-sm-12">

						<table class="table table-bordered data-tbl">

							<thead>

								<tr>

									<th>#</th>

									<th>Transaction No</th>

									<th>Mode</th>

									<th>Status</th>

									<th>Transaction Type</th>

									<th>Trasaction By</th>

									<th>Recharge Date</th>

									<th>Amount</th>
									<th>COMMISION(15%)</th>

								</tr>

							</thead>

							<tbody>

								<?php

								$history = $access->get_recharge_history('', '', '2', $cond);

								arsort($history);



								$walletres = $access->get_wallet('', '', '');

								if (!empty($history)) {

									$sr = 1;

									foreach ($history as $trans => $transArr) {

										if (is_array($transArr) && !empty($transArr)) {



											extract($transArr);

											if ($USER_ROLE == '2') $USER_ROLE = 'Institute';

											if ($USER_ROLE == '3') $USER_ROLE = 'Employer';
											$COMM = $AMOUNT * 0.15;
											echo "<tr>

										<td>$sr</td>

										<td>#$TRANSACTION_NO</td>										

										<td>$PAYMENT_MODE</td>

										<td>$STATUS</td>

										<td>$TRANSACTION_TYPE</td>

										<td>$CREATED_BY</td>

										<td>$CREATED_DATE</td>

										<td>$AMOUNT</td>
										<td>$COMM</td>

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

				<br><br>







			</div>



			<!-- /.box-footer-->

		</div>

	</section>

</div>

<!--  </body> -->