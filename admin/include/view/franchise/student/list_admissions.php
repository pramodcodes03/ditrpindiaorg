 <?php
 include('include/classes/exam.class.php');
 $exam = new exam();
 
$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';		  

$institute_id = $user_id;


 $res='';
 
 $selsql = "SELECT *,get_student_name(A.STUDENT_ID) AS STUDENT_NAME,get_stud_photo(A.STUDENT_ID) AS STUDENT_PHOTO, DATE_FORMAT(A.CREATED_ON, '%d-%m-%Y') AS CREATED_DATE   FROM student_course_details A WHERE A.DELETE_FLAG=0 ";
 
 

 $selsql .= " ORDER BY A.CREATED_ON DESC";
$result = $db->execQuery($selsql);
if($result && $result->num_rows>0)
	$res = $result;
 
$action = isset($_POST['action'])?$_POST['action']:'';
$checkstud = isset($_POST['checkstud'])?$_POST['checkstud']:'';
if($action=='applyforexam')
{
	$result= $exam->add_student_exam();
	$result = json_decode($result, true);
	$success = isset($result['success'])?$result['success']:'';
	$message = isset($result['message'])?$result['message']:'';
	$errors = isset($result['errors'])?$result['errors']:'';
	if($success==true)
	{
		$_SESSION['msg'] = $message;
		$_SESSION['msg_flag'] = $success;
		header('location:page.php?page=list-exams');
	}
	
}	
?>

<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
	  <div class="card">
	    <div class="card-body">
	      <h4 class="card-title">List Admissions           
	      
      		<?php if($db->permission('add_enquiry')){ ?>
			<div class="form-group col-sm-2">
			  <label> &nbsp;</label>
			  <a href="page.php?page=add-student-enquiry" class="form-control btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Enquiry</a>
			</div>
			<?php } ?>
			<?php if($db->permission('add_admission')){ ?>
			<div class="form-group col-sm-2">
			  <label> &nbsp;</label>
			  <a href="page.php?page=list-student-enquiries" class="form-control btn btn-sm btn-primary"><i class="fa fa-plus"></i> Register Admission</a>
			</div>

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
	                     
	      <div class="table-responsive pt-3">
	        <table id="order-listing" class="table">
	          <thead>
	            <tr>
	            	<th>#</th>
					<th>Action</th>
					<th>Photo</th>
					<th width="20%">Student</th>					
					<th>Course</th>					
					<th>Course Fees</th>								
					<th>Balance</th>
					<th>Admission Date</th>
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
								$TOTAL_BALANCE_FEES='N/A';
								$sql2 = "SELECT ($TOTAL_COURSE_FEES - SUM(B.FEES_PAID)) AS TOTAL_BALANCE_FEES FROM student_payments B WHERE B.STUD_COURSE_DETAIL_ID='$STUD_COURSE_DETAIL_ID' AND B.DELETE_FLAG=0";
								$res2 = $db->execQuery($sql2);
								if($res2 && $res2->num_rows>0)
								{
									$data2 = $res2->fetch_assoc();
									$TOTAL_BALANCE_FEES = $data2['TOTAL_BALANCE_FEES'];
								}						
								$courseinfo 	= $db->get_inst_course_info($INSTITUTE_COURSE_ID);
								//print_r($course_info);
								$COURSE_NAME = isset($courseinfo['COURSE_NAME_MODIFY'])?$courseinfo['COURSE_NAME_MODIFY']:'';
								if($ACTIVE==1)
									$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentCourseStatus('.$STUD_COURSE_DETAIL_ID.',0)"><i class="fa fa-check"></i></a>';
								elseif($ACTIVE==0)	
									$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentCourseStatus('.$STUD_COURSE_DETAIL_ID.',1)"><i class="fa fa-times"></i></a>';
								$action='';
								if($db->permission('update_admission'))	
								$action .= "<a href='page.php?page=update-admission&id=$STUD_COURSE_DETAIL_ID' class='btn btn-xs btn-link' title='Edit'><i class=' fa fa-pencil'></i></a>";
							
								if($db->permission('delete_admission'))	
								$action .= "<a href='javascript:void(0)' onclick='deleteStudentAdmission($STUD_COURSE_DETAIL_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>";
							
							$examstatus_class = '';
							switch($EXAM_STATUS){
								case('1'): $examstatus_class = 'btn-warning'; break;
								case('2'): $examstatus_class = 'btn-success'; break;
								case('3'): $examstatus_class = 'btn-info'; break;
								default: $examstatus_class = 'btn-primary'; break;
							}
							$examtype_class = '';
							switch($EXAM_TYPE){
								case('1'): $examtype_class = 'btn-success'; break;
								case('2'): $examtype_class = 'btn-danger'; break;
								case('3'): $examtype_class = 'btn-warning'; break;
								default: $examtype_class = 'btn-primary'; break;
							}
							
							//$STUDENT_PHOTO = ($STUDENT_PHOTO!='')?STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/thumb/'.$STUDENT_PHOTO :'../uploads/default_user.png';
							
							$STUDENT_PHOTO = SHOW_IMG_AWS.'/default_user.png';					
							if($STUDENT_PHOTO!='')
								$STUDENT_PHOTO = SHOW_IMG_AWS.'/student/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;
								
							?>
							<tr id="row-<?= $STUD_COURSE_DETAIL_ID ?>">
							<!--<td><input type="checkbox" name="checkstud[]" id="checkstud<?= $STUD_COURSE_DETAIL_ID ?>" value="<?= $STUD_COURSE_DETAIL_ID ?>" <?= ($checkstud!='' && in_array($STUD_COURSE_DETAIL_ID,$checkstud))?'checked="checked"':'' ?> /></td> -->
							
							<td><?= $courseSrNo ?></td>
							<td><?= $action ?></td>
								<td><img src="<?= $STUDENT_PHOTO ?>" class="img img-responsive img-thumbnail" style="width:50px; height:50px"></td> 
							  <td><?= $STUDENT_NAME ?></td>	
							
							  <td><?= $COURSE_NAME ?></td>											 
							  <td><?= $COURSE_FEES ?></td>	 
							 
							  <td><?= $TOTAL_BALANCE_FEES ?></td>	 
						
							
							 <td><?= $CREATED_DATE ?></td>
							 
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
	  </div>
	</div>
</div>