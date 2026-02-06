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
                        $res = $student->list_attendance('','',$cond);
                        $res1 = $student->list_attendance('','',$cond);
                      ?>           
                        <div class="table-responsive"> 
                        <table class="table">
                        <thead>
                            <tr>
                                <th>S/N</th> 
                                <th>Photo</th>                              
                                <th>Student Name</th>
                                <th>Course Name</th>
                               <?php
                                 if($res1!='')
                                 {                                  
                                     while($data1 = $res1->fetch_assoc())
                                     {
                                        $JOIN_DATE = $data1['JOIN_DATE'];
                                        $JOIN_DATE = date("Y-m-d", strtotime($JOIN_DATE));

                                        $sdate = strtotime($JOIN_DATE);
                                        $edate = strtotime($enddate);                                    
                                        for($k=$sdate;$k<=$edate;$k=$k+86400)
                                        {
                                            $thisDate = date( 'd-m-Y', $k );
                                            echo "<th>".$thisDate."</th>";
                                        }
                                     }
                                }                          
                                  
                                ?>
                            
                            </tr>
                        </thead>
                        <tbody>
                            <?php			
                                if($res!='')
                                {
                                    $srno=1;
                                    while($data = $res->fetch_assoc())
                                    {				
                                        extract($data);
                                        //print_r($data); exit();
                                        if($STUD_PHOTO!=''){
                                            $STUD_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUD_PHOTO;}else{$STUD_PHOTO = '/default_user.png';}

                                        $batch_name = '';
                                        if(!empty($BATCH_ID) && $BATCH_ID !== 0 && $BATCH_ID !== ''){
                                            $batch_name = $db->get_batchname($BATCH_ID);
                                        }

                                        $block ='';
                                            for($j=$sdate;$j<=$edate;$j=$j+86400)
                                            {
                                                $date = date('Y-m-d', $j);
                                                $attendancedateStatus = $db->get_attendancedateStatus($BATCH_ID,$STUDENT_ID,$INSTITUTE_COURSE_ID,$date); 
                                                $course_name = $db->get_inst_course_name($INSTITUTE_COURSE_ID);

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
                                              
                                                $block .=  "<td>".$present."</td>";
                                            }

                                        echo " <tr id='row-$STUDENT_ID'>
                                                <td>$srno
                                                    <input type='hidden' name='studId[]' id='studId$STUDENT_ID' value='$STUDENT_ID'/>
                                                </td>	 
                                                <td><img class='img-rounded' src='$STUD_PHOTO' alt='$STUDENT_FULLNAME' style='width:50px !important; height:50px !important; border-radius: 10%;'></td>                                          
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
                   
                