 <div class="content-wrapper">
 	<div class="col-lg-12 stretch-card">
 		<div class="card">
 			<div class="card-body">
 				<h4 class="card-title">List Award Categories
 					<a href="page.php?page=addAwardCategories" class="btn btn-primary" style="float: right">Add Award Categories</a>
 				</h4>

 				<div class="table-responsive pt-3">
 					<table id="order-listing" class="table">
 						<thead>
 							<tr>
 								<th>Sr.</th>
 								<th>Award Categories Name</th>
 								<th>Status</th>
 								<th>Action</th>
 							</tr>
 						</thead>
 						<tbody>
 							<?php
								include_once('include/classes/course.class.php');
								$course = new course();
								$res = $course->list_award('', '');
								if ($res != '') {
									$srno = 1;
									while ($data = $res->fetch_assoc()) {
										$AWARD_ID 		= $data['AWARD_ID'];
										$AWARD 	= $data['AWARD'];
										$ACTIVE			= $data['ACTIVE'];
										$CREATED_BY 	= $data['CREATED_BY'];
										$CREATED_ON 	= $data['CREATED_ON'];

										if ($ACTIVE == 1) $ACTIVE = 'Active';
										elseif ($ACTIVE == 0) $ACTIVE = 'In-Active';

										$PHOTO = '../uploads/default_user.png';
										$action = '';
										if ($db->permission('update_course'))
											$action = "<a href='page.php?page=updateAwardCategories&id=$AWARD_ID' class='btn btn-primary table-btn' title='Edit'><i class=' mdi mdi-grease-pencil'></i></a>";

										$action .= "<a href='javascript:void(0)' onclick='deleteAward($AWARD_ID)' class='btn btn-danger table-btn' title='Delete'><i class=' mdi mdi-delete'></i></a>";

										echo " <tr id='id" . $AWARD_ID . "'>						
									<td>$srno</td>	
									<td>$AWARD</td>
									<td id='status-$AWARD_ID'>$ACTIVE</td>
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