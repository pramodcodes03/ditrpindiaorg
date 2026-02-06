<?php
$action = isset($_POST['add_product']) ? $_POST['add_product'] : '';
include_once('include/classes/tools.class.php');
$tools = new tools();
if ($action != '') {
  $result = $tools->add_product();
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
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Add Product
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
              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Product Name <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" id="exampleInputName1" name="name" placeholder="name" value="">
                <span class="help-block"><?= isset($errors['name']) ? $errors['name'] : '' ?></span>
              </div>

              <div class="col-md-6 form-group">
                <label for="exampleInputName1">Product Link <span class="asterisk"> * </span></label>
                <input type="text" class="form-control" name="link" value="">
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
              <input type="submit" name="add_product" class="btn btn-primary mr-2" value="Submit">
              <a href="page.php?page=viewProduct" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>