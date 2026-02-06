<?php
include_once('include/classes/student.class.php');
$student = new student();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 3) {
    $institute_id = $db->get_parent_id($user_role, $user_id);
    $staff_id = $user_id;
} else {
    $institute_id = $user_id;
    $staff_id = 0;
}
$action     = '';
$action    = isset($_POST['save_admission']) ? $_POST['save_admission'] : '';
if ($action != '') {
    //print_r($_POST); exit();

    $result = $student->add_student_re_admission();

    $result = json_decode($result, true);
    $success = isset($result['success']) ? $result['success'] : '';
    $message = isset($result['message']) ? $result['message'] : '';
    $errors = isset($result['errors']) ? $result['errors'] : '';
    if ($success == true) {
        $_SESSION['msg'] = $message;
        $_SESSION['msg_flag'] = $success;
        header('location:page.php?page=studentAdmission');
    }
    //print_r($errors);
}
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"> Re-Admission Student </h4>
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
                    <form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">
                        <div class="row">
                            <input type="hidden" name="institute_id" value="<?= $institute_id ?>" />

                            <div class="form-group col-md-4">
                                <label for="student_id">Select Student:</label>
                                <select class="form-control select2" name="student_id" id="student_id" onchange="getCourseListNotPurchase(this.value)">
                                    <?php echo $db->MenuItemsDropdown('student_details', "STUDENT_ID", "STUDENT_NAME", "STUDENT_ID, CONCAT(CONCAT(STUDENT_FNAME,' ',STUDENT_MNAME),' ',STUDENT_LNAME) STUDENT_NAME", $student_id, "  WHERE DELETE_FLAG=0 AND INSTITUTE_ID='$institute_id'");
                                    ?>
                                </select>
                                <span class="help-block"><?= (isset($errors['student_id'])) ? $errors['student_id'] : '' ?></span>
                            </div>


                            <div class="form-group col-sm-4 <?= (isset($errors['interested_course'])) ? 'has-error' : '' ?>">
                                <label for="interested_course">Course of interest <span class="asterisk">*</span></label>
                                <?php $interested_course  = isset($_POST['interested_course']) ? $_POST['interested_course'] : ''; ?>
                                <select class="form-control select2" name="interested_course" data-placeholder="Select a Course" id="coursename" onchange="getcoursefees()" required>
                                    <option name="" value="">Select a Course</option>
                                    <?php
                                    $sql = "SELECT A.INSTITUTE_COURSE_ID, A.COURSE_ID, A.MULTI_SUB_COURSE_ID, A.COURSE_TYPE, A.TYPING_COURSE_ID FROM institute_courses A WHERE  A.INSTITUTE_ID='$institute_id' AND A.DELETE_FLAG=0 AND A.ACTIVE=1";
                                    //echo $sql;
                                    $ex = $db->execQuery($sql);
                                    if ($ex && $ex->num_rows > 0) {
                                        while ($data = $ex->fetch_assoc()) {
                                            $INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
                                            $COURSE_ID              = $data['COURSE_ID'];
                                            $MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
                                            $TYPING_COURSE_ID      = $data['TYPING_COURSE_ID'];

                                            if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != '0') {
                                                $course              = $db->get_course_detail($COURSE_ID);
                                                $course_name          = $course['COURSE_NAME_MODIFY'];
                                            }

                                            if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != '0') {
                                                $course              = $db->get_course_detail_multi_sub($MULTI_SUB_COURSE_ID);
                                                $course_name          = $course['COURSE_NAME_MODIFY'];
                                            }

                                            if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != '0') {
                                                $course = $db->get_course_detail_typing($TYPING_COURSE_ID);
                                                $course_name     = $course['COURSE_NAME_MODIFY'];
                                            }

                                            $selected = (is_array($interested_course) && in_array($INSTITUTE_COURSE_ID, $interested_course)) ? 'selected="selected"' : '';

                                            echo '<option value="' . $INSTITUTE_COURSE_ID . '" ' . $selected . '>' . $course_name . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="help-block"><?= (isset($errors['interested_course'])) ? $errors['interested_course'] : '' ?></span>
                            </div>
                            <div class="form-group col-sm-4 <?= (isset($errors['examtype1'])) ? 'has-error' : '' ?>">
                                <label>Select Exam Type <span class="asterisk">*</span></label>
                                <?php $examtype1 = isset($_POST['examtype1']) ? $_POST['examtype1'] : ''; ?>
                                <select class="form-control" name="examtype1" id="examtype">
                                    <?php echo $db->MenuItemsDropdown('exam_types_master', "EXAM_TYPE_ID", "EXAM_TYPE", "EXAM_TYPE_ID, EXAM_TYPE", $examtype1, " WHERE ACTIVE=1 AND DELETE_FLAG=0"); ?>
                                </select>
                                <span class="help-block"><?= (isset($errors['examtype1'])) ? $errors['examtype1'] : '' ?></span>
                            </div>
                            <input type="hidden" class="btn btn-sm btn-primary" name="examstatus1" value="2">

                            <div class="form-group col-sm-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Course Fees</th>
                                            <th>Discount Rate</th>
                                            <th>Discount Amount</th>
                                            <th>Total Fees</th>
                                            <th>Fees Recieved</th>
                                            <th>Balance</th>
                                            <th>Remarks</th>

                                        </tr>
                                    </thead>
                                    <tbody id="courses-rows">

                                        <tr id="courserow">
                                            <td>
                                                <input type="text" class="form-control" name="coursefees" id="coursefees" value="" />
                                            </td>
                                            <td>
                                                <select class="form-control" name="discrate" id="discrate" onchange="calDiscountedAmt()">
                                                    <option value="amtminus" selected="selected">Amount - </option>
                                                    <option value="amtplus">Amount + </option>
                                                    <option value="perminus">Percent - </option>
                                                    <option value="perplus">Percent + </option>
                                                </select>
                                            </td>

                                            <td>

                                                <input type="text" class="form-control" name="discamt" id="discamt" onchange="calDiscountedAmt()" onkeyup="calDiscountedAmt()" value="" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="totalcoursefee" id="totalcoursefee" readonly value="" />
                                            </td>
                                            <td>

                                                <input type="text" class="form-control" name="amtrecieved" id="amtrecieved" onchange="calTotalPerCourse()" onkeyup="calTotalPerCourse()" value="" />
                                                <span style="color:#f00" id="amtrecieved_err"></span>
                                            </td>
                                            <td>

                                                <input type="text" class="form-control" name="amtbalance" id="amtbalance" readonly value="" />
                                            </td>
                                            <td>

                                                <textarea class="form-control" name="payremarks" id="payremarks"></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Installment Details</label>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Installment Name</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="courses-rows">

                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="installment_name0" id="installment_name0" value="" />
                                            </td>

                                            <td>
                                                <input type="text" class="form-control" name="installment_amount0" id="installment_amount" value="" />
                                            </td>

                                            <td>
                                                <input type="date" class="form-control" name="installment_date0" id="installment_date" value="" max="2999-12-31" />
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-warning btn1" onclick="addMoreInstallments()"><i class="fa fa-plus"></i> Add More</a>
                                            </td>
                                            <input type="hidden" name="filecount4" id="filecount4" value="1" />
                                        </tr>
                                        <tr id="add_more_installments">

                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group <?= (isset($errors['examfees'])) ? 'has-error' : '' ?>">
                                <label for="examfees">Exam Fees</label>
                                <input type="text" name="examfees" class="form-control" id="examfees" value="" placeholder="Exam Fees" readonly>
                                <span class="help-block"><?= (isset($errors['examfees'])) ? $errors['examfees'] : '' ?></span>
                            </div>

                            <div class="form-group col-sm-4 <?= (isset($errors['batch'])) ? 'has-error' : '' ?>">
                                <label> Select Batch For Student <span class="asterisk">*</span></label>
                                <?php $batch = isset($_POST['batch']) ? $_POST['batch'] : ''; ?>
                                <select class="form-control select2 " name="batch" id="batch" style="width: 100%;" onchange="seeRemaining(this.value,<?= $institute_id ?>)">
                                    <?php echo $db->MenuItemsDropdown('course_batches', "id", "batch_name", "id, batch_name", $batch, " WHERE delete_flag = 0 AND inst_id = $institute_id ORDER BY id"); ?>
                                </select>
                                <span class="help-block"><?= (isset($errors['batch'])) ? $errors['batch'] : '' ?></span>
                            </div>

                            <div class="form-group col-sm-4">
                                <label> Remaining Seats For This Batch <span class="asterisk">*</span> </label>
                                <input type="text" class="remaining form-control" name="remainingStudent" id="remainingStudent" readonly value="" />
                                <span class="help-block"><?= (isset($errors['remainingStudent'])) ? $errors['remainingStudent'] : '' ?></span>
                            </div>

                            <div class="form-group col-sm-4 <?= (isset($errors['admission_date'])) ? 'has-error' : '' ?>">
                                <label>Admission Date <span class="asterisk"></span></label>
                                <input class="form-control pull-right" name="admission_date" value="<?= isset($_POST['admission_date']) ? $_POST['admission_date'] : '' ?>" id="admission_date" type="date" autocomplete="off" max="2999-12-31">
                                <span class="help-block"><?= (isset($errors['admission_date'])) ? $errors['admission_date'] : '' ?></span>
                            </div>

                        </div>
                        <div class="row">
                            <div class="box-footer text-center">
                                <input type="submit" class="btn btn-primary" name="save_admission" value="Submit" />
                                &nbsp;&nbsp;&nbsp;
                                <a href="page.php?page=studentAdmission" class="btn btn-danger" title="Cancel">Cancel</a>
                                &nbsp;&nbsp;&nbsp;
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function minmax(value, min, max) {
        if (parseInt(value) < min || isNaN(parseInt(value)))
            return 0;
        else if (parseInt(value) > max)
            return 50;
        else {
            calPracticalResult();
            return value;
        }
    }

    //photo and signature
    function readURL1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#stud_photo')
                    .attr('src', e.target.result)
                    .width(130)
                    .height(150);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#stud_sign')
                    .attr('src', e.target.result)
                    .width(220)
                    .height(70);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function getcoursefees() {
        var instcourseid = $("#coursename").val();
        //alert(instcourseid);

        $.ajax({
            type: 'post',
            url: '/admin/include/classes/ajax.php',
            data: {
                action: 'get_inst_course_fees_enquiry',
                instcourseid: instcourseid
            },
            success: function(data) {
                //console.log(data);
                var data = JSON.parse(data);
                var courseFees = data.coursefees;
                var minAmount = data.minamount;
                var balanceFees = data.balance;
                var examFees = data.examfees;
                $("#coursefees").val(courseFees);
                $("#totalcoursefee").val(courseFees);
                $("#amtrecieved").val(minAmount);
                $("#amtbalance").val(balanceFees);
                $("#examfees").val(examFees);
            }

        });
    }
</script>