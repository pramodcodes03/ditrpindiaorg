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

 

 ?>

<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
	  <div class="card">
	    <div class="card-body">
	      <h4 class="card-title"> List Exam Code
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
					<th>S/N</th>
					<th>Photo</th> 
					<th>Exam Code</th>
					<th>Name</th>
					<th>Email</th>
					<th>Mobile</th>
					<th>Created On</th>
					<!--<th>Action</th>-->
	            </tr>
	          </thead>
	          <tbody>
			    <?php

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

					include_once('include/classes/student.class.php');

					$student = new student();

					$res = $exam->list_exam_codes('',$institute_id, '','','', " AND EXAM_SECRETE_CODE!='' OR EXAM_SECRETE_CODE!=NULL ");

					if($res!='')

					{

						$srno=1;

						while($data = $res->fetch_assoc())

						{

							$STUDENT_ID 		= $data['STUDENT_ID'];

							$INSTITUTE_ID 		= $data['INSTITUTE_ID'];

							$STUDENT_CODE 		= $data['STUDENT_CODE'];

							$STUDENT_FULLNAME 	= $data['STUDENT_NAME'];

							$STUDENT_MOBILE		= $data['STUDENT_MOBILE'];				

							$STUDENT_EMAIL		= $data['STUDENT_EMAIL'];				

							

							$STUD_PHOTO		= $data['STUDENT_PHOTO'];				

							$ACTIVE 			= $data['ACTIVE'];	

							$EXAM_SECRETE_CODE 			= $data['EXAM_SECRETE_CODE'];	

							$EXAM_CODE_DATE 			= $data['EXAM_CODE_DATE'];	

							$EXAM_STATUS_NAME 			= $data['EXAM_STATUS_NAME'];	

							/*

							if($ACTIVE==1)

							$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeStudentStatus('.$STUDENT_ID.',0)"><i class="fa fa-check"></i></a>';

							elseif($ACTIVE==0)	

							$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeStudentStatus('.$STUDENT_ID.',1)"><i class="fa fa-times"></i></a>';	

							*/

							

							$PHOTO = '../uploads/default_user.png';					

							if($STUD_PHOTO!='')

								$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUD_PHOTO;

							$editLink = "";
							// get student courses details

							$courseInfo = "<a href='javascript:void(0)' class='btn btn-link show-stud-course-info' title='View Course Details' data-toggle='modal' data-target='.show-stud-course-details'  data-id='$STUDENT_ID' data-name='$STUDENT_FULLNAME' data-email='$STUDENT_EMAIL'><i class='fa  fa-info'></i></a>

							<a href='page.php?page=list-student-courses&id=$STUDENT_ID' class='btn btn-xs btn-link' title='View Course Info'><i class=' fa fa-eye'></i></a>";

							echo " <tr id='row-$STUDENT_ID'>
									<td>$srno</td>
									<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>
									<td><h4>$EXAM_SECRETE_CODE</h4></td>
									<td>$STUDENT_FULLNAME</td>	
								<!--	<td>$STUDENT_CODE</td> -->
									<td>$STUDENT_EMAIL</td>
									<td>$STUDENT_MOBILE</td>
									<td>$EXAM_CODE_DATE</td>								
									<!--	<td>$editLink</td> -->
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