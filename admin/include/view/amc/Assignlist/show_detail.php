  <?php
  $institute_id1 = isset($_GET['id']) ? $_GET['id'] : '';
  $amc_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
  include_once('include/classes/student.class.php');
  include_once('include/classes/institute.class.php');
  $student = new student();
  $institute = new institute();
  include_once('include/classes/amc.class.php');

  $amc = new amc();
  $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
  $user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
  if ($user_role == 5) {
    $institute_id = $db->get_parent_id($user_role, $user_id);
    $staff_id = $user_id;
  } else {
    $institute_id = $user_id;
    $staff_id = 0;
  }
  ?>
  <?php
  //AMC DETAILS	
  $res = $amc->list_amc($amc_id, '');
  if ($res != '') {
    $srno = 1;
    while ($data = $res->fetch_assoc()) {
      $AMC_CODE         = $data['AMC_CODE'];
      $AMC_COMPANY_NAME   = $data['AMC_COMPANY_NAME'];
      $AMC_NAME           = $data['AMC_NAME'];
    }
  }
  ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="text-center" style="margin-top: 7px;">
        <?= $inst_name = $db->get_institute_name($institute_id1); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">AMC</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content" style="margin-right: -366px;">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-2 col-xs-6 text-center">
          <!-- small box -->
          <div class="bg-teal text-center" style="padding: 2em;">
            <div class="inner">
              <?php
              $res = $student->list_student('', $institute_id1, '');
              $count = ($res != '') ? $res->num_rows : 0;
              ?>
              <h3><i class="fa fa-user"></i> <?= $count ?></h3>

              <p>Total Admissions Of All Assign Institute</p>
            </div>

          </div>
        </div>
        <!-- ./col -->

        <?php
        $res = $institute->total_institute_payments($institute_id1, '');
        $TOTAL_EXAM_FEES = isset($res['TOTAL_EXAM_FEES']) ? $res['TOTAL_EXAM_FEES'] : 0;

        ?>
        <!--  <div class="col-lg-3 col-xs-6 text-center">
          <!-- small box -
          <div class="bg-teal text-center" style="padding: 2em;">
            <div class="inner">			
              <h3><i class="fa fa-inr"></i> <?= $TOTAL_EXAM_FEES ?></h3>
              <p>DITRP Payments</p>
			 
            </div>
           
          </div>
        </div>-->
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
        <div class="col-lg-2 col-xs-6 text-center">
          <!-- small box -->
          <div class="bg-teal text-center" style="padding: 2em;">
            <div class="inner">
              <h3><i class="fa fa-user"></i> <?= $count ?></h3>
              <p>Total Confirm Admission For All Assign Institute</p>

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

    <section class="content-header">

      <br />

    </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12" style="margin-top: -111px;">
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

      <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <section class="content">
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
          <div class="row">
            <div class="col-xs-12" style="margin-top: -75px;">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Area Managing Center Payment Details</h3>
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
                            <th>Comission</th>
                            <th>Payment Status</th>
                            <th>Payment Date</th>

                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $cond .= "  AND A.USER_ROLE=2 AND A.USER_ID IN (SELECT INSTITUTE_ID FROM amc_assign WHERE AMC_ID=$amc_id)";
                          include('include/classes/admin.class.php');
                          $admin = new admin();
                          $history = $admin->get_recharge_history_amc('', '', $institute_id1, 2, $cond, " AND A.PAYMENT_STATUS='success'", " AND A.TRANSACTION_TYPE='CREDIT' AND A.BONUS_STAUS!='1'");
                          arsort($history);
                          // print_r($history);

                          $walletres = $access->get_wallet('', '', '');
                          if (!empty($history)) {
                            $finalTotal = 0;
                            $sr = 1;
                            foreach ($history as $trans => $transArr) {
                              if (is_array($transArr) && !empty($transArr)) {

                                extract($transArr);
                                // $action="<button type='button' class='btn btn-primary pay' data-toggle='modal' data-target='#exampleModal'  data-id='$USER_ID' data-amc='$amc_id' data-comission='$' data-date=''> Pay</button>";
                                //Here USER_ID is a institute id 
                                $finalTotal += $AMOUNT;
                                $COMISSION = $AMOUNT * 0.15;
                                $AMC_PAYMENT_STATUS_FLAG = ($AMC_PAYMENT_STATUS == 1) ? 'PAID' : 'UNPAID';
                                $pay_disabled  = ($AMC_PAYMENT_STATUS == 1) ? 'true' : 'false';

                                $unpaid = "<button type='button' class='btn bg-yellow btn-flat margin pull-right pay' data-toggle='modal' data-target='#exampleModal' data-paymentid='$PAYMENT_ID' data-id='$USER_ID' data-amc='$amc_id' data-paymentmode='$PAYMENT_MODE' data-comission='$COMISSION'> Make Payment </button>";
                                $paid = "<button type='button' class='btn btn-success'> PAID </button>";
                                //  $action  = ($AMC_PAYMENT_STATUS==0)?$unpaid:$paid;

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
                                                        <td>$COMISSION</td>
                                                        <td>$AMC_PAYMENT_STATUS_FLAG</td>
                                                        <td></td>
                                                       
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
                                                        <td>$COMISSION</td>
                                                        <td>$AMC_PAYMENT_STATUS_FLAG</td>
                                                        <td></td>
                                                       
                                                    </tr>";
                                }
                              }
                              $sr++;
                            }
                          }
                          ?>
                          <!--   <tr>
                                        <td>TOTAL AMOUNT</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?= $finalTotal ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>-->
                        </tbody>

                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <!-- /.box-footer-->
  </div>
  </section>
  </div>
  <!--  </body> -->