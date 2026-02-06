<?php 

//include('include/controller/institute/staff/add_staff.php');

?>

<?php

$payment_id = isset($_GET['payid'])?$_GET['payid']:'';

$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  

$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';

if($user_role==5){

   $institute_id = $db->get_parent_id($user_role,$user_id);

   $staff_id = $user_id;

}

else{

   $institute_id = $user_id;

   $staff_id = 0;

}



$student_id = $db->test(isset($_GET['id'])?$_GET['id']:'');

$action= isset($_POST['action'])?$_POST['action']:'';
header('location:page.php?page=
include_once('include/classes/student.class.php');

	

include_once('include/classes/institute.class.php');

	$institute = new institute();

	$student = new student();

if($action!='')

{

	

	$result= $institute->update_student_payment();

	$result = json_decode($result, true);

	$success = isset($result['success'])?$result['success']:'';

	$message = isset($result['message'])?$result['message']:'';

	$errors = isset($result['errors'])?$result['errors']:'';

	if($success==true)

	{

		$_SESSION['msg'] = $message;

		$_SESSION['msg_flag'] = $success;

		header('location:page.php?page=list-student-payments');

	}	



}



		

$payments = $institute->list_student_payments($payment_id,'',$institute_id, '', '');

if($payments!='')

{

	$courseSrNo = 1;

		while($courseData = $payments->fetch_assoc())

		{

			extract($courseData);

			$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);	

		

		}

}		



?>

 <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Update Student Payment   

      </h1>

      <ol class="breadcrumb">

        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="page.php?page=list-students">Students</a></li>

        <li><a href="page.php?page=list-student-payments"> List Student Payments</a></li>

        <li class="active">Update Student Payment</li>

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

	<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">

        <div class="col-md-6">

          <!-- general form elements -->

          <div class="box box-primary">

            <div class="box-header with-border">

              <h3 class="box-title">Update Student Payment</h3>

            </div>

           

               <div class="box-body">

				<input type="hidden" name="payment_id" id="payment_id" value="<?= $PAYMENT_ID ?>" />

				<input type="hidden" name="staff_id" id="staff_id" value="<?= $staff_id ?>" />

				<input type="hidden" name="institute_id" id="institute_id" value="<?= $institute_id ?>" />

				<input type="hidden" name="course_fees" id="course_fees" value="<?= $COURSE_FEES ?>" />

				

				

			    <div class="form-group <?= (isset($errors['student_id']))?'has-error':'' ?>">

                  <label for="student_id">Student Name</label>

                <?php 

				$student_id = isset($_POST['student_id'])?$_POST['student_id']:$STUDENT_ID;

				?>

				 <select class="form-control select2" name="student_id" id="student_id" onchange="getStudentAllCourses(this.value); getStudPaymentInfo(); getBalAmtCourse();">

					  <?php echo $db->MenuItemsDropdown ('student_payments',"STUDENT_ID","STUD_NAME","DISTINCT STUDENT_ID, get_student_name(STUDENT_ID) AS STUD_NAME",$student_id," WHERE INSTITUTE_ID='$institute_id'"); ?>

					</select>

					<span class="help-block"><?= isset($errors['student_id'])?$errors['student_id']:'' ?></span>

                </div>

               

                <div class="form-group <?= (isset($errors['course']))?'has-error':'' ?>">

                  <label for="course">Select Course</label>

				  <?php $course = isset($_POST['course'])?$_POST['course']:''; ?>

                  <select class="form-control select2" name="course" id="course" onchange="getStudPaymentInfo(); getBalAmtCourse();">

				  <?php

				  if($student_id!='')

				  {

					$course = isset($_POST['course'])?$_POST['course']:$INSTITUTE_COURSE_ID;

					$res = $student->get_student_allcourses($student_id);

					if($res!='')

					{

						echo '<option value="">--select--</option>';

						while($data = $res->fetch_assoc())

						{

							$INSTITUTE_COURSE_ID1 = $data['INSTITUTE_COURSE_ID'];					

							$output = $db->get_inst_course_info($INSTITUTE_COURSE_ID1);

							if(!empty($output))

							{

								$selected = ($course==$INSTITUTE_COURSE_ID1)?'selected="selected"':'';

								echo '<option value="'.$INSTITUTE_COURSE_ID1.'" '.$selected.'>'.$output['COURSE_NAME'].'</option>';						

							}

						}

					}	

				  }

				  ?>

					</select>				  

					<span class="help-block"><?= isset($errors['course'])?$errors['course']:'' ?></span>

                </div>

				<div class="form-group  <?= (isset($errors['amountpaid']))?'has-error':'' ?>">

                  <label for="amountpaid">Recieved Payment Amount</label>

					<input type="text" name="amountpaid" id="amountpaid" class="form-control" value="<?= isset($_POST['amountpaid'])?$_POST['amountpaid']:$FEES_PAID; ?>" onchange="calBalAmt()"  />

					<span class="help-block"><?= isset($errors['amountpaid'])?$errors['amountpaid']:'' ?></span>

                </div>				

				<div class="form-group  <?= (isset($errors['amountbalance']))?'has-error':'' ?>">

                  <label for="amountpaid">Total Balance Amount</label>

					<input type="text" name="amountbalance" id="amountbalance" class="form-control" value="<?= isset($_POST['amountbalance'])?$_POST['amountbalance']:$FEES_BALANCE; ?>"  />

					<span class="help-block"><?= isset($errors['amountbalance'])?$errors['amountbalance']:'' ?></span>

                </div>

				<div class="form-group  <?= (isset($errors['paymentnote']))?'has-error':'' ?>">

                  <label for="paymentnote">Any Notes:</label>					

					<textarea name="paymentnote" id="paymentnote" class="form-control"><?= isset($_POST['paymentnote'])?$_POST['paymentnote']:$PAYMENT_NOTE; ?></textarea>

					<span class="help-block"><?= isset($errors['paymentnote'])?$errors['paymentnote']:'' ?></span>

                </div>

                </div>		

				

              <!-- /.box-body -->



              <div class="box-footer text-center">	

				<input type="submit" class="btn btn-primary" name="action" value="Update Payment" />		 &nbsp;&nbsp;&nbsp;	  

				<a href="page.php?page=list-student-payments" class="btn btn-warning" title="Cancel">Cancel</a>

              </div>           

          </div>

        </div>

		 <div class="col-md-6">

		  <div class="box box-primary">

            <div class="box-header with-border">

              <h3 class="box-title">Payment History</h3>

            </div>  

			<div class="box-body" id="payment_info">

				 <!--

				 <div class="form-group  <?= (isset($errors['amountpaidon']))?'has-error':'' ?>">

                  <label for="amountpaidon">Payment Date</label>

					<input type="hidden" name="amountpaidon" id="amountpaidon" class="form-control" placeholder="Choose payment date" value="<?= $access->curr_date() ?>" readonly />

					<span class="help-block"><?= isset($errors['amountpaidon'])?$errors['amountpaidon']:'' ?></span>

                </div>

				-->

				

			</div>

		 </div>

		 </div> 

		 <!--

		 <div class="col-md-6">

		  <div class="box box-primary">

            <div class="box-header with-border">

              <h3 class="box-title">Course Info</h3>

            </div>  

			<div class="box-body" id="course-info">

				

			</div>

		 </div>

		 </div> -->

		 </form>

        <!--/.col (left) -->

      

        <!--/.col (right) -->

      </div>

      <!-- /.row -->

    </section>

    <!-- /.content -->

  </div>

 