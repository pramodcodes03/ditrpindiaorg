  <?php
	include_once('include/classes/student.class.php');
	include_once('include/classes/institute.class.php');
	include_once('include/classes/employer.class.php');
	include_once('include/classes/admin.class.php');
	$student = new student();
	$institute = new institute();
	$employer = new employer();
	$admin = new admin();

	?>
  <style>
  	.box-title {
  		font-size: 24px !important;
  	}

  	.box-header-blue {
  		background-color: #00c0ef;
  		color: #fff;
  	}
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	<!-- Content Header (Page header) -->
  	<section class="content-header">
  		<h1>
  			Dashboard
  			<small>Control panel</small>
  		</h1>
  		<ol class="breadcrumb">
  			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
  			<li class="active">Dashboard</li>
  		</ol>
  	</section>

  	<!-- Main content -->
  	<section class="content admin-dashboard">
  		<!-- Small boxes (Stat box) -->
  		<div class="row">
  			<div class="col-lg-3 col-xs-6">
  				<!-- small box -->
  				<div class="small-box bg-aqua">
  					<div class="inner">

  						<h3>Admissions</h3>

  					</div>
  					<div class="icon" style="top:15px; color:#fff;">
  					</div>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>TOTAL</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=admission-reports&coursetype=" class="label  pull-right"><?= $admin->getTotalAdmissions('', '') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>DITRP</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=admission-reports&coursetype=1" class="label pull-right"><?= $admin->getTotalAdmissions('', '1') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>NON-DITRP</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=admission-reports&coursetype=2" class="label pull-right"><?= $admin->getTotalAdmissions('', '2') ?></a></h3>
  							</td>
  						</tr>
  					</table>
  					<a href="admission-reports" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<!-- ./col -->
  			<div class="col-lg-3 col-xs-6">
  				<!-- small box -->
  				<div class="small-box bg-green">
  					<div class="inner">

  						<h3>Institutes</h3>
  					</div>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>TOTAL</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-institutes" class="label  pull-right"><?= $admin->getTotalInstitutes('', '') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Verified</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-institutes&verified=1" class="label pull-right"><?= $admin->getTotalInstitutes('1', '') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Un-Verified</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-institutes&verified=0" class="label  pull-right"><?= $admin->getTotalInstitutes('0', '') ?></a></h3>
  							</td>
  						</tr>
  					</table>
  					<a href="page.php?page=list-institutes" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<!-- ./col -->
  			<div class="col-lg-3 col-xs-6">
  				<!-- small box -->
  				<div class="small-box bg-yellow">
  					<div class="inner">

  						<h3>Employers</h3>
  					</div>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>TOTAL</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-employer" class="label pull-right"><?= $admin->getTotalEmployers('', '') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Verified</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-employer&verified=1" class="label  pull-right"><?= $admin->getTotalEmployers('1', '') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Un-Verified</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-employer&verified=0" class="label  pull-right"><?= $admin->getTotalEmployers('0', '') ?></a></h3>
  							</td>
  						</tr>
  					</table>
  					<a href="page.php?page=list-employer" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<!-- ./col -->

  			<div class="col-lg-3 col-xs-6">
  				<!-- small box -->
  				<div class="small-box bg-maroon">
  					<div class="inner">
  						<h3>Exams</h3>
  					</div>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>Total</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=admission-reports" class="label  pull-right"><?= $admin->student_reports_count('', '', '', '') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Not Applied</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=admission-reports&coursetype=1&examstatus=1" class="label  pull-right"><?= $admin->student_reports_count('', '', '', ' AND C.COURSE_TYPE=1 AND A.EXAM_STATUS="1"') ?></span></h3>
  							</td>
  						</tr>

  						<tr>
  							<th>
  								<h4>Applied</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=admission-reports&coursetype=1&examstatus=2" class="label  pull-right"><?= $admin->student_reports_count('', '', '', ' AND C.COURSE_TYPE=1 AND A.EXAM_STATUS="2"') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Appeared</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=admission-reports&coursetype=1&examstatus=3" class="label  pull-right"><?= $admin->student_reports_count('', '', '', ' AND C.COURSE_TYPE=1 AND A.EXAM_STATUS="3"') ?></a></h3>
  							</td>
  						</tr>

  						<tr>
  							<th>
  								<h4>Appeared But Certificate Not Requested</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=admission-reports&coursetype=1&examstatus=3" class="label  pull-right"><?= $admin->total_exam_result(" AND RESULT_STATUS='Passed' AND APPLY_FOR_CERTIFICATE=0") ?></a></h3>
  							</td>
  						</tr>
  					</table>
  					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<div class="clearfix"></div>
  			<div class="col-lg-3 col-xs-6">
  				<!-- small box -->
  				<div class="small-box bg-purple">
  					<div class="inner">
  						<h3>Certificates</h3>
  					</div>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>Pending</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-requested-certificates&requeststatus=1" class="label  pull-right"><?= $admin->getTotalCertificateRequests('1') ?></a></h3>
  							</td>
  						</tr>

  						<tr>
  							<th>
  								<h4>Approved</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-requested-certificates&requeststatus=2" class="label  pull-right"><?= $admin->getTotalCertificateRequests('2') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Total Pending Fees</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-requested-certificates&requeststatus=2" class="label  pull-right"><?= round($admin->getTotalCertificateRequestsFees('1')) ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Total Approved Fees</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-requested-certificates&requeststatus=2" class="label  pull-right"><?= round($admin->getTotalCertificateRequestsFees('2')) ?></a></h3>
  							</td>
  						</tr>
  					</table>
  					<a href="page.php?page=list-requested-certificates" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<!-- ./col -->
  			<div class="col-lg-3 col-xs-6">
  				<!-- small box -->
  				<div class="small-box bg-fuchsia">
  					<div class="inner">
  						<h3>Payments</h3>
  					</div>
  					<?php
						$totalOnline = $admin->getTotalOnlinePayment('success');
						$totalOffline = $admin->getTotalOfflinePayment(" AND TRANSACTION_TYPE='CREDIT' OR TRANSACTION_TYPE='DEBIT'");
						$totalCredit = $admin->getTotalOfflinePayment(" AND TRANSACTION_TYPE='CREDIT'");
						$totalDebit = $admin->getTotalOfflinePayment(" AND TRANSACTION_TYPE='DEBIT'");
						$finalTotal = $totalOnline + $totalOffline;
						?>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>Total</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=recharge-history" class="label  pull-right"><?= round($finalTotal) ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Online</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=recharge-history&paymentmode1=ONLINE" class="label  pull-right"><?= round($totalOnline)  ?></a></h3>
  							</td>
  						</tr>

  						<tr>
  							<th>
  								<h4>Offline</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=recharge-history&paymentmode1=OFFLINE" class="label  pull-right"><?= round($totalOffline) ?></a></h3>
  							</td>
  						</tr>

  						<tr>
  							<th>
  								<h4 style="margin:0px;font-size: 14px !important;">Total Credit</h4>
  							</th>
  							<td>
  								<h4 style="margin:0px;"><a href="page.php?page=recharge-history&paymentmode1=OFFLINE" class="label  pull-right"><?= round($totalCredit) ?></a></h4>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4 style="margin:0px;font-size: 14px !important;">Total Debit</h4>
  							</th>
  							<td>
  								<h4 style="margin:0px;"><a href="page.php?page=recharge-history&paymentmode1=OFFLINE" class="label  pull-right"><?= round($totalDebit) ?></a></h4>
  							</td>
  						</tr>
  					</table>
  					<a href="page.php?page=recharge-history" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>

  			<div class="col-lg-3 col-xs-6">
  				<!-- small box -->
  				<div class="small-box bg-navy">
  					<div class="inner">
  						<h3>Wallet</h3>
  					</div>
  					<?php
						$totalWalletAmount =  $admin->getTotalWallet();
						$totalWalletInstCount =  $admin->getTotalWalletInstitutesCount(" AND USER_ROLE IN(2,5)");
						$totalWalletInstCountZeroAmt = $admin->getTotalWalletInstitutesCount(" AND USER_ROLE IN(2,5) AND TOTAL_BALANCE=0");
						$totalWalletInstCountNonZeroAmt = $admin->getTotalWalletInstitutesCount(" AND USER_ROLE IN(2,5) AND TOTAL_BALANCE > 0");
						?>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>Total Amount</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=wallet" class="label pull-right">
  										<?= round($totalWalletAmount) ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Total Institutes</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=wallet" class="label pull-right">
  										<?= round($totalWalletInstCount) ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Total Zero Balance Institutes</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=wallet&balance=0" class="label pull-right">
  										<?= round($totalWalletInstCountZeroAmt) ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Total Non-Zero Balance Institutes</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=wallet&balance=1" class="label pull-right">
  										<?= round($totalWalletInstCountNonZeroAmt) ?></a></h3>
  							</td>
  						</tr>

  					</table>
  					<a href="page.php?page=wallet" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<div class="col-lg-3 col-xs-6">
  				<!-- small box -->
  				<div class="small-box bg-red">
  					<div class="inner">
  						<h3>Enquiries</h3>
  					</div>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>Contact</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-contact-enquiries" class="label  pull-right"><?php $totalEnqRes = $db->list_contact_enquiries();
																									echo isset($totalEnqRes->num_rows) ? $totalEnqRes->num_rows : 0; ?></a></h3>
  							</td>
  						</tr>

  						<tr>
  							<th>
  								<h4>Course</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-course-enquiries" class="label  pull-right"><?php $totalEnqRes = $db->list_course_enquiries();
																								echo isset($totalEnqRes->num_rows) ? $totalEnqRes->num_rows : 0; ?></a></h3>
  							</td>
  						</tr>
  					</table>
  					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<div class="clearfix"></div>
  		</div>

  		<!-- /.row -->
  		<!-- Main row -->
  		<div class="row">
  			<!-- Left col -->
  			<section class="col-lg-7 connectedSortable">
  				<!-- TO DO List -->
  				<div class="box box-primary">
  					<div class="box-header">
  						<i class="fa fa-university"></i>
  						<h3 class="box-title">Latest Added Institutes</h3>
  					</div>
  					<!-- /.box-header -->
  					<div class="box-body">
  						<ul class="products-list product-list-in-box">
  							<?php

								$res = $admin->list_institute(' ORDER BY A.INSTITUTE_ID DESC LIMIT 0,5');
								$count = ($res != '') ? $res->num_rows : 0;
								if ($count != 0) {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										$INSTITUTE_ID = $data['INSTITUTE_ID'];
										$INSTITUTE_CODE = $data['INSTITUTE_CODE'];
										$INSTITUTE_NAME = $data['INSTITUTE_NAME'];
										$INSTITUTE_OWNER_NAME = $data['INSTITUTE_OWNER_NAME'];
										$CITY_NAME = $data['CITY_NAME'];
										$CREATED_DATE = $data['CREATED_DATE'];
										$logo = $institute->get_institute_docs_single($INSTITUTE_ID, 'logo');

								?>
  									<li class="item">
  										<div class="product-img">
  											<?= $logo ?>
  										</div>
  										<div class="product-info">
  											<a href="page.php?page=update-institute&id=<?= $INSTITUTE_ID ?>" class="product-title"><?= $INSTITUTE_NAME ?>
  												<span class="label  pull-right">View</span></a>
  											<span class="product-description">
  												<?= $INSTITUTE_OWNER_NAME ?>,<?= $CITY_NAME ?>
  											</span>
  										</div>
  									</li>
  							<?php

										$srno++;
									}
								}
								?>
  						</ul>
  					</div>
  					<!-- /.box-body -->
  					<div class="box-footer clearfix no-border text-center">
  						<a href="page.php?page=list-institutes" class="btn btn-default pull-right"><i class="fa fa-eye"></i> View All</a>
  					</div>
  				</div>


  			</section>
  			<!-- /.Left col -->
  			<!-- right col (We are only adding the ID to make the widgets sortable)-->
  			<section class="col-lg-5 connectedSortable">

  				<div class="box box-primary">
  					<div class="box-header">
  						<i class="fa fa-briefcase"></i>
  						<h3 class="box-title">Latest Added Employers</h3>
  					</div>
  					<!-- /.box-header -->
  					<div class="box-body">
  						<ul class="products-list product-list-in-box">
  							<?php
								$res = $admin->list_employer(' ORDER BY A.EMPLOYER_ID DESC LIMIT 0,5');
								$count = ($res != '') ? $res->num_rows : 0;
								if ($count != 0) {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										$EMPLOYER_ID 		= $data['EMPLOYER_ID'];
										$REG_DATE 			= $data['REG_DATE'];
										$EMPLOYER_NAME		= $data['EMPLOYER_NAME'];
										$EMPLOYER_COMPANY_NAME		= $data['EMPLOYER_COMPANY_NAME'];
										$CITY_NAME = $data['CITY_NAME'];
										$CREATED_DATE = $data['REG_DATE'];
										$logo = $employer->get_employer_docs_single($EMPLOYER_ID, INST_LOGO);

								?>
  									<li class="item">
  										<div class="product-img">
  											<?= $logo ?>
  										</div>
  										<div class="product-info">
  											<a href="page.php?page=update-employer&id=<?= $EMPLOYER_ID ?>" class="product-title"><?= $EMPLOYER_COMPANY_NAME ?>
  												<span class="label  pull-right">View</span></a>
  											<span class="product-description">
  												<?= $EMPLOYER_NAME . ' , ' . $CITY_NAME ?>
  											</span>
  										</div>
  									</li>
  							<?php

										$srno++;
									}
								}
								?>
  						</ul>
  					</div>
  					<!-- /.box-body -->
  					<div class="box-footer clearfix no-border text-center">
  						<a href="page.php?page=list-employer" class="btn btn-default pull-right"><i class="fa fa-eye"></i> View All</a>
  					</div>
  				</div>

  			</section>
  			<section class="col-lg-7 connectedSortable">
  				<!-- TO DO List -->
  				<div class="box box-primary">
  					<div class="box-header">
  						<i class="fa fa-university"></i>
  						<h3 class="box-title">Today B'day</h3>
  					</div>
  					<!-- /.box-header -->
  					<div class="box-body">
  						<ul class="products-list product-list-in-box">
  							<?php

								$res = $admin->get_birth_day_report($city = '', date('m'), date('d'), ' ');
								$count = ($res != '') ? $res->num_rows : 0;
								if ($count != 0) {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										$INSTITUTE_ID 		= $data['INSTITUTE_ID'];
										$INSTITUTE_CODE 	= $data['INSTITUTE_CODE'];
										$INSTITUTE_NAME 	= $data['INSTITUTE_NAME'];
										$INSTITUTE_OWNER_NAME = $data['INSTITUTE_OWNER_NAME'];
										$CITY_NAME 			= $data['CITY_NAME'];
										$MOBILE 			= $data['MOBILE'];
										$DOB 				= $data['DOB_FORMATTED'];
										$DOB_DAY 			= $data['DOB_DAY'];
										$DOB_MONTH 			= $data['DOB_MONTH'];

										$logo = $access->get_curr_userphoto($INSTITUTE_ID, 2);

								?>
  									<li class="item">
  										<div class="product-img">
  											<img src="<?= $logo ?>" />
  										</div>
  										<div class="product-info" style="margin-left:10px;">
  											<a href="page.php?page=bday-reports" class="product-title"><?= $INSTITUTE_OWNER_NAME ?>
  												<a class="btn btn-lg btn-primary pull-right bg-maroon btn-flat"><?= $DOB ?></a></a>
  											<span class="product-description">
  												<?= $INSTITUTE_NAME ?>, <?= $CITY_NAME ?>
  											</span>
  										</div>
  									</li>
  							<?php

										$srno++;
									}
								}
								?>
  						</ul>
  					</div>
  					<!-- /.box-body -->
  					<div class="box-footer clearfix no-border text-center">
  						<a href="page.php?page=list-institutes" class="btn btn-default pull-right"><i class="fa fa-eye"></i> View All</a>
  					</div>
  				</div>


  			</section>
  			<!-- right col -->
  		</div>
  		<!-- /.row (main row) -->

  	</section>
  	<!-- /.content -->
  </div>