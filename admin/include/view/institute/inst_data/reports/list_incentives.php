 <?php

 $staff_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

 $institute_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:'';

 $cond ='';

 $action = isset($_POST['search'])?$_POST['search']:'';

 if($action!='')

 {

	$datefrom 	= isset($_POST['datefrom'])?$_POST['datefrom']:'';

	$dateto 		= isset($_POST['dateto'])?$_POST['dateto']:date('');	

	if($datefrom!='' && $dateto!='')

	{

		$datefrom 	 = date('Y-m-d H:i:s', strtotime($datefrom));

		$dateto	 = date('Y-m-d H:i:s', strtotime($dateto));

		$cond .= " AND A.CREATED_ON BETWEEN '$datefrom' AND '$dateto'";

	}	

 }

 

 

	include_once('include/classes/institute.class.php');

	$institute = new institute();

	//$res = $institute->list_institute_staff_incentive('',$_SESSION['user_id

	

	$sql = "SELECT A.ENQUIRY_BY, get_institute_staff_name(A.ENQUIRY_BY) AS ENQUIRY_BY_NAME,A.ADMISSION_BY,get_institute_staff_name(A.ADMISSION_BY) AS ADMISSION_BY_NAME, B.STUDENT_ID, get_student_name(B.STUDENT_ID) AS STUD_NAME, C.TOTAL_COURSE_FEES FROM student_enquiry A LEFT JOIN student_details B ON A.ENQUIRY_ID=B.ENQUIRY_ID LEFT JOIN student_course_details C ON B.STUDENT_ID=C.STUDENT_ID  WHERE A.INSTITUTE_ID='$institute_id' AND A.REGISTRATION=1 ";

	

	$cond .= " AND (A.ENQUIRY_BY='$staff_id' OR A.ADMISSION_BY='$staff_id') ";

	$sql .= $cond;

	$res = $db->execQuery($sql);

 ?>

 <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

         Staff Incentives

        

      </h1>

      <ol class="breadcrumb">

        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a <a href="#"> Reports</a></li>

        <li class="active">Staff Incentive</li>

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

          <div class="box box-primary">           

            <!-- /.box-header -->

			<div class="box-header">			

						

			<h3 class="box-title">Search By Filters</h3>

            </div>

            <div class="box-body">

			<form action="" method="post" onsubmit="pageLoaderOverlay('show')">  

				<input type="hidden" name="page" value="list-student-payments" />

				<div class="form-group col-sm-2">

				  <label>Date From</label>

				     <input class="form-control pull-right" name="datefrom" value="<?= isset($_REQUEST['datefrom'])?$_REQUEST['datefrom']:'' ?>"  id="datefrom" type="text">				 

				</div>

				<div class="form-group col-sm-2">

				  <label>Date To</label>

				  <input class="form-control pull-right" name="dateto" value="<?= isset($_REQUEST['dateto'])?$_REQUEST['dateto']:'' ?>"  id="dateto" type="text">	

				</div>			

				<div class="form-group col-sm-2">

				  <label>Staff Name</label>

				  <?php $staff_id = isset($_REQUEST['staff_id'])?$_REQUEST['staff_id']:''; ?>

				   <select class="form-control select2" name="staff_id" id="staff_id">

					  <?php echo $db->MenuItemsDropdown ('institute_staff_details',"STAFF_ID","STAFF_FULLNAME","DISTINCT STAFF_ID, STAFF_FULLNAME",$staff_id," WHERE DELETE_FLAG=0 AND ACTIVE=1"); ?>

					</select>

				</div>				

				

				<div class="form-group col-sm-1">

				  <label> &nbsp;</label>

					<input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />				

				</div>

				<div class="form-group col-sm-1">

				  <label> &nbsp;</label>

					<a class="form-control btn btn-sm btn-warning" onclick="pageLoaderOverlay('show'); location.href='page.php?page=list-student-payments';">Clear</a>

				</div>

			</form>

		</div>

		</div>

		</div>

	</div>

      <div class="row">

        <div class="col-xs-12">

          <div class="box">

            <div class="box-header">

             <!-- <a href="page.php?page=add-staff" class="btn btn-sm btn-primary pull-left"><i class="fa fa-plus"></i> Add Staff Member</a> -->

            </div>

            <!-- /.box-header -->

            <div class="box-body">

			 <table class="table table-bordered table-hover data-tbl">

                <thead>

                <tr>

                  <th>S/N</th>

                  <th>Admission</th>

                  <th>Admission By</th>

                  <th>Enquiry By</th>

                  <th>Course Fees</th>

                  <th>Enquiry Incentive</th>

                  <th>Admission Incentive</th>

                  <th>Total Incentive</th>

                 

                </tr>

                </thead>

                <tbody>

			<?php

			

			if($res!='')

			{

				$srno=1;

				while($data = $res->fetch_assoc())

				{

					extract($data);

					

					//enquiry

					$enquiry_inc=0;

					$enquiry_inc_rate='';

					if($ENQUIRY_BY!='' && $ENQUIRY_BY!=0)

					{

						$sql1 = "SELECT INCENTIVE_IN,INCENTIVE_VALUE FROM institute_staff_details WHERE STAFF_ID='$ENQUIRY_BY'";

						$res1 = $db->execQuery($sql1);

						if($res1 && $res1->num_rows>0)

						{

							extract($res1->fetch_assoc());

							if($INCENTIVE_IN=='amount'){

								$enquiry_inc_rate=$INCENTIVE_VALUE.' <i class="fa fa-inr"></i>';

								$enquiry_inc = $INCENTIVE_VALUE;

							}

							if($INCENTIVE_IN=='percentage'){

								$enquiry_inc_rate=$INCENTIVE_VALUE.' <i class="fa fa-percent"></i>';

								$enquiry_inc = ($INCENTIVE_VALUE/100) * $TOTAL_COURSE_FEES;

							}

							

						}

					}

					$admission_enc=0;

					$admission_inc_rate='';

					if($ADMISSION_BY!='' && $ADMISSION_BY!=0)

					{

						$sql2 = "SELECT INCENTIVE_IN,INCENTIVE_VALUE FROM institute_staff_details WHERE STAFF_ID='$ADMISSION_BY'";

						$res2 = $db->execQuery($sql2);

						if($res2 && $res2->num_rows>0)

						{

							extract($res2->fetch_assoc());

							if($INCENTIVE_IN=='amount'){

								$admission_inc_rate=$INCENTIVE_VALUE.' <i class="fa fa-inr"></i>';

								$admission_enc = $INCENTIVE_VALUE;

							}

							if($INCENTIVE_IN=='percentage'){

								$admission_inc_rate= $INCENTIVE_VALUE.' <i class="fa fa-percent"></i>';

								$admission_enc = ($INCENTIVE_VALUE/100) * $TOTAL_COURSE_FEES;

							}

							

						}

					}

					$total_incentive = $enquiry_inc + $admission_enc;

					echo '<tr>

							<td>'.$srno.'</td>

							<td>'.$STUD_NAME.'</td>

							<td>'.$ADMISSION_BY_NAME.'</td>

							<td>'.$ENQUIRY_BY_NAME.'</td>

							<td>'.$TOTAL_COURSE_FEES.' <i class="fa fa-inr"></i></td>

							<td>'.$enquiry_inc.' <i class="fa fa-inr"></i> &nbsp;&nbsp( @ '.$enquiry_inc_rate.')</td>

							<td>'.$admission_enc.' <i class="fa fa-inr"></i> &nbsp;&nbsp( @ '.$admission_inc_rate.')</td>

							<td>'.$total_incentive.' <i class="fa fa-inr"></i></td>

						</tr>';

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

					<form id="send_email_form" method="post">

					

					<input type="hidden" name="inst_id" id="inst_id" value="" />

					<input type="hidden" name="action" id="action" value="send_email" />

						<div class="box-body">

						  <div class="form-group" id="email-error">

							<input class="form-control" placeholder="To:" id="inst_email" name="inst_email" >

							<p class="help-block"></p>

						  </div>

						  <div class="form-group">

							<input class="form-control" placeholder="Subject:" id="subject" name="subject">

						  </div>

						  <div class="form-group" id="msg-error">

								<textarea id="compose-textarea" class="form-control" name="message" id="message" style="height: 150px">

								 

								</textarea>

								<p class="help-block"></p>

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