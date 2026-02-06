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

$errors = array();
$startdate = date("Y-m-d");
$enddate = date("Y-m-d");

$batch_id         = $db->test(isset($_REQUEST['batch_id']) ? $_REQUEST['batch_id'] : '');

$startdate = $db->test(isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : $startdate);
$enddate = $db->test(isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : $enddate);

$cond = '';

if ($batch_id == '') $errors['batch_id'] = 'Please select batch';
if ($batch_id != '') $cond .= " AND B.BATCH_ID='$batch_id'";
//if($attendancedate!='') $cond  .= " AND B.date='$attendancedate'";

$action = isset($_POST['add_attendance']) ? $_POST['add_attendance'] : '';

if ($action != '') {
    //print_r($_POST); exit();
    $result = $student->add_attendance();
    $result = json_decode($result, true);
    $success = isset($result['success']) ? $result['success'] : '';
    $message = isset($result['message']) ? $result['message'] : '';
    $errors = isset($result['errors']) ? $result['errors'] : '';
    if ($success == true) {
        $_SESSION['msg'] = $message;
        $_SESSION['msg_flag'] = $success;
        //header('location:page.php?page=list-exams');
    }
}

?>

<div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Attendance Report </h4>
                <?php
                if (isset($success)) {
                ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-<?= ($success == true) ? 'success' : 'danger' ?> alert-dismissible" id="messages">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                                <h4><i class="icon fa fa-check"></i> <?= ($success == true) ? 'Success' : 'Error' ?>: <?= isset($message) ? $message : 'Please correct the errors.'; ?></h4>

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
                <form name="form1" class="forms-sample" action="" method="post" enctype="multipart/form-data">
                    <div class="row">

                        <div class="form-group col-sm-3 <?= (isset($errors['batch'])) ? 'has-error' : '' ?>">
                            <label>Select Batch</label>
                            <?php
                            $batch_id = isset($_POST['batch_id']) ? $_POST['batch_id'] : $batch_id;
                            ?>
                            <select class="form-control select2 " name="batch_id" id="batch_id" style="width: 100%;">
                                <?php echo $db->MenuItemsDropdown('course_batches', "id", "batch_name", "id, batch_name", $batch_id, " WHERE delete_flag = 0 AND inst_id = $institute_id  ORDER BY id"); ?>
                            </select>
                            <span class="help-block"><?= (isset($errors['batch_id'])) ? $errors['batch_id'] : '' ?></span>
                        </div>

                        <div class="form-group col-sm-3 <?= (isset($errors['startdate'])) ? 'has-error' : '' ?>">
                            <label>Start Date</label>

                            <input type="date" class="form-control" placeholder="startdate" name="startdate" value="<?php echo $startdate ?>">
                            <span class="help-block"><?= (isset($errors['startdate'])) ? $errors['startdate'] : '' ?></span>
                        </div>

                        <div class="form-group col-sm-3 <?= (isset($errors['enddate'])) ? 'has-error' : '' ?>">
                            <label>End Date</label>

                            <input type="date" class="form-control" placeholder="enddate" name="enddate" value="<?php echo $enddate ?>">
                            <span class="help-block"><?= (isset($errors['enddate'])) ? $errors['enddate'] : '' ?></span>
                        </div>

                        <div class="form-group col-sm-1">
                            <input type="submit" class="btn btn-danger btn1" value="Filter" name="search" style='border-radius:0%; position: absolute;
                             border-radius: 0%; top: 30px;' />
                        </div>

                    </div>

                    <?php
                    if ($cond != '') {
                        $sr = 1;
                        $res = $student->list_attendance('', '', $cond);
                    ?>
                        <div class="table-responsive pt-3">
                            <table class="table">
                                <thead>
                                    <tr class="tableRowColor">
                                        <th>S/N</th>
                                        <th>Student Name</th>
                                        <th>Course Name</th>
                                        <?php
                                        $sdate = strtotime($startdate);
                                        $edate = strtotime($enddate);
                                        for ($k = $sdate; $k <= $edate; $k = $k + 86400) {
                                            $thisDate = date('d-m-Y', $k);
                                            echo "<th>" . $thisDate . "</th>";
                                        }
                                        ?>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($res != '') {
                                        $srno = 1;
                                        while ($data = $res->fetch_assoc()) {
                                            extract($data);
                                            //print_r($data);
                                            if ($STUD_PHOTO != '') {
                                                $STUD_PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUD_PHOTO;
                                            } else {
                                                $STUD_PHOTO = '/default_user.png';
                                            }

                                            $batch_name = '';
                                            if (!empty($BATCH_ID) && $BATCH_ID !== 0 && $BATCH_ID !== '') {
                                                $batch_name = $db->get_batchname($BATCH_ID);
                                            }

                                            $block = '';
                                            for ($j = $sdate; $j <= $edate; $j = $j + 86400) {
                                                $date = date('Y-m-d', $j);
                                                $attendancedateStatus = $db->get_attendancedateStatus($BATCH_ID, $STUDENT_ID, $INSTITUTE_COURSE_ID, $date);
                                                $course_name = $db->get_inst_course_name($INSTITUTE_COURSE_ID);

                                                if ($attendancedateStatus != '') {
                                                    if ($attendancedateStatus == '1') {
                                                        $present = "<span class='text-success'>Present</span>";
                                                    }
                                                    if ($attendancedateStatus == '0') {
                                                        $present = "<span class='text-danger'>Absent</span></td>";
                                                    }
                                                } else {
                                                    $present = 'No Attendance';
                                                }

                                                $block .=  "<td>" . $present . "</td>";
                                            }

                                            echo " <tr id='row-$STUDENT_ID'>
                                                <td>$srno
                                                    <input type='hidden' name='studId[]' id='studId$STUDENT_ID' value='$STUDENT_ID'/>
                                                </td>	                                           
                                                <td>$STUDENT_FULLNAME</td>
                                                <td>$course_name</td>
                                                $block
                                          
                                            </tr>";
                                            $srno++;
                                        }
                                    }


                                    ?>
                                </tbody>
                            </table>
                        </div>

                    <?php
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function selectAll(form1) {

        var check = document.getElementsByName("group0"),
            radios = document.form1.elements;

        //If the first radio is checked
        if (check[0].checked) {

            for (i = 0; i < radios.length; i++) {

                //And the elements are radios
                if (radios[i].type == "radio") {

                    //And the radio elements's value are 1
                    if (radios[i].value == 1) {
                        //Check all radio elements with value = 1
                        radios[i].checked = true;
                    }

                } //if

            } //for

            //If the second radio is checked
        } else {

            for (i = 0; i < radios.length; i++) {

                //And the elements are radios
                if (radios[i].type == "radio") {

                    //And the radio elements's value are 0
                    if (radios[i].value == 0) {

                        //Check all radio elements with value = 0
                        radios[i].checked = true;

                    }

                } //if

            } //for

        }; //if
        return null;
    }

    function changeBatch(batch) {
        alert(batch);

    }

    function changeDate(date) {
        alert(date);

    }

    function refreshPage() {
        location.reload(true);
    }
</script>