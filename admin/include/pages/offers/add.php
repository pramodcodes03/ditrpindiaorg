<?php
$action = isset($_POST['add_advertise']) ? $_POST['add_advertise'] : '';
include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();
if ($action != '') {
  $result = $websiteManage->add_advertise();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:/website_management/manageAdvertise');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add New Advertise</h4>
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

            <div class="form-group col-sm-4 <?= (isset($errors['website'])) ? 'has-error' : '' ?>">
              <label>Select Type </label>
              <?php $website = isset($_POST['website']) ? $_POST['website'] : ''; ?>
              <select class="form-control" name="website" id="website">
                <option <?= ($website == '') ? 'selected="selected"' : '' ?> value="">--select--</option>
                <option value="1" <?= ($website == '1') ? 'selected="selected"' : '' ?>>Website</option>
                <option value="2" <?= ($website == '2') ? 'selected="selected"' : '' ?>>Institute Portal</option>
              </select>
              <span class="help-block"><?= (isset($errors['website'])) ? $errors['website'] : '' ?></span>
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Title</label>
              <input type="text" class="form-control" id="exampleInputName1" name="name" placeholder="name" value="">
            </div>
            <div class="form-group">
              <label for="exampleInputName1">Link</label>
              <input type="text" class="form-control" id="exampleInputName1" name="link" placeholder="link" value="">
            </div>
            <div class="form-group">
              <label>Advertise Image</label>
              <input type="file" name="advertiseimage" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <input type="submit" name="add_advertise" class="btn btn-primary mr-2" value="Submit">
            <a href="/website_management/manageAdvertise" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>