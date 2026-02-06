<?php
$action = isset($_POST['add_course']) ? $_POST['add_course'] : '';
include_once('include/classes/coursemultisub.class.php');
$coursemultisub = new coursemultisub();
if ($action != '') {
  $result = $coursemultisub->add_course_multi_sub();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listCourseMultiSub');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add Course With Multiple Subjects
            <a href="#" class="btn btn-warning" style="float: right; margin-right:20px;" target="_blank">How To Upload Videos Link</a>
          </h4>
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
              <div class="col-md-3 form-group">
                <label>Course Code</label>
                <input type="text" class="form-control" id="coursecode" name="coursecode" placeholder="coursecode" value="<?= isset($_POST['coursecode']) ? $_POST['coursecode'] : '' ?>">
              </div>
              <div class="col-md-3 form-group">
                <label for="exampleFormControlSelect3">Award</label>
                <select class="form-control form-control-sm" id="award" id="award" name="award">
                  <?php
                  $award = isset($_POST['award']) ? $_POST['award'] : '';
                  echo $db->MenuItemsDropdown('course_awards', 'AWARD_ID', 'AWARD', 'AWARD_ID,AWARD', $award, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                  ?>
                </select>
              </div>
              <div class="col-md-6 form-group">
                <label>Course Name</label>
                <input type="text" class="form-control" id="coursename" name="coursename" value="<?= isset($_POST['coursename']) ? $_POST['coursename'] : '' ?>">
              </div>

              <div class="col-md-3 form-group">
                <label>Course Duration</label>
                <input type="text" class="form-control" id="duration" name="duration" value="<?= isset($_POST['duration']) ? $_POST['duration'] : '' ?>">
              </div>

              <div class="col-md-6  form-group <?= (isset($errors['subject0'])) ? 'has-error' : '' ?>">

                <label for="subject0" class="control-label">Course Subjects Name</label>
                <div class="row">
                  <input class="col-md-7 form-control" id="subject0" name="subject0" placeholder="Enter Subject Name" value="<?= isset($_POST['subject0']) ? $_POST['subject0'] : '' ?>" type="text" style="margin:0px 15px" maxlength="100">


                  <input type="hidden" name="filecount2" id="filecount2" value="1" />

                  <a href="javascript:void(0)" class="col-md-4 btn btn-danger" onclick="addMoreSubjects()"><i class="fa fa-plus"></i> Add Subjects</a>
                </div>
                <div id="add_more_subjects">

                </div>
                <span class="help-block"><?= isset($errors['subject0']) ? $errors['subject0'] : '' ?></span>
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

              <div class="col-md-12 form-group <?= (isset($errors['coursematerial0'])) ? 'has-error' : '' ?>">
                <label>Course Material Pdf Files</label>
                <div class="row col-md-12">
                  <input class="col-md-4 form-control" id="filetitle0" name="filetitle0" placeholder="File Title" value="<?= isset($_POST['filetitle0']) ? $_POST['filetitle0'] : '' ?>" type="text">
                  <span class="help-block"><?= isset($errors['filetitle0']) ? $errors['filetitle0'] : '' ?></span>
                  <input class="col-md-4" id="coursematerial0" name="coursematerial0" type="file">
                  <p class="help-block"><?= (isset($errors['coursematerial0'])) ? $errors['coursematerial0'] : '' ?> </p>
                  <input type="hidden" name="filecount" id="filecount" value="1" />
                  <a href="javascript:void(0)" class="col-md-2 btn btn-warning" onclick="addMoreCourseMaterial()" style="padding: 15px;">Add files</a>

                </div>

                <div id="add_more_files">

                </div>
              </div>

              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Video Link 1 </label>
                <input type="text" class="form-control" name="video1" value="">
                <span class="help-block"><?= isset($errors['video1']) ? $errors['video1'] : '' ?></span>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Video Link 2 </label>
                <input type="text" class="form-control" name="video2" value="">
                <span class="help-block"><?= isset($errors['video2']) ? $errors['video2'] : '' ?></span>
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

              <div class="col-md-6 form-group <?= (isset($errors['videomaterial0'])) ? 'has-error' : '' ?>">
                <label>Course Video Links (Add YouTube Embedded Links)</label>
                <div class="">
                  <div class="form-group <?= (isset($errors['videotitle0'])) ? 'has-error' : '' ?>">
                    <div class="col-sm-6" style="float: left">
                      <input class="form-control" id="videotitle0" name="videotitle0" value="<?= isset($_POST['videotitle0']) ? $_POST['videotitle0'] : '' ?>" type="text" placeholder="Video Title">
                      <span class="help-block"><?= isset($errors['videotitle0']) ? $errors['videotitle0'] : '' ?></span>
                    </div>
                    <div class="col-sm-6" style="float: left">
                      <input class="form-control" id="videomaterial0" name="videomaterial0" value="<?= isset($_POST['videomaterial0']) ? $_POST['videomaterial0'] : '' ?>" type="text" placeholder="Video Link">
                      <span class="help-block"><?= isset($errors['videomaterial0']) ? $errors['videomaterial0'] : '' ?></span>
                    </div>
                    <input type="hidden" name="filecount3" id="filecount3" value="1" />
                  </div>
                </div>
                <div class="clearfix"></div>
                <div id="add_more_files3">

                </div>

                <label for="addcoursematerial" class="col-sm-3 control-label"></label>
                <div class="col-sm-8">
                  <a href="javascript:void(0)" class="btn  btn-warning btn-xs" onclick="addMoreVideoMaterial()"><i class="fa fa-plus"></i> Add more videos</a>
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
            <a href="page.php?page=listCourseMultiSub" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>