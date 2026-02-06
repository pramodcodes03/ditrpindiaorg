<?php

	include('include/classes/exam.class.php');

	$exam = new exam();

	$result_id = isset($_GET['id'])?$_GET['id']:'';

	

	$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  

	$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';

	if($user_role==3){

	   $institute_id = $db->get_parent_id($user_role,$user_id);

	   $staff_id = $user_id;

	}

	else{

	   $institute_id = $user_id;

	   $staff_id = 0;

	}

	

	$action		= isset($_POST['save'])?$_POST['save']:'';		

	if($action!='')

	{		

		$result= $exam->update_offline_exam_marks();

		$result = json_decode($result, true);

		$success = isset($result['success'])?$result['success']:'';

		$message = isset($result['message'])?$result['message']:'';

		$errors = isset($result['errors'])?$result['errors']:'';

		if($success==true)

		{

			$_SESSION['msg'] = $message;

			$_SESSION['msg_flag'] = $success;

			header('location:page.php?page=list-exam-results');

		}

	}

	 $PHOTO = SHOW_IMG_AWS.'/default_user.png';	

	

	$res 	= $exam->list_student_exam_results($result_id, '', '', '', '');

	 if($res!='')

	 {

		 while($data = $res->fetch_assoc())

		 {

			extract($data);							

			if($STUDENT_PHOTO!='')

				$PHOTO = SHOW_IMG_AWS.STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;

		 }

	 }

?>

 <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1> Update Exam Result </h1>

      <ol class="breadcrumb">

        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="page.php?page=list-exam-results">Exams</a></li>

        <li class="active"> Update Exam Result</li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

	<?php

	if(isset($success))

	{		

	?>

	<div class="row">

		<div class="col-sm-12">

		<div class="alert alert-<?= ($success==true)?'success':'danger' ?> alert-dismissible" id="messages">

			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>

			<h4><i class="icon fa fa-check"></i> <?= ($success==true)?'Success':'Error' ?>:</h4>

			<?= isset($message)?$message:'Please correct the errors.'; ?>

		</div>

		 </div>

	 </div>

	<?php

	}

	?>

      <div class="row">

        <div class="col-md-8">

		 <form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">

          <div class="box box-primary">

            <div class="box-header with-border">

              <h3 class="box-title">Update Exam Result</h3>

            </div>

            <div class="box-body">			 

				<input type="hidden" name="exam_result_id" value="<?= $EXAM_RESULT_ID ?>" />	

				<input type="hidden" name="student_id" value="<?= $STUDENT_ID ?>" />				

				<input type="hidden" id="exam_total_que" name="exam_total_que" value="<?= $EXAM_TOTAL_QUE ?>" />

				<input type="hidden" name="exam_secrete_code" value="<?= $EXAM_SECRETE_CODE ?>" />

				<input type="hidden" id="exam_total_marks" name="exam_total_marks" value="<?= $EXAM_TOTAL_MARKS ?>" />

				<input type="hidden" id="exam_marks_per_que" name="exam_marks_per_que" value="<?= $EXAM_MARKS_PER_QUE ?>" />

				

				<div class="form-group <?= (isset($errors['totalcorrect']))?'has-error':'' ?>">

                  <label for="inputEmail3" class="col-sm-4 control-label">Total Correct Answers</label>

                  <div class="col-sm-8">

                    <input class="form-control" id="totalcorrect" placeholder="Total correct answers" type="text" name="totalcorrect" value="<?= isset($_POST['totalcorrect'])?$_POST['totalcorrect']:$CORRECT_ANSWER ?>" onkeyup="calOfflineResult()">

					<span class="help-block"><?= (isset($errors['totalcorrect']))?$errors['totalcorrect']:'' ?></span>

                  </div>

                </div>

				<div class="form-group <?= (isset($errors['totalincorrect']))?'has-error':'' ?>">

                  <label for="inputEmail3" class="col-sm-4 control-label">Total In-Correct Answers</label>

                  <div class="col-sm-8">

                    <input class="form-control" id="totalincorrect" placeholder="Total In-Correct answers" type="text" name="totalincorrect" value="<?= isset($_POST['totalincorrect'])?$_POST['totalincorrect']:$INCORRECT_ANSWER ?>">

					<span class="help-block"><?= (isset($errors['totalincorrect']))?$errors['totalincorrect']:'' ?></span>

                  </div>

                </div>

				<!--

				<div class="form-group <?= (isset($errors['scananswersheet']))?'has-error':'' ?>">

                  <label for="inputEmail3" class="col-sm-4 control-label">Upload Answer Sheet Proof:</label>

                  <div class="col-sm-8">

                    <input type="file" name="scananswersheet">

					<span class="help-block"><?= (isset($errors['scananswersheet']))?$errors['scananswersheet']:'' ?></span>

					<?php

					if($OFFLINE_ANS_PROOF!='')

					{

						$path = EXAM_OFFLINE_PAPER_PATH.'/'.$STUDENT_ID.'/'.$OFFLINE_ANS_PROOF;

						echo '<a href="$path" class="btn btn-xs bg-purple">Download</a><br>';

					}

					

					?>

                  </div>

                </div>

				-->

				<div class="form-group <?= (isset($errors['marksobt']))?'has-error':'' ?>">

                  <label for="inputEmail3" class="col-sm-4 control-label">Total Marks Obtained</label>

                  <div class="col-sm-8">

                    <input class="form-control" id="marksobt" placeholder="Total Marks obtained" type="text" name="marksobt" value="<?= isset($_POST['marksobt'])?$_POST['marksobt']:$MARKS_OBTAINED ?>" readonly>

					<span class="help-block"><?= (isset($errors['marksobt']))?$errors['marksobt']:'' ?></span>

                  </div>

                </div>

				<div class="form-group <?= (isset($errors['marks_per']))?'has-error':'' ?>">

                  <label for="inputEmail3" class="col-sm-4 control-label">Percentage</label>

                  <div class="col-sm-8">

                    <input class="form-control" id="marks_per" placeholder="Percentage" type="text" name="marks_per" value="<?= isset($_POST['marks_per'])?$_POST['marks_per']:$MARKS_PER ?>" readonly>

					<span class="help-block"><?= (isset($errors['marks_per']))?$errors['marks_per']:'' ?></span>

                  </div>

                </div>

				<div class="form-group <?= (isset($errors['grade']))?'has-error':'' ?>">

                  <label for="inputEmail3" class="col-sm-4 control-label">Grade</label>

                  <div class="col-sm-8">

                    <input class="form-control" id="grade" placeholder="Grade" type="text" name="grade" value="<?= isset($_POST['grade'])?$_POST['grade']:$GRADE ?>" readonly>

					<span class="help-block"><?= (isset($errors['grade']))?$errors['grade']:'' ?></span>

                  </div>

                </div>

				<div class="form-group <?= (isset($errors['result_status']))?'has-error':'' ?>">

                  <label for="inputEmail3" class="col-sm-4 control-label">Result Status</label>

                  <div class="col-sm-8">

                    <input class="form-control" id="result_status" placeholder="Result status" type="text" name="result_status" value="<?= isset($_POST['result_status'])?$_POST['result_status']:$RESULT_STATUS ?>" readonly>

					<span class="help-block"><?= (isset($errors['result_status']))?$errors['result_status']:'' ?></span>

                  </div>

                </div>

				

				

			</div>             

			  <div class="box-footer text-center">					 

				<input type="submit" class="btn btn-primary" name="save" value="Save" />&nbsp;&nbsp;&nbsp;			

				<a href="page.php?page=list-exam-results" class="btn btn-danger" title="Cancel">Cancel</a>		

			  </div>           

          </div>

		   </form>         

        </div>	

			  

	   <div class="col-md-4">

	    <div class="box box-primary">

            <div class="box-header with-border">

              <h3 class="box-title">Exam Details</h3>

            </div>

           

		<table class="table">

			<tr>

				<th>Photo</th>

				<td><img src="<?= $PHOTO ?>" class='img img-responsive img-circle' style='width:50px; height:50px'></td>

			</tr>

			<tr>

				<th>Student Name</th>

				<td><?= $STUDENT_NAME ?></td>

			</tr>

			<tr>

				<th>Exam Name</th>

				<td><?= $EXAM_TITLE ?></td>

			</tr>

			

			<tr>

				<th>Total Questions</th>

				<td><?= $EXAM_TOTAL_QUE ?></td>

			</tr>

			<tr>

				<th>Total Marks</th>

				<td><?= $EXAM_TOTAL_MARKS ?></td>

			</tr>

			<tr>

				<th>Total Passing Marks</th>

				<td><?= $EXAM_PASSING_MARKS ?></td>

			</tr>

			<tr>

				<th>Marks Per Question</th>

				<td><?= $EXAM_MARKS_PER_QUE ?></td>

			</tr>

		</table>

	   </div>

	   </div>

      </div>



	   </div>

      <!-- /.row -->

    </section>

    <!-- /.content -->

  </div>