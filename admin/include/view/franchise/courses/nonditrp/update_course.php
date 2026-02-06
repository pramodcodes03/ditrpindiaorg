<?php //include_once('include/controller/admin/courses/update_course.php'); 
?>
<?php
$course_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_course']) ? $_POST['update_course'] : '';
include_once('include/classes/course.class.php');
$course = new course();
if ($action != '') {
  $result = $course->institute_update_nonaicpe_course($course_id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=list-nonditrp-courses');
  }
}
/* get course details */
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
if ($user_role == 5) {
  $institute_id = $db->get_parent_id($user_role, $user_id);
  $staff_id = $user_id;
} else {
  $institute_id = $user_id;
  $staff_id = 0;
}

$res = $course->list_nonaicpe_courses($course_id, $institute_id);
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    $COURSE_ID     = $data['COURSE_ID'];
    $COURSE_AUTHORITY   = $data['COURSE_AUTHORITY'];
    $COURSE_DURATION = $data['COURSE_DURATION'];
    $COURSE_NAME   = $data['COURSE_NAME'];
    $COURSE_AWARD   = $data['COURSE_AWARD'];
    $COURSE_DETAILS = $data['COURSE_DETAILS'];
    $COURSE_ELIGIBILITY   = $data['COURSE_ELIGIBILITY'];
    $COURSE_FEES   = $data['COURSE_FEES'];
    $EXAM_FEES   = $data['EXAM_FEES'];
    $ACTIVE      = $data['ACTIVE'];
    $CREATED_BY   = $data['CREATED_BY'];
    $CREATED_ON   = $data['CREATED_ON'];
    $UPDATED_BY   = $data['UPDATED_BY'];
    $UPDATED_ON   = $data['UPDATED_ON'];
  }
}
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Update Course <?= isset($INSTITUTE_NAME) ? ': ' . $INSTITUTE_NAME : '' ?>

    </h1>
    <ol class="breadcrumb">
      <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="page.php?page=list-all-courses">Course</a></li>
      <li><a href="page.php?page=list-nonditrp-courses">NON-DITRP Course</a></li>
      <li class="active">Update</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <form class="form-horizontal form-validate" action="" method="post" enctype="multipart/form-data">

      <!-- left column -->
      <?php
      if (isset($success)) {
      ?>
        <div class="row">
          <div class="col-sm-12">
            <div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
              <h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
              <?= isset($message) ? $message : 'Please correct the errors.'; ?>
            </div>
          </div>
        </div>
      <?php
      }
      ?>

      <div class="row">


        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Update NON-DITRP Course</h3>
            </div>
            <div class="box-body">
              <input type="hidden" name="course_id" value="<?= isset($COURSE_ID) ? $COURSE_ID : '' ?>" />
              <input type="hidden" name="inst_id" value="<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '' ?>" />
              <div class="form-group col-sm-6 <?= (isset($errors['award'])) ? 'has-error' : '' ?>">
                <label for="award" class="col-sm-4 control-label">Course Code</label>
                <div class="col-sm-8">
                  <input class="form-control" id="award" name="award" placeholder="Course Code" value="<?= isset($_POST['award']) ? $_POST['award'] : $COURSE_AWARD ?>" type="text">
                  <span class="help-block"><?= isset($errors['award']) ? $errors['award'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['coursename'])) ? 'has-error' : '' ?>">
                <label for="coursename" class="col-sm-4 control-label">Course Name</label>
                <div class="col-sm-8">
                  <input class="form-control" id="coursename" name="coursename" placeholder="Course name" value="<?= isset($_POST['coursename']) ? $_POST['coursename'] : $COURSE_NAME ?>" type="text">
                  <span class="help-block"><?= isset($errors['coursename']) ? $errors['coursename'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['courseauth'])) ? 'has-error' : '' ?>">
                <label for="courseauth" class="col-sm-4 control-label">Certifying Authority</label>
                <div class="col-sm-8">
                  <input class="form-control" id="courseauth" name="courseauth" placeholder="Certifying Authority" value="<?= isset($_POST['courseauth']) ? $_POST['courseauth'] : $COURSE_AUTHORITY ?>" type="text">
                  <span class="help-block"><?= isset($errors['courseauth']) ? $errors['courseauth'] : '' ?></span>
                </div>
              </div>
              <div class="form-group col-sm-6 <?= (isset($errors['duration'])) ? 'has-error' : '' ?>">
                <label for="duration" class="col-sm-4 control-label">Course Duration</label>
                <div class="col-sm-8">
                  <input class="form-control" id="duration" name="duration" placeholder="Duration" value="<?= isset($_POST['duration']) ? $_POST['duration'] : $COURSE_DURATION ?>" type="text">
                  <span class="help-block"><?= isset($errors['duration']) ? $errors['duration'] : '' ?></span>
                </div>
              </div>
              <div class="form-group col-sm-6 <?= (isset($errors['examfees'])) ? 'has-error' : '' ?>">
                <label for="examfees" class="col-sm-4 control-label">Exam Fees to be paid</label>
                <div class="col-sm-8">
                  <input class="form-control" id="examfees" name="examfees" placeholder="Exam Fees" value="<?= isset($_POST['examfees']) ? $_POST['examfees'] : $EXAM_FEES ?>" type="text">
                  <span class="help-block"><?= isset($errors['examfees']) ? $errors['examfees'] : '' ?></span>
                </div>
              </div>
              <div class="form-group col-sm-6 <?= (isset($errors['coursefees'])) ? 'has-error' : '' ?>">
                <label for="coursefees" class="col-sm-4 control-label">Course Fees</label>
                <div class="col-sm-8">
                  <input class="form-control" id="coursefees" name="coursefees" placeholder="Course Fees" value="<?= isset($_POST['coursefees']) ? $_POST['coursefees'] : $COURSE_FEES ?>" type="text">
                  <span class="help-block"><?= isset($errors['coursefees']) ? $errors['coursefees'] : '' ?></span>
                </div>
              </div>
              <div class="form-group col-sm-12 <?= (isset($errors['detail'])) ? 'has-error' : '' ?>">
                <label for="detail" class="col-sm-2 control-label">Details</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="detail" name="detail" placeholder="Details" type="text"><?= isset($_POST['detail']) ? $_POST['detail'] : $COURSE_DETAILS ?></textarea>
                  <span class="help-block"><?= isset($errors['detail']) ? $errors['detail'] : '' ?></span>
                </div>
              </div>
              <div class="form-group col-sm-12 <?= (isset($errors['eligibility'])) ? 'has-error' : '' ?>">
                <label for="eligibility" class="col-sm-2 control-label">Eligibility</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="eligibility" name="eligibility" placeholder="Eligibility" type="text"><?= isset($_POST['eligibility']) ? $_POST['eligibility'] : $COURSE_ELIGIBILITY ?></textarea>
                  <span class="help-block"><?= isset($errors['eligibility']) ? $errors['eligibility'] : '' ?></span>
                </div>
              </div>


              <div class="form-group col-sm-12 ">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;
                ?>
                <label for="status" class="col-sm-2 control-label">Status</label>
                <div class="radio col-sm-10">
                  <label>
                    <input name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?> type="radio">
                    Active
                  </label>
                  <label>
                    <input name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?> type="radio">
                    Inactive
                  </label>
                </div>
              </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="page.php?page=list-nonaicpe-courses" class="btn btn-default">Cancel</a>
              <input type="submit" name="update_course" class="btn btn-info" value="Update Course" />
            </div>
          </div>
        </div>




      </div>
    </form>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>