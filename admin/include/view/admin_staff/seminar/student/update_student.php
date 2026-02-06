<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';

$action = isset($_POST['update_seminar_student']) ? $_POST['update_seminar_student'] : '';

include_once('include/classes/seminar.class.php');
$seminar = new seminar();

if ($action != '') {
	$id = isset($_POST['id']) ? $_POST['id'] : '';

	$result = $seminar->update_seminar_student($id);
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=listSeminarStudent');
	}
}
/* get institute details */
$res = $seminar->list_seminar_student($id, '');
if ($res != '') {
	$srno = 1;
	while ($data = $res->fetch_assoc()) {
		extract($data);
	}
}
?>
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title"> Update Student </h4>

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

					<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">
						<div class="box-body">

							<div class="row">
								<input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>" />
								<div class="form-group col-sm-4 <?= (isset($errors['seminar_id'])) ? 'has-error' : '' ?>">
									<label>Select Seminar Topic</label>
									<?php
									$seminar_id = isset($_POST['seminar_id']) ? $_POST['seminar_id'] : $seminar_id;
									?>
									<select class="form-control select2 " name="seminar_id" id="seminar_id" style="width: 100%;" onchange="getCitiesByState(this.value)">
										<?php echo $db->MenuItemsDropdown('seminar', "id", "topic_name", "id, topic_name", $seminar_id, "WHERE delete_flag = 0 ORDER BY id"); ?>
									</select>
									<span class="help-block"><?= (isset($errors['seminar_id'])) ? $errors['seminar_id'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['name'])) ? 'has-error' : '' ?>">
									<label for="name">Student Name <span class="asterisk">*</span></label>
									<input type="text" name="name" class="form-control" value="<?= isset($_POST['name']) ? $_POST['name'] : $name ?>" id="name" placeholder="Enter Student Name">
									<span class="help-block"><?= isset($errors['name']) ? $errors['name'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['crr_no'])) ? 'has-error' : '' ?>">
									<label for="crr_no">CRR Number</label>
									<input type="text" name="crr_no" class="form-control" value="<?= isset($_POST['crr_no']) ? $_POST['crr_no'] : $crr_no ?>" id="crr_no" placeholder="CRR Number">
									<span class="help-block"><?= isset($errors['crr_no']) ? $errors['crr_no'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['type'])) ? 'has-error' : '' ?>">
									<label>Student Type </label>
									<?php $type = isset($_POST['type']) ? $_POST['type'] : $type; ?>
									<select class="form-control" name="type" id="type">
										<option <?= ($type == '') ? 'selected="selected"' : '' ?> value="">--select--</option>
										<option value="Chairperson" <?= ($type == 'Chairperson') ? 'selected="selected"' : '' ?>>Chairperson</option>
										<option value="Resource persons" <?= ($type == 'Resource persons') ? 'selected="selected"' : '' ?>>Resource persons</option>
										<option value="Keynote Speaker" <?= ($type == 'Keynote Speaker') ? 'selected="selected"' : '' ?>>Keynote Speaker</option>
										<option value="Paper Presentation" <?= ($type == 'Paper Presentation') ? 'selected="selected"' : '' ?>>Paper Presentation</option>
										<option value="Poster Presentation" <?= ($type == 'Poster Presentation') ? 'selected="selected"' : '' ?>>Poster Presentation</option>
										<option value="Instructor" <?= ($type == 'Instructor') ? 'selected="selected"' : '' ?>>Instructor</option>
										<option value="Coordinator" <?= ($type == 'Coordinator') ? 'selected="selected"' : '' ?>>Coordinator</option>
										<option value="Participant" <?= ($type == 'Participant') ? 'selected="selected"' : '' ?>>Participant</option>

									</select>
									<span class="help-block"><?= (isset($errors['type'])) ? $errors['type'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['cre_points'])) ? 'has-error' : '' ?>">
									<label for="cre_points">CRE Points <span class="asterisk">*</span></label>
									<input type="text" name="cre_points" class="form-control" id="cre_points" value="<?= isset($_POST['cre_points']) ? $_POST['cre_points'] : $cre_points ?>" placeholder="CRE Points">
									<span class="help-block"><?= (isset($errors['cre_points'])) ? $errors['cre_points'] : '' ?></span>
								</div>

								<div class="form-group col-sm-4 <?= (isset($errors['session'])) ? 'has-error' : '' ?>">
									<label>Session Type </label>
									<?php $session = isset($_POST['session']) ? $_POST['session'] : $session; ?>
									<select class="form-control" name="session" id="session">
										<option <?= ($session == '') ? 'selected="selected"' : '' ?> value="">--select--</option>
										<option value="per day" <?= ($session == 'per day') ? 'selected="selected"' : '' ?>>per day</option>
										<option value="per session" <?= ($session == 'per session') ? 'selected="selected"' : '' ?>>per session</option>
									</select>
									<span class="help-block"><?= (isset($errors['session'])) ? $errors['session'] : '' ?></span>
								</div>


								<div class="col-md-6 form-group row">
									<?php $active = isset($_POST['active']) ? $_POST['active'] : $active;  ?>
									<label class="col-sm-4 col-form-label">Status</label>
									<div class="col-sm-3">
										<div class="form-check">
											<label class="form-check-label">
												<input type="radio" class="form-check-input" name="active" id="optionsRadios1" value="1" <?= ($active == 1) ? "checked=''" : ''  ?>>
												Active
											</label>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-check">
											<label class="form-check-label">
												<input type="radio" class="form-check-input" name="active" id="optionsRadios2" value="0" <?= ($active == 0) ? "checked=''" : ''  ?>>
												Inactive
											</label>
										</div>
									</div>
								</div>

							</div>
							<div class="clearfix"></div>
							<div class="row">
								<div class="box-footer text-center">
									<input type="submit" class="btn btn-primary" name="update_seminar_student" value="Update" />
									&nbsp;&nbsp;&nbsp;
									<a href="page.php?page=listSeminarStudent" class="btn btn-danger" title="Cancel">Cancel</a>
									&nbsp;&nbsp;&nbsp;
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>