<?php
error_reporting(E_ALL);
include_once('include/controller/admin/expense/updateexpensetype.php');
include_once('include/classes/expense.class.php');
$expense = new expense();
?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Update Expense Type</h4>
          <form class="forms-sample" action="" method="post">
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
            <input type="hidden" class="form-control" name="id" placeholder="expense_id" value="<?= $CATEGORY_ID ?>">
            <div class="row">
              <div class="form-group col-sm-6">
                <label for="category">Expense Type</label>
                <input type="text" name="expensetype" value="<?= isset($_POST['expensetype']) ? $_POST['expensetype'] : $CATEGORY ?>" class="form-control" id="expensetype" placeholder="Expense Type">
              </div>
            </div>
            <input type="submit" name="updateexpense" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=listExpenseCategory" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>