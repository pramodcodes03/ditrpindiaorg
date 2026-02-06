<?php

$datefrom 	= isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : date('d-m-Y', strtotime("-360 days"));

$dateto 	= isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : date('d-m-Y');

$state	 	= isset($_REQUEST['state']) ? $_REQUEST['state'] : '';

$city	 	= isset($_REQUEST['city']) ? $_REQUEST['city'] : '';

$institute_id = isset($_REQUEST['institute_id']) ? $_REQUEST['institute_id'] : '';



?>



<style>
	.report,
	.report>thead>tr>th,
	.report>tbody>tr>th,
	.report>tfoot>tr>th,
	.report>thead>tr>td,
	.report>tbody>tr>td,
	.report>tfoot>tr>td {
		border: 1px solid #ececec !important;
	}

	.report .border-left {
		border-right: 1px solid #ececec;
	}
</style>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

	<!-- Content Header (Page header) -->

	<section class="content-header">

		<h1>

			Institute Staff Reports



		</h1>

		<ol class="breadcrumb">

			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>



			<li class="active"> Institute Staff Reports</li>

		</ol>

	</section>



	<!-- Main content -->

	<section class="content">

		<?php

		if (isset($_SESSION['msg'])) {

			$message = isset($_SESSION['msg']) ? $_SESSION['msg'] : '';

			$msg_flag = $_SESSION['msg_flag'];

		?>

			<div class="row">

				<div class="col-sm-12">

					<div class="alert alert-<?= ($msg_flag == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">

						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>

						<h4><i class="icon fa fa-check"></i> <?= ($msg_flag == true) ? 'Success' : 'Error' ?>:</h4>

						<?= ($message != '') ? $message : 'Sorry! Something went wrong!'; ?>

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

								<input class="form-control" name="datefrom" value="<?= $datefrom ?>" id="datefrom" type="text">

							</div>



							<div class="form-group col-sm-2">

								<label>Date To</label>

								<input class="form-control" name="dateto" value="<?= $dateto ?>" id="dateto" type="text">

							</div>

							<div class="form-group col-sm-2">

								<label>City</label>

								<select class="form-control select2" name="city" value="<?= $city ?>" id="city">

									<?php echo $db->MenuItemsDropdown("institute_staff_details A LEFT JOIN city_master B ON A.STAFF_CITY=B.CITY_ID", "STAFF_CITY", "CITY_NAME", "DISTINCT A.STAFF_CITY,B.CITY_NAME", $city, " WHERE A.DELETE_FLAG=0 ORDER BY B.CITY_NAME"); ?>

								</select>

							</div>

							<div class="form-group col-sm-4">

								<label>Institute</label>

								<select class="form-control select2" name="institute_id" value="<?= $institute_id ?>" id="city">

									<?php echo $db->MenuItemsDropdown("institute_staff_details A", "INSTITUTE_ID", "INSTITUTE_NAME", "DISTINCT A.INSTITUTE_ID,get_institute_name(A.INSTITUTE_ID) AS INSTITUTE_NAME", $institute_id, " WHERE A.DELETE_FLAG=0"); ?>

								</select>

							</div>



							<div class="form-group col-sm-1">

								<label> &nbsp;</label>

								<input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />

							</div>

							<div class="form-group col-sm-1">

								<label> &nbsp;</label>

								<a class="form-control btn btn-sm btn-warning" href="institute-staff-reports">Clear</a>

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

						<!--  <a <a href="#" class="btn btn-sm btn-primary pull-right"><i class="fa fa-file-excel-o"></i>  Export</a> -->

					</div>

					<!-- /.box-header -->

					<div class="box-body">

						<table class="table table-bordered table-hover data-tbl">

							<thead>

								<tr>

									<th>#</th>

									<th>Photo</th>

									<th>Name</th>

									<th>ATC</th>

									<th>Email/Username</th>

									<th>Mobile</th>

									<th>DOB</th>

									<th>DOJ</th>

									<th>Qualification</th>

									<th>Address</th>

									<!--

					<th>Status</th>

					<th>Action</th> 

				-->

								</tr>

							</thead>

							<tbody>

								<?php

								include_once('include/classes/institute.class.php');

								$institute = new institute();

								$cond = '';

								if ($datefrom != '' && $dateto != '') {

									$datefrom = date('Y-m-d', strtotime($datefrom));

									$dateto = date('Y-m-d', strtotime($dateto));

									$cond .= " AND A.STAFF_DOJ BETWEEN '$datefrom' AND '$dateto' ";
								}

								if ($state != '') $cond .= " AND A.STAFF_STATE='$state' ";

								if ($city != '') $cond .= " AND A.STAFF_CITY='$city' ";

								if ($institute_id != '') $cond .= " AND A.INSTITUTE_ID='$institute_id' ";



								$res = $institute->list_institute_staff('', '', $cond);

								if ($res != '') {

									$srno = 1;

									while ($data = $res->fetch_assoc()) {

										$STAFF_ID 			= $data['STAFF_ID'];

										$INSTITUTE_ID 		= $data['INSTITUTE_ID'];

										$INSTITUTE_NAME 	= $data['INSTITUTE_NAME'];

										$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];

										$STAFF_FULLNAME 	= $data['STAFF_FULLNAME'];

										$STAFF_EMAIL 		= $data['STAFF_EMAIL'];

										$USER_NAME 			= $data['USER_NAME'];

										$STAFF_MOBILE 		= $data['STAFF_MOBILE'];

										$STAFF_PHOTO 		= $data['STAFF_PHOTO'];

										$STAFF_EDUCATION 	= $data['STAFF_EDUCATION'];

										$STAFF_CITY_NAME 	= strtoupper($data['STAFF_CITY_NAME']);

										$STAFF_STATE_NAME 	= strtoupper($data['STAFF_STATE_NAME']);

										$STAFF_PER_ADDRESS 	= strtoupper($data['STAFF_PER_ADDRESS']);

										$STAFF_DOB_FORMATED = $data['STAFF_DOB_FORMATED'];

										$STAFF_DOJ_FORMATED = $data['STAFF_DOJ_FORMATED'];

										$ACTIVE 			= $data['ACTIVE'];



										if ($ACTIVE == 1)

											$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstStaffStatus(' . $STAFF_ID . ',0)"><i class="fa fa-check"></i></a>';

										elseif ($ACTIVE == 0)

											$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstStaffStatus(' . $STAFF_ID . ',1)"><i class="fa fa-times"></i></a>';

										$PHOTO = '../uploads/default_user.png';

										if ($STAFF_PHOTO != '')

											$PHOTO = INSTITUTE_STAFF_PHOTO_PATH . '/' . $STAFF_ID . '/' . $STAFF_PHOTO;

										$editLink = '';

										/*	$editLink .= "<a href='update-staff&id=$STAFF_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>

					<a href='javascript:void(0)' onclick='deleteInstStaff($STAFF_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>

					

					<a href='list-incentives&id=$STAFF_ID' class='btn btn-xs btn-link' title='Incentives'><i class=' fa fa-inr'></i></a>

					

					<a href='javascript:void(0)' class='btn btn-link send-email-inst' title='Send Email' data-toggle='modal' data-target='.bs-example-modal-md' data-email='$STAFF_EMAIL' data-id='$STAFF_ID' data-name='$STAFF_FULLNAME'><i class=' fa fa-envelope'></i></a>

					";

					*/





										echo " <tr id='row-$STAFF_ID'>

							<td>$srno</td>

							<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>

							<td>$STAFF_FULLNAME</td>

							<td><a href='page.php?page=update-institute&id=$INSTITUTE_ID' data-toggle='tooltip' data-placement='right' title='$INSTITUTE_NAME' target='_blank'>$INSTITUTE_CODE</a></td>

							<td>$STAFF_EMAIL</td>							

							<td>$STAFF_MOBILE</td>

							<td>$STAFF_DOB_FORMATED</td>

							<td>$STAFF_DOJ_FORMATED</td>

							<td>$STAFF_EDUCATION</td>

							<td>

							$STAFF_PER_ADDRESS <br>

							<strong>State: </strong> $STAFF_STATE_NAME<br>

							<strong>City: </strong> $STAFF_CITY_NAME<br>

							</td>

							<!--

							<td id='status-$STAFF_ID'>$ACTIVE</td>

							 <td>$editLink</td> -->

                           </tr>";

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