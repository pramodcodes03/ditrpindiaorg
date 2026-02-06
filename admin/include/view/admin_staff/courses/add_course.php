<?php
$action = isset($_POST['add_course']) ? $_POST['add_course'] : '';
include_once('include/classes/course.class.php');
$course = new course();
if ($action != '') {
  $result = $course->add_course();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listCourse');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add Course
            <a href="#" class="btn btn-warning" style="float: right; margin-right:20px;" target="_blank">How To Upload Videos Link</a>
          </h4>
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
            <?php
            if (isset($success)) {
            ?>
              <div class="row">
                <div class="col-md-12">
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
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Code <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" id="exampleInputName1" name="coursecode" placeholder="coursecode" value="">
                <span class="help-block"><?= isset($errors['coursecode']) ? $errors['coursecode'] : '' ?></span>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleFormControlSelect3">Award <span class="asterisk"> * </span></label>
                <select class="form-control form-control-sm" id="exampleFormControlSelect3" id="award" name="award">
                  <?php
                  $award = isset($_POST['award']) ? $_POST['award'] : '';
                  echo $db->MenuItemsDropdown('course_awards', 'AWARD_ID', 'AWARD', 'AWARD_ID,AWARD', $award, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                  ?>
                </select>
                <span class="help-block"><?= isset($errors['award']) ? $errors['award'] : '' ?></span>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Name <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="coursename" value="">
                <span class="help-block"><?= isset($errors['coursename']) ? $errors['coursename'] : '' ?></span>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Subject <span class="asterisk"> * </span></label>
                <textarea class="form-control" name="coursesubject"></textarea>
                <span class="help-block"><?= isset($errors['coursesubject']) ? $errors['coursesubject'] : '' ?></span>
              </div>

              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Course Duration <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="duration" value="">
                <span class="help-block"><?= isset($errors['duration']) ? $errors['duration'] : '' ?></span>
              </div>

              <div class="clearfix"></div>
              <div class="form-group col-sm-6 <?= (isset($errors['fees0'])) ? 'has-error' : '' ?>">
                <label>Select Institute Plan <span class="asterisk"> * </span></label>
                <div class="row">
                  <div class="col-sm-4">
                    <select class="form-control" id="plan0" name="plan0">
                      <?php
                      $plan0 = isset($_POST['plan0']) ? $_POST['plan0'] : '';
                      echo $db->MenuItemsDropdown('institute_plans', 'PLAN_ID', 'PLAN_NAME', 'PLAN_ID,PLAN_NAME', $plan0, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                      ?>
                    </select>
                    <span class="help-block"><?= isset($errors['plan0']) ? $errors['plan0'] : '' ?></span>
                  </div>

                  <div class="col-sm-4">
                    <input class="form-control" id="fees0" name="fees0" placeholder="Exam Fees" value="<?= isset($_POST['fees0']) ? $_POST['fees0'] : '' ?>" type="text">
                    <span class="help-block"><?= isset($errors['fees0']) ? $errors['fees0'] : '' ?></span>
                  </div>
                  <input type="hidden" name="filecount1" id="filecount1" value="1" />
                  <div class="col-sm-4">
                    <a href="javascript:void(0)" class="btn  btn-warning btn1" onclick="addMorePlans()"><i class="fa fa-plus"></i> Add Plans</a>
                  </div>
                </div>
                <div id="add_more_plans" class="row" style="margin-top:15px">

                </div>
              </div>

              <div class="col-md-6 form-group">

                <label for="exampleInputName1">Course Video Link 1 </label>
                <input type="text" class="form-control" name="video1" value="">
                <span class="help-block"><?= isset($errors['video1']) ? $errors['video1'] : '' ?></span>

                <div class="clearfix"></div>

                <label for="exampleInputName1">Course Video Link 2 </label>
                <input type="text" class="form-control" name="video2" value="">
                <span class="help-block"><?= isset($errors['video2']) ? $errors['video2'] : '' ?></span>

              </div>
              <div class="clearfix"></div>

              <div class="col-md-6 form-group">
                <label for="exampleTextarea1">Course Syllabus</label>
                <textarea class="form-control" id="tinyMceExample" rows="4" name="detail"></textarea>
                <span class="help-block"><?= isset($errors['detail']) ? $errors['detail'] : '' ?></span>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleTextarea1">Eligibility</label>
                <textarea class="form-control" id="tinyMceExample1" rows="4" name="eligibility"></textarea>
                <span class="help-block"><?= isset($errors['eligibility']) ? $errors['eligibility'] : '' ?></span>
              </div>
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
              <div class="col-md-6 form-group <?= (isset($errors['coursematerial0'])) ? 'has-error' : '' ?>">
                <label>Course Material Pdf Files</label>
                <div class="">
                  <div class="form-group <?= (isset($errors['filetitle0'])) ? 'has-error' : '' ?>">
                    <div class="col-sm-6" style="float: left">
                      <input class="form-control" id="filetitle0" name="filetitle0" value="<?= isset($_POST['filetitle0']) ? $_POST['filetitle0'] : '' ?>" type="text">
                      <span class="help-block"><?= isset($errors['filetitle0']) ? $errors['filetitle0'] : '' ?></span>
                    </div>
                    <div class="col-sm-6" style="float: left">
                      <input id="coursematerial0" name="coursematerial0" type="file">
                      <p class="help-block"><?= (isset($errors['coursematerial0'])) ? $errors['coursematerial0'] : '' ?> </p>
                    </div>
                    <input type="hidden" name="filecount" id="filecount" value="1" />
                  </div>
                </div>
                <div class="clearfix"></div>
                <div id="add_more_files">

                </div>

                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-8">
                  <a href="javascript:void(0)" class="btn  btn-warning btn-xs" onclick="addMoreCourseMaterial()"><i class="fa fa-plus"></i> Add more files</a>
                </div>
              </div>

              <!-- Videos section-->
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
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" checked>
                      Active
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0">
                      Inactive
                    </label>
                  </div>
                </div>
              </div>
              <input type="submit" name="add_course" class="btn btn-primary mr-2" value="Submit">
              <a href="page.php?page=listCourse" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>