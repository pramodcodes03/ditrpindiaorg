<?php

$id = isset($_GET['id']) ? $_GET['id'] : '';

$action = isset($_POST['update_product']) ? $_POST['update_product'] : '';

include_once('include/classes/tools.class.php');
$tools = new tools();

if ($action != '') {
  $id = isset($_POST['id']) ? $_POST['id'] : '';

  $result = $tools->update_product($id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=viewProduct');
  }
}
/* get institute details */
$res = $tools->list_product($id, '');
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
          <h4 class="card-title">Update Product
          </h4>
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">
            <?php
            if (isset($success)) {
            ?>
              <div class="row">
                <div class="col-md-12">
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
            <div class="row">
              <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>" />
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Topic Name <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" id="exampleInputName1" name="name" placeholder="name" value="<?= isset($_POST['name']) ? $_POST['name'] : $name ?>">
                <span class="help-block"><?= isset($errors['name']) ? $errors['name'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Approval Number <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="link" value="<?= isset($_POST['link']) ? $_POST['link'] : $link ?>">
                <span class="help-block"><?= isset($errors['link']) ? $errors['link'] : '' ?></span>
              </div>

              <div class="col-md-12 form-group row">
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios1" value="1" checked>
                      Active
                    </label>
                  </div>
                </div>
                <div class="col-sm-2">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="radio" class="form-check-input" name="status" id="optionsRadios2" value="0">
                      Inactive
                    </label>
                  </div>
                </div>
              </div>
              <input type="submit" name="update_product" class="btn btn-primary mr-2" value="Update">
              <a href="page.php?page=viewProduct" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>