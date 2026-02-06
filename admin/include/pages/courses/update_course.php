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
            <input type="hidden" name="course_id" value="<?= isset($COURSE_ID) ? $COURSE_ID : '' ?>" />
            <div class="form-group">
              <label for="exampleInputName1">Course Code</label>
              <input type="text" class="form-control" id="exampleInputName1" name="coursecode" placeholder="coursecode" value="<?= isset($_POST['coursecode']) ? $_POST['coursecode'] : $COURSE_CODE ?>">
            </div>
            <div class="form-group">
              <label for="exampleFormControlSelect3">Award</label>
              <select class="form-control form-control-sm" id="exampleFormControlSelect3" id="award" name="award">
                <?php
                $award = isset($_POST['award']) ? $_POST['award'] : $COURSE_AWARD;
                echo $db->MenuItemsDropdown('course_awards', 'AWARD_ID', 'AWARD', 'AWARD_ID,AWARD', $award, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="exampleInputName1">Course Name</label>
              <input type="text" class="form-control" id="exampleInputName1" name="coursename" placeholder="coursename" value="<?= isset($_POST['coursename']) ? $_POST['coursename'] : $COURSE_NAME ?>">
            </div>
            <div class="form-group">
              <label for="exampleInputName1">Course Fees</label>
              <input type="text" class="form-control" id="exampleInputName1" name="coursefees" placeholder="coursefees" value="<?= isset($_POST['coursefees']) ? $_POST['coursefees'] : $COURSE_FEES ?>">
            </div>
            <div class="form-group">
              <label for="exampleInputName1">Course Duration</label>
              <input type="text" class="form-control" id="exampleInputName1" name="duration" placeholder="duration" value="<?= isset($_POST['duration']) ? $_POST['duration'] : $COURSE_DURATION ?>">
            </div>
            <div class="form-group">
              <label for="exampleTextarea1">Course Syllabus</label>
              <textarea class="form-control" id="tinyMceExample" rows="4" name="detail"><?= isset($_POST['detail']) ? $_POST['detail'] : $COURSE_DETAILS ?></textarea>
            </div>
            <div class="form-group">
              <label for="exampleTextarea1">Eligibility</label>
              <textarea class="form-control" id="tinyMceExample1" rows="4" name="eligibility"><?= isset($_POST['eligibility']) ? $_POST['eligibility'] : $COURSE_ELIGIBILITY ?></textarea>
            </div>
            <div class="form-group">
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
            <div class="form-group row">
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
            <a href="/website_management/listCourse" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>