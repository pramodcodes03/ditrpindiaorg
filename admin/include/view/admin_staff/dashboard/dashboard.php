  <?php
	include_once('include/classes/student.class.php');
	include_once('include/classes/institute.class.php');
	include_once('include/classes/employer.class.php');
	include_once('include/classes/admin.class.php');
	include_once('include/classes/amc.class.php');
	$student 	= new student();
	$institute 	= new institute();
	$employer 	= new employer();
	$admin 	 	= new admin();
	$amc 	 	= new amc();
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

  			<div class="col-lg-3 col-xs-6 animated flipInX">
  				<!-- small box -->
  				<div class="small-box bg-navy">
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

  			<div class="col-lg-3 col-xs-6 animated flipInX">
  				<!-- small box -->
  				<div class="small-box bg-navy">
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
  					<a href="page.php?page=admission-reports" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<div class="col-lg-3 col-xs-6 animated flipInX">
  				<!-- small box -->
  				<div class="small-box bg-navy">
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
  						<!--<tr>
						<th><h4>Total</h4></th>
						<td><h3><a href="page.php?page=recharge-history" class="label  pull-right"><?= round($finalTotal) ?></a></h3></td>
					</tr>-->
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
  								<h4 style="margin:0px;"><a href="page.php?page=recharge-history&paymentmode1=OFFLINE&trantype=CREDIT" class="label  pull-right"><?= round($totalCredit) ?></a></h4>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4 style="margin:0px;font-size: 14px !important;">Total Debit</h4>
  							</th>
  							<td>
  								<h4 style="margin:0px;"><a href="page.php?page=recharge-history&paymentmode1=OFFLINE&trantype=DEBIT" class="label  pull-right"><?= round($totalDebit) ?></a></h4>
  							</td>
  						</tr>
  					</table>
  					<a href="page.php?page=recharge-history" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<!-- ./col -->

  			<div class="col-lg-3 col-xs-6 animated flipInX">
  				<!-- small box -->
  				<div class="small-box bg-navy">
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
  						<!--
					<tr>
						<th><h4>Appeared</h4></th>
						<td><h3><a href="page.php?page=admission-reports&coursetype=1&examstatus=3" class="label  pull-right"><?= $admin->student_reports_count('', '', '', ' AND C.COURSE_TYPE=1 AND A.EXAM_STATUS="3"') ?></a></h3></td>
					</tr>
			
					<tr>
						<th><h4>Appeared But Certificate Not Requested</h4></th>
						<td><h3><a href="page.php?page=admission-reports&coursetype=1&examstatus=3" class="label  pull-right"><?= $admin->total_exam_result(" AND RESULT_STATUS='Passed' AND APPLY_FOR_CERTIFICATE=0") ?></a></h3></td>
					</tr>
					-->
  					</table>
  					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<!-- ./col -->
  			<div class="clearfix"></div>
  			<div class="col-lg-3 col-xs-6 animated flipInX">
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
  			<div class="col-lg-3 col-xs-6 animated flipInX">
  				<!-- small box -->
  				<div class="small-box bg-navy">
  					<div class="inner">
  						<h3>Certificates</h3>
  					</div>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>Approval - Pending</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-requested-certificates&requeststatus=1" class="label  pull-right"><?= $admin->getTotalCertificateRequests('1') ?></a></h3>
  							</td>
  						</tr>

  						<tr>
  							<th>
  								<h4>Certificate - Approved</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-requested-certificates&requeststatus=2" class="label  pull-right"><?= $admin->getTotalCertificateRequests('2') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Order Certificate - Pending</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-print-approval-certificate&requeststatus=1" class="label  pull-right"><?= $admin->getTotalCertificateRequestsOrder('1') ?></a></h3>
  							</td>
  						</tr>

  						<tr>
  							<th>
  								<h4>Order Certificate - Approved</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-print-approval-certificate&requeststatus=2" class="label  pull-right"><?= $admin->getTotalCertificateRequestsOrder('2') ?></a></h3>
  							</td>
  						</tr>
  						<!-- 	<tr>
						<th><h4>Total Pending Fees</h4></th>
						<td><h3><a href="page.php?page=list-requested-certificates&requeststatus=2" class="label  pull-right"><?= round($admin->getTotalCertificateRequestsFees('1')) ?></a></h3></td>
					</tr>
					<tr>
						<th><h4>Total Approved Fees</h4></th>
						<td><h3><a href="page.php?page=list-requested-certificates&requeststatus=2" class="label  pull-right"><?= round($admin->getTotalCertificateRequestsFees('2')) ?></a></h3></td>
					</tr> -->
  					</table>
  					<a href="page.php?page=list-requested-certificates" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<!-- ./col -->

  			<!-------------------Typing ----------------------------------------------------->

  			<div class="col-lg-3 col-xs-6 animated flipInX">
  				<!-- small box -->
  				<div class="small-box bg-navy">
  					<div class="inner">

  						<h3>Typing Institutes</h3>
  					</div>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>TOTAL</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=typing-institute-list" class="label  pull-right"><?= $admin->getTotalTypingInstitutes('') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Approved</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=typing-institute-list" class="label pull-right"><?= $admin->getTotalTypingInstitutes('1') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Un-Approved</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=typing-institute-list" class="label  pull-right"><?= $admin->getTotalTypingInstitutes('0') ?></a></h3>
  							</td>
  						</tr>
  					</table>
  					<a href="page.php?page=typing-institute-list" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<!-- ./col -->
  			<!------------------------------------------------------------------------------>



  			<div class="col-lg-3 col-xs-6 animated flipInX">
  				<!-- small box -->
  				<div class="small-box bg-navy">
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
  			<div class="col-lg-3 col-xs-6 animated flipInX">
  				<!-- small box -->
  				<div class="small-box bg-navy">
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

  			<div class="col-lg-3 col-xs-6 animated flipInX">
  				<!-- small box -->
  				<div class="small-box bg-navy">
  					<div class="inner">
  						<?php
							$count =   $amc->get_toal_amc();
							?>
  						<h3>AMC</h3>
  					</div>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>TOTAL</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-amc" class="label  pull-right"><?php echo $count; ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4></h4>
  							</th>
  							<td>
  								<h3></h3>
  							</td>
  						</tr>
  					</table>
  					<a href="page.php?page=list-amc" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>

  			<div class="col-lg-3 col-xs-6 animated flipInX">
  				<!-- small box -->
  				<div class="small-box bg-navy">
  					<div class="inner">
  						<?php
							$count =   $amc->get_toal_amc();
							$preogress = $institute->helpSupport_progress();
							$closed = $institute->helpSupport_closed();
							$total_help = $institute->helpSupport_total();
							?>
  						<h3>HELP SUPPORT</h3>
  					</div>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>TOTAL</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-support" class="label  pull-right"><?php echo $total_help; ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>CLOSED</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-support" class="label  pull-right"><?php echo $closed; ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>WORK IN PROGRESS</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=list-support" class="label  pull-right quadrat"><?php echo $preogress; ?></a></h3>
  							</td>
  						</tr>
  					</table>
  					<a href="page.php?page=list-support" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>

  			<!--Admin Section Report handling related to internal use.  -->
  			<div class="col-lg-3 col-xs-6 animated flipInX">
  				<!-- small box -->
  				<div class="small-box bg-navy">
  					<div class="inner">

  						<h3>Institute Reports (Staff Only)</h3>
  					</div>
  					<table class="table">
  						<tr>
  							<th>
  								<h4>TOTAL</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=report-institute-list" class="label  pull-right"><?= $admin->getTotalInstitutes('', '') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Verified</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=report-institute-list&verified=1" class="label pull-right"><?= $admin->getTotalInstitutes('1', '') ?></a></h3>
  							</td>
  						</tr>
  						<tr>
  							<th>
  								<h4>Un-Verified</h4>
  							</th>
  							<td>
  								<h3><a href="page.php?page=report-institute-list&verified=0" class="label  pull-right"><?= $admin->getTotalInstitutes('0', '') ?></a></h3>
  							</td>
  						</tr>
  					</table>
  					<a href="page.php?page=report-institute-list" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  				</div>
  			</div>
  			<!-- ./col -->
  			<div class="clearfix"></div>
  		</div>
  	</section>
  	<!-- /.content -->
  </div>