 <?php

include_once('include/classes/student.class.php');

include_once('include/classes/employer.class.php');

$employer = new employer();	

$student = new student();	

$student_id 	= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';





$action = isset($_POST['upload'])?$_POST['upload']:'';

if($action!='')

{

	$stud_id 		= isset($_POST['stud_id'])?$_POST['stud_id']:'';

	$result= $student->store_file($stud_id);

	$result = json_decode($result, true);

	$success = isset($result['success'])?$result['success']:'';

	$message = isset($result['message'])?$result['message']:'';

	$errors = isset($result['errors'])?$result['errors']:'';

	if($success<a href="page.php?page=

	{

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

      List All Job Posts

      

      </h1>

      <ol class="breadcrumb">

        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>       

        <li>Jobs</li>

        <li class="active">List All Jobs</li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

		<?php

			if(isset($_SESSION['msg']))

			{

				$message = isset($_SESSION['msg'])?$_SESSION['msg']:'';

				$msg_flag =$_SESSION['msg_flag'];

			?>

			<div class="row">

			<div class="col-sm-12">

			<div class="alert alert-<?= ($msg_flag==true)?'success':'danger' ?> alert-dismissible" id="messages">

                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>

                <h4><i class="icon fa fa-check"></i> <?= ($msg_flag==true)?'Success':'Error' ?>:</h4>

				<?= ($message!='')?$message:'Sorry! Something went wrong!'; ?>

            </div>

			 </div>

			 </div>

			<?php

			unset($_SESSION['msg']);

			unset($_SESSION['msg_flag']);

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

<!-- <th>Job Code</th> -->

					<th>Job Title</th>

					<th>Skills Required</th>

					<th>Employer</th>

					<th>Added On</th>

				<!--	<th>Status</th> -->

					<th>Action</th>

				</tr>

                </thead>

                <tbody>

			<?php

		

		

			$res = $employer->list_jobs('','',' AND A.ACTIVE=1');

			if($res!='')

			{

				$srno=1;

				$stud_name = $student->get_stud_name($student_id);

				while($data = $res->fetch_assoc())

				{

					extract($data);

					

					//$applyLink = "<a href='page.php?page=update-job&id=$JOB_POST_ID' class='btn btn-xs btn-primary' title='Apply'>Apply</a>";

					$applyLink = "<a href='javascript:void(0)' class='btn btn-link apply-job-email' title='Send Email' data-toggle='modal' data-target='.bs-example-modal-md' data-email='$EMAIL' data-id='$JOB_POST_ID' data-name='$stud_name - $JOB_TITLE'><i class=' fa fa-envelope'></i></a>";

					

					echo " <tr id='row-$JOB_POST_ID'>

							<td>$srno</td>

							

							<!-- <td>$JOB_POST_CODE</td> -->

							<td>".ucwords(strtoupper($JOB_TITLE))."</td>

							<td>".ucwords(strtoupper($JOB_SKILLS))."</td>

							<td>$EMPLOYER_COMPANY_NAME</td>

							<td>$CREATED_DATE</td>

							

							<!-- <td id='status-$JOB_POST_ID'>$JOB_STATUS</td> -->

							<td>$applyLink</td>								

                           </tr>

						  

						   ";	

						   $srno++;

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

  <?php

	include('include/classes/emails.class.php');

	$emails = new emails();

	$action = isset($_POST['action'])?$_POST['action']:'';

	if($action!='')

	{

		$res = $emails->apply_for_job();

			

	}

  ?>

  

  <!-- modal to send email -->

  	<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">

		 

		  <img src="resources/dist/img/loader.gif" class="loader-mg-modal" />

		  <div class="modal-dialog modal-md" role="document">

			<div class="modal-content">

			 

			  <div class="box box-primary modal-body">

				 <div class="">

					<div class="box-header with-border">

					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

					  <h3 class="box-title">Compose New Message</h3>

					</div>

					<!-- /.box-header -->

					<form action="" method="post">		

					<input type="hidden" name="student_name" id="student_name" value="<?php echo $_SESSION['user_fullname'] ?>" />					

					<input type="hidden" name="job_post_id" id="job_post_id" value="" />					

					<input type="hidden" name="action" id="action" value="send_email" />

						<div class="box-body">

						  <div class="form-group" id="email-error">

							<input class="form-control" placeholder="To:" id="emp_email" name="emp_email" readonly >

							<p class="help-block"></p>

						  </div>

						  <div class="form-group">

							<input class="form-control" placeholder="Subject:" id="subject" name="subject">

						  </div>

						  <div class="form-group" id="msg-error">

								<textarea id="compose-textarea" class="form-control" name="message" id="message" style="height: 150px">

								Hi there,<br>

								Please click the link for downloading resume.

								<br><br>

								<br><br>

								

								Regards,<br>

								<?php echo $_SESSION['user_fullname']; ?>

								</textarea>

								<p class="help-block"></p>

						  </div>

						   <div class="form-group">

						   <?php

								$resume = $student->get_stud_resume($student_id);								

								$resumeLink='';

								if($resume!='')

								{

									$resumeLink = STUDENT_RESUME_DOWNLOAD.'/'.$student_id.'/'.$resume;

																		

								}

							?>			

							<input class="form-control" type="text" name="resume" value="<?= $resumeLink ?>" />

						  </div>

						  <div class="form-group msg">							

							<p class="help-block"></p>

						  </div>

						</div>

					

					<!-- /.box-body -->

					<div class="box-footer">

					  <div class="pull-right">

						<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>

						<button type="submit" name="send" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>

					  </div>					 

					</div>

					</form>

					<!-- /.box-footer -->

				  </div>

				 </div>

			</div>

		  </div>

		</div>

  