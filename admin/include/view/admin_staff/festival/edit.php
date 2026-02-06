<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';

$action = isset($_POST['edit_festival']) ? $_POST['edit_festival'] : '';

include_once('include/classes/festival.class.php');
$festival = new festival();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $festival->update_festival($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=list-festival');
  }
}

$res = $festival->list_festival($id, '', '');
if ($res != '') {
  $srno = 1;
  while ($data = $res->fetch_assoc()) {
    extract($data);

    //$photo = FESTIVAL_IMAGES_PATH.'/'.$id.'/'.$image;
  }
}
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Festival</h4>
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
            <input class="form-control" id="id" name="id" placeholder="Festival Name" value="<?= isset($_POST['id']) ? $_POST['id'] : $id ?>" type="hidden">
            <div class="row">
              <div class="form-group col-sm-6 <?= (isset($errors['name'])) ? 'has-error' : '' ?>">
                <label for="name" class="col-sm-4 control-label">Festival Name</label>
                <div class="col-sm-8">
                  <input class="form-control" id="name" name="name" placeholder="Festival Name" value="<?= isset($_POST['name']) ? $_POST['name'] : $name ?>" type="text">
                  <span class="help-block"><?= isset($errors['name']) ? $errors['name'] : '' ?></span>
                </div>
              </div>

              <div class="form-group col-sm-6 <?= (isset($errors['date'])) ? 'has-error' : '' ?>">
                <label for="date" class="col-sm-4 control-label">Date</label>
                <div class="col-sm-8">
                  <input class="form-control" id="date" name="date" placeholder="Date" value="<?= isset($_POST['date']) ? $_POST['date'] : $date ?>" type="date">
                  <span class="help-block"><?= isset($errors['date']) ? $errors['date'] : '' ?></span>
                </div>
              </div>


              <div class="form-group col-sm-12 <?= (isset($errors['coursematerial0'])) ? 'has-error' : '' ?>">

                <div id="add_more_files">
                  <label for="coursematerial0" class="col-sm-2 control-label">Festival Images</label>
                  <div class="col-sm-10">
                    <?php
                    echo  $doc = $festival->get_docs_all($id, true);
                    ?>
                    <input type="hidden" name="filecount" id="filecount" value="0" />
                  </div>
                </div>

                <label for="addcoursematerial" class="col-sm-3 control-label"></label>
                <div class="col-sm-8">
                  <a href="javascript:void(0)" class="btn  btn-warning btn-xs" onclick="addMoreFestivalImages()"><i class="fa fa-plus"></i> Add more images</a>
                </div>
              </div>
              <div class="form-group col-sm-12">
                <?php
                $status = isset($_POST['status']) ? $_POST['status'] : $active;
                ?>
                <label for="status" class="col-sm-2 control-label">Status</label>
                <div class="radio col-sm-10">
                  <label>
                    <input name="status" id="optionsRadios1" value="1" <?= ($status == 1) ? "checked=''" : ''  ?> type="radio">
                    Active
                  </label>
                  <label>
                    <input name="status" id="optionsRadios2" value="0" <?= ($status == 0) ? "checked=''" : ''  ?> type="radio">
                    Inactive
                  </label>
                </div>
              </div>


              <input type="submit" name="edit_festival" class="btn btn-primary mr-2" value="Update Festival">
              <a href="page.php?page=list-festival" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>