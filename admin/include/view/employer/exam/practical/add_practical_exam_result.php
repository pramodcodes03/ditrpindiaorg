<?php

	include('include/classes/exam.class.php');

	$exam = new exam();

	include('include/classes/exammultisub.class.php');

	$exammultisub = new exammultisub();

	include('include/classes/coursetypingexam.class.php');

	$coursetypingexam = new coursetypingexam();

	$offline_paper_id= isset($_GET['id'])?$_GET['id']:'';			  

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

	$action 	= '';

	$save		= isset($_POST['save'])?$_POST['save']:'';

	$register	= isset($_POST['register'])?$_POST['register']:'';

	$save_multi_sub	= isset($_POST['save_multi_sub'])?$_POST['save_multi_sub']:'';

	$save_typing	= isset($_POST['save_typing'])?$_POST['save_typing']:'';


	if($save!='')

		$action		= $save;

	if($register!='')

		$action		= $register;

	include_once('include/classes/student.class.php');

	$student = new student();

	if($action!='')

	{

		//print_r($_POST);

		

		$result= $exam->add_practical_exam_marks();

		$result = json_decode($result, true);

		$success = isset($result['success'])?$result['success']:'';

		$message = isset($result['message'])?$result['message']:'';

		$errors = isset($result['errors'])?$result['errors']:'';

		if($success==true)

		{

			$_SESSION['msg'] = $message;

			$_SESSION['msg_flag'] = $success;

			header('location:page.php?page=listPracticalExamResult');

		}

	}
	if($save_multi_sub!='')

	{

		$result= $exammultisub->add_practical_exam_marks_multi_sub();

		$result = json_decode($result, true);

		$success = isset($result['success'])?$result['success']:'';

		$message = isset($result['message'])?$result['message']:'';

		$errors = isset($result['errors'])?$result['errors']:'';

		if($success==true)

		{

			$_SESSION['msg'] = $message;

			$_SESSION['msg_flag'] = $success;

			header('location:page.php?page=listPracticalExamResult');

		}

	}

	//typing result add
	if($save_typing!='')
	{
		$result= $coursetypingexam->add_practical_exam_marks_typing();
		$result = json_decode($result, true);
		$success = isset($result['success'])?$result['success']:'';
		$message = isset($result['message'])?$result['message']:'';
		$errors = isset($result['errors'])?$result['errors']:'';
		if($success==true)
		{
			$_SESSION['msg'] = $message;
			$_SESSION['msg_flag'] = $success;
			header('location:page.php?page=listPracticalExamResult');
		}
	}

	/*$PHOTO = '../uploads/default_user.png';	*/
	$PHOTO = '../uploads/default_user.png';

	//$res 	= $exam->list_offline_downloaded_papers($offline_paper_id,'','','');

	$res = $exam->list_practical_exams('','', '','3', ''," AND A.STUD_COURSE_DETAIL_ID='$offline_paper_id' ");

	if($res!='')

	{

		while($data = $res->fetch_assoc())

		{

			extract($data);
			//print_r($data); exit();

			$course_info	= $db->get_inst_course_info($INSTITUTE_COURSE_ID);
			//print_r($course_info);


			$COURSE_ID 	= isset($course_info['COURSE_ID'])?$course_info['COURSE_ID']:'';	

			$MULTI_SUB_COURSE_ID 	= isset($course_info['MULTI_SUB_COURSE_ID'])?$course_info['MULTI_SUB_COURSE_ID']:'';

			$TYPING_COURSE_ID 	= isset($course_info['TYPING_COURSE_ID'])?$course_info['TYPING_COURSE_ID']:'';

			$EXAM_TITLE 	= isset($course_info['COURSE_NAME'])?$course_info['COURSE_NAME']:'';

			//$EXAM_TITLE_MULTI_SUB 	= isset($course_info['MULTI_SUB_COURSE_NAME'])?$course_info['MULTI_SUB_COURSE_NAME']:'';

			$COURSE_NAME_MODIFY = $course_info['COURSE_NAME_MODIFY'];
          
           $EXAM_TITLE_MULTI_SUB = $course_info['COURSE_NAME_MODIFY'];

			if($COURSE_ID!=''){

				$examstr 			= $db->get_exam_structure($COURSE_ID);
			}
			if($MULTI_SUB_COURSE_ID!=''){

				$examstr 			= $db->get_exam_structure_multi_sub($MULTI_SUB_COURSE_ID);
			}
			if($TYPING_COURSE_ID!='' && !empty($TYPING_COURSE_ID)){

				$examstr = $db->get_exam_structure_typing_course($TYPING_COURSE_ID);
			}
			if($examstr!='')

			{
				$examstr = $examstr->fetch_assoc();
			}			
			if($STUDENT_PHOTO!='')

				$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;

		}

	}
?>

<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"> Add Practical Exam Result </h4>          
          <form class="forms-sample" action="" method="post" enctype="multipart/form-data">  
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
  
				<?php if($COURSE_ID!=''){ ?>
					<div class="row">

						<div class="col-md-7">

						<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">

						<div class="box box-primary">

							<div class="box-header with-border">
							
							<marquee style="color:red; padding:5px; background-color:yellow; font-weight:900; font-size:14px;"> Please Enter Subjects Before Updating Marks Other Wise No Subjects Printed On Marksheet And DITRP Charges For Updation For Marksheet With Courier Charges.
							</marquee>

							</div>
							
							<div class="box-body">			 

								

								<input type="hidden" name="stud_course_detail_id" value="<?= $STUD_COURSE_DETAIL_ID ?>" />

								<input type="hidden" name="student_id" value="<?= $STUDENT_ID ?>" />

								<input type="hidden" name="institute_id" value="<?= $INSTITUTE_ID ?>" />

								<input type="hidden" name="exam_id" value="<?= $examstr['EXAM_ID'] ?>" />

								<input type="hidden" name="inst_course_id" value="<?= $INSTITUTE_COURSE_ID ?>" />

								<input type="hidden" name="exam_secrete_code" value="<?= $EXAM_SECRETE_CODE ?>" />

								<input type="hidden" name="exam_title" value="<?= $examstr['EXAM_TITLE'] ?>" />

								<input type="hidden" name="exam_attempt" value="1" />

								<input type="hidden" id="exam_total_que" name="exam_total_que" value="<?= $examstr['TOTAL_QUESTIONS'] ?>" />

								<input type="hidden" id="exam_total_marks" name="exam_total_marks" value="<?= $examstr['TOTAL_MARKS'] ?>" />

								<input type="hidden" id="exam_marks_per_que" name="exam_marks_per_que" value="<?= $examstr['MARKS_PER_QUE'] ?>" />

								<input type="hidden" name="exam_passing_marks" value="<?= $examstr['PASSING_MARKS'] ?>" />

								

								<input type="hidden" name="exam_time" value="<?= $examstr['EXAM_TIME'] ?>" />				

								<input type="hidden" name="exam_type" value="3" />

								<input type="hidden" name="exam_status" value="3" />

								<div class="row">
									<div class="form-group col-md-6 <?= (isset($errors['marksobt']))?'has-error':'' ?>">
										<label>Total Objective Marks Obtained</label>
										<input class="form-control" id="marksobt" placeholder="Total  objective Marks obtained" type="number" name="marksobt" maxlength="50" onkeyup="this.value = minmax(this.value, 0, 50)" value="<?= isset($_POST['marksobt'])?$_POST['marksobt']:''; ?>" >
										<span class="help-block"><?= (isset($errors['marksobt']))?$errors['marksobt']:'' ?></span>
											<p> (Out Of 50 Marks)</p>
									</div>

									<div class="form-group col-md-6 <?= (isset($errors['marksobt']))?'has-error':'' ?>">
										<label>Total Practical Marks Obtained</label>
										<input class="form-control" id="marksobtpract" placeholder="Total practicle  Marks obtained" type="number" name="marksobtpract"  maxlength="50" onkeyup="this.value = minmax(this.value, 0, 50)"value="<?= isset($_POST['marksobtpract'])?$_POST['marksobtpract']:''; ?>"  >
										<span class="help-block"><?= (isset($errors['marksobtpract']))?$errors['marksobtpract']:'' ?></span>
										<p> (Out Of 50 Marks)</p>                  
									</div>
									
									<div class="form-group col-md-6 <?= (isset($errors['marksobt']))?'has-error':'' ?>">

										<label>Subjects</label>
									
										<textarea class="form-control"   placeholder="SUBJECT" type="text" name="subject" value="" > <?= isset($_POST['subject'])?$_POST['subject']:$COURSE_SUBJECTS ?></textarea>

										<span class="help-block"><?= (isset($errors['subject']))?$errors['subject']:'' ?></span>
										<p> (Please Enter Subjects)</p>
									</div>

									

									<div class="form-group col-md-6 <?= (isset($errors['marks_per']))?'has-error':'' ?>">
									<label>Percentage</label>
										<input class="form-control" id="marks_per" placeholder="Percentage" type="number" name="marks_per" value="" onkeyup="calPracticalResult()" readonly="readonly" />
										<span class="help-block"><?= (isset($errors['marks_per']))?$errors['marks_per']:'' ?></span>
									</div>
					
									<div class="form-group col-md-6 <?= (isset($errors['grade']))?'has-error':'' ?>">
										<label>Grade</label>
										<input class="form-control" id="grade" placeholder="Grade" type="text" name="grade" value="" readonly>
										<span class="help-block"><?= (isset($errors['grade']))?$errors['grade']:'' ?></span>
									</div>

									<div class="form-group col-md-6 <?= (isset($errors['result_status']))?'has-error':'' ?>">
										<label>Result Status</label>
										<input class="form-control" id="result_status" placeholder="Result status" type="text" name="result_status" value=""readonly>
										<span class="help-block"><?= (isset($errors['result_status']))?$errors['result_status']:'' ?></span>
									</div> 

									<div class="form-group col-md-12 <?= (isset($errors['result_status']))?'has-error':'' ?>">
										<input type="checkbox" id="conditon" name="conditon" value="conditon" required> I declare that I have 	verified the candidate appearing for Examination in all respect. 
												I had conducted the examination of this student under the supervision of subject experts. 
												All Details / Records of Exam conduction is available with me and I promise to provide the records immediately whenever demanded. 
												I am responsible and answerable for all queries, whenever arises.                
									</div> 
								</div>
							</div>  
							<div class="box-footer text-center">
								<input type="submit" class="btn btn-primary" name="save" value="Save" />&nbsp;&nbsp;&nbsp;
								<a href="page.php?page=listPracticalExamResult" class="btn btn-danger" title="Cancel">Cancel</a>	
							</div>   
						</div>
						</form>  
						</div>	

							

						<div class="col-md-4">
							<div class="box box-primary">
								<div class="box-header with-border">
								<h3 class="box-title">Exam Details</h3>
								</div>          

							<table class="table">
								<tr>
									<th>Photo</th>
									<td><img src="<?= $PHOTO ?>" class='img img-responsive img-circle' style='width:50px; height:50px'></td>
								</tr>
								<tr>
									<th>Student Name</th>
									<td><?= $STUDENT_NAME ?></td>
								</tr>

								<tr>
									<th>Exam Name</th>
									<td><?php echo $abc = (isset($examstr['EXAM_TITLE']))?$examstr['EXAM_TITLE']:$EXAM_TITLE_MULTI_SUB; ?></td>
								</tr>

								<!--

								<tr>

									<th>Exam Duration</th>

									<td><?= $examstr['EXAM_TIME'] ?> Minutes</td>

								</tr>

								<tr>

									<th>Total Questions</th>

									<td><?= $examstr['TOTAL_QUESTIONS'] ?></td>

								</tr>

								<tr>

									<th>Total Marks</th>

									<td><?= $examstr['TOTAL_MARKS'] ?></td>

								</tr>

								<tr>

									<th>Total Passing Marks</th>

									<td><?= $examstr['PASSING_MARKS'] ?></td>

								</tr>

								<tr>

									<th>Marks Per Question</th>

									<td><?= $examstr['MARKS_PER_QUE'] ?></td>

								</tr>

								-->

							</table>

						</div>
					</div>

					</div>
				<?php 
					}
				?>

				<?php if($MULTI_SUB_COURSE_ID!=''){ ?>
						<div class="row">
								
							<div class="col-md-6">
								<div class="table-responsive pt-3">
									<table id="order-listing" class="table">
									
										<tr>
											<th>Photo</th>
											<td><img src="<?= $PHOTO ?>" class='img img-responsive img-circle' style='width:50px; height:50px'></td>
										</tr>
										<tr>
											<th>Student Name</th>
											<td><?= $STUDENT_NAME ?></td>
										</tr>
										<tr>
											<th>Exam Name</th>
											<td><?php echo $abc = (isset($examstr['EXAM_TITLE']))?$examstr['EXAM_TITLE']:$EXAM_TITLE_MULTI_SUB; ?></td>
										</tr>
									</table>
								</div>
							</div>	
							<div class="col-md-12">
									<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');">				
									<div class="table-responsive pt-3">
										<table id="order-listing" class="table" style="font-size:12px; ">
										
											<tbody>
											<tr>
												<th rowspan="2">SUBLECT</th>
												<th colspan="2">THEORY MARKS</th>
												<th colspan="2">PRACTICAL MARKS</th>                   
												<th rowspan="2">OBT MARKS</th>
												<th rowspan="2">TOTAL MARKS</th>
											</tr>
											<tr>
												<th>TOTAL THEORY MARKS</th>
												<th>OBT THEORY MARKS</th>
												<th>PRACTICAL MARKS</th>
												<th>OBT PRACTICAL MARKS</th>                   
											</tr>                
											<?php              	

											include('include/classes/coursemultisub.class.php');
											
											$coursemultisub = new coursemultisub();
											
											$res1 = $coursemultisub->get_course_subject_added_by_institute($MULTI_SUB_COURSE_ID,$institute_id, false);  
											if($res1!='')
											{
												$srno=1;
												while($data1 = $res1->fetch_assoc())
												{
													$INSTITUTE_SUBJECT_ID 	= $data1['INSTITUTE_SUBJECT_ID'];
													$MULTI_SUB_COURSE_ID 	= $data1['MULTI_SUB_COURSE_ID'];
													$SUBJECT_ID 			= $data1['SUBJECT_ID'];
													$SUBJECT_NAME 			= $data1['SUBJECT_NAME'];

													$EXAM_ID 				= $data1['EXAM_ID'];
													$COURSE_SUBJECT_ID 		= $data1['COURSE_SUBJECT_ID'];
													$TOTAL_MARKS 			= $data1['TOTAL_MARKS'];
													$TOTAL_QUESTIONS 		= $data1['TOTAL_QUESTIONS'];
													$MARKS_PER_QUE 			= $data1['MARKS_PER_QUE'];
													$PASSING_MARKS 			= $data1['PASSING_MARKS'];
													$EXAM_TIME 				= $data1['EXAM_TIME'];

											?>
											<tr>

												<input type="hidden" name="multisub_studcoursedetailid<?=$srno?>" value="<?= $STUD_COURSE_DETAIL_ID ?>" />
												<input type="hidden" name="multisub_studentid<?=$srno?>" value="<?= $STUDENT_ID ?>" />
												<input type="hidden" name="multisub_instituteid<?=$srno?>" value="<?= $INSTITUTE_ID ?>" />
											
												<input type="hidden" name="multisub_instcourseid<?=$srno?>" value="<?= $INSTITUTE_COURSE_ID ?>" />
												<input type="hidden" name="multisub_examsecretecode<?=$srno?>" value="<?= $EXAM_SECRETE_CODE ?>" />
												<input type="hidden" name="multisub_examtitle<?=$srno?>" value="<?= $EXAM_TITLE_MULTI_SUB ?>" />
												<input type="hidden" name="multisub_examattempt<?=$srno?>" value="1" />
									

												<input type="hidden" name="multisub_examid<?=$srno?>" value="<?= $EXAM_ID ?>" />
												<input type="hidden" name="multisub_coursesubid<?=$srno?>" value="<?= $COURSE_SUBJECT_ID ?>" />
												<input type="hidden" name="multisub_courseid<?=$srno?>" value="<?= $MULTI_SUB_COURSE_ID ?>" />

												<input type="hidden" name="multisub_totalmarks<?=$srno?>" value="<?= $TOTAL_MARKS ?>" />
												<input type="hidden" name="multisub_totalque<?=$srno?>" value="<?= $TOTAL_QUESTIONS ?>" />
												<input type="hidden" name="multisub_markperque<?=$srno?>" value="<?= $MARKS_PER_QUE ?>" />
												<input type="hidden" name="multisub_passingmark<?=$srno?>" value="<?= $PASSING_MARKS ?>" />
												<input type="hidden" name="multisub_exametime<?=$srno?>" value="<?= $EXAM_TIME ?>" />

												<input type="hidden" name="multisub_examtype<?=$srno?>" value="3" />
												<input type="hidden" name="multisub_examstatus<?=$srno?>" value="3" />
											
											<td><?= $SUBJECT_NAME ?></td>

											<td><input class="borderset" type="text" value="<?= $TOTAL_MARKS ?>" name="totaltheory<?=$srno?>" id="totaltheory<?=$srno?>" readonly="true"> </td>

											<td><input class="form-control" id="thobt<?=$srno?>" placeholder="Objective Obt Marks" type="number" name="thobt<?=$srno?>" maxlength="50" onkeyup="this.value = minmax(this.value, 0, 50); calTotalPerSub(<?=$srno?>)" value="<?= isset($_POST['thobt'])?$_POST['thobt']:''; ?>" ></td>

											<td><input class="borderset" type="text" value="50" name="totalpract<?=$srno?>" id="totalpract" readonly="true"></td>

											<td style="text-align:right;"> <input class="form-control" id="probt<?=$srno?>" placeholder="Practical Obt Marks" type="number" name="probt<?=$srno?>" maxlength="50" onkeyup="this.value = minmax(this.value, 0, 50); calTotalPerSub(<?=$srno?>)" value="<?= isset($_POST['probt'])?$_POST['probt']:''; ?>" ></td>                
											
											<td><input type="text" name="tot_obt<?=$srno?>" id="tot_obt<?=$srno?>" value="" class="form-control" style="width:100px;" readonly="true"></td>
											<td> <input type="text" name="tot_marks<?=$srno?>" id="tot_marks<?=$srno?>" value="100" class="form-control" style="width:100px;" readonly="true"></td> 
											</tr>
											<?php 
												$srno++;
												}
											}
											?>

											<tr>
												<th colspan="5" style="text-align:right;">Total Marks:
												</th>
													<th>
														<input type="hidden" name="totalSubjectCount" id="totalSubjectCount" value="<?= $srno-1 ?>">
													<input type="text" name="total_obt_marks" id="total_obt_marks" value="" class="form-control" style="width:100px;" readonly="true"></th>
													<th><input type="text" name="total_marks" id="total_marks" value="" class="form-control" style="width:100px;" readonly="true"></th>
											</tr>
											<tr>
												<th colspan="5" style="text-align:right;">Percentage:</th>
												<th colspan="2">
													<div class="col-sm-6"> 
														<input type="text" name="percentage" value="" class="form-control" readonly="true" id="percentage" style="width:100px;">
														<input type="hidden" name="result_status_multi" id="result_status_multi" value="">
														<input type="hidden" name="grade_multi" id="grade_multi" value="">
													</div>
													<div class="col-sm-3" style="margin-top: 10px;"> % </div>
												</th>	              
											</tr>
											</tbody>
										</table>

										</div>
										<hr>
										<div class="form-group col-md-12 <?= (isset($errors['result_status']))?'has-error':'' ?>" style="padding:25px">
											<input type="checkbox" id="conditon" name="conditon" value="conditon" required> I declare that I have verified the candidate appearing for Examination in all respect. 
											I had conducted the examination of this student under the supervision of subject experts. 
											All Details / Records of Exam conduction is available with me and I promise to provide the records immediately whenever demanded. 
											I am responsible and answerable for all queries, whenever arises.
										</div> 
							<hr>
						
						<div class="box-footer text-center">					 

							<input type="submit" class="btn btn-primary" name="save_multi_sub" value="Save Marks Multi Subjects" />&nbsp;&nbsp;&nbsp;			

							<a href="page.php?page=listPracticalExamResult" class="btn btn-danger" title="Cancel">Cancel</a>		

						</div>           

						       

							</div>	

						</div>
				<?php 
					}
				?>

				<?php if($TYPING_COURSE_ID!=''){ ?>
						<div class="row">
								
							<div class="col-md-6">
								<div class="table-responsive pt-3">
									<table id="order-listing" class="table">
									
										<tr>
											<th>Photo</th>
											<td><img src="<?= $PHOTO ?>" class='img img-responsive img-circle' style='width:50px; height:50px'></td>
										</tr>
										<tr>
											<th>Student Name</th>
											<td><?= $STUDENT_NAME ?></td>
										</tr>
										<tr>
											<th>Exam Name</th>
											<td><?php echo $abc = (isset($examstr['EXAM_TITLE']))?$examstr['EXAM_TITLE']:$EXAM_TITLE_MULTI_SUB; ?></td>
										</tr>
									</table>
								</div>
							</div>	
							<div class="col-md-12">
									<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');">				
									<div class="table-responsive pt-3">
										<table id="order-listing" class="table" style="font-size:12px; ">
										
											<tbody>
											<tr>
												<th>SUBLECT</th>
												<th>SPEED ( WPM )</th>
												<th>MAXIMUM MARKS</th>
												<th>MINIMUM MARKS</th>                   
												<th>MARKS OBTAINED</th>
												<th></th>
											</tr>											             
											<?php              	

											include('include/classes/coursetyping.class.php');
											
											$coursetyping = new coursetyping();
											
											$res1 = $coursetyping->get_course_subject_added_by_institute($TYPING_COURSE_ID,$institute_id, false);  
											if($res1!='')
											{
												$srno=1;
												while($data1 = $res1->fetch_assoc())
												{
													$INSTITUTE_SUBJECT_ID 	= $data1['INSTITUTE_SUBJECT_ID'];
													$TYPING_COURSE_ID 		= $data1['TYPING_COURSE_ID'];
													$SUBJECT_ID 			= $data1['SUBJECT_ID'];
													$SUBJECT_NAME 			= $data1['SUBJECT_NAME'];

													$EXAM_ID 				= $data1['EXAM_ID'];
													$COURSE_SUBJECT_ID 		= $data1['COURSE_SUBJECT_ID'];
													$TOTAL_MARKS 			= $data1['TOTAL_MARKS'];
													$MINIMUM_MARKS 			= $data1['MINIMUM_MARKS'];
													$TYPING_COURSE_SPEED 	= $data1['TYPING_COURSE_SPEED'];

											?>
											<tr>

												<input type="hidden" name="typing_studcoursedetailid<?=$srno?>" value="<?= $STUD_COURSE_DETAIL_ID ?>" />
												<input type="hidden" name="typing_studentid<?=$srno?>" value="<?= $STUDENT_ID ?>" />
												<input type="hidden" name="typing_instituteid<?=$srno?>" value="<?= $INSTITUTE_ID ?>" />											
												<input type="hidden" name="typing_instcourseid<?=$srno?>" value="<?= $INSTITUTE_COURSE_ID ?>" />
												<input type="hidden" name="typing_examtitle<?=$srno?>" value="<?= $EXAM_TITLE_MULTI_SUB ?>" />
												<input type="hidden" name="typing_examattempt<?=$srno?>" value="1" />
												<input type="hidden" name="typing_examid<?=$srno?>" value="<?= $EXAM_ID ?>" />
												<input type="hidden" name="typing_coursesubid<?=$srno?>" value="<?= $COURSE_SUBJECT_ID ?>" />
												<input type="hidden" name="typing_courseid<?=$srno?>" value="<?= $TYPING_COURSE_ID ?>" />

												<input type="hidden" name="typing_totalmarks<?=$srno?>" value="<?= $TOTAL_MARKS ?>" />
												<input type="hidden" name="typing_minimummarks<?=$srno?>" value="<?= $MINIMUM_MARKS ?>" />
												
												<input type="hidden" name="typing_examtype<?=$srno?>" value="3" />
												<input type="hidden" name="typing_examstatus<?=$srno?>" value="3" />
											
											<td><?= $SUBJECT_NAME ?></td>
											<td><?= $TYPING_COURSE_SPEED ?></td>

											<td><input class="borderset" type="text" value="<?= $TOTAL_MARKS ?>" name="totaltheory<?=$srno?>" id="totaltheory<?=$srno?>" readonly="true"> </td>

											<td><input class="borderset" type="text" value="<?= $MINIMUM_MARKS ?>" name="minimum_marks<?=$srno?>" id="minimum_marks<?=$srno?>" readonly="true"> </td>

											<td><input class="form-control" id="thobt<?=$srno?>" placeholder="MARKS OBTAINED" type="number" name="thobt<?=$srno?>" onkeyup="calTotalPerSubTyping(<?=$srno?>)" value="<?= isset($_POST['thobt'])?$_POST['thobt']:''; ?>" ></td>

											<td></td> 	

										
											</tr>
											<?php 
												$srno++;
												}
											}
											?>

											<tr>
												<th colspan="4" style="text-align:right;">Total Marks:
												</th>
													<th>
														<input type="hidden" name="totalSubjectCount" id="totalSubjectCount" value="<?= $srno-1 ?>">
														<input type="text" name="total_obt_marks" id="total_obt_marks" value="" class="form-control" style="width:100px;" readonly="true"> </th>
													<th>
														<input type="text" name="total_marks" id="total_marks" value="" class="form-control" style="width:100px;" readonly="true">
													</th>
											</tr>
											<tr>
												<th colspan="3" style="text-align:right;">Percentage:</th>
												<th colspan="3">
													<div class="col-sm-6"> 
														<input type="text" name="percentage" value="" class="form-control" readonly="true" id="percentage" style="width:100px;">
														<input type="hidden" name="result_status_multi" id="result_status_multi" value="">
														<input type="hidden" name="grade_multi" id="grade_multi" value="">
													</div>
													<div class="col-sm-3" style="margin-top: 10px;"> % </div>
												</th>	              
											</tr>
											</tbody>
										</table>

										</div>
										<hr>
										<div class="form-group col-md-12 <?= (isset($errors['result_status']))?'has-error':'' ?>" style="padding:25px">
											<input type="checkbox" id="conditon" name="conditon" value="conditon" required> I declare that I have verified the candidate appearing for Examination in all respect. 
											I had conducted the examination of this student under the supervision of subject experts. 
											All Details / Records of Exam conduction is available with me and I promise to provide the records immediately whenever demanded. 
											I am responsible and answerable for all queries, whenever arises.
										</div> 
							<hr>
						
						<div class="box-footer text-center">					 

							<input type="submit" class="btn btn-primary" name="save_typing" value="Save Marks" />&nbsp;&nbsp;&nbsp;			

							<a href="page.php?page=listPracticalExamResult" class="btn btn-danger" title="Cancel">Cancel</a>		

						</div>           

						       

							</div>	

						</div>
				<?php 
					}
				?>
            
           
          </form>
        </div>
      </div>
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
		else{ calPracticalResult(); return value;}
	}
</script>