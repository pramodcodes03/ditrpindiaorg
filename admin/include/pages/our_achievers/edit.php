<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';

$action = isset($_POST['edit_achievers']) ? $_POST['edit_achievers'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $websiteManage->edit_achievers($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:/website_management/manageAchievers');
  }
}

$res = $websiteManage->list_achievers($id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    extract($data);

    $photo = ACHIEVERS_PATH . '/' . $id . '/' . $image;
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Achiever</h4>
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
            <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>" />
            <div class="form-group">
              <label for="exampleInputName1">Name</label>
              <input type="text" class="form-control" id="exampleInputName1" name="name" placeholder="name" value="<?= isset($_POST['name']) ? $_POST['name'] : $name ?>">
            </div>
            <div class="form-group">
              <label for="exampleTextarea1">Description</label>
              <textarea class="form-control" id="exampleTextarea1" rows="4" name="description"><?= isset($_POST['description']) ? $_POST['description'] : $description ?></textarea>
            </div>
            <div class="form-group">
              <label for="exampleInputName1">Course </label>
              <input type="text" class="form-control" id="exampleInputName1" name="course" placeholder="course" value="<?= isset($_POST['course']) ? $_POST['course'] : $course ?>">
            </div>
            <div class="form-group">
              <label>Image</label>
              <input type="file" name="achievers_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $photo ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <input type="submit" name="edit_achievers" class="btn btn-primary mr-2" value="Submit">
            <a href="/website_management/manageAchievers" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>