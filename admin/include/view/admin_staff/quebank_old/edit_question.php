<?php //include_once('include/controller/admin/exams/quebank/edit_question.php'); 
$que_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_quebank']) ? $_POST['update_quebank'] : '';
include_once('include/classes/exam.class.php');
$exam = new exam();
if ($action != '') {
  $quebank_id = isset($_POST['quebank_id']) ? $_POST['quebank_id'] : '';
  $result = $exam->edit_question($que_id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=viewQueBank&id=' . $quebank_id);
  }
}
/* get que bank details */

$res = $exam->get_question_detail($que_id, '');
if ($res != '') {
  while ($data = $res->fetch_assoc()) {
    $QUESTION_ID   = $data['QUESTION_ID'];
    $QUEBANK_ID    = $data['QUEBANK_ID'];
    $QUESTION     = $data['QUESTION'];
    $IMAGE       = $data['IMAGE'];
    $OPTION_A    = $data['OPTION_A'];
    $OPTION_B    = $data['OPTION_B'];
    $OPTION_C    = $data['OPTION_C'];
    $OPTION_D    = $data['OPTION_D'];
    $CORRECT_ANS  = $data['CORRECT_ANS'];
    $ACTIVE      = $data['ACTIVE'];
    $CREATED_BY   = $data['CREATED_BY'];
    $CREATED_ON   = $data['CREATED_DATE'];

    $imgPreview = '';
    if ($IMAGE != '') {
      $path = QUEBANK_PATH . '/' . $QUEBANK_ID . '/images/' . $IMAGE;
      if (file_exists($path))
        $imgPreview = '<img src="' . $path . '" class="img img-responsive" style="height:35px; width:35px;" id="img_preview"/>';
    }
  }
}
?>



<div class="content-wrapper">
  <div class="row">
    <div class="col-8 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Update Question </h4>
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

            <input type="hidden" name="question_id" value="<?= $QUESTION_ID ?>" />
            <input type="hidden" name="quebank_id" value="<?= $QUEBANK_ID ?>" />


            <div class="form-group">
              <label for="exampleInputName1">Question</label>
              <input type="text" class="form-control" id="exampleInputName1" name="question" placeholder="question" value="<?= isset($_POST['question']) ? $_POST['question'] : $QUESTION ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Option A</label>
              <input type="text" class="form-control" id="exampleInputName1" name="opt1" placeholder="opt1" value="<?= isset($_POST['opt1']) ? $_POST['opt1'] : $OPTION_A ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Option B</label>
              <input type="text" class="form-control" id="exampleInputName1" name="opt2" placeholder="opt2" value="<?= isset($_POST['opt2']) ? $_POST['opt2'] : $OPTION_B ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Option C</label>
              <input type="text" class="form-control" id="exampleInputName1" name="opt3" placeholder="opt3" value="<?= isset($_POST['opt3']) ? $_POST['opt3'] : $OPTION_C ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Option D</label>
              <input type="text" class="form-control" id="exampleInputName1" name="opt4" placeholder="opt4" value="<?= isset($_POST['opt4']) ? $_POST['opt4'] : $OPTION_D ?>">
            </div>

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">Correct Answer</label>
              <div class="col-sm-4">
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="correctans" id="optionsRadios1" value="option_a" <?= ($CORRECT_ANS == 'option_a') ? 'checked="checked"' : '' ?>>
                    Option A
                  </label>
                </div>
              </div>
              <div class="col-sm-5">
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="correctans" id="optionsRadios2" value="option_b" <?= ($CORRECT_ANS == 'option_b') ? 'checked="checked"' : '' ?>>
                    Option B
                  </label>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="correctans" id="optionsRadios1" value="option_c" <?= ($CORRECT_ANS == 'option_c') ? 'checked="checked"' : '' ?>>
                    Option C
                  </label>
                </div>
              </div>
              <div class="col-sm-5">
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
              <?php
              if ($IMAGE != '') {
                $path = QUEBANK_PATH . '/' . $QUEBANK_ID . '/images/' . $IMAGE;
                if (file_exists($path))
                  echo '<a href="' . $path . '" target="_blank"><img src="' . $path . '" class="img img-responsive" style="height:150px; width:150px;" id="img_preview"/></a><br><br>';
              }
              ?>
            </div>



            <div class="form-group row">
              <?php $status = isset($_POST['status']) ? $_POST['status'] : $ACTIVE;  ?>
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
            <input type="submit" name="update_quebank" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=viewQueBank&id=<?= $QUEBANK_ID ?>" class="btn btn-danger mr-2" title="Cancel">Cancel</a>

        </div>
      </div>
    </div>
  </div>
</div>