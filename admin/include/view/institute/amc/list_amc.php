 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<h1>
 			List AMC
 			<small>All AMC LIST</small>
 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li><a <a href="#"> AMC</a></li>
 			<li class="active"> List AMC</li>
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
 						<h3 class="box-title">List Amc</h3>

 						<a href="page.php?page=add-amc" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add Amc</a>

 					</div>
 					<!-- /.box-header -->
 					<div class="box-body">
 						<div class="table-responsive">
 							<table id="example1" class="table table-bordered table-hover data-tbl">
 								<thead>
 									<tr>
 										<th>Sr.</th>
 										<th>Approved</th>
 										<th>Action</th>
 										<th>Login</th>
 										<th>AMC Name</th>
 										<th>Person Name</th>
 										<th>Email</th>
 										<th>Mobile</th>
 										<th>Assign Institute</th>
 										<th>Registration Date</th>
 									</tr>
 								</thead>
 								<tbody>
 									<?php
										include_once('include/classes/amc.class.php');
										$amc = new amc();
										$cond = " ";
										$verified = isset($_REQUEST['verified']) ? $_REQUEST['verified'] : '';
										if ($verified != '') {
											//$cond .= " AND A.VERIFIED='$verified'";
										}
										$res = $amc->list_amc('', $cond);
										if ($res != '') {

											$srno = 1;
											while ($data = $res->fetch_assoc()) {
												$AMC_ID 		= $data['AMC_ID'];
												$USER_LOGIN_ID 		= $data['USER_LOGIN_ID'];
												$REG_DATE 			= $data['REG_DATE'];
												$AMC_CODE       	= $data['AMC_CODE'];
												$AMC_COMPANY_NAME 	= $data['AMC_COMPANY_NAME'];
												$AMC_NAME           = $data['AMC_NAME'];
												$EMAIL 				= $data['EMAIL'];
												$MOBILE 			= $data['MOBILE'];
												$CREDIT 			= $data['CREDIT'];
												$CREDIT_BALANCE 	= $data['CREDIT_BALANCE'];
												$ACTIVE 			= $data['ACTIVE'];
												$VERIFIED 			= $data['VERIFIED'];

												$USER_NAME 			= $data['USER_NAME'];
												$PASS_WORD 			= $data['PASS_WORD'];
												$TOTAL 			= $data['TOTAL'];
												//	$TOTAL 			= $data['TOTAL'];

												$changepassFunParams = "'$USER_LOGIN_ID', '$EMAIL'";
												$changepassFun = 'onclick="changePass(' . $changepassFunParams . ')"';

												if ($db->permission('update_employer')) {
													if ($ACTIVE == 1)
														$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeEmpStatus(' . $AMC_ID . ',0)"><i class="fa fa-check"></i></a>';
													elseif ($ACTIVE == 0)
														$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeEmpStatus(' . $AMC_ID . ',1)"><i class="fa fa-times"></i></a>';
												} else {
													if ($ACTIVE == 1)
														$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i></span>';
													elseif ($ACTIVE == 0)
														$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i></span>';
												}

												if ($db->permission('verify_institute')) {
													if ($VERIFIED == 1) {
														$VERIFIED = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeAmcVerify(' . $AMC_ID . ',0)"><i class="fa fa-check"></i> YES</a> ';
													} elseif ($VERIFIED == 0)
														$VERIFIED = '<a href="javascript:void(0)" style="color:#f00" onclick="changeAmcVerify(' . $AMC_ID . ',1)"><i class="fa fa-times"></i> NO</a> ';
												} else {
													if ($VERIFIED == 1)
														$VERIFIED = '<span style="color:#3c763d"><i class="fa fa-check"></i> YES</span>';
													elseif ($VERIFIED == 0)
														$VERIFIED = '<span style="color:#f00"><i class="fa fa-times"></i> NO</span>';
												}

												//$PHOTO = '../uploads/default_user.png';
												$PHOTO = '../uploads/default_user.png';
												$editLink = "";

												$editLink .= "<a href='page.php?page=update-amc&id=$AMC_ID' class='btn btn-link'  title='Edit'><i class=' fa fa-pencil'></i></a>";
												$editLink .= "<a href='page.php?page=assign-inst&id=$AMC_ID' class='btn btn-link' title='Assign'><i class='fa fa-plus'></i></a>";
												$editLink .= "<a href='page.php?page=view-cert&id=$AMC_ID' class='btn btn-link' title='Certificate' target='_blank'><i class='fa fa-certificate'></i></a>";
												$editLink .= "<a href='page.php?page=pay-amc&id=$AMC_ID'  title='Pay to Amc' class='btn btn-link'><i class='fa fa-inr'></i></a>";
												$editLink .= "<a href='page.php?page=show-assign-list&id=$AMC_ID' class='btn btn-link' title='Assign List'><i class='fa fa-book'></i></a>";
												$editLink .= "<a href='javascript:void(0)' class='btn btn-link' title='Delete' onclick='delete_amc($AMC_ID)'><i class='fa fa-trash'></i></a>";

												$params = "'$USER_NAME','" . $PASS_WORD . "'";
												$loginBtn = "<a href='javascript:void(0)' class='btn btn-primary btn-xs' title='LOGIN' onclick=\"loginToInst($params)\"><i class=' fa fa-sign-in'></i>Login</a>";

												echo " <tr id='row-$AMC_ID'>
							<td>$srno</td>
							<td id='verify-$AMC_ID'>$VERIFIED</td>
							<td>$editLink</td>
							<td>$loginBtn</td>
							<td>$AMC_COMPANY_NAME</td>
							<td>$AMC_NAME</td>
							<td>$EMAIL</td>
							<td>$MOBILE</td>
							<td>$TOTAL</td>
							<td>$REG_DATE</td>
                           </tr>";
												$srno++;
											}
										}
										?>
 								</tbody>

 							</table>
 						</div>
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