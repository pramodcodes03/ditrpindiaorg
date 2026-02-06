 <div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">List Exams For Courses With Multiple Subjects
				  	<a href="page.php?page=addExamMultiSub" class="btn btn-primary" style="float: right">Add Exam</a>
				  </h4> 
				  <?php

        				if(isset($_SESSION['msg']))
        
        				{
        
        					$message = isset($_SESSION['msg'])?$_SESSION['msg']:'';
        
        					$msg_flag =$_SESSION['msg_flag'];
        
        				?>
        
        				<div class="row">
        
        				<div class="col-sm-12">
        
        				<div class="alert alert-<?= ($msg_flag==true)?'success':'danger' ?> alert-dismissible" id="messages">
        
        					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
        
        					<h4><i class="icon fa fa-check"></i> <?= ($msg_flag==true)?'Success':'Error' ?>:</h4>
        
        					<?= ($message!='')?$message:'Sorry! Something went wrong!'; ?>
        
        				</div>
        
        				</div>
        
        				</div>
        
        				<?php
        
        				unset($_SESSION['msg']);
        
        				unset($_SESSION['msg_flag']);
        
        				}
        
        			?>
                                 
                  <div class="table-responsive pt-3">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
							<th>Sr.</th>
							<th>Course Code</th> 
							<th>Course Name</th>   
							<th>Subject Name</th>                
							<th>Total Marks</th>  
							<th>Total Questions</th>  
							<th>Marks Per Question</th>  
							<th>Passing Marks</th>  
							<th>Exam Time</th>  
							<th>Display Result</th>
							<th>Demos</th>	
							<th>Status</th>
							<th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
							include_once('include/classes/exammultisub.class.php');
							$exammultisub = new exammultisub();
							$res = $exammultisub->list_exams_multi_sub('','');
							if($res!='')
							{
								$srno=1;
								while($data = $res->fetch_assoc())
								{
									$EXAM_ID 		= $data['EXAM_ID'];
									$MULTI_SUB_COURSE_ID= $data['MULTI_SUB_COURSE_ID'];
									$MULTI_SUB_COURSE_CODE 	= $data['MULTI_SUB_COURSE_CODE'];
									$COURSE_NAME_MODIFY= $data['COURSE_NAME_MODIFY'];
									$COURSE_SUBJECT_ID= $data['COURSE_SUBJECT_ID'];
									$SUBJECT_NAME_MODIFY= $data['SUBJECT_NAME_MODIFY'];
									$TOTAL_MARKS 	= $data['TOTAL_MARKS'];
									$TOTAL_QUESTIONS= $data['TOTAL_QUESTIONS'];
									$MARKS_PER_QUE 	= $data['MARKS_PER_QUE'];
									$PASSING_MARKS 	= $data['PASSING_MARKS'];
									$EXAM_TIME	 	= $data['EXAM_TIME'];
									///$EXAM_TITLE		= $data['EXAM_TITLE'];
									$SHOW_RESULT 	= $data['SHOW_RESULT'];
									$DEMO_TEST 		= $data['DEMO_TEST'];
									$ACTIVE			= $data['ACTIVE'];
									$CREATED_BY 	= $data['CREATED_BY'];
									$CREATED_ON 	= $data['CREATED_ON'];
									$rowclass		= ($ACTIVE==0)?'class="danger"':'';
									
									if($db->permission('update_exam')){
										if($ACTIVE==1)
										$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeExamStatusMultiSub('.$EXAM_ID.',0)"><i class="mdi mdi-check"></i>Active</a>';
										elseif($ACTIVE==0)	
										$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeExamStatusMultiSub('.$EXAM_ID.',1)"><i class="fa fa-times"></i>In-Active</a> ';	
										
										$SHOW_RESULT 	= ($SHOW_RESULT==1)?'<a href="javascript:void(0)" onclick="changeExamDispResultFlagMultiSub('.$EXAM_ID.',0)"><i class="mdi mdi-check"></i></a>':'<a href="javascript:void(0)" onclick="changeExamDispResultFlagMultiSub('.$EXAM_ID.',1)"><i class="mdi mdi-close"></i></a>';
									
										$DEMO_TEST 		= ($DEMO_TEST==1)?'<a href="javascript:void(0)" onclick="changeExamDemoFlagMultiSub('.$EXAM_ID.',0)"><i class="mdi mdi-check"></i></a>':'<a href="javascript:void(0)" onclick="changeExamDemoFlagMultiSub('.$EXAM_ID.',1)"><i class="mdi mdi-close"></i></a>';
										
									}else{
										if($ACTIVE==1)
										$ACTIVE = '<span style="color:#3c763d"><i class="mdi mdi-check"></i>Active</span>';
										elseif($ACTIVE==0)	
										$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';	
										
										$SHOW_RESULT 	= ($SHOW_RESULT==1)?'<span><i class="mdi mdi-check"></i></span>':'<span><i class="mdi mdi-close"></i></span>';
										
										$DEMO_TEST 		= ($DEMO_TEST==1)?'<span><i class="mdi mdi-check"></i></span>':'<span><i class="mdi mdi-close"></i></span>';						
									}
									
									$action = "";
									
									if($db->permission('update_exam'))
									$action .= "<a href='page.php?page=updateExamMultiSub&id=$EXAM_ID' class='btn btn-primary table-btn ' title='Edit'><i class='mdi mdi-grease-pencil'></i></a>";
									if($db->permission('delete_exam'))
									$action .= "<a href='javascript:void(0)' onclick='deleteExamMultiSub($EXAM_ID)' class='btn btn-danger table-btn ' title='Delete'><i class='mdi mdi-delete'></i></a>
									";
									
									echo " <tr id='exam-id".$EXAM_ID."'>
											<td>$srno</td>
											<td>$MULTI_SUB_COURSE_CODE</td>							
											<td>$COURSE_NAME_MODIFY</td>
											<td>$SUBJECT_NAME_MODIFY</td>						
											<td>$TOTAL_MARKS</td>
											<td>$TOTAL_QUESTIONS</td>
											<td>$MARKS_PER_QUE</td>
											<td>$PASSING_MARKS</td>
											<td>$EXAM_TIME</td>
											<td id='disp-result-".$EXAM_ID."'>$SHOW_RESULT</td>
											<td id='demo-".$EXAM_ID."'>$DEMO_TEST</td>
											<td id='status-".$EXAM_ID."'>$ACTIVE</td>
											<td>$action</td>
				                           </tr>";
									$srno++;
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