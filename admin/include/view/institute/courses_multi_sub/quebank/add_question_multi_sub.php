<?php

//include_once('include/controller/admin/exams/quebank/edit_question.php'); 

$quebank_id = isset($_GET['quebank']) ? $_GET['quebank'] : 0;

$course_id = isset($_GET['course']) ? $_GET['course'] : 0;

$subject_id = isset($_GET['subject']) ? $_GET['subject'] : 0;


$action = isset($_POST['add_question']) ? $_POST['add_question'] : '';

include_once('include/classes/exammultisub.class.php');

$exammultisub = new exammultisub();

if ($action != '') {
  $quebank_id  = $db->test(isset($_POST['quebank_id']) ? $_POST['quebank_id'] : '');

  $course_id  = $db->test(isset($_POST['course_id']) ? $_POST['course_id'] : '');

  $subject_id  = $db->test(isset($_POST['subject_id']) ? $_POST['subject_id'] : '');

  $result = $exammultisub->add_question_multi_sub($quebank_id);

  $result = json_decode($result, true);

  $success = isset($result['success']) ? $result['success'] : '';

  $message = isset($result['message']) ? $result['message'] : '';

  $errors = isset($result['errors']) ? $result['errors'] : '';

  if ($success == true) {

    $_SESSION['msg'] = $message;

    $_SESSION['msg_flag'] = $success;

    header('location:page.php?page=viewQueBankMultiSub&course=' . $course_id . '&id=' . $quebank_id . '&subject=' . $subject_id);
  }
}



$res = $exammultisub->list_quetion_bank_multi_sub($quebank_id, '');

if ($res != '') {

  while ($data = $res->fetch_assoc()) {

    $QUEBANK_ID   = $data['QUEBANK_ID'];

    $MULTI_SUB_COURSE_ID    = $data['MULTI_SUB_COURSE_ID'];

    $MULTI_SUB_COURSE_CODE  = $data['MULTI_SUB_COURSE_CODE'];

    $COURSE_SUBJECT_ID  = $data['COURSE_SUBJECT_ID'];

    $EXAM_NAME    = $data['EXAM_NAME'];

    $CREATED_BY   = $data['CREATED_BY'];

    $CREATED_ON   = $data['CREATED_DATE'];

    // print_r($data);

  }
}

?>



<div class="content-wrapper">
  <div class="row">
    <div class="col-8 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add Question For Courses With Multiple Subjects </h4>
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

            <div class="form-group">
              <label for="exampleInputName1">Question</label>
              <input type="text" class="form-control" id="exampleInputName1" name="question" placeholder="question" value="<?= isset($_POST['question']) ? $_POST['question'] : '' ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Option A</label>
              <input type="text" class="form-control" id="exampleInputName1" name="opt1" placeholder="opt1" value="<?= isset($_POST['opt1']) ? $_POST['opt1'] : '' ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Option B</label>
              <input type="text" class="form-control" id="exampleInputName1" name="opt2" placeholder="opt2" value="<?= isset($_POST['opt2']) ? $_POST['opt2'] : '' ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Option C</label>
              <input type="text" class="form-control" id="exampleInputName1" name="opt3" placeholder="opt3" value="<?= isset($_POST['opt3']) ? $_POST['opt3'] : '' ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Option D</label>
              <input type="text" class="form-control" id="exampleInputName1" name="opt4" placeholder="opt4" value="<?= isset($_POST['opt4']) ? $_POST['opt4'] : '' ?>">
            </div>

            <div class="form-group row">
              <?php $CORRECT_ANS = isset($_POST['correctans']) ? $_POST['correctans'] : '';  ?>
              <label class="col-sm-3 col-form-label">Correct Answer</label>
              <div class="col-sm-2">
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="correctans" id="optionsRadios1" value="option_a" <?= ($CORRECT_ANS == 'option_a') ? 'checked="checked"' : '' ?>>
                    Option A
                  </label>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="correctans" id="optionsRadios2" value="option_b" <?= ($CORRECT_ANS == 'option_b') ? 'checked="checked"' : '' ?>>
                    Option B
                  </label>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="correctans" id="optionsRadios1" value="option_c" <?= ($CORRECT_ANS == 'option_c') ? 'checked="checked"' : '' ?>>
                    Option C
                  </label>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="correctans" id="optionsRadios2" value="option_d" <?= ($CORRECT_ANS == 'option_d') ? 'checked="checked"' : '' ?>>
                    Option D
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Upload Image</label>
              <input type="file" name="queimg" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Course Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>



            <div class="form-group row">
              <?php $status = isset($_POST['status']) ? $_POST['status'] : 1;  ?>
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
            <input type="submit" name="add_question" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=viewQueBankMultiSub&id=<?= $quebank_id ?>" class="btn btn-danger mr-2" title="Cancel">Cancel</a>

        </div>
      </div>
    </div>
    <div class="col-4 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Question Bank Details </h4>
          <input type="hidden" name="quebank_id" value="<?= $quebank_id ?>" />

          <div class="form-group">
            <label for="exampleFormControlSelect3">Course</label>
            <select class="form-control form-control-sm" id="exampleFormControlSelect3" id="course_id" name="course_id" readonly="">
              <?php
              echo $db->MenuItemsDropdown("multi_sub_courses", "MULTI_SUB_COURSE_ID", "MULTI_SUB_COURSE_CODE", "DISTINCT MULTI_SUB_COURSE_ID,get_course_multi_sub_title_modify(MULTI_SUB_COURSE_ID) AS MULTI_SUB_COURSE_CODE", $course_id, " WHERE ACTIVE=1 AND DELETE_FLAG=0 ");

              ?>
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="exampleInputName1">Subject</label>
            <select class="form-control" name="subject_id" id="subject_id" readonly="">
              <?php
              echo $db->MenuItemsDropdown("multi_sub_courses_subjects", "COURSE_SUBJECT_ID", "COURSE_SUBJECT_NAME", "COURSE_SUBJECT_ID,COURSE_SUBJECT_NAME", $subject_id, " WHERE ACTIVE=1 AND DELETE_FLAG=0 ");
              ?>
            </select>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>