<?php
include_once('include/classes/student.class.php');
include_once('include/classes/institute.class.php');
include_once('include/classes/admin.class.php');
$student     = new student();
$institute  = new institute();
$admin     = new admin();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

$month     = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
$day     = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d');

$month1     = isset($_REQUEST['month']) ? $_REQUEST['month'] : '';
$day1     = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
$user_id = $_SESSION['user_id'];
?>

<div class="content-wrapper">
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">

        <div class="col-12 mb-4 mb-xl-0">
          <h3 class="font-weight-bold">Welcome To <?= $_SESSION['user_fullname']; ?></h3>
        </div>

      </div>
      <div class="row">

        <div class="col-md-8">
          <!---   <a href="https://support.hellodigitalindia.co.in/help_support" class="btn btn-primary" style="float: right; margin:0px 5px;" target="_blank">Support </a> 
            <a href="BatchDetails" class="btn btn-warning" style="float: right; margin:0px 5px;">Batch Details </a> 
            <a href="Attendance" class="btn btn-danger" style="float: right">Take Attendance</a> 
            <a href="listStudentFees" class="btn btn-info" style="float: right; margin:0px 5px;">Fees Details</a> 
          
            <a href="studentAddAdmission" class="btn btn-success" style="float: right; margin:0px 5px;">Direct Admission </a>  -->

        </div>

      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 grid-margin transparent">
      <div class="row">


        <div class="col-md-3 mb-4 stretch-card transparent stretch-card1">
          <div class="card card-light-blue bggrey" style=" background-image: url('resources/icons/t5.jpg');background-size: 100% 100%;">
            <div class="card-body">
              <p class="card-title text-black">Certificates</p>
              <div class="row">
                <div class="col-12 text-black">
                  <h5 class="dashboard-text"><a href="page.php?page=listRequestedCertificates" class="dashboard-link">Approval Pending : <?= $admin->getTotalCertificateRequests('1') ?></a></h5>
                  <h5 class="dashboard-text"><a href="page.php?page=listRequestedCertificates" class="dashboard-link">Certificate Approved :<?= $admin->getTotalCertificateRequests('2') ?></a></h5>

                  <h5 class="dashboard-text"><a href="page.php?page=listOrderRequestedCertificates" class="dashboard-link"> Pending : <?= $admin->getTotalCertificateRequestsOrder('1') ?></a></h5>
                  <h5 class="dashboard-text"><a href="page.php?page=listOrderRequestedCertificates" class="dashboard-link">Order Certificate Approved : <?= $admin->getTotalCertificateRequestsOrder('2') ?></a></h5>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="col-md-3 mb-4 stretch-card transparent stretch-card1">
          <div class="card card-tale bggrey" style=" background-image: url('resources/icons/t1.jpg');background-size: 100% 100%;">
            <div class="card-body">
              <p class="card-title text-black">Students Enquiries</p>
              <div class="row">
                <div class="col-12 text-black">
                  <?php

                  $cond1  = " AND INSTITUTE_ID = $user_id";
                  $cond3  = " AND INSTITUTE_ID != $user_id";

                  ?>
                  <h5 class="dashboard-text">Total Enquiry : <?= $student->get_enquiry_count($cond3) ?></h5>
                  <h5 class="dashboard-text"> Total Admission : <?= $student->get_admission_count($cond3) ?></h5>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--  <a href="ourRechargeHistory" class="btn btn-warning btn1" style="padding: 10px 10px !important;">View History</a>  -->
        <div class="col-md-3 mb-4 stretch-card transparent stretch-card1">
          <div class="card data-icon-card-primary bgred" style=" background-image: url('resources/icons/t2.jpg');background-size: 100% 100%;">
            <div class="card-body">
              <p class="card-title text-black">Payments</p>
              <?php
              $totalOnline = $admin->getTotalOnlinePayment('success');
              $totalOffline = $admin->getTotalOfflinePayment(" AND TRANSACTION_TYPE='CREDIT' OR TRANSACTION_TYPE='DEBIT'");
              $totalCredit = $admin->getTotalOfflinePayment(" AND TRANSACTION_TYPE='CREDIT'");
              $totalDebit = $admin->getTotalOfflinePayment(" AND TRANSACTION_TYPE='DEBIT'");
              $finalTotal = $totalOnline + $totalOffline;
              ?>
              <div class="row">
                <div class="col-12 text-black">
                  <h5 class="dashboard-text">Online : <?= round($totalOnline)  ?></h5>
                  <h5 class="dashboard-text">Offline : <?= round($totalOffline) ?></h5>

                  <h5 class="dashboard-text">Total Credit : <?= round($totalCredit) ?></h5>
                  <h5 class="dashboard-text">Total Debit : <?= round($totalDebit) ?></h5>
                </div>
              </div>
            </div>
          </div>
        </div>



        <div class="col-md-3 mb-4 mb-lg-0 stretch-card transparent stretch-card1">
          <div class="card card-light-blue bgcrimson" style=" background-image: url('resources/icons/t3.jpg');background-size: 100% 100%;">
            <div class="card-body">
              <p class="card-title text-black">Wallet</p>
              <div class="row">
                <div class="col-12 text-black">
                  <?php
                  $totalWalletAmount =  $admin->getTotalWallet();
                  $totalWalletInstCount =  $admin->getTotalWalletInstitutesCount(" AND USER_ROLE IN(8)");
                  $totalWalletInstCountZeroAmt = $admin->getTotalWalletInstitutesCount(" AND USER_ROLE IN(8) AND TOTAL_BALANCE=0");
                  $totalWalletInstCountNonZeroAmt = $admin->getTotalWalletInstitutesCount(" AND USER_ROLE IN(8) AND TOTAL_BALANCE > 0");
                  ?>
                  <h5 class="dashboard-text">Total Amount : <?= round($totalWalletAmount) ?></h5>
                  <h5 class="dashboard-text">Total Institute : <?= round($totalWalletInstCount) ?></h5>
                  <h5 class="dashboard-text">Total Zero Balance Institute : <?= round($totalWalletInstCountZeroAmt) ?></h5>
                  <h5 class="dashboard-text">Total Non-Zero Balance Institute : <?= round($totalWalletInstCountNonZeroAmt) ?></h5>

                </div>
              </div>
            </div>
          </div>
        </div>



      </div>
    </div>
  </div>

  <div class="row">

    <div class="col-md-3 stretch-card transparent stretch-card1">
      <div class="card card-tale" style=" background-image: url('resources/icons/t4.jpg');background-size: 100% 100%;">
        <div class="card-body">
          <p class="card-title text-black">Course Section</p>
          <div class="row">
            <div class="col-12 text-black">
              <h5 class="dashboard-text"><a href="page.php?page=listCourse" class="dashboard-link">Total Courses : <?= $db->get_singlecourse_count() ?></a></h5>
              <h5 class="dashboard-text"><a href="page.php?page=listCourseMultiSub" class="dashboard-link">Total Courses Multi Subject : <?= $db->get_multicourse_count() ?></a></h5>
              <h5 class="dashboard-text"><a href="page.php?page=listTypingCourses" class="dashboard-link">Total Typing Courses : <?= $db->get_typingourse_count() ?></a></h5>
            </div>
          </div>
        </div>
      </div>
    </div>



    <div class="col-md-3 mb-4 stretch-card transparent stretch-card1">
      <div class="card card-light-blue bgred" style=" background-image: url('resources/icons/t6.jpg');background-size: 100% 100%;">
        <div class="card-body">
          <p class="card-title text-black">Franchise Enquiry</p>
          <div class="row">
            <div class="col-12 text-black">
              <h5 class="dashboard-text"><a href="page.php?page=listFranchiseEnquiry" class="dashboard-link">Total Enquiry : <?= $db->get_franchise_enquiry_count() ?></a></h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-4 stretch-card transparent stretch-card1">
      <div class="card card-light-blue bggrey" style=" background-image: url('resources/icons/t7.jpg');background-size: 100% 100%;">
        <div class="card-body">
          <p class="card-title text-black">Help Support</p>
          <div class="row">
            <div class="col-12 text-black">
              <?php
              $preogress = $institute->helpSupport_progress();
              $closed = $institute->helpSupport_closed();
              $total_help = $institute->helpSupport_total();
              ?>
              <h5 class="dashboard-text"><a href="page.php?page=listSupport" class="dashboard-link">Total : <?= $total_help ?></a></h5>
              <h5 class="dashboard-text"><a href="page.php?page=listSupport" class="dashboard-link">Closed :<?= $closed ?></a></h5>
              <h5 class="dashboard-text"><a href="page.php?page=listSupport" class="dashboard-link">Work In Progress (Pending) :<?= $preogress ?></a></h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-4 stretch-card transparent stretch-card1">
      <div class="card card-light-blue bgred" style=" background-image: url('resources/icons/t8.jpg');background-size: 100% 100%;">
        <div class="card-body">
          <p class="card-title text-black">Franchises</p>
          <div class="row">
            <div class="col-8 text-black">
              <h5 class="dashboard-text"><a href="page.php?page=listFranchise" class="dashboard-link">TOTAL : <?= $admin->getTotalInstitutes('', '') ?></a></h5>
              <h5 class="dashboard-text"><a href="page.php?page=listFranchise" class="dashboard-link">Verified : <?= $admin->getTotalInstitutes('1', '') ?></a></h5>
              <h5 class="dashboard-text"><a href="page.php?page=listFranchise" class="dashboard-link">Un-Verified : <?= $admin->getTotalInstitutes('0', '') ?></a></h5>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>