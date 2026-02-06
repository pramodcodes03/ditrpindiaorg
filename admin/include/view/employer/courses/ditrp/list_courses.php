<?php
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
?>
<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
	  <div class="card">
	    <div class="card-body">
	      	<h4 class="card-title">List Course with Single Subject </h4> 
			<div style="margin-bottom:10px;">
				<?php if($db->permission('list_courses')){ ?>	
				<a style="margin-bottom:10px;" href="page.php?page=listCourses" class="btn btn-warning"><i class="mdi mdi-library-books"></i>  List Course with Single Subject </a> &nbsp;
				<?php } ?>
				
				<?php if($db->permission('add_courses')){ ?>	
				<a style="margin-bottom:10px;" href="page.php?page=addCourses" class="btn btn-warning"><i class="mdi mdi-library-plus"></i> Add Course with Single Subject</a> &nbsp;
				<?php } ?>
				
				<?php if($db->permission('list_courses_multisub')){ ?>	
				<a style="margin-bottom:10px;" href="page.php?page=listCoursesMultiSub" class="btn btn-primary"><i class="mdi mdi-library-books"></i>  List Course With Multiple Subject</a> &nbsp;
				<?php } ?>

				<?php if($db->permission('add_courses_multisub')){ ?>	
				<a style="margin-bottom:10px;" href="page.php?page=addCoursesMultiSub" class="btn btn-primary"><i class="mdi mdi-library-plus"></i> Add Course With Multiple Subject</a> &nbsp;
				<?php } ?>	
				
				<?php if($db->permission('list_courses_typing')){ ?>	
				<a style="margin-bottom:10px;" href="page.php?page=listCoursesTyping" class="btn btn-info"><i class="mdi mdi-library-books"></i>  List Typing Course</a> &nbsp;
				<?php } ?>	
				<?php if($db->permission('add_courses_typing')){ ?>		
				<a style="margin-bottom:10px;" href="page.php?page=addCoursesTyping" class="btn btn-info"><i class="mdi mdi-library-plus"></i> Add Typing Course</a> &nbsp;
				<?php } ?>	
				<a style="margin-bottom:10px;" href="page.php?page=previewSingleMarksheet" class="btn btn-success" target="_blank"><i class="mdi mdi-library-plus"></i> Single Course Marksheet</a> &nbsp;
			
				<a style="margin-bottom:10px;" href="page.php?page=previewMultipleMarksheet" class="btn btn-success" target="_blank"><i class="mdi mdi-library-plus"></i> Multiple Course Marksheet</a> &nbsp;
			
				<a style="margin-bottom:10px;" href="page.php?page=previewTypingMarksheet" class="btn btn-success" target="_blank"><i class="mdi mdi-library-plus"></i> Typing  Course Marksheet</a> &nbsp;
			
			</div>
		 

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
					<h4><i class="icon mdi mdi-check"></i> <?= ($msg_flag==true)?'Success':'Error' ?>:</h4>
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
					<?php if($db->permission('delete_aicpe_courses')){ ?>    <th><label><input type='checkbox' value='1' id='selectall' class='edit-course'></label></th><?php } ?>    
					
					<th>Sr.</th> 
					<th>Course Name</th>
					<!-- <th>Certifying Authority</th> -->
					<th>Exam Fees</th>
					<th>Course Fees</th>
					<th>Minimum Fees</th>
					<th>Duration</th>
					<th>Status</th>
					<th>Action</th>
	            </tr>
	          </thead>
	          <tbody>
			  	<?php
					include_once('include/classes/course.class.php');
					$course = new course();
					$res = $course->list_added_courses($institute_id);
					if($res!='')
					{
						$srno=1;
						while($data = $res->fetch_assoc())
						{
							$INSTITUTE_COURSE_ID 		= $data['INSTITUTE_COURSE_ID'];
							$COURSE_ID 		= $data['COURSE_ID'];
							$COURSE_NAME_MODIFY 	= $data['COURSE_NAME_MODIFY'];
							$COURSE_AWARD_NAME 	= $data['COURSE_AWARD_NAME'];
							$COURSE_AUTHORITY 	= 'DITRP';
							$COURSE_CODE 	= $data['COURSE_CODE'];
							$COURSE_DURATION= $data['COURSE_DURATION'];
							$COURSE_NAME 	= $data['COURSE_NAME'];
							$COURSE_FEES 	= $data['COURSE_FEES'];
							$EXAM_FEES 	= $data['EXAM_FEES'];
							$INSTITUTE_COURSE_FEES 	= $data['INSTITUTE_COURSE_FEES'];
							$ACTIVE			= $data['STATUS'];
							$CREATED_BY 	= $data['CREATED_BY'];
							$CREATED_ON 	= $data['CREATED_ON'];
							$MINIMUM_FEES 	= $data['MINIMUM_FEES'];
							
							$PLAN_FEES 	= $data['PLAN_FEES'];
							
							if($ACTIVE==1)
							$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeInstCourseStatus('.$INSTITUTE_COURSE_ID.',0)"><i class="mdi mdi-check"></i> Active</a>';
							elseif($ACTIVE==0)	
							$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeInstCourseStatus('.$INSTITUTE_COURSE_ID.',1)"><i class="mdi mdi-close"></i>In-Active</a>';	
							
							
							/*if($ACTIVE==1) $ACTIVE= 'Active';
							elseif($ACTIVE==0) $ACTIVE= 'In-Active';
							*/
							$PHOTO = '../uploads/default_user.png';
							$action="";
							if($db->permission('update_aicpe_courses'))
								$action .= "<a href='page.php?page=updateCourses&id=$INSTITUTE_COURSE_ID' class='btn btn-primary table-btn' title='Edit'><i class=' mdi mdi-grease-pencil'></i></a>  ";
							$action .= '<a href="javascript:void(0)"  onclick="getInstCourseSubjects('.$INSTITUTE_COURSE_ID.')" class="btn btn-primary btn1"><i class="fa fa-plus" aria-hidden="true"></i> Add Subjects</a>';
							if($db->permission('delete_aicpe_courses'))
								$action .="<a href='javascript:void(0)' onclick='deleteInstCourse($INSTITUTE_COURSE_ID)' class='btn btn-danger table-btn' title='Remove'><i class=' mdi mdi-delete'></i></a>";
						
							$DISP_INSTITUTE_COURSE_FEES 	= "<span id='dis_fee_$INSTITUTE_COURSE_ID'>$INSTITUTE_COURSE_FEES</span> <a href='javascript:void(0)' onclick='toggleEditBox(this.id)' class='pull-right' id='editfees_$INSTITUTE_COURSE_ID'><i class='mdi mdi-grease-pencil'></i></a>";
						
						
							
							$editcourse_txt = ' <div class="input-group input-group-sm" id="editbox_'.$INSTITUTE_COURSE_ID.'" style="display:none;">
						<input type="text" class="form-control" value="'.$INSTITUTE_COURSE_FEES.'" id="coursefees_'.$INSTITUTE_COURSE_ID.'">
							<span class="input-group-btn">
							<button type="button" class="btn btn-info btn-flat" onclick="changeInstCourseFees(this.id)" id="change_fees_'.$INSTITUTE_COURSE_ID.'">Save</button>
							</span>
					</div>';
					
					$checkbox="";
							if($db->permission('delete_aicpe_courses'))
								$checkbox = "<td><label><input type='checkbox' name='check_course' value='$INSTITUTE_COURSE_ID' id='check-$INSTITUTE_COURSE_ID' class='check-course' ></label></td>	";
							echo " <tr id='row-".$INSTITUTE_COURSE_ID."'>
									$checkbox
									<td>$srno</td>	
									<td>$COURSE_NAME_MODIFY</td>								
									<td>$PLAN_FEES </i></td>
									<td>$INSTITUTE_COURSE_FEES</td>
									<td>$MINIMUM_FEES</td>
									<td>$COURSE_DURATION</td>
									<td id='status-$INSTITUTE_COURSE_ID'>$ACTIVE</td>
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

<!-- modal to send email -->
<div class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">			
			<div class="box box-primary modal-body">
				<form action="" method="post" id="bulk_edit_course_form">
					<div class="">
						<div class="box-header with-border">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h3 class="box-title">Bulk Edit</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="form-group">						
							<p class="help-block" id="ajax-data"></p>
							</div>
							<div class="form-group" id="exam_fees_err">
							Update Price Of Selected Courses
							<input class="form-control" name="exam_fees" id="exam_fees" placeholder="Enter Price">
							<span class="help-block"></span>
							<input class="form-control" type="hidden" name="action" id="action" value="bulk_update_submit_course">
							</div>							
						</div>
						<div class="box-footer">
							<div class="pull-right">
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
							<input type="submit" name="submit" id="submit_btn" class="btn btn-primary" value="Update" />
							</div>					 
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="instCourseSubjectsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Course Subject</h4>
			</div>
			<form class="form-horizontal form-validate" id="instCourseSubjects" action="" method="post">  
				<input type="hidden" name="action" value="add_course_detail">    
				<input type="hidden" name="inst_course_id" id="inst_course_id" value="">    
				<div class="modal-body">
				
				<label for="Subject" class="col-xs-3 control-label">Subject</label>
				<textarea rows="4" cols="30" class="form-control" id="subject" name="subject" placeholder="Add Course Subject here"></textarea>
				<p> (Please Enter Subjects)</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" value="add_course_subjects" name="action">Save changes</button>
				</div>    
			</form>
		</div>
	</div>
</div>