 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<section class="content-header">
 		<h1>
 			Institutes Report Section (DITRP Staff Only)
 			<small>All Institutes</small>
 		</h1>
 		<ol class="breadcrumb">
 			<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
 			<li><a <a href="#"> Institutes</a></li>
 			<li class="active"> Institutes Report Section (DITRP Staff Only)</li>
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
 						<h3 class="box-title">List Institutes Report</h3>

 					</div>
 					<!-- /.box-header -->
 					<div class="box-body">

 						<table class="table table-bordered table-striped table-hover data-tbl">
 							<thead>
 								<tr>
 									<th>Sr.</th>

 									<th>Logo</th>
 									<th>ATC Code</th>
 									<th>Institute Name</th>
 									<th>No Of Student</th>
 									<th>AMC CODE(Ref)</th>
 									<th>Mobile</th>

 									<th>Registration Fees</th>
 									<th>Welcome Kit</th>
 									<th>Admission Details</th>
 									<th>E-Contest Details</th>
 									<th>Typing Demo</th>
 									<th>Mobile App Demo</th>

 									<th>Action</th>
 								</tr>
 							</thead>
 							<tbody>
 								<?php
									$cond = " ";
									$verified = isset($_REQUEST['verified']) ? $_REQUEST['verified'] : '';
									if ($verified != '') {
										$cond .= " AND A.VERIFIED='$verified'";
									}
									$cond .=  " ORDER BY A.CREATED_ON DESC";

									include_once('include/classes/institute.class.php');
									include_once('include/classes/student.class.php');
									$institute = new institute();
									$student = new student();
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
											$PASS_WORD 			= $data['PASS_WORD'];
											$USER_NAME 			= $data['USER_NAME'];
											$ACTIVE 			= $data['ACTIVE'];

											$AMC_CODE 			= $data['AMC_CODE'];

											$STATE_NAME 		= $data['STATE_NAME'];
											$CITY_NAME 			= $data['CITY_NAME'];
											$VERIFIED 			= $data['VERIFIED'];
											$verify_flag 			= $data['VERIFIED'];

											$res123 = $student->list_student('', $INSTITUTE_ID, '');
											$count = ($res123 != '') ? $res123->num_rows : 0;

											$params = "'$USER_NAME','" . $PASS_WORD . "'";
											$loginBtn = "<a href='javascript:void(0)' class='btn btn-primary btn-xs' title='LOGIN' onclick=\"loginToInst($params)\"><i class=' fa fa-sign-in'></i>Login</a>";

											$action = '';

											$action = "<a href='page.php?page=update-report&id=$INSTITUTE_ID' class='btn btn-primary' title='ADD REPORT' target='_blank'><i class='fa fa-plus'></i> / <i class='fa fa-minus'></i></a>";

											include_once('include/classes/admin.class.php');
											$admin = new admin();


											$REGISTRATION_FEE = '';
											$WELCOMEKIT_DEMO = '';
											$ADMISSION_DEMO = '';
											$ECONTEST_DEMO = '';
											$TYPING_DEMO = '';
											$MOBILEAPP_DEMO = '';


											$res2 = $admin->list_institute_report_staff('', $INSTITUTE_ID, '');
											if ($res2 != '') {
												$srno2 = 1;
												while ($data2 = $res2->fetch_assoc()) {

													$report_id 		            = $data2['REPORT_ID'];
													$ADMISSION_DEMO 		    = $data2['ADMISSION_DEMO'];
													$ADMISSION_DETAILS	        = $data2['ADMISSION_DETAILS'];
													$TYPING_DEMO 	            = $data2['TYPING_DEMO'];
													$TYPING_DETAILS			    = $data2['TYPING_DETAILS'];
													$MOBILEAPP_DEMO 			= $data2['MOBILEAPP_DEMO'];
													$MOBILEAPP_DETAILS 		    = $data2['MOBILEAPP_DETAILS'];
													$ECONTEST_DEMO			    = $data2['ECONTEST_DEMO'];
													$ECONTEST_DETAILS			= $data2['ECONTEST_DETAILS'];
													$WELCOMEKIT_DEMO		    = $data2['WELCOMEKIT_DEMO'];
													$WELCOMEKIT_DETAILS         = $data2['WELCOMEKIT_DETAILS'];

													$REGISTRATION_FEE 	       = $data2['REGISTRATION_FEE'];
													$REGISTRATIONFEES_DETAILS  = $data2['REGISTRATIONFEES_DETAILS'];
													$REMARK                    = $data2['REMARK'];
													$ACTIVE                    = $data2['ACTIVE'];
													$CREATED_BY                = $data2['CREATED_BY'];
													$CREATED_ON                = $data2['CREATED_ON'];

													if ($REGISTRATION_FEE == 1)
														$REGISTRATION_FEE = '<a style="color:#3c763d"><i class="fa fa-check"></i> YES</a>';
													elseif ($REGISTRATION_FEE == 0)
														$REGISTRATION_FEE = '<a style="color:#f00"><i class="fa fa-times"></i> NO</a>';

													if ($WELCOMEKIT_DEMO == 1)
														$WELCOMEKIT_DEMO = '<a style="color:#3c763d"><i class="fa fa-check"></i> YES</a>';
													elseif ($WELCOMEKIT_DEMO == 0)
														$WELCOMEKIT_DEMO = '<a style="color:#f00"><i class="fa fa-times"></i> NO</a>';

													if ($ADMISSION_DEMO == 1)
														$ADMISSION_DEMO = '<a style="color:#3c763d"><i class="fa fa-check"></i> YES</a>';
													elseif ($ADMISSION_DEMO == 0)
														$ADMISSION_DEMO = '<a style="color:#f00"><i class="fa fa-times"></i> NO</a>';

													if ($ECONTEST_DEMO == 1)
														$ECONTEST_DEMO = '<a style="color:#3c763d"><i class="fa fa-check"></i> YES</a>';
													elseif ($ECONTEST_DEMO == 0)
														$ECONTEST_DEMO = '<a style="color:#f00"><i class="fa fa-times"></i> NO</a>';

													if ($TYPING_DEMO == 1)
														$TYPING_DEMO = '<a style="color:#3c763d"><i class="fa fa-check"></i> YES</a>';
													elseif ($TYPING_DEMO == 0)
														$TYPING_DEMO = '<a style="color:#f00"><i class="fa fa-times"></i> NO</a>';

													if ($MOBILEAPP_DEMO == 1)
														$MOBILEAPP_DEMO = '<a style="color:#3c763d"><i class="fa fa-check"></i> YES</a>';
													elseif ($MOBILEAPP_DEMO == 0)
														$MOBILEAPP_DEMO = '<a style="color:#f00"><i class="fa fa-times"></i> NO</a>';
												}
											}

											echo " <tr id='row-$INSTITUTE_ID'>
							<td>$srno</td>
                            <td><p>$loginBtn</p></td> 
                            <td>$INSTITUTE_CODE</td>
							<td>$INSTITUTE_NAME</td>
							<td>$count</td>
							<td>$AMC_CODE</td>
							<td>$MOBILE</td>
							
							<td>$REGISTRATION_FEE</td>
							<td>$WELCOMEKIT_DEMO</td>
							<td>$ADMISSION_DEMO</td>
							<td>$ECONTEST_DEMO</td>
							<td>$TYPING_DEMO</td>
							<td>$MOBILEAPP_DEMO</td>
							
                            <td>$action</td>
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