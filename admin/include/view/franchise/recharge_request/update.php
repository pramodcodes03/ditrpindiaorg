<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_rechargerequest']) ? $_POST['update_rechargerequest'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
	$result	= $websiteManage->update_rechargerequest();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$type = isset($_POST['type']) ? $_POST['type'] : '';
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=listRechargeRequest');
	}
}
$res = $websiteManage->list_rechargerequest($id, '');
while ($data = $res->fetch_assoc()) {
	extract($data);
}
?><div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Update Recharge Request </h4>
					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_expenses">
						<div class="box-body row">
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
							<div class="col-md-12">
								<!-- general form elements -->
								<div class="box box-primary">

									<div class="box-body row">
										<form role="form" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
											<input type="hidden" name="id" value="<?= $id ?>" />

											<div class="col-md-6 form-group">
												<label for="exampleFormControlSelect3">Select Type</label>
												<?php $wallet_name = isset($_POST['wallet_name']) ? $_POST['wallet_name'] : $wallet_name;	?>
												<select class="form-control form-control-sm" id="wallet_name" name="wallet_name" required>
													<option value="" <?php echo ($wallet_name == '') ? 'selected="selected"' : '' ?>> Select Type</option>
													<option value="1" <?php echo ($wallet_name == '1') ? 'selected="selected"' : '' ?>> Main Wallet </option>
													<option value="2" <?php echo ($wallet_name == '2') ? 'selected="selected"' : '' ?>> Courier Wallet</option>
												</select>
											</div>

											<div class="form-group col-sm-6  <?= (isset($errors['title'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">Title</label>
												<div class="col-xs-6">
													<input type="text" name="title" class="form-control" id="title" placeholder="Title" value="<?= isset($_POST['title']) ? $_POST['title'] : $title ?>" />
													<span class="help-block"><?= (isset($errors['title'])) ? $errors['title'] : '' ?></span>
												</div>
											</div>

											<div class="form-group col-sm-6 <?= (isset($errors['amount'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">Amount</label>
												<div class="col-xs-6">
													<input type="text" name="amount" class="form-control" id="amount" placeholder="Amount" value="<?= isset($_POST['amount']) ? $_POST['amount'] : $amount ?>" />
													<span class="help-block"><?= (isset($errors['amount'])) ? $errors['amount'] : '' ?></span>
												</div>
											</div>

											<div class="form-group col-sm-6 ">
												<label for="inputEmail" class="control-label col-xs-3">Status</label>
												<?php $status =  isset($_POST['status']) ? $_POST['status'] : $active; ?>
												<div>
													<label class="radio-inline">
														<input type="radio" name="status" id="status" value="1" <?= ($status == 1) ? 'checked="checked"' : '' ?> />Active
													</label>
													<label class="radio-inline">
														<input type="radio" name="status" id="status2" value="0" <?= ($status == 0) ? 'checked="checked"' : '' ?> />Inactive
													</label>
												</div>
											</div>

											<!-- /.box-body -->

											<div class="box-footer text-center">
												<input type="submit" class="btn btn-primary" name="update_rechargerequest" value="Update Recharge Request" /> &nbsp;&nbsp;&nbsp;
												<a href="page.php?page=listRechargeRequest" class="btn btn-warning" title="Cancel">Cancel</a>

											</div>
										</form>
									</div>
									<!-- /.box -->


									<!-- /.box -->

									<!-- /.box -->

									<!-- /.box -->

								</div>

							</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
</div>