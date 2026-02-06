 <?php
	include_once('include/classes/student.class.php');
	$student = new student();
	$student_id 	= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
	$file_id 		= isset($_REQUEST['file_id']) ? $_REQUEST['file_id'] : '';

	$action = isset($_POST['upload']) ? $_POST['upload'] : '';
	if ($action != '') {
		$stud_id 		= isset($_POST['stud_id']) ? $_POST['stud_id'] : '';
		$result = $student->store_file($stud_id);
		$result = json_decode($result, true);
		$success = isset($result['success']) ? $result['success'] : '';
		$message = isset($result['message']) ? $result['message'] : '';
		$errors = isset($result['errors']) ? $result['errors'] : '';
		if ($success == true) {
			$_SESSION['msg'] = $message;
			$_SESSION['msg_flag'] = $success;
			//header('location:page.php?page=list-student-payments');
		}
	}
	?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<h1>
 			Student Storage Drive

 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li>Student</li>
 			<li class="active">List Storage Files</li>
 		</ol>
 	</section>

 	<!-- Main content -->
 	<section class="content">
 		<?php
			if (isset($_SESSION['msg'])) {
				$message = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';
				$msg_flag = $_SESSION['msg_flag'];
			?>
 			<div class="row">
 				<div class="col-sm-12">
 					<div class="alert alert-<?= ($msg_flag == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
 						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
 						<h4><i class="icon fa fa-check"></i> <?= ($msg_flag == true) ? 'Success' : 'Error' ?>:</h4>
 						<?= ($message != '') ? $message : 'Sorry! Something went wrong!'; ?>
 					</div>
 				</div>
 			</div>
 		<?php
				unset($_SESSION['msg']);
				unset($_SESSION['msg_flag']);
			}
			?>
 		<?php
			if ($student->count_total_files_uploaded($student_id) < STUD_MAX_DRIVE_FILE) {
			?>
 			<div class="row">
 				<div class="col-xs-12">
 					<div class="box">
 						<div class="box-header">
 							<form class="form-horizontal" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');">
 								<input type="hidden" name="stud_id" value="<?= $student_id ?>" />
 								<div class="form-group <?= (isset($errors['file'])) ? 'has-error' : '' ?>">
 									<label for="file" class="col-sm-2 control-label">Upload New Files: </label>
 									<div class="col-sm-3">
 										<input type="file" name="file[]" multiple="multiple" class="form-control" id="file" />
 									</div>
 									<div class="col-sm-2">
 										<input type="submit" class="btn btn-sm btn-primary" id="upload" name="upload" Value="Upload" />
 									</div>
 									<span class="help-block"><?= isset($errors['file']) ? $errors['file'] : '' ?></span>
 								</div>
 							</form>
 						</div>
 					</div>
 				</div>
 			</div>
 		<?php
			}
			?>
 		<div class="row">
 			<div class="col-xs-12">
 				<div class="box">
 					<div class="box-header">

 					</div>
 					<!-- /.box-header -->
 					<div class="box-body">
 						<table class="table table-bordered table-hover data-tbl">
 							<thead>
 								<tr>
 									<th>#</th>
 									<th>File Name</th>
 									<th>File Type</th>
 									<th>Date</th>
 									<th>Action</th>
 								</tr>
 							</thead>
 							<tbody>
 								<?php


									$files = $student->get_student_docs($student_id, " AND FILE_LABEL='" . STUD_DRIVE_FILE . "' ORDER BY CREATED_ON DESC");
									if (!empty($files)) {
										$filesNo = 1;
										foreach ($files as $value) {
											extract($value);
											$filePath = 	STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $FILE_NAME;
											$downloadLink = 'download-files&filename=' . $FILE_NAME . '&stud=' . $STUDENT_ID;
											$file_ico = $access->book_icon($FILE_MIME);

											$action = "	
							<a href='javascript:void(0)' onclick='deleteStudFile($FILE_ID)' class='btn btn-xs btn-danger' title='Delete'><i class=' fa fa-trash'></i></a>
							<a href='$filePath' target='_blank' class='btn btn-xs btn-primary' title='Download File'><i class=' fa fa-download'></i></a>
							
							<input type='hidden' value='$downloadLink' id='downloadLink$FILE_ID' />
							";
											echo $courseDetail = "<tr id='img-$FILE_ID'><td width='5%'>$filesNo</td>
										  <td><a href='$filePath' target='_blank'>$FILE_NAME</a></td>	
										  <td><a href='$filePath' target='_blank'><i class='fa $file_ico fa-2x'></i></a></td>	
										 
										  <td>$CREATED_DATE</td>
										 <td>$action</td> 
										 </tr>";
											$filesNo++;
										}
									}

									?>
 							</tbody>
 						</table>
 					</div>
 					<!-- /.box-body -->
 				</div>
 				<!-- /.box -->
 				<!-- /.box -->
 			</div>
 			<!-- /.col -->
 		</div>
 		<!-- /.row -->
 	</section>
 	<!-- /.content -->
 </div>