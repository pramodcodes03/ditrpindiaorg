 <?php
 include('include/classes/exam.class.php');
 $exam = new exam();
$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  
$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';
if($user_role==3){
   $institute_id = $db->get_parent_id($user_role,$user_id);
   $staff_id = $user_id;
}
else{
   $institute_id = $user_id;
   $staff_id = 0;
}
 $studid 		= $db->test(isset($_REQUEST['studid'])?$_REQUEST['studid']:'');
 $courseid	 	= $db->test(isset($_REQUEST['courseid'])?$_REQUEST['courseid']:'');
 $coursetype 	= $db->test(isset($_REQUEST['coursetype'])?$_REQUEST['coursetype']:'');
 $examstatus 	= $db->test(isset($_REQUEST['examstatus'])?$_REQUEST['examstatus']:'');
 $examtype 		= $db->test(isset($_REQUEST['examtype'])?$_REQUEST['examtype']:'');
 
 $res 			= $exam->filter_aicpe_exams($studid,$institute_id, $courseid,$examtype, $examstatus);

$action = isset($_POST['action'])?$_POST['action']:'';
$checkstud = isset($_POST['checkstud'])?$_POST['checkstud']:'';
if($action=='applyforexam')
{
	$result= $exam->add_student_exam();
	$result = json_decode($result, true);
	$success = isset($result['success'])?$result['success']:'';
	$message = isset($result['message'])?$result['message']:'';
	$errors = isset($result['errors'])?$result['errors']:'';
	$invalid = isset($result['invalid'])?$result['invalid']:array();
	$photo = isset($result['photo'])?$result['photo']:array();
	$photo_id = isset($result['photo_id'])?$result['photo_id']:array();
	
	//print_r($result);
	if($success==true)
	{
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=resetExam');
	}
	
}	
?>
<div class="content-wrapper">
	<div class="col-lg-12 grid-margin stretch-card">
	  <div class="card">
	    <div class="card-body">
	      <h4 class="card-title">List Students Exams  
	      </h4> 

			<?php
				if(isset($success))
				{
					$message="";
						if(isset($invalid) && !empty($invalid))
						{
							$message .= "Sorry! Some exams are not applied! Exam mode for these courses are not available!<br>";
						}
						if(isset($photo) && !empty($photo))
						{
							$message .= "Sorry! Following students have not uploaded their photos!";
							$message .= "<ul>";
							foreach($photo as $stud)
							{
								$message .= "<li>$stud</li>";
							}
							$message .= "</ul>";
						}
						if(isset($photo_id) && !empty($photo_id))
						{
							$message .= "Sorry! Following students have not uploaded their Photo ID!";
							$message .= "<ul>";
							foreach($photo_id as $stud_name)
							{
								$message .= "<li>$stud_name</li>";
							}
							$message .= "</ul>";
						}
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
	                     
	<div class="col-md-12 row">     
	<form action="" method="post" class="form-inline" onsubmit="return confirm('Confirm! Apply student for exam?'); pageLoaderOverlay('show')">
	 <div class="col-md-12">
	   <div class="box box-warning">
	   	<?php if($db->permission('list_reset_exam')){ ?>
		 <div class="box-header row mb-4">
		 	<div class="col-md-4">
				<div class="form-group <?= (isset($errors['examtype1']))?'has-error':'' ?>">
					<label class="col-md-6 "><strong>Select Exam Type:</strong></label>
					<?php $examtype1 = isset($_POST['examtype1'])?$_POST['examtype1']:''; ?>
					<select class="col-md-6 form-control" name="examtype1" id="examtype">
						<?php echo $db->MenuItemsDropdown ('exam_types_master',"EXAM_TYPE_ID","EXAM_TYPE","EXAM_TYPE_ID, EXAM_TYPE",$examtype1," WHERE ACTIVE=1 AND DELETE_FLAG=0"); ?>
					</select>	
				</div>
			</div>
		   <div class="col-md-4">
				<input type="submit" class="btn btn-primary" name="submit"  value="Apply For Exam" />
				<input type="hidden" class="btn btn-sm btn-primary" name="action" value="applyforexam">
				<input type="hidden" class="btn btn-sm btn-primary" name="examstatus1" value="2">
		   </div>

		 </div>
	   <?php } ?>
		 <!-- /.box-header -->
		 <div class="box-body">	
			 <div class="table-responsive pt-3">
				 <table id="order-listing" class="table">
			 <thead>
			 <tr>
				 <?php if($db->permission('list_reset_exam')){ ?>
				 <th><input type="checkbox" name="selectall" id="selectall" /></th>
				 <?php }else echo '<th>#</th>'; ?>
				 <th>Exam Status</th>
				 <th>Photo</th>
				 <th>Student Name</th>
				 <th>Course Name</th>
				 <th>Exam Mode</th>	
				 <th>Course Fees</th> 					
				 <th>Balance Fees</th>
				 <!-- <th>Exam Type</th> -->
				 
			 </tr>
			 </thead>
			 <tbody>
		 <?php
	 
		 if($res!='')
		 {
			 $courseSrNo = 1;
				 while($courseData = $res->fetch_assoc())
				 {
					 
					 extract($courseData);
					 //print_r($courseData);
					 //echo "<br>";
					 $EXAM_MODE_TYPE=array();
					 $COURSE_NAME 	= $db->get_inst_course_name($INSTITUTE_COURSE_ID);
					 if($ACTIVE==1)
						 $ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentCourseStatus('.$STUD_COURSE_DETAIL_ID.',0)"><i class="fa fa-check"></i></a>';
					 elseif($ACTIVE==0)	
						 $ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentCourseStatus('.$STUD_COURSE_DETAIL_ID.',1)"><i class="fa fa-times"></i></a>';
				 
				 //	$action = "<!-- <a href='page.php?page=update-exam&id=$STUD_COURSE_DETAIL_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a> -->";
					 $action= '';
					 $reset= '';
					 //if($db->permission('delete_stud_exam'))						
					 $action .= "<a href='javascript:void(0)' onclick='deleteStudentExamDetail($STUD_COURSE_DETAIL_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>";
				 
				 if(($EXAM_STATUS==3 || $EXAM_STATUS==2) && !$exam->check_certificate_applied($STUD_COURSE_DETAIL_ID))
				 $reset = "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' class='resetexam btn btn-xs btn-primary btn1' title='Reset' id='rest$STUD_COURSE_DETAIL_ID'><i class='mdi mdi-refresh'></i> Reset</a>";
					 
				 $examstatus_class = '';
				 switch($EXAM_STATUS){
					 case('1'): $examstatus_class = 'label-warning'; break;
					 case('2'): $examstatus_class = 'label-success'; break;
					 case('3'): $examstatus_class = 'label-info'; break;
					 default: $examstatus_class 	 = 'label-primary'; break;
				 }
				 $examtype_class = '';
				 switch($EXAM_TYPE){
					 case('1'): $examtype_class = 'btn-success'; break;
					 case('2'): $examtype_class = 'btn-danger'; break;
					 case('3'): $examtype_class = 'btn-warning'; break;
					 default: $examtype_class = 'btn-primary'; break;
				 }
				 
				 /*$STUDENT_PHOTO = ($STUDENT_PHOTO!='')?SHOW_IMG_AWS.STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO :'../uploads/default_user.png';
				 */
				 
							 
					 if($STUDENT_PHOTO!=''){
						 $STUDENT_PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;}else{$STUDENT_PHOTO = '/default_user.png';		}
	 
				 // validations before exam apply
				 /* ----------------------------------------------------------------- */
			 
				 
				 $instcourse = $db->get_inst_course_info($INSTITUTE_COURSE_ID);
				 //print_r($instcourse);
				 //for multiple subject condition for exam status

				 $COURSE_ID = isset($instcourse['COURSE_ID'])?$instcourse['COURSE_ID']:'';
				 $MULTI_SUB_COURSE_ID = isset($instcourse['MULTI_SUB_COURSE_ID'])?$instcourse['MULTI_SUB_COURSE_ID']:'';
				 $TYPING_COURSE_ID = isset($instcourse['TYPING_COURSE_ID'])?$instcourse['TYPING_COURSE_ID']:'';

				 //$aicpe_course_id=($COURSE_ID!='')?$COURSE_ID:$MULTI_SUB_COURSE_ID;
				 $aicpe_course_id = $COURSE_ID;
				 $aicpe_course_id_multi = $MULTI_SUB_COURSE_ID;
				 $course_typing = $TYPING_COURSE_ID;
				 
				 $cond='';
				 $errors = array();
				  $valid_exam = $db->validate_apply_exam($aicpe_course_id,$aicpe_course_id_multi,$course_typing);
				  if(!empty($valid_exam))
				  {
					  $errors 	= isset($valid_exam['errors'])?$valid_exam['errors']:'';
					  $success_flag 	= isset($valid_exam['success'])?$valid_exam['success']:'';
					  if($success_flag==true)
					  {
						  $exam_modes= isset($valid_exam['exam_modes'])?$valid_exam['exam_modes']:'';
						  if($exam_modes!='')
						  {
							  $str='';
							  $exam_modes = json_decode($exam_modes);
							  if(!empty($exam_modes))
							  {
								  foreach($exam_modes as $value)
								  {
									 $str .= "'$value',"; 
								  }
								 $str = rtrim($str, ",");
								 $cond = "WHERE EXAM_TYPE_ID IN($str)";
							  }
						  }
					  }
					  
				  }else{
					  $success_flag = false;
					  $errors['exam_unavailable'] = "Exam Unavailable!";
				  }
				  //check for submit form validations
				  $rowclass="";
				  if(isset($invalid) && in_array($STUD_COURSE_DETAIL_ID,$invalid))
				  {
					  $rowclass = "class='danger'";
				  }
				  
				  //disable checkbox button if exam applied
				  
				  /* ----------------------------------------------------------------- */
				 ?>
				 <tr id="row-<?= $STUD_COURSE_DETAIL_ID ?>" <?= $rowclass ?>>
					 <td>
				 <?php if($db->permission('list_reset_exam')){ ?>
				 <?php if($success_flag==true && $EXAM_STATUS==1){?>
				 <input type="checkbox" name="checkstud[]" id="checkstud<?= $STUD_COURSE_DETAIL_ID ?>" value="<?= $STUD_COURSE_DETAIL_ID ?>" <?= ($checkstud!='' && in_array($STUD_COURSE_DETAIL_ID,$checkstud))?'checked="checked"':'' ?> />
				 <?php }else{ ?>
				  <span></span> 
				 <?php } ?>
				 <?php }else echo $courseSrNo; ?>
				 </td>
				 <td id="exam-status-<?= $STUD_COURSE_DETAIL_ID ?>">
				  <?php if($success_flag==true)
				  { 
				   $sqlExamStatus = "SELECT EXAM_STATUS_ID,EXAM_STATUS FROM exam_status_master";
				   $resExamStatus = $db->execQuery($sqlExamStatus);
				   if($resExamStatus && $resExamStatus->num_rows>0)
				   {
					   
					   while($dataExamStatus = $resExamStatus->fetch_assoc())
					   {
						   if($EXAM_STATUS==$dataExamStatus['EXAM_STATUS_ID'])
						   echo '<label class="label '.$examstatus_class.'">'.$dataExamStatus['EXAM_STATUS'].'</label>';
					   }
					   
				   }
				   ?>
				  <?php }else{
					  foreach($errors as $value)
					  {
						  echo $value."<br>";
					  }
				  }  
				   echo $reset;
				  ?>
				   </td>	
			 
				 
				   <td><img src="<?= $STUDENT_PHOTO ?>" class="img img-responsive img-thumbnail" style="width:50px; height:50px"></td> 
				   <td><?= $STUDENT_NAME ?></td>	
				   <td><?= $COURSE_NAME ?></td>
				   <td><?= $EXAM_TYPE_NAME ?></td>	
				   <td><?= $COURSE_FEES ?></td>	 
				   <td><?= ($BALANCE_FEES=='')?$COURSE_FEES:$BALANCE_FEES  ?></td>	 
			 <!--
				  <td id="exam-type-<?= $STUD_COURSE_DETAIL_ID ?>">
				  <?php
				 
				  if($success_flag==true)
				  {
				  ?>
				   <?php 
				   $sqlExam = "SELECT * FROM exam_types_master $cond";
				   $resExam = $db->execQuery($sqlExam);
				   if($resExam && $resExam->num_rows>0)
				   {
					   echo '<ul>';
					   while($dataExam = $resExam->fetch_assoc())
					   {
						   echo '<li>'.$dataExam['EXAM_TYPE'].'</li>';
					   }
					   echo '</ul>';
				   }
				   ?>
				  <?php }else{
					  foreach($errors as $value)
					  {
						  echo $value."<br>";
					  }
				  } ?>
				   </td>	
			  
			 
				  -->
				 
				  </tr>
				 <?php
				 $courseSrNo++;
				 }
		 }
		 
		 ?>
			 </tbody>               
		   </table>
			 </div>
		 </div>
		 <div class="box-footer">
		  <div class="alert" style="background: #f9f9f9;color: #000; font-size:12px;">
			 <strong> Please Note:</strong>
			 <ol>			 
				 <li> Before applying for Exam please check if the Students Passport Photo, Photo ID Proof and Photo ID Type and Number are already uploaded.</li>
				   <li>Students Photo and Photo ID Type and Number will be printed on
				   Students Final Certificate. Please check it carefully. </li> 
				   <li> Corrections are not permitted once applied.</li>
			   </ol> 			  
		   </div>
		 </div>
		 <!-- /.box-body -->
	   </div>
	   <!-- /.box -->     
	   <!-- /.box -->
	 </div>
	   </form>
	 <!-- /.col -->
   </div>

	    
</div>