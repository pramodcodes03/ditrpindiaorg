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
 			<li><a href="#"> AMC</a></li>
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
 					<!--<div class="box-header">
              <h3 class="box-title">List Employer</h3>
			  <?php if ($db->permission('add_employer')) { ?>
			 <a href="page.php?page=add-employer" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Add Employer</a>
			  <?php } ?>
            </div>-->
 					<!-- /.box-header -->
 					<div class="box-body">
 						<div class="table-responsive">
 							<table id="example1" class="table table-bordered table-hover data-tbl">
 								<thead>
 									<tr>
 										<th>Sr.</th>
 										<th>AMC Name</th>
 										<th>Person Name</th>
 										<th>Email</th>
 										<th>Mobile</th>
 										<th>Status</th>
 										<th>Action</th>
 									</tr>
 								</thead>
 								<tbody>
 									<?php
										include_once('include/classes/employer.class.php');
										$employer = new employer();
										$cond = " ";
										$verified = isset($_REQUEST['verified']) ? $_REQUEST['verified'] : '';
										if ($verified != '') {
											$cond .= " AND A.VERIFIED='$verified'";
										}
										$res = $employer->list_employer('', $cond);
										if ($res != '') {

											$srno = 1;
											while ($data = $res->fetch_assoc()) {
												$EMPLOYER_ID 		= $data['EMPLOYER_ID'];
												$USER_LOGIN_ID 		= $data['USER_LOGIN_ID'];
												$REG_DATE 			= $data['REG_DATE'];
												$EMPLOYER_CODE 	= $data['EMPLOYER_CODE'];
												$EMPLOYER_COMPANY_NAME 	= $data['EMPLOYER_COMPANY_NAME'];
												$EMPLOYER_NAME = $data['EMPLOYER_NAME'];
												$EMAIL 				= $data['EMAIL'];
												$MOBILE 			= $data['MOBILE'];
												$CREDIT 			= $data['CREDIT'];
												$CREDIT_BALANCE 	= $data['CREDIT_BALANCE'];
												$ACTIVE 			= $data['ACTIVE'];
												$VERIFIED 			= $data['VERIFIED'];

												$changepassFunParams = "'$USER_LOGIN_ID', '$EMAIL'";
												$changepassFun = 'onclick="changePass(' . $changepassFunParams . ')"';

												if ($db->permission('update_employer')) {
													if ($ACTIVE == 1)
														$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeEmpStatus(' . $EMPLOYER_ID . ',0)"><i class="fa fa-check"></i></a>';
													elseif ($ACTIVE == 0)
														$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeEmpStatus(' . $EMPLOYER_ID . ',1)"><i class="fa fa-times"></i></a>';
												} else {
													if ($ACTIVE == 1)
														$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i></span>';
													elseif ($ACTIVE == 0)
														$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i></span>';
												}
												if ($db->permission('verify_employer')) {
													if ($VERIFIED == 1)
														$VERIFIED = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeEmpVerify(' . $EMPLOYER_ID . ',0)"><i class="fa fa-check"></i></a>';
													elseif ($VERIFIED == 0)
														$VERIFIED = '<a href="javascript:void(0)" style="color:#f00" onclick="changeEmpVerify(' . $EMPLOYER_ID . ',1)"><i class="fa fa-times"></i></a>';
												} else {
													if ($VERIFIED == 1)
														$VERIFIED = '<span style="color:#3c763d"><i class="fa fa-check"></i></span>';
													elseif ($VERIFIED == 0)
														$VERIFIED = '<span style="color:#f00"><i class="fa fa-times"></i></span>';
												}

												$PHOTO = '../uploads/default_user.png';
												$editLink = "";
												if ($db->permission('update_employer'))
													$editLink .= "<a href='page.php?page=update-employer&id=$EMPLOYER_ID' class='btn btn-primary' title='Edit'><i class=' fa fa-pencil'></i>  View</a>";

												/*	if($db->permission('delete_employer'))					
					$editLink .= "<a href='javascript:void(0)' class='btn btn-link' title='Delete' onclick='deleteEmployer($EMPLOYER_ID)'><i class=' fa fa-trash'></i></a>";
					*/
												/*
					$editLink .="<a href='javascript:void(0)' class='btn btn-link send-email-inst' title='Send Email' data-toggle='modal' data-target='.bs-example-modal-md' data-email='$EMAIL' data-id='$EMPLOYER_ID' data-name='$EMPLOYER_NAME'><i class=' fa fa-envelope'></i></a>";
					
					$editLink .= "<a href='javascript:void(0)' class='btn btn-link' title='Change Password' $changepassFun><i class=' fa fa-key'></i></a>
					";
					*/
												/*	$editLink .="<a href='javascript:void(0)' class='btn btn-link' title='Password Recovery SMS' onclick='forgotPassSMS($EMPLOYER_ID,3)' ><i class=' fa fa-key'></i></a>
					";*/
												$logo = $employer->get_employer_docs_single($EMPLOYER_ID, INST_LOGO);
												echo " <tr id='row-$EMPLOYER_ID'>
							<td>$srno</td>
							<td>$EMPLOYER_COMPANY_NAME</td>
							<td>$EMPLOYER_NAME</td>
							<td>$EMAIL</td>
							<td>$MOBILE</td>
							<td id='status-$EMPLOYER_ID'>$ACTIVE</td>
							<td>$editLink</td>
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