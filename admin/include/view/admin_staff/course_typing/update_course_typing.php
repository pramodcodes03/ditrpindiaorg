<?php

$course_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_course']) ? $_POST['update_course'] : '';
include_once('include/classes/coursetyping.class.php');
$coursetyping = new coursetyping();
if ($action != '') {
  $result = $coursetyping->update_course_typing($course_id);
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
/* get course details */
$res = $coursetyping->list_courses_typing($course_id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    $TYPING_COURSE_ID     = $data['TYPING_COURSE_ID'];
    $TYPING_COURSE_CODE   = $data['TYPING_COURSE_CODE'];
    $TYPING_COURSE_DURATION = $data['TYPING_COURSE_DURATION'];
    $TYPING_COURSE_NAME   = $data['TYPING_COURSE_NAME'];
    $TYPING_COURSE_DETAILS = $data['TYPING_COURSE_DETAILS'];
    $TYPING_COURSE_ELIGIBILITY   = $data['TYPING_COURSE_ELIGIBILITY'];
    $TYPING_COURSE_FEES  = $data['TYPING_COURSE_FEES'];
    $TYPING_COURSE_MRP  = $data['TYPING_COURSE_MRP'];
    $TYPING_MINIMUM_AMOUNT  = $data['TYPING_MINIMUM_AMOUNT'];
    $ACTIVE1     = $data['ACTIVE'];
    $CREATED_BY   = $data['CREATED_BY'];
    $CREATED_ON   = $data['CREATED_ON'];
    $UPDATED_BY   = $data['UPDATED_BY'];
    $UPDATED_ON   = $data['UPDATED_ON'];
    $TYPING_COURSE_IMAGE   = $data['TYPING_COURSE_IMAGE'];
    $DISPLAY_FEES   = $data['DISPLAY_FEES'];
  }
}

?><div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Update Typing Courses
          </h4>
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
            <?php
            if (isset($success)) {
            ?>
              <div class="row">
                <div class="col-sm-12">
                  <div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                    <h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
                    <?= isset($message) ? $message : 'Please correct the errors.'; ?>
                    <?php
                    echo "<ul>";
                    foreach ($errors as $error) {
                      echo "<li>$error</li>";
                    }
                    echo "<ul>";
                    ?>
                  </div>
                </div>
              </div>
            <?php
            }
            ?>
            <input type="hidden" class="form-control" name="course_id" placeholder="course_id" value="<?= $TYPING_COURSE_ID ?>">

            <div class="row">
              <div class="col-md-4 form-group">
                <label for="exampleInputName1">Course Code</label>
                <input type="text" class="form-control" id="exampleInputName1" name="coursecode" placeholder="coursecode" value="<?= isset($_POST['coursecode']) ? $_POST['coursecode'] : $TYPING_COURSE_CODE  ?>">
              </div>

              <div class="col-md-8 form-group">
                <label for="exampleInputName1">Course Name</label>
                <input type="text" class="form-control" id="exampleInputName1" name="coursename" value="<?= isset($_POST['coursename']) ? $_POST['coursename'] : $TYPING_COURSE_NAME  ?>">
              </div>

              <div class="col-md-3  form-group">
                <label for="exampleInputName1">Course Duration</label>
                <input type="text" class="form-control" id="exampleInputName1" name="duration" value="<?= isset($_POST['duration']) ? $_POST['duration'] : $TYPING_COURSE_DURATION ?>">
              </div>


              <div class="col-md-6  form-group col-sm-12  <?= (isset($errors['fees0'])) ? 'has-error' : '' ?>">
                <label class="control-label">Course Subjects Name</label>
                <div class="row">

                  <?php
                  $docData = $coursetyping->get_course_typing_subjects($TYPING_COURSE_ID, false);
                  $sr = 0;
                  if (!empty($docData)) {

                    foreach ($docData as $key => $value) {
                      extract($value);
                  ?>
                      <input type="hidden" name="course_typing_id<?= $sr ?>" value="<?= $TYPING_COURSE_SUBJECT_ID  ?>" />

                      <input class="col-md-4 form-control" id="subject<?= $sr ?>" name="subject<?= $sr ?>" value="<?= isset($_POST['subject' . $sr]) ? $_POST['subject' . $sr] : $TYPING_COURSE_SUBJECT_NAME ?>" type="text" style="margin:2px 15px" maxlength="100">

                      <input class="col-md-3 form-control" id="speed<?= $sr ?>" name="speed<?= $sr ?>" value="<?= isset($_POST['speed' . $sr]) ? $_POST['speed' . $sr] : $TYPING_COURSE_SPEED ?>" type="text" style="margin:2px 15px" maxlength="100">

                      <span class="help-block"><?= isset($errors['subject' . $sr]) ? $errors['subject' . $sr] : '' ?></span>

                  <?php
                      $sr++;
                    }
                  }
                  ?>
                  <input type="hidden" name="filecount2" id="filecount2" value="<?= $sr ?>" />
                  <a href="javascript:void(0)" class="col-md-2 btn btn-danger" onclick="addMoreSubjectsTyping()"><i class="fa fa-plus"></i> Add Subjects</a>
                </div>

                <div id="add_more_subjects_typing">

                </div>
              </div>

              <div class="form-group col-md-6  <?= (isset($errors['fees0'])) ? 'has-error' : '' ?>">

                <label for="plan0" class="control-label">Selet Institute Plan</label>
                <div class="row">
                  <div class="col-md-10">
                    <div class="row">
                      <?php
                      $docData = $coursetyping->get_course_plans_typing($TYPING_COURSE_ID, false);
                      $sr = 0;
                      if (!empty($docData)) {

                        foreach ($docData as $key => $value) {
                          extract($value);
                      ?>
                          <input type="hidden" name="course_plan_fees_id<?= $sr ?>" value="<?= $COURSE_PLAN_FEES_ID ?>" />

                          <div class="col-md-6" style="margin-bottom:5px">
                            <select class="form-control" id="plan<?= $sr ?>" name="plan<?= $sr ?>">
                              <?php
                              $plan = isset($_POST['plan' . $sr]) ? $_POST['plan' . $sr] : $PLAN_ID;
                              echo $db->MenuItemsDropdown('institute_plans', 'PLAN_ID', 'PLAN_NAME', 'PLAN_ID,PLAN_NAME', $plan, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                              ?>
                            </select>
                            <span class="help-block"><?= isset($errors['plan' . $sr]) ? $errors['plan' . $sr] : '' ?></span>
                          </div>

                          <div class="col-md-6" style="margin-bottom:5px">
                            <input class="form-control" id="fees<?= $sr ?>" name="fees<?= $sr ?>" placeholder="Exam Fees" value="<?= isset($_POST['fees' . $sr]) ? $_POST['fees' . $sr] : $COURSE_FEES ?>" type="text">
                            <span class="help-block"><?= isset($errors['fees' . $sr]) ? $errors['fees' . $sr] : '' ?></span>
                          </div>

                      <?php
                          $sr++;
                        }
                      }
                      ?>
                      <input type="hidden" name="filecount1" id="filecount1" value="<?= $sr ?>" />
                    </div>
                  </div>
                  <div class="col-md-2">
                    <a href="javascript:void(0)" class="btn  btn-warning btn1" onclick="addMorePlans()"><i class="fa fa-plus"></i> Add Plans</a>
                  </div>
                </div>


                <div id="add_more_plans">

                </div>
              </div>

              <div class="col-md-6 form-group">
                <label for="exampleTextarea1">Course Syllabus</label>
                <textarea class="form-control" id="tinyMceExample" rows="4" name="detail"><?= isset($_POST['detail']) ? $_POST['detail'] : $TYPING_COURSE_DETAILS ?></textarea>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleTextarea1">Eligibility</label>
                <textarea class="form-control" id="tinyMceExample1" rows="4" name="eligibility"><?= isset($_POST['eligibility']) ? $_POST['eligibility'] : $TYPING_COURSE_ELIGIBILITY ?></textarea>
              </div>


              <div class="col-md-6 form-group">
                <label>Course Image</label>

                <div class="row">
                  <input type="file" name="course_img" class="file-upload-default">
                  <div class="input-group col-md-6" style="height: fit-content;">
                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Course Image">
                    <span class="input-group-append">
                      <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                    </span>
                  </div>
                  <div class="col-md-6">
                    <?php
                    if ($TYPING_COURSE_IMAGE != '') {
                      $path = COURSE_WITH_TYPING_MATERIAL_PATH . '/' . $TYPING_COURSE_ID . '/' . $TYPING_COURSE_IMAGE;
                      echo '<br> <img src="' . $path . '" class="img img-responsive" style="height:100px;"  />';
                    }
                    ?>
                  </div>
                </div>
              </div>

              <div class="col-md-12 form-group row">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;
                ?>
                <label class="col-sm-3 col-form-label">Status</label>
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
            <input type="submit" name="update_course" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=listTypingCourses" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>