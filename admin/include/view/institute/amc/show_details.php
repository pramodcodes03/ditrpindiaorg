  <?php
  $institute_id1 = isset($_GET['id']) ? $_GET['id'] : '';
  include_once('include/classes/student.class.php');
  include_once('include/classes/institute.class.php');
  $student = new student();
  $institute = new institute();
  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
  $user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
  if ($user_role == 5) {
    $institute_id = $db->get_parent_id($user_role, $user_id);
    $staff_id = $user_id;
  } else {
    $institute_id = $user_id;
    $staff_id = 0;
  }
  $inst_name = $db->get_institute_name($institute_id1);
  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Institute Details Assign To Area Managing Center
        <small>Institute Details</small>
      </h1>
      <ol class="breadcrumb">
        <li><a <a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">AMC</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <h1 class="text-center" style="margin-top: 11px;">
        <?= $inst_name ?>
      </h1>
      <div class="row" style="margin-top: 24px;">
        <div class="col-lg-3 col-xs-6 text-center">
          <!-- small box -->
          <div class="bg-teal text-center" style="padding: 4em;">
            <div class="inner">
              <?php
              $res = $institute->get_total_admissions_amc($institute_id1);
              $count = ($res != '') ? $res->num_rows : 0;
              ?>
              <h3><i class="fa fa-inr"></i><?= $count ?></h3>

              <p>Admissions</p>
            </div>

          </div>
        </div>
        <!-- ./col -->

        <?php
        $res = $institute->total_institute_payments($institute_id1, '');
        $TOTAL_EXAM_FEES = isset($res['TOTAL_EXAM_FEES']) ? $res['TOTAL_EXAM_FEES'] : 0;

        ?>
        <div class="col-lg-3 col-xs-6 text-center">
          <!-- small box -->
          <div class="bg-teal text-center" style="padding: 4em;">
            <div class="inner">
              <?php
              $res = $institute->getTotalWallet_amc($institute_id1);
              $count = ($res != '') ? $res->num_rows : 0;
              ?>
              <h3><i class="fa fa-inr"></i> <?= $count   ?></h3>
              <p>Wallet Recharge</p>

            </div>

          </div>
        </div>
        <?php
        $ALL_COURSE_FEES = $TOTAL_FEES_PAID = $TOTAL_FEES_BALANCE = 0;
        if ($user_role == 5)
          $res = $institute->total_payments($institute_id1, $user_id);
        else
          $res = $institute->total_payments($institute_id1, '');
        if (!empty($res)) {
          $ALL_COURSE_FEES = $res['ALL_COURSE_FEES'];
          $TOTAL_FEES_PAID = $res['TOTAL_FEES_PAID'];
          $TOTAL_FEES_BALANCE = $res['TOTAL_FEES_BALANCE'];
        }
        ?>
        <div class="col-lg-3 col-xs-6 text-center">
          <!-- small box -->
          <div class="bg-teal text-center" style="padding: 4em;">
            <div class="inner">
              <h3><i class="fa fa-inr"></i> <?= $TOTAL_FEES_PAID ?></h3>
              <p>Student Payments</p>

            </div>

          </div>
        </div>
        <div class="col-lg-3 col-xs-6 text-center">
          <!-- small box -->
          <div class="bg-teal text-center" style="padding: 4em;">
            <div class="inner">
              <?php
              $res = $institute->get_total_admissions_amc($institute_id1);
              $count = ($res != '') ? $res->num_rows : 0;
              ?>
              <h3><i class="fa fa-user"></i> <?= $count ?></h3>
              <p>Confirm Students</p>

            </div>

          </div>
        </div>
      </div>





    </section>

    <?php
    $search     = isset($_POST['search']) ? $_POST['search'] : '';
    $wallet_id  = isset($_REQUEST['wallet']) ? $_REQUEST['wallet'] : '';
    $user_id    = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
    $user_role  = isset($_REQUEST['user_role']) ? $_REQUEST['user_role'] : '2';
    $datefrom   = isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : '';
    $dateto     = isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : '';
    $paymentmode1 = isset($_REQUEST['paymentmode1']) ? $_REQUEST['paymentmode1'] : '';
    $cond = '';

    if ($datefrom != '' && $dateto != '') {
      $datefrom1 = date('Y-m-d', strtotime($datefrom));
      $dateto1 = date('Y-m-d', strtotime($dateto));
      $cond = " AND A.CREATED_ON BETWEEN '$datefrom1' AND '$dateto1'";
    }

    ?>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <!-- /.box-header -->
            <div class="box-header">
              <h3 class="box-title">Search By Filters</h3>
            </div>
            <div class="box-body">
              <form action="" method="post" onsubmit="pageLoaderOverlay('show')">
                <input type="hidden" name="page" value="recharge-history" />
                <div class="form-group col-sm-2">
                  <label>Date From</label>
                  <input type="text" class="form-control" name="datefrom" id="dob" value="<?= $datefrom ?>" />
                </div>
                <div class="form-group col-sm-2">
                  <label>Date To</label>
                  <input type="text" class="form-control" name="dateto" id="doj" value="<?= $dateto ?>" />
                </div>
                <div class="form-group col-sm-2">
                  <label>Payment Mode</label>
                  <select class="form-control" name="paymentmode1">
                    <option value="">--select--</option>
                    <option value="ONLINE" <?= ($paymentmode1 == 'ONLINE') ? 'selected="selected"' : '' ?>>ONLINE</option>
                    <option value="OFFLINE" <?= ($paymentmode1 == 'OFFLINE') ? 'selected="selected"' : '' ?>>OFFLINE</option>
                  </select>
                </div>
                <div class="form-group col-sm-1">
                  <label> &nbsp;</label>
                  <input type="submit" class="form-control btn btn-sm btn-primary" value="Filter" name="search" />
                </div>
                <div class="form-group col-sm-1">
                  <label> &nbsp;</label>
                  <a class="form-control btn btn-sm btn-warning" onclick="pageLoaderOverlay('show'); location.assign('recharge-history')">Clear</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php
      if (isset($success)) {
      ?>
        <div class="row">
          <div class="col-sm-12">
            <div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
              <h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>:</h4>
              <?= isset($message) ? $message : 'Please correct the errors.'; ?>
            </div>
          </div>
        </div>
      <?php
      }
      ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Wallet Details</h3>

        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-sm-12">
              <table class="table table-bordered data-tbl">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Transaction No</th>
                    <th>Name</th>

                    <th>Mode</th>
                    <th>Status</th>
                    <th>Transaction Type</th>
                    <th>Recharge Date</th>
                    <th>Amount</th>
                    <!-- <th>Action</th> -->
                  </tr>
                </thead>
                <tbody>
                  <?php
                  include('include/classes/admin.class.php');
                  $admin = new admin();
                  $history = $admin->get_recharge_history_amc('', '', $institute_id1, 2, $cond, " AND A.PAYMENT_STATUS='success'", " AND A.TRANSACTION_TYPE='CREDIT'");
                  arsort($history);
                  //print_r($history);

                  $walletres = $access->get_wallet('', '', '');
                  if (!empty($history)) {
                    $sr = 1;
                    foreach ($history as $trans => $transArr) {
                      if (is_array($transArr) && !empty($transArr)) {

                        extract($transArr);
                        if ($paymentmode1 != ''  and $PAYMENT_MODE == $paymentmode1) {
                          echo "<tr>
                                        <td>$sr</td>
                                        <td>#$TRANSACTION_NO</td>
                                        <td>$USER_FULLNAME</td>
                                        <td>$PAYMENT_MODE</td>
                                        <td>$STATUS</td>
                                        <td>$TRANSACTION_TYPE</td>
                                        <td>$CREATED_DATE</td>
                                        <td>$AMOUNT</td>
                                    </tr>";
                        } else if ($paymentmode1 == '') {
                          echo "<tr>
                                        <td>$sr</td>
                                        <td>#$TRANSACTION_NO</td>
                                        <td>$USER_FULLNAME</td>
                                        <td>$PAYMENT_MODE</td>
                                        <td>$STATUS</td>
                                        <td>$TRANSACTION_TYPE</td>
                                        <td>$CREATED_DATE</td>
                                        <td>$AMOUNT</td>
                                    </tr>";
                        }
                      }
                      $sr++;
                    }
                  }
                  ?>
                <tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /.box-footer-->
      </div>
    </section>
  </div>
  <!--  </body> -->