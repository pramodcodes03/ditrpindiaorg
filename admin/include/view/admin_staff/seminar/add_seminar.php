<?php
$action = isset($_POST['add_seminar']) ? $_POST['add_seminar'] : '';
include_once('include/classes/seminar.class.php');
$seminar = new seminar();
if ($action != '') {
  $result = $seminar->add_seminar();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listSeminar');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add Seminar
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
                <label for="exampleInputName1">Topic Name <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" id="exampleInputName1" name="topic_name" placeholder="topic_name" value="">
                <span class="help-block"><?= isset($errors['topic_name']) ? $errors['topic_name'] : '' ?></span>
              </div>
              <div class="col-md-3 form-group">
                <label for="exampleFormControlSelect3">Seminar Type <span class="asterisk"> * </span></label>
                <select class="form-control form-control-sm" id="exampleFormControlSelect3" id="seminar_type" name="seminar_type">
                  <option value="CRE Programme"> CRE Programme </option>
                  <option value="Workshop"> Workshop </option>
                  <option value="Seminar"> Seminar </option>
                  <option value="Conference"> Conference </option>
                </select>
                <span class="help-block"><?= isset($errors['seminar_type']) ? $errors['seminar_type'] : '' ?></span>
              </div>
              <div class="col-md-3 form-group">
                <label for="exampleFormControlSelect3">Seminar Mode <span class="asterisk"> * </span></label>
                <select class="form-control form-control-sm" id="exampleFormControlSelect3" id="mode" name="mode">
                  <option value="Offline"> Offline </option>
                  <option value="Online"> Online </option>
                </select>
                <span class="help-block"><?= isset($errors['mode']) ? $errors['mode'] : '' ?></span>
              </div>

              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Approval Number <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="approval_no" value="">
                <span class="help-block"><?= isset($errors['approval_no']) ? $errors['approval_no'] : '' ?></span>
              </div>


              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Director Name <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="conductor_name" value="">
                <span class="help-block"><?= isset($errors['conductor_name']) ? $errors['conductor_name'] : '' ?></span>
              </div>


              <div class="col-md-3 form-group">
                <label for="exampleInputName1">College Name <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="college_name" value="">
                <span class="help-block"><?= isset($errors['college_name']) ? $errors['college_name'] : '' ?></span>
              </div>
              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Fees Date<span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="fee_date" value="">
                <span class="help-block"><?= isset($errors['fee_date']) ? $errors['fee_date'] : '' ?></span>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Address <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="address" value="">
                <span class="help-block"><?= isset($errors['address']) ? $errors['address'] : '' ?></span>
              </div>

              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Place <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="place" value="">
                <span class="help-block"><?= isset($errors['place']) ? $errors['place'] : '' ?></span>
              </div>

              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Date <span class="asterisk"> * </span></label>
                <input type="date" class="form-control" name="date" value="">
                <span class="help-block"><?= isset($errors['date']) ? $errors['date'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label>Signature <span class="asterisk"> * </span></label>
                <input type="file" name="sign" class="file-upload-default">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Course Image">
                  <span class="input-group-append">
                    <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                  </span>
                </div>
              </div>

              <div class="col-md-6 form-group">
                <label>Stamp <span class="asterisk"> * </span></label>
                <input type="file" name="stamp" class="file-upload-default">
                <div class="input-group col-xs-12">
                  <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Course Image">
                  <span class="input-group-append">
                    <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                  </span>
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
              <input type="submit" name="add_seminar" class="btn btn-primary mr-2" value="Submit">
              <a href="page.php?page=listSeminar" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>