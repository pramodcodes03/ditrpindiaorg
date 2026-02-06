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
    header('location:page.php?page=list-courses');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add Course</h4>
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
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Code</label>
                <input type="text" class="form-control" id="exampleInputName1" name="coursecode" placeholder="coursecode" value="">
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleFormControlSelect3">Award</label>
                <select class="form-control form-control-sm" id="exampleFormControlSelect3" id="award" name="award">
                  <?php
                  $award = isset($_POST['award']) ? $_POST['award'] : '';
                  echo $db->MenuItemsDropdown('course_awards', 'AWARD_ID', 'AWARD', 'AWARD_ID,AWARD', $award, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                  ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Name</label>
                <input type="text" class="form-control" id="exampleInputName1" name="coursename" placeholder="coursename" value="">
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Fees</label>
                <input type="text" class="form-control" id="exampleInputName1" name="coursefees" placeholder="coursefees" value="">
              </div>
            </div>
            <?php
            $is_multi = isset($_POST['is_multi']) ? $_POST['is_multi'] : 0;
            ?>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Is Multi Subject Course ? </label>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="is_multi" id="optionsRadios1" value="1" <?= ($is_multi == 0) ? "checked=''" : ''  ?> onclick="toggle_div_fun('sectiontohide',1);">
                      Yes
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input " name="is_multi" id="optionsRadios2" value="0" <?= ($is_multi == 0) ? "checked=''" : ''  ?> onclick="toggle_div_fun('sectiontohide',0);">
                      No
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" id="sectiontohide" style="display: none">
              <div class="form-group col-md-12  <?= (isset($errors['subject0'])) ? 'has-error' : '' ?>">
                <div class="form-group col-md-11  <?= (isset($errors['fees0'])) ? 'has-error' : '' ?>">
                  <label for="subject0" class="col-md-2 control-label">Course Subjects Name</label>
                  <div class="col-md-4">
                    <input class="form-control" id="subject0" name="subject0" placeholder="Enter Subject Name" value="<?= isset($_POST['subject0']) ? $_POST['subject0'] : '' ?>" type="text">
                    <span class="help-block"><?= isset($errors['subject0']) ? $errors['subject0'] : '' ?></span>
                  </div>
                  <input type="hidden" name="filecount2" id="filecount2" value="1" />
                  <div class="col-sm-2">
                    <a href="javascript:void(0)" class="btn  btn-danger btn-sm" onclick="addMoreSubjects()"><i class="fa fa-plus"></i> Add Subjects</a>
                  </div>
                </div>
                <div id="add_more_subjects">

                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Duration</label>
                <input type="text" class="form-control" id="exampleInputName1" name="duration" placeholder="duration" value="">
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
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="exampleTextarea1">Course Syllabus</label>
                <textarea class="form-control" id="tinyMceExample" rows="4" name="detail"></textarea>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleTextarea1">Eligibility</label>
                <textarea class="form-control" id="tinyMceExample1" rows="4" name="eligibility"></textarea>
              </div>
            </div>


            <div class="row">
              <div class="col-md-12 <?= (isset($errors['coursematerial0'])) ? 'has-error' : '' ?>">
                <label>Course Material Files</label>
                <div class="col-sm-10">
                  <div class="form-group <?= (isset($errors['filetitle0'])) ? 'has-error' : '' ?>">
                    <div class="col-sm-6" style="float: left">
                      <input class="form-control" id="filetitle0" name="filetitle0" placeholder="File Title" value="<?= isset($_POST['filetitle0']) ? $_POST['filetitle0'] : '' ?>" type="text">
                      <span class="help-block"><?= isset($errors['filetitle0']) ? $errors['filetitle0'] : '' ?></span>
                    </div>
                    <div class="col-sm-6" style="float: left">
                      <input id="coursematerial0" name="coursematerial0" type="file">
                      <p class="help-block"><?= (isset($errors['coursematerial0'])) ? $errors['coursematerial0'] : '' ?> </p>
                    </div>
                    <input type="hidden" name="filecount" id="filecount" value="1" />
                  </div>
                </div>

                <div id="add_more_files">

                </div>

                <label for="addcoursematerial" class="col-sm-3 control-label"></label>
                <div class="col-sm-8">
                  <a href="javascript:void(0)" class="btn  btn-warning btn-xs" onclick="addMoreCourseMaterial()"><i class="fa fa-plus"></i> Add more files</a>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
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
            </div>

            <input type="submit" name="add_course" class="btn btn-primary mr-2" value="Submit">
            <a href="listCourse" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>