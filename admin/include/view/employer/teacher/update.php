<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_teacher']) ? $_POST['update_teacher'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
	$result	= $websiteManage->update_teacher();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$type = isset($_POST['type']) ? $_POST['type'] : '';
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=listTeacher');
	}
}
$res = $websiteManage->list_teacher($id, '');
while ($data = $res->fetch_assoc()) {
	extract($data);
	if ($photo != '')
		$photo = TEACHERPHOTO_PATH . '/' . $id . '/' . $photo;
}
?>

<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Update Teacher </h4>
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

								<div class="box-body ">
									<form role="form" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
										<input type="hidden" name="id" value="<?= $id ?>" />
										<div class="row col-md-12">


											<div class="form-group col-md-4 <?= (isset($errors['photo'])) ? 'has-error' : '' ?>">
												<label for="stud_photo">Photo </label>
												<input type="file" name="photo">
												<p class="help-block"><?= (isset($errors['photo'])) ? $errors['photo'] : '' ?></p>
											</div>

											<?php
											if ($photo != '') {
											?>
												<img src="<?= $photo ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />
											<?php
											}
											?>

											<div class="form-group col-sm-4 <?= (isset($errors['code'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">Id Number</label>
												<div class="col-xs-6">
													<input type="text" name="code" class="form-control" id="code" placeholder="Id Number" value="<?= isset($_POST['code']) ? $_POST['code'] : $code ?>" />
													<span class="help-block"><?= (isset($errors['code'])) ? $errors['code'] : '' ?></span>
												</div>
											</div>

											<div class="form-group col-sm-4 <?= (isset($errors['name'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">Name</label>
												<div class="col-xs-6">
													<input type="text" name="name" class="form-control" id="name" placeholder="Name" value="<?= isset($_POST['name']) ? $_POST['name'] : $name ?>" />
													<span class="help-block"><?= (isset($errors['name'])) ? $errors['name'] : '' ?></span>
												</div>
											</div>

											<div class="form-group col-sm-4 <?= (isset($errors['designation'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">Designation</label>
												<div class="col-xs-6">
													<input type="text" name="designation" class="form-control" id="designation" placeholder="Designation" value="<?= isset($_POST['designation']) ? $_POST['designation'] : $designation ?>" />
													<span class="help-block"><?= (isset($errors['designation'])) ? $errors['designation'] : '' ?></span>
												</div>
											</div>

											<div class="form-group col-sm-4 <?= (isset($errors['mobile'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">Mobile</label>
												<div class="col-xs-6">
													<input type="text" name="mobile" class="form-control" id="mobile" placeholder="Mobile" value="<?= isset($_POST['mobile']) ? $_POST['mobile'] : $mobile ?>" />
													<span class="help-block"><?= (isset($errors['mobile'])) ? $errors['mobile'] : '' ?></span>
												</div>
											</div>

											<div class="form-group col-sm-4 <?= (isset($errors['email'])) ? 'has-error' : '' ?>">
												<label for="inputEmail" class="control-label col-xs-3">Email Id</label>
												<div class="col-xs-6">
													<input type="text" name="email" class="form-control" id="email" placeholder="Email Id" value="<?= isset($_POST['email']) ? $_POST['email'] : $email ?>" />
													<span class="help-block"><?= (isset($errors['email'])) ? $errors['email'] : '' ?></span>
												</div>
											</div>

											<div class="form-group col-sm-6">
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
										</div>
										<!-- /.box-body -->

										<div class="box-footer text-center">
											<input type="submit" class="btn btn-primary" name="update_teacher" value="Update Teacher" /> &nbsp;&nbsp;&nbsp;
											<a href="page.php?page=listTeacher" class="btn btn-warning" title="Cancel">Cancel</a>

										</div>
									</form>
								</div>
								<!-- /.box -->


								<!-- /.box -->

								<!-- /.box -->

								<!-- /.box -->

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>