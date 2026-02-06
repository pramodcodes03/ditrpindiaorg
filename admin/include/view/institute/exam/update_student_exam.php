<?php 

//include('include/controller/institute/staff/add_staff.php');

?>

<?php

$course_detail_id = $db->test(isset($_GET['id'])?$_GET['id']:'');



$action= isset($_POST['action'])?$_POST['action']:'';

include_once('include/classes/student.class.php');

	$student = new student();



if($action!='')

{

	include_once('include/classes/exam.class.php');

	$exam = new exam();

	$student_id 	= $db->test(isset($_POST['student_id'])?$_POST['student_id']:'');

	$result= $exam->update_student_exam();

	$result = json_decode($result, true);

	$success = isset($result['success'])?$result['success']:'';

	$message = isset($result['message'])?$result['message']:'';

	$errors = isset($result['errors'])?$result['errors']:'';

	if($success==true)

	{

		$_SESSION['msg'] = $message;

		$_SESSION['msg_flag'] = $success;

		header('location:page.php?page=list-exams');

	}	



}

$courses = $student->list_student_courses($course_detail_id,'');

if($courses!='')

{

	

		while($courseData = $courses->fetch_assoc())

		{

			extract($courseData);		

			$COURSE_NAME 	= $db->get_inst_course_name($INSTITUTE_COURSE_ID);

		}

}

?>

 <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Update Student Exam

      

      </h1>

      <ol class="breadcrumb">

        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="page.php?page=list-exams">Exams</a></li>

        <li class="active">Update Student Exam</li>

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

        <!-- left column -->

		

        <div class="col-md-2"></div>

        <div class="col-md-8">

          <!-- general form elements -->

          <div class="box box-primary">

            <div class="box-header with-border">

              <h3 class="box-title">Update Student Exam</h3>

            </div>        

		

            <form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">

               <div class="box-body">

			   <input type="hidden" name="course_detail_id" value="<?= $STUD_COURSE_DETAIL_ID ?>" />

			   <input type="hidden" name="student_id" value="<?= $STUDENT_ID ?>" />

			    <div class="form-group">

                  <label for="student_name">Student Name</label>

                  <input type="text" name="student_name" class="form-control" value="<?= $STUDENT_NAME ?>" id="student_name" readonly="readonly" disbaled="disabled">				 

				

                </div>



                <div class="form-group <?= (isset($errors['course']))?'has-error':'' ?>">

                  <label for="course">Select Course</label>

				  <?php $course = isset($_POST['course'])?$_POST['course']:$INSTITUTE_COURSE_ID; ?>

                  <select class="form-control select2" name="course" id="course" reaonly disabled>

					   <?php echo $db->MenuItemsDropdown ('student_course_details B',"INSTITUTE_COURSE_ID","COURSE_NAME","DISTINCT B.INSTITUTE_COURSE_ID, (SELECT A.COURSE_NAME FROM courses A WHERE A.COURSE_ID=(SELECT C.COURSE_ID FROM institute_courses C WHERE C.INSTITUTE_COURSE_ID=B.INSTITUTE_COURSE_ID)) AS COURSE_NAME",$course," WHERE B.STUDENT_ID=$STUDENT_ID"); ?>

					</select>				  

					<span class="help-block"><?= isset($errors['course'])?$errors['course']:'' ?></span>

                </div>

				 <div class="form-group <?= (isset($errors['examstatus']))?'has-error':'' ?>">

                  <label for="course">Select Exam Status</label>

				  <?php $examstatus = isset($_POST['examstatus'])?$_POST['examstatus']:$EXAM_STATUS; ?>

                  <select class="form-control select2" name="examstatus" id="examstatus">

					  <?php echo $db->MenuItemsDropdown ('exam_status_master',"EXAM_STATUS_ID","EXAM_STATUS","EXAM_STATUS_ID, EXAM_STATUS",$examstatus,""); ?>

					</select>				  

					<span class="help-block"><?= isset($errors['examstatus'])?$errors['examstatus']:'' ?></span>

                </div>

				 <div class="form-group <?= (isset($errors['examtype']))?'has-error':'' ?>">

                  <label for="examtype">Select Exam Type</label>

				  <?php $examtype = isset($_POST['examtype'])?$_POST['examtype']:$EXAM_TYPE; ?>

                  <select class="form-control select2" name="examtype" id="examtype">

					  <?php echo $db->MenuItemsDropdown ('exam_types_master',"EXAM_TYPE_ID","EXAM_TYPE","EXAM_TYPE_ID,EXAM_TYPE",$examtype,""); ?>

					</select>			  

					<span class="help-block"><?= isset($errors['examtype'])?$errors['examtype']:'' ?></span>

                </div>

                </div>			

				

              <!-- /.box-body -->



              <div class="box-footer text-center">	

				<input type="submit" class="btn btn-primary" name="action" value="Update Exam" />		 &nbsp;&nbsp;&nbsp;	  

				<a href="page.php?page=list-exams" class="btn btn-warning" title="Cancel">Cancel</a>

              </div>

           </form>

          </div>

		   

          <!-- /.box -->



    

          <!-- /.box -->



          <!-- /.box -->



          <!-- /.box -->



        </div>

		 

        <!--/.col (left) -->

      

        <!--/.col (right) -->

      </div>

      <!-- /.row -->

    </section>

    <!-- /.content -->

  </div>