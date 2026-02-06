<?php include_once('include/controller/admin/courses/update_course.php'); ?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Update Course</h4>
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
                <input type="text" class="form-control" id="exampleInputName1" name="coursecode" placeholder="coursecode" value="<?= isset($_POST['coursecode']) ? $_POST['coursecode'] : $COURSE_CODE ?>">
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleFormControlSelect3">Award</label>
                <select class="form-control form-control-sm" id="exampleFormControlSelect3" id="award" name="award">
                  <?php
                  $award = isset($_POST['award']) ? $_POST['award'] : $COURSE_AWARD;
                  echo $db->MenuItemsDropdown('course_awards', 'AWARD_ID', 'AWARD', 'AWARD_ID,AWARD', $award, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                  ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Name</label>
                <input type="text" class="form-control" id="exampleInputName1" name="coursename" placeholder="coursename" value="<?= isset($_POST['coursename']) ? $_POST['coursename'] : $COURSE_NAME ?>">
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Fees</label>
                <input type="text" class="form-control" id="exampleInputName1" name="coursefees" placeholder="coursefees" value="<?= isset($_POST['duration']) ? $_POST['duration'] : $COURSE_FEES ?>">
              </div>
            </div>
            <?php
            $is_multi = isset($_POST['is_multi']) ? $_POST['is_multi'] : $IS_MULTIPLE;
            ?>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Is Multi Subject Course ? </label>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="is_multi" id="optionsRadios1" value="1" <?= ($is_multi == 1) ? "checked=''" : ''  ?> onclick="toggle_div_fun('sectiontohide',1);">
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
            <div class="row" id="sectiontohide" style="<?= ($is_multi == 1) ? "display: block" : "display: none" ?>">
              <div class="form-group col-sm-12  <?= (isset($errors['fees0'])) ? 'has-error' : '' ?>">
                <div class="form-group <?= (isset($errors['fees0'])) ? 'has-error' : '' ?>">
                  <div class="form-group col-sm-12 <?= (isset($errors['plan0'])) ? 'has-error' : '' ?>">
                    <label for="plan0" class="col-sm-2 control-label">Course Subjects Name</label>
                    <div class="col-sm-4">

                      <?php
                      $docData = $course->get_course_multi_sub($COURSE_ID, false);
                      $sr = 0;
                      if (!empty($docData)) {

                        foreach ($docData as $key => $value) {
                          extract($value);
                      ?>
                          <input type="hidden" name="course_multi_sub_id<?= $sr ?>" value="<?= $COURSE_SUBJECT_ID ?>" />
                          <div class="form-group <?= (isset($errors['subject' . $sr])) ? 'has-error' : '' ?>">
                            <div class="col-sm-12">
                              <input class="form-control" id="subject<?= $sr ?>" name="subject<?= $sr ?>" placeholder="Exam Fees" value="<?= isset($_POST['subject' . $sr]) ? $_POST['subject' . $sr] : $COURSE_SUBJECT_NAME ?>" type="text">
                              <span class="help-block"><?= isset($errors['subject' . $sr]) ? $errors['subject' . $sr] : '' ?></span>
                            </div>
                          </div>
                      <?php
                          $sr++;
                        }
                      }
                      ?>
                      <input type="hidden" name="filecount2" id="filecount2" value="<?= $sr ?>" />

                    </div>
                    <div class="col-sm-4">
                      <a href="javascript:void(0)" class="btn  btn-danger btn-sm" onclick="addMoreSubjects()"><i class="fa fa-plus"></i> Add Subjects</a>
                    </div>
                  </div>
                </div>
                <div id="add_more_subjects">

                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Duration</label>
                <input type="text" class="form-control" id="exampleInputName1" name="duration" placeholder="duration" value="<?= isset($_POST['duration']) ? $_POST['duration'] : $COURSE_DURATION ?>">
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
              <?php
              if ($COURSE_IMAGE != '') {
                $path = COURSE_MATERIAL_PATH . '/' . $COURSE_ID . '/' . $COURSE_IMAGE;
                echo '<br> <img src="' . $path . '" class="img img-responsive" style="height:100px;"  />';
              }
              ?>
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="exampleTextarea1">Course Syllabus</label>
                <textarea class="form-control" id="tinyMceExample" rows="2" name="detail"><?= isset($_POST['detail']) ? $_POST['detail'] : $COURSE_DETAILS ?></textarea>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleTextarea1">Eligibility</label>
                <textarea class="form-control" id="tinyMceExample1" rows="2" name="eligibility"><?= isset($_POST['eligibility']) ? $_POST['eligibility'] : $COURSE_ELIGIBILITY ?></textarea>
              </div>
            </div>

            <div class="form-group <?= (isset($errors['coursematerial0'])) ? 'has-error' : '' ?>">
              <div id="add_more_files">
                <label>Course Material Files</label>
                <div class="col-sm-10">
                  <?php
                  echo  $doc = $course->get_course_docs_all($COURSE_ID, true);
                  ?>
                  <input type="hidden" name="filecount" id="filecount" value="0" />

                </div>
              </div>
              <label for="addcoursematerial" class="col-sm-3 control-label"></label>
              <div class="col-sm-8">
                <a href="javascript:void(0)" class="btn  btn-warning btn-xs" onclick="addMoreCourseMaterial()"><i class="fa fa-plus"></i> Add more files</a>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;
                ?>
                <label class="col-sm-2 col-form-label">Status</label>
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
            <a href="page.php?page=listCourse" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>