<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';

$action = isset($_POST['edit_social_links']) ? $_POST['edit_social_links'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $websiteManage->edit_social_links($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:/website_management/manageSocialLinks');
  }
}

$res = $websiteManage->list_social_links($id, '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    extract($data);
  }
}
?><div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Social Integration Link</h4>
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
              <label for="exampleFormControlSelect3">Link Type</label>
              <select class="form-control form-control-sm" id="exampleFormControlSelect3" id="master_id" name="master_id">
                <?php
                $master_id = isset($_POST['master_id']) ? $_POST['master_id'] : $master_id;
                echo $db->MenuItemsDropdown('social_media_master', 'id', 'name', 'id,name', $master_id, ' WHERE active=1');
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="exampleInputName1">Social Link</label>
              <input type="text" class="form-control" id="exampleInputName1" name="link" placeholder="link" value="<?= isset($_POST['link']) ? $_POST['link'] : $link ?>">
            </div>

            <input type="submit" name="edit_social_links" class="btn btn-primary mr-2" value="Submit">
            <a href="/website_management/manageSocialLinks" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>