<?php
include_once('include/classes/student.class.php');
$student = new student();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';

$errors = array();
$startdate = date("Y-m-d");
$enddate = date("Y-m-d");

$student_id         = $db->test(isset($_REQUEST['student_id']) ? $_REQUEST['student_id'] : '');
$course             = $db->test(isset($_REQUEST['course']) ? $_REQUEST['course'] : '');

$startdate = $db->test(isset($_REQUEST['startdate']) ? $_REQUEST['startdate'] : $startdate);
$enddate = $db->test(isset($_REQUEST['enddate']) ? $_REQUEST['enddate'] : $enddate);

$cond = '';

if ($student_id == '') $errors['student_id'] = 'Please select student';
if ($course == '') $errors['course'] = 'Please select course';

if ($student_id != '' && $course != '') $cond .= " AND A.STUDENT_ID='$student_id' AND INSTITUTE_COURSE_ID = '$course'";
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
                            <label for="student_id">Student Name</label>
                            <?php
                            $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : $student_id;
                            ?>
                            <select class="form-control select2" name="student_id" id="student_id" onchange="getStudentAllCourses(this.value); getStudPaymentInfo(); getBalAmtCourse();">
                                <?php
                                echo $db->MenuItemsDropdown('student_details', "STUDENT_ID", "STUDENT_NAME", "STUDENT_ID, CONCAT(CONCAT(STUDENT_FNAME,' ',STUDENT_MNAME),' ',STUDENT_LNAME) STUDENT_NAME", $student_id, " WHERE DELETE_FLAG=0 AND INSTITUTE_ID = $user_id");
                                ?>
                            </select>
                            <span class="help-block"><?= isset($errors['student_id']) ? $errors['student_id'] : '' ?></span>
                        </div>

                        <div class="form-group col-md-4 <?= (isset($errors['course'])) ? 'has-error' : '' ?>">
                            <label for="course">Select Course</label>
                            <?php $course = isset($_POST['course']) ? $_POST['course'] : ''; ?>
                            <select class="form-control select2" name="course" id="course" onchange="getStudPaymentInfo(); getBalAmtCourse();">
                                <?php
                                if ($student_id != '') {
                                    $course = isset($_POST['course']) ? $_POST['course'] : '';
                                    $res = $student->get_student_allcourses($student_id);
                                    if ($res != '') {
                                        echo '<option value="">--select--</option>';
                                        while ($data = $res->fetch_assoc()) {
                                            $INSTITUTE_COURSE_ID = $data['INSTITUTE_COURSE_ID'];
                                            $output = $db->get_inst_course_info($INSTITUTE_COURSE_ID);
                                            if (!empty($output)) {
                                                print_r($output);
                                                $selected = ($course == $INSTITUTE_COURSE_ID) ? 'selected="selected"' : '';
                                                echo '<option value="' . $INSTITUTE_COURSE_ID . '" ' . $selected . '>' . $output['COURSE_NAME_MODIFY'] . '</option>';
                                            }
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <span class="help-block"><?= isset($errors['course']) ? $errors['course'] : '' ?></span>
                        </div>

                        <!-- <div class="form-group col-sm-3 <?= (isset($errors['startdate'])) ? 'has-error' : '' ?>" >
                            <label>Start Date</label>		
                            						
                            <input type="date" class="form-control" placeholder="startdate" name="startdate" value="<?php echo $startdate ?>">
                            <span class="help-block"><?= (isset($errors['startdate'])) ? $errors['startdate'] : '' ?></span>
                        </div>

                        <div class="form-group col-sm-3 <?= (isset($errors['enddate'])) ? 'has-error' : '' ?>" >
                            <label>End Date</label>		
                            						
                            <input type="date" class="form-control" placeholder="enddate" name="enddate" value="<?php echo $enddate ?>">
                            <span class="help-block"><?= (isset($errors['enddate'])) ? $errors['enddate'] : '' ?></span>
                        </div> -->

                        <div class="form-group col-sm-1">
                            <input type="submit" class="btn btn-danger btn1" value="Filter" name="search" style='border-radius:0%; position: absolute;
                             border-radius: 0%; top: 30px;' />
                        </div>

                    </div>

                    <?php
                    if ($cond != '') {
                        $sr = 1;
                        $res = $student->list_attendance('', '', $cond);
                        $res1 = $student->list_attendance('', '', $cond);
                        $res2 = $student->list_attendance('', '', $cond);
                    ?>
                        <div class="table-responsive pt-3">
                            <div>
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-tale bggrey">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="row text-white">
                                                    <?php
                                                    if ($res2 != '') {
                                                        while ($data2 = $res2->fetch_assoc()) {
                                                            extract($data2);

                                                            $course_name = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
                                                            if ($STUD_PHOTO != '') {
                                                                $STUD_PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUD_PHOTO;
                                                            } else {
                                                                $STUD_PHOTO = '/default_user.png';
                                                            }
                                                    ?>
                                                            <div class="col-md-4">
                                                                <img class="img-rounded" src="<?= $STUD_PHOTO ?>" alt="<?= $STUDENT_FULLNAME ?>" style="width: 150px !important; border-radius: 10%;">
                                                            </div>
                                                            <div class="col-md-8">
                                                                <h5 class="dashboard-text">Student Name : <br /><br /><?= $STUDENT_FULLNAME ?></h5>
                                                                <h5 class="dashboard-text">Course Name : <br /><br /><?= $course_name ?></h5>
                                                            </div>



                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr class="tableRowColor">
                                        <th>S/N</th>
                                        <th>Date</th>
                                        <th>Attendance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($res != '') {
                                        $srno = 1;
                                        while ($data = $res->fetch_assoc()) {
                                            extract($data);
                                            //print_r($data); exit();
                                            //$JOIN_DATE = $data1['JOIN_DATE'];
                                            $JOIN_DATE = date("Y-m-d", strtotime($JOIN_DATE));

                                            $sdate = strtotime($JOIN_DATE);
                                            $edate = strtotime($enddate);

                                            for ($k = $sdate; $k <= $edate; $k = $k + 86400) {
                                                $thisDate = date('d-m-Y', $k);

                                                $batch_name = '';
                                                if (!empty($BATCH_ID) && $BATCH_ID !== 0 && $BATCH_ID !== '') {
                                                    $batch_name = $db->get_batchname($BATCH_ID);
                                                }

                                                $date = date('Y-m-d', $k);
                                                $block = '';
                                                $attendancedateStatus = $db->get_attendancedateStatus($BATCH_ID, $STUDENT_ID, $INSTITUTE_COURSE_ID, $date);


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

                                                echo " <tr id='row-$thisDate'>
                                            <td>$srno
                                                <input type='hidden' name='studId[]' id='studId$STUDENT_ID' value='$STUDENT_ID'/>
                                            </td>
                                            <td>$thisDate </td>	
                                            $block
                                      
                                             </tr>";
                                                $srno++;
                                            }
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