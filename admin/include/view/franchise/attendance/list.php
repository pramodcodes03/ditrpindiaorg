<?php
include_once('include/classes/student.class.php');
$student = new student();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

$errors = array();
$attendancedate = date("Y-m-d");
$batch_id         = $db->test(isset($_REQUEST['batch_id']) ? $_REQUEST['batch_id'] : '');
$attendancedate = $db->test(isset($_REQUEST['attendancedate']) ? $_REQUEST['attendancedate'] : $attendancedate);

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
                <h4 class="card-title">Attendance Section </h4>
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

                        <div class="form-group col-sm-4 <?= (isset($errors['batch'])) ? 'has-error' : '' ?>">
                            <label>Select Batch</label>
                            <?php
                            $batch_id = isset($_POST['batch_id']) ? $_POST['batch_id'] : $batch_id;
                            ?>
                            <select class="form-control select2 " name="batch_id" id="batch_id" style="width: 100%;">
                                <?php echo $db->MenuItemsDropdown('course_batches', "id", "batch_name", "id, batch_name", $batch_id, " WHERE delete_flag = 0 AND inst_id = $user_id ORDER BY id"); ?>
                            </select>
                            <span class="help-block"><?= (isset($errors['batch_id'])) ? $errors['batch_id'] : '' ?></span>
                        </div>

                        <div class="form-group col-sm-4 <?= (isset($errors['attendancedate'])) ? 'has-error' : '' ?>">
                            <label>Select Date</label>

                            <input type="date" class="form-control" placeholder="attendancedate" name="attendancedate" value="<?php echo $attendancedate ?>">
                            <span class="help-block"><?= (isset($errors['attendancedate'])) ? $errors['attendancedate'] : '' ?></span>
                        </div>

                        <div class="form-group col-sm-1">
                            <input type="submit" class="btn btn-danger btn1" value="Load" name="search" style='border-radius:0%; position: absolute;
                             border-radius: 0%; top: 30px;' />
                        </div>

                        <div class="form-group col-sm-1">
                            <a onclick="refreshPage()" class="btn btn-warning btn1" style='border-radius:0%; position: absolute; border-radius: 0%; top: 30px;'>Refresh</a>
                        </div>
                    </div>

                    <?php
                    if ($cond != '') {
                        $sr = 1;
                        $res = $student->list_attendance('', '', $cond);
                    ?>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-success btn-block" style='border-radius:0%;                         border-radius: 0%;;' name="add_attendance" value="Save Attendance"><i class="glyphicon glyphicon-ok-sign"></i> Save Attendance
                            </button>
                        </div>

                        <div class="clearfix"></div>
                        <div class="table-responsive pt-3" style="margin-bottom:30px">
                            <table class="table">
                                <thead>
                                    <tr class="tableRowColor">
                                        <th>S/N</th>
                                        <th>Photo</th>
                                        <th>Batch</th>
                                        <th>Student Name</th>
                                        <th>Course Name</th>
                                        <th> Date </th>
                                        <th>
                                            <input type="radio" name="group0" value="1" onclick="selectAll(form1)" style='width: 20px; height: 20px;'> Present All
                                            <!-- <span class="help-block"><?= (isset($errors['batch'])) ? $errors['batch'] : '' ?></span> -->
                                        </th>
                                        <th>
                                            <input type="radio" name="group0" value="0" onclick="selectAll(form1)" style='width: 20px; height: 20px;'>Absent All
                                            <!-- <span class="help-block"><?= (isset($errors['batch'])) ? $errors['batch'] : '' ?></span> -->
                                        </th>
                                        <!-- <th> View  </th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($res != '') {
                                        $srno = 1;
                                        while ($data = $res->fetch_assoc()) {
                                            extract($data);
                                            //print_r($data); exit();
                                            if ($STUD_PHOTO != '') {
                                                $STUD_PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUD_PHOTO;
                                            } else {
                                                $STUD_PHOTO = '/default_user.png';
                                            }

                                            $batch_name = '';
                                            if (!empty($BATCH_ID) && $BATCH_ID !== 0 && $BATCH_ID !== '') {
                                                $batch_name = $db->get_batchname($BATCH_ID);
                                            }

                                            $attendancedateStatus = $db->get_attendancedateStatus($BATCH_ID, $STUDENT_ID, $INSTITUTE_COURSE_ID, $attendancedate);
                                            $course_name = $db->get_inst_course_name($INSTITUTE_COURSE_ID);

                                            $present = '';
                                            $absent = '';
                                            if ($attendancedateStatus == '1') {
                                                $present = "checked = 'checked'";
                                            }
                                            if ($attendancedateStatus == '0') {
                                                $absent = "checked = 'checked'";
                                            }
                                            $date = date("d-m-Y", strtotime($attendancedate));

                                            $checkbox = "<input class='is_present' type='checkbox' name='is_present[][]' id='checkstud$STUDENT_ID' value='[$STUDENT_ID][1]' $checked style='height: 20px; float: left; text-align: left; width: 45px;'/>";

                                            $couurseInfo = $db->get_inst_course_info($INSTITUTE_COURSE_ID);
                                            //print_r($couurseInfo); 
                                            $COURSE_ID = '';
                                            $MULTI_SUB_COURSE_ID = '';
                                            if ($couurseInfo != '') {
                                                $COURSE_ID = isset($couurseInfo['COURSE_ID']) ? $couurseInfo['COURSE_ID'] : '';
                                                $MULTI_SUB_COURSE_ID = isset($couurseInfo['MULTI_SUB_COURSE_ID']) ? $couurseInfo['MULTI_SUB_COURSE_ID'] : '';
                                            }

                                            $checkCertStatus = $db->check_certificate_applystatus($STUDENT_ID, $COURSE_ID, $MULTI_SUB_COURSE_ID);

                                            if (empty($checkCertStatus)) {
                                                echo " <tr id='row-$STUDENT_ID'>
                                                <td>$srno
                                                    <input type='hidden' name='studId[]' id='studId$STUDENT_ID' value='$STUDENT_ID'/>

                                                    <input type='hidden' name='courseId[]' id='course_id$STUDENT_ID' value='$INSTITUTE_COURSE_ID'/>
                                                </td>	
                                                <td><img src='$STUD_PHOTO' class='img img-responsive img-thumbnail' style='width:50px; height:50px; border-radius:0px'></td> 
                                                <td>$batch_name</td>
                                                <td>$STUDENT_FULLNAME</td>
                                                <td>$course_name</td>													
                                                <td>$date</td>                                               
                                                <td> <input type='radio' name='group$STUDENT_ID$INSTITUTE_COURSE_ID' value='1' class='' style='    width: 20px; height: 20px;' $present ><span style='font-size: 14px;'> Present </span></td> 

                                                <td> <input type='radio' name='group$STUDENT_ID$INSTITUTE_COURSE_ID' value='0'  $absent class='' style='width: 20px; height: 20px;'> <span style='font-size: 14px; '> Absent </span> </td> 

                                                <!-- <td> 
                                                    <a href='page.php?page=viewStudentIdcard&id=$STUDENT_ID' class='btn btn-success table-btn' title='View ID Card' target='_blank'><i class='mdi mdi-account-card-details'></i></a> 
                                                </td> -->
                                               
                                            </tr>";
                                                $srno++;
                                            }
                                        }
                                    }


                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-success btn-block" style='border-radius:0%; 
                        border-radius: 0%; top: 30px;' name="add_attendance" value="Save Attendance"><i class="glyphicon glyphicon-ok-sign"></i> Save Attendance
                            </button>
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