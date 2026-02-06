 <?php
 include('include/classes/exam.class.php');
 $exam = new exam();
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

$action = isset($_POST['action'])?$_POST['action']:'';
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
 
 $res 	= $exam->list_student_exam_results('', $studid, $institute_id, '', $cond);
 ?>
 <div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
	  <div class="card">
	    <div class="card-body">
	      <h4 class="card-title">  All Exams Results
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


	                     
			<div class="row">
				<div class="col-md-12">
				<form action="" method="post" class="form-inline" onsubmit="return confirm('Confirm! Apply student for certificates?'); pageLoaderOverlay('show')">
      	
               <!--
               <div class="form-group <?= (isset($errors['examtype1']))?'has-error':'' ?>">
               <label for="exampleInputName2">Select Exam Mode:</label>
               <?php $examtype1 = isset($_POST['examtype1'])?$_POST['examtype1']:''; ?>
                <select class="form-control" name="examtype1" id="examtype">
                    <?php echo $db->MenuItemsDropdown ('exam_types_master',"EXAM_TYPE_ID","EXAM_TYPE","EXAM_TYPE_ID, EXAM_TYPE",$examtype1," WHERE ACTIVE=1 AND DELETE_FLAG=0"); ?>
               </select>
             </div>-->
             <?php if($db->permission('apply_certificate')){	?>
            <!--  <input type="submit" class="btn btn-primary" name="submit"  value="Apply For Certificate" /> -->
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
               <th>Requested Certificate</th>				
               <th>Created On</th>
               <th>Action</th>
             </thead>
			  <tbody>
						<?php		
						if($res!='')
						{
							$srno=1;
							while($data=$res->fetch_assoc())
							{
								extract($data);				
													
								if($STUDENT_PHOTO!=''){
										$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;}else{	$PHOTO = '../uploads/default_user.png';}
								$EXAM_TYPE_NAME = !empty($EXAM_TYPE_NAME)?$EXAM_TYPE_NAME:'-';
								$GRADE = !empty($GRADE)?$GRADE:'-';					
								$COURSE_NAME = $db->get_inst_course_name($INSTITUTE_COURSE_ID);
								//$action = "<!-- <a href='page.php?page=update-exam-results&id=$EXAM_RESULT_ID' class='btn' title='Edit'><i class='fa fa-pencil'></i></a> -->";
								$action="";
								if($db->permission('delete_exam_result'))
								//$action .= "<a href='javascript:void(0)' onclick='deleteStudentResult(this.id)' id='result$EXAM_RESULT_ID' class='btn' title='Delete'><i class='fa fa-trash'></i></a>";
								
								$APPLY_FOR_CERTIFICATE_LABEL = ($APPLY_FOR_CERTIFICATE==0)?'No':'Yes';
								$disableCheck = ($APPLY_FOR_CERTIFICATE==1)?'disabled':'';
								$disableCheck1 = ($PRACTICAL_MARKS=='' || $PRACTICAL_MARKS==NULL || $PRACTICAL_MARKS==0)?'disabled':'';
								$checkstud = isset($_POST['checkstud'])?$_POST['checkstud']:'';
								$checkbox="";
								if($db->permission('apply_certificate'))
									//$checkbox = "<td><input type='checkbox' name='checkstud[]' id='checkstud$EXAM_RESULT_ID' value='$EXAM_RESULT_ID' $disableCheck1 /></td>";
									//$MARKS_PER = $PRACTICAL_MARKS+$MARKS_OBTAINED;
									//	$GRADE='';
									//	$RESULT_STATUS='';

								if($APPLY_FOR_CERTIFICATE==0)
								$action .= '<a href="#"  onclick="getpopup('.$EXAM_RESULT_ID.','.$INSTITUTE_COURSE_ID.')" value="'.$EXAM_RESULT_ID.'" class="btn btn-xs btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>Marksheet</a>';
							
								
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
       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
       <h4 class="modal-title" id="myModalLabel">Add marksheet Details</h4>
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