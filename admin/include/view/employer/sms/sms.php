<?php
$action = isset($_POST['send_sms']) ? $_POST['send_sms'] : '';

if ($action != '') {
	$checkinst	= isset($_POST['checkinst']) ? $_POST['checkinst'] : '';
	$sms_text 	= isset($_POST['sms_text']) ? $_POST['sms_text'] : '';
	if ($checkinst != '' && $sms_text != '') {
		$inst_mobile = implode(",", $checkinst);
		$access->trigger_sms($sms_text, $inst_mobile);
		$_SESSION['msg'] = "Success! SMS has been sent successfully.";

		$_SESSION['msg_flag'] = true;
	} else {
		$_SESSION['msg_flag'] = false;
		$_SESSION['msg'] = "Sorry! Message can not sent. Please select institute and enter text message.";
	}
}

$active = isset($_REQUEST['active']) ? $_REQUEST['active'] : '';
$approved = isset($_REQUEST['approved']) ? $_REQUEST['approved'] : '';
$state = isset($_REQUEST['state']) ? $_REQUEST['state'] : '';
$city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
$apdatefrom = isset($_REQUEST['apdatefrom']) ? $_REQUEST['apdatefrom'] : '';
$apdateto = isset($_REQUEST['apdateto']) ? $_REQUEST['apdateto'] : '';
$regdatefrom = isset($_REQUEST['regdatefrom']) ? $_REQUEST['regdatefrom'] : '';
$regdateto = isset($_REQUEST['regdateto']) ? $_REQUEST['regdateto'] : '';

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Send SMS

		</h1>
		<ol class="breadcrumb">
			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#"> SMS</a></li>
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

							<input type="hidden" name="page" value="sms" />
							<div class="form-group col-sm-2">
								<label>Status</label>

								<select class="form-control" name="active" id="active">
									<option value="" <?= ($active == '') ? 'selected="selected"' : '' ?>>--select--</option>
									<option value="1" <?= ($active == '1') ? 'selected="selected"' : '' ?>>Active</option>
									<option value="0" <?= ($active == '0') ? 'selected="selected"' : '' ?>>In-Active</option>
								</select>
							</div>
							<div class="form-group col-sm-3">
								<label>Approval Status</label>
								<?php

								?>
								<select class="form-control" name="approved" id="approved">
									<option value="" <?= ($approved == '') ? 'selected="selected"' : '' ?>>--select--</option>
									<option value="1" <?= ($approved == '1') ? 'selected="selected"' : '' ?>>Approved</option>
									<option value="0" <?= ($approved == '0') ? 'selected="selected"' : '' ?>>Un-Approved</option>
								</select>
							</div>
							<div class="form-group col-sm-3">
								<label>City</label>
								<select class="form-control" name="city" id="city">
									<?php echo $db->MenuItemsDropdown('institute_details A LEFT JOIN city_master B ON A.CITY=B.CITY_ID', "CITY", "CITY_NAME", "DISTINCT A.CITY, B.CITY_NAME ", $city, " WHERE A.CITY!='' AND A.DELETE_FLAG=0"); ?>
								</select>
							</div>
							<div class="form-group col-sm-3">
								<label>State</label>
								<select class="form-control" name="state" id="state">
									<?php echo $db->MenuItemsDropdown('institute_details A LEFT JOIN states_master B ON A.STATE=B.STATE_ID', "STATE", "STATE_NAME", "DISTINCT A.STATE, B.STATE_NAME ", $state, " WHERE A.STATE!='' AND A.DELETE_FLAG=0"); ?>
								</select>
							</div>
							<div class="form-group col-sm-2">
								<label>Approved Date From</label>
								<input class="form-control pull-right" name="apdatefrom" value="<?= isset($_REQUEST['apdatefrom']) ? $_REQUEST['apdatefrom'] : '' ?>" id="datefrom" type="text">
							</div>
							<div class="form-group col-sm-2">
								<label>Approved Date To</label>
								<input class="form-control pull-right" name="apdateto" value="<?= isset($_REQUEST['apdateto']) ? $_REQUEST['apdateto'] : '' ?>" id="dateto" type="text">
							</div>
							<div class="form-group col-sm-2">
								<label>Register Date From</label>
								<input class="form-control pull-right" name="regdatefrom" value="<?= isset($_REQUEST['regdatefrom']) ? $_REQUEST['regdatefrom'] : '' ?>" id="datefrom" type="text">
							</div>
							<div class="form-group col-sm-2">
								<label>Register Date To</label>
								<input class="form-control pull-right calender" name="regdateto" value="<?= isset($_REQUEST['regdateto']) ? $_REQUEST['regdateto'] : '' ?>" type="text" id="dateto" />
							</div>

							<div class="form-group col-sm-1">
								<label> &nbsp;</label>
								<input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />
							</div>
							<div class="form-group col-sm-1">
								<label> &nbsp;</label>
								<a class="form-control btn btn-sm btn-warning" onclick="pageLoaderOverlay('show'); location.assign('list-requested-certificates')">Clear</a>
							</div>
						</form>
					</div>
				</div>
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Send SMS</h3>

					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<form role="form" class="form-validate" action="" method="post" onsubmit="pageLoaderOverlay('show');">
							<div class="form-group col-sm-6 ">
								<label>Enter Message</label>
								<textarea class="form-control" rows="3" placeholder="Enter Message..." name="sms_text" onkeyup="countChar(this)" required="required"><?= isset($_POST['sms_text']) ? $_POST['sms_text'] : '' ?></textarea>
								<span class="help-block" id="charNum"></span>
							</div>
							<div class="form-group col-sm-2 ">
								<label> </label>
								<input type="submit" name="send_sms" value="Send SMS" class="form-control btn btn-sm btn-primary" />
								<span class="help-block"></span>
							</div>
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th><input type="checkbox" name="selectall" id="selectall" /></th>
											<th>Sr.</th>
											<th>Institute Name</th>
											<th>City</th>
											<th>State</th>
											<th>ATC Code</th>
											<th>Mobile</th>
											<th>Status</th>
											<th>Approved</th>
											<th>Register On</th>
											<th>Approved On</th>

										</tr>
									</thead>
									<tbody>
										<?php
										$cond = "";
										if ($active != '') $cond .= " AND A.ACTIVE='$active' ";
										if ($approved != '') $cond .= " AND A.VERIFIED='$approved' ";
										if ($state != '') $cond .= " AND A.STATE='$state' ";
										if ($city != '') $cond .= " AND A.CITY='$city' ";

										if ($apdatefrom != '' && $apdateto != '') {
											$cond .= " AND A.VERIFIED_ON BETWEEN '" . date('Y-m-d', strtotime($apdatefrom)) . "' AND '" . date('Y-m-d', strtotime($apdateto)) . "' AND A.VERIFIED='1' ";
										}
										if ($regdatefrom != '' && $regdateto != '') $cond .= " AND A.CREATED_ON BETWEEN '" . date('Y-m-d', strtotime($regdatefrom)) . "' AND '" . date('Y-m-d', strtotime($regdateto)) . "' ";

										$cond .= " ORDER BY A.INSTITUTE_NAME ASC";
										include_once('include/classes/institute.class.php');
										$institute = new institute();
										$res = $institute->list_institute('', $cond);
										if ($res != '') {
											$srno = 1;
											while ($data = $res->fetch_assoc()) {
												$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
												$USER_LOGIN_ID 		= $data['USER_LOGIN_ID'];
												$REG_DATE 			= $data['REG_DATE'];
												$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];
												$INSTITUTE_NAME 	= $data['INSTITUTE_NAME'];
												$INSTITUTE_OWNER_NAME = $data['INSTITUTE_OWNER_NAME'];
												$EMAIL 				= $data['EMAIL'];
												$MOBILE 			= $data['MOBILE'];
												$CREDIT 			= $data['CREDIT'];
												$CREDIT_BALANCE 	= $data['CREDIT_BALANCE'];
												$ACTIVE 			= $data['ACTIVE'];
												$CITY_NAME 			= $data['CITY_NAME'];
												$STATE_NAME 			= $data['STATE_NAME'];
												$VERIFIED 			= $data['VERIFIED'];
												$VERIFIED_ON_FORMATTED 			= $data['VERIFIED_ON_FORMATTED'];
												$verify_flag 			= $data['VERIFIED'];


												if ($ACTIVE == 1)
													$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i> YES</span>';
												elseif ($ACTIVE == 0)
													$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i> NO</span>';

												$printCert = "";

												if ($VERIFIED == 1)
													$VERIFIED = '<span style="color:#3c763d"><i class="fa fa-check"></i> YES</span>';
												elseif ($VERIFIED == 0)
													$VERIFIED = '<span style="color:#f00"><i class="fa fa-times"></i> NO</span>';
												$checkbox = "<input type='checkbox' name='checkinst[]' id='checkinst$INSTITUTE_ID' value='$MOBILE' />";
												echo " <tr id='row-$INSTITUTE_ID'>
							<td>$checkbox</td>
							<td>$srno</td>
							<td>$INSTITUTE_NAME</td>
							<td>$CITY_NAME</td>
							<td>$STATE_NAME</td>
							<td>$INSTITUTE_CODE</td>
							<td>$MOBILE</td>
							<td id='status-$INSTITUTE_ID'>$ACTIVE</td>
							<td id='verify-$INSTITUTE_ID'>$VERIFIED</td>
							<td>$REG_DATE</td>
							<td>$VERIFIED_ON_FORMATTED</td>
							
                           </tr>";
												$srno++;
											}
										}

										?>
									</tbody>
								</table>
							</div>
						</form>
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