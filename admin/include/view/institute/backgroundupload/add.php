<?php

$id = isset($_GET['id']) ? $_GET['id'] : 1;

$action = isset($_POST['update_images']) ? $_POST['update_images'] : '';

include_once('include/classes/tools.class.php');
$tools = new tools();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 5) {
  $institute_id = $db->get_parent_id($user_role, $user_id);
  $staff_id = $user_id;
} else {
  $institute_id = $user_id;
  $staff_id = 0;
}

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $tools->edit_backgroundimages($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=manageBackground');
  }
}

$res = $tools->list_backgroundimages($id, $institute_id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    extract($data);

    $certificate_image    = BACKGROUND_IMAGE_PATH . '/' . $id . '/' . $certificate_image;
    $marksheet_image      = BACKGROUND_IMAGE_PATH . '/' . $id . '/' . $marksheet_image;
    $admissionform_image  = BACKGROUND_IMAGE_PATH . '/' . $id . '/' . $admissionform_image;
    $idcard_image         = BACKGROUND_IMAGE_PATH . '/' . $id . '/' . $idcard_image;
    $hallticket_image     = BACKGROUND_IMAGE_PATH . '/' . $id . '/' . $hallticket_image;
    $feesreceipt_image    = BACKGROUND_IMAGE_PATH . '/' . $id . '/' . $feesreceipt_image;
    $atccert_image    = BACKGROUND_IMAGE_PATH . '/' . $id . '/' . $atccert_image;
    $typingmarksheet_image    = BACKGROUND_IMAGE_PATH . '/' . $id . '/' . $typingmarksheet_image;

    $seminar_image    = BACKGROUND_IMAGE_PATH . '/' . $id . '/' . $seminar_image;

    $performance_image = BACKGROUND_IMAGE_PATH . '/' . $id . '/' . $performance_image;
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Manage Background Images
            <a href="https://drive.google.com/file/d/1yFWPEa1eGu5kMFbBkYBOmXTdXptAm6Vu/view?usp=share_link" class="btn btn-primary" style="float: right" target="_blank">Download Sample Certificates</a>
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
            <input type="hidden" name="inst_id" value="<?= isset($institute_id) ? $institute_id : '' ?>" />
            <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>" />
            <div class="form-group">
              <label>Certificate Image</label>
              <input type="file" name="certificate_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($certificate_image != '') {
            ?>
              <img src="<?= $certificate_image ?>" style="width:150px; height:100%; border-radius:0;" />
              <a href="page.php?page=previewCertificate" target="_blank" class="btn btn-primary btn1">Preview</a>
              <br /><br />
            <?php
            }
            ?>


            <div class="form-group">
              <label>Marksheet Image</label>
              <input type="file" name="marksheet_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($marksheet_image != '') {
            ?>
              <img src="<?= $marksheet_image ?>" style="width:150px; height:100%; border-radius:0;" />
              <a href="page.php?page=previewMarksheet" target="_blank" class="btn btn-primary btn1">Preview</a>
              <br /><br />
            <?php
            }
            ?>


            <div class="form-group">
              <label>ATC Certificate Image</label>
              <input type="file" name="atccert_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($atccert_image != '') {
            ?>
              <img src="<?= $atccert_image ?>" style="width:150px; height:100%; border-radius:0;" />
              <a href="page.php?page=previewFranchiseCertificate" target="_blank" class="btn btn-primary btn1">Preview</a>
              <br /><br />
            <?php
            }
            ?>


            <div class="form-group">
              <label>Typing Marksheet Image</label>
              <input type="file" name="typingmarksheet_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($typingmarksheet_image != '') {
            ?>
              <img src="<?= $typingmarksheet_image ?>" style="width:150px; height:100%; border-radius:0;" />
              <a href="page.php?page=previewTypingMarksheet" target="_blank" class="btn btn-primary btn1">Preview</a>
              <br /><br />
            <?php
            }
            ?>

            <div class="form-group">
              <label>Seminar Certificate Image</label>
              <input type="file" name="seminar_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($seminar_image != '') {
            ?>
              <img src="<?= $seminar_image ?>" style="width:150px; height:100%; border-radius:0;" />
              <a href="page.php?page=previewSeminarCertificate" target="_blank" class="btn btn-primary btn1">Preview</a>
              <br /><br />
            <?php
            }
            ?>

            <div class="form-group">
              <label>Performance Certificate Image</label>
              <input type="file" name="performance_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <?php
            if ($performance_image != '') {
            ?>
              <img src="<?= $performance_image ?>" style="width:150px; height:100%; border-radius:0;" />
              <a href="page.php?page=previewPerformanceCertificate" target="_blank" class="btn btn-primary btn1">Preview</a>
              <br /><br />
            <?php
            }
            ?>

            <input type="submit" name="update_images" class="btn btn-success mr-2" value="Submit">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>