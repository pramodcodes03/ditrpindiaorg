 <?php
	include('include/classes/exam.class.php');
	$exam = new exam();
	$studid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

	$action		= isset($_POST['action']) ? $_POST['action'] : '';


	if ($action != '') {
		//print_r($_POST);
		$result = $exam->download_offline_exam_ppr();
		$esc 	= $db->test(isset($_POST['esc']) ? $_POST['esc'] : '');
		$result = json_decode($result, true);
		$success = isset($result['success']) ? $result['success'] : '';
		$message = isset($result['message']) ? $result['message'] : '';
		$errors = isset($result['errors']) ? $result['errors'] : '';

		if ($success == true) {
			$scd = isset($result['scd']) ? $result['scd'] : '';
			if ($scd != '') {
				$scd = base64_encode($scd);
				header('location:page.php?page=print-offline-papers&scd=' . $scd);
			}
		}
	}
	?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<h1> Download Offline Exam Papers </h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li class="active">Download Offline Exam Papers</li>
 		</ol>
 	</section>

 	<!-- Main content -->
 	<section class="content">

 		<div class="row">

 			<div class="col-xs-12">
 				<div class="box box-warning">
 					<div class="box-header">
 					</div>
 					<form role="form" action="" method="post" onsubmit="pageLoaderOverlay('show');">
 						<div class="box-body">

 							<div class="col-sm-3"></div>
 							<div class="col-sm-6">
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
 								<div class="form-group <?= (isset($errors['esc'])) ? 'has-error' : '' ?>">
 									<label for="esc">Enter Exam Secret Code</label>
 									<input class="form-control" id="esc" name="esc" placeholder="Exam Secret Code" type="text" value="<?= isset($_POST['esc']) ? $_POST['esc'] : '' ?>">
 									<span class="help-block"><?= isset($errors['esc']) ? $errors['esc'] : '' ?></span>

 									<input type="submit" name="action" value="Submit" class="btn btn-primary" />
 								</div>
 							</div>
 						</div>
 						<!-- /.box-body -->
 					</form>
 					<!-- /.box-body -->
 				</div>
 				<!-- /.box -->
 				<!-- /.box -->
 				<?php
					$res = $exam->list_offline_downloaded_papers('', $studid, '', '');
					if ($res != '') {
					?>
 					<div class="box">
 						<div class="box-header">
 							<h3 class="box-title">Downloaded Offline Exam Files</h3>
 						</div>
 						<!-- /.box-header -->
 						<div class="box-body no-padding">
 							<table class="table table-bordered">
 								<thead>
 									<th>#</th>
 									<th>Course</th>
 									<th>Question Paper</th>
 									<th>Model Answer Paper</th>
 									<!-- <th>Created On</th> -->
 								</thead>
 								<tbody>
 									<?php
										$srno = 1;
										while ($data = $res->fetch_assoc()) {
											extract($data);
											$quefile = '../uploads/exam/offline/' . $studid . '/' . $QUESTION_PAPER;
											$ansfile = '../uploads/exam/offline/' . $studid . '/' . $BLANK_ANSWER_PAPER;

											$downloadque = "<a href='$quefile' target='_blank' class='btn btn-flat bg-orange'>Download Question Paper</a>";
											$downloadans = "<a href='$ansfile' target='_blank' class='btn btn-flat bg-orange'>Download Answer Paper</a>";
											$course_info	= $db->get_inst_course_info($INSTITUTE_COURSE_ID);
											$course_name 	= $course_info['COURSE_NAME'];
											echo "<tr><td>$srno</td>
						<td>$course_name</td>
						<td>$downloadque</td>
						<td>$downloadans</td>
						<!-- <td>$CREATED_DATE</td> -->
						</tr>
						";
											$srno++;
										}

										?> </tbody>
 							</table>
 						</div>
 						<!-- /.box-body -->
 					</div>
 					<!-- /.box -->
 				<?php } ?>
 			</div>

 		</div>
 		<!-- /.row -->
 	</section>
 	<!-- /.content -->
 </div>