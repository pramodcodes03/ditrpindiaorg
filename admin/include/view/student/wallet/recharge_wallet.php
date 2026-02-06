<?php

$user_role = isset($_REQUEST['user_role']) ? $_REQUEST['user_role'] : '2';

$user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';



$action = isset($_POST['paymoney']) ? $_POST['paymoney'] : '';

if ($action != '') {

	$result	= $access->recharge_wallet_offline();

	$result = json_decode($result, true);

	$success = isset($result['success']) ? $result['success'] : '';

	$message = $result['message'];

	$errors = isset($result['errors']) ? $result['errors'] : '';



	if ($success == true) {

		$_SESSION['msg'] 		= $message;

		$_SESSION['msg_flag'] 	= $success;

		header('location:page.php?page=Wallet');
	}
}

?>

<div class="content-wrapper">

	<!-- Content Header (Page header) -->

	<section class="content-header">

		<h1>Recharge </h1>

		<ol class="breadcrumb">

			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

			<li><a href="page.php?page=Wallet">Wallet</a></li>

			<li class="active">Recharge</li>

		</ol>

	</section>

	<section class="content">

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



		<div class="row">



			<form class="form-horizontal form-validate" action="" method="post">

				<div class="col-md-12">

					<!-- general form elements -->

					<div class="box box-primary">

						<div class="box-header with-border">

							<h3 class="box-title">Recharge Wallet</h3>

						</div>

						<div class="box-body">

							<div class="form-group <?= (isset($errors['trans_type'])) ? 'has-error' : '' ?>">

								<label for="amount" class="col-sm-4 control-label">Select Transaction type:</label>

								<div class="col-sm-6">

									<?php

									$trans_type = isset($_POST['trans_type']) ? $_POST['trans_type'] : 'CREDIT';

									?>

									<div class="radio">

										<label>

											<input name="trans_type" id="trans_type1" value="CREDIT" type="radio" <?= ($trans_type == 'CREDIT') ? 'checked="checked"' : '' ?>> Add

										</label>

										<label>

											<input name="trans_type" id="trans_type2" value="DEBIT" type="radio" <?= ($trans_type == 'DEBIT') ? 'checked="checked"' : '' ?>> Subtract

										</label>

									</div>

									<span class="help-block"><?= (isset($errors['trans_type'])) ? $errors['trans_type'] : '' ?></span>

								</div>

							</div>

							<div class="form-group <?= (isset($errors['amount'])) ? 'has-error' : '' ?>">

								<label for="amount" class="col-sm-4 control-label">Enter Amount</label>

								<div class="col-sm-6">

									<div class="input-group">

										<span class="input-group-addon"><i class="fa fa-inr"></i></span>

										<input class="form-control" id="amount" name="amount" placeholder="Enter amount" value="<?= isset($_POST['amount']) ? $_POST['amount'] : '' ?>" type="text" autocomplete="off" required>

									</div>

									<span class="help-block"><?= (isset($errors['amount'])) ? $errors['amount'] : '' ?></span>

								</div>

							</div>

							<div class="form-group <?= (isset($errors['user_role'])) ? 'has-error' : '' ?>">

								<label for="amount" class="col-sm-4 control-label">User Role</label>

								<div class="col-sm-6">



									<div class="input-group">

										<span class="input-group-addon"><i class="fa fa-user"></i></span>

										<select class="form-control" name="user_role" onchange="getUserListByRole(this.value)">

											<?php

											echo $db->MenuItemsDropdown('user_role_master', 'USER_ROLE_ID', 'STATUS_NAME', 'USER_ROLE_ID,STATUS_NAME', $user_role, " WHERE USER_ROLE_ID IN(2,3)");

											?>

										</select>

									</div>

									<span class="help-block"><?= (isset($errors['user_role'])) ? $errors['user_role'] : '' ?></span>



								</div>

							</div>

							<div class="form-group <?= (isset($errors['user_id'])) ? 'has-error' : '' ?>">

								<label for="amount" class="col-sm-4 control-label">User Name</label>

								<div class="col-sm-6">

									<div class="input-group">

										<span class="input-group-addon"><i class="fa fa-user"></i></span>

										<select class="form-control select2" name="user_id" id="userlist" onchange="getUserDetails(this.value)">

											<?php

											if ($user_role == 2)

												echo $db->MenuItemsDropdown('institute_details', 'INSTITUTE_ID', 'INSTITUTE_NAME', 'INSTITUTE_ID,CONCAT(INSTITUTE_NAME," - ",INSTITUTE_CODE) AS INSTITUTE_NAME', $user_id, " WHERE DELETE_FLAG=0 AND ACTIVE=1");

											else if ($user_role == 3)

												echo $db->MenuItemsDropdown('employer_details', 'EMPLOYER_ID', 'EMPLOYER_COMPANY_NAME', 'EMPLOYER_ID,EMPLOYER_COMPANY_NAME', $user_id, " WHERE DELETE_FLAG=0 AND ACTIVE=1");

											?>

										</select>

									</div>

									<span class="help-block"><?= (isset($errors['user_id'])) ? $errors['user_id'] : '' ?></span>



								</div>

							</div>

							<div class="form-group <?= (isset($errors['pay_mode'])) ? 'has-error' : '' ?>">

								<label for="pay_mode" class="col-sm-4 control-label">Select Payment Mode</label>

								<div class="col-sm-6">

									<select class="form-control" name="pay_mode" id="pay_mode">

										<?php

										echo $db->MenuItemsDropdown('payment_modes_master', 'PAYMENT_MODE_ID', 'PAYMENT_MODE', 'PAYMENT_MODE_ID,PAYMENT_MODE', $user_id, " ");

										?>

									</select>

								</div>

							</div>

							<div class="form-group <?= (isset($errors['pay_remark'])) ? 'has-error' : '' ?>">

								<label for="pay_remark" class="col-sm-4 control-label">Payment Remarks</label>

								<div class="col-sm-6">

									<textarea class="form-control" id="pay_remark" name="pay_remark" placeholder="Enter cheque / demand bank details"><?= isset($_POST['pay_remark']) ? $_POST['pay_remark'] : '' ?></textarea>

								</div>

							</div>

							<div class="form-group">

								<div for="pay_mode" class="col-sm-4"></div>

								<div for="pay_mode" class="col-sm-4">

									<a href="javascript:void(0)" onclick="$('#payment_details').toggle()">Add Payment Details</a>

								</div>



							</div>

							<div id="payment_details" style="display:none;">

								<div class="form-group <?= (isset($errors['cheque_no'])) ? 'has-error' : '' ?>">

									<label for="cheque_no" class="col-sm-4 control-label">Cheque / Demand Draft No.</label>

									<div class="col-sm-6">

										<input class="form-control" id="cheque_no" name="cheque_no" placeholder="Enter cheque / demand draft number" value="<?= isset($_POST['cheque_no']) ? $_POST['cheque_no'] : '' ?>" type="text" autocomplete="off">

									</div>

								</div>

								<div class="form-group <?= (isset($errors['cheque_date'])) ? 'has-error' : '' ?>">

									<label for="cheque_date" class="col-sm-4 control-label">Cheque / Demand Draft Date</label>

									<div class="col-sm-6">

										<input class="form-control" id="dob" name="cheque_date" placeholder="Enter cheque / demand draft number" value="<?= isset($_POST['cheque_date']) ? $_POST['cheque_date'] : '' ?>" type="text" autocomplete="off">

									</div>

								</div>

								<div class="form-group <?= (isset($errors['cheque_bank'])) ? 'has-error' : '' ?>">

									<label for="cheque_bank" class="col-sm-4 control-label">Cheque / Demand Draft Bank Details</label>

									<div class="col-sm-6">

										<textarea class="form-control" id="cheque_bank" name="cheque_bank" placeholder="Enter cheque / demand bank details"><?= isset($_POST['cheque_bank']) ? $_POST['cheque_bank'] : '' ?></textarea>

									</div>

								</div>



							</div>

							<div class="form-group <?= (isset($errors['bonus_type'])) ? 'has-error' : '' ?>">

								<label for="amount" class="col-sm-4 control-label">Promotional Bonus</label>

								<div class="col-sm-6">

									<?php

									$bonus_type = isset($_POST['bonus_type']) ? $_POST['bonus_type'] : '0';

									?>

									<div class="radio">

										<label>

											<input name="bonus_type" id="bonus_type1" value="1" type="radio" <?= ($bonus_type == '1') ? 'checked="checked"' : '' ?>> YES

										</label>

										<label>

											<input name="bonus_type" id="bonus_type2" value="0" type="radio" <?= ($bonus_type == '0') ? 'checked="checked"' : '' ?>> NO

										</label>

									</div>

									<span class="help-block"><?= (isset($errors['bonus_type'])) ? $errors['bonus_type'] : '' ?></span>

								</div>

							</div>

						</div>

						<!-- /.box-body -->

						<div class="box-footer text-center">

							<a href="page.php?page=Wallet" class="btn bg-orange btn-flat margin">Cancel</a>

							<input type="submit" value="Make Payment" name="paymoney" class="btn bg-purple btn-flat margin" />



						</div>

					</div>

				</div>



			</form>

		</div>

	</section>

</div>