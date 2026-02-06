<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_rechargeoffers']) ? $_POST['update_rechargeoffers'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
	$result	= $websiteManage->update_rechargeoffers();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$type = isset($_POST['type']) ? $_POST['type'] : '';
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=listRechargeOffers');
	}
}
$res = $websiteManage->list_rechargeoffers($id, '');
while ($data = $res->fetch_assoc()) {
	extract($data);

	if ($image != '')
		$image = RECHARGEOFFER_PATH . '/' . $id . '/' . $image;
}
?><div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Update Recharge Offers </h4>
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
											<div class="form-group col-sm-6  <?= (isset($errors['title'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">Title</label>
												<div class="col-xs-6">
													<input type="text" name="title" class="form-control" id="title" placeholder="Title" value="<?= isset($_POST['title']) ? $_POST['title'] : $name ?>" />
													<span class="help-block"><?= (isset($errors['title'])) ? $errors['title'] : '' ?></span>
												</div>
											</div>
											<div class="form-group col-sm-6 ">
												<label for="inputEmail" class="control-label col-xs-3">Description</label>
												<div class="col-xs-6">
													<textarea name="description" class="form-control" id="description"><?= isset($_POST['description']) ? $_POST['description'] : $description ?></textarea>
												</div>
												<p id="title_err" style="color:#f00;"></p>
											</div>

											<div class="form-group col-sm-6  <?= (isset($errors['date'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">Start Date</label>
												<div class="col-xs-6">
													<input type="date" name="date" class="form-control" id="date" value="<?= isset($_POST['date']) ? $_POST['date'] : $date ?>" />
													<span class="help-block"><?= (isset($errors['date'])) ? $errors['date'] : '' ?></span>
												</div>
											</div>

											<div class="form-group col-sm-6  <?= (isset($errors['end_date'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">End Date</label>
												<div class="col-xs-6">
													<input type="date" name="end_date" class="form-control" id="end_date" value="<?= isset($_POST['end_date']) ? $_POST['end_date'] : $end_date ?>" />
													<span class="help-block"><?= (isset($errors['end_date'])) ? $errors['end_date'] : '' ?></span>
												</div>
											</div>

											<div class="form-group col-sm-6  <?= (isset($errors['time'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">Time</label>
												<div class="col-xs-6">
													<input type="text" name="time" class="form-control" id="time" placeholder="time" value="<?= isset($_POST['time']) ? $_POST['time'] : $time ?>" />
													<span class="help-block"><?= (isset($errors['time'])) ? $errors['time'] : '' ?></span>
												</div>
											</div>

											<div class="form-group col-sm-6  <?= (isset($errors['event_imgs'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">Image </label>
												<div class="col-xs-6 col-md-6">
													<input type="file" name="event_imgs" id="event_imgs" />
													<span class="help-block"><?= (isset($errors['event_imgs'])) ? $errors['event_imgs'] : '' ?></span> <!--(jpg,  png, gif, pdf, cdr format only)-->
												</div>
											</div>

											<?php
											if ($image != '') {
											?>
												<img src="<?= $image ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />
											<?php
											}
											?>

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
												<input type="submit" class="btn btn-primary" name="update_rechargeoffers" value="Update" /> &nbsp;&nbsp;&nbsp;
												<a href="page.php?page=listRechargeOffers" class="btn btn-warning" title="Cancel">Cancel</a>

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