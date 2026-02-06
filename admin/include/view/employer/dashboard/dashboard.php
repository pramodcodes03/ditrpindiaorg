<style>
  .dropbtn {
    background-color: #065286;
    color: white;
    padding: 10px;
    font-size: 15px;
    border: none;
    cursor: pointer;
    font-weight: 600;
  }

  .dropbtn:hover,
  .dropbtn:focus {
    background-color: #2980B9;
  }

  .dropdown {
    position: relative;
    display: inline-block;
  }

  .dropdown-content {
    display: none;
    position: absolute;
    background-color: #fdffd2;
    min-width: max-content;
    overflow: auto;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
  }

  .dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }

  .dropdown a:hover {
    background-color: #ddd;
  }

  .show {
    display: block;
  }
</style>

<?php
$todays_date = date("Y-m-d");
include_once('include/classes/student.class.php');
include_once('include/classes/institute.class.php');
include_once('include/classes/admin.class.php');
$student     = new student();
$institute  = new institute();
$admin     = new admin();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

if ($user_role == 3) {
  $institute_id = $db->get_parent_id($user_role, $user_id);
  $staff_id = $user_id;
} else {
  $institute_id = $user_id;
  $staff_id = 0;
}


$month     = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
$day     = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d');

$month1     = isset($_REQUEST['month']) ? $_REQUEST['month'] : '';
$day1     = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';


include_once('include/classes/tools.class.php');
$tools = new tools();

$res = $tools->list_marquee('', " AND inst_id = '1'");
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    extract($data);
  }
}


?>
<?php
if ($name != '') {
?>
  <marquee class="marqueeTag"><?= html_entity_decode($name) ?></marquee>
<?php
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">

        <div class="col-12 mb-4 mb-xl-0">
          <h3 class="font-weight-bold">Welcome To <?= $_SESSION['user_fullname']; ?></h3>

          <ol class="breadcrumb">
            <li><a href="tel:7877994994" download="" style="color:red;"><i class="fa fa-phone"></i>Help Line +91 7620052713</a></li>
            <li><a href="resources/howto.pdf" download="" style="color:blue;"><i class="fa fa-file-pdf-o"></i>HOW TO UPLOAD DIGITAL SIGNATURE</a></li>
            <li><a href="https://www.docsketch.com/online-signature/type/" target="_blank" style="color:blue;"><i class="fa fa-link"></i> DIGITAL SIGNATURE LINK</a></li>
          </ol>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <select class="form-control select2" name="student_id" id="student_id" onchange="window.location = 'page.php?page=IMSDashboardStudent&student_id='+this.value">
              <?php echo $db->MenuItemsDropdown('student_details', "STUDENT_ID", "STUDENT_NAME", "STUDENT_ID, CONCAT(CONCAT(STUDENT_FNAME,' ',STUDENT_MNAME),' ',STUDENT_LNAME) STUDENT_NAME", $student_id, " WHERE DELETE_FLAG=0 AND INSTITUTE_ID='$institute_id'");
              ?>
            </select>
          </div>
        </div>
        <div class="col-md-8">

          <a href="page.php?page=BatchDetails" class="btn btn-danger" style="  background: #e90707 !important;float: right; margin:0px 5px;">Batch Details </a>

          <?php if ($db->permission('list_attendance')) { ?>
            <a href="page.php?page=Attendance" class="btn btn-info" style="   background: #06298f!important;float: right">Take Attendance</a>
          <?php } ?>
          <?php if ($db->permission('list_student_fees')) { ?>
            <a href="page.php?page=listStudentFees" class="btn btn-danger" style="  background: #e90707 !important;float: right; margin:0px 5px;">Fees Details</a>
          <?php } ?>
          <?php if ($db->permission('add_admission')) { ?>
            <a href="page.php?page=studentAddAdmission" class="btn btn-success" style="  background: #06298f!important;float: right; margin:0px 5px;">Direct Admission </a>
          <?php } ?>

          <div class="dropdown">
            <button onclick="myFunction()" class="dropbtn">Our Products</button>
            <div id="myDropdown" class="dropdown-content">
              <?php
              include_once('include/classes/tools.class.php');
              $tools = new tools();
              $res = $tools->list_product('', '');
              if ($res != '') {
                $srno = 1;
                while ($data = $res->fetch_assoc()) {
                  extract($data);
              ?>
                  <a href="<?= $link ?>" target="_blank"><?= $name ?></a>
              <?php
                  $srno++;
                }
              }

              ?>
            </div>
          </div>


        </div>

      </div>
    </div>

  </div>

  <div class="row">
    <div class="col-md-12 grid-margin transparent">
      <div class="row">
        <?php if ($db->permission('list_enquiry') || $db->permission('list_admission')) { ?>
          <div class="col-md-3 mb-4 stretch-card transparent stretch-card1">
            <div class="card card-tale" style=" background-image: url('resources/icons/a1.jpg');background-size: 100% 100%;">
              <div class="card-body">
                <p class="card-title text-white">Students Enquiries</p>
                <div class="row">
                  <div class="col-8 text-white">
                    <?php

                    $cond1  = " AND INSTITUTE_ID = $institute_id";
                    $cond3  = " AND INSTITUTE_ID != $institute_id";

                    ?>
                    <h5 class="dashboard-text">Total Enquiry : <?= $student->get_enquiry_count($cond1) ?></h5>
                    <h5 class="dashboard-text">Total Admission : <?= $student->get_admission_count($cond1) ?></h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>

        <div class="col-md-3 mb-4 stretch-card transparent stretch-card1">
          <div class="card data-icon-card-primary" style=" background-image: url('resources/icons/a2.jpg');background-size: 100% 100%;">
            <div class="card-body">
              <p class="card-title text-white">Exam Wallet Amount</p>
              <div class="row">
                <div class="col-12 text-white">
                  <h3 class="dashboard-text" style="font-size: 20px;"> INR <?= $db->get_institute_walletamount($institute_id, '8') ?> </h3>

                  <?php
                  $TOTAL_FEES_PAID1 = $student->get_allpaidfeestotal($institute_id);
                  $total_expense = $db->get_totalexpenses($institute_id);
                  $inst_wallet = $TOTAL_FEES_PAID1 - $total_expense;
                  ?>
                  <p class="card-title text-white">Your Wallet Amount</p>
                  <h3 class="dashboard-text" style="font-size: 20px;"> INR <?= $inst_wallet ?> </h3>

                </div>
              </div>
            </div>
          </div>
        </div>

        <?php if ($db->permission('list_student_fees')) { ?>
          <div class="col-md-3 mb-4 mb-lg-0 stretch-card transparent stretch-card1">
            <div class="card card-light-blue" style=" background-image: url('resources/icons/a3.jpg');background-size: 100% 100%;">
              <div class="card-body">
                <p class="card-title text-white">Fees Section</p>
                <div class="row">
                  <div class="col-12 text-white">
                    <?php
                    $ALL_COURSE_FEES = $TOTAL_FEES_PAID = $TOTAL_FEES_BALANCE = 0;

                    $ALL_COURSE_FEES = $student->get_allcoursefeestotal($institute_id);
                    $TOTAL_FEES_PAID = $student->get_allpaidfeestotal($institute_id);
                    $TOTAL_FEES_BALANCE = $ALL_COURSE_FEES - $TOTAL_FEES_PAID;

                    ?>
                    <h5 class="dashboard-text">Paid Fees : <?= $TOTAL_FEES_PAID ?></h5>
                    <h5 class="dashboard-text">Balance Fees : <?= $TOTAL_FEES_BALANCE ?></h5>
                    <h5 class="dashboard-text">Total Fees : <?= $ALL_COURSE_FEES ?></h5>

                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
        <?php if ($db->permission('list_expenses')) { ?>
          <div class="col-md-3 mb-4 mb-lg-0 stretch-card transparent stretch-card1">
            <div class="card card-light-blue" style=" background-image: url('resources/icons/a4.jpg');background-size: 100% 100%;">
              <div class="card-body">
                <p class="card-title text-white">Expense Section</p>
                <div class="row">
                  <div class="col-12 text-white">
                    <h5 class="dashboard-text">Total Expenses : INR <?= $db->get_totalexpenses($institute_id) ?> </h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>


      </div>
    </div>
  </div>

  <div class="row">



    <div class="col-md-3 mb-4 stretch-card transparent stretch-card1">
      <div class="card card-light-blue" style=" background-image: url('resources/icons/a5.jpg');background-size: 100% 100%;">
        <div class="card-body">
          <p class="card-title text-white">Certificates</p>
          <div class="row">
            <div class="col-12 text-white">
              <h5 class="dashboard-text">Approval Pending : <?= $admin->getTotalCertificateRequests('1', " AND INSTITUTE_ID = $institute_id ") ?></h5>
              <h5 class="dashboard-text">Certificate Approved :<?= $admin->getTotalCertificateRequests('2', " AND INSTITUTE_ID = $institute_id ") ?></h5>

              <h5 class="dashboard-text">Order Pending : <?= $admin->getTotalCertificateRequestsOrder('1', " AND INSTITUTE_ID = $institute_id ") ?></h5>
              <h5 class="dashboard-text">Order Certificate Approved : <?= $admin->getTotalCertificateRequestsOrder('2', " AND INSTITUTE_ID = $institute_id ") ?></h5>

            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 mb-4 stretch-card transparent stretch-card1">
      <div class="card card-light-blue bgred" style=" background-image: url('resources/icons/a6.jpg');background-size: 100% 100%;">
        <div class="card-body">
          <p class="card-title text-white">Help Support</p>
          <div class="row">
            <div class="col-12 text-white">
              <?php
              $preogress = $institute->helpSupport_progress();
              $closed = $institute->helpSupport_closed();
              $total_help = $institute->helpSupport_total();
              ?>
              <h5 class="dashboard-text">Total : <?= $total_help ?></h5>
              <h5 class="dashboard-text">Closed :<?= $closed ?></h5>
              <h5 class="dashboard-text">Work In Progress (Pending) :<?= $preogress ?></h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-4 mb-lg-0 stretch-card transparent stretch-card1">
      <div class="card card-light-blue bgred" style=" background-image: url('resources/icons/a7.jpg');background-size: 100% 100%;">
        <div class="card-body">
          <p class="card-title text-white">ATC CERTIFICATE</p>
          <div class="row">
            <div class="col-12 text-white">

              <a href="page.php?page=printFranchiseCertificate&inst=<?php echo $institute_id ?>" target="_blank" class="btn btn-primary btn1">Download </a>

              <br /><br />
              <a href="page.php?page=print-performance-certificate-cover&inst[]=<?php echo $institute_id ?>" target="_blank" class="btn btn-warning btn1">Performance Certificate </a>

            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
    $res = $institute->list_institute($institute_id, '');
    if ($res != '') {
      $srno = 1;
      while ($data = $res->fetch_assoc()) {
        extract($data);
        //print_r($data);
      }
    }
    if ($FESTIVAL_PACKAGE == 1 && ($FESTIVAL_LAST_DATE >= $todays_date)) {
    ?>
      <div class="col-md-3 mb-4 mb-lg-0 stretch-card transparent stretch-card1">
        <div class="card card-light-blue bgred" style=" background-image: url('resources/icons/a7.jpg');background-size: 100% 100%;">
          <div class="card-body">
            <p class="card-title text-white">Festival Images</p>
            <div class="row">
              <div class="col-12 text-white">

                <a href="page.php?page=list-festival" target="_blank" class="btn btn-primary btn1">Festival List </a>


              </div>
            </div>
          </div>
        </div>
      </div>

    <?php
    }
    ?>


  </div>

  <div class="row">
    <div class="col-md-4 grid-margin transparent">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Today's Birthday List
          </h4>
          <ul class="icon-data-list">
            <?php
            $month     = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
            $day     = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d');
            $cond = '';
            $res = $admin->get_birth_day_report($month, $day, " AND INSTITUTE_ID = $institute_id ORDER BY DAY(A.STUDENT_DOB) ASC");
            if ($res != '') {
              $srno = 1;

              while ($data = $res->fetch_assoc()) {
                //extract($data);
                $STUDENT_ID      = $data['STUDENT_ID'];
                $STUDENT_FNAME   = $data['STUDENT_FNAME'];
                $STUDENT_MNAME   = $data['STUDENT_MNAME'];
                $STUDENT_LNAME   = $data['STUDENT_LNAME'];
                $STUDENT_PHOTO   = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $data['STUDENT_PHOTO'];
                $STUDENT_MOBILE       = $data['STUDENT_MOBILE'];
                $STUDENT_EMAIL       = $data['STUDENT_EMAIL'];
                $DOB         = $data['DOB_FORMATTED'];
                $DOB_DAY       = $data['DOB_DAY'];
                $DOB_MONTH       = $data['DOB_MONTH'];
                $today_month = date('m');
                $today_day = date('d');
            ?>
                <li>
                  <div class="d-flex">
                    <img src="<?= $STUDENT_PHOTO ?>" alt="user">
                    <div>
                      <p class="title2"><?= $STUDENT_FNAME ?><?= $STUDENT_LNAME ?></p>
                    </div>
                  </div>
                </li>

            <?php
                $srno++;
              }
            }
            ?>
          </ul>
        </div>
      </div>
    </div>


  </div>
</div>


<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">


    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <h5 style="color: #f00;"><b>IMPORTANT NOTE:</b></h5>

        <?php
        include_once('include/classes/websiteManage.class.php');
        $websiteManage = new websiteManage();
        $res = $websiteManage->list_advertise('', '');
        if ($res != '') {
          $srno = 1;
          while ($data = $res->fetch_assoc()) {
            extract($data);

            if ($website == '2') {
              $photo = '';
              $photo = ADVERTISE_PATH . '/' . $id . '/' . $image;

              echo "<a href='$link' target='_blank'> <img src='$photo' style='width:100%; height:100%; border-radius:0;'/></a>";
            }
          }
        }
        ?>
      </div>

    </div>
  </div>
</div>


<script>
  /* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
  function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
  }

  // Close the dropdown if the user clicks outside of it
  window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }
</script>