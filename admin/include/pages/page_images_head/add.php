<?php

$id = isset($_POST['id']) ? $_POST['id'] : '';
$action = isset($_POST['edit_headimages']) ? $_POST['edit_headimages'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $websiteManage->edit_headimages($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:/website_management/manageHeaderImages');
  }
}

$res = $websiteManage->list_headimages($id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    extract($data);

    $aboutus      = BANNERS_PATH . '/' . $id . '/' . $aboutus;
    $courses      = BANNERS_PATH . '/' . $id . '/' . $courses;
    $services     = BANNERS_PATH . '/' . $id . '/' . $services;
    $achiever     = BANNERS_PATH . '/' . $id . '/' . $achiever;
    $gallery      = BANNERS_PATH . '/' . $id . '/' . $gallery;
    $team         = BANNERS_PATH . '/' . $id . '/' . $team;
    $jobs         = BANNERS_PATH . '/' . $id . '/' . $jobs;
    $verification = BANNERS_PATH . '/' . $id . '/' . $verification;
    $contact      = BANNERS_PATH . '/' . $id . '/' . $contact;
    $policies     = BANNERS_PATH . '/' . $id . '/' . $policies;

    $certificate  = BANNERS_PATH . '/' . $id . '/' . $certificate;
    $affiliations = BANNERS_PATH . '/' . $id . '/' . $affiliations;

    $download_materials = BANNERS_PATH . '/' . $id . '/' . $download_materials;
    $refund_policy      = BANNERS_PATH . '/' . $id . '/' . $refund_policy;
    $our_blogs          = BANNERS_PATH . '/' . $id . '/' . $our_blogs;
    $term_condition     = BANNERS_PATH . '/' . $id . '/' . $term_condition;
    $disclaimer         = BANNERS_PATH . '/' . $id . '/' . $disclaimer;
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Header Banner Management</h4>
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
            <input type="hidden" name="id" value="1" />

            <div class="form-group">
              <label>About Us Header Image</label>
              <input type="file" name="aboutus" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $aboutus ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Courses Header Image</label>
              <input type="file" name="courses" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $courses ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Services Header Image</label>
              <input type="file" name="services" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $services ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Achiever Header Image</label>
              <input type="file" name="achiever" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $achiever ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Gallery Header Image</label>
              <input type="file" name="gallery" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $gallery ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Team Header Image</label>
              <input type="file" name="team" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $team ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Jobs Header Image</label>
              <input type="file" name="jobs" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $jobs ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Verification Header Image</label>
              <input type="file" name="verification" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $verification ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Contact Header Image</label>
              <input type="file" name="contact" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $contact ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Policies Header Image</label>
              <input type="file" name="policies" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $policies ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Certfication Header Image</label>
              <input type="file" name="certificate" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $certificate ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Affiliations Header Image</label>
              <input type="file" name="affiliations" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $affiliations ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Download Materials Header Image</label>
              <input type="file" name="download_materials" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $download_materials ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Refund Policy Header Image</label>
              <input type="file" name="refund_policy" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $refund_policy ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Our Blogs Header Image</label>
              <input type="file" name="our_blogs" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $our_blogs ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Term Condition Header Image</label>
              <input type="file" name="term_condition" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $term_condition ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <div class="form-group">
              <label>Disclaimer Header Image</label>
              <input type="file" name="disclaimer" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>
            <img src="<?= $disclaimer ?>" style="width:150px; height:100%; border-radius:0;" /> <br /><br />

            <input type="submit" name="edit_headimages" class="btn btn-success mr-2" value="Submit">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>