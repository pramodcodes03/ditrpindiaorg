<?php
ini_set('max_execution_time', 600);
$action = isset($_POST['add_quebank']) ? $_POST['add_quebank'] : '';
include_once('include/classes/exammultisub.class.php');
$exammultisub = new exammultisub();
if ($action != '') {
	$result = $exammultisub->add_quebank_multi_sub();
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=listQueBankMultiSub');
	}
}
?>
<div class="content-wrapper">
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Add Question Bank For Courses With Multiple Subjects
						<a href="<?= QUEBANK_PATH ?>/sample.csv" target="_blank" class="btn btn-primary" style="float: right">Download Sample CSV</a>
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

						<div class="row">
							<div class="col-md-6 form-group">
								<label for="exampleFormControlSelect3">Course Code</label>
								<select class="form-control form-control-sm" id="exampleFormControlSelect3" id="courseid" name="courseid" onchange="getSubjectId(this.value)">
									<?php
									$courseid = isset($_POST['courseid']) ? $_POST['courseid'] : '';
									echo $db->MenuItemsDropdown('multi_sub_courses', 'MULTI_SUB_COURSE_ID', 'COURSE', 'MULTI_SUB_COURSE_ID, get_course_multi_sub_title_modify(MULTI_SUB_COURSE_ID) AS COURSE', $courseid, ' WHERE ACTIVE=1 AND DELETE_FLAG=0 ORDER BY MULTI_SUB_COURSE_CODE ASC');
									?>
								</select>
							</div>
							<div class="col-md-6 form-group <?= (isset($errors['subjectid'])) ? 'has-error' : '' ?>">
								<label for="subjectid">Course Subjects</label>

								<select class="form-control select2" id="subjectid" name="subjectid">
									<?php
									$subjectid = isset($_POST['subjectid']) ? $_POST['subjectid'] : '';
									echo $db->MenuItemsDropdown('multi_sub_courses_subjects', 'COURSE_SUBJECT_ID', 'COURSE_SUBJECT_NAME', 'COURSE_SUBJECT_ID,COURSE_SUBJECT_NAME', $subjectid, 'ORDER BY COURSE_SUBJECT_ID ASC');
									?>
								</select>
								<span class="help-block"><?= isset($errors['subjectid']) ? $errors['subjectid'] : '' ?></span>

							</div>

							<div class="col-md-12 form-group">
								<label>Upload CSV</label>
								<input type="file" name="quebankfile" class="file-upload-default">
								<div class="input-group col-xs-12">
									<input type="text" class="form-control file-upload-info" disabled placeholder="Upload Course Image">
									<span class="input-group-append">
										<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
									</span>
								</div>
							</div>

							<!-- <div class="col-md-12 form-group">
	              <label>Upload Images Zip File</label>
	              <input type="file" name="quebankimgs" class="file-upload-default">
	              <div class="input-group col-xs-12">
	                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Course Image">
	                <span class="input-group-append">
	                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
	                </span>
	              </div>
	            </div> 
				<div class="col-md-12 form-group">
	            <ul>
					<li>Create a folder with name " images ".</li>
					<li>Add all the image files in the same " images " folder.</li>
					<li>Add the  " images " folder to a zip file.</li>
					<li>Choose the zip file.</li>
					<li>For example: <a href="<?= QUEBANK_PATH ?>/images.zip">Download Zip</a></li>
				</ul>            
				</div>  -->

							<div class="col-md-6 form-group row">
								<?php $status = isset($_POST['status']) ? $_POST['status'] : 0;  ?>
								<label class="col-sm-2 col-form-label">Status</label>
								<div class="col-sm-3">
									<div class="form-check">
										<label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?>>
											Active
										</label>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-check">
										<label class="form-check-label">
											<input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?>>
											Inactive
										</label>
									</div>
								</div>
							</div>
						</div>
						<input type="submit" name="add_quebank" class="btn btn-primary mr-2" value="Submit">
						<a href="page.php?page=listQueBankMultiSub" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>