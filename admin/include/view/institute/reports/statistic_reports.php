<?php 

	$datefrom 	= isset($_REQUEST['datefrom'])?$_REQUEST['datefrom']:date('d-m-Y',strtotime("-30 days"));;

	$dateto 	= isset($_REQUEST['dateto'])?$_REQUEST['dateto']:date('d-m-Y');

	$city	 	= isset($_REQUEST['city'])?$_REQUEST['city']:'';

	$institute_id	 	= isset($_REQUEST['institute_id'])?$_REQUEST['institute_id']:'';

	//$course_type	 	= isset($_REQUEST['course_type'])?$_REQUEST['course_type']:'';

	



?>



 <style>

 .report, .report > thead > tr > th, .report > tbody > tr > th, .report > tfoot > tr > th, .report > thead > tr > td, .report > tbody > tr > td, .report > tfoot > tr > td{border: 1px solid #ececec  !important; font-size:0.95em;}

 .report .border-left{border-right: 1px solid #ececec ;}

 

 </style>

 <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Reports

        <small>Reports</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>

 

        <li class="active"> Reports</li>

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

				<input type="hidden" name="page" value="statistic-reports" />

				<div class="form-group col-sm-2">

				  <label>Date From</label>

				     <input class="form-control" name="datefrom" value="<?= $datefrom ?>"  id="datefrom" type="text">				 

				</div>

				

				<div class="form-group col-sm-2">

				  <label>Date To</label>

				  <input class="form-control" name="dateto" value="<?= $dateto ?>"  id="dateto" type="text">	

				</div>

				<div class="form-group col-sm-2">

				  <label>City</label>

				  <select class="form-control select2" name="city" value="<?= $city ?>"  id="city">	

				  <?php echo $db->MenuItemsDropdown ("institute_details A LEFT JOIN city_master B ON A.CITY=B.CITY_ID","CITY","CITY_NAME","DISTINCT A.CITY,B.CITY_NAME",$city," WHERE A.DELETE_FLAG=0 ORDER BY B.CITY_NAME"); ?>

				  </select>

				</div>

				<div class="form-group col-sm-4">

				  <label>Institute</label>

				  <select class="form-control select2" name="institute_id" value="<?= $institute_id ?>"  id="city">	

				  <?php echo $db->MenuItemsDropdown ("institute_details A","INSTITUTE_ID","INSTITUTE_NAME","A.INSTITUTE_ID,A.INSTITUTE_NAME",$institute_id," WHERE A.DELETE_FLAG=0 ORDER BY A.INSTITUTE_NAME"); ?>

				  </select>

				</div>

				

				<div class="form-group col-sm-1">

				  <label> &nbsp;</label>

					<input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />				

				</div>

				<div class="form-group col-sm-1">

				  <label> &nbsp;</label>

					<a class="form-control btn btn-sm btn-warning" href="page.php?page=statistic-reports">Clear</a>

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

              <h3 class="box-title">Reports</h3>

			  <form action="export.php" method="post">

				<input type="hidden" value="institute_report" name="action" />

			

				<input type="hidden" value="<?= $city ?>" name="city2" />

				<input type="hidden" value="<?= $institute_id ?>" name="institute_id2" />

				<input type="hidden" value="<?= $datefrom ?>" name="datefrom2" />

				<input type="hidden" value="<?= $dateto ?>" name="dateto2" />

				<button type="submit" name="export" value="Export" class="btn btn-sm btn-primary pull-right"><i class="fa fa-file-excel-o"></i>  Export</button>

			   </form>

            </div>

            <!-- /.box-header -->

            <div class="box-body">

			 <table class="table table-bordered table-striped table-hover report data-tbl">

                <thead>

					<tr>

					  <th>S/N</th>

					  <th>Institute</th>

					  <th>City</th>

					  <th>Enq DITRP</th>

					  <th>Enq N-DITRP</th>

					  <th>Admisn DITRP</th>

					  <th>Admisn N-DITRP</th>

					  <th>Collectn DITRP</th> 

					  <th>Collectn N-DITRP</th> 

					  <th>Business DITRP</th> 

					  <th>Business N-DITRP</th>				

					  <th>Exam Pending</th>

					  <th>Exam Appeared</th>

					  <th>Cert. Orders</th>					  

					</tr>

                </thead>

                <tbody>

			<?php

			include_once('include/classes/institute.class.php');

			include_once('include/classes/reports.class.php');

			$reports = new reports();

			$institute = new institute();

			$cond = '';

			if($city!='')

			{

				$cond .= " AND A.CITY='$city'";

			}

			if($institute_id!='')

			{

				$cond .= " AND A.INSTITUTE_ID='$institute_id'";

			}

			$res = $institute->list_institute('',$cond);

			if($res!='')

			{

				$srno=1;

				$datefrom 	= date('Y-m-d', strtotime($datefrom));

				$dateto 	= date('Y-m-d', strtotime($dateto));

				while($data = $res->fetch_assoc())

				{

					$INSTITUTE_ID 		= $data['INSTITUTE_ID'];

					$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];

					$INSTITUTE_NAME 	= $data['INSTITUTE_NAME'];

					$CITY_NAME1 			= explode("|",$data['CITY_NAME']);

					$CITY_NAME = isset($CITY_NAME1[0])?$CITY_NAME1[0]:$data['CITY_NAME'];

					$enqArr = $reports->getDistinctCoursesEnquiry($INSTITUTE_ID,$datefrom, $dateto);

					$admAICPE = $reports->getTotalAdmissionsCourse($INSTITUTE_ID,1,$datefrom,$dateto);

					$admNONAICPE = $reports->getTotalAdmissionsCourse($INSTITUTE_ID,2,$datefrom,$dateto);

					

					$feeBusinessAICPE = $reports->getTotalFeesBusinessCourse($INSTITUTE_ID,1,$datefrom,$dateto);

					$feeBusinessNONAICPE = $reports->getTotalFeesBusinessCourse($INSTITUTE_ID,2,$datefrom,$dateto);

					

					$feeCollectionAICPE = $reports->getTotalFeesCollectionCourse($INSTITUTE_ID,1);

					$feeCollectionNONAICPE = $reports->getTotalFeesCollectionCourse($INSTITUTE_ID,2);

					

					$pendingExam = $reports->getTotalExam($INSTITUTE_ID,2);

					$appearedExam = $reports->getTotalExam($INSTITUTE_ID,3);

					$totalCertificateOrder = $reports->getTotalCertificateOrder($INSTITUTE_ID);

					

					

					?>

					<tr>

						<td><?= $srno ?></td>

						<td><a href="page.php?page=update-institute&id=<?= $INSTITUTE_ID ?>" data-toggle="tooltip" data-placement="right" title="<?= $INSTITUTE_NAME ?>" target="_blank"><?= $INSTITUTE_CODE ?></a></td>

						<td><?= $CITY_NAME ?></td>

						<td><?= $enqArr['DITRP'] ?></td>

						<td><?= $enqArr['NON-DITRP'] ?></td>

						<td><?= $admAICPE ?></td>

						<td><?= $admNONAICPE ?></td>

						<td><?= $feeCollectionAICPE ?></td>

						<td><?= $feeCollectionNONAICPE ?></td>

						<td><?= $feeBusinessAICPE ?></td>

						<td><?= $feeBusinessNONAICPE ?></td>							

						<td><?= $pendingExam ?></td>

						<td><?= $appearedExam ?></td>

						<td><?= $totalCertificateOrder ?></td>

					</tr>

					

					<?php

					

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