<?php
$exam_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_exam']) ? $_POST['update_exam'] : '';
include_once('include/classes/coursetypingexam.class.php');
$coursetypingexam = new coursetypingexam();
if ($action != '') {
  $result = $coursetypingexam->update_exam($exam_id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listExamsTypingCourses');
  }
}
/* get exam details */
$res = $coursetypingexam->list_exams($exam_id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    extract($data);
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Update Exam Typing Course</h4>
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="exam_id" value="<?= $EXAM_ID ?>" />
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
                <label>Course Code</label>
                <select class="form-control form-control-sm" id="courseid" name="courseid" onchange="getSubjectIdTyping(this.value)">
                  <?php
                  $courseid = isset($_POST['courseid']) ? $_POST['courseid'] : $TYPING_COURSE_ID;
                  echo $db->MenuItemsDropdown('courses_typing', 'TYPING_COURSE_ID', 'TYPING_COURSE_NAME', 'TYPING_COURSE_ID,TYPING_COURSE_NAME', $courseid, ' WHERE ACTIVE=1 AND DELETE_FLAG=0 ORDER BY TYPING_COURSE_CODE ASC');

                  ?>
                </select>
              </div>
              <div class="col-md-6 form-group">
                <label>Course Subjects</label>
                <select class="form-control form-control-sm" id="subjectid" name="subjectid">
                  <?php
                  $subjectid = isset($_POST['subjectid']) ? $_POST['subjectid'] : $COURSE_SUBJECT_ID;
                  $db->MenuItemsDropdown('courses_typing_subjects', 'TYPING_COURSE_SUBJECT_ID', 'TYPING_COURSE_SUBJECT_NAME', 'TYPING_COURSE_SUBJECT_ID,CONCAT(TYPING_COURSE_SUBJECT_NAME," ",TYPING_COURSE_SPEED) AS TYPING_COURSE_SUBJECT_NAME', $subjectid, 'WHERE TYPING_COURSE_ID=' . $TYPING_COURSE_ID . ' ORDER BY TYPING_COURSE_SUBJECT_ID ASC');
                  ?>
                </select>
              </div>

              <div class="col-md-6 form-group">
                <label>Maximum Marks</label>
                <input type="number" class="form-control" id="totalmarks" name="totalmarks" placeholder="totalmarks" value="<?= isset($_POST['totalmarks']) ? $_POST['totalmarks'] : $TOTAL_MARKS ?>">
              </div>

              <div class="col-md-6 form-group">
                <label>Minimum Marks</label>
                <input type="number" class="form-control" id="minimum_marks" name="minimum_marks" placeholder="minimum_marks" value="<?= isset($_POST['minimum_marks']) ? $_POST['minimum_marks'] : $MINIMUM_MARKS ?>">
              </div>

              <div class="col-md-6 form-group">
                <label>Exam Modes</label>
                <div style="display: flex; margin: auto;">
                  <?php
                  $EXAM_MODE_TYPE = !empty($EXAM_MODE_TYPE) ? $EXAM_MODE_TYPE : array();
                  $exam_mode = isset($_POST['exam_mode']) ? $_POST['exam_mode'] : json_decode($EXAM_MODE_TYPE);
                  $sql = "SELECT * FROM exam_types_master WHERE ACTIVE=1 AND DELETE_FLAG=0 AND EXAM_TYPE_ID = '3'";
                  $res = $db->execQuery($sql);
                  if ($res && $res->num_rows > 0) {
                    while ($data = $res->fetch_assoc()) {
                      extract($data);
                  ?>
                      <label>
                        <input name="exam_mode[]" value="<?= $EXAM_TYPE_ID ?>" <?= (in_array($EXAM_TYPE_ID, $exam_mode)) ? "checked=''" : ''  ?> type="checkbox" style="height: 20px; float: left; text-align: left; width: 45px;">
                        <?= $EXAM_TYPE ?>
                      </label>
                  <?php
                    }
                  }
                  ?>
                </div>
              </div>
              <div class="col-md-6 form-group row">
                <?php $showresult = isset($_POST['showresult']) ? $_POST['showresult'] : $SHOW_RESULT;  ?>
                <label class="col-sm-3 col-form-label">Display Result</label>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="showresult" id="optionsRadios1" value="1" <?= ($showresult == 1) ? "checked=''" : ''  ?>>
                      Yes
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="showresult" id="optionsRadios2" value="0" <?= ($showresult == 0) ? "checked=''" : ''  ?>>
                      No
                    </label>
                  </div>
                </div>
              </div>

              <div class="col-md-6 form-group row">
                <?php $demotest = isset($_POST['demotest']) ? $_POST['demotest'] : $DEMO_TEST;  ?>
                <label class="col-sm-3 col-form-label">Demo Exam</label>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="demotest" id="optionsRadios1" value="1" <?= ($demotest == 1) ? "checked=''" : ''  ?>>
                      Yes
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="demotest" id="optionsRadios2" value="0" <?= ($demotest == 0) ? "checked=''" : ''  ?>>
                      No
                    </label>
                  </div>
                </div>
              </div>

              <div class="col-md-6 form-group row">
                <?php $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;  ?>
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-3">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?>>
                      Active
                    </label>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?>>
                      Inactive
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <input type="submit" name="update_exam" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=listExamsTypingCourses" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>