<?php
$stud_id = $db->test(isset($_REQUEST['student_id'])?$_REQUEST['student_id']:'');
$course_id = $db->test(isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'');
$cond = '';
if($cond!='')
{
    $cond = " AND A.INSTITUTE_COURSE_ID='$course_id' ";
}

$output = '';
include_once('student.class.php');
$student = new student();
$payments = $student->list_student_courses('',$stud_id,'');
//$payments = $institute->list_student_payments('',$stud_id,'', '', $cond);
if($payments!='')
{
    $output = '
    <table class="table table-bordered">
                <thead>
                    <tr>									
                        <th>Date</th>					
                        <th>Course Name</th>					
                        <th>Course Fees</th>                       								
                    </tr>
                </thead><tbody>';
    while($data = $payments->fetch_assoc())
    {
        extract($data);
        //print_r($data); exit();
        $course_name = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
        $date = date("d-m-Y",strtotime($CREATED_ON));
     
        $output .= "<tr>
                        <td>$date</td>	
                        <td>$course_name</td>	
                        <td>$TOTAL_COURSE_FEES</td>	                       						 
                        </tr>";
    }
    $output .= '</tbody></table>';
}
echo $output;
?>