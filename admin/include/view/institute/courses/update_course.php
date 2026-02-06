<?php include_once('include/controller/admin/courses/update_course.php'); ?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Update Course
            <a <a href="#" class="btn btn-warning" style="float: right; margin-right:20px;" target="_blank">How To Upload Videos Link</a>
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
            <input type="hidden" class="form-control" name="course_id" placeholder="course_id" value="<?= $course_id ?>">
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Code</label>
                <input type="text" class="form-control" id="exampleInputName1" name="coursecode" placeholder="coursecode" value="<?= isset($_POST['coursecode']) ? $_POST['coursecode'] : $COURSE_CODE ?>">
                <span class="help-block"><?= isset($errors['coursecode']) ? $errors['coursecode'] : '' ?></span>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleFormControlSelect3">Award</label>
                <select class="form-control form-control-sm" id="exampleFormControlSelect3" id="award" name="award">
                  <?php
                  $award = isset($_POST['award']) ? $_POST['award'] : $COURSE_AWARD;
                  echo $db->MenuItemsDropdown('course_awards', 'AWARD_ID', 'AWARD', 'AWARD_ID,AWARD', $award, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                  ?>
                </select>
                <span class="help-block"><?= isset($errors['award']) ? $errors['award'] : '' ?></span>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Name</label>
                <input type="text" class="form-control" name="coursename" value="<?= isset($_POST['coursename']) ? $_POST['coursename'] : $COURSE_NAME ?>">
                <span class="help-block"><?= isset($errors['coursename']) ? $errors['coursename'] : '' ?></span>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Course Subject</label>
                <textarea class="form-control" name="coursesubject"><?= isset($_POST['coursesubject']) ? $_POST['coursesubject'] : $COURSE_SUBJECTS ?></textarea>
                <span class="help-block"><?= isset($errors['coursesubject']) ? $errors['coursesubject'] : '' ?></span>
              </div>

              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Course Duration</label>
                <input type="text" class="form-control" name="duration" value="<?= isset($_POST['duration']) ? $_POST['duration'] : $COURSE_UDRATION ?>">
                <span class="help-block"><?= isset($errors['duration']) ? $errors['duration'] : '' ?></span>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['fees0'])) ? 'has-error' : '' ?>">

                <label>Selet Institute Plan</label>
                <div class="row">
                  <div class="col-md-8">
                    <?php
                    $docData = $course->get_course_plans($COURSE_ID, false);
                    $sr = 0;
                    if (!empty($docData)) {

                      foreach ($docData as $key => $value) {
                        extract($value);
                    ?>
                        <input type="hidden" name="course_plan_fees_id<?= $sr ?>" value="<?= $COURSE_PLAN_FEES_ID ?>" />
                        <div class="row <?= (isset($errors['fees' . $sr])) ? 'has-error' : '' ?>" style="margin-top:10px">
                          <div class="col-md-6">
                            <select class="form-control" id="plan<?= $sr ?>" name="plan<?= $sr ?>">
                              <?php
                              $plan = isset($_POST['plan' . $sr]) ? $_POST['plan' . $sr] : $PLAN_ID;
                              echo $db->MenuItemsDropdown('institute_plans', 'PLAN_ID', 'PLAN_NAME', 'PLAN_ID,PLAN_NAME', $plan, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                              ?>
                            </select>
                            <span class="help-block"><?= isset($errors['plan' . $sr]) ? $errors['plan' . $sr] : '' ?></span>
                          </div>

                          <div class="col-md-6">
                            <input class="form-control" id="fees<?= $sr ?>" name="fees<?= $sr ?>" placeholder="Exam Fees" value="<?= isset($_POST['fees' . $sr]) ? $_POST['fees' . $sr] : $COURSE_FEES ?>" type="text">
                            <span class="help-block"><?= isset($errors['fees' . $sr]) ? $errors['fees' . $sr] : '' ?></span>
                          </div>
                        </div>
                    <?php
                        $sr++;
                      }
                    }
                    ?>
                    <input type="hidden" name="filecount1" id="filecount1" value="<?= $sr ?>" />
                  </div>



                  <div class="col-md-4">
                    <a href="javascript:void(0)" class="btn  btn-warning btn-xs" onclick="addMorePlans()"><i class="fa fa-plus"></i> Add Plans</a>
                  </div>
                </div>

                <div id="add_more_plans" style="margin-top:10px">

                </div>
              </div>

              <div class="col-md-6 form-group">

                <label for="exampleInputName1">Course Video Link 1 </label>
                <input type="text" class="form-control" name="video1" value="<?= $VIDEO1 ?>">
                <span class="help-block"><?= isset($errors['video1']) ? $errors['video1'] : '' ?></span>

                <div class="clearfix"></div>

                <label for="exampleInputName1">Course Video Link 2 </label>
                <input type="text" class="form-control" name="video2" value="<?= $VIDEO2 ?>">
                <span class="help-block"><?= isset($errors['video2']) ? $errors['video2'] : '' ?></span>

              </div>

              <div class="col-md-6 form-group">
                <label for="exampleTextarea1">Course Syllabus</label>
                <textarea class="form-control" id="tinyMceExample" rows="4" name="detail"><?= isset($_POST['detail']) ? $_POST['detail'] : $COURSE_DETAILS ?></textarea>
                <span class="help-block"><?= isset($errors['detail']) ? $errors['detail'] : '' ?></span>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleTextarea1">Eligibility</label>
                <textarea class="form-control" id="tinyMceExample1" rows="4" name="eligibility"><?= isset($_POST['eligibility']) ? $_POST['eligibility'] : $COURSE_ELIGIBILITY ?></textarea>
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
                  <span class="help-block"><?= isset($errors['course_img']) ? $errors['course_img'] : '' ?></span>
                </div>
                <?php
                if ($COURSE_IMAGE != '') {
                  $path = COURSE_MATERIAL_PATH . '/' . $COURSE_ID . '/' . $COURSE_IMAGE;
                  echo '<br> <img src="' . $path . '" class="img img-responsive" style="height:100px;"  />';
                }
                ?>
              </div>

              <div class="col-md-6 form-group <?= (isset($errors['coursematerial0'])) ? 'has-error' : '' ?>">
                <div id="add_more_files">
                  <label>Course Material Pdf Files</label>
                  <div class="">
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
              <div class="col-md-6 form-group <?= (isset($errors['videomaterial0'])) ? 'has-error' : '' ?>">
                <div id="add_more_files3">
                  <label>Course Video Links (Add YouTube Embedded Links)</label>
                  <div class="">
                    <?php
                    echo  $doc = $course->get_course_videos_all($COURSE_ID, true);
                    ?>
                    <input type="hidden" name="filecount3" id="filecount3" value="0" />

                  </div>
                </div>
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-8">
                  <a href="javascript:void(0)" class="btn  btn-warning btn-xs" onclick="addMoreVideoMaterial()"><i class="fa fa-plus"></i> Add more videos</a>
                </div>
              </div>

              <div class="col-md-12 form-group row">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;
                ?>
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-4">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?>>
                      Active
                    </label>
                  </div>
                </div>
                <div class="col-sm-5">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?>>
                      Inactive
                    </label>
                  </div>
                </div>
              </div>
              <input type="submit" name="update_course" class="btn btn-primary mr-2" value="Submit">
              <a href="page.php?page=listCourse" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>