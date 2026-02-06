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

$res = $exam->filter_aicpe_exams('',$institute_id, '','3', '');

 

// $res 	= $exam->list_offline_downloaded_papers('','',$institute_id,'');

 ?>
<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
	  <div class="card">
	    <div class="card-body">
	      <h4 class="card-title"> List Offine Exams Results
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
					<th>#</th>
					<th>Photo</th>
					<th>Student</th>
					<th>Course</th>
					<th>Exam Status</th>
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
						//print_r($data);
						$PHOTO = '../uploads/default_user.png';
						//$PHOTO = '/default_user.png';

						if($STUDENT_PHOTO!='')

							$PHOTO = STUDENT_DOCUMENTS_PATH.'/'.$STUDENT_ID.'/'.$STUDENT_PHOTO;

						//$result_id = $exam->check_practical_result_added($STUD_COURSE_DETAIL_ID);

						

						$exam_result_info = $exam->list_student_exam_results('', $STUDENT_ID,'',$STUD_COURSE_DETAIL_ID, '');

						$exam_result_info_multi_sub = $exammultisub->list_student_exam_results_multi_sub('', $STUDENT_ID,'',$STUD_COURSE_DETAIL_ID, '');

						$exam_result_info_typing = $coursetypingexam->list_student_exam_results_typing('', $STUDENT_ID,'',$STUD_COURSE_DETAIL_ID, '');							

						/*$EXAM_STATUS_NAME='';

						if($EXAM_STATUS==2) $EXAM_STATUS_NAME= 'Pending'; 

						if($EXAM_STATUS==3) $EXAM_STATUS_NAME= 'Completed'; 

						*/

						$resultInfo='';

						if($exam_result_info!='')

						{

							$resultData 	= $exam_result_info->fetch_assoc();

							$EXAM_RESULT_ID = $resultData['EXAM_RESULT_ID'];

							$RESULT_STATUS  = $resultData['RESULT_STATUS'];

							$GRADE  		= $resultData['GRADE'];

							$MARKS_PER  	= $resultData['MARKS_PER'];

							$EXAM_RESULT_FINAL_ID = '';
							$EXAM_RESULT_TYPING_ID = '';

							

							$resultInfo 	= '<table class="table table-bordered" style="margin: 10px 0px;">

											<tr>

												<th>Marks </th>

												<th>Result </th>

												<th>Grade </th>											

											</tr>

											<tr>

												<td> '.$MARKS_PER.' % </td>

												<td> '.$RESULT_STATUS.' </td>

												<td> '.$GRADE.' </td>											

											</tr>										

										</table>';

						}

						if($exam_result_info_multi_sub!='')

						{

							$resultData 	= $exam_result_info_multi_sub->fetch_assoc();

							$EXAM_RESULT_FINAL_ID = $resultData['EXAM_RESULT_FINAL_ID'];

							$RESULT_STATUS  = $resultData['RESULT_STATUS'];

							$GRADE  		= $resultData['GRADE'];

							$MARKS_PER  	= $resultData['MARKS_PER'];

							$EXAM_RESULT_ID = '';
							$EXAM_RESULT_TYPING_ID = '';

							

							$resultInfo 	= '<table class="table table-bordered" style="margin: 10px 0px;">

											<tr>

												<th>Marks </th>

												<th>Result </th>

												<th>Grade </th>											

											</tr>

											<tr>

												<td> '.$MARKS_PER.' % </td>

												<td> '.$RESULT_STATUS.' </td>

												<td> '.$GRADE.' </td>											

											</tr>										

										</table>';

						}

						if($exam_result_info_typing!='')

						{

							$resultData 	= $exam_result_info_typing->fetch_assoc();

							$EXAM_RESULT_TYPING_ID = $resultData['EXAM_RESULT_FINAL_ID'];

							$RESULT_STATUS  = $resultData['RESULT_STATUS'];

							$GRADE  		= $resultData['GRADE'];

							$MARKS_PER  	= $resultData['MARKS_PER'];

							$EXAM_RESULT_ID = '';
							$EXAM_RESULT_FINAL_ID = '';

							

							$resultInfo 	= '<table class="table table-bordered" style="margin: 10px 0px;">

											<tr>

												<th>Marks </th>

												<th>Result </th>

												<th>Grade </th>											

											</tr>

											<tr>

												<td> '.$MARKS_PER.' % </td>

												<td> '.$RESULT_STATUS.' </td>

												<td> '.$GRADE.' </td>											

											</tr>										

										</table>';

						}

						// print_r($exam_result_info);
						// if($exam_result_info=='' && $exam_result_info_multi_sub=='' && $exam_result_info_typing=='' && $db->permission('add_practical_exam_result'))
						if (empty($exam_result_info) && empty($exam_result_info_multi_sub) && empty($exam_result_info_typing) && $db->permission('add_practical_exam_result')) 


						$action = '<a href="page.php?page=addPracticalExamResult&id='.$STUD_COURSE_DETAIL_ID.'" class="btn   btn-primary btn1">Add Result</a>';					

						elseif($db->permission('update_practical_exam_result')){

							$action = '<a href="page.php?page=updatePracticalExamResult&result='.$EXAM_RESULT_ID.'&result_multi_sub='.$EXAM_RESULT_FINAL_ID.'&result_typing='.$EXAM_RESULT_TYPING_ID.'" class="btn btn-primary btn1">Update Result</a>';	

						}

						$course_info	= $db->get_inst_course_info($INSTITUTE_COURSE_ID);
						
						$course_name = isset($course_info['COURSE_NAME_MODIFY'])?$course_info['COURSE_NAME_MODIFY']:'';
						//$course_name 	= $course_info['COURSE_NAME'];

						echo "<tr><td>$srno</td>
								<td><img src='$PHOTO' class='img img-responsive img-circle' style='width:50px; height:50px'></td>
								<td>$STUDENT_NAME</td>
								<td>$course_name</td>
								<td><strong>$EXAM_STATUS_NAME </strong>
								$resultInfo
								</td>
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
  