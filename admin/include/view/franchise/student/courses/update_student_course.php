<?php 

//include('include/controller/institute/staff/add_staff.php');

?>

<?php

$student_id = $db->test(isset($_GET['stud'])?$_GET['stud']:'');

$cdetail_id = $db->test(isset($_GET['cdetail'])?$_GET['cdetail']:'');

$action= isset($_POST['action'])?$_POST['action']:'';

include_once('include/classes/student.class.php');

	$student = new student();

if($action!='')

{

	$student_id 	= $db->test(isset($_POST['student_id'])?$_POST['student_id']:'');

	$result= $student->update_student_course();

	$result = json_decode($result, true);

	$success = isset($result['success'])?$result['success']:'';

	$message = isset($result['message'])?$result['message']:'';

	$errors = isset($result['errors'])?$result['errors']:'';

	if($success==true)

	{

		$_SESSION['msg'] = $message;

		$_SESSION['msg_flag'] = $success;

		header('location:page.php?page=list-student-courses&id='.$student_id);

	}	



}

$courses = $<a href="page.php?page=list_student_courses($cdetail_id,$student_id,'');
<a href="page.php?page=
if($courses!<a href="page.php?page=
<a href="page.php?page=
{

	

		while($courseData = $courses->fetch_assoc())

		{

			$STUD_COURSE_DETAIL_ID = $courseData['STUD_COURSE_DETAIL_ID'];

			$STUDENT_ID 	= $courseData['STUDENT_ID'];

			$COURSE_ID 	= $courseData['COURSE_ID'];

			$COURSE_TYPE = $courseData['COURSE_TYPE'];

			$ACTIVE = $courseData['ACTIVE'];

			$EXAM_STATUS = $courseData['EXAM_STATUS'];

			$EXAM_TYPE = $courseData['EXAM_TYPE'];

			$COURSE_TYPE_NAME = $courseData['COURSE_TYPE_NAME'];

		}

}

?>

 <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Update Student Course

      

      </h1>

      <ol class="breadcrumb">

        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="page.php?page=list-students">Students</a></li>

        <li><a href="page.php?page=list-student-courses&id=<?= $student_id ?>"> List Student Courses</a></li>

        <li><a href="#"><?= $db->get_stud_name($student_id) ?></a></li>

        <li class="active">Update Course</li>

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
<a href="page.php?page=
        <!-- left column -->

		

        <div class="col-md-2"></div>

        <div class="col-md-8">

          <!-- general form elements -->

          <div class="box box-primary">

            <div class="box-header with-border">

              <h3 class="box-title">Update Student Course</h3>

            </div>        

		

            <form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">

               <div class="box-body">

			   <input type="hidden" name="course_detail_id" value="<?= $STUD_COURSE_DETAIL_ID ?>" />

			   <input type="hidden" name="student_id" value="<?= $STUDENT_ID ?>" />

			    <div class="form-group">

                  <label for="student_name">Student Name</label>

                  <input type="text" name="student_name" class="form-control" value="<?= $db->get_stud_name($STUDENT_ID) ?>" id="student_name" readonly="readonly" disbaled="disabled">				 

				

                </div>

                <div class="form-group <?= (isset($errors['course_type']))?'has-error':'' ?>">

                  <label for="course_type">Select Course Type</label>

				  <?php $course_type =isset($_POST['course_type'])?$_POST['course_type']:$COURSE_TYPE; ?>

                  <select class="form-control" name="course_type" id="course_type" onchange="getInstituteCourses(this.value)">

					  <?php echo $db->MenuItemsDropdown ('course_type_master',"COURSE_TYPE_ID","COURSE_TYPE","COURSE_TYPE_ID, COURSE_TYPE",$course_type,""); ?>

					</select>				  

				<span class="help-block"><?= isset($errors['course_type'])?$errors['course_type']:'' ?></span>

                </div>

                <div class="form-group <?= (isset($errors['course']))?'has-error':'' ?>">

                  <label for="course">Select Course</label>

				  <?php $course = isset($_POST['course'])?$_POST['course']:$COURSE_ID; ?>

                  <select class="form-control select2" name="course" id="course">

					  <?php echo $db->MenuItemsDropdown ('institute_courses A LEFT JOIN courses B ON A.COURSE_ID=B.COURSE_ID',"COURSE_ID","COURSE_NAME","A.COURSE_ID, B.COURSE_NAME",$course," WHERE A.INSTITUTE_ID ='".$_SESSION['user_id']."' AND A.DELETE_FLAG=0 AND A.ACTIVE=1 ORDER BY B.COURSE_NAME ASC"); ?>

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

				<div class="form-group">

				 <label for="status">Status</label>

				 <?php

				$ACTIVE =  isset($_POST['status'])?$_POST['status']:$ACTIVE;

				 ?>

                  <div class="radio">

                    <label>

                      <input name="status" id="status1"  value="1" <?= ($ACTIVE==1)?'checked="checked"':'' ?> type="radio">

                      Active

                    </label>

					 <label>

                      <input name="status" id="status2" value="0" <?= ($ACTIVE==0)?'checked="checked"':'' ?> type="radio">

                     In-Active

                    </label>

                  </div>                

                </div>

		

				

                </div>			

				

              <!-- /.box-body -->



              <div class="box-footer text-center">	

				<input type="submit" class="btn btn-primary" name="action" value="Update Course" />		 &nbsp;&nbsp;&nbsp;	  

				<a href="page.php?page=list-student-courses&id=<?= $student_id ?>" class="btn btn-warning" title="Cancel">Cancel</a>

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