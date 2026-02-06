<?php

$id = isset($_POST['id']) ? $_POST['id'] : '';
$action = isset($_POST['update_marquee']) ? $_POST['update_marquee'] : '';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

include_once('include/classes/tools.class.php');
$tools = new tools();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $tools->edit_marquee($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listMarqueeNotification');
  }
}

$res = $tools->list_marquee('', " AND inst_id = $user_id");
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
          <h4 class="card-title">Manage Marquee</h4>
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
              <label for="exampleInputName1">Marquee Text </label>
              <input type="text" class="form-control" id="exampleInputName1" name="marqueetext" placeholder="Marquee Text" value="<?= isset($_POST['name']) ? $_POST['name'] : $name ?>">
            </div>

            <input type="submit" name="update_marquee" class="btn btn-success mr-2" value="Submit">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>