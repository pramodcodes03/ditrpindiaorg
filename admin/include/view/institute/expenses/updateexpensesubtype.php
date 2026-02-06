<?php
include_once('include/classes/expense.class.php');
$expense = new expense();


$expense_id = isset($_GET['id']) ? $_GET['id'] : '';
$action = isset($_POST['update_expense']) ? $_POST['update_expense'] : '';
include_once('include/classes/expense.class.php');
$expense = new expense();
if ($action != '') {
  $result = $expense->updateexpensesubtype($expense_id);
  $result = json_decode($result, true);
  $success = isset($result['success']) ? $result['success'] : '';
  $message = $result['message'];
  $errors = isset($result['errors']) ? $result['errors'] : '';
  if ($success == true) {
    $_SESSION['msg'] = $message;
    $_SESSION['msg_flag'] = $success;
    header('location:page.php?page=listSubExpenseCategory');
  }
}
/* get course details */
$res = $expense->list_expensesubcategory($expense_id, '');
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
          <h4 class="card-title">Update Expense SubCategory</h4>
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
            <input type="hidden" class="form-control" name="id" placeholder="id" value="<?= $SUBCATEGORY_ID ?>">
            <div class="row">
              <div class="form-group col-sm-6">
                <label for="category">Expense Type</label><?= $CATEGORY_ID ?>
                <select class="form-control" name="expensetype" id="expensetype">
                  <?php
                  $expensetype = isset($_POST['expensetype']) ? $_POST['expensetype'] : $CATEGORY_ID;
                  echo $db->MenuItemsDropdown('expense_category', 'CATEGORY_ID', 'CATEGORY', 'CATEGORY_ID,CATEGORY', $expensetype, ' WHERE ACTIVE=1 AND DELETE_FLAG=0');
                  ?>

                </select>
              </div>

              <div class="form-group col-sm-6" style="width:110px;">
                <label for="category">Expense Sub Type</label>
                <input type="text" name="expensesubtype" value="<?= isset($_POST['expensesubtype']) ? $_POST['expensesubtype'] : $SUBCATEGORY ?>" class="form-control" id="expensesubtype" placeholder="Expense Sub Type">
              </div>
            </div>
            <input type="submit" name="update_expense" class="btn btn-primary mr-2" value="Submit">
            <a href="page.php?page=listSubExpenseCategory" class="btn btn-danger mr-2" title="Cancel">Cancel</a>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>