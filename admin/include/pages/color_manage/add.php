<?php

$id = isset($_POST['id']) ? $_POST['id'] : '';
$action = isset($_POST['edit_color']) ? $_POST['edit_color'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $websiteManage->edit_color($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:/website_management/manageColors');
  }
}

$res = $websiteManage->list_color($id, '');
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
          <h4 class="card-title">Colour Management</h4>
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
            <div class="col-md-12">
              <div class="form-group col-md-3 floatLeft">
                <label for="exampleInputName1">Header Background Colour </label>
                <input type="color" class="form-control colorHeight" id="exampleInputName1" name="header_color" value="<?= isset($_POST['header_color']) ? $_POST['header_color'] : $header_color ?>">
              </div>

              <div class="form-group col-md-3 floatLeft">
                <label for="exampleInputName1">Footer Background Colour </label>
                <input type="color" class="form-control colorHeight" id="exampleInputName1" name="footer_color" value="<?= isset($_POST['footer_color']) ? $_POST['footer_color'] : $footer_color ?>">
              </div>

              <div class="form-group col-md-3 floatLeft">
                <label for="exampleInputName1">Top Header Background Colour </label>
                <input type="color" class="form-control colorHeight" id="exampleInputName1" name="top_header_color" value="<?= isset($_POST['top_header_color']) ? $_POST['top_header_color'] : $top_header_color ?>">
              </div>

              <div class="form-group col-md-3 floatLeft">
                <label for="exampleInputName1">Address Box Background Colour</label>
                <input type="color" class="form-control colorHeight" id="exampleInputName1" name="address_box_color" value="<?= isset($_POST['address_box_color']) ? $_POST['address_box_color'] : $address_box_color ?>">
              </div>

              <div class="form-group col-md-3 floatLeft">
                <label for="exampleInputName1">Marquee Background Colour </label>
                <input type="color" class="form-control colorHeight" id="exampleInputName1" name="marquee_color" value="<?= isset($_POST['marquee_color']) ? $_POST['marquee_color'] : $marquee_color ?>">
              </div>

              <div class="clearfix"></div>
              <input type="submit" name="edit_color" class="btn btn-success mr-2" value="Submit">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>