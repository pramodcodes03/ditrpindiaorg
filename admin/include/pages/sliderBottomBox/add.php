<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_box']) ? $_POST['update_box'] : '';

include_once('include/classes/websiteManage.class.php');
$websiteManage = new websiteManage();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $websiteManage->edit_sliderbox($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:/website_management/SliderBox');
  }
}

$res = $websiteManage->list_sliderbox($id, '');
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
          <h4 class="card-title">Add Boxes Data</h4>
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
            <input type="hidden" name="id" value="<?= $id ?>" />

            <div class="form-group">
              <label for="exampleInputName1">Box Title 1 </label>
              <input type="text" class="form-control" name="box1_title" value="<?= isset($_POST['box1_title']) ? $_POST['box1_title'] : $box1_title ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Box Description 1 </label>
              <input type="text" class="form-control" name="box1_desc" value="<?= isset($_POST['box1_desc']) ? $_POST['box1_desc'] : $box1_desc ?>">
            </div>

            <div class="form-group col-md-3">
              <label for="exampleInputName1">Box Color 1 </label>
              <input type="color" class="form-control" name="box_color1" value="<?= isset($_POST['box_color1']) ? $_POST['box_color1'] : $box_color1 ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Box Title 2 </label>
              <input type="text" class="form-control" name="box2_title" value="<?= isset($_POST['box2_title']) ? $_POST['box2_title'] : $box2_title ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Box Description 2 </label>
              <input type="text" class="form-control" name="box2_desc" value="<?= isset($_POST['box2_desc']) ? $_POST['box2_desc'] : $box2_desc ?>">
            </div>

            <div class="form-group col-md-3">
              <label for="exampleInputName1">Box Color 2 </label>
              <input type="color" class="form-control" name="box_color2" value="<?= isset($_POST['box_color2']) ? $_POST['box_color2'] : $box_color2 ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Box Title 3 </label>
              <input type="text" class="form-control" name="box3_title" value="<?= isset($_POST['box3_title']) ? $_POST['box3_title'] : $box3_title ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Box Description 3 </label>
              <input type="text" class="form-control" name="box3_desc" value="<?= isset($_POST['box3_desc']) ? $_POST['box3_desc'] : $box3_desc ?>">
            </div>

            <div class="form-group col-md-3">
              <label for="exampleInputName1">Box Color 3 </label>
              <input type="color" class="form-control" name="box_color3" value="<?= isset($_POST['box_color3']) ? $_POST['box_color3'] : $box_color3 ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Box Title 4 </label>
              <input type="text" class="form-control" name="box4_title" value="<?= isset($_POST['box4_title']) ? $_POST['box4_title'] : $box4_title ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputName1">Box Description 4 </label>
              <input type="text" class="form-control" name="box4_desc" value="<?= isset($_POST['box4_desc']) ? $_POST['box4_desc'] : $box4_desc ?>">
            </div>

            <div class="form-group col-md-3">
              <label for="exampleInputName1">Box Color 4 </label>
              <input type="color" class="form-control" name="box_color4" value="<?= isset($_POST['box_color4']) ? $_POST['box_color4'] : $box_color4 ?>">
            </div>



            <input type="submit" name="update_box" class="btn btn-success mr-2" value="Submit">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>