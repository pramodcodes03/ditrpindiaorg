 <!-- Content Wrapper. Contains page content -->


 <?php $amc_id = isset($_GET['id']) ? $_GET['id'] : '';





	$action = isset($_POST['action']) ? $_POST['action'] : '';
	//$checkstud = isset($_POST['checkstud'])?$_POST['checkstud']:'';
	if ($action == 'Assign') {

		//print_r($_POST);exit();
		include_once('include/classes/amc.class.php');
		$amc = new amc();
		$result = $amc->assign_inst();
		$result = json_decode($result, true);
		$success = isset($result['success']) ? $result['success'] : '';
		$message = $result['message'];
		$errors = isset($result['errors']) ? $result['errors'] : '';
		if ($success == true) {
			$_SESSION['msg'] = $message;
			$_SESSION['msg_flag'] = $success;
			header('location:page.php?page=list-amc');
		}
	}

	?>
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->

 	<section class="content-header">
 		<h1>
 			List Institutes
 			<small>All Institutes</small>
 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li><a href="#"> Institutes</a></li>
 			<li class="active"> List Institutes</li>
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
 				<div class="box">
 					<div class="box-header">
 						<h3 class="box-title">List Institutes</h3>

 					</div>
 					<!-- /.box-header -->
 					<div class="box-body">
 						<form action="" method="post" class="" onsubmit="return confirm('Confirm! Asssign Institude');">
 							<input type="hidden" value="<?php echo $amc_id ?>" name="amc_id" />
 							<input type="hidden" value="Assign" name="action" />
 							<input type="submit" class="btn btn-sm btn-primary" name="submit" value="Assign Institute" />

 							<table class="table table-bordered table-striped table-hover data-tbl">
 								<thead>
 									<tr>
 										<th><input type="checkbox" name="selectall" id="selectall" /> </th>
 										<th>Sr.</th>
 										<th>Logo</th>
 										<th>Institute Name</th>
 										<th>City</th>
 										<th>ATC Code</th>
 										<th>Username</th>
 										<th>Mobile</th>
 										<th>Status</th>
 									</tr>
 								</thead>
 								<tbody>
 									<?php
										$cond = " ";
										$verified = isset($_REQUEST['verified']) ? $_REQUEST['verified'] : '';
										if ($verified != '') {
											$cond .= " AND A.VERIFIED='$verified'";
										}


										include_once('include/classes/amc.class.php');
										$amc = new amc();
										$res = $amc->list_assigned_institutes($amc_id, $cond);
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
												$USER_NAME 			= $data['USER_NAME'];
												$ACTIVE 			= $data['ACTIVE'];
												$CITY_NAME 			= $data['CITY_NAME'];
												$VERIFIED 			= $data['VERIFIED'];
												$verify_flag 			= $data['VERIFIED'];
												$verify_flag 			= $data['VERIFIED'];
												$assign_flag 			= $data['ASSIGN_FLAGF'];

												if ($db->permission('update_institute')) {
													if ($ACTIVE == 1)
														$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstStatus(' . $INSTITUTE_ID . ',0)"><i class="fa fa-check"></i> YES</a>';
													elseif ($ACTIVE == 0)
														$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstStatus(' . $INSTITUTE_ID . ',1)"><i class="fa fa-times"></i> NO</a>';
												} else {
													if ($ACTIVE == 1)
														$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i> YES</span>';
													elseif ($ACTIVE == 0)
														$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i> NO</span>';
												}
												$printCert = "";
												if ($db->permission('verify_institute')) {
													if ($VERIFIED == 1) {
														$VERIFIED = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstVerify(' . $INSTITUTE_ID . ',0)"><i class="fa fa-check"></i> YES</a> ';

														$printCert = "<a href='page.php?page=print-franchise-certificate&inst[]=$INSTITUTE_ID' class='btn btn-link' title='Print Certificate' target='_blank'><i class=' fa fa-certificate'></i></a>";

														$printCert .= "<a href='page.php?page=print-franchise-address&inst[]=$INSTITUTE_ID' class='btn btn-link' title='Print Address' target='_blank'><i class=' fa fa-envelope'></i></a>";
													} elseif ($VERIFIED == 0)
														$VERIFIED = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstVerify(' . $INSTITUTE_ID . ',1)"><i class="fa fa-times"></i> NO</a> ';
												} else {
													if ($VERIFIED == 1)
														$VERIFIED = '<span style="color:#3c763d"><i class="fa fa-check"></i> YES</span>';
													elseif ($VERIFIED == 0)
														$VERIFIED = '<span style="color:#f00"><i class="fa fa-times"></i> NO</span>';
												}

												$changepassFunParams = "'$USER_LOGIN_ID', '$EMAIL'";
												$changepassFun = 'onclick="changePass(' . $changepassFunParams . ')"';
												$PHOTO = '../uploads/default_user.png';
												//if($STAFF_PHOTO!='')
												//	$PHOTO = INSTITUTE_DOCUMENTS_PATH.'/'.$INSTITUTE_ID.'/thumb/'.$STAFF_PHOTO;
												$editLink = "";
												if ($db->permission('update_institute'))
													$editLink .= "<a href='page.php?page=update-institute&id=$INSTITUTE_ID' class='btn btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";

												if ($db->permission('delete_institute'))
													$editLink .= "<a href='javascript:void(0)' class='btn btn-link' title='Delete' onclick='deleteInstitute($INSTITUTE_ID)'><i class=' fa fa-trash'></i></a>";

												/*
					$editLink .= "<a href='javascript:void(0)' class='btn btn-link send-email-inst' title='Send Email' data-toggle='modal' data-target='.bs-example-modal-md' data-email='$EMAIL' data-id='$INSTITUTE_ID' data-name='$INSTITUTE_NAME'><i class=' fa fa-envelope'></i></a>";					
					$editLink .="<a href='javascript:void(0)' class='btn btn-link' title='Change Password' $changepassFun><i class=' fa fa-key'></i></a>
					";
					*/
												//  $editLink .="<a href='javascript:void(0)' class='btn btn-link' title='Password Recovery SMS' onclick='forgotPassSMS($INSTITUTE_ID,2)' ><i class=' fa fa-key'></i></a>";

												/*if($verify_flag==0)
					$editLink .="<a href='javascript:void(0)' class='btn btn-link' title='Documents Reminder SMS' onclick='uploadDocsSMS($INSTITUTE_ID,2)' ><i class=' fa fa-bell'></i></a>";*/
												$disable = "";
												if ($assign_flag == 1) {

													$disable = "disabled";
												}
												$editLink .= $printCert;

												$checkbox = "";

												$checkbox = "<td><input type='checkbox'  name='institute_id[]' id='checkstud$INSTITUTE_ID' class='chk-col-pink' value='$INSTITUTE_ID' ></td>";
												include_once('include/classes/institute.class.php');
												$institute = new institute();
												$logo = $institute->get_institute_docs_single($INSTITUTE_ID, 'logo');
												echo " <tr id='row-$INSTITUTE_ID'>
					 		$checkbox
							<td>$srno</td>						
							<td>$logo</td>

							<td>$INSTITUTE_NAME</td>
							<td>$CITY_NAME</td>
							<td>$INSTITUTE_CODE</td>
							<td>$USER_NAME</td>
							<!-- <td>$EMAIL</td> -->
							<td>$MOBILE</td>
<td id='status-$INSTITUTE_ID'>$ACTIVE</td>
                           </tr>";
												$srno++;
											}
										}

										?>
 								</tbody>
 							</table>

 						</form>
 						<!-- /.box -->
 					</div>
 					<!-- /.col -->
 				</div>
 			</div>
 		</div>
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
 								<input class="form-control" placeholder="To:" id="inst_email" name="inst_email">
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