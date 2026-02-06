<?php
ini_set('max_execution_time', 600);
$action = isset($_POST['add_old_certificate']) ? $_POST['add_old_certificate'] : '';
include_once('include/classes/tools.class.php');
$tools = new tools();
if ($action != '') {
	$result = $tools->add_old_certificate();
	$result = json_decode($result, true);
	//print_r($result);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=oldCertificate');
	}
}

?>


<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Add Old Certificate
						<a href="<?= OLD_CERTIFICATE_PATH ?>/samplelist.csv" class="btn btn-primary" style="float: right; margin-right:20px;">Sample Import Format</a>
					</h4>
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
										echo "<ul>";
										foreach ($errors as $error) {
											echo "<li>$error</li>";
										}
										echo "<ul>";
										?>
									</div>
								</div>
							</div>
						<?php
						}
						?>
						<div class="form-group  col-sm-6 ">
							<label>Upload CSV</label>
							<input type="file" name="certfile" class="file-upload-default">
							<div class="input-group col-xs-12">
								<input type="text" class="form-control file-upload-info" disabled placeholder="Upload CSV">
								<span class="input-group-append">
									<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
								</span>
							</div>
							<span class="help-block"><?= (isset($errors['certfile'])) ? $errors['certfile'] : '' ?></span>
						</div>

						<div class="form-group col-sm-6 <?= (isset($errors['password'])) ? 'has-error' : '' ?>">
							<label for="password">Password</label>
							<input type="password" class="form-control" placeholder="password" name="password" value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
							<span class="help-block"><?= (isset($errors['password'])) ? $errors['password'] : '' ?></span>
						</div>

						<div class="form-group row">
							<?php $status = isset($_POST['status']) ? $_POST['status'] : 1;  ?>
							<label class="col-sm-2 col-form-label">Status</label>
							<div class="col-sm-2">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?>>
										Active
									</label>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-check">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?>>
										Inactive
									</label>
								</div>
							</div>
						</div>
						<input type="submit" name="add_old_certificate" class="btn btn-primary mr-2" value="Submit">
						<a href="page.php?page=oldCertificate" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>