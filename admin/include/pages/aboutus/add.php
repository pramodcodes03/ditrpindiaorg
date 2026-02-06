<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_about']) ? $_POST['update_about'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $websiteManage->edit_about($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:/website_management/AboutUs');
  }
}

$res = $websiteManage->list_about($id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    extract($data);

    $homepage = ABOUTUS_PATH . '/' . $id . '/' . $homepage_image;
    $mission = ABOUTUS_PATH . '/' . $id . '/' . $mission_image;
    $vision = ABOUTUS_PATH . '/' . $id . '/' . $vision_image;
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">About Us</h4>
          <form class="forms-sample" action="" method="POST" enctype="multipart/form-data">
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
            <input type="hidden" name="id" value="1" />

            <div class="form-group">
              <label for="exampleInputName1">About Us Short Description </label>
              <input type="text" class="form-control" id="tinyMceExample3" name="about_short" value="<?= isset($_POST['about_short']) ? $_POST['about_short'] : $about_short ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">About Us Long Description </label>
              <textarea class="form-control" id="tinyMceExample" rows="4" name="about_long"><?= isset($_POST['about_long']) ? $_POST['about_long'] : $about_long ?></textarea>
            </div>

            <div class="form-group">
              <label>HomePage About Us Image</label>
              <input type="file" name="homepage_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $homepage ?>" style="width:150px; height:100%; border-radius:0;" /> <br />

            <div class="form-group">
              <label for="exampleInputName1">Mission Short Description </label>
              <input type="text" class="form-control" id="tinyMceExample4" name="mission_short" value="<?= isset($_POST['mission_short']) ? $_POST['mission_short'] : $mission_short ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Mission Long Description </label>
              <textarea class="form-control" id="tinyMceExample1" rows="4" name="mission_long"><?= isset($_POST['mission_long']) ? $_POST['mission_long'] : $mission_long ?></textarea>
            </div>

            <div class="form-group">
              <label>Mission Image</label>
              <input type="file" name="mission_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $mission ?>" style="width:150px; height:100%; border-radius:0;" /> <br />

            <div class="form-group">
              <label for="exampleInputName1">Vision Short Description </label>
              <input type="text" class="form-control" id="tinyMceExample5" name="vision_short" value="<?= isset($_POST['vision_short']) ? $_POST['vision_short'] : $vision_short ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Vision Long Description </label>
              <textarea class="form-control" id="tinyMceExample2" rows="4" name="vision_long"><?= isset($_POST['vision_long']) ? $_POST['vision_long'] : $vision_long ?></textarea>
            </div>

            <div class="form-group">
              <label>Vision Image</label>
              <input type="file" name="vision_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $vision ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <input type="submit" name="update_about" class="btn btn-success mr-2" value="Submit">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>