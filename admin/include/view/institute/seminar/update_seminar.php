<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';

$action = isset($_POST['update_seminar']) ? $_POST['update_seminar'] : '';

include_once('include/classes/seminar.class.php');
$seminar = new seminar();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $seminar->update_seminar($id);
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
/* get institute details */
$res = $seminar->list_seminar($id, '');
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
          <h4 class="card-title">Update Seminar
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
              <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>" />
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Topic Name <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" id="exampleInputName1" name="topic_name" placeholder="topic_name" value="<?= isset($_POST['topic_name']) ? $_POST['topic_name'] : $topic_name ?>">
                <span class="help-block"><?= isset($errors['topic_name']) ? $errors['topic_name'] : '' ?></span>
              </div>
              <div class="col-md-3 form-group">
                <label for="exampleFormControlSelect3">Seminar Type <span class="asterisk"> * </span></label>
                <select class="form-control form-control-sm" id="exampleFormControlSelect3" id="seminar_type" name="seminar_type">
                  <option value="CRE Programme" <?php echo ($seminar_type == 'CRE Programme') ? 'selected="selected"' : '' ?>> CRE Programme </option>
                  <option value="Workshop" <?php echo ($seminar_type == 'Workshop') ? 'selected="selected"' : '' ?>> Workshop </option>
                  <option value="Seminar" <?php echo ($seminar_type == 'Seminar') ? 'selected="selected"' : '' ?>> Seminar </option>
                  <option value="Conference" <?php echo ($seminar_type == 'Conference') ? 'selected="selected"' : '' ?>> Conference </option>
                </select>
                <span class="help-block"><?= isset($errors['seminar_type']) ? $errors['seminar_type'] : '' ?></span>
              </div>
              <div class="col-md-3 form-group">
                <label for="exampleFormControlSelect3">Seminar Mode <span class="asterisk"> * </span></label>
                <select class="form-control form-control-sm" id="exampleFormControlSelect3" id="mode" name="mode">
                  <option value="Offline" <?php echo ($mode == 'Offline') ? 'selected="selected"' : '' ?>> Offline </option>
                  <option value="Online" <?php echo ($mode == 'Online') ? 'selected="selected"' : '' ?>> Online </option>
                </select>
                <span class="help-block"><?= isset($errors['mode']) ? $errors['mode'] : '' ?></span>
              </div>

              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Approval Number <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="approval_no" value="<?= isset($_POST['approval_no']) ? $_POST['approval_no'] : $approval_no ?>">
                <span class="help-block"><?= isset($errors['approval_no']) ? $errors['approval_no'] : '' ?></span>
              </div>
              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Director Name <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="conductor_name" value="<?= isset($_POST['conductor_name']) ? $_POST['conductor_name'] : $conductor_name ?>">
                <span class="help-block"><?= isset($errors['conductor_name']) ? $errors['conductor_name'] : '' ?></span>
              </div>
              <div class="col-md-3 form-group">
                <label for="exampleInputName1">College Name <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="college_name" value="<?= isset($_POST['college_name']) ? $_POST['college_name'] : $college_name ?>">
                <span class="help-block"><?= isset($errors['college_name']) ? $errors['college_name'] : '' ?></span>
              </div>
              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Fees Date<span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="fee_date" value="<?= isset($_POST['fee_date']) ? $_POST['fee_date'] : $fee_date ?>">
                <span class="help-block"><?= isset($errors['fee_date']) ? $errors['fee_date'] : '' ?></span>
              </div>
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Address <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="address" value="<?= isset($_POST['address']) ? $_POST['address'] : $address ?>">
                <span class="help-block"><?= isset($errors['address']) ? $errors['address'] : '' ?></span>
              </div>

              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Place <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="place" value="<?= isset($_POST['place']) ? $_POST['place'] : $place ?>">
                <span class="help-block"><?= isset($errors['place']) ? $errors['place'] : '' ?></span>
              </div>

              <div class="col-md-3 form-group">
                <label for="exampleInputName1">Date <span class="asterisk"> * </span></label>
                <input type="date" class="form-control" name="date" value="<?= isset($_POST['date']) ? $_POST['date'] : $date ?>">
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
                <br />
                <?php
                if ($sign != '') {
                  $photo_path  = SEMINAR_DOCUMENTS_PATH . '/' . $id . '/' . $sign;
                  echo $disp_photo = '<a href="' . $photo_path . '" target="_blank"><img id="stud_photo" src="' . $photo_path . '" class="img img-responsive thumbnail" style="width:130px; height:150px;border: 1px solid #000;" /></a>	';
                }
                ?>
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
                <br />
                <?php
                if ($stamp != '') {
                  $photo_path1  = SEMINAR_DOCUMENTS_PATH . '/' . $id . '/' . $stamp;
                  echo $disp_photo = '<a href="' . $photo_path1 . '" target="_blank"><img id="stud_photo" src="' . $photo_path1 . '" class="img img-responsive thumbnail" style="width:130px; height:150px;border: 1px solid #000;" /></a>	';
                }
                ?>
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
              <input type="submit" name="update_seminar" class="btn btn-primary mr-2" value="Update">
              <a href="page.php?page=listSeminar" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>