<?php

	include('include/classes/exam.class.php');

	$exam = new exam();

	include('include/classes/exammultisub.class.php');

	$exammultisub = new exammultisub();

	include('include/classes/coursetypingexam.class.php');

	$coursetypingexam = new coursetypingexam();

	$result_id = isset($_GET['result'])?$_GET['result']:'';

	$result_multi_sub = isset($_GET['result_multi_sub'])?$_GET['result_multi_sub']:'';

	$result_typing = isset($_GET['result_typing'])?$_GET['result_typing']:'';
	

	$user_id= isset($_SESSION['user_id'])?$_SESSION['user_id']:'';			  

	$user_role = isset($_SESSION['user_role'])?$_SESSION['user_role']:'';

	$action		= isset($_POST['save'])?$_POST['save']:'';	
	$save_multi_sub	= isset($_POST['save_multi_sub'])?$_POST['save_multi_sub']:'';	
	$save_typing	= isset($_POST['save_typing'])?$_POST['save_typing']:'';	

	if($action!='')
	{
		$result= $exam->update_practical_exam_marks();
		$result = json_decode($result, true);
		//print_r($result);
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
		$result= $exammultisub->update_practical_exam_marks_multi_sub();
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

	if($save_typing!='')
	{	
		$result= $coursetypingexam->update_practical_exam_marks_typing();
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

	 /*$PHOTO = '../uploads/default_user.png';*/	
	 $PHOTO = '../uploads/default_user.png';	
	 if($result_id !='' && !empty($result_id)){ 
	 	$res 	= $exam->list_student_exam_results($result_id,'','','','');
		 if($res!='')
		 {
			 while($data = $res->fetch_assoc())
			 {	           
				extract($data);	
				$EXAM_TOTAL_MARKS=100;
				if($STUDENT_PHOTO!='')
					$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;

			 }
		 }
	}

?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"> Update Practical Exam Result </h4>          
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
				
				
				<?php if($result_id !='' && !empty($result_id)){ ?> 
					<div class="row">

						<div class="col-md-7">
							<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');" id="add_student">

								<div class="box box-primary">
							
									<p style="color:red; padding:5px; background-color:yellow; font-weight:900; font-size:14px;"> 
										Please Enter Subjects Before Updating Marks Other Wise No Subjects Printed On Marksheet And DITRP Charges For Updation For Marksheet With Courier Charges.
									</p>
								</div>

								<div class="box-body">			 

									<input type="hidden" name="exam_result_id" value="<?= $EXAM_RESULT_ID ?>" />	

									<input type="hidden" name="student_id" value="<?= $STUDENT_ID ?>" />				

									<input type="hidden" id="exam_total_que" name="exam_total_que" value="<?= $EXAM_TOTAL_QUE ?>" />

									<input type="hidden" name="exam_secrete_code" value="<?= $EXAM_SECRETE_CODE ?>" />

									<input type="hidden" id="exam_total_marks" name="exam_total_marks" value="<?= $EXAM_TOTAL_MARKS ?>" />

									<input type="hidden" id="exam_marks_per_que" name="exam_marks_per_que" value="<?= $EXAM_MARKS_PER_QUE ?>" />

										<div class="row">
											<div class="form-group col-md-6 <?= (isset($errors['marksobt']))?'has-error':'' ?>">
												<label>Total Objective Marks Obtained</label>
												<input class="form-control" id="marksobt" placeholder="Total  objective Marks obtained" type="number" name="marksobt" maxlength="50" onkeyup="this.value = minmax(this.value, 0, 50)" value="<?= isset($_POST['marksobt'])?$_POST['marksobt']:$MARKS_OBTAINED ?>" >
												<span class="help-block"><?= (isset($errors['marksobt']))?$errors['marksobt']:'' ?></span>
												<p> (Out Of 50 Marks)</p>
											</div>

											<div class="form-group col-md-6 <?= (isset($errors['marksobt']))?'has-error':'' ?>">
												<label>Total Practical Marks Obtained</label>
												<input class="form-control" id="marksobtpract" placeholder="Total practicle  Marks obtained" type="number" name="marksobtpract"  maxlength="50" onkeyup="this.value = minmax(this.value, 0, 50)" value="<?= isset($_POST['marksobtpract'])?$_POST['marksobtpract']:$PRACTICAL_MARKS ?>">

												<span class="help-block"><?= (isset($errors['marksobtpract']))?$errors['marksobtpract']:'' ?></span>
												<p> (Out Of 50 Marks)</p>						
											</div>

											<div class="form-group  col-md-6 <?= (isset($errors['marksobt']))?'has-error':'' ?>">
												<label>Subjects</label>
												<textarea class="form-control" placeholder="SUBJECT" type="text" name="subject" value="" ><?= isset($_POST['subject'])?$_POST['subject']:$SUBJECT ?></textarea>

												<span class="help-block"><?= (isset($errors['subject']))?$errors['subject']:'' ?></span>
												<p> (Please Enter Subjects)</p>		
											</div>

											<div class="form-group  col-md-6 <?= (isset($errors['marks_per']))?'has-error':'' ?>">
												<label>Percentage</label>
												<input class="form-control" id="marks_per" placeholder="Percentage" type="number" name="marks_per" value="<?= isset($_POST['marks_per'])?$_POST['marks_per']:$MARKS_PER ?>" onkeyup="calPracticalResult()" readonly="readonly" />
												<span class="help-block"><?= (isset($errors['marks_per']))?$errors['marks_per']:'' ?></span>
											</div>

											<div class="form-group  col-md-6 <?= (isset($errors['grade']))?'has-error':'' ?>">
												<label>Grade</label>
												<input class="form-control" id="grade" placeholder="Grade" type="text" name="grade" value="<?= isset($_POST['grade'])?$_POST['grade']:$GRADE ?>" readonly>
												<span class="help-block"><?= (isset($errors['grade']))?$errors['grade']:'' ?></span>
											</div>

											<div class="form-group  col-md-6 <?= (isset($errors['result_status']))?'has-error':'' ?>">
												<label>Result Status</label>
												<input class="form-control" id="result_status" placeholder="Result status" type="text" name="result_status" value="<?= isset($_POST['result_status'])?$_POST['result_status']:$RESULT_STATUS ?>" readonly>
												<span class="help-block"><?= (isset($errors['result_status']))?$errors['result_status']:'' ?></span>
											</div>					

											<div class="form-group  col-md-12 <?= (isset($errors['result_status']))?'has-error':'' ?>">
												<input type="checkbox" id="conditon" name="conditon" value="conditon" required> I declare that I have verified the candidate appearing for Examination in all respect. 
												I had conducted the examination of this student under the supervision of subject experts. 
												All Details / Records of Exam conduction is available with me and I promise to provide the records immediately whenever demanded. 
												I am responsible and answerable for all queries, whenever arises.
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

								<td><?= $EXAM_TITLE ?></td>

							</tr>

							<!--

							<tr>

								<th>Total Questions</th>

								<td><?= $EXAM_TOTAL_QUE ?></td>

							</tr>

							<tr>

								<th>Total Marks</th>

								<td><?= $EXAM_TOTAL_MARKS ?></td>

							</tr>

							<tr>

								<th>Total Passing Marks</th>

								<td><?= $EXAM_PASSING_MARKS ?></td>

							</tr>

							<tr>

								<th>Marks Per Question</th>

								<td><?= $EXAM_MARKS_PER_QUE ?></td>

							</tr> -->

						</table>

					</div>

					</div>

					</div>

  				<?php } ?>

				<?php 

				if($result_multi_sub !='' && !empty($result_multi_sub)){

					$res1 	= $exammultisub->list_student_exam_results_multi_sub($result_multi_sub,'','','','');

					if($res1!='')

					{
						while($data1 = $res1->fetch_assoc())
						{
						
							extract($data1);
							//print_r($data1);
							$course_details = $db->get_inst_course_info($INSTITUTE_COURSE_ID);
							$total_marks_obtained = $MARKS_OBTAINED;
							$course_id 		= $course_details['MULTI_SUB_COURSE_ID'];
							$course_type 		= $course_details['COURSE_TYPE'];

							$course = $db->get_course_detail_multi_sub($course_id,$course_type);
							$course_name 		= $data1['EXAM_TITLE'];

							if($STUDENT_PHOTO!='')

								$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;

						}

					} 
				?>
					<div class="row">
						<div class="col-md-6">

						<div class="box box-primary">
						

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

								<td><?= $course_name ?></td>

							</tr>

						</table>

						</div>

						</div>
						<div class="col-md-12">

						<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');">

						<div class="box box-primary">
							<div class="box-body">
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

								/*include('include/classes/coursemultisub.class.php');
								
								$coursemultisub = new coursemultisub();*/
								
								$res2 = $exammultisub->list_student_exam_results_multi_sub_list('',$STUDENT_ID,$INSTITUTE_ID,$STUD_COURSE_ID,'');  
								if($res2!='')
								{
									$srno=1;
									while($data2 = $res2->fetch_assoc())
									{
										$EXAM_RESULT_ID 		= $data2['EXAM_RESULT_ID'];
										$STUDENT_SUBJECT_ID 	= $data2['STUDENT_SUBJECT_ID'];
										$EXAM_ID 				= $data2['EXAM_ID'];
										$INSTITUTE_COURSE_ID 	= $data2['INSTITUTE_COURSE_ID'];
										$SUBJECT_NAME 			= $data2['SUBJECT_NAME'];
										$EXAM_TITLE 			= $data2['EXAM_TITLE'];
										$MARKS_OBTAINED 		= $data2['MARKS_OBTAINED'];
										$PRACTICAL_MARKS 		= $data2['PRACTICAL_MARKS'];
										$TOTAL_MARKS 			= $data2['TOTAL_MARKS'];

										$tot_obt = $MARKS_OBTAINED + $PRACTICAL_MARKS;

								?>
								<tr>
									<input type="hidden" name="multisub_examid<?=$srno?>" value="<?= $EXAM_RESULT_ID ?>" />
												
								<td><?= $SUBJECT_NAME ?></td>

								<td><input class="borderset" type="text" value="<?= $TOTAL_MARKS ?>" name="totaltheory<?=$srno?>" id="totaltheory<?=$srno?>" readonly="true"> </td>

								<td><input class="form-control" id="thobt<?=$srno?>" placeholder="Objective Obt Marks" type="number" name="thobt<?=$srno?>" maxlength="50" onkeyup="this.value = minmax(this.value, 0, 50); calTotalPerSub(<?=$srno?>)" value="<?= isset($_POST['thobt'])?$_POST['thobt']:$MARKS_OBTAINED; ?>" ></td>

								<td><input class="borderset" type="text" value="50" name="totalpract<?=$srno?>" id="totalpract" readonly="true"></td>

								<td style="text-align:right;"> <input class="form-control" id="probt<?=$srno?>" placeholder="Practical Obt Marks" type="number" name="probt<?=$srno?>" maxlength="50" onkeyup="this.value = minmax(this.value, 0, 50); calTotalPerSub(<?=$srno?>)" value="<?= isset($_POST['probt'])?$_POST['probt']:$PRACTICAL_MARKS; ?>" ></td>                
								
								<td><input type="text" name="tot_obt<?=$srno?>" id="tot_obt<?=$srno?>" value="<?= $tot_obt ?>" class="form-control" style="width:100px;" readonly="true"></td>
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
											<input type="hidden" name="multisub_examresultfinalid" id="multisub_examresultfinalid" value="<?= $EXAM_RESULT_FINAL_ID?>">

											<input type="hidden" name="totalSubjectCount" id="totalSubjectCount" value="<?= $srno-1 ?>">
										<input type="text" name="total_obt_marks" id="total_obt_marks" value="<?= $total_marks_obtained ?>" class="form-control" style="width:100px;" readonly="true"></th>
										<th><input type="text" name="total_marks" id="total_marks" value="<?= $EXAM_TOTAL_MARKS ?>" class="form-control" style="width:100px;" readonly="true"></th>
								</tr>
								<tr>
									<th colspan="5" style="text-align:right;">Percentage:</th>
									<th colspan="2">
										<div class="col-sm-6"> 
											<input type="text" name="percentage" value="<?= $MARKS_PER ?>" class="form-control" readonly="true" id="percentage" style="width:100px;">
											<input type="hidden" name="result_status_multi" id="result_status_multi" value="">
											<input type="hidden" name="grade_multi" id="grade_multi" value="">
										</div>
										<div class="col-sm-3" style="margin-top: 10px;"> % </div>
									</th>	              
								</tr>
								</tbody>
							

							</table>
										
							<div class="form-group <?= (isset($errors['result_status']))?'has-error':'' ?>">

								<div class="col-sm-12"> 

									<input type="checkbox" id="conditon" name="conditon" value="conditon" required> I declare that I have verified the candidate appearing for Examination in all respect. 
										I had conducted the examination of this student under the supervision of subject experts. 
										All Details / Records of Exam conduction is available with me and I promise to provide the records immediately whenever demanded. 
										I am responsible and answerable for all queries, whenever arises.

								</div>

								</div> 
								<hr>
							
							<div class="box-footer text-center">					 

								<input type="submit" class="btn btn-primary" name="save_multi_sub" value="Save Marks Multi Subjects" />&nbsp;&nbsp;&nbsp;			

								<a href="page.php?page=listPracticalExamResult" class="btn btn-danger" title="Cancel">Cancel</a>		

							</div>           
								</div>
						</div>

						</form>         

						</div>	

					</div>
				<?php 
				}
				?>


				<?php 

				if($result_typing !='' && !empty($result_typing)){

					$res1 	= $coursetypingexam->list_student_exam_results_typing($result_typing,'','','','');

					if($res1!='')

					{
						while($data1 = $res1->fetch_assoc())
						{
						
							extract($data1);
							//print_r($data1);
							$course_details = $db->get_inst_course_info($INSTITUTE_COURSE_ID);
							$total_marks_obtained = $MARKS_OBTAINED;
							$course_id 		= $course_details['TYPING_COURSE_ID'];
							$course_type 	= $course_details['COURSE_TYPE'];

							$course = $db->get_course_detail_multi_sub($course_id,$course_type);
							$course_name 		= $data1['EXAM_TITLE'];

							if($STUDENT_PHOTO!='')

								$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;

						}

					} 
				?>
					<div class="row">
						<div class="col-md-6">

						<div class="box box-primary">
						

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

								<td><?= $course_name ?></td>

							</tr>

						</table>

						</div>

						</div>
						<div class="col-md-12">

						<form role="form" class="form-validate" action="" method="post" enctype="multipart/form-data" onsubmit="pageLoaderOverlay('show');">

						<div class="box box-primary">
							<div class="box-body">
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

								/*include('include/classes/coursemultisub.class.php');
								
								$coursemultisub = new coursemultisub();*/
								
								$res2 = $coursetypingexam->list_student_exam_results_typing_list('',$STUDENT_ID,$INSTITUTE_ID,$STUD_COURSE_ID,'');  
								if($res2!='')
								{
									$srno=1;
									while($data2 = $res2->fetch_assoc())
									{
										$EXAM_RESULT_ID 		= $data2['EXAM_RESULT_ID'];
										$STUDENT_SUBJECT_ID 	= $data2['STUDENT_SUBJECT_ID'];
										$EXAM_ID 				= $data2['EXAM_ID'];
										$INSTITUTE_COURSE_ID 	= $data2['INSTITUTE_COURSE_ID'];
										$SUBJECT_NAME 			= $data2['SUBJECT_NAME'];
										$EXAM_TITLE 			= $data2['EXAM_TITLE'];
										$MARKS_OBTAINED 		= $data2['MARKS_OBTAINED'];
										$PRACTICAL_MARKS 		= $data2['PRACTICAL_MARKS'];
										$TOTAL_MARKS 			= $data2['TOTAL_MARKS'];
										$MINIMUM_MARKS 			= $data2['MINIMUM_MARKS'];
									    $TYPING_COURSE_SPEED 	= $data2['TYPING_COURSE_SPEED'];

								?>
								<tr>
									<input type="hidden" name="typing_examid<?=$srno?>" value="<?= $EXAM_RESULT_ID ?>" />
												
									<td><?= $SUBJECT_NAME ?></td>
									<td><?= $TYPING_COURSE_SPEED ?></td>

									<td><input class="borderset" type="text" value="<?= $TOTAL_MARKS ?>" name="totaltheory<?=$srno?>" id="totaltheory<?=$srno?>" readonly="true"> </td>

									<td><input class="borderset" type="text" value="<?= $MINIMUM_MARKS ?>" name="minimum_marks<?=$srno?>" id="minimum_marks<?=$srno?>" readonly="true"> </td>

									<td><input class="form-control" id="thobt<?=$srno?>" placeholder="Objective Obt Marks" type="number" name="thobt<?=$srno?>" maxlength="50" onkeyup="calTotalPerSubTyping(<?=$srno?>)" value="<?= isset($_POST['thobt'])?$_POST['thobt']:$MARKS_OBTAINED; ?>" ></td>
									
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
											<input type="hidden" name="typing_examresultfinalid" id="typing_examresultfinalid" value="<?= $EXAM_RESULT_FINAL_ID?>">

											<input type="hidden" name="totalSubjectCount" id="totalSubjectCount" value="<?= $srno-1 ?>">
										<input type="text" name="total_obt_marks" id="total_obt_marks" value="<?= $total_marks_obtained ?>" class="form-control" style="width:100px;" readonly="true"></th>
										<th><input type="text" name="total_marks" id="total_marks" value="<?= $EXAM_TOTAL_MARKS ?>" class="form-control" style="width:100px;" readonly="true"></th>
								</tr>
								<tr>
									<th colspan="3" style="text-align:right;">Percentage:</th>
									<th colspan="3">
										<div class="col-sm-6"> 
											<input type="text" name="percentage" value="<?= $MARKS_PER ?>" class="form-control" readonly="true" id="percentage" style="width:100px;"> 
											<input type="hidden" name="result_status_multi" id="result_status_multi" value="">
											<input type="hidden" name="grade_multi" id="grade_multi" value="">
										</div>
										<div class="col-sm-3" style="margin-top: 10px;"> % </div>
									</th>	              
								</tr>
								</tbody>
							

							</table>
										
							<div class="form-group <?= (isset($errors['result_status']))?'has-error':'' ?>">

								<div class="col-sm-12"> 

									<input type="checkbox" id="conditon" name="conditon" value="conditon" required> I declare that I have verified the candidate appearing for Examination in all respect. 
										I had conducted the examination of this student under the supervision of subject experts. 
										All Details / Records of Exam conduction is available with me and I promise to provide the records immediately whenever demanded. 
										I am responsible and answerable for all queries, whenever arises.

								</div>

								</div> 
								<hr>
							
							<div class="box-footer text-center">					 

								<input type="submit" class="btn btn-primary" name="save_typing" value="Save Marks" />&nbsp;&nbsp;&nbsp;			

								<a href="page.php?page=listPracticalExamResult" class="btn btn-danger" title="Cancel">Cancel</a>		

							</div>           
								</div>
						</div>

						</form>         

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