<?php
$amc_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

include_once('include/classes/student.class.php');
include_once('include/classes/institute.class.php');
include_once('include/classes/amc.class.php');

$amc = new amc();
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

//AMC DETAILS	
$res = $amc->list_amc($amc_id, '');
if ($res != '') {
    $srno = 1;
    while ($data = $res->fetch_assoc()) {
        $AMC_CODE           = $data['AMC_CODE'];
        $AMC_COMPANY_NAME     = $data['AMC_COMPANY_NAME'];
        $AMC_NAME           = $data['AMC_NAME'];
    }
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Area Managing Center Payment Details
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="page.php?page=list-amc">AMC</a></li>
            <li class="active">Area Managing Center Payment Details</li>
        </ol>
        <br />
        <h1 class="text-center">
            <?= $AMC_COMPANY_NAME ?> [ <?= $AMC_CODE ?> ]
        </h1>
    </section>
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
            <div class="col-sm-12">
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cond .= "  AND A.USER_ID IN (SELECT INSTITUTE_ID FROM amc_assign WHERE AMC_ID=$amc_id)";
                                        include('include/classes/admin.class.php');
                                        $admin = new admin();
                                        $history = $admin->get_recharge_history_amc('', '', '', 2, $cond, " AND A.PAYMENT_STATUS='success'", " AND A.TRANSACTION_TYPE='CREDIT' AND A.BONUS_STAUS!='1'");
                                        arsort($history);
                                        // print_r($history);

                                        $walletres = $access->get_wallet('', '', '');
                                        if (!empty($history)) {
                                            $sr = 1;
                                            foreach ($history as $trans => $transArr) {
                                                if (is_array($transArr) && !empty($transArr)) {

                                                    extract($transArr);
                                                    // $action="<button type='button' class='btn btn-primary pay' data-toggle='modal' data-target='#exampleModal'  data-id='$USER_ID' data-amc='$amc_id' data-comission='$' data-date=''> Pay</button>";
                                                    //Here USER_ID is a institute id 

                                                    $COMISSION = $AMOUNT * 0.15;
                                                    $AMC_PAYMENT_STATUS_FLAG = ($AMC_PAYMENT_STATUS == 1) ? 'PAID' : 'UNPAID';
                                                    $pay_disabled  = ($AMC_PAYMENT_STATUS == 1) ? 'true' : 'false';

                                                    $unpaid = "<button type='button' class='btn bg-yellow btn-flat margin pull-right pay' data-toggle='modal' data-target='#exampleModal' data-paymentid='$PAYMENT_ID' data-id='$USER_ID' data-amc='$amc_id' data-paymentmode='$PAYMENT_MODE' data-comission='$COMISSION'> Make Payment </button>";
                                                    $paid = "<button type='button' class='btn btn-success'> PAID </button>";
                                                    $action  = ($AMC_PAYMENT_STATUS == 0) ? $unpaid : $paid;

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
                                                        <td>$action</td>
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
                                                         <td>$action</td>
                                                    </tr>";
                                                    }
                                                }
                                                $sr++;
                                            }
                                        }
                                        ?>
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="box-title" id="exampleModalLabel">Pay To AMC </h3>
            </div>
            <form class="form-horizontal form-validate" name="paydetailsfrm" id="paydetailsfrm" action="" method="post">
                <div class="box-body">
                    <div class="col-md-12">
                        <div class="form-group <?= (isset($errors['amount'])) ? 'has-error' : '' ?>">
                            <label for="amount" class="col-sm-4 control-label">Amount</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                    <input class="form-control amount" id="amount" name="amount" placeholder="Enter amount" value="<?= isset($_POST['amount']) ? $_POST['amount'] : '' ?>" type="text" autocomplete="off" required>

                                    <input type="hidden" name="payment_id" id="paymentid">
                                    <input type="hidden" name="institute_id" id="institute_id">
                                    <input type="hidden" name="amc_id" id="amc">
                                    <input type="hidden" name="payment_mode" id="paymentmode">
                                    <input type="hidden" name="action" value="save_payment">
                                </div>
                                <span class="help-block"><?= (isset($errors['amount'])) ? $errors['amount'] : '' ?></span>
                            </div>
                        </div>

                        <div class="form-group <?= (isset($errors['pay_mode'])) ? 'has-error' : '' ?>">
                            <label for="pay_mode" class="col-sm-4 control-label">Select Payment Mode</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="pay_mode" id="pay_mode">
                                    <?php
                                    $user_id = isset($_POST['pay_mode']) ? $_POST['pay_mode'] : '';
                                    echo $db->MenuItemsDropdown('payment_modes_master', 'PAYMENT_MODE_ID', 'PAYMENT_MODE', 'PAYMENT_MODE_ID,PAYMENT_MODE', $user_id, " ");
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group <?= (isset($errors['pay_remark'])) ? 'has-error' : '' ?>">
                            <label for="pay_remark" class="col-sm-4 control-label">Payment Remarks</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" id="pay_remark" name="pay_remark" placeholder="Enter cheque / demand bank details"><?= isset($_POST['pay_remark']) ? $_POST['pay_remark'] : '' ?></textarea>
                            </div>
                        </div>


                        <div class="form-group <?= (isset($errors['cheque_no'])) ? 'has-error' : '' ?>">
                            <label for="cheque_no" class="col-sm-4 control-label">Cheque / Demand Draft No.</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="cheque_no" name="cheque_no" placeholder="Enter cheque / demand draft number" value="<?= isset($_POST['cheque_no']) ? $_POST['cheque_no'] : '' ?>" type="text" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group <?= (isset($errors['cheque_date'])) ? 'has-error' : '' ?>">
                            <label for="cheque_date" class="col-sm-4 control-label">Cheque / Demand Draft Date</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="dob" name="cheque_date" placeholder="Enter cheque / demand draft number" value="<?= isset($_POST['cheque_date']) ? $_POST['cheque_date'] : '' ?>" type="text" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group <?= (isset($errors['cheque_bank'])) ? 'has-error' : '' ?>">
                            <label for="cheque_bank" class="col-sm-4 control-label">Cheque / Demand Draft Bank Details</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" id="cheque_bank" name="cheque_bank" placeholder="Enter cheque / demand bank details"><?= isset($_POST['cheque_bank']) ? $_POST['cheque_bank'] : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-primary">Make Payment</button>
                </div>
        </div>
        </form>
    </div>
</div>