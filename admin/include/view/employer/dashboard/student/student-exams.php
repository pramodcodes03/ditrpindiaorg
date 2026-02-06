<?php
include('include/classes/exam.class.php');
$exam = new exam();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
if ($user_role == 5) {
    $institute_id = $db->get_parent_id($user_role, $user_id);
    $staff_id = $user_id;
} else {
    $institute_id = $user_id;
    $staff_id = 0;
}



/* display exam results details */
$studid         = $db->test(isset($_REQUEST['student_id']) ? $_REQUEST['student_id'] : '');
$examtitle         = $db->test(isset($_REQUEST['examtitle']) ? $_REQUEST['examtitle'] : '');
$resultstatus     = $db->test(isset($_REQUEST['resultstatus']) ? $_REQUEST['resultstatus'] : '');
$examtype         = $db->test(isset($_REQUEST['examtype']) ? $_REQUEST['examtype'] : '');
$cond = '';
if ($resultstatus != '') $cond .= " AND A.RESULT_STATUS='$resultstatus'";
if ($examtype != '') $cond .= " AND A.EXAM_TYPE='$examtype'";
if ($examtitle != '') $cond .= " AND A.EXAM_TITLE='$examtitle'";

$res     = $exam->list_student_exam_results('', $studid, $institute_id, '', $cond);
?>
<table class="table  table-bordered  table-responsive">
    <thead>
        <th>#</th>
        <th>Course</th>
        <th>Exam Mode</th>
        <th>Objective Marks</th>
        <th>Practical Marks</th>
        <th>Percentage</th>
        <th>Grade</th>
        <th>Result</th>
        <th>Requested Certificate</th>
        <th>Created On</th>
        <th>Action</th>
    </thead>
    <tbody>
        <?php
        if ($res != '') {
            $srno = 1;
            while ($data = $res->fetch_assoc()) {
                extract($data);

                if ($STUDENT_PHOTO != '') {
                    $PHOTO = STUDENT_DOCUMENTS_PATH . '/' . $STUDENT_ID . '/' . $STUDENT_PHOTO;
                } else {
                    $PHOTO = '../uploads/default_user.png';
                }
                $EXAM_TYPE_NAME = !empty($EXAM_TYPE_NAME) ? $EXAM_TYPE_NAME : '-';
                $GRADE = !empty($GRADE) ? $GRADE : '-';
                $COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
                //$action = "<!-- <a href='update-exam-results&id=$EXAM_RESULT_ID' class='btn' title='Edit'><i class='fa fa-pencil'></i></a> -->";
                $action = "";
                if ($db->permission('delete_exam_result'))
                    //$action .= "<a href='javascript:void(0)' onclick='deleteStudentResult(this.id)' id='result$EXAM_RESULT_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";

                    $APPLY_FOR_CERTIFICATE_LABEL = ($APPLY_FOR_CERTIFICATE == 0) ? 'No' : 'Yes';
                $disableCheck = ($APPLY_FOR_CERTIFICATE == 1) ? 'disabled' : '';
                $disableCheck1 = ($PRACTICAL_MARKS == '' || $PRACTICAL_MARKS == NULL || $PRACTICAL_MARKS == 0) ? 'disabled' : '';
                $checkstud = isset($_POST['checkstud']) ? $_POST['checkstud'] : '';
                $checkbox = "";
                if ($db->permission('apply_certificate'))
                    //$checkbox = "<td><input type='checkbox' name='checkstud[]' id='checkstud$EXAM_RESULT_ID' value='$EXAM_RESULT_ID' $disableCheck1 /></td>";
                    //$MARKS_PER = $PRACTICAL_MARKS+$MARKS_OBTAINED;
                    //	$GRADE='';
                    //	$RESULT_STATUS='';

                    if ($MARKS_PER >= 85) {
                        $GRADE = "A+";
                        $RESULT_STATUS = "Passed";
                    } else if ($MARKS_PER    >= 70 && $MARKS_PER < 85) {
                        $GRADE = "A";
                        $RESULT_STATUS = "Passed";
                    } else if ($MARKS_PER >= 55 && $MARKS_PER < 70) {
                        $GRADE = "B";
                        $RESULT_STATUS = "Passed";
                    } else if ($MARKS_PER >= 40 && $MARKS_PER < 55) {
                        $GRADE = "C";
                        $RESULT_STATUS = "Passed";
                    } else {
                        $GRADE = "";
                        $RESULT_STATUS = "Failed";
                    }
                echo "<tr id='row-result$EXAM_RESULT_ID'>
                        <td>$srno</td>
                        <td>$COURSE_NAME</td>							
                        <td>$EXAM_TYPE_NAME</td>	
                        <td>$MARKS_OBTAINED</td>
                        <td>$PRACTICAL_MARKS</td>
                        <td>$MARKS_PER</td>
                        <td>$GRADE</td>
                        <td>$RESULT_STATUS</td>													
                        <td>$APPLY_FOR_CERTIFICATE_LABEL</td>													
                        <td>$CREATED_DATE</td>
                        <td>$action</td>
                        </tr>
                        ";

                $srno++;
            }
        }

        ?>
    </tbody>
</table>