<?php
include_once('include/classes/student.class.php');
include_once('include/classes/institute.class.php');
include_once('include/classes/admin.class.php');
$student = new student();
$institute = new institute();
$admin = new admin();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

$month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('m');
$day = isset($_REQUEST['day']) ? $_REQUEST['day'] : date('d');

$month1 = isset($_REQUEST['month']) ? $_REQUEST['month'] : '';
$day1 = isset($_REQUEST['day']) ? $_REQUEST['day'] : '';
$student_id = isset($_REQUEST['student_id']) ? $_REQUEST['student_id'] : '';
$user_id = $_SESSION['user_id'];

?>

<div class="content-wrapper">
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="row">

        <div class="col-10 mb-4 mb-xl-0">
          <div class="form-group col-md-6">
            <label for="student_id">Search Student:</label>
            <select class="form-control select2" name="student_id" id="student_id"
              onchange="window.location = 'page.php?page=IMSDashboardStudent&student_id='+this.value">
              <?php echo $db->MenuItemsDropdown('student_details', "STUDENT_ID", "STUDENT_NAME", "STUDENT_ID, CONCAT(CONCAT(STUDENT_FNAME,' ',STUDENT_MNAME),' ',STUDENT_LNAME) STUDENT_NAME", $student_id, "  WHERE DELETE_FLAG=0 AND INSTITUTE_ID='$user_id'");
              ?>
            </select>
          </div>
        </div>
        <div class="col-2 mb-4 mb-xl-0">
          <input type="button" class="btn btn-link" onclick="printDiv('printableArea')" value="Print" />
        </div>

      </div>
    </div>
  </div>
  <div class="row" id="printableArea">
    <div id="accordion">
      <div class="card">
        <div class="card-header" id="headingOne">
          <h5 class="mb-0">
            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
              aria-controls="collapseOne">
              Student Details
            </button>
          </h5>
        </div>

        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">

          <div class="card-body">

            <?php include_once('student/student-profile.php'); ?>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" id="headingTwo">
          <h5 class="mb-0">
            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTwo"
              aria-expanded="false" aria-controls="collapseTwo">
              Course Details
            </button>
          </h5>
        </div>
        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
          <div class="card-body">
            <?php include_once('student/student-courses.php'); ?>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" id="headingThree">
          <h5 class="mb-0">
            <button class="btn btn-link " data-toggle="collapse" data-target="#collapseThree"
              aria-expanded="false" aria-controls="collapseThree">
              Fees Details
            </button>
          </h5>
        </div>
        <div id="collapseThree" class="collapse show" aria-labelledby="headingThree" data-parent="#accordion">
          <div class="card-body">
            <?php include_once('student/student-courses-fees.php'); ?>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" id="headingFour">
          <h5 class="mb-0">
            <button class="btn btn-link " data-toggle="collapse" data-target="#collapseFour"
              aria-expanded="false" aria-controls="collapseFour">
              Exam Results
            </button>
          </h5>
        </div>
        <div id="collapseFour" class="collapse show" aria-labelledby="headingThree" data-parent="#accordion">
          <div class="card-body">
            <?php include_once('student/student-exams.php'); ?>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header" id="headingFive">
          <h5 class="mb-0">
            <button class="btn btn-link " data-toggle="collapse" data-target="#collapseFive"
              aria-expanded="false" aria-controls="collapseFive">
              Student Attendance
            </button>
          </h5>
        </div>
        <div id="collapseFive" class="collapse show" aria-labelledby="headingThree" data-parent="#accordion">
          <div class="card-body">
            <?php include_once('student/student-attendance.php'); ?>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script type="text/javascript">
    function printDiv(divName) {
      var printContents = document.getElementById(divName).innerHTML;
      var originalContents = document.body.innerHTML;

      document.body.innerHTML = printContents;

      window.print();

      document.body.innerHTML = originalContents;
    }
  </script>