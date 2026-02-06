<?php
$action = isset($_POST['add_sample_certificates']) ? $_POST['add_sample_certificates'] : '';
include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();
if ($action != '') {
  $result = $websiteManage->add_sample_certificates();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:/website_management/sampleCert');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add Sample Certificates </h4>
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
              <label>Select Type </label>
              <?php $type = isset($_POST['type']) ? $_POST['type'] : ''; ?>
              <select class="form-control" name="type" id="type">
                <option <?= ($type == '') ? 'selected="selected"' : '' ?> value="">--select--</option>
                <option value="ATC CERTIFICATES" <?= ($type == 'ATC CERTIFICATES') ? 'selected="selected"' : '' ?>>ATC CERTIFICATES</option>
                <option value="STUDENT CERTIFICATES" <?= ($type == 'STUDENT CERTIFICATES') ? 'selected="selected"' : '' ?>>STUDENT CERTIFICATES</option>
                <option value="OUR CERTIFICATES" <?= ($type == 'OUR CERTIFICATES') ? 'selected="selected"' : '' ?>>OUR CERTIFICATES</option>
              </select>
              <span class="help-block"><?= (isset($errors['type'])) ? $errors['type'] : '' ?></span>
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Title</label>
              <input type="text" class="form-control" name="name" placeholder="Title" value="">
            </div>

            <div class="form-group">
              <label>Image</label>
              <input type="file" name="sample_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Position</label>
              <input type="text" class="form-control" name="position" placeholder="Position" value="">
            </div>

            <input type="submit" name="add_sample_certificates" class="btn btn-primary mr-2" value="Submit">
            <a href="/website_management/sampleCert" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>