 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Courses
 					<a href="page.php?page=addCourse" class="btn btn-primary" style="float: right">Add Courses</a>
 				</h4>

 				<div class="table-responsive pt-3">
 					<table id="order-listing" class="table">
 						<thead>
 							<tr>
 								<th><label><input type='checkbox' value='1' id='selectall' class='edit-course'></label></th>
 								<th>Sr.</th>
 								<th>Course Code</th>
 								<th>Award</th>
 								<th>Course Name</th>
 								<th>Duration</th>
 								<th>Status</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php
								include_once('include/classes/course.class.php');
								$course = new course();
								$res = $course->list_courses('', '');
								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										$COURSE_ID 		= $data['COURSE_ID'];
										$COURSE_CODE 	= $data['COURSE_CODE'];
										$COURSE_DURATION = $data['COURSE_DURATION'];
										$COURSE_NAME 	= $data['COURSE_NAME'];
										$COURSE_FEES 	= $data['COURSE_FEES'];
										$COURSE_AWARD_NAME 	= $data['COURSE_AWARD_NAME'];
										$ACTIVE			= $data['ACTIVE'];
										$CREATED_BY 	= $data['CREATED_BY'];
										$CREATED_ON 	= $data['CREATED_ON'];
										//$EXAM_FEES 	= $data['EXAM_FEES'];
										if ($db->permission('update_course')) {
											if ($ACTIVE == 1)
												$ACTIVE = '<a href="javascript:void(0)" style="color:#3c763d" onclick="changeCoureStatus(' . $COURSE_ID . ',0)"><i class="fa fa-check"></i>Active</a>';
											elseif ($ACTIVE == 0)
												$ACTIVE = '<a href="javascript:void(0)" style="color:#f00" onclick="changeCoureStatus(' . $COURSE_ID . ',1)"><i class="fa fa-times"></i>In-Active</a> ';
										} else {
											if ($ACTIVE == 1)
												$ACTIVE = '<span style="color:#3c763d"><i class="fa fa-check"></i>Active</span>';
											elseif ($ACTIVE == 0)
												$ACTIVE = '<span style="color:#f00"><i class="fa fa-times"></i>In-Active</span> ';
										}

										/*if($ACTIVE==1) $ACTIVE= 'Active';
									elseif($ACTIVE==0) $ACTIVE= 'In-Active';
									*/
										$PHOTO = '../uploads/default_user.png';
										$action = '';
										if ($db->permission('update_course'))
											$action = "<a href='page.php?page=updateCourse&id=$COURSE_ID' class='btn btn-primary btn-sm' title='Edit'>Edit</a>";
										/*if($db->permission('delete_course'))
									$action .= "<a href='javascript:void(0)' onclick='deleteCourse($COURSE_ID)' class='btn btn-link' title='Delete'><i class=' fa fa-trash'></i></a>
									";
				*/
										echo " <tr id='course-id" . $COURSE_ID . "'>
											<td><label><input type='checkbox' name='check_course' value='$COURSE_ID' id='check-$COURSE_ID' class='' ></label></td>	
											<td>$srno</td>	
											<td>$COURSE_CODE</td>
											<td>$COURSE_AWARD_NAME</td>
											<td>$COURSE_NAME</td>											
											<td>$COURSE_DURATION</td>
											<td id='status-$COURSE_ID'>$ACTIVE</td>
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