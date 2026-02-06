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
		header('location:page.php?page=franchiseWallet');
	}
}
?>
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Franchise Recharge</h4>
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
							<div class="form-group col-sm-12 <?= (isset($errors['trans_type'])) ? 'has-error' : '' ?>">
								<label>Select Transaction type:</label>
								<?php $trans_type = isset($_POST['trans_type']) ? $_POST['trans_type'] : 'CREDIT'; ?>
								<label> <input name="trans_type" id="trans_type1" value="CREDIT" type="radio" <?= ($trans_type == 'CREDIT') ? 'checked="checked"' : '' ?>> Add </label>
								<label> <input name="trans_type" id="trans_type2" value="DEBIT" type="radio" <?= ($trans_type == 'DEBIT') ? 'checked="checked"' : '' ?>> Subtract </label>
								<span class="help-block"><?= (isset($errors['trans_type'])) ? $errors['trans_type'] : '' ?></span>
							</div>

							<div class="form-group col-sm-6 <?= (isset($errors['amount'])) ? 'has-error' : '' ?>">
								<label>Enter Amount</label>
								<span class="input-group-addon"><i class="fa fa-inr"></i></span>
								<input class="form-control" id="amount" name="amount" placeholder="Enter amount" value="<?= isset($_POST['amount']) ? $_POST['amount'] : '' ?>" type="text" autocomplete="off" required>
								<span class="help-block"><?= (isset($errors['amount'])) ? $errors['amount'] : '' ?></span>
							</div>

							<div class="form-group  col-sm-6 <?= (isset($errors['user_role'])) ? 'has-error' : '' ?>">
								<label>User Role</label>
								<select class="form-control" name="user_role" onchange="getUserListByRole(this.value)">
									<?php
									echo $db->MenuItemsDropdown('user_role_master', 'USER_ROLE_ID', 'STATUS_NAME', 'USER_ROLE_ID,STATUS_NAME', $user_role, " WHERE USER_ROLE_ID IN(8)");
									?>
								</select>
								<span class="help-block"><?= (isset($errors['user_role'])) ? $errors['user_role'] : '' ?></span>
							</div>


							<div class="form-group col-sm-6 <?= (isset($errors['user_id'])) ? 'has-error' : '' ?>">
								<label>User Name</label>
								<select class="form-control select2" name="user_id" id="userlist" onchange="getUserDetails(this.value)">
									<?php
									if ($user_role == 8)
										echo $db->MenuItemsDropdown('institute_details', 'INSTITUTE_ID', 'INSTITUTE_NAME', 'INSTITUTE_ID,CONCAT(INSTITUTE_NAME," - ",INSTITUTE_CODE) AS INSTITUTE_NAME', $user_id, " WHERE DELETE_FLAG=0 AND ACTIVE=1 AND INSTITUTE_ID !=1");
									else if ($user_role == 3)
										echo $db->MenuItemsDropdown('employer_details', 'EMPLOYER_ID', 'EMPLOYER_COMPANY_NAME', 'EMPLOYER_ID,EMPLOYER_COMPANY_NAME', $user_id, " WHERE DELETE_FLAG=0 AND ACTIVE=1");
									else if ($user_role == 4)
										echo $db->MenuItemsDropdown('student_details', 'STUDENT_ID', 'get_student_name(STUDENT_ID)', 'STUDENT_ID,get_student_name(STUDENT_ID)', $user_id, " WHERE DELETE_FLAG=0 AND ACTIVE=1");
									?>
								</select>
								<span class="help-block"><?= (isset($errors['user_id'])) ? $errors['user_id'] : '' ?></span>
							</div>

							<div class="form-group col-sm-6 <?= (isset($errors['pay_mode'])) ? 'has-error' : '' ?>">
								<label>Select Payment Mode</label>
								<select class="form-control" name="pay_mode" id="pay_mode">
									<?php
									echo $db->MenuItemsDropdown('payment_modes_master', 'PAYMENT_MODE_ID', 'PAYMENT_MODE', 'PAYMENT_MODE_ID,PAYMENT_MODE', $user_id, " WHERE PAYMENT_MODE_ID IN (1,2,4)");
									?>
								</select>
							</div>

							<div class="form-group col-sm-6 <?= (isset($errors['recharge_by'])) ? 'has-error' : '' ?>">
								<label>Recharge By</label>
								<span class="input-group-addon"><i class="fa fa-inr"></i></span>
								<input class="form-control" id="recharge_by" name="recharge_by" placeholder="Recharge By" value="<?= isset($_POST['recharge_by']) ? $_POST['recharge_by'] : '' ?>" type="text" autocomplete="off">
								<span class="help-block"><?= (isset($errors['recharge_by'])) ? $errors['recharge_by'] : '' ?></span>
							</div>
							<div class="form-group col-sm-6 <?= (isset($errors['lead_by'])) ? 'has-error' : '' ?>">
								<label>Lead By</label>
								<span class="input-group-addon"><i class="fa fa-inr"></i></span>
								<input class="form-control" id="lead_by" name="lead_by" placeholder="Lead By" value="<?= isset($_POST['lead_by']) ? $_POST['lead_by'] : '' ?>" type="text" autocomplete="off">
								<span class="help-block"><?= (isset($errors['lead_by'])) ? $errors['lead_by'] : '' ?></span>
							</div>

							<div class="form-group col-sm-6 <?= (isset($errors['password'])) ? 'has-error' : '' ?>">
								<label>OTP</label>
								<span class="input-group-addon"><i class="fa fa-inr"></i></span>
								<input class="form-control" id="password" name="password" placeholder="Master Password" value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>" type="text" autocomplete="off">
								<span class="help-block"><?= (isset($errors['password'])) ? $errors['password'] : '' ?></span>
							</div>


							<div class="form-group col-sm-6  <?= (isset($errors['pay_remark'])) ? 'has-error' : '' ?>">
								<label>Payment Remarks</label>
								<textarea class="form-control" id="pay_remark" name="pay_remark" placeholder="Enter cheque / demand bank details"><?= isset($_POST['pay_remark']) ? $_POST['pay_remark'] : '' ?></textarea>
							</div>

							<div class="form-group col-sm-6">
								<a href="javascript:void(0)" onclick="$('#payment_details').toggle()">Add Payment Details</a>
							</div>

							<div id="payment_details" class="row" style="display:none;">
								<div class="form-group col-sm-6 <?= (isset($errors['cheque_no'])) ? 'has-error' : '' ?>">
									<label>Cheque No.</label>
									<input class="form-control" id="cheque_no" name="cheque_no" placeholder="Enter cheque number" value="<?= isset($_POST['cheque_no']) ? $_POST['cheque_no'] : '' ?>" type="text" autocomplete="off">
								</div>
								<div class="form-group col-sm-6 <?= (isset($errors['cheque_date'])) ? 'has-error' : '' ?>">
									<label>Cheque Date</label>
									<input class="form-control" id="dob" name="cheque_date" placeholder="Enter cheque number" value="<?= isset($_POST['cheque_date']) ? $_POST['cheque_date'] : '' ?>" type="text" autocomplete="off">
								</div>
								<div class="form-group col-sm-6  <?= (isset($errors['cheque_bank'])) ? 'has-error' : '' ?>">
									<label>Cheque Bank Details</label>
									<textarea class="form-control" id="cheque_bank" name="cheque_bank" placeholder="Enter cheque / demand bank details"><?= isset($_POST['cheque_bank']) ? $_POST['cheque_bank'] : '' ?></textarea>
								</div>

								<div class="form-group col-sm-6  <?= (isset($errors['bonus_type'])) ? 'has-error' : '' ?>">
									<label>Promotional Bonus</label>
									<?php $bonus_type = isset($_POST['bonus_type']) ? $_POST['bonus_type'] : '0'; ?>

									<label> <input name="bonus_type" id="bonus_type1" value="1" type="radio" <?= ($bonus_type == '1') ? 'checked="checked"' : '' ?>> YES </label>

									<label> <input name="bonus_type" id="bonus_type2" value="0" type="radio" <?= ($bonus_type == '0') ? 'checked="checked"' : '' ?>> NO </label>
									<span class="help-block"><?= (isset($errors['bonus_type'])) ? $errors['bonus_type'] : '' ?></span>
								</div>
							</div>
						</div>

						<div class="box-footer text-center">
							<a href="page.php?page=Wallet" class="btn btn-warning">Cancel</a>
							<input type="submit" value="Make Payment" name="paymoney" class="btn btn-primary" />
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>