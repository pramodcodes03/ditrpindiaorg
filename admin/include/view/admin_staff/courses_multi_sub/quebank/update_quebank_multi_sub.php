<?php
$quebank_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_quebank']) ? $_POST['update_quebank'] : '';
include_once('include/classes/exam.class.php');
$exam = new exam();
if ($action != '') {
	$result = $exam->update_quebank($quebank_id);
	$result = json_decode($result, true);
	$success = isset($result['success']) ? $result['success'] : '';
	$message = $result['message'];
	$errors = isset($result['errors']) ? $result['errors'] : '';
	if ($success == true) {
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=list-quebank');
	}
}
/* get que bank details */

$res = $exam->list_quetion_bank($quebank_id, '');
if ($res != '') {
	while ($data = $res->fetch_assoc()) {
		$QUEBANK_ID 	= $data['QUEBANK_ID'];
		$COURSE_ID		= $data['COURSE_ID'];
		$EXAM_NAME 		= $data['EXAM_NAME'];
		$FILE_NAME 		= $data['FILE_NAME'];
		$IMG_ZIP_FILE	= $data['IMG_ZIP_FILE'];
		$ACTIVE			= $data['ACTIVE'];
		$CREATED_BY 	= $data['CREATED_BY'];
		$CREATED_ON 	= $data['CREATED_DATE'];
	}
}
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Update Question Bank

		</h1>
		<ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="list-quebank">Question Banks</a></li>
			<li class="active">Update Question Bank</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">

			<!-- left column -->
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

			<div class="row">


				<div class="col-md-2">
				</div>
				<div class="col-md-8">
					<!-- general form elements -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Update Question Bank</h3>
							<a href="<?= QUEBANK_PATH ?>/sample.zip" target="_blank" class="btn btn-sm btn-success pull-right"><i class="fa fa-cloud-download"></i> Download Sample CSV</a>
						</div>
						<div class="box-body">
							<input type="hidden" name="quebank_id" value="<?= $QUEBANK_ID ?>" />
							<div class="form-group <?= (isset($errors['courseid'])) ? 'has-error' : '' ?>">
								<label for="courseid" class="col-sm-3 control-label">Course Code</label>
								<div class="col-sm-9">
									<select class="form-control" id="courseid" name="courseid" onchange="getExamByCourse(this.value)">
										<?php
										$courseid = isset($_POST['courseid']) ? $_POST['courseid'] : $COURSE_ID;
										echo $db->MenuItemsDropdown('courses', 'COURSE_ID', 'COURSE_CODE', 'COURSE_ID,COURSE_CODE', $courseid, ' ORDER BY COURSE_CODE ASC');
										?>
									</select>
									<span class="help-block"><?= isset($errors['courseid']) ? $errors['courseid'] : '' ?></span>
								</div>
							</div>

							<div class="form-group <?= (isset($errors['examname'])) ? 'has-error' : '' ?>">
								<label for="examname" class="col-sm-3 control-label">Exam Name</label>
								<div class="col-sm-9">
									<input class="form-control" id="examname" name="examname" placeholder="Exam name" value="<?= isset($_POST['examname']) ? $_POST['examname'] : $EXAM_NAME ?>" type="text">
									<span class="help-block"><?= isset($errors['examname']) ? $errors['examname'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['quebankfile'])) ? 'has-error' : '' ?>">
								<label for="quebankfile" class="col-sm-3 control-label">Upload CSV</label>
								<div class="col-sm-6">
									<?php
									/*if($FILE_NAME!='')
					{
						$path = QUEBANK_PATH.'/'.$QUEBANK_ID.'/'.$FILE_NAME;						
						echo '<a href="'.$path.'" target="_blank">'.$FILE_NAME.'</a><br><br>';
					}*/
									?>

									<input id="quebankfile" name="quebankfile" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
									<span class="help-block"><?= isset($errors['quebankfile']) ? $errors['quebankfile'] : '' ?></span>
								</div>
							</div>
							<div class="form-group <?= (isset($errors['quebankimgs'])) ? 'has-error' : '' ?>">
								<label for="quebankimgs" class="col-sm-3 control-label">Upload Images (Zip)</label>
								<div class="col-sm-6">
									<?php
									/*if($FILE_NAME!='')
					{
						$path = QUEBANK_PATH.'/'.$QUEBANK_ID.'/'.$FILE_NAME;						
						echo '<a href="'.$path.'" target="_blank">'.$FILE_NAME.'</a><br><br>';
					}*/
									?>

									<input id="quebankimgs" name="quebankimgs" type="file" accept=".zip">
									<span class="help-block"><?= isset($errors['quebankimgs']) ? $errors['quebankimgs'] : '' ?></span>
									<ul>
										<li>Create a folder with name " images ".</li>
										<li>Add all the image files in the same " images " folder.</li>
										<li>Add the " images " folder to a zip file.</li>
										<li>Choose the zip file.</li>
										<li>For example: <a href="<?= QUEBANK_PATH ?>/images.zip">Download Zip</a></li>
									</ul>
								</div>
							</div>
							<div class="form-group">
								<?php $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;  ?>
								<label for="status" class="col-sm-3 control-label">Status</label>
								<div class="radio">
									<label>
										<input name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?> type="radio">
										Active
									</label>
									<label>
										<input name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?> type="radio">
										Inactive
									</label>
								</div>
							</div>


						</div>
						<!-- /.box-body -->
						<div class="box-footer text-center">
							<a href="page.php?page=list-quebank" class="btn btn-default">Cancel</a>
							<input type="submit" name="update_quebank" class="btn btn-info" value="Update" />
						</div>
					</div>
				</div>




			</div>
		</form>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>