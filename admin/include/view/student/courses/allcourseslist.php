<div class="content-wrapper">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">List All Courses
				</h4>

				<div class="table-responsive pt-3">
					<table id="order-listing" class="table">
						<thead>
							<tr>
								<th>Sr.</th>
								<th>Course Code</th>
								<th>Course Name</th>
								<th>Duration</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							include_once('include/classes/course.class.php');
							$course = new course();
							$student_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
							$inst_id = $db->get_student_institute_id($student_id);
							$condition = '';
							$condition = " AND INSTITUTE_ID = $inst_id ";
							$res = $course->list_courses_not_purchase('', $condition);
							if ($res != '') {
								$srno = 1;
								while ($data = $res->fetch_assoc()) {
									extract($data);
									//print_r($data);
									$course_name =  $db->get_inst_course_name($INSTITUTE_COURSE_ID);
									$course_duration = $db->get_inst_course_duration($INSTITUTE_COURSE_ID);
									$course_code = $db->get_inst_course_code($INSTITUTE_COURSE_ID);
									//$EXAM_FEES 	= $data['EXAM_FEES'];

									$isCoursePurchase = $db->checkIsCoursePurchase($student_id, $INSTITUTE_COURSE_ID);


									$action = '';
									if ($db->permission('update_course'))

										//$action .= "<a href='coursesDetails&id=$INSTITUTE_COURSE_ID' class='btn btn-primary btn1' title='Details'>Details</a>&nbsp;&nbsp;";

										$action .= "<a href='page.php?page=purchaseCourse&id=$INSTITUTE_COURSE_ID' class='btn btn-success btn1' title='Purchase Now'>Purchase Now</a>";

									if ($isCoursePurchase == '') {
										echo " <tr id='id" . $INSTITUTE_COURSE_ID . "'>										
										<td>$srno</td>	
										<td>$course_code</td>									
										<td>$course_name</td>											
										<td>$course_duration</td>
										<td>$action</td>
										</tr>";
										$srno++;
									}
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