  <!-- Content Wrapper. Contains page content -->
  <?php
	include_once('include/classes/student.class.php');
	$student = new student();
	$student_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
	$action = isset($_POST['action']) ? $_POST['action'] : '';


	?>
  <div class="content-wrapper">
  	<!-- Content Header (Page header) -->
  	<section class="content-header">

  	</section>

  	<!-- Main content -->
  	<section class="content">

  		<!-- /.row -->
  		<!-- Main row -->
  		<div class="row">
  			<!-- Left col -->
  			<section class="col-lg-12 connectedSortable">
  				<!-- Custom tabs (Charts with tabs)-->
  				<div class="nav-tabs-custom">
  					<?php
						include_once('include/classes/student.class.php');
						$student = new student();
						$res = $student->list_student($student_id, '', '');
						if ($res != '') {
							while ($resdata = $res->fetch_assoc()) {
								extract($resdata);
								//print_r($resdata);
							}
						}
						$STUD_PHOTO = ($STUD_PHOTO != '') ? STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUD_PHOTO : '../uploads/default_user.png';
						$STUD_SIGN =  ($STUD_SIGN != '') ? STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUD_SIGN : '../uploads/default_user.png';
						?>

  					<div class="tab-content" style="background:#f2f3f2;">
  						<!-- Morris chart - Sales -->
  						<div class="tab-pane active" style="position: relative;">
  							<p>
  								<?php
									//disable edit if applied for certifcate
									//if(!$db->certificate_pending($STUDENT_ID,$INSTITUTE_ID) && !$db->exam_pending($STUDENT_ID,$INSTITUTE_ID))
									//	{
									?>
  								<!-- <a href="page.php?page=update-student" class="btn btn-primary btn1"><i class="fa fa-pencil"></i> Edit Details</a> 
			  <?php //} /*else echo "<span class='label label-danger'>Certificate Under Process!</span>"; */
				?> -->
  								<a href="page.php?page=update-student" class="btn btn-primary btn1"><i class="fa fa-pencil"></i> Edit Details</a>
  								&nbsp;
  								<a href="page.php?page=change-password" class="btn btn-warning btn1"><i class="fa fa-pencil"></i> Change Password</a>
  								&nbsp;
  								<a href="page.php?page=viewResume&id=<?= $STUDENT_ID ?>" class="btn  btn-success btn1" target="_blank"><i class="fa fa-file-pdf-o"></i> Generate Resume</a>

  							</p>
  							<div class="clearfix"></div>

  							<table class="table table-bordered">
  								<tr>
  									<td><img src="<?= $STUD_PHOTO ?>" style="width:180px; height:180px;    border-radius: 0%;" class="img img-thumbnail img-rounded" /></td>
  									<td><img src="<?= $STUD_SIGN ?>" style="width:250px; border-radius: 0%;height: auto;" /></td>
  								</tr>
  								<tr>
  									<th>Student Name</th>
  									<td><?= $STUDENT_FULLNAME ?></td>

  								</tr>
  								<tr>
  									<th>Institute Name</th>
  									<td colspan="2"><?= $INSTITUTE_NAME ?></td>

  								</tr>
  								<tr>
  									<th>Student Code</th>
  									<td colspan="2"><?= $STUDENT_CODE ?></td>

  								</tr>
  								<tr>
  									<th>Institute Code</th>
  									<td colspan="2"><?= $INSTITUTE_CODE ?></td>

  								</tr>
  								<tr>
  									<th>Username</th>
  									<td colspan="2"><?= $USER_NAME ?></td>

  								</tr>
  								<tr>
  									<th>Date of birth</th>
  									<td colspan="2"><?= $STUD_DOB_FORMATED ?></td>

  								</tr>
  								<tr>
  									<th>Mobile</th>
  									<td colspan="2"><?= $STUDENT_MOBILE ?></td>

  								</tr>
  								<tr>
  									<th>Email</th>
  									<td colspan="2"><?= $STUDENT_EMAIL ?></td>

  								</tr>
  								<tr>
  									<th>Address</th>
  									<td colspan="2"><?= $STUDENT_TEMP_ADD . ' ' . $STUDENT_PER_ADD . ' ' ?></td>

  								</tr>
  							</table>

  						</div>

  					</div>
  				</div>



  			</section>
  			<!-- right col -->
  		</div>
  		<!-- /.row (main row) -->

  	</section>
  	<!-- /.content -->
  </div>