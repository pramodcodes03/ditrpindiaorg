<?php
$action = isset($_POST['add_exam']) ? $_POST['add_exam'] : '';
include_once('include/classes/exam.class.php');
$exam = new exam();
if ($action != '') {

  $result = $exam->add_exam();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listExams');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add Exam </h4>
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
                <label>Course Code</label>
                <select class="form-control form-control-sm select2" id="courseid" id="courseid" name="courseid" onchange="setCourseName(this.value)">
                  <?php
                  $courseid = isset($_POST['courseid']) ? $_POST['courseid'] : '';
                  echo $db->MenuItemsDropdown('courses A LEFT JOIN exam_structure B ON A.COURSE_ID=B.COURSE_ID', 'COURSE_ID', 'COURSE', 'A.COURSE_ID, get_course_title_modify(A.COURSE_ID) AS COURSE', $courseid, ' WHERE B.COURSE_ID IS NULL AND A.ACTIVE=1 AND A.DELETE_FLAG=0 ORDER BY A.COURSE_CODE ASC');
                  ?>
                </select>
              </div>
              <input id="examname" name="examname" value="<?= isset($_POST['examname']) ? $_POST['examname'] : '' ?>" type="hidden">

              <div class="col-md-6 form-group">
                <label>Total Marks</label>
                <input type="number" class="form-control" id="totalmarks" name="totalmarks" placeholder="totalmarks" value="<?= isset($_POST['totalmarks']) ? $_POST['totalmarks'] : 50 ?>" onchange="setMarkPerQue()" readonly>
              </div>
              <div class="col-md-6 form-group">
                <label>Total Questions</label>
                <input type="number" class="form-control" id="totalque" name="totalque" placeholder="totalque" value="<?= isset($_POST['totalque']) ? $_POST['totalque'] : '' ?>" onchange="setMarkPerQue()">
              </div>
              <div class="col-md-6 form-group">
                <label>Marks Per Questions</label>
                <input type="text" class="form-control" id="markperque" name="markperque" placeholder="markperque" value="<?= isset($_POST['markperque']) ? $_POST['markperque'] : '' ?>" readonly>
              </div>

              <div class="col-md-6 form-group">
                <label>Passing Marks( % )</label>
                <input type="text" class="form-control" id="passingmarks" name="passingmarks" placeholder="passingmarks" value="<?= isset($_POST['passingmarks']) ? $_POST['passingmarks'] : '' ?>">
              </div>

              <div class="col-md-6 form-group">
                <label for="examtime">Exam Time (minutes)</label>
                <input type="number" class="form-control" id="examtime" name="examtime" placeholder="examtime" value="<?= isset($_POST['examtime']) ? $_POST['examtime'] : '' ?>">
              </div>

              <div class="col-md-6 form-group row">
                <div class="" style="display: flex;">
                  <label class="col-sm-4 col-form-label">Exam Modes</label>
                  <div style="display: flex; margin: auto;">
                    <?php
                    $exam_mode = isset($_POST['exam_mode']) ? $_POST['exam_mode'] : array();
                    $sql = "SELECT * FROM exam_types_master WHERE ACTIVE=1 AND DELETE_FLAG=0";
                    $res = $db->execQuery($sql);
                    if ($res && $res->num_rows > 0) {
                      while ($data = $res->fetch_assoc()) {
                        extract($data);
                        //$selected = in_array($EXAM_TYPE_ID,$exam_mode);
                    ?>

                        <input class="form-control" name="exam_mode[]" value="<?= $EXAM_TYPE_ID ?>" <?= (in_array($EXAM_TYPE_ID, $exam_mode)) ? "checked=''" : 'checked'  ?> type="checkbox" style="height: 20px; float: left; text-align: left; width: 45px;">
                        <?= $EXAM_TYPE ?> &nbsp;&nbsp; &nbsp;&nbsp;


                    <?php
                      }
                    }
                    ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6 form-group row">
                <?php $showresult = isset($_POST['showresult']) ? $_POST['showresult'] : 1;  ?>
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
                <?php $demotest = isset($_POST['demotest']) ? $_POST['demotest'] : 1;  ?>
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
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-3">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" checked>
                      Active
                    </label>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0">
                      Inactive
                    </label>
                  </div>
                </div>
              </div>
              <input type="submit" name="add_exam" class="btn btn-primary mr-2" value="Submit">
              <a href="page.php?page=listExams" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>