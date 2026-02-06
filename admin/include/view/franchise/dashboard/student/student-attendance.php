<?php
include_once('include/classes/student.class.php');
$student = new student();
$stud_id = $db->test(isset($_REQUEST['student_id'])?$_REQUEST['student_id']:'');
$course_id = $db->test(isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'');
$cond = '';

if($stud_id=='') $errors['stud_id'] = 'Please select student';	
if($stud_id!='') $cond .= " AND A.STUDENT_ID='$stud_id'";
$startdate = date("Y-m-d"); 
$enddate = date("Y-m-d");
?>

          
                      <?php 
                        if($cond != ''){
                        $sr=1; 
                        $res1 = $student->list_attendance('','',$cond);
                         	
                                if($res1!='')
                                 {                                  
                                     while($data1 = $res1->fetch_assoc())
                                     {
                                        extract($data1);
                                        $course_name = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
                                         
                                        $JOIN_DATE = date("Y-m-d", strtotime($JOIN_DATE));

                                        $sdate = strtotime($JOIN_DATE);
                                        $edate = strtotime($enddate);     
                        
                      ?>           
                        
                        <table id="order-listing" class="table dataTable no-footer">
                        <thead>
                            <tr>
                                <?php  echo "<th>Course Name : $course_name </th> <th></th>"; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php			
                            
                                   
                                        for($j=$sdate;$j<=$edate;$j=$j+86400)
                                        {
                                            $date = date('Y-m-d', $j);
                                            $attendancedateStatus = $db->get_attendancedateStatus($BATCH_ID,$STUDENT_ID,$INSTITUTE_COURSE_ID,$date); 
                                            

                                            if($attendancedateStatus !=''){
                                                if($attendancedateStatus == '1'){
                                                    $present = "<span class='text-success'>Present</span>";
                                                }
                                                if($attendancedateStatus == '0'){
                                                    $present = "<span class='text-danger'>Absent</span></td>";
                                                } 
                                            }else{
                                                $present = 'No Attendance';
                                            }   
                                         
                                         $thisDate = date( 'd-m-Y', $j );

                                    echo " <tr>
                                            <td>$thisDate</td>
                                            <td>$present</td>
                                      
                                        </tr>";						
                                   
                                        }
                                }
                            }
                       
                                
                        ?>                      
                        </tbody>
                        </table>
                        
                          <?php  $sr++;
                                    }
                                
                                ?> 
              
                   
                   
                