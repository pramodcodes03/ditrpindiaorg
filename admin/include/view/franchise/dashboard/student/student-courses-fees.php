<?php
$stud_id = $db->test(isset($_REQUEST['student_id']) ? $_REQUEST['student_id'] : '');
$course_id = $db->test(isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '');
$cond = '';
if ($cond != '') {
    $cond = " AND A.INSTITUTE_COURSE_ID='$course_id' ";
}

$output = '';
include_once('institute.class.php');
$institute = new institute();
$payments = $institute->get_stud_course_payment_total($stud_id, $course_id, 0);
//$payments = $institute->list_student_payments('',$stud_id,'', '', $cond);
if ($payments != '') {
    $output = '
    <table class="table table-bordered">
                <thead>
                    <tr>									
                        <th>Date</th>					
                        <th>Course Name</th>					
                        <th>Total Course Fees</th>
                        <th>Fees Paid</th>
                        <th>Fees Balance</th>
                        <!-- <th>Action</th> -->									
                    </tr>
                </thead><tbody>';
    while ($data = $payments->fetch_assoc()) {
        extract($data);
        $COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
        //$FEES_BALANCE = floatval($TOTAL_COURSE_FEES) - floatval($FEES_PAID);
        $action = '';
        if ($db->permission('update_payment'))
            $action = "<a href='page.php?page=studentUpdateFees&payid=$PAYMENT_ID' class='btn btn-primary table-btn' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";

        if ($db->permission('print_payment_reciept'))
            $action .= "<a href='page.php?page=viewStudentReceipt&payid=$PAYMENT_ID' target='_blank' class='btn btn-primary table-btn' title='Print Reciept'><i class='mdi mdi-file-pdf'></i></a>";

        $output .= "<tr>
                        <td>$FEES_PAID_ON</td>	
                        <td>$COURSE_NAME</td>	
                        <td>$TOTAL_COURSE_FEES</td>	
                        <td>$FEES_PAID</td>											  	
                        <td>$FEES_BALANCE</td>	
                        <!-- <td>$action</td>	 -->									 
                        </tr>";
    }
    $output .= '</tbody></table>';
}
echo $output;
