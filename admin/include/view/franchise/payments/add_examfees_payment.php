<?php 

//include('include/controller/institute/staff/add_staff.php');

?>

<?php

$reqid = $db->test(isset($_GET['reqid'])?$_GET['reqid']:'');

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

$action= isset($_POSTPOST['action']:'';

include_once('include/classes/student.class.php');

	

include_once('include/classes/admin.class.php');

$admin = new admin();

$student = new student();

if($action!='')

{	

	$result= $admin->add_institute_payment();

	$result = json_decode($result, true);

	$success = isset($result['success'])?$result['success']:'';

	$message = isset($result['message'])?$result['message']:'';

	$errors = isset($result['errors'])?$result['errors']:'';

	if($success==true)

	{

		$_SESSION['msg'] = $message;

		$_SESSION['msg_flag'] = $success;

		//header('location:page.php?page=list-student-payments');

	}	





}

//$sql ="SELECT A.*, get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME,DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,get_institute_city(A.INSTITUTE_ID) AS INSTITUTE_CITY, A.TOTAL_EXAM_FEES FROM certificate_requests_master A WHERE A.DELETE_FLAG=0 AND A.CERTIFICATE_REQUEST_MASTER_ID='$reqid' ORDER BY A.CREATED_ON DESC";



//$sql  = "SELECT A.* ,get_institute_code(A.INSTITUTE_ID) AS INSTITUTE_CODE,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME,DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y %h:%i:%p') AS CREATED_DATE,get_institute_city(A.INSTITUTE_ID) AS INSTITUTE_CITY  FROM institute_payments WHERE A.CERTIFICATE_REQUEST_MASTER_ID='$reqid' AND A.DELETE_FLAG=0 ORDER BY A.PAYMENT_ID DESC LIMIT 0,1";



$res = $admin->list_institute_payments($reqid,' ORDER BY A.PAYMENT_ID DESC LIMIT 0,1');

if($res!='')

{

	while($data = $res->fetch_assoc())

	{

		extract($data);

	}

	$totals = $admin->get_payment_totals($reqid,$INSTITUTE_ID);

	if(!empty($totals))

	{

		$total_fees 	= $totals['total_fees'];

		$total_recieved = $totals['total_recieved'];

		$total_balance 	= $totals['total_balance'];

	}

}

?>

 <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Add Exam Fees Payment      

      </h1>

      <ol class="breadcrumb">

        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="page.php?page=list-payments">Payments</a></li>

      

        <li class="active">Add Exam Fees Payment</li>

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

              <h3 class="box-title">Add Exam Fees Payment</h3>

            </div>

           

               <div class="box-body">

				

				

				<input type="hidden" name="reqid" id="reqid" value="<?= $CERTIFICATE_REQUEST_MASTER_ID ?>" />

				<input type="hidden" name="institute_id" id="institute_id" value="<?= isset($_POST['institute'])?$_POST['institute']:$INSTITUTE_ID ?>" />

			    <input type="hidden" name="totalexamfees" id="totalexamfees" class="form-control" value="<?= isset($_POST['totalexamfees'])?$_POST['totalexamfees']:$total_fees; ?>" readonly />      

               

						

				<div class="form-group  <?= (isset($errors['totalamtrecieved']))?'has-error':'' ?>">

                  <label for="totalamtrecieved">Enter Recieved Amount</label>

					<input type="text" name="totalamtrecieved" id="totalamtrecieved" class="form-control" value="<?= isset($_POST['totalamtrecieved'])?$_POST['totalamtrecieved']:''; ?>" onkeyup="calTotalBalAmt();" placeholder="" />

					<span class="help-block"><?= isset($errors['totalamtrecieved'])?$errors['totalamtrecieved']:'' ?></span>

                </div>

				<div class="form-group  <?= (isset($errors['totalamtbalance']))?'has-error':'' ?>">

                  <label for="totalamtbalance">Total Amount Balance</label>

					<input type="text" name="totalamtbalance" id="totalamtbalance" class="form-control" value="<?= isset($_POST['totalamtbalance'])?$_POST['totalamtbalance']:$total_balance; ?>" readonly />

					<span class="help-block"><?= isset($errors['totalamtbalance'])?$errors['totalamtbalance']:'' ?></span>

                </div>

				

				<div class="form-group  <?= (isset($errors['paymentdate']))?'has-error':'' ?>">

                  <label for="paymentdate">Payment Date</label>

					<input type="text" name="paymentdate" id="doj" class="form-control" value="<?= isset($_POST['paymentdate'])?$_POST['paymentdate']:date('d-m-Y'); ?>"  />

					<span class="help-block"><?= isset($errors['paymentdate'])?$errors['paymentdate']:'' ?></span>

                </div>

				<div class="form-group  <?= (isset($errors['paymentmode']))?'has-error':'' ?>">

                  <label for="paymentmode">Payment Mode</label>

					<input type="text" name="paymentmode" id="paymentmode" class="form-control" value="<?= isset($_POST['paymentmode'])?$_POST['paymentmode']:''; ?>"  />

					<span class="help-block"><?= isset($errors['paymentmode'])?$errors['paymentmode']:'' ?></span>

                </div>

				<div class="form-group  <?= (isset($errors['chequeno']))?'has-error':'' ?>">

                  <label for="chequeno">Cheque / DD number</label>

					<input type="text" name="chequeno" id="chequeno" class="form-control" value="<?= isset($_POST['chequeno'])?$_POST['chequeno']:''; ?>"  />

					<span class="help-block"><?= isset($errors['chequeno'])?$errors['chequeno']:'' ?></span>

                </div>

				<div class="form-group  <?= (isset($errors['chequebank']))?'has-error':'' ?>">

                  <label for="chequebank">Payee Bank Name</label>

					<input type="text" name="chequebank" id="chequebank" class="form-control" value="<?= isset($_POST['chequebank'])?$_POST['chequebank']:''; ?>"  />

					<span class="help-block"><?= isset($errors['chequebank'])?$errors['chequebank']:'' ?></span>

                </div>

				<div class="form-group  <?= (isset($errors['chequedate']))?'has-error':'' ?>">

                  <label for="chequedate">Cheque Date</label>

					<input type="text" name="chequedate" id="dob" class="form-control" value="<?= isset($_POST['chequedate'])?$_POST['chequedate']:''; ?>"  />

					<span class="help-block"><?= isset($errors['chequedate'])?$errors['chequedate']:'' ?></span>

                </div>

				<div class="form-group  <?= (isset($errors['paymentnote']))?'has-error':'' ?>">

                  <label for="paymentnote">Any Notes:</label>					

					<textarea name="paymentnote" id="paymentnote" class="form-control"><?= isset($_POST['paymentnote'])?$_POST['paymentnote']:''; ?></textarea>

					<span class="help-block"><?= isset($errors['paymentnote'])?$errors['paymentnote']:'' ?></span>

                </div>

                </div>		

				

              <!-- /.box-body -->



              <div class="box-footer text-center">	

				<input type="submit" class="btn btn-primary" name="action" value="Add Payment" />		 &nbsp;&nbsp;&nbsp;	  

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

				<table class="table">

					<tr class="success">

						<th colspan="2">Institute Details</th>

					</tr>

					<tr>

						<th>Name:</th>

						<td><?= $INSTITUTE_NAME ?></td>

					</tr>

					<tr>

						<th>Code:</th>

						<td><?= $INSTITUTE_CODE ?></td>

					</tr>

					<tr>

						<th>City:</th>

						<td><?= $INSTITUTE_CITY ?></td>

					</tr>

				</table>

				<table class="table table-bordered">

					<thead>

						<tr class="success">

							<th>#</th>

							<th>Payment Date</th>

							<th>Amount</th>

						</tr>

					</thead>

					<tbody>

				<?php

				$res = $admin->list_institute_payments($reqid,' ORDER BY A.PAYMENT_ID DESC');

				if($res!='')

				{

					$srno = 1;

					$total_paid=0;

					while($data = $res->fetch_assoc())

					{						

						$CREATED_ON = $data['CREATED_DATE'];

						$EXAM_FEES_RECIEVED = $data['TOTAL_EXAM_FEES_RECIEVED'];

						$total_paid += $total_paid;

						echo '<tr>

								<td>'.$srno.'</td>

								<td>'.$CREATED_ON.'</td>

								<td>'.$EXAM_FEES_RECIEVED.'</td>

							</tr>';						

						$srno++;

					}

				}

				?>

					</tbody>					

				</table>

				<hr>

				<table class="table table-bordered">

					<tr>

						<th>Total Exam Fees</th>

						<td><?= $total_fees ?></td>

					</tr>

					<tr>

						<th>Total Paid</th>

						<td><?= $total_recieved ?></td>

					</tr>

					<tr>

						<th>Total Balance</th>

						<td><?= $total_balance ?></td>

					</tr>

				</table>

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

		 </div>

		 -->

		 </form>

        <!--/.col (left) -->

      

        <!--/.col (right) -->

      </div>

      <!-- /.row -->

    </section>

    <!-- /.content -->

  </div>

 