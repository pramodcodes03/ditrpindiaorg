<?php
$action = isset($_POST['add_jobpost']) ? $_POST['add_jobpost'] : '';
include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();
if ($action != '') {
  $result = $websiteManage->add_jobpost();
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:/website_management/manageJobs');
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add New Job</h4>
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
              <label for="exampleInputName1">Job Code</label>
              <input type="text" class="form-control" id="exampleInputName1" name="job_code" placeholder="job_code" value="">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Title</label>
              <input type="text" class="form-control" id="exampleInputName1" name="title" placeholder="title" value="">
            </div>


            <div class="form-group">
              <label for="exampleTextarea1">Description</label>
              <textarea class="form-control" id="exampleTextarea1" rows="4" name="description"></textarea>
            </div>

            <div class="form-group">
              <label for="exampleTextarea1">Skills</label>
              <textarea class="form-control" id="exampleTextarea1" rows="4" name="skills"></textarea>
            </div>

            <div class="form-group">
              <label>Image</label>
              <input type="file" name="job_image" class="file-upload-default">
              <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                <span class="input-group-append">
                  <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                </span>
              </div>
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Start Date</label>
              <input type="date" class="form-control" id="exampleInputName1" name="post_date" placeholder="post_date" value="">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Last Date</label>
              <input type="date" class="form-control" id="exampleInputName1" name="last_date" placeholder="last_date" value="">
            </div>

            <input type="submit" name="add_jobpost" class="btn btn-primary mr-2" value="Submit">
            <a href="/website_management/manageJobs" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>