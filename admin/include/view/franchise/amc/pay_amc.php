<?php
$amc_id = isset($_GET['id']) ? $_GET['id'] : '';

include_once('include/classes/amc.class.php');
$amc = new amc();
$cond = '';
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
        <h1 class="text-center" style="margin-top: 11px;">
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
                                            <th>Date Of Payment</th>
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
                                                    $today = date("d/m/Y");
                                                    $COMISSION = $AMOUNT * 0.15;
                                                    $AMC_PAYMENT_STATUS_FLAG = ($AMC_PAYMENT_STATUS == 1) ? 'PAID' : 'UNPAID';
                                                    $pay_disabled  = ($AMC_PAYMENT_STATUS == 1) ? 'true' : 'false';

                                                    $unpaid = "<button type='button' class='btn bg-yellow btn-flat margin pull-right pay' data-toggle='modal' data-target='.amc_payment_modal' data-paymentid='$PAYMENT_ID' data-id='$USER_ID' data-amc='$amc_id' data-paymentmode='$PAYMENT_MODE' data-comission='$COMISSION'> Make Payment </button>";
                                                    $paid = "<button type='button' class='btn btn-success'> PAID </button>";
                                                    $action  = ($AMC_PAYMENT_STATUS == 0) ? $unpaid : $paid;
                                                    $date = ($AMC_PAYMENT_STATUS == 0) ? 'Not Paid Yet' : $today;

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
                                                         <td>$date</td>
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
                                                          <td>$date</td>
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
<div class="modal fade amc_payment_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <img src="resources/dist/img/loader.gif" class="loader-mg-modal" />
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="box box-primary modal-body">
                <div class="box-header with-border">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="box-title">Pay To AMC</h3>
                </div>

                <form class="form-horizontal form-validate" name="paydetailsfrm" id="paydetailsfrm" action="" method="post">
                    <input type="hidden" name="payment_id" id="paymentid">
                    <input type="hidden" name="institute_id" id="institute_id">
                    <input type="hidden" name="amc_id" id="amc">
                    <input type="hidden" name="payment_mode" id="paymentmode">
                    <input type="hidden" name="action" value="save_payment">

                    <div class="box-body">
                        <div class="form-group <?= (isset($errors['amount'])) ? 'has-error' : '' ?>">
                            <label for="amount" class="col-sm-4 control-label">Amount</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-inr"></i></span>
                                    <input class="form-control" id="amount" name="amount" placeholder="Enter amount" value="<?= isset($_POST['amount']) ? $_POST['amount'] : '' ?>" type="text" autocomplete="off" required>
                                </div>
                                <span class="help-block"><?= (isset($errors['amount'])) ? $errors['amount'] : '' ?></span>
                            </div>
                        </div>

                        <div class="form-group <?= (isset($errors['pay_mode'])) ? 'has-error' : '' ?>">
                            <label for="pay_mode" class="col-sm-4 control-label">Select Payment Mode</label>
                            <div class="col-sm-8">
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
                            <div class="col-sm-8">
                                <textarea class="form-control" id="pay_remark" name="pay_remark" placeholder="Payment Remarks"><?= isset($_POST['pay_remark']) ? $_POST['pay_remark'] : '' ?></textarea>
                            </div>
                        </div>

                        <div class="form-group <?= (isset($errors['cheque_no'])) ? 'has-error' : '' ?>">
                            <label for="cheque_no" class="col-sm-4 control-label">Cheque / Demand Draft No.</label>
                            <div class="col-sm-8">
                                <input class="form-control" id="cheque_no" name="cheque_no" placeholder="Enter cheque / demand draft number" value="<?= isset($_POST['cheque_no']) ? $_POST['cheque_no'] : '' ?>" type="text" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group <?= (isset($errors['cheque_date'])) ? 'has-error' : '' ?>">
                            <label for="cheque_date" class="col-sm-4 control-label">Payment Date</label>
                            <div class="col-sm-8">
                                <input class="form-control" id="dob" name="cheque_date" placeholder="Payment Date" value="<?= isset($_POST['cheque_date']) ? $_POST['cheque_date'] : '' ?>" type="text" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group <?= (isset($errors['cheque_bank'])) ? 'has-error' : '' ?>">
                            <label for="cheque_bank" class="col-sm-4 control-label">Bank Details</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="cheque_bank" name="cheque_bank" placeholder="Bank Details"><?= isset($_POST['cheque_bank']) ? $_POST['cheque_bank'] : '' ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary">Make Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>