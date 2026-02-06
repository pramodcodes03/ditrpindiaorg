<?php
$action = isset($_POST['add_course']) ? $_POST['add_course'] : '';
include_once('include/classes/coursetyping.class.php');
$coursetyping = new coursetyping();
if ($action != '') {
  $result = $coursetyping->add_course_typing();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listTypingCourses');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"> Add Typing Courses </h4>
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
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
              <div class="col-md-4 form-group">
                <label>Course Code</label>
                <input type="text" class="form-control" id="coursecode" name="coursecode" placeholder="coursecode" value="<?= isset($_POST['coursecode']) ? $_POST['coursecode'] : '' ?>">
              </div>
              <div class="col-md-8 form-group">
                <label>Course Name</label>
                <input type="text" class="form-control" id="coursename" name="coursename" value="<?= isset($_POST['coursename']) ? $_POST['coursename'] : '' ?>">
              </div>

              <div class="col-md-3 form-group">
                <label>Course Duration</label>
                <input type="text" class="form-control" id="duration" name="duration" value="<?= isset($_POST['duration']) ? $_POST['duration'] : '' ?>">
              </div>

              <div class="form-group col-md-6 <?= (isset($errors['fees0'])) ? 'has-error' : '' ?>">
                <label for="plan0" class="col-sm-6 control-label">Select Institute Plan</label>
                <div class="row">
                  <div class="col-md-5">
                    <select class="form-control" id="plan0" name="plan0">
                      <?php
                      $plan0 = isset($_POST['plan0']) ? $_POST['plan0'] : '';
                      echo $db->MenuItemsDropdown('institute_plans', 'PLAN_ID', 'PLAN_NAME', 'PLAN_ID,PLAN_NAME', $plan0, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                      ?>
                    </select>
                    <span class="help-block"><?= isset($errors['plan0']) ? $errors['plan0'] : '' ?></span>
                  </div>
                  <div class="col-md-5">
                    <input class="form-control" id="fees0" name="fees0" placeholder="Exam Fees" value="<?= isset($_POST['fees0']) ? $_POST['fees0'] : '' ?>" type="text">
                    <span class="help-block"><?= isset($errors['fees0']) ? $errors['fees0'] : '' ?></span>
                  </div>
                  <input type="hidden" name="filecount1" id="filecount1" value="1" />
                  <div class="col-md-2">
                    <a href="javascript:void(0)" class="btn  btn-warning btn1" onclick="addMorePlans()"><i class="fa fa-plus"></i> Add Plans</a>
                  </div>
                </div>
                <div id="add_more_plans">

                </div>
              </div>

              <div class="col-md-6 form-group">

                <label class="control-label">Course Subjects Name</label>
                <div class="row">
                  <input class="col-md-4 form-control" id="subject0" name="subject0" placeholder="Enter Subject Name" value="<?= isset($_POST['subject0']) ? $_POST['subject0'] : '' ?>" type="text" style="margin:0px 15px" maxlength="100">

                  <input class="col-md-3 form-control" id="speed0" name="speed0" placeholder="Enter Speed (WPM)" value="<?= isset($_POST['speed0']) ? $_POST['speed0'] : '' ?>" type="text" style="margin:0px 15px" maxlength="100">

                  <input type="hidden" name="filecount2" id="filecount2" value="1" />

                  <a href="javascript:void(0)" class="col-md-2 btn btn-danger" onclick="addMoreSubjectsTyping()"><i class="fa fa-plus"></i> Add Subjects</a>
                </div>
                <div id="add_more_subjects_typing">

                </div>
                <span class="help-block"><?= isset($errors['subject0']) ? $errors['subject0'] : '' ?></span>

              </div>

              <div class="col-md-6 form-group">
                <FILE_LABEL>Course Syllabus</label>
                  <textarea class="form-control" id="tinyMceExample" rows="4" name="detail"><?= isset($_POST['detail']) ? $_POST['detail'] : '' ?></textarea>
              </div>
              <div class="col-md-6 form-group">
                <FILE_LABEL>Eligibility</label>
                  <textarea class="form-control" id="tinyMceExample1" rows="4" name="eligibility"><?= isset($_POST['eligibility']) ? $_POST['eligibility'] : '' ?></textarea>
              </div>
              <div class="row col-md-12">
                <div class="col-md-6 form-group">
                  <label>Course Image</label>
                  <input type="file" name="course_img" class="file-upload-default">
                  <div class="input-group col-xs-12">
                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Course Image">
                    <span class="input-group-append">
                      <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                    </span>
                  </div>
                </div>
              </div>

              <div class="col-md-12 form-group row">
                <label class="col-sm-3 col-form-label">Status</label>
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : 1;
                ?>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?>>
                      Active
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?>>
                      Inactive
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <input type="submit" name="add_course" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=listTypingCourses" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>