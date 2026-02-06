<?php
 include('include/classes/exam.class.php');
 $exam = new exam();
 include('include/classes/exammultisub.class.php');
 $exammultisub = new exammultisub();
 include('include/classes/coursetypingexam.class.php');
 $coursetypingexam = new coursetypingexam();

$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';

if($user_role==5){

   $institute_id = $db->get_parent_id($user_role,$user_id);

   $staff_id = $user_id;

}

else{

   $institute_id = $user_id;

   $staff_id = 0;

}


/* apply for certificates */
$action = isset($_POST['action'])?$_POST['action']:'';
$checkstud = isset($_POST['checkstud'])?$_POST['checkstud']:'';
$checkstud_multisub = isset($_POST['checkstud_multisub'])?$_POST['checkstud_multisub']:'';
$checkstud_typing = isset($_POST['checkstud_typing'])?$_POST['checkstud_typing']:'';
if($action=='applyforcertificate')
{
	//$result= $exam->apply_for_certificate();
	$result= $exam->apply_for_certificate_wihtout_pay();
	$result = json_decode($result, true);
	$success = isset($result['success'])?$result['success']:'';
	$message = isset($result['message'])?$result['message']:'';
	$errors = isset($result['errors'])?$result['errors']:'';
	//print_r($result);
	if($success==true)
	{
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
//require_once(ROOT."/include/email/config.php");						
		//require_once(ROOT."/include/email/templates/certificate_request_by_admin_to_inst.php");
		//header('location:page.php?page=list-exams');
	}
	
}

if($action=='add_marksheet')
{
$result= $exam->add_marksheet();
//print_r($result);exit();

	$result = json_decode($result, true);

	$success = isset($result['success'])?$result['success']:'';

	$message = isset($result['message'])?$result['message']:'';

	$errors = isset($result['errors'])?$result['errors']:'';


}

if($action=='update_marksheet')
{
$result= $exam->update_marksheet();
//print_r($result);exit();

	$result = json_decode($result, true);

	$success = isset($result['success'])?$result['success']:'';

	$message = isset($result['message'])?$result['message']:'';

	$errors = isset($result['errors'])?$result['errors']:'';


}

/* display exam results details */
 $studid 		= $db->test(isset($_REQUEST['studid'])?$_REQUEST['studid']:'');
 $examtitle	 	= $db->test(isset($_REQUEST['examtitle'])?$_REQUEST['examtitle']:'');
 $resultstatus 	= $db->test(isset($_REQUEST['resultstatus'])?$_REQUEST['resultstatus']:'');
 $examtype 		= $db->test(isset($_REQUEST['examtype'])?$_REQUEST['examtype']:'');
 $cond = '';
 if($resultstatus!='') $cond .= " AND A.RESULT_STATUS='$resultstatus'";
 if($examtype!='') $cond .= " AND A.EXAM_TYPE='$examtype'";
 if($examtitle!='') $cond .= " AND A.EXAM_TITLE='$examtitle'";
 
 $res 	= $exam->list_student_exam_results('', $studid,$institute_id, '', $cond);

 $exam_result_info_multi_sub = $exammultisub->list_student_exam_results_multi_sub('',$studid,$institute_id,'', $cond);

 
 $exam_result_typing = $coursetypingexam->list_student_exam_results_typing('',$studid,$institute_id,'', $cond);

 ?>

<div class="content-wrapper">
    <div class="col-lg-12 stretch-card">
		<div class="card">
		<div class="card-body">
			<h4 class="card-title">All Exams Results
			</h4> 
			<?php
			if(isset($success))
			{
			?>
			<div class="row">
			<div class="col-sm-12">
			<div class="alert alert-<?= ($success==true)?'success':'danger' ?> alert-dismissible" id="messages">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                <h4><i class="icon fa fa-check"></i> <?= ($success==true)?'Success':'Error' ?>:</h4>
				<?= isset($message)?$message:'Please correct the errors.'; ?>
            </div>
			 </div>
			 </div>
			<?php
			}
			?>
			<form action="" method="post" class="form-inline" onsubmit="return confirm('Confirm! Apply for certificates ?'); pageLoaderOverlay('show')">
			<p style="color:red; padding:5px; background-color:yellow; font-weight:900; font-size:14px;"> 
				Before Applying For Approval Certificate Please Ensure That You Can Enter Both Objective Marks And Practical Marks. After Approve Certificate You Are Not Able To Update Marks.
			</p>
			<div class="box-header" style="margin:10px 0px">	
				<?php if($db->permission('apply_certificate')){	?>
				<input type="submit" class="btn btn-primary" name="submit"  value="Apply For Certificate" />
				<?php } ?>
			   <input type="hidden" name="action" value="applyforcertificate">
				<input type="hidden" name="examstatus1" value="2">
				<input type="hidden" name="institute_id" value="<?= $institute_id ?>">
			   <input type="hidden" name="user_role" value="<?= 2 ?>">
				<div class="clearfix"></div> 	
			</div>
							
			<div class="table-responsive pt-3">
			<table id="order-listing" class="table">
				<thead>
				<tr>				
					<?php  if($db->permission('apply_certificate')){ ?>	<th><input type="checkbox" name="selectall" id="selectall" /></th><?php  } ?>
					<th>#</th>
					<th>Photo</th>
					<th>Student</th>
					<th>Course</th>				
					<th>Exam Mode</th>	
					<th>Objective Marks</th>			
					<th>Practical Marks</th>			
					<th>Percentage</th>				
					<th>Grade</th>				
					<th>Result</th>				
				
					<th>Student Exam Date</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
				<?php		
					if($res!='')
					{
						$srno=1;
						while($data=$res->fetch_assoc())
						{
							extract($data);	
							
						//	print_r($data); exit();
							/*$PHOTO = '../uploads/default_user.png';*/	
						
							if($STUDENT_PHOTO!=''){
								$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;}else{	$PHOTO = '../uploads/default_user.png';}
							$EXAM_TYPE_NAME = !empty($EXAM_TYPE_NAME)?$EXAM_TYPE_NAME:'-';
							$GRADE = !empty($GRADE)?$GRADE:'-';					
							$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
							//$action = "<!-- <a href='page.php?page=update-exam-results&id=$EXAM_RESULT_ID' class='btn' title='Edit'><i class='fa fa-pencil'></i></a> -->";
							$action="";
							if($db->permission('delete_exam_result'))
							//$action .= "<a href='javascript:void(0)' onclick='deleteStudentResult(this.id)' id='result$EXAM_RESULT_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";
							if($APPLY_FOR_CERTIFICATE==0)
						    $action .= '<a href="#"  onclick="getpopup('.$EXAM_RESULT_ID.','.$INSTITUTE_COURSE_ID.')" value="'.$EXAM_RESULT_ID.'" class="btn btn-xs btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>Marksheet</a>';
							
							
							$APPLY_FOR_CERTIFICATE_LABEL = ($APPLY_FOR_CERTIFICATE==0)?'No':'Yes';
							$disableCheck = ($APPLY_FOR_CERTIFICATE==1)?'disabled':'';
							$disableCheck1 = ($APPLY_FOR_CERTIFICATE==0 && ($PRACTICAL_MARKS=='' || $PRACTICAL_MARKS==NULL || $PRACTICAL_MARKS==0))?'disabled':'';

							$checkstud = isset($_POST['checkstud'])?$_POST['checkstud']:'';
							$checkbox="<td>";
							if($db->permission('apply_certificate') && $APPLY_FOR_CERTIFICATE==0)
								$checkbox .= "<input type='checkbox' name='checkstud[]' id='checkstud$EXAM_RESULT_ID' value='$EXAM_RESULT_ID' $disableCheck1 />";
							$checkbox .="</td>";


						// echo  $CREATED_DATE 	= date('d-m-Y',strtotime($CREATED_DATE)); exit();
							

							if($RESULT_STATUS=='Passed'){


							echo "<tr id='row-result$EXAM_RESULT_ID'>
									$checkbox
									<td>$srno</td>
									<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>
									<td>$STUDENT_NAME</td>
									<td>$COURSE_NAME</td>							
									<td>$EXAM_TYPE_NAME</td>
									<td>$MARKS_OBTAINED</td>
									<td>$PRACTICAL_MARKS</td>
									<td>$MARKS_PER</td>
									<td>$GRADE</td>
									<td>$RESULT_STATUS</td>													
									

									<td>$CREATED_DATE</td>
									<td>$action</td>
								
									</tr>
									";


							}
							$srno++;
							
						}			
					}
					if($exam_result_info_multi_sub!='')
					{
						$srno=1;
						while($data=$exam_result_info_multi_sub->fetch_assoc())
						{
							extract($data);	
							//print_r($data);			
						/*	$PHOTO = SHOW_IMG_AWS.'default_user.png';*/					
							if($STUDENT_PHOTO!=''){
									$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;}else{	$PHOTO = '../uploads/default_user.png';}
							
							$GRADE = !empty($GRADE)?$GRADE:'-';					
							$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
							//$action = "<!-- <a href='page.php?page=update-exam-results&id=$EXAM_RESULT_FINAL_ID' class='btn' title='Edit'><i class='fa fa-pencil'></i></a> -->";
							$action="";
							if($db->permission('delete_exam_result'))
							$action .= "<a href='javascript:void(0)' onclick='deleteStudentResult_MultiSub(this.id)' id='result$EXAM_RESULT_FINAL_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";

							if($APPLY_FOR_CERTIFICATE==0)
							$APPLY_FOR_CERTIFICATE_LABEL = ($APPLY_FOR_CERTIFICATE==0)?'No':'Yes';
							$disableCheck = ($APPLY_FOR_CERTIFICATE==1)?'disabled':'';		

							$checkstud_multisub = isset($_POST['checkstud_multisub'])?$_POST['checkstud_multisub']:'';
							$checkbox1="<td>";
							if($db->permission('apply_certificate') && $APPLY_FOR_CERTIFICATE==0)
								$checkbox1 .= "<input type='checkbox' name='checkstud_multisub[]' id='checkstud_multisub$EXAM_RESULT_FINAL_ID' value='$EXAM_RESULT_FINAL_ID' $disableCheck />";
							$checkbox1 .="</td>";


							if($RESULT_STATUS=='Passed'){

							echo "<tr id='row-result$EXAM_RESULT_FINAL_ID'>
									$checkbox1
									<td>$srno</td>
									<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>
									<td>$STUDENT_NAME</td>
									<td>$COURSE_NAME</td>							
									<td></td>
									<td>$MARKS_OBTAINED / $EXAM_TOTAL_MARKS</td>
									<td><table class='table table-bordered'>
													<tr>
														<th>Subject Name </th>
														<th>Theory Marks</th>
														<th>Practical Marks </th>	
													</tr>";
							$res2 = $exammultisub->list_student_exam_results_multi_sub_list('',$STUDENT_ID,$INSTITUTE_ID,$STUD_COURSE_ID,'');
							$resultInfo = ''; 
							if($res2!='')
							{	
							$srno1=1;
								
							while($data2 = $res2->fetch_assoc())
							{
								//print_r($data2);
								$EXAM_RESULT_ID1 		= $data2['EXAM_RESULT_ID'];
								$STUDENT_SUBJECT_ID1	= $data2['STUDENT_SUBJECT_ID'];
								$EXAM_ID1 				= $data2['EXAM_ID'];
								$INSTITUTE_COURSE_ID1 	= $data2['INSTITUTE_COURSE_ID'];
								$SUBJECT_NAME1 			= $data2['SUBJECT_NAME'];
								$EXAM_TITLE1 			= $data2['EXAM_TITLE'];
								$MARKS_OBTAINED1 		= $data2['MARKS_OBTAINED'];
								$PRACTICAL_MARKS1 		= $data2['PRACTICAL_MARKS'];
								$TOTAL_MARKS1 			= $data2['TOTAL_MARKS'];
						
								echo $resultInfo 	= '<tr>
														<td> '.$SUBJECT_NAME1.'</td>
														<td> '.$MARKS_OBTAINED1.' </td>
														<td> '.$PRACTICAL_MARKS1.' </td>
													</tr>	';	
									$srno1++;				
							
							}	
								
							}										

										
									echo "</table></td>
									<td>$MARKS_PER</td>
									<td>$GRADE</td>
									<td>$RESULT_STATUS</td>	

									<td>$CREATED_DATE</td>
									<td></td>
								
									</tr>
									";




							}
							$srno++;
							
						}			
					}
					if($exam_result_typing!='')
					{
						$srno=1;
						while($data=$exam_result_typing->fetch_assoc())
						{
							extract($data);	
							//print_r($data);			
						/*	$PHOTO = SHOW_IMG_AWS.'default_user.png';*/					
							if($STUDENT_PHOTO!=''){
									$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;}else{	$PHOTO = '../uploads/default_user.png';}
							
							$GRADE = !empty($GRADE)?$GRADE:'-';					
							$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
							//$action = "<!-- <a href='page.php?page=update-exam-results&id=$EXAM_RESULT_FINAL_ID' class='btn' title='Edit'><i class='fa fa-pencil'></i></a> -->";
							$action="";
							if($db->permission('delete_exam_result'))
							$action .= "<a href='javascript:void(0)' onclick='deleteStudentResult_MultiSub(this.id)' id='result$EXAM_RESULT_FINAL_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";

							if($APPLY_FOR_CERTIFICATE==0)
							$APPLY_FOR_CERTIFICATE_LABEL = ($APPLY_FOR_CERTIFICATE==0)?'No':'Yes';
							$disableCheck = ($APPLY_FOR_CERTIFICATE==1)?'disabled':'';		

							$checkstud_typing = isset($_POST['checkstud_typing'])?$_POST['checkstud_typing']:'';
							$checkbox1="<td>";
							if($db->permission('apply_certificate') && $APPLY_FOR_CERTIFICATE==0)
								$checkbox1 .= "<input type='checkbox' name='checkstud_typing[]' id='checkstud_typing$EXAM_RESULT_FINAL_ID' value='$EXAM_RESULT_FINAL_ID' $disableCheck />";
							$checkbox1 .="</td>";


							if($RESULT_STATUS=='Passed'){

							echo "<tr id='row-result$EXAM_RESULT_FINAL_ID'>
									$checkbox1
									<td>$srno</td>
									<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>
									<td>$STUDENT_NAME</td>
									<td>$COURSE_NAME</td>							
									<td> OFFLINE </td>
									<td>$MARKS_OBTAINED / $EXAM_TOTAL_MARKS</td>
									<td><table class='table table-bordered'>
													<tr>
														<th>Subject Name </th>
														<th>Speed ( WPM) </th>
														<th>Marks </th>	
													</tr>";
							$res2 = $coursetypingexam->list_student_exam_results_typing_list('',$STUDENT_ID,$INSTITUTE_ID,$STUD_COURSE_ID,'');
							$resultInfo = ''; 
							if($res2!='')
							{	
							$srno1=1;
								
							while($data2 = $res2->fetch_assoc())
							{
								//print_r($data2);
								$EXAM_RESULT_ID1 		= $data2['EXAM_RESULT_ID'];
								$STUDENT_SUBJECT_ID1	= $data2['STUDENT_SUBJECT_ID'];
								$EXAM_ID1 				= $data2['EXAM_ID'];
								$INSTITUTE_COURSE_ID1 	= $data2['INSTITUTE_COURSE_ID'];
								$SUBJECT_NAME1 			= $data2['SUBJECT_NAME'];
								$TYPING_COURSE_SPEED 	= $data2['TYPING_COURSE_SPEED'];
								$EXAM_TITLE1 			= $data2['EXAM_TITLE'];
								$MARKS_OBTAINED1 		= $data2['MARKS_OBTAINED'];
								$PRACTICAL_MARKS1 		= $data2['PRACTICAL_MARKS'];
								$TOTAL_MARKS1 			= $data2['TOTAL_MARKS'];
						
								echo $resultInfo 	= '<tr>
														<td> '.$SUBJECT_NAME1.'</td>
														<td> '.$TYPING_COURSE_SPEED.' </td>
														<td> '.$MARKS_OBTAINED1.' </td>
													</tr>	';	
									$srno1++;				
							
							}	
								
							}										

										
									echo "</table></td>
									<td>$MARKS_PER</td>
									<td>$GRADE</td>
									<td>$RESULT_STATUS</td>	

									<td>$CREATED_DATE</td>
									<td></td>
								
									</tr>
									";




							}
							$srno++;
							
						}			
					}
					
				?>                            
				</tbody>
			</table>
			</div>
			</form>
		</div>
		</div>
	</div>
</div>

  
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      
        <h4 class="modal-title" id="myModalLabel">Add marksheet Details</h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form class="form-horizontal form-validate" id="frmmarksheet" action="" method="post">  
      	<input type="hidden" name="exam_id"  id= "exam_id" value="">    
      	<input type="hidden" name="institute_id" id="institute_id" value="">    
      	<input type="hidden" name="student_id" id="student_id" value="">
      	<input type="hidden" name="inst_course_id" id="inst_course_id" value="">

      	<input type="hidden" name="certificate_requests_id" id="certificate_requests_id" value="">     
      	<input type="hidden" name="action" value="get_marksheet_detail">    
      	  <div class="modal-body">
      	<div>
      	 <label for="Subject" class="col-xs-3 control-label" style="text-align:left;">Subject</label>
      <textarea rows="4" cols="30" class="form-control" id="subject" name="subject" placeholder="Add Marksheet Subject here">
      	
</textarea>
<p>(Please Enter Subjects)</p>
</div>
      
      <div>	

      	 <label for="Subject" class="col-xs-3 control-label" style="text-align:left;">Pactical Marks  </label>
      	 <input type="text" name="marks" id="marks"class="form-control" onkeyup="this.value = minmax(this.value, 0, 50)" placeholder="Add practical Marks here">
      	 <p>(Out Of 50 Marks)</p>
      </div>
       <div>

      	 <label for="Subject" class="col-xs-3 control-label" style="text-align:left;">Objective Marks  </label>
      	 <input type="text" name="marksobj" id="marksobj"class="form-control" onkeyup="this.value = minmax(this.value, 0, 50)" placeholder="Add Objective Marks here">
      	 <p>(Out Of 50 Marks)</p>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" value="add_marksheet" name="action">Save changes</button>

      </div>
    
</form>
  </div>
</div>
</div>

<script type="text/javascript">
  	
  	function minmax(value, min, max) 
{
    if(parseInt(value) < min || isNaN(parseInt(value))) 
        return 0; 
    else if(parseInt(value) > max) 
        return 50; 
    else{ return value;}
}
  </script>